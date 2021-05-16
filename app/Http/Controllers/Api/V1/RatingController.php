<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Models\Ratings; 
use Auth; 
use Validator;
use App\Http\Helpers\CommonTrait;
use App\Http\Helpers\ResponseTrait;
use Exception;
use DB;
use Carbon\Carbon;

class RatingController extends Controller 
{
    use CommonTrait, ResponseTrait;

    public function __construct() {
        $this->currentUser = Auth::guard('api')->user();
    }
    
    /**
     * save kitchen rating
     * @param Request $request
     */
    public function saveKitchenRating(Request $request) {
        $validation = Validator::make($request->all(), ['rating' => "required|in:1,1.5,2,2.3,3,3.5,4,4.5,5", 'description' => 'required','kitchen_id' => 'required|integer|exists:kitchens,id']);
        if ($validation->fails()) {
            return $this->validationError($validation);
        }
        DB::beginTransaction();
        try {
            $input['user_id'] = $this->currentUser->id;
            $input['kitchen_id'] = $request->kitchen_id;
            $input['rating'] = $request->rating;
            $input['description'] = $request->description;
            Ratings::create($input);
            $msg = 'RATING_SAVED_SUCCESSFULLY';
            DB::commit();
            return $this->success(null, $msg);
        } catch (Exception $ex) {
            DB::rollback();
            return $this->error('ERROR');
        }
    }
    
    /**
     * get kitchen's rating
     * @param Request $request
     * @return type
     */
    public function getKitchenRatings(Request $request) {
        $validation = Validator::make($request->all(), ['kitchen_id' => 'required|integer|exists:kitchens,id']);
        if ($validation->fails()) {
            return $this->validationError($validation);
        }
        try {
            $kitchenRatings = Ratings::select('id', 'rating', 'description' ,DB::raw('created_at AS posted_date'))
                            ->where(['kitchen_id' => $request->kitchen_id, 'status' => 'Active'])->get()->toArray();
            return $this->success(array('list' => $kitchenRatings));
        } catch (Exception $ex) {
            return $this->error('ERROR');
        }
    }


}