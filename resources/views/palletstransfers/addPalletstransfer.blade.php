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

<script type="text/javascript" src="{{asset('js/addUpdatePalletstransfer.js')}}">
</script>

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
                                    <select class="selectpicker show-tick form-control" data-size="10"
                                            data-live-search="true" data-live-search-style="startsWith"
                                            title="Type" name="type" id="type" required onchange="displayFieldsType(this);"
                                    >
                                        @if(Illuminate\Support\Facades\Input::old('type'))
                                            <option @if(old('type') == 'Purchase_Ext') selected @endif value="Purchase_Ext"
                                                    id="Purchase_ExtOption">Purchase_Ext
                                            </option>
                                            <option @if(old('type') == 'Purchase_Int') selected @endif value="Purchase_Int"
                                                    id="Purchase_IntOption">Purchase_Int
                                            </option>
                                            <option @if(old('type') == 'Sale_Ext') selected @endif value="Sale_Ext"
                                                    id="Sale_ExtOption">Sale_Ext
                                            </option>
                                            <option @if(old('type') == 'Sale_Int') selected @endif value="Sale_Int"
                                                    id="Sale_IntOption">Sale_Int
                                            </option>
                                            <option @if(old('type') == 'Deposit-Withdrawal') selected @endif value="Deposit-Withdrawal"
                                                    id="Deposit-WithdrawalOption">Deposit-Withdrawal
                                            </option>
                                            <option @if(old('type') == 'Deposit_Only') selected @endif value="Deposit_Only"
                                                    id="Deposit_OnlyOption">Deposit_Only
                                            </option>
                                            <option @if(old('type') == 'Withdrawal_Only') selected @endif value="Withdrawal_Only"
                                                    id="Withdrawal_OnlyOption">Withdrawal_Only
                                            </option>
                                            <option @if(old('type') == 'Other') selected @endif value="Other" id="OtherOption">Other</option>
                                        @else
                                            <option value="Purchase_Ext" id="Purchase_ExtOption">Purchase_Ext</option>
                                            <option value="Purchase_Int" id="Purchase_IntOption">Purchase_Int</option>
                                            <option value="Sale_Ext" id="Sale_ExtOption">Sale_Ext</option>
                                            <option value="Sale_Int" id="Sale_IntOption">Sale_Int</option>
                                            <option value="Deposit-Withdrawal" id="Deposit-WithdrawalOption">Deposit-Withdrawal</option>
                                            <option value="Deposit_Only" id="Deposit_OnlyOption">Deposit_Only</option>
                                            <option value="Withdrawal_Only" id="Withdrawal_OnlyOption">Withdrawal_Only</option>
                                            <option value="Other" id="otherOption">Other</option>
                                        @endif
                                    </select>
                                </div>
                                <!--details-->
                                <div class="col-lg-4">
                                    @if(isset($details))
                                        <textarea class="form-control" rows="1" id="details" placeholder="Details (broken pallets, gift, receipt...)">{{$details}}</textarea>
                                        @else
                                    <textarea class="form-control" rows="1" id="details" placeholder="Details (broken pallets, gift, receipt...)">{{old('details')}}</textarea>
                                        @endif
                                </div>
                                <!--atrnr-->
                                <div class="col-lg-1 text-left">
                                    <label for="loading_atrnr" class="control-label">Atrnr
                                        :</label>
                                </div>
                                <div class="col-lg-2">
                                    <select class="selectpicker show-tick form-control" data-size="5"
                                            data-live-search="true" data-live-search-style="startsWith"
                                            title="Loading_atrnr" name="loading_atrnr" id="loading_atrnrSelect"
                                            onchange="displayFieldsAtrnr(this);">
                                        <option value="">No loading</option>
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
                                    <!--number of pallets-->
                                    <div class="col-lg-2">
                                        <label for="palletsNumber" class="control-label"><span>*</span> Pallets number
                                            :</label>
                                    </div>
                                    <div class="col-lg-1">
                                        @if(Illuminate\Support\Facades\Input::old('palletsNumber'))
                                            <input id="palletsNumber" type="number" class="form-control"
                                                   name="palletsNumber"
                                                   value="{{ old('palletsNumber') }}" placeholder="Nbr" min="0"
                                                   required autofocus>
                                        @elseif(isset($palletsNumber))
                                            <input id="palletsNumber" type="number" class="form-control"
                                                   name="palletsNumber"
                                                   value="{{$palletsNumber}}" placeholder="Nbr" min="0"
                                                   required autofocus>
                                        @else
                                            <input id="palletsNumber" type="number" class="form-control"
                                                   name="palletsNumber"
                                                   value="0" placeholder="Nbr" min="0"
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
                                    <div class="col-lg-2 col-lg-offset-3 text-left">
                                        <a href="{{route('showAddPalletsaccount')}}" class="link"><span
                                                    class="glyphicon glyphicon-plus-sign"></span> Add account</a>
                                    </div>
                                    {{--<!--multitransfer-->--}}
                                    {{--@if(isset($loading_atrnr))--}}
                                        {{--<div>--}}
                                            {{--@else--}}
                                                {{--<div id="loading_atrnrLink">--}}
                                                    {{--@endif--}}
                                    {{--<div class="col-lg-2 col-lg-offset-1 text-left">--}}
                                        {{--<label for="state"--}}
                                               {{--class="control-label ">Multi-Transfers ?--}}
                                        {{--</label>--}}
                                    {{--</div>--}}
                                    {{--<div class="col-lg-2 text-left">--}}
                                        {{--@if(Illuminate\Support\Facades\Input::old('multiTransfer') && old('multiTransfer')=='true'||(isset($multiTransfer)&&$multiTransfer=='true'))--}}
                                            {{--<label class="radio-inline"><input--}}
                                                        {{--type="radio"--}}
                                                        {{--name="multiTransfer"--}}
                                                        {{--value="true"--}}
                                                        {{--checked>Yes</label>--}}
                                            {{--<label class="radio-inline"><input--}}
                                                        {{--type="radio"--}}
                                                        {{--name="multiTransfer"--}}
                                                        {{--value="false">No</label>--}}
                                        {{--@else--}}
                                            {{--<label class="radio-inline"><input--}}
                                                        {{--type="radio"--}}
                                                        {{--name="multiTransfer"--}}
                                                        {{--value="true">Yes</label>--}}
                                            {{--<label class="radio-inline"><input--}}
                                                        {{--type="radio"--}}
                                                        {{--name="multiTransfer"--}}
                                                        {{--value="false"--}}
                                                        {{--checked>No</label>--}}
                                        {{--@endif--}}
                                    {{--</div>--}}
                                                    {{--@if(isset($loading_atrnr))--}}
                                                        {{--</div>--}}
                                                            {{--@else--}}
                                                                {{--</div>--}}
                                                                    {{--@endif--}}
                            </div>

                            <div class="form-group">
                                <!--credit account-->
                                <div class="col-lg-2" id="creditAccount1">
                                    <label for="creditAccount" class="control-label"><span>*</span> Credit
                                        account
                                        :</label>
                                </div>
                                <div class="col-lg-4" id="creditAccount2">
                                    <select class="selectpicker show-tick form-control" data-size="10"
                                            data-live-search="true" data-live-search-style="startsWith"
                                            title="Credit Account" name="creditAccount" required >
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
                                <div class="col-lg-2" id="debitAccount1">
                                    <label for="debitAccount" class="control-label"><span>*</span> Debit
                                        account
                                        :</label>
                                </div>
                                <div class="col-lg-4" id="debitAccount2">
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
                                <div class="col-lg-4 col-lg-offset-4">
                                    <input type="submit"
                                           class="btn btn-primary btn-block btn-form"
                                           value="Add"
                                           name="addPalletstransfer" data-toggle="modal"
                                           data-target="#submitAdd_modal">
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
