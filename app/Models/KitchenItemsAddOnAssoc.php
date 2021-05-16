<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class KitchenItemsAddOnAssoc extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'kitchen_items_add_on_assoc';
    
    protected $fillable = [
        'kitchen_item_id', 'kitchen_add_on_cat_id', 'kitchen_add_on_item_name_en', 'kitchen_add_on_item_name_ar', 'price','min','max','cat_seq_no','seq_no', 'created_by', 'updated_by', 'created_at', 'updated_at'
    ];
    
    /**
     * get kitchen items on kitchen id
     * @param type $kitchenId
     * @return type
     */
    public static function getAddOnsOnKitchenItemId_old($itemId, $choice) {
        return DB::select("SELECT kac.category_name_en, "
                . " kas.id, kas.price, kas.kitchen_add_on_item_name_en, kas.kitchen_add_on_item_name_ar"
                . " FROM kitchen_items ki "
                . "JOIN kitchen_items_add_on_assoc kas ON ki.id = kas.kitchen_item_id "
                . "JOIN kitchen_add_on_category kac ON kac.id = kas.kitchen_add_on_cat_id "
                . "WHERE kas.kitchen_item_id = $itemId AND kac.choices = '$choice' "
                . "Order by kac.cat_seq_no ASC, kas.seq_no ASC");
    }
    /**
     * get kitchen items on kitchen id
     * @param type $kitchenId
     * @return type
     */
    public static function getAddOnsOnKitchenItemId($itemId) {
        return DB::select("SELECT kac.category_name_en, "
                . " kas.id, kas.price, kas.kitchen_add_on_item_name_en, kas.kitchen_add_on_item_name_ar"
                . " FROM kitchen_items_add_on_assoc kas "
                . "JOIN kitchen_add_on_category kac ON kac.id = kas.kitchen_add_on_cat_id "
                . "WHERE kas.kitchen_item_id = $itemId  AND kac.status = 'Active'"
                . "Order by kac.cat_seq_no, kas.seq_no ASC");
    }
}
