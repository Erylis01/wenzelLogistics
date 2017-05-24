@extends('layouts.default')

@section('title')
    Details loading
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

                @if($state=="OK")
                    <div class="panel panel-general">
                        @elseif($state=="almost OK")
                            <div class="panel panel-warning">
                                @elseif ($state=="not OK")
                                    <div class="panel panel-danger">
                                        @else
                                            <div class="panel panel-default">
                                                @endif
                                                <div class="panel-heading">Details of the loading n°{{ $atrnr }}
                                                    <span class="col-lg-offset-7">{{$state}}</span>
                                                </div>
                                                <div class="panel-body panel-body-general form-loading">
                                                    <div class="panel-group">
                                                        <div class="panel subpanel">
                                                            <div class="panel-heading">
                                                                <a data-toggle="collapse" href="#Pan1collapse">Information from table all loadings</a>
                                                            </div>
                                                            <div id="Pan1collapse" class="panel-collapse collapse">
                                                                <div class="panel-body">
                                                                    <!--reading form suming up information from the table-->
                                                                    <form class="form-horizontal" role="form"
                                                                          method="POST"
                                                                          action="">
                                                                        {{ csrf_field() }}

                                                                        <div class="form-group">
                                                                            <!--ladedatum-->
                                                                            <div class="col-lg-4">
                                                                                <div class="input-group details-loading">
                                                                                <label for="ladedatum"
                                                                                       class="input-group-addon">Ladedatum
                                                                                    :</label>
                                                                                <input type="date" name="ladedatum"
                                                                                       class="form-control" value="{{ $ladedatum }}" placeholder="ladedatum" required>
                                                                            </div>
                                                                            </div>
                                                                            <!--entladedatum-->
                                                                            <div class="col-lg-4">
                                                                                <div class="input-group details-loading">
                                                                                <label for="entladedatum"
                                                                                       class="input-group-addon">Entladedatum
                                                                                    :</label>
                                                                                <input type="date" name="entladedatum"
                                                                                       class="form-control" value="{{ $entladedatum }}" placeholder="entladedatum" required>
                                                                            </div>
                                                                            </div>
                                                                            <!--disp-->
                                                                            <div class="col-lg-4">
                                                                                <div class="input-group details-loading">
                                                                                <label for="disp" class="input-group-addon">Disp
                                                                                    :</label>
                                                                                <input type="text" name="disp"
                                                                                       class="form-control" value="{{ $disp }}" placeholder="disp" required>
                                                                            </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <!--referenz-->
                                                                            <div class="col-lg-4">
                                                                                <div class="input-group details-loading">
                                                                                <label for="referenz"
                                                                                       class="input-group-addon">Referenz
                                                                                    :</label>
                                                                                <input type="text" name="referenz"
                                                                                       class="form-control" value="{{ $referenz }}" placeholder="referenz" required >
                                                                            </div>
                                                                            </div>
                                                                            <!--auftraggeber-->
                                                                            <div class="col-lg-8">
                                                                                <div class="input-group details-loading">
                                                                                <label for="auftraggeber"
                                                                                       class="input-group-addon">Auftraggeber
                                                                                    :</label> <input type="text" name="auftraggeber"
                                                                                       class="form-control" value="{{ $auftraggeber }}" placeholder="auftraggeber" required>
                                                                            </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <!--beladestelle-->
                                                                            <div class="col-lg-4">
                                                                                <div class="input-group details-loading">
                                                                                <label for="beladestelle"
                                                                                       class="input-group-addon">Beladestelle
                                                                                    :</label>
                                                                                <input type="text" name="beladestelle"
                                                                                       class="form-control" value="{{ $beladestelle }}" placeholder="beladestelle" required>
                                                                            </div>
                                                                            </div>
                                                                            <!--ort-->
                                                                            <div class="col-lg-4">
                                                                                <div class="input-group details-loading">
                                                                                <label for="ortb" class="input-group-addon">Ort
                                                                                    :</label>
                                                                                <input type="text" for="ortb"
                                                                                       class="form-control" value="{{ $ortb }}" placeholder="ort" required>
                                                                            </div>
                                                                            </div>
                                                                            <!--plz-->
                                                                            <div class="col-lg-2">
                                                                                <div class="input-group details-loading">
                                                                                <label for="plzb" class="input-group-addon">Plz
                                                                                    :</label>
                                                                                <input type="number" name="plzb"
                                                                                       class="form-control" value="{{ $plzb }}" placeholder="plz" min="0" required>
                                                                            </div>
                                                                            </div>
                                                                            <!--land-->
                                                                            <div class="col-lg-2">
                                                                                <div class="input-group details-loading">
                                                                                <label for="landb"
                                                                                       class="input-group-addon">Land
                                                                                    :</label>
                                                                                <input type="text" name="landb"
                                                                                       class="form-control" value="{{ $landb }}" placeholder="land" required>
                                                                            </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <!--entladestelle-->
                                                                            <div class="col-lg-4">
                                                                                <div class="input-group details-loading">
                                                                                <label for="entladestelle"
                                                                                       class="input-group-addon">Entladestelle
                                                                                    :</label>
                                                                                <input type="text" name="entladestelle"
                                                                                       class="form-control" value="{{ $entladestelle }}" placeholder="entladestelle" required>
                                                                            </div>
                                                                            </div>
                                                                            <!--ort-->
                                                                            <div class="col-lg-4">
                                                                                <div class="input-group details-loading">
                                                                                <label for="orte" class="input-group-addon">Ort
                                                                                    :</label>
                                                                                <input type="text" name="orte"
                                                                                       class="form-control" value="{{ $orte }}" placeholder="ort" required>
                                                                            </div>
                                                                            </div>
                                                                            <!--plz-->
                                                                            <div class="col-lg-2">
                                                                                <div class="input-group details-loading">
                                                                                <label for="plze" class="input-group-addon">Plz
                                                                                    :</label>
                                                                                <input type="number" name="plze"
                                                                                       class="form-control" value="{{ $plze }}" placeholder="plz" min="0" required>
                                                                            </div>
                                                                            </div>
                                                                            <!--land-->
                                                                            <div class="col-lg-2">
                                                                                <div class="input-group details-loading">
                                                                                <label for="lande"
                                                                                       class="input-group-addon">Land
                                                                                    :</label>
                                                                                <input type="text" name="lande"
                                                                                       class="form-control" value="{{ $lande }}" placeholder="land" required>
                                                                            </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <!--anzahl-->
                                                                            <div class="col-lg-4">
                                                                                <div class="input-group details-loading">
                                                                                <label for="anz"
                                                                                       class="input-group-addon">Anzahl
                                                                                    :</label>
                                                                                <input type="number" name="anz"
                                                                                       class="form-control" value="{{ $anz }}" placeholder="anzahl" min="0" required>
                                                                            </div>
                                                                            </div>
                                                                            <!--art-->
                                                                            <div class="col-lg-4">
                                                                                <div class="input-group details-loading">
                                                                                <label for="art" class="input-group-addon">
                                                                                   Art :</label>
                                                                                <input type="text" name="art"
                                                                                       class="form-control" value="{{ $art }}" placeholder="art" required>
                                                                            </div>
                                                                            </div>
                                                                            <!--ware-->
                                                                            <div class="col-lg-4">
                                                                                <div class="input-group details-loading">
                                                                                <label for="ware" class="input-group-addon">Ware
                                                                                    :</label>
                                                                                <input type="text" name="ware"
                                                                                       class="form-control" value="{{ $ware }}" placeholder="ware" required>
                                                                            </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <!--gewicht-->
                                                                            <div class="col-lg-4">
                                                                            <div class="input-group details-loading">
                                                                                <label for="gewicht"
                                                                                       class="input-group-addon">Gewicht (Kg)
                                                                                    :</label>
                                                                                <input type="number" name="gewicht"
                                                                                       class="form-control" value="{{$gewicht}}" placeholder="gewicht" min="0" required>
                                                                            </div>
                                                                            </div>
                                                                            <!--vol-->
                                                                            <div class="col-lg-4">
                                                                            <div class="input-group details-loading">
                                                                                <label for="vol" class="input-group-addon">
                                                                                   Vol :</label>
                                                                                <input type="number" step="0.1" name="vol"
                                                                                       class="form-control" value="{{ $vol }}" placeholder="vol" min="0">
                                                                            </div>
                                                                            </div>
                                                                            <!--ldm-->
                                                                            <div class="col-lg-4">
                                                                            <div class="input-group details-loading">
                                                                                <label for="ldm" class="input-group-addon">
                                                                                    LDM :</label>
                                                                                <input type="number" step="0.1" name="ldm"
                                                                                       class="form-control" value="{{ $ldm }}" placeholder="ldm" min="0" required>
                                                                            </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <!--umsatz-->
                                                                            <div class="col-lg-4">
                                                                                <div class="input-group details-loading">
                                                                                <label for="umsatz"
                                                                                       class="input-group-addon">Umsatz (€)
                                                                                    :</label>
                                                                                <input type="number" step="0.1" name="umsatz"
                                                                                       class="form-control" value="{{ $umsatz }}" placeholder="umsatz" min="0" required>
                                                                            </div>
                                                                            </div>
                                                                            <!--aufwand-->
                                                                            <div class="col-lg-4">
                                                                                <div class="input-group details-loading">
                                                                                <label for="aufwand"
                                                                                       class="input-group-addon">Aufwand (€)
                                                                                    :</label>
                                                                                <input type="number" step="0.1" name="aufwand"
                                                                                       class="form-control" value="{{ $aufwand }}" placeholder="aufwand" min="0"required>
                                                                            </div>
                                                                            </div>
                                                                            <!--db-->
                                                                            <div class="col-lg-4">
                                                                                <div class="input-group details-loading">
                                                                                <label for="db" class="input-group-addon">DB (€)
                                                                                    :</label>
                                                                                <input type="number" step="0.1" name="db"
                                                                                       class="form-control" value="{{ $db }}" placeholder="db" required>
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
                                                                                <input type="text" name="subfrachter"
                                                                                       class="form-control" value="{{ $subfrachter }}" placeholder="subfrachter" required>
                                                                            </div>
                                                                            </div>
                                                                            <!--trp-->
                                                                            <div class="col-lg-2">
                                                                                <div class="input-group details-loading">
                                                                                <label for="trp" class="input-group-addon">Trp
                                                                                    :</label>
                                                                                <input type="number" name="trp"
                                                                                       class="form-control" value="{{ $trp }}" placeholder="trp" required>
                                                                            </div>
                                                                            </div>
                                                                            <!--change pt-->
                                                                            <!--pt-->
                                                                            <div class="col-lg-2">
                                                                                <div class="input-group details-loading">
                                                                                <label for="pt" class="input-group-addon">PT
                                                                                    :</label>

                                                                                <input type="text" readonly name="pt" class="form-control link"
                                                                                       data-toggle="modal"
                                                                                       data-target="#updatePT_modal" value="{{ $pt }}">
                                                                            </div>
                                                                            </div>
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
                                                                                                                      action="{{ route('saveDetailsLoading', $atrnr) }}">
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
                                                                        <div class="form-group">
                                                                            @if (Session::has('messageUpdatePTLoading'))
                                                                                <div class="alert alert-warning text-alert text-center">{{ Session::get('messageUpdatePTLoading') }}
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <!--kennzeichen-->
                                                                            <div class="col-lg-4">
                                                                                <div class="input-group details-loading">
                                                                                <label for="kennzeichen" class="input-group-addon">Kennzeichen
                                                                                    :</label>
                                                                                <input type="number" name="kennzeichen"
                                                                                       class="form-control" value="{{ $kennzeichen }}" placeholder="kennzeichen" min="0">
                                                                            </div>
                                                                            </div>
                                                                            <!--zusladestellen-->
                                                                            <div class="col-lg-4">
                                                                                <div class="input-group details-loading">
                                                                                <label for="zusladestellen"
                                                                                       class="input-group-addon">Zus. Ladestellen
                                                                                    :</label>
                                                                                <input type="text" name="zusladestellen"
                                                                                       class="form-control" value="{{ $zusladestellen }}" placeholder="zus ladestellen">
                                                                            </div>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="panel subpanel">
                                                            <div class="panel-heading">
                                                                <a data-toggle="collapse" href="#Pan2collapse">Information to add</a>
                                                            </div>
                                                            <div id="Pan2collapse" class="panel-collapse collapse">
                                                                <div class="panel-body">
                                                                    <!--form to edit loading-->
                                                                    <form class="form-horizontal" role="form"
                                                                          method="POST"
                                                                          action="{{route('saveDetailsLoading', $atrnr)}}">
                                                                        <input type="hidden" name="_token"
                                                                               value="{{ csrf_token() }}">
                                                                        <input type="hidden" name="id" value={{$atrnr}}>
                                                                        @if (Session::has('messageSaveLoading'))
                                                                            <div class="alert alert-success text-alert text-center">{{ Session::get('messageSaveLoading') }}</div>
                                                                    @endif

                                                                    <!--ruckgabewo-->
                                                                        <div class="form-group {{ $errors->has('ruckgabewo') ? ' has-error' : '' }}">
                                                                            <label for="ruckgabewo"
                                                                                   class="col-lg-3 col-lg-offset-1 control-label">Ruckgabe
                                                                                Wo
                                                                                ?</label>
                                                                            <div class="col-lg-6">
                                                                                <input id="ruckgabewo" type="text"
                                                                                       class="form-control"
                                                                                       name="ruckgabewo"
                                                                                       value="{{ $ruckgabewo }}"
                                                                                       placeholder="Ruckgabe Wo"
                                                                                       autofocus>

                                                                                @if ($errors->has('ruckgabewo'))
                                                                                    <span class="help-block">
                                        <strong>{{ $errors->first('ruckgabewo') }}</strong>
                                    </span>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        <!--mahnung-->
                                                                        <div class="form-group {{ $errors->has('mahnung') ? ' has-error' : '' }}">
                                                                            <label for="mahnung"
                                                                                   class="col-lg-3 col-lg-offset-1 control-label">Mahnung
                                                                                :</label>
                                                                            <div class="col-lg-6">
                                                                                <input id="mahnung" type="text"
                                                                                       class="form-control"
                                                                                       name="mahnung"
                                                                                       value="{{ $mahnung }}"
                                                                                       placeholder="Mahnung"
                                                                                       autofocus>

                                                                                @if ($errors->has('mahnung'))
                                                                                    <span class="help-block">
                                        <strong>{{ $errors->first('mahnung') }}</strong>
                                    </span>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        <!--blockierung-->
                                                                        <div class="form-group {{ $errors->has('blockierung') ? ' has-error' : '' }}">
                                                                            <label for="blockierung"
                                                                                   class="col-lg-3 col-lg-offset-1 control-label">Blockierung
                                                                                :</label>
                                                                            <div class="col-lg-6">
                                                                                <input id="blockierung" type="text"
                                                                                       class="form-control"
                                                                                       name="blockierung"
                                                                                       value="{{ $blockierung }}"
                                                                                       placeholder="Blockierung"
                                                                                       autofocus>

                                                                                @if ($errors->has('blockierung'))
                                                                                    <span class="help-block">
                                        <strong>{{ $errors->first('blockierung') }}</strong>
                                    </span>
                                                                                @endif
                                                                            </div>
                                                                            <div class="col-lg-2">
                                                                                <input type="submit"
                                                                                       class="btn btn-primary btn-block btn-form"
                                                                                       value="Save"
                                                                                       name="save">
                                                                            </div>
                                                                        </div>
                                                                        <!--bearbeitungsdatum-->
                                                                        <div class="form-group {{ $errors->has('bearbeitungsdatum') ? ' has-error' : '' }}">
                                                                            <label for="bearbeitungsdatum"
                                                                                   class="col-lg-3 col-lg-offset-1 control-label">Bearbeitungsdatum
                                                                                :</label>
                                                                            <div class="col-lg-6">
                                                                                <input id="bearbeitungsdatum"
                                                                                       type="date"
                                                                                       class="form-control"
                                                                                       name="bearbeitungsdatum"
                                                                                       value="{{ $bearbeitungsdatum }}"
                                                                                       placeholder="bearbeitungsdatum"
                                                                                       autofocus>

                                                                                @if ($errors->has('bearbeitungsdatum'))
                                                                                    <span class="help-block">
                                        <strong>{{ $errors->first('bearbeitungsdatum') }}</strong>
                                    </span>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        <!--palgebucht-->
                                                                        <div class="form-group {{ $errors->has('palgebucht') ? ' has-error' : '' }}">
                                                                            <label for="palgebucht"
                                                                                   class="col-lg-3 col-lg-offset-1 control-label">Pal
                                                                                gebucht
                                                                                ?</label>
                                                                            <div class="col-lg-6">
                                                                                <input id="palgebucht" type="text"
                                                                                       class="form-control"
                                                                                       name="palgebucht"
                                                                                       value="{{ $palgebucht }}"
                                                                                       placeholder="Pal gebucht"
                                                                                       autofocus>

                                                                                @if ($errors->has('palgebucht'))
                                                                                    <span class="help-block">
                                        <strong>{{ $errors->first('palgebucht') }}</strong>
                                    </span>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="panel subpanel">
                                                            <div class="panel-heading ">
                                                                <a data-toggle="collapse" href="#Pan3collapse">Recap pallets chart</a>
                                                            </div>
                                                            <div id="Pan3collapse" class="panel-collapse collapse">
                                                                <div class="panel-body">
                                                                    <div class="table-responsive loadings-container">
                                                                        <table class="table table-hover  table-bordered table-loading-pallets">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>TOTAL</th>
                                                                                <th>Fakturiert</th>
                                                                                <th>Verschenkt</th>
                                                                                <th>ECL Wolfurt</th>
                                                                                <th>Systempo AT</th>
                                                                                <th>Benoit & Valerie</th>
                                                                                <th>PFM - FR</th>
                                                                                <th>Team Tex</th>
                                                                                <th>ALDI SWB</th>
                                                                                <th>ALDI DAG</th>
                                                                                <th>ALDI DOM</th>
                                                                                <th>Dachser F51 Reims</th>
                                                                                <th>Impex-EUY</th>
                                                                                <th>Bonduelle F80</th>
                                                                                <th>Schefknecht</th>
                                                                                <th>Wildenhofer Salzburg</th>
                                                                                <th>Impex-EUX</th>
                                                                                <th>Arinthod</th>
                                                                                <th>SPAR Wels</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>


                                                                            <tr>
                                                                                <td class="text-center colTotal">{{$anz}}</td>
                                                                                @foreach($warehouses as $warehouse)
                                                                                    @if($warehouse->id == $warehouse_id)
                                                                                        <td class="text-center">{{$anz}}</td>
                                                                                    @else
                                                                                        <td class="text-center"></td>
                                                                                    @endif
                                                                                @endforeach
                                                                            </tr>

                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
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