<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;
use App\Models\KitchenItems;
use App\Models\Kitchens;
use App\Models\Groups;
use App\Models\KitchenItemsAddOnAssoc;
use App\Models\KitchenAddOnCategory;
use App\Models\KitchenItemVarients;
use Validator;
use App\Http\Helpers\CommonTrait;
use App\Http\Helpers\ResponseTrait;
use Exception;

class KitchenMenuController extends Controller 
{
    use CommonTrait, ResponseTrait;

    public function __construct() {
        
    }
    
    /**
     * Get Kitchen Menu
     * @param Request $request
     * @return type
     */
    public function getKitchenItems_old(Request $request) {
        $validation = Validator::make($request->all(), ['kitchen_id' => 'required|integer']);
        if ($validation->fails()) {
            return $this->validationError($validation);
        }
        $finalResult = [];
        try {
            $kitchen = Kitchens::select('chef_id')->where('id', $request->kitchen_id)->first();
            $groups = Groups::select('seq_no', 'id', 'group_name')->where('chef_id', $kitchen->chef_id)->orderBy('seq_no', 'ASC')->get()->toArray();
            $kitchenItemsArr = KitchenItems::getKitchenItems($request->kitchen_id, $kitchen->chef_id);
            $result = [];
            foreach($kitchenItemsArr as $item) {
                $tempArray = [];
                $tempArray['item_id'] = $item->id;
                $tempArray['item_name'] = $item->item_name;
                $tempArray['description'] = $item->description;
                $tempArray['average_prep_time'] = $item->average_prep_time;
                $tempArray['price'] = $item->price;
                $tempArray['categories'] = $item->categories;
                $tempArray['groups'] = $item->groups;
                $tempArray['item_image'] = $item->item_image;
                $tempArray['item_banner'] = $item->item_image;
                $tempArray['add_on_count'] = $item->add_on_count;
                $tempArray['varient_count'] = $item->varient_count;
                
                $arrGroups = explode(',', $item->groups);
                foreach($arrGroups as $grp) {
                    $result[$grp][] = $tempArray;
                }
            }

            foreach($groups as $group) {
                foreach($result as $key => $val) {
                    if($group['id'] == $key)
                        $finalResult[] = array('title' => $group['group_name'] , 'items' => $val);
                }
            }
            
            return $this->success(array("list" => $finalResult));
        } catch (Exception $ex) {
            return $this->error('ERROR');
        }
    }
    
    /**
     * * Get Kitchen Menu
     * @param Request $request
     * @return type
     */
    public function getKitchenItems(Request $request) {
        $validation = Validator::make($request->all(), ['kitchen_id' => 'required|integer']);
        if ($validation->fails()) {
            return $this->validationError($validation);
        }
        $finalResult = [];
        try {
            $kitchen = Kitchens::select('chef_id')->where('id', $request->kitchen_id)->first();
            $groups = Groups::select('seq_no', 'id', 'group_name')->where('chef_id', $kitchen->chef_id)->orderBy('seq_no', 'ASC')->get()->toArray();
            $kitchenItemsArr = KitchenItems::getKitchenItems($request->kitchen_id, $kitchen->chef_id);
            
            $result = [];
            foreach($kitchenItemsArr as $item) {
                $tempArray = [];
                $tempArray['item_id'] = $item->id;
                $tempArray['item_name'] = $item->item_name;
                $tempArray['description'] = $item->description;
                $tempArray['average_prep_time'] = $item->average_prep_time;
                $tempArray['price'] = $item->price;
                $tempArray['categories'] = $item->categories;
                $tempArray['groups'] = $item->groups;
                $tempArray['item_image'] = $item->item_image;
                $tempArray['item_banner'] = $item->item_image;
                $tempArray['add_on_count'] = $item->add_on_count;
                $tempArray['varient_count'] = $item->varient_count;
                $tempArray['is_new_image'] = "";
                $dayRange = config('app.IS_NEW_ITEM_DAY_RANGE');
                if((strtotime($item->created_at) > strtotime("-$dayRange days"))) {
                    $tempArray['is_new_image'] = $item->item_image;
                }
                if($item->item_name) {
                    $result[$item->group_id][] = $tempArray;
                }
            }

            foreach($groups as $group) {
                foreach($result as $key => $val) {
                    if($group['id'] == $key) {
                        $finalResult[] = array('title' => $group['group_name'], 'group_id' => $group['id'], 'items' => $val);
                    }
                }
            }

            return $this->success(array("list" => $finalResult));
        } catch (Exception $ex) {
            return $this->error('ERROR');
        }
    }
    
    public function groupByCategories() {
        
    }
    
    /**
     * get item's add ons on item id
     * @param Request $request
     * @return type
     */
    public function getAddOnsOnKitchenItemId(Request $request) {
        $validation = Validator::make($request->all(), ['item_id' => 'required|integer|exists:kitchen_items,id']);
        if ($validation->fails()) {
            return $this->validationError($validation);
        }
        
        try {
            //get varients
            $varientResult = $tempArr = $result = [];
            $varientArr = KitchenItemVarients::where(['kitchen_item_id' => $request->item_id])->get()->toArray();
            if(!empty($varientArr)) {
                $varientResult = array('title' => 'Varient', 'desc' => "Select one of these item", 'type' => "varient", 'selection' => "single");
                foreach($varientArr as $ele) {
                    $tempArr= [];
                    $tempArr['add_on_id'] = $ele['id'];
                    $tempArr['add_on_name'] = $ele['varient_name'];
                    $tempArr['price'] = $ele['varient_price'];
                    $varientResult['items'][] = $tempArr;
                }
                $result[] = $varientResult;
            }
            
            //get add ons
            $addOnResult = $catResult = [];
            $addOnArr = KitchenItemsAddOnAssoc::getAddOnsOnKitchenItemId($request->item_id, 'Multiple');
            
            if(!empty($addOnArr)) {
                foreach($addOnArr as $addOn) {
                    $addOnTempArr = [];
                    $addOnTempArr['add_on_id'] = $addOn->id;
                    $addOnTempArr['add_on_name'] = $addOn->kitchen_add_on_item_name_en;
                    $addOnTempArr['price'] = $addOn->price;
                    $addOnResult[$addOn->category_name_en]['items'][] = $addOnTempArr;
                }
            
                //get add ons cat
                $addOnCatArr = KitchenAddOnCategory::where(['status' => 'Active', 'kitchen_item_id' => $request->item_id])
                                ->orderBy('cat_seq_no', 'ASC')->get()->toArray();

                foreach($addOnCatArr as $addOnCat) {
                    $catResult[$addOnCat['category_name_en']] = array('title' => $addOnCat['category_name_en'],'min' => $addOnCat['min'],
                                                        'max' => $addOnCat['max'],'desc' => "Select Min ".$addOnCat['min']." and Max ".$addOnCat['max'],
                                                        'type' => 'add_on' ,'selection' => 'multiple'); 
                }
                
                //merge add ons in cat and create response
                foreach($addOnResult as $addOnKey => $addOnVal) {
                    $result[] = array_merge($catResult[$addOnKey], $addOnVal);
                }
            }
            
            return $this->success(['list' => $result]);
        } catch (Exception $ex) {
            return $this->error('ERROR');
        }
    }

}