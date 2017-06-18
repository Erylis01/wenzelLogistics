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
                            </div>
                            @if (Session::has('openPanelLoading')||Session::has('openPanelOffloading')||Session::has('openPanelTruck'))
                                <div id="Pan2collapse" class="panel-collapse in collapse">
                                    @else
                                        <div id="Pan2collapse" class="panel-collapse collapse">
                                            @endif
                                            <div class="panel-body">
                                                @if (Session::has('messageErrorSubmit'))
                                                    <div class="alert alert-danger text-alert text-center">{{ Session::get('messageErrorSubmit') }}</div>
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

                                                    <!-- truck panel-->
                                                    {{--<div class="panel subpanel">--}}
                                                        {{--<div class="panel-heading">--}}
                                                            {{--<a class="col-lg-3 text-left" data-toggle="collapse"--}}
                                                               {{--href="#Pan2Sub3collapse">Truck</a>--}}
                                                            {{--<span class="col-lg-offset-2">{{$anz-$totalPalletsLoadingPlace+ $totalPalletsOffloadingPlace}} / 25</span>--}}
                                                        {{--</div>--}}
                                                        {{--@if (Session::has('openPanelTruck'))--}}
                                                            {{--<div id="Pan2Sub3collapse"--}}
                                                                 {{--class="panel-collapse in collapse">--}}
                                                                {{--@else--}}
                                                                    {{--<div id="Pan2Sub3collapse"--}}
                                                                         {{--class="panel-collapse collapse">--}}
                                                                        {{--@endif--}}
                                                                        {{--<div class="panel-body">--}}
                                                                            {{--<div class="panel subpanel">--}}
                                                                                {{--<div class="panel-body">--}}
                                                                                    {{--<!--documents proof upload-->--}}
                                                                                    {{--<div class="form-group text-center">--}}
                                                                                        {{--<label for="documentsTruck">Do--}}
                                                                                            {{--you--}}
                                                                                            {{--have proof--}}
                                                                                            {{--documents (CMR/Exchange--}}
                                                                                            {{--bill/Pallets bill)--}}
                                                                                            {{--?</label>--}}
                                                                                    {{--</div>--}}
                                                                                    {{--<div class="form-group">--}}
                                                                                        {{--<div class="col-lg-offset-1 col-lg-4">--}}
                                                                                            {{--<input type="file"--}}
                                                                                                   {{--name="documentsTruck[]"--}}
                                                                                                   {{--multiple>--}}
                                                                                        {{--</div>--}}
                                                                                        {{--<!--button upload-->--}}
                                                                                        {{--<div class="col-lg-4 col-lg-offset-2">--}}
                                                                                            {{--<input type="submit"--}}
                                                                                                   {{--class="btn btn-primary btn-block btn-form"--}}
                                                                                                   {{--value="Upload"--}}
                                                                                                   {{--name="uploadTruck"/>--}}
                                                                                        {{--</div>--}}
                                                                                    {{--</div>--}}
                                                                                    {{--<div class="form-group">--}}
                                                                                        {{--@if(isset($filesNamesTruck))--}}
                                                                                            {{--<ul class="col-lg-offset-1">--}}
                                                                                                {{--@foreach($filesNamesTruck as $name)--}}
                                                                                                    {{--<div>--}}
                                                                                                        {{--<button type="submit"--}}
                                                                                                                {{--name="deleteDocument"--}}
                                                                                                                {{--class="btn-add glyphicon glyphicon-remove"--}}
                                                                                                                {{--value="{{$name}}"></button>--}}
                                                                                                        {{--<a href="../../storage/app/proofsPallets/{{$atrnr}}/documentsTruck/{{$name}}"--}}
                                                                                                           {{--class="link">{{$name}}</a>--}}
                                                                                                    {{--</div>--}}
                                                                                                {{--@endforeach--}}
                                                                                            {{--</ul>--}}
                                                                                        {{--@endif--}}
                                                                                    {{--</div>--}}
                                                                                {{--</div>--}}
                                                                            {{--</div>--}}
                                                                            {{--<div class="panel subpanel">--}}
                                                                                {{--<div class="panel-body">--}}
                                                                                    {{--<!--initial pallets number truck-->--}}
                                                                                    {{--<div class="form-group">--}}
                                                                                        {{--<div class="col-lg-7 ">--}}
                                                                                            {{--<table class="table table-hover table-bordered">--}}
                                                                                                {{--<thead>--}}
                                                                                                {{--<tr>--}}
                                                                                                    {{--<th class="text-center">Initial - Offloaded</th>--}}
                                                                                                    {{--<th class="text-center">Loaded - Initial</th>--}}
                                                                                                    {{--<th class="text-center">Initial - Offloaded + Loaded</th>--}}
                                                                                                {{--</tr>--}}
                                                                                                {{--</thead>--}}
                                                                                                {{--<tbody>--}}
                                                                                                {{--<tr>--}}
                                                                                                    {{--<td class="text-center">{{$anz - $totalPalletsOffloadingPlace}} / 0</td>--}}
                                                                                                    {{--<td class="text-center">{{$totalPalletsLoadingPlace - $anz}} / 0</td>--}}
                                                                                                    {{--<td class="text-center">{{$anz - $totalPalletsOffloadingPlace + $totalPalletsLoadingPlace}} / 0</td>--}}
                                                                                                {{--</tr>--}}
                                                                                                {{--</tbody>--}}
                                                                                            {{--</table>--}}
                                                                                        {{--</div>--}}
                                                                                        {{--@if(isset($anz))--}}
                                                                                            {{--<div class="col-lg-4 col-lg-offset-1">--}}
                                                                                                {{--<label for="numberPalletsInitialTruck"--}}
                                                                                                       {{--class="control-label">Initial pallets number : {{$anz}}</label>--}}
                                                                                            {{--</div>--}}
                                                                                        {{--@endif--}}
                                                                                    {{--</div>--}}
                                                                                    {{--<!--Account credit-->--}}
                                                                                    {{--<div class="form-group">--}}
                                                                                        {{--<div class="col-lg-2">--}}
                                                                                            {{--<label for="accountTruck"--}}
                                                                                                   {{--class="control-label">Truck--}}
                                                                                                {{--Account :</label>--}}
                                                                                        {{--</div>--}}
                                                                                        {{--<div class="col-lg-5">--}}
                                                                                            {{--<select class="selectpicker show-tick form-control"--}}
                                                                                                    {{--data-size="5"--}}
                                                                                                    {{--data-live-search="true"--}}
                                                                                                    {{--data-live-search-style="startsWith"--}}
                                                                                                    {{--title="Pallets Account Truck"--}}
                                                                                                    {{--name="accountTruck">--}}
                                                                                                {{--@foreach($listPalletsAccountsCarrier as $palletsAccount )--}}
                                                                                                    {{--@if(Illuminate\Support\Facades\Input::old('accountTruck') && $palletsAccount->name==old('accountTruck'))--}}
                                                                                                        {{--<option selected>{{$palletsAccount->name}}</option>--}}
                                                                                                    {{--@elseif(isset($accountTruck)&& $palletsAccount->name==$accountTruck)--}}
                                                                                                        {{--<option selected>{{$palletsAccount->name}}</option>--}}
                                                                                                    {{--@elseif(!isset($accountTruck)&&isset($palletsAccountFavoriteTruck)&& $palletsAccount->name==$palletsAccountFavoriteTruck)--}}
                                                                                                        {{--<option selected>{{$palletsAccount->name}}</option>--}}
                                                                                                    {{--@else--}}
                                                                                                        {{--<option>{{$palletsAccount->name}}</option>--}}
                                                                                                    {{--@endif--}}
                                                                                                {{--@endforeach--}}
                                                                                            {{--</select>--}}
                                                                                        {{--</div>--}}
                                                                                    {{--</div>--}}
                                                                                    {{--<!--validate truck ?-->--}}
                                                                                    {{--<div class="form-group">--}}
                                                                                        {{--@if(isset($filesNamesTruck)&&isset($accountTruck))--}}
                                                                                            {{--<div class="col-lg-2">--}}
                                                                                                {{--<label for="validateTruck"--}}
                                                                                                       {{--class="control-label">Validated--}}
                                                                                                    {{--?</label>--}}
                                                                                            {{--</div>--}}
                                                                                            {{--<div class="col-lg-3">--}}
                                                                                                {{--@if($validateTruck==1)--}}
                                                                                                    {{--<label class="radio-inline"><input--}}
                                                                                                                {{--type="radio"--}}
                                                                                                                {{--name="validateTruck"--}}
                                                                                                                {{--value="true"--}}
                                                                                                                {{--checked>Yes</label>--}}
                                                                                                    {{--<label class="radio-inline"><input--}}
                                                                                                                {{--type="radio"--}}
                                                                                                                {{--name="validateTruck"--}}
                                                                                                                {{--value="false">No</label>--}}
                                                                                                {{--@else--}}
                                                                                                    {{--<label class="radio-inline"><input--}}
                                                                                                                {{--type="radio"--}}
                                                                                                                {{--name="validateTruck"--}}
                                                                                                                {{--value="true">Yes</label>--}}
                                                                                                    {{--<label class="radio-inline"><input--}}
                                                                                                                {{--type="radio"--}}
                                                                                                                {{--name="validateTruck"--}}
                                                                                                                {{--value="false"--}}
                                                                                                                {{--checked>No</label>--}}
                                                                                                {{--@endif--}}
                                                                                            {{--</div>--}}
                                                                                            {{--<div class="col-lg-4 col-lg-offset-2">--}}
                                                                                                {{--<button type="submit"--}}
                                                                                                        {{--class="btn btn-primary btn-block btn-form"--}}
                                                                                                        {{--value="submitTruck"--}}
                                                                                                        {{--name="submitTruck">--}}
                                                                                                    {{--Submit--}}
                                                                                                {{--</button>--}}
                                                                                            {{--</div>--}}
                                                                                        {{--@else--}}
                                                                                            {{--<div class="col-lg-4 col-lg-offset-7">--}}
                                                                                                {{--<button type="submit"--}}
                                                                                                        {{--class="btn btn-primary btn-block btn-form"--}}
                                                                                                        {{--value="submitTruck"--}}
                                                                                                        {{--name="submitTruck"--}}
                                                                                                        {{--data-toggle="modal"--}}
                                                                                                        {{--data-target="#submitTruck_modal">--}}
                                                                                                    {{--Submit--}}
                                                                                                {{--</button>--}}
                                                                                            {{--</div>--}}
                                                                                        {{--@endif--}}
                                                                                    {{--</div>--}}
                                                                                {{--</div>--}}
                                                                            {{--</div>--}}
                                                                        {{--</div>--}}
                                                                        {{--@if (Session::has('openPanelTruck'))--}}
                                                                    {{--</div>--}}
                                                                    {{--@else--}}
                                                            {{--</div>--}}
                                                        {{--@endif--}}
                                                    {{--</div>--}}

                                                    <!-- loading panel-->
                                                    <div class="panel subpanel">
                                                        <div class="panel-heading">
                                                            <a class="col-lg-3 text-left" data-toggle="collapse"
                                                               href="#Pan2Sub1collapse">Loading
                                                                place</a>
                                                            <span class="col-lg-offset-2">{{$totalPalletsLoadingPlace}} / {{$loading->anz}}</span>
                                                            <button type="submit"
                                                                    class="col-lg-offset-4 btn btn-add glyphicon glyphicon-plus"
                                                                    value="addLoadingPlace"
                                                                    name="addLoadingPlace"></button>
                                                            <button type="submit"
                                                                    name="deleteLoadingPlace"
                                                                    class="btn btn-add glyphicon glyphicon-minus"
                                                                    value="deleteLoadingPlace"></button>
                                                        </div>
                                                        @if (Session::has('openPanelLoading'))
                                                            <div id="Pan2Sub1collapse"
                                                                 class="panel-collapse in collapse">
                                                                @else
                                                                    <div id="Pan2Sub1collapse"
                                                                         class="panel-collapse collapse">
                                                                        @endif
                                                                        <div class="panel-body">
                                                                            @if($loading->numberLoadingPlace>0)
                                                                            <div class="panel subpanel">
                                                                                <div class="panel-body">
                                                                                    <!--documents proof upload-->
                                                                                    <div class="form-group text-center">
                                                                                        <label for="documentsLoading">Do
                                                                                            you
                                                                                            have proof
                                                                                            documents (CMR/Exchange
                                                                                            bill/Pallets bill)
                                                                                            ?</label>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <div class="col-lg-offset-1 col-lg-4">
                                                                                            <input type="file"
                                                                                                   name="documentsLoading[]"
                                                                                                   multiple>
                                                                                        </div>
                                                                                        <!--button upload-->
                                                                                        <div class="col-lg-4 col-lg-offset-2">
                                                                                            <input type="submit"
                                                                                                   class="btn btn-primary btn-block btn-form"
                                                                                                   value="Upload"
                                                                                                   name="uploadLoading"/>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        {{--@php(dd($filesNamesLoadingPlace))--}}
                                                                                        @if(isset($filesNamesLoadingPlace))
                                                                                            <ul class="col-lg-offset-1">
                                                                                                @php($list=[])
                                                                                                @foreach($filesNamesLoadingPlace as $name)
                                                                                                    @if(!in_array($name, $list))
                                                                                                    <div>
                                                                                                        <button type="submit"
                                                                                                                name="deleteDocument"
                                                                                                                class="btn-add glyphicon glyphicon-remove"
                                                                                                                value="{{$name}}-Loading"></button>
                                                                                                        <a href="../../storage/app/proofsPallets/{{$loading->atrnr}}/documentsLoading/{{$name}}"
                                                                                                           class="link">{{$name}}</a>
                                                                                                    </div>
                                                                                                        @php(array_push($list,$name))
                                                                                                    @endif
                                                                                                @endforeach
                                                                                            </ul>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @for($k=1; $k<=$loading->numberLoadingPlace; $k++)
                                                                                <div class="panel subpanel">
                                                                                    @php($stateLoadingPlaceK='stateLoadingPlace'.$k)
                                                                                    @php($validateLoadingPlaceK ='validateLoadingPlace'.$k)
                                                                                    <div class="panel-body">
                                                                                        <!--pallets number brought to loading place-->
                                                                                        <div class="form-group">
                                                                                            <div class="col-lg-4">
                                                                                                <label for="numberPalletsLoadingPlace{{$k}}"
                                                                                                       class="control-label">How
                                                                                                    many pallets
                                                                                                    were
                                                                                                    brought
                                                                                                    ?</label>
                                                                                            </div>
                                                                                            <div class="col-lg-2">
                                                                                                @php($numberPalletsLoadingPlaceK ='numberPalletsLoadingPlace'.$k)
                                                                                                @if(isset($loading->$numberPalletsLoadingPlaceK)&& (isset($loading->$validateLoadingPlaceK) && $loading->$validateLoadingPlaceK==1))
                                                                                                    <input class="form-control col-lg-10"
                                                                                                           type="number"
                                                                                                           name="numberPalletsLoadingPlace{{$k}}"
                                                                                                           value="{{$loading->$numberPalletsLoadingPlaceK}}" readonly>
                                                                                                @elseif(isset($loading->$numberPalletsLoadingPlaceK))
                                                                                                    <input class="form-control col-lg-10"
                                                                                                           type="number"
                                                                                                           name="numberPalletsLoadingPlace{{$k}}"
                                                                                                           value="{{$loading->$numberPalletsLoadingPlaceK}}" >
                                                                                                @else
                                                                                                    <input class="form-control col-lg-10"
                                                                                                           type="number"
                                                                                                           name="numberPalletsLoadingPlace{{$k}}"
                                                                                                           value="">
                                                                                                @endif
                                                                                            </div>
                                                                                            @if(isset($loading->$numberPalletsLoadingPlaceK))
                                                                                                <div class="col-lg-2">
                                                                                                    @php($diffK ='diff'.$k)
                                                                                                    @php($$diffK=$loading->$numberPalletsLoadingPlaceK-$loading->anz)
                                                                                                    <label for="differencePalletsLoadingPlace{{$k}}"
                                                                                                           class="control-label">Diff
                                                                                                        : {{$$diffK}}</label>
                                                                                                </div>
                                                                                            @endif
                                                                                        </div>
                                                                                        <!--Account credit/debit-->
                                                                                        <div class="form-group">
                                                                                            <div class="col-lg-2">
                                                                                                <label for="accountCreditLoadingPlace{{$k}}"
                                                                                                       class="control-label">Credit
                                                                                                    Account :</label>
                                                                                            </div>
                                                                                            <div class="col-lg-4">
                                                                                                @php($accountCreditLoadingPlaceK ='accountCreditLoadingPlace'.$k)
                                                                                                @if(isset($loading->$accountCreditLoadingPlaceK)&&(isset($loading->$validateLoadingPlaceK) && $loading->$validateLoadingPlaceK==1))
                                                                                                    <input type="text" name="accountCreditLoadingPlace{{$k}}" class="form-control"
                                                                                                           value="{{$loading->$accountCreditLoadingPlaceK}}" readonly>
                                                                                                @else
                                                                                                <select class="selectpicker show-tick form-control"
                                                                                                        data-size="10"
                                                                                                        data-live-search="true"
                                                                                                        data-live-search-style="startsWith"
                                                                                                        title="Pallets Account Credit"
                                                                                                        name="accountCreditLoadingPlace{{$k}}">
                                                                                                    @foreach($listPalletsAccounts as $palletsAccount )
                                                                                                        @if(Illuminate\Support\Facades\Input::old('accountCreditLoadingPlace'.$k) && $palletsAccount->name==old('accountCreditLoadingPlace'.$k))
                                                                                                            <option selected>{{$palletsAccount->name}}</option>
                                                                                                        @elseif(isset($loading->$accountCreditLoadingPlaceK)&& $palletsAccount->name==$loading->$accountCreditLoadingPlaceK)
                                                                                                            <option selected>{{$palletsAccount->name}}</option>
                                                                                                        @elseif(!isset($loading->$accountCreditLoadingPlaceK)&&isset($accountZipcodeLoadingPlace)&& $palletsAccount->name==$accountZipcodeLoadingPlace)
                                                                                                            <option selected>{{$palletsAccount->name}}</option>
                                                                                                        @else
                                                                                                            <option>{{$palletsAccount->name}}</option>
                                                                                                        @endif
                                                                                                    @endforeach
                                                                                                </select>
                                                                                                    @endif
                                                                                            </div>
                                                                                            <!--Account debited-->
                                                                                            <div class="col-lg-2">
                                                                                                <label for="accountDebitLoadingPlace{{$k}}"
                                                                                                       class="control-label">Debit
                                                                                                    Account :</label>
                                                                                            </div>
                                                                                            <div class="col-lg-4">
                                                                                                @php($accountDebitLoadingPlaceK ='accountDebitLoadingPlace'.$k)
                                                                                                @if(isset($loading->$accountDebitLoadingPlaceK)&&(isset($loading->$validateLoadingPlaceK) && $loading->$validateLoadingPlaceK==1))
                                                                                                    <input type="text" name="accountDebitLoadingPlace{{$k}}" class="form-control"
                                                                                                           value="{{$loading->$accountDebitLoadingPlaceK}}" readonly>
                                                                                                @else
                                                                                                <select class="selectpicker show-tick form-control"
                                                                                                        data-size="10"
                                                                                                        data-live-search="true"
                                                                                                        data-live-search-style="startsWith"
                                                                                                        title="Pallets Account Debit"
                                                                                                        name="accountDebitLoadingPlace{{$k}}">
                                                                                                    @php($accountDebitLoadingPlaceK ='accountDebitLoadingPlace'.$k)
                                                                                                    @foreach($listPalletsAccounts as $palletsAccount )
                                                                                                        @if(Illuminate\Support\Facades\Input::old('accountDebitLoadingPlace'.$k) && $palletsAccount->name==old('accountDebitLoadingPlace'.$k))
                                                                                                            <option selected>{{$palletsAccount->name}}</option>
                                                                                                        @elseif(isset($loading->$accountDebitLoadingPlaceK)&& $palletsAccount->name==$loading->$accountDebitLoadingPlaceK)
                                                                                                            <option selected>{{$palletsAccount->name}}</option>
                                                                                                        @else
                                                                                                            <option>{{$palletsAccount->name}}</option>
                                                                                                        @endif
                                                                                                    @endforeach
                                                                                                </select>
                                                                                                    @endif
                                                                                            </div>
                                                                                        </div>
                                                                                        <!--validate loading ?-->
                                                                                        <div class="form-group">
                                                                                            @if(!empty($filesNamesLoadingPlace)&&isset($loading->$numberPalletsLoadingPlaceK)&&isset($loading->$accountCreditLoadingPlaceK)&&isset($loading->$accountDebitLoadingPlaceK))
                                                                                                <div class="col-lg-2">
                                                                                                    <label for="validateLoadingPlace{{$k}}"
                                                                                                           class="control-label">Validated
                                                                                                        ?</label>
                                                                                                </div>
                                                                                                <div class="col-lg-3">
                                                                                                    @if(isset($loading->$validateLoadingPlaceK) && $loading->$validateLoadingPlaceK==1)
                                                                                                        <label class="radio-inline"><input
                                                                                                                    type="radio"
                                                                                                                    name="validateLoadingPlace{{$k}}"
                                                                                                                    value="true"
                                                                                                                    checked>Yes</label>
                                                                                                        <label class="radio-inline"><input
                                                                                                                    type="radio"
                                                                                                                    name="validateLoadingPlace{{$k}}"
                                                                                                                    value="false">No</label>
                                                                                                    @elseif(isset($loading->$validateLoadingPlaceK) && $loading->$validateLoadingPlaceK==0)
                                                                                                        <label class="radio-inline"><input
                                                                                                                    type="radio"
                                                                                                                    name="validateLoadingPlace{{$k}}"
                                                                                                                    value="true">Yes</label>
                                                                                                        <label class="radio-inline"><input
                                                                                                                    type="radio"
                                                                                                                    name="validateLoadingPlace{{$k}}"
                                                                                                                    value="false"
                                                                                                                    checked>No</label>
                                                                                                    @endif
                                                                                                </div>
                                                                                                <!--button submit-->
                                                                                                <div class="col-lg-4 col-lg-offset-1">
                                                                                                    <button type="submit"
                                                                                                            class="btn btn-primary btn-block btn-form"
                                                                                                            value="{{$k}}"
                                                                                                            name="submitLoading"
                                                                                                            data-toggle="modal"
                                                                                                            data-target="#submitLoading_modal">
                                                                                                        Submit
                                                                                                    </button>
                                                                                                </div>
                                                                                            @else
                                                                                            <!--button submit-->
                                                                                                <div class="col-lg-4 col-lg-offset-6">
                                                                                                    <button type="submit"
                                                                                                            class="btn btn-primary btn-block btn-form"
                                                                                                            value="{{$k}}"
                                                                                                            name="submitLoading"
                                                                                                            data-toggle="modal"
                                                                                                            data-target="#submitLoading_modal">
                                                                                                        Submit
                                                                                                    </button>
                                                                                                </div>
                                                                                            @endif
                                                                                        </div>
{{--@php(dd(isset($submitLoading),isset($loading->$numberPalletsLoadingPlaceK), isset($loading->$accountCreditLoadingPlaceK),isset($loading->$accountDebitLoadingPlaceK) ))--}}
                                                                                        @if(isset($submitLoading) &&isset($loading->$numberPalletsLoadingPlaceK)&&isset($loading->$accountCreditLoadingPlaceK)&&isset($loading->$accountDebitLoadingPlaceK))
                                                                                        <!-- Modal submit -->
                                                                                            <div class="modal show"
                                                                                                 id="submitLoading_modal"
                                                                                                 role="dialog">
                                                                                                <div class="modal-dialog modal-md">
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header modalHeaderLoading">
                                                                                                            <button type="submit"
                                                                                                                    class="close"
                                                                                                                    value="close"
                                                                                                                    name="closeSubmitLoadingModal">
                                                                                                                &times;
                                                                                                            </button>
                                                                                                            <h4 class="modal-title text-center ">
                                                                                                                INFORMATION
                                                                                                            </h4>
                                                                                                        </div>
                                                                                                        <div class="modal-body center modalBodyLoading">
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
                                                                                                                    value="{{$k}}"
                                                                                                                    name="okSubmitLoadingModal" data-toggle="modal"
                                                                                                                    data-target="#submitValidateLoading_modal">
                                                                                                                Confirm
                                                                                                            </button>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        @endif
                                                                                    @if(isset($okSubmitLoadingModal) && $loading->$stateLoadingPlaceK=='Complete Validated')
                                                                                        <div class="modal show"
                                                                                             id="submitLoadingValidate_modal"
                                                                                             role="dialog">
                                                                                            <div class="modal-dialog modal-md">
                                                                                                <div class="modal-content">
                                                                                                    <div class="modal-header modalHeaderLoading">
                                                                                                        <button value="close"
                                                                                                                class="close"
                                                                                                                type="submit"
                                                                                                                name="closeSubmitLoadingModal">
                                                                                                            &times;
                                                                                                        </button>
                                                                                                        <h4 class="modal-title text-center">
                                                                                                            INFORMATION
                                                                                                        </h4>
                                                                                                    </div>
                                                                                                    <div class="modal-body center modalBodyLoading">
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
                                                                                                                value="{{$k}}"
                                                                                                                name="okSubmitValidateLoadingModal">
                                                                                                            Confirm
                                                                                                        </button>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    @endif


                                                                                    </div>
                                                                                </div>
                                                                            @endfor
                                                                                @endif
                                                                        </div>
                                                                        @if (Session::has('openPanelLoading'))
                                                                    </div>
                                                                    @else
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!--offloading panel-->
                                                    {{--<div class="panel subpanel">--}}
                                                        {{--<div class="panel-heading">--}}
                                                            {{--<a class="col-lg-3 text-left" data-toggle="collapse"--}}
                                                               {{--href="#Pan2Sub2collapse">Offloading--}}
                                                                {{--place</a>--}}
                                                            {{--<span class="col-lg-offset-2">{{$totalPalletsOffloadingPlace}} / {{$anz}}</span>--}}
                                                            {{--<button type="submit"--}}
                                                                    {{--class="col-lg-offset-4 btn btn-add glyphicon glyphicon-plus"--}}
                                                                    {{--value="addOffloadingPlace"--}}
                                                                    {{--name="addOffloadingPlace"></button>--}}
                                                            {{--<button type="submit"--}}
                                                                    {{--name="deleteOffloadingPlace"--}}
                                                                    {{--class="btn btn-add glyphicon glyphicon-minus"--}}
                                                                    {{--value="deleteOffloadingPlace"></button>--}}
                                                        {{--</div>--}}
                                                        {{--@if (Session::has('openPanelOffloading'))--}}
                                                            {{--<div id="Pan2Sub2collapse"--}}
                                                                 {{--class="panel-collapse in collapse">--}}
                                                                {{--@else--}}
                                                                    {{--<div id="Pan2Sub2collapse"--}}
                                                                         {{--class="panel-collapse collapse">--}}
                                                                        {{--@endif--}}
                                                                        {{--<div class="panel-body">--}}
                                                                            {{--@if($numberOffloadingPlace>0)--}}
                                                                            {{--<div class="panel subpanel">--}}
                                                                                {{--<div class="panel-body">--}}
                                                                                    {{--<!--documents proof upload-->--}}
                                                                                    {{--<div class="form-group text-center">--}}
                                                                                        {{--<label for="documentsOffloading">Do--}}
                                                                                            {{--you--}}
                                                                                            {{--have proof--}}
                                                                                            {{--documents (CMR/Exchange--}}
                                                                                            {{--bill/Pallets bill)--}}
                                                                                            {{--?</label>--}}
                                                                                    {{--</div>--}}
                                                                                    {{--<div class="form-group">--}}
                                                                                        {{--<div class="col-lg-offset-1 col-lg-4">--}}
                                                                                            {{--<input type="file"--}}
                                                                                                   {{--name="documentsOffloading[]"--}}
                                                                                                   {{--multiple>--}}
                                                                                        {{--</div>--}}
                                                                                        {{--<!--button upload-->--}}
                                                                                        {{--<div class="col-lg-4 col-lg-offset-2">--}}
                                                                                            {{--<input type="submit"--}}
                                                                                                   {{--class="btn btn-primary btn-block btn-form"--}}
                                                                                                   {{--value="Upload"--}}
                                                                                                   {{--name="uploadOffloading"/>--}}
                                                                                        {{--</div>--}}
                                                                                    {{--</div>--}}
                                                                                    {{--<div class="form-group">--}}
                                                                                        {{--@if(isset($filesNamesOffloadingPlace))--}}
                                                                                            {{--<ul class="col-lg-offset-1">--}}
                                                                                                {{--@foreach($filesNamesOffloadingPlace as $name)--}}
                                                                                                    {{--<div>--}}
                                                                                                        {{--<button type="submit"--}}
                                                                                                                {{--name="deleteDocument"--}}
                                                                                                                {{--class="btn-add glyphicon glyphicon-remove"--}}
                                                                                                                {{--value="{{$name}}"></button>--}}
                                                                                                        {{--<a href="../../storage/app/proofsPallets/{{$atrnr}}/documentsOffloading/{{$name}}"--}}
                                                                                                           {{--class="link">{{$name}}</a>--}}
                                                                                                    {{--</div>--}}
                                                                                                {{--@endforeach--}}
                                                                                            {{--</ul>--}}
                                                                                        {{--@endif--}}
                                                                                    {{--</div>--}}
                                                                                {{--</div>--}}
                                                                            {{--</div>--}}
                                                                            {{--@for($k=1; $k<=$numberOffloadingPlace; $k++)--}}
                                                                                {{--<div class="panel subpanel">--}}
                                                                                    {{--<div class="panel-body">--}}
                                                                                        {{--<!--pallets number taken from offloading place-->--}}
                                                                                        {{--<div class="form-group">--}}
                                                                                            {{--<div class="col-lg-4">--}}
                                                                                                {{--<label for="numberPalletsOffloadingPlace{{$k}}"--}}
                                                                                                       {{--class="control-label">How--}}
                                                                                                    {{--many pallets--}}
                                                                                                    {{--were--}}
                                                                                                    {{--taken--}}
                                                                                                    {{--?</label>--}}
                                                                                            {{--</div>--}}
                                                                                            {{--<div class="col-lg-2">--}}
                                                                                                {{--@php($numberPalletsOffloadingPlaceK ='numberPalletsOffloadingPlace'.$k)--}}
                                                                                                {{--@if(isset($$numberPalletsOffloadingPlaceK))--}}
                                                                                                    {{--<input class="col-lg-10"--}}
                                                                                                           {{--type="number"--}}
                                                                                                           {{--name="numberPalletsOffloadingPlace{{$k}}"--}}
                                                                                                           {{--value="{{$$numberPalletsOffloadingPlaceK}}">--}}
                                                                                                {{--@else--}}
                                                                                                    {{--<input class="col-lg-10"--}}
                                                                                                           {{--type="number"--}}
                                                                                                           {{--name="numberPalletsOffloadingPlace{{$k}}"--}}
                                                                                                           {{--value="">--}}
                                                                                                {{--@endif--}}
                                                                                            {{--</div>--}}
                                                                                            {{--@if(isset($$numberPalletsOffloadingPlaceK))--}}
                                                                                                {{--<div class="col-lg-2">--}}
                                                                                                    {{--@php($diffK ='diff'.$k)--}}
                                                                                                    {{--@php($$diffK=$$numberPalletsOffloadingPlaceK-$anz)--}}
                                                                                                    {{--<label for="differencePalletsOffloadingPlace{{$k}}"--}}
                                                                                                           {{--class="control-label">Diff--}}
                                                                                                        {{--: {{$$diffK}}</label>--}}
                                                                                                {{--</div>--}}
                                                                                            {{--@endif--}}
                                                                                        {{--</div>--}}
                                                                                        {{--<!--Account credit-->--}}
                                                                                        {{--<div class="form-group">--}}
                                                                                            {{--<div class="col-lg-2">--}}
                                                                                                {{--<label for="accountCreditOffloadingPlace{{$k}}"--}}
                                                                                                       {{--class="control-label">Credit--}}
                                                                                                    {{--Account :</label>--}}
                                                                                            {{--</div>--}}
                                                                                            {{--<div class="col-lg-4">--}}
                                                                                                {{--<select class="selectpicker show-tick form-control"--}}
                                                                                                        {{--data-size="5"--}}
                                                                                                        {{--data-live-search="true"--}}
                                                                                                        {{--data-live-search-style="startsWith"--}}
                                                                                                        {{--title="Pallets Account Credit"--}}
                                                                                                        {{--name="accountCreditOffloadingPlace{{$k}}">--}}
                                                                                                    {{--@php($accountCreditOffloadingPlaceK ='accountCreditOffloadingPlace'.$k)--}}
                                                                                                    {{--@foreach($listPalletsAccounts as $palletsAccount )--}}
                                                                                                        {{--@if(Illuminate\Support\Facades\Input::old('accountCreditOffloadingPlace'.$k) && $palletsAccount->name==old('accountCreditOffloadingPlace'.$k))--}}
                                                                                                            {{--<option selected>{{$palletsAccount->name}}</option>--}}
                                                                                                        {{--@elseif(isset($$accountCreditOffloadingPlaceK)&& $palletsAccount->name==$$accountCreditOffloadingPlaceK)--}}
                                                                                                            {{--<option selected>{{$palletsAccount->name}}</option>--}}
                                                                                                        {{--@else--}}
                                                                                                            {{--<option>{{$palletsAccount->name}}</option>--}}
                                                                                                        {{--@endif--}}
                                                                                                    {{--@endforeach--}}
                                                                                                {{--</select>--}}
                                                                                            {{--</div>--}}
                                                                                            {{--<!--Account debited-->--}}
                                                                                            {{--<div class="col-lg-2">--}}
                                                                                                {{--<label for="accountDebitOffloadingPlace{{$k}}"--}}
                                                                                                       {{--class="control-label">Debit--}}
                                                                                                    {{--Account :</label>--}}
                                                                                            {{--</div>--}}
                                                                                            {{--<div class="col-lg-4">--}}
                                                                                                {{--<select class="selectpicker show-tick form-control"--}}
                                                                                                        {{--data-size="5"--}}
                                                                                                        {{--data-live-search="true"--}}
                                                                                                        {{--data-live-search-style="startsWith"--}}
                                                                                                        {{--title="Pallets Account Debit"--}}
                                                                                                        {{--name="accountDebitOffloadingPlace{{$k}}">--}}
                                                                                                    {{--@php($accountDebitOffloadingPlaceK ='accountDebitOffloadingPlace'.$k)--}}
                                                                                                    {{--@foreach($listPalletsAccounts as $palletsAccount )--}}
                                                                                                        {{--@if(Illuminate\Support\Facades\Input::old('accountDebitOffloadingPlace'.$k) && $palletsAccount->name==old('accountDebitOffloadingPlace'.$k))--}}
                                                                                                            {{--<option selected>{{$palletsAccount->name}}</option>--}}
                                                                                                        {{--@elseif(isset($$accountDebitOffloadingPlaceK)&& $palletsAccount->name==$$accountDebitOffloadingPlaceK)--}}
                                                                                                            {{--<option selected>{{$palletsAccount->name}}</option>--}}
                                                                                                        {{--@elseif(!isset($$accountDebitOffloadingPlaceK)&&isset($accountZipcodeOffloadingPlace)&& $palletsAccount->name==$accountZipcodeOffloadingPlace)--}}
                                                                                                            {{--<option selected>{{$palletsAccount->name}}</option>--}}
                                                                                                        {{--@else--}}
                                                                                                            {{--<option>{{$palletsAccount->name}}</option>--}}
                                                                                                        {{--@endif--}}
                                                                                                    {{--@endforeach--}}
                                                                                                {{--</select>--}}
                                                                                            {{--</div>--}}
                                                                                        {{--</div>--}}
                                                                                        {{--<!--validate offloading ?-->--}}
                                                                                        {{--@php($validateOffloadingPlaceK ='validateOffloadingPlace'.$k)--}}
                                                                                        {{--<div class="form-group">--}}
                                                                                            {{--@if(isset($filesNamesOffloadingPlace)&&isset($$numberPalletsOffloadingPlaceK)&&isset($$accountCreditOffloadingPlaceK)&&isset($$accountDebitOffloadingPlaceK))--}}
                                                                                                {{--<div class="col-lg-2">--}}
                                                                                                    {{--<label for="validateOffloadingPlace{{$k}}"--}}
                                                                                                           {{--class="control-label">Validated--}}
                                                                                                        {{--?</label>--}}
                                                                                                {{--</div>--}}
                                                                                                {{--<div class="col-lg-3">--}}
                                                                                                    {{--@if($$validateOffloadingPlaceK==1)--}}
                                                                                                        {{--<label class="radio-inline"><input--}}
                                                                                                                    {{--type="radio"--}}
                                                                                                                    {{--name="validateOffloadingPlace{{$k}}"--}}
                                                                                                                    {{--value="true"--}}
                                                                                                                    {{--checked>Yes</label>--}}
                                                                                                        {{--<label class="radio-inline"><input--}}
                                                                                                                    {{--type="radio"--}}
                                                                                                                    {{--name="validateOffloadingPlace{{$k}}"--}}
                                                                                                                    {{--value="false">No</label>--}}
                                                                                                    {{--@else--}}
                                                                                                        {{--<label class="radio-inline"><input--}}
                                                                                                                    {{--type="radio"--}}
                                                                                                                    {{--name="validateOffloadingPlace{{$k}}"--}}
                                                                                                                    {{--value="true">Yes</label>--}}
                                                                                                        {{--<label class="radio-inline"><input--}}
                                                                                                                    {{--type="radio"--}}
                                                                                                                    {{--name="validateOffloadingPlace{{$k}}"--}}
                                                                                                                    {{--value="false"--}}
                                                                                                                    {{--checked>No</label>--}}
                                                                                                    {{--@endif--}}
                                                                                                {{--</div>--}}
                                                                                                {{--<div class="col-lg-4 col-lg-offset-1">--}}
                                                                                                    {{--<button type="submit"--}}
                                                                                                            {{--class="btn btn-primary btn-block btn-form"--}}
                                                                                                            {{--value="{{$k}}"--}}
                                                                                                            {{--name="submitOffloading">--}}
                                                                                                        {{--Submit--}}
                                                                                                    {{--</button>--}}
                                                                                                {{--</div>--}}
                                                                                            {{--@else--}}
                                                                                                {{--<div class="col-lg-4 col-lg-offset-6">--}}
                                                                                                    {{--<button type="submit"--}}
                                                                                                            {{--class="btn btn-primary btn-block btn-form"--}}
                                                                                                            {{--value="{{$k}}"--}}
                                                                                                            {{--name="submitOffloading"--}}
                                                                                                            {{--data-toggle="modal"--}}
                                                                                                            {{--data-target="#submitOffloading_modal">--}}
                                                                                                        {{--Submit--}}
                                                                                                    {{--</button>--}}
                                                                                                {{--</div>--}}
                                                                                            {{--@endif--}}
                                                                                        {{--</div>--}}

                                                                                        {{--@if(Session::has('testFirstTime'))--}}
                                                                                        {{--<!-- Modal submit -->--}}
                                                                                            {{--<div class="modal show"--}}
                                                                                                 {{--id="submitOffloading_modal"--}}
                                                                                                 {{--role="dialog">--}}
                                                                                                {{--<div class="modal-dialog modal-md">--}}
                                                                                                    {{--<div class="modal-content">--}}
                                                                                                        {{--<div class="modal-header">--}}
                                                                                                            {{--<button type="submit"--}}
                                                                                                                    {{--class="close"--}}
                                                                                                                    {{--value="close"--}}
                                                                                                                    {{--name="closeSubmitOffloadingModal">--}}
                                                                                                                {{--&times;--}}
                                                                                                            {{--</button>--}}
                                                                                                            {{--<h4 class="modal-title text-center">--}}
                                                                                                                {{--Information--}}
                                                                                                                {{--:</h4>--}}
                                                                                                        {{--</div>--}}
                                                                                                        {{--<div class="modal-body center">--}}

                                                                                                        {{--@if(request()->session()->get('testFirstTime')=='1stTime' ||request()->session()->get('testFirstTime')=='diffC-diffD')--}}
                                                                                                            {{--<!-- 1st time or none account changed-->--}}
                                                                                                                {{--@if(request()->session()->get('testFirstTime')=='1stTime')--}}
                                                                                                                    {{--<p class="text-center">--}}
                                                                                                                        {{--It--}}
                                                                                                                        {{--is--}}
                                                                                                                        {{--the--}}
                                                                                                                        {{--1st--}}
                                                                                                                        {{--time--}}
                                                                                                                        {{--this--}}
                                                                                                                        {{--pallets--}}
                                                                                                                        {{--transfer--}}
                                                                                                                        {{--is--}}
                                                                                                                        {{--done--}}
                                                                                                                        {{--for--}}
                                                                                                                        {{--this--}}
                                                                                                                        {{--loading--}}
                                                                                                                        {{--place.--}}
                                                                                                                    {{--</p>--}}
                                                                                                                {{--@elseif(request()->session()->get('testFirstTime')=='diffC-diffD')--}}
                                                                                                                    {{--<p class="text-center">--}}
                                                                                                                        {{--Both--}}
                                                                                                                        {{--accounts--}}
                                                                                                                        {{--have--}}
                                                                                                                        {{--been--}}
                                                                                                                        {{--updated--}}
                                                                                                                        {{--for--}}
                                                                                                                        {{--this--}}
                                                                                                                        {{--loading--}}
                                                                                                                        {{--place.--}}
                                                                                                                    {{--</p>--}}
                                                                                                                {{--@endif--}}
                                                                                                                {{--<p class="text-center">--}}
                                                                                                                    {{--Here,--}}
                                                                                                                    {{--planned--}}
                                                                                                                    {{--pallets--}}
                                                                                                                    {{--number</p>--}}
                                                                                                                {{--<table class="table table-hover table-bordered">--}}
                                                                                                                    {{--<thead>--}}
                                                                                                                    {{--<tr>--}}
                                                                                                                        {{--<th class="text-center">--}}
                                                                                                                            {{--CREDIT--}}
                                                                                                                        {{--</th>--}}
                                                                                                                        {{--<th class="text-center">--}}
                                                                                                                            {{--DEBIT--}}
                                                                                                                        {{--</th>--}}
                                                                                                                    {{--</tr>--}}
                                                                                                                    {{--</thead>--}}
                                                                                                                    {{--<tbody>--}}
                                                                                                                    {{--<tr>--}}
                                                                                                                        {{--<td class="text-center">{{request()->session()->get('creditAccount')}}</td>--}}
                                                                                                                        {{--<td class="text-center">{{request()->session()->get('debitAccount')}}</td>--}}
                                                                                                                    {{--</tr>--}}
                                                                                                                    {{--<tr>--}}
                                                                                                                        {{--<td class="text-center">{{request()->session()->get('actualTheoricalNumberPalletsCreditAccount')}}</td>--}}
                                                                                                                        {{--<td class="text-center">{{request()->session()->get('actualTheoricalNumberPalletsDebitAccount')}}</td>--}}
                                                                                                                    {{--</tr>--}}
                                                                                                                    {{--<tr>--}}
                                                                                                                        {{--<td class="text-center">--}}
                                                                                                                            {{--+ {{request()->session()->get('palletsNumber')}}</td>--}}
                                                                                                                        {{--<td class="text-center">--}}
                                                                                                                            {{--- {{request()->session()->get('palletsNumber')}}</td>--}}
                                                                                                                    {{--</tr>--}}
                                                                                                                    {{--<tr>--}}
                                                                                                                        {{--<td class="text-center">--}}
                                                                                                                            {{--= {{request()->session()->get('actualTheoricalNumberPalletsCreditAccount')+request()->session()->get('palletsNumber')}}</td>--}}
                                                                                                                        {{--<td class="text-center">--}}
                                                                                                                            {{--= {{request()->session()->get('actualTheoricalNumberPalletsDebitAccount')-request()->session()->get('palletsNumber')}}</td>--}}
                                                                                                                    {{--</tr>--}}
                                                                                                                    {{--</tbody>--}}
                                                                                                                {{--</table>--}}
                                                                                                            {{--@elseif(request()->session()->get('testFirstTime')=='sameC-sameD')--}}
                                                                                                            {{--<!-- credit and debit accounts haven't changed-->--}}
                                                                                                                {{--<p class="text-center">--}}
                                                                                                                    {{--These--}}
                                                                                                                    {{--accounts--}}
                                                                                                                    {{--have--}}
                                                                                                                    {{--already--}}
                                                                                                                    {{--been--}}
                                                                                                                    {{--credited/debited--}}
                                                                                                                    {{--for--}}
                                                                                                                    {{--this--}}
                                                                                                                    {{--loading--}}
                                                                                                                    {{--place.--}}
                                                                                                                {{--</p>--}}
                                                                                                                {{--<p class="text-center">--}}
                                                                                                                    {{--Here,--}}
                                                                                                                    {{--planned--}}
                                                                                                                    {{--pallets--}}
                                                                                                                    {{--number</p>--}}
                                                                                                                {{--<table class="table table-hover table-bordered">--}}
                                                                                                                    {{--<thead>--}}
                                                                                                                    {{--<tr>--}}
                                                                                                                        {{--<th class="text-center">--}}
                                                                                                                            {{--CREDIT--}}
                                                                                                                        {{--</th>--}}
                                                                                                                        {{--<th class="text-center">--}}
                                                                                                                            {{--DEBIT--}}
                                                                                                                        {{--</th>--}}
                                                                                                                    {{--</tr>--}}
                                                                                                                    {{--</thead>--}}
                                                                                                                    {{--<tbody>--}}
                                                                                                                    {{--<tr>--}}
                                                                                                                        {{--<td class="text-center">{{request()->session()->get('creditAccount')}}</td>--}}
                                                                                                                        {{--<td class="text-center">{{request()->session()->get('debitAccount')}}</td>--}}
                                                                                                                    {{--</tr>--}}
                                                                                                                    {{--<tr>--}}
                                                                                                                        {{--<td class="text-center">{{request()->session()->get('actualTheoricalNumberPalletsCreditAccount')}}</td>--}}
                                                                                                                        {{--<td class="text-center">{{request()->session()->get('actualTheoricalNumberPalletsDebitAccount')}}</td>--}}
                                                                                                                    {{--</tr>--}}
                                                                                                                    {{--<tr>--}}
                                                                                                                        {{--<td class="text-center">--}}
                                                                                                                            {{--- {{request()->session()->get('lastPalletsNumber')}} (last transfer)</td>--}}
                                                                                                                        {{--<td class="text-center">--}}
                                                                                                                            {{--+ {{request()->session()->get('lastPalletsNumber')}} (last transfer)</td>--}}
                                                                                                                    {{--</tr>--}}
                                                                                                                    {{--<tr>--}}
                                                                                                                        {{--<td class="text-center">--}}
                                                                                                                            {{--+ {{request()->session()->get('palletsNumber')}} (new transfer)</td>--}}
                                                                                                                        {{--<td class="text-center">--}}
                                                                                                                            {{--- {{request()->session()->get('palletsNumber')}} (new transfer)</td>--}}
                                                                                                                    {{--</tr>--}}
                                                                                                                    {{--<tr>--}}
                                                                                                                        {{--<td class="text-center">--}}
                                                                                                                            {{--= {{request()->session()->get('actualTheoricalNumberPalletsCreditAccount')- request()->session()->get('lastPalletsNumber')+request()->session()->get('palletsNumber')}}</td>--}}
                                                                                                                        {{--<td class="text-center">--}}
                                                                                                                            {{--= {{request()->session()->get('actualTheoricalNumberPalletsDebitAccount')+ request()->session()->get('lastPalletsNumber')-request()->session()->get('palletsNumber')}}</td>--}}
                                                                                                                    {{--</tr>--}}
                                                                                                                    {{--</tbody>--}}
                                                                                                                {{--</table>--}}
                                                                                                            {{--@elseif(request()->session()->get('testFirstTime')=='sameC-diffD')--}}
                                                                                                            {{--<!-- debit account has changed-->--}}
                                                                                                                {{--<p class="text-center">--}}
                                                                                                                    {{--Debit--}}
                                                                                                                    {{--account--}}
                                                                                                                    {{--has--}}
                                                                                                                    {{--been--}}
                                                                                                                    {{--updated--}}
                                                                                                                    {{--for--}}
                                                                                                                    {{--this--}}
                                                                                                                    {{--loading--}}
                                                                                                                    {{--place.--}}
                                                                                                                {{--</p>--}}
                                                                                                                {{--<p class="text-center">--}}
                                                                                                                    {{--Here,--}}
                                                                                                                    {{--planned--}}
                                                                                                                    {{--pallets--}}
                                                                                                                    {{--number</p>--}}
                                                                                                                {{--<table class="table table-hover table-bordered">--}}
                                                                                                                    {{--<thead>--}}
                                                                                                                    {{--<tr>--}}
                                                                                                                        {{--<th class="text-center">--}}
                                                                                                                            {{--CREDIT--}}
                                                                                                                        {{--</th>--}}
                                                                                                                        {{--<th class="text-center">--}}
                                                                                                                            {{--DEBIT--}}
                                                                                                                        {{--</th>--}}
                                                                                                                    {{--</tr>--}}
                                                                                                                    {{--</thead>--}}
                                                                                                                    {{--<tbody>--}}
                                                                                                                    {{--<tr>--}}
                                                                                                                        {{--<td class="text-center">{{request()->session()->get('creditAccount')}}</td>--}}
                                                                                                                        {{--<td class="text-center">{{request()->session()->get('debitAccount')}}</td>--}}
                                                                                                                    {{--</tr>--}}
                                                                                                                    {{--<tr>--}}
                                                                                                                        {{--<td class="text-center">{{request()->session()->get('actualTheoricalNumberPalletsCreditAccount')}}</td>--}}
                                                                                                                        {{--<td class="text-center">{{request()->session()->get('actualTheoricalNumberPalletsDebitAccount')}}</td>--}}
                                                                                                                    {{--</tr>--}}
                                                                                                                    {{--<tr>--}}
                                                                                                                        {{--<td class="text-center">--}}
                                                                                                                            {{--- {{request()->session()->get('lastPalletsNumber')}} (last transfer)</td>--}}
                                                                                                                        {{--<td class="text-center">--}}
                                                                                                                        {{--</td>--}}
                                                                                                                    {{--</tr>--}}
                                                                                                                    {{--<tr>--}}
                                                                                                                        {{--<td class="text-center">--}}
                                                                                                                            {{--+ {{request()->session()->get('palletsNumber')}} (new transfer)</td>--}}
                                                                                                                        {{--<td class="text-center">--}}
                                                                                                                            {{--- {{request()->session()->get('palletsNumber')}} (new transfer)</td>--}}
                                                                                                                    {{--</tr>--}}
                                                                                                                    {{--<tr>--}}
                                                                                                                        {{--<td class="text-center">--}}
                                                                                                                            {{--= {{request()->session()->get('actualTheoricalNumberPalletsCreditAccount')- request()->session()->get('lastPalletsNumber')+request()->session()->get('palletsNumber')}}</td>--}}
                                                                                                                        {{--<td class="text-center">--}}
                                                                                                                            {{--= {{request()->session()->get('actualTheoricalNumberPalletsDebitAccount')-request()->session()->get('palletsNumber')}}</td>--}}
                                                                                                                    {{--</tr>--}}
                                                                                                                    {{--</tbody>--}}
                                                                                                                {{--</table>--}}
                                                                                                            {{--@elseif(request()->session()->get('testFirstTime')=='diffC-sameD')--}}
                                                                                                            {{--<!-- credit account has changed-->--}}
                                                                                                                {{--<p class="text-center">--}}
                                                                                                                    {{--Credit--}}
                                                                                                                    {{--account--}}
                                                                                                                    {{--has--}}
                                                                                                                    {{--been--}}
                                                                                                                    {{--updated--}}
                                                                                                                    {{--for--}}
                                                                                                                    {{--this--}}
                                                                                                                    {{--loading--}}
                                                                                                                    {{--place.--}}
                                                                                                                {{--</p>--}}
                                                                                                                {{--<p class="text-center">--}}
                                                                                                                    {{--Here,--}}
                                                                                                                    {{--planned--}}
                                                                                                                    {{--pallets--}}
                                                                                                                    {{--number</p>--}}
                                                                                                                {{--<table class="table table-hover table-bordered">--}}
                                                                                                                    {{--<thead>--}}
                                                                                                                    {{--<tr>--}}
                                                                                                                        {{--<th class="text-center">--}}
                                                                                                                            {{--CREDIT--}}
                                                                                                                        {{--</th>--}}
                                                                                                                        {{--<th class="text-center">--}}
                                                                                                                            {{--DEBIT--}}
                                                                                                                        {{--</th>--}}
                                                                                                                    {{--</tr>--}}
                                                                                                                    {{--</thead>--}}
                                                                                                                    {{--<tbody>--}}
                                                                                                                    {{--<tr>--}}
                                                                                                                        {{--<td class="text-center">{{request()->session()->get('creditAccount')}}</td>--}}
                                                                                                                        {{--<td class="text-center">{{request()->session()->get('debitAccount')}}</td>--}}
                                                                                                                    {{--</tr>--}}
                                                                                                                    {{--<tr>--}}
                                                                                                                        {{--<td class="text-center">{{request()->session()->get('actualTheoricalNumberPalletsCreditAccount')}}</td>--}}
                                                                                                                        {{--<td class="text-center">{{request()->session()->get('actualTheoricalNumberPalletsDebitAccount')}}</td>--}}
                                                                                                                    {{--</tr>--}}
                                                                                                                    {{--<tr>--}}
                                                                                                                        {{--<td class="text-center">--}}
                                                                                                                        {{--</td>--}}
                                                                                                                        {{--<td class="text-center">--}}
                                                                                                                            {{--+ {{request()->session()->get('lastPalletsNumber')}} (last transfer)</td>--}}
                                                                                                                    {{--</tr>--}}
                                                                                                                    {{--<tr>--}}
                                                                                                                        {{--<td class="text-center">--}}
                                                                                                                            {{--+ {{request()->session()->get('palletsNumber')}} (new transfer)</td>--}}
                                                                                                                        {{--<td class="text-center">--}}
                                                                                                                            {{--- {{request()->session()->get('palletsNumber')}} (new transfer)</td>--}}
                                                                                                                    {{--</tr>--}}
                                                                                                                    {{--<tr>--}}
                                                                                                                        {{--<td class="text-center">--}}
                                                                                                                            {{--= {{request()->session()->get('actualTheoricalNumberPalletsCreditAccount')+request()->session()->get('palletsNumber')}}</td>--}}
                                                                                                                        {{--<td class="text-center">--}}
                                                                                                                            {{--= {{request()->session()->get('actualTheoricalNumberPalletsDebitAccount')+ request()->session()->get('lastPalletsNumber')-request()->session()->get('palletsNumber')}}</td>--}}
                                                                                                                    {{--</tr>--}}
                                                                                                                    {{--</tbody>--}}
                                                                                                                {{--</table>--}}
                                                                                                            {{--@endif--}}
                                                                                                        {{--</div>--}}
                                                                                                        {{--<div class="modal-footer">--}}
                                                                                                            {{--<button type="submit"--}}
                                                                                                                    {{--class="btn btn-default btn-modal"--}}
                                                                                                                    {{--value="close"--}}
                                                                                                                    {{--name="closeSubmitOffloadingModal">--}}
                                                                                                                {{--OK--}}
                                                                                                            {{--</button>--}}
                                                                                                        {{--</div>--}}
                                                                                                    {{--</div>--}}
                                                                                                {{--</div>--}}
                                                                                            {{--</div>--}}
                                                                                        {{--@endif--}}


                                                                                    {{--</div>--}}
                                                                                {{--</div>--}}
                                                                            {{--@endfor--}}
                                                                                {{--@endif--}}
                                                                        {{--</div>--}}
                                                                        {{--@if (Session::has('openPanelOffloading'))--}}
                                                                    {{--</div>--}}
                                                                    {{--@else--}}
                                                            {{--</div>--}}
                                                        {{--@endif--}}
                                                    {{--</div>--}}
                                                </form>
                                            </div>
                                            @if (Session::has('openPanelLoading')||Session::has('openPanelOffloading')||Session::has('openPanelTruck'))
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

    <script type="text/javascript" src="{{asset('js/addUpdatePalletsaccount.js')}}"></script>
@endsection