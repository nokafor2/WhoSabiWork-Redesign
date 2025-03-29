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
        Schema::create('users_feedback', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('first_name', 50)->nullable();
            $table->string('last_name', 50)->nullable();
            $table->string('phone_number', 50)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('message_subject', 50)->nullable();
            $table->text('message_content', 50)->nullable()->default(null);
            $table->boolean('resolved')->default(0)->notNull();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_feedback');
    }
};
