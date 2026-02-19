<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->text('user_comment')->nullable()->after('admin_notes');
            $table->timestamp('commented_at')->nullable()->after('user_comment');
            $table->boolean('requires_comment')->default(false)->after('commented_at');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['user_comment', 'commented_at', 'requires_comment']);
        });
    }
};
