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
            <div class="loadings-wrapper">
                <div class="table-responsive loadings-container">
                    <table class="table table-hover table-bordered table-loadings">
                        <thead>
                        <tr>
                            <th>ID</th>
                            {{--<a href="/loadings?order=id&dir={{ $dir ? $dir : 'asc' }}">--}}
                            <th>Ladedatum</th>
                            <th>Entladedatum</th>
                            <th>Disp.</th>
                            <th>AtrNr</th>
                            <th>Referenz</th>
                            <th>Auftraggeber</th>
                            <th>Beladestelle</th>
                            <th>Land</th>
                            <th>Plz</th>
                            <th>Ort</th>
                            <th>Entladestelle</th>
                            <th>Land</th>
                            <th>Plz</th>
                            <th>Ort</th>
                            <th>Anzahl</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Ware</th>
                            <th>Gewicht</th>
                            <th>Umsatz</th>
                            <th>Aufwand</th>
                            <th>DB</th>
                            <th>Trp</th>
                            <th>PT</th>
                            <th>Subfrächter</th>
                            <th>Pal</th>
                            <th>Im Klärung</th>
                            <th>Pal Tausch<br>vereinbart ?</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($listLoadings as $loading)
                            @if($loading->disp=="OK")
                                @php($class="success")
                                @elseif ($loading->disp=="almost OK")
                                @php($class="warning")
                                @elseif ($loading->disp=="not OK")
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
            </div>
        @endif
    </div>
@endsection