<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Admin',
            'email' => 'admin@publiclik.com',
            'password' => Hash::make('admin123'),
            'referral_code' => 'ADMIN001',
            'is_admin' => true,
            'is_active' => true,
            'wallet_balance' => 0
        ]);
        
        // Test User
        User::create([
            'name' => 'Usuario Demo',
            'email' => 'demo@publiclik.com',
            'password' => Hash::make('demo123'),
            'referral_code' => strtoupper(Str::random(8)),
            'is_admin' => false,
            'is_active' => true,
            'wallet_balance' => 0
        ]);
    }
}
