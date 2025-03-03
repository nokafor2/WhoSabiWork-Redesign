<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhotographsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photographs', function (Blueprint $table) {
            $table->increments('id')->unsigned();            
            $table->unsignedInteger('user_id');
            $table->string('filename');
            $table->string('path');
            $table->integer('size');
            $table->string('caption')->nullable();
            $table->string('photo_type')->default('gallery');
            $table->boolean('visible')->default(1);
            $table->timestamps();

            // Set reference id and cascade
            $table->foreign('user_id')->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('photographs');
    }
}
