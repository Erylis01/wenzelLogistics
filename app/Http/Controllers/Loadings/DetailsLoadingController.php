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

            //link to the mother loading of the subloading
            if(substr_count($loading->atrnr, '-')<>0){
           $atrnr1=explode('-', $loading->atrnr)[0];
                $atrnr2=array_slice(explode('-', $loading->atrnr), 1);
                $atrnr2=implode('-',$atrnr2);
            }

            return view('loadings.detailsLoading', compact('loading','atrnr1', 'atrnr2', 'listPalletsAccounts', 'listPalletstransfers',
                'palletsAccountFavoriteTruck', 'listPalletsaccountsCarrier', 'listTypes'
            ));
        } else {
            return view('auth.login');
        }
    }

    /**
     * update only the panel information of the loading
     * @param Request $request
     * @param $atrnr
     * @return $this
     */
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
                Loading::where('atrnr','like', $atrnr.'%' )->update(['reasonUpdatePT' => $reasonUpdatePT, 'pt' => 'NEIN']);
                session()->flash('messageUpdatePTLoading', 'Be careful : your loading is now WITHOUT exchange pallets');
            } elseif (isset($request->update)) {
                Loading::where('atrnr', $atrnr)->update(['ladedatum' => $ladedatum, 'entladedatum' => $entladedatum, 'disp' => $disp, 'referenz' => $referenz, 'auftraggeber' => $auftraggeber, 'beladestelle' => $beladestelle,
                    'ortb' => $ortb, 'plzb' => $plzb, 'landb' => $landb, 'entladestelle' => $entladestelle, 'orte' => $orte, 'plze' => $plze, 'lande' => $lande, 'anz' => $anz, 'art' => $art, 'ware' => $ware,
                    'subfrachter' => $subfrachter, 'kennzeichen' => $kennzeichen, 'zusladestellen' => $zusladestellen]);
                Loading::where('atrnr','like', $atrnr.'%' )->update(['disp'=> $disp]);
                session()->flash('messageUpdateLoading', 'Successfully updated loading');
            }
            session()->flash('openPanelInformation', 'openPanelInformation');
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
            return redirect()->back();
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
            //accept to add the transfer
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
            $this->state($loading, Palletstransfer::where('loading_atrnr', $atrnr)->get());
            session()->flash('messageAddPalletstransfer', 'Successfully added new pallets transfer');
            session()->flash('openPanelPallets', 'openPanelPallets');
            return redirect()->back();
        } elseif (isset($closeSubmitAddModal)) {
            //refuse to add the transfer
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
            if (isset($creditAccount) && isset($debitAccount) && isset($palletsNumber) && isset($type) &&  !empty($filesNames) && $validate == 1) {
                $state = 'Complete Validated';
            } elseif (isset($creditAccount) && isset($debitAccount) && isset($palletsNumber) && isset($type) && !empty($filesNames) && $validate == 0) {
                $state = 'Complete';
            } elseif (empty($filesNames)) {
                $state = 'Waiting documents';
            } elseif (isset($creditAccount) || isset($debitAccount) || isset($palletsNumber) || isset($type) || !empty($filesNames)) {
                $state = 'In progress';
            }
            Palletstransfer::where('id', $transfer->id)->update(['state' => $state]);
            $this->state($loading, Palletstransfer::where('loading_atrnr', $atrnr)->get());
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
            $this->state($loading, Palletstransfer::where('loading_atrnr', $atrnr)->get());
            session()->flash('openPanelPallets', 'openPanelPallets');
            return redirect()->back();
        }elseif(isset($submitPallets)){
            //to update the transfer
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
            $this->state($loading, Palletstransfer::where('loading_atrnr', $atrnr)->get());
            session()->flash('openPanelPallets', 'openPanelPallets');
            return view('loadings.detailsLoading', compact('loading', 'listPalletsAccounts', 'listPalletstransfers',
                 'listPalletsaccountsCarrier', 'listTypes', 'transfer', 'submitPallets', 'filesNames'));
        }elseif(isset($okSubmitPalletsModal)){
            //valide the transfer update
            $transfer=Palletstransfer::where('id', $okSubmitPalletsModal)->first();
            $filesNames=$this->actualDocuments($transfer->id);
            $actualCreditAccount = session('actualCreditAccount');
            $actualDebitAccount = session('actualDebitAccount');
            $actualPalletsNumber = session('actualPalletsNumber');
            $this->updateInfo($transfer, $actualPalletsNumber, $actualCreditAccount, $actualDebitAccount, $filesNames);
            $transfer=Palletstransfer::where('id', $okSubmitPalletsModal)->first();
            $this->state($loading, Palletstransfer::where('loading_atrnr', $atrnr)->get());
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
            //refuse the transfer update
            $actualCreditAccount = session('actualCreditAccount');
            $actualDebitAccount = session('actualDebitAccount');
            $actualPalletsNumber = session('actualPalletsNumber');
            $actualType = session('actualType');
            $actualDetails = session('actualDetails');
            $actualDate = session('actualDate');
            $actualMultiTransfer = session('actualMultiTransfer');
            $actualValidate = session('actualValidate');
            Palletstransfer::where('id', $closeSubmitPalletsModal)->update(['multiTransfer'=>$actualMultiTransfer,'validate'=>$actualValidate, 'type'=>$actualType, 'details'=>$actualDetails,'palletsNumber'=>$actualPalletsNumber,'date'=>$actualDate,  'creditAccount'=>$actualCreditAccount, 'debitAccount'=>$actualDebitAccount]);
            $this->state($loading, Palletstransfer::where('loading_atrnr', $atrnr)->get());
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
            $this->state($loading, $listPalletstransfers);
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

    /**
     * get all the documents associated to the transfer $id
     * @param $id
     * @return array
     */
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

    /**
     * upload a document on the website
     * @param $documents
     * @param $id
     * @return array
     */
    public function upload($documents, $id)
    {
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
        $filesNames = $this->actualDocuments($id);
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

    /**
     * remove the last transfer on real pallets number
     * @param $transfer
     */
    public function inverseRealPalletsNumber($transfer)
    {
        $actualRealPalletsNumberCreditAccount = Palletsaccount::where('name', $transfer->creditAccount)->first()->realNumberPallets;
        Palletsaccount::where('name', $transfer->creditAccount)->update(['realNumberPallets' => $actualRealPalletsNumberCreditAccount -$transfer->palletsNumber]);
        $actualRealPalletsNumberDebitAccount = Palletsaccount::where('name', $transfer->debitAccount)->first()->realNumberPallets;
        Palletsaccount::where('name', $transfer->debitAccount)->update(['realNumberPallets' => $actualRealPalletsNumberDebitAccount + $transfer->palletsNumber]);
    }

    /**
     * update only the information related to the transfer
     * @param $transfer
     * @param $actualPalletsNumber
     * @param $actualCreditAccount
     * @param $actualDebitAccount
     * @param $filesNames
     */
    public function updateInfo($transfer, $actualPalletsNumber, $actualCreditAccount, $actualDebitAccount, $filesNames)
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

        //state
        if (isset($transfer->creditAccount) && isset($transfer->debitAccount) && isset($transfer->palletsNumber) && isset($transfer->type) && !empty($filesNames) && $transfer->validate == 1) {
            $state = 'Complete Validated';
        } elseif (isset($transfer->creditAccount) && isset($transfer->debitAccount) && isset($transfer->palletsNumber) && isset($transfer->type) &&  !empty($filesNames) && $transfer->validate == 0) {
            $state = 'Complete';
        } elseif (empty($filesNames)) {
            $state = 'Waiting documents';
        } elseif (isset($transfer->creditAccount) || isset($transfer->debitAccount) || isset($transfer->palletsNumber) || isset($transfer->type) || !empty($filesNames)) {
            $state = 'In progress';
        }
        Palletstransfer::where('id', $transfer->id)->update(['state' => $state]);
        session()->flash('messageSubmitPalletstransfer', 'Successfully updated and pallets transfer');
    }

    /**
     * define the general state of the loading according to all transfers state
     * @param $loading
     * @param $listPalletstransfers
     */
    public function state($loading, $listPalletstransfers)
    {
        //////STATE GENERAL////
        if($listPalletstransfers->isEmpty()){
            $state='Untreated';
        }else{
            $stateCompleteValidated = 0;
            $stateComplete = 0;
            $stateWaitingDocuments = 0;
            $stateInProgress = 0;
            foreach($listPalletstransfers as $transfer){
                if($transfer->state=='Complete Validated'){
                    $stateCompleteValidated=$stateCompleteValidated+1;
                }elseif($transfer->state=='Complete'){
                    $stateComplete=$stateComplete+1;
                }elseif($transfer->state=='Waiting documents'){
                    $stateWaitingDocuments=$stateWaitingDocuments+1;
                }elseif($transfer->state=='In progress'){
                    $stateInProgress=$stateInProgress+1;
                }
            }

            if ($stateCompleteValidated == count($listPalletstransfers)) {
                $state = 'Complete Validated';
            } elseif ($stateWaitingDocuments == 0 && $stateInProgress == 0) {
                $state = 'Complete';
            } elseif ($stateWaitingDocuments > 0) {
                $state = 'Waiting documents';
            } elseif ($stateWaitingDocuments = 0 && $stateInProgress > 0) {
                $state = 'In progress';
            }
        }
        Loading::where('atrnr', $loading->atrnr)->update(['state' => $state]);
    }

    /**
     * show the add form to add a subloading
     * @param $atrnr
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAdd($atrnr){
        $loading=Loading::where('atrnr', $atrnr)->first();
return view('loadings.addSubloading', compact('loading'));
    }

    /**
     * add a subloading
     * @param $atrnr
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function add($atrnr){

        $loadingInitial=Loading::where('atrnr', $atrnr)->first();
        $referenz=Input::get('referenz');
        $auftraggeber=Input::get('auftraggeber');
        $subfrachter=Input::get('subfrachter');
        $kennzeichen=Input::get('kennzeichen');
        $art=Input::get('art');
        $anz=Input::get('anz');
        $ware=Input::get('ware');
        $ladedatum=Input::get('ladedatum');
        $beladestelle=Input::get('beladestelle');
        $ortb=Input::get('ortb');
        $plzb=Input::get('plzb');
        $landb=Input::get('landb');
        $zusladestellen=Input::get('zusladestellen');
        $entladedatum=Input::get('entladedatum');
        $entladestelle=Input::get('entladestelle');
        $orte=Input::get('orte');
        $plze=Input::get('plze');
        $lande=Input::get('lande');
        $disp=$loadingInitial->disp;
        $pt=$loadingInitial->pt;

        if(substr_count($loadingInitial->atrnr, '-')==0){
            $atrnr=$loadingInitial->atrnr.'-1';
        }elseif(substr_count($loadingInitial->atrnr, '-')>0){
            $atrnrSplit=explode('-', $loadingInitial->atrnr);
            $atrnrSplit[count($atrnrSplit-1)]=$atrnrSplit[count($atrnrSplit-1)]+1;
            $atrnr=implode('-',$atrnrSplit);
        }
        $loadingsTest = Loading::where('atrnr', '=', $atrnr)->first();
        if ($loadingsTest==null) {
            $k=count(Loading::get())+1;
            Loading::firstOrCreate([
                'id'=>$k,
                'ladedatum' =>$ladedatum ,
                'entladedatum' => $entladedatum,
                'disp' => $disp,
                'atrnr' => $atrnr,
                'referenz' => $referenz,
                'auftraggeber' => $auftraggeber,
                'beladestelle' => $beladestelle,
                'landb' => $landb,
                'plzb' => $plzb,
                'ortb' => $ortb,
                'entladestelle' => $entladestelle,
                'lande' => $lande,
                'plze' => $plze,
                'orte' => $orte,
                'anz' => $anz,
                'art' => $art,
                'ware' => $ware,
                'pt' =>$pt,
                'subfrachter' => $subfrachter,
                'kennzeichen' => $kennzeichen,
                'zusladestellen' => $zusladestellen,
            ]);
        }

        return redirect('/loadings');
    }
}
