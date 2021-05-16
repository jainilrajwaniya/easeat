<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Models\Cart; 
use App\Models\Kitchens; 
use App\Models\PromoCodes; 
use App\Models\PromoCodeKitchenAssoc; 
use Validator;
use App\Http\Helpers\CommonTrait;
use App\Http\Helpers\ResponseTrait;
use Exception;
use DB;

class PromocodeController extends Controller 
{
    use CommonTrait, ResponseTrait;

    public function __construct() {
        
    }
    
    /**
     * get active promocodes
     * @return type
     */
    public function getActivePromoCodes() {
        try {
            $promocodeArr = PromoCodes::getActivePromoCodes();
            $result = [];
            foreach($promocodeArr as $ele) {
                $temp = [];
                $temp['id'] = $ele['id'];
                $temp['promo_code'] = $ele['promo_code'];
                $temp['image'] = $ele['image']."?".rand(1111,9999);
                $temp['discount_percentage'] = $ele['discount_percentage'];
                $temp['max_dis_amt'] = $ele['max_dis_amt'];
                $temp['title'] = "Get ".$ele['discount_percentage']."% discount";
                $des = '';
                if($ele['min_order_value'] > 0) {
                    $des = "Minimum order value must be ".$ele['min_order_value'].'.';
                }
                if($ele['max_dis_amt'] > 0) {
                    $des .= " Max discount amount allowed ".$ele['max_dis_amt'];
                }
                $temp['description'] = $des;
                $result[] = $temp;
            }
            return $this->success(array('list' => $result));
        } catch (Exception $ex) {
            return $this->error('ERROR');
        }
    }
    
    /**
     * Check and apply promocode
     * @param Request $request
     * @return type
     */
    public function checkAndApply(Request $request) {
        $validation = Validator::make($request->all(), ['cart_id' => 'required|integer|exists:cart,id', 'promo_code' => 'required|exists:promo_codes,promo_code']);
        if ($validation->fails()) {
            return $this->validationError($validation);
        }
        DB::beginTransaction();
        try {
            $cart = Cart::where(['id' => $request->cart_id])->first();
            //check if kitchen discount is already applied
            if(!($cart->kitchen_id > 0)) {
                return $this->error("CART_IS_EMPTY");
            }
            
            //check if kitchen discount is already applied
            if($cart->company_discount > 0) {
                return $this->error("PROMOCODE_CANT_BE_APPLIED_ON_COMPANY_DISCOUNT");
            }
            
            //check promo code active and not expired
            if(count(PromoCodes::checkPromoCodeAvaibility($request->promo_code)) == 0) {
                return $this->error("PROMOCODE_NOT_ACTIVE_OR_EXPIRED");
            }
            
            //get promo code detail
            $promoCodeArr = PromoCodes::getPromoCodeDetail($request->promo_code);
            
            //check promo code is assoc. with kitchen
            if(count(PromoCodeKitchenAssoc::checkPromoCodeAssoc($promoCodeArr['id'], $cart->kitchen_id)) == 0) {
                return $this->error("PROMOCODE_NOT_ASSOC_WITH_THIS_CHEF");
            }
            
            
            
            //check min order value
            if(intval($cart->total) < intval($promoCodeArr['min_order_value'])) {
                return $this->error("PROMOCODE_MINIMUM_ORDER_VALUE_NOT_REACHED", 200, ['min_order_value' => $promoCodeArr['min_order_value']]);
            }
//            $epsilon = 0.00001; 
//            $min_order_value = floatval($promoCodeArr['min_order_value']);
//            $order_total = floatval($request->order_total);
//            if(abs($min_order_value - $order_total) < $epsilon) {
//                return $this->error("PROMOCODE_MINIMUM_ORDER_VALUE_NOT_REACHED", 200, ['min_order_value' => $promoCodeArr['min_order_value']]);
//            } else {
//                if (!($min_order_value < $order_total)) {  
//                    return $this->error("PROMOCODE_MINIMUM_ORDER_VALUE_NOT_REACHED", 200, ['min_order_value' => $promoCodeArr['min_order_value']]);
//                } 
//            }
            
            //check promo code usage

            //if all conditions cleared then check allowed discount
            $allowedDiscount = $discountCalculated = ($cart->total / 100) * $promoCodeArr['discount_percentage'];
            if(intval($promoCodeArr['max_dis_amt']) != 0 && intval($promoCodeArr['max_dis_amt']) < intval($discountCalculated)) {
                $allowedDiscount = $promoCodeArr['max_dis_amt'];
            }
            //check max discount allowed
//            $max_dis_amt = floatval($promoCodeArr['max_dis_amt']);
//            $discountCalculated = floatval($discountCalculated);
//            if(abs($max_dis_amt - $discountCalculated) < $epsilon) {
//                return $this->error("PROMOCODE_MAX_DISCOUNT_ALLOWED", 200, ['max_dis_amt' => $promoCodeArr['max_dis_amt']]);
//            } else {
//                if (!($max_dis_amt < $discountCalculated)) {  
//                    return $this->error("PROMOCODE_MAX_DISCOUNT_ALLOWED", 200, ['max_dis_amt' => $promoCodeArr['max_dis_amt']]);
//                } 
//            }
//            $tax_per = 10;
//            $discountedTotal = ($cart->total - $allowedDiscount);
//            if($tax_per) {
//                $tax = $discountedTotal / $tax_per;
//            }
            
            $cart->promo_code = $promoCodeArr['promo_code'];
            $cart->discount = round($allowedDiscount, 2);
//            $cart->tax = $tax;
//            $discountedTotal = $discountedTotal + $tax + $cart->delivery_fee;
//            $cart->grand_total = $discountedTotal;
            $cart->save();
            DB::commit();
            return $this->success(['order_total' => $cart->total, 'discount_order_total' => $allowedDiscount], "PROMOCODE_DISCOUNT_APPLIED");
        } catch (Exception $ex) {
            DB::rollback();
            return $this->error('ERROR');
        }
    }
    
    /**
     * Remove promocode
     * @param Request $request
     * @return type
     */
    public function removePromocode(Request $request) {
        try {
            $validation = Validator::make($request->all(), ['cart_id' => 'required|integer|exists:cart,id']);
            if ($validation->fails()) {
                return $this->validationError($validation);
            }
            //call common function to remove promocode from cart
            $this->removePromocodeFromCart($request->cart_id);
            return $this->success("",'PROMOCODE_REMOVED');
        } catch (Exception $ex) {
            return $this->error('ERROR');
        }
    }

}