@extends('chef.layout.master')

@section('title', $pageMeta['pageName'])

@section('pageCss')
<link rel="stylesheet" href="{{url('/css/bootstrap/dataTables.bootstrap.min.css')}}">
<link rel="stylesheet" href="{{url('/css/jquery-ui.min.css')}}">
<link rel="stylesheet" href="{{url('/css/button.css')}}">
<style>
    #group_sortable:hover{ 
        cursor:grab; 
    }
</style>
@endsection

@section('content')
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <!--<h3 class="box-title">Data Table With Full Features</h3>-->
                </div>

                <div class="row">
<!--                    <div class="col-xs-3 pull-right" style="margin-right:10px;">
                        <a onclick="editGroupOrder();" href="javascript:void(0);" type="button" class="btn btn-block btn-warning">Drag and Change Group Order</a>
                    </div>-->
                    <div class="col-xs-3 pull-right" style="margin-right:10px;">
                        <a href="{{url('/chef/kitchenmenu/show_edit_form/0')}}" type="button" class="btn btn-block btn-primary">Add New Menu</a>
                    </div>
                </div>
                <!-- /.box-header -->
                <div id="group_sortable" title="Drag and drop to change group's sequence order" class="box-body">
                    @foreach($result as $ele)
                        <div class="box group_item box-solid box-warning" group_id='{{$ele['group_id']}}'>
                            <div class="box-header">
                            <h3 class="box-title">{{$ele['title']}}</h3>
                                <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                                            <!--<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>-->
                                </div>
                            </div>
                                <div id="item_sortable_{{$ele['group_id']}}" title="Drag and drop to change item's sequence order in group" class="box-body">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Item Name</th>
                                                <th>Categories</th>
                                                <th>Cuisine Type</th>
                                                <th>Average Prep Time</th>
                                                <th>Price</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                    <tbody>
                                        @foreach($ele['items'] as $item)
                                            <tr class='kitchen_item' item_id='{{$item['item_id']}}'>
                                                <td>{{$item['item_name']}}</td>
                                                <td>{{$item['categories']}}</td>
                                                <td>{{$item['cuisine_types']}}</td>
                                                <td>{{$item['average_prep_time']}}</td>
                                                <td>{{$item['price']}}</td>
                                                <td>
                                                    <label title="Change Status" class="switch"><input type="checkbox" {{ $item['status'] == 'Active' ? 'checked' : '' }} onchange="changepromo_codestatus('{{$item['item_id']}}')" /><span class="slider round"></span></label>
                                                    &nbsp;&nbsp;
                                                    <a href="{{url('/')}}/chef/kitchenmenu/show_edit_form/{{$item['item_id']}}">
                                                        <i class="fa fa-edit" style="font-size:20px;"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    </table>
                                </div>
                        </div>
                    @endforeach
<!--                  <table id="menuListing"     class="table table-bordered table-striped">
                  <thead>
<tr>
<th>Item Name</th>
<th>Category</th>
<th>Cuisine Type</th>
<th>Group</th>
<th>Average Prep Time</th>
<th>Price</th>
<th>Created At</th>
<th>Action</th>
</tr>
</thead>
<tfoot>
<tr>
<th>Item Name</th>
<th>Category</th>
<th>Cuisine Type</th>
<th>Group</th>
<th>Average Prep Time</th>
<th>Price</th>
<th>Created At</th>
<th>Action</th>
</tr>
</tfoot>
</table>-->
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
<script src="{{url('/js/chef/datatables/kitchenmenuListing.js')}}"></script>
<script>
    $( function() {
        $("#group_sortable").sortable({
            items: ".group_item"
        });
        $("#group_sortable").disableSelection();
        $("#group_sortable").on("sortstop", function(event, ui) {
            editGroupOrder();
        });
            
        @foreach($result as $ele)
            $("#item_sortable_{{$ele['group_id']}}").sortable({
                items: ".kitchen_item"
            });
            $("#item_sortable_{{$ele['group_id']}}").disableSelection();
            $("#item_sortable_{{$ele['group_id']}}").on("sortstop", function(event, ui) {
                editGroupItemOrder($(this).attr('id'));
            });
        @endforeach
  } );

@if (Session::has('message'))
var type = "{{ Session::get('alert-type', 'info') }}";
switch (type){
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
@endsection