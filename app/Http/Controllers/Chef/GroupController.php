<?php

namespace App\Http\Controllers\Chef;

use App\Http\Controllers\Controller;
use App\Models\Groups;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Http\Helpers\ResponseTrait;
use App\Http\Helpers\CommonTrait;
use Illuminate\Routing\UrlGenerator;
use Yajra\DataTables\Facades\DataTables;

class GroupController extends Controller
{
    use ResponseTrait, CommonTrait;
    public function __construct(UrlGenerator $url) {
        $this->url = $url;
        $this->pageMeta['pageName'] = "Group Management";
        $this->pageMeta['pageDes'] = "Manage Groups here!!";
        $this->pageMeta['breadCrumbs'] = array("Home" => $this->url->to('/chef/'), "Groups Management" => "");
    }
    
    /**
     * Category page
     * @return type
     */
    public function index() {
        return view('chef.group.index', ['pageMeta' => $this->pageMeta]);
    }
    
    /**
     * Category ajax listing
     * @param \App\Http\Controllers\Chef\Request $request
     * @return type
     */
     public function ajaxGetGroupList() {
        if(Auth::guard('chef')->check()) {
            $this->logged_in_user_id = Auth::guard('chef')->user()->id;
        }
        $groupsList = Groups::select(['id','group_name', 'status', 'seq_no'])
                 ->where(['chef_id' => $this->logged_in_user_id])->orderBy('seq_no', 'ASC')->get();
         // Using the Engine Factory
        return Datatables::of($groupsList)
            ->make(true);
     }
        
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function ajaxEditGroup(Request $request) {    
        if(Auth::guard('chef')->check()) {
            $this->logged_in_user_id = Auth::guard('chef')->user()->id;
        }
        
        $input['group_name'] = $request->group_name;
        $input['seq_no'] = $request->seq_no;
        if(isset($request->id) && $request->id > 0) {
            $input['chef_id'] = $this->logged_in_user_id;
            $input['updated_by'] = $this->logged_in_user_id;
            $group = Groups::whereId($request->id)->update($input);
            $id = $request->id;
            $actionName = 'edit/Group';
            $msg = 'GROUP_EDITED';
        } else {
            $input['chef_id'] = $this->logged_in_user_id;
            $input['created_by'] = $this->logged_in_user_id;
            $group = Groups::create($input);
            $id = $group->id;
            $actionName = 'Add/Group';
            $msg = 'GROUP_CREATED';
        }

        /**Log activity**/
        $this->logactivity($id, $actionName, $request->ip());
        return $this->success(null, $msg);
    }
    
    
    public function changeCategoryStatus(Request $request, $id) {
        $category = Categories::findOrFail($id);
        if ($category->status == 'Active') {
            $category->status = 'Inactive';
        } else {
            $category->status = 'Active';
        }
        $category->save();

        return redirect('/admin/category/listing');
    }
    
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function ajaxEditGroupOrder(Request $request) {    
        if(Auth::guard('chef')->check()) {
            $this->logged_in_user_id = Auth::guard('chef')->user()->id;
        }
        $group_order_arr = explode("||", $request->group_order);
        for($i = 0 ; $i < count($group_order_arr) ; $i++) {
            $group = Groups::where('id', $group_order_arr[$i])->update(['seq_no' => $i]);
        }
        $actionName = 'Add/Group';
        $msg = 'GROUP_ORDER_CHANGED';
        
        /**Log activity**/
        $this->logactivity($this->logged_in_user_id, $actionName, $request->ip());
        return $this->success(null, $msg);
    }
}