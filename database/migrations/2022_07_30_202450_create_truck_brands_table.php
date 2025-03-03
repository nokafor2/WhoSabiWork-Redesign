<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTruckBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('truck_brands', function (Blueprint $table) {
            $table->increments('id')->unsigned();            
            $table->unsignedInteger('user_id');
            $table->string('business_category');
            $table->boolean('sinotruck')->default(0)->nullable();
            $table->boolean('mack')->default(0)->nullable();
            $table->boolean('MAN')->default(0)->nullable();
            $table->boolean('mercedes_benz')->default(0)->nullable();
            $table->boolean('HOWO')->default(0)->nullable();
            $table->boolean('volvo')->default(0)->nullable();
            $table->boolean('tata')->default(0)->nullable();
            $table->boolean('shacman')->default(0)->nullable();
            $table->boolean('volkswagen')->default(0)->nullable();
            $table->boolean('scania')->default(0)->nullable();
            $table->boolean('renault')->default(0)->nullable();
            $table->boolean('DAF')->default(0)->nullable();
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
        Schema::dropIfExists('truck_brands');
    }
}
