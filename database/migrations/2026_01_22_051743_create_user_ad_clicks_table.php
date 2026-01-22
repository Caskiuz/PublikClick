<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_ad_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('ad_id')->constrained()->onDelete('cascade');
            $table->decimal('earnings', 8, 4);
            $table->string('ip_address');
            $table->timestamp('clicked_at');
            $table->timestamps();
            
            $table->unique(['user_id', 'ad_id', 'clicked_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_ad_clicks');
    }
};