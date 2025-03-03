<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTechnicalServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('technical_services', function (Blueprint $table) {
            $table->increments('id')->unsigned();            
            $table->unsignedInteger('user_id')->unique();
            $table->boolean('engine_service')->default(0)->nullable();
            $table->boolean('mechanical_service')->default(0)->nullable();
            $table->boolean('electrical_service')->default(0)->nullable();
            $table->boolean('air_conditioning_service')->default(0)->nullable();
            $table->boolean('computer_diagnostics_service')->default(0)->nullable();
            $table->boolean('panel_beating_service')->default(0)->nullable();
            $table->boolean('body_work_service')->default(0)->nullable();
            $table->boolean('shock_absorber_service')->default(0)->nullable();
            $table->boolean('ballon_shocks_service')->default(0)->nullable();
            $table->boolean('wheel_balancing_and_alignment_service')->default(0)->nullable();
            $table->boolean('car_wash_service')->default(0)->nullable();
            $table->boolean('towing_service')->default(0)->nullable();
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
        Schema::dropIfExists('technical_services');
    }
}
