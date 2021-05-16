@extends('chef.layout.master')
@section('title', $pageMeta['pageName'])
@section('content')
<div class="panel panel-default">
   <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
         <li class=""><a href="{{url('/chef/kitchenmenu/listing/')}}"  aria-expanded="true">Menu</a></li>
         <li class="active"><a href="#"  aria-expanded="false">Customizations / Add Ons</a></li>
      </ul>
   </div>
   <div class="panel-heading">
      <h3 class="panel-title">{{isset($menuArr['id']) ? 'Edit' : 'Add'}} Kitchen Add on</h3>
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
      <form id='addEditPromoForm' method='POST'  action='{{url('/chef/kitchenmenu/saveAddon')}}' class="form-horizontal" enctype="multipart/form-data">
      {{ csrf_field() }}
      <input id="id" type="hidden" class="form-control" name="id" value="{{isset($menuArr['id']) ? $menuArr['id'] : ''}}" autofocus>
      <input id="kitchenItemId" type="hidden" class="form-control" name="kitchenItemId" value="{{ $kitchenItemId }}" autofocus>
      <div class="form-group">
         <label class="col-sm-4 control-label" for="category">Category</label>
         <div class="col-sm-5">
            <input id="category" type="text" placeholder="Category Name" class="form-control" name="category_name_en" value="{{isset($menuArr['category_name_en']) ? $menuArr['category_name_en'] : old('category_name_en')}}" autofocus >
            <input type="hidden" placeholder="Category Name Ar" class="form-control" name="category_name_ar" value="{{isset($menuArr['category_name_ar']) ? $menuArr['category_name_ar'] : old('category_name_ar')}}" autofocus >
         </div>
      </div>
<!--      <div class="form-group">
         <label class="col-sm-4 control-label" for="item_name">Choice</label>
         <div class="col-sm-5">
            <select id="choices" name='choices' class="form-control" >
            <option value='Single' {{isset($menuArr['choices']) && $menuArr['choices'] == 'Single' ? 'selected' : ''}}>Single</option>
            <option value='Multiple' {{isset($menuArr['choices']) && $menuArr['choices'] == 'Multiple' ? 'selected' : ''}}>Multiple</option>
            </select>
         </div>
      </div>-->
      <div class="form-group">
         <label class="col-sm-4 control-label" for="min">Minimum Quantity</label>
         <div class="col-sm-5">
            <input id="min" type="number" min="0" placeholder="Minimum Quantity" class="form-control" name="min" value="{{isset($menuArr['min']) ? $menuArr['min'] : old('min')}}" autofocus >
         </div>
      </div>
      <div class="form-group">
         <label class="col-sm-4 control-label" for="max">Maxmum Quantity</label>
         <div class="col-sm-5">
            <input id="max" type="number" min="0" max="10" placeholder="Maximum Quantity" class="form-control" name="max" value="{{isset($menuArr['max']) ? $menuArr['max'] : old('max')}}" autofocus >
         </div>
      </div>
      <div class="form-group">
         <label class="col-sm-4 control-label" for="cat_seq_no">Category Sequence</label>
         <div class="col-sm-5">
            <input id="cat_seq_no" type="number" min="0" placeholder="Category Sequence" class="form-control" name="cat_seq_no" value="{{isset($menuArr['cat_seq_no']) ? $menuArr['cat_seq_no'] : old('cat_seq_no')}}" autofocus >
         </div>
      </div>
      @if(isset($menuArr['items']) && count($menuArr['items']) > 0)
        
        <div  class="form-group {{ ($menuArr['items'][0]) ? 'input_fields_wrap' : ''}}">
          @foreach($menuArr['items'] as $key=>$item)
          <div id="addOnItemDiv_{{ $item['id'] }}" class="form-group">
           <label class="col-sm-2 control-label" for="">{{ ($key == 0) ? 'Add on' : ''}}</label>
           <div class="col-sm-2">
              <input id="add_on_name" type="text" placeholder="Add on" class="form-control" name="add_on_name[]" value="{{ $item['kitchen_add_on_item_name_en'] }}" >
              <input type="hidden" name="add_on_id[]" value="{{ $item['id'] }}" >
           </div>
           <div class="col-sm-2">
              <input type="text" placeholder="Add on arabic" class="form-control" name="add_on_name_ar[]" value="{{ $item['kitchen_add_on_item_name_ar'] }}" >
           </div>
           <div class="col-sm-2">
              <input id="price" type="text" placeholder="Price" class="form-control" name="price[]" value="{{ $item['price'] }}" >
           </div>
           <div class="col-sm-2">
              <input id="seq_no" type="number" min="0" placeholder="seq no" class="form-control" name="seq_no[]" value="{{ $item['seq_no'] }}" >
           </div>
           <div class="col-sm-2">
              @if($key==0)
              <button type="button" class="add_field_button btn btn-warning">Add</button>
              @else
              <a href="javascript:void(0);" onclick="deleteAddonItem('{{ $item['id'] }}')">Remove</a>
              @endif
           </div>
          </div>
        @endforeach
        </div>
      @else
        @if(isset($menuArr['id']))
          <div class="form-group input_fields_wrap">
             <label class="col-sm-2 control-label" for="">Add on</label>
             <div class="col-sm-2">
                <input id="add_on_name" type="text" placeholder="Add on" class="form-control" name="add_on_name[]" value="" >
                <input type="hidden" name="add_on_id[]" value="0" >
             </div>
             <div class="col-sm-2">
                <input type="text" placeholder="Add on arabic" class="form-control" name="add_on_name_ar[]" value="" >
             </div>
             <div class="col-sm-2">
                <input id="price" type="text" placeholder="Price" class="form-control" name="price[]" value="" >
             </div>
            <div class="col-sm-2">
               <input id="seq_no" type="number" min="0" placeholder="seq no" class="form-control" name="seq_no[]" value="" >
            </div>
             <div class="col-sm-2">
                <button type="button" class="add_field_button  btn btn-warning">Add</button>
             </div>
          </div>
        @else
          <div class="form-group input_fields_wrap">
            <div class="form-group ">
                <label class="col-sm-2 control-label" for="">Add on</label>
                <div class="col-sm-2">
                   <input id="add_on_name" type="text" placeholder="Add on" class="form-control" name="add_on_name[]" value="" >
                   <input type="hidden" name="add_on_id[]" value="0" >
                </div>
                <div class="col-sm-2">
                    <input type="text" placeholder="Add on arabic" class="form-control" name="add_on_name_ar[]" value="" >
                 </div>
                <div class="col-sm-2">
                   <input id="price" type="text" placeholder="Price" class="form-control" name="price[]" value="" >
                </div>
               <div class="col-sm-2">
                  <input id="seq_no" type="number" min="0" placeholder="seq no" class="form-control" name="seq_no[]" value="" >
               </div>
                <div class="col-sm-2">
                   <button type="button" class="add_field_button  btn btn-warning">Add</button>
                </div>
             </div>
          </div>
        @endif
      @endif
      <div class="form-group" >
         <div class="col-sm-9 col-sm-offset-4">
            <button type="submit" class="btn btn-primary" name="signup" value="Sign up">{{isset($menuArr['id']) ? 'Save' : 'Save'}}</button>
            <a href="{{url('/chef/kitchenmenu/addon/listing/'.$kitchenItemId)}}"><button type="button" class="btn btn-primary" name="signup" value="Sign up">Cancel</button></a>
         </div>
      </div>
      </form>
   </div>
</div>
@endsection
@section('pageJavascript')
<!-- Laravel Javascript Validation -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script> -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
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
   
   $( document ).ready(function() {
//     $( "#category" ).autocomplete({
//         source: function(request, response) {
//           $.ajax({
//             url: baseUrl+'/chef/kitchenmenu/ajax_category_listing',
//             data: {
//                     term : request.term
//              },
//             dataType: "json",
//             success: function(data){
//                var resp = $.map(data,function(obj){
//                     //console.log(obj.city_name);
//                     return obj.category;
//                }); 
//   
//                response(resp);
//             }
//           });
//         },
//         minLength: 1
//     });
   
   
      /* Add new field */
     var max_fields      = 10; //maximum input boxes allowed
     var wrapper       = $(".input_fields_wrap"); //Fields wrapper
     var add_button      = $(".add_field_button"); //Add button ID
     
     var x = 1; //initlal text box count
     $(add_button).click(function(e){ //on add input button click
       e.preventDefault();
       if(x < max_fields){ //max input box allowed
         x++; //text box increment
         $(wrapper).append('<div class="form-group"><label class="col-sm-2 control-label" for=""></label><div class="col-sm-2"><input type="hidden" name="add_on_id[]" value="0" ><input type="text" placeholder="Add on" class="form-control" name="add_on_name[]" value="" ></div><div class="col-sm-2"><input type="text" placeholder="Add on arabic" class="form-control" name="add_on_name_ar[]" value="" ></div><div class="col-sm-2"><input id="price" type="text" placeholder="Price" class="form-control" name="price[]" value="" ></div><div class="col-sm-2"><input id="seq_no" type="number" min="0" placeholder="seq no" class="form-control" name="seq_no[]" value="" ></div><div class="col-sm-2"><a href="#" class="remove_field">Remove</a></div></div>'); //add input box
       }
     });
     
     $(".remove_field").on("click", function(e){
       e.preventDefault(); $(this).parent('div').parent('div').remove(); x--;
     })
   
     $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
       e.preventDefault(); $(this).parent('div').parent('div').remove(); x--;
     })
   });
   
   function deleteAddonItem(id) {
        if(confirm('Are you sure want to delete this add on item?')) {
             $('.spinner').show();
             $.ajaxSetup({
                 headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 }
             });
             $.ajax({
                 url: baseUrl+"/chef/kitchenmenu/ajax_delete_addon/"+id,
                 method: "GET",
                 dataType : 'json',
                 success: function(response) {
                     if(response.status == true) {
                         toastr.success(response.message);
                         $("#addOnItemDiv_"+id).remove();
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