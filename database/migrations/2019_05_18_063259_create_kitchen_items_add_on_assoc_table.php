<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKitchenItemsAddOnAssocTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kitchen_items_add_on_assoc', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('kitchen_item_id')->index();
            $table->integer('kitchen_add_on_cat_id')->index();
            $table->integer('kitchen_add_on_item_id')->index();
            $table->decimal('price', 10, 2);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('kitchen_items_add_on_assoc');
    }
}
