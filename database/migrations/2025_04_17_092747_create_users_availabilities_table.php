<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users_availabilities', function (Blueprint $table) {
            $table->increments('id')->unsigned();            
            $table->unsignedInteger('user_id');
            $table->date('date_available');
            $table->boolean('eight_AM')->default(0);
            $table->boolean('eight_Thirty_AM')->default(0);
            $table->boolean('nine_AM')->default(0);
            $table->boolean('nine_Thirty_AM')->default(0);
            $table->boolean('ten_AM')->default(0);
            $table->boolean('ten_Thirty_AM')->default(0);
            $table->boolean('eleven_AM')->default(0);
            $table->boolean('eleven_Thirty_AM')->default(0);
            $table->boolean('twelve_PM')->default(0);
            $table->boolean('twelve_Thirty_PM')->default(0);
            $table->boolean('one_PM')->default(0);
            $table->boolean('one_Thirty_PM')->default(0);
            $table->boolean('two_PM')->default(0);
            $table->boolean('two_Thirty_PM')->default(0);
            $table->boolean('three_PM')->default(0);
            $table->boolean('three_Thirty_PM')->default(0);
            $table->boolean('four_PM')->default(0);
            $table->boolean('four_Thirty_PM')->default(0);
            $table->boolean('five_PM')->default(0);
            $table->boolean('five_Thirty_PM')->default(0);
            $table->boolean('six_PM')->default(0);
            $table->boolean('six_Thirty_PM')->default(0);
            $table->boolean('seven_PM')->default(0);
            $table->boolean('seven_Thirty_PM')->default(0);
            $table->timestamps();

            // Add foreign key
            $table->foreign('user_id')->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_availabilities');
        Schema::dropSoftDeletes();
    }
};
