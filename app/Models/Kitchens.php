<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Kitchens extends Model
{
    protected $table="kitchens";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'chef_id', 'area_id', 'address', 'lane', 'landmark', 'delivery_type',
        'delivery_radius', 'pre_order','open', 'status', 'longitude', 'latitude',
        'per_person_cost', 'min_order_home_delivery', 'cuisine_types', 'categories',
        'pure_veg', 'company_discount', 'company_commission', 'created_by', 'updated_by',
        'created_at', 'updated_at', 'delivery_fee', 'prep_time'
    ];
    
    /**
     * Get kitchen detail
     * @param type $kitchenId
     * return array
     */
    public static function getKitchenDetails($kitchenId) {
        return DB::select('SELECT chefs.id as chef_id, chefs.name as chef_name, '
                . ' kitchens.id as kitchen_id, kitchens.cuisine_types, kitchens.categories, kitchens.longitude, kitchens.latitude, '
                . ' kitchens.pure_veg, kitchens.min_order_home_delivery, kitchens.per_person_cost, kitchens.delivery_radius,'
                . ' kitchens.open, kitchens.delivery_type, kitchens.delivery_fee, kitchens.pre_order, kitchens.rating, kitchens.prep_time, kitchen_timings.from_time1,'
                . ' kitchen_timings.to_time1, kitchen_timings.from_time2, kitchen_timings.to_time2, cities.name as area, '
                . ' (SELECT CONCAT("'.config('aws.aws_s3_url').config('aws.aws_s3_kitchen_images_bucket').'",id,"'.config('aws.thumbnails_200x200').'",kitchen_image) FROM kitchen_images WHERE kitchen_id = kitchens.id LIMIT 1) AS image ' 
                . ' FROM kitchens '
                . ' JOIN chefs on chefs.id = kitchens.chef_id '
                . ' JOIN kitchen_timings ON kitchens.id = kitchen_timings.kitchen_id'
                . ' JOIN cities ON kitchens.area_id = cities.id'
                . ' WHERE  kitchen_timings.day = "'.date('l').'" '
                . ' AND kitchens.id = '.$kitchenId);
    }

}
