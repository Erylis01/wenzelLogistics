@extends('layouts.default')

@section('title')
    Add truck
@endsection

@section('stylesheet')
    <link href="{{asset('css/truck.css')}}" rel="stylesheet" type="text/css">
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
                    <div class="panel-heading"><span class="glyphicon glyphicon-plus-sign"></span> Add a new truck
                    </div>
                    <div class="panel-body panel-body-general">
                        <form class="form-horizontal text-right" role="form" method="POST"
                              action="{{route('addTruck')}}">
                            {{ csrf_field() }}
                            <p class="text-center legend-auth">* required field</p>

                            @if(Session::has('messageErrorAddTruck'))
                                <div class="alert alert-danger text-alert text-center">{{ Session::get('messageErrorAddTruck') }}</div>
                            @endif
                            <div class="form-group">
                                <!--name-->
                                <div class="col-lg-3">
                                    <label for="name" class="control-label"><span>*</span> Name :</label>
                                </div>
                                <div class="col-lg-8">
                                    @if(isset($name))
                                        <input id="name" type="text" class="form-control" name="name"
                                               value="{{$name}}" placeholder="Name" required autofocus>
                                    @else
                                        <input id="name" type="text" class="form-control" name="name"
                                               value="{{ old('name') }}" placeholder="Name" required autofocus>
                                    @endif
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
                                    @if(isset($licensePlate))
                                        <input id="licensePlate" type="text" class="form-control" name="licensePlate"
                                               value="{{$licensePlate}}" placeholder="License Plate" autofocus>
                                    @else
                                        <input id="licensePlate" type="text" class="form-control" name="licensePlate"
                                               value="{{old('licensePlate')}}" placeholder="License Plate" autofocus>
                                    @endif
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
                                            @if(Illuminate\Support\Facades\Input::old('palletsaccount_name') && $palletsAccount->name==old('palletsaccount_name'))
                                                <option selected>{{$palletsAccount->name}}</option>
                                            @elseif(isset($palletsaccount_name)&& $palletsAccount->name==$palletsaccount_name)
                                                <option selected>{{$palletsAccount->name}}</option>
                                            @else
                                                <option>{{$palletsAccount->name}}</option>
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
                                <!--adress-->
                                <div class="col-lg-3">
                                    <label for="adress" class="control-label">Adress :</label>
                                </div>
                                <div class="col-lg-8">
                                    @if(isset($adress))
                                        <input id="adress" type="text" class="form-control" name="adress"
                                               value="{{$adress}}" placeholder="Adress" autofocus>
                                    @else
                                        <input id="adress" type="text" class="form-control" name="adress"
                                               value="{{ old('adress') }}" placeholder="Adress" autofocus>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-lg-8 col-lg-offset-3">
                                    <button type="submit" class="btn btn-primary btn-block btn-form"
                                            name="addTruck">
                                        Add
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection