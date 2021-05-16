@extends('admin.layout.auth')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">

                    <div align="center" style="font-size: 2.5em;">
                        <img style="height:125px;" src="{{url('/img/default/logo.png')}}" >
                        <b>Easeat</b>
                    </div>

                    <form class="form-horizontal" role="form" method="POST" action="{{url('/admin/login')}}">
                        {{ csrf_field() }}
                        <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">E-Mail Address</label>
                            <div class="col-md-6">
                                <input id="email" type="email" placeholder="Email" class="form-control" name="email" value="{{ old('email') }}" autofocus>
                                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                                @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Password</label>
                            <div class="col-md-6">
                                <input id="password" placeholder="Password" type="password" class="form-control" name="password">
                                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <!--                <div class="col-xs-8">
                                                <div class="checkbox icheck">
                                                    <label>
                                                        <input type="checkbox" name="remember"> Remember Me
                                                    </label>
                                                </div>
                                            </div>-->
                            <!-- /.col -->
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-sign-in"></i>
                                    Login
                                </button>
                                <a class="btn btn-link" href="{{url('/')}}/admin/password/reset">
                                    Forgot Your Password?
                                </a>
                            </div>
                            <!-- /.col -->
                        </div>
                    </form>
                </div>      
            </div>      
        </div>
    </div>
</div>
<!-- /.login-box -->
@endsection
