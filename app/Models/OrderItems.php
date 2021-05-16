<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    protected $table="order_items";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'order_id', 'item_id', 'item_name', 'varient_id', 'varient_name',
        'quantity', 'price', 'item_instruction'
    ];
}
