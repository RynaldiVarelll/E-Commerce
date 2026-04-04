<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk merepresentasikan Produk dalam sistem.
 * Mencakup informasi seperti nama, deskripsi, harga, kategori, dan stok.
 */
class Product extends Model
{
    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'whatsapp_link',
        'image_url',
        'is_active',
        'slug',
        'quantity',
        'user_id',
    ];

    /**
     * Accessor untuk mendapatkan URL lengkap gambar produk.
     * Database cuma simpan nama file (contoh: sepatu.jpg)
     * URL lengkap dibentuk di sini
     */
    public function getImageUrlAttribute($value)
    {
        if (!$value) {
            return asset('images/no-image.png'); // fallback jika tidak ada gambar
        }

        return asset('storage/products/' . $value);
    }

    /**
     * Relasi ke model Category (Satu produk memiliki satu kategori).
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi ke model ProductImage (Satu produk bisa memiliki banyak gambar tambahan).
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Relasi ke model User (Pemilik produk/penjual).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
