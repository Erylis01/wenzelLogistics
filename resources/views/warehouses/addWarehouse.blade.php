@extends('layouts.default')

@section('title')
    Details warehouse
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
@section('classPalletsAccounts')
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
                    <div class="panel-heading"><span class="glyphicon glyphicon-plus-sign"></span> Add a new warehouse
                    </div>
                    <div class="panel-body panel-body-general">
                        <form class="form-horizontal text-right" role="form" method="POST"
                              action="{{route('addWarehouse')}}">
                            {{ csrf_field() }}
                            <p class="text-center legend-auth">* required field</p>

                            @if(Session::has('messageRefuseAddWarehouse'))
                                <div class="alert alert-danger text-alert text-center">{{ Session::get('messageRefuseAddWarehouse') }}</div>
                            @endif
                            <div class="form-group">
                                <!--name-->
                                <div class="col-lg-3">
                                    <label for="name" class="control-label"><span>*</span> Name :</label>
                                </div>
                                <div class="col-lg-8">
                                    @if(isset($name))
                                        <input id="name" type="text" class="form-control" name="name"
                                               value="{{$name}}" placeholder="Name" required autofocus>
                                    @else
                                        <input id="name" type="text" class="form-control" name="name"
                                               value="{{ old('name') }}" placeholder="Name" required autofocus>
                                    @endif
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
                                    @if(isset($adress))
                                        <input id="adress" type="text" class="form-control" name="adress"
                                               value="{{$adress}}" placeholder="Adress" required autofocus>
                                    @else
                                        <input id="adress" type="text" class="form-control" name="adress"
                                               value="{{old('adress')}}" placeholder="Adress" required autofocus>
                                    @endif
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
                                    @if(isset($zipcode))
                                        <input id="zipcode" type="number" min="0" class="form-control" name="zipcode"
                                               value="{{$zipcode}}" placeholder="Zip Code" required autofocus>
                                    @else
                                        <input id="zipcode" type="number" class="form-control" name="zipcode"
                                               value="{{old('zipcode')}}" placeholder="Zip Code" required autofocus>
                                    @endif
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
                                    @if(isset($town))
                                        <input id="town" type="text" class="form-control" name="town"
                                               value="{{$town}}" placeholder="Town" required autofocus>
                                    @else
                                        <input id="town" type="text" class="form-control" name="town"
                                               value="{{old('town')}}" placeholder="Town" required autofocus>
                                    @endif
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
                                    @if(isset($country))
                                        <input id="country" type="text" class="form-control" name="country"
                                               value="{{$country}}" placeholder="Country" required autofocus>
                                    @else
                                        <input id="country" type="text" class="form-control" name="country"
                                               value="{{old('country')}}" placeholder="Country" required autofocus>
                                    @endif
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
                                    @if(isset($phone))
                                        <input id="phone" type="text" class="form-control" name="phone"
                                               value="{{$phone}}" placeholder="Phone" required autofocus>
                                    @else
                                        <input id="phone" type="text" class="form-control" name="phone"
                                               value="{{old('phone')}}" placeholder="Phone" autofocus>
                                    @endif
                                    {{--@if ($errors->has('phone'))--}}
                                        {{--<span class="help-block">--}}
                                        {{--<strong>{{ $errors->first('phone') }}</strong>--}}
                                    {{--</span>--}}
                                    {{--@endif--}}
                                </div>
                                <!--fax-->
                                <div class="col-lg-2">
                                    <label for="fax" class="control-label">Fax :</label>
                                </div>
                                <div class="col-lg-3">
                                    @if(isset($fax))
                                        <input id="fax" type="text" class="form-control" name="fax"
                                               value="{{$fax}}" placeholder="Fax" autofocus>
                                    @else
                                        <input id="fax" type="text" class="form-control" name="fax"
                                               value="{{old('fax')}}" placeholder="Fax" autofocus>
                                    @endif
                                    {{--@if ($errors->has('fax'))--}}
                                        {{--<span class="help-block">--}}
                                        {{--<strong>{{ $errors->first('fax') }}</strong>--}}
                                    {{--</span>--}}
                                    {{--@endif--}}
                                </div>
                            </div>


                            <div class="form-group">
                                <!--email-->
                                <div class="col-lg-3">
                                    <label for="email" class="control-label">Email :</label>
                                </div>
                                <div class="col-lg-8">
                                    @if(isset($email))
                                        <input id="email" type="text" class="form-control" name="email"
                                               value="{{$email}}" placeholder="Email" autofocus>
                                    @else
                                        <input id="email" type="text" class="form-control" name="email"
                                               value="{{old('email')}}" placeholder="Email" autofocus>
                                    @endif
                                    {{--@if ($errors->has('email'))--}}
                                        {{--<span class="help-block">--}}
                                        {{--<strong>{{ $errors->first('email') }}</strong>--}}
                                    {{--</span>--}}
                                    {{--@endif--}}
                                </div>
                            </div>

                            <div class="form-group">
                                <!--contact name-->
                                <div class="col-lg-3">
                                    <label for="namecontact" class="control-label">Contact Name :</label>
                                </div>
                                <div class="col-lg-8">
                                    @if(isset($namecontact))
                                        <input id="namecontact" type="text" class="form-control" name="namecontact"
                                               value="{{$namecontact}}" placeholder="Contact Name" autofocus>
                                    @else
                                        <input id="namecontact" type="text" class="form-control" name="namecontact"
                                               value="{{old('namecontact')}}" placeholder="Contact Name" autofocus>
                                    @endif
                                    {{--@if ($errors->has('namecontact'))--}}
                                        {{--<span class="help-block">--}}
                                        {{--<strong>{{ $errors->first('namecontact') }}</strong>--}}
                                    {{--</span>--}}
                                    {{--@endif--}}
                                </div>
                            </div>

                            <div class="form-group">
                                <!--pallet account associated-->
                                <div class="col-lg-3">
                                    <label for="namecontact" class="control-label"><span>*</span> Pallet Account
                                        :</label>
                                </div>
                                <div class="col-lg-6">
                                    @if(isset($namepalletaccount))
                                        <select class="selectpicker show-tick form-control" data-size="5"
                                                data-live-search="true" data-live-search-style="startsWith"
                                                title="Pallets Account" name="namepalletaccount">
                                            @foreach($listPalletsAccounts as $palletsAccount )
                                                @if($palletsAccount==$namepalletaccount)
                                                    @php($option='selected')
                                                    <option {{$option}}>{{$palletsAccount}}</option>
                                                @else
                                                    <option>{{$palletsAccount}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    @else
                                        <select class="selectpicker show-tick form-control" data-size="5"
                                                data-live-search="true" data-live-search-style="startsWith"
                                                title="Pallets Account" name="namepalletaccount">
                                            @foreach($listPalletsAccounts as $palletsAccount )
                                                <option>{{$palletsAccount}}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                                <div class="col-lg-3 text-left">
                                    <a href="{{route('showAddPalletsaccount')}}" class="link"><span
                                                class="glyphicon glyphicon-plus-sign"></span> Add account</a>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-lg-8 col-lg-offset-3">
                                    <button type="submit" class="btn btn-primary btn-block btn-form"
                                            name="addWarehouse" data-toggle="modal" data-target="#addWarehouse_modal">
                                        Add
                                    </button>
                                    {{--<button type="button" class="btn btn-primary btn-block btn-form"--}}
                                    {{--data-toggle="modal"--}}
                                    {{--data-target="#addWarehouse_modal">Add--}}
                                    {{--</button>--}}
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
                                                <form role="form" method="POST" action="{{route('addWarehouse')}}">
                                                    {{--<input type="hidden" name="_method" value="delete">--}}
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
                                                                name="validateAddWarehouse">
                                                            Yes
                                                        </button>

                                                        <button type="submit" class="btn btn-success btn-modal"
                                                                value="close" name="refuseAddWarehouse">No
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-default btn-modal"
                                                        value="close" name="refuseAddWarehouse">
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
                @endif
            </div>
@endsection