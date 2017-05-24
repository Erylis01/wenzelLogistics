@extends('layouts.default')

@section('title')
    Profile
@endsection

@section('stylesheet')
    <link href="{{asset('css/auth_home.css')}}" rel="stylesheet" type="text/css">
@endsection

@section('classLoadings')
    class="nonActive"
@endsection
@section('classWarehouses')
    class="nonActive"
@endsection
@section('classPalletsAccounts')
    class="nonActive"
@endsection
@section('classProfile')
    active
@endsection

@section('content')
    <div class="row">
        @if(Auth::guest())
            <h4>You need to login to see the content</h4>
        @else
            <div class="col-lg-8 col-lg-offset-2">
                @if (Session::has('messageRegistration'))
                    <div class="alert alert-info">{{ Session::get('messageRegistration') }}</div>
                @endif
                <div class="panel panel-general">
                    <div class="panel-heading">Profile</div>
                    <div class="panel-body panel-body-general">
                        <form class="form-horizontal" role="form" method="POST" action="">
                            {{ csrf_field() }}
                            <p class="text-center legend-auth">* required field</p>

                            <!--lastname-->
                            <div class="form-group {{ $errors->has('lastname') ? ' has-error' : '' }}">
                                <label for="lastname" class="col-lg-4 control-label"><span>*</span> Lastname :</label>
                                <div class="col-lg-6">
                                    <input id="lastname" type="text" class="form-control" name="lastname"
                                           value="{{ $lastname }}" placeholder="Lastname" required autofocus>

                                    @if ($errors->has('lastname'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('lastname') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>


                            <!--firstname-->
                            <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
                                <label for="firstname" class="col-lg-4 control-label"><span>*</span> Firstname :</label>

                                <div class="col-lg-6">
                                    <input id="firstname" type="text" class="form-control" name="firstname"
                                           value="{{ $firstname }}" placeholder="Firstname" required autofocus>

                                    @if ($errors->has('firstname'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('firstname') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <!--username-->
                            <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                <label for="username" class="col-lg-4 control-label">
                                    <div data-toggle="modal" data-target="#username_modal"
                                         class="glyphicon glyphicon-info-sign link"></div>
                                    <span>*</span>
                                    Username :</label>

                                <div class="col-lg-6">
                                    <input id="username" type="text" class="form-control" name="username"
                                           value="{{ $username }}" placeholder="Username" required autofocus>

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

                            <!--email-->
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-lg-4 control-label"><span>*</span> E-Mail Address
                                    :</label>

                                <div class="col-lg-6">
                                    <input id="email" type="email" class="form-control" name="email"
                                           value="{{ $email }}"
                                           placeholder="Email" required>

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <!-------------------------------------------------------------------------------->

                            <!--password-->
                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-lg-4 control-label">Actual
                                    password :</label>

                                <div class="col-lg-6">
                                    <input id="password" type="password" class="form-control"
                                           name="password" placeholder="Actual password">
                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('new_password') ? ' has-error' : '' }}">
                                <label for="new_password" class="col-lg-4 control-label">New
                                    password :</label>

                                <div class="col-lg-6">
                                    <input id="new_password" type="password" class="form-control"
                                           name="new_password" placeholder="New password">
                                    @if ($errors->has('new_password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('new_password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="new_password_confirmation" class="col-lg-4 control-label">Confirm
                                    password :</label>
                                <div class="col-lg-6">
                                    <input id="new_password_confirmation" type="password"
                                           class="form-control" name="new_password_confirmation"
                                           placeholder="Password confirmation">
                                </div>
                            </div>

                            <!--buttons-->
                            <div class="container-fluid  text-center">
                                <div class="row">
                                    <div class="col-lg-3 col-lg-offset-4">
                                        <button type="button" class="btn btn-primary btn-block btn-form"
                                                data-toggle="modal"
                                                data-target="#update_modal">Update
                                        </button>
                                    </div>
                                    <div class="col-lg-3">
                                        <button type="button" class="btn btn-primary btn-block btn-form"
                                                data-toggle="modal"
                                                data-target="#delete_modal">Delete
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal update -->
                            <div class="modal fade" id="update_modal" role="dialog">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Are you sure to update your profile ?</h4>
                                        </div>
                                        <div class="modal-body center">
                                            <form method="post" action="{{ route('updateProfile') }}">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <div class="col-lg-offset-3">
                                                    <input type="submit" class="btn btn-danger btn-modal" value="Yes"
                                                           name="update">

                                                    <button type="button" class="btn btn-success btn-modal"
                                                            data-dismiss="modal">No
                                                        .
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default btn-modal"
                                                    data-dismiss="modal">
                                                Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Delete -->
                            <div class="modal fade" id="delete_modal" role="dialog">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Are you sure to delete your profile ?</h4>
                                        </div>
                                        <div class="modal-body center">
                                            <form method="post" action="{{route('destroyProfile')}}">
                                                <input type="hidden" name="_method" value="delete">
                                                {{ csrf_field() }}
                                                <div class="col-lg-offset-3">
                                                    <button type="submit" class="btn btn-danger btn-modal" value="yes"
                                                            name="delete">
                                                        Yes
                                                    </button>
                                                    <button type="button" class="btn btn-success btn-modal"
                                                            data-dismiss="modal">No
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default btn-modal"
                                                    data-dismiss="modal">
                                                Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @if (Session::has('messageUpdate'))
                    <div class="alert alert-success text-alert text-center">{{ Session::get('messageUpdate') }}</div>
                @elseif(Session::has('messageUpdatePassword'))
                    <div class="alert alert-success text-alert text-center">{{ Session::get('messageUpdatePassword') }}</div>
                @endif
            </div>
        @endif
    </div>
@endsection