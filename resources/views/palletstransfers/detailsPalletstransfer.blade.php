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
                @if($state==false && $documents==false && $realPalletsNumber<>$palletsNumber)
                    <div class="panel panel-danger">
                        @elseif ($state==true && $documents==true && $realPalletsNumber==$palletsNumber)
                            <div class="panel panel-general">
                                @else
                                    <div class="panel panel-warning">
                                        @endif
                                        <div class="panel-heading">Details of the pallets transfer
                                            n° {{$id}}</div>

                                        <div class="panel-body panel-body-general">
                                            <form class="form-horizontal text-right" role="form" method="POST"
                                                  action="{{route('updatePalletstransfer', $id)}}">
                                                {{ csrf_field() }}
                                                <div class="form-group">
                                                    <!--date-->
                                                    <div class="col-lg-2">
                                                        <label for="date" class="control-label">Date :</label>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <input id="date" type="date" class="form-control"
                                                               name="date"
                                                               value="{{$date}}" placeholder="Date" required
                                                               autofocus>
                                                        @if ($errors->has('date'))
                                                            <span class="help-block">
                                        <strong>{{ $errors->first('date') }}</strong>
                                    </span>
                                                        @endif
                                                    </div>

                                                    <!--loading atrnr-->
                                                    <div class="col-lg-3">
                                                        <label for="loading_atrnr" class="control-label">Loading
                                                            atrnr :</label>
                                                    </div>
                                                    <div class="col-lg-5">
                                                        <input id="loading_atrnr" type="number" min="0" class="form-control"
                                                               name="loading_atrnr"
                                                               value="{{$loading_atrnr}}"
                                                               placeholder="Loading atrnr" required
                                                               autofocus>
                                                        @if ($errors->has('loading_atrnr'))
                                                            <span class="help-block">
                                        <strong>{{ $errors->first('loading_atrnr') }}</strong>
                                    </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <!--number of pallets-->
                                                    <div class="col-lg-2">
                                                        <label for="palletsNumber" class="control-label">Pallets
                                                            number
                                                            :</label>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <input id="palletsNumber" type="number"
                                                               class="form-control"
                                                               name="palletsNumber"
                                                               value="{{$palletsNumber}}"
                                                               placeholder="Pallets Number"
                                                               required autofocus>
                                                    </div>

                                                    <!--pallets account-->
                                                    <div class="col-lg-2 col-lg-offset-2">
                                                        <label for="warehousesAssociated" class="control-label">Pallets
                                                            account
                                                            :</label>
                                                    </div>
                                                    <div class="col-lg-5">

                                                        <select class="selectpicker show-tick form-control"
                                                                data-size="5"
                                                                data-live-search="true"
                                                                data-live-search-style="startsWith"
                                                                title="Pallets Account" name="palletsaccount_name"
                                                                required>
                                                            @foreach($listPalletsaccounts as $account )
                                                                @if($account->name==$palletsaccount_name)
                                                                    @php($option='selected')
                                                                    <option {{$option}}>{{$account->name}}</option>
                                                                @else
                                                                    <option>{{$account->name}}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>

                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="col-lg-2 col-lg-offset-7 text-left">
                                                        <a href="{{route('showAddPalletsaccount')}}"
                                                           class="link"><span
                                                                    class="glyphicon glyphicon-plus-sign"></span>
                                                            Add account</a>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-lg-4 col-lg-offset-1">
                                                        <input type="submit"
                                                               class="btn btn-primary btn-block btn-form"
                                                               value="Update"
                                                               name="updatePalletstransfer">
                                                    </div>

                                                    <div class="col-lg-3 col-lg-offset-3">
                                                        <button type="button"
                                                                class="btn btn-primary btn-block btn-form"
                                                                data-toggle="modal"
                                                                data-target="#deletePalletstransfer_modal">
                                                            Delete
                                                        </button>
                                                    </div>
                                                </div>
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
                                                                delete this
                                                                pallets
                                                                transfer ?</h4>
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
                                                                            name="deletePalletstransfer">
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
                                            @if(Session::has('messageUpdatePalletstransfer'))
                                                <div class="alert alert-success text-alert text-center">{{ Session::get('messageUpdatePalletstransfer') }}</div>
                                            @endif

                                            <div class="panel subpanel">
                                                <div class="panel-heading">
                                                    <a data-toggle="collapse" href="#Pancollapse">Verification
                                                        and Validation
                                                    </a>
                                                </div>
                                                <div id="Pancollapse" class="panel-collapse in collapse">
                                                    <div class="panel-body">
                                                        <!--form to edit pallets transfer-->
                                                        <form class="form-horizontal" role="form"
                                                              method="POST"
                                                              action="{{route('saveVerificationPalletstransfer', ['id' => $id, 'palletsaccount_name' => $palletsaccount_name])}}">
                                                            <input type="hidden" name="_token"
                                                                   value="{{ csrf_token() }}">

                                                            @if (Session::has('messageSaveVerificationPalletstransfer'))
                                                                <div class="alert alert-success text-alert text-center">{{ Session::get('messageSaveVerificationPalletstransfer') }}</div>
                                                            @endif

                                                        <!--pallets number-->
                                                            <div class="form-group">
                                                                <div class="col-lg-3 col-lg-offset-1 text-right">
                                                                    @if($realPalletsNumber<>$palletsNumber)
                                                                        <span class="text-danger glyphicon glyphicon-remove"></span>
                                                                    @else
                                                                        <span class="text-success glyphicon glyphicon-ok"></span>
                                                                    @endif
                                                                    <label for="realPalletsNumber"
                                                                           class="ontrol-label">Real
                                                                        pallets number
                                                                        :</label>
                                                                </div>
                                                                <div class="col-lg-2">
                                                                    <input id="realPalletsNumber" type="number"
                                                                           class="form-control"
                                                                           name="realPalletsNumber"
                                                                           value="{{ $realPalletsNumber }}"
                                                                           placeholder="Real pal. number"
                                                                           autofocus>

                                                                </div>
                                                                <label for="palletsNumber"
                                                                       class="col-lg-3 control-label">Theoric
                                                                    pallets number
                                                                    :</label>
                                                                <div class="col-lg-2">
                                                                    <input id="palletsNumber" type="number"
                                                                           class="form-control"
                                                                           name="palletsNumber"
                                                                           value="{{ $palletsNumber }}"
                                                                           placeholder="Theoric pallets number"
                                                                           readonly>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <!--documents-->
                                                                <div class=" col-lg-3 col-lg-offset-1 text-right">
                                                                    @if($documents==true)
                                                                        <span class="text-success glyphicon glyphicon-ok"></span>
                                                                    @else
                                                                        <span class="text-danger glyphicon glyphicon-remove"></span>
                                                                    @endif
                                                                    <label for="documents"
                                                                           class="control-label">Documents
                                                                        ?</label>
                                                                </div>
                                                                <div class="col-lg-2 text-center">
                                                                    @if($documents==true)
                                                                        <label class="radio-inline">
                                                                            <input id="documents" type="radio"
                                                                                   name="documents"
                                                                                   value="true"
                                                                                   checked><span>Yes</span></label>
                                                                        <label class="radio-inline">
                                                                            <input id="documents" type="radio"
                                                                                   name="documents"
                                                                                   value="false">No</label>
                                                                    @else
                                                                        <label class="radio-inline">
                                                                            <input id="documents" type="radio"
                                                                                   name="documents"
                                                                                   value="true">Yes</label>
                                                                        <label class="radio-inline">
                                                                            <input id="documents" type="radio"
                                                                                   name="documents"
                                                                                   value="false"
                                                                                   checked>No</label>
                                                                    @endif
                                                                </div>

                                                                <!--validation-->
                                                                <div class=" col-lg-3 text-right">
                                                                    @if($state==true)
                                                                        <span class="text-success glyphicon glyphicon-ok"></span>
                                                                    @else
                                                                        <span class="text-danger glyphicon glyphicon-remove"></span>
                                                                    @endif
                                                                    <label for="state"
                                                                           class="control-label">Transfer
                                                                        validated ?
                                                                    </label>
                                                                </div>
                                                                <div class="col-lg-2 text-center">
                                                                    @if($state==true)
                                                                        <label class="radio-inline">
                                                                            <input id="state" type="radio"
                                                                                   name="state"
                                                                                   value="true"
                                                                                   checked>Yes</label>
                                                                        <label class="radio-inline">
                                                                            <input id="state" type="radio"
                                                                                   name="state"
                                                                                   value="false">No</label>
                                                                    @else
                                                                        <label class="radio-inline">
                                                                            <input id="state" type="radio"
                                                                                   name="state"
                                                                                   value="true">Yes</label>
                                                                        <label class="radio-inline">
                                                                            <input id="state" type="radio"
                                                                                   name="state"
                                                                                   value="false"
                                                                                   checked>No</label>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            @if (Session::has('messageValidatedPalletstransfer'))
                                                                <div class="alert alert-success text-alert text-center">{{ Session::get('messageValidatedPalletstransfer') }}</div>
                                                            @endif
                                                        <!--reminders-->
                                                            <div class="form-group">
                                                                <label for="dateLastReminder"
                                                                       class="col-lg-4 control-label">Date last reminder
                                                                    :</label>
                                                                <div class="col-lg-2">
                                                                    <input id="dateLastReminder" type="date"
                                                                           class="form-control"
                                                                           name="dateLastReminder"
                                                                           value="{{ $dateLastReminder }}"
                                                                           autofocus readonly>
                                                                </div>
                                                                <label for="remindersNumber"
                                                                       class="col-lg-3 control-label">Reminders number
                                                                    :</label>
                                                                <div class="col-lg-2">
                                                                    <input id="remindersNumber" type="number" min="0"
                                                                           class="form-control"
                                                                           name="remindersNumber"
                                                                           value="{{ $remindersNumber }}"
                                                                           autofocus readonly>
                                                                </div>
                                                            </div>
                                                            @if (Session::has('messageValidatedPalletstransfer'))
                                                                <div class="alert alert-danger text-alert text-center">{{ Session::get('messageValidatedPalletstransfer') }}</div>
                                                            @endif

                                                            <div class="form-group">
                                                                <!--warehouse for reminder-->
                                                                <label for="reminderEmail"
                                                                       class="col-lg-4 control-label">Warehouse to send
                                                                    a reminder
                                                                    :</label>
                                                                <div class="col-lg-7">
                                                                    <select class="selectpicker show-tick form-control"
                                                                            data-size="5"
                                                                            data-live-search="true"
                                                                            data-live-search-style="startsWith"
                                                                            title="Warehouse reminder"
                                                                            name="reminderWarehouse">
                                                                        <option></option>
                                                                        @foreach($listWarehouses as $warehouses )
                                                                            @if($warehouses==$reminderWarehouse)
                                                                                @php($option='selected')
                                                                                <option {{$option}}>{{$warehouses}}</option>
                                                                            @else
                                                                                <option>{{$warehouses}}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            @if (Session::has('messageSuccessEmail'))
                                                                <div class="alert alert-success text-alert text-center">{{ Session::get('messageSuccessEmail') }}</div>
                                                            @elseif(Session::has('messageSuccessPhone'))
                                                                <div class="alert alert-success text-alert text-center">{{ Session::get('messageSuccessPhone') }}</div>
                                                            @elseif(Session::has('messageErrorEmailPhone'))
                                                                <div class="alert alert-warning text-alert text-center">{{ Session::get('messageErrorEmailPhone') }}</div>
                                                            @endif

                                                            <div class="form-group">
                                                                <div class="col-lg-offset-4 col-lg-5">
                                                                    <input type="submit"
                                                                           class="btn btn-primary btn-block btn-form"
                                                                           value="Save"
                                                                           name="saveVerification">
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                            </div>
                    </div>
            </div>

        @endif
    </div>
@endsection