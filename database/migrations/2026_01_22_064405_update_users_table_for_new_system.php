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
        Schema::table('users', function (Blueprint $table) {
            // Solo agregar campos que no existen
            if (!Schema::hasColumn('users', 'current_rank_id')) {
                $table->foreignId('current_rank_id')->nullable()->constrained('ranks')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('users', 'package_purchased_at')) {
                $table->timestamp('package_purchased_at')->nullable();
            }
            
            if (!Schema::hasColumn('users', 'nequi_phone')) {
                $table->string('nequi_phone', 20)->nullable();
            }
            
            // Remover columnas del sistema anterior si existen
            if (Schema::hasColumn('users', 'rank')) {
                $table->dropColumn('rank');
            }
            
            if (Schema::hasColumn('users', 'wallet_retiro')) {
                $table->dropColumn(['wallet_retiro', 'wallet_donacion']);
            }
            
            if (Schema::hasColumn('users', 'active_referrals_count')) {
                $table->dropColumn('active_referrals_count');
            }
            
            if (Schema::hasColumn('users', 'package_expires_at')) {
                $table->dropColumn('package_expires_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'current_rank_id')) {
                $table->dropForeign(['current_rank_id']);
                $table->dropColumn('current_rank_id');
            }
            
            $table->dropColumn([
                'package_purchased_at',
                'nequi_phone'
            ]);
        });
    }
};
