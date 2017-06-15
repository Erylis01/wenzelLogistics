<?php

namespace App\Http\Controllers;

use App\Document;
use App\Loading;
use App\PalletsAccount;
use App\Palletstransfer;
use App\Truck;
use App\Warehouse;
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
            $detailsLoading = DB::table('loadings')->where('atrnr', '=', $atrnr)->first();
            $ladedatum = $detailsLoading->ladedatum;
            $entladedatum = $detailsLoading->entladedatum;
            $disp = $detailsLoading->disp;
            $referenz = $detailsLoading->referenz;
            $auftraggeber = $detailsLoading->auftraggeber;
            $beladestelle = $detailsLoading->beladestelle;
            $landb = $detailsLoading->landb;
            $plzb = $detailsLoading->plzb;
            $ortb = $detailsLoading->ortb;
            $entladestelle = $detailsLoading->entladestelle;
            $lande = $detailsLoading->lande;
            $plze = $detailsLoading->plze;
            $orte = $detailsLoading->orte;
            $anz = $detailsLoading->anz;
            $art = $detailsLoading->art;
            $ware = $detailsLoading->ware;
            $pt = $detailsLoading->pt;
            $subfrachter = $detailsLoading->subfrachter;
            $kennzeichen = $detailsLoading->kennzeichen;
            $zusladestellen = $detailsLoading->zusladestellen;
            $reasonUpdatePT = $detailsLoading->reasonUpdatePT;


            //////PALLETS PANEL//////
            //control pallets
            $state = $detailsLoading->state;
            $numberLoadingPlace = $detailsLoading->numberLoadingPlace;
            $numberOffloadingPlace = $detailsLoading->numberOffloadingPlace;

            //all pallets account
            $listPalletsAccounts = DB::table('palletsaccounts')->get();

            //truck
            $listPalletsAccountsCarrier = Palletsaccount::where('type', 'Carrier')->get();
            $accountTruck = $detailsLoading->accountTruck;
            $stateTruck = $detailsLoading->stateTruck;
            $validateTruck = $detailsLoading->validateTruck;

            //looking for the account that contains the license plate if it's set
            if($kennzeichen==""){
                $licensePlate='OTHER';
            }else{
                $licensePlate=$kennzeichen;
            }
if (Truck::where('name', trim(explode(',', $subfrachter)[0]))->where('licensePlate', $licensePlate)->first() <> null){
            $namePalletsAccountTruck = Truck::where('name', trim(explode(',', $subfrachter)[0]))->where('licensePlate', $licensePlate)->first()->palletsaccount_name;
            if ($namePalletsAccountTruck <> null) {
                $palletsAccountFavoriteTruck = Palletsaccount::where('name', $namePalletsAccountTruck)->first()->name;
            }
        }
//        dd($palletsAccountFavoriteTruck);


            //loading panel
            $totalPalletsLoadingPlace = 0;
            for ($k = 1; $k <= $numberLoadingPlace; $k++) {
                $stateLoadingPlaceK = 'stateLoadingPlace' . $k;
                $$stateLoadingPlaceK = $detailsLoading->$stateLoadingPlaceK;
                $validateLoadingPlaceK = 'validateLoadingPlace' . $k;
                $$validateLoadingPlaceK = $detailsLoading->$validateLoadingPlaceK;
                $numberPalletsLoadingPlaceK = 'numberPalletsLoadingPlace' . $k;
                $$numberPalletsLoadingPlaceK = $detailsLoading->$numberPalletsLoadingPlaceK;
                $accountDebitLoadingPlaceK = 'accountDebitLoadingPlace' . $k;
                $$accountDebitLoadingPlaceK = $detailsLoading->$accountDebitLoadingPlaceK;
                $accountCreditLoadingPlaceK = 'accountCreditLoadingPlace' . $k;
                $$accountCreditLoadingPlaceK = $detailsLoading->$accountCreditLoadingPlaceK;
                $totalPalletsLoadingPlace = $totalPalletsLoadingPlace + $$numberPalletsLoadingPlaceK;
            }
            //looking for the account of the warehouse which zipcode is plz beladestelle
            if (Warehouse::where('zipcode', $plzb)->first() <> null) {
                $idWarehouseZipcodeLoadingPlace = Warehouse::where('zipcode', $plzb)->first()->id;
                $accountZipcodeLoadingPlace1 = DB::table('palletsaccount_warehouse')->where('warehouse_id', $idWarehouseZipcodeLoadingPlace)->first();
                if ($accountZipcodeLoadingPlace1 <> null) {
                    $accountZipcodeLoadingPlace = Palletsaccount::where('id', $accountZipcodeLoadingPlace1->palletsaccount_id)->first()->name;

                }
            };

            $files = DB::table('document_loading')->where('loading_id', $atrnr)->get();

            //offloading panel
            $totalPalletsOffloadingPlace = 0;
            for ($k = 1; $k <= $numberOffloadingPlace; $k++) {
                $stateOffloadingPlaceK = 'stateOffloadingPlace' . $k;
                $$stateOffloadingPlaceK = $detailsLoading->$stateOffloadingPlaceK;
                $validateOffloadingPlaceK = 'validateOffloadingPlace' . $k;
                $$validateOffloadingPlaceK = $detailsLoading->$validateOffloadingPlaceK;
                $numberPalletsOffloadingPlaceK = 'numberPalletsOffloadingPlace' . $k;
                $$numberPalletsOffloadingPlaceK = $detailsLoading->$numberPalletsOffloadingPlaceK;
                $accountDebitOffloadingPlaceK = 'accountDebitOffloadingPlace' . $k;
                $$accountDebitOffloadingPlaceK = $detailsLoading->$accountDebitOffloadingPlaceK;
                $accountCreditOffloadingPlaceK = 'accountCreditOffloadingPlace' . $k;
                $$accountCreditOffloadingPlaceK = $detailsLoading->$accountCreditOffloadingPlaceK;
                $totalPalletsOffloadingPlace = $totalPalletsOffloadingPlace + $$numberPalletsOffloadingPlaceK;
            }

            if (Warehouse::where('zipcode', $plze)->first() <> null) {
                $idWarehouseZipcodeOffloadingPlace = Warehouse::where('zipcode', $plze)->first()->id;
                $accountZipcodeOffloadingPlace1 = DB::table('palletsaccount_warehouse')->where('warehouse_id', $idWarehouseZipcodeOffloadingPlace)->first();
                if ($accountZipcodeOffloadingPlace1 <> null) {
                    $accountZipcodeOffloadingPlace = Palletsaccount::where('id', $accountZipcodeOffloadingPlace1->palletsaccount_id)->first()->name;
                }
            };

            //offloading-loading-truck
            if (!$files->isEmpty()) {
                foreach ($files as $f) {
                    $filesNames = Document::where('id', $f->document_id)->first();
                    if ($filesNames->type == 'Loading') {
                        $filesNamesLoadingPlace[] = $filesNames->name;
                    } elseif ($filesNames->type == 'Offloading') {
                        $filesNamesOffloadingPlace[] = $filesNames->name;
                    } elseif ($filesNames->type == 'Truck') {
                        $filesNamesTruck[] = $filesNames->name;
                    }
                }
            }

            return view('loadings.detailsLoading', compact('ladedatum', 'entladedatum', 'disp', 'atrnr', 'referenz', 'auftraggeber', 'beladestelle',
                'landb', 'plzb', 'ortb', 'entladestelle', 'lande', 'plze', 'orte', 'anz', 'art', 'ware',
                'pt', 'subfrachter', 'kennzeichen', 'zusladestellen', 'reasonUpdatePT', 'state', 'listPalletsAccounts', 'numberLoadingPlace', 'numberOffloadingPlace',
                'filesNamesLoadingPlace', 'accountZipcodeLoadingPlace', 'totalPalletsLoadingPlace',
                'stateLoadingPlace1', 'numberPalletsLoadingPlace1', 'accountDebitLoadingPlace1', 'accountCreditLoadingPlace1', 'validateLoadingPlace1',
                'stateLoadingPlace2', 'numberPalletsLoadingPlace2', 'accountDebitLoadingPlace2', 'accountCreditLoadingPlace2', 'validateLoadingPlace2',
                'stateLoadingPlace3', 'numberPalletsLoadingPlace3', 'accountDebitLoadingPlace3', 'accountCreditLoadingPlace3', 'validateLoadingPlace3',
                'stateLoadingPlace4', 'numberPalletsLoadingPlace4', 'accountDebitLoadingPlace4', 'accountCreditLoadingPlace4', 'validateLoadingPlace4',
                'stateLoadingPlace5', 'numberPalletsLoadingPlace5', 'accountDebitLoadingPlace5', 'accountCreditLoadingPlace5', 'validateLoadingPlace5',
                'filesNamesOffloadingPlace', 'accountZipcodeOffloadingPlace', 'totalPalletsOffloadingPlace',
                'stateOffloadingPlace1', 'numberPalletsOffloadingPlace1', 'accountDebitOffloadingPlace1', 'accountCreditOffloadingPlace1', 'validateOffloadingPlace1',
                'stateOffloadingPlace2', 'numberPalletsOffloadingPlace2', 'accountDebitOffloadingPlace2', 'accountCreditOffloadingPlace2', 'validateOffloadingPlace2',
                'stateOffloadingPlace3', 'numberPalletsOffloadingPlace3', 'accountDebitOffloadingPlace3', 'accountCreditOffloadingPlace3', 'validateOffloadingPlace3',
                'stateOffloadingPlace4', 'numberPalletsOffloadingPlace4', 'accountDebitOffloadingPlace4', 'accountCreditOffloadingPlace4', 'validateOffloadingPlace4',
                'stateOffloadingPlace5', 'numberPalletsOffloadingPlace5', 'accountDebitOffloadingPlace5', 'accountCreditOffloadingPlace5', 'validateOffloadingPlace5',
                'filesNamesTruck', 'stateTruck', 'accountTruck', 'validateTruck', 'palletsAccountFavoriteTruck', 'listPalletsAccountsCarrier'
            ));
        } else {
            return view('auth.login');
        }
    }

    public function update(Request $request, $atrnr)
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

    public function submitUpload($atrnr, $anz, Request $request)
    {

        $loading = Loading::where('atrnr', $atrnr)->first();
        $uploadLoading = Input::get('uploadLoading');
        $uploadOffloading = Input::get('uploadOffloading');
        $uploadTruck=Input::get('uploadTruck');
        $deleteDocument = Input::get('deleteDocument');
        $deleteLoadingPlace = Input::get('deleteLoadingPlace');
        $deleteOffloadingPlace = Input::get('deleteOffloadingPlace');
        $addLoadingPlace = Input::get('addLoadingPlace');
        $addOffloadingPlace = Input::get('addOffloadingPlace');
        $submitLoading = Input::get('submitLoading');
        $submitOffloading = Input::get('submitOffloading');
        $submitTruck=Input::get('submitTruck');
        $closeSubmitLoadingModal = Input::get('closeSubmitLoadingModal');
        $closeSubmitOffloadingModal = Input::get('closeSubmitOffloadingModal');

        //documents already associated to the loading TRUCK ?
        $actualDocuments_LoadingTruck = DB::table('document_loading')->where('loading_id', $atrnr)->get();
        $actualDocumentsTruck = $this->documentsAssociated($actualDocuments_LoadingTruck, 'Truck');
        //documents
        $documentsTruck = $request->file('documentsTruck');

        //documents already associated to the loading LOADING ?
        $actualDocuments_LoadingLoading = DB::table('document_loading')->where('loading_id', $atrnr)->get();
        $actualDocumentsLoading = $this->documentsAssociated($actualDocuments_LoadingLoading, 'Loading');
        //documents
        $documentsLoading = $request->file('documentsLoading');

        //documents already associated to the loading OFFLOADING ?
        $actualDocuments_LoadingOffloading = DB::table('document_loading')->where('loading_id', $atrnr)->get();
        $actualDocumentsOffloading = $this->documentsAssociated($actualDocuments_LoadingOffloading, 'Offloading');
        //documents
        $documentsOffloading = $request->file('documentsOffloading');

        ///////TRUCK/////
    if(isset($submitTruck)){
        $accountTruck=Input::get('accountTruck');
        $validateTruck=Input::get('validateTruck');
        $firstTimeTruck=$loading->firstTimeTruck;

        $this->accountTruck($atrnr, $anz, $accountTruck, $firstTimeTruck);
        $this->validateTransfer($atrnr, $validateTruck, 'Truck', -1000);

        //state
        if (isset($accountTruck) && $validateTruck==true && (isset($documentsTruck) || !empty($actualDocumentsTruck))) {
            $stateTruck = 'Complete Validated';
//            $actualRealNumberPalletsAccount = Palletsaccount::where('name', $accountTruck)->first()->realNumberPallets;
//            Palletsaccount::where('name', $accountTruck)->update(['realNumberPallets' => $actualRealNumberPalletsAccount + $anz]);
            } elseif (isset($accountTruck) && (isset($documentsTruck) || !empty($actualDocumentsTruck))) {
            $stateTruck = 'Complete';
        } elseif (!isset($documentsTruck) || empty($actualDocumentsTruck)) {
            $stateTruck = 'Waiting documents';
        } elseif (isset($accountTruck)  || (isset($documentsTruck) || !empty($actualDocumentsTruck))) {
            $stateTruck = 'In progress';
        } else {
            $stateTruck = 'Untreated';
        }
        Loading::where('atrnr', $atrnr)->update(['stateTruck' => $stateTruck]);

        session()->flash('messageSuccessSubmit', 'Successfully updated pallets location');
        session()->flash('openPanelTruck', 'openPanelTruck');

    }elseif (isset($uploadTruck)) {
        ////////UPLOAD TRUCK PANEL/////////
        $this->uploadDocuments($atrnr, $documentsTruck, 'Truck');

        //state
            $accountTruck=Input::get('accountTruck');
            $validateTruck=Input::get('validateTruck');

            if (isset($accountTruck) && $validateTruck==true && (isset($documentsTruck) || !empty($actualDocumentsTruck))) {
                $stateTruck = 'Complete Validated';
//            $actualRealNumberPalletsAccount = Palletsaccount::where('name', $accountTruck)->first()->realNumberPallets;
//            Palletsaccount::where('name', $accountTruck)->update(['realNumberPallets' => $actualRealNumberPalletsAccount + $anz]);
            } elseif (isset($accountTruck) && (isset($documentsTruck) || !empty($actualDocumentsTruck))) {
                $stateTruck = 'Complete';
            } elseif (!isset($documentsTruck) || empty($actualDocumentsTruck)) {
                $stateTruck = 'Waiting documents';
            } elseif (isset($accountTruck)  || (isset($documentsTruck) || !empty($actualDocumentsTruck))) {
                $stateTruck = 'In progress';
            } else {
                $stateTruck = 'Untreated';
            }
            Loading::where('atrnr', $atrnr)->update(['stateTruck' => $stateTruck]);

        session()->flash('openPanelTruck', 'openPanelTruck');

    }elseif (isset($addLoadingPlace)) {
        //////LOADING PLACES//////
            $this->addPlace($atrnr, 'LoadingPlace', $loading->numberLoadingPlace);
            session()->flash('openPanelLoading', 'openPanelLoading');
        } elseif (isset($deleteLoadingPlace)) {
            $this->deletePlace($atrnr, 'LoadingPlace', $loading->numberLoadingPlace);
            session()->flash('openPanelLoading', 'openPanelLoading');
        } elseif (isset($submitLoading)) {
            ///////SUBMIT LOADING PANEL////////
            $k = $submitLoading;
            //number pallets
            $numberPalletsLoadingPlaceK = 'numberPalletsLoadingPlace' . $k;
            $$numberPalletsLoadingPlaceK = Input::get('numberPalletsLoadingPlace' . $k);

            //account credited
            $accountCreditLoadingPlaceK = 'accountCreditLoadingPlace' . $k;
            $$accountCreditLoadingPlaceK = Input::get('accountCreditLoadingPlace' . $k);

            //account debited
            $accountDebitLoadingPlaceK = 'accountDebitLoadingPlace' . $k;
            $$accountDebitLoadingPlaceK = Input::get('accountDebitLoadingPlace' . $k);

            //validated
            $validateLoadingPlaceK = 'validateLoadingPlace' . $k;
            $$validateLoadingPlaceK = Input::get('validateLoadingPlace' . $k);

            $actualCreditAccount = Loading::where('atrnr', $atrnr)->first()->$accountCreditLoadingPlaceK; //credit account in memory
            $actualDebitAccount = Loading::where('atrnr', $atrnr)->first()->$accountDebitLoadingPlaceK;
            $actualNumberPallets = Loading::where('atrnr', $atrnr)->first()->$numberPalletsLoadingPlaceK;

            if (isset($$accountCreditLoadingPlaceK) && isset($$accountDebitLoadingPlaceK) && isset($$numberPalletsLoadingPlaceK)) {
                if ($actualCreditAccount <> null && $actualDebitAccount <> null && $actualNumberPallets <> null) {
                    //the pallets transfer has alredy been done, we have to take it the former off and then to put the new one

                    //pallets number on the credit account in memory
                    $actualTheoricalNumberPalletsActualCreditAccount = Palletsaccount::where('name', $actualCreditAccount)->first()->theoricalNumberPallets;
                    Palletsaccount::where('name', $actualCreditAccount)->update(['theoricalNumberPallets' => $actualTheoricalNumberPalletsActualCreditAccount - $actualNumberPallets]);
                    //pallets number on the new credit account
                    $actualTheoricalNumberPalletsCreditAccount = Palletsaccount::where('name', $$accountCreditLoadingPlaceK)->first()->theoricalNumberPallets;
                    Palletsaccount::where('name', $$accountCreditLoadingPlaceK)->update(['theoricalNumberPallets' => $actualTheoricalNumberPalletsCreditAccount + $$numberPalletsLoadingPlaceK]);
                    //pallets number on the debit account in memory
                    $actualTheoricalNumberPalletsActualDebitAccount = Palletsaccount::where('name', $actualDebitAccount)->first()->theoricalNumberPallets;
                    Palletsaccount::where('name', $actualDebitAccount)->update(['theoricalNumberPallets' => $actualTheoricalNumberPalletsActualDebitAccount + $actualNumberPallets]);
                    //pallets number on the new debit account
                    $actualTheoricalNumberPalletsDebitAccount = Palletsaccount::where('name', $$accountDebitLoadingPlaceK)->first()->theoricalNumberPallets;
                    Palletsaccount::where('name', $$accountDebitLoadingPlaceK)->update(['theoricalNumberPallets' => $actualTheoricalNumberPalletsDebitAccount - $$numberPalletsLoadingPlaceK]);

                    if ($actualCreditAccount == $$accountCreditLoadingPlaceK && $actualDebitAccount <> $$accountDebitLoadingPlaceK) {
                        session()->flash('testFirstTime', 'sameC-diffD');
                        session()->flash('actualTheoricalNumberPalletsCreditAccount', $actualTheoricalNumberPalletsActualCreditAccount);
                        session()->flash('actualTheoricalNumberPalletsDebitAccount', $actualTheoricalNumberPalletsDebitAccount);
                    } elseif ($actualCreditAccount <> $$accountCreditLoadingPlaceK && $actualDebitAccount == $$accountDebitLoadingPlaceK) {
                        session()->flash('testFirstTime', 'diffC-sameD');
                        session()->flash('actualTheoricalNumberPalletsCreditAccount', $actualTheoricalNumberPalletsCreditAccount);
                        session()->flash('actualTheoricalNumberPalletsDebitAccount', $actualTheoricalNumberPalletsActualDebitAccount);
                    } elseif ($actualCreditAccount <> $$accountCreditLoadingPlaceK && $actualDebitAccount <> $$accountDebitLoadingPlaceK) {
                        session()->flash('testFirstTime', 'diffC-diffD');
                        session()->flash('actualTheoricalNumberPalletsCreditAccount', $actualTheoricalNumberPalletsCreditAccount);
                        session()->flash('actualTheoricalNumberPalletsDebitAccount', $actualTheoricalNumberPalletsDebitAccount);
                    } elseif ($actualCreditAccount == $$accountCreditLoadingPlaceK && $actualDebitAccount == $$accountDebitLoadingPlaceK) {
                        session()->flash('testFirstTime', 'sameC-sameD');
                        session()->flash('actualTheoricalNumberPalletsCreditAccount', $actualTheoricalNumberPalletsActualCreditAccount);
                        session()->flash('actualTheoricalNumberPalletsDebitAccount', $actualTheoricalNumberPalletsActualDebitAccount);
                    }
                    session()->flash('creditAccount', $$accountCreditLoadingPlaceK);
                    session()->flash('debitAccount', $$accountDebitLoadingPlaceK);
                    session()->flash('palletsNumber', $$numberPalletsLoadingPlaceK);
                    session()->flash('lastPalletsNumber', $actualNumberPallets);
                } else {
                    //0 pallets have been done ->1st time we will do it -> credit and debit
                    $actualTheoricalNumberPalletsCreditAccount = Palletsaccount::where('name', $$accountCreditLoadingPlaceK)->first()->theoricalNumberPallets;
                    Palletsaccount::where('name', $$accountCreditLoadingPlaceK)->update(['theoricalNumberPallets' => $actualTheoricalNumberPalletsCreditAccount + $$numberPalletsLoadingPlaceK, 'lastNumberPalletsTransfered' => $$numberPalletsLoadingPlaceK]);
                    $actualTheoricalNumberPalletsDebitAccount = Palletsaccount::where('name', $$accountDebitLoadingPlaceK)->first()->theoricalNumberPallets;
                    Palletsaccount::where('name', $$accountDebitLoadingPlaceK)->update(['theoricalNumberPallets' => $actualTheoricalNumberPalletsDebitAccount - $$numberPalletsLoadingPlaceK, 'lastNumberPalletsTransfered' => -$$numberPalletsLoadingPlaceK]);

                    session()->flash('testFirstTime', '1stTime');
                    session()->flash('actualTheoricalNumberPalletsCreditAccount', $actualTheoricalNumberPalletsCreditAccount);
                    session()->flash('actualTheoricalNumberPalletsDebitAccount', $actualTheoricalNumberPalletsDebitAccount);
                    session()->flash('creditAccount', $$accountCreditLoadingPlaceK);
                    session()->flash('debitAccount', $$accountDebitLoadingPlaceK);
                    session()->flash('palletsNumber', $$numberPalletsLoadingPlaceK);
                }
            } else {
                if ($actualNumberPallets <> null) {
                    session()->flash('messageErrorSubmit', 'Error ! The pallets number of the loading place ' . $k . " hasn't been updated. Pallets number on credit/debit accounts hasn't been updated too.");
                }
            }


            //count
//            $countTimeLoadingPlaceK = 'countTimeLoadingPlace' . $k;
//            $$countTimeLoadingPlaceK = $loading->$countTimeLoadingPlaceK;
//            if (isset($$accountCreditLoadingPlaceK) && isset($$accountDebitLoadingPlaceK) && isset($$numberPalletsLoadingPlaceK)) {
//                if ($$countTimeLoadingPlaceK < 1) {
//                    //credit and debit account 1st time
//                    Loading::where('atrnr', $atrnr)->update(['countTimeLoadingPlace' . $k => $$countTimeLoadingPlaceK + 1]);
//                    $actualTheoricalNumberPalletsCreditAccount = Palletsaccount::where('name', $$accountCreditLoadingPlaceK)->first()->theoricalNumberPallets;
//                    Palletsaccount::where('name', $$accountCreditLoadingPlaceK)->update(['theoricalNumberPallets' => $actualTheoricalNumberPalletsCreditAccount + $$numberPalletsLoadingPlaceK, 'lastNumberPalletsTransfered' => $$numberPalletsLoadingPlaceK]);
//                    $actualTheoricalNumberPalletsDebitAccount = Palletsaccount::where('name', $$accountDebitLoadingPlaceK)->first()->theoricalNumberPallets;
//                    Palletsaccount::where('name', $$accountDebitLoadingPlaceK)->update(['theoricalNumberPallets' => $actualTheoricalNumberPalletsDebitAccount - $$numberPalletsLoadingPlaceK, 'lastNumberPalletsTransfered' => -$$numberPalletsLoadingPlaceK]);
//
//                    session()->flash('testFirstTime', 1);
//                    session()->flash('actualTheoricalNumberPalletsCreditAccount', $actualTheoricalNumberPalletsCreditAccount);
//                    session()->flash('actualTheoricalNumberPalletsDebitAccount', $actualTheoricalNumberPalletsDebitAccount);
//                }else{
//                    Loading::where('atrnr',$atrnr)->update(['countTimeLoadingPlace'.$k => $$countTimeLoadingPlaceK+1]);
//                    //credit and debit account update
//                    $actualCreditAccount=Loading::where('atrnr',$atrnr)->first()->$accountCreditLoadingPlaceK;
//                    $actualTheoricalNumberPalletsActualCreditAccount=Palletsaccount::where('name', $actualCreditAccount)->first()->theoricalNumberPallets;
//                    $lastNumberPalletsTransferedCreditAccount=Palletsaccount::where('name', $actualCreditAccount)->first()->lastNumberPalletsTransfered;
//                    Palletsaccount::where('name', $actualCreditAccount)->update(['theoricalNumberPallets'=>$actualTheoricalNumberPalletsActualCreditAccount-$lastNumberPalletsTransferedCreditAccount]);
//                    $actualTheoricalNumberPalletsCreditAccount = Palletsaccount::where('name', $$accountCreditLoadingPlaceK)->first()->theoricalNumberPallets;
//                    Palletsaccount::where('name', $$accountCreditLoadingPlaceK)->update(['theoricalNumberPallets' => $actualTheoricalNumberPalletsCreditAccount + $$numberPalletsLoadingPlaceK, 'lastNumberPalletsTransfered'=>$$numberPalletsLoadingPlaceK]);
//
//                    $actualDebitAccount=Loading::where('atrnr',$atrnr)->first()->$accountDebitLoadingPlaceK;
//                    $actualTheoricalNumberPalletsActualDebitAccount=Palletsaccount::where('name', $actualDebitAccount)->first()->theoricalNumberPallets;
//                    $lastNumberPalletsTransferedDebitAccount=Palletsaccount::where('name', $actualDebitAccount)->first()->lastNumberPalletsTransfered;
//                    Palletsaccount::where('name', $actualDebitAccount)->update(['theoricalNumberPallets'=>$actualTheoricalNumberPalletsActualDebitAccount-$lastNumberPalletsTransferedDebitAccount]);
//                    $actualTheoricalNumberPalletsDebitAccount = Palletsaccount::where('name', $$accountDebitLoadingPlaceK)->first()->theoricalNumberPallets;
//                    Palletsaccount::where('name', $$accountDebitLoadingPlaceK)->update(['theoricalNumberPallets' => $actualTheoricalNumberPalletsDebitAccount - $$numberPalletsLoadingPlaceK, 'lastNumberPalletsTransfered'=>-$$numberPalletsLoadingPlaceK]);
//
//                    if($actualCreditAccount==$$accountCreditLoadingPlaceK &&$actualDebitAccount<>$$accountDebitLoadingPlaceK ){
//                        session()->flash('testFirstTime', 3);
//                    }elseif($actualCreditAccount<>$$accountCreditLoadingPlaceK &&$actualDebitAccount==$$accountDebitLoadingPlaceK){
//                        session()->flash('testFirstTime', 5);
//                    }else{
//                        session()->flash('testFirstTime', 2);
//                    }
//
//                    session()->flash('actualTheoricalNumberPalletsCreditAccount', $actualTheoricalNumberPalletsCreditAccount);
//                    session()->flash('lastNumberPalletsTransferedCreditAccount', $lastNumberPalletsTransferedCreditAccount);
//                    session()->flash('actualTheoricalNumberPalletsDebitAccount', $actualTheoricalNumberPalletsDebitAccount);
//                    session()->flash('lastNumberPalletsTransferedDebitAccount', $lastNumberPalletsTransferedDebitAccount);
//                }
//            }

            $this->numberPallets($atrnr, $$numberPalletsLoadingPlaceK, 'LoadingPlace', $k);
            $this->accountCredit($atrnr, $$accountCreditLoadingPlaceK, 'LoadingPlace', $k);
            $this->accountDebit($atrnr, $$accountDebitLoadingPlaceK, 'LoadingPlace', $k);
            $this->validateTransfer($atrnr, $$validateLoadingPlaceK, 'LoadingPlace', $k);

            //state
            $stateLoadingPlaceK = 'stateLoadingPlace' . $k;
            if (isset($$numberPalletsLoadingPlaceK) && isset($$accountCreditLoadingPlaceK) && isset($$accountDebitLoadingPlaceK) && (isset($documentsLoading) || !empty($actualDocumentsLoading)) && $$validateLoadingPlaceK == 'true') {
                $$stateLoadingPlaceK = 'Complete Validated';
                $actualRealNumberPalletsCreditAccount = Palletsaccount::where('name', $$accountCreditLoadingPlaceK)->first()->realNumberPallets;
                Palletsaccount::where('name', $$accountCreditLoadingPlaceK)->update(['realNumberPallets' => $actualRealNumberPalletsCreditAccount + $$numberPalletsLoadingPlaceK]);
                $actualRealNumberPalletsDebitAccount = Palletsaccount::where('name', $$accountDebitLoadingPlaceK)->first()->realNumberPallets;
                Palletsaccount::where('name', $$accountDebitLoadingPlaceK)->update(['realNumberPallets' => $actualRealNumberPalletsDebitAccount - $$numberPalletsLoadingPlaceK]);
            } elseif (isset($$numberPalletsLoadingPlaceK) && isset($$accountCreditLoadingPlaceK) && isset($$accountDebitLoadingPlaceK) && (isset($documentsLoading) || !empty($actualDocumentsLoading))) {
                $$stateLoadingPlaceK = 'Complete';
            } elseif (!isset($documentsLoading) || empty($actualDocumentsLoading)) {
                $$stateLoadingPlaceK = 'Waiting documents';
            } elseif (isset($$numberPalletsLoadingPlaceK) || isset($$accountCreditLoadingPlaceK) || isset($$accountDebitLoadingPlaceK) || (isset($documentsLoading) || !empty($actualDocumentsLoading))) {
                $$stateLoadingPlaceK = 'In progress';
            } else {
                $$stateLoadingPlaceK = 'Untreated';
            }
            Loading::where('atrnr', $atrnr)->update(['stateLoadingPlace' . $k => $$stateLoadingPlaceK]);

            session()->flash('messageSuccessSubmit', 'Successfully updated pallets location');
            session()->flash('openPanelLoading', 'openPanelLoading');

        } elseif (isset($closeSubmitLoadingModal)) {
            session()->flash('messageSuccessSubmit', 'Successfully updated pallets location');
            session()->flash('openPanelLoading', 'openPanelLoading');
        } elseif (isset($uploadLoading)) {
            ////////UPLOAD LOADING PANEL/////////
            $this->uploadDocuments($atrnr, $documentsLoading, 'Loading');

            //state
            for ($k = 1; $k <= $loading->numberLoadingPlace; $k++) {
                $stateLoadingPlaceK = 'stateLoadingPlace' . $k;

                $numberPalletsLoadingPlaceK = 'numberPalletsLoadingPlace' . $k;
                $$numberPalletsLoadingPlaceK = Input::get('numberPalletsLoadingPlace' . $k);
                $accountCreditLoadingPlaceK = 'accountCreditLoadingPlace' . $k;
                $$accountCreditLoadingPlaceK = Input::get('accountCreditLoadingPlace' . $k);
                $accountDebitLoadingPlaceK = 'accountDebitLoadingPlace' . $k;
                $$accountDebitLoadingPlaceK = Input::get('accountDebitLoadingPlace' . $k);
                $validateLoadingPlaceK = 'validateLoadingPlace' . $k;
                $$validateLoadingPlaceK = Input::get('validateLoadingPlace' . $k);

                if (isset($$numberPalletsLoadingPlaceK) && isset($$accountCreditLoadingPlaceK) && isset($$accountDebitLoadingPlaceK) && (isset($documentsLoading) || !empty($actualDocumentsLoading)) && $$validateLoadingPlaceK == 'true') {
                    $$stateLoadingPlaceK = 'Complete Validated';
                    $actualRealNumberPalletsCreditAccount = Palletsaccount::where('name', $$accountCreditLoadingPlaceK)->first()->realNumberPallets;
                    Palletsaccount::where('name', $$accountCreditLoadingPlaceK)->update(['realNumberPallets' => $actualRealNumberPalletsCreditAccount + $$numberPalletsLoadingPlaceK]);
                    $actualRealNumberPalletsDebitAccount = Palletsaccount::where('name', $$accountDebitLoadingPlaceK)->first()->realNumberPallets;
                    Palletsaccount::where('name', $$accountDebitLoadingPlaceK)->update(['realNumberPallets' => $actualRealNumberPalletsDebitAccount - $$numberPalletsLoadingPlaceK]);
                } elseif (isset($$numberPalletsLoadingPlaceK) && isset($$accountCreditLoadingPlaceK) && isset($$accountDebitLoadingPlaceK) && (isset($documentsLoading) || !empty($actualDocumentsLoading))) {
                    $$stateLoadingPlaceK = 'Complete';
                } elseif (!isset($documentsLoading) || empty($actualDocumentsLoading)) {
                    $$stateLoadingPlaceK = 'Waiting documents';
                } elseif (isset($$numberPalletsLoadingPlaceK) || isset($$accountCreditLoadingPlaceK) || isset($$accountDebitLoadingPlaceK) || (isset($documentsLoading) || !empty($actualDocumentsLoading))) {
                    $$stateLoadingPlaceK = 'In progress';
                } else {
                    $$stateLoadingPlaceK = 'Untreated';
                }
                Loading::where('atrnr', $atrnr)->update(['stateLoadingPlace' . $k => $$stateLoadingPlaceK]);
            }

            session()->flash('openPanelLoading', 'openPanelLoading');
        } elseif (isset($addOffloadingPlace)) {
            ////OFFLOADING PLACES/////
            $this->addPlace($atrnr, 'OffloadingPlace', $loading->numberOffloadingPlace);
            session()->flash('openPanelOffloading', 'openPanelOffloading');
        } elseif (isset($deleteOffloadingPlace)) {
            $this->deletePlace($atrnr, 'OffloadingPlace', $loading->numberOffloadingPlace);
            session()->flash('openPanelOffloading', 'openPanelOffloading');
        } elseif (isset($submitOffloading)) {
            $k = $submitOffloading;
            ///////SUBMIT OFFLOADING PANEL////////
            //number pallets
            $numberPalletsOffloadingPlaceK = 'numberPalletsOffloadingPlace' . $k;
            $$numberPalletsOffloadingPlaceK = Input::get('numberPalletsOffloadingPlace' . $k);

            //account credited
            $accountCreditOffloadingPlaceK = 'accountCreditOffloadingPlace' . $k;
            $$accountCreditOffloadingPlaceK = Input::get('accountCreditOffloadingPlace' . $k);

            //account debited
            $accountDebitOffloadingPlaceK = 'accountDebitOffloadingPlace' . $k;
            $$accountDebitOffloadingPlaceK = Input::get('accountDebitOffloadingPlace' . $k);

            //validated
            $validateOffloadingPlaceK = 'validateOffloadingPlace' . $k;
            $$validateOffloadingPlaceK = Input::get('validateOffloadingPlace' . $k);

            $actualCreditAccount = Loading::where('atrnr', $atrnr)->first()->$accountCreditOffloadingPlaceK; //credit account in memory
            $actualDebitAccount = Loading::where('atrnr', $atrnr)->first()->$accountDebitOffloadingPlaceK;
            $actualNumberPallets = Loading::where('atrnr', $atrnr)->first()->$numberPalletsOffloadingPlaceK;

            if (isset($$accountCreditOffloadingPlaceK) && isset($$accountDebitOffloadingPlaceK) && isset($$numberPalletsOffloadingPlaceK)) {
                if ($actualCreditAccount <> null && $actualDebitAccount <> null && $actualNumberPallets <> null) {
                    //the pallets transfer has alredy been done, we have to take it the former off and then to put the new one

                    //pallets number on the credit account in memory
                    $actualTheoricalNumberPalletsActualCreditAccount = Palletsaccount::where('name', $actualCreditAccount)->first()->theoricalNumberPallets;
                    Palletsaccount::where('name', $actualCreditAccount)->update(['theoricalNumberPallets' => $actualTheoricalNumberPalletsActualCreditAccount - $actualNumberPallets]);
                    //pallets number on the new credit account
                    $actualTheoricalNumberPalletsCreditAccount = Palletsaccount::where('name', $$accountCreditOffloadingPlaceK)->first()->theoricalNumberPallets;
                    Palletsaccount::where('name', $$accountCreditOffloadingPlaceK)->update(['theoricalNumberPallets' => $actualTheoricalNumberPalletsCreditAccount + $$numberPalletsOffloadingPlaceK]);
                    //pallets number on the debit account in memory
                    $actualTheoricalNumberPalletsActualDebitAccount = Palletsaccount::where('name', $actualDebitAccount)->first()->theoricalNumberPallets;
                    Palletsaccount::where('name', $actualDebitAccount)->update(['theoricalNumberPallets' => $actualTheoricalNumberPalletsActualDebitAccount + $actualNumberPallets]);
                    //pallets number on the new debit account
                    $actualTheoricalNumberPalletsDebitAccount = Palletsaccount::where('name', $$accountDebitOffloadingPlaceK)->first()->theoricalNumberPallets;
                    Palletsaccount::where('name', $$accountDebitOffloadingPlaceK)->update(['theoricalNumberPallets' => $actualTheoricalNumberPalletsDebitAccount - $$numberPalletsOffloadingPlaceK]);

                    if ($actualCreditAccount == $$accountCreditOffloadingPlaceK && $actualDebitAccount <> $$accountDebitOffloadingPlaceK) {
                        session()->flash('testFirstTime', 'sameC-diffD');
                        session()->flash('actualTheoricalNumberPalletsCreditAccount', $actualTheoricalNumberPalletsActualCreditAccount);
                        session()->flash('actualTheoricalNumberPalletsDebitAccount', $actualTheoricalNumberPalletsDebitAccount);
                    } elseif ($actualCreditAccount <> $$accountCreditOffloadingPlaceK && $actualDebitAccount == $$accountDebitOffloadingPlaceK) {
                        session()->flash('testFirstTime', 'diffC-sameD');
                        session()->flash('actualTheoricalNumberPalletsCreditAccount', $actualTheoricalNumberPalletsCreditAccount);
                        session()->flash('actualTheoricalNumberPalletsDebitAccount', $actualTheoricalNumberPalletsActualDebitAccount);
                    } elseif ($actualCreditAccount <> $$accountCreditOffloadingPlaceK && $actualDebitAccount <> $$accountDebitOffloadingPlaceK) {
                        session()->flash('testFirstTime', 'diffC-diffD');
                        session()->flash('actualTheoricalNumberPalletsCreditAccount', $actualTheoricalNumberPalletsCreditAccount);
                        session()->flash('actualTheoricalNumberPalletsDebitAccount', $actualTheoricalNumberPalletsDebitAccount);
                    } elseif ($actualCreditAccount == $$accountCreditOffloadingPlaceK && $actualDebitAccount == $$accountDebitOffloadingPlaceK) {
                        session()->flash('testFirstTime', 'sameC-sameD');
                        session()->flash('actualTheoricalNumberPalletsCreditAccount', $actualTheoricalNumberPalletsActualCreditAccount);
                        session()->flash('actualTheoricalNumberPalletsDebitAccount', $actualTheoricalNumberPalletsActualDebitAccount);
                    }
                    session()->flash('creditAccount', $$accountCreditOffloadingPlaceK);
                    session()->flash('debitAccount', $$accountDebitOffloadingPlaceK);
                    session()->flash('palletsNumber', $$numberPalletsOffloadingPlaceK);
                    session()->flash('lastPalletsNumber', $actualNumberPallets);
                } else {
                    //0 pallets have been done ->1st time we will do it -> credit and debit
                    $actualTheoricalNumberPalletsCreditAccount = Palletsaccount::where('name', $$accountCreditOffloadingPlaceK)->first()->theoricalNumberPallets;
                    Palletsaccount::where('name', $$accountCreditOffloadingPlaceK)->update(['theoricalNumberPallets' => $actualTheoricalNumberPalletsCreditAccount + $$numberPalletsOffloadingPlaceK, 'lastNumberPalletsTransfered' => $$numberPalletsOffloadingPlaceK]);
                    $actualTheoricalNumberPalletsDebitAccount = Palletsaccount::where('name', $$accountDebitOffloadingPlaceK)->first()->theoricalNumberPallets;
                    Palletsaccount::where('name', $$accountDebitOffloadingPlaceK)->update(['theoricalNumberPallets' => $actualTheoricalNumberPalletsDebitAccount - $$numberPalletsOffloadingPlaceK, 'lastNumberPalletsTransfered' => -$$numberPalletsOffloadingPlaceK]);

                    session()->flash('testFirstTime', '1stTime');
                    session()->flash('actualTheoricalNumberPalletsCreditAccount', $actualTheoricalNumberPalletsCreditAccount);
                    session()->flash('actualTheoricalNumberPalletsDebitAccount', $actualTheoricalNumberPalletsDebitAccount);
                    session()->flash('creditAccount', $$accountCreditOffloadingPlaceK);
                    session()->flash('debitAccount', $$accountDebitOffloadingPlaceK);
                    session()->flash('palletsNumber', $$numberPalletsOffloadingPlaceK);
                }
            } else {
                if ($actualNumberPallets <> null) {
                    session()->flash('messageErrorSubmit', 'Error ! The pallets number of the loading place ' . $k . " hasn't been updated. Pallets number on credit/debit accounts hasn't been updated too.");
                }
            }

            $this->numberPallets($atrnr, $$numberPalletsOffloadingPlaceK, 'OffloadingPlace', $k);
            $this->accountCredit($atrnr, $$accountCreditOffloadingPlaceK, 'OffloadingPlace', $k);
            $this->accountDebit($atrnr, $$accountDebitOffloadingPlaceK, 'OffloadingPlace', $k);
            $this->validateTransfer($atrnr, $$validateOffloadingPlaceK, 'OffloadingPlace', $k);

            //state
            $stateOffloadingPlaceK = 'stateOffloadingPlace' . $k;
            if (isset($$numberPalletsOffloadingPlaceK) && isset($$accountCreditOffloadingPlaceK) && isset($$accountDebitOffloadingPlaceK) && $$validateOffloadingPlaceK == 'true' && (isset($documentsOffloading) || !empty($actualDocumentsOffloading))) {

                $$stateOffloadingPlaceK = 'Complete Validated';
                $actualRealNumberPalletsCreditAccount = Palletsaccount::where('name', $$accountCreditOffloadingPlaceK)->first()->realNumberPallets;
                Palletsaccount::where('name', $$accountCreditOffloadingPlaceK)->update(['realNumberPallets' => $actualRealNumberPalletsCreditAccount + $$numberPalletsOffloadingPlaceK]);
                $actualRealNumberPalletsDebitAccount = Palletsaccount::where('name', $$accountDebitOffloadingPlaceK)->first()->realNumberPallets;
                Palletsaccount::where('name', $$accountDebitOffloadingPlaceK)->update(['realNumberPallets' => $actualRealNumberPalletsDebitAccount - $$numberPalletsOffloadingPlaceK]);
            } elseif (isset($$numberPalletsOffloadingPlaceK) && isset($$accountCreditOffloadingPlaceK) && isset($$accountDebitOffloadingPlaceK) && (isset($documentsOffloading) || !empty($actualDocumentsOffloading))) {
                $$stateOffloadingPlaceK = 'Complete';
            } elseif (!isset($documentsOffloading) || empty($actualDocumentsOffloading)) {
                $$stateOffloadingPlaceK = 'Waiting documents';
            } elseif (isset($$numberPalletsOffloadingPlaceK) || isset($$accountCreditOffloadingPlaceK) || isset($$accountDebitOffloadingPlaceK) || (isset($documentsOffloading) || !empty($actualDocumentsOffloading))) {
                $$stateOffloadingPlaceK = 'In progress';
            } else {
                $$stateOffloadingPlaceK = 'Untreated';
            }
            Loading::where('atrnr', $atrnr)->update(['stateOffloadingPlace' . $k => $$stateOffloadingPlaceK]);

            session()->flash('messageSuccessSubmit', 'Successfully updated pallets location');
            session()->flash('openPanelOffloading', 'openPanelOffloading');

        } elseif (isset($closeSubmitOffloadingModal)) {
            session()->flash('messageSuccessSubmit', 'Successfully updated pallets location');
            session()->flash('openPanelOffloading', 'openPanelOffloading');
        } elseif (isset($uploadOffloading)) {
            ////////UPLOAD OFFLOADING PANEL/////////
            $this->uploadDocuments($atrnr, $documentsOffloading, 'Offloading');

            //state
            for ($k = 1; $k <= $loading->numberLoadingPlace; $k++) {
                $stateOffloadingPlaceK = 'stateOffloadingPlace' . $k;

                $numberPalletsOffloadingPlaceK = 'numberPalletsOffloadingPlace' . $k;
                $$numberPalletsOffloadingPlaceK = Input::get('numberPalletsOffloadingPlace' . $k);
                $accountCreditOffloadingPlaceK = 'accountCreditOffloadingPlace' . $k;
                $$accountCreditOffloadingPlaceK = Input::get('accountCreditOffloadingPlace' . $k);
                $accountDebitOffloadingPlaceK = 'accountDebitOffloadingPlace' . $k;
                $$accountDebitOffloadingPlaceK = Input::get('accountDebitOffloadingPlace' . $k);
                $validateOffloadingPlaceK = 'validateOffloadingPlace' . $k;
                $$validateOffloadingPlaceK = Input::get('validateOffloadingPlace' . $k);

                if (isset($$numberPalletsOffloadingPlaceK) && isset($$accountCreditOffloadingPlaceK) && isset($$accountDebitOffloadingPlaceK) && (isset($documentsOffloading) || !empty($actualDocumentsOffloading)) && $$validateOffloadingPlaceK == 'true') {
                    $$stateOffloadingPlaceK = 'Complete Validated';
                    $actualRealNumberPalletsCreditAccount = Palletsaccount::where('name', $$accountCreditOffloadingPlaceK)->first()->realNumberPallets;
                    Palletsaccount::where('name', $$accountCreditOffloadingPlaceK)->update(['realNumberPallets' => $actualRealNumberPalletsCreditAccount + $$numberPalletsOffloadingPlaceK]);
                    $actualRealNumberPalletsDebitAccount = Palletsaccount::where('name', $$accountDebitOffloadingPlaceK)->first()->realNumberPallets;
                    Palletsaccount::where('name', $$accountDebitOffloadingPlaceK)->update(['realNumberPallets' => $actualRealNumberPalletsDebitAccount - $$numberPalletsOffloadingPlaceK]);
                } elseif (isset($$numberPalletsOffloadingPlaceK) && isset($$accountCreditOffloadingPlaceK) && isset($$accountDebitOffloadingPlaceK) && (isset($documentsOffloading) || !empty($actualDocumentsOffloading))) {
                    $$stateOffloadingPlaceK = 'Complete';
                } elseif (!isset($documentsOffloading) || empty($actualDocumentsOffloading)) {
                    $$stateOffloadingPlaceK = 'Waiting documents';
                } elseif (isset($$numberPalletsOffloadingPlaceK) || isset($$accountCreditOffloadingPlaceK) || isset($$accountDebitOffloadingPlaceK) || (isset($documentsOffloading) || !empty($actualDocumentsOffloading))) {
                    $$stateOffloadingPlaceK = 'In progress';
                } else {
                    $$stateOffloadingPlaceK = 'Untreated';
                }
                Loading::where('atrnr', $atrnr)->update(['stateOffloadingPlace' . $k => $$stateOffloadingPlaceK]);
            }
            session()->flash('openPanelOffloading', 'openPanelOffloading');

        } elseif (isset($deleteDocument)) {
            $this->deleteDocument($atrnr, $deleteDocument);
        }
        if(!isset($addLoadingPlace)||!isset($addOffloadingPlace)){
        $this->state($loading, $atrnr);
    }

        return redirect()->back();
    }

    public function numberPallets($atrnr, $numberPallets, $type, $k)
    {
        if (isset($numberPallets)) {
            Loading::where('atrnr', $atrnr)->update(['numberPallets' . $type . $k => $numberPallets]);
        }
    }

    public function accountCredit($atrnr, $accountCredit, $type, $k)
    {
        if (isset($accountCredit)) {
            Loading::where('atrnr', $atrnr)->update(['accountCredit' . $type . $k => $accountCredit]);
        }
    }

    public function accountDebit($atrnr, $accountDebit, $type, $k)
    {
        if (isset($accountDebit)) {
            Loading::where('atrnr', $atrnr)->update(['accountDebit' . $type . $k => $accountDebit]);
        }
    }

    public function accountTruck($atrnr, $anz, $account, $firstTime)
    {
        if (isset($account)) {
            Loading::where('atrnr', $atrnr)->update(['accountTruck' => $account]);
            if($firstTime==true){
                $palletsNumber=Palletsaccount::where('name',$account)->first()->theoricalNumberPallets;
                Palletsaccount::where('name',$account)->update(['theoricalNumberPallets' => $palletsNumber+$anz]);
            }
        }
    }

    public function validateTransfer($atrnr, $validate, $type, $k)
    {
        if ($validate == 'true') {
            $validate = true;
            if ($k = -1000) {
                Loading::where('atrnr', $atrnr)->update(['validate' . $type => $validate]);
            } else {
                Loading::where('atrnr', $atrnr)->update(['validate' . $type . $k => $validate]);
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
                    Storage::putFileAs('/proofsPallets/' . $atrnr . '/documents' . $type, $doc, $filename);
                    Document::firstOrCreate([
                        'name' => $filename,
                        'type' => $type
                    ])->loadings()->attach($atrnr);
                    session()->flash('messageSuccessUpload', 'Successfully uploaded the files');
                } else {
                    session()->flash('messageErrorUpload', 'Error ! The file type is not supported (png, jgp, pdf, msg, htm, rtf only');
                    return redirect()->back();
                }
            }
        }
    }

    public function documentsAssociated($actualDocuments_Loading, $type)
    {
        $actualDocs = [];
        if (!$actualDocuments_Loading->isEmpty()) {
            foreach ($actualDocuments_Loading as $actualDoc) {
                $actualDocuments = Document::where('id', $actualDoc->document_id)->first();
                if ($actualDocuments->type == $type) {
                    $actualDocs[] = $actualDocuments;
                }
            }
        }
        return $actualDocs;
    }

    public function deleteDocument($atrnr, $name)
    {
        $doc = Document::where('name', $name)->first();
        if ($doc->type == 'Loading') {
            session()->flash('openPanelLoading', 'openPanelLoading');
        } elseif ($doc->type == 'Offloading') {
            session()->flash('openPanelOffloading', 'openPanelOffloading');
        }
        $doc->loadings()->detach($atrnr);
        $path = '/proofsPallets/' . $atrnr . '/documentsOffloading/';
        Storage::delete($path . $name);
        $doc->delete();
        // redirect
        session()->flash('messageSuccessDeleteDocument', 'Successfully deleted the document!');
        return redirect()->back();
    }

    public function deletePlace($atrnr, $type, $numberPlace)
    {
        Loading::where('atrnr', $atrnr)->update(['number' . $type => $numberPlace - 1]);
        Loading::where('atrnr', $atrnr)->update(['numberPallets' . $type . $numberPlace => null]);
        Loading::where('atrnr', $atrnr)->update(['accountCredit' . $type . $numberPlace => null]);
        Loading::where('atrnr', $atrnr)->update(['accountDebit' . $type . $numberPlace => null]);
        Loading::where('atrnr', $atrnr)->update(['validate' . $type . $numberPlace => false]);
        Loading::where('atrnr', $atrnr)->update(['state' . $type . $numberPlace => 'Untreated']);
    }

    public function addPlace($atrnr, $type, $numberPlace)
    {
        Loading::where('atrnr', $atrnr)->update(['number' . $type => $numberPlace + 1]);
    }

    public function state($loading, $atrnr){
        //////STATE GENERAL////
        //state loading place
        if($loading->numberLoadingPlace>0){
            for($k=1; $k<=$loading->numberLoadingPlace; $k++){
                $stateLoadingPlaceK='stateLoadingPlace'.$k;
                $$stateLoadingPlaceK=$loading->$stateLoadingPlaceK;
                $stateCompleteValidated=0;
                $stateComplete=0;
                $stateWaitingDocuments=0;
                $stateInProgress=0;
                $stateUntreated=0;
                if($$stateLoadingPlaceK=='Complete Validated'){
                    $stateCompleteValidated++;
                }elseif($$stateLoadingPlaceK=='Complete'){
                    $stateComplete++;
                }elseif($$stateLoadingPlaceK=='Waiting documents'){
                    $stateWaitingDocuments++;
                }elseif($$stateLoadingPlaceK=='In progress'){
                    $stateInProgress++;
                }elseif($$stateLoadingPlaceK=='Untreated'){
                    $stateUntreated++;
                }
            }
            if($stateCompleteValidated==$loading->numberLoadingPlace){
                $stateLoadingPlace='Complete Validated';
            }elseif($stateWaitingDocuments==0 && $stateInProgress==0 && $stateUntreated==0){
                $stateLoadingPlace='Complete';
            }elseif($stateWaitingDocuments>0){
                $stateLoadingPlace='Waiting documents';
            }elseif($stateWaitingDocuments=0 && ($stateInProgress>0 || ($stateUntreated<$loading->numberLoadingPlace && $stateUntreated>0))){
                $stateLoadingPlace='In progress';
            }elseif($stateUntreated==$loading->numberLoadingPlace){
                $stateLoadingPlace='Untreated';
            }
        }

        //state offloading place
        if($loading->numberOffloadingPlace>0){
            for($k=1; $k<=$loading->numberOffloadingPlace; $k++){
                $stateOffloadingPlaceK='stateOffloadingPlace'.$k;
                $$stateOffloadingPlaceK=$loading->$stateOffloadingPlaceK;
                $stateCompleteValidated=0;
                $stateComplete=0;
                $stateWaitingDocuments=0;
                $stateInProgress=0;
                $stateUntreated=0;
                if($$stateOffloadingPlaceK=='Complete Validated'){
                    $stateCompleteValidated++;
                }elseif($$stateOffloadingPlaceK=='Complete'){
                    $stateComplete++;
                }elseif($$stateOffloadingPlaceK=='Waiting documents'){
                    $stateWaitingDocuments++;
                }elseif($$stateOffloadingPlaceK=='In progress'){
                    $stateInProgress++;
                }elseif($$stateOffloadingPlaceK=='Untreated'){
                    $stateUntreated++;
                }
            }
            if($stateCompleteValidated==$loading->numberLoadingPlace){
                $stateOffloadingPlace='Complete Validated';
            }elseif($stateWaitingDocuments==0 && $stateInProgress==0 && $stateUntreated==0){
                $stateOffloadingPlace='Complete';
            }elseif($stateWaitingDocuments>0){
                $stateOffloadingPlace='Waiting documents';
            }elseif($stateWaitingDocuments=0 && ($stateInProgress>0 || ($stateUntreated<$loading->numberLoadingPlace && $stateUntreated>0))){
                $stateOffloadingPlace='In progress';
            }elseif($stateUntreated==$loading->numberLoadingPlace){
                $stateOffloadingPlace='Untreated';
            }
        }

        //general state
        $stateTruck=$loading->stateTruck;
        if($stateTruck=='Complete Validated' && $stateOffloadingPlace=='Complete Validated'&& $stateLoadingPlace=='Complete Validated'){
            $state='Complete Validated';
        }elseif(($stateTruck=='Complete Validated'||$stateTruck=='Complete') && ($stateOffloadingPlace=='Complete Validated'||$stateOffloadingPlace=='Complete')&& ($stateLoadingPlace=='Complete' || $stateLoadingPlace=='Complete Validated')){
            $state='Complete';
        }elseif($stateTruck=='Waiting documents' || $stateOffloadingPlace=='Waiting documents'|| $stateLoadingPlace=='Waiting documents'){
            $state='Waiting documents';
        }elseif($stateTruck=='Untreated' && $stateOffloadingPlace=='Untreated'&& $stateLoadingPlace=='Untreated'){
            $state='Untreated';
        }elseif($stateTruck<>'Waiting documents' && $stateOffloadingPlace<>'Waiting documents'&& $stateLoadingPlace<>'Waiting documents'){
            $state='In progress';
        }
        Loading::where('atrnr', $atrnr)->update(['state' => $state]);
    }
}
