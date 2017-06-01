@extends('layouts.default')

@section('title')
    All loadings
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
            <div class="col-lg-14">
                <div class="panel panel-general panel-warehouses">
                    <div class="panel-heading">List of all loadings</div>

                    <div class="panel-body panel-body-general">
                        <!-- Table -->
                        <div class="table-responsive loadings-wrapper">
                            <table class="table table-hover table-bordered table-loadings">
                                <thead>
                                <tr>

                                    <th class="text-center col1 colHeight">AtrNr<br><a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=atrnr&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=atrnr&order=desc')}}"></a>
                                    </th>
                                    <th class="col1b colHeight"></th>
                                    <th class="text-center colHeight">Ladedatum <a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=ladedatum&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=ladedatum&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center colHeight">Entladedatum <a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=entladedatum&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=entladedatum&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center colHeight">Disp. <a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=disp&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=disp&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center colHeight">Referenz<br><a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=referenz&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=referenz&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center colHeight colLarge">Auftraggeber<br><a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=auftraggeber&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=auftraggeber&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center colHeight colLarge">Beladestelle<br><a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=beladestelle&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=beladestelle&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center colHeight">Land <a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=landb&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=landb&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center colHeight">Plz <a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=plzb&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=plzb&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center colHeight colSmall">Ort<br><a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=ortb&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=ortb&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center colHeight colLarge">Entladestelle<br><a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=entladestelle&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=entladestelle&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center colHeight">Land <a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=lande&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=lande&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center colHeight">Plz<a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=plze&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=plze&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center colHeight colSmall">Ort<br><a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=orte&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=orte&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center colHeight">Anzahl <a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=anz&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=anz&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center colHeight colXSmall">Art.<a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=art&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=art&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center colHeight colMedium">Ware<br><a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=ware&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=ware&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center colHeight">Gewicht<a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=gewicht&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=gewicht&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center colHeight colXSmall">Vol<a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=vol&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=vol&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center colHeight">LDM<a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=ldm&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=ldm&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center colHeight">Umsatz<a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=umsatz&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=umsatz&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center colHeight">Aufwand<a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=aufwand&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=aufwand&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center colHeight colXSmall">DB<a class="glyphicon glyphicon-chevron-up general-sorting"
                                                                                     href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=db&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=db&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center colHeight colXSmall">Trp<a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=trp&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=trp&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center colHeight colXSmall">PT<a class="glyphicon glyphicon-chevron-up general-sorting"
                                                                                     href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=pt&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=pt&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center colHeight colXLarge">Subfrächter<br><a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=subfrachter&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=subfrachter&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center colHeight">Kennzeichen<a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=kennzeichen&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=kennzeichen&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center colHeight colMedium">Zus. Ladestellen<a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=zusladestellen&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=zusladestellen&order=desc')}}"></a>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($listLoadings as $loading)
                                    {{--@if($loading->state=="OK")--}}
                                    {{--@php($class="success")--}}
                                    {{--@elseif ($loading->state=="almost OK")--}}
                                    {{--@php($class="warning")--}}
                                    {{--@elseif ($loading->state=="not OK")--}}
                                    {{--@php($class="danger")--}}
                                    {{--@else--}}
                                    {{--@php ($class="default")--}}
                                    {{--@endif--}}
                                    <tr >
                                        <td class="text-center col1 colHeight"><a href="{{route('showDetailsLoading',$loading->atrnr)}}">{{$loading->atrnr}}</a>
                                        </td>
                                        <td class="col1b colHeight"></td>
                                        <td class="text-center colHeight">{{date('d-m-Y', strtotime($loading->ladedatum))}}</td>
                                        <td class="text-center colHeight">{{date('d-m-Y', strtotime($loading->entladedatum))}}</td>
                                        <td class="text-center colHeight">{{$loading->disp}}</td>
                                        <td class="text-center colHeight">{{$loading->referenz}}</td>
                                        <td class="text-center colHeight">{{$loading->auftraggeber}}</td>
                                        <td class="text-center colHeight">{{$loading->beladestelle}}</td>
                                        <td class="text-center colHeight">{{$loading->landb}}</td>
                                        <td class="text-center colHeight">{{$loading->plzb}}</td>
                                        <td class="text-center colHeight">{{$loading->ortb}}</td>
                                        <td class="text-center colHeight">{{$loading->entladestelle}}</td>
                                        <td class="text-center colHeight">{{$loading->lande}}</td>
                                        <td class="text-center colHeight">{{$loading->plze}}</td>
                                        <td class="text-center colHeight">{{$loading->orte}}</td>
                                        <td class="text-center colHeight">{{$loading->anz}}</td>
                                        <td class="text-center colHeight">{{$loading->art}}</td>
                                        <td class="text-center colHeight">{{$loading->ware}}</td>
                                        <td class="text-center colHeight">{{$loading->gewicht}}</td>
                                        <td class="text-center colHeight">{{$loading->vol}}</td>
                                        <td class="text-center colHeight">{{$loading->ldm}}</td>
                                        <td class="text-center colHeight">{{$loading->umsatz}}</td>
                                        <td class="text-center colHeight">{{$loading->aufwand}}</td>
                                        <td class="text-center colHeight">{{$loading->db}}</td>
                                        <td class="text-center colHeight">{{$loading->trp}}</td>
                                        <td class="text-center colHeight">{{$loading->pt}}</td>
                                        <td class="text-center colHeight">{{$loading->subfrachter}}</td>
                                        <td class="text-center colHeight">{{$loading->kennzeichen}}</td>
                                        <td class="text-center colHeight">{{$loading->zusladestellen}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="general-pagination text-left">{!! $listLoadings->render() !!}</div>
                            {{--->appends($links)--}}
                            @if ($listLoadings->currentPage()==$listLoadings->lastPage())
                                <div class="general-legend col-lg-offset-8">
                                    Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}
                                    to {{$count}} of {{$count}} results
                                </div>
                            @else
                                <div class="general-legend col-lg-offset-8">
                                    Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}
                                    to @php($legend2= $listLoadings->currentPage() * 5) {{$legend2}} of {{$count}}
                                    results
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection