<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromoCodeKitchenAssocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_code_kitchen_assocs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('kitchen_id')->index();
            $table->integer('chef_id')->index();
            $table->integer('promo_code_id')->index();
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
        Schema::dropIfExists('promo_code_kitchen_assocs');
    }
}
