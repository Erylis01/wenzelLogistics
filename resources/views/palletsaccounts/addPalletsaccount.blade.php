@extends('layouts.default')

@section('title')
    Add pallets account
@endsection

@section('stylesheet')
    <link href="{{asset('css/palletsaccounts.css')}}" rel="stylesheet" type="text/css">
@endsection

@section('classLoadings')
    class="nonActive"
@endsection
@section('classWarehouses')
    class="nonActive"
@endsection
@section('classPalletsAccounts')
    class="active"
@endsection
@section('classPalletsTransfers')
    class="nonActive"
@endsection
@section('classProfile')
    nonActive
@endsection

@section('content')
    <div class="row">
        @if(Auth::guest())
            <h4>You need to login to see the content</h4>
        @else
            <div class="col-lg-14">

                <div class="panel panel-general">

                    <div class="panel-heading"><span class="glyphicon glyphicon-plus-sign"></span> Add a new pallets
                        account
                    </div>
                    <div class="panel-body panel-body-general">
                        <form class="form-horizontal text-right" role="form" method="POST"
                              action="{{route('addPalletsaccount')}}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <!--name-->
                                <div class="col-lg-3">
                                    <label for="name" class="control-label">Name :</label>
                                </div>
                                <div class="col-lg-6">
                                    <input id="name" type="text" class="form-control" name="name"
                                           value="" placeholder="Name" required autofocus>
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <!--number of pallets-->
                                <div class="col-lg-2">
                                    <label for="numberPallets" class="control-label">Pallets Number
                                        :</label>
                                </div>
                                <div class="col-lg-1">
                                    <input id="numberPallets" type="number" class="form-control"
                                           name="numberPallets"
                                           value="0" placeholder="Pallets number"
                                           required autofocus>
                                </div>
                            </div>

                            <div class="form-group">
                                <!--warehouses associated-->
                                <div class="col-lg-3">
                                    <label for="warehousesAssociated" class="control-label">Warehouses
                                        associated
                                        :</label>
                                </div>
                                <div class="col-lg-6">
                                    {{--@if(isset($warehouseA))--}}
                                        {{--<select class="selectpicker show-tick form-control" data-size="5"--}}
                                                {{--data-live-search="true" data-live-search-style="startsWith"--}}
                                                {{--title="Warehouses Associated" name="warehousesAssociated"--}}
                                                {{--multiple>--}}
                                            {{--@foreach($listWarehouses as $warehouse )--}}
                                                {{--@php($list[]=null)--}}
                                                {{--@foreach($warehousesAssociated as $warehouseA)--}}
                                                    {{--@if($warehouse->name==$warehouseA->name)--}}
                                                        {{--@php($option='selected')--}}
                                                        {{--<option {{$option}}>{{$warehouse->name}}</option>--}}
                                                        {{--@php($list[]=$warehouse)--}}
                                                    {{--@endif--}}
                                                {{--@endforeach--}}
                                                {{--@if(!in_array($warehouse, $list))--}}
                                                    {{--<option>{{$warehouse->name}}</option>--}}
                                                {{--@endif--}}
                                            {{--@endforeach--}}
                                        {{--</select>--}}
                                    {{--@else--}}
                                        <select class="selectpicker show-tick form-control" data-size="5"
                                                data-live-search="true" data-live-search-style="startsWith"
                                                title="Warehouses Associated" name="warehousesAssociated[]"
                                                multiple required>
                                            @foreach($listWarehouses as $warehouse )
                                                <option>{{$warehouse->name}}</option>
                                            @endforeach
                                        </select>
                                    {{--@endif--}}
                                </div>
                                <div class="col-lg-3 text-left">
                                    <a href="{{route('showAddWarehouse')}}" class="link"><span
                                                class="glyphicon glyphicon-plus-sign"></span> Add warehouse</a>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-lg-4 col-lg-offset-3">
                                    <input type="submit"
                                           class="btn btn-primary btn-block btn-form"
                                           value="Add"
                                           name="addPalletsaccount">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection