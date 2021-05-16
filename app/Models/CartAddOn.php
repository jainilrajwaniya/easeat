<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class CartAddOn extends Model
{
    protected $table="cart_add_on";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'cart_item_id', 'add_on_id'
    ];

    /**
     * get cart add ons
     * @param type $cart_item_id
     * @return type
     */
    public static function getCartAddons($cart_item_id) {
        return DB::select('SELECT CA.add_on_id, KASC.kitchen_add_on_item_name_en AS add_on_name, KAC.category_name_en AS category,'
                . ' KASC.price as add_on_price, KAC.status, KASC.kitchen_add_on_item_name_ar AS add_on_name_ar  '
                . 'FROM cart_add_on CA '
                . 'LEFT JOIN kitchen_items_add_on_assoc KASC ON KASC.id = CA.add_on_id '
                . 'LEFT JOIN kitchen_add_on_category KAC ON KAC.id = KASC.kitchen_add_on_cat_id '
                . 'WHERE CA.cart_item_id = '.$cart_item_id);
    }
}
