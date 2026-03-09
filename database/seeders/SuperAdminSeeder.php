<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan tidak duplikat
        if (!User::where('email', 'superadmin@example.com')->exists()) {
            User::create([
                'name' => 'Super Administrator',
                'email' => 'superadmin@example.com',
                'password' => Hash::make('password123'),
                'role' => 'super_admin',
                'email_verified_at' => now(),
            ]);
        }
    }
}
