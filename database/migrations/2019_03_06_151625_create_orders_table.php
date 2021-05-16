<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index()->nullable();
            $table->integer('guest_user_id')->index()->nullable();
            $table->integer('chef_id')->index();
            $table->integer('kitchen_id')->index();
            $table->enum('discount_type', ['PromoCode', 'CompanyDiscount'])->nullable();
            $table->integer('promo_code_id')->nullable();
            $table->decimal('company_discount', 10, 2)->nullable();
            $table->decimal('order_total', 10, 2);
            $table->decimal('order_total_after_discount', 10, 2);
            $table->enum('status', ['Pending', 'Payment_Done', 'Confirmed', 'Cooking', 'Ready', 'Completed' , 'Customer_Cancelled', 'Chef_Cancelled', 'Payment_Issue', 'Waiting_Pickup', 'Waiting_Home_Delivery'])->default('Pending')->index();
            $table->enum('delivery_type', ['Pickup', 'HomeDelivery']);
            $table->text('cooking_instructions')->nullable();
            $table->integer('user_address_id')->nullable();
            $table->datetime('cancellation_time')->nullable();
            $table->string('reason_cancelled')->nullable();
            $table->integer('payment_method_id')->nullable();
            $table->string('payment_response')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
