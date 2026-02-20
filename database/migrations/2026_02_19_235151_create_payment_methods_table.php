<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nequi, Bancolombia, Daviplata, etc.
            $table->string('slug')->unique(); // nequi, bancolombia, daviplata
            $table->string('icon')->nullable(); // URL del icono
            $table->boolean('is_active')->default(true);
            $table->json('config')->nullable(); // Configuración específica
            $table->integer('min_withdrawal')->default(10000); // Mínimo de retiro
            $table->text('instructions')->nullable(); // Instrucciones para el usuario
            $table->timestamps();
        });

        // Agregar campo payment_method_id a transactions
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'payment_method_id')) {
                $table->foreignId('payment_method_id')->nullable()->after('user_id')->constrained()->onDelete('set null');
            }
            if (!Schema::hasColumn('transactions', 'payment_details')) {
                $table->json('payment_details')->nullable(); // Detalles específicos del método
            }
        });

        // Insertar métodos de pago por defecto
        DB::table('payment_methods')->insert([
            [
                'name' => 'Nequi',
                'slug' => 'nequi',
                'icon' => '/images/payments/nequi.png',
                'is_active' => true,
                'min_withdrawal' => 10000,
                'instructions' => 'Ingresa tu número de celular Nequi (10 dígitos)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Bancolombia',
                'slug' => 'bancolombia',
                'icon' => '/images/payments/bancolombia.png',
                'is_active' => true,
                'min_withdrawal' => 10000,
                'instructions' => 'Ingresa tu número de cuenta Bancolombia',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Daviplata',
                'slug' => 'daviplata',
                'icon' => '/images/payments/daviplata.png',
                'is_active' => true,
                'min_withdrawal' => 10000,
                'instructions' => 'Ingresa tu número de celular Daviplata (10 dígitos)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Epayco',
                'slug' => 'epayco',
                'icon' => '/images/payments/epayco.png',
                'is_active' => true,
                'min_withdrawal' => 10000,
                'instructions' => 'Ingresa tu email registrado en Epayco',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Binance',
                'slug' => 'binance',
                'icon' => '/images/payments/binance.png',
                'is_active' => true,
                'min_withdrawal' => 10000,
                'instructions' => 'Ingresa tu Binance ID o email',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'PayPal',
                'slug' => 'paypal',
                'icon' => '/images/payments/paypal.png',
                'is_active' => true,
                'min_withdrawal' => 10000,
                'instructions' => 'Ingresa tu email de PayPal',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Stripe',
                'slug' => 'stripe',
                'icon' => '/images/payments/stripe.png',
                'is_active' => true,
                'min_withdrawal' => 10000,
                'instructions' => 'Ingresa tu email registrado en Stripe',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'payment_method_id')) {
                $table->dropForeign(['payment_method_id']);
                $table->dropColumn('payment_method_id');
            }
            if (Schema::hasColumn('transactions', 'payment_details')) {
                $table->dropColumn('payment_details');
            }
        });
        
        Schema::dropIfExists('payment_methods');
    }
};
