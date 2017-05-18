@extends('layouts.default')

@section('title')
    Total warehouses
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
                    <div class="panel-heading">Total of pallets by warehouse</div>

                    <div class="panel-body panel-body-general">
                        <!-- Table -->
                        <div class="table-responsive table-small-warehouses">
                            <table class="table table-hover table-bordered">
                                <thead>
                                <tr>
                                    <th class="text-center colTotal"><a href="#total-collapse" data-toggle="collapse"
                                                                        class="link">TOTAL</a></th>
                                    <th class="text-center colFakturiert"><a href="#fakturiert-collapse"
                                                                             data-toggle="collapse"
                                                                             class="link">Fakturiert</a></th>
                                    <th class="text-center colVerschenkt"><a href="#verschenkt-collapse"
                                                                             data-toggle="collapse"
                                                                             class="link">Verschenkt</a></th>
                                    <th class="text-center colECLWolfurt"><a href="#ECL-collapse"
                                                                             data-toggle="collapse"
                                                                             class="link">ECL Wolfurt</a></th>
                                    <th class="text-center colSystempo"><a href="#systempo-collapse"
                                                                           data-toggle="collapse"
                                                                           class="link">Systempo AT</a></th>
                                    <th class="text-center colBenoit"><a href="#benoit-collapse"
                                                                         data-toggle="collapse"
                                                                         class="link">Benoit & Valerie</a></th>
                                    <th class="text-center colPFM"><a href="#pfm-collapse"
                                                                      data-toggle="collapse"
                                                                      class="link">PFM - FR</a></th>
                                    <th class="text-center colTeamTex"><a href="#team-collapse"
                                                                          data-toggle="collapse"
                                                                          class="link">Team Tex</a></th>
                                    <th class="text-center colAldi"><a href="#aldiswb-collapse"
                                                                       data-toggle="collapse"
                                                                       class="link">ALDI SWB</a></th>
                                    <th class="text-center colAldi"><a href="#aldidag-collapse"
                                                                       data-toggle="collapse"
                                                                       class="link">ALDI DAG</a></th>
                                    <th class="text-center colAldi"><a href="#aldidom-collapse"
                                                                       data-toggle="collapse"
                                                                       class="link">ALDI DOM</a></th>
                                    <th class="text-center colDachser"><a href="#dachser-collapse"
                                                                          data-toggle="collapse"
                                                                          class="link">Dachser F51</a></th>
                                    <th class="text-center colImpex"><a href="#impexeuy-collapse"
                                                                        data-toggle="collapse"
                                                                        class="link">Impex-EUY</a></th>
                                    <th class="text-center colBonduelle"><a href="#bonduelle-collapse"
                                                                            data-toggle="collapse"
                                                                            class="link">Bonduelle F80</a></th>
                                    <th class="text-center colSchefknecht"><a href="#schefknecht-collapse"
                                                                              data-toggle="collapse"
                                                                              class="link">Schefknecht</a></th>
                                    <th class="text-center colWildenhofer"><a href="#wildenhofer-collapse"
                                                                              data-toggle="collapse"
                                                                              class="link">Wildenhofer Salzburg</a></th>
                                    <th class="text-center colImpex"><a href="#impexeux-collapse"
                                                                        data-toggle="collapse"
                                                                        class="link">Impex-EUX</a></th>
                                    <th class="text-center colArinthod"><a href="#arinthod-collapse"
                                                                           data-toggle="collapse"
                                                                           class="link">Arinthod</a></th>
                                    <th class="text-center colSpar"><a href="#spar-collapse"
                                                                       data-toggle="collapse"
                                                                       class="link">Spar Wels</a></th>
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
            <!--list loadings with number of pallets by warehouse-->
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
                    <div class="row">
                        {{--<div class="text-left">{!! $listLoadings->render() !!}</div>--}}
                        {{--->appends($links)--}}
                        {{--@if ($listLoadings->currentPage()==$listLoadings->lastPage())--}}
                        {{--<div class=" col-lg-offset-8">--}}
                        {{--Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}--}}
                        {{--to {{$count}} of {{$count}} results--}}
                        {{--</div>--}}
                        {{--@else--}}
                        {{--<div class=" col-lg-offset-8">--}}
                        {{--Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}--}}
                        {{--to @php($legend2= $listLoadings->currentPage() * 5) {{$legend2}} of {{$count}}--}}
                        {{--results--}}
                        {{--</div>--}}
                        {{--@endif--}}
                    </div>
                </div>
            </div>

            <!--list loadings with number of pallets Fakturiert-->
            <div id="fakturiert-collapse" class="panel panel-general panel-list-warehouses collapse">
                <div class="panel-heading">Fakturiert - Total ...</div>

                <div class="panel-body panel-body-general">
                    <!-- Table -->
                    <div class="table-responsive table-big-warehouses">
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center colIDLoading">ID Loading</th>
                                <th class="text-center colFakturiert">Anzahl pal</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center colIDLoading">ID Loading</td>
                                <td class="text-center colFakturiert">Anzahl pal</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        {{--<div class="text-left">{!! $listLoadings->render() !!}</div>--}}
                        {{--->appends($links)--}}
                        {{--@if ($listLoadings->currentPage()==$listLoadings->lastPage())--}}
                        {{--<div class=" col-lg-offset-8">--}}
                        {{--Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}--}}
                        {{--to {{$count}} of {{$count}} results--}}
                        {{--</div>--}}
                        {{--@else--}}
                        {{--<div class=" col-lg-offset-8">--}}
                        {{--Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}--}}
                        {{--to @php($legend2= $listLoadings->currentPage() * 5) {{$legend2}} of {{$count}}--}}
                        {{--results--}}
                        {{--</div>--}}
                        {{--@endif--}}
                    </div>
                </div>
            </div>

            <!--list loadings with number of pallets Verschenkt-->
            <div id="verschenkt-collapse" class="panel panel-general panel-list-warehouses collapse">
                <div class="panel-heading">Verschenkt - Total ...</div>
                <div class="panel-body panel-body-general">
                    <!-- Table -->
                    <div class="table-responsive table-big-warehouses">
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center colIDLoading">ID Loading</th>
                                <th class="text-center colVerschenkt">Anzahl pal</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center colIDLoading">ID Loading</td>
                                <td class="text-center colVerschenkt">Anzahl pal</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        {{--<div class="text-left">{!! $listLoadings->render() !!}</div>--}}
                        {{--->appends($links)--}}
                        {{--@if ($listLoadings->currentPage()==$listLoadings->lastPage())--}}
                        {{--<div class=" col-lg-offset-8">--}}
                        {{--Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}--}}
                        {{--to {{$count}} of {{$count}} results--}}
                        {{--</div>--}}
                        {{--@else--}}
                        {{--<div class=" col-lg-offset-8">--}}
                        {{--Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}--}}
                        {{--to @php($legend2= $listLoadings->currentPage() * 5) {{$legend2}} of {{$count}}--}}
                        {{--results--}}
                        {{--</div>--}}
                        {{--@endif--}}
                    </div>
                </div>
            </div>

                <!--list loadings with number of pallets ECL Wolfurt-->
                <div id="ECL-collapse" class="panel panel-general panel-list-warehouses collapse">
                    <div class="panel-heading">ECL Wolfurt - Total ...</div>

                    <div class="panel-body panel-body-general">
                        <!-- Table -->
                        <div class="table-responsive table-big-warehouses">
                            <table class="table table-hover table-bordered">
                                <thead>
                                <tr>
                                    <th class="text-center colIDLoading">ID Loading</th>
                                    <th class="text-center colECLWolfurt">Anzahl pal</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="text-center colIDLoading">ID Loading</td>
                                    <td class="text-center colECLWolfurt">Anzahl pal</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            {{--<div class="text-left">{!! $listLoadings->render() !!}</div>--}}
                            {{--->appends($links)--}}
                            {{--@if ($listLoadings->currentPage()==$listLoadings->lastPage())--}}
                            {{--<div class=" col-lg-offset-8">--}}
                            {{--Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}--}}
                            {{--to {{$count}} of {{$count}} results--}}
                            {{--</div>--}}
                            {{--@else--}}
                            {{--<div class=" col-lg-offset-8">--}}
                            {{--Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}--}}
                            {{--to @php($legend2= $listLoadings->currentPage() * 5) {{$legend2}} of {{$count}}--}}
                            {{--results--}}
                            {{--</div>--}}
                            {{--@endif--}}
                        </div>
                    </div>
                </div>

            <!--list loadings with number of pallets Systempo AT-->
            <div id="systempo-collapse" class="panel panel-general panel-list-warehouses collapse">
                <div class="panel-heading">Systempo AT - Total ...</div>
                <div class="panel-body panel-body-general">
                    <!-- Table -->
                    <div class="table-responsive table-big-warehouses">
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center colIDLoading">ID Loading</th>
                                <th class="text-center colSystempo">Anzahl pal</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center colIDLoading">ID Loading</td>
                                <td class="text-center colSystempo">Anzahl pal</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        {{--<div class="text-left">{!! $listLoadings->render() !!}</div>--}}
                        {{--->appends($links)--}}
                        {{--@if ($listLoadings->currentPage()==$listLoadings->lastPage())--}}
                        {{--<div class=" col-lg-offset-8">--}}
                        {{--Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}--}}
                        {{--to {{$count}} of {{$count}} results--}}
                        {{--</div>--}}
                        {{--@else--}}
                        {{--<div class=" col-lg-offset-8">--}}
                        {{--Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}--}}
                        {{--to @php($legend2= $listLoadings->currentPage() * 5) {{$legend2}} of {{$count}}--}}
                        {{--results--}}
                        {{--</div>--}}
                        {{--@endif--}}
                    </div>
                </div>
            </div>

            <!--list loadings with number of pallets Benoit & Valerie-->
            <div id="benoit-collapse" class="panel panel-general panel-list-warehouses collapse">
                <div class="panel-heading">Benoit & Valerie - Total ...</div>
                <div class="panel-body panel-body-general">
                    <!-- Table -->
                    <div class="table-responsive table-big-warehouses">
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center colIDLoading">ID Loading</th>
                                <th class="text-center colBenoit">Anzahl pal</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center colIDLoading">ID Loading</td>
                                <td class="text-center colBenoit">Anzahl pal</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        {{--<div class="text-left">{!! $listLoadings->render() !!}</div>--}}
                        {{--->appends($links)--}}
                        {{--@if ($listLoadings->currentPage()==$listLoadings->lastPage())--}}
                        {{--<div class=" col-lg-offset-8">--}}
                        {{--Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}--}}
                        {{--to {{$count}} of {{$count}} results--}}
                        {{--</div>--}}
                        {{--@else--}}
                        {{--<div class=" col-lg-offset-8">--}}
                        {{--Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}--}}
                        {{--to @php($legend2= $listLoadings->currentPage() * 5) {{$legend2}} of {{$count}}--}}
                        {{--results--}}
                        {{--</div>--}}
                        {{--@endif--}}
                    </div>
                </div>
            </div>

            <!--list loadings with number of pallets PFM FR-->
            <div id="pfm-collapse" class="panel panel-general panel-list-warehouses collapse">
                <div class="panel-heading">PFM FR - Total ...</div>
                <div class="panel-body panel-body-general">
                    <!-- Table -->
                    <div class="table-responsive table-big-warehouses">
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center colIDLoading">ID Loading</th>
                                <th class="text-center colPFM">Anzahl pal</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center colIDLoading">ID Loading</td>
                                <td class="text-center colPFM">Anzahl pal</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        {{--<div class="text-left">{!! $listLoadings->render() !!}</div>--}}
                        {{--->appends($links)--}}
                        {{--@if ($listLoadings->currentPage()==$listLoadings->lastPage())--}}
                        {{--<div class=" col-lg-offset-8">--}}
                        {{--Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}--}}
                        {{--to {{$count}} of {{$count}} results--}}
                        {{--</div>--}}
                        {{--@else--}}
                        {{--<div class=" col-lg-offset-8">--}}
                        {{--Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}--}}
                        {{--to @php($legend2= $listLoadings->currentPage() * 5) {{$legend2}} of {{$count}}--}}
                        {{--results--}}
                        {{--</div>--}}
                        {{--@endif--}}
                    </div>
                </div>
            </div>

            <!--list loadings with number of pallets Team Tex-->
            <div id="team-collapse" class="panel panel-general panel-list-warehouses collapse">
                <div class="panel-heading">Team Tex - Total ...</div>
                <div class="panel-body panel-body-general">
                    <!-- Table -->
                    <div class="table-responsive table-big-warehouses">
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center colIDLoading">ID Loading</th>
                                <th class="text-center colTeamTex">Anzahl pal</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center colIDLoading">ID Loading</td>
                                <td class="text-center colTeamTex">Anzahl pal</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        {{--<div class="text-left">{!! $listLoadings->render() !!}</div>--}}
                        {{--->appends($links)--}}
                        {{--@if ($listLoadings->currentPage()==$listLoadings->lastPage())--}}
                        {{--<div class=" col-lg-offset-8">--}}
                        {{--Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}--}}
                        {{--to {{$count}} of {{$count}} results--}}
                        {{--</div>--}}
                        {{--@else--}}
                        {{--<div class=" col-lg-offset-8">--}}
                        {{--Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}--}}
                        {{--to @php($legend2= $listLoadings->currentPage() * 5) {{$legend2}} of {{$count}}--}}
                        {{--results--}}
                        {{--</div>--}}
                        {{--@endif--}}
                    </div>
                </div>
            </div>

            <!--list loadings with number of pallets Aldi Swb-->
            <div id="aldiswb-collapse" class="panel panel-general panel-list-warehouses collapse">
                <div class="panel-heading">ALDI SWB - Total ...</div>
                <div class="panel-body panel-body-general">
                    <!-- Table -->
                    <div class="table-responsive table-big-warehouses">
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center colIDLoading">ID Loading</th>
                                <th class="text-center colAldi">Anzahl pal</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center colIDLoading">ID Loading</td>
                                <td class="text-center colAldi">Anzahl pal</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        {{--<div class="text-left">{!! $listLoadings->render() !!}</div>--}}
                        {{--->appends($links)--}}
                        {{--@if ($listLoadings->currentPage()==$listLoadings->lastPage())--}}
                        {{--<div class=" col-lg-offset-8">--}}
                        {{--Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}--}}
                        {{--to {{$count}} of {{$count}} results--}}
                        {{--</div>--}}
                        {{--@else--}}
                        {{--<div class=" col-lg-offset-8">--}}
                        {{--Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}--}}
                        {{--to @php($legend2= $listLoadings->currentPage() * 5) {{$legend2}} of {{$count}}--}}
                        {{--results--}}
                        {{--</div>--}}
                        {{--@endif--}}
                    </div>
                </div>
            </div>

            <!--list loadings with number of pallets Aldi dag-->
            <div id="aldidag-collapse" class="panel panel-general panel-list-warehouses collapse">
                <div class="panel-heading">ALDI DAG - Total ...</div>
                <div class="panel-body panel-body-general">
                    <!-- Table -->
                    <div class="table-responsive table-big-warehouses">
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center colIDLoading">ID Loading</th>
                                <th class="text-center colAldi">Anzahl pal</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center colIDLoading">ID Loading</td>
                                <td class="text-center colAldi">Anzahl pal</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        {{--<div class="text-left">{!! $listLoadings->render() !!}</div>--}}
                        {{--->appends($links)--}}
                        {{--@if ($listLoadings->currentPage()==$listLoadings->lastPage())--}}
                        {{--<div class=" col-lg-offset-8">--}}
                        {{--Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}--}}
                        {{--to {{$count}} of {{$count}} results--}}
                        {{--</div>--}}
                        {{--@else--}}
                        {{--<div class=" col-lg-offset-8">--}}
                        {{--Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}--}}
                        {{--to @php($legend2= $listLoadings->currentPage() * 5) {{$legend2}} of {{$count}}--}}
                        {{--results--}}
                        {{--</div>--}}
                        {{--@endif--}}
                    </div>
                </div>
            </div>

            <!--list loadings with number of pallets Aldi dom-->
            <div id="aldidom-collapse" class="panel panel-general panel-list-warehouses collapse">
                <div class="panel-heading">ALDI DOM - Total ...</div>
                <div class="panel-body panel-body-general">
                    <!-- Table -->
                    <div class="table-responsive table-big-warehouses">
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center colIDLoading">ID Loading</th>
                                <th class="text-center colAldi">Anzahl pal</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center colIDLoading">ID Loading</td>
                                <td class="text-center colAldi">Anzahl pal</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        {{--<div class="text-left">{!! $listLoadings->render() !!}</div>--}}
                        {{--->appends($links)--}}
                        {{--@if ($listLoadings->currentPage()==$listLoadings->lastPage())--}}
                        {{--<div class=" col-lg-offset-8">--}}
                        {{--Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}--}}
                        {{--to {{$count}} of {{$count}} results--}}
                        {{--</div>--}}
                        {{--@else--}}
                        {{--<div class=" col-lg-offset-8">--}}
                        {{--Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}--}}
                        {{--to @php($legend2= $listLoadings->currentPage() * 5) {{$legend2}} of {{$count}}--}}
                        {{--results--}}
                        {{--</div>--}}
                        {{--@endif--}}
                    </div>
                </div>
            </div>

            <!--list loadings with number of pallets Dachser-->
            <div id="dachser-collapse" class="panel panel-general panel-list-warehouses collapse">
                <div class="panel-heading">Dachser F51 - Total ...</div>
                <div class="panel-body panel-body-general">
                    <!-- Table -->
                    <div class="table-responsive table-big-warehouses">
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center colIDLoading">ID Loading</th>
                                <th class="text-center colDachser">Anzahl pal</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center colIDLoading">ID Loading</td>
                                <td class="text-center colDachser">Anzahl pal</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        {{--<div class="text-left">{!! $listLoadings->render() !!}</div>--}}
                        {{--->appends($links)--}}
                        {{--@if ($listLoadings->currentPage()==$listLoadings->lastPage())--}}
                        {{--<div class=" col-lg-offset-8">--}}
                        {{--Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}--}}
                        {{--to {{$count}} of {{$count}} results--}}
                        {{--</div>--}}
                        {{--@else--}}
                        {{--<div class=" col-lg-offset-8">--}}
                        {{--Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}--}}
                        {{--to @php($legend2= $listLoadings->currentPage() * 5) {{$legend2}} of {{$count}}--}}
                        {{--results--}}
                        {{--</div>--}}
                        {{--@endif--}}
                    </div>
                </div>
            </div>

            <!--list loadings with number of pallets Impex EUY-->
            <div id="impexeuy-collapse" class="panel panel-general panel-list-warehouses collapse">
                <div class="panel-heading">Impex EUY - Total ...</div>
                <div class="panel-body panel-body-general">
                    <!-- Table -->
                    <div class="table-responsive table-big-warehouses">
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center colIDLoading">ID Loading</th>
                                <th class="text-center colImpex">Anzahl pal</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center colIDLoading">ID Loading</td>
                                <td class="text-center colImpex">Anzahl pal</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        {{--<div class="text-left">{!! $listLoadings->render() !!}</div>--}}
                        {{--->appends($links)--}}
                        {{--@if ($listLoadings->currentPage()==$listLoadings->lastPage())--}}
                        {{--<div class=" col-lg-offset-8">--}}
                        {{--Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}--}}
                        {{--to {{$count}} of {{$count}} results--}}
                        {{--</div>--}}
                        {{--@else--}}
                        {{--<div class=" col-lg-offset-8">--}}
                        {{--Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}--}}
                        {{--to @php($legend2= $listLoadings->currentPage() * 5) {{$legend2}} of {{$count}}--}}
                        {{--results--}}
                        {{--</div>--}}
                        {{--@endif--}}
                    </div>
                </div>
            </div>
            <!--list loadings with number of pallets Bonduelle-->
            <div id="bonduelle-collapse" class="panel panel-general panel-list-warehouses collapse">
                <div class="panel-heading">Bonduelle F80 - Total ...</div>
                <div class="panel-body panel-body-general">
                    <!-- Table -->
                    <div class="table-responsive table-big-warehouses">
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center colIDLoading">ID Loading</th>
                                <th class="text-center colBonduelle">Anzahl pal</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center colIDLoading">ID Loading</td>
                                <td class="text-center colBonduelle">Anzahl pal</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        {{--<div class="text-left">{!! $listLoadings->render() !!}</div>--}}
                        {{--->appends($links)--}}
                        {{--@if ($listLoadings->currentPage()==$listLoadings->lastPage())--}}
                        {{--<div class=" col-lg-offset-8">--}}
                        {{--Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}--}}
                        {{--to {{$count}} of {{$count}} results--}}
                        {{--</div>--}}
                        {{--@else--}}
                        {{--<div class=" col-lg-offset-8">--}}
                        {{--Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}--}}
                        {{--to @php($legend2= $listLoadings->currentPage() * 5) {{$legend2}} of {{$count}}--}}
                        {{--results--}}
                        {{--</div>--}}
                        {{--@endif--}}
                    </div>
                </div>
            </div>

            <!--list loadings with number of pallets Schefknecht-->
            <div id="schefknecht-collapse" class="panel panel-general panel-list-warehouses collapse">
                <div class="panel-heading">Schefknecht - Total ...</div>
                <div class="panel-body panel-body-general">
                    <!-- Table -->
                    <div class="table-responsive table-big-warehouses">
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center colIDLoading">ID Loading</th>
                                <th class="text-center colSchefknecht">Anzahl pal</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center colIDLoading">ID Loading</td>
                                <td class="text-center colSchefknecht">Anzahl pal</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        {{--<div class="text-left">{!! $listLoadings->render() !!}</div>--}}
                        {{--->appends($links)--}}
                        {{--@if ($listLoadings->currentPage()==$listLoadings->lastPage())--}}
                        {{--<div class=" col-lg-offset-8">--}}
                        {{--Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}--}}
                        {{--to {{$count}} of {{$count}} results--}}
                        {{--</div>--}}
                        {{--@else--}}
                        {{--<div class=" col-lg-offset-8">--}}
                        {{--Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}--}}
                        {{--to @php($legend2= $listLoadings->currentPage() * 5) {{$legend2}} of {{$count}}--}}
                        {{--results--}}
                        {{--</div>--}}
                        {{--@endif--}}
                    </div>
                </div>
            </div>

            <!--list loadings with number of pallets Wildenhofer-->
            <div id="wildenhofer-collapse" class="panel panel-general panel-list-warehouses collapse">
                <div class="panel-heading">Wildenhofer - Total ...</div>
                <div class="panel-body panel-body-general">
                    <!-- Table -->
                    <div class="table-responsive table-big-warehouses">
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center colIDLoading">ID Loading</th>
                                <th class="text-center colWildenhofer">Anzahl pal</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center colIDLoading">ID Loading</td>
                                <td class="text-center colWildenhofer">Anzahl pal</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        {{--<div class="text-left">{!! $listLoadings->render() !!}</div>--}}
                        {{--->appends($links)--}}
                        {{--@if ($listLoadings->currentPage()==$listLoadings->lastPage())--}}
                        {{--<div class=" col-lg-offset-8">--}}
                        {{--Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}--}}
                        {{--to {{$count}} of {{$count}} results--}}
                        {{--</div>--}}
                        {{--@else--}}
                        {{--<div class=" col-lg-offset-8">--}}
                        {{--Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}--}}
                        {{--to @php($legend2= $listLoadings->currentPage() * 5) {{$legend2}} of {{$count}}--}}
                        {{--results--}}
                        {{--</div>--}}
                        {{--@endif--}}
                    </div>
                </div>
            </div>

            <!--list loadings with number of pallets Impex EUX-->
            <div id="impexeux-collapse" class="panel panel-general panel-list-warehouses collapse">
                <div class="panel-heading">Impex EUX - Total ...</div>
                <div class="panel-body panel-body-general">
                    <!-- Table -->
                    <div class="table-responsive table-big-warehouses">
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center colIDLoading">ID Loading</th>
                                <th class="text-center colImpex">Anzahl pal</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center colIDLoading">ID Loading</td>
                                <td class="text-center colImpex">Anzahl pal</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        {{--<div class="text-left">{!! $listLoadings->render() !!}</div>--}}
                        {{--->appends($links)--}}
                        {{--@if ($listLoadings->currentPage()==$listLoadings->lastPage())--}}
                        {{--<div class=" col-lg-offset-8">--}}
                        {{--Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}--}}
                        {{--to {{$count}} of {{$count}} results--}}
                        {{--</div>--}}
                        {{--@else--}}
                        {{--<div class=" col-lg-offset-8">--}}
                        {{--Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}--}}
                        {{--to @php($legend2= $listLoadings->currentPage() * 5) {{$legend2}} of {{$count}}--}}
                        {{--results--}}
                        {{--</div>--}}
                        {{--@endif--}}
                    </div>
                </div>
            </div>

            <!--list loadings with number of pallets Arinthod-->
            <div id="arinthod-collapse" class="panel panel-general panel-list-warehouses collapse">
                <div class="panel-heading">Arinthod - Total ...</div>
                <div class="panel-body panel-body-general">
                    <!-- Table -->
                    <div class="table-responsive table-big-warehouses">
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center colIDLoading">ID Loading</th>
                                <th class="text-center colArinthod">Anzahl pal</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center colIDLoading">ID Loading</td>
                                <td class="text-center colArinthod">Anzahl pal</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        {{--<div class="text-left">{!! $listLoadings->render() !!}</div>--}}
                        {{--->appends($links)--}}
                        {{--@if ($listLoadings->currentPage()==$listLoadings->lastPage())--}}
                        {{--<div class=" col-lg-offset-8">--}}
                        {{--Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}--}}
                        {{--to {{$count}} of {{$count}} results--}}
                        {{--</div>--}}
                        {{--@else--}}
                        {{--<div class=" col-lg-offset-8">--}}
                        {{--Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}--}}
                        {{--to @php($legend2= $listLoadings->currentPage() * 5) {{$legend2}} of {{$count}}--}}
                        {{--results--}}
                        {{--</div>--}}
                        {{--@endif--}}
                    </div>
                </div>
            </div>

            <!--list loadings with number of pallets Spar Wels-->
            <div id="spar-collapse" class="panel panel-general panel-list-warehouses collapse">
                <div class="panel-heading">Spar Wels - Total ...</div>
                <div class="panel-body panel-body-general">
                    <!-- Table -->
                    <div class="table-responsive table-big-warehouses">
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center colIDLoading">ID Loading</th>
                                <th class="text-center colSpar">Anzahl pal</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center colIDLoading">ID Loading</td>
                                <td class="text-center colSpar">Anzahl pal</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        {{--<div class="text-left">{!! $listLoadings->render() !!}</div>--}}
                        {{--->appends($links)--}}
                        {{--@if ($listLoadings->currentPage()==$listLoadings->lastPage())--}}
                        {{--<div class=" col-lg-offset-8">--}}
                        {{--Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}--}}
                        {{--to {{$count}} of {{$count}} results--}}
                        {{--</div>--}}
                        {{--@else--}}
                        {{--<div class=" col-lg-offset-8">--}}
                        {{--Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}--}}
                        {{--to @php($legend2= $listLoadings->currentPage() * 5) {{$legend2}} of {{$count}}--}}
                        {{--results--}}
                        {{--</div>--}}
                        {{--@endif--}}
                    </div>
                </div>
            </div>
                @endif
            </div>
@endsection