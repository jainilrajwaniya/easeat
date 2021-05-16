<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Validator;
use App\Http\Helpers\ResponseTrait;
use App\Http\Helpers\CommonTrait;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Filesystem\Filesystem;
use File;
use Storage;
use App\Http\Helpers\UploadImageTrait;
use App\Http\Helpers\UploadImageOnBucket;
use JsValidator;
use Yajra\DataTables\Facades\DataTables;

class SubadminController extends Controller
{
    protected $url;
    use CommonTrait, ResponseTrait, UploadImageTrait, UploadImageOnBucket;

    protected $addValidationRules = [
                                        'name'  => 'required',
                                        'email'  => 'required|unique:admins,email',
                                        'profile_pic' =>  'required | mimes:jpeg,jpg,png',
                                    ];

    public function __construct(UrlGenerator $url) {
        $this->url = $url;
        $this->pageMeta['pageName'] = "Subadmin Management";
        $this->pageMeta['pageDes'] = "Manage Subadmin here!!";
        $this->pageMeta['breadCrumbs'] = array("Home" => $this->url->to('/admin/'), "Subadmin Management" => "#");
    }
    
    /**
     * Sub admin listing page
     * @return type
     */
    public function index() {
         return view('admin.subadmin.index', ['pageMeta' => $this->pageMeta]);
    }
    
    /**
     * Sub admin ajax listing
     * @param \App\Http\Controllers\Admin\Request $request
     * @return type
     */
    public function ajaxGetAdminList() {
        $adminList = Admin::select(['id','name', 'email', 'profile_pic', 'role', 'created_at']);
        // return response()->json(['data' => $adminList->get()]);

        return Datatables::of($adminList)
            ->addColumn('profile_pic', function ($adminList) { $url=config('aws.aws_s3_url')."/uploads/sub-admin/profile-pic/".$adminList->id."/thumbnails/50x50/".$adminList->profile_pic; 
                return '<img src='.$url.' border="0" width="40" class="img-rounded" align="center" />'; })
            ->rawColumns(['profile_pic'])
            ->make(true);
    }
    
    
    
    /**
     * Sub admin Add Edit page
     * @return type
     */
    public function editSubAdmin($id = 0) {
        $this->pageMeta['breadCrumbs'] = array("Home" => $this->url->to('/admin/'), "Subadmin List" => $this->url->to("/admin/subadmin/listing"), 'Add / Edit Sub Admin' => '');
        $adminArr = [];
        if($id > 0) {
            $adminObj = Admin::find($id);
            if(!isset($adminObj->id)) {
                return redirect('/admin/subadmin/listing');
            }
            $adminArr = $adminObj->toArray();

            $editValidationRules = [
                                        'name'  => 'required',
                                        'email'  => 'required|unique:admins,email,'.$id,
                                        'profile_pic' =>  'mimes:jpeg,jpg,png',
                                    ];
            $validator = JsValidator::make($editValidationRules,[],[],'#addEditSubAdminForm');
        }else{
            $validator = JsValidator::make($this->addValidationRules,[],[],'#addEditSubAdminForm');
        }
        return view('admin.subadmin.edit', ['pageMeta' => $this->pageMeta, 'adminArr' => $adminArr, 'adminRolesArr' => config('dbFields.ADMIN_ROLES'), 'validator' => $validator]);
    }
    
    
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function saveSubAdmin(Request $request) {    
        if(isset($request->id) && $request->id > 0) {
            // $validation = Validator::make($request->all(), config('adminValidations.EDIT_SUBADMIN'));
            $editValidationRules = [
                                        'name'  => 'required',
                                        'email'  => 'required|unique:admins,email,'.$request->id,
                                        'profile_pic' =>  'mimes:jpeg,jpg,png',
                                    ];
            $validation = Validator::make($request->all(), $editValidationRules);
        } else {
            $validation = Validator::make($request->all(), $this->addValidationRules);
            // $validation = Validator::make($request->all(), config('adminValidations.ADD_SUBADMIN'));
        }
        
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        } else {
            if(isset($request->id) && $request->id > 0 && Admin::checkEmailAlreadyExists($request->email, $request->id)) {
                return redirect()->back()->withErrors(['The email '.$request->email.' has already been taken.'])->withInput();
            }
            $input['name'] = $request->name;
            $input['email'] = $request->email;
            $input['role'] = $request->role;
            if(isset($request->id) && $request->id > 0) {
                if (!empty($request->file('profile_pic'))) {
                    $image = $request->file('profile_pic');
                    $imageData = [
                        'id' => $request->id,
                        'image' => $image,
                        'folder_name' => 'sub-admin/profile-pic'
                    ];
                    $imageName = $this->uploadImage($imageData);
                    $this->imageUploadOnBucket($imageData['folder_name'], $imageData['image'], $imageName, $imageData['id']);
                    if ($imageName) {
                        $input['profile_pic'] = $imageName;
                    } else {
                        $input['profile_pic'] = '';
                    }
                }
                Admin::whereId($request->id)->update($input);
                $id = $request->id;
                $actionName = 'Edit/SubAdmin';

                $notification = array(
                    'message' => 'Sub Admin updated successfully', 
                    'alert-type' => 'success'
                );
            } else {
                $password = str_random(8);
                $input['password'] = bcrypt($password);

                $admin = Admin::create($input);
                $id = $admin->id;
                if (!empty($request->file('profile_pic'))) {
                    $image = $request->file('profile_pic');
                    $imageData = [
                        'id' => $id,
                        'image' => $image,
                        'folder_name' => 'sub-admin/profile-pic'
                    ];
                    $imageName = $this->uploadImage($imageData);
                    $this->imageUploadOnBucket($imageData['folder_name'], $imageData['image'], $imageName, $imageData['id']);
                    if ($imageName) {
                        $input['profile_pic'] = $imageName;
                    } else {
                        $input['profile_pic'] = '';
                    }
                    Admin::whereId($id)->update($input);
                }

                /* Email */
                $subject = "Sub Admin Account is created";
                $content = "Hello ".$request->name."<br/> Your account is created with us. <br/> Email: ".$request->email."<br/> Password: ".$password."<br/> Thanks";
                $this->sendEmailNotificationCustom($id, $password, $subject, $content, 'sub_admin');

                $actionName = 'Add/SubAdmin';

                $notification = array(
                    'message' => 'Sub Admin added successfully', 
                    'alert-type' => 'success'
                );
            }
            
            /**Log activity**/
            $this->logactivity($id, $actionName, $request->ip());
            return redirect('/admin/subadmin/listing')->with($notification);
        }
    }
}
