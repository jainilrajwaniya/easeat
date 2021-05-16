<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Models\Chef; 
use Validator;
use App\Http\Helpers\CommonTrait;
use App\Http\Helpers\ResponseTrait;
use Exception;
use DB;
use Auth;

class ChefLoginController extends Controller 
{
    use CommonTrait, ResponseTrait;

    public function __construct() {}
    
    /** 
    * login chef api
    * @param Request $request
    * @return type
    */
    public function login(Request $request) {
        $validation = Validator::make($request->all(), config('frontValidations.CHEF_LOGIN'));
        
        if ($validation->fails()) {
            return $this->validationError($validation);
        }
        DB::beginTransaction();
        try {
            $credentials = ['email' => $request->email,'password' => $request->password];
            if(Auth::guard('chef')->validate($credentials)) {
                $chef = Chef::where(['email' => $request->email])->first();
                $chef->device_token = $request->device_token;
                $chef->fcm_token = $request->fcm_token;
                $chef->api_token = $request->device_token.'_'.rand(111111,999999);
                $chef->device_type = $request->device_type;
                $chef->save();
                DB::commit();

                $result['token'] =  $chef->api_token; 
                $user_data['id'] =  $chef->id;
                $user_data['name'] =  ($chef->name != null ? $chef->name : ''); 
                $user_data['mobile'] =  ($chef->mobile != null ? $chef->mobile : '');
                $user_data['profile_pic'] =  ($chef->profile_pic != null ? config('aws.aws_s3_url').'/uploads/chef/profile-pic/'.$chef->id.'/thumbnails/50x50/'.$chef->profile_pic.'?'.time() : '');
                $user_data['status'] =  $chef->status;
                $result['chef_data'] =  $user_data; 
                return $this->success($result, 'USER_LOGGEDIN'); 
            } else { 
                return $this->error('INVALID_CREDENTIAL'); 
            }
        } catch (Exception $ex) {
             DB::rollback();
            return $this->error('ERROR');
        }
    }
    
}