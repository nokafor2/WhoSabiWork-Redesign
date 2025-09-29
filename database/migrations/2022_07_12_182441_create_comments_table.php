<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id')->unique()->unsigned();
            $table->unsignedInteger('user_id')->index();
            $table->unsignedInteger('user_id_comment')->nullable()->index();
            $table->string('author')->nullable(false)->default('');
            $table->text('body')->nullable(false);
            $table->timestamps();

            // Add foreign keys
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('user_id_comment')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
