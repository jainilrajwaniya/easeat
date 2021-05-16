@extends('chef.layout.master')

@section('title', $pageMeta['pageName'])

@section('pageCss')
<style type="text/css">
    #map {
        height: 100%;
    }
</style>
@endsection

@section('content')
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Map</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <!--<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>-->
                    </div>
                </div>
                <div class="box-body">
                    <div class="" id="map" style="width: 98%; height: 500px; border: 1px solid #a0a0a0; margin:1%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6" class="col-xs-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Invoice Breakup</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <!--<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>-->
                    </div>
                </div>
                <div class="box-body">
                    <b>Promo code : </b>{{($order['promo_code'] ? $order['promo_code'] : '-NA-')}} <br>
                    <b>Company discount : </b>{{($order['promo_code'] ? $order['company_discount'].'%' : '-NA-')}}<br>
                    <b>Item total : </b>{{$order['total']}}<br>
                    <b>Discount : </b>{{$order['discount']}}<br>
                    <!--<b>Taxes : </b>{{$order['tax']}}<br>-->
                    <b>Delivery fee : </b>{{$order['delivery_fee']}}<br>
                    <b>Grand total : </b>{{$order['grand_total']}}<br>
                </div>
            </div>
        </div>
        <div class="col-lg-6" class="col-xs-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Customer Details</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <!--<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>-->
                    </div>
                </div>
                <div class="box-body">
                    <b>User Id : </b>{{$order['user_id']}}<br>
                    <b>Guest User Id : </b>{{$order['guest_user_id']}}<br>
                    <b>Contact Person No : </b>{{$order['contact_person_no']}}<br>
                    <b>Delivery Address : </b>{{$order['delivery_address']}}<br>
                    <b>Delivery Latitude : </b>{{$order['delivery_latitude']}}<br>
                    <b>Delivery Longitude : </b>{{$order['delivery_longitude']}}<br>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12" class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Order Details</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <!--<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>-->
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class='table no-margin'>
                            <thead>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Varient Name</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Add Ons</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order['items'] as $item)
                                <tr>
                                    <td>{{$item['item_name']}}</td>
                                    <td>{{$item['varient_name']}}</td>
                                    <td>{{$item['quantity']}}</td>
                                    <td>{{$item['price']}}</td>
                                    @if(isset($item['addons']))
                                    <td>
                                        @foreach($item['addons'] as $addon)
                                        {{$addon['add_on_name']}},
                                        @endforeach
                                    </td>
                                    @else
                                    <td>--</td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
@endsection
@section('pageJavascript')
<script>
    var map;
    // Initialize and add the map
    function initMap() {
        // Get both customer and kitchen address
         var locations = [
            ["Customer Address - {{$order['delivery_address']}}", {{$order['delivery_latitude']}}, {{$order['delivery_longitude']}}],
            ["Kitchen Address - {{$order['kitchen_address']}}", {{$order['kitchen_lat']}}, {{$order['kitchen_long']}}]
        ];
        // Center map
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 12,
            center: new google.maps.LatLng({{$order['delivery_latitude']}}, {{$order['delivery_longitude']}}),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        
        var infowindow = new google.maps.InfoWindow();

        var marker, i;

        for (i = 0; i < locations.length; i++) {  
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                map: map
            });

            google.maps.event.addListener(marker, 'click', (function(marker, i) {
              return function() {
                  infowindow.setContent(locations[i][0]);
                  infowindow.open(map, marker);
              }
            })(marker, i));
        }
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('app.GOOGLEMAPKEY') }}&callback=initMap"></script>
@endsection