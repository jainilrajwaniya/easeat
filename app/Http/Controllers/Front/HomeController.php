<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use App\Models\Orders;
use App\Models\Cart;
use App\Models\User;
use App\Models\PaymentTransactions;
use App\Models\Chef;

class HomeController extends Controller
{
    public function __construct() {
        $this->myFatoorahToken = null;
        $this->myFatoorahUrl = config('app.MY_FATOORAH_URL');
        $this->myFatoorahUsername = config('app.MY_FATOORAH_USERNAME');
        $this->myFatoorahPassword = config('app.MY_FATOORAH_PASSWORD');
    }
    
    public function index() {
         return view('front.home.index');
    }
    
    public function privacy() {
         return view('front.home.privacy');
    }
    
    public function paymentSuccess(Request $request) {

        $id = isset($request->Id) ? $request->Id : '';
        
        //create my fatoorah token
        $json = $this->createMyFatoorahToken();

        if(isset($json['access_token']) && !empty($json['access_token'])) {
            $access_token = $json['access_token'];
            $url = $this->myFatoorahUrl.'ApiInvoices/Transaction/'.$id;
            $soap_do1 = curl_init();
            curl_setopt($soap_do1, CURLOPT_URL,$url );
            curl_setopt($soap_do1, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($soap_do1, CURLOPT_TIMEOUT, 10);
            curl_setopt($soap_do1, CURLOPT_RETURNTRANSFER, true );
            curl_setopt($soap_do1, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($soap_do1, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($soap_do1, CURLOPT_POST, false );
            curl_setopt($soap_do1, CURLOPT_POST, 0);
            curl_setopt($soap_do1, CURLOPT_HTTPGET, 1);
            curl_setopt($soap_do1, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8', 'Accept: application/json','Authorization: Bearer '.$access_token));
            $response = curl_exec($soap_do1);
            curl_close($soap_do1);
            $getRecorById = json_decode($response, true);
            
            if (!$getRecorById['Error']) {
                //update payment transaction table
                $pt = PaymentTransactions::where('m_order_id', $getRecorById['OrderId'])->first();
                
                $order_id = $pt->order_id;
                $pt->response_after_payment = $response;
                $pt->transaction_status = 1;
                $pt->save();
                
                //update order table
                $order = Orders::where('id', $order_id)->first();
                $order->payment_status = "DONE";
                $order->status = "Placed";
                $order->save();
                
                //call common function to clear cart
                if($order->user_id != null) {
                    $cart = Cart::select('id')->where('user_id', $order->user_id)->first();
                } else {
                    $cart = Cart::select('id')->where('guest_user_id', $order->guest_user_id)->first();
                }
                $this->clearCartOncartId($cart->id);
                
                //send mail to customer
                $chef = Chef::select('name')->where('id', $order->chef_id)->first();
                $user = User::select('email', 'name')->where('id', $order->user_id)->first();
                if($user && isset($user->email) && $user->email != null) {
                    $mailData['chef_name'] = $chef->name;
                    $mailData['order_id'] = $order->id;
                    $mailData['delivery_address'] = $order->delivery_address;
                    $mailData['payment_method'] = $order->payment_method;
                    $mailData['updated_at'] = $order->updated_at;
                    $mailData['json'] = json_decode($order->order_json, 1);
                    $username = isset($user->email) && $user->email != null ? $user->email : "User";
                    $this->sendMail('emails.order_invoice', $mailData, $user->email, $username, "Order Placed : ".$order_id);
                }
                return view('front.my_fatoorah.success', ['pt'=> $pt]);
            } else {
                //update payment transaction table
                $pt = PaymentTransactions::where('m_order_id', $getRecorById['OrderId'])->first();
                $order_id = $pt->order_id;
                $pt->response_after_payment = $response;
                $pt->transaction_status = 0;
                $pt->save();
                
                //update order table
                $order = Orders::where('id', $order_id)->first();
                $order->payment_status = "FAILED";
                $order->save();
                view('front.my_fatoorah.failed', ['pt'=> $pt]);
            }
        } else {
                //update payment transaction table
                $pt = PaymentTransactions::where('m_order_id', $getRecorById['OrderId'])->first();
                $order_id = $pt->order_id;
                $pt->response_after_payment = $response;
                $pt->transaction_status = 0;
                $pt->save();
                
                //update order table
                $order = Orders::where('id', $order_id)->first();
                $order->payment_status = "FAILED";
                $order->save();
                return view('front.my_fatoorah.failed', ['pt'=> $pt]);
            }
    }



    public function paymentError() {
        return view('front.my_fatoorah.failed');
    }


    public function failed($id) {
        return view('front.my_fatoorah.failed');
   
    }
}
