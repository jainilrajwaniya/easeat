<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otps extends Model
{
    protected $table="otps";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mobile', 'otp'
    ];
}
