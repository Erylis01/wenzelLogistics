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
    class="nonActive"
@endsection
@section('classPalletsTransfers')
    class="nonActive"
@endsection
@section('classProfile')
    nonActive
@endsection

@section('content')
    <div class="container-fluid">
        @if(Auth::guest())
            <h4>You need to login to see the content</h4>
        @else
            <div class="col-lg-14 container-details">
                <div class="panel @if($loading->state=="In progress") panelInprogress @elseif ($loading->state=="Waiting documents")panelWaitingdocuments @elseif ($loading->state=="Complete") panelComplete @elseif ($loading->state=="Complete Validated")panel-general@else panelUntreated @endif">
                    <div class="panel-heading">
                        <div class="col-lg-5">
                            @if(substr_count($loading->atrnr, '-')==0)
                                <p>Details of the loading n°{{ $loading->atrnr }}</p>
                            @else
                                <p> Details of the loading n°  <a href="{{route('showDetailsLoading', $atrnr1)}}">{{$atrnr1}}</a>-{{$atrnr2}}</p>
                            @endif
                        </div>
                        <div class="col-lg-5 text-left">
                            @php($k=0)
                            @foreach($listPalletstransfers as $transfer)
                                @php($errorsTransfer= \App\Http\Controllers\PalletstransfersController::actualErrors($transfer))
                                @if(!empty($errorsTransfer)&&$k<10)
                                    <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                @elseif($k==10)
                                    <span class="text-danger">...</span>
                                @endif
                                @php($k=$k+1)
                            @endforeach
                        </div>
                        <a href="{{route('showAddSubloading', $loading->atrnr)}}" class=" btn btn-add"><span class="glyphicon glyphicon-plus-sign"></span> Add subloading</a>
                    </div>
                    <div class="panel-body panel-body-general">
                        <!-------SUBPANEL 1 : reading form suming up information from the table------->
                        <div class="panel subpanel">
                            <div class="panel-heading">
                                <a data-toggle="collapse" href="#Pan1collapse">Information</a>
                            </div>
                            <div id="Pan1collapse" class="panel-collapse @if (Session::has('openPanelInformation'))in @endif collapse">
                                @if (Session::has('messageUpdateLoading'))
                                    <div class="alert alert-success text-alert text-center">{{ Session::get('messageUpdateLoading') }}</div>
                                @endif
                                <div class="panel-body">
                                    <form class="form-horizontal" role="form" method="POST" action="{{route('submitUpdateUpload', $loading->atrnr)}}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                        <!-- subpanel general-->
                                        <div class="panel subpanel">
                                            <div class="panel-heading">
                                                <a data-toggle="collapse" href="#PanSub3collapse">General</a>
                                            </div>
                                            <div id="PanSub3collapse" class="panel-collapse @if(Session::has('openPanelInformation'))in @endif collapse">
                                                <div class="panel-body">
                                                    <div class="form-group">
                                                        <!--referenz-->
                                                        <div class="col-lg-6">
                                                            <div class="input-group details-loading">
                                                                <label for="referenz" class="input-group-addon">Referenz:</label>
                                                                <input type="text" name="referenz" class="form-control" value="{{ $loading->referenz }}" placeholder="referenz" required />
                                                            </div>
                                                        </div>
                                                    @if(substr_count($loading->atrnr, '-')==0)
                                                        <!--disp-->
                                                            <div class="col-lg-4">
                                                                <div class="input-group details-loading">
                                                                    <label for="disp" class="input-group-addon">Disp:</label>
                                                                    <input type="text" name="disp" class="form-control" value="{{ $disp }}" placeholder="disp" required />
                                                                    @if ($errors->has('disp'))
                                                                        <span class="help-block"><strong>{{ $errors->first('disp') }}</strong></span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <!--pt change pt-->
                                                            @if(Auth::user()->lastname=='Gundogan'&& Auth::user()->firstname=='Adrien' ||Auth::user()->username=='CamilleS' )
                                                                <div class="col-lg-2">
                                                                    <div class="input-group details-loading">
                                                                        <label for="pt" class="input-group-addon">PT:</label>
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
                                                                <label for="auftraggeber" class="input-group-addon">Auftraggeber:</label>
                                                                <input type="text" name="auftraggeber" class="form-control" value="{{ $loading->auftraggeber }}" placeholder="auftraggeber" required />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <!--subfrachter-->
                                                        <div class="col-lg-8">
                                                            <div class="input-group details-loading">
                                                                <label for="subfrachter" class="input-group-addon">Subfrachter:</label>
                                                                <input type="text" name="subfrachter" class="form-control" value="{{ $loading->subfrachter }}" placeholder="subfrachter" required />
                                                            </div>
                                                        </div>
                                                        <!--kennzeichen-->
                                                        <div class="col-lg-4">
                                                            <div class="input-group details-loading">
                                                                <label for="kennzeichen" class="input-group-addon">Kennzeichen:</label>
                                                                <input type="text" name="kennzeichen" class="form-control" value="{{ $loading->kennzeichen }}" placeholder="kennzeichen" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <!--anz-->
                                                        <div class="col-lg-2 details-loading">
                                                            <input type="number" name="anz" class="form-control" value="{{ $loading->anz }}" placeholder="anz." min="0" required data-toggle="tooltip" data-placement="top" title="Anzahl" />
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
                                                </div>
                                            </div>
                                        </div>
                                        <!-- subpanel loading-->
                                        <div class="panel subpanel col-lg-6">
                                            <div class="panel-heading">
                                                <a class="col-lg-3 text-left" data-toggle="collapse" href="#PanSub1collapse">Loading</a>
                                                <div class="col-lg-5 col-lg-offset-1 text-right details-loading">
                                                    <input type="date" name="ladedatum" class="form-control  text-center" value="{{ $loading->ladedatum }}" placeholder="ladedatum" required />
                                                </div>
                                            </div>
                                            <div id="PanSub1collapse" class="panel-collapse @if (Session::has('openPanelInformation')) in @endif collapse">
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
                                                                <label for="zusladestellen" class="input-group-addon">Zus. Ladestellen:</label>
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
                                                <a class="col-lg-3 text-left" data-toggle="collapse" href="#PanSub2collapse">Offloading</a>
                                                <div class="col-lg-5 col-lg-offset-1 text-right details-loading">
                                                    <input type="date" name="entladedatum" class="form-control" value="{{ $loading->entladedatum }}" placeholder="entladedatum" required />
                                                </div>
                                            </div>
                                            <div id="PanSub2collapse" class="panel-collapse @if (Session::has('openPanelInformation')) in @endif collapse">
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
                                            <input type="submit" class="btn btn-primary btn-block btn-form" value="Update" name="update" />
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!------SUBPANEL 2 : infos about pallets transfer-------->
                        <div class="panel subpanel">
                            <div class="panel-heading">
                                <a data-toggle="collapse" href="#Pan2collapse">Pallets location ?</a>
                                <div class="col-lg-offset-3">
                                    <p>order : {{$loading->anz}}</p>
                                </div>
                                <div class="col-lg-offset-3">
                                    <p>truck : {{$theoricalNumberPalletsTruck}} (planned) - {{$realNumberPalletsTruck}} (confirmed)</p>
                                </div>
                            </div>
                            <div id="Pan2collapse" class="panel-collapse @if(Session::has('openPanelPallets')) in @endif collapse">
                                <div class="panel-body">
                                    <form class="form-horizontal" role="form"  method="POST" action="{{route('submitUpdateUpload', $loading->atrnr)}}" enctype="multipart/form-data">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                        <!--msg-->
                                        <div class="container">
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
                                        <div class="container">
                                            <div class="from-group">
                                                <div class="col-lg-4 col-lg-offset-4">
                                                    <button type="submit" class="btn btn-add btn-block" value="addTransferForm" name="addTransferForm" data-toggle="collapse" data-target="#addForm">
                                                        Add transfer
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <!--Add form-->
                                        <div id="addForm" class="row collapse in">
                                            @if(isset($addTransferForm)|| isset($addPalletstransfer)|| isset($showAddCorrectingTransfer))
                                                <div class="panel subpanel">
                                                    <div class="panel-body">
                                                        <div class="form-group">
                                                            <div class="text-center">
                                                                <label for="legend" class="control-label">@if(isset($showAddCorrectingTransfer))<span class="glyphicon glyphicon-check"></span>CORRECTING TRANSFER@else NORMAL TRANSFER@endif</label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <!--type-->
                                                            <div class="col-lg-1 col-lg-offset-1">
                                                                <label for="type" class="control-label">*Type:</label>
                                                            </div>
                                                            <div class="col-lg-2">
                                                                @if(isset($showAddCorrectingTransfer))
                                                                    <select class="selectpicker show-tick form-control" data-size="5" data-live-search="true" data-live-search-style="startsWith" title="Type" name="type" id="typeL" onchange="displayFieldsTypeCorrecting(this);">
                                                                        @if(Illuminate\Support\Facades\Input::old('type') || isset($type))
                                                                            <optgroup label="Correcting">
                                                                                <option @if(strcmp('Purchase-Sale',old('type'))==0 || strcmp($type,'Purchase-Sale')==0) selected @endif value="Purchase-Sale" id="Purchase-SaleOptionL">
                                                                                    Purchase-Sale
                                                                                </option>
                                                                                <option @if(strcmp('Other',old('type'))==0 || strcmp($type,'Other')==0) selected @endif value="Other" id="OtherOptionL">
                                                                                    Other
                                                                                </option>
                                                                            </optgroup>
                                                                        @endif
                                                                    </select>
                                                                @else
                                                                    <select class="selectpicker show-tick form-control" data-size="5" data-live-search="true" data-live-search-style="startsWith" title="Type" name="type" id="typeL" onchange="displayFieldsTypeNormal(this);">
                                                                        @if(Illuminate\Support\Facades\Input::old('type') || isset($type))
                                                                            <optgroup label="Normal">
                                                                                <option @if(strcmp(old('type'),'Deposit-Withdrawal')==0|| strcmp($type,'Deposit-Withdrawal')==0) selected @endif value="Deposit-Withdrawal" id="Deposit-WithdrawalOptionL">
                                                                                    Deposit-Withdrawal
                                                                                </option>
                                                                                <option @if(strcmp(old('type'),'Withdrawal-Deposit')==0|| strcmp($type,'Withdrawal-Deposit')==0) selected @endif value="Withdrawal-Deposit" id="Withdrawal-DepositOptionL">
                                                                                    Withdrawal-Deposit
                                                                                </option>
                                                                                <option @if(strcmp(old('type'),'Deposit_Only')==0 || strcmp($type,'Deposit_Only')==0) selected @endif value="Deposit_Only" id="Deposit_OnlyOptionL">
                                                                                    Deposit_Only
                                                                                </option>
                                                                                <option @if(strcmp(old('type'),'Withdrawal_Only')==0 ||strcmp($type,'Withdrawal_Only')==0) selected @endif value="Withdrawal_Only" id="Withdrawal_OnlyOptionL">
                                                                                    Withdrawal_Only
                                                                                </option>
                                                                            </optgroup>
                                                                        @endif
                                                                    </select>
                                                                @endif
                                                            </div>
                                                            <!--details-->
                                                            <div class="col-lg-6">
                                                                <textarea class="form-control" name="details" rows="1" placeholder="Details (broken pallets, gift, receipt...)">
                                                                    @if(isset($details))
                                                                        {{$details}}
                                                                    @else
                                                                        {{old('details')}}
                                                                    @endif
                                                                </textarea>
                                                            </div>
                                                            <!--close add form-->
                                                            <div class="col-lg-offset-11">
                                                                <button type="submit" class="btn glyphicon glyphicon-remove" value="close" name="closeSubmitAddModal"></button>
                                                            </div>
                                                        </div>
                                                        <!--deposit-->
                                                        <div class="form-group" id="deposit-withdrawal1L" @if(isset($type) && $type=='Deposit-Withdrawal')style="display: block" @else style="display:none" @endif>
                                                            <div class="col-lg-12 text-center">
                                                                <label for="deposit" class="control-label">DEPOSIT</label>
                                                            </div>
                                                        </div>
                                                        <!--withdrawal-->
                                                        <div class="form-group" id="withdrawal-deposit1L" @if(isset($type) &&$type=='Withdrawal-Deposit') style="display:block" @else  style="display:none;" @endif>
                                                            <div class="col-lg-12 text-center">
                                                                <label for="withdrawal" class="control-label">WITHDRAWAL</label>
                                                            </div>
                                                        </div>
                                                        <!--purchase-->
                                                        <div class="form-group" id="purchase-sale1L" @if(isset($type) &&$type=='Purchase-Sale')style="display:block" @else style="display: none;" @endif>
                                                            <div class="col-lg-12 text-center">
                                                                <label for="purchase" class="control-label">PURCHASE</label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <!--number of pallets-->
                                                            <div class="col-lg-2">
                                                                <label for="palletsNumber" class="control-label">*Pallets number:</label>
                                                            </div>
                                                            <div class="col-lg-1">
                                                                <input id="palletsNumber" type="number" class="form-control" name="palletsNumber"
                                                                       value="@if(isset($palletsNumber)){{$palletsNumber}}
                                                                       @elseif(Illuminate\Support\Facades\Input::old('palletsNumber')){{ old('palletsNumber') }}
                                                                       @else0 @endif" placeholder="Nbr" min="0" autofocus />
                                                            </div>
                                                            <!--date-->
                                                            <div class="col-lg-1">
                                                                <label for="date" class="control-label">Date:</label>
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <input id="date" type="date" class="form-control" name="date" value="{{ $loading->ladedatum }}" placeholder="Date" autofocus />
                                                            </div>
                                                            <!--transfer normal associated-->
                                                            @if(isset($showAddCorrectingTransfer))
                                                                <div class="col-lg-2 text-right">
                                                                    <label for="normalTransferAssociated" class="control-label">*Associated:</label>
                                                                </div>
                                                                <div class="col-lg-1">
                                                                    <select class="selectpicker show-tick form-control" data-size="5" data-live-search="true" data-live-search-style="startsWith" title="Normal transfer associated" name="normalTransferAssociated">
                                                                        @foreach($listPalletstransfersNormal as $normalTransfer )
                                                                            @if(Illuminate\Support\Facades\Input::old('normalTransferAssociated') && $normalTransfer->id==old('normalTransferAssociated'))
                                                                                <option value="{{$normalTransfer->id}}"
                                                                                        selected>{{$normalTransfer->id}}</option>
                                                                            @elseif(isset($normalTransferAssociated)&&$normalTransfer->id==$normalTransferAssociated)
                                                                                <option value="{{$normalTransfer->id}}"
                                                                                        selected>{{$normalTransfer->id}}</option>
                                                                            @elseif(!isset($normalTransferAssociated)&& $showAddCorrectingTransfer==$normalTransfer->id)
                                                                                <option value="{{$normalTransfer->id}}"
                                                                                        selected>{{$normalTransfer->id}}</option>
                                                                            @else
                                                                                <option value="{{$normalTransfer->id}}">{{$normalTransfer->id}}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            @endif
                                                        <!--add pallet account-->
                                                            <div class="col-lg-2 col-lg-offset-1 text-left">
                                                                <a href="{{route('showAddPalletsaccount')}}" class="link"><span class="glyphicon glyphicon-plus-sign"></span> Add account</a>
                                                            </div>
                                                            <!--errors messages-->
                                                            <div class="form-group">
                                                                @if(Session::has('errorFields'))
                                                                    <div class="alert alert-danger text-alert text-center">{{ Session::get('errorFields') }}</div>
                                                                @elseif(Session::has('errorType'))
                                                                    <div class="alert alert-danger text-alert text-center">{{ Session::get('errorType') }}</div>
                                                                @endif
                                                            </div>
                                                            <div class="form-group">
                                                                <!--debit account-->
                                                                <div class="col-lg-2">
                                                                    <label for="debitAccount" class="control-label">*Debit account:</label>
                                                                </div>
                                                                <div class="col-lg-4" id="debitAccount0"  @if(!isset($type)) style="display: block" @else style="display:none;" @endif>
                                                                    <select class="selectpicker show-tick form-control" data-size="10" data-live-search="true" data-live-search-style="startsWith" title="Debit Account" name="debitAccount" disabled="true" >
                                                                        <option></option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-4" id="debitAccount3" @if(isset($type) && ($type=='Withdrawal_Only' || $type=='Other')) style="display: block" @else style="display:none" @endif>
                                                                    <select class="selectpicker show-tick form-control" data-size="10" data-live-search="true" data-live-search-style="startsWith" id="select-debit3b" title="Debit Account" name="debitAccount3b" onchange="selectAccount(this.value, '3b');">
                                                                        @foreach($listPalletsAccounts as $palletsAccount )
                                                                            @if(Illuminate\Support\Facades\Input::old('debitAccount3') && (strpos(old('debitAccount3'), '-') == 7 && explode('-', old('debitAccount3'))[0] == 'account') && ($palletsAccount->id==explode('-', old('debitAccount3'))[1]))
                                                                                <option value="account-{{$palletsAccount->id}}"
                                                                                        selected>{{$palletsAccount->name}}</option>
                                                                            @elseif(isset($debitAccount) && (strpos($debitAccount, '-') == 7 && explode('-', $debitAccount)[0] == 'account') && ($palletsAccount->id==explode('-', $debitAccount)[1]))
                                                                                <option value="account-{{$palletsAccount->id}}"
                                                                                        selected>{{$palletsAccount->name}}</option>
                                                                            @else
                                                                                <option value="account-{{$palletsAccount->id}}">{{$palletsAccount->name}}</option>
                                                                            @endif
                                                                        @endforeach
                                                                        @foreach($listTrucksAccounts as $trucksAccount )
                                                                            @if(Illuminate\Support\Facades\Input::old('debitAccount3') && (strpos(old('debitAccount3'), '-') == 5 && explode('-', old('debitAccount3'))[0] == 'truck') && ($trucksAccount->id==explode('-', old('debitAccount3'))[1]))
                                                                                <option value="truck-{{$trucksAccount->id}}"
                                                                                        selected>{{$trucksAccount->name}}
                                                                                    - {{$trucksAccount->licensePlate}}</option>
                                                                            @elseif(isset($debitAccount)&& (strpos($debitAccount, '-') == 5 && explode('-', $debitAccount)[0] == 'truck') && ($trucksAccount->id==explode('-', $debitAccount)[1]))
                                                                                <option value="truck-{{$trucksAccount->id}}"
                                                                                        selected>{{$trucksAccount->name}}
                                                                                    - {{$trucksAccount->licensePlate}}</option>
                                                                            @else
                                                                                <option value="truck-{{$trucksAccount->id}}">{{$trucksAccount->name}}
                                                                                    - {{$trucksAccount->licensePlate}}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-4" id="debitAccount1" @if(isset($type) && ($type=='Deposit_Only'||$type=='Deposit-Withdrawal')) style="display: block" @else style="display:none;" @endif>
                                                                    <select class="selectpicker show-tick form-control" data-size="10" data-live-search="true" data-live-search-style="startsWith" id="select-debit1b" title="Debit Account" name="debitAccount1b" onchange="selectAccount(this.value, '1b');">
                                                                        @foreach($listTrucksPossible as $trucksAccount )
                                                                            @if(Illuminate\Support\Facades\Input::old('debitAccount1') && (strpos(old('debitAccount1'), '-') == 5 && explode('-', old('debitAccount1'))[0] == 'truck') && ($trucksAccount->id==explode('-', old('debitAccount1'))[1]))
                                                                                <option value="truck-{{$trucksAccount->id}}" selected>{{$trucksAccount->name}} - {{$trucksAccount->licensePlate}}</option>
                                                                            @elseif(isset($debitAccount)&& (strpos($debitAccount, '-') == 5 && explode('-', $debitAccount)[0] == 'truck') && ($trucksAccount->id==explode('-', $debitAccount)[1]))
                                                                                <option value="truck-{{$trucksAccount->id}}" selected>{{$trucksAccount->name}} - {{$trucksAccount->licensePlate}}</option>
                                                                            @else
                                                                                <option value="truck-{{$trucksAccount->id}}">{{$trucksAccount->name}} - {{$trucksAccount->licensePlate}}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-4" id="debitAccount2" @if(isset($type) && $type=='Withdrawal-Deposit') style="display: block" @else  style="display:none;" @endif >
                                                                    <select class="selectpicker show-tick form-control" data-size="10" data-live-search="true" data-live-search-style="startsWith" id="select-debit2b" title="Debit Account" name="debitAccount2b" onchange="selectAccount(this.value, '2b');">
                                                                        @foreach($listPalletsAccounts as $palletsAccount )
                                                                            @if(Illuminate\Support\Facades\Input::old('debitAccount2b') && (strpos(old('debitAccount2b'), '-') == 7 && explode('-', old('debitAccount2b'))[0] == 'account') && ($palletsAccount->id==explode('-', old('debitAccount2b'))[1]))
                                                                                <option value="account-{{$palletsAccount->id}}" selected>{{$palletsAccount->name}}</option>
                                                                            @elseif(isset($debitAccount) && (strpos($debitAccount, '-') == 7 && explode('-', $debitAccount)[0] == 'account') && ($palletsAccount->id==explode('-', $debitAccount)[1]))
                                                                                <option value="account-{{$palletsAccount->id}}" selected>{{$palletsAccount->name}}</option>
                                                                            @else
                                                                                <option value="account-{{$palletsAccount->id}}">{{$palletsAccount->name}}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-4" id="debitAccount4" @if(isset($type) && $type=='Purchase-Sale') style="display: block" @else style="display: none;" @endif>
                                                                    <select class="selectpicker show-tick form-control"data-size="10"data-live-search="true" data-live-search-style="startsWith"id="select-debit4b" title="Debit Account" name="debitAccount4b" onchange="selectAccount(this.value, '4b');">
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
                                                                            <option value="account-1">STOCK</option>
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
                                                                <!--credit account-->
                                                                <div class="col-lg-2">
                                                                    <label for="creditAccount" class="control-label">*Credit account:</label>
                                                                </div>
                                                                <div class="col-lg-4" id="creditAccount0" @if(!isset($type)) style="display: block" @else style="display: none" @endif>
                                                                    <select class="selectpicker show-tick form-control" data-size="10" data-live-search="true" data-live-search-style="startsWith" title="Credit Account" name="creditAccount" disabled="true">
                                                                        <option></option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-4" id="creditAccount3"
                                                                     @if(isset($type) && ($type=='Deposit_Only'||$type=='Other')) style="display: block" @else style="display: none" @endif>
                                                                    <select class="selectpicker show-tick form-control" data-live-search="true" data-live-search-style="startsWith" title="Credit Account" name="creditAccount3b" id="select-credit3b" onchange="creditaccount(this.value, '3b');">
                                                                        @foreach($listPalletsAccounts as $palletsAccount )
                                                                            @if(Illuminate\Support\Facades\Input::old('creditAccount3') && (strpos(old('creditAccount3'), '-') == 7 && explode('-', old('creditAccount3'))[0] == 'account') && ($palletsAccount->id==explode('-', old('creditAccount3'))[1]))
                                                                                <option value="account-{{$palletsAccount->id}}"
                                                                                        selected>{{$palletsAccount->name}}</option>
                                                                            @elseif(isset($creditAccount) && (strpos($creditAccount, '-') == 7 && explode('-', $creditAccount)[0] == 'account') && ($palletsAccount->id==explode('-', $creditAccount)[1]))
                                                                                <option value="account-{{$palletsAccount->id}}"
                                                                                        selected>{{$palletsAccount->name}}</option>
                                                                            @else
                                                                                <option value="account-{{$palletsAccount->id}}">{{$palletsAccount->name}}</option>
                                                                            @endif
                                                                        @endforeach
                                                                        @foreach($listTrucksAccounts as $trucksAccount )
                                                                            @if(Illuminate\Support\Facades\Input::old('creditAccount3') && (strpos(old('creditAccount3'), '-') == 5 && explode('-', old('creditAccount3'))[0] == 'truck') && ($trucksAccount->id==explode('-', old('creditAccount3'))[1]))
                                                                                <option value="truck-{{$trucksAccount->id}}"
                                                                                        selected>{{$trucksAccount->name}}
                                                                                    - {{$trucksAccount->licensePlate}}</option>
                                                                            @elseif(isset($creditAccount)&& (strpos($creditAccount, '-') == 5 && explode('-', $creditAccount)[0] == 'truck') && ($trucksAccount->id==explode('-', $creditAccount)[1]))
                                                                                <option value="truck-{{$trucksAccount->id}}"
                                                                                        selected>{{$trucksAccount->name}}
                                                                                    - {{$trucksAccount->licensePlate}}</option>
                                                                            @else
                                                                                <option value="truck-{{$trucksAccount->id}}">{{$trucksAccount->name}}
                                                                                    - {{$trucksAccount->licensePlate}}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-4" id="creditAccount1" @if(isset($type) && ($type=='Withdrawal_Only'||$type=='Withdrawal-Deposit')) style="display: block;" @else style="display: none;" @endif>
                                                                    <select class="selectpicker show-tick form-control" data-size="10" data-live-search="true" data-live-search-style="startsWith" title="Credit Account" name="creditAccount1b" id="select-credit1b" onchange="creditaccount(this.value, '1b');">
                                                                        @foreach($listTrucksPossible as $trucksAccount )
                                                                            @if(Illuminate\Support\Facades\Input::old('creditAccount1') && (strpos(old('creditAccount1'), '-') == 5 && explode('-', old('creditAccount1'))[0] == 'truck') && ($trucksAccount->id==explode('-', old('creditAccount1'))[1]))
                                                                                <option value="truck-{{$trucksAccount->id}}"
                                                                                        selected>{{$trucksAccount->name}}
                                                                                    - {{$trucksAccount->licensePlate}}</option>
                                                                            @elseif(isset($creditAccount)&& (strpos($creditAccount, '-') == 5 && explode('-', $creditAccount)[0] == 'truck') && ($trucksAccount->id==explode('-', $creditAccount)[1]))
                                                                                <option value="truck-{{$trucksAccount->id}}"
                                                                                        selected>{{$trucksAccount->name}}
                                                                                    - {{$trucksAccount->licensePlate}}</option>
                                                                            @else
                                                                                <option value="truck-{{$trucksAccount->id}}">{{$trucksAccount->name}}
                                                                                    - {{$trucksAccount->licensePlate}}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-4" id="creditAccount2" @if(isset($type) && $type=='Deposit-Withdrawal') style="display: block;" @else style="display:none;" @endif>
                                                                    <select class="selectpicker show-tick form-control" data-size="10" data-live-search="true" data-live-search-style="startsWith" title="Credit Account" name="creditAccount2b" id="select-credit2b"onchange="creditaccount(this.value, '2b');">
                                                                        @foreach($listPalletsAccounts as $palletsAccount )
                                                                            @if(Illuminate\Support\Facades\Input::old('creditAccount2b') && (strpos(old('creditAccount2b'), '-') == 7 && explode('-', old('creditAccount2b'))[0] == 'account') && ($palletsAccount->id==explode('-', old('creditAccount2b'))[1]))
                                                                                <option value="account-{{$palletsAccount->id}}"
                                                                                        selected>{{$palletsAccount->name}}</option>
                                                                            @elseif(isset($creditAccount) && (strpos($creditAccount, '-') == 7 && explode('-', $creditAccount)[0] == 'account') && ($palletsAccount->id==explode('-', $creditAccount)[1]))
                                                                                <option value="account-{{$palletsAccount->id}}"
                                                                                        selected>{{$palletsAccount->name}}</option>
                                                                            @else
                                                                                <option value="account-{{$palletsAccount->id}}">{{$palletsAccount->name}}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-4" id="creditAccount4" @if(isset($type) && $type=='Purchase-Sale') style="display: block" @else style="display: none" @endif>
                                                                    <select class="selectpicker show-tick form-control" data-size="10" data-live-search="true" data-live-search-style="startsWith" title="Credit Account" name="creditAccount4b" id="select-credit4b" onchange="creditaccount(this.value, '4b');">
                                                                        @if(isset($debitAccountCorr) && isset($creditAccountCorr))
                                                                            <option value="account-1">
                                                                                STOCK
                                                                            </option>
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
                                                                                <option selected
                                                                                        value="truck-{{explode('-', $creditAccount)[1]}}">{{$nameTruckAccount}}
                                                                                    - {{$licensePlate}}</option>
                                                                            @elseif (strpos($creditAccount, '-') == 7 && explode('-', $creditAccount)[0] == 'account')
                                                                                @php($namePalletsAccount = App\Palletsaccount::where('id', explode('-', $creditAccount)[1])->value('name'))
                                                                                <option selected
                                                                                        value="account-{{explode('-', $creditAccount)[1]}}">{{$namePalletsAccount}}</option>
                                                                            @endif
                                                                        @endif
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            @if(Session::has('errorFields2'))
                                                                <div class="form-group">
                                                                    <div class="alert alert-danger text-alert text-center">{{ Session::get('errorFields2') }}</div>
                                                                </div>
                                                        @endif
                                                        <!--withdrawal-->
                                                            <div id="deposit-withdrawal2L" @if(isset($type) &&$type=='Deposit-Withdrawal') style="display:block;" @else style="display: none;" @endif>
                                                                <div class="form-group">
                                                                    <div class="col-lg-12 text-center">
                                                                        <label for="withdrawal" class="control-label">WITHDRAWAL</label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="col-lg-12 text-center">
                                                                        <p>You should fulfill the withdrawal associated. If you don't want to do it now, you will have to do it by the transfer details page</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!--deposit-->
                                                            <div id="withdrawal-deposit2L" @if(isset($type) &&$type=='Withdrawal-Deposit') style="display:block" @else style="display: none" @endif>
                                                                <div class="form-group">
                                                                    <div class="col-lg-12 text-center">
                                                                        <label for="deposit" class="control-label">DEPOSIT</label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="col-lg-12 text-center">
                                                                        <p>You should fulfill the deposit associated.If you don't want to do it now, you will have to do it by the transfer details page</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!--sale-->
                                                            <div id="purchase-sale2L" @if(isset($type) &&$type=='Purchase-Sale')style="display:block" @else style="display: none;" @endif>
                                                                <div class="form-group">
                                                                    <div class="col-lg-12 text-center">
                                                                        <label for="sale" class="control-label">SALE</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!--2nd transfer : pallets number only-->
                                                            <div id="DWL" @if(isset($type) &&($type=='Withdrawal-Deposit' ||$type=='Deposit-Withdrawal')) style="display:block;" @else style="display:none;"@endif>
                                                                <div class="form-group">
                                                                    <!--number of pallets-->
                                                                    <div class="col-lg-2">
                                                                        <label for="palletsNumber2" class="control-label">Pal.nbr:</label>
                                                                    </div>
                                                                    <div class="col-lg-1">
                                                                        <input id="palletsNumber2" type="number"class="form-control" name="palletsNumber2"
                                                                               value=" @if(Illuminate\Support\Facades\Input::old('palletsNumber2')){{ old('palletsNumber2') }}  @elseif(isset($palletsNumber2)) {{$palletsNumber2}} @else 0 @endif"
                                                                               placeholder="Nbr" min="0"  autofocus />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!--2nd transfer : pallets number credit account and debit account-->
                                                            <div id="SPL" @if(isset($type) &&($type=='Purchase-Sale'))style="display:block" @else  style="display:none;" @endif>
                                                                <div class="form-group">
                                                                    <!--number of pallets-->
                                                                    <div class="col-lg-1">
                                                                        <label for="palletsNumber2C" class="control-label">Nbr:</label>
                                                                    </div>
                                                                    <div class="col-lg-1 text-left">
                                                                        <input id="palletsNumber2C" type="number" class="form-control" name="palletsNumber2C"
                                                                               value="@if(Illuminate\Support\Facades\Input::old('palletsNumber2C')){{ old('palletsNumber2C') }} @elseif(isset($palletsNumber2C)) {{$palletsNumber2C}} @endif 0 "
                                                                               placeholder="Nbr" min="0" autofocus />
                                                                    </div>
                                                                    <div class="col-lg-2 text-right">
                                                                        <label for="debitAccount2" class="control-label">*Debit:</label>
                                                                    </div>
                                                                    <div class="col-lg-3">
                                                                        <select class="selectpicker show-tick form-control" data-size="10" data-live-search="true" data-live-search-style="startsWith" title="Debit Account (=saler)" name="debitAccount2" id="select-debit2" onchange="selectAccount2(this.value);">
                                                                            @if(isset($debitAccountCorr) && isset($creditAccountCorr))
                                                                                <option value="account-1">STOCK</option>
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
                                                                    <div class="col-lg-2 text-right">
                                                                        <label for="creditAccount2" class="control-label">*Credit:</label>
                                                                    </div>
                                                                    <div class="col-lg-3">
                                                                        <select class="selectpicker show-tick form-control" data-size="10" data-live-search="true" data-live-search-style="startsWith" title="Credit Account (=purchaser)" name="creditAccount2" id="select-credit2" onchange="creditaccount2(this.value);">
                                                                            @if(isset($debitAccountCorr) && isset($creditAccountCorr))
                                                                                <option value="account-1">STOCK</option>
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
                                                                                    <option selected
                                                                                            value="truck-{{explode('-', $creditAccount2)[1]}}">{{$nameTruckAccount}}
                                                                                        - {{$licensePlate}}</option>
                                                                                @elseif (strpos($creditAccount2, '-') == 7 && explode('-', $creditAccount2)[0] == 'account')
                                                                                    @php($namePalletsAccount = App\Palletsaccount::where('id', explode('-', $creditAccount2)[1])->value('name'))
                                                                                    <option selected
                                                                                            value="account-{{explode('-', $creditAccount2)[1]}}">{{$namePalletsAccount}}</option>
                                                                                @endif
                                                                            @endif
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <div class="col-lg-4 col-lg-offset-4">
                                                                            <input type="submit" class="btn btn-primary btn-block btn-form" value="Add" name="addPalletstransfer" data-toggle="modal" data-target="#submitAdd_modal" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>


    <!-- BEGIN MODAL SECTION -->

    <!-- Modal update pt -->
    <div class="modal fade" id="updatePT_modal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Why would you like to change the loading into a loading WITHOUT exchange pallets ?</h4>
                </div>
                <div class="modal-body center">
                    <form role="form" method="POST" action="">
                        <input type="hidden"name="_token" value="{{ csrf_token() }}"/>
                        <textarea class="form-control" rows="5" id="reasonUpdatePT" name="reasonUpdatePT" required autofocus>{{$loading->reasonUpdatePT}}</textarea>
                        <button type="button" class="btn btn-success btn-modal" data-toggle="modal" data-target="#updateValidatePT_modal">
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
                                        <h4 class="modal-title"> Are you sure that loading is WITHOUT exchange pallets?</h4>
                                    </div>
                                    <div class="modal-body center">
                                        <h4>If you have made a mistake you can change this information directly on the database</h4>
                                        <br>
                                        <form role="form" method="POST" action="{{ route('submitUpdateUpload', $loading->atrnr) }}">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                            <div class="col-lg-offset-3">
                                                <input type="submit" class="btn btn-danger btn-modal" value="Yes" name="updateValidatePT" />
                                                <button type="button" class="btn btn-success btn-modal" data-dismiss="modal">
                                                    No
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default btn-modal" data-dismiss="modal">
                                            Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-modal" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal submit -->
    @if(isset($addPalletstransfer))
        <div class="modal show" id="submitAdd_modal" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header modalHeaderTransfer">
                        <button type="submit" class="close" value="close" name="closeSubmitAddModal">
                            &times;
                        </button>
                        <h4 class="modal-title text-center">INFORMATION</h4>
                    </div>
                    <div class="modal-body center modalBodyTransfer">
                        <p class="text-center">Here,PLANNED pallets number</p>
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th></th>
                                @if(Session::has('debitAccount'))
                                    <th class="text-center">DEBIT</th>
                                @endif
                                @if(Session::has('creditAccount'))
                                    <th class="text-center">CREDIT</th>
                                @endif
                                @if(Session::has('creditAccount2')&&Session::has('debitAccount2')&&Session::has('palletsNumber2'))
                                    <th class="text-center">DEBIT 2</th>
                                    <th class="text-center">CREDIT 2</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td></td>
                                @if(Session::has('debitAccount'))
                                    <td class="text-center">{{request()->session()->get('debitAccount')}}</td>
                                @endif
                                @if(Session::has('creditAccount'))
                                    <td class="text-center">{{request()->session()->get('creditAccount')}}</td>
                                @endif
                                @if(Session::has('creditAccount2')&&Session::has('debitAccount2')&&Session::has('palletsNumber2'))
                                    <td class="text-center">{{request()->session()->get('debitAccount2')}}</td>
                                    <td class="text-center">{{request()->session()->get('creditAccount2')}}</td>
                                @endif
                            </tr>
                            <tr>
                                <td class="text-center"> Actual</td>
                                @if(Session::has('debitAccount'))
                                    <td class="text-center">{{request()->session()->get('palletsNumberDebitAccount')}}</td>
                                @endif
                                @if(Session::has('creditAccount'))
                                    <td class="text-center">{{request()->session()->get('palletsNumberCreditAccount')}}</td>
                                @endif
                                @if(Session::has('creditAccount2')&&Session::has('debitAccount2')&&Session::has('palletsNumber2'))
                                    <td class="text-center">{{request()->session()->get('palletsNumberDebitAccount2')}}</td>
                                    <td class="text-center">{{request()->session()->get('palletsNumberCreditAccount2')}}</td>
                                @endif
                            </tr>
                            <tr>
                                <td class="text-center"> New transfer</td>
                                @if(Session::has('debitAccount'))
                                    <td class="text-center">
                                        - {{request()->session()->get('palletsNumber')}}</td>
                                @endif
                                @if(Session::has('creditAccount'))
                                    <td class="text-center">
                                        + {{request()->session()->get('palletsNumber')}}</td>
                                @endif
                                @if(Session::has('creditAccount2')&&Session::has('debitAccount2')&&Session::has('palletsNumber2'))
                                    <td class="text-center">
                                        - {{request()->session()->get('palletsNumber2')}}</td>
                                    <td class="text-center">
                                        + {{request()->session()->get('palletsNumber2')}}</td>
                                @endif
                            </tr>
                            <tr>
                                <td class="text-center">Total</td>
                                @if(Session::has('debitAccount'))
                                    <td class="text-center">
                                        = {{request()->session()->get('palletsNumberDebitAccount') -request()->session()->get('palletsNumber')}}</td>
                                @endif
                                @if(Session::has('creditAccount'))
                                    <td class="text-center">
                                        = {{request()->session()->get('palletsNumberCreditAccount')+request()->session()->get('palletsNumber')}}</td>
                                @endif
                                @if(Session::has('creditAccount2')&&Session::has('debitAccount2')&&Session::has('palletsNumber2'))
                                    <td class="text-center">
                                        = {{request()->session()->get('palletsNumberDebitAccount2') -request()->session()->get('palletsNumber2')}}</td>
                                    <td class="text-center">
                                        = {{request()->session()->get('palletsNumberCreditAccount2')+request()->session()->get('palletsNumber2')}}</td>
                                @endif
                            </tr>
                            </tbody>
                        </table>
                        @if(($type=='Deposit-Withdrawal' || $type=='Withdrawal-Deposit'|| $type=='Purchase-Sale')&&(Session::has('creditAccount2')&&Session::has('debitAccount2')&&Session::has('palletsNumber2')&& (request()->session()->get('palletsNumber2')<>request()->session()->get('palletsNumber'))))
                            <div class="text-center">
                                <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                <span class="glyphicon glyphicon-warning-sign text-danger"></span><span
                                        class="text-danger"> Pallets numbers are DIFFERENT for both transfers </span>
                            </div>
                        @endif
                        @if(($type=='Deposit-Withdrawal' || $type=='Withdrawal-Deposit' )&&((Session::has('palletsNumber')&&(request()->session()->get('palletsNumber')<>$loading->anz))||(Session::has('palletsNumber2')&&(request()->session()->get('palletsNumber2')<>$loading->anz))))
                            <div class="text-center">
                                <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                <span class="text-danger">Pallets number does NOT MATCH the number expected in the loading order ({{$loading->anz}}
                                    )</span>
                            </div>
                        @endif
                        @if(Session::has('sumTransfersDepositOnly') && Session::has('sumTransfersWithdrawalOnly') && request()->session()->get('sumTransfersDepositOnly')<>request()->session()->get('sumTransfersWithdrawalOnly') )
                            <div class="text-center">
                                <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                <span class="text-danger"> Sum of deposit only transfers does NOT MATCH the sum of withdrawal only transfers </span>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        @if((($type=='Deposit-Withdrawal' || $type=='Withdrawal-Deposit'|| $type=='Purchase-Sale')&&(Session::has('creditAccount2')&&Session::has('debitAccount2')&&Session::has('palletsNumber2')&&(request()->session()->get('palletsNumber2')<>request()->session()->get('palletsNumber'))))||(($type=='Deposit-Withdrawal' || $type=='Withdrawal-Deposit')&&((Session::has('palletsNumber')&&(request()->session()->get('palletsNumber')<>$loading->anz))||(Session::has('palletsNumber2')&&(request()->session()->get('palletsNumber2')<>$loading->anz))))||(Session::has('sumTransfersDepositOnly') && Session::has('sumTransfersWithdrawalOnly') && request()->session()->get('sumTransfersDepositOnly')<>request()->session()->get('sumTransfersWithdrawalOnly')))
                            <button type="submit" class="btn btn-danger btn-modal" value="yes" name="okSubmitAddModal">
                                Confirm
                            </button>
                        @else
                            <button type="submit" class="btn btn-default btn-form btn-modal" value="yes" name="okSubmitAddModal">
                                Confirm
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- END MODAL SECTION -->

    <script>
        $(document).ready(function () { $('[data-toggle="tooltip"]').tooltip();});
    </script>
    <script type="text/javascript" src="{{asset('js/addUpdateTransferLoading.js')}}">
    </script>
@endsection
