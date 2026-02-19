<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('proof_image')->nullable()->after('admin_notes');
            $table->foreignId('processed_by')->nullable()->after('processed_at')->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['processed_by']);
            $table->dropColumn(['proof_image', 'processed_by']);
        });
    }
};
