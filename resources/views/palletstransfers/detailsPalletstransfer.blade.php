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
                @if($transfer->state=="In progress")
                    <div class="panel panelInprogress">
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
                                                                    <select id="type"
                                                                            class="selectpicker show-tick form-control"
                                                                            data-size="5"
                                                                            data-live-search="true"
                                                                            data-live-search-style="startsWith"
                                                                            title="Type" name="type" required>
                                                                        @foreach($listTypes as $t )
                                                                            @if(isset($transfer->type)&&$transfer->type==$t)
                                                                                <option selected>{{$t}}</option>
                                                                            @elseif(Illuminate\Support\Facades\Input::old('type') && $t==old('type'))
                                                                                <option selected>{{$t}}</option>
                                                                            @else
                                                                                <option>{{$t}}</option>
                                                                            @endif
                                                                        @endforeach
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
                                                                <label for="loading_atrnr" class="control-label">Atrnr
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
                                                                       class="control-label"><span>*</span> Date
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
                                                            <!--multitransfer-->
                                                            @if(isset($transfer->loading_atrnr))
                                                                <div>
                                                                    @else
                                                                        <div id="loading_atrnrLink">
                                                                            @endif
                                                                            <div class="col-lg-2">
                                                                                <label for="multiTransfer"
                                                                                       class="control-label">Multi-Transfers
                                                                                    ?
                                                                                </label>
                                                                            </div>
                                                                            <div class="col-lg-2 text-left">
                                                                                @if((isset($transfer->validate) && $transfer->validate==1 && (Illuminate\Support\Facades\Input::old('multiTransfer') && old('multiTransfer')=='true'||(isset($transfer->multiTransfer)&&$transfer->multiTransfer=='true'))))
                                                                                    <input type="text"
                                                                                           name="multiTransfer"
                                                                                           class="form-control"
                                                                                           value="Yes" readonly>
                                                                                @elseif(Illuminate\Support\Facades\Input::old('multiTransfer') && old('multiTransfer')=='true'||(isset($transfer->multiTransfer)&&$transfer->multiTransfer==1))
                                                                                    <label class="radio-inline"><input
                                                                                                type="radio"
                                                                                                name="multiTransfer"
                                                                                                value="true"
                                                                                                checked>Yes</label>
                                                                                    <label class="radio-inline"><input
                                                                                                type="radio"
                                                                                                name="multiTransfer"
                                                                                                value="false">No</label>
                                                                                @elseif((isset($transfer->validate) && $transfer->validate==1))
                                                                                    <input type="text"
                                                                                           name="multiTransfer"
                                                                                           class="form-control"
                                                                                           value="No" readonly>
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
                                                                            @if(isset($transfer->loading_atrnr))
                                                                        </div>
                                                                        @else
                                                                </div>
                                                            @endif
                                                        </div>
                                                        {{--<!--Link other transfer-->--}}
                                                        {{--@if(($transfer->multitransfer==1 && $transfer->type=='Withdrawal'))--}}
                                                        {{--<div class="col-lg-2 text-left">--}}
                                                        {{--<a href="{{route('showDetailsPalletstransferOther', $transfer->debitAccount)}}"--}}
                                                        {{--class="link"><span--}}
                                                        {{--class="glyphicon glyphicon-info-sign"></span>--}}
                                                        {{--See transfers associated</a>--}}
                                                        {{--</div>--}}
                                                        {{--@elseif(($transfer->multitransfer==1 && $transfer->type=='Deposit'))--}}
                                                        {{--<div class="col-lg-2 text-left">--}}
                                                        {{--<a href="{{route('showDetailsPalletstransferOther', $transfer->creditAccount)}}"--}}
                                                        {{--class="link"><span--}}
                                                        {{--class="glyphicon glyphicon-info-sign"></span>--}}
                                                        {{--See transfers associated</a>--}}
                                                        {{--</div>--}}
                                                        {{--@endif--}}

                                                        <div class="form-group">
                                                            <!--credit account-->
                                                            <div class="col-lg-2">
                                                                <label for="creditAccount"
                                                                       class="control-label"><span>*</span> Credit
                                                                    account
                                                                    :</label>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                @if(isset($transfer->validate) && $transfer->validate==1)
                                                                    <input type="text" name="creditAccount"
                                                                           class="form-control"
                                                                           value="{{$transfer->creditAccount}}"
                                                                           readonly>
                                                                @else
                                                                    <select class="selectpicker show-tick form-control"
                                                                            data-size="5"
                                                                            data-live-search="true"
                                                                            data-live-search-style="startsWith"
                                                                            title="Credit Account"
                                                                            name="creditAccount" id="creditAccount"
                                                                            required>
                                                                        @foreach($listNamesPalletsaccounts as $palletsAccount )
                                                                            @if(isset($transfer->creditAccount)&& $palletsAccount==$transfer->creditAccount)
                                                                                <option selected>{{$palletsAccount}}</option>
                                                                            @elseif(Illuminate\Support\Facades\Input::old('creditAccount') && $palletsAccount==old('creditAccount'))
                                                                                <option selected>{{$palletsAccount}}</option>
                                                                            @else
                                                                                <option>{{$palletsAccount}}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>
                                                                @endif

                                                            </div>
                                                            <!--debit account-->
                                                            <div class="col-lg-2">
                                                                <label for="debitAccount"
                                                                       class="control-label"><span>*</span> Debit
                                                                    account
                                                                    :</label>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                @if(isset($transfer->validate) && $transfer->validate==1)
                                                                    <input type="text" name="debitAccount"
                                                                           class="form-control"
                                                                           value="{{$transfer->debitAccount}}"
                                                                           readonly>
                                                                @else
                                                                    <select class="selectpicker show-tick form-control"
                                                                            data-size="5"
                                                                            data-live-search="true"
                                                                            data-live-search-style="startsWith"
                                                                            title="Debit Account"
                                                                            name="debitAccount" id="debitAccount"
                                                                            required>
                                                                        @foreach($listNamesPalletsaccounts as $palletsAccount )
                                                                            @if(isset($transfer->debitAccount)&& $palletsAccount==$transfer->debitAccount)
                                                                                <option selected>{{$palletsAccount}}</option>
                                                                            @elseif(Illuminate\Support\Facades\Input::old('debitAccount') && $palletsAccount==old('debitAccount'))
                                                                                <option selected>{{$palletsAccount}}</option>
                                                                            @else
                                                                                <option>{{$palletsAccount}}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <!--Link add pallets account-->
                                                        <div class="form-group">
                                                            <div class="col-lg-2 col-lg-offset-8 text-left">
                                                                <a href="{{route('showAddPalletsaccount')}}"
                                                                   class="link"><span
                                                                            class="glyphicon glyphicon-plus-sign"></span>
                                                                    Add account</a>
                                                            </div>
                                                        </div>
                                                        <!--documents proof upload-->
                                                        <div class="form-group text-left">
                                                            <div class="col-lg-2">
                                                                <label for="documentsTransfer">Proof
                                                                    documents
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
                                                                                    <a href="../../storage/app/proofsPallets/documentsTransfer/{{$transfer->id}}/{{$nameF}}"
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

                                                            @if(!empty($filesNames)&&isset($transfer->palletsNumber)&&isset($transfer->creditAccount)&&isset($transfer->debitAccount))
                                                                <div class="col-lg-2">
                                                                    <label for="state"
                                                                           class="control-label">Validated ?
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
                                                                                    <td class="text-center">{{request()->session()->get('thPalletsNumberCreditAccount')}}</td>
                                                                                    <td class="text-center">{{request()->session()->get('thPalletsNumberDebitAccount')}}</td>
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
                                                                                    <td class="text-center">
                                                                                        +{{request()->session()->get('palletsNumber')-request()->session()->get('actualPalletsNumber')}}</td>
                                                                                    <td class="text-center">
                                                                                        - {{request()->session()->get('palletsNumber')-request()->session()->get('actualPalletsNumber')}}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="text-center">Total</td>
                                                                                    <td class="text-center">
                                                                                        = {{request()->session()->get('thPalletsNumberCreditAccount')- request()->session()->get('actualPalletsNumber') +request()->session()->get('palletsNumber')}}</td>
                                                                                    <td class="text-center">
                                                                                        = {{request()->session()->get('thPalletsNumberDebitAccount')  + request()->session()->get('actualPalletsNumber') -request()->session()->get('palletsNumber')}}</td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        @elseif(request()->session()->get('actualCreditAccount')<>request()->session()->get('creditAccount') && request()->session()->get('actualDebitAccount')<>request()->session()->get('debitAccount'))
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
                                                                                    <td class="text-center">{{request()->session()->get('thPalletsNumberCreditAccount')}}</td>
                                                                                    <td class="text-center">{{request()->session()->get('thPalletsNumberDebitAccount')}}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="text-center">New
                                                                                        transfer
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        + {{request()->session()->get('palletsNumber')}}</td>
                                                                                    <td class="text-center">
                                                                                        - {{request()->session()->get('palletsNumber')}}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="text-center">Total</td>
                                                                                    <td class="text-center">
                                                                                        = {{request()->session()->get('thPalletsNumberCreditAccount')- request()->session()->get('actualPalletsNumber') +request()->session()->get('palletsNumber')}}</td>
                                                                                    <td class="text-center">
                                                                                        = {{request()->session()->get('thPalletsNumberDebitAccount')  + request()->session()->get('actualPalletsNumber') -request()->session()->get('palletsNumber')}}</td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        @elseif(request()->session()->get('actualCreditAccount')==request()->session()->get('creditAccount') && request()->session()->get('actualDebitAccount')<>request()->session()->get('debitAccount'))
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
                                                                                    <td class="text-center">{{request()->session()->get('thPalletsNumberCreditAccount')}}</td>
                                                                                    <td class="text-center">{{request()->session()->get('thPalletsNumberDebitAccount')}}</td>
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
                                                                                    <td class="text-center">
                                                                                        + {{request()->session()->get('palletsNumber')-request()->session()->get('actualPalletsNumber')}}</td>
                                                                                    <td class="text-center">
                                                                                        - {{request()->session()->get('palletsNumber')}}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="text-center">Total</td>
                                                                                    <td class="text-center">
                                                                                        = {{request()->session()->get('thPalletsNumberCreditAccount')- request()->session()->get('actualPalletsNumber') +request()->session()->get('palletsNumber')}}</td>
                                                                                    <td class="text-center">
                                                                                        = {{request()->session()->get('thPalletsNumberDebitAccount')   -request()->session()->get('palletsNumber')}}</td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        @elseif(request()->session()->get('actualCreditAccount')<>request()->session()->get('creditAccount') && request()->session()->get('actualDebitAccount')==request()->session()->get('debitAccount'))
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
                                                                                    <td class="text-center">{{request()->session()->get('thPalletsNumberCreditAccount')}}</td>
                                                                                    <td class="text-center">{{request()->session()->get('thPalletsNumberDebitAccount')}}</td>
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
                                                                                    <td class="text-center">
                                                                                        + {{request()->session()->get('palletsNumber')}}</td>
                                                                                    <td class="text-center">
                                                                                        - {{request()->session()->get('palletsNumber')-request()->session()->get('actualPalletsNumber')}}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="text-center">Total</td>
                                                                                    <td class="text-center">
                                                                                        = {{request()->session()->get('thPalletsNumberCreditAccount') +request()->session()->get('palletsNumber')}}</td>
                                                                                    <td class="text-center">
                                                                                        = {{request()->session()->get('thPalletsNumberDebitAccount')+ request()->session()->get('actualPalletsNumber') -request()->session()->get('palletsNumber')}}</td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        @endif
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit"
                                                                                class="btn btn-default btn-form btn-modal"
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
                                                                                <td class="text-center">{{request()->session()->get('realPalletsNumberCreditAccount')}}</td>
                                                                                <td class="text-center">{{request()->session()->get('realPalletsNumberDebitAccount')}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="text-center">New transfer
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    + {{request()->session()->get('palletsNumber')}}</td>
                                                                                <td class="text-center">
                                                                                    - {{request()->session()->get('palletsNumber')}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="text-center">Total</td>
                                                                                <td class="text-center">
                                                                                    = {{request()->session()->get('realPalletsNumberCreditAccount')+request()->session()->get('palletsNumber')}}</td>
                                                                                <td class="text-center">
                                                                                    = {{request()->session()->get('realPalletsNumberDebitAccount') -request()->session()->get('palletsNumber')}}</td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit"
                                                                                class="btn btn-default btn-form btn-modal"
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