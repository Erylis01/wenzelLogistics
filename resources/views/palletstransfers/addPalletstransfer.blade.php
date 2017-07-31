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
    <script src="{{asset('js/bootstrap3-typeahead.min.js')}}"></script>
    {{--<script src="{{asset('typeahead.js')}}" type="text-javascript"></script>--}}
    <script type="text/javascript" src="{{asset('js/addUpdatePalletstransfer.js')}}"></script>
@endsection

@section('content')
    <div class="row">
        @if(Auth::guest())
            <h4>You need to login to see the content</h4>
        @else
            <div class="col-lg-14">
                <div class="panel panel-general">
                    <div class="panel-heading"><span class="glyphicon glyphicon-plus-sign"></span> Pallets transfer
                    </div>
                    <div class="panel-body panel-body-general">
                        <form class="form-horizontal text-right" role="form" method="POST"
                              action="{{route('addPalletstransfer')}}" id="formAddPalletstransfer">
                            {{ csrf_field() }}
                            <input type="hidden" name="actionForm" id="actionForm"/>
                            <input type="hidden" name="originalPage" id="originalPage"
                                   @if(isset($originalPage))value="{{$originalPage}}" @endif/>

                            @if(Session::has('errorFields'))
                                <div class="form-group">
                                    <p class="alert alert-danger text-alert text-center">{{ Session::get('errorFields') }}</p>
                                </div>
                            @endif

                            <div class="form-group">
                                <!--type-->
                                <div class="col-lg-1">
                                    <label for="type" class="control-label">Type :</label>
                                </div>
                                <div class="col-lg-10 text-left">
                                    @if(Illuminate\Support\Facades\Input::old('type') || isset($type))
                                        <label class="radio-inline" data-toggle="tooltip" data-placement="top"
                                               title="Purchase of pallets from an account to other account">
                                            <input type="radio" name="type" value="Purchase-Sale"
                                                   @if(strcmp(old('type'),'Purchase')==0 || strcmp($type,'Purchase')==0) checked
                                                   @endif id="typePS" onchange="displayFieldsType(this);"
                                                   required/>Purchase</label>
                                        <label class="radio-inline" data-toggle="tooltip" data-placement="top"
                                               title="Sale of pallets from an account to another account">
                                            <input type="radio" name="type" value="Sale"
                                                   @if(strcmp(old('type'),'Sale')==0 || strcmp($type,'Sale')==0) checked
                                                   @endif id="typeSP" onchange="displayFieldsType(this);"/>Sale</label>
                                        <label class="radio-inline" data-toggle="tooltip" data-placement="top"
                                               title="Purchase of pallets from an account to an external account not in the database">
                                            <input type="radio" name="type" value="Purchase_Ext"
                                                   @if(strcmp(old('type'),'Purchase_Ext')==0 || strcmp($type,'Purchase_Ext')==0) checked
                                                   @endif id="typePext" onchange="displayFieldsType(this);"/>Purchase
                                            External</label>
                                        <label class="radio-inline" data-toggle="tooltip" data-placement="top"
                                               title="Sale of pallets from an account to an external account not in the database">
                                            <input type="radio" name="type" value="Sale_Ext"
                                                   @if(strcmp(old('type'),'Sale_Ext')==0 || strcmp($type,'Sale_Ext')==0) checked
                                                   @endif id="typeSext" onchange="displayFieldsType(this);"/>Sale
                                            External</label>
                                        <label class="radio-inline" data-toggle="tooltip" data-placement="top"
                                               title="When an account has debt of pallets toward other accounts and inversely">
                                            <input type="radio" name="type" value="Debt"
                                                   @if(strcmp(old('type'),'Debt')==0|| strcmp($type,'Debt')==0) checked
                                                   @endif id="typeDebt" onchange="displayFieldsType(this);"/>Debt</label>
                                        <label class="radio-inline" data-toggle="tooltip" data-placement="top"
                                               title="Other kind of transfer">
                                            <input type="radio" name="type" value="Other"
                                                   @if(strcmp(old('type'),'Other')==0 ||strcmp($type,'Other')==0) checked
                                                   @endif id="typeOther" onchange="displayFieldsType(this);"/>Other</label>
                                    @else
                                        <label class="radio-inline" data-toggle="tooltip" data-placement="top"
                                               title="Purchase of pallets from an account to other account">
                                            <input type="radio" name="type" value="Purchase" checked id="typePS"
                                                   onchange="displayFieldsType(this);"
                                                   required/>Purchase</label>
                                        <label class="radio-inline" data-toggle="tooltip" data-placement="top"
                                               title="Sale of pallets from an account to another account">
                                            <input type="radio" name="type" value="Sale" id="typeSP"
                                                   onchange="displayFieldsType(this);"/>Sale</label>
                                        <label class="radio-inline" data-toggle="tooltip" data-placement="top"
                                               title="Purchase of pallets from an account to an external account not in the database">
                                            <input type="radio" name="type" value="Purchase_Ext" id="typePext"
                                                   onchange="displayFieldsType(this);"/>Purchase
                                            External</label>
                                        <label class="radio-inline" data-toggle="tooltip" data-placement="top"
                                               title="Sale of pallets from an account to an external account not in the database">
                                            <input type="radio" name="type" value="Sale_Ext" id="typeSext"
                                                   onchange="displayFieldsType(this);"/>Sale External</label>
                                        <label class="radio-inline" data-toggle="tooltip" data-placement="top"
                                               title="When an account has debt of pallets toward other accounts and inversely">
                                            <input type="radio" name="type" value="Debt" id="typeDebt"
                                                   onchange="displayFieldsType(this);"/>Debt</label>
                                        <label class="radio-inline" data-toggle="tooltip" data-placement="top"
                                               title="Other kind of transfer">
                                            <input type="radio" name="type" value="Other" id="typeOther"
                                                   onchange="displayFieldsType(this);"/>Other</label>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <!--details-->
                                <div class="col-lg-8">
                                    @if(isset($details))
                                        <textarea class="form-control" rows="1" id="details"
                                                  placeholder="Details (broken pallets, gift, receipt...)"
                                                  data-toggle="tooltip" data-placement="top" title="details"
                                                  name="details">{{$details}}</textarea>
                                    @else
                                        <textarea class="form-control" rows="1" id="details"
                                                  placeholder="Details (broken pallets, gift, receipt...)"
                                                  data-toggle="tooltip" data-placement="top" title="details"
                                                  name="details">{{old('details')}}</textarea>
                                    @endif
                                </div>
                                <!--date-->
                                <div class="col-lg-2">
                                    <input id="date" type="date" class="form-control" name="date" value="{{ $date }}"
                                           autofocus data-toggle="tooltip" data-placement="top" title="date"/>
                                </div>
                                <!--show add pallets account-->
                                <div class="col-lg-2 text-center">
                                    <a href="{{route('showAddPalletsaccount', ['originalPage'=>'addPalletstransfer'])}}"
                                       class="link"><span class="glyphicon glyphicon-plus-sign"></span> Account</a>
                                </div>
                            </div>
                            {{--<!--atrnr-->--}}
                            {{--<div class="col-lg-1 text-left">--}}
                            {{--<label for="loading_atrnr"--}}
                            {{--class="control-label">--}}
                            {{--@if(isset($type)&&($type=='Deposit-Withdrawal'||$type=='Withdrawal-Deposit'||$type=='Withdrawal_Only'||$type=='Deposit_Only'))--}}
                            {{--<span id="atrnr" style="display:inline-block">*</span>--}}
                            {{--@else<span id="atrnr" style="display:none">*</span>@endif--}}
                            {{--Atrnr :</label>--}}
                            {{--</div>--}}
                            {{--<div class="col-lg-2">--}}
                            {{--<select class="selectpicker show-tick form-control" data-size="5"--}}
                            {{--data-live-search="true" data-live-search-style="startsWith"--}}
                            {{--title="Loading_atrnr" name="loading_atrnr" id="loading_atrnrSelect"--}}
                            {{--onchange="displayFieldsAtrnr(this);">--}}
                            {{--<option value="">No loading</option>--}}
                            {{--@foreach($listAtrnr as $atrnr )--}}
                            {{--@if((Illuminate\Support\Facades\Input::old('loading_atrnr') && $atrnr==old('loading_atrnr'))||(isset($loading_atrnr)&&$atrnr==$loading_atrnr))--}}
                            {{--<option selected>{{$atrnr}}</option>--}}
                            {{--@else--}}
                            {{--<option>{{$atrnr}}</option>--}}
                            {{--@endif--}}
                            {{--@endforeach--}}
                            {{--</select>--}}
                            {{--</div>--}}
                            <br>
                            <div class="form-group">
                                <!--number of pallets-->
                                <div class="col-lg-2">
                                    <div class="input-group requiredField">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default btn-number" data-type="minus"
                                                    data-field="palletsNumber">
                                                <span class="glyphicon glyphicon-minus"></span>
                                            </button>
                                        </span>
                                        <input id="palletsNumber" type="number" name="palletsNumber"
                                               class="form-control input-number text-center"
                                               @if(isset($palletsNumber)) value="{{$palletsNumber}}"
                                               @elseif(Illuminate\Support\Facades\Input::old('palletsNumber')) value="{{ old('palletsNumber') }}"
                                               @else value="0" @endif placeholder="Nbr" min="0" max="999999" autofocus
                                               required onchange="updateFieldsNormal();">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default btn-number" data-type="plus"
                                                    data-field="palletsNumber">
                                                <span class="glyphicon glyphicon-plus"></span>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-1 text-center" id="pallets">
                                    <label for="palletsNumber" class="control-label">pallets</label>
                                </div>
                                <!-- debit account -->
                                <div class="col-lg-1 text-center" id="debitAccountLegend">
                                    <label for="debitAccount" class="control-label">from</label>
                                </div>
                                <div class="col-lg-1 text-center">
                                    <input type="text" id="input-debitAccount" class="form-control" autocomplete="off"/>
                                </div>
                                <div class="col-lg-3" id="debitAccount"
                                     @if(isset($type) && ($type=='Sale_Ext')) style="display: none"
                                     @else style="display:block;" @endif >
                                    {{--data-live-search="true" data-live-search-style="startsWith"--}}
                                    {{--<select class="selectpicker show-tick form-control" data-size="10"--}}
                                            {{--title="Account (pallets giver)" name="debitAccount" id="select-debitAccount"--}}
                                            {{--data-style="requiredField"--}}
                                            {{--autocomplete="off"--}}
                                            {{--data-live-search="true" data-live-search-style="startsWith">--}}
                                        {{--@foreach($listPalletsAccounts as $palletsAccount )--}}
                                            {{--@if((Illuminate\Support\Facades\Input::old('debitAccount') && (strpos(old('debitAccount'), '-') == 7 && explode('-', old('debitAccount'))[0] == 'account') && ($palletsAccount->id==explode('-', old('debitAccount'))[1]))||(isset($debitAccount)  && (strpos($debitAccount, '-') == 7 && explode('-', $debitAccount)[0] == 'account') && ($palletsAccount->id==explode('-', $debitAccount)[1])))--}}
                                                {{--<option value="account-{{$palletsAccount->id}}"--}}
                                                        {{--selected>{{$palletsAccount->nickname}}</option>--}}
                                            {{--@else--}}
                                                {{--<option value="account-{{$palletsAccount->id}}">{{$palletsAccount->nickname}}</option>--}}
                                            {{--@endif--}}
                                        {{--@endforeach--}}
                                        {{--@foreach($listTrucksAccounts as $trucksAccount )--}}
                                            {{--@if((Illuminate\Support\Facades\Input::old('debitAccount') && (strpos(old('debitAccount'), '-') == 5 && explode('-', old('debitAccount'))[0] == 'truck') && ($trucksAccount->id==explode('-', old('debitAccount'))[1]))|| (isset($debitAccount) &&(strpos($debitAccount, '-') == 5 && explode('-', $debitAccount)[0] == 'truck') && ($trucksAccount->id==explode('-', $debitAccount)[1])))--}}
                                                {{--<option value="truck-{{$trucksAccount->id}}"--}}
                                                        {{--selected>{{$trucksAccount->name}}--}}
                                                    {{--- {{$trucksAccount->licensePlate}}</option>--}}
                                            {{--@else--}}
                                                {{--<option value="truck-{{$trucksAccount->id}}">{{$trucksAccount->name}}--}}
                                                    {{--- {{$trucksAccount->licensePlate}}</option>--}}
                                            {{--@endif--}}
                                        {{--@endforeach--}}
                                    {{--</select>--}}
                                </div>
                                <!-- credit account -->
                                <div class="col-lg-1 text-center" id="creditAccountLegend">
                                    <label for="creditAccount" class="control-label">to</label>
                                </div>
                                <div class="col-lg-4" id="creditAccount"
                                     @if(isset($type) && $type =='Sale_Ext') style="display: none;"
                                     @else style="display:block;" @endif>
                                    <select class="selectpicker show-tick form-control" data-size="10"
                                            data-live-search="true" data-live-search-style="startsWith"
                                            id="select-creditAccount" title="Account (pallets taker)"
                                            name="creditAccount" data-style="requiredField">
                                        @foreach($listPalletsAccounts as $palletsAccount )
                                            @if((Illuminate\Support\Facades\Input::old('creditAccount') && (strpos(old('creditAccount'), '-') == 7 && explode('-', old('creditAccount'))[0] == 'account') && ($palletsAccount->id==explode('-', old('creditAccount'))[1]))||(isset($creditAccount) && ((isset($type) && $type <>'Sale_Ext')||!isset($type)) && (strpos($creditAccount, '-') == 7 && explode('-', $creditAccount)[0] == 'account') && ($palletsAccount->id==explode('-', $creditAccount)[1])))
                                                <option value="account-{{$palletsAccount->id}}"
                                                        selected>{{$palletsAccount->nickname}}</option>
                                            @else
                                                <option value="account-{{$palletsAccount->id}}">{{$palletsAccount->nickname}}</option>
                                            @endif
                                        @endforeach
                                        @foreach($listTrucksAccounts as $trucksAccount )
                                            @if((Illuminate\Support\Facades\Input::old('creditAccount') && (strpos(old('creditAccount'), '-') == 5 && explode('-', old('creditAccount'))[0] == 'truck') && ($trucksAccount->id==explode('-', old('creditAccount'))[1]))|| (isset($creditAccount)&& ((isset($type) && $type <>'Sale_Ext')||!isset($type)) && (strpos($creditAccount, '-') == 5 && explode('-', $creditAccount)[0] == 'truck') && ($trucksAccount->id==explode('-', $creditAccount)[1])))
                                                <option value="truck-{{$trucksAccount->id}}"
                                                        selected>{{$trucksAccount->name}}
                                                    - {{$trucksAccount->licensePlate}}</option>
                                            @else
                                                <option value="truck-{{$trucksAccount->id}}">{{$trucksAccount->name}}
                                                    - {{$trucksAccount->licensePlate}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="form-group">
                                <div class="col-lg-4 col-lg-offset-4">
                                    <button type="submit" class="btn btn-primary btn-block btn-form"
                                            value="addPalletstransfer" id="addPalletstransfer" name="addPalletstransfer"
                                            data-toggle="modal"
                                            data-target="#submitAdd_modal" onclick="formAddSubmitBlock(this);">Add
                                    </button>
                                </div>
                            </div>
                            <!-- Modal submit -->
                            @if(isset($actionForm) && $actionForm=='addPalletstransfer')
                                <div class="modal show" id="submitAdd_modal" role="dialog">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header modalHeaderTransfer">
                                                <button type="submit" class="close" value="closeSubmitAddModal"
                                                        name="closeSubmitAddModal" id="closeSubmitAddModal"
                                                        onclick="formAddSubmitBlock(this);">
                                                    &times;
                                                </button>
                                                <h4 class="modal-title text-center">TRANSFERS RECAP</h4>
                                            </div>
                                            <div class="modal-body center modalBodyTransfer">
                                                <p class="text-center"><strong>Date :</strong> {{$date}}</p>
                                                <p class="text-center"><strong>Type :</strong> {{$type}} </p>
                                                @if(isset($details))
                                                    <p class="text-center"><strong>Details :</strong> {{$details}}</p>
                                                @endif
                                                <table class="table table-hover table-bordered">
                                                    <thead>
                                                    <tr>
                                                        @if(Session::has('debitAccountModal'))
                                                            <th class="text-center">From</th>
                                                        @endif
                                                        @if(Session::has('creditAccountModal'))
                                                            <th class="text-center">To</th>
                                                        @endif
                                                        <th class="text-center">Nbr</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        @if(Session::has('debitAccountModal'))
                                                            <td class="text-center">{{request()->session()->get('debitAccountModal')}}</td>
                                                        @endif
                                                        @if(Session::has('creditAccountModal'))
                                                            <td class="text-center">{{request()->session()->get('creditAccountModal')}}</td>
                                                        @endif
                                                        <td class="text-center">{{$palletsNumber}}</td>
                                                    </tr>
                                                    </tbody>
                                                </table>

                                                {{--@if(($type=='Deposit-Withdrawal' || $type=='Withdrawal-Deposit'|| $type=='Purchase')&&(Session::has('creditAccount2')&&Session::has('debitAccount2')&&Session::has('palletsNumber2')&& (request()->session()->get('palletsNumber2')<>request()->session()->get('palletsNumber'))))--}}
                                                {{--<div class="text-center">--}}
                                                {{--<span class="glyphicon glyphicon-warning-sign text-danger"></span>--}}
                                                {{--<span class="glyphicon glyphicon-warning-sign text-danger"></span><p--}}
                                                {{--class="text-danger"> Pallets number for transfer 1 does NOT MATCH Pallets number for transfer 2 </p>--}}
                                                {{--</div>--}}
                                                {{--@endif--}}
                                                {{--@if(($type=='Deposit-Withdrawal' || $type=='Withdrawal-Deposit' )&&((Session::has('palletsNumber')&&(request()->session()->get('palletsNumber')<>$loading->anz))||(Session::has('palletsNumber2')&&(request()->session()->get('palletsNumber2')<>$loading->anz))))--}}
                                                {{--<div class="text-center">--}}
                                                {{--<span class="glyphicon glyphicon-warning-sign text-danger"></span>--}}
                                                {{--<span class="glyphicon glyphicon-warning-sign text-danger"></span>--}}
                                                {{--<p class="text-danger">Pallets number does NOT MATCH the number expected in the loading order ({{$loading->anz}}--}}
                                                {{--)</p>--}}
                                                {{--</div>--}}
                                                {{--@endif--}}
                                                {{--@if(Session::has('sumTransfersDepositOnly') && Session::has('sumTransfersWithdrawalOnly') && request()->session()->get('sumTransfersDepositOnly')<>request()->session()->get('sumTransfersWithdrawalOnly') )--}}
                                                {{--<div class="text-center">--}}
                                                {{--<span class="glyphicon glyphicon-warning-sign text-danger"></span>--}}
                                                {{--<span class="glyphicon glyphicon-warning-sign text-danger"></span>--}}
                                                {{--<p class="text-danger"> Sum of deposit only transfers does NOT MATCH the sum of withdrawal only transfers </p>--}}
                                                {{--</div>--}}
                                                {{--@endif--}}
                                            </div>
                                            <div class="modal-footer">
                                                <div class="text-center">
                                                    <div class="col-lg-7">
                                                        <h5>(Confirm will only affect PLANNED pallets number)</h5>
                                                    </div>
                                                    <div class="col-lg-4 col-lg-offset-1">
                                                        <button type="submit" class="btn btn-block btn-form btn-modal"
                                                                value="okSubmitAddModal" name="okSubmitAddModal"
                                                                id="okSubmitAddModal"
                                                                onclick="formAddSubmitBlock(this);">
                                                            Confirm
                                                        </button>
                                                    </div>
                                                </div>
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
@section('scriptEnd')
    <script type="text/javascript" src="{{asset('js/addUpdatePalletstransfer.js')}}">
    </script>
@endsection