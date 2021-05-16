@extends('admin.layout.master')

@section('title', $pageMeta['pageName'])

@section('pageCss')
  <link rel="stylesheet" href="{{url('/css/bootstrap/dataTables.bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{url('/css/button.css')}}">
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
                    <div class="col-xs-3 pull-right" style="margin-right:10px;">
                        <a href="{{url('/admin/cuisine/show_edit_form/0')}}" type="button" class="btn btn-block btn-primary" style="border-radius: 100px;"><i class="fa fa-plus"></i> Add New Cuisine</a>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <table id="cuisineListing" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Cuisine Name</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                   <tbody>
                    @for ($i = 0; $i < sizeof($cuisine); $i++)
                        <tr>
                          <td>{{ $cuisine[$i]->cuisine_type_name }}</td>
                          <td>{{ $cuisine[$i]->status }}</td>
                          <td>{{ $cuisine[$i]->created_at }}</td>
                            <td>
                                <label class="switch">
                                    <input type="checkbox" {{ $cuisine[$i]->status=='Active'?'checked':'' }} onchange="updateCuisineStatus({{ $cuisine[$i]->id }}, '{{ $cuisine[$i]->cuisine_type_name }}')">
                                    <span class="slider round"></span>
                                </label>
                                &nbsp;&nbsp;
                                <a href="{{url('/admin/cuisine/show_edit_form/')}}/{{ $cuisine[$i]->id }}"><i class="fa fa-edit" style='font-size:20px;'></i></a>
                            </td>
                        </tr>
                      @endfor
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Cuisine Name</th>
                            <th>Status</th>
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
    <!-- The Modal -->
    <div class="modal" id="updateCuisineStatus">
      <div class="modal-dialog">
        <div class="modal-content">

          <!-- Modal Header -->
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="update-status-title"></h4>
          </div>

          <!-- Modal body -->
          <div class="modal-body">
            Are you sure? You want to update the status..
          </div>

          <!-- Modal footer -->
          <form id="update-status-form" action="/" method="POST">
								@csrf
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Save changes</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
          </form>
        </div>
      </div>
    </div>

@endsection

@section('pageJavascript')
    <!-- DataTables -->
    <script src="{{url('/js/jquery/jquery.dataTables.min.js')}}"></script>
    <script src="{{url('/js/bootstrap/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{url('/js/admin/datatables/cuisineListing.js')}}"></script>
    <script>
      function updateCuisineStatus(id, name) {
        $("#updateCuisineStatus").modal();
        // $("#modelTitle").html('Update '+ name +' Status');
        var title = document.getElementById('update-status-title');
        var form = document.getElementById('update-status-form');
        title.innerText = "Update Status of " + name + "?";
        form.action = baseUrl+"/admin/cuisine/change_cuisine_status/" + id;
      }
    </script>
@endsection