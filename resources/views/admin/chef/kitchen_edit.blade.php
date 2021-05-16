@extends('admin.layout.master')
@section('title', $pageMeta['pageName'])
@section('pageCss')
  <link rel="stylesheet" href="{{url('/css/select2.min.css')}}">
@endsection
@section('content')
<div class="panel panel-default">
   @if(isset($kitchenArr['chef_id']))
   <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
         <li class=""><a href="{{url('/admin/chef/show_edit_form/'.$kitchenArr['chef_id'])}}"  aria-expanded="true">Personal Details</a></li>
         <li class="active"><a href="#"  aria-expanded="false">Kitchen</a></li>
         @if(isset($kitchenArr['id']))
         <li class=""><a href="{{url('/admin/chef/kitchen_image_form/'.$kitchenArr['id'])}}" aria-expanded="false">Images</a></li>
         @endif
      </ul>
   </div>
   @endif
   <div class="panel-heading">
      <h3 class="panel-title">{{isset($kitchenArr['chef_id']) ? 'Edit' : 'Add'}} Chef</h3>
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
      <form id='editKitchenForm' method='POST'  action='{{url('/admin/chef/kitchen/save')}}' class="form-horizontal" >
      {{ csrf_field() }}
      <input type="hidden" class="form-control" name="chef_id" value="{{isset($kitchenArr['chef_id']) ? $kitchenArr['chef_id'] : ''}}" autofocus>
      <input id="id" type="hidden" class="form-control" name="id" value="{{isset($kitchenArr['id']) ? $kitchenArr['id'] : ''}}" autofocus>
      <div class="form-group">
         <label class="col-sm-4 control-label" for="lane">Country</label>
         <div class="col-sm-5">
            <select id="country" name="country" class="form-control"  >
               <option value="" selected disabled>Select</option>
               @foreach($countries as $key => $country)
               <option value="{{$key}}" {{isset($kitchenArr['country']) && $kitchenArr['country'] == $key ? 'selected' : ''}}> {{$country}}</option>
               @endforeach
            </select>
         </div>
      </div>
      <div class="form-group">
         <label class="col-sm-4 control-label" for="state">Governate</label>
         <div class="col-sm-5">
            <select name="state" id="state" class="form-control" >
            </select>
         </div>
      </div>
      <div class="form-group">
         <label class="col-sm-4 control-label" for="area">Area</label>
         <div class="col-sm-5">
            <select name="area" id="area" class="form-control">
            </select>
         </div>
      </div>
      <div class="form-group">
         <label class="col-sm-4 control-label" for="lane">Address</label>
         <div class="col-sm-5">
            <input type="text" class="form-control" id="address" name="address" placeholder="Address" value="{{isset($kitchenArr['address']) ? $kitchenArr['address'] : old('lane')}}"/>
         </div>
      </div>
      <div class="form-group">
         <label class="col-sm-4 control-label" for="lane">Street / Block</label>
         <div class="col-sm-5">
            <input type="text" class="form-control" id="lane" name="lane" placeholder="Lane" value="{{isset($kitchenArr['lane']) ? $kitchenArr['lane'] : old('lane')}}"/>
         </div>
      </div>
      <div class="form-group">
         <label class="col-sm-4 control-label" for="landmark">Additional Details</label>
         <div class="col-sm-5">
            <input type="text" class="form-control" id="landmark" name="landmark" placeholder="Landmark" value="{{isset($kitchenArr['landmark']) ? $kitchenArr['landmark'] : old('landmark')}}"/>
         </div>
      </div>
      <div class="form-group">
         <label class="col-sm-4 control-label" for="latitude">Latitude</label>
         <div class="col-sm-5">
            <input type="text" class="form-control" id="latitude" name="latitude" placeholder="Latitude" value="{{isset($kitchenArr['latitude']) ? $kitchenArr['latitude'] : old('latitude')}}"/>
         </div>
      </div>
      <div class="form-group">
         <label class="col-sm-4 control-label" for="longitude">Longitude</label>
         <div class="col-sm-5">
            <input type="text" class="form-control" id="longitude" name="longitude" placeholder="Longitude" value="{{isset($kitchenArr['longitude']) ? $kitchenArr['longitude'] : old('longitude')}}"/>
         </div>
      </div>
<!--      <div class="form-group">
         <label class="col-sm-4 control-label" for="lane">Area</label>
         <div class="col-sm-5">
            <select name="area" id="area" class="form-control">
            </select>
         </div>
      </div>-->
      <div class="form-group">
         <label class="col-sm-4 control-label" for="password1">Delivery Type</label>
         <div class="col-sm-5">
            <select id="delivery_type" name='delivery_type' class="form-control" >
            <option value='PickUp' {{isset($kitchenArr['delivery_type']) && $kitchenArr['delivery_type'] == 'PickUp' ? 'selected' : ''}}>Pick Up</option>
            <option value='HomeDelivery' {{isset($kitchenArr['delivery_type']) && $kitchenArr['delivery_type'] == 'HomeDelivery' ? 'selected' : ''}}>Home Delivery</option>
            <option value='Both' {{isset($kitchenArr['delivery_type']) && $kitchenArr['delivery_type'] == 'Both' ? 'selected' : ''}}>Both</option>
            </select>
         </div>
      </div>
      <div class="form-group">
         <label class="col-sm-4 control-label" for="preorder">&nbsp;</label>
         <div class="col-sm-5 checkbox">
             <label class="checkbox-inline"><input type="checkbox" id="preorder" name="preorder" {{isset($kitchenArr['pre_order']) && $kitchenArr['pre_order'] == '1' ? 'checked' : ''}} value="1">Pre Order</label>
         </div>
      </div>
      <div class="form-group">
         <label class="col-sm-4 control-label" for="delivery_radius">Delivery Radius (In kms)</label>
         <div class="col-sm-5">
            <input type="text" class="form-control" id="delivery_radius" name="delivery_radius" placeholder="Delivery Radius in miles" value="{{isset($kitchenArr['delivery_radius']) ? $kitchenArr['delivery_radius'] : old('delivery_radius')}}"/>
         </div>
      </div>
      <div class="form-group">
         <label class="col-sm-4 control-label" for="delivery_fee">Delivery Fee</label>
         <div class="col-sm-5">
            <input type="text" class="form-control" id="delivery_fee" name="delivery_fee" placeholder="Delivery Fee" value="{{isset($kitchenArr['delivery_fee']) ? $kitchenArr['delivery_fee'] : old('delivery_fee')}}"/>
         </div>
      </div>
      <div class="form-group">
         <label class="col-sm-4 control-label" for="prep_time">Preparation Time (In Mins)</label>
         <div class="col-sm-5">
            <input type="text" class="form-control" id="prep_time" name="prep_time" placeholder="Preparation Time" value="{{isset($kitchenArr['prep_time']) ? $kitchenArr['prep_time'] : old('prep_time')}}"/>
         </div>
      </div>
      <div class="form-group">
         <label class="col-sm-4 control-label" for="per_person_cost">Per Person Cost</label>
         <div class="col-sm-5">
            <input type="text" class="form-control" id="per_person_cost" name="per_person_cost" placeholder="Per Person Cost" value="{{isset($kitchenArr['per_person_cost']) ? $kitchenArr['per_person_cost'] : old('per_person_cost')}}"/>
         </div>
      </div>
      <div class="form-group">
         <label class="col-sm-4 control-label" for="min_order_home_delivery">Min Order Home Delivery</label>
         <div class="col-sm-5">
            <input type="text" class="form-control" id="min_order_home_delivery" name="min_order_home_delivery" placeholder="Min Order Home Delivery" value="{{isset($kitchenArr['min_order_home_delivery']) ? $kitchenArr['min_order_home_delivery'] : old('min_order_home_delivery')}}"/>
         </div>
      </div>
      <div class="form-group">
         <label class="col-sm-4 control-label" for="cuisine_types">Cusine Type</label>
         <div class="col-sm-5">
            <select id="cuisine_types" name='cuisine_types[]' class="form-control" multiple>
            @foreach($cuisine_types as $cuisine)
            <option value='{{$cuisine->cuisine_type_name}}' @if(in_array($cuisine->cuisine_type_name, $kitchenArr['cuisine_types'])) selected @endif >{{$cuisine->cuisine_type_name}}</option>
            @endforeach
            </select>
         </div>
      </div>
      <div class="form-group">
         <label class="col-sm-4 control-label" for="categories">Categories</label>
         <div class="col-sm-5">
            <select id="categories" name='categories[]' class="form-control" multiple>
            @foreach($categories as $cat)
            <option value='{{$cat->category_name}}' @if(in_array($cat->category_name, $kitchenArr['categories'])) selected @endif>{{$cat->category_name}}</option>
            @endforeach
            </select>
         </div>
      </div>
      <div class="form-group">
         <label class="col-sm-4 control-label" for="pure_veg">Pure Veg</label>
         <div class="col-sm-5">
            <select id="pure_veg" name='pure_veg' class="form-control" >
            <option value='1' {{isset($kitchenArr['pure_veg']) && $kitchenArr['pure_veg'] == '1' ? 'selected' : ''}}>Yes</option>
            <option value='0' {{isset($kitchenArr['pure_veg']) && $kitchenArr['pure_veg'] == '0' ? 'selected' : ''}}>No</option>
            </select>
         </div>
      </div>
      <div class="form-group">
         <label class="col-sm-4 control-label" for="company_discount">Chef Discount (In %)</label>
         <div class="col-sm-5">
            <input type="text" class="form-control" id="company_discount" name="company_discount" placeholder="Company Discount" value="{{isset($kitchenArr['company_discount']) ? $kitchenArr['company_discount'] : old('company_discount')}}"/>
         </div>
      </div>
      <div class="form-group">
         <label class="col-sm-4 control-label" for="company_commission">Easeat Commission (In %)</label>
         <div class="col-sm-5">
            <input type="text" class="form-control" id="company_commission" name="company_commission" placeholder="Company Discount" value="{{isset($kitchenArr['company_commission']) ? $kitchenArr['company_commission'] : old('company_commission')}}"/>
         </div>
      </div>
      <div class="form-group">
         <div class="col-sm-9 col-sm-offset-4">
            <button type="submit" class="btn btn-primary" name="signup" value="Sign up">{{isset($kitchenArr['id']) ? 'Edit' : 'Add'}}</button>
         </div>
      </div>
      </form>
   </div>
</div>
@endsection
@section('pageJavascript')
<!-- Laravel Javascript Validation -->
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/select2.min.js')}}"></script>
<script>
    var state = 0;
    var city = 0;
   @if(isset($kitchenArr['state']))
    state = '{{ $kitchenArr['state'] }}';
   @endif
   @if(isset($kitchenArr['city']))
    city = '{{ $kitchenArr['city'] }}';
   @endif
//   @if(isset($kitchenArr['area']))
//    var area = '{{ $kitchenArr['area'] }}';
//   @endif

    $(document).ready(function() {
        $('#categories, #cuisine_types').select2();//initializing ddls
        $('.select2-selection__choice').css('background-color', '#3c8dbc');//set color for selected value
        $('.select2-selection__choice').css('border-color', '#3c8dbc');//set color for selected value
    });
    
    
   $('#country').change(function(){
   var countryID = $(this).val();    
   if(countryID){
       $('.spinner').show();
       $.ajax({
          type:"GET",
          url:"{{url('admin/region/get-state-list')}}?country_id="+countryID,
          success:function(res){               
           if(res){
               $("#state").empty();
               $("#state").append('<option value="">Select</option>');
               $.each(res,function(key,value){
                    var selected = "";
                    if(state == key) {
                       selected = "selected";
                    }
                    $("#state").append('<option value="'+key+'" '+selected+'>'+value+'</option>');
                    if(selected) {
                        $("#state").trigger('change');
                    }
               });
               $('.spinner').hide();
           }else{
               $('.spinner').hide();
              $("#state").empty();
              $("#area").empty();
           }
          }
       });
   }else{
       $('.spinner').hide();
       $("#state").empty();
       $("#area").empty();
   }      
   }).change();
   
   $('#state').on('change',function(){
   var stateID = $(this).val();    
   if(stateID){
       $('.spinner').show();
       $.ajax({
          type:"GET",
          url:"{{url('admin/region/get-city-list')}}?state_id="+stateID,
          success:function(res){               
           if(res){
               $('.spinner').hide();
               $("#area").empty();
               $("#area").append('<option value="">Select</option>');
               $.each(res,function(key,value){
                    var selected = "";
                    if(city == key) {
                       selected = "selected";
                    }
                    $("#area").append('<option value="'+key+'" '+selected+'>'+value+'</option>');
                    if(selected) {
                        $("#area").trigger('change');
                    }
               });
          
           }else{
               $('.spinner').hide();
              $("#area").empty();
           }
          }
       });
   }else{
       $('.spinner').hide();
       $("#area").empty();
   }
   }).change();
   
//   $('#city').on('change',function(){
//   var cityID = $(this).val();    
//   if(cityID){
//       $('.spinner').show();
//       $.ajax({
//          type:"GET",
//          url:"{{url('admin/region/get-area-list')}}?city_id="+cityID,
//          success:function(res){    
//              $('.spinner').hide();
//           if(res){
//               $("#area").empty();
//               $("#area").append('<option value="">Select</option>');
//               $.each(res,function(key,value){
//                    var selected = "";
//                    if(area == key) {
//                       selected = "selected";
//                    }
//                    $("#area").append('<option value="'+key+'" '+selected+'>'+value+'</option>');
//                    if(selected) {
//                        $("#area").trigger('change');
//                    }
//               });
//          
//           }else{
//              $("#area").empty();
//           }
//          }
//       });
//   }else{
//       $('.spinner').hide();
//       $("#area").empty();
//   }
//   });
   
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
</script>
{!! $editvalidator !!}
@endsection