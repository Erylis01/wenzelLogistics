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
@section('classCarriers')
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
                    <div class="panel-heading">Details of the loading n°{{ $atrnr }}
                        @if(isset($numberPalletsBackLoadingPlace))
                            <span class="col-lg-offset-6">TOTAL = {{$numberPalletsBackLoadingPlace}}</span>
                        @endif
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
                                                      action="{{route('updateDetailsLoading', $atrnr)}}">
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
                                                                                               value="{{ $referenz }}"
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
                                                                                               value="{{ $disp }}"
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
                                                                                                   value="{{ $pt }}">
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
                                                                                                             value="{{ $auftraggeber }}"
                                                                                                             placeholder="auftraggeber"
                                                                                                             required>
                                                                                    </div>
                                                                                </div>

                                                                                {{--<!--trp-->--}}
                                                                                {{--<div class="col-lg-4">--}}
                                                                                {{--<div class="input-group details-loading">--}}
                                                                                {{--<label for="trp" class="input-group-addon">Trp--}}
                                                                                {{--:</label>--}}
                                                                                {{--<input type="number" name="trp"--}}
                                                                                {{--class="form-control" value="{{ $trp }}"--}}
                                                                                {{--placeholder="trp" required>--}}
                                                                                {{--</div>--}}
                                                                                {{--</div>--}}
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
                                                                                               value="{{ $subfrachter }}"
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
                                                                                               value="{{ $kennzeichen }}"
                                                                                               placeholder="kennzeichen">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <!--anz-->
                                                                                <div class="col-lg-2 details-loading">
                                                                                    <input type="number" name="anz"
                                                                                           class="form-control"
                                                                                           value="{{ $anz }}"
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
                                                                                           value="{{ $art }}"
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
                                                                                           value="{{ $ware }}"
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
                                                                        value="{{ $ladedatum }}"
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
                                                                                           value="{{ $beladestelle }}"
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
                                                                                           value="{{ $plzb }}"
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
                                                                                           value="{{ $ortb }}"
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
                                                                                           value="{{ $landb }}"
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
                                                                                               value="{{ $zusladestellen }}"
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
                                                               href="#PanSub2collapse">Unloading</a>
                                                            <div class="col-lg-5 col-lg-offset-1 text-right details-loading">
                                                                <input type="date" name="entladedatum"
                                                                       class="form-control"
                                                                       value="{{ $entladedatum }}"
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
                                                                                           value="{{ $entladestelle }}"
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
                                                                                           value="{{ $plze }}"
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
                                                                                           value="{{ $orte }}"
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
                                                                                           value="{{ $lande }}"
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
                                                                            autofocus>{{$reasonUpdatePT}}</textarea>
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
                                                                                          action="{{ route('updateDetailsLoading', $atrnr) }}">
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
                            @if (Session::has('openPanelLoading')||Session::has('openPanelOffloading'))
                                <div id="Pan2collapse" class="panel-collapse in collapse">
                                    @else
                                        <div id="Pan2collapse" class="panel-collapse collapse">
                                            @endif
                                            <div class="panel-body">
                                                @if (Session::has('messageSuccessSubmit'))
                                                    <div class="alert alert-success text-alert text-center">{{ Session::get('messageSuccessSubmit') }}</div>
                                                @elseif (Session::has('messageSuccessUpload'))
                                                    <div class="alert alert-success text-alert text-center">{{ Session::get('messageSuccessUpload') }}</div>
                                                @elseif (Session::has('messageSuccessDeleteDocument'))
                                                    <div class="alert alert-success text-alert text-center">{{ Session::get('messageSuccessDeleteDocument') }}</div>
                                                @elseif(Session::has('messageErrorUpload'))
                                                    <div class="alert alert-danger text-alert text-center">{{ Session::get('messageErrorUpload') }}</div>
                                                @endif
                                                <form class="form-horizontal"
                                                      role="form"
                                                      method="POST"
                                                      action="{{route('submitUpload', ['atrnr'=>$atrnr, 'anz'=>$anz])}}"
                                                      enctype="multipart/form-data">
                                                    <input type="hidden"
                                                           name="_token"
                                                           value="{{ csrf_token() }}">

                                                    <!-- loading panel-->
                                                    <div class="panel subpanel">
                                                        <div class="panel-heading">
                                                            <a data-toggle="collapse" href="#Pan2Sub1collapse">Loading
                                                                place</a>
                                                        </div>
                                                        @if (Session::has('openPanelLoading'))
                                                            <div id="Pan2Sub1collapse"
                                                                 class="panel-collapse in collapse">
                                                                @else
                                                                    <div id="Pan2Sub1collapse"
                                                                         class="panel-collapse collapse">
                                                                        @endif
                                                                        <div class="panel-body">
                                                                            <div class="panel subpanel col-lg-7">
                                                                                <div class="panel-body">
                                                                                    <!--pallets number brought to loading place-->
                                                                                    <div class="form-group">
                                                                                        <div class="col-lg-6 noPadding">
                                                                                            <label for="numberPalletsLoadingPlace"
                                                                                                   class="control-label">How
                                                                                                many pallets
                                                                                                were
                                                                                                brought
                                                                                                ?</label>
                                                                                        </div>
                                                                                        <div class="col-lg-3">
                                                                                            @if(isset($numberPalletsLoadingPlace))
                                                                                                <input class="col-lg-10"
                                                                                                       type="number"
                                                                                                       name="numberPalletsLoadingPlace"
                                                                                                       value="{{$numberPalletsLoadingPlace}}">
                                                                                            @else
                                                                                                <input class="col-lg-10"
                                                                                                       type="number"
                                                                                                       name="numberPalletsLoadingPlace"
                                                                                                       value="">
                                                                                            @endif
                                                                                        </div>
                                                                                        @if(isset($numberPalletsLoadingPlace))
                                                                                            <div class="col-lg-3 noPadding">
                                                                                                @php($diff=$numberPalletsLoadingPlace-$anz)
                                                                                                <label for="differencePalletsLoadingPlace"
                                                                                                       class="control-label">Diff
                                                                                                    : {{$diff}}</label>
                                                                                            </div>
                                                                                        @endif
                                                                                    </div>
                                                                                    <!--Account credit-->
                                                                                    <div class="form-group">
                                                                                        <div class="col-lg-5 noPadding">
                                                                                            <label for="accountCreditLoadingPlace"
                                                                                                   class="control-label">Which
                                                                                                account to
                                                                                                credit ?</label>
                                                                                        </div>
                                                                                        <div class="col-lg-7">
                                                                                            <select class="selectpicker show-tick form-control"
                                                                                                    data-size="5"
                                                                                                    data-live-search="true"
                                                                                                    data-live-search-style="startsWith"
                                                                                                    title="Pallets Account Credit"
                                                                                                    name="accountCreditLoadingPlace"
                                                                                            >
                                                                                                @foreach($listPalletsAccounts as $palletsAccount )
                                                                                                    @if(Illuminate\Support\Facades\Input::old('accountCreditLoadingPlace') && $palletsAccount->name==old('accountCreditLoadingPlace'))
                                                                                                        <option selected>{{$palletsAccount->name}}</option>
                                                                                                    @elseif(isset($accountZipcodeLoadingPlace)&& $palletsAccount->name==$accountZipcodeLoadingPlace)
                                                                                                        <option selected>{{$palletsAccount->name}}</option>
                                                                                                    @elseif(isset($accountCreditLoadingPlace)&& $palletsAccount->name==$accountCreditLoadingPlace)
                                                                                                        <option selected>{{$palletsAccount->name}}</option>
                                                                                                    @else
                                                                                                        <option>{{$palletsAccount->name}}</option>
                                                                                                    @endif
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!--Account debited-->
                                                                                    <div class="form-group">
                                                                                        <div class="col-lg-5 noPadding">
                                                                                            <label for="accountDebitLoadingPlace"
                                                                                                   class="control-label">Which
                                                                                                account to
                                                                                                debit ?</label>
                                                                                        </div>
                                                                                        <div class="col-lg-7">
                                                                                            <select class="selectpicker show-tick form-control"
                                                                                                    data-size="5"
                                                                                                    data-live-search="true"
                                                                                                    data-live-search-style="startsWith"
                                                                                                    title="Pallets Account Debit"
                                                                                                    name="accountDebitLoadingPlace"
                                                                                            >
                                                                                                @foreach($listPalletsAccounts as $palletsAccount )
                                                                                                    @if(Illuminate\Support\Facades\Input::old('accountDebitLoadingPlace') && $palletsAccount->name==old('accountDebitLoadingPlace'))
                                                                                                        <option selected>{{$palletsAccount->name}}</option>
                                                                                                        {{--@elseif(isset($accountZipcodeLoadingPlace)&& $palletsAccount->name==$accountZipcodeLoadingPlace)--}}
                                                                                                        {{--<option selected>{{$palletsAccount->name}}</option>--}}
                                                                                                    @elseif(isset($accountDebitLoadingPlace)&& $palletsAccount->name==$accountDebitLoadingPlace)
                                                                                                        <option selected>{{$palletsAccount->name}}</option>
                                                                                                    @else
                                                                                                        <option>{{$palletsAccount->name}}</option>
                                                                                                    @endif
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!--validate loading ?-->
                                                                                    @if(isset($filesNamesLoadingPlace)&&isset($numberPalletsLoadingPlace)&&isset($accountCreditLoadingPlace)&&isset($accountDebitLoadingPlace))
                                                                                        <div class="form-group">
                                                                                            <div class="col-lg-5 noPadding">
                                                                                                <label for="validateLoadingPlace"
                                                                                                       class="control-label">Is
                                                                                                    this
                                                                                                    transfer
                                                                                                    validated
                                                                                                    ?</label>
                                                                                            </div>
                                                                                            <div class="col-lg-3">
                                                                                                @if($validateLoadingPlace==true)
                                                                                                    <label class="radio-inline"><input
                                                                                                                type="radio"
                                                                                                                name="validateLoadingPlace"
                                                                                                                value="true"
                                                                                                                checked>Yes</label>
                                                                                                    <label class="radio-inline"><input
                                                                                                                type="radio"
                                                                                                                name="validateLoadingPlace"
                                                                                                                value="false">No</label>
                                                                                                @else
                                                                                                    <label class="radio-inline"><input
                                                                                                                type="radio"
                                                                                                                name="validateLoadingPlace"
                                                                                                                value="true">Yes</label>
                                                                                                    <label class="radio-inline"><input
                                                                                                                type="radio"
                                                                                                                name="validateLoadingPlace"
                                                                                                                value="false"
                                                                                                                checked>No</label>
                                                                                                @endif
                                                                                            </div>
                                                                                        </div>
                                                                                @endif
                                                                                <!--submit button-->
                                                                                    <div class="form-group">
                                                                                        <div class="col-lg-6 col-lg-offset-3">
                                                                                            <input type="submit"
                                                                                                   class="btn btn-primary btn-block btn-form"
                                                                                                   value="Submit"
                                                                                                   name="submitLoading"/>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="panel subpanel col-lg-5">
                                                                                <div class="panel-body">
                                                                                    <!--documents proof upload-->
                                                                                    <div class="form-group text-center">
                                                                                        <label for="documentsLoading">Do
                                                                                            you
                                                                                            have proof
                                                                                            documents<br>
                                                                                            (CMR/Exchange
                                                                                            bill/Pallets bill)
                                                                                            ?</label>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <input type="file"
                                                                                               name="documentsLoading[]"
                                                                                               multiple/>
                                                                                    </div>
                                                                                    <div class="form-group text-left">
                                                                                        @if(isset($filesNamesLoadingPlace))
                                                                                            <ul>
                                                                                                @foreach($filesNamesLoadingPlace as $name)
                                                                                                    <li>
                                                                                                        <a href="../../storage/app/proofsPallets/{{$atrnr}}/documentsLoading/{{$name}}"
                                                                                                           class="link">{{$name}}</a>
                                                                                                    </li>
                                                                                                @endforeach
                                                                                            </ul>
                                                                                        @endif
                                                                                    </div>
                                                                                    <!--upload button-->
                                                                                    <div class="form-group">
                                                                                        <div class="col-lg-8 col-lg-offset-2">
                                                                                            <input type="submit"
                                                                                                   class="btn btn-primary btn-block btn-form"
                                                                                                   value="Upload"
                                                                                                   name="uploadLoading"/>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        @if (Session::has('openPanelLoading'))
                                                                    </div>
                                                                    @else
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!--offloading panel-->
                                                    <div class="panel subpanel">
                                                        <div class="panel-heading">
                                                            <a data-toggle="collapse"
                                                               href="#Pan2Sub2collapse">Offloading
                                                                place</a>
                                                        </div>
                                                        @if (Session::has('openPanelOffloading'))
                                                            <div id="Pan2Sub2collapse"
                                                                 class="panel-collapse in collapse">
                                                                @else
                                                                    <div id="Pan2Sub2collapse"
                                                                         class="panel-collapse collapse">
                                                                        @endif
                                                                        <div class="panel-body">
                                                                            <div class="panel subpanel col-lg-7">
                                                                                <div class="panel-body">
                                                                                    <!--pallets number taken from offloading place-->
                                                                                    <div class="form-group">
                                                                                        <div class="col-lg-6 noPadding">
                                                                                            <label for="numberPalletsOffloadingPlace"
                                                                                                   class="control-label">How
                                                                                                many pallets
                                                                                                were
                                                                                                taken
                                                                                                ?</label>
                                                                                        </div>
                                                                                        <div class="col-lg-3">
                                                                                            @if(isset($numberPalletsOffloadingPlace))
                                                                                                <input class="col-lg-10"
                                                                                                       type="number"
                                                                                                       name="numberPalletsOffloadingPlace"
                                                                                                       value="{{$numberPalletsOffloadingPlace}}">
                                                                                            @else
                                                                                                <input class="col-lg-10"
                                                                                                       type="number"
                                                                                                       name="numberPalletsOffloadingPlace"
                                                                                                       value="">
                                                                                            @endif
                                                                                        </div>
                                                                                        @if(isset($numberPalletsOffloadingPlace))
                                                                                            <div class="col-lg-3 noPadding">
                                                                                                @php($diff=$numberPalletsOffloadingPlace-$anz)
                                                                                                <label for="differencePalletsOffloadingPlace"
                                                                                                       class="control-label">Diff
                                                                                                    : {{$diff}}</label>
                                                                                            </div>
                                                                                        @endif
                                                                                    </div>
                                                                                    <!--Account credit-->
                                                                                    <div class="form-group">
                                                                                        <div class="col-lg-5 noPadding">
                                                                                            <label for="accountCreditOffloadingPlace"
                                                                                                   class="control-label">Which
                                                                                                account to
                                                                                                credit ?</label>
                                                                                        </div>
                                                                                        <div class="col-lg-7">
                                                                                            <select class="selectpicker show-tick form-control"
                                                                                                    data-size="5"
                                                                                                    data-live-search="true"
                                                                                                    data-live-search-style="startsWith"
                                                                                                    title="Pallets Account Credit"
                                                                                                    name="accountCreditOffloadingPlace"
                                                                                            >
                                                                                                @foreach($listPalletsAccounts as $palletsAccount )
                                                                                                    @if(Illuminate\Support\Facades\Input::old('accountCreditOffloadingPlace') && $palletsAccount->name==old('accountCreditOffloadingPlace'))
                                                                                                        <option selected>{{$palletsAccount->name}}</option>
                                                                                                        {{--@elseif(isset($accountZipcodeCreditOffloadingPlace)&& $palletsAccount->name==$accountZipcodeCreditOffloadingPlace)--}}
                                                                                                        {{--<option selected>{{$palletsAccount->name}}</option>--}}
                                                                                                    @elseif(isset($accountCreditOffloadingPlace)&& $palletsAccount->name==$accountCreditOffloadingPlace)
                                                                                                        <option selected>{{$palletsAccount->name}}</option>
                                                                                                    @else
                                                                                                        <option>{{$palletsAccount->name}}</option>
                                                                                                    @endif
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!--Account debited-->
                                                                                    <div class="form-group">
                                                                                        <div class="col-lg-5 noPadding">
                                                                                            <label for="accountDebitOffloadingPlace"
                                                                                                   class="control-label">Which
                                                                                                account to
                                                                                                debit ?</label>
                                                                                        </div>
                                                                                        <div class="col-lg-7">
                                                                                            <select class="selectpicker show-tick form-control"
                                                                                                    data-size="5"
                                                                                                    data-live-search="true"
                                                                                                    data-live-search-style="startsWith"
                                                                                                    title="Pallets Account Debit"
                                                                                                    name="accountDebitOffloadingPlace"
                                                                                            >
                                                                                                @foreach($listPalletsAccounts as $palletsAccount )
                                                                                                    @if(Illuminate\Support\Facades\Input::old('accountDebitOffloadingPlace') && $palletsAccount->name==old('accountDebitOffloadingPlace'))
                                                                                                        <option selected>{{$palletsAccount->name}}</option>
                                                                                                    @elseif(isset($accountZipcodeOffloadingPlace)&& $palletsAccount->name==$accountZipcodeOffloadingPlace)
                                                                                                        <option selected>{{$palletsAccount->name}}</option>
                                                                                                    @elseif(isset($accountDebitOffloadingPlace)&& $palletsAccount->name==$accountDebitOffloadingPlace)
                                                                                                        <option selected>{{$palletsAccount->name}}</option>
                                                                                                    @else
                                                                                                        <option>{{$palletsAccount->name}}</option>
                                                                                                    @endif
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!--validate loading ?-->
                                                                                    @if(isset($filesNamesOffloadingPlace)&&isset($numberPalletsOffloadingPlace)&&isset($accountCreditOffloadingPlace)&&isset($accountDebitOffloadingPlace))
                                                                                        <div class="form-group">
                                                                                            <div class="col-lg-5 noPadding">
                                                                                                <label for="validateOffloadingPlace"
                                                                                                       class="control-label">Is
                                                                                                    this
                                                                                                    transfer
                                                                                                    validated
                                                                                                    ?</label>
                                                                                            </div>
                                                                                            <div class="col-lg-3">
                                                                                                @if($validateOffloadingPlace==true)
                                                                                                    <label class="radio-inline"><input
                                                                                                                type="radio"
                                                                                                                name="validateOffloadingPlace"
                                                                                                                value="true"
                                                                                                                checked>Yes</label>
                                                                                                    <label class="radio-inline"><input
                                                                                                                type="radio"
                                                                                                                name="validateOffloadingPlace"
                                                                                                                value="false">No</label>
                                                                                                @else
                                                                                                    <label class="radio-inline"><input
                                                                                                                type="radio"
                                                                                                                name="validateoffloadingPlace"
                                                                                                                value="true">Yes</label>
                                                                                                    <label class="radio-inline"><input
                                                                                                                type="radio"
                                                                                                                name="validateOffloadingPlace"
                                                                                                                value="false"
                                                                                                                checked>No</label>
                                                                                                @endif
                                                                                            </div>
                                                                                        </div>
                                                                                @endif
                                                                                <!--submit button-->
                                                                                    <div class="form-group">
                                                                                        <div class="col-lg-6 col-lg-offset-3">
                                                                                            <input type="submit"
                                                                                                   class="btn btn-primary btn-block btn-form"
                                                                                                   value="Submit"
                                                                                                   name="submitOffloading"/>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="panel subpanel col-lg-5">
                                                                                <div class="panel-body">
                                                                                    <!--documents proof upload-->
                                                                                    <div class="form-group text-center">
                                                                                        <label for="documentsOffloading">Do
                                                                                            you
                                                                                            have proof
                                                                                            documents<br>
                                                                                            (CMR/Exchange
                                                                                            bill/Pallets bill)
                                                                                            ?</label>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <input type="file"
                                                                                               name="documentsOffloading[]"
                                                                                               multiple/>
                                                                                    </div>
                                                                                    <div class="form-group text-left">
                                                                                            @if(isset($filesNamesOffloadingPlace))
                                                                                                <ul>
                                                                                                    @foreach($filesNamesOffloadingPlace as $name)
                                                                                                        <li>
                                                                                                            <button type="submit"
                                                                                                                   name="deleteDocument"
                                                                                                                   class="btn-add glyphicon glyphicon-remove"
                                                                                                                   value="{{$name}}"></button>
                                                                                                            <a href="../../storage/app/proofsPallets/{{$atrnr}}/documentsOffloading/{{$name}}"
                                                                                                               class="col-lg-offset-1 link radio-inline">{{$name}}</a>
                                                                                                        </li>
                                                                                                    @endforeach
                                                                                                </ul>
                                                                                            @endif
                                                                                    </div>
                                                                                    <!--upload button-->
                                                                                    <div class="form-group">
                                                                                        <div class="col-lg-8 col-lg-offset-2">
                                                                                            <input type="submit"
                                                                                                   class="btn btn-primary btn-block btn-form"
                                                                                                   value="Upload"
                                                                                                   name="uploadOffloading"/>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                        @if (Session::has('openPanelOffloading'))
                                                                    </div>
                                                                    @else
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- carrier panel-->
                                                    <div class="panel subpanel">
                                                        <div class="panel-heading">
                                                            <a data-toggle="collapse"
                                                               href="#Pan2Sub3collapse">Carrier</a>
                                                        </div>
                                                        <div id="Pan2Sub3collapse"
                                                             class="panel-collapse collapse">
                                                            <div class="panel-body">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- other places-->
                                                    <div class="panel subpanel">
                                                        <div class="panel-heading">
                                                            <a data-toggle="collapse"
                                                               href="#Pan2Sub4collapse">Other
                                                                place</a>
                                                        </div>
                                                        <div id="Pan2Sub4collapse"
                                                             class="panel-collapse collapse">
                                                            <div class="panel-body">
                                                            </div>
                                                        </div>
                                                    </div>

                                                </form>
                                            </div>
                                            @if (Session::has('openPanelLoading')||Session::has('openPanelOffloading'))
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