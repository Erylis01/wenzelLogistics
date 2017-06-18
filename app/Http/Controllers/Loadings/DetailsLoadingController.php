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
            $loading = DB::table('loadings')->where('atrnr', '=', $atrnr)->first();

            //////PALLETS PANEL//////
            //all pallets account
            $listPalletsAccounts = DB::table('palletsaccounts')->get();

//            //truck
//            $listPalletsAccountsCarrier = Palletsaccount::where('type', 'Carrier')->get();
//            $accountTruck = $detailsLoading->accountTruck;
//            $stateTruck = $detailsLoading->stateTruck;
//            $validateTruckM = $detailsLoading->validateTruck;

            //looking for the account that contains the license plate if it's set
            if ($loading->kennzeichen == "") {
                $licensePlate = 'OTHER';
            } else {
                $licensePlate = $loading->kennzeichen;
            }
//if (Truck::where('name', trim(explode(',', $subfrachter)[0]))->where('licensePlate', $licensePlate)->first() <> null){
//            $namePalletsAccountTruck = Truck::where('name', trim(explode(',', $subfrachter)[0]))->where('licensePlate', $licensePlate)->first()->palletsaccount_name;
//            if ($namePalletsAccountTruck <> null) {
//                $palletsAccountFavoriteTruck = Palletsaccount::where('name', $namePalletsAccountTruck)->first()->name;
//            }
//        }

            //loading panel
            $totalPalletsLoadingPlace = 0;
            for ($k = 1; $k <= $loading->numberLoadingPlace; $k++) {
                $numberPalletsLoadingPlaceK = 'numberPalletsLoadingPlace' . $k;
                $$numberPalletsLoadingPlaceK = $loading->$numberPalletsLoadingPlaceK;
                $totalPalletsLoadingPlace = $totalPalletsLoadingPlace + $$numberPalletsLoadingPlaceK;
            }

            //looking for the account of the warehouse which zipcode is plz beladestelle
            if (Warehouse::where('zipcode', $loading->plzb)->first() <> null) {
                $idWarehouseZipcodeLoadingPlace = Warehouse::where('zipcode', $loading->plzb)->first()->id;
                $accountZipcodeLoadingPlace1 = DB::table('palletsaccount_warehouse')->where('warehouse_id', $idWarehouseZipcodeLoadingPlace)->first();
                if ($accountZipcodeLoadingPlace1 <> null) {
                    $accountZipcodeLoadingPlace = Palletsaccount::where('id', $accountZipcodeLoadingPlace1->palletsaccount_id)->first()->name;

                }
            };

//            //offloading panel
//            $totalPalletsOffloadingPlace = 0;
//            for ($k = 1; $k <= $numberOffloadingPlace; $k++) {
//                $stateOffloadingPlaceK = 'stateOffloadingPlace' . $k;
//                $$stateOffloadingPlaceK = $detailsLoading->$stateOffloadingPlaceK;
//                $validateOffloadingPlaceMK = 'validateOffloadingPlaceM' . $k;
//                $$validateOffloadingPlaceMK = $detailsLoading->$validateOffloadingPlaceMK;
//                $numberPalletsOffloadingPlaceK = 'numberPalletsOffloadingPlace' . $k;
//                $$numberPalletsOffloadingPlaceK = $detailsLoading->$numberPalletsOffloadingPlaceK;
//                $accountDebitOffloadingPlaceK = 'accountDebitOffloadingPlace' . $k;
//                $$accountDebitOffloadingPlaceK = $detailsLoading->$accountDebitOffloadingPlaceK;
//                $accountCreditOffloadingPlaceK = 'accountCreditOffloadingPlace' . $k;
//                $$accountCreditOffloadingPlaceK = $detailsLoading->$accountCreditOffloadingPlaceK;
//                $totalPalletsOffloadingPlace = $totalPalletsOffloadingPlace + $$numberPalletsOffloadingPlaceK;
//            }
//
//            if (Warehouse::where('zipcode', $plze)->first() <> null) {
//                $idWarehouseZipcodeOffloadingPlace = Warehouse::where('zipcode', $plze)->first()->id;
//                $accountZipcodeOffloadingPlace1 = DB::table('palletsaccount_warehouse')->where('warehouse_id', $idWarehouseZipcodeOffloadingPlace)->first();
//                if ($accountZipcodeOffloadingPlace1 <> null) {
//                    $accountZipcodeOffloadingPlace = Palletsaccount::where('id', $accountZipcodeOffloadingPlace1->palletsaccount_id)->first()->name;
//                }
//            };

            //offloading-loading-truck
            $filesNamesLoadingPlace = $this->actualDocuments($loading->atrnr, 'Loading');

//            $filesNamesOffloadingPlace = $this->actualDocuments($loading->atrnr, 'Offloading');
//            $filesNamesTruck = $this->actualDocuments($loading->atrnr, 'Truck');

            return view('loadings.detailsLoading', compact('loading', 'listPalletsAccounts',
                'filesNamesLoadingPlace', 'accountZipcodeLoadingPlace', 'totalPalletsLoadingPlace',
                'filesNamesOffloadingPlace', 'accountZipcodeOffloadingPlace', 'totalPalletsOffloadingPlace',
                'filesNamesTruck', 'stateTruck', 'accountTruck', 'validateTruckM', 'palletsAccountFavoriteTruck', 'listPalletsAccountsCarrier'
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

    public function submitUpdateUpload($atrnr, Request $request)
    {
        $loading = Loading::where('atrnr', $atrnr)->first();

        //buttons
        $update=Input::get('update');
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

//        $uploadTruck=Input::get('uploadTruck');
        //        $submitTruck=Input::get('submitTruck');
        $deleteDocument = Input::get('deleteDocument');

        //data
        $state = $loading->state;

        $listPalletsAccounts = DB::table('palletsaccounts')->get();
        $totalPalletsLoadingPlace = 0;
        for ($k = 1; $k <= $loading->numberLoadingPlace; $k++) {
            $numberPalletsLoadingPlaceK = 'numberPalletsLoadingPlace' . $k;
            $$numberPalletsLoadingPlaceK = $loading->$numberPalletsLoadingPlaceK;
            $totalPalletsLoadingPlace = $totalPalletsLoadingPlace + $$numberPalletsLoadingPlaceK;
        }

        //looking for the account of the warehouse which zipcode is plz beladestelle
        if (Warehouse::where('zipcode', $loading->plzb)->first() <> null) {
            $idWarehouseZipcodeLoadingPlace = Warehouse::where('zipcode', $loading->plzb)->first()->id;
            $accountZipcodeLoadingPlace1 = DB::table('palletsaccount_warehouse')->where('warehouse_id', $idWarehouseZipcodeLoadingPlace)->first();
            if ($accountZipcodeLoadingPlace1 <> null) {
                $accountZipcodeLoadingPlace = Palletsaccount::where('id', $accountZipcodeLoadingPlace1->palletsaccount_id)->first()->name;

            }
        };

        //offloading panel
//        $totalPalletsOffloadingPlace = 0;
//        if (Warehouse::where('zipcode', $loading->plze)->first() <> null) {
//            $idWarehouseZipcodeOffloadingPlace = Warehouse::where('zipcode', $loading->plze)->first()->id;
//            $accountZipcodeOffloadingPlace1 = DB::table('palletsaccount_warehouse')->where('warehouse_id', $idWarehouseZipcodeOffloadingPlace)->first();
//            if ($accountZipcodeOffloadingPlace1 <> null) {
//                $accountZipcodeOffloadingPlace = Palletsaccount::where('id', $accountZipcodeOffloadingPlace1->palletsaccount_id)->first()->name;
//            }
//        };

//documents
        $documentsLoading = $request->file('documentsLoading');
        $documentsOffloading = $request->file('documentsOffloading');

//        //documents already associated to the loading TRUCK ?
//        $actualDocuments_LoadingTruck = DB::table('document_loading')->where('loading_id', $atrnr)->get();
//        $actualDocumentsTruck = $this->documentsAssociated($actualDocuments_LoadingTruck, 'Truck');
//        //documents
//        $documentsTruck = $request->file('documentsTruck');

        ///////TRUCK/////

        if(isset($update)){
            $this->update($request, $loading->atrnr);
        }
        elseif(isset($addLoadingPlace)) {
            //////LOADING PLACES//////
            $this->addPlace($loading->atrnr, 'LoadingPlace', $loading->numberLoadingPlace);
            session()->flash('openPanelLoading', 'openPanelLoading');
            return redirect()->back();
        } elseif (isset($deleteLoadingPlace)) {
            $this->deletePlace($loading, 'LoadingPlace', $loading->numberLoadingPlace);
            session()->flash('openPanelLoading', 'openPanelLoading');
            return redirect()->back();
        } elseif (isset($submitLoading)) {
            ///////SUBMIT LOADING PANEL////////
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
                if($listDataK[1][4]=='true'){
                    Loading::where('atrnr', $loading->atrnr)->update(['validateLoadingPlace' . $k => true]);
                }else{
                    Loading::where('atrnr', $loading->atrnr)->update(['validateLoadingPlace' . $k => false]);
                }
                $loading = Loading::where('atrnr', $atrnr)->first();

                session()->flash('thPalletsNumberCreditAccount', Palletsaccount::where('name', $listDataK[1][1])->first()->theoricalNumberPallets);
                session()->flash('thPalletsNumberDebitAccount', Palletsaccount::where('name', $listDataK[1][2])->first()->theoricalNumberPallets);
                session()->flash('openPanelLoading', 'openPanelLoading');

                return view('loadings.detailsLoading', compact('loading', 'listPalletsAccounts',
                    'filesNamesLoadingPlace', 'accountZipcodeLoadingPlace', 'totalPalletsLoadingPlace', 'submitLoading'
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
            session()->flash('messageUpdatePalletstransfer', 'Successfully updated pallets transfer');

            $loading = Loading::where('atrnr', $atrnr)->first();
            $$listDataK[0][3] = $loading->$listDataK[0][3];
            if ($$listDataK[0][3] == 'Complete Validated') {
                session()->flash('palletsNumber', $listDataK[1][0]);
                session()->flash('creditAccount', $listDataK[1][1]);
                session()->flash('debitAccount', $listDataK[1][2]);
                session()->flash('realPalletsNumberCreditAccount', Palletsaccount::where('name', $listDataK[1][1])->first()->realNumberPallets);
                session()->flash('realPalletsNumberDebitAccount', Palletsaccount::where('name', $listDataK[1][2])->first()->realNumberPallets);
                return view('loadings.detailsLoading', compact('loading', 'listPalletsAccounts',
                    'filesNamesLoadingPlace', 'accountZipcodeLoadingPlace', 'totalPalletsLoadingPlace', 'okSubmitLoadingModal'));
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
            session()->flash('messageUpdateValidateLoadingPlace', 'VALIDATE ! Successfully updated and validated pallets transfer for the loading place ' . $k);
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
                Loading::where('atrnr', $loading->atrnr)->update(['stateLoadingPlace'.$k => $listDataK[1][3]]);
            }
            session()->flash('openPanelLoading', 'openPanelLoading');
            return redirect()->back();
        } elseif (isset($deleteDocument)) {
            $typePlace = trim(explode('-', $deleteDocument)[1]);
            $name = trim(explode('-', $deleteDocument)[0]);
            $this->deleteDocument($loading, $typePlace, $name);
            session()->flash('openPanel' . $typePlace, 'openPanel');
            return redirect()->back();
        }
    }

    public function actualDocuments($atrnr, $type)
    {
        $listFilesAssociated = DB::table('document_loading')->where('loading_id', $atrnr)->get();
        $filesNames = [];
        if (!$listFilesAssociated->isEmpty()) {
            foreach ($listFilesAssociated as $fileAssociated) {
                $file = Document::where('id', $fileAssociated->document_id)->first();
                if ($file->type == $type) {
                    $filesNames[] = $file->name;
                }
            }
        }
        return $filesNames;
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

//        if ($$validateLoadingPlaceK == null) {
//            $validateLoadingPlaceMK = 'validateLoadingPlaceM' . $k;
//            $listTitleVariable[] = $validateLoadingPlaceMK;
//            $$validateLoadingPlaceMK = $loading->$validateLoadingPlaceK;
//            $listContentVariable[] = $$validateLoadingPlaceMK;
//        }
        $listData = [$listTitleVariable, $listContentVariable];
        return $listData;
    }

    public function getDataOffloadingPlace($loading, $k)
    {
        $numberPalletsOffloadingPlaceK = 'numberPalletsOffloadingPlace' . $k;
        $$numberPalletsOffloadingPlaceK = Input::get('numberPalletsOffloadingPlace' . $k);
        $accountCreditOffloadingPlaceK = 'accountCreditOffloadingPlace' . $k;
        $$accountCreditOffloadingPlaceK = Input::get('accountCreditOffloadingPlace' . $k);
        $accountDebitOffloadingPlaceK = 'accountDebitOffloadingPlace' . $k;
        $$accountDebitOffloadingPlaceK = Input::get('accountDebitOffloadingPlace' . $k);
        $validateOffloadingPlaceK = 'validateOffloadingPlace' . $k;
        $$validateOffloadingPlaceK = Input::get('validateOffloadingPlace' . $k);

        if ($$validateOffloadingPlaceK == null) {
            $validateOffloadingPlaceMK = 'validateOffloadingPlaceM' . $k;
            $$validateOffloadingPlaceMK = $loading->$validateOffloadingPlaceK;
        }
    }

    public function inverseRealPalletsNumber($creditAccount, $debitAccount, $palletsNumber)
    {
        $actualRealPalletsNumberCreditAccount = Palletsaccount::where('name', $creditAccount)->first()->realNumberPallets;
        Palletsaccount::where('name', $creditAccount)->update(['realNumberPallets' => $actualRealPalletsNumberCreditAccount - $palletsNumber]);
        $actualRealPalletsNumberDebitAccount = Palletsaccount::where('name', $debitAccount)->first()->realNumberPallets;
        Palletsaccount::where('name', $debitAccount)->update(['realNumberPallets' => $actualRealPalletsNumberDebitAccount + $palletsNumber]);
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

    public function deleteDocument($loading, $typePlace, $name)
    {
        $doc = Document::where('name', $name)->where('type', $typePlace)->first();

        $doc->loadings()->detach($loading->atrnr);
        $path = '/proofsPallets/' . $loading->atrnr . '/documents' . $typePlace . 'Place/';

        Storage::delete($path . $name);
        $actualLoadingsAssociated = DB::table('document_loading')->where('document_id', $doc->id)->get();
        if ($actualLoadingsAssociated->isEmpty()) {
            $doc->delete();
        }
        $actualDocuments_Loadings = DB::table('document_loading')->where('loading_id', $loading->atrnr)->get();
        if ($actualDocuments_Loadings->isEmpty()) {
            for ($k = 1; $k <= $loading->numberLoadingPlace; $k++) {
                $stateLoadingPlaceK = 'stateLoadingPlace' . $k;
                $$stateLoadingPlaceK = $loading->$stateLoadingPlaceK;
                if ($$stateLoadingPlaceK == 'Complete Validated') {
                    $accountCreditLoadingPlaceK = 'accountCreditLoadingPlace' . $k;
                    $$accountCreditLoadingPlaceK = $loading->$accountCreditLoadingPlaceK;
                    $accountDebitLoadingPlaceK = 'accountDebitLoadingPlace' . $k;
                    $$accountDebitLoadingPlaceK = $loading->$accountDebitLoadingPlaceK;
                    $numberPalletsLoadingPlaceK = 'numberPalletsLoadingPlace' . $k;
                    $$numberPalletsLoadingPlaceK = $loading->$numberPalletsLoadingPlaceK;

                    if(isset($$accountCreditLoadingPlaceK)&&isset($$accountDebitLoadingPlaceK)&&isset($$numberPalletsLoadingPlaceK)){
                        $this->inverseRealPalletsNumber($$accountCreditLoadingPlaceK, $$accountDebitLoadingPlaceK, $$numberPalletsLoadingPlaceK);
                        session()->flash('messageSuccessDeleteDocument', 'No more documents. The confirmed pallets number on both account has been updated');
                    }
                }
                $$stateLoadingPlaceK = 'Waiting documents';
                Loading::where('atrnr', $loading->atrnr)->update(['stateLoadingPlace'.$k => $$stateLoadingPlaceK]);
                Loading::where('atrnr', $loading->atrnr)->update(['validateLoadingPlace' . $k => false]);
            }
        } else {
            foreach ($actualDocuments_Loadings as $actualDoc) {
                $doc = Document::where('id', $actualDoc->document_id)->first();
                if ($doc->type == $typePlace) {
                    $listDocs[] = $doc;
                    if (empty($listDocs) && $typePlace == 'LoadingPlace') {
                        for ($k = 1; $k <= $loading->numberLoadingPlace; $k++) {
                            $stateLoadingPlaceK = 'stateLoadingPlace' . $k;
                            $$stateLoadingPlaceK = $loading->$stateLoadingPlaceK;
                            if ($$stateLoadingPlaceK == 'Complete Validated') {
                                $accountCreditLoadingPlaceK = 'accountCreditLoadingPlace' . $k;
                                $$accountCreditLoadingPlaceK = $loading->$accountCreditLoadingPlaceK;
                                $accountDebitLoadingPlaceK = 'accountDebitLoadingPlaceK' . $k;
                                $$accountDebitLoadingPlaceK = $loading->$accountDebitLoadingPlaceK;
                                $numberPalletsLoadingPlaceK = 'numberPalletsLoadingPlaceK' . $k;
                                $$numberPalletsLoadingPlaceK = $loading->$numberPalletsLoadingPlaceK;
                                dd($loading,isset($$accountCreditLoadingPlaceK)&&isset($$accountDebitLoadingPlaceK)&&isset($$numberPalletsLoadingPlaceK));
//                    if(isset($$accountCreditLoadingPlaceK)&&isset($$accountDebitLoadingPlaceK)&&isset($$numberPalletsLoadingPlaceK)){
                                $this->inverseRealPalletsNumber($$accountCreditLoadingPlaceK, $$accountDebitLoadingPlaceK, $$numberPalletsLoadingPlaceK);
//                    }
                            }
                            $$stateLoadingPlaceK = 'Waiting documents';
                            Loading::where('atrnr', $loading->atrnr)->update(['stateLoadingPlace'.$k => $$stateLoadingPlaceK]);
                            Loading::where('atrnr', $loading->atrnr)->update(['validateLoadingPlace' . $k => false]);
                        }
                    }
                }
            }
        }
    }

    public function deletePlace($loading, $type, $numberPlace)
    {
        $numberPalletsK = 'numberPallets' . $type . $numberPlace;
        $creditAccountK = 'creditAccount' . $type . $numberPlace;
        $debitAccountK = 'debitAccount' . $type . $numberPlace;
        if (isset($loading->$creditAccountK) && isset($loading->$debitAccountK) && isset($loading->$numberPalletsK)) {
            $actualPalletsNumberCreditAccount = Palletsaccount::where('name', $loading->$creditAccountK)->first()->theoricalNumberPallets;
            Palletsaccount::where('name', $loading->$creditAccountK)->update(['theoricalNumberPallets' => $actualPalletsNumberCreditAccount - $loading->$numberPalletsK]);
            $actualPalletsNumberDebitAccount = Palletsaccount::where('name', $loading->$debitAccountK)->first()->theoricalNumberPallets;
            Palletsaccount::where('name', $loading->$debitAccountK)->update(['theoricalNumberPallets' => $actualPalletsNumberDebitAccount + $loading->$numberPalletsK]);

        }
        $stateK = 'state' . $type . $numberPlace;
        $$stateK = $loading->$stateK;
        if ($$stateK == 'Complete Validate') {
            $this->inverseRealPalletsNumber($loading->$creditAccountK, $loading->$debitAccountK, $loading->$numberPalletsK);
        }
        Loading::where('atrnr', $loading->atrnr)->update(['number' . $type => $numberPlace - 1]);
        Loading::where('atrnr', $loading->atrnr)->update(['numberPallets' . $type . $numberPlace => null]);
        Loading::where('atrnr', $loading->atrnr)->update(['accountCredit' . $type . $numberPlace => null]);
        Loading::where('atrnr', $loading->atrnr)->update(['accountDebit' . $type . $numberPlace => null]);
        Loading::where('atrnr', $loading->atrnr)->update(['validate' . $type . $numberPlace => false]);
        Loading::where('atrnr', $loading->atrnr)->update(['state' . $type . $numberPlace => 'Untreated']);
    }

    public function addPlace($atrnr, $type, $numberPlace)
    {
        Loading::where('atrnr', $atrnr)->update(['number' . $type => $numberPlace + 1]);
    }

//    public function state($loading, $atrnr)
//    {
//        //////STATE GENERAL////
//        //state loading place
//        if ($loading->numberLoadingPlace > 0) {
//            for ($k = 1; $k <= $loading->numberLoadingPlace; $k++) {
//                $stateLoadingPlaceK = 'stateLoadingPlace' . $k;
//                $$stateLoadingPlaceK = $loading->$stateLoadingPlaceK;
//                $stateCompleteValidated = 0;
//                $stateComplete = 0;
//                $stateWaitingDocuments = 0;
//                $stateInProgress = 0;
//                $stateUntreated = 0;
//                if ($$stateLoadingPlaceK == 'Complete Validated') {
//                    $stateCompleteValidated++;
//                } elseif ($$stateLoadingPlaceK == 'Complete') {
//                    $stateComplete++;
//                } elseif ($$stateLoadingPlaceK == 'Waiting documents') {
//                    $stateWaitingDocuments++;
//                } elseif ($$stateLoadingPlaceK == 'In progress') {
//                    $stateInProgress++;
//                } elseif ($$stateLoadingPlaceK == 'Untreated') {
//                    $stateUntreated++;
//                }
//            }
//            if ($stateCompleteValidated == $loading->numberLoadingPlace) {
//                $stateLoadingPlace = 'Complete Validated';
//            } elseif ($stateWaitingDocuments == 0 && $stateInProgress == 0 && $stateUntreated == 0) {
//                $stateLoadingPlace = 'Complete';
//            } elseif ($stateWaitingDocuments > 0) {
//                $stateLoadingPlace = 'Waiting documents';
//            } elseif ($stateWaitingDocuments = 0 && ($stateInProgress > 0 || ($stateUntreated < $loading->numberLoadingPlace && $stateUntreated > 0))) {
//                $stateLoadingPlace = 'In progress';
//            } elseif ($stateUntreated == $loading->numberLoadingPlace) {
//                $stateLoadingPlace = 'Untreated';
//            }
//        }
//
//        //state offloading place
//        if ($loading->numberOffloadingPlace > 0) {
//            for ($k = 1; $k <= $loading->numberOffloadingPlace; $k++) {
//                $stateOffloadingPlaceK = 'stateOffloadingPlace' . $k;
//                $$stateOffloadingPlaceK = $loading->$stateOffloadingPlaceK;
//                $stateCompleteValidated = 0;
//                $stateComplete = 0;
//                $stateWaitingDocuments = 0;
//                $stateInProgress = 0;
//                $stateUntreated = 0;
//                if ($$stateOffloadingPlaceK == 'Complete Validated') {
//                    $stateCompleteValidated++;
//                } elseif ($$stateOffloadingPlaceK == 'Complete') {
//                    $stateComplete++;
//                } elseif ($$stateOffloadingPlaceK == 'Waiting documents') {
//                    $stateWaitingDocuments++;
//                } elseif ($$stateOffloadingPlaceK == 'In progress') {
//                    $stateInProgress++;
//                } elseif ($$stateOffloadingPlaceK == 'Untreated') {
//                    $stateUntreated++;
//                }
//            }
//            if ($stateCompleteValidated == $loading->numberLoadingPlace) {
//                $stateOffloadingPlace = 'Complete Validated';
//            } elseif ($stateWaitingDocuments == 0 && $stateInProgress == 0 && $stateUntreated == 0) {
//                $stateOffloadingPlace = 'Complete';
//            } elseif ($stateWaitingDocuments > 0) {
//                $stateOffloadingPlace = 'Waiting documents';
//            } elseif ($stateWaitingDocuments = 0 && ($stateInProgress > 0 || ($stateUntreated < $loading->numberLoadingPlace && $stateUntreated > 0))) {
//                $stateOffloadingPlace = 'In progress';
//            } elseif ($stateUntreated == $loading->numberLoadingPlace) {
//                $stateOffloadingPlace = 'Untreated';
//            }
//        }
//
//        //general state
//        $stateTruck = $loading->stateTruck;
//        if (isset($stateOffloadingPlace) && isset($stateLoadingPlace)) {
//            if ($stateTruck == 'Complete Validated' && $stateOffloadingPlace == 'Complete Validated' && $stateLoadingPlace == 'Complete Validated') {
//                $state = 'Complete Validated';
//            } elseif (($stateTruck == 'Complete Validated' || $stateTruck == 'Complete') && ($stateOffloadingPlace == 'Complete Validated' || $stateOffloadingPlace == 'Complete') && ($stateLoadingPlace == 'Complete' || $stateLoadingPlace == 'Complete Validated')) {
//                $state = 'Complete';
//            } elseif ($stateTruck == 'Waiting documents' || $stateOffloadingPlace == 'Waiting documents' || $stateLoadingPlace == 'Waiting documents') {
//                $state = 'Waiting documents';
//            } elseif ($stateTruck == 'Untreated' && $stateOffloadingPlace == 'Untreated' && $stateLoadingPlace == 'Untreated') {
//                $state = 'Untreated';
//            } elseif ($stateTruck <> 'Waiting documents' && $stateOffloadingPlace <> 'Waiting documents' && $stateLoadingPlace <> 'Waiting documents') {
//                $state = 'In progress';
//            }
//        } elseif (isset($stateOffloadingPlace) && !isset($stateLoadingPlace)) {
//            if ($stateTruck == 'Complete Validated' && $stateOffloadingPlace == 'Complete Validated') {
//                $state = 'Complete Validated';
//            } elseif (($stateTruck == 'Complete Validated' || $stateTruck == 'Complete') && ($stateOffloadingPlace == 'Complete Validated' || $stateOffloadingPlace == 'Complete')) {
//                $state = 'Complete';
//            } elseif ($stateTruck == 'Waiting documents' || $stateOffloadingPlace == 'Waiting documents') {
//                $state = 'Waiting documents';
//            } elseif ($stateTruck == 'Untreated' && $stateOffloadingPlace == 'Untreated') {
//                $state = 'Untreated';
//            } elseif ($stateTruck <> 'Waiting documents' && $stateOffloadingPlace <> 'Waiting documents') {
//                $state = 'In progress';
//            }
//        } elseif (!isset($stateOffloadingPlace) && isset($stateLoadingPlace)) {
//            if ($stateTruck == 'Complete Validated' && $stateLoadingPlace == 'Complete Validated') {
//                $state = 'Complete Validated';
//            } elseif (($stateTruck == 'Complete Validated' || $stateTruck == 'Complete') && ($stateLoadingPlace == 'Complete Validated' || $stateLoadingPlace == 'Complete')) {
//                $state = 'Complete';
//            } elseif ($stateTruck == 'Waiting documents' || $stateLoadingPlace == 'Waiting documents') {
//                $state = 'Waiting documents';
//            } elseif ($stateTruck == 'Untreated' && $stateLoadingPlace == 'Untreated') {
//                $state = 'Untreated';
//            } elseif ($stateTruck <> 'Waiting documents' && $stateLoadingPlace <> 'Waiting documents') {
//                $state = 'In progress';
//            }
//        } elseif (!isset($stateOffloadingPlace) && !isset($stateLoadingPlace)) {
//            $state = $stateTruck;
//        }
//
//        Loading::where('atrnr', $atrnr)->update(['state' => $state]);
//    }
}
