<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_categories', function (Blueprint $table) {
            $table->increments('id')->unsigned();            
            $table->unsignedInteger('user_id')->unique();
            $table->tinyInteger('artisan')->default(0)->nullable();
            $table->tinyInteger('seller')->default(0)->nullable();
            $table->tinyInteger('technician')->default(0)->nullable();
            $table->tinyInteger('spare_part_seller')->default(0)->nullable();
            $table->string('business_name');
            $table->text('business_description')->nullable();
            $table->string('business_page')->nullable()->comment('Can be used to save a website link.');
            $table->integer('views')->default(0);
            $table->tinyInteger('advertise')->default(0);
            $table->timestamps();

            // Add foreign key
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_categories');
    }
}
