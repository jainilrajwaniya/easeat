<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Auth;
use Validator;
use App\Http\Helpers\ResponseTrait;
use Exception;
use App\Models\User;
use App\Models\GuestUsers;
use App\Models\Otps;
use App\Models\OtpHistory;
use DB;

class PhoneNumberController extends Controller 
{
    use ResponseTrait;
    
    public function __construct() {
        $this->currentUser = Auth::guard('api')->user();
    }

    /**
     * Verify phone number
     * @param Request $request
     * @return type
     */
    public function verifyPhoneNumber(Request $request) {
        $validationRules = ['phone_number' => 'required|numeric'];
        
        $validation = Validator::make($request->all(), $validationRules);
        if ($validation->fails()) {
            return $this->validationError($validation);
        }
        
        //get user id or guest user id
        $user_id = $guest_user_id = null;
        if(!empty($this->currentUser->id)) {
            $user_id = $this->currentUser->id;
            $usrObj = $this->getUserByPhoneNumber('USER', $request->phone_number);
            if($usrObj) {
                if($usrObj->id == $user_id)
                    return $this->error('SAME_PHONE_NUMBER_ENTERED');
                
                if($usrObj->id != $user_id)
                    return $this->error('PHONE_NUMBER_ALREADY_TAKEN');
            }
            
        } else {
            $guestUserObj = $this->getGuestUserDetails();
            if(!empty($guestUserObj->id)) {
                $guest_user_id = $guestUserObj->id;
                $gstGstObj = $this->getUserByPhoneNumber('GUEST_USER', $request->phone_number);
                if($gstGstObj) {
                    if($gstGstObj->id == $guest_user_id)
                        return $this->error('SAME_PHONE_NUMBER_ENTERED');

                    if($gstGstObj->id != $guest_user_id)
                        return $this->error('PHONE_NUMBER_ALREADY_TAKEN');
                }
            }
        }
        
        //return if both user id and guest user id not found
        if($guest_user_id == NULL && $user_id == NULL) {
            return $this->error('USER_OR_GUEST_USER_NOT_FOUND');
        }
        
        $otp = rand(1111, 9999);
        DB::beginTransaction();
        try {
            //delete all previous otps of this number
            Otps::where(['mobile' => $request->phone_number])->delete();
            
            //add new otp into table
            $data['mobile'] = $request->phone_number;
            $data['otp'] = $otp;
            Otps::create($data);

            $msg = 'Your OTP for adding address is : '.$otp;
            DB::commit();
            //send sms
            //$this->sendSMS($request->phone_number, $msg);
            return $this->success(array('otp' => $otp, 'phone_number' => $request->phone_number), 'OTP_SENT');
        } catch (Exception $ex) {
            DB::rollback();
            return $this->error('ERROR');
        }
    }
    
    /**
     * Add phone number on correct otp
     * @param Request $request
     * @return type
     */
    public function addPhoneNumber(Request $request) {
        $validationRules = ['phone_number' => 'required|numeric', 'otp' => 'required|numeric'];
        
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
        
        if(!$this->verifyOTP($request->phone_number, $request->otp)) {
            return $this->error('OTP_MISMATCH');
        }
        
        try {
            if($user_id) {
                $user = User::where(['id' => $user_id])->first();
            } else {
                $user = GuestUsers::where(['id' => $guest_user_id])->first();
            }
            $user->phone_number = $request->phone_number;
            $user->save();
            return $this->success(null, 'PHONE_NUMBER_ADDED');
        } catch (Exception $ex) {
            return $this->error('ERROR');
        }
    }
}