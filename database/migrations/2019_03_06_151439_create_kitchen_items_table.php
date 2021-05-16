<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKitchenItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kitchen_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('item_name', 50);
            $table->longText('description');
            $table->integer('kitchen_id')->index();
            $table->integer('category_id')->index();
            $table->integer('cuisine_type_id')->index();
            $table->integer('average_prep_time')->nullable();
            $table->decimal('price', 10,2)->default(0.00);
            $table->decimal('half_price', 10,2)->default(0.00);
            $table->string('profile_pic')->nullable();
            $table->enum('pure_veg', [0,1])->default(0);;
            $table->enum('status',['Active', 'Inactive'])->default('Inactive');
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
        Schema::dropIfExists('kitchen_items');
    }
}
