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

@section('scriptBegin')
    <script type="text/javascript" src="{{asset('js/addUpdatePalletstransfer.js')}}"></script>

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
                                @if(Session::has('errorFields'))
                                    <div class="alert alert-danger text-alert text-center">{{ Session::get('errorFields') }}</div>
                                @endif
                            </div>
                            <div class="form-group">
                                <!--type-->
                                <div class="col-lg-1 col-lg-offset-1">
                                    <label for="type" class="control-label"><span>*</span>Type
                                        :</label>
                                </div>
                                <div class="col-lg-2">
                                    <select class="selectpicker show-tick form-control" data-size="10"
                                            data-live-search="true" data-live-search-style="startsWith"
                                            title="Type" name="type" id="type" required
                                            onchange="displayFieldsType(this);">
                                        @if(Illuminate\Support\Facades\Input::old('type'))
                                            <optgroup label="Normal">
                                                <option @if(old('type') == 'Deposit-Withdrawal') selected
                                                        @endif value="Deposit-Withdrawal"
                                                        id="Deposit-WithdrawalOption">
                                                    Deposit-Withdrawal
                                                </option>
                                                <option @if(old('type') == 'Withdrawal-Deposit') selected
                                                        @endif value="Withdrawal-Deposit"
                                                        id="Withdrawal-DepositOption">
                                                    Withdrawal-Deposit
                                                </option>
                                                <option @if(old('type') == 'Deposit_Only') selected
                                                        @endif value="Deposit_Only"
                                                        id="Deposit_OnlyOption">
                                                    Deposit_Only
                                                </option>
                                                <option @if(old('type') == 'Withdrawal_Only') selected
                                                        @endif value="Withdrawal_Only"
                                                        id="Withdrawal_OnlyOption">
                                                    Withdrawal_Only
                                                </option>
                                            </optgroup>
                                            <optgroup label="Correcting">
                                                <option @if(old('type') == 'Purchase_Ext') selected
                                                        @endif value="Purchase_Ext"
                                                        id="Purchase_ExtOption">
                                                    Purchase_Ext
                                                </option>
                                                <option @if(old('type') == 'Purchase_Int') selected
                                                        @endif value="Purchase_Int"
                                                        id="Purchase_IntOption">
                                                    Purchase_Int
                                                </option>
                                                <option @if(old('type') == 'Sale_Ext') selected
                                                        @endif value="Sale_Ext"
                                                        id="Sale_ExtOption">
                                                    Sale_Ext
                                                </option>
                                                <option @if(old('type') == 'Sale_Int') selected
                                                        @endif value="Sale_Int"
                                                        id="Sale_IntOption">
                                                    Sale_Int
                                                </option>
                                                <option @if(old('type') == 'Other') selected
                                                        @endif value="Other"
                                                        id="OtherOption">
                                                    Other
                                                </option>
                                            </optgroup>
                                        @elseif(isset($type))
                                            <optgroup label="Normal">
                                                <option @if($type == 'Deposit-Withdrawal') selected
                                                        @endif value="Deposit-Withdrawal"
                                                        id="Deposit-WithdrawalOption">
                                                    Deposit-Withdrawal
                                                </option>
                                                <option @if($type == 'Withdrawal-Deposit') selected
                                                        @endif value="Withdrawal-Deposit"
                                                        id="Withdrawal-DepositOption">
                                                    Withdrawal-Deposit
                                                </option>
                                                <option @if($type == 'Deposit_Only') selected
                                                        @endif value="Deposit_Only"
                                                        id="Deposit_OnlyOption">
                                                    Deposit_Only
                                                </option>
                                                <option @if($type == 'Withdrawal_Only') selected
                                                        @endif value="Withdrawal_Only"
                                                        id="Withdrawal_OnlyOption">
                                                    Withdrawal_Only
                                                </option>
                                            </optgroup>
                                            <optgroup label="Correcting">
                                                <option @if($type == 'Purchase_Ext') selected
                                                        @endif value="Purchase_Ext"
                                                        id="Purchase_ExtOption">
                                                    Purchase_Ext
                                                </option>
                                                <option @if($type == 'Purchase_Int') selected
                                                        @endif value="Purchase_Int"
                                                        id="Purchase_IntOption">
                                                    Purchase_Int
                                                </option>
                                                <option @if($type == 'Sale_Ext') selected
                                                        @endif value="Sale_Ext"
                                                        id="Sale_ExtOption">
                                                    Sale_Ext
                                                </option>
                                                <option @if($type == 'Sale_Int') selected
                                                        @endif value="Sale_Int"
                                                        id="Sale_IntOption">
                                                    Sale_Int
                                                </option>
                                                <option @if($type == 'Other') selected
                                                        @endif value="Other"
                                                        id="OtherOption">
                                                    Other
                                                </option>
                                            </optgroup>
                                        @else
                                            <optgroup label="Normal">
                                                <option value="Deposit-Withdrawal"
                                                        id="Deposit-WithdrawalOption">
                                                    Deposit-Withdrawal
                                                </option>
                                                <option value="Withdrawal-Deposit"
                                                        id="Withdrawal-DepositOption">
                                                    Withdrawal-Deposit
                                                </option>
                                                <option value="Deposit_Only"
                                                        id="Deposit_OnlyOption">
                                                    Deposit_Only
                                                </option>
                                                <option value="Withdrawal_Only"
                                                        id="Withdrawal_OnlyOption">
                                                    Withdrawal_Only
                                                </option>
                                            </optgroup>
                                            <optgroup label="Correcting">
                                                <option value="Purchase_Ext"
                                                        id="Purchase_ExtOption">
                                                    Purchase_Ext
                                                </option>
                                                <option value="Purchase_Int"
                                                        id="Purchase_IntOption">
                                                    Purchase_Int
                                                </option>
                                                <option value="Sale_Ext"
                                                        id="Sale_ExtOption">
                                                    Sale_Ext
                                                </option>
                                                <option value="Sale_Int"
                                                        id="Sale_IntOption">
                                                    Sale_Int
                                                </option>
                                                <option value="Other"
                                                        id="otherOption">
                                                    Other
                                                </option>
                                            </optgroup>
                                        @endif
                                    </select>
                                </div>
                                <!--details-->
                                <div class="col-lg-4">
                                    @if(isset($details))
                                        <textarea class="form-control" rows="1" id="details"
                                                  placeholder="Details (broken pallets, gift, receipt...)">{{$details}}</textarea>
                                    @else
                                        <textarea class="form-control" rows="1" id="details"
                                                  placeholder="Details (broken pallets, gift, receipt...)">{{old('details')}}</textarea>
                                    @endif
                                </div>
                                <!--atrnr-->
                                <div class="col-lg-1 text-left">
                                    <label for="loading_atrnr"
                                           class="control-label">@if(isset($type)&&($type=='Deposit-Withdrawal'||$type=='Withdrawal-Deposit'))
                                            <span id="atrnr" style="display:inline-block">*</span>@else<span id="atrnr"
                                                                                                             style="display:none">*</span>@endif
                                        Atrnr
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
                            <!--deposit-->
                            @if(isset($type) && $type=='Deposit-Withdrawal')
                                <div class="form-group" id="deposit-withdrawal1" style="display:block">
                                    @else
                                        <div class="form-group" id="deposit-withdrawal1">
                                            @endif
                                            <div class="col-lg-12 text-center">
                                                <label for="deposit" class="control-label">DEPOSIT</label>
                                            </div>
                                            @if(isset($type) &&$type=='Deposit-Withdrawal')
                                        </div>
                                        @else
                                </div>
                            @endif
                        <!--withdrawal-->
                            @if(isset($type) &&$type=='Withdrawal-Deposit')
                                <div class="form-group" id="withdrawal-deposit1" style="display:block">
                                    @else
                                        <div class="form-group" id="withdrawal-deposit1">
                                            @endif
                                            <div class="col-lg-12 text-center">
                                                <label for="withdrawal" class="control-label">WITHDRAWAL</label>
                                            </div>
                                            @if(isset($type) &&$type=='Withdrawal-Deposit')
                                        </div>
                                        @else
                                </div>
                            @endif
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
                            </div>
                            <div class="form-group">
                                <!--debit account-->
                                @if(isset($type)&&($type=='Other'||$type=='Purchase_Int'||$type=='Sale_Ext'||$type=='Sale_Int'||$type=='Deposit-Withdrawal'||$type=='Withdrawal-Deposit'||$type=='Deposit_Only'||$type=='Withdrawal_Only'))
                                    <div class="col-lg-2" id="debitAccount1"
                                         style="display: block">
                                        @else
                                            <div class="col-lg-2" id="debitAccount1">
                                                @endif
                                                <label for="debitAccount" class="control-label"><span>*</span> Debit
                                                    account
                                                    :</label>
                                                @if(isset($type)&&($type=='Other'||$type=='Purchase_Int'||$type=='Sale_Ext'||$type=='Sale_Int'||$type=='Deposit-Withdrawal'||$type=='Withdrawal-Deposit'||$type=='Deposit_Only'||$type=='Withdrawal_Only'))
                                            </div>
                                            @else
                                    </div>
                                @endif
                                @if(isset($type)&&($type=='Other'||$type=='Purchase_Int'||$type=='Sale_Ext'||$type=='Sale_Int'||$type=='Deposit-Withdrawal'||$type=='Withdrawal-Deposit'||$type=='Deposit_Only'||$type=='Withdrawal_Only'))
                                    <div class="col-lg-4" id="debitAccount2"
                                         style="display: block">
                                        @else
                                            <div class="col-lg-4" id="debitAccount2">
                                                @endif
                                                <select class="selectpicker show-tick form-control" data-size="10"
                                                        id="select-debit"
                                                        data-live-search="true" data-live-search-style="startsWith"
                                                        title="Debit Account" name="debitAccount" onchange="selectAccount(this.value);">
                                                    @foreach($listPalletsAccounts as $palletsAccount )
                                                        @if(Illuminate\Support\Facades\Input::old('debitAccount') && (strpos(old('debitAccount'), '-') == 7 && explode('-', old('debitAccount'))[0] == 'account') && ($palletsAccount->id==explode('-', old('debitAccount'))[1]))
                                                            <option value="account-{{$palletsAccount->id}}"
                                                                    selected>{{$palletsAccount->name}}</option>
                                                        @elseif(isset($debitAccount) && (strpos($debitAccount, '-') == 7 && explode('-', $debitAccount)[0] == 'account') && ($palletsAccount->id==explode('-', $debitAccount)[1]))
                                                            <option value="account-{{$palletsAccount->id}}"
                                                                    selected>{{$palletsAccount->name}}</option>
                                                        @else
                                                            <option value="account-{{$palletsAccount->id}}">{{$palletsAccount->name}}</option>
                                                        @endif
                                                    @endforeach
                                                    @foreach($listTrucksAccounts as $trucksAccount )
                                                        @if(Illuminate\Support\Facades\Input::old('debitAccount') && (strpos(old('debitAccount'), '-') == 5 && explode('-', old('debitAccount'))[0] == 'truck') && ($trucksAccount->id==explode('-', old('debitAccount'))[1]))
                                                            <option value="truck-{{$trucksAccount->id}}"
                                                                    selected>{{$trucksAccount->name}}
                                                                - {{$trucksAccount->licensePlate}}</option>
                                                        @elseif(isset($debitAccount)&& (strpos($debitAccount, '-') == 5 && explode('-', $debitAccount)[0] == 'truck') && ($trucksAccount->id==explode('-', $debitAccount)[1]))
                                                            <option value="truck-{{$trucksAccount->id}}"
                                                                    selected>{{$trucksAccount->name}}
                                                                - {{$trucksAccount->licensePlate}}</option>
                                                        @else
                                                            <option value="truck-{{$trucksAccount->id}}">{{$trucksAccount->name}}
                                                                - {{$trucksAccount->licensePlate}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @if(isset($type)&&($type=='Other'||$type=='Purchase_Int'||$type=='Sale_Ext'||$type=='Sale_Int'||$type=='Deposit-Withdrawal'||$type=='Withdrawal-Deposit'||$type=='Deposit_Only'||$type=='Withdrawal_Only'))
                                            </div>
                                            @else
                                    </div>
                                @endif

                            <!--credit account-->
                                @if(isset($type)&&($type=='Other'||$type=='Purchase_Int'||$type=='Purchase_Ext'||$type=='Sale_Int'||$type=='Deposit-Withdrawal'||$type=='Withdrawal-Deposit'||$type=='Deposit_Only'||$type=='Withdrawal_Only'))
                                    <div class="col-lg-2" id="creditAccount1"
                                         style="display: block">
                                        @else
                                            <div class="col-lg-2" id="creditAccount1">
                                                @endif
                                                <label for="creditAccount" class="control-label"><span>*</span> Credit
                                                    account
                                                    :</label>
                                                @if(isset($type)&&($type=='Other'||$type=='Purchase_Int'||$type=='Purchase_Ext'||$type=='Sale_Int'||$type=='Deposit-Withdrawal'||$type=='Withdrawal-Deposit'||$type=='Deposit_Only'||$type=='Withdrawal_Only'))
                                            </div>
                                            @else
                                    </div>
                                @endif
                                @if(isset($type)&&($type=='Other'||$type=='Purchase_Int'||$type=='Purchase_Ext'||$type=='Sale_Int'||$type=='Deposit-Withdrawal'||$type=='Withdrawal-Deposit'||$type=='Deposit_Only'||$type=='Withdrawal_Only'))
                                    <div class="col-lg-4" id="creditAccount2"
                                         style="display: block">
                                        @else
                                            <div class="col-lg-4" id="creditAccount2">
                                                @endif
                                                <select class="selectpicker show-tick form-control" data-size="10"
                                                        data-live-search="true" data-live-search-style="startsWith"
                                                        title="Credit Account" name="creditAccount" id="select-credit"
                                                        onchange="creditaccount(this.value);">
                                                    @foreach($listPalletsAccounts as $palletsAccount )
                                                        @if(Illuminate\Support\Facades\Input::old('creditAccount') && (strpos(old('creditAccount'), '-') == 7 && explode('-', old('creditAccount'))[0] == 'account') && ($palletsAccount->id==explode('-', old('creditAccount'))[1]))
                                                            <option value="account-{{$palletsAccount->id}}"
                                                                    selected>{{$palletsAccount->name}}</option>
                                                        @elseif(isset($creditAccount) && (strpos($creditAccount, '-') == 7 && explode('-', $creditAccount)[0] == 'account') && ($palletsAccount->id==explode('-', $creditAccount)[1]))
                                                            <option value="account-{{$palletsAccount->id}}"
                                                                    selected>{{$palletsAccount->name}}</option>
                                                        @else
                                                            <option value="account-{{$palletsAccount->id}}">{{$palletsAccount->name}}</option>
                                                        @endif
                                                    @endforeach
                                                    @foreach($listTrucksAccounts as $trucksAccount )
                                                        @if(Illuminate\Support\Facades\Input::old('creditAccount') && (strpos(old('creditAccount'), '-') == 5 && explode('-', old('creditAccount'))[0] == 'truck') && ($trucksAccount->id==explode('-', old('creditAccount'))[1]))
                                                            <option value="truck-{{$trucksAccount->id}}"
                                                                    selected>{{$trucksAccount->name}}
                                                                - {{$trucksAccount->licensePlate}}</option>
                                                        @elseif(isset($creditAccount)&& (strpos($creditAccount, '-') == 5 && explode('-', $creditAccount)[0] == 'truck') && ($trucksAccount->id==explode('-', $creditAccount)[1]))
                                                            <option value="truck-{{$trucksAccount->id}}"
                                                                    selected>{{$trucksAccount->name}}
                                                                - {{$trucksAccount->licensePlate}}</option>
                                                        @else
                                                            <option value="truck-{{$trucksAccount->id}}">{{$trucksAccount->name}}
                                                                - {{$trucksAccount->licensePlate}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @if(isset($type)&&($type=='Other'||$type=='Purchase_Int'||$type=='Purchase_Ext'||$type=='Sale_Int'||$type=='Deposit-Withdrawal'||$type=='Withdrawal-Deposit'||$type=='Deposit_Only'||$type=='Withdrawal_Only'))
                                            </div>
                                            @else
                                    </div>
                                @endif
                            </div>
                            <div class="form-group">
                                @if(Session::has('errorFields2'))
                                    <div class="alert alert-danger text-alert text-center">{{ Session::get('errorFields2') }}</div>
                                @endif
                            </div>
                            <!--withdrawal-->
                            @if(isset($type) &&$type=='Deposit-Withdrawal')
                                <div id="deposit-withdrawal2" style="display:block">
                                    @else
                                        <div id="deposit-withdrawal2">
                                            @endif
                                            <div class="form-group">
                                                <div class="col-lg-12 text-center">
                                                    <label for="withdrawal" class="control-label">WITHDRAWAL</label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-lg-12 text-center">
                                                    <p>You should fulfill the withdrawal associated. If you don't want
                                                        to do it now,
                                                        you will have to do it by the transfer details page</p>
                                                </div>
                                            </div>
                                            @if(isset($type) &&$type=='Deposit-Withdrawal')
                                        </div>
                                        @else
                                </div>
                            @endif
                        <!--deposit-->
                            @if(isset($type) &&$type=='Withdrawal-Deposit')
                                <div id="withdrawal-deposit2" style="display:block">
                                    @else
                                        <div id="withdrawal-deposit2">
                                            @endif
                                            <div class="form-group">
                                                <div class="col-lg-12 text-center">
                                                    <label for="deposit" class="control-label">DEPOSIT</label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-lg-12 text-center">
                                                    <p>You should fulfill the deposit associated. If you don't want to
                                                        do it now,
                                                        you will have to do it by the transfer details page</p>
                                                </div>
                                            </div>
                                            @if(isset($type) &&$type=='Withdrawal-Deposit')
                                        </div>
                                        @else
                                </div>
                            @endif
                        <!--2nd transfer-->
                            @if(isset($type) &&($type=='Withdrawal-Deposit' ||$type=='Deposit-Withdrawal'))
                                <div id="DW" style="display:block">
                                    @else
                                        <div id="DW">
                                            @endif
                                            <div class="form-group">
                                                <!--number of pallets-->
                                                <div class="col-lg-2">
                                                    <label for="palletsNumber2" class="control-label">Pal. nbr
                                                        :</label>
                                                </div>
                                                <div class="col-lg-1">
                                                    @if(Illuminate\Support\Facades\Input::old('palletsNumber2'))
                                                        <input id="palletsNumber2" type="number" class="form-control"
                                                               name="palletsNumber2"
                                                               value="{{ old('palletsNumber2') }}" placeholder="Nbr"
                                                               min="0"
                                                               autofocus>
                                                    @elseif(isset($palletsNumber2))
                                                        <input id="palletsNumber2" type="number" class="form-control"
                                                               name="palletsNumber2"
                                                               value="{{$palletsNumber2}}" placeholder="Nbr" min="0"
                                                               autofocus>
                                                    @else
                                                        <input id="palletsNumber2" type="number" class="form-control"
                                                               name="palletsNumber2"
                                                               value="" placeholder="Nbr" min="0"
                                                               autofocus>
                                                    @endif
                                                </div>
                                                <!--debit account-->
                                            {{--<div class="col-lg-1" >--}}
                                            {{--<label for="debitAccount2" class="control-label">--}}
                                            {{--Debit--}}
                                            {{--:</label>--}}
                                            {{--</div>--}}
                                            {{--<div class="col-lg-3">--}}
                                            {{--<select class="selectpicker show-tick form-control"--}}
                                            {{--data-size="10"--}}
                                            {{--data-live-search="true"--}}
                                            {{--data-live-search-style="startsWith"--}}
                                            {{--title="Debit Account" name="debitAccount2">--}}
                                            {{--@foreach($listNamesPalletsaccounts as $palletsAccount )--}}
                                            {{--@if(Illuminate\Support\Facades\Input::old('debitAccount2') && $palletsAccount==old('debitAccount2'))--}}
                                            {{--<option selected>{{$palletsAccount}}</option>--}}
                                            {{--@elseif(isset($debitAccount2)&& $palletsAccount==$debitAccount2)--}}
                                            {{--<option selected>{{$palletsAccount}}</option>--}}
                                            {{--@else--}}
                                            {{--<option>{{$palletsAccount}}</option>--}}
                                            {{--@endif--}}
                                            {{--@endforeach--}}
                                            {{--</select>--}}
                                            {{--</div>--}}
                                            <!--credit account-->
                                                {{--<div class="col-lg-2">--}}
                                                {{--<label for="creditAccount2" class="control-label">--}}
                                                {{--Credit--}}
                                                {{--ac.--}}
                                                {{--:</label>--}}
                                                {{--</div>--}}
                                                {{--<div class="col-lg-3">--}}
                                                {{--<select class="selectpicker show-tick form-control"--}}
                                                {{--data-size="10"--}}
                                                {{--data-live-search="true"--}}
                                                {{--data-live-search-style="startsWith"--}}
                                                {{--title="Credit Account" name="creditAccount2">--}}
                                                {{--@foreach($listNamesPalletsaccounts as $palletsAccount )--}}
                                                {{--@if(Illuminate\Support\Facades\Input::old('creditAccount2') && $palletsAccount==old('creditAccount2'))--}}
                                                {{--<option selected>{{$palletsAccount}}</option>--}}
                                                {{--@elseif(isset($creditAccount2)&& $palletsAccount==$creditAccount2)--}}
                                                {{--<option selected>{{$palletsAccount}}</option>--}}
                                                {{--@else--}}
                                                {{--<option>{{$palletsAccount}}</option>--}}
                                                {{--@endif--}}
                                                {{--@endforeach--}}
                                                {{--</select>--}}
                                                {{--</div>--}}
                                            </div>
                                            @if(isset($type) &&($type=='Withdrawal-Deposit'||$type=='Deposit-Withdrawal'))
                                        </div>
                                        @else
                                </div>
                            @endif
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
                                                        @if(Session::has('debitAccount'))
                                                            <th class="text-center">
                                                                DEBIT
                                                            </th>
                                                        @endif
                                                        @if(Session::has('creditAccount'))
                                                            <th class="text-center">
                                                                CREDIT
                                                            </th>
                                                        @endif
                                                        @if(Session::has('creditAccount2')&&Session::has('debitAccount2')&&Session::has('palletsNumber2'))
                                                            <th class="text-center">
                                                                DEBIT 2
                                                            </th>
                                                            <th class="text-center">
                                                                CREDIT 2
                                                            </th>
                                                        @endif
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td></td>
                                                        @if(Session::has('debitAccount'))
                                                            <td class="text-center">{{request()->session()->get('debitAccount')}}</td>
                                                        @endif
                                                        @if(Session::has('creditAccount'))
                                                            <td class="text-center">{{request()->session()->get('creditAccount')}}</td>
                                                        @endif
                                                        @if(Session::has('creditAccount2')&&Session::has('debitAccount2')&&Session::has('palletsNumber2'))
                                                            <td class="text-center">{{request()->session()->get('debitAccount2')}}</td>
                                                            <td class="text-center">{{request()->session()->get('creditAccount2')}}</td>
                                                        @endif
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">Actual</td>
                                                        @if(Session::has('debitAccount'))
                                                            <td class="text-center">{{request()->session()->get('palletsNumberDebitAccount')}}</td>
                                                        @endif
                                                        @if(Session::has('creditAccount'))
                                                            <td class="text-center">{{request()->session()->get('palletsNumberCreditAccount')}}</td>
                                                        @endif
                                                        @if(Session::has('creditAccount2')&&Session::has('debitAccount2')&&Session::has('palletsNumber2'))
                                                            <td class="text-center">{{request()->session()->get('palletsNumberDebitAccount2')}}</td>
                                                            <td class="text-center">{{request()->session()->get('palletsNumberCreditAccount2')}}</td>
                                                        @endif
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">New transfer</td>
                                                        @if(Session::has('debitAccount'))
                                                            <td class="text-center">
                                                                - {{request()->session()->get('palletsNumber')}}</td>
                                                        @endif
                                                        @if(Session::has('creditAccount'))
                                                            <td class="text-center">
                                                                + {{request()->session()->get('palletsNumber')}}</td>
                                                        @endif
                                                        @if(Session::has('creditAccount2')&&Session::has('debitAccount2')&&Session::has('palletsNumber2'))
                                                            <td class="text-center">
                                                                - {{request()->session()->get('palletsNumber2')}}</td>
                                                            <td class="text-center">
                                                                + {{request()->session()->get('palletsNumber2')}}</td>
                                                        @endif
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">Total</td>
                                                        @if(Session::has('debitAccount'))
                                                            <td class="text-center">
                                                                = {{request()->session()->get('palletsNumberDebitAccount') -request()->session()->get('palletsNumber')}}</td>
                                                        @endif
                                                        @if(Session::has('creditAccount'))
                                                            <td class="text-center">
                                                                = {{request()->session()->get('palletsNumberCreditAccount')+request()->session()->get('palletsNumber')}}</td>
                                                        @endif
                                                        @if(Session::has('creditAccount2')&&Session::has('debitAccount2')&&Session::has('palletsNumber2'))
                                                            <td class="text-center">
                                                                = {{request()->session()->get('palletsNumberDebitAccount2') -request()->session()->get('palletsNumber2')}}</td>
                                                            <td class="text-center">
                                                                = {{request()->session()->get('palletsNumberCreditAccount2')+request()->session()->get('palletsNumber2')}}</td>
                                                        @endif
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                @if(Session::has('creditAccount2')&&Session::has('debitAccount2')&&Session::has('palletsNumber2')&& (request()->session()->get('palletsNumber2')<>request()->session()->get('palletsNumber')))
                                                    <div class="text-center">
                                                        <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                        <span class="glyphicon glyphicon-warning-sign text-danger"></span><span
                                                                class="text-danger"> Pallets numbers are different for both transfers </span>
                                                    </div>
                                                @endif
                                                @if(($type=='Deposit-Withdrawal' || $type=='Withdrawal-Deposit')&&((Session::has('palletsNumber')&&isset($anz)&&request()->session()->get('palletsNumber')<>$anz)||(Session::has('palletsNumber2')&&isset($anz)&&(request()->session()->get('palletsNumber2')<>$anz))))
                                                    <div class="text-center">
                                                        <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                        <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                        <span class="text-danger">Pallets number doesn't match the number expected in the loading order ({{$anz}}
                                                            )</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                @if((Session::has('creditAccount2')&&Session::has('debitAccount2')&&Session::has('palletsNumber2'))||(($type=='Deposit-Withdrawal' || $type=='Withdrawal-Deposit')&&((Session::has('palletsNumber')&&isset($anz)&&request()->session()->get('palletsNumber')<>$anz)||(Session::has('palletsNumber2')&&isset($anz)&&(request()->session()->get('palletsNumber2')<>$anz)))))
                                                    <button type="submit"
                                                            class="btn btn-danger btn-modal"
                                                            value="yes"
                                                            name="okSubmitAddModal">
                                                        Confirm
                                                    </button>
                                                @else
                                                    <button type="submit"
                                                            class="btn btn-default btn-form btn-modal"
                                                            value="yes"
                                                            name="okSubmitAddModal">
                                                        Confirm
                                                    </button>
                                                @endif
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
