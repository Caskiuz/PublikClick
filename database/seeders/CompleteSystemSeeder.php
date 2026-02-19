<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;
use App\Models\Rank;
use App\Models\Ad;

class CompleteSystemSeeder extends Seeder
{
    public function run()
    {
        $this->seedRanks();
        $this->seedPackages();
        $this->seedAds();
    }

    private function seedRanks()
    {
        $ranks = [
            ['name' => 'Jade', 'min_referrals' => 0, 'max_referrals' => 2, 'mega_ads_monthly' => 10, 'referral_commission' => 100, 'mini_ads_daily' => 1, 'order' => 1],
            ['name' => 'Perla', 'min_referrals' => 3, 'max_referrals' => 5, 'mega_ads_monthly' => 25, 'referral_commission' => 200, 'mini_ads_daily' => 2, 'order' => 2],
            ['name' => 'Zafiro', 'min_referrals' => 6, 'max_referrals' => 9, 'mega_ads_monthly' => 50, 'referral_commission' => 300, 'mini_ads_daily' => 3, 'order' => 3],
            ['name' => 'Rubí', 'min_referrals' => 10, 'max_referrals' => 19, 'mega_ads_monthly' => 75, 'referral_commission' => 400, 'mini_ads_daily' => 4, 'order' => 4],
            ['name' => 'Esmeralda', 'min_referrals' => 20, 'max_referrals' => 25, 'mega_ads_monthly' => 125, 'referral_commission' => 400, 'mini_ads_daily' => 5, 'order' => 5],
            ['name' => 'Diamante', 'min_referrals' => 26, 'max_referrals' => 30, 'mega_ads_monthly' => 150, 'referral_commission' => 400, 'mini_ads_daily' => 5, 'order' => 6],
            ['name' => 'Diamante Azul', 'min_referrals' => 31, 'max_referrals' => 35, 'mega_ads_monthly' => 175, 'referral_commission' => 400, 'mini_ads_daily' => 5, 'order' => 7],
            ['name' => 'Diamante Negro', 'min_referrals' => 36, 'max_referrals' => 39, 'mega_ads_monthly' => 190, 'referral_commission' => 400, 'mini_ads_daily' => 5, 'order' => 8],
            ['name' => 'Diamante Corona', 'min_referrals' => 40, 'max_referrals' => null, 'mega_ads_monthly' => 200, 'referral_commission' => 400, 'mini_ads_daily' => 5, 'order' => 9],
        ];

        foreach ($ranks as $rank) {
            Rank::updateOrCreate(['name' => $rank['name']], $rank);
        }

        $this->command->info('✓ Rangos creados');
    }

    private function seedPackages()
    {
        $packages = [
            [
                'name' => 'Básico $25',
                'description' => 'Paquete inicial - Categoría Jade',
                'price_usd' => 25,
                'price_cop' => 95000,
                'daily_ads' => 5,
                'click_earnings' => 400,
                'banner_views' => 20000,
                'post_views' => 9000,
                'ptc_views' => 120,
                'main_ad_value' => 400,
                'mini_ad_value' => 83.33,
                'mini_ads_count' => 4,
                'benefits' => [],
                'is_active' => true
            ],
            [
                'name' => 'Básico $50',
                'description' => 'Paquete intermedio - Categoría Jade',
                'price_usd' => 50,
                'price_cop' => 190000,
                'daily_ads' => 5,
                'click_earnings' => 600,
                'banner_views' => 40000,
                'post_views' => 20000,
                'ptc_views' => 250,
                'main_ad_value' => 600,
                'mini_ad_value' => 425,
                'mini_ads_count' => 4,
                'benefits' => [],
                'is_active' => true
            ],
            [
                'name' => 'Avanzado $100',
                'description' => 'Paquete avanzado - Categoría Esmeralda temporal',
                'price_usd' => 100,
                'price_cop' => 380000,
                'daily_ads' => 5,
                'click_earnings' => 1120,
                'banner_views' => 80000,
                'post_views' => 40000,
                'ptc_views' => 500,
                'main_ad_value' => 1120,
                'mini_ad_value' => 100,
                'mini_ads_count' => 4,
                'benefits' => [],
                'is_active' => true
            ],
            [
                'name' => 'Premium $150',
                'description' => 'Paquete premium - Categoría Esmeralda temporal',
                'price_usd' => 150,
                'price_cop' => 570000,
                'daily_ads' => 5,
                'click_earnings' => 1600,
                'banner_views' => 120000,
                'post_views' => 60000,
                'ptc_views' => 750,
                'main_ad_value' => 1600,
                'mini_ad_value' => 600,
                'mini_ads_count' => 8,
                'benefits' => [],
                'is_active' => true
            ]
        ];

        foreach ($packages as $package) {
            Package::updateOrCreate(
                ['price_usd' => $package['price_usd']], 
                $package
            );
        }

        $this->command->info('✓ Paquetes creados');
    }

    private function seedAds()
    {
        $ads = [
            [
                'title' => 'Anuncio Demo 1',
                'description' => 'Este es un anuncio de demostración',
                'url' => 'https://example.com',
                'image_url' => '/imagenes/IMG1.jpeg',
                'click_value' => 400,
                'is_active' => true
            ],
            [
                'title' => 'Anuncio Demo 2',
                'description' => 'Segundo anuncio de demostración',
                'url' => 'https://example.com',
                'image_url' => '/imagenes/IMG2.jpeg',
                'click_value' => 400,
                'is_active' => true
            ],
            [
                'title' => 'Anuncio Demo 3',
                'description' => 'Tercer anuncio de demostración',
                'url' => 'https://example.com',
                'image_url' => '/imagenes/IMG3.jpeg',
                'click_value' => 400,
                'is_active' => true
            ],
            [
                'title' => 'Anuncio Demo 4',
                'description' => 'Cuarto anuncio de demostración',
                'url' => 'https://example.com',
                'image_url' => '/imagenes/IMG4.jpeg',
                'click_value' => 400,
                'is_active' => true
            ],
            [
                'title' => 'Anuncio Demo 5',
                'description' => 'Quinto anuncio de demostración',
                'url' => 'https://example.com',
                'image_url' => '/imagenes/IMG5.jpeg',
                'click_value' => 400,
                'is_active' => true
            ]
        ];

        foreach ($ads as $ad) {
            Ad::updateOrCreate(
                ['title' => $ad['title']], 
                $ad
            );
        }

        $this->command->info('✓ Anuncios demo creados');
    }
}
