<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Auth;
use Validator;
use App\Http\Helpers\ResponseTrait;
use Exception;
use App\Models\UserAddress;
use DB;

class UseraddressController extends Controller 
{
    use ResponseTrait;
    
    public function __construct() {
        $this->currentUser = Auth::guard('api')->user();
    }

    
    /**
     * get user addresses
     * @param Request $request
     * @return type
     */
    public function getAddressList(Request $request) {
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

        if($user_id) {
            $where = ['user_id' => $user_id];
        } else {
            $where = ['guest_user_id' => $guest_user_id];
        }
        
        try {
            $userAddress = UserAddress::where($where)->get()->toArray();
            return $this->success(array('list' => $userAddress));
        } catch (Exception $ex) {
            return $this->error('ERROR');
        }
    }
    
    /**
     * get user addresses
     * @param Request $request
     * @return type
     */
    public function getAddress(Request $request) {
        
        $validationRules = ["address_id" => "required"];
        
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

        if($user_id) {
            $where = ['user_id' => $user_id, 'id' => $request->address_id];
        } else {
            $where = ['guest_user_id' => $guest_user_id, 'id' => $request->address_id];
        }
        
        try {
            $userAddress = UserAddress::where($where)->first();
            if(!$userAddress) {
                return $this->error("ADDRESS_DID_NOT_BELONGS_TO_THIS_USER");
            } else {
                return $this->success(['user_address' => $userAddress->toArray()], 'ADDRESS_FOUND');
            }
        } catch (Exception $ex) {
            return $this->error('ERROR');
        }
    }
    
    /**
     * save user/guest address
     * @param Request $request
     * @return type
     */
    public function saveAddress(Request $request) {
        $validationRules = 'frontValidations.SAVE_USER_ADDRESS';
        
        $validation = Validator::make($request->all(), config($validationRules));
        if ($validation->fails()) {
            return $this->validationError($validation);
        }
        
        //get user id or guest user id
        $user_id = $guest_user_id = null;
        if(!empty($this->currentUser->id)) {
            $user_id = $this->currentUser->id;
            //if(!$this->currentUser->phone_number)
                //return $this->error('PHONE_NUMBER_MISSING');
        } else {
            $guestUserObj = $this->getGuestUserDetails();
            if(!empty($guestUserObj->id)) {
                $guest_user_id = $guestUserObj->id;
                //if(!$guestUserObj->phone_number)
                    //return $this->error('PHONE_NUMBER_MISSING');
            }
        }
        
        //return if both user id and guest user id not found
        if($guest_user_id == NULL && $user_id == NULL) {
            return $this->error('USER_OR_GUEST_USER_NOT_FOUND');
        }
        
        DB::beginTransaction();
        try {
            $data = $request->all();
            if($user_id) {
                $data['user_id'] = $user_id;
            } else {
                $data['guest_user_id'] = $guest_user_id;
            }

            if(!empty($request->id)) {
                if($user_id) {
                    $where = ['user_id' => $user_id, 'id' => $request->id];
                } else {
                    $where = ['guest_user_id' => $guest_user_id, 'id' => $request->id];
                }
                $userAddress = UserAddress::where($where)->first();
                if(!$userAddress) {
                    return $this->error("ADDRESS_DID_NOT_BELONGS_TO_THIS_USER");
                } else {
                    $userAddress->name = $data['name'];
                    $userAddress->block = $data['block'];
                    $userAddress->street = isset($data['street']) ? $data['street'] : "";
                    $userAddress->latitude = isset($data['latitude']) ? $data['latitude'] : "0.00";
                    $userAddress->longitude = isset($data['longitude']) ? $data['longitude'] : "0.00";
                    $userAddress->gov_en = isset($data['gov_en']) ? $data['gov_en'] : "";
                    $userAddress->area_en = isset($data['area_en']) ? $data['area_en'] : "";
                    $userAddress->gov_ar = isset($data['gov_ar']) ? $data['gov_ar'] : "";
                    $userAddress->area_ar = isset($data['area_ar']) ? $data['area_ar'] : "";
                    $userAddress->avenue = isset($data['avenue']) ? $data['avenue'] : "";
                    $userAddress->building = isset($data['building']) ? $data['building'] : "";
                    $userAddress->floor = isset($data['floor']) ? $data['floor'] : "";
                    $userAddress->apartment_no = isset($data['apartment_no']) ? $data['apartment_no'] : "";
                    $userAddress->additional_directions = isset($data['additional_directions']) ?  $data['additional_directions'] : "";
                    
                    $userAddress->address = $this->arrangeAddress($userAddress->gov_en, $userAddress->area_en, $userAddress->block, 
                            $userAddress->street, $userAddress->avenue, $userAddress->building, $userAddress->floor, $userAddress->apartment_no, $userAddress->additional_directions);
                    $userAddress->save();
                    
                    $msg = 'USER_ADDRESS_EDITED_SUCCESSFULLY';
                }
            } else {
                UserAddress::create($data);
                $msg = 'USER_ADDRESS_SAVED_SUCCESSFULLY';
            }
            
            DB::commit();
            return $this->success('', $msg);
        } catch (Exception $ex) {
            DB::rollback();
            return $this->error('ERROR');
        }
    }
    
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function removeAddress(Request $request) {
         $validationRules = ['address_id' => 'required'];
        
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

        if($user_id) {
            $where = ['user_id' => $user_id, 'id' => $request->address_id];
        } else {
            $where = ['guest_user_id' => $guest_user_id, 'id' => $request->address_id];
        }
        DB::beginTransaction();
        try {
            $userAddress = UserAddress::where($where)->first();
            if(!$userAddress) {
                return $this->error("ADDRESS_DID_NOT_BELONGS_TO_THIS_USER");
            } else {
                $userAddress->delete();
                DB::commit();
                return $this->success('', 'ADDRESS_DELETED_SUCCESSFULLY');
            }
        } catch (Exception $ex) {
            DB::rollback();
            return $this->error('ERROR');
        }
    }
    
    /**
    * Get formatted address
    * @param Request $request
    * @return type
    */
    public function getFormatedAddress(Request $request) {
        $formatedAddress = $this->arrangeAddress(isset($request->gov) ? $request->gov : '', isset($request->area) ? $request->area : "", isset($request->block) ? $request->block : "", 
                            isset($request->street) ? $request->street : "", isset($request->avenue) ? $request->avenue : "", isset($request->building) ? $request->building : "", isset($request->floor) ? $request->floor : "", isset($request->apartment_no) ? $request->apartment_no : "", isset($request->additional_directions) ? $request->additional_directions : "");
        
        return $this->success(['formated_address' => $formatedAddress], 'ADDRESS_FOUND');
    }
}