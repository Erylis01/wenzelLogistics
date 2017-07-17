@extends('layouts.default')

@section('title')
    Add subloading
@endsection

@section('stylesheet')
    <link href="{{asset('css/loadings.css')}}" rel="stylesheet" type="text/css">
@endsection

@section('classLoadings')
    class="active"
@endsection
@section('classWarehouses')
    class="nonActive"
@endsection
@section('classTrucks')
    class="nonActive"
@endsection
@section('classPalletsAccounts')
    nonActive
@endsection
@section('classPalletsTransfers')
    class="nonActive"
@endsection
@section('classProfile')
    nonActive
@endsection

@section('scriptBegin')
    <script type="text/javascript" src="{{asset('js/addSubloading.js')}}">
    </script>
@endsection

@section('content')
    <div class="row">
        @if(Auth::guest())
            <h4>You need to login to see the content</h4>
        @else
            <div class="col-lg-14 container-details">
                <div class="panel panel-general">
                    <div class="panel-heading">Add a subloading to the loading n°{{ $loading->atrnr }}
                    </div>
                    <div class="panel-body panel-body-general">
                        <form class="form-horizontal" role="form" method="POST"
                              action="{{route('addSubloading', $loading->atrnr)}}" id="formAddSubloading">
                            <input type="hidden"  name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <!--referenz-->
                                <div class="col-lg-3 details-loading">
                                    <label for="referenz" class="control-label">Referenz :</label>
                                    <input type="text" name="referenz" class="form-control"
                                           value="{{ $loading->referenz }}" placeholder="referenz" required>
                                </div>
                                <div class="col-lg-9 details-loading">
                                    <!--auftraggeber-->
                                    <label for="auftraggeber" class="control-label">Auftraggeber :</label>
                                    <input type="text" name="auftraggeber" class="form-control" value="{{ $loading->auftraggeber }}"
                                           placeholder="auftraggeber" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <!--subfrachter-->
                                <div class="col-lg-9 details-loading">
                                    <label for="subfrachter" class="control-label">Subfrachter :</label>
                                    <input type="text" name="subfrachter" class="form-control"
                                           value="{{ $loading->subfrachter }}" placeholder="subfrachter" required>
                                </div>
                                <!--kennzeichen-->
                                <div class="col-lg-3 details-loading">
                                    <label for="kennzeichen" class="control-label">Kennzeichen :</label>
                                    <input type="text" name="kennzeichen" class="form-control" value="{{ $loading->kennzeichen }}"  placeholder="kennzeichen">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-1 details-loading">
                                    <label for="article" class="control-label">Article :</label>
                                </div>
                                <!--anz-->
                                <div class="col-lg-1 details-loading">
                                    <input type="number" name="anz" class="form-control" value="{{ $loading->anz }}"
                                           placeholder="anz." min="0" required data-toggle="tooltip" data-placement="top" title="Anzahl">
                                </div>
                                <div class="col-lg-1 details-loading text-center">
                                    -
                                </div>
                                <!--art-->
                                <div class="col-lg-1 details-loading">
                                    <input type="text" name="art" class="form-control" value="{{ $loading->art }}"
                                           placeholder="art" required data-toggle="tooltip" data-placement="top" title="Art">
                                </div>
                                <div class="col-lg-1 details-loading text-center">
                                    -
                                </div>
                                <!--ware-->
                                <div class="col-lg-4 details-loading">
                                    <input type="text" name="ware" class="form-control" value="{{ $loading->ware }}"
                                           placeholder="ware" required data-toggle="tooltip" data-placement="top" title="Ware">
                                </div>
                            </div>
                            <br>
                            <div class="panel subpanel col-lg-6">
                                <div class="form-group">
                                    <div class="col-lg-12 details-loading">
                                        <label for="ladedatum" class="control-label">Ladedatum :</label>
                                        <input type="date" name="ladedatum" class="form-control  text-center" value="{{ $loading->ladedatum }}"
                                                placeholder="ladedatum" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <!--beladestelle-->
                                    <div class="col-lg-12 details-loading">
                                        <label for="beladestelle" class="control-label">Beladestelle :</label>
                                        <input type="text" name="beladestelle" class="form-control text-center"
                                               value="{{ $loading->beladestelle }}" placeholder="beladestelle"
                                               required data-toggle="tooltip" data-placement="top" title="Beladestelle">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-6 details-loading">
                                        <!--ort-->
                                        <label for="ortb" class="control-label">Ort :</label>
                                        <input type="text" name="ortb" class="form-control text-center" value="{{ $loading->ortb }}"
                                               placeholder="ort" required data-toggle="tooltip" data-placement="top" title="Ort">
                                    </div>
                                    <!--plz-->
                                    <div class="col-lg-4 details-loading">
                                        <label for="plzb" class="control-label">Plz :</label>
                                        <input type="number" name="plzb" class="form-control text-center"
                                               value="{{ $loading->plzb }}" placeholder="plz"
                                               min="0" required data-toggle="tooltip" data-placement="top" title="Plz">
                                    </div>
                                    <!--land-->
                                    <div class="col-lg-2 details-loading">
                                        <label for="landb" class="control-label">Land :</label>
                                        <input type="text" name="landb" class="form-control text-center"  value="{{ $loading->landb }}"
                                               placeholder="land" required data-toggle="tooltip" data-placement="top" title="Land">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <!--zusladestellen-->
                                    <div class="col-lg-12 details-loading">
                                        <label for="zusladestellen" class="control-label">Zus. Ladestellen :</label>
                                        <input type="text" name="zusladestellen" class="form-control" value="{{ $loading->zusladestellen }}"
                                               placeholder="zus ladestellen">
                                    </div>
                                </div>
                            </div>
                            <div class="panel subpanel col-lg-6">
                                <div class="form-group">
                                    <div class="col-lg-12 details-loading">
                                        <label for="entladedatum" class="control-label">Entladedatum :</label>
                                        <input type="date" name="entladedatum" class="form-control  text-center"
                                                value="{{ $loading->entladedatum }}" placeholder="ladedatum" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <!--entladestelle-->
                                    <div class="col-lg-12 details-loading">
                                        <label for="entladestelle" class="control-label">Entladestelle :</label>
                                        <input type="text" name="entladestelle" class="form-control text-center"
                                               value="{{ $loading->entladestelle }}" placeholder="entladestelle"
                                               required data-toggle="tooltip" data-placement="top" title="Entladestelle">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-6 details-loading">
                                        <!--ort-->
                                        <label for="orte" class="control-label">Ort :</label>
                                        <input type="text" name="orte" class="form-control text-center"
                                               value="{{ $loading->orte }}" placeholder="ort"
                                               required data-toggle="tooltip" data-placement="top" title="Ort">
                                    </div>
                                    <!--plz-->
                                    <div class="col-lg-4 details-loading">
                                        <label for="plze" class="control-label">Plz :</label>
                                        <input type="number" name="plze" class="form-control text-center"
                                               value="{{ $loading->plze }}" placeholder="plz"
                                               min="0" required data-toggle="tooltip" data-placement="top" title="Plz">
                                    </div>
                                    <!--land-->
                                    <div class="col-lg-2 details-loading">
                                        <label for="lande" class="control-label">Land :</label>
                                        <input type="text" name="lande" class="form-control text-center" value="{{ $loading->lande }}"
                                               placeholder="land" required data-toggle="tooltip" data-placement="top" title="Land">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-offset-1 col-lg-4">
                                <button type="submit" class="btn btn-block btn-form" name="addSubloading" id="addSubloading" onclick="formSubmitBlock(this);">
                                    Add
                                </button>
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
    <script type="text/javascript" src="{{asset('js/addSubloading.js')}}">
    </script>
@endsection