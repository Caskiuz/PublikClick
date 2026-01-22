<?php

namespace Database\Seeders;

use App\Models\Ad;
use Illuminate\Database\Seeder;

class AdSeeder extends Seeder
{
    public function run(): void
    {
        $ads = [
            [
                'title' => 'Descubre Amazon Prime',
                'description' => 'Envíos gratis, películas y series ilimitadas',
                'url' => 'https://amazon.com/prime',
                'image_url' => 'https://via.placeholder.com/300x200?text=Amazon+Prime',
                'click_value' => 0.0250,
                'is_active' => true
            ],
            [
                'title' => 'Netflix - Entretenimiento Sin Límites',
                'description' => 'Miles de películas y series en streaming',
                'url' => 'https://netflix.com',
                'image_url' => 'https://via.placeholder.com/300x200?text=Netflix',
                'click_value' => 0.0300,
                'is_active' => true
            ],
            [
                'title' => 'Spotify Premium',
                'description' => 'Música sin anuncios y descargas offline',
                'url' => 'https://spotify.com/premium',
                'image_url' => 'https://via.placeholder.com/300x200?text=Spotify',
                'click_value' => 0.0200,
                'is_active' => true
            ],
            [
                'title' => 'Udemy - Cursos Online',
                'description' => 'Aprende nuevas habilidades con expertos',
                'url' => 'https://udemy.com',
                'image_url' => 'https://via.placeholder.com/300x200?text=Udemy',
                'click_value' => 0.0350,
                'is_active' => true
            ],
            [
                'title' => 'Rappi - Delivery Rápido',
                'description' => 'Comida, mercado y farmacia a domicilio',
                'url' => 'https://rappi.com',
                'image_url' => 'https://via.placeholder.com/300x200?text=Rappi',
                'click_value' => 0.0180,
                'is_active' => true
            ],
            [
                'title' => 'MercadoLibre',
                'description' => 'Compra y vende todo lo que necesitas',
                'url' => 'https://mercadolibre.com.co',
                'image_url' => 'https://via.placeholder.com/300x200?text=MercadoLibre',
                'click_value' => 0.0220,
                'is_active' => true
            ],
            [
                'title' => 'Bancolombia App',
                'description' => 'Banca digital segura y fácil',
                'url' => 'https://bancolombia.com',
                'image_url' => 'https://via.placeholder.com/300x200?text=Bancolombia',
                'click_value' => 0.0400,
                'is_active' => true
            ],
            [
                'title' => 'Uber - Viaja Cómodo',
                'description' => 'Solicita tu viaje con un toque',
                'url' => 'https://uber.com',
                'image_url' => 'https://via.placeholder.com/300x200?text=Uber',
                'click_value' => 0.0280,
                'is_active' => true
            ],
            [
                'title' => 'Falabella Online',
                'description' => 'Moda, tecnología y hogar',
                'url' => 'https://falabella.com.co',
                'image_url' => 'https://via.placeholder.com/300x200?text=Falabella',
                'click_value' => 0.0320,
                'is_active' => true
            ],
            [
                'title' => 'Platzi - Educación Tech',
                'description' => 'Cursos de programación y tecnología',
                'url' => 'https://platzi.com',
                'image_url' => 'https://via.placeholder.com/300x200?text=Platzi',
                'click_value' => 0.0450,
                'is_active' => true
            ]
        ];

        foreach ($ads as $ad) {
            Ad::create($ad);
        }
    }
}