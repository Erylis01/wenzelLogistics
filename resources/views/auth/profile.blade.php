@extends('layouts.default')

@section('title')
    Login
@endsection

@section('stylesheet')
    <link href="{{asset('css/auth.css')}}" rel="stylesheet" type="text/css">
@endsection

@section('content')

    <div class="col-lg-8 col-lg-offset-2">
        <div class="panel panel-default panel-auth">
            <div class="panel-heading">Profile</div>
            <div class="panel-body panel-body-auth">
                <div class="row">
                    <div class="col-lg-3 col-lg-offset-2">
                        <label class="profile-legend" for="lastname">Lastname : </label>
                    </div>
                    <div class="col-lg-4">
                        {{Auth::user()->lastname}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-lg-offset-2">
                        <label class="profile-legend" for="Firstname">Firstname : </label>
                    </div>
                    <div class="col-lg-4">
                        {{Auth::user()->firstname}}
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-3 col-lg-offset-2">
                        <label class="profile-legend" for="Username">Username : </label>
                    </div>
                    <div class="col-lg-4">
                        {{Auth::user()->username}}
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-3 col-lg-offset-2">
                        <label class="profile-legend" for="Email">Email : </label>
                    </div>
                    <div class="col-lg-4">
                        {{Auth::user()->email}}
                    </div>
                </div>

                {{--<div class="row">--}}
                    {{--<div class="col-lg-3 col-lg-offset-2">--}}
                        {{--<label class="profile-legend" for="Password">Password : </label>--}}
                    {{--</div>--}}
                    {{--<div class="col-lg-4">--}}
                        {{--{{Auth::user()->password}}--}}
                    {{--</div>--}}
                {{--</div>--}}
            </div>
        </div>
    </div>
@endsection