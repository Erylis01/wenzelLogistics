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
                    <div class="panel-heading">Details of the truck : {{$id}} - {{ $name }}</div>
                    <div class="panel-body panel-body-general">
                        <form class="form-horizontal text-right" role="form" method="POST"
                              action="{{route('updateTruck', $id)}}">
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
                                           value="{{ $name }}" placeholder="Name" required autofocus>
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
                                           value="{{$licensePlate}}" placeholder="License Plate" autofocus>
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
                                        @foreach($listPalletsAccounts as $palletsAccount )
                                            @if(Illuminate\Support\Facades\Input::old('palletsaccount_name') && $palletsAccount->name==old('palletsaccount_name')))
                                            <option selected>{{$palletsAccount->name}}</option>
                                            @else
                                                @if($palletsAccount->name==$palletsaccount_name)
                                                    <option selected>{{$palletsAccount->name}}</option>
                                                @else
                                                    <option>{{$palletsAccount->name}}</option>
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
                                <div class="col-lg-4 col-lg-offset-3">
                                    <input type="submit"
                                           class="btn btn-primary btn-block btn-form"
                                           value="Update"
                                           name="updateTruck">
                                </div>

                                <div class="col-lg-3 col-lg-offset-1">
                                    <button type="button" class="btn btn-primary btn-block btn-form"
                                            data-toggle="modal"
                                            data-target="#deleteTruck_modal">Delete
                                    </button>
                                </div>
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
                                        <form method="post" action="{{route('deleteTruck',$id)}}">
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