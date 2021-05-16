<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Models\Kitchens; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use App\Http\Helpers\CommonTrait;
use App\Http\Helpers\ResponseTrait;
use Exception;
use DB;

class KitchenController extends Controller 
{
    use CommonTrait, ResponseTrait;

    public function __construct() {
        
    }
    
    /**
     * Get kitchen details
     * @param Request $request
     * @return type
     */
    public function getKitchenDetail(Request $request) {
        $validation = Validator::make($request->all(), ['kitchen_id' => 'required|integer']);
        
        if ($validation->fails()) {
            return $this->validationError($validation);
        }
       
        try {
            $kitchenDetailArr = Kitchens::getKitchenDetails($request->kitchen_id);
            if(!empty($kitchenDetailArr[0]->chef_id)) {
                $arrResult['chef_id'] = $kitchenDetailArr[0]->chef_id;
                $arrResult['chef_name'] = $kitchenDetailArr[0]->chef_name;
                $arrResult['kitchen_id'] = $kitchenDetailArr[0]->kitchen_id;
                $arrResult['per_person_cost'] = $kitchenDetailArr[0]->per_person_cost;
                $arrResult['cuisine_types'] = $kitchenDetailArr[0]->cuisine_types;
                $arrResult['categories'] = $kitchenDetailArr[0]->categories;
                $arrResult['rating'] = $kitchenDetailArr[0]->rating;
                $arrResult['prep_time'] = $kitchenDetailArr[0]->prep_time;
                $arrResult['image'] = $kitchenDetailArr[0]->image;
                $arrResult['banner'] = $kitchenDetailArr[0]->image;
                $arrResult['delivery_time'] = $kitchenDetailArr[0]->prep_time;
                $arrResult['min_order_value'] = $kitchenDetailArr[0]->min_order_home_delivery;
                $arrResult['delivery_fee'] = $kitchenDetailArr[0]->delivery_fee;
                $arrResult['pre_order'] = $kitchenDetailArr[0]->pre_order;
                $arrResult['area'] = $kitchenDetailArr[0]->area;
                $arrResult['opening_hours'] = $kitchenDetailArr[0]->from_time1.':00 - '. $kitchenDetailArr[0]->to_time1.':00 , '.
                                              $kitchenDetailArr[0]->from_time2 .':00 - '. $kitchenDetailArr[0]->to_time2.':00';  
                $arrResult['accepting_order'] = 0;
                
                $currentHr = date('H'); 
                if($kitchenDetailArr[0]->open == 1 && 
                (($kitchenDetailArr[0]->from_time1 < $currentHr && $currentHr <  $kitchenDetailArr[0]->to_time1) ||
                 ($kitchenDetailArr[0]->from_time2 < $currentHr && $currentHr <  $kitchenDetailArr[0]->to_time2))) {
                    $arrResult['accepting_order'] = 1;
                }

                return $this->success($arrResult);
            } else {
                return $this->error('NO_KITCHEN_FOUND');
            }
        } catch (Exception $ex) {
            DB::rollback();
            return $this->error('ERROR');
        }
    }
    
    /**
     * get kitchens on home screens
     * @param Request $request
     * @return type
     */
    public function getKitchenMenu(Request $request) {
        try {
            $chefsArr = Chef::getChefsWithKitchens($request->all());
            return $this->success($chefsArr);
        } catch (Exception $ex) {
            DB::rollback();
            return $this->error('ERROR');
        }
    }

}