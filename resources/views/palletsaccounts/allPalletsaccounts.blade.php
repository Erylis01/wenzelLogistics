@extends('layouts.default')

@section('title')
    All pallets accounts
@endsection

@section('stylesheet')
    <link href="{{asset('css/palletsaccounts.css')}}" rel="stylesheet" type="text/css">
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
                <div class="panel panel-general col-lg-6 panel-palletsaccounts">
                    <div class="panel-heading">Total of pallets by account</div>

                    <div class="panel-body">
                        @if (Session::has('messageDeletePalletsaccount'))
                            <div class="alert alert-success text-alert text-center">{{ Session::get('messageDeletePalletsaccount') }}</div>
                        @endif
                        <div class="table-responsive table-palletsaccounts">
                            <table class="table table-hover table-bordered">
                                <thead>
                                @if($totalpallets<0)
                                    @php($class="text-alert")
                                @elseif($totalpallets>0)
                                    @php($class="text-warning")
                                    @else
                                    @php($class="text-success")
                                @endif
                                <tr>
                                    <th class="text-center colName"><a href="{{route('showTotalPalletsaccounts')}}"
                                                                       class="link">TOTAL</a></th>
                                    <th class="text-center colTotal"><span class={{$class}}>{{$totalpallets}}</span>
                                    </th>
                                </tr>
                                </thead>
                            </table>
                        </div>

                        <!-- Table -->
                        <div class="table-responsive table-palletsaccounts">
                            <table class="table table-hover table-bordered">
                                <thead>
                                <tr>
                                    <th class="text-center colName">Name<br><a
                                                class="glyphicon glyphicon-chevron-up"
                                                href="{{url('/allPalletsaccounts?sortby=name&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down"
                                                href="{{url('/allPalletsaccounts?sortby=name&order=desc')}}"></a></th>
                                    <th class="text-center colTotal">Total<br><a
                                                class="glyphicon glyphicon-chevron-up"
                                                href="{{url('/allPalletsaccounts?sortby=numberPallets&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down"
                                                href="{{url('/allPalletsaccounts?sortby=numberPallets&order=desc')}}"></a></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($listPalletsaccounts as $palletsaccount)
                                    <tr>
                                        <td class="text-center colName"><a href="#{{str_replace(' ', '', $palletsaccount->name)}}-collapse"
                                                                           data-toggle="collapse"
                                                                           class="link">{{$palletsaccount->name}}</a></td>
                                        <td class="text-center colTotal">{{$palletsaccount->numberPallets}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                @foreach($listPalletsaccounts as $palletsaccount)
                <div id="{{str_replace(' ', '', $palletsaccount->name)}}-collapse" class="panel panel-general col-lg-8 col-lg-offset-1 panel-palletsaccounts-details collapse">
                    <div class="panel-heading">Account n° {{$palletsaccount->id}} : {{$palletsaccount->name}}</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="">
                            <div class="form-group">
                                <div class="col-lg-5">
                                    <label for="numberPallets" class="control-label legend-palletsaccounts">Number of pallets :</label>
                                </div>
                                <div class="col-lg-6">
                                    <input id="numberPallets" type="number" class="form-control info-palletsaccounts" name="numberPallets"
                                           value="" placeholder="Pallets number" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-5">
                                    <label for="warehousesAssociated" class="control-label legend-palletsaccounts">Warehouses
                                        associated :</label>
                                </div>
                                <div class="col-lg-6 info-palletsaccounts">
                                    <ul>
                                        <li>lalal</li>
                                    </ul>
                                </div>
                                {{--id="warehousesAssociated" type="text" class="form-control" name="warehousesAssociated"--}}
                                {{--value="" placeholder="Warehouses associated" readonly>--}}
                            </div>

                            <div class="form-group">
                                <div class="col-lg-offset-5 col-lg-6">
                                    <a href="{{route('showDetailsPalletsaccount', $palletsaccount->id)}}" class="btn btn-form btn-block">Details pallets transferts</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            {{--<!--list loadings with number of pallets by warehouse-->--}}
            {{--<div id="total-collapse" class="panel panel-general panel-list-warehouses collapse">--}}
            {{--<div class="panel-heading">Loadings list : number of pallets by warehouse</div>--}}

            {{--<div class="panel-body">--}}
            {{--<!-- Table -->--}}
            {{--<div class="table-responsive table-big-warehouses">--}}
            {{--<table class="table table-hover table-bordered">--}}
            {{--<thead>--}}
            {{--<tr>--}}
            {{--<th class="text-center colRefLoading">Referenz Loading </th>--}}
            {{--<th class="text-center colTotal">TOTAL</th>--}}
            {{--@foreach($warehouses as $warehouse)--}}
            {{--@if($warehouse->name=='Fakturiert')--}}
            {{--@php($size='colFakturiert')--}}
            {{--@elseif($warehouse->name=='Verschenkt')--}}
            {{--@php($size='colVerschenkt')--}}
            {{--@elseif($warehouse->name=='ECL Wolfurt')--}}
            {{--@php($size='colECLWolfurt')--}}
            {{--@elseif($warehouse->name=='Systempo AT')--}}
            {{--@php($size='colSystempo')--}}
            {{--@elseif($warehouse->name=='Benoit & Valerie')--}}
            {{--@php($size='colBenoit')--}}
            {{--@elseif($warehouse->name=='PFM-FR')--}}
            {{--@php($size='colPFM')--}}
            {{--@elseif($warehouse->name=='Team Tex')--}}
            {{--@php($size='colTeamTex')--}}
            {{--@elseif($warehouse->name=='ALDI DAG')--}}
            {{--@elseif($warehouse->name=='ALDI SWB')--}}
            {{--@php($size='colAldiSWB')--}}
            {{--@elseif($warehouse->name=='ALDI DOM')--}}
            {{--@php($size='colAldiDOM')--}}
            {{--@elseif($warehouse->name=='Spar Wels')--}}
            {{--@php($size='colSpar')--}}
            {{--@elseif($warehouse->name=='Dachser F51')--}}
            {{--@php($size='colDachser')--}}
            {{--@elseif($warehouse->name=='Impex-EUX')--}}
            {{--@php($size='colImpexEUX')--}}
            {{--@elseif($warehouse->name=='Impex-EUY')--}}
            {{--@php($size='colImpexEUY')--}}
            {{--@elseif($warehouse->name=='Bonduelle F80')--}}
            {{--@php($size='colBonduelle')--}}
            {{--@elseif($warehouse->name=='Schefknecht')--}}
            {{--@php($size='colSchefknecht')--}}
            {{--@elseif($warehouse->name=='Wildenhofer Salzburg')--}}
            {{--@php($size='colWildenhofer')--}}
            {{--@elseif($warehouse->name=='Arinthod')--}}
            {{--@php($size='colArinthod')--}}
            {{--@endif--}}
            {{--<th class="text-center {{$size}}">{{$warehouse->name}}</th>--}}
            {{--@endforeach--}}
            {{--</tr>--}}
            {{--</thead>--}}
            {{--<tbody>--}}
            {{--@foreach($warehouses as $warehouse)--}}
            {{--@foreach($warehouse->loadings as $loading)--}}
            {{--@php($sum=Illuminate\Support\Facades\DB::table('loadings')->select(DB::raw('SUM(id + anzahl) as total'))--}}
            {{--->where('id', '=' , $loading->id)->get())--}}
            {{--<tr>--}}
            {{--<td class="text-center colIDLoading">{{$loading->referenz}}</td>--}}
            {{--<td class="text-center colTotal">{{$sum[0]->total}}</td>--}}
            {{--@foreach($warehouses as $warehouse)--}}
            {{--@if($warehouse->id == $loading->warehouse_id)--}}
            {{--<td class="text-center">{{$loading->anzahl}}</td>--}}
            {{--@else--}}
            {{--<td class="text-center"></td>--}}
            {{--@endif--}}
            {{--@endforeach--}}
            {{--@endforeach--}}
            {{--@endforeach--}}
            {{--</tr>--}}
            {{--</tbody>--}}
            {{--</table>--}}
            {{--</div>--}}
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
            {{--</div>--}}
            {{--</div>--}}

            {{--@foreach($warehouses as $warehouse)--}}
            {{--@if($warehouse->name=='Fakturiert')--}}
            {{--@php($size='colFakturiert')--}}
            {{--@elseif($warehouse->name=='Verschenkt')--}}
            {{--@php($size='colVerschenkt')--}}
            {{--@elseif($warehouse->name=='ECL Wolfurt')--}}
            {{--@php($size='colECLWolfurt')--}}
            {{--@elseif($warehouse->name=='Systempo AT')--}}
            {{--@php($size='colSystempo')--}}
            {{--@elseif($warehouse->name=='Benoit & Valerie')--}}
            {{--@php($size='colBenoit')--}}
            {{--@elseif($warehouse->name=='PFM-FR')--}}
            {{--@php($size='colPFM')--}}
            {{--@elseif($warehouse->name=='Team Tex')--}}
            {{--@php($size='colTeamTex')--}}
            {{--@elseif($warehouse->name=='ALDI DAG')--}}
            {{--@elseif($warehouse->name=='ALDI SWB')--}}
            {{--@php($size='colAldiSWB')--}}
            {{--@elseif($warehouse->name=='ALDI DOM')--}}
            {{--@php($size='colAldiDOM')--}}
            {{--@elseif($warehouse->name=='Spar Wels')--}}
            {{--@php($size='colSpar')--}}
            {{--@elseif($warehouse->name=='Dachser F51')--}}
            {{--@php($size='colDachser')--}}
            {{--@elseif($warehouse->name=='Impex-EUX')--}}
            {{--@php($size='colImpexEUX')--}}
            {{--@elseif($warehouse->name=='Impex-EUY')--}}
            {{--@php($size='colImpexEUY')--}}
            {{--@elseif($warehouse->name=='Bonduelle F80')--}}
            {{--@php($size='colBonduelle')--}}
            {{--@elseif($warehouse->name=='Schefknecht')--}}
            {{--@php($size='colSchefknecht')--}}
            {{--@elseif($warehouse->name=='Wildenhofer Salzburg')--}}
            {{--@php($size='colWildenhofer')--}}
            {{--@elseif($warehouse->name=='Arinthod')--}}
            {{--@php($size='colArinthod')--}}
            {{--@endif--}}
            {{--<!--list loadings with number of pallets-->--}}
            {{--<div id="{{$size}}-collapse" class="panel panel-general panel-list-warehouses collapse">--}}
            {{--<div class="panel-heading">{{$warehouse->name}} - {{$warehouse->palanzahl}} pallet(s)</div>--}}
            {{--<div class="panel-body">--}}
            {{--<!-- Table -->--}}
            {{--<div class="table-responsive table-big-warehouses">--}}
            {{--<table class="table table-hover table-bordered">--}}
            {{--<thead>--}}
            {{--<tr>--}}
            {{--<th class="text-center colIDLoading">Referenz Loading</th>--}}
            {{--<th class="text-center {{$size}}">Anzahl pal</th>--}}
            {{--</tr>--}}
            {{--</thead>--}}
            {{--<tbody>--}}
            {{--@foreach($warehouse->loadings as $loading)--}}
            {{--<tr>--}}
            {{--<td class="text-center colIDLoading">{{$loading->referenz}}</td>--}}
            {{--<td class="text-center {{$size}}">{{$loading->anzahl}}</td>--}}
            {{--</tr>--}}
            {{--@endforeach--}}
            {{--</tbody>--}}
            {{--</table>--}}
            {{--</div>--}}
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
            {{--</div>--}}
            {{--</div>--}}
            {{--@endforeach--}}
        @endif
    </div>
@endsection