<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->string('first_name', 25)->nullable();
            $table->string('last_name', 25)->nullable();
            $table->string('name', 50)->nullable();
            $table->string('email')->unique()->index();
            $table->string('phone_number', 15)->nullable();
            $table->string('password');            
            $table->enum('status', ['Active', 'Inactive'])->default('Inactive');
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();            
            $table->string('otp', 4)->nullable();
            $table->dateTime('otp_send_datetime')->nullable();
            $table->dateTime('activation_datetime')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
