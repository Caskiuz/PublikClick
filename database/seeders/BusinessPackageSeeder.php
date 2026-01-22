<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;

class BusinessPackageSeeder extends Seeder
{
    public function run(): void
    {
        Package::truncate();
        
        $packages = [
            [
                'name' => 'Básico $25',
                'description' => 'Paquete inicial para comenzar',
                'price_cop' => 91742.24,
                'price_usd' => 25.00,
                'daily_ads' => 5,
                'banner_views' => 20000,
                'post_views' => 9000,
                'ptc_views' => 120,
                'click_earnings' => 0.10,
                'main_ad_value' => 400.00,
                'mini_ad_value' => 83.33,
                'mini_ads_count' => 4,
                'benefits' => json_encode(['5 anuncios diarios', 'Comisiones nivel 1', 'Soporte básico']),
                'is_active' => true
            ],
            [
                'name' => 'Básico $50',
                'description' => 'Mayor visibilidad y ganancias',
                'price_cop' => 183484.48,
                'price_usd' => 50.00,
                'daily_ads' => 5,
                'banner_views' => 40000,
                'post_views' => 20000,
                'ptc_views' => 250,
                'click_earnings' => 0.15,
                'main_ad_value' => 600.00,
                'mini_ad_value' => 425.00,
                'mini_ads_count' => 4,
                'benefits' => json_encode(['5 anuncios diarios', 'Comisiones 2 niveles', 'Soporte prioritario']),
                'is_active' => true
            ],
            [
                'name' => 'Avanzado $100',
                'description' => 'Paquete profesional con más beneficios',
                'price_cop' => 366968.96,
                'price_usd' => 100.00,
                'daily_ads' => 5,
                'banner_views' => 80000,
                'post_views' => 40000,
                'ptc_views' => 500,
                'click_earnings' => 0.20,
                'main_ad_value' => 1120.00,
                'mini_ad_value' => 100.00,
                'mini_ads_count' => 4,
                'benefits' => json_encode(['5 anuncios diarios', 'Comisiones 3 niveles', 'Soporte VIP']),
                'is_active' => true
            ],
            [
                'name' => 'Avanzado $150',
                'description' => 'Máximo nivel de ganancias',
                'price_cop' => 550453.44,
                'price_usd' => 150.00,
                'daily_ads' => 5,
                'banner_views' => 120000,
                'post_views' => 60000,
                'ptc_views' => 750,
                'click_earnings' => 0.25,
                'main_ad_value' => 1600.00,
                'mini_ad_value' => 600.00,
                'mini_ads_count' => 8,
                'benefits' => json_encode(['5 anuncios diarios', 'Comisiones 3 niveles', 'Mini-anuncios extra', 'Soporte premium']),
                'is_active' => true
            ]
        ];

        foreach ($packages as $package) {
            Package::create($package);
        }
    }
}