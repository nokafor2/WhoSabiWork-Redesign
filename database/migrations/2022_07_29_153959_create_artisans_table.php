<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArtisansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('artisans', function (Blueprint $table) {
            $table->increments('id')->unsigned();            
            $table->unsignedInteger('user_id')->unique();
            $table->boolean('caterer')->default(0)->nullable();
            $table->boolean('confectioner')->default(0)->nullable();
            $table->boolean('event_planner')->default(0)->nullable();
            $table->boolean('make_up_artist')->default(0)->nullable();
            $table->boolean('tailor')->default(0)->nullable();
            $table->boolean('jeweller')->default(0)->nullable();
            $table->boolean('artist')->default(0)->nullable();
            $table->boolean('tiler')->default(0)->nullable();
            $table->boolean('holticulturist')->default(0)->nullable();
            $table->boolean('driver')->default(0)->nullable();
            $table->boolean('cleaner')->default(0)->nullable();
            $table->boolean('mover')->default(0)->nullable();
            $table->boolean('transporter')->default(0)->nullable();
            $table->boolean('upholsterer')->default(0)->nullable();
            $table->boolean('shoemaker')->default(0)->nullable();
            $table->boolean('watchmaker')->default(0)->nullable();
            $table->boolean('bag_maker')->default(0)->nullable();
            $table->boolean('milliner')->default(0)->nullable();
            $table->boolean('tanner')->default(0)->nullable();
            $table->boolean('fabric_maker')->default(0)->nullable();
            $table->boolean('brewer')->default(0)->nullable();
            $table->boolean('detergent_maker')->default(0)->nullable();
            $table->boolean('locksmith')->default(0)->nullable();
            $table->boolean('welder')->default(0)->nullable();
            $table->boolean('electronics_technician')->default(0)->nullable();
            $table->boolean('bookbinder')->default(0)->nullable();
            $table->boolean('carbinet_maker')->default(0)->nullable();
            $table->boolean('gasman')->default(0)->nullable();
            $table->boolean('glassblower')->default(0)->nullable();
            $table->boolean('goldsmith')->default(0)->nullable();
            $table->boolean('blacksmith')->default(0)->nullable();
            $table->boolean('mason')->default(0)->nullable();
            $table->boolean('plumber')->default(0)->nullable();
            $table->boolean('electrician')->default(0)->nullable();
            $table->boolean('painter')->default(0)->nullable();
            $table->boolean('air_condition_technician')->default(0)->nullable();
            $table->boolean('POP_maker')->default(0)->nullable();
            $table->boolean('carpenter')->default(0)->nullable();
            $table->boolean('security_guard')->default(0)->nullable();
            $table->boolean('glazier')->default(0)->nullable();
            $table->boolean('drycleaner')->default(0)->nullable();
            $table->boolean('hair_stylist')->default(0)->nullable();
            $table->boolean('wig_maker')->default(0)->nullable();
            $table->boolean('photographer')->default(0)->nullable();
            $table->boolean('computer_technician')->default(0)->nullable();
            $table->boolean('phone_technician')->default(0)->nullable();
            $table->boolean('solar_energy_technician')->default(0)->nullable();
            $table->boolean('CCTV_technician')->default(0)->nullable();
            $table->boolean('video_editor')->default(0)->nullable();
            $table->boolean('barber')->default(0)->nullable();
            $table->boolean('interior_designer')->default(0)->nullable();
            $table->boolean('generator_technician')->default(0)->nullable();
            $table->boolean('DSTV_technician')->default(0)->nullable();
            $table->boolean('workout_trainer')->default(0)->nullable();
            $table->boolean('alumaco_fabrication')->default(0)->nullable();
            $table->boolean('fabric_printing')->default(0)->nullable();
            $table->boolean('delivery_agent')->default(0)->nullable();
            $table->boolean('diary_producer')->default(0)->nullable();
            $table->boolean('cold_room_services')->default(0)->nullable();
            $table->boolean('real_estate_agent')->default(0)->nullable();
            $table->boolean('mechanic')->default(0)->nullable();
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
        Schema::dropIfExists('artisans');
    }
}
