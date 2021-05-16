<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Validator;
use App\Models\Orders;
use App\Models\Cart;
use App\Models\User;
use App\Models\GuestUsers;
use App\Models\WalletTransactions;
use App\Models\PaymentTransactions;
use App\Http\Helpers\CommonTrait;
use App\Http\Helpers\ResponseTrait;
use Exception;
use DB;
use Auth;

class PaymentController extends Controller 
{
    use CommonTrait, ResponseTrait;

    public function __construct() {
        $this->currentUser = Auth::guard('api')->user();
        $this->callbackUrl = url('/payment-success');
        $this->errorUrl = url('/payment-error');
        $this->myFatoorahToken = null;
        $this->myFatoorahUrl = config('app.MY_FATOORAH_URL');
        $this->myFatoorahUsername = config('app.MY_FATOORAH_USERNAME');
        $this->myFatoorahPassword = config('app.MY_FATOORAH_PASSWORD');
    }
    
    /**
    * Pay from card
    * @param Request $request
    * @return type
    */
    public function payFromCard(Request $request) {
        $validationRules = ['order_id' => 'required|integer|exists:orders,id', 'card_number' => 'required|min:12', 'exp_month' => 'required|integer|between:1,12', 'exp_year' => 'required|integer', 'cvc' => 'required|integer'];
        
        $validation = Validator::make($request->all(), $validationRules);
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
        $stArr = [];//stripe transaction input array
        DB::beginTransaction();
        try {
            if($user_id) {
                $order = Orders::where(['user_id' => $user_id, 'id'=> $request->order_id])->first();
                $cartObj = Cart::where(['user_id' => $user_id])->first();
                $stArr['user_id'] = $user_id;
            } else {
                $order = Orders::where(['guest_user_id' => $guest_user_id, 'id'=> $request->order_id])->first();
                $cartObj = Cart::where(['guest_user_id' => $guest_user_id])->first();
                $stArr['guest_user_id'] = $guest_user_id;
            }
            // return if, order does not belongs to logged in user
            if(empty($order)) {
                return $this->error('USER_CART_MISMATCH');
            }
            
            //return if already paid
            if($order->payment_status == 'DONE') {
                return $this->error('PAYMENT_ALREADY_DONE');
            }
            
            
            $stripe = Stripe::make(config('services.stripe.secret'));
            $token = $stripe->tokens()->create([
                'card' => [
                    'number' => $request->card_number,
                    'exp_month' => $request->exp_month,
                    'exp_year' => $request->exp_year,
                    'cvc' => $request->cvc
                ]
            ]);

            if (!isset($token['id'])) {
                return $this->error('PAYMENT_TOKEN_NOT_FOUND');
            }

            $charge = $stripe->charges()->create([
                'card' => $token['id'],
                'currency' => 'gbp',
                'amount' => $order->grand_total,
                'capture' => true,
            ]);
            //save complete response
            if($charge['status'] == 'succeeded') {
                $order->status = "Placed";
                $order->payment_method = "STRIPE_CARD";
                $order->payment_status = "DONE";
                $order->receipt_url = !empty($charge['receipt_url']) ? $charge['receipt_url'] : NULL;
                $order->save();
                //save data in strip transactions table
                $stArr['order_id'] = $order->id;
                $stArr['type'] = $stArr['transaction_status'] = 1;
                $stArr['transaction_id'] = $charge['id'];
                $stArr['response'] = json_encode($charge);
                $stArr['status'] = "DONE";
                PaymentTransactions::create($stArr);
                //call common function to clear cart
                $this->clearCartOncartId($cartObj->id);
                DB::commit();
                $this->createOrderNodeInFB($order);//enter new node in kitchen in firbase
                $this->notifyOnOrder($user_id, $guest_user_id, "NOTIFY_USER_ON_ORDER_CREATE");//notify user
                return $this->success('', "ORDER_PAYMENT_DONE");
            } else {
                $order->payment_method = "STRIPE_CARD";
                $order->payment_status = "NOT_DONE";
                $order->status = "PaymentIssue";
                $order->save();
                //save data in strip transactions table
                $stArr['order_id'] = $order->id;
                $stArr['type'] = 1;
                $stArr['transaction_id'] = $charge['id'];
                $stArr['transaction_status'] = 0;
                $stArr['response'] = json_encode($charge);
                $stArr['status'] = "PaymentIssue";
                PaymentTransactions::create($stArr);
                DB::commit();
                return $this->error('', 'ORDER_PAYMENT_ISSUE');
            }
        } catch(\Cartalyst\Stripe\Exception\NotFoundException $e) {
            DB::rollback();
            return $this->error($e->getMessage());
        } catch(\Cartalyst\Stripe\Exception\BadRequestException $e) {
            DB::rollback();
            return $this->error($e->getMessage());
        } catch(\Cartalyst\Stripe\Exception\UnauthorizedException $e) {
            DB::rollback();
            return $this->error($e->getMessage());
        } catch(\Cartalyst\Stripe\Exception\InvalidRequestException $e) {
            DB::rollback();
            return $this->error($e->getMessage());
        } catch(\Cartalyst\Stripe\Exception\CardErrorException $e) {
            DB::rollback();
            return $this->error($e->getMessage());
        } catch(\Cartalyst\Stripe\Exception\ServerErrorException $e) {
            DB::rollback();
            return $this->error($e->getMessage());
        } catch (Exception $e) {
            DB::rollback();
            return $this->error('ERROR_OR_STRIPE_ERROR');
        }
    }
    
    /**
     * Pay from Wallet
     * @param Request $request
     * @return type
     */
    public function payFromWallet(Request $request) {
        $validationRules = ['order_id' => 'required|integer|exists:orders,id'];
        
        $validation = Validator::make($request->all(), $validationRules);
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
        
        DB::beginTransaction();
        try {
            if($user_id) {
                $order = Orders::where(['user_id' => $user_id, 'id'=> $request->order_id])->first();
                $walletInfo = User::where(['id' => $user_id])->first();
            } else {
                $order = Orders::where(['guest_user_id' => $guest_user_id, 'id'=> $request->order_id])->first();
                $walletInfo = GuestUsers::where(['id' => $guest_user_id])->first();
            }
            // return if, order does not belongs to logged in user
            if(empty($order)) {
                return $this->error('USER_CART_MISMATCH');
            }
            
            //return if already paid
            if($order->payment_status == 'DONE') {
                return $this->error('PAYMENT_ALREADY_DONE');
            }
            
            //check balance in wallet
            if($walletInfo->wallet < $order->grand_total) {
                return $this->error('NOT_ENOUGH_BALANCE_IN_WALLET');
            }
            
            //update in wallet table
            $walletInfo->wallet = $walletInfo->wallet - $order->grand_total;
            $walletInfo->save();
            
            //update in order table
            $order->status = "Placed";
            $order->payment_method = "WALLET";
            $order->payment_status = "DONE";
            $order->save();
            
            /*save in wallet transaction table*/
            $input['order_id'] = $order->id;
            $input['user_id'] = $user_id;
            $input['guest_user_id'] = $guest_user_id;
            $input['amount'] = $order->grand_total;
            $input['transaction_type'] = 'debit';
            $input['type'] = 'payment';
            WalletTransactions::create($input);
            //call common function to clear cart
            $this->clearCartOncartId($cartObj->id);
            DB::commit();
            $this->createOrderNodeInFB($order);//enter new node in kitchen in firbase
            $this->notifyOnOrder($user_id, $guest_user_id, "NOTIFY_USER_ON_ORDER_CREATE");//notify user
            return $this->success('', "ORDER_PAYMENT_DONE");
        } catch (Exception $e) {
            DB::rollback();
            return $this->error('ERROR');
        }
    }
    
    /**
     * Enter node in Firebase
     * @param type $order
     */
    public function createOrderNodeInFB($order) {
        $inputArr['order_id'] = $order->id;
        $inputArr['user_id'] = $order->user_id;
        $inputArr['guest_user_id'] = $order->guest_user_id;
        $inputArr['chef_id'] = $order->chef_id;
        $inputArr['kitchen_id'] = $order->kitchen_id;
        $inputArr['status'] = $order->status;
        $inputArr['payment_method'] = $order->payment_method;
        $inputArr['payment_status'] = $order->payment_status;
        $inputArr['order_json'] = $order->order_json;
        $database = $this->firebaseConnect();
        $database->getReference('orders/chefs/'.$order->chef_id.'/'.$order->id)
                                    ->set($inputArr);
    }
    
    
    /**
    * My fatoorah payment function
    * @param Request $request
    * @return type
    */
    public function pay(Request $request) {
        $validationRules = ['order_id' => 'required|integer|exists:orders,id'];
        
        $validation = Validator::make($request->all(), $validationRules);
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
        
        DB::beginTransaction();
        try {
            if($user_id) {
                $order = Orders::where(['user_id' => $user_id, 'id'=> $request->order_id])->first();
                $cartObj = Cart::where(['user_id' => $user_id])->first();
                $orderArr['user_id'] = $user_id;
            } else {
                $order = Orders::where(['guest_user_id' => $guest_user_id, 'id'=> $request->order_id])->first();
                $cartObj = Cart::where(['guest_user_id' => $guest_user_id])->first();
                $orderArr['guest_user_id'] = $guest_user_id;
            }
            // return if, order does not belongs to logged in user
            if(empty($order)) {
                return $this->error('USER_CART_MISMATCH');
            }
            
            //return if already paid
            if($order->payment_status == 'DONE') {
                return $this->error('PAYMENT_ALREADY_DONE');
            }

            //create my fatoorah token
            $json = $this->createMyFatoorahToken();

            if(isset($json['access_token']) && !empty($json['access_token'])) {
                $this->myFatoorahToken = $json['access_token'];
                
                //create product list
                $PRODUCT['ProductId'] = null;
                $PRODUCT['ProductName'] = "Order Id : ".$order->id;
                $PRODUCT['Quantity'] = 1;
                $PRODUCT['UnitPrice'] = $order->grand_total;
                $PRODUCT_LIST[] = $PRODUCT;
                
                $PRODUCT_LIST = json_encode($PRODUCT_LIST);
                
                //make payment
                $orderArr['order_id'] = $order->id;
                $orderArr['order_value'] = $order->grand_total;
                $orderArr['delivery_address'] = $order->delivery_address;
                $orderArr['contact_person_no'] = $order->contact_person_no;
                $result = $this->makeMyFatoorahInvoice($PRODUCT_LIST, $orderArr);
                if(empty($result['payment_url'])) {
                    DB::rollback();
                    return $this->error('PAYMENT_URL_ISSUE');
                }
                
                //update payment status in order table
                $order->payment_method = "MY_FATOORAH";
                $order->payment_status = "INITIALIZING";
                $order->save();
                
                DB::commit();
                $this->createOrderNodeInFB($order);//enter new node in kitchen in firbase
                $this->notifyOnOrder($user_id, $guest_user_id, "NOTIFY_USER_ON_ORDER_CREATE");//notify user
                return $this->success(['payment_url' => $result['payment_url']], "PAYMENT_URL");
            } else {
                DB::rollback();
                return $this->error('PAYMENT_TOKEN_NOT_FOUND');
            }
        } catch (Exception $e) {
            DB::rollback();
            return $this->error('ERROR');
        }
     }
     
     public function makeMyFatoorahInvoice($PRODUCT_LIST, $orderArr) {
        $post_string = '{
            "InvoiceValue":'.$orderArr['order_value'].',
            "CustomerName": "Alex",

            "CustomerAddress": "'. $orderArr['delivery_address'].'",

            "CustomerReference": "'.time().'",
            "DisplayCurrencyIsoAlpha": "KWD",
            "CountryCodeId": "+965",
            "CustomerMobile": "'.$orderArr['contact_person_no'].'",
            "CustomerEmail": "jainil@bildarb.com",

            "DisplayCurrencyId": 3,
            "SendInvoiceOption": 1,
            "InvoiceItemsCreate":'.$PRODUCT_LIST.',
            "CallBackUrl": "'. $this->callbackUrl.'",
            "Language": 2,
            "ExpireDate": "2022-12-31T13:30:17.812Z",
            "ApiCustomFileds": "null",
            "ErrorUrl": "'.$this->errorUrl.'"
        }';

        $soap_do = curl_init();
        curl_setopt($soap_do, CURLOPT_URL, $this->myFatoorahUrl."ApiInvoices/CreateInvoiceIso");
        curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($soap_do, CURLOPT_TIMEOUT, 10);
        curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($soap_do, CURLOPT_POST, true);
        curl_setopt($soap_do, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($soap_do, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8','Content-Length: ' . strlen($post_string),  'Accept: application/json','Authorization: Bearer '.$this->myFatoorahToken));
        $reponse = curl_exec($soap_do);
        $err = curl_error($soap_do);
        $json = json_decode($reponse,true);
        $result = [];
        if($json['Id'] > 0) {
            $result['payment_url'] = $json['RedirectUrl'];
            $result['transaction_id'] = $json['Id'];
        } else {
            $result['message'] = $json['Message'];
        }
        
        //save data in strip transactions table
        $stArr['order_id'] = isset($orderArr['order_id']) ? $orderArr['order_id'] : 0;
        $stArr['type'] = 1;
        $stArr['user_id'] = isset($orderArr['user_id']) ? $orderArr['user_id'] : 0;
        $stArr['guest_user_id'] = isset($orderArr['guest_user_id']) ? $orderArr['guest_user_id'] : 0;
        $stArr['m_order_id'] = $result['transaction_id'];
        $stArr['response'] = $reponse;
        PaymentTransactions::create($stArr);
        
        curl_close($soap_do);
        return $result;
     }
     
     
}