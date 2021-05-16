<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KitchenAddOnItems extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'add_on_name', 'created_by', 'updated_by', 'created_at', 'updated_at'
    ];
}
