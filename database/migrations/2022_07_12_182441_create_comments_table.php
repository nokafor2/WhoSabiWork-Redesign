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
            $table->unsignedInteger('business_id')->index();
            $table->string('author')->nullable(false)->default('');
            $table->text('body')->nullable(false)->default('');
            $table->timestamps();

            // Add foreign key
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('business_id')->references('id')->on('users');
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
