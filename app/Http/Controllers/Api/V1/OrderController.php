<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Auth;
use App\Models\Chef;
use App\Models\Cart;
use App\Models\CartItems;
use App\Models\CartAddOn;
use App\Models\Orders;
use App\Models\OrderItems;
use App\Models\OrderAddOn;
use App\Models\Kitchens;
use App\Models\PromoCodes;
use App\Models\PromoCodeKitchenAssoc;
use Validator;
use App\Http\Helpers\CommonTrait;
use App\Http\Helpers\ResponseTrait;
use Exception;
use DB;

class OrderController extends Controller 
{
    use CommonTrait, ResponseTrait;

    public function __construct() {
        $this->currentUser = Auth::guard('api')->user();
    }
    
    /**
     * Create Order
     * @param Request $request
     * @return type
     */
    public function createOrder(Request $request) {
        $validation = Validator::make($request->all(), config('frontValidations.CREATE_ORDER'));
        if ($validation->fails()) {
            return $this->validationError($validation);
        }
        //get user id or guest user id
        $user_id = $guest_user_id = null;
        if(!empty($this->currentUser->id)) {
            $user_id = $this->currentUser->id;
            if(!$this->currentUser->phone_number) {
                return $this->error('PHONE_NUMBER_MISSING');
            }
        } else {
            $guestUserObj = $this->getGuestUserDetails();
            if(!empty($guestUserObj->id)) {
                $guest_user_id = $guestUserObj->id;
                if(!$guestUserObj->phone_number) {
                    return $this->error('PHONE_NUMBER_MISSING');
                }
            }
        }
        
        //return if both user id and guest user id not found
        if($guest_user_id == NULL && $user_id == NULL) {
            return $this->error('USER_OR_GUEST_USER_NOT_FOUND');
        }
        try {
            if($user_id) {
                $cartObj = Cart::where(['user_id' => $user_id, 'id'=> $request->cart_id])->first();
            } else {
                $cartObj = Cart::where(['guest_user_id' => $guest_user_id, 'id'=> $request->cart_id])->first();
            }
            /*****************Validation begins*******************/
            // return if, Cart does not belongs to logged in user
            if(empty($cartObj)) {
                return $this->error('USER_CART_MISMATCH');
            }
            
            // do not create order if cart is empty
            if(!($cartObj->kitchen_id > 0)) {
                return $this->error('CART_IS_EMPTY');
            }
            
            //check all validation before order is created
            //check if kitchen discount is already applied
            if($cartObj->company_discount > 0 && $cartObj->promo_code != '' && $cartObj->promo_code != null) {
                return $this->error("PROMOCODE_CANT_BE_APPLIED_ON_COMPANY_DISCOUNT");
            }

            if($cartObj->promo_code) {
                //check promo code active and not expired
                if(count(PromoCodes::checkPromoCodeAvaibility($cartObj->promo_code)) == 0) {
                    return $this->error("PROMOCODE_NOT_ACTIVE_OR_EXPIRED");
                }

                //get promo code detail
                $promoCodeArr = PromoCodes::getPromoCodeDetail($cartObj->promo_code);

                //check promo code is assoc. with kitchen
                if(count(PromoCodeKitchenAssoc::checkPromoCodeAssoc($promoCodeArr['id'], $cartObj->kitchen_id)) == 0) {
                    return $this->error("PROMOCODE_NOT_ASSOC_WITH_THIS_CHEF");
                }

                //check min order value
                if(intval($cartObj->total) < intval($promoCodeArr['min_order_value'])) {
                    return $this->error("PROMOCODE_MINIMUM_ORDER_VALUE_NOT_REACHED", 200, ['min_order_value' => $promoCodeArr['min_order_value']]);
                }
            }

            //check chef availibility 
            $kitchen = Kitchens::where(['id' => $cartObj->kitchen_id])->first();
            if($kitchen->open == 0) {
                return $this->error("CHEF_IS_NOT_AVAILABLE_TO_TAKE_ORDER_NOW");
            }

            //check whether food can be delivered to selected address by chef
            if($this->distance($request->delivery_latitude, $request->delivery_longitude, $kitchen->latitude, $kitchen->longitude, 'M') > $kitchen->delivery_radius ) {
                return $this->error("CHEF_DOES_NOT_DELIVER_FOOD_AT_SELECTED_LOCATION");
            }
            /*****************Validation ends*******************/

            $result = $this->makeOrderFromCart($cartObj, $request->all());
            
            if($result == 'ITEM_OR_ADD_ON_INACTIVE') {
                return $this->error('ITEM_OR_ADD_ON_INACTIVE');
            } else {
                return $this->success(['order_id' => $result],'ORDER_CREATED');
            }
            
        } catch (Exception $e) {
            return $this->error('ERROR');
        }
    }
    
    /**
     * make order from cart id
     * @param type $cartObj
     * @param type $request
     */
    public function makeOrderFromCart($cartObj, $request) {
        DB::beginTransaction();
        $orderArr = $orderItemArr = [];
        try {
            $order = new Orders();
            $order->user_id = $orderArr['user_id'] = $cartObj->user_id;
            $order->guest_user_id = $orderArr['guest_user_id'] = $cartObj->guest_user_id;
            $kitchen = Kitchens::where(['id' => $cartObj->kitchen_id])->first()->toArray();
            $order->chef_id = $orderArr['chef_id'] = $kitchen['chef_id'];
            $chefsDt = Chef::where('id', $kitchen['chef_id'])->first();
            $orderArr['chef_name'] = $chefsDt->name;
            $orderArr['image'] = config('aws.aws_s3_url').config('aws.aws_s3_kitchen_images_bucket').$kitchen['chef_id'].config('aws.thumbnails_200x200').$chefsDt->profile_pic;
            $order->kitchen_id = $orderArr['kitchen_id'] = $cartObj->kitchen_id;
            $order->promo_code = $orderArr['promo_code'] = $cartObj->promo_code;
            $order->company_discount = $orderArr['company_discount'] = $cartObj->company_discount;
            $order->total = $orderArr['total'] = $cartObj->total;
            $order->delivery_fee = $orderArr['delivery_fee'] = $cartObj->delivery_fee;
            $order->discount = $orderArr['discount'] = $cartObj->discount;
            $order->tax = $orderArr['tax'] = $cartObj->tax;
            $order->grand_total = $orderArr['grand_total'] = $cartObj->grand_total;
            $order->delivery_type = $orderArr['delivery_type'] = isset($request['delivery_type']) ? $request['delivery_type'] : "HomeDelivery";
            if($request['preorder_time']) {
                $order->preorder_time = $orderArr['preorder_time'] = $request['preorder_time'];
            }
            $order->cooking_instructions = $orderArr['cooking_instructions'] = isset($request['cooking_instructions']) ? $request['cooking_instructions'] : "HomeDelivery";
            $order->delivery_latitude = $orderArr['delivery_latitude'] = $request['delivery_latitude'];
            $order->delivery_longitude = $orderArr['delivery_longitude'] = $request['delivery_longitude'];
            $order->delivery_address = $orderArr['delivery_address'] = $request['delivery_address'];
            $order->contact_person_no = $orderArr['contact_person_no'] = $request['contact_person_no'];
            $order->save();
            //make order items from cart items
            $cartItems = CartItems::getCartItems($cartObj->id);
            $i = 0;
            foreach($cartItems as $items) {
                if(isset($items->status) && $items->status != 'Active') {
                    return "ITEM_OR_ADD_ON_INACTIVE";
                    exit;
                }
                $orderItems = new OrderItems();
                $orderItems->order_id = $order->id;
                $orderItems->kitchen_item_id = $orderItemArr[$i]['kitchen_item_id'] = $items->kitchen_item_id;
                $orderItems->item_name = $orderItemArr[$i]['item_name'] = $items->item_name;
                $orderItemArr[$i]['item_name_ar'] = $items->item_name_ar; //save arabic in json
                $orderItems->varient_id = $orderItemArr[$i]['varient_id'] = $items->varient_id;
                $orderItems->varient_name = $orderItemArr[$i]['varient_name'] = ($items->varient_name != null) ? $items->varient_name : '';
                $orderItemArr[$i]['varient_name_ar'] = ($items->varient_name_ar != null) ? $items->varient_name_ar : ''; //save arabic in json
                $orderItems->quantity = $orderItemArr[$i]['quantity'] = $items->quantity;
                $orderItems->item_instruction = $orderItemArr[$i]['item_instruction'] = $items->item_instruction;
                $orderItems->price = $orderItemArr[$i]['price'] = $items->price;
                $orderItems->save();
                //make order add ons from cart add ons
                $cartAddOns = CartAddOn::getCartAddons($items->id);
                if(!empty($cartAddOns)) {
                    $j = 0;
                    foreach($cartAddOns as $addOn) {
                        if(isset($items->status) && $addOn->status != 'Active') {
                            return "ITEM_OR_ADD_ON_INACTIVE";
                            exit;
                        }
                        $orderAddOn = new OrderAddOn();
                        $orderAddOn->order_item_id = $orderItems->id;
                        $orderAddOn->add_on_id = $orderItemArr[$i]['addons'][$j]['add_on_id'] = $addOn->add_on_id;
                        $orderAddOn->add_on_name = $orderItemArr[$i]['addons'][$j]['add_on_name'] = $addOn->add_on_name;
                        $orderItemArr[$i]['addons'][$j]['add_on_name_ar'] = $addOn->add_on_name_ar; //save arabic in json
                        $orderAddOn->price = $orderItemArr[$i]['addons'][$j]['price'] = $addOn->add_on_price;
                        $orderAddOn->save();
                        $j++;
                    }
                }
                $i++;
            }
            $orderArr['items'] = $orderItemArr;
            $order = Orders::where('id', $order->id)->first();
            $order->order_json = json_encode($orderArr);
            $order->save();
            DB::commit();
            return $order->id;
        } catch (Exception $ex) {
            DB::rollback();
            throw new Exception("");
        }
    }
    
    /**
     * make order items from cart items
     * @param type $order_id
     * @param type $cart_id
     */
    function makeOrderItemsFromCartItems($order_id, $cart_id) {
        DB::beginTransaction();
        try {
            $cartItems = CartItems::getCartItems($cart_id);
            foreach($cartItems as $items) {
                $orderItems = new OrderItems();
                $orderItems->order_id = $order_id;
                $orderItems->kitchen_item_id = $items->kitchen_item_id;
                $orderItems->item_name = $items->item_name;
                $orderItems->varient_id = $items->varient_id;
                $orderItems->varient_name = ($items->varient_name != null) ? $items->varient_name : '';
                $orderItems->quantity = $items->quantity;
                $orderItems->price = $items->price;
                $orderItems->save();
                $this->makeOrderAddonsFromCartAddons($orderItems->id, $items->id);
                DB::commit();
            }
        } catch (Exception $ex) {
            DB::rollback();
            throw new Exception("");
        }
    }
    
    /**
     * make order add ons from cart add ons
     * @param type $order_item_id
     * @param type $cart_item_id
     */
    function makeOrderAddonsFromCartAddons($order_item_id, $cart_item_id) {
        DB::beginTransaction();
        try {
            $cartAddOns = CartAddOn::getCartAddons($cart_item_id);
            foreach($cartAddOns as $addOn) {
                $orderAddOn = new OrderAddOn();
                $orderAddOn->order_item_id = $order_item_id;
                $orderAddOn->add_on_id = $addOn->add_on_id;
                $orderAddOn->add_on_name = $addOn->add_on_name;
                $orderAddOn->price = $addOn->add_on_price;
                $orderAddOn->save();
                DB::commit();
            }
        } catch (Exception $ex) {
            DB::rollback();
            throw new Exception("");
        }
    }
    
    /**
     * Get order details
     * @param Request $request
     * @return type
     */
    public function getOrderDetails(Request $request) {
        $validation = Validator::make($request->all(), ['order_id' => 'required|integer|exists:orders,id']);
        if ($validation->fails()) {
            return $this->validationError($validation);
        }
        //get user id or guest user id
        $user_id = $guest_user_id = null;
        if(!empty($this->currentUser->id)) {
            $user_id = $this->currentUser->id;
        } else {
            $guestUserObj = $this->getGuestUserDetails();
            if(!empty($guestUserObj->id)) {
                $guest_user_id = $guestUserObj->id;
            }
        }
        
        //return if both user id and guest user id not found
        if($guest_user_id == NULL && $user_id == NULL) {
            return $this->error('USER_OR_GUEST_USER_NOT_FOUND');
        }
        try {
            if($user_id) {
                $order = Orders::where(['user_id' => $user_id, 'id'=> $request->order_id])->first();
            } else {
                $order = Orders::where(['guest_user_id' => $guest_user_id, 'id'=> $request->order_id])->first();
            }
            // return if, Cart does not belongs to logged in user
            if(empty($order)) {
                return $this->error('USER_ORDER_MISMATCH');
            }
            $result = json_decode($order->order_json, 1);
            $result['status'] = $order->status;
            return $this->success($result, 'ORDER_FOUND');
        } catch (Exception $e) {
            return $this->error('ERROR');
        }
    }
    
    /**
     * Get all user's orders
     * @return type
     */
    public function getOrderList() {
        $user_id = $guest_user_id = null;
        if(!empty($this->currentUser->id)) {
            $user_id = $this->currentUser->id;
        } else {
            $guestUserObj = $this->getGuestUserDetails();
            if(!empty($guestUserObj->id)) {
                $guest_user_id = $guestUserObj->id;
            }
        }
        
        //return if both user id and guest user id not found
        if($guest_user_id == NULL && $user_id == NULL) {
            return $this->error('USER_OR_GUEST_USER_NOT_FOUND');
        }
        
        try {
            if($user_id) {
                $where = " user_id = $user_id AND O.status IN ('Placed','Cooking','Ready','OnTheWay','Completed') ";
            } else {
                $where = " guest_user_id =  $guest_user_id AND O.status IN ('Placed','Cooking','Ready','OnTheWay','Completed') ";
            }
            
            $orders = Orders::getOrderList($where);
            
            $result = $subResult = [];
            foreach($orders as $order) {
                $tempArr =[];
                $tempVar = '';
                $tempArr['id'] = $order->id;
                $tempArr['status'] = trans('message.'.$order->status);
                $tempArr['chef_name'] = $order->name;
                $tempArr['chef_image'] = $order->image;
                $tempArr['price'] = $order->grand_total;
                $tempVar = $order->item_name." X ".$order->quantity;
                $tempArr['items'] = $tempVar.', ';
                if(!empty($subResult[$order->id])) {
                    $subResult[$order->id]['items'] .= $tempVar;
                } else {
                    $subResult[$order->id] = $tempArr;
                }
            }
            
            foreach($subResult as $row) {
                $row['items'] = trim($row['items'], ', ');
                $result[] = $row;
            }
            
            $msg = (count($result) > 0 ? "LIST" : "NO_RECORD_FOUND");
            return $this->success(['list' => $result], $msg);
        } catch (Exception $e) {
            return $this->error('ERROR');
        }
    }

}