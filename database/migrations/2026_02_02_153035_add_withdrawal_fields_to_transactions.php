<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('status');
            $table->text('payment_details')->nullable()->after('payment_method');
            $table->timestamp('processed_at')->nullable()->after('payment_details');
            $table->string('admin_notes')->nullable()->after('processed_at');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'payment_details', 'processed_at', 'admin_notes']);
        });
    }
};
