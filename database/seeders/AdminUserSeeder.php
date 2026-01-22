<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrador PubliClick',
            'email' => 'admin@publiclik.com',
            'password' => Hash::make('Admin123!'),
            'referral_code' => 'ADMIN' . strtoupper(Str::random(6)),
            'wallet_balance' => 0,
            'is_active' => true,
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);
    }
}
