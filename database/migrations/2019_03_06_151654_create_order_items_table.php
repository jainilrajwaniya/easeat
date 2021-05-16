<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->index();
            $table->integer('item_id')->index();
            $table->string('item_name')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('original_price', 10, 2);
            $table->decimal('discount_price', 10, 2);
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->text('special_request')->nullable();
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
        Schema::dropIfExists('order_items');
    }
}
