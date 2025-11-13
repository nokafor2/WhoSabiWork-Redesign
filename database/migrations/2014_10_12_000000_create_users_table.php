<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('username')->unique();
            $table->string('gender')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable(); // Made nullable for OAuth users
            $table->string('phone_number')->unique()->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('account_type')->default('regular'); // regular or business
            $table->string('account_status')->default('active'); // active or inactive
            $table->boolean('is_admin')->default(false); // admin flag
            
            // OAuth provider fields
            $table->string('provider')->nullable()->comment('OAuth provider (google, facebook)');
            $table->string('provider_id')->nullable()->comment('Unique ID from OAuth provider');
            $table->text('provider_token')->nullable()->comment('Access token from OAuth provider');
            
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for better performance
            $table->index(['provider', 'provider_id']);
            $table->index('account_status');
            $table->index('account_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
