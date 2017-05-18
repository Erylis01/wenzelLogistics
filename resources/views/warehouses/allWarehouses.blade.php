@extends('layouts.default')

@section('title')
    All warehouses
@endsection

@section('stylesheet')
    <link href="{{asset('css/warehouses.css')}}" rel="stylesheet" type="text/css">
@endsection

@section('classLoadings')
    class="nonActive"
@endsection
@section('classWarehouses')
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
                <div class="panel panel-general panel-warehouses">
                    <div class="panel-heading">Number of pallets by warehouse</div>

                    <div class="panel-body panel-body-general">
                        <!-- Table -->
                        <div class="table-responsive table-small-warehouses">
                            <table class="table table-hover table-bordered">
                                <thead>
                                <tr>
                                    <th class="text-center colTotal"><a href="#total-collapse" data-toggle="collapse" class="link">TOTAL</a></th>
                                    <th class="text-center colFakturiert">Fakturiert</th>
                                    <th class="text-center colVerschenkt">Verschenkt</th>
                                    <th class="text-center colECLWolfurt">ECL Wolfurt</th>
                                    <th class="text-center colSystempo">Systempo AT</th>
                                    <th class="text-center colBenoit">Benoit & Valerie</th>
                                    <th class="text-center colPFM">PFM - FR</th>
                                    <th class="text-center colTeamTex">Team Tex</th>
                                    <th class="text-center colAldi">ALDI SWB</th>
                                    <th class="text-center colAldi">ALDI DAG</th>
                                    <th class="text-center colAldi">ALDI DOM</th>
                                    <th class="text-center colDachser">Dachser F51</th>
                                    <th class="text-center colImpex">Impex-EUY</th>
                                    <th class="text-center colBonduelle">Bonduelle F80</th>
                                    <th class="text-center colSchefknecht">Schefknecht</th>
                                    <th class="text-center colWildenhofer">Wildenhofer Salzburg</th>
                                    <th class="text-center colImpex">IMPEX-EUX</th>
                                    <th class="text-center colArinthod">Arinthod</th>
                                    <th class="text-center colSpar">Spar Wels</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center colTotal">{{$totalpalanzahl}}</td>
                                        <td class="text-center colFakturiert">{{$totalpalanzahlFakturiert}}</td>
                                        <td class="text-center colVerschenkt">{{$totalpalanzahlVerschenkt}}</td>
                                        <td class="text-center colECLWolfurt">{{$totalpalanzahlECLWolfurt}}</td>
                                        <td class="text-center colSystempo">{{$totalpalanzahlSystempoAT}}</td>
                                        <td class="text-center colBenoit">{{$totalpalanzahlSBenoitValerie}}</td>
                                        <td class="text-center colPFM">{{$totalpalanzahlSPFMFR}}</td>
                                        <td class="text-center colTeamTex">{{$totalpalanzahlTeamTex}}</td>
                                        <td class="text-center colAldi">{{$totalpalanzahlALDISWB}}</td>
                                        <td class="text-center colAldi">{{$totalpalanzahlALDIDAG}}</td>
                                        <td class="text-center colAldi">{{$totalpalanzahlALDIDOM}}</td>
                                        <td class="text-center colDachser">{{$totalpalanzahlDachserF51}}</td>
                                        <td class="text-center colImpex">{{$totalpalanzahlImpexEUY}}</td>
                                        <td class="text-center colBonduelle">{{$totalpalanzahlBonduelleF80}}</td>
                                        <td class="text-center colSchefknecht">{{$totalpalanzahlSchefknecht}}</td>
                                        <td class="text-center colWildenhofer">{{$totalpalanzahlWildenhoferSalzburg}}</td>
                                        <td class="text-center colImpex">{{$totalpalanzahlIMPEXEUX}}</td>
                                        <td class="text-center colArinthod">{{$totalpalanzahlArinthod}}</td>
                                        <td class="text-center colSpar">{{$totalpalanzahlSparWels}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div id="total-collapse" class="panel panel-general panel-list-warehouses collapse">
                <div class="panel-heading">Loadings list : number of pallets by warehouse</div>

                <div class="panel-body panel-body-general">
                        <!-- Table -->
                        <div class="table-responsive table-big-warehouses">
                            <table class="table table-hover table-bordered">
                                <thead>
                                <tr>
                                    <th class="text-center colIDLoading">ID Loading</th>
                                    <th class="text-center colTotal">TOTAL</th>
                                    <th class="text-center colFakturiert">Fakturiert</th>
                                    <th class="text-center colVerschenkt">Verschenkt</th>
                                    <th class="text-center colECLWolfurt">ECL Wolfurt</th>
                                    <th class="text-center colSystempo">Systempo AT</th>
                                    <th class="text-center colBenoit">Benoit & Valerie</th>
                                    <th class="text-center colPFM">PFM - FR</th>
                                    <th class="text-center colTeamTex">Team Tex</th>
                                    <th class="text-center colAldi">ALDI SWB</th>
                                    <th class="text-center colAldi">ALDI DAG</th>
                                    <th class="text-center colAldi">ALDI DOM</th>
                                    <th class="text-center colDachser">Dachser F51</th>
                                    <th class="text-center colImpex">Impex-EUY</th>
                                    <th class="text-center colBonduelle">Bonduelle F80</th>
                                    <th class="text-center colSchefknecht">Schefknecht</th>
                                    <th class="text-center colWildenhofer">Wildenhofer Salzburg</th>
                                    <th class="text-center colImpex">IMPEX-EUX</th>
                                    <th class="text-center colArinthod">Arinthod</th>
                                    <th class="text-center colSpar">Spar Wels</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="text-center colIDLoading">ID Loading</td>
                                    <td class="text-center colTotal">{{$totalpalanzahl}}</td>
                                    <td class="text-center colFakturiert">{{$totalpalanzahlFakturiert}}</td>
                                    <td class="text-center colVerschenkt">{{$totalpalanzahlVerschenkt}}</td>
                                    <td class="text-center colECLWolfurt">{{$totalpalanzahlECLWolfurt}}</td>
                                    <td class="text-center colSystempo">{{$totalpalanzahlSystempoAT}}</td>
                                    <td class="text-center colBenoit">{{$totalpalanzahlSBenoitValerie}}</td>
                                    <td class="text-center colPFM">{{$totalpalanzahlSPFMFR}}</td>
                                    <td class="text-center colTeamTex">{{$totalpalanzahlTeamTex}}</td>
                                    <td class="text-center colAldi">{{$totalpalanzahlALDISWB}}</td>
                                    <td class="text-center colAldi">{{$totalpalanzahlALDIDAG}}</td>
                                    <td class="text-center colAldi">{{$totalpalanzahlALDIDOM}}</td>
                                    <td class="text-center colDachser">{{$totalpalanzahlDachserF51}}</td>
                                    <td class="text-center colImpex">{{$totalpalanzahlImpexEUY}}</td>
                                    <td class="text-center colBonduelle">{{$totalpalanzahlBonduelleF80}}</td>
                                    <td class="text-center colSchefknecht">{{$totalpalanzahlSchefknecht}}</td>
                                    <td class="text-center colWildenhofer">{{$totalpalanzahlWildenhoferSalzburg}}</td>
                                    <td class="text-center colImpex">{{$totalpalanzahlIMPEXEUX}}</td>
                                    <td class="text-center colArinthod">{{$totalpalanzahlArinthod}}</td>
                                    <td class="text-center colSpar">{{$totalpalanzahlSparWels}}</td>
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