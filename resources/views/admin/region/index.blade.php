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
                    <!-- /.custome-tab -->
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <!--<li class=""><a href="#tab_1" data-toggle="tab" aria-expanded="true">Block/Street</a></li>-->
                            <li class="active"><a href="#tab_2" data-toggle="tab" aria-expanded="false">Area</a></li>
                            <li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="false">Governate</a></li>
                            <li class=""><a href="#tab_4" data-toggle="tab" aria-expanded="false">Countries</a></li>
                            <!--<li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>-->
                        </ul>
                        <div class="tab-content">
<!--                            <div class="tab-pane active" id="tab_1">
                                <div class="row">
                                    <div class="col-xs-1 pull-right">
                                        <a href="javascript:void(0);" onclick="openEditAreaModal(0, '', '', 'ADD');" type="button" class="btn btn-block btn-primary">Add</a>
                                    </div>
                                </div>
                                <br>
                                <table id="areaListing" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Block/Street</th>
                                            <th>Area</th>
                                            <th>Governate</th>
                                            <th>Country</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                   
                                    <tfoot>
                                        <tr>
                                            <th>Block/Street</th>
                                            <th>Area</th>
                                            <th>Governate</th>
                                            <th>Country</th>
                                            <th>Actions</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>-->
                            <!-- /.tab-pane -->
                            <div class="tab-pane active" id="tab_2">
                                <div class="row">
                                    <div class="col-xs-1 pull-right">
                                        <a href="javascript:void(0);" onclick="openEditCityModal(0, '', '', 'ADD');" type="button" class="btn btn-block btn-primary">Add</a>
                                    </div>
                                </div>
                                <br>
                                <table id="cityListing" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Area</th>
                                            <th>Governate</th>
                                            <th>Country</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>

                                    <tfoot>
                                        <tr>
                                            <th>Area</th>
                                            <th>Governate</th>
                                            <th>Country</th>
                                            <th>Actions</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="tab_3">
                                <div class="row">
                                    <div class="col-xs-1 pull-right">
                                        <a href="javascript:void(0);" onclick="openEditCountyModal(0, '', '', 'ADD');" type="button" class="btn btn-block btn-primary">Add</a>
                                    </div>
                                </div>
                                <br>
                                <table id="stateListing" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Governate</th>
                                            <th>Country</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Governate</th>
                                            <th>Country</th>
                                            <th>Actions</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- /.tab-pane -->
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="tab_4">
                                <table id="conListing" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Country</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 0; $i < sizeof($countries); $i++)
                                        <tr>
                                            <td>{{ $countries[$i]->name }}</td>
                                        </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div>


                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>

<!-- edit area Modal -->
<div class="modal" id="editAreaModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 id="area_modal_title" class="modal-title">Add</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <form id='editAreaForm' method='POST'  action='javascript:void(0);' class="form-horizontal" >
                    {{ csrf_field() }}
                    <input id="area_id" type="hidden" class="form-control" name="id" value="" autofocus>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="area">Area</label>
                        <div class="col-sm-5">
                            <input id="area_name" type="text" placeholder="Area" class="form-control" name="area_name" value="" autofocus>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="city">City</label>
                        <div class="col-sm-5">
                            <select id="city" name='' class="form-control" >
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button id="area_modal_save_button" onclick="editArea();" type="button" class="btn btn-primary" >Save</button>
                <button type="button" class="btn cancel" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- edit city Modal -->
<div class="modal" id="editCityModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 id="city_modal_title" class="modal-title">Add</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <form id='editCityForm' method='POST'  action='javascript:void(0);' class="form-horizontal" >
                    {{ csrf_field() }}
                    <input id="city_id" type="hidden" class="form-control" name="city_id" value="" autofocus>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="city">Area</label>
                        <div class="col-sm-5">
                            <input id="city_name" type="text" placeholder="City / Town" class="form-control" name="city_name" value="" autofocus>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="state">Governate</label>
                        <div class="col-sm-5">
                            <select id="state" name='state' class="form-control" >
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button id="county_modal_save_button" onclick="editCity();" type="button" class="btn btn-primary" >Save</button>
                <button type="button" class="btn cancel" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- edit county Modal -->
<div class="modal" id="editCountyModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 id="county_modal_title" class="modal-title">Add</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <form id='editCountyForm' method='POST'  action='javascript:void(0);' class="form-horizontal" >
                    {{ csrf_field() }}
                    <input id="county_id" type="hidden" class="form-control" name="id" value="" autofocus>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="county">Governate</label>
                        <div class="col-sm-5">
                            <input id="county_name" type="text" placeholder="County / State" class="form-control" name="county_name" value="" autofocus>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="country">Country</label>
                        <div class="col-sm-5">
                            <select id="country" name='country' class="form-control" >
                                    @foreach($countries as $coun)
                                        <option value='{{$coun->id}}' >{{$coun->name}}</option>
                                    @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button id="county_modal_save_button" onclick="editCounty();" type="button" class="btn btn-primary" >Save</button>
                <button type="button" class="btn cancel" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('pageJavascript')
<!-- DataTables -->
<script src="{{url('/js/jquery/jquery.dataTables.min.js')}}"></script>
<script src="{{url('/js/bootstrap/dataTables.bootstrap.min.js')}}"></script>
<script src="{{url('/js/admin/region.js')}}"></script>
@endsection