<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_available_ads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('ad_type', ['principal', 'mini', 'mega', 'mini_unlocked']); // Tipo de anuncio
            $table->integer('quantity')->default(0); // Cantidad disponible
            $table->timestamp('expires_at')->nullable(); // Fecha de expiración
            $table->date('generated_date'); // Fecha de generación
            $table->timestamps();
            
            $table->index(['user_id', 'ad_type']);
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_available_ads');
    }
};
