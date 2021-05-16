<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Countries;
use App\Models\States;
use App\Models\Cities;
use App\Models\Areas;
use Illuminate\Http\Request;
use App\Http\Helpers\ResponseTrait;
use App\Http\Helpers\CommonTrait;
use Validator;
use Auth;
use Illuminate\Routing\UrlGenerator;
use DB;

class RegionController extends Controller
{
    use ResponseTrait, CommonTrait;
    public function __construct(UrlGenerator $url) {
        $this->url = $url;
        $this->pageMeta['pageName'] = "Region Management";
        $this->pageMeta['pageDes'] = "Manage Region here!!";
        $this->pageMeta['breadCrumbs'] = array("Home" => $this->url->to('/admin/'), "Region Management" => "");
        $this->logged_in_user_id = 0;
    }
    
    /**
     * get all region's data
     * @return type
     */
    public function index() {
        return view('admin.region.index', [
                'pageMeta' => $this->pageMeta,
                'countries' => Countries::all()
            ]);
    }
    
    /**
     * get countis
     * @return type
     */
    public function ajaxGetCounties() {
        return response()->json(['data' => States::getAllStates()]);
    }
    
    /**
     * get cities
     * @return type
     */
    public function ajaxGetCities() {
        return response()->json(['data' => Cities::getAllCities()]);
    }
    /**
     * get areas
     * @return type
     */
    public function ajaxGetAreas() {
        return response()->json(['data' => Areas::getAllAreas()]);
    }
    
    
    /**
     * 
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function ajaxUpdateCounty(Request $request, $id) {
        $validation = Validator::make($request->all(), config('adminValidations.EDIT_COUNTY'));
        
        if ($validation->fails()) {
            return $this->validationError($validation);
        } else {
            if(States::checkStateExists($id, $request->name) > 0) {
                return $this->Error('COUNTY_ALREADY_EXISTS');
            }
            if(Auth::guard('admin')->check()) {
                $this->logged_in_user_id = Auth::guard('admin')->user()->id;
            }
            $input['name'] = $request->name;
            $input['country_id'] = $request->country;
            if(isset($id) && $id > 0) {
                $input['updated_by'] = $this->logged_in_user_id;
                States::whereId($id)->update($input);
                $actionName = 'Edit/County';
                $msg = "COUNTY_UPDATED";
            } else {
                $input['created_by'] = $this->logged_in_user_id;
                $state = States::create($input);
                $id = $state->id;
                $actionName = 'Add/County';
                $msg = "COUNTY_CREATED";
            }            
            /**Log activity**/
            $this->logactivity($id, $actionName, $request->ip());
            return $this->success(null, $msg);
        }
        
    }
    
    /**
     * 
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function ajaxUpdateCity(Request $request, $id) {
        $validation = Validator::make($request->all(), config('adminValidations.EDIT_CITY'));
        
        if ($validation->fails()) {
            return $this->validationError($validation);
        } else {
            if(Cities::checkCityExists($id, $request->name) > 0) {
                return $this->Error('CITY_ALREADY_EXISTS');
            }
            if(Auth::guard('admin')->check()) {
                $this->logged_in_user_id = Auth::guard('admin')->user()->id;
            }
            $input['name'] = $request->name;
            $input['state_id'] = $request->state;
            if(isset($id) && $id > 0) {
                $input['updated_by'] = $this->logged_in_user_id;
                Cities::whereId($id)->update($input);
                $actionName = 'Edit/City';
                $msg = "CITY_UPDATED";
            } else {
                $input['created_by'] = $this->logged_in_user_id;
                $city = Cities::create($input);
                $id = $city->id;
                $actionName = 'Add/City';
                $msg = "CITY_CREATED";
            }            
            /**Log activity**/
            $this->logactivity($id, $actionName, $request->ip());
            return $this->success(null, $msg);
        }
        
    }
    
    /**
     * 
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function ajaxUpdateArea(Request $request, $id) {
        
        $validation = Validator::make($request->all(), config('adminValidations.EDIT_AREA'));
        
        if ($validation->fails()) {
            return $this->validationError($validation);
        } else {
            if(Areas::checkAreaExists($id, $request->name) > 0) {
                return $this->Error('AREA_ALREADY_EXISTS');
            }
            
            if(Auth::guard('admin')->check()) {
                $this->logged_in_user_id = Auth::guard('admin')->user()->id;
            }
            
            $input['name'] = $request->name;
            $input['city_id'] = $request->city;
            if(isset($id) && $id > 0) {
                $input['updated_by'] = $this->logged_in_user_id;
                Areas::whereId($id)->update($input);
                $actionName = 'Edit/Area';
                $msg = "AREA_UPDATED";
            } else {
                $input['created_by'] = $this->logged_in_user_id;
                $city = Areas::create($input);
                $id = $city->id;
                $actionName = 'Add/Area';
                $msg = "AREA_CREATED";
            }            
            /**Log activity**/
            $this->logactivity($id, $actionName, $request->ip());
            return $this->success(null, $msg);
        }
        
    }
    
    /**
     * delete region
     * @param type $id
     * @return type
     */
    public function ajaxDelete(Request $request, $id) {
        $validation = Validator::make($request->all(), ['type' => 'required|in:CITY,STATE,AREA']);
        
        if ($validation->fails()) {
            return $this->validationError($validation);
        }
        switch($request->type) {
            case "AREA":
                $area = Areas::find($id);
                if($area) {
                    $id = $area->id;
                    $actionName = 'Delete/Area';
                    $area->delete();
                    /**Log activity**/
                    $this->logactivity($id, $actionName, $request->ip());
                    return $this->success(null, 'AREA_DELETED');
                } else {
                    return $this->success(null, 'NO_RECORD_FOUND');
                }
            break;
            case "CITY":
                $area = Cities::find($id);
                if($area) {
                    $isCityInUse = Areas::select('id')->where('city_id', $id)->first();
                    if($isCityInUse) {
                        return $this->success(null, 'CITY_IN_USE');
                    }
                    $id = $area->id;
                    $actionName = 'Delete/City';
                    $area->delete();
                    /**Log activity**/
                    $this->logactivity($id, $actionName, $request->ip());
                    return $this->success(null, 'CITY_DELETED');
                } else {
                    return $this->success(null, 'NO_RECORD_FOUND');
                }
            break;
            case "STATE":
                $area = States::find($id);
                if($area) {
                    $isStateInUse = Cities::select('id')->where('state_id', $id)->first();
                    if($isStateInUse) {
                        return $this->success(null, 'STATE_IN_USE');
                    }
                    $id = $area->id;
                    $actionName = 'Delete/State';
                    $area->delete();
                    /**Log activity**/
                    $this->logactivity($id, $actionName, $request->ip());
                    return $this->success(null, 'STATE_DELETED');
                } else {
                    return $this->success(null, 'NO_RECORD_FOUND');
                }
            break;
        }
        
    }

    public function getStateList(Request $request)
    {
        $states = DB::table("states")
        ->where("country_id",$request->country_id)
        ->pluck("name","id");
        return response()->json($states);
    }

    public function getCityList(Request $request)
    {
        $cities = DB::table("cities")
        ->where("state_id",$request->state_id)
        ->pluck("name","id");
        return response()->json($cities);
    }

    public function getAreaList(Request $request)
    {
        $areas = DB::table("areas")
        ->where("city_id",$request->city_id)
        ->pluck("name","id");
        return response()->json($areas);
    }
}
