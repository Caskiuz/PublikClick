<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('referral_code')->unique()->after('email');
            $table->unsignedBigInteger('referred_by')->nullable()->after('referral_code');
            $table->decimal('wallet_balance', 10, 2)->default(0)->after('referred_by');
            $table->json('bank_info')->nullable()->after('wallet_balance');
            $table->boolean('is_active')->default(true)->after('bank_info');
            
            $table->foreign('referred_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['referred_by']);
            $table->dropColumn(['referral_code', 'referred_by', 'wallet_balance', 'bank_info', 'is_active']);
        });
    }
};