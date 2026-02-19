<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            if (!Schema::hasColumn('ads', 'view_duration')) {
                $table->integer('view_duration')->default(90)->after('is_active');
            }
            if (!Schema::hasColumn('ads', 'image_url')) {
                $table->string('image_url')->nullable()->after('view_duration');
            }
            if (!Schema::hasColumn('ads', 'target_url')) {
                $table->string('target_url')->nullable()->after('image_url');
            }
        });

        Schema::table('user_ad_clicks', function (Blueprint $table) {
            if (!Schema::hasColumn('user_ad_clicks', 'view_time')) {
                $table->integer('view_time')->default(0)->after('earnings');
            }
            if (!Schema::hasColumn('user_ad_clicks', 'validation_attempts')) {
                $table->integer('validation_attempts')->default(0)->after('view_time');
            }
            if (!Schema::hasColumn('user_ad_clicks', 'validation_passed')) {
                $table->boolean('validation_passed')->default(false)->after('validation_attempts');
            }
            if (!Schema::hasColumn('user_ad_clicks', 'expires_at')) {
                $table->timestamp('expires_at')->nullable()->after('clicked_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropColumn(['view_duration', 'image_url', 'target_url']);
        });

        Schema::table('user_ad_clicks', function (Blueprint $table) {
            $table->dropColumn(['view_time', 'validation_attempts', 'validation_passed', 'expires_at']);
        });
    }
};
