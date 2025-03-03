<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_brands', function (Blueprint $table) {
            $table->increments('id')->unsigned();            
            $table->unsignedInteger('user_id');
            $table->string('business_category');
            $table->boolean('toyota')->default(0)->nullable();
            $table->boolean('honda')->default(0)->nullable();
            $table->boolean('nissan')->default(0)->nullable();
            $table->boolean('mazda')->default(0)->nullable();
            $table->boolean('mitsubishi')->default(0)->nullable();
            $table->boolean('suzuki')->default(0)->nullable();
            $table->boolean('subaru')->default(0)->nullable();
            $table->boolean('scion')->default(0)->nullable();
            $table->boolean('kia')->default(0)->nullable();
            $table->boolean('hyundai')->default(0)->nullable();
            $table->boolean('acura')->default(0)->nullable();
            $table->boolean('infinity')->default(0)->nullable();
            $table->boolean('lexus')->default(0)->nullable();
            $table->boolean('mercedes_benz')->default(0)->nullable();
            $table->boolean('BMW')->default(0)->nullable();
            $table->boolean('volkswagen')->default(0)->nullable();
            $table->boolean('audi')->default(0)->nullable();
            $table->boolean('ford')->default(0)->nullable();
            $table->boolean('chrystler')->default(0)->nullable();
            $table->boolean('dodge')->default(0)->nullable();
            $table->boolean('chevrolet')->default(0)->nullable();
            $table->boolean('GMC')->default(0)->nullable();
            $table->boolean('peugout')->default(0)->nullable();
            $table->boolean('renault')->default(0)->nullable();
            $table->boolean('innoson')->default(0)->nullable();
            $table->boolean('volvo')->default(0)->nullable();
            $table->boolean('citroen')->default(0)->nullable();
            $table->boolean('saturn')->default(0)->nullable();
            $table->boolean('opel')->default(0)->nullable();
            $table->boolean('range_rover')->default(0)->nullable();
            $table->boolean('hummer')->default(0)->nullable();
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
        Schema::dropIfExists('car_brands');
    }
}
