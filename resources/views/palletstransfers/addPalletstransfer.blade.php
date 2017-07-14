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
    nonActive
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
                                    <p class="alert alert-danger text-alert text-center">{{ Session::get('errorFields') }}</p>
                                @endif
                            </div>
                            <div class="form-group">
                                <!--type-->
                                <div class="col-lg-1 col-lg-offset-1">
                                    <label for="type" class="control-label">*Type :</label>
                                </div>
                                <div class="col-lg-2">
                                    <select class="selectpicker show-tick form-control" data-size="10"
                                            data-live-search="true" data-live-search-style="startsWith"
                                            title="Type" name="type" id="type" required
                                            onchange="displayFieldsType(this);">
                                        @if(Illuminate\Support\Facades\Input::old('type') || isset($type))
                                            <optgroup label="Normal">
                                                <option @if(strcmp(old('type'),'Deposit-Withdrawal')==0|| strcmp($type,'Deposit-Withdrawal')==0) selected @endif value="Deposit-Withdrawal" id="Deposit-WithdrawalOption">
                                                    Deposit-Withdrawal
                                                </option>
                                                <option @if(strcmp(old('type'),'Withdrawal-Deposit')==0|| strcmp($type,'Withdrawal-Deposit')==0) selected @endif value="Withdrawal-Deposit" id="Withdrawal-DepositOption">
                                                    Withdrawal-Deposit
                                                </option>
                                                <option @if(strcmp(old('type'),'Deposit_Only')==0 || strcmp($type,'Deposit_Only')==0) selected @endif value="Deposit_Only" id="Deposit_OnlyOption">
                                                    Deposit_Only
                                                </option>
                                                <option @if(strcmp(old('type'),'Withdrawal_Only')==0 ||strcmp($type,'Withdrawal_Only')==0) selected @endif value="Withdrawal_Only" id="Withdrawal_OnlyOption">
                                                    Withdrawal_Only
                                                </option>
                                            </optgroup>
                                            <optgroup label="Correcting">
                                                <option @if(strcmp('Purchase-Sale',old('type'))==0 || strcmp($type,'Purchase-Sale')==0) selected @endif value="Purchase-Sale" id="Purchase-SaleOption">
                                                    Purchase-Sale
                                                </option>
                                                <option @if(strcmp('Sale-Purchase',old('type'))==0 || strcmp($type,'Sale-Purchase')==0) selected @endif value="Sale-Purchase" id="Sale-PurchaseOption">
                                                    Sale-Purchase
                                                </option>
                                                <option @if(strcmp('Purchase_Ext',old('type'))==0 || strcmp($type,'Purchase_Ext')==0) selected @endif value="Purchase_Ext" id="Purchase_ExtOption">
                                                    Purchase_Ext
                                                </option>
                                                <option @if(strcmp('Sale_Ext',old('type'))==0 || strcmp($type,'Sale_Ext')==0) selected @endif value="Sale_Ext" id="Sale_ExtOption">
                                                    Sale_Ext
                                                </option>
                                                <option @if(strcmp('Other',old('type'))==0 || strcmp($type,'Other')==0) selected @endif value="Other" id="OtherOption">
                                                    Other
                                                </option>
                                            </optgroup>
                                        @else
                                            <optgroup label="Normal">
                                                <option value="Deposit-Withdrawal" id="Deposit-WithdrawalOption">Deposit-Withdrawal</option>
                                                <option value="Withdrawal-Deposit" id="Withdrawal-DepositOption">Withdrawal-Deposit</option>
                                                <option value="Deposit_Only" id="Deposit_OnlyOption">Deposit_Only</option>
                                                <option value="Withdrawal_Only" id="Withdrawal_OnlyOption">Withdrawal_Only</option>
                                            </optgroup>
                                            <optgroup label="Correcting">
                                                <option value="Purchase-Sale" id="Purchase-SaleOption">Purchase-Sale</option>
                                                <option value="Sale-Purchase" id="Sale-PurchaseOption">Sale-Purchase</option>
                                                <option value="Purchase_Ext" id="Purchase_ExtOption">Purchase_Ext</option>
                                                <option value="Sale_Ext" id="Sale_ExtOption">Sale_Ext</option>
                                                <option value="Other" id="OtherOption">Other</option>
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
                                           class="control-label">@if(isset($type)&&($type=='Deposit-Withdrawal'||$type=='Withdrawal-Deposit'||$type=='Withdrawal_Only'||$type=='Deposit_Only'))
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
                                            @if((Illuminate\Support\Facades\Input::old('loading_atrnr') && $atrnr==old('loading_atrnr'))||(isset($loading_atrnr)&&$atrnr==$loading_atrnr))
                                                <option selected>{{$atrnr}}</option>
                                            @else
                                                <option>{{$atrnr}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!--deposit-->
                                <div class="form-group" id="deposit-withdrawal1" @if(isset($type) && $type=='Deposit-Withdrawal') style="display:block" @endif>
                                            <div class="col-lg-12 text-center">
                                                <label for="deposit" class="control-label">DEPOSIT</label>
                                            </div>
                                </div>
                        <!--withdrawal-->
                                <div class="form-group" id="withdrawal-deposit1" @if(isset($type) &&$type=='Withdrawal-Deposit') style="display:block" @endif>
                                            <div class="col-lg-12 text-center">
                                                <label for="withdrawal" class="control-label">WITHDRAWAL</label>
                                            </div>
                                </div>
                            <div class="form-group">
                                <!--number of pallets-->
                                <div class="col-lg-2">
                                    <label for="palletsNumber" class="control-label">*Pallets number :</label>
                                </div>
                                <div class="col-lg-1">
                                    <input id="palletsNumber" type="number" class="form-control" name="palletsNumber"
                                           @if(isset($palletsNumber)) value="{{$palletsNumber}}"
                                           @elseif(Illuminate\Support\Facades\Input::old('palletsNumber')) value="{{ old('palletsNumber') }}"
                                           @else value="0" @endif placeholder="Nbr" min="0" autofocus />
                                </div>
                                <!--date-->
                                <div class="col-lg-1">
                                    <label for="date" class="control-label">Date :</label>
                                </div>
                                <div class="col-lg-2">
                                        <input id="date" type="date" class="form-control" name="date"
                                               @if(isset($date)) value="{{ $date }}" @else value="{{ old('date') }}" @endif placeholder="Date" autofocus/>
                                </div>
                                <div id="normalTransferAssociated" @if(isset($debitAccountCorr) && isset($creditAccountCorr)) style="display:block" @else style="display:none" @endif>
                                <!--transfer normal associated-->
                                <div class="col-lg-2 text-right">
                                    <label for="normalTransferAssociated"
                                           class="control-label">*Associated
                                        :</label>
                                </div>
                                    <div class="col-lg-1">
                                        <select class="selectpicker show-tick form-control" data-size="5" data-live-search="true" data-live-search-style="startsWith" title="Normal transfer associated" name="normalTransferAssociated">
                                            @foreach($listPalletstransfersNormal as $normalTransfer )
                                                @if((Illuminate\Support\Facades\Input::old('normalTransferAssociated') && $normalTransfer->id==old('normalTransferAssociated'))|| (isset($normalTransferAssociated)&&$normalTransfer->id==$normalTransferAssociated)||(!isset($normalTransferAssociated) && $showAddCorrectingTransfer==$normalTransfer->id))
                                                    <option value="{{$normalTransfer->id}}"
                                                            selected>{{$normalTransfer->id}}</option>
                                                @else
                                                    <option value="{{$normalTransfer->id}}">{{$normalTransfer->id}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!--show add pallets account-->
                                <div class="col-lg-2  text-left">
                                    <a href="{{route('showAddPalletsaccount')}}" class="link"><span
                                                class="glyphicon glyphicon-plus-sign"></span> Add account</a>
                                </div>
                            </div>
                            <div class="form-group">
                                <!--debit account-->
                                @if(isset($type)&&($type=='Other'||$type=='Purchase-Sale'||$type=='Sale_Ext'||$type=='Sale-Purchase'||$type=='Deposit-Withdrawal'||$type=='Withdrawal-Deposit'||$type=='Deposit_Only'||$type=='Withdrawal_Only'))
                                    <div class="col-lg-2" id="debitAccount1"
                                         style="display: block">
                                        @else
                                            <div class="col-lg-2" id="debitAccount1">
                                                @endif
                                                <label for="debitAccount" class="control-label">*Debit
                                                    account
                                                    :</label>
                                                @if(isset($type)&&($type=='Other'||$type=='Purchase-Sale'||$type=='Sale-Purchase'||$type=='Sale_Ext'||$type=='Deposit-Withdrawal'||$type=='Withdrawal-Deposit'||$type=='Deposit_Only'||$type=='Withdrawal_Only'))
                                            </div>
                                            @else
                                    </div>
                                @endif
                                @if(isset($type)&&($type=='Other'||$type=='Purchase-Sale'||$type=='Sale-Purchase'||$type=='Sale_Ext'||$type=='Deposit-Withdrawal'||$type=='Withdrawal-Deposit'||$type=='Deposit_Only'||$type=='Withdrawal_Only'))
                                    <div class="col-lg-4" id="debitAccount2"
                                         style="display: block">
                                        @else
                                            <div class="col-lg-4" id="debitAccount2">
                                                @endif
                                                <select class="selectpicker show-tick form-control" data-size="10"
                                                        id="select-debit"
                                                        data-live-search="true" data-live-search-style="startsWith"
                                                        title="Debit Account" name="debitAccount" onchange="selectAccount(this.value);">
                                                    @if(isset($creditAccountCorr)&&isset($debitAccountCorr))
                                                        @php($partsDebitAccount=explode('-',$debitAccountCorr))
                                                        @php($idDC=$partsDebitAccount[count($partsDebitAccount)-1])
                                                        @php($typeDC=$partsDebitAccount[count($partsDebitAccount)-2])
                                                        @if(count(array_diff ($partsDebitAccount, [$idDC, $typeDC]))==1)
                                                            @php($debitAccountC=array_diff ($partsDebitAccount, [$idDC, $typeDC])[0])
                                                        @else
                                                            @php($debitAccountC=implode( ' - ', array_diff ($partsDebitAccount, [$idDC, $typeDC])))
                                                        @endif
                                                        @php($partsCreditAccount=explode('-',$creditAccountCorr))
                                                        @php($idCC=$partsCreditAccount[count($partsCreditAccount)-1])
                                                        @php($typeCC=$partsCreditAccount[count($partsCreditAccount)-2])
                                                        @if(count(array_diff ($partsCreditAccount, [$idCC, $typeCC]))==1)
                                                            @php($creditAccountC=array_diff ($partsCreditAccount, [$idCC, $typeCC])[0])
                                                        @else
                                                            @php($creditAccountC=implode( ' - ', array_diff ($partsCreditAccount, [$idCC, $typeCC])))
                                                        @endif
                                                        <option value="account-1">STOCK</option>
                                                        @if($typeDC=='truck')
                                                            <option value="truck-{{$idDC}}">{{$debitAccountC}}</option>
                                                        @elseif($typeDC=='account')
                                                            <option value="account-{{$idDC}}">{{$debitAccountC}}</option>
                                                        @endif
                                                        @if($typeCC=='truck')
                                                            <option value="truck-{{$idCC}}">{{$creditAccountC}}</option>
                                                        @elseif($typeCC=='account')
                                                            <option value="account-{{$idCC}}">{{$creditAccountC}}</option>
                                                        @endif
                                                    @else
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
                                                        @endif
                                                </select>
                                                @if(isset($type)&&($type=='Other'||$type=='Purchase-Sale'||$type=='Sale-Purchase'||$type=='Sale_Ext'||$type=='Deposit-Withdrawal'||$type=='Withdrawal-Deposit'||$type=='Deposit_Only'||$type=='Withdrawal_Only'))
                                            </div>
                                            @else
                                    </div>
                                @endif

                            <!--credit account-->
                                @if(isset($type)&&($type=='Other'||$type=='Purchase-Sale'||$type=='Sale-Purchase'||$type=='Purchase_Ext'||$type=='Deposit-Withdrawal'||$type=='Withdrawal-Deposit'||$type=='Deposit_Only'||$type=='Withdrawal_Only'))
                                    <div class="col-lg-2" id="creditAccount1"
                                         style="display: block">
                                        @else
                                            <div class="col-lg-2" id="creditAccount1">
                                                @endif
                                                <label for="creditAccount" class="control-label">*Credit
                                                    account
                                                    :</label>
                                                @if(isset($type)&&($type=='Other'||$type=='Purchase-Sale'||$type=='Sale-Purchase'||$type=='Purchase_Ext'||$type=='Deposit-Withdrawal'||$type=='Withdrawal-Deposit'||$type=='Deposit_Only'||$type=='Withdrawal_Only'))
                                            </div>
                                            @else
                                    </div>
                                @endif
                                @if(isset($type)&&($type=='Other'||$type=='Purchase-Sale'||$type=='Sale-Purchase'||$type=='Purchase_Ext'||$type=='Deposit-Withdrawal'||$type=='Withdrawal-Deposit'||$type=='Deposit_Only'||$type=='Withdrawal_Only'))
                                    <div class="col-lg-4" id="creditAccount2"
                                         style="display: block">
                                        @else
                                            <div class="col-lg-4" id="creditAccount2">
                                                @endif
                                                <select class="selectpicker show-tick form-control" data-size="10"
                                                        data-live-search="true" data-live-search-style="startsWith"
                                                        title="Credit Account" name="creditAccount" id="select-credit"
                                                        onchange="creditaccount(this.value);">
                                                    @if(isset($debitAccountCorr) && isset($creditAccountCorr))
                                                        <option value="account-1">
                                                            STOCK
                                                        </option>
                                                        @if($typeDC=='truck')
                                                            <option value="truck-{{$idDC}}">{{$debitAccountC}}</option>
                                                        @elseif($typeDC=='account')
                                                            <option value="account-{{$idDC}}">{{$debitAccountC}}</option>
                                                        @endif
                                                        @if($typeCC=='truck')
                                                            <option value="truck-{{$idCC}}">{{$creditAccountC}}</option>
                                                        @elseif($typeCC=='account')
                                                            <option value="account-{{$idCC}}">{{$creditAccountC}}</option>
                                                        @endif
                                                    @else
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
                                                    @endif
                                                </select>
                                                @if(isset($type)&&($type=='Other'||$type=='Purchase-Sale'||$type=='Sale-Purchase'||$type=='Purchase_Ext'||$type=='Deposit-Withdrawal'||$type=='Withdrawal-Deposit'||$type=='Deposit_Only'||$type=='Withdrawal_Only'))
                                            </div>
                                            @else
                                    </div>
                                @endif
                            </div>
                            <div class="form-group">
                                @if(Session::has('errorFields2'))
                                    <p class="alert alert-danger text-alert text-center">{{ Session::get('errorFields2') }}</p>
                                @endif
                            </div>
                            <!--withdrawal-->
                                <div id="deposit-withdrawal2" @if(isset($type) &&$type=='Deposit-Withdrawal') style="display:block" @endif>
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
                                </div>
                        <!--deposit-->
                                <div id="withdrawal-deposit2" @if(isset($type) &&$type=='Withdrawal-Deposit') style="display:block" @endif>
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
                                </div>
                        <!--2nd transfer-->
                                <div id="DW" @if(isset($type) &&($type=='Withdrawal-Deposit' ||$type=='Deposit-Withdrawal')) style="display:block" @endif>
                                            <div class="form-group">
                                                <!--number of pallets-->
                                                <div class="col-lg-2">
                                                    <label for="palletsNumber2" class="control-label">Pal. nbr
                                                        :</label>
                                                </div>
                                                <div class="col-lg-1">
                                                    <input id="palletsNumber2" type="number"class="form-control" name="palletsNumber2" value=" @if(Illuminate\Support\Facades\Input::old('palletsNumber2')){{ old('palletsNumber2') }}  @elseif(isset($palletsNumber2)) {{$palletsNumber2}} @else 0 @endif" placeholder="Nbr" min="0"  autofocus />
                                                </div>
                                            </div>
                                </div>

                            <div class="form-group">
                                <div class="col-lg-4 col-lg-offset-4">
                                    <input type="submit"
                                           class="btn btn-primary btn-block btn-form"
                                           value="Add"
                                           name="addPalletstransfer" data-toggle="modal"
                                           data-target="#submitAdd_modal"/>
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
                                                @if(($type=='Deposit-Withdrawal' || $type=='Withdrawal-Deposit'|| $type=='Purchase-Sale')&&(Session::has('creditAccount2')&&Session::has('debitAccount2')&&Session::has('palletsNumber2')&& (request()->session()->get('palletsNumber2')<>request()->session()->get('palletsNumber'))))
                                                    <div class="text-center">
                                                        <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                        <span class="glyphicon glyphicon-warning-sign text-danger"></span><p
                                                                class="text-danger"> Pallets numbers are DIFFERENT for both transfers </p>
                                                    </div>
                                                @endif
                                                @if(($type=='Deposit-Withdrawal' || $type=='Withdrawal-Deposit' )&&((Session::has('palletsNumber')&&(request()->session()->get('palletsNumber')<>$loading->anz))||(Session::has('palletsNumber2')&&(request()->session()->get('palletsNumber2')<>$loading->anz))))
                                                    <div class="text-center">
                                                        <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                        <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                        <p class="text-danger">Pallets number does NOT MATCH the number expected in the loading order ({{$loading->anz}}
                                                            )</p>
                                                    </div>
                                                @endif
                                                @if(Session::has('sumTransfersDepositOnly') && Session::has('sumTransfersWithdrawalOnly') && request()->session()->get('sumTransfersDepositOnly')<>request()->session()->get('sumTransfersWithdrawalOnly') )
                                                    <div class="text-center">
                                                        <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                        <span class="glyphicon glyphicon-warning-sign text-danger"></span><p
                                                                class="text-danger"> Sum of deposit only transfers does NOT MATCH the sum of withdrawal only transfers </p>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                @if((($type=='Deposit-Withdrawal' || $type=='Withdrawal-Deposit'|| $type=='Purchase-Sale')&&(Session::has('creditAccount2')&&Session::has('debitAccount2')&&Session::has('palletsNumber2')&&(request()->session()->get('palletsNumber2')<>request()->session()->get('palletsNumber'))))||(($type=='Deposit-Withdrawal' || $type=='Withdrawal-Deposit')&&((Session::has('palletsNumber')&&(request()->session()->get('palletsNumber')<>$loading->anz))||(Session::has('palletsNumber2')&&(request()->session()->get('palletsNumber2')<>$loading->anz))))||(Session::has('sumTransfersDepositOnly') && Session::has('sumTransfersWithdrawalOnly') && request()->session()->get('sumTransfersDepositOnly')<>request()->session()->get('sumTransfersWithdrawalOnly')))
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
<script type="text/javascript" src="{{asset('js/addUpdatePalletstransfer.js')}}">
</script>