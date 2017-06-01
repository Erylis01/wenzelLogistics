@extends('layouts.default')

@section('title')
    All pallets transfers
@endsection

@section('stylesheet')
    <link href="{{asset('css/palletstransfers.css')}}" rel="stylesheet" type="text/css">
@endsection

@section('classLoadings')
    class="nonActive"
@endsection
@section('classWarehouses')
    class="nonActive"
@endsection
@section('classPalletsAccounts')
    class="nonActive"
@endsection
@section('classPalletsTransfers')
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
            <div class="col-lg-14">
                <div class="panel panel-general">
                    <div class="panel-heading">List of all pallets transfers <span class="col-lg-offset-7">
                            <a href="{{route('showAddPalletstransfer')}}" class="btn btn-add"><span class="glyphicon glyphicon-plus-sign"></span> Add transfers</a>
                        </span></div>

                    <div class="panel-body panel-body-general">
                        @if(Session::has('messageDeletePalletstransfer'))
                            <div class="alert alert-success text-alert text-center">{{ Session::get('messageDeletePalletstransfer') }}</div>
                        @elseif(Session::has('messageAddPalletstransfer'))
                            <div class="alert alert-success text-alert text-center">{{ Session::get('messageAddPalletstransfer') }}</div>
                    @endif



                    <!-- Table -->
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead>
                                <tr>
                                    <th class="text-center">ID <a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=id&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=id&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">Date <a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=date&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=date&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">Loading Atrnr <a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=loading_atrnr&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=loading_atrnr&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">Pallets Account <a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=palletsAccount&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=palletsAccount&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">Pallets Number <a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=palletsNumber&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=palletsNumber&order=desc')}}"></a>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($listPalletstransfers as $transfer)
                                    {{--@php(dd(\App\Palletstransfer::find($transfer->id)->with('loading')->first()->loading))--}}

                                    @if($transfer->state==true && $transfer->documents==true && $transfer->realPalletsNumber==$transfer->palletsNumber)
                                        @php($class="success")
                                    @elseif ($transfer->state==false && $transfer->documents==false && $transfer->realPalletsNumber<>$transfer->palletsNumber)
                                        @php($class="danger")
                                    @else
                                        @php ($class="warning")
                                    @endif
                                    <tr class={{$class}}>
                                        <td class="text-center"><a href="{{route('showDetailsPalletstransfer',$transfer->id)}}">{{$transfer->id}}</a>
                                        </td>
                                        {{--<td class="text-center colID">{{$transfer->id}}</td>--}}
                                        <td class="text-center colDate">{{date('d-m-Y', strtotime($transfer->date))}}</td>
                                        <td class="text-center colRef">{{$transfer->loading_atrnr}}</td>
                                        <td class="text-center colAccount">{{$transfer->palletsAccount}}</td>
                                        <td class="text-center colNumber">{{$transfer->palletsNumber}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="general-pagination text-left">{!! $listPalletstransfers->render() !!}</div>

                            @if ($listPalletstransfers->currentPage()==$listPalletstransfers->lastPage())
                                <div class="general-legend col-lg-offset-8">
                                    Showing @php($legend1=1+ ($listPalletstransfers->currentPage() -1) * 5)  {{$legend1}}
                                    to {{$count}} of {{$count}} results
                                </div>
                            @elseif($listPalletstransfers->isEmpty())
                                <div class="general-legend col-lg-offset-9">
                                    Showing 0 to 0 of 0 results
                                </div>
                            @else
                                <div class="general-legend col-lg-offset-8">
                                    Showing @php($legend1=1+ ($listPalletstransfers->currentPage() -1) * 5)  {{$legend1}}
                                    to @php($legend2= $listPalletstransfers->currentPage() * 5) {{$legend2}} of {{$count}}
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