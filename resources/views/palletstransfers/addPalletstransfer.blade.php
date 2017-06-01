@extends('layouts.default')

@section('title')
    Add pallets transfers
@endsection

@section('stylesheet')
    <link href="{{asset('css/palletstransfers.css')}}" rel="stylesheet" type="text/css">
@endsection

@section('classLoadings')
    class="nonActive"
@endsection
@section('classWarehouses')
    class="nonActive"
@endsection
@section('classPalletsAccounts')
    class="nonActive"
@endsection
@section('classPalletsTransfers')
    class="active"
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
                <div class="panel panel-general">
                    <div class="panel-heading"><span class="glyphicon glyphicon-plus-sign"></span> Add a new pallets
                        transfer
                    </div>
                    <div class="panel-body panel-body-general">
                        <form class="form-horizontal text-right" role="form" method="POST"
                              action="{{route('addPalletstransfer')}}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <!--date-->
                                <div class="col-lg-2">
                                    <label for="date" class="control-label">Date :</label>
                                </div>
                                <div class="col-lg-2">
                                    <input id="date" type="date" class="form-control" name="date"
                                           value="" placeholder="Date" required autofocus>
                                    @if ($errors->has('date'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('date') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <!--loading atrnr-->
                                <div class="col-lg-3">
                                    <label for="loading_atrnr" class="control-label">Loading atrnr :</label>
                                </div>
                                <div class="col-lg-5">
                                    <input id="loading_atrnr" type="number" min="0" class="form-control" name="loading_atrnr"
                                           value="" placeholder="Loading atrnr" required autofocus>
                                    @if ($errors->has('loading_atrnr'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('loading_atrnr') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <!--number of pallets-->
                                <div class="col-lg-2">
                                    <label for="palletsNumber" class="control-label">Pallets number
                                        :</label>
                                </div>
                                <div class="col-lg-1">
                                    <input id="palletsNumber" type="number" class="form-control"
                                           name="palletsNumber"
                                           value="0" placeholder="Pallets Number"
                                           required autofocus>
                                </div>

                                <!--pallets account-->
                                <div class="col-lg-2 col-lg-offset-2">
                                    <label for="warehousesAssociated" class="control-label">Pallets
                                        account
                                        :</label>
                                </div>
                                <div class="col-lg-5">
                                    <select class="selectpicker show-tick form-control" data-size="5"
                                            data-live-search="true" data-live-search-style="startsWith"
                                            title="Pallets Account" name="palletsAccount" required>
                                        @foreach($listPalletsaccounts as $account )
                                            <option>{{$account->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-lg-3 col-lg-offset-2">
                                    <input type="submit"
                                           class="btn btn-primary btn-block btn-form"
                                           value="Add"
                                           name="addPalletstransfer">
                                </div>
                                <div class="col-lg-2 col-lg-offset-2 text-left">
                                    <a href="{{route('showAddPalletsaccount')}}" class="link"><span
                                                class="glyphicon glyphicon-plus-sign"></span> Add account</a>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
                @endif
            </div>
    </div>
@endsection