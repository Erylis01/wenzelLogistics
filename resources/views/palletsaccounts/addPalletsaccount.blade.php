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
    nonActive
@endsection
@section('classPalletsAccounts')
    active
@endsection
@section('classPalletsTransfers')
    nonActive
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
                            <input type="hidden" name="originalPage" id="originalPage" @if(isset($originalPage))value="{{$originalPage}}" @endif/>
                            <div class="form-group">
                                <!--name-->
                                <div class="col-lg-3">
                                    <label for="name" class="control-label">Name :</label>
                                </div>
                                <div class="col-lg-7">
                                    <input id="name" type="text" class="form-control requiredField" name="name"
                                           @if(isset($name)) value="{{$name}}" @else value="{{old('name')}}" @endif placeholder="Name" required autofocus onchange="writeNickname(this);">
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
                                <div class="col-lg-7">
                                    <input id="nickname" type="text" class="form-control" name="nickname"
                                           @if(isset($nickname)) value="{{$nickname}}" @else value="{{old('nickname')}}" @endif placeholder="Nickname" autofocus>
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
                                    <label for="type" class="control-label">Type :</label>
                                </div>
                                <div class="col-lg-2">
                                    <!-- if mistake in the adding form you are redirected with field already filled-->
                                    <select class="selectpicker show-tick form-control" data-size="5"
                                            data-live-search="true" data-live-search-style="startsWith"
                                            title="Type" name="type" id="type" onchange="displayFields(this);"
                                            required data-style="requiredField">
                                        @if(Illuminate\Support\Facades\Input::old('type') || isset($type))
                                            <option @if((Illuminate\Support\Facades\Input::old('type') && old('type') == 'Carrier') || (isset($type) && $type=='Carrier')) selected @endif value="Carrier"
                                                    id="carrierOption">Carrier
                                            </option>
                                            <option @if((Illuminate\Support\Facades\Input::old('type') && old('type') == 'Network') || (isset($type) && $type=='Network')) selected @endif value="Network"
                                                    id="networkOption">Network
                                            </option>
                                            <option @if((Illuminate\Support\Facades\Input::old('type') && old('type') == 'Other') || (isset($type) && $type=='Other')) selected @endif value="Other"
                                                    id="otherOption">Other
                                            </option>
                                        @else
                                            <option value="Carrier" id="carrierOption">Carrier</option>
                                            <option value="Network" id="networkOption">Network</option>
                                            <option value="Other" id="otherOption">Other</option>
                                        @endif
                                    </select>
                                </div>

                                <div id="createWarehouse" @if((isset($type) && $type=='Network') || (Illuminate\Support\Facades\Input::old('type') && old('type') == 'Network'))style="display: block;" @else style="display: none;" @endif>
                                    <div class="col-lg-2 col-lg-offset-1 checkbox">
                                        <label><input type="checkbox" name="oneWarehouse" value="oneWarehouse" id="oneWarehouse" onchange="hideWarehousesAssociated();" @if(Illuminate\Support\Facades\Input::old('oneWarehouse')) checked @endif/>1 warehouse only</label>
                                    </div>
                                </div>
                            </div>

                            <div id="warehouse" @if((isset($type) && $type=='Network') || (Illuminate\Support\Facades\Input::old('type') && old('type') == 'Network'))style="display: block;" @else style="display: none;" @endif>
                                <div class="form-group">
                                    <!--number of pallets-->
                                    <div class="col-lg-2 col-lg-offset-1">
                                        <label for="realNumberPallets" class="control-label">Pallets Number :</label>
                                    </div>
                                    <div class="col-lg-2">
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
                                <div class="form-group" id="warehousesAssociated" @if(Illuminate\Support\Facades\Input::old('oneWarehouse')) style="display:none;" @endif>
                                    <!--warehouses associated-->
                                    <div class="col-lg-3">
                                        <label for="warehousesAssociated" class="control-label">Warehouses associated
                                            :</label>
                                    </div>
                                    <div class="col-lg-6">
                                        <select class="selectpicker show-tick form-control" data-size="5"
                                                data-live-search="true" data-live-search-style="startsWith"
                                                title="Warehouses Associated" name="warehousesAssociated[]"
                                                multiple id="select-warehousesAssociated">
                                            <option value="none" disabled>None</option>
                                            @foreach($listWarehouses as $warehouse )
                                                @php($list[]=null)
                                                @if(Illuminate\Support\Facades\Input::old('warehousesAssociated'))
                                                    @foreach(old('warehousesAssociated') as $warehouseA)
                                                        @if($warehouseA == $warehouse->nickname)
                                                            <option selected>{{$warehouse->nickname}}</option>
                                                            @php($list[]=$warehouse)
                                                        @endif
                                                    @endforeach
                                                    @if(!in_array($warehouse, $list))
                                                        <option>{{$warehouse->nickname}}</option>
                                                    @endif
                                                @else
                                                    <option>{{$warehouse->nickname}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 text-left">
                                        <a href="{{route('showAddWarehouse', ['originalPage'=>'addPalletsaccount'])}}" class="link">
                                            <span class="glyphicon glyphicon-plus-sign"></span> Add warehouse</a>
                                    </div>
                                </div>
                            </div>

                            <div id="contactInfos" @if((isset($type) && ($type=='Carrier'))|| (isset($type) && ($type=='Network') && isset($oneWarehouse)) || (Illuminate\Support\Facades\Input::old('type') && (old('type')=='Carrier'))|| (Illuminate\Support\Facades\Input::old('type') && (old('type')=='Network') && Illuminate\Support\Facades\Input::old('oneWarehouse'))) style="display: block;" @else style="display: none;" @endif>
                                <input type="hidden" @if(isset($atrnr)) value="{{$atrnr}}" @endif name="atrnr">
                                <div class="form-group">
                                    <!--adress-->
                                    <div class="col-lg-3">
                                        <label for="adress" class="control-label">Adress :</label>
                                    </div>
                                    <div class="col-lg-7">
                                        <input id="adress" type="text" class="form-control" name="adress"
                                               @if(isset($adress)) value="{{$adress}}" @else value="{{old('adress')}}" @endif placeholder="Adress" autofocus/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <!--country-->
                                    <div class="col-lg-2 col-lg-offset-3">
                                        <input id="country" type="text" class="form-control requiredField" name="country" @if(isset($country)) value="{{$country}}" @else value="{{old('country')}}" @endif placeholder="Country" data-toggle="tooltip" data-placement="top" title="Country" autofocus/>
                                    </div>
                                    <!--zipcode-->
                                    <div class="col-lg-2">
                                       <input id="zipcode" type="number" min="0" class="form-control requiredField" name="zipcode"
                                                 @if(isset($zipcode)) value="{{$zipcode}}" @else value="{{old('zipcode')}}" @endif placeholder="Zipcode" data-toggle="tooltip" data-placement="top" title="Zipcode" autofocus/>
                                    </div>
                                    <!--town-->
                                    <div class="col-lg-3">
                                        <input id="town" type="text" class="form-control requiredField" name="town"
                                                 @if(isset($town)) value="{{$town}}" @else value="{{old('town')}}" @endif placeholder="Town" data-toggle="tooltip" data-placement="top" title="Town" autofocus/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    @if ($errors->has('country'))
                                        <span class="help-block text-center">
                                    <strong>{{ $errors->first('country') }}</strong>
                                    </span>
                                    @endif
                                    @if ($errors->has('zipcode'))
                                        <span class="help-block text-center">
                                    <strong>{{ $errors->first('zipcode') }}</strong>
                                    </span>
                                    @endif
                                        @if ($errors->has('town'))
                                            <span class="help-block text-center">
                                    <strong>{{ $errors->first('town') }}</strong>
                                    </span>
                                        @endif
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
                                    <!--fax-->
                                    <div class="col-lg-3">
                                        <label for="fax" class="control-label">Fax/Mobile :</label>
                                    </div>
                                    <div class="col-lg-2">
                                        <input id="fax" type="text" class="form-control" name="fax"
                                               value="{{old('fax')}}" placeholder="Fax/Mobile" autofocus/>
                                        @if ($errors->has('fax'))
                                            <span class="help-block">
                                    <strong>{{ $errors->first('fax') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <!--contact infos-->
                                    <div class="col-lg-3">
                                        <label for="details" class="control-label">Details :</label>
                                    </div>
                                    <div class="col-lg-7">
                                        <textarea rows="2" id="details" class="form-control" name="details"
                                                   placeholder="Details (contact name, ...)" autofocus>{{old('details')}}</textarea>
                                        @if ($errors->has('details'))
                                            <span class="help-block">
                                    <strong>{{ $errors->first('details') }}</strong>
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