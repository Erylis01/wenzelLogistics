@extends('layouts.default')

@section('title')
    Register
@endsection

@section('stylesheet')
    <link href="{{asset('css/auth_home.css')}}" rel="stylesheet" type="text/css">
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2">
            <div class="panel panel-general">
                <div class="panel-heading">Register</div>
                <div class="panel-body panel-body-general">
                    <form class="form-horizontal" role="form" method="POST" action="{{route('fillDolibarr')}}">
                    {{ csrf_field() }}

                    <!-- button -->
                        <div class="form-group">
                            <label for="lastname" class="col-lg-4 control-label">Fill with existing profile</label>
                            <div class="col-lg-offset-1 col-lg-3">
                                @if (Session::has('dolibarr'))
                                <button type="button" data-toggle="modal"
                                       data-target="#fillDolibarr_modal" class="btn btn-block"
                                        name="fillDolibarr" ><img src="../../public/image/dolibarr_logo.png" alt="dolibarr" class="img-responsive"></button>
@else
                                    <button type="button" data-toggle="modal"
                                            data-target="#fillDolibarr_modal" class="btn btn-block"
                                            name="fillDolibarr" ><img src="../public/image/dolibarr_logo.png" alt="dolibarr" class="img-responsive"></button>
                                @endif
                            </div>
                            <!-- modal fillDolibarr-->
                            <div class="modal fade" id="fillDolibarr_modal"
                                 role="dialog">
                                <div class="modal-dialog modal-md">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button"
                                                    class="close"
                                                    data-dismiss="modal">
                                                &times;
                                            </button>
                                            <h4 class="modal-title">Fill the registration with your Dolibarr
                                                profile</h4>
                                        </div>
                                        <div class="modal-body">
                                            <form role="form"
                                                  method="POST"
                                                  action="{{route('fillDolibarr')}}">
                                                <input type="hidden"
                                                       name="_token"
                                                       value="{{ csrf_token() }}">
                                                <div class="form-group">
                                                    <label for="username" class="col-lg-4 control-label"><span
                                                                 data-toggle="modal"
                                                                data-target="#usernameDolibarr_modal"
                                                                class="glyphicon glyphicon-info-sign link"></span>
                                                        Username</label>

                                                    <div class="col-lg-6">
                                                        <input type="text" class="form-control" name="username" value=""
                                                               placeholder="Username" required autofocus>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="col-lg-6 col-lg-offset-4">
                                                    <input type="submit"
                                                           class="btn btn-success btn-modal btn-block"
                                                           name="fillDolibarrValidate"
                                                           value="Validate">
                                                </div>
                                                </div>

                                                <!-- Modal username -->
                                                <div class="modal fade" id="usernameDolibarr_modal" role="dialog">
                                                    <div class="modal-dialog modal-sm">
                                                        <div class="modal-content">
                                                            <div class="modal-body center">
                                                                <h4 class="modal-title">= your login on Dolibarr</h4>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default"
                                                                        data-dismiss="modal">Close
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <form class="form-horizontal" role="form" method="POST" action="{{ route('register') }}">
                    {{ csrf_field() }}

                    <!--username-->
                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <label for="username" class="col-lg-4 control-label">
                                <span data-toggle="modal" data-target="#username_modal"
                                     class="glyphicon glyphicon-info-sign link"></span>
                                Username</label>

                            <div class="col-lg-6">
                                @if (Session::has('dolibarr'))
                                    <input id="username" type="text" class="form-control" name="username"
                                           value="{{ $username }}" placeholder="Username" required autofocus>
                                @else
                                    <input id="username" type="text" class="form-control" name="username"
                                           value="{{ old('username') }}" placeholder="Username" required autofocus>
                                @endif

                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Modal username -->
                        <div class="modal fade" id="username_modal" role="dialog">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-body center">
                                        <h4 class="modal-title">Used when you sign in</h4>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--lastname-->
                        <div class="form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
                            <label for="lastname" class="col-lg-4 control-label">Lastname</label>

                            <div class="col-lg-6">
                                @if (Session::has('dolibarr'))
                                    <input id="lastname" type="text" class="form-control" name="lastname"
                                           value="{{$lastname}}" placeholder="Lastname" required autofocus>
                                @else
                                    <input id="lastname" type="text" class="form-control" name="lastname"
                                           value="{{ old('lastname')}}" placeholder="Lastname" required autofocus>
                                @endif

                                @if ($errors->has('lastname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('lastname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!--firstname-->
                        <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
                            <label for="firstname" class="col-lg-4 control-label">Firstname</label>

                            <div class="col-lg-6">
                                @if (Session::has('dolibarr'))
                                    <input id="firstname" type="text" class="form-control" name="firstname"
                                           value="{{ $firstname }}" placeholder="Firstname" required autofocus>
                                @else
                                    <input id="firstname" type="text" class="form-control" name="firstname"
                                           value="{{ old('firstname') }}" placeholder="Firstname" required autofocus>
                                @endif

                                @if ($errors->has('firstname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('firstname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!--email-->
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-lg-4 control-label">E-Mail Address</label>

                            <div class="col-lg-6">
                                @if (Session::has('dolibarr'))
                                    <input id="email" type="email" class="form-control" name="email"
                                           value="{{ $email }}" placeholder="Email" required>
                                @else
                                    <input id="email" type="email" class="form-control" name="email"
                                           value="{{ old('email') }}" placeholder="Email" required>
                                @endif

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!--password-->
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

                        <div class="form-group">
                            <label for="password_confirmation" class="col-lg-4 control-label">Confirm Password</label>
                            <div class="col-lg-6">
                                <input id="password_confirmation" type="password" class="form-control"
                                       name="password_confirmation" placeholder="Password" required>
                            </div>
                        </div>

                        <!-- button -->
                        <div class="form-group">
                            <div class="col-lg-6 col-lg-offset-4">
                                <input type="submit" class="btn btn-primary btn-block btn-form" name="Register" value="Register">
                            </div>
                        </div>
                    </form>
@php(session()->pull('dolibarr'))
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
