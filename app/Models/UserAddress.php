<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $table = 'user_address';
    
    protected $fillable = ['user_id', 'guest_user_id', 'name', 'address', 
        'gov_en', 'gov_ar', 'area_en', 'area_ar', 'block', 'street', 'additional_directions',
        'latitude', 'longitude', 'floor', 'building', 'avenue', 'apartment_no'];
}
