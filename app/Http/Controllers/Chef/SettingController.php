<?php

namespace App\Http\Controllers\Chef;

use App\Http\Controllers\Controller;
use App\Models\Chef;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Illuminate\Http\Request;
use App\Http\Helpers\CommonTrait;
use App\Http\Helpers\ResponseTrait;
use Illuminate\Routing\UrlGenerator;
use App\Http\Helpers\UploadImageTrait;
use App\Http\Helpers\UploadImageOnBucket;
use JsValidator;
use Validator;
use Auth;
use Hash;
use Illuminate\Filesystem\Filesystem;
use File;
use Storage;

class SettingController extends Controller
{
    use CommonTrait, ResponseTrait, UploadImageTrait, UploadImageOnBucket;
            
    public function __construct(UrlGenerator $url) {
        $this->url = $url;
        $this->pageMeta['pageName'] = "Settings";
        $this->pageMeta['pageDes'] = "Manage Settings here!!";
        $this->pageMeta['breadCrumbs'] = array("Home" => $this->url->to('/chef/'), "Settings" => "");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function passwordIndex()
    {
        $rules = array(
            'oldpassword' => 'required',
            'password'   => 'required|min:6',
            'password_confirmation' => 'required|same:password',
        );
        $validator = JsValidator::make($rules,[],[],'#changePasswordForm');

        return view('chef.settings.change_password', ['pageMeta' => $this->pageMeta, 'validator' => $validator]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function passwordStore(Request $request)
    {
        $postdata = $request->all();
        if(Auth::guard('chef')->check()) {
            $email = Auth::guard('chef')->user()->email;
            $id = Auth::guard('chef')->user()->id;
        }
        $chefData = Chef::where('email', '=', $email)->get();
        // echo "<pre>";print_r($chefData);exit;
        $password = isset($postdata['password']) ? $postdata['password'] : '';
        $oldpassword = isset($postdata['oldpassword']) ? $postdata['oldpassword'] : '';

        $rules = array(
            'oldpassword' => 'required',
            'password'   => 'required|min:6',
            'password_confirmation' => 'required|same:password',
        );
        $validator = Validator::make($request->all(), $rules);

        // process the login
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            // store
            if(!Hash::check($oldpassword, $chefData[0]->password)){
                $notification = array(
                    'message' => 'Old password not match', 
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);
            }else{
                $password = bcrypt($password);
                $change = DB::table('chefs')
                    ->where('email', $email)
                    ->update(['password' => $password]);

                $notification = array(
                    'message' => 'Password updated successfully', 
                    'alert-type' => 'success'
                );
                $actionName = 'changepassword';

                /**Log activity**/
                $this->logactivity($id, $actionName, $request->ip());
                return redirect('/chef/settings/change_password_form')->with($notification);
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editProfile()
    {
        $rules = array(
            'name' => 'required',
            'profile_pic' =>  'mimes:jpeg,jpg,png',
        );
        $validator = JsValidator::make($rules,[],[],'#editProfileForm');

        return view('chef.settings.edit_profile', ['pageMeta' => $this->pageMeta, 'validator' => $validator]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveProfile(Request $request)
    {
        if(Auth::guard('chef')->check()) {
            $email = Auth::guard('chef')->user()->email;
            $id = Auth::guard('chef')->user()->id;
        }
    
        $rules = array(
            'name' => 'required',
            'profile_pic' =>  'mimes:jpeg,jpg,png',
        );
        $validator = Validator::make($request->all(), $rules);

        // process the login
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $input['name'] = $request->name;
            if (!empty($request->file('profile_pic'))) {
                $image = $request->file('profile_pic');
                $imageData = [
                    'id' => $id,
                    'image' => $image,
                    'folder_name' => 'chef/profile-pic'
                ];
                $imageName = $this->uploadImage($imageData);
                $this->imageUploadOnBucket($imageData['folder_name'], $imageData['image'], $imageName, $imageData['id']);
                if ($imageName) {
                    $input['profile_pic'] = $imageName;
                } else {
                    $input['profile_pic'] = '';
                }
            }
            Chef::whereId($id)->update($input);      
            $actionName = 'EditProfile';

            $notification = array(
                'message' => 'Profile updated successfully', 
                'alert-type' => 'success'
            );
        }

        /**Log activity**/
        $this->logactivity($id, $actionName, $request->ip());
        return redirect('/chef/settings/profile_form')->with($notification);
    }
    
    /**
     *  markKitchenOpenClose
     * @param Request $request
     * @return type
    */
    public function markKitchenOpenClose(Request $request) {
        $result = false;
        $open = 0;
        $msg = "CHEF_MARKED_AS_OFFLINE";
        if($request->status) {
            $open = 1;
            $msg = "CHEF_MARKED_AS_ONLINE";
        }
        $result = $this->updateKitchenOpenStatus($request->id, $open);
        if($result == true) {
            return $this->success(null, $msg);
        } else {
            return $this->error('ERROR');
        }
    }
}
