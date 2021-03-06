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
    nonActive
@endsection
@section('classPalletsAccounts')
    nonActive
@endsection
@section('classPalletsTransfers')
    nonActive
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
                        <div class="col-lg-3">All warehouses</div>
                        <div class="col-lg-4">
                            <form class="form-horizontal" role="form" method="GET" action="{{route('showAllWarehouses')}}">
                            {{ csrf_field() }}
                            <!--search bar-->
                                <div class="form-group col-lg-8">
                                    <input type="text" class="form-control" name="search"
                                           @if(isset($searchQuery)) value="{{$searchQuery}}" @else value=""
                                           @endif placeholder="search"/>
                                </div>
                                <div class="form-group col-lg-3">
                                    <select class="selectpicker show-tick form-control searchSelect" data-size="5"
                                            data-live-search="true" data-live-search-style="startsWith"
                                            title="columns" name="searchColumns[]" multiple required>
                                        @if(!isset($searchQuery) ||(isset($searchColumns)&& in_array('ALL',$searchColumns))||(Illuminate\Support\Facades\Input::old('searchColumns') && in_array('ALL', Illuminate\Support\Facades\Input::old('searchColumns'))))
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
                                </div>
                                <div class="form-group col-lg-1">
                                    <button class="btn" type="submit" name="searchSubmit"><span
                                                class="glyphicon glyphicon-search"></span></button>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-4">
                            <form class="form-horizontal" role="form" method="POST" action="{{route('uploadImportWarehouses')}}" id="uploadImportWarehousesForm" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <input type="hidden" name="actionForm" id="actionForm" />
                                <div class="col-lg-11 form-group upload">
                                    <input type="file" name="documentsWarehouses" id="documentsWarehouses"/>
                                </div>
                                <div class="form-group col-lg-1">
                                    <button type="submit" class="glyphicon glyphicon-refresh btn btn-add" value="uploadImportWarehouse" id="uploadImportWarehouse" name="uploadImportWarehouse" onclick="formUploadSubmitBlock(this);"></button>
                                </div>
                            </form>
                        </div>

                            <div>
                                <a href="{{route('showAddWarehouse', ['originalPage'=>'allWarehouses'])}}" class="btn btn-add"><span
                                            class="glyphicon glyphicon-plus-sign"></span> Add</a>
                            </div>

                    </div>

                    <div class="panel-body panel-body-general">
                        <!--msg errors-->
                        @if(Session::has('messageDeleteWarehouse'))
                            <div class="alert alert-success text-alert text-center">{{ Session::get('messageDeleteWarehouse') }}</div>
                        @elseif(Session::has('messageAddWarehouse'))
                            <div class="alert alert-success text-alert text-center">{{ Session::get('messageAddWarehouse') }}</div>
                        @elseif(Session::has('messageErrorUpload'))
                            <div class="alert alert-danger text-alert text-center">{{ Session::get('messageErrorUpload') }}</div>
                        @elseif(Session::has('messageErrorImport'))
                            <div class="alert alert-danger text-alert text-center">{{ Session::get('messageErrorImport') }}</div>
                    @endif

                    <!-- Table -->
                        <div class="table-responsive warehouses-wrapper">
                            <table class="table table-hover table-bordered table-warehouses">
                                <thead>
                                <tr>
                                        {{--<th class="text-center">ID<br> <a--}}
                                                    {{--class="glyphicon glyphicon-chevron-up general-sorting"--}}
                                                    {{--href="{{url('/allWarehouses?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=id&order=asc')}}"></a><a--}}
                                                    {{--class="glyphicon glyphicon-chevron-down general-sorting"--}}
                                                    {{--href="{{url('/allWarehouses?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=id&order=desc')}}"></a>--}}
                                        {{--</th>--}}
                                        <th class="text-center colName colHeightTitle">Name<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                                    @if(isset($searchQuery)) href="{{url('/allWarehouses?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=nickname&order=asc')}}"
                                                    @else href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=nickname&order=asc')}}"
                                                    @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                                    @if(isset($searchQuery)) href="{{url('/allWarehouses?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=nickname&order=desc')}}"
                                                    @else href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=nickname&order=desc')}}"
                                                    @endif></a>
                                        </th>
                                        <th class="colNameb colHeightTitle"></th>
                                    <th class="text-center col1 colHeightTitle"><br>
                                        <a class="glyphicon glyphicon-chevron-up general-sorting"
                                           @if(isset($searchQuery)) href="{{url('/allWarehouses?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=activated&order=asc')}}"
                                           @else href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=activated&order=asc')}}"
                                                @endif></a>
                                        <a class="glyphicon glyphicon-chevron-down general-sorting"
                                           @if(isset($searchQuery)) href="{{url('/allWarehouses?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=activated&order=desc')}}"
                                           @else href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=activated&order=desc')}}"
                                                @endif></a>
                                    </th>
                                        <th class="text-center colAdress colHeightTitle">Adress<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/allWarehouses?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=adress&order=asc')}}"
                                               @else href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=adress&order=asc')}}"
                                                    @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/allWarehouses?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=adress&order=desc')}}"
                                               @else href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=adress&order=desc')}}"
                                                    @endif></a>
                                           </th>
                                        <th class="text-center colHeightTitle colZipcode">Zip Code<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/allWarehouses?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=zipcode&order=asc')}}"
                                               @else href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=zipcode&order=asc')}}"
                                                    @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/allWarehouses?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=zipcode&order=desc')}}"
                                               @else href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=zipcode&order=desc')}}"
                                                    @endif></a>
                                            </th>
                                        <th class="text-center colHeightTitle colTown">Town<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/allWarehouses?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=town&order=asc')}}"
                                               @else href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=town&order=asc')}}"
                                                    @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/allWarehouses?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=town&order=desc')}}"
                                               @else href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=town&order=desc')}}"
                                                    @endif></a>
                                            </th>
                                        <th class="text-center colHeightTitle colCountry">Country<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/allWarehouses?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=country&order=asc')}}"
                                               @else href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=country&order=asc')}}"
                                                    @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/allWarehouses?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=country&order=desc')}}"
                                               @else href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=country&order=desc')}}"
                                                    @endif></a>
                                            </th>
                                        <th class="text-center colPhoneFax colHeightTitle">Phone<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/allWarehouses?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=phone&order=asc')}}"
                                               @else href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=phone&order=asc')}}"
                                                    @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/allWarehouses?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=phone&order=desc')}}"
                                               @else href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=phone&order=desc')}}"
                                                    @endif></a>
                                            </th>
                                        <th class="text-center colPhoneFax colHeightTitle">Fax/Mobile<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/allWarehouses?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=fax&order=asc')}}"
                                               @else href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=fax&order=asc')}}"
                                                    @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/allWarehouses?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=fax&order=desc')}}"
                                               @else href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=fax&order=desc')}}"
                                                    @endif></a>
                                            </th>
                                        <th class="text-center colHeightTitle colEmail">Email<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/allWarehouses?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=email&order=asc')}}"
                                               @else href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=email&order=asc')}}"
                                                    @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/allWarehouses?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=email&order=desc')}}"
                                               @else href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=email&order=desc')}}"
                                                    @endif></a>
                                           </th>
                                        <th class="text-center colHeightTitle colContact">Details<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/allWarehouses/?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=details&order=asc')}}"
                                               @else href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=details&order=asc')}}"
                                                    @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/allWarehouses?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listWarehouses->currentPage().'&sortby=details&order=desc')}}"
                                               @else href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=details&order=desc')}}"
                                                    @endif></a>
                                            </th>
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
                                        <td class="col1 colHeight"><span @if($warehouse->activated==1) class="glyphicon glyphicon-eye-open" @elseif($warehouse->activated==0) class="glyphicon glyphicon-eye-close" @endif></span></td>
                                        <td class="text-center colHeight colAdress">{{$warehouse->adress}}</td>
                                        <td class="text-center colHeight colZipcode">{{$warehouse->zipcode}}</td>
                                        <td class="text-center colHeight colTown">{{$warehouse->town}}</td>
                                        <td class="text-center colHeight colCountry">{{$warehouse->country}}</td>
                                        <td class="text-center colHeight colPhoneFax">{{$warehouse->phone}}</td>
                                        <td class="text-center colHeight colPhoneFax">{{$warehouse->fax}}</td>
                                        <td class="text-center colHeight colEmail">{{$warehouse->email}}</td>
                                        <td class="text-center colHeight colContact">{{$warehouse->details}}</td>
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