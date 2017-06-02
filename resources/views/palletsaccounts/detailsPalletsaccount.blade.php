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
                                                    <div class="col-lg-7">
                                                        <input id="name" type="text" class="form-control" name="name"
                                                               value="{{ $name }}" placeholder="Name" required autofocus>
                                                        @if ($errors->has('name'))
                                                            <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                    <div class="form-group">
                                                        <!--real number of pallets-->
                                                        <div class="col-lg-3">
                                                            <label for="realNumberPallets" class="control-label">Real Pallets Number
                                                                :</label>
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <input id="realNumberPallets" type="number" class="form-control"
                                                                   name="realNumberPallets"
                                                                   value="{{ $realNumberPallets }}" placeholder="Real pallets number"
                                                                   required readonly autofocus>
                                                        </div>

                                                        <!--theorical number of pallets-->
                                                        <div class="col-lg-3">
                                                            <label for="theoricalNumberPallets" class="control-label">Theorical Pallets Number
                                                                :</label>
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <input id="theoricalNumberPallets" type="number" class="form-control"
                                                                   name="theoricalNumberPallets"
                                                                   value="{{ $theoricalNumberPallets }}" placeholder="Theorical pallets number"
                                                                   readonly required autofocus>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                    <!--warehouses associated-->
                                                    <div class="col-lg-3">
                                                        <label for="warehousesAssociated" class="control-label">Warehouses
                                                            associated
                                                            :</label>
                                                    </div>
                                                    <div class="col-lg-7">
                                                        <select class="selectpicker show-tick form-control" data-size="5"
                                                                data-live-search="true" data-live-search-style="startsWith"
                                                                title="Warehouses Associated" name="warehousesAssociated[]"
                                                                multiple>
                                                            @foreach($listWarehouses as $warehouse )
                                                                @php($list[]=null)
                                                                @foreach($warehousesAssociated as $warehouseA)

                                                                    @if($warehouse->name==$warehouseA->name)
                                                                        @php($option='selected')
                                                                        <option {{$option}}>{{$warehouse->name}}</option>
                                                                        @php($list[]=$warehouse)
                                                                    @endif
                                                                @endforeach
                                                                @if(!in_array($warehouse, $list))
                                                                    <option>{{$warehouse->name}}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>

                                                    </div>
                                                    <div class="col-lg-2 text-left">
                                                        <a href="{{route('showAddWarehouse')}}" class="link"><span
                                                                    class="glyphicon glyphicon-plus-sign"></span> Add warehouse</a>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="col-lg-3 col-lg-offset-3">
                                                        <input type="submit"
                                                               class="btn btn-primary btn-block btn-form"
                                                               value="Update"
                                                               name="updatePalletsaccount">
                                                    </div>

                                                    <div class="col-lg-3 col-lg-offset-1">
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
                                                            <button type="button" class="close" data-dismiss="modal">&times;
                                                            </button>
                                                            <h4 class="modal-title text-center">Are you sure to delete this
                                                                pallets
                                                                account ?</h4>
                                                        </div>
                                                        <div class="modal-body center">
                                                            <form method="post" action="{{route('deletePalletsaccount', $id)}}">
                                                                <input type="hidden" name="_method" value="delete">
                                                                {{ csrf_field() }}
                                                                <div class="text-center">
                                                                    <button type="submit" class="btn btn-danger btn-modal"
                                                                            value="yes"
                                                                            name="deletePalletsaccount">
                                                                        Yes
                                                                    </button>
                                                                    <button type="button" class="btn btn-success btn-modal"
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

                                            <br>
                                            <div class="table-responsive">
                                                <table class="table table-hover table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-center col1">ID <a
                                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                                    href="{{url('/detailsPalletsaccount/'.$id.'?page='.$listPalletstransfers->currentPage().'&sortby=id&order=asc')}}"></a><a
                                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                                    href="{{url('/detailsPalletsaccount/'.$id.'?page='.$listPalletstransfers->currentPage().'&sortby=id&order=desc')}}"></a></th>
                                                        <th class="text-center">Date transfer <a
                                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                                    href="{{url('/detailsPalletsaccount/'.$id.'?page='.$listPalletstransfers->currentPage().'&sortby=date&order=asc')}}"></a><a
                                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                                    href="{{url('/detailsPalletsaccount/'.$id.'?page='.$listPalletstransfers->currentPage().'&sortby=date&order=desc')}}"></a></th>
                                                        <th class="text-center">Atrnr Loading <a
                                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                                    href="{{url('/detailsPalletsaccount/'.$id.'?page='.$listPalletstransfers->currentPage().'&sortby=loading_atrnr&order=asc')}}"></a><a
                                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                                    href="{{url('/detailsPalletsaccount/'.$id.'?page='.$listPalletstransfers->currentPage().'&sortby=loading_atrnr&order=desc')}}"></a></th>
                                                        <th class="text-center">Real pallets Number <a
                                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                                    href="{{url('/detailsPalletsaccount/'.$id.'?page='.$listPalletstransfers->currentPage().'&sortby=realPalletsNumber&order=asc')}}"></a><a
                                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                                    href="{{url('/detailsPalletsaccount/'.$id.'?page='.$listPalletstransfers->currentPage().'&sortby=realPalletsNumber&order=desc')}}"></a></th>
                                                        <th class="text-center">Theorical pallets Number <a
                                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                                    href="{{url('/detailsPalletsaccount/'.$id.'?page='.$listPalletstransfers->currentPage().'&sortby=palletsNumber&order=asc')}}"></a><a
                                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                                    href="{{url('/detailsPalletsaccount/'.$id.'?page='.$listPalletstransfers->currentPage().'&sortby=palletsNumber&order=desc')}}"></a></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($listPalletstransfers as $transfer)
                                                        <tr>
                                                            <td class="text-center col1"><a href="{{route('showDetailsPalletstransfer', $transfer->id)}}" class="link">{{$transfer->id}}</a></td>
                                                            <td class="text-center">{{$transfer->date}}</td>
                                                            <td class="text-center"><a href="{{route('showDetailsLoading', $transfer->loading_atrnr)}}" class="link">{{$transfer->loading_atrnr}}</a></td>
                                                            <td class="text-center">{{$transfer->realPalletsNumber}}</td>
                                                            <td class="text-center">{{$transfer->palletsNumber}}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="row">
                                                <div class="general-pagination text-left">{!! $listPalletstransfers->render() !!}</div>

                                                @if ($listPalletstransfers->currentPage()==$listPalletstransfers->lastPage())
                                                    <div class="general-legend col-lg-offset-9">
                                                        Showing @php($legend1=1+ ($listPalletstransfers->currentPage() -1) * 5)  {{$legend1}}
                                                        to {{$count}} of {{$count}} results
                                                    </div>
                                                @elseif($listPalletstransfers->isEmpty())
                                                    <div class="general-legend col-lg-offset-9">
                                                        Showing 0 to 0 of 0 results
                                                    </div>
                                                @else
                                                    <div class="general-legend col-lg-offset-9">
                                                        Showing @php($legend1=1+ ($listPalletstransfers->currentPage() -1) * 5)  {{$legend1}}
                                                        to @php($legend2= $listPalletstransfers->currentPage() * 5) {{$legend2}} of {{$count}}
                                                        results
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                            </div>
                    </div>
            </div>
    @endif
    </div>
@endsection