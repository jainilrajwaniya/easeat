<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WalletTransactions;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Illuminate\Http\Request;
use App\Http\Helpers\CommonTrait;
use App\Http\Helpers\ResponseTrait;
use Illuminate\Routing\UrlGenerator;
use JsValidator;
use Validator;
use Auth;

class WalletController extends Controller
{
    use CommonTrait, ResponseTrait;

    protected $addValidationRules = [
                                        'user_id'  => 'required|numeric',
                                        'amount'  => 'required|numeric',
                                        'description'  => 'required',
                                    ];

    public function __construct(UrlGenerator $url) {
        $this->url = $url;
        $this->pageMeta['pageName'] = "Wallet Management";
        $this->pageMeta['pageDes'] = "Manage Wallet here!!";
        $this->pageMeta['breadCrumbs'] = array("Home" => $this->url->to('/admin/'), "Wallet" => "");
    }
    
    
    /**
     * log activity listing page
     * @return type
     */
    public function index() {
        $users = User::all();
        $validator = JsValidator::make($this->addValidationRules,[],[],'#addEditForm');
        return view('admin.wallet.index', ['pageMeta' => $this->pageMeta, 'users' => $users, 'validator' => $validator]);
    }
    
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function ajaxGetWalletList(Request $request) {
        $wallet = WalletTransactions::where('amount', '>', '0')->select(['wallet_transactions.id', 'wallet_transactions.amount', 'wallet_transactions.description','wallet_transactions.transaction_type','wallet_transactions.type','wallet_transactions.order_id', DB::raw('IF(wallet_transactions.guest_user_id > 0,wallet_transactions.guest_user_id,0) AS guest_user_id'), DB::raw('IF(wallet_transactions.user_id > 0,wallet_transactions.user_id,0) AS user_id'), DB::raw('DATE_FORMAT(wallet_transactions.created_at, "%m/%d/%Y %h:%i") as created_date')]);

        // Using the Engine Factory
        return Datatables::of($wallet)
            ->make(true);
    }

    /**
     * Wallet Add Edit page
     * @return type
     */
    public function editWallet($id = 0) {
        $this->pageMeta['breadCrumbs'] = array("Home" => $this->url->to('/admin/'), "Promocode List" => $this->url->to("/admin/wallet/listing"), 'Add / Edit Wallet' => '');
        
        $validator = JsValidator::make($this->addValidationRules,[],[],'#addEditForm');
        $users = User::all();
        return view('admin.wallet.edit', ['pageMeta' => $this->pageMeta, 'adminRolesArr' => config('dbFields.ADMIN_ROLES'), 'validator' => $validator, 'users' => $users]);
    }
    
    
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function saveWallet(Request $request) { 
        $validation = Validator::make($request->all(), $this->addValidationRules);
        
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        } else {
            // echo "<pre>";print_r($request->all());exit;
            $input['user_id'] = $request->user_id;
            $input['amount'] = $request->amount;
            $input['description'] = $request->description;
            $input['transaction_type'] = 'credit';
            $input['type'] = 'other';
            if(Auth::guard('admin')->check()) {
                $this->logged_in_user_id = Auth::guard('admin')->user()->id;
            }
            
            $input['created_by'] = $this->logged_in_user_id;
            $promo = WalletTransactions::create($input);
            $id = $promo->id;
            
            $user = User::where('id', $request->user_id)->first();
            $user->wallet = $user->wallet + $request->amount;
            $user->save();
            
//            $balance = $request->amount;
//            $wallet = Wallet::where('user_id', $request->user_id)->first();
//            if(!empty($wallet)){
//                $balance = $wallet->balance_amount + $request->amount;
//            }
//            Wallet::updateOrCreate(
//                ['user_id' => $request->user_id],
//                ['balance_amount' => $balance]
//            );
            
            $actionName = 'Add/Wallet';
            $notification = array(
                'message' => 'Wallet added successfully', 
                'alert-type' => 'success'
            );
            
            /**Log activity**/
            $this->logactivity($id, $actionName, $request->ip());
            return redirect('/admin/wallet/listing')->with($notification);
        }
    }
}
