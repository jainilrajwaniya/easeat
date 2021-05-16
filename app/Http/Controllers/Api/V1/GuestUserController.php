<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Models\GuestUsers; 
use Validator;
use App\Http\Helpers\CommonTrait;
use App\Http\Helpers\ResponseTrait;
use Exception;
use DB;

class GuestUserController extends Controller 
{
    use CommonTrait, ResponseTrait;

    public function __construct() {}
    /** 
    * login api 
    *
    * 
    * @param Request $request
    * @return type
    */
    public function guestUserLogin(Request $request) {

        $validation = Validator::make($request->all(), config('frontValidations.GUESTLOGIN'));
        
        if ($validation->fails()) {
            return $this->validationError($validation);
        }
        DB::beginTransaction();
        try {
            $guestUser = GuestUsers::where(['device_token' => $request->device_token])->first();
            if(!$guestUser) {
                $input = $request->all();
                $guestUser = GuestUsers::create($input);
                DB::commit();
            } 
                
            $result['token'] =  $guestUser->device_token; 
            $user_data['id'] =  $guestUser->id;
            $user_data['name'] =  ($guestUser->name != null ? $guestUser->name : ''); 
            $user_data['phone_number'] =  ($guestUser->phone_number != null ? $guestUser->phone_number : '');
            $user_data['email'] =  ($guestUser->email != null ? $guestUser->email : '');
            $result['user_data'] =  $user_data; 
            return $this->success($result, 'GUESTUSER_LOGGEDIN'); 
            
        } catch (Exception $ex) {
             DB::rollback();
            return $this->error('ERROR');
        }
         
    }
    
}