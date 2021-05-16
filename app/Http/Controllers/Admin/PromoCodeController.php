<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoCodes;
use App\Models\Chef;
use App\Models\PromoCodeKitchenAssoc;
use App\Models\Kitchens;
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

class PromoCodeController extends Controller
{
    use CommonTrait, ResponseTrait, UploadImageTrait, UploadImageOnBucket;

    protected $addValidationRules = [
                                        'promo_code'  => 'required|unique:promo_codes,promo_code',
                                        'discount_percentage'  => 'required|numeric',
                                        'image' =>  'required | mimes:jpeg,jpg,png',
                                        'no_of_usage'  => 'required|numeric',
                                        'min_order_value'  => 'required|numeric',
                                        'max_dis_amt'  => 'required|numeric',
                                        'limitation'  => 'required',
                                        'publish_at'  => 'required|after:today',
                                        'expire_at'  => 'required|after:today',
                                    ];

    protected $assignValidationRules = [
                                    'promo_code_id'  => 'required',
                                    'chef_id'  => 'required'
                                ];

    protected $message = [
                        'promo_code_id.required' => 'promo code field is required.',
                        'chef_id.required'  => 'Please select atleast one chef.'
                    ];
                                    
    public function __construct(UrlGenerator $url) {
        $this->url = $url;
        $this->pageMeta['pageName'] = "Promo Codes";
        $this->pageMeta['pageDes'] = "Manage Promo Codes here!!";
        $this->pageMeta['breadCrumbs'] = array("Home" => $this->url->to('/admin/'), "Promo Codes" => "");
    }
    
    
    /**
     * log activity listing page
     * @return type
     */
    public function index() {
         return view('admin.promocode.index', ['pageMeta' => $this->pageMeta]);
    }
    
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function ajaxGetPromocodeList(Request $request) {
        $now = date('Y-m-d');
        $promo = PromoCodes::select(['promo_codes.*']);

        // Using the Engine Factory
        return Datatables::of($promo)
            ->addColumn('image', function ($promo) { $url=config('aws.aws_s3_url')."/uploads/promo-code/".$promo->id."/thumbnails/50x50/".$promo->image; 
                return '<img src='.$url.' border="0" width="40" class="img-rounded" align="center" />'; })
            ->rawColumns(['image'])
            ->make(true);
    }

    /**
     * Promocode Add Edit page
     * @return type
     */
    public function editPromocode($id = 0) {
        $this->pageMeta['breadCrumbs'] = array("Home" => $this->url->to('/admin/'), "Promocode List" => $this->url->to("/admin/promocode/listing"), 'Add / Edit Promocode' => '');
        $promoArr = [];
        if($id > 0) {
            $promoObj = PromoCodes::find($id);
            if(!isset($promoObj->id)) {
                return redirect('/admin/promocode/listing');
            }
            $promoArr = $promoObj->toArray();
            $promoArr['publish_at'] = date('m/d/Y', strtotime($promoObj->publish_at));
            $promoArr['expire_at'] = date('m/d/Y', strtotime($promoObj->expire_at));

            $editValidationRules = [
                                        'promo_code'  => 'required|unique:promo_codes,promo_code,'.$id,
                                        'discount_percentage'  => 'required|numeric',
                                        'no_of_usage'  => 'required|numeric',
                                        'min_order_value'  => 'required|numeric',
                                        'max_dis_amt'  => 'required|numeric',
                                        'limitation'  => 'required',
                                        'publish_at'  => 'required|after:today',
                                        'expire_at'  => 'required|after:today',
                                        'image' =>  'mimes:jpeg,jpg,png'
                                    ];
            $validator = JsValidator::make($editValidationRules,[],[],'#addEditPromoForm');
        }else{
            $validator = JsValidator::make($this->addValidationRules,[],[],'#addEditPromoForm');
        }
        return view('admin.promocode.edit', ['pageMeta' => $this->pageMeta, 'promoArr' => $promoArr, 'adminRolesArr' => config('dbFields.ADMIN_ROLES'), 'validator' => $validator]);
    }
    
    
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function savePromocode(Request $request) {    
        if(isset($request->id) && $request->id > 0) {
            $editValidationRules = [
                                        'promo_code'  => 'required|unique:promo_codes,promo_code,'.$request->id,
                                        'discount_percentage'  => 'required|numeric',
                                        'no_of_usage'  => 'required|numeric',
                                        'min_order_value'  => 'required|numeric',
                                        'max_dis_amt'  => 'required|numeric',
                                        'limitation'  => 'required',
                                        'publish_at'  => 'required|after:today',
                                        'expire_at'  => 'required|after:today',
                                        'image' =>  'mimes:jpeg,jpg,png'
                                    ];
            $validation = Validator::make($request->all(), $editValidationRules);
        } else {
            $validation = Validator::make($request->all(), $this->addValidationRules);
        }
        
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        } else {
            $input['promo_code'] = $request->promo_code;
            $input['discount_percentage'] = $request->discount_percentage;
            $input['no_of_usage'] = $request->no_of_usage;
            $input['min_order_value'] = $request->min_order_value;
            $input['max_dis_amt'] = $request->max_dis_amt;
            $input['limitation'] = $request->limitation;
            $input['publish_at'] = date('Y-m-d', strtotime($request->publish_at));;
            $input['expire_at'] = date('Y-m-d', strtotime($request->expire_at));;
            $input['status'] = 'Active';
            if(Auth::guard('admin')->check()) {
                $this->logged_in_user_id = Auth::guard('admin')->user()->id;
            }
            
            
            if(isset($request->id) && $request->id > 0) {
                if (!empty($request->file('image'))) {
                    $image = $request->file('image');
                    $imageData = [
                        'id' => $request->id,
                        'image' => $image,
                        'folder_name' => 'promo-code'
                    ];
                    $imageName = $this->uploadImage($imageData);
                    $this->imageUploadOnBucket($imageData['folder_name'], $imageData['image'], $imageName, $imageData['id']);
                    if ($imageName) {
                        $input['image'] = $imageName;
                    } else {
                        $input['image'] = '';
                    }
                }
                $input['updated_by'] = $this->logged_in_user_id;

                Promocodes::whereId($request->id)->update($input);
                $id = $request->id;                
                $actionName = 'Edit/Promocode';

                $notification = array(
                    'message' => 'Promocode updated successfully', 
                    'alert-type' => 'success'
                );

            } else {
                $input['created_by'] = $this->logged_in_user_id;
                $promo = PromoCodes::create($input);
                $id = $promo->id;
                if (!empty($request->file('image'))) {
                    $image = $request->file('image');
                    $imageData = [
                        'id' => $id,
                        'image' => $image,
                        'folder_name' => 'promo-code'
                    ];
                    $imageName = $this->uploadImage($imageData);
                    $this->imageUploadOnBucket($imageData['folder_name'], $imageData['image'], $imageName, $imageData['id']);
                    if ($imageName) {
                        $input['image'] = $imageName;
                    } else {
                        $input['image'] = '';
                    }
                    PromoCodes::whereId($id)->update($input);
                }
                $actionName = 'Add/Promocode';
                $notification = array(
                    'message' => 'Promocode added successfully', 
                    'alert-type' => 'success'
                );
            }
            
            /**Log activity**/
            $this->logactivity($id, $actionName, $request->ip());
            return redirect('/admin/promocode/listing')->with($notification);
        }
    }

    /**
     * change PromoCodes status
     * @param type $id
     * @return type
     */
    public function changePromocodeStatus($id) {
        $chef = PromoCodes::find($id);
        if($chef) {
            if ($chef->status == 'Active') {
                $chef->status = 'Inactive';
            } else {
                $chef->status = 'Active';
            }
            $chef->save();
            $msg = "PROMOCODE_STATUS_CHANGED";
        } else {
            $msg = "NO_RECORD_FOUND";
        }
        
        return $this->success(null, $msg);
    }

    public function indexPromoKitchen() {
        $this->pageMeta['breadCrumbs'] = array("Home" => $this->url->to('/admin/'), "Promocode List" => $this->url->to("/admin/promocode/listing"), 'Assign Promocode' => '');

        $now = date('Y-m-d');
        $promoArr = PromoCodes::Where('expire_at', '>=', $now)->Where('status', 'Active')->pluck("promo_code","id");

        $validator = JsValidator::make($this->assignValidationRules,$this->message,[],'#addPromoForm');

        return view('admin.promocode.promo_kitchen_edit', ['pageMeta' => $this->pageMeta, 'promoArr' => $promoArr, 'adminRolesArr' => config('dbFields.ADMIN_ROLES'), 'validator' => $validator]);
    }

    public function savePromocodeKitchen(Request $request) {
        $validation = Validator::make($request->all(), $this->assignValidationRules, $this->message);
        
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        } else {
            PromoCodeKitchenAssoc::where('promo_code_id', $request->promo_code_id)->delete();
            foreach($request->chef_id as $key=>$val) {
                $kitchen = Kitchens::where('chef_id', $val)->first();
                $promo = PromoCodeKitchenAssoc::create(['promo_code_id' => $request->promo_code_id, 'chef_id' => $val, 'kitchen_id' => $kitchen->id] );
                
                $id = $promo->id;
                $actionName = 'AssignPromocode';
            }

            $notification = array(
                'message' => 'Promocode assign successfully', 
                'alert-type' => 'success'
            );
            
            /**Log activity**/
            $this->logactivity($id, $actionName, $request->ip());
            return redirect('/admin/promocode/kitchen/listing')->with($notification);
        }
    }

    
    public function getKitchenList(Request $request)
    {
        $kitchenChefArr = array();
        if($request->promo_code_id) {
            $kitchen_chef = DB::table('promo_code_kitchen_assocs')->where('promo_code_id', $request->promo_code_id)->get();
            if(count($kitchen_chef) > 0){
                foreach($kitchen_chef as $kitchen) {
                    $kitchenChefArr[] = $kitchen->chef_id;
                }  
            }
            // echo "<pre>";print_r($kitchenChefArr);exit;
        }

        $chef = Chef::Join('kitchens', 'chefs.id', 'kitchens.chef_id')->select('chefs.id', 'chefs.name')->get()->toArray();

        return response()->json(array(
            'kitchenChefArr' => $kitchenChefArr,
            'chef' => $chef
        ));
    }
}
