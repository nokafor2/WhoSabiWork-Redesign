<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sellers', function (Blueprint $table) {
            $table->increments('id')->unsigned();            
            $table->unsignedInteger('user_id')->unique();
            $table->boolean('provisions')->default(0)->nullable();
            $table->boolean('fabrics')->default(0)->nullable();
            $table->boolean('electronics')->default(0)->nullable();
            $table->boolean('electricals_and_accessories')->default(0)->nullable();
            $table->boolean('computers_and_accessories')->default(0)->nullable();
            $table->boolean('phones_and_accessories')->default(0)->nullable();
            $table->boolean('kitchen_utensils')->default(0)->nullable();
            $table->boolean('house_hold_equipment')->default(0)->nullable();
            $table->boolean('jeweries')->default(0)->nullable();
            $table->boolean('shoes')->default(0)->nullable();
            $table->boolean('clothes_and_apparels')->default(0)->nullable();
            $table->boolean('televisions')->default(0)->nullable();
            $table->boolean('bags_and_boxes')->default(0)->nullable();
            $table->boolean('tiles')->default(0)->nullable();
            $table->boolean('building_materials')->default(0)->nullable();
            $table->boolean('furniture')->default(0)->nullable();
            $table->boolean('cars')->default(0)->nullable();
            $table->boolean('motorcycles')->default(0)->nullable();
            $table->boolean('tricycles')->default(0)->nullable();
            $table->boolean('paints')->default(0)->nullable();
            $table->boolean('books')->default(0)->nullable();
            $table->boolean('decors')->default(0)->nullable();
            $table->boolean('generators')->default(0)->nullable();
            $table->boolean('watches')->default(0)->nullable();
            $table->boolean('hairs_and_wigs')->default(0)->nullable();
            $table->boolean('exercise_equipment')->default(0)->nullable();
            $table->boolean('plumbimg_materials')->default(0)->nullable();
            $table->boolean('fashion_accessories')->default(0)->nullable();
            $table->boolean('pharmaceuticals')->default(0)->nullable();
            $table->boolean('fragrances_and_ornamentals')->default(0)->nullable();
            $table->boolean('fishery')->default(0)->nullable();
            $table->boolean('skin_care_products')->default(0)->nullable();
            $table->boolean('hair_products')->default(0)->nullable();
            $table->boolean('baby_care_accessories')->default(0)->nullable();
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
        Schema::dropIfExists('sellers');
    }
}
