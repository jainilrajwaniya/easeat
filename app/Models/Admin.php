<?php

namespace App\Models;

use App\Notifications\AdminResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $guard = 'admin';
    protected $table="admins";
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'profile_pic', 'role', 'ip_address', 'mac_address', 'created_by', 'updated_by', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new AdminResetPassword($token));
    }
    
    /**
     * check whether email already used
     * @param type $email
     * @param type $id
     * @return type
     */
    public static function checkEmailAlreadyExists($email, $id) {
       return self::where([['id' , '!=', $id], ['email', '=', $email]])->count(); 
    }
}
