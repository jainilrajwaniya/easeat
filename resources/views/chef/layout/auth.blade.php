<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel Multi Auth Guard') }}</title>
    <!-- Font Awesome -->
<!--    <link rel="stylesheet" href="{{url('/')}}/css/font-awesome/css/font-awesome.min.css">
     Theme style 
    <link rel="stylesheet" href="{{url('/')}}/css/common/AdminLTE.min.css">

    <link rel="stylesheet" href="{{url('/')}}/css/common/skin-blue.min.css">
      Ionicons 
    <link rel="stylesheet" href="{{url('/')}}/css/ionicons/css/ionicons.min.css">-->
    <link rel="stylesheet" href="{{url('/')}}/css/bootstrap/css/bootstrap.min.css">

    <!--main css-->
    <!--<link rel="stylesheet" href="{{url('/')}}/css/main.css">-->

    <!-- Styles -->
    <link href="{{url('/')}}/css/app.css" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body class="hold-transition">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    &nbsp;
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
<!--                        <li><a href="{{ url('/chef/login') }}">Login</a></li>
                        <li><a href="{{ url('/chef/register') }}">Register</a></li>-->
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ url('/chef/logout') }}"
                                        onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ url('/chef/logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- Scripts -->
    <script src="{{url('/')}}/js/app.js"></script>
</body>
</html>
