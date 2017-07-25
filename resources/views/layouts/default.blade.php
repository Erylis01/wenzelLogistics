<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Bootstrap core CSS -->
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template -->
    @yield('stylesheet')
    <link href="{{asset('css/general.css')}}" rel="stylesheet" type="text/css">


    <!-- Scripts -->
    @yield('scriptBegin')
    <script>
        window.Laravel = {!! json_encode([
                'csrfToken' => csrf_token(),
        ]) !!};
    </script>

</head>
<body>
<div id="app">
    <nav class="navbar navbar-default navbar-static-top navbar-title">

        <div class="container-fluid">
            <div class="col-lg-2 navbar-logo">
                <a href="{{route('home')}}"> <img class="img-responsive img-logo"
                                                  src="{{URL::asset('/image/wenzel_logistics.png')}}"
                                                  alt="Wenzel Logistics logo"></a>

            </div>

            <!-- Authentication Links -->
            @if (Auth::guest())
                <div class="col-lg-1 col-lg-offset-8 text-center ">
                    <a class="navbar-title-link" href="{{ route('login') }}">Login</a>
                </div>
                <div class="col-lg-1 text-center">
                    <a class="navbar-title-link" href="{{ route('register') }}">Register</a>
                </div>
            @else
                <div class="col-lg-1 text-center navbar-loading navbar-title-link">
                    <a @yield('classLoadings') href="{{ route('showAllLoadings', ['refresh'=>'false']) }}">Loadings</a>
                </div>
                <div class="col-lg-2 text-center navbar-warehouse navbar-title-link">
                    <a @yield('classWarehouses') href="{{ route('showAllWarehouses', 'false') }}">Warehouses</a>
                </div>
                <div class="col-lg-2 text-center navbar-truck  navbar-title-link dropdown">
                    <a class="dropdown-toggle @yield('classTrucks')" data-toggle="dropdown">Trucks <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('showAllTrucks', ['refresh'=>'false','nb'=>'all']) }}">All</a></li>
                        <li><a  href="{{ route('showAllTrucks', ['refresh'=>'false', 'nb'=>'debt only']) }}">Debt only</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 text-center navbar-accounts navbar-title-link dropdown">
                    <a class="dropdown-toggle @yield('classPalletsAccounts')" data-toggle="dropdown">Pallets Accounts <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('showAllPalletsaccounts', ['nb'=>'all']) }}">All</a></li>
                        <li><a  href="{{ route('showAllPalletsaccounts', ['nb'=>'debt only']) }}">Debt only</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 text-center navbar-transfers navbar-title-link dropdown">
                    <a class="dropdown-toggle @yield('classPalletsTransfers')" data-toggle="dropdown">Pallets Transfers <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('showAllPalletstransfers', ['nb'=>'all']) }}">All</a></li>
                        <li><a  href="{{ route('showAllPalletstransfers', ['nb'=>'debt']) }}">Debt</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-lg-offset-1 text-center dropdown">
                    <a href="#" class="dropdown-toggle navbar-title-link @yield('classProfile') " data-toggle="dropdown"
                       role="button"
                       aria-expanded="false">
                        {{ Auth::user()->username }} <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href="{{ route('showProfile') }}">Profile <span class="glyphicon glyphicon-user "></span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                Logout
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                  style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </div>
            @endif

        </div>
    </nav>
</div>

<div class="container">
    @yield('content')
</div>

</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
<!-- Bootstrap core JavaScript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="{{ asset('js/jquery-3.2.1.js') }}"></script>
<link rel="stylesheet" href="{{asset('css/bootstrap-select.min.css')}}"/>
<script src="{{ asset('js/bootstrap-select.js') }}"></script>
@yield('scriptEnd')
</body>
</html>
