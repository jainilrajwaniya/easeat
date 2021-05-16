<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuisineTypes extends Model
{
    protected $table="cuisine_types";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'cuisine_type_name', 'status'
    ];
}
