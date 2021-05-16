@extends('admin.layout.master')

@section('title', $pageMeta['pageName'])

@section('pageCss')
  <link rel="stylesheet" href="{{url('/css/bootstrap/dataTables.bootstrap.min.css')}}">
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
                    <div class="col-xs-2 pull-right" style="margin-right:10px;">
                        <a href="{{url('/admin/subadmin/show_edit_form/0')}}" type="button" class="btn btn-block btn-primary">Add New Admin</a>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <table id="adminListing" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Profile Pic</th>
                            <!--<th>Status</th>-->
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Profile Pic</th>
                            <!--<th>Status</th>-->
                            <th>Created At</th>
                            <th>Actions</th>
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
    <script src="{{url('/js/admin/datatables/adminListing.js')}}"></script>
@endsection

@section('pagePopups')
    <!-- Add Sub admin modal -->
<!--    <div id="addEditSubAdminModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
       Modal content
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Edit Sub Admin</h4>
        </div>
        <div class="modal-body">
            
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>
    
    <script type="text/javascript">
        function submitAdminForm() {
            $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var data = {};
            data['name'] = $('#name').val();
            data['email'] = $('#email').val();
            data['password'] = $('#password').val();
            data['confirm_password'] = $('#confirm_password').val();
            data['role'] = $('#role').val();
            console.log(data);
            $.ajax({
                url: "/subadmin/edit",
                method: "POST",
                data: data,
                dataType : 'json',
                success: function(response) {
                    console.log(response);
                },
                error: function(response) {
                    var result = response.responseText;
                    console.log(response);
                    if(typeof(result.meta.status_code) != 'undefined' && result.meta.status_code == 422) {
                        alert('Pleae provide all mandatory fields');
                    } else {
                        alert('Something went wrong!!!');
                    }
                }
          });
        }
    </script>-->
@endsection