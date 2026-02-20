<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, url, number, boolean
            $table->string('group')->default('general'); // general, whatsapp, social, etc.
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insertar configuraciones por defecto
        DB::table('site_settings')->insert([
            [
                'key' => 'whatsapp_sorteos',
                'value' => 'https://chat.whatsapp.com/XXXXXX',
                'type' => 'url',
                'group' => 'whatsapp',
                'description' => 'Link del grupo de WhatsApp para Mundo Sorteos',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'whatsapp_comunidad',
                'value' => 'https://chat.whatsapp.com/YYYYYY',
                'type' => 'url',
                'group' => 'whatsapp',
                'description' => 'Link del grupo de WhatsApp para Comunidad',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'video_explicativo_url',
                'value' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'type' => 'url',
                'group' => 'general',
                'description' => 'URL del video explicativo en landing page',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
