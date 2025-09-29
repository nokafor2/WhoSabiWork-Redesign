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
        Schema::create('replies', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->unsignedInteger('comment_id')->index();
            $table->unsignedInteger('user_id_comment')->index();
            $table->unsignedInteger('user_id_reply')->index();
            $table->text('body');
            $table->timestamps(); // This creates created_at and updated_at
            $table->softDeletes(); // This creates deleted_at timestamp
            
            // Foreign key relationships
            $table->foreign('comment_id')->references('id')->on('comments')->onDelete('cascade');
            $table->foreign('user_id_comment')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_id_reply')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('replies');
    }
};
