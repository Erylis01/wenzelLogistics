@extends('layouts.default')

@section('title')
    Add warehouse
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
            <div class="col-lg-10 col-lg-offset-1">

                <div class="panel panel-general">
                    <div class="panel-heading"><span class="glyphicon glyphicon-plus-sign"></span> Add a new warehouse
                    </div>
                    <div class="panel-body panel-body-general">
                        <form class="form-horizontal text-right" role="form" method="POST"
                              action="{{route('addWarehouse')}}" id="formAddWarehouse">
                            {{ csrf_field() }}
                            <input type="hidden" name="actionAddForm" id="actionAddForm" />
                            <input type="hidden" name="originalPage" id="originalPage" @if(isset($originalPage))value="{{$originalPage}}" @endif/>

                            @if(Session::has('messageRefuseAddWarehouse'))
                                <div class="alert alert-danger text-alert text-center">{{ Session::get('messageRefuseAddWarehouse') }}</div>
                            @endif
                            <div class="form-group">
                                <!--name-->
                                <div class="col-lg-3">
                                    <label for="name" class="control-label">Name :</label>
                                </div>
                                <div class="col-lg-8">
                                        <input id="name" type="text" class="form-control requiredField" name="name"
                                               @if(isset($name)) value="{{$name}}" @else value="{{ old('name') }}" @endif placeholder="Name" required autofocus onchange="writeNickname(this);">
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
                                <div class="col-lg-8">
                                    <input id="nickname" type="text" class="form-control" name="nickname"
                                           @if(isset($nickname)) value="{{$nickname}}" @else value="{{ old('nickname') }}" @endif placeholder="Nickname" autofocus>
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
                                        <input id="adress" type="text" class="form-control" name="adress"
                                               @if(isset($adress)) value="{{$adress}}" @else value="{{old('adress')}}" @endif placeholder="Adress" autofocus>
                                </div>
                            </div>
                            <div class="form-group">
                                <!--country-->
                                <div class="col-lg-2 col-lg-offset-3">
                                    <input id="country" type="text" class="form-control requiredField" name="country" @if(isset($country)) value="{{$country}}" @else value="{{old('country')}}" @endif placeholder="Country" data-toggle="tooltip" data-placement="top" title="Country" required autofocus/>
                                </div>
                                <!--zipcode-->
                                <div class="col-lg-2">
                                    <input id="zipcode" type="number" min="0" class="form-control requiredField" name="zipcode"
                                           @if(isset($zipcode)) value="{{$zipcode}}" @else value="{{old('zipcode')}}" @endif placeholder="Zipcode" data-toggle="tooltip" data-placement="top" title="Zipcode" required autofocus/>
                                </div>
                                <!--town-->
                                <div class="col-lg-3">
                                    <input id="town" type="text" class="form-control requiredField" name="town"
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
                                               @if(isset($phone)) value="{{$phone}}" @else value="{{old('phone')}}" @endif placeholder="Phone" autofocus>
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
                                               @if(isset($fax)) value="{{$fax}}" @else value="{{old('fax')}}" @endif placeholder="Fax/Mobile" autofocus>
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
                                               @if(isset($email)) value="{{$email}}" @else value="{{old('email')}}" @endif placeholder="Email" autofocus>
                                    @if ($errors->has('email'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <!--contact name-->
                                <div class="col-lg-2">
                                    <label for="details" class="control-label">Details :</label>
                                </div>
                                <div class="col-lg-8">
                                    @if(isset($details))
                                        <textarea
                                                class="form-control" name="details"
                                                id="details"
                                                rows="2"
                                                placeholder="Details (contact name, ...)" autofocus>{{$details}}</textarea>
                                    @else
                                        <textarea
                                                class="form-control" name="details"
                                                id="details"
                                                rows="2"
                                                placeholder="Details (contact name, ...)" autofocus>{{old('details')}}</textarea>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <!--pallet account associated-->
                                <div class="col-lg-3">
                                    <label for="namepalletsaccounts" class="control-label">Pallets Account
                                        :</label>
                                </div>
                                <div class="col-lg-6">
                                    <!-- if mistake in the adding form you are redirected with field already filled-->
                                        <select class="selectpicker show-tick form-control" data-size="5"
                                                data-live-search="true" data-live-search-style="startsWith"
                                                title="Pallets Accounts" name="namepalletsaccounts[]"
                                                multiple required data-style="requiredField">
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
                                                @else
                                                    <option>{{$palletsAccount->nickname}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                </div>
                                <div class="col-lg-3 text-left">
                                    <a href="{{route('showAddPalletsaccount', ['originalPage'=>'addWarehouse'])}}" class="link">
                                        <span class="glyphicon glyphicon-plus-sign"></span> Add account</a>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-lg-8 col-lg-offset-3">
                                    <button type="submit" class="btn btn-primary btn-block btn-form"
                                            name="addWarehouse" id="addWarehouse" value="addWarehouse" data-toggle="modal" data-target="#addWarehouse_modal" onclick="formAddSubmitBlock(this);">
                                        Add
                                    </button>
                                </div>
                            </div>

                            @if(Session::has('testZipcode'))
                            <!-- Modal Add -->
                                <div class="modal show" id="addWarehouse_modal" role="dialog">
                                    <div class="modal-dialog modal-md">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="submit" class="close" value="close"
                                                        name="refuseAddWarehouse">&times;
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
                                                                value="yes"
                                                                name="validateAddWarehouse" id="validateAddWarehouse" value="validateAddWarehouse" onclick="formAddSubmitBlock(this);">
                                                            Yes
                                                        </button>

                                                        <button type="submit" class="btn btn-success btn-modal"
                                                                value="refuseAddWarehouse" name="refuseAddWarehouse" id="refuseAddWarehouse" onclick="formAddSubmitBlock(this);">No
                                                        </button>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-default btn-modal"
                                                        value="refuseAddWarehouse" name="refuseAddWarehouse" id="refuseAddWarehouseb" onclick="formAddSubmitBlock(this);">
                                                    Close
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </form>
                        @php(session()->pull('testZipcode'))
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
@section('scriptEnd')
    <script type="text/javascript" src="{{asset('js/addUpdateWarehouse.js')}}">
    </script>
@endsection