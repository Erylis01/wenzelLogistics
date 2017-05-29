@extends('layouts.default')

@section('title')
    Home
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
    nonActive
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-14">
            <div class="panel panel-general">
                <div class="panel-heading">Welcome {{Auth::user()->username}}</div>

                <div class="panel-body panel-body-general">
                    <p class="text-center">What do you want to do ?</p>

                    <!-- All loadings -->
                    <a href="{{ route('showAllLoadings') }}">
                        <div class="col-lg-3 text-center ">
                            <div class="service-box">
                                <h3 class="menu-title">Loadings</h3>
                                <img class="img-responsive img-home center-block"
                                     src="{{URL::asset('/image/loading.png')}}"
                                     alt="loading image">
                                <p class="text-muted menu-legend">Show all loadings</p>
                            </div>
                        </div>
                    </a>

                    <!-- All warehouses -->
                    <a href="{{ route('showAllWarehouses') }}">
                        <div class="col-lg-3 text-center ">
                            <div class="service-box">
                                <h3 class="menu-title">Warehouses</h3>
                                <img class="img-responsive img-home center-block"
                                     src="{{URL::asset('/image/warehouse2.jpg')}}"
                                     alt="warehouse image">
                                <p class="text-muted menu-legend">Show all warehouses</p>
                            </div>
                        </div>
                    </a>

                    <!-- All pallets accounts -->
                    <a href="{{ route('showAllPalletsaccounts') }}">
                        <div class="col-lg-3 text-center ">
                            <div class="service-box">
                                <h3 class="menu-title">Pallets Accounts</h3>
                                <img class="img-responsive img-home center-block"
                                     src="{{URL::asset('/image/account2.jpg')}}"
                                     alt="warehouse image">
                                <p class="text-muted menu-legend">Show all pallets accounts</p>
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
