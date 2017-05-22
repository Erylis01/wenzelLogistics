@extends('layouts.default')

@section('title')
    Login
@endsection

@section('stylesheet')
    <link href="{{asset('css/auth_home.css')}}" rel="stylesheet" type="text/css">
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2">
            <div class="panel panel-general">
                <div class="panel-heading">Login</div>
                <div class="panel-body panel-body-general">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <label for="username" class="col-lg-4 control-label">Username</label>

                            <div class="col-lg-6">
                                <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" placeholder="Username" required autofocus>

                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('passwords') ? ' has-error' : '' }}">
                            <label for="password" class="col-lg-4 control-label">Password</label>

                            <div class="col-lg-6">
                                <input id="password" type="password" class="form-control" name="password" placeholder="Password" required>

                                @if ($errors->has('passwords'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group legend-auth">
                            <div class="col-lg-6 col-lg-offset-4">
                                Forgot Your Password ? <a class="link" href="{{ route('password.request') }}">Here</a> a new one.
                            </div>
                        </div>

                        <div class="form-group legend-auth">
                            <div class="col-lg-3 col-lg-offset-4">
                                <button type="submit" class="btn btn-primary btn-block btn-form">
                                    Login
                                </button>
                            </div>
                            <div class="col-lg-3">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group legend-auth" >
                            <div class="col-lg-8 col-lg-offset-4">
                            No account ? Create one <a href="{{ route('register') }}" class="link">here</a>.
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @if (Session::has('messageDelete'))
                <div class="alert alert-info text-center">{{ Session::get('messageDelete') }}</div>
            @endif
        </div>
    </div>
@endsection
