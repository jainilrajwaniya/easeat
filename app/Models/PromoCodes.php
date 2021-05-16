<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class PromoCodes extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'promo_code', 'discount_percentage', 'no_of_usage', 'max_dis_amt', 'limitation', 'status', 'created_by', 'updated_by', 'publish_at', 'expire_at', 'created_at', 'updated_at', 'image' 
    ];
    
    /**
     * get all active promocodes
     * @return type
     */
    public static function getActivePromoCodes() {
        $result = Self::where('status', 'Active')
                    ->Where('expire_at', '>=', date('Y-m-d'))
                    ->select(['id', 'promo_code', DB::raw('CONCAT("'.config('aws.aws_s3_url').config('aws.aws_s3_promocode_images_bucket').'",id,"'.config('aws.thumbnails_200x200').'",image) AS image'), 'discount_percentage', 'min_order_value', 'max_dis_amt'] )
                    ->orderBy('updated_at', 'DESC')->get()->toArray();
        return $result;
    }
    
    /**
     * Check promocodes is not expired, assoc. with kitchen
     * @return type
     */
    public static function checkPromoCodeAvaibility($promo_code) {
        return Self::where('status', 'Active')
                    ->Where('expire_at', '>=', date('Y-m-d'))
                    ->Where('promo_code', '=', $promo_code)
                    ->select(['id'] )
                    ->get()->toArray();
    }
    
    /**
     * Check order hase minimum value of order
     * @return type
     */
    public static function getPromoCodeDetail($promo_code) {
        return Self::where('promo_code', '=', $promo_code)
                    ->select(['id', 'promo_code', 'discount_percentage', 'no_of_usage', 'min_order_value', 'max_dis_amt', 'limitation'] )
                    ->first()->toArray();
    }
}
