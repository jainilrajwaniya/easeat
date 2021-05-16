<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderAddOn extends Model
{
    protected $table="order_add_on";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'order_item_id', 'add_on_id', 'add_on_name', 'price'
    ];
}
