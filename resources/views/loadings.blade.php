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
    <!-- Table -->
        <div class="table-responsive table-loadings-container">
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
                    <th>{{$loading->id}}</th>
                    <th>{{$loading->ladedatum}}</th>
                    <th>{{$loading->entladedatum}}</th>
                    <th>{{$loading->disp}}</th>
                    <th>{{$loading->atrNr}}</th>
                    <th>{{$loading->referenz}}</th>
                    <th>{{$loading->auftraggeber}}</th>
                    <th>{{$loading->beladestelle}}</th>
                    <th>{{$loading->landB}}</th>
                    <th>{{$loading->plzB}}</th>
                    <th>{{$loading->ortB}}</th>
                    <th>{{$loading->entladestelle}}</th>
                    <th>{{$loading->landE}}</th>
                    <th>{{$loading->plzE}}</th>
                    <th>{{$loading->ortE}}</th>
                    <th>{{$loading->anzahl}}</th>
                    <th>{{$loading->TRY1}}</th>
                    <th>{{$loading->TRY2}}</th>
                    <th>{{$loading->TRY3}}</th>
                    <th>{{$loading->ware}}</th>
                    <th>{{$loading->gewicht}}</th>
                    <th>{{$loading->umsatz}}</th>
                    <th>{{$loading->aufwand}}</th>
                    <th>{{$loading->db}}</th>
                    <th>{{$loading->trp}}</th>
                    <th>{{$loading->pt}}</th>
                    <th>{{$loading->subfrächter}}</th>
                    <th>{{$loading->pal}}</th>
                    <th>{{$loading->imKlärung}}</th>
                    <th>{{$loading->palTauschVereinbart}}</th>
                </tr>
                @endforeach
                </tbody>
            </table>

        </div>
        @endif
    </div>
@endsection