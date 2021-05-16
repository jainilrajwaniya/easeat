<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestUsers extends Model
{
    
    protected $fillable = [
        'id', 'name', 'phone_number', 'device_token', 'device_type'
    ];
}
