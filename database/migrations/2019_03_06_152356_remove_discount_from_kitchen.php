<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveDiscountFromKitchen extends Migration
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
            $table->dropColumn('discount_percentage');
            $table->integer('company_discount')->after('pure_veg')->default(0);
            $table->integer('company_commission')->after('company_discount')->default(10);
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
            $table->integer('discount_percentage')->after('pure_veg')->nullable();
            $table->dropColumn('company_discount');
            $table->dropColumn('company_commission');
        });
    }
}
