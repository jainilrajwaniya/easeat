<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKitchensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kitchens', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('chef_id')->unsigned()->index();
//            $table->foreign('chef_id')->references('id')->on('chefs')->onUpdate('cascade')->onDelete('restrict');
            $table->integer('area_id')->unsigned()->index();
//            $table->foreign('area_id')->references('id')->on('areas')->onUpdate('cascade')->onDelete('restrict');
            $table->string('address', 100)->nullable();
            $table->string('lane', 100)->nullable();
            $table->string('landmark', 100)->nullable();
            $table->enum('delivery_type', ['PickUp', 'HomeDelivery', 'Both'])->default('pickUp');
            $table->enum('open', [1,0])->default(0);
            $table->enum('status', ['Active', 'Inactive'])->default('Inactive');
            $table->decimal('longitude', 10, 7);
            $table->decimal('latitude', 10, 7);
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
        Schema::dropIfExists('kitchens');
    }
}
