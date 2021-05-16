<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KitchenTimings extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'day', 'from_time1', 'to_time1', 'from_time2', 'to_time2', 'created_by', 'updated_by', 'created_at', 'updated_at', 'kitchen_id'
    ];
}
