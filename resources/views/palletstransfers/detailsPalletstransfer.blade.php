@extends('layouts.default')

@section('title')
    Pallets transfer details
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
                @if($state=="OK")
                    <div class="panel panel-general">
                        @elseif($state=="almost OK")
                            <div class="panel panel-warning">
                                @elseif ($state=="not OK")
                                    <div class="panel panel-danger">
                                        @else
                                            <div class="panel panel-default">
                                                @endif
                                                <div class="panel-heading">Details of the pallets transfer
                                                    n° {{$id}}</div>

                                                <div class="panel-body panel-body-general">
                                                    <form class="form-horizontal text-right" role="form" method="POST"
                                                          action="{{route('updatePalletstransfer', $id)}}">
                                                        {{ csrf_field() }}
                                                        <div class="form-group">
                                                            <!--date-->
                                                            <div class="col-lg-2">
                                                                <label for="date" class="control-label">Date :</label>
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <input id="date" type="date" class="form-control"
                                                                       name="date"
                                                                       value="{{$date}}" placeholder="Date" required autofocus>
                                                                @if ($errors->has('date'))
                                                                    <span class="help-block">
                                        <strong>{{ $errors->first('date') }}</strong>
                                    </span>
                                                                @endif
                                                            </div>

                                                            <!--loading reference-->
                                                            <div class="col-lg-3">
                                                                <label for="loadingRef" class="control-label">Loading
                                                                    reference :</label>
                                                            </div>
                                                            <div class="col-lg-5">
                                                                <input id="loadingRef" type="text" class="form-control"
                                                                       name="loadingRef"
                                                                       value="{{$loadingRef}}" placeholder="Loading reference" required
                                                                       autofocus>
                                                                @if ($errors->has('loadingRef'))
                                                                    <span class="help-block">
                                        <strong>{{ $errors->first('loadingRef') }}</strong>
                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <!--number of pallets-->
                                                            <div class="col-lg-2">
                                                                <label for="palletsNumber" class="control-label">Pallets
                                                                    number
                                                                    :</label>
                                                            </div>
                                                            <div class="col-lg-1">
                                                                <input id="palletsNumber" type="number"
                                                                       class="form-control"
                                                                       name="palletsNumber"
                                                                       value="{{$palletsNumber}}" placeholder="Pallets Number"
                                                                       required autofocus>
                                                            </div>

                                                            <!--pallets account-->
                                                            <div class="col-lg-2 col-lg-offset-2">
                                                                <label for="warehousesAssociated" class="control-label">Pallets
                                                                    account
                                                                    :</label>
                                                            </div>
                                                            <div class="col-lg-5">

                                                                <select class="selectpicker show-tick form-control"
                                                                        data-size="5"
                                                                        data-live-search="true"
                                                                        data-live-search-style="startsWith"
                                                                        title="Pallets Account" name="palletsAccount"
                                                                        required>
                                                                    @foreach($listPalletsaccounts as $account )
                                                                        @if($account->name==$palletsAccount)
                                                                            @php($option='selected')
                                                                        <option {{$option}}>{{$account->name}}</option>
                                                                        @else
                                                                            <option>{{$account->name}}</option>
                                                                            @endif
                                                                    @endforeach
                                                                </select>

                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                        <div class="col-lg-2 col-lg-offset-7 text-left">
                                                            <a href="{{route('showAddPalletsaccount')}}" class="link"><span
                                                                        class="glyphicon glyphicon-plus-sign"></span> Add account</a>
                                                        </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-lg-4 col-lg-offset-1">
                                                                <input type="submit"
                                                                       class="btn btn-primary btn-block btn-form"
                                                                       value="Update"
                                                                       name="updatePalletstransfer">
                                                            </div>

                                                            <div class="col-lg-3 col-lg-offset-3">
                                                                <button type="button"
                                                                        class="btn btn-primary btn-block btn-form"
                                                                        data-toggle="modal"
                                                                        data-target="#deletePalletstransfer_modal">
                                                                    Delete
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <!-- Modal Delete -->
                                                    <div class="modal fade" id="deletePalletstransfer_modal"
                                                         role="dialog">
                                                        <div class="modal-dialog modal-sm">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close"
                                                                            data-dismiss="modal">&times;
                                                                    </button>
                                                                    <h4 class="modal-title text-center">Are you sure to
                                                                        delete this
                                                                        pallets
                                                                        transfer ?</h4>
                                                                </div>
                                                                <div class="modal-body center">
                                                                    <form method="post"
                                                                          action="{{route('deletePalletstransfer', $id)}}">
                                                                        <input type="hidden" name="_method"
                                                                               value="delete">
                                                                        {{ csrf_field() }}
                                                                        <div class="text-center">
                                                                            <button type="submit"
                                                                                    class="btn btn-danger btn-modal"
                                                                                    value="yes"
                                                                                    name="deletePalletstransfer">
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
                                                                    <button type="button"
                                                                            class="btn btn-default btn-modal"
                                                                            data-dismiss="modal">
                                                                        Close
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if(Session::has('messageUpdatePalletstransfer'))
                                                        <div class="alert alert-success text-alert text-center">{{ Session::get('messageUpdatePalletstransfer') }}</div>
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