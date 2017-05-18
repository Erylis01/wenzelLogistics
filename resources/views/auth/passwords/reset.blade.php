@extends('layouts.default')

@section('title')
    Reset password
@endsection

@section('stylesheet')
    <link href="{{asset('css/auth_home.css')}}" rel="stylesheet" type="text/css">
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2">
            <div class="panel panel-general">
                <div class="panel-heading">Reset Password</div>
                <div class="panel-body panel-body-general">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="form-horizontal" role="form" method="POST" action="{{ route('password.request') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-lg-4 control-label">E-Mail Address</label>

                            <div class="col-lg-6">
                                <input id="email" type="email" class="form-control" name="email"
                                       value="{{ $email or old('email') }}" placeholder="Email" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-lg-4 control-label">Password</label>

                            <div class="col-lg-6">
                                <input id="password" type="password" class="form-control" name="password"
                                       placeholder="Password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password-confirm" class="col-lg-4 control-label">Confirm Password</label>
                            <div class="col-lg-6">
                                <input id="password-confirm" type="password" class="form-control"
                                       name="password_confirmation" placeholder="Password" required>

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-lg-6 col-lg-offset-4">
                                <button type="submit" class="btn btn-primary btn-block btn-form">
                                    Reset Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
