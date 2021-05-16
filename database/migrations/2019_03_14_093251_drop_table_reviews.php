<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropTableReviews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('reviews');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
//            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
            $table->integer('kitchen_id')->unsigned()->index();
//            $table->foreign('kitchen_id')->references('id')->on('kitchens')->onUpdate('cascade')->onDelete('restrict');
            $table->string('heading')->nullable();
            $table->text('description')->nullable();            
            $table->enum('status', ['Active', 'Inactive'])->default('Inactive');
            $table->integer('activated_by')->nullable();
            $table->integer('inactivated_by')->nullable();
            $table->timestamps();
        });
    }
}
