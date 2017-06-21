<?php

namespace App\Http\Controllers;

use App\Document;
use App\Loading;
use App\PalletsAccount;
use App\Palletstransfer;
use App\Truck;
use App\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DetailsLoadingController extends Controller
{
    /**
     * Display the content.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($atrnr)
    {
        if (Auth::check()) {
            ////////PANEL INFO///////
            $loading = Loading::where('atrnr', '=', $atrnr)->first();

            //////PALLETS PANEL//////
            //all pallets account
            $listPalletsAccounts = Palletsaccount::get();
            $listPalletstransfers = Palletstransfer::where('loading_atrnr', $atrnr)->get();
            $listTypes = ['Deposit', 'Withdrawal', 'Purchase', 'Sale', 'Other'];

            //truck
            $listPalletsaccountsCarrier = Palletsaccount::where('type', 'Carrier')->get();
            //looking for the account that contains the license plate if it's set
            if ($loading->kennzeichen == "") {
                $licensePlate = 'OTHER';
            } else {
                $licensePlate = $loading->kennzeichen;
            }
            if (Truck::where('name', trim(explode(',', $loading->subfrachter)[0]))->where('licensePlate', $licensePlate)->first() <> null) {
                $namePalletsAccountTruck = Truck::where('name', trim(explode(',', $loading->subfrachter)[0]))->where('licensePlate', $licensePlate)->first()->palletsaccount_name;
                if ($namePalletsAccountTruck <> null) {
                    $palletsAccountFavoriteTruck = Palletsaccount::where('name', $namePalletsAccountTruck)->first()->name;
                }
            }
            return view('loadings.detailsLoading', compact('loading', 'listPalletsAccounts', 'listPalletstransfers',
                'palletsAccountFavoriteTruck', 'listPalletsaccountsCarrier', 'listTypes'
            ));
        } else {
            return view('auth.login');
        }
    }

    public function updatePanel1(Request $request, $atrnr)
    {
        $ladedatum = Input::get('ladedatum');
        $entladedatum = Input::get('entladedatum');
        $disp = Input::get('disp');
        $referenz = Input::get('referenz');
        $auftraggeber = Input::get('auftraggeber');
        $beladestelle = Input::get('beladestelle');
        $ortb = Input::get('ortb');
        $plzb = Input::get('plzb');
        $landb = Input::get('landb');
        $entladestelle = Input::get('entladestelle');
        $orte = Input::get('orte');
        $plze = Input::get('plze');
        $lande = Input::get('lande');
        $anz = Input::get('anz');
        $art = Input::get('art');
        $ware = Input::get('ware');
        $subfrachter = Input::get('subfrachter');
        $kennzeichen = Input::get('kennzeichen');
        $zusladestellen = Input::get('zusladestellen');
        $reasonUpdatePT = Input::get('reasonUpdatePT');

        $rules = array(
            'disp' => 'required|string|max:4',
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            if (isset($reasonUpdatePT) && isset($request->updateValidatePT)) {
                Loading::where('atrnr', $atrnr)->update(['reasonUpdatePT' => $reasonUpdatePT, 'pt' => 'NEIN']);
                session()->flash('messageUpdatePTLoading', 'Be careful : your loading is now WITHOUT exchange pallets');
            } elseif (isset($request->update)) {
                Loading::where('atrnr', $atrnr)->update(['ladedatum' => $ladedatum, 'entladedatum' => $entladedatum, 'disp' => $disp, 'referenz' => $referenz, 'auftraggeber' => $auftraggeber, 'beladestelle' => $beladestelle,
                    'ortb' => $ortb, 'plzb' => $plzb, 'landb' => $landb, 'entladestelle' => $entladestelle, 'orte' => $orte, 'plze' => $plze, 'lande' => $lande, 'anz' => $anz, 'art' => $art, 'ware' => $ware,
                    'subfrachter' => $subfrachter, 'kennzeichen' => $kennzeichen, 'zusladestellen' => $zusladestellen]);
                session()->flash('messageUpdateLoading', 'Successfully updated loading');
            }
            session()->flash('openPanelInformation', 'openPanelInformation');
            return redirect()->back();
        }
    }

    public function submitUpdateUpload($atrnr, Request $request)
    {
        $loading = Loading::where('atrnr', $atrnr)->first();

        //buttons
        $update = Input::get('update');
        $addTransferForm = Input::get('addTransferForm');
        $addPalletstransfer = Input::get('addPalletstransfer');
        $okSubmitAddModal = Input::get('okSubmitAddModal');
        $closeSubmitAddModal = Input::get('closeSubmitAddModal');
        $uploadDocument = Input::get('upload');
        $delete = Input::get('delete');
        $deleteDocument = Input::get('deleteDocument');
$submitPallets=Input::get('submitPallets');
       $closeSubmitPalletsModal=Input::get('closeSubmitPalletsModal');
       $okSubmitPalletsModal=Input::get('okSubmitPalletsModal');
       $okSubmitPalletsValidateModal=Input::get('okSubmitPalletsValidateModal');

        $truckAccount = Input::get('truckAccount');

        $date = $loading->ladedatum;
        $listPalletsAccounts = Palletsaccount::get();
        $listPalletstransfers = Palletstransfer::where('loading_atrnr', $atrnr)->get();

        $listTypes = ['Deposit', 'Withdrawal', 'Purchase', 'Sale', 'Other'];
        //truck
        $listPalletsaccountsCarrier = Palletsaccount::where('type', 'Carrier')->get();

        if (isset($update)) {
            $this->updatePanel1($request, $loading->atrnr);
        } elseif (isset($addTransferForm)) {
            if (isset($truckAccount)) {
                Loading::where('atrnr', $atrnr)->update(['truckAccount' => $truckAccount]);
                $creditAccount = $truckAccount;
                $debitAccount = $truckAccount;
            } else {
                //looking for the account that contains the license plate if it's set
                if ($loading->kennzeichen == "") {
                    $licensePlate = 'OTHER';
                } else {
                    $licensePlate = $loading->kennzeichen;
                }
                if (Truck::where('name', trim(explode(',', $loading->subfrachter)[0]))->where('licensePlate', $licensePlate)->first() <> null) {
                    $namePalletsAccountTruck = Truck::where('name', trim(explode(',', $loading->subfrachter)[0]))->where('licensePlate', $licensePlate)->first()->palletsaccount_name;
                    if ($namePalletsAccountTruck <> null) {
                        $palletsAccountFavoriteTruck = Palletsaccount::where('name', $namePalletsAccountTruck)->first()->name;
                    }
                }
            }
            $loading = Loading::where('atrnr', $atrnr)->first();
            session()->flash('openPanelPallets', 'openPanelPallets');
            return view('loadings.DetailsLoading', compact('loading', 'palletsAccountFavoriteTruck', 'listPalletsaccountsCarrier', 'listPalletsAccounts', 'listPalletstransfers', 'date', 'listTypes', 'creditAccount', 'debitAccount', 'addTransferForm'));
        } elseif (isset($addPalletstransfer)) {
            $date = Input::get('date');
            $type = Input::get('type');
            $multiTransfer = Input::get('multiTransfer');
            $details = Input::get('details');
            $loading_atrnr = $atrnr;
            $creditAccount = Input::get('creditAccount');
            $debitAccount = Input::get('debitAccount');
            $palletsNumber = Input::get('palletsNumber');
            $actualTheoricalCreditPalletsNumber = Palletsaccount::where('name', $creditAccount)->value('theoricalNumberPallets');
            $actualTheoricalDebitPalletsNumber = Palletsaccount::where('name', $debitAccount)->value('theoricalNumberPallets');
            session()->flash('palletsNumber', $palletsNumber);
            session()->flash('creditAccount', $creditAccount);
            session()->flash('debitAccount', $debitAccount);
            session()->flash('palletsNumberCreditAccount', $actualTheoricalCreditPalletsNumber);
            session()->flash('palletsNumberDebitAccount', $actualTheoricalDebitPalletsNumber);
            session()->flash('openPanelPallets', 'openPanelPallets');
            return view('loadings.DetailsLoading', compact('loading', 'listPalletsaccountsCarrier', 'listPalletsAccounts', 'listPalletstransfers', 'listFilesNames', 'loading_atrnr', 'date', 'type', 'multiTransfer', 'details', 'listTypes', 'creditAccount', 'debitAccount', 'palletsNumber', 'addPalletstransfer'));
        } elseif (isset($okSubmitAddModal)) {
            $date = Input::get('date');
            $type = Input::get('type');
            $multiTransfer = Input::get('multiTransfer');
            $details = Input::get('details');
            $loading_atrnr = $atrnr;
            $creditAccount = Input::get('creditAccount');
            $debitAccount = Input::get('debitAccount');
            $palletsNumber = Input::get('palletsNumber');
            $actualTheoricalCreditPalletsNumber = Palletsaccount::where('name', $creditAccount)->value('theoricalNumberPallets');
            $actualTheoricalDebitPalletsNumber = Palletsaccount::where('name', $debitAccount)->value('theoricalNumberPallets');

            if ($multiTransfer == 'true') {
                $multiTransfer = true;
            } elseif ($multiTransfer == 'false') {
                $multiTransfer = false;
            }
            if (isset($date) && isset($loading_atrnr)) {
                Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccount, 'debitAccount' => $debitAccount, 'palletsNumber' => $palletsNumber, 'multiTransfer' => $multiTransfer, 'loading_atrnr' => $loading_atrnr]);
            } elseif (isset($date)) {
                Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccount, 'debitAccount' => $debitAccount, 'palletsNumber' => $palletsNumber, 'multiTransfer' => $multiTransfer]);
            } elseif (isset($loading_atrnr)) {
                Palletstransfer::create(['loading_atrnr' => $loading_atrnr, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccount, 'debitAccount' => $debitAccount, 'palletsNumber' => $palletsNumber, 'multiTransfer' => $multiTransfer]);
            } else {
                Palletstransfer::create(['type' => $type, 'details' => $details, 'creditAccount' => $creditAccount, 'debitAccount' => $debitAccount, 'palletsNumber' => $palletsNumber, 'multiTransfer' => $multiTransfer]);
            }
            Palletsaccount::where('name', $creditAccount)->update(['theoricalNumberPallets' => $actualTheoricalCreditPalletsNumber + $palletsNumber]);
            Palletsaccount::where('name', $debitAccount)->update(['theoricalNumberPallets' => $actualTheoricalDebitPalletsNumber - $palletsNumber]);
            session()->flash('messageAddPalletstransfer', 'Successfully added new pallets transfer');
            session()->flash('openPanelPallets', 'openPanelPallets');
            return redirect()->back();
        } elseif (isset($closeSubmitAddModal)) {
            session()->flash('openPanelPallets', 'openPanelPallets');
            return redirect()->back();
        } elseif (isset($uploadDocument)) {
            $transfer = Palletstransfer::where('id', $uploadDocument)->first();
            $documents  = $request->file('documentsTransfer' . $uploadDocument);
            $state = $transfer->state;
            $validate = $transfer->validate;
            $type = $transfer->type;
            $creditAccount = $transfer->creditAccount;
            $debitAccount = $transfer->debitAccount;
            $palletsNumber = $transfer->palletsNumber;

            $filesNames = $this->upload($documents, $transfer->id);

            if (isset($creditAccount) && isset($debitAccount) && isset($palletsNumber) && isset($type) && (isset($documents) || !empty($filesNames)) && ($validate <> null && $validate == 1)) {
                $state = 'Complete Validated';
            } elseif (isset($creditAccount) && isset($debitAccount) && isset($palletsNumber) && isset($type) && (isset($documents) || !empty($filesNames)) && ($validate <> null && $validate == 0)) {
                $state = 'Complete';
            } elseif (!isset($documents) && empty($filesNames)) {
                $state = 'Waiting documents';
            } elseif (isset($creditAccount) || isset($debitAccount) || isset($palletsNumber) || isset($type) || (isset($documents) || !empty($filesNames))) {
                $state = 'In progress';
            }
            Palletstransfer::where('id', $transfer->id)->update(['state' => $state]);
            session()->flash('openPanelPallets', 'openPanelPallets');
            return redirect()->back();
        } elseif (isset($delete)) {
            $transfer = Palletstransfer::where('id', $delete)->first();
            foreach (Palletsaccount::get() as $account) {
                $listNamesPalletsaccounts[] = $account->name;
            }
            $listTypes = ['Deposit', 'Withdrawal', 'Purchase', 'Sale', 'Other'];
            foreach (Loading::get()->where('pt', 'JA') as $loading) {
                $listAtrnr[] = $loading->atrnr;
            }
            $filesNames = $this->actualDocuments($transfer->id);
            return view('palletstransfers.detailsPalletstransfer', compact('transfer', 'listAtrnr', 'listTypes', 'listNamesPalletsaccounts', 'filesNames', 'delete'));
        } elseif (isset($deleteDocument)) {
            $this->deleteDocument(Palletstransfer::where('id', trim(explode('-', $deleteDocument)[1]))->first(), trim(explode('-', $deleteDocument)[0]));
            session()->flash('openPanelPallets', 'openPanelPallets');
            return redirect()->back();
        }elseif(isset($submitPallets)){
            $transfer = Palletstransfer::where('id', $submitPallets)->first();
            $loading_atrnr = $atrnr;
            $palletsNumber=Input::get('palletsNumber'.$submitPallets);
            $type=Input::get('type'.$submitPallets);
            $details=Input::get('details'.$submitPallets);
            $date=Input::get('date'.$submitPallets);
            $multiTransfer=Input::get('multiTransfer'.$submitPallets);
            $creditAccount=Input::get('creditAccount'.$submitPallets);
            $debitAccount=Input::get('debitAccount'.$submitPallets);
            $validate=Input::get('validate'.$submitPallets);

            if ($transfer->state == 'Complete Validated') {
                $this->inverseRealPalletsNumber($transfer);
            }
            $filesNames= $this->actualDocuments($transfer->id);
            session()->put('actualCreditAccount', $transfer->creditAccount);
            session()->put('actualDebitAccount', $transfer->debitAccount);
            session()->put('actualPalletsNumber', $transfer->palletsNumber);
            session()->put('actualType', $transfer->type);
            session()->put('actualDetails', $transfer->details);
            session()->put('actualDate', $transfer->date);
            session()->put('actualValidate', $transfer->validate);
            session()->put('actualMultiTransfer', $transfer->multiTransfer);

            session()->flash('palletsNumber', $palletsNumber);
            session()->flash('creditAccount', $creditAccount);
            session()->flash('debitAccount', $debitAccount);
            session()->flash('thPalletsNumberCreditAccount', Palletsaccount::where('name', $creditAccount)->first()->theoricalNumberPallets);
            session()->flash('thPalletsNumberDebitAccount', Palletsaccount::where('name', $debitAccount)->first()->theoricalNumberPallets);

            Palletstransfer::where('id', $transfer->id)->update(['type'=>$type, 'details'=>$details,'loading_atrnr'=>$loading_atrnr,'palletsNumber'=>$palletsNumber,'date'=>$date,  'creditAccount'=>$creditAccount, 'debitAccount'=>$debitAccount]);
            if($validate<>null && $validate=='true'){
                Palletstransfer::where('id', $transfer->id)->update(['validate'=>true]);
            }elseif($validate<>null&&$validate=='false'){
                Palletstransfer::where('id', $transfer->id)->update(['validate'=>false]);
            }
            if($multiTransfer<>null && $multiTransfer=='true'){
                Palletstransfer::where('id', $transfer->id)->update(['multiTransfer'=>true]);
            }elseif($multiTransfer<>null&&$multiTransfer=='false'){
                Palletstransfer::where('id', $transfer->id)->update(['multiTransfer'=>false]);
            }
            $transfer=Palletstransfer::where('id', $transfer->id)->first();
            session()->flash('openPanelPallets', 'openPanelPallets');
            return view('loadings.detailsLoading', compact('loading', 'listPalletsAccounts', 'listPalletstransfers',
                 'listPalletsaccountsCarrier', 'listTypes', 'transfer', 'submitPallets', 'filesNames'));
        }elseif(isset($okSubmitPalletsModal)){
            $transfer=Palletstransfer::where('id', $okSubmitPalletsModal)->first();
            $filesNames=$this->actualDocuments($transfer->id);
            $actualCreditAccount = session('actualCreditAccount');
            $actualDebitAccount = session('actualDebitAccount');
            $actualPalletsNumber = session('actualPalletsNumber');
            $this->updateInfo($transfer, $actualPalletsNumber, $actualCreditAccount, $actualDebitAccount, $filesNames);
            $transfer=Palletstransfer::where('id', $okSubmitPalletsModal)->first();
            session()->flash('openPanelPallets', 'openPanelPallets');
            if ($transfer->state == 'Complete Validated') {
                session()->flash('palletsNumber', $transfer->palletsNumber);
                session()->flash('creditAccount', $transfer->creditAccount);
                session()->flash('debitAccount', $transfer->debitAccount);
                session()->flash('realPalletsNumberCreditAccount', Palletsaccount::where('name', $transfer->creditAccount)->first()->realNumberPallets);
                session()->flash('realPalletsNumberDebitAccount', Palletsaccount::where('name', $transfer->debitAccount)->first()->realNumberPallets);
                return view('loadings.detailsLoading',compact( 'loading', 'listPalletsAccounts', 'listPalletstransfers',
                    'listPalletsaccountsCarrier', 'listTypes', 'transfer', 'okSubmitPalletsModal', 'filesNames'));
            } else {
                session()->pull('actualCreditAccount');
                session()->pull('actualDebitAccount');
                session()->pull('actualPalletsNumber');
                session()->pull('actualType');
                session()->pull('actualDetails');
                session()->pull('actualDate');
                session()->pull('actualMultiTransfer');
                session()->pull('actualValidate');
                return redirect()->back();
            }
        }elseif(isset($closeSubmitPalletsModal)){
            $actualCreditAccount = session('actualCreditAccount');
            $actualDebitAccount = session('actualDebitAccount');
            $actualPalletsNumber = session('actualPalletsNumber');
            $actualType = session('actualType');
            $actualDetails = session('actualDetails');
            $actualDate = session('actualDate');
            $actualMultiTransfer = session('actualMultiTransfer');
            $actualValidate = session('actualValidate');
            Palletstransfer::where('id', $closeSubmitPalletsModal)->update(['multiTransfer'=>$actualMultiTransfer,'validate'=>$actualValidate, 'type'=>$actualType, 'details'=>$actualDetails,'palletsNumber'=>$actualPalletsNumber,'date'=>$actualDate,  'creditAccount'=>$actualCreditAccount, 'debitAccount'=>$actualDebitAccount]);
            session()->pull('actualCreditAccount');
            session()->pull('actualDebitAccount');
            session()->pull('actualPalletsNumber');
            session()->pull('actualType');
            session()->pull('actualDetails');
            session()->pull('actualDate');
            session()->pull('actualMultiTransfer');
            session()->pull('actualValidate');
            session()->flash('openPanelPallets', 'openPanelPallets');
            return redirect()->back();
        }elseif(isset($okSubmitPalletsValidateModal)){
            $transfer=Palletstransfer::where('id', $okSubmitPalletsValidateModal)->first();
            $realPalletsNumberCreditAccount = Palletsaccount::where('name', $transfer->creditAccount)->first()->realNumberPallets;
            Palletsaccount::where('name', $transfer->creditAccount)->update(['realNumberPallets' => $realPalletsNumberCreditAccount + $transfer->palletsNumber]);
            $realPalletsNumberDebitAccount = Palletsaccount::where('name', $transfer->debitAccount)->first()->realNumberPallets;
            Palletsaccount::where('name', $transfer->debitAccount)->update(['realNumberPallets' => $realPalletsNumberDebitAccount - $transfer->palletsNumber]);
            session()->flash('messageUpdateValidatePalletstransfer', 'VALIDATE ! Successfully updated and validated pallets transfer');
            session()->pull('actualCreditAccount');
            session()->pull('actualDebitAccount');
            session()->pull('actualPalletsNumber');
            session()->pull('actualType');
            session()->pull('actualDetails');
            session()->pull('actualDate');
            session()->pull('actualMultiTransfer');
            session()->pull('actualValidate');
            session()->flash('openPanelPallets', 'openPanelPallets');
            return redirect()->back();
        }

    }

    public static function actualDocuments($id)
    {
        $actualDocuments_Palletstransfers = DB::table('document_palletstransfer')->where('palletstransfer_id', $id)->get();
        $filesNames = [];
        if (!$actualDocuments_Palletstransfers->isEmpty()) {
            foreach ($actualDocuments_Palletstransfers as $actualDoc) {
                $filesNames[] = Document::where('id', $actualDoc->document_id)->first()->name;
            }
        }
        return $filesNames;
    }

    public function upload($documents, $id)
    {
        $filesNames = $this->actualDocuments($id);
        if (isset($documents)) {
            foreach ($documents as $doc) {
                $filename = $doc->getClientOriginalName();
                $extension = $doc->getClientOriginalExtension();
                $size = $doc->getSize();
                //if file is an image, a pdf or an email
                if (($extension == 'png' || $extension == 'jpg' || $extension == 'JPG' || $extension == 'msg' || $extension == 'htm' || $extension == 'rtf' || $extension == 'pdf') && $size < 2000000) {
                    Storage::putFileAs('/proofsPallets/documentsTransfer/' . $id, $doc, $filename);
                    Document::firstOrCreate([
                        'name' => $filename,
                    ])->palletstransfers()->attach($id);
                } else {
                    session()->flash('messageErrorUpload', 'Error ! The file type is not supported (png, jgp, pdf, msg, htm, rtf only');
                }
            }
        }
        return $filesNames;
    }

    /**
     * delete a document attach to this transfer
     * @param $transfer
     * @param $name
     */
    public function deleteDocument($transfer, $name)
    {
        $doc = Document::where('name', $name)->first();
        $doc->palletstransfers()->detach($transfer->id);
        $path = '/proofsPallets/documentsTransfer/' . $transfer->id . '/';
        Storage::delete($path . $name);
        $actualTransferAssociated = DB::table('document_palletstransfer')->where('document_id', $doc->id)->get();
        if ($actualTransferAssociated->isEmpty()) {
            $doc->delete();
        }
        $actualDocuments_Palletstransfers = DB::table('document_palletstransfer')->where('palletstransfer_id', $transfer->id)->get();
        if ($actualDocuments_Palletstransfers->isEmpty()) {
            Palletstransfer::where('id', $transfer->id)->update(['validate' => false]);
            if ($transfer->state == 'Complete Validated') {
                $this->inverseRealPalletsNumber($transfer->creditAccount, $transfer->debitAccount, $transfer->palletsNumber);
            }
            Palletstransfer::where('id', $transfer->id)->update(['state' => 'Waiting documents']);
        }
    }


    public function inverseRealPalletsNumber($transfer)
    {
        $actualRealPalletsNumberCreditAccount = Palletsaccount::where('name', $transfer->creditAccount)->first()->realNumberPallets;
        Palletsaccount::where('name', $transfer->creditAccount)->update(['realNumberPallets' => $actualRealPalletsNumberCreditAccount -$transfer->palletsNumber]);
        $actualRealPalletsNumberDebitAccount = Palletsaccount::where('name', $transfer->debitAccount)->first()->realNumberPallets;
        Palletsaccount::where('name', $transfer->debitAccount)->update(['realNumberPallets' => $actualRealPalletsNumberDebitAccount + $transfer->palletsNumber]);
    }

    public function updateInfo($transfer, $actualPalletsNumber, $actualCreditAccount, $actualDebitAccount, $actualDoc)
    {
        //inverse transfer : we delete the last transfer
        $actualPalletsNumberCreditAccount = Palletsaccount::where('name', $actualCreditAccount)->first()->theoricalNumberPallets;
        Palletsaccount::where('name', $actualCreditAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberCreditAccount - $actualPalletsNumber]);
        $actualPalletsNumberDebitAccount = Palletsaccount::where('name', $actualDebitAccount)->first()->theoricalNumberPallets;
        Palletsaccount::where('name', $actualDebitAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberDebitAccount + $actualPalletsNumber]);

        //we do the new transfer
        $palletsNumberCreditAccount = Palletsaccount::where('name', $transfer->creditAccount)->first()->theoricalNumberPallets;
        Palletsaccount::where('name', $transfer->creditAccount)->update(['theoricalNumberPallets' => $palletsNumberCreditAccount + $transfer->palletsNumber]);
        $palletsNumberDebitAccount = Palletsaccount::where('name', $transfer->debitAccount)->first()->theoricalNumberPallets;
        Palletsaccount::where('name', $transfer->debitAccount)->update(['theoricalNumberPallets' => $palletsNumberDebitAccount - $transfer->palletsNumber]);

        if (isset($transfer->creditAccount) && isset($transfer->debitAccount) && isset($transfer->palletsNumber) && isset($transfer->type) && !empty($filesNames) && ($transfer->validate <> null && $transfer->validate == 1)) {
            $state = 'Complete Validated';
        } elseif (isset($transfer->creditAccount) && isset($transfer->debitAccount) && isset($transfer->palletsNumber) && isset($transfer->type) &&  !empty($filesNames) && ($transfer->validate <> null && $transfer->validate == 0)) {
            $state = 'Complete';
        } elseif (empty($filesNames)) {
            $state = 'Waiting documents';
        } elseif (isset($transfer->creditAccount) || isset($transfer->debitAccount) || isset($transfer->palletsNumber) || isset($transfer->type) || !empty($filesNames)) {
            $state = 'In progress';
        }
        Palletstransfer::where('id', $transfer->id)->update(['state' => $state]);

        session()->flash('messageSubmitPalletstransfer', 'Successfully updated and pallets transfer');
    }





    public function submitUpdateUd($atrnr, Request $request)
    {
        $loading = Loading::where('atrnr', $atrnr)->first();

        //buttons
        $update = Input::get('update');
        $uploadLoading = Input::get('uploadLoading');
        $submitLoading = Input::get('submitLoading');
        $addLoadingPlace = Input::get('addLoadingPlace');
        $deleteLoadingPlace = Input::get('deleteLoadingPlace');
        $okSubmitLoadingModal = Input::get('okSubmitLoadingModal');
        $okSubmitValidateLoadingModal = Input::get('okSubmitValidateLoadingModal');
        $closeSubmitLoadingModal = Input::get('closeSubmitLoadingModal');

        $uploadOffloading = Input::get('uploadOffloading');
        $submitOffloading = Input::get('submitOffloading');
        $deleteOffloadingPlace = Input::get('deleteOffloadingPlace');
        $addOffloadingPlace = Input::get('addOffloadingPlace');
        $okSubmitOffloadingModal = Input::get('okSubmitOffloadingModal');
        $okSubmitValidateOffloadingModal = Input::get('okSubmitValidateOffloadingModal');
        $closeSubmitOffloadingModal = Input::get('closeSubmitOffloadingModal');

        $uploadTruck = Input::get('uploadTruck');
        $submitTruck = Input::get('submitTruck');

        $deleteDocument = Input::get('deleteDocument');

        //data
        $state = $loading->state;

        $listPalletsAccounts = DB::table('palletsaccounts')->get();

        $listPalletsAccountsCarrier = Palletsaccount::where('type', 'Carrier')->get();
        if ($loading->kennzeichen == "") {
            $licensePlate = 'OTHER';
        } else {
            $licensePlate = $loading->kennzeichen;
        }
        if (Truck::where('name', trim(explode(',', $loading->subfrachter)[0]))->where('licensePlate', $licensePlate)->first() <> null) {
            $namePalletsAccountTruck = Truck::where('name', trim(explode(',', $loading->subfrachter)[0]))->where('licensePlate', $licensePlate)->first()->palletsaccount_name;
            if ($namePalletsAccountTruck <> null) {
                $palletsAccountFavoriteTruck = Palletsaccount::where('name', $namePalletsAccountTruck)->first()->name;
            }
        }

        $totalPalletsLoadingPlace = 0;
        for ($k = 1; $k <= $loading->numberLoadingPlace; $k++) {
            $numberPalletsLoadingPlaceK = 'numberPalletsLoadingPlace' . $k;
            $$numberPalletsLoadingPlaceK = $loading->$numberPalletsLoadingPlaceK;
            $totalPalletsLoadingPlace = $totalPalletsLoadingPlace + $$numberPalletsLoadingPlaceK;
        }
        if (Warehouse::where('zipcode', $loading->plzb)->first() <> null) {
            $idWarehouseZipcodeLoadingPlace = Warehouse::where('zipcode', $loading->plzb)->first()->id;
            $accountZipcodeLoadingPlace1 = DB::table('palletsaccount_warehouse')->where('warehouse_id', $idWarehouseZipcodeLoadingPlace)->first();
            if ($accountZipcodeLoadingPlace1 <> null) {
                $accountZipcodeLoadingPlace = Palletsaccount::where('id', $accountZipcodeLoadingPlace1->palletsaccount_id)->first()->name;

            }
        };

        //offloading panel
        $totalPalletsOffloadingPlace = 0;
        for ($k = 1; $k <= $loading->numberLoadingPlace; $k++) {
            $numberPalletsOffloadingPlaceK = 'numberPalletsOffloadingPlace' . $k;
            $$numberPalletsOffloadingPlaceK = $loading->$numberPalletsOffloadingPlaceK;
            $totalPalletsOffloadingPlace = $totalPalletsOffloadingPlace + $$numberPalletsOffloadingPlaceK;
        }
        if (Warehouse::where('zipcode', $loading->plze)->first() <> null) {
            $idWarehouseZipcodeOffloadingPlace = Warehouse::where('zipcode', $loading->plze)->first()->id;
            $accountZipcodeOffloadingPlace1 = DB::table('palletsaccount_warehouse')->where('warehouse_id', $idWarehouseZipcodeOffloadingPlace)->first();
            if ($accountZipcodeOffloadingPlace1 <> null) {
                $accountZipcodeOffloadingPlace = Palletsaccount::where('id', $accountZipcodeOffloadingPlace1->palletsaccount_id)->first()->name;
            }
        };

//documents
        $documentsLoading = $request->file('documentsLoading');
        $documentsOffloading = $request->file('documentsOffloading');
        $documentsTruck = $request->file('documentsTruck');

        ///////TRUCK/////

        if (isset($update)) {
            $this->update($request, $loading->atrnr);
        } elseif (isset($deleteDocument)) {
            $typePlace = trim(explode('-', $deleteDocument)[1]);
            $name = trim(explode('-', $deleteDocument)[0]);
            $this->deleteDocument($loading, $typePlace, $name);
            session()->flash('openPanel' . $typePlace, 'openPanel');
            return redirect()->back();
        } elseif (isset($addLoadingPlace)) {
            //////LOADING PLACES//////
            $this->addPlace($loading->atrnr, 'LoadingPlace', $loading->numberLoadingPlace);
            session()->flash('openPanelLoading', 'openPanelLoading');
            return redirect()->back();
        } elseif (isset($deleteLoadingPlace)) {
            $this->deletePlace($loading, 'LoadingPlace', $loading->numberLoadingPlace);
            session()->flash('openPanelLoading', 'openPanelLoading');
            return redirect()->back();
        } elseif (isset($submitLoading)) {
            $k = $submitLoading;
            $listDataK = $this->getDataLoadingPlace($loading, $k);

            if ($listDataK[1][3] == 'Complete Validated') {
                //data in memory
                $this->inverseRealPalletsNumber($loading->$listDataK[0][1], $loading->$listDataK[0][2], $loading->$listDataK[0][0]);
            }
            $filesNamesLoadingPlace = $this->actualDocuments($atrnr, 'Loading');

            if (isset($listDataK[0][0]) && isset($listDataK[0][1]) && isset($listDataK[0][2])) {

                session()->flash('palletsNumber', $listDataK[1][0]);
                session()->flash('creditAccount', $listDataK[1][1]);
                session()->flash('debitAccount', $listDataK[1][2]);
                if (!isset($loading->$listDataK[0][1]) && !isset($loading->$listDataK[0][2]) && !isset($loading->$listDataK[0][0])) {
                    //1stTime
                    session()->put('actualCreditAccount', null);
                    session()->put('actualDebitAccount', null);
                    session()->put('actualPalletsNumber', null);
                } else {
                    session()->put('actualCreditAccount', $loading->$listDataK[0][1]);
                    session()->put('actualDebitAccount', $loading->$listDataK[0][2]);
                    session()->put('actualPalletsNumber', $loading->$listDataK[0][0]);
                }
                Loading::where('atrnr', $loading->atrnr)->update(['numberPalletsLoadingPlace' . $k => $listDataK[1][0]]);
                Loading::where('atrnr', $loading->atrnr)->update(['accountCreditLoadingPlace' . $k => $listDataK[1][1]]);
                Loading::where('atrnr', $loading->atrnr)->update(['accountDebitLoadingPlace' . $k => $listDataK[1][2]]);
                if ($listDataK[1][4] == 'true') {
                    Loading::where('atrnr', $loading->atrnr)->update(['validateLoadingPlace' . $k => true]);
                } else {
                    Loading::where('atrnr', $loading->atrnr)->update(['validateLoadingPlace' . $k => false]);
                }
                $loading = Loading::where('atrnr', $atrnr)->first();

                session()->flash('thPalletsNumberCreditAccount', Palletsaccount::where('name', $listDataK[1][1])->first()->theoricalNumberPallets);
                session()->flash('thPalletsNumberDebitAccount', Palletsaccount::where('name', $listDataK[1][2])->first()->theoricalNumberPallets);
                session()->flash('openPanelLoading', 'openPanelLoading');

                return view('loadings.detailsLoading', compact('loading', 'listPalletsAccounts',
                    'filesNamesTruck', 'palletsAccountFavoriteTruck', 'listPalletsAccountsCarrier',
                    'filesNamesLoadingPlace', 'accountZipcodeLoadingPlace', 'totalPalletsLoadingPlace',
                    'filesNamesOffloadingPlace', 'accountZipcodeOffloadingPlace', 'totalPalletsOffloadingPlace', 'submitLoading'
                ));
            } else {
                if (isset($listDataK[0][0])) {
                    Loading::where('atrnr', $loading->atrnr)->update(['numberPalletsLoadingPlace' . $k => $listDataK[1][0]]);
                }
                if (isset($listDataK[0][1])) {
                    Loading::where('atrnr', $loading->atrnr)->update(['accountCreditLoadingPlace' . $k => $listDataK[1][1]]);
                }
                if (isset($listDataK[0][2])) {
                    Loading::where('atrnr', $loading->atrnr)->update(['accountDebitLoadingPlace' . $k => $listDataK[1][2]]);
                }
                session()->flash('openPanelLoading', 'openPanelLoading');
                return redirect()->back();
            }
        } elseif (isset($okSubmitLoadingModal)) {
            $filesNamesLoadingPlace = $this->actualDocuments($loading->atrnr, 'Loading');
            $k = $okSubmitLoadingModal;
            $listDataK = $this->getDataLoadingPlace($loading, $k);
            $actualCreditAccount = session('actualCreditAccount');
            $actualDebitAccount = session('actualDebitAccount');
            $actualPalletsNumber = session('actualPalletsNumber');
            if (!isset($actualCreditAccount) && !isset($actualDebitAccount) && !isset($actualPalletsNumber)) {
                //1st time
                $this->update1stTime($listDataK, $loading, $k, 'LoadingPlace');
            } else {
                $this->updateMoreTimes($listDataK, $loading, $documentsLoading, $filesNamesLoadingPlace, $actualCreditAccount, $actualDebitAccount, $actualPalletsNumber, $k, 'LoadingPlace');
            }
            session()->pull('actualCreditAccount');
            session()->pull('actualDebitAccount');
            session()->pull('actualPalletsNumber');
            $totalPalletsLoadingPlace = 0;
            for ($k = 1; $k <= $loading->numberLoadingPlace; $k++) {
                $numberPalletsLoadingPlaceK = 'numberPalletsLoadingPlace' . $k;
                $$numberPalletsLoadingPlaceK = $loading->$numberPalletsLoadingPlaceK;
                $totalPalletsLoadingPlace = $totalPalletsLoadingPlace + $$numberPalletsLoadingPlaceK;
            }
            session()->flash('messageSuccessSubmit', 'Successfully submited pallets transfer for this loading. Planned pallets number on both account has been updated');

            $loading = Loading::where('atrnr', $atrnr)->first();
            $$listDataK[0][3] = $loading->$listDataK[0][3];
            if ($$listDataK[0][3] == 'Complete Validated') {
                session()->flash('palletsNumber', $listDataK[1][0]);
                session()->flash('creditAccount', $listDataK[1][1]);
                session()->flash('debitAccount', $listDataK[1][2]);
                session()->flash('realPalletsNumberCreditAccount', Palletsaccount::where('name', $listDataK[1][1])->first()->realNumberPallets);
                session()->flash('realPalletsNumberDebitAccount', Palletsaccount::where('name', $listDataK[1][2])->first()->realNumberPallets);
                return view('loadings.detailsLoading', compact('loading', 'listPalletsAccounts',
                    'filesNamesTruck', 'palletsAccountFavoriteTruck', 'listPalletsAccountsCarrier',
                    'filesNamesLoadingPlace', 'accountZipcodeLoadingPlace', 'totalPalletsLoadingPlace',
                    'filesNamesOffloadingPlace', 'accountZipcodeOffloadingPlace', 'totalPalletsOffloadingPlace', 'okSubmitLoadingModal'));
            } else {
                session()->flash('openPanelLoading', 'openPanelLoading');
                return redirect()->back();
            }
        } elseif (isset($closeSubmitLoadingModal)) {
            session()->flash('openPanelLoading', 'openPanelLoading');
            return redirect()->back();
        } elseif (isset($okSubmitValidateLoadingModal)) {
            $k = $okSubmitValidateLoadingModal;
            $listDataK = $this->getDataLoadingPlace($loading, $k);
            $realPalletsNumberCreditAccount = Palletsaccount::where('name', $listDataK[1][1])->first()->realNumberPallets;
            Palletsaccount::where('name', $listDataK[1][1])->update(['realNumberPallets' => $realPalletsNumberCreditAccount + $listDataK[1][0]]);
            $realPalletsNumberDebitAccount = Palletsaccount::where('name', $listDataK[1][2])->first()->realNumberPallets;
            Palletsaccount::where('name', $listDataK[1][2])->update(['realNumberPallets' => $realPalletsNumberDebitAccount - $listDataK[1][0]]);
            session()->flash('messageUpdateValidate', 'VALIDATE ! Successfully updated and validated pallets transfer for the place ' . $k . '. Confirmed pallets number on both account has been updated');
            session()->flash('openPanelLoading', 'openPanelLoading');
            return redirect()->back();
        } elseif (isset($uploadLoading)) {
            $filesNamesLoadingPlace = $this->uploadDocuments($loading->atrnr, $documentsLoading, 'Loading');

            for ($k = 1; $k <= $loading->numberLoadingPlace; $k++) {
                $listDataK = $this->getDataLoadingPlace($loading, $k);

                if (isset($listDataK[0][0]) && isset($listDataK[0][1]) && isset($listDataK[0][2]) && (isset($documentsLoading) || !empty($filesNamesLoadingPlace)) && (($listDataK[1][4] <> null && $listDataK[1][4] == 'true') || ($listDataK[1][4] == null && $loading->$listDataK[0][4] == 1))) {
                    $listDataK[1][3] = 'Complete Validated';
                } elseif (isset($listDataK[0][0]) && isset($listDataK[0][1]) && isset($listDataK[0][2]) && (isset($documentsLoading) || !empty($filesNamesLoadingPlace))) {
                    $listDataK[1][3] = 'Complete';
                } elseif (!isset($documentsLoading) || empty($filesNamesLoadingPlace)) {
                    $listDataK[1][3] = 'Waiting documents';
                } elseif (isset($listDataK[0][0]) || isset($listDataK[0][1]) || isset($listDataK[0][2]) || (isset($documentsLoading) || !empty($filesNamesLoadingPlace))) {
                    $listDataK[1][3] = 'In progress';
                } else {
                    $listDataK[1][3] = 'Untreated';
                }
                Loading::where('atrnr', $loading->atrnr)->update(['stateLoadingPlace' . $k => $listDataK[1][3]]);
            }
            $loading = Loading::where('atrnr', $loading->atrnr)->first();
            $this->state($loading);
            session()->flash('openPanelLoading', 'openPanelLoading');
            return redirect()->back();
        } elseif (isset($addOffloadingPlace)) {
            //////OFFLOADING PLACES//////
            $this->addPlace($loading->atrnr, 'OffloadingPlace', $loading->numberOffloadingPlace);
            session()->flash('openPanelOffloading', 'openPanelOffloading');
            return redirect()->back();
        } elseif (isset($deleteOffloadingPlace)) {
            $this->deletePlace($loading, 'OffloadingPlace', $loading->numberOffloadingPlace);
            session()->flash('openPanelOffloading', 'openPanelOffloading');
            return redirect()->back();
        } elseif (isset($submitOffloading)) {
            $k = $submitOffloading;
            $listDataK = $this->getDataOffloadingPlace($loading, $k);

            if ($listDataK[1][3] == 'Complete Validated') {
                //data in memory
                $this->inverseRealPalletsNumber($loading->$listDataK[0][1], $loading->$listDataK[0][2], $loading->$listDataK[0][0]);
            }
            $filesNamesOffloadingPlace = $this->actualDocuments($atrnr, 'Offloading');

            if (isset($listDataK[0][0]) && isset($listDataK[0][1]) && isset($listDataK[0][2])) {

                session()->flash('palletsNumber', $listDataK[1][0]);
                session()->flash('creditAccount', $listDataK[1][1]);
                session()->flash('debitAccount', $listDataK[1][2]);
                if (!isset($loading->$listDataK[0][1]) && !isset($loading->$listDataK[0][2]) && !isset($loading->$listDataK[0][0])) {
                    //1stTime
                    session()->put('actualCreditAccount', null);
                    session()->put('actualDebitAccount', null);
                    session()->put('actualPalletsNumber', null);
                } else {
                    session()->put('actualCreditAccount', $loading->$listDataK[0][1]);
                    session()->put('actualDebitAccount', $loading->$listDataK[0][2]);
                    session()->put('actualPalletsNumber', $loading->$listDataK[0][0]);
                }
                Loading::where('atrnr', $loading->atrnr)->update(['numberPalletsOffloadingPlace' . $k => $listDataK[1][0]]);
                Loading::where('atrnr', $loading->atrnr)->update(['accountCreditOffloadingPlace' . $k => $listDataK[1][1]]);
                Loading::where('atrnr', $loading->atrnr)->update(['accountDebitOffloadingPlace' . $k => $listDataK[1][2]]);
                if ($listDataK[1][4] == 'true') {
                    Loading::where('atrnr', $loading->atrnr)->update(['validateOffloadingPlace' . $k => true]);
                } else {
                    Loading::where('atrnr', $loading->atrnr)->update(['validateOffloadingPlace' . $k => false]);
                }
                $loading = Loading::where('atrnr', $atrnr)->first();

                session()->flash('thPalletsNumberCreditAccount', Palletsaccount::where('name', $listDataK[1][1])->first()->theoricalNumberPallets);
                session()->flash('thPalletsNumberDebitAccount', Palletsaccount::where('name', $listDataK[1][2])->first()->theoricalNumberPallets);
                session()->flash('openPanelOffloading', 'openPanelOffloading');

                return view('loadings.detailsLoading', compact('loading', 'listPalletsAccounts',
                    'filesNamesTruck', 'palletsAccountFavoriteTruck', 'listPalletsAccountsCarrier',
                    'filesNamesLoadingPlace', 'accountZipcodeLoadingPlace', 'totalPalletsLoadingPlace',
                    'filesNamesOffloadingPlace', 'accountZipcodeOffloadingPlace', 'totalPalletsOffloadingPlace', 'submitOffloading'
                ));
            } else {
                if (isset($listDataK[0][0])) {
                    Loading::where('atrnr', $loading->atrnr)->update(['numberPalletsOffloadingPlace' . $k => $listDataK[1][0]]);
                }
                if (isset($listDataK[0][1])) {
                    Loading::where('atrnr', $loading->atrnr)->update(['accountCreditOffloadingPlace' . $k => $listDataK[1][1]]);
                }
                if (isset($listDataK[0][2])) {
                    Loading::where('atrnr', $loading->atrnr)->update(['accountDebitOffloadingPlace' . $k => $listDataK[1][2]]);
                }
                session()->flash('openPanelOffloading', 'openPanelOffloading');
                return redirect()->back();
            }

        } elseif (isset($okSubmitOffloadingModal)) {
            $filesNamesOffloadingPlace = $this->actualDocuments($loading->atrnr, 'Offloading');
            $k = $okSubmitOffloadingModal;
            $listDataK = $this->getDataOffloadingPlace($loading, $k);
            $actualCreditAccount = session('actualCreditAccount');
            $actualDebitAccount = session('actualDebitAccount');
            $actualPalletsNumber = session('actualPalletsNumber');
            if (!isset($actualCreditAccount) && !isset($actualDebitAccount) && !isset($actualPalletsNumber)) {
                //1st time
                $this->update1stTime($listDataK, $loading, $k, 'OffloadingPlace');
            } else {
                $this->updateMoreTimes($listDataK, $loading, $documentsOffloading, $filesNamesOffloadingPlace, $actualCreditAccount, $actualDebitAccount, $actualPalletsNumber, $k, 'OffloadingPlace');
            }
            session()->pull('actualCreditAccount');
            session()->pull('actualDebitAccount');
            session()->pull('actualPalletsNumber');
            $totalPalletsOffloadingPlace = 0;
            for ($k = 1; $k <= $loading->numberOffloadingPlace; $k++) {
                $numberPalletsOffloadingPlaceK = 'numberPalletsOffloadingPlace' . $k;
                $$numberPalletsOffloadingPlaceK = $loading->$numberPalletsOffloadingPlaceK;
                $totalPalletsOffloadingPlace = $totalPalletsOffloadingPlace + $$numberPalletsOffloadingPlaceK;
            }
            session()->flash('messageSuccessSubmit', 'Successfully submited pallets transfer for this loading. Planned pallets number on both account has been updated');

            $loading = Loading::where('atrnr', $atrnr)->first();
            $$listDataK[0][3] = $loading->$listDataK[0][3];
            if ($$listDataK[0][3] == 'Complete Validated') {
                session()->flash('palletsNumber', $listDataK[1][0]);
                session()->flash('creditAccount', $listDataK[1][1]);
                session()->flash('debitAccount', $listDataK[1][2]);
                session()->flash('realPalletsNumberCreditAccount', Palletsaccount::where('name', $listDataK[1][1])->first()->realNumberPallets);
                session()->flash('realPalletsNumberDebitAccount', Palletsaccount::where('name', $listDataK[1][2])->first()->realNumberPallets);
                return view('loadings.detailsLoading', compact('loading', 'listPalletsAccounts',
                    'filesNamesTruck', 'palletsAccountFavoriteTruck', 'listPalletsAccountsCarrier',
                    'filesNamesLoadingPlace', 'accountZipcodeLoadingPlace', 'totalPalletsLoadingPlace',
                    'filesNamesOffloadingPlace', 'accountZipcodeOffloadingPlace', 'totalPalletsOffloadingPlace', 'okSubmitOffloadingModal'));
            } else {
                session()->flash('openPanelOffloading', 'openPanelOffloading');
                return redirect()->back();
            }
        } elseif (isset($closeSubmitOffloadingModal)) {
            session()->flash('openPanelOffloading', 'openPanelOffloading');
            return redirect()->back();
        } elseif (isset($okSubmitValidateOffloadingModal)) {
            $k = $okSubmitValidateOffloadingModal;
            $listDataK = $this->getDataOffloadingPlace($loading, $k);
            $realPalletsNumberCreditAccount = Palletsaccount::where('name', $listDataK[1][1])->first()->realNumberPallets;
            Palletsaccount::where('name', $listDataK[1][1])->update(['realNumberPallets' => $realPalletsNumberCreditAccount + $listDataK[1][0]]);
            $realPalletsNumberDebitAccount = Palletsaccount::where('name', $listDataK[1][2])->first()->realNumberPallets;
            Palletsaccount::where('name', $listDataK[1][2])->update(['realNumberPallets' => $realPalletsNumberDebitAccount - $listDataK[1][0]]);
            session()->flash('messageUpdateValidate', 'VALIDATE ! Successfully updated and validated pallets transfer for the place ' . $k . '. Confirmed pallets number on both account has been updated');
            session()->flash('openPanelOffloading', 'openPanelOffloading');
            return redirect()->back();
        } elseif (isset($uploadOffloading)) {
            $filesNamesOffloadingPlace = $this->uploadDocuments($loading->atrnr, $documentsOffloading, 'Offloading');

            for ($k = 1; $k <= $loading->numberOffloadingPlace; $k++) {
                $listDataK = $this->getDataOffloadingPlace($loading, $k);
                if (isset($listDataK[0][0]) && isset($listDataK[0][1]) && isset($listDataK[0][2]) && (isset($documentsOffloading) || !empty($filesNamesOffloadingPlace)) && (($listDataK[1][4] <> null && $listDataK[1][4] == 'true') || ($listDataK[1][4] == null && $loading->$listDataK[0][4] == 1))) {
                    $listDataK[1][3] = 'Complete Validated';
                } elseif (isset($listDataK[0][0]) && isset($listDataK[0][1]) && isset($listDataK[0][2]) && (isset($documentsOffloading) || !empty($filesNamesOffloadingPlace))) {
                    $listDataK[1][3] = 'Complete';
                } elseif (!isset($documentsOffloading) || empty($filesNamesOffloadingPlace)) {
                    $listDataK[1][3] = 'Waiting documents';
                } elseif (isset($listDataK[0][0]) || isset($listDataK[0][1]) || isset($listDataK[0][2]) || (isset($documentsOffloading) || !empty($filesNamesOffloadingPlace))) {
                    $listDataK[1][3] = 'In progress';
                } else {
                    $listDataK[1][3] = 'Untreated';
                }
                Loading::where('atrnr', $loading->atrnr)->update(['stateOffloadingPlace' . $k => $listDataK[1][3]]);
            }
            $loading = Loading::where('atrnr', $loading->atrnr)->first();
            $this->state($loading);
            session()->flash('openPanelOffloading', 'openPanelOffloading');
            return redirect()->back();
        } elseif (isset($deleteDocument)) {
            $typePlace = trim(explode('-', $deleteDocument)[1]);
            $name = trim(explode('-', $deleteDocument)[0]);
            $this->deleteDocument($loading, $typePlace, $name);
            session()->flash('openPanel' . $typePlace, 'openPanel');
            return redirect()->back();
        }
    }

    public function getDataLoadingPlace($loading, $k)
    {
        $numberPalletsLoadingPlaceK = 'numberPalletsLoadingPlace' . $k;
        $listTitleVariable[] = $numberPalletsLoadingPlaceK;
        $$numberPalletsLoadingPlaceK = Input::get('numberPalletsLoadingPlace' . $k);
        $listContentVariable[] = $$numberPalletsLoadingPlaceK;

        $accountCreditLoadingPlaceK = 'accountCreditLoadingPlace' . $k;
        $listTitleVariable[] = $accountCreditLoadingPlaceK;
        $$accountCreditLoadingPlaceK = Input::get('accountCreditLoadingPlace' . $k);
        $listContentVariable[] = $$accountCreditLoadingPlaceK;

        $accountDebitLoadingPlaceK = 'accountDebitLoadingPlace' . $k;
        $listTitleVariable[] = $accountDebitLoadingPlaceK;
        $$accountDebitLoadingPlaceK = Input::get('accountDebitLoadingPlace' . $k);
        $listContentVariable[] = $$accountDebitLoadingPlaceK;

        $stateLoadingPlaceK = 'stateLoadingPlace' . $k;
        $listTitleVariable[] = $stateLoadingPlaceK;
        $$stateLoadingPlaceK = Input::get('stateLoadingPlace' . $k);
        $listContentVariable[] = $$stateLoadingPlaceK;

        $validateLoadingPlaceK = 'validateLoadingPlace' . $k;
        $listTitleVariable[] = $validateLoadingPlaceK;
        $$validateLoadingPlaceK = Input::get('validateLoadingPlace' . $k);
        $listContentVariable[] = $$validateLoadingPlaceK;

        $listData = [$listTitleVariable, $listContentVariable];
        return $listData;
    }

    public function getDataOffloadingPlace($loading, $k)
    {
        $numberPalletsOffloadingPlaceK = 'numberPalletsOffloadingPlace' . $k;
        $listTitleVariable[] = $numberPalletsOffloadingPlaceK;
        $$numberPalletsOffloadingPlaceK = Input::get('numberPalletsOffloadingPlace' . $k);
        $listContentVariable[] = $$numberPalletsOffloadingPlaceK;

        $accountCreditOffloadingPlaceK = 'accountCreditOffloadingPlace' . $k;
        $listTitleVariable[] = $accountCreditOffloadingPlaceK;
        $$accountCreditOffloadingPlaceK = Input::get('accountCreditOffloadingPlace' . $k);
        $listContentVariable[] = $$accountCreditOffloadingPlaceK;

        $accountDebitOffloadingPlaceK = 'accountDebitOffloadingPlace' . $k;
        $listTitleVariable[] = $accountDebitOffloadingPlaceK;
        $$accountDebitOffloadingPlaceK = Input::get('accountDebitOffloadingPlace' . $k);
        $listContentVariable[] = $$accountDebitOffloadingPlaceK;

        $stateOffloadingPlaceK = 'stateOffloadingPlace' . $k;
        $listTitleVariable[] = $stateOffloadingPlaceK;
        $$stateOffloadingPlaceK = Input::get('stateOffloadingPlace' . $k);
        $listContentVariable[] = $$stateOffloadingPlaceK;

        $validateOffloadingPlaceK = 'validateOffloadingPlace' . $k;
        $listTitleVariable[] = $validateOffloadingPlaceK;
        $$validateOffloadingPlaceK = Input::get('validateOffloadingPlace' . $k);
        $listContentVariable[] = $$validateOffloadingPlaceK;

        $listData = [$listTitleVariable, $listContentVariable];
        return $listData;
    }

    public function update1stTime($listDataK, $loading, $k, $type)
    {
        $actualTheoricalCreditPalletsNumber = Palletsaccount::where('name', $listDataK[1][1])->first()->theoricalNumberPallets;
        $actualTheoricalDebitPalletsNumber = Palletsaccount::where('name', $listDataK[1][2])->first()->theoricalNumberPallets;
        Palletsaccount::where('name', $listDataK[1][1])->update(['theoricalNumberPallets' => $actualTheoricalCreditPalletsNumber + $listDataK[1][0]]);
        Palletsaccount::where('name', $listDataK[1][2])->update(['theoricalNumberPallets' => $actualTheoricalDebitPalletsNumber - $listDataK[1][0]]);

//        //we update
//        Loading::where('atrnr', $loading->atrnr)->update(['numberPallets' . $type . $k => $listDataK[1][0]]);
//        Loading::where('atrnr', $loading->atrnr)->update(['accountCredit' . $type . $k => $listDataK[1][1]]);
//        Loading::where('atrnr', $loading->atrnr)->update(['accountDebit' . $type . $k => $listDataK[1][2]]);
//
//        if ($listDataK[0][4] == 'true') {
//
//            Loading::where('atrnr', $loading->atrnr)->update(['validate' . $type . $k => true]);
//        } else {
//
//            Loading::where('atrnr', $loading->atrnr)->update(['validate' . $type . $k => false]);
//        }

        if (isset($listDataK[0][0]) && isset($listDataK[0][1]) && isset($listDataK[0][2]) && (isset($documents) || !empty($actualDocuments)) && (($listDataK[1][4] <> null && $listDataK[1][4] == 'true') || ($listDataK[1][4] == null && $loading->$listDataK[0][4] == 1))) {
            $listDataK[1][3] = 'Complete Validated';

        } elseif (isset($listDataK[0][0]) && isset($listDataK[0][1]) && isset($listDataK[0][2]) && (isset($documents) || !empty($actualDocuments))) {
            $listDataK[1][3] = 'Complete';
        } elseif (!isset($documents) || empty($actualDocuments)) {
            $listDataK[1][3] = 'Waiting documents';
        } elseif (isset($listDataK[0][0]) || isset($listDataK[0][1]) || isset($listDataK[0][2]) || (isset($documents) || !empty($actualDocuments))) {
            $listDataK[1][3] = 'In progress';
        } else {
            $listDataK[1][3] = 'Untreated';
        }
        Loading::where('atrnr', $loading->atrnr)->update(['state' . $type . $k => $listDataK[1][3]]);
        $this->state($loading);
    }

    public function updateMoreTimes($listDataK, $loading, $documents, $actualDocuments, $actualCreditAccount, $actualDebitAccount, $actualPalletsNumber, $k, $type)
    {

//inverse transfer : we delete the last transfer
        $actualPalletsNumberCreditAccount = Palletsaccount::where('name', $actualCreditAccount)->first()->theoricalNumberPallets;
        Palletsaccount::where('name', $actualCreditAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberCreditAccount - $actualPalletsNumber]);
        $actualPalletsNumberDebitAccount = Palletsaccount::where('name', $actualDebitAccount)->first()->theoricalNumberPallets;
        Palletsaccount::where('name', $actualDebitAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberDebitAccount + $actualPalletsNumber]);

        //we do the new transfer
        $palletsNumberCreditAccount = Palletsaccount::where('name', $listDataK[1][1])->first()->theoricalNumberPallets;
        Palletsaccount::where('name', $listDataK[1][1])->update(['theoricalNumberPallets' => $palletsNumberCreditAccount + $listDataK[1][0]]);
        $palletsNumberDebitAccount = Palletsaccount::where('name', $listDataK[1][2])->first()->theoricalNumberPallets;
        Palletsaccount::where('name', $listDataK[1][2])->update(['theoricalNumberPallets' => $palletsNumberDebitAccount - $listDataK[1][0]]);

//        //we update
//        Loading::where('atrnr', $loading->atrnr)->update(['numberPallets' . $type . $k => $listDataK[1][0]]);
//        Loading::where('atrnr', $loading->atrnr)->update(['accountCredit' . $type . $k => $listDataK[1][1]]);
//        Loading::where('atrnr', $loading->atrnr)->update(['accountDebit' . $type . $k => $listDataK[1][2]]);
//
//        if ($listDataK[1][4] == 'true') {
//            Loading::where('atrnr', $loading->atrnr)->update(['validate' . $type . $k => true]);
//        } else {
//            Loading::where('atrnr', $loading->atrnr)->update(['validate' . $type . $k => false]);
//        }

        if (isset($listDataK[0][0]) && isset($listDataK[0][1]) && isset($listDataK[0][2]) && (isset($documents) || !empty($actualDocuments)) && (($listDataK[1][4] <> null && $listDataK[1][4] == 'true') || ($listDataK[1][4] == null && $loading->$listDataK[0][4] == 1))) {
            $listDataK[1][3] = 'Complete Validated';
        } elseif (isset($listDataK[0][0]) && isset($listDataK[0][1]) && isset($listDataK[0][2]) && (isset($documents) || !empty($actualDocuments))) {
            $listDataK[1][3] = 'Complete';
        } elseif (!isset($documents) || empty($actualDocuments)) {
            $listDataK[1][3] = 'Waiting documents';
        } elseif (isset($listDataK[0][0]) || isset($listDataK[0][1]) || isset($listDataK[0][2]) || (isset($documents) || !empty($actualDocuments))) {
            $listDataK[1][3] = 'In progress';
        } else {
            $listDataK[1][3] = 'Untreated';
        }
        Loading::where('atrnr', $loading->atrnr)->update(['state' . $type . $k => $listDataK[1][3]]);
        $this->state($loading);
    }

    public function accountTruck($atrnr, $anz, $account, $firstTime)
    {
        if (isset($account)) {
            Loading::where('atrnr', $atrnr)->update(['accountTruck' => $account]);
            if ($firstTime == true) {
                $palletsNumber = Palletsaccount::where('name', $account)->first()->theoricalNumberPallets;
                Palletsaccount::where('name', $account)->update(['theoricalNumberPallets' => $palletsNumber + $anz]);
            }
        }
    }

    public function uploadDocuments($atrnr, $documents, $type)
    {
        if (isset($documents)) {
            foreach ($documents as $doc) {
                $filename = $doc->getClientOriginalName();
                $extension = $doc->getClientOriginalExtension();
                $size = $doc->getSize();
                //if file is an image, a pdf or an email
                if (($extension == 'png' || $extension == 'jpg' || $extension == 'msg' || $extension == 'htm' || $extension == 'rtf' || $extension == 'pdf') && $size < 2000000) {
                    Storage::putFileAs('/proofsPallets/' . $atrnr . '/documents' . $type . 'Place', $doc, $filename);
                    Document::firstOrCreate([
                        'name' => $filename,
                        'type' => $type
                    ])->loadings()->attach($atrnr);
                } else {
                    session()->flash('messageErrorUpload', 'Error ! The file type is not supported (png, jgp, pdf, msg, htm, rtf only');
                }
            }
        }
        $filesNames = $this->actualDocuments($atrnr, $type);
        return $filesNames;
    }


    public function state($loading)
    {
        //////STATE GENERAL////
        //state loading place
        if ($loading->numberLoadingPlace > 0) {
            for ($k = 1; $k <= $loading->numberLoadingPlace; $k++) {
                $stateLoadingPlaceK = 'stateLoadingPlace' . $k;
                $$stateLoadingPlaceK = $loading->$stateLoadingPlaceK;
                $stateCompleteValidated = 0;
                $stateComplete = 0;
                $stateWaitingDocuments = 0;
                $stateInProgress = 0;
                $stateUntreated = 0;

                if ($$stateLoadingPlaceK == 'Complete Validated') {
                    $stateCompleteValidated++;
                } elseif ($$stateLoadingPlaceK == 'Complete') {
                    $stateComplete++;
                } elseif ($$stateLoadingPlaceK == 'Waiting documents') {
                    $stateWaitingDocuments++;
                } elseif ($$stateLoadingPlaceK == 'In progress') {
                    $stateInProgress++;
                } elseif ($$stateLoadingPlaceK == 'Untreated') {
                    $stateUntreated++;
                }
            }
//            dd($stateCompleteValidated, $stateComplete, $stateWaitingDocuments,$stateInProgress, $stateUntreated );
            if ($stateCompleteValidated == $loading->numberLoadingPlace) {
                $stateLoadingPlace = 'Complete Validated';
            } elseif ($stateWaitingDocuments == 0 && $stateInProgress == 0 && $stateUntreated == 0) {
                $stateLoadingPlace = 'Complete';
            } elseif ($stateWaitingDocuments > 0) {
                $stateLoadingPlace = 'Waiting documents';
            } elseif ($stateWaitingDocuments = 0 && ($stateInProgress > 0 || ($stateUntreated < $loading->numberLoadingPlace && $stateUntreated > 0))) {
                $stateLoadingPlace = 'In progress';
            } elseif ($stateUntreated == $loading->numberLoadingPlace) {
                $stateLoadingPlace = 'Untreated';
            }
//            dd($stateLoadingPlace);
        }

        //state offloading place
        if ($loading->numberOffloadingPlace > 0) {
            for ($k = 1; $k <= $loading->numberOffloadingPlace; $k++) {
                $stateOffloadingPlaceK = 'stateOffloadingPlace' . $k;
                $$stateOffloadingPlaceK = $loading->$stateOffloadingPlaceK;
                $stateCompleteValidated = 0;
                $stateComplete = 0;
                $stateWaitingDocuments = 0;
                $stateInProgress = 0;
                $stateUntreated = 0;
                if ($$stateOffloadingPlaceK == 'Complete Validated') {
                    $stateCompleteValidated++;
                } elseif ($$stateOffloadingPlaceK == 'Complete') {
                    $stateComplete++;
                } elseif ($$stateOffloadingPlaceK == 'Waiting documents') {
                    $stateWaitingDocuments++;
                } elseif ($$stateOffloadingPlaceK == 'In progress') {
                    $stateInProgress++;
                } elseif ($$stateOffloadingPlaceK == 'Untreated') {
                    $stateUntreated++;
                }
            }
//            dd($stateCompleteValidated, $stateComplete, $stateWaitingDocuments,$stateInProgress, $stateUntreated );
            if ($stateCompleteValidated == $loading->numberLoadingPlace) {
                $stateOffloadingPlace = 'Complete Validated';
            } elseif ($stateWaitingDocuments == 0 && $stateInProgress == 0 && $stateUntreated == 0) {
                $stateOffloadingPlace = 'Complete';
            } elseif ($stateWaitingDocuments > 0) {
                $stateOffloadingPlace = 'Waiting documents';
            } elseif ($stateWaitingDocuments = 0 && ($stateInProgress > 0 || ($stateUntreated < $loading->numberLoadingPlace && $stateUntreated > 0))) {
                $stateOffloadingPlace = 'In progress';
            } elseif ($stateUntreated == $loading->numberLoadingPlace) {
                $stateOffloadingPlace = 'Untreated';
            }
//            dd($stateOffloadingPlace);
        }
//        dd($stateLoadingPlace,$stateOffloadingPlace);

        //general state
        $stateTruck = $loading->stateTruck;
        if (isset($stateOffloadingPlace) && isset($stateLoadingPlace)) {
            if ($stateTruck == 'Complete Validated' && $stateOffloadingPlace == 'Complete Validated' && $stateLoadingPlace == 'Complete Validated') {
                $state = 'Complete Validated';
            } elseif (($stateTruck == 'Complete Validated' || $stateTruck == 'Complete') && ($stateOffloadingPlace == 'Complete Validated' || $stateOffloadingPlace == 'Complete') && ($stateLoadingPlace == 'Complete' || $stateLoadingPlace == 'Complete Validated')) {
                $state = 'Complete';
            } elseif ($stateTruck == 'Waiting documents' || $stateOffloadingPlace == 'Waiting documents' || $stateLoadingPlace == 'Waiting documents') {
                $state = 'Waiting documents';
            } elseif ($stateTruck == 'Untreated' && $stateOffloadingPlace == 'Untreated' && $stateLoadingPlace == 'Untreated') {
                $state = 'Untreated';
            } elseif ($stateTruck <> 'Waiting documents' && $stateOffloadingPlace <> 'Waiting documents' && $stateLoadingPlace <> 'Waiting documents') {
                $state = 'In progress';
            }
        } elseif (isset($stateOffloadingPlace) && !isset($stateLoadingPlace)) {
            if ($stateTruck == 'Complete Validated' && $stateOffloadingPlace == 'Complete Validated') {
                $state = 'Complete Validated';
            } elseif (($stateTruck == 'Complete Validated' || $stateTruck == 'Complete') && ($stateOffloadingPlace == 'Complete Validated' || $stateOffloadingPlace == 'Complete')) {
                $state = 'Complete';
            } elseif ($stateTruck == 'Waiting documents' || $stateOffloadingPlace == 'Waiting documents') {
                $state = 'Waiting documents';
            } elseif ($stateTruck == 'Untreated' && $stateOffloadingPlace == 'Untreated') {
                $state = 'Untreated';
            } elseif ($stateTruck <> 'Waiting documents' && $stateOffloadingPlace <> 'Waiting documents') {
                $state = 'In progress';
            }
        } elseif (!isset($stateOffloadingPlace) && isset($stateLoadingPlace)) {
            if ($stateTruck == 'Complete Validated' && $stateLoadingPlace == 'Complete Validated') {
                $state = 'Complete Validated';
            } elseif (($stateTruck == 'Complete Validated' || $stateTruck == 'Complete') && ($stateLoadingPlace == 'Complete Validated' || $stateLoadingPlace == 'Complete')) {
                $state = 'Complete';
            } elseif ($stateTruck == 'Waiting documents' || $stateLoadingPlace == 'Waiting documents') {
                $state = 'Waiting documents';
            } elseif ($stateTruck == 'Untreated' && $stateLoadingPlace == 'Untreated') {
                $state = 'Untreated';
            } elseif ($stateTruck <> 'Waiting documents' && $stateLoadingPlace <> 'Waiting documents') {
                $state = 'In progress';
            }
        } elseif (!isset($stateOffloadingPlace) && !isset($stateLoadingPlace)) {
            $state = $stateTruck;
        }

        Loading::where('atrnr', $loading->atrnr)->update(['state' => $state]);
    }
}
