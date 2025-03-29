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
        Schema::create('users_ratings', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->unsignedInteger('user_id');
            $table->unsignedTinyInteger('rating')->notNull();
            $table->unsignedInteger('rated_by_user')->notNull();
            $table->timestamps();

            // Set reference id and cascade
            $table->foreign('user_id')->references('id')
                ->on('users')
                ->onDelete('cascade');
            // Set reference id and cascade
            $table->foreign('rated_by_user')->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_ratings');
    }
};
