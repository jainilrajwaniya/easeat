<!DOCTYPE html>

<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title')</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{url('/')}}/css/font-awesome/css/font-awesome.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{url('/')}}/css/common/AdminLTE.min.css">
  
  <link rel="stylesheet" href="{{url('/')}}/css/common/skin-blue.min.css">
   <!-- Ionicons -->
  <link rel="stylesheet" href="{{url('/')}}/css/ionicons/css/ionicons.min.css">
  <link rel="stylesheet" href="{{url('/')}}/css/bootstrap/css/bootstrap.min.css">
  <link rel="manifest" href="{{url('/')}}/manifest.json">
  <!--toastr min css-->
  <link rel="stylesheet" href="{{url('/')}}/css/toastr.min.css">
  <!--main css-->
  <link rel="stylesheet" href="{{url('/')}}/css/main.css">

  <!--Pafe specific css-->
  @yield('pageCss')
  
  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="spinner" style="display:none;"></div>
<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <a href="index2.html" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>H</b>K</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Home</b>Kitchen</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
<!--          <li class="dropdown messages-menu">
             Menu toggle button 
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success">4</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 4 messages</li>
              <li>
                 inner menu: contains the messages 
                <ul class="menu">
                  <li> start message 
                    <a href="#">
                      <div class="pull-left">
                         User Image 
                        <img src="/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                      </div>
                       Message title and timestamp 
                      <h4>
                        Support Team
                        <small><i class="fa fa-clock-o"></i> 5 mins</small>
                      </h4>
                       The message 
                      <p>Why not buy a new awesome theme?</p>
                    </a>
                  </li>
                   end message 
                </ul>
                 /.menu 
              </li>
              <li class="footer"><a href="#">See All Messages</a></li>
            </ul>
          </li>
           /.messages-menu 

           Notifications Menu 
          <li class="dropdown notifications-menu">
             Menu toggle button 
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning">10</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 10 notifications</li>
              <li>
                 Inner Menu: contains the notifications 
                <ul class="menu">
                  <li> start notification 
                    <a href="#">
                      <i class="fa fa-users text-aqua"></i> 5 new members joined today
                    </a>
                  </li>
                   end notification 
                </ul>
              </li>
              <li class="footer"><a href="#">View all</a></li>
            </ul>
          </li>-->
          <!-- Tasks Menu -->
          <li class="dropdown tasks-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-flag-o"></i>
              <span id="newOrderNotificationCount" class="label label-danger">0</span>
            </a>
            <ul class="dropdown-menu">
              <li id="newOrderNotificationHeading" class="header">You have 0 new orders</li>
              <li>
                <!-- Inner menu: contains the tasks -->
                <ul id="newOrderNotificationListing" class="menu">
                  
                </ul>
              </li>
              <li class="footer">
                <a href="{{url('/chef/orders/listing')}}">View all orders</a>
              </li>
            </ul>
          </li>
          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              @if(isset(Auth::guard('chef')->user()->id))
                  @php
                    $url = url('/img/default/admin.png');
                  @endphp

                  <img src="{{ config('aws.aws_s3_url').'/uploads/chef/profile-pic/'.Auth::guard('chef')->user()->id.'/thumbnails/50x50/'.Auth::guard('chef')->user()->profile_pic.'?'.time() }} " onerror="this.src='{{ $url }}'"  class="img-circle" alt="User Image" width="20" height="20" >
              @endif
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs">{{Auth::guard('chef')->user()->name}}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- The user image in the menu -->
              <li class="user-header">
                  @if(isset(Auth::guard('chef')->user()->id))
                    @php
                      $url = url('/img/default/admin.png');
                    @endphp

                    <img src="{{ config('aws.aws_s3_url').'/uploads/chef/profile-pic/'.Auth::guard('chef')->user()->id.'/thumbnails/200x200/'.Auth::guard('chef')->user()->profile_pic.'?'.time() }} " onerror="this.src='{{ $url }}'"  class="img-circle" alt="User Image" width="20" height="20" >
                  @endif

                <p>
                  {{Auth::guard('chef')->user()->name}} - {{Auth::guard('chef')->user()->role}}
                  <!--<small>Member since Nov. 2012</small>-->
                </p>
              </li>
              <!-- Menu Body -->
<!--              <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                </div>
                 /.row 
              </li>-->
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="{{url('/chef/settings/profile_form')}}" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                    <a href="{{ url('/chef/logout') }}"
                          onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                          class="btn btn-default btn-flat">Sign out
                    </a>
                    <form id="logout-form" action="{{ url('/chef/logout') }}" method="POST" style="display: none;">
                      {{ csrf_field() }}
                    </form>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button  data-toggle="control-sidebar" -->
          <li>
            <a href="{{url('/chef/settings/profile_form')}}"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          @if(isset(Auth::guard('chef')->user()->id))
            @php
              $url = url('/img/default/admin.png');
            @endphp

            <img src="{{ config('aws.aws_s3_url').'/uploads/chef/profile-pic/'.Auth::guard('chef')->user()->id.'/thumbnails/50x50/'.Auth::guard('chef')->user()->profile_pic.'?'.time() }} " onerror="this.src='{{ $url }}'"  class="img-circle" alt="User Image" width="20" height="20" >
          @endif
        </div>
        <div class="pull-left info">
          <p>{{Auth::guard('chef')->user()->name}}</p>
          <!-- Status -->
          <!--<a href="#"><i class="fa fa-circle text-success"></i> Online</a>-->
        </div>
      </div>

      <!-- search form (Optional) -->
<!--      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
              <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
              </button>
            </span>
        </div>
      </form>-->
      <!-- /.search form -->

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">HEADER</li>
        <!-- Optionally, you can add icons to the links -->
        <li class="{{(Request::path() == 'chef/home' ? 'active' : '')}}"><a href="{{url('/chef/home')}}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
        <li class="{{(strstr(Request::path(), 'orders') ? 'active' : '')}}"><a href="{{url('/chef/orders/listing')}}"><i class="fa fa-link"></i> <span>Order Management</span></a></li>
        <!-- <li><a href="#"><i class="fa fa-link"></i> <span>Reviews</span></a></li>
        <li><a href="#"><i class="fa fa-link"></i> <span>Ratings</span></a></li> -->
        <li class="{{(strstr(Request::path(), 'rating') ? 'active' : '')}}"><a href="{{url('/chef/rating/listing')}}"><i class="fa fa-star"></i> <span>Ratings & Reviews</span></a></li>
        <li><a href="#"><i class="fa fa-link"></i> <span>Complaint Management</span></a></li>
        <li class="{{(strstr(Request::path(), 'kitchenmenu/listing') ? 'active' : '')}}"><a href="{{url('/chef/kitchenmenu/listing')}}"><i class="fa fa-link"></i> <span>Kitchen Menu</span></a></li>
        <li class="{{(strstr(Request::path(), 'kitchentiming') ? 'active' : '')}}"><a href="{{url('/chef/kitchentiming/listing')}}"><i class="fa fa-link"></i> <span>Kitchen Timings</span></a></li>
        <li class="{{(strstr(Request::path(), 'group') ? 'active' : '')}}"><a href="{{url('/chef/group/listing')}}"><i class="fa fa-link"></i> <span>Kitchen Item Groups</span></a></li>
        <li class="{{(strstr(Request::path(), 'bulkupload') ? 'active' : '')}}"><a href="{{url('/chef/kitchenmenu/bulkupload')}}"><i class="fa fa-link"></i> <span>Bulk Upload</span></a></li>
        <li class="treeview {{(strstr(Request::path(), 'settings') ? 'active' : '')}} ">
          <a href="#"><i class="fa fa-link"></i>Settings <span></span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{(strstr(Request::path(), 'settings/profile_form') ? 'active' : '')}}"><a href="{{url('/chef/settings/profile_form')}}">Profile</a></li>
            <li class="{{(strstr(Request::path(), 'settings/change_password_form') ? 'active' : '')}}"><a href="{{url('/chef/settings/change_password_form')}}">Change Password</a></li>
          </ul>
        </li>
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        {{$pageMeta['pageName']}}
        <small> {{$pageMeta['pageDes']}}</small>
      </h1>
      <ol class="breadcrumb">
        @foreach ($pageMeta['breadCrumbs'] as $key => $value)
            @if($value == '')
                <li class="active">{{$key}}</li>
            @else
                <li><a href="{{$value}}">{{$key}}</a></li>
            @endif
        @endforeach
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
        
      <!--------------------------
        | Your Page Content Here |
        -------------------------->
        @yield('content')

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
<!--    <div class="pull-right hidden-xs">
      Anything you want
    </div>-->
    <!-- Default to the left -->
    <strong>Copyright &copy; <?= DATE('Y') ?> <a href="#">Easeat</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
<!--  <aside class="control-sidebar control-sidebar-dark">
     Create the tabs 
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
     Tab panes 
    <div class="tab-content">
       Home tab content 
      <div class="tab-pane active" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:;">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
        </ul>
         /.control-sidebar-menu 

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:;">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="pull-right-container">
                    <span class="label label-danger pull-right">70%</span>
                  </span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
        </ul>
         /.control-sidebar-menu 

      </div>
       /.tab-pane 
       Stats tab content 
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
       /.tab-pane 
       Settings tab content 
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>
           /.form-group 
        </form>
      </div>
       /.tab-pane 
    </div>
  </aside>-->
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
  immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
@yield('pagePopups')
<!-- REQUIRED JS SCRIPTS -->

<!-- openOrderMenuModal -->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="openOrderMenuModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="orderMenuTitle"></h4>
            </div>
            <div class="modal-body">
                <!--<p></p>-->
                <div class="adv-table">
                    <table  class="display table table-bordered table-striped" id="dynamic-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Item Name</th>
                                <th>Add Ons</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody id="orderMenuBody">
                            <tr><td colspan="4">No records found!!</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="orderMenuFooter" class="modal-footer">
<!--                <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>-->
            </div>
        </div>
    </div>
</div>
<audio id="audio" src="{{ url('/') }}/longtune.mp3"  ></audio>
<!-- The Base URL in layer. That way it'll show in all pages. -->
<script>
  const baseUrl = "{{ url('/') }}";
</script>
<!-- jQuery 3 -->
<script src="{{url('/')}}/js/jquery/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{url('/')}}/js/bootstrap/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="{{url('/')}}/js/common/adminlte.min.js"></script>
<!-- jQuery valddate js -->
<script src="{{url('/')}}/js/jquery/jquery.validate.min.js"></script>
<!--Page specific js-->

<!-- Toaster js-->
<script src="{{url('/')}}/js/toastr.min.js"></script>
<!-- functions js-->
<script src="{{url('/')}}/js/common/functions.js"></script>
<script src="{{url('/')}}/js/chef/common.js"></script>
<script>
$('form').on('submit', function() {
  if($(this).valid()) {
    $('.spinner').show();
    $('form').submit();
  }
  return false;
});
var APP_ENV = "{{App::environment()}}";
var LOGGED_IN_USER_ID = "{{Auth::guard('chef')->user()->id}}";
var COUNT_ORDERS = 0;
getChefPlacedOrders();
setInterval(function(){
    getChefPlacedOrders();
}, 30000);

/**
 * Get new//pending order for header notifications
 * @param {type} id
 * @returns {undefined}
 */
function getChefPlacedOrders() {
    $('.spinner').show();
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: baseUrl+"/chef/orders/ajax_get_chef_placed_order",
        type: "GET",
        dataType : 'json',
        success: function(response) {
            if(response.status == true) {
                if(response.data.length > COUNT_ORDERS && COUNT_ORDERS != 0) {
                    COUNT_ORDERS = parseInt(response.data.length);
                    playSound(); // play notfication sound
                    toastr.success('New orders arrived');
                } else {
                    if(response.data.length > 0 && COUNT_ORDERS == 0) {
                        playSound(); // play notfication sound
                        toastr.success('New orders arrived');
                    }
                    COUNT_ORDERS = parseInt(response.data.length);
                }
                setNewOrderNotifHTML(response.data.length);
                $('.spinner').hide();
            } else {
                $('.spinner').hide();
            }
        },
        error: function(response) {
            $('.spinner').hide();
            toastr.success('Something went wrong!!!');
        }
    });
}

function setNewOrderNotifHTML(cnt) {
//    var newOrderCnt = $('#newOrderNotificationCount').html();
    var newOrderCnt = parseInt(cnt);
    $('#newOrderNotificationCount').html(newOrderCnt);//set count

    //set notifications headings
    $('#newOrderNotificationHeading').html("You have "+newOrderCnt+" new orders");

    //set order rows in notifications section in header
//    var newOrderHtml = '';
//    $(res).each(function (ind, ele) {
//        newOrderHtml += "<li>";
//        newOrderHtml += "<a href='javascript:void(0);' onclick='openOrderMenuModal("+ele+");'>";
//        newOrderHtml += "<i class='fa fa-cutlery text-aqua'></i>New order arrived : "+ele+"</a>";
//        newOrderHtml += "</li>";
//        newOrderHtml += "</a>";
//        newOrderHtml += "</li>";
//    });
//    
//    $('#newOrderNotificationListing').html(newOrderHtml);   
}
 
</script>
@yield('pageJavascript')
<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/6.3.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/6.3.1/firebase-database.js"></script>
<!-- firebase functions and queries -->
<script src="{{url('/')}}/js/common/firebaseConfig.js"></script>
<script src="{{url('/')}}/js/chef/firebaseQueries.js"></script>
</body>
</html>