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
                        <a href="{{url('/admin/promocode/show_edit_form/0')}}" type="button" class="btn btn-block btn-primary">Add New Promocode</a>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <table id="promocodeListing" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Promocode</th>
                            <th>Image</th>
                            <th>Discount(%)</th>
                            <th>No of Usage</th>
                            <th>Min Order Value</th>
                            <th>Max Dis Amt</th>
                            <th>Limitation</th>
                            <th>Starts On</th>
                            <th>Expire On</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Promocode</th>
                            <th>Image</th>
                            <th>Discount(%)</th>
                            <th>No of Usage</th>
                            <th>Min Order Value</th>
                            <th>Max Dis Amt</th>
                            <th>Limitation</th>
                            <th>Starts On</th>
                            <th>Expire On</th>
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
    <script src="{{url('/js/admin/datatables/promocodeListing.js')}}"></script>
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
  </script>
@endsection