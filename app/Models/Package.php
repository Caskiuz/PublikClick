<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price_usd',
        'banner_views',
        'post_views',
        'ptc_views',
        'main_ad_value',
        'mini_ad_value',
        'mini_ads_count',
        'is_active'
    ];

    protected $casts = [
        'price_usd' => 'decimal:2',
        'banner_views' => 'integer',
        'post_views' => 'integer',
        'ptc_views' => 'integer',
        'main_ad_value' => 'decimal:2',
        'mini_ad_value' => 'decimal:2',
        'mini_ads_count' => 'integer',
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
        // 5 anuncios principales * 30 días + mini anuncios * 30 días
        $mainEarnings = $this->main_ad_value * 5 * 30;
        $miniEarnings = $this->mini_ad_value * $this->mini_ads_count * 30;
        
        return $mainEarnings + $miniEarnings;
    }

    public static function seedPackages()
    {
        $packages = [
            [
                'name' => 'Básico $25',
                'description' => 'Paquete inicial para comenzar',
                'price_usd' => 25,
                'banner_views' => 20000,
                'post_views' => 9000,
                'ptc_views' => 120,
                'main_ad_value' => 400,
                'mini_ad_value' => 83.33,
                'mini_ads_count' => 4,
                'is_active' => true
            ],
            [
                'name' => 'Básico $50',
                'description' => 'Paquete intermedio con mejores ganancias',
                'price_usd' => 50,
                'banner_views' => 40000,
                'post_views' => 20000,
                'ptc_views' => 250,
                'main_ad_value' => 600,
                'mini_ad_value' => 425,
                'mini_ads_count' => 4,
                'is_active' => true
            ],
            [
                'name' => 'Avanzado $100',
                'description' => 'Paquete avanzado con rango temporal Esmeralda',
                'price_usd' => 100,
                'banner_views' => 80000,
                'post_views' => 40000,
                'ptc_views' => 500,
                'main_ad_value' => 1120,
                'mini_ad_value' => 100,
                'mini_ads_count' => 4,
                'is_active' => true
            ],
            [
                'name' => 'Avanzado $150',
                'description' => 'Paquete premium con máximas ganancias',
                'price_usd' => 150,
                'banner_views' => 120000,
                'post_views' => 60000,
                'ptc_views' => 750,
                'main_ad_value' => 1600,
                'mini_ad_value' => 600,
                'mini_ads_count' => 8,
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
