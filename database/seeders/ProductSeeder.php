<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // ================================
        // USER SELLER (ADMIN)
        // ================================
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        // ================================
        // USER CUSTOMER
        // ================================
        User::create([
            'name' => 'rynald',
            'email' => 'rynald@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'customer'
        ]);

        // ================================
        // CATEGORY
        // ================================
        $elektronik = Category::create([
            'name' => 'Elektronik',
            'slug' => 'elektronik'
        ]);

        $fashion = Category::create([
            'name' => 'Fashion',
            'slug' => 'fashion'
        ]);

        // ================================
        // PRODUCT
        // ================================
        foreach ([$elektronik, $fashion] as $cat) {
            foreach (range(1, 3) as $i) {

                $product = Product::create([
                    'category_id' => $cat->id,
                    'name' => "{$cat->name} Produk $i",
                    'slug' => strtolower($cat->name) . "-produk-$i",
                    'description' => "Deskripsi produk {$cat->name} ke-$i.",
                    'price' => 100000 + ($i * 50000),
                    'whatsapp_link' => 'https://wa.me/6281234567890',
                    'is_active' => true,
                    'image_url' => 'https://via.placeholder.com/300'
                ]);

                // Gambar tambahan
                foreach (range(1, 2) as $j) {
                    $product->images()->create([
                        'image_url' => "https://via.placeholder.com/300?text=Gambar+$j",
                        'position' => $j
                    ]);
                }
            }
        }
    }
}