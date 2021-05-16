<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Auth;
use App\Models\Messages;
use App\Http\Helpers\CommonTrait;
use App\Http\Helpers\ResponseTrait;
use Exception;

class SettingController extends Controller 
{
    use CommonTrait, ResponseTrait;

    public function __construct() {
//        $this->currentUser = Auth::guard('api')->user();
    }
    
    /**
    * Get all messages or on id
    * @param Request $request
    * @return type
    */
    public function getMessage(Request $request) {
        try {
            $lang = isset($request->lang) ? $request->lang : '';
            $whereField = "message";
            if($lang == 'ar') {
                $whereField = "message_ar";
            }
            if(isset($request->type) && $request->type != '') {
                $msg = Messages::select([$whereField])->where(['type' => $request->type])->first();
            } else {
                $msg = Messages::select([$whereField])->get();
            }
            
            return $this->success($msg, "DATA");
        } catch (Exception $ex) {
            return $this->error('ERROR');
        }
    }
}