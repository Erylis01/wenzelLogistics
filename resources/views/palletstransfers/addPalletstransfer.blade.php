@extends('layouts.default')

@section('title')
    Add pallets transfers
@endsection

@section('stylesheet')
    <link href="{{asset('css/palletstransfers.css')}}" rel="stylesheet" type="text/css">
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
    class="nonActive"
@endsection
@section('classPalletsTransfers')
    class="active"
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
                        transfer
                    </div>
                    <div class="panel-body panel-body-general">
                        <form class="form-horizontal text-right" role="form" method="POST"
                              action="{{route('addPalletstransfer')}}">
                            {{ csrf_field() }}
                            <p class="text-center legend-auth">* required field</p>
                            <div class="form-group">
                                <!--date-->
                                <div class="col-lg-1 col-lg-offset-1">
                                    <label for="date" class="control-label"><span>*</span> Date :</label>
                                </div>
                                <div class="col-lg-2">
                                    @if(isset($date))
                                        <input id="date" type="date" class="form-control" name="date"
                                               value="{{ $date }}" placeholder="Date" required autofocus>
                                        @else
                                    <input id="date" type="date" class="form-control" name="date"
                                           value="{{ old('date') }}" placeholder="Date" required autofocus>
                                    @endif
                                    @if ($errors->has('date'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('date') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <!--number of pallets-->
                                <div class="col-lg-2 col-lg-offset-2">
                                    <label for="palletsNumber" class="control-label"><span>*</span> Pallets number
                                        :</label>
                                </div>
                                <div class="col-lg-1">
                                    @if(Illuminate\Support\Facades\Input::old('palletsNumber'))
                                    <input id="palletsNumber" type="number" class="form-control"
                                           name="palletsNumber"
                                           value="{{ old('palletsNumber') }}" placeholder="Pallets Number"
                                           required autofocus>
                                        @elseif(isset($palletsNumber))
                                        <input id="palletsNumber" type="number" class="form-control"
                                               name="palletsNumber"
                                               value="{{$palletsNumber}}" placeholder="Nbr"
                                               required autofocus>
                                        @else
                                        <input id="palletsNumber" type="number" class="form-control"
                                               name="palletsNumber"
                                               value="0" placeholder="Nbr"
                                               required autofocus>
                                    @endif
                                </div>

                                <!--type-->
                                <div class="col-lg-1">
                                    <label for="type" class="control-label">Type
                                        :</label>
                                </div>
                                <div class="col-lg-2">
                                    <select class="selectpicker show-tick form-control" data-size="5"
                                            data-live-search="true" data-live-search-style="startsWith"
                                            title="Type" name="type"
                                            >
                                        @foreach($listTypes as $type )
                                            @if(Illuminate\Support\Facades\Input::old('type') && $type==old('type'))
                                                <option selected>{{$type}}</option>
                                            @else
                                                <option>{{$type}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <!--credit account-->
                                <div class="col-lg-2">
                                    <label for="creditAccount" class="control-label"><span>*</span> Credit
                                        account
                                        :</label>
                                </div>
                                <div class="col-lg-4">
                                    <select class="selectpicker show-tick form-control" data-size="10"
                                            data-live-search="true" data-live-search-style="startsWith"
                                            title="Credit Account" name="creditAccount" required>
                                        @foreach($listPalletsaccounts as $palletsAccount )
                                            @if(Illuminate\Support\Facades\Input::old('creditAccount') && $palletsAccount->name==old('creditAccount'))
                                                <option selected>{{$palletsAccount->name}}</option>
                                            @elseif(isset($creditAccount)&& $palletsAccount->name==$creditAccount)
                                                <option selected>{{$palletsAccount->name}}</option>
                                            @else
                                                <option>{{$palletsAccount->name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @if ($errors->has('creditAccount'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('creditAccount') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <!--credit account-->
                                <div class="col-lg-2">
                                    <label for="debitAccount" class="control-label"><span>*</span> Debit
                                        account
                                        :</label>
                                </div>
                                <div class="col-lg-4">
                                    <select class="selectpicker show-tick form-control" data-size="10"
                                            data-live-search="true" data-live-search-style="startsWith"
                                            title="Debit Account" name="debitAccount" required>
                                        @foreach($listPalletsaccounts as $palletsAccount )
                                            @if(Illuminate\Support\Facades\Input::old('debitAccount') && $palletsAccount->name==old('debitAccount'))
                                                <option selected>{{$palletsAccount->name}}</option>
                                            @elseif(isset($debitAccount)&& $palletsAccount->name==$debitAccount)
                                                <option selected>{{$palletsAccount->name}}</option>
                                            @else
                                                <option>{{$palletsAccount->name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @if ($errors->has('debitAccount'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('debitAccount') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-4 col-lg-offset-2">
                                    <input type="submit"
                                           class="btn btn-primary btn-block btn-form"
                                           value="Add"
                                           name="addPalletstransfer" data-toggle="modal"
                                           data-target="#submitAdd_modal">
                                </div>
                                <div class="col-lg-2 col-lg-offset-3 text-left">
                                    <a href="{{route('showAddPalletsaccount')}}" class="link"><span
                                                class="glyphicon glyphicon-plus-sign"></span> Add account</a>
                                </div>
                            </div>
                            <!-- Modal submit -->
                            @if(isset($addPalletstransfer))
                            <div class="modal show"
                                 id="submitAdd_modal"
                                 role="dialog">
                                <div class="modal-dialog modal-md">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="submit"
                                                    class="close"
                                                    value="close"
                                                    name="closeSubmitAddModal">
                                                &times;
                                            </button>
                                            <h4 class="modal-title text-center">
                                                Information
                                                :</h4>
                                        </div>
                                        <div class="modal-body center">
                                                <p class="text-center">
                                                    Here,
                                                    planned
                                                    pallets
                                                    number</p>
                                                <table class="table table-hover table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-center">
                                                            CREDIT
                                                        </th>
                                                        <th class="text-center">
                                                            DEBIT
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td class="text-center">{{request()->session()->get('creditAccount')}}</td>
                                                        <td class="text-center">{{request()->session()->get('debitAccount')}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">{{request()->session()->get('palletsNumberCreditAccount')}}</td>
                                                        <td class="text-center">{{request()->session()->get('palletsNumberDebitAccount')}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">
                                                            + {{request()->session()->get('palletsNumber')}}</td>
                                                        <td class="text-center">
                                                            - {{request()->session()->get('palletsNumber')}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">
                                                            = {{request()->session()->get('palletsNumberCreditAccount')- request()->session()->get('actualPalletsNumber') +request()->session()->get('palletsNumber')}}</td>
                                                        <td class="text-center">
                                                            = {{request()->session()->get('palletsNumberDebitAccount')  + request()->session()->get('actualPalletsNumber') -request()->session()->get('palletsNumber')}}</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit"
                                                    class="btn btn-default btn-modal"
                                                    value="yes"
                                                    name="okSubmitAddModal">
                                                Confirm
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                @endif
                        </form>

                    </div>

                </div>
                @endif
            </div>
    </div>
@endsection