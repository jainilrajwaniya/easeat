<?php

namespace App\Http\Controllers\Chef;

use App\Http\Controllers\Controller;
use App\Models\KitchenTimings;
use App\Models\Kitchens;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Illuminate\Http\Request;
use App\Http\Helpers\CommonTrait;
use App\Http\Helpers\ResponseTrait;
use App\Http\Helpers\UploadImageTrait;
use Illuminate\Routing\UrlGenerator;
use JsValidator;
use Validator;
use Auth;

class KitchenTimingController extends Controller
{
    use CommonTrait, ResponseTrait, UploadImageTrait;

    protected $addValidationRules = [
                                        // 'day'  => 'required',
                                        // 'from_time1[]'  => 'required|array',
                                        'from_time1.*'  => 'required',
                                        'to_time1.*'  => 'required|greater_than:from_time1.*',
                                        'from_time2.*'  => 'required',
                                        'to_time2.*'  => 'required|greater_than:from_time2.*',
                                    ];

                                    
    public function __construct(UrlGenerator $url) {
        $this->url = $url;
        $this->pageMeta['pageName'] = "Kitchen Timing";
        $this->pageMeta['pageDes'] = "Manage Kitchen Timing here!!";
        $this->pageMeta['breadCrumbs'] = array("Home" => $this->url->to('/chef/'), "Kitchen Timing" => "");
    }
    
    
    /**
     * log activity listing page
     * @return type
     */
    public function index() {
         return view('chef.kitchen-timing.index', ['pageMeta' => $this->pageMeta]);
    }
    
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function ajaxGetKitchenTimingList(Request $request) {
        if(Auth::guard('chef')->check()) {
            $this->logged_in_user_id = Auth::guard('chef')->user()->id;
        }
        $kitchen = Kitchens::where('chef_id', $this->logged_in_user_id)->first();
        $promo = KitchenTimings::where('kitchen_id', $kitchen->id)->select(['kitchen_timings.*', DB::raw('DATE_FORMAT(kitchen_timings.created_at, "%m/%d/%Y") as created_date')]);

        // Using the Engine Factory
        return Datatables::of($promo)
            ->make(true);
    }

    /**
     * Kitchen Timing Add Edit page
     * @return type
     */
    public function editKitchenTiming($id = 0) {
        $this->pageMeta['breadCrumbs'] = array("Home" => $this->url->to('/chef/'), "Kitchen Timing List" => $this->url->to("/chef/kitchentiming/listing"), 'Add / Edit Kitchen Timing' => '');
        $timingsArr = [];
        if($id > 0) {
            if(Auth::guard('chef')->check()) {
                $this->logged_in_user_id = Auth::guard('chef')->user()->id;
            }
            $kitchen = Kitchens::where('chef_id', $this->logged_in_user_id)->first();
            
            $timingsObj = KitchenTimings::where('kitchen_id', $kitchen->id)->get();
            /*if(!isset($timingsObj->id)) {
                return redirect('/chef/kitchentiming/listing');
            }*/
            $timingsArr = $timingsObj->toArray();
            // echo "<pre>";print_r($timingsArr);exit;

            $editValidationRules = [
                                        'from_time1.*'  => 'required',
                                        'to_time1.*'  => 'required',
                                        'from_time2.*'  => 'required',
                                        'to_time2.*'  => 'required',
                                    ];
            $validator = JsValidator::make($editValidationRules,[],[],'#addEditPromoForm');
        }else{
            $validator = JsValidator::make($this->addValidationRules,[],[],'#addEditPromoForm');
        }

        return view('chef.kitchen-timing.edit', ['pageMeta' => $this->pageMeta, 'timingsArr' => $timingsArr, 'adminRolesArr' => config('dbFields.ADMIN_ROLES'), 'validator' => $validator]);
    }
    
    
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function saveKitchenTiming(Request $request) { 
        $validationRules = [
                'from_time1.*'  => 'required',
                'to_time1.*' => 'required|array',
                'from_time2.*'  => 'required',
                'to_time2.*'  => 'required|array',
            ];

            if ($request->get('to_time1')) {
                foreach($request->get('to_time1') as $key => $val)
                {
                    $validationRules['to_time1.'.$key] = 'required|greater_than:from_time1.'.$key; //example
                }
            }
            if ($request->get('to_time2')) {
                foreach($request->get('to_time2') as $key => $val)
                {
                    $validationRules['to_time2.'.$key] = 'required|greater_than:from_time2.'.$key; //example
                }
            }
        
        $validation = Validator::make($request->all(), $validationRules);
        
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        } else {
            // echo "<pre>";print_r($request->all());exit;
            
            if(Auth::guard('chef')->check()) {
                $this->logged_in_user_id = Auth::guard('chef')->user()->id;
            }
            $kitchen = Kitchens::where('chef_id', $this->logged_in_user_id)->first();
            
            if(isset($request->id[0]) && $request->id[0] > 0) {
                
                foreach($request->day as $key=>$val){
                    $input['day'] = $val;
                    $input['from_time1'] = $request->from_time1[$key];
                    $input['to_time1'] = $request->to_time1[$key];
                    $input['from_time2'] = $request->from_time2[$key];
                    $input['to_time2'] = $request->to_time2[$key];
                    $input['kitchen_id'] = $kitchen->id;
                    $input['updated_by'] = $this->logged_in_user_id;

                    KitchenTimings::updateOrCreate(['id' => $request->id[$key], 'day' => $val, 'kitchen_id' => $input['kitchen_id']] , ['from_time1' => $input['from_time1'], 'to_time1' => $input['to_time1'], 'from_time2' => $input['from_time2'], 'to_time2' => $input['to_time2'], 'updated_by' => $input['updated_by']]);

                    $id = $request->id[$key];                
                    $actionName = 'Edit/Kitchen Timing';
                    $this->logactivity($id, $actionName, $request->ip());
                }

                $notification = array(
                    'message' => 'Kitchen Timing updated successfully', 
                    'alert-type' => 'success'
                );

            } 
            /**Log activity**/
            
            return redirect('/chef/kitchentiming/listing')->with($notification);
        }
    }
}
