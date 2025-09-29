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
        Schema::create('photograph_likes_dislikes', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->unsignedInteger('photograph_id')->index();
            $table->unsignedInteger('user_id')->index();
            $table->unsignedInteger('user_id_like')->nullable()->index();
            $table->unsignedInteger('user_id_dislike')->nullable()->index();
            $table->timestamps(); // This creates created_at and updated_at
            $table->softDeletes(); // This creates deleted_at timestamp
            
            // Foreign key relationships
            $table->foreign('photograph_id')->references('id')->on('photographs')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_id_like')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_id_dislike')->references('id')->on('users')->onDelete('cascade');
            
            // Ensure a user can only have one like/dislike entry per photograph
            $table->unique(['photograph_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photograph_likes_dislikes');
    }
};
