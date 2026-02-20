<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
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
    ];

    /*
    |--------------------------------------------------------------------------
    | Accessor: image_url
    |--------------------------------------------------------------------------
    | Database cuma simpan nama file (contoh: sepatu.jpg)
    | URL lengkap dibentuk di sini
    */
    public function getImageUrlAttribute($value)
    {
        if (!$value) {
            return asset('images/no-image.png'); // optional fallback
        }

        return asset('storage/products/' . $value);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
}
