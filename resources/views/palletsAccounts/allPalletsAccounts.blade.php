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
    class="nonActive"
@endsection
@section('classPalletsAccounts')
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

                    <div class="panel-body">
                        <!-- Table -->
                        <div class="table-responsive table-small-warehouses">
                            <table class="table table-hover table-bordered">
                                <thead>
                                <tr>
                                    <th class="text-center colTotal"><a href="#total-collapse" data-toggle="collapse"
                                                                        class="link">TOTAL</a></th>
                                    @foreach($warehouses as $warehouse)
                                        @if($warehouse->name=='Fakturiert')
                                            @php($size='colFakturiert')
                                        @elseif($warehouse->name=='Verschenkt')
                                            @php($size='colVerschenkt')
                                        @elseif($warehouse->name=='ECL Wolfurt')
                                            @php($size='colECLWolfurt')
                                        @elseif($warehouse->name=='Systempo AT')
                                            @php($size='colSystempo')
                                        @elseif($warehouse->name=='Benoit & Valerie')
                                            @php($size='colBenoit')
                                        @elseif($warehouse->name=='PFM-FR')
                                            @php($size='colPFM')
                                        @elseif($warehouse->name=='Team Tex')
                                            @php($size='colTeamTex')
                                        @elseif($warehouse->name=='ALDI DAG')
                                        @elseif($warehouse->name=='ALDI SWB')
                                            @php($size='colAldiSWB')
                                        @elseif($warehouse->name=='ALDI DOM')
                                            @php($size='colAldiDOM')
                                        @elseif($warehouse->name=='Spar Wels')
                                            @php($size='colSpar')
                                        @elseif($warehouse->name=='Dachser F51')
                                            @php($size='colDachser')
                                        @elseif($warehouse->name=='Impex-EUX')
                                            @php($size='colImpexEUX')
                                        @elseif($warehouse->name=='Impex-EUY')
                                            @php($size='colImpexEUY')
                                        @elseif($warehouse->name=='Bonduelle F80')
                                            @php($size='colBonduelle')
                                        @elseif($warehouse->name=='Schefknecht')
                                            @php($size='colSchefknecht')
                                        @elseif($warehouse->name=='Wildenhofer Salzburg')
                                            @php($size='colWildenhofer')
                                        @elseif($warehouse->name=='Arinthod')
                                            @php($size='colArinthod')
                                        @endif
                                        <th class="text-center {{$size}}"><a href="#{{$size}}-collapse"
                                                                             data-toggle="collapse"
                                                                             class="link">{{$warehouse->name}}</a></th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="text-center colTotal">{{$totalpalanzahl}}</td>
                                    @foreach($warehouses as $warehouse)
                                        <td class="text-center">{{$warehouse->palanzahl}}</td>
                                    @endforeach
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

                <div class="panel-body">
                    <!-- Table -->
                    <div class="table-responsive table-big-warehouses">
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center colRefLoading">Referenz Loading </th>
                                <th class="text-center colTotal">TOTAL</th>
                                @foreach($warehouses as $warehouse)
                                    @if($warehouse->name=='Fakturiert')
                                        @php($size='colFakturiert')
                                    @elseif($warehouse->name=='Verschenkt')
                                        @php($size='colVerschenkt')
                                    @elseif($warehouse->name=='ECL Wolfurt')
                                        @php($size='colECLWolfurt')
                                    @elseif($warehouse->name=='Systempo AT')
                                        @php($size='colSystempo')
                                    @elseif($warehouse->name=='Benoit & Valerie')
                                        @php($size='colBenoit')
                                    @elseif($warehouse->name=='PFM-FR')
                                        @php($size='colPFM')
                                    @elseif($warehouse->name=='Team Tex')
                                        @php($size='colTeamTex')
                                    @elseif($warehouse->name=='ALDI DAG')
                                    @elseif($warehouse->name=='ALDI SWB')
                                        @php($size='colAldiSWB')
                                    @elseif($warehouse->name=='ALDI DOM')
                                        @php($size='colAldiDOM')
                                    @elseif($warehouse->name=='Spar Wels')
                                        @php($size='colSpar')
                                    @elseif($warehouse->name=='Dachser F51')
                                        @php($size='colDachser')
                                    @elseif($warehouse->name=='Impex-EUX')
                                        @php($size='colImpexEUX')
                                    @elseif($warehouse->name=='Impex-EUY')
                                        @php($size='colImpexEUY')
                                    @elseif($warehouse->name=='Bonduelle F80')
                                        @php($size='colBonduelle')
                                    @elseif($warehouse->name=='Schefknecht')
                                        @php($size='colSchefknecht')
                                    @elseif($warehouse->name=='Wildenhofer Salzburg')
                                        @php($size='colWildenhofer')
                                    @elseif($warehouse->name=='Arinthod')
                                        @php($size='colArinthod')
                                    @endif
                                    <th class="text-center {{$size}}">{{$warehouse->name}}</th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($warehouses as $warehouse)
                                @foreach($warehouse->loadings as $loading)
                                    @php($sum=Illuminate\Support\Facades\DB::table('loadings')->select(DB::raw('SUM(id + anzahl) as total'))
->where('id', '=' , $loading->id)->get())
                                    <tr>
                                        <td class="text-center colIDLoading">{{$loading->referenz}}</td>
                                        <td class="text-center colTotal">{{$sum[0]->total}}</td>
                                        @foreach($warehouses as $warehouse)
                                            @if($warehouse->id == $loading->warehouse_id)
                                                <td class="text-center">{{$loading->anzahl}}</td>
                                            @else
                                                <td class="text-center"></td>
                                            @endif
                                        @endforeach
                                        @endforeach
                                        @endforeach
                                    </tr>
                            </tbody>
                        </table>
                    </div>
                    {{--<div class="row">--}}
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
                    {{--</div>--}}
                </div>
            </div>

            @foreach($warehouses as $warehouse)
                @if($warehouse->name=='Fakturiert')
                    @php($size='colFakturiert')
                @elseif($warehouse->name=='Verschenkt')
                    @php($size='colVerschenkt')
                @elseif($warehouse->name=='ECL Wolfurt')
                    @php($size='colECLWolfurt')
                @elseif($warehouse->name=='Systempo AT')
                    @php($size='colSystempo')
                @elseif($warehouse->name=='Benoit & Valerie')
                    @php($size='colBenoit')
                @elseif($warehouse->name=='PFM-FR')
                    @php($size='colPFM')
                @elseif($warehouse->name=='Team Tex')
                    @php($size='colTeamTex')
                @elseif($warehouse->name=='ALDI DAG')
                @elseif($warehouse->name=='ALDI SWB')
                    @php($size='colAldiSWB')
                @elseif($warehouse->name=='ALDI DOM')
                    @php($size='colAldiDOM')
                @elseif($warehouse->name=='Spar Wels')
                    @php($size='colSpar')
                @elseif($warehouse->name=='Dachser F51')
                    @php($size='colDachser')
                @elseif($warehouse->name=='Impex-EUX')
                    @php($size='colImpexEUX')
                @elseif($warehouse->name=='Impex-EUY')
                    @php($size='colImpexEUY')
                @elseif($warehouse->name=='Bonduelle F80')
                    @php($size='colBonduelle')
                @elseif($warehouse->name=='Schefknecht')
                    @php($size='colSchefknecht')
                @elseif($warehouse->name=='Wildenhofer Salzburg')
                    @php($size='colWildenhofer')
                @elseif($warehouse->name=='Arinthod')
                    @php($size='colArinthod')
                @endif
            <!--list loadings with number of pallets-->
                <div id="{{$size}}-collapse" class="panel panel-general panel-list-warehouses collapse">
                    <div class="panel-heading">{{$warehouse->name}} - {{$warehouse->palanzahl}} pallet(s)</div>
                    <div class="panel-body">
                        <!-- Table -->
                        <div class="table-responsive table-big-warehouses">
                            <table class="table table-hover table-bordered">
                                <thead>
                                <tr>
                                    <th class="text-center colIDLoading">Referenz Loading</th>
                                    <th class="text-center {{$size}}">Anzahl pal</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($warehouse->loadings as $loading)
                                    <tr>
                                        <td class="text-center colIDLoading">{{$loading->referenz}}</td>
                                        <td class="text-center {{$size}}">{{$loading->anzahl}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{--<div class="row">--}}
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
                        {{--</div>--}}
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection