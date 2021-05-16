<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSettings extends Model
{
    protected $table = 'app_settings';
    protected $fillable = ['type', 'value', 'updated_at', 'created_at'];
}
