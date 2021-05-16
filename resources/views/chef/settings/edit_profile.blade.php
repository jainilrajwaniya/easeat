@extends('chef.layout.master')

@section('title', $pageMeta['pageName'])

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Edit Profile</h3>
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
        <form id='editProfileForm' method='POST'  action='{{url('/chef/settings/profile/save')}}' class="form-horizontal" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input id="id" type="hidden" class="form-control" name="id" value="{{isset($chefArr['id']) ? $chefArr['id'] : ''}}" autofocus>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="firstname1">Name</label>
                <div class="col-sm-5">
                    <input id="name" type="text" placeholder="Name" class="form-control" name="name" value="{{isset(Auth::guard('chef')->user()->name) ? Auth::guard('chef')->user()->name : old('name')}}" autofocus>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="email">Email</label>
                <div class="col-sm-5">
                    {{ Auth::guard('chef')->user()->email }}
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label" for="password1">Role</label>
                <div class="col-sm-5">
                    {{ str_replace("_", " ", Auth::guard('chef')->user()->role) }}
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 control-label" for="password1">Profile Pic</label>
                <div class="col-sm-5">
                    <input type="file" id="profile_pic" name="profile_pic" class="form-control" value="">
                    <!-- <div><strong class="text-info">[Recommended Size 2200 X 850px]</strong></div> -->
                    @if(isset(Auth::guard('chef')->user()->id))
                        <img src="{{ config('aws.aws_s3_url').'/uploads/chef/profile-pic/'.Auth::guard('chef')->user()->id.'/thumbnails/200x200/'.Auth::guard('chef')->user()->profile_pic.'?'.time() }}" border="0" width="150" class="img-rounded" align="center" /> 
                    @endif
                </div>
            </div>

            
            <div class="form-group">
                <div class="col-sm-9 col-sm-offset-4">
                    <button type="submit" class="btn btn-primary" name="signup" value="Sign up">Update</button>
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
  </script>
{!! $validator !!}
@endsection