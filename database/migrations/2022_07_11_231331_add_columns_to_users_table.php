<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Columns to add
            $table->string('last_name')->after('first_name');
            $table->string('gender', 20)->after('last_name');
            $table->string('username')->after('email_verified_at');
            $table->string('phone_number', 15)->unique()->nullable()->after('remember_token');
            $table->timestamp('phone_verified_at')->nullable()->after('phone_number');
            $table->string('business_title')->nullable()->after('phone_verified_at');
            $table->tinyInteger('business_page')->nullable()->after('business_title');
            $table->string('account_status', 15)->nullable()->after('business_page');
            $table->string('account_type', 15)->after('account_status');
            $table->string('fb_user_id', 100)->nullable()->after('account_type');
            $table->string('fb_access_token', 500)->nullable()->after('fb_user_id');
            $table->string('google_user_id', 100)->nullable()->after('fb_access_token');
            $table->string('google_access_token', 500)->nullable()->after('google_user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['last_name', 'gender', 'username', 'phone_number', 'phone_verified_at']);
            $table->dropColumn(['business_title', 'business_page', 'account_status', 'account_type']);
            $table->dropColumn(['fb_user_id', 'fb_access_token', 'google_user_id', 'google_access_token']);
        });
    }
}
