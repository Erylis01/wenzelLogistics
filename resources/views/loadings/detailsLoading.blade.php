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

                @if($loading->state=="In progress")
                    <div class="panel panelInprogress">
                        @elseif ($loading->state=="Waiting documents")
                            <div class="panel panelWaitingdocuments">
                                @elseif ($loading->state=="Complete")
                                    <div class="panel panelComplete">
                                        @elseif ($loading->state=="Complete Validated")
                                            <div class="panel panel-general">
                                                @else
                                                    <div class="panel panelUntreated">
                                                        @endif
                                                        <div class="panel-heading">
                                                            <span class="col-lg-5">
                                                            @if(substr_count($loading->atrnr, '-')==0)
                                                                    Details of the loading
                                                                    n°{{ $loading->atrnr }}
                                                                @else
                                                                    Details of the loading
                                                                    n°
                                                                    <a href="{{route('showDetailsLoading', $atrnr1)}}">{{$atrnr1}}</a>
                                                                    -{{$atrnr2}}
                                                                @endif
                                                                </span>
                                                            <span class="col-lg-5 text-left">
                                                                @php($k=0)
                                                                @foreach($listPalletstransfers as $transfer)
                                                                    @php($errorsTransfer= \App\Http\Controllers\PalletstransfersController::actualErrors($transfer))
                                                                    @if(!empty($errorsTransfer)&&$k<10)
                                                                        <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                        @php($k=$k+1)
                                                                    @elseif($k==10)
                                                                        <span class="text-danger">...</span>
                                                                        @php($k=$k+1)
                                                                    @endif
                                                                @endforeach
                                                                </span>
                                                            <span><a
                                                                        href="{{route('showAddSubloading', $loading->atrnr)}}"
                                                                        class=" btn btn-add"><span
                                                                            class="glyphicon glyphicon-plus-sign"></span> Add subloading</a></span>
                                                        </div>
                                                        <div class="panel-body panel-body-general">


                                                            <!--subpanel 1 reading form suming up information from the table-->
                                                            <div class="panel subpanel">
                                                                <div class="panel-heading">
                                                                    <a data-toggle="collapse" href="#Pan1collapse">Information</a>
                                                                </div>
                                                                @if (Session::has('openPanelInformation'))
                                                                    <div id="Pan1collapse"
                                                                         class="panel-collapse in collapse">
                                                                        @else
                                                                            <div id="Pan1collapse"
                                                                                 class="panel-collapse collapse">
                                                                                @endif
                                                                                @if (Session::has('messageUpdateLoading'))
                                                                                    <div class="alert alert-success text-alert text-center">{{ Session::get('messageUpdateLoading') }}</div>
                                                                                @endif
                                                                                <div class="panel-body">
                                                                                    <form class="form-horizontal"
                                                                                          role="form"
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
                                                                                                                @if(substr_count($loading->atrnr, '-')==0)
                                                                                                                    <!--disp-->
                                                                                                                        <div class="col-lg-4">
                                                                                                                            <div class="input-group details-loading">
                                                                                                                                <label for="disp"
                                                                                                                                       class="input-group-addon">Disp
                                                                                                                                    :</label>
                                                                                                                                <input type="text"
                                                                                                                                       name="disp"
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
                                                                                                                        @if(Auth::user()->lastname=='Gundogan'&& Auth::user()->firstname=='Adrien' ||Auth::user()->username=='CamilleS' )
                                                                                                                            <div class="col-lg-2">
                                                                                                                                <div class="input-group details-loading">
                                                                                                                                    <label for="pt"
                                                                                                                                           class="input-group-addon">PT
                                                                                                                                        :</label>

                                                                                                                                    <input type="text"
                                                                                                                                           readonly
                                                                                                                                           name="pt"
                                                                                                                                           class="form-control link"
                                                                                                                                           data-toggle="modal"
                                                                                                                                           data-target="#updatePT_modal"
                                                                                                                                           value="{{ $loading->pt }}">
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        @endif
                                                                                                                    @endif
                                                                                                                </div>
                                                                                                                <div class="form-group">
                                                                                                                    <div class="col-lg-12">
                                                                                                                        <!--auftraggeber-->
                                                                                                                        <div class="input-group details-loading">
                                                                                                                            <label for="auftraggeber"
                                                                                                                                   class="input-group-addon">Auftraggeber
                                                                                                                                :</label>
                                                                                                                            <input type="text"
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
                                                                                                                        <input type="number"
                                                                                                                               name="anz"
                                                                                                                               class="form-control"
                                                                                                                               value="{{ $loading->anz }}"
                                                                                                                               placeholder="anz."
                                                                                                                               min="0"
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
                                                                                                                        <input type="text"
                                                                                                                               name="art"
                                                                                                                               class="form-control"
                                                                                                                               value="{{ $loading->art }}"
                                                                                                                               placeholder="art"
                                                                                                                               required
                                                                                                                               data-toggle="tooltip"
                                                                                                                               data-placement="top"
                                                                                                                               title="Art">
                                                                                                                    </div>
                                                                                                                    <div class="col-lg-1 details-loading text-center">
                                                                                                                        -
                                                                                                                    </div>
                                                                                                                    <!--ware-->
                                                                                                                    <div class="col-lg-5 details-loading">
                                                                                                                        <input type="text"
                                                                                                                               name="ware"
                                                                                                                               class="form-control"
                                                                                                                               value="{{ $loading->ware }}"
                                                                                                                               placeholder="ware"
                                                                                                                               required
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
                                                                                                <a class="col-lg-3 text-left"
                                                                                                   data-toggle="collapse"
                                                                                                   href="#PanSub1collapse">Loading</a>
                                                                                                <div class="col-lg-5 col-lg-offset-1 text-right details-loading">
                                                                                                    <input
                                                                                                            type="date"
                                                                                                            name="ladedatum"
                                                                                                            class="form-control  text-center"
                                                                                                            value="{{ $loading->ladedatum }}"
                                                                                                            placeholder="ladedatum"
                                                                                                            required>
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
                                                                                                    <input type="date"
                                                                                                           name="entladedatum"
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
                                                                                    <div class="modal fade"
                                                                                         id="updatePT_modal"
                                                                                         role="dialog">
                                                                                        <div class="modal-dialog modal-lg">
                                                                                            <div class="modal-content">
                                                                                                <div class="modal-header">
                                                                                                    <button type="button"
                                                                                                            class="close"
                                                                                                            data-dismiss="modal">
                                                                                                        &times;
                                                                                                    </button>
                                                                                                    <h4 class="modal-title">
                                                                                                        Why
                                                                                                        would you like
                                                                                                        to change the
                                                                                                        loading
                                                                                                        into a
                                                                                                        loading WITHOUT
                                                                                                        exchange
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
                                                                                                                        <h4>
                                                                                                                            If
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
                                                                    <a data-toggle="collapse" href="#Pan2collapse">Pallets
                                                                        location ?</a>
                                                                    <span class="col-lg-offset-3">
order : {{$loading->anz}}
                                </span>
                                                                    <span class="col-lg-offset-3">
truck : {{$theoricalNumberPalletsTruck}} (planned) - {{$realNumberPalletsTruck}} (confirmed)
                                </span>

                                                                </div>
                                                                @if(Session::has('openPanelPallets'))
                                                                    <div id="Pan2collapse"
                                                                         class="panel-collapse in collapse">
                                                                        @else
                                                                            <div id="Pan2collapse"
                                                                                 class="panel-collapse collapse">
                                                                                @endif
                                                                                <div class="panel-body">
                                                                                    <form class="form-horizontal"
                                                                                          role="form"
                                                                                          method="POST"
                                                                                          action="{{route('submitUpdateUpload', $loading->atrnr)}}"
                                                                                          enctype="multipart/form-data">
                                                                                        <input type="hidden"
                                                                                               name="_token"
                                                                                               value="{{ csrf_token() }}">
                                                                                        <!--msg-->
                                                                                        <div class="row">
                                                                                            @if(Session::has('messageAddPalletstransfer'))
                                                                                                <div class="alert alert-success text-alert text-center">{{ Session::get('messageAddPalletstransfer') }}</div>
                                                                                            @elseif(Session::has('messageDeletePalletstransfer'))
                                                                                                <div class="alert alert-success text-alert text-center">{{ Session::get('messageDeletePalletstransfer') }}</div>
                                                                                            @elseif(Session::has('messageSubmitPalletstransfer'))
                                                                                                <div class="alert alert-success text-alert text-center">{{ Session::get('messageSubmitPalletstransfer') }}</div>
                                                                                            @elseif(Session::has('messageUpdateValidatePalletstransfer'))
                                                                                                <div class="alert alert-success text-alert text-center">{{ Session::get('messageUpdateValidatePalletstransfer') }}</div>
                                                                                            @endif
                                                                                        </div>
                                                                                        <!--show add form-->
                                                                                        <div class="row">
                                                                                            <div class="from-group">
                                                                                                <div class="col-lg-4 col-lg-offset-4">
                                                                                                    <button type="submit"
                                                                                                            class="btn btn-add btn-block"
                                                                                                            value="addTransferForm"
                                                                                                            name="addTransferForm"
                                                                                                            data-toggle="collapse"
                                                                                                            data-target="#addForm">
                                                                                                        Add transfer
                                                                                                    </button>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <br>

                                                                                        <!--Add form-->
                                                                                        <div id="addForm"
                                                                                             class="row collapse in">
                                                                                            @if(isset($addTransferForm)||isset($addPalletstransfer)||isset($showAddCorrectingTransfer))
                                                                                                <div class="panel subpanel">
                                                                                                    <div class="panel-body">
                                                                                                        @if(isset($showAddCorrectingTransfer))
                                                                                                            <div class="form-group">
                                                                                                                <div class="text-center">
                                                                                                                    <label for="legend"
                                                                                                                           class="control-label"><span
                                                                                                                                class="glyphicon glyphicon-check"> </span>
                                                                                                                        CORRECTING
                                                                                                                        TRANSFER</label>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        @else
                                                                                                            <div class="form-group">
                                                                                                                <div class="text-center">
                                                                                                                    <label for="legend"
                                                                                                                           class="control-label">NORMAL
                                                                                                                        TRANSFER</label>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        @endif
                                                                                                        <div class="form-group">
                                                                                                            <!--type-->
                                                                                                            <div class="col-lg-1 col-lg-offset-1">
                                                                                                                <label for="type"
                                                                                                                       class="control-label"><span>*</span>Type
                                                                                                                    :</label>
                                                                                                            </div>
                                                                                                            <div class="col-lg-2">
                                                                                                                @if(isset($showAddCorrectingTransfer))
                                                                                                                    <select class="selectpicker show-tick form-control"
                                                                                                                            data-size="5"
                                                                                                                            data-live-search="true"
                                                                                                                            data-live-search-style="startsWith"
                                                                                                                            title="Type"
                                                                                                                            name="type"
                                                                                                                            id="typeL"
                                                                                                                            onchange="displayFieldsTypeCorrecting(this);">
                                                                                                                        @if(Illuminate\Support\Facades\Input::old('type'))
                                                                                                                            <optgroup
                                                                                                                                    label="Correcting">
                                                                                                                                <option @if(old('type') == 'Purchase-Sale') selected
                                                                                                                                        @endif value="Purchase-Sale"
                                                                                                                                        id="Purchase-SaleOptionL">
                                                                                                                                    Purchase-Sale
                                                                                                                                </option>
                                                                                                                                <option @if(old('type') == 'Other') selected
                                                                                                                                        @endif value="Other"
                                                                                                                                        id="OtherOptionL">
                                                                                                                                    Other
                                                                                                                                </option>
                                                                                                                            </optgroup>
                                                                                                                        @elseif(isset($type))
                                                                                                                            <optgroup
                                                                                                                                    label="Correcting">
                                                                                                                                <option @if($type == 'Purchase-Sale') selected
                                                                                                                                        @endif value="Purchase-Sale"
                                                                                                                                        id="Purchase-SaleOptionL">
                                                                                                                                    Purchase-Sale
                                                                                                                                </option>
                                                                                                                                <option @if($type == 'Other') selected
                                                                                                                                        @endif value="Other"
                                                                                                                                        id="OtherOptionL">
                                                                                                                                    Other
                                                                                                                                </option>
                                                                                                                            </optgroup>
                                                                                                                        @else
                                                                                                                            <optgroup
                                                                                                                                    label="Correcting">
                                                                                                                                <option value="Purchase-Sale"
                                                                                                                                        id="Purchase-SaleOptionL">
                                                                                                                                    Purchase-Sale
                                                                                                                                </option>
                                                                                                                                <option value="Other"
                                                                                                                                        id="otherOptionL">
                                                                                                                                    Other
                                                                                                                                </option>
                                                                                                                            </optgroup>
                                                                                                                        @endif
                                                                                                                    </select>
                                                                                                                @else
                                                                                                                    <select class="selectpicker show-tick form-control"
                                                                                                                            data-size="5"
                                                                                                                            data-live-search="true"
                                                                                                                            data-live-search-style="startsWith"
                                                                                                                            title="Type"
                                                                                                                            name="type"
                                                                                                                            id="typeL"
                                                                                                                            onchange="displayFieldsTypeNormal(this);">
                                                                                                                        @if(Illuminate\Support\Facades\Input::old('type'))
                                                                                                                            <optgroup
                                                                                                                                    label="Normal">
                                                                                                                                <option @if(old('type') == 'Deposit-Withdrawal') selected
                                                                                                                                        @endif value="Deposit-Withdrawal"
                                                                                                                                        id="Deposit-WithdrawalOptionL">
                                                                                                                                    Deposit-Withdrawal
                                                                                                                                </option>
                                                                                                                                <option @if(old('type') == 'Withdrawal-Deposit') selected
                                                                                                                                        @endif value="Withdrawal-Deposit"
                                                                                                                                        id="Withdrawal-DepositOptionL">
                                                                                                                                    Withdrawal-Deposit
                                                                                                                                </option>
                                                                                                                                <option @if(old('type') == 'Deposit_Only') selected
                                                                                                                                        @endif value="Deposit_Only"
                                                                                                                                        id="Deposit_OnlyOptionL">
                                                                                                                                    Deposit_Only
                                                                                                                                </option>
                                                                                                                                <option @if(old('type') == 'Withdrawal_Only') selected
                                                                                                                                        @endif value="Withdrawal_Only"
                                                                                                                                        id="Withdrawal_OnlyOptionL">
                                                                                                                                    Withdrawal_Only
                                                                                                                                </option>
                                                                                                                            </optgroup>
                                                                                                                        @elseif(isset($type))
                                                                                                                            <optgroup
                                                                                                                                    label="Normal">
                                                                                                                                <option @if($type == 'Deposit-Withdrawal') selected
                                                                                                                                        @endif value="Deposit-Withdrawal"
                                                                                                                                        id="Deposit-WithdrawalOptionL">
                                                                                                                                    Deposit-Withdrawal
                                                                                                                                </option>
                                                                                                                                <option @if($type == 'Withdrawal-Deposit') selected
                                                                                                                                        @endif value="Withdrawal-Deposit"
                                                                                                                                        id="Withdrawal-DepositOptionL">
                                                                                                                                    Withdrawal-Deposit
                                                                                                                                </option>
                                                                                                                                <option @if($type == 'Deposit_Only') selected
                                                                                                                                        @endif value="Deposit_Only"
                                                                                                                                        id="Deposit_OnlyOptionL">
                                                                                                                                    Deposit_Only
                                                                                                                                </option>
                                                                                                                                <option @if($type == 'Withdrawal_Only') selected
                                                                                                                                        @endif value="Withdrawal_Only"
                                                                                                                                        id="Withdrawal_OnlyOptionL">
                                                                                                                                    Withdrawal_Only
                                                                                                                                </option>
                                                                                                                            </optgroup>
                                                                                                                        @else
                                                                                                                            <optgroup
                                                                                                                                    label="Normal">
                                                                                                                                <option value="Deposit-Withdrawal"
                                                                                                                                        id="Deposit-WithdrawalOptionL">
                                                                                                                                    Deposit-Withdrawal
                                                                                                                                </option>
                                                                                                                                <option value="Withdrawal-Deposit"
                                                                                                                                        id="Withdrawal-DepositOptionL">
                                                                                                                                    Withdrawal-Deposit
                                                                                                                                </option>
                                                                                                                                <option value="Deposit_Only"
                                                                                                                                        id="Deposit_OnlyOptionL">
                                                                                                                                    Deposit_Only
                                                                                                                                </option>
                                                                                                                                <option value="Withdrawal_Only"
                                                                                                                                        id="Withdrawal_OnlyOptionL">
                                                                                                                                    Withdrawal_Only
                                                                                                                                </option>
                                                                                                                            </optgroup>
                                                                                                                        @endif
                                                                                                                    </select>
                                                                                                                @endif
                                                                                                            </div>
                                                                                                            <!--details-->
                                                                                                            <div class="col-lg-6">
                                                                                                                @if(isset($details))
                                                                                                                    <textarea
                                                                                                                            class="form-control"
                                                                                                                            name="details"
                                                                                                                            rows="1"
                                                                                                                            placeholder="Details (broken pallets, gift, receipt...)">{{$details}}</textarea>
                                                                                                                @else
                                                                                                                    <textarea
                                                                                                                            class="form-control"
                                                                                                                            name="details"
                                                                                                                            rows="1"
                                                                                                                            placeholder="Details (broken pallets, gift, receipt...)">{{old('details')}}</textarea>
                                                                                                                @endif
                                                                                                            </div>
                                                                                                            <!--close add form-->
                                                                                                            <div class="col-lg-offset-11">
                                                                                                                <button type="submit"
                                                                                                                        class="btn glyphicon glyphicon-remove"
                                                                                                                        value="close"
                                                                                                                        name="closeSubmitAddModal"
                                                                                                                ></button>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <!--deposit-->
                                                                                                        @if(isset($type) && $type=='Deposit-Withdrawal')
                                                                                                            <div class="form-group"
                                                                                                                 id="deposit-withdrawal1L"
                                                                                                                 style="display:block">
                                                                                                                @else
                                                                                                                    <div class="form-group"
                                                                                                                         id="deposit-withdrawal1L"
                                                                                                                         style="display:none;">
                                                                                                                        @endif
                                                                                                                        <div class="col-lg-12 text-center">
                                                                                                                            <label for="deposit"
                                                                                                                                   class="control-label">DEPOSIT</label>
                                                                                                                        </div>
                                                                                                                        @if(isset($type) &&$type=='Deposit-Withdrawal')
                                                                                                                    </div>
                                                                                                                    @else
                                                                                                            </div>
                                                                                                        @endif
                                                                                                    <!--withdrawal-->
                                                                                                        @if(isset($type) &&$type=='Withdrawal-Deposit')
                                                                                                            <div class="form-group"
                                                                                                                 id="withdrawal-deposit1L"
                                                                                                                 style="display:block">
                                                                                                                @else
                                                                                                                    <div class="form-group"
                                                                                                                         id="withdrawal-deposit1L"
                                                                                                                         style="display:none;">
                                                                                                                        @endif
                                                                                                                        <div class="col-lg-12 text-center">
                                                                                                                            <label for="withdrawal"
                                                                                                                                   class="control-label">WITHDRAWAL</label>
                                                                                                                        </div>
                                                                                                                        @if(isset($type) &&$type=='Withdrawal-Deposit')
                                                                                                                    </div>
                                                                                                                    @else
                                                                                                            </div>
                                                                                                        @endif
                                                                                                    <!--purchase-->
                                                                                                        @if(isset($type) &&$type=='Purchase-Sale')
                                                                                                            <div class="form-group"
                                                                                                                 id="purchase-sale1L"
                                                                                                                 style="display:block">
                                                                                                                @else
                                                                                                                    <div class="form-group"
                                                                                                                         id="purchase-sale1L"
                                                                                                                         style="display:none;">
                                                                                                                        @endif
                                                                                                                        <div class="col-lg-12 text-center">
                                                                                                                            <label for="purchase"
                                                                                                                                   class="control-label">PURCHASE
                                                                                                                            </label>
                                                                                                                        </div>
                                                                                                                        @if(isset($type) &&$type=='Purchase-Sale')
                                                                                                                    </div>
                                                                                                                    @else
                                                                                                            </div>
                                                                                                        @endif
                                                                                                        <div class="form-group">
                                                                                                            <!--number of pallets-->
                                                                                                            <div class="col-lg-2">
                                                                                                                <label for="palletsNumber"
                                                                                                                       class="control-label"><span>*</span>
                                                                                                                    Pallets
                                                                                                                    number
                                                                                                                    :</label>
                                                                                                            </div>
                                                                                                            <div class="col-lg-1">
                                                                                                                @if(Illuminate\Support\Facades\Input::old('palletsNumber'))
                                                                                                                    <input id="palletsNumber"
                                                                                                                           type="number"
                                                                                                                           class="form-control"
                                                                                                                           name="palletsNumber"
                                                                                                                           value="{{ old('palletsNumber') }}"
                                                                                                                           placeholder="Nbr"
                                                                                                                           min="0"
                                                                                                                           autofocus>
                                                                                                                @elseif(isset($palletsNumber))
                                                                                                                    <input id="palletsNumber"
                                                                                                                           type="number"
                                                                                                                           class="form-control"
                                                                                                                           name="palletsNumber"
                                                                                                                           value="{{$palletsNumber}}"
                                                                                                                           placeholder="Nbr"
                                                                                                                           min="0"
                                                                                                                           autofocus>
                                                                                                                @else
                                                                                                                    <input id="palletsNumber"
                                                                                                                           type="number"
                                                                                                                           class="form-control"
                                                                                                                           name="palletsNumber"
                                                                                                                           value="0"
                                                                                                                           placeholder="Nbr"
                                                                                                                           min="0"
                                                                                                                           autofocus>
                                                                                                                @endif
                                                                                                            </div>
                                                                                                            <!--date-->
                                                                                                            <div class="col-lg-1">
                                                                                                                <label for="date"
                                                                                                                       class="control-label">Date
                                                                                                                    :</label>
                                                                                                            </div>
                                                                                                            <div class="col-lg-2">
                                                                                                                    <input id="date"
                                                                                                                           type="date"
                                                                                                                           class="form-control"
                                                                                                                           name="date"
                                                                                                                           value="{{ $loading->ladedatum }}"
                                                                                                                           placeholder="Date"
                                                                                                                           autofocus>
                                                                                                            </div>
                                                                                                            <!--transfer normal associated-->
                                                                                                            @if(isset($showAddCorrectingTransfer))
                                                                                                                <div class="col-lg-2 text-right">
                                                                                                                    <label for="normalTransferAssociated"
                                                                                                                           class="control-label"><span>*</span>
                                                                                                                        Associated
                                                                                                                        :</label>
                                                                                                                </div>
                                                                                                                <div class="col-lg-1">

                                                                                                                    <select class="selectpicker show-tick form-control"
                                                                                                                            data-size="5"
                                                                                                                            data-live-search="true"
                                                                                                                            data-live-search-style="startsWith"
                                                                                                                            title="Normal transfer associated"
                                                                                                                            name="normalTransferAssociated">
                                                                                                                        @foreach($listPalletstransfersNormal as $normalTransfer )
                                                                                                                            @if(Illuminate\Support\Facades\Input::old('normalTransferAssociated') && $normalTransfer->id==old('normalTransferAssociated'))
                                                                                                                                <option value="{{$normalTransfer->id}}"
                                                                                                                                        selected>{{$normalTransfer->id}}</option>
                                                                                                                            @elseif(isset($normalTransferAssociated)&&$normalTransfer->id==$normalTransferAssociated)
                                                                                                                                <option value="{{$normalTransfer->id}}"
                                                                                                                                        selected>{{$normalTransfer->id}}</option>
                                                                                                                            @elseif(!isset($normalTransferAssociated)&& $showAddCorrectingTransfer==$normalTransfer->id)
                                                                                                                                <option value="{{$normalTransfer->id}}"
                                                                                                                                        selected>{{$normalTransfer->id}}</option>
                                                                                                                            @else
                                                                                                                                <option value="{{$normalTransfer->id}}">{{$normalTransfer->id}}</option>
                                                                                                                            @endif
                                                                                                                        @endforeach
                                                                                                                    </select>
                                                                                                                </div>
                                                                                                                <!--add pallet account-->
                                                                                                                <div class="col-lg-2 col-lg-offset-1 text-left">
                                                                                                                    <a href="{{route('showAddPalletsaccount')}}"
                                                                                                                       class="link"><span
                                                                                                                                class="glyphicon glyphicon-plus-sign"></span>
                                                                                                                        Add
                                                                                                                        account</a>
                                                                                                                </div>
                                                                                                            @else
                                                                                                            <!--add pallet account-->
                                                                                                                <div class="col-lg-2 col-lg-offset-3 text-left">
                                                                                                                    <a href="{{route('showAddPalletsaccount')}}"
                                                                                                                       class="link"><span
                                                                                                                                class="glyphicon glyphicon-plus-sign"></span>
                                                                                                                        Add
                                                                                                                        account</a>
                                                                                                                </div>
                                                                                                            @endif
                                                                                                        </div>
                                                                                                        <!--errors-->
                                                                                                        <div class="form-group">
                                                                                                            @if(Session::has('errorFields'))
                                                                                                                <div class="alert alert-danger text-alert text-center">{{ Session::get('errorFields') }}</div>
                                                                                                            @elseif(Session::has('errorType'))
                                                                                                                <div class="alert alert-danger text-alert text-center">{{ Session::get('errorType') }}</div>
                                                                                                            @endif
                                                                                                        </div>
                                                                                                        <div class="form-group">
                                                                                                            <!--debit account-->
                                                                                                            <div class="col-lg-2">
                                                                                                                <label for="debitAccount"
                                                                                                                       class="control-label"><span>*</span>
                                                                                                                    Debit
                                                                                                                    account
                                                                                                                    :</label>
                                                                                                            </div>
                                                                                                            @if(!isset($type))
                                                                                                                <div class="col-lg-4"
                                                                                                                     id="debitAccount0"
                                                                                                                     style="display: block">
                                                                                                                    @else
                                                                                                                        <div class="col-lg-4"
                                                                                                                             id="debitAccount0"
                                                                                                                             style="display:none;">
                                                                                                                            @endif
                                                                                                                            <select class="selectpicker show-tick form-control"
                                                                                                                                    data-size="10"
                                                                                                                                    data-live-search="true"
                                                                                                                                    data-live-search-style="startsWith"
                                                                                                                                    title="Debit Account"
                                                                                                                                    name="debitAccount"
                                                                                                                                    disabled="true"
                                                                                                                            >
                                                                                                                                <option></option>
                                                                                                                            </select>
                                                                                                                            @if(!isset($type))
                                                                                                                        </div>
                                                                                                                        @else
                                                                                                                </div>
                                                                                                            @endif

                                                                                                            @if(isset($type) && ($type=='Withdrawal_Only' || $type=='Other'))
                                                                                                                <div class="col-lg-4"
                                                                                                                     id="debitAccount3"
                                                                                                                     style="display: block">
                                                                                                                    @else
                                                                                                                        <div class="col-lg-4"
                                                                                                                             id="debitAccount3"
                                                                                                                             style="display:none;">
                                                                                                                            @endif
                                                                                                                            <select class="selectpicker show-tick form-control"
                                                                                                                                    data-size="10"
                                                                                                                                    data-live-search="true"
                                                                                                                                    data-live-search-style="startsWith"
                                                                                                                                    id="select-debit3b"
                                                                                                                                    title="Debit Account"
                                                                                                                                    name="debitAccount3b"
                                                                                                                                    onchange="selectAccount(this.value, '3b');">
                                                                                                                                @foreach($listPalletsAccounts as $palletsAccount )
                                                                                                                                    @if(Illuminate\Support\Facades\Input::old('debitAccount3') && (strpos(old('debitAccount3'), '-') == 7 && explode('-', old('debitAccount3'))[0] == 'account') && ($palletsAccount->id==explode('-', old('debitAccount3'))[1]))
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
                                                                                                                                    @if(Illuminate\Support\Facades\Input::old('debitAccount3') && (strpos(old('debitAccount3'), '-') == 5 && explode('-', old('debitAccount3'))[0] == 'truck') && ($trucksAccount->id==explode('-', old('debitAccount3'))[1]))
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
                                                                                                                            </select>
                                                                                                                            @if(isset($type) && ($type=='Withdrawal_Only' || $type=='Other'))
                                                                                                                        </div>
                                                                                                                        @else
                                                                                                                </div>
                                                                                                            @endif

                                                                                                            @if(isset($type) && ($type=='Deposit_Only'||$type=='Deposit-Withdrawal'))
                                                                                                                <div class="col-lg-4"
                                                                                                                     id="debitAccount1"
                                                                                                                     style="display: block">
                                                                                                                    @else
                                                                                                                        <div class="col-lg-4"
                                                                                                                             id="debitAccount1"
                                                                                                                             style="display:none;">
                                                                                                                            @endif
                                                                                                                            <select class="selectpicker show-tick form-control"
                                                                                                                                    data-size="10"
                                                                                                                                    data-live-search="true"
                                                                                                                                    data-live-search-style="startsWith"
                                                                                                                                    id="select-debit1b"
                                                                                                                                    title="Debit Account"
                                                                                                                                    name="debitAccount1b"
                                                                                                                                    onchange="selectAccount(this.value, '1b');">
                                                                                                                                @foreach($listTrucksPossible as $trucksAccount )
                                                                                                                                    @if(Illuminate\Support\Facades\Input::old('debitAccount1') && (strpos(old('debitAccount1'), '-') == 5 && explode('-', old('debitAccount1'))[0] == 'truck') && ($trucksAccount->id==explode('-', old('debitAccount1'))[1]))
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
                                                                                                                            </select>
                                                                                                                            @if(isset($type) && ($type=='Deposit_Only'||$type=='Deposit-Withdrawal'))
                                                                                                                        </div>
                                                                                                                        @else
                                                                                                                </div>
                                                                                                            @endif

                                                                                                            @if(isset($type) && $type=='Withdrawal-Deposit')
                                                                                                                <div class="col-lg-4"
                                                                                                                     id="debitAccount2"
                                                                                                                     style="display: block">
                                                                                                                    @else
                                                                                                                        <div class="col-lg-4"
                                                                                                                             id="debitAccount2"
                                                                                                                             style="display:none;">
                                                                                                                            @endif
                                                                                                                            <select class="selectpicker show-tick form-control"
                                                                                                                                    data-size="10"
                                                                                                                                    data-live-search="true"
                                                                                                                                    data-live-search-style="startsWith"
                                                                                                                                    id="select-debit2b"
                                                                                                                                    title="Debit Account"
                                                                                                                                    name="debitAccount2b"
                                                                                                                                    onchange="selectAccount(this.value, '2b');">
                                                                                                                                @foreach($listPalletsAccounts as $palletsAccount )
                                                                                                                                    @if(Illuminate\Support\Facades\Input::old('debitAccount2b') && (strpos(old('debitAccount2b'), '-') == 7 && explode('-', old('debitAccount2b'))[0] == 'account') && ($palletsAccount->id==explode('-', old('debitAccount2b'))[1]))
                                                                                                                                        <option value="account-{{$palletsAccount->id}}"
                                                                                                                                                selected>{{$palletsAccount->name}}</option>
                                                                                                                                    @elseif(isset($debitAccount) && (strpos($debitAccount, '-') == 7 && explode('-', $debitAccount)[0] == 'account') && ($palletsAccount->id==explode('-', $debitAccount)[1]))
                                                                                                                                        <option value="account-{{$palletsAccount->id}}"
                                                                                                                                                selected>{{$palletsAccount->name}}</option>
                                                                                                                                    @else
                                                                                                                                        <option value="account-{{$palletsAccount->id}}">{{$palletsAccount->name}}</option>
                                                                                                                                    @endif
                                                                                                                                @endforeach
                                                                                                                            </select>
                                                                                                                            @if(isset($type)&& $type=='Withdrawal-Deposit')
                                                                                                                        </div>
                                                                                                                        @else
                                                                                                                </div>
                                                                                                            @endif

                                                                                                            @if(isset($type) && $type=='Purchase-Sale')
                                                                                                                <div class="col-lg-4"
                                                                                                                     id="debitAccount4"
                                                                                                                     style="display: block">
                                                                                                                    @else
                                                                                                                        <div class="col-lg-4"
                                                                                                                             id="debitAccount4"
                                                                                                                             style="display:none;">
                                                                                                                            @endif
                                                                                                                            <select class="selectpicker show-tick form-control"
                                                                                                                                    data-size="10"
                                                                                                                                    data-live-search="true"
                                                                                                                                    data-live-search-style="startsWith"
                                                                                                                                    id="select-debit4b"
                                                                                                                                    title="Debit Account"
                                                                                                                                    name="debitAccount4b"
                                                                                                                                    onchange="selectAccount(this.value, '4b');">
                                                                                                                                @if(isset($debitAccountCorr)&& isset($creditAccountCorr))
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
                                                                                                                                @elseif(isset($debitAccount))
                                                                                                                                    @if (strpos($debitAccount, '-') == 5 && explode('-', $debitAccount)[0] == 'truck')
                                                                                                                                        @php($nameTruckAccount = App\Truck::where('id', explode('-', $debitAccount)[1])->value('name'))
                                                                                                                                        @php($licensePlate = App\Truck::where('id', explode('-', $debitAccount)[1])->value('licensePlate'))
                                                                                                                                        <option selected
                                                                                                                                                value="truck-{{explode('-', $debitAccount)[1]}}">{{$nameTruckAccount}}
                                                                                                                                            - {{$licensePlate}}</option>
                                                                                                                                    @elseif (strpos($debitAccount, '-') == 7 && explode('-', $debitAccount)[0] == 'account')
                                                                                                                                        @php($namePalletsAccount = App\Palletsaccount::where('id', explode('-', $debitAccount)[1])->value('name'))
                                                                                                                                        <option selected
                                                                                                                                                value="account-{{explode('-', $debitAccount)[1]}}">{{$namePalletsAccount}}</option>
                                                                                                                                    @endif
                                                                                                                                @endif
                                                                                                                            </select>
                                                                                                                            @if(isset($type)&& $type=='Purchase-Sale')
                                                                                                                        </div>
                                                                                                                        @else
                                                                                                                </div>
                                                                                                            @endif

                                                                                                        <!--credit account-->
                                                                                                            <div class="col-lg-2">
                                                                                                                <label for="creditAccount"
                                                                                                                       class="control-label"><span>*</span>
                                                                                                                    Credit
                                                                                                                    account
                                                                                                                    :</label>
                                                                                                            </div>

                                                                                                            @if(!isset($type))
                                                                                                                <div class="col-lg-4"
                                                                                                                     id="creditAccount0"
                                                                                                                     style="display: block">
                                                                                                                    @else
                                                                                                                        <div class="col-lg-4"
                                                                                                                             id="creditAccount0"
                                                                                                                             style="display:none;">
                                                                                                                            @endif
                                                                                                                            <select class="selectpicker show-tick form-control"
                                                                                                                                    data-size="10"
                                                                                                                                    data-live-search="true"
                                                                                                                                    data-live-search-style="startsWith"
                                                                                                                                    title="Credit Account"
                                                                                                                                    name="creditAccount"
                                                                                                                                    disabled="true">
                                                                                                                                <option></option>
                                                                                                                            </select>
                                                                                                                            @if(!isset($type))
                                                                                                                        </div>
                                                                                                                        @else
                                                                                                                </div>
                                                                                                            @endif

                                                                                                            @if(isset($type) && ($type=='Deposit_Only'||$type=='Other'))
                                                                                                                <div class="col-lg-4"
                                                                                                                     id="creditAccount3"
                                                                                                                     style="display: block">
                                                                                                                    @else
                                                                                                                        <div class="col-lg-4"
                                                                                                                             id="creditAccount3"
                                                                                                                             style="display:none;">
                                                                                                                            @endif
                                                                                                                            <select class="selectpicker show-tick form-control"
                                                                                                                                    data-size="10"
                                                                                                                                    data-live-search="true"
                                                                                                                                    data-live-search-style="startsWith"
                                                                                                                                    title="Credit Account"
                                                                                                                                    name="creditAccount3b"
                                                                                                                                    id="select-credit3b"
                                                                                                                                    onchange="creditaccount(this.value, '3b');">
                                                                                                                                @foreach($listPalletsAccounts as $palletsAccount )
                                                                                                                                    @if(Illuminate\Support\Facades\Input::old('creditAccount3') && (strpos(old('creditAccount3'), '-') == 7 && explode('-', old('creditAccount3'))[0] == 'account') && ($palletsAccount->id==explode('-', old('creditAccount3'))[1]))
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
                                                                                                                                    @if(Illuminate\Support\Facades\Input::old('creditAccount3') && (strpos(old('creditAccount3'), '-') == 5 && explode('-', old('creditAccount3'))[0] == 'truck') && ($trucksAccount->id==explode('-', old('creditAccount3'))[1]))
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
                                                                                                                            </select>
                                                                                                                            @if(isset($type)&& ($type=='Deposit_Only'||$type=='Other'))
                                                                                                                        </div>
                                                                                                                        @else
                                                                                                                </div>
                                                                                                            @endif

                                                                                                            @if(isset($type) && ($type=='Withdrawal_Only'||$type=='Withdrawal-Deposit'))
                                                                                                                <div class="col-lg-4"
                                                                                                                     id="creditAccount1"
                                                                                                                     style="display: block">
                                                                                                                    @else
                                                                                                                        <div class="col-lg-4"
                                                                                                                             id="creditAccount1"
                                                                                                                             style="display:none;">
                                                                                                                            @endif
                                                                                                                            <select class="selectpicker show-tick form-control"
                                                                                                                                    data-size="10"
                                                                                                                                    data-live-search="true"
                                                                                                                                    data-live-search-style="startsWith"
                                                                                                                                    title="Credit Account"
                                                                                                                                    name="creditAccount1b"
                                                                                                                                    id="select-credit1b"
                                                                                                                                    onchange="creditaccount(this.value, '1b');">
                                                                                                                                @foreach($listTrucksPossible as $trucksAccount )
                                                                                                                                    @if(Illuminate\Support\Facades\Input::old('creditAccount1') && (strpos(old('creditAccount1'), '-') == 5 && explode('-', old('creditAccount1'))[0] == 'truck') && ($trucksAccount->id==explode('-', old('creditAccount1'))[1]))
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
                                                                                                                            </select>
                                                                                                                            @if(isset($type) && ($type=='Withdrawal_Only'||$type=='Withdrawal-Deposit'))
                                                                                                                        </div>
                                                                                                                        @else
                                                                                                                </div>
                                                                                                            @endif

                                                                                                            @if(isset($type) && $type=='Deposit-Withdrawal')
                                                                                                                <div class="col-lg-4"
                                                                                                                     id="creditAccount2"
                                                                                                                     style="display: block">
                                                                                                                    @else
                                                                                                                        <div class="col-lg-4"
                                                                                                                             id="creditAccount2"
                                                                                                                             style="display:none;">
                                                                                                                            @endif
                                                                                                                            <select class="selectpicker show-tick form-control"
                                                                                                                                    data-size="10"
                                                                                                                                    data-live-search="true"
                                                                                                                                    data-live-search-style="startsWith"
                                                                                                                                    title="Credit Account"
                                                                                                                                    name="creditAccount2b"
                                                                                                                                    id="select-credit2b"
                                                                                                                                    onchange="creditaccount(this.value, '2b');">
                                                                                                                                @foreach($listPalletsAccounts as $palletsAccount )
                                                                                                                                    @if(Illuminate\Support\Facades\Input::old('creditAccount2b') && (strpos(old('creditAccount2b'), '-') == 7 && explode('-', old('creditAccount2b'))[0] == 'account') && ($palletsAccount->id==explode('-', old('creditAccount2b'))[1]))
                                                                                                                                        <option value="account-{{$palletsAccount->id}}"
                                                                                                                                                selected>{{$palletsAccount->name}}</option>
                                                                                                                                    @elseif(isset($creditAccount) && (strpos($creditAccount, '-') == 7 && explode('-', $creditAccount)[0] == 'account') && ($palletsAccount->id==explode('-', $creditAccount)[1]))
                                                                                                                                        <option value="account-{{$palletsAccount->id}}"
                                                                                                                                                selected>{{$palletsAccount->name}}</option>
                                                                                                                                    @else
                                                                                                                                        <option value="account-{{$palletsAccount->id}}">{{$palletsAccount->name}}</option>
                                                                                                                                    @endif
                                                                                                                                @endforeach
                                                                                                                            </select>
                                                                                                                            @if(isset($type)&&  $type=='Deposit-Withdrawal')
                                                                                                                        </div>
                                                                                                                        @else
                                                                                                                </div>
                                                                                                            @endif

                                                                                                            @if(isset($type) && $type=='Purchase-Sale')
                                                                                                                <div class="col-lg-4"
                                                                                                                     id="creditAccount4"
                                                                                                                     style="display: block">
                                                                                                                    @else
                                                                                                                        <div class="col-lg-4"
                                                                                                                             id="creditAccount4"
                                                                                                                             style="display:none;">
                                                                                                                            @endif
                                                                                                                            <select class="selectpicker show-tick form-control"
                                                                                                                                    data-size="10"
                                                                                                                                    data-live-search="true"
                                                                                                                                    data-live-search-style="startsWith"
                                                                                                                                    title="Credit Account"
                                                                                                                                    name="creditAccount4b"
                                                                                                                                    id="select-credit4b"
                                                                                                                                    onchange="creditaccount(this.value, '4b');">
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
                                                                                                                                @elseif(isset($creditAccount))
                                                                                                                                    @if (strpos($creditAccount, '-') == 5 && explode('-', $creditAccount)[0] == 'truck')
                                                                                                                                        @php($nameTruckAccount = App\Truck::where('id', explode('-', $creditAccount)[1])->value('name'))
                                                                                                                                        @php($licensePlate = App\Truck::where('id', explode('-', $creditAccount)[1])->value('licensePlate'))
                                                                                                                                        <option selected
                                                                                                                                                value="truck-{{explode('-', $creditAccount)[1]}}">{{$nameTruckAccount}}
                                                                                                                                            - {{$licensePlate}}</option>
                                                                                                                                    @elseif (strpos($creditAccount, '-') == 7 && explode('-', $creditAccount)[0] == 'account')
                                                                                                                                        @php($namePalletsAccount = App\Palletsaccount::where('id', explode('-', $creditAccount)[1])->value('name'))
                                                                                                                                        <option selected
                                                                                                                                                value="account-{{explode('-', $creditAccount)[1]}}">{{$namePalletsAccount}}</option>
                                                                                                                                    @endif
                                                                                                                                @endif
                                                                                                                            </select>
                                                                                                                            @if(isset($type)&& $type=='Purchase-Sale')
                                                                                                                        </div>
                                                                                                                        @else
                                                                                                                </div>
                                                                                                            @endif

                                                                                                        </div>
                                                                                                        <div class="form-group">
                                                                                                            @if(Session::has('errorFields2'))
                                                                                                                <div class="alert alert-danger text-alert text-center">{{ Session::get('errorFields2') }}</div>
                                                                                                            @endif
                                                                                                        </div>
                                                                                                        <!--withdrawal-->
                                                                                                        @if(isset($type) &&$type=='Deposit-Withdrawal')
                                                                                                            <div id="deposit-withdrawal2L"
                                                                                                                 style="display:block">
                                                                                                                @else
                                                                                                                    <div id="deposit-withdrawal2L"
                                                                                                                         style="display:none;">
                                                                                                                        @endif
                                                                                                                        <div class="form-group">
                                                                                                                            <div class="col-lg-12 text-center">
                                                                                                                                <label for="withdrawal"
                                                                                                                                       class="control-label">WITHDRAWAL</label>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                        <div class="form-group">
                                                                                                                            <div class="col-lg-12 text-center">
                                                                                                                                <p>
                                                                                                                                    You
                                                                                                                                    should
                                                                                                                                    fulfill
                                                                                                                                    the
                                                                                                                                    withdrawal
                                                                                                                                    associated.
                                                                                                                                    If
                                                                                                                                    you
                                                                                                                                    don't
                                                                                                                                    want
                                                                                                                                    to
                                                                                                                                    do
                                                                                                                                    it
                                                                                                                                    now,
                                                                                                                                    you
                                                                                                                                    will
                                                                                                                                    have
                                                                                                                                    to
                                                                                                                                    do
                                                                                                                                    it
                                                                                                                                    by
                                                                                                                                    the
                                                                                                                                    transfer
                                                                                                                                    details
                                                                                                                                    page</p>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                        @if(isset($type) &&$type=='Deposit-Withdrawal')
                                                                                                                    </div>
                                                                                                                    @else
                                                                                                            </div>
                                                                                                        @endif
                                                                                                    <!--deposit-->
                                                                                                        @if(isset($type) &&$type=='Withdrawal-Deposit')
                                                                                                            <div id="withdrawal-deposit2L"
                                                                                                                 style="display:block">
                                                                                                                @else
                                                                                                                    <div id="withdrawal-deposit2L"
                                                                                                                         style="display:none;">
                                                                                                                        @endif
                                                                                                                        <div class="form-group">
                                                                                                                            <div class="col-lg-12 text-center">
                                                                                                                                <label for="deposit"
                                                                                                                                       class="control-label">DEPOSIT</label>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                        <div class="form-group">
                                                                                                                            <div class="col-lg-12 text-center">
                                                                                                                                <p>
                                                                                                                                    You
                                                                                                                                    should
                                                                                                                                    fulfill
                                                                                                                                    the
                                                                                                                                    deposit
                                                                                                                                    associated.
                                                                                                                                    If
                                                                                                                                    you
                                                                                                                                    don't
                                                                                                                                    want
                                                                                                                                    to
                                                                                                                                    do
                                                                                                                                    it
                                                                                                                                    now,
                                                                                                                                    you
                                                                                                                                    will
                                                                                                                                    have
                                                                                                                                    to
                                                                                                                                    do
                                                                                                                                    it
                                                                                                                                    by
                                                                                                                                    the
                                                                                                                                    transfer
                                                                                                                                    details
                                                                                                                                    page</p>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                        @if(isset($type) &&$type=='Withdrawal-Deposit')
                                                                                                                    </div>
                                                                                                                    @else
                                                                                                            </div>
                                                                                                        @endif
                                                                                                    <!--sale-->
                                                                                                        @if(isset($type) &&$type=='Purchase-Sale')
                                                                                                            <div id="purchase-sale2L"
                                                                                                                 style="display:block">
                                                                                                                @else
                                                                                                                    <div id="purchase-sale2L"
                                                                                                                         style="display:none;">
                                                                                                                        @endif
                                                                                                                        <div class="form-group">
                                                                                                                            <div class="col-lg-12 text-center">
                                                                                                                                <label for="sale"
                                                                                                                                       class="control-label">SALE
                                                                                                                                </label>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                        @if(isset($type) &&$type=='Purchase-Sale')
                                                                                                                    </div>
                                                                                                                    @else
                                                                                                            </div>
                                                                                                        @endif
                                                                                                    <!--2nd transfer : pallets number only-->
                                                                                                        @if(isset($type) &&($type=='Withdrawal-Deposit' ||$type=='Deposit-Withdrawal'))
                                                                                                            <div id="DWL"
                                                                                                                 style="display:block">
                                                                                                                @else
                                                                                                                    <div id="DWL"
                                                                                                                         style="display:none;">
                                                                                                                        @endif
                                                                                                                        <div class="form-group">
                                                                                                                            <!--number of pallets-->
                                                                                                                            <div class="col-lg-2">
                                                                                                                                <label for="palletsNumber2"
                                                                                                                                       class="control-label">Pal.
                                                                                                                                    nbr
                                                                                                                                    :</label>
                                                                                                                            </div>
                                                                                                                            <div class="col-lg-1">
                                                                                                                                @if(Illuminate\Support\Facades\Input::old('palletsNumber2'))
                                                                                                                                    <input id="palletsNumber2"
                                                                                                                                           type="number"
                                                                                                                                           class="form-control"
                                                                                                                                           name="palletsNumber2"
                                                                                                                                           value="{{ old('palletsNumber2') }}"
                                                                                                                                           placeholder="Nbr"
                                                                                                                                           min="0"
                                                                                                                                           autofocus>
                                                                                                                                @elseif(isset($palletsNumber2))
                                                                                                                                    <input id="palletsNumber2"
                                                                                                                                           type="number"
                                                                                                                                           class="form-control"
                                                                                                                                           name="palletsNumber2"
                                                                                                                                           value="{{$palletsNumber2}}"
                                                                                                                                           placeholder="Nbr"
                                                                                                                                           min="0"
                                                                                                                                           autofocus>
                                                                                                                                @else
                                                                                                                                    <input id="palletsNumber2"
                                                                                                                                           type="number"
                                                                                                                                           class="form-control"
                                                                                                                                           name="palletsNumber2"
                                                                                                                                           value=""
                                                                                                                                           placeholder="Nbr"
                                                                                                                                           min="0"
                                                                                                                                           autofocus>
                                                                                                                                @endif
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                        @if(isset($type) &&($type=='Withdrawal-Deposit'||$type=='Deposit-Withdrawal'))
                                                                                                                    </div>
                                                                                                                    @else
                                                                                                            </div>
                                                                                                        @endif
                                                                                                    <!--2nd transfer : pallets number credit account and debit account-->
                                                                                                        @if(isset($type) &&($type=='Purchase-Sale'))
                                                                                                            <div id="SPL"
                                                                                                                 style="display:block">
                                                                                                                @else
                                                                                                                    <div id="SPL"
                                                                                                                         style="display:none;">
                                                                                                                        @endif
                                                                                                                        <div class="form-group">
                                                                                                                            <!--number of pallets-->
                                                                                                                            <div class="col-lg-1">
                                                                                                                                <label for="palletsNumber2C"
                                                                                                                                       class="control-label"><span>*</span>Nbr
                                                                                                                                    :</label>
                                                                                                                            </div>
                                                                                                                            <div class="col-lg-1 text-left">
                                                                                                                                @if(Illuminate\Support\Facades\Input::old('palletsNumber2C'))
                                                                                                                                    <input id="palletsNumber2C"
                                                                                                                                           type="number"
                                                                                                                                           class="form-control"
                                                                                                                                           name="palletsNumber2C"
                                                                                                                                           value="{{ old('palletsNumber2C') }}"
                                                                                                                                           placeholder="Nbr"
                                                                                                                                           min="0"
                                                                                                                                           autofocus>
                                                                                                                                @elseif(isset($palletsNumber2C))
                                                                                                                                    <input id="palletsNumber2C"
                                                                                                                                           type="number"
                                                                                                                                           class="form-control"
                                                                                                                                           name="palletsNumber2C"
                                                                                                                                           value="{{$palletsNumber2C}}"
                                                                                                                                           placeholder="Nbr"
                                                                                                                                           min="0"
                                                                                                                                           autofocus>
                                                                                                                                @else
                                                                                                                                    <input id="palletsNumber2C"
                                                                                                                                           type="number"
                                                                                                                                           class="form-control"
                                                                                                                                           name="palletsNumber2C"
                                                                                                                                           value="0"
                                                                                                                                           placeholder="Nbr"
                                                                                                                                           min="0"
                                                                                                                                           autofocus>
                                                                                                                                @endif
                                                                                                                            </div>
                                                                                                                            <div class="col-lg-2 text-right">
                                                                                                                                <label for="debitAccount2"
                                                                                                                                       class="control-label"><span>*</span>
                                                                                                                                    Debit
                                                                                                                                    :</label>
                                                                                                                            </div>
                                                                                                                            <div class="col-lg-3">
                                                                                                                                <select class="selectpicker show-tick form-control"
                                                                                                                                        data-size="10"
                                                                                                                                        data-live-search="true"
                                                                                                                                        data-live-search-style="startsWith"
                                                                                                                                        title="Debit Account (=saler)"
                                                                                                                                        name="debitAccount2"
                                                                                                                                        id="select-debit2"
                                                                                                                                        onchange="selectAccount2(this.value);">
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
                                                                                                                                    @elseif(isset($debitAccount2))
                                                                                                                                        @if (strpos($debitAccount2, '-') == 5 && explode('-', $debitAccount2)[0] == 'truck')
                                                                                                                                            @php($nameTruckAccount = App\Truck::where('id', explode('-', $debitAccount2)[1])->value('name'))
                                                                                                                                            @php($licensePlate = App\Truck::where('id', explode('-', $debitAccount2)[1])->value('licensePlate'))
                                                                                                                                            <option selected
                                                                                                                                                    value="truck-{{explode('-', $debitAccount2)[1]}}">{{$nameTruckAccount}}
                                                                                                                                                - {{$licensePlate}}</option>
                                                                                                                                        @elseif (strpos($debitAccount2, '-') == 7 && explode('-', $debitAccount2)[0] == 'account')
                                                                                                                                            @php($namePalletsAccount = App\Palletsaccount::where('id', explode('-', $debitAccount2)[1])->value('name'))
                                                                                                                                            <option selected
                                                                                                                                                    value="account-{{explode('-', $debitAccount2)[1]}}">{{$namePalletsAccount}}</option>
                                                                                                                                        @endif
                                                                                                                                    @endif
                                                                                                                                </select>
                                                                                                                            </div>
                                                                                                                            <div class="col-lg-2 text-right">
                                                                                                                                <label for="creditAccount2"
                                                                                                                                       class="control-label"><span>*</span>
                                                                                                                                    Credit
                                                                                                                                    :</label>
                                                                                                                            </div>
                                                                                                                            <div class="col-lg-3">
                                                                                                                                <select class="selectpicker show-tick form-control"
                                                                                                                                        data-size="10"
                                                                                                                                        data-live-search="true"
                                                                                                                                        data-live-search-style="startsWith"
                                                                                                                                        title="Credit Account (=purchaser)"
                                                                                                                                        name="creditAccount2"
                                                                                                                                        id="select-credit2"
                                                                                                                                        onchange="creditaccount2(this.value);">
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
                                                                                                                                    @elseif(isset($creditAccount2))
                                                                                                                                        @if (strpos($creditAccount2, '-') == 5 && explode('-', $creditAccount2)[0] == 'truck')
                                                                                                                                            @php($nameTruckAccount = App\Truck::where('id', explode('-', $creditAccount2)[1])->value('name'))
                                                                                                                                            @php($licensePlate = App\Truck::where('id', explode('-', $creditAccount2)[1])->value('licensePlate'))
                                                                                                                                            <option selected
                                                                                                                                                    value="truck-{{explode('-', $creditAccount2)[1]}}">{{$nameTruckAccount}}
                                                                                                                                                - {{$licensePlate}}</option>
                                                                                                                                        @elseif (strpos($creditAccount2, '-') == 7 && explode('-', $creditAccount2)[0] == 'account')
                                                                                                                                            @php($namePalletsAccount = App\Palletsaccount::where('id', explode('-', $creditAccount2)[1])->value('name'))
                                                                                                                                            <option selected
                                                                                                                                                    value="account-{{explode('-', $creditAccount2)[1]}}">{{$namePalletsAccount}}</option>
                                                                                                                                        @endif
                                                                                                                                    @endif
                                                                                                                                </select>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                        @if(isset($type) &&($type=='Purchase-Sale'||$type=='Sale-PurchaseSale-Purchase'))
                                                                                                                    </div>
                                                                                                                    @else
                                                                                                            </div>
                                                                                                        @endif
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
                                                                                                                <div class="modal-dialog modal-lg">
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
                                                                                                                                            DEBIT
                                                                                                                                            2
                                                                                                                                        </th>
                                                                                                                                        <th class="text-center">
                                                                                                                                            CREDIT
                                                                                                                                            2
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
                                                                                                                                    <td class="text-center">
                                                                                                                                        Actual
                                                                                                                                    </td>
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
                                                                                                                                    @if(Session::has('creditAccount2')&&Session::has('debitAccount2')&&Session::has('palletsNumber2'))
                                                                                                                                        <td class="text-center">
                                                                                                                                            - {{request()->session()->get('palletsNumber2')}}</td>
                                                                                                                                        <td class="text-center">
                                                                                                                                            + {{request()->session()->get('palletsNumber2')}}</td>
                                                                                                                                    @endif
                                                                                                                                </tr>
                                                                                                                                <tr>
                                                                                                                                    <td class="text-center">
                                                                                                                                        Total
                                                                                                                                    </td>
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
                                                                                                                                    <span class="glyphicon glyphicon-warning-sign text-danger"></span><span
                                                                                                                                            class="text-danger"> Pallets numbers are DIFFERENT for both transfers </span>
                                                                                                                                </div>
                                                                                                                            @endif
                                                                                                                            @if(($type=='Deposit-Withdrawal' || $type=='Withdrawal-Deposit' )&&((Session::has('palletsNumber')&&(request()->session()->get('palletsNumber')<>$loading->anz))||(Session::has('palletsNumber2')&&(request()->session()->get('palletsNumber2')<>$loading->anz))))
                                                                                                                                <div class="text-center">
                                                                                                                                    <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                                                                    <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                                                                    <span class="text-danger">Pallets number does NOT MATCH the number expected in the loading order ({{$loading->anz}}
                                                                                                                                        )</span>
                                                                                                                                </div>
                                                                                                                            @endif
                                                                                                                            @if(Session::has('sumTransfersDepositOnly') && Session::has('sumTransfersWithdrawalOnly') && request()->session()->get('sumTransfersDepositOnly')<>request()->session()->get('sumTransfersWithdrawalOnly') )
                                                                                                                                <div class="text-center">
                                                                                                                                    <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                                                                    <span class="glyphicon glyphicon-warning-sign text-danger"></span><span
                                                                                                                                            class="text-danger"> Sum of deposit only transfers does NOT MATCH the sum of withdrawal only transfers </span>
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
                                                                                                    </div>
                                                                                                </div>
                                                                                            @endif
                                                                                        </div>
                                                                                    </form>

                                                                                    <!---TABLE ALL TRANSFERS-->
                                                                                    <div class="row">
                                                                                        <div class="table-responsive table-transfers">
                                                                                            <table class="table table-hover table-bordered">
                                                                                                <thead>
                                                                                                <tr>
                                                                                                    <th class="text-center col1ID">
                                                                                                        ID<br><a
                                                                                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                                                                                href="{{url('/detailsLoading/'.$loading->atrnr.'?sortby=id&order=asc')}}"></a><a
                                                                                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                                                                                href="{{url('/detailsLoading/'.$loading->atrnr.'?sortby=id&order=desc')}}"></a>
                                                                                                    </th>
                                                                                                    <th class="text-center col3">
                                                                                                        Type<br><a
                                                                                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                                                                                href="{{url('/detailsLoading/'.$loading->atrnr.'?sortby=type&order=asc')}}"></a><a
                                                                                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                                                                                href="{{url('/detailsLoading/'.$loading->atrnr.'?sortby=type&order=desc')}}"></a>
                                                                                                    </th>
                                                                                                    <th class="text-center col4">
                                                                                                        Debit
                                                                                                        account<br><a
                                                                                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                                                                                href="{{url('/detailsLoading/'.$loading->atrnr.'?sortby=debitAccount&order=asc')}}"></a><a
                                                                                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                                                                                href="{{url('/detailsLoading/'.$loading->atrnr.'?sortby=debitAccount&order=desc')}}"></a>
                                                                                                    </th>
                                                                                                    <th class="text-center col4">
                                                                                                        Credit
                                                                                                        account<br><a
                                                                                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                                                                                href="{{url('/detailsLoading/'.$loading->atrnr.'?sortby=creditAccount&order=asc')}}"></a><a
                                                                                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                                                                                href="{{url('/detailsLoading/'.$loading->atrnr.'?sortby=creditAccount&order=desc')}}"></a>
                                                                                                    </th>
                                                                                                    <th class="text-center col2">
                                                                                                        Pal.
                                                                                                        nbr<br><a
                                                                                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                                                                                href="{{url('/detailsLoading/'.$loading->atrnr.'?sortby=palletsNumber&order=asc')}}"></a><a
                                                                                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                                                                                href="{{url('/detailsLoading/'.$loading->atrnr.'?sortby=palletsNumber&order=desc')}}"></a>
                                                                                                    </th>

                                                                                                    <th class="text-center col5">
                                                                                                        State<br><a
                                                                                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                                                                                href="{{url('/detailsLoading/'.$loading->atrnr.'?sortby=state&order=asc')}}"></a><a
                                                                                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                                                                                href="{{url('/detailsLoading/'.$loading->atrnr.'?sortby=state&order=desc')}}"></a>
                                                                                                    </th>
                                                                                                    <th></th>
                                                                                                </tr>
                                                                                                </thead>
                                                                                                <tbody>
                                                                                                @foreach($listPalletstransfers as $transfer)
                                                                                                    @if($transfer->state=="Untreated")
                                                                                                        @php($class="untreated")
                                                                                                    @elseif($transfer->state=="Waiting documents")
                                                                                                        @php($class="waitingdocuments")
                                                                                                    @elseif($transfer->state=="Complete")
                                                                                                        @php($class="complete")
                                                                                                    @else
                                                                                                        @php($class="completevalidated")
                                                                                                    @endif
                                                                                                    <tr class="{{$class}}">
                                                                                                        <td class="text-center col1ID">
                                                                                                            <a class="link"
                                                                                                               href="{{route('showDetailsPalletstransfer',$transfer->id)}}">{{$transfer->id}}</a>
                                                                                                        </td>
                                                                                                        <td class="text-center col3">{{$transfer->type}}</td>
                                                                                                        @if(isset($transfer->debitAccount))
                                                                                                            @php($partsDebitAccount=explode('-', $transfer->debitAccount))
                                                                                                            @php($typeDebitAccount=$partsDebitAccount[count($partsDebitAccount)-2])
                                                                                                            @php($idDebitAccount=$partsDebitAccount[count($partsDebitAccount)-1])
                                                                                                            @if($typeDebitAccount=='account')
                                                                                                                @php($nameDebitAccount=\App\Palletsaccount::where('id', $idDebitAccount)->first()->name)
                                                                                                                <td class="text-center">
                                                                                                                    <a class="link"
                                                                                                                       href="{{route('showDetailsPalletsaccount',$idDebitAccount)}}">{{$nameDebitAccount}}</a>
                                                                                                                </td>
                                                                                                            @elseif($typeDebitAccount=='truck')
                                                                                                                @php($nameDebitAccount=\App\Truck::where('id', $idDebitAccount)->first()->name)
                                                                                                                @php($licensePlateDebitAccount=\App\Truck::where('id', $idDebitAccount)->first()->licensePlate)
                                                                                                                <td class="text-center">
                                                                                                                    <a class="link"
                                                                                                                       href="{{route('showDetailsTruck',$idDebitAccount)}}">{{$nameDebitAccount}}
                                                                                                                        - {{$licensePlateDebitAccount}}</a>
                                                                                                                </td>
                                                                                                            @endif
                                                                                                        @else
                                                                                                            <td></td>
                                                                                                        @endif
                                                                                                        @if(isset($transfer->creditAccount))
                                                                                                            @php($partsCreditAccount=explode('-', $transfer->creditAccount))
                                                                                                            @php($typeCreditAccount=$partsCreditAccount[count($partsCreditAccount)-2])
                                                                                                            @php($idCreditAccount=$partsCreditAccount[count($partsCreditAccount)-1])
                                                                                                            @if($typeCreditAccount=='account')
                                                                                                                @php($nameCreditAccount=\App\Palletsaccount::where('id', $idCreditAccount)->first()->name)
                                                                                                                <td class="text-center">
                                                                                                                    <a class="link"
                                                                                                                       href="{{route('showDetailsPalletsaccount',$idCreditAccount)}}">{{$nameCreditAccount}}</a>
                                                                                                                </td>
                                                                                                            @elseif($typeCreditAccount=='truck')
                                                                                                                @php($nameCreditAccount=\App\Truck::where('id', $idCreditAccount)->first()->name)
                                                                                                                @php($licensePlateCreditAccount=\App\Truck::where('id', $idCreditAccount)->first()->licensePlate)
                                                                                                                <td class="text-center">
                                                                                                                    <a class="link"
                                                                                                                       href="{{route('showDetailsTruck',$idCreditAccount)}}">{{$nameCreditAccount}}
                                                                                                                        - {{$licensePlateCreditAccount}}</a>
                                                                                                                </td>
                                                                                                            @endif
                                                                                                        @else
                                                                                                            <td></td>
                                                                                                        @endif
                                                                                                        <td class="text-center col2">{{$transfer->palletsNumber}}</td>
                                                                                                        <td class="text-center col5">{{$transfer->state}}</td>
                                                                                                        @php($errorsTransfer= \App\Http\Controllers\PalletstransfersController::actualErrors($transfer))
                                                                                                        <td class="text-left colDanger">
                                                                                                            @if(!empty($errorsTransfer))
                                                                                                                @foreach($errorsTransfer as $error)
                                                                                                                    <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                                                @endforeach
                                                                                                            @else
                                                                                                            @endif
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                @endforeach
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </div>
                                                                                    </div>
                                                                                    <br>

                                                                                    <!--PANEL FOR EACH TRANSFER-->
                                                                                    <form class="form-horizontal"
                                                                                          role="form"
                                                                                          method="POST"
                                                                                          action="{{route('submitUpdateUpload', $loading->atrnr)}}"
                                                                                          enctype="multipart/form-data">
                                                                                        <input type="hidden"
                                                                                               name="_token"
                                                                                               value="{{ csrf_token() }}">
                                                                                        <div class="form-group">
                                                                                            @if(Session::has('errorAccountsPanel'))
                                                                                                <div class="alert alert-danger text-alert text-center">{{ Session::get('errorAccountsPanel') }}</div>
                                                                                            @endif
                                                                                        </div>
                                                                                        <!-----------------NORMAL TRANSFERS---------->
                                                                                                                                                                                <div class="form-group text-center">
                                                                                            <label for="normal"
                                                                                                   class="control-label text-center">NORMAL</label>
                                                                                        </div>

                                                                                        @foreach($listPalletstransfersNormal as $transferNormal)
                                                                                            @php($errorsTransfer=\App\Http\Controllers\PalletstransfersController::actualErrors($transferNormal))
                                                                                            @if($transferNormal->state=="Untreated")
                                                                                                <div class="panel panelUntreated">
                                                                                                    @elseif ($transferNormal->state=="Waiting documents")
                                                                                                        <div class="panel panelWaitingdocuments">
                                                                                                            @elseif ($transferNormal->state=="Complete")
                                                                                                                <div class="panel panelComplete">
                                                                                                                    @elseif ($transferNormal->state=="Complete Validated")
                                                                                                                        <div class="panel panel-general">
                                                                                                                            @endif
                                                                                                                            <div class="panel-heading">
                                                                                                                                <div class="col-lg-11 text-left headerSubpanelDetailsLoading">
                                                                                                                                    <a data-toggle="collapse"
                                                                                                                                       href="#PanSubcollapse{{$transferNormal->id}}">Transfer {{$transferNormal->id}}
                                                                                                                                    </a>
                                                                                                                                    @if(!empty($errorsTransfer))
                                                                                                                                        @foreach($errorsTransfer as $error)
                                                                                                                                            <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                                                                        @endforeach
                                                                                                                                    @endif
                                                                                                                                </div>
                                                                                                                                <div>
                                                                                                                                    <button type="submit"
                                                                                                                                            class=" btn btn-primary btn-form glyphicon glyphicon-remove"
                                                                                                                                            value="{{$transferNormal->id}}"
                                                                                                                                            name="delete"
                                                                                                                                    ></button>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                            <div id="PanSubcollapse{{$transferNormal->id}}"
                                                                                                                                 class="panel-collapse collapse panel-body">
                                                                                                                                <div class="form-group">
                                                                                                                                    <!--type-->
                                                                                                                                    <div class="col-lg-1">
                                                                                                                                        <label for="type{{$transferNormal->id}}"
                                                                                                                                               class="control-label"><span>*</span>Type
                                                                                                                                            :</label>
                                                                                                                                    </div>
                                                                                                                                    <div class="col-lg-2">
                                                                                                                                        @if(isset($transferNormal->validate) && $transferNormal->validate==1)
                                                                                                                                            <input type="text"
                                                                                                                                                   name="type{{$transferNormal->id}}"
                                                                                                                                                   class="form-control"
                                                                                                                                                   value="{{$transferNormal->type}}"
                                                                                                                                                   readonly>
                                                                                                                                        @else
                                                                                                                                            <select class="selectpicker show-tick form-control"
                                                                                                                                                    data-size="5"
                                                                                                                                                    data-live-search="true"
                                                                                                                                                    data-live-search-style="startsWith"
                                                                                                                                                    title="Type"
                                                                                                                                                    name="type{{$transferNormal->id}}"
                                                                                                                                                    id="type{{$transferNormal->id}}"
                                                                                                                                            >
                                                                                                                                                @if(Illuminate\Support\Facades\Input::old('type'.$transferNormal->id))
                                                                                                                                                    <optgroup
                                                                                                                                                            label="Normal">
                                                                                                                                                        <option @if(old('type'.$transferNormal->id) == 'Deposit-Withdrawal') selected
                                                                                                                                                                @endif value="Deposit-Withdrawal"
                                                                                                                                                                id="Deposit-WithdrawalOption{{$transferNormal->id}}">
                                                                                                                                                            Deposit-Withdrawal
                                                                                                                                                        </option>
                                                                                                                                                        <option @if(old('type'.$transferNormal->id) == 'Withdrawal-Deposit') selected
                                                                                                                                                                @endif value="Withdrawal-Deposit"
                                                                                                                                                                id="Withdrawal-DepositOption{{$transferNormal->id}}">
                                                                                                                                                            Withdrawal-Deposit
                                                                                                                                                        </option>
                                                                                                                                                        <option @if(old('type'.$transferNormal->id) == 'Deposit_Only') selected
                                                                                                                                                                @endif value="Deposit_Only"
                                                                                                                                                                id="Deposit_OnlyOption{{$transferNormal->id}}">
                                                                                                                                                            Deposit_Only
                                                                                                                                                        </option>
                                                                                                                                                        <option @if(old('type'.$transferNormal->id) == 'Withdrawal_Only') selected
                                                                                                                                                                @endif value="Withdrawal_Only"
                                                                                                                                                                id="Withdrawal_OnlyOption{{$transferNormal->id}}">
                                                                                                                                                            Withdrawal_Only
                                                                                                                                                        </option>
                                                                                                                                                    </optgroup>
                                                                                                                                                @elseif(isset($transferNormal->type))
                                                                                                                                                    <optgroup
                                                                                                                                                            label="Normal">
                                                                                                                                                        <option @if($transferNormal->type == 'Deposit-Withdrawal') selected
                                                                                                                                                                @endif value="Deposit-Withdrawal"
                                                                                                                                                                id="Deposit-WithdrawalOption{{$transferNormal->id}}">
                                                                                                                                                            Deposit-Withdrawal
                                                                                                                                                        </option>
                                                                                                                                                        <option @if($transferNormal->type == 'Withdrawal-Deposit') selected
                                                                                                                                                                @endif value="Withdrawal-Deposit"
                                                                                                                                                                id="Withdrawal-DepositOption{{$transferNormal->id}}">
                                                                                                                                                            Withdrawal-Deposit
                                                                                                                                                        </option>
                                                                                                                                                        <option @if($transferNormal->type == 'Deposit_Only') selected
                                                                                                                                                                @endif value="Deposit_Only"
                                                                                                                                                                id="Deposit_OnlyOption{{$transferNormal->id}}">
                                                                                                                                                            Deposit_Only
                                                                                                                                                        </option>
                                                                                                                                                        <option @if($transferNormal->type == 'Withdrawal_Only') selected
                                                                                                                                                                @endif value="Withdrawal_Only"
                                                                                                                                                                id="Withdrawal_OnlyOption{{$transferNormal->id}}">
                                                                                                                                                            Withdrawal_Only
                                                                                                                                                        </option>
                                                                                                                                                    </optgroup>
                                                                                                                                                @else
                                                                                                                                                    <optgroup
                                                                                                                                                            label="Normal">
                                                                                                                                                        <option value="Deposit-Withdrawal"
                                                                                                                                                                id="Deposit-WithdrawalOption{{$transferNormal->id}}">
                                                                                                                                                            Deposit-Withdrawal
                                                                                                                                                        </option>
                                                                                                                                                        <option value="Withdrawal-Deposit"
                                                                                                                                                                id="Withdrawal-DepositOption{{$transferNormal->id}}">
                                                                                                                                                            Withdrawal-Deposit
                                                                                                                                                        </option>
                                                                                                                                                        <option value="Deposit_Only"
                                                                                                                                                                id="Deposit_OnlyOption{{$transferNormal->id}}">
                                                                                                                                                            Deposit_Only
                                                                                                                                                        </option>
                                                                                                                                                        <option value="Withdrawal_Only"
                                                                                                                                                                id="Withdrawal_OnlyOption{{$transferNormal->id}}">
                                                                                                                                                            Withdrawal_Only
                                                                                                                                                        </option>
                                                                                                                                                    </optgroup>
                                                                                                                                                @endif
                                                                                                                                            </select>
                                                                                                                                        @endif
                                                                                                                                    </div>
                                                                                                                                    <!--details-->
                                                                                                                                    <div class="col-lg-4">
                                                                                                                                        @if(isset($transferNormal->details)&&(isset($transferNormal->validate) && $transferNormal->validate==1))
                                                                                                                                            <textarea
                                                                                                                                                    class="form-control"
                                                                                                                                                    rows="1"
                                                                                                                                                    name="details{{$transferNormal->id}}"
                                                                                                                                                    placeholder="Details"
                                                                                                                                                    readonly>{{$transferNormal->details}}</textarea>
                                                                                                                                        @elseif(isset($transferNormal->details))
                                                                                                                                            <textarea
                                                                                                                                                    class="form-control"
                                                                                                                                                    rows="1"
                                                                                                                                                    name="details{{$transferNormal->id}}"
                                                                                                                                                    placeholder="Details">{{$transferNormal->details}}</textarea>
                                                                                                                                        @elseif(isset($transferNormal->validate) && $transferNormal->validate==1)
                                                                                                                                            <textarea
                                                                                                                                                    class="form-control"
                                                                                                                                                    rows="1"
                                                                                                                                                    name="details{{$transferNormal->id}}"
                                                                                                                                                    placeholder="Details"
                                                                                                                                                    readonly>{{old('details'.$transferNormal->id)}}</textarea>
                                                                                                                                        @else
                                                                                                                                            <textarea
                                                                                                                                                    class="form-control"
                                                                                                                                                    rows="1"
                                                                                                                                                    id="details{{$transferNormal->id}}"
                                                                                                                                                    placeholder="Details">{{old('details'.$transferNormal->id)}}</textarea>
                                                                                                                                        @endif
                                                                                                                                    </div>
                                                                                                                                    <!--date-->
                                                                                                                                    <div class="col-lg-2">
                                                                                                                                        @if(isset($transferNormal->date)&&(isset($transferNormal->validate) && $transferNormal->validate==1))
                                                                                                                                            <input id="date{{$transferNormal->id}}"
                                                                                                                                                   type="date"
                                                                                                                                                   class="form-control"
                                                                                                                                                   name="date{{$transferNormal->id}}"
                                                                                                                                                   value="{{ $transferNormal->date }}"
                                                                                                                                                   placeholder="Date"
                                                                                                                                                   autofocus
                                                                                                                                                   readonly>
                                                                                                                                        @elseif(isset($transferNormal->date))
                                                                                                                                            <input id="date{{$transferNormal->id}}"
                                                                                                                                                   type="date"
                                                                                                                                                   class="form-control"
                                                                                                                                                   name="date{{$transferNormal->id}}"
                                                                                                                                                   value="{{ $transferNormal->date }}"
                                                                                                                                                   placeholder="Date"
                                                                                                                                                   required
                                                                                                                                                   autofocus>

                                                                                                                                        @elseif(isset($transferNormal->validate) && $transferNormal->validate==1)
                                                                                                                                            <input id="date{{$transferNormal->id}}"
                                                                                                                                                   type="date"
                                                                                                                                                   class="form-control"
                                                                                                                                                   name="date{{$transferNormal->id}}"
                                                                                                                                                   value="{{ old('date'.$transferNormal->id) }}"
                                                                                                                                                   placeholder="Date"
                                                                                                                                                   autofocus
                                                                                                                                                   readonly>
                                                                                                                                        @else(Illuminate\Support\Facades\Input::old('date'))
                                                                                                                                            <input id="date{{$transferNormal->id}}"
                                                                                                                                                   type="date"
                                                                                                                                                   class="form-control"
                                                                                                                                                   name="date{{$transferNormal->id}}"
                                                                                                                                                   value="{{ old('date'.$transferNormal->id) }}"
                                                                                                                                                   placeholder="Date"
                                                                                                                                                   autofocus
                                                                                                                                                   required>
                                                                                                                                        @endif
                                                                                                                                    </div>
                                                                                                                                    <!--add account-->
                                                                                                                                    <div class="col-lg-2 col-lg-offset-1">
                                                                                                                                        <a href="{{route('showAddPalletsaccount')}}"
                                                                                                                                           class="link"><span
                                                                                                                                                    class="glyphicon glyphicon-plus-sign"></span>
                                                                                                                                            Add
                                                                                                                                            account</a>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                                <div class="form-group">
                                                                                                                                    <!--number of pallets-->
                                                                                                                                    <div class="col-lg-1">
                                                                                                                                        <label for="palletsNumber{{$transferNormal->id}}"
                                                                                                                                               class="control-label"><span>*</span>Pal.
                                                                                                                                            :</label>
                                                                                                                                    </div>
                                                                                                                                    <div class="col-lg-1">
                                                                                                                                        @if(Illuminate\Support\Facades\Input::old('palletsNumber'.$transfer->id))
                                                                                                                                            <input id="palletsNumber{{$transferNormal->id}}"
                                                                                                                                                   type="number"
                                                                                                                                                   class="form-control"
                                                                                                                                                   name="palletsNumber{{$transferNormal->id}}"
                                                                                                                                                   value="{{ old('palletsNumber'.$transferNormal->id) }}"
                                                                                                                                                   placeholder="Nbr"
                                                                                                                                                   min="0"
                                                                                                                                                   required
                                                                                                                                                   autofocus>
                                                                                                                                        @elseif(isset($transferNormal->validate) && $transferNormal->validate==1)
                                                                                                                                            <input id="palletsNumber{{$transferNormal->id}}"
                                                                                                                                                   type="number"
                                                                                                                                                   class="form-control"
                                                                                                                                                   name="palletsNumber{{$transferNormal->id}}"
                                                                                                                                                   value="{{$transferNormal->palletsNumber}}"
                                                                                                                                                   placeholder="Nbr"
                                                                                                                                                   min="0"
                                                                                                                                                   autofocus
                                                                                                                                                   readonly>
                                                                                                                                        @else
                                                                                                                                            <input id="palletsNumber{{$transferNormal->id}}"
                                                                                                                                                   type="number"
                                                                                                                                                   class="form-control"
                                                                                                                                                   name="palletsNumber{{$transferNormal->id}}"
                                                                                                                                                   value="{{$transferNormal->palletsNumber}}"
                                                                                                                                                   placeholder="Nbr"
                                                                                                                                                   min="0"
                                                                                                                                                   autofocus>
                                                                                                                                        @endif
                                                                                                                                    </div>

                                                                                                                                    <!--debit account-->
                                                                                                                                    <div class="col-lg-2"
                                                                                                                                         id="debitAccount1{{$transferNormal->id}}"
                                                                                                                                         style="display: block">
                                                                                                                                        <label for="debitAccount{{$transferNormal->id}}"
                                                                                                                                               class="control-label"><span>*</span>
                                                                                                                                            Debit
                                                                                                                                            account
                                                                                                                                            :</label>
                                                                                                                                    </div>
                                                                                                                                    <div class="col-lg-3"
                                                                                                                                         id="debitAccount2{{$transferNormal->id}}"
                                                                                                                                         style="display: block">
                                                                                                                                        @if(isset($transferNormal->validate) && $transferNormal->validate==1)
                                                                                                                                            @php($partsDebitAccount=explode('-',$transferNormal->debitAccount))
                                                                                                                                            @php($aprim=$partsDebitAccount[count($partsDebitAccount)-1])
                                                                                                                                            @php($bprim=$partsDebitAccount[count($partsDebitAccount)-2])
                                                                                                                                            @if(count(array_diff ($partsDebitAccount, [$aprim, $bprim]))==1)
                                                                                                                                                @php($debitAccountValidate=array_diff ($partsDebitAccount, [$aprim, $bprim])[0])
                                                                                                                                            @else
                                                                                                                                                @php($debitAccountValidate=implode( ' - ', array_diff ($partsDebitAccount, [$aprim, $bprim])))
                                                                                                                                            @endif
                                                                                                                                            <input type="text"
                                                                                                                                                   name="debitAccount{{$transferNormal->id}}"
                                                                                                                                                   class="form-control"
                                                                                                                                                   value="{{$debitAccountValidate}}"
                                                                                                                                                   readonly>
                                                                                                                                        @else
                                                                                                                                            <select class="selectpicker show-tick form-control"
                                                                                                                                                    data-size="10"
                                                                                                                                                    data-live-search="true"
                                                                                                                                                    data-live-search-style="startsWith"
                                                                                                                                                    title="Debit Account"
                                                                                                                                                    name="debitAccount{{$transferNormal->id}}"
                                                                                                                                                    id="select-debit{{$transferNormal->id}}"
                                                                                                                                                    onchange="selectAccountK(this.value, id);"
                                                                                                                                            >
                                                                                                                                                @if(isset($transferNormal->debitAccount))
                                                                                                                                                    @php($partsDebitAccount=explode('-', $transferNormal->debitAccount))
                                                                                                                                                    @php($typeDebitAccount=$partsDebitAccount[count($partsDebitAccount)-2])
                                                                                                                                                    @php($idDebitAccount=$partsDebitAccount[count($partsDebitAccount)-1])
                                                                                                                                                @elseif(Illuminate\Support\Facades\Input::old('debitAccount'.$transferNormal->id))
                                                                                                                                                    @php($partsDebitAccountOld=explode('-', old('debitAccount'.$transferNormal->id)))
                                                                                                                                                    @php($typeDebitAccountOld=$partsDebitAccountOld[count($partsDebitAccountOld)-2])
                                                                                                                                                    @php($idDebitAccountOld=$partsDebitAccountOld[count($partsDebitAccountOld)-1])
                                                                                                                                                @endif
                                                                                                                                                @foreach($listPalletsAccounts as $palletsAccount )
                                                                                                                                                    @if(isset($transferNormal->debitAccount)&& ($typeDebitAccount == 'account') && ($palletsAccount->id==$idDebitAccount))
                                                                                                                                                        <option value="account-{{$palletsAccount->id}}"
                                                                                                                                                                selected>{{$palletsAccount->name}}</option>
                                                                                                                                                    @elseif(Illuminate\Support\Facades\Input::old('debitAccount'.$transferNormal->id) && ($typeDebitAccountOld == 'account') && ($palletsAccount->id==$idDebitAccountOld))
                                                                                                                                                        <option value="account-{{$palletsAccount->id}}"
                                                                                                                                                                selected>{{$palletsAccount->name}}</option>
                                                                                                                                                    @else
                                                                                                                                                        <option value="account-{{$palletsAccount->id}}">{{$palletsAccount->name}}</option>
                                                                                                                                                    @endif
                                                                                                                                                @endforeach
                                                                                                                                                @foreach($listTrucksAccounts as $trucksAccount )
                                                                                                                                                    @if(Illuminate\Support\Facades\Input::old('debitAccount'.$transferNormal->id) && ($typeDebitAccountOld == 'truck') && ($trucksAccount->id==$idDebitAccountOld))
                                                                                                                                                        <option value="truck-{{$trucksAccount->id}}"
                                                                                                                                                                selected>{{$trucksAccount->name}}
                                                                                                                                                            - {{$trucksAccount->licensePlate}}</option>
                                                                                                                                                    @elseif(isset($transferNormal->debitAccount)&& ($typeDebitAccount== 'truck') && ($trucksAccount->id==$idDebitAccount))
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
                                                                                                                                    <div class="col-lg-2"
                                                                                                                                         id="creditAccount1{{$transferNormal->id}}"
                                                                                                                                         style="display: block">
                                                                                                                                        <label for="creditAccount{{$transferNormal->id}}"
                                                                                                                                               class="control-label"><span>*</span>
                                                                                                                                            Credit
                                                                                                                                            account
                                                                                                                                            :</label>
                                                                                                                                    </div>
                                                                                                                                    <div class="col-lg-3"
                                                                                                                                         id="creditAccount2{{$transferNormal->id}}"
                                                                                                                                         style="display: block">
                                                                                                                                        @if(isset($transferNormal->validate) && $transferNormal->validate==1)
                                                                                                                                            @php($partsCreditAccount=explode('-',$transferNormal->creditAccount))
                                                                                                                                            @php($a=$partsCreditAccount[count($partsCreditAccount)-1])
                                                                                                                                            @php($b=$partsCreditAccount[count($partsCreditAccount)-2])
                                                                                                                                            @if(count(array_diff ($partsCreditAccount, [$a, $b]))==1)
                                                                                                                                                @php($creditAccountValidate=array_diff ($partsCreditAccount, [$a, $b])[0])
                                                                                                                                            @else
                                                                                                                                                @php($creditAccountValidate=implode( ' - ', array_diff ($partsCreditAccount, [$a, $b])))
                                                                                                                                            @endif
                                                                                                                                            <input type="text"
                                                                                                                                                   name="creditAccount{{$transferNormal->id}}"
                                                                                                                                                   class="form-control"
                                                                                                                                                   value="{{$creditAccountValidate}}"
                                                                                                                                                   readonly>
                                                                                                                                        @else
                                                                                                                                            <select class="selectpicker show-tick form-control"
                                                                                                                                                    data-size="10"
                                                                                                                                                    data-live-search="true"
                                                                                                                                                    data-live-search-style="startsWith"
                                                                                                                                                    title="Credit Account"
                                                                                                                                                    name="creditAccount{{$transferNormal->id}}"
                                                                                                                                                    id="select-credit{{$transferNormal->id}}"
                                                                                                                                                    onchange="creditaccountK(this.value, id);"
                                                                                                                                            >
                                                                                                                                                @if(isset($transferNormal->creditAccount))
                                                                                                                                                    @php($partsCreditAccount=explode('-', $transferNormal->creditAccount))
                                                                                                                                                    @php($typeCreditAccount=$partsCreditAccount[count($partsCreditAccount)-2])
                                                                                                                                                    @php($idCreditAccount=$partsCreditAccount[count($partsCreditAccount)-1])
                                                                                                                                                @elseif(Illuminate\Support\Facades\Input::old('creditAccount'.$transferNormal->id))
                                                                                                                                                    @php($partsCreditAccountOld=explode('-', old('creditAccount'.$transferNormal->id)))
                                                                                                                                                    @php($typeCreditAccountOld=$partsCreditAccountOld[count($partsCreditAccountOld)-2])
                                                                                                                                                    @php($idCreditAccountOld=$partsCreditAccountOld[count($partsCreditAccountOld)-1])
                                                                                                                                                @endif
                                                                                                                                                @foreach($listPalletsAccounts as $palletsAccount )
                                                                                                                                                    @if(isset($transferNormal->creditAccount)&& ($typeCreditAccount == 'account') && ($palletsAccount->id==$idCreditAccount))
                                                                                                                                                        <option value="account-{{$palletsAccount->id}}"
                                                                                                                                                                selected>{{$palletsAccount->name}}</option>
                                                                                                                                                    @elseif(Illuminate\Support\Facades\Input::old('creditAccount'.$transferNormal->id) && ($typeCreditAccountOld == 'account') && ($palletsAccount->id==$idCreditAccountOld))
                                                                                                                                                        <option value="account-{{$palletsAccount->id}}"
                                                                                                                                                                selected>{{$palletsAccount->name}}</option>
                                                                                                                                                    @else
                                                                                                                                                        <option value="account-{{$palletsAccount->id}}">{{$palletsAccount->name}}</option>
                                                                                                                                                    @endif
                                                                                                                                                @endforeach
                                                                                                                                                @foreach($listTrucksAccounts as $trucksAccount )
                                                                                                                                                    @if(Illuminate\Support\Facades\Input::old('creditAccount'.$transferNormal->id) && ($typeCreditAccountOld == 'truck') && ($trucksAccount->id==$idCreditAccountOld))
                                                                                                                                                        <option value="truck-{{$trucksAccount->id}}"
                                                                                                                                                                selected>{{$trucksAccount->name}}
                                                                                                                                                            - {{$trucksAccount->licensePlate}}</option>
                                                                                                                                                    @elseif(isset($transferNormal->creditAccount)&& ($typeCreditAccount == 'truck') && ($trucksAccount->id==$idCreditAccount))
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
                                                                                                                                        <label for="documentsTransfer{{$transferNormal->id}}"><span>*</span>Proof
                                                                                                                                            docs
                                                                                                                                            ?</label>
                                                                                                                                    </div>
                                                                                                                                    <div class="col-lg-4">
                                                                                                                                        <input type="file"
                                                                                                                                               name="documentsTransfer{{$transferNormal->id}}[]"
                                                                                                                                               multiple
                                                                                                                                               id="documentsTransfer{{$transferNormal->id}}">
                                                                                                                                    </div>
                                                                                                                                    <!--button upload-->
                                                                                                                                    <div class="col-lg-2">
                                                                                                                                        <button type="submit"
                                                                                                                                                class="btn btn-primary btn-block btn-form"
                                                                                                                                                value="{{$transferNormal->id}}"
                                                                                                                                                name="upload">
                                                                                                                                            Upload
                                                                                                                                        </button>
                                                                                                                                    </div>
                                                                                                                                </div>

                                                                                                                            @php($filesNames= \App\Http\Controllers\DetailsLoadingController::actualDocuments($transferNormal->id))
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
                                                                                                                                                                    value="{{$nameF}}-{{$transferNormal->id}}"></button>
                                                                                                                                                            <a href="../../storage/app/proofsPallets/documentsTransfer/{{$transferNormal->id}}/{{$transfer->type}}/{{$nameF}}"
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

                                                                                                                                    @if(!empty($filesNames)&&isset($transferNormal->palletsNumber)&&isset($transferNormal->creditAccount)&&isset($transferNormal->debitAccount))
                                                                                                                                        <div class="col-lg-2">
                                                                                                                                            <label for="validate{{$transferNormal->id}}"
                                                                                                                                                   class="control-label">Validated
                                                                                                                                                ?
                                                                                                                                            </label>
                                                                                                                                        </div>
                                                                                                                                        <div class="col-lg-2 text-left">
                                                                                                                                            @if(isset($transferNormal->validate) && $transferNormal->validate==1)
                                                                                                                                                <label class="radio-inline"><input
                                                                                                                                                            type="radio"
                                                                                                                                                            name="validate{{$transferNormal->id}}"
                                                                                                                                                            value="true"
                                                                                                                                                            checked
                                                                                                                                                            id="validateYes">Yes</label>
                                                                                                                                                <label class="radio-inline"><input
                                                                                                                                                            type="radio"
                                                                                                                                                            name="validate{{$transferNormal->id}}"
                                                                                                                                                            value="false"
                                                                                                                                                            id="validateNo">No</label>
                                                                                                                                            @elseif(isset($transferNormal->validate) && $transferNormal->validate==0)
                                                                                                                                                <label class="radio-inline"><input
                                                                                                                                                            type="radio"
                                                                                                                                                            name="validate{{$transferNormal->id}}"
                                                                                                                                                            value="true"
                                                                                                                                                            id="validateYes">Yes</label>
                                                                                                                                                <label class="radio-inline"><input
                                                                                                                                                            type="radio"
                                                                                                                                                            name="validate{{$transferNormal->id}}"
                                                                                                                                                            value="false"
                                                                                                                                                            checked
                                                                                                                                                            id="validateNo">No</label>
                                                                                                                                            @endif
                                                                                                                                        </div>
                                                                                                                                        <!--submit-->
                                                                                                                                        <div class="col-lg-4 col-lg-offset-1">
                                                                                                                                            <button type="submit"
                                                                                                                                                    class="btn btn-primary btn-block btn-form"
                                                                                                                                                    value="{{$transferNormal->id}}"
                                                                                                                                                    name="submitPallets"
                                                                                                                                                    data-toggle="modal"
                                                                                                                                                    data-target="#submitPallets_modal">
                                                                                                                                                Update
                                                                                                                                            </button>
                                                                                                                                        </div>
                                                                                                                                    @else
                                                                                                                                    <!--submit-->
                                                                                                                                        <div class="col-lg-4 col-lg-offset-5">
                                                                                                                                            <button type="submit"
                                                                                                                                                    class="btn btn-primary btn-block btn-form"
                                                                                                                                                    value="{{$transferNormal->id}}"
                                                                                                                                                    name="submitPallets"
                                                                                                                                                    data-toggle="modal"
                                                                                                                                                    data-target="#submitPallets_modal">
                                                                                                                                                Update
                                                                                                                                            </button>
                                                                                                                                        </div>
                                                                                                                                    @endif
                                                                                                                                    @if(!empty($errorsTransfer))
                                                                                                                                    <!--show addCorrectingTransfer -->
                                                                                                                                        <div class="col-lg-3">
                                                                                                                                            <button type="submit"
                                                                                                                                                    class="btn btn-primary btn-block btn-form"
                                                                                                                                                    value="{{$transferNormal->id}}"
                                                                                                                                                    name="showAddCorrectingTransfer"
                                                                                                                                                    data-toggle="modal"
                                                                                                                                                    data-target="#addForm">
                                                                                                                                                Add
                                                                                                                                                correcting
                                                                                                                                                transfer
                                                                                                                                            </button>
                                                                                                                                        </div>
                                                                                                                                    @endif
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                            @if($transferNormal->state=="Untreated")
                                                                                                                        </div>
                                                                                                                    @elseif ($transferNormal->state=="Waiting documents")
                                                                                                                </div>
                                                                                                            @elseif ($transferNormal->state=="Complete")
                                                                                                        </div>
                                                                                                    @elseif ($transferNormal->state=="Complete Validated")
                                                                                                </div>
                                                                                            @endif

                                                                                            <!-- Modal update -->
                                                                                                @if((isset($submitPalletsNormal)&& $submitPalletsNormal==$transferNormal->id))
                                                                                                    <div class="modal show"
                                                                                                         id="submitPallets_modal"
                                                                                                         role="dialog">
                                                                                                        <div class="modal-dialog modal-lg">
                                                                                                            <div class="modal-content">
                                                                                                                <div class="modal-header modalHeaderTransfer">
                                                                                                                        <button value="{{$transferNormal->id}}"
                                                                                                                                class="close"
                                                                                                                                type="submit"
                                                                                                                                name="closeSubmitPalletsModal">
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
                                                                                                                    @foreach($errorsTransfer as $error)
                                                                                                                        @if($error->name=='DW-WD_notNumberLoadingOrder')
                                                                                                                            <div class="text-center">
                                                                                                                                <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                                                                <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                                                                <span class="text-danger">Pallets number does NOT MATCH the number expected in the loading order ({{$loading->anz}})</span>
                                                                                                                            </div>
                                                                                                                        @endif
                                                                                                                        @if($error->name=='Donly-Wonly_notSameNumber')
                                                                                                                            <div class="text-center">
                                                                                                                                <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                                                                <span class="glyphicon glyphicon-warning-sign text-danger"></span><span
                                                                                                                                        class="text-danger"> Sum of deposit only transfers does NOT MATCH the sum of withdrawal only transfers </span>
                                                                                                                            </div>
                                                                                                                        @endif
                                                                                                                    @endforeach
                                                                                                                </div>
                                                                                                                <div class="modal-footer">
                                                                                                                        @if(!empty($errorsTransfer))
                                                                                                                            <button type="submit"
                                                                                                                                    class="btn btn-danger btn-modal"
                                                                                                                                    value="{{$transferNormal->id}}"
                                                                                                                                    name="okSubmitPalletsModal"
                                                                                                                                    data-toggle="modal"
                                                                                                                                    data-target="#submitPalletsValidate_modal">
                                                                                                                                Confirm
                                                                                                                            </button>
                                                                                                                        @else
                                                                                                                            <button type="submit"
                                                                                                                                    class="btn btn-default btn-form btn-modal"
                                                                                                                                    value="{{$transferNormal->id}}"
                                                                                                                                    name="okSubmitPalletsModal"
                                                                                                                                    data-toggle="modal"
                                                                                                                                    data-target="#submitPalletsValidate_modal">
                                                                                                                                Confirm
                                                                                                                            </button>
                                                                                                                        @endif
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                @endif

                                                                                            <!-- Modal update validate -->
                                                                                                @if((isset($okSubmitPalletsModalNormal) && $okSubmitPalletsModalNormal==$transferNormal->id && $transferNormal->state=='Complete Validated'))
                                                                                                    <div class="modal show"
                                                                                                         id="submitPalletsValidate_modal"
                                                                                                         role="dialog">
                                                                                                        <div class="modal-dialog modal-lg">
                                                                                                            <div class="modal-content">
                                                                                                                <div class="modal-header modalHeaderTransfer">
                                                                                                                   <button value="{{$transferNormal->id}}"
                                                                                                                                class="close"
                                                                                                                                type="submit"
                                                                                                                                name="closeSubmitValidatePalletsModal">
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
                                                                                                                    @foreach($errorsTransfer as $error)
                                                                                                                        @if($error->name=='DW-WD_notNumberLoadingOrder')
                                                                                                                            <div class="text-center">
                                                                                                                                <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                                                                <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                                                                <span class="text-danger">Pallets number does NOT MATCH the number expected in the loading order ({{$loading->anz}}
                                                                                                                                    )</span>
                                                                                                                            </div>
                                                                                                                        @endif
                                                                                                                        @if($error->name=='Donly-Wonly_notSameNumber')
                                                                                                                            <div class="text-center">
                                                                                                                                <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                                                                <span class="glyphicon glyphicon-warning-sign text-danger"></span><span
                                                                                                                                        class="text-danger"> Sum of deposit only transfers does NOT MATCH the sum of withdrawal only transfers </span>
                                                                                                                            </div>
                                                                                                                        @endif
                                                                                                                    @endforeach
                                                                                                                </div>
                                                                                                                <div class="modal-footer">
                                                                                                                        @if(!empty($errorsTransfer))
                                                                                                                            <button type="submit"
                                                                                                                                    class="btn btn-danger btn-modal"
                                                                                                                                    value="{{$transferNormal->id}}"
                                                                                                                                    name="okSubmitPalletsValidateModal">
                                                                                                                                Confirm
                                                                                                                            </button>
                                                                                                                        @else
                                                                                                                            <button type="submit"
                                                                                                                                    class="btn btn-default btn-form btn-modal"
                                                                                                                                    value="{{$transferNormal->id}}"
                                                                                                                                    name="okSubmitPalletsValidateModal">
                                                                                                                                Confirm
                                                                                                                            </button>
                                                                                                                        @endif
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                @endif
                                                                                        @endforeach
<!------------CORRECTING TRANSFERS-------->
                                                                                        <div class="form-group text-center">
                                                                                            <label for="correcting"
                                                                                                   class="control-label text-center">CORRECTING</label>
                                                                                        </div>
                                                                                        @foreach($listPalletstransfersCorrecting as $transferCorrecting)
                                                                                            @if($transferCorrecting->state=="Untreated")
                                                                                                <div class="panel panelUntreated">
                                                                                                    @elseif ($transferCorrecting->state=="Waiting documents")
                                                                                                        <div class="panel panelWaitingdocuments">
                                                                                                            @elseif ($transferCorrecting->state=="Complete")
                                                                                                                <div class="panel panelComplete">
                                                                                                                    @elseif ($transferCorrecting->state=="Complete Validated")
                                                                                                                        <div class="panel panel-general">
                                                                                                                            @endif
                                                                                                                            <div class="panel-heading">
                                                                                                                                <div class="col-lg-11 text-left headerSubpanelDetailsLoading">
                                                                                                                                    <a data-toggle="collapse"
                                                                                                                                       href="#PanSubcollapse{{$transferCorrecting->id}}">Transfer {{$transferCorrecting->id}}
                                                                                                                                    </a>@php($errorsTransfer=\App\Http\Controllers\PalletstransfersController::actualErrors($transferCorrecting))
                                                                                                                                    @if(!empty($errorsTransfer))
                                                                                                                                        @foreach($errorsTransfer as $error)
                                                                                                                                            <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                                                                        @endforeach
                                                                                                                                    @endif
                                                                                                                                </div>
                                                                                                                                <div>
                                                                                                                                    <button type="submit"
                                                                                                                                            class=" btn btn-primary btn-form glyphicon glyphicon-remove"
                                                                                                                                            value="{{$transferCorrecting->id}}"
                                                                                                                                            name="delete"
                                                                                                                                    ></button>
                                                                                                                                </div>

                                                                                                                            </div>
                                                                                                                            <div id="PanSubcollapse{{$transferCorrecting->id}}"
                                                                                                                                 class="panel-collapse collapse panel-body">
                                                                                                                                <div class="form-group">
                                                                                                                                    <!--type-->
                                                                                                                                    <div class="col-lg-1">
                                                                                                                                        <label for="type{{$transferCorrecting->id}}"
                                                                                                                                               class="control-label"><span>*</span>Type
                                                                                                                                            :</label>
                                                                                                                                    </div>
                                                                                                                                    <div class="col-lg-2">
                                                                                                                                        @if(isset($transferCorrecting->validate) && $transferCorrecting->validate==1)
                                                                                                                                            <input type="text"
                                                                                                                                                   name="type{{$transferCorrecting->id}}"
                                                                                                                                                   class="form-control"
                                                                                                                                                   value="{{$transferCorrecting->type}}"
                                                                                                                                                   readonly>
                                                                                                                                        @else
                                                                                                                                            <select class="selectpicker show-tick form-control"
                                                                                                                                                    data-size="5"
                                                                                                                                                    data-live-search="true"
                                                                                                                                                    data-live-search-style="startsWith"
                                                                                                                                                    title="Type"
                                                                                                                                                    name="type{{$transferCorrecting->id}}"
                                                                                                                                                    id="type{{$transferCorrecting->id}}"
                                                                                                                                                    onchange="displayFieldsTypeIdCorrecting(this, id);">
                                                                                                                                                @if(Illuminate\Support\Facades\Input::old('type'))
                                                                                                                                                    <optgroup
                                                                                                                                                            label="Correcting">
                                                                                                                                                        <option @if(old('type'.$transferCorrecting->id) == 'Purchase-Sale') selected
                                                                                                                                                                @endif value="Purchase-Sale"
                                                                                                                                                                id="Purchase-SaleOption{{$transferCorrecting->id}}">
                                                                                                                                                            Purchase-Sale
                                                                                                                                                        </option>
                                                                                                                                                        <option @if(old('type'.$transferCorrecting->id) == 'Sale-Purchase') selected
                                                                                                                                                                @endif value="Sale-Purchase"
                                                                                                                                                                id="Sale-PurchaseOption{{$transferCorrecting->id}}">
                                                                                                                                                            Sale-Purchase
                                                                                                                                                        </option>
                                                                                                                                                        <option @if(old('type'.$transferCorrecting->id) == 'Other') selected
                                                                                                                                                                @endif value="Other"
                                                                                                                                                                id="OtherOption{{$transferCorrecting->id}}">
                                                                                                                                                            Other
                                                                                                                                                        </option>
                                                                                                                                                    </optgroup>
                                                                                                                                                @elseif(isset($transferCorrecting->type))
                                                                                                                                                    <optgroup
                                                                                                                                                            label="Correcting">
                                                                                                                                                        <option @if($transferCorrecting->type == 'Purchase-Sale') selected
                                                                                                                                                                @endif value="Purchase-Sale"
                                                                                                                                                                id="Purchase-SaleOption{{$transferCorrecting->id}}">
                                                                                                                                                            Purchase-Sale
                                                                                                                                                        </option>
                                                                                                                                                        <option @if($transferCorrecting->type == 'Sale-Purchase') selected
                                                                                                                                                                @endif value="Sale-Purchase"
                                                                                                                                                                id="Sale-PurchaseOption{{$transferCorrecting->id}}">
                                                                                                                                                            Sale-Purchase
                                                                                                                                                        </option>
                                                                                                                                                        <option @if($transferCorrecting->type == 'Other') selected
                                                                                                                                                                @endif value="Other"
                                                                                                                                                                id="OtherOption{{$transferCorrecting->id}}">
                                                                                                                                                            Other
                                                                                                                                                        </option>
                                                                                                                                                    </optgroup>
                                                                                                                                                @else
                                                                                                                                                    <optgroup
                                                                                                                                                            label="Correcting">
                                                                                                                                                        <option value="Purchase-Sale"
                                                                                                                                                                id="Purchase-SaleOption{{$transferCorrecting->id}}">
                                                                                                                                                            Purchase-Sale
                                                                                                                                                        </option>
                                                                                                                                                        <option value="Sale-Purchase"
                                                                                                                                                                id="Sale-PurchaseOption{{$transferCorrecting->id}}">
                                                                                                                                                            Sale-Purchase
                                                                                                                                                        </option>
                                                                                                                                                        <option value="Other"
                                                                                                                                                                id="otherOption{{$transferCorrecting->id}}">
                                                                                                                                                            Other
                                                                                                                                                        </option>
                                                                                                                                                    </optgroup>
                                                                                                                                                @endif
                                                                                                                                            </select>
                                                                                                                                        @endif
                                                                                                                                    </div>
                                                                                                                                    <!--details-->
                                                                                                                                    <div class="col-lg-4">
                                                                                                                                        @if(isset($transferCorrecting->details)&&(isset($transferCorrecting->validate) && $transferCorrecting->validate==1))
                                                                                                                                            <textarea
                                                                                                                                                    class="form-control"
                                                                                                                                                    rows="1"
                                                                                                                                                    name="details{{$transferCorrecting->id}}"
                                                                                                                                                    placeholder="Details"
                                                                                                                                                    readonly>{{$transferCorrecting->details}}</textarea>
                                                                                                                                        @elseif(isset($transferCorrecting->details))
                                                                                                                                            <textarea
                                                                                                                                                    class="form-control"
                                                                                                                                                    rows="1"
                                                                                                                                                    name="details{{$transferCorrecting->id}}"
                                                                                                                                                    placeholder="Details">{{$transferCorrecting->details}}</textarea>
                                                                                                                                        @elseif(isset($transferCorrecting->validate) && $transferCorrecting->validate==1)
                                                                                                                                            <textarea
                                                                                                                                                    class="form-control"
                                                                                                                                                    rows="1"
                                                                                                                                                    name="details{{$transferCorrecting->id}}"
                                                                                                                                                    placeholder="Details"
                                                                                                                                                    readonly>{{old('details'.$transferCorrecting->id)}}</textarea>
                                                                                                                                        @else
                                                                                                                                            <textarea
                                                                                                                                                    class="form-control"
                                                                                                                                                    rows="1"
                                                                                                                                                    id="details{{$transferCorrecting->id}}"
                                                                                                                                                    placeholder="Details">{{old('details'.$transferCorrecting->id)}}</textarea>
                                                                                                                                        @endif
                                                                                                                                    </div>
                                                                                                                                    <!--date-->
                                                                                                                                    <div class="col-lg-2">
                                                                                                                                        @if(isset($transferCorrecting->date)&&(isset($transferCorrecting->validate) && $transferCorrecting->validate==1))
                                                                                                                                            <input id="date{{$transferCorrecting->id}}"
                                                                                                                                                   type="date"
                                                                                                                                                   class="form-control"
                                                                                                                                                   name="date{{$transferCorrecting->id}}"
                                                                                                                                                   value="{{ $transferCorrecting->date }}"
                                                                                                                                                   placeholder="Date"
                                                                                                                                                   autofocus
                                                                                                                                                   readonly>
                                                                                                                                        @elseif(isset($transferCorrecting->date))
                                                                                                                                            <input id="date{{$transferCorrecting->id}}"
                                                                                                                                                   type="date"
                                                                                                                                                   class="form-control"
                                                                                                                                                   name="date{{$transferCorrecting->id}}"
                                                                                                                                                   value="{{ $transferCorrecting->date }}"
                                                                                                                                                   placeholder="Date"
                                                                                                                                                   required
                                                                                                                                                   autofocus>

                                                                                                                                        @elseif(isset($transferCorrecting->validate) && $transferCorrecting->validate==1)
                                                                                                                                            <input id="date{{$transferCorrecting->id}}"
                                                                                                                                                   type="date"
                                                                                                                                                   class="form-control"
                                                                                                                                                   name="date{{$transferCorrecting->id}}"
                                                                                                                                                   value="{{ old('date'.$transferCorrecting->id) }}"
                                                                                                                                                   placeholder="Date"
                                                                                                                                                   autofocus
                                                                                                                                                   readonly>
                                                                                                                                        @else(Illuminate\Support\Facades\Input::old('date'))
                                                                                                                                            <input id="date{{$transferCorrecting->id}}"
                                                                                                                                                   type="date"
                                                                                                                                                   class="form-control"
                                                                                                                                                   name="date{{$transferCorrecting->id}}"
                                                                                                                                                   value="{{ old('date'.$transferCorrecting->id) }}"
                                                                                                                                                   placeholder="Date"
                                                                                                                                                   autofocus
                                                                                                                                                   required>
                                                                                                                                        @endif
                                                                                                                                    </div>
                                                                                                                                    <!--transfer normal associated-->
                                                                                                                                    <div class="col-lg-2 text-right">
                                                                                                                                        <label for="normalTransferAssociated{{$transferCorrecting->id}}"
                                                                                                                                               class="control-label"><span>*</span>
                                                                                                                                            Associated
                                                                                                                                            :</label>
                                                                                                                                    </div>
                                                                                                                                    <div class="col-lg-1">
                                                                                                                                        @if(isset($transferCorrecting->normalTransferAssociated)&&(isset($transferCorrecting->validate) && $transferCorrecting->validate==1))
                                                                                                                                            <input type="text"
                                                                                                                                                   name="normalTransferAssociated{{$transferCorrecting->id}}"
                                                                                                                                                   class="form-control"
                                                                                                                                                   value="{{$transferCorrecting->normalTransferAssociated}}"
                                                                                                                                                   readonly>
                                                                                                                                        @elseif(isset($transferCorrecting->validate) && $transferCorrecting->validate==1)
                                                                                                                                            <input type="text"
                                                                                                                                                   name="normalTransferAssociated{{$transferCorrecting->id}}"
                                                                                                                                                   class="form-control"
                                                                                                                                                   value="{{old('normalTransferAssociated')}}"
                                                                                                                                                   readonly>
                                                                                                                                        @else
                                                                                                                                            <select class="selectpicker show-tick form-control"
                                                                                                                                                    data-size="5"
                                                                                                                                                    data-live-search="true"
                                                                                                                                                    data-live-search-style="startsWith"
                                                                                                                                                    title="Normal transfer associated"
                                                                                                                                                    name="normalTransferAssociated{{$transferCorrecting->id}}">
                                                                                                                                                @foreach($listPalletstransfersNormal as $normalTransfer )
                                                                                                                                                    @if(Illuminate\Support\Facades\Input::old('normalTransferAssociated') && $normalTransfer->id==old('normalTransferAssociated'))
                                                                                                                                                        <option value="{{$normalTransfer->id}}"
                                                                                                                                                                selected>{{$normalTransfer->id}}</option>
                                                                                                                                                    @elseif(isset($transferCorrecting->normalTransferAssociated)&&$normalTransfer->id==$transferCorrecting->normalTransferAssociated)
                                                                                                                                                        <option value="{{$normalTransfer->id}}"
                                                                                                                                                                selected>{{$normalTransfer->id}}</option>
                                                                                                                                                    @else
                                                                                                                                                        <option value="{{$normalTransfer->id}}">{{$normalTransfer->id}}</option>
                                                                                                                                                    @endif
                                                                                                                                                @endforeach
                                                                                                                                            </select>
                                                                                                                                        @endif
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                                <div class="form-group">
                                                                                                                                    <!--number of pallets-->
                                                                                                                                    <div class="col-lg-1">
                                                                                                                                        <label for="palletsNumber{{$transferCorrecting->id}}"
                                                                                                                                               class="control-label"><span>*</span>Pal.
                                                                                                                                            :</label>
                                                                                                                                    </div>
                                                                                                                                    <div class="col-lg-1">
                                                                                                                                        @if(Illuminate\Support\Facades\Input::old('palletsNumber'.$transfer->id))
                                                                                                                                            <input id="palletsNumber{{$transferCorrecting->id}}"
                                                                                                                                                   type="number"
                                                                                                                                                   class="form-control"
                                                                                                                                                   name="palletsNumber{{$transferCorrecting->id}}"
                                                                                                                                                   value="{{ old('palletsNumber'.$transferCorrecting->id) }}"
                                                                                                                                                   placeholder="Nbr"
                                                                                                                                                   min="0"
                                                                                                                                                   required
                                                                                                                                                   autofocus>
                                                                                                                                        @elseif(isset($transferCorrecting->validate) && $transferCorrecting->validate==1)
                                                                                                                                            <input id="palletsNumber{{$transferCorrecting->id}}"
                                                                                                                                                   type="number"
                                                                                                                                                   class="form-control"
                                                                                                                                                   name="palletsNumber{{$transferCorrecting->id}}"
                                                                                                                                                   value="{{$transferCorrecting->palletsNumber}}"
                                                                                                                                                   placeholder="Nbr"
                                                                                                                                                   min="0"
                                                                                                                                                   autofocus
                                                                                                                                                   readonly>
                                                                                                                                        @else
                                                                                                                                            <input id="palletsNumber{{$transferCorrecting->id}}"
                                                                                                                                                   type="number"
                                                                                                                                                   class="form-control"
                                                                                                                                                   name="palletsNumber{{$transferCorrecting->id}}"
                                                                                                                                                   value="{{$transferCorrecting->palletsNumber}}"
                                                                                                                                                   placeholder="Nbr"
                                                                                                                                                   min="0"
                                                                                                                                                   autofocus>
                                                                                                                                        @endif
                                                                                                                                    </div>

                                                                                                                                    <!--debit account-->
                                                                                                                                    <div class="col-lg-2"
                                                                                                                                         id="debitAccount1{{$transferCorrecting->id}}"
                                                                                                                                         style="display: block">
                                                                                                                                        <label for="debitAccount{{$transferCorrecting->id}}"
                                                                                                                                               class="control-label"><span>*</span>
                                                                                                                                            Debit
                                                                                                                                            account
                                                                                                                                            :</label>
                                                                                                                                    </div>
                                                                                                                                    <div class="col-lg-3"
                                                                                                                                         id="debitAccount2{{$transferCorrecting->id}}"
                                                                                                                                         style="display: block">
                                                                                                                                        @if(isset($transferCorrecting->validate) && $transferCorrecting->validate==1)
                                                                                                                                            @php($partsDebitAccount=explode('-',$transferCorrecting->debitAccount))
                                                                                                                                            @php($aprim=$partsDebitAccount[count($partsDebitAccount)-1])
                                                                                                                                            @php($bprim=$partsDebitAccount[count($partsDebitAccount)-2])
                                                                                                                                            @if(count(array_diff ($partsDebitAccount, [$aprim, $bprim]))==1)
                                                                                                                                                @php($debitAccountValidate=array_diff ($partsDebitAccount, [$aprim, $bprim])[0])
                                                                                                                                            @else
                                                                                                                                                @php($debitAccountValidate=implode(' - ', array_diff ($partsDebitAccount, [$aprim, $bprim])))
                                                                                                                                            @endif
                                                                                                                                            <input type="text"
                                                                                                                                                   name="debitAccount{{$transferCorrecting->id}}"
                                                                                                                                                   class="form-control"
                                                                                                                                                   value="{{$debitAccountValidate}}"
                                                                                                                                                   readonly>
                                                                                                                                        @else
                                                                                                                                            <select class="selectpicker show-tick form-control"
                                                                                                                                                    data-size="10"
                                                                                                                                                    data-live-search="true"
                                                                                                                                                    data-live-search-style="startsWith"
                                                                                                                                                    title="Debit Account"
                                                                                                                                                    name="debitAccount{{$transferCorrecting->id}}"
                                                                                                                                                    id="select-debit{{$transferCorrecting->id}}"
                                                                                                                                                    onchange="selectAccountK(this.value, id);"
                                                                                                                                            >
                                                                                                                                                @if(isset($transferCorrecting->debitAccount))
                                                                                                                                                    @php($partsDebitAccount=explode('-', $transferCorrecting->debitAccount))
                                                                                                                                                    @php($typeDebitAccount=$partsDebitAccount[count($partsDebitAccount)-2])
                                                                                                                                                    @php($idDebitAccount=$partsDebitAccount[count($partsDebitAccount)-1])
                                                                                                                                                @elseif(Illuminate\Support\Facades\Input::old('debitAccount'.$transferCorrecting->id))
                                                                                                                                                    @php($partsDebitAccountOld=explode('-', old('debitAccount'.$transferCorrecting->id)))
                                                                                                                                                    @php($typeDebitAccountOld=$partsDebitAccountOld[count($partsDebitAccountOld)-2])
                                                                                                                                                    @php($idDebitAccountOld=$partsDebitAccountOld[count($partsDebitAccountOld)-1])
                                                                                                                                                @endif
                                                                                                                                                @foreach($listPalletsAccounts as $palletsAccount )
                                                                                                                                                    @if(isset($transferCorrecting->debitAccount)&& ($typeDebitAccount == 'account') && ($palletsAccount->id==$idDebitAccount))
                                                                                                                                                        <option value="account-{{$palletsAccount->id}}"
                                                                                                                                                                selected>{{$palletsAccount->name}}</option>
                                                                                                                                                    @elseif(Illuminate\Support\Facades\Input::old('debitAccount'.$transferCorrecting->id) && ($typeDebitAccountOld == 'account') && ($palletsAccount->id==$idDebitAccountOld))
                                                                                                                                                        <option value="account-{{$palletsAccount->id}}"
                                                                                                                                                                selected>{{$palletsAccount->name}}</option>
                                                                                                                                                    @else
                                                                                                                                                        <option value="account-{{$palletsAccount->id}}">{{$palletsAccount->name}}</option>
                                                                                                                                                    @endif
                                                                                                                                                @endforeach
                                                                                                                                                @foreach($listTrucksAccounts as $trucksAccount )
                                                                                                                                                    @if(Illuminate\Support\Facades\Input::old('debitAccount'.$transferCorrecting->id) && ($typeDebitAccountOld == 'truck') && ($trucksAccount->id==$idDebitAccountOld))
                                                                                                                                                        <option value="truck-{{$trucksAccount->id}}"
                                                                                                                                                                selected>{{$trucksAccount->name}}
                                                                                                                                                            - {{$trucksAccount->licensePlate}}</option>
                                                                                                                                                    @elseif(isset($transferCorrecting->debitAccount)&& ($typeDebitAccount== 'truck') && ($trucksAccount->id==$idDebitAccount))
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
                                                                                                                                    <div class="col-lg-2"
                                                                                                                                         id="creditAccount1{{$transferCorrecting->id}}"
                                                                                                                                         style="display: block">

                                                                                                                                        <label for="creditAccount{{$transferCorrecting->id}}"
                                                                                                                                               class="control-label"><span>*</span>
                                                                                                                                            Credit
                                                                                                                                            account
                                                                                                                                            :</label>
                                                                                                                                    </div>
                                                                                                                                    <div class="col-lg-3"
                                                                                                                                         id="creditAccount2{{$transferCorrecting->id}}"
                                                                                                                                         style="display: block">
                                                                                                                                        @if(isset($transferCorrecting->validate) && $transferCorrecting->validate==1)
                                                                                                                                            @php($partsCreditAccount=explode('-',$transferCorrecting->creditAccount))
                                                                                                                                            @php($a=$partsCreditAccount[count($partsCreditAccount)-1])
                                                                                                                                            @php($b=$partsCreditAccount[count($partsCreditAccount)-2])
                                                                                                                                            @if(count(array_diff ($partsCreditAccount, [$a, $b]))==1)
                                                                                                                                                @php($creditAccountValidate=array_diff ($partsCreditAccount, [$a, $b])[0])
                                                                                                                                            @else
                                                                                                                                                @php($creditAccountValidate=implode( ' - ', array_diff ($partsCreditAccount, [$a, $b])))
                                                                                                                                            @endif
                                                                                                                                            <input type="text"
                                                                                                                                                   name="creditAccount{{$transferCorrecting->id}}"
                                                                                                                                                   class="form-control"
                                                                                                                                                   value="{{$creditAccountValidate}}"
                                                                                                                                                   readonly>
                                                                                                                                        @else
                                                                                                                                            <select class="selectpicker show-tick form-control"
                                                                                                                                                    data-size="10"
                                                                                                                                                    data-live-search="true"
                                                                                                                                                    data-live-search-style="startsWith"
                                                                                                                                                    title="Credit Account"
                                                                                                                                                    name="creditAccount{{$transferCorrecting->id}}"
                                                                                                                                                    id="select-credit{{$transferCorrecting->id}}"
                                                                                                                                                    onchange="creditaccountK(this.value, id);"
                                                                                                                                            >
                                                                                                                                                @if(isset($transferCorrecting->creditAccount))
                                                                                                                                                    @php($partsCreditAccount=explode('-', $transferCorrecting->creditAccount))
                                                                                                                                                    @php($typeCreditAccount=$partsCreditAccount[count($partsCreditAccount)-2])
                                                                                                                                                    @php($idCreditAccount=$partsCreditAccount[count($partsCreditAccount)-1])
                                                                                                                                                @elseif(Illuminate\Support\Facades\Input::old('creditAccount'.$transferCorrecting->id))
                                                                                                                                                    @php($partsCreditAccountOld=explode('-', old('creditAccount'.$transferCorrecting->id)))
                                                                                                                                                    @php($typeCreditAccountOld=$partsCreditAccountOld[count($partsCreditAccountOld)-2])
                                                                                                                                                    @php($idCreditAccountOld=$partsCreditAccountOld[count($partsCreditAccountOld)-1])
                                                                                                                                                @endif
                                                                                                                                                @foreach($listPalletsAccounts as $palletsAccount )
                                                                                                                                                    @if(isset($transferCorrecting->creditAccount)&& ($typeCreditAccount == 'account') && ($palletsAccount->id==$idCreditAccount))
                                                                                                                                                        <option value="account-{{$palletsAccount->id}}"
                                                                                                                                                                selected>{{$palletsAccount->name}}</option>
                                                                                                                                                    @elseif(Illuminate\Support\Facades\Input::old('creditAccount'.$transferCorrecting->id) && ($typeCreditAccountOld == 'account') && ($palletsAccount->id==$idCreditAccountOld))
                                                                                                                                                        <option value="account-{{$palletsAccount->id}}"
                                                                                                                                                                selected>{{$palletsAccount->name}}</option>
                                                                                                                                                    @else
                                                                                                                                                        <option value="account-{{$palletsAccount->id}}">{{$palletsAccount->name}}</option>
                                                                                                                                                    @endif
                                                                                                                                                @endforeach
                                                                                                                                                @foreach($listTrucksAccounts as $trucksAccount )
                                                                                                                                                    @if(Illuminate\Support\Facades\Input::old('creditAccount'.$transferCorrecting->id) && ($typeCreditAccountOld == 'truck') && ($trucksAccount->id==$idCreditAccountOld))
                                                                                                                                                        <option value="truck-{{$trucksAccount->id}}"
                                                                                                                                                                selected>{{$trucksAccount->name}}
                                                                                                                                                            - {{$trucksAccount->licensePlate}}</option>
                                                                                                                                                    @elseif(isset($transferCorrecting->creditAccount)&& ($typeCreditAccount == 'truck') && ($trucksAccount->id==$idCreditAccount))
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
                                                                                                                                        <label for="documentsTransfer{{$transferCorrecting->id}}"><span>*</span>Proof
                                                                                                                                            docs
                                                                                                                                            ?</label>
                                                                                                                                    </div>
                                                                                                                                    <div class="col-lg-4">
                                                                                                                                        <input type="file"
                                                                                                                                               name="documentsTransfer{{$transferCorrecting->id}}[]"
                                                                                                                                               multiple
                                                                                                                                               id="documentsTransfer{{$transferCorrecting->id}}">
                                                                                                                                    </div>
                                                                                                                                    <!--button upload-->
                                                                                                                                    <div class="col-lg-2">
                                                                                                                                        <button type="submit"
                                                                                                                                                class="btn btn-primary btn-block btn-form"
                                                                                                                                                value="{{$transferCorrecting->id}}"
                                                                                                                                                name="upload">
                                                                                                                                            Upload
                                                                                                                                        </button>
                                                                                                                                    </div>
                                                                                                                                    <!--add account-->
                                                                                                                                    <div class="col-lg-2 col-lg-offset-2">
                                                                                                                                        <a href="{{route('showAddPalletsaccount')}}"
                                                                                                                                           class="link"><span
                                                                                                                                                    class="glyphicon glyphicon-plus-sign"></span>
                                                                                                                                            Add
                                                                                                                                            account</a>
                                                                                                                                    </div>
                                                                                                                                </div>

                                                                                                                            @php($filesNames= \App\Http\Controllers\DetailsLoadingController::actualDocuments($transferCorrecting->id))
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
                                                                                                                                                                    value="{{$nameF}}-{{$transferCorrecting->id}}"></button>
                                                                                                                                                            <a href="../../storage/app/proofsPallets/documentsTransfer/{{$transferCorrecting->id}}/{{$transfer->type}}/{{$nameF}}"
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
                                                                                                                                    @if(!empty($filesNames)&&isset($transferCorrecting->palletsNumber)&&isset($transferCorrecting->creditAccount)&&isset($transferCorrecting->debitAccount))
                                                                                                                                        <div class="col-lg-2">
                                                                                                                                            <label for="validate{{$transferCorrecting->id}}"
                                                                                                                                                   class="control-label">Validated
                                                                                                                                                ?
                                                                                                                                            </label>
                                                                                                                                        </div>
                                                                                                                                        <div class="col-lg-2 text-left">
                                                                                                                                            @if(isset($transferCorrecting->validate) && $transferCorrecting->validate==1)
                                                                                                                                                <label class="radio-inline"><input
                                                                                                                                                            type="radio"
                                                                                                                                                            name="validate{{$transferCorrecting->id}}"
                                                                                                                                                            value="true"
                                                                                                                                                            checked
                                                                                                                                                            id="validateYes">Yes</label>
                                                                                                                                                <label class="radio-inline"><input
                                                                                                                                                            type="radio"
                                                                                                                                                            name="validate{{$transferCorrecting->id}}"
                                                                                                                                                            value="false"
                                                                                                                                                            id="validateNo">No</label>
                                                                                                                                            @elseif(isset($transferCorrecting->validate) && $transferCorrecting->validate==0)
                                                                                                                                                <label class="radio-inline"><input
                                                                                                                                                            type="radio"
                                                                                                                                                            name="validate{{$transferCorrecting->id}}"
                                                                                                                                                            value="true"
                                                                                                                                                            id="validateYes">Yes</label>
                                                                                                                                                <label class="radio-inline"><input
                                                                                                                                                            type="radio"
                                                                                                                                                            name="validate{{$transferCorrecting->id}}"
                                                                                                                                                            value="false"
                                                                                                                                                            checked
                                                                                                                                                            id="validateNo">No</label>
                                                                                                                                            @endif
                                                                                                                                        </div>
                                                                                                                                        <!--submit-->
                                                                                                                                        <div class="col-lg-4 col-lg-offset-1">
                                                                                                                                            <button type="submit"
                                                                                                                                                    class="btn btn-primary btn-block btn-form"
                                                                                                                                                    value="{{$transferCorrecting->id}}"
                                                                                                                                                    name="submitPallets"
                                                                                                                                                    data-toggle="modal"
                                                                                                                                                    data-target="#submitPallets_modal">
                                                                                                                                                Update
                                                                                                                                            </button>
                                                                                                                                        </div>
                                                                                                                                    @else
                                                                                                                                    <!--submit-->
                                                                                                                                        <div class="col-lg-4 col-lg-offset-5">
                                                                                                                                            <button type="submit"
                                                                                                                                                    class="btn btn-primary btn-block btn-form"
                                                                                                                                                    value="{{$transferCorrecting->id}}"
                                                                                                                                                    name="submitPallets"
                                                                                                                                                    data-toggle="modal"
                                                                                                                                                    data-target="#submitPallets_modal">
                                                                                                                                                Update
                                                                                                                                            </button>
                                                                                                                                        </div>
                                                                                                                                    @endif
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                            @if($transferCorrecting->state=="Untreated")
                                                                                                                        </div>
                                                                                                                    @elseif ($transferCorrecting->state=="Waiting documents")
                                                                                                                </div>
                                                                                                            @elseif ($transferCorrecting->state=="Complete")
                                                                                                        </div>
                                                                                                    @elseif ($transferCorrecting->state=="Complete Validated")
                                                                                                </div>
                                                                                            @endif

                                                                                            <!-- Modal update -->
                                                                                                @if((isset($submitPalletsCorrecting)&& $submitPalletsCorrecting==$transferCorrecting->id))
                                                                                                    <div class="modal show"
                                                                                                         id="submitPallets_modal"
                                                                                                         role="dialog">
                                                                                                        <div class="modal-dialog modal-lg">
                                                                                                            <div class="modal-content">
                                                                                                                <div class="modal-header modalHeaderTransfer">
                                                                                                                        <button value="{{$transferCorrecting->id}}"
                                                                                                                                class="close"
                                                                                                                                type="submit"
                                                                                                                                name="closeSubmitPalletsModal">
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
                                                                                                                    <!--partie identique a transfer normal-->
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
<!-->
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
                                                                                                                    @foreach($errorsTransfer as $error)
                                                                                                                        @if($error->name=='Correcting_notCompleteNormal')
                                                                                                                            <div class="text-center">
                                                                                                                                <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                                                                <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                                                                <span class="text-danger">Pallets number does NOT CORRECT the number expected in the loading order ({{$loading->anz}}
                                                                                                                                    )</span>
                                                                                                                            </div>
                                                                                                                        @endif
                                                                                                                        @if($error->name=='SP-PS_notSameNumber')
                                                                                                                            <div class="text-center">
                                                                                                                                <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                                                                <span class="glyphicon glyphicon-warning-sign text-danger"></span><span
                                                                                                                                        class="text-danger"> Pallets numbers are DIFFERENT for both transfers </span>
                                                                                                                            </div>
                                                                                                                        @endif
                                                                                                                    @endforeach
                                                                                                                </div>
                                                                                                                <div class="modal-footer">
                                                                                                                        @if(!empty($errorsTransfer))
                                                                                                                            <button type="submit"
                                                                                                                                    class="btn btn-danger btn-modal"
                                                                                                                                    value="{{$transferCorrecting->id}}"
                                                                                                                                    name="okSubmitPalletsModal"
                                                                                                                                    data-toggle="modal"
                                                                                                                                    data-target="#submitPalletsValidate_modal">
                                                                                                                                Confirm
                                                                                                                            </button>
                                                                                                                        @else
                                                                                                                            <button type="submit"
                                                                                                                                    class="btn btn-default btn-form btn-modal"
                                                                                                                                    value="{{$transferCorrecting->id}}"
                                                                                                                                    name="okSubmitPalletsModal"
                                                                                                                                    data-toggle="modal"
                                                                                                                                    data-target="#submitPalletsValidate_modal">
                                                                                                                                Confirm
                                                                                                                            </button>
                                                                                                                        @endif
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                @endif

                                                                                            <!-- Modal update validate -->
                                                                                                @if((isset($okSubmitPalletsModalCorrecting) && $okSubmitPalletsModalCorrecting==$transferCorrecting->id && $transferCorrecting->state=='Complete Validated'))
                                                                                                    <div class="modal show"
                                                                                                         id="submitPalletsValidate_modal"
                                                                                                         role="dialog">
                                                                                                        <div class="modal-dialog modal-lg">
                                                                                                            <div class="modal-content">
                                                                                                                <div class="modal-header modalHeaderTransfer">
                                                                                                                        <button value="{{$transferCorrecting->id}}"
                                                                                                                                class="close"
                                                                                                                                type="submit"
                                                                                                                                name="closeSubmitValidatePalletsModal">
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
                                                                                                                    @foreach($errorsTransfer as $error)
                                                                                                                        @if($error->name=='Correcting_notCompleteNormal')
                                                                                                                            <div class="text-center">
                                                                                                                                <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                                                                <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                                                                <span class="text-danger">Pallets number does NOT CORRECT the number expected in the loading order ({{$loading->anz}}
                                                                                                                                    )</span>
                                                                                                                            </div>
                                                                                                                        @endif
                                                                                                                        @if($error->name=='SP-PS_notSameNumber')
                                                                                                                            <div class="text-center">
                                                                                                                                <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                                                                <span class="glyphicon glyphicon-warning-sign text-danger"></span><span
                                                                                                                                        class="text-danger"> Pallets numbers are DIFFERENT for both transfers </span>
                                                                                                                            </div>
                                                                                                                        @endif
                                                                                                                    @endforeach
                                                                                                                </div>
                                                                                                                <div class="modal-footer">
                                                                                                                        @if(!empty($errorsTransfer))
                                                                                                                            <button type="submit"
                                                                                                                                    class="btn btn-danger btn-modal"
                                                                                                                                    value="{{$transferCorrecting->id}}"
                                                                                                                                    name="okSubmitPalletsValidateModal">
                                                                                                                                Confirm
                                                                                                                            </button>
                                                                                                                        @else
                                                                                                                            <button type="submit"
                                                                                                                                    class="btn btn-default btn-form btn-modal"
                                                                                                                                    value="{{$transferCorrecting->id}}"
                                                                                                                                    name="okSubmitPalletsValidateModal">
                                                                                                                                Confirm
                                                                                                                            </button>
                                                                                                                        @endif
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                @endif
                                                                                        @endforeach
                                                                                    </form>
                                                                                </div>
                                                                                @if(Session::has('openPanelPallets'))
                                                                            </div>
                                                                            @else
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if($loading->state=="In progress")
                                            </div>
                                        @elseif ($loading->state=="Waiting documents")
                                    </div>
                                @elseif ($loading->state=="Complete")
                            </div>
                        @elseif ($loading->state=="Complete Validated")
                    </div>
                @else
            </div>
        @endif
        @endif
    </div>

    <script>
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
    <script type="text/javascript" src="{{asset('js/addUpdateTransferLoading.js')}}">
    </script>
@endsection