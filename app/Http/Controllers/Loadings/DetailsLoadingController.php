<?php

namespace App\Http\Controllers;

use App\Document;
use App\Error;
use App\Loading;
use App\PalletsAccount;
use App\Palletstransfer;
use App\Truck;
use App\User;
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
            $loading = Loading::where('atrnr', $atrnr)->first();

            if ($loading->referenz == null || $loading->disp == null || $loading->auftraggeber == null || $loading->subfrachter == null
                || $loading->anz == null || $loading->art == null || $loading->ware == null || $loading->beladestelle == null || $loading->plzb == null
                || $loading->ortb == null || $loading->landb == null || $loading->entladestelle == null || $loading->plze == null || $loading->orte == null || $loading->lande == null
            ) {
                session()->flash('openPanelInformation', 'openPanelInformation');
                session()->flash('messageErrorFieldsRequired', "BE CAREFUL !! Some required loading information haven't been field. Please solve this before continuing.");
            }

            //looking for the user who did this loading order
            foreach (User::get() as $user) {
                if ($user->initials == $loading->disp) {
                    $disp = $user->lastname . ' ' . $user->firstname;
                }
            }
            if (!isset($disp)) {
                //llx_user : table get from Dolibarr, not updated with the website
                foreach (DB::table('llx_user')->get() as $userDolibarr) {
                    //get the 2 first initials of lastname and firstname
                    if ($userDolibarr->lastname == 'SuperAdmin') {
                        $lastnameIni = 'Gu';
                        $firstnameIni = 'Ad';
                    } else {
                        $lastnameIni = substr($userDolibarr->lastname, 0, 2);
                        $firstnameIni = substr($userDolibarr->firstname, 0, 2);
                    }
                    if ($lastnameIni . $firstnameIni == $loading->disp && $userDolibarr->lastname == 'SuperAdmin') {
                        $disp = 'Adrien GUNDOGAN';
                    } elseif ($lastnameIni . $firstnameIni == $loading->disp) {
                        $disp = $userDolibarr->lastname . ' ' . $userDolibarr->firstname;
                    } elseif (!isset($disp) && $lastnameIni . $firstnameIni <> $loading->disp) {
                        $disp = $loading->disp;
                    }
                }
            }

//            //link to the mother loading of the subloading
//            if (substr_count($loading->atrnr, '-') <> 0) {
//                $atrnr1 = explode('-', $loading->atrnr)[0];
//                $atrnr2 = array_slice(explode('-', $loading->atrnr), 1);
//                $atrnr2 = implode('-', $atrnr2);
//            }

            //////PALLETS PANEL//////
            // get all the pallets account except the carriers accounts that will be get after, truck by truck
            $listPalletsAccounts = Palletsaccount::where('type', 'Network')->orWhere('type', 'Other')->orderBy('nickname', 'asc')->get();
            $listTrucksAccounts = Truck::orderBy('name', 'asc')->get();

            if (request()->has('sortby') && request()->has('order')) {
                $sortby = $request->get('sortby'); // Order by what column?
                $order = $request->get('order'); // Order direction: asc or desc
                $listPalletstransfers = Palletstransfer::where('loading_atrnr', $atrnr)->orderBy($sortby, $order)->get();
            } else {
                $listPalletstransfers = Palletstransfer::where('loading_atrnr', $atrnr)->orderBy('id', 'asc')->get();
            }
            $listAccountsTransfers = [];
            if (!$listPalletstransfers->isEmpty()) {
                foreach ($listPalletstransfers as $transfer) {
                    if (!in_array($transfer->creditAccount, $listAccountsTransfers)) {
                        $listAccountsTransfers[] = $transfer->creditAccount;
                    }
                    if (!in_array($transfer->debitAccount, $listAccountsTransfers)) {
                        $listAccountsTransfers[] = $transfer->debitAccount;
                    }
                }
            }
            asort($listAccountsTransfers);
            $listPalletstransfersNormal = Palletstransfer::where('loading_atrnr', $atrnr)->where(function ($q) {
                $q->where('type', 'Deposit_Only')->orWhere('type', 'Withdrawal_Only')->orWhere('type', 'Withdrawal-Deposit')->orWhere('type', 'Deposit-Withdrawal');
            })->orderBy('id', 'asc')->get();
            $listPalletstransfersCorrecting = Palletstransfer::where('loading_atrnr', $atrnr)->where(function ($q) {
                $q->where('type', 'Purchase')->orWhere('type', 'Sale')->orWhere('type', 'Other')->orWhere('type', 'Debt');
            })->orderBy('id', 'asc')->get();
            //looking for the truck associated to this loading
            if ($loading->kennzeichen == '') {
                $truckAssociated = Truck::where('name', trim(explode(',', $loading->subfrachter)[0]))->where('licensePlate', 'OTHER')->first();
            } else {
                $truckAssociated = Truck::where('name', trim(explode(',', $loading->subfrachter)[0]))->where('licensePlate', $loading->kennzeichen)->first();
            }

//get pallets numbers of the truck
            if ($truckAssociated <> null) {
                $theoricalNumberPalletsTruck = $truckAssociated->theoricalNumberPallets;
                $realNumberPalletsTruck = $truckAssociated->realNumberPallets;
            }

            return view('loadings.detailsLoading', compact('sortby', 'order', 'loading', 'disp', 'atrnr1', 'atrnr2', 'listPalletsAccounts', 'truckAssociated', 'listAccountsTransfers', 'listTrucksAccounts', 'listPalletstransfers', 'listPalletstransfersNormal', 'listPalletstransfersCorrecting', 'theoricalNumberPalletsTruck', 'realNumberPalletsTruck'
            ));
        } else {
            return view('auth.login');
        }
    }


    /**
     * update only the panel information of the loading
     * @param $actionForm
     * @param $atrnr
     * @param $subfrachter
     * @param $kennzeichen
     * @return string
     * @internal param Request $request
     */
    public function updatePanel1($actionForm, $atrnr, $subfrachter, $kennzeichen)
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
        $zusladestellen = Input::get('zusladestellen');
        $reasonUpdatePT = Input::get('reasonUpdatePT');


        if (isset($reasonUpdatePT) && $actionForm == 'updateValidatePT') {
            Loading::where('atrnr', $atrnr)->update(['reasonUpdatePT' => $reasonUpdatePT, 'pt' => 'NEIN']);
            Loading::where('atrnr', 'like', $atrnr . '%')->update(['reasonUpdatePT' => $reasonUpdatePT, 'pt' => 'NEIN']);
            session()->flash('messageUpdatePTLoading', 'Be careful : your loading is now WITHOUT exchange pallets');
            $view = 'ok';
        } elseif ($actionForm == 'closeUpdateModal') {
            session()->flash('openPanelInformation', 'openPanelInformation');
            $view = 'ok';
        } elseif ($actionForm == 'updateCreateCarrier') {
            $view = 'createCarrier';
        } elseif ($actionForm == 'updateCreateTruck') {
            $view = 'createTruck';
        } elseif ($actionForm == 'Update') {
            Loading::where('atrnr', $atrnr)->update(['ladedatum' => $ladedatum, 'entladedatum' => $entladedatum, 'disp' => $disp, 'referenz' => $referenz, 'auftraggeber' => $auftraggeber, 'beladestelle' => $beladestelle,
                'ortb' => $ortb, 'plzb' => $plzb, 'landb' => $landb, 'entladestelle' => $entladestelle, 'orte' => $orte, 'plze' => $plze, 'lande' => $lande, 'anz' => $anz, 'art' => $art, 'ware' => $ware,
                'kennzeichen' => $kennzeichen, 'zusladestellen' => $zusladestellen]);
            Loading::where('atrnr', 'like', $atrnr . '%')->update(['disp' => $disp]);
            if (!strpos($subfrachter, ',')) {
                $testCarrier = Palletsaccount::where('type', 'Carrier')->where('nickname', $subfrachter)->first();
                if ($testCarrier == null) {
                    session()->flash('testCarrier', true);
                    $view = 'testCarrier';
                } else {
                    Loading::where('atrnr', $atrnr)->update(['subfrachter' => $testCarrier->name . ', ' . $testCarrier->country . '-' . $testCarrier->zipcode . ' ' . $testCarrier->town]);
                    $testTruck = Truck::where('name', $subfrachter)->where('licensePlate', $kennzeichen)->first();

                    if ($testTruck == null) {
                        session()->flash('testTruck', true);
                        $view = 'testTruck';
                    } else {
                        Loading::where('atrnr', $atrnr)->update(['kennzeichen' => $kennzeichen]);
                        $view = 'ok';
                    }
                }
            } else {
                $testCarrier = Palletsaccount::where('type', 'Carrier')->where('nickname', explode(',', $subfrachter)[0])->first();
                if ($testCarrier == null) {
                    session()->flash('testCarrier', true);
                    $view = 'testCarrier';
                } else {
                    Loading::where('atrnr', $atrnr)->update(['subfrachter' => $testCarrier->name . ', ' . $testCarrier->country . '-' . $testCarrier->zipcode . ' ' . $testCarrier->town]);
                    $testTruck = Truck::where('name', explode(',', $subfrachter)[0])->where('licensePlate', $kennzeichen)->first();

                    if ($testTruck == null) {
                        session()->flash('testTruck', true);
                        $view = 'testTruck';
                    } else {
                        Loading::where('atrnr', $atrnr)->update(['kennzeichen' => $kennzeichen]);
                        $view = 'ok';
                    }
                }
            }
            session()->flash('messageUpdateLoading', 'Successfully updated loading');
        }
        $this->state(Loading::where('atrnr', $atrnr)->first(), Palletstransfer::where('loading_atrnr', $atrnr)->get());
        session()->flash('openPanelInformation', 'openPanelInformation');
        return $view;
    }


    /**
     * Main function. According to the button selected, differents actions possibles
     * @param $atrnr
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function submitUpdateUpload($atrnr, Request $request)
    {
        if (Auth::check()) {
            $loading = Loading::where('atrnr', $atrnr)->first();

            //BUTTONS
            $actionForm = Input::get('actionForm');

            // get all the pallets account except the carriers accounts that will be get after, truck by truck
            $listPalletsAccounts = Palletsaccount::where('type', 'Network')->orWhere('type', 'Other')->orderBy('nickname', 'asc')->get();
            $listTrucksAccounts = Truck::orderBy('name', 'asc')->get();

            //looking for the truck associated to this loading
            if ($loading->kennzeichen == '') {
                $truckAssociated = Truck::where('name', trim(explode(',', $loading->subfrachter)[0]))->where('licensePlate', 'OTHER')->first();
            } else {
                $truckAssociated = Truck::where('name', trim(explode(',', $loading->subfrachter)[0]))->where('licensePlate', $loading->kennzeichen)->first();
            }
            if ($truckAssociated <> null) {
                $theoricalNumberPalletsTruck = $truckAssociated->theoricalNumberPallets;
                $realNumberPalletsTruck = $truckAssociated->realNumberPallets;
            }

            //looking for the user who did this loading order
            foreach (User::get() as $user) {
                if ($user->initials == $loading->disp) {
                    $disp = $user->lastname . ' ' . $user->firstname;
                }
            }
            if (!isset($disp)) {
                foreach (DB::table('llx_user')->get() as $userDolibarr) {
                    if ($userDolibarr->lastname == 'SuperAdmin') {
                        $lastnameIni = 'Gu';
                        $firstnameIni = 'Ad';
                    } else {
                        $lastnameIni = substr($userDolibarr->lastname, 0, 2);
                        $firstnameIni = substr($userDolibarr->firstname, 0, 2);
                    }
                    if ($lastnameIni . $firstnameIni == $loading->disp && $userDolibarr->lastname == 'SuperAdmin') {
                        $disp = 'Adrien GUNDOGAN';
                    } elseif ($lastnameIni . $firstnameIni == $loading->disp) {
                        $disp = $userDolibarr->lastname . ' ' . $userDolibarr->firstname;
                    } elseif (!isset($disp) && $lastnameIni . $firstnameIni <> $loading->disp) {
                        $disp = $loading->disp;
                    }
                }
            }

//            //link to the mother loading of the subloading
//            if (substr_count($loading->atrnr, '-') <> 0) {
//                $atrnr1 = explode('-', $loading->atrnr)[0];
//                $atrnr2 = array_slice(explode('-', $loading->atrnr), 1);
//                $atrnr2 = implode('-', $atrnr2);
//            }

            //get all transfers to fulfill the table
            $listPalletstransfers = Palletstransfer::where('loading_atrnr', $atrnr)->orderBy('id', 'asc')->get();
            $listAccountsTransfers = [];
            if (!$listPalletstransfers->isEmpty()) {
                foreach ($listPalletstransfers as $transfer) {
                    if (!in_array($transfer->creditAccount, $listAccountsTransfers)) {
                        $listAccountsTransfers[] = $transfer->creditAccount;
                    }
                    if (!in_array($transfer->debitAccount, $listAccountsTransfers)) {
                        $listAccountsTransfers[] = $transfer->debitAccount;
                    }
                }
            }

            //get only the normal transfers (deposit/withdrawal)
            $listPalletstransfersNormal = Palletstransfer::where('loading_atrnr', $atrnr)->where(function ($q) {
                $q->where('type', 'Deposit_Only')->orWhere('type', 'Withdrawal_Only')->orWhere('type', 'Withdrawal-Deposit')->orWhere('type', 'Deposit-Withdrawal');
            })->orderBy('id', 'asc')->get();
            //get only the correcting transfers (sale/purchase)
            $listPalletstransfersCorrecting = Palletstransfer::where('loading_atrnr', $atrnr)->where(function ($q) {
                $q->where('type', 'Purchase')->orWhere('type', 'Sale')->orWhere('type', 'Debt')->orWhere('type', 'Other');
            })->orderBy('id', 'asc')->get();

            if (isset ($actionForm) && ($actionForm == 'addPalletstransfer' || $actionForm == 'okSubmitAddModal' || $actionForm == 'closeSubmitAddModal')) {
                //get data from the form
                $date = Input::get('date');
                $type = Input::get('type');
                $details = Input::get('details');
                $notExchanging = Input::get('notExchanging');
                $palletsNumber = Input::get('palletsNumber');
                $creditAccount2 = null;
                $debitAccount2 = null;
                $palletsNumber2 = null;
                $palletsNumber3 = null;
                $creditAccount3 = null;
                $debitAccount3 = null;
                $transferToCorrect = Input::get('transferToCorrect');
//            $transferToCorrect2 = Input::get('transferToCorrect2');

                if (!isset($type)) {
                    $creditAccount = null;
                    $debitAccount = null;
                } else {
                    if ($type == 'Deposit-Withdrawal') {
                        $creditAccount = Input::get('creditAccountDW');
                        $debitAccount = Input::get('debitAccountDWD');
                        $creditAccount2 = Input::get('creditAccount2DW');
                        $debitAccount2 = Input::get('debitAccount2DW');
                        $palletsNumber2 = Input::get('palletsNumber2DW');
                    } elseif ($type == 'Deposit_Only') {
                        $debitAccount = Input::get('debitAccountDWD');
                        $creditAccount = Input::get('creditAccountDDebtOther');
                    } elseif ($type == 'Withdrawal_Only') {
                        $creditAccount = Input::get('creditAccountW');
                        $debitAccount = Input::get('debitAccountWDebtOther');
                    } elseif ($type == 'Debt' || $type == 'Other') {
                        $creditAccount = Input::get('creditAccountDDebtOther');
                        $debitAccount = Input::get('debitAccountWDebtOther');
                    } elseif ($type == 'Purchase') {
                        $creditAccount = Input::get('creditAccountPS');
                        $debitAccount = Input::get('debitAccountPS');
                        $palletsNumber2 = Input::get('palletsNumber2PS');
                        $creditAccount2 = Input::get('creditAccount2PS');
                        $debitAccount2 = Input::get('debitAccount2PS');
                    }
                    if (isset($notExchanging) && (($type == 'Deposit-Withdrawal' && isset($palletsNumber) && isset($palletsNumber2) && (($palletsNumber > $loading->anz && $palletsNumber2 > $loading->anz && $palletsNumber - $palletsNumber2 > 0) || ($palletsNumber < $loading->anz && $palletsNumber2 < $loading->anz && $palletsNumber2 - $palletsNumber > 0) || ($palletsNumber == $loading->anz && $palletsNumber2 > $loading->anz) || ($palletsNumber < $loading->anz && $palletsNumber2 >= $loading->anz)))
                            || ($type == 'Withdrawal_Only'))
                    ) {
                        $palletsNumber3 = Input::get('palletsNumber3a');
                        $creditAccount3 = Input::get('creditAccount3a');
                        $debitAccount3 = Input::get('debitAccount3a');
                    } elseif (isset($notExchanging) && (($type == 'Deposit-Withdrawal' && isset($palletsNumber) && isset($palletsNumber2) && (($palletsNumber > $loading->anz && $palletsNumber2 > $loading->anz && $palletsNumber - $palletsNumber2 < 0) || ($palletsNumber < $loading->anz && $palletsNumber2 < $loading->anz && $palletsNumber2 - $palletsNumber < 0) || ($palletsNumber == $loading->anz && $palletsNumber2 < $loading->anz) || ($palletsNumber > $loading->anz && $palletsNumber2 <= $loading->anz)))
                            || ($type == 'Deposit_Only'))
                    ) {
                        $palletsNumber3 = Input::get('palletsNumber3b');
                        $creditAccount3 = Input::get('creditAccount3b');
                        $debitAccount3 = Input::get('debitAccount3b');
                    }
                }
            }

            if (isset($actionForm) && ($actionForm == 'Update' || $actionForm == 'updateValidatePT' || $actionForm == 'closeUpdateModal' || $actionForm == 'updateCreateCarrier' || $actionForm == 'updateCreateTruck')) {
                $subfrachter = Input::get('subfrachter');
                $kennzeichen = Input::get('kennzeichen');
                if (!isset($kennzeichen)) {
                    $kennzeichen = 'OTHER';
                }
                $view = $this->updatePanel1($actionForm, $loading->atrnr, $subfrachter, $kennzeichen);
                if ($view == 'ok') {
                    return redirect()->back();
                } elseif ($view == 'testCarrier') {
                    $loading = Loading::where('atrnr', $loading->atrnr)->first();
                    $listPossibilitiesCarriers = Palletsaccount::where('type', 'Carrier')->where(function ($q) use ($subfrachter) {
                        if (!strpos(',', $subfrachter)) {
                            $subfrachterName = str_replace('  ', ' ', trim($subfrachter));
                            $partsSubfrachterName = explode(' ', $subfrachterName);
                            foreach ($partsSubfrachterName as $partName) {
                                $q->orWhere('nickname', 'LIKE', '%' . $partName . '%');
                            }
                        } else {
                            $subfrachterName = str_replace('  ', ' ', trim(explode(',', $subfrachter)[0]));
                            $partsSubfrachterName = explode(' ', $subfrachterName);
                            foreach ($partsSubfrachterName as $partName) {
                                $q->orWhere('nickname', 'LIKE', '%' . $partName . '%');
                            }
                            $subfrachterAdress = trim(explode(',', $subfrachter)[1]);
                            if (!strpos('-', $subfrachterAdress)) {
                                $subfrachterAdress = str_replace('  ', ' ', trim($subfrachterAdress));
                                $partsSubfrachterAdress = explode(' ', $subfrachterAdress);
                                foreach ($partsSubfrachterAdress as $partAdress) {
                                    $q->orWhere('adress', 'LIKE', '%' . $partAdress . '%');
                                }
                            } else {
                                $partsSubfrachterAdress1 = trim(explode('-', $subfrachterAdress)[0]);
                                $q->orWhere('adress', 'LIKE', $partsSubfrachterAdress1 . '%');

                                $partsSubfrachterAdress2 = explode(' ', str_replace('  ', ' ', trim(explode('-', $subfrachterAdress)[1])));
                                foreach ($partsSubfrachterAdress2 as $partsAdress) {
                                    $q->orWhere('adress', 'LIKE', '%' . $partsAdress . '%');
                                }
                            }
                        }
                    })->orderBy('nickname', 'asc')->get();

                    return view('loadings.detailsLoading', compact('subfrachter', 'loading', 'disp', 'atrnr1', 'atrnr2', 'listPalletsAccounts', 'truckAssociated', 'listAccountsTransfers', 'listTrucksAccounts', 'listPalletstransfers', 'listPalletstransfersNormal', 'listPalletstransfersCorrecting', 'theoricalNumberPalletsTruck', 'realNumberPalletsTruck',
                        'actionForm', 'listPossibilitiesCarriers'
                    ));
                } elseif ($view == 'createCarrier') {
                    $originalPage = 'detailsLoading-' . $loading->atrnr;
                    $listWarehouses = DB::table('warehouses')->get();
                    $type = 'Carrier';

                    if (count(explode(',', $subfrachter)) > 2) {
                        $adress = trim(explode(',', $subfrachter)[count(explode(',', $subfrachter)) - 1]);
                        $name = trim(str_replace($adress, '', $$subfrachter));
                        $country = null;
                        $zipcode = null;
                        $town = null;
                    } else {
                        $name = trim(explode(',', $subfrachter)[0]);
                        $adress = trim(explode(',', $subfrachter)[1]);
                        $country = trim(explode('-', $adress)[0]);
                        $zipTown = trim(explode('-', $adress)[1]);
                        $zipcode = trim(explode(' ', $zipTown)[0]);
                        $town = str_replace($zipcode, '', $zipTown);
                    }
                    $nickname = $name;
                    $atrnr = $loading->atrnr;
                    return view('palletsaccounts.addPalletsaccount', compact('atrnr', 'name', 'nickname', 'adress', 'country', 'town', 'zipcode', 'type', 'listWarehouses', 'originalPage'));
                } elseif ($view == 'testTruck') {
                    $loading = Loading::where('atrnr', $loading->atrnr)->first();
                    $listPossibilitiesTrucks = Truck::where('name', explode(',', $loading->subfrachter)[0])->orderBy('licensePlate', 'asc')->get();
                    return view('loadings.detailsLoading', compact('kennzeichen', 'loading', 'disp', 'atrnr1', 'atrnr2', 'listPalletsAccounts', 'truckAssociated', 'listAccountsTransfers', 'listTrucksAccounts', 'listPalletstransfers', 'listPalletstransfersNormal', 'listPalletstransfersCorrecting', 'theoricalNumberPalletsTruck', 'realNumberPalletsTruck',
                        'actionForm', 'listPossibilitiesTrucks'
                    ));
                } elseif ($view == 'createTruck') {
                    $originalPage = 'detailsLoading-' . $loading->atrnr;
                    $name = trim(explode(',', $loading->subfrachter)[0]);
                    $palletsaccount_name = $name;
                    $licensePlate = trim($kennzeichen);
                    $atrnr = $loading->atrnr;
                    $listPalletsAccounts = DB::table('palletsaccounts')->where('type', 'Carrier')->orderBy('nickname', 'asc')->get();
                    return view('trucks.addTruck', compact('atrnr', 'palletsaccount_name', 'licensePlate', 'name', 'listPalletsAccounts', 'originalPage'));
                }

            } elseif (isset($actionForm) && $actionForm == 'addTransferForm') {
                $palletsNumber = $loading->anz;
                $palletsNumber2 = $palletsNumber;
                return view('loadings.detailsLoading', compact('loading', 'disp', 'atrnr1', 'atrnr2', 'palletsNumber', 'palletsNumber2', 'truckAssociated', 'listPalletsAccounts', 'listTrucksAccounts', 'listAccountsTransfers', 'listPalletstransfers', 'listPalletstransfersNormal', 'listPalletstransfersCorrecting', 'actionForm', 'theoricalNumberPalletsTruck', 'realNumberPalletsTruck'));
            } elseif (isset($actionForm) && explode('-', $actionForm)[0] == 'showAddCorrectingTransfer') {
                //get data to pre fulfill the field in the form
                $transferNormal = Palletstransfer::where('id', explode('-', $actionForm)[1])->first();
                if ($transferNormal->palletsNumber <= $loading->anz) {
                    if ($transferNormal->type == 'Deposit_Only' || $transferNormal->type == 'Withdrawal_Only') {
                        $palletsNumber = $transferNormal->palletsNumber;
                    } elseif ($transferNormal->type == 'Deposit-Withdrawal' || $transferNormal->type == 'Withdrawal-Deposit') {
                        $palletsNumber = $loading->anz - $transferNormal->palletsNumber;
                    }
                } else {
                    if ($transferNormal->type == 'Deposit_Only' || $transferNormal->type == 'Withdrawal_Only') {
                        $palletsNumber = $transferNormal->palletsNumber;
                    } elseif ($transferNormal->type == 'Deposit-Withdrawal' || $transferNormal->type == 'Withdrawal-Deposit') {
                        $palletsNumber = $transferNormal->palletsNumber - $loading->anz;
                    }
                }
                $palletsNumber2 = $palletsNumber;
                $creditAccountCorr = $transferNormal->creditAccount;
                $debitAccountCorr = $transferNormal->debitAccount;
                return view('loadings.detailsLoading', compact('debitAccountCorr', 'creditAccountCorr', 'palletsNumber', 'palletsNumber2', 'loading', 'disp', 'atrnr1', 'atrnr2', 'listPalletsAccounts', 'listTrucksAccounts', 'truckAssociated', 'listAccountsTransfers', 'listPalletstransfers', 'listPalletstransfersNormal', 'listPalletstransfersCorrecting', 'date', 'actionForm', 'theoricalNumberPalletsTruck', 'realNumberPalletsTruck'));
            } elseif (isset($actionForm) && $actionForm == 'closeAddForm') {
                return redirect()->back();
            } elseif (isset($actionForm) && $actionForm == 'addPalletstransfer') {
                $addTransferForm = $this->addPalletsTransfer($loading, $notExchanging, $truckAssociated, $type, $debitAccount, $creditAccount, $debitAccount2, $creditAccount2, $palletsNumber, $palletsNumber2, $creditAccount3, $debitAccount3, $palletsNumber3, $transferToCorrect);
                $loading = Loading::where('atrnr', $loading->atrnr)->first();
                if ($addTransferForm == 'error') {
                    //redirect with error
                    if ($type == 'Deposit-Withdrawal' || $type == 'Deposit_Only' || $type == 'Withdrawal_Only') {
                        $actionForm = 'addTransferForm';
                        return view('loadings.detailsLoading', compact('loading', 'disp', 'atrnr1', 'atrnr2', 'date', 'details', 'type', 'creditAccount', 'debitAccount', 'palletsNumber', 'truckAssociated', 'listPalletsAccounts', 'listTrucksAccounts', 'listAccountsTransfers', 'listPalletstransfers', 'listPalletstransfersNormal', 'listPalletstransfersCorrecting', 'actionForm', 'theoricalNumberPalletsTruck', 'realNumberPalletsTruck'));
                    } elseif ($type == 'Purchase' || $type == 'Other' || $type == 'Debt') {
                        $actionForm = 'showAddCorrectingTransfer-' . $transferToCorrect;
                        $transferNormal = Palletstransfer::where('id', explode('-', $actionForm)[1])->first();
                        if ($transferNormal->palletsNumber <= $loading->anz) {
                            if ($transferNormal->type == 'Deposit_Only' || $transferNormal->type == 'Withdrawal_Only') {
                                $palletsNumber = $transferNormal->palletsNumber;
                            } elseif ($transferNormal->type == 'Deposit-Withdrawal' || $transferNormal->type == 'Withdrawal-Deposit') {
                                $palletsNumber = $loading->anz - $transferNormal->palletsNumber;
                            }
                        } else {
                            if ($transferNormal->type == 'Deposit_Only' || $transferNormal->type == 'Withdrawal_Only') {
                                $palletsNumber = $transferNormal->palletsNumber;
                            } elseif ($transferNormal->type == 'Deposit-Withdrawal' || $transferNormal->type == 'Withdrawal-Deposit') {
                                $palletsNumber = $transferNormal->palletsNumber - $loading->anz;
                            }
                        }
                        $creditAccountCorr = $transferNormal->creditAccount;
                        $debitAccountCorr = $transferNormal->debitAccount;

                        return view('loadings.detailsLoading', compact('debitAccountCorr', 'creditAccountCorr', 'palletsNumber', 'palletsNumber2', 'loading', 'disp', 'atrnr1', 'atrnr2', 'date', 'details', 'type', 'creditAccount', 'debitAccount', 'creditAccount2', 'debitAccount2', 'truckAssociated', 'listPalletsAccounts', 'listTrucksAccounts', 'listAccountsTransfers', 'listPalletstransfers', 'listPalletstransfersNormal', 'listPalletstransfersCorrecting', 'actionForm', 'theoricalNumberPalletsTruck', 'realNumberPalletsTruck'));
                    } elseif (!isset($type) && isset($transferToCorrect)) {
                        $actionForm = 'showAddCorrectingTransfer-' . $transferToCorrect;
                        $transferNormal = Palletstransfer::where('id', $transferToCorrect)->first();
                        $creditAccountCorr = $transferNormal->creditAccount;
                        $debitAccountCorr = $transferNormal->debitAccount;
                        return view('loadings.detailsLoading', compact('debitAccountCorr', 'creditAccountCorr', 'palletsNumber', 'palletsNumber2', 'loading', 'disp', 'atrnr1', 'atrnr2', 'listPalletsAccounts', 'listTrucksAccounts', 'truckAssociated', 'listAccountsTransfers', 'listPalletstransfers', 'listPalletstransfersNormal', 'listPalletstransfersCorrecting', 'date', 'actionForm', 'theoricalNumberPalletsTruck', 'realNumberPalletsTruck'));
                    } else {
                        $actionForm = 'addTransferForm';
                        return view('loadings.detailsLoading', compact('loading', 'disp', 'atrnr1', 'atrnr2', 'palletsNumber', 'palletsNumber2', 'truckAssociated', 'listPalletsAccounts', 'listTrucksAccounts', 'listAccountsTransfers', 'listPalletstransfers', 'listPalletstransfersNormal', 'listPalletstransfersCorrecting', 'actionForm', 'theoricalNumberPalletsTruck', 'realNumberPalletsTruck'));
                    }
                } else {
                    //redirect with modal open to validate the transfer adding
                    if ($type == 'Deposit-Withdrawal' || $type == 'Deposit_Only' || $type == 'Withdrawal_Only') {
                        return view('loadings.detailsLoading', compact('loading', 'disp', 'atrnr1', 'atrnr2', 'date', 'details', 'type', 'creditAccount', 'debitAccount', 'palletsNumber', 'creditAccount2', 'debitAccount2', 'palletsNumber2', 'creditAccount3', 'debitAccount3', 'palletsNumber3', 'truckAssociated', 'listPalletsAccounts', 'listTrucksAccounts', 'listAccountsTransfers', 'listPalletstransfers', 'listPalletstransfersNormal', 'listPalletstransfersCorrecting', 'actionForm', 'theoricalNumberPalletsTruck', 'realNumberPalletsTruck'));
                    } elseif ($type == 'Purchase' || $type == 'Other' || $type == 'Debt') {
                        $showAddCorrectingTransfer = true;
                        return view('loadings.detailsLoading', compact('loading', 'disp', 'atrnr1', 'atrnr2', 'date', 'details', 'type', 'transferToCorrect', 'creditAccount', 'debitAccount', 'palletsNumber', 'creditAccount2', 'debitAccount2', 'truckAssociated', 'palletsNumber2', 'listPalletsAccounts', 'listTrucksAccounts', 'listAccountsTransfers', 'listPalletstransfers', 'listPalletstransfersNormal', 'listPalletstransfersCorrecting', 'actionForm', 'showAddCorrectingTransfer', 'theoricalNumberPalletsTruck', 'realNumberPalletsTruck'));
                    }
                }
            } elseif (isset($actionForm) && $actionForm == 'okSubmitAddModal') {
                //accept to add the transfer
                $this->validateAddPalletsTransfer($loading, $type, $date, $details, $creditAccount, $debitAccount, $creditAccount2, $debitAccount2, $palletsNumber, $palletsNumber2, $creditAccount3, $debitAccount3, $palletsNumber3, $transferToCorrect, $notExchanging);
                //get all transfers
                $listPalletstransfers = Palletstransfer::where('loading_atrnr', $atrnr)->get();
                $listAccountsTransfers = [];
                if (!$listPalletstransfers->isEmpty()) {
                    foreach ($listPalletstransfers as $transfer) {
                        if (!in_array($transfer->creditAccount, $listAccountsTransfers)) {
                            $listAccountsTransfers[] = $transfer->creditAccount;
                        }
                        if (!in_array($transfer->debitAccount, $listAccountsTransfers)) {
                            $listAccountsTransfers[] = $transfer->debitAccount;
                        }
                    }
                }
                $this->state($loading, $listPalletstransfers);
                //re open the right add form
                if (!isset($transferToCorrect)) {
                    session()->flash('openAddForm', 'addTransferForm');
                }
                return redirect()->back();
            } elseif (isset($actionForm) && $actionForm == 'closeSubmitAddModal') {
                //refuse to add the transfer -> good redirection
                if (isset($transferToCorrect)) {
                    $actionForm = 'showAddCorrectingTransfer-' . $transferToCorrect;
                    $transferNormal = Palletstransfer::where('id', explode('-', $actionForm)[1])->first();
                    if ($transferNormal->palletsNumber <= $loading->anz) {
                        if ($transferNormal->type == 'Deposit_Only' || $transferNormal->type == 'Withdrawal_Only') {
                            $palletsNumber = $transferNormal->palletsNumber;
                        } elseif ($transferNormal->type == 'Deposit-Withdrawal' || $transferNormal->type == 'Withdrawal-Deposit') {
                            $palletsNumber = $loading->anz - $transferNormal->palletsNumber;
                        }
                    } else {
                        if ($transferNormal->type == 'Deposit_Only' || $transferNormal->type == 'Withdrawal_Only') {
                            $palletsNumber = $transferNormal->palletsNumber;
                        } elseif ($transferNormal->type == 'Deposit-Withdrawal' || $transferNormal->type == 'Withdrawal-Deposit') {
                            $palletsNumber = $transferNormal->palletsNumber - $loading->anz;
                        }
                    }
                    $creditAccountCorr = $transferNormal->creditAccount;
                    $debitAccountCorr = $transferNormal->debitAccount;
                    return view('loadings.detailsLoading', compact('debitAccountCorr', 'creditAccountCorr', 'loading', 'disp', 'atrnr1', 'atrnr2', 'type', 'details', 'palletsNumber', 'debitAccount', 'creditAccount', 'palletsNumber2', 'debitAccount2', 'creditAccount2', 'listPalletsAccounts', 'listTrucksAccounts', 'truckAssociated', 'listAccountsTransfers', 'listPalletstransfers', 'listPalletstransfersNormal', 'listPalletstransfersCorrecting', 'date', 'actionForm', 'theoricalNumberPalletsTruck', 'realNumberPalletsTruck'));
                } else {
                    $actionForm = 'addTransferForm';
                    return view('loadings.detailsLoading', compact('loading', 'disp', 'atrnr1', 'atrnr2', 'details', 'date', 'type', 'palletsNumber', 'debitAccount', 'creditAccount', 'palletsNumber2', 'debitAccount2', 'creditAccount2', 'palletsNumber3', 'debitAccount3', 'creditAccount3', 'truckAssociated', 'listPalletsAccounts', 'listTrucksAccounts', 'listAccountsTransfers', 'listPalletstransfers', 'listPalletstransfersNormal', 'listPalletstransfersCorrecting', 'actionForm', 'theoricalNumberPalletsTruck', 'realNumberPalletsTruck'));
                }
            } elseif (isset($actionForm) && $actionForm == 'clearTransfers') {
                $this->deleteAllTransfers($loading, $listPalletstransfers);
                return redirect()->back();
            } elseif (isset($actionForm) && explode('-', $actionForm)[0] == 'delete') {
                //get all the data necessary to display the transfer details page
                $transfer = Palletstransfer::where('id', explode('-', $actionForm)[1])->first();
                $listPalletsAccounts = Palletsaccount::where('type', 'Network')->orWhere('type', 'Other')->orderBy('nickname', 'asc')->get();
                $listTrucksAccounts = Truck::orderBy('name', 'asc')->get();
                $listAtrnr = [];
                foreach (Loading::where('pt', 'JA')->orderBy('atrnr', 'asc')->get() as $loading) {
                    $listAtrnr[] = $loading->atrnr;
                }
                $filesNames = $this->actualDocuments($transfer->id);
                $listPalletstransfersNormal = Palletstransfer::where('loading_atrnr', $atrnr)->where(function ($q) {
                    $q->where('type', 'Deposit_Only')->orWhere('type', 'Withdrawal_Only')->orWhere('type', 'Withdrawal-Deposit')->orWhere('type', 'Deposit-Withdrawal');
                })->get();
                $delete = explode('-', $actionForm)[1];
                //redirect to the details page of the transfer to delete it
                return view('palletstransfers.detailsPalletstransfer', compact('transfer', 'listAtrnr', 'listPalletstransfersNormal', 'listPalletsAccounts', 'listTrucksAccounts', 'filesNames', 'delete'));
            } elseif (isset($actionForm) && explode('-', $actionForm)[0] == 'deleteDocument') {
                $this->deleteDocument(Palletstransfer::where('id', trim(explode('-', $actionForm)[2]))->first(), trim(explode('-', $actionForm)[1]));
                $this->state($loading, Palletstransfer::where('loading_atrnr', $atrnr)->get());
                return redirect()->back();
            } elseif (isset($actionForm) && explode('-', $actionForm)[0] == 'submitPallets') {
                //to update the transfer, get all data
                $transfer = Palletstransfer::where('id', explode('-', $actionForm)[1])->first();

                $documents = $request->file('documentsTransfer' . explode('-', $actionForm)[1]);
                $proof = Input::get('proof');
                $details = Input::get('details' . explode('-', $actionForm)[1]);
                $validate = Input::get('validate' . explode('-', $actionForm)[1]);
                if (isset($details) || isset($validate)) {
                    if (isset($documents)) {
                        $this->upload($documents, $transfer, $loading);
                    }
                    if (isset($proof)) {
                        $this->uploadProof($proof, $transfer, $actionForm, $loading);
                    }
                    $filesNames = $this->actualDocuments($transfer->id);

                    $submitPalletsNormal = $this->defineSubmitPalletsValue($listPalletstransfersNormal, $listPalletstransfersCorrecting, explode('-', $actionForm)[1])[0];
                    $submitPalletsCorrecting = $this->defineSubmitPalletsValue($listPalletstransfersNormal, $listPalletstransfersCorrecting, explode('-', $actionForm)[1])[1];

                    $view = $this->updateTransfer($transfer, $validate, $details, $loading, $filesNames);
                    session()->flash('openPanelDetails', $transfer->id);
                    if ($view == 'error') {
                        return redirect()->back();
                    } else {
                        //get only the normal transfers (deposit/withdrawal)
                        $listPalletstransfersNormal = Palletstransfer::where('loading_atrnr', $atrnr)->where(function ($q) {
                            $q->where('type', 'Deposit_Only')->orWhere('type', 'Withdrawal_Only')->orWhere('type', 'Withdrawal-Deposit')->orWhere('type', 'Deposit-Withdrawal');
                        })->get();
                        //get only the correcting transfers (sale/purchase)
                        $listPalletstransfersCorrecting = Palletstransfer::where('loading_atrnr', $atrnr)->where(function ($q) {
                            $q->where('type', 'Purchase')->orWhere('type', 'Sale')->orWhere('type', 'Debt')->orWhere('type', 'Other');
                        })->get();
                        $transfer = Palletstransfer::where('id', $transfer->id)->first();

                        return view('loadings.detailsLoading', compact('loading', 'disp', 'atrnr1', 'atrnr2', 'truckAssociated', 'listPalletsAccounts', 'listTrucksAccounts', 'listAccountsTransfers', 'listPalletstransfers', 'listPalletstransfersNormal', 'listPalletstransfersCorrecting',
                            'transfer', 'submitPalletsNormal', 'submitPalletsCorrecting', 'filesNames', 'theoricalNumberPalletsTruck', 'realNumberPalletsTruck'));
                    }
                } else {
                    if (isset($documents)) {
                        $this->upload($documents, $transfer, $loading);
                    }
                    if (isset($proof)) {
                        $this->uploadProof($proof, $transfer, $actionForm, $loading);
                    }
                    return redirect()->back();
                }

            } elseif (isset($actionForm) && explode('-', $actionForm)[0] == 'okSubmitPalletsModal') {
                $transfer = Palletstransfer::where('id', explode('-', $actionForm)[1])->first();
//                $okSubmitPalletsModalNormal = $this->defineSubmitPalletsValue($listPalletstransfersNormal, $listPalletstransfersCorrecting, explode('-', $actionForm)[1])[0];
//                $okSubmitPalletsModalCorrecting = $this->defineSubmitPalletsValue($listPalletstransfersNormal, $listPalletstransfersCorrecting, explode('-', $actionForm)[1])[1];
//                $filesNames = $this->actualDocuments($transfer->id);

                $this->validateUpdateTransfer($transfer, $loading);
                session()->flash('openPanelDetails', $transfer->id);
//                $transfer = Palletstransfer::where('id', explode('-', $actionForm)[1])->first();
//                if ($view == 'ok') {
//                    //get only the normal transfers (deposit/withdrawal)
//                    $listPalletstransfersNormal = Palletstransfer::where('loading_atrnr', $atrnr)->where(function ($q) {
//                        $q->where('type', 'Deposit_Only')->orWhere('type', 'Withdrawal_Only')->orWhere('type', 'Withdrawal-Deposit')->orWhere('type', 'Deposit-Withdrawal');
//                    })->get();
//                    //get only the correcting transfers (sale/purchase)
//                    $listPalletstransfersCorrecting = Palletstransfer::where('loading_atrnr', $atrnr)->where(function ($q) {
//                        $q->where('type', 'Purchase')->orWhere('type', 'Sale')->orWhere('type', 'Debt')->orWhere('type', 'Other');
//                    })->get();
//
//                    return view('loadings.detailsLoading', compact('loading', 'disp', 'atrnr1', 'atrnr2', 'truckAssociated', 'listPalletsAccounts', 'listTrucksAccounts', 'listAccountsTransfers', 'listPalletstransfers', 'listPalletstransfersNormal', 'listPalletstransfersCorrecting',
//                        'transfer', 'okSubmitPalletsModalNormal', 'okSubmitPalletsModalCorrecting', 'filesNames', 'theoricalNumberPalletsTruck', 'realNumberPalletsTruck'));
//                } elseif ($view == 'back') {
                return redirect()->back();
//                }
            } elseif (isset($actionForm) && explode('-', $actionForm)[0] == 'closeSubmitPalletsModal') {
                //refuse the transfer update
//                $this->refuseValidateUpdateTransfer(explode('-', $actionForm)[1], $loading);
                return redirect()->back();
//            } elseif (isset($actionForm) && explode('-', $actionForm)[0] == 'okSubmitPalletsValidateModal') {
//                $this->validateCompleteUpdateTransfer(explode('-', $actionForm)[1], $loading, $listPalletstransfers);
//                return redirect()->back();
            }
        } else {
            return view('auth.login');
        }
    }


    /**
     * Prepare the data to add a new pallets transfer, then redirect to a page to confirm the adding
     * @param $loading
     * @param $notExchanging
     * @param $truckAssociated
     * @param $type
     * @param $debitAccount
     * @param $creditAccount
     * @param $debitAccount2
     * @param $creditAccount2
     * @param $palletsNumber
     * @param $palletsNumber2
     * @param $creditAccount3
     * @param $debitAccount3
     * @param $palletsNumber3
     * @param $transferToCorrect
     * @return string
     */
    public function addPalletsTransfer($loading, $notExchanging, $truckAssociated, $type, $debitAccount, $creditAccount, $debitAccount2, $creditAccount2, $palletsNumber, $palletsNumber2, $creditAccount3, $debitAccount3, $palletsNumber3, $transferToCorrect)
    {
        if (!isset($debitAccount) || !isset($creditAccount) || !(isset($palletsNumber))) {
            $view = 'error';
            session()->flash('errorFields', "The fields have not been filled as expected");
        } elseif ($debitAccount == $creditAccount || (isset($debitAccount2) && isset($creditAccount2) && $debitAccount2 == $creditAccount2) || (isset($debitAccount3) && isset($creditAccount3) && $debitAccount3 == $creditAccount3)) {
            $view = 'error';
            session()->flash('errorFields', "The fields have not been filled as expected : debit account and credit account must be different");
        } elseif ($type == 'Deposit-Withdrawal') {
            if (isset($notExchanging) && $palletsNumber2 <> $palletsNumber && (!isset($debitAccount3) || !isset($creditAccount3) || !(isset($palletsNumber3)))) {
                $view = 'error';
                session()->flash('errorFields', "The fields have not been filled as expected");
            } else {
                $view = 'ok';
            }
        } elseif ($type == 'Deposit_Only' || $type == 'Withdrawal_Only') {
            if (isset($notExchanging) && (!isset($debitAccount3) || !isset($creditAccount3) || !(isset($palletsNumber3)))) {
                $view = 'error';
                session()->flash('errorFields', "The fields have not been filled as expected");
            } else {
                $view = 'ok';
            }
        } elseif ($type == 'Purchase') {
            if (!isset($transferToCorrect) && !isset($debitAccount) && !isset($creditAccount) && !isset($palletsNumber) && !isset($debitAccount2) && !isset($creditAccount2) && !isset($palletsNumber2)) {
                $view = 'error';
                session()->flash('errorFields', "The fields have not been filled as expected");
            } else {
                $view = 'ok';
            }
        } else {
            if (!isset($transferToCorrect) && !isset($debitAccount) && !isset($creditAccount) && !(isset($palletsNumber))) {
                $view = 'error';
                session()->flash('errorFields', "The fields have not been filled as expected");
            } else {
                $view = 'ok';
            }
        }

        if ($view == 'ok') {
            if (isset($notExchanging)) {
                Palletsaccount::where('nickname', $truckAssociated->name)->update(['notExchange' => true]);
                Loading::where('atrnr', $loading->atrnr)->update(['notExchange' => true]);
            } else {
                Palletsaccount::where('nickname', $truckAssociated->name)->update(['notExchange' => false]);
                Loading::where('atrnr', $loading->atrnr)->update(['notExchange' => false]);
            }

            $actualTheoricalDebitPalletsNumber = $this->actualTheoricalPalletsNumber($creditAccount, $debitAccount)[0];
            $actualTheoricalCreditPalletsNumber = $this->actualTheoricalPalletsNumber($creditAccount, $debitAccount)[1];
            $this->displayAccounts($creditAccount, $debitAccount, null);
            session()->put('palletsNumberCreditAccount', $actualTheoricalCreditPalletsNumber);
            session()->put('palletsNumberDebitAccount', $actualTheoricalDebitPalletsNumber);
            if (($type == 'Deposit-Withdrawal' || $type == 'Purchase') && isset($creditAccount2) && isset($debitAccount2)) {
                $this->displayAccounts($creditAccount2, $debitAccount2, 2);
                if ($debitAccount2 <> $creditAccount) {
                    $actualTheoricalDebitPalletsNumber2 = $this->actualTheoricalPalletsNumber($creditAccount2, $debitAccount2)[0];
                    session()->put('palletsNumberDebitAccount2', $actualTheoricalDebitPalletsNumber2);
                } else {
                    session()->put('palletsNumberDebitAccount2', $actualTheoricalCreditPalletsNumber + $palletsNumber);
                }
                if ($creditAccount2 <> $debitAccount) {
                    $actualTheoricalCreditPalletsNumber2 = $this->actualTheoricalPalletsNumber($creditAccount2, $debitAccount2)[1];
                    session()->put('palletsNumberCreditAccount2', $actualTheoricalCreditPalletsNumber2);
                } else {
                    session()->put('palletsNumberCreditAccount2', $actualTheoricalDebitPalletsNumber - $palletsNumber);
                }
            }
            if (($type == 'Deposit-Withdrawal' || $type == 'Deposit_Only' || $type == 'Withdrawal_Only') && isset($creditAccount3) && isset($debitAccount3)) {
                $this->displayAccounts($creditAccount3, $debitAccount3, 3);
//                if ($debitAccount3 == $creditAccount) {
//                    $actualDebtDebitPalletsNumber3 = $actualTheoricalCreditPalletsNumber + $palletsNumber - $palletsNumber2;
//                } elseif ($debitAccount3 == $debitAccount) {
//                    $actualDebtDebitPalletsNumber3 = $actualTheoricalDebitPalletsNumber - $palletsNumber + $palletsNumber2;
//                } else {
                $actualDebtDebitPalletsNumber3 = $this->actualTheoricalDebtPalletsNumber($creditAccount3, $debitAccount3)[0];

//                }
                session()->put('palletsNumberDebtDebitAccount3', $actualDebtDebitPalletsNumber3);
//                if ($creditAccount3 == $creditAccount) {
//                    $actualTheoricalCreditPalletsNumber3 = $actualTheoricalCreditPalletsNumber + $palletsNumber - $palletsNumber2;
//                } elseif ($creditAccount3 == $debitAccount) {
//                    $actualTheoricalCreditPalletsNumber3 = $actualTheoricalDebitPalletsNumber - $palletsNumber + $palletsNumber2;
//                } else {
                $actualDebtCreditPalletsNumber3 = $this->actualTheoricalDebtPalletsNumber($creditAccount3, $debitAccount3)[1];
                session()->put('palletsNumberDebtCreditAccount3', $actualDebtCreditPalletsNumber3);
            }

//            }
        }
        return $view;
    }

    /**
     * get actual theorical pallets number of the credit account and debit account
     * @param $creditAccount
     * @param $debitAccount
     * @return array
     */
    public function actualTheoricalPalletsNumber($creditAccount, $debitAccount)
    {
        if (strpos($debitAccount, '-') == 5 && explode('-', $debitAccount)[0] == 'truck') {
            //truck account
            $actualTheoricalDebitPalletsNumber = Truck::where('id', explode('-', $debitAccount)[1])->value('theoricalNumberPallets');
        } elseif (strpos($debitAccount, '-') == 7 && explode('-', $debitAccount)[0] == 'account') {
            //others accounts (network, other)
            $actualTheoricalDebitPalletsNumber = Palletsaccount::where('id', explode('-', $debitAccount)[1])->value('theoricalNumberPallets');
        }

        if (strpos($creditAccount, '-') == 5 && explode('-', $creditAccount)[0] == 'truck') {
            //truck account
            $actualTheoricalCreditPalletsNumber = Truck::where('id', explode('-', $creditAccount)[1])->value('theoricalNumberPallets');
        } elseif (strpos($creditAccount, '-') == 7 && explode('-', $creditAccount)[0] == 'account') {
            //others accounts (network, other)
            $actualTheoricalCreditPalletsNumber = Palletsaccount::where('id', explode('-', $creditAccount)[1])->value('theoricalNumberPallets');
        }

        return [$actualTheoricalDebitPalletsNumber, $actualTheoricalCreditPalletsNumber];
    }

    /**
     * get actual debt pallets number of the credit account and debit account
     * @param $creditAccount
     * @param $debitAccount
     * @return array
     */
    public function actualTheoricalDebtPalletsNumber($creditAccount, $debitAccount)
    {
        if (strpos($debitAccount, '-') == 5 && explode('-', $debitAccount)[0] == 'truck') {
            //truck account
            $actualTheoricalDebtDebitPalletsNumber = Truck::where('id', explode('-', $debitAccount)[1])->value('theoricalPalletsDebt');
        } elseif (strpos($debitAccount, '-') == 7 && explode('-', $debitAccount)[0] == 'account') {
            //others accounts (network, other)
            $actualTheoricalDebtDebitPalletsNumber = Palletsaccount::where('id', explode('-', $debitAccount)[1])->value('theoricalPalletsDebt');
        }

        if (strpos($creditAccount, '-') == 5 && explode('-', $creditAccount)[0] == 'truck') {
            //truck account
            $actualTheoricalDebtCreditPalletsNumber = Truck::where('id', explode('-', $creditAccount)[1])->value('theoricalPalletsDebt');
        } elseif (strpos($creditAccount, '-') == 7 && explode('-', $creditAccount)[0] == 'account') {
            //others accounts (network, other)
            $actualTheoricalDebtCreditPalletsNumber = Palletsaccount::where('id', explode('-', $creditAccount)[1])->value('theoricalPalletsDebt');
        }

        return [$actualTheoricalDebtDebitPalletsNumber, $actualTheoricalDebtCreditPalletsNumber];
    }

    /**
     * write properly credit and debit account for the modals to confirm transfers
     * @param $creditAccount
     * @param $debitAccount
     * @param $index
     */
    public function displayAccounts($creditAccount, $debitAccount, $index)
    {
        if (strpos($creditAccount, '-') == 5 && explode('-', $creditAccount)[0] == 'truck') {
            //truck account
            $nameTruckAccount = Truck::where('id', explode('-', $creditAccount)[1])->value('name');
            $licensePlate = Truck::where('id', explode('-', $creditAccount)[1])->value('licensePlate');
            session()->flash('creditAccount' . $index, $nameTruckAccount . ' - ' . $licensePlate);
        } elseif (strpos($creditAccount, '-') == 7 && explode('-', $creditAccount)[0] == 'account') {
            //others accounts (network, other)
            $namePalletsAccount = Palletsaccount::where('id', explode('-', $creditAccount)[1])->value('nickname');
            session()->flash('creditAccount' . $index, $namePalletsAccount);
        }
        if (strpos($debitAccount, '-') == 5 && explode('-', $debitAccount)[0] == 'truck') {
            //truck account
            $nameTruckAccount = Truck::where('id', explode('-', $debitAccount)[1])->value('name');
            $licensePlate = Truck::where('id', explode('-', $debitAccount)[1])->value('licensePlate');
            session()->flash('debitAccount' . $index, $nameTruckAccount . ' - ' . $licensePlate);
        } elseif (strpos($debitAccount, '-') == 7 && explode('-', $debitAccount)[0] == 'account') {
            //others accounts (network, other)
            $namePalletsAccount = Palletsaccount::where('id', explode('-', $debitAccount)[1])->value('nickname');
            session()->flash('debitAccount' . $index, $namePalletsAccount);
        }
    }

    /**
     * write properly credit and debit account once they have been get from the form, to save them in the database
     * @param $creditAccount
     * @param $debitAccount
     * @param $index
     * @return array
     */
    public function namesAccounts($creditAccount, $debitAccount, $index)
    {
        if (strpos($creditAccount, '-') == 5 && explode('-', $creditAccount)[0] == 'truck') {
            //truck account
            $nameTruckAccount = Truck::where('id', explode('-', $creditAccount)[1])->value('name');
            $licensePlate = Truck::where('id', explode('-', $creditAccount)[1])->value('licensePlate');
            $creditAccountTransfer = $nameTruckAccount . '-' . $licensePlate . '-' . $creditAccount;
            if ($index <> null) {
                session()->flash('creditAccount', $nameTruckAccount . ' - ' . $licensePlate);
//                session()->flash('thPalletsNumberCreditAccount', Truck::where('id', explode('-', $creditAccount)[1])->first()->theoricalNumberPallets);
            }
        } elseif (strpos($creditAccount, '-') == 7 && explode('-', $creditAccount)[0] == 'account') {
            //others accounts (network, other)
            $namePalletsAccount = Palletsaccount::where('id', explode('-', $creditAccount)[1])->value('nickname');
            $creditAccountTransfer = $namePalletsAccount . '-' . $creditAccount;
            if ($index <> null) {
                session()->flash('creditAccount', $namePalletsAccount);
//                session()->flash('thPalletsNumberCreditAccount', Palletsaccount::where('id', explode('-', $creditAccount)[1])->first()->theoricalNumberPallets);
            }
        }
        if (strpos($debitAccount, '-') == 5 && explode('-', $debitAccount)[0] == 'truck') {
            //truck account
            $nameTruckAccount = Truck::where('id', explode('-', $debitAccount)[1])->value('name');
            $licensePlate = Truck::where('id', explode('-', $debitAccount)[1])->value('licensePlate');
            $debitAccountTransfer = $nameTruckAccount . '-' . $licensePlate . '-' . $debitAccount;
            if ($index <> null) {
                session()->flash('debitAccount', $nameTruckAccount . ' - ' . $licensePlate);
//                session()->flash('thPalletsNumberDebitAccount', Truck::where('id', explode('-', $debitAccount)[1])->first()->theoricalNumberPallets);
            }
        } elseif (strpos($debitAccount, '-') == 7 && explode('-', $debitAccount)[0] == 'account') {
            //others accounts (network, other)
            $namePalletsAccount = Palletsaccount::where('id', explode('-', $debitAccount)[1])->value('nickname');
            $debitAccountTransfer = $namePalletsAccount . '-' . $debitAccount;
            if ($index <> null) {
                session()->flash('debitAccount', $namePalletsAccount);
//                session()->flash('thPalletsNumberDebitAccount', Palletsaccount::where('id', explode('-', $debitAccount)[1])->first()->theoricalNumberPallets);
            }
        }
        return [$debitAccountTransfer, $creditAccountTransfer];
    }

    /**
     * create ne transfer according to the type
     * @param $loading
     * @param $type
     * @param $date
     * @param $details
     * @param $creditAccountTransfer
     * @param $debitAccountTransfer
     * @param $palletsNumber
     * @param $creditAccountTransfer2
     * @param $debitAccountTransfer2
     * @param $palletsNumber2
     * @param $transferToCorrect
     */
    public function createTransfer($loading, $type, $date, $details, $creditAccountTransfer, $debitAccountTransfer, $palletsNumber, $creditAccountTransfer2, $debitAccountTransfer2, $palletsNumber2, $creditAccountTransfer3, $debitAccountTransfer3, $palletsNumber3, $transferToCorrect, $notExchanging)
    {
        if ($type == 'Deposit-Withdrawal') {
            Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading->atrnr, 'notExchange' => $notExchanging]);
            Palletstransfer::create(['date' => $date, 'type' => 'Withdrawal-Deposit', 'details' => $details, 'creditAccount' => $creditAccountTransfer2, 'debitAccount' => $debitAccountTransfer2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading->atrnr, 'notExchange' => $notExchanging]);
            if (isset($palletsNumber3) && isset($creditAccountTransfer3) && isset($debitAccountTransfer3)) {
//                if ($palletsNumber < $loading->anz && $palletsNumber2 == $loading->anz) {
//                    $associatedId = Palletstransfer::where('date', $date)->where('type', $type)->where('details', $details)->where('creditAccount', $creditAccountTransfer)->where('debitAccount', $debitAccountTransfer)->where('palletsNumber', $palletsNumber)->where('loading_atrnr', $loading->atrnr)->first()->id;
//                    Palletstransfer::create(['date' => $date, 'type' => 'Debt', 'details' => $details, 'creditAccount' => $creditAccountTransfer3, 'debitAccount' => $debitAccountTransfer3, 'palletsNumber' => $palletsNumber3, 'loading_atrnr' => $loading->atrnr, 'transferToCorrect' => $associatedId, 'notExchange' => $notExchanging]);
//                } elseif ($palletsNumber == $loading->anz && $palletsNumber2 < $loading->anz) {
//                    $associatedId = Palletstransfer::where('date', $date)->where('type', 'Withdrawal-Deposit')->where('details', $details)->where('creditAccount', $creditAccountTransfer2)->where('debitAccount', $debitAccountTransfer2)->where('palletsNumber', $palletsNumber2)->where('loading_atrnr', $loading->atrnr)->first()->id;
//                    Palletstransfer::create(['date' => $date, 'type' => 'Debt', 'details' => $details, 'creditAccount' => $creditAccountTransfer3, 'debitAccount' => $debitAccountTransfer3, 'palletsNumber' => $palletsNumber3, 'loading_atrnr' => $loading->atrnr, 'transferToCorrect' => $associatedId, 'notExchange' => $notExchanging]);
//                } elseif ($palletsNumber == $loading->anz && $palletsNumber2 > $loading->anz) {
//                    $associatedId = Palletstransfer::where('date', $date)->where('type', 'Withdrawal-Deposit')->where('details', $details)->where('creditAccount', $creditAccountTransfer2)->where('debitAccount', $debitAccountTransfer2)->where('palletsNumber', $palletsNumber2)->where('loading_atrnr', $loading->atrnr)->first()->id;
//                    Palletstransfer::create(['date' => $date, 'type' => 'Debt', 'details' => $details, 'creditAccount' => $creditAccountTransfer3, 'debitAccount' => $debitAccountTransfer3, 'palletsNumber' => $palletsNumber3, 'loading_atrnr' => $loading->atrnr, 'transferToCorrect' => $associatedId, 'notExchange' => $notExchanging]);
//                } elseif ($palletsNumber > $loading->anz && $palletsNumber2 == $loading->anz) {
//                    $associatedId = Palletstransfer::where('date', $date)->where('type', $type)->where('details', $details)->where('creditAccount', $creditAccountTransfer)->where('debitAccount', $debitAccountTransfer)->where('palletsNumber', $palletsNumber)->where('loading_atrnr', $loading->atrnr)->first()->id;
//                    Palletstransfer::create(['date' => $date, 'type' => 'Debt', 'details' => $details, 'creditAccount' => $creditAccountTransfer3, 'debitAccount' => $debitAccountTransfer3, 'palletsNumber' => $palletsNumber3, 'loading_atrnr' => $loading->atrnr, 'transferToCorrect' => $associatedId, 'notExchange' => $notExchanging]);
//                } elseif (($palletsNumber < $loading->anz && $palletsNumber2 > $loading->anz) || ($palletsNumber > $loading->anz && $palletsNumber2 < $loading->anz) || ($palletsNumber > $loading->anz && $palletsNumber2 > $loading->anz) || ($palletsNumber < $loading->anz && $palletsNumber2 < $loading->anz)) {
                $associatedId1 = Palletstransfer::where('date', $date)->where('type', $type)->where('details', $details)->where('creditAccount', $creditAccountTransfer)->where('debitAccount', $debitAccountTransfer)->where('palletsNumber', $palletsNumber)->where('loading_atrnr', $loading->atrnr)->first()->id;
                $associatedId2 = Palletstransfer::where('date', $date)->where('type', 'Withdrawal-Deposit')->where('details', $details)->where('creditAccount', $creditAccountTransfer2)->where('debitAccount', $debitAccountTransfer2)->where('palletsNumber', $palletsNumber2)->where('loading_atrnr', $loading->atrnr)->first()->id;
                Palletstransfer::create(['date' => $date, 'type' => 'Debt', 'details' => $details, 'creditAccount' => $creditAccountTransfer3, 'debitAccount' => $debitAccountTransfer3, 'palletsNumber' => $palletsNumber3, 'loading_atrnr' => $loading->atrnr, 'transferToCorrect' => $associatedId1 . '-' . $associatedId2, 'notExchange' => $notExchanging]);
//                }
            }
        } elseif ($type == 'Deposit_Only') {
            Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading->atrnr, 'notExchange' => $notExchanging]);
            if (isset($palletsNumber3) && isset($creditAccountTransfer3) && isset($debitAccountTransfer3)) {
                $associatedId = Palletstransfer::where('date', $date)->where('type', $type)->where('details', $details)->where('creditAccount', $creditAccountTransfer)->where('debitAccount', $debitAccountTransfer)->where('palletsNumber', $palletsNumber)->where('loading_atrnr', $loading->atrnr)->first()->id;
                Palletstransfer::create(['date' => $date, 'type' => 'Debt', 'details' => $details, 'creditAccount' => $creditAccountTransfer3, 'debitAccount' => $debitAccountTransfer3, 'palletsNumber' => $palletsNumber3, 'loading_atrnr' => $loading->atrnr, 'transferToCorrect' => $associatedId, 'notExchange' => $notExchanging]);
            }
        } elseif ($type == 'Withdrawal_Only') {
            Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading->atrnr, 'notExchange' => $notExchanging]);
            if (isset($palletsNumber3) && isset($creditAccountTransfer3) && isset($debitAccountTransfer3)) {
                $associatedId = Palletstransfer::where('date', $date)->where('type', $type)->where('details', $details)->where('creditAccount', $creditAccountTransfer)->where('debitAccount', $debitAccountTransfer)->where('palletsNumber', $palletsNumber)->where('loading_atrnr', $loading->atrnr)->first()->id;
                Palletstransfer::create(['date' => $date, 'type' => 'Debt', 'details' => $details, 'creditAccount' => $creditAccountTransfer3, 'debitAccount' => $debitAccountTransfer3, 'palletsNumber' => $palletsNumber3, 'loading_atrnr' => $loading->atrnr, 'transferToCorrect' => $associatedId, 'notExchange' => $notExchanging]);
            }
        } elseif ($type == 'Purchase') {
            Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading->atrnr, 'transferToCorrect' => $transferToCorrect, 'notExchange' => $notExchanging]);
            Palletstransfer::create(['date' => $date, 'type' => 'Sale', 'details' => $details, 'creditAccount' => $creditAccountTransfer2, 'debitAccount' => $debitAccountTransfer2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading->atrnr, 'transferToCorrect' => $transferToCorrect, 'notExchange' => $notExchanging]);
        } else {
            Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading->atrnr, 'transferToCorrect' => $transferToCorrect, 'notExchange' => $notExchanging]);
        }
    }

    /**
     * update pallets numbers on pallets account according to the type of account
     * @param $creditAccount
     * @param $debitAccount
     * @param $actualTheoricalCreditPalletsNumber
     * @param $actualTheoricalDebitPalletsNumber
     * @param $palletsNumber
     */
    public function updatePalletsAccount($type, $creditAccount, $debitAccount, $actualCreditPalletsNumber, $actualDebitPalletsNumber, $palletsNumber)
    {
        if ($type == 'Debt') {
            if (strpos($creditAccount, '-') == 5 && explode('-', $creditAccount)[0] == 'truck') {
                //truck account
                Truck::where('id', explode('-', $creditAccount)[1])->update(['theoricalPalletsDebt' => $actualCreditPalletsNumber + $palletsNumber]);
                $palletsaccount_name = Truck::where('id', explode('-', $creditAccount)[1])->value('palletsaccount_name');
                Palletsaccount::where('nickname', $palletsaccount_name)->update(['theoricalPalletsDebt' => Truck::where('name', $palletsaccount_name)->sum('theoricalPalletsDebt')]);
            } elseif (strpos($creditAccount, '-') == 7 && explode('-', $creditAccount)[0] == 'account') {
                //others accounts (network, other)
                Palletsaccount::where('id', explode('-', $creditAccount)[1])->update(['theoricalPalletsDebt' => $actualCreditPalletsNumber + $palletsNumber]);
            }

            if (strpos($debitAccount, '-') == 5 && explode('-', $debitAccount)[0] == 'truck') {
                //truck account
                Truck::where('id', explode('-', $debitAccount)[1])->update(['theoricalPalletsDebt' => $actualDebitPalletsNumber - $palletsNumber]);
                $palletsaccount_name = Truck::where('id', explode('-', $debitAccount)[1])->value('palletsaccount_name');
                Palletsaccount::where('nickname', $palletsaccount_name)->update(['theoricalPalletsDebt' => Truck::where('name', $palletsaccount_name)->sum('theoricalPalletsDebt')]);
            } elseif (strpos($debitAccount, '-') == 7 && explode('-', $debitAccount)[0] == 'account') {
                //others accounts (network, other)
                Palletsaccount::where('id', explode('-', $debitAccount)[1])->update(['theoricalPalletsDebt' => $actualDebitPalletsNumber - $palletsNumber]);
            }
        } else {
            if (strpos($creditAccount, '-') == 5 && explode('-', $creditAccount)[0] == 'truck') {
                //truck account
                Truck::where('id', explode('-', $creditAccount)[1])->update(['theoricalNumberPallets' => $actualCreditPalletsNumber + $palletsNumber]);
                $palletsaccount_name = Truck::where('id', explode('-', $creditAccount)[1])->value('palletsaccount_name');
                Palletsaccount::where('nickname', $palletsaccount_name)->update(['theoricalNumberPallets' => Truck::where('name', $palletsaccount_name)->sum('theoricalNumberPallets')]);
            } elseif (strpos($creditAccount, '-') == 7 && explode('-', $creditAccount)[0] == 'account') {
                //others accounts (network, other)
                Palletsaccount::where('id', explode('-', $creditAccount)[1])->update(['theoricalNumberPallets' => $actualCreditPalletsNumber + $palletsNumber]);
            }

            if (strpos($debitAccount, '-') == 5 && explode('-', $debitAccount)[0] == 'truck') {
                //truck account
                Truck::where('id', explode('-', $debitAccount)[1])->update(['theoricalNumberPallets' => $actualDebitPalletsNumber - $palletsNumber]);
                $palletsaccount_name = Truck::where('id', explode('-', $debitAccount)[1])->value('palletsaccount_name');
                Palletsaccount::where('nickname', $palletsaccount_name)->update(['theoricalNumberPallets' => Truck::where('name', $palletsaccount_name)->sum('theoricalNumberPallets')]);
            } elseif (strpos($debitAccount, '-') == 7 && explode('-', $debitAccount)[0] == 'account') {
                //others accounts (network, other)
                Palletsaccount::where('id', explode('-', $debitAccount)[1])->update(['theoricalNumberPallets' => $actualDebitPalletsNumber - $palletsNumber]);
            }
        }

    }

    /**
     * validate add a new pallets transfer
     * @param $loading
     * @param $type
     * @param $date
     * @param $details
     * @param $creditAccount
     * @param $debitAccount
     * @param $creditAccount2
     * @param $debitAccount2
     * @param $palletsNumber
     * @param $palletsNumber2
     * @param $transferToCorrect
     */
    public function validateAddPalletsTransfer($loading, $type, $date, $details, $creditAccount, $debitAccount, $creditAccount2, $debitAccount2, $palletsNumber, $palletsNumber2, $creditAccount3, $debitAccount3, $palletsNumber3, $transferToCorrect, $notExchanging)
    {
        $actualTheoricalCreditPalletsNumber = session('palletsNumberCreditAccount');
        $actualTheoricalDebitPalletsNumber = session('palletsNumberDebitAccount');

        $debitAccountTransfer = $this->namesAccounts($creditAccount, $debitAccount, null)[0];
        $creditAccountTransfer = $this->namesAccounts($creditAccount, $debitAccount, null)[1];
        if (isset($creditAccount2) && isset($debitAccount2)) {
            $debitAccountTransfer2 = $this->namesAccounts($creditAccount2, $debitAccount2, null)[0];
            $creditAccountTransfer2 = $this->namesAccounts($creditAccount2, $debitAccount2, null)[1];
        } else {
            $debitAccountTransfer2 = null;
            $creditAccountTransfer2 = null;
        }
        if (isset($creditAccount3) && isset($debitAccount3)) {
            $debitAccountTransfer3 = $this->namesAccounts($creditAccount3, $debitAccount3, null)[0];
            $creditAccountTransfer3 = $this->namesAccounts($creditAccount3, $debitAccount3, null)[1];
        } else {
            $debitAccountTransfer3 = null;
            $creditAccountTransfer3 = null;
        }
        if (isset($notExchanging)) {
            $notExchanging = true;
        } else {
            $notExchanging = false;
        }

        $this->createTransfer($loading, $type, $date, $details, $creditAccountTransfer, $debitAccountTransfer, $palletsNumber, $creditAccountTransfer2, $debitAccountTransfer2, $palletsNumber2, $creditAccountTransfer3, $debitAccountTransfer3, $palletsNumber3, $transferToCorrect, $notExchanging);
        $this->updatePalletsAccount($type, $creditAccount, $debitAccount, $actualTheoricalCreditPalletsNumber, $actualTheoricalDebitPalletsNumber, $palletsNumber);
        if (isset($creditAccount2) && isset($debitAccount2)) {
            $actualTheoricalCreditPalletsNumber2 = session('palletsNumberCreditAccount2');
            $actualTheoricalDebitPalletsNumber2 = session('palletsNumberDebitAccount2');
            $this->updatePalletsAccount($type, $creditAccount2, $debitAccount2, $actualTheoricalCreditPalletsNumber2, $actualTheoricalDebitPalletsNumber2, $palletsNumber2);
        }
        if (isset($creditAccount3) && isset($debitAccount3)) {
            $actualTheoricalDebtCreditPalletsNumber3 = session('palletsNumberDebtCreditAccount3');
            $actualTheoricalDebtDebitPalletsNumber3 = session('palletsNumberDebtDebitAccount3');
            $this->updatePalletsAccount('Debt', $creditAccount3, $debitAccount3, $actualTheoricalDebtCreditPalletsNumber3, $actualTheoricalDebtDebitPalletsNumber3, $palletsNumber3);
        }

        $this->state($loading, Palletstransfer::where('loading_atrnr', $loading->atrnr)->get());
        session()->flash('messageAddPalletstransfer', 'Successfully added new pallets transfer(s)');
        session()->pull('palletsNumberCreditAccount');
        session()->pull('palletsNumberDebitAccount');
        session()->pull('palletsNumberCreditAccount2');
        session()->pull('palletsNumberDebitAccount2');
        session()->pull('palletsNumberDebtCreditAccount3');
        session()->pull('palletsNumberDebtDebitAccount3');
    }

    /**
     * define if there is a normal or correcting transfer and the id of the transfer
     * @param $listPalletstransfersNormal
     * @param $listPalletstransfersCorrecting
     * @param $submitPallets
     * @return array
     */
    public function defineSubmitPalletsValue($listPalletstransfersNormal, $listPalletstransfersCorrecting, $submitPallets)
    {
        $listIDTransfersNormal = [];
        foreach ($listPalletstransfersNormal as $transferNormal) {
            $listIDTransfersNormal[] = $transferNormal->id;
        }
        $listIDTransfersCorrecting = [];
        foreach ($listPalletstransfersCorrecting as $transferCorrecting) {
            $listIDTransfersCorrecting[] = $transferCorrecting->id;
        }
        if (in_array($submitPallets, $listIDTransfersNormal)) {
            $submitPalletsNormal = $submitPallets;
            $submitPalletsCorrecting = null;
        } elseif (in_array($submitPallets, $listIDTransfersCorrecting)) {
            $submitPalletsNormal = null;
            $submitPalletsCorrecting = $submitPallets;
        }
        return [$submitPalletsNormal, $submitPalletsCorrecting];
    }

    /**
     * prepare the data to update a transfer and then redirect to a view to confirm the update
     * @param $filesNames
     * @param $transfer
     * @param $loading
     * @param $type
     * @param $date
     * @param $details
     * @param $validate
     * @param $creditAccount
     * @param $debitAccount
     * @param $palletsNumber
     * @param $submitPallets
     * @param $transferToCorrect
     * @return string
     */
    public function updateTransfer($transfer, $validate, $details, $loading, $actualDocuments)
    {
        Palletstransfer::where('id', $transfer->id)->update(['details' => $details]);
        $partsCreditAccount = explode('-', $transfer->creditAccount);
        $typeCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 2];
        $idCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 1];
        $partsDebitAccount = explode('-', $transfer->debitAccount);
        $typeDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 2];
        $idDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 1];

        if ($transfer->validate == 0 && $validate == 'true' && !empty($actualDocuments)) {
            $creditAccount = array_diff($partsCreditAccount, [$typeCreditAccount, $idCreditAccount])[0];
            session()->flash('creditAccount', $creditAccount);
            $debitAccount = array_diff($partsDebitAccount, [$typeDebitAccount, $idDebitAccount])[0];
            session()->flash('debitAccount', $debitAccount);
//            session()->put('actualValidate', $transfer->validate);
            $view = 'ok';
        } elseif ($transfer->validate == 1 && $validate == 'false' && !empty($actualDocuments)) {
            if($transfer->type == 'Debt'){
                //inverse operation
                if ($typeCreditAccount == 'truck') {
                    $realDebtPalletsNumberCreditAccount = Truck::where('id', $idCreditAccount)->first()->realPalletsDebt;
                    Truck::where('id', $idCreditAccount)->update(['realPalletsDebt' => $realDebtPalletsNumberCreditAccount - $transfer->palletsNumber]);
                    $palletsaccount_name = Truck::where('id', $idCreditAccount)->value('palletsaccount_name');
                    Palletsaccount::where('nickname', $palletsaccount_name)->update(['realPalletsDebt' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('realPalletsDebt')]);
                } elseif ($typeCreditAccount == 'account') {
                    $realDebtPalletsNumberCreditAccount = Palletsaccount::where('id', $idCreditAccount)->first()->realPalletsDebt;
                    Palletsaccount::where('id', $idCreditAccount)->update(['realPalletsDebt' => $realDebtPalletsNumberCreditAccount - $transfer->palletsNumber]);
                }
                if ($typeDebitAccount == 'truck') {
                    $realDebtPalletsNumberDebitAccount = Truck::where('id', $idDebitAccount)->first()->realPalletsDebt;
                    Truck::where('id', $idDebitAccount)->update(['realPalletsDebt' => $realDebtPalletsNumberDebitAccount + $transfer->palletsNumber]);
                    $palletsaccount_name = Truck::where('id', $idDebitAccount)->value('palletsaccount_name');
                    Palletsaccount::where('nickname', $palletsaccount_name)->update(['realPalletsDebt' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('realPalletsDebt')]);
                } elseif ($typeDebitAccount == 'account') {
                    $realDebtPalletsNumberDebitAccount = Palletsaccount::where('id', $idDebitAccount)->first()->realPalletsDebt;
                    Palletsaccount::where('id', $idDebitAccount)->update(['realPalletsDebt' => $realDebtPalletsNumberDebitAccount + $transfer->palletsNumber]);
                }
            }else{
                //inverse operation
                if ($typeCreditAccount == 'truck') {
                    $realPalletsNumberCreditAccount = Truck::where('id', $idCreditAccount)->first()->realNumberPallets;
                    Truck::where('id', $idCreditAccount)->update(['realNumberPallets' => $realPalletsNumberCreditAccount - $transfer->palletsNumber]);
                    $palletsaccount_name = Truck::where('id', $idCreditAccount)->value('palletsaccount_name');
                    Palletsaccount::where('nickname', $palletsaccount_name)->update(['realNumberPallets' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('realNumberPallets')]);
                } elseif ($typeCreditAccount == 'account') {
                    $realPalletsNumberCreditAccount = Palletsaccount::where('id', $idCreditAccount)->first()->realNumberPallets;
                    Palletsaccount::where('id', $idCreditAccount)->update(['realNumberPallets' => $realPalletsNumberCreditAccount - $transfer->palletsNumber]);
                }
                if ($typeDebitAccount == 'truck') {
                    $realPalletsNumberDebitAccount = Truck::where('id', $idDebitAccount)->first()->realNumberPallets;
                    Truck::where('id', $idDebitAccount)->update(['realNumberPallets' => $realPalletsNumberDebitAccount + $transfer->palletsNumber]);
                    $palletsaccount_name = Truck::where('id', $idDebitAccount)->value('palletsaccount_name');
                    Palletsaccount::where('nickname', $palletsaccount_name)->update(['realNumberPallets' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('realNumberPallets')]);
                } elseif ($typeDebitAccount == 'account') {
                    $realPalletsNumberDebitAccount = Palletsaccount::where('id', $idDebitAccount)->first()->realNumberPallets;
                    Palletsaccount::where('id', $idDebitAccount)->update(['realNumberPallets' => $realPalletsNumberDebitAccount + $transfer->palletsNumber]);
                }
            }

            Palletstransfer::where('id', $transfer->id)->update(['validate' => false, 'state' => 'Complete']);
            $this->state($loading, Palletstransfer::where('loading_atrnr', $loading->atrnr)->get());
            $view = 'error';
        } else {
            if ($validate == 'true') {
                Palletstransfer::where('id', $transfer->id)->update(['validate' => true]);
            } elseif ($validate == 'false') {
                Palletstransfer::where('id', $transfer->id)->update(['validate' => false]);
            }
            $this->state($loading, Palletstransfer::where('loading_atrnr', $loading->atrnr)->get());
            $view = 'error';
        }
        return $view;
    }

    /**
     * update only the information related to the transfer
     * @param $transfer
     * @param $filesNames
     * @param $okSubmitPalletsModal
     * @param $loading
     * @return $view
     */
    public function validateUpdateTransfer($transfer, $loading)
    {
        if($transfer->type == 'Debt'){
            //confirmed transfer -> update real debt pallets number
            $partsCreditAccount = explode('-', $transfer->creditAccount);
            $typeCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 2];
            $idCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 1];
            if ($typeCreditAccount == 'truck') {
                $realDebtPalletsNumberCreditAccount = Truck::where('id', $idCreditAccount)->first()->realPalletsDebt;
                Truck::where('id', $idCreditAccount)->update(['realPalletsDebt' => $realDebtPalletsNumberCreditAccount + $transfer->palletsNumber]);
                $palletsaccount_name = Truck::where('id', $idCreditAccount)->value('palletsaccount_name');
                Palletsaccount::where('nickname', $palletsaccount_name)->update(['realPalletsDebt' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('realPalletsDebt')]);
            } elseif ($typeCreditAccount == 'account') {
                $realDebtPalletsNumberCreditAccount = Palletsaccount::where('id', $idCreditAccount)->first()->realPalletsDebt;
                Palletsaccount::where('id', $idCreditAccount)->update(['realPalletsDebt' => $realDebtPalletsNumberCreditAccount + $transfer->palletsNumber]);
            }

            $partsDebitAccount = explode('-', $transfer->debitAccount);
            $typeDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 2];
            $idDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 1];
            if ($typeDebitAccount == 'truck') {
                $realDebtPalletsNumberDebitAccount = Truck::where('id', $idDebitAccount)->first()->realPalletsDebt;
                Truck::where('id', $idDebitAccount)->update(['realPalletsDebt' => $realDebtPalletsNumberDebitAccount - $transfer->palletsNumber]);
                $palletsaccount_name = Truck::where('id', $idDebitAccount)->value('palletsaccount_name');
                Palletsaccount::where('nickname', $palletsaccount_name)->update(['realPalletsDebt' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('realPalletsDebt')]);
            } elseif ($typeDebitAccount == 'account') {
                $realDebtPalletsNumberDebitAccount = Palletsaccount::where('id', $idDebitAccount)->first()->realPalletsDebt;
                Palletsaccount::where('id', $idDebitAccount)->update(['realPalletsDebt' => $realDebtPalletsNumberDebitAccount - $transfer->palletsNumber]);
            }
        }else{
            //confirmed transfer -> update real pallets number
            $partsCreditAccount = explode('-', $transfer->creditAccount);
            $typeCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 2];
            $idCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 1];
            if ($typeCreditAccount == 'truck') {
                $realPalletsNumberCreditAccount = Truck::where('id', $idCreditAccount)->first()->realNumberPallets;
                Truck::where('id', $idCreditAccount)->update(['realNumberPallets' => $realPalletsNumberCreditAccount + $transfer->palletsNumber]);
                $palletsaccount_name = Truck::where('id', $idCreditAccount)->value('palletsaccount_name');
                Palletsaccount::where('nickname', $palletsaccount_name)->update(['realNumberPallets' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('realNumberPallets')]);
            } elseif ($typeCreditAccount == 'account') {
                $realPalletsNumberCreditAccount = Palletsaccount::where('id', $idCreditAccount)->first()->realNumberPallets;
                Palletsaccount::where('id', $idCreditAccount)->update(['realNumberPallets' => $realPalletsNumberCreditAccount + $transfer->palletsNumber]);
            }

            $partsDebitAccount = explode('-', $transfer->debitAccount);
            $typeDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 2];
            $idDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 1];
            if ($typeDebitAccount == 'truck') {
                $realPalletsNumberDebitAccount = Truck::where('id', $idDebitAccount)->first()->realNumberPallets;
                Truck::where('id', $idDebitAccount)->update(['realNumberPallets' => $realPalletsNumberDebitAccount - $transfer->palletsNumber]);
                $palletsaccount_name = Truck::where('id', $idDebitAccount)->value('palletsaccount_name');
                Palletsaccount::where('nickname', $palletsaccount_name)->update(['realNumberPallets' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('realNumberPallets')]);
            } elseif ($typeDebitAccount == 'account') {
                $realPalletsNumberDebitAccount = Palletsaccount::where('id', $idDebitAccount)->first()->realNumberPallets;
                Palletsaccount::where('id', $idDebitAccount)->update(['realNumberPallets' => $realPalletsNumberDebitAccount - $transfer->palletsNumber]);
            }
        }


        Palletstransfer::where('id', $transfer->id)->update(['validate' => true]);
        Palletstransfer::where('id', $transfer->id)->update(['state' => 'Complete Validated']);
        $this->state($loading, Palletstransfer::where('loading_atrnr', $loading->atrnr)->get());

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
                $filesNames[] =Document::where('id', $actualDoc->document_id)->first()->name;
            }
        }elseif(Palletstransfer::where('id', $id)->first()->proof <> null){
            $filesNames[]= 'proof-'.Palletstransfer::where('id', $id)->first()->proof;
        }
        return $filesNames;
    }

    /**
     * upload a document on the website
     * @param $documents
     * @param $transfer
     * @param $loading
     *
     */
    public function upload($documents, $transfer, $loading)
    {
        if (isset($documents)) {
            foreach ($documents as $doc) {
                $filename = $doc->getClientOriginalName();
                $extension = $doc->getClientOriginalExtension();
                $size = $doc->getSize();
                //if file is an image, a pdf or an email
                if (($extension == 'png' || $extension == 'jpg' || $extension == 'JPG' || $extension == 'msg' || $extension == 'htm' || $extension == 'txt' || $extension == 'rtf' || $extension == 'pdf') && $size < 2000000) {
                    Storage::putFileAs('/proofsPallets/documentsTransfer/' . $transfer->id . '/' . $transfer->type, $doc, $filename);
                    Document::firstOrCreate([
                        'name' => $filename,
                    ])->palletstransfers()->attach($transfer->id);
                } else {
                    session()->flash('messageErrorUpload', 'Error ! The file type is not supported (png, jgp, pdf, msg, htm, rtf, txt only)');
                }
            }
        }
        $filesNames = $this->actualDocuments($transfer->id);
        if (!empty($filesNames) && $transfer->validate == 1) {
            Palletstransfer::where('id', $transfer->id)->update(['state' => 'Complete Validated']);
        } elseif (!empty($filesNames) && $transfer->validate == 0) {
            Palletstransfer::where('id', $transfer->id)->update(['state' => 'Complete']);
        } elseif (empty($filesNames)) {
            Palletstransfer::where('id', $transfer->id)->update(['state' => 'Waiting documents']);
        }

        $this->state($loading, Palletstransfer::where('loading_atrnr', $loading->atrnr)->get());
    }

    public function uploadProof($proof, $transfer, $actionForm, $loading){
        Palletstransfer::where('id', explode('-', $actionForm)[1])->update(['proof' => $proof]);
        $filesNames = $this->actualDocuments($transfer->id);
        if (!empty($filesNames) && $transfer->validate == 1) {
            Palletstransfer::where('id', $transfer->id)->update(['state' => 'Complete Validated']);
        } elseif (!empty($filesNames) && $transfer->validate == 0) {
            Palletstransfer::where('id', $transfer->id)->update(['state' => 'Complete']);
        } elseif (empty($filesNames)) {
            Palletstransfer::where('id', $transfer->id)->update(['state' => 'Waiting documents']);
        }
        $this->state($loading, Palletstransfer::where('loading_atrnr', $loading->atrnr)->get());
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
        $path = '/proofsPallets/documentsTransfer/' . $transfer->id . '/' . $transfer->type . '/';
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
            $partsCreditAccount = explode('-', $transfer->creditAccount);
            $typeCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 2];
            $idCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 1];

            if ($typeCreditAccount == 'truck') {
                $actualRealPalletsNumberCreditAccount = Truck::where('id', $idCreditAccount)->first()->realNumberPallets;
                Truck::where('id', $idCreditAccount)->update(['realNumberPallets' => $actualRealPalletsNumberCreditAccount - $transfer->palletsNumber]);
                $palletsaccount_name = Truck::where('id', $idCreditAccount)->value('palletsaccount_name');
                Palletsaccount::where('nickname', $palletsaccount_name)->update(['realNumberPallets' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('realNumberPallets')]);
            } elseif ($typeCreditAccount == 'account') {
                $actualRealPalletsNumberCreditAccount = Palletsaccount::where('id', $idCreditAccount)->first()->realNumberPallets;
                Palletsaccount::where('id', $idCreditAccount)->update(['realNumberPallets' => $actualRealPalletsNumberCreditAccount - $transfer->palletsNumber]);
            }
        }
        if (isset($transfer->debitAccount)) {
            $partsDebitAccount = explode('-', $transfer->debitAccount);
            $typeDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 2];
            $idDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 1];

            if ($typeDebitAccount == 'truck') {
                $actualRealPalletsNumberDebitAccount = Truck::where('id', $idDebitAccount)->first()->realNumberPallets;
                Truck::where('id', $idDebitAccount)->update(['realNumberPallets' => $actualRealPalletsNumberDebitAccount + $transfer->palletsNumber]);
                $palletsaccount_name = Truck::where('id', $idDebitAccount)->value('palletsaccount_name');
                Palletsaccount::where('nickname', $palletsaccount_name)->update(['realNumberPallets' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('realNumberPallets')]);
            } elseif ($typeDebitAccount == 'account') {
                $actualRealPalletsNumberDebitAccount = Palletsaccount::where('id', $idDebitAccount)->first()->realNumberPallets;
                Palletsaccount::where('id', $idDebitAccount)->update(['realNumberPallets' => $actualRealPalletsNumberDebitAccount + $transfer->palletsNumber]);
            }
        }
    }



    /**
     * cancelling the update transfer -> go back to the initial state
     * @param $closeSubmitPalletsModal
     * @param $loading
     */
//    public function refuseValidateUpdateTransfer($closeSubmitPalletsModal, $loading)
//    {
//        $actualCreditAccount = session('actualCreditAccount');
//        $actualDebitAccount = session('actualDebitAccount');
//        $actualPalletsNumber = session('actualPalletsNumber');
//        $actualType = session('actualType');
//        $actualDetails = session('actualDetails');
//        $actualDate = session('actualDate');
//        $actualValidate = session('actualValidate');
//        $actualtransferToCorrect = session('actualtransferToCorrect');
//
//        if (isset($actualDebitAccount)) {
//            Palletstransfer::where('id', $closeSubmitPalletsModal)->update(['debitAccount' => $actualDebitAccount]);
//        }
//        if (isset($actualCreditAccount)) {
//            Palletstransfer::where('id', $closeSubmitPalletsModal)->update(['creditAccount' => $actualCreditAccount]);
//        }
//        Palletstransfer::where('id', $closeSubmitPalletsModal)->update(['validate' => $actualValidate, 'type' => $actualType, 'details' => $actualDetails, 'palletsNumber' => $actualPalletsNumber, 'date' => $actualDate, 'transferToCorrect' => $actualtransferToCorrect]);
//
//        $filesNames = $this->actualDocuments($closeSubmitPalletsModal);
//        if (!empty($filesNames) && $actualValidate == 1) {
//            Palletstransfer::where('id', $closeSubmitPalletsModal)->update(['state' => 'Complete Validated']);
//        } elseif (!empty($filesNames) && $actualValidate == 0) {
//            Palletstransfer::where('id', $closeSubmitPalletsModal)->update(['state' => 'Complete']);
//        } elseif (empty($filesNames)) {
//            Palletstransfer::where('id', $closeSubmitPalletsModal)->update(['state' => 'Waiting documents']);
//        }
//
//        $this->state($loading, Palletstransfer::where('loading_atrnr', $loading->atrnr)->get());
//        session()->pull('actualCreditAccount');
//        session()->pull('actualDebitAccount');
//        session()->pull('actualPalletsNumber');
//        session()->pull('actualType');
//        session()->pull('actualDetails');
//        session()->pull('actualDate');
//        session()->pull('actualValidate');
//        session()->pull('actualtransferToCorrect');
//    }

    /**
     * cancelling the update transfer -> go back to the initial state
     * @param $closeSubmitPalletsModal
     * @param $loading
    //     */
//    public function refuseValidateUpdateTransfer($closeSubmitPalletsModal, $loading)
//    {
//        $actualValidate = session('actualValidate');
//        $actualState = session('actualState');
//        Palletstransfer::where('id', $closeSubmitPalletsModal)->update(['validate' => $actualValidate, 'state' => $actualState]);
//
//        $this->state($loading, Palletstransfer::where('loading_atrnr', $loading->atrnr)->get());
//        session()->pull('actualState');
//        session()->pull('actualValidate');
//    }


    /**
     * delete the transfer from the database
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function deleteAllTransfers($loading, $listPalletstransfers)
    {
        foreach ($listPalletstransfers as $transfer) {
            if ($transfer->type == 'Debt') {
                if (isset($transfer->creditAccount)) {
                    $partsCreditAccount = explode('-', $transfer->creditAccount);
                    $typeCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 2];
                    $idCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 1];
                    if ($typeCreditAccount == 'truck') {
                        $actualDebtCreditAccount = Truck::where('id', $idCreditAccount)->first()->palletsDebt;
                        Truck::where('id', $idCreditAccount)->update(['palletsDebt' => $actualDebtCreditAccount - $transfer->palletsNumber]);
                        $palletsaccount_name = Truck::where('id', $idCreditAccount)->value('palletsaccount_name');
                        Palletsaccount::where('name', $palletsaccount_name)->update(['palletsDebt' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('palletsDebt')]);
                    } elseif ($typeCreditAccount == 'account') {
                        $actualDebtCreditAccount = Palletsaccount::where('id', $idCreditAccount)->first()->palletsDebt;
                        Palletsaccount::where('id', $idCreditAccount)->update(['palletsDebt' => $actualDebtCreditAccount - $transfer->palletsNumber]);
                    }
                }
                if (isset($transfer->debitAccount)) {
                    $partsDebitAccount = explode('-', $transfer->debitAccount);
                    $typeDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 2];
                    $idDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 1];
                    if ($typeDebitAccount == 'truck') {
                        $actualDebtDebitAccount = Truck::where('id', $idDebitAccount)->first()->palletsDebt;
                        Truck::where('id', $idDebitAccount)->update(['palletsDebt' => $actualDebtDebitAccount + $transfer->palletsNumber]);
                        $palletsaccount_name = Truck::where('id', $idDebitAccount)->value('palletsaccount_name');
                        Palletsaccount::where('name', $palletsaccount_name)->update(['palletsDebt' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('palletsDebt')]);
                    } elseif ($typeDebitAccount == 'account') {
                        $actualDebtDebitAccount = Palletsaccount::where('id', $idDebitAccount)->first()->palletsDebt;
                        Palletsaccount::where('id', $idDebitAccount)->update(['palletsDebt' => $actualDebtDebitAccount + $transfer->palletsNumber]);
                    }
                }
            } else {
                //inverse operation
                if (isset($transfer->creditAccount)) {
                    $partsCreditAccount = explode('-', $transfer->creditAccount);
                    $typeCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 2];
                    $idCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 1];
                    if ($typeCreditAccount == 'truck') {
                        $actualPalletsNumberCreditAccount = Truck::where('id', $idCreditAccount)->first()->theoricalNumberPallets;
                        Truck::where('id', $idCreditAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberCreditAccount - $transfer->palletsNumber]);
                        $palletsaccount_name = Truck::where('id', $idCreditAccount)->value('palletsaccount_name');
                        Palletsaccount::where('name', $palletsaccount_name)->update(['theoricalNumberPallets' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('theoricalNumberPallets')]);
                    } elseif ($typeCreditAccount == 'account') {
                        $actualPalletsNumberCreditAccount = Palletsaccount::where('id', $idCreditAccount)->first()->theoricalNumberPallets;
                        Palletsaccount::where('id', $idCreditAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberCreditAccount - $transfer->palletsNumber]);
                    }
                }
                if (isset($transfer->debitAccount)) {
                    $partsDebitAccount = explode('-', $transfer->debitAccount);
                    $typeDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 2];
                    $idDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 1];
                    if ($typeDebitAccount == 'truck') {
                        $actualPalletsNumberDebitAccount = Truck::where('id', $idDebitAccount)->first()->theoricalNumberPallets;
                        Truck::where('id', $idDebitAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberDebitAccount + $transfer->palletsNumber]);
                        $palletsaccount_name = Truck::where('id', $idDebitAccount)->value('palletsaccount_name');
                        Palletsaccount::where('name', $palletsaccount_name)->update(['theoricalNumberPallets' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('theoricalNumberPallets')]);
                    } elseif ($typeDebitAccount == 'account') {
                        $actualPalletsNumberDebitAccount = Palletsaccount::where('id', $idDebitAccount)->first()->theoricalNumberPallets;
                        Palletsaccount::where('id', $idDebitAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberDebitAccount + $transfer->palletsNumber]);
                    }
                }
                if ($transfer->state == 'Complete Validated') {
                    $this->inverseRealPalletsNumber($transfer);
                }
            }

            $actualDocuments_Palletstransfers = DB::table('document_palletstransfer')->where('palletstransfer_id', $transfer->id)->get();
            $actualDocuments = [];
            if (!$actualDocuments_Palletstransfers->isEmpty()) {
                foreach ($actualDocuments_Palletstransfers as $actualDoc) {
                    $actualDocuments[] = Document::where('id', $actualDoc->document_id)->first();
                }
                foreach ($actualDocuments as $actDoc) {
                    $actDoc->palletstransfers()->detach($transfer->id);
                    $path = '/proofsPallets/documentsTransfer/' . $transfer->id . '/' . $transfer->type . '/';
                    Storage::delete($path . $actDoc->name);
                    $actualTransferAssociated = DB::table('document_palletstransfer')->where('document_id', $actDoc->id)->get();
                    if ($actualTransferAssociated->isEmpty()) {
                        $actDoc->delete();
                    }
                }
            }
            $actualErrors_Palletstransfers = DB::table('error_palletstransfer')->where('palletstransfer_id', $transfer->id)->get();
            $actualErrors = [];
            if (!$actualErrors_Palletstransfers->isEmpty()) {
                foreach ($actualErrors_Palletstransfers as $actualError) {
                    $actualErrors[] = Error::where('id', $actualError->error_id)->first();
                }
                foreach ($actualErrors as $actErr) {
                    $actErr->palletstransfers()->detach($transfer->id);
                }
            }
            Palletstransfer::where('id', $transfer->id)->delete();
        }
        $this->state($loading, Palletstransfer::where('loading_atrnr', $loading->atrnr)->get());
        session()->flash('messageDeleteAllPalletstransfer', 'Successfully deleted ALL the pallets transfer!');
    }

    /**
     * define the general state of the loading according to all transfers state and update every error
     * @param $loading
     * @param $listPalletstransfers
     */
    public static function state($loading, $listPalletstransfers)
    {
        $idErrorWDDW_atLeastOne = Error::where('name', 'DW-WD_atLeastOne')->first()->id;
        $idErrorWDDW_NotNumberLoadingOrder = Error::where('name', 'DW-WD_notNumberLoadingOrder')->first()->id;
        $idErrorDWWD_NotSameNumber = Error::where('name', 'DW-WD_notSameNumber')->first()->id;
        $idErrorDW_NotSameNumber = Error::where('name', 'Donly-Wonly_notSameNumber')->first()->id;
        $idErrorCorrecting_NotCompleteNormal = Error::where('name', 'Correcting_notCompleteNormal')->first()->id;
        $idErrorCorrecting_NotEnoughTransfers = Error::where('name', 'Correcting_notEnoughTransfers')->first()->id;
//        $idErrorSPPS_NotEnoughTransfers = Error::where('name', 'SP-PS_notEnoughTransfers')->first()->id;

        //0) remove all errors on transfers
        foreach ($listPalletstransfers as $transfer) {
            $transfer->errors()->detach([$idErrorWDDW_atLeastOne, $idErrorDWWD_NotSameNumber, $idErrorDW_NotSameNumber, $idErrorWDDW_NotNumberLoadingOrder, $idErrorCorrecting_NotCompleteNormal, $idErrorCorrecting_NotEnoughTransfers]);
        }

        //////CORRECTING TRANSFERS//////
        //check if there is enough correcting transfers (even number)
        $listTransfersCorrecting = Palletstransfer::where('loading_atrnr', $loading->atrnr)->where(function($q){
            $q->where('type', 'Debt')->orWhere('type', 'Purchase')->orWhere('type', 'Sale');
        })->get();

//        if (count($listTransfersSP) <> count($listTransfersPS)) {
//            foreach ($listTransfersSP as $transferSP) {
//                $transferSP->errors()->attach($idErrorSPPS_NotEnoughTransfers);
//            }
//            foreach ($listTransfersPS as $transferPS) {
//                $transferPS->errors()->attach($idErrorSPPS_NotEnoughTransfers);
//            }
//        }
        if (count($listTransfersCorrecting) % 2 <> 0) {
            foreach ($listTransfersCorrecting as $transferCorrecting) {
                $transferCorrecting->errors()->attach($idErrorCorrecting_NotEnoughTransfers);
            }
        }

        ////////TRANSFERS D only - W only /////////
        //1) check if Donly has a Wonly equivalent transfer
        //check if sum(Donly) = sum(Wonly)
        $listTransfersD = Palletstransfer::where('type', 'Deposit_Only')->where('loading_atrnr', $loading->atrnr)->get();
        $listTransfersW = Palletstransfer::where('type', 'Withdrawal_Only')->where('loading_atrnr', $loading->atrnr)->get();
        $sumTransfersD = Palletstransfer::where('type', 'Deposit_Only')->where('loading_atrnr', $loading->atrnr)->sum('palletsNumber');
        $sumTransfersW = Palletstransfer::where('type', 'Withdrawal_Only')->where('loading_atrnr', $loading->atrnr)->sum('palletsNumber');

        if ($sumTransfersD <> $sumTransfersW) {
            foreach ($listTransfersD as $transferD) {
                $transferD->errors()->attach($idErrorDW_NotSameNumber);
            }
            foreach ($listTransfersW as $transferW) {
                $transferW->errors()->attach($idErrorDW_NotSameNumber);
            }
        }

        //1) correction : Donly+debt+PS = Wonly+debt+PS OR Donly+debt+SP = Wonly+debt+PS OR Donly+debt+PS = Wonly+debt+SP OR Donly+debt+SP = Wonly+debt+SP
        $sumD = 0;
        $sum1CorrectingTransferD = 0;
        $sum2CorrectingTransferD = 0;
        foreach ($listTransfersD as $transferD) {
            $sum1CorrectingTransferD = $sum1CorrectingTransferD + Palletstransfer::where('transferToCorrect', $transferD->id)->where('loading_atrnr', $loading->atrnr)->where(function ($q) {
                    $q->where('type', 'Debt')->orWhere('type', 'Purchase');
                })->sum('palletsNumber');
            $sum2CorrectingTransferD = $sum2CorrectingTransferD + Palletstransfer::where('transferToCorrect', $transferD->id)->where('loading_atrnr', $loading->atrnr)->where(function ($q) {
                    $q->where('type', 'Debt')->orWhere('type', 'Sale');
                })->sum('palletsNumber');
            $sumD = $sumD + $transferD->palletsNumber;
        }

        $sumW = 0;
        $sum1CorrectingTransferW = 0;
        $sum2CorrectingTransferW = 0;
        foreach ($listTransfersW as $transferW) {
            $sum1CorrectingTransferW = $sum1CorrectingTransferW + Palletstransfer::where('transferToCorrect', $transferW->id)->where('loading_atrnr', $loading->atrnr)->where(function ($q) {
                    $q->where('type', 'Debt')->orWhere('type', 'Purchase');
                })->sum('palletsNumber');
            $sum2CorrectingTransferW = $sum2CorrectingTransferW + Palletstransfer::where('transferToCorrect', $transferW->id)->where('loading_atrnr', $loading->atrnr)->where(function ($q) {
                    $q->where('type', 'Debt')->orWhere('type', 'Sale');
                })->sum('palletsNumber');
            $sumW = $sumW + $transferW->palletsNumber;
        }
        //sum d only + correcting w only
        $sum1D = $sumD + $sum1CorrectingTransferW;
        $sum2D = $sumD + $sum2CorrectingTransferW;
        //sum w only + correcting d only
        $sum1W = $sumW + $sum1CorrectingTransferD;
        $sum2W = $sumW + $sum2CorrectingTransferD;

        //errors
        if ($sum1D <> $sum1W) {
            foreach ($listTransfersD as $transferD) {
                foreach (Palletstransfer::where('transferToCorrect', $transferD->id)->where('loading_atrnr', $loading->atrnr)->where(function ($q) {
                    $q->where('type', 'Debt')->orWhere('type', 'Purchase');
                })->get() as $transferCorrecting1D) {
                    $transferCorrecting1D->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                }
            }
            foreach ($listTransfersW as $transferW) {
                foreach (Palletstransfer::where('transferToCorrect', $transferW->id)->where('loading_atrnr', $loading->atrnr)->where(function ($q) {
                    $q->where('type', 'Debt')->orWhere('type', 'Purchase');
                })->get() as $transferCorrecting1W) {
                    $transferCorrecting1W->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                }
            }
        }
        if ($sum1D <> $sum2W) {
            foreach ($listTransfersD as $transferD) {
                foreach (Palletstransfer::where('transferToCorrect', $transferD->id)->where('loading_atrnr', $loading->atrnr)->where(function ($q) {
                    $q->where('type', 'Debt')->orWhere('type', 'Purchase');
                })->get() as $transferCorrecting1D) {
                    $transferCorrecting1D->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                    $transferCorrecting1D->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                }
            }
            foreach ($listTransfersW as $transferW) {
                foreach (Palletstransfer::where('transferToCorrect', $transferW->id)->where('loading_atrnr', $loading->atrnr)->where(function ($q) {
                    $q->where('type', 'Debt')->orWhere('type', 'Sale');
                })->get() as $transferCorrecting2W) {
                    $transferCorrecting2W->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                    $transferCorrecting2W->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                }
            }
        }
        if ($sum2D <> $sum1W) {
            foreach ($listTransfersD as $transferD) {
                foreach (Palletstransfer::where('transferToCorrect', $transferD->id)->where('loading_atrnr', $loading->atrnr)->where(function ($q) {
                    $q->where('type', 'Debt')->orWhere('type', 'Sale');
                })->get() as $transferCorrecting2D) {
                    $transferCorrecting2D->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                    $transferCorrecting2D->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                }
            }
            foreach ($listTransfersW as $transferW) {
                foreach (Palletstransfer::where('transferToCorrect', $transferW->id)->where('loading_atrnr', $loading->atrnr)->where(function ($q) {
                    $q->where('type', 'Debt')->orWhere('type', 'Purchase');
                })->get() as $transferCorrecting1W) {
                    $transferCorrecting1W->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                    $transferCorrecting1W->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                }
            }
        }
        if ($sum2D <> $sum2W) {
            foreach ($listTransfersD as $transferD) {
                foreach (Palletstransfer::where('transferToCorrect', $transferD->id)->where('loading_atrnr', $loading->atrnr)->where(function ($q) {
                    $q->where('type', 'Debt')->orWhere('type', 'Sale');
                })->get() as $transferCorrecting2D) {
                    $transferCorrecting2D->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                    $transferCorrecting2D->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                }
            }
            foreach ($listTransfersW as $transferW) {
                foreach (Palletstransfer::where('transferToCorrect', $transferW->id)->where('loading_atrnr', $loading->atrnr)->where(function ($q) {
                    $q->where('type', 'Debt')->orWhere('type', 'Sale');
                })->get() as $transferCorrecting2W) {
                    $transferCorrecting2W->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                    $transferCorrecting2W->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                }
            }
        }
        //no error
        if ($sum1D == $sum1W && $sum1D == $sum2W && $sum2D == $sum1W && $sum2D == $sum2W) {
            foreach ($listTransfersD as $transferD) {
                $transferD->errors()->detach($idErrorDW_NotSameNumber);
            }
            foreach ($listTransfersW as $transferW) {
                $transferW->errors()->detach($idErrorDW_NotSameNumber);
            }
        }

        //////////TRANSFERS DW - WD //////////
        //we need to distinguish every loading place / unloading place for DW and WD transfers
        $listTransfersDW = Palletstransfer::where('type', 'Deposit-Withdrawal')->where('loading_atrnr', $loading->atrnr)->get();
        $listTransfersWD = Palletstransfer::where('type', 'Withdrawal-Deposit')->where('loading_atrnr', $loading->atrnr)->get();
        $listAccounts = [];
        foreach ($listTransfersDW as $transferDW) {
            if (!in_array($transferDW->creditAccount, $listAccounts)) {
                $listAccounts[] = $transferDW->creditAccount;
            }
        }
        foreach ($listTransfersWD as $transferWD) {
            if (!in_array($transferWD->debitAccount, $listAccounts)) {
                $listAccounts[] = $transferWD->debitAccount;
            }
        }
        foreach ($listAccounts as $account) {
            $listTransfersDW_acc = Palletstransfer::where('type', 'Deposit-Withdrawal')->where('loading_atrnr', $loading->atrnr)->where('creditAccount', $account)->get();
            $listTransfersWD_acc = Palletstransfer::where('type', 'Withdrawal-Deposit')->where('loading_atrnr', $loading->atrnr)->where('debitAccount', $account)->get();

            //2) check if for DW transfers there is at least 1 WD transfer and inversely
            if (count($listTransfersWD_acc) == 0) {
                foreach ($listTransfersDW_acc as $transferDW) {
                    $transferDW->errors()->attach($idErrorWDDW_atLeastOne);
                }
            }
            if (count($listTransfersDW_acc) == 0) {
                foreach ($listTransfersWD_acc as $transferWD) {
                    $transferWD->errors()->attach($idErrorWDDW_atLeastOne);
                }
            }
            //2) correction : no correction possible - add a new normal DW-WD transfer


            //3) check if sumDW = sum WD
            if (Palletstransfer::where('type', 'Deposit-Withdrawal')->where('loading_atrnr', $loading->atrnr)->where('creditAccount', $account)->sum('palletsNumber') <> Palletstransfer::where('type', 'Withdrawal-Deposit')->where('loading_atrnr', $loading->atrnr)->where('debitAccount', $account)->sum('palletsNumber')) {
                foreach ($listTransfersDW_acc as $transferDW) {
                    $transferDW->errors()->attach($idErrorDWWD_NotSameNumber);
                }
                foreach ($listTransfersWD_acc as $transferWD) {
                    $transferWD->errors()->attach($idErrorDWWD_NotSameNumber);
                }
            }

            //3) correction : check if DW + debt + PS = WD + debt + PS OR DW + debt + PS = WD + debt + SP OR DW + debt + SP = WD + debt + PS OR DW + debt + SP = WD + debt + SP
            $sum1DW = 0;
            $sum2DW = 0;
            foreach ($listTransfersDW_acc as $transferDW_acc) {
                $sumTransfersPSAssociated = Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Purchase')->where('transferToCorrect', $transferDW_acc->id)->sum('palletsNumber');
                if ($transferDW_acc->palletsNumber <= $loading->anz) {
                    $sum1DW = $sum1DW + $sumTransfersPSAssociated + $transferDW_acc->palletsNumber;
                } else {
                    $sum1DW = $sum1DW - $sumTransfersPSAssociated + $transferDW_acc->palletsNumber;
                }
                $sumTransfersSPAssociated = Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Sale')->where('transferToCorrect', $transferDW_acc->id)->sum('palletsNumber');
                if ($transferDW_acc->palletsNumber <= $loading->anz) {
                    $sum2DW = $sum2DW + $sumTransfersSPAssociated + $transferDW_acc->palletsNumber;
                } else {
                    $sum2DW = $sum2DW - $sumTransfersSPAssociated + $transferDW_acc->palletsNumber;
                }

                $transferDebtAssociated = Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Debt')->where(function ($q) use ($transferDW_acc) {
                    $q->where('transferToCorrect', 'like', '%' . $transferDW_acc->id)->orWhere('transferToCorrect', 'like', $transferDW_acc->id . '%');
                })->first();
                if ($transferDebtAssociated <> null && strpos($transferDebtAssociated->transferToCorrect, '-') == true && count(explode('-', $transferDebtAssociated->transferToCorrect)) == 2) {
                    if (explode('-', $transferDebtAssociated->transferToCorrect)[0] == $transferDW_acc->id) {
                        $transferWDAssociated = Palletstransfer::where('id', explode('-', $transferDebtAssociated->transferToCorrect)[1])->first();
                    } elseif (explode('-', $transferDebtAssociated->transferToCorrect)[1] == $transferDW_acc->id) {
                        $transferWDAssociated = Palletstransfer::where('id', explode('-', $transferDebtAssociated->transferToCorrect)[0])->first();
                    }
                    if ($transferDW_acc->palletsNumber <= $transferWDAssociated->palletsNumber) {
                        $sum1DW = $sum1DW + $transferDebtAssociated->palletsNumber;
                        $sum2DW = $sum2DW + $transferDebtAssociated->palletsNumber;
                    }
                } elseif ($transferDebtAssociated <> null && strpos($transferDebtAssociated->transferToCorrect, '-') == false) {
                    $sum1DW = $sum1DW + $transferDebtAssociated->palletsNumber;
                    $sum2DW = $sum2DW + $transferDebtAssociated->palletsNumber;
                }
            }
            $sum1WD = 0;
            $sum2WD = 0;
            foreach ($listTransfersWD_acc as $transferWD_acc) {
                $sumTransfersPSAssociated = Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Purchase')->where('transferToCorrect', $transferWD_acc->id)->sum('palletsNumber');
                if ($transferWD_acc->palletsNumber <= $loading->anz) {
                    $sum1WD = $sum1WD + $sumTransfersPSAssociated + $transferWD_acc->palletsNumber;
                } else {
                    $sum1WD = $sum1WD - $sumTransfersPSAssociated + $transferWD_acc->palletsNumber;
                }
                $sumTransfersSPAssociated = Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Sale')->where('transferToCorrect', $transferWD_acc->id)->sum('palletsNumber');
                if ($transferWD_acc->palletsNumber <= $loading->anz) {
                    $sum2WD = $sum2WD + $sumTransfersSPAssociated + $transferWD_acc->palletsNumber;
                } else {
                    $sum2WD = $sum2WD - $sumTransfersSPAssociated + $transferWD_acc->palletsNumber;
                }

                $transferDebtAssociated = Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Debt')->where(function ($q) use ($transferWD_acc) {
                    $q->where('transferToCorrect', 'like', '%' . $transferWD_acc->id)->orWhere('transferToCorrect', 'like', $transferWD_acc->id . '%');
                })->first();
                if ($transferDebtAssociated <> null && strpos($transferDebtAssociated->transferToCorrect, '-') == true && count(explode('-', $transferDebtAssociated->transferToCorrect)) == 2) {
                    if (explode('-', $transferDebtAssociated->transferToCorrect)[0] == $transferWD_acc->id) {
                        $transferDWAssociated = Palletstransfer::where('id', explode('-', $transferDebtAssociated->transferToCorrect)[1])->first();
                    } elseif (explode('-', $transferDebtAssociated->transferToCorrect)[1] == $transferWD_acc->id) {
                        $transferDWAssociated = Palletstransfer::where('id', explode('-', $transferDebtAssociated->transferToCorrect)[0])->first();
                    }
                    if ($transferWD_acc->palletsNumber <= $transferDWAssociated->palletsNumber) {
                        $sum1WD = $sum1WD + $transferDebtAssociated->palletsNumber;
                        $sum2WD = $sum2WD + $transferDebtAssociated->palletsNumber;
                    }
                } elseif ($transferDebtAssociated <> null && strpos($transferDebtAssociated->transferToCorrect, '-') == false) {
                    $sum1WD = $sum1WD + $transferDebtAssociated->palletsNumber;
                    $sum2WD = $sum2WD + $transferDebtAssociated->palletsNumber;
                }
            }

            //errors
            if ($sum1DW <> $sum1WD) {
                foreach ($listTransfersDW_acc as $transferDW_acc) {
                    foreach (Palletstransfer::where('transferToCorrect', $transferDW_acc->id)->where('loading_atrnr', $loading->atrnr)->where('type', 'Purchase')->get() as $transferCorrecting1DW) {
                        $transferCorrecting1DW->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                    foreach (Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Debt')->where(function ($q) use ($transferDW_acc) {
                        $q->where('transferToCorrect', 'like', '%' . $transferDW_acc->id)->orWhere('transferToCorrect', 'like', $transferDW_acc->id . '%');
                    })->get() as $transferCorrectingDebt) {
                        $transferCorrectingDebt->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                }
                foreach ($listTransfersWD_acc as $transferWD_acc) {
                    foreach (Palletstransfer::where('transferToCorrect', $transferWD_acc->id)->where('loading_atrnr', $loading->atrnr)->where('type', 'Purchase')->get() as $transferCorrecting1WD) {
                        $transferCorrecting1WD->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                    foreach (Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Debt')->where(function ($q) use ($transferWD_acc) {
                        $q->where('transferToCorrect', 'like', '%' . $transferWD_acc->id)->orWhere('transferToCorrect', 'like', $transferWD_acc->id . '%');
                    })->get() as $transferCorrectingDebt) {
                        $transferCorrectingDebt->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrectingDebt->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                }
            }
            if ($sum1DW <> $sum2WD) {
                foreach ($listTransfersDW_acc as $transferDW_acc) {
                    foreach (Palletstransfer::where('transferToCorrect', $transferDW_acc->id)->where('loading_atrnr', $loading->atrnr)->where('type', 'Purchase')->get() as $transferCorrecting1DW) {
                        $transferCorrecting1DW->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrecting1DW->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                    foreach (Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Debt')->where(function ($q) use ($transferDW_acc) {
                        $q->where('transferToCorrect', 'like', '%' . $transferDW_acc->id)->orWhere('transferToCorrect', 'like', $transferDW_acc->id . '%');
                    })->get() as $transferCorrectingDebt) {
                        $transferCorrectingDebt->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrectingDebt->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                }
                foreach ($listTransfersWD_acc as $transferWD_acc) {
                    foreach (Palletstransfer::where('transferToCorrect', $transferWD_acc->id)->where('loading_atrnr', $loading->atrnr)->where('type', 'Sale')->get() as $transferCorrecting2WD) {
                        $transferCorrecting2WD->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrecting2WD->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                    foreach (Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Debt')->where(function ($q) use ($transferWD_acc) {
                        $q->where('transferToCorrect', 'like', '%' . $transferWD_acc->id)->orWhere('transferToCorrect', 'like', $transferWD_acc->id . '%');
                    })->get() as $transferCorrectingDebt) {
                        $transferCorrectingDebt->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrectingDebt->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                }
            }
            if ($sum2DW <> $sum1WD) {
                foreach ($listTransfersDW_acc as $transferDW_acc) {
                    foreach (Palletstransfer::where('transferToCorrect', $transferDW_acc->id)->where('loading_atrnr', $loading->atrnr)->where('type', 'Sale')->get() as $transferCorrecting2DW) {
                        $transferCorrecting2DW->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrecting2DW->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                    foreach (Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Debt')->where(function ($q) use ($transferDW_acc) {
                        $q->where('transferToCorrect', 'like', '%' . $transferDW_acc->id)->orWhere('transferToCorrect', 'like', $transferDW_acc->id . '%');
                    })->get() as $transferCorrectingDebt) {
                        $transferCorrectingDebt->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrectingDebt->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                }
                foreach ($listTransfersWD_acc as $transferWD_acc) {
                    foreach (Palletstransfer::where('transferToCorrect', $transferWD_acc->id)->where('loading_atrnr', $loading->atrnr)->where('type', 'Purchase')->get() as $transferCorrecting1WD) {
                        $transferCorrecting1WD->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrecting1WD->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                    foreach (Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Debt')->where(function ($q) use ($transferWD_acc) {
                        $q->where('transferToCorrect', 'like', '%' . $transferWD_acc->id)->orWhere('transferToCorrect', 'like', $transferWD_acc->id . '%');
                    })->get() as $transferCorrectingDebt) {
                        $transferCorrectingDebt->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrectingDebt->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                }
            }
            if ($sum2DW <> $sum2WD) {
                foreach ($listTransfersDW_acc as $transferDW_acc) {
                    foreach (Palletstransfer::where('transferToCorrect', $transferDW_acc->id)->where('loading_atrnr', $loading->atrnr)->where('type', 'Sale')->get() as $transferCorrecting2DW) {
                        $transferCorrecting2DW->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrecting2DW->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                    foreach (Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Debt')->where(function ($q) use ($transferDW_acc) {
                        $q->where('transferToCorrect', 'like', '%' . $transferDW_acc->id)->orWhere('transferToCorrect', 'like', $transferDW_acc->id . '%');
                    })->get() as $transferCorrectingDebt) {
                        $transferCorrectingDebt->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrectingDebt->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                }
                foreach ($listTransfersWD_acc as $transferWD_acc) {
                    foreach (Palletstransfer::where('transferToCorrect', $transferWD_acc->id)->where('loading_atrnr', $loading->atrnr)->where('type', 'Sale')->get() as $transferCorrecting2WD) {
                        $transferCorrecting2WD->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrecting2WD->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                    foreach (Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Debt')->where(function ($q) use ($transferWD_acc) {
                        $q->where('transferToCorrect', 'like', '%' . $transferWD_acc->id)->orWhere('transferToCorrect', 'like', $transferWD_acc->id . '%');
                    })->get() as $transferCorrectingDebt) {
                        $transferCorrectingDebt->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrectingDebt->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                }
            }
            //no error
            if ($sum1DW == $sum1WD && $sum1DW == $sum2WD && $sum2DW == $sum1WD && $sum2DW == $sum2WD) {
                foreach ($listTransfersDW_acc as $transferDW_acc) {
                    $transferDW_acc->errors()->detach($idErrorDWWD_NotSameNumber);
                }
                foreach ($listTransfersWD_acc as $transferWD_acc) {
                    $transferWD_acc->errors()->detach($idErrorDWWD_NotSameNumber);
                }
            }

            //4) check if sum DW = sum WD = anz
            if (Palletstransfer::where('type', 'Deposit-Withdrawal')->where('loading_atrnr', $loading->atrnr)->where('creditAccount', $account)->sum('palletsNumber') <> $loading->anz) {
                foreach ($listTransfersDW_acc as $transferDW) {
                    $transferDW->errors()->attach($idErrorWDDW_NotNumberLoadingOrder);
                }
            }
            if (Palletstransfer::where('type', 'Withdrawal-Deposit')->where('loading_atrnr', $loading->atrnr)->where('debitAccount', $account)->sum('palletsNumber') <> $loading->anz) {
                foreach ($listTransfersWD_acc as $transferWD) {
                    $transferWD->errors()->attach($idErrorWDDW_NotNumberLoadingOrder);
                }
            }

            //4) correction : check if DW + debt + PS = WD + debt + PS OR DW + debt + PS = WD + debt + SP OR DW + debt + SP = WD + debt + PS OR DW + debt + SP = WD + debt + SP
            $sum1DW = 0;
            $sum2DW = 0;
            foreach ($listTransfersDW_acc as $transferDW_acc) {
                $sumTransfersPSAssociated = Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Purchase')->where('transferToCorrect', $transferDW_acc->id)->sum('palletsNumber');
                if ($transferDW_acc->palletsNumber <= $loading->anz) {
                    $sum1DW = $sum1DW + $sumTransfersPSAssociated + $transferDW_acc->palletsNumber;
                } else {
                    $sum1DW = $sum1DW - $sumTransfersPSAssociated + $transferDW_acc->palletsNumber;
                }
                $sumTransfersSPAssociated = Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Sale')->where('transferToCorrect', $transferDW_acc->id)->sum('palletsNumber');
                if ($transferDW_acc->palletsNumber <= $loading->anz) {
                    $sum2DW = $sum2DW + $sumTransfersSPAssociated + $transferDW_acc->palletsNumber;
                } else {
                    $sum2DW = $sum2DW - $sumTransfersSPAssociated + $transferDW_acc->palletsNumber;
                }


                $transferDebtAssociated = Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Debt')->where(function ($q) use ($transferDW_acc) {
                    $q->where('transferToCorrect', 'like', '%' . $transferDW_acc->id)->orWhere('transferToCorrect', 'like', $transferDW_acc->id . '%');
                })->first();
                if ($transferDebtAssociated <> null && strpos($transferDebtAssociated->transferToCorrect, '-') == true && count(explode('-', $transferDebtAssociated->transferToCorrect)) == 2) {
                    if (explode('-', $transferDebtAssociated->transferToCorrect)[0] == $transferDW_acc->id) {
                        $transferWDAssociated = Palletstransfer::where('id', explode('-', $transferDebtAssociated->transferToCorrect)[1])->first();
                    } elseif (explode('-', $transferDebtAssociated->transferToCorrect)[1] == $transferDW_acc->id) {
                        $transferWDAssociated = Palletstransfer::where('id', explode('-', $transferDebtAssociated->transferToCorrect)[0])->first();
                    }
                    if ($transferDW_acc->palletsNumber <= $transferWDAssociated->palletsNumber) {
                        $sum1DW = $sum1DW + $transferDebtAssociated->palletsNumber;
                        $sum2DW = $sum2DW + $transferDebtAssociated->palletsNumber;
                    }
                } elseif ($transferDebtAssociated <> null && strpos($transferDebtAssociated->transferToCorrect, '-') == false) {
                    $sum1DW = $sum1DW + $transferDebtAssociated->palletsNumber;
                    $sum2DW = $sum2DW + $transferDebtAssociated->palletsNumber;
                }
            }
            $sum1WD = 0;
            $sum2WD = 0;
            foreach ($listTransfersWD_acc as $transferWD_acc) {
                $sumTransfersPSAssociated = Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Purchase')->where('transferToCorrect', $transferWD_acc->id)->sum('palletsNumber');
                if ($transferWD_acc->palletsNumber <= $loading->anz) {
                    $sum1WD = $sum1WD + $sumTransfersPSAssociated + $transferWD_acc->palletsNumber;
                } else {
                    $sum1WD = $sum1WD - $sumTransfersPSAssociated + $transferWD_acc->palletsNumber;
                }
                $sumTransfersSPAssociated = Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Sale')->where('transferToCorrect', $transferWD_acc->id)->sum('palletsNumber');
                if ($transferWD_acc->palletsNumber <= $loading->anz) {
                    $sum2WD = $sum2WD + $sumTransfersSPAssociated + $transferWD_acc->palletsNumber;
                } else {
                    $sum2WD = $sum2WD - $sumTransfersSPAssociated + $transferWD_acc->palletsNumber;
                }

                $transferDebtAssociated = Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Debt')->where(function ($q) use ($transferWD_acc) {
                    $q->where('transferToCorrect', 'like', '%' . $transferWD_acc->id)->orWhere('transferToCorrect', 'like', $transferWD_acc->id . '%');
                })->first();
                if ($transferDebtAssociated <> null && strpos($transferDebtAssociated->transferToCorrect, '-') == true && count(explode('-', $transferDebtAssociated->transferToCorrect)) == 2) {
                    if (explode('-', $transferDebtAssociated->transferToCorrect)[0] == $transferWD_acc->id) {
                        $transferDWAssociated = Palletstransfer::where('id', explode('-', $transferDebtAssociated->transferToCorrect)[1])->first();
                    } elseif (explode('-', $transferDebtAssociated->transferToCorrect)[1] == $transferWD_acc->id) {
                        $transferDWAssociated = Palletstransfer::where('id', explode('-', $transferDebtAssociated->transferToCorrect)[0])->first();
                    }
                    if ($transferWD_acc->palletsNumber <= $transferDWAssociated->palletsNumber) {
                        $sum1WD = $sum1WD + $transferDebtAssociated->palletsNumber;
                        $sum2WD = $sum2WD + $transferDebtAssociated->palletsNumber;
                    }
                } elseif ($transferDebtAssociated <> null && strpos($transferDebtAssociated->transferToCorrect, '-') == false) {
                    $sum1WD = $sum1WD + $transferDebtAssociated->palletsNumber;
                    $sum2WD = $sum2WD + $transferDebtAssociated->palletsNumber;
                }
            }

            //errors
            if ($sum1DW <> $loading->anz) {
                foreach ($listTransfersDW_acc as $transferDW_acc) {
                    foreach (Palletstransfer::where('transferToCorrect', $transferDW_acc->id)->where('loading_atrnr', $loading->atrnr)->where('type', 'Purchase')->get() as $transferCorrecting1DW) {
                        $transferCorrecting1DW->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrecting1DW->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                    foreach (Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Debt')->where(function ($q) use ($transferDW_acc) {
                        $q->where('transferToCorrect', 'like', '%' . $transferDW_acc->id)->orWhere('transferToCorrect', 'like', $transferDW_acc->id . '%');
                    })->get() as $transferCorrectingDebt) {
                        $transferCorrectingDebt->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrectingDebt->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                }
            }
            if ($sum1WD <> $loading->anz) {
                foreach ($listTransfersWD_acc as $transferWD_acc) {
                    foreach (Palletstransfer::where('transferToCorrect', $transferWD_acc->id)->where('loading_atrnr', $loading->atrnr)->where('type', 'Purchase')->get() as $transferCorrecting1WD) {
                        $transferCorrecting1WD->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrecting1WD->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                    foreach (Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Debt')->where(function ($q) use ($transferWD_acc) {
                        $q->where('transferToCorrect', 'like', '%' . $transferWD_acc->id)->orWhere('transferToCorrect', 'like', $transferWD_acc->id . '%');
                    })->get() as $transferCorrectingDebt) {
                        $transferCorrectingDebt->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrectingDebt->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                }
            }
            if ($sum2DW <> $loading->anz) {
                foreach ($listTransfersDW_acc as $transferDW_acc) {
                    foreach (Palletstransfer::where('transferToCorrect', $transferDW_acc->id)->where('loading_atrnr', $loading->atrnr)->where('type', 'Sale')->get() as $transferCorrecting2DW) {
                        $transferCorrecting2DW->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrecting2DW->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                    foreach (Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Debt')->where(function ($q) use ($transferDW_acc) {
                        $q->where('transferToCorrect', 'like', '%' . $transferDW_acc->id)->orWhere('transferToCorrect', 'like', $transferDW_acc->id . '%');
                    })->get() as $transferCorrectingDebt) {
                        $transferCorrectingDebt->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrectingDebt->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                }
            }
            if ($sum2WD <> $loading->anz) {
                foreach ($listTransfersWD_acc as $transferWD_acc) {
                    foreach (Palletstransfer::where('transferToCorrect', $transferWD_acc->id)->where('loading_atrnr', $loading->atrnr)->where('type', 'Sale')->get() as $transferCorrecting2WD) {
                        $transferCorrecting2WD->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrecting2WD->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                    foreach (Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Debt')->where(function ($q) use ($transferWD_acc) {
                        $q->where('transferToCorrect', 'like', '%' . $transferWD_acc->id)->orWhere('transferToCorrect', 'like', $transferWD_acc->id . '%');
                    })->get() as $transferCorrectingDebt) {
                        $transferCorrectingDebt->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrectingDebt->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                }
            }

            //no error
            if ($sum1DW == $loading->anz && $sum2DW == $loading->anz) {
                foreach ($listTransfersDW_acc as $transferDW_acc) {
                    $transferDW_acc->errors()->detach($idErrorWDDW_NotNumberLoadingOrder);
                }
            }
            if ($sum1WD == $loading->anz && $sum2WD == $loading->anz) {
                foreach ($listTransfersWD_acc as $transferWD_acc) {
                    $transferWD_acc->errors()->detach($idErrorWDDW_NotNumberLoadingOrder);
                }
            }
        }


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

///////////SUBLOADING///////////////////////////////////

//    /**
//     * show the add form to add a subloading
//     * @param $atrnr
//     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
//     */
//    public function showAdd($atrnr)
//    {
//        $loading = Loading::where('atrnr', $atrnr)->first();
//        return view('loadings.addSubloading', compact('loading'));
//    }
//
//    /**
//     * add a subloading
//     * @param $atrnr
//     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
//     */
//    public function add($atrnr)
//    {
//        $loadingInitial = Loading::where('atrnr', $atrnr)->first();
//        $referenz = Input::get('referenz');
//        $auftraggeber = Input::get('auftraggeber');
//        $subfrachter = Input::get('subfrachter');
//        $kennzeichen = Input::get('kennzeichen');
//        $art = Input::get('art');
//        $anz = Input::get('anz');
//        $ware = Input::get('ware');
//        $ladedatum = Input::get('ladedatum');
//        $beladestelle = Input::get('beladestelle');
//        $ortb = Input::get('ortb');
//        $plzb = Input::get('plzb');
//        $landb = Input::get('landb');
//        $zusladestellen = Input::get('zusladestellen');
//        $entladedatum = Input::get('entladedatum');
//        $entladestelle = Input::get('entladestelle');
//        $orte = Input::get('orte');
//        $plze = Input::get('plze');
//        $lande = Input::get('lande');
//        $disp = $loadingInitial->disp;
//        $pt = $loadingInitial->pt;
//
//        $listLoadingsAtrnr = Loading::where('atrnr', 'like', $loadingInitial->atrnr . '-' . '%')->get();
//        $max = 0;
//        foreach ($listLoadingsAtrnr as $loadingAtrnr) {
//            if (substr_count($loadingAtrnr->atrnr, '-') == substr_count($atrnr . '-', '-')) {
//                $explode = explode('-', $loadingAtrnr->atrnr);
//                if ($explode[count($explode) - 1] > $max) {
//                    $max = $explode[count($explode) - 1];
//                }
//            }
//        }
//        $max = $max + 1;
//        $atrnr = $atrnr . '-' . $max;
//
//        $loadingsTest = Loading::where('atrnr', '=', $atrnr)->first();
//        if ($loadingsTest == null) {
//            $k = count(Loading::get()) + 1;
//            Loading::firstOrCreate([
//                'id' => $k,
//                'ladedatum' => $ladedatum,
//                'entladedatum' => $entladedatum,
//                'disp' => $disp,
//                'atrnr' => $atrnr,
//                'referenz' => $referenz,
//                'auftraggeber' => $auftraggeber,
//                'beladestelle' => $beladestelle,
//                'landb' => $landb,
//                'plzb' => $plzb,
//                'ortb' => $ortb,
//                'entladestelle' => $entladestelle,
//                'lande' => $lande,
//                'plze' => $plze,
//                'orte' => $orte,
//                'anz' => $anz,
//                'art' => $art,
//                'ware' => $ware,
//                'pt' => $pt,
//                'subfrachter' => $subfrachter,
//                'kennzeichen' => $kennzeichen,
//                'zusladestellen' => $zusladestellen,
//            ]);
//        }
//
//        return redirect('/loadings/false');
//    }
}
