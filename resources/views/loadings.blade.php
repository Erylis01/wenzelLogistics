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
            <div class="loadings-pagination text-center"> {!! $listLoadings->render() !!} </div>
            <div class="loadings-wrapper">
                <div class="table-responsive loadings-container">
                    <table class="table table-hover table-bordered table-loadings">
                        <thead>
                        <tr>
                            <th class="text-center">ID</th>
                            {{--<a href="/loadings?order=id&dir={{ $dir ? $dir : 'asc' }}">--}}
                            <th class="text-center">Ladedatum</th>
                            <th class="text-center">Entladedatum</th>
                            <th class="text-center">Disp.</th>
                            <th class="text-center">AtrNr</th>
                            <th class="text-center">Referenz</th>
                            <th class="text-center">Auftraggeber</th>
                            <th class="text-center">Beladestelle</th>
                            <th class="text-center">Land</th>
                            <th class="text-center">Plz</th>
                            <th class="text-center">Ort</th>
                            <th class="text-center">Entladestelle</th>
                            <th class="text-center">Land</th>
                            <th class="text-center">Plz</th>
                            <th class="text-center">Ort</th>
                            <th class="text-center">Anzahl</th>
                            <th class="text-center"></th>
                            <th class="text-center"></th>
                            <th class="text-center"></th>
                            <th class="text-center">Ware</th>
                            <th class="text-center">Gewicht</th>
                            <th class="text-center">Umsatz</th>
                            <th class="text-center">Aufwand</th>
                            <th class="text-center">DB</th>
                            <th class="text-center">Trp</th>
                            <th class="text-center">PT</th>
                            <th class="text-center">Subfrächter</th>
                            <th class="text-center">Pal</th>
                            <th class="text-center">Im Klärung</th>
                            <th class="text-center">Pal Tausch<br>vereinbart ?</th>
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
            </div>
        @endif
    </div>
@endsection