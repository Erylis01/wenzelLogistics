@extends('layouts.default')

@section('title')
    All warehouses
@endsection

@section('stylesheet')
    <link href="{{asset('css/warehouses.css')}}" rel="stylesheet" type="text/css">
@endsection

@section('classLoadings')
    class="nonActive"
@endsection
@section('classWarehouses')
    class="active"
@endsection
@section('classCarriers')
    class="nonActive"
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
                <div class="panel panel-general panel-warehouses">
                    <div class="panel-heading">
                        <div class="col-lg-4">List of all warehouses
                        </div>
                        <form role="form" class="form-inline" method="GET" action="{{route('showAllWarehouses')}}">
                            {{ csrf_field() }}
                            <div class="col-lg-4 input-group">
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
                                <a href="{{route('showAddWarehouse')}}" class="btn btn-add"><span
                                            class="glyphicon glyphicon-plus-sign"></span> Add warehouse</a>
                            </div>
                        </form>
                    </div>

                    <div class="panel-body panel-body-general">

                        @if(Session::has('messageDeleteWarehouse'))
                            <div class="alert alert-success text-alert text-center">{{ Session::get('messageDeleteWarehouse') }}</div>
                        @elseif(Session::has('messageAddWarehouse'))
                            <div class="alert alert-success text-alert text-center">{{ Session::get('messageAddWarehouse') }}</div>
                    @endif

                    <!-- Table -->
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead>
                                <tr>
                                    @if(isset($searchQuery))
                                        <th class="text-center">ID<br> <a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allWarehouses?search='.$searchQuery.'&page='.$listWarehouses->currentPage().'&sortby=id&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allWarehouses?search='.$searchQuery.'&page='.$listWarehouses->currentPage().'&sortby=id&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center">Name<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allWarehouses?search='.$searchQuery.'&page='.$listWarehouses->currentPage().'&sortby=name&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allWarehouses?search='.$searchQuery.'&page='.$listWarehouses->currentPage().'&sortby=name&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center">Adress<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allWarehouses?search='.$searchQuery.'&page='.$listWarehouses->currentPage().'&sortby=adress&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allWarehouses?search='.$searchQuery.'&page='.$listWarehouses->currentPage().'&sortby=adress&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center">Zip Code<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allWarehouses?search='.$searchQuery.'&page='.$listWarehouses->currentPage().'&sortby=zipcode&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allWarehouses?search='.$searchQuery.'&page='.$listWarehouses->currentPage().'&sortby=zipcode&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center">Town<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allWarehouses?search='.$searchQuery.'&page='.$listWarehouses->currentPage().'&sortby=town&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allWarehouses?search='.$searchQuery.'&page='.$listWarehouses->currentPage().'&sortby=town&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center">Country<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allWarehouses?search='.$searchQuery.'&page='.$listWarehouses->currentPage().'&sortby=country&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/loadings?search='.$searchQuery.'&page='.$listWarehouses->currentPage().'&sortby=country&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center">Phone<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allWarehouses?search='.$searchQuery.'&page='.$listWarehouses->currentPage().'&sortby=phone&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allWarehouses?search='.$searchQuery.'&page='.$listWarehouses->currentPage().'&sortby=phone&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center">Fax<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allWarehouses?search='.$searchQuery.'&page='.$listWarehouses->currentPage().'&sortby=fax&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allWarehouses?search='.$searchQuery.'&page='.$listWarehouses->currentPage().'&sortby=fax&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center">Email<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allWarehouses?search='.$searchQuery.'&page='.$listWarehouses->currentPage().'&sortby=email&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allWarehouses?search='.$searchQuery.'&page='.$listWarehouses->currentPage().'&sortby=email&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center">Contact Name<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allWarehouses?search='.$searchQuery.'&page='.$listWarehouses->currentPage().'&sortby=namecontact&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allWarehouses?search='.$searchQuery.'&page='.$listWarehouses->currentPage().'&sortby=namecontact&order=desc')}}"></a>
                                        </th>
                                    @else
                                        <th class="text-center">ID<br> <a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=id&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=id&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center">Name<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=name&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=name&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center">Adress<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=adress&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=adress&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center">Zip Code<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=zipcode&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=zipcode&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center">Town<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=town&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=town&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center">Country<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=country&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/loadings?page='.$listWarehouses->currentPage().'&sortby=country&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center">Phone<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=phone&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=phone&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center">Fax<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=fax&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=fax&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center">Email<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=email&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=email&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center">Contact Name<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=namecontact&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=namecontact&order=desc')}}"></a>
                                        </th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($listWarehouses as $warehouse)
                                    <tr class="text-center">
                                        <td>
                                            <a href="{{route('showDetailsWarehouse',$warehouse->id)}}">{{$warehouse->id}}</a>
                                        </td>
                                        {{--<td>{{$warehouse->id}}</td>--}}
                                        <td>{{$warehouse->name}}</td>
                                        <td>{{$warehouse->adress}}</td>
                                        <td>{{$warehouse->zipcode}}</td>
                                        <td>{{$warehouse->town}}</td>
                                        <td>{{$warehouse->country}}</td>
                                        <td>{{$warehouse->phone}}</td>
                                        <td>{{$warehouse->fax}}</td>
                                        <td>{{$warehouse->email}}</td>
                                        <td>{{$warehouse->namecontact}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="general-pagination text-left">{!! $listWarehouses->render() !!}</div>
                            {{--->appends($links)--}}
                            @if ($listWarehouses->currentPage()==$listWarehouses->lastPage())
                                <div class="general-legend col-lg-offset-8">
                                    Showing @php($legend1=1+ ($listWarehouses->currentPage() -1) * 5)  {{$legend1}}
                                    to {{$count}} of {{$count}} results
                                </div>
                            @elseif($listWarehouses->isEmpty())
                                <div class="general-legend col-lg-offset-9">
                                    Showing 0 to 0 of 0 results
                                </div>
                            @else
                                <div class="general-legend col-lg-offset-8">
                                    Showing @php($legend1=1+ ($listWarehouses->currentPage() -1) * 5)  {{$legend1}}
                                    to @php($legend2= $listWarehouses->currentPage() * 5) {{$legend2}} of {{$count}}
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