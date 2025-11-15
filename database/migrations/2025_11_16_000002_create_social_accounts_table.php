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
        Schema::create('social_accounts', function (Blueprint $table) {
            $table->id();
            // Use unsignedInteger to match users.id (which uses increments())
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Provider information
            $table->string('provider'); // 'google', 'facebook', 'twitter', etc.
            $table->string('provider_id'); // Provider's unique user ID
            
            // OAuth tokens
            $table->text('provider_token')->nullable();
            $table->text('provider_refresh_token')->nullable();
            
            // Provider email (can be different from user's primary email)
            $table->string('provider_email')->nullable();
            $table->boolean('provider_email_verified')->default(false);
            
            // Profile data
            $table->string('avatar')->nullable();
            
            $table->timestamps();
            
            // Ensure one provider account can only be linked to one user
            $table->unique(['provider', 'provider_id']);
            
            // Indexes for faster lookups
            $table->index(['user_id', 'provider']);
            $table->index('provider_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_accounts');
    }
};

