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
        Schema::create('photographs_replies', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->unsignedInteger('photograph_id')->index(); // ID of the photograph that was commented on
            $table->unsignedInteger('comment_id')->index(); // ID of the comment being replied to
            $table->unsignedInteger('user_id_comment')->index(); // Owner of the photograph comment
            $table->unsignedInteger('user_id_reply')->index(); // User replying to the comment
            $table->text('reply');
            $table->timestamps(); // This creates created_at and updated_at
            $table->softDeletes(); // This creates deleted_at timestamp
            
            // Foreign key relationships
            $table->foreign('photograph_id')->references('id')->on('photographs')->onDelete('cascade');
            $table->foreign('comment_id')->references('id')->on('photographs_comments')->onDelete('cascade');
            $table->foreign('user_id_comment')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_id_reply')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photographs_replies');
    }
};
