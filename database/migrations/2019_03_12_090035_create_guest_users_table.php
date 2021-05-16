<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuestUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guest_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name', 25)->nullable();
            $table->string('last_name', 25)->nullable();
            $table->string('name', 50)->nullable();
            $table->string('email')->unique()->index();
            $table->string('phone_number', 15)->unique()->index()->nullable();
            $table->string('device_token')->unique()->index();
            $table->enum('device_type', ['Android', 'Iphone', 'Ipad'])->index()->default('Android');
            $table->enum('status', ['Active', 'Inactive'])->default('Inactive');
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('otp', 4)->nullable();
            $table->timestamp('otp_send_at')->nullable();
            $table->timestamp('otp_verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guest_users');
    }
}
