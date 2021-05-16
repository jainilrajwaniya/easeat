<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ratings extends Model
{
    protected $table = 'ratings';
    
    protected $fillable = ['user_id', 'kitchen_id', 'rating', 'status', 'description'];
    
    /**
     * get rating list
     * @param type $request
     * @return type
     */
    public static function getRatings($id="") {
        $selectArray = ['users.name as user_name','users.email as user_email', 'users.phone_number as user_phno', 
            'chefs.name as chef_name', 'chefs.email as chef_email', 'users.phone_number as chef_phno',
            'ratings.rating as rating', 'ratings.status as status', 'ratings.created_at as created_at',
            'ratings.id as id'];


        
        $list = self::select($selectArray)
                ->leftJoin('users', 'users.id', '=', 'ratings.user_id')
                ->leftJoin('kitchens', 'kitchens.id', '=', 'ratings.kitchen_id')
                ->leftJoin('chefs', 'chefs.id', '=', 'kitchens.chef_id');

        if(!empty($id)){
            $list->where('ratings.kitchen_id', $id);
        }
                
        return $list;
    }
        
}
