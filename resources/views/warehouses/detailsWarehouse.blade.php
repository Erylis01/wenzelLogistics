@extends('layouts.default')

@section('title')
    Details warehouse
@endsection

@section('stylesheet')
    <link href="{{asset('css/warehouses.css')}}" rel="stylesheet" type="text/css">
@endsection

@section('classLoadings')
    class="nonActive"
@endsection
@section('classWarehouses')
    class="active"
@endsection
@section('classPalletsAccounts')
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
                    <div class="panel-heading">Details of the warehouse {{ $name }}</div>
                    <div class="panel-body panel-body-general">
                        <form class="form-horizontal text-right" role="form" method="POST" action="">
                            {{ csrf_field() }}
                            <p class="text-center legend-auth">* required field</p>

                            <div class="form-group">
                                <!--name-->
                                <div class="col-lg-3">
                                    <label for="name" class="control-label"><span>*</span> Name :</label>
                                </div>
                                    <div class="col-lg-8">
                                        <input id="name" type="text" class="form-control" name="name"
                                               value="{{ $name }}" placeholder="Name" required autofocus>

                                    </div>
                            </div>

                            <div class="form-group">
                                <!--adress-->
                                <div class="col-lg-3">
                                    <label for="adress" class="control-label"><span>*</span> Adress :</label>
                                </div>
                                    <div class="col-lg-8">
                                        <input id="adress" type="text" class="form-control" name="adress"
                                               value="{{ $adress }}" placeholder="Adress" required autofocus>
                                    </div>
                            </div>

                            <div class="form-group">
                                <!--zipcode-->
                                <div class="col-lg-3">
                                    <label for="zipcode" class="control-label"><span>*</span> Zip Code :</label>
                                </div>
                                    <div class="col-lg-3">
                                        <input id="zipcode" type="number" class="form-control" name="zipcode"
                                               value="{{ $zipcode }}" placeholder="Zip Code" required autofocus>
                                    </div>
                                <!--town-->
                                <div class="col-lg-2">
                                    <label for="town" class="control-label"><span>*</span> Town :</label>
                                </div>
                                <div class="col-lg-3">
                                    <input id="town" type="text" class="form-control" name="town"
                                           value="{{ $town }}" placeholder="Town" required autofocus>
                                </div>
                            </div>


                            <div class="form-group">
                                <!--country-->
                                <div class="col-lg-3">
                                    <label for="country" class="control-label"><span>*</span> Country :</label>
                                </div>
                                    <div class="col-lg-8">
                                        <input id="country" type="text" class="form-control" name="country"
                                               value="{{ $country }}" placeholder="country" required autofocus>
                                    </div>
                            </div>

                            <div class="form-group">
                                <!--phone-->
                                <div class="col-lg-3">
                                    <label for="phone" class="control-label">Phone :</label>
                                </div>
                                    <div class="col-lg-3">
                                        <input id="phone" type="text" class="form-control" name="phone"
                                               value="{{ $phone }}" placeholder="Phone" autofocus>
                                    </div>
                                <!--fax-->
                                <div class="col-lg-2">
                                    <label for="fax" class="control-label">Fax :</label>
                                </div>
                                <div class="col-lg-3">
                                    <input id="fax" type="text" class="form-control" name="fax"
                                           value="{{ $fax }}" placeholder="Fax" autofocus>
                                </div>
                            </div>


                            <div class="form-group">
                                <!--email-->
                                <div class="col-lg-3">
                                    <label for="email" class="control-label">Email :</label>
                                </div>
                                    <div class="col-lg-8">
                                        <input id="email" type="text" class="form-control" name="email"
                                               value="{{ $email }}" placeholder="Email" autofocus>
                                    </div>
                            </div>

                            <div class="form-group">
                                <!--contact name-->
                                <div class="col-lg-3">
                                    <label for="namecontact" class="control-label">Contact Name :</label>
                                </div>
                                    <div class="col-lg-8">
                                        <input id="namecontact" type="text" class="form-control" name="namecontact"
                                               value="{{ $namecontact }}" placeholder="Contact Name" autofocus>
                                    </div>
                            </div>

                            <div class="form-group">
                                <!--pallet account associated-->
                                <div class="col-lg-3">
                                    <label for="namecontact" class="control-label">Pallet Account :</label>
                                </div>
                                {{--<div class="col-lg-8">--}}
                                    {{--<input id="namepalletaccount" type="text" class="form-control" name="namepalletaccount"--}}
                                           {{--value="{{ $namepalletaccount }}" readonly>--}}
                                {{--</div>--}}
                            </div>

                            <div class="form-group">
                                <div class="col-lg-8 col-lg-offset-3">
                                    <input type="submit"
                                           class="btn btn-primary btn-block btn-form"
                                           value="Update"
                                           name="updateWarehouse">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @if (Session::has('messageUpdateWarehouse'))
                    <div class="alert alert-success text-alert text-center">{{ Session::get('messageUpdateWarehouse') }}</div>
                @endif
            </div>
        @endif
    </div>
@endsection