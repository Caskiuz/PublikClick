<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_advertisements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['ptc', 'banner']); // ptc o banner
            $table->string('title');
            $table->string('file_path'); // imagen o video
            $table->string('file_type'); // image, video
            $table->string('redirect_url');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_cloned')->default(false); // si es clonado de otro usuario
            $table->foreignId('cloned_from')->nullable()->constrained('user_advertisements')->onDelete('set null');
            $table->integer('likes_count')->default(0);
            $table->integer('comments_count')->default(0);
            $table->integer('shares_count')->default(0);
            $table->integer('views_count')->default(0);
            $table->timestamps();
        });

        // Tabla para likes
        Schema::create('advertisement_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('advertisement_id')->constrained('user_advertisements')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['user_id', 'advertisement_id']);
        });

        // Tabla para comentarios
        Schema::create('advertisement_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('advertisement_id')->constrained('user_advertisements')->onDelete('cascade');
            $table->text('comment');
            $table->timestamps();
        });

        // Tabla para compartidos
        Schema::create('advertisement_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('advertisement_id')->constrained('user_advertisements')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advertisement_shares');
        Schema::dropIfExists('advertisement_comments');
        Schema::dropIfExists('advertisement_likes');
        Schema::dropIfExists('user_advertisements');
    }
};
