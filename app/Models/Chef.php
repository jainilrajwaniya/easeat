<?php

namespace App\Models;

use App\Notifications\ChefResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

class Chef extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'profile_pic', 'role', 'ip_address', 'mac_address', 'created_at', 'updated_at' 
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
        $this->notify(new ChefResetPassword($token));
    }
    
    
    public static function getChefsWithKitchens($params) {
        $where = $limit = $join = '';
        $limit = " LIMIT 0, 100";
        if(!empty($params['keyword'])) {
            //search in chef and food name
            $join = ' JOIN kitchen_items ON kitchens.id = kitchen_items.kitchen_id ';
            $where .= ' AND (chefs.name LIKE "%'.$params['keyword'].'%" OR kitchen_items.item_name LIKE "%'.$params['keyword'].'%") ';
        }
        if(!empty($params['promo_code_id'])) {
            $join .= ' JOIN promo_code_kitchen_assocs ON promo_code_kitchen_assocs.kitchen_id = kitchens.id ';
            $where .= ' AND promo_code_kitchen_assocs.promo_code_id = "'.$params['promo_code_id'].'" ';
        }
        if(!empty($params['delivery_type'])) {
            $where .= ' AND kitchens.delivery_type IN ("'.$params['delivery_type'].'","Both" )';
        }
        if(!empty($params['pre_order'])) {
            $where .= ' AND kitchens.pre_order = "'.$params['pre_order'].'"';
        }
        if(!empty($params['cuisine_type'])) {
            $arrCT = explode(',', $params['cuisine_type']);
            $or = " AND (";
            for($i=0;$i<count($arrCT);$i++) {
                $where .= $or.'  kitchens.cuisine_types LIKE "%'.$arrCT[$i].'%"';
                $or = " OR ";
            }
            $where .= ") ";
        }
        
        if(!empty($params['category'])) {
            $arrCT = explode(',', $params['category']);
            $or = " AND (";
            for($i=0;$i<count($arrCT);$i++) {
                $where .= $or.'  kitchens.categories LIKE "%'.$arrCT[$i].'%"';
                $or = " OR ";
            }
            $where .= ") ";
        }
        
        if(!empty($params['page'])) {
            $offset = 10 * $params['page'];
            $count = 10;
            $limit = " LIMIT $offset, $count";
        }
        $radius = 10000;
//        if(!empty($params['radius'])) {
//            $radius = $params['radius'];
//        }
        
//        echo "SELECT DISTINCT(chefs.id) AS chef_id, chefs.name, kitchens.id AS kitchen_id,"
//                . "kitchens.open, kitchens.address, kitchens.per_person_cost,"
//                . "kitchens.cuisine_types, kitchens.categories, kitchen_timings.from_time1,"
//                . "kitchen_timings.to_time1, kitchen_timings.from_time2,kitchen_timings.to_time2, rating, prep_time, "
//                . "(SELECT CONCAT('".config('aws.aws_s3_url').config('aws.aws_s3_kitchen_images_bucket')."',id,'".config('aws.thumbnails_200x200')."',kitchen_image) FROM kitchen_images WHERE kitchen_id = kitchens.id LIMIT 1) AS image "
//                . ", (3959 * acos (
//                        cos ( radians(".$params['lat'].") )
//                        * cos( radians( kitchens.latitude ) )
//                        * cos( radians( kitchens.longitude ) - radians(".$params['long'].") )
//                        + sin ( radians(".$params['lat'].") )
//                        * sin( radians( kitchens.latitude ) )
//                      )
//                    ) AS distance "
//                . "FROM chefs "
//                . "JOIN kitchens ON chefs.id = kitchens.chef_id "
//                . "JOIN kitchen_timings ON kitchens.id = kitchen_timings.kitchen_id "
//                . "$join"
//                . "WHERE chefs.status = 'Active' "
//                . "AND kitchen_timings.day = '".date('l')."' "
//                . "$where AND ((SELECT COUNT(*) FROM kitchen_items WHERE kitchen_id = kitchens.id AND status = 'Active') > 0)"
//                . "HAVING distance <= $radius "
//                . "ORDER BY kitchens.open DESC $limit";
//        die();
        $result = DB::select("SELECT DISTINCT(chefs.id) AS chef_id, chefs.name, kitchens.id AS kitchen_id,"
                . "kitchens.open, kitchens.address, kitchens.per_person_cost,"
                . "kitchens.cuisine_types, kitchens.categories, kitchen_timings.from_time1,"
                . "kitchen_timings.to_time1, kitchen_timings.from_time2,kitchen_timings.to_time2, rating, prep_time, "
                . "(SELECT CONCAT('".config('aws.aws_s3_url').config('aws.aws_s3_kitchen_images_bucket')."',id,'".config('aws.thumbnails_200x200')."',kitchen_image) FROM kitchen_images WHERE kitchen_id = kitchens.id LIMIT 1) AS image "
                . ", (6371 * acos (
                        cos ( radians(".$params['lat'].") )
                        * cos( radians( kitchens.latitude ) )
                        * cos( radians( kitchens.longitude ) - radians(".$params['long'].") )
                        + sin ( radians(".$params['lat'].") )
                        * sin( radians( kitchens.latitude ) )
                      )
                    ) AS distance "
                . "FROM chefs "
                . "JOIN kitchens ON chefs.id = kitchens.chef_id "
                . "JOIN kitchen_timings ON kitchens.id = kitchen_timings.kitchen_id "
                . "$join"
                . "WHERE chefs.status = 'Active' "
                . "AND kitchen_timings.day = '".date('l')."' "
                . "$where AND ((SELECT COUNT(*) FROM kitchen_items WHERE kitchen_id = kitchens.id AND status = 'Active') > 0) "
                . "HAVING distance <= $radius "
                . "ORDER BY chef_id DESC, kitchens.open DESC  $limit"
                );
        return $result;
    }
}
