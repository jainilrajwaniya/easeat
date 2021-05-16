<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CuisineTypes;
use Illuminate\Http\Request;
use Auth;
use Validator;
use App\Http\Helpers\ResponseTrait;
use App\Http\Helpers\CommonTrait;
use Illuminate\Routing\UrlGenerator;

class CuisineController extends Controller
{
    use ResponseTrait, CommonTrait;
    public function __construct(UrlGenerator $url) {
        $this->url = $url;
        $this->pageMeta['pageName'] = "Cuisine Management";
        $this->pageMeta['pageDes'] = "Manage Cuisine here!!";
        $this->pageMeta['breadCrumbs'] = array("Home" => $this->url->to('/admin/'), "Cuisine Management" => "");
    }
    
    /**
     * Cuisine page
     * @return type
     */
    public function index() {
        $cuisineList = CuisineTypes::select(['id','cuisine_type_name', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'])->get();
        return view('admin.cuisine.index', ['pageMeta' => $this->pageMeta, 'cuisine' => $cuisineList]);
    }
    
    /**
     * Sub admin ajax listing
     * @param \App\Http\Controllers\Admin\Request $request
     * @return type
     */
    // public function ajaxGetAdminList() {
    //     $adminList = Admin::select(['id','name', 'email', 'profile_pic', 'role', 'created_at']);
    //     return response()->json(['data' => $adminList->get()]);
    // }
    
    
    
    /**
     * Cuisine Add Edit page
     * @return type
     */
    public function editCuisine($id = 0) {
        $this->pageMeta['breadCrumbs'] = array("Home" => $this->url->to('/admin/'), "Cuisine List" => $this->url->to('/admin/cuisine/listing'), 'Add / Edit Cuisine' => '');
        $cuisineArr = [];
        if($id > 0) {
            $cuisineObj = CuisineTypes::find($id);
            if(!isset($cuisineObj->id)) {
                return redirect('/admin/cuisine/listing');
            }
            $cuisineArr = $cuisineObj->toArray();
        }
        return view('admin.cuisine.edit', ['pageMeta' => $this->pageMeta, 'cuisineArr' => $cuisineArr, 'adminRolesArr' => config('dbFields.ADMIN_ROLES')]);
    }
    
    public function changeCuisineStatus(Request $request, $id) {
        $cuisine = CuisineTypes::findOrFail($id);
        if ($cuisine->status == 'Active') {
            $cuisine->status = 'Inactive';
        } else {
            $cuisine->status = 'Active';
        }
        $cuisine->save();

        return redirect('/admin/cuisine/listing');
    }
    
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function saveCuisine(Request $request) {    
        if(isset($request->id) && $request->id > 0) {
            $validation = Validator::make($request->all(), config('adminValidations.EDIT_CUISINE'));
        } else {
            $validation = Validator::make($request->all(), config('adminValidations.ADD_CUISINE'));
        }
        
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        } else {
            $userId = 0;
            if(Auth::guard('admin')->check()) {
                $userId = Auth::guard('admin')->user()->id;
            }
            $input['cuisine_type_name'] = $request->cuisine_type_name;
            if(isset($request->id) && $request->id > 0) {
                $input['updated_by'] = $userId;
                CuisineTypes::whereId($request->id)->update($input);
                $id = $request->id;
                $actionName = 'Edit/Cuisine';
            } else {
                $input['created_by'] = $userId;
                $cuisine = CuisineTypes::create($input);
                $id = $cuisine->id;
                $actionName = 'Add/Cuisine';
            }
            
            /**Log activity**/
            $this->logactivity($id, $actionName, $request->ip());
            return redirect('/admin/cuisine/listing');
        }
    }
}