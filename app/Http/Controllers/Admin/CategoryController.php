<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Http\Helpers\ResponseTrait;
use App\Http\Helpers\CommonTrait;
use Illuminate\Routing\UrlGenerator;

class CategoryController extends Controller
{
    use ResponseTrait, CommonTrait;
    public function __construct(UrlGenerator $url) {
        $this->url = $url;
        $this->pageMeta['pageName'] = "Category Management";
        $this->pageMeta['pageDes'] = "Manage Categories here!!";
        $this->pageMeta['breadCrumbs'] = array("Home" => $this->url->to('/admin/'), "Category Management" => "");
    }
    
    /**
     * Category page
     * @return type
     */
    public function index() {
        $categoriesList = Categories::select(['id','category_name', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'])->get();
        return view('admin.category.index', ['pageMeta' => $this->pageMeta, 'categories' => $categoriesList]);
    }
    
    /**
     * Category ajax listing
     * @param \App\Http\Controllers\Admin\Request $request
     * @return type
     */
    // public function ajaxGetAdminList() {
    //     $adminList = Admin::select(['id','name', 'email', 'profile_pic', 'role', 'created_at']);
    //     return response()->json(['data' => $adminList->get()]);
    // }
    
    
    
    /**
     * Category Add Edit page
     * @return type
     */
    public function editCategory($id = 0) {
        $this->pageMeta['breadCrumbs'] = array("Home" => $this->url->to('/admin/'), "Category List" => $this->url->to('/admin/category/listing'), 'Add / Edit Category' => '');
        $categoryArr = [];
        if($id > 0) {
            $categoryObj = Categories::find($id);
            if(!isset($categoryObj->id)) {
                return redirect('/category/listing');
            }
            $categoryArr = $categoryObj->toArray();
        }
        return view('admin.category.edit', ['pageMeta' => $this->pageMeta, 'categoryArr' => $categoryArr, 'adminRolesArr' => config('dbFields.ADMIN_ROLES')]);
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
    public function saveCategory(Request $request) {    
        if(isset($request->id) && $request->id > 0) {
            $validation = Validator::make($request->all(), config('adminValidations.EDIT_CATEGORY'));
        } else {
            $validation = Validator::make($request->all(), config('adminValidations.ADD_CATEGORY'));
        }
        
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        }
        
        if(Auth::guard('admin')->check()) {
            $userId = Auth::guard('admin')->user()->id;
        }
        
        $input['category_name'] = $request->category_name;
        if(isset($request->id) && $request->id > 0) {
            $input['updated_by'] = $userId;
            Categories::whereId($request->id)->update($input);
            $id = $request->id;
            $actionName = 'Edit/Category';
        } else {
            $input['created_by'] = $userId;
            $category = Categories::create($input);
            $id = $category->id;
            $actionName = 'Add/Category';
        }

        /**Log activity**/
        $this->logactivity($id, $actionName, $request->ip());
        return redirect('/admin/category/listing');
    }
}