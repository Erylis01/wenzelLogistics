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
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4//css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
@yield('stylesheet')
    <link href="{{asset('css/general.css')}}" rel="stylesheet" type="text/css">

<!-- Scripts -->
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
            <div class="col-lg-2">
                <img class="img-responsive img-logo" src="{{URL::asset('../../wenzel_logistics.png')}}"
                     alt="Wenzel Logistics logo">
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
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu" role="menu">
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
                </li>
            @endif

        </div>
</div>
</nav>

<div class="container">
    @yield('content')
</div>

</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
<!-- Bootstrap core JavaScript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
<script src="../../dist/js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>
