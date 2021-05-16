<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KitchenItemOrder extends Model
{
    protected $table="kitchen_item_order";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'group_id', 'kitchen_item_id', 'seq_no'
    ];
}
