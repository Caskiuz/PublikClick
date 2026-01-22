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
        Schema::create('ranks', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->integer('min_referrals');
            $table->integer('max_referrals')->nullable();
            $table->integer('mega_ads_monthly');
            $table->decimal('referral_commission', 8, 2);
            $table->integer('mini_ads_daily');
            $table->integer('order');
            $table->timestamps();
            
            $table->index(['min_referrals', 'max_referrals']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ranks');
    }
};
