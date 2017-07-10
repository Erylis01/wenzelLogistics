@extends('layouts.default')

@section('title')
    All pallets accounts
@endsection

@section('stylesheet')
    <link href="{{asset('css/palletsaccounts.css')}}" rel="stylesheet" type="text/css">
@endsection

@section('classLoadings')
    class="nonActive"
@endsection
@section('classWarehouses')
    class="nonActive"
@endsection
@section('classTrucks')
    class="nonActive"
@endsection
@section('classPalletsAccounts')
    class="active"
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
                <div class="panel panel-general col-lg-6 panel-palletsaccounts">
                    <div class="panel-heading">Total of pallets by account <span class="col-lg-offset-1"><a
                                    href="{{route('showAddPalletsaccount')}}" class=" btn btn-add"><span
                                        class="glyphicon glyphicon-plus-sign"></span> Add account</a></span></div>

                    <div class="panel-body">
                        @if (Session::has('messageDeletePalletsaccount'))
                            <p class="alert alert-success text-alert text-center">{{ Session::get('messageDeletePalletsaccount') }}</p>
                        @elseif(Session::has('messageAddPalletsaccount'))
                            <p class="alert alert-success text-alert text-center">{{ Session::get('messageAddPalletsaccount') }}</p>
                        @endif

                        <div class="table-responsive table-palletsaccounts">
                            <table class="table table-hover table-bordered">
                                <thead>
                                <tr>
                                    <th class="text-center colTot1"><a href="{{route('showAllPalletstransfers')}}"
                                                                      class="link">TOTAL</a></th>
                                    <th class="text-center colTotal1"><span @if($totalpallets<0) class="text-inf0" @elseif($totalpallets>0) class="text-sup0" @else class="text-egal0" @endif>{{$totalpallets}}</span>
                                    </th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <br>
                        <div>
                            <form role="form" method="GET" action="{{route('showAllPalletsaccounts')}}">
                                {{ csrf_field() }}
                                <div class="col-lg-5 input-group searchBar">
                            <span class="input-group-btn searchInput">
                                    <input type="text" class="form-control" name="search" @if(isset($searchQuery)) value="{{$searchQuery}}" @else value="" @endif
                                           placeholder="search"/>
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
                                </div>
                            </form>
                            <br>
                        </div>

                        <!-- Table -->
                        <div class="table-responsive table-palletsaccounts">
                            <table class="table table-hover table-bordered">
                                <thead>
                                <tr>

                                        <th class="text-center colName1">Name<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/allPalletsaccounts?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=name&order=asc')}}"
                                            @else href="{{url('/allPalletsaccounts?sortby=name&order=asc')}}"
                                                @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/allPalletsaccounts?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=name&order=desc')}}"
                                            @else href="{{url('/allPalletsaccounts?sortby=name&order=desc')}}"
                                                @endif></a>
                                        </th>
                                        <th class="text-center colType1">Type<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/allPalletsaccounts?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=type&order=asc')}}"
                                               @else href="{{url('/allPalletsaccounts?sortby=type&order=asc')}}"
                                                    @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/allPalletsaccounts?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=type&order=desc')}}"
                                               @else href="{{url('/allPalletsaccounts?sortby=type&order=desc')}}"
                                                    @endif></a>
                                            </th>
                                        <th class="text-center colTotal1">Total<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/allPalletsaccounts?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=realNumberPallets&order=asc')}}"
                                               @else href="{{url('/allPalletsaccounts?sortby=realNumberPallets&order=asc')}}"
                                                    @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/allPalletsaccounts?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=realNumberPallets&order=desc')}}"
                                               @else href="{{url('/allPalletsaccounts?sortby=realNumberPallets&order=desc')}}"
                                                    @endif></a>
                                            </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($listPalletsaccounts as $palletsaccount)
                                    <tr>
                                        <td class="text-center colName1"><a
                                                    href="#{{str_replace(array(' ', '.', ',', '-'), '', $palletsaccount->name)}}-collapse"
                                                    data-toggle="collapse"
                                                    class="link">{{$palletsaccount->name}}</a></td>
                                        <td class="text-center colType1">{{$palletsaccount->type}}</td>
                                        <td class="text-center colTotal1"><span  @if($palletsaccount->realNumberPallets<0) class="text-inf0" @elseif($palletsaccount->realNumberPallets>0) class="text-sup0" @else class="text-egal0" @endif>{{$palletsaccount->realNumberPallets}}</span></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                @foreach($listPalletsaccounts as $palletsaccount)
                    @if($palletsaccount->realNumberPallets==0||$palletsaccount->realNumberPallets==null)
                        <div id="{{str_replace(array(' ', '.', ',', '-'), '', $palletsaccount->name)}}-collapse"
                             class="panel panelInprogress col-lg-8 panel-palletsaccounts-details collapse">
                            @elseif($palletsaccount->realNumberPallets>0)
                                <div id="{{str_replace(array(' ', '.', ',', '-'), '', $palletsaccount->name)}}-collapse"
                                     class="panel panel-general col-lg-8 panel-palletsaccounts-details collapse">
                                    @else
                                        <div id="{{str_replace(array(' ', '.', ',', '-'), '', $palletsaccount->name)}}-collapse"
                                             class="panel panelUntreated col-lg-8 panel-palletsaccounts-details collapse">
                                            @endif
                                            <div class="panel-heading">Account n° {{$palletsaccount->id}}
                                                : {{$palletsaccount->name}}</div>
                                            <div class="panel-body">
                                                <form class="form-horizontal" role="form" method="POST" action="">
                                                    <div class="form-group">
                                                        <div class="table-responsive table-palletsNumber">
                                                            <table class="table table-hover table-bordered">
                                                                <thead>
                                                                <tr>
                                                                    <th class="text-center">Confirmed<br> pallets nbr
                                                                    </th>
                                                                    <th class="text-center">Planned<br> pallets nbr</th>
                                                                    <th class="text-center">Rest<br> to confirm</th>
                                                                    <th class="colDanger"></th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr>
                                                                    <td class="text-center">{{$palletsaccount->realNumberPallets}}</td>
                                                                    <td class="text-center">{{$palletsaccount->theoricalNumberPallets}}</td>
                                                                    <td class="text-center ">
                                                                        <strong>{{$palletsaccount->theoricalNumberPallets-$palletsaccount->realNumberPallets}}</strong>
                                                                    </td>
                                                                    <td class="colDanger">
                                                                        @php($listPalletstransfers=\App\Palletstransfer::where('creditAccount','LIKE', $palletsaccount->name.'-'.'%')->orWhere('debitAccount','LIKE', $palletsaccount->name.'-'.'%')->get())
                                                                        @php($k=0)
                                                                        @foreach($listPalletstransfers as $transfer)
                                                                            @php($errorsTransfer= \App\Http\Controllers\PalletstransfersController::actualErrors($transfer))
                                                                            @if(!empty($errorsTransfer)&& $k<2)
                                                                                <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                            @elseif(!empty($errorsTransfer)&& $k==2)
                                                                                <span class="text-danger">...</span>
                                                                            @endif
                                                                            @php($k=$k+1)
                                                                        @endforeach
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    @if($palletsaccount->type=='Network')
                                                        <div class="form-group">
                                                            <div class="col-lg-5">
                                                                <label for="warehousesAssociated"
                                                                       class="control-label legend-palletsaccounts">Warehouses
                                                                    associated :</label>
                                                            </div>
                                                            <div class="col-lg-6 info-palletsaccounts">
                                                                @php($listWarehouses=App\Palletsaccount::where('name', $palletsaccount->name)->with('warehouses')->first()->warehouses()->get())
                                                                <select class="selectpicker show-tick form-control"
                                                                        data-size="10"
                                                                        data-live-search="true"
                                                                        data-live-search-style="startsWith"
                                                                        title="Warehouses Associated"
                                                                        name="namewarehouses[]" readonly="true"
                                                                        multiple>
                                                                @foreach($listWarehouses as $warehouse)
                                                                        <option selected>{{$warehouse->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                                <ul>
                                                                    @foreach($listWarehouses as $warehouse)
                                                                        <li>
                                                                            <a href="{{route('showDetailsWarehouse', $warehouse->id)}}"
                                                                               class="link">{{$warehouse->name}}</a>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    @elseif($palletsaccount->type=='Carrier')
                                                        <div class="form-group">
                                                            <div class="col-lg-5">
                                                                <label for="trucksAssociated"
                                                                       class="control-label legend-palletsaccounts">Trucks
                                                                    associated :</label>
                                                            </div>
                                                            <div class="col-lg-6 info-palletsaccounts">
                                                                @php($listTrucks=\App\Truck::where('palletsaccount_name',$palletsaccount->name)->get())
                                                                <ul>
                                                                    @foreach($listTrucks as $truck)
                                                                        <li>
                                                                            <a href="{{route('showDetailsTruck', $truck->id)}}"
                                                                               class="link">{{$truck->name}}
                                                                                - {{$truck->licensePlate}}</a></li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="form-group">
                                                        <div class="col-lg-6">
                                                            <a href="{{route('showDetailsPalletsaccount', $palletsaccount->id)}}"
                                                               class="btn btn-form btn-block">Details</a>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            @if($palletsaccount->realNumberPallets==0||$palletsaccount->realNumberPallets==null)
                                        </div>
                                        @elseif($palletsaccount->realNumberPallets>0)
                                </div>
                            @else
                        </div>
                    @endif

                @endforeach
            </div>
        @endif
    </div>
@endsection