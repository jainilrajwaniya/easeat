@extends('admin.layout.master')

@section('title', $pageMeta['pageName'])

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{isset($promoArr['id']) ? 'Edit' : 'Add'}} Promo Code</h3>
    </div>
    <div class="panel-body">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form id='addEditPromoForm' method='POST'  action='{{url('/admin/promocode/save')}}' class="form-horizontal"  enctype="multipart/form-data">
            {{ csrf_field() }}
            <input id="id" type="hidden" class="form-control" name="id" value="{{isset($promoArr['id']) ? $promoArr['id'] : ''}}" autofocus>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="promo_code">Promo Code</label>
                <div class="col-sm-5">
                    <input id="promo_code" type="text" placeholder="Promo Code" class="form-control" name="promo_code" value="{{isset($promoArr['promo_code']) ? $promoArr['promo_code'] : old('promo_code')}}" autofocus>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="password1">Profile Pic</label>
                <div class="col-sm-5">
                    <input type="file" id="image" name="image" class="form-control" value="">
                    @if(isset($promoArr["id"]))
                        <img src="{{ config('aws.aws_s3_url').'/uploads/promo-code/'.$promoArr["id"].'/thumbnails/200x200/'.$promoArr["image"].'?'.time() }}" border="0" width="150" class="img-rounded" align="center" /> 
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="discount_percentage">Discount Percentage</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="discount_percentage" name="discount_percentage" placeholder="Discount Percentage" value="{{isset($promoArr['discount_percentage']) ? $promoArr['discount_percentage'] : old('discount_percentage')}}"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="no_of_usage">No of Usage</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="no_of_usage" name="no_of_usage" placeholder="No of Usage" value="{{isset($promoArr['no_of_usage']) ? $promoArr['no_of_usage'] : old('no_of_usage')}}"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="no_of_usage">Min Order Value</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="min_order_value" name="min_order_value" placeholder="Min Order Value" value="{{isset($promoArr['min_order_value']) ? $promoArr['min_order_value'] : old('min_order_value')}}"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="no_of_usage">Max Discount Amount </label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="max_dis_amt" name="max_dis_amt" placeholder="Max Discount Amount" value="{{isset($promoArr['max_dis_amt']) ? $promoArr['max_dis_amt'] : old('max_dis_amt')}}"/>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label" for="limitation">Limitation</label>
                <div class="col-sm-5">
                    <select id="limitation" name='limitation' class="form-control" >
                      <option value='NTO' {{isset($promoArr['limitation']) && $promoArr['limitation'] == 'NTO' ? 'selected' : ''}}>NTO</option>
                      <option value='NTPC' {{isset($promoArr['limitation']) && $promoArr['limitation'] == 'NTPC' ? 'selected' : ''}}>NTPC</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label" for="publish_at">Starts On</label>
                <div class="col-sm-5">
                    <div class='input-group date' id='datetimepicker1'>
                        <input type="text" class="form-control" id="publish_at" name="publish_at" placeholder="Starts On" value="{{isset($promoArr['publish_at']) ? $promoArr['publish_at'] : old('publish_at')}}"/>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                    
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label" for="expire_at">Expire On</label>
                <div class="col-sm-5">
                    <div class='input-group date' id='datetimepicker1'>
                        <input type="text" class="form-control" id="expire_at" name="expire_at" placeholder="Expire On" value="{{isset($promoArr['expire_at']) ? $promoArr['expire_at'] : old('expire_at')}}"/>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                    
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-sm-9 col-sm-offset-4">
                    <button type="submit" class="btn btn-primary" name="signup" value="Sign up">{{isset($promoArr['id']) ? 'Edit' : 'Add'}}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('pageJavascript')
<!-- Javascript Requirements -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<!-- <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script> -->

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script> </head>


<!-- Laravel Javascript Validation -->
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
<script>
    @if(Session::has('message'))
      var type = "{{ Session::get('alert-type', 'info') }}";
      switch(type){
          case 'info':
              toastr.info("{{ Session::get('message') }}");
              break;
          
          case 'warning':
              toastr.warning("{{ Session::get('message') }}");
              break;

          case 'success':
              toastr.success("{{ Session::get('message') }}");
              break;

          case 'error':
              toastr.error("{{ Session::get('message') }}");
              break;
      }
    @endif

    $(document).ready(function () {
        $('#publish_at').datepicker({
            format: "mm/dd/yyyy"
        });
        $('#expire_at').datepicker({
            format: "mm/dd/yyyy"
        });
    });
  </script>
{!! $validator !!}
@endsection