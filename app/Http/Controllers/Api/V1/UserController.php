<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Models\User; 
use App\Models\GuestUsers; 
use Validator;
use App\Http\Helpers\CommonTrait;
use App\Http\Helpers\ResponseTrait;
use Exception;
use DB;
use Carbon\Carbon;
use Auth;
use App\Models\UserFavouriteKichens;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class UserController extends Controller 
{
    use CommonTrait, ResponseTrait, SendsPasswordResetEmails;

    public function __construct() {
        $this->currentUser = Auth::guard('api')->user();
    }
    /** 
    * login api 
    *
     * 
     * @param Request $request
     * @return type
     */
    public function login(Request $request) {

        $validation = Validator::make($request->all(), config('frontValidations.LOGIN'));
        
        if ($validation->fails()) {
            return $this->validationError($validation);
        }
        DB::beginTransaction();
        try {
            $user = User::where('email', $request->email)->first();
            if(!$user) {
                return $this->error('USER_EMAIL_NOT_FOUND');
            } else {
                //validate if email is registered from any social account
                if($user->signup_from != null) {
                    return $this->error('EMAIL_REGISTERED_FROM_SOCIAL_ACCOUNT');
                    exit;
                }
                if(Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')])) { 
                    $user = Auth::user();
                    /**update device token, if logged in from other device**/
                    $userObj = User::where(['email' => $request->email])->first();
                    $userObj->device_token =  $request->device_token;
                    $userObj->fcm_token =  $request->fcm_token;
                    $userObj->save();
                    DB::commit();
                    $result['token'] =  $user->createToken('email')->accessToken; 
                    $user_data['id'] =  $user->id; 
                    $user_data['email'] =  $user->email; 
                    $user_data['name'] =  $user->name; 
                    $user_data['phone_number'] =  $user->phone_number;
                    $result['user_data'] =  $user_data; 
                    return $this->success($result, 'USER_LOGGEDIN'); 
                } else { 
                    return $this->error('INVALID_CREDENTIAL'); 
                }
            }
        } catch (Exception $ex) {
             DB::rollback();
            return $this->error('ERROR');
        }
         
    }
    
    /** 
    * social login api 
    * 
    * @param Request $request
    * @return type
    */
    public function socialLogin(Request $request) {

        $validation = Validator::make($request->all(), config('frontValidations.SOCIAL_LOGIN'));
        if ($validation->fails()) {
            return $this->validationError($validation);
        }
        
        DB::beginTransaction();
        try {
            $user = User::where('email', $request->get('email'))->first();
            if ($user) {
                //validate if email is registered from normal account
//                if($user->signup_from == null) {
//                    return $this->error('EMAIL_REGISTERED_FROM_NORMAL_ACCOUNT');
//                    exit;
//                }
                $user_data['id'] =  $user->id; 
                $user_data['email'] =  $user->email; 
                $user_data['name'] =  $user->name; 
                $user_data['phone_number'] =  $user->phone_number;
                $result['user_data'] =  $user_data; 
                $type = 'USER_LOGGEDIN';
                /**update device token, if logged in from other device**/
                $updateArr['device_token'] =  $request->device_token;
                $updateArr['fcm_token'] =  $request->fcm_token;
                $user->update($updateArr);
                DB::commit();
            } else {
                $validation = Validator::make($request->all(), config('frontValidations.SOCIAL_REGISTER'));
                if ($validation->fails()) {
                    return $this->validationError($validation);
                }
                $rndPsswd = mt_rand(10000000, 99999999);
                $userData = $request->all();
                $userData['password'] = $rndPsswd;
                $userData['status'] = 'Active';
                $user = new User();
                $user = $user->create($userData);
                DB::commit();
                $result['user_data']['id'] = $user->id;
                $result['user_data']['email'] = $user->email;
                $result['user_data']['name'] = $user->name;
                $result['user_data']['phone_number'] = $user->phone_number;
                $type = 'USER_REGISTERED';
                //send registration mail to admin
                $tags = ['USER_NAME' => $user->name, 'USER_EMAIL' => $user->email, 'SIGNUP_FROM' => $user->signup_from, 'CREATED_AT' => Carbon::parse($user->created_at)->format('d-m-Y H:i:s'),];
                $this->sendEmailNotificationToEmail(config('mail.admin.address'), config('mail.admin.name'),'NOTIFY_ADMIN_USER_REGISTRAION', $tags);
                //send registration mail to user
                $this->sendEmailNotification('USER_REGISTRAION', [$user->id], $tags);
                //send registration notification to user
//                $dt = 'cPDVPXBdK4M:APA91bGrn3fNg6llteXzGSLvdzJGsny32NEcDytEBsWQLSeoCUi0ROh91Igf7UE0ghu2W5Wij2y-0KrdVln4doo7ZSUcfjFIL1hiMR6WA8dPu4FhPuI6ez6D00qMRSuzlXFoM5AOSVrk';
//                $this->sendFCM($dt,'test', 'hi how are you?');
            }
            
            $result['token'] =  $user->createToken('email')->accessToken;
            return $this->success($result, $type);
        } catch (Exception $ex) {
            DB::rollback();
            return $this->error('ERROR');
        }
    }
    
    /** 
    * Register api 
    * 
    * @return \Illuminate\Http\Response 
    */ 
   public function register(Request $request) 
   { 
        $validation = Validator::make($request->all(), config('frontValidations.REGISTER'));

        if ($validation->fails()) {
            return $this->validationError($validation);
        }

        $input = $request->all(); 
        $input['password'] = bcrypt($input['password']);
        $input['status'] = 'Active';
        DB::beginTransaction();
        try {
            $user = User::create($input);
            DB::commit();
            $result['token'] =  $user->createToken('email')-> accessToken;
            $result['user_data']['email'] = $user->email;
            $result['user_data']['name'] = $user->name;
            $result['user_data']['phone_number'] = $user->phone_number;
            //send registration mail to admin
            $tags = ['USER_NAME' => $user->name, 'USER_EMAIL' => $user->email, 'SIGNUP_FROM' => 'Normal Signup', 'CREATED_AT' => Carbon::parse($user->created_at)->format('d-m-Y H:i:s'),];
            $this->sendEmailNotificationToEmail(config('mail.admin.address'), config('mail.admin.name'),'NOTIFY_ADMIN_USER_REGISTRAION', $tags);
            //send registration mail to user
            $this->sendEmailNotification('USER_REGISTRAION', [$user->id], $tags);
            //send registration notification to user
//                $dt = 'cPDVPXBdK4M:APA91bGrn3fNg6llteXzGSLvdzJGsny32NEcDytEBsWQLSeoCUi0ROh91Igf7UE0ghu2W5Wij2y-0KrdVln4doo7ZSUcfjFIL1hiMR6WA8dPu4FhPuI6ez6D00qMRSuzlXFoM5AOSVrk';
//                $this->sendFCM($dt,'test', 'hi how are you?');
            return $this->success($result);
        } catch (Exception $ex) {
            DB::rollback();
            return $this->error('ERROR');
        }
       
   }
   
   /**
     * Mark / delete Kitchen As Favourite
     * @param Request $request
     */
    public function markKitchenAsFavourite(Request $request) {
        $validation = Validator::make($request->all(), ['fav' => 'required|in:0,1','kitchen_id' => 'required|integer|exists:kitchens,id']);
        if ($validation->fails()) {
            return $this->validationError($validation);
        }
        DB::beginTransaction();
        try {
            $ufkObj = UserFavouriteKichens::where(['user_id' => $this->currentUser->id, 'kitchen_id' => $request->kitchen_id]);
            switch($request->fav) {
                case 0:
                    $ufkObj->delete();
                    $msg = 'CHEF_REMOVED_FAVOURITE';
                break;
                case 1:
                    $ufkObj = $ufkObj->get()->toArray();
                    if(empty($ufkObj)) {
                        $input['user_id'] = $this->currentUser->id;
                        $input['kitchen_id'] = $request->kitchen_id;
                        UserFavouriteKichens::create($input);
                        $msg = 'CHEF_MARKED_FAVOURITE';
                    } else {
                        $msg = 'CHEF_ALREADY_MARKED_FAVOURITE';
                    }
                break;
            }
            DB::commit();
            return $this->success(null, $msg);
        } catch (Exception $ex) {
            DB::rollback();
            return $this->error('ERROR');
        }
    }
    
    /**
     * get user fav kitchens
     * @param Request $request
     * @return type
     */
    public function getFavouriteKitchens(Request $request) {
        try {
            $result = $list = [];
            if($request->list == 1){
               $userFavouriteKichens = UserFavouriteKichens::getFavKitchenList($this->currentUser->id);
               foreach($userFavouriteKichens as $ele){
                   $arrTemp = [];
                   $arrTemp['chef_id'] = $ele->chef_id;
                   $arrTemp['chef_name'] = $ele->chef_name;
                   $arrTemp['kitchen_id'] = $ele->kitchen_id;
                   $arrTemp['cuisine_types'] = $ele->cuisine_types;
                   $arrTemp['categories'] = $ele->categories;
                   $arrTemp['rating'] = $ele->rating;
                   $arrTemp['image'] = $ele->image;
                   $list[] = $arrTemp;
               }
                $result = array('fav_kitchen_list' => $userFavouriteKichens);
            } else {
                $userFavouriteKichens = UserFavouriteKichens::where('user_id', $this->currentUser->id)->get()->toArray();
                $ids = [];
                foreach($userFavouriteKichens as $ele) {
                    $ids[] = $ele['kitchen_id'];
                }
                $result = array('fav_kitchen_ids' => $ids);
            }            
            return $this->success($result);
        } catch (Exception $ex) {
            return $this->error('ERROR');
        }
    }
    
    /** 
    * details api 
    * 
    * @return \Illuminate\Http\Response 
    */ 
   public function detail() 
   { 
       //get user id or guest user id
        $user_id = $guest_user_id = null;
        $reponse = [];
        if(!empty($this->currentUser->id)) {
            $reponse['id'] = $this->currentUser->id; 
            $reponse['email'] = $this->currentUser->email; 
            $reponse['name'] = $this->currentUser->name;  
            $reponse['phone_number'] = $this->currentUser->phone_number;  
        } else {
            $guestUserObj = $this->getGuestUserDetails();
            if(!empty($guestUserObj->id)) {
                $reponse['id'] = $guestUserObj->id; 
                $reponse['email'] = $guestUserObj->email; 
                $reponse['name'] = $guestUserObj->name;  
                $reponse['phone_number'] = $guestUserObj->phone_number;  
            }
        }
       
       return $this->success(array("user_data" => $reponse));
   }
   
   /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
       $validation = Validator::make($request->all(), ['email' => 'required|email|exists:users,email']);
        if ($validation->fails()) {
            return $this->validationError($validation);
        }
        
        //validate if email is registered from any social account
        $user = User::where('email', $request->email)->select('signup_from')->first();
        if($user->signup_from != null) {
            return $this->error('EMAIL_REGISTERED_FROM_SOCIAL_ACCOUNT');
        }
        
        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        try {
            $response = $this->broker()->sendResetLink(
                $request->only('email')
            );
            return $this->success(null, 'PASSWORD_RESET_LINK_SENT');
        } catch(Exception $ex){
            return $this->error('ERROR');
        }
        
    }
    
    /**
     * Change user password
     * @param Request $request
     * @return type
     */
   public function changePassword(Request $request) 
   { 
       $validation = Validator::make($request->all(), config('frontValidations.CHANGE_PASSWORD'));
        if ($validation->fails()) {
            return $this->validationError($validation);
        }
        
        DB::beginTransaction();
        try {
            if(!(Auth::attempt(['email' => $this->currentUser->email, 'password' => $request->old_password]))) {
                return $this->error('OLD_PASSWORD_NOT_MATCH');
            }
            $user = User::where(['id' => $this->currentUser->id])->first();
            $updateArr['password'] = bcrypt($request->new_password);
            $user->update($updateArr);
            DB::commit();
            return $this->success(null, 'PASSWORD_CHANGED');
        } catch(Exception $ex){
            DB::rollback();
            return $this->error('ERROR');
        }
       return $this->success($reponse);
   }
   
    /**
    * edit User Profile
    * @param Request $request
    * @return type
    */
   public function editProfile(Request $request) 
   { 
       $validation = Validator::make($request->all(), ['email' => 'email']);
        if ($validation->fails()) {
            return $this->validationError($validation);
        }
        
        DB::beginTransaction();
        try {
            //get user id or guest user id
            $user_id = $guest_user_id = null;
            if(!empty($this->currentUser->id)) {
                $user_id = $this->currentUser->id;
                $usrObj = $this->getUserByEmail('USER', $request->email);
                if($usrObj) {
                    if($usrObj->id != $user_id) {
                        return $this->error('EMAIL_ALREADY_TAKEN');
                    }
                }
                $user = User::where(['id' => $this->currentUser->id])->first();
                $user->email = !empty($request->email) ? $request->email : "";
                $user->name = !empty($request->name) ? $request->name : "";
                $user->save();
            } else {
                $guestUserObj = $this->getGuestUserDetails();
                if(!empty($guestUserObj->id)) {
                    $guest_user_id = $guestUserObj->id;
                    $gstGstObj = $this->getUserByEmail('GUEST_USER', $request->email);
                    if($gstGstObj) {
                        if($gstGstObj->id != $guest_user_id) {
                            return $this->error('EMAIL_ALREADY_TAKEN');
                        }
                    }
                    $guser = GuestUsers::where(['id' => $guest_user_id])->first();
                    $guser->email = !empty($request->email) ? $request->email : "";
                    $guser->name = !empty($request->name) ? $request->name : "";
                    $guser->save();
                }
            }
            DB::commit();
            return $this->success(null, 'PROFILE_UPDATED');
        } catch(Exception $ex){
            DB::rollback();
            return $this->error('ERROR');
        }
       return $this->success($reponse);
   }
}