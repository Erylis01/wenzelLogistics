@extends('layouts.default')

@section('title')
    Logout
@endsection

@section('stylesheet')
    <link href="{{asset('css/auth.css')}}" rel="stylesheet" type="text/css">
@endsection

@section('content')

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">
                    You are logged out!
                </div>
            </div>
        </div>
    </div>

@endsection