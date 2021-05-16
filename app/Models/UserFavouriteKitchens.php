<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class UserFavouriteKichens extends Model
{
    protected $table = 'user_favourite_kitchens';
    
    protected $fillable = ['user_id', 'kitchen_id'];
    
    /**
     * Get kitchen detail
     * @param type $kitchenId
     * return array
     */
    public static function getFavKitchenList($currentUserId) {
        return DB::select('SELECT chefs.id as chef_id, chefs.name as chef_name, '
                . ' kitchens.id as kitchen_id, kitchens.cuisine_types, kitchens.categories,'
                . ' kitchens.rating, '
                . ' (SELECT CONCAT("'.config('aws.aws_s3_url').config('aws.aws_s3_kitchen_images_bucket').'",id,"'.config('aws.thumbnails_200x200').'",kitchen_image) FROM kitchen_images WHERE kitchen_id = kitchens.id LIMIT 1) AS image ' 
                . ' FROM user_favourite_kitchens '
                . ' JOIN kitchens ON kitchens.id = user_favourite_kitchens.kitchen_id'
                . ' JOIN chefs on chefs.id = kitchens.chef_id '
                . ' WHERE user_favourite_kitchens.user_id = '.$currentUserId);
    }
}
