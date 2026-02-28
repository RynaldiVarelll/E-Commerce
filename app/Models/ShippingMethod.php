<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction;

class ShippingMethod extends Model
{
    use HasFactory;

    /**
     * Nama table (optional kalau nama table sudah sesuai plural)
     */
    protected $table = 'shipping_methods';

    /**
     * Kolom yang boleh diisi
     */
    protected $fillable = [
        'name',
        'service',
        'cost',
        'is_active',
    ];

    /**
     * Casting tipe data
     */
    protected $casts = [
        'cost' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Scope: hanya ambil shipping aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Relasi: 1 ShippingMethod punya banyak Transaction
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'shipping_method_id');
    }
}