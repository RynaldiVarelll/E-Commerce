<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar semua pesanan milik user yang sedang login.
     */
    public function index()
    {
        // Mengambil transaksi milik user, urutkan dari yang terbaru (latest)
        // Kita gunakan simplePaginate jika suatu saat transaksi user sangat banyak
        $orders = Transaction::where('user_id', Auth::id())
            ->with(['seller'])
            ->latest()
            ->get();

        return view('frontend.orders.index', compact('orders'));
    }

    /**
     * Menampilkan detail satu pesanan tertentu.
     */
    public function show(Transaction $transaction)
    {
        // 1. Keamanan: Pastikan user tidak bisa mengintip pesanan orang lain lewat URL
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
        }

        // 2. Eager Loading: Mengambil data relasi agar muncul di halaman detail
        // items.product = mengambil item transaksi beserta info produknya
        // shippingMethod = mengambil info kurir (JNE/J&T/dll)
        $transaction->load(['items.product', 'shippingMethod', 'user', 'seller']);

        return view('frontend.orders.show', compact('transaction'));
    }

    /**
     * Menghapus riwayat pesanan user.
     */
    public function destroy(Transaction $transaction)
    {
        // 1. Keamanan: Pastikan user tidak bisa menghapus pesanan orang lain
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus pesanan ini.');
        }

        // Hapus transaksi (jika menggunakan soft delete, ini aman; jika regular delete, data akan terhapus)
        $transaction->delete();

        return redirect()->route('orders.index')->with('success', 'Riwayat pesanan berhasil dihapus.');
    }
}