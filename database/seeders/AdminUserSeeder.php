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
            'password' => Hash::make('password'),
            'referral_code' => 'ADMIN001',
            'whatsapp' => '+57 300 000 0000',
            'country' => 'CO',
            'state' => 'Bogotá',
            'city' => 'Bogotá',
            'is_active' => true,
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);
    }
}
