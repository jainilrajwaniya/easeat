@extends('chef.layout.master')

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
                        <a onclick="openEditModel('', '0', '0')" href="javascript:void(0);" type="button" class="btn btn-block btn-primary" style="border-radius: 100px;"><i class="fa fa-plus"></i> Add New Group</a>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <table id="groupListing" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Group Name</th>
                            <th>Status</th>
                            <th>Sequence Number</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Group Name</th>
                            <th>Status</th>
                            <th>Sequence Number</th>
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
    
    <!-- The add / edit Modal -->
    <div class="modal" id="addEditGroupStatus">
      <div class="modal-dialog">
        <div class="modal-content">

          <!-- Modal Header -->
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Add / Edit Groups</h4>
          </div>
          <!-- Modal body -->
          <div class="modal-body">
              <input id="group_id" type="hidden" class="form-control" name="group_id" value="0" autofocus>
<!--                <div class="form-group">
                    <label class="col-sm-4 control-label" for="group_name">Group Name</label>
                    <div class="col-sm-5">-->
                    <div class="row">
                        <div class="col-sm-12">
                            <input id="group_name" type="text" placeholder="Group Name" class="form-control" name="group_name" value="" autofocus>
                        </div>
                        <div class="col-sm-12" style="padding-top : 20px;">
                            <input id="seq_no" type="number" min="0" placeholder="Sequence Number" class="form-control" name="seq_no" value="0" autofocus>
                        </div>
                    </div>
<!--                    </div>
                </div>-->
          </div>
          <!-- Modal footer -->
            <div class="modal-footer">
                <button type="submit" onclick="editGroup();" class="btn btn-primary">Save changes</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
      </div>
    </div>

@endsection

@section('pageJavascript')
    <!-- DataTables -->
    <script src="{{url('/js/jquery/jquery.dataTables.min.js')}}"></script>
    <script src="{{url('/js/bootstrap/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{url('/js/chef/datatables/groupListing.js')}}"></script>
    <script>
      function updateCategoryStatus(id, name) {
        $("#updateGroupStatus").modal();
        // $("#modelTitle").html('Update '+ name +' Status');
        var title = document.getElementById('update-group-title');
        var form = document.getElementById('update-group-form');
        title.innerText = "Update Status of " + name + "?";
        form.action = baseUrl+'/chef/group/change_group_status/' + id;
      }
    </script>
@endsection