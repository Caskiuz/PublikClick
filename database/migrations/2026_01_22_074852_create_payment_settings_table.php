<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->id();
            $table->string('gateway_name'); // stripe, paypal, coinbase, binance
            $table->string('gateway_type'); // fiat, crypto
            $table->boolean('is_active')->default(false);
            $table->text('api_key')->nullable();
            $table->text('api_secret')->nullable();
            $table->text('webhook_secret')->nullable();
            $table->string('wallet_address')->nullable(); // Para crypto
            $table->string('network')->nullable(); // BTC, ETH, TRC20, etc
            $table->json('settings')->nullable(); // Configuraciones adicionales
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_settings');
    }
};
