<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOtpFieldsInUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('otp_send_datetime');
            $table->dateTime('otp_send_at')->nullable()->after('password');
            $table->dateTime('otp_verified_at')->nullable()->after('otp_send_at');
            $table->enum('device_type', ['Android', 'Iphone', 'Ipad'])->default('Android')->after('otp_verified_at');
            $table->string('device_token')->after('device_type');
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
            $table->dateTime('otp_send_datetime')->nullable();
            $table->dropColumn('otp_send_at');
            $table->dropColumn('otp_verified_at');
            $table->dropColumn('device_type');
            $table->dropColumn('device_token');
        });
    }
}
