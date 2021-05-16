<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ratings;
use Illuminate\Http\Request;
use App\Http\Helpers\ResponseTrait;
use App\Http\Helpers\CommonTrait;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Illuminate\Routing\UrlGenerator;

class RatingController extends Controller
{
    use ResponseTrait, CommonTrait;
    public function __construct(UrlGenerator $url) {
        $this->url = $url;
        $this->pageMeta['pageName'] = "Rating Management";
        $this->pageMeta['pageDes'] = "Manage Rating here!!";
        $this->pageMeta['breadCrumbs'] = array("Home" => $this->url->to('/admin'), "Rating Management" => "");
    }
    
    /**
     * Rating page
     * @return type
     */
    public function index() {
         return view('admin.rating.index', ['pageMeta' => $this->pageMeta]);
    }
    
    /**
     *  ajax listing
     * @param \App\Http\Controllers\Admin\Request $request
     * @return type
     */
    public function ajaxGetRatingList() {
        $list = Ratings::getRatings();
        
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
            ->filterColumn('chef_name', function($query, $keyword) {
                $query->whereRaw('LOWER(chefs.name) LIKE ?', ["%{$keyword}%"]);
            })
            ->filterColumn('chef_email', function($query, $keyword) {
                $query->whereRaw('LOWER(chefs.email) LIKE ?', ["%{$keyword}%"]);
            })
            ->filterColumn('chef_phno', function($query, $keyword) {
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
     * change rating status
     * @param type $id
     * @return type
     */
    public function changeRatingStatus($id) {
        $rating = Ratings::find($id);
        if($rating) {
            if ($rating->status == 'Active') {
                $rating->status = 'Inactive';
            } else {
                $rating->status = 'Active';
            }
            $rating->save();
            $msg = "RATING_STATUS_CHANGED";
        } else {
            $msg = "NO_RECORD_FOUND";
        }
        
        return $this->success(null, $msg);
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
