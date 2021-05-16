<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chef;
use App\Models\Kitchens;
use App\Models\CuisineTypes;
use App\Models\Categories;
use App\Models\KitchenImages;
use App\Models\KitchenTimings;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Illuminate\Http\Request;
use App\Http\Helpers\CommonTrait;
use App\Http\Helpers\ResponseTrait;
use App\Http\Helpers\UploadImageTrait;
use App\Http\Helpers\UploadImageOnBucket;
use Illuminate\Routing\UrlGenerator;
use JsValidator;
use Validator;
use Illuminate\Support\Facades\Hash;
use Auth;
use Illuminate\Filesystem\Filesystem;
use File;
use Storage;

class ChefController extends Controller
{
    use CommonTrait, ResponseTrait, UploadImageTrait, UploadImageOnBucket;

    protected $addValidationRules = [
                                        'name'  => 'required',
                                        'email'  => 'required|unique:chefs,email',
                                        'profile_pic' =>  'mimes:jpeg,jpg,png',
                                    ];

    protected $editKitchenValidationRules = [
                                        'area'  => 'required',
                                        'address'  => 'required',
                                        'lane'  => 'required',
                                        'landmark'  => 'required',
                                        'delivery_type'  => 'required',
                                        'longitude'  => 'required',
                                        'latitude'  => 'required',
                                        'per_person_cost'  => 'required|numeric',
                                        'cuisine_types'  => 'required',
                                        'categories'  => 'required',
                                        'company_discount'  => 'required|numeric',
                                        'company_commission'  => 'required|numeric',
                                        'min_order_home_delivery'  => 'nullable|numeric',
                                        'delivery_fee'  => 'required|numeric',
                                    ];

    protected $kitchenImageValidationRules = [
                                        'kitchen_image' =>  'required',
                                        'kitchen_image.*' =>  'mimes:jpeg,jpg,png',
                                    ];


    public function __construct(UrlGenerator $url) {
        $this->url = $url;
        $this->pageMeta['pageName'] = "Chef";
        $this->pageMeta['pageDes'] = "Manage Chefs here!!";
        $this->pageMeta['breadCrumbs'] = array("Home" => $this->url->to('/admin/'), "Chef" => "");
    }
    
    
    /**
     * log activity listing page
     * @return type
     */
    public function index() {
         return view('admin.chef.index', ['pageMeta' => $this->pageMeta]);
    }
    
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function ajaxGetChefList(Request $request) {
        $chef = Chef::select(['chefs.*', DB::raw('DATE_FORMAT(chefs.created_at, "%m/%d/%Y %h:%i") as created_date')]);

        // Using the Engine Factory
        return Datatables::of($chef)
            ->addColumn('profile_pic', function ($chef) { $url=config('aws.aws_s3_url')."/uploads/chef/profile-pic/".$chef->id."/thumbnails/50x50/".$chef->profile_pic; 
                return '<img src='.$url.' border="0" width="40" class="img-rounded" align="center" />'; })
            ->rawColumns(['profile_pic'])
            ->make(true);
    }

    /**
     * Chef Add Edit page
     * @return type
     */
    public function editChef($id = 0) {
        $this->pageMeta['breadCrumbs'] = array("Home" => $this->url->to('/admin/'), "Chef List" => $this->url->to("/admin/chef/listing"), 'Add / Edit Chef' => '');
        $chefArr = [];
        if($id > 0) {
            $chefObj = Chef::find($id);
            if(!isset($chefObj->id)) {
                return redirect('/admin/chef/listing');
            }
            $chefArr = $chefObj->toArray();

            $editValidationRules = [
                                        'name'  => 'required',
                                        'email'  => 'required|unique:chefs,email,'.$id,
                                        'profile_pic' =>  'mimes:jpeg,jpg,png',
                                    ];
            $validator = JsValidator::make($editValidationRules,[],[],'#addEditChefForm');
        }else{
            $validator = JsValidator::make($this->addValidationRules,[],[],'#addEditChefForm');
        }
        return view('admin.chef.edit', ['pageMeta' => $this->pageMeta, 'chefArr' => $chefArr, 'adminRolesArr' => config('dbFields.ADMIN_ROLES'), 'validator' => $validator]);
    }
    
    
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function saveChef(Request $request) {    
        if(isset($request->id) && $request->id > 0) {
            $editValidationRules = [
                                        'name'  => 'required',
                                        'email'  => 'required|unique:chefs,email,'.$request->id,
                                        'profile_pic' =>  'mimes:jpeg,jpg,png',
                                    ];
            $validation = Validator::make($request->all(), $editValidationRules);
        } else {
            $validation = Validator::make($request->all(), $this->addValidationRules);
        }
        
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        } else {
            ini_set('upload_max_filesize', '8M');//allow 8 MB
            
            $input['name'] = $request->name;
            $input['email'] = $request->email;
            $input['role'] = $request->role;
            
            if(isset($request->id) && $request->id > 0) {
                if (!empty($request->file('profile_pic'))) {
                    $image = $request->file('profile_pic');
                    $imageData = [
                        'id' => $request->id,
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
                Chef::whereId($request->id)->update($input);
                $id = $request->id;                
                $actionName = 'Edit/Chef';

                $notification = array(
                    'message' => 'Chef updated successfully', 
                    'alert-type' => 'success'
                );

            } else {
                $password = str_random(8);
                $input['password'] = bcrypt($password);
                
                $chef = Chef::create($input);
                $id = $chef->id;
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
                    Chef::whereId($id)->update($input);
                }

                /* Email */
                $subject = "Chef Account is created";
                $content = "Hello ".$request->name."<br/> Your account is created with us. <br/> Email: ".$request->email."<br/> Password: ".$password."<br/> Thanks";
                $this->sendEmailNotificationCustom($id, $password, $subject, $content, 'chef');

                $actionName = 'Add/Chef';

                $notification = array(
                    'message' => 'Chef added successfully', 
                    'alert-type' => 'success'
                );
            }
            
            /**Log activity**/
            $this->logactivity($id, $actionName, $request->ip());
            return redirect('/admin/chef/show_edit_form/'.$id)->with($notification);
        }
    }

    
    /**
     * Kitchen Edit page
     * @return type
     */
    public function editKitchen($id = 0) {
        $this->pageMeta['breadCrumbs'] = array("Home" => $this->url->to('/admin/'), "Chef List" => $this->url->to("/admin/chef/listing"), 'Edit Kitchen' => '');
        $chefArr = [];
        if($id > 0) {
            $kitchenObj = Kitchens::Where('chef_id', $id)->first();
            if(!empty($kitchenObj)){
                $kitchenArr = $kitchenObj->toArray();
                if(count($kitchenArr) > 0){
                    $kitchenArr['cuisine_types'] = explode(',', $kitchenArr['cuisine_types']);
                    $kitchenArr['categories'] = explode(',', $kitchenArr['categories']);
                    $cities = DB::table('cities')->where('id', $kitchenArr['area_id'])->first();
                    $kitchenArr['city'] = $cities->id;
                    $kitchenArr['state'] = $cities->state_id;
                    $state = DB::table('states')->where('id', $cities->state_id)->first();
                    $kitchenArr['country'] = $state->country_id;
                }
            }
            else{
                $kitchenArr['chef_id'] = $id;
                $kitchenArr['cuisine_types'] = array();
                $kitchenArr['categories'] = array();
            }
// echo "<pre>";print_r($kitchenArr);exit;
            $editvalidator = JsValidator::make($this->editKitchenValidationRules,[],[],'#editKitchenForm');

            $cuisine_types = CuisineTypes::all();
            $categories = Categories::all();
            $countries = DB::table("countries")->pluck("name","id");
        }

        return view('admin.chef.kitchen_edit', ['pageMeta' => $this->pageMeta, 'kitchenArr' => $kitchenArr, 'adminRolesArr' => config('dbFields.ADMIN_ROLES'), 'editvalidator' => $editvalidator, 'cuisine_types' => $cuisine_types, 'categories' => $categories, 'countries' => $countries]);
    }
    
    
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function saveKitchen(Request $request) {    
        if(isset($request->chef_id) && $request->chef_id > 0) {
            $validation = Validator::make($request->all(), $this->editKitchenValidationRules);
        }
        
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        } else {
            // echo "<pre>";print_r($request->all());exit;
            $input['chef_id'] = $request->chef_id;
            $input['area_id'] = $request->area;
            $input['address'] = $request->address;
            $input['lane'] = $request->lane;
            $input['landmark'] = $request->landmark;
            $input['delivery_type'] = $request->delivery_type;
            $input['pre_order'] = $request->preorder;
            $input['delivery_radius'] = isset($request->delivery_radius) ? $request->delivery_radius : 0;
            $input['longitude'] = $request->longitude;
            $input['latitude'] = $request->latitude;
            $input['per_person_cost'] = $request->per_person_cost;
            $input['prep_time'] = $request->prep_time;
            $input['min_order_home_delivery'] = $request->min_order_home_delivery;
            $input['cuisine_types'] = implode(',', $request->cuisine_types);
            $input['categories'] = implode(',', $request->categories);
            $input['pure_veg'] = $request->pure_veg;
            $input['company_discount'] = $request->company_discount;
            $input['company_commission'] = $request->company_commission;
            $input['delivery_fee'] = $request->delivery_fee;
            //$input['open'] = 1;
            $input['status'] = 'Active';
            if(Auth::guard('admin')->check()) {
                $this->logged_in_user_id = Auth::guard('admin')->user()->id;
            }
            $input['created_by'] = $this->logged_in_user_id;

            if(isset($request->id) && $request->id > 0) {
                Kitchens::whereId($request->id)->update($input);
                $id = $request->id;
                $actionName = 'Edit/Kitchen';
                $notification = array(
                    'message' => 'Kitchen updated successfully', 
                    'alert-type' => 'success'
                );
            } else if(isset($request->chef_id) && $request->chef_id > 0) {
                $kitchen = Kitchens::create($input);
                $id = $kitchen->id;
                $actionName = 'Add/Kitchen';
                $notification = array(
                    'message' => 'Kitchen added successfully', 
                    'alert-type' => 'success'
                );

                $weekArr = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
                foreach($weekArr as $val){
                    $input['day'] = $val;
                    $input['from_time1'] = 0;
                    $input['to_time1'] = 0;
                    $input['from_time2'] = 0;
                    $input['to_time2'] = 0;
                    $input['kitchen_id'] = $id;
                    $input['created_by'] = $this->logged_in_user_id;
                    $menu = KitchenTimings::create($input);
                }
            }
            
            /**Log activity**/
            $this->logactivity($id, $actionName, $request->ip());
            return redirect('/admin/chef/kitchen_edit_form/'.$request->chef_id)->with($notification);
        }
    }

    /**
     * Chef Add Edit page
     * @return type
     */
    public function editKitchenImage($id = 0) {
        $this->pageMeta['breadCrumbs'] = array("Home" => $this->url->to('/admin/'), "Chef List" => $this->url->to("/admin/chef/listing"), 'Add / Edit Kitchen Image' => '');
        $kitchenImageArr = [];
        if($id > 0) {
            $kitchen = Kitchens::find($id);
            $kitchenImageObj = KitchenImages::Where('chef_id', $kitchen->chef_id)->get();
            if(count($kitchenImageObj) > 0){
                $kitchenImageArr = $kitchenImageObj->toArray();
                // echo "<pre>";print_r($kitchenImageArr);exit;
                if(count($kitchenImageArr) > 0) {
                    $kitchenImageArr['chef_id'] = $kitchenImageArr[0]['chef_id'];
                }
            }else{
                $kitchenImageArr['kitchen_id'] = $id;
                $kitchenImageArr['chef_id'] = $kitchen->chef_id;
            }
            $validator = JsValidator::make($this->kitchenImageValidationRules,[],[],'#editKitchenImageForm');
        }

        return view('admin.chef.kitchen_image_edit', ['pageMeta' => $this->pageMeta, 'kitchenImageArr' => $kitchenImageArr, 'adminRolesArr' => config('dbFields.ADMIN_ROLES'), 'validator' => $validator]);
    }
    
    
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function saveKitchenImage(Request $request) {  
        $validation = Validator::make($request->all(), $this->kitchenImageValidationRules);
        
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        } else {
            // echo "<pre>";print_r($request->all());exit;
            if(Auth::guard('admin')->check()) {
                $this->logged_in_user_id = Auth::guard('admin')->user()->id;
            }
            $input['created_by'] = $this->logged_in_user_id;
            $input['chef_id'] = $request->chef_id;
            $input['kitchen_id'] = $request->kitchen_id;
            
            if (!empty($request->file('kitchen_image'))) {
                foreach($request->kitchen_image as $key=>$val) {
                    $kitchenImage = KitchenImages::create($input);
                    $id = $kitchenImage->id;
                    if (!empty($request->file('kitchen_image')[$key])) {
                        $image = $request->file('kitchen_image')[$key];
                        $imageData = [
                            'id' => $id,
                            'image' => $image,
                            'folder_name' => 'chef/kitchens'
                        ];
                        $imageName = $this->uploadImage($imageData);
                        $this->imageUploadOnBucket($imageData['folder_name'], $imageData['image'], $imageName, $imageData['id']);
                        if ($imageName) {
                            $input['kitchen_image'] = $imageName;
                        } else {
                            $input['kitchen_image'] = '';
                        }
                        KitchenImages::whereId($id)->update($input);
                    }
                    $actionName = 'Add/KitchenImage';
                }
            }
            
            $notification = array(
                'message' => 'Kitchen Images added successfully', 
                'alert-type' => 'success'
            );
            
            /**Log activity**/
            $this->logactivity($id, $actionName, $request->ip());
            return redirect('/admin/chef/listing')->with($notification);
        }
    }

    /**
     * delete region
     * @param type $id
     * @return type
     */
    public function deleteImage(Request $request, $id) {   
        $KitchenImages = KitchenImages::find($id);
        $KitchenImages->delete();
        if(File::exists(public_path('uploads/chef/kitchens/'.$id))) {
            File::deleteDirectory(public_path('uploads/chef/kitchens/'.$id));
        }

        $notification = array(
            'message' => 'Kitchen Image deleted successfully', 
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    /**
     * change chef status
     * @param type $id
     * @return type
     */
    public function changeChefStatus($id) {
        $chef = Chef::find($id);
        if($chef) {
            if ($chef->status == 'Active') {
                $chef->status = 'Inactive';
            } else {
                $chef->status = 'Active';
            }
            $chef->save();
            $msg = "CHEF_STATUS_CHANGED";
        } else {
            $msg = "NO_RECORD_FOUND";
        }
        
        return $this->success(null, $msg);
    }
}
