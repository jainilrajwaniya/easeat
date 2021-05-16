<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldInKitchens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kitchens', function (Blueprint $table) {
            //
            $table->integer('per_person_cost')->after('latitude')->nullable();
            $table->integer('min_order_home_delivery')->after('per_person_cost')->nullable();
            $table->longText('cuisine_types')->after('min_order_home_delivery');
            $table->longText('categories')->after('cuisine_types');
            $table->enum('pure_veg', [0,1])->after('categories')->default(0)->index();
            $table->integer('discount_percentage')->after('pure_veg')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kitchens', function (Blueprint $table) {
            //
            $table->dropColumn('per_person_cost');
            $table->dropColumn('min_order_home_delivery');
            $table->dropColumn('cuisine_types');
            $table->dropColumn('categories');
            $table->dropColumn('pure_veg');
            $table->dropColumn('discount_percentage');
        });
    }
}
