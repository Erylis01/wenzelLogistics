@extends('layouts.default')


@section('title')
    All pallets
@endsection

@section('stylesheet')

@endsection

@section('classPallets')
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
        <div class="table-responsive table-pallets-container">
            <table class="table table-hover  table-bordered table-pallets">
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
                @foreach($listPallets as $pallet)
                <tr>
                    <th>{{$pallet->id}}</th>
                    <th>{{$pallet->ladedatum}}</th>
                    <th>{{$pallet->entladedatum}}</th>
                    <th>{{$pallet->disp}}</th>
                    <th>{{$pallet->atrNr}}</th>
                    <th>{{$pallet->referenz}}</th>
                    <th>{{$pallet->auftraggeber}}</th>
                    <th>{{$pallet->beladestelle}}</th>
                    <th>{{$pallet->landB}}</th>
                    <th>{{$pallet->plzB}}</th>
                    <th>{{$pallet->ortB}}</th>
                    <th>{{$pallet->entladestelle}}</th>
                    <th>{{$pallet->landE}}</th>
                    <th>{{$pallet->plzE}}</th>
                    <th>{{$pallet->ortE}}</th>
                    <th>{{$pallet->anzahl}}</th>
                    <th>{{$pallet->TRY1}}</th>
                    <th>{{$pallet->TRY2}}</th>
                    <th>{{$pallet->TRY3}}</th>
                    <th>{{$pallet->ware}}</th>
                    <th>{{$pallet->gewicht}}</th>
                    <th>{{$pallet->umsatz}}</th>
                    <th>{{$pallet->aufwand}}</th>
                    <th>{{$pallet->db}}</th>
                    <th>{{$pallet->trp}}</th>
                    <th>{{$pallet->pt}}</th>
                    <th>{{$pallet->subfrächter}}</th>
                    <th>{{$pallet->pal}}</th>
                    <th>{{$pallet->imKlärung}}</th>
                    <th>{{$pallet->palTauschVereinbart}}</th>
                </tr>
                @endforeach
                </tbody>
            </table>

        </div>
        @endif
    </div>
@endsection