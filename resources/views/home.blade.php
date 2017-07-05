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
@section('classTrucks')
    class="nonActive"
@endsection
@section('classPalletsAccounts')
    class="nonActive"
@endsection
@section('classPalletsTransfers')
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
                    <a href="{{ route('showAllLoadings', ['refresh'=>'false']) }}">
                        <div class="col-lg-2 text-center home">
                            <div class="service-box">
                                <h3 class="menu-title">Loadings</h3>
                                <img class="img-responsive img-home center-block"
                                     src="{{URL::asset('/image/loading.png')}}"
                                     alt="loading image">
                                {{--<p class="text-muted menu-legend">Show all loadings</p>--}}
                            </div>
                        </div>
                    </a>

                    <!-- All warehouses -->
                    <a href="{{ route('showAllWarehouses','false') }}">
                        <div class="col-lg-2 text-center home ">
                            <div class="service-box">
                                <h3 class="menu-title">Warehouses</h3>
                                <img class="img-responsive img-home center-block"
                                     src="{{URL::asset('/image/warehouse2.jpg')}}"
                                     alt="warehouse image">
                                {{--<p class="text-muted menu-legend">Show all warehouses</p>--}}
                            </div>
                        </div>
                    </a>

                    <!-- All trucks -->
                    <a href="{{ route('showAllTrucks', ['refresh'=>'false']) }}">
                        <div class="col-lg-2 text-center home">
                            <div class="service-box">
                                <h3 class="menu-title">Trucks</h3>
                                <img class="img-responsive center-block img-home"
                                     src="{{URL::asset('/image/truck.jpg')}}"
                                     alt="truck image">
                                {{--<p class="text-muted menu-legend">Show all trucks</p>--}}
                            </div>
                        </div>
                    </a>

                    <!-- All pallets accounts -->
                    <a href="{{ route('showAllPalletsaccounts') }}">
                        <div class="col-lg-2 text-center home">
                            <div class="service-box">
                                <h3 class="menu-title">Accounts</h3>
                                <img class="img-responsive img-home center-block"
                                     src="{{URL::asset('/image/account2.jpg')}}"
                                     alt="pallets account image">
                                {{--<p class="text-muted menu-legend">Show all pallets accounts</p>--}}
                            </div>
                        </div>
                    </a>

                    <!-- All pallets transfers -->
                    <a href="{{ route('showAllPalletstransfers') }}">
                        <div class="col-lg-2 text-center home">
                            <div class="service-box">
                                <h3 class="menu-title">Transfers</h3>
                                <img class="img-responsive img-home center-block"
                                     src="{{URL::asset('/image/pallets.png')}}"
                                     alt="pallets transfer image">
                                {{--<p class="text-muted menu-legend">Show all pallets transfers</p>--}}
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
