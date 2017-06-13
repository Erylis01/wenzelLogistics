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
                    <div class="panel-heading"><div class="col-lg-4">List of all carriers
                        </div>
                        <form role="form" class="searchBar form-inline" method="GET" action="{{route('showAllCarriers')}}">
                            {{ csrf_field() }}
                            <div class="searchBar col-lg-4 input-group">
                                @if(isset($searchQuery))
                                    <input type="text" class="form-control" name="search" value="{{$searchQuery}}"
                                           placeholder="search"/>
                                @else
                                    <input type="text" class="form-control" name="search" value=""
                                           placeholder="search"/>
                                @endif
                                <span class="input-group-btn">
                                    <select class="col-lg-8 selectpicker show-tick input-group" data-size="5"
                                            data-live-search="true" data-live-search-style="startsWith"
                                            title="columns" name="searchColumn">
                                      @if(!isset($searchColumn)||!Illuminate\Support\Facades\Input::old('searchColumn'))
                                            <option selected>all</option>
                                        @else
                                            <option>all</option>
                                        @endif
                                        @foreach($listColumns as $column )
                                            @if(Illuminate\Support\Facades\Input::old('searchColumn') && $column==old('searchColumn'))
                                                <option selected>{{$column}}</option>
                                            @elseif(isset($searchColumn)&& $column==$searchColumn)
                                                <option selected>{{$column}}</option>
                                            @else
                                                <option>{{$column}}</option>
                                            @endif
                                        @endforeach
                                        </select>
                                <button class="btn glyphicon glyphicon-search" type="submit"
                                        name="searchSubmit"></button>
                            </span>
                            </div>
                            <div class="col-lg-1 col-lg-offset-2 input-group">
                                <a href="{{route('showAddCarrier')}}" class="btn btn-add"><span
                                            class="glyphicon glyphicon-plus-sign"></span> Add carrier</a>
                            </div>
                        </form>
                    </div>
                    <div class="panel-body panel-body-general">

                        @if(Session::has('messageDeleteCarrier'))
                            <div class="alert alert-success text-alert text-center">{{ Session::get('messageDeleteCarrier') }}</div>
                        @elseif(Session::has('messageAddCarrier'))
                            <div class="alert alert-success text-alert text-center">{{ Session::get('messageAddCarrier') }}</div>
                    @endif

                    <!-- Table -->
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-carriers">
                                <thead>
                                <tr>
                                    @if(isset($searchQuery))
                                        <th class="text-center colID">ID<br> <a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allCarriers?search='.$searchQuery.'&page='.$listCarriers->currentPage().'&sortby=id&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allCarriers?search='.$searchQuery.'&page='.$listCarriers->currentPage().'&sortby=id&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colName">Name<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allCarriers?search='.$searchQuery.'&page='.$listCarriers->currentPage().'&sortby=name&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allCarriers?search='.$searchQuery.'&page='.$listCarriers->currentPage().'&sortby=name&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colLicense">License Plate<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allCarriers?search='.$searchQuery.'&page='.$listCarriers->currentPage().'&sortby=licensePlate&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allCarriers?search='.$searchQuery.'&page='.$listCarriers->currentPage().'&sortby=licensePlate&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center">Pallets Account<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allCarriers?search='.$searchQuery.'&page='.$listCarriers->currentPage().'&sortby=palletsaccount_name&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allCarriers?search='.$searchQuery.'&page='.$listCarriers->currentPage().'&sortby=palletsaccount_name&order=desc')}}"></a>
                                        </th>
                                        @else
                                    <th class="text-center colID">ID<br> <a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/allCarriers?page='.$listCarriers->currentPage().'&sortby=id&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/allCarriers?page='.$listCarriers->currentPage().'&sortby=id&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center colName">Name<br><a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/allCarriers?page='.$listCarriers->currentPage().'&sortby=name&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/allCarriers?page='.$listCarriers->currentPage().'&sortby=name&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center colLicense">License Plate<br><a
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
                                        @endif
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($listCarriers as $carriers)
                                    <tr class="text-center">
                                        <td><a class="link" href="{{route('showDetailsCarrier',$carriers->id)}}">{{$carriers->id}}</a>
                                        </td>
                                        <td>{{$carriers->name}}</td>
                                        <td>{{$carriers->licensePlate}}</td>
                                        <td><a class="link" href="{{route('showDetailsPalletsaccount',$carriers->palletsaccount_name)}}">{{$carriers->palletsaccount_name}}</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="general-pagination text-left">{!! $listCarriers->render() !!}</div>
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