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
    active
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
                <div class="panel panel-general panel-trucks">
                        <div class="panel-heading">
                            <form class="form-horizontal" role="form" method="GET"
                                  action="{{route('showAllTrucks', ['refresh'=>$refresh, 'nb'=>$nb])}}">
                                {{ csrf_field() }}
                            <div class="col-lg-4">@if($nb=='all')All trucks @elseif($nb=='debt only')Trucks with debt only @endif</div>
                            <div class="form-group col-lg-4">
                                <input type="text" class="form-control" name="search"
                                       @if(isset($searchQuery)) value="{{$searchQuery}}" @else value=""
                                       @endif placeholder="search"/>
                            </div>
                            <div class="form-group col-lg-2">
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
                            <div class="form-group col-lg-1">
                                <a href="{{route('showAllTrucks', ['refresh'=>'true', 'nb'=>$nb])}}"
                                   class="btn btn-add"><span
                                            class="glyphicon glyphicon-refresh"></span></a>
                            </div>
                            <div>
                                <a href="{{route('showAddTruck', ['originalPage'=>'allTrucks-'.$nb])}}"
                                   class="btn btn-add"><span
                                            class="glyphicon glyphicon-plus-sign"></span> Truck</a>
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
                                    <th class="col1"></th>
                                    <th class="text-center colName">
                                        {{--Name / Carrier<br>--}}
                                        {{--<a class="glyphicon glyphicon-chevron-up general-sorting"--}}
                                           {{--@if(isset($searchQuery)) href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listTrucks->currentPage().'&sortby=name&order=asc')}}"--}}
                                           {{--@else href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?page='.$listTrucks->currentPage().'&sortby=name&order=asc')}}"--}}
                                                {{--@endif></a>--}}
                                        {{--<a class="glyphicon glyphicon-chevron-down general-sorting"--}}
                                           {{--@if(isset($searchQuery)) href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listTrucks->currentPage().'&sortby=name&order=desc')}}"--}}
                                           {{--@else href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?page='.$listTrucks->currentPage().'&sortby=name&order=desc')}}"--}}
                                                {{--@endif></a>--}}
                                    </th>
                                    <th class="text-center colLicense">
                                        {{--License Plate<br>--}}
                                        {{--<a class="glyphicon glyphicon-chevron-up general-sorting"--}}
                                           {{--@if(isset($searchQuery)) href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listTrucks->currentPage().'&sortby=licensePlate&order=asc')}}"--}}
                                           {{--@else href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?page='.$listTrucks->currentPage().'&sortby=licensePlate&order=asc')}}"--}}
                                                {{--@endif></a>--}}
                                        {{--<a class="glyphicon glyphicon-chevron-down general-sorting"--}}
                                           {{--@if(isset($searchQuery)) href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listTrucks->currentPage().'&sortby=licensePlate&order=desc')}}"--}}
                                           {{--@else href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?page='.$listTrucks->currentPage().'&sortby=licensePlate&order=desc')}}"--}}
                                                {{--@endif></a>--}}
                                    </th>
                                    <th class="text-center colNumber">
                                        {{--Confirmed<br>--}}
                                        {{--<a class="glyphicon glyphicon-chevron-up general-sorting"--}}
                                           {{--@if(isset($searchQuery)) href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listTrucks->currentPage().'&sortby=realNumberPallets&order=asc')}}"--}}
                                           {{--@else href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?page='.$listTrucks->currentPage().'&sortby=realNumberPallets&order=asc')}}"--}}
                                                {{--@endif></a>--}}
                                        {{--<a class="glyphicon glyphicon-chevron-down general-sorting"--}}
                                           {{--@if(isset($searchQuery)) href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listTrucks->currentPage().'&sortby=realNumberPallets&order=desc')}}"--}}
                                           {{--@else href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?page='.$listTrucks->currentPage().'&sortby=realNumberPallets&order=desc')}}"--}}
                                                {{--@endif></a>--}}
                                    </th>
                                    <th class="text-center colNumber">
                                        {{--Planned<br>--}}
                                        {{--<a class="glyphicon glyphicon-chevron-up general-sorting"--}}
                                           {{--@if(isset($searchQuery)) href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listTrucks->currentPage().'&sortby=theoricalNumberPallets&order=asc')}}"--}}
                                           {{--@else href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?page='.$listTrucks->currentPage().'&sortby=theoricalNumberPallets&order=asc')}}"--}}
                                                {{--@endif></a>--}}
                                        {{--<a class="glyphicon glyphicon-chevron-down general-sorting"--}}
                                           {{--@if(isset($searchQuery)) href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listTrucks->currentPage().'&sortby=theoricalNumberPallets&order=desc')}}"--}}
                                           {{--@else href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?page='.$listTrucks->currentPage().'&sortby=theoricalNumberPallets&order=desc')}}"--}}
                                                {{--@endif></a>--}}
                                    </th>
                                    <th class="text-center colNumber">
                                        {{--Rest<br><br>--}}
                                    </th>
                                    <th class="text-center" colspan="2">Truck situation by WENZEL
                                        {{--Debt<br>--}}
                                        {{--<a class="glyphicon glyphicon-chevron-up general-sorting"--}}
                                           {{--@if(isset($searchQuery)) href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listTrucks->currentPage().'&sortby=palletsDebt&order=asc')}}"--}}
                                           {{--@else href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?page='.$listTrucks->currentPage().'&sortby=palletsDebt&order=asc')}}"--}}
                                                {{--@endif></a>--}}
                                        {{--<a class="glyphicon glyphicon-chevron-down general-sorting"--}}
                                           {{--@if(isset($searchQuery)) href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listTrucks->currentPage().'&sortby=palletsDebt&order=desc')}}"--}}
                                           {{--@else href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?page='.$listTrucks->currentPage().'&sortby=palletsDebt&order=desc')}}"--}}
                                                {{--@endif></a>--}}
                                        </th>
                                    <th class="colDanger"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th class="col1">
                                        <a class="glyphicon glyphicon-chevron-up general-sorting"
                                           @if(isset($searchQuery)) href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listTrucks->currentPage().'&sortby=activated&order=asc')}}"
                                           @else href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?page='.$listTrucks->currentPage().'&sortby=activated&order=asc')}}"
                                                @endif></a>
                                        <a class="glyphicon glyphicon-chevron-down general-sorting"
                                           @if(isset($searchQuery)) href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listTrucks->currentPage().'&sortby=activated&order=desc')}}"
                                           @else href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?page='.$listTrucks->currentPage().'&sortby=activated&order=desc')}}"
                                                @endif></a>
                                    </th>
                                    <th class="text-center colName">Name / Carrier<br>
                                        <a class="glyphicon glyphicon-chevron-up general-sorting"
                                           @if(isset($searchQuery)) href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listTrucks->currentPage().'&sortby=name&order=asc')}}"
                                           @else href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?page='.$listTrucks->currentPage().'&sortby=name&order=asc')}}"
                                                @endif></a>
                                        <a class="glyphicon glyphicon-chevron-down general-sorting"
                                           @if(isset($searchQuery)) href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listTrucks->currentPage().'&sortby=name&order=desc')}}"
                                           @else href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?page='.$listTrucks->currentPage().'&sortby=name&order=desc')}}"
                                                @endif></a>
                                    </th>
                                    <th class="text-center colLicense">License Plate<br>
                                        <a class="glyphicon glyphicon-chevron-up general-sorting"
                                           @if(isset($searchQuery)) href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listTrucks->currentPage().'&sortby=licensePlate&order=asc')}}"
                                           @else href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?page='.$listTrucks->currentPage().'&sortby=licensePlate&order=asc')}}"
                                                @endif></a>
                                        <a class="glyphicon glyphicon-chevron-down general-sorting"
                                           @if(isset($searchQuery)) href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listTrucks->currentPage().'&sortby=licensePlate&order=desc')}}"
                                           @else href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?page='.$listTrucks->currentPage().'&sortby=licensePlate&order=desc')}}"
                                                @endif></a>
                                    </th>
                                    <th class="text-center colNumber">Conf.<br>
                                        <a class="glyphicon glyphicon-chevron-up general-sorting"
                                           @if(isset($searchQuery)) href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listTrucks->currentPage().'&sortby=realNumberPallets&order=asc')}}"
                                           @else href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?page='.$listTrucks->currentPage().'&sortby=realNumberPallets&order=asc')}}"
                                                @endif></a>
                                        <a class="glyphicon glyphicon-chevron-down general-sorting"
                                           @if(isset($searchQuery)) href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listTrucks->currentPage().'&sortby=realNumberPallets&order=desc')}}"
                                           @else href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?page='.$listTrucks->currentPage().'&sortby=realNumberPallets&order=desc')}}"
                                                @endif></a>
                                    </th>
                                    <th class="text-center colNumber">Plan.<br>
                                        <a class="glyphicon glyphicon-chevron-up general-sorting"
                                           @if(isset($searchQuery)) href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listTrucks->currentPage().'&sortby=theoricalNumberPallets&order=asc')}}"
                                           @else href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?page='.$listTrucks->currentPage().'&sortby=theoricalNumberPallets&order=asc')}}"
                                                @endif></a>
                                        <a class="glyphicon glyphicon-chevron-down general-sorting"
                                           @if(isset($searchQuery)) href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listTrucks->currentPage().'&sortby=theoricalNumberPallets&order=desc')}}"
                                           @else href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?page='.$listTrucks->currentPage().'&sortby=theoricalNumberPallets&order=desc')}}"
                                                @endif></a>
                                    </th>
                                    <th class="text-center colNumber" data-toggle="tooltip" data-placement="top" title="The number of pallets that hasn't been confirmed yet">Rest<br><br></th>
                                    <th class="text-center colNumber2" data-toggle="tooltip" data-placement="top" title="The confirmed number of pallets the account has to give to Wenzel (<0) or inversely (>0)">Confirmed<br>
                                        <a class="glyphicon glyphicon-chevron-up general-sorting"
                                           @if(isset($searchQuery)) href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listTrucks->currentPage().'&sortby=theoricalPalletsDebt&order=asc')}}"
                                           @else href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?page='.$listTrucks->currentPage().'&sortby=theoricalPalletsDebt&order=asc')}}"
                                                @endif></a>
                                        <a class="glyphicon glyphicon-chevron-down general-sorting"
                                           @if(isset($searchQuery)) href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listTrucks->currentPage().'&sortby=theoricalPalletsDebt&order=desc')}}"
                                           @else href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?page='.$listTrucks->currentPage().'&sortby=theoricalPalletsDebt&order=desc')}}"
                                                @endif></a>
                                    </th>
                                    <th class="text-center colNumber2" data-toggle="tooltip" data-placement="top" title="The planned number of pallets the account has to give to Wenzel (<0) or inversely (>0)">Planned<br>
                                        <a class="glyphicon glyphicon-chevron-up general-sorting"
                                           @if(isset($searchQuery)) href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listTrucks->currentPage().'&sortby=realPalletsDebt&order=asc')}}"
                                           @else href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?page='.$listTrucks->currentPage().'&sortby=realPalletsDebt&order=asc')}}"
                                                @endif></a>
                                        <a class="glyphicon glyphicon-chevron-down general-sorting"
                                           @if(isset($searchQuery)) href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listTrucks->currentPage().'&sortby=realPalletsDebt&order=desc')}}"
                                           @else href="{{url('/allTrucks/'.$refresh.'/'.$nb.'?page='.$listTrucks->currentPage().'&sortby=realPalletsDebt&order=desc')}}"
                                                @endif></a>
                                    </th>
                                    <th class="colDanger text-center">Errors<br>?</th>
                                </tr>
                                @foreach($listTrucks as $trucks)
                                    <tr class="text-center">
                                        <td class="text-center col1"><a class="link"
                                                                        href="{{route('showDetailsTruck',$trucks->id)}}">
                                                <span @if($trucks->activated == 1) class="glyphicon glyphicon-eye-open"
                                                      @else class="glyphicon glyphicon-eye-close" @endif></span></a>
                                        </td>
                                        <td class="colName"><a class="link"
                                                               href="{{route('showDetailsPalletsaccount',\App\Palletsaccount::where('nickname',$trucks->palletsaccount_name)->first()->id)}}">{{$trucks->name}}</a>
                                        </td>
                                        <td class="colLicense">{{$trucks->licensePlate}}</td>
                                        <td class="colNumber"><span @if($trucks->realNumberPallets<0) class="text-inf0"
                                                                    @elseif($trucks->realNumberPallets>0) class="text-sup0"
                                                                    @else class="text-egal0" @endif>{{$trucks->realNumberPallets}}</span>
                                        </td>
                                        <td class="colNumber"><span
                                                    @if($trucks->theoricalNumberPallets<0) class="text-inf0"
                                                    @elseif($trucks->theoricalNumberPallets>0) class="text-sup0"
                                                    @else class="text-egal0" @endif>{{$trucks->theoricalNumberPallets}}</span>
                                        </td>
                                        <td class="colNumber">{{$trucks->theoricalNumberPallets - $trucks->realNumberPallets}}</td>
                                        <td class="colNumber2"><span @if($trucks->realPalletsDebt<0) class="text-inf0"
                                                                     @elseif($trucks->realPalletsDebt>0) class="text-sup0"
                                                                     @else class="text-egal0" @endif>{{$trucks->realPalletsDebt}}</span>
                                        </td>
                                        <td class="colNumber2"><span @if($trucks->theoricalPalletsDebt<0) class="text-inf0"
                                                                     @elseif($trucks->theoricalPalletsDebt>0) class="text-sup0"
                                                                     @else class="text-egal0" @endif>{{$trucks->theoricalPalletsDebt}}</span>
                                        </td>
                                        <td class="colDanger">
                                            @php($listPalletstransfers=\App\Palletstransfer::where('creditAccount','LIKE', $trucks->name.'-'.$trucks->licensePlate.'%')->orWhere('debitAccount','LIKE', $trucks->name.'-'.$trucks->licensePlate.'%')->get())
                                            @php($k=0)
                                            @foreach($listPalletstransfers as $transfer)
                                                @php($errorsTransfer= \App\Http\Controllers\PalletstransfersController::actualErrors($transfer))
                                                @foreach($errorsTransfer as $errorT)
                                                    @if(!empty($errorT)&& $k<2)
                                                        <span class="glyphicon glyphicon-warning-sign text-danger"
                                                              data-toggle="tooltip" title="{{$errorT->name}}"></span>
                                                        @php($k=$k+1)
                                                    @elseif(!empty($errorT)&& $k==2)
                                                        <span class="text-danger">...</span>
                                                        @php($k=$k+1)
                                                    @endif
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