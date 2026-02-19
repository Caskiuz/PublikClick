<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Campos de registro
            if (!Schema::hasColumn('users', 'whatsapp')) {
                $table->string('whatsapp', 20)->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('whatsapp');
            }
            if (!Schema::hasColumn('users', 'country')) {
                $table->string('country', 2)->nullable()->after('avatar');
            }
            if (!Schema::hasColumn('users', 'state')) {
                $table->string('state')->nullable()->after('country');
            }
            if (!Schema::hasColumn('users', 'city')) {
                $table->string('city')->nullable()->after('state');
            }
            
            // Sistema de estrellas para Diamante Corona
            if (!Schema::hasColumn('users', 'stars')) {
                $table->integer('stars')->default(0)->after('current_rank_id');
            }
            
            // Índices para mejorar rendimiento
            $table->index('country');
            $table->index('stars');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['whatsapp', 'avatar', 'country', 'state', 'city', 'stars']);
        });
    }
};
