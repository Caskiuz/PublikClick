<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('multilevel_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('from_user_id')->constrained('users')->onDelete('cascade');
            $table->integer('level'); // 2, 3, 4, 5, 6
            $table->decimal('amount', 10, 2);
            $table->string('description');
            $table->timestamps();
            
            $table->index(['user_id', 'level']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('multilevel_commissions');
    }
};
