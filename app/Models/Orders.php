<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Orders extends Model
{
    protected $table="orders";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'user_id', 'guest_user_id', 'chef_id', 'kitchen_id', 'promo_code',
        'company_discount', 'total', 'delivery_fee', 'discount', 'tax', 'grand_total',
        'status', 'delivery_type', 'cooking_instructions', 'delivery_address', 'delivery_latitude'
        , 'delivery_longitude', 'contact_person_no', 'preorder_time' , 'receipt_url'
    ];
    
    /**
     * Get order detail
     * @param type $order_id
     * @return type
     */
    public static function getOrderItems($order_id) {
//        echo 'SELECT  O.kitchen_id, O.cooking_instructions, OI.id AS order_item_id, OI.quantity, OI.item_name, OI.varient_name, '
//                . 'OI.item_instruction, OA.add_on_name '
//                . 'FROM orders O '
//                . 'LEFT JOIN order_items OI ON OI.order_id = O.id '
//                . 'LEFT JOIN order_add_on OA ON OA.order_item_id = OI.id '
//                . 'WHERE O.id ='.$order_id;
        return DB::select('SELECT O.status, O.payment_status, O.payment_method, O.kitchen_id, O.cooking_instructions,OI.id AS order_item_id,OI.quantity, OI.item_name, OI.varient_name, '
                . 'OI.item_instruction, OA.add_on_name '
                . 'FROM orders O '
                . 'LEFT JOIN order_items OI ON OI.order_id = O.id '
                . 'LEFT JOIN order_add_on OA ON OA.order_item_id = OI.id '
                . 'WHERE O.id ='.$order_id);
    }
    
    /**
     * Get order List
     * @param type $where
     * @return type
     */
    public static function getOrderList($where = '') {
        if($where != '') {
            $where = " WHERE $where ";
        }
//        echo 'SELECT O.id, chefs.name, O.status, O.total, '
//                . 'OI.quantity, OI.item_name, OI.varient_name, O.cooking_instructions, '
//                . 'O.created_at, '
//                . "(SELECT CONCAT('".config('aws.aws_s3_url').config('aws.aws_s3_kitchen_images_bucket')."',id,'".config('aws.thumbnails_200x200')."',kitchen_image) FROM kitchen_images WHERE kitchen_id = O.kitchen_id LIMIT 1) AS image "
//                . 'FROM orders O '
//                . 'LEFT JOIN order_items OI ON OI.order_id = O.id '
//                . 'JOIN chefs ON O.chef_id = chefs.id '
//                . $where
//                . ' ORDER BY O.created_at DESC';
//        die;
        return DB::select('SELECT O.id, chefs.name, O.status, O.created_at, O.grand_total,O.contact_person_no, O.delivery_type, O.preorder_time, '
                . 'OI.quantity, OI.item_name, OI.varient_name, O.cooking_instructions, '
                . 'O.created_at, '
                . "(SELECT CONCAT('".config('aws.aws_s3_url').config('aws.aws_s3_kitchen_images_bucket')."',id,'".config('aws.thumbnails_200x200')."',kitchen_image) FROM kitchen_images WHERE kitchen_id = O.kitchen_id LIMIT 1) AS image "
                . 'FROM orders O '
                . 'LEFT JOIN order_items OI ON OI.order_id = O.id '
                . 'JOIN chefs ON O.chef_id = chefs.id '
                . $where
                . ' ORDER BY O.created_at DESC');
    }
    
    /**
     * Get order
     * @param type $where
     * @return type
     */
    public static function getOrdersList($where = '') {
        if($where != '') {
            $where = " WHERE $where ";
        }
//        echo 'SELECT O.id, chefs.name, O.order_json, '
//                . 'O.status, O.created_at, '
//                . "(SELECT CONCAT('".config('aws.aws_s3_url').config('aws.aws_s3_kitchen_images_bucket')."',id,'".config('aws.thumbnails_200x200')."',kitchen_image) FROM kitchen_images WHERE kitchen_id = O.kitchen_id LIMIT 1) AS image "
//                . 'FROM orders O '
//                . 'LEFT JOIN order_items OI ON OI.order_id = O.id '
//                . 'JOIN chefs ON O.chef_id = chefs.id '
//                . $where
//                . ' ORDER BY O.created_at DESC';
//        die;
        return DB::select('SELECT O.id, chefs.name, O.order_json, '
                . 'O.status, O.created_at, '
                . "(SELECT CONCAT('".config('aws.aws_s3_url').config('aws.aws_s3_kitchen_images_bucket')."',id,'".config('aws.thumbnails_200x200')."',kitchen_image) FROM kitchen_images WHERE kitchen_id = O.kitchen_id LIMIT 1) AS image "
                . 'FROM orders O '
                . 'JOIN chefs ON O.chef_id = chefs.id '
                . $where
                . ' ORDER BY O.created_at DESC');
    }
}
