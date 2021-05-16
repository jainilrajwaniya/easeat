@extends('admin.layout.master')

@section('title', $pageMeta['pageName'])

@section('content')
<div class="panel panel-default">
    @if(isset($kitchenImageArr['chef_id']))
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class=""><a href="{{url('/admin/chef/show_edit_form/'.$kitchenImageArr['chef_id'])}}"  aria-expanded="true">Personal Details</a></li>
                <li class=""><a href="{{url('/admin/chef/kitchen_edit_form/'.$kitchenImageArr['chef_id'])}}"  aria-expanded="false">Kitchen</a></li>
                <li class="active"><a href="#" aria-expanded="false">Images</a></li>
            </ul>
        </div>
    @endif
    <div class="panel-heading">
        <h3 class="panel-title">{{isset($kitchenImageArr['chef_id']) ? 'Edit' : 'Add'}} Kitchen Image</h3>
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
        <form id='editKitchenImageForm' method='POST'  action='{{url('/admin/chef/kitchen_images/save')}}' class="form-horizontal" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input id="id" type="hidden" class="form-control" name="kitchen_id" value="{{isset($kitchenImageArr['kitchen_id']) ? $kitchenImageArr['kitchen_id'] : $kitchenImageArr[0]['kitchen_id']}}" autofocus>
            <input id="id" type="hidden" class="form-control" name="chef_id" value="{{isset($kitchenImageArr['chef_id']) ? $kitchenImageArr['chef_id'] : $kitchenImageArr[0]['chef_id']}}" autofocus>
            <input id="id" type="hidden" class="form-control" name="id" value="{{isset($kitchenImageArr['id']) ? $kitchenImageArr['id'] : ''}}" autofocus>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="password1">Kitchen Images</label>
                <div class="col-sm-5">
                    <input type="file" id="kitchen_image" name="kitchen_image[]" class="form-control" value="" multiple>
                    <!-- <div><strong class="text-info">[Recommended Size 2200 X 850px]</strong></div> -->
                </div>
            </div>
            

            <div class="form-group">
                <div class="col-sm-9 col-sm-offset-4">
                    <button type="submit" class="btn btn-primary" name="signup" value="Sign up">{{isset($kitchenImageArr['id']) ? 'Edit' : 'Add'}}</button>
                </div>
            </div>
        </form>

        <div class="row">
            @if(count($kitchenImageArr) > 0)
                @foreach($kitchenImageArr as $kitchenImage)
                    @if(isset($kitchenImage["kitchen_image"]))
                            @php
                                $kitchenId = $kitchenImage["id"];
                            @endphp
                            <div class="col-sm-3">
                                <img src="{{ config('aws.aws_s3_url').'/uploads/chef/kitchens/'.$kitchenImage["id"].'/thumbnails/200x200/'.$kitchenImage["kitchen_image"].'?'.time() }}" border="0" width="150" height="100" class="img-rounded" align="center" /> 
                                <form action="{{url('/admin/chef/kitchen_images/delete/'.$kitchenId)}}" method="post">
                                {{csrf_field()}}
                                <input name="_method" type="hidden" value="DELETE">
                                <button class="btn btn-danger" type="submit" onclick="return confirm('Are you sure want to delete this Image?')"><i class="fa fa-times-circle-o text-danger" style="font-size:20px;"></i></button>
                                </form>
                            </div>
                    @endif
                @endforeach
            @endif
        </div>
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