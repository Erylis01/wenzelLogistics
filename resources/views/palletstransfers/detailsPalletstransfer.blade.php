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
                {{--@if($state==false && $documents==false && $realPalletsNumber<>$palletsNumber)--}}
                {{--<div class="panel panel-danger">--}}
                {{--@elseif ($state==true && $documents==true && $realPalletsNumber==$palletsNumber)--}}
                {{--<div class="panel panel-general">--}}
                {{--@else--}}
                {{--<div class="panel panel-warning">--}}
                {{--@endif--}}
                <form class="form-horizontal text-right" role="form" method="POST"
                      action="{{route('updatePalletstransfer', $id)}}" enctype="multipart/form-data">
                    <input type="hidden"
                           name="_token"
                           value="{{ csrf_token() }}">
                    <div class="panel panel-general">
                        <div class="panel-heading">
                            <div class="col-lg-5 text-left">Details of the pallets transfer n° {{$id}}
                            </div>
                            <div class="col-lg-offset-8">
                                <button type="button"
                                        class=" btn btn-primary btn-form glyphicon glyphicon-remove"
                                        data-toggle="modal"
                                        data-target="#deletePalletstransfer_modal"
                                        value="{{$id}}"
                                        name="deletePalletstransferModal"
                                        ></button>
                            </div>
                        </div>

                        <div class="panel-body panel-body-general">
                            @if(Session::has('messageUpdatePalletstransfer'))
                                <div class="alert alert-success text-alert text-center">{{ Session::get('messageUpdatePalletstransfer') }}</div>
                                @elseif(Session::has('messageErrorUpload'))
                                    <div class="alert alert-danger text-alert text-center">{{ Session::get('messageErrorUpload') }}</div>
                            @elseif(Session::has('messageUpdateValidatePalletstransfer'))
                                <div class="alert alert-success text-alert text-center">{{ Session::get('messageUpdateValidatePalletstransfer') }}</div>
                            @endif
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
                                    @else
                                        <input id="palletsNumber" type="number" class="form-control"
                                               name="palletsNumber"
                                               value="{{$palletsNumber}}" placeholder="Nbr"
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
                                            title="Type" name="type">
                                        @foreach($listTypes as $t )
                                            @if(isset($type)&&$type=$t)
                                                <option selected>{{$t}}</option>
                                            @elseif(Illuminate\Support\Facades\Input::old('type') && $t==old('type'))
                                                <option selected>{{$t}}</option>
                                            @else
                                                <option>{{$t}}</option>
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
                                    <select class="selectpicker show-tick form-control" data-size="5"
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
                                    <select class="selectpicker show-tick form-control" data-size="5"
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
                                               multiple>
                                    </div>
                                    <!--button upload-->
                                    <div class="col-lg-2">
                                        <input type="submit"
                                               class="btn btn-primary btn-block btn-form"
                                               value="Upload"
                                               name="upload"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-10 col-lg-offset-1 text-left">
                                    @if(isset($filesNames))
                                        <ul>
                                            @foreach($filesNames as $nameF)
                                                <div>
                                                    <button type="submit"
                                                            name="deleteDocument"
                                                            class="btn-add glyphicon glyphicon-remove"
                                                            value="{{$nameF}}"></button>
                                                    <a href="../../storage/app/proofsPallets/documentsTransfer/{{$nameF}}"
                                                       class="link">{{$nameF}}</a>
                                                </div>
                                            @endforeach
                                        </ul>
                                    @endif
                                    </div>
                                </div>
                                <!--validation-->
                                <div class="form-group">
                                    @if(isset($filesNames)&&isset($palletsNumber)&&isset($creditAccount)&&isset($debitAccount))
                                        <div class="col-lg-2">
                                            <label for="state"
                                                   class="control-label">Validated ?
                                            </label>
                                        </div>
                                        <div class="col-lg-3">
                                            @if(isset($validateM) && $validateM==1||isset($validate) && $validate=='true')
                                                <label class="radio-inline"><input
                                                            type="radio"
                                                            name="validate"
                                                            value="true"
                                                            checked>Yes</label>
                                                <label class="radio-inline"><input
                                                            type="radio"
                                                            name="validate"
                                                            value="false">No</label>
                                            @elseif(isset($validateM) && $validateM==0||isset($validate) && $validate=='false')

                                                <label class="radio-inline"><input
                                                            type="radio"
                                                            name="validate"
                                                            value="true">Yes</label>
                                                <label class="radio-inline"><input
                                                            type="radio"
                                                            name="validate"
                                                            value="false"
                                                            checked>No</label>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            <div class="form-group">
                                <div class="col-lg-4 col-lg-offset-1">
                                    <input type="submit"
                                           class="btn btn-primary btn-block btn-form"
                                           value="Update"
                                           name="update" data-toggle="modal"
                                           data-target="#submitUpdate_modal">
                                </div>
                            </div>
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
                                                    <tr>
                                                        <td class="text-center">Last transfer</td>
                                                        <td class="text-center">
                                                            - {{request()->session()->get('actualPalletsNumber')}}</td>
                                                        <td class="text-center">
                                                            + {{request()->session()->get('actualPalletsNumber')}}</td>
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
                                                        <td class="text-center">New transfer</td>
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
                                                    <td class="text-center">{{request()->session()->get('creditAccount')}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">Actual</td>
                                                    <td class="text-center">{{request()->session()->get('thPalletsNumberCreditAccount')}}</td>
                                                    <td class="text-center">{{request()->session()->get('thPalletsNumberDebitAccount')}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">Last transfer</td>
                                                    <td class="text-center">
                                                         {{request()->session()->get('actualPalletsNumber')}}</td>
                                                    <td class="text-center">
                                                        </td>
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
                                                    <td class="text-center">{{request()->session()->get('creditAccount')}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">Actual</td>
                                                    <td class="text-center">{{request()->session()->get('thPalletsNumberCreditAccount')}}</td>
                                                    <td class="text-center">{{request()->session()->get('thPalletsNumberDebitAccount')}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">Last transfer</td>
                                                    <td class="text-center">
                                                        </td>
                                                    <td class="text-center">
                                                        + {{request()->session()->get('actualPalletsNumber')}}</td>
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
                                                name="okSubmitUpdateModal" data-toggle="modal"
                                                data-target="#submitUpdateValidate_modal">
                                            Confirm
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
        @endif
                <!-- Modal update -->
                    @if(isset($okSubmitUpdateModal) && $state=='Complete Validated')
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
                                                    <td class="text-center">New transfer</td>
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
                                                name="okSubmitUpdateValidateModal" data-toggle="modal"
                                                data-target="#submitUpdateValidate_modal">
                                            Confirm
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </form>

                <!-- Modal Delete -->
                <div class="modal fade" id="deletePalletstransfer_modal"
                     role="dialog">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close"
                                        data-dismiss="modal">&times;
                                </button>
                                <h4 class="modal-title text-center">Are you sure to
                                    delete the
                                    pallets
                                    transfer {{$id}} ?</h4>
                            </div>
                            <div class="modal-body center">
                                <form method="post"
                                      action="{{route('deletePalletstransfer', $id)}}">
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
                                        <button type="button"
                                                class="btn btn-success btn-modal"
                                                data-dismiss="modal">No
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button"
                                        class="btn btn-default btn-modal"
                                        data-dismiss="modal">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @endif
    </div>
@endsection