<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Agregar campos para sistema multinivel profundo
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'level_2_count')) {
                $table->integer('level_2_count')->default(0)->after('stars');
                $table->integer('level_3_count')->default(0)->after('level_2_count');
                $table->integer('level_4_count')->default(0)->after('level_3_count');
                $table->integer('level_5_count')->default(0)->after('level_4_count');
                $table->integer('level_6_count')->default(0)->after('level_5_count');
            }
        });

        // Crear tabla de comisiones multinivel solo si no existe
        if (!Schema::hasTable('multilevel_commissions')) {
            Schema::create('multilevel_commissions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('from_user_id')->constrained('users')->onDelete('cascade');
                $table->integer('level'); // 1-6
                $table->decimal('amount', 10, 2);
                $table->string('type'); // click, package_purchase, renewal
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // Actualizar tabla ranks para incluir niveles profundos
        $existingRanks = DB::table('ranks')->whereIn('name', [
            'Diamante Corona 1 Estrella',
            'Diamante Corona 2 Estrellas',
            'Diamante Corona 3 Estrellas',
            'Diamante Corona 4 Estrellas',
            'Diamante Corona 5 Estrellas'
        ])->count();

        if ($existingRanks == 0) {
            DB::table('ranks')->insert([
                [
                    'name' => 'Diamante Corona 1 Estrella',
                    'min_referrals' => 100,
                    'max_referrals' => 199,
                    'mega_ads_monthly' => 100,
                    'referral_commission' => 400,
                    'mini_ads_daily' => 8,
                    'order' => 10,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'Diamante Corona 2 Estrellas',
                    'min_referrals' => 200,
                    'max_referrals' => 299,
                    'mega_ads_monthly' => 120,
                    'referral_commission' => 400,
                    'mini_ads_daily' => 8,
                    'order' => 11,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'Diamante Corona 3 Estrellas',
                    'min_referrals' => 300,
                    'max_referrals' => 399,
                    'mega_ads_monthly' => 140,
                    'referral_commission' => 400,
                    'mini_ads_daily' => 8,
                    'order' => 12,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'Diamante Corona 4 Estrellas',
                    'min_referrals' => 400,
                    'max_referrals' => 499,
                    'mega_ads_monthly' => 160,
                    'referral_commission' => 400,
                    'mini_ads_daily' => 8,
                    'order' => 13,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'Diamante Corona 5 Estrellas',
                    'min_referrals' => 500,
                    'max_referrals' => 999999,
                    'mega_ads_monthly' => 200,
                    'referral_commission' => 400,
                    'mini_ads_daily' => 8,
                    'order' => 14,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['level_2_count', 'level_3_count', 'level_4_count', 'level_5_count', 'level_6_count']);
        });
        
        Schema::dropIfExists('multilevel_commissions');
        
        DB::table('ranks')->whereIn('name', [
            'Diamante Corona 1 Estrella',
            'Diamante Corona 2 Estrellas',
            'Diamante Corona 3 Estrellas',
            'Diamante Corona 4 Estrellas',
            'Diamante Corona 5 Estrellas'
        ])->delete();
    }
};
