<?php

namespace App\Http\Controllers\Chef;

use App\Http\Controllers\Controller;
use App\Models\Ratings;
use App\Models\Kitchens;
use Illuminate\Http\Request;
use App\Http\Helpers\ResponseTrait;
use App\Http\Helpers\CommonTrait;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Illuminate\Routing\UrlGenerator;
use Auth;

class RatingController extends Controller
{
    use ResponseTrait, CommonTrait;
    public function __construct(UrlGenerator $url) {
        $this->url = $url;
        $this->pageMeta['pageName'] = "Rating Management";
        $this->pageMeta['pageDes'] = "Manage Rating here!!";
        $this->pageMeta['breadCrumbs'] = array("Home" => $this->url->to('/chef'), "Rating Management" => "");
    }
    
    /**
     * Rating page
     * @return type
     */
    public function index() {
         return view('chef.rating.index', ['pageMeta' => $this->pageMeta]);
    }
    
    /**
     *  ajax listing
     * @param \App\Http\Controllers\Chef\Request $request
     * @return type
     */
    public function ajaxGetRatingList() {
        if(Auth::guard('chef')->check()) {
            $this->logged_in_user_id = Auth::guard('chef')->user()->id;
        }
        $kitchen = Kitchens::where('chef_id', $this->logged_in_user_id)->first();
        $list = Ratings::getRatings($kitchen->id);
        
        // Using the Engine Factory
        return Datatables::of($list)
                //'user_email', 'user_phno', 'chef_name', 'chef_email', 'chef_phno', 'rating', 'status', 'created_at'
            //->escapeColumns([])
            ->filterColumn('user_name', function($query, $keyword) {
                $query->whereRaw('LOWER(users.name) LIKE ?', ["%{$keyword}%"]);
            })
            ->filterColumn('user_email', function($query, $keyword) {
                $query->whereRaw('LOWER(users.email) LIKE ?', ["%{$keyword}%"]);
            })
            ->filterColumn('user_phno', function($query, $keyword) {
                $query->whereRaw('LOWER(users.phone_number) LIKE ?', ["%{$keyword}%"]);
            })            
            ->filterColumn('rating', function($query, $keyword) {
                $query->whereRaw('LOWER(ratings.rating) LIKE ?', ["%{$keyword}%"]);
            })
            ->filterColumn('status', function($query, $keyword) {
                $query->whereRaw('LOWER(ratings.status) LIKE ?', ["%{$keyword}%"]);
            })
            ->filterColumn('created_at', function($query, $keyword) {
                $query->whereRaw('LOWER(ratings.created_at) LIKE ?', ["%{$keyword}%"]);
            })
            ->make(true);
    }
    
    
    /**
     * get all rating data on id
     * @param type $id
     * @return type
     */
    public function getRatingData($id) {
        $rating = Ratings::find($id);
        if($rating) {
            return $this->success($rating->toArray());
        } else {
            return $this->success(null, 'NO_RECORD_FOUND');
        }
    }
}
