<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Illuminate\Http\Request;
use App\Http\Helpers\CommonTrait;
use App\Http\Helpers\ResponseTrait;
use Illuminate\Routing\UrlGenerator;

class UserController extends Controller
{
    use CommonTrait, ResponseTrait;
    public function __construct(UrlGenerator $url) {
        $this->url = $url;
        $this->pageMeta['pageName'] = "User Management";
        $this->pageMeta['pageDes'] = "Manage users here!!";
        $this->pageMeta['breadCrumbs'] = array("Home" => $this->url->to('/admin/'), "User Management" => "");
    }
    
    
    /**
     * user listing page
     * @return type
     */
    public function index() {
         return view('admin.user.index', ['pageMeta' => $this->pageMeta]);
    }
    
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function ajaxGetUserList(Request $request) {
        $users = User::select(['id','name', 'email', 'phone_number','status', 'created_at']);

        // Using the Engine Factory
        return Datatables::of($users)
            ->make(true);
    }
}
