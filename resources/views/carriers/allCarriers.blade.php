@extends('layouts.default')

@section('title')
    All carriers
@endsection

@section('stylesheet')
    <link href="{{asset('css/carriers.css')}}" rel="stylesheet" type="text/css">
@endsection

@section('classLoadings')
    class="nonActive"
@endsection
@section('classWarehouses')
    class="nonActive"
@endsection
@section('classCarriers')
    class="active"
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
                <div class="panel panel-general panel-carriers">
                    <div class="panel-heading">List of all carriers <span class="col-lg-offset-7">
                            <a href="{{route('showAddCarrier')}}" class="btn btn-add"><span class="glyphicon glyphicon-plus-sign"></span> Add carrier</a>
                        </span></div>

                    <div class="panel-body panel-body-general">

                        @if(Session::has('messageDeleteCarrier'))
                            <div class="alert alert-success text-alert text-center">{{ Session::get('messageDeleteCarrier') }}</div>
                        @elseif(Session::has('messageAddCarrier'))
                            <div class="alert alert-success text-alert text-center">{{ Session::get('messageAddCarrier') }}</div>
                    @endif



                    <!-- Table -->
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead>
                                <tr>
                                    <th class="text-center">ID<br> <a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/allCarriers?page='.$listCarriers->currentPage().'&sortby=id&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/allCarriers?page='.$listCarriers->currentPage().'&sortby=id&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">Name<br><a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/allCarriers?page='.$listCarriers->currentPage().'&sortby=name&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/allCarriers?page='.$listCarriers->currentPage().'&sortby=name&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">License Plate<br><a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/allCarriers?page='.$listCarriers->currentPage().'&sortby=licensePlate&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/allCarriers?page='.$listCarriers->currentPage().'&sortby=licensePlate&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">Pallets Account<br><a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/allCarriers?page='.$listCarriers->currentPage().'&sortby=palletsaccount_name&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/allCarriers?page='.$listCarriers->currentPage().'&sortby=palletsaccount_name&order=desc')}}"></a>
                                    </th>
                                    {{--<th class="text-center">Phone<br><a--}}
                                                {{--class="glyphicon glyphicon-chevron-up general-sorting"--}}
                                                {{--href="{{url('/allCarriers?page='.$listCarriers->currentPage().'&sortby=phone&order=asc')}}"></a><a--}}
                                                {{--class="glyphicon glyphicon-chevron-down general-sorting"--}}
                                                {{--href="{{url('/allCarriers?page='.$listCarriers->currentPage().'&sortby=phone&order=desc')}}"></a>--}}
                                    {{--</th>--}}
                                    {{--<th class="text-center">Fax<br><a--}}
                                                {{--class="glyphicon glyphicon-chevron-up general-sorting"--}}
                                                {{--href="{{url('/allCarriers?page='.$listCarriers->currentPage().'&sortby=fax&order=asc')}}"></a><a--}}
                                                {{--class="glyphicon glyphicon-chevron-down general-sorting"--}}
                                                {{--href="{{url('/allCarriers?page='.$listCarriers->currentPage().'&sortby=fax&order=desc')}}"></a>--}}
                                    {{--</th>--}}
                                    {{--<th class="text-center">Email<br><a--}}
                                                {{--class="glyphicon glyphicon-chevron-up general-sorting"--}}
                                                {{--href="{{url('/allCarriers?page='.$listCarriers->currentPage().'&sortby=email&order=asc')}}"></a><a--}}
                                                {{--class="glyphicon glyphicon-chevron-down general-sorting"--}}
                                                {{--href="{{url('/allCarriers?page='.$listCarriers->currentPage().'&sortby=email&order=desc')}}"></a>--}}
                                    {{--</th>--}}
                                    {{--<th class="text-center">Contact Name<br><a--}}
                                                {{--class="glyphicon glyphicon-chevron-up general-sorting"--}}
                                                {{--href="{{url('/allCarriers?page='.$listCarriers->currentPage().'&sortby=namecontact&order=asc')}}"></a><a--}}
                                                {{--class="glyphicon glyphicon-chevron-down general-sorting"--}}
                                                {{--href="{{url('/allCarriers?page='.$listCarriers->currentPage().'&sortby=namecontact&order=desc')}}"></a>--}}
                                    {{--</th>--}}
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($listCarriers as $carriers)
                                    <tr class="text-center">
                                        <td><a href="{{route('showDetailsCarrier',$carriers->id)}}">{{$carriers->id}}</a>
                                        </td>
                                        <td>{{$carriers->name}}</td>
                                        <td>{{$carriers->licensePlate}}</td>
                                        <td><a href="{{route('showDetailsPalletsaccount',$carriers->palletsaccount_name)}}">{{$carriers->palletsaccount_name}}</a>
                                        </td>
                                        {{--<td>{{$warehouse->phone}}</td>--}}
                                        {{--<td>{{$warehouse->fax}}</td>--}}
                                        {{--<td>{{$warehouse->email}}</td>--}}
                                        {{--<td>{{$warehouse->namecontact}}</td>--}}
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="general-pagination text-left">{!! $listCarriers->render() !!}</div>
                            {{--->appends($links)--}}
                            @if ($listCarriers->currentPage()==$listCarriers->lastPage())
                                <div class="general-legend col-lg-offset-8">
                                    Showing @php($legend1=1+ ($listCarriers->currentPage() -1) * 5)  {{$legend1}}
                                    to {{$count}} of {{$count}} results
                                </div>
                            @elseif($listCarriers->isEmpty())
                                <div class="general-legend col-lg-offset-9">
                                    Showing 0 to 0 of 0 results
                                </div>
                            @else
                                <div class="general-legend col-lg-offset-8">
                                    Showing @php($legend1=1+ ($listCarriers->currentPage() -1) * 5)  {{$legend1}}
                                    to @php($legend2= $listCarriers->currentPage() * 5) {{$legend2}} of {{$count}}
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