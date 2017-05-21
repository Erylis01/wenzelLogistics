@extends('layouts.default')

@section('title')
    Details loading
@endsection

@section('stylesheet')
    <link href="{{asset('css/loadings.css')}}" rel="stylesheet" type="text/css">
@endsection

@section('classLoadings')
    class="nonActive"
@endsection
@section('classWarehouses')
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
                                                <div class="panel-heading">Details of the loading n°{{ $id }}
                                                    <span class="col-lg-offset-8">{{$state}}</span>
                                                </div>
                                                <div class="panel-body panel-body-general form-loading">
                                                    <!--reading form suming up information from the table-->
                                                    <form class="form-horizontal" role="form" method="POST"
                                                          action="">
                                                        {{ csrf_field() }}

                                                        <div class="form-group">
                                                            <!--ladedatum-->
                                                            <div class="col-lg-4">
                                                                <label for="ladedatum" class="control-label">Ladedatum
                                                                    :</label>
                                                                <label for="ladedatum"
                                                                       class="details-loading">{{ $ladedatum }}</label>

                                                            </div>
                                                            <!--entladedatum-->
                                                            <div class="col-lg-3">
                                                                <label for="entladedatum" class="control-label">Entladedatum
                                                                    :</label>
                                                                <label for="entladedatum"
                                                                       class="details-loading">{{ $entladedatum }}</label>
                                                            </div>
                                                            <!--disp-->
                                                            <div class="col-lg-3">
                                                                <label for="disp" class="control-label">Disp :</label>
                                                                <label for="disp"
                                                                       class="details-loading">{{ $disp }}</label>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <!--atrnr-->
                                                            <div class="col-lg-4">
                                                                <label for="atrnr" class="control-label">AtrNr :</label>
                                                                <label for="atrnr"
                                                                       class="details-loading">{{ $atrnr }}</label>
                                                            </div>
                                                            <!--referenz-->
                                                            <div class="col-lg-3">
                                                                <label for="referenz" class="control-label">Referenz
                                                                    :</label>
                                                                <label for="referenz"
                                                                       class="details-loading">{{ $referenz }}</label>
                                                            </div>
                                                            <!--auftraggeber-->
                                                            <div class="col-lg-5">
                                                                <label for="auftraggeber" class="control-label">Auftraggeber
                                                                    :</label>
                                                                <label for="auftraggeber"
                                                                       class="details-loading">{{ $auftraggeber }}</label>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <!--beladestelle-->
                                                            <div class="col-lg-4">
                                                                <label for="beladestelle" class="control-label">Beladestelle
                                                                    :</label>
                                                                <label for="beladestelle"
                                                                       class="details-loading">{{ $beladestelle }}</label>
                                                            </div>
                                                            <!--land-->
                                                            <div class="col-lg-3">
                                                                <label for="landb" class="control-label">Land :</label>
                                                                <label for="landb"
                                                                       class="details-loading">{{ $landb }}</label>
                                                            </div>
                                                            <!--plz-->
                                                            <div class="col-lg-2">
                                                                <label for="plzb" class="control-label">Plz :</label>
                                                                <label for="plzb"
                                                                       class="details-loading">{{ $plzb }}</label>
                                                            </div>
                                                            <!--ort-->
                                                            <div class="col-lg-3">
                                                                <label for="ortb" class="control-label">Ort :</label>
                                                                <label for="ortb"
                                                                       class="details-loading">{{ $ortb }}</label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <!--entladestelle-->
                                                            <div class="col-lg-4">
                                                                <label for="entladestelle" class="control-label">Entladestelle
                                                                    :</label>
                                                                <label for="entladestelle"
                                                                       class="details-loading">{{ $entladestelle }}</label>
                                                            </div>
                                                            <!--land-->
                                                            <div class="col-lg-3">
                                                                <label for="lande" class="control-label">Land :</label>
                                                                <label for="lande"
                                                                       class="details-loading">{{ $lande }}</label>
                                                            </div>
                                                            <!--plz-->
                                                            <div class="col-lg-2">
                                                                <label for="plze" class="control-label">Plz :</label>
                                                                <label for="plze"
                                                                       class="details-loading">{{ $plze }}</label>
                                                            </div>
                                                            <!--ort-->
                                                            <div class="col-lg-3">
                                                                <label for="orte" class="control-label">Ort :</label>
                                                                <label for="orte"
                                                                       class="details-loading">{{ $orte }}</label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <!--anzahl-->
                                                            <div class="col-lg-4">
                                                                <label for="anzahl" class="control-label">Anzahl
                                                                    :</label>
                                                                <label for="anzahl"
                                                                       class="details-loading">{{ $anzahl }}</label>
                                                            </div>
                                                            <!---->
                                                            <div class="col-lg-3">
                                                                <label for="" class="control-label"> :</label>
                                                                <label for=""
                                                                       class="details-loading">{{ $try1 }}</label>
                                                            </div>
                                                            <!---->
                                                            <div class="col-lg-2">
                                                                <label for="" class="control-label"> :</label>
                                                                <label for=""
                                                                       class="details-loading">{{ $try2 }}</label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <!---->
                                                            <div class="col-lg-4">
                                                                <label for="" class="control-label"> :</label>
                                                                <label for=""
                                                                       class="details-loading">{{ $try3 }}</label>
                                                            </div>
                                                            <!--ware-->
                                                            <div class="col-lg-3">
                                                                <label for="ware" class="control-label">Ware :</label>
                                                                <label for="ware"
                                                                       class="details-loading">{{ $ware }}</label>
                                                            </div>
                                                            <!--gewicht-->
                                                            <div class="col-lg-2">
                                                                <label for="gewicht" class="control-label">Gewicht
                                                                    :</label>
                                                                <label for="gewicht"
                                                                       class="details-loading">{{ $gewicht }}</label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <!--umsatz-->
                                                            <div class="col-lg-4">
                                                                <label for="umsatz" class="control-label">Umsatz
                                                                    :</label>
                                                                <label for="umsatz"
                                                                       class="details-loading">{{ $umsatz }} €</label>
                                                            </div>
                                                            <!--aufwand-->
                                                            <div class="col-lg-3">
                                                                <label for="aufwand" class="control-label">Aufwand
                                                                    :</label>
                                                                <label for="aufwand"
                                                                       class="details-loading">{{ $aufwand }} €</label>
                                                            </div>
                                                            <!--db-->
                                                            <div class="col-lg-2">
                                                                <label for="db" class="control-label">DB :</label>
                                                                <label for="db" class="details-loading">{{ $db }}
                                                                    €</label>
                                                            </div>
                                                            <!--trp-->
                                                            <div class="col-lg-2">
                                                                <label for="trp" class="control-label">Trp :</label>
                                                                <label for="trp"
                                                                       class="details-loading">{{ $trp }}</label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <!--change pt-->
                                                            <!--pt-->
                                                            <div class="col-lg-4">
                                                                <label for="pt" class="control-label">PT :</label>

                                                                <label for="pt" class="link"
                                                                       data-toggle="modal"
                                                                       data-target="#updatePT_modal">{{ $pt }}</label>
                                                            </div>

                                                            <!-- Modal update pt -->
                                                            <div class="modal fade" id="updatePT_modal"
                                                                 role="dialog">
                                                                <div class="modal-dialog modal-md">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close"
                                                                                    data-dismiss="modal">&times;
                                                                            </button>
                                                                            <h4 class="modal-title">Why would you like
                                                                                to change the loading into a
                                                                                loading WITHOUT exchange pallets ?</h4>
                                                                        </div>
                                                                        <div class="modal-body center">
                                                                            <form role="form" method="POST" action="">
                                                                                <input type="hidden" name="_token"
                                                                                       value="{{ csrf_token() }}">
                                                                                <textarea class="form-control" rows="5"
                                                                                          id="reasonUpdatePT"
                                                                                          name="reasonUpdatePT" required
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
                                                                                                    Are you sure that
                                                                                                    loading is
                                                                                                    WITHOUT exchange
                                                                                                    pallets ?</h4>
                                                                                            </div>
                                                                                            <div class="modal-body center">
                                                                                                <h4>If you have made a
                                                                                                    mistake you can
                                                                                                    change this
                                                                                                    information directly
                                                                                                    on the Excel
                                                                                                    file</h4>
                                                                                                <br>
                                                                                                <form role="form"
                                                                                                      method="POST"
                                                                                                      action="{{ route('saveDetailsLoading', $id) }}">
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

                                                            <!--subfrachter-->
                                                            <div class="col-lg-6">
                                                                <label for="subfrachter" class="control-label">Subfrachter
                                                                    :</label>
                                                                <label for="subfrachter"
                                                                       class="details-loading">{{ $subfrachter }}</label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            @if (Session::has('messageUpdatePTLoading'))
                                                                <div class="alert alert-warning text-alert text-center">{{ Session::get('messageUpdatePTLoading') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="form-group">
                                                            <!--pal-->
                                                            <div class="col-lg-4">
                                                                <label for="pal" class="control-label">Pal :</label>
                                                                <label for="pal"
                                                                       class="details-loading">{{ $pal }}</label>
                                                            </div>
                                                            <!--imklarung-->
                                                            <div class="col-lg-3">
                                                                <label for="imklarung" class="control-label">im Klarung
                                                                    :</label>
                                                                <label for="imklarung"
                                                                       class="details-loading">{{ $imklarung }}</label>
                                                            </div>

                                                            <!--paltauschvereinbart-->
                                                            <div class="col-lg-5">
                                                                <label for="paltauschvereinbart" class="control-label">Pal
                                                                    Tausch Vereinbart
                                                                    ?</label>
                                                                <label for="paltauschvereinbart"
                                                                       class="details-loading">{{ $paltauschvereinbart }}</label>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>

                                                <div class="panel-body panel-body-general form-loading">
                                                    <!--form to edit loading-->
                                                    <form class="form-horizontal" role="form" method="POST"
                                                          action="{{route('saveDetailsLoading', $id)}}">
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                        <input type="hidden" name="id" value={{$id}}>
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
                                                                <input id="ruckgabewo" type="text" class="form-control"
                                                                       name="ruckgabewo"
                                                                       value="{{ $ruckgabewo }}"
                                                                       placeholder="Ruckgabe Wo" autofocus>

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
                                                                <input id="mahnung" type="text" class="form-control"
                                                                       name="mahnung"
                                                                       value="{{ $mahnung }}" placeholder="Mahnung"
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
                                                                <input id="blockierung" type="text" class="form-control"
                                                                       name="blockierung"
                                                                       value="{{ $blockierung }}"
                                                                       placeholder="Blockierung" autofocus>

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
                                                                <input id="bearbeitungsdatum" type="date"
                                                                       class="form-control"
                                                                       name="bearbeitungsdatum"
                                                                       value="{{ $bearbeitungsdatum }}"
                                                                       placeholder="bearbeitungsdatum" autofocus>

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
                                                                <input id="palgebucht" type="text" class="form-control"
                                                                       name="palgebucht"
                                                                       value="{{ $palgebucht }}"
                                                                       placeholder="Pal gebucht" autofocus>

                                                                @if ($errors->has('palgebucht'))
                                                                    <span class="help-block">
                                        <strong>{{ $errors->first('palgebucht') }}</strong>
                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="panel-body panel-body-general">
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
                                                                        <td class="text-center colTotal">{{$anzahl}}</td>
                                                                        @foreach($warehouses as $warehouse)
                                                                            @if($warehouse->id == $warehouse_id)
                                                                                <td class="text-center">{{$anzahl}}</td>
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
                                @endif
                            </div>
@endsection