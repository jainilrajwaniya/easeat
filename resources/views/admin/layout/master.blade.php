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
  
  <!--<link rel="stylesheet" href="{{url('/')}}/css/common/skin-blue.min.css">-->
  <link rel="stylesheet" href="{{url('/')}}/css/common/skin-purple.min.css">
   <!-- Ionicons -->
  <link rel="stylesheet" href="{{url('/')}}/css/ionicons/css/ionicons.min.css">
  <link rel="stylesheet" href="{{url('/')}}/css/bootstrap/css/bootstrap.min.css">
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

<body class=" hold-transition skin-purple sidebar-mini">
    <div class="spinner" style="display:none;"></div>
<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <a href="index2.html" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>E</b>2</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Ease</b>at</span>
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
          </li>
           Tasks Menu 
          <li class="dropdown tasks-menu">
             Menu Toggle Button 
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-flag-o"></i>
              <span class="label label-danger">9</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 9 tasks</li>
              <li>
                 Inner menu: contains the tasks 
                <ul class="menu">
                  <li> Task item 
                    <a href="#">
                       Task title and progress text 
                      <h3>
                        Design some buttons
                        <small class="pull-right">20%</small>
                      </h3>
                       The progress bar 
                      <div class="progress xs">
                         Change the css width attribute to simulate progress 
                        <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar"
                             aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">20% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>
                   end task item 
                </ul>
              </li>
              <li class="footer">
                <a href="#">View all tasks</a>
              </li>
            </ul>
          </li>-->
          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              @if(isset(Auth::guard('admin')->user()->id))
                  @php
                      $url = url('/img/default/admin.png');
                  @endphp

                  <img src="{{ config('aws.aws_s3_url').'/uploads/admin/profile-pic/'.Auth::guard('admin')->user()->id.'/thumbnails/50x50/'.Auth::guard('admin')->user()->profile_pic.'?'.time() }} " onerror="this.src='{{ $url }}'"  class="img-circle" alt="User Image" width="20" height="20" >
          
              @endif
              
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs">{{Auth::guard('admin')->user()->name}}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- The user image in the menu -->
              <li class="user-header">
                @if(isset(Auth::guard('admin')->user()->id))
                  @php
                      $url = url('/img/default/admin.png');
                  @endphp

                  <img src="{{ config('aws.aws_s3_url').'/uploads/admin/profile-pic/'.Auth::guard('admin')->user()->id.'/thumbnails/200x200/'.Auth::guard('admin')->user()->profile_pic.'?'.time() }} " onerror="this.src='{{ $url }}'"  class="img-circle" alt="User Image" width="20" height="20" >
                @endif

                <p>
                  {{Auth::guard('admin')->user()->name}} - {{Auth::guard('admin')->user()->role}}
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
                  <a href="{{url('/admin/settings/profile_form')}}" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                    <a href="{{url('/')}}/admin/logout"
                          onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                          class="btn btn-default btn-flat">Sign out
                    </a>
                    <form id="logout-form" action="{{url('/')}}/admin/logout" method="POST" style="display: none;">
                      {{ csrf_field() }}
                    </form>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="{{url('/admin/settings/profile_form')}}"><i class="fa fa-gears"></i></a>
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
          @if(isset(Auth::guard('admin')->user()->id))
            @php
              $url = url('/img/default/admin.png');
            @endphp

            <img src="{{ config('aws.aws_s3_url').'/uploads/admin/profile-pic/'.Auth::guard('admin')->user()->id.'/thumbnails/50x50/'.Auth::guard('admin')->user()->profile_pic.'?'.time() }} " onerror="this.src='{{ $url }}'"  class="img-circle" alt="User Image" width="20" height="20" >
          @endif
        </div>
        <div class="pull-left info">
          <p>{{Auth::guard('admin')->user()->name}}</p>
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
        <li class="{{(Request::path() == 'admin/home' ? 'active' : '')}}"><a href="{{url('/admin/home')}}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
        <li class="{{(strstr(Request::path(), 'subadmin') ? 'active' : '')}}"><a href="{{url('/admin/subadmin/listing')}}"><i class="fa fa-link"></i> <span>SubAdmin Management</span></a></li>
        <li class="{{(strstr(Request::path(), '/user/') ? 'active' : '')}}"><a href="{{url('/admin/user/listing')}}"><i class="fa fa-users"></i> <span>User Management</span></a></li>
        <li class="{{(strstr(Request::path(), '/rating/') ? 'active' : '')}}"><a href="{{url('/admin/rating/listing')}}"><i class="fa fa-star"></i> <span>Ratings & Reviews</span></a></li>
<!--        <li class="treeview">
          <a href="#"><i class="fa fa-link"></i>aaa <span></span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="/rating/listing">Ratings Management</a></li>
            <li><a href="#">Reviews Management</a></li>
          </ul>
        </li>-->
        <li class="{{(strstr(Request::path(), '/category/') ? 'active' : '')}}"><a href="{{url('/admin/category/listing')}}"><i class="fa fa-cutlery"></i> <span>Category Management</span></a></li>
        <li class="{{(strstr(Request::path(), '/cuisine/') ? 'active' : '')}}"><a href="{{url('/admin/cuisine/listing')}}"><i class="fa fa-lemon-o"></i> <span>Cuisine Management</span></a></li>
        <li class="{{(strstr(Request::path(), '/region/') ? 'active' : '')}}"><a href="{{url('/admin/region/listing')}}"><i class="fa fa-globe"></i> <span>Region Management</span></a></li>
        <li class="{{(strstr(Request::path(), '/chef/') ? 'active' : '')}}"><a href="{{url('/admin/chef/listing')}}"><i class="fa fa-delicious"></i> <span>Chef Management</span></a></li>
        <!-- <li><a href="#"><i class="fa fa-link"></i> <span>Promo Code Management</span></a></li> -->
        <li class="treeview {{(strstr(Request::path(), '/promocode/') ? 'active' : '')}}">
          <a href="#"><i class="fa fa-link"></i>Promo Code Management <span></span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{(strstr(Request::path(), 'promocode/listing') ? 'active' : '')}}"><a href="{{url('/admin/promocode/listing')}}">Promocode Listing</a></li>
            <li class="{{(strstr(Request::path(), 'promocode/kitchen/listing') ? 'active' : '')}}"><a href="{{url('/admin/promocode/kitchen/listing')}}">Assign Promocode</a></li>
          </ul>
        </li>
        <li class="{{(strstr(Request::path(), '/wallet/') ? 'active' : '')}}"><a href="{{url('/admin/wallet/listing')}}"><i class="fa fa-google-wallet"></i> <span>Wallet Management</span></a></li>
        <li class="{{(strstr(Request::path(), '/orders/') ? 'active' : '')}}"><a href="{{url('/admin/orders/listing')}}"><i class="fa fa-link"></i> <span>Order Management</span></a></li>
        <!--<li><a href="#"><i class="fa fa-link"></i> <span>Chef</span></a></li>-->
        <!--<li><a href="#"><i class="fa fa-bars"></i> <span>Chef Menu</span></a></li>-->
        <li class="{{(strstr(Request::path(), '/log-activity/') ? 'active' : '')}}"><a href="{{url('/admin/log-activity/listing')}}"><i class="fa fa-th-list"></i> <span>Log Activities</span></a></li>
        <!-- <li><a href="{{url('/admin/settings/change_password_form')}}"><i class="fa fa-cogs"></i> <span>Settings, change pass, profile</span></a></li> -->
        <li class="treeview {{(strstr(Request::path(), '/settings/') ? 'active' : '')}}">
          <a href="#"><i class="fa fa-link"></i>Settings <span></span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{(Request::path() == 'admin/settings/profile_form' ? 'active' : '')}}"><a href="{{url('/admin/settings/profile_form')}}">Profile</a></li>
            <li class="{{(Request::path() == 'admin/settings/change_password_form' ? 'active' : '')}}"><a href="{{url('/admin/settings/change_password_form')}}">Change Password</a></li>
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
<!-- Toaster js-->
<script src="{{url('/')}}/js/toastr.min.js"></script>
<!-- functions js-->
<script src="{{url('/')}}/js/common/functions.js"></script>
<!--Page specific js-->
<script>
$('form').on('submit', function() {
  if($(this).valid()) {
    $('.spinner').show();
    $('form').submit();
  }
  return false;
});
</script>
@yield('pageJavascript')


</body>
</html>