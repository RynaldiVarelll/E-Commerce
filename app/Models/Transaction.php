<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TransactionItem;
use App\Models\ShippingMethod;
use App\Models\User;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'user_id',
        'shipping_method_id', 
        'total_amount',
        'status',
        'payment_method',
        'invoice_code',
        // Optional: tambahkan ini jika ingin menyimpan nama ekspedisi secara permanen
        // 'shipping_name', 
        // 'shipping_cost',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'created_at' => 'datetime', // Memastikan jam bisa dimanipulasi dengan format()
    ];

    /*
    |--------------------------------------------------------------------------
    | BOOT - Auto Generate Invoice Code
    |--------------------------------------------------------------------------
    */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            // Menggunakan timezone yang sudah kita set di config/app.php
            $datePart = now()->format('Ymd');
            $randomPart = strtoupper(substr(uniqid(), -4));
            $transaction->invoice_code = "INV-{$datePart}-{$randomPart}";
        });
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // Menghubungkan ke tabel user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault([
            'name' => 'Guest User'
        ]);
    }

    // 🔥 PENTING: Relasi ke Shipping Method
    // withDefault() mencegah error jika metode pengiriman terhapus dari database admin
    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method_id')->withDefault([
            'name' => 'Metode Pengiriman Tidak Ditemukan',
            'service' => '-'
        ]);
    }

    // Relasi ke item belanjaan
    public function items()
    {
        return $this->hasMany(TransactionItem::class, 'transaction_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES (Untuk filter data lebih mudah)
    |--------------------------------------------------------------------------
    */

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /*
    |--------------------------------------------------------------------------
    | CUSTOM HELPERS
    |--------------------------------------------------------------------------
    */

    // Helper untuk hapus transaksi beserta itemnya agar database bersih
    public function deleteWithItems()
    {
        $this->items()->delete();
        return $this->delete();
    }
}