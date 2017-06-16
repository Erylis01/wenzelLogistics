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
                                                    <!--nickname-->
                                                    <div class="col-lg-3">
                                                        <label for="nickname" class="control-label">Nickname :</label>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        @if(isset($nickname))
                                                        <input id="nickname" type="text" class="form-control" name="nickname"
                                                               value="{{ $nickname }}" placeholder="Nickname"
                                                               autofocus>
                                                        @else
                                                            <input id="nickname" type="text" class="form-control" name="nickname"
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
                                                                title="Type" name="type" id="type" onchange="displayFields(this);"
                                                                required>
                                                            @if(Illuminate\Support\Facades\Input::old('type'))
                                                                <option @if(old('type') == 'Carrier') selected @endif value="Carrier" id="carrierOption">
                                                                    Carrier
                                                                </option>
                                                                <option @if(old('type') == 'Network') selected @endif value="Network" id="networkOption">
                                                                    Network
                                                                </option>
                                                                <option @if(old('type') == 'Other') selected @endif value="Other">
                                                                    Other
                                                                </option>
                                                            @elseif(isset($type))
                                                                <option @if($type == 'Carrier') selected @endif value="Carrier" id="carrierOption">
                                                                    Carrier
                                                                </option>
                                                                <option @if($type == 'Network') selected @endif value="Network" id="networkOption">
                                                                    Network
                                                                </option>
                                                                <option @if($type == 'Other') selected @endif value="Other">Other
                                                                </option>
                                                            @else
                                                                <option value="Carrier" id="carrierOption">Carrier</option>
                                                                <option value="Network" id="networkOption">Network</option>
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
                                                        @if(isset($realNumberPallets))
                                                        <input id="realNumberPallets" type="number" class="form-control"
                                                               name="realNumberPallets"
                                                               value="{{ $realNumberPallets }}"
                                                               placeholder="Confirmed pal. nbr"
                                                               required readonly autofocus>
                                                            @else
                                                            <input id="realNumberPallets" type="number" class="form-control"
                                                                   name="realNumberPallets"
                                                                   value=""
                                                                   placeholder="Confirmed pal. nbr"
                                                                   required readonly autofocus>
                                                        @endif
                                                    </div>

                                                    <!--planned number of pallets-->
                                                    <div class="col-lg-3">
                                                        <label for="theoricalNumberPallets" class="control-label">Planned
                                                            Pallets Nbr
                                                            :</label>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        @if(isset($theoricalNumberPallets))
                                                        <input id="theoricalNumberPallets" type="number"
                                                               class="form-control"
                                                               name="theoricalNumberPallets"
                                                               value="{{ $theoricalNumberPallets }}"
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
                                                @if($type=='Network')
                                                    <div id="warehousesAssociated" style="display: block">
                                                    @else
                                                <div id="warehousesAssociated">
                                                    @endif
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
                                                    @if($type=='Network')
                                                        </div>
                                                            @else
                                                                </div>
                                                                    @endif

                                                @if($type=='Carrier')
                                                    <div id="trucksAssociated" style="display: block">
                                                        @else
                                                            <div id="trucksAssociated">
                                                                @endif
                                                                <div class="form-group">
                                                                    <!--trucks associated-->
                                                                    <div class="col-lg-3">
                                                                        <label for="trucksAssociated" class="control-label">Trucks
                                                                            associated
                                                                            :</label>
                                                                    </div>
                                                                    <div class="col-lg-7">
                                                                        <select class="selectpicker show-tick form-control"
                                                                                data-size="5"
                                                                                data-live-search="true"
                                                                                data-live-search-style="startsWith"
                                                                                title="Trucks Associated"
                                                                                name="trucksAssociated[]"
                                                                                multiple>
                                                                            @foreach($listTrucks as $truck )
                                                                                <option>{{$truck->name}} - {{$truck->licensePlate}}</option>
                                                                            @endforeach
                                                                                @if(Illuminate\Support\Facades\Input::old('trucksAssociated'))
                                                                                    @foreach(old('trucksAssociated') as $truckA)
                                                                                            <option selected>{{$truckA->name}}
                                                                                                - {{$truckA->licensePlate}}</option>
                                                                                    @endforeach
                                                                                @elseif(isset($trucksAssociated))
                                                                                    @foreach($trucksAssociated as $truckA)
                                                                                            <option selected>{{$truckA->name}} - {{$truckA->licensePlate}}</option>
                                                                                    @endforeach
                                                                                @endif
                                                                        </select>

                                                                    </div>
                                                                    <div class="col-lg-2 text-left">
                                                                        <a href="{{route('showAddTruck')}}" class="link"><span
                                                                                    class="glyphicon glyphicon-plus-sign"></span>
                                                                            Add truck</a>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <!--adress-->
                                                                    <div class="col-lg-3">
                                                                        <label for="adress" class="control-label">Adress :</label>
                                                                    </div>
                                                                    <div class="col-lg-7">
                                                                        @if(isset($adress))
                                                                            <input id="adress" type="text" class="form-control" name="adress"
                                                                                   value="{{ $adress }}" placeholder="Adress" autofocus>
                                                                            @else
                                                                        <input id="adress" type="text" class="form-control" name="adress"
                                                                               value="" placeholder="Adress" autofocus>
                                                                        @endif
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
                                                                        <label for="phone" class="control-label">Phone :</label>
                                                                    </div>
                                                                    <div class="col-lg-2">
                                                                        @if(isset($phone))
                                                                        <input id="phone" type="text" class="form-control" name="phone"
                                                                               value="{{$phone}}" placeholder="Phone" autofocus>
                                                                        @else
                                                                            <input id="phone" type="text" class="form-control" name="phone"
                                                                                   value="" placeholder="Phone" autofocus>
                                                                            @endif
                                                                        @if ($errors->has('phone'))
                                                                            <span class="help-block">
                                    <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                                                        @endif
                                                                    </div>
                                                                    <!--name contact-->
                                                                    <div class="col-lg-2">
                                                                        <label for="namecontact" class="control-label">Contact :</label>
                                                                    </div>
                                                                    <div class="col-lg-3">
                                                                        @if(isset($namecontact))
                                                                        <input id="namecontact" type="text" class="form-control" name="namecontact"
                                                                               value="{{$namecontact}}" placeholder="Contact name" autofocus>
                                                                        @else
                                                                            <input id="namecontact" type="text" class="form-control" name="namecontact"
                                                                                   value="" placeholder="Contact name" autofocus>
                                                                            @endif
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
                                                                        <label for="email" class="control-label">Email :</label>
                                                                    </div>
                                                                    <div class="col-lg-7">
                                                                        @if(isset($email))
                                                                        <input id="email" type="text" class="form-control" name="email"
                                                                               value="{{$email}}" placeholder="Email" autofocus>
                                                                        @else
                                                                            <input id="email" type="text" class="form-control" name="email"
                                                                                   value="" placeholder="Email" autofocus>
                                                                            @endif
                                                                        @if ($errors->has('email'))
                                                                            <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                @if($type=='Carrier')
                                                            </div>
                                                            @else
                                                    </div>
                                                @endif

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
                                            <div >
                                                <div class="col-lg-2">
                                                    <form role="form" method="GET" action="{{route('showAddPalletstransfer')}}">
                                                        {{ csrf_field() }}
                                <button type="submit" class="btn btn-add" name="addTransferAccount" value="{{$name}}"> Add transfer</button>

                                                    </form>
                                                </div>
                                                <form role="form" method="GET" action="{{route('showDetailsPalletsaccount', $id)}}">
                                                    {{ csrf_field() }}
                                                    <div class="input-group col-lg-offset-3 col-lg-7">
                                                        {{--<span class="input-group-btn searchCheckbox col-lg-offset-4">--}}
                                                        {{--<label class="checkbox-inline searchBar"><input type="checkbox" value="loading">Loading Place</label>--}}
                                                        {{--<label class="checkbox-inline searchBar"><input type="checkbox" value="offloading">Offloading Place</label>--}}
                                                        {{--</span>--}}
                            <span class="input-group-btn searchInput">
                                @if(isset($searchQuery))
                                    <input type="text" class="form-control searchBar" name="search" value="{{$searchQuery}}"
                                           placeholder="search">
                                @else
                                    <input type="text" class="form-control searchBar" name="search" value=""
                                           placeholder="search">
                                @endif
                            </span>
                                                        <span class="input-group-btn">
                                    <select class="selectpicker show-tick form-control searchSelect searchBar" data-size="5"
                                            data-live-search="true" data-live-search-style="startsWith"
                                            title="columns" name="searchColumns[]" multiple>
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
                                                    {{--</div>--}}


                                                </form>
                                                <br>
                                            </div>


                                            <!--table list loadings associated-->
                                            <div class="table-responsive table-loading-account">
                                                <table class="table table-hover table-bordered">
                                                    <thead>
                                                    <tr>
                                                        @if(isset($searchQuery))
                                                        <th class="text-center col1">Atrnr
                                                            <a
                                                            class="glyphicon glyphicon-chevron-up general-sorting"
                                                            href="{{url('/detailsPalletsaccount/'.$id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=atrnr&order=asc')}}"></a><a
                                                            class="glyphicon glyphicon-chevron-down general-sorting"
                                                            href="{{url('/detailsPalletsaccount/'.$id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=atrnr&order=desc')}}"></a>
                                                        </th>
                                                        <th class="text-center col3">Date transfer
                                                            <a
                                                            class="glyphicon glyphicon-chevron-up general-sorting"
                                                            href="{{url('/detailsPalletsaccount/'.$id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=ladedatum&order=asc')}}"></a><a
                                                            class="glyphicon glyphicon-chevron-down general-sorting"
                                                            href="{{url('/detailsPalletsaccount/'.$id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=ladedatum&order=desc')}}"></a>
                                                        </th>
                                                        <th class="text-center col4">Subfrachter
                                                            <a
                                                            class="glyphicon glyphicon-chevron-up general-sorting"
                                                            href="{{url('/detailsPalletsaccount/'.$id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=subfrachter&order=asc')}}"></a><a
                                                            class="glyphicon glyphicon-chevron-down general-sorting"
                                                            href="{{url('/detailsPalletsaccount/'.$id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=subfrachter&order=desc')}}"></a>
                                                        </th>
                                                        <th class="text-center col2">Planned pallets nbr
                                                            {{--<a--}}
                                                            {{--class="glyphicon glyphicon-chevron-up general-sorting"--}}
                                                            {{--href="{{url('/detailsPalletsaccount/'.$id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=palletsNumber&order=asc')}}"></a><a--}}
                                                            {{--class="glyphicon glyphicon-chevron-down general-sorting"--}}
                                                            {{--href="{{url('/detailsPalletsaccount/'.$id.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&sortby=palletsNumber&order=desc')}}"></a>--}}
                                                        </th>
                                                            @else
                                                            <th class="text-center col1">Atrnr
                                                                <a
                                                                        class="glyphicon glyphicon-chevron-up general-sorting"
                                                                        href="{{url('/detailsPalletsaccount/'.$id.'?sortby=atrnr&order=asc')}}"></a><a
                                                                        class="glyphicon glyphicon-chevron-down general-sorting"
                                                                        href="{{url('/detailsPalletsaccount/'.$id.'?sortby=atrnr&order=desc')}}"></a>
                                                            </th>
                                                            <th class="text-center col3">Date transfer
                                                                <a
                                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                                href="{{url('/detailsPalletsaccount/'.$id.'?sortby=ladedatum&order=asc')}}"></a><a
                                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                                href="{{url('/detailsPalletsaccount/'.$id.'?sortby=ladedatum&order=desc')}}"></a>
                                                            </th>
                                                            <th class="text-center col4">Subfrachter
                                                                <a
                                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                                href="{{url('/detailsPalletsaccount/'.$id.'?sortby=subfrachter&order=asc')}}"></a><a
                                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                                href="{{url('/detailsPalletsaccount/'.$id.'?sortby=subfrachter&order=desc')}}"></a>
                                                            </th>
                                                            <th class="text-center col2">Planned pallets nbr
                                                                {{--<a--}}
                                                                {{--class="glyphicon glyphicon-chevron-up general-sorting"--}}
                                                                {{--href="{{url('/detailsPalletsaccount/'.$id.'?sortby=palletsNumber&order=asc')}}"></a><a--}}
                                                                {{--class="glyphicon glyphicon-chevron-down general-sorting"--}}
                                                                {{--href="{{url('/detailsPalletsaccount/'.$id.'?sortby=palletsNumber&order=desc')}}"></a>--}}
                                                            </th>
                                                        @endif
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($listLoadings as $loading)
                                                        @php($idPalletsaccount=\App\Palletsaccount::where('name', trim(explode(',', $loading->subfrachter)[0]))->first()->id)
                                                        @for($k=1;$k<=$loading->numberLoadingPlace; $k++)
                                                    <tr>
                                                        <td class="text-center col1"><a class="link" href="{{route('showDetailsLoading',$loading->atrnr)}}">{{$loading->atrnr}}</a></td>
                                                        <td class="text-center col3">{{$loading->ladedatum}}</td>
                                                        <td class="text-center col4"><a class="link" href="{{route('showDetailsPalletsaccount',$idPalletsaccount)}}">{{$loading->subfrachter}}</a></td>
                                                            @php($accountDebitLoadingPlaceK='accountDebitLoadingPlace'.$k)
                                                            @php($accountCreditLoadingPlaceK='accountCreditLoadingPlace'.$k)
                                                            @php($numberPalletsLoadingPlaceK='numberPalletsLoadingPlace'.$k)
                                                            @if($name==$loading->$accountCreditLoadingPlaceK)
                                                                <td class="text-center col2">{{$loading->$numberPalletsLoadingPlaceK}}</td>
                                                                @elseif($name==$loading->$accountDebitLoadingPlaceK)
                                                                <td class="text-center col2">{{- $loading->$numberPalletsLoadingPlaceK}}</td>
                                                                @endif
                                                    </tr>
                                                            @endfor
                                                        <tr>
                                                        @for($k=1;$k<=$loading->numberOffloadingPlace; $k++)
                                                                <td class="text-center col1"><a class="link" href="{{route('showDetailsLoading',$loading->atrnr)}}">{{$loading->atrnr}}</a></td>
                                                                <td class="text-center col3">{{$loading->ladedatum}}</td>
                                                                <td class="text-center col4"><a class="link" href="{{route('showDetailsPalletsaccount',$idPalletsaccount)}}">{{$loading->subfrachter}}</a></td>
                                                            @php($accountDebitOffloadingPlaceK='accountDebitOffloadingPlace'.$k)
                                                            @php($accountCreditOffloadingPlaceK='accountCreditOffloadingPlace'.$k)
                                                            @php($numberPalletsOffloadingPlaceK='numberPalletsOffloadingPlace'.$k)
                                                            @if($name==$loading->$accountCreditOffloadingPlaceK)
                                                                <td class="text-center col2">{{$loading->$numberPalletsOffloadingPlaceK}}</td>
                                                            @elseif($name==$loading->$accountDebitOffloadingPlaceK)
                                                                <td class="text-center col2">{{- $loading->$numberPalletsOffloadingPlaceK}}</td>
                                                            @endif
                                                    </tr>
                                                        @endfor
                                                        @endforeach
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
<script type="text/javascript" src="{{asset('js/addUpdatePalletsaccount.js')}}"></script>