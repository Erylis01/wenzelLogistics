@extends('layouts.default')

@section('title')
    Home
@endsection

@section('stylesheet')
    <link href="{{asset('css/auth.css')}}" rel="stylesheet" type="text/css">
@endsection

@section('classLoadings')
    class="nonActive"
@endsection
@section('classProfile')
    nonActive
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-14">
            <div class="panel panel-auth">
                <div class="panel-heading">Welcome {{Auth::user()->username}}</div>

                <div class="panel-body panel-body-auth">
                    <p class="text-center">What do you want to do ?</p>

                    <!-- All loadings -->
                    <a href="{{ route('showAllLoadings') }}">
                        <div class="col-lg-3 text-center ">
                            <div class="service-box">
                                <h3 class="menu-title">Loadings</h3>
                                <img class="img-responsive center-block"
                                     src="{{URL::asset('/image/details_loading.jpg')}}"
                                     alt="loading image">
                                <p class="text-muted menu-legend">Show all loadings</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @if(Auth::guest())
        You need to login to see the content >>
    @endif

@endsection
