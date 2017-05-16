@extends('layouts.default')

@section('title')
    All loadings
@endsection

@section('stylesheet')

@endsection

@section('classLoadings')
    class="active"
@endsection
@section('classProfile')
    nonActive
@endsection

@section('content')
    {{--<div class="row">--}}
    {{--<div class="text-center col-lg-offset-4 col-lg-4">--}}
    {{--<a class="btn btn-default btn-block btn-refresh" href="{{route('showAllLoadings')}}">Refresh</a>--}}
    {{--</div>--}}
    {{--</div>--}}

    <div class="row">
        @if(Auth::guest())
            <h4>You need to login to see the content</h4>
        @else

        <!-- Table -->
                    <div class="table-responsive loadings-wrapper">
                <table class="table table-hover table-bordered table-loadings">
                    <thead>
                    <tr>

                        <th class="text-center">ID<a class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                      href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=id&order=asc')}}"></a><a
                                    class="glyphicon glyphicon-chevron-down loadings-sorting"
                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=id&order=desc')}}"></a></th>
                        <th class="text-center">Ladedatum <a class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                             href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=ladedatum&order=asc')}}"></a><a
                                    class="glyphicon glyphicon-chevron-down loadings-sorting"
                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=ladedatum&order=desc')}}"></a></th>
                        <th class="text-center">Entladedatum <a class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=entladedatum&order=asc')}}"></a><a
                                    class="glyphicon glyphicon-chevron-down loadings-sorting"
                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=entladedatum&order=desc')}}"></a></th>
                        <th class="text-center">Disp. <a class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                         href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=disp&order=asc')}}"></a><a
                                    class="glyphicon glyphicon-chevron-down loadings-sorting"
                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=disp&order=desc')}}"></a></th>
                        <th class="text-center">AtrNr <a class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                         href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=atrnr&order=asc')}}"></a><a
                                    class="glyphicon glyphicon-chevron-down loadings-sorting"
                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=atrnr&order=desc')}}"></a></th>
                        <th class="text-center">Referenz <a class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                            href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=referenz&order=asc')}}"></a><a
                                    class="glyphicon glyphicon-chevron-down loadings-sorting"
                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=referenz&order=desc')}}"></a></th>
                        <th class="text-center">Auftraggeber <a class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=auftraggeber&order=asc')}}"></a><a
                                    class="glyphicon glyphicon-chevron-down loadings-sorting"
                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=auftraggeber&order=desc')}}"></a></th>
                        <th class="text-center">Beladestelle <a class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                                href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=beladestelle&order=asc')}}"></a><a
                                    class="glyphicon glyphicon-chevron-down loadings-sorting"
                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=beladestelle&order=desc')}}"></a></th>
                        <th class="text-center">Land <a class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                        href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=landb&order=asc')}}"></a><a
                                    class="glyphicon glyphicon-chevron-down loadings-sorting"
                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=landb&order=desc')}}"></a></th>
                        <th class="text-center">Plz <a class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                       href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=plzb&order=asc')}}"></a><a
                                    class="glyphicon glyphicon-chevron-down loadings-sorting"
                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=plzb&order=desc')}}"></a></th>
                        <th class="text-center">Ort<br><a class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                       href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=ortb&order=asc')}}"></a><a
                                    class="glyphicon glyphicon-chevron-down loadings-sorting"
                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=ortb&order=desc')}}"></a></th>
                        <th class="text-center">Entladestelle <a class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                                 href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=entladestelle&order=asc')}}"></a><a
                                    class="glyphicon glyphicon-chevron-down loadings-sorting"
                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=entladestelle&order=desc')}}"></a></th>
                        <th class="text-center">Land <a class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                        href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=lande&order=asc')}}"></a><a
                                    class="glyphicon glyphicon-chevron-down loadings-sorting"
                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=lande&order=desc')}}"></a></th>
                        <th class="text-center">Plz<a class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                      href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=plze&order=asc')}}"></a><a
                                    class="glyphicon glyphicon-chevron-down loadings-sorting"
                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=plze&order=desc')}}"></a></th>
                        <th class="text-center">Ort<br><a class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                      href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=orte&order=asc')}}"></a><a
                                    class="glyphicon glyphicon-chevron-down loadings-sorting"
                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=orte&order=desc')}}"></a></th>
                        <th class="text-center">Anzahl <a class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                          href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=anzahl&order=asc')}}"></a><a
                                    class="glyphicon glyphicon-chevron-down loadings-sorting"
                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=anzahl&order=desc')}}"></a></th>
                        <th class="text-center">TRY1<a class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                   href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=try1&order=asc')}}"></a><a
                                    class="glyphicon glyphicon-chevron-down loadings-sorting"
                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=try1&order=desc')}}"></a></th>
                        <th class="text-center">TRY2<a class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                   href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=try2&order=asc')}}"></a><a
                                    class="glyphicon glyphicon-chevron-down loadings-sorting"
                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=try2&order=desc')}}"></a></th>
                        <th class="text-center">TRY3<a class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                       href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=try3&order=asc')}}"></a><a
                                    class="glyphicon glyphicon-chevron-down loadings-sorting"
                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=try3&order=desc')}}"></a></th>
                        <th class="text-center">Ware<a class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                        href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=ware&order=asc')}}"></a><a
                                    class="glyphicon glyphicon-chevron-down loadings-sorting"
                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=ware&order=desc')}}"></a></th>
                        <th class="text-center">Gewicht<a class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                          href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=gewicht&order=asc')}}"></a><a
                                    class="glyphicon glyphicon-chevron-down loadings-sorting"
                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=gewicht&order=desc')}}"></a></th>
                        <th class="text-center">Umsatz<a class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                         href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=umsatz&order=asc')}}"></a><a
                                    class="glyphicon glyphicon-chevron-down loadings-sorting"
                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=umsatz&order=desc')}}"></a></th>
                        <th class="text-center">Aufwand<a class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                          href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=aufwand&order=asc')}}"></a><a
                                    class="glyphicon glyphicon-chevron-down loadings-sorting"
                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=aufwand&order=desc')}}"></a></th>
                        <th class="text-center">DB<a class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                     href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=db&order=asc')}}"></a><a
                                    class="glyphicon glyphicon-chevron-down loadings-sorting"
                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=db&order=desc')}}"></a></th>
                        <th class="text-center">Trp<a class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                      href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=trp&order=asc')}}"></a><a
                                    class="glyphicon glyphicon-chevron-down loadings-sorting"
                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=trp&order=desc')}}"></a></th>
                        <th class="text-center">PT<a class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                     href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=pt&order=asc')}}"></a><a
                                    class="glyphicon glyphicon-chevron-down loadings-sorting"
                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=pt&order=desc')}}"></a></th>
                        <th class="text-center">Subfrächter<a class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                              href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=subfrachter&order=asc')}}"></a><a
                                    class="glyphicon glyphicon-chevron-down loadings-sorting"
                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=subfrachter&order=desc')}}"></a></th>
                        <th class="text-center">Pal<a class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                      href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=pal&order=asc')}}"></a><a
                                    class="glyphicon glyphicon-chevron-down loadings-sorting"
                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=pal&order=desc')}}"></a></th>
                        <th class="text-center">Im Klärung<a class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                             href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=imklarung&order=asc')}}"></a><a
                                    class="glyphicon glyphicon-chevron-down loadings-sorting"
                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=imklarung&order=desc')}}"></a></th>
                        <th class="text-center">Pal Tausch Vereinbart ? <a class="glyphicon glyphicon-chevron-up loadings-sorting"
                                                                          href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=paltauschvereinbart&order=asc')}}"></a><a
                                    class="glyphicon glyphicon-chevron-down loadings-sorting"
                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=paltauschvereinbart&order=desc')}}"></a></th>
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
                            <td><a href="{{route('showDetailsLoading',$loading->id)}}">{{$loading->id}}</a></td>
                            <td>{{date('d-m-Y', strtotime($loading->ladedatum))}}</td>
                            <td>{{$loading->entladedatum}}</td>
                            <td>{{$loading->disp}}</td>
                            <td>{{$loading->atrnr}}</td>
                            <td>{{$loading->referenz}}</td>
                            <td>{{$loading->auftraggeber}}</td>
                            <td>{{$loading->beladestelle}}</td>
                            <td>{{$loading->landb}}</td>
                            <td>{{$loading->plzb}}</td>
                            <td>{{$loading->ortb}}</td>
                            <td>{{$loading->entladestelle}}</td>
                            <td>{{$loading->lande}}</td>
                            <td>{{$loading->plze}}</td>
                            <td>{{$loading->orte}}</td>
                            <td>{{$loading->anzahl}}</td>
                            <td>{{$loading->try1}}</td>
                            <td>{{$loading->try2}}</td>
                            <td>{{$loading->try3}}</td>
                            <td>{{$loading->ware}}</td>
                            <td>{{$loading->gewicht}}</td>
                            <td>{{$loading->umsatz}}</td>
                            <td>{{$loading->aufwand}}</td>
                            <td>{{$loading->db}}</td>
                            <td>{{$loading->trp}}</td>
                            <td>{{$loading->pt}}</td>
                            <td>{{$loading->subfrachter}}</td>
                            <td>{{$loading->pal}}</td>
                            <td>{{$loading->imklarung}}</td>
                            <td>{{$loading->paltauschvereinbart}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        <div class="row">
            <div class="loadings-pagination text-left">{!! $listLoadings->render() !!}</div>
            {{--->appends($links)--}}
            @if ($listLoadings->currentPage()==$listLoadings->lastPage())
                <div class="loadings-legend col-lg-offset-8" >Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}} to {{$count}} of {{$count}} results</div>
            @else
                <div class="loadings-legend col-lg-offset-8" >Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 5)  {{$legend1}} to @php($legend2= $listLoadings->currentPage() * 5) {{$legend2}} of {{$count}} results</div>
                @endif
        </div>

        @endif
    </div>
@endsection