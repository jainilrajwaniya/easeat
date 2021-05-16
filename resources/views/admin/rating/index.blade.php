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
              <!-- /.box-header -->
              <div class="box-body">
                <table id="ratingListing" class="table table-bordered table-striped nowrap">
                  <thead>
                      <tr>
                          <th>User Name</th>
                          <th>User Email</th>
                          <th>User Phone Number</th>
                          <th>Chef Name</th>
                          <th>Chef Email</th>
                          <th>Chef Phone Number</th>    
                          <th>Rating</th>    
                          <th>Status</th>
                          <th>Created At</th>
                          <th>Actions</th>
                      </tr>
                  </thead>
  <!--                <tbody>
                  </tbody>-->
                  <tfoot>
                      <tr>
                          <th>User Name</th>
                          <th>User Email</th>
                          <th>User Phone Number</th>
                          <th>User Name</th>
                          <th>User Email</th>
                          <th>User Phone Number</th>    
                          <th>Rating</th>    
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

<!-- Rating and review Modal -->
<div class="modal" id="ratingAndReviewModal">
  <div class="modal-dialog">
    <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Rating & Review</h4>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
          <div id="reviewHeading" class="d-flex p-2"></div>
          <div id="reviewdes" class="d-flex p-2"></div>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn" data-dismiss="modal">Close</button>
        </div>
    </div>
  </div>
</div>

@endsection

@section('pageJavascript')
    <!-- DataTables -->
    <script src="{{url('/js/jquery/jquery.dataTables.min.js')}}"></script>
    <script src="{{url('/js/bootstrap/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{url('/js/admin/datatables/ratingListing.js')}}"></script>
@endsection