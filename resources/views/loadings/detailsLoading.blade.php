@extends('layouts.default')

@section('title')
    Loading details
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
    <script type="text/javascript" src="{{asset('js/addUpdateTransferLoading.js')}}"></script>
@endsection

@section('content')
    <div class="container-fluid">
        @if(Auth::guest())
            <h4>You need to login to see the content</h4>
        @else
            <div class="col-lg-14 container-details">
                <div class="panel @if($loading->state=="In progress") panelInprogress @elseif ($loading->state=="Waiting documents") panelWaitingdocuments @elseif ($loading->state=="Complete") panelComplete @elseif ($loading->state=="Complete Validated")panel-general @else panelUntreated @endif">
                    <div class="panel-heading">
                        <!--atrnr loading head panel-->
                        <div class="col-lg-5">
                            @if(substr_count($loading->atrnr, '-')==0)
                                <p>Details of the loading n°{{ $loading->atrnr }}</p>
                            @else
                                <p> Details of the loading n° <a
                                            href="{{route('showDetailsLoading', $atrnr1)}}">{{$atrnr1}}</a>-{{$atrnr2}}
                                </p>
                            @endif
                        </div>
                        <!--sign errors head panel-->
                        <div class="col-lg-5 text-left">
                            @php($k=0)
                            @foreach($listPalletstransfers as $transfer)
                                @php($errorsTransfer= \App\Http\Controllers\PalletstransfersController::actualErrors($transfer))
                                @if(!empty($errorsTransfer)&&$k<10)
                                    <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                @elseif(!empty($errorsTransfer) && $k==10)
                                    <span class="text-danger">...</span>
                                @endif
                                @php($k=$k+1)
                            @endforeach
                        </div>
                        <!--add subloading-->
                        <div>
                            <a href="{{route('showAddSubloading', $loading->atrnr)}}" class=" btn btn-add"><span
                                        class="glyphicon glyphicon-plus-sign"></span> Subloading</a>
                        </div>
                    </div>
                    <div class="panel-body panel-body-general">
                        <form class="form-horizontal" role="form"  method="POST" action="{{route('submitUpdateUpload', $loading->atrnr)}}" enctype="multipart/form-data" id="formSubmitUpdateUpload">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <input type="hidden" name="actionForm" id="actionForm" />
                        <!-------SUBPANEL 1 : reading form suming up information from the table------->
                        <div class="row">
                        <div class="panel subpanel">
                            <!--head panel info-->
                            <div class="panel-heading">
                                <div class="col-lg-2">
                                    <a data-toggle="collapse" href="#Pan1collapse" onclick="openClosePanel1();"><span id="infoPanelLogo" @if (Session::has('openPanelInformation')) class="glyphicon glyphicon-menu-up" @else class="glyphicon glyphicon-menu-down" @endif></span> Information</a>
                                </div>
                                <div class="col-lg-9 text-center">
                                    @php($carrier=explode(',', $loading->subfrachter)[0])
                                    <p><span class="glyphicon glyphicon-road"></span> {{$carrier}} - {{$loading->landb}} {{$loading->plzb}} {{$loading->ortb}} <span class="glyphicon glyphicon-arrow-right"></span> {{$loading->lande}} {{$loading->plze}} {{$loading->orte}} - {{$loading->anz}} {{$loading->art}}</p>
                                </div>
                                <br>
                            </div>
                            <div id="Pan1collapse" class="panel-collapse @if (Session::has('openPanelInformation'))in @endif collapse">
                                <!--msg update loading-->
                                @if (Session::has('messageUpdateLoading'))
                                    <div class="alert alert-success text-alert text-center">{{ Session::get('messageUpdateLoading') }}</div>
                                @endif
                                <div class="panel-body">
                                    <div class="form-group">
                                        <!--referenz-->
                                        <div class="col-lg-6">
                                            <div class="input-group details-loading">
                                                <label for="referenz" class="input-group-addon">Referenz :</label>
                                                <input type="text" name="referenz" class="form-control" value="{{ $loading->referenz }}" placeholder="referenz" required />
                                            </div>
                                        </div>
                                    @if(substr_count($loading->atrnr, '-')==0)
                                        <!--disp-->
                                            <div class="col-lg-4">
                                                <div class="input-group details-loading">
                                                    <label for="disp" class="input-group-addon">Disp :</label>
                                                    <input type="text" name="disp" class="form-control" value="{{ $disp }}" placeholder="disp" required />
                                                    @if ($errors->has('disp'))
                                                        <span class="help-block"><strong>{{ $errors->first('disp') }}</strong></span>
                                                    @endif
                                                </div>
                                            </div>
                                            <!--pt change pt-->
                                            @if(Auth::user()->lastname=='Gundogan'&& Auth::user()->firstname=='Adrien' ||Auth::user()->username=='CamilleS'||Auth::user()->username=='Admin' )
                                                <div class="col-lg-2">
                                                    <div class="input-group details-loading">
                                                        <label for="pt" class="input-group-addon">PT :</label>
                                                        <input type="text" readonly name="pt" class="form-control link" data-toggle="modal" data-target="#updatePT_modal" value="{{ $loading->pt }}" />
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-12">
                                            <!--auftraggeber-->
                                            <div class="input-group details-loading">
                                                <label for="auftraggeber" class="input-group-addon">Auftraggeber :</label>
                                                <input type="text" name="auftraggeber" class="form-control" value="{{ $loading->auftraggeber }}" placeholder="auftraggeber" required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <!--subfrachter-->
                                        <div class="col-lg-8">
                                            <div class="input-group details-loading">
                                                <label for="subfrachter" class="input-group-addon">Subfrachter :</label>
                                                <input type="text" name="subfrachter" class="form-control" value="{{ $loading->subfrachter }}" placeholder="subfrachter" required />
                                            </div>
                                        </div>
                                        <!--kennzeichen-->
                                        <div class="col-lg-4">
                                            <div class="input-group details-loading">
                                                <label for="kennzeichen" class="input-group-addon">Kennzeichen :</label>
                                                <input type="text" name="kennzeichen" class="form-control" value="{{ $loading->kennzeichen }}" placeholder="kennzeichen" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <!--anz-->
                                        <div class="col-lg-2 details-loading">
                                            <div class="input-group">
                                                                <span class="input-group-btn">
                                                                    <button type="button" class="btn btn-default btn-number" disabled="disabled" data-type="minus" data-field="anz">
                                                                        <span class="glyphicon glyphicon-minus"></span>
                                                                    </button>
                                                                </span>
                                                <input type="number" name="anz" id="anz" class="form-control input-number" value="{{ $loading->anz }}" min="0" max="999999" placeholder="anz." required data-toggle="tooltip" data-placement="top" title="Anzahl">
                                                <span class="input-group-btn">
                                                                    <button type="button" class="btn btn-default btn-number" data-type="plus" data-field="anz">
                                                                        <span class="glyphicon glyphicon-plus"></span>
                                                                    </button>
                                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-lg-1 details-loading text-center"><p>-</p></div>
                                        <!--art-->
                                        <div class="col-lg-3 details-loading">
                                            <input type="text" name="art" class="form-control" value="{{ $loading->art }}" placeholder="art" required data-toggle="tooltip" data-placement="top" title="Art" />
                                        </div>
                                        <div class="col-lg-1 details-loading text-center"><p>-</p></div>
                                        <!--ware-->
                                        <div class="col-lg-5 details-loading">
                                            <input type="text" name="ware" class="form-control" value="{{ $loading->ware }}" placeholder="ware" required data-toggle="tooltip" data-placement="top" title="Ware" />
                                        </div>
                                    </div>
                                    @if (Session::has('messageUpdatePTLoading'))
                                        <div class="form-group">
                                            <p class="alert alert-warning text-alert text-center">{{ Session::get('messageUpdatePTLoading') }}</p>
                                        </div>
                                @endif

                                        <!-- subpanel loading-->
                                        <div class="panel subpanel col-lg-6">
                                            <div class="panel-heading">
                                                <a class="col-lg-4 text-left" data-toggle="collapse" href="#PanSub2collapse" onclick="openClosePanelSub2();"><span id="loadingPanelLogo" @if (Session::has('openPanelInformation')) class="glyphicon glyphicon-menu-up" @else class="glyphicon glyphicon-menu-down" @endif></span> Loading</a>
                                                <div class="col-lg-5 col-lg-offset-1 text-right details-loading">
                                                    <input type="date" name="ladedatum" class="form-control  text-center" value="{{ $loading->ladedatum }}" placeholder="ladedatum" required />
                                                </div>
                                            </div>
                                            <div id="PanSub2collapse" class="panel-collapse @if (Session::has('openPanelInformation')) in @endif collapse">
                                                <div class="panel-body">
                                                    <div class="form-group">
                                                        <!--beladestelle-->
                                                        <div class="col-lg-12 details-loading">
                                                            <input type="text" name="beladestelle" class="form-control text-center" value="{{ $loading->beladestelle }}" placeholder="beladestelle" required data-toggle="tooltip" data-placement="top" title="Beladestelle" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <!--ort plz land-->
                                                        <div class="col-lg-3 details-loading">
                                                            <input type="number" name="plzb" class="form-control text-center" value="{{ $loading->plzb }}" placeholder="plz" min="0" required data-toggle="tooltip" data-placement="top" title="Plz" />
                                                        </div>
                                                        <div class="col-lg-1 details-loading"><p>-</p></div>
                                                        <div class="col-lg-5 details-loading">
                                                            <input type="text" name="ortb" class="form-control text-center" value="{{ $loading->ortb }}" placeholder="ort" required data-toggle="tooltip" data-placement="top" title="Ort" />
                                                        </div>
                                                        <div class="col-lg-1 details-loading"><p>-</p></div>
                                                        <div class="col-lg-2 details-loading">
                                                            <input type="text" name="landb" class="form-control text-center" value="{{ $loading->landb }}" placeholder="land" required data-toggle="tooltip" data-placement="top" title="Land" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <!--zusladestellen-->
                                                        <div class="col-lg-12">
                                                            <div class="input-group details-loading">
                                                                <label for="zusladestellen" class="input-group-addon">Zus. Ladestellen :</label>
                                                                <input type="text" name="zusladestellen" class="form-control" value="{{ $loading->zusladestellen }}" placeholder="zus ladestellen"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- subpanel unloading-->
                                        <div class="panel subpanel col-lg-6">
                                            <div class="panel-heading">
                                                <a class="col-lg-4 text-left" data-toggle="collapse" href="#PanSub3collapse" onclick="openClosePanelSub3();"><span id="offloadingPanelLogo" @if (Session::has('openPanelInformation')) class="glyphicon glyphicon-menu-up" @else class="glyphicon glyphicon-menu-down" @endif></span> Offloading</a>
                                                <div class="col-lg-5 col-lg-offset-1 text-right details-loading">
                                                    <input type="date" name="entladedatum" class="form-control" value="{{ $loading->entladedatum }}" placeholder="entladedatum" required />
                                                </div>
                                            </div>
                                            <div id="PanSub3collapse" class="panel-collapse @if (Session::has('openPanelInformation')) in @endif collapse">
                                                <div class="panel-body">
                                                    <div class="form-group">
                                                        <!--entladestelle-->
                                                        <div class="col-lg-12 details-loading">
                                                            <input type="text" name="entladestelle" class="form-control" value="{{ $loading->entladestelle }}" placeholder="entladestelle" required data-toggle="tooltip" data-placement="top" title="Entladestelle" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <!--plz-->
                                                        <div class="col-lg-3 details-loading">
                                                            <input type="number" name="plze" class="form-control" value="{{ $loading->plze }}" placeholder="plz" min="0" required data-toggle="tooltip" data-placement="top" title="Plz" />
                                                        </div>
                                                        <div class="col-lg-1 details-loading"><p>-</p></div>
                                                        <!--ort-->
                                                        <div class="col-lg-5 details-loading">
                                                            <input type="text" name="orte" class="form-control" value="{{ $loading->orte }}" placeholder="ort" required data-toggle="tooltip" data-placement="top" title="Ort" />
                                                        </div>
                                                        <div class="col-lg-1 details-loading"><p>-</p></div>
                                                        <!--land-->
                                                        <div class="col-lg-2 details-loading">
                                                            <input type="text" name="lande" class="form-control" value="{{ $loading->lande }}" placeholder="land" required data-toggle="tooltip" data-placement="top" title="Land" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- update-->
                                        <div class="col-lg-4 col-lg-offset-4">
                                            <input type="submit" class="btn btn-primary btn-block btn-form" value="Update" name="update" onclick="formSubmitBlock(this);"/>
                                        </div>
                                </div>
                            </div>
                            <!-- Modal update pt -->
                            <div class="modal fade" id="updatePT_modal" role="dialog">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">
                                                &times;
                                            </button>
                                            <h4 class="modal-title">Why would you like to change the loading into a loading WITHOUT exchange
                                                pallets ?</h4>
                                        </div>
                                        <div class="modal-body center">
                                <textarea class="form-control" rows="5" id="reasonUpdatePT" name="reasonUpdatePT" required
                                          autofocus>{{$loading->reasonUpdatePT}}</textarea>
                                            <button type="button" class="btn btn-success btn-modal" data-toggle="modal"
                                                    data-target="#updateValidatePT_modal">
                                                Update
                                            </button>
                                            <!-- Modal update validate pt -->
                                            <div class="modal fade" id="updateValidatePT_modal" role="dialog">
                                                <div class="modal-dialog modal-sm">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal">
                                                                &times;
                                                            </button>
                                                            <h4 class="modal-title"> Are you sure that loading is WITHOUT exchange
                                                                pallets?</h4>
                                                        </div>
                                                        <div class="modal-body center">
                                                            <h4>If you have made a mistake you can change this information directly on the
                                                                database</h4>
                                                            <br>
                                                            <div class="col-lg-offset-3">
                                                                <input type="submit" class="btn btn-danger btn-modal" value="updateValidatePT"
                                                                       name="updateValidatePT" onclick="formSubmitBlock(this);"/>
                                                                <button type="button" class="btn btn-success btn-modal"
                                                                        data-dismiss="modal">
                                                                    No
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default btn-modal" data-dismiss="modal">
                                                                Close
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default btn-modal" data-dismiss="modal">
                                                Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>

                        <!------SUBPANEL 2 : infos about pallets transfer-------->
                        <div class="row">
                        <div class="panel subpanel">
                            <div class="panel-heading">
                                <div class="col-lg-3">
                                    <a data-toggle="collapse" href="#Pan2collapse" onclick="openClosePanel2();"><span id="palletsPanelLogo" @if (Session::has('openPanelPallets')) class="glyphicon glyphicon-menu-up" @else class="glyphicon glyphicon-menu-down" @endif></span> Pallets location ?</a>
                                </div>
                                @if($loading->notExchange==1)
                                <div class="col-lg-3">
                                    <p>Agreed w/o exchange</p>
                                </div>
                                @endif
                                <div @if($loading->notExchange==1) class="col-lg-2" @else class="col-lg-offset-3 col-lg-2" @endif>
                                    <p>order : {{$loading->anz}}</p>
                                </div>
                                <div class="col-lg-offset-4">
                                    <p>truck : {{$theoricalNumberPalletsTruck}} (planned) - {{$realNumberPalletsTruck}} (confirmed)</p>
                                </div>
                            </div>
                            <div id="Pan2collapse" class="panel-collapse in collapse">
                                <div class="panel-body">
                                    <!--msg-->
                                    <div class="row">
                                        @if(Session::has('messageAddPalletstransfer'))
                                            <p class="alert alert-success text-alert text-center">{{ Session::get('messageAddPalletstransfer') }}</p>
                                        @elseif(Session::has('messageDeletePalletstransfer'))
                                            <p class="alert alert-success text-alert text-center">{{ Session::get('messageDeletePalletstransfer') }}</p>
                                        @elseif(Session::has('messageSubmitPalletstransfer'))
                                            <p class="alert alert-success text-alert text-center">{{ Session::get('messageSubmitPalletstransfer') }}</p>
                                        @elseif(Session::has('messageUpdateValidatePalletstransfer'))
                                            <p class="alert alert-success text-alert text-center">{{ Session::get('messageUpdateValidatePalletstransfer') }}</p>
                                        @endif
                                    </div>
                                    <!--show add form-->
                                    <div class="row">
                                        <div class="from-group">
                                            <div class="col-lg-4 col-lg-offset-4">
                                                <button type="submit" class="btn btn-add btn-block" value="addTransferForm" name="addTransferForm" id="addTransferForm" data-toggle="collapse" data-target="#addForm" onclick="formSubmitBlock(this);"
                                                @if((isset($actionForm) && ($actionForm== 'addTransferForm'||$actionForm=='addPalletstransfer'||explode('-', $actionForm)[0]=='showAddCorrectingTransfer')) || isset($showAddCorrectingTransfer)) disabled="true" @endif>
                                                    Add transfer
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <!--Add form-->
                                    <div id="addForm" class="row collapse in">
                                        @if(Session::has('openPanelPalletsAddForm') || (isset($actionForm) && ($actionForm== 'addTransferForm'||$actionForm=='addPalletstransfer'||explode('-', $actionForm)[0]=='showAddCorrectingTransfer') || isset($showAddCorrectingTransfer)) )
                                            <div class="panel subpanel">
                                                <div class="panel-body">
                                                    <div class="form-group">
                                                        <div class="col-lg-4 text-right">
                                                            <label for="legend" class="control-label text-underline">@if(isset($showAddCorrectingTransfer) ||( isset($actionForm) && explode('-', $actionForm)[0]=='showAddCorrectingTransfer'))<span class="glyphicon glyphicon-check"></span> CORRECTING TRANSFER @else NORMAL TRANSFER @endif : </label>
                                                        </div>
                                                        @if(isset($showAddCorrectingTransfer) ||( isset($actionForm) && explode('-', $actionForm)[0]=='showAddCorrectingTransfer'))
                                                            <div class="col-lg-7 text-left">
                                                                @if(Illuminate\Support\Facades\Input::old('type') || isset($type))
                                                                    <label class="radio-inline" data-toggle="tooltip" data-placement="top" title="Purchase of pallets from Wenzel account to other account. Then sale of pallets from an other account to Wenzel account">
                                                                        <input type="radio" name="type" value="Purchase-Sale" @if(strcmp(old('type'),'Purchase-Sale')==0 || strcmp($type,'Purchase-Sale')==0) checked @endif id="typePS" onchange="displayFieldsTypeCorrecting(this);"/>Purchase-Sale</label>
                                                                    <label class="radio-inline" data-toggle="tooltip" data-placement="top" title="When the loading is agreed without exchange : wenzel has debt of pallets toward other accounts and inversely">
                                                                        <input type="radio" name="type" value="Debt" @if(strcmp(old('type'),'Debt')==0|| strcmp($type,'Debt')==0) checked @endif id="typeDebt" onchange="displayFieldsTypeCorrecting(this);"/>Debt</label>
                                                                    <label class="radio-inline" data-toggle="tooltip" data-placement="top" title="Other kind of transfer">
                                                                        <input type="radio" name="type" value="Other" @if(strcmp(old('type'),'Other')==0 ||strcmp($type,'Other')==0) checked @endif id="typeOther" onchange="displayFieldsTypeCorrecting(this);"/>Other</label>
                                                                @else
                                                                    <label class="radio-inline" data-toggle="tooltip" data-placement="top" title="Purchase of pallets from Wenzel account to other account. Then sale of pallets from an other account to Wenzel account">
                                                                        <input type="radio" name="type" value="Purchase-Sale" checked id="typePS" onchange="displayFieldsTypeCorrecting(this);"/>Purchase-Sale</label>
                                                                    <label class="radio-inline" data-toggle="tooltip" data-placement="top" title="When the loading is agreed without exchange : wenzel has debt of pallets toward other accounts and inversely">
                                                                        <input type="radio" name="type" value="Debt"  id="typeDebt" onchange="displayFieldsTypeCorrecting(this);"/>Debt</label>
                                                                    <label class="radio-inline" data-toggle="tooltip" data-placement="top" title="Other kind of transfer">
                                                                        <input type="radio" name="type" value="Other" id="typeOther" onchange="displayFieldsTypeCorrecting(this);"/>Other</label>
                                                                @endif
                                                            </div>
                                                        @else
                                                            <div class="col-lg-7 text-left">
                                                                @if(Illuminate\Support\Facades\Input::old('type') || isset($type))
                                                                    <label class="radio-inline" data-toggle="tooltip" data-placement="top" title="On loading place : deposit of empty pallets, then withdrawal of full pallets. On offloading place : deposit of full pallets, then withdrawal of empty pallets">
                                                                        <input type="radio" name="type" value="Deposit-Withdrawal" @if(strcmp(old('type'),'Deposit-Withdrawal')==0|| strcmp($type,'Deposit-Withdrawal')==0) checked @endif id="typeDW" onchange="displayFieldsTypeNormal(this);"/>Deposit-Withdrawal</label>
                                                                    <label class="radio-inline" data-toggle="tooltip" data-placement="top" title="Deposit of pallets on an extra account">
                                                                        <input type="radio" name="type" value="Deposit_Only" @if(strcmp(old('type'),'Deposit_Only')==0 || strcmp($type,'Deposit_Only')==0) checked @endif id="typeDonly" onchange="displayFieldsTypeNormal(this);"/>Deposit only</label>
                                                                    <label class="radio-inline" data-toggle="tooltip" data-placement="top" title="Withdrawal of pallets on an extra account">
                                                                        <input type="radio" name="type" value="Withdrawal_Only" @if(strcmp(old('type'),'Withdrawal_Only')==0 ||strcmp($type,'Withdrawal_Only')==0) checked @endif id="typeWonly" onchange="displayFieldsTypeNormal(this);"/>Withdrawal only</label>
                                                                @else
                                                                    <label class="radio-inline" data-toggle="tooltip" data-placement="top" title="On loading place : deposit of empty pallets, then withdrawal of full pallets. On offloading place : deposit of full pallets, then withdrawal of empty pallets">
                                                                        <input type="radio" name="type" value="Deposit-Withdrawal" checked id="typeDW" onchange="displayFieldsTypeNormal(this);"/>Deposit-Withdrawal</label>
                                                                    <label class="radio-inline" data-toggle="tooltip" data-placement="top" title="Deposit of pallets on an extra account">
                                                                        <input type="radio" name="type" value="Deposit_Only" id="typeDonly" onchange="displayFieldsTypeNormal(this);"/>Deposit only</label>
                                                                    <label class="radio-inline" data-toggle="tooltip" data-placement="top" title="Withdrawal of pallets on an extra account">
                                                                        <input type="radio" name="type" value="Withdrawal_Only" id="typeWonly" onchange="displayFieldsTypeNormal(this);"/>Withdrawal only</label>
                                                                @endif
                                                            </div>
                                                            @endif
                                                            <!--close add form-->
                                                            <div class="col-lg-offset-1">
                                                                <button type="submit" class="btn glyphicon glyphicon-remove" value="closeSubmitAddModal" name="closeSubmitAddModal" id="closeSubmitAddModal" onclick="formSubmitBlock(this);"></button>
                                                            </div>
                                                    </div>
                                                    <!--errors messages-->
                                                    <div class="form-group">
                                                        @if(Session::has('errorFields'))
                                                            <p class="alert alert-danger text-alert text-center"> {{ Session::get('errorFields') }}</p>
                                                        @elseif(Session::has('errorType'))
                                                            <p class="alert alert-danger text-alert text-center">{{ Session::get('errorType') }} </p>
                                                        @endif
                                                    </div>
                                                    <div class="form-group">
                                                        <!--details-->
                                                        <div class="col-lg-5">
                                                            @if(isset($details))
                                                                <textarea class="form-control" rows="1" id="details"
                                                                              placeholder="Details (broken pallets, gift, receipt...)" data-toggle="tooltip" data-placement="top" title="details">{{$details}}</textarea>
                                                            @else
                                                                <textarea class="form-control" rows="1" id="details"
                                                                              placeholder="Details (broken pallets, gift, receipt...)" data-toggle="tooltip" data-placement="top" title="details">{{old('details')}}</textarea>
                                                            @endif
                                                        </div>
                                                        <!--date-->
                                                        <div class="col-lg-2">
                                                            <input id="date" type="date" class="form-control" name="date" value="{{ $loading->ladedatum }}" autofocus data-toggle="tooltip" data-placement="top" title="date" />
                                                        </div>
                                                        <!--transfer normal associated-->
                                                        @if(isset($showAddCorrectingTransfer) ||( isset($actionForm) && explode('-', $actionForm)[0]=='showAddCorrectingTransfer'))
                                                            <div class="col-lg-2 text-right">
                                                                <label for="normalTransferAssociated" class="control-label">*Correction on :</label>
                                                            </div>
                                                            <div class="col-lg-1">
                                                                <select class="selectpicker show-tick form-control" data-size="5" data-live-search="true" data-live-search-style="startsWith" title="Normal transfer associated" name="normalTransferAssociated" id="normalTransferAssociated">
                                                                    @foreach($listPalletstransfersNormal as $normalTransfer )
                                                                        @if((Illuminate\Support\Facades\Input::old('normalTransferAssociated') && $normalTransfer->id==old('normalTransferAssociated'))|| (isset($normalTransferAssociated)&&$normalTransfer->id==$normalTransferAssociated)||(!isset($normalTransferAssociated) && explode('-', $actionForm)[1] ==$normalTransfer->id))
                                                                            <option value="{{$normalTransfer->id}}" selected>{{$normalTransfer->id}}</option>
                                                                        @else
                                                                            <option value="{{$normalTransfer->id}}">{{$normalTransfer->id}}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        @else
                                                        <!--exchanging ?-->
                                                            <div class="col-lg-3 checkbox">
                                                                @php($notExchanging=\App\Palletsaccount::where('name', $truckAssociated->name)->first()->notExchange)
                                                                <label><input type="checkbox" value="notExchanging" name="notExchanging" id="notExchanging" @if($loading->notExchange==1 || (isset($notExchanging) && $notExchanging==1)) checked @endif onchange="updateFieldsNormal();"/>Agreed w/o exchange</label>
                                                            </div>
                                                        @endif
                                                        <!--add pallet account-->
                                                        <div class="col-lg-2 text-right">
                                                            <a href="{{route('showAddPalletsaccount', ['originalPage'=>'detailsLoading-'.$loading->atrnr])}}" class="link"><span class="glyphicon glyphicon-plus-sign"></span> Account</a>
                                                        </div>
                                                    </div>
                                                    <!--deposit title-->
                                                    <div class="form-group" id="deposit-withdrawal1" @if((isset($type) && ($type<>'Deposit-Withdrawal'))||(!isset($type) && !(isset($showAddCorrectingTransfer) || (isset($actionForm) && explode('-', $actionForm)[0]=='showAddCorrectingTransfer'))))style="display: block;" @else style="display:none;" @endif>
                                                        <div class="col-lg-12 text-center">
                                                            <label for="deposit" class="control-label">DEPOSIT</label>
                                                        </div>
                                                    </div>
                                                    <!--purchase title-->
                                                    <div class="form-group" id="purchase-sale1" @if((isset($type) && ($type=='Purchase-Sale'))|| (!isset($type) && (isset($showAddCorrectingTransfer) ||( isset($actionForm) && explode('-', $actionForm)[0]=='showAddCorrectingTransfer'))))style="display: block;" @else style="display:none;" @endif>
                                                        <div class="col-lg-12 text-center">
                                                            <label for="purchase" class="control-label">PURCHASE</label>
                                                        </div>
                                                    </div>
                                                    <!-- pallets number debit account credit account -->
                                                    <div class="form-group">
                                                        <!--number of pallets-->
                                                        <div class="col-lg-2">
                                                            <div class="input-group requiredField">
                                                                <span class="input-group-btn">
                                                                    <button type="button" class="btn btn-default btn-number" data-type="minus" data-field="palletsNumber">
                                                                        <span class="glyphicon glyphicon-minus"></span>
                                                                    </button>
                                                                </span>
                                                                    <input id="palletsNumber" type="number" name="palletsNumber"
                                                                           class="form-control input-number text-center"  @if(isset($palletsNumber)) value="{{$palletsNumber}}"
                                                                           @elseif(Illuminate\Support\Facades\Input::old('palletsNumber')) value="{{ old('palletsNumber') }}"
                                                                           @else value="0" @endif placeholder="Nbr" min="0" max="999999" autofocus onchange="updateFieldsNormal();">
                                                                    <span class="input-group-btn">
                                                                    <button type="button" class="btn btn-default btn-number" data-type="plus" data-field="palletsNumber">
                                                                        <span class="glyphicon glyphicon-plus"></span>
                                                                    </button>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2 text-center" id="palletsGiven" @if((isset($type) && ($type=='Deposit_Only'|| $type=='Deposit-Withdrawal'))||(!isset($type) && !(isset($showAddCorrectingTransfer) || (isset($actionForm) && explode('-', $actionForm)[0]=='showAddCorrectingTransfer')))) style="display: block;" @else style="display:none;" @endif>
                                                            <label for="palletsNumber" class="control-label">pallets given</label>
                                                        </div>
                                                        <div class="col-lg-2 text-center" id="palletsTaken" @if(isset($type) && $type=='Withdrawal_Only' )style="display: block;" @else style="display:none;" @endif>
                                                            <label for="palletsNumber" class="control-label">pallets taken</label>
                                                        </div>
                                                        <div class="col-lg-2 text-center" id="palletsBought" @if((isset($type) && ($type=='Purchase-Sale'))|| (!isset($type) && (isset($showAddCorrectingTransfer) ||( isset($actionForm) && explode('-', $actionForm)[0]=='showAddCorrectingTransfer'))))style="display: block;" @else style="display:none;" @endif>
                                                            <label for="palletsNumber" class="control-label">pallets bought</label>
                                                        </div>
                                                        <div class="col-lg-2 text-center" id="pallets" @if(isset($type) && ($type=='Debt' || $type=='Other'))style="display: block;" @else style="display:none;" @endif>
                                                            <label for="palletsNumber" class="control-label">pallets</label>
                                                        </div>
                                                        <!-- debit account -->
                                                        <div class="col-lg-1 text-center">
                                                            <label for="debitAccount" class="control-label">from</label>
                                                        </div>
                                                        @if(isset($truckAssociated))
                                                            <input type="hidden" name="truckAssociatedId" id="truckAssociatedId" value="{{$truckAssociated->id}}"/>
                                                            <input type="hidden" name="truckAssociatedName" id="truckAssociatedName" value="{{$truckAssociated->name}}"/>
                                                            <input type="hidden" name="truckAssociatedLicensePlate" id="truckAssociatedLicensePlate" value="{{$truckAssociated->licensePlate}}"/>
                                                        @endif
                                                        <!-- deposit withdrawal or deposit only : truck or wenzel -->
                                                        <div class="col-lg-3" id="debitAccountDWD"  @if((isset($type) && ($type<>'Deposit-Withdrawal' || $type<>'Deposit_Only'))||(!isset($type) && !(isset($showAddCorrectingTransfer) || (isset($actionForm) && explode('-', $actionForm)[0]=='showAddCorrectingTransfer')))) style="display: block;" @else style="display:none;" @endif>
                                                            <input type="hidden" name="debitAccountDWD" id="input-debitAccountDWD" value=""/>
                                                            <select class="selectpicker show-tick form-control" data-size="10" data-live-search="true" data-live-search-style="startsWith" title="Account (gives pallets)" name="select-debitAccountDWD" id="select-debitAccountDWD" disabled="true">
                                                                @if($loading->notExchange==1 || (isset($notExchanging) && $notExchanging==1))
                                                                    <option value="account-1" selected>WENZEL</option>
                                                                @elseif(isset($truckAssociated))
                                                                    <option value="truck-{{$truckAssociated->id}}" selected>{{$truckAssociated->name}} - {{$truckAssociated->licensePlate}}</option>
                                                                @else
                                                                    <option selected></option>
                                                                @endif
                                                            </select>
                                                        </div>
                                                        <!-- withdrawal only : all accounts possible OR debt OR other-->
                                                        <div class="col-lg-3" id="debitAccountWDebtOther"  @if(isset($type) && ($type == 'Withdrawal_Only' || $type=='Debt' || $type=='Other')) style="display: block" @else style="display:none;" @endif >
                                                            <select class="selectpicker show-tick form-control" data-size="10" data-live-search="true" data-live-search-style="startsWith" title="Account (gives pallets)" name="debitAccountWDebtOther" id="select-debitAccountWDebtOther" onchange="updateFieldsNormal();" data-style="requiredField">
                                                                @foreach($listPalletsAccounts as $palletsAccount )
                                                                    @if((Illuminate\Support\Facades\Input::old('debitAccountWDebtOther') && (strpos(old('debitAccountWDebtOther'), '-') == 7 && explode('-', old('debitAccountWDebtOther'))[0] == 'account') && ($palletsAccount->id==explode('-', old('debitAccountWDebtOther'))[1]))||(isset($debitAccount) && (strpos($debitAccount, '-') == 7 && explode('-', $debitAccount)[0] == 'account') && ($palletsAccount->id==explode('-', $debitAccount)[1])))
                                                                        <option value="account-{{$palletsAccount->id}}" selected>{{$palletsAccount->name}}</option>
                                                                    @else
                                                                        <option value="account-{{$palletsAccount->id}}">{{$palletsAccount->name}}</option>
                                                                    @endif
                                                                @endforeach
                                                                    @foreach($listTrucksAccounts as $trucksAccount )
                                                                        @if((Illuminate\Support\Facades\Input::old('debitAccountWDebtOther') && (strpos(old('debitAccountWDebtOther'), '-') == 5 && explode('-', old('debitAccountWDebtOther'))[0] == 'truck') && ($trucksAccount->id==explode('-', old('debitAccountWDebtOther'))[1]))|| (isset($debitAccount)&& (strpos($debitAccount, '-') == 5 && explode('-', $debitAccount)[0] == 'truck') && ($trucksAccount->id==explode('-', $debitAccount)[1])))
                                                                            <option value="truck-{{$trucksAccount->id}}" selected>{{$trucksAccount->name}}
                                                                                - {{$trucksAccount->licensePlate}}</option>
                                                                        @else
                                                                            <option value="truck-{{$trucksAccount->id}}">{{$trucksAccount->name}}
                                                                                - {{$trucksAccount->licensePlate}}</option>
                                                                        @endif
                                                                    @endforeach
                                                            </select>
                                                        </div>
                                                        <!-- purchase-sale : only accounts that were on the normal transfer associated -->
                                                        <div class="col-lg-3" id="debitAccountPS" @if((isset($type) && ($type=='Purchase-Sale'))|| (!isset($type) && (isset($showAddCorrectingTransfer) ||( isset($actionForm) && explode('-', $actionForm)[0]=='showAddCorrectingTransfer')))) style="display: block;" @else style="display: none;" @endif>
                                                            <select class="selectpicker show-tick form-control" data-size="10" data-live-search="true" data-live-search-style="startsWith" id="select-debitAccountPS" title="Account (sales pallets)" name="debitAccountPS" onchange="selectAccount();" data-style="requiredField">
                                                                @if(isset($debitAccountCorr)&& isset($creditAccountCorr))
                                                                    @php($partsDebitAccount=explode('-',$debitAccountCorr))
                                                                    @php($idDC=$partsDebitAccount[count($partsDebitAccount)-1])
                                                                    @php($typeDC=$partsDebitAccount[count($partsDebitAccount)-2])
                                                                    @if(count(array_diff ($partsDebitAccount, [$idDC, $typeDC]))==1)
                                                                        @php($debitAccountC=array_diff ($partsDebitAccount, [$idDC, $typeDC])[0])
                                                                    @else
                                                                        @php($debitAccountC=implode( ' - ', array_diff ($partsDebitAccount, [$idDC, $typeDC])))
                                                                    @endif
                                                                    @php($partsCreditAccount=explode('-',$creditAccountCorr))
                                                                    @php($idCC=$partsCreditAccount[count($partsCreditAccount)-1])
                                                                    @php($typeCC=$partsCreditAccount[count($partsCreditAccount)-2])
                                                                    @if(count(array_diff ($partsCreditAccount, [$idCC, $typeCC]))==1)
                                                                        @php($creditAccountC=array_diff ($partsCreditAccount, [$idCC, $typeCC])[0])
                                                                    @else
                                                                        @php($creditAccountC=implode( ' - ', array_diff ($partsCreditAccount, [$idCC, $typeCC])))
                                                                    @endif
                                                                    <option value="account-1">WENZEL</option>
                                                                    @if($typeDC=='truck')
                                                                        <option value="truck-{{$idDC}}">{{$debitAccountC}}</option>
                                                                    @elseif($typeDC=='account')
                                                                        <option value="account-{{$idDC}}">{{$debitAccountC}}</option>
                                                                    @endif
                                                                    @if($typeCC=='truck')
                                                                        <option value="truck-{{$idCC}}">{{$creditAccountC}}</option>
                                                                    @elseif($typeCC=='account')
                                                                        <option value="account-{{$idCC}}">{{$creditAccountC}}</option>
                                                                    @endif
                                                                @elseif(isset($debitAccount))
                                                                    @if (strpos($debitAccount, '-') == 5 && explode('-', $debitAccount)[0] == 'truck')
                                                                        @php($nameTruckAccount = App\Truck::where('id', explode('-', $debitAccount)[1])->value('name'))
                                                                        @php($licensePlate = App\Truck::where('id', explode('-', $debitAccount)[1])->value('licensePlate'))
                                                                        <option selected value="truck-{{explode('-', $debitAccount)[1]}}">{{$nameTruckAccount}} - {{$licensePlate}}</option>
                                                                    @elseif (strpos($debitAccount, '-') == 7 && explode('-', $debitAccount)[0] == 'account')
                                                                        @php($namePalletsAccount = App\Palletsaccount::where('id', explode('-', $debitAccount)[1])->value('name'))
                                                                        <option selected value="account-{{explode('-', $debitAccount)[1]}}">{{$namePalletsAccount}}</option>
                                                                    @endif
                                                                @endif
                                                            </select>
                                                        </div>
                                                        <!-- credit account -->
                                                        <div class="col-lg-1 text-center">
                                                            <label for="creditAccount" class="control-label">to</label>
                                                            {{--@if((isset($type) && ($type=='Deposit-Withdrawal' || $type=='Purchase-Sale' || $type=='Other' || $type=='Debt'))|| !(isset($type)))--}}
                                                            {{--<a href="#" class="link" onclick="inverseAccounts(this);" id="inverseAccount1" name="inverseAccount1">(<span class="glyphicon glyphicon-resize-horizontal"></span>)</a>--}}
                                                            {{--@endif--}}
                                                        </div>
                                                        <!-- deposit withdrawal : only network and other accounts -->
                                                        <div class="col-lg-3" id="creditAccountDW"  @if((isset($type) && ($type<>'Deposit-Withdrawal'))||(!isset($type) && !(isset($showAddCorrectingTransfer) || (isset($actionForm) && explode('-', $actionForm)[0]=='showAddCorrectingTransfer')))) style="display: block;" @else style="display:none;" @endif >
                                                            <select class="selectpicker show-tick form-control" data-size="10" data-live-search="true" data-live-search-style="startsWith" title="Account (takes pallets)" name="creditAccountDW" id="select-creditAccountDW" onchange="updateFieldsNormal();" data-style="requiredField">
                                                                @foreach($listPalletsAccounts as $palletsAccount )
                                                                    @if((Illuminate\Support\Facades\Input::old('creditAccountDW') && (strpos(old('creditAccountDW'), '-') == 7 && explode('-', old('creditAccountDW'))[0] == 'account') && ($palletsAccount->id==explode('-', old('creditAccountDW'))[1]))||(isset($creditAccount) && (strpos($creditAccount, '-') == 7 && explode('-', $creditAccount)[0] == 'account') && ($palletsAccount->id==explode('-', $creditAccount)[1])))
                                                                        <option value="account-{{$palletsAccount->id}}" selected>{{$palletsAccount->name}}</option>
                                                                    @else
                                                                        <option value="account-{{$palletsAccount->id}}">{{$palletsAccount->name}}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <!-- deposit only : all accounts possible OR debt OR other-->
                                                        <div class="col-lg-3" id="creditAccountDDebtOther"  @if(isset($type) && ($type == 'Deposit_Only' || $type == 'Debt' || $type =='Other')) style="display: block;" @else style="display:none;" @endif>
                                                            <select class="selectpicker show-tick form-control" data-size="10" data-live-search="true" data-live-search-style="startsWith" id="select-creditAccountDDebtOther" title="Account (takes pallets)" name="creditAccountDDebtOther" data-style="requiredField" onchange="updateFieldsNormal();">
                                                                @foreach($listPalletsAccounts as $palletsAccount )
                                                                    @if((Illuminate\Support\Facades\Input::old('creditAccountDDebtOther') && (strpos(old('creditAccountDDebtOther'), '-') == 7 && explode('-', old('creditAccountDDebtOther'))[0] == 'account') && ($palletsAccount->id==explode('-', old('creditAccountDDebtOther'))[1]))||(isset($creditAccount) && (strpos($creditAccount, '-') == 7 && explode('-', $creditAccount)[0] == 'account') && ($palletsAccount->id==explode('-', $creditAccount)[1])))
                                                                        <option value="account-{{$palletsAccount->id}}" selected>{{$palletsAccount->name}}</option>
                                                                    @else
                                                                        <option value="account-{{$palletsAccount->id}}">{{$palletsAccount->name}}</option>
                                                                    @endif
                                                                @endforeach
                                                                @foreach($listTrucksAccounts as $trucksAccount )
                                                                        @if((Illuminate\Support\Facades\Input::old('creditAccountDDebtOther') && (strpos(old('creditAccountDDebtOther'), '-') == 5 && explode('-', old('creditAccountD'))[0] == 'truck') && ($trucksAccount->id==explode('-', old('creditAccountDDebtOther'))[1]))|| (isset($creditAccount)&& (strpos($creditAccount, '-') == 5 && explode('-', $creditAccount)[0] == 'truck') && ($trucksAccount->id==explode('-', $creditAccount)[1])))
                                                                            <option value="truck-{{$trucksAccount->id}}" selected>{{$trucksAccount->name}}
                                                                                - {{$trucksAccount->licensePlate}}</option>
                                                                        @else
                                                                            <option value="truck-{{$trucksAccount->id}}">{{$trucksAccount->name}}
                                                                                - {{$trucksAccount->licensePlate}}</option>
                                                                        @endif
                                                                    @endforeach
                                                            </select>
                                                        </div>
                                                        <!-- withdrawal only : truck or wenzel -->
                                                        <div class="col-lg-3" id="creditAccountW"  @if(isset($type) && ($type == 'Withdrawal_Only')) style="display: block;" @else style="display:none;" @endif >
                                                            <input type="hidden" name="creditAccountW" id="input-creditAccountW" value=""/>
                                                            <select class="selectpicker show-tick form-control" data-size="10" data-live-search="true" data-live-search-style="startsWith" title="Account (takes pallets)" name="creditAccountW" id="select-creditAccountW" disabled="true">
                                                                @if($loading->notExchange==1)
                                                                    <option value="account-1" selected>WENZEL</option>
                                                                @elseif(isset($truckAssociated))
                                                                    <option value="truck-{{$truckAssociated->id}}" selected>{{$truckAssociated->name}} - {{$truckAssociated->licensePlate}}</option>
                                                                @else
                                                                    <option selected></option>
                                                                @endif
                                                            </select>
                                                        </div>
                                                        <!-- purchase-sale : only accounts that were on the normal transfer associated -->
                                                        <div class="col-lg-3" id="creditAccountPS" @if((isset($type) && ($type=='Purchase-Sale'))|| (!isset($type) && (isset($showAddCorrectingTransfer) ||( isset($actionForm) && explode('-', $actionForm)[0]=='showAddCorrectingTransfer')))) style="display: block;" @else style="display: none;" @endif>
                                                            <select class="selectpicker show-tick form-control" data-size="10" data-live-search="true" data-live-search-style="startsWith" title="Account (purchases pallets)" name="creditAccountPS" id="select-creditAccountPS" data-style="requiredField">
                                                                @if(isset($debitAccountCorr) && isset($creditAccountCorr))
                                                                    <option value="account-1"> WENZEL </option>
                                                                    @if($typeDC=='truck')
                                                                        <option value="truck-{{$idDC}}">{{$debitAccountC}}</option>
                                                                    @elseif($typeDC=='account')
                                                                        <option value="account-{{$idDC}}">{{$debitAccountC}}</option>
                                                                    @endif
                                                                    @if($typeCC=='truck')
                                                                        <option value="truck-{{$idCC}}">{{$creditAccountC}}</option>
                                                                    @elseif($typeCC=='account')
                                                                        <option value="account-{{$idCC}}">{{$creditAccountC}}</option>
                                                                    @endif
                                                                @elseif(isset($creditAccount))
                                                                    @if (strpos($creditAccount, '-') == 5 && explode('-', $creditAccount)[0] == 'truck')
                                                                        @php($nameTruckAccount = App\Truck::where('id', explode('-', $creditAccount)[1])->value('name'))
                                                                        @php($licensePlate = App\Truck::where('id', explode('-', $creditAccount)[1])->value('licensePlate'))
                                                                        <option selected value="truck-{{explode('-', $creditAccount)[1]}}">{{$nameTruckAccount}}
                                                                            - {{$licensePlate}}</option>
                                                                    @elseif (strpos($creditAccount, '-') == 7 && explode('-', $creditAccount)[0] == 'account')
                                                                        @php($namePalletsAccount = App\Palletsaccount::where('id', explode('-', $creditAccount)[1])->value('name'))
                                                                        <option selected value="account-{{explode('-', $creditAccount)[1]}}">{{$namePalletsAccount}}</option>
                                                                    @endif
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <!--withdrawal title-->
                                                    <div class="form-group" id="deposit-withdrawal2" @if((isset($type) && ($type<>'Deposit-Withdrawal'))||(!isset($type) && !(isset($showAddCorrectingTransfer) || (isset($actionForm) && explode('-', $actionForm)[0]=='showAddCorrectingTransfer')))) style="display:block;" @else style="display: none;" @endif>
                                                        <div class="col-lg-12 text-center">
                                                            <label for="withdrawal" class="control-label">WITHDRAWAL</label>
                                                        </div>
                                                    </div>
                                                    <!--sale title-->
                                                    <div class="form-group" id="purchase-sale2" @if((isset($type) && ($type=='Purchase-Sale'))|| (!isset($type) && (isset($showAddCorrectingTransfer) ||( isset($actionForm) && explode('-', $actionForm)[0]=='showAddCorrectingTransfer')))) style="display:block;" @else style="display: none;" @endif>
                                                        <div class="col-lg-12 text-center">
                                                            <label for="sale" class="control-label">SALE</label>
                                                        </div>
                                                    </div>
                                                    <!--2nd transfer DW -->
                                                    <div id="DW" @if((isset($type) && ($type<>'Deposit-Withdrawal'))||(!isset($type) && !(isset($showAddCorrectingTransfer) || (isset($actionForm) && explode('-', $actionForm)[0]=='showAddCorrectingTransfer')))) style="display:block;" @else style="display:none;"@endif>
                                                        <div class="form-group">
                                                            <!--number of pallets 2-->
                                                            <div class="col-lg-2">
                                                                <div class="input-group requiredField">
                                                                    <span class="input-group-btn">
                                                                        <button type="button" class="btn btn-default btn-number" data-type="minus" data-field="palletsNumber2DW">
                                                                            <span class="glyphicon glyphicon-minus"></span>
                                                                        </button>
                                                                    </span>
                                                                    <input id="palletsNumber2DW" type="number" name="palletsNumber2DW"
                                                                               class="form-control input-number text-center"  @if(isset($palletsNumber2)) value="{{$palletsNumber2}}"
                                                                               @elseif(Illuminate\Support\Facades\Input::old('palletsNumber2DW')) value="{{ old('palletsNumber2DW') }}"
                                                                               @else value="0" @endif placeholder="Nbr" min="0" max="999999" autofocus onchange="updateFieldsNormal();">
                                                                    <span class="input-group-btn">
                                                                        <button type="button" class="btn btn-default btn-number" data-type="plus" data-field="palletsNumber2DW">
                                                                            <span class="glyphicon glyphicon-plus"></span>
                                                                        </button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-2 text-center">
                                                                <label for="palletsNumber2DW" class="control-label">pallets taken</label>
                                                            </div>
                                                            <!-- debit account -->
                                                            <div class="col-lg-1 text-center">
                                                                <label for="debitAccount2" class="control-label">from</label>
                                                            </div>
                                                            <!-- deposit withdrawal : debit account 2 = credit account 1 -->
                                                            <div class="col-lg-3" id="debitAccount2DW">
                                                                <input type="hidden" name="debitAccount2DW" id="input-debitAccount2DW" @if(isset($debitAccount2)) value="{{$debitAccount2}}" @endif readonly/>
                                                                <select class="selectpicker show-tick form-control" data-size="10" data-live-search="true" data-live-search-style="startsWith" title="Account (gives pallets)" name="select-debitAccount2DW" id="select-debitAccount2DW" disabled="true">
                                                                    @if (isset($debitAccount2) && strpos($debitAccount2, '-') == 5 && explode('-', $debitAccount2)[0] == 'truck')
                                                                        @php($nameTruckAccount = App\Truck::where('id', explode('-', $debitAccount2)[1])->value('name'))
                                                                        @php($licensePlate = App\Truck::where('id', explode('-', $debitAccount2)[1])->value('licensePlate'))
                                                                        <option selected value="truck-{{explode('-', $debitAccount2)[1]}}">{{$nameTruckAccount}} - {{$licensePlate}}</option>
                                                                    @elseif (isset($debitAccount2) && strpos($debitAccount2, '-') == 7 && explode('-', $debitAccount2)[0] == 'account')
                                                                        @php($namePalletsAccount = App\Palletsaccount::where('id', explode('-', $debitAccount2)[1])->value('name'))
                                                                        <option selected value="account-{{explode('-', $debitAccount2)[1]}}">{{$namePalletsAccount}}</option>
                                                                    @endif
                                                                </select>
                                                            </div>
                                                            <!-- credit account -->
                                                            <div class="col-lg-1 text-center">
                                                                <label for="creditAccount2" class="control-label">to</label>
                                                            </div>
                                                            <!-- deposit withdrawal : credit account 2 = debit account 1 -->
                                                            <div class="col-lg-3" id="creditAccount2DW">
                                                                <input type="hidden" name="creditAccount2DW" id="input-creditAccount2DW" @if(isset($creditAccount2)) value="{{$creditAccount2}}" @endif readonly/>
                                                                <select class="selectpicker show-tick form-control" data-size="10" data-live-search="true" data-live-search-style="startsWith" title="Account (takes pallets)" name="select-creditAccount2DW" id="select-creditAccount2DW" disabled="true">
                                                                    @if (isset($creditAccount2) && strpos($creditAccount2, '-') == 5 && explode('-', $creditAccount2)[0] == 'truck')
                                                                        @php($nameTruckAccount = App\Truck::where('id', explode('-', $creditAccount2)[1])->value('name'))
                                                                        @php($licensePlate = App\Truck::where('id', explode('-', $creditAccount2)[1])->value('licensePlate'))
                                                                        <option selected value="truck-{{explode('-', $creditAccount2)[1]}}">{{$nameTruckAccount}} - {{$licensePlate}}</option>
                                                                    @elseif (isset($creditAccount2) && strpos($creditAccount2, '-') == 7 && explode('-', $creditAccount2)[0] == 'account')
                                                                        @php($namePalletsAccount = App\Palletsaccount::where('id', explode('-', $creditAccount2)[1])->value('name'))
                                                                        <option selected value="account-{{explode('-', $creditAccount2)[1]}}">{{$namePalletsAccount}}</option>
                                                                    @endif
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!--2nd transfer Sale-Purchase : pallets number credit account and debit account-->
                                                    <div id="PS" @if((isset($type) && ($type=='Purchase-Sale'))|| (!isset($type) && (isset($showAddCorrectingTransfer) ||( isset($actionForm) && explode('-', $actionForm)[0]=='showAddCorrectingTransfer')))) style="display:block;" @else  style="display:none;" @endif>
                                                        <div class="form-group">
                                                            <!--number of pallets 2-->
                                                            <div class="col-lg-2">
                                                                <div class="input-group requiredField">
                                                                    <span class="input-group-btn">
                                                                        <button type="button" class="btn btn-default btn-number" data-type="minus" data-field="palletsNumber2PS">
                                                                            <span class="glyphicon glyphicon-minus"></span>
                                                                        </button>
                                                                    </span>
                                                                    <input id="palletsNumber2PS" type="number" name="palletsNumber2PS"
                                                                           class="form-control input-number text-center"  @if(isset($palletsNumber2)) value="{{$palletsNumber2}}"
                                                                           @elseif(Illuminate\Support\Facades\Input::old('palletsNumber2PS')) value="{{ old('palletsNumber2PS') }}"
                                                                           @else value="0" @endif placeholder="Nbr" min="0" max="999999" autofocus>
                                                                    <span class="input-group-btn">
                                                                        <button type="button" class="btn btn-default btn-number" data-type="plus" data-field="palletsNumber2PS">
                                                                            <span class="glyphicon glyphicon-plus"></span>
                                                                        </button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-2 text-center">
                                                                <label for="palletsNumber2PS" class="control-label">pallets sold</label>
                                                            </div>
                                                            <!-- debit account -->
                                                            <div class="col-lg-1 text-center">
                                                                <label for="debitAccount2PS" class="control-label">from</label>
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <select class="selectpicker show-tick form-control" data-size="10" data-live-search="true" data-live-search-style="startsWith" title="Account (sales pallets)" name="debitAccount2PS" id="select-debitAccount2PS" data-style="requiredField">
                                                                    @if(isset($debitAccountCorr) && isset($creditAccountCorr))
                                                                        <option value="account-1">WENZEL</option>
                                                                        @if($typeDC=='truck')
                                                                            <option value="truck-{{$idDC}}">{{$debitAccountC}}</option>
                                                                        @elseif($typeDC=='account')
                                                                            <option value="account-{{$idDC}}">{{$debitAccountC}}</option>
                                                                        @endif
                                                                        @if($typeCC=='truck')
                                                                            <option value="truck-{{$idCC}}">{{$creditAccountC}}</option>
                                                                        @elseif($typeCC=='account')
                                                                            <option value="account-{{$idCC}}">{{$creditAccountC}}</option>
                                                                        @endif
                                                                    @elseif(isset($debitAccount2))
                                                                        @if (strpos($debitAccount2, '-') == 5 && explode('-', $debitAccount2)[0] == 'truck')
                                                                            @php($nameTruckAccount = App\Truck::where('id', explode('-', $debitAccount2)[1])->value('name'))
                                                                            @php($licensePlate = App\Truck::where('id', explode('-', $debitAccount2)[1])->value('licensePlate'))
                                                                            <option selected
                                                                                    value="truck-{{explode('-', $debitAccount2)[1]}}">{{$nameTruckAccount}}
                                                                                - {{$licensePlate}}</option>
                                                                        @elseif (strpos($debitAccount2, '-') == 7 && explode('-', $debitAccount2)[0] == 'account')
                                                                            @php($namePalletsAccount = App\Palletsaccount::where('id', explode('-', $debitAccount2)[1])->value('name'))
                                                                            <option selected
                                                                                    value="account-{{explode('-', $debitAccount2)[1]}}">{{$namePalletsAccount}}</option>
                                                                        @endif
                                                                    @endif
                                                                </select>
                                                            </div>
                                                            <!-- credit account -->
                                                            <div class="col-lg-1 text-center">
                                                                <label for="creditAccount2PS" class="control-label">to</label>
                                                                {{--@if((isset($type) && $type=='Purchase-Sale' )))--}}
                                                                    {{--<a href="#" class="link" onclick="inverseAccounts(this);" id="inverseAccount2" name="inverseAccount2">(<span class="glyphicon glyphicon-resize-horizontal"></span>)</a>--}}
                                                                {{--@endif--}}
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <select class="selectpicker show-tick form-control" data-size="10" data-live-search="true" data-live-search-style="startsWith" title="Account (purchase pallets)" name="creditAccount2PS" id="select-creditAccount2PS" data-style="requiredField">
                                                                    @if(isset($debitAccountCorr) && isset($creditAccountCorr))
                                                                        <option value="account-1">WENZEL</option>
                                                                        @if($typeDC=='truck')
                                                                            <option value="truck-{{$idDC}}">{{$debitAccountC}}</option>
                                                                        @elseif($typeDC=='account')
                                                                            <option value="account-{{$idDC}}">{{$debitAccountC}}</option>
                                                                        @endif
                                                                        @if($typeCC=='truck')
                                                                            <option value="truck-{{$idCC}}">{{$creditAccountC}}</option>
                                                                        @elseif($typeCC=='account')
                                                                            <option value="account-{{$idCC}}">{{$creditAccountC}}</option>
                                                                        @endif
                                                                    @elseif(isset($creditAccount2))
                                                                        @if (strpos($creditAccount2, '-') == 5 && explode('-', $creditAccount2)[0] == 'truck')
                                                                            @php($nameTruckAccount = App\Truck::where('id', explode('-', $creditAccount2)[1])->value('name'))
                                                                            @php($licensePlate = App\Truck::where('id', explode('-', $creditAccount2)[1])->value('licensePlate'))
                                                                            <option selected value="truck-{{explode('-', $creditAccount2)[1]}}">{{$nameTruckAccount}}
                                                                                - {{$licensePlate}}</option>
                                                                        @elseif (strpos($creditAccount2, '-') == 7 && explode('-', $creditAccount2)[0] == 'account')
                                                                            @php($namePalletsAccount = App\Palletsaccount::where('id', explode('-', $creditAccount2)[1])->value('name'))
                                                                            <option selected value="account-{{explode('-', $creditAccount2)[1]}}">{{$namePalletsAccount}}</option>
                                                                        @endif
                                                                    @endif
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!--3rd transfer : Debt - pallets number credit account and debit account-->
                                                    <div id="debt" @if(isset($type) &&($type=='Deposit-Withdrawal' || $type=='Deposit_Only' || $type=='Withdrawal_Only') && $loading->notExchange==1 && (isset($palletsNumber) && $palletsNumber < $loading->anz || isset($palletsNumber2) && $palletsNumber2 < $loading->anz)) style="display:block;" @else  style="display:none;" @endif>
                                                        <div class="form-group">
                                                            <div class="col-lg-12 text-center">
                                                                <label for="debt" class="control-label">DEBT</label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group" id="debt1" @if(isset($type) &&(($type=='Deposit-Withdrawal' && isset($palletsNumber) && $palletsNumber < $loading->anz)|| ($type=='Withdrawal_Only')) && $loading->notExchange==1) style="display:block;" @else  style="display:none;" @endif>
                                                            <div class="col-lg-3">
                                                                <input type="hidden" name="debitAccount3a" id="debitAccount3a" value="account-1"/>
                                                                <select class="selectpicker show-tick form-control text-center" data-size="10" data-live-search="true" data-live-search-style="startsWith" title="Account" name="select-debitAccount3a" id="select-debitAccount3a" disabled="true">
                                                                    <option selected value="account-1">WENZEL</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <p class="text-center">has a debt of</p>
                                                            </div>
                                                            <div class="col-lg-1">
                                                                <input class="form-control text-center" type="text" id="palletsNumber3a" name="palletsNumber3a" readonly @if(isset($palletsNumber3) && isset($type) && ($type=='Deposit-Withdrawal'||$type=='Withdrawal_Only')) value="{{$palletsNumber3}}" @else value="" @endif/>
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <p class="text-center"> pallets toward</p>
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <select class="selectpicker show-tick form-control text-center" data-size="10" data-live-search="true" data-live-search-style="startsWith" title="Account (takes pallets)" name="creditAccount3a" id="creditAccount3a" data-style="requiredField">
                                                                    @foreach($listPalletsAccounts as $palletsAccount )
                                                                        @if((Illuminate\Support\Facades\Input::old('creditAccount3a') && (strpos(old('creditAccount3a'), '-') == 7 && explode('-', old('creditAccount3a'))[0] == 'account') && ($palletsAccount->id==explode('-', old('creditAccount3a'))[1]))||(isset($creditAccount3) && (strpos($creditAccount3, '-') == 7 && explode('-', $creditAccount3)[0] == 'account') && ($palletsAccount->id==explode('-', $creditAccount3)[1])))
                                                                            <option value="account-{{$palletsAccount->id}}" selected>{{$palletsAccount->name}}</option>
                                                                        @else
                                                                            <option value="account-{{$palletsAccount->id}}">{{$palletsAccount->name}}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group" id="debt2" @if(isset($type) &&(($type=='Deposit-Withdrawal' && isset($palletsNumber2) && $palletsNumber2 < $loading->anz)|| ($type=='Deposit_Only')) && $loading->notExchange==1 ) style="display:block;" @else  style="display:none;" @endif>
                                                            <div class="col-lg-3">
                                                                <select class="selectpicker show-tick form-control text-center" data-size="10" data-live-search="true" data-live-search-style="startsWith" title="Account (gives pallets)" name="debitAccount3b" id="debitAccount3b" data-style="requiredField">
                                                                    @foreach($listPalletsAccounts as $palletsAccount )
                                                                        @if((Illuminate\Support\Facades\Input::old('debitAccount3b') && (strpos(old('debitAccount3b'), '-') == 7 && explode('-', old('debitAccount3b'))[0] == 'account') && ($palletsAccount->id==explode('-', old('debitAccount3b'))[1]))||(isset($debitAccount3) && (strpos($debitAccount3, '-') == 7 && explode('-', $debitAccount3)[0] == 'account') && ($palletsAccount->id==explode('-', $debitAccount3)[1])))
                                                                            <option value="account-{{$palletsAccount->id}}" selected>{{$palletsAccount->name}}</option>
                                                                        @else
                                                                            <option value="account-{{$palletsAccount->id}}">{{$palletsAccount->name}}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <p class="text-center">has a debt of</p>
                                                            </div>
                                                            <div class="col-lg-1">
                                                                <input class="form-control text-center" type="text" id="palletsNumber3b" name="palletsNumber3b" readonly @if(isset($palletsNumber3) && isset($type) && ($type=='Deposit-Withdrawal' || $type=='Deposit_Only')) value="{{$palletsNumber3}}" @else value="" @endif/>
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <p class="text-center">pallets toward</p>
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <input type="hidden" name="creditAccount3b" id="creditAccount3b" value="account-1"/>
                                                                <select class="selectpicker show-tick form-control text-center" data-size="10" data-live-search="true" data-live-search-style="startsWith" title="Account" name="select-creditAccount3b" id="select-creditAccount3b" disabled="true">
                                                                    <option selected value="account-1">WENZEL</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!--btn submit add-->
                                                    <div class="form-group">
                                                        <div class="col-lg-4 col-lg-offset-4">
                                                            <button type="submit" class="btn btn-add btn-block" value="addPalletstransfer" name="addPalletstransfer"  id="addPalletstransfer" data-toggle="modal" data-target="#submitAdd_modal" onclick="formSubmitBlock(this);">
                                                                Add
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <!-- Modal submit -->
                                    @if(isset($actionForm) && $actionForm=='addPalletstransfer')
                                        <div class="modal show" id="submitAdd_modal" role="dialog">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header modalHeaderTransfer">
                                                        <button type="submit" class="close" value="closeSubmitAddModal" name="closeSubmitAddModal" id="closeSubmitAddModalb" onclick="formSubmitBlock(this);">
                                                                &times;
                                                        </button>
                                                        <h4 class="modal-title text-center">INFORMATION</h4>
                                                    </div>
                                                    <div class="modal-body center modalBodyTransfer">
                                                        <p class="text-center">Here, PLANNED pallets number</p>
                                                        <table class="table table-hover table-bordered">
                                                            <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th class="text-center">DEBIT</th>
                                                                <th class="text-center">CREDIT</th>
                                                                @if(Session::has('creditAccount2')&&Session::has('debitAccount2')&&Session::has('palletsNumber2'))
                                                                    <th class="text-center">DEBIT 2</th>
                                                                    <th class="text-center">CREDIT 2</th>
                                                                @endif
                                                                @if(Session::has('creditAccount3')&&Session::has('debitAccount3')&&Session::has('palletsNumber3'))
                                                                    <th class="text-center">DEBIT 3 (debt)</th>
                                                                    <th class="text-center">CREDIT 3 (debt)</th>
                                                                @endif
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td></td>
                                                                <td class="text-center">{{request()->session()->get('debitAccount')}}</td>
                                                                <td class="text-center">{{request()->session()->get('creditAccount')}}</td>
                                                                @if(Session::has('creditAccount2')&&Session::has('debitAccount2')&&Session::has('palletsNumber2'))
                                                                    <td class="text-center">{{request()->session()->get('debitAccount2')}}</td>
                                                                    <td class="text-center">{{request()->session()->get('creditAccount2')}}</td>
                                                                @endif
                                                                @if(Session::has('creditAccount3')&&Session::has('debitAccount3')&&Session::has('palletsNumber3'))
                                                                    <td class="text-center">{{request()->session()->get('debitAccount3')}}</td>
                                                                    <td class="text-center">{{request()->session()->get('creditAccount3')}}</td>
                                                                @endif
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center"> Actual</td>
                                                                <td class="text-center">{{request()->session()->get('palletsNumberDebitAccount')}}</td>
                                                                <td class="text-center">{{request()->session()->get('palletsNumberCreditAccount')}}</td>
                                                                @if(Session::has('creditAccount2')&&Session::has('debitAccount2')&&Session::has('palletsNumber2'))
                                                                    <td class="text-center">{{request()->session()->get('palletsNumberDebitAccount2')}}</td>
                                                                    <td class="text-center">{{request()->session()->get('palletsNumberCreditAccount2')}}</td>
                                                                @endif
                                                                @if(Session::has('creditAccount3')&&Session::has('debitAccount3')&&Session::has('palletsNumber3'))
                                                                    <td class="text-center"> {{request()->session()->get('palletsNumberDebitAccount3')}}</td>
                                                                    <td class="text-center">{{request()->session()->get('palletsNumberCreditAccount3')}}</td>
                                                                @endif
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center"> New transfer</td>
                                                                <td class="text-center">  - {{request()->session()->get('palletsNumber')}}</td>
                                                                <td class="text-center">  + {{request()->session()->get('palletsNumber')}}</td>
                                                                @if(Session::has('creditAccount2')&&Session::has('debitAccount2')&&Session::has('palletsNumber2'))
                                                                    <td class="text-center"> - {{request()->session()->get('palletsNumber2')}}</td>
                                                                    <td class="text-center"> + {{request()->session()->get('palletsNumber2')}}</td>
                                                                @endif
                                                                @if(Session::has('creditAccount3')&&Session::has('debitAccount3')&&Session::has('palletsNumber3'))
                                                                    <td class="text-center"> - {{request()->session()->get('palletsNumber3')}}</td>
                                                                    <td class="text-center"> + {{request()->session()->get('palletsNumber3')}}</td>
                                                                @endif
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center">Total</td>
                                                                <td class="text-center"> = {{request()->session()->get('palletsNumberDebitAccount') -request()->session()->get('palletsNumber')}}</td>
                                                                <td class="text-center"> = {{request()->session()->get('palletsNumberCreditAccount')+request()->session()->get('palletsNumber')}}</td>
                                                                @if(Session::has('creditAccount2')&&Session::has('debitAccount2')&&Session::has('palletsNumber2'))
                                                                    <td class="text-center"> = {{request()->session()->get('palletsNumberDebitAccount2') -request()->session()->get('palletsNumber2')}}</td>
                                                                    <td class="text-center"> = {{request()->session()->get('palletsNumberCreditAccount2')+request()->session()->get('palletsNumber2')}}</td>
                                                                @endif
                                                                @if(Session::has('creditAccount3')&&Session::has('debitAccount3')&&Session::has('palletsNumber3'))
                                                                    <td class="text-center"> = {{request()->session()->get('palletsNumberDebitAccount3') -request()->session()->get('palletsNumber3')}}</td>
                                                                    <td class="text-center"> = {{request()->session()->get('palletsNumberCreditAccount3')+request()->session()->get('palletsNumber3')}}</td>
                                                                @endif
                                                            </tr>
                                                            </tbody>
                                                        </table>

                                                            {{--@if(($type=='Deposit-Withdrawal' || $type=='Withdrawal-Deposit'|| $type=='Purchase-Sale')&&(Session::has('creditAccount2')&&Session::has('debitAccount2')&&Session::has('palletsNumber2')&& (request()->session()->get('palletsNumber2')<>request()->session()->get('palletsNumber'))))--}}
                                                                {{--<div class="text-center">--}}
                                                                    {{--<span class="glyphicon glyphicon-warning-sign text-danger"></span>--}}
                                                                    {{--<span class="glyphicon glyphicon-warning-sign text-danger"></span><p--}}
                                                                            {{--class="text-danger"> Pallets number for transfer 1 does NOT MATCH Pallets number for transfer 2 </p>--}}
                                                                {{--</div>--}}
                                                            {{--@endif--}}
                                                            {{--@if(($type=='Deposit-Withdrawal' || $type=='Withdrawal-Deposit' )&&((Session::has('palletsNumber')&&(request()->session()->get('palletsNumber')<>$loading->anz))||(Session::has('palletsNumber2')&&(request()->session()->get('palletsNumber2')<>$loading->anz))))--}}
                                                                {{--<div class="text-center">--}}
                                                                    {{--<span class="glyphicon glyphicon-warning-sign text-danger"></span>--}}
                                                                    {{--<span class="glyphicon glyphicon-warning-sign text-danger"></span>--}}
                                                                    {{--<p class="text-danger">Pallets number does NOT MATCH the number expected in the loading order ({{$loading->anz}}--}}
                                                                        {{--)</p>--}}
                                                                {{--</div>--}}
                                                            {{--@endif--}}
                                                            {{--@if(Session::has('sumTransfersDepositOnly') && Session::has('sumTransfersWithdrawalOnly') && request()->session()->get('sumTransfersDepositOnly')<>request()->session()->get('sumTransfersWithdrawalOnly') )--}}
                                                                {{--<div class="text-center">--}}
                                                                    {{--<span class="glyphicon glyphicon-warning-sign text-danger"></span>--}}
                                                                    {{--<span class="glyphicon glyphicon-warning-sign text-danger"></span>--}}
                                                                    {{--<p class="text-danger"> Sum of deposit only transfers does NOT MATCH the sum of withdrawal only transfers </p>--}}
                                                                {{--</div>--}}
                                                            {{--@endif--}}
                                                    </div>
                                                    <div class="modal-footer">
                                                            {{--@if((($type=='Deposit-Withdrawal' || $type=='Withdrawal-Deposit'|| $type=='Purchase-Sale')&&(Session::has('creditAccount2')&&Session::has('debitAccount2')&&Session::has('palletsNumber2')&&(request()->session()->get('palletsNumber2')<>request()->session()->get('palletsNumber'))))||(($type=='Deposit-Withdrawal' || $type=='Withdrawal-Deposit')&&((Session::has('palletsNumber')&&(request()->session()->get('palletsNumber')<>$loading->anz))||(Session::has('palletsNumber2')&&(request()->session()->get('palletsNumber2')<>$loading->anz))))||(Session::has('sumTransfersDepositOnly') && Session::has('sumTransfersWithdrawalOnly') && request()->session()->get('sumTransfersDepositOnly')<>request()->session()->get('sumTransfersWithdrawalOnly')))--}}
                                                                {{--<button type="submit" class="btn btn-danger btn-modal" value="yes" name="okSubmitAddModal">--}}
                                                                    {{--Confirm--}}
                                                                {{--</button>--}}
                                                            {{--@else--}}
                                                        <button type="submit" class="btn btn-default btn-form btn-modal" value="okSubmitAddModal" name="okSubmitAddModal" id="okSubmitAddModal" onclick="formSubmitBlock(this);">
                                                            Confirm
                                                        </button>
                                                            {{--@endif--}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <br>

                                    <!---TABLE ALL TRANSFERS-->
                                    <div class="row">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-bordered">
                                                <thead>
                                                <tr>
                                                    @foreach($listAccountsTransfers as  $accountTransfer)
                                                        @php($partsAccount=explode('-', $accountTransfer))
                                                        @php($typeAccount=$partsAccount[count($partsAccount)-2])
                                                        @php($idAccount=$partsAccount[count($partsAccount)-1])
                                                        @if($typeAccount=='account')
                                                            @php($nameAccount=\App\Palletsaccount::where('id', $idAccount)->first()->name)
                                                            <th colspan="3" class="text-center">
                                                                <a class="link"
                                                                   href="{{route('showDetailsPalletsaccount',$idAccount)}}">{{$nameAccount}}</a>
                                                            </th>
                                                            @elseif($typeAccount=='truck')
                                                            @php($nameAccount=\App\Truck::where('id', $idAccount)->first()->name)
                                                            @php($licensePlateAccount=\App\Truck::where('id', $idAccount)->first()->licensePlate)
                                                            <th colspan="3">
                                                            <a class="link"
                                                               href="{{route('showDetailsTruck',$idAccount)}}">{{$nameAccount}}
                                                                - {{$licensePlateAccount}}</a>
                                                            </th>
                                                        @endif
                                                    @endforeach
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    @foreach($listAccountsTransfers as  $accountTransfer)
                                                    <td class="text-center">Confirmed</td>
                                                    <td class="text-center">Planned</td>
                                                    <td class="text-center">Rest</td>
                                                    @endforeach
                                                </tr>
                                                <tr>
                                                    @foreach($listAccountsTransfers as  $accountTransfer)
                                                        @php($partsAccount=explode('-', $accountTransfer))
                                                        @php($typeAccount=$partsAccount[count($partsAccount)-2])
                                                        @php($idAccount=$partsAccount[count($partsAccount)-1])
                                                        @if($typeAccount=='account')
                                                            @php($confirmed=\App\Palletsaccount::where('id', $idAccount)->first()->realNumberPallets)
                                                            @php($planned=\App\Palletsaccount::where('id', $idAccount)->first()->theoricalNumberPallets)
                                                        @elseif($typeAccount=='truck')
                                                            @php($confirmed=\App\Truck::where('id', $idAccount)->first()->realNumberPallets)
                                                            @php($planned=\App\Truck::where('id', $idAccount)->first()->theoricalNumberPallets)
                                                        @endif
                                                        <td class="text-center"><span @if($confirmed <0) class="text-inf0" @elseif($confirmed >0) class="text-sup0" @else class="text-egal0" @endif >{{$confirmed}}</span></td>
                                                        <td class="text-center"><span @if($planned <0) class="text-inf0" @elseif($planned >0) class="text-sup0" @else class="text-egal0" @endif>{{$planned}}</span></td>
                                                        <td class="text-center"><span @if($planned - $confirmed <0) class="text-inf0" @elseif($planned - $confirmed >0) class="text-sup0" @else class="text-egal0" @endif>{{$planned - $confirmed}}</span></td>
                                                    @endforeach
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="table-responsive table-transfers">
                                            <table class="table table-hover table-bordered">
                                                <thead>
                                                <tr>
                                                    <th class="text-center col1ID"> ID<br><a
                                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                                href="{{url('/detailsLoading/'.$loading->atrnr.'?sortby=id&order=asc')}}"></a><a
                                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                                href="{{url('/detailsLoading/'.$loading->atrnr.'?sortby=id&order=desc')}}"></a>
                                                    </th>
                                                    <th class="text-center col3"> Type<br><a
                                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                                href="{{url('/detailsLoading/'.$loading->atrnr.'?sortby=type&order=asc')}}"></a><a
                                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                                href="{{url('/detailsLoading/'.$loading->atrnr.'?sortby=type&order=desc')}}"></a>
                                                    </th>
                                                    <th class="text-center col4"> Debit account<br><a
                                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                                href="{{url('/detailsLoading/'.$loading->atrnr.'?sortby=debitAccount&order=asc')}}"></a><a
                                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                                href="{{url('/detailsLoading/'.$loading->atrnr.'?sortby=debitAccount&order=desc')}}"></a>
                                                    </th>
                                                    <th class="text-center col4"> Credit account<br><a
                                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                                href="{{url('/detailsLoading/'.$loading->atrnr.'?sortby=creditAccount&order=asc')}}"></a><a
                                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                                href="{{url('/detailsLoading/'.$loading->atrnr.'?sortby=creditAccount&order=desc')}}"></a>
                                                    </th>
                                                    <th class="text-center col2"> Nbr<br><a
                                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                                href="{{url('/detailsLoading/'.$loading->atrnr.'?sortby=palletsNumber&order=asc')}}"></a><a
                                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                                href="{{url('/detailsLoading/'.$loading->atrnr.'?sortby=palletsNumber&order=desc')}}"></a>
                                                    </th>
                                                    <th class="text-center col5"> State<br><a
                                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                                href="{{url('/detailsLoading/'.$loading->atrnr.'?sortby=state&order=asc')}}"></a><a
                                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                                href="{{url('/detailsLoading/'.$loading->atrnr.'?sortby=state&order=desc')}}"></a>
                                                    </th>
                                                    <th class="text-center colDanger"></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($listPalletstransfers as $transfer)
                                                    <tr @if($transfer->state=="Untreated") class="untreated"  @elseif($transfer->state=="Waiting documents") class="waitingdocuments" @elseif($transfer->state=="Complete") class="complete" @else class="completevalidated" @endif>
                                                        <td class="text-center col1ID">
                                                            <a class="link"
                                                               href="{{route('showDetailsPalletstransfer',$transfer->id)}}">{{$transfer->id}}</a>
                                                        </td>
                                                        <td class="text-center col3">{{$transfer->type}}</td>
                                                        <td class="text-center">
                                                                @php($partsDebitAccount=explode('-', $transfer->debitAccount))
                                                                @php($typeDebitAccount=$partsDebitAccount[count($partsDebitAccount)-2])
                                                                @php($idDebitAccount=$partsDebitAccount[count($partsDebitAccount)-1])
                                                                @if($typeDebitAccount=='account')
                                                                    @php($nameDebitAccount=\App\Palletsaccount::where('id', $idDebitAccount)->first()->name)
                                                                    <a class="link"
                                                                       href="{{route('showDetailsPalletsaccount',$idDebitAccount)}}">{{$nameDebitAccount}}</a>
                                                                @elseif($typeDebitAccount=='truck')
                                                                    @php($nameDebitAccount=\App\Truck::where('id', $idDebitAccount)->first()->name)
                                                                    @php($licensePlateDebitAccount=\App\Truck::where('id', $idDebitAccount)->first()->licensePlate)
                                                                    <a class="link"
                                                                       href="{{route('showDetailsTruck',$idDebitAccount)}}">{{$nameDebitAccount}}
                                                                        - {{$licensePlateDebitAccount}}</a>
                                                                @endif
                                                        </td>
                                                        <td class="text-center">
                                                                @php($partsCreditAccount=explode('-', $transfer->creditAccount))
                                                                @php($typeCreditAccount=$partsCreditAccount[count($partsCreditAccount)-2])
                                                                @php($idCreditAccount=$partsCreditAccount[count($partsCreditAccount)-1])
                                                                @if($typeCreditAccount=='account')
                                                                    @php($nameCreditAccount=\App\Palletsaccount::where('id', $idCreditAccount)->first()->name)
                                                                    <a class="link"
                                                                       href="{{route('showDetailsPalletsaccount',$idCreditAccount)}}">{{$nameCreditAccount}}</a>
                                                                @elseif($typeCreditAccount=='truck')
                                                                    @php($nameCreditAccount=\App\Truck::where('id', $idCreditAccount)->first()->name)
                                                                    @php($licensePlateCreditAccount=\App\Truck::where('id', $idCreditAccount)->first()->licensePlate)
                                                                    <a class="link"
                                                                       href="{{route('showDetailsTruck',$idCreditAccount)}}">{{$nameCreditAccount}}
                                                                        - {{$licensePlateCreditAccount}}</a>
                                                                @endif
                                                        </td>
                                                        <td class="text-center col2">{{$transfer->palletsNumber}}</td>
                                                        <td class="text-center col5">{{$transfer->state}}</td>
                                                        @php($errorsTransfer= \App\Http\Controllers\PalletstransfersController::actualErrors($transfer))
                                                        <td class="text-left colDanger">
                                                            @if(!empty($errorsTransfer))
                                                                @foreach($errorsTransfer as $errorTrans)
                                                                    <span class="glyphicon glyphicon-warning-sign text-danger" data-toggle="tooltip" data-placement="top" title="{{$errorTrans->name}}"></span>
                                                                @endforeach
                                                            @else
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <br>

                                    <!--PANEL FOR EACH TRANSFER-->
                                    <div class="row">
                                        <!--msg error-->
                                        <div class="form-group">
                                            @if(Session::has('errorAccountsPanel'))
                                                <p class="alert alert-danger text-alert text-center">{{ Session::get('errorAccountsPanel') }}</p>
                                            @endif
                                        </div>
                                        <!-----------------NORMAL TRANSFERS---------->
                                        <div class="form-group text-center">
                                            <label for="normal" class="control-label text-center">NORMAL</label>
                                        </div>
                                        @foreach($listPalletstransfersNormal as $transferNormal)
                                            @php($errorsTransfer=\App\Http\Controllers\PalletstransfersController::actualErrors($transferNormal))
                                            <div @if($transferNormal->state=="Untreated") class="panel panelUntreated" @elseif ($transferNormal->state=="Waiting documents") class="panel panelWaitingdocuments" @elseif ($transferNormal->state=="Complete") class="panel panelComplete"  @elseif ($transferNormal->state=="Complete Validated") class="panel panel-general" @endif>
                                                <div class="panel-heading">
                                                    <div class="col-lg-2 text-left headerSubpanelDetailsLoading">
                                                        <a data-toggle="collapse" href="#PanSubcollapse{{$transferNormal->id}}">Transfer {{$transferNormal->id}} </a>
                                                        <!--display errors signs-->
                                                        @if(!empty($errorsTransfer))
                                                            @foreach($errorsTransfer as $error)
                                                                <span class="glyphicon glyphicon-warning-sign text-danger"> </span>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                    <div class="@if(empty($errorsTransfer)) col-lg-8 @else col-lg-7 @endif text-left headerSubpanelDetailsLoading">
                                                        @php($partsDebitAccount=explode('-',$transferNormal->debitAccount))
                                                        @php($idDebAcc=$partsDebitAccount[count($partsDebitAccount)-1])
                                                        @php($typeDebAcc=$partsDebitAccount[count($partsDebitAccount)-2])
                                                        @if(count(array_diff ($partsDebitAccount, [$idDebAcc, $typeDebAcc]))==1)
                                                            @php($debitAccountValidate=array_diff ($partsDebitAccount, [$idDebAcc, $typeDebAcc])[0])
                                                        @else
                                                            @php($debitAccountValidate=implode( ' - ', array_diff ($partsDebitAccount, [$idDebAcc, $typeDebAcc])))
                                                        @endif
                                                        @php($partsCreditAccount=explode('-',$transferNormal->creditAccount))
                                                        @php($idCredAcc=$partsCreditAccount[count($partsCreditAccount)-1])
                                                        @php($typeCredAcc=$partsCreditAccount[count($partsCreditAccount)-2])
                                                        @if(count(array_diff ($partsCreditAccount, [$idCredAcc, $typeCredAcc]))==1)
                                                            @php($creditAccountValidate=array_diff ($partsCreditAccount, [$idCredAcc, $typeCredAcc])[0])
                                                        @else
                                                            @php($creditAccountValidate=implode( ' - ', array_diff ($partsCreditAccount, [$idCredAcc, $typeCredAcc])))
                                                        @endif
                                                        <p> {{$debitAccountValidate}} <span class="glyphicon glyphicon-arrow-right"></span> {{$creditAccountValidate}} - {{$transferNormal->palletsNumber}}</p>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <a class="link" href="{{route('showDetailsPalletstransfer',$transferNormal->id)}}"><span class="glyphicon glyphicon-eye-open"></span></a>
                                                    </div>
                                                        {{--<div class="col-lg-1">--}}
                                                            {{--<a class="link"  data-toggle="modal"--}}
                                                               {{--data-target="#sendEmailTransfer_modal"><span class="glyphicon glyphicon-envelope"></span></a>--}}
                                                        {{--</div>--}}
                                                    @if(!empty($errorsTransfer))
                                                        <!--show addCorrectingTransfer -->
                                                            <div class="col-lg-1">
                                                                <button type="submit" class="btn btn-primary btn-form  glyphicon glyphicon-wrench" value="showAddCorrectingTransfer-{{$transferNormal->id}}" name="showAddCorrectingTransfer" id="showAddCorrectingTransfer" data-toggle="modal" data-target="#addForm" onclick="formSubmitBlock(this);">
                                                                </button>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <button type="submit" class="btn btn-primary btn-form glyphicon glyphicon-remove" value="delete-{{$transferNormal->id}}" name="delete" id="delete" onclick="formSubmitBlock(this);"></button>
                                                        </div>
                                                    </div>
                                                    <div id="PanSubcollapse{{$transferNormal->id}}" class="panel-collapse collapse panel-body">
                                                        <div class="form-group">
                                                            <!--type-->
                                                            <div class="col-lg-1">
                                                                <label for="type{{$transferNormal->id}}" class="control-label" >*Type :</label>
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <input type="text" name="type{{$transferNormal->id}}" class="form-control" value="{{$transferNormal->type}}" id="type{{$transferNormal->id}}" readonly/>
                                                            </div>
                                                            <!--details-->
                                                            <div class="col-lg-4">
                                                                @if(isset($transferNormal->details)&&(isset($transferNormal->validate) && $transferNormal->validate==1))
                                                                    <textarea class="form-control" rows="1" name="details{{$transferNormal->id}}" name="details{{$transferNormal->id}}" placeholder="Details" readonly>{{$transferNormal->details}}</textarea>
                                                                @elseif(isset($transferNormal->details))
                                                                    <textarea class="form-control" rows="1" name="details{{$transferNormal->id}}" name="details{{$transferNormal->id}}" placeholder="Details">{{$transferNormal->details}}</textarea>
                                                                @elseif(Illuminate\Support\Facades\Input::old('details'.$transferNormal->id) && isset($transferNormal->validate) && $transferNormal->validate==1)
                                                                    <textarea class="form-control" rows="1" name="details{{$transferNormal->id}}" name="details{{$transferNormal->id}}" placeholder="Details" readonly>{{old('details'.$transferNormal->id)}}</textarea>
                                                                @elseif(Illuminate\Support\Facades\Input::old('details'.$transferNormal->id))
                                                                    <textarea class="form-control" rows="1" id="details{{$transferNormal->id}}" name="details{{$transferNormal->id}}" placeholder="Details">{{old('details'.$transferNormal->id)}}</textarea>
                                                                @else
                                                                    <textarea class="form-control" rows="1" id="details{{$transferNormal->id}}" name="details{{$transferNormal->id}}" placeholder="Details"></textarea>
                                                                @endif
                                                            </div>
                                                            <!--date-->
                                                            <div class="col-lg-2">
                                                                <input id="date{{$transferNormal->id}}" type="date" class="form-control" name="date{{$transferNormal->id}}" value="{{ $transferNormal->date }}" placeholder="Date" autofocus readonly/>
                                                            </div>
                                                            <!--add account-->
                                                            <div class="col-lg-2 col-lg-offset-1">
                                                                <a href="{{route('showAddPalletsaccount', ['originalPage' => 'detailsLoading-'.$loading->anz])}}" class="link"><span class="glyphicon glyphicon-plus-sign"></span> Add account</a>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <!--number of pallets-->
                                                            <div class="col-lg-1">
                                                                <label for="palletsNumber{{$transferNormal->id}}" class="control-label">*Pal. :</label>
                                                            </div>
                                                            <div class="col-lg-1">
                                                                <input id="palletsNumber{{$transferNormal->id}}" type="number" class="form-control" name="palletsNumber{{$transferNormal->id}}" value="{{$transferNormal->palletsNumber}}"  placeholder="Nbr" min="0" autofocus readonly/>
                                                            </div>

                                                            <!--debit account-->
                                                            <div class="col-lg-2" id="debitAccount1{{$transferNormal->id}}">
                                                                <label for="debitAccount{{$transferNormal->id}}" class="control-label">*Debit account :</label>
                                                            </div>
                                                            <div class="col-lg-3" id="debitAccount2{{$transferNormal->id}}" >
                                                                <input type="text" name="debitAccount{{$transferNormal->id}}" id="debitAccount{{$transferNormal->id}}" class="form-control" value="{{$debitAccountValidate}}" readonly/>
                                                            </div>

                                                            <!--credit account-->
                                                            <div class="col-lg-2" id="creditAccount1{{$transferNormal->id}}">
                                                                <label for="creditAccount{{$transferNormal->id}}" class="control-label">*Credit account :</label>
                                                            </div>
                                                            <div class="col-lg-3" id="creditAccount2{{$transferNormal->id}}" >
                                                                <input type="text" name="creditAccount{{$transferNormal->id}}"  id="creditAccount{{$transferNormal->id}}" class="form-control" value="{{$creditAccountValidate}}" readonly/>
                                                            </div>
                                                        </div>
                                                        <!--documents proof upload-->
                                                        <div class="form-group">
                                                            <div class="col-lg-2">
                                                                <label for="documentsTransfer{{$transferNormal->id}}">*Proof docs ?</label>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <input type="file" name="documentsTransfer{{$transferNormal->id}}[]" multiple id="documentsTransfer{{$transferNormal->id}}"/>
                                                            </div>
                                                            <!--button upload-->
                                                            <div class="col-lg-2">
                                                                <button type="submit" class="btn btn-primary btn-block btn-form" value="upload-{{$transferNormal->id}}" name="upload" id="upload" onclick="formSubmitBlock(this);">
                                                                    Upload
                                                                </button>
                                                            </div>
                                                        </div>

                                                    @php($filesNames= \App\Http\Controllers\DetailsLoadingController::actualDocuments($transferNormal->id))
                                                    <!-- documents -->
                                                        <div class="form-group">
                                                            <div class="col-lg-10 col-lg-offset-1 text-left">
                                                                @if(!empty($filesNames))
                                                                    <ul>
                                                                        @php($list=[])
                                                                        @foreach($filesNames as $nameF)
                                                                            @if(!in_array($nameF, $list))
                                                                                <div>
                                                                                    <button type="submit" name="deleteDocument" id="deleteDocument" class="btn-add glyphicon glyphicon-remove" value="deleteDocument-{{$nameF}}-{{$transferNormal->id}}" onclick="formSubmitBlock(this);"></button>
                                                                                    <a href="../../storage/app/proofsPallets/documentsTransfer/{{$transferNormal->id}}/{{$transfer->type}}/{{$nameF}}" class="link">{{$nameF}}</a>
                                                                                </div>
                                                                                @php(array_push($list,$nameF))
                                                                            @endif
                                                                        @endforeach
                                                                    </ul>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <!--validation-->
                                                        <div class="form-group">
                                                            @if(!empty($filesNames)&&isset($transferNormal->palletsNumber)&&isset($transferNormal->creditAccount)&&isset($transferNormal->debitAccount))
                                                                <div class="col-lg-2">
                                                                    <label for="validate{{$transferNormal->id}}" class="control-label">Validated ? </label>
                                                                </div>
                                                                <div class="col-lg-2 text-left">
                                                                    @if(isset($transferNormal->validate) && $transferNormal->validate==1)
                                                                        <label class="radio-inline"><input type="radio" name="validate{{$transferNormal->id}}" value="true" checked id="validateYes"/>Yes</label>
                                                                        <label class="radio-inline"><input type="radio" name="validate{{$transferNormal->id}}" value="false" id="validateNo"/>No</label>
                                                                    @elseif(isset($transferNormal->validate) && $transferNormal->validate==0)
                                                                        <label class="radio-inline"><input type="radio" name="validate{{$transferNormal->id}}" value="true" id="validateYes">Yes</label>
                                                                        <label class="radio-inline"><input type="radio" name="validate{{$transferNormal->id}}" value="false" checked id="validateNo"/>No</label>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        <!--submit-->
                                                            <div @if(!empty($filesNames)&&isset($transferNormal->palletsNumber)&&isset($transferNormal->creditAccount)&&isset($transferNormal->debitAccount)) class="col-lg-2 col-lg-offset-2" @else class="col-lg-2 col-lg-offset-6"  @endif>
                                                                <button type="submit" class="btn btn-primary btn-block btn-form" value="submitPallets-{{$transferNormal->id}}" name="submitPallets" id="submitPallets" data-toggle="modal" data-target="#submitPallets_modal" onclick="formSubmitBlock(this);">
                                                                    Update
                                                                </button>
                                                            </div>

                                                            {{--@if(!empty($errorsTransfer))--}}
                                                            {{--<!--show addCorrectingTransfer -->--}}
                                                                {{--<div class="col-lg-3 col-lg-offset-1">--}}
                                                                    {{--<button type="submit" class="btn btn-primary btn-block btn-form" value="{{$transferNormal->id}}" name="showAddCorrectingTransfer" data-toggle="modal" data-target="#addForm">--}}
                                                                        {{--Add correcting transfer--}}
                                                                    {{--</button>--}}
                                                                {{--</div>--}}
                                                            {{--@endif--}}
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Modal update -->
                                                @if((isset($submitPalletsNormal)&& $submitPalletsNormal==$transferNormal->id))
                                                    <div class="modal show"
                                                         id="submitPallets_modal"
                                                         role="dialog">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header modalHeaderTransfer">
                                                                    <button value="closeSubmitPalletsModal-{{$transferNormal->id}}"
                                                                            class="close"
                                                                            type="submit"
                                                                            name="closeSubmitPalletsModal" id="closeSubmitPalletsModal" onclick="formSubmitBlock(this);">
                                                                        &times;
                                                                    </button>
                                                                    <h4 class="modal-title text-center ">
                                                                        INFORMATION
                                                                    </h4>
                                                                </div>
                                                                <div class="modal-body center modalBodyTransfer">
                                                                    <p class="text-center">
                                                                        Here, PLANNED pallets number</p>
                                                                    @if(count(array_diff (request()->session()->get('partsCreditAccount'), [request()->session()->get('typeCreditAccount'), request()->session()->get('idCreditAccount')]))==1)
                                                                        @php($actualCreditAccount=array_diff (request()->session()->get('partsCreditAccount'), [request()->session()->get('typeCreditAccount'), request()->session()->get('idCreditAccount')])[0])
                                                                    @else
                                                                        @php($actualCreditAccount=implode(' - ', array_diff (request()->session()->get('partsCreditAccount'), [request()->session()->get('typeCreditAccount'), request()->session()->get('idCreditAccount')])))
                                                                    @endif

                                                                    @if(count(array_diff (request()->session()->get('partsDebitAccount'), [request()->session()->get('typeDebitAccount'), request()->session()->get('idDebitAccount')]))==1)
                                                                        @php($actualDebitAccount=array_diff (request()->session()->get('partsCreditAccount'), [request()->session()->get('typeCreditAccount'), request()->session()->get('idCreditAccount')])[0])
                                                                    @else
                                                                        @php($actualDebitAccount=implode( ' - ', array_diff (request()->session()->get('partsDebitAccount'), [request()->session()->get('typeDebitAccount'), request()->session()->get('idDebitAccount')])))
                                                                    @endif
                                                                    <table class="table table-hover table-bordered">
                                                                        <thead>
                                                                        <tr>
                                                                            <th></th>
                                                                                <th class="text-center">
                                                                                    DEBIT
                                                                                </th>
                                                                                <th class="text-center">
                                                                                    CREDIT
                                                                                </th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <!-- name account -->
                                                                        <tr>
                                                                            <td></td>
                                                                                <td class="text-center">{{$actualDebitAccount}}</td>
                                                                                <td class="text-center">{{$actualCreditAccount}}</td>
                                                                        </tr>
                                                                        <!-- actual -->
                                                                        <tr>
                                                                            <td class="text-center">
                                                                                Actual
                                                                            </td>
                                                                                <td class="text-center">{{request()->session()->get('actualPalletsNumberDebitAccount')}}</td>
                                                                                <td class="text-center">{{request()->session()->get('actualPalletsNumberCreditAccount')}}</td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <!--display errors signs-->
                                                                    @foreach($errorsTransfer as $errorTrans)
                                                                        @if($errorTrans->name=='DW-WD_atLeastOne')
                                                                            <div class="text-center">
                                                                                <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                <p class="text-danger"> A with-dep transfer or dep-with transfer is missing</p>
                                                                            </div>
                                                                        @endif
                                                                            @if($errorTrans->name=='DW-WD_notNumberLoadingOrder')
                                                                                <div class="text-center">
                                                                                    <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                    <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                    <p class="text-danger"> The pallets numbers sum of with-dep transfers or dep-with transfers does NOT MATCH the number in the loading order ({{$loading->anz}})</p>
                                                                                </div>
                                                                            @endif
                                                                        @if($errorTrans->name=='Donly-Wonly_notSameNumber')
                                                                            <div class="text-center">
                                                                                <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                <span class="glyphicon glyphicon-warning-sign text-danger"></span><p
                                                                                        class="text-danger"> Sum of deposit only transfers does NOT MATCH the sum of withdrawal only transfers </p>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit"
                                                                            @if(!empty($errorsTransfer))
                                                                            class="btn btn-danger btn-modal"
                                                                            @else
                                                                            class="btn btn-default btn-form btn-modal"
                                                                            @endif
                                                                            value="okSubmitPalletsModal-{{$transferNormal->id}}"
                                                                            name="okSubmitPalletsModal"
                                                                            id="okSubmitPalletsModal"
                                                                            data-toggle="modal"
                                                                            data-target="#submitPalletsValidate_modal" onclick="formSubmitBlock(this);">
                                                                        Ok
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                            <!-- Modal update validate -->
                                                @if((isset($okSubmitPalletsModalNormal) && $okSubmitPalletsModalNormal==$transferNormal->id && $transferNormal->state=='Complete Validated'))
                                                    <div class="modal show"
                                                         id="submitPalletsValidate_modal"
                                                         role="dialog">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header modalHeaderTransfer">
                                                                    <button value="closeSubmitPalletsModal-{{$transferNormal->id}}"
                                                                            class="close"
                                                                            type="submit"
                                                                            name="closeSubmitPalletsModal" id="closeSubmitPalletsModalb" onclick="formSubmitBlock(this);">
                                                                        &times;
                                                                    </button>
                                                                    <h4 class="modal-title text-center">
                                                                        INFORMATION
                                                                    </h4>
                                                                </div>
                                                                <div class="modal-body center modalBodyTransfer">
                                                                    <p class="text-center">
                                                                        Here, CONFIRMED pallets number</p>
                                                                    @if(count(array_diff (request()->session()->get('partsCreditAccount'), [request()->session()->get('typeCreditAccount'), request()->session()->get('idCreditAccount')]))==1)
                                                                        @php($actualCreditAccount=array_diff (request()->session()->get('partsCreditAccount'), [request()->session()->get('typeCreditAccount'), request()->session()->get('idCreditAccount')])[0])
                                                                    @else
                                                                        @php($actualCreditAccount=implode(' - ', array_diff (request()->session()->get('partsCreditAccount'), [request()->session()->get('typeCreditAccount'), request()->session()->get('idCreditAccount')])))
                                                                    @endif

                                                                    @if(count(array_diff (request()->session()->get('partsDebitAccount'), [request()->session()->get('typeDebitAccount'), request()->session()->get('idDebitAccount')]))==1)
                                                                        @php($actualDebitAccount=array_diff (request()->session()->get('partsCreditAccount'), [request()->session()->get('typeCreditAccount'), request()->session()->get('idCreditAccount')])[0])
                                                                    @else
                                                                        @php($actualDebitAccount=implode( ' - ', array_diff (request()->session()->get('partsDebitAccount'), [request()->session()->get('typeDebitAccount'), request()->session()->get('idDebitAccount')])))
                                                                    @endif
                                                                    <table class="table table-hover table-bordered">
                                                                        <thead>
                                                                        <tr>
                                                                            <th></th>
                                                                            <th class="text-center">
                                                                                DEBIT
                                                                            </th>
                                                                            <th class="text-center">
                                                                                CREDIT
                                                                            </th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <!-- name account -->
                                                                        <tr>
                                                                            <td></td>
                                                                            <td class="text-center">{{$actualDebitAccount}}</td>
                                                                            <td class="text-center">{{$actualCreditAccount}}</td>
                                                                        </tr>
                                                                        <!-- actual -->
                                                                        <tr>
                                                                            <td class="text-center">
                                                                                Actual
                                                                            </td>
                                                                            <td class="text-center">{{request()->session()->get('actualRealPalletsNumberDebitAccount')}}</td>
                                                                            <td class="text-center">{{request()->session()->get('actualRealPalletsNumberCreditAccount')}}</td>
                                                                        </tr>
                                                                        @if(Session::has('notUpdateRealPalletsNumber'))
                                                                            <tr><td class="text-center">
                                                                                    Transfer
                                                                                </td>
                                                                                <td class="text-center">
                                                                                   no update</td>
                                                                                <td class="text-center">
                                                                                   no update</td></tr>
                                                                        @else
                                                                        <tr>
                                                                            <td class="text-center">
                                                                                Transfer
                                                                            </td>
                                                                                <td class="text-center">
                                                                                    - {{request()->session()->get('palletsNumber')}}</td>
                                                                                <td class="text-center">
                                                                                    + {{request()->session()->get('palletsNumber')}}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="text-center">
                                                                                Total
                                                                            </td>
                                                                                <td class="text-center">
                                                                                    = {{request()->session()->get('actualRealPalletsNumberDebitAccount') -request()->session()->get('palletsNumber')}}</td>
                                                                                <td class="text-center">
                                                                                    = {{request()->session()->get('actualRealPalletsNumberCreditAccount')+request()->session()->get('palletsNumber')}}</td>
                                                                        </tr>
                                                                        @endif
                                                                        </tbody>
                                                                    </table>
                                                                    @foreach($errorsTransfer as $errorTrans)
                                                                        @if($errorTrans->name=='DW-WD_atLeastOne')
                                                                            <div class="text-center">
                                                                                <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                <p class="text-danger"> A with-dep transfer or dep-with transfer is missing</p>
                                                                            </div>
                                                                        @endif
                                                                        @if($errorTrans->name=='DW-WD_notNumberLoadingOrder')
                                                                            <div class="text-center">
                                                                                <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                <p class="text-danger"> The pallets numbers sum of with-dep transfers or dep-with transfers does NOT MATCH the number in the loading order ({{$loading->anz}})</p>
                                                                            </div>
                                                                        @endif
                                                                        @if($errorTrans->name=='Donly-Wonly_notSameNumber')
                                                                            <div class="text-center">
                                                                                <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                <span class="glyphicon glyphicon-warning-sign text-danger"></span><p
                                                                                        class="text-danger"> Sum of deposit only transfers does NOT MATCH the sum of withdrawal only transfers </p>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit"
                                                                            @if(!empty($errorsTransfer))
                                                                            class="btn btn-danger btn-modal"
                                                                            @else
                                                                            class="btn btn-default btn-form btn-modal"
                                                                            @endif
                                                                            value="okSubmitPalletsValidateModal-{{$transferNormal->id}}"
                                                                            name="okSubmitPalletsValidateModal" id="okSubmitPalletsValidateModal" onclick="formSubmitBlock(this);">
                                                                        Confirm
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                            {{--<!-- Modal Send Email -->--}}
                                                {{--<div class="modal fade" id="sendEmailTransfer_modal"--}}
                                                {{--role="dialog">--}}
                                                {{--<div class="modal-dialog modal-md">--}}
                                                {{--<div class="modal-content">--}}
                                                {{--<div class="modal-header">--}}
                                                {{--<button type="button" class="close" data-dismiss="modal">&times;--}}
                                                {{--</button>--}}
                                                {{--<h4 class="modal-title text-center">Choose the right account to warn people of a problem in the transfer {{$transfer->id}}</h4>--}}
                                                {{--</div>--}}
                                                {{--<div class="modal-body">--}}
                                                    {{--<ul>--}}
                                                    {{--@if($typeCredAcc=='account')--}}
                                                            {{--@php($nameCredAcc=\App\Palletsaccount::where('id', $idCredAcc)->first()->name)--}}
                                                            {{--<li><a class="link"--}}
                                                                   {{--href="{{route('showDetailsPalletsaccount',$idCredAcc)}}">{{$nameCredAcc}}</a></li>--}}
                                                        {{--@elseif($typeCredAcc=='truck')--}}
                                                            {{--@php($idAcc1=\App\Palletsaccount::where('name',\App\Truck::where('id', $idCredAcc)->first()->name )->first()->id)--}}
                                                            {{--<li><a class="link"--}}
                                                                   {{--href="{{route('showDetailsPalletsAccount',$idAcc1)}}">{{$nameCredAcc}}--}}
                                                                {{--</a></li>--}}
                                                        {{--@endif--}}
                                                        {{--@if($typeDebAcc=='account')--}}
                                                            {{--@php($nameDebAcc=\App\Palletsaccount::where('id', $idDebAcc)->first()->name)--}}
                                                            {{--<li><a class="link"--}}
                                                                   {{--href="{{route('showDetailsPalletsaccount',$idDebAcc)}}">{{$nameDebAcc}}</a></li>--}}
                                                        {{--@elseif($typeDebAcc=='truck')--}}
                                                            {{--@php($idAcc2=\App\Palletsaccount::where('name',\App\Truck::where('id', $idDebAcc)->first()->name )->first()->id)--}}
                                                            {{--<li><a class="link"--}}
                                                               {{--href="{{route('showDetailsPalletsAccount',$idAcc2)}}">{{$nameDebAcc}}--}}
                                                                {{--</a></li>--}}
                                                        {{--@endif--}}
                                                    {{--</ul>--}}
                                                {{--</div>--}}
                                                {{--<div class="modal-footer">--}}
                                                {{--<button type="button"--}}
                                                {{--class="btn btn-default btn-modal"--}}
                                                {{--data-dismiss="modal">--}}
                                                {{--Close--}}
                                                {{--</button>--}}
                                                {{--</div>--}}
                                                {{--</div>--}}
                                                {{--</div>--}}
                                                {{--</div>--}}
                                            @endforeach

                                        <!-----------------CORRECTING TRANSFERS---------->
                                            <div class="form-group text-center">
                                                <label for="normal" class="control-label text-center">CORRECTING</label>
                                            </div>
                                            @foreach($listPalletstransfersCorrecting as $transferCorrecting)
                                                @php($errorsTransfer=\App\Http\Controllers\PalletstransfersController::actualErrors($transferCorrecting))
                                                <div @if($transferCorrecting->state=="Untreated") class="panel panelUntreated" @elseif ($transferCorrecting->state=="Waiting documents") class="panel panelWaitingdocuments" @elseif ($transferCorrecting->state=="Complete") class="panel panelComplete"  @elseif ($transferCorrecting->state=="Complete Validated") class="panel panel-general" @endif>
                                                    <div class="panel-heading">
                                                        <div class="col-lg-2 text-left headerSubpanelDetailsLoading">
                                                            <a data-toggle="collapse" href="#PanSubcollapse{{$transferCorrecting->id}}">Transfer {{$transferCorrecting->id}} </a>
                                                            <!--display errors signs-->
                                                            @if(!empty($errorsTransfer))
                                                                @foreach($errorsTransfer as $error)
                                                                    <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                        <div class="col-lg-8 text-left headerSubpanelDetailsLoading">
                                                            @php($partsDebitAccount=explode('-',$transferCorrecting->debitAccount))
                                                            @php($idDebAcc=$partsDebitAccount[count($partsDebitAccount)-1])
                                                            @php($typeDebAcc=$partsDebitAccount[count($partsDebitAccount)-2])
                                                            @if(count(array_diff ($partsDebitAccount, [$idDebAcc, $typeDebAcc]))==1)
                                                                @php($debitAccountValidate=array_diff ($partsDebitAccount, [$idDebAcc, $typeDebAcc])[0])
                                                            @else
                                                                @php($debitAccountValidate=implode( ' - ', array_diff ($partsDebitAccount, [$idDebAcc, $typeDebAcc])))
                                                            @endif
                                                            @php($partsCreditAccount=explode('-',$transferCorrecting->creditAccount))
                                                            @php($idCredAcc=$partsCreditAccount[count($partsCreditAccount)-1])
                                                            @php($typeCredAcc=$partsCreditAccount[count($partsCreditAccount)-2])
                                                            @if(count(array_diff ($partsCreditAccount, [$idCredAcc, $typeCredAcc]))==1)
                                                                @php($creditAccountValidate=array_diff ($partsCreditAccount, [$idCredAcc, $typeCredAcc])[0])
                                                            @else
                                                                @php($creditAccountValidate=implode( ' - ', array_diff ($partsCreditAccount, [$idCredAcc, $typeCredAcc])))
                                                            @endif
                                                            <p> {{$debitAccountValidate}} <span class="glyphicon glyphicon-arrow-right"></span> {{$creditAccountValidate}} - {{$transferCorrecting->palletsNumber}}</p>
                                                        </div>
                                                        <div class="col-lg-1">
                                                            <a class="link" href="{{route('showDetailsPalletstransfer',$transferCorrecting->id)}}"><span class="glyphicon glyphicon-eye-open"></span></a>
                                                        </div>
                                                        {{--<div class="col-lg-1">--}}
                                                            {{--<a class="link"  data-toggle="modal"--}}
                                                               {{--data-target="#sendEmailTransfer_modal"><span class="glyphicon glyphicon-envelope"></span></a>--}}
                                                        {{--</div>--}}
                                                        <div>
                                                            <button type="submit" class=" btn btn-primary btn-form glyphicon glyphicon-remove" value="delete-{{$transferCorrecting->id}}" name="delete" id="deleteb" onclick="formSubmitBlock(this);"></button>
                                                        </div>
                                                    </div>
                                                    <div id="PanSubcollapse{{$transferCorrecting->id}}"
                                                         class="panel-collapse collapse panel-body">
                                                        <div class="form-group">
                                                            <!--type-->
                                                            <div class="col-lg-1">
                                                                <label for="type{{$transferCorrecting->id}}" class="control-label">*Type :</label>
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <input type="text" name="type{{$transferCorrecting->id}}" class="form-control" value="{{$transferCorrecting->type}}" readonly/>
                                                            </div>
                                                            <!--details-->
                                                            <div class="col-lg-4">
                                                                @if(isset($transferCorrecting->details)&&(isset($transferCorrecting->validate) && $transferCorrecting->validate==1))
                                                                    <textarea class="form-control" rows="1" name="details{{$transferCorrecting->id}}" name="details{{$transferCorrecting->id}}" placeholder="Details" readonly>{{$transferCorrecting->details}}</textarea>
                                                                @elseif(isset($transferCorrecting->details))
                                                                    <textarea class="form-control" rows="1" name="details{{$transferCorrecting->id}}" name="details{{$transferCorrecting->id}}" placeholder="Details">{{$transferCorrecting->details}}</textarea>
                                                                @elseif(Illuminate\Support\Facades\Input::old('details'.$transferCorrecting->id) && isset($transferCorrecting->validate) && $transferCorrecting->validate==1)
                                                                    <textarea class="form-control" rows="1" name="details{{$transferCorrecting->id}}" name="details{{$transferCorrecting->id}}" placeholder="Details" readonly>{{old('details'.$transferCorrecting->id)}}</textarea>
                                                                @elseif(Illuminate\Support\Facades\Input::old('details'.$transferCorrecting->id))
                                                                    <textarea class="form-control" rows="1" id="details{{$transferCorrecting->id}}" name="details{{$transferCorrecting->id}}" placeholder="Details">{{old('details'.$transferCorrecting->id)}}</textarea>
                                                                @else
                                                                    <textarea class="form-control" rows="1" id="details{{$transferCorrecting->id}}" name="details{{$transferCorrecting->id}}" placeholder="Details"></textarea>
                                                                @endif
                                                            </div>
                                                            <!--date-->
                                                            <div class="col-lg-2">
                                                                <input id="date{{$transferCorrecting->id}}" type="date" class="form-control" name="date{{$transferCorrecting->id}}" value="{{ $transferCorrecting->date }}" placeholder="Date" autofocus readonly/>
                                                            </div>
                                                            <!--transfer normal associated-->
                                                            <div class="col-lg-2 text-right">
                                                                <label for="normalTransferAssociated{{$transferCorrecting->id}}" class="control-label">*Correction on :</label>
                                                            </div>
                                                            <div class="col-lg-1">
                                                                <input type="text" name="normalTransferAssociated{{$transferCorrecting->id}}" class="form-control" value="{{$transferCorrecting->normalTransferAssociated}}" readonly/>
                                                            </div>
                                                            <!--add account-->
                                                            <div class="col-lg-2 col-lg-offset-1">
                                                                <a href="{{route('showAddPalletsaccount', ['originalPage' => 'detailsLoading-'.$loading->anz])}}" class="link"><span class="glyphicon glyphicon-plus-sign"></span> Add account</a>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <!--number of pallets-->
                                                            <div class="col-lg-1">
                                                                <label for="palletsNumber{{$transferCorrecting->id}}" class="control-label">*Pal. :</label>
                                                            </div>
                                                            <div class="col-lg-1">
                                                                <input id="palletsNumber{{$transferCorrecting->id}}" type="number" class="form-control" name="palletsNumber{{$transferCorrecting->id}}" value="{{$transferCorrecting->palletsNumber}}"  placeholder="Nbr" min="0" autofocus readonly/>
                                                            </div>

                                                            <!--debit account-->
                                                            <div class="col-lg-2" id="debitAccount1{{$transferCorrecting->id}}">
                                                                <label for="debitAccount{{$transferCorrecting->id}}" class="control-label">*Debit account :</label>
                                                            </div>
                                                            <div class="col-lg-3" id="debitAccount2{{$transferCorrecting->id}}" >
                                                                <input type="text" name="debitAccount{{$transferCorrecting->id}}" class="form-control" value="{{$debitAccountValidate}}" readonly/>
                                                            </div>

                                                            <!--credit account-->
                                                            <div class="col-lg-2" id="creditAccount1{{$transferCorrecting->id}}">
                                                                <label for="creditAccount{{$transferCorrecting->id}}" class="control-label">*Credit account :</label>
                                                            </div>
                                                            <div class="col-lg-3" id="creditAccount2{{$transferCorrecting->id}}" >
                                                                <input type="text" name="creditAccount{{$transferCorrecting->id}}" class="form-control" value="{{$creditAccountValidate}}" readonly/>
                                                            </div>
                                                        </div>
                                                        <!--documents proof upload-->
                                                        <div class="form-group">
                                                            <div class="col-lg-2">
                                                                <label for="documentsTransfer{{$transferCorrecting->id}}">*Proof docs ?</label>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <input type="file" name="documentsTransfer{{$transferCorrecting->id}}[]" multiple id="documentsTransfer{{$transferCorrecting->id}}"/>
                                                            </div>
                                                            <!--button upload-->
                                                            <div class="col-lg-2">
                                                                <button type="submit" class="btn btn-primary btn-block btn-form" value="upload-{{$transferCorrecting->id}}" name="upload" id="upload" onclick="formSubmitBlock(this);">
                                                                    Upload
                                                                </button>
                                                            </div>
                                                        </div>

                                                    @php($filesNames= \App\Http\Controllers\DetailsLoadingController::actualDocuments($transferCorrecting->id))
                                                    <!-- documents -->
                                                        <div class="form-group">
                                                            <div class="col-lg-10 col-lg-offset-1 text-left">
                                                                @if(!empty($filesNames))
                                                                    <ul>
                                                                        @php($list=[])
                                                                        @foreach($filesNames as $nameF)
                                                                            @if(!in_array($nameF, $list))
                                                                                <div>
                                                                                    <button type="submit" name="deleteDocument" id="deleteDocumentb" class="btn-add glyphicon glyphicon-remove" value="deleteDocument-{{$nameF}}-{{$transferCorrecting->id}}" onclick="formSubmitBlock(this);"></button>
                                                                                    <a href="../../storage/app/proofsPallets/documentsTransfer/{{$transferCorrecting->id}}/{{$transfer->type}}/{{$nameF}}" class="link">{{$nameF}}</a>
                                                                                </div>
                                                                                @php(array_push($list,$nameF))
                                                                            @endif
                                                                        @endforeach
                                                                    </ul>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <!--validation-->
                                                        <div class="form-group">
                                                            @if(!empty($filesNames)&&isset($transferCorrecting->palletsNumber)&&isset($transferCorrecting->creditAccount)&&isset($transferCorrecting->debitAccount))
                                                                <div class="col-lg-2">
                                                                    <label for="validate{{$transferCorrecting->id}}" class="control-label">Validated ? </label>
                                                                </div>
                                                                <div class="col-lg-2 text-left">
                                                                    @if(isset($transferCorrecting->validate) && $transferCorrecting->validate==1)
                                                                        <label class="radio-inline"><input type="radio" name="validate{{$transferCorrecting->id}}" value="true" checked id="validateYes"/>Yes</label>
                                                                        <label class="radio-inline"><input type="radio" name="validate{{$transferCorrecting->id}}" value="false" id="validateNo"/>No</label>
                                                                    @elseif(isset($transferCorrecting->validate) && $transferCorrecting->validate==0)
                                                                        <label class="radio-inline"><input type="radio" name="validate{{$transferCorrecting->id}}" value="true" id="validateYes">Yes</label>
                                                                        <label class="radio-inline"><input type="radio" name="validate{{$transferCorrecting->id}}" value="false" checked id="validateNo"/>No</label>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        <!--submit-->
                                                            <div @if(!empty($filesNames)&&isset($transferCorrecting->palletsNumber)&&isset($transferCorrecting->creditAccount)&&isset($transferCorrecting->debitAccount)) class="col-lg-2 col-lg-offset-2" @else class="col-lg-2 col-lg-offset-6"  @endif>
                                                                <button type="submit" class="btn btn-primary btn-block btn-form" value="submitPallets-{{$transferCorrecting->id}}" name="submitPallets" id="submitPalletsb" data-toggle="modal" data-target="#submitPallets_modal" onclick="formSubmitBlock(this);">
                                                                    Update
                                                                </button>
                                                            </div>

                                                            {{--@if(!empty($errorsTransfer))--}}
                                                            {{--<!--show addCorrectingTransfer -->--}}
                                                                {{--<div class="col-lg-3">--}}
                                                                    {{--<button type="submit" class="btn btn-primary btn-block btn-form" value="showAddCorrectingTransfer-{{$transferCorrecting->id}}" name="showAddCorrectingTransfer" data-toggle="modal" data-target="#addForm">--}}
                                                                        {{--Add correcting transfer--}}
                                                                    {{--</button>--}}
                                                                {{--</div>--}}
                                                            {{--@endif--}}
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Modal update -->
                                                @if((isset($submitPalletsCorrecting)&& $submitPalletsCorrecting==$transferCorrecting->id))
                                                    <div class="modal show"
                                                         id="submitPallets_modal"
                                                         role="dialog">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header modalHeaderTransfer">
                                                                    <button value="closeSubmitPalletsModal-{{$transferCorrecting->id}}"
                                                                            class="close"
                                                                            type="submit"
                                                                            name="closeSubmitPalletsModal" id="closeSubmitPalletsModalbb" onclick="formSubmitBlock(this);">
                                                                        &times;
                                                                    </button>
                                                                    <h4 class="modal-title text-center ">
                                                                        INFORMATION
                                                                    </h4>
                                                                </div>
                                                                <div class="modal-body center modalBodyTransfer">
                                                                    <p class="text-center">
                                                                        Here, PLANNED pallets number</p>
                                                                    @if(count(array_diff (request()->session()->get('partsCreditAccount'), [request()->session()->get('typeCreditAccount'), request()->session()->get('idCreditAccount')]))==1)
                                                                        @php($actualCreditAccount=array_diff (request()->session()->get('partsCreditAccount'), [request()->session()->get('typeCreditAccount'), request()->session()->get('idCreditAccount')])[0])
                                                                    @else
                                                                        @php($actualCreditAccount=implode(' - ', array_diff (request()->session()->get('partsCreditAccount'), [request()->session()->get('typeCreditAccount'), request()->session()->get('idCreditAccount')])))
                                                                    @endif

                                                                    @if(count(array_diff (request()->session()->get('partsDebitAccount'), [request()->session()->get('typeDebitAccount'), request()->session()->get('idDebitAccount')]))==1)
                                                                        @php($actualDebitAccount=array_diff (request()->session()->get('partsCreditAccount'), [request()->session()->get('typeCreditAccount'), request()->session()->get('idCreditAccount')])[0])
                                                                    @else
                                                                        @php($actualDebitAccount=implode( ' - ', array_diff (request()->session()->get('partsDebitAccount'), [request()->session()->get('typeDebitAccount'), request()->session()->get('idDebitAccount')])))
                                                                    @endif
                                                                    <table class="table table-hover table-bordered">
                                                                        <thead>
                                                                        <tr>
                                                                            <th></th>
                                                                                <th class="text-center">
                                                                                    DEBIT
                                                                                </th>
                                                                                <th class="text-center">
                                                                                    CREDIT
                                                                                </th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <!-- name account -->
                                                                        <tr>
                                                                            <td></td>
                                                                                <td class="text-center">{{request()->session()->get('debitAccount')}}</td>
                                                                                <td class="text-center">{{request()->session()->get('creditAccount')}}</td>
                                                                        </tr>
                                                                        <!-- actual -->
                                                                        <tr>
                                                                            <td class="text-center">
                                                                                Actual
                                                                            </td>
                                                                                <td class="text-center">{{request()->session()->get('thPalletsNumberDebitAccount')}}</td>
                                                                                <td class="text-center">{{request()->session()->get('thPalletsNumberCreditAccount')}}</td>
                                                                        </tr>
                                                                        <!-- actual credit acc = credit acc && actual debit acc = debit acc-->
                                                                        @if($actualCreditAccount==request()->session()->get('creditAccount') && $actualDebitAccount==request()->session()->get('debitAccount'))
                                                                            <!--update-->
                                                                            <tr>
                                                                                <td class="text-center">
                                                                                    Update
                                                                                    number
                                                                                </td>
                                                                                    <td class="text-center">
                                                                                        - {{request()->session()->get('palletsNumber')-request()->session()->get('actualPalletsNumber')}}</td>
                                                                                    <td class="text-center">
                                                                                        +{{request()->session()->get('palletsNumber')-request()->session()->get('actualPalletsNumber')}}</td>
                                                                            </tr>
                                                                            <!--total-->
                                                                            <tr>
                                                                                <td class="text-center">
                                                                                    Total
                                                                                </td>
                                                                                    <td class="text-center">
                                                                                        = {{request()->session()->get('thPalletsNumberDebitAccount')  + request()->session()->get('actualPalletsNumber') -request()->session()->get('palletsNumber')}}</td>
                                                                                    <td class="text-center">
                                                                                        = {{request()->session()->get('thPalletsNumberCreditAccount')- request()->session()->get('actualPalletsNumber') +request()->session()->get('palletsNumber')}}</td>
                                                                            </tr>
                                                                            <!-- actual credit acc <> credit acc && actual debit acc <> debit acc -->
                                                                        @elseif($actualCreditAccount<>request()->session()->get('creditAccount') && $actualDebitAccount<>request()->session()->get('debitAccount'))
                                                                            <!-- new transfer -->
                                                                            <tr>
                                                                                <td class="text-center">
                                                                                    New
                                                                                    transfer
                                                                                </td>
                                                                                    <td class="text-center">
                                                                                        - {{request()->session()->get('palletsNumber')}}</td>
                                                                                    <td class="text-center">
                                                                                        + {{request()->session()->get('palletsNumber')}}</td>
                                                                            </tr>
                                                                            <!-- total -->
                                                                            <tr>
                                                                                <td class="text-center">
                                                                                    Total
                                                                                </td>
                                                                                    <td class="text-center">
                                                                                        = {{request()->session()->get('thPalletsNumberDebitAccount') -request()->session()->get('palletsNumber')}}</td>
                                                                                    <td class="text-center">
                                                                                        = {{request()->session()->get('thPalletsNumberCreditAccount')+request()->session()->get('palletsNumber')}}</td>
                                                                            </tr>
                                                                            <!--actual credit acc == credit acc && actual debit acc <> debit acc-->
                                                                        @elseif($actualCreditAccount==request()->session()->get('creditAccount') && $actualDebitAccount<>request()->session()->get('debitAccount'))
                                                                            <!-- update-->
                                                                            <tr>
                                                                                <td class="text-center">
                                                                                    Update
                                                                                    number
                                                                                </td>
                                                                                    <td class="text-center">
                                                                                        - {{request()->session()->get('palletsNumber')}}</td>
                                                                                    <td class="text-center">
                                                                                        +{{request()->session()->get('palletsNumber')-request()->session()->get('actualPalletsNumber')}}</td>
                                                                            </tr>
                                                                            <!-- total -->
                                                                            <tr>
                                                                                <td class="text-center">
                                                                                    Total
                                                                                </td>
                                                                                    <td class="text-center">
                                                                                        = {{request()->session()->get('thPalletsNumberDebitAccount') -request()->session()->get('palletsNumber')}}</td>
                                                                                    <td class="text-center">
                                                                                        = {{request()->session()->get('thPalletsNumberCreditAccount')- request()->session()->get('actualPalletsNumber') +request()->session()->get('palletsNumber')}}</td>
                                                                            </tr>
                                                                            <!-- actual credit acc <> credit acc && actual debit acc = debit acc-->
                                                                        @elseif($actualCreditAccount<>request()->session()->get('creditAccount') && $actualDebitAccount==request()->session()->get('debitAccount'))
                                                                            <!-- update -->
                                                                            <tr>
                                                                                <td class="text-center">
                                                                                    Update
                                                                                    number
                                                                                </td>
                                                                                    <td class="text-center">
                                                                                        - {{request()->session()->get('palletsNumber')-request()->session()->get('actualPalletsNumber')}}</td>
                                                                                    <td class="text-center">
                                                                                        +{{request()->session()->get('palletsNumber')}}</td>
                                                                            </tr>
                                                                            <!-- total-->
                                                                            <tr>
                                                                                <td class="text-center">
                                                                                    Total
                                                                                </td>
                                                                                    <td class="text-center">
                                                                                        = {{request()->session()->get('thPalletsNumberDebitAccount')+ request()->session()->get('actualPalletsNumber') -request()->session()->get('palletsNumber')}}</td>
                                                                                    <td class="text-center">
                                                                                        = {{request()->session()->get('thPalletsNumberCreditAccount') +request()->session()->get('palletsNumber')}}</td>
                                                                            </tr>
                                                                        @endif
                                                                        </tbody>
                                                                    </table>
                                                                    <!--display errors signs-->
                                                                    @foreach($errorsTransfer as $errorTrans)
                                                                        @if($errorTrans->name=='Correcting_notCompleteNormal')
                                                                            <div class="text-center">
                                                                                <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                <p class="text-danger"> Correcting transfers does NOT COMPLETE the normal transfer associated {{$transferCorrecting->normalTransferAssociated}}</p>
                                                                            </div>
                                                                        @endif
                                                                            @if($errorTrans->name=='SP-PS_notEnoughTransfers')
                                                                                <div class="text-center">
                                                                                    <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                    <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                    <p class="text-danger"> A sale-purchase or purchase-sale transfer is missing</p>
                                                                                </div>
                                                                            @endif
                                                                            @if($errorTrans->name=='Correcting_notEnoughTransfers')
                                                                                <div class="text-center">
                                                                                    <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                    <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                    <p class="text-danger"> A correcting transfer is missing</p>
                                                                                </div>
                                                                            @endif
                                                                    @endforeach
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit"
                                                                            @if(!empty($errorsTransfer)) class="btn btn-danger btn-modal"
                                                                            @else class="btn btn-default btn-form btn-modal"
                                                                            @endif
                                                                            value="okSubmitPalletsModal-{{$transferCorrecting->id}}"
                                                                            name="okSubmitPalletsModal"
                                                                            id="okSubmitPalletsModalb"
                                                                            data-toggle="modal"
                                                                            data-target="#submitPalletsValidate_modal" onclick="formSubmitBlock(this);">
                                                                        Confirm
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                            <!-- Modal update validate -->
                                                @if((isset($okSubmitPalletsModalCorrecting) && $okSubmitPalletsModalCorrecting==$transferCorrecting->id && $transferCorrecting->state=='Complete Validated'))
                                                    <div class="modal show"
                                                         id="submitPalletsValidate_modal"
                                                         role="dialog">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header modalHeaderTransfer">
                                                                    <button value="closeSubmitPalletsModal-{{$transferCorrecting->id}}"
                                                                            class="close"
                                                                            type="submit"
                                                                            name="closeSubmitPalletsModal" id="closeSubmitPalletsModalbbb" onclick="formSubmitBlock(this);">
                                                                        &times;
                                                                    </button>
                                                                    <h4 class="modal-title text-center">
                                                                        INFORMATION
                                                                    </h4>
                                                                </div>
                                                                <div class="modal-body center modalBodyTransfer">
                                                                    <p class="text-center">
                                                                        Here, CONFIRMED pallets number</p>
                                                                    @if(count(array_diff (request()->session()->get('partsCreditAccount'), [request()->session()->get('typeCreditAccount'), request()->session()->get('idCreditAccount')]))==1)
                                                                        @php($actualCreditAccount=array_diff (request()->session()->get('partsCreditAccount'), [request()->session()->get('typeCreditAccount'), request()->session()->get('idCreditAccount')])[0])
                                                                    @else
                                                                        @php($actualCreditAccount=implode(' - ', array_diff (request()->session()->get('partsCreditAccount'), [request()->session()->get('typeCreditAccount'), request()->session()->get('idCreditAccount')])))
                                                                    @endif

                                                                    @if(count(array_diff (request()->session()->get('partsDebitAccount'), [request()->session()->get('typeDebitAccount'), request()->session()->get('idDebitAccount')]))==1)
                                                                        @php($actualDebitAccount=array_diff (request()->session()->get('partsCreditAccount'), [request()->session()->get('typeCreditAccount'), request()->session()->get('idCreditAccount')])[0])
                                                                    @else
                                                                        @php($actualDebitAccount=implode( ' - ', array_diff (request()->session()->get('partsDebitAccount'), [request()->session()->get('typeDebitAccount'), request()->session()->get('idDebitAccount')])))
                                                                    @endif
                                                                    <table class="table table-hover table-bordered">
                                                                        <thead>
                                                                        <tr>
                                                                            <th></th>
                                                                                <th class="text-center">
                                                                                    DEBIT
                                                                                </th>
                                                                                <th class="text-center">
                                                                                    CREDIT
                                                                                </th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <tr>
                                                                            <td></td>
                                                                                <td class="text-center">{{$debitAccountValidate}}</td>
                                                                                <td class="text-center">{{$creditAccountValidate}}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="text-center">
                                                                                Actual
                                                                            </td>
                                                                                <td class="text-center">{{request()->session()->get('realPalletsNumberDebitAccount')}}</td>
                                                                                <td class="text-center">{{request()->session()->get('realPalletsNumberCreditAccount')}}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="text-center">
                                                                                New
                                                                                transfer
                                                                            </td>
                                                                                <td class="text-center">
                                                                                    - {{request()->session()->get('palletsNumber')}}</td>
                                                                                <td class="text-center">
                                                                                    + {{request()->session()->get('palletsNumber')}}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="text-center">
                                                                                Total
                                                                            </td>
                                                                                <td class="text-center">
                                                                                    = {{request()->session()->get('realPalletsNumberDebitAccount') -request()->session()->get('palletsNumber')}}</td>
                                                                                <td class="text-center">
                                                                                    = {{request()->session()->get('realPalletsNumberCreditAccount')+request()->session()->get('palletsNumber')}}</td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    @foreach($errorsTransfer as $errorTrans)
                                                                        @if($errorTrans->name=='Correcting_notCompleteNormal')
                                                                            <div class="text-center">
                                                                                <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                <p class="text-danger"> Correcting transfers does NOT COMPLETE the normal transfer associated {{$transferCorrecting->normalTransferAssociated}}</p>
                                                                            </div>
                                                                        @endif
                                                                        @if($errorTrans->name=='SP-PS_notEnoughTransfers')
                                                                            <div class="text-center">
                                                                                <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                <p class="text-danger"> A sale-purchase or purchase-sale transfer is missing</p>
                                                                            </div>
                                                                        @endif
                                                                        @if($errorTrans->name=='Correcting_notEnoughTransfers')
                                                                            <div class="text-center">
                                                                                <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                                                                <p class="text-danger"> A correcting transfer is missing</p>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit"
                                                                            @if(!empty($errorsTransfer))
                                                                            class="btn btn-danger btn-modal"
                                                                            @else
                                                                            class="btn btn-default btn-form btn-modal"
                                                                            @endif
                                                                            value="okSubmitPalletsValidateModal-{{$transferCorrecting->id}}"
                                                                            name="okSubmitPalletsValidateModal" id="okSubmitPalletsValidateModalb" onclick="formSubmitBlock(this);">
                                                                        Confirm
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                    </div>

                                </div>
                            </div>
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
    <script type="text/javascript" src="{{asset('js/addUpdateTransferLoading.js')}}">
    </script>
@endsection
