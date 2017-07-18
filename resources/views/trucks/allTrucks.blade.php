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
    nonActive
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
                    <div class="panel-heading">
                        <div class="col-lg-4">List of all trucks
                        </div>
                        <form role="form" method="GET" action="{{route('showAllTrucks', ['refresh'=>'false'])}}">
                            {{ csrf_field() }}
                            <div class="col-lg-8 input-group searchBar">
                            <span class="input-group-btn searchInput">
                                <input type="text" class="form-control" name="search" @if(isset($searchQuery)) value="{{$searchQuery}}" @else value="" @endif placeholder="search">
                            </span>
                                <span class="input-group-btn">
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
                                     </span>
                                <span class="input-group-btn">
                                <button class="btn glyphicon glyphicon-search" type="submit"
                                        name="searchSubmit"></button>
                            </span>
                                <span class="col-lg-offset-4">
                                    <a href="{{route('showAllTrucks', ['refresh'=>'true'])}}" class="btn btn-add"><span
                                                class="glyphicon glyphicon-refresh"></span></a>
                            </span>
                                <span class="col-lg-offset-1">
                                <a href="{{route('showAddTruck', ['originalPage'=>'allTrucks'])}}" class="btn btn-add"><span
                                            class="glyphicon glyphicon-plus-sign"></span> Truck</a>
                            </span>
                            </div>
                        </form>
                    </div>
                    <div class="panel-body panel-body-general">
                        @if(Session::has('messageDeleteTruck'))
                            <p class="alert alert-success text-alert text-center">{{ Session::get('messageDeleteTruck') }}</p>
                        @elseif(Session::has('messageAddTruck'))
                            <p class="alert alert-success text-alert text-center">{{ Session::get('messageAddTruck') }}</p>
                    @endif

                    <!-- Table -->
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-trucks">
                                <thead>
                                <tr>
                                    <th class="text-center colID">ID<br>
                                        <a class="glyphicon glyphicon-chevron-up general-sorting"
                                           @if(isset($searchQuery)) href="{{url('/allTrucks/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listTrucks->currentPage().'&sortby=id&order=asc')}}"
                                           @else href="{{url('/allTrucks/'.$refresh.'?page='.$listTrucks->currentPage().'&sortby=id&order=asc')}}"
                                                @endif></a>
                                        <a class="glyphicon glyphicon-chevron-down general-sorting"
                                           @if(isset($searchQuery)) href="{{url('/allTrucks/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listTrucks->currentPage().'&sortby=id&order=desc')}}"
                                           @else href="{{url('/allTrucks/'.$refresh.'?page='.$listTrucks->currentPage().'&sortby=id&order=desc')}}"
                                                @endif></a>
                                    </th>
                                    <th class="text-center colName">Name<br>
                                        <a class="glyphicon glyphicon-chevron-up general-sorting"
                                           @if(isset($searchQuery)) href="{{url('/allTrucks/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listTrucks->currentPage().'&sortby=name&order=asc')}}"
                                           @else href="{{url('/allTrucks/'.$refresh.'?page='.$listTrucks->currentPage().'&sortby=name&order=asc')}}"
                                                @endif></a>
                                        <a class="glyphicon glyphicon-chevron-down general-sorting"
                                           @if(isset($searchQuery)) href="{{url('/allTrucks/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listTrucks->currentPage().'&sortby=name&order=desc')}}"
                                           @else href="{{url('/allTrucks/'.$refresh.'?page='.$listTrucks->currentPage().'&sortby=name&order=desc')}}"
                                                @endif></a>
                                    </th>
                                    <th class="text-center colLicense">License Plate<br>
                                        <a class="glyphicon glyphicon-chevron-up general-sorting"
                                           @if(isset($searchQuery)) href="{{url('/allTrucks/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listTrucks->currentPage().'&sortby=licensePlate&order=asc')}}"
                                           @else href="{{url('/allTrucks/'.$refresh.'?page='.$listTrucks->currentPage().'&sortby=licensePlate&order=asc')}}"
                                                @endif></a>
                                        <a class="glyphicon glyphicon-chevron-down general-sorting"
                                           @if(isset($searchQuery)) href="{{url('/allTrucks/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listTrucks->currentPage().'&sortby=licensePlate&order=desc')}}"
                                           @else href="{{url('/allTrucks/'.$refresh.'?page='.$listTrucks->currentPage().'&sortby=licensePlate&order=desc')}}"
                                                @endif></a>
                                    </th>
                                    <th class="text-center colNumber">Confirmed<br>
                                        <a class="glyphicon glyphicon-chevron-up general-sorting"
                                           @if(isset($searchQuery)) href="{{url('/allTrucks/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listTrucks->currentPage().'&sortby=realNumberPallets&order=asc')}}"
                                           @else href="{{url('/allTrucks/'.$refresh.'?page='.$listTrucks->currentPage().'&sortby=realNumberPallets&order=asc')}}"
                                                @endif></a>
                                        <a class="glyphicon glyphicon-chevron-down general-sorting"
                                           @if(isset($searchQuery)) href="{{url('/allTrucks/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listTrucks->currentPage().'&sortby=realNumberPallets&order=desc')}}"
                                           @else href="{{url('/allTrucks/'.$refresh.'?page='.$listTrucks->currentPage().'&sortby=realNumberPallets&order=desc')}}"
                                                @endif></a>
                                    </th>
                                    <th class="text-center colNumber">Planned<br>
                                        <a class="glyphicon glyphicon-chevron-up general-sorting"
                                           @if(isset($searchQuery)) href="{{url('/allTrucks/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listTrucks->currentPage().'&sortby=theoricalNumberPallets&order=asc')}}"
                                           @else href="{{url('/allTrucks/'.$refresh.'?page='.$listTrucks->currentPage().'&sortby=theoricalNumberPallets&order=asc')}}"
                                                @endif></a>
                                        <a class="glyphicon glyphicon-chevron-down general-sorting"
                                           @if(isset($searchQuery)) href="{{url('/allTrucks/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listTrucks->currentPage().'&sortby=theoricalNumberPallets&order=desc')}}"
                                           @else href="{{url('/allTrucks/'.$refresh.'?page='.$listTrucks->currentPage().'&sortby=theoricalNumberPallets&order=desc')}}"
                                                @endif></a>
                                    </th>
                                    <th class="text-center colNumber">Rest<br><br></th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($listTrucks as $trucks)
                                    <tr class="text-center">
                                        <td class="colID"><a class="link"
                                                             href="{{route('showDetailsTruck',$trucks->id)}}">{{$trucks->id}}</a>
                                        </td>
                                        <td class="colName"><a class="link"
                                                               href="{{route('showDetailsPalletsaccount',\App\Palletsaccount::where('name',$trucks->palletsaccount_name)->first()->id)}}">{{$trucks->name}}</a>
                                        </td>
                                        <td class="colLicense">{{$trucks->licensePlate}}</td>
                                        <td class="colNumber">{{$trucks->realNumberPallets}}</td>
                                        <td class="colNumber">{{$trucks->theoricalNumberPallets}}</td>
                                        <td class="colNumber">{{$trucks->theoricalNumberPallets - $trucks->realNumberPallets}}</td>
                                        <td class="colDanger">
                                            @php($listPalletstransfers=\App\Palletstransfer::where('creditAccount','LIKE', $trucks->name.'-'.$trucks->licensePlate.'%')->orWhere('debitAccount','LIKE', $trucks->name.'-'.$trucks->licensePlate.'%')->get())
                                            @php($k=0)
                                            @foreach($listPalletstransfers as $transfer)
                                                @php($errorsTransfer= \App\Http\Controllers\PalletstransfersController::actualErrors($transfer))
                                                @foreach($errorsTransfer as $errorT)
                                                    @if(!empty($errorT)&& $k<2)
                                                        <span class="glyphicon glyphicon-warning-sign text-danger"
                                                              data-toggle="tooltip" title="{{$errorT->name}}"></span>
                                                    @elseif(!empty($errorT)&& $k==2)
                                                        <span class="text-danger">...</span>
                                                    @endif
                                                    @php($k=$k+1)
                                                @endforeach
                                            @endforeach
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
                                    Showing @php($legend1=1+ ($listTrucks->currentPage() -1) * 20)  {{$legend1}}
                                    to {{$count}} of {{$count}} results
                                </div>
                            @elseif($listTrucks->isEmpty())
                                <div class="general-legend col-lg-offset-9">
                                    Showing 0 to 0 of 0 results
                                </div>
                            @else
                                <div class="general-legend col-lg-offset-8">
                                    Showing @php($legend1=1+ ($listTrucks->currentPage() -1) * 20)  {{$legend1}}
                                    to @php($legend2= $listTrucks->currentPage() * 20) {{$legend2}} of {{$count}}
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