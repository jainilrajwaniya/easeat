@extends('chef.layout.master')

@section('title', $pageMeta['pageName'])

@section('pageCss')
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
                    @if ($hasItems == 1)
                        <div class="row">
                            <div class="col-sm-12" style="text-align: right;"><a class="btn btn-warning" href="{{url('/chef/kitchenmenu/download_kitchen_menu_excel')}}">Download Current menu</a></div>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                           <ul>
                              @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                              @endforeach
                           </ul>
                        </div>
                    @endif
                    <form id='uploadSheetForm' method='POST'  action='{{url('/chef/kitchenmenu/bulkupload/save')}}' class="form-horizontal" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input id="chef_id" type="hidden" class="form-control" name="chef_id" value="{{$chef_id}}" autofocus>
                        <input id="kitchen_id" type="hidden" class="form-control" name="kitchen_id" value="{{$kitchen_id}}" autofocus>
                        <div class="form-group">
                           <label class="col-sm-4 control-label" for="kitchen_item_sheet">Kitchen Item Sheet</label>
                           <div class="col-sm-5">
                              <input type="file" id="kitchen_item_sheet" name="kitchen_item_sheet" class="form-control" value="">
                              <label style="color:red;">Please upload file having extension: xlsx, xls</lable>
                              &nbsp;&nbsp;<a href="{{url('/kitchen_item_upload_sheet_sample_format.xlsx')}}">(Sample file)</a>
                           </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-9 col-sm-offset-4">
                               <button type="submit" class="btn btn-primary" name="signup" value="Sign up">Save</button>
                            </div>
                         </div>
                      </form>
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
@endsection