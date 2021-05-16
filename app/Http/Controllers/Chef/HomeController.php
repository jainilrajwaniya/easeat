<?php

namespace App\Http\Controllers\Chef;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function __construct() {
        $this->pageMeta['pageName'] = "Dashboard";
        $this->pageMeta['pageDes'] = "Get your analytics here!!";
        $this->pageMeta['breadCrumbs'] = array("Home" => "/", "Dashboard" => "");
    }
    
    public function index() {
         return view('chef.home.index', ['pageMeta' => $this->pageMeta]);
    }
}
