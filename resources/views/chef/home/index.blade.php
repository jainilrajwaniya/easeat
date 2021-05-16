@extends('chef.layout.master')
@section('title', $pageMeta['pageName'])
@section('pageCss')
  <link rel="stylesheet" href="{{url('/css/button.css')}}">
@endsection
@section('content')
<div class="row">
<!--    <div class="col-lg-3 col-xs-6">
        <div class="box box-info box-solid">
            <div class="box-header">
              <h3 class="box-title">Kitchen's Status</h3>
            </div>
            <div class="box-body" style='text-align:center;'>
                <label title="Change Status" class="switch">
                    <input id="kitchenStatus" type="checkbox" onchange="markKitchenAsOpenClose({{Auth::guard('chef')->user()->id}})">
                    <span class="slider round"></span>
                </label>
            </div>
            <div class="overlay">
              <i class="fa fa-refresh fa-spin"></i>
            </div>
        </div>
    </div>-->
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-aqua">
            <div class="inner">
              <h3>150</h3>
              <p>New Orders</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-green">
            <div class="inner">
                <h3>53<sup style="font-size: 20px">%</sup></h3>

                <p>Bounce Rate</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>    
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-yellow">
            <div class="inner">
              <h3>44</h3>

              <p>User Registrations</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-red">
            <div class="inner">
              <h3>65</h3>

              <p>Unique Visitors</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>
@endsection
