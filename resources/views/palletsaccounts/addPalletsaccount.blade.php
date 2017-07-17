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
    active
@endsection
@section('classPalletsTransfers')
    class="nonActive"
@endsection
@section('classProfile')
    nonActive
@endsection

@section('scriptBegin')
    <script type="text/javascript" src="{{asset('js/addUpdatePalletsaccount.js')}}"></script>
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
                              action="{{route('addPalletsaccount')}}" id="formAddPalletsaccount">
                            {{ csrf_field() }}
                            <input type="hidden" name="actionAddForm" id="actionAddForm"/>
                            <p class="text-center legend-auth">* required field</p>
                            <div class="form-group">
                                <!--name-->
                                <div class="col-lg-3">
                                    <label for="name" class="control-label">*Name :</label>
                                </div>
                                <div class="col-lg-6">
                                    <input id="name" type="text" class="form-control" name="name"
                                           value="{{old('name')}}" placeholder="Name" required autofocus>
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <!--nickname-->
                                <div class="col-lg-3">
                                    <label for="nickname" class="control-label">Nickname :</label>
                                </div>
                                <div class="col-lg-6">
                                    <input id="nickname" type="text" class="form-control" name="nickname"
                                           value="{{old('nickname')}}" placeholder="Nickname" autofocus>
                                    @if ($errors->has('nickname'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('nickname') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <!--type-->
                                <div class="col-lg-3">
                                    <label for="type" class="control-label">*Type :</label>
                                </div>
                                <div class="col-lg-2">
                                    <!-- if mistake in the adding form you are redirected with field already filled-->
                                    <select class="selectpicker show-tick form-control" data-size="5"
                                            data-live-search="true" data-live-search-style="startsWith"
                                            title="Type" name="type" id="type" onchange="displayFields(this);"
                                            required>
                                        @if(Illuminate\Support\Facades\Input::old('type'))
                                            <option @if(old('type') == 'Carrier') selected @endif value="Carrier"
                                                    id="carrierOption">Carrier
                                            </option>
                                            <option @if(old('type') == 'Network') selected @endif value="Network"
                                                    id="networkOption">Network
                                            </option>
                                            <option @if(old('type') == 'Other') selected @endif value="Other"
                                                    id="otherOption">Other
                                            </option>
                                        @else
                                            <option value="Carrier" id="carrierOption">Carrier</option>
                                            <option value="Network" id="networkOption">Network</option>
                                            <option value="Other" id="otherOption">Other</option>
                                        @endif
                                    </select>
                                </div>
                                <!--number of pallets-->
                                <div class="col-lg-2" id="realNumberPallets1">
                                    <label for="realNumberPallets" class="control-label">Pallets Number :</label>
                                </div>
                                <div class="col-lg-2" id="realNumberPallets2">
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default btn-number"
                                                    data-type="minus" data-field="realNumberPallets">
                                                <span class="glyphicon glyphicon-minus"></span>
                                            </button>
                                        </span>
                                        <input id="realNumberPallets" type="number" name="realNumberPallets"
                                               class="form-control input-number"
                                               value="{{old('realNumberPallets')}}"
                                               min="-999999" max="999999" autofocus
                                                required data-toggle="tooltip" data-placement="top" title="pallets number">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default btn-number"
                                                    data-type="plus" data-field="realNumberPallets">
                                                <span class="glyphicon glyphicon-plus"></span>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div id="warehousesAssociated">
                                <div class="form-group">
                                    <!--warehouses associated-->
                                    <div class="col-lg-3">
                                        <label for="warehousesAssociated" class="control-label">Warehouses associated
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
                                                            <option selected>{{$warehouse->name}}</option>
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
                                        <a href="{{route('showAddWarehouse')}}" class="link">
                                            <span class="glyphicon glyphicon-plus-sign"></span> Add warehouse</a>
                                    </div>
                                </div>
                            </div>

                            <div id="trucksAssociated">
                                <div class="form-group">
                                    <!--adress-->
                                    <div class="col-lg-3">
                                        <label for="adress" class="control-label">Adress :</label>
                                    </div>
                                    <div class="col-lg-7">
                                        <input id="adress" type="text" class="form-control" name="adress"
                                               value="{{old('adress')}}" placeholder="Adress" autofocus/>
                                        @if ($errors->has('adress'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('adress') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <!--phone-->
                                    <div class="col-lg-3">
                                        <label for="phone" class="control-label">Phone :</label>
                                    </div>
                                    <div class="col-lg-2">
                                        <input id="phone" type="text" class="form-control" name="phone"
                                               value="{{old('phone')}}" placeholder="Phone" autofocus/>
                                        @if ($errors->has('phone'))
                                            <span class="help-block">
                                    <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                    <!--name contact-->
                                    <div class="col-lg-2">
                                        <label for="namecontact" class="control-label">Contact :</label>
                                    </div>
                                    <div class="col-lg-3">
                                        <input id="namecontact" type="text" class="form-control" name="namecontact"
                                               value="{{old('namecontact')}}" placeholder="Contact name" autofocus/>
                                        @if ($errors->has('namecontact'))
                                            <span class="help-block">
                                    <strong>{{ $errors->first('namecontact') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <!--email-->
                                    <div class="col-lg-3">
                                        <label for="email" class="control-label">Email :</label>
                                    </div>
                                    <div class="col-lg-7">
                                        <input id="email" type="text" class="form-control" name="email"
                                               value="{{old('email')}}" placeholder="Email" autofocus/>
                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-lg-4 col-lg-offset-4">
                                    <input type="submit" class="btn btn-primary btn-block btn-form" value="Add"
                                           name="addPalletsaccount" id="addPalletsaccount" onclick="formAddSubmitBlock(this);"/>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
@section('scriptEnd')
<script type="text/javascript" src="{{asset('js/addUpdatePalletsaccount.js')}}"></script>
    @endsection