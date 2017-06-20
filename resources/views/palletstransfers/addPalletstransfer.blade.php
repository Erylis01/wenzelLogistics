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
                                <!--type-->
                                <div class="col-lg-1 col-lg-offset-1">
                                    <label for="type" class="control-label"><span>*</span>Type
                                        :</label>
                                </div>
                                <div class="col-lg-2">
                                    <select class="selectpicker show-tick form-control" data-size="5"
                                            data-live-search="true" data-live-search-style="startsWith"
                                            title="Type" name="type" required
                                    >
                                        @foreach($listTypes as $t )
                                            @if(Illuminate\Support\Facades\Input::old('type') && $t==old('type'))
                                                <option selected>{{$t}}</option>
                                            @elseif(isset($type)&&$t==$type)
                                                <option selected>{{$t}}</option>
                                                @else
                                                <option>{{$t}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <!--details-->
                                <div class="col-lg-4">
                                    @if(isset($details))
                                        <textarea class="form-control" rows="1" id="details" placeholder="Details">{{$details}}</textarea>
                                        @else
                                    <textarea class="form-control" rows="1" id="details" placeholder="Details">{{old('details')}}</textarea>
                                        @endif
                                </div>
                                <!--multitransfer-->
                                    <div class="col-lg-2 text-left">
                                        <label for="state"
                                               class="control-label ">Multi-Transfers ?
                                        </label>
                                    </div>
                                    <div class="col-lg-2 text-left">
                                        @if(Illuminate\Support\Facades\Input::old('multiTransfer') && old('multiTransfer')=='true'||(isset($multiTransfer)&&$multiTransfer=='true'))
                                            <label class="radio-inline"><input
                                                        type="radio"
                                                        name="multiTransfer"
                                                        value="true"
                                                        checked>Yes</label>
                                            <label class="radio-inline"><input
                                                        type="radio"
                                                        name="multiTransfer"
                                                        value="false">No</label>
                                        @else
                                            <label class="radio-inline"><input
                                                        type="radio"
                                                        name="multiTransfer"
                                                        value="true">Yes</label>
                                            <label class="radio-inline"><input
                                                        type="radio"
                                                        name="multiTransfer"
                                                        value="false"
                                                        checked>No</label>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <!--number of pallets-->
                                    <div class="col-lg-2">
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
                                <!--date-->
                                <div class="col-lg-1">
                                    <label for="date" class="control-label">Date :</label>
                                </div>
                                <div class="col-lg-2">
                                    @if(isset($date))
                                        <input id="date" type="date" class="form-control" name="date"
                                               value="{{ $date }}" placeholder="Date" autofocus>
                                        @else
                                    <input id="date" type="date" class="form-control" name="date"
                                           value="{{ old('date') }}" placeholder="Date" autofocus>
                                    @endif
                                </div>
                                    <!--atrnr-->
                                    <div class="col-lg-1 col-lg-offset-1">
                                        <label for="loading_atrnr" class="control-label">Atrnr
                                            :</label>
                                    </div>
                                    <div class="col-lg-2">
                                        <select class="selectpicker show-tick form-control" data-size="5"
                                                data-live-search="true" data-live-search-style="startsWith"
                                                title="Loading_atrnr" name="loading_atrnr" >
                                            @foreach($listAtrnr as $atrnr )
                                                @if(Illuminate\Support\Facades\Input::old('loading_atrnr') && $atrnr==old('loading_atrnr'))
                                                    <option selected>{{$atrnr}}</option>
                                                @elseif(isset($loading_atrnr)&&$atrnr==$loading_atrnr)
                                                    <option selected>{{$atrnr}}</option>
                                                @else
                                                    <option>{{$atrnr}}</option>
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
                                        @foreach($listNamesPalletsaccounts as $palletsAccount )
                                            @if(Illuminate\Support\Facades\Input::old('creditAccount') && $palletsAccount==old('creditAccount'))
                                                <option selected>{{$palletsAccount}}</option>
                                            @elseif(isset($creditAccount)&& $palletsAccount==$creditAccount)
                                                <option selected>{{$palletsAccount}}</option>
                                            @else
                                                <option>{{$palletsAccount}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <!--debit account-->
                                <div class="col-lg-2">
                                    <label for="debitAccount" class="control-label"><span>*</span> Debit
                                        account
                                        :</label>
                                </div>
                                <div class="col-lg-4">
                                    <select class="selectpicker show-tick form-control" data-size="10"
                                            data-live-search="true" data-live-search-style="startsWith"
                                            title="Debit Account" name="debitAccount" required>
                                        @foreach($listNamesPalletsaccounts as $palletsAccount )
                                            @if(Illuminate\Support\Facades\Input::old('debitAccount') && $palletsAccount==old('debitAccount'))
                                                <option selected>{{$palletsAccount}}</option>
                                            @elseif(isset($debitAccount)&& $palletsAccount==$debitAccount)
                                                <option selected>{{$palletsAccount}}</option>
                                            @else
                                                <option>{{$palletsAccount}}</option>
                                            @endif
                                        @endforeach
                                    </select>
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
                                        <div class="modal-header modalHeaderTransfer">
                                            <button type="submit"
                                                    class="close"
                                                    value="close"
                                                    name="closeSubmitAddModal">
                                                &times;
                                            </button>
                                            <h4 class="modal-title text-center">
                                                INFORMATION
                                                </h4>
                                        </div>
                                        <div class="modal-body center modalBodyTransfer">
                                                <p class="text-center">
                                                    Here,
                                                    PLANNED
                                                    pallets
                                                    number</p>
                                                <table class="table table-hover table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th></th>
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
                                                        <td></td>
                                                        <td class="text-center">{{request()->session()->get('creditAccount')}}</td>
                                                        <td class="text-center">{{request()->session()->get('debitAccount')}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">Actual</td>
                                                        <td class="text-center">{{request()->session()->get('palletsNumberCreditAccount')}}</td>
                                                        <td class="text-center">{{request()->session()->get('palletsNumberDebitAccount')}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">New transfer</td>
                                                        <td class="text-center">
                                                            + {{request()->session()->get('palletsNumber')}}</td>
                                                        <td class="text-center">
                                                            - {{request()->session()->get('palletsNumber')}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">Total</td>
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
                                                    class="btn btn-default btn-form btn-modal"
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