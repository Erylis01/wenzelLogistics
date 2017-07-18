@extends('layouts.default')

@section('title')
    Truck details
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

@section('scriptBegin')
    <script type="text/javascript" src="{{asset('js/addUpdateTruck.js')}}">
    </script>
@endsection

@section('content')
    <div class="row">
        @if(Auth::guest())
            <h4>You need to login to see the content</h4>
        @else
            <div class="col-lg-12">

                <div class="panel panel-general">
                    <div class="panel-heading">
                        <div class="col-lg-11 text-left">Details of the truck : {{$truck->id}} - {{ $truck->name }}
                        </div>
                        <div>
                            <button type="button" class=" btn btn-primary btn-form glyphicon glyphicon-remove"
                                    data-toggle="modal" data-target="#deleteTruck_modal" value="{{$truck->id}}"
                                    name="deleteTruck_modal"></button>
                        </div>
                    </div>
                    <div class="panel-body panel-body-general">
                        <form class="form-horizontal text-right" role="form" method="POST"
                              action="{{route('updateTruck', $truck->id)}}" id="formUpdateTruck">
                            {{ csrf_field() }}
                            <input type="hidden" name="actionUpdateForm" id="actionUpdateForm"/>
                            <p class="text-center legend-auth">* required field</p>

                            @if(Session::has('messageErrorUpdateTruck'))
                                <div class="alert alert-danger text-alert text-center">{{ Session::get('messageErrorUpdateTruck') }}</div>
                            @elseif (Session::has('messageUpdateTruck'))
                                <div class="alert alert-success text-alert text-center">{{ Session::get('messageUpdateTruck') }}</div>
                            @endif

                            <div class="form-group">
                                <!--name-->
                                <div class="col-lg-2">
                                    <label for="name" class="control-label">*Name :</label>
                                </div>
                                <div class="col-lg-8">
                                    <input id="name" type="text" class="form-control" name="name"
                                           value="{{ $truck->name }}" placeholder="Name" required autofocus>
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <!--license plate-->
                                <div class="col-lg-2">
                                    <label for="licensePlate" class="control-label">License Plate :</label>
                                </div>
                                <div class="col-lg-8">
                                    <input id="licensePlate" type="text" class="form-control" name="licensePlate"
                                           value="{{$truck->licensePlate}}" placeholder="License Plate" autofocus>
                                    @if ($errors->has('licensePlate'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('licensePlate') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <!--pallet account associated-->
                                <div class="col-lg-2">
                                    <label for="palletsaccount_name" class="control-label">*Pallets Account :</label>
                                </div>
                                <div class="col-lg-6">
                                    <!-- if mistake in the adding form you are redirected with field already filled-->
                                    <select class="selectpicker show-tick form-control" data-size="5"
                                            data-live-search="true" data-live-search-style="startsWith"
                                            title="Pallets Account" name="palletsaccount_name"
                                            required>
                                        @foreach($listPalletsAccounts as $palletsA )
                                            @if((Illuminate\Support\Facades\Input::old('palletsaccount_name') && $palletsA->name==old('palletsaccount_name'))||($palletsA->name==$truck->palletsaccount_name))
                                                <option selected>{{$palletsA->name}}</option>
                                            @else
                                                <option>{{$palletsA->name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2 text-left">
                                    <a class="link"
                                       href="{{route('showDetailsPalletsaccount',$palletsaccount->id)}}">
                                        <span class="glyphicon glyphicon-user"> </span>
                                        <span class="glyphicon glyphicon-envelope"> </span>
                                        <span class="glyphicon glyphicon-phone"> </span>
                                    </a>
                                </div>

                                <div class="col-lg-2 text-left">
                                    <a href="{{route('showAddPalletsaccount', ['originalPage'=>'detailsTruck-'.$truck->id])}}"
                                       class="link">
                                        <span class="glyphicon glyphicon-plus-sign"></span> Add account</a>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-lg-6 col-lg-offset-3 table-responsive">
                                    <table class="table table-hover table-bordered table-truck">
                                        <thead>
                                        <tr>
                                            <th class="text-center">Confirmed<br> pallets nbr
                                            </th>
                                            <th class="text-center">Planned<br> pallets nbr</th>
                                            <th class="text-center">Rest<br> to confirm</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td class="text-center">{{$truck->realNumberPallets}}</td>
                                            <td class="text-center">{{$truck->theoricalNumberPallets}}</td>
                                            <td class="text-center ">
                                                <strong>{{$truck->theoricalNumberPallets-$truck->realNumberPallets}}</strong>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <div class="form-group">
                                <div class="col-lg-4 col-lg-offset-4">
                                    <input type="submit" class="btn btn-primary btn-block btn-form"
                                           value="Update" name="updateTruck" id="updateTruck"
                                           onclick="formUpdateSubmitBlock(this);"/>
                                </div>
                            </div>
                        </form>
                        <div class="form-group">
                            <div class="col-lg-2 addTransfer">
                                <a href="{{route('showAddPalletstransfer')}}" class="link">
                                    <span class="glyphicon glyphicon-plus-sign"></span>
                                    Add transfer</a>
                            </div>
                            <!-- search bar-->
                            <form role="form" method="GET"
                                  action="{{route('showDetailsTruck', $truck->id)}}">
                                {{ csrf_field() }}
                                <div class="input-group col-lg-offset-3 col-lg-7">
                            <span class="input-group-btn searchInput">
                                <input type="text" class="form-control searchBar" name="search"
                                       @if(isset($searchQuery)) value="{{$searchQuery}}" @else value=""
                                       @endif placeholder="search"/>
                            </span>
                                    <span class="input-group-btn">
                                    <select class="selectpicker show-tick form-control searchSelect searchBar"
                                            data-size="5"
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
                                <button class="btn glyphicon glyphicon-search searchBar" type="submit"
                                        name="searchSubmit"></button>
                            </span>
                                </div>
                            </form>
                            <br>
                        </div>

                        <!--table list transfers associated-->
                        <div class="table-responsive table-transfers">
                            <table class="table table-hover table-bordered">
                                <thead>
                                <tr>
                                    <th class="text-center colID">ID<br>
                                        <a class="glyphicon glyphicon-chevron-up general-sorting"
                                           @if(isset($searchQuery)) href="{{url('/detailsPalletsaccount/'.$truck->id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=id&order=asc')}}"
                                           @else href="{{url('/detailsPalletsaccount/'.$truck->id.'?sortby=id&order=asc')}}"
                                                @endif></a>
                                        <a class="glyphicon glyphicon-chevron-down general-sorting"
                                           @if(isset($searchQuery)) href="{{url('/detailsPalletsaccount/'.$truck->id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=id&order=desc')}}"
                                           @else href="{{url('/detailsPalletsaccount/'.$truck->id.'?sortby=id&order=desc')}}"
                                                @endif></a>
                                    </th>
                                    <th class="text-center colType">Type<br>
                                        <a class="glyphicon glyphicon-chevron-up general-sorting"
                                           @if(isset($searchQuery)) href="{{url('/detailsPalletsaccount/'.$truck->id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=type&order=asc')}}"
                                           @else href="{{url('/detailsPalletsaccount/'.$truck->id.'?sortby=type&order=asc')}}"
                                                @endif></a>
                                        <a class="glyphicon glyphicon-chevron-down general-sorting"
                                           @if(isset($searchQuery)) href="{{url('/detailsPalletsaccount/'.$truck->id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=type&order=desc')}}"
                                           @else href="{{url('/detailsPalletsaccount/'.$truck->id.'?sortby=type&order=desc')}}"
                                                @endif></a>
                                    </th>
                                    <th class="text-center colNumber">Pal. nbr<br>
                                        <a class="glyphicon glyphicon-chevron-up general-sorting"
                                           @if(isset($searchQuery)) href="{{url('/detailsPalletsaccount/'.$truck->id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=palletsNumber&order=asc')}}"
                                           @else href="{{url('/detailsPalletsaccount/'.$truck->id.'?sortby=palletsNumber&order=asc')}}"
                                                @endif></a>
                                        <a class="glyphicon glyphicon-chevron-down general-sorting"
                                           @if(isset($searchQuery)) href="{{url('/detailsPalletsaccount/'.$truck->id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=palletsNumber&order=desc')}}"
                                           @else href="{{url('/detailsPalletsaccount/'.$truck->id.'?sortby=palletsNumber&order=desc')}}"
                                                @endif></a>
                                    </th>
                                    <th class="text-center colAtrnr">Atrnr<br>
                                        <a class="glyphicon glyphicon-chevron-up general-sorting"
                                           @if(isset($searchQuery)) href="{{url('/detailsPalletsaccount/'.$truck->id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=loading_atrnr&order=asc')}}"
                                           @else href="{{url('/detailsPalletsaccount/'.$truck->id.'?sortby=loading_atrnr&order=asc')}}"
                                                @endif></a>
                                        <a class="glyphicon glyphicon-chevron-down general-sorting"
                                           @if(isset($searchQuery)) href="{{url('/detailsPalletsaccount/'.$truck->id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=loading_atrnr&order=desc')}}"
                                           @else href="{{url('/detailsPalletsaccount/'.$truck->id.'?sortby=loading_atrnr&order=desc')}}"
                                                @endif></a>
                                    </th>
                                    <th class="text-center colDate">Date<br>
                                        <a class="glyphicon glyphicon-chevron-up general-sorting"
                                           @if(isset($searchQuery)) href="{{url('/detailsPalletsaccount/'.$truck->id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=date&order=asc')}}"
                                           @else href="{{url('/detailsPalletsaccount/'.$truck->id.'?sortby=date&order=asc')}}"
                                                @endif></a>
                                        <a class="glyphicon glyphicon-chevron-down general-sorting"
                                           @if(isset($searchQuery)) href="{{url('/detailsPalletsaccount/'.$truck->id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=date&order=desc')}}"
                                           @else href="{{url('/detailsPalletsaccount/'.$truck->id.'?sortby=date&order=desc')}}"
                                                @endif></a>
                                    </th>
                                    <th class="text-center colDanger2"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($listTransfers as $transfer)
                                    <tr @if($transfer->state=="Untreated") class="untreated"
                                        @elseif ($transfer->state=="Waiting documents") class="waitingdocuments"
                                        @elseif ($transfer->state=="Complete") class="complete"
                                        @else class="completevalidated" @endif>
                                        <td class="text-center colID"><a class="link"
                                                                         href="{{route('showDetailsPalletstransfer',$transfer->id)}}">{{$transfer->id}}</a>
                                        </td>
                                        <td class="text-center colType">{{$transfer->type}}</td>
                                        <td class="text-center colNumber">{{$transfer->palletsNumber}}</td>
                                        <td class="text-center colAtrnr"><a class="link"
                                                                            href="{{route('showDetailsLoading',$transfer->loading_atrnr)}}">{{$transfer->loading_atrnr}}</a>
                                        </td>
                                        <td class="text-center colDate">{{date('d-m-y', strtotime($transfer->date))}}</td>
                                        <td class="colDanger2">
                                            {{--@php($listPalletstransfers=\App\Palletstransfer::where('creditAccount','LIKE', $truck->name.'-'.$truck->licensePlate.'%')->orWhere('debitAccount','LIKE', $truck->name.'-'.$truck->licensePlate.'%')->get())--}}
                                            @php($k=0)
                                            @php($errorsTransfer= \App\Http\Controllers\PalletstransfersController::actualErrors($transfer))
                                            @if(!empty($errorsTransfer)&&$k<2)
                                                <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                            @elseif(!empty($errorsTransfer) && $k==2)
                                                <span class="text-danger">...</span>
                                            @endif
                                            @php($k=$k+1)

                                            {{--@foreach($listPalletstransfers as $transfer)--}}
                                            {{--@php($errorsTransfer= \App\Http\Controllers\PalletstransfersController::actualErrors($transfer))--}}
                                            {{--@if(!empty($errorsTransfer)&& $k<2)--}}
                                            {{--<span class="glyphicon glyphicon-warning-sign text-danger" data-toggle="tooltip" title="{{$errorTrans->name}}"></span>--}}
                                            {{--@elseif(!empty($errorsTransfer)&& $k==2)--}}
                                            {{--<span class="text-danger">...</span>--}}
                                            {{--@endif--}}
                                            {{--@php($k=$k+1)--}}
                                            {{--@endforeach--}}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>


                        <!-- Modal Delete -->
                        <div class="modal fade" id="deleteTruck_modal" role="dialog">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title text-center">Are you sure to delete this truck ?</h4>
                                    </div>
                                    <div class="modal-body center">
                                        <form method="post" action="{{route('deleteTruck',$truck->id)}}"
                                              id="formDeleteTruck">
                                            <input type="hidden" name="_method" value="delete">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="actionDeleteForm" id="actionDeleteForm"/>
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-danger btn-modal"
                                                        value="deleteTruck" name="deleteTruck" id="deleteTruck"
                                                        onclick="formDeleteSubmitBlock(this);">
                                                    Yes
                                                </button>
                                                <button type="button" class="btn btn-success btn-modal"
                                                        data-dismiss="modal">
                                                    No
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default btn-modal" data-dismiss="modal">
                                            Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
@section('scriptEnd')
    <script type="text/javascript" src="{{asset('js/addUpdateTruck.js')}}">
    </script>
@endsection
