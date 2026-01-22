<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Básico',
                'description' => 'Paquete ideal para comenzar en PubliClick',
                'price_cop' => 50000,
                'price_usd' => 12.50,
                'daily_ads' => 5,
                'click_earnings' => 0.0250,
                'benefits' => [
                    '5 clicks diarios',
                    '30% comisión nivel 1',
                    '15% comisión nivel 2',
                    '5% comisión nivel 3',
                    'Soporte básico'
                ],
                'is_active' => true
            ],
            [
                'name' => 'Premium',
                'description' => 'Mayor rentabilidad y beneficios exclusivos',
                'price_cop' => 100000,
                'price_usd' => 25.00,
                'daily_ads' => 5,
                'click_earnings' => 0.0500,
                'benefits' => [
                    '5 clicks diarios',
                    '35% comisión nivel 1',
                    '20% comisión nivel 2',
                    '10% comisión nivel 3',
                    'Mini-anuncios desbloqueados',
                    'Soporte prioritario'
                ],
                'is_active' => true
            ],
            [
                'name' => 'VIP',
                'description' => 'Máximas ganancias y beneficios premium',
                'price_cop' => 200000,
                'price_usd' => 50.00,
                'daily_ads' => 5,
                'click_earnings' => 0.1000,
                'benefits' => [
                    '5 clicks diarios',
                    '40% comisión nivel 1',
                    '25% comisión nivel 2',
                    '15% comisión nivel 3',
                    'Todos los mini-anuncios',
                    'Bonos especiales',
                    'Soporte VIP 24/7'
                ],
                'is_active' => true
            ],
            [
                'name' => 'Elite',
                'description' => 'El paquete más exclusivo con máximos beneficios',
                'price_cop' => 500000,
                'price_usd' => 125.00,
                'daily_ads' => 5,
                'click_earnings' => 0.2500,
                'benefits' => [
                    '5 clicks diarios',
                    '50% comisión nivel 1',
                    '30% comisión nivel 2',
                    '20% comisión nivel 3',
                    'Acceso a anuncios premium',
                    'Bonos mensuales',
                    'Asesoría personalizada',
                    'Retiros prioritarios'
                ],
                'is_active' => true
            ]
        ];
        
        foreach ($packages as $packageData) {
            Package::create($packageData);
        }
    }
}