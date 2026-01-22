<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ad;

class AdsSeeder extends Seeder
{
    public function run(): void
    {
        $ads = [
            [
                'title' => 'Anuncio Demo 1',
                'description' => 'Haz click y gana dinero con este anuncio',
                'url' => 'https://example.com/ad1',
                'image_url' => null,
                'click_value' => 0.10,
                'is_active' => true
            ],
            [
                'title' => 'Anuncio Demo 2', 
                'description' => 'Promoción especial - Click aquí',
                'url' => 'https://example.com/ad2',
                'image_url' => null,
                'click_value' => 0.15,
                'is_active' => true
            ],
            [
                'title' => 'Anuncio Demo 3',
                'description' => 'Oferta limitada - No te lo pierdas',
                'url' => 'https://example.com/ad3', 
                'image_url' => null,
                'click_value' => 0.20,
                'is_active' => true
            ],
            [
                'title' => 'Anuncio Demo 4',
                'description' => 'Gana dinero fácil con este click',
                'url' => 'https://example.com/ad4',
                'image_url' => null,
                'click_value' => 0.25,
                'is_active' => true
            ],
            [
                'title' => 'Anuncio Demo 5',
                'description' => 'Último anuncio del día - Click ya',
                'url' => 'https://example.com/ad5',
                'image_url' => null,
                'click_value' => 0.30,
                'is_active' => true
            ]
        ];

        foreach ($ads as $ad) {
            Ad::create($ad);
        }
    }
}