<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShippingMethod;

class ShippingMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            ['name' => 'JNE', 'service' => 'REG', 'cost' => 20000],
            ['name' => 'J&T', 'service' => 'EZ', 'cost' => 15000],
            ['name' => 'SiCepat', 'service' => 'BEST', 'cost' => 18000],
            ['name' => 'AnterAja', 'service' => 'Next Day', 'cost' => 25000],
        ];

        foreach ($methods as $method) {
            ShippingMethod::updateOrCreate(
                [
                    'name' => $method['name'],
                    'service' => $method['service'],
                ],
                [
                    'cost' => $method['cost'],
                    'is_active' => true,
                ]
            );
        }
    }
}