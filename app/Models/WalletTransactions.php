<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletTransactions extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id', 'user_id', 'guest_user_id', 'amount', 'description', 'transaction_type', 'type', 'created_by', 'updated_by', 'created_at', 'updated_at' 
    ];

}
