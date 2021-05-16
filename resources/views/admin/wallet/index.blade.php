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
                <!-- <div class="row">
                    <div class="col-xs-2 pull-right" style="margin-right:10px;">
                        <a href="{{url('/admin/wallet/show_edit_form/0')}}" type="button" class="btn btn-block btn-primary">Add User Wallet</a>
                    </div>
                </div> -->
                <div class="row">
                  @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form id='addEditForm' method='POST'  action='{{url('/admin/wallet/save')}}' class="form-horizontal" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                      <label class="col-sm-4 control-label" for="user_id">User</label>
                      <div class="col-sm-5">
                        <select id="chz-select" name="user_id" data-placeholder="Select...">
                          @foreach($users as $user)
                            <option value='{{$user->id}}' >{{$user->name}}</option>
                            @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-4 control-label" for="amount">Amount</label>
                      <div class="col-sm-5">
                          <input id="amount" type="text" placeholder="Amount" class="form-control" name="amount" value="{{old('amount')}}" autofocus>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-4 control-label" for="description">Description</label>
                      <div class="col-sm-5">
                          <textarea class="form-control" id="description" name="description" placeholder="Description"></textarea>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-9 col-sm-offset-4">
                        <button type="submit" class="btn btn-primary" name="signup" value="Sign up">Add</button>
                      </div>
                    </div>
                </form>
                </div>

                <!-- /.box-header -->
                <div class="box-body">
                  <table id="walletListing" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Order ID</th>
                            <th>User Id</th>
                            <th>Guest User Id</th>
                            <th>Amount</th>
                            <th>Description</th>
                            <th>Transaction Type</th>
                            <th>Type</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Id</th>
                            <th>Order ID</th>
                            <th>User Id</th>
                            <th>Guest User Id</th>
                            <th>Amount</th>
                            <th>Description</th>
                            <th>Transaction Type</th>
                            <th>Type</th>
                            <th>Created At</th>
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
  <script type='text/javascript'
  src='http://code.jquery.com/jquery-1.8.3.js'></script>
  <script type='text/javascript'
  src="http://cdn.jsdelivr.net/select2/3.4.1/select2.min.js"></script>
  <link rel="stylesheet" type="text/css"
  href="http://cdn.jsdelivr.net/select2/3.4.1/select2.css">
  <script type='text/javascript'
  src="http://globaltradeconcierge.com/javascripts/bootstrap.min.js"></script>

  <!-- DataTables -->
  <script src="{{url('/js/jquery/jquery.dataTables.min.js')}}"></script>
  <script src="{{url('/js/bootstrap/dataTables.bootstrap.min.js')}}"></script>
  <script src="{{url('/js/admin/datatables/walletListing.js')}}"></script>
  <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

  <script>
  $(window).load(function(){
     $('#chz-select').select2();
  });
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
{!! $validator !!}
@endsection