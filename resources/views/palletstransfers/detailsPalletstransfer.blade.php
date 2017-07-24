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
    nonActive
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

@section('content')

    <div class="row">
        @if(Auth::guest())
            <h4>You need to login to see the content</h4>
        @else
            <div class="col-lg-14">
                <div class="panel @if ($transfer->state=="Waiting documents") panelWaitingdocuments @elseif ($transfer->state=="Complete") panelComplete @elseif ($transfer->state=="Complete Validated")panel-general @else panelUntreated @endif">
                    <div class="panel-heading">
                        <div class="col-lg-11 text-left">Details of the pallets
                            transfer n° {{$transfer->id}}
                            @if(!empty($errorsTransfer))
                                @foreach($errorsTransfer as $errorT)
                                    <span class="glyphicon glyphicon-warning-sign text-danger" data-toggle="tooltip" title="{{$errorT->name}}"></span>
                                @endforeach
                            @endif
                        </div>
                        <div>
                            <button type="button"
                                    class=" btn btn-primary btn-form glyphicon glyphicon-remove"
                                    data-toggle="modal"
                                    data-target="#deletePalletstransfer_modal"
                                    value="{{$transfer->id}}"
                                    name="deletePalletstransferModal" ></button>
                        </div>
                    </div>
                    <form class="form-horizontal text-right" role="form" method="POST"
                          action="{{route('updatePalletstransfer', $transfer->id)}}"
                          enctype="multipart/form-data">
                        <input type="hidden"
                               name="_token"
                               value="{{ csrf_token() }}"/>
                        <div class="panel-body panel-body-general">
                            @if(Session::has('messageUpdatePalletstransfer'))
                                <p class="alert alert-success text-alert text-center">{{ Session::get('messageUpdatePalletstransfer') }}</p>
                            @elseif(Session::has('messageErrorUpload'))
                                <p class="alert alert-danger text-alert text-center">{{ Session::get('messageErrorUpload') }}</p>
                            @elseif(Session::has('errorFields'))
                                <p class="alert alert-danger text-alert text-center">{{ Session::get('errorFields') }}</p>
                            @elseif(Session::has('messageUpdateValidatePalletstransfer'))
                                <p class="alert alert-success text-alert text-center">{{ Session::get('messageUpdateValidatePalletstransfer') }}</p>
                            @endif
                            <div class="form-group">
                                <!--type-->
                                <div class="col-lg-1 col-lg-offset-1">
                                    <label for="type"
                                           class="control-label">*Type
                                        :</label>
                                </div>
                                <div class="col-lg-2">
                                    @if(isset($transfer->validate) && $transfer->validate==1)
                                        <input type="text" name="type"
                                               class="form-control"
                                               value="{{$transfer->type}}"
                                               required
                                               readonly/>
                                    @else
                                        <select class="selectpicker show-tick form-control"
                                                data-size="10"
                                                data-live-search="true"
                                                data-live-search-style="startsWith"
                                                title="Type" name="type" id="type" required
                                                onchange="displayFieldsType(this);" >
                                            @if(Illuminate\Support\Facades\Input::old('type') || isset($transfer->type))
                                                <optgroup label="Normal">
                                                    <option @if(strcmp(old('type'),'Deposit-Withdrawal')==0|| strcmp($transfer->type,'Deposit-Withdrawal')==0) selected @endif value="Deposit-Withdrawal" id="Deposit-WithdrawalOption">
                                                        Deposit-Withdrawal
                                                    </option>
                                                    <option @if(strcmp(old('type'),'Withdrawal-Deposit')==0|| strcmp($transfer->type,'Withdrawal-Deposit')==0) selected @endif value="Withdrawal-Deposit" id="Withdrawal-DepositOption">
                                                        Withdrawal-Deposit
                                                    </option>
                                                    <option @if(strcmp(old('type'),'Deposit_Only')==0 || strcmp($transfer->type,'Deposit_Only')==0) selected @endif value="Deposit_Only" id="Deposit_OnlyOption">
                                                        Deposit_Only
                                                    </option>
                                                    <option @if(strcmp(old('type'),'Withdrawal_Only')==0 ||strcmp($transfer->type,'Withdrawal_Only')==0) selected @endif value="Withdrawal_Only" id="Withdrawal_OnlyOption">
                                                        Withdrawal_Only
                                                    </option>
                                                </optgroup>
                                                <optgroup label="Correcting">
                                                    <option @if(strcmp('Purchase-Sale',old('type'))==0 || strcmp($transfer->type,'Purchase-Sale')==0) selected @endif value="Purchase-Sale" id="Purchase-SaleOption">
                                                        Purchase-Sale
                                                    </option>
                                                    <option @if(strcmp('Sale-Purchase',old('type'))==0 || strcmp($transfer->type,'Sale-Purchase')==0) selected @endif value="Sale-Purchase" id="Sale-PurchaseOption">
                                                        Sale-Purchase
                                                    </option>
                                                    <option @if(strcmp('Purchase_Ext',old('type'))==0 || strcmp($transfer->type,'Purchase_Ext')==0) selected @endif value="Purchase_Ext" id="Purchase_ExtOption">
                                                        Purchase_Ext
                                                    </option>
                                                    <option @if(strcmp('Sale_Ext',old('type'))==0 || strcmp($transfer->type,'Sale_Ext')==0) selected @endif value="Sale_Ext" id="Sale_ExtOption">
                                                        Sale_Ext
                                                    </option>
                                                    <option @if(strcmp('Other',old('type'))==0 || strcmp($transfer->type,'Other')==0) selected @endif value="Other" id="OtherOption">
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
                                    @endif
                                </div>
                                <!--details-->
                                <div class="col-lg-3">
                                    @if(isset($transfer->details)&&(isset($transfer->validate) && $transfer->validate==1))
                                        <textarea class="form-control" rows="1"
                                                  id="details" placeholder="Details (broken pallets, gift, receipt...)"
                                                  readonly>{{$transfer->details}}</textarea>
                                    @elseif(isset($transfer->details))
                                        <textarea class="form-control" rows="1"
                                                  id="details"
                                                  placeholder="Details (broken pallets, gift, receipt...)">{{$transfer->details}}</textarea>
                                    @elseif(isset($transfer->validate) && $transfer->validate==1)
                                        <textarea class="form-control" rows="1"
                                                  id="details" placeholder="Details (broken pallets, gift, receipt...)"
                                                  readonly>{{old('details')}}</textarea>
                                    @else
                                        <textarea class="form-control" rows="1"
                                                  id="details"
                                                  placeholder="Details (broken pallets, gift, receipt...)">{{old('details')}}</textarea>
                                    @endif
                                </div>
                                <!--atrnr-->
                                <div class="col-lg-1 text-left">
                                    <label for="loading_atrnr"
                                           class="control-label">@if(isset($transfer->type)&&($transfer->type=='Deposit-Withdrawal'||$transfer->type=='Withdrawal-Deposit'))
                                            <span id="atrnr"
                                                  style="display:inline-block">*</span>@else
                                            <span id="atrnr"
                                                  style="display:none">*</span>@endif Atrnr
                                        :</label>
                                </div>
                                <div class="col-lg-2">
                                    @if(isset($transfer->loading_atrnr)&&(isset($transfer->validate) && $transfer->validate==1))
                                        <input type="text" name="loading_atrnr"
                                               class="form-control"
                                               value="{{$transfer->loading_atrnr}}"
                                               readonly/>
                                    @elseif(isset($transfer->validate) && $transfer->validate==1)
                                        <input type="text" name="loading_atrnr"
                                               class="form-control"
                                               value="{{old('loading_atrnr')}}"
                                               readonly/>
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
                                                @if((Illuminate\Support\Facades\Input::old('loading_atrnr') && $atrnr==old('loading_atrnr'))||(isset($transfer->loading_atrnr)&&$atrnr==$transfer->loading_atrnr))
                                                    <option value="{{$atrnr}}" selected>{{$atrnr}}</option>
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
                                           class="link"><span class="glyphicon glyphicon-info-sign"></span> See loading</a>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <!--number of pallets-->
                                <div class="col-lg-2">
                                    <label for="palletsNumber"
                                           class="control-label">*Pallets
                                        number
                                        :</label>
                                </div>
                                <div class="col-lg-1">
                                    @if(Illuminate\Support\Facades\Input::old('palletsNumber'))
                                        <input id="palletsNumber{{$transfer->id}}" type="number" class="form-control"
                                               name="palletsNumber$transfer" value="{{ old('palletsNumber') }}"
                                               placeholder="Nbr" min="0" required autofocus/>
                                    @elseif(isset($transfer->validate) && $transfer->validate==1)
                                        <input id="palletsNumber" type="number" class="form-control"
                                               name="palletsNumber" value="{{$transfer->palletsNumber}}"
                                               placeholder="Nbr" min="0" autofocus readonly/>
                                    @else
                                        <input id="palletsNumber" type="number" class="form-control"
                                               name="palletsNumber" value="{{$transfer->palletsNumber}}"
                                               placeholder="Nbr" min="0" autofocus/>
                                    @endif
                                </div>
                                <!--date-->
                                <div class="col-lg-1">
                                    <label for="date" class="control-label">Date :</label>
                                </div>
                                <div class="col-lg-2">
                                    @if(isset($transfer->date)&&(isset($transfer->validate) && $transfer->validate==1))
                                        <input id="date" type="date" class="form-control" name="date"
                                               value="{{ $transfer->date }}" placeholder="Date" autofocus readonly/>
                                    @elseif(isset($transfer->date))
                                        <input id="date" type="date" class="form-control" name="date"
                                               value="{{ $transfer->date }}" placeholder="Date" required autofocus/>
                                    @elseif(isset($transfer->validate) && $transfer->validate==1)
                                        <input id="date" type="date" class="form-control" name="date"
                                               value="{{ old('date') }}" placeholder="Date" autofocus readonly/>
                                    @else(Illuminate\Support\Facades\Input::old('date'))
                                        <input id="date" type="date" class="form-control" name="date"
                                               value="{{ old('date') }}" placeholder="Date" autofocus required/>
                                    @endif
                                </div>
                                <div id="normalTransferAssociated"
                                     @if($transfer->type=='Purchase-Sale' || $transfer->type=='Sale-Purchase' || $transfer->type == 'Other') style="display:block"
                                     @else style="display:none" @endif>
                                    <!--transfer normal associated-->
                                    <div class="col-lg-2 text-right">
                                        <label for="normalTransferAssociated"
                                               class="control-label">*Associated
                                            :</label>
                                    </div>
                                    <div class="col-lg-1">
                                        @if(isset($transfer->normalTransferAssociated)&&(isset($transfer->validate) && $transfer->validate==1))
                                            <input type="text"
                                                   name="normalTransferAssociated"
                                                   class="form-control"
                                                   value="{{$transfer->normalTransferAssociated}}"
                                                   readonly/>
                                        @elseif(isset($transfer->validate) && $transfer->validate==1)
                                            <input type="text"
                                                   name="normalTransferAssociated{{$transfer->id}}"
                                                   class="form-control"
                                                   value="{{old('normalTransferAssociated')}}"
                                                   readonly/>
                                        @else
                                            <select class="selectpicker show-tick form-control"
                                                    data-size="5"
                                                    data-live-search="true"
                                                    data-live-search-style="startsWith"
                                                    title="Normal transfer associated"
                                                    name="normalTransferAssociated{{$transfer->id}}">
                                                @foreach($listPalletstransfersNormal as $normalTransfer )
                                                    @if((Illuminate\Support\Facades\Input::old('normalTransferAssociated') && $normalTransfer->id==old('normalTransferAssociated'))||(isset($transfer->normalTransferAssociated)&&$normalTransfer->id==$transfer->normalTransferAssociated))
                                                        <option value="{{$normalTransfer->id}}" selected>{{$normalTransfer->id}}</option>
                                                    @else
                                                        <option value="{{$normalTransfer->id}}">{{$normalTransfer->id}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-2  text-left">
                                    <a href="{{route('showAddPalletsaccount', ['originalPage', 'details...'])}}"
                                       class="link"><span class="glyphicon glyphicon-plus-sign"></span>
                                        Add account</a>
                                </div>
                            </div>

                            <div class="form-group">
                                <!--debit account-->
                                <div class="col-lg-2" id="debitAccount1"
                                     @if($transfer->type=='Other'||$transfer->type=='Purchase-Sale'||$transfer->type=='Sale_Ext'||$transfer->type=='Sale-Purchase'||$transfer->type=='Deposit-Withdrawal'||$transfer->type=='Withdrawal-Deposit'||$transfer->type=='Deposit_Only'||$transfer->type=='Withdrawal_Only') style="display: block" @endif>
                                    <label for="debitAccount"
                                           class="control-label">*Debit
                                        account
                                        :</label>
                                </div>

                                <div class="col-lg-4" id="debitAccount2"
                                     @if($transfer->type=='Other'||$transfer->type=='Purchase-Sale'||$transfer->type=='Sale_Ext'||$transfer->type=='Sale-Purchase'||$transfer->type=='Deposit-Withdrawal'||$transfer->type=='Withdrawal-Deposit'||$transfer->type=='Deposit_Only'||$transfer->type=='Withdrawal_Only') style="display: block" @endif>
                                    @if(isset($transfer->validate) && $transfer->validate==1)
                                        @php($partsDebitAccount=explode('-',$transfer->debitAccount))
                                        @php($aprim=$partsDebitAccount[count($partsDebitAccount)-1])
                                        @php($bprim=$partsDebitAccount[count($partsDebitAccount)-2])
                                        @if(count(array_diff ($partsDebitAccount, [$aprim, $bprim]))==1)
                                            @php($debitAccountValidate=array_diff ($partsDebitAccount, [$aprim, $bprim])[0])
                                        @else
                                            @php($debitAccountValidate=implode(' - ', array_diff ($partsDebitAccount, [$aprim, $bprim])))
                                        @endif
                                        <input type="text"
                                               name="debitAccount"
                                               class="form-control"
                                               value="{{$debitAccountValidate}}"
                                               readonly/>
                                    @else
                                        <select class="selectpicker show-tick form-control"
                                                data-size="10"
                                                data-live-search="true"
                                                data-live-search-style="startsWith"
                                                title="Debit Account"
                                                name="debitAccount"
                                                id="debitAccount" >
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
                                                @if((isset($transfer->debitAccount)&& ($typeDebitAccount == 'account') && ($palletsAccount->id==$idDebitAccount))||(Illuminate\Support\Facades\Input::old('debitAccount') && ($typeDebitAccountOld == 'account') && ($palletsAccount->id==$idDebitAccountOld)))
                                                    <option value="account-{{$palletsAccount->id}}"
                                                            selected>{{$palletsAccount->name}}</option>
                                                @else
                                                    <option value="account-{{$palletsAccount->id}}">{{$palletsAccount->name}}</option>
                                                @endif
                                            @endforeach
                                            @foreach($listTrucksAccounts as $trucksAccount )
                                                @if((Illuminate\Support\Facades\Input::old('debitAccount') && ($typeDebitAccountOld == 'truck') && ($trucksAccount->id==$idDebitAccountOld))||(isset($transfer->debitAccount)&& ($typeDebitAccount== 'truck') && ($trucksAccount->id==$idDebitAccount)))
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
                                </div>

                                <!--credit account-->
                                <div class="col-lg-2" id="creditAccount1"
                                     @if($transfer->type=='Other'||$transfer->type=='Purchase-Sale'||$transfer->type=='Purchase_Ext'||$transfer->type=='Sale-Purchase'||$transfer->type=='Deposit-Withdrawal'||$transfer->type=='Withdrawal-Deposit'||$transfer->type=='Deposit_Only'||$transfer->type=='Withdrawal_Only') style="display: block" @endif>
                                    <label for="creditAccount" class="control-label">*Credit account :</label>
                                </div>
                                <div class="col-lg-4" id="creditAccount2"
                                     @if($transfer->type=='Other'||$transfer->type=='Purchase-Sale'||$transfer->type=='Purchase_Ext'||$transfer->type=='Sale-Purchase'||$transfer->type=='Deposit-Withdrawal'||$transfer->type=='Withdrawal-Deposit'||$transfer->type=='Deposit_Only'||$transfer->type=='Withdrawal_Only') style="display: block" @endif>
                                    @if(isset($transfer->validate) && $transfer->validate==1)
                                        @php($partsCreditAccount=explode('-',$transfer->creditAccount))
                                        @php($a=$partsCreditAccount[count($partsCreditAccount)-1])
                                        @php($b=$partsCreditAccount[count($partsCreditAccount)-2])
                                        @if(count(array_diff ($partsCreditAccount, [$a, $b]))==1)
                                            @php($creditAccountValidate=array_diff ($partsCreditAccount, [$a, $b])[0])
                                        @else
                                            @php($creditAccountValidate=implode( ' - ', array_diff ($partsCreditAccount, [$a, $b])))
                                        @endif
                                        <input type="text"
                                               name="creditAccount"
                                               class="form-control"
                                               value="{{$creditAccountValidate}}"
                                               readonly/>
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
                                                @if((isset($transfer->creditAccount)&& ($typeCreditAccount == 'account') && ($palletsAccount->id==$idCreditAccount))||(Illuminate\Support\Facades\Input::old('creditAccount') && ($typeCreditAccountOld == 'account') && ($palletsAccount->id==$idCreditAccountOld)))
                                                    <option value="account-{{$palletsAccount->id}}"
                                                            selected>{{$palletsAccount->name}}</option>
                                                @else
                                                    <option value="account-{{$palletsAccount->id}}">{{$palletsAccount->name}}</option>
                                                @endif
                                            @endforeach
                                            @foreach($listTrucksAccounts as $trucksAccount )
                                                @if((Illuminate\Support\Facades\Input::old('creditAccount') && ($typeCreditAccountOld == 'truck') && ($trucksAccount->id==$idCreditAccountOld))||(isset($transfer->creditAccount)&& ($typeCreditAccount == 'truck') && ($trucksAccount->id==$idCreditAccount)))
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
                                </div>
                            </div>

                            <!--documents proof upload-->
                            <div class="form-group">
                                <div class="col-lg-2">
                                    <label for="documentsTransfer">*Proof docs ?</label>
                                </div>
                                <div class="col-lg-4">
                                    <input type="file" name="documentsTransfer[]" multiple id="documentsTransfer"/>
                                </div>
                                <!--button upload-->
                                <div class="col-lg-2">
                                    <button type="submit" class="btn btn-primary btn-block btn-form"
                                            value="{{$transfer->id}}" name="upload">
                                        Upload
                                    </button>
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
                                               class="control-label">*Validated ?
                                        </label>
                                    </div>
                                    <div class="col-lg-2 text-left">
                                        @if(isset($transfer->validate) && $transfer->validate==1)
                                            <label class="radio-inline"><input type="radio"
                                                                               name="validate{{$transfer->id}}"
                                                                               value="true" checked id="validateYes"/>Yes</label>
                                            <label class="radio-inline"><input type="radio"
                                                                               name="validate{{$transfer->id}}"
                                                                               value="false" id="validateNo"/>No</label>
                                        @elseif(isset($transfer->validate) && $transfer->validate==0)
                                            <label class="radio-inline"><input type="radio"
                                                                               name="validate{{$transfer->id}}"
                                                                               value="true" id="validateYes">Yes</label>
                                            <label class="radio-inline"><input type="radio"
                                                                               name="validate{{$transfer->id}}"
                                                                               value="false" checked id="validateNo"/>No</label>
                                        @endif
                                    </div>
                            @endif
                            <!--submit-->
                                <div @if(!empty($filesNames)&&isset($transfer->palletsNumber)&&isset($transfer->creditAccount)&&isset($transfer->debitAccount)) class="col-lg-4 col-lg-offset-1"
                                     @else class="col-lg-4 col-lg-offset-5" @endif>
                                    <button type="submit" class="btn btn-primary btn-block btn-form"
                                            value="{{$transfer->id}}" name="update" data-toggle="modal"
                                            data-target="#submitUpdate_modal">
                                        Update
                                    </button>
                                </div>
                                    @if(!empty($errorsTransfer) && $transfer->type<>'Purchase-Sale' && $transfer->type<>'Sale-Purchase' && $transfer->type<>'Other')
                                    <!--show addCorrectingTransfer -->
                                        <div class="col-lg-3">
                                            <button type="submit" class="btn btn-primary btn-block btn-form" value="{{$transfer->id}}" name="showAddCorrectingTransfer" data-toggle="modal" data-target="#addForm">
                                                Add correcting transfer
                                            </button>
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
                                            <button value="{{$transfer->id}}"
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
                                            @php($partsActualCreditAccount=explode('-',request()->session()->get('actualCreditAccount')))
                                            @php($a=$partsActualCreditAccount[count($partsActualCreditAccount)-1])
                                            @php($b=$partsActualCreditAccount[count($partsActualCreditAccount)-2])
                                            @if(count(array_diff ($partsActualCreditAccount, [$a, $b]))==1)
                                                @php($actualCreditAccount=array_diff ($partsActualCreditAccount, [$a, $b])[0])
                                            @else
                                                @php($actualCreditAccount=implode(' - ', array_diff ($partsActualCreditAccount, [$a, $b])))
                                            @endif

                                            @php($partsActualDebitAccount=explode('-',request()->session()->get('actualDebitAccount')))
                                            @php($aprim=$partsActualDebitAccount[count($partsActualDebitAccount)-1])
                                            @php($bprim=$partsActualDebitAccount[count($partsActualDebitAccount)-2])
                                            @if(count(array_diff ($partsActualDebitAccount, [$aprim, $bprim]))==1)
                                                @php($actualDebitAccount=array_diff ($partsActualDebitAccount, [$aprim, $bprim])[0])
                                            @else
                                                @php($actualDebitAccount=implode( ' - ', array_diff ($partsActualDebitAccount, [$aprim, $bprim])))
                                            @endif

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
                                                <!-- name account -->
                                                <tr>
                                                    <td></td>
                                                    @if(Session::has('debitAccount'))
                                                        <td class="text-center">{{request()->session()->get('debitAccount')}}</td>
                                                    @endif
                                                    @if(Session::has('creditAccount'))
                                                        <td class="text-center">{{request()->session()->get('creditAccount')}}</td>
                                                    @endif
                                                </tr>
                                                <!-- actual -->
                                                <tr>
                                                    <td class="text-center">
                                                        Actual
                                                    </td>
                                                    @if(Session::has('debitAccount'))
                                                        <td class="text-center">{{request()->session()->get('thPalletsNumberDebitAccount')}}</td>
                                                    @endif
                                                    @if(Session::has('creditAccount'))
                                                        <td class="text-center">{{request()->session()->get('thPalletsNumberCreditAccount')}}</td>
                                                    @endif
                                                </tr>
                                                <!-- actual credit acc = credit acc && actual debit acc = debit acc-->
                                                @if($actualCreditAccount==request()->session()->get('creditAccount') && $actualDebitAccount==request()->session()->get('debitAccount'))
                                                    <!--update-->
                                                    <tr>
                                                        <td class="text-center">
                                                            Update
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
                                                    <!--total-->
                                                    <tr>
                                                        <td class="text-center">
                                                            Total
                                                        </td>
                                                        @if(Session::has('debitAccount'))
                                                            <td class="text-center">
                                                                = {{request()->session()->get('thPalletsNumberDebitAccount')  + request()->session()->get('actualPalletsNumber') -request()->session()->get('palletsNumber')}}</td>
                                                        @endif
                                                        @if(Session::has('creditAccount'))
                                                            <td class="text-center">
                                                                = {{request()->session()->get('thPalletsNumberCreditAccount')- request()->session()->get('actualPalletsNumber') +request()->session()->get('palletsNumber')}}</td>
                                                        @endif
                                                    </tr>
                                                    <!-- actual credit acc <> credit acc && actual debit acc <> debit acc -->
                                                @elseif($actualCreditAccount<>request()->session()->get('creditAccount') && $actualDebitAccount<>request()->session()->get('debitAccount'))
                                                    <!-- new transfer -->
                                                    <tr>
                                                        <td class="text-center">
                                                            New
                                                            transfer
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
                                                    <!-- total -->
                                                    <tr>
                                                        <td class="text-center">
                                                            Total
                                                        </td>
                                                        @if(Session::has('debitAccount'))
                                                            <td class="text-center">
                                                                = {{request()->session()->get('thPalletsNumberDebitAccount') -request()->session()->get('palletsNumber')}}</td>
                                                        @endif
                                                        @if(Session::has('creditAccount'))
                                                            <td class="text-center">
                                                                = {{request()->session()->get('thPalletsNumberCreditAccount')+request()->session()->get('palletsNumber')}}</td>
                                                        @endif
                                                    </tr>
                                                    <!--actual credit acc == credit acc && actual debit acc <> debit acc-->
                                                @elseif($actualCreditAccount==request()->session()->get('creditAccount') && $actualDebitAccount<>request()->session()->get('debitAccount'))
                                                    <!-- update-->
                                                    <tr>
                                                        <td class="text-center">
                                                            Update
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
                                                    <!-- total -->
                                                    <tr>
                                                        <td class="text-center">
                                                            Total
                                                        </td>
                                                        @if(Session::has('debitAccount'))
                                                            <td class="text-center">
                                                                = {{request()->session()->get('thPalletsNumberDebitAccount') -request()->session()->get('palletsNumber')}}</td>
                                                        @endif
                                                        @if(Session::has('creditAccount'))
                                                            <td class="text-center">
                                                                = {{request()->session()->get('thPalletsNumberCreditAccount')- request()->session()->get('actualPalletsNumber') +request()->session()->get('palletsNumber')}}</td>
                                                        @endif
                                                    </tr>
                                                    <!-- actual credit acc <> credit acc && actual debit acc = debit acc-->
                                                @elseif($actualCreditAccount<>request()->session()->get('creditAccount') && $actualDebitAccount==request()->session()->get('debitAccount'))
                                                    <!-- update -->
                                                    <tr>
                                                        <td class="text-center">
                                                            Update
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
                                                    <!-- total-->
                                                    <tr>
                                                        <td class="text-center">
                                                            Total
                                                        </td>
                                                        @if(Session::has('debitAccount'))
                                                            <td class="text-center">
                                                                = {{request()->session()->get('thPalletsNumberDebitAccount')+ request()->session()->get('actualPalletsNumber') -request()->session()->get('palletsNumber')}}</td>
                                                        @endif
                                                        @if(Session::has('creditAccount'))
                                                            <td class="text-center">
                                                                = {{request()->session()->get('thPalletsNumberCreditAccount') +request()->session()->get('palletsNumber')}}</td>
                                                        @endif
                                                    </tr>
                                                @endif
                                                </tbody>
                                            </table>

                                            @foreach($errors as $error)
                                                @if($error->name=='Correcting_notCompleteNormal')
                                                    <div class="text-center">
                                                        <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                        <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                        <p class="text-danger">Pallets number does NOT CORRECT the
                                                            number expected in the loading order ({{$loading->anz}}
                                                            )</p>
                                                    </div>
                                                @endif
                                                @if($error->name=='SP-PS_notSameNumber')
                                                    <div class="text-center">
                                                        <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                        <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                        <p class="text-danger"> Pallets numbers are DIFFERENT for
                                                            both transfers </p>
                                                    </div>
                                                @endif
                                                @if($error->name=='DW-WD_notNumberLoadingOrder')
                                                    <div class="text-center">
                                                        <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                        <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                        <p class="text-danger">Pallets number does NOT MATCH the number
                                                            expected in the loading order ({{$loading->anz}}
                                                            )</p>
                                                    </div>
                                                @endif
                                                @if($error->name=='Donly-Wonly_notSameNumber')
                                                    <div class="text-center">
                                                        <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                        <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                        <p class="text-danger"> Sum of deposit only transfers does
                                                            NOT MATCH the sum of withdrawal only transfers </p>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit"
                                                    @if(!empty($errors))
                                                    class="btn btn-danger btn-modal"
                                                    @else
                                                    class="btn btn-default btn-form btn-modal"
                                                    @endif
                                                    value="yes"
                                                    name="okSubmitUpdateModal"
                                                    data-toggle="modal"
                                                    data-target="#submitUpdateValidate_modal">
                                                Confirm
                                            </button>
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
                                            <button value="{{$transfer->id}}"
                                                    class="close"
                                                    type="submit"
                                                    name="closeSubmitValidateUpdateModal">
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
                                            @php($partsCreditAccount=explode('-',request()->session()->get('creditAccount')))
                                            @php($a=$partsCreditAccount[count($partsCreditAccount)-1])
                                            @php($b=$partsCreditAccount[count($partsCreditAccount)-2])
                                            @if(count(array_diff ($partsCreditAccount, [$a, $b]))==1)
                                                @php($creditAccountValidate=array_diff ($partsCreditAccount, [$a, $b])[0])
                                            @else
                                                @php($creditAccountValidate=implode(' - ', array_diff ($partsCreditAccount, [$a, $b])))
                                            @endif

                                            @php($partsDebitAccount=explode('-',request()->session()->get('debitAccount')))
                                            @php($aprim=$partsDebitAccount[count($partsDebitAccount)-1])
                                            @php($bprim=$partsDebitAccount[count($partsDebitAccount)-2])
                                            @if(count(array_diff ($partsDebitAccount, [$aprim, $bprim]))==1)
                                                @php($debitAccountValidate=array_diff ($partsDebitAccount, [$aprim, $bprim])[0])
                                            @else
                                                @php($debitAccountValidate=implode( ' - ', array_diff ($partsDebitAccount, [$aprim, $bprim])))
                                            @endif
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
                                                        <td class="text-center">{{$debitAccountValidate}}</td>
                                                    @endif
                                                    @if(Session::has('creditAccount'))
                                                        <td class="text-center">{{$creditAccountValidate}}</td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <td class="text-center">
                                                        Actual
                                                    </td>
                                                    @if(Session::has('debitAccount'))
                                                        <td class="text-center">{{request()->session()->get('realPalletsNumberDebitAccount')}}</td>
                                                    @endif
                                                    @if(Session::has('creditAccount'))
                                                        <td class="text-center">{{request()->session()->get('realPalletsNumberCreditAccount')}}</td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <td class="text-center">
                                                        New transfer
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
                                                    <td class="text-center">
                                                        Total
                                                    </td>
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
                                            @foreach($errors as $error)
                                                @if($error->name=='Correcting_notCompleteNormal')
                                                    <div class="text-center">
                                                        <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                        <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                        <p class="text-danger">Pallets number does NOT CORRECT the
                                                            number expected in the loading order ({{$loading->anz}}
                                                            )</p>
                                                    </div>
                                                @endif
                                                @if($error->name=='SP-PS_notSameNumber')
                                                    <div class="text-center">
                                                        <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                        <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                        <p class="text-danger"> Pallets numbers are DIFFERENT for
                                                            both transfers </p>
                                                    </div>
                                                @endif
                                                @if($error->name=='DW-WD_notNumberLoadingOrder')
                                                    <div class="text-center">
                                                        <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                        <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                        <p class="text-danger">Pallets number does NOT MATCH the number
                                                            expected in the loading order ({{$loading->anz}}
                                                            )</p>
                                                    </div>
                                                @endif
                                                @if($error->name=='Donly-Wonly_notSameNumber')
                                                    <div class="text-center">
                                                        <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                        <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                        <p class="text-danger"> Sum of deposit only transfers does
                                                            NOT MATCH the sum of withdrawal only transfers </p>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit"
                                                    @if(!empty($errors))
                                                    class="btn btn-danger btn-modal"
                                                    @else
                                                    class="btn btn-default btn-form btn-modal"
                                                    @endif
                                                    value="yes"
                                                    name="okSubmitUpdateValidateModal">
                                                Confirm
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </form>

                    <!-- Modal Delete -->
                    <div class="modal @if(isset($delete)) show @else fade @endif" id="deletePalletstransfer_modal"
                         role="dialog">
                        <div class="modal-dialog modal-md">
                            <div class="modal-content">
                                <div class="modal-header">
                                    @if(isset($delete))
                                        <a href="{{route('showDetailsPalletstransfer', $transfer->id)}}"
                                           class="close">&times;</a>
                                    @else
                                        <button type="button" class="close" data-dismiss="modal">&times;
                                        </button>
                                    @endif
                                    <h4 class="modal-title text-center">Are you
                                        sure to
                                        delete the following
                                        pallets
                                        transfer ?</h4>
                                </div>
                                <div class="modal-body center">
                                    <form method="post"
                                          action="{{route('deletePalletstransfer', $transfer->id)}}">
                                        <input type="hidden" name="_method"
                                               value="delete">
                                        {{ csrf_field() }}
                                        @php($partsCreditAccount=explode('-',$transfer->creditAccount))
                                        @php($a=$partsCreditAccount[count($partsCreditAccount)-1])
                                        @php($b=$partsCreditAccount[count($partsCreditAccount)-2])
                                        @if(count(array_diff ($partsCreditAccount, [$a, $b]))==1)
                                            @php($creditAccountValidate=array_diff ($partsCreditAccount, [$a, $b])[0])
                                        @else
                                            @php($creditAccountValidate=implode(' - ', array_diff ($partsCreditAccount, [$a, $b])))
                                        @endif

                                        @php($partsDebitAccount=explode('-',$transfer->debitAccount))
                                        @php($aprim=$partsDebitAccount[count($partsDebitAccount)-1])
                                        @php($bprim=$partsDebitAccount[count($partsDebitAccount)-2])
                                        @if(count(array_diff ($partsDebitAccount, [$aprim, $bprim]))==1)
                                            @php($debitAccountValidate=array_diff ($partsDebitAccount, [$aprim, $bprim])[0])
                                        @else
                                            @php($debitAccountValidate=implode( ' - ', array_diff ($partsDebitAccount, [$aprim, $bprim])))
                                        @endif
                                        <div class="text-center">
                                            <p>{{$transfer->palletsNumber}} pallets</p>
                                            <p>from : {{$debitAccountValidate}}</p>
                                            <p>to : {{$creditAccountValidate}}</p>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit"
                                                    class="btn btn-danger btn-modal"
                                                    value="yes"
                                                    name="delete">
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
                    </div>

                    <!-- Modal Send Email -->
                    {{--<div class="modal fade" id="sendEmailTransfer_modal"--}}
                         {{--role="dialog">--}}
                        {{--<div class="modal-dialog modal-sm">--}}
                            {{--<div class="modal-content">--}}
                                {{--<div class="modal-header">--}}
                                    {{--<button type="button" class="close" data-dismiss="modal">&times;--}}
                                    {{--</button>--}}
                                    {{--<h4 class="modal-title text-center">You can find contact information to warn people of a problem in the transfer {{$transfer->id}} ?</h4>--}}
                                {{--</div>--}}
                                {{--<div class="modal-body">--}}

                                {{--</div>--}}
                                {{--<div class="modal-footer">--}}
                                    {{--<button type="button"--}}
                                            {{--class="btn btn-success btn-modal"--}}
                                            {{--data-dismiss="modal">--}}
                                        {{--Send email--}}
                                    {{--</button>--}}
                                    {{--<button type="button"--}}
                                            {{--class="btn btn-default btn-modal"--}}
                                            {{--data-dismiss="modal">--}}
                                        {{--Close--}}
                                    {{--</button>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                </div>
            </div>
        @endif
    </div>
@endsection
<script type="text/javascript" src="{{asset('js/addUpdatePalletstransfer.js')}}">
</script>