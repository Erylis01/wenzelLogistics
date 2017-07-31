@extends('layouts.default')

@section('title')
    Warehouse details
@endsection

@section('stylesheet')
    <link href="{{asset('css/warehouses.css')}}" rel="stylesheet" type="text/css">
@endsection

@section('classLoadings')
    class="nonActive"
@endsection
@section('classWarehouses')
    class="active"
@endsection
@section('classTrucks')
    nonActive
@endsection
@section('classPalletsAccounts')
    nonActive
@endsection
@section('classPalletsTransfers')
    nonActive
@endsection
@section('classProfile')
    nonActive
@endsection

@section('scriptBegin')
    <script type="text/javascript" src="{{asset('js/addUpdateWarehouse.js')}}">
    </script>
@endsection

@section('content')
    <div class="row">
        @if(Auth::guest())
            <h4>You need to login to see the content</h4>
        @else
            <div class="col-lg-14">
                <form class="form-horizontal text-right" role="form" method="POST"
                      action="{{route('updateWarehouse', $id)}}" id="formUpdateWarehouse">
                    {{ csrf_field() }}
                    <input type="hidden" name="actionUpdateForm" id="actionUpdateForm" />
                <div class="panel panel-general">
                    <div class="panel-heading">
                        <div class="col-lg-10 text-left">{{ $name }}</div>
                        <div>
                            @if($activate==1)
                                <span class="glyphicon glyphicon-eye-open"></span>
                                <button type="submit" class="btn-add btn" id="desactivate" name="desactivate" value="desactivate" onclick="formUpdateSubmitBlock(this);">Desactivate</button>
                            @else
                                <span class="glyphicon glyphicon-eye-close"></span>
                                <button type="submit" class="btn-add btn" id="activate" name="activate" value="activate" onclick="formUpdateSubmitBlock(this);">Activate</button>
                            @endif
                            {{--<button type="button" class=" btn btn-primary btn-form glyphicon glyphicon-trash"--}}
                                    {{--data-toggle="modal" data-target="#deleteWarehouse_modal" value="{{$id}}"--}}
                                    {{--name="deleteWarehouse_modal"></button>--}}
                        </div>
                    </div>
                    <div class="panel-body panel-body-general">
                            @if(Session::has('messageRefuseUpdateWarehouse'))
                                <p class="alert alert-danger text-alert text-center">{{ Session::get('messageRefuseUpdateWarehouse') }}</p>
                            @elseif (Session::has('messageUpdateWarehouse'))
                                <p class="alert alert-success text-alert text-center">{{ Session::get('messageUpdateWarehouse') }}</p>
                            @endif

                            <div class="form-group">
                                <!--nickname-->
                                <div class="col-lg-3">
                                    <label for="nickname" class="control-label">Nickname :</label>
                                </div>
                                <div class="col-lg-8">
                                    <input id="nickname" type="text" class="form-control" name="nickname"
                                           value="{{ $nickname }}" placeholder="Nickname" required autofocus>
                                    @if ($errors->has('nickname'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('nickname') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <!--adress-->
                                <div class="col-lg-3">
                                    <label for="adress" class="control-label">Adress :</label>
                                </div>
                                <div class="col-lg-8">
                                    <input id="adress" type="text" class="form-control" name="adress" value="{{ $adress }}" placeholder="Adress" autofocus>
                                </div>
                            </div>

                            <div class="form-group">
                                <!--country-->
                                <div class="col-lg-2 col-lg-offset-3">
                                    <input id="country" type="text" class="form-control" name="country" @if(isset($country)) value="{{$country}}" @else value="{{old('country')}}" @endif placeholder="Country" data-toggle="tooltip" data-placement="top" title="Country" required autofocus/>
                                </div>
                                <!--zipcode-->
                                <div class="col-lg-2">
                                    <input id="zipcode" type="number" min="0" class="form-control" name="zipcode"
                                           @if(isset($zipcode)) value="{{$zipcode}}" @else value="{{old('zipcode')}}" @endif placeholder="Zipcode" data-toggle="tooltip" data-placement="top" title="Zipcode" required autofocus/>
                                </div>
                                <!--town-->
                                <div class="col-lg-4">
                                    <input id="town" type="text" class="form-control" name="town"
                                           @if(isset($town)) value="{{$town}}" @else value="{{old('town')}}" @endif placeholder="Town" data-toggle="tooltip" data-placement="top" title="Town" required autofocus/>
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
                                <div class="col-lg-3">
                                    <input id="phone" type="text" class="form-control" name="phone"
                                           value="{{ $phone }}" placeholder="Phone" autofocus>
                                    @if ($errors->has('phone'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <!--fax-->
                                <div class="col-lg-2">
                                    <label for="fax" class="control-label">Fax/Mobile :</label>
                                </div>
                                <div class="col-lg-3">
                                    <input id="fax" type="text" class="form-control" name="fax"
                                           value="{{ $fax }}" placeholder="Fax/Mobile" autofocus>
                                    @if ($errors->has('fax'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('fax') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <!--email-->
                                <div class="col-lg-3">
                                    <label for="email" class="control-label">Email :</label>
                                </div>
                                <div class="col-lg-8">
                                    <input id="email" type="text" class="form-control" name="email"
                                           value="{{ $email }}" placeholder="Email" autofocus>
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <!--contact name-->
                                <div class="col-lg-3">
                                    <label for="details" class="control-label">Details :</label>
                                </div>
                                <div class="col-lg-8">
                                        <textarea class="form-control" name="details"
                                                id="details" rows="2" placeholder="Details (contact name, ...)"
                                                autofocus>{{$details}}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <!--pallets accounts associated-->
                                <div class="col-lg-3">
                                    <label for="details" class="control-label">@if(isset($namepalletsaccounts))
                                            <a class="link" data-toggle="modal" data-target="#palletsaccounts_modal">
                                                Pallets Account :</a>
                                        @else Pallets Account :@endif
                                    </label>
                                </div>
                                <div class="col-lg-6">
                                    <select class="selectpicker show-tick form-control" data-size="5"
                                            data-live-search="true" data-live-search-style="startsWith"
                                            title="Pallets Accounts" name="namepalletsaccounts[]" required multiple>
                                        @foreach($listPalletsAccounts as $palletsAccount )
                                            @php($list[]=null)
                                            @if(Illuminate\Support\Facades\Input::old('namepalletsaccounts'))
                                                @foreach(old('namepalletsaccounts') as $namePA)
                                                    @if($palletsAccount->nickname==$namePA)
                                                        <option selected>{{$palletsAccount->nickname}}</option>
                                                        @php($list[]=$palletsAccount)
                                                    @endif
                                                @endforeach
                                                @if(!in_array($palletsAccount, $list))
                                                    <option>{{$palletsAccount->nickname}}</option>
                                                @endif
                                            @elseif(isset($namepalletsaccounts))
                                                @foreach($namepalletsaccounts as $namePA)
                                                    @if($palletsAccount->nickname==$namePA)
                                                        <option selected>{{$palletsAccount->nickname}}</option>
                                                        @php($list[]=$palletsAccount)
                                                    @endif
                                                @endforeach
                                                @if(!in_array($palletsAccount, $list))
                                                    <option>{{$palletsAccount->nickname}}</option>
                                                @endif
                                            @else
                                                <option>{{$palletsAccount->nickname}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 text-left">
                                    <a href="{{route('showAddPalletsaccount', ['originalPage'=>'detailsWarehouse-'.$id])}}" class="link"><span
                                                class="glyphicon glyphicon-plus-sign"></span> Add account</a>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-lg-4 col-lg-offset-4">
                                    <input type="submit"
                                           class="btn btn-primary btn-block btn-form"
                                           value="Update"
                                           name="updateWarehouse" id="updateWarehouse" data-toggle="modal"
                                           data-target="#updateWarehouse_modal" onclick="formUpdateSubmitBlock(this);"/>
                                </div>
                            </div>

                                <!-- Modal palletsaccounts -->
                                <div class="modal fade" id="palletsaccounts_modal" role="dialog">
                                    <div class="modal-dialog modal-md">
                                        <div class="modal-content">
                                            @if(isset($namepalletsaccounts))
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;
                                                    </button>
                                                    <h4 class="modal-title text-center">List of pallets accounts</h4>
                                                </div>
                                                <div class="modal-body text-left">
                                                    @foreach($namepalletsaccounts as $namePA)
                                                        <li>
                                                            @php($idPA=\App\Palletsaccount::where('nickname', $namePA)->first()->id)
                                                            <a href="{{route('showDetailsPalletsaccount', $idPA)}}"
                                                               class="link">{{$namePA}}</a>
                                                        </li>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            @if(Session::has('testZipcode'))
                            <!-- Modal Add -->
                                <div class="modal show" id="updateWarehouse_modal" role="dialog">
                                    <div class="modal-dialog modal-md">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="submit" class="close" value="refuseUpdateWarehouse"
                                                        name="refuseUpdateWarehouse" id="refuseUpdateWarehouse" onclick="formUpdateSubmitBlock(this);">&times;
                                                </button>
                                                <h4 class="modal-title text-center">Be careful !</h4>
                                            </div>
                                            <div class="modal-body center">
                                                    @if(count($zipcodeWarehouses)==1)
                                                        <p class="text-left">
                                                            An other warehouse ({{$zipcodeWarehouses[0]->nickname}}) already
                                                            exists in this town. Are you
                                                            sure to create a new one ?
                                                        </p>
                                                    @else
                                                        <p class="text-left"> Others warehouses already exist in this town : </p>
                                                        <ul>
                                                            @foreach($zipcodeWarehouses as $warehouse)
                                                                <li class="text-left">{{$warehouse->nickname}}</li>
                                                            @endforeach
                                                        </ul>
                                                        <p class="text-left"> Are you sure to create a new one ? </p>
                                                    @endif
                                                    <div class="text-center">
                                                        <button type="submit" class="btn btn-danger btn-modal"
                                                                value="validateUpdateWarehouse"
                                                                name="validateUpdateWarehouse" id="validateUpdateWarehouse"  onclick="formUpdateSubmitBlock(this);">
                                                            Yes
                                                        </button>

                                                        <button type="submit" class="btn btn-success btn-modal" value="refuseUpdateWarehouse" name="refuseUpdateWarehouse" id="refuseUpdateWarehouseb" onclick="formUpdateSubmitBlock(this);">
                                                            No
                                                        </button>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-default btn-modal"
                                                        value="refuseUpdateWarehouse" name="refuseUpdateWarehouse" id="refuseUpdateWarehouse3" onclick="formUpdateSubmitBlock(this);">
                                                    Close
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif


                        {{--<!-- Modal Delete -->--}}
                        {{--<div class="modal fade" id="deleteWarehouse_modal" role="dialog">--}}
                            {{--<div class="modal-dialog modal-sm">--}}
                                {{--<div class="modal-content">--}}
                                    {{--<div class="modal-header">--}}
                                        {{--<button type="button" class="close" data-dismiss="modal">&times;</button>--}}
                                        {{--<h4 class="modal-title text-center">Are you sure to delete this warehouse ?</h4>--}}
                                    {{--</div>--}}
                                    {{--<div class="modal-body center">--}}
                                        {{--<form method="post" action="{{route('deleteWarehouse',$id)}}" id="formDeleteWarehouse">--}}
                                            {{--<input type="hidden" name="_method" value="delete">--}}
                                            {{--<input type="hidden" name="actionDeleteForm" id="actionDeleteForm" />--}}
                                            {{--{{ csrf_field() }}--}}
                                            {{--<div class="text-center">--}}
                                                {{--<button type="submit" class="btn btn-danger btn-modal" value="deleteWarehouse"--}}
                                                        {{--name="deleteWarehouse" id="deleteWarehouse" onclick="formDeleteSubmitBlock(this);">--}}
                                                    {{--Yes--}}
                                                {{--</button>--}}
                                                {{--<button type="button" class="btn btn-success btn-modal"--}}
                                                        {{--data-dismiss="modal">No--}}
                                                {{--</button>--}}
                                            {{--</div>--}}
                                        {{--</form>--}}
                                    {{--</div>--}}
                                    {{--<div class="modal-footer">--}}
                                        {{--<button type="button" class="btn btn-default btn-modal"--}}
                                                {{--data-dismiss="modal">--}}
                                            {{--Close--}}
                                        {{--</button>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        @php(session()->pull('testZipcode'))
                    </div>
                </div>
                </form>
            </div>
        @endif
    </div>
@endsection

@section('scriptEnd')
    <script type="text/javascript" src="{{asset('js/addUpdateWarehouse.js')}}">
    </script>
@endsection