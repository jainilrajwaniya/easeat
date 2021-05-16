<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveUnwantedFieldsFromUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('email_verified_at');          
            $table->dropColumn('otp');
            $table->dropColumn('activation_datetime');
            $table->dropColumn('otp_send_at');
            $table->dropColumn('otp_verified_at');
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
            $table->string('first_name', 25)->nullable();
            $table->string('last_name', 25)->nullable();
            $table->timestamp('email_verified_at')->nullable();          
            $table->string('otp', 4)->nullable();
            $table->dateTime('activation_datetime')->nullable();
            $table->dateTime('otp_send_at')->nullable()->after('password');
            $table->dateTime('otp_verified_at')->nullable()->after('otp_send_at');
        });
    }
}
