<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class CartItems extends Model
{
    protected $table="cart_items";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'cart_id', 'kitchen_item_id', 'varient_id', 'quantity', 'item_instruction'
    ];
    
    /**
     * get cart items
     * @param type $cart_id
     * @return type
     */
    public static function getCartItems($cart_id) {
        return DB::select('SELECT CI.id, CI.kitchen_item_id, CI.varient_id, CI.item_instruction, CI.quantity, KI.item_name,KI.item_name_ar,'
                . 'CONCAT("'.config('aws.aws_s3_url').config('aws.aws_s3_kitchen_items_images_bucket').'",KI.id,"'.config('aws.thumbnails_200x200').'",KI.profile_pic) AS item_image, '
                . ' KIV.varient_name, KIV.varient_name_ar, IF(CI.varient_id = 0, KI.price, KIV.varient_price) AS price, KI.status '
                . 'FROM cart_items CI '
                . 'LEFT JOIN kitchen_items KI ON KI.id = CI.kitchen_item_id '
                . 'LEFT JOIN kitchen_item_varients KIV ON KIV.id = CI.varient_id '
//                . 'LEFT JOIN cart_add_on CA ON CA.cart_item_id = CI.id '
//                . 'LEFT JOIN kitchen_items_add_on_assoc KASC ON KASC.id = CA.add_on_id '
//                . 'LEFT JOIN kitchen_add_on_items KAI ON  KAI.id = KASC.kitchen_add_on_item_id '
//                . 'LEFT JOIN kitchen_add_on_category KAC ON KAC.id = KASC.kitchen_add_on_cat_id '
                . 'WHERE CI.cart_id = '.$cart_id);
    }
}
