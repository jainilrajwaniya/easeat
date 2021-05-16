@extends('admin.layout.master')

@section('title', $pageMeta['pageName'])

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Wallet Add</h3>
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
        <form id='addEditForm' method='POST'  action='{{url('/admin/wallet/save')}}' class="form-horizontal" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-group">
                 <label class="col-sm-4 control-label" for="user_id">User</label>
                 <div class="col-sm-5">
                    <select id="user_id" name='user_id' class="form-control" >
                        <option value='' >Select</option>
                        @foreach($users as $user)
                        <option value='{{$user->id}}' >{{$user->first_name}} {{$user->last_name}}</option>
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
                <label class="col-sm-4 control-label" for="transaction_type">Transaction Type</label>
                <div class="col-sm-5">
                    <select id="transaction_type" name='transaction_type' class="form-control" >
                        <option value='debit'>Debit</option>
                        <option value='credit'>Credit</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label" for="type">Type</label>
                <div class="col-sm-5">
                    <select id="type" name='type' class="form-control" >
                        <option value='refund'>Refund</option>
                        <option value='payment'>Payment</option>
                        <option value='other'>Other</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-sm-9 col-sm-offset-4">
                    <button type="submit" class="btn btn-primary" name="signup" value="Sign up">Add</button>
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
    
    // $('#addEditChefForm').submit(function() {
    //   $('.spinner').show();
    // });
  </script>
{!! $validator !!}
@endsection