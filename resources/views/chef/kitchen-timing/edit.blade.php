@extends('chef.layout.master')

@section('title', $pageMeta['pageName'])

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{isset($timingsArr['id']) ? 'Edit' : 'Add'}} Kitchen Timings</h3>
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
        <form id='addEditPromoForm' method='POST'  action='{{url('/chef/kitchentiming/save')}}' class="form-inline" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
            <div class="form-group">
                <div class="col-sm-12">
                  <div class="text-info">Please enter To Time 1 greater than From Time 1</div>
                  <div class="text-info">Please enter To Time 2 greater than From Time 2</div>
                </div>
            </div>
          </div>
            @if(count($timingsArr) > 0)
              <div class="row">
               <table id="timingListing" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Day</th>
                        <th>From Time 1</th>
                        <th>To Time 1</th>
                        <th>From Time 2</th>
                        <th>To Time 2</th>
                    </tr>
                </thead>
                @foreach($timingsArr as $key=>$time)
                <tfoot>
                  <tr>
                      <th>
                          {{ $time['day'] }}
                          <input id="day" type="hidden" class="form-control" name="day[]" value="{{ $time['day'] }}" autofocus>
                          <input id="id" type="hidden" class="form-control" name="id[]" value="{{isset($time['id']) ? $time['id'] : ''}}" autofocus>
                      </th>
                      <th>
                          <select id="from_time1" name='from_time1[]' class="form-control" >
                            @for ($i = 0; $i <= 23; $i++)
                                <option value="{{ $i }}" {{isset($time['from_time1']) && $time['from_time1'] == $i ? 'selected' : ''}}>{{ $i }}</option>
                            @endfor
                          </select>
                      </th>
                      <th>
                          <select id="to_time1" name='to_time1[]' class="form-control" >
                            @for ($i = 0; $i <= 23; $i++)
                                <option value="{{ $i }}" {{isset($time['to_time1']) && $time['to_time1'] == $i ? 'selected' : ''}}>{{ $i }}</option>
                            @endfor
                          </select>
                      </th>
                      <th>
                          <select id="from_time2" name='from_time2[]' class="form-control" >
                            @for ($i = 0; $i <= 23; $i++)
                                <option value="{{ $i }}" {{isset($time['from_time2']) && $time['from_time2'] == $i ? 'selected' : ''}}>{{ $i }}</option>
                            @endfor
                          </select>
                      </th>
                      <th>
                          <select id="to_time2" name='to_time2[]' class="form-control" >
                            @for ($i = 0; $i <= 23; $i++)
                                <option value="{{ $i }}" {{isset($time['to_time2']) && $time['to_time2'] == $i ? 'selected' : ''}}>{{ $i }}</option>
                            @endfor
                          </select>
                      </th>
                  </tr>
                </tfoot>
              @endforeach
            </table>
          </div>
            @endif
            <div class="form-group">
                <div class="col-sm-9 col-sm-offset-4">
                    
                    <button type="submit" class="btn btn-primary" name="signup" value="Sign up">Save</button>
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