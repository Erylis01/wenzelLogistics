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

            //link to the mother loading of the subloading
            if (substr_count($loading->atrnr, '-') <> 0) {
                $atrnr1 = explode('-', $loading->atrnr)[0];
                $atrnr2 = array_slice(explode('-', $loading->atrnr), 1);
                $atrnr2 = implode('-', $atrnr2);
            }

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
                $q->where('type', 'Purchase-Sale')->orWhere('type', 'Sale-Purchase')->orWhere('type', 'Other')->orWhere('type', 'Debt');
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
     * @param Request $request
     * @param $atrnr
     * @return $this
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
            if (!strpos($subfrachter, '-') || !strpos($subfrachter, ',')) {
                session()->flash('messageErrorSubfrachter', "The subfrachter/carrier hasn't been field as expected");
                $view = 'ok';
            } else {
                $testCarrier = Palletsaccount::where('type', 'Carrier')->where('nickname', explode(',', $subfrachter)[0])->first();
                if ($testCarrier == null) {
                    session()->flash('testCarrier', true);
                    $view = 'testCarrier';
                } else {
                    Loading::where('atrnr', $atrnr)->update(['subfrachter' => $subfrachter]);
                    $testTruck = Truck::where('name', explode(',', $subfrachter)[0])->where('licensePlate', $kennzeichen)->first();

                    if ($testTruck == null) {
                        session()->flash('testTruck', true);
                        $view = 'testTruck';
                    } else {
                        Loading::where('atrnr', $atrnr)->update(['kennzeichen' => $kennzeichen]);
                        $view = 'ok';
                    }
                }
                session()->flash('messageUpdateLoading', 'Successfully updated loading');
            }
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

        //link to the mother loading of the subloading
        if (substr_count($loading->atrnr, '-') <> 0) {
            $atrnr1 = explode('-', $loading->atrnr)[0];
            $atrnr2 = array_slice(explode('-', $loading->atrnr), 1);
            $atrnr2 = implode('-', $atrnr2);
        }

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
            $q->where('type', 'Purchase-Sale')->orWhere('type', 'Sale-Purchase')->orWhere('type', 'Debt')->orWhere('type', 'Other');
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
            $normalTransferAssociated = Input::get('normalTransferAssociated');
//            $normalTransferAssociated2 = Input::get('normalTransferAssociated2');

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
                } elseif ($type == 'Purchase-Sale') {
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
                    $subfrachterName = str_replace('  ', ' ', trim(explode(',', $subfrachter)[0]));
                    $partsSubfrachterName = explode(' ', $subfrachterName);
                    foreach ($partsSubfrachterName as $partName) {
                        $q->orWhere('nickname', 'LIKE', '%' . $partName . '%');
                    }
                    $subfrachterAdress = trim(explode(',', $subfrachter)[1]);
                    $partsSubfrachterAdress1 = trim(explode('-', $subfrachterAdress)[0]);
                    $q->orWhere('adress', 'LIKE', $partsSubfrachterAdress1 . '%');

                    $partsSubfrachterAdress2 = explode(' ', str_replace('  ', ' ', trim(explode('-', $subfrachterAdress)[1])));
                    foreach ($partsSubfrachterAdress2 as $partsAdress) {
                        $q->orWhere('adress', 'LIKE', '%' . $partsAdress . '%');
                    }
                })->orderBy('nickname', 'asc')->get();

                return view('loadings.detailsLoading', compact('subfrachter', 'loading', 'disp', 'atrnr1', 'atrnr2', 'listPalletsAccounts', 'truckAssociated', 'listAccountsTransfers', 'listTrucksAccounts', 'listPalletstransfers', 'listPalletstransfersNormal', 'listPalletstransfersCorrecting', 'theoricalNumberPalletsTruck', 'realNumberPalletsTruck',
                    'actionForm', 'listPossibilitiesCarriers'
                ));
            } elseif ($view == 'createCarrier') {
                $originalPage = 'detailsLoading-' . $loading->atrnr;
                $listWarehouses = DB::table('warehouses')->get();
                $type = 'Carrier';
                $name = trim(explode(',', $subfrachter)[0]);
                $nickname = $name;
                $adress = trim(explode(',', $subfrachter)[1]);
                $atrnr = $loading->atrnr;
                return view('palletsaccounts.addPalletsaccount', compact('atrnr', 'name', 'nickname', 'adress', 'type', 'listWarehouses', 'originalPage'));
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
            $addTransferForm = $this->addPalletsTransfer($loading, $notExchanging, $truckAssociated, $type, $debitAccount, $creditAccount, $debitAccount2, $creditAccount2, $palletsNumber, $palletsNumber2, $creditAccount3, $debitAccount3, $palletsNumber3, $normalTransferAssociated);
            $loading = Loading::where('atrnr', $loading->atrnr)->first();
            if ($addTransferForm == 'error') {
                //redirect with error
                if ($type == 'Deposit-Withdrawal' || $type == 'Deposit_Only' || $type == 'Withdrawal_Only') {
                    $actionForm = 'addTransferForm';
                    return view('loadings.detailsLoading', compact('loading', 'disp', 'atrnr1', 'atrnr2', 'date', 'details', 'type', 'creditAccount', 'debitAccount', 'palletsNumber', 'truckAssociated', 'listPalletsAccounts', 'listTrucksAccounts', 'listAccountsTransfers', 'listPalletstransfers', 'listPalletstransfersNormal', 'listPalletstransfersCorrecting', 'actionForm', 'theoricalNumberPalletsTruck', 'realNumberPalletsTruck'));
                } elseif ($type == 'Purchase-Sale' || $type == 'Other' || $type == 'Debt') {
                    $actionForm = 'showAddCorrectingTransfer-' . $normalTransferAssociated;
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
                } elseif (!isset($type) && isset($normalTransferAssociated)) {
                    $actionForm = 'showAddCorrectingTransfer-' . $normalTransferAssociated;
                    $transferNormal = Palletstransfer::where('id', $normalTransferAssociated)->first();
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
                } elseif ($type == 'Purchase-Sale' || $type == 'Other' || $type == 'Debt') {
                    $showAddCorrectingTransfer = true;
                    return view('loadings.detailsLoading', compact('loading', 'disp', 'atrnr1', 'atrnr2', 'date', 'details', 'type', 'normalTransferAssociated', 'normalTransferAssociated2', 'creditAccount', 'debitAccount', 'palletsNumber', 'creditAccount2', 'debitAccount2', 'truckAssociated', 'palletsNumber2', 'listPalletsAccounts', 'listTrucksAccounts', 'listAccountsTransfers', 'listPalletstransfers', 'listPalletstransfersNormal', 'listPalletstransfersCorrecting', 'actionForm', 'showAddCorrectingTransfer', 'theoricalNumberPalletsTruck', 'realNumberPalletsTruck'));
                }
            }
        } elseif (isset($actionForm) && $actionForm == 'okSubmitAddModal') {
            //accept to add the transfer
            $this->validateAddPalletsTransfer($loading, $type, $date, $details, $creditAccount, $debitAccount, $creditAccount2, $debitAccount2, $palletsNumber, $palletsNumber2, $creditAccount3, $debitAccount3, $palletsNumber3, $normalTransferAssociated, $notExchanging);
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
            if (!isset($normalTransferAssociated)) {
                session()->flash('openAddForm', 'addTransferForm');
            }
            return redirect()->back();
        } elseif (isset($actionForm) && $actionForm == 'closeSubmitAddModal') {
            //refuse to add the transfer -> good redirection
            if (isset($normalTransferAssociated)) {
                $actionForm = 'showAddCorrectingTransfer-' . $normalTransferAssociated;
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
        } elseif (isset($actionForm) && explode('-', $actionForm)[0] == 'upload') {
            $transfer = Palletstransfer::where('id', explode('-', $actionForm)[1])->first();
            $documents = $request->file('documentsTransfer' . explode('-', $actionForm)[1]);
            $this->upload($documents, $transfer, $loading);
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
            $details = Input::get('details' . explode('-', $actionForm)[1]);
            $validate = Input::get('validate' . explode('-', $actionForm)[1]);
            $filesNames = $this->actualDocuments($transfer->id);

            $submitPalletsNormal = $this->defineSubmitPalletsValue($listPalletstransfersNormal, $listPalletstransfersCorrecting, explode('-', $actionForm)[1])[0];
            $submitPalletsCorrecting = $this->defineSubmitPalletsValue($listPalletstransfersNormal, $listPalletstransfersCorrecting, explode('-', $actionForm)[1])[1];

            $view = $this->updateTransfer($transfer, $validate, $details, $loading, $filesNames);
            if ($view == 'error') {
                return redirect()->back();
            } else {
                //get only the normal transfers (deposit/withdrawal)
                $listPalletstransfersNormal = Palletstransfer::where('loading_atrnr', $atrnr)->where(function ($q) {
                    $q->where('type', 'Deposit_Only')->orWhere('type', 'Withdrawal_Only')->orWhere('type', 'Withdrawal-Deposit')->orWhere('type', 'Deposit-Withdrawal');
                })->get();
                //get only the correcting transfers (sale/purchase)
                $listPalletstransfersCorrecting = Palletstransfer::where('loading_atrnr', $atrnr)->where(function ($q) {
                    $q->where('type', 'Purchase-Sale')->orWhere('type', 'Sale-Purchase')->orWhere('type', 'Debt')->orWhere('type', 'Other');
                })->get();
                $transfer = Palletstransfer::where('id', $transfer->id)->first();
                return view('loadings.detailsLoading', compact('loading', 'disp', 'atrnr1', 'atrnr2', 'truckAssociated', 'listPalletsAccounts', 'listTrucksAccounts', 'listAccountsTransfers', 'listPalletstransfers', 'listPalletstransfersNormal', 'listPalletstransfersCorrecting',
                    'transfer', 'submitPalletsNormal', 'submitPalletsCorrecting', 'filesNames', 'theoricalNumberPalletsTruck', 'realNumberPalletsTruck'));
            }

        } elseif (isset($actionForm) && explode('-', $actionForm)[0] == 'okSubmitPalletsModal') {
            $transfer = Palletstransfer::where('id', explode('-', $actionForm)[1])->first();
            $okSubmitPalletsModalNormal = $this->defineSubmitPalletsValue($listPalletstransfersNormal, $listPalletstransfersCorrecting, explode('-', $actionForm)[1])[0];
            $okSubmitPalletsModalCorrecting = $this->defineSubmitPalletsValue($listPalletstransfersNormal, $listPalletstransfersCorrecting, explode('-', $actionForm)[1])[1];
            $filesNames = $this->actualDocuments($transfer->id);

            $view = $this->validateUpdateTransfer($transfer);
            $transfer = Palletstransfer::where('id', explode('-', $actionForm)[1])->first();
            if ($view == 'ok') {
                //get only the normal transfers (deposit/withdrawal)
                $listPalletstransfersNormal = Palletstransfer::where('loading_atrnr', $atrnr)->where(function ($q) {
                    $q->where('type', 'Deposit_Only')->orWhere('type', 'Withdrawal_Only')->orWhere('type', 'Withdrawal-Deposit')->orWhere('type', 'Deposit-Withdrawal');
                })->get();
                //get only the correcting transfers (sale/purchase)
                $listPalletstransfersCorrecting = Palletstransfer::where('loading_atrnr', $atrnr)->where(function ($q) {
                    $q->where('type', 'Purchase-Sale')->orWhere('type', 'Sale-Purchase')->orWhere('type', 'Debt')->orWhere('type', 'Other');
                })->get();

                return view('loadings.detailsLoading', compact('loading', 'disp', 'atrnr1', 'atrnr2', 'truckAssociated', 'listPalletsAccounts', 'listTrucksAccounts', 'listAccountsTransfers', 'listPalletstransfers', 'listPalletstransfersNormal', 'listPalletstransfersCorrecting',
                    'transfer', 'okSubmitPalletsModalNormal', 'okSubmitPalletsModalCorrecting', 'filesNames', 'theoricalNumberPalletsTruck', 'realNumberPalletsTruck'));
            } elseif ($view == 'back') {
                return redirect()->back();
            }
        } elseif (isset($actionForm) && explode('-', $actionForm)[0] == 'closeSubmitPalletsModal') {
            //refuse the transfer update
            $this->refuseValidateUpdateTransfer(explode('-', $actionForm)[1], $loading);
            return redirect()->back();
        } elseif (isset($actionForm) && explode('-', $actionForm)[0] == 'okSubmitPalletsValidateModal') {
            $this->validateCompleteUpdateTransfer(explode('-', $actionForm)[1], $loading, $listPalletstransfers);
            return redirect()->back();
        }
    }


    /**
     * Prepare the data to add a new pallets transfer, then redirect to a page to confirm the adding
     * @param $type
     * @param $debitAccount
     * @param $creditAccount
     * @param $debitAccount2
     * @param $creditAccount2
     * @param $palletsNumber
     * @param $palletsNumber2
     * @param $normalTransferAssociated
     * @return string
     */
    public function addPalletsTransfer($loading, $notExchanging, $truckAssociated, $type, $debitAccount, $creditAccount, $debitAccount2, $creditAccount2, $palletsNumber, $palletsNumber2, $creditAccount3, $debitAccount3, $palletsNumber3, $normalTransferAssociated)
    {
        if ($debitAccount == $creditAccount || (isset($debitAccount2) && isset($creditAccount2) && $debitAccount2 == $creditAccount2) || (isset($debitAccount3) && isset($creditAccount3) && $debitAccount3 == $creditAccount3)) {
            $view = 'error';
            session()->flash('errorFields', "The fields have not been filled as expected : debit account and credit account must be different");
        } else {
            if (!isset($type)) {
                session()->flash('errorType', "The type hasn't been filled");
                $view = 'error';
            } elseif (isset($type) && !isset($debitAccount) && !isset($creditAccount) && !(isset($palletsNumber))) {
                $view = 'error';
                session()->flash('errorFields', "The fields have not been filled as expected");
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
            } elseif ($type == 'Purchase-Sale') {
                if (!isset($normalTransferAssociated) && !isset($debitAccount) && !isset($creditAccount) && !isset($palletsNumber) && !isset($debitAccount2) && !isset($creditAccount2) && !isset($palletsNumber2)) {
                    $view = 'error';
                    session()->flash('errorFields', "The fields have not been filled as expected");
                } else {
                    $view = 'ok';
                }
            } else {
                if (!isset($normalTransferAssociated) && !isset($debitAccount) && !isset($creditAccount) && !(isset($palletsNumber))) {
                    $view = 'error';
                    session()->flash('errorFields', "The fields have not been filled as expected");
                } else {
                    $view = 'ok';
                }
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
            if (($type == 'Deposit-Withdrawal' || $type == 'Purchase-Sale') && isset($creditAccount2) && isset($debitAccount2)) {
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
                $actualDebtDebitPalletsNumber3 = $this->actualDebtPalletsNumber($creditAccount3, $debitAccount3)[0];

//                }
                session()->put('palletsNumberDebtDebitAccount3', $actualDebtDebitPalletsNumber3);
//                if ($creditAccount3 == $creditAccount) {
//                    $actualTheoricalCreditPalletsNumber3 = $actualTheoricalCreditPalletsNumber + $palletsNumber - $palletsNumber2;
//                } elseif ($creditAccount3 == $debitAccount) {
//                    $actualTheoricalCreditPalletsNumber3 = $actualTheoricalDebitPalletsNumber - $palletsNumber + $palletsNumber2;
//                } else {
                $actualDebtCreditPalletsNumber3 = $this->actualDebtPalletsNumber($creditAccount3, $debitAccount3)[1];

            }
            session()->put('palletsNumberDebtCreditAccount3', $actualDebtCreditPalletsNumber3);
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
    public function actualDebtPalletsNumber($creditAccount, $debitAccount)
    {
        if (strpos($debitAccount, '-') == 5 && explode('-', $debitAccount)[0] == 'truck') {
            //truck account
            $actualDebtDebitPalletsNumber = Truck::where('id', explode('-', $debitAccount)[1])->value('palletsDebt');
        } elseif (strpos($debitAccount, '-') == 7 && explode('-', $debitAccount)[0] == 'account') {
            //others accounts (network, other)
            $actualDebtDebitPalletsNumber = Palletsaccount::where('id', explode('-', $debitAccount)[1])->value('palletsDebt');
        }

        if (strpos($creditAccount, '-') == 5 && explode('-', $creditAccount)[0] == 'truck') {
            //truck account
            $actualDebtCreditPalletsNumber = Truck::where('id', explode('-', $creditAccount)[1])->value('palletsDebt');
        } elseif (strpos($creditAccount, '-') == 7 && explode('-', $creditAccount)[0] == 'account') {
            //others accounts (network, other)
            $actualDebtCreditPalletsNumber = Palletsaccount::where('id', explode('-', $creditAccount)[1])->value('palletsDebt');
        }

        return [$actualDebtDebitPalletsNumber, $actualDebtCreditPalletsNumber];
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
                session()->flash('thPalletsNumberCreditAccount', Truck::where('id', explode('-', $creditAccount)[1])->first()->theoricalNumberPallets);
            }
        } elseif (strpos($creditAccount, '-') == 7 && explode('-', $creditAccount)[0] == 'account') {
            //others accounts (network, other)
            $namePalletsAccount = Palletsaccount::where('id', explode('-', $creditAccount)[1])->value('nickname');
            $creditAccountTransfer = $namePalletsAccount . '-' . $creditAccount;
            if ($index <> null) {
                session()->flash('creditAccount', $namePalletsAccount);
                session()->flash('thPalletsNumberCreditAccount', Palletsaccount::where('id', explode('-', $creditAccount)[1])->first()->theoricalNumberPallets);
            }
        }
        if (strpos($debitAccount, '-') == 5 && explode('-', $debitAccount)[0] == 'truck') {
            //truck account
            $nameTruckAccount = Truck::where('id', explode('-', $debitAccount)[1])->value('name');
            $licensePlate = Truck::where('id', explode('-', $debitAccount)[1])->value('licensePlate');
            $debitAccountTransfer = $nameTruckAccount . '-' . $licensePlate . '-' . $debitAccount;
            if ($index <> null) {
                session()->flash('debitAccount', $nameTruckAccount . ' - ' . $licensePlate);
                session()->flash('thPalletsNumberDebitAccount', Truck::where('id', explode('-', $debitAccount)[1])->first()->theoricalNumberPallets);
            }
        } elseif (strpos($debitAccount, '-') == 7 && explode('-', $debitAccount)[0] == 'account') {
            //others accounts (network, other)
            $namePalletsAccount = Palletsaccount::where('id', explode('-', $debitAccount)[1])->value('nickname');
            $debitAccountTransfer = $namePalletsAccount . '-' . $debitAccount;
            if ($index <> null) {
                session()->flash('debitAccount', $namePalletsAccount);
                session()->flash('thPalletsNumberDebitAccount', Palletsaccount::where('id', explode('-', $debitAccount)[1])->first()->theoricalNumberPallets);
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
     * @param $normalTransferAssociated
     */
    public function createTransfer($loading, $type, $date, $details, $creditAccountTransfer, $debitAccountTransfer, $palletsNumber, $creditAccountTransfer2, $debitAccountTransfer2, $palletsNumber2, $creditAccountTransfer3, $debitAccountTransfer3, $palletsNumber3, $normalTransferAssociated, $notExchanging)
    {
        if ($type == 'Deposit-Withdrawal') {
            Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading->atrnr, 'notExchange' => $notExchanging]);
            Palletstransfer::create(['date' => $date, 'type' => 'Withdrawal-Deposit', 'details' => $details, 'creditAccount' => $creditAccountTransfer2, 'debitAccount' => $debitAccountTransfer2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading->atrnr, 'notExchange' => $notExchanging]);
            if (isset($palletsNumber3) && isset($creditAccountTransfer3) && isset($debitAccountTransfer3)) {
                if ($palletsNumber < $loading->anz && $palletsNumber2 == $loading->anz) {
                    $associatedId = Palletstransfer::where('date', $date)->where('type', $type)->where('details', $details)->where('creditAccount', $creditAccountTransfer)->where('debitAccount', $debitAccountTransfer)->where('palletsNumber', $palletsNumber)->where('loading_atrnr', $loading->atrnr)->first()->id;
                    Palletstransfer::create(['date' => $date, 'type' => 'Debt', 'details' => $details, 'creditAccount' => $creditAccountTransfer3, 'debitAccount' => $debitAccountTransfer3, 'palletsNumber' => $palletsNumber3, 'loading_atrnr' => $loading->atrnr, 'normalTransferAssociated' => $associatedId, 'notExchange' => $notExchanging]);
                } elseif ($palletsNumber == $loading->anz && $palletsNumber2 < $loading->anz) {
                    $associatedId = Palletstransfer::where('date', $date)->where('type', 'Withdrawal-Deposit')->where('details', $details)->where('creditAccount', $creditAccountTransfer2)->where('debitAccount', $debitAccountTransfer2)->where('palletsNumber', $palletsNumber2)->where('loading_atrnr', $loading->atrnr)->first()->id;
                    Palletstransfer::create(['date' => $date, 'type' => 'Debt', 'details' => $details, 'creditAccount' => $creditAccountTransfer3, 'debitAccount' => $debitAccountTransfer3, 'palletsNumber' => $palletsNumber3, 'loading_atrnr' => $loading->atrnr, 'normalTransferAssociated' => $associatedId, 'notExchange' => $notExchanging]);
                } elseif ($palletsNumber == $loading->anz && $palletsNumber2 > $loading->anz) {
                    $associatedId = Palletstransfer::where('date', $date)->where('type', 'Withdrawal-Deposit')->where('details', $details)->where('creditAccount', $creditAccountTransfer2)->where('debitAccount', $debitAccountTransfer2)->where('palletsNumber', $palletsNumber2)->where('loading_atrnr', $loading->atrnr)->first()->id;
                    Palletstransfer::create(['date' => $date, 'type' => 'Debt', 'details' => $details, 'creditAccount' => $creditAccountTransfer3, 'debitAccount' => $debitAccountTransfer3, 'palletsNumber' => $palletsNumber3, 'loading_atrnr' => $loading->atrnr, 'normalTransferAssociated' => $associatedId, 'notExchange' => $notExchanging]);
                } elseif ($palletsNumber > $loading->anz && $palletsNumber2 == $loading->anz) {
                    $associatedId = Palletstransfer::where('date', $date)->where('type', $type)->where('details', $details)->where('creditAccount', $creditAccountTransfer)->where('debitAccount', $debitAccountTransfer)->where('palletsNumber', $palletsNumber)->where('loading_atrnr', $loading->atrnr)->first()->id;
                    Palletstransfer::create(['date' => $date, 'type' => 'Debt', 'details' => $details, 'creditAccount' => $creditAccountTransfer3, 'debitAccount' => $debitAccountTransfer3, 'palletsNumber' => $palletsNumber3, 'loading_atrnr' => $loading->atrnr, 'normalTransferAssociated' => $associatedId, 'notExchange' => $notExchanging]);
                } elseif (($palletsNumber < $loading->anz && $palletsNumber2 > $loading->anz) || ($palletsNumber > $loading->anz && $palletsNumber2 < $loading->anz) || ($palletsNumber > $loading->anz && $palletsNumber2 > $loading->anz) || ($palletsNumber < $loading->anz && $palletsNumber2 < $loading->anz)) {
                    $associatedId1 = Palletstransfer::where('date', $date)->where('type', $type)->where('details', $details)->where('creditAccount', $creditAccountTransfer)->where('debitAccount', $debitAccountTransfer)->where('palletsNumber', $palletsNumber)->where('loading_atrnr', $loading->atrnr)->first()->id;
                    $associatedId2 = Palletstransfer::where('date', $date)->where('type', 'Withdrawal-Deposit')->where('details', $details)->where('creditAccount', $creditAccountTransfer2)->where('debitAccount', $debitAccountTransfer2)->where('palletsNumber', $palletsNumber2)->where('loading_atrnr', $loading->atrnr)->first()->id;
                    Palletstransfer::create(['date' => $date, 'type' => 'Debt', 'details' => $details, 'creditAccount' => $creditAccountTransfer3, 'debitAccount' => $debitAccountTransfer3, 'palletsNumber' => $palletsNumber3, 'loading_atrnr' => $loading->atrnr, 'normalTransferAssociated' => $associatedId1 . '-' . $associatedId2, 'notExchange' => $notExchanging]);
                }
            }
        } elseif ($type == 'Deposit_Only') {
            Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading->atrnr, 'notExchange' => $notExchanging]);
            if (isset($palletsNumber3) && isset($creditAccountTransfer3) && isset($debitAccountTransfer3)) {
                $associatedId = Palletstransfer::where('date', $date)->where('type', $type)->where('details', $details)->where('creditAccount', $creditAccountTransfer)->where('debitAccount', $debitAccountTransfer)->where('palletsNumber', $palletsNumber)->where('loading_atrnr', $loading->atrnr)->first()->id;
                Palletstransfer::create(['date' => $date, 'type' => 'Debt', 'details' => $details, 'creditAccount' => $creditAccountTransfer3, 'debitAccount' => $debitAccountTransfer3, 'palletsNumber' => $palletsNumber3, 'loading_atrnr' => $loading->atrnr, 'normalTransferAssociated' => $associatedId, 'notExchange' => $notExchanging]);
            }
        } elseif ($type == 'Withdrawal_Only') {
            Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading->atrnr, 'notExchange' => $notExchanging]);
            if (isset($palletsNumber3) && isset($creditAccountTransfer3) && isset($debitAccountTransfer3)) {
                $associatedId = Palletstransfer::where('date', $date)->where('type', $type)->where('details', $details)->where('creditAccount', $creditAccountTransfer)->where('debitAccount', $debitAccountTransfer)->where('palletsNumber', $palletsNumber)->where('loading_atrnr', $loading->atrnr)->first()->id;
                Palletstransfer::create(['date' => $date, 'type' => 'Debt', 'details' => $details, 'creditAccount' => $creditAccountTransfer3, 'debitAccount' => $debitAccountTransfer3, 'palletsNumber' => $palletsNumber3, 'loading_atrnr' => $loading->atrnr, 'normalTransferAssociated' => $associatedId, 'notExchange' => $notExchanging]);
            }
        } elseif ($type == 'Purchase-Sale') {
            Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading->atrnr, 'normalTransferAssociated' => $normalTransferAssociated[0], 'notExchange' => $notExchanging]);
            Palletstransfer::create(['date' => $date, 'type' => 'Sale-Purchase', 'details' => $details, 'creditAccount' => $creditAccountTransfer2, 'debitAccount' => $debitAccountTransfer2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading->atrnr, 'normalTransferAssociated' => $normalTransferAssociated[0], 'notExchange' => $notExchanging]);
        } else {
            Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading->atrnr, 'normalTransferAssociated' => $normalTransferAssociated[0] . '-' . $normalTransferAssociated[1], 'notExchange' => $notExchanging]);
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
                Truck::where('id', explode('-', $creditAccount)[1])->update(['palletsDebt' => $actualCreditPalletsNumber + $palletsNumber]);
                $palletsaccount_name = Truck::where('id', explode('-', $creditAccount)[1])->value('palletsaccount_name');
                Palletsaccount::where('nickname', $palletsaccount_name)->update(['palletsDebt' => Truck::where('name', $palletsaccount_name)->sum('palletsDebt')]);
            } elseif (strpos($creditAccount, '-') == 7 && explode('-', $creditAccount)[0] == 'account') {
                //others accounts (network, other)
                Palletsaccount::where('id', explode('-', $creditAccount)[1])->update(['palletsDebt' => $actualCreditPalletsNumber + $palletsNumber]);
            }

            if (strpos($debitAccount, '-') == 5 && explode('-', $debitAccount)[0] == 'truck') {
                //truck account
                Truck::where('id', explode('-', $debitAccount)[1])->update(['palletsDebt' => $actualDebitPalletsNumber - $palletsNumber]);
                $palletsaccount_name = Truck::where('id', explode('-', $debitAccount)[1])->value('palletsaccount_name');
                Palletsaccount::where('nickname', $palletsaccount_name)->update(['palletsDebt' => Truck::where('name', $palletsaccount_name)->sum('palletsDebt')]);
            } elseif (strpos($debitAccount, '-') == 7 && explode('-', $debitAccount)[0] == 'account') {
                //others accounts (network, other)
                Palletsaccount::where('id', explode('-', $debitAccount)[1])->update(['palletsDebt' => $actualDebitPalletsNumber - $palletsNumber]);
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
     * @param $normalTransferAssociated
     */
    public function validateAddPalletsTransfer($loading, $type, $date, $details, $creditAccount, $debitAccount, $creditAccount2, $debitAccount2, $palletsNumber, $palletsNumber2, $creditAccount3, $debitAccount3, $palletsNumber3, $normalTransferAssociated, $notExchanging)
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
//        dd($debitAccount3, $creditAccount3,$debitAccountTransfer3,$creditAccountTransfer3);
        $this->createTransfer($loading, $type, $date, $details, $creditAccountTransfer, $debitAccountTransfer, $palletsNumber, $creditAccountTransfer2, $debitAccountTransfer2, $palletsNumber2, $creditAccountTransfer3, $debitAccountTransfer3, $palletsNumber3, $normalTransferAssociated, $notExchanging);
        $this->updatePalletsAccount($type, $creditAccount, $debitAccount, $actualTheoricalCreditPalletsNumber, $actualTheoricalDebitPalletsNumber, $palletsNumber);
        if (isset($creditAccount2) && isset($debitAccount2)) {
            $actualTheoricalCreditPalletsNumber2 = session('palletsNumberCreditAccount2');
            $actualTheoricalDebitPalletsNumber2 = session('palletsNumberDebitAccount2');
            $this->updatePalletsAccount($type, $creditAccount2, $debitAccount2, $actualTheoricalCreditPalletsNumber2, $actualTheoricalDebitPalletsNumber2, $palletsNumber2);
        }
        if (isset($creditAccount3) && isset($debitAccount3)) {
            $actualDebtCreditPalletsNumber3 = session('palletsNumberDebtCreditAccount3');
            $actualDebtDebitPalletsNumber3 = session('palletsNumberDebtDebitAccount3');
            $this->updatePalletsAccount($type, $creditAccount3, $debitAccount3, $actualDebtCreditPalletsNumber3, $actualDebtDebitPalletsNumber3, $palletsNumber3);
        }
//        if ($type == 'Deposit_Only') {
//            session()->flash('sumTransfersDepositOnly', Palletstransfer::where('type', 'Deposit_Only')->sum('palletsNumber') + $palletsNumber);
//            session()->flash('sumTransfersWithdrawalOnly', Palletstransfer::where('type', 'Withdrawal_Only')->sum('palletsNumber'));
//        } elseif ($type == 'Withdrawal_Only') {
//            session()->flash('sumTransfersDepositOnly', Palletstransfer::where('type', 'Deposit_Only')->sum('palletsNumber'));
//            session()->flash('sumTransfersWithdrawalOnly', Palletstransfer::where('type', 'Withdrawal_Only')->sum('palletsNumber') + $palletsNumber);
//        }
        $this->state($loading, Palletstransfer::where('loading_atrnr', $loading->atrnr)->get());
        session()->flash('messageAddPalletstransfer', 'Successfully added new pallets transfer(s)');
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
     * @param $normalTransferAssociated
     * @return string
     */
    public function updateTransfer($transfer, $validate, $details, $loading, $actualDocuments)
    {
        Palletstransfer::where('id', $transfer->id)->update(['details' => $details]);

        if (empty($actualDocuments)) {
            $view = 'error';
        } else {
            $partsCreditAccount = explode('-', $transfer->creditAccount);
            $typeCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 2];
            $idCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 1];
            session()->flash('idCreditAccount', $idCreditAccount);
            session()->flash('typeCreditAccount', $typeCreditAccount);
            session()->flash('partsCreditAccount', $partsCreditAccount);
            if ($typeCreditAccount == 'truck') {
                session()->flash('actualPalletsNumberCreditAccount', Truck::where('id', $idCreditAccount)->first()->theoricalNumberPallets);
            } elseif ($typeCreditAccount == 'account') {
                session()->flash('actualPalletsNumberCreditAccount', Palletsaccount::where('id', $idCreditAccount)->first()->theoricalNumberPallets);
            }
            $partsDebitAccount = explode('-', $transfer->debitAccount);
            $typeDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 2];
            $idDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 1];
            session()->flash('idDebitAccount', $idDebitAccount);
            session()->flash('typeDebitAccount', $typeDebitAccount);
            session()->flash('partsDebitAccount', $partsDebitAccount);
            if ($typeDebitAccount == 'truck') {
                session()->flash('actualPalletsNumberDebitAccount', Truck::where('id', $idDebitAccount)->first()->theoricalNumberPallets);
            } elseif ($typeDebitAccount == 'account') {
                session()->flash('actualPalletsNumberDebitAccount', Palletsaccount::where('id', $idDebitAccount)->first()->theoricalNumberPallets);
            }

            session()->put('actualState', $transfer->state);
            session()->put('actualValidate', $transfer->validate);
            if ($validate == 'true') {
                Palletstransfer::where('id', $transfer->id)->update(['validate' => true, 'state' => 'Complete Validated']);
            } elseif ($validate == 'false') {
                Palletstransfer::where('id', $transfer->id)->update(['validate' => false, 'state' => 'Complete']);
            }
            $view = 'ok';
        }
        $this->state($loading, Palletstransfer::where('loading_atrnr', $loading->atrnr)->get());
        return $view;
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
                Palletsaccount::where('nickname', $palletsaccount_name)->update(['realNumberPallets' => Palletsaccount::where('nickname', $palletsaccount_name)->sum('realNumberPallets')]);
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
                Palletsaccount::where('nickname', $palletsaccount_name)->update(['realNumberPallets' => Palletsaccount::where('nickname', $palletsaccount_name)->sum('realNumberPallets')]);
            } elseif ($typeDebitAccount == 'account') {
                $actualRealPalletsNumberDebitAccount = Palletsaccount::where('id', $idDebitAccount)->first()->realNumberPallets;
                Palletsaccount::where('id', $idDebitAccount)->update(['realNumberPallets' => $actualRealPalletsNumberDebitAccount + $transfer->palletsNumber]);
            }
        }
    }

    /**
     * update only the information related to the transfer
     * @param $transfer
     * @param $filesNames
     * @param $okSubmitPalletsModal
     * @param $loading
     * @return $view
     */
    public function validateUpdateTransfer($transfer)
    {
        $actualState = session('actualState');
        if ($transfer->state == 'Complete' && $actualState == 'Complete Validated') {
            $partsCreditAccount = explode('-', $transfer->creditAccount);
            $typeCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 2];
            $idCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 1];
            if ($typeCreditAccount == 'truck') {
                $realPalletsNumberCreditAccount = Truck::where('id', $idCreditAccount)->first()->realNumberPallets;
                Truck::where('id', $idCreditAccount)->update(['realNumberPallets' => $realPalletsNumberCreditAccount - $transfer->palletsNumber]);
                $palletsaccount_name = Truck::where('id', $idCreditAccount)->value('palletsaccount_name');
                Palletsaccount::where('nickname', $palletsaccount_name)->update(['realNumberPallets' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('realNumberPallets')]);
            } elseif ($typeCreditAccount == 'account') {
                $realPalletsNumberCreditAccount = Palletsaccount::where('id', $idCreditAccount)->first()->realNumberPallets;
                Palletsaccount::where('id', $idCreditAccount)->update(['realNumberPallets' => $realPalletsNumberCreditAccount - $transfer->palletsNumber]);
            }

            $partsDebitAccount = explode('-', $transfer->debitAccount);
            $typeDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 2];
            $idDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 1];
            if ($typeDebitAccount == 'truck') {
                $realPalletsNumberDebitAccount = Truck::where('id', $idDebitAccount)->first()->realNumberPallets;
                Truck::where('id', $idDebitAccount)->update(['realNumberPallets' => $realPalletsNumberDebitAccount + $transfer->palletsNumber]);
                $palletsaccount_name = Truck::where('id', $idDebitAccount)->value('palletsaccount_name');
                Palletsaccount::where('nickname', $palletsaccount_name)->update(['realNumberPallets' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('realNumberPallets')]);
            } elseif ($typeDebitAccount == 'account') {
                $realPalletsNumberDebitAccount = Palletsaccount::where('id', $idDebitAccount)->first()->realNumberPallets;
                Palletsaccount::where('id', $idDebitAccount)->update(['realNumberPallets' => $realPalletsNumberDebitAccount + $transfer->palletsNumber]);
            }
            session()->pull('actualState');
            session()->pull('actualValidate');
            session()->flash('messageSubmitPalletstransfer', 'Successfully updated and pallets transfer');
            $view = 'back';
        } elseif ($transfer->state == 'Complete' && $actualState == 'Complete') {
            session()->pull('actualState');
            session()->pull('actualValidate');
            $view = 'back';
        } elseif ($transfer->state == 'Complete Validated' && $actualState == 'Complete') {
            $partsCreditAccount = explode('-', $transfer->creditAccount);
            $typeCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 2];
            $idCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 1];
            session()->flash('idCreditAccount', $idCreditAccount);
            session()->flash('typeCreditAccount', $typeCreditAccount);
            session()->flash('partsCreditAccount', $partsCreditAccount);
            if ($typeCreditAccount == 'truck') {
                session()->flash('actualRealPalletsNumberCreditAccount', Truck::where('id', $idCreditAccount)->first()->realNumberPallets);
            } elseif ($typeCreditAccount == 'account') {
                session()->flash('actualRealPalletsNumberCreditAccount', Palletsaccount::where('id', $idCreditAccount)->first()->realNumberPallets);
            }
            $partsDebitAccount = explode('-', $transfer->debitAccount);
            $typeDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 2];
            $idDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 1];
            session()->flash('idDebitAccount', $idDebitAccount);
            session()->flash('typeDebitAccount', $typeDebitAccount);
            session()->flash('partsDebitAccount', $partsDebitAccount);
            if ($typeDebitAccount == 'truck') {
                session()->flash('actualRealPalletsNumberDebitAccount', Truck::where('id', $idDebitAccount)->first()->realNumberPallets);
            } elseif ($typeDebitAccount == 'account') {
                session()->flash('actualRealPalletsNumberDebitAccount', Palletsaccount::where('id', $idDebitAccount)->first()->realNumberPallets);
            }
            session()->flash('palletsNumber', $transfer->palletsNumber);
            session()->flash('messageSubmitPalletstransfer', 'Successfully updated and pallets transfer');
            $view = 'ok';
        } elseif ($transfer->state == 'Complete Validated' && $actualState == 'Complete Validated') {
            $partsCreditAccount = explode('-', $transfer->creditAccount);
            $typeCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 2];
            $idCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 1];
            session()->flash('idCreditAccount', $idCreditAccount);
            session()->flash('typeCreditAccount', $typeCreditAccount);
            session()->flash('partsCreditAccount', $partsCreditAccount);
            if ($typeCreditAccount == 'truck') {
                session()->flash('actualRealPalletsNumberCreditAccount', Truck::where('id', $idCreditAccount)->first()->realNumberPallets);
            } elseif ($typeCreditAccount == 'account') {
                session()->flash('actualRealPalletsNumberCreditAccount', Palletsaccount::where('id', $idCreditAccount)->first()->realNumberPallets);
            }
            $partsDebitAccount = explode('-', $transfer->debitAccount);
            $typeDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 2];
            $idDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 1];
            session()->flash('idDebitAccount', $idDebitAccount);
            session()->flash('typeDebitAccount', $typeDebitAccount);
            session()->flash('partsDebitAccount', $partsDebitAccount);
            if ($typeDebitAccount == 'truck') {
                session()->flash('actualRealPalletsNumberDebitAccount', Truck::where('id', $idDebitAccount)->first()->realNumberPallets);
            } elseif ($typeDebitAccount == 'account') {
                session()->flash('actualRealPalletsNumberDebitAccount', Palletsaccount::where('id', $idDebitAccount)->first()->realNumberPallets);
            }
            session()->flash('palletsNumber', $transfer->palletsNumber);
            session()->flash('notUpdateRealPalletsNumber', 'notUpdateRealPalletsNumber');
            $view = 'ok';
        }
        return $view;
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
//        $actualNormalTransferAssociated = session('actualNormalTransferAssociated');
//
//        if (isset($actualDebitAccount)) {
//            Palletstransfer::where('id', $closeSubmitPalletsModal)->update(['debitAccount' => $actualDebitAccount]);
//        }
//        if (isset($actualCreditAccount)) {
//            Palletstransfer::where('id', $closeSubmitPalletsModal)->update(['creditAccount' => $actualCreditAccount]);
//        }
//        Palletstransfer::where('id', $closeSubmitPalletsModal)->update(['validate' => $actualValidate, 'type' => $actualType, 'details' => $actualDetails, 'palletsNumber' => $actualPalletsNumber, 'date' => $actualDate, 'normalTransferAssociated' => $actualNormalTransferAssociated]);
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
//        session()->pull('actualNormalTransferAssociated');
//    }

    /**
     * cancelling the update transfer -> go back to the initial state
     * @param $closeSubmitPalletsModal
     * @param $loading
     */
    public function refuseValidateUpdateTransfer($closeSubmitPalletsModal, $loading)
    {
        $actualValidate = session('actualValidate');
        $actualState = session('actualState');
        Palletstransfer::where('id', $closeSubmitPalletsModal)->update(['validate' => $actualValidate, 'state' => $actualState]);

        $this->state($loading, Palletstransfer::where('loading_atrnr', $loading->atrnr)->get());
        session()->pull('actualState');
        session()->pull('actualValidate');
    }

    /**
     * validate the transfer once it's complete
     * @param $okSubmitPalletsValidateModal
     * @param $loading
     * @param $listPalletstransfers
     */
    public function validateCompleteUpdateTransfer($okSubmitPalletsValidateModal, $loading, $listPalletstransfers)
    {
        $transfer = Palletstransfer::where('id', $okSubmitPalletsValidateModal)->first();

        $actualState = session('actualState');
        if ($transfer->state == 'Complete Validated' && $actualState == 'Complete') {
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
        $this->state($loading, $listPalletstransfers);
        session()->flash('messageUpdateValidatePalletstransfer', 'VALIDATE ! Successfully updated and validated pallets transfer');
        session()->pull('actualValidate');
        session()->pull('actualState');
    }

    /**
     * delete the transfer from the database
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function deleteAllTransfers($loading, $listPalletstransfers)
    {
        foreach ($listPalletstransfers as $transfer) {
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
        $idErrorDW_NotSameNumber = Error::where('name', 'Donly-Wonly_notSameNumber')->first()->id;
        $idErrorCorrecting_NotCompleteNormal = Error::where('name', 'Correcting_notCompleteNormal')->first()->id;
        $idErrorCorrecting_NotEnoughTransfers = Error::where('name', 'Correcting_notEnoughTransfers')->first()->id;
        $idErrorSPPS_NotEnoughTransfers = Error::where('name', 'SP-PS_notEnoughTransfers')->first()->id;

        //remove all errors on transfers
        foreach ($listPalletstransfers as $transfer) {
            $transfer->errors()->detach([$idErrorWDDW_atLeastOne, $idErrorCorrecting_NotCompleteNormal, $idErrorCorrecting_NotEnoughTransfers, $idErrorDW_NotSameNumber, $idErrorWDDW_NotNumberLoadingOrder, $idErrorSPPS_NotEnoughTransfers]);
        }

        //we need to distinct every loading place / unloading place for DW and WD transfers
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

            //check if for DW transfers there is at least 1 WD transfer and inversely
            if (count($listTransfersWD_acc) == 0) {
                foreach ($listTransfersWD_acc as $transferWD) {
                    $transferWD->errors()->attach($idErrorWDDW_atLeastOne);
                }
//            } else {
//                foreach ($listTransfersWD_acc as $transferWD) {
//                    $transferWD->errors()->detach($idErrorWDDW_atLeastOne);
//                }
            }
            if (count($listTransfersDW_acc) == 0) {
                foreach ($listTransfersDW_acc as $transferDW) {
                    $transferDW->errors()->attach($idErrorWDDW_atLeastOne);
                }
//            } else {
//                foreach ($listTransfersDW_acc as $transferDW) {
//                    $transferDW->errors()->detach($idErrorWDDW_atLeastOne);
//                }
            }

            $sumTransferDW_acc = Palletstransfer::where('type', 'Deposit-Withdrawal')->where('loading_atrnr', $loading->atrnr)->where('creditAccount', $account)->sum('palletsNumber');
            $sumTransferWD_acc = Palletstransfer::where('type', 'Withdrawal-Deposit')->where('loading_atrnr', $loading->atrnr)->where('debitAccount', $account)->sum('palletsNumber');

            //check if sum(all transfers DW on a special account) = anz loading order
            if ($sumTransferDW_acc <> $loading->anz) {
                foreach ($listTransfersDW_acc as $transferDW) {
                    $transferDW->errors()->attach($idErrorWDDW_NotNumberLoadingOrder);
                }
//            } else {
//                foreach ($listTransfersDW_acc as $transferDW) {
//                    $transferDW->errors()->detach($idErrorWDDW_NotNumberLoadingOrder);
//                }
            }

            //check if sum(all transfers WD) = anz loading order
            if ($sumTransferWD_acc <> $loading->anz) {
                foreach ($listTransfersWD_acc as $transferWD) {
                    $transferWD->errors()->attach($idErrorWDDW_NotNumberLoadingOrder);
                }
//            } else {
//                foreach ($listTransfersWD_acc as $transferWD) {
//                    $transferWD->errors()->detach($idErrorWDDW_NotNumberLoadingOrder);
//                }
            }
        }

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
//        } else {
//            foreach ($listTransfersD as $transferD) {
//                $transferD->errors()->detach($idErrorDW_NotSameNumber);
//            }
//            foreach ($listTransfersW as $transferW) {
//                $transferW->errors()->detach($idErrorDW_NotSameNumber);
//            }
        }

        //check if there is enough correcting transfers (even number)
        $listTransfersDebt = Palletstransfer::where('type', 'Debt')->where('loading_atrnr', $loading->atrnr)->get();
        $listTransfersSP = Palletstransfer::where('type', 'Purchase-Sale')->where('loading_atrnr', $loading->atrnr)->get();
        $listTransfersPS = Palletstransfer::where('type', 'Sale-Purchase')->where('loading_atrnr', $loading->atrnr)->get();

        if ((count($listTransfersSP) + count($listTransfersPS) + count($listTransfersDebt)) % 2 <> 0) {
            foreach ($listTransfersSP as $transferSP) {
                $transferSP->errors()->attach($idErrorCorrecting_NotEnoughTransfers);
            }
            foreach ($listTransfersPS as $transferPS) {
                $transferPS->errors()->attach($idErrorCorrecting_NotEnoughTransfers);
            }
            foreach ($listTransfersDebt as $transferDebt) {
                $transferDebt->errors()->attach($idErrorCorrecting_NotEnoughTransfers);
            }
        } else {
            foreach ($listTransfersSP as $transferSP) {
                $transferSP->errors()->detach($idErrorCorrecting_NotEnoughTransfers);
            }
            foreach ($listTransfersPS as $transferPS) {
                $transferPS->errors()->detach($idErrorCorrecting_NotEnoughTransfers);
            }
            foreach ($listTransfersDebt as $transferDebt) {
                $transferDebt->errors()->detach($idErrorCorrecting_NotEnoughTransfers);
            }
        }

        //check every normal transfer
        $listTransfersNormal = Palletstransfer::where(function ($q) {
            $q->where('type', 'Deposit-Withdrawal')->orWhere('type', 'Withdrawal-Deposit')->orWhere('type', 'Withdrawal_Only')->orWhere('type', 'Deposit_Only');
        })->where('loading_atrnr', $loading->atrnr)->get();

        foreach ($listTransfersNormal as $transferNormal) {
            $errorsTransfer = PalletstransfersController::actualErrors($transferNormal);
//            dump('errors', PalletstransfersController::actualErrors($transferNormal));
            if (count($errorsTransfer) <> 0) {
                foreach ($errorsTransfer as $errorTransfer) {
                    //check if the transfer has been corrected
                    $correctingTransfersAssociated = Palletstransfer::where('normalTransferAssociated', $transferNormal->id)->where('loading_atrnr', $loading->atrnr)->get();
//                   dump('corr trans asso', $correctingTransfersAssociated);
                    if (!$correctingTransfersAssociated->isEmpty()) {
                        //check if correcting transfers are completing these normal transfers
                        $listTransfersPSAssociated = Palletstransfer::where('normalTransferAssociated', $transferNormal->id)->where('type', 'Purchase-Sale')->where('loading_atrnr', $loading->atrnr)->get();
                        $listTransfersSPAssociated = Palletstransfer::where('normalTransferAssociated', $transferNormal->id)->where('type', 'Sale-Purchase')->where('loading_atrnr', $loading->atrnr)->get();
                        $listTransfersDebtAssociated = Palletstransfer::where('normalTransferAssociated', $transferNormal->id)->where('type', 'Debt')->where('loading_atrnr', $loading->atrnr)->get();
                        $sumTransfersDebtAssociated = Palletstransfer::where('normalTransferAssociated', $transferNormal->id)->where('type', 'Debt')->where('loading_atrnr', $loading->atrnr)->sum('palletsNumber');
                        $sumTransfersSPAssociated = Palletstransfer::where('normalTransferAssociated', $transferNormal->id)->where('type', 'Sale-Purchase')->where('loading_atrnr', $loading->atrnr)->sum('palletsNumber');
                        $sumTransfersPSAssociated = Palletstransfer::where('normalTransferAssociated', $transferNormal->id)->where('type', 'Purchase-Sale')->where('loading_atrnr', $loading->atrnr)->sum('palletsNumber');

                        if (count($listTransfersSPAssociated) <> count($listTransfersPSAssociated)) {
                            foreach ($listTransfersSPAssociated as $transferSPA) {
                                $transferSPA->errors()->attach($idErrorSPPS_NotEnoughTransfers);
                            }
                            foreach ($listTransfersPSAssociated as $transferPSA) {
                                $transferPSA->errors()->attach($idErrorSPPS_NotEnoughTransfers);
                            }
//        } else {
//            foreach ($listTransfersSP as $transferSP) {
//                $transferSP->errors()->detach($idErrorSPPS_NotEnoughTransfers);
//            }
//            foreach ($listTransfersPS as $transferPS) {
//                $transferPS->errors()->detach($idErrorSPPS_NotEnoughTransfers);
//            }
                        }

                        if ($errorTransfer->name == 'DW-WD_notNumberLoadingOrder' && ($transferNormal->type == 'Deposit-Withdrawal' || $transferNormal->type == 'Withdrawal-Deposit')) {
                            //check if NExchange or not
                            if ($transferNormal->notExchange == 1) {
                                //if not exchange : check if sum debt + p numb = anz
                                //if not : attach error on every debt transfer : not complete normal transfer
                                //if yes : detach error on normal transfer : notNumberLoadingOrder
                                if ($transferNormal->palletsNumber <= $loading->anz) {
                                    if ($sumTransfersDebtAssociated + $transferNormal->palletsNumber <> $loading->anz) {
                                        foreach ($listTransfersDebtAssociated as $transferDebtAssociated) {
                                            $transferDebtAssociated->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                                        }
                                    } else {
//                                        foreach ($listTransfersDebtAssociated as $transferDebtAssociated) {
//                                            $transferDebtAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
//                                        }
                                        $transferNormal->errors()->detach($idErrorWDDW_NotNumberLoadingOrder);
                                    }
                                } else {
                                    if ($transferNormal->palletsNumber - $sumTransfersDebtAssociated <> $loading->anz) {
                                        foreach ($listTransfersDebtAssociated as $transferDebtAssociated) {
                                            $transferDebtAssociated->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                                        }
                                    } else {
//                                        foreach ($listTransfersDebtAssociated as $transferDebtAssociated) {
//                                            $transferDebtAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
//                                        }
                                        $transferNormal->errors()->detach($idErrorWDDW_NotNumberLoadingOrder);
                                    }
                                }
                            } else {
                                //if exchange : check sum PS + p numb = anz and sum SP + p numb = anz
                                if ($transferNormal->palletsNumber <= $loading->anz) {
                                    if ($sumTransfersSPAssociated + $transferNormal->palletsNumber <> $loading->anz) {
                                        foreach ($listTransfersSPAssociated as $transferSPAssociated) {
                                            $transferSPAssociated->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                                        }
//                                    } else {
//                                        foreach ($listTransfersSPAssociated as $transferSPAssociated) {
//                                            $transferSPAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
//                                        }
                                    }
                                    if ($sumTransfersPSAssociated + $transferNormal->palletsNumber <> $loading->anz) {
                                        foreach ($listTransfersPSAssociated as $transferPSAssociated) {
                                            $transferPSAssociated->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                                        }
//                                    } else {
//                                        foreach ($listTransfersPSAssociated as $transferPSAssociated) {
//                                            $transferPSAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
//                                        }
                                    }
                                    if ($sumTransfersPSAssociated + $transferNormal->palletsNumber == $loading->anz && $sumTransfersSPAssociated + $transferNormal->palletsNumber == $loading->anz) {
                                        $transferNormal->errors()->detach($idErrorWDDW_NotNumberLoadingOrder);
                                    }
                                } else {
                                    if ($transferNormal->palletsNumber - $sumTransfersSPAssociated <> $loading->anz) {
                                        foreach ($listTransfersSPAssociated as $transferSPAssociated) {
                                            $transferSPAssociated->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                                        }
//                                    } else {
//                                        foreach ($listTransfersSPAssociated as $transferSPAssociated) {
//                                            $transferSPAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
//                                        }
                                    }
                                    if ($transferNormal->palletsNumber - $sumTransfersPSAssociated <> $loading->anz) {
                                        foreach ($listTransfersPSAssociated as $transferPSAssociated) {
                                            $transferPSAssociated->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                                        }
//                                    } else {
//                                        foreach ($listTransfersPSAssociated as $transferPSAssociated) {
//                                            $transferPSAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
//                                        }
                                    }
                                    if ($transferNormal->palletsNumber - $sumTransfersSPAssociated == $loading->anz && $transferNormal->palletsNumber - $sumTransfersPSAssociated == $loading->anz) {
                                        $transferNormal->errors()->detach($idErrorWDDW_NotNumberLoadingOrder);
                                    }
                                }
                            }
//dump('check errors PSA and SPA transfers');
//                            foreach ($listTransfersPSAssociated as $transferPSAssociated) {
//                                dump(PalletstransfersController::actualErrors($transferPSAssociated));
//                            }
//                            foreach ($listTransfersSPAssociated as $transferSPAssociated) {
//                                dump(PalletstransfersController::actualErrors($transferSPAssociated));
//                            }

                            //check if debt + PS + p numb = anz or debt + SP + p numb = anz
                            if ($transferNormal->palletsNumber <= $loading->anz) {
                                if ($sumTransfersPSAssociated + $sumTransfersDebtAssociated + $transferNormal->palletsNumber <> $loading->anz) {
                                    foreach ($listTransfersPSAssociated as $transferPSAssociated) {
                                        $transferPSAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                                        $transferPSAssociated->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                                    }
                                    foreach ($listTransfersDebtAssociated as $transferDebtAssociated) {
                                        $transferDebtAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                                        $transferDebtAssociated->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                                    }
//                                } else {
//                                    foreach ($listTransfersPSAssociated as $transferPSAssociated) {
//                                        $transferPSAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
//                                    }
//                                    foreach ($listTransfersDebtAssociated as $transferDebtAssociated) {
//                                        $transferDebtAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
//                                    }
                                }
                                if ($sumTransfersSPAssociated + $sumTransfersDebtAssociated + $transferNormal->palletsNumber <> $loading->anz) {
                                    foreach ($listTransfersSPAssociated as $transferSPAssociated) {
                                        $transferSPAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                                        $transferSPAssociated->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                                    }
                                    foreach ($listTransfersDebtAssociated as $transferDebtAssociated) {
                                        $transferDebtAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                                        $transferDebtAssociated->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                                    }
//                                } else {
//                                    foreach ($listTransfersSPAssociated as $transferSPAssociated) {
//                                        $transferSPAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
//                                    }
//                                    foreach ($listTransfersDebtAssociated as $transferDebtAssociated) {
//                                        $transferDebtAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
//                                    }
                                }
                                if ($sumTransfersSPAssociated + $sumTransfersDebtAssociated + $transferNormal->palletsNumber == $loading->anz || $sumTransfersPSAssociated + $sumTransfersDebtAssociated + $transferNormal->palletsNumber == $loading->anz) {
                                    $transferNormal->errors()->detach($idErrorWDDW_NotNumberLoadingOrder);
                                }
                            } else {
                                if ($transferNormal->palletsNumber - ($sumTransfersPSAssociated + $sumTransfersDebtAssociated) <> $loading->anz) {
                                    foreach ($listTransfersPSAssociated as $transferPSAssociated) {
                                        $transferPSAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                                        $transferPSAssociated->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                                    }
                                    foreach ($listTransfersDebtAssociated as $transferDebtAssociated) {
                                        $transferDebtAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                                        $transferDebtAssociated->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                                    }
//                                } else {
//                                    foreach ($listTransfersPSAssociated as $transferPSAssociated) {
//                                        $transferPSAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
//                                    }
//                                    foreach ($listTransfersDebtAssociated as $transferDebtAssociated) {
//                                        $transferDebtAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
//                                    }
                                }
//                                dump('check errors PSA and SPA transfers 2');
//                                foreach ($listTransfersPSAssociated as $transferPSAssociated) {
//                                    dump(PalletstransfersController::actualErrors($transferPSAssociated));
//                                }
//                                foreach ($listTransfersSPAssociated as $transferSPAssociated) {
//                                    dump(PalletstransfersController::actualErrors($transferSPAssociated));
//                                }
                                if ($transferNormal->palletsNumber - ($sumTransfersSPAssociated + $sumTransfersDebtAssociated) <> $loading->anz) {
                                    foreach ($listTransfersSPAssociated as $transferSPAssociated) {
                                        $transferSPAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                                        $transferSPAssociated->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                                    }
                                    foreach ($listTransfersDebtAssociated as $transferDebtAssociated) {
                                        $transferDebtAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                                        $transferDebtAssociated->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                                    }
//                                } else {
//                                    foreach ($listTransfersSPAssociated as $transferSPAssociated) {
//                                        $transferSPAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
//                                    }
//                                    foreach ($listTransfersDebtAssociated as $transferDebtAssociated) {
//                                        $transferDebtAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
//                                    }
                                }
                                if ($transferNormal->palletsNumber - ($sumTransfersPSAssociated + $sumTransfersDebtAssociated) == $loading->anz || $transferNormal->palletsNumber - ($sumTransfersSPAssociated + $sumTransfersDebtAssociated) == $loading->anz) {
                                    $transferNormal->errors()->detach($idErrorWDDW_NotNumberLoadingOrder);
                                }
                            }
//                            dump('check errors PSA and SPA transfers 3');
//                            foreach ($listTransfersPSAssociated as $transferPSAssociated) {
//                                dump(PalletstransfersController::actualErrors($transferPSAssociated));
//                            }
//                            foreach ($listTransfersSPAssociated as $transferSPAssociated) {
//                                dump(PalletstransfersController::actualErrors($transferSPAssociated));
//                            }

                        } elseif ($errorTransfer->name == 'Donly-Wonly_notSameNumber') {
                            if ($transferNormal->notExchange == 1) {
                                //check debt transfers
                                //if sum debt = p numb : no errors
                                if ($sumTransfersDebtAssociated <> $transferNormal->palletsNumber) {
                                    foreach ($listTransfersDebtAssociated as $transferDebtAssociated) {
                                        $transferDebtAssociated->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                                    }
                                } else {
//                                    foreach ($listTransfersDebtAssociated as $transferDebtAssociated) {
//                                        $transferDebtAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
//                                    }
                                    $transferNormal->errors()->detach($idErrorDW_NotSameNumber);
                                }
                            } else {
                                //check PS and SP transfers
                                //if sum PS = sum SP = p numb : no errors
                                if ($sumTransfersSPAssociated <> $transferNormal->palletsNumber) {
                                    foreach ($listTransfersSPAssociated as $transferSPAssociated) {
                                        $transferSPAssociated->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                                    }
//                                } else {
//                                    foreach ($listTransfersSPAssociated as $transferSPAssociated) {
//                                        $transferSPAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
//                                    }
                                }
                                if ($sumTransfersPSAssociated <> $transferNormal->palletsNumber) {
                                    foreach ($listTransfersPSAssociated as $transferPSAssociated) {
                                        $transferPSAssociated->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                                    }
//                                } else {
//                                    foreach ($listTransfersPSAssociated as $transferPSAssociated) {
//                                        $transferPSAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
//                                    }
                                }
                                if ($sumTransfersPSAssociated == $transferNormal->palletsNumber && $sumTransfersSPAssociated == $transferNormal->palletsNumber) {
                                    $transferNormal->errors()->detach($idErrorDW_NotSameNumber);
                                }

                            }
//                            foreach ($listTransfersPSAssociated as $transferPSAssociated) {
//                                dump($transferPSAssociated->errors());
//                            }
//                            foreach ($listTransfersSPAssociated as $transferSPAssociated) {
//                                dump($transferSPAssociated->errors());
//                            }
//                            dd('stop');

                            //check if debt + PS = p numb or debt + SP = p numb
                            if ($sumTransfersPSAssociated + $sumTransfersDebtAssociated <> $transferNormal->palletsNumber) {
                                foreach ($listTransfersPSAssociated as $transferPSAssociated) {
                                    $transferPSAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                                    $transferPSAssociated->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                                }
                                foreach ($listTransfersDebtAssociated as $transferDebtAssociated) {
                                    $transferDebtAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                                    $transferDebtAssociated->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                                }
//                            } else {
//                                foreach ($listTransfersPSAssociated as $transferPSAssociated) {
//                                    $transferPSAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
//                                }
//                                foreach ($listTransfersDebtAssociated as $transferDebtAssociated) {
//                                    $transferDebtAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
//                                }
                            }
                            if ($sumTransfersSPAssociated + $sumTransfersDebtAssociated <> $transferNormal->palletsNumber) {
                                foreach ($listTransfersSPAssociated as $transferSPAssociated) {
                                    $transferSPAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                                    $transferSPAssociated->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                                }
                                foreach ($listTransfersDebtAssociated as $transferDebtAssociated) {
                                    $transferDebtAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                                    $transferDebtAssociated->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                                }
//                            } else {
//                                foreach ($listTransfersSPAssociated as $transferSPAssociated) {
//                                    $transferSPAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
//                                }
//                                foreach ($listTransfersDebtAssociated as $transferDebtAssociated) {
//                                    $transferDebtAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
//                                }
                            }
                            if ($sumTransfersSPAssociated + $sumTransfersDebtAssociated == $transferNormal->palletsNumber || $sumTransfersPSAssociated + $sumTransfersDebtAssociated == $transferNormal->palletsNumber) {
                                $transferNormal->errors()->detach($idErrorDW_NotSameNumber);
                            }
                        }
                    }

                }

            }
        }
//        dd('stop');


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

        $listLoadingsAtrnr = Loading::where('atrnr', 'like', $loadingInitial->atrnr . '-' . '%')->get();
        $max = 0;
        foreach ($listLoadingsAtrnr as $loadingAtrnr) {
            if (substr_count($loadingAtrnr->atrnr, '-') == substr_count($atrnr . '-', '-')) {
                $explode = explode('-', $loadingAtrnr->atrnr);
                if ($explode[count($explode) - 1] > $max) {
                    $max = $explode[count($explode) - 1];
                }
            }
        }
        $max = $max + 1;
        $atrnr = $atrnr . '-' . $max;

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

        return redirect('/loadings/false');
    }
}
