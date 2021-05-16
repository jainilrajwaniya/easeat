<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpHistory extends Model
{
    protected $table="otp_history";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'user_id', 'mobile', 'otp', 'response', 'status'
    ];
}
