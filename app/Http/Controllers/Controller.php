<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use App\Models\GuestUsers;
use App\Models\Chef;
use App\Models\User;
use App\Models\Otps;
use App\Models\Orders;
use App\Models\Cart;
use App\Models\CartItems;
use App\Models\CartAddOn;
use App\Models\Kitchens;
use App\Models\Groups;
use App\Models\KitchenItems;
use App\Models\KitchenItemOrder;
use App\Models\KitchenItemVarients;
use App\Models\kitchenAddOnCategory;
use App\Models\KitchenItemsAddOnAssoc;
use DB;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
use Mail;
use Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function sendSMS($mobile, $message){
        try{
            //.env variables
            $SMS_USER = env('SMS_USER');//"wagon"
            $SMS_PASSWORD = env('SMS_PASSWORD');//"wagonmppsms1"
            $SMS_LANG_ENG = env('SMS_LANG_ENG');//"1"
            $SMS_LANG_AR = env('SMS_LANG_AR');//"3"
            $SMS_SENDER = env('SMS_SENDER');//"WAGON"

            $message = urlencode($message);
            $url = "http://api.mpp-sms.com/api/send.aspx?username=".$SMS_USER."&password=".$SMS_PASSWORD."&language=".$SMS_LANG_ENG."&sender=".$SMS_SENDER."&mobile=".$mobile."&message=".$message;

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30000,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HEADER => FALSE,
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                return $err;
            } 
            else {
                $response = explode(',', $response);
                return $response;
            }
        }
        catch(\Exception $e){
            return response()->json([
                 'status'=> 0,
                 'error' => $e->getMessage(),
                 'message' => 'Something went wrong'
            ], 200);
        }
    }
    
    /**
     * get firebase connection object
     * @return type
     */
    public function firebaseConnect() {
        $serviceAccount = ServiceAccount::fromJsonFile(config('app.FIREBASEACCOUNT'));
        $firebase = (new Factory)
                    ->withServiceAccount($serviceAccount)
                    ->create();
        return $firebase->getDatabase();
    }
    
    public function getGuestUserDetails() {
        if(isset($_SERVER['HTTP_AUTHORIZATION']) && $_SERVER['HTTP_AUTHORIZATION']) {
            $authArr = explode(' ', $_SERVER['HTTP_AUTHORIZATION']);
            $token = (!empty($authArr[1]) ? $authArr[1] : '');
            return GuestUsers::where(['device_token' => $token])->first();
        }
    }
    
    /**
     * Get user by phone number, used to check phone number already taken or not
     * @param type $userType
     * @param type $phone
     * @return type
     */
    public function getUserByPhoneNumber($userType, $phone) {
        if($userType == 'USER') {
            return User::where(['phone_number' => $phone])->first();
        }
        
        if($userType == 'GUEST_USER') {
            return GuestUsers::where(['phone_number' => $phone])->first();
        }
    }
    
    /**
     * Get user by Email, used to check phone number already taken or not
     * @param type $userType
     * @param type $phone
     * @return type
     */
    public function getUserByEmail($userType, $email) {
        if($userType == 'USER') {
            return User::where(['email' => $email])->first();
        }
        
        if($userType == 'GUEST_USER') {
            return GuestUsers::where(['email' => $email])->first();
        }
    }
    
    /**
     * verify otp and then delete record from otps table
     * @param type $phn
     * @param type $otp
     * @return boolean
     */
    public function verifyOTP($phn, $otp) {
        $otp = Otps::where(['mobile' => $phn, 'otp' => $otp])->first();
        if(!empty($otp)) {
            Otps::where(['mobile' => $phn])->delete();
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Change order status
     * @param type $order
     */
    public function updateOrderStatus($order_id, $status) {
        DB::beginTransaction();
        try {
            $order = Orders::where(['id' => $order_id])->first();
            $order->update(['status' => $status]);
            DB::commit();
            //change status in firebase
            $database = $this->firebaseConnect();
            $database->getReference('orders/chefs/'.$order->chef_id.'/'.$order_id)
                     ->update(['status' => $status]);
            return true;
        } catch (Exception $ex) {
             DB::rollback();
            return false;
        }
    }
    
    /**
     * Update Kitchen Open Status
     * @param type $order
     */
    public function updateKitchenOpenStatus($chef_id, $status) {
        DB::beginTransaction();
        try {
            $kitchen = Kitchens::where(['chef_id' => $chef_id])->first();
            $kitchen->open = $status;
            $kitchen->save();
            DB::commit();
            return true;
        } catch (Exception $ex) {
             DB::rollback();
            return false;
        }
    }
    
    /**
     * Get disctace between two points
     * source: https://www.geodatasource.com/developers/php
     * test : https://developers.google.com/maps/documentation/geocoding/intro
     * @param type $lat1
     * @param type $lon1
     * @param type $lat2
     * @param type $lon2
     * @param type $unit
     * @return int
     */
    public function distance($lat1, $lon1, $lat2, $lon2, $unit) {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        }
        else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
              return ($miles * 1.609344);
            } else if ($unit == "N") {
              return ($miles * 0.8684);
            } else {
              return $miles;
            }
        }
    }
    
    /**
    * Calculates the great-circle distance between two points, with
    * the Haversine formula.
    * @param float $latitudeFrom Latitude of start point in [deg decimal]
    * @param float $longitudeFrom Longitude of start point in [deg decimal]
    * @param float $latitudeTo Latitude of target point in [deg decimal]
    * @param float $longitudeTo Longitude of target point in [deg decimal]
    * @param float $earthRadius Mean earth radius in [m]
    * @return float Distance between points in [m] (same as earthRadius)
    */
   function haversineGreatCircleDistance(
     $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000) {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
          cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
   }
   
   /**
 * Calculates the great-circle distance between two points, with
 * the Vincenty formula.
 * @param float $latitudeFrom Latitude of start point in [deg decimal]
 * @param float $longitudeFrom Longitude of start point in [deg decimal]
 * @param float $latitudeTo Latitude of target point in [deg decimal]
 * @param float $longitudeTo Longitude of target point in [deg decimal]
 * @param float $earthRadius Mean earth radius in [m]
 * @return float Distance between points in [m] (same as earthRadius)
 */
public static function vincentyGreatCircleDistance(
  $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
          pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);
        return $angle * $earthRadius;
    }
    
    /**
     * clear cart on cart id
     * @param type $cart_id
     * @return type
     */
    public function clearCartOncartId($cart_id) {
        DB::beginTransaction();
        try {
            //delete add on first
            $cartItemArr = CartItems::where(['cart_id' => $cart_id])->get()->toArray();
            foreach($cartItemArr as $ele) {
                $carAddOn = CartAddOn::where('cart_item_id', $ele['id'])->delete();
            }
            //delete cart items
            CartItems::where('cart_id', $cart_id)->delete();
            
            //setCartValuesToInitial
            $cart = Cart::where(['id' => $cart_id])->first();
            $cart->promo_code = '';
            $cart->company_discount = $cart->kitchen_id = 0;
            $cart->total = $cart->discount = $cart->grand_total = $cart->tax = $cart->delivery_fee = "0.00";
            $cart->save();
            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();
            throw new Exception("");
        }
    }
    
    /**
     * Remove promocode
     * @param Request $request
     * @return type
     */
    public function removePromocodeFromCart($cart_id) {
        DB::beginTransaction();
        try {
            $cart = Cart::where(['id' => $cart_id])->first();
            $tax_per = 10;
            $cart->promo_code = "";
            $cart->discount = 0;
            $tax = ($cart->total / 100) * $tax_per;
            $cart->tax = $tax;
            $cart->grand_total = $cart->total + $tax + $cart->delivery_fee;
            $cart->save();
            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();
            throw new Exception("");
        }
    }
    
    /**
     * Get chef details
     * @param Request $request
     * @return type
     */
    public function authenticateChef() {
        if(isset($_SERVER['HTTP_AUTHORIZATION']) && $_SERVER['HTTP_AUTHORIZATION']) {
            $authArr = explode(' ', $_SERVER['HTTP_AUTHORIZATION']);
            $token = (!empty($authArr[1]) ? $authArr[1] : '');
            return Chef::where(['api_token' => $token])->first();
        }
    }
    
    /**
     * Common function to upload kitchen items
     * @param type $chef_id
     * @param type $kitchen_id
     * @param type $request
     */
    public function kitchenItemsBulkUpload_old($chef_id, $kitchen_id, $request) {
        $kitchen_item_sheet_rows = $kitchen_item_varient_sheet_rows = 
        $add_on_category_sheet_rows = $add_on_item_sheet_rows = [];
        
        $kitchen_item_sheet_path = $request->file('kitchen_item_sheet')->getRealPath();
        $kitchen_item_sheet_rows = array_map('str_getcsv', file($kitchen_item_sheet_path));
        $kitchen_item_sheet_rows = array_slice($kitchen_item_sheet_rows, 1);

        if($request->file('kitchen_item_varient_sheet') !== null) {
            $kitchen_item_varient_sheet_path = $request->file('kitchen_item_varient_sheet')->getRealPath();
            $kitchen_item_varient_sheet_rows = array_map('str_getcsv', file($kitchen_item_varient_sheet_path));
            $kitchen_item_varient_sheet_rows = array_slice($kitchen_item_varient_sheet_rows, 1);
        }
        
        if($request->file('add_on_category_sheet') !== null) {
            $add_on_category_sheet_path = $request->file('add_on_category_sheet')->getRealPath();
            $add_on_category_sheet_rows = array_map('str_getcsv', file($add_on_category_sheet_path));
            $add_on_category_sheet_rows = array_slice($add_on_category_sheet_rows, 1);
        }

        if($request->file('add_on_item_sheet') !== null) {
            $add_on_item_sheet_path = $request->file('add_on_item_sheet')->getRealPath();
            $add_on_item_sheet_rows = array_map('str_getcsv', file($add_on_item_sheet_path));
            $add_on_item_sheet_rows = array_slice($add_on_item_sheet_rows, 1);
        }

        $group = Groups::where(['chef_id' => $chef_id, 'group_name' => 'BULK_UPLOAD'])->first();
        if(empty($group)) {
            $group = Groups::create(['chef_id' => $chef_id, 'group_name' => 'BULK_UPLOAD', 'seq_no' => 1000]);
        }
        $group_id = $group->id;
        
        $response['noOfItemsGiven'] = count($kitchen_item_varient_sheet_rows);//total items provided
        $response['noOfItemsAdded'] = 0;//total items added
        //looping kitchen item data
        foreach($kitchen_item_sheet_rows as $kitchen_item_sheet_row) {
            $ifExistKitchenItems = KitchenItems::where('kitchen_id', $kitchen_id)->where('item_name', 'LIKE', $kitchen_item_sheet_row[1])->first();
            //skip item if already exists for this kitchen
            if(empty($ifExistKitchenItems)) {
                $input_kitchen_items = [];
                $input_kitchen_items['kitchen_id'] = $kitchen_id;
                $input_kitchen_items['item_name'] = $kitchen_item_sheet_row[1];
                $input_kitchen_items['item_name_ar'] = $kitchen_item_sheet_row[2];
                $input_kitchen_items['description'] = $kitchen_item_sheet_row[3];
                $input_kitchen_items['description_ar'] = $kitchen_item_sheet_row[4];
                $input_kitchen_items['average_prep_time'] = $kitchen_item_sheet_row[5];
                $input_kitchen_items['price'] = $kitchen_item_sheet_row[6];
                $input_kitchen_items['pure_veg'] = $kitchen_item_sheet_row[7];
                $input_kitchen_items['groups'] = $group_id;

                //Kitchen Items
                $kitchenItem = KitchenItems::create($input_kitchen_items);
                $kitchen_item_id = $kitchenItem->id;
                //Kitchen Items order
                KitchenItemOrder::create(['group_id' => $group_id, 'kitchen_item_id' => $kitchen_item_id,'seq_no' => 0]);

                //looping kitchen item varient data
                foreach($kitchen_item_varient_sheet_rows as $kitchen_item_varient_sheet_row) {
                    $input_kitchen_item_vareint = [];
                    if($kitchen_item_sheet_row[0] == $kitchen_item_varient_sheet_row[0]) {
                        $input_kitchen_item_vareint['kitchen_item_id'] = $kitchen_item_id;
                        $input_kitchen_item_vareint['varient_name'] = $kitchen_item_varient_sheet_row[1];
                        $input_kitchen_item_vareint['varient_name_ar'] = $kitchen_item_varient_sheet_row[2];
                        $input_kitchen_item_vareint['price'] = ($kitchen_item_varient_sheet_row[3] == '' ? 0.000 : $kitchen_item_varient_sheet_row[3]);
                        //Kitchen Items Varients
                        KitchenItemVarients::create($input_kitchen_item_vareint);
                    }
                }

                //looping kitchen add on category data
                foreach($add_on_category_sheet_rows as $add_on_category_sheet_row) {
                    $input_add_on_category = [];
                    if($kitchen_item_sheet_row[0] == $add_on_category_sheet_row[0]) {
                        $input_add_on_category['kitchen_item_id'] = $kitchen_item_id;
                        $input_add_on_category['category_name_en'] = $add_on_category_sheet_row[2];
                        $input_add_on_category['category_name_ar'] = $add_on_category_sheet_row[3];
                        $input_add_on_category['min'] = $add_on_category_sheet_row[4];
                        $input_add_on_category['max'] = $add_on_category_sheet_row[5];
                        $input_add_on_category['choices'] = "Multiple";
                        //Kitchen add on category
                        $kitchenAddOnCategory = KitchenAddOnCategory::create($input_add_on_category);
                        $kitchen_add_on_category_id = $kitchenAddOnCategory->id;

                        //looping kitchen add on items
                        foreach($add_on_item_sheet_rows as $add_on_item_sheet_row) {
                            $input_add_on_item = [];
                            if($kitchen_item_sheet_row[0] == $add_on_item_sheet_row[0]
                                && $add_on_category_sheet_row[1] == $add_on_item_sheet_row[1]) {
                                $input_add_on_item['kitchen_item_id'] = $kitchen_item_id;
                                $input_add_on_item['kitchen_add_on_cat_id'] = $kitchen_add_on_category_id;
                                $input_add_on_item['kitchen_add_on_item_name_en'] = $add_on_item_sheet_row[2];
                                $input_add_on_item['kitchen_add_on_item_name_ar'] = $add_on_item_sheet_row[3];
                                $input_add_on_item['price'] = ($add_on_item_sheet_row[4] == '' ? 0.000 : $add_on_item_sheet_row[4]);
                                $input_add_on_item['seq_no'] = 0;
                                //Kitchen add on Items
                                KitchenItemsAddOnAssoc::create($input_add_on_item);
                            }
                        }
                    }
                }
                
                $response['noOfItemsAdded']++;//total items added
            }
        }
        
        return $response;
    }
    
    public function kitchenItemsBulkUpload($chef_id, $kitchen_id, $request) {
        $GLOBALS['kitchen_item_groups'] = $GLOBALS['kitchen_item_sheet_rows'] =
        $GLOBALS['kitchen_item_varient_sheet_rows'] = $GLOBALS['add_on_category_sheet_rows'] = 
        $GLOBALS['add_on_item_sheet_rows'] = [];
        $kitchen_item_groups = $add_on_category_sheet_rows = $kitchen_item_varient_sheet_rows = 
        $add_on_category_sheet_rows = $add_on_item_sheet_rows = [];        
        
        Excel::selectSheets('groups')->load($request->file('kitchen_item_sheet'), function ($reader) {
            $GLOBALS['kitchen_item_groups'] = $reader->toArray();
        });
        
        if(count($GLOBALS['kitchen_item_groups']) == 0) {
            $response['error'] = 'Groups not found';
            return $response;
        }
        
        Excel::selectSheets('kitchen_items')->load($request->file('kitchen_item_sheet'), function ($reader) {
            $GLOBALS['kitchen_item_sheet_rows'] = $reader->toArray();
        });
        Excel::selectSheets('item_varients')->load($request->file('kitchen_item_sheet'), function ($reader) {
            $GLOBALS['kitchen_item_varient_sheet_rows'] = $reader->toArray();
        });
        Excel::selectSheets('add_on_categories')->load($request->file('kitchen_item_sheet'), function ($reader) {
            $GLOBALS['add_on_category_sheet_rows'] = $reader->toArray();
        });
        Excel::selectSheets('add_on_choices')->load($request->file('kitchen_item_sheet'), function ($reader) {
            $GLOBALS['add_on_item_sheet_rows'] = $reader->toArray();
        });
        
        $kitchen_item_sheet_rows = $GLOBALS['kitchen_item_sheet_rows'];

        if(count($GLOBALS['kitchen_item_groups']) > 0) {
            foreach($GLOBALS['kitchen_item_groups'] as $kitchen_item_group) {
                $group = Groups::where(['chef_id' => $chef_id, 'group_name' => $kitchen_item_group['group_name']])->first();
                if(empty($group)) {
                    $group = Groups::create(['chef_id' => $chef_id, 'group_name' => $kitchen_item_group['group_name'], 'seq_no' => 1000]);
                }
                $kitchen_item_groups[$kitchen_item_group['group_id']] = ['id' => $group->id, 'group_name' => $group->group_name];
            }
        }
        
        if(count($GLOBALS['kitchen_item_varient_sheet_rows']) > 0) {
            $kitchen_item_varient_sheet_rows = $GLOBALS['kitchen_item_varient_sheet_rows'];
        }
        if(count($GLOBALS['add_on_category_sheet_rows']) > 0) {
            $add_on_category_sheet_rows = $GLOBALS['add_on_category_sheet_rows'];
        }
        if(count($GLOBALS['add_on_item_sheet_rows']) > 0) {
            $add_on_item_sheet_rows = $GLOBALS['add_on_item_sheet_rows'];
        }

        $response['noOfItemsGiven'] = count($kitchen_item_varient_sheet_rows);//total items provided
        $response['noOfItemsAdded'] = 0;//total items added
        //looping kitchen item data
        foreach($kitchen_item_sheet_rows as $kitchen_item_sheet_row) {
            $ifExistKitchenItems = KitchenItems::where('kitchen_id', $kitchen_id)->where('item_name', 'LIKE', $kitchen_item_sheet_row['item_name'])->first();
            //skip item if already exists for this kitchen
            if(empty($ifExistKitchenItems)) {
                $input_kitchen_items = [];
                $input_kitchen_items['kitchen_id'] = $kitchen_id;
                $input_kitchen_items['item_name'] = $kitchen_item_sheet_row['item_name'];
                $input_kitchen_items['item_name_ar'] = $kitchen_item_sheet_row['item_name_ar'];
                $input_kitchen_items['description'] = $kitchen_item_sheet_row['description'];
                $input_kitchen_items['description_ar'] = $kitchen_item_sheet_row['description_ar'];
                $input_kitchen_items['average_prep_time'] = $kitchen_item_sheet_row['average_prep_time'];
                $input_kitchen_items['price'] = $kitchen_item_sheet_row['price'];
                $input_kitchen_items['pure_veg'] = $kitchen_item_sheet_row['pure_veg'];
                $currentItemGroupsArr = explode(',', $kitchen_item_sheet_row['group_id']);
                $currentItemGroups = $sep = '';
                foreach($currentItemGroupsArr as $currentItemGroup) {
                    $currentItemGroups .= $sep.$kitchen_item_groups[$currentItemGroup]['id'];
                    $sep = ',';
                }
//                $input_kitchen_items['groups'] = $kitchen_item_groups[$kitchen_item_sheet_row['group_id']]['id'];
                $input_kitchen_items['groups'] = $currentItemGroups;

                //Kitchen Items
                $kitchenItem = KitchenItems::create($input_kitchen_items);
                $kitchen_item_id = $kitchenItem->id;
                //Kitchen Items order
                $currentItemGroups = explode(',', $currentItemGroups);
                foreach($currentItemGroups as $currentItemGroup) {
                    KitchenItemOrder::create(['group_id' => $currentItemGroup, 'kitchen_item_id' => $kitchen_item_id, 'seq_no' => 0]);
                }

                //looping kitchen item varient data
                foreach($kitchen_item_varient_sheet_rows as $kitchen_item_varient_sheet_row) {
                    $input_kitchen_item_vareint = [];
                    if($kitchen_item_sheet_row['item_id'] == $kitchen_item_varient_sheet_row['item_id']) {
                        $input_kitchen_item_vareint['kitchen_item_id'] = $kitchen_item_id;
                        $input_kitchen_item_vareint['varient_name'] = $kitchen_item_varient_sheet_row['varient_name'];
                        $input_kitchen_item_vareint['varient_name_ar'] = $kitchen_item_varient_sheet_row['varient_name_ar'];
                        $input_kitchen_item_vareint['varient_price'] = ($kitchen_item_varient_sheet_row['varient_price'] == '' ? 0.000 : $kitchen_item_varient_sheet_row['varient_price']);
                        //Kitchen Items Varients
                        KitchenItemVarients::create($input_kitchen_item_vareint);
                    }
                }

                //looping kitchen add on category data
                foreach($add_on_category_sheet_rows as $add_on_category_sheet_row) {
                    $input_add_on_category = [];
                    if($kitchen_item_sheet_row['item_id'] == $add_on_category_sheet_row['item_id']) {
                        $input_add_on_category['kitchen_item_id'] = $kitchen_item_id;
                        $input_add_on_category['category_name_en'] = $add_on_category_sheet_row['category_name_en'];
                        $input_add_on_category['category_name_ar'] = $add_on_category_sheet_row['category_name_ar'];
                        $input_add_on_category['min'] = $add_on_category_sheet_row['min'];
                        $input_add_on_category['max'] = $add_on_category_sheet_row['max'];
                        $input_add_on_category['choices'] = "Multiple";
                        //Kitchen add on category
                        $kitchenAddOnCategory = KitchenAddOnCategory::create($input_add_on_category);
                        $kitchen_add_on_category_id = $kitchenAddOnCategory->id;

                        //looping kitchen add on items
                        foreach($add_on_item_sheet_rows as $add_on_item_sheet_row) {
                            $input_add_on_item = [];
                            if($kitchen_item_sheet_row['item_id'] == $add_on_item_sheet_row['item_id']
                                && $add_on_category_sheet_row['add_on_cat_id'] == $add_on_item_sheet_row['add_on_cat_id']) {
                                $input_add_on_item['kitchen_item_id'] = $kitchen_item_id;
                                $input_add_on_item['kitchen_add_on_cat_id'] = $kitchen_add_on_category_id;
                                $input_add_on_item['kitchen_add_on_item_name_en'] = $add_on_item_sheet_row['kitchen_add_on_item_name_en'];
                                $input_add_on_item['kitchen_add_on_item_name_ar'] = $add_on_item_sheet_row['kitchen_add_on_item_name_ar'];
                                $input_add_on_item['price'] = ($add_on_item_sheet_row['price'] == '' ? 0.000 : $add_on_item_sheet_row['price']);
                                $input_add_on_item['seq_no'] = 0;
                                //Kitchen add on Items
                                KitchenItemsAddOnAssoc::create($input_add_on_item);
                            }
                        }
                    }
                }
                
                $response['noOfItemsAdded']++;//total items added
            }
        }
        
        return $response;
    }
    
    /**
     * Download kitchen menu items
     * @return type
     */
    public function downloadKitchenMenu($chef_id, $kitchen_id) {
        $GLOBALS['chef_id'] = $chef_id;
        $GLOBALS['kitchen_id'] = $kitchen_id;
        $groups = Groups::select('id AS group_id', 'group_name')
                ->where(['chef_id' => $chef_id])->orderBy('id', 'ASC')
                ->get()->toArray();
        $filename = str_replace(" ","_", Auth::guard('chef')->user()->name).date("-H_i_s_m_d_Y");
        return Excel::create($filename, function($excel) use ($groups) {
            //make group sheet
            $excel->sheet('groups', function($sheet) use ($groups) {
                $sheet->fromArray($groups);
            });
            
            //make kitchen items sheet
            $kitchen_items_ids = KitchenItems::select('id')
                    ->where('kitchen_id', $GLOBALS['kitchen_id'])->orderBy('id', 'ASC')
                    ->get()->toArray();
            $kitchen_items = KitchenItems::select('groups', 'id AS item_id','item_name', 'item_name_ar','description',
                    'description_ar','average_prep_time', 'price', 'pure_veg')
                    ->where('kitchen_id', $GLOBALS['kitchen_id'])->orderBy('id', 'ASC')
                    ->get()->toArray();
            $excel->sheet('kitchen_items', function($sheet) use ($kitchen_items) {
                $sheet->fromArray($kitchen_items);
            });
            
            //make kitchen items variant sheet
            $kitchen_itemVariants = KitchenItemVarients::select('kitchen_item_id AS item_id', 'varient_name', 'varient_name_ar','varient_price')
                    ->whereIn('kitchen_item_id', $kitchen_items_ids)->orderBy('kitchen_item_id', 'ASC')->orderBy('id', 'ASC')
                    ->get()->toArray();
            $excel->sheet('item_varients', function($sheet) use ($kitchen_itemVariants) {
                $sheet->fromArray($kitchen_itemVariants);
            });
            
            //make add on categories sheet
            $kitchen_item_add_on_cats = KitchenAddOnCategory::select('kitchen_item_id AS item_id', 'id AS add_on_cat_id',
                    'category_name_en','category_name_ar','min','max')->whereIn('kitchen_item_id', $kitchen_items_ids)
                    ->orderBy('kitchen_item_id', 'ASC')->orderBy('id', 'ASC')->get()->toArray();
            $excel->sheet('add_on_categories', function($sheet) use ($kitchen_item_add_on_cats) {
                $sheet->fromArray($kitchen_item_add_on_cats);
            });

//            //make add on choices sheet
            $kitchen_item_add_on_choices = KitchenItemsAddOnAssoc::select('kitchen_item_id AS item_id',
                    'kitchen_add_on_cat_id AS add_on_cat_id', 'kitchen_add_on_item_name_en',
                    'kitchen_add_on_item_name_ar', 'price')
                    ->whereIn('kitchen_item_id', $kitchen_items_ids)
                    ->orderBy('kitchen_item_id', 'ASC')->orderBy('id', 'ASC')->get()->toArray();
            $excel->sheet('add_on_choices', function($sheet) use ($kitchen_item_add_on_choices) {
                $sheet->fromArray($kitchen_item_add_on_choices);
            });
            
        })->download('xlsx');
        
    }
    
    /**
      * create my fatoorah intitate token
      * @return type
      */
     public function createMyFatoorahToken() {
       $curl = curl_init();
       curl_setopt($curl, CURLOPT_URL, config('app.MY_FATOORAH_URL').'Token');
       curl_setopt($curl, CURLOPT_POST, 1);
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
       curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
       curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array('grant_type' => 'password','username' => config('app.MY_FATOORAH_USERNAME'),'password' => config('app.MY_FATOORAH_PASSWORD'))));
       $result = curl_exec($curl);
       curl_close($curl);
       $json = json_decode($result, true);
       return $json;
    }
    
    /**
    * Send mail function
    * @param type $mailData
    * @param type $to
    * @param type $name
    * @param type $subject
    */
    public function sendMail($template, $mailData, $to, $name, $subject) {
        if($name == null || $name == '') {
            $name = 'Customer';
        }
        if(env('APP_ENV') != 'production') {
            $subject = "Test - ".$subject;
        }
        //(env('APP_ENV') == 'production' || env('APP_ENV') == 'test') && 
        if(($to != null && $to != '')) {
            Mail::send($template, $mailData, function($message) use ($to, $name, $subject){
                $message->to($to, $name)->subject($subject);
            });	
        }
    }
    
    /**
    * Arrange address format
    * @param type $area
    * @param type $block
    * @param type $street
    * @param type $additional_details
    * @param type $address
    * @return type
    */
   public function arrangeAddress($gov, $area, $block, 
                            $street, $avenue, $building, $floor, $apartment_no, $additional_directions) {
       $addresStr = "";
       $addresStr .= ($gov != '') ? "<b>Governorate: </b>".$gov.", " : "" ;
       $addresStr .= ($area != '') ? "<b>Area: </b>".$area.", " : "" ;
       $addresStr .= ($block != '') ? "<b>Block: </b>".$block.", " : "" ;
       $addresStr .= ($street != '') ? "<b>Street: </b>".$street.", " : "" ;
       $addresStr .= ($avenue != '') ? "<b>Avenue: </b>".$avenue.", " : "" ;
       $addresStr .= ($building != '') ? "<b>Building: </b>".$building.", " : "" ;
       $addresStr .= ($floor != '') ? "<b>Floor: </b>".$floor.", " : "" ;
       $addresStr .= ($apartment_no != '') ? "<b>Apartment No: </b>".$apartment_no.", " : "" ;
       $addresStr .= ($additional_directions != '') ? "<b>Additional Directions: </b>".$additional_directions.", " : "" ;
       return $addresStr;
   }
}
