<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Routing\UrlGenerator;
class HomeController extends Controller
{
    public function __construct(UrlGenerator $url) {
        $this->url = $url;
        $this->pageMeta['pageName'] = "Dashboard";
        $this->pageMeta['pageDes'] = "Get your analytics here!!";
        $this->pageMeta['breadCrumbs'] = array("Home" => $this->url->to('/admin/'), "Dashboard" => "");
    }
    
    public function index() {
         return view('admin.home.index', ['pageMeta' => $this->pageMeta]);
    }
}
