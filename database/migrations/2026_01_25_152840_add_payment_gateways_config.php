<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('type'); // fiat, crypto
            $table->string('category'); // wallet, bank, card, crypto
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->boolean('is_active')->default(false);
            $table->json('config')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Insertar métodos de pago predefinidos
        DB::table('payment_gateways')->insert([
            // FIAT - Wallets Colombia
            ['code' => 'nequi', 'name' => 'Nequi', 'type' => 'fiat', 'category' => 'wallet', 'description' => 'Billetera digital Colombia', 'is_active' => true, 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'daviplata', 'name' => 'Daviplata', 'type' => 'fiat', 'category' => 'wallet', 'description' => 'Billetera digital Davivienda', 'is_active' => false, 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            
            // FIAT - Regional
            ['code' => 'mercadopago', 'name' => 'Mercado Pago', 'type' => 'fiat', 'category' => 'wallet', 'description' => 'Pagos Latinoamérica', 'is_active' => false, 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'payu', 'name' => 'PayU', 'type' => 'fiat', 'category' => 'gateway', 'description' => 'Pasarela de pagos LATAM', 'is_active' => false, 'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'wompi', 'name' => 'Wompi', 'type' => 'fiat', 'category' => 'gateway', 'description' => 'Pasarela Colombia', 'is_active' => false, 'sort_order' => 5, 'created_at' => now(), 'updated_at' => now()],
            
            // FIAT - Internacional
            ['code' => 'paypal', 'name' => 'PayPal', 'type' => 'fiat', 'category' => 'wallet', 'description' => 'Pagos internacionales', 'is_active' => false, 'sort_order' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'stripe', 'name' => 'Stripe', 'type' => 'fiat', 'category' => 'card', 'description' => 'Tarjetas crédito/débito', 'is_active' => false, 'sort_order' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'skrill', 'name' => 'Skrill', 'type' => 'fiat', 'category' => 'wallet', 'description' => 'Monedero electrónico', 'is_active' => false, 'sort_order' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'payoneer', 'name' => 'Payoneer', 'type' => 'fiat', 'category' => 'wallet', 'description' => 'Pagos globales', 'is_active' => false, 'sort_order' => 9, 'created_at' => now(), 'updated_at' => now()],
            
            // FIAT - Bancos
            ['code' => 'pse', 'name' => 'PSE', 'type' => 'fiat', 'category' => 'bank', 'description' => 'Pagos Seguros en Línea Colombia', 'is_active' => false, 'sort_order' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'bancolombia', 'name' => 'Bancolombia', 'type' => 'fiat', 'category' => 'bank', 'description' => 'Transferencia Bancolombia', 'is_active' => false, 'sort_order' => 11, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'spei', 'name' => 'SPEI', 'type' => 'fiat', 'category' => 'bank', 'description' => 'Transferencias México', 'is_active' => false, 'sort_order' => 12, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'pix', 'name' => 'PIX', 'type' => 'fiat', 'category' => 'bank', 'description' => 'Pagos instantáneos Brasil', 'is_active' => false, 'sort_order' => 13, 'created_at' => now(), 'updated_at' => now()],
            
            // CRYPTO - Stablecoins
            ['code' => 'usdt_trc20', 'name' => 'USDT (TRC-20)', 'type' => 'crypto', 'category' => 'stablecoin', 'description' => 'Tether en red Tron', 'is_active' => false, 'sort_order' => 14, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'usdt_erc20', 'name' => 'USDT (ERC-20)', 'type' => 'crypto', 'category' => 'stablecoin', 'description' => 'Tether en red Ethereum', 'is_active' => false, 'sort_order' => 15, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'usdt_bep20', 'name' => 'USDT (BEP-20)', 'type' => 'crypto', 'category' => 'stablecoin', 'description' => 'Tether en Binance Smart Chain', 'is_active' => false, 'sort_order' => 16, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'usdc', 'name' => 'USDC', 'type' => 'crypto', 'category' => 'stablecoin', 'description' => 'USD Coin', 'is_active' => false, 'sort_order' => 17, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'busd', 'name' => 'BUSD', 'type' => 'crypto', 'category' => 'stablecoin', 'description' => 'Binance USD', 'is_active' => false, 'sort_order' => 18, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'dai', 'name' => 'DAI', 'type' => 'crypto', 'category' => 'stablecoin', 'description' => 'Stablecoin descentralizada', 'is_active' => false, 'sort_order' => 19, 'created_at' => now(), 'updated_at' => now()],
            
            // CRYPTO - Principales
            ['code' => 'btc', 'name' => 'Bitcoin (BTC)', 'type' => 'crypto', 'category' => 'cryptocurrency', 'description' => 'Bitcoin', 'is_active' => false, 'sort_order' => 20, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'eth', 'name' => 'Ethereum (ETH)', 'type' => 'crypto', 'category' => 'cryptocurrency', 'description' => 'Ethereum', 'is_active' => false, 'sort_order' => 21, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'bnb', 'name' => 'BNB', 'type' => 'crypto', 'category' => 'cryptocurrency', 'description' => 'Binance Coin', 'is_active' => false, 'sort_order' => 22, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'ltc', 'name' => 'Litecoin (LTC)', 'type' => 'crypto', 'category' => 'cryptocurrency', 'description' => 'Litecoin', 'is_active' => false, 'sort_order' => 23, 'created_at' => now(), 'updated_at' => now()],
            
            // CRYPTO - Pasarelas
            ['code' => 'binance_pay', 'name' => 'Binance Pay', 'type' => 'crypto', 'category' => 'gateway', 'description' => 'Pagos con Binance', 'is_active' => false, 'sort_order' => 24, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'coinbase', 'name' => 'Coinbase Commerce', 'type' => 'crypto', 'category' => 'gateway', 'description' => 'Pagos Coinbase', 'is_active' => false, 'sort_order' => 25, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'coinpayments', 'name' => 'CoinPayments', 'type' => 'crypto', 'category' => 'gateway', 'description' => 'Multi-crypto gateway', 'is_active' => false, 'sort_order' => 26, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'nowpayments', 'name' => 'NOWPayments', 'type' => 'crypto', 'category' => 'gateway', 'description' => 'Pagos crypto API', 'is_active' => false, 'sort_order' => 27, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_gateways');
    }
};
