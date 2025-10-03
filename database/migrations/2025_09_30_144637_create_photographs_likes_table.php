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
        Schema::create('photographs_likes', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->unsignedInteger('photograph_id')->index();
            $table->unsignedInteger('photograph_user_id')->index(); // Owner of the photograph
            $table->unsignedInteger('user_id')->index(); // User who liked the photograph
            $table->smallInteger('like')->default(1); // 1 = liked, 0 = cancelled like
            $table->timestamps(); // This creates created_at and updated_at
            $table->softDeletes(); // This creates deleted_at timestamp
            
            // Foreign key relationships
            $table->foreign('photograph_id')->references('id')->on('photographs')->onDelete('cascade');
            $table->foreign('photograph_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Unique constraint to prevent duplicate likes from same user on same photograph
            $table->unique(['photograph_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photographs_likes');
    }
};
