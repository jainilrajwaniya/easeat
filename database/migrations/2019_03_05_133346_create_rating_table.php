<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRatingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
//            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
            $table->integer('kitchen_id')->unsigned()->index();
//            $table->foreign('kitchen_id')->references('id')->on('kitchens')->onUpdate('cascade')->onDelete('restrict');
            $table->enum('rating', ['1','1.5','2','2.5','3','3.5','4','4.5','5'])->default('1')->index();            
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->integer('activated_by')->nullable();
            $table->integer('inactivated_by')->nullable();
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
        Schema::dropIfExists('ratings');
    }
}
