<?php

namespace App\Http\Controllers\Admin;
use Carbon\Carbon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;
use App\Models\TransactionItems;
use App\Models\Product;
class DashboardController extends Controller
{
     public function index()
    {
        $user = auth()->user();

        // Base Query instances handling Super Admin vs Seller Logic
        $transactionQuery = Transaction::query();
        $productQuery = Product::query();

        if (!$user->isSuperAdmin()) {
            $transactionQuery->where('seller_id', $user->id);
            $productQuery->where('user_id', $user->id);
        }

        // Statistik utama
        $totalUsers = User::count(); // Tetap semua jika mau tau jumlah user (atau bisa dihilangkan di view jika bukan super admin)
        $totalTransactions = (clone $transactionQuery)->count();
        $totalRevenue = (clone $transactionQuery)->sum('total_amount');

        // Statistik berdasarkan status transaksi
        $pendingCount = (clone $transactionQuery)->where('status', 'pending')->count();
        $completedCount = (clone $transactionQuery)->where('status', 'completed')->count();
        $failedCount = (clone $transactionQuery)->where('status', 'failed')->count();

        // Produk
        $totalProducts = (clone $productQuery)->count();
        $latestProducts = (clone $productQuery)->latest()->take(5)->get();

        // Transaksi terbaru
        $recentTransactions = (clone $transactionQuery)
            ->with(['user', 'seller'])
            ->latest()
            ->take(10)
            ->get();

        // Generate 7 days labels
        $dateLabels = collect();
        for ($i = 6; $i >= 0; $i--) {
            $dateLabels->put(Carbon::now()->subDays($i)->format('Y-m-d'), 0);
        }

        // Grafik pendapatan 7 hari terakhir (Per Seller)
        $revenueDatasets = [];
        
        if ($user->isSuperAdmin()) {
            // Get all sellers (role: admin)
            $allSellers = User::where('role', 'admin')->get();
            
            // Prepare a baseline map of zeros for each seller
            $sellerMaps = [];
            foreach ($allSellers as $seller) {
                $sellerMaps[$seller->id] = [
                    'name' => $seller->name,
                    'data' => $dateLabels->toArray()
                ];
            }

            // Also prepare an "Unknown Seller" map just in case
            $sellerMaps['unknown'] = [
                'name' => 'Unknown Seller',
                'data' => $dateLabels->toArray()
            ];

            // Fetch transaction data
            $txData = Transaction::with('seller')
                ->selectRaw('DATE(created_at) as date, seller_id, SUM(total_amount) as total')
                ->where('status', 'completed')
                ->where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
                ->groupBy('date', 'seller_id')
                ->get();
                
            $groupedBySeller = $txData->groupBy('seller_id');
            foreach ($groupedBySeller as $sellerId => $records) {
                $mapKey = isset($sellerMaps[$sellerId]) ? $sellerId : 'unknown';
                
                foreach ($records as $record) {
                    $sellerMaps[$mapKey]['data'][$record->date] += $record->total;
                }
            }
            
            // Build the datasets array
            foreach ($sellerMaps as $key => $map) {
                // If the unknown map is empty, skip it
                if ($key === 'unknown' && array_sum(array_values($map['data'])) == 0) {
                    continue;
                }
                
                $revenueDatasets[] = [
                    'label' => $map['name'],
                    'data' => array_values($map['data'])
                ];
            }
        } else {
            $queriedRevenue = (clone $transactionQuery)
                ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
                ->where('status', 'completed')
                ->where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('total', 'date');

            $revenueData = $dateLabels->merge($queriedRevenue);
            $revenueDatasets[] = [
                'label' => 'Pendapatan Anda',
                'data' => array_values($revenueData->toArray())
            ];
        }

        $chartLabels = array_keys($dateLabels->toArray());

        // Chat Unread Count
        $unreadChatCount = \App\Models\Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();

        // Data pengguna (Hanya untuk Super Admin, pendaftaran 7 hari terakhir)
        $userData = collect();
        if ($user->isSuperAdmin()) {
            $queriedUsers = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('count', 'date');
            
            $userData = $dateLabels->merge($queriedUsers);
        }

        return view('admin.dashboard.index', [
            'totalUsers' => $totalUsers,
            'totalTransactions' => $totalTransactions,
            'totalRevenue' => $totalRevenue,
            'pendingCount' => $pendingCount,
            'completedCount' => $completedCount,
            'failedCount' => $failedCount,
            'totalProducts' => $totalProducts,
            'latestProducts' => $latestProducts,
            'recentTransactions' => $recentTransactions,
            'revenueDatasets' => $revenueDatasets,
            'chartLabels' => $chartLabels,
            'unreadChatCount' => $unreadChatCount,
            'userData' => $userData,
        ]);
    }

    public function updateStatus(\App\Models\Transaction $transaction, $status)
    {
        // 🚨 KEAMANAN: Super Admin tidak mengelola status transaksi, itu tugas Seller
        if (auth()->user()->isSuperAdmin()) {
            abort(403, 'Aksi ini hanya diperbolehkan untuk Seller (Admin).');
        }

        // Cek apakah seller ini pemilik produk di transaksi tersebut
        if ($transaction->seller_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak untuk mengelola transaksi ini.');
        }

        // Validasi status yang diperbolehkan
        $allowedStatuses = ['pending', 'confirmed', 'shipped', 'completed', 'cancelled'];
    
    if (in_array($status, $allowedStatuses)) {
        $transaction->update(['status' => $status]);
        
        return redirect()->route('admin.dashboard')
            ->with('success', "Status transaksi #{$transaction->id} berhasil diupdate menjadi {$status}!");
    }

    return redirect()->route('admin.dashboard')
        ->with('error', 'Status tidak valid!');
}
}

