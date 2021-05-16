<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use App\Models\Kitchens;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Illuminate\Http\Request;
use App\Http\Helpers\CommonTrait;
use App\Http\Helpers\ResponseTrait;
use Illuminate\Routing\UrlGenerator;

class OrderController extends Controller
{
    use CommonTrait, ResponseTrait;
    public function __construct(UrlGenerator $url) {
        $this->url = $url;
        $this->pageMeta['pageName'] = "Order management";
        $this->pageMeta['pageDes'] = "You orders here!!";
        $this->pageMeta['breadCrumbs'] = array("Home" => $this->url->to('/admin/'), "Orders" => "");
    }
    
    
    /**
     * log activity listing page
     * @return type
     */
    public function index() {
         return view('admin.orders.index', ['pageMeta' => $this->pageMeta]);
    }
    
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function ajaxGetOrdersList() {
        $orders = Orders::select(['orders.*', DB::raw('DATE_FORMAT(orders.created_at, "%d/%m/%Y %H:%i") as created_date')]);

        // Using the Engine Factory
        return Datatables::of($orders)
            ->make(true);
    }
    
    /**
     * Get order detail
     * @param Request $request
     * @return type
     */
    public function orderDetail(Request $request) {
        if(!$request->id) {
            return redirect('/admin/orders/listing');
        }
        $order = [];
        $orderArr = Orders::select(['orders.order_json'])->where(['id' => $request->id])->get()->toArray();
        if(!empty($orderArr) && isset($orderArr[0]['order_json'])) {
            $order = json_decode($orderArr[0]['order_json'], 1);
            $kitchen = Kitchens::where(['id' => $order['kitchen_id']])->first()->toArray();
            $order['kitchen_lat'] = $kitchen['latitude'];
            $order['kitchen_long'] = $kitchen['longitude'];
            $order['kitchen_address'] = $kitchen['address']." ".$kitchen['lane']." ".$kitchen['landmark'];
            
            return view('admin.orders.detail', ['pageMeta' => $this->pageMeta, 'order' => $order]);
        } else {
//            return redirect()->route('orders_listing');
            return redirect('/admin/orders/listing');
        }
    }
}
