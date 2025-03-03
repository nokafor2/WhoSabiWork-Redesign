<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSparePartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spare_parts', function (Blueprint $table) {
            $table->increments('id')->unsigned();            
            $table->unsignedInteger('user_id')->unique();
            $table->boolean('engine')->default(0)->nullable();
            $table->boolean('engine_part')->default(0)->nullable();
            $table->boolean('tyre')->default(0)->nullable();
            $table->boolean('mechanical_part')->default(0)->nullable();
            $table->boolean('electrical_part')->default(0)->nullable();
            $table->boolean('door')->default(0)->nullable();
            $table->boolean('side_mirror')->default(0)->nullable();
            $table->boolean('body_part')->default(0)->nullable();
            $table->boolean('light')->default(0)->nullable();
            $table->boolean('widnscreen')->default(0)->nullable();
            $table->boolean('chair')->default(0)->nullable();
            $table->boolean('upholstery_part')->default(0)->nullable();
            $table->boolean('lubricant')->default(0)->nullable();
            $table->boolean('dashboard')->default(0)->nullable();
            $table->boolean('bumper')->default(0)->nullable();
            $table->boolean('air_conditioning_part')->default(0)->nullable();
            $table->boolean('compressor')->default(0)->nullable();
            $table->boolean('condenser')->default(0)->nullable();
            $table->boolean('radiator')->default(0)->nullable();
            $table->boolean('transmission')->default(0)->nullable();
            $table->boolean('exhaust_part')->default(0)->nullable();
            $table->boolean('steering_system')->default(0)->nullable();
            $table->boolean('steering_rack')->default(0)->nullable();
            $table->boolean('steering')->default(0)->nullable();
            $table->boolean('shock_absorber')->default(0)->nullable();
            $table->boolean('shock_spring')->default(0)->nullable();
            $table->boolean('wheel')->default(0)->nullable();
            $table->boolean('brake_disk')->default(0)->nullable();
            $table->boolean('radio_system')->default(0)->nullable();
            $table->boolean('fan')->default(0)->nullable();
            $table->boolean('chasis')->default(0)->nullable();
            $table->boolean('fuel_tank')->default(0)->nullable();
            $table->boolean('sensor')->default(0)->nullable();
            $table->boolean('speaker_system')->default(0)->nullable();
            $table->boolean('seat_belt')->default(0)->nullable();
            $table->boolean('windshield_wiper')->default(0)->nullable();
            $table->boolean('key')->default(0)->nullable();
            $table->boolean('motor')->default(0)->nullable();
            $table->boolean('jack')->default(0)->nullable();
            $table->boolean('horn')->default(0)->nullable();
            $table->boolean('belt')->default(0)->nullable();
            $table->boolean('chain')->default(0)->nullable();
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
        Schema::dropIfExists('spare_parts');
    }
}
