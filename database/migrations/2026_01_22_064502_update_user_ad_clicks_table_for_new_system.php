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
        Schema::table('user_ad_clicks', function (Blueprint $table) {
            $table->foreignId('mega_ad_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('ad_type', ['main', 'mini', 'mega'])->default('main');
            $table->text('user_agent')->nullable();
            $table->boolean('is_valid')->default(true);
            
            // Cambiar ad_id a nullable para mega ads y mini ads
            $table->foreignId('ad_id')->nullable()->change();
            
            $table->index(['user_id', 'ad_type', 'clicked_at']);
            $table->index(['ip_address', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_ad_clicks', function (Blueprint $table) {
            $table->dropForeign(['mega_ad_id']);
            $table->dropColumn([
                'mega_ad_id',
                'ad_type',
                'user_agent',
                'is_valid'
            ]);
        });
    }
};