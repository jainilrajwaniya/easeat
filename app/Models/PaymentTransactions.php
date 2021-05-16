<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class PaymentTransactions extends Model
{
    protected $table="payment_transactions";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'user_id', 'guest_user_id', 'order_id', 'type', 'm_order_id',
        'response', 'transaction_status', 'response_after_payment'
    ];
}
