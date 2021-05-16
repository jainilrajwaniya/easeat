<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Cart extends Model
{
    protected $table="cart";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'user_id', 'guest_user_id', 'kitchen_id', 'promo_code', 'company_discount', 'total', 'delivery_fee', 'discount', 'grand_total'
    ];
    
    public static function getCartDetails($user_id, $guest_user_id) {
        if($user_id) {
            $where = " C.user_id  = $user_id ";
        } else {
            $where = " C.guest_user_id = $guest_user_id" ;
        }
//        echo 'SELECT CI.id, KI.item_name, KI.description, '
//                . 'CONCAT("'.config('aws.aws_s3_url').config('aws.aws_s3_kitchen_items_images_bucket').'",KI.id,"'.config('aws.thumbnails_200x200').'",KI.profile_pic) AS item_image, '
//                . ' KIV.varient_name, IF(CI.varient_id = 0, KI.price, KIV.varient_price) AS price, '
//                . ' CA.add_on_id, KAI.add_on_name, KAC.category, KASC.price as add_on_price  '
//                . 'FROM cart C '
//                . 'LEFT JOIN cart_items CI ON CI.cart_id = C.id '
//                . 'LEFT JOIN kitchen_items KI ON KI.id = CI.kitchen_item_id '
//                . 'LEFT JOIN kitchen_item_varients KIV ON KIV.id = CI.varient_id '
//                . 'LEFT JOIN cart_add_on CA ON CA.cart_item_id = CI.id '
//                . 'LEFT JOIN kitchen_items_add_on_assoc KASC ON KASC.id = CA.add_on_id '
//                . 'LEFT JOIN kitchen_add_on_items KAI ON  KAI.id = KASC.kitchen_add_on_item_id '
//                . 'LEFT JOIN kitchen_add_on_category KAC ON KAC.id = KASC.kitchen_add_on_cat_id '
//                . 'WHERE '.$where;
//             die;   
        return DB::select('SELECT CI.id, C.kitchen_id, CI.kitchen_item_id,CI.quantity, KI.item_name, KI.description, '
                . 'CONCAT("'.config('aws.aws_s3_url').config('aws.aws_s3_kitchen_items_images_bucket').'",KI.id,"'.config('aws.thumbnails_200x200').'",KI.profile_pic) AS item_image, '
                . ' KIV.varient_name, IF(CI.varient_id = 0, KI.price, KIV.varient_price) AS price, '
                . ' CA.add_on_id, KASC.kitchen_add_on_item_name_en AS add_on_name, KAC.category_name_en AS category,'
                . ' KASC.price as add_on_price  '
                . 'FROM cart C '
                . 'LEFT JOIN cart_items CI ON CI.cart_id = C.id '
                . 'LEFT JOIN kitchen_items KI ON KI.id = CI.kitchen_item_id '
                . 'LEFT JOIN kitchen_item_varients KIV ON KIV.id = CI.varient_id '
                . 'LEFT JOIN cart_add_on CA ON CA.cart_item_id = CI.id '
                . 'LEFT JOIN kitchen_items_add_on_assoc KASC ON KASC.id = CA.add_on_id '
                . 'LEFT JOIN kitchen_add_on_category KAC ON KAC.id = KASC.kitchen_add_on_cat_id '
                . 'WHERE '.$where);
    }
    
    /**
     * check if item already exists in cart
     * if yes then inc. quantity, else new entry
     * @param type $item_id
     */
    public static function checkItemExistsInCart($item_id, $user_id, $guest_user_id) {
        $where['kitchen_item_id'] = $item_id;
        if($user_id) {
            $where['user_id'] = $user_id;
        } else {
            $where['guest_user_id'] = $guest_user_id;
        }
        return self::where($where)->count();
    }
}
