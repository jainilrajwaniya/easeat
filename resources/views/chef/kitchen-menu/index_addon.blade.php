@extends('chef.layout.master')
@section('title', $pageMeta['pageName'])
@section('pageCss')
<link rel="stylesheet" href="{{url('/css/bootstrap/dataTables.bootstrap.min.css')}}">
<link rel="stylesheet" href="{{url('/css/button.css')}}">
<link rel="stylesheet" href="{{url('/css/jquery-ui.min.css')}}">
<style>
    #addon_sortable:hover{ 
        cursor:grab; 
    }
</style>
@endsection
@section('content')
<section class="content">
   <div class="row">
      <div class="nav-tabs-custom">
         <ul class="nav nav-tabs">
            <li class=""><a href="{{url('/chef/kitchenmenu/show_edit_form/'.Request::segment(5))}}"  aria-expanded="true">Menu</a></li>
            <li class="active"><a href="#"  aria-expanded="false">Customizations / Add Ons</a></li>
         </ul>
      </div>
      <div class="col-xs-12">
         <div class="box">
            <div class="box-header">
               <!--<h3 class="box-title">Data Table With Full Features</h3>-->
            </div>
            <div class="row">
               <div class="col-xs-3 pull-right" style="margin-right:10px;">
                  <a href="{{url('/chef/kitchenmenu/addon/show_edit_form/'.Request::segment(5).'/0')}}" type="button" class="btn btn-block btn-primary">Add New Addons</a>
               </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               <table id="menuAddonListing" class="table table-bordered table-striped table-hover">
                  <thead>
                     <tr>
                        <th>Category</th>
                        <!--<th>Choices</th>-->
                        <th>Created At</th>
                        <th>Action</th>
                     </tr>
                  </thead>
                  <tbody id="addon_sortable">
                    @foreach($addOnsCats as $addOnsCat)
                        <tr title="Drag and drop to change sequence order" class="addon_item" addon_id='{{$addOnsCat['id']}}'>
                            <td>{{$addOnsCat['category_name_en']}}</td>
                            <!--<td>{{$addOnsCat['choices']}}</td>-->
                            <td>{{$addOnsCat['created_date']}}</td>
                            <td>
                                <label title="Change Status" class="switch"><input type="checkbox" {{ $addOnsCat['status'] == 'Active' ? 'checked' : '' }} onchange="changeaddonstatus('{{$addOnsCat['id']}}')" /><span class="slider round"></span></label>
                                &nbsp;&nbsp;
                                <a href="javascript:void(0)" onclick="deleteAddon('{{$addOnsCat['id']}}')">
                                    <i class="fa fa-remove" style="font-size:20px;"></i>
                                </a>&nbsp;&nbsp;
                                <a href="{{url('/')}}/chef/kitchenmenu/addon/show_edit_form/{{$addOnsCat['kitchen_item_id']}}/{{$addOnsCat['id']}}">
                                    <i class="fa fa-edit" style="font-size:20px;"></i>
                                </a>    
                            </td>
                        </tr>
                    @endforeach
                  </tbody>
                  <tfoot>
                     <tr>
                        <th>Category</th>
                        <!--<th>Choices</th>-->
                        <th>Created At</th>
                        <th>Action</th>
                     </tr>
                  </tfoot>
               </table>
            </div>
            <!-- /.box-body -->
         </div>
         <!-- /.box -->
      </div>
      <!-- /.col -->
   </div>
   <!-- /.row -->
</section>
@endsection
@section('pageJavascript')
<!-- DataTables -->
<script src="{{url('/js/jquery/jquery.dataTables.min.js')}}"></script>
<script src="{{url('/js/bootstrap/dataTables.bootstrap.min.js')}}"></script>
<script src="{{url('/js/jquery/jquery-ui.min.js')}}"></script>
<script src="{{url('/js/chef/datatables/kitchenmenuAddOnListing.js')}}"></script>
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
   var kitchenItemId = '{{ Request::segment(5) }}';//used in js file
</script>
@endsection