@extends('admin.layout.master')

@section('title', $pageMeta['pageName'])

@section('content')
<div class="panel panel-default">

    <div class="panel-heading">
        <h3 class="panel-title">Assign Promocode</h3>
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
        <form id='addPromoForm' method='POST' action='{{url('/admin/promocode/kitchen/save')}}' class="form-horizontal" >
            {{ csrf_field() }}
            
            <div class="form-group">
                <label class="col-sm-4 control-label" for="promo_code_id">Promo Code</label>
                <div class="col-sm-5">
                    <select id="promo_code_id" name='promo_code_id' class="form-control" >
                      <option value="" selected disabled>Select</option>
                       @foreach($promoArr as $key => $promo)
                       <option value="{{$key}}"> {{$promo}}</option>
                       @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group" style="display: none;" id="assign_chef">
                <label class="col-sm-4 control-label" for="chef_id">Assign Chef</label>
                <div class="col-sm-5" id="kitchen">

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

    $('#promo_code_id').change(function(){
   var promo_code_id = $(this).val();    
   if(promo_code_id){
       $('.spinner').show();
       $.ajax({
          type:"GET",
          url:"{{url('admin/promocode/get-kitchen-list')}}?promo_code_id="+promo_code_id,
          success:function(res){               
           if(res){
               $("#kitchen").empty();
               $("#assign_chef").show();
               // console.log(res.kitchenChefArr);
               var myarray = res.kitchenChefArr;
               $.each(res.chef,function(key,value){
                    var selected = "";
                    if(jQuery.inArray(value.id, myarray) != -1) {
                        $("#kitchen").append('<div class="checkbox"><label><input type="checkbox" value='+value.id+' id="chef_id" name="chef_id[]" checked>'+value.name+'</label></div>');
                    } else {
                        $("#kitchen").append('<div class="checkbox"><label><input type="checkbox" value='+value.id+' id="chef_id" name="chef_id[]">'+value.name+'</label></div>');
                    }
               });
               $('.spinner').hide();
           }else{
               $('.spinner').hide();
           }
          }
       });
   }else{
       $('.spinner').hide();
   }      
   }).change();
  </script>
{!! $validator !!}
@endsection