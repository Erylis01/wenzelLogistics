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
@section('classTrucks')
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
                            <p class="text-center legend-auth">* required field</p>
                            <div class="form-group">
                                <!--name-->
                                <div class="col-lg-3">
                                    <label for="name" class="control-label"><span>*</span> Name :</label>
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
                            </div>
                            <div class="form-group">
                                <!--type-->
                                <div class="col-lg-3">
                                    <label for="type" class="control-label"><span>*</span> Type :</label>
                                </div>
                                <div class="col-lg-3">
                                    <!-- if mistake in the adding form you are redirected with field already filled-->
                                        <select class="selectpicker show-tick form-control" data-size="5"
                                                data-live-search="true" data-live-search-style="startsWith"
                                                title="Type" name="type"
                                                required>
                                            @if(Illuminate\Support\Facades\Input::old('type'))
                                                <option @if(old('type') == 'Truck') selected @endif>Carrier</option>
                                                <option @if(old('type') == 'Other') selected @endif>Other</option>
                                                <option @if(old('type') == 'Warehouse') selected @endif>Network</option>
                                       @else
                                                <option>Carrier</option>
                                                <option>Other</option>
                                                <option>Network</option>
                                            @endif
                                        </select>
                                </div>
                                <!--number of pallets-->
                                <div class="col-lg-2">
                                    <label for="realNumberPallets" class="control-label">Pallets Number
                                        :</label>
                                </div>
                                <div class="col-lg-1">
                                    <input id="realNumberPallets" type="number" class="form-control"
                                           name="realNumberPallets"
                                           value="0" placeholder="Pallets number"
                                           autofocus>
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
                                    <select class="selectpicker show-tick form-control" data-size="5"
                                                data-live-search="true" data-live-search-style="startsWith"
                                                title="Warehouses Associated" name="warehousesAssociated[]"
                                                multiple>
                                            @foreach($listWarehouses as $warehouse )
                                                @php($list[]=null)
                                                @if(Illuminate\Support\Facades\Input::old('warehousesAssociated'))
                                                    @foreach(old('warehousesAssociated') as $warehouseA)
                                                    @if($warehouseA == $warehouse->name)
                                                        <option  selected>{{$warehouse->name}}</option>
                                                        @php($list[]=$warehouse)
                                                    @endif
                                                    @endforeach
                                                    @if(!in_array($warehouse, $list))
                                                        <option>{{$warehouse->name}}</option>
                                                    @endif
                                                @else
                                                    <option>{{$warehouse->name}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                </div>
                                <div class="col-lg-3 text-left">
                                    <a href="{{route('showAddWarehouse')}}" class="link"><span
                                                class="glyphicon glyphicon-plus-sign"></span> Add warehouse</a>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-lg-4 col-lg-offset-4">
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