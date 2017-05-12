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
    <div class="row">
        @if(Auth::guest())
            <h4>You need to login to see the content</h4>
        @else
            <div class="">
                <a class="btn btn-default btn-modal" href="{{route('showAllLoadings')}}" >Refresh</a>
            </div>

    <!-- Table -->
            <div class="loadings-wrapper">
        <div class="table-responsive loadings-container">
            <table class="table table-hover  table-bordered table-loadings">
                <thead>
                <tr>
                    <th>ID</th>
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
                <tr>
                    {{--<th>'<a href="colis.php?id='.{{$loading->id}}. '">'.{{$loading->id}}.'</a>'</th>--}}

                    <th>{{$loading->id}}</th>
                    <th>{{$loading->ladedatum}}</th>
                    <th>{{$loading->entladedatum}}</th>
                    <th>{{$loading->disp}}</th>
                    <th>{{$loading->atrnr}}</th>
                    <th>{{$loading->referenz}}</th>
                    <th>{{$loading->auftraggeber}}</th>
                    <th>{{$loading->beladestelle}}</th>
                    <th>{{$loading->landb}}</th>
                    <th>{{$loading->plzb}}</th>
                    <th>{{$loading->ortb}}</th>
                    <th>{{$loading->entladestelle}}</th>
                    <th>{{$loading->lande}}</th>
                    <th>{{$loading->plze}}</th>
                    <th>{{$loading->orte}}</th>
                    <th>{{$loading->anzahl}}</th>
                    <th>{{$loading->try1}}</th>
                    <th>{{$loading->try2}}</th>
                    <th>{{$loading->try3}}</th>
                    <th>{{$loading->ware}}</th>
                    <th>{{$loading->gewicht}}</th>
                    <th>{{$loading->umsatz}}</th>
                    <th>{{$loading->aufwand}}</th>
                    <th>{{$loading->db}}</th>
                    <th>{{$loading->trp}}</th>
                    <th>{{$loading->pt}}</th>
                    <th>{{$loading->subfrachter}}</th>
                    <th>{{$loading->pal}}</th>
                    <th>{{$loading->imklarung}}</th>
                    <th>{{$loading->paltauschvereinbart}}</th>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        </div>
        @endif
    </div>
@endsection