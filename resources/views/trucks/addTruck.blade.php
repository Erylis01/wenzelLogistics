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
    active
@endsection
@section('classPalletsAccounts')
    nonActive
@endsection
@section('classPalletsTransfers')
    nonActive
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
            <div class="col-lg-10 col-lg-offset-1">
                <div class="panel panel-general">
                    <div class="panel-heading"><span class="glyphicon glyphicon-plus-sign"></span> Add truck
                    </div>
                    <div class="panel-body panel-body-general">
                        <form class="form-horizontal text-right" role="form" method="POST"
                              action="{{route('addTruck')}}" id="formAddTruck">
                            <input type="hidden" name="actionAddForm" id="actionAddForm"/>
                            {{ csrf_field() }}
                            <input type="hidden" name="originalPage" id="originalPage" @if(isset($originalPage))value="{{$originalPage}}" @endif/>
                            <input type="hidden" name="atrnr" id="atrnr" @if(isset($atrnr))value="{{$atrnr}}" @endif/>

                            @if(Session::has('messageErrorAddTruck'))
                                <div class="alert alert-danger text-alert text-center">{{ Session::get('messageErrorAddTruck') }}</div>
                            @endif


                            <div class="form-group">
                                <!--pallet account associated-->
                                <div class="col-lg-3">
                                    <label for="palletsaccount_name" class="control-label">Carrier Account :</label>
                                </div>
                                <div class="col-lg-6">
                                    <!-- if mistake in the adding form you are redirected with field already filled-->
                                    <select class="selectpicker show-tick form-control" data-size="5"
                                            data-live-search="true" data-live-search-style="startsWith"
                                            title="Pallets Account" name="palletsaccount_name" id="select-palletsaccount_name"
                                            required onchange="writeName();" data-style="requiredField">
                                        @foreach($listPalletsAccounts as $palletsAccount )
                                            @if(Illuminate\Support\Facades\Input::old('palletsaccount_name') && $palletsAccount->nickname==old('palletsaccount_name'))
                                                <option selected>{{$palletsAccount->nickname}}</option>
                                            @elseif(isset($palletsaccount_name)&& $palletsAccount->nickname==$palletsaccount_name)
                                                <option selected>{{$palletsAccount->nickname}}</option>
                                            @else
                                                <option>{{$palletsAccount->nickname}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 text-left">
                                    <a href="{{route('showAddPalletsaccount', ['originalPage'=>'addTruck'])}}" class="link">
                                        <span class="glyphicon glyphicon-plus-sign"></span> Account</a>
                                </div>
                            </div>
                            <div class="form-group">
                                <!--name-->
                                <div class="col-lg-3">
                                    <label for="name" class="control-label">Name :</label>
                                </div>
                                <div class="col-lg-8">
                                    <input id="name" type="text" class="form-control requiredField" name="name"
                                           @if(isset($name)) value="{{$name}}" @else value="{{ old('name') }}"
                                           @endif placeholder="Name" required autofocus readonly >
                                </div>
                            </div>
                            <div class="form-group">
                                <!--license plate-->
                                <div class="col-lg-3">
                                    <label for="licensePlate" class="control-label">License Plate :</label>
                                </div>
                                <div class="col-lg-8">
                                    <input id="licensePlate" type="text" class="form-control" name="licensePlate"
                                           @if(isset($licensePlate)) value="{{$licensePlate}}"
                                           @else value="{{old('licensePlate')}}" @endif placeholder="License Plate"
                                           autofocus>
                                </div>
                            </div>
                            <div class="form-group">
                                <!--pallets number-->
                                <div class="col-lg-3">
                                    <label for="realNumberPallets" class="control-label">Pallets Number :</label>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default btn-number"
                                                    data-type="minus" data-field="realNumberPallets">
                                                <span class="glyphicon glyphicon-minus"></span>
                                            </button>
                                        </span>
                                        <input id="realNumberPallets" type="number" name="realNumberPallets"
                                               class="form-control input-number"
                                               @if(isset($realNumberPallets)) value="{{$realNumberPallets}}"
                                               @else value="{{ old('realNumberPallets') }}" @endif
                                                min="-999999" max="999999" autofocus
                                                required data-toggle="tooltip" data-placement="top" title="pallets number">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default btn-number"
                                                    data-type="plus" data-field="realNumberPallets">
                                                <span class="glyphicon glyphicon-plus"></span>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-lg-offset-1 checkbox">
                                    <label><input type="checkbox" name="activate" value="activate" @if((isset($activate) && $activate==1) || !(isset($activate))) checked @endif/>Activate</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-8 col-lg-offset-3">
                                    <button type="submit" class="btn btn-primary btn-block btn-form" name="addTruck"
                                            value="addTruck" id="addTruck" onclick="formAddSubmitBlock(this);">
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

@section('scriptEnd')
    <script>
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
    <script type="text/javascript" src="{{asset('js/addUpdateTruck.js')}}">
    </script>
@endsection