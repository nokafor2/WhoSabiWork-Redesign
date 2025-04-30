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
        Schema::create('users_appointments', function (Blueprint $table) {
            $table->increments('id')->unsigned();            
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('scheduler_id');
            $table->date('appointment_date');
            $table->text('hours')->notNull();
            $table->text('appointment_message')->notNull();
            $table->string('user_decision', 10)->notNull();
            $table->text('user_decline_message', 250)->nullable()->default(null);
            $table->text('scheduler_cancel_message', 250)->nullable()->default(null);
            $table->text('user_cancel_message', 250)->nullable()->default(null);
            $table->timestamps();

            // Add foreign keys
            $table->foreign('user_id')->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('scheduler_id')->references('id')
                ->on('users')
                ->onDelete('cascade');

            // Create soft delete for table
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users_appointments', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('users_appointments');
    }
};
