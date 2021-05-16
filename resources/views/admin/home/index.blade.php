@extends('admin.layout.master')
@section('title', $pageMeta['pageName'])

@section('content')
<div class="row">
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

<div class="row">
    <div class="col-lg-6 col-xs-6">
        <div id="chartContainer" style="width: 100%;"></div>
    </div>
</div>
@endsection
<?php
 
$dataPoints1 = array(
	array("label"=> "2006", "y"=> 3289),
	array("label"=> "2007", "y"=> 5312),
	array("label"=> "2008", "y"=> 11020),
	array("label"=> "2009", "y"=> 16854),
	array("label"=> "2010", "y"=> 30505),
	array("label"=> "2011", "y"=> 52764),
	array("label"=> "2012", "y"=> 70513),
	array("label"=> "2013", "y"=> 81488),
	array("label"=> "2014", "y"=> 88636),
	array("label"=> "2015", "y"=> 95092),
	array("label"=> "2016", "y"=> 103000)
);
 
$dataPoints2 = array(
	array("label"=> "2006", "y"=> 1827),
	array("label"=> "2007", "y"=> 2098),
	array("label"=> "2008", "y"=> 2628),
	array("label"=> "2009", "y"=> 3373),
	array("label"=> "2010", "y"=> 4951),
	array("label"=> "2011", "y"=> 7513),
	array("label"=> "2012", "y"=> 12159),
	array("label"=> "2013", "y"=> 21992),
	array("label"=> "2014", "y"=> 34991),
	array("label"=> "2015", "y"=> 50776),
	array("label"=> "2016", "y"=> 68000)
);
 
$dataPoints3 = array(
	array("label"=> "2006", "y"=> 355),
	array("label"=> "2007", "y"=> 522),
	array("label"=> "2008", "y"=> 828),
	array("label"=> "2009", "y"=> 1328),
	array("label"=> "2010", "y"=> 2410),
	array("label"=> "2011", "y"=> 4590),
	array("label"=> "2012", "y"=> 8365),
	array("label"=> "2013", "y"=> 13727),
	array("label"=> "2014", "y"=> 20534),
	array("label"=> "2015", "y"=> 29639),
	array("label"=> "2016", "y"=> 49000)
);
 
$dataPoints4 = array(
	array("label"=> "2006", "y"=> 80),
	array("label"=> "2007", "y"=> 100),
	array("label"=> "2008", "y"=> 140),
	array("label"=> "2009", "y"=> 300),
	array("label"=> "2010", "y"=> 800),
	array("label"=> "2011", "y"=> 3300),
	array("label"=> "2012", "y"=> 6800),
	array("label"=> "2013", "y"=> 18600),
	array("label"=> "2014", "y"=> 28199),
	array("label"=> "2015", "y"=> 43530),
	array("label"=> "2016", "y"=> 78000)
);
 
$dataPoints5 = array(
	array("label"=> "2006", "y"=> 1),
	array("label"=> "2007", "y"=> 2),
	array("label"=> "2008", "y"=> 3),
	array("label"=> "2009", "y"=> 25),
	array("label"=> "2010", "y"=> 80),
	array("label"=> "2011", "y"=> 205),
	array("label"=> "2012", "y"=> 570),
	array("label"=> "2013", "y"=> 953),
	array("label"=> "2014", "y"=> 1711),
	array("label"=> "2015", "y"=> 2420),
	array("label"=> "2016", "y"=> 6000)
);
 
$dataPoints6 = array(
	array("label"=> "2006", "y"=> 1108),
	array("label"=> "2007", "y"=> 1150),
	array("label"=> "2008", "y"=> 1226),
	array("label"=> "2009", "y"=> 1306),
	array("label"=> "2010", "y"=> 1590),
	array("label"=> "2011", "y"=> 2098),
	array("label"=> "2012", "y"=> 2098),
	array("label"=> "2013", "y"=> 2098),
	array("label"=> "2014", "y"=> 4155),
	array("label"=> "2015", "y"=> 7843),
	array("label"=> "2016", "y"=> 7843)
);
 
?>
@section('pageJavascript')
<script src="{{url('/js/canvas.min.js')}}"></script>
<script>
window.onload = function () {


var chart = new CanvasJS.Chart("chartContainer", {
	title: {
		text: "Growth of Solar Photovoltaics"
	},
	theme: "light2",
	animationEnabled: true,
	toolTip:{
		shared: true,
		reversed: true
	},
	axisY: {
		title: "Cumulative Capacity",
		suffix: " MW"
	},
	legend: {
		cursor: "pointer",
		itemclick: toggleDataSeries
	},
	data: [
		{
			type: "stackedColumn",
			name: "Europe",
			showInLegend: true,
			yValueFormatString: "#,##0 MW",
			dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
		},{
			type: "stackedColumn",
			name: "Asia-Pacific",
			showInLegend: true,
			yValueFormatString: "#,##0 MW",
			dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
		},{
			type: "stackedColumn",
			name: "Americas",
			showInLegend: true,
			yValueFormatString: "#,##0 MW",
			dataPoints: <?php echo json_encode($dataPoints3, JSON_NUMERIC_CHECK); ?>
		},{
			type: "stackedColumn",
			name: "China",
			showInLegend: true,
			yValueFormatString: "#,##0 MW",
			dataPoints: <?php echo json_encode($dataPoints4, JSON_NUMERIC_CHECK); ?>
		},{
			type: "stackedColumn",
			name: "Middle East and Africa",
			showInLegend: true,
			yValueFormatString: "#,##0 MW",
			dataPoints: <?php echo json_encode($dataPoints5, JSON_NUMERIC_CHECK); ?>
		},{
			type: "stackedColumn",
			name: "Rest of the world",
			showInLegend: true,
			yValueFormatString: "#,##0 MW",
			dataPoints: <?php echo json_encode($dataPoints6, JSON_NUMERIC_CHECK); ?>
		}
	]
});
 
chart.render();
 
function toggleDataSeries(e) {
	if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
		e.dataSeries.visible = false;
	} else {
		e.dataSeries.visible = true;
	}
	e.chart.render();
}
 
}
</script>
@endsection