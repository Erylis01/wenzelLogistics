@extends('layouts.default')


@section('title')
    Pallets
@endsection

@section('stylesheet')

@endsection

@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-success">
                <div class="panel-heading">List of the pallets</div>

            @if(Auth::check())
                <!-- Table -->
                    <table class="table">
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
                            <th>Anzahl</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Ware</th>
                            <th>Gewitch</th>
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
                        @foreach($characters as $key => $value)
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                        @endforeach
                    </table>
                @endif
            </div>
            @if(Auth::guest())
                You need to login to see the list 😜😜 >>
            @endif
        </div>
    </div>
@endsection