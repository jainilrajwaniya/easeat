<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Models\Orders;
use Validator;
use App\Http\Helpers\CommonTrait;
use App\Http\Helpers\ResponseTrait;
use Exception;
use App\Models\Kitchens;
use App\Models\KitchenItems;
use App\Models\Groups;
use Carbon\Carbon;

class ChefOrderController extends Controller 
{
    use CommonTrait, ResponseTrait;

    public function __construct() {
        $this->currentChef = $this->authenticateChef();
    }
    
    /**
     * get Orders
     * @param Request $request
     * @return type
     */
    public function getOrders(Request $request) {
        try {
        $lang = isset($request->lang) ? $request->lang : '';
            //Check if chef is authorized
            if(empty($this->currentChef->id)) {
                return $this->error('UNAUTHORIZED');
            }
            
            //validate request
            $validation = Validator::make($request->all(), ['order_type' => "in:NEW,ONGOING,PAST"]);
            if ($validation->fails()) {
                return $this->validationError($validation);
            }
            
            //where condition
            $where = " chef_id = ".$this->currentChef->id;
            switch($request->order_type) {
                case "NEW":
                    $where .= " AND O.status IN('Placed') "  ;
                break;
                case "ONGOING":
                    $where .= " AND O.status IN('Cooking', 'Ready', 'OnTheWay') ";
                break;
                default:
                    $where .= " AND O.status IN('Completed') ";
                break;
            }
        
            $orders = Orders::getOrdersList($where);
            
            $result = $subResult = [];
            foreach($orders as $order) {
                $tempArr =[];
                $tempVar = '';
                $arrOrder = json_decode($order->order_json, 1);
                $tempArr['id'] = $order->id;
                $tempArr['status'] = trans('message.'.$order->status);
                $tempArr['contact_person_no'] = $arrOrder['contact_person_no'];
                $tempArr['delivery_type'] = trans('message.'.$arrOrder['delivery_type']);
                if(isset($arrOrder['preorder_time'])) {
                    $tempArr['delivery_type'] .= " / Pre Order";
                } else {
//                    $tempArr['delivery_type'] .= " / Normal";
                }
                $tempArr['preorder_time'] = (isset($arrOrder['preorder_time']) && $arrOrder['preorder_time'] != null) ? Carbon::parse($arrOrder['preorder_time'])->format('d-m-Y H:i') : "";
                $tempArr['created_at'] = Carbon::parse($order->created_at)->format('d-m-Y H:i');
                $tempArr['price'] = $arrOrder['grand_total'];
                //change response on basis of lang
                for($i=0; $i < count($arrOrder['items']); $i++) {
                    $tempVar .= ($lang == 'ar' ? (isset($arrOrder['items'][$i]['item_name_ar']) ? $arrOrder['items'][$i]['item_name_ar'] : $arrOrder['items'][$i]['item_name']) : $arrOrder['items'][$i]['item_name']). " X ". $arrOrder['items'][$i]['quantity'].",";
                }
                $tempArr['items'] = trim($tempVar, ',');
                $result[] = $tempArr;
            }
            
            $msg = (count($result) > 0 ? "ORDER_LIST" : "NO_RECORD_FOUND");
            return $this->success(['list' => $result], $msg);
        } catch (Exception $e) {
            return $this->error('ERROR');
        }
    }
    
    /**
     * Changes order status
     * @param Request $request
     * @return type
     */
    public function changeOrderStatus(Request $request) {
        try {
            //Check if chef is authorized
            if(empty($this->currentChef->id)) {
                return $this->error('UNAUTHORIZED');
            }

            //validate request
            $validation = Validator::make($request->all(), config('frontValidations.CHEF_ORDER_STATUS_CHANGE'));
            if ($validation->fails()) {
                return $this->validationError($validation);
            }

            //check order belongs to chef
            $order = Orders::where(['chef_id' => $this->currentChef->id, 'id' => $request->order_id])->first();
            if(empty($order)) {
                return $this->error('CHEF_ORDER_MISMATCH');
            }

            //chek status is already the required
            if($order->status == $request->status) {
                return $this->success("", 'ORDER_ALREADY_MARKED_AS_REQUIRED');
            }

            $result = false;
            $result = $this->updateOrderStatus($request->order_id, $request->status);
            if($result == true) {
                $msg = "ORDER_MARKED_AS_". strtoupper($request->status);
                return $this->success("", $msg);
            } else {
                return $this->error('ERROR');
            }
        } catch (Exception $ex) {
            return $this->error('ERROR');
        }
    }
    
    /**
     * Changes Chef's status
     * @param Request $request
     * @return type
     */
    public function changeChefStatus(Request $request) {
        try {
            //Check if chef is authorized
            if(empty($this->currentChef->id)) {
                return $this->error('UNAUTHORIZED');
            }

            //validate request
            $validation = Validator::make($request->all(), ['status' => 'required|in:Active,Inactive']);
            if ($validation->fails()) {
                return $this->validationError($validation);
            }
            
            $open = 0;
            $msg = 'CHEF_MARKED_AS_OFFLINE';
            if($request->status == 'Active') {
                $open = 1;
                $msg = 'CHEF_MARKED_AS_ONLINE';
            }
            $result = $this->updateKitchenOpenStatus($this->currentChef->id, $open);
            
            if($result == true) {
                return $this->success("", $msg);
            } else {
                return $this->error('ERROR');
            }
        } catch (Exception $ex) {
            return $this->error('ERROR');
        }
    }
    
    /**
     * Get Chef's status
     * @param Request $request
     * @return type
     */
    public function getChefStatus(Request $request) {
        try {
            //Check if chef is authorized
            if(empty($this->currentChef->id)) {
                return $this->error('UNAUTHORIZED');
            }
            $kitchen = Kitchens::where(['chef_id' => $this->currentChef->id])->first();
            $msg = 'CHEF_IS_OFFLINE';
            if($kitchen->open) {
                $msg = 'CHEF_IS_ONLINE';
            }

            return $this->success($kitchen->open, $msg);
        } catch (Exception $ex) {
            return $this->error('ERROR');
        }
    }
    
    /**
     * get Order Detail
     * @param Request $request
     * @return type
     */
    public function getOrderDetail(Request $request) {
        try {
            //Check if chef is authorized
            if(empty($this->currentChef->id)) {
                return $this->error('UNAUTHORIZED');
            }
            
            $lang = isset($request->lang) ? $request->lang : '';
            //validate request
            $validation = Validator::make($request->all(), ['order_id' => 'required|integer|exists:orders,id']);
            if ($validation->fails()) {
                return $this->validationError($validation);
            }
            $order = Orders::where(['id'=> $request->order_id])->first();
            if(!empty($order)) {
                $result = json_decode($order->order_json, 1);
                //change response on basis of lang
                for($i=0; $i < count($result['items']); $i++) {
                    $result['items'][$i]['item_name'] = ($lang == 'ar' ? (isset($result['items'][$i]['item_name_ar']) ? $result['items'][$i]['item_name_ar'] : $result['items'][$i]['item_name'] ) : $result['items'][$i]['item_name'] );
                    $result['items'][$i]['varient_name'] = ($lang == 'ar' ? (isset($result['items'][$i]['varient_name_ar']) ? $result['items'][$i]['varient_name_ar'] : $result['items'][$i]['varient_name']) : $result['items'][$i]['varient_name'] );
                    if(isset($result['items'][$i]['addons'])) {
                        for($j=0; $j < count($result['items'][$i]['addons']) ; $j++) {
                            $result['items'][$i]['addons'][$j]['add_on_name'] = ($lang == 'ar' ? (isset($result['items'][$i]['addons'][$j]['add_on_name_ar'])? $result['items'][$i]['addons'][$j]['add_on_name_ar'] : $result['items'][$i]['addons'][$j]['add_on_name'] ) : $result['items'][$i]['addons'][$j]['add_on_name'] );
                        }
                    }
                }
                $result['status'] = trans('message.'.$order->status);
                $result['delivery_type'] = trans('message.'.$order->delivery_type);
                $result['preorder_time'] = ($order->preorder_time != null) ? $order->preorder_time : "";
                $result['created_at'] = Carbon::parse($order->created_at)->format('Y-m-d H:i:s');
                switch($result['status']) {
                    case "Placed":
                        $result['change_next_button_text'] = "Accept Order";
                        $result['change_next_status'] = 'Cooking';
                    break;
                    case "Cooking":
                        $result['change_next_button_text'] = "Mark As Ready";
                        $result['change_next_status'] = "Ready";
                    break;
                    case "Ready":
                        $result['change_next_button_text'] = "Mark As On The Way";
                        $result['change_next_status'] = 'OnTheWay';
                    break;
                    case "OnTheWay":
                        $result['change_next_button_text'] = "Mark As Completed";
                        $result['change_next_status'] = 'Completed';
                    break;
                    default:
                        $result['change_next_button_text'] = 'Completed';
                        $result['change_next_status'] = '';
                    break;
                }
                
                return $this->success($result,'ORDER_FOUND');
            } else {
                return $this->success([],'ORDER_NOT_FOUND');
            }
        } catch (Exception $e) {
            return $this->error('ERROR');
        }
    }
    
    /**
     * * Get Kitchen Menu
     * @param Request $request
     * @return type
     */
    public function getKitchenItems(Request $request) {
        //Check if chef is authorized
        if(empty($this->currentChef->id)) {
            return $this->error('UNAUTHORIZED');
        }
        
        $finalResult = [];
        try {
            $kitchen = Kitchens::select('id', 'chef_id')->where('chef_id', $this->currentChef->id)->first();
            $groups = Groups::select('seq_no', 'id', 'group_name')->where('chef_id', $kitchen->chef_id)->orderBy('seq_no', 'ASC')->get()->toArray();
            $kitchenItemsArr = KitchenItems::getKitchenItems($kitchen->id, $kitchen->chef_id, 'chef');
            
            $result = [];
            foreach($kitchenItemsArr as $item) {
                $tempArray = [];
                $tempArray['item_id'] = $item->id;
                $tempArray['item_name'] = $item->item_name;
                $tempArray['status'] = $item->status;
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
    
    
    /**
    * Change status of Items from Chef App
    * @return type
    */
    public function changeItemStatus(Request $request) {
        try {
            //Check if chef is authorized
            if(empty($this->currentChef->id)) {
                return $this->error('UNAUTHORIZED');
            }
            
            //validate request
            $validation = Validator::make($request->all(), ['item_id' => 'required|integer']);
            if ($validation->fails()) {
                return $this->validationError($validation);
            }
            
            $item = KitchenItems::find($request->item_id);
            if($item) {
                if ($item->status == 'Active') {
                    $item->status = 'Inactive';
                } else {
                    $item->status = 'Active';
                }
                $item->save();
                $msg = "KITCHEN_MENU_STATUS_CHANGED";
            } else {
                $msg = "NO_RECORD_FOUND";
            }

            return $this->success(['status' => $item->status], $msg);
        } catch (Exception $ex) {
            return $this->error('ERROR');
        }
    }
}