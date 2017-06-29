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
            <div class="col-lg-10 col-lg-offset-1">

                <div class="panel panel-general">
                    <div class="panel-heading">
                        <div class="col-lg-11 text-left">Details of the truck : {{$truck->id}} - {{ $truck->name }}
                        </div>
                        <div>
                            <button type="button"
                                    class=" btn btn-primary btn-form glyphicon glyphicon-remove"
                                    data-toggle="modal"
                                    data-target="#deleteTruck_modal"
                                    value="{{$truck->id}}"
                                    name="deleteTruck_modal"
                            ></button>
                        </div>
                    </div>
                    <div class="panel-body panel-body-general">
                        <form class="form-horizontal text-right" role="form" method="POST"
                              action="{{route('updateTruck', $truck->id)}}">
                            {{ csrf_field() }}
                            <p class="text-center legend-auth">* required field</p>

                            @if(Session::has('messageErrorUpdateTruck'))
                                <div class="alert alert-danger text-alert text-center">{{ Session::get('messageErrorUpdateTruck') }}</div>
                            @elseif (Session::has('messageUpdateTruck'))
                                <div class="alert alert-success text-alert text-center">{{ Session::get('messageUpdateTruck') }}</div>
                            @endif

                            <div class="form-group">
                                <!--name-->
                                <div class="col-lg-3">
                                    <label for="name" class="control-label"><span>*</span> Name :</label>
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
                                <div class="col-lg-3">
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
                                <div class="col-lg-3">
                                    <label for="palletsaccount_name" class="control-label"><span>*</span> Pallets
                                        Account
                                        :</label>
                                </div>
                                <div class="col-lg-6">
                                    <!-- if mistake in the adding form you are redirected with field already filled-->
                                    <select class="selectpicker show-tick form-control" data-size="5"
                                            data-live-search="true" data-live-search-style="startsWith"
                                            title="Pallets Account" name="palletsaccount_name"
                                            required>
                                        @foreach($listPalletsAccounts as $palletsA )
                                            @if(Illuminate\Support\Facades\Input::old('palletsaccount_name') && $palletsA->name==old('palletsaccount_name'))
                                                )
                                                <option selected>{{$palletsA->name}}</option>
                                            @else
                                                @if($palletsA->name==$truck->palletsaccount_name)
                                                    <option selected>{{$palletsA->name}}</option>
                                                @else
                                                    <option>{{$palletsA->name}}</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-3 text-left">
                                    <a href="{{route('showAddPalletsaccount')}}" class="link"><span
                                                class="glyphicon glyphicon-plus-sign"></span> Add account</a>
                                </div>
                            </div>
                            <div class="form-group">
                                <!---->
                                <div class="col-lg-6">
                                    <label for="info" class="control-label"><a class="link"
                                                                               href="{{route('showDetailsPalletsaccount',$palletsaccount->id)}}"><span
                                                    class="glyphicon glyphicon-info-sign"></span> Contact
                                            information</a></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-8 col-lg-offset-3 table-responsive">
                                    <table class="table table-hover table-bordered">
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
                                <div class="col-lg-4 col-lg-offset-5">
                                    <input type="submit"
                                           class="btn btn-primary btn-block btn-form"
                                           value="Update"
                                           name="updateTruck">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-lg-2 addTransfer">
                                    <a href="{{route('showAddPalletstransfer')}}"
                                       class="link"><span
                                                class="glyphicon glyphicon-plus-sign"></span>Add transfer</a>
                                </div>
                                <form role="form" method="GET"
                                      action="{{route('showDetailsTruck', $truck->id)}}">
                                    {{ csrf_field() }}
                                    <div class="input-group col-lg-offset-3 col-lg-7">
                            <span class="input-group-btn searchInput">
                                @if(isset($searchQuery))
                                    <input type="text" class="form-control searchBar" name="search"
                                           value="{{$searchQuery}}"
                                           placeholder="search">
                                @else
                                    <input type="text" class="form-control searchBar" name="search" value=""
                                           placeholder="search">
                                @endif
                            </span>
                                        <span class="input-group-btn">
                                    <select class="selectpicker show-tick form-control searchSelect searchBar"
                                            data-size="5"
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
                                        @if(isset($searchQuery))
                                            <th class="text-center colID">ID<br>
                                                <a
                                                        class="glyphicon glyphicon-chevron-up general-sorting"
                                                        href="{{url('/detailsPalletsaccount/'.$truck->id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=id&order=asc')}}"></a><a
                                                        class="glyphicon glyphicon-chevron-down general-sorting"
                                                        href="{{url('/detailsPalletsaccount/'.$truck->id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=id&order=desc')}}"></a>
                                            </th>
                                            <th class="text-center colType">Type<br>
                                                <a
                                                        class="glyphicon glyphicon-chevron-up general-sorting"
                                                        href="{{url('/detailsPalletsaccount/'.$truck->id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=type&order=asc')}}"></a><a
                                                        class="glyphicon glyphicon-chevron-down general-sorting"
                                                        href="{{url('/detailsPalletsaccount/'.$truck->id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=type&order=desc')}}"></a>
                                            </th>
                                            <th class="text-center colNumber">Pallets nbr<br>
                                                <a
                                                        class="glyphicon glyphicon-chevron-up general-sorting"
                                                        href="{{url('/detailsPalletsaccount/'.$truck->id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=palletsNumber&order=asc')}}"></a><a
                                                        class="glyphicon glyphicon-chevron-down general-sorting"
                                                        href="{{url('/detailsPalletsaccount/'.$truck->id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=palletsNumber&order=desc')}}"></a>
                                            </th>
                                            <th class="text-center colAtrnr">Atrnr<br>
                                                <a
                                                        class="glyphicon glyphicon-chevron-up general-sorting"
                                                        href="{{url('/detailsPalletsaccount/'.$truck->id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=loading_atrnr&order=asc')}}"></a><a
                                                        class="glyphicon glyphicon-chevron-down general-sorting"
                                                        href="{{url('/detailsPalletsaccount/'.$truck->id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=loading_atrnr&order=desc')}}"></a>
                                            </th>
                                            <th class="text-center colDate">Date<br>
                                                <a
                                                        class="glyphicon glyphicon-chevron-up general-sorting"
                                                        href="{{url('/detailsPalletsaccount/'.$truck->id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=date&order=asc')}}"></a><a
                                                        class="glyphicon glyphicon-chevron-down general-sorting"
                                                        href="{{url('/detailsPalletsaccount/'.$truck->id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=date&order=desc')}}"></a>
                                            </th>
                                        @else
                                            <th class="text-center colID">ID<br>
                                                <a
                                                        class="glyphicon glyphicon-chevron-up general-sorting"
                                                        href="{{url('/detailsPalletsaccount/'.$truck->id.'?sortby=id&order=asc')}}"></a><a
                                                        class="glyphicon glyphicon-chevron-down general-sorting"
                                                        href="{{url('/detailsPalletsaccount/'.$truck->id.'?sortby=id&order=desc')}}"></a>
                                            </th>
                                            <th class="text-center colType">Type<br>
                                                <a
                                                        class="glyphicon glyphicon-chevron-up general-sorting"
                                                        href="{{url('/detailsPalletsaccount/'.$truck->id.'?sortby=type&order=asc')}}"></a><a
                                                        class="glyphicon glyphicon-chevron-down general-sorting"
                                                        href="{{url('/detailsPalletsaccount/'.$truck->id.'?sortby=type&order=desc')}}"></a>
                                            </th>
                                            <th class="text-center colNumber">Pallets nbr<br>
                                                <a
                                                        class="glyphicon glyphicon-chevron-up general-sorting"
                                                        href="{{url('/detailsPalletsaccount/'.$truck->id.'?sortby=palletsNumber&order=asc')}}"></a><a
                                                        class="glyphicon glyphicon-chevron-down general-sorting"
                                                        href="{{url('/detailsPalletsaccount/'.$truck->id.'?sortby=palletsNumber&order=desc')}}"></a>
                                            </th>
                                            <th class="text-center colAtrnr">Atrnr<br>
                                                <a
                                                        class="glyphicon glyphicon-chevron-up general-sorting"
                                                        href="{{url('/detailsPalletsaccount/'.$truck->id.'?sortby=loading_atrnr&order=asc')}}"></a><a
                                                        class="glyphicon glyphicon-chevron-down general-sorting"
                                                        href="{{url('/detailsPalletsaccount/'.$truck->id.'?sortby=loading_atrnr&order=desc')}}"></a>
                                            </th>
                                            <th class="text-center colDate">Date<br>
                                                <a
                                                        class="glyphicon glyphicon-chevron-up general-sorting"
                                                        href="{{url('/detailsPalletsaccount/'.$truck->id.'?sortby=date&order=asc')}}"></a><a
                                                        class="glyphicon glyphicon-chevron-down general-sorting"
                                                        href="{{url('/detailsPalletsaccount/'.$truck->id.'?sortby=date&order=desc')}}"></a>
                                            </th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($listTransfers as $transfer)
                                        {{--@php($idDebitAccount=\App\Palletsaccount::where('name', $transfer->debitAccount)->first()->id)--}}
                                        {{--@php($idCreditAccount=\App\Palletsaccount::where('name', $transfer->creditAccount)->first()->id)--}}
                                        @if($transfer->state=="In progress")
                                            @php($class="inprogress")
                                        @elseif ($transfer->state=="Waiting documents")
                                            @php($class="waitingdocuments")
                                        @elseif ($transfer->state=="Complete")
                                            @php($class="complete")
                                        @else
                                            @php($class="completevalidated")
                                        @endif
                                        <tr class="{{$class}}">
                                            <td class="text-center colID"><a class="link"
                                                                             href="{{route('showDetailsPalletstransfer',$transfer->id)}}">{{$transfer->id}}</a>
                                            </td>
                                            <td class="text-center colType">{{$transfer->type}}</td>
                                            <td class="text-center colNumber">{{$transfer->palletsNumber}}</td>
                                            {{--@if($transfer->type=='Deposit')--}}
                                            {{--<td class="text-center col4"><a class="link"--}}
                                            {{--href="{{route('showDetailsPalletsaccount',$idDebitAccount)}}">{{$transfer->debitAccount}}</a>--}}
                                            {{--</td>--}}
                                            {{--@elseif($transfer->type=='Withdrawal')--}}
                                            {{--<td class="text-center col4"><a class="link"--}}
                                            {{--href="{{route('showDetailsPalletsaccount',$idCreditAccount)}}">{{$transfer->creditAccount}}</a>--}}
                                            {{--</td>--}}
                                            {{--@endif--}}
                                            <td class="text-center colAtrnr"><a class="link"
                                                                               href="{{route('showDetailsLoading',$transfer->loading_atrnr)}}">{{$transfer->loading_atrnr}}</a>
                                            </td>
                                            <td class="text-center colDate">{{date('d-m-y', strtotime($transfer->date))}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </form>

                        <!-- Modal Delete -->
                        <div class="modal fade" id="deleteTruck_modal" role="dialog">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title text-center">Are you sure to delete this truck ?</h4>
                                    </div>
                                    <div class="modal-body center">
                                        <form method="post" action="{{route('deleteTruck',$truck->id)}}">
                                            <input type="hidden" name="_method" value="delete">
                                            {{ csrf_field() }}
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-danger btn-modal" value="yes"
                                                        name="deleteTruck">
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

                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection