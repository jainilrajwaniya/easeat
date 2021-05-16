@extends('chef.layout.master')
@section('title', $pageMeta['pageName'])

@section('pageCss')
  <link rel="stylesheet" href="{{url('/css/select2.min.css')}}">
@endsection

@section('content')
<div class="panel panel-default">
   @if(isset($menuArr['id']))
   <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
         <li class="active"><a href="#"  aria-expanded="false">Menu</a></li>
         <li class=""><a href="{{url('/chef/kitchenmenu/addon/listing/'.$menuArr['id'])}}"  aria-expanded="true">Customizations / Add Ons</a></li>
      </ul>
   </div>
   @endif
   <div class="panel-heading">
      <h3 class="panel-title">{{isset($menuArr['id']) ? 'Edit' : 'Add'}} Kitchen Menu</h3>
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
      <form id='addEditPromoForm' method='POST'  action='{{url('/chef/kitchenmenu/save')}}' class="form-horizontal" enctype="multipart/form-data">
      {{ csrf_field() }}
      <input id="id" type="hidden" class="form-control" name="id" value="{{isset($menuArr['id']) ? $menuArr['id'] : ''}}" autofocus>
      <div class="form-group">
         <label class="col-sm-4 control-label" for="item_name">Item Name</label>
         <div class="col-sm-5">
            <input id="item_name" type="text" placeholder="Item Name" class="form-control" name="item_name" value="{{isset($menuArr['item_name']) ? $menuArr['item_name'] : old('item_name')}}" autofocus>
         </div>
      </div>
      <div class="form-group">
         <label class="col-sm-4 control-label" for="item_name_ar">Item Name Arabic</label>
         <div class="col-sm-5">
            <input id="item_name_ar" type="text" placeholder="Item Name Ar" class="form-control" name="item_name_ar" value="{{isset($menuArr['item_name_ar']) ? $menuArr['item_name_ar'] : old('item_name_ar')}}" autofocus>
         </div>
      </div>
      <div class="form-group">
         <label class="col-sm-4 control-label" for="description">Description</label>
         <div class="col-sm-5">
            <textarea class="form-control" id="description" name="description" placeholder="Description">{{isset($menuArr['description']) ? $menuArr['description'] : old('description')}}</textarea>
         </div>
      </div>
      <div class="form-group">
         <label class="col-sm-4 control-label" for="cuisine_type_id">Cusine Type</label>
         <div class="col-sm-5">
            <select id="cuisine_types" name='cuisine_types[]' class="form-control" multiple="multiple">
                @foreach($cuisine_types as $cuisine)
                <option value='{{$cuisine->cuisine_type_name}}' @if(in_array($cuisine->cuisine_type_name, $kitchenArr['cuisine_types'])) selected @endif >{{$cuisine->cuisine_type_name}}</option>
                @endforeach
            </select>
         </div>
      </div>
      <div class="form-group">
         <label class="col-sm-4 control-label" for="category_id">Categories</label>
         <div class="col-sm-5">
            <select id="categories" name='categories[]' class="form-control" multiple="multiple">
                @foreach($categories as $cat)
                <option value='{{$cat->category_name}}' @if(in_array($cat->category_name, $kitchenArr['categories'])) selected @endif>{{$cat->category_name}}</option>
                @endforeach
            </select>
         </div>
      </div>
      <div class="form-group">
         <label class="col-sm-4 control-label" for="groups">Groups</label>
         <div class="col-sm-5">
            <select id="groups" name='groups[]' class="form-control" multiple="multiple">
                @foreach($groups as $group)
                <option value='{{$group->id}}' @if(in_array($group->id, $kitchenArr['groups'])) selected @endif>{{$group->group_name}}</option>
                @endforeach
            </select>
         </div>
      </div>
      <div class="form-group">
         <label class="col-sm-4 control-label" for="password1">Item Pic</label>
         <div class="col-sm-5">
            <input type="file" id="profile_pic" name="profile_pic" class="form-control" value="">
            @if(isset($menuArr["id"]) && !empty($menuArr["profile_pic"]))
                <img src="{{ config('aws.aws_s3_url').'/uploads/kitchen-menu/'.$menuArr["id"].'/thumbnails/200x200/'.$menuArr["profile_pic"].'?'.time() }}" border="0" width="150" class="img-rounded" align="center" />
            @endif
         </div>
      </div>
      <div class="form-group">
         <label class="col-sm-4 control-label" for="average_prep_time">Average Prep Time</label>
         <div class="col-sm-5">
            <input id="average_prep_time" type="text" placeholder="Average Prep Time" class="form-control" name="average_prep_time" value="{{isset($menuArr['average_prep_time']) ? $menuArr['average_prep_time'] : old('average_prep_time')}}" autofocus>
         </div>
      </div>
      <div class="form-group">
         <label class="col-sm-4 control-label" for="price">Price</label>
         <div class="col-sm-5">
            <input id="price" type="text" placeholder="Price" class="form-control" name="price" value="{{isset($menuArr['price']) ? $menuArr['price'] : old('price')}}" autofocus>
         </div>
      </div>
<!--      <div class="form-group">
         <label class="col-sm-4 control-label" for="half_price">Half Price</label>
         <div class="col-sm-5">
            <input id="half_price" type="text" placeholder="Half Plate Price" class="form-control" name="half_price" value="{{isset($menuArr['half_price']) ? $menuArr['half_price'] : old('half_price')}}" autofocus>
         </div>
      </div>-->
      <div class="form-group">
         <label class="col-sm-4 control-label" for="pure_veg">Pure Veg</label>
         <div class="col-sm-5">
            <select id="pure_veg" name='pure_veg' class="form-control" >
                <option value='0' {{isset($menuArr['pure_veg']) && $menuArr['pure_veg'] == '0' ? 'selected' : ''}}>No</option>
                <option value='1' {{isset($menuArr['pure_veg']) && $menuArr['pure_veg'] == '1' ? 'selected' : ''}}>Yes</option>
            </select>
         </div>
      </div>
      <div class="form-group" id="sizeDiv">
         <label class="col-sm-4 control-label" for="Varients">Varients</label>
         <div class="col-sm-4">
            <button type="button" id="add_sizes_button">Add Varient</button>
         </div>
      </div>

    @if(isset($kitchenVarientsArr) && count($kitchenVarientsArr) > 0)
        @foreach($kitchenVarientsArr as $key => $item)
            <div class="form-group" id="varientDiv_{{$item['id']}}">
             <label class="col-sm-4 control-label" for="Varient">Varient</label>
             <div class="col-sm-2">
                <input type="hidden" name="varient_id[]" value="{{$item['id']}}" autofocus>
                <input type="text" placeholder="Half,Full,6 inches" class="form-control" name="varient_name[]" value="{{$item['varient_name']}}" autofocus>
             </div>
             <div class="col-sm-2">
                <input type="text" placeholder="varient name ar" class="form-control" name="varient_name_ar[]" value="{{$item['varient_name_ar']}}" autofocus>
             </div>
             <div class="col-sm-2">
                <input  type="text" placeholder="Price" class="form-control" name="varient_price[]" value="{{$item['varient_price']}}" autofocus>
             </div>
             <div class="col-sm-2">
                <button type="button" onclick="deleteVarient('{{$item['id']}}');">remove</button>
             </div>
            </div>
        @endforeach
    @endif

      <div class="form-group">
         <div class="col-sm-9 col-sm-offset-4">
            <button type="submit" class="btn btn-primary" name="signup" value="Sign up">{{isset($menuArr['id']) ? 'Edit' : 'Add'}}</button>
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
<script type="text/javascript">
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
   
   $(document).ready(function() {
       $('#categories, #cuisine_types, #groups').select2();//initializing ddls
       $('.select2-selection__choice').css('background-color', '#3c8dbc');//set color for selected value
       $('.select2-selection__choice').css('border-color', '#3c8dbc');//set color for selected value
       
       $('#add_sizes_button').click(function(){
           $('#sizeDiv').after('<div class="form-group"><label class="col-sm-4 control-label" for="sizes">Varient</label><div class="col-sm-2"><input type="hidden" name="varient_id[]" value="0" autofocus><input type="text" placeholder="Half,Full,6 inches" class="form-control" name="varient_name[]" value="" autofocus></div><div class="col-sm-2"><input type="text" placeholder="varient name ar" class="form-control" name="varient_name_ar[]" value="" autofocus></div><div class="col-sm-2"><input  type="text" placeholder="Price" class="form-control" name="varient_price[]" value="" autofocus></div><div class="col-sm-2"><button type="button" class="removeSizeBtn">remove</button></div></div>');
       });
       
       $(document).on('click', '.removeSizeBtn', function(){
           $(this).parent('div').parent('div').remove();
       });
   });
   
   /**
   * delete varient
    */
   function deleteVarient(varient_id) {
           if(confirm('Are you sure want to delete this varient?')) {
                $('.spinner').show();
                $.ajaxSetup({
                    headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: baseUrl+"/chef/kitchenmenu/ajax_delete_varient/"+varient_id,
                    method: "GET",
                    dataType : 'json',
                    success: function(response) {
                        if(response.status == true) {
                            toastr.success(response.message);
                            $("#varientDiv_"+varient_id).remove();
                            $('.spinner').hide();
                        } else {
                            toastr.error(response.message);
                            $('.spinner').hide();
                        }
                    },
                    error: function(response) {
                        $('.spinner').hide();
                        toastr.error('Something went wrong!!!');
                    }
                });
            }
       }
</script>
{!! $validator !!}
@endsection