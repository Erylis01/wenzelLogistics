@extends('layouts.default')

@section('title')
    All trucks
@endsection

@section('stylesheet')
    <link href="{{asset('css/trucks.css')}}" rel="stylesheet" type="text/css">
@endsection

@section('classLoadings')
    class="nonActive"
@endsection
@section('classWarehouses')
    class="nonActive"
@endsection
@section('classTrucks')
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
                <div class="panel panel-general panel-trucks">
                    <div class="panel-heading"><div class="col-lg-4">List of all trucks
                        </div>
                        <form role="form" class="searchBar form-inline" method="GET" action="{{route('showAllTrucks')}}">
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
                                <a href="{{route('showAddTruck')}}" class="btn btn-add"><span
                                            class="glyphicon glyphicon-plus-sign"></span> Add truck</a>
                            </div>
                        </form>
                    </div>
                    <div class="panel-body panel-body-general">

                        @if(Session::has('messageDeleteTruck'))
                            <div class="alert alert-success text-alert text-center">{{ Session::get('messageDeleteTruck') }}</div>
                        @elseif(Session::has('messageAddTruck'))
                            <div class="alert alert-success text-alert text-center">{{ Session::get('messageAddTruck') }}</div>
                    @endif

                    <!-- Table -->
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-trucks">
                                <thead>
                                <tr>
                                    @if(isset($searchQuery))
                                        <th class="text-center colID">ID<br> <a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allTrucks?search='.$searchQuery.'&page='.$listTrucks->currentPage().'&sortby=id&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allTrucks?search='.$searchQuery.'&page='.$listTrucks->currentPage().'&sortby=id&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colName">Name<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allTrucks?search='.$searchQuery.'&page='.$listTrucks->currentPage().'&sortby=name&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allTrucks?search='.$searchQuery.'&page='.$listTrucks->currentPage().'&sortby=name&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colLicense">License Plate<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allTrucks?search='.$searchQuery.'&page='.$listTrucks->currentPage().'&sortby=licensePlate&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allTrucks?search='.$searchQuery.'&page='.$listTrucks->currentPage().'&sortby=licensePlate&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center">Adress<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allTrucks?search='.$searchQuery.'&page='.$listTrucks->currentPage().'&sortby=adress&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allTrucks?search='.$searchQuery.'&page='.$listTrucks->currentPage().'&sortby=adress&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colName">Pallets Account<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allTrucks?search='.$searchQuery.'&page='.$listTrucks->currentPage().'&sortby=palletsaccount_name&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allTrucks?search='.$searchQuery.'&page='.$listTrucks->currentPage().'&sortby=palletsaccount_name&order=desc')}}"></a>
                                        </th>
                                        @else
                                    <th class="text-center colID">ID<br> <a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/allTrucks?page='.$listTrucks->currentPage().'&sortby=id&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/allTrucks?page='.$listTrucks->currentPage().'&sortby=id&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center colName">Name<br><a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/allTrucks?page='.$listTrucks->currentPage().'&sortby=name&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/allTrucks?page='.$listTrucks->currentPage().'&sortby=name&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center colLicense">License Plate<br><a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/allTrucks?page='.$listTrucks->currentPage().'&sortby=licensePlate&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/allTrucks?page='.$listTrucks->currentPage().'&sortby=licensePlate&order=desc')}}"></a>
                                    </th>
                                        <th class="text-center">Adress<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allTrucks?page='.$listTrucks->currentPage().'&sortby=adress&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allTrucks?page='.$listTrucks->currentPage().'&sortby=adress&order=desc')}}"></a>
                                        </th>
                                    <th class="text-center colName">Pallets Account<br><a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/allTrucks?page='.$listTrucks->currentPage().'&sortby=palletsaccount_name&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/allTrucks?page='.$listTrucks->currentPage().'&sortby=palletsaccount_name&order=desc')}}"></a>
                                    </th>
                                        @endif
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($listTrucks as $trucks)
                                    <tr class="text-center">
                                        <td><a class="link" href="{{route('showDetailsTruck',$trucks->id)}}">{{$trucks->id}}</a>
                                        </td>
                                        <td>{{$trucks->name}}</td>
                                        <td>{{$trucks->licensePlate}}</td>
                                        <td>{{$trucks->adress}}</td>
                                        <td><a class="link" href="{{route('showDetailsPalletsaccount',$trucks->palletsaccount_name)}}">{{$trucks->palletsaccount_name}}</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="general-pagination text-left">{!! $listTrucks->render() !!}</div>
                            @if ($listTrucks->currentPage()==$listTrucks->lastPage())
                                <div class="general-legend col-lg-offset-8">
                                    Showing @php($legend1=1+ ($listTrucks->currentPage() -1) * 5)  {{$legend1}}
                                    to {{$count}} of {{$count}} results
                                </div>
                            @elseif($listTrucks->isEmpty())
                                <div class="general-legend col-lg-offset-9">
                                    Showing 0 to 0 of 0 results
                                </div>
                            @else
                                <div class="general-legend col-lg-offset-8">
                                    Showing @php($legend1=1+ ($listTrucks->currentPage() -1) * 5)  {{$legend1}}
                                    to @php($legend2= $listTrucks->currentPage() * 5) {{$legend2}} of {{$count}}
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