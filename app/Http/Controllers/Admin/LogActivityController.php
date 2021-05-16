<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogActivity;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Illuminate\Http\Request;
use App\Http\Helpers\CommonTrait;
use App\Http\Helpers\ResponseTrait;
use Illuminate\Routing\UrlGenerator;

class LogActivityController extends Controller
{
    use CommonTrait, ResponseTrait;
    public function __construct(UrlGenerator $url) {
        $this->url = $url;
        $this->pageMeta['pageName'] = "Log Activities";
        $this->pageMeta['pageDes'] = "Manage log activities here!!";
        $this->pageMeta['breadCrumbs'] = array("Home" => $this->url->to('/admin/'), "Log Activities" => "");
    }
    
    
    /**
     * log activity listing page
     * @return type
     */
    public function index() {
         return view('admin.log-activity.index', ['pageMeta' => $this->pageMeta]);
    }
    
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function ajaxGetLogActivityList(Request $request) {
        $log_activity = LogActivity::select(['log_activities.id', 'log_activities.logger_id', 'log_activities.message', 'log_activities.action', 'log_activities.type', 'log_activities.ip_address', DB::raw('DATE_FORMAT(log_activities.created_at, "%m/%d/%Y") as created_date'),  'admins.name'])
            ->join('admins', function($join)
            {
                $join->on('log_activities.logger_id', '=', 'admins.id')
                     ->where('log_activities.type', '=', 'Admin');
            });

        // Using the Engine Factory
        return Datatables::of($log_activity)
            ->make(true);
    }
}
