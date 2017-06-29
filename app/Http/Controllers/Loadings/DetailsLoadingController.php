<?php

namespace App\Http\Controllers;

use App\Document;
use App\Error;
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
    public function show(Request $request, $atrnr)
    {
        if (Auth::check()) {
            ////////PANEL INFO///////
            $loading = Loading::where('atrnr', '=', $atrnr)->first();

            //////PALLETS PANEL//////
            //all pallets account
            $listPalletsAccounts = Palletsaccount::get();

            if (request()->has('sortby') && request()->has('order')) {
                $sortby = $request->get('sortby'); // Order by what column?
                $order = $request->get('order'); // Order direction: asc or desc
                $listPalletstransfers = Palletstransfer::where('loading_atrnr', $atrnr)->orderBy($sortby, $order)->get();
                session()->flash('openPanelPallets', 'openPanelPallets');
            }else{
                $listPalletstransfers = Palletstransfer::where('loading_atrnr', $atrnr)->orderBy('id', 'asc')->get();
            }
            $listPalletstransfersNormal=Palletstransfer::where('loading_atrnr', $atrnr)->where(function ($q){
                $q->where('type', 'Deposit_Only')->orWhere('type', 'Withdrawal_Only')->orWhere('type', 'Withdrawal-Deposit')->orWhere('type', 'Deposit-Withdrawal');
            })->orderBy('id', 'asc')->get();
            $listPalletstransfersCorrecting=Palletstransfer::where('loading_atrnr', $atrnr)->where(function ($q){
                $q->where('type', 'Purchase_Int')->orWhere('type', 'Purchase_Ext')->orWhere('type', 'Sale_Int')->orWhere('type', 'Sale_Ext')->orWhere('type', 'Other');
            })->orderBy('id', 'asc')->get();
//            //truck
//            $listPalletsaccountsCarrier = Palletsaccount::where('type', 'Carrier')->get();
//            //looking for the account that contains the license plate if it's set
//            if ($loading->kennzeichen == "") {
//                $licensePlate = 'OTHER';
//            } else {
//                $licensePlate = $loading->kennzeichen;
//            }
//            if (Truck::where('name', trim(explode(',', $loading->subfrachter)[0]))->where('licensePlate', $licensePlate)->first() <> null) {
//                $namePalletsAccountTruck = Truck::where('name', trim(explode(',', $loading->subfrachter)[0]))->where('licensePlate', $licensePlate)->first()->palletsaccount_name;
//                if ($namePalletsAccountTruck <> null) {
//                    $palletsAccountFavoriteTruck = Palletsaccount::where('name', $namePalletsAccountTruck)->first()->name;
//                }
//            }

            //link to the mother loading of the subloading
            if (substr_count($loading->atrnr, '-') <> 0) {
                $atrnr1 = explode('-', $loading->atrnr)[0];
                $atrnr2 = array_slice(explode('-', $loading->atrnr), 1);
                $atrnr2 = implode('-', $atrnr2);
            }

            return view('loadings.detailsLoading', compact('sortby', 'order','loading', 'atrnr1', 'atrnr2', 'listPalletsAccounts', 'listPalletstransfers', 'listPalletstransfersNormal', 'listPalletstransfersCorrecting'
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
                Loading::where('atrnr', 'like', $atrnr . '%')->update(['reasonUpdatePT' => $reasonUpdatePT, 'pt' => 'NEIN']);
                session()->flash('messageUpdatePTLoading', 'Be careful : your loading is now WITHOUT exchange pallets');
            } elseif (isset($request->update)) {
                Loading::where('atrnr', $atrnr)->update(['ladedatum' => $ladedatum, 'entladedatum' => $entladedatum, 'disp' => $disp, 'referenz' => $referenz, 'auftraggeber' => $auftraggeber, 'beladestelle' => $beladestelle,
                    'ortb' => $ortb, 'plzb' => $plzb, 'landb' => $landb, 'entladestelle' => $entladestelle, 'orte' => $orte, 'plze' => $plze, 'lande' => $lande, 'anz' => $anz, 'art' => $art, 'ware' => $ware,
                    'subfrachter' => $subfrachter, 'kennzeichen' => $kennzeichen, 'zusladestellen' => $zusladestellen]);
                Loading::where('atrnr', 'like', $atrnr . '%')->update(['disp' => $disp]);
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
        $submitPallets = Input::get('submitPallets');
        $closeSubmitPalletsModal = Input::get('closeSubmitPalletsModal');
        $okSubmitPalletsModal = Input::get('okSubmitPalletsModal');
        $okSubmitPalletsValidateModal = Input::get('okSubmitPalletsValidateModal');

//        $truckAccount = Input::get('truckAccount');

        $date = $loading->ladedatum;
        $listPalletsAccounts = Palletsaccount::get();
        $listPalletstransfers = Palletstransfer::where('loading_atrnr', $atrnr)->get();
$listPalletstransfersNormal=Palletstransfer::where('loading_atrnr', $atrnr)->where(function ($q){
    $q->where('type', 'Deposit_Only')->orWhere('type', 'Withdrawal_Only')->orWhere('type', 'Withdrawal-Deposit')->orWhere('type', 'Deposit-Withdrawal');
})->get();
    $listPalletstransfersCorrecting=Palletstransfer::where('loading_atrnr', $atrnr)->where(function ($q){
        $q->where('type', 'Purchase_Int')->orWhere('type', 'Purchase_Ext')->orWhere('type', 'Sale_Int')->orWhere('type', 'Sale_Ext')->orWhere('type', 'Other');
    })->get();
//        $listTypes = ['Deposit', 'Withdrawal', 'Purchase', 'Sale', 'Other'];
//        //truck
//        $listPalletsaccountsCarrier = Palletsaccount::where('type', 'Carrier')->get();

        if (isset($update)) {
            $this->updatePanel1($request, $loading->atrnr);
            return redirect()->back();
        } elseif (isset($addTransferForm)) {
//            if (isset($truckAccount)) {
////                Loading::where('atrnr', $atrnr)->update(['truckAccount' => $truckAccount]);
////                $creditAccount = $truckAccount;
////                $debitAccount = $truckAccount;
//            } else {
//                //looking for the account that contains the license plate if it's set
//                if ($loading->kennzeichen == "") {
//                    $licensePlate = 'OTHER';
//                } else {
//                    $licensePlate = $loading->kennzeichen;
//                }
//                if (Truck::where('name', trim(explode(',', $loading->subfrachter)[0]))->where('licensePlate', $licensePlate)->first() <> null) {
//                    $namePalletsAccountTruck = Truck::where('name', trim(explode(',', $loading->subfrachter)[0]))->where('licensePlate', $licensePlate)->first()->palletsaccount_name;
//                    if ($namePalletsAccountTruck <> null) {
//                        $palletsAccountFavoriteTruck = Palletsaccount::where('name', $namePalletsAccountTruck)->first()->name;
//                    }
//                }
//            }
//            $loading = Loading::where('atrnr', $atrnr)->first();
            session()->flash('openPanelPallets', 'openPanelPallets');
            return view('loadings.DetailsLoading', compact('loading', 'listPalletsAccounts', 'listPalletstransfers','listPalletstransfersNormal','listPalletstransfersCorrecting', 'date', 'addTransferForm'));
        } elseif (isset($addPalletstransfer)) {
            $date = Input::get('date');
            $type = Input::get('type');
            $details = Input::get('details');
            $creditAccount = Input::get('creditAccount');
            $debitAccount = Input::get('debitAccount');
            $palletsNumber = Input::get('palletsNumber');

            if ($type == 'Purchase_Ext') {
                $rules = array(
                    'creditAccount' => 'required',
                );
                $actualTheoricalCreditPalletsNumber = Palletsaccount::where('name', $creditAccount)->value('theoricalNumberPallets');
            } elseif ($type == 'Sale_Ext') {
                $rules = array(
                    'debitAccount' => 'required',
                );
                $actualTheoricalDebitPalletsNumber = Palletsaccount::where('name', $debitAccount)->value('theoricalNumberPallets');
            } elseif ($type == 'Deposit-Withdrawal' || $type == 'Withdrawal-Deposit') {
                $creditAccount2 = $debitAccount;
                $debitAccount2 = $creditAccount;
                $palletsNumber2 = Input::get('palletsNumber2');

                $rules = array(
                    'creditAccount' => 'required',
                    'debitAccount' => 'required',
                );
                $actualTheoricalCreditPalletsNumber = Palletsaccount::where('name', $creditAccount)->value('theoricalNumberPallets');
                $actualTheoricalDebitPalletsNumber = Palletsaccount::where('name', $debitAccount)->value('theoricalNumberPallets');
//                $actualTheoricalCreditPalletsNumber2 = Palletsaccount::where('name', $creditAccount2)->value('theoricalNumberPallets');
//                $actualTheoricalDebitPalletsNumber2 = Palletsaccount::where('name', $debitAccount2)->value('theoricalNumberPallets');
            } else {
                $rules = array(
                    'creditAccount' => 'required',
                    'debitAccount' => 'required',
                );
                $actualTheoricalCreditPalletsNumber = Palletsaccount::where('name', $creditAccount)->value('theoricalNumberPallets');
                $actualTheoricalDebitPalletsNumber = Palletsaccount::where('name', $debitAccount)->value('theoricalNumberPallets');
            }
            $validator = Validator::make(Input::all(), $rules);

            if ($validator->fails()) {
                session()->flash('errorAccounts', "The account(s) has(ve) not been filled as expected");
                return view('loadings.DetailsLoading', compact('loading', 'date', 'details', 'type', 'creditAccount', 'debitAccount', 'palletsNumber', 'listPalletsAccounts', 'listPalletstransfers','listPalletstransfersNormal','listPalletstransfersCorrecting', 'addTransferForm'));
            } else {
                session()->flash('palletsNumber', $palletsNumber);
                session()->flash('openPanelPallets', 'openPanelPallets');
                if (isset($creditAccount)) {
                    session()->flash('creditAccount', $creditAccount);
                    session()->put('palletsNumberCreditAccount', $actualTheoricalCreditPalletsNumber);
                }
                if (isset($debitAccount)) {
                    session()->flash('debitAccount', $debitAccount);
                    session()->put('palletsNumberDebitAccount', $actualTheoricalDebitPalletsNumber);
                }
                if (isset($creditAccount2) && isset($debitAccount2) && isset($palletsNumber2)) {
                    session()->flash('palletsNumber2', $palletsNumber2);
                    session()->flash('creditAccount2', $creditAccount2);
                    session()->put('palletsNumberCreditAccount2', $actualTheoricalDebitPalletsNumber - $palletsNumber);
                    session()->flash('debitAccount2', $debitAccount2);
                    session()->put('palletsNumberDebitAccount2', $actualTheoricalCreditPalletsNumber + $palletsNumber);
                }
                return view('loadings.DetailsLoading', compact('loading', 'date', 'details', 'type', 'creditAccount', 'debitAccount', 'palletsNumber', 'creditAccount2', 'debitAccount2', 'palletsNumber2', 'listPalletsAccounts', 'listPalletstransfers', 'listPalletstransfersNormal','listPalletstransfersCorrecting','addPalletstransfer'));
            }
//            return view('loadings.DetailsLoading', compact('loading', 'listPalletsaccountsCarrier', 'listPalletsAccounts', 'listPalletstransfers', 'listFilesNames', 'loading_atrnr', 'date', 'type', 'multiTransfer', 'details', 'listTypes', 'creditAccount', 'debitAccount', 'palletsNumber', 'addPalletstransfer'));
        } elseif (isset($okSubmitAddModal)) {
            //accept to add the transfer
            $date = Input::get('date');
            $type = Input::get('type');
            $details = Input::get('details');
            $loading_atrnr = $atrnr;
            $creditAccount = Input::get('creditAccount');
            $debitAccount = Input::get('debitAccount');
            $palletsNumber = Input::get('palletsNumber');
            $creditAccount2 = $debitAccount;
            $debitAccount2 = $creditAccount;
            $palletsNumber2 = Input::get('palletsNumber2');
            $actualTheoricalCreditPalletsNumber = session('palletsNumberCreditAccount');
            $actualTheoricalDebitPalletsNumber = session('palletsNumberDebitAccount');
//            $actualTheoricalCreditPalletsNumber2 = session('palletsNumberCreditAccount2');
//            $actualTheoricalDebitPalletsNumber2 = session('palletsNumberDebitAccount2');

            $idErrorNotNumberLoading = Error::where('name', 'DW-WD_notNumberLoadingOrder')->first()->id;
            $idErrorNotSameNumber = Error::where('name', 'DW-WD_notSameNumber')->first()->id;
            if ($type == 'Deposit-Withdrawal') {
                if (!isset($palletsNumber2)) {
                    if ($palletsNumber <> $loading->anz) {
                        Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccount, 'debitAccount' => $debitAccount, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading_atrnr])->errors()->attach($idErrorNotNumberLoading);
                        Palletstransfer::create(['date' => $date, 'type' => 'Withdrawal-Deposit', 'details' => $details, 'creditAccount' => $creditAccount2, 'debitAccount' => $debitAccount2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading_atrnr, 'state' => 'Untreated'])->errors()->attach($idErrorNotNumberLoading);
                    } else {
                        Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccount, 'debitAccount' => $debitAccount, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading_atrnr]);
                        Palletstransfer::create(['date' => $date, 'type' => 'Withdrawal-Deposit', 'details' => $details, 'creditAccount' => $creditAccount2, 'debitAccount' => $debitAccount2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading_atrnr, 'state' => 'Untreated']);
                    }
                } else {
                    if ($palletsNumber <> $palletsNumber2 && $palletsNumber <> $loading->anz && $palletsNumber2 == $loading->anz) {
                        Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccount, 'debitAccount' => $debitAccount, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading_atrnr])->errors()->attach([$idErrorNotSameNumber, $idErrorNotNumberLoading]);
                        Palletstransfer::create(['date' => $date, 'type' => 'Withdrawal-Deposit', 'details' => $details, 'creditAccount' => $creditAccount2, 'debitAccount' => $debitAccount2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading_atrnr])->errors()->attach($idErrorNotSameNumber);
                    } elseif ($palletsNumber <> $palletsNumber2 && $palletsNumber <> $loading->anz && $palletsNumber2 <> $loading->anz) {
                        Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccount, 'debitAccount' => $debitAccount, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading_atrnr])->errors()->attach([$idErrorNotSameNumber, $idErrorNotNumberLoading]);
                        Palletstransfer::create(['date' => $date, 'type' => 'Withdrawal-Deposit', 'details' => $details, 'creditAccount' => $creditAccount2, 'debitAccount' => $debitAccount2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading_atrnr])->errors()->attach([$idErrorNotSameNumber, $idErrorNotNumberLoading]);
                    } elseif ($palletsNumber <> $palletsNumber2 && $palletsNumber == $loading->anz && $palletsNumber2 <> $loading->anz) {
                        Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccount, 'debitAccount' => $debitAccount, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading_atrnr])->errors()->attach($idErrorNotNumberLoading);
                        Palletstransfer::create(['date' => $date, 'type' => 'Withdrawal-Deposit', 'details' => $details, 'creditAccount' => $creditAccount2, 'debitAccount' => $debitAccount2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading_atrnr])->errors()->attach([$idErrorNotSameNumber, $idErrorNotNumberLoading]);
                    } elseif ($palletsNumber == $palletsNumber2 && $palletsNumber <> $loading->anz && $palletsNumber2 <> $loading->anz) {
                        Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccount, 'debitAccount' => $debitAccount, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading_atrnr])->errors()->attach($idErrorNotNumberLoading);
                        Palletstransfer::create(['date' => $date, 'type' => 'Withdrawal-Deposit', 'details' => $details, 'creditAccount' => $creditAccount2, 'debitAccount' => $debitAccount2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading_atrnr])->errors()->attach($idErrorNotNumberLoading);
                    } elseif ($palletsNumber == $palletsNumber2 && $palletsNumber == $loading->anz && $palletsNumber2 == $loading->anz) {
                        Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccount, 'debitAccount' => $debitAccount, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading_atrnr]);
                        Palletstransfer::create(['date' => $date, 'type' => 'Withdrawal-Deposit', 'details' => $details, 'creditAccount' => $creditAccount2, 'debitAccount' => $debitAccount2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading_atrnr]);
                    }
                }
            } elseif ($type == 'Withdrawal-Deposit') {
                if (!isset($palletsNumber2)) {
                    if ($palletsNumber <> $loading->anz) {
                        Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccount, 'debitAccount' => $debitAccount, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading_atrnr])->errors()->attach($idErrorNotNumberLoading);
                        Palletstransfer::create(['date' => $date, 'type' => 'Deposit-Withdrawal', 'details' => $details, 'creditAccount' => $creditAccount2, 'debitAccount' => $debitAccount2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading_atrnr, 'state' => 'Untreated'])->errors()->attach($idErrorNotNumberLoading);
                    } else {
                        Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccount, 'debitAccount' => $debitAccount, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading_atrnr]);
                        Palletstransfer::create(['date' => $date, 'type' => 'Deposit-Withdrawal', 'details' => $details, 'creditAccount' => $creditAccount2, 'debitAccount' => $debitAccount2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading_atrnr, 'state' => 'Untreated']);
                    }
                } else {
                    if ($palletsNumber <> $palletsNumber2 && $palletsNumber <> $loading->anz && $palletsNumber2 == $loading->anz) {
                        Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccount, 'debitAccount' => $debitAccount, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading_atrnr])->errors()->attach([$idErrorNotSameNumber, $idErrorNotNumberLoading]);
                        Palletstransfer::create(['date' => $date, 'type' => 'Deposit-Withdrawal', 'details' => $details, 'creditAccount' => $creditAccount2, 'debitAccount' => $debitAccount2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading_atrnr])->errors()->attach($idErrorNotSameNumber);
                    } elseif ($palletsNumber <> $palletsNumber2 && $palletsNumber <> $loading->anz && $palletsNumber2 <> $loading->anz) {
                        Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccount, 'debitAccount' => $debitAccount, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading_atrnr])->errors()->attach([$idErrorNotSameNumber, $idErrorNotNumberLoading]);
                        Palletstransfer::create(['date' => $date, 'type' => 'Deposit-Withdrawal', 'details' => $details, 'creditAccount' => $creditAccount2, 'debitAccount' => $debitAccount2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading_atrnr])->errors()->attach([$idErrorNotSameNumber, $idErrorNotNumberLoading]);
                    } elseif ($palletsNumber <> $palletsNumber2 && $palletsNumber == $loading->anz && $palletsNumber2 <> $loading->anz) {
                        Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccount, 'debitAccount' => $debitAccount, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading_atrnr])->errors()->attach($idErrorNotNumberLoading);
                        Palletstransfer::create(['date' => $date, 'type' => 'Deposit-Withdrawal', 'details' => $details, 'creditAccount' => $creditAccount2, 'debitAccount' => $debitAccount2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading_atrnr])->errors()->attach([$idErrorNotSameNumber, $idErrorNotNumberLoading]);
                    } elseif ($palletsNumber == $palletsNumber2 && $palletsNumber <> $loading->anz && $palletsNumber2 <> $loading->anz) {
                        Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccount, 'debitAccount' => $debitAccount, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading_atrnr])->errors()->attach($idErrorNotNumberLoading);
                        Palletstransfer::create(['date' => $date, 'type' => 'Deposit-Withdrawal', 'details' => $details, 'creditAccount' => $creditAccount2, 'debitAccount' => $debitAccount2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading_atrnr])->errors()->attach($idErrorNotNumberLoading);
                    } elseif ($palletsNumber == $palletsNumber2 && $palletsNumber == $loading->anz && $palletsNumber2 == $loading->anz) {
                        Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccount, 'debitAccount' => $debitAccount, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading_atrnr]);
                        Palletstransfer::create(['date' => $date, 'type' => 'Deposit-Withdrawal', 'details' => $details, 'creditAccount' => $creditAccount2, 'debitAccount' => $debitAccount2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading_atrnr]);
                    }
                }
            } else {
                Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccount, 'debitAccount' => $debitAccount, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading_atrnr]);
            }

            if (isset($creditAccount)) {
                Palletsaccount::where('name', $creditAccount)->update(['theoricalNumberPallets' => $actualTheoricalCreditPalletsNumber + $palletsNumber]);
            }
            if (isset($debitAccount)) {
                Palletsaccount::where('name', $debitAccount)->update(['theoricalNumberPallets' => $actualTheoricalDebitPalletsNumber - $palletsNumber]);
            }
            if (isset($creditAccount2) && isset($debitAccount2) && isset($palletsNumber2)) {
                Palletsaccount::where('name', $creditAccount2)->update(['theoricalNumberPallets' => $actualTheoricalDebitPalletsNumber - $palletsNumber + $palletsNumber2]);
                Palletsaccount::where('name', $debitAccount2)->update(['theoricalNumberPallets' => $actualTheoricalCreditPalletsNumber + $palletsNumber - $palletsNumber2]);
            }
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
            $documents = $request->file('documentsTransfer' . $uploadDocument);
            $state = $transfer->state;
//            $type = $transfer->type;
//            $creditAccount = $transfer->creditAccount;
//            $debitAccount = $transfer->debitAccount;
//            $palletsNumber = $transfer->palletsNumber;

            $filesNames = $this->upload($documents, $transfer);
            if (!empty($filesNames) && $transfer->validate == 1) {
                $state = 'Complete Validated';
            } elseif (!empty($filesNames) && $transfer->validate == 0) {
                $state = 'Complete';
            } elseif (empty($filesNames)) {
                $state = 'Waiting documents';
            }
//            elseif (isset($creditAccount) || isset($debitAccount) || isset($palletsNumber) || isset($type) || !empty($filesNames)) {
//                $state = 'In progress';
//            }
            Palletstransfer::where('id', $transfer->id)->update(['state' => $state]);
            $this->state($loading, Palletstransfer::where('loading_atrnr', $atrnr)->get());
            session()->flash('openPanelPallets', 'openPanelPallets');
            return redirect()->back();
        } elseif (isset($delete)) {
            $transfer = Palletstransfer::where('id', $delete)->first();
            foreach (Palletsaccount::get() as $account) {
                $listNamesPalletsaccounts[] = $account->name;
            }
            foreach (Loading::get()->where('pt', 'JA') as $loading) {
                $listAtrnr[] = $loading->atrnr;
            }
            $filesNames = $this->actualDocuments($transfer->id);
            return view('palletstransfers.detailsPalletstransfer', compact('transfer', 'listNamesPalletsaccounts', 'filesNames', 'delete'));
        } elseif (isset($deleteDocument)) {
            $this->deleteDocument(Palletstransfer::where('id', trim(explode('-', $deleteDocument)[1]))->first(), trim(explode('-', $deleteDocument)[0]));
            $this->state($loading, Palletstransfer::where('loading_atrnr', $atrnr)->get());
            session()->flash('openPanelPallets', 'openPanelPallets');
            return redirect()->back();
        } elseif (isset($submitPallets)) {
            //to update the transfer
            $transfer = Palletstransfer::where('id', $submitPallets)->first();
            $loading_atrnr = $atrnr;
            $palletsNumber = Input::get('palletsNumber' . $submitPallets);
            $type = Input::get('type' . $submitPallets);
            $details = Input::get('details' . $submitPallets);
            $date = Input::get('date' . $submitPallets);
            $creditAccount = Input::get('creditAccount' . $submitPallets);
            $debitAccount = Input::get('debitAccount' . $submitPallets);
            $validate = Input::get('validate' . $submitPallets);

            if ($type == 'Purchase_Ext') {
                $rules = array(
                    'creditAccount' . $submitPallets => 'required',
                );
                $debitAccount = null;
            } elseif ($type == 'Sale_Ext') {
                $rules = array(
                    'debitAccount' . $submitPallets => 'required',
                );
                $creditAccount = null;
            } else {
                $rules = array(
                    'creditAccount' . $submitPallets => 'required',
                    'debitAccount' . $submitPallets => 'required',
                );
            }
            $validator = Validator::make(Input::all(), $rules);

            if ($validator->fails()) {
                session()->flash('errorAccountsPanel', "The account(s) has(ve) not been filled as expected. REFILL !");
                return redirect()->back();
            } else {
                if ($transfer->state == 'Complete Validated') {
                    $this->inverseRealPalletsNumber($transfer);
                }
                $filesNames = $this->actualDocuments($transfer->id);
                if (isset($transfer->creditAccount)) {
                    session()->put('actualCreditAccount', $transfer->creditAccount);
                }
                if (isset($transfer->debitAccount)) {
                    session()->put('actualDebitAccount', $transfer->debitAccount);
                }
                if (($transfer->type == 'Deposit-Withdrawal' || $transfer->type == 'Withdrawal-Deposit') && ($type <> 'Deposit-Withdrawal' || $type <> 'Withdrawal-Deposit')) {
                    $transfer->errors()->detach(Error::where('name', 'DW-WD_notSameNumber')->first()->id);
                    $transfer->errors()->detach(Error::where('name', 'DW-WD_notNumberLoadingOrder')->first()->id);
                }
                if (isset($creditAccount)) {
                    session()->flash('creditAccount', $creditAccount);
                    session()->flash('thPalletsNumberCreditAccount', Palletsaccount::where('name', $creditAccount)->first()->theoricalNumberPallets);
                    Palletstransfer::where('id', $transfer->id)->update(['creditAccount' => $creditAccount]);

                }
                if (isset($debitAccount)) {
                    session()->flash('debitAccount', $debitAccount);
                    session()->flash('thPalletsNumberDebitAccount', Palletsaccount::where('name', $debitAccount)->first()->theoricalNumberPallets);
                    Palletstransfer::where('id', $transfer->id)->update(['debitAccount' => $debitAccount]);

                }
                session()->put('actualPalletsNumber', $transfer->palletsNumber);
                session()->put('actualType', $transfer->type);
                session()->put('actualDetails', $transfer->details);
                session()->put('actualDate', $transfer->date);
                session()->put('actualValidate', $transfer->validate);
                session()->flash('palletsNumber', $palletsNumber);
                Palletstransfer::where('id', $transfer->id)->update(['type' => $type, 'details' => $details, 'loading_atrnr' => $loading_atrnr, 'palletsNumber' => $palletsNumber, 'date' => $date]);

                if ($validate <> null && $validate == 'true') {
                    Palletstransfer::where('id', $transfer->id)->update(['validate' => true]);
                } elseif ($validate <> null && $validate == 'false') {
                    Palletstransfer::where('id', $transfer->id)->update(['validate' => false]);
                }
                $transfer = Palletstransfer::where('id', $transfer->id)->first();
                $this->state($loading, Palletstransfer::where('loading_atrnr', $atrnr)->get());
                session()->flash('openPanelPallets', 'openPanelPallets');
                return view('loadings.detailsLoading', compact('loading', 'listPalletsAccounts', 'listPalletstransfers','listPalletstransfersNormal','listPalletstransfersCorrecting',
                    'transfer', 'submitPallets', 'filesNames'));
            }

        } elseif (isset($okSubmitPalletsModal)) {
            //valide the transfer update
            $transfer = Palletstransfer::where('id', $okSubmitPalletsModal)->first();
            $filesNames = $this->actualDocuments($transfer->id);
            $actualCreditAccount = session('actualCreditAccount');
            $actualDebitAccount = session('actualDebitAccount');
            $actualPalletsNumber = session('actualPalletsNumber');
            $this->updateInfo($transfer, $actualPalletsNumber, $actualCreditAccount, $actualDebitAccount, $filesNames);
            $transfer = Palletstransfer::where('id', $okSubmitPalletsModal)->first();
            $this->state($loading, Palletstransfer::where('loading_atrnr', $atrnr)->get());
            session()->flash('openPanelPallets', 'openPanelPallets');

            $queryTransfer = Palletstransfer::where(function ($q) {
                $q->where('type', 'Deposit-Withdrawal')->orWhere('type', 'Withdrawal-Deposit');
            });

            if (!$queryTransfer->get()->isEmpty()) {
                $listLoadings = [];
                foreach ($queryTransfer->get() as $transfer) {
                    if (!in_array($transfer->loading_atrnr, $listLoadings)) {
                        $listLoadings[] = $transfer->loading_atrnr;
                        $loading = Loading::where('atrnr', $transfer->loading_atrnr)->first();
                        $listTransfersDWK =Palletstransfer::where('loading_atrnr', $transfer->loading_atrnr)->where('type', 'Deposit-Withdrawal')->get();
                        $listTransfersWDK = Palletstransfer::where('loading_atrnr', $transfer->loading_atrnr)->where('type', 'Withdrawal-Deposit')->get();
                        $listAccounts = [];
                        foreach ($listTransfersDWK as $transferDWK) {
                            if (!in_array($transferDWK->creditAccount, $listAccounts)) {
                                $listAccounts[] = $transferDWK->creditAccount;
                            }
                        }
                        foreach ($listTransfersWDK as $transferWDK) {
                            if (!in_array($transferWDK->debitAccount, $listAccounts)) {
                                $listAccounts[] = $transferWDK->debitAccount;
                            }
                        }

                        for ($k = 0; $k < count($listAccounts); $k++) {
                            $sumTransferDW = Palletstransfer::where('loading_atrnr', $transfer->loading_atrnr)->where('type', 'Deposit-Withdrawal')->where('creditAccount', $listAccounts[$k])->sum('palletsNumber');
                            $sumTransferWD = Palletstransfer::where('loading_atrnr', $transfer->loading_atrnr)->where('type', 'Withdrawal-Deposit')->where('debitAccount', $listAccounts[$k])->sum('palletsNumber');
                            $idErrorNotSameNumber = Error::where('name', 'DW-WD_notSameNumber')->first()->id;
                            $idErrorNotNumberLoadingOrder = Error::where('name', 'DW-WD_notNumberLoadingOrder')->first()->id;
                            if ($sumTransferWD <> $sumTransferDW && $sumTransferWD <> $loading->anz && $sumTransferDW == $loading->anz) {
                                foreach (Palletstransfer::where('loading_atrnr', $transfer->loading_atrnr)->where('type', 'Deposit-Withdrawal')->where('creditAccount', $listAccounts[$k])->get() as $transfer) {
                                    $transfer->errors()->sync($idErrorNotSameNumber);
                                }
                                foreach (Palletstransfer::where('loading_atrnr', $transfer->loading_atrnr)->where('type', 'Withdrawal-Deposit')->where('debitAccount', $listAccounts[$k])->get() as $transfer) {
                                    $transfer->errors()->sync([$idErrorNotSameNumber, $idErrorNotNumberLoadingOrder]);
                                }
                            } elseif ($sumTransferWD <> $sumTransferDW && $sumTransferWD <> $loading->anz && $sumTransferDW <> $loading->anz) {
                                foreach (Palletstransfer::where('loading_atrnr', $transfer->loading_atrnr)->where('type', 'Deposit-Withdrawal')->where('creditAccount', $listAccounts[$k])->get() as $transfer) {
                                    $transfer->errors()->sync([$idErrorNotSameNumber, $idErrorNotNumberLoadingOrder]);
                                }
                                foreach (Palletstransfer::where('loading_atrnr', $transfer->loading_atrnr)->where('type', 'Withdrawal-Deposit')->where('debitAccount', $listAccounts[$k])->get() as $transfer) {
                                    $transfer->errors()->sync([$idErrorNotSameNumber, $idErrorNotNumberLoadingOrder]);
                                }
                            } elseif ($sumTransferWD <> $sumTransferDW && $sumTransferWD == $loading->anz && $sumTransferDW <> $loading->anz) {
                                foreach (Palletstransfer::where('loading_atrnr', $transfer->loading_atrnr)->where('type', 'Deposit-Withdrawal')->where('creditAccount', $listAccounts[$k])->get() as $transfer) {
                                    $transfer->errors()->sync([$idErrorNotSameNumber, $idErrorNotNumberLoadingOrder]);
                                }
                                foreach (Palletstransfer::where('loading_atrnr', $transfer->loading_atrnr)->where('type', 'Withdrawal-Deposit')->where('debitAccount', $listAccounts[$k])->get() as $transfer) {
                                    $transfer->errors()->sync($idErrorNotSameNumber);
                                }
                            } elseif($sumTransferWD == $sumTransferDW && $sumTransferWD == $loading->anz) {
                                foreach (Palletstransfer::where('loading_atrnr', $transfer->loading_atrnr)->where('type', 'Deposit-Withdrawal')->where('creditAccount', $listAccounts[$k])->get() as $transfer) {
                                    $transfer->errors()->detach($idErrorNotSameNumber);
                                    $transfer->errors()->detach($idErrorNotNumberLoadingOrder);
                                }
                                foreach (Palletstransfer::where('loading_atrnr', $transfer->loading_atrnr)->where('type', 'Withdrawal-Deposit')->where('debitAccount', $listAccounts[$k])->get() as $transfer) {
                                    $transfer->errors()->detach($idErrorNotSameNumber);
                                    $transfer->errors()->detach($idErrorNotNumberLoadingOrder);
                                }
                            }elseif($sumTransferWD == $sumTransferDW && $sumTransferWD <> $loading->anz) {
                                foreach (Palletstransfer::where('loading_atrnr', $transfer->loading_atrnr)->where('type', 'Deposit-Withdrawal')->where('creditAccount', $listAccounts[$k])->get() as $transfer) {
                                    $transfer->errors()->sync($idErrorNotNumberLoadingOrder);
                                }
                                foreach (Palletstransfer::where('loading_atrnr', $transfer->loading_atrnr)->where('type', 'Withdrawal-Deposit')->where('debitAccount', $listAccounts[$k])->get() as $transfer) {
                                    $transfer->errors()->sync($idErrorNotNumberLoadingOrder);
                                }
                            }
                        }
                    }
                }
            }

            if ($transfer->state == 'Complete Validated') {
                session()->flash('palletsNumber', $transfer->palletsNumber);
                session()->flash('creditAccount', $transfer->creditAccount);
                session()->flash('debitAccount', $transfer->debitAccount);
                if (isset($transfer->creditAccount)) {
                    session()->flash('realPalletsNumberCreditAccount', Palletsaccount::where('name', $transfer->creditAccount)->first()->realNumberPallets);
                }
                if (isset($transfer->debitAccount)) {
                    session()->flash('realPalletsNumberDebitAccount', Palletsaccount::where('name', $transfer->debitAccount)->first()->realNumberPallets);
                }

                return view('loadings.detailsLoading', compact('loading', 'listPalletsAccounts', 'listPalletstransfers','listPalletstransfersNormal','listPalletstransfersCorrecting',
                    'transfer', 'okSubmitPalletsModal', 'filesNames'));
            } else {
                session()->pull('actualCreditAccount');
                session()->pull('actualDebitAccount');
                session()->pull('actualPalletsNumber');
                session()->pull('actualType');
                session()->pull('actualDetails');
                session()->pull('actualDate');
                session()->pull('actualValidate');
                return redirect()->back();
            }
        } elseif (isset($closeSubmitPalletsModal)) {
            //refuse the transfer update
            $actualCreditAccount = session('actualCreditAccount');
            $actualDebitAccount = session('actualDebitAccount');
            $actualPalletsNumber = session('actualPalletsNumber');
            $actualType = session('actualType');
            $actualDetails = session('actualDetails');
            $actualDate = session('actualDate');
            $actualValidate = session('actualValidate');
            if (isset($actualDebitAccount)) {
                Palletstransfer::where('id', $closeSubmitPalletsModal)->update(['debitAccount' => $actualDebitAccount]);
            }
            if (isset($actualCreditAccount)) {
                Palletstransfer::where('id', $closeSubmitPalletsModal)->update(['creditAccount' => $actualCreditAccount]);
            }
            Palletstransfer::where('id', $closeSubmitPalletsModal)->update(['validate' => $actualValidate, 'type' => $actualType, 'details' => $actualDetails, 'palletsNumber' => $actualPalletsNumber, 'date' => $actualDate]);
            $this->state($loading, Palletstransfer::where('loading_atrnr', $atrnr)->get());
            session()->pull('actualCreditAccount');
            session()->pull('actualDebitAccount');
            session()->pull('actualPalletsNumber');
            session()->pull('actualType');
            session()->pull('actualDetails');
            session()->pull('actualDate');
            session()->pull('actualValidate');
            session()->flash('openPanelPallets', 'openPanelPallets');
            return redirect()->back();
        } elseif (isset($okSubmitPalletsValidateModal)) {
            $transfer = Palletstransfer::where('id', $okSubmitPalletsValidateModal)->first();
            if (isset($transfer->creditAccount)) {
                $realPalletsNumberCreditAccount = Palletsaccount::where('name', $transfer->creditAccount)->first()->realNumberPallets;
                Palletsaccount::where('name', $transfer->creditAccount)->update(['realNumberPallets' => $realPalletsNumberCreditAccount + $transfer->palletsNumber]);
            }
            if (isset($transfer->debitAccount)) {
                $realPalletsNumberDebitAccount = Palletsaccount::where('name', $transfer->debitAccount)->first()->realNumberPallets;
                Palletsaccount::where('name', $transfer->debitAccount)->update(['realNumberPallets' => $realPalletsNumberDebitAccount - $transfer->palletsNumber]);
            }
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
    public function upload($documents, $transfer)
    {
        if (isset($documents)) {
            foreach ($documents as $doc) {
                $filename = $doc->getClientOriginalName();
                $extension = $doc->getClientOriginalExtension();
                $size = $doc->getSize();
                //if file is an image, a pdf or an email
                if (($extension == 'png' || $extension == 'jpg' || $extension == 'JPG' || $extension == 'msg' || $extension == 'htm' || $extension == 'rtf' || $extension == 'pdf') && $size < 2000000) {
                    Storage::putFileAs('/proofsPallets/documentsTransfer/' . $transfer->id . '/' . $transfer->type, $doc, $filename);
                    Document::firstOrCreate([
                        'name' => $filename,
                    ])->palletstransfers()->attach($transfer->id);
                } else {
                    session()->flash('messageErrorUpload', 'Error ! The file type is not supported (png, jgp, pdf, msg, htm, rtf only');
                }
            }
        }
        $filesNames = $this->actualDocuments($transfer->id);
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
        $path = '/proofsPallets/documentsTransfer/' . $transfer->id . '/'. $transfer->type . '/';
        Storage::delete($path . $name);
        $actualTransferAssociated = DB::table('document_palletstransfer')->where('document_id', $doc->id)->get();
        if ($actualTransferAssociated->isEmpty()) {
            $doc->delete();
        }
        $actualDocuments_Palletstransfers = DB::table('document_palletstransfer')->where('palletstransfer_id', $transfer->id)->get();
        if ($actualDocuments_Palletstransfers->isEmpty()) {
            Palletstransfer::where('id', $transfer->id)->update(['validate' => false]);
            if ($transfer->state == 'Complete Validated') {
                $this->inverseRealPalletsNumber($transfer);
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
        if (isset($transfer->creditAccount)) {
            $actualRealPalletsNumberCreditAccount = Palletsaccount::where('name', $transfer->creditAccount)->first()->realNumberPallets;
            Palletsaccount::where('name', $transfer->creditAccount)->update(['realNumberPallets' => $actualRealPalletsNumberCreditAccount - $transfer->palletsNumber]);
        }
        if (isset($transfer->debitAccount)) {
            $actualRealPalletsNumberDebitAccount = Palletsaccount::where('name', $transfer->debitAccount)->first()->realNumberPallets;
            Palletsaccount::where('name', $transfer->debitAccount)->update(['realNumberPallets' => $actualRealPalletsNumberDebitAccount + $transfer->palletsNumber]);
        }
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
        if (isset($actualCreditAccount)) {
            $actualPalletsNumberCreditAccount = Palletsaccount::where('name', $actualCreditAccount)->first()->theoricalNumberPallets;
            Palletsaccount::where('name', $actualCreditAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberCreditAccount - $actualPalletsNumber]);
        }
        if (isset($actualDebitAccount)) {
            $actualPalletsNumberDebitAccount = Palletsaccount::where('name', $actualDebitAccount)->first()->theoricalNumberPallets;
            Palletsaccount::where('name', $actualDebitAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberDebitAccount + $actualPalletsNumber]);
        }

        //we do the new transfer
        if (isset($transfer->creditAccount)) {
            $palletsNumberCreditAccount = Palletsaccount::where('name', $transfer->creditAccount)->first()->theoricalNumberPallets;
            Palletsaccount::where('name', $transfer->creditAccount)->update(['theoricalNumberPallets' => $palletsNumberCreditAccount + $transfer->palletsNumber]);
        }
        if (isset($transfer->debitAccount)) {
            $palletsNumberDebitAccount = Palletsaccount::where('name', $transfer->debitAccount)->first()->theoricalNumberPallets;
            Palletsaccount::where('name', $transfer->debitAccount)->update(['theoricalNumberPallets' => $palletsNumberDebitAccount - $transfer->palletsNumber]);
        }

        //state
        if (!empty($filesNames) && $transfer->validate == 1) {
            $state = 'Complete Validated';
        } elseif (!empty($filesNames) && $transfer->validate == 0) {
            $state = 'Complete';
        } elseif (empty($filesNames)) {
            $state = 'Waiting documents';
        }
//        elseif (isset($transfer->creditAccount) || isset($transfer->debitAccount) || isset($transfer->palletsNumber) || isset($transfer->type) || !empty($filesNames)) {
//            $state = 'In progress';
//        }
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
        if ($listPalletstransfers->isEmpty()) {
            $state = 'Untreated';
        } else {
            $stateCompleteValidated = 0;
            $stateComplete = 0;
            $stateWaitingDocuments = 0;
            $stateUntreated = 0;
            foreach ($listPalletstransfers as $transfer) {
                if ($transfer->state == 'Complete Validated') {
                    $stateCompleteValidated = $stateCompleteValidated + 1;
                } elseif ($transfer->state == 'Complete') {
                    $stateComplete = $stateComplete + 1;
                } elseif ($transfer->state == 'Waiting documents') {
                    $stateWaitingDocuments = $stateWaitingDocuments + 1;
                } elseif ($transfer->state == 'Untreated') {
                    $stateUntreated = $stateUntreated + 1;
                }
            }

            if ($stateCompleteValidated == count($listPalletstransfers)) {
                $state = 'Complete Validated';
            } elseif ($stateWaitingDocuments == 0 && $stateUntreated == 0) {
                $state = 'Complete';
            } elseif ($stateWaitingDocuments > 0) {
                $state = 'Waiting documents';
            } elseif ($stateWaitingDocuments = 0 && $stateUntreated > 0) {
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
    public function showAdd($atrnr)
    {
        $loading = Loading::where('atrnr', $atrnr)->first();
        return view('loadings.addSubloading', compact('loading'));
    }

    /**
     * add a subloading
     * @param $atrnr
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function add($atrnr)
    {

        $loadingInitial = Loading::where('atrnr', $atrnr)->first();
        $referenz = Input::get('referenz');
        $auftraggeber = Input::get('auftraggeber');
        $subfrachter = Input::get('subfrachter');
        $kennzeichen = Input::get('kennzeichen');
        $art = Input::get('art');
        $anz = Input::get('anz');
        $ware = Input::get('ware');
        $ladedatum = Input::get('ladedatum');
        $beladestelle = Input::get('beladestelle');
        $ortb = Input::get('ortb');
        $plzb = Input::get('plzb');
        $landb = Input::get('landb');
        $zusladestellen = Input::get('zusladestellen');
        $entladedatum = Input::get('entladedatum');
        $entladestelle = Input::get('entladestelle');
        $orte = Input::get('orte');
        $plze = Input::get('plze');
        $lande = Input::get('lande');
        $disp = $loadingInitial->disp;
        $pt = $loadingInitial->pt;

        if (substr_count($loadingInitial->atrnr, '-') == 0) {
            $atrnr = $loadingInitial->atrnr . '-1';
        } elseif (substr_count($loadingInitial->atrnr, '-') > 0) {
            $atrnrSplit = explode('-', $loadingInitial->atrnr);
            $atrnrSplit[count($atrnrSplit - 1)] = $atrnrSplit[count($atrnrSplit - 1)] + 1;
            $atrnr = implode('-', $atrnrSplit);
        }
        $loadingsTest = Loading::where('atrnr', '=', $atrnr)->first();
        if ($loadingsTest == null) {
            $k = count(Loading::get()) + 1;
            Loading::firstOrCreate([
                'id' => $k,
                'ladedatum' => $ladedatum,
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
                'pt' => $pt,
                'subfrachter' => $subfrachter,
                'kennzeichen' => $kennzeichen,
                'zusladestellen' => $zusladestellen,
            ]);
        }

        return redirect('/loadings');
    }
}
