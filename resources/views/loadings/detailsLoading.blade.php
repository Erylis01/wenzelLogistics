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
                        {{--<span class="col-lg-offset-7">{{$state}}</span>--}}
                    </div>
                    <div class="panel-body panel-body-general">

                        <!--subpanel 1 reading form suming up information from the table-->
                        <div class="panel subpanel">
                            <div class="panel-heading">
                                <a data-toggle="collapse" href="#Pan1collapse">Information</a>
                            </div>
                            <div id="Pan1collapse" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <form class="form-horizontal" role="form"
                                          method="POST"
                                          action="{{route('updateDetailsLoading', $atrnr)}}">
                                        <input type="hidden"
                                               name="_token"
                                               value="{{ csrf_token() }}">
                                        @if (Session::has('messageUpdateLoading'))
                                            <div class="alert alert-success text-alert text-center">{{ Session::get('messageUpdateLoading') }}</div>
                                        @endif

                                    <!-- subpanel general-->
                                        <div class="panel subpanel">
                                            <div class="panel-heading">
                                                <a data-toggle="collapse" href="#PanSub3collapse">General</a>
                                            </div>
                                            <div id="PanSub3collapse" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <div class="form-group">
                                                        <!--referenz-->
                                                        <div class="col-lg-6">
                                                            <div class="input-group details-loading">
                                                                <label for="referenz"
                                                                       class="input-group-addon">Referenz
                                                                    :</label>
                                                                <input type="text" name="referenz"
                                                                       class="form-control" value="{{ $referenz }}"
                                                                       placeholder="referenz" required>
                                                            </div>
                                                        </div>
                                                        <!--disp-->
                                                        <div class="col-lg-2">
                                                        <div class="input-group details-loading">
                                                            <label for="disp" class="input-group-addon">Disp
                                                                :</label>
                                                            <input type="text" name="disp"
                                                                   class="form-control" value="{{ $disp }}"
                                                                   placeholder="disp" required>
                                                            @if ($errors->has('disp'))
                                                                <span class="help-block">
                                        <strong>{{ $errors->first('disp') }}</strong>
                                    </span>
                                                            @endif
                                                        </div>
                                                        </div>
                                                        <!--change pt-->
                                                        <!--pt-->
                                                        <div class="col-lg-2 col-lg-offset-2">
                                                            <div class="input-group details-loading">
                                                                <label for="pt" class="input-group-addon">PT
                                                                    :</label>

                                                                <input type="text" readonly name="pt"
                                                                       class="form-control link"
                                                                       data-toggle="modal"
                                                                       data-target="#updatePT_modal"
                                                                       value="{{ $pt }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-lg-12">
                                                            <!--auftraggeber-->
                                                                <div class="input-group details-loading">
                                                                    <label for="auftraggeber"
                                                                           class="input-group-addon">Auftraggeber
                                                                        :</label> <input type="text" name="auftraggeber"
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
                                                                <input type="text" name="subfrachter"
                                                                       class="form-control"
                                                                       value="{{ $subfrachter }}"
                                                                       placeholder="subfrachter" required>
                                                            </div>
                                                        </div>
                                                        <!--kennzeichen-->
                                                        <div class="col-lg-4">
                                                            <div class="input-group details-loading">
                                                                <label for="kennzeichen" class="input-group-addon">Kennzeichen
                                                                    :</label>
                                                                <input type="number" name="kennzeichen"
                                                                       class="form-control"
                                                                       value="{{ $kennzeichen }}"
                                                                       placeholder="kennzeichen" min="0">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <!--anz-->
                                                            <div class="col-lg-2 details-loading">
                                                                <input type="number" name="anz"
                                                                       class="form-control" value="{{ $anz }}"
                                                                       placeholder="anz." min="0" required>
                                                            </div>
                                                        <div class="col-lg-1 details-loading text-center">-</div>
                                                        <!--art-->
                                                        <div class="col-lg-3 details-loading">
                                                                <input type="text" name="art"
                                                                       class="form-control" value="{{ $art }}"
                                                                       placeholder="art" required>
                                                        </div>
                                                        <div class="col-lg-1 details-loading text-center">-</div>
                                                        <!--ware-->
                                                        <div class="col-lg-5 details-loading">
                                                                <input type="text" name="ware"
                                                                       class="form-control" value="{{ $ware }}"
                                                                       placeholder="ware" required>
                                                        </div>
                                                    </div>
                                                    {{--<div class="form-group">--}}
                                                        {{--<!--gewicht-->--}}
                                                        {{--<div class="col-lg-4">--}}
                                                            {{--<div class="input-group details-loading">--}}
                                                                {{--<label for="gewicht"--}}
                                                                       {{--class="input-group-addon">Gewicht (Kg)--}}
                                                                    {{--:</label>--}}
                                                                {{--<input type="number" name="gewicht"--}}
                                                                       {{--class="form-control" value="{{$gewicht}}"--}}
                                                                       {{--placeholder="gewicht" min="0" required>--}}
                                                            {{--</div>--}}
                                                        {{--</div>--}}
                                                        {{--<!--vol-->--}}
                                                        {{--<div class="col-lg-4">--}}
                                                            {{--<div class="input-group details-loading">--}}
                                                                {{--<label for="vol" class="input-group-addon">--}}
                                                                    {{--Vol :</label>--}}
                                                                {{--<input type="number" step="0.1" name="vol"--}}
                                                                       {{--class="form-control" value="{{ $vol }}"--}}
                                                                       {{--placeholder="vol" min="0">--}}
                                                            {{--</div>--}}
                                                        {{--</div>--}}
                                                        {{--<!--ldm-->--}}
                                                        {{--<div class="col-lg-4">--}}
                                                            {{--<div class="input-group details-loading">--}}
                                                                {{--<label for="ldm" class="input-group-addon">--}}
                                                                    {{--LDM :</label>--}}
                                                                {{--<input type="number" step="0.1" name="ldm"--}}
                                                                       {{--class="form-control" value="{{ $ldm }}"--}}
                                                                       {{--placeholder="ldm" min="0" required>--}}
                                                            {{--</div>--}}
                                                        {{--</div>--}}
                                                    {{--</div>--}}
                                                    {{--<div class="form-group">--}}
                                                        {{--<!--umsatz-->--}}
                                                        {{--<div class="col-lg-4">--}}
                                                            {{--<div class="input-group details-loading">--}}
                                                                {{--<label for="umsatz"--}}
                                                                       {{--class="input-group-addon">Umsatz (€)--}}
                                                                    {{--:</label>--}}
                                                                {{--<input type="number" step="0.1" name="umsatz"--}}
                                                                       {{--class="form-control" value="{{ $umsatz }}"--}}
                                                                       {{--placeholder="umsatz" min="0" required>--}}
                                                            {{--</div>--}}
                                                        {{--</div>--}}
                                                        {{--<!--aufwand-->--}}
                                                        {{--<div class="col-lg-4">--}}
                                                            {{--<div class="input-group details-loading">--}}
                                                                {{--<label for="aufwand"--}}
                                                                       {{--class="input-group-addon">Aufwand (€)--}}
                                                                    {{--:</label>--}}
                                                                {{--<input type="number" step="0.1" name="aufwand"--}}
                                                                       {{--class="form-control" value="{{ $aufwand }}"--}}
                                                                       {{--placeholder="aufwand" min="0" required>--}}
                                                            {{--</div>--}}
                                                        {{--</div>--}}
                                                        {{--<!--db-->--}}
                                                        {{--<div class="col-lg-4">--}}
                                                            {{--<div class="input-group details-loading">--}}
                                                                {{--<label for="db" class="input-group-addon">DB (€)--}}
                                                                    {{--:</label>--}}
                                                                {{--<input type="number" step="0.1" name="db"--}}
                                                                       {{--class="form-control" value="{{$db}}"--}}
                                                                       {{--placeholder="db" required readonly>--}}
                                                            {{--</div>--}}
                                                        {{--</div>--}}
                                                    {{--</div>--}}

                                                    <div class="form-group">
                                                        @if (Session::has('messageUpdatePTLoading'))
                                                            <div class="alert alert-warning text-alert text-center">{{ Session::get('messageUpdatePTLoading') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- subpanel loading-->
                                        <div class="panel subpanel col-lg-6">
                                            <div class="panel-heading">
                                                <a class="col-lg-3 text-left" data-toggle="collapse"
                                                   href="#PanSub1collapse">Loading</a>
                                                <div class="col-lg-5 col-lg-offset-1 text-right details-loading">
                                                    <input
                                                            type="date" name="ladedatum"
                                                            class="form-control  text-center" value="{{ $ladedatum }}"
                                                            placeholder="ladedatum" required>
                                                </div>
                                            </div>
                                            <div id="PanSub1collapse" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <br>
                                                    <div class="form-group">
                                                        <!--beladestelle-->
                                                        <div class="col-lg-12 details-loading">
                                                            <input type="text" name="beladestelle"
                                                                   class="form-control text-center"
                                                                   value="{{ $beladestelle }}"
                                                                   placeholder="beladestelle" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <!--ort plz land-->
                                                        <div class="col-lg-3 details-loading">
                                                            <input type="number" name="plzb"
                                                                   class="form-control text-center" value="{{ $plzb }}"
                                                                   placeholder="plz" min="0" required>
                                                        </div>
                                                        <div class="col-lg-1 details-loading">-</div>
                                                        <div class="col-lg-5 details-loading">
                                                            <input type="text" name="ortb"
                                                                   class="form-control text-center" value="{{ $ortb }}"
                                                                   placeholder="ort" required>
                                                        </div>
                                                        <div class="col-lg-1 details-loading">-</div>
                                                        <div class="col-lg-2 details-loading">
                                                            <input type="text" name="landb"
                                                                   class="form-control text-center" value="{{ $landb }}"
                                                                   placeholder="land" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <!--zusladestellen-->
                                                        <div class="col-lg-12">
                                                            <div class="input-group details-loading">
                                                                <label for="zusladestellen"
                                                                       class="input-group-addon">Zus. Ladestellen
                                                                    :</label>
                                                                <input type="text" name="zusladestellen"
                                                                       class="form-control"
                                                                       value="{{ $zusladestellen }}"
                                                                       placeholder="zus ladestellen">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- subpanel unloading-->
                                        <div class="panel subpanel col-lg-6">
                                            <div class="panel-heading">
                                                <a class="col-lg-3 text-left" data-toggle="collapse"
                                                   href="#PanSub2collapse">Unloading</a>
                                                <div class="col-lg-5 col-lg-offset-1 text-right details-loading">
                                                    <input type="date" name="entladedatum"
                                                           class="form-control"
                                                           value="{{ $entladedatum }}"
                                                           placeholder="entladedatum" required>
                                                </div>
                                            </div>
                                            <div id="PanSub2collapse" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <br>
                                                    <div class="form-group">
                                                        <!--entladestelle-->
                                                        <div class="col-lg-12 details-loading">
                                                            <input type="text" name="entladestelle"
                                                                   class="form-control"
                                                                   value="{{ $entladestelle }}"
                                                                   placeholder="entladestelle" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <!--plz-->
                                                        <div class="col-lg-3 details-loading">
                                                            <input type="number" name="plze"
                                                                   class="form-control" value="{{ $plze }}"
                                                                   placeholder="plz" min="0" required>
                                                        </div>
                                                        <div class="col-lg-1 details-loading">-</div>
                                                        <!--ort-->
                                                        <div class="col-lg-5 details-loading">
                                                            <input type="text" name="orte"
                                                                   class="form-control" value="{{ $orte }}"
                                                                   placeholder="ort" required>
                                                        </div>
                                                        <div class="col-lg-1 details-loading">-</div>
                                                        <!--land-->
                                                        <div class="col-lg-2 details-loading">
                                                            <input type="text" name="lande"
                                                                   class="form-control" value="{{ $lande }}"
                                                                   placeholder="land" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
                            </div>
                        </div>

                        <!--subpanel 2-->
                        <div class="panel subpanel">
                            <div class="panel-heading">
                                <a data-toggle="collapse" href="#Pan2collapse">Pallets location ?</a>
                            </div>
                            <div id="Pan2collapse" class="panel-collapse in collapse">
                                <div class="panel-body">
                                    <form class="form-horizontal" role="form"
                                          method="POST"
                                          action="">
                                        <input type="hidden"
                                               name="_token"
                                               value="{{ csrf_token() }}">
                                        <div class="panel subpanel">
                                            <div class="panel-heading">
                                                <a data-toggle="collapse" href="#Pan2Sub1collapse">Loading place</a>
                                            </div>
                                            <div id="Pan2Sub1collapse" class="panel-collapse in collapse">
                                                <div class="panel-body">
                                                    <div class="form-group">
                                                        <div class="col-lg-6">
                                                            Do we need to bring back pallets to the loading place ?
                                                        </div>
                                                        <div>
                                                            <input class="radio-inline" type="radio"
                                                                   name="palletsBackLoadingPlace"
                                                                   value="" data-toggle="collapse"
                                                                   data-target="#yesPalletsBackLoadingPlace_collapse:not(.in)"/>Yes
                                                            <input class="radio-inline" type="radio"
                                                                   name="palletsBackLoadingPlace"
                                                                   value="" data-toggle="collapse"
                                                                   data-target="#yesPalletsBackLoadingPlace_collapse.in"/>No
                                                        </div>
                                                    </div>
                                                    <div id="yesPalletsBackLoadingPlace_collapse"
                                                         class="form-group collapse">
                                                        <div class="form-group">
                                                            <div class="col-lg-4 col-lg-offset-1">
                                                                How many pallets planned ?
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-lg-4 col-lg-offset-1">
                                                                How many pallets confirmed ?
                                                            </div>
                                                            <div class="col-lg-6 col-lg-offset-1">
                                                                Is the situation verified ? => update account warehouse
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-lg-6">
                                                            Do the loading place needs to bring me back pallets ?
                                                        </div>
                                                        <div>
                                                            <input class="radio-inline" type="radio"
                                                                   name="palletsBackMeLoadingPlace"
                                                                   value="" data-toggle="collapse"
                                                                   data-target="#yesPalletsBackMeLoadingPlace_collapse:not(.in)"/>Yes
                                                            <input class="radio-inline" type="radio"
                                                                   name="palletsBackMeLoadingPlace"
                                                                   value="" data-toggle="collapse"
                                                                   data-target="#yesPalletsBackMeLoadingPlace_collapse.in"/>No
                                                        </div>
                                                    </div>
                                                    <div id="yesPalletsBackMeLoadingPlace_collapse"
                                                         class="form-group collapse">
                                                        <div class="form-group">
                                                            <div class="col-lg-4 col-lg-offset-1">
                                                                How many pallets planned ?
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-lg-5 col-lg-offset-1">
                                                                Do we have the pallets order ?
                                                            </div>
                                                            <div>
                                                                <input class="radio-inline" type="radio"
                                                                       name="palletsOrderLoadingPlace"
                                                                       value="" data-toggle="collapse"
                                                                       data-parent="#accordion"
                                                                       data-target="#yesPalletsOrderLoadingPlace_collapse:not(.in)"/>Yes
                                                                <input class="radio-inline" type="radio"
                                                                       name="palletsOrderLoadingPlace"
                                                                       value="" data-toggle="collapse"
                                                                       data-parent="#accordion"
                                                                       data-target="#noPalletsOrderLoadingPlace_collapse:not(.in)"/>No
                                                                <input class="radio-inline" type="radio"
                                                                       name="palletsOrderLoadingPlace"
                                                                       value="" data-toggle="collapse"
                                                                       data-parent="#accordion"
                                                                       data-target="#noNecesaryPalletsOrderLoadingPlace_collapse:not(.in)"/>No
                                                                necessary, pallets have been brought back
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="accordion">
                                                        <div id="yesPalletsOrderLoadingPlace_collapse"
                                                             class="form-group collapse">
                                                            <div class="col-lg-5 col-lg-offset-3">
                                                                Load pallets order
                                                            </div>
                                                        </div>
                                                        <div id="noPalletsOrderLoadingPlace_collapse"
                                                             class="form-group collapse">
                                                            <div class="col-lg-5 col-lg-offset-3">
                                                                Send reminder -> block account -> payment -> no update
                                                                account
                                                            </div>
                                                        </div>
                                                        <div id="noNecessaryPalletsOrderLoadingPlace_collapse"
                                                             class="form-group collapse">
                                                            <div class="col-lg-5 col-lg-offset-3">
                                                                OK update account warehouse
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-lg-6">
                                                            Do we have the documents ?
                                                        </div>
                                                        <div>
                                                            <input class="radio-inline" type="radio"
                                                                   name="DocLoadingPlace"
                                                                   value="" data-toggle="collapse"
                                                                   data-parent="#accordionDoc"
                                                                   data-target="#yesDocLoadingPlace_collapse:not(.in)"/>Yes
                                                            <input class="radio-inline" type="radio"
                                                                   name="DocLoadingPlace"
                                                                   value="" data-toggle="collapse"
                                                                   data-parent="#accordionDoc"
                                                                   data-target="#noDocLoadingPlace_collapse.in"/>No
                                                        </div>
                                                    </div>
                                                    <div id="accordion">
                                                        <div id="yesDocLoadingPlace_collapse"
                                                             class="form-group collapse">
                                                            <div class="col-lg-5 col-lg-offset-3">
                                                                Load documents
                                                            </div>
                                                        </div>
                                                        <div id="noDocLoadingPlace_collapse"
                                                             class="form-group collapse">
                                                            <div class="col-lg-5 col-lg-offset-3">
                                                                Send reminder
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        si 1 yes final + 3 yes => selectionner compte à actualiser :
                                                        beladestelle = nom entrepot ?
                                                        idem 2yes final + 3yes
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel subpanel">
                                            <div class="panel-heading">
                                                <a data-toggle="collapse" href="#Pan2Sub2collapse">Unloading place</a>
                                            </div>
                                            <div id="Pan2Sub2collapse" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel subpanel">
                                            <div class="panel-heading">
                                                <a data-toggle="collapse" href="#Pan2Sub3collapse">Carrier</a>
                                            </div>
                                            <div id="Pan2Sub3collapse" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel subpanel">
                                            <div class="panel-heading">
                                                <a data-toggle="collapse" href="#Pan2Sub4collapse">Other place</a>
                                            </div>
                                            <div id="Pan2Sub4collapse" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>


                        {{--<!--subpanel-->--}}
                        {{--<div class="panel subpanel">--}}
                        {{--<div class="panel-heading">--}}
                        {{--@if($sum==0 || $sum==null)--}}
                        {{--@php($class="text-alert")--}}
                        {{--@endif--}}
                        {{--<a data-toggle="collapse" href="#Pan2collapse">Verification--}}
                        {{--and Validation - Pallets transfers</a><span--}}
                        {{--class="col-lg-offset-6">TOTAL = {{$sum}}</span>--}}
                        {{--</div>--}}
                        {{--<div id="Pan2collapse" class="panel-collapse collapse">--}}
                        {{--<div class="panel-body">--}}
                        {{--<div class="table-responsive">--}}
                        {{--<p class="text-center"><span--}}
                        {{--class="glyphicon glyphicon-plus glyphicon-green"></span> TOTAL--}}
                        {{--= {{$sumPlus}}--}}
                        {{--</p>--}}
                        {{--<table class="table table-hover table-bordered table-loading-pallets">--}}
                        {{--<thead>--}}
                        {{--@if($totalpallets<0)--}}
                        {{--@php($class="text-alert")--}}
                        {{--@elseif($totalpallets>0)--}}
                        {{--@php($class="text-warning")--}}
                        {{--@else--}}
                        {{--@php($class="text-success")--}}
                        {{--@endif--}}
                        {{--<tr>--}}
                        {{--<th class="text-center">ID</th>--}}
                        {{--<th class="text-center">Pallets account</th>--}}
                        {{--<th class="text-center">Real pallets--}}
                        {{--number--}}
                        {{--</th>--}}
                        {{--<th class="text-center">Validated ?</th>--}}
                        {{--<th class="text-center">Theorical pallets--}}
                        {{--number--}}
                        {{--</th>--}}
                        {{--<th class="text-center">Documents ?</th>--}}
                        {{--<th class="text-center">Date last reminder--}}
                        {{--</th>--}}
                        {{--<th class="text-center">Reminders number--}}
                        {{--</th>--}}
                        {{--<th class="text-center colTotal"><span class={{$class}}>{{$totalpallets}}</span></th>--}}
                        {{--</tr>--}}
                        {{--</thead>--}}
                        {{--<tbody>--}}
                        {{--@foreach($palletstransfersPlus as $transfersPlus)--}}
                        {{--<tr>--}}
                        {{--<td class="text-center"><a--}}
                        {{--href="{{route('showDetailsPalletstransfer',$transfersPlus->id)}}"--}}
                        {{--class="link">{{$transfersPlus->id}}</a></td>--}}
                        {{--<td class="text-center">{{$transfersPlus->palletsaccount_name}}</td>--}}
                        {{--<td class="text-center">{{$transfersPlus->realPalletsNumber}}</td>--}}
                        {{--@if($transfersPlus->state==false)--}}
                        {{--<td class="text-center">No</td>--}}
                        {{--@else--}}
                        {{--<td class="text-center">Yes</td>--}}
                        {{--@endif--}}
                        {{--<td class="text-center">{{$transfersPlus->palletsNumber}}</td>--}}
                        {{--@if($transfersPlus->documents==false)--}}
                        {{--<td class="text-center">No</td>--}}
                        {{--@else--}}
                        {{--<td class="text-center">Yes</td>--}}
                        {{--@endif--}}
                        {{--<td class="text-center">{{$transfersPlus->dateLastReminder}}</td>--}}
                        {{--<td class="text-center">{{$transfersPlus->remindersNumber}}</td>--}}
                        {{--</tr>--}}
                        {{--@endforeach--}}
                        {{--</tbody>--}}
                        {{--</table>--}}
                        {{--</div>--}}

                        {{--<div class="table-responsive">--}}
                        {{--<p class="text-center"><span--}}
                        {{--class="glyphicon glyphicon-minus glyphicon-green"></span> TOTAL--}}
                        {{--= {{$sumMinus}}--}}
                        {{--</p>--}}
                        {{--<table class="table table-hover table-loading-pallets table-bordered">--}}
                        {{--<thead>--}}
                        {{--@if($totalpallets<0)--}}
                        {{--@php($class="text-alert")--}}
                        {{--@elseif($totalpallets>0)--}}
                        {{--@php($class="text-warning")--}}
                        {{--@else--}}
                        {{--@php($class="text-success")--}}
                        {{--@endif--}}
                        {{--<tr>--}}
                        {{--<th class="text-center">ID</th>--}}
                        {{--<th class="text-center">Pallets account</th>--}}
                        {{--<th class="text-center">Real pallets--}}
                        {{--number--}}
                        {{--</th>--}}
                        {{--<th class="text-center">Validated ?</th>--}}
                        {{--<th class="text-center">Theorical pallets--}}
                        {{--number--}}
                        {{--</th>--}}
                        {{--<th class="text-center">Documents ?</th>--}}
                        {{--<th class="text-center">Date last reminder--}}
                        {{--</th>--}}
                        {{--<th class="text-center">Reminders number--}}
                        {{--</th>--}}
                        {{--<th class="text-center colTotal"><span class={{$class}}>{{$totalpallets}}</span></th>--}}
                        {{--</tr>--}}
                        {{--</thead>--}}
                        {{--<tbody>--}}
                        {{--@foreach($palletstransfersMinus as $transfersMinus)--}}
                        {{--<tr>--}}
                        {{--<td class="text-center"><a--}}
                        {{--href="{{route('showDetailsPalletstransfer',$transfersMinus->id)}}"--}}
                        {{--class="link">{{$transfersMinus->id}}</a></td>--}}
                        {{--<td class="text-center">{{$transfersMinus->palletsaccount_name}}</td>--}}
                        {{--<td class="text-center">{{$transfersMinus->realPalletsNumber}}</td>--}}
                        {{--@if($transfersMinus->state==false)--}}
                        {{--<td class="text-center">No</td>--}}
                        {{--@else--}}
                        {{--<td class="text-center">Yes</td>--}}
                        {{--@endif--}}
                        {{--<td class="text-center">{{$transfersMinus->palletsNumber}}</td>--}}
                        {{--@if($transfersMinus->documents==false)--}}
                        {{--<td class="text-center">No</td>--}}
                        {{--@else--}}
                        {{--<td class="text-center">Yes</td>--}}
                        {{--@endif--}}
                        {{--<td class="text-center">{{$transfersMinus->dateLastReminder}}</td>--}}
                        {{--<td class="text-center">{{$transfersMinus->remindersNumber}}</td>--}}
                        {{--</tr>--}}
                        {{--@endforeach--}}
                        {{--</tbody>--}}
                        {{--</table>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                    </div>
                </div>
                {{--</div>--}}
                {{--</div>--}}
                {{--</div>--}}
            </div>
        @endif
    </div>
@endsection