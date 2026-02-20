<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'user_comment')) {
                $table->text('user_comment')->nullable()->after('description');
            }
            if (!Schema::hasColumn('transactions', 'user_comment_at')) {
                $table->timestamp('user_comment_at')->nullable()->after('user_comment');
            }
            if (!Schema::hasColumn('transactions', 'payment_proof')) {
                $table->string('payment_proof')->nullable()->after('user_comment_at');
            }
            if (!Schema::hasColumn('transactions', 'payment_proof_uploaded_at')) {
                $table->timestamp('payment_proof_uploaded_at')->nullable()->after('payment_proof');
            }
            if (!Schema::hasColumn('transactions', 'requires_comment')) {
                $table->boolean('requires_comment')->default(false)->after('status');
            }
            if (!Schema::hasColumn('transactions', 'system_locked')) {
                $table->boolean('system_locked')->default(false)->after('requires_comment');
            }
        });

        // Agregar campo para bloquear el sistema del usuario
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'system_locked')) {
                $table->boolean('system_locked')->default(false)->after('is_active');
            }
            if (!Schema::hasColumn('users', 'lock_reason')) {
                $table->text('lock_reason')->nullable()->after('system_locked');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'user_comment',
                'user_comment_at',
                'payment_proof',
                'payment_proof_uploaded_at',
                'requires_comment',
                'system_locked'
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['system_locked', 'lock_reason']);
        });
    }
};
