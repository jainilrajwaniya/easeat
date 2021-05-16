<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderAddOnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_add_on', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_item_id')->index();
            $table->integer('add_on_id');
            $table->string('add_on_name')->nullable();
            $table->integer('price')->default(0.0);
            $table->integer('quantity')->default(1);
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
        Schema::dropIfExists('order_add_on');
    }
}
