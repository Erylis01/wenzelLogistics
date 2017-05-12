@extends('layouts.default')

@section('title')
    Details loading
@endsection

@section('stylesheet')

@endsection

@section('classLoadings')
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
                <div class="panel panel-default panel-auth">
                    <div class="panel-heading">Details of the loading n°</div>
                    <div class="panel-body panel-body-auth">
                        <form class="form-horizontal form-loading" role="form" method="POST" action="">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <!--ladedatum-->
                                <div class="col-lg-4">
                                    <label for="ladedatum" class="control-label">Ladedatum :</label>
                                    <label for="ladedatum" class="details-loading">27-05-2017</label>
                                    {{--{{ $ladedatum }}--}}
                                </div>
                                <!--entladedatum-->
                                <div class="col-lg-3">
                                    <label for="entladedatum" class="control-label">Entladedatum :</label>
                                    <label for="entladedatum" class="details-loading">24-05-2017</label>
                                </div>
                                <!--disp-->
                                <div class="col-lg-3">
                                    <label for="disp" class="control-label">Disp :</label>
                                    <label for="disp" class="details-loading">GuAd</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <!--atrnr-->
                                <div class="col-lg-4">
                                    <label for="atrnr" class="control-label">AtrNr :</label>
                                    <label for="atrnr" class="details-loading">2177859</label>
                                </div>
                                <!--referenz-->
                                <div class="col-lg-3">
                                    <label for="referenz" class="control-label">Referenz :</label>
                                    <label for="referenz" class="details-loading">RIE-01539/F34058</label>
                                </div>
                                <!--auftraggeber-->
                                <div class="col-lg-5">
                                    <label for="auftraggeber" class="control-label">Auftraggeber :</label>
                                    <label for="auftraggeber" class="details-loading">HOFER RIETZ, AT-6421 Rietz,
                                        Tirol</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <!--beladestelle-->
                                <div class="col-lg-4">
                                    <label for="beladestelle" class="control-label">Beladestelle :</label>
                                    <label for="beladestelle" class="details-loading">SAS BENOIT Valerie Calvet</label>
                                </div>
                                <!--land-->
                                <div class="col-lg-3">
                                    <label for="landb" class="control-label">Land :</label>
                                    <label for="landb" class="details-loading">FR</label>
                                </div>
                                <!--plz-->
                                <div class="col-lg-2">
                                    <label for="plzb" class="control-label">Plz :</label>
                                    <label for="plzb" class="details-loading">33500</label>
                                </div>
                                <!--ort-->
                                <div class="col-lg-3">
                                    <label for="ortb" class="control-label">Ort :</label>
                                    <label for="ortb" class="details-loading">Libourne</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <!--entladestelle-->
                                <div class="col-lg-4">
                                    <label for="entladestelle" class="control-label">Entladestelle :</label>
                                    <label for="entladestelle" class="details-loading">SAS BENOIT Valerie Calvet</label>
                                </div>
                                <!--land-->
                                <div class="col-lg-3">
                                    <label for="lande" class="control-label">Land :</label>
                                    <label for="lande" class="details-loading">FR</label>
                                </div>
                                <!--plz-->
                                <div class="col-lg-2">
                                    <label for="plze" class="control-label">Plz :</label>
                                    <label for="plze" class="details-loading">33500</label>
                                </div>
                                <!--ort-->
                                <div class="col-lg-3">
                                    <label for="orte" class="control-label">Ort :</label>
                                    <label for="orte" class="details-loading">Libourne</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <!--anzahl-->
                                <div class="col-lg-4">
                                    <label for="anzahl" class="control-label">Anzahl :</label>
                                    <label for="anzahl" class="details-loading">32</label>
                                </div>
                                <!---->
                                <div class="col-lg-3">
                                    <label for="" class="control-label"> :</label>
                                    <label for="" class="details-loading">EP</label>
                                </div>
                                <!---->
                                <div class="col-lg-2">
                                    <label for="" class="control-label"> :</label>
                                    <label for="" class="details-loading">Paletten</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <!---->
                                <div class="col-lg-4">
                                    <label for="" class="control-label"> :</label>
                                    <label for="" class="details-loading">32000</label>
                                </div>
                                <!--ware-->
                                <div class="col-lg-3">
                                    <label for="ware" class="control-label">Ware :</label>
                                    <label for="ware" class="details-loading">5 euro palleten</label>
                                </div>
                                <!--gewicht-->
                                <div class="col-lg-2">
                                    <label for="gewicht" class="control-label">Gewicht :</label>
                                    <label for="gewicht" class="details-loading">4325</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <!--umsatz-->
                                <div class="col-lg-4">
                                    <label for="umsatz" class="control-label">Umsatz :</label>
                                    <label for="umsatz" class="details-loading">320</label>
                                </div>
                                <!--aufwand-->
                                <div class="col-lg-3">
                                    <label for="aufwand" class="control-label">Aufwand :</label>
                                    <label for="aufwand" class="details-loading">700</label>
                                </div>
                                <!--db-->
                                <div class="col-lg-2">
                                    <label for="db" class="control-label">DB :</label>
                                    <label for="db" class="details-loading">420</label>
                                </div>
                                <!--trp-->
                                <div class="col-lg-2">
                                    <label for="trp" class="control-label">Trp :</label>
                                    <label for="trp" class="details-loading">0</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <!--pt-->
                                <div class="col-lg-4">
                                    <label for="pt" class="control-label">PT :</label>
                                    <label for="pt" class="details-loading">nein</label>
                                </div>
                                <!--subfrachter-->
                                <div class="col-lg-6">
                                    <label for="subfrachter" class="control-label">Subfrachter :</label>
                                    <label for="subfrachter" class="details-loading">KAI GÖSSINGER NEUHAUS, DE-98724
                                        Neuhaus</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <!--pal-->
                                <div class="col-lg-4">
                                    <label for="pal" class="control-label">Pal :</label>
                                    <label for="pal" class="details-loading"></label>
                                </div>
                                <!--imklarung-->
                                <div class="col-lg-3">
                                    <label for="imklarung" class="control-label">im Klarung :</label>
                                    <label for="imklarung" class="details-loading">Adrien</label>
                                </div>
                                <!--paltauschvereinbart-->
                                <div class="col-lg-4">
                                    <label for="paltauschvereinbart" class="control-label">Pal Tausch Vereinbart
                                        ?</label>
                                    <label for="paltauschvereinbart" class="details-loading">nein</label>
                                </div>
                            </div>
                        </form>
                        <form class="form-horizontal form-loading" role="form" method="POST" action="">
                        {{ csrf_field() }}
                        <!--ruckgabewo-->
                            <div class="form-group {{ $errors->has('ruckgabewo') ? ' has-error' : '' }}">
                                <label for="ruckgabewo" class="col-lg-3 col-lg-offset-1 control-label">Ruckgabe Wo ?</label>
                                <div class="col-lg-6">
                                    <input id="ruckgabewo" type="text" class="form-control" name="ruckgabewo"
                                           value="" placeholder="Ruckgabe Wo" required autofocus>
                                    {{--{{ $ruckgabewo }}--}}
                                    @if ($errors->has('ruckgabewo'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('ruckgabewo') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <!--mahnung-->
                            <div class="form-group {{ $errors->has('mahnung') ? ' has-error' : '' }}">
                                <label for="mahnung" class="col-lg-3 col-lg-offset-1 control-label">Mahnung :</label>
                                <div class="col-lg-6">
                                    <input id="mahnung" type="text" class="form-control" name="mahnung"
                                           value="" placeholder="Mahnung" required autofocus>
                                    {{--{{ $mahnung }}--}}
                                    @if ($errors->has('mahnung'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('mahnung') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <!--blockierung-->
                            <div class="form-group {{ $errors->has('blockierung') ? ' has-error' : '' }}">
                                <label for="blockierung" class="col-lg-3 col-lg-offset-1 control-label">Blockierung :</label>
                                <div class="col-lg-6">
                                    <input id="blockierung" type="text" class="form-control" name="blockierung"
                                           value="" placeholder="blockierung" required autofocus>
                                    {{--{{ $blockierung }}--}}
                                    @if ($errors->has('blockierung'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('blockierung') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <!--bearbeitungsdatum-->
                            <div class="form-group {{ $errors->has('bearbeitungsdatum') ? ' has-error' : '' }}">
                                <label for="bearbeitungsdatum" class="col-lg-3 col-lg-offset-1 control-label">Bearbeitungsdatum :</label>
                                <div class="col-lg-6">
                                    <input id="bearbeitungsdatum" type="date" class="form-control" name="bearbeitungsdatum"
                                           value="" placeholder="bearbeitungsdatum" required autofocus>
                                    {{--{{ $bearbeitungsdatum }}--}}
                                    @if ($errors->has('bearbeitungsdatum'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('bearbeitungsdatum') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <!--palgebucht-->
                            <div class="form-group {{ $errors->has('palgebucht') ? ' has-error' : '' }}">
                                <label for="palgebucht" class="col-lg-3 col-lg-offset-1 control-label">Pal gebucht ?</label>
                                <div class="col-lg-6">
                                    <input id="palgebucht" type="text" class="form-control" name="palgebucht"
                                           value="" placeholder="Pal gebucht" required autofocus>
                                    {{--{{ $palgebucht }}--}}
                                    @if ($errors->has('palgebucht'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('palgebucht') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </form>
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
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
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