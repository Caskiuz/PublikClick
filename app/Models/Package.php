<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price_usd',
        'price_cop',
        'daily_ads',
        'click_earnings',
        'banner_views',
        'post_views',
        'ptc_views',
        'main_ad_value',
        'mini_ad_value',
        'mini_ads_count',
        'benefits',
        'is_active'
    ];

    protected $casts = [
        'price_usd' => 'decimal:2',
        'price_cop' => 'decimal:2',
        'daily_ads' => 'integer',
        'click_earnings' => 'decimal:4',
        'banner_views' => 'integer',
        'post_views' => 'integer',
        'ptc_views' => 'integer',
        'main_ad_value' => 'decimal:2',
        'mini_ad_value' => 'decimal:2',
        'mini_ads_count' => 'integer',
        'benefits' => 'array',
        'is_active' => 'boolean'
    ];

    // Relaciones
    public function users()
    {
        return $this->hasMany(User::class, 'current_package_id');
    }

    // Métodos de negocio
    public function getMonthlyEarningsPotential()
    {
        $config = \App\Services\EconomicConfig::class;
        $mainEarnings = $this->main_ad_value * $config::MAIN_ADS_DAILY * 30;
        $miniEarnings = $this->mini_ad_value * $this->mini_ads_count * 30;
        return $mainEarnings + $miniEarnings;
    }

    public static function seedPackages()
    {
        $config = \App\Services\EconomicConfig::class;
        $packages = [
            [
                'name' => 'Básico $25',
                'description' => 'Paquete inicial - 5 anuncios principales + 4 mini-anuncios diarios',
                'price_usd' => 25,
                'price_cop' => 25 * 3800,
                'daily_ads' => $config::MAIN_ADS_DAILY,
                'main_ad_value' => $config::getMainAdValue(25),
                'mini_ad_value' => $config::getMiniAdValue(25),
                'mini_ads_count' => $config::getMiniAdCount(25),
                'is_active' => true
            ],
            [
                'name' => 'Básico $50',
                'description' => 'Paquete intermedio - 5 anuncios principales + 4 mini-anuncios diarios',
                'price_usd' => 50,
                'price_cop' => 50 * 3800,
                'daily_ads' => $config::MAIN_ADS_DAILY,
                'main_ad_value' => $config::getMainAdValue(50),
                'mini_ad_value' => $config::getMiniAdValue(50),
                'mini_ads_count' => $config::getMiniAdCount(50),
                'is_active' => true
            ],
            [
                'name' => 'Avanzado $100',
                'description' => 'Paquete avanzado - 5 anuncios principales + 4 mini-anuncios diarios',
                'price_usd' => 100,
                'price_cop' => 100 * 3800,
                'daily_ads' => $config::MAIN_ADS_DAILY,
                'main_ad_value' => $config::getMainAdValue(100),
                'mini_ad_value' => $config::getMiniAdValue(100),
                'mini_ads_count' => $config::getMiniAdCount(100),
                'is_active' => true
            ],
            [
                'name' => 'Avanzado $150',
                'description' => 'Paquete premium - 5 anuncios principales + 8 mini-anuncios diarios',
                'price_usd' => 150,
                'price_cop' => 150 * 3800,
                'daily_ads' => $config::MAIN_ADS_DAILY,
                'main_ad_value' => $config::getMainAdValue(150),
                'mini_ad_value' => $config::getMiniAdValue(150),
                'mini_ads_count' => $config::getMiniAdCount(150),
                'is_active' => true
            ]
        ];

        foreach ($packages as $package) {
            self::updateOrCreate(
                ['price_usd' => $package['price_usd']], 
                $package
            );
        }
    }
}
