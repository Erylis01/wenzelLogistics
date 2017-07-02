@extends('layouts.default')

@section('title')
    Pallets transfer details
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
                @if($transfer->state=="Untreated")
                    <div class="panel panelUntreated">
                        @elseif ($transfer->state=="Waiting documents")
                            <div class="panel panelWaitingdocuments">
                                @elseif ($transfer->state=="Complete")
                                    <div class="panel panelComplete">
                                        @elseif ($transfer->state=="Complete Validated")
                                            <div class="panel panel-general">
                                                @endif
                                                <div class="panel-heading">
                                                    <div class="col-lg-11 text-left">Details of the pallets
                                                        transfer n° {{$transfer->id}}
                                                        @if(!empty($errors))
                                                            @foreach($errors as $error)
                                                            <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                            @endforeach
                                                            @endif
                                                    </div>
                                                    <div>
                                                        <button type="button"
                                                                class=" btn btn-primary btn-form glyphicon glyphicon-remove"
                                                                data-toggle="modal"
                                                                data-target="#deletePalletstransfer_modal"
                                                                value="{{$transfer->id}}"
                                                                name="deletePalletstransferModal"
                                                        ></button>
                                                    </div>
                                                </div>
                                                <form class="form-horizontal text-right" role="form" method="POST"
                                                      action="{{route('updatePalletstransfer', $transfer->id)}}"
                                                      enctype="multipart/form-data">
                                                    <input type="hidden"
                                                           name="_token"
                                                           value="{{ csrf_token() }}">
                                                    <div class="panel-body panel-body-general">
                                                        @if(Session::has('messageUpdatePalletstransfer'))
                                                            <div class="alert alert-success text-alert text-center">{{ Session::get('messageUpdatePalletstransfer') }}</div>
                                                        @elseif(Session::has('messageErrorUpload'))
                                                            <div class="alert alert-danger text-alert text-center">{{ Session::get('messageErrorUpload') }}</div>
                                                        @elseif(Session::has('errorFields'))
                                                            <div class="alert alert-danger text-alert text-center">{{ Session::get('errorFields') }}</div>
                                                        @elseif(Session::has('messageUpdateValidatePalletstransfer'))
                                                            <div class="alert alert-success text-alert text-center">{{ Session::get('messageUpdateValidatePalletstransfer') }}</div>
                                                        @endif
                                                        <div class="form-group">
                                                            <!--type-->
                                                            <div class="col-lg-1 col-lg-offset-1">
                                                                <label for="type"
                                                                       class="control-label"><span>*</span> Type
                                                                    :</label>
                                                            </div>
                                                            <div class="col-lg-2">
                                                                @if(isset($transfer->validate) && $transfer->validate==1)
                                                                    <input type="text" name="type"
                                                                           class="form-control"
                                                                           value="{{$transfer->type}}"
                                                                           required
                                                                           readonly>
                                                                @else
                                                                    <select class="selectpicker show-tick form-control"
                                                                            data-size="10"
                                                                            data-live-search="true"
                                                                            data-live-search-style="startsWith"
                                                                            title="Type" name="type" id="type" required
                                                                            onchange="displayFieldsType(this);"
                                                                    >
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
                                                                        @elseif(isset($transfer->type))
                                                                            <optgroup label="Normal">
                                                                                <option @if($transfer->type == 'Deposit-Withdrawal') selected
                                                                                        @endif value="Deposit-Withdrawal"
                                                                                        id="Deposit-WithdrawalOption">
                                                                                    Deposit-Withdrawal
                                                                                </option>
                                                                                <option @if($transfer->type == 'Withdrawal-Deposit') selected
                                                                                        @endif value="Withdrawal-Deposit"
                                                                                        id="Withdrawal-DepositOption">
                                                                                    Withdrawal-Deposit
                                                                                </option>
                                                                                <option @if($transfer->type == 'Deposit_Only') selected
                                                                                        @endif value="Deposit_Only"
                                                                                        id="Deposit_OnlyOption">
                                                                                    Deposit_Only
                                                                                </option>
                                                                                <option @if($transfer->type == 'Withdrawal_Only') selected
                                                                                        @endif value="Withdrawal_Only"
                                                                                        id="Withdrawal_OnlyOption">
                                                                                    Withdrawal_Only
                                                                                </option>
                                                                            </optgroup>
                                                                            <optgroup label="Correcting">
                                                                                <option @if($transfer->type == 'Purchase_Ext') selected
                                                                                        @endif value="Purchase_Ext"
                                                                                        id="Purchase_ExtOption">
                                                                                    Purchase_Ext
                                                                                </option>
                                                                                <option @if($transfer->type == 'Purchase_Int') selected
                                                                                        @endif value="Purchase_Int"
                                                                                        id="Purchase_IntOption">
                                                                                    Purchase_Int
                                                                                </option>
                                                                                <option @if($transfer->type == 'Sale_Ext') selected
                                                                                        @endif value="Sale_Ext"
                                                                                        id="Sale_ExtOption">
                                                                                    Sale_Ext
                                                                                </option>
                                                                                <option @if($transfer->type == 'Sale_Int') selected
                                                                                        @endif value="Sale_Int"
                                                                                        id="Sale_IntOption">
                                                                                    Sale_Int
                                                                                </option>
                                                                                <option @if($transfer->type == 'Other') selected
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
                                                                @endif
                                                            </div>
                                                            <!--details-->
                                                            <div class="col-lg-3">
                                                                @if(isset($transfer->details)&&(isset($transfer->validate) && $transfer->validate==1))
                                                                    <textarea class="form-control" rows="1"
                                                                              id="details" placeholder="Details"
                                                                              readonly>{{$transfer->details}}</textarea>
                                                                @elseif(isset($transfer->details))
                                                                    <textarea class="form-control" rows="1"
                                                                              id="details"
                                                                              placeholder="Details">{{$transfer->details}}</textarea>
                                                                @elseif(isset($transfer->validate) && $transfer->validate==1)
                                                                    <textarea class="form-control" rows="1"
                                                                              id="details" placeholder="Details"
                                                                              readonly>{{old('details')}}</textarea>
                                                                @else
                                                                    <textarea class="form-control" rows="1"
                                                                              id="details"
                                                                              placeholder="Details">{{old('details')}}</textarea>
                                                                @endif
                                                            </div>
                                                            <!--atrnr-->
                                                            <div class="col-lg-1 text-left">
                                                                <label for="loading_atrnr" class="control-label">@if(isset($transfer->type)&&($transfer->type=='Deposit-Withdrawal'||$transfer->type=='Withdrawal-Deposit'))<span id="atrnr" style="display:inline-block">*</span>@else<span id="atrnr" style="display:none">*</span>@endif Atrnr
                                                                    :</label>
                                                            </div>
                                                            <div class="col-lg-2">
                                                                @if(isset($transfer->loading_atrnr)&&(isset($transfer->validate) && $transfer->validate==1))
                                                                    <input type="text" name="loading_atrnr"
                                                                           class="form-control"
                                                                           value="{{$transfer->loading_atrnr}}"
                                                                           readonly>
                                                                @elseif(isset($transfer->validate) && $transfer->validate==1)
                                                                    <input type="text" name="loading_atrnr"
                                                                           class="form-control"
                                                                           value="{{old('loading_atrnr')}}"
                                                                           readonly>
                                                                @else
                                                                    <select class="selectpicker show-tick form-control"
                                                                            data-size="5"
                                                                            data-live-search="true"
                                                                            data-live-search-style="startsWith"
                                                                            title="Loading_atrnr"
                                                                            name="loading_atrnr"
                                                                            id="loading_atrnrSelect"
                                                                            onchange="displayFields(this);">
                                                                        <option value="">No loading</option>
                                                                        @foreach($listAtrnr as $atrnr )
                                                                            @if(Illuminate\Support\Facades\Input::old('loading_atrnr') && $atrnr==old('loading_atrnr'))
                                                                                <option value="{{$atrnr}}"
                                                                                        selected>{{$atrnr}}</option>
                                                                            @elseif(isset($transfer->loading_atrnr)&&$atrnr==$transfer->loading_atrnr)
                                                                                <option value="{{$atrnr}}"
                                                                                        selected>{{$atrnr}}</option>
                                                                            @else
                                                                                <option value="{{$atrnr}}">{{$atrnr}}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>
                                                                @endif
                                                            </div>
                                                            <!--Link loading-->
                                                            @if(isset($transfer->loading_atrnr))
                                                                <div class="col-lg-2 text-left">
                                                                    <a href="{{route('showDetailsLoading', $transfer->loading_atrnr)}}"
                                                                       class="link"><span
                                                                                class="glyphicon glyphicon-info-sign"></span>
                                                                        See loading</a>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="form-group">
                                                            <!--number of pallets-->
                                                            <div class="col-lg-2">
                                                                <label for="palletsNumber"
                                                                       class="control-label"><span>*</span> Pallets
                                                                    number
                                                                    :</label>
                                                            </div>
                                                            <div class="col-lg-1">
                                                                @if(Illuminate\Support\Facades\Input::old('palletsNumber'))
                                                                    <input id="palletsNumber" type="number"
                                                                           class="form-control"
                                                                           name="palletsNumber"
                                                                           value="{{ old('palletsNumber') }}"
                                                                           placeholder="Nbr" min="0"
                                                                           required autofocus>
                                                                @elseif(isset($transfer->validate) && $transfer->validate==1)
                                                                    <input id="palletsNumber" type="number"
                                                                           class="form-control"
                                                                           name="palletsNumber"
                                                                           value="{{$transfer->palletsNumber}}"
                                                                           placeholder="Nbr" min="0"
                                                                           required autofocus readonly>
                                                                @else
                                                                    <input id="palletsNumber" type="number"
                                                                           class="form-control"
                                                                           name="palletsNumber"
                                                                           value="{{$transfer->palletsNumber}}"
                                                                           placeholder="Nbr" min="0"
                                                                           required autofocus>
                                                                @endif
                                                            </div>
                                                            <!--date-->
                                                            <div class="col-lg-1">
                                                                <label for="date"
                                                                       class="control-label">Date
                                                                    :</label>
                                                            </div>
                                                            <div class="col-lg-2">
                                                                @if(isset($transfer->date)&&(isset($transfer->validate) && $transfer->validate==1))
                                                                    <input id="date" type="date"
                                                                           class="form-control" name="date"
                                                                           value="{{ $transfer->date }}"
                                                                           placeholder="Date" required autofocus
                                                                           readonly>
                                                                @elseif(isset($transfer->date))
                                                                    <input id="date" type="date"
                                                                           class="form-control" name="date"
                                                                           value="{{ $transfer->date }}"
                                                                           placeholder="Date" required autofocus>

                                                                @elseif(isset($transfer->validate) && $transfer->validate==1)
                                                                    <input id="date" type="date"
                                                                           class="form-control" name="date"
                                                                           value="{{ old('date') }}"
                                                                           placeholder="Date" required autofocus
                                                                           readonly>
                                                                @else(Illuminate\Support\Facades\Input::old('date'))
                                                                    <input id="date" type="date"
                                                                           class="form-control" name="date"
                                                                           value="{{ old('date') }}"
                                                                           placeholder="Date" required autofocus>
                                                                @endif
                                                            </div>
                                                            <div class="col-lg-2 col-lg-offset-2 text-left">
                                                                <a href="{{route('showAddPalletsaccount')}}"
                                                                   class="link"><span
                                                                            class="glyphicon glyphicon-plus-sign"></span>
                                                                    Add account</a>
                                                            </div>
                                                            {{--<!--multitransfer-->--}}
                                                            {{--@if(isset($transfer->loading_atrnr))--}}
                                                            {{--<div>--}}
                                                            {{--@else--}}
                                                            {{--<div id="loading_atrnrLink">--}}
                                                            {{--@endif--}}
                                                            {{--<div class="col-lg-2">--}}
                                                            {{--<label for="multiTransfer"--}}
                                                            {{--class="control-label">Multi-Transfers--}}
                                                            {{--?--}}
                                                            {{--</label>--}}
                                                            {{--</div>--}}
                                                            {{--<div class="col-lg-2 text-left">--}}
                                                            {{--@if((isset($transfer->validate) && $transfer->validate==1 && (Illuminate\Support\Facades\Input::old('multiTransfer') && old('multiTransfer')=='true'||(isset($transfer->multiTransfer)&&$transfer->multiTransfer=='true'))))--}}
                                                            {{--<input type="text"--}}
                                                            {{--name="multiTransfer"--}}
                                                            {{--class="form-control"--}}
                                                            {{--value="Yes" readonly>--}}
                                                            {{--@elseif(Illuminate\Support\Facades\Input::old('multiTransfer') && old('multiTransfer')=='true'||(isset($transfer->multiTransfer)&&$transfer->multiTransfer==1))--}}
                                                            {{--<label class="radio-inline"><input--}}
                                                            {{--type="radio"--}}
                                                            {{--name="multiTransfer"--}}
                                                            {{--value="true"--}}
                                                            {{--checked>Yes</label>--}}
                                                            {{--<label class="radio-inline"><input--}}
                                                            {{--type="radio"--}}
                                                            {{--name="multiTransfer"--}}
                                                            {{--value="false">No</label>--}}
                                                            {{--@elseif((isset($transfer->validate) && $transfer->validate==1))--}}
                                                            {{--<input type="text"--}}
                                                            {{--name="multiTransfer"--}}
                                                            {{--class="form-control"--}}
                                                            {{--value="No" readonly>--}}
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
                                                            {{--@if(isset($transfer->loading_atrnr))--}}
                                                            {{--</div>--}}
                                                            {{--@else--}}
                                                            {{--</div>--}}
                                                            {{--@endif--}}
                                                        </div>

                                                        <div class="form-group">
                                                            <!--debit account-->
                                                            @if($transfer->type=='Other'||$transfer->type=='Purchase_Int'||$transfer->type=='Sale_Ext'||$transfer->type=='Sale_Int'||$transfer->type=='Deposit-Withdrawal'||$transfer->type=='Withdrawal-Deposit'||$transfer->type=='Deposit_Only'||$transfer->type=='Withdrawal_Only')
                                                                <div class="col-lg-2" id="debitAccount1"
                                                                     style="display: block">
                                                                    @else
                                                                        <div class="col-lg-2" id="debitAccount1">
                                                                            @endif
                                                                            <label for="debitAccount"
                                                                                   class="control-label"><span>*</span>
                                                                                Debit
                                                                                account
                                                                                :</label>
                                                                            @if($transfer->type=='Other'||$transfer->type=='Purchase_Int'||$transfer->type=='Sale_Ext'||$transfer->type=='Sale_Int'||$transfer->type=='Deposit-Withdrawal'||$transfer->type=='Withdrawal-Deposit'||$transfer->type=='Deposit_Only'||$transfer->type=='Withdrawal_Only')
                                                                        </div>
                                                                        @else
                                                                </div>
                                                            @endif
                                                            @if($transfer->type=='Other'||$transfer->type=='Purchase_Int'||$transfer->type=='Sale_Ext'||$transfer->type=='Sale_Int'||$transfer->type=='Deposit-Withdrawal'||$transfer->type=='Withdrawal-Deposit'||$transfer->type=='Deposit_Only'||$transfer->type=='Withdrawal_Only')
                                                                <div class="col-lg-4" id="debitAccount2"
                                                                     style="display: block">
                                                                    @else
                                                                        <div class="col-lg-4" id="debitAccount2">
                                                                            @endif
                                                                            @if(isset($transfer->validate) && $transfer->validate==1)
                                                                                <input type="text" name="debitAccount"
                                                                                       class="form-control"
                                                                                       value="{{$transfer->debitAccount}}"
                                                                                       readonly>
                                                                            @else
                                                                                <select class="selectpicker show-tick form-control"
                                                                                        data-size="10"
                                                                                        data-live-search="true"
                                                                                        data-live-search-style="startsWith"
                                                                                        title="Debit Account"
                                                                                        name="debitAccount"
                                                                                        id="debitAccount"
                                                                                >
                                                                                    @if(isset($transfer->debitAccount))
                                                                                        @php($partsDebitAccount=explode('-', $transfer->debitAccount))
                                                                                        @php($typeDebitAccount=$partsDebitAccount[count($partsDebitAccount)-2])
                                                                                        @php($idDebitAccount=$partsDebitAccount[count($partsDebitAccount)-1])
                                                                                    @elseif(Illuminate\Support\Facades\Input::old('debitAccount'))
                                                                                        @php($partsDebitAccountOld=explode('-', old('debitAccount')))
                                                                                        @php($typeDebitAccountOld=$partsDebitAccountOld[count($partsDebitAccountOld)-2])
                                                                                        @php($idDebitAccountOld=$partsDebitAccountOld[count($partsDebitAccountOld)-1])
                                                                                    @endif
                                                                                        @foreach($listPalletsAccounts as $palletsAccount )
                                                                                            @if(isset($transfer->debitAccount)&& ($typeDebitAccount == 'account') && ($palletsAccount->id==$idDebitAccount))
                                                                                                <option value="account-{{$palletsAccount->id}}"
                                                                                                        selected>{{$palletsAccount->name}}</option>
                                                                                            @elseif(Illuminate\Support\Facades\Input::old('debitAccount') && ($typeDebitAccountOld == 'account') && ($palletsAccount->id==$idDebitAccountOld))
                                                                                                <option value="account-{{$palletsAccount->id}}"
                                                                                                        selected>{{$palletsAccount->name}}</option>
                                                                                            @else
                                                                                                <option value="account-{{$palletsAccount->id}}">{{$palletsAccount->name}}</option>
                                                                                            @endif
                                                                                        @endforeach
                                                                                        @foreach($listTrucksAccounts as $trucksAccount )
                                                                                            @if(Illuminate\Support\Facades\Input::old('debitAccount') && ($typeDebitAccountOld == 'truck') && ($trucksAccount->id==$idDebitAccountOld))
                                                                                                <option value="truck-{{$trucksAccount->id}}"
                                                                                                        selected>{{$trucksAccount->name}}
                                                                                                    - {{$trucksAccount->licensePlate}}</option>
                                                                                            @elseif(isset($transfer->debitAccount)&& ($typeDebitAccount== 'truck') && ($trucksAccount->id==$idDebitAccount))
                                                                                                <option value="truck-{{$trucksAccount->id}}"
                                                                                                        selected>{{$trucksAccount->name}}
                                                                                                    - {{$trucksAccount->licensePlate}}</option>
                                                                                            @else
                                                                                                <option value="truck-{{$trucksAccount->id}}">{{$trucksAccount->name}}
                                                                                                    - {{$trucksAccount->licensePlate}}</option>
                                                                                            @endif
                                                                                        @endforeach
                                                                                </select>
                                                                            @endif
                                                                            @if($transfer->type=='Other'||$transfer->type=='Purchase_Int'||$transfer->type=='Sale_Ext'||$transfer->type=='Sale_Int'||$transfer->type=='Deposit-Withdrawal'||$transfer->type=='Withdrawal-Deposit'||$transfer->type=='Deposit_Only'||$transfer->type=='Withdrawal_Only')
                                                                        </div>
                                                                        @else
                                                                </div>
                                                            @endif
                                                            {{--@if ($errors->has('debitAccount'))--}}
                                                                {{--<span class="help-block">--}}
                                        {{--<strong>{{ $errors->first('debitAccount') }}</strong>--}}
                                    {{--</span>--}}
                                                            {{--@endif--}}

                                                        <!--credit account-->
                                                            @if($transfer->type=='Other'||$transfer->type=='Purchase_Int'||$transfer->type=='Purchase_Ext'||$transfer->type=='Sale_Int'||$transfer->type=='Deposit-Withdrawal'||$transfer->type=='Withdrawal-Deposit'||$transfer->type=='Deposit_Only'||$transfer->type=='Withdrawal_Only')
                                                                <div class="col-lg-2" id="creditAccount1"
                                                                     style="display: block">
                                                                    @else
                                                                        <div class="col-lg-2" id="creditAccount1">
                                                                            @endif
                                                                            <label for="creditAccount"
                                                                                   class="control-label"><span>*</span>
                                                                                Credit
                                                                                account
                                                                                :</label>
                                                                            @if($transfer->type=='Other'||$transfer->type=='Purchase_Int'||$transfer->type=='Purchase_Ext'||$transfer->type=='Sale_Int'||$transfer->type=='Deposit-Withdrawal'||$transfer->type=='Withdrawal-Deposit'||$transfer->type=='Deposit_Only'||$transfer->type=='Withdrawal_Only')
                                                                        </div>
                                                                        @else
                                                                </div>
                                                            @endif
                                                            @if($transfer->type=='Other'||$transfer->type=='Purchase_Int'||$transfer->type=='Purchase_Ext'||$transfer->type=='Sale_Int'||$transfer->type=='Deposit-Withdrawal'||$transfer->type=='Withdrawal-Deposit'||$transfer->type=='Deposit_Only'||$transfer->type=='Withdrawal_Only')
                                                                <div class="col-lg-4" id="creditAccount2"
                                                                     style="display: block">
                                                                    @else
                                                                        <div class="col-lg-4" id="creditAccount2">
                                                                            @endif
                                                                            @if(isset($transfer->validate) && $transfer->validate==1)
                                                                                <input type="text" name="creditAccount"
                                                                                       class="form-control"
                                                                                       value="{{$transfer->creditAccount}}"
                                                                                       readonly>
                                                                            @else
                                                                                <select class="selectpicker show-tick form-control"
                                                                                        data-size="10"
                                                                                        data-live-search="true"
                                                                                        data-live-search-style="startsWith"
                                                                                        title="Credit Account"
                                                                                        name="creditAccount"
                                                                                        id="creditAccount"
                                                                                >
                                                                                    @if(isset($transfer->creditAccount))
                                                                                        @php($partsCreditAccount=explode('-', $transfer->creditAccount))
                                                                                        @php($typeCreditAccount=$partsCreditAccount[count($partsCreditAccount)-2])
                                                                                        @php($idCreditAccount=$partsCreditAccount[count($partsCreditAccount)-1])
                                                                                    @elseif(Illuminate\Support\Facades\Input::old('creditAccount'))
                                                                                        @php($partsCreditAccountOld=explode('-', old('creditAccount')))
                                                                                        @php($typeCreditAccountOld=$partsCreditAccountOld[count($partsCreditAccountOld)-2])
                                                                                        @php($idCreditAccountOld=$partsCreditAccountOld[count($partsCreditAccountOld)-1])
                                                                                    @endif
                                                                                    @foreach($listPalletsAccounts as $palletsAccount )
                                                                                        @if(isset($transfer->creditAccount)&& ($typeCreditAccount == 'account') && ($palletsAccount->id==$idCreditAccount))
                                                                                            <option value="account-{{$palletsAccount->id}}"
                                                                                                    selected>{{$palletsAccount->name}}</option>
                                                                                        @elseif(Illuminate\Support\Facades\Input::old('creditAccount') && ($typeCreditAccountOld == 'account') && ($palletsAccount->id==$idCreditAccountOld))
                                                                                            <option value="account-{{$palletsAccount->id}}"
                                                                                                    selected>{{$palletsAccount->name}}</option>
                                                                                        @else
                                                                                            <option value="account-{{$palletsAccount->id}}">{{$palletsAccount->name}}</option>
                                                                                        @endif
                                                                                    @endforeach
                                                                                    @foreach($listTrucksAccounts as $trucksAccount )
                                                                                        @if(Illuminate\Support\Facades\Input::old('creditAccount') && ($typeCreditAccountOld == 'truck') && ($trucksAccount->id==$idCreditAccountOld))
                                                                                            <option value="truck-{{$trucksAccount->id}}"
                                                                                                    selected>{{$trucksAccount->name}}
                                                                                                - {{$trucksAccount->licensePlate}}</option>
                                                                                        @elseif(isset($transfer->creditAccount)&& ($typeCreditAccount == 'truck') && ($trucksAccount->id==$idCreditAccount))
                                                                                            <option value="truck-{{$trucksAccount->id}}"
                                                                                                    selected>{{$trucksAccount->name}}
                                                                                                - {{$trucksAccount->licensePlate}}</option>
                                                                                        @else
                                                                                            <option value="truck-{{$trucksAccount->id}}">{{$trucksAccount->name}}
                                                                                                - {{$trucksAccount->licensePlate}}</option>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </select>
                                                                            @endif
                                                                            @if($transfer->type=='Other'||$transfer->type=='Purchase_Int'||$transfer->type=='Purchase_Ext'||$transfer->type=='Sale_Int'||$transfer->type=='Deposit-Withdrawal'||$transfer->type=='Withdrawal-Deposit'||$transfer->type=='Deposit_Only'||$transfer->type=='Withdrawal_Only')
                                                                        </div>
                                                                        @else
                                                                </div>
                                                            @endif
                                                            {{--@if ($errors->has('creditAccount'))--}}
                                                                {{--<span class="help-block">--}}
                                        {{--<strong>{{ $errors->first('creditAccount') }}</strong>--}}
                                    {{--</span>--}}
                                                            {{--@endif--}}

                                                        </div>

                                                        <!--documents proof upload-->
                                                        <div class="form-group">
                                                            <div class="col-lg-2">
                                                                <label for="documentsTransfer"><span>*</span> Proof
                                                                    docs
                                                                    ?</label>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <input type="file"
                                                                       name="documentsTransfer[]"
                                                                       multiple id="documentsTransfer">
                                                            </div>
                                                            <!--button upload-->
                                                            <div class="col-lg-2">
                                                                <input type="submit"
                                                                       class="btn btn-primary btn-block btn-form"
                                                                       value="Upload"
                                                                       name="upload"/>
                                                            </div>
                                                        </div>
                                                        <!-- documents -->
                                                        <div class="form-group">
                                                            <div class="col-lg-10 col-lg-offset-1 text-left">
                                                                @if(!empty($filesNames))
                                                                    <ul>
                                                                        @php($list=[])
                                                                        @foreach($filesNames as $nameF)
                                                                            @if(!in_array($nameF, $list))
                                                                                <div>
                                                                                    <button type="submit"
                                                                                            name="deleteDocument"
                                                                                            class="btn-add glyphicon glyphicon-remove"
                                                                                            value="{{$nameF}}"></button>
                                                                                    <a href="../../storage/app/proofsPallets/documentsTransfer/{{$transfer->id}}/{{$transfer->type}}/{{$nameF}}"
                                                                                       class="link">{{$nameF}}</a>
                                                                                </div>
                                                                                @php(array_push($list,$nameF))
                                                                            @endif
                                                                        @endforeach
                                                                    </ul>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <!--validation-->
                                                        <div class="form-group">

                                                            @if(!empty($filesNames)&&isset($transfer->palletsNumber))
                                                                    <div class="col-lg-2">
                                                                        <label for="state"
                                                                               class="control-label"><span>*</span> Validated ?
                                                                        </label>
                                                                    </div>
                                                                    <div class="col-lg-2 text-left">
                                                                        @if(isset($transfer->validate) && $transfer->validate==1)
                                                                            <label class="radio-inline"><input
                                                                                        type="radio"
                                                                                        name="validate"
                                                                                        value="true"
                                                                                        checked
                                                                                        id="validateYes">Yes</label>
                                                                            <label class="radio-inline"><input
                                                                                        type="radio"
                                                                                        name="validate"
                                                                                        value="false"
                                                                                        id="validateNo">No</label>
                                                                        @elseif(isset($transfer->validate) && $transfer->validate==0)
                                                                            <label class="radio-inline"><input
                                                                                        type="radio"
                                                                                        name="validate"
                                                                                        value="true"
                                                                                        id="validateYes">Yes</label>
                                                                            <label class="radio-inline"><input
                                                                                        type="radio"
                                                                                        name="validate"
                                                                                        value="false"
                                                                                        checked
                                                                                        id="validateNo">No</label>
                                                                        @endif
                                                                    </div>
                                                                    <!--submit-->
                                                                    <div class="col-lg-4 col-lg-offset-1">
                                                                        <input type="submit"
                                                                               class="btn btn-primary btn-block btn-form"
                                                                               value="Update"
                                                                               name="update" data-toggle="modal"
                                                                               data-target="#submitUpdate_modal">
                                                                    </div>
                                                                @else
                                                                <!--submit-->
                                                                    <div class="col-lg-4 col-lg-offset-5">
                                                                        <input type="submit"
                                                                               class="btn btn-primary btn-block btn-form"
                                                                               value="Update"
                                                                               name="update" data-toggle="modal"
                                                                               data-target="#submitUpdate_modal">
                                                                    </div>
                                                                @endif
                                                        </div>
                                                    </div>

                                                    <!-- Modal update -->
                                                    @if(isset($update))
                                                        <div class="modal show"
                                                             id="submitUpdate_modal"
                                                             role="dialog">
                                                            <div class="modal-dialog modal-md">
                                                                <div class="modal-content">
                                                                    <div class="modal-header modalHeaderTransfer">
                                                                        <button value="close"
                                                                                class="close"
                                                                                type="submit"
                                                                                name="closeSubmitUpdateModal">
                                                                            &times;
                                                                        </button>
                                                                        <h4 class="modal-title text-center ">
                                                                            INFORMATION
                                                                        </h4>
                                                                    </div>
                                                                    <div class="modal-body center modalBodyTransfer">
                                                                        <p class="text-center">
                                                                            Here,
                                                                            PLANNED
                                                                            pallets
                                                                            number</p>
                                                                        @if(request()->session()->get('actualCreditAccount')==request()->session()->get('creditAccount') && request()->session()->get('actualDebitAccount')==request()->session()->get('debitAccount'))
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
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="text-center">Actual</td>
                                                                                    @if(Session::has('debitAccount'))
                                                                                        <td class="text-center">{{request()->session()->get('thPalletsNumberDebitAccount')}}</td>
                                                                                    @endif
                                                                                    @if(Session::has('creditAccount'))
                                                                                        <td class="text-center">{{request()->session()->get('thPalletsNumberCreditAccount')}}</td>
                                                                                    @endif
                                                                                </tr>
                                                                                {{--<tr>--}}
                                                                                {{--<td class="text-center">Last transfer</td>--}}
                                                                                {{--<td class="text-center">--}}
                                                                                {{--- {{request()->session()->get('actualPalletsNumber')}}</td>--}}
                                                                                {{--<td class="text-center">--}}
                                                                                {{--+ {{request()->session()->get('actualPalletsNumber')}}</td>--}}
                                                                                {{--</tr>--}}
                                                                                <tr>
                                                                                    <td class="text-center">Update
                                                                                        number
                                                                                    </td>
                                                                                    @if(Session::has('debitAccount'))
                                                                                        <td class="text-center">
                                                                                            - {{request()->session()->get('palletsNumber')-request()->session()->get('actualPalletsNumber')}}</td>
                                                                                        @endif
                                                                                    @if(Session::has('creditAccount'))
                                                                                        <td class="text-center">
                                                                                            +{{request()->session()->get('palletsNumber')-request()->session()->get('actualPalletsNumber')}}</td>
                                                                                    @endif
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="text-center">Total</td>
                                                                                    @if(Session::has('debitAccount'))
                                                                                        <td class="text-center">
                                                                                            = {{request()->session()->get('thPalletsNumberDebitAccount')  + request()->session()->get('actualPalletsNumber') -request()->session()->get('palletsNumber')}}</td>
                                                                                    @endif
                                                                                    @if(Session::has('creditAccount'))
                                                                                        <td class="text-center">
                                                                                            = {{request()->session()->get('thPalletsNumberCreditAccount')- request()->session()->get('actualPalletsNumber') +request()->session()->get('palletsNumber')}}</td>
                                                                                    @endif
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        @elseif(request()->session()->get('actualCreditAccount')<>request()->session()->get('creditAccount') && request()->session()->get('actualDebitAccount')<>request()->session()->get('debitAccount'))
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
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="text-center">Actual</td>
                                                                                    @if(Session::has('debitAccount'))
                                                                                        <td class="text-center">{{request()->session()->get('thPalletsNumberDebitAccount')}}</td>
                                                                                    @endif
                                                                                    @if(Session::has('creditAccount'))
                                                                                        <td class="text-center">{{request()->session()->get('thPalletsNumberCreditAccount')}}</td>
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
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="text-center">Total</td>
                                                                                    @if(Session::has('debitAccount'))
                                                                                        <td class="text-center">
                                                                                            = {{request()->session()->get('thPalletsNumberDebitAccount') -request()->session()->get('palletsNumber')}}</td>
                                                                                    @endif
                                                                                    @if(Session::has('creditAccount'))
                                                                                        <td class="text-center">
                                                                                            = {{request()->session()->get('thPalletsNumberCreditAccount')+request()->session()->get('palletsNumber')}}</td>
                                                                                    @endif
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        @elseif(request()->session()->get('actualCreditAccount')==request()->session()->get('creditAccount') && request()->session()->get('actualDebitAccount')<>request()->session()->get('debitAccount'))
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
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="text-center">Actual</td>
                                                                                    @if(Session::has('debitAccount'))
                                                                                        <td class="text-center">{{request()->session()->get('thPalletsNumberDebitAccount')}}</td>
                                                                                    @endif
                                                                                    @if(Session::has('creditAccount'))
                                                                                        <td class="text-center">{{request()->session()->get('thPalletsNumberCreditAccount')}}</td>
                                                                                    @endif
                                                                                </tr>
                                                                                {{--<tr>--}}
                                                                                {{--<td class="text-center">Last transfer</td>--}}
                                                                                {{--<td class="text-center">--}}
                                                                                {{--{{request()->session()->get('actualPalletsNumber')}}</td>--}}
                                                                                {{--<td class="text-center">--}}
                                                                                {{--</td>--}}
                                                                                {{--</tr>--}}
                                                                                <tr>
                                                                                    <td class="text-center">Update
                                                                                        number
                                                                                    </td>
                                                                                    @if(Session::has('debitAccount'))
                                                                                        <td class="text-center">
                                                                                            - {{request()->session()->get('palletsNumber')}}</td>
                                                                                    @endif
                                                                                    @if(Session::has('creditAccount'))
                                                                                        <td class="text-center">
                                                                                            +{{request()->session()->get('palletsNumber')-request()->session()->get('actualPalletsNumber')}}</td>
                                                                                    @endif
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="text-center">Total</td>
                                                                                    @if(Session::has('debitAccount'))
                                                                                        <td class="text-center">
                                                                                            = {{request()->session()->get('thPalletsNumberDebitAccount') -request()->session()->get('palletsNumber')}}</td>
                                                                                    @endif
                                                                                    @if(Session::has('creditAccount'))
                                                                                        <td class="text-center">
                                                                                            = {{request()->session()->get('thPalletsNumberCreditAccount')- request()->session()->get('actualPalletsNumber') +request()->session()->get('palletsNumber')}}</td>
                                                                                    @endif
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        @elseif(request()->session()->get('actualCreditAccount')<>request()->session()->get('creditAccount') && request()->session()->get('actualDebitAccount')==request()->session()->get('debitAccount'))
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
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="text-center">Actual</td>
                                                                                    @if(Session::has('debitAccount'))
                                                                                        <td class="text-center">{{request()->session()->get('thPalletsNumberDebitAccount')}}</td>
                                                                                    @endif
                                                                                    @if(Session::has('creditAccount'))
                                                                                        <td class="text-center">{{request()->session()->get('thPalletsNumberCreditAccount')}}</td>
                                                                                    @endif
                                                                                </tr>
                                                                                {{--<tr>--}}
                                                                                {{--<td class="text-center">Last transfer</td>--}}
                                                                                {{--<td class="text-center">--}}
                                                                                {{--</td>--}}
                                                                                {{--<td class="text-center">--}}
                                                                                {{--+ {{request()->session()->get('actualPalletsNumber')}}</td>--}}
                                                                                {{--</tr>--}}
                                                                                <tr>
                                                                                    <td class="text-center">Update
                                                                                        number
                                                                                    </td>
                                                                                    @if(Session::has('debitAccount'))
                                                                                        <td class="text-center">
                                                                                            - {{request()->session()->get('palletsNumber')-request()->session()->get('actualPalletsNumber')}}</td>
                                                                                    @endif
                                                                                    @if(Session::has('creditAccount'))
                                                                                        <td class="text-center">
                                                                                            +{{request()->session()->get('palletsNumber')}}</td>
                                                                                    @endif
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="text-center">Total</td>
                                                                                    @if(Session::has('debitAccount'))
                                                                                        <td class="text-center">
                                                                                            = {{request()->session()->get('thPalletsNumberDebitAccount')+ request()->session()->get('actualPalletsNumber') -request()->session()->get('palletsNumber')}}</td>
                                                                                    @endif
                                                                                    @if(Session::has('creditAccount'))
                                                                                        <td class="text-center">
                                                                                            = {{request()->session()->get('thPalletsNumberCreditAccount') +request()->session()->get('palletsNumber')}}</td>
                                                                                    @endif
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        @endif
                                                                    @if(($transfer->type=='Deposit-Withdrawal' || $transfer->type=='Withdrawal-Deposit')&&(((request()->session()->get('palletsNumber')<>$anz))))
                                                                        <div class="text-center">
                                                                            <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                            <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                            <span class="text-danger">Pallets number doesn't match the number expected in the loading order ({{$anz}}
                                                                                )</span>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="modal-footer">
                                                                    @if(($transfer->type=='Deposit-Withdrawal' || $transfer->type=='Withdrawal-Deposit')&& (request()->session()->get('palletsNumber')<>$anz))
                                                                        <button type="submit"
                                                                                class="btn btn-danger btn-modal"
                                                                                value="yes"
                                                                                name="okSubmitUpdateModal"
                                                                                data-toggle="modal"
                                                                                data-target="#submitUpdateValidate_modal">
                                                                            Confirm
                                                                        </button>
                                                                    @else
                                                                        <button type="submit"
                                                                                class="btn btn-default btn-form btn-modal"
                                                                                value="yes"
                                                                                name="okSubmitUpdateModal"
                                                                                data-toggle="modal"
                                                                                data-target="#submitUpdateValidate_modal">
                                                                            Confirm
                                                                        </button>
                                                                    @endif
                                                                </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                <!-- Modal update -->
                                                    @if(isset($okSubmitUpdateModal) && $transfer->state=='Complete Validated')
                                                        <div class="modal show"
                                                             id="submitUpdateValidate_modal"
                                                             role="dialog">
                                                            <div class="modal-dialog modal-md">
                                                                <div class="modal-content">
                                                                    <div class="modal-header modalHeaderTransfer">
                                                                        <button value="close"
                                                                                class="close"
                                                                                type="submit"
                                                                                name="closeSubmitUpdateModal">
                                                                            &times;
                                                                        </button>
                                                                        <h4 class="modal-title text-center">
                                                                            INFORMATION
                                                                        </h4>
                                                                    </div>
                                                                    <div class="modal-body center modalBodyTransfer">
                                                                        <p class="text-center">
                                                                            Here,
                                                                            CONFIRMED
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
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="text-center">Actual</td>
                                                                                @if(Session::has('debitAccount'))
                                                                                    <td class="text-center">{{request()->session()->get('realPalletsNumberDebitAccount')}}</td>
                                                                                @endif
                                                                                @if(Session::has('creditAccount'))
                                                                                    <td class="text-center">{{request()->session()->get('realPalletsNumberCreditAccount')}}</td>
                                                                                    @endif
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="text-center">New transfer
                                                                                </td>
                                                                                @if(Session::has('debitAccount'))
                                                                                    <td class="text-center">
                                                                                        - {{request()->session()->get('palletsNumber')}}</td>
                                                                                @endif
                                                                                @if(Session::has('creditAccount'))
                                                                                <td class="text-center">
                                                                                    + {{request()->session()->get('palletsNumber')}}</td>
                                                                                @endif
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="text-center">Total</td>
                                                                                @if(Session::has('debitAccount'))
                                                                                    <td class="text-center">
                                                                                        = {{request()->session()->get('realPalletsNumberDebitAccount') -request()->session()->get('palletsNumber')}}</td>
                                                                                @endif
                                                                                @if(Session::has('creditAccount'))
                                                                                <td class="text-center">
                                                                                    = {{request()->session()->get('realPalletsNumberCreditAccount')+request()->session()->get('palletsNumber')}}</td>
                                                                                @endif
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        @if(($transfer->type=='Deposit-Withdrawal' || $transfer->type=='Withdrawal-Deposit')&&(((request()->session()->get('palletsNumber')<>$anz))))
                                                                            <div class="text-center">
                                                                                <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                <span class="text-danger">Pallets number doesn't match the number expected in the loading order ({{$anz}}
                                                                                    )</span>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        @if(($transfer->type=='Deposit-Withdrawal' || $transfer->type=='Withdrawal-Deposit')&& (request()->session()->get('palletsNumber')<>$anz))
                                                                            <button type="submit"
                                                                                    class="btn btn-danger btn-modal"
                                                                                    value="yes"
                                                                                    name="okSubmitUpdateValidateModal">
                                                                                Confirm
                                                                            </button>
                                                                        @else
                                                                            <button type="submit"
                                                                                    class="btn btn-default btn-form btn-modal"
                                                                                    value="yes"
                                                                                    name="okSubmitUpdateValidateModal">
                                                                                Confirm
                                                                            </button>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </form>

                                                <!-- Modal Delete -->
                                                @if(isset($delete))
                                                    <div class="modal show" id="deletePalletstransfer_modal"
                                                         role="dialog">
                                                        @else
                                                            <div class="modal fade" id="deletePalletstransfer_modal"
                                                                 role="dialog">
                                                                @endif
                                                                <div class="modal-dialog modal-sm">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            @if(isset($delete))
                                                                                <a href="{{route('showDetailsPalletstransfer', $transfer->id)}}"
                                                                                   class="close">&times;</a>
                                                                            @else
                                                                                <button type="button" class="close"
                                                                                        data-dismiss="modal">&times;
                                                                                </button>
                                                                            @endif
                                                                            <h4 class="modal-title text-center">Are you
                                                                                sure to
                                                                                delete the
                                                                                pallets
                                                                                transfer {{$transfer->id}} ?</h4>
                                                                        </div>
                                                                        <div class="modal-body center">
                                                                            <form method="post"
                                                                                  action="{{route('deletePalletstransfer', $transfer->id)}}">
                                                                                <input type="hidden" name="_method"
                                                                                       value="delete">
                                                                                {{ csrf_field() }}
                                                                                <div class="text-center">
                                                                                    <button type="submit"
                                                                                            class="btn btn-danger btn-modal"
                                                                                            value="yes"
                                                                                            name="delete"
                                                                                    >
                                                                                        Yes
                                                                                    </button>
                                                                                    @if(isset($delete))
                                                                                        <a href="{{route('showDetailsPalletstransfer', $transfer->id)}}"
                                                                                           class="btn btn-success btn-modal">No</a>
                                                                                    @else
                                                                                        <button type="button"
                                                                                                class="btn btn-success btn-modal"
                                                                                                data-dismiss="modal">No
                                                                                        </button>
                                                                                    @endif
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            @if(isset($delete))
                                                                                <a href="{{route('showDetailsPalletstransfer', $transfer->id)}}"
                                                                                   class="btn btn-default btn-modal">Close</a>
                                                                            @else
                                                                                <button type="button"
                                                                                        class="btn btn-default btn-modal"
                                                                                        data-dismiss="modal">
                                                                                    Close
                                                                                </button>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @if(isset($delete))
                                                            </div>
                                                            @else
                                                    </div>
                                                @endif
                                                @if($transfer->state=="In progress")
                                            </div>
                                        @elseif ($transfer->state=="Waiting documents")
                                    </div>
                                @elseif ($transfer->state=="Complete")
                            </div>
                        @elseif ($transfer->state=="Complete Validated")
                    </div>
                @endif
            </div>
        @endif
    </div>
@endsection
<script type="text/javascript" src="{{asset('js/addUpdatePalletstransfer.js')}}">
</script>