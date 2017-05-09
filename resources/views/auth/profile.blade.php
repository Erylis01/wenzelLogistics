@extends('layouts.default')

@section('title')
    Login
@endsection

@section('stylesheet')
    <link href="{{asset('css/auth.css')}}" rel="stylesheet" type="text/css">
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2">
            <div class="panel panel-default panel-auth">
                <div class="panel-heading">Profile</div>
                <div class="panel-body panel-body-auth">
                    <form class="form-horizontal" role="form" method="POST" action="">
                    {{ csrf_field() }}

                    <!--lastname-->
                        <div class="form-group {{ $errors->has('lastname') ? ' has-error' : '' }}">
                            <label for="lastname" class="col-lg-4 control-label">Lastname :</label>
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
                            <label for="firstname" class="col-lg-4 control-label">Firstname :</label>

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
                                <div type="button" data-toggle="modal" data-target="#username_modal"
                                     class="glyphicon glyphicon-info-sign link"></div>
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
                            <label for="email" class="col-lg-4 control-label">E-Mail Address :</label>

                            <div class="col-lg-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ $email }}"
                                       placeholder="Email" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!--password-->
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-lg-4 control-label">Password :</label>
                            <div class="col-lg-6">
                                <a class="profile-link" href="{{ route('password.email') }}">Reset here</a>
                            </div>
                        </div>

                        <!--buttons-->
                        <div class="container-fluid  text-center">
                            <div class="row">
                                <div class="col-lg-3 col-lg-offset-4">
                                    <button type="button" class="btn btn-primary btn-block btn-form" data-toggle="modal"
                                            data-target="#update_modal">Update
                                    </button>
                                </div>
                                <div class="col-lg-3">
                                    <button type="button" class="btn btn-primary btn-block btn-form" data-toggle="modal"
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
                                            {{ csrf_field() }}

                                            <div class="col-lg-offset-3">
                                                <button type="submit" class="btn btn-danger btn-modal" value="yes"
                                                        name="update">
                                                    Yes
                                                </button>
                                                <button type="button" class="btn btn-success btn-modal"
                                                        data-dismiss="modal">No
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default btn-modal" data-dismiss="modal">
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
                                        <button type="button" class="btn btn-default btn-modal" data-dismiss="modal">
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
                <div class="alert alert-info text-center">{{ Session::get('messageUpdate') }}</div>
            @endif
        </div>
    </div>
@endsection