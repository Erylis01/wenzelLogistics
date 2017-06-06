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
@section('classCarriers')
    class="nonActive"
@endsection
@section('classPalletsAccounts')
    class="nonActive"
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
            <div class="col-lg-10 col-lg-offset-1">

                <div class="panel panel-general">
                    <div class="panel-heading">Details of the warehouse : {{$id}} - {{ $name }}</div>
                    <div class="panel-body panel-body-general">
                        <form class="form-horizontal text-right" role="form" method="POST"
                              action="{{route('updateWarehouse', $id)}}">
                            {{ csrf_field() }}
                            <p class="text-center legend-auth">* required field</p>

                            @if(Session::has('messageRefuseUpdateWarehouse'))
                                <div class="alert alert-danger text-alert text-center">{{ Session::get('messageRefuseUpdateWarehouse') }}</div>
                                @elseif (Session::has('messageUpdateWarehouse'))
                                    <div class="alert alert-success text-alert text-center">{{ Session::get('messageUpdateWarehouse') }}</div>
                            @endif

                            <div class="form-group">
                                <!--name-->
                                <div class="col-lg-3">
                                    <label for="name" class="control-label"><span>*</span> Name :</label>
                                </div>
                                <div class="col-lg-8">
                                    <input id="name" type="text" class="form-control" name="name"
                                           value="{{ $name }}" placeholder="Name" required autofocus>
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <!--adress-->
                                <div class="col-lg-3">
                                    <label for="adress" class="control-label"><span>*</span> Adress :</label>
                                </div>
                                <div class="col-lg-8">
                                    <input id="adress" type="text" class="form-control" name="adress"
                                           value="{{ $adress }}" placeholder="Adress" required autofocus>
                                    @if ($errors->has('adress'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('adress') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <!--zipcode-->
                                <div class="col-lg-3">
                                    <label for="zipcode" class="control-label"><span>*</span> Zip Code :</label>
                                </div>
                                <div class="col-lg-3">
                                    <input id="zipcode" type="number" min="0" class="form-control" name="zipcode"
                                           value="{{ $zipcode }}" placeholder="Zip Code" required autofocus>
                                    @if ($errors->has('zipcode'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('zipcode') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <!--town-->
                                <div class="col-lg-2">
                                    <label for="town" class="control-label"><span>*</span> Town :</label>
                                </div>
                                <div class="col-lg-3">
                                    <input id="town" type="text" class="form-control" name="town"
                                           value="{{ $town }}" placeholder="Town" required autofocus>
                                    @if ($errors->has('town'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('town') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <!--country-->
                                <div class="col-lg-3">
                                    <label for="country" class="control-label"><span>*</span> Country :</label>
                                </div>
                                <div class="col-lg-8">
                                    <input id="country" type="text" class="form-control" name="country"
                                           value="{{ $country }}" placeholder="country" required autofocus>
                                    @if ($errors->has('country'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('country') }}</strong>
                                    </span>
                                    @endif
                                </div>
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
                                    <label for="fax" class="control-label">Fax :</label>
                                </div>
                                <div class="col-lg-3">
                                    <input id="fax" type="text" class="form-control" name="fax"
                                           value="{{ $fax }}" placeholder="Fax" autofocus>
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
                                    <label for="namecontact" class="control-label">Contact Name :</label>
                                </div>
                                <div class="col-lg-8">
                                    <input id="namecontact" type="text" class="form-control" name="namecontact"
                                           value="{{ $namecontact }}" placeholder="Contact Name" autofocus>
                                </div>
                            </div>

                            <div class="form-group">
                                <!--pallets accounts associated-->
                                <div class="col-lg-3">
                                    {{--<label for="namecontact" class="control-label"><a href="{{route('showDetailsPalletsaccount', \App\Palletsaccount::where('name',$namepalletsaccount)->first()->id)}}" class="link">Pallets Account :</a></label>--}}
                                    <label for="namecontact" class="control-label"><span>*</span> Pallets Account : </label>
                                </div>
                                <div class="col-lg-6">
                                    <select class="selectpicker show-tick form-control" data-size="5"
                                            data-live-search="true" data-live-search-style="startsWith"
                                            title="Pallets Accounts" name="namepalletsaccounts[]" required multiple>
                                        @foreach($listPalletsAccounts as $palletsAccount )
                                            @php($list[]=null)
                                            @if(Illuminate\Support\Facades\Input::old('namepalletsaccounts'))
                                                @foreach(old('namepalletsaccounts') as $namePA)
                                                @if($palletsAccount->name==$namePA)
                                                    <option selected>{{$palletsAccount->name}}</option>
                                                    @php($list[]=$palletsAccount)
                                                @endif
                                            @endforeach
                                            @if(!in_array($palletsAccount, $list))
                                                <option>{{$palletsAccount->name}}</option>
                                            @endif
                                                    @elseif(isset($namepalletsaccounts))
                                                        @foreach($namepalletsaccounts as $namePA)
                                                            @if($palletsAccount->name==$namePA)
                                                                <option selected>{{$palletsAccount->name}}</option>
                                                                @php($list[]=$palletsAccount)
                                                            @endif
                                                        @endforeach
                                                        @if(!in_array($palletsAccount, $list))
                                                            <option>{{$palletsAccount->name}}</option>
                                                        @endif
                                            @else
                                                <option>{{$palletsAccount->name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 text-left">
                                    <a href="{{route('showAddPalletsaccount')}}" class="link"><span
                                                class="glyphicon glyphicon-plus-sign"></span> Add account</a>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-lg-4 col-lg-offset-3">
                                    <input type="submit"
                                           class="btn btn-primary btn-block btn-form"
                                           value="Update"
                                           name="updateWarehouse" data-toggle="modal"
                                           data-target="#updateWarehouse_modal">
                                </div>

                                <div class="col-lg-3 col-lg-offset-1">
                                    <button type="button" class="btn btn-primary btn-block btn-form"
                                            data-toggle="modal"
                                            data-target="#deleteWarehouse_modal">Delete
                                    </button>
                                </div>
                            </div>

                            @if(Session::has('testZipcode'))
                            <!-- Modal Add -->
                                <div class="modal show" id="updateWarehouse_modal" role="dialog">
                                    <div class="modal-dialog modal-md">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="submit" class="close" value="close"
                                                        name="refuseUpdateWarehouse">&times;
                                                </button>
                                                <h4 class="modal-title text-center">Be careful !</h4>
                                            </div>
                                            <div class="modal-body center">
                                                <form role="form" method="POST"
                                                      action="{{route('updateWarehouse', $id)}}">
                                                    {{ csrf_field() }}
                                                    @if(count($zipcodeWarehouses)==1)
                                                        <p class="text-left">
                                                            An other warehouse ({{$zipcodeWarehouses[0]->name}}) already
                                                            exists in this town. Are you
                                                            sure to create a new one ?
                                                        </p>
                                                    @else
                                                        <p class="text-left">
                                                            Others warehouses already exist in this town :
                                                        </p>
                                                        <ul>
                                                            @foreach($zipcodeWarehouses as $warehouse)
                                                                <li class="text-left">{{$warehouse->name}}</li>
                                                            @endforeach
                                                        </ul>
                                                        <p class="text-left">
                                                            Are you sure to create a new one ?
                                                        </p>
                                                    @endif
                                                    <div class="text-center">
                                                        <button type="submit" class="btn btn-danger btn-modal"
                                                                value="yes"
                                                                name="validateUpdateWarehouse">
                                                            Yes
                                                        </button>

                                                        <button type="submit" class="btn btn-success btn-modal"
                                                                value="close" name="refuseUpdateWarehouse">No
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-default btn-modal"
                                                        value="close" name="refuseUpdateWarehouse">
                                                    Close
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </form>

                        <!-- Modal Delete -->
                        <div class="modal fade" id="deleteWarehouse_modal" role="dialog">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title text-center">Are you sure to delete this warehouse ?</h4>
                                    </div>
                                    <div class="modal-body center">
                                        <form method="post" action="{{route('deleteWarehouse',$id)}}">
                                            <input type="hidden" name="_method" value="delete">
                                            {{ csrf_field() }}
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-danger btn-modal" value="yes"
                                                        name="deleteWarehouse">
                                                    Yes
                                                </button>
                                                <button type="button" class="btn btn-success btn-modal"
                                                        data-dismiss="modal">No
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default btn-modal"
                                                data-dismiss="modal">
                                            Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @php(session()->pull('testZipcode'))

                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection