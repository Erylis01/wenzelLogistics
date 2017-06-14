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
                            <div class="alert alert-success text-alert text-center">{{ Session::get('messageDeletePalletsaccount') }}</div>
                        @elseif(Session::has('messageAddPalletsaccount'))
                            <div class="alert alert-success text-alert text-center">{{ Session::get('messageAddPalletsaccount') }}</div>
                        @endif

                        <div class="table-responsive table-palletsaccounts">
                            <table class="table table-hover table-bordered">
                                <thead>
                                @if($totalpallets<0)
                                    @php($class="text-alert")
                                @elseif($totalpallets>0)
                                    @php($class="text-warning")
                                @else
                                    @php($class="text-success")
                                @endif
                                <tr>
                                    <th class="text-center colTot"><a href="{{route('showAllPalletstransfers')}}"
                                                                      class="link">TOTAL</a></th>
                                    <th class="text-center colTotal"><span class={{$class}}>{{$totalpallets}}</span>
                                    </th>
                                </tr>
                                </thead>
                            </table>
                        </div>
<br>
                            <div>
                                <form role="form" class="searchBar form-inline" method="GET" action="{{route('showAllPalletsaccounts')}}">
                                    {{ csrf_field() }}
                                    <div class="searchBar col-lg-12 input-group">
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
                                </form>
                                <br>
                            </div>

                        <!-- Table -->
                        <div class="table-responsive table-palletsaccounts">
                            <table class="table table-hover table-bordered">
                                <thead>
                                <tr>
                                    @if(isset($searchQuery))
                                        <th class="text-center colName">Name<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allPalletsaccounts?search='.$searchQuery.'&sortby=name&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allPalletsaccounts?search='.$searchQuery.'&sortby=name&order=desc')}}"></a></th>
                                        <th class="text-center colType">Type<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allPalletsaccounts?search='.$searchQuery.'&sortby=type&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allPalletsaccounts?search='.$searchQuery.'&sortby=type&order=desc')}}"></a></th>
                                        <th class="text-center colTotal">Total<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allPalletsaccounts?search='.$searchQuery.'&sortby=realNumberPallets&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allPalletsaccounts?search='.$searchQuery.'&sortby=realNumberPallets&order=desc')}}"></a>
                                        </th>
                                        @else
                                    <th class="text-center colName">Name<br><a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/allPalletsaccounts?sortby=name&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/allPalletsaccounts?sortby=name&order=desc')}}"></a></th>
                                    <th class="text-center colType">Type<br><a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/allPalletsaccounts?sortby=type&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/allPalletsaccounts?sortby=type&order=desc')}}"></a></th>
                                    <th class="text-center colTotal">Total<br><a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/allPalletsaccounts?sortby=realNumberPallets&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/allPalletsaccounts?sortby=realNumberPallets&order=desc')}}"></a>
                                    </th>
                                        @endif
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($listPalletsaccounts as $palletsaccount)
                                    <tr>
                                        <td class="text-center colName"><a
                                                    href="#{{str_replace(array(' ', '.', ',', '-'), '', $palletsaccount->name)}}-collapse"
                                                    data-toggle="collapse"
                                                    class="link">{{$palletsaccount->name}}</a></td>
                                        <td class="text-center colType">{{$palletsaccount->type}}</td>
                                        <td class="text-center colTotal">{{$palletsaccount->realNumberPallets}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                @foreach($listPalletsaccounts as $palletsaccount)
                    <div id="{{str_replace(array(' ', '.', ',', '-'), '', $palletsaccount->name)}}-collapse"
                         class="panel panel-general col-lg-8 panel-palletsaccounts-details collapse">
                        <div class="panel-heading">Account n° {{$palletsaccount->id}} : {{$palletsaccount->name}}</div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" action="">
                                <div class="form-group">
                                    <div class="table-responsive table-palletsNumber">
                                        <table class="table table-hover table-bordered">
                                            <thead>
                                            <tr>
                                                <th class="text-center">Confirmed<br> pallets nbr</th>
                                                <th class="text-center">Planned<br> pallets nbr</th>
                                                <th class="text-center">Last<br> pallets transfer</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td class="text-center">{{$palletsaccount->realNumberPallets}}</td>
                                                <td class="text-center">{{$palletsaccount->theoricalNumberPallets}}</td>
                                                <td class="text-center">{{$palletsaccount->lastNumberPalletsTransfered}}</td>
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
                                            <ul>
                                                @foreach($listWarehouses as $warehouse)
                                                    <li><a href="{{route('showDetailsWarehouse', $warehouse->id)}}"
                                                           class="link">{{$warehouse->name}}</a></li>
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
                                                    <li><a href="{{route('showDetailsTruck', $truck->id)}}"
                                                           class="link">{{$truck->name}} - {{$truck->licensePlate}}</a></li>
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
                    </div>

                @endforeach
            </div>
        @endif
    </div>
@endsection