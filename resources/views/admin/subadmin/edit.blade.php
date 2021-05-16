@extends('admin.layout.master')

@section('title', $pageMeta['pageName'])

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{isset($adminArr['id']) ? 'Edit' : 'Add'}} Sub Admin</h3>
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
        <form id='addEditSubAdminForm' method='POST'  action='{{url('/admin/subadmin/save')}}' class="form-horizontal"  enctype="multipart/form-data">
            {{ csrf_field() }}
            <input id="id" type="hidden" class="form-control" name="id" value="{{isset($adminArr['id']) ? $adminArr['id'] : ''}}" autofocus>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="firstname1">Name</label>
                <div class="col-sm-5">
                    <input id="name" type="text" placeholder="Name" class="form-control" name="name" value="{{isset($adminArr['name']) ? $adminArr['name'] : old('name')}}" autofocus>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="email">Email</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="email" name="email" placeholder="Email" value="{{isset($adminArr['email']) ? $adminArr['email'] : old('email')}}"/>
                </div>
            </div>
            @if(!isset($adminArr['id']) || $adminArr['id'] == 0)
            <!-- <div class="form-group">
                <label class="col-sm-4 control-label" for="password">Password</label>
                <div class="col-sm-5">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="confirm_password">Confirm password</label>
                <div class="col-sm-5">
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm password" />
                </div>
            </div> -->
            @endif
            <!-- <div class="form-group">
                <label class="col-sm-4 control-label" for="password1">Password</label>
                <div class="col-sm-5">
                    <select id="role" name='role' class="form-control" >
                        @foreach($adminRolesArr as $adminRole)
                            <option value='{{$adminRole}}' {{isset($adminArr['role']) && $adminRole == $adminArr['role'] ? 'selected' : ''}}>{{$adminRole}}</option>
                        @endforeach
                    </select>
                </div>
            </div> -->

            <div class="form-group">
                <label class="col-sm-4 control-label" for="password1">Role</label>
                <div class="col-sm-5">
                    <select id="role" name='role' class="form-control" >
                            <option value='SUPER_ADMIN' {{isset($adminArr['role']) && $adminArr['role'] == 'SUPER_ADMIN' ? 'selected' : ''}}>Super Admin</option>
                            <option value='ADMIN' {{isset($adminArr['role']) && $adminArr['role'] == 'ADMIN' ? 'selected' : ''}}>Admin</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 control-label" for="password1">Profile Pic</label>
                <div class="col-sm-5">
                    <input type="file" id="profile_pic" name="profile_pic" class="form-control" value="">
                    @if(isset($adminArr["id"]))
                        <img src="{{ config('aws.aws_s3_url').'/uploads/sub-admin/profile-pic/'.$adminArr["id"].'/thumbnails/200x200/'.$adminArr["profile_pic"].'?'.time() }}" border="0" width="150" class="img-rounded" align="center" /> 
                    @endif
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-9 col-sm-offset-4">
                    <button type="submit" class="btn btn-primary" name="signup" value="Sign up">{{isset($adminArr['id']) ? 'Edit' : 'Add'}}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('pageJavascript')
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

<script type='text/javascript'>
    $(document).ready(function () {
        /*$("#addEditSubAdminForm").validate({
            rules: {
                name: "required",
                password: {
                    required: true,
                    minlength: 8
                },
                confirm_password: {
                    required: true,
                    minlength: 8,
                    equalTo: "#password"
                },
                email: {
                    required: true,
                    email: true
                }
            },
            messages: {
                name: "Please enter name",
                password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 8 characters long"
                },
                confirm_password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 8 characters long",
                    equalTo: "Please enter the same password as above"
                },
                email: "Please enter a valid email address"
            },
            errorElement: "em",
            errorPlacement: function (error, element) {
                // Add the `help-block` class to the error element
                error.addClass("help-block");

                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.parent("label"));
                } else {
                    error.insertAfter(element);
                }

                // Add the span element, if doesn't exists, and apply the icon classes to it.
                if (!element.next("span")[ 0 ]) {
                    $("<span class='glyphicon glyphicon-remove form-control-feedback'></span>").insertAfter(element);
                }

            },
            success: function (label, element) {
                // Add the span element, if doesn't exists, and apply the icon classes to it.
                if (!$(element).next("span")[ 0 ]) {
                    $("<span class='glyphicon glyphicon-ok form-control-feedback'></span>").insertAfter($(element));
                }
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents(".col-sm-5").addClass("has-error").removeClass("has-success");
                $(element).next("span").addClass("glyphicon-remove").removeClass("glyphicon-ok");
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".col-sm-5").addClass("has-success").removeClass("has-error");
                $(element).next("span").addClass("glyphicon-ok").removeClass("glyphicon-remove");
            }
        });*/

    });
</script>
@endsection