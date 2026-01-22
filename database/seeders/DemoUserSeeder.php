<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Usuario Demo',
            'email' => 'demo@publiclik.com',
            'password' => bcrypt('123456'),
            'referral_code' => 'DEMO2026',
            'wallet_balance' => 10.50,
            'is_active' => true
        ]);
        
        echo "Usuario demo creado: demo@publiclik.com / 123456\n";
    }
}