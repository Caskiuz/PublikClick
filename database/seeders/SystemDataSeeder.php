<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rank;
use App\Models\Package;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SystemDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Ranks
        $this->seedRanks();
        
        // Seed Packages
        $this->seedPackages();
        
        // Create admin user
        $this->createAdminUser();
        
        $this->command->info('Sistema inicializado correctamente con rangos, paquetes y usuario admin.');
    }
    
    private function seedRanks()
    {
        $ranks = [
            [
                'name' => 'Jade',
                'min_referrals' => 0,
                'max_referrals' => 2,
                'mega_ads_monthly' => 10,
                'referral_commission' => 100.00,
                'mini_ads_daily' => 1,
                'order' => 1
            ],
            [
                'name' => 'Perla',
                'min_referrals' => 3,
                'max_referrals' => 5,
                'mega_ads_monthly' => 25,
                'referral_commission' => 200.00,
                'mini_ads_daily' => 2,
                'order' => 2
            ],
            [
                'name' => 'Zafiro',
                'min_referrals' => 6,
                'max_referrals' => 9,
                'mega_ads_monthly' => 50,
                'referral_commission' => 300.00,
                'mini_ads_daily' => 3,
                'order' => 3
            ],
            [
                'name' => 'Rubí',
                'min_referrals' => 10,
                'max_referrals' => 19,
                'mega_ads_monthly' => 75,
                'referral_commission' => 400.00,
                'mini_ads_daily' => 4,
                'order' => 4
            ],
            [
                'name' => 'Esmeralda',
                'min_referrals' => 20,
                'max_referrals' => 25,
                'mega_ads_monthly' => 125,
                'referral_commission' => 400.00,
                'mini_ads_daily' => 5,
                'order' => 5
            ],
            [
                'name' => 'Diamante',
                'min_referrals' => 26,
                'max_referrals' => 30,
                'mega_ads_monthly' => 150,
                'referral_commission' => 400.00,
                'mini_ads_daily' => 5,
                'order' => 6
            ],
            [
                'name' => 'Diamante Azul',
                'min_referrals' => 31,
                'max_referrals' => 35,
                'mega_ads_monthly' => 175,
                'referral_commission' => 400.00,
                'mini_ads_daily' => 5,
                'order' => 7
            ],
            [
                'name' => 'Diamante Negro',
                'min_referrals' => 36,
                'max_referrals' => 39,
                'mega_ads_monthly' => 190,
                'referral_commission' => 400.00,
                'mini_ads_daily' => 5,
                'order' => 8
            ],
            [
                'name' => 'Diamante Corona',
                'min_referrals' => 40,
                'max_referrals' => null,
                'mega_ads_monthly' => 200,
                'referral_commission' => 400.00,
                'mini_ads_daily' => 5,
                'order' => 9
            ]
        ];

        foreach ($ranks as $rank) {
            Rank::updateOrCreate(['name' => $rank['name']], $rank);
        }
        
        $this->command->info('✅ Rangos creados correctamente');
    }
    
    private function seedPackages()
    {
        $packages = [
            [
                'name' => 'Básico $25',
                'description' => 'Paquete inicial perfecto para comenzar tu aventura publicitaria',
                'price_usd' => 25.00,
                'price_cop' => 100000,
                'daily_ads' => 5,
                'click_earnings' => 400.00,
                'benefits' => json_encode(['banner_views' => 20000, 'post_views' => 9000, 'ptc_views' => 120]),
                'banner_views' => 20000,
                'post_views' => 9000,
                'ptc_views' => 120,
                'main_ad_value' => 400.00,
                'mini_ad_value' => 83.33,
                'mini_ads_count' => 4,
                'is_active' => true
            ],
            [
                'name' => 'Básico $50',
                'description' => 'Paquete intermedio con mejores ganancias y más visibilidad',
                'price_usd' => 50.00,
                'price_cop' => 200000,
                'daily_ads' => 5,
                'click_earnings' => 600.00,
                'benefits' => json_encode(['banner_views' => 40000, 'post_views' => 20000, 'ptc_views' => 250]),
                'banner_views' => 40000,
                'post_views' => 20000,
                'ptc_views' => 250,
                'main_ad_value' => 600.00,
                'mini_ad_value' => 425.00,
                'mini_ads_count' => 4,
                'is_active' => true
            ],
            [
                'name' => 'Avanzado $100',
                'description' => 'Paquete avanzado con rango temporal Esmeralda y altas ganancias',
                'price_usd' => 100.00,
                'price_cop' => 400000,
                'daily_ads' => 5,
                'click_earnings' => 1120.00,
                'benefits' => json_encode(['banner_views' => 80000, 'post_views' => 40000, 'ptc_views' => 500]),
                'banner_views' => 80000,
                'post_views' => 40000,
                'ptc_views' => 500,
                'main_ad_value' => 1120.00,
                'mini_ad_value' => 100.00,
                'mini_ads_count' => 4,
                'is_active' => true
            ],
            [
                'name' => 'Avanzado $150',
                'description' => 'Paquete premium con máximas ganancias y beneficios exclusivos',
                'price_usd' => 150.00,
                'price_cop' => 600000,
                'daily_ads' => 5,
                'click_earnings' => 1600.00,
                'benefits' => json_encode(['banner_views' => 120000, 'post_views' => 60000, 'ptc_views' => 750]),
                'banner_views' => 120000,
                'post_views' => 60000,
                'ptc_views' => 750,
                'main_ad_value' => 1600.00,
                'mini_ad_value' => 600.00,
                'mini_ads_count' => 8,
                'is_active' => true
            ]
        ];

        foreach ($packages as $package) {
            Package::updateOrCreate(
                ['price_usd' => $package['price_usd']], 
                $package
            );
        }
        
        $this->command->info('✅ Paquetes publicitarios creados correctamente');
    }
    
    private function createAdminUser()
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@publiclik.com'],
            [
                'name' => 'Administrador',
                'email' => 'admin@publiclik.com',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'is_active' => true,
                'email_verified_at' => now()
            ]
        );
        
        $this->command->info('✅ Usuario administrador creado: admin@publiclik.com / admin123');
    }
}