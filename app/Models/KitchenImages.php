<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KitchenImages extends Model
{
    protected $table="kitchen_images";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'chef_id', 'kitchen_id', 'kitchen_image', 'created_by', 'updated_by', 'created_at', 'updated_at'
    ];
}
