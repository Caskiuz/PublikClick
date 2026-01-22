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
        Schema::table('packages', function (Blueprint $table) {
            $table->integer('banner_views')->after('daily_ads');
            $table->integer('post_views')->after('banner_views');
            $table->integer('ptc_views')->after('post_views');
            $table->decimal('main_ad_value', 8, 2)->after('click_earnings');
            $table->decimal('mini_ad_value', 8, 2)->after('main_ad_value');
            $table->integer('mini_ads_count')->after('mini_ad_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['banner_views', 'post_views', 'ptc_views', 'main_ad_value', 'mini_ad_value', 'mini_ads_count']);
        });
    }
};
