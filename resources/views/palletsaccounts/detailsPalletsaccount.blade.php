@extends('layouts.default')

@section('title')
    Pallets account details
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
@section('classCarriers')
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
                @if($totalpallets>0)
                    <div class="panel panel-warning">
                        @elseif($totalpallets=0)
                            <div class="panel panel-general">
                                @else
                                    <div class="panel panel-danger">
                                        @endif
                                        <div class="panel-heading">Details of the account n° {{$id}} : {{$name}}</div>
                                        <div class="panel-body panel-body-general">
                                            <form class="form-horizontal text-right" role="form" method="POST"
                                                  action="{{route('updatePalletsaccount', $id)}}">
                                                {{ csrf_field() }}

                                                <div class="form-group">
                                                    <!--name-->
                                                    <div class="col-lg-3">
                                                        <label for="name" class="control-label">Name :</label>
                                                    </div>
                                                    <div class="col-lg-5">
                                                        <input id="name" type="text" class="form-control" name="name"
                                                               value="{{ $name }}" placeholder="Name" required
                                                               autofocus>
                                                        @if ($errors->has('name'))
                                                            <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                                        @endif
                                                    </div>
                                                    <!--type-->
                                                    <div class="col-lg-1">
                                                        <label for="type" class="control-label"><span>*</span> Type
                                                            :</label>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <!-- if mistake in the adding form you are redirected with field already filled-->
                                                        <select class="selectpicker show-tick form-control"
                                                                data-size="5"
                                                                data-live-search="true"
                                                                data-live-search-style="startsWith"
                                                                title="Type" name="type"
                                                                required>
                                                            @if(Illuminate\Support\Facades\Input::old('type'))
                                                                <option @if(old('type') == 'Carrier') selected @endif>
                                                                    Carrier
                                                                </option>
                                                                <option @if(old('type') == 'Other') selected @endif>
                                                                    Other
                                                                </option>
                                                                <option @if(old('type') == 'Warehouse') selected @endif>
                                                                    Warehouse
                                                                </option>
                                                            @elseif(isset($type))
                                                                <option @if($type == 'Carrier') selected @endif>
                                                                    Carrier
                                                                </option>
                                                                <option @if($type == 'Other') selected @endif>Other
                                                                </option>
                                                                <option @if($type == 'Warehouse') selected @endif>
                                                                    Warehouse
                                                                </option>
                                                            @else
                                                                <option>Carrier</option>
                                                                <option>Other</option>
                                                                <option>Warehouse</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <!--confirmed number of pallets-->
                                                    <div class="col-lg-3">
                                                        <label for="realNumberPallets" class="control-label">Confirmed
                                                            Pallets Nbr
                                                            :</label>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <input id="realNumberPallets" type="number" class="form-control"
                                                               name="realNumberPallets"
                                                               value="{{ $realNumberPallets }}"
                                                               placeholder="Confirmed pal. nbr"
                                                               required readonly autofocus>
                                                    </div>

                                                    <!--planned number of pallets-->
                                                    <div class="col-lg-4">
                                                        <label for="theoricalNumberPallets" class="control-label">Planned
                                                            Pallets Nbr
                                                            :</label>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <input id="theoricalNumberPallets" type="number"
                                                               class="form-control"
                                                               name="theoricalNumberPallets"
                                                               value="{{ $theoricalNumberPallets }}"
                                                               placeholder="Planned pal. nbr"
                                                               readonly required autofocus>
                                                    </div>
                                                </div>
                                                @if($type<>'Other')
                                                    <div class="form-group">
                                                        <!--warehouses associated-->
                                                        <div class="col-lg-3">
                                                            <label for="namewarehouses" class="control-label">Warehouses
                                                                associated
                                                                :</label>
                                                        </div>
                                                        <div class="col-lg-7">

                                                            <select class="selectpicker show-tick form-control"
                                                                    data-size="5"
                                                                    data-live-search="true"
                                                                    data-live-search-style="startsWith"
                                                                    title="Warehouses Associated"
                                                                    name="namewarehouses[]"
                                                                    multiple>
                                                                @foreach($listWarehouses as $warehouse )
                                                                    @php($list[]=null)
                                                                    @if(Illuminate\Support\Facades\Input::old('namewarehouses'))
                                                                        @foreach(old('namewarehouses') as $warehouseA)
                                                                            @if($warehouseA == $warehouse->name)
                                                                                <option selected>{{$warehouse->name}}</option>
                                                                                @php($list[]=$warehouse)
                                                                            @endif
                                                                        @endforeach
                                                                        @if(!in_array($warehouse, $list))
                                                                            <option>{{$warehouse->name}}</option>
                                                                        @endif
                                                                    @elseif(isset($namewarehouses))
                                                                        @foreach($namewarehouses as $warehouseA)
                                                                            @if($warehouseA == $warehouse->name)
                                                                                <option selected>{{$warehouse->name}}</option>
                                                                                @php($list[]=$warehouse)
                                                                            @endif
                                                                        @endforeach
                                                                        @if(!in_array($warehouse, $list))
                                                                            <option>{{$warehouse->name}}</option>
                                                                        @endif
                                                                    @else
                                                                        <option>{{$warehouse->name}}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>

                                                        </div>
                                                        <div class="col-lg-2 text-left">
                                                            <a href="{{route('showAddWarehouse')}}" class="link"><span
                                                                        class="glyphicon glyphicon-plus-sign"></span>
                                                                Add warehouse</a>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="form-group">
                                                    <div class="col-lg-3 col-lg-offset-3">
                                                        <input type="submit"
                                                               class="btn btn-primary btn-block btn-form"
                                                               value="Update"
                                                               name="updatePalletsaccount">
                                                    </div>

                                                    <div class="col-lg-3 col-lg-offset-2">
                                                        <button type="button" class="btn btn-primary btn-block btn-form"
                                                                data-toggle="modal"
                                                                data-target="#deletePalletsaccount_modal">Delete
                                                        </button>
                                                    </div>
                                                </div>
                                                @if (Session::has('messageUpdatePalletsaccount'))
                                                    <div class="alert alert-success text-alert text-center">{{ Session::get('messageUpdatePalletsaccount') }}</div>
                                                @endif
                                            </form>
                                            <!-- Modal Delete -->
                                            <div class="modal fade" id="deletePalletsaccount_modal" role="dialog">
                                                <div class="modal-dialog modal-sm">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal">
                                                                &times;
                                                            </button>
                                                            <h4 class="modal-title text-center">Are you sure to delete
                                                                this
                                                                pallets
                                                                account ?</h4>
                                                        </div>
                                                        <div class="modal-body center">
                                                            <form method="post"
                                                                  action="{{route('deletePalletsaccount', $id)}}">
                                                                <input type="hidden" name="_method" value="delete">
                                                                {{ csrf_field() }}
                                                                <div class="text-center">
                                                                    <button type="submit"
                                                                            class="btn btn-danger btn-modal"
                                                                            value="yes"
                                                                            name="deletePalletsaccount">
                                                                        Yes
                                                                    </button>
                                                                    <button type="button"
                                                                            class="btn btn-success btn-modal"
                                                                            data-dismiss="modal">No
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default btn-modal"
                                                                    data-dismiss="modal">
                                                                Close
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!--search bar-->
                                            <br>
                                            <div>
                                                <form role="form" class="searchBar form-inline" method="GET" action="{{route('showDetailsPalletsaccount', $id)}}">
                                                    {{ csrf_field() }}
                                                    {{--<div class="form-goup">--}}
                                                    <div class="col-lg-8 col-lg-offset-2 input-group">
                                                        @if(isset($searchQuery))
                                                            <input type="text" class="searchBar form-control" name="search" value="{{$searchQuery}}"
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
                                                    {{--</div>--}}
                                                    <div class="col-lg-5 col-lg-offset-2 input-group">
                                                        <label class="checkbox-inline"><input type="checkbox" value="loading">Loading Place</label>
                                                        <label class="checkbox-inline"><input type="checkbox" value="offloading">Offloading Place</label>
                                                        <label class="checkbox-inline"><input type="checkbox" value="credit">Credit</label>
                                                        <label class="checkbox-inline"><input type="checkbox" value="debit">Debit</label>
                                                    </div>
                                                    <div class="col-lg-3 col-lg-offset-1 input-group">
                                                    {{--<span class="input-group-btn">--}}
                                                    <button class="btn btn-block glyphicon glyphicon-search" type="submit"
                                                            name="searchSubmit"></button>
                                                    {{--</span>--}}
                                                    </div>
                                                </form>
                                                <br>
                                            </div>
                                            <div class="table-responsive table-loading-account">
                                                <table class="table table-hover table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-center col1">Atrnr
                                                            {{--<a--}}
                                                            {{--class="glyphicon glyphicon-chevron-up general-sorting"--}}
                                                            {{--href="{{url('/detailsPalletsaccount/'.$id.'?page='.$listLoadingsAssociated->currentPage().'&sortby=atrnr&order=asc')}}"></a><a--}}
                                                            {{--class="glyphicon glyphicon-chevron-down general-sorting"--}}
                                                            {{--href="{{url('/detailsPalletsaccount/'.$id.'?page='.$listLoadingsAssociated->currentPage().'&sortby=atrnr&order=desc')}}"></a>--}}
                                                        </th>
                                                        <th class="text-center col3">Date transfer
                                                            {{--<a--}}
                                                            {{--class="glyphicon glyphicon-chevron-up general-sorting"--}}
                                                            {{--href="{{url('/detailsPalletsaccount/'.$id.'?page='.$listPalletstransfers->currentPage().'&sortby=date&order=asc')}}"></a><a--}}
                                                            {{--class="glyphicon glyphicon-chevron-down general-sorting"--}}
                                                            {{--href="{{url('/detailsPalletsaccount/'.$id.'?page='.$listPalletstransfers->currentPage().'&sortby=date&order=desc')}}"></a>--}}
                                                        </th>
                                                        <th class="text-center col4">Subfrachter
                                                            {{--<a--}}
                                                            {{--class="glyphicon glyphicon-chevron-up general-sorting"--}}
                                                            {{--href="{{url('/detailsPalletsaccount/'.$id.'?page='.$listPalletstransfers->currentPage().'&sortby=loading_atrnr&order=asc')}}"></a><a--}}
                                                            {{--class="glyphicon glyphicon-chevron-down general-sorting"--}}
                                                            {{--href="{{url('/detailsPalletsaccount/'.$id.'?page='.$listPalletstransfers->currentPage().'&sortby=loading_atrnr&order=desc')}}"></a>--}}
                                                        </th>
                                                        <th class="text-center col3">Planned<br>pallets nbr
                                                            {{--<a--}}
                                                            {{--class="glyphicon glyphicon-chevron-up general-sorting"--}}
                                                            {{--href="{{url('/detailsPalletsaccount/'.$id.'?page='.$listPalletstransfers->currentPage().'&sortby=palletsNumber&order=asc')}}"></a><a--}}
                                                            {{--class="glyphicon glyphicon-chevron-down general-sorting"--}}
                                                            {{--href="{{url('/detailsPalletsaccount/'.$id.'?page='.$listPalletstransfers->currentPage().'&sortby=palletsNumber&order=desc')}}"></a>--}}
                                                        </th>
                                                        <th class="text-center col2">Type<br>place
                                                            {{--<a--}}
                                                            {{--class="glyphicon glyphicon-chevron-up general-sorting"--}}
                                                            {{--href="{{url('/detailsPalletsaccount/'.$id.'?page='.$listPalletstransfers->currentPage().'&sortby=palletsNumber&order=asc')}}"></a><a--}}
                                                            {{--class="glyphicon glyphicon-chevron-down general-sorting"--}}
                                                            {{--href="{{url('/detailsPalletsaccount/'.$id.'?page='.$listPalletstransfers->currentPage().'&sortby=palletsNumber&order=desc')}}"></a>--}}
                                                        </th>
                                                        <th class="text-center col2">Type transfer
                                                            {{--<a--}}
                                                            {{--class="glyphicon glyphicon-chevron-up general-sorting"--}}
                                                            {{--href="{{url('/detailsPalletsaccount/'.$id.'?page='.$listPalletstransfers->currentPage().'&sortby=palletsNumber&order=asc')}}"></a><a--}}
                                                            {{--class="glyphicon glyphicon-chevron-down general-sorting"--}}
                                                            {{--href="{{url('/detailsPalletsaccount/'.$id.'?page='.$listPalletstransfers->currentPage().'&sortby=palletsNumber&order=desc')}}"></a>--}}
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @for($k=0;$k<5;$k++)
                                                        @for($i=0;$i<4;$i++)
                                                            @if(!$listLoadingsAssociated[$i+4*$k]->isEmpty())
                                                                @php($j=$k+1)
                                                                @if($i==0)
                                                                    @php($numberPalletsI='numberPalletsLoadingPlace'.$j)
                                                                    <!--loading place credit account-->
                                                                    @foreach($listLoadingsAssociated[$i+4*$k] as $loading)
                                                                        <tr>
                                                                            <td class="text-center col1"><a
                                                                                        href="{{route('showDetailsLoading', $loading->atrnr)}}"
                                                                                        class="link">{{$loading->atrnr}}</a></td>
                                                                            <td class="text-center col3">{{$loading->ladedatum}}</td>
                                                                            <td class="text-center col4">{{$loading->subfrachter}}</td>
                                                                            <td class="text-center col3">{{$loading->$numberPalletsI}}</td>
                                                                            <td class="text-center col2">Loading place</td>
                                                                            <td class="text-center col2">Credit</td>
                                                                        </tr>
                                                                    @endforeach
                                                                    @elseif($i==1)
                                                                    @php($numberPalletsI='numberPalletsLoadingPlace'.$j)
                                                                    <!--loading place debit account-->
                                                                    @foreach($listLoadingsAssociated[$i+4*$k] as $loading)
                                                                        <tr>
                                                                            <td class="text-center col1"><a
                                                                                        href="{{route('showDetailsLoading', $loading->atrnr)}}"
                                                                                        class="link">{{$loading->atrnr}}</a></td>
                                                                            <td class="text-center col3">{{$loading->ladedatum}}</td>
                                                                            <td class="text-center col4">{{$loading->subfrachter}}</td>
                                                                            <td class="text-center col3">-{{$loading->$numberPalletsI}}</td>
                                                                            <td class="text-center col2">Loading place</td>
                                                                            <td class="text-center col2">Debit</td>
                                                                        </tr>
                                                                    @endforeach
                                                                @elseif($i==2)
                                                                    @php($numberPalletsI='numberPalletsOffloadingPlace'.$j)
                                                                    <!--offloading place credit account-->
                                                                    @foreach($listLoadingsAssociated[$i+4*$k] as $loading)
                                                                        <tr>
                                                                            <td class="text-center col1"><a
                                                                                        href="{{route('showDetailsLoading', $loading->atrnr)}}"
                                                                                        class="link">{{$loading->atrnr}}</a></td>
                                                                            <td class="text-center col3">{{$loading->entladedatum}}</td>
                                                                            <td class="text-center col4">{{$loading->subfrachter}}</td>
                                                                            <td class="text-center col3">{{$loading->$numberPalletsI}}</td>
                                                                            <td class="text-center col2">Offloading place</td>
                                                                            <td class="text-center col2">Credit</td>
                                                                        </tr>
                                                                    @endforeach
                                                                @elseif($i==3)
                                                                    @php($numberPalletsI='numberPalletsOffloadingPlace'.$j)
                                                                    <!--offloading place debit account-->
                                                                    @foreach($listLoadingsAssociated[$i+4*$k] as $loading)
                                                                        <tr>
                                                                            <td class="text-center col1"><a
                                                                                        href="{{route('showDetailsLoading', $loading->atrnr)}}"
                                                                                        class="link">{{$loading->atrnr}}</a></td>
                                                                            <td class="text-center col3">{{$loading->entladedatum}}</td>
                                                                            <td class="text-center col4">{{$loading->subfrachter}}</td>
                                                                            <td class="text-center col3">-{{$loading->$numberPalletsI}}</td>
                                                                            <td class="text-center col2">Offloading place</td>
                                                                            <td class="text-center col2">Debit</td>
                                                                        </tr>
                                                                    @endforeach
                                                                    @endif
                                                                @endif
                                                    @endfor
                                                    @endfor
                                                    </tbody>
                                                </table>
                                            </div>
                                            {{--<div class="row">--}}
                                            {{--<div class="general-pagination text-left">{!! $listLoadingsAssociated->render() !!}</div>--}}

                                            {{--@if ($listLoadingsAssociated->currentPage()==$listLoadingsAssociated->lastPage())--}}
                                            {{--<div class="general-legend col-lg-offset-9">--}}
                                            {{--Showing @php($legend1=1+ ($listLoadingsAssociated->currentPage() -1) * 5)  {{$legend1}}--}}
                                            {{--to {{$count}} of {{$count}} results--}}
                                            {{--</div>--}}
                                            {{--@elseif($listLoadingsAssociated->isEmpty())--}}
                                            {{--<div class="general-legend col-lg-offset-9">--}}
                                            {{--Showing 0 to 0 of 0 results--}}
                                            {{--</div>--}}
                                            {{--@else--}}
                                            {{--<div class="general-legend col-lg-offset-9">--}}
                                            {{--Showing @php($legend1=1+ ($listLoadingsAssociated->currentPage() -1) * 5)  {{$legend1}}--}}
                                            {{--to @php($legend2= $listLoadingsAssociated->currentPage() * 5) {{$legend2}}--}}
                                            {{--of {{$count}}--}}
                                            {{--results--}}
                                            {{--</div>--}}
                                            {{--@endif--}}
                                            {{--</div>--}}
                                        </div>
                                    </div>
                            </div>
                    </div>
            </div>
        @endif
    </div>
@endsection