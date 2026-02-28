<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;

class ShippingMethodController extends Controller
{
    /**
     * Menampilkan daftar semua metode pengiriman.
     */
    public function index()
    {
        $shippingMethods = ShippingMethod::latest()->get();
        return view('admin.shipping-methods.index', compact('shippingMethods'));
    }

    /**
     * Menampilkan form tambah metode pengiriman.
     */
    public function create()
    {
        return view('admin.shipping-methods.create');
    }

    /**
     * Menyimpan metode pengiriman baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'service' => 'required|string|max:255',
            'cost' => 'required|numeric|min:0',
        ]);

        $data = $request->all();
        // 🔥 Pastikan is_active bernilai 1 jika dicentang, atau 0 jika tidak
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        ShippingMethod::create($data);

        return redirect()->route('admin.shipping-methods.index')
            ->with('success', 'Metode pengiriman berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit metode pengiriman.
     */
    public function edit(ShippingMethod $shippingMethod)
    {
        return view('admin.shipping-methods.edit', compact('shippingMethod'));
    }

    /**
     * Memperbarui data metode pengiriman di database.
     */
    public function update(Request $request, ShippingMethod $shippingMethod)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'service' => 'required|string|max:255',
            'cost' => 'required|numeric|min:0',
        ]);

        $data = $request->all();
        // 🔥 Logika yang sama untuk proses update status aktif/nonaktif
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        $shippingMethod->update($data);

        return redirect()->route('admin.shipping-methods.index')
            ->with('success', 'Metode pengiriman berhasil diperbarui!');
    }

    /**
     * Menghapus metode pengiriman.
     */
    public function destroy(ShippingMethod $shippingMethod)
    {
        $shippingMethod->delete();
        return back()->with('success', 'Metode pengiriman berhasil dihapus!');
    }
}