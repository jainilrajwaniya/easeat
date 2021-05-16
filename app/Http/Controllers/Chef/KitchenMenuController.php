<?php

namespace App\Http\Controllers\Chef;

use App\Http\Controllers\Controller;
use App\Models\KitchenItems;
use App\Models\Kitchens;
use App\Models\CuisineTypes;
use App\Models\Categories;
use App\Models\Groups;
use App\Models\KitchenAddOnCategory;
use App\Models\KitchenAddOnItems;
use App\Models\KitchenItemsAddOnAssoc;
use App\Models\KitchenItemVarients;
use App\Models\KitchenItemOrder;
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
use Auth;

class KitchenMenuController extends Controller
{
    use CommonTrait, ResponseTrait, UploadImageTrait, UploadImageOnBucket;
                                    
    public function __construct(UrlGenerator $url) {
        $this->url = $url;
        $this->pageMeta['pageName'] = "Kitchen Menu";
        $this->pageMeta['pageDes'] = "Manage Kitchen Menu here!!";
        $this->pageMeta['breadCrumbs'] = array("Home" => $this->url->to('/chef/'), "Kitchen Menu" => "");
    }
    
    
    /**
     * log activity listing page
     * @return type
     */
    public function index_old() {
        $finalResult = [];
        if(Auth::guard('chef')->check()) {
            $this->logged_in_user_id = Auth::guard('chef')->user()->id;
        }
        $kitchen = Kitchens::where('chef_id', $this->logged_in_user_id)->first();
        $groups = Groups::select('seq_no', 'id', 'group_name')->where('chef_id', $kitchen->chef_id)->orderBy('seq_no', 'ASC')->get()->toArray();
        $kitchenItemsArr = KitchenItems::getKitchenItems_admin($kitchen->id);

        $result = [];
        foreach($kitchenItemsArr as $item) {
            $tempArray = [];
            $tempArray['item_id'] = $item->id;
            $tempArray['item_name'] = $item->item_name;
            $tempArray['description'] = $item->description;
            $tempArray['average_prep_time'] = $item->average_prep_time;
            $tempArray['price'] = $item->price;
            $tempArray['categories'] = $item->categories;
            $tempArray['cuisine_types'] = $item->cuisine_types;
            $tempArray['groups'] = $item->groups;
            $tempArray['item_image'] = $item->item_image;
            $tempArray['item_banner'] = $item->item_image;
            $tempArray['status'] = $item->status;

            $arrGroups = explode(',', $item->groups);
            foreach($arrGroups as $grp) {
                $result[$grp][] = $tempArray;
            }
        }

        foreach($groups as $group) {
            foreach($result as $key => $val) {
                if($group['id'] == $key) {
                    $finalResult[] = array('title' => $group['group_name'], 'group_id' => $group['id'], 'items' => $val);
                }
            }
        }

        return view('chef.kitchen-menu.index', ['pageMeta' => $this->pageMeta, 'result' => $finalResult]);
    }
    
    public function index() {
        $finalResult = [];
        if(Auth::guard('chef')->check()) {
            $this->logged_in_user_id = Auth::guard('chef')->user()->id;
        }
        $kitchen = Kitchens::where('chef_id', $this->logged_in_user_id)->first();
        $groups = Groups::select('seq_no', 'id', 'group_name')->where('chef_id', $kitchen->chef_id)->orderBy('seq_no', 'ASC')->get()->toArray();
        $kitchenItemsArr = KitchenItems::getKitchenItems_admin($this->logged_in_user_id);

        $result = [];
        foreach($kitchenItemsArr as $item) {
            $tempArray = [];
            $tempArray['group_id'] = $item->group_id;
            $tempArray['group_name'] = $item->group_name;
            $tempArray['item_id'] = $item->id;
            $tempArray['item_name'] = $item->item_name;
            $tempArray['description'] = $item->description;
            $tempArray['average_prep_time'] = $item->average_prep_time;
            $tempArray['price'] = $item->price;
            $tempArray['categories'] = $item->categories;
            $tempArray['cuisine_types'] = $item->cuisine_types;
            $tempArray['status'] = $item->status;
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
    
        return view('chef.kitchen-menu.index', ['pageMeta' => $this->pageMeta, 'result' => $finalResult]);
    }
    
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function ajaxGetKitchenMenuList(Request $request) {
        $now = date('Y-m-d');
        if(Auth::guard('chef')->check()) {
            $this->logged_in_user_id = Auth::guard('chef')->user()->id;
        }
        $kitchen = Kitchens::where('chef_id', $this->logged_in_user_id)->first();

        $promo = KitchenItems::Join('kitchens', 'kitchen_items.kitchen_id', '=', 'kitchens.id')->where('kitchen_items.kitchen_id', $kitchen->id)->select(['kitchen_items.*', DB::raw('DATE_FORMAT(kitchen_items.created_at, "%m/%d/%Y") as created_date'), 'kitchen_items.cuisine_types AS cuisine_type_name', 'kitchen_items.categories AS category_name', 'kitchen_items.groups AS group_name']);

        // Using the Engine Factory
        return Datatables::of($promo)
            ->make(true);
    }

    /**
     * Kitchen Menu Add Edit page
     * @return type
     */
    public function editKitchenMenu($id = 0) {
        $this->pageMeta['breadCrumbs'] = array("Home" => $this->url->to('/chef/'), "Kitchen Menu List" => $this->url->to("/chef/kitchenmenu/listing"), 'Add / Edit Kitchen Menu' => '');
        $menuArr = [];
        $kitchenArr['cuisine_types'] = $kitchenArr['categories'] = $kitchenArr['groups'] = $kitchenVarientsArr =array();
        if(Auth::guard('chef')->check()) {
            $this->logged_in_user_id = Auth::guard('chef')->user()->id;
        }
        if($id > 0) {
            $menuObj = KitchenItems::find($id);
            if(!isset($menuObj->id)) {
                return redirect('/chef/kitchenmenu/listing');
            }
            $menuArr = $menuObj->toArray();
            $kitchenArr['categories'] = explode(',', $menuArr['categories']);
            $kitchenArr['cuisine_types'] = explode(',', $menuArr['cuisine_types']);
            $kitchenArr['groups'] = explode(',', $menuArr['groups']);
            $editValidationRules = [
                                        'item_name'  => 'required',
                                        'item_name_ar'  => 'required',
                                        // 'kitchen_id'  => 'required',
                                        'groups'  => 'required',
                                        'categories'  => 'required',
                                        'cuisine_types'  => 'required',
                                        'average_prep_time'  => 'required',
                                        'price'  => 'required|numeric',
//                                        'profile_pic' =>  'mimes:jpeg,jpg,png',
                                    ];
            $validator = JsValidator::make($editValidationRules,[],[],'#addEditPromoForm');
            $kitchenVarientsArr = KitchenItemVarients::where(['kitchen_item_id' => $id])->get()->toArray();
        }else{
            $addValidationRules = [
                                        'item_name'  => 'required',
                                        'item_name_ar'  => 'required',
                                        // 'kitchen_id'  => 'required',
                                        'groups'  => 'required',
                                        'categories'  => 'required',
                                        'cuisine_types'  => 'required',
                                        'average_prep_time'  => 'required',
                                        'price'  => 'required|numeric',
//                                        'profile_pic' =>  'required | mimes:jpeg,jpg,png',
                                    ];
            $validator = JsValidator::make($addValidationRules,[],[],'#addEditPromoForm');
        }
        
        $cuisine_types = CuisineTypes::all();
        $categories = Categories::all();
        $groups = Groups::where(['chef_id' => $this->logged_in_user_id])->get();
        return view('chef.kitchen-menu.edit', ['kitchenArr' => $kitchenArr, 'kitchenVarientsArr' => $kitchenVarientsArr, 'pageMeta' => $this->pageMeta, 'menuArr' => $menuArr, 'adminRolesArr' => config('dbFields.ADMIN_ROLES'), 'validator' => $validator, 'categories' => $categories, 'cuisine_types' => $cuisine_types, 'groups' => $groups]);
    }
    
    
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function saveKitchenMenu(Request $request) {
        if(Auth::guard('chef')->check()) {
            $this->logged_in_user_id = Auth::guard('chef')->user()->id;
        }
        $kitchen = Kitchens::where('chef_id', $this->logged_in_user_id)->first();
        $input['kitchen_id'] = $kitchen->id;    
        
        if(isset($request->id) && $request->id > 0) {
            $editValidationRules = [
                                        'item_name'  => 'required',
                                        'item_name_ar'  => 'required',
                                        // 'kitchen_id'  => 'required',
                                        'groups'  => 'required',
                                        'categories'  => 'required',
                                        'cuisine_types'  => 'required',
                                        'average_prep_time'  => 'required',
                                        'price'  => 'required|numeric',
//                                        'profile_pic' =>  'mimes:jpeg,jpg,png',
                                        'varient_name.*' =>  'required',
                                        'varient_name_ar.*' =>  'required',
                                        'varient_price.*' =>  'required|numeric'
                                    ];
            $validation = Validator::make($request->all(), $editValidationRules);
            $chechExists = KitchenItems::where('item_name' ,  $request->item_name)->where('kitchen_id' ,  $kitchen->id)->where('id' , '!=',  $request->id)->count(); 
            if($chechExists) {
                return redirect()->back()->withErrors(['The '.$request->item_name.' has already been taken.'])->withInput();
            }
        } else {
            $addValidationRules = [
                                        'item_name'  => 'required',
                                        'item_name_ar'  => 'required',
                                        // 'kitchen_id'  => 'required',
                                        'groups'  => 'required',
                                        'categories'  => 'required',
                                        'cuisine_types'  => 'required',
                                        'average_prep_time'  => 'required',
                                        'price'  => 'required|numeric',
//                                        'profile_pic' =>  'required | mimes:jpeg,jpg,png',
                                        'varient_name.*' =>  'required',
                                        'varient_name_ar.*' =>  'required',
                                        'varient_price.*' =>  'required|numeric'
                                    ];
            $validation = Validator::make($request->all(), $addValidationRules);
            $chechExists = KitchenItems::where(['item_name' => $request->item_name, 'kitchen_id' => $kitchen->id])->count(); 
            if($chechExists) {
                return redirect()->back()->withErrors(['The '.$request->item_name.' has already been taken.'])->withInput();
            }
        }
         
        
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        } else {
            ini_set('upload_max_filesize', '8M');//allow 8 MB
            
            $input['item_name'] = $request->item_name;
            $input['item_name_ar'] = $request->item_name_ar;
            $input['description'] = isset($request->description) ? $request->description : '';
            $input['groups'] = implode(',', $request->groups);
            $input['cuisine_types'] = implode(',', $request->cuisine_types);
            $input['categories'] = implode(',', $request->categories);
            $input['average_prep_time'] = $request->average_prep_time;
            $input['price'] = $request->price;
            $input['pure_veg'] = $request->pure_veg;
            $input['status'] = 'Active';
            
            if(isset($request->id) && $request->id > 0) {
                if (!empty($request->file('profile_pic'))) {
                    $image = $request->file('profile_pic');
                    $imageData = [
                        'id' => $request->id,
                        'image' => $image,
                        'folder_name' => 'kitchen-menu'
                    ];
                    $imageName = $this->uploadImage($imageData);
                    $this->imageUploadOnBucket($imageData['folder_name'], $imageData['image'], $imageName, $imageData['id']);
                    if ($imageName) {
                        $input['profile_pic'] = $imageName;
                    } else {
                        $input['profile_pic'] = '';
                    }
                }
                $input['updated_by'] = $this->logged_in_user_id;

                KitchenItems::whereId($request->id)->update($input);
                /**save varients****/
                $this->editKitchenVarients($request->varient_name, $request->varient_name_ar, $request->varient_id, $request->varient_price, $request->id);
                $id = $request->id;     
                /**save kitchen item order / group assoc****/
                $this->saveKitchenItemOrder($id, $input['groups']);           
                $actionName = 'Edit/Kitchen Menu';

                $notification = array(
                    'message' => 'Kitchen Menu updated successfully', 
                    'alert-type' => 'success'
                );

            } else {
                $input['created_by'] = $this->logged_in_user_id;
                $menu = KitchenItems::create($input);
                $id = $menu->id;
                if (!empty($request->file('profile_pic'))) {
                    $image = $request->file('profile_pic');
                    $imageData = [
                        'id' => $id,
                        'image' => $image,
                        'folder_name' => 'kitchen-menu'
                    ];
                    $imageName = $this->uploadImage($imageData);
                    $this->imageUploadOnBucket($imageData['folder_name'], $imageData['image'], $imageName, $imageData['id']);
                    if ($imageName) {
                        $input['profile_pic'] = $imageName;
                    } else {
                        $input['profile_pic'] = '';
                    }
                    KitchenItems::whereId($id)->update($input);
                }
                /**save varients****/
                $this->editKitchenVarients($request->varient_name, $request->varient_name_ar, $request->varient_id, $request->varient_price, $id);
                /**save kitchen item order  / group assoc****/
                $this->saveKitchenItemOrder($id, $input['groups']);
                $actionName = 'Add/Kitchen Menu';
                $notification = array(
                    'message' => 'Kitchen Menu added successfully', 
                    'alert-type' => 'success'
                );
            }
            
            /**Log activity**/
            $this->logactivity($id, $actionName, $request->ip());
            return redirect('/chef/kitchenmenu/listing')->with($notification);
        }
    }
    
    /**
     * add/edit kitchen varients
     * @param Request $request
     */
    public function editKitchenVarients($arrvarientName, $arrvarientNameAr, $arrvarientid, $arrvarientPrice, $kitchen_item_id) {
        if(is_array($arrvarientName)) {
            for($i=0;$i<count($arrvarientName);$i++) {
                $inputSizeData['varient_name'] = $arrvarientName[$i];
                $inputSizeData['varient_name_ar'] = $arrvarientNameAr[$i];
                $inputSizeData['varient_price'] = $arrvarientPrice[$i];
                if($arrvarientid[$i] > 0) {
                    KitchenItemVarients::where(['id' => $arrvarientid[$i]])->update($inputSizeData);
                } else {
                    $inputSizeData['kitchen_item_id'] = $kitchen_item_id;
                    KitchenItemVarients::create($inputSizeData);
                }
            }
        }
    }

    /**
     * change Kitchen Menus status
     * @param type $id
     * @return type
     */
    public function changeKitchenMenuStatus($id) {
        $chef = KitchenItems::find($id);
        if($chef) {
            if ($chef->status == 'Active') {
                $chef->status = 'Inactive';
            } else {
                $chef->status = 'Active';
            }
            $chef->save();
            $msg = "KITCHEN_MENU_STATUS_CHANGED";
        } else {
            $msg = "NO_RECORD_FOUND";
        }
        
        return $this->success(null, $msg);
    }

    
    public function indexAddon($id=0) {
        $addOnsCats = KitchenAddOnCategory::select(['status','category_name_en', 'kitchen_item_id',DB::raw('DATE_FORMAT(kitchen_add_on_category.created_at, "%m/%d/%Y") as created_date'), 'id', 'choices'])
                        ->where('kitchen_item_id', $id)->orderBy('cat_seq_no', 'ASC')->get();
        return view('chef.kitchen-menu.index_addon', ['pageMeta' => $this->pageMeta, 'addOnsCats' => $addOnsCats]);
    }
    
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function ajaxGetKitchenMenuAddonList($kitchen_item_id, Request $request) {
        $promo = KitchenAddOnCategory::select(['category_name_en', DB::raw('DATE_FORMAT(kitchen_add_on_category.created_at, "%m/%d/%Y") as created_date'), 'id', 'choices']);

        // Using the Engine Factory
        return Datatables::of($promo)
            ->make(true);
    }
    
    /**
     * Update add on category order on drag drop
     * @param Request $request
     * @return type
     */
    public function ajaxEditAddonCatOrder(Request $request) {
        if($request->kitchen_item_id && $request->cat_order) {
            $item_order = explode("||", $request->cat_order);
            for($i = 0 ; $i < count($item_order) ; $i++) {
                KitchenAddOnCategory::where([ 'kitchen_item_id' => $request->kitchen_item_id, 'id' => $item_order[$i]])->update(['cat_seq_no' => $i]);
            }
            return $this->success(null, 'ITEM_ORDER_CHANGED');
        } else {
            return $this->error('ERROR');
        }
    }
  
    /**
     * Kitchen Menu Add Edit page
     * @return type
     */
    public function editKitchenAddOnMenu($kitchenItemId, $id = 0) {
        $this->pageMeta['breadCrumbs'] = array("Home" => $this->url->to('/chef/'), "Kitchen Menu List" => $this->url->to("/chef/kitchenmenu/addon/listing"), 'Add / Edit Kitchen Add On' => '');
        $menuArr = [];
        if($id > 0) {
            $addOncat = KitchenAddOnCategory::find($id);
            if(!isset($addOncat->id)) {
                return redirect('/chef/kitchenmenu/listing');
            }
            $menuArr = $addOncat->toArray();
            if(!empty($menuArr)){
                $kitchenItemAssoc = KitchenItemsAddOnAssoc::where('kitchen_item_id', $kitchenItemId)->where('kitchen_add_on_cat_id', $menuArr['id'])->select(['kitchen_add_on_item_name_en', 'kitchen_add_on_item_name_ar','price','seq_no','id'])->orderBy('seq_no', 'ASC')->get()->toarray();
                $menuArr['items'] = $kitchenItemAssoc;
            }
            
            $editAddOnValidationRules = [
                                        'category_name_en'  => 'required',
                                        'min'  => 'required',
                                        'max'  => 'required',
                                        'cat_seq_no'  => 'required',
                                    ];
            $validator = JsValidator::make($editAddOnValidationRules,[],[],'#addEditPromoForm');
        }else{
            $addAddOnValidationRules = [
                                        'category_name_en'  => 'required',
                                        'min'  => 'required',
                                        'max'  => 'required',
                                        'cat_seq_no'  => 'required',
                                        'add_on_name'  => 'required',
                                    ];
            $validator = JsValidator::make($addAddOnValidationRules,[],[],'#addEditPromoForm');
        }

        return view('chef.kitchen-menu.add_on_edit', ['pageMeta' => $this->pageMeta, 'menuArr' => $menuArr, 'adminRolesArr' => config('dbFields.ADMIN_ROLES'), 'validator' => $validator, 'kitchenItemId' => $kitchenItemId]);
    }
      
    public function saveKitchenAddOn(Request $request) {
        $kitchen_item_id = $request->kitchenItemId;
        if(Auth::guard('chef')->check()) {
            $this->logged_in_user_id = Auth::guard('chef')->user()->id;
        }
        
        if(isset($request->id) && $request->id > 0) {
            $editValidationRules = [
                                        'category_name_en'  => 'required',
                                        'min'  => 'required',
                                        'max'  => 'required',
                                        'cat_seq_no'  => 'required',
                                        'add_on_name.*'  => 'required',
                                        'add_on_name_ar.*'  => 'required',
                                    ];
            $validation = Validator::make($request->all(), $editValidationRules);
        } else {
            $addValidationRules = [
                                        'category_name_en'  => 'required',
                                        'min'  => 'required',
                                        'max'  => 'required',
                                        'cat_seq_no'  => 'required',
                                        'add_on_name.*'  => 'required',
                                        'add_on_name_ar.*'  => 'required',
                                    ];
            $validation = Validator::make($request->all(), $addValidationRules);
        }
        
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        } else {
            $input['category_name_en'] = $request->category_name_en;
            $input['kitchen_item_id'] = $kitchen_item_id;
            $input['min'] = isset($request->min) ? $request->min : 0;
            $input['max'] = isset($request->max) ? $request->max : 0;
            $input['cat_seq_no'] = isset($request->cat_seq_no) ? $request->cat_seq_no : 0;
            $input['choices'] = "Multiple";
            
            if(isset($request->id) && $request->id > 0) {
                $input['updated_by'] = $this->logged_in_user_id;
                $checkCategory = KitchenAddOnCategory::where('category_name_en', 'LIKE', ''. $request->category_name_en. '')
                                    ->where('kitchen_item_id', $kitchen_item_id)->where('id','!=' ,$request->id)->first();
                if(!empty($checkCategory)) {
                    return redirect()->back()->withErrors(['There is already an add on category with this name in this Kitchen Items'])->withInput();
                }
                
                $kitchen_add_on_cat_id = $request->id;
                KitchenAddOnCategory::whereId($request->id)->update($input);

                $this->editAddOns($kitchen_item_id, $kitchen_add_on_cat_id, $request);
                $actionName = 'Edit/Kitchen Menu';
                $notification = array(
                    'message' => 'Kitchen Add on updated successfully', 
                    'alert-type' => 'success'
                );
            } else {
                $checkCategory = KitchenAddOnCategory::where('category_name_en', 'LIKE', ''. $request->category_name_en. '')
                                    ->where('kitchen_item_id', $kitchen_item_id)->first();
                if(!empty($checkCategory)){
                    return redirect()->back()->withErrors(['There is already an add on category with this name in this Kitchen Items'])->withInput();
                }else{
                    $addoncategory = KitchenAddOnCategory::create($input);
                    $cat_id = $addoncategory->id;
                }
                
                $this->editAddOns($kitchen_item_id, $cat_id, $request);
                
                $actionName = 'Add/Kitchen Add On';
                $notification = array(
                    'message' => 'Kitchen Add On added successfully', 
                    'alert-type' => 'success'
                );
            }
            
            /**Log activity**/
            $this->logactivity($kitchen_item_id, $actionName, $request->ip());
            return redirect('/chef/kitchenmenu/addon/listing/'.$kitchen_item_id)->with($notification);
        }
    }
    
    /**
    * Edit add on items
    * @param type $kitchen_item_id
    * @param type $id
    * @param type $request
    */
    public function editAddOns($kitchen_item_id, $catid, $request) {
        if(Auth::guard('chef')->check()) {
            $this->logged_in_user_id = Auth::guard('chef')->user()->id;
        }
        if(is_array($request->add_on_name)) {
            for($i = 0; $i < count($request->add_on_name) ; $i++) {
                $assocArr['kitchen_item_id'] = $kitchen_item_id;
                $assocArr['kitchen_add_on_cat_id'] = $catid;
                $assocArr['kitchen_add_on_item_name_en'] = $request->add_on_name[$i];
                $assocArr['kitchen_add_on_item_name_ar'] = $request->add_on_name_ar[$i];
                $assocArr['price'] = isset($request->price[$i]) ? $request->price[$i] : 0;
                $assocArr['seq_no'] = isset($request->seq_no[$i]) ? $request->seq_no[$i] : 0;
                if(isset($request->add_on_id[$i]) && $request->add_on_id[$i] > 0) {
                    $assocArr['updated_by'] = $this->logged_in_user_id;
                    KitchenItemsAddOnAssoc::where(['id' => $request->add_on_id[$i]])->update($assocArr);
                } else {
                    $assocArr['created_by'] = $this->logged_in_user_id;
                    KitchenItemsAddOnAssoc::create($assocArr);
                }
                
            }
        }
    }

    /**
     * Kitchen Menu Add Edit page
     * @return type
     */
    public function ajaxGetCategoryList(Request $request) {
        $search = $request->get('term');
        $result = KitchenAddOnCategory::where('category', 'LIKE', '%'. $search. '%')->get();
        return response()->json($result);
    }
    
    /**
     * delete item varient
     * @param Request $request
     * @return type
     */
    public function ajaxDeleteVarient(Request $request) {
        $ktcItmVarObj = KitchenItemVarients::where(['id' => $request->id])->first();
        if(!empty($ktcItmVarObj)) {
            $ktcItmVarObj->delete();
            return $this->success(null, 'VARIENT_DELETED');
        } else {
            return $this->error('VARIENT_NOT_FOUND');
        }
    }
    
    /**
    * delete item Addon
    * @param Request $request
    * @return type
    */
    public function ajaxDeleteAddonCat(Request $request) {
        kitchenAddOnCategory::where(['id' => $request->id])->delete();
        KitchenItemsAddOnAssoc::where(['kitchen_add_on_cat_id' => $request->id])->delete();
        return $this->success(null, 'ADDON_DELETED');
    }
    
    /**
    * delete item Addon
    * @param Request $request
    * @return type
    */
    public function ajaxDeleteAddon(Request $request) {
        $ktcObj = KitchenItemsAddOnAssoc::where(['id' => $request->id])->delete();
        return $this->success(null, 'ADDON_DELETED');
    }
    
    /**
    * Edit kitchen item order in group
    * @param Request $request
    * @return type
    */
    public function ajaxEditItemOrder(Request $request) {
        if($request->group_id && $request->item_order) {
            $item_order = explode("||", $request->item_order);
            for($i = 0 ; $i < count($item_order) ; $i++) {
                $kio = KitchenItemOrder::where(['group_id' => $request->group_id, 'kitchen_item_id' => $item_order[$i]])->first();
                if(!empty($kio)) {
                    $kio->update(['seq_no' => $i]);
                } else {
                    KitchenItemOrder::create(['group_id' => $request->group_id, 'kitchen_item_id' => $item_order[$i], 'seq_no' => $i]);
                }
            }
            return $this->success(null, 'ITEM_ORDER_CHANGED');
        } else {
            return $this->error('ERROR');
        }
    }
    
    /**
    * save Kitchen Item Order while creating item
    * @param type $item_id
    * @param type $groups
    */
    public function saveKitchenItemOrder($item_id, $groups) {
        $input['kitchen_item_id'] = $item_id;
        $groups = explode(',', $groups);
        foreach($groups as $group) {
            $kIO = KitchenItemOrder::where(['kitchen_item_id' => $item_id, 'group_id' => $group])->first();
            if(empty($kIO)) {
                $input['group_id'] = $group;
                $input['seq_no'] = 0;
                KitchenItemOrder::create($input);
            }
        }
    }
    
    /**
     * Change add on category status
     * @return type
     */
    public function ajaxChangeAddonCatStatus($id) {
        $addOnCat = kitchenAddOnCategory::find($id);
        if($addOnCat) {
            if ($addOnCat->status == 'Active') {
                $addOnCat->status = 'Inactive';
            } else {
                $addOnCat->status = 'Active';
            }
            $addOnCat->save();
            $msg = "ADDON_CAT_STATUS_CHANGED";
        } else {
            $msg = "NO_RECORD_FOUND";
        }
        
        return $this->success(null, $msg);
    }
    
    /**
     * Bulk upload kitchen items
     * @param Request $request
     */
    public function bulkUpload(Request $request) {
        if(Auth::guard('chef')->check()) {
            $this->logged_in_user_id = Auth::guard('chef')->user()->id;
        }
        $kitchen = Kitchens::select('id')->where('chef_id', $this->logged_in_user_id)->first();
        $groups = Groups::select('id')->where(['chef_id' => $this->logged_in_user_id])->get();
        $hasItems = 0;
        
        if(!empty($groups) && count($groups) > 0) {
            $hasItems = 1;
        }
        return view('chef.kitchen-menu.bulk_upload', ['pageMeta' => $this->pageMeta, 'hasItems' => $hasItems,
            'kitchen_id' => $kitchen->id, 'chef_id' => $this->logged_in_user_id]);
    }
    
    /**
     * Bulk upload kitchen items
     * @param Request $request
     */
    public function bulkUploadSave(Request $request) {
        DB::beginTransaction();
        try {
        
        if ($request->file('shipment_sheet') === null) {
            return redirect()->back()->withErrors(['Please upload an excel file'])->withInput();
        }
        
        $uploadFileExt = $request->file('shipment_sheet')->getClientOriginalExtension();
        if (!in_array($uploadFileExt, ['xlsx', 'XLSX', 'xls', 'XLS'])) {
            return redirect()->back()->withErrors(['Please upload file having extension: xlsx, XLSX, xls, XLS'])->withInput();
        }
        
        if(Auth::guard('chef')->check()) {
            $this->logged_in_user_id = Auth::guard('chef')->user()->id;
        }
        $kitchen = Kitchens::select('id')->where('chef_id', $this->logged_in_user_id)->first();
        
        $response = $this->kitchenItemsBulkUpload($this->logged_in_user_id, $kitchen->id, $request);
        
        if(!empty($response['error'])) {
            $logtext = $response['error'];
            $notification = array(
                'message' => $logtext, 
                'alert-type' => 'warning'
            );
        } else {
            DB::commit();
            $logtext = $response['noOfItemsAdded']. " Items added from ".$response['noOfItemsGiven'];
            $actionName = "Bulk upload kitchen itms, $logtext";
            $notification = array(
                'message' => $logtext, 
                'alert-type' => 'success'
            );

            /**Log activity**/
            $this->logactivity($kitchen->id, $actionName, $request->ip());
        }
        
        return redirect('/chef/kitchenmenu/bulkupload')->with($notification);
        }  catch (Exception $ex) {
            DB::rollback();
            $notification = array(
                'message' => 'Somthing went wrong, please try again', 
                'alert-type' => 'warning'
            );
            return redirect('/chef/kitchenmenu/bulkupload')->with($notification);
        }
    }
    
    public function downloadKitchenMenuExcel() {
        if(Auth::guard('chef')->check()) {
            $this->logged_in_user_id = Auth::guard('chef')->user()->id;
        }
        $kitchen = Kitchens::select('id')->where('chef_id', $this->logged_in_user_id)->first();
        return $this->downloadKitchenMenu($this->logged_in_user_id, $kitchen->id);
    }
}