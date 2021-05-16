<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class KitchenItems extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_name', 'item_name_ar', 'description', 'kitchen_id', 
        'average_prep_time', 'price', 'profile_pic', 'pure_veg', 'status', 'created_by',
        'updated_by', 'created_at', 'updated_at', 'groups', 'categories', 'cuisine_types'
    ];
    
    /**
     * get kitchen items on kitchen id
     * @param type $kitchenId
     * @return type
     */
    public static function getKitchenItemsOld($kitchenId) {
        return DB::select('SELECT KI.id, KI.item_name, KI.description, KI.average_prep_time, KI.price, KI.categories, KI.groups,'
                . 'CONCAT("'.config('aws.aws_s3_url').config('aws.aws_s3_kitchen_items_images_bucket').'",KI.id,"'.config('aws.thumbnails_200x200').'",KI.profile_pic) AS item_image, '
                . '(SELECT COUNT(*) FROM kitchen_items_add_on_assoc WHERE kitchen_item_id = KI.id) AS add_on_count, '
                . '(SELECT COUNT(*) FROM kitchen_item_varients WHERE kitchen_item_id = KI.id) AS varient_count '
                . 'FROM kitchen_items KI '
                . 'WHERE KI.kitchen_id = '.$kitchenId.' AND KI.status = "Active" '
                . 'ORDER BY KI.categories');
    }
    
    /**
     * get kitchen items on kitchen id
     * @param type $kitchenId
     * @return type
     */
    public static function getKitchenItems($kitchenId, $chef_id, $app = "customer") {
        $whereStatus = ($app == 'customer' ? " AND KI.status = 'Active' " : "");
        return DB::select('SELECT G.id AS group_id,G.group_name,G.seq_no, KIO.kitchen_item_id,
                            CONCAT("'.config('aws.aws_s3_url').config('aws.aws_s3_kitchen_items_images_bucket').'",KI.id,"'.config('aws.thumbnails_200x200').'",KI.profile_pic) AS item_image,
                            KIO.seq_no, KI.*,
                            (SELECT COUNT(*) FROM kitchen_add_on_category WHERE kitchen_item_id = KI.id AND kitchen_add_on_category.status = "Active") AS add_on_count,
                            (SELECT COUNT(*) FROM kitchen_item_varients WHERE kitchen_item_id = KI.id) AS varient_count 
                            FROM groups G
                            LEFT JOIN kitchen_item_order KIO on KIO.group_id = G.id 
                            LEFT JOIN kitchen_items KI on KI.id = KIO.kitchen_item_id 
                            where 1=1 '.$whereStatus.' AND G.chef_id = '.$chef_id.' order by G.seq_no ASC, KIO.seq_no');
    }
    
    /**
    * get kitchen items on kitchen id for backend
    * @param type $kitchenId
    * @return type
    */
    public static function getKitchenItems_admin_old($kitchenId) {
        return DB::select('SELECT KI.id, KI.item_name, KI.description, KI.average_prep_time, KI.price, KI.cuisine_types, KI.categories, KI.groups, KI.status,'
                . 'CONCAT("'.config('aws.aws_s3_url').config('aws.aws_s3_kitchen_items_images_bucket').'",KI.id,"'.config('aws.thumbnails_200x200').'",KI.profile_pic) AS item_image, '
                . '(SELECT COUNT(*) FROM kitchen_items_add_on_assoc WHERE kitchen_item_id = KI.id) AS add_on_count, '
                . '(SELECT COUNT(*) FROM kitchen_item_varients WHERE kitchen_item_id = KI.id) AS varient_count '
                . 'FROM kitchen_items KI '
                . 'WHERE KI.kitchen_id = '.$kitchenId.' '
                . 'ORDER BY KI.categories');
    }
    
    /**
    * get kitchen items on kitchen id for backend
    * @param type $chef_id
    * @return type
    */
     public static function getKitchenItems_admin($chef_id) {
        return DB::select("SELECT G.id AS group_id,G.group_name,G.seq_no, KIO.kitchen_item_id,
                            KIO.seq_no, KI.* FROM groups G
                            LEFT JOIN kitchen_item_order KIO on KIO.group_id = G.id 
                            LEFT JOIN kitchen_items KI on KI.id = KIO.kitchen_item_id 
                            where G.chef_id = $chef_id order by G.seq_no ASC, KIO.seq_no");
    }
}
