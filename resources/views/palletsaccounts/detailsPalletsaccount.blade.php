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
    nonActive
@endsection
@section('classPalletsAccounts')
    active
@endsection
@section('classPalletsTransfers')
    nonActive
@endsection
@section('classProfile')
    nonActive
@endsection

@section('scriptBegin')
    <script type="text/javascript" src="{{asset('js/addUpdatePalletsaccount.js')}}"></script>
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
                        {{--<div class="col-lg-9 text-left">--}}
                        Details of the account : {{$account->name}}
                        {{--</div>--}}

                        {{--@if($account->type=='Network' && isset($namewarehouses))--}}
                        {{--<div class="col-lg-1">--}}
                        {{--<a class="link"  data-toggle="modal"--}}
                        {{--data-target="#sendEmailTransfer_modal"><span class="glyphicon glyphicon-envelope"></span></a>--}}
                        {{--</div>--}}
                        {{--@endif--}}
                        {{--<div>--}}
                        {{--<button type="button" class=" btn btn-primary btn-form glyphicon glyphicon-remove"--}}
                        {{--data-toggle="modal" data-target="#deletePalletsaccount_modal"--}}
                        {{--value="{{$account->id}}" name="deletePalletsaccount_modal"></button>--}}
                        {{--</div>--}}
                    </div>
                    <div class="panel-body panel-body-general">
                        <form class="form-horizontal text-right" role="form" method="POST"
                              action="{{route('updatePalletsaccount', $account->id)}}" id="formUpdatePalletsaccount">
                            {{ csrf_field() }}
                            <input type="hidden" name="actionUpdateForm" id="actionUpdateForm"/>
                            {{--@if (Session::has('messageDeletePalletsaccount'))--}}
                            {{--<div class="alert alert-danger text-alert text-center">{{ Session::get('messageDeletePalletsaccount') }}</div>--}}
                            {{--@endif--}}
                            @if (Session::has('messageUpdatePalletsaccount'))
                                <div class="alert alert-success text-alert text-center">{{ Session::get('messageUpdatePalletsaccount') }}</div>
                            @elseif (Session::has('messageClearTrucks'))
                                <div class="alert alert-success text-alert text-center">{{ Session::get('messageClearTrucks') }}</div>
                            @endif
                            @if($account->notExchange==1)
                                <div class="form-group text-center">
                                    <p>Agreed without exchange pallets</p>
                                </div>
                            @endif

                            <div class="form-group">
                                <!--nickname-->
                                <div class="col-lg-2">
                                    <label for="nickname" class="control-label">Nickname :</label>
                                </div>
                                <div class="col-lg-5">
                                    <input id="nickname" type="text" class="form-control" name="nickname"
                                           @if(isset($account->nickname)) value="{{ $account->nickname }}"
                                           @else value="" @endif placeholder="Nickname" autofocus/>
                                    @if ($errors->has('nickname'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('nickname') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <!--type-->
                                <div class="col-lg-1">
                                    <label for="type" class="control-label">*Type :</label>
                                </div>
                                <div class="col-lg-2">
                                    <!-- if mistake in the adding form you are redirected with field already filled-->
                                    <select class="selectpicker show-tick form-control" data-size="5"
                                            data-live-search="true"
                                            data-live-search-style="startsWith" title="Type" name="type" id="type"
                                            onchange="displayFieldsUpdate(this);" required>
                                        @if(Illuminate\Support\Facades\Input::old('type') || isset($account->type))
                                            <option @if((Illuminate\Support\Facades\Input::old('type') && old('type') == 'Carrier') || (isset($account->type) && $account->type == 'Carrier')) selected
                                                    @endif value="Carrier" id="carrierOption">
                                                Carrier
                                            </option>
                                            <option @if((Illuminate\Support\Facades\Input::old('type') && old('type') == 'Network') || (isset($account->type) && $account->type == 'Network')) selected
                                                    @endif value="Network" id="networkOption">
                                                Network
                                            </option>
                                            <option @if((Illuminate\Support\Facades\Input::old('type') && old('type') == 'Other') || (isset($account->type) && $account->type == 'Other')) selected
                                                    @endif value="Other" id="otherOption">
                                                Other
                                            </option>
                                        @else
                                            <option value="Carrier" id="carrierOption">Carrier
                                            </option>
                                            <option value="Network" id="networkOption">Network
                                            </option>
                                            <option value="Other" id="otherOption">Other</option>
                                        @endif
                                    </select>
                                </div>
                                    <div class="col-lg-2 text-center" id="truckAsso"  @if($account->type=='Carrier') style="display: block;" @else style="display: none;" @endif>
                                        <a href="{{route('showAddTruck', ['originalPage'=>'detailsPalletsaccount-'.$account->id])}}"
                                           class="link"><span class="glyphicon glyphicon-plus-sign"></span>
                                            Truck</a>
                                    </div>
                            </div>

                            <div id="warehousesAssociated" @if($account->type=='Network') style="display: block"
                                 @else style="display:none;" @endif>
                                <div class="form-group">
                                    <!--warehouses associated-->
                                    <div class="col-lg-2">
                                        <label for="namewarehouses" class="control-label">
                                            @if(isset($namewarehouses))
                                                <a class="link" data-toggle="modal" data-target="#warehouses_modal">
                                                    Warehouses :</a>
                                            @else Warehouses :@endif</label>
                                    </div>

                                    <div class="col-lg-8">
                                        <select class="selectpicker show-tick form-control"
                                                data-size="5" data-live-search="true"
                                                data-live-search-style="startsWith"
                                                title="Warehouses Associated" name="namewarehouses[]" id="select-warehouses" multiple>
                                            @foreach($listWarehouses as $warehouse )
                                                @php($list[]=null)
                                                @if(Illuminate\Support\Facades\Input::old('namewarehouses'))
                                                    @foreach(old('namewarehouses') as $warehouseA)
                                                        @if($warehouseA == $warehouse->nickname)
                                                            <option selected>{{$warehouse->nickname}}</option>
                                                            @php($list[]=$warehouse)
                                                        @endif
                                                    @endforeach
                                                    @if(!in_array($warehouse, $list))
                                                        <option>{{$warehouse->nickname}}</option>
                                                    @endif
                                                @elseif(isset($namewarehouses))
                                                    @foreach($namewarehouses as $warehouseA)
                                                        @if($warehouseA == $warehouse->nickname)
                                                            <option selected>{{$warehouse->nickname}}</option>
                                                            @php($list[]=$warehouse)
                                                        @endif
                                                    @endforeach
                                                    @if(!in_array($warehouse, $list))
                                                        <option>{{$warehouse->nickname}}</option>
                                                    @endif
                                                @else
                                                    <option>{{$warehouse->nickname}}</option>
                                                @endif
                                            @endforeach
                                        </select>

                                    </div>
                                    <div class="col-lg-2 text-left">
                                        <a href="{{route('showAddWarehouse', ['originalPage'=>'detailsPalletsaccount-'.$account->id])}}"
                                           class="link">
                                            <span class="glyphicon glyphicon-plus-sign"></span>
                                            Warehouse</a>
                                    </div>
                                </div>
                            </div>

                            <div id="trucksAssociated" @if($account->type=='Carrier') style="display: block"
                                 @else style="display:none;" @endif>
                                @if(isset($trucksActivated))
                                    <div class="panel panel-carrierAccount">
                                        <div class="panel-heading text-left">
                                            <a data-toggle="collapse" href="#trucksAcitvated"
                                               onclick="openClosePanelTrucksActivated();"><span
                                                        id="trucksActivatedPanelLogo"
                                                        class="glyphicon glyphicon-menu-down"></span> Trucks
                                                activated ({{count($trucksActivated)}})</a>
                                        </div>
                                        <div id="trucksAcitvated" class="panel-collapse collapse">
                                            <div class="panel-body-general panel-body">
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-bordered table-truckActivated">
                                                        <thead>
                                                        <tr>
                                                            <th class="text-center">Desactivate</th>
                                                            <th class="text-center">License plate</th>
                                                            <th class="text-center">Confirmed<br> pallets nbr
                                                            </th>
                                                            <th class="text-center">Planned<br> pallets nbr</th>
                                                            <th class="text-center">Rest<br> to confirm</th>
                                                            <th class="text-center">Debt<br></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($trucksActivated as $truck)
                                                            <tr>
                                                                <td class="text-center">
                                                                    <button type="submit" name="desactivate"
                                                                            value="{{$truck->id}}"
                                                                            class="btn-activated btn">
                                                                        <span class="glyphicon glyphicon-remove"></span>
                                                                    </button>
                                                                </td>
                                                                <td class="text-center"><a
                                                                            href="{{route('showDetailsTruck', $truck->id)}}"
                                                                            class="link">{{$truck->licensePlate}}</a>
                                                                </td>
                                                                <td class="text-center">{{$truck->realNumberPallets}}</td>
                                                                <td class="text-center">{{$truck->theoricalNumberPallets}}</td>
                                                                <td class="text-center ">
                                                                    <strong>{{$truck->theoricalNumberPallets-$truck->realNumberPallets}}</strong>
                                                                </td>
                                                                <td class="text-center">{{$truck->palletsDebt}}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if(isset($trucksInactivated))
                                    <div class="panel panel-carrierAccount">
                                        <div class="panel-heading text-left">
                                            <a data-toggle="collapse" href="#trucksInacitvated"
                                               onclick="openClosePanelTrucksInactivated();"><span
                                                        id="trucksInactivatedPanelLogo"
                                                        class="glyphicon glyphicon-menu-down"></span> Trucks inactivated
                                                ({{count($trucksInactivated)}})</a>
                                        </div>
                                        <div id="trucksInacitvated" class="panel-collapse collapse">
                                            <div class="panel-body-general panel-body">
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-bordered table-truckActivated">
                                                        <thead>
                                                        <tr>
                                                            <th class="text-center">Activate</th>
                                                            <th class="text-center">License plate</th>
                                                            <th class="text-center">Confirmed<br> pallets nbr
                                                            </th>
                                                            <th class="text-center">Planned<br> pallets nbr</th>
                                                            <th class="text-center">Rest<br> to confirm</th>
                                                            <th class="text-center">Debt<br></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($trucksInactivated as $truck)
                                                            <tr>
                                                                <td class="text-center">
                                                                    <button type="submit" name="activate"
                                                                            value="{{$truck->id}}"
                                                                            class="btn-activated btn">
                                                                        <span class="glyphicon glyphicon-ok"></span>
                                                                    </button>
                                                                </td>
                                                                <td class="text-center"><a
                                                                            href="{{route('showDetailsTruck', $truck->id)}}"
                                                                            class="link">{{$truck->licensePlate}}</a>
                                                                </td>
                                                                <td class="text-center">{{$truck->realNumberPallets}}</td>
                                                                <td class="text-center">{{$truck->theoricalNumberPallets}}</td>
                                                                <td class="text-center ">
                                                                    <strong>{{$truck->theoricalNumberPallets-$truck->realNumberPallets}}</strong>
                                                                </td>
                                                                <td class="text-center">{{$truck->palletsDebt}}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="panel panel-carrierAccount">
                                    <div class="panel-heading text-left">
                                        <a data-toggle="collapse" href="#contactInfos"
                                           onclick="openClosePanelContactInfos();"><span id="contactInfosPanelLogo"
                                                                                         class="glyphicon glyphicon-menu-down"></span>
                                            Contact information</a>
                                    </div>
                                    <div id="contactInfos" class="panel-collapse collapse">
                                        <div class="panel-body-general panel-body">
                                            <div class="form-group">
                                                <!--adress-->
                                                <div class="col-lg-2">
                                                    <label for="adress" class="control-label">Adress :</label>
                                                </div>
                                                <div class="col-lg-10">
                                                    <input id="adress" type="text"
                                                           class="form-control" name="adress"
                                                           @if(isset($account->adress)) value="{{ $account->adress }}"
                                                           @else value="" @endif placeholder="Adress" autofocus>
                                                    @if ($errors->has('adress'))
                                                        <span class="help-block">
                                                <strong>{{ $errors->first('adress') }}</strong>
                                            </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <!--country-->
                                                <div class="col-lg-3 col-lg-offset-2">
                                                    <input id="country" type="text" class="form-control" name="country"
                                                           @if(isset($account->country)) value="{{$account->country}}"
                                                           @else value="{{old('country')}}" @endif placeholder="Country"
                                                           data-toggle="tooltip" data-placement="top" title="Country"
                                                           autofocus/>
                                                </div>
                                                <!--zipcode-->
                                                <div class="col-lg-3">
                                                    <input id="zipcode" type="number" min="0" class="form-control"
                                                           name="zipcode"
                                                           @if(isset($account->zipcode)) value="{{$account->zipcode}}"
                                                           @else value="{{old('zipcode')}}" @endif placeholder="Zipcode"
                                                           data-toggle="tooltip" data-placement="top" title="Zipcode"
                                                           autofocus/>
                                                </div>
                                                <!--town-->
                                                <div class="col-lg-4">
                                                    <input id="town" type="text" class="form-control" name="town"
                                                           @if(isset($account->town)) value="{{$account->town}}"
                                                           @else value="{{old('town')}}" @endif placeholder="Town"
                                                           data-toggle="tooltip" data-placement="top" title="Town"
                                                           autofocus/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                @if ($errors->has('country'))
                                                    <span class="help-block text-center">
                                    <strong>{{ $errors->first('country') }}</strong>
                                    </span>
                                                @endif
                                                @if ($errors->has('zipcode'))
                                                    <span class="help-block text-center">
                                    <strong>{{ $errors->first('zipcode') }}</strong>
                                    </span>
                                                @endif
                                                @if ($errors->has('town'))
                                                    <span class="help-block text-center">
                                    <strong>{{ $errors->first('town') }}</strong>
                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <!--phone-->
                                                <div class="col-lg-2">
                                                    <label for="phone" class="control-label">Phone :</label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <input id="phone" type="text" class="form-control" name="phone"
                                                           @if(isset($account->phone)) value="{{$account->phone}}"
                                                           @else value=""
                                                           @endif placeholder="Phone" autofocus>
                                                    @if ($errors->has('phone'))
                                                        <span class="help-block">
                                    <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                                    @endif
                                                </div>
                                                <!--fax-->
                                                <div class="col-lg-2">
                                                    <label for="fax" class="control-label">Fax / Mobile :</label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <input id="fax" type="text" class="form-control" name="fax"
                                                           @if(isset($account->fax)) value="{{$account->fax}}"
                                                           @else value=""
                                                           @endif placeholder="Fax / Mobile" autofocus>
                                                    @if ($errors->has('fax'))
                                                        <span class="help-block">
                                    <strong>{{ $errors->first('fax') }}</strong>
                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <!--email-->
                                                <div class="col-lg-2">
                                                    <label for="email" class="control-label">Email :</label>
                                                </div>
                                                <div class="col-lg-10">
                                                    <input id="email" type="text" class="form-control" name="email"
                                                           @if(isset($account->email)) value="{{$account->email}}"
                                                           @else value=""
                                                           @endif placeholder="Email" autofocus>
                                                    @if ($errors->has('email'))
                                                        <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <!--name contact-->
                                                <div class="col-lg-2">
                                                    <label for="details" class="control-label">Details :</label>
                                                </div>
                                                <div class="col-lg-10">
                                        <textarea rows="2" id="details"  class="form-control"
                                                  name="details"
                                                  placeholder="Details (contact name, job, ...)"
                                                  autofocus>@if(isset($account->details)) {{$account->details}} @endif</textarea>
                                                    @if ($errors->has('details'))
                                                        <span class="help-block">
                                    <strong>{{ $errors->first('details') }}</strong>
                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{--@if($account->type=='Network')--}}
                            {{--<div class="col-lg-1">--}}
                            {{--<a class="link"  data-toggle="modal"--}}
                            {{--data-target="#sendEmailTransfer_modal"><span class="glyphicon glyphicon-envelope"></span></a>--}}
                            {{--</div>--}}
                            {{--@endif--}}


                            <div class="form-group">
                                <div class="col-lg-4 col-lg-offset-4">
                                    <input type="submit" class="btn btn-primary btn-block btn-form"
                                           value="Update" name="updatePalletsaccount" id="updatePalletsaccount"
                                           onclick="formUpdateSubmitBlock(this);"/>
                                </div>

                            </div>

                            <div class="form-group">
                                <div class="col-lg-6 col-lg-offset-3 table-responsive">
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                        <tr>
                                            <th class="text-center">Confirmed<br> pallets nbr
                                            </th>
                                            <th class="text-center">Planned<br> pallets nbr</th>
                                            <th class="text-center">Rest<br> to confirm</th>
                                            <th class="text-center">Debt<br></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td class="text-center">{{$account->realNumberPallets}}</td>
                                            <td class="text-center">{{$account->theoricalNumberPallets}}</td>
                                            <td class="text-center ">
                                                <strong>{{$account->theoricalNumberPallets-$account->realNumberPallets}}</strong>
                                            </td>
                                            <td class="text-center">{{$account->palletsDebt}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </form>
                        <!-- Modal Delete -->
                    {{--<div class="modal fade" id="deletePalletsaccount_modal" role="dialog">--}}
                    {{--<div class="modal-dialog modal-sm">--}}
                    {{--<div class="modal-content">--}}
                    {{--<div class="modal-header">--}}
                    {{--<button type="button" class="close" data-dismiss="modal">--}}
                    {{--&times;--}}
                    {{--</button>--}}
                    {{--<h4 class="modal-title text-center">Are you sure to delete--}}
                    {{--this pallets account ?</h4>--}}
                    {{--</div>--}}
                    {{--<div class="modal-body center">--}}
                    {{--<form method="post" action="{{route('deletePalletsaccount', $account->id)}}"--}}
                    {{--id="formDeletePalletsaccount">--}}
                    {{--<input type="hidden" name="_method" value="delete"/>--}}
                    {{--{{ csrf_field() }}--}}
                    {{--<input type="hidden" name="actionDeleteForm" id="actionDeleteForm"/>--}}
                    {{--<div class="text-center">--}}
                    {{--<button type="submit" class="btn btn-danger btn-modal"--}}
                    {{--value="deletePalletsaccount" name="deletePalletsaccount"--}}
                    {{--id="deletePalletsaccount"--}}
                    {{--onclick="formDeleteSubmitBlock(this);">--}}
                    {{--Yes--}}
                    {{--</button>--}}
                    {{--<button type="button" class="btn btn-success btn-modal"--}}
                    {{--data-dismiss="modal">No--}}
                    {{--</button>--}}
                    {{--</div>--}}
                    {{--</form>--}}
                    {{--</div>--}}
                    {{--<div class="modal-footer">--}}
                    {{--<button type="button" class="btn btn-default btn-modal"--}}
                    {{--data-dismiss="modal">--}}
                    {{--Close--}}
                    {{--</button>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}

                    <!--search bar-->
                        <br>
                        <form class="form-horizontal" role="form" method="GET"
                              action="{{route('showDetailsPalletsaccount', $account->id)}}">
                            {{ csrf_field() }}
                            <div class="col-lg-3 text-center checkbox">
                                <label><input type="checkbox" name="debt" value="debt" id="debt" checked
                                              onchange="displayRowsTable();"/>With debt transfers</label>
                            </div>
                            <div class="form-group col-lg-5">
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
                        </form>
                        <br>
                        <div class=" row">
                            <form class="form-horizontal text-right" role="form" method="POST"
                                  action="{{route('updatePalletsaccount', $account->id)}}" id="formClearPalletsaccount">
                                {{ csrf_field() }}
                                <input type="hidden" name="actionClearForm" id="actionClearForm"/>
                                <div class="form-group">
                                    <div class="col-lg-2 col-lg-offset-3">
                                        <a href="{{route('showAddPalletstransfer', ['originalPage'=>'detailsPalletsaccount-'.$account->id])}}" class="link">
                                            <span class="glyphicon glyphicon-plus-sign"></span>
                                            Transfer</a>
                                    </div>

                                    <div id="buttonClearTrucks" class="col-lg-2 col-lg-offset-2" @if($account->type=='Carrier') style="display:block" @else style="display:none" @endif>
                                        <input type="submit" class="btn btn-primary btn-block btn-form"
                                               value="Clear trucks" name="clearTrucks" id="clearTrucks"
                                               onclick="formClearSubmitBlock(this);"/>
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
                                        <th class="colDanger text-center">Errors</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($listTransfers as $transfer)
                                        <tr @if($transfer->state=="Untreated"&& $transfer->type=='Debt') class="untreated debt"
                                            @elseif($transfer->state=="Untreated" )class="untreated"
                                            @elseif($transfer->state=="Waiting documents" && $transfer->type=='Debt') class="waitingdocuments debt"
                                            @elseif($transfer->state=="Waiting documents" ) class="waitingdocuments"
                                            @elseif ($transfer->state=="Complete" && $transfer->type=='Debt') class="complete debt"
                                            @elseif ($transfer->state=="Complete") class="complete"
                                            @elseif ($transfer->state=="Complete Validated") class="completevalidated debt"
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
                                                @if(isset($creditAccount) && substr($creditAccount, 0, strlen($account->nickname)) === $account->nickname)
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
                                                    @if(isset($debitAccount) && substr($debitAccount, 0, strlen($account->nickname)) === $account->nickname)
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
                                                @php($k=0)
                                                @php($errorsTransfer= \App\Http\Controllers\PalletstransfersController::actualErrors($transfer))
                                                    @foreach($errorsTransfer as $errorT)
                                                        @if(!empty($errorT)&& $k<2)
                                                            <span class="glyphicon glyphicon-warning-sign text-danger"
                                                                  data-toggle="tooltip"
                                                                  title="{{$errorT->name}}"></span>
                                                            @php($k=$k+1)
                                                        @elseif(!empty($errorT)&& $k==2)
                                                            <span class="text-danger">...</span>
                                                            @php($k=$k+1)
                                                        @endif
                                                    @endforeach
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Modal warehouses -->
                        <div class="modal fade" id="warehouses_modal" role="dialog">
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
                                            <button type="button" class="btn btn-default btn-modal"
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
@endsection

@section('scriptEnd')
    <script type="text/javascript" src="{{asset('js/addUpdatePalletsaccount.js')}}"></script>
@endsection
