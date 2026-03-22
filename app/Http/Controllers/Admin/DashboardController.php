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

        // Grafik pendapatan 7 hari terakhir
        $revenueData = (clone $transactionQuery)
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

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
            'revenueData' => $revenueData,
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

