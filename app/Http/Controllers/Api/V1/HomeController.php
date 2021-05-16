<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Models\Chef; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use App\Http\Helpers\CommonTrait;
use App\Http\Helpers\ResponseTrait;
use Exception;
use DB;
use Carbon\Carbon;
use App\Models\Categories;
use App\Models\CuisineTypes;

class HomeController extends Controller 
{
    use CommonTrait, ResponseTrait;

    public function __construct() {
        
    }
    
    /**
     * get kitchens on home screens
     * @param Request $request
     * @return type
     */
    public function getChefs(Request $request) {
//        $lat1 = '24.596375';
//        $lon1 = '73.685811'; 
//        $lat2 = '25.5909920';
//        $lon2 = '73.7433840';
//        $unit = 'M';
//        echo $this->distance($lat1, $lon1, $lat2, $lon2, $unit);
//        echo "++++";
//        echo $this->haversineGreatCircleDistance($lat1, $lon1, $lat2, $lon2);
//        echo "++++";
//        echo $this->vincentyGreatCircleDistance($lat1, $lon1, $lat2, $lon2);
//        die();
        $validation = Validator::make($request->all(), ['lat' => 'required', 'long' => 'required']);
        
        if ($validation->fails()) {
            return $this->validationError($validation);
        }
        
        try {
            $chefsArr = Chef::getChefsWithKitchens($request->all());
            $arrResult = [];
            foreach($chefsArr as $chefsEle){
                $arrTemp = [];
                $arrTemp['chef_id'] = $chefsEle->chef_id;
                $arrTemp['chef_name'] = $chefsEle->name;
                $arrTemp['kitchen_id'] = $chefsEle->kitchen_id;
                $arrTemp['per_person_cost'] = $chefsEle->per_person_cost;
                $arrTemp['cuisine_types'] = $chefsEle->cuisine_types;
                $arrTemp['categories'] = $chefsEle->categories;
                $arrTemp['rating'] = $chefsEle->rating;
                $arrTemp['prep_time'] = $chefsEle->prep_time." Mins";
                $arrTemp['image'] = $chefsEle->image;
                $arrTemp['banner'] = $chefsEle->image;
                $arrTemp['accepting_order'] = 0;
                $currentHr = date('H');
                if($chefsEle->open == 1 && 
                (($chefsEle->from_time1 < $currentHr && $currentHr <  $chefsEle->to_time1) ||
                 ($chefsEle->from_time2 < $currentHr && $currentHr <  $chefsEle->to_time2))) {
                    $arrTemp['accepting_order'] = 1;
                }
                $arrResult[] = $arrTemp;
            }
            return $this->success(array("list" => $arrResult));
        } catch (Exception $ex) {
            DB::rollback();
            return $this->error('ERROR');
        }
    }
    
    public function getSearchFilters() {
        $result['delivery_type'][] = ['name' => 'PickUp'];
        $result['delivery_type'][] = ['name' => 'HomeDelivery'];

        $cat = Categories::where(['status' => 'Active'])->get();
        foreach($cat as $ele) {
            $temp = [];
            //$temp['id'] = $ele->category_name;
            $temp['name'] = $ele->category_name;
            $catResult[] = $temp;
        }
        $result['categories'] = $catResult;
        $ct = CuisineTypes::where(['status' => 'Active'])->get();
        foreach($ct as $ele) {
            $temp = [];
            //$temp['id'] = $ele->cuisine_type_name;
            $temp['name'] = $ele->cuisine_type_name;
            $ctResult[] = $temp;
        }
        $result['cuisine_types'] = $ctResult;
        return $this->success(array("list" => $result));
    }
    
    public function testFCM(Request $request) {
        $this->test_FCM(array($request->fcm_token), "Hello !", "Testing");
    }

}