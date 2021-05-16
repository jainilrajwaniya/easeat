<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveUnwantedFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('guest_users', function (Blueprint $table) {
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('email');
            $table->dropColumn('status');
            $table->dropColumn('otp');
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
        Schema::table('guest_users', function (Blueprint $table) {
            $table->string('first_name', 25)->nullable();
            $table->string('last_name', 25)->nullable();
            $table->string('email')->unique()->index();
            $table->enum('status', ['Active', 'Inactive'])->default('Inactive');
            $table->string('otp', 4)->nullable();
            $table->timestamp('otp_send_at')->nullable();
            $table->timestamp('otp_verified_at')->nullable();
        });
    }
}
