<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = ['product_id', 'image_url', 'position'];

    public function getImageUrlAttribute($value)
    {
        if (!$value) {
            return asset('images/no-image.png');
        }

        return asset('storage/product_images/' . $value);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
