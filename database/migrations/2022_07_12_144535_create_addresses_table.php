<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id')->unique()->unsigned();            
            $table->unsignedInteger('user_id')->unique();
            $table->string('address_line_1', 250)->default('')->nullable();
            $table->string('address_line_2', 250)->default('')->nullable();
            $table->string('address_line_3', 250)->default('')->nullable();
            $table->string('town', 100)->default('')->nullable();
            $table->string('state', 100)->default('')->nullable();
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
        Schema::dropIfExists('addresses');
    }
}
