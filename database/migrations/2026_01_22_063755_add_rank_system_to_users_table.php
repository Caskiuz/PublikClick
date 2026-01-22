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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('rank', ['jade', 'perla', 'zafiro', 'rubi', 'esmeralda', 'diamante', 'diamante_azul', 'diamante_negro', 'diamante_corona'])->default('jade')->after('is_admin');
            $table->decimal('wallet_retiro', 10, 2)->default(0)->after('wallet_balance');
            $table->decimal('wallet_donacion', 10, 2)->default(0)->after('wallet_retiro');
            $table->integer('active_referrals_count')->default(0)->after('wallet_donacion');
            $table->foreignId('current_package_id')->nullable()->constrained('packages')->after('active_referrals_count');
            $table->date('package_expires_at')->nullable()->after('current_package_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['current_package_id']);
            $table->dropColumn(['rank', 'wallet_retiro', 'wallet_donacion', 'active_referrals_count', 'current_package_id', 'package_expires_at']);
        });
    }
};
