<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromoCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('promo_code', 20)->unique()->index();
            $table->decimal('discount_percentage', 10, 2)->default(0.00);
            $table->integer('no_of_usage')->default(0);
            $table->integer('max_dis_amt')->default(0);
            $table->enum('limitation', ['NTO', 'NTPC'])->default('NTO')->comments="NTO-N Times Only, NTPC-N Times Per Customer";
            $table->enum('status', ['Active', 'Inactive'])->default('Inactive');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamp('publish_at')->nullable();
            $table->timestamp('expire_at')->nullable();
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
        Schema::dropIfExists('promo_codes');
    }
}
