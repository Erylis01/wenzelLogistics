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
@section('classTrucks')
    class="nonActive"
@endsection
@section('classPalletsAccounts')
    active
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

                <div @if($account->realNumberPallets==0||$account->realNumberPallets==null) class="panel panelInprogress"
                     @elseif($account->realNumberPallets>0) class="panel panel-general"
                     @else class="panel panelUntreated" @endif>
                    <div class="panel-heading">
                        <div class="@if($account->type=='Network' && isset($namewarehouses)) col-lg-9 @else col-lg-11 @endif text-left">
                            Details of the account n° {{$account->id}}
                            : {{$account->name}}
                        </div>
                        {{--@if($account->type=='Network' && isset($namewarehouses))--}}
                        {{--<div class="col-lg-1">--}}
                        {{--<a class="link"  data-toggle="modal"--}}
                        {{--data-target="#sendEmailTransfer_modal"><span class="glyphicon glyphicon-envelope"></span></a>--}}
                        {{--</div>--}}
                        {{--@endif--}}
                        <div>
                            <button type="button"
                                    class=" btn btn-primary btn-form glyphicon glyphicon-remove"
                                    data-toggle="modal"
                                    data-target="#deletePalletsaccount_modal"
                                    value="{{$account->id}}"
                                    name="deletePalletsaccount_modal"
                            ></button>
                        </div>
                    </div>
                    <div class="panel-body panel-body-general">
                        <form class="form-horizontal text-right" role="form" method="POST"
                              action="{{route('updatePalletsaccount', $account->id)}}">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <!--nickname-->
                                <div class="col-lg-3">
                                    <label for="nickname" class="control-label">Nickname :</label>
                                </div>
                                <div class="col-lg-4">
                                    @if(isset($account->nickname))
                                        <input id="nickname" type="text" class="form-control"
                                               name="nickname"
                                               value="{{ $account->nickname }}"
                                               placeholder="Nickname"
                                               autofocus>
                                    @else
                                        <input id="nickname" type="text" class="form-control"
                                               name="nickname"
                                               value="" placeholder="Nickname"
                                               autofocus>
                                    @endif
                                    @if ($errors->has('nickname'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('nickname') }}</strong>
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
                                            title="Type" name="type" id="type"
                                            onchange="displayFields(this);"
                                            required>
                                        @if(Illuminate\Support\Facades\Input::old('type') || isset($account->type))
                                            <option @if(old('type') == 'Carrier' || $account->type == 'Carrier') selected
                                                    @endif value="Carrier" id="carrierOption">
                                                Carrier
                                            </option>
                                            <option @if(old('type') == 'Network' || $account->type == 'Network') selected
                                                    @endif value="Network" id="networkOption">
                                                Network
                                            </option>
                                            <option @if(old('type') == 'Other' || $account->type == 'Other') selected
                                                    @endif value="Other">
                                                Other
                                            </option>
                                        @else
                                            <option value="Carrier" id="carrierOption">Carrier
                                            </option>
                                            <option value="Network" id="networkOption">Network
                                            </option>
                                            <option value="Other">Other</option>
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
                                    <input id="realNumberPallets" type="number"
                                           class="form-control"
                                           name="realNumberPallets"
                                           @if(isset($account->realNumberPallets)) value="{{ $account->realNumberPallets }}"
                                           @else value="" @endif
                                           placeholder="Confirmed pal. nbr"
                                           required readonly autofocus>
                                </div>

                                <!--planned number of pallets-->
                                <div class="col-lg-3">
                                    <label for="theoricalNumberPallets" class="control-label">Planned
                                        Pallets Nbr
                                        :</label>
                                </div>
                                <div class="col-lg-2">
                                    @if(isset($account->theoricalNumberPallets))
                                        <input id="theoricalNumberPallets" type="number"
                                               class="form-control"
                                               name="theoricalNumberPallets"
                                               value="{{ $account->theoricalNumberPallets }}"
                                               placeholder="Planned pal. nbr"
                                               readonly required autofocus>
                                    @else
                                        <input id="theoricalNumberPallets" type="number"
                                               class="form-control"
                                               name="theoricalNumberPallets"
                                               value=""
                                               placeholder="Planned pal. nbr"
                                               readonly required autofocus>
                                    @endif
                                </div>
                            </div>

                            <div id="warehousesAssociated" @if($account->type=='Network') style="display: block" @endif>
                                <div class="form-group">
                                    <!--warehouses associated-->
                                    <div class="col-lg-3">
                                        <label for="namewarehouses"
                                               class="control-label"><a class="link" data-toggle="modal"
                                                                        data-target="#warehouses_modal">Warehouses
                                                associated
                                                :</a></label>
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
                                        <a href="{{route('showAddWarehouse')}}"
                                           class="link"><span
                                                    class="glyphicon glyphicon-plus-sign"></span>
                                            Add warehouse</a>
                                    </div>
                                </div>
                            </div>

                            <div id="trucksAssociated" @if($account->type=='Carrier') style="display: block" @endif>
                                <div class="form-group">
                                    <!--trucks associated-->
                                    <div class="col-lg-3">
                                        <label for="trucksAssociated"
                                               class="control-label">Trucks
                                            associated
                                            :</label>
                                    </div>
                                    <div class="col-lg-7">
                                        <ul>
                                            @if(isset($trucksAssociated))
                                                @foreach($trucksAssociated as $truck)
                                                    <li class="text-left"><a
                                                                href="{{route('showDetailsTruck', $truck->id)}}"
                                                                class="link">{{$truck->licensePlate}}</a>
                                                        - {{$truck->realNumberPallets}}
                                                        - {{$truck->theoricalNumberPallets}}
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                    <div class="col-lg-2 text-left">
                                        <a href="{{route('showAddTruck')}}"
                                           class="link"><span
                                                    class="glyphicon glyphicon-plus-sign"></span>
                                            Add truck</a>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <!--adress-->
                                    <div class="col-lg-3">
                                        <label for="adress" class="control-label">Adress
                                            :</label>
                                    </div>
                                    <div class="col-lg-7">
                                        <input id="adress" type="text"
                                               class="form-control" name="adress"
                                               @if(isset($account->adress)) value="{{ $account->adress }}"
                                               @else value="" @endif
                                               placeholder="Adress" autofocus>
                                        @if ($errors->has('adress'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('adress') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <!--phone-->
                                    <div class="col-lg-3">
                                        <label for="phone" class="control-label">Phone
                                            :</label>
                                    </div>
                                    <div class="col-lg-2">
                                        <input id="phone" type="text"
                                               class="form-control" name="phone"
                                               @if(isset($account->phone)) value="{{$account->phone}}" @else value=""
                                               @endif
                                               placeholder="Phone" autofocus>
                                        @if ($errors->has('phone'))
                                            <span class="help-block">
                                    <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                    <!--name contact-->
                                    <div class="col-lg-2">
                                        <label for="namecontact" class="control-label">Contact
                                            :</label>
                                    </div>
                                    <div class="col-lg-3">

                                        <input id="namecontact" type="text"
                                               class="form-control"
                                               name="namecontact"
                                               @if(isset($account->namecontact)) value="{{$account->namecontact}}"
                                               @else value="" @endif
                                               placeholder="Contact name" autofocus>
                                        @if ($errors->has('namecontact'))
                                            <span class="help-block">
                                    <strong>{{ $errors->first('namecontact') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <!--email-->
                                    <div class="col-lg-3">
                                        <label for="email" class="control-label">Email
                                            :</label>
                                    </div>
                                    <div class="col-lg-7">
                                        <input id="email" type="text"
                                               class="form-control" name="email"
                                               @if(isset($account->email)) value="{{$account->email}}" @else value=""
                                               @endif
                                               placeholder="Email" autofocus>
                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                    {{--@if($account->type=='Network')--}}
                                    {{--<div class="col-lg-1">--}}
                                    {{--<a class="link"  data-toggle="modal"--}}
                                    {{--data-target="#sendEmailTransfer_modal"><span class="glyphicon glyphicon-envelope"></span></a>--}}
                                    {{--</div>--}}
                                    {{--@endif--}}
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-lg-4 col-lg-offset-4">
                                    <input type="submit"
                                           class="btn btn-primary btn-block btn-form"
                                           value="Update"
                                           name="updatePalletsaccount">
                                </div>

                            </div>
                            @if (Session::has('messageUpdatePalletsaccount'))
                                <div class="alert alert-success text-alert text-center">{{ Session::get('messageUpdatePalletsaccount') }}</div>
                            @elseif (Session::has('messageClearTrucks'))
                                <div class="alert alert-success text-alert text-center">{{ Session::get('messageClearTrucks') }}</div>
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
                                              action="{{route('deletePalletsaccount', $account->id)}}">
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
                        <div class="row">
                            <form role="form" method="GET"
                                  action="{{route('showDetailsPalletsaccount', $account->id)}}">
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
                        </div>
                        <br>
                        <div class=" row">
                            <form class="form-horizontal text-right" role="form" method="POST"
                                  action="{{route('updatePalletsaccount', $account->id)}}">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <div class="col-lg-2 col-lg-offset-3">
                                        <a href="{{route('showAddPalletstransfer')}}"
                                           class="link"><span
                                                    class="glyphicon glyphicon-plus-sign"></span>Add
                                            transfer</a>
                                    </div>
                                    <div class="col-lg-2 col-lg-offset-2">
                                        <input type="submit"
                                               class="btn btn-primary btn-block btn-form"
                                               value="Clear trucks"
                                               name="clearTrucks">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <br>
                        <!--table list transfers associated-->
                        <div class="row">
                            <div class="table-responsive table-loading-account @if($account->type<>'Carrier') notCarrier @endif">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                    <tr>
                                        <th class="text-center colID">ID<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/detailsPalletsaccount/'.$account->id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=id&order=asc')}}"
                                               @else href="{{url('/detailsPalletsaccount/'.$account->id.'?sortby=id&order=asc')}}"
                                                    @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/detailsPalletsaccount/'.$account->id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=id&order=desc')}}"
                                               @else href="{{url('/detailsPalletsaccount/'.$account->id.'?sortby=id&order=desc')}}"
                                                    @endif></a>
                                        </th>
                                        <th class="text-center colType">Type<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/detailsPalletsaccount/'.$account->id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=type&order=asc')}}"
                                               @else href="{{url('/detailsPalletsaccount/'.$account->id.'?sortby=type&order=asc')}}"
                                                    @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/detailsPalletsaccount/'.$account->id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=type&order=desc')}}"
                                               @else href="{{url('/detailsPalletsaccount/'.$account->id.'?sortby=type&order=desc')}}"
                                                    @endif></a>
                                        </th>
                                        <th class="text-center colPNumb">Pal. nbr<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/detailsPalletsaccount/'.$account->id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=palletsNumber&order=asc')}}"
                                               @else href="{{url('/detailsPalletsaccount/'.$account->id.'?sortby=palletsNumber&order=asc')}}"
                                                    @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/detailsPalletsaccount/'.$account->id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=palletsNumber&order=desc')}}"
                                               @else href="{{url('/detailsPalletsaccount/'.$account->id.'?sortby=palletsNumber&order=desc')}}"
                                                    @endif></a>
                                        </th>
                                        @if($account->type=='Carrier')
                                            <th class="text-center colTruck">Truck<br>
                                                <a class="glyphicon glyphicon-chevron-up general-sorting"
                                                   @if(isset($searchQuery)) href="{{url('/detailsPalletsaccount/'.$account->id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=licensePlate&order=asc')}}"
                                                   @else href="{{url('/detailsPalletsaccount/'.$account->id.'?sortby=licensePlate&order=asc')}}"
                                                        @endif></a>
                                                <a class="glyphicon glyphicon-chevron-down general-sorting"
                                                   @if(isset($searchQuery)) href="{{url('/detailsPalletsaccount/'.$account->id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=licensePlate&order=desc')}}"
                                                   @else href="{{url('/detailsPalletsaccount/'.$account->id.'?sortby=licensePlate&order=desc')}}"
                                                        @endif></a>
                                            </th>
                                        @endif
                                        <th class="text-center colType">Atrnr<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/detailsPalletsaccount/'.$account->id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=loading_atrnr&order=asc')}}"
                                               @else href="{{url('/detailsPalletsaccount/'.$account->id.'?sortby=loading_atrnr&order=asc')}}"
                                                    @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/detailsPalletsaccount/'.$account->id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=loading_atrnr&order=desc')}}"
                                               @else href="{{url('/detailsPalletsaccount/'.$account->id.'?sortby=loading_atrnr&order=desc')}}"
                                                    @endif></a>
                                        </th>
                                        <th class="text-center colDate">Date<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/detailsPalletsaccount/'.$account->id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=date&order=asc')}}"
                                               @else href="{{url('/detailsPalletsaccount/'.$account->id.'?sortby=date&order=asc')}}"
                                                    @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/detailsPalletsaccount/'.$account->id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=date&order=desc')}}"
                                               @else href="{{url('/detailsPalletsaccount/'.$account->id.'?sortby=date&order=desc')}}"
                                                    @endif></a>
                                        </th>
                                        <th class="colDanger"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($listTransfers as $transfer)
                                        <tr @if($transfer->state=="Untreated") class="untreated"
                                            @elseif($transfer->state=="Waiting documents") class="waitingdocuments"
                                            @elseif ($transfer->state=="Complete") class="complete"
                                            @else class="completevalidated" @endif>
                                            <td class="text-center colID"><a class="link"
                                                                             href="{{route('showDetailsPalletstransfer',$transfer->id)}}">{{$transfer->id}}</a>
                                            </td>
                                            <td class="text-center colType">{{$transfer->type}}</td>
                                            <td class="text-center colPNumb">{{$transfer->palletsNumber}}</td>
                                            @if($account->type=='Carrier')
                                                @php($partsCreditAccount=explode('-',$transfer->creditAccount))
                                                @php($a=$partsCreditAccount[count($partsCreditAccount)-1])
                                                @php($b=$partsCreditAccount[count($partsCreditAccount)-2])
                                                @if(count(array_diff ($partsCreditAccount, [$a, $b]))==1)
                                                    @php($creditAccount=array_diff ($partsCreditAccount, [$a, $b])[0])
                                                @else
                                                    @php($creditAccount=implode(' - ', array_diff ($partsCreditAccount, [$a, $b])))
                                                @endif
                                                @if(isset($creditAccount) && substr($creditAccount, 0, strlen($account->name)) === $account->name)
                                                    <td class="text-center colTruck">{{$creditAccount}}</td>
                                                @else
                                                    @php($partsDebitAccount=explode('-',$transfer->debitAccount))
                                                    @php($aprim=$partsDebitAccount[count($partsDebitAccount)-1])
                                                    @php($bprim=$partsDebitAccount[count($partsDebitAccount)-2])
                                                    @if(count(array_diff ($partsDebitAccount, [$aprim, $bprim]))==1)
                                                        @php($debitAccount=array_diff ($partsDebitAccount, [$aprim, $bprim])[0])
                                                    @else
                                                        @php($debitAccount=implode( ' - ', array_diff ($partsDebitAccount, [$aprim, $bprim])))
                                                    @endif
                                                    @if(isset($debitAccount) && substr($debitAccount, 0, strlen($account->name)) === $account->name)
                                                        <td class="text-center colTruck">{{$debitAccount}}</td>
                                                    @else
                                                        <td class="text-center colTruck"></td>
                                                    @endif
                                                @endif
                                            @endif
                                            <td class="text-center colType"><a class="link"
                                                                               href="{{route('showDetailsLoading',$transfer->loading_atrnr)}}">{{$transfer->loading_atrnr}}</a>
                                            </td>
                                            <td class="text-center colDate">{{date('d-m-y', strtotime($transfer->date))}}</td>
                                            <td class="colDanger">
                                                @php($listPalletstransfers=\App\Palletstransfer::where('creditAccount','LIKE', $account->name.'-'.'%')->orWhere('debitAccount','LIKE', $account->name.'-'.'%')->get())
                                                @php($k=0)
                                                @foreach($listPalletstransfers as $transfer)
                                                    @php($errorsTransfer= \App\Http\Controllers\PalletstransfersController::actualErrors($transfer))
                                                    @foreach($errorsTransfer as $errorT)
                                                        @if(!empty($errorT)&& $k<2)
                                                            <span class="glyphicon glyphicon-warning-sign text-danger" data-toggle="tooltip" title="{{$errorT->name}}"></span>
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
                        </div>

                        <!-- Modal warehouses -->
                        <div class="modal fade" id="warehouses_modal"
                             role="dialog">
                            <div class="modal-dialog modal-md">
                                <div class="modal-content">
                                    @if($account->type=='Network' && isset($namewarehouses))
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;
                                            </button>
                                            <h4 class="modal-title text-center">List of warehouses</h4>
                                        </div>
                                        <div class="modal-body">
                                            @foreach($namewarehouses as $nameW)
                                                <li>
                                                    @php($idW=\App\Warehouse::where('name', $nameW)->first()->id)
                                                    <a href="{{route('showDetailsWarehouse', $idW)}}"
                                                       class="link">{{$nameW}}</a>
                                                </li>
                                            @endforeach
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button"
                                                    class="btn btn-default btn-modal"
                                                    data-dismiss="modal">
                                                Close
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>


                        {{--<!-- Modal Send Email -->--}}
                        {{--<div class="modal fade" id="sendEmailTransfer_modal"--}}
                        {{--role="dialog">--}}
                        {{--<div class="modal-dialog modal-md">--}}
                        {{--<div class="modal-content">--}}
                        {{--@if($account->type=='Network' && isset($namewarehouses))--}}
                        {{--<div class="modal-header">--}}
                        {{--<button type="button" class="close" data-dismiss="modal">&times;--}}
                        {{--</button>--}}
                        {{--<h4 class="modal-title text-center">Choose the right warehouse to contact people </h4>--}}
                        {{--</div>--}}
                        {{--<div class="modal-body">--}}
                        {{--@foreach($namewarehouses as $warehouse)--}}
                        {{--<li>--}}
                        {{--<a href="{{route('showDetailsWarehouse', $warehouse->id)}}"--}}
                        {{--class="link">{{$warehouse->name}}</a>--}}
                        {{--</li>--}}
                        {{--@endforeach--}}
                        {{--</div>--}}
                        {{--<div class="modal-footer">--}}
                        {{--<button type="button"--}}
                        {{--class="btn btn-default btn-modal"--}}
                        {{--data-dismiss="modal">--}}
                        {{--Close--}}
                        {{--</button>--}}
                        {{--</div>--}}
                        {{--@elseif($account->type=='Carrier')--}}
                        {{--<div class="modal-header">--}}
                        {{--<button type="button" class="close" data-dismiss="modal">&times;--}}
                        {{--</button>--}}
                        {{--<h4 class="modal-title text-center">Choose the right warehouse to contact people </h4>--}}
                        {{--</div>--}}
                        {{--<form class="form-horizontal text-right" role="form" method="POST"--}}
                        {{--action="{{route('updatePalletsaccount', $account->id)}}">--}}
                        {{--{{ csrf_field() }}--}}
                        {{--<div class="modal-body">--}}



                        {{--</div>--}}
                        {{--<div class="modal-footer">--}}
                        {{--<button type="submit"--}}
                        {{--class="btn btn-success btn-modal"--}}
                        {{--value="sendEmail" name="sendEmail">--}}
                        {{--Close--}}
                        {{--</button>--}}
                        {{--<button type="button"--}}
                        {{--class="btn btn-default btn-modal"--}}
                        {{--data-dismiss="modal">--}}
                        {{--Close--}}
                        {{--</button>--}}
                        {{--</div>--}}
                        {{--</form>--}}
                        {{--@endif--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{----}}
                    </div>
                </div>
            </div>
        @endif
    </div>
    <script type="text/javascript" src="{{asset('js/addUpdatePalletsaccount.js')}}"></script>
@endsection
