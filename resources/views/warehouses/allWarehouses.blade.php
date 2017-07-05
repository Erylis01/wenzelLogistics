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
@section('classTrucks')
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
                        <form role="form" method="GET" action="{{route('showAllWarehouses', 'false')}}">
                            {{ csrf_field() }}

                            <!--search bar-->
                            <div class="col-lg-8 input-group searchBar">
                            <span class="input-group-btn searchInput">
                                @if(isset($searchQuery))
                                    <input type="text" class="form-control" name="search" value="{{$searchQuery}}"
                                           placeholder="search">
                                @else
                                    <input type="text" class="form-control" name="search" value=""
                                           placeholder="search">
                                @endif
                            </span>
                                <span class="input-group-btn">
                                    <select class="selectpicker show-tick form-control searchSelect" data-size="5"
                                            data-live-search="true" data-live-search-style="startsWith"
                                            title="columns" name="searchColumns[]" multiple required>
                                      @if((isset($searchColumns)&& in_array('ALL',$searchColumns))||(Illuminate\Support\Facades\Input::old('searchColumns') && in_array('ALL', Illuminate\Support\Facades\Input::old('searchColumns'))))
                                            <option selected>ALL</option>
                                        @else
                                            <option>ALL</option>
                                        @endif
                                        @foreach($listColumns as $column)
                                            @php($list[]=null)
                                            @if(isset($searchColumns))
                                                @foreach($searchColumns as $searchC)
                                                    @if($column==$searchC)
                                                        <option selected>{{$column}}</option>
                                                        @php($list[]=$column)
                                                    @endif
                                                @endforeach
                                                @if(!in_array($column, $list))
                                                    <option>{{$column}}</option>
                                                @endif
                                            @elseif(Illuminate\Support\Facades\Input::old('searchColumns'))
                                                @foreach(old('searchColumns') as $searchC)
                                                    @if($column==$searchC)
                                                        <option selected>{{$column}}</option>
                                                        @php($list[]=$column)
                                                    @endif
                                                @endforeach
                                                @if(!in_array($column, $list))
                                                    <option>{{$column}}</option>
                                                @endif
                                            @else
                                                <option>{{$column}}</option>
                                            @endif
                                        @endforeach
                                        </select>
                                     </span>
                                <span class="input-group-btn">
                                <button class="btn glyphicon glyphicon-search" type="submit"
                                        name="searchSubmit"></button>
                            </span>
                                <span class="col-lg-offset-6">
                                    <a href="{{route('showAllWarehouses', ['refresh'=>'true'])}}" class="btn btn-add"><span
                                                class="glyphicon glyphicon-refresh"></span></a>
                            </span>
                            <span class="col-lg-offset-1">
                                <a href="{{route('showAddWarehouse')}}" class="btn btn-add"><span
                                            class="glyphicon glyphicon-plus-sign"></span> Add</a>
                            </span>

                            </div>
                        </form>
                    </div>

                    <div class="panel-body panel-body-general">
                        <!--msg errors-->
                        @if(Session::has('messageDeleteWarehouse'))
                            <div class="alert alert-success text-alert text-center">{{ Session::get('messageDeleteWarehouse') }}</div>
                        @elseif(Session::has('messageAddWarehouse'))
                            <div class="alert alert-success text-alert text-center">{{ Session::get('messageAddWarehouse') }}</div>
                    @endif

                    <!-- Table -->
                        <div class="table-responsive warehouses-wrapper">
                            <table class="table table-hover table-bordered table-warehouses">
                                <thead>
                                <tr>
                                    @if(isset($searchQuery))
                                        {{--<th class="text-center">ID<br> <a--}}
                                                    {{--class="glyphicon glyphicon-chevron-up general-sorting"--}}
                                                    {{--href="{{url('/allWarehouses?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=id&order=asc')}}"></a><a--}}
                                                    {{--class="glyphicon glyphicon-chevron-down general-sorting"--}}
                                                    {{--href="{{url('/allWarehouses?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=id&order=desc')}}"></a>--}}
                                        {{--</th>--}}
                                        <th class="text-center colName colHeightTitle">Name<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allWarehouses/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=nickname&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allWarehouses/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=nickname&order=desc')}}"></a>
                                        </th>
                                        <th class="colNameb colHeightTitle"></th>
                                        <th class="text-center colAdress colHeightTitle">Adress<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allWarehouses/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=adress&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allWarehouses/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=adress&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colHeightTitle colZipcode">Zip Code<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allWarehouses/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=zipcode&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allWarehouses/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=zipcode&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colHeightTitle colTown">Town<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allWarehouses/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=town&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allWarehouses/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=town&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colHeightTitle colCountry">Country<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allWarehouses/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=country&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/loadings/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=country&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colPhoneFax colHeightTitle">Phone<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allWarehouses/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=phone&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allWarehouses/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=phone&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colPhoneFax colHeightTitle">Fax/Mobile<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allWarehouses/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=fax&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allWarehouses/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=fax&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colHeightTitle colEmail">Email<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allWarehouses/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=email&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allWarehouses/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=email&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colHeightTitle colContact">Contact Infos<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allWarehouses/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=namecontact&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allWarehouses/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=namecontact&order=desc')}}"></a>
                                        </th>
                                    @else
                                        {{--<th class="text-center">ID<br> <a--}}
                                                    {{--class="glyphicon glyphicon-chevron-up general-sorting"--}}
                                                    {{--href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=id&order=asc')}}"></a><a--}}
                                                    {{--class="glyphicon glyphicon-chevron-down general-sorting"--}}
                                                    {{--href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=id&order=desc')}}"></a>--}}
                                        {{--</th>--}}
                                        <th class="text-center colName colHeightTitle">Name<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allWarehouses/'.$refresh.'?page='.$listWarehouses->currentPage().'&sortby=nickname&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allWarehouses/'.$refresh.'?page='.$listWarehouses->currentPage().'&sortby=nickname&order=desc')}}"></a>
                                        </th>
                                        <th class="colNameb colHeightTitle"></th>
                                        <th class="text-center colHeight colAdress">Adress<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allWarehouses/'.$refresh.'?page='.$listWarehouses->currentPage().'&sortby=adress&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allWarehouses/'.$refresh.'?page='.$listWarehouses->currentPage().'&sortby=adress&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colHeightTitle colZipcode">Zip Code<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allWarehouses/'.$refresh.'?page='.$listWarehouses->currentPage().'&sortby=zipcode&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allWarehouses/'.$refresh.'?page='.$listWarehouses->currentPage().'&sortby=zipcode&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colTown colHeightTitle">Town<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allWarehouses/'.$refresh.'?page='.$listWarehouses->currentPage().'&sortby=town&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allWarehouses/'.$refresh.'?page='.$listWarehouses->currentPage().'&sortby=town&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colCountry colHeightTitle">Country<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allWarehouses/'.$refresh.'?page='.$listWarehouses->currentPage().'&sortby=country&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/loadings/'.$refresh.'?page='.$listWarehouses->currentPage().'&sortby=country&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colPhoneFax colHeightTitle">Phone<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allWarehouses/'.$refresh.'?page='.$listWarehouses->currentPage().'&sortby=phone&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allWarehouses/'.$refresh.'?page='.$listWarehouses->currentPage().'&sortby=phone&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colPhoneFax colHeightTitle">Fax/Mobile<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allWarehouses/'.$refresh.'?page='.$listWarehouses->currentPage().'&sortby=fax&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allWarehouses/'.$refresh.'?page='.$listWarehouses->currentPage().'&sortby=fax&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colEmail colHeightTitle">Email<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allWarehouses/'.$refresh.'?page='.$listWarehouses->currentPage().'&sortby=email&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allWarehouses/'.$refresh.'?page='.$listWarehouses->currentPage().'&sortby=email&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colHeightTitle colContact">Contact Infos<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allWarehouses/'.$refresh.'?page='.$listWarehouses->currentPage().'&sortby=namecontact&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allWarehouses/'.$refresh.'?page='.$listWarehouses->currentPage().'&sortby=namecontact&order=desc')}}"></a>
                                        </th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($listWarehouses as $warehouse)
                                    <tr class="text-center">
                                        {{--<td>--}}
                                            {{--<a href="{{route('showDetailsWarehouse',$warehouse->id)}}">{{$warehouse->id}}</a>--}}
                                        {{--</td>--}}
                                        <td class="text-center colHeight colName"><a class="link" href="{{route('showDetailsWarehouse',$warehouse->id)}}">{{$warehouse->nickname}}</a></td>
                                        <td class="colHeight colNameb"></td>
                                        <td class="text-center colHeight colAdress">{{$warehouse->adress}}</td>
                                        <td class="text-center colHeight colZipcode">{{$warehouse->zipcode}}</td>
                                        <td class="text-center colHeight colTown">{{$warehouse->town}}</td>
                                        <td class="text-center colHeight colCountry">{{$warehouse->country}}</td>
                                        <td class="text-center colHeight colPhoneFax">{{$warehouse->phone}}</td>
                                        <td class="text-center colHeight colPhoneFax">{{$warehouse->fax}}</td>
                                        <td class="text-center colHeight colEmail">{{$warehouse->email}}</td>
                                        <td class="text-center colHeight colContact">{{$warehouse->namecontact}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="general-pagination text-left">{!! $listWarehouses->render() !!}</div>
                            @if ($listWarehouses->currentPage()==$listWarehouses->lastPage())
                                <div class="general-legend col-lg-offset-8">
                                    Showing @php($legend1=1+ ($listWarehouses->currentPage() -1) * 10)  {{$legend1}}
                                    to {{$count}} of {{$count}} results
                                </div>
                            @elseif($listWarehouses->isEmpty())
                                <div class="general-legend col-lg-offset-9">
                                    Showing 0 to 0 of 0 results
                                </div>
                            @else
                                <div class="general-legend col-lg-offset-8">
                                    Showing @php($legend1=1+ ($listWarehouses->currentPage() -1) * 10)  {{$legend1}}
                                    to @php($legend2= $listWarehouses->currentPage() * 10) {{$legend2}} of {{$count}}
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