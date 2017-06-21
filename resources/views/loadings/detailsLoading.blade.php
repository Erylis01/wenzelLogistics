@extends('layouts.default')

@section('title')
    Loading details
@endsection

@section('stylesheet')
    <link href="{{asset('css/loadings.css')}}" rel="stylesheet" type="text/css">
@endsection

@section('classLoadings')
    class="active"
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
    class="nonActive"
@endsection
@section('classProfile')
    nonActive
@endsection

@section('content')
    <div class="row">
        @if(Auth::guest())
            <h4>You need to login to see the content</h4>
        @else
            <div class="col-lg-14 container-details">
                <!--different panel style according to the state of the loading-->

                {{--@if($state=="OK")--}}
                {{--<div class="panel panel-general">--}}
                {{--@elseif($state=="almost OK")--}}
                {{--<div class="panel panel-warning">--}}
                {{--@elseif ($state=="not OK")--}}
                {{--<div class="panel panel-danger">--}}
                {{--@else--}}
                <div class="panel panel-default">
                    {{--@endif--}}
                    <div class="panel-heading">Details of the loading n°{{ $loading->atrnr }}
                    </div>
                    <div class="panel-body panel-body-general">

                        @if (Session::has('messageUpdateLoading'))
                            <div class="alert alert-success text-alert text-center">{{ Session::get('messageUpdateLoading') }}</div>
                    @endif
                    <!--subpanel 1 reading form suming up information from the table-->
                        <div class="panel subpanel">
                            <div class="panel-heading">
                                <a data-toggle="collapse" href="#Pan1collapse">Information</a>
                            </div>
                            @if (Session::has('openPanelInformation'))
                                <div id="Pan1collapse" class="panel-collapse in collapse">
                                    @else
                                        <div id="Pan1collapse" class="panel-collapse collapse">
                                            @endif
                                            <div class="panel-body">
                                                <form class="form-horizontal" role="form"
                                                      method="POST"
                                                      action="{{route('submitUpdateUpload', $loading->atrnr)}}">
                                                    <input type="hidden"
                                                           name="_token"
                                                           value="{{ csrf_token() }}">

                                                    <!-- subpanel general-->
                                                    <div class="panel subpanel">
                                                        <div class="panel-heading">
                                                            <a data-toggle="collapse"
                                                               href="#PanSub3collapse">General</a>
                                                        </div>
                                                        @if (Session::has('openPanelInformation'))
                                                            <div id="PanSub3collapse"
                                                                 class="panel-collapse in collapse">
                                                                @else
                                                                    <div id="PanSub3collapse"
                                                                         class="panel-collapse collapse">
                                                                        @endif
                                                                        <div class="panel-body">
                                                                            <div class="form-group">
                                                                                <!--referenz-->
                                                                                <div class="col-lg-6">
                                                                                    <div class="input-group details-loading">
                                                                                        <label for="referenz"
                                                                                               class="input-group-addon">Referenz
                                                                                            :</label>
                                                                                        <input type="text"
                                                                                               name="referenz"
                                                                                               class="form-control"
                                                                                               value="{{ $loading->referenz }}"
                                                                                               placeholder="referenz"
                                                                                               required>
                                                                                    </div>
                                                                                </div>
                                                                                <!--disp-->
                                                                                <div class="col-lg-2">
                                                                                    <div class="input-group details-loading">
                                                                                        <label for="disp"
                                                                                               class="input-group-addon">Disp
                                                                                            :</label>
                                                                                        <input type="text" name="disp"
                                                                                               class="form-control"
                                                                                               value="{{ $loading->disp }}"
                                                                                               placeholder="disp"
                                                                                               required>
                                                                                        @if ($errors->has('disp'))
                                                                                            <span class="help-block">
                                        <strong>{{ $errors->first('disp') }}</strong>
                                    </span>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>

                                                                                <!--pt change pt-->
                                                                                @if(Auth::user()->lastname=='Gundogan'&& Auth::user()->firstname=='Adrien' ||Auth::user()->username=='A' )
                                                                                    <div class="col-lg-2 col-lg-offset-2">
                                                                                        <div class="input-group details-loading">
                                                                                            <label for="pt"
                                                                                                   class="input-group-addon">PT
                                                                                                :</label>

                                                                                            <input type="text" readonly
                                                                                                   name="pt"
                                                                                                   class="form-control link"
                                                                                                   data-toggle="modal"
                                                                                                   data-target="#updatePT_modal"
                                                                                                   value="{{ $loading->pt }}">
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <div class="col-lg-12">
                                                                                    <!--auftraggeber-->
                                                                                    <div class="input-group details-loading">
                                                                                        <label for="auftraggeber"
                                                                                               class="input-group-addon">Auftraggeber
                                                                                            :</label> <input type="text"
                                                                                                             name="auftraggeber"
                                                                                                             class="form-control"
                                                                                                             value="{{ $loading->auftraggeber }}"
                                                                                                             placeholder="auftraggeber"
                                                                                                             required>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <!--subfrachter-->
                                                                                <div class="col-lg-8">
                                                                                    <div class="input-group details-loading">
                                                                                        <label for="subfrachter"
                                                                                               class="input-group-addon">Subfrachter
                                                                                            :</label>
                                                                                        <input type="text"
                                                                                               name="subfrachter"
                                                                                               class="form-control"
                                                                                               value="{{ $loading->subfrachter }}"
                                                                                               placeholder="subfrachter"
                                                                                               required>
                                                                                    </div>
                                                                                </div>
                                                                                <!--kennzeichen-->
                                                                                <div class="col-lg-4">
                                                                                    <div class="input-group details-loading">
                                                                                        <label for="kennzeichen"
                                                                                               class="input-group-addon">Kennzeichen
                                                                                            :</label>
                                                                                        <input type="text"
                                                                                               name="kennzeichen"
                                                                                               class="form-control"
                                                                                               value="{{ $loading->kennzeichen }}"
                                                                                               placeholder="kennzeichen">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <!--anz-->
                                                                                <div class="col-lg-2 details-loading">
                                                                                    <input type="number" name="anz"
                                                                                           class="form-control"
                                                                                           value="{{ $loading->anz }}"
                                                                                           placeholder="anz." min="0"
                                                                                           required
                                                                                           data-toggle="tooltip"
                                                                                           data-placement="top"
                                                                                           title="Anzahl">
                                                                                </div>
                                                                                <div class="col-lg-1 details-loading text-center">
                                                                                    -
                                                                                </div>
                                                                                <!--art-->
                                                                                <div class="col-lg-3 details-loading">
                                                                                    <input type="text" name="art"
                                                                                           class="form-control"
                                                                                           value="{{ $loading->art }}"
                                                                                           placeholder="art" required
                                                                                           data-toggle="tooltip"
                                                                                           data-placement="top"
                                                                                           title="Art">
                                                                                </div>
                                                                                <div class="col-lg-1 details-loading text-center">
                                                                                    -
                                                                                </div>
                                                                                <!--ware-->
                                                                                <div class="col-lg-5 details-loading">
                                                                                    <input type="text" name="ware"
                                                                                           class="form-control"
                                                                                           value="{{ $loading->ware }}"
                                                                                           placeholder="ware" required
                                                                                           data-toggle="tooltip"
                                                                                           data-placement="top"
                                                                                           title="Ware">
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                @if (Session::has('messageUpdatePTLoading'))
                                                                                    <div class="alert alert-warning text-alert text-center">{{ Session::get('messageUpdatePTLoading') }}
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        @if (Session::has('openPanelInformation'))
                                                                    </div>
                                                                    @else
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- subpanel loading-->
                                                    <div class="panel subpanel col-lg-6">
                                                        <div class="panel-heading">
                                                            <a class="col-lg-3 text-left" data-toggle="collapse"
                                                               href="#PanSub1collapse">Loading</a>
                                                            <div class="col-lg-5 col-lg-offset-1 text-right details-loading">
                                                                <input
                                                                        type="date" name="ladedatum"
                                                                        class="form-control  text-center"
                                                                        value="{{ $loading->ladedatum }}"
                                                                        placeholder="ladedatum" required>
                                                            </div>
                                                        </div>
                                                        @if (Session::has('openPanelInformation'))
                                                            <div id="PanSub1collapse"
                                                                 class="panel-collapse in collapse">
                                                                @else
                                                                    <div id="PanSub1collapse"
                                                                         class="panel-collapse collapse">
                                                                        @endif
                                                                        <div class="panel-body">
                                                                            <br>
                                                                            <div class="form-group">
                                                                                <!--beladestelle-->
                                                                                <div class="col-lg-12 details-loading">
                                                                                    <input type="text"
                                                                                           name="beladestelle"
                                                                                           class="form-control text-center"
                                                                                           value="{{ $loading->beladestelle }}"
                                                                                           placeholder="beladestelle"
                                                                                           required
                                                                                           data-toggle="tooltip"
                                                                                           data-placement="top"
                                                                                           title="Beladestelle">
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <!--ort plz land-->
                                                                                <div class="col-lg-3 details-loading">
                                                                                    <input type="number"
                                                                                           name="plzb"
                                                                                           class="form-control text-center"
                                                                                           value="{{ $loading->plzb }}"
                                                                                           placeholder="plz"
                                                                                           min="0"
                                                                                           required
                                                                                           data-toggle="tooltip"
                                                                                           data-placement="top"
                                                                                           title="Plz">
                                                                                </div>
                                                                                <div class="col-lg-1 details-loading">
                                                                                    -
                                                                                </div>
                                                                                <div class="col-lg-5 details-loading">
                                                                                    <input type="text"
                                                                                           name="ortb"
                                                                                           class="form-control text-center"
                                                                                           value="{{ $loading->ortb }}"
                                                                                           placeholder="ort"
                                                                                           required
                                                                                           data-toggle="tooltip"
                                                                                           data-placement="top"
                                                                                           title="Ort">
                                                                                </div>
                                                                                <div class="col-lg-1 details-loading">
                                                                                    -
                                                                                </div>
                                                                                <div class="col-lg-2 details-loading">
                                                                                    <input type="text"
                                                                                           name="landb"
                                                                                           class="form-control text-center"
                                                                                           value="{{ $loading->landb }}"
                                                                                           placeholder="land"
                                                                                           required
                                                                                           data-toggle="tooltip"
                                                                                           data-placement="top"
                                                                                           title="Land">
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <!--zusladestellen-->
                                                                                <div class="col-lg-12">
                                                                                    <div class="input-group details-loading">
                                                                                        <label for="zusladestellen"
                                                                                               class="input-group-addon">Zus.
                                                                                            Ladestellen
                                                                                            :</label>
                                                                                        <input type="text"
                                                                                               name="zusladestellen"
                                                                                               class="form-control"
                                                                                               value="{{ $loading->zusladestellen }}"
                                                                                               placeholder="zus ladestellen">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        @if (Session::has('openPanelInformation'))
                                                                    </div>
                                                                    @else
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- subpanel unloading-->
                                                    <div class="panel subpanel col-lg-6">
                                                        <div class="panel-heading">
                                                            <a class="col-lg-3 text-left"
                                                               data-toggle="collapse"
                                                               href="#PanSub2collapse">Offloading</a>
                                                            <div class="col-lg-5 col-lg-offset-1 text-right details-loading">
                                                                <input type="date" name="entladedatum"
                                                                       class="form-control"
                                                                       value="{{ $loading->entladedatum }}"
                                                                       placeholder="entladedatum"
                                                                       required>
                                                            </div>
                                                        </div>
                                                        @if (Session::has('openPanelInformation'))
                                                            <div id="PanSub2collapse"
                                                                 class="panel-collapse in collapse">
                                                                @else
                                                                    <div id="PanSub2collapse"
                                                                         class="panel-collapse collapse">
                                                                        @endif
                                                                        <div class="panel-body">
                                                                            <br>
                                                                            <div class="form-group">
                                                                                <!--entladestelle-->
                                                                                <div class="col-lg-12 details-loading">
                                                                                    <input type="text"
                                                                                           name="entladestelle"
                                                                                           class="form-control"
                                                                                           value="{{ $loading->entladestelle }}"
                                                                                           placeholder="entladestelle"
                                                                                           required
                                                                                           data-toggle="tooltip"
                                                                                           data-placement="top"
                                                                                           title="Entladestelle">
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <!--plz-->
                                                                                <div class="col-lg-3 details-loading">
                                                                                    <input type="number"
                                                                                           name="plze"
                                                                                           class="form-control"
                                                                                           value="{{ $loading->plze }}"
                                                                                           placeholder="plz"
                                                                                           min="0"
                                                                                           required
                                                                                           data-toggle="tooltip"
                                                                                           data-placement="top"
                                                                                           title="Plz">
                                                                                </div>
                                                                                <div class="col-lg-1 details-loading">
                                                                                    -
                                                                                </div>
                                                                                <!--ort-->
                                                                                <div class="col-lg-5 details-loading">
                                                                                    <input type="text"
                                                                                           name="orte"
                                                                                           class="form-control"
                                                                                           value="{{ $loading->orte }}"
                                                                                           placeholder="ort"
                                                                                           required
                                                                                           data-toggle="tooltip"
                                                                                           data-placement="top"
                                                                                           title="Ort">
                                                                                </div>
                                                                                <div class="col-lg-1 details-loading">
                                                                                    -
                                                                                </div>
                                                                                <!--land-->
                                                                                <div class="col-lg-2 details-loading">
                                                                                    <input type="text"
                                                                                           name="lande"
                                                                                           class="form-control"
                                                                                           value="{{ $loading->lande }}"
                                                                                           placeholder="land"
                                                                                           required
                                                                                           data-toggle="tooltip"
                                                                                           data-placement="top"
                                                                                           title="Land">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        @if (Session::has('openPanelInformation'))
                                                                    </div>
                                                                    @else
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- update-->
                                                    <div class="col-lg-4 col-lg-offset-4">
                                                        <input type="submit"
                                                               class="btn btn-primary btn-block btn-form"
                                                               value="Update"
                                                               name="update">
                                                    </div>
                                                </form>
                                                <!-- Modal update pt -->
                                                <div class="modal fade" id="updatePT_modal"
                                                     role="dialog">
                                                    <div class="modal-dialog modal-md">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button"
                                                                        class="close"
                                                                        data-dismiss="modal">
                                                                    &times;
                                                                </button>
                                                                <h4 class="modal-title">Why
                                                                    would you like
                                                                    to change the loading
                                                                    into a
                                                                    loading WITHOUT exchange
                                                                    pallets ?</h4>
                                                            </div>
                                                            <div class="modal-body center">
                                                                <form role="form"
                                                                      method="POST"
                                                                      action="">
                                                                    <input type="hidden"
                                                                           name="_token"
                                                                           value="{{ csrf_token() }}">
                                                                    <textarea
                                                                            class="form-control"
                                                                            rows="5"
                                                                            id="reasonUpdatePT"
                                                                            name="reasonUpdatePT"
                                                                            required
                                                                            autofocus>{{$loading->reasonUpdatePT}}</textarea>
                                                                    <button type="button"
                                                                            class="btn btn-success btn-modal"
                                                                            data-toggle="modal"
                                                                            data-target="#updateValidatePT_modal">
                                                                        Update
                                                                    </button>
                                                                    <!-- Modal update validate pt -->
                                                                    <div class="modal fade"
                                                                         id="updateValidatePT_modal"
                                                                         role="dialog">
                                                                        <div class="modal-dialog modal-sm">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <button type="button"
                                                                                            class="close"
                                                                                            data-dismiss="modal">
                                                                                        &times;
                                                                                    </button>
                                                                                    <h4 class="modal-title">
                                                                                        Are
                                                                                        you
                                                                                        sure
                                                                                        that
                                                                                        loading
                                                                                        is
                                                                                        WITHOUT
                                                                                        exchange
                                                                                        pallets
                                                                                        ?</h4>
                                                                                </div>
                                                                                <div class="modal-body center">
                                                                                    <h4>If
                                                                                        you
                                                                                        have
                                                                                        made
                                                                                        a
                                                                                        mistake
                                                                                        you
                                                                                        can
                                                                                        change
                                                                                        this
                                                                                        information
                                                                                        directly
                                                                                        on
                                                                                        the
                                                                                        database
                                                                                    </h4>
                                                                                    <br>
                                                                                    <form role="form"
                                                                                          method="POST"
                                                                                          action="{{ route('submitUpdateUpload', $loading->atrnr) }}">
                                                                                        <input type="hidden"
                                                                                               name="_token"
                                                                                               value="{{ csrf_token() }}">
                                                                                        <div class="col-lg-offset-3">
                                                                                            <input type="submit"
                                                                                                   class="btn btn-danger btn-modal"
                                                                                                   value="Yes"
                                                                                                   name="updateValidatePT">

                                                                                            <button type="button"
                                                                                                    class="btn btn-success btn-modal"
                                                                                                    data-dismiss="modal">
                                                                                                No
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
                                            @if (Session::has('openPanelInformation'))
                                        </div>
                                        @else
                                </div>
                            @endif
                        </div>

                        <!--subpanel 2 info about pallets transfer-->
                        <div class="panel subpanel">
                            <div class="panel-heading">
                                <a data-toggle="collapse" href="#Pan2collapse">Pallets location ?</a>
                                <span>

                                </span>
                            </div>
                            @if(isset($addTransferForm)||isset($addPalletstransfer))
                                <div id="Pan2collapse" class="panel-collapse in collapse">
                                    @else
                                        <div id="Pan2collapse" class="panel-collapse collapse">
                                            @endif
                                            <div class="panel-body">
                                                @if (Session::has('messageUpdateValidate'))
                                                    <div class="alert alert-success text-alert text-center">{{ Session::get('messageUpdateValidate') }}</div>
                                                @elseif(Session::has('messageSuccessSubmit'))
                                                    <div class="alert alert-success text-alert text-center">{{ Session::get('messageSuccessSubmit') }}</div>
                                                @elseif (Session::has('messageSuccessDeleteDocument'))
                                                    <div class="alert alert-success text-alert text-center">{{ Session::get('messageSuccessDeleteDocument') }}</div>
                                                @elseif(Session::has('messageErrorUpload'))
                                                    <div class="alert alert-danger text-alert text-center">{{ Session::get('messageErrorUpload') }}</div>
                                                @endif

                                                <form class="form-horizontal"
                                                      role="form"
                                                      method="POST"
                                                      action="{{route('submitUpdateUpload', $loading->atrnr)}}"
                                                      enctype="multipart/form-data">
                                                    <input type="hidden"
                                                           name="_token"
                                                           value="{{ csrf_token() }}">
                                                    <div class="row">
                                                        @if(Session::has('messageAddPalletstransfer'))
                                                            <div class="alert alert-success text-alert text-center">{{ Session::get('messageAddPalletstransfer') }}</div>
                                                        @endif
                                                    </div>
                                                    <div class="row">
                                                        <div class="from-group">
                                                            <div class="col-lg-2 text-right">
                                                                <a href="{{route('showAddPalletsaccount')}}"
                                                                   class="link"><span
                                                                            class="glyphicon glyphicon-plus-sign"></span>
                                                                    Add account</a>
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <label for="truckAccount" class="control-label">Truck
                                                                    account
                                                                    :</label>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <select class="selectpicker show-tick form-control"
                                                                        data-size="5"
                                                                        data-live-search="true"
                                                                        data-live-search-style="startsWith"
                                                                        title="Trcuk Account" name="truckAccount">
                                                                    @foreach($listPalletsaccountsCarrier as $carrierAccount )
                                                                        @if(Illuminate\Support\Facades\Input::old('truckAccount') && $carrierAccount->name==old('truckAccount'))
                                                                            <option selected>{{$carrierAccount->name}}</option>
                                                                        @elseif(isset($loading->truckAccount)&& $carrierAccount->name==$loading->truckAccount)
                                                                            <option selected>{{$carrierAccount->name}}</option>
                                                                        @elseif(!isset($loading->truckAccount)&&isset($palletsAccountFavoriteTruck)&& $carrierAccount->name==$palletsAccountFavoriteTruck)
                                                                            <option selected>{{$carrierAccount->name}}</option>
                                                                        @else
                                                                            <option>{{$carrierAccount->name}}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-lg-2  col-lg-offset-1">
                                                                <button type="submit"
                                                                        class="btn btn-add"
                                                                        value="addTransferForm"
                                                                        name="addTransferForm" data-toggle="collapse"
                                                                        data-target="#addForm">Add transfer
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div id="addForm" class="row collapse in">
                                                        @if(isset($addTransferForm)||isset($addPalletstransfer))
                                                            <div class="panel subpanel">
                                                                <div class="panel-body">
                                                                    <div class="form-group">
                                                                        <!--type-->
                                                                        <div class="col-lg-1 col-lg-offset-1">
                                                                            <label for="type" class="control-label">Type
                                                                                :</label>
                                                                        </div>
                                                                        <div class="col-lg-2">
                                                                            <select class="selectpicker show-tick form-control"
                                                                                    data-size="5"
                                                                                    data-live-search="true"
                                                                                    data-live-search-style="startsWith"
                                                                                    title="Type" name="type"
                                                                            >
                                                                                @foreach($listTypes as $t )
                                                                                    @if(Illuminate\Support\Facades\Input::old('type') && $t==old('type'))
                                                                                        <option selected>{{$t}}</option>
                                                                                    @elseif(isset($type)&&$t==$type)
                                                                                        <option selected>{{$t}}</option>
                                                                                    @else
                                                                                        <option>{{$t}}</option>
                                                                                    @endif
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <!--details-->
                                                                        <div class="col-lg-4">
                                                                            @if(isset($details))
                                                                                <textarea class="form-control" rows="1"
                                                                                          id="details"
                                                                                          placeholder="Details">{{$details}}</textarea>
                                                                            @else
                                                                                <textarea class="form-control" rows="1"
                                                                                          id="details"
                                                                                          placeholder="Details">{{old('details')}}</textarea>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <!--number of pallets-->
                                                                        <div class="col-lg-2">
                                                                            <label for="palletsNumber"
                                                                                   class="control-label">Pallets
                                                                                number
                                                                                :</label>
                                                                        </div>
                                                                        <div class="col-lg-1">
                                                                            @if(Illuminate\Support\Facades\Input::old('palletsNumber'))
                                                                                <input id="palletsNumber" type="number"
                                                                                       class="form-control"
                                                                                       name="palletsNumber"
                                                                                       value="{{ old('palletsNumber') }}"
                                                                                       placeholder="Pallets Number"
                                                                                       required autofocus>
                                                                            @elseif(isset($palletsNumber))
                                                                                <input id="palletsNumber" type="number"
                                                                                       class="form-control"
                                                                                       name="palletsNumber"
                                                                                       value="{{$palletsNumber}}"
                                                                                       placeholder="Nbr"
                                                                                       required autofocus>
                                                                            @else
                                                                                <input id="palletsNumber" type="number"
                                                                                       class="form-control"
                                                                                       name="palletsNumber"
                                                                                       value="0" placeholder="Nbr"
                                                                                       required autofocus>
                                                                            @endif
                                                                        </div>
                                                                        <!--date-->
                                                                        <div class="col-lg-1">
                                                                            <label for="date" class="control-label">Date
                                                                                :</label>
                                                                        </div>
                                                                        <div class="col-lg-2">
                                                                            @if(isset($date))
                                                                                <input id="date" type="date"
                                                                                       class="form-control"
                                                                                       name="date"
                                                                                       value="{{ $date }}"
                                                                                       placeholder="Date"
                                                                                       autofocus>
                                                                            @else
                                                                                <input id="date" type="date"
                                                                                       class="form-control"
                                                                                       name="date"
                                                                                       value="{{ old('date') }}"
                                                                                       placeholder="Date"
                                                                                       autofocus>
                                                                            @endif
                                                                        </div>
                                                                        <div class="col-lg-2 text-left">
                                                                            <label for="state"
                                                                                   class="control-label ">Multi-Transfers
                                                                                ?
                                                                            </label>
                                                                        </div>
                                                                        <div class="col-lg-2 text-left">
                                                                            @if(Illuminate\Support\Facades\Input::old('multiTransfer') && old('multiTransfer')=='true'||(isset($multiTransfer)&&$multiTransfer=='true'))
                                                                                <label class="radio-inline"><input
                                                                                            type="radio"
                                                                                            name="multiTransfer"
                                                                                            value="true"
                                                                                            checked>Yes</label>
                                                                                <label class="radio-inline"><input
                                                                                            type="radio"
                                                                                            name="multiTransfer"
                                                                                            value="false">No</label>
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
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <!--credit account-->
                                                                        <div class="col-lg-2">
                                                                            <label for="creditAccount"
                                                                                   class="control-label">Credit
                                                                                account
                                                                                :</label>
                                                                        </div>
                                                                        <div class="col-lg-4">
                                                                            <select class="selectpicker show-tick form-control"
                                                                                    data-size="10"
                                                                                    data-live-search="true"
                                                                                    data-live-search-style="startsWith"
                                                                                    title="Credit Account"
                                                                                    name="creditAccount"
                                                                                    required>
                                                                                @foreach($listPalletsAccounts as $palletsAccount )
                                                                                    @if(Illuminate\Support\Facades\Input::old('creditAccount') && $palletsAccount->name==old('creditAccount'))
                                                                                        <option selected>{{$palletsAccount->name}}</option>
                                                                                    @elseif(isset($creditAccount)&& $palletsAccount->name==$creditAccount)
                                                                                        <option selected>{{$palletsAccount->name}}</option>
                                                                                    @else
                                                                                        <option>{{$palletsAccount->name}}</option>
                                                                                    @endif
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <!--debit account-->
                                                                        <div class="col-lg-2">
                                                                            <label for="debitAccount"
                                                                                   class="control-label">Debit
                                                                                account
                                                                                :</label>
                                                                        </div>
                                                                        <div class="col-lg-4">
                                                                            <select class="selectpicker show-tick form-control"
                                                                                    data-size="10"
                                                                                    data-live-search="true"
                                                                                    data-live-search-style="startsWith"
                                                                                    title="Debit Account"
                                                                                    name="debitAccount"
                                                                                    required>
                                                                                @foreach($listPalletsAccounts as $palletsAccount )
                                                                                    @if(Illuminate\Support\Facades\Input::old('debitAccount') && $palletsAccount->name==old('debitAccount'))
                                                                                        <option selected>{{$palletsAccount->name}}</option>
                                                                                    @elseif(isset($debitAccount)&& $palletsAccount->name==$debitAccount)
                                                                                        <option selected>{{$palletsAccount->name}}</option>
                                                                                    @else
                                                                                        <option>{{$palletsAccount->name}}</option>
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
                                                                                   name="addPalletstransfer"
                                                                                   data-toggle="modal"
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
                                                                                                <td class="text-center">
                                                                                                    Actual
                                                                                                </td>
                                                                                                <td class="text-center">{{request()->session()->get('palletsNumberCreditAccount')}}</td>
                                                                                                <td class="text-center">{{request()->session()->get('palletsNumberDebitAccount')}}</td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td class="text-center">
                                                                                                    New
                                                                                                    transfer
                                                                                                </td>
                                                                                                <td class="text-center">
                                                                                                    + {{request()->session()->get('palletsNumber')}}</td>
                                                                                                <td class="text-center">
                                                                                                    - {{request()->session()->get('palletsNumber')}}</td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td class="text-center">
                                                                                                    Total
                                                                                                </td>
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
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </form>

                                                <br>
                                                <div class="row">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover table-bordered">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center">ID<br><a
                                                                            class="glyphicon glyphicon-chevron-up general-sorting"
                                                                            href="{{url('/allPalletstransfers?sortby=id&order=asc')}}"></a><a
                                                                            class="glyphicon glyphicon-chevron-down general-sorting"
                                                                            href="{{url('/allPalletstransfers?sortby=id&order=desc')}}"></a>
                                                                </th>
                                                                <th class="text-center">Credit account<br><a
                                                                            class="glyphicon glyphicon-chevron-up general-sorting"
                                                                            href="{{url('/allPalletstransfers?sortby=creditAccount&order=asc')}}"></a><a
                                                                            class="glyphicon glyphicon-chevron-down general-sorting"
                                                                            href="{{url('/allPalletstransfers?sortby=creditAccount&order=desc')}}"></a>
                                                                </th>
                                                                <th class="text-center">Debit account<br><a
                                                                            class="glyphicon glyphicon-chevron-up general-sorting"
                                                                            href="{{url('/allPalletstransfers?sortby=debitAccount&order=asc')}}"></a><a
                                                                            class="glyphicon glyphicon-chevron-down general-sorting"
                                                                            href="{{url('/allPalletstransfers?sortby=debitAccount&order=desc')}}"></a>
                                                                </th>
                                                                <th class="text-center">Pallets nbr<br><a
                                                                            class="glyphicon glyphicon-chevron-up general-sorting"
                                                                            href="{{url('/allPalletstransfers?sortby=palletsNumber&order=asc')}}"></a><a
                                                                            class="glyphicon glyphicon-chevron-down general-sorting"
                                                                            href="{{url('/allPalletstransfers?sortby=palletsNumber&order=desc')}}"></a>
                                                                </th>
                                                                <th class="text-center">Type<br><a
                                                                            class="glyphicon glyphicon-chevron-up general-sorting"
                                                                            href="{{url('/allPalletstransfers?sortby=type&order=asc')}}"></a><a
                                                                            class="glyphicon glyphicon-chevron-down general-sorting"
                                                                            href="{{url('/allPalletstransfers?sortby=type&order=desc')}}"></a>
                                                                </th>
                                                                <th class="text-center">State<br><a
                                                                            class="glyphicon glyphicon-chevron-up general-sorting"
                                                                            href="{{url('/allPalletstransfers?sortby=state&order=asc')}}"></a><a
                                                                            class="glyphicon glyphicon-chevron-down general-sorting"
                                                                            href="{{url('/allPalletstransfers?sortby=state&order=desc')}}"></a>
                                                                </th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($listPalletstransfers as $transfer)
                                                                @php($creditAccountID=\App\Palletsaccount::where('name', $transfer->creditAccount)->first())
                                                                @php($debitAccountID=\App\Palletsaccount::where('name', $transfer->debitAccount)->first())
                                                                <tr>
                                                                    <td class="text-center"><a class="link"
                                                                                               href="{{route('showDetailsPalletstransfer',$transfer->id)}}">{{$transfer->id}}</a>
                                                                    </td>
                                                                    <td class="text-center"><a class="link"
                                                                                               href="{{route('showDetailsPalletsaccount',$creditAccountID)}}">{{$transfer->creditAccount}}</a>
                                                                    </td>
                                                                    <td class="text-center"><a class="link"
                                                                                               href="{{route('showDetailsPalletsaccount',$debitAccountID)}}">{{$transfer->debitAccount}}</a>
                                                                    </td>
                                                                    <td class="text-center">{{$transfer->palletsNumber}}</td>
                                                                    <td class="text-center">{{$transfer->type}}</td>
                                                                    <td class="text-center">{{$transfer->state}}</td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            @if(isset($addTransferForm)||isset($addPalletstransfer))
                                        </div>
                                        @else
                                </div>
                            @endif
                        </div>


                    </div>
                </div>

            </div>

            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
        @endif
    </div>

    <script>
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection