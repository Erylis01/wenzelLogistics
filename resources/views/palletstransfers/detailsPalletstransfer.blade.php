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
    active
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
                <div class="panel @if ($transfer->state=="Waiting documents") panelWaitingdocuments @elseif ($transfer->state=="Complete") panelComplete @elseif ($transfer->state=="Complete Validated")panel-general @else panelUntreated @endif">
                    <div class="panel-heading">
                        @if(isset($transfer->debitAccount))
                        @php($partsDebitAccount=explode('-',$transfer->debitAccount))
                        @php($idDebAcc=$partsDebitAccount[count($partsDebitAccount)-1])
                        @php($typeDebAcc=$partsDebitAccount[count($partsDebitAccount)-2])
                        @if(count(array_diff ($partsDebitAccount, [$idDebAcc, $typeDebAcc]))==1)
                            @php($debitAccount=array_diff ($partsDebitAccount, [$idDebAcc, $typeDebAcc])[0])
                        @else
                            @php($debitAccount=implode( ' - ', array_diff ($partsDebitAccount, [$idDebAcc, $typeDebAcc])))
                        @endif
                        @endif
                        @if($transfer->creditAccount)
                        @php($partsCreditAccount=explode('-',$transfer->creditAccount))
                        @php($idCredAcc=$partsCreditAccount[count($partsCreditAccount)-1])
                        @php($typeCredAcc=$partsCreditAccount[count($partsCreditAccount)-2])
                        @if(count(array_diff ($partsCreditAccount, [$idCredAcc, $typeCredAcc]))==1)
                            @php($creditAccount=array_diff ($partsCreditAccount, [$idCredAcc, $typeCredAcc])[0])
                        @else
                            @php($creditAccount=implode( ' - ', array_diff ($partsCreditAccount, [$idCredAcc, $typeCredAcc])))
                        @endif
                            @endif
                        <div class="col-lg-11 text-left">Details of the pallets
                            transfer : {{$debitAccount}} <span class="glyphicon glyphicon-arrow-right"></span> {{$transfer->palletsNumber}} <span class="glyphicon glyphicon-arrow-right"></span> {{$creditAccount}}
                            @if(!empty($errorsTransfer))
                                @foreach($errorsTransfer as $errorT)
                                    <span class="glyphicon glyphicon-warning-sign text-danger" data-toggle="tooltip"
                                          title="{{$errorT->name}}"></span>
                                @endforeach
                            @endif
                        </div>
                        <div>
                            <button type="button"
                                    class=" btn btn-primary btn-form glyphicon glyphicon-trash"
                                    data-toggle="modal"
                                    data-target="#deletePalletstransfer_modal"
                                    value="deletePalletstransferModal"
                                    id="deletePalletstransferModal"
                                    name="deletePalletstransferModal"></button>
                        </div>
                    </div>
                    <form class="form-horizontal text-right" role="form" method="POST"
                          action="{{route('updatePalletstransfer', $transfer->id)}}"
                          enctype="multipart/form-data" id="formUpdateUpload">
                        <input type="hidden"
                               name="_token"
                               value="{{ csrf_token() }}"/>
                        <input type="hidden" name="actionForm" id="actionForm" />
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
                                <div class="col-lg-1">
                                    <label for="type"
                                           class="control-label">*Type
                                        :</label>
                                </div>
                                <div class="col-lg-2">
                                    <input type="text" name="type"
                                           class="form-control"
                                           value="{{$transfer->type}}"
                                           required
                                           readonly/>
                                </div>
                                <!--details-->
                                <div class="col-lg-5">
                                    @if(isset($transfer->details)&&(isset($transfer->validate) && $transfer->validate==1))
                                        <textarea class="form-control" rows="1"
                                                  id="details" placeholder="Details (broken pallets, gift, receipt...)"
                                                  readonly name="details">{{$transfer->details}}</textarea>
                                    @elseif(isset($transfer->details))
                                        <textarea class="form-control" rows="1"
                                                  id="details" name="details"
                                                  placeholder="Details (broken pallets, gift, receipt...)">{{$transfer->details}}</textarea>
                                    @elseif(isset($transfer->validate) && $transfer->validate==1)
                                        <textarea class="form-control" rows="1" name="details"
                                                  id="details" placeholder="Details (broken pallets, gift, receipt...)"
                                                  readonly>{{old('details')}}</textarea>
                                    @else
                                        <textarea class="form-control" rows="1"
                                                  id="details" name="details"
                                                  placeholder="Details (broken pallets, gift, receipt...)">{{old('details')}}</textarea>
                                    @endif
                                </div>
                                <!--date-->
                                <div class="col-lg-1 col-lg-offset-1">
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
                            </div>

                            <div class="form-group">
                                <!--atrnr-->
                                @if(isset($transfer->loading_atrnr))
                                    <div class="col-lg-1 text-left">
                                        <label for="loading_atrnr" class="control-label"> *Atrnr :</label>
                                    </div>
                                    <div class="col-lg-2 text-left">
                                        <a href="{{route('showDetailsLoading', $transfer->loading_atrnr)}}"
                                           class="link">{{$transfer->loading_atrnr}}</a>
                                    </div>
                                @endif
                            <!--transfer normal associated-->
                                @if(isset($transfer->transferToCorrect))
                                    <div class="col-lg-2 text-right">
                                        <label for="transferToCorrect" class="control-label">*Correction on
                                            :</label>
                                    </div>
                                    <div class="col-lg-1">
                                        <input type="text" name="transferToCorrect" class="form-control"
                                               value="{{$transfer->transferToCorrect}}" readonly/>
                                    </div>
                                @endif
                            <!--agreed with or without exchange-->
                                @if($transfer->notExchange==1)
                                    <div class="col-lg-3">
                                        Agreed without exchange pallets
                                    </div>
                                @elseif($transfer->notExchange==0)
                                    <div class="col-lg-3">
                                        Agreed with exchange pallets
                                    </div>
                            @endif
                            <!--add account-->
                                <div class="col-lg-2">
                                    <a href="{{route('showAddPalletsaccount', ['originalPage' => 'detailsPalletstransfer-'.$transfer->id])}}"
                                       class="link"><span class="glyphicon glyphicon-plus-sign"></span> Account</a>
                                </div>
                            </div>
                            <br>
                            <div class="form-group">
                                <!--number of pallets-->
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default btn-number" data-type="minus"
                                                    data-field="palletsNumber">
                                                <span class="glyphicon glyphicon-minus"></span>
                                            </button>
                                        </span>
                                        <input id="palletsNumber" type="number" name="palletsNumber"
                                               class="form-control input-number text-center"
                                                value="{{$transfer->palletsNumber}}" placeholder="Nbr" min="0" max="999999" autofocus required>
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default btn-number" data-type="plus"
                                                    data-field="palletsNumber">
                                                <span class="glyphicon glyphicon-plus"></span>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-2 text-center">
                                    <label for="palletsNumber" class="control-label">pallets</label>
                                </div>
                                <!-- debit account -->
                                @if(isset($debitAccount))
                                <div class="col-lg-1 text-center">
                                    <label for="debitAccount" class="control-label">from</label>
                                </div>
                                <div class="col-lg-3" id="debitAccount">
                                    @if((isset($transfer->validate) && $transfer->validate==1)|| ($transfer->type=='Deposit-Withdrawal' && $transfer->notExchange==1) || ($transfer->type=='Deposit_Only' && $transfer->notExchange==1))
                                        <input type="hidden" value="{{$typeDebAcc}}-{{$idDebAcc}}" name="debitAccount"/>
                                        <input type="text" value="{{$debitAccount}}" name="input-debitAccount" class="form-control" readonly/>
                                        @else
                                    <select class="selectpicker show-tick form-control" data-size="10" data-live-search="true" data-live-search-style="startsWith" id="select-debitAccount" title="Account (pallets giver)" name="debitAccount" required>
                                        @foreach($listPalletsAccounts as $palletsAccount )
                                            @if((Illuminate\Support\Facades\Input::old('debitAccount') && (strpos(old('debitAccount'), '-') == 7 && explode('-', old('debitAccount'))[0] == 'account') && ($palletsAccount->id==explode('-', old('debitAccount'))[1]))||(isset($debitAccount)  && $typeDebAcc == 'account' && $palletsAccount->id==$idDebAcc))
                                                <option value="account-{{$palletsAccount->id}}" selected>{{$palletsAccount->nickname}}</option>
                                            @else
                                                <option value="account-{{$palletsAccount->id}}">{{$palletsAccount->nickname}}</option>
                                            @endif
                                        @endforeach
                                        @foreach($listTrucksAccounts as $trucksAccount )
                                            @if((Illuminate\Support\Facades\Input::old('debitAccount') && (strpos(old('debitAccount'), '-') == 5 && explode('-', old('debitAccount'))[0] == 'truck') && ($trucksAccount->id==explode('-', old('debitAccount'))[1]))|| (isset($debitAccount)  && $typeDebAcc == 'truck' && $palletsAccount->id==$idDebAcc))
                                                <option value="truck-{{$trucksAccount->id}}" selected>{{$trucksAccount->name}} - {{$trucksAccount->licensePlate}}</option>
                                            @else
                                                <option value="truck-{{$trucksAccount->id}}">{{$trucksAccount->name}}
                                                    - {{$trucksAccount->licensePlate}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                        @endif
                                </div>
                                @endif
                                <!-- credit account -->
                                @if(isset($creditAccount))
                                <div class="col-lg-1 text-center">
                                    <label for="creditAccount" class="control-label">to</label>
                                </div>
                                <div class="col-lg-3" id="creditAccount">
                                    @if((isset($transfer->validate) && $transfer->validate==1) || ($transfer->type=='Withdrawal-Deposit' && $transfer->notExchange==1) || ($transfer->type=='Withdrawal_Only' && $transfer->notExchange==1))
                                        <input type="hidden" value="{{$typeCredAcc}}-{{$idCredAcc}}" name="creditAccount"/>
                                        <input type="text" value="{{$creditAccount}}" name="input-creditAccount" class="form-control" readonly/>
                                    @else
                                    <select class="selectpicker show-tick form-control" data-size="10" data-live-search="true" data-live-search-style="startsWith" id="select-creditAccount" title="Account (pallets taker)" name="creditAccount" required>
                                        @foreach($listPalletsAccounts as $palletsAccount )
                                            @if((Illuminate\Support\Facades\Input::old('creditAccount') && (strpos(old('creditAccount'), '-') == 7 && explode('-', old('creditAccount'))[0] == 'account') && ($palletsAccount->id==explode('-', old('creditAccount'))[1]))||(isset($creditAccount)  && $typeCredAcc == 'account' && $palletsAccount->id==$idCredAcc))
                                                <option value="account-{{$palletsAccount->id}}" selected>{{$palletsAccount->nickname}}</option>
                                            @else
                                                <option value="account-{{$palletsAccount->id}}">{{$palletsAccount->nickname}}</option>
                                            @endif
                                        @endforeach
                                        @foreach($listTrucksAccounts as $trucksAccount )
                                            @if((Illuminate\Support\Facades\Input::old('creditAccount') && (strpos(old('creditAccount'), '-') == 5 && explode('-', old('creditAccount'))[0] == 'truck') && ($trucksAccount->id==explode('-', old('creditAccount'))[1]))|| (isset($creditAccount)  && $typeCredAcc == 'truck' && $palletsAccount->id==$idCredAcc))
                                                <option value="truck-{{$trucksAccount->id}}" selected>{{$trucksAccount->name}} - {{$trucksAccount->licensePlate}}</option>
                                            @else
                                                <option value="truck-{{$trucksAccount->id}}">{{$trucksAccount->name}}
                                                    - {{$trucksAccount->licensePlate}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                        @endif
                                </div>
                                @endif
                            </div>
<br>
                            <!--documents proof upload-->
                            <div class="form-group">
                                <div class="col-lg-2 text-left">
                                    <label for="documentsTransfer">*Proof docs ?</label>
                                </div>
                                <div class="col-lg-4">
                                    <input type="file" name="documentsTransfer[]" multiple id="documentsTransfer"/>
                                </div>
                                <!--button upload-->
                                <div class="col-lg-2">
                                    <button type="submit" class="btn btn-primary btn-block btn-form"
                                            value="upload" name="upload" id="upload" onclick="formSubmitBlock(this);">
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
                                                                class="btn-add glyphicon glyphicon-trash"
                                                                value="deleteDocument-{{$nameF}}" onclick="formSubmitBlock(this);"></button>
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
                                            <label class="radio-inline">
                                                <input type="radio" name="validate{{$transfer->id}}"
                                                       value="true" checked id="validateYes"/>Yes</label>
                                            <label class="radio-inline">
                                                <input type="radio" name="validate{{$transfer->id}}"
                                                       value="false" id="validateNo"/>No</label>
                                        @elseif(isset($transfer->validate) && $transfer->validate==0)
                                            <label class="radio-inline">
                                                <input type="radio" name="validate{{$transfer->id}}"
                                                       value="true" id="validateYes">Yes</label>
                                            <label class="radio-inline">
                                                <input type="radio" name="validate{{$transfer->id}}"
                                                       value="false" checked id="validateNo"/>No</label>
                                        @endif
                                    </div>
                                @endif
                            <!--submit-->
                                <div @if(!empty($filesNames)&&isset($transfer->palletsNumber)&&isset($transfer->creditAccount)&&isset($transfer->debitAccount)) class="col-lg-4 col-lg-offset-1"
                                     @else class="col-lg-4 col-lg-offset-5" @endif>
                                    <button type="submit" class="btn btn-primary btn-block btn-form"
                                            value="update" name="update" id="update" data-toggle="modal"
                                            data-target="#submitUpdate_modal" onclick="formSubmitBlock(this);">
                                        Update
                                    </button>
                                </div>
                                {{--@if(!empty($errorsTransfer) && ($transfer->type=='Deposit-Withdrawal' || $transfer->type=='Deposit_Only' || $transfer->type=='Withdrawal_Only'))--}}
                                {{--<!--show addCorrectingTransfer -->--}}
                                    {{--<div class="col-lg-3">--}}
                                        {{--<button type="submit" class="btn btn-primary btn-block btn-form"--}}
                                                {{--value="showAddCorrectingTransfer-{{$transfer->id}}" name="showAddCorrectingTransfer" id="showAddCorrectingTransfer"--}}
                                                {{--data-toggle="modal" data-target="#addForm" onclick="formSubmitBlock(this);">--}}
                                            {{--Add correcting transfer--}}
                                        {{--</button>--}}
                                    {{--</div>--}}
                                {{--@endif--}}
                            </div>
                        </div>

                        <!-- Modal update -->
                        @if(isset($actionForm) && $actionForm=='update')
                            <div class="modal show"
                                 id="submitUpdate_modal"
                                 role="dialog">
                                <div class="modal-dialog modal-md">
                                    <div class="modal-content">
                                        <div class="modal-header modalHeaderTransfer">
                                            <button  class="close"
                                                    type="submit"
                                                    name="closeSubmitUpdateModal" value="closeSubmitUpdateModal" id="closeSubmitUpdateModal" onclick="formSubmitBlock(this);">
                                                &times;
                                            </button>
                                            <h4 class="modal-title text-center">TRANSFERS RECAP</h4>
                                            @if(Session::has('validate') && request()->session()->get('validate')=='true' )
                                            <h4 class="modal-title text-center">validation</h4>
                                                @endif
                                        </div>
                                        <div class="modal-body center modalBodyTransfer">
                                            <p class="text-center">Date : {{$transfer->date}}</p>
                                            <p class="text-center">Type : {{$transfer->type}} </p>
                                            @if(isset($transfer->details))
                                                <p class="text-center">Details : {{$transfer->details}}</p>
                                            @endif
                                            <table class="table table-hover table-bordered">
                                                <thead>
                                                <tr>
                                                    <th class="text-center">Type</th>
                                                    <th class="text-center">From</th>
                                                    <th class="text-center">To</th>
                                                    <th class="text-center">Nbr</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td class="text-center">{{$transfer->type}}</td>
                                                    <td class="text-center">{{request()->session()->get('debitAccount')}}</td>
                                                    <td class="text-center">{{request()->session()->get('creditAccount')}}</td>
                                                    <td class="text-center">{{request()->session()->get('palletsNumber')}}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            {{--<div class="text-center">--}}
                                                {{--<span class="glyphicon glyphicon-warning-sign text-danger"></span>--}}
                                                {{--<span class="glyphicon glyphicon-warning-sign text-danger"></span>--}}
                                                {{--<span class="glyphicon glyphicon-warning-sign text-danger"></span>--}}
                                            {{--</div>--}}
                                            {{--<div class="text-center">--}}
                                                {{--@foreach($errorsTransfer as $errorTrans)--}}
                                                    {{--@if($errorTrans->name=='DW-WD_atLeastOne')--}}
                                                        {{--<p class="text-danger"> A with-dep transfer or dep-with transfer is missing</p>--}}
                                                    {{--@endif--}}
                                                    {{--@if($errorTrans->name=='DW-WD_notNumberLoadingOrder')--}}
                                                        {{--<p class="text-danger"> The pallets numbers sum of with-dep transfers or dep-with transfers does NOT MATCH the number in the loading order</p>--}}
                                                    {{--@endif--}}
                                                    {{--@if($errorTrans->name=='Donly-Wonly_notSameNumber')--}}
                                                        {{--<p class="text-danger"> Sum of deposit only transfers does NOT MATCH the sum of withdrawal only transfers </p>--}}
                                                    {{--@endif--}}
                                                {{--@endforeach--}}
                                                {{--@if(empty($errorsTransfer))--}}
                                                    {{--<p class="text-center">GOOD ! no errors</p>--}}
                                                {{--@endif--}}
                                            {{--</div>--}}
                                        </div>
                                        <div class="modal-footer">
                                            <div class="text-center">
                                                <div class="col-lg-7">
                                                    @if(request()->session('validate')=='true')
                                                        <h5>(Confirm will affect PLANNED and CONFIRMED pallets number)</h5>
                                                    @elseif(request()->session('validate')=='false')
                                                        <h5>(Confirm will only affect PLANNED pallets number)</h5>
                                                    @endif
                                                </div>
                                                <div class="col-lg-4 col-lg-offset-1">
                                                    <button type="submit"
                                                            {{--@if(!empty($errorsTransfer)) class="btn btn-danger btn-modal"--}}
                                                            class="btn btn-default btn-form btn-modal"
                                                            value="okSubmitPalletsModal" name="okSubmitUpdateModal" id="okSubmitUpdateModal"
                                                            onclick="formSubmitBlock(this);">
                                                        Confirm
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    {{--<!-- Modal update -->--}}
                        {{--@if(isset($okSubmitUpdateModal) && $transfer->state=='Complete Validated')--}}
                            {{--<div class="modal show"--}}
                                 {{--id="submitUpdateValidate_modal"--}}
                                 {{--role="dialog">--}}
                                {{--<div class="modal-dialog modal-md">--}}
                                    {{--<div class="modal-content">--}}
                                        {{--<div class="modal-header modalHeaderTransfer">--}}
                                            {{--<button value="{{$transfer->id}}"--}}
                                                    {{--class="close"--}}
                                                    {{--type="submit"--}}
                                                    {{--name="closeSubmitValidateUpdateModal">--}}
                                                {{--&times;--}}
                                            {{--</button>--}}
                                            {{--<h4 class="modal-title text-center">--}}
                                                {{--INFORMATION--}}
                                            {{--</h4>--}}
                                        {{--</div>--}}
                                        {{--<div class="modal-body center modalBodyTransfer">--}}
                                            {{--<p class="text-center">--}}
                                                {{--Here,--}}
                                                {{--CONFIRMED--}}
                                                {{--pallets--}}
                                                {{--number</p>--}}
                                            {{--@php($partsCreditAccount=explode('-',request()->session()->get('creditAccount')))--}}
                                            {{--@php($a=$partsCreditAccount[count($partsCreditAccount)-1])--}}
                                            {{--@php($b=$partsCreditAccount[count($partsCreditAccount)-2])--}}
                                            {{--@if(count(array_diff ($partsCreditAccount, [$a, $b]))==1)--}}
                                                {{--@php($creditAccountValidate=array_diff ($partsCreditAccount, [$a, $b])[0])--}}
                                            {{--@else--}}
                                                {{--@php($creditAccountValidate=implode(' - ', array_diff ($partsCreditAccount, [$a, $b])))--}}
                                            {{--@endif--}}

                                            {{--@php($partsDebitAccount=explode('-',request()->session()->get('debitAccount')))--}}
                                            {{--@php($aprim=$partsDebitAccount[count($partsDebitAccount)-1])--}}
                                            {{--@php($bprim=$partsDebitAccount[count($partsDebitAccount)-2])--}}
                                            {{--@if(count(array_diff ($partsDebitAccount, [$aprim, $bprim]))==1)--}}
                                                {{--@php($debitAccountValidate=array_diff ($partsDebitAccount, [$aprim, $bprim])[0])--}}
                                            {{--@else--}}
                                                {{--@php($debitAccountValidate=implode( ' - ', array_diff ($partsDebitAccount, [$aprim, $bprim])))--}}
                                            {{--@endif--}}
                                            {{--<table class="table table-hover table-bordered">--}}
                                                {{--<thead>--}}
                                                {{--<tr>--}}
                                                    {{--<th></th>--}}
                                                    {{--@if(Session::has('debitAccount'))--}}
                                                        {{--<th class="text-center">--}}
                                                            {{--DEBIT--}}
                                                        {{--</th>--}}
                                                    {{--@endif--}}
                                                    {{--@if(Session::has('creditAccount'))--}}
                                                        {{--<th class="text-center">--}}
                                                            {{--CREDIT--}}
                                                        {{--</th>--}}
                                                    {{--@endif--}}
                                                {{--</tr>--}}
                                                {{--</thead>--}}
                                                {{--<tbody>--}}
                                                {{--<tr>--}}
                                                    {{--<td></td>--}}
                                                    {{--@if(Session::has('debitAccount'))--}}
                                                        {{--<td class="text-center">{{$debitAccountValidate}}</td>--}}
                                                    {{--@endif--}}
                                                    {{--@if(Session::has('creditAccount'))--}}
                                                        {{--<td class="text-center">{{$creditAccountValidate}}</td>--}}
                                                    {{--@endif--}}
                                                {{--</tr>--}}
                                                {{--<tr>--}}
                                                    {{--<td class="text-center">--}}
                                                        {{--Actual--}}
                                                    {{--</td>--}}
                                                    {{--@if(Session::has('debitAccount'))--}}
                                                        {{--<td class="text-center">{{request()->session()->get('realPalletsNumberDebitAccount')}}</td>--}}
                                                    {{--@endif--}}
                                                    {{--@if(Session::has('creditAccount'))--}}
                                                        {{--<td class="text-center">{{request()->session()->get('realPalletsNumberCreditAccount')}}</td>--}}
                                                    {{--@endif--}}
                                                {{--</tr>--}}
                                                {{--<tr>--}}
                                                    {{--<td class="text-center">--}}
                                                        {{--New transfer--}}
                                                    {{--</td>--}}
                                                    {{--@if(Session::has('debitAccount'))--}}
                                                        {{--<td class="text-center">--}}
                                                            {{--- {{request()->session()->get('palletsNumber')}}</td>--}}
                                                    {{--@endif--}}
                                                    {{--@if(Session::has('creditAccount'))--}}
                                                        {{--<td class="text-center">--}}
                                                            {{--+ {{request()->session()->get('palletsNumber')}}</td>--}}
                                                    {{--@endif--}}
                                                {{--</tr>--}}
                                                {{--<tr>--}}
                                                    {{--<td class="text-center">--}}
                                                        {{--Total--}}
                                                    {{--</td>--}}
                                                    {{--@if(Session::has('debitAccount'))--}}
                                                        {{--<td class="text-center">--}}
                                                            {{--= {{request()->session()->get('realPalletsNumberDebitAccount') -request()->session()->get('palletsNumber')}}</td>--}}
                                                    {{--@endif--}}
                                                    {{--@if(Session::has('creditAccount'))--}}
                                                        {{--<td class="text-center">--}}
                                                            {{--= {{request()->session()->get('realPalletsNumberCreditAccount')+request()->session()->get('palletsNumber')}}</td>--}}
                                                    {{--@endif--}}
                                                {{--</tr>--}}
                                                {{--</tbody>--}}
                                            {{--</table>--}}
                                            {{--@foreach($errors as $error)--}}
                                                {{--@if($error->name=='Correcting_notCompleteNormal')--}}
                                                    {{--<div class="text-center">--}}
                                                        {{--<span class="glyphicon glyphicon-warning-sign text-danger"></span>--}}
                                                        {{--<span class="glyphicon glyphicon-warning-sign text-danger"></span>--}}
                                                        {{--<p class="text-danger">Pallets number does NOT CORRECT the--}}
                                                            {{--number expected in the loading order ({{$loading->anz}}--}}
                                                            {{--)</p>--}}
                                                    {{--</div>--}}
                                                {{--@endif--}}
                                                {{--@if($error->name=='SP-PS_notSameNumber')--}}
                                                    {{--<div class="text-center">--}}
                                                        {{--<span class="glyphicon glyphicon-warning-sign text-danger"></span>--}}
                                                        {{--<span class="glyphicon glyphicon-warning-sign text-danger"></span>--}}
                                                        {{--<p class="text-danger"> Pallets numbers are DIFFERENT for--}}
                                                            {{--both transfers </p>--}}
                                                    {{--</div>--}}
                                                {{--@endif--}}
                                                {{--@if($error->name=='DW-WD_notNumberLoadingOrder')--}}
                                                    {{--<div class="text-center">--}}
                                                        {{--<span class="glyphicon glyphicon-warning-sign text-danger"></span>--}}
                                                        {{--<span class="glyphicon glyphicon-warning-sign text-danger"></span>--}}
                                                        {{--<p class="text-danger">Pallets number does NOT MATCH the number--}}
                                                            {{--expected in the loading order ({{$loading->anz}}--}}
                                                            {{--)</p>--}}
                                                    {{--</div>--}}
                                                {{--@endif--}}
                                                {{--@if($error->name=='Donly-Wonly_notSameNumber')--}}
                                                    {{--<div class="text-center">--}}
                                                        {{--<span class="glyphicon glyphicon-warning-sign text-danger"></span>--}}
                                                        {{--<span class="glyphicon glyphicon-warning-sign text-danger"></span>--}}
                                                        {{--<p class="text-danger"> Sum of deposit only transfers does--}}
                                                            {{--NOT MATCH the sum of withdrawal only transfers </p>--}}
                                                    {{--</div>--}}
                                                {{--@endif--}}
                                            {{--@endforeach--}}
                                        {{--</div>--}}
                                        {{--<div class="modal-footer">--}}
                                            {{--<button type="submit"--}}
                                                    {{--@if(!empty($errors))--}}
                                                    {{--class="btn btn-danger btn-modal"--}}
                                                    {{--@else--}}
                                                    {{--class="btn btn-default btn-form btn-modal"--}}
                                                    {{--@endif--}}
                                                    {{--value="yes"--}}
                                                    {{--name="okSubmitUpdateValidateModal">--}}
                                                {{--Confirm--}}
                                            {{--</button>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--@endif--}}
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
                                          action="{{route('deletePalletstransfer', $transfer->id)}}" id="formDelete">
                                        <input type="hidden" name="_method"
                                               value="delete">
                                        <input type="hidden" name="actionFormDelete" id="actionFormDelete" />
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
                                                    name="delete" onclick="formDeleteSubmitBlock();">
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
@section('scriptEnd')
<script type="text/javascript" src="{{asset('js/addUpdatePalletstransfer.js')}}">
</script>
    @endsection