@extends('layouts.default')

@section('title')
    Login
@endsection

@section('stylesheet')
    <link href="{{asset('css/auth.css')}}" rel="stylesheet" type="text/css">
@endsection
@section('content')

    <div class="row">
        <div class="col-lg-14">
            <div class="panel panel-default panel-auth">
                <div class="panel-heading">Welcome</div>

                <div class="panel-body panel-body-auth">
                    <p class="text-center">What do you want to do ?</p>

                    <!-- All pallets -->
                    <a href="{{ route('showAllPallets') }}">
                        <div class="col-lg-3 text-center ">
                            <div class="service-box">
                                <h3 class="menu-title">Pallets</h3>
                                <img class="img-responsive"
                                     src="{{URL::asset('/image/pallets.png')}}"
                                     alt="pallets image">
                                <p class="text-muted menu-legend">Show all pallets</p>
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
