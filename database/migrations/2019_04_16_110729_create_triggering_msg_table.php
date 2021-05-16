<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggeringMsgTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('triggering_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('trigger', 45)->nullable();
            $table->text('sms')->nullable();            
            $table->text('notification')->nullable();   
            $table->text('email_subject')->nullable();
            $table->text('email_body')->nullable();            
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
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
        Schema::dropIfExists('triggering_messages');
    }
}
