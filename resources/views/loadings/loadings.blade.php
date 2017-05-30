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

                                    <th class="text-center">AtrNr <a
                                                class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=atrnr&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=atrnr&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">Ladedatum <a
                                                class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=ladedatum&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=ladedatum&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">Entladedatum <a
                                                class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=entladedatum&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=entladedatum&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">Disp. <a
                                                class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=disp&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=disp&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">Referenz<br><a
                                                class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=referenz&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=referenz&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">Auftraggeber <a
                                                class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=auftraggeber&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=auftraggeber&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">Beladestelle <a
                                                class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=beladestelle&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=beladestelle&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">Land <a
                                                class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=landb&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=landb&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">Plz <a
                                                class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=plzb&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=plzb&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">Ort<br><a
                                                class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=ortb&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=ortb&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">Entladestelle <a
                                                class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=entladestelle&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=entladestelle&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">Land <a
                                                class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=lande&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=lande&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">Plz<a
                                                class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=plze&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=plze&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">Ort<br><a
                                                class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=orte&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=orte&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">Anzahl <a
                                                class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=anz&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=anz&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">Art.<a
                                                class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=art&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=art&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">Ware<a
                                                class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=ware&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=ware&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">Gewicht<a
                                                class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=gewicht&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=gewicht&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">Vol<a
                                                class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=vol&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=vol&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">LDM<a
                                                class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=ldm&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=ldm&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">Umsatz<a
                                                class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=umsatz&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=umsatz&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">Aufwand<a
                                                class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=aufwand&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=aufwand&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">DB<a class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                                 href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=db&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=db&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">Trp<a
                                                class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=trp&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=trp&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">PT<a class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                                 href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=pt&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=pt&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">Subfrächter<a
                                                class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=subfrachter&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=subfrachter&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">Kennzeichen<a
                                                class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=kennzeichen&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=kennzeichen&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">Zus. Ladestellen<a
                                                class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=zusladestellen&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down loadings-sorting"
                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=zusladestellen&order=desc')}}"></a>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($listLoadings as $loading)
                                    @if($loading->state=="OK")
                                        @php($class="success")
                                    @elseif ($loading->state=="almost OK")
                                        @php($class="warning")
                                    @elseif ($loading->state=="not OK")
                                        @php($class="danger")
                                    @else
                                        @php ($class="default")
                                    @endif
                                    <tr class={{$class}}>
                                        <td class="text-center"><a href="{{route('showDetailsLoading',$loading->atrnr)}}">{{$loading->atrnr}}</a>
                                        </td>
                                        <td class="text-center">{{date('d-m-Y', strtotime($loading->ladedatum))}}</td>
                                        <td class="text-center">{{date('d-m-Y', strtotime($loading->entladedatum))}}</td>
                                        <td class="text-center">{{$loading->disp}}</td>
                                        <td class="text-center">{{$loading->referenz}}</td>
                                        <td class="text-center">{{$loading->auftraggeber}}</td>
                                        <td class="text-center">{{$loading->beladestelle}}</td>
                                        <td class="text-center">{{$loading->landb}}</td>
                                        <td class="text-center">{{$loading->plzb}}</td>
                                        <td class="text-center">{{$loading->ortb}}</td>
                                        <td class="text-center">{{$loading->entladestelle}}</td>
                                        <td class="text-center">{{$loading->lande}}</td>
                                        <td class="text-center">{{$loading->plze}}</td>
                                        <td class="text-center">{{$loading->orte}}</td>
                                        <td class="text-center">{{$loading->anz}}</td>
                                        <td class="text-center">{{$loading->art}}</td>
                                        <td class="text-center">{{$loading->ware}}</td>
                                        <td class="text-center">{{$loading->gewicht}}</td>
                                        <td class="text-center">{{$loading->vol}}</td>
                                        <td class="text-center">{{$loading->ldm}}</td>
                                        <td class="text-center">{{$loading->umsatz}}</td>
                                        <td class="text-center">{{$loading->aufwand}}</td>
                                        <td class="text-center">{{$loading->db}}</td>
                                        <td class="text-center">{{$loading->trp}}</td>
                                        <td class="text-center">{{$loading->pt}}</td>
                                        <td class="text-center">{{$loading->subfrachter}}</td>
                                        <td class="text-center">{{$loading->kennzeichen}}</td>
                                        <td class="text-center">{{$loading->zusladestellen}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="loadings-pagination text-left">{!! $listLoadings->render() !!}</div>
                            {{--->appends($links)--}}
                            @if ($listLoadings->currentPage()==$listLoadings->lastPage())
                                <div class="loadings-legend col-lg-offset-8">
                                    Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}}
                                    to {{$count}} of {{$count}} results
                                </div>
                            @else
                                <div class="loadings-legend col-lg-offset-8">
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