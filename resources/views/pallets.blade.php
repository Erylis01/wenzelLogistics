@extends('layouts.default')


@section('title')
    Pallets
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
                </thead>
                <tbody>
                {{--@foreach($characters as $key => $value)--}}
                {{--<tr>--}}
                {{--<td></td>--}}
                {{--<td></td>--}}
                {{--</tr>--}}
                {{--@endforeach--}}
                </tbody>
            </table>
            {{--</div>--}}
        </div>
        @if(Auth::guest())
            <h4>You need to login to see the content</h4>
        @endif
    </div>
@endsection