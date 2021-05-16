<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TriggeringMessages extends Model
{
    protected $table="triggering_messages";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'trigger', 'sms', 'status', 'email_body', 'notification', 'email_subject'
    ];
}
