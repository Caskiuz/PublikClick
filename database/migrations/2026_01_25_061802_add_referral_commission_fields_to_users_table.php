<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'total_referral_earnings')) {
                $table->decimal('total_referral_earnings', 15, 2)->default(0)->after('wallet_balance');
            }
            if (!Schema::hasColumn('users', 'active_referrals_count')) {
                $table->integer('active_referrals_count')->default(0)->after('total_referral_earnings');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['total_referral_earnings', 'active_referrals_count']);
        });
    }
};
