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
        // Statistik utama
        $totalUsers = User::count();
        $totalTransactions = Transaction::count();
        $totalRevenue = Transaction::sum('total_amount');

        // Statistik berdasarkan status transaksi
        $pendingCount = Transaction::where('status', 'pending')->count();
        $completedCount = Transaction::where('status', 'completed')->count();
        $failedCount = Transaction::where('status', 'failed')->count();

        // Produk
        $totalProducts = Product::count();
        $latestProducts = Product::latest()->take(5)->get();

        // Transaksi terbaru
        $recentTransactions = Transaction::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Grafik pendapatan 7 hari terakhir
        $revenueData = Transaction::selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
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

