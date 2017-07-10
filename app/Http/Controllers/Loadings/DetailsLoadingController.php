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
            //les fameuses listes utilisees uniquement dans les listes déroulantes des select
            //possibilites : listPalletsAccounts + listTrucksAccounts, listPalletsAccounts, listTrucksPossible
            $listPalletsAccounts = Palletsaccount::where('type', 'Network')->orWhere('type', 'Other')->orderBy('name', 'asc')->get();
            $listTrucksAccounts = Truck::orderBy('name', 'asc')->get();
//list trucks possible associated to the loading
            $listTrucksPossible = Truck::where('name', trim(explode(',', $loading->subfrachter)[0]))->orderBy('name', 'asc')->get();

            $queryPalletstransfers=Palletstransfer::where('loading_atrnr', $atrnr);
            if (request()->has('sortby') && request()->has('order')) {
                $sortby = $request->get('sortby'); // Order by what column?
                $order = $request->get('order'); // Order direction: asc or desc
                $listPalletstransfers = $queryPalletstransfers->orderBy($sortby, $order)->get();
                session()->flash('openPanelPallets', 'openPanelPallets');
            } else {
                $listPalletstransfers = $queryPalletstransfers->orderBy('id', 'asc')->get();
            }
            $listPalletstransfersNormal =$queryPalletstransfers->where(function ($q) {
                $q->where('type', 'Deposit_Only')->orWhere('type', 'Withdrawal_Only')->orWhere('type', 'Withdrawal-Deposit')->orWhere('type', 'Deposit-Withdrawal');
            })->orderBy('id', 'asc')->get();
            $listPalletstransfersCorrecting = $queryPalletstransfers->where(function ($q) {
                $q->where('type', 'Purchase-Sale')->orWhere('type', 'Sale-Purchase')->orWhere('type', 'Other');
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

           ini_set('memory_limit', '-1');
            return view('loadings.detailsLoading', compact('sortby', 'order', 'loading', 'disp', 'atrnr1', 'atrnr2', 'listPalletsAccounts', 'listTrucksAccounts', 'listTrucksPossible', 'listPalletstransfers', 'listPalletstransfersNormal', 'listPalletstransfersCorrecting', 'theoricalNumberPalletsTruck', 'realNumberPalletsTruck'
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
        //update only the panel information
        $update = Input::get('update');
        //show the form to add a transfer
        $addTransferForm = Input::get('addTransferForm');
        //add a transfer then redirect with a pop up modal to validate the adding
        $addPalletstransfer = Input::get('addPalletstransfer');
        //validate the adding
        $okSubmitAddModal = Input::get('okSubmitAddModal');
        //cancel the adding
        $closeSubmitAddModal = Input::get('closeSubmitAddModal');
        //upload a document for a transfer
        $uploadDocument = Input::get('upload');
        //delete a transfer
        $delete = Input::get('delete');
        //delete a document of a transfer
        $deleteDocument = Input::get('deleteDocument');
        // update a transfer then redirect with a pop up modal to validate the update
        $submitPallets = Input::get('submitPallets');
        // cancel the update
        $closeSubmitPalletsModal = Input::get('closeSubmitPalletsModal');
        // cancel the update
        $closeSubmitValidatePalletsModal = Input::get('closeSubmitValidatePalletsModal');
        //validte the update
        $okSubmitPalletsModal = Input::get('okSubmitPalletsModal');
        //validate the transfer whenall its complete
        $okSubmitPalletsValidateModal = Input::get('okSubmitPalletsValidateModal');
        //show the form to add a transfer to correct an other transfer
        $showAddCorrectingTransfer = Input::get('showAddCorrectingTransfer');

        // get all the pallets account except the carriers accounts that will be get after, truck by truck
        $listPalletsAccounts = Palletsaccount::where('type', 'Network')->orWhere('type', 'Other')->orderBy('name', 'asc')->get();
        $listTrucksAccounts = Truck::orderBy('name', 'asc')->get();
//list trucks possible
        $listTrucksPossible = Truck::where('name', trim(explode(',', $loading->subfrachter)[0]))->orderBy('name', 'asc')->get();

        //looking for the truck associated to this loading
        if ($loading->kennzeichen == '') {
            $truckAssociated = Truck::where('name', trim(explode(',', $loading->subfrachter)[0]))->where('licensePlate', 'OTHER')->first();
        } else {
            $truckAssociated = Truck::where('name', trim(explode(',', $loading->subfrachter)[0]))->where('licensePlate', $loading->kennzeichen)->first();
        }if ($truckAssociated <> null) {
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

        //get all transfers to fulfill the table
        $queryPalletstransfers=Palletstransfer::where('loading_atrnr', $atrnr);
        $listPalletstransfers = $queryPalletstransfers->orderBy('id', 'asc')->get();
        //get only the normal transfers (deposit/withdrawal)
        $listPalletstransfersNormal = $queryPalletstransfers->where(function ($q) {
            $q->where('type', 'Deposit_Only')->orWhere('type', 'Withdrawal_Only')->orWhere('type', 'Withdrawal-Deposit')->orWhere('type', 'Deposit-Withdrawal');
        })->orderBy('id', 'asc')->get();
        //get only the correcting transfers (sale/purchase)
        $listPalletstransfersCorrecting = $queryPalletstransfers->where(function ($q) {
            $q->where('type', 'Purchase-Sale')->orWhere('type', 'Sale-Purchase')->orWhere('type', 'Other');
        })->orderBy('id', 'asc')->get();

        if (isset($update)) {
            $this->updatePanel1($request, $loading->atrnr);
            return redirect()->back();
        } elseif (isset($addTransferForm)) {
            session()->flash('openPanelPallets', 'openPanelPallets');
            ini_set('memory_limit', '-1');
            return view('loadings.DetailsLoading', compact('loading', 'disp', 'listPalletsAccounts', 'listTrucksAccounts', 'listTrucksPossible', 'listPalletstransfers', 'listPalletstransfersNormal', 'listPalletstransfersCorrecting','addTransferForm', 'theoricalNumberPalletsTruck', 'realNumberPalletsTruck'));
        } elseif (isset($addPalletstransfer)) {
            //get data from the form
            $date = Input::get('date');
            $type = Input::get('type');
            $details = Input::get('details');

            if (Input::get('creditAccount1b') <> null) {
                $creditAccount = Input::get('creditAccount1b');
            } elseif (Input::get('creditAccount2b') <> null) {
                $creditAccount = Input::get('creditAccount2b');
            } elseif (Input::get('creditAccount3b') <> null) {
                $creditAccount = Input::get('creditAccount3b');
            } elseif (Input::get('creditAccount4b') <> null) {
                $creditAccount = Input::get('creditAccount4b');
            } else {
                $creditAccount = null;
            }
            if (Input::get('debitAccount1b') <> null) {
                $debitAccount = Input::get('debitAccount1b');
            } elseif (Input::get('debitAccount2b') <> null) {
                $debitAccount = Input::get('debitAccount2b');
            } elseif (Input::get('debitAccount3b') <> null) {
                $debitAccount = Input::get('debitAccount3b');
            } elseif (Input::get('debitAccount4b') <> null) {
                $debitAccount = Input::get('debitAccount4b');
            } else {
                $debitAccount = null;
            }

            $palletsNumber = Input::get('palletsNumber');
            $creditAccount2 = Input::get('creditAccount2');
            $debitAccount2 = Input::get('debitAccount2');
            $normalTransferAssociated = Input::get('normalTransferAssociated');
            if ($type == 'Deposit-Withdrawal' || $type == 'Withdrawal-Deposit') {
                $palletsNumber2 = Input::get('palletsNumber2');
            } elseif ( $type == 'Purchase-Sale') {
                $palletsNumber2 = Input::get('palletsNumber2C');
            } else {
                $palletsNumber2 = null;
            }

            $addTransferForm = $this->addPalletsTransfer($type, $debitAccount, $creditAccount, $debitAccount2, $creditAccount2, $palletsNumber, $palletsNumber2, $normalTransferAssociated);
            ini_set('memory_limit', '-1');
            if ($addTransferForm == 'error') {
                //redirect with error
                if ($type == 'Withdrawal-Deposit' || $type == 'Deposit-Withdrawal' || $type == 'Deposit_Only' || $type == 'Withdrawal_Only') {
                    return view('loadings.DetailsLoading', compact('loading', 'disp', 'date', 'details', 'type', 'creditAccount', 'debitAccount', 'palletsNumber', 'listPalletsAccounts', 'listTrucksAccounts', 'listPalletstransfers', 'listPalletstransfersNormal', 'listPalletstransfersCorrecting', 'addTransferForm', 'theoricalNumberPalletsTruck', 'realNumberPalletsTruck'));
                } elseif ($type == 'Purchase-Sale'|| $type == 'Other') {
                    $showAddCorrectingTransfer = true;
                    return view('loadings.DetailsLoading', compact('loading', 'disp', 'date', 'details', 'type', 'normalTransferAssociated', 'creditAccount', 'debitAccount', 'palletsNumber', 'listPalletsAccounts', 'listTrucksAccounts', 'listTrucksPossible', 'listPalletstransfers', 'listPalletstransfersNormal', 'listPalletstransfersCorrecting', 'showAddCorrectingTransfer', 'theoricalNumberPalletsTruck', 'realNumberPalletsTruck'));
                }
            } else {
                //redirect with modal open to validate the transfer adding
                if ($type == 'Withdrawal-Deposit' || $type == 'Deposit-Withdrawal' || $type == 'Deposit_Only' || $type == 'Withdrawal_Only') {
                    return view('loadings.DetailsLoading', compact('loading', 'disp', 'date', 'details', 'type', 'creditAccount', 'debitAccount', 'palletsNumber', 'creditAccount2', 'debitAccount2', 'palletsNumber2', 'listPalletsAccounts', 'listTrucksAccounts', 'listTrucksPossible', 'listPalletstransfers', 'listPalletstransfersNormal', 'listPalletstransfersCorrecting', 'addPalletstransfer', 'theoricalNumberPalletsTruck', 'realNumberPalletsTruck'));
                } elseif ($type == 'Purchase-Sale'|| $type == 'Other') {
                    $showAddCorrectingTransfer = true;
                    $palletsNumber2C = $palletsNumber2;
                    return view('loadings.DetailsLoading', compact('loading', 'disp', 'date', 'details', 'type', 'normalTransferAssociated', 'creditAccount', 'debitAccount', 'palletsNumber', 'creditAccount2', 'debitAccount2', 'palletsNumber2C', 'listPalletsAccounts', 'listTrucksAccounts', 'listTrucksPossible', 'listPalletstransfers', 'listPalletstransfersNormal', 'listPalletstransfersCorrecting', 'addPalletstransfer', 'showAddCorrectingTransfer', 'theoricalNumberPalletsTruck', 'realNumberPalletsTruck'));
                }
            }
        } elseif (isset($okSubmitAddModal)) {
            //get data from the form
            $date = Input::get('date');
            $type = Input::get('type');
            $details = Input::get('details');

            if (Input::get('creditAccount1b') <> null) {
                $creditAccount = Input::get('creditAccount1b');
            } elseif (Input::get('creditAccount2b') <> null) {
                $creditAccount = Input::get('creditAccount2b');
            } elseif (Input::get('creditAccount3b') <> null) {
                $creditAccount = Input::get('creditAccount3b');
            } elseif (Input::get('creditAccount4b') <> null) {
                $creditAccount = Input::get('creditAccount4b');
            } else {
                $creditAccount = null;
            }
            if (Input::get('debitAccount1b') <> null) {
                $debitAccount = Input::get('debitAccount1b');
            } elseif (Input::get('debitAccount2b') <> null) {
                $debitAccount = Input::get('debitAccount2b');
            } elseif (Input::get('debitAccount3b') <> null) {
                $debitAccount = Input::get('debitAccount3b');
            } elseif (Input::get('debitAccount4b') <> null) {
                $debitAccount = Input::get('debitAccount4b');
            } else {
                $debitAccount = null;
            }

            $palletsNumber = Input::get('palletsNumber');
            $normalTransferAssociated = Input::get('normalTransferAssociated');
            if ($type == 'Deposit-Withdrawal' || $type == 'Withdrawal-Deposit') {
                $creditAccount2 = $debitAccount;
                $debitAccount2 = $creditAccount;
                $palletsNumber2 = Input::get('palletsNumber2');
            } elseif ($type == 'Purchase-Sale') {
                $creditAccount2 = Input::get('creditAccount2');
                $debitAccount2 = Input::get('debitAccount2');
                $palletsNumber2 = Input::get('palletsNumber2C');
            } else {
                $creditAccount2 = null;
                $debitAccount2 = null;
                $palletsNumber2 = null;
            }

            //accept to add the transfer
            $this->validateAddPalletsTransfer($loading, $type, $date, $details, $creditAccount, $debitAccount, $creditAccount2, $debitAccount2, $palletsNumber, $palletsNumber2, $normalTransferAssociated);
            //get all transfers
            $listPalletstransfers = Palletstransfer::where('loading_atrnr', $atrnr)->get();
            $this->state($loading, $listPalletstransfers);
            return redirect()->back();
        } elseif (isset($closeSubmitAddModal)) {
            //refuse to add the transfer
            session()->flash('openPanelPallets', 'openPanelPallets');
            return redirect()->back();
        } elseif (isset($showAddCorrectingTransfer)) {
            //get data to pre fulfill the field in the form
            $transferNormal = Palletstransfer::where('id', $showAddCorrectingTransfer)->first();
            if ($transferNormal->palletsNumber <= $loading->anz) {
                $palletsNumber = $loading->anz - $transferNormal->palletsNumber;
            } else {
                $palletsNumber = $transferNormal->palletsNumber - $loading->anz;
            }
            $palletsNumber2C = $palletsNumber;
            $creditAccountCorr = $transferNormal->creditAccount;
            $debitAccountCorr = $transferNormal->debitAccount;

            session()->flash('openPanelPallets', 'openPanelPallets');
            ini_set('memory_limit', '-1');
            return view('loadings.DetailsLoading', compact('debitAccountCorr', 'creditAccountCorr', 'palletsNumber', 'palletsNumber2C', 'loading', 'disp', 'transferToCorrect', 'listPalletsAccounts', 'listTrucksAccounts', 'listTrucksPossible', 'listPalletstransfers', 'listPalletstransfersNormal', 'listPalletstransfersCorrecting', 'date', 'showAddCorrectingTransfer', 'theoricalNumberPalletsTruck', 'realNumberPalletsTruck'));
        } elseif (isset($uploadDocument)) {
            $transfer = Palletstransfer::where('id', $uploadDocument)->first();
            $documents = $request->file('documentsTransfer' . $uploadDocument);
            $this->upload($documents, $transfer, $loading);
            return redirect()->back();
        } elseif (isset($delete)) {
            //get all the data necessary to display the transfer details page
            $transfer = Palletstransfer::where('id', $delete)->first();
            $listPalletsAccounts = Palletsaccount::where('type', 'Network')->orWhere('type', 'Other')->orderBy('name', 'asc')->get();
            $listTrucksAccounts = Truck::orderBy('name', 'asc')->get();
            $listAtrnr = [];
            foreach (Loading::where('pt', 'JA')->orderBy('atrnr', 'asc')->get() as $loading) {
                $listAtrnr[] = $loading->atrnr;
            }
            $filesNames = $this->actualDocuments($transfer->id);
            //redirect to the details page of the transfer to delete it
            return view('palletstransfers.detailsPalletstransfer', compact('transfer', 'listAtrnr', 'listPalletsAccounts', 'listTrucksAccounts', 'filesNames', 'delete'));
        } elseif (isset($deleteDocument)) {
            $this->deleteDocument(Palletstransfer::where('id', trim(explode('-', $deleteDocument)[1]))->first(), trim(explode('-', $deleteDocument)[0]));
            $this->state($loading, Palletstransfer::where('loading_atrnr', $atrnr)->get());
            session()->flash('openPanelPallets', 'openPanelPallets');
            return redirect()->back();
        } elseif (isset($submitPallets)) {
            //to update the transfer, get all data
            $transfer = Palletstransfer::where('id', $submitPallets)->first();
            $palletsNumber = Input::get('palletsNumber' . $submitPallets);
            $type = Input::get('type' . $submitPallets);
            $details = Input::get('details' . $submitPallets);
            $date = Input::get('date' . $submitPallets);
            $creditAccount = Input::get('creditAccount' . $submitPallets);
            $debitAccount = Input::get('debitAccount' . $submitPallets);
            $validate = Input::get('validate' . $submitPallets);
            $normalTransferAssociated = Input::get('normalTransferAssociated' . $submitPallets);

            //to see if we are updating a normal transfer or a correcting one
            $submitPalletsNormal = $this->defineSubmitPalletsValue($listPalletstransfersNormal, $listPalletstransfersCorrecting, $submitPallets)[0];
            $submitPalletsCorrecting = $this->defineSubmitPalletsValue($listPalletstransfersNormal, $listPalletstransfersCorrecting, $submitPallets)[1];

            $filesNames = $this->actualDocuments($transfer->id);
            $view = $this->updateTransfer($filesNames, $transfer, $loading, $type, $date, $details, $validate, $creditAccount, $debitAccount, $palletsNumber, $submitPallets, $normalTransferAssociated);

            if ($view == 'error') {
                return redirect()->back();
            } elseif ($view == 'ok') {
                $transfer = Palletstransfer::where('id', $transfer->id)->first();
                ini_set('memory_limit', '-1');
                return view('loadings.detailsLoading', compact('loading', 'disp', 'listPalletsAccounts', 'listTrucksAccounts', 'listTrucksPossible', 'listPalletstransfers', 'listPalletstransfersNormal', 'listPalletstransfersCorrecting',
                    'transfer', 'submitPalletsNormal', 'submitPalletsCorrecting', 'filesNames', 'theoricalNumberPalletsTruck', 'realNumberPalletsTruck'));
            }
        } elseif (isset($okSubmitPalletsModal)) {
            $okSubmitPalletsModalNormal = $this->defineSubmitPalletsValue($listPalletstransfersNormal, $listPalletstransfersCorrecting, $okSubmitPalletsModal)[0];
            $okSubmitPalletsModalCorrecting = $this->defineSubmitPalletsValue($listPalletstransfersNormal, $listPalletstransfersCorrecting, $okSubmitPalletsModal)[1];

            //valide the transfer update
            $transfer = Palletstransfer::where('id', $okSubmitPalletsModal)->first();
            $filesNames = $this->actualDocuments($transfer->id);
            $view = $this->validateUpdateTransfer($transfer, $filesNames, $loading);
            $transfer = Palletstransfer::where('id', $okSubmitPalletsModal)->first();
            if ($view == 'ok') {
                //get only the normal transfers (deposit/withdrawal)
                $listPalletstransfersNormal = Palletstransfer::where('loading_atrnr', $atrnr)->where(function ($q) {
                    $q->where('type', 'Deposit_Only')->orWhere('type', 'Withdrawal_Only')->orWhere('type', 'Withdrawal-Deposit')->orWhere('type', 'Deposit-Withdrawal');
                })->get();
                //get only the correcting transfers (sale/purchase)
                $listPalletstransfersCorrecting = Palletstransfer::where('loading_atrnr', $atrnr)->where(function ($q) {
                    $q->where('type', 'Purchase-Sale')->orWhere('type', 'Sale-Purchase')->orWhere('type', 'Other');
                })->get();
                ini_set('memory_limit', '-1');
                return view('loadings.detailsLoading', compact('loading', 'disp', 'listPalletsAccounts', 'listTrucksAccounts', 'listTrucksPossible', 'listPalletstransfers', 'listPalletstransfersNormal', 'listPalletstransfersCorrecting',
                    'transfer', 'okSubmitPalletsModalNormal', 'okSubmitPalletsModalCorrecting', 'filesNames', 'theoricalNumberPalletsTruck', 'realNumberPalletsTruck'));
            } elseif ($view == 'error') {
                return redirect()->back();
            }
        } elseif (isset($closeSubmitPalletsModal)) {
            //refuse the transfer update
            $this->refuseValidateUpdateTransfer($closeSubmitPalletsModal, $loading);
            return redirect()->back();
        } elseif (isset($closeSubmitValidatePalletsModal)) {
            $transfer=Palletstransfer::where('id',$closeSubmitValidatePalletsModal )->first();
            //refuse the transfer update validate
            $this->refuseValidateValidateUpdateTransfer($closeSubmitValidatePalletsModal, $loading, $transfer);
            return redirect()->back();
        }elseif (isset($okSubmitPalletsValidateModal)) {
            $this->validateCompleteUpdateTransfer($okSubmitPalletsValidateModal, $loading, $listPalletstransfers);
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
    public function addPalletsTransfer($type, $debitAccount, $creditAccount, $debitAccount2, $creditAccount2, $palletsNumber, $palletsNumber2, $normalTransferAssociated)
    {
        if (!isset($type)) {
            session()->flash('errorType', "The type hasn't been filled");
            $view = 'error';
        } elseif ($type == 'Deposit-Withdrawal' || $type == 'Withdrawal-Deposit') {
            if (!isset($debitAccount) && !isset($creditAccount) && !(isset($palletsNumber))) {
                $view = 'error';
                session()->flash('errorFields', "The fields have not been filled as expected");
            } else {
                $view = 'ok';
                $creditAccount2 = $debitAccount;
                $debitAccount2 = $creditAccount;
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

        if ($view == 'ok') {
            session()->flash('palletsNumber', $palletsNumber);
            session()->flash('openPanelPallets', 'openPanelPallets');
            $actualTheoricalDebitPalletsNumber = $this->actualTheoricalPalletsNumber($creditAccount, $debitAccount)[0];
            $actualTheoricalCreditPalletsNumber = $this->actualTheoricalPalletsNumber($creditAccount, $debitAccount)[1];
            $this->displayAccounts($creditAccount, $debitAccount, null);
            session()->put('palletsNumberCreditAccount', $actualTheoricalCreditPalletsNumber);
            session()->put('palletsNumberDebitAccount', $actualTheoricalDebitPalletsNumber);
            if (isset($creditAccount2) && isset($debitAccount2)) {
                $this->displayAccounts($creditAccount2, $debitAccount2, 2);
                session()->flash('palletsNumber2', $palletsNumber2);
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
            $namePalletsAccount = Palletsaccount::where('id', explode('-', $creditAccount)[1])->value('name');
            session()->flash('creditAccount' . $index, $namePalletsAccount);
        }
        if (strpos($debitAccount, '-') == 5 && explode('-', $debitAccount)[0] == 'truck') {
            //truck account
            $nameTruckAccount = Truck::where('id', explode('-', $debitAccount)[1])->value('name');
            $licensePlate = Truck::where('id', explode('-', $debitAccount)[1])->value('licensePlate');
            session()->flash('debitAccount' . $index, $nameTruckAccount . ' - ' . $licensePlate);
        } elseif (strpos($debitAccount, '-') == 7 && explode('-', $debitAccount)[0] == 'account') {
            //others accounts (network, other)
            $namePalletsAccount = Palletsaccount::where('id', explode('-', $debitAccount)[1])->value('name');
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
            $namePalletsAccount = Palletsaccount::where('id', explode('-', $creditAccount)[1])->value('name');
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
            $namePalletsAccount = Palletsaccount::where('id', explode('-', $debitAccount)[1])->value('name');
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
    public function createTransfer($loading, $type, $date, $details, $creditAccountTransfer, $debitAccountTransfer, $palletsNumber, $creditAccountTransfer2, $debitAccountTransfer2, $palletsNumber2, $normalTransferAssociated)
    {
        if ($type == 'Deposit-Withdrawal') {
            if (!isset($palletsNumber2)) {
                Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading->atrnr]);
                Palletstransfer::create(['date' => $date, 'type' => 'Withdrawal-Deposit', 'details' => $details, 'creditAccount' => $creditAccountTransfer2, 'debitAccount' => $debitAccountTransfer2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading->atrnr, 'state' => 'Untreated']);
            } else {
                Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading->atrnr]);
                Palletstransfer::create(['date' => $date, 'type' => 'Withdrawal-Deposit', 'details' => $details, 'creditAccount' => $creditAccountTransfer2, 'debitAccount' => $debitAccountTransfer2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading->atrnr]);

            }
        } elseif ($type == 'Withdrawal-Deposit') {
            if (!isset($palletsNumber2)) {
                Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading->atrnr]);
                Palletstransfer::create(['date' => $date, 'type' => 'Deposit-Withdrawal', 'details' => $details, 'creditAccount' => $creditAccountTransfer2, 'debitAccount' => $debitAccountTransfer2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading->atrnr, 'state' => 'Untreated']);
            } else {
                Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading->atrnr]);
                Palletstransfer::create(['date' => $date, 'type' => 'Deposit-Withdrawal', 'details' => $details, 'creditAccount' => $creditAccountTransfer2, 'debitAccount' => $debitAccountTransfer2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading->atrnr]);

            }
        } elseif ($type == 'Deposit_Only') {
            Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading->atrnr]);
        } elseif ($type == 'Withdrawal_Only') {
            Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading->atrnr]);
        } elseif ($type == 'Purchase-Sale') {
            Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading->atrnr, 'normalTransferAssociated' => $normalTransferAssociated]);
            Palletstransfer::create(['date' => $date, 'type' => 'Sale-Purchase', 'details' => $details, 'creditAccount' => $creditAccountTransfer2, 'debitAccount' => $debitAccountTransfer2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading->atrnr, 'normalTransferAssociated' => $normalTransferAssociated]);
        } else {
            Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading->atrnr, 'normalTransferAssociated' => $normalTransferAssociated]);
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
    public function updatePalletsAccount($creditAccount, $debitAccount, $actualTheoricalCreditPalletsNumber, $actualTheoricalDebitPalletsNumber, $palletsNumber)
    {
        if (strpos($creditAccount, '-') == 5 && explode('-', $creditAccount)[0] == 'truck') {
            //truck account
            Truck::where('id', explode('-', $creditAccount)[1])->update(['theoricalNumberPallets' => $actualTheoricalCreditPalletsNumber + $palletsNumber]);
            $palletsaccount_name = Truck::where('id', explode('-', $creditAccount)[1])->value('palletsaccount_name');
            Palletsaccount::where('name', $palletsaccount_name)->update(['theoricalNumberPallets' => Palletsaccount::where('name', $palletsaccount_name)->sum('theoricalNumberPallets')]);
        } elseif (strpos($creditAccount, '-') == 7 && explode('-', $creditAccount)[0] == 'account') {
            //others accounts (network, other)
            Palletsaccount::where('id', explode('-', $creditAccount)[1])->update(['theoricalNumberPallets' => $actualTheoricalCreditPalletsNumber + $palletsNumber]);
        }

        if (strpos($debitAccount, '-') == 5 && explode('-', $debitAccount)[0] == 'truck') {
            //truck account
            Truck::where('id', explode('-', $debitAccount)[1])->update(['theoricalNumberPallets' => $actualTheoricalDebitPalletsNumber - $palletsNumber]);
            $palletsaccount_name = Truck::where('id', explode('-', $debitAccount)[1])->value('palletsaccount_name');
            Palletsaccount::where('name', $palletsaccount_name)->update(['theoricalNumberPallets' => Palletsaccount::where('name', $palletsaccount_name)->sum('theoricalNumberPallets')]);
        } elseif (strpos($debitAccount, '-') == 7 && explode('-', $debitAccount)[0] == 'account') {
            //others accounts (network, other)
            Palletsaccount::where('id', explode('-', $debitAccount)[1])->update(['theoricalNumberPallets' => $actualTheoricalDebitPalletsNumber - $palletsNumber]);
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
    public function validateAddPalletsTransfer($loading, $type, $date, $details, $creditAccount, $debitAccount, $creditAccount2, $debitAccount2, $palletsNumber, $palletsNumber2, $normalTransferAssociated)
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
        $this->createTransfer($loading, $type, $date, $details, $creditAccountTransfer, $debitAccountTransfer, $palletsNumber, $creditAccountTransfer2, $debitAccountTransfer2, $palletsNumber2, $normalTransferAssociated);
        $this->updatePalletsAccount($creditAccount, $debitAccount, $actualTheoricalCreditPalletsNumber, $actualTheoricalDebitPalletsNumber, $palletsNumber);
        if (isset($creditAccount2) && isset($debitAccount2)) {
            $actualTheoricalCreditPalletsNumber2 = session('palletsNumberCreditAccount2');
            $actualTheoricalDebitPalletsNumber2 = session('palletsNumberDebitAccount2');
            $this->updatePalletsAccount($creditAccount2, $debitAccount2, $actualTheoricalCreditPalletsNumber2, $actualTheoricalDebitPalletsNumber2, $palletsNumber2);
        }
        if ($type == 'Deposit_Only') {
            session()->flash('sumTransfersDepositOnly', Palletstransfer::where('type', 'Deposit_Only')->sum('palletsNumber') + $palletsNumber);
            session()->flash('sumTransfersWithdrawalOnly', Palletstransfer::where('type', 'Withdrawal_Only')->sum('palletsNumber'));
        } elseif ($type == 'Withdrawal_Only') {
            session()->flash('sumTransfersDepositOnly', Palletstransfer::where('type', 'Deposit_Only')->sum('palletsNumber'));
            session()->flash('sumTransfersWithdrawalOnly', Palletstransfer::where('type', 'Withdrawal_Only')->sum('palletsNumber') + $palletsNumber);
        }
        $this->state($loading, Palletstransfer::where('loading_atrnr', $loading->atrnr)->get());
        session()->flash('messageAddPalletstransfer', 'Successfully added new pallets transfer');
        session()->flash('openPanelPallets', 'openPanelPallets');
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
    public function updateTransfer($filesNames, $transfer, $loading, $type, $date, $details, $validate, $creditAccount, $debitAccount, $palletsNumber, $submitPallets, $normalTransferAssociated)
    {
        $rules = array(
            'creditAccount' . $submitPallets => 'required',
            'debitAccount' . $submitPallets => 'required',
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            session()->flash('openPanelPallets', 'openPanelPallets');
            session()->flash('errorAccountsPanel', "The account(s) has(ve) not been filled as expected. REFILL ! (transfer " . $transfer->id . ")");
            $view = 'error';
        } else {
            if ($transfer->state == 'Complete Validated') {
                $this->inverseRealPalletsNumber($transfer);
            }
            session()->put('actualCreditAccount', $transfer->creditAccount);
            session()->put('actualDebitAccount', $transfer->debitAccount);
            session()->put('actualPalletsNumber', $transfer->palletsNumber);
            session()->put('actualType', $transfer->type);
            session()->put('actualDetails', $transfer->details);
            session()->put('actualDate', $transfer->date);
            session()->put('actualNormalTransferAssociated', $transfer->normalTransferAssociated);
            session()->put('actualValidate', $transfer->validate);
            session()->flash('palletsNumber', $palletsNumber);
            if ($transfer->validate == 1 && $validate <> null && $validate == 'false') {
                Palletstransfer::where('id', $transfer->id)->update(['validate' => false]);
                //state
                if (!empty($filesNames) && $validate == 'false') {
                    Palletstransfer::where('id', $transfer->id)->update(['state' => 'Complete']);
                } elseif (empty($filesNames)) {
                    Palletstransfer::where('id', $transfer->id)->update(['state' => 'Waiting documents']);
                }
                $view = 'error';
            } elseif ($transfer->validate == 1 && $validate <> null && $validate == 'true') {
                $view = 'error';
            } else {
                if (($transfer->type == 'Deposit-Withdrawal' || $transfer->type == 'Withdrawal-Deposit') && ($type <> 'Deposit-Withdrawal' || $type <> 'Withdrawal-Deposit')) {
//                    $transfer->errors()->detach(Error::where('name', 'DW-WD_notSameNumber')->first()->id);
                    $transfer->errors()->detach(Error::where('name', 'DW-WD_notNumberLoadingOrder')->first()->id);
                } elseif (($transfer->type == 'Deposit_Only' || $transfer->type == 'Withdrawal_Only') && ($type <> 'Deposit_Only' || $type <> 'Withdrawal_Only')) {
                    $transfer->errors()->detach(Error::where('name', 'Donly-Wonly_notSameNumber')->first()->id);
                } elseif (($transfer->type == 'Sale-Purchase' || $transfer->type == 'Purchase-Sale') && ($type <> 'Sale-Purchase' || $type <> 'Purchase-Sale')) {
                    $transfer->errors()->detach(Error::where('name', 'SP-PS_notSameNumber')->first()->id);
                    $transfer->errors()->detach(Error::where('name', 'Correcting_notCompleteNormal')->first()->id);
                }
                $debitAccountTransfer = $this->namesAccounts($creditAccount, $debitAccount, 1)[0];
                Palletstransfer::where('id', $transfer->id)->update(['debitAccount' => $debitAccountTransfer]);
                $creditAccountTransfer = $this->namesAccounts($creditAccount, $debitAccount, 1)[1];
                Palletstransfer::where('id', $transfer->id)->update(['creditAccount' => $creditAccountTransfer]);

                Palletstransfer::where('id', $transfer->id)->update(['type' => $type, 'details' => $details, 'loading_atrnr' => $loading->atrnr, 'palletsNumber' => $palletsNumber, 'date' => $date, 'normalTransferAssociated' => $normalTransferAssociated]);

                if ($validate <> null && $validate == 'true') {
                    Palletstransfer::where('id', $transfer->id)->update(['validate' => true]);
                } elseif ($validate <> null && $validate == 'false') {
                    Palletstransfer::where('id', $transfer->id)->update(['validate' => false]);
                }
                $view = 'ok';
            }
            $this->state($loading, Palletstransfer::where('loading_atrnr', $loading->atrnr)->get());
            session()->flash('openPanelPallets', 'openPanelPallets');
        }
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
        session()->flash('openPanelPallets', 'openPanelPallets');
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
                Palletsaccount::where('name', $palletsaccount_name)->update(['realNumberPallets' => Palletsaccount::where('name', $palletsaccount_name)->sum('realNumberPallets')]);
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
                Palletsaccount::where('name', $palletsaccount_name)->update(['realNumberPallets' => Palletsaccount::where('name', $palletsaccount_name)->sum('realNumberPallets')]);
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
    public function validateUpdateTransfer($transfer, $filesNames, $loading)
    {
        $actualCreditAccount = session('actualCreditAccount');
        $actualDebitAccount = session('actualDebitAccount');
        $actualPalletsNumber = session('actualPalletsNumber');

//inverse transfer : we delete the last transfer
        $partsCreditAccount = explode('-', $actualCreditAccount);
        $typeCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 2];
        $idCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 1];

        if ($typeCreditAccount == 'truck') {
            $actualPalletsNumberCreditAccount = Truck::where('id', $idCreditAccount)->first()->theoricalNumberPallets;
            Truck::where('id', $idCreditAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberCreditAccount - $actualPalletsNumber]);
            $palletsaccount_name = Truck::where('id', $idCreditAccount)->value('palletsaccount_name');
            Palletsaccount::where('name', $palletsaccount_name)->update(['theoricalNumberPallets' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('theoricalNumberPallets')]);
        } elseif ($typeCreditAccount == 'account') {
            $actualPalletsNumberCreditAccount = Palletsaccount::where('id', $idCreditAccount)->first()->theoricalNumberPallets;
            Palletsaccount::where('id', $idCreditAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberCreditAccount - $actualPalletsNumber]);
        }

        $partsDebitAccount = explode('-', $actualDebitAccount);
        $typeDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 2];
        $idDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 1];
        if ($typeDebitAccount == 'truck') {
            $actualPalletsNumberDebitAccount = Truck::where('id', $idDebitAccount)->first()->theoricalNumberPallets;
            Truck::where('id', $idDebitAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberDebitAccount + $actualPalletsNumber]);
            $palletsaccount_name = Truck::where('id', $idDebitAccount)->value('palletsaccount_name');
            Palletsaccount::where('name', $palletsaccount_name)->update(['theoricalNumberPallets' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('theoricalNumberPallets')]);
        } elseif ($typeDebitAccount == 'account') {
            $actualPalletsNumberDebitAccount = Palletsaccount::where('id', $idDebitAccount)->first()->theoricalNumberPallets;
            Palletsaccount::where('id', $idDebitAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberDebitAccount + $actualPalletsNumber]);
        }

        //we do the new transfer and update accounts
        $partsCreditAccount = explode('-', $transfer->creditAccount);
        $typeCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 2];
        $idCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 1];
        if ($typeCreditAccount == 'truck') {
            $palletsNumberCreditAccount = Truck::where('id', $idCreditAccount)->first()->theoricalNumberPallets;
            Truck::where('id', $idCreditAccount)->update(['theoricalNumberPallets' => $palletsNumberCreditAccount + $transfer->palletsNumber]);
            $palletsaccount_name = Truck::where('id', $idCreditAccount)->value('palletsaccount_name');
            Palletsaccount::where('name', $palletsaccount_name)->update(['theoricalNumberPallets' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('theoricalNumberPallets')]);
        } elseif ($typeCreditAccount == 'account') {
            $palletsNumberCreditAccount = Palletsaccount::where('id', $idCreditAccount)->first()->theoricalNumberPallets;
            Palletsaccount::where('id', $idCreditAccount)->update(['theoricalNumberPallets' => $palletsNumberCreditAccount + $transfer->palletsNumber]);
        }

        $partsDebitAccount = explode('-', $transfer->debitAccount);
        $typeDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 2];
        $idDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 1];
        if ($typeDebitAccount == 'truck') {
            $palletsNumberDebitAccount = Truck::where('id', $idDebitAccount)->first()->theoricalNumberPallets;
            Truck::where('id', $idDebitAccount)->update(['theoricalNumberPallets' => $palletsNumberDebitAccount - $transfer->palletsNumber]);
            $palletsaccount_name = Truck::where('id', $idDebitAccount)->value('palletsaccount_name');
            Palletsaccount::where('name', $palletsaccount_name)->update(['theoricalNumberPallets' => Truck::where('palletsaccount_name',$palletsaccount_name)->sum('theoricalNumberPallets')]);
        } elseif ($typeDebitAccount == 'account') {
            $palletsNumberDebitAccount = Palletsaccount::where('id', $idDebitAccount)->first()->theoricalNumberPallets;
            Palletsaccount::where('id', $idDebitAccount)->update(['theoricalNumberPallets' => $palletsNumberDebitAccount - $transfer->palletsNumber]);
        }

        //state
        if (!empty($filesNames) && $transfer->validate == 1) {
            Palletstransfer::where('id', $transfer->id)->update(['state' => 'Complete Validated']);
        } elseif (!empty($filesNames) && $transfer->validate == 0) {
            Palletstransfer::where('id', $transfer->id)->update(['state' => 'Complete']);
        } elseif (empty($filesNames)) {
            Palletstransfer::where('id', $transfer->id)->update(['state' => 'Waiting documents']);
        }
        $transfer = Palletstransfer::where('id', $transfer->id)->first();
        $this->state($loading, Palletstransfer::where('loading_atrnr', $loading->atrnr)->get());
        session()->flash('openPanelPallets', 'openPanelPallets');

        if ($transfer->type == 'Deposit_Only') {
            session()->flash('sumTransfersDepositOnly', Palletstransfer::where('type', 'Deposit_Only')->where('loading_atrnr', $loading->atrnr)->sum('palletsNumber') + $transfer->palletsNumber);
            session()->flash('sumTransfersWithdrawalOnly', Palletstransfer::where('type', 'Withdrawal_Only')->where('loading_atrnr', $loading->atrnr)->sum('palletsNumber'));
        } elseif ($transfer->type == 'Withdrawal_Only') {
            session()->flash('sumTransfersDepositOnly', Palletstransfer::where('type', 'Deposit_Only')->where('loading_atrnr', $loading->atrnr)->sum('palletsNumber'));
            session()->flash('sumTransfersWithdrawalOnly', Palletstransfer::where('type', 'Withdrawal_Only')->where('loading_atrnr', $loading->atrnr)->sum('palletsNumber') + $transfer->palletsNumber);
        }

        if ($transfer->state == 'Complete Validated') {
            session()->flash('palletsNumber', $transfer->palletsNumber);
            session()->flash('creditAccount', $transfer->creditAccount);
            session()->flash('debitAccount', $transfer->debitAccount);

            $partsCreditAccount = explode('-', $transfer->creditAccount);
            $typeCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 2];
            $idCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 1];
            if ($typeCreditAccount == 'truck') {
                session()->flash('realPalletsNumberCreditAccount', Truck::where('id', $idCreditAccount)->first()->realNumberPallets);
            } elseif ($typeCreditAccount == 'account') {
                session()->flash('realPalletsNumberCreditAccount', Palletsaccount::where('id', $idCreditAccount)->first()->realNumberPallets);
            }

            $partsDebitAccount = explode('-', $transfer->debitAccount);
            $typeDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 2];
            $idDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 1];
            if ($typeDebitAccount == 'truck') {
                session()->flash('realPalletsNumberDebitAccount', Truck::where('id', $idDebitAccount)->first()->realNumberPallets);
            } elseif ($typeDebitAccount == 'account') {
                session()->flash('realPalletsNumberDebitAccount', Palletsaccount::where('id', $idDebitAccount)->first()->realNumberPallets);
            }
            $view = 'ok';
        } else {
            session()->pull('actualCreditAccount');
            session()->pull('actualDebitAccount');
            session()->pull('actualPalletsNumber');
            session()->pull('actualType');
            session()->pull('actualDetails');
            session()->pull('actualDate');
            session()->pull('actualNormalTransferAssociated');
            session()->pull('actualValidate');
            $view = 'error';
        }

        session()->flash('messageSubmitPalletstransfer', 'Successfully updated and pallets transfer');
        return $view;
    }

    /**
     * cancelling the update transfer -> go back to the initial state
     * @param $closeSubmitPalletsModal
     * @param $loading
     */
    public function refuseValidateUpdateTransfer($closeSubmitPalletsModal, $loading)
    {
        $actualCreditAccount = session('actualCreditAccount');
        $actualDebitAccount = session('actualDebitAccount');
        $actualPalletsNumber = session('actualPalletsNumber');
        $actualType = session('actualType');
        $actualDetails = session('actualDetails');
        $actualDate = session('actualDate');
        $actualValidate = session('actualValidate');
        $actualNormalTransferAssociated = session('actualNormalTransferAssociated');

        if (isset($actualDebitAccount)) {
            Palletstransfer::where('id', $closeSubmitPalletsModal)->update(['debitAccount' => $actualDebitAccount]);
        }
        if (isset($actualCreditAccount)) {
            Palletstransfer::where('id', $closeSubmitPalletsModal)->update(['creditAccount' => $actualCreditAccount]);
        }
        Palletstransfer::where('id', $closeSubmitPalletsModal)->update(['validate' => $actualValidate, 'type' => $actualType, 'details' => $actualDetails, 'palletsNumber' => $actualPalletsNumber, 'date' => $actualDate, 'normalTransferAssociated' => $actualNormalTransferAssociated]);

        $filesNames = $this->actualDocuments($closeSubmitPalletsModal);
        if (!empty($filesNames) && $actualValidate == 1) {
            Palletstransfer::where('id', $closeSubmitPalletsModal)->update(['state' => 'Complete Validated']);
        } elseif (!empty($filesNames) && $actualValidate == 0) {
            Palletstransfer::where('id', $closeSubmitPalletsModal)->update(['state' => 'Complete']);
        } elseif (empty($filesNames)) {
            Palletstransfer::where('id', $closeSubmitPalletsModal)->update(['state' => 'Waiting documents']);
        }

        $this->state($loading, Palletstransfer::where('loading_atrnr', $loading->atrnr)->get());
        session()->pull('actualCreditAccount');
        session()->pull('actualDebitAccount');
        session()->pull('actualPalletsNumber');
        session()->pull('actualType');
        session()->pull('actualDetails');
        session()->pull('actualDate');
        session()->pull('actualValidate');
        session()->pull('actualNormalTransferAssociated');
        session()->flash('openPanelPallets', 'openPanelPallets');
    }

    /**
     * cancelling the update transfer -> go back to the initial state
     * @param $closeSubmitPalletsModal
     * @param $loading
     */
    public function refuseValidateValidateUpdateTransfer($closeSubmitValidatePalletsModal, $loading, $transfer)
    {
        $actualCreditAccount = session('actualCreditAccount');
        $actualDebitAccount = session('actualDebitAccount');
        $actualPalletsNumber = session('actualPalletsNumber');
        $actualType = session('actualType');
        $actualDetails = session('actualDetails');
        $actualDate = session('actualDate');
        $actualValidate = session('actualValidate');
        $actualNormalTransferAssociated = session('actualNormalTransferAssociated');

//inverse transfer : we delete the last transfer
        $partsCreditAccount = explode('-', $actualCreditAccount);
        $typeCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 2];
        $idCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 1];
        if ($typeCreditAccount == 'truck') {
            $actualPalletsNumberCreditAccount = Truck::where('id', $idCreditAccount)->first()->theoricalNumberPallets;
            Truck::where('id', $idCreditAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberCreditAccount - $transfer->palletsNumber + $actualPalletsNumber]);
            $palletsaccount_name = Truck::where('id', $idCreditAccount)->value('palletsaccount_name');
            Palletsaccount::where('name', $palletsaccount_name)->update(['theoricalNumberPallets' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('theoricalNumberPallets')]);
        } elseif ($typeCreditAccount == 'account') {
            $actualPalletsNumberCreditAccount = Palletsaccount::where('id', $idCreditAccount)->first()->theoricalNumberPallets;
            Palletsaccount::where('id', $idCreditAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberCreditAccount - $transfer->palletsNumber + $actualPalletsNumber]);
        }

        $partsDebitAccount = explode('-', $actualDebitAccount);
        $typeDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 2];
        $idDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 1];
        if ($typeDebitAccount == 'truck') {
            $actualPalletsNumberDebitAccount = Truck::where('id', $idDebitAccount)->first()->theoricalNumberPallets;
            Truck::where('id', $idDebitAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberDebitAccount + $transfer->palletsNumber - $actualPalletsNumber]);
            $palletsaccount_name = Truck::where('id', $idDebitAccount)->value('palletsaccount_name');
            Palletsaccount::where('name', $palletsaccount_name)->update(['theoricalNumberPallets' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('theoricalNumberPallets')]);
        } elseif ($typeDebitAccount == 'account') {
            $actualPalletsNumberDebitAccount = Palletsaccount::where('id', $idDebitAccount)->first()->theoricalNumberPallets;
            Palletsaccount::where('id', $idDebitAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberDebitAccount + $transfer->palletsNumber - $actualPalletsNumber]);
        }

        if (isset($actualDebitAccount)) {
            Palletstransfer::where('id', $closeSubmitValidatePalletsModal)->update(['debitAccount' => $actualDebitAccount]);
        }
        if (isset($actualCreditAccount)) {
            Palletstransfer::where('id', $closeSubmitValidatePalletsModal)->update(['creditAccount' => $actualCreditAccount]);
        }
        Palletstransfer::where('id', $closeSubmitValidatePalletsModal)->update(['validate' => $actualValidate, 'type' => $actualType, 'details' => $actualDetails, 'palletsNumber' => $actualPalletsNumber, 'date' => $actualDate, 'normalTransferAssociated' => $actualNormalTransferAssociated]);

        $filesNames = $this->actualDocuments($closeSubmitValidatePalletsModal);
        if (!empty($filesNames) && $actualValidate == 1) {
            Palletstransfer::where('id', $closeSubmitValidatePalletsModal)->update(['state' => 'Complete Validated']);
        } elseif (!empty($filesNames) && $actualValidate == 0) {
            Palletstransfer::where('id', $closeSubmitValidatePalletsModal)->update(['state' => 'Complete']);
        } elseif (empty($filesNames)) {
            Palletstransfer::where('id', $closeSubmitValidatePalletsModal)->update(['state' => 'Waiting documents']);
        }

        $this->state($loading, Palletstransfer::where('loading_atrnr', $loading->atrnr)->get());
        session()->pull('actualCreditAccount');
        session()->pull('actualDebitAccount');
        session()->pull('actualPalletsNumber');
        session()->pull('actualType');
        session()->pull('actualDetails');
        session()->pull('actualDate');
        session()->pull('actualValidate');
        session()->pull('actualNormalTransferAssociated');
        session()->flash('openPanelPallets', 'openPanelPallets');
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

        if (isset($transfer->creditAccount)) {
            $partsCreditAccount = explode('-', $transfer->creditAccount);
            $typeCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 2];
            $idCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 1];
            if ($typeCreditAccount == 'truck') {
                $realPalletsNumberCreditAccount = Truck::where('id', $idCreditAccount)->first()->realNumberPallets;
                Truck::where('id', $idCreditAccount)->update(['realNumberPallets' => $realPalletsNumberCreditAccount + $transfer->palletsNumber]);
                $palletsaccount_name = Truck::where('id', $idCreditAccount)->value('palletsaccount_name');
                Palletsaccount::where('name', $palletsaccount_name)->update(['realNumberPallets' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('realNumberPallets')]);
            } elseif ($typeCreditAccount == 'account') {
                $realPalletsNumberCreditAccount = Palletsaccount::where('id', $idCreditAccount)->first()->realNumberPallets;
                Palletsaccount::where('id', $idCreditAccount)->update(['realNumberPallets' => $realPalletsNumberCreditAccount + $transfer->palletsNumber]);
            }
        }
        if (isset($transfer->debitAccount)) {
            $partsDebitAccount = explode('-', $transfer->debitAccount);
            $typeDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 2];
            $idDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 1];
            if ($typeDebitAccount == 'truck') {
                $realPalletsNumberDebitAccount = Truck::where('id', $idDebitAccount)->first()->realNumberPallets;
                Truck::where('id', $idDebitAccount)->update(['realNumberPallets' => $realPalletsNumberDebitAccount - $transfer->palletsNumber]);
                $palletsaccount_name = Truck::where('id', $idDebitAccount)->value('palletsaccount_name');
                Palletsaccount::where('name', $palletsaccount_name)->update(['realNumberPallets' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('realNumberPallets')]);
            } elseif ($typeDebitAccount == 'account') {
                $realPalletsNumberDebitAccount = Palletsaccount::where('id', $idDebitAccount)->first()->realNumberPallets;
                Palletsaccount::where('id', $idDebitAccount)->update(['realNumberPallets' => $realPalletsNumberDebitAccount - $transfer->palletsNumber]);
            }
        }

        $this->state($loading, $listPalletstransfers);
        session()->flash('messageUpdateValidatePalletstransfer', 'VALIDATE ! Successfully updated and validated pallets transfer');
        session()->pull('actualCreditAccount');
        session()->pull('actualDebitAccount');
        session()->pull('actualPalletsNumber');
        session()->pull('actualType');
        session()->pull('actualDetails');
        session()->pull('actualDate');
        session()->pull('actualValidate');
        session()->pull('actualNormalTransferAssociated');
        session()->flash('openPanelPallets', 'openPanelPallets');
    }

    /**
     * define the general state of the loading according to all transfers state and update every error
     * @param $loading
     * @param $listPalletstransfers
     */
    public static function state($loading, $listPalletstransfers)
    {
        $idErrorNotNumberLoadingOrder = Error::where('name', 'DW-WD_notNumberLoadingOrder')->first()->id;
        $idErrorNotSameNumber = Error::where('name', 'Donly-Wonly_notSameNumber')->first()->id;
        $idErrorNotCompleteNormal = Error::where('name', 'Correcting_notCompleteNormal')->first()->id;
        $idErrorNotSameNumberSP = Error::where('name', 'SP-PS_notSameNumber')->first()->id;

        //update errors on each transfer SP-PS associated to this loading
        $queryTransfer2 = Palletstransfer::where(function ($q) {
            $q->where('type', 'Sale-Purchase')->orWhere('type', 'Purchase-Sale');
        })->where('loading_atrnr', $loading->atrnr);

        if (!$queryTransfer2->get()->isEmpty()) {
            foreach ($queryTransfer2->get() as $transferQ) {
                $listTransfersSPK = Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Sale-Purchase')->get();
                $listTransfersPSK = Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Purchase-Sale')->get();
                $listNormalTransfersAssociated = [];
                foreach ($listTransfersSPK as $transferSPK) {
                    if (!in_array($transferSPK->normalTransferAssociated, $listNormalTransfersAssociated)) {
                        $listNormalTransfersAssociated[] = $transferSPK->normalTransferAssociated;
                    }
                }
                foreach ($listTransfersPSK as $transferPSK) {
                    if (!in_array($transferPSK->normalTransferAssociated, $listNormalTransfersAssociated)) {
                        $listNormalTransfersAssociated[] = $transferPSK->normalTransferAssociated;
                    }
                }

                for ($k = 0; $k < count($listNormalTransfersAssociated); $k++) {
                    $sumTransferSP = Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Sale-Purchase')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->sum('palletsNumber');
                    $sumTransferPS = Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Purchase-Sale')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->sum('palletsNumber');
//                    $palletsNumberNormalTransferAssociated=Palletstransfer::where('id',$listNormalTransfersAssociated[$k])->first()->palletsNumber;
                    $transferNormalK = Palletstransfer::where('id', $listNormalTransfersAssociated[$k])->first();

                    if ($transferNormalK->type == 'Withdrawal-Deposit' || $transferNormalK->type == 'Deposit-Withdrawal') {
                        if ($transferNormalK->palletsNumber >= $loading->anz) {
                            if ($sumTransferSP <> $sumTransferPS && ($transferNormalK->palletsNumber - $sumTransferPS) <> $loading->anz && ($transferNormalK->palletsNumber - $sumTransferSP) == $loading->anz) {
                                foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Sale-Purchase')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ1) {
                                    $transferQ1->errors()->sync($idErrorNotSameNumberSP);
                                }
                                foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Purchase-Sale')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ2) {
                                    $transferQ2->errors()->sync([$idErrorNotSameNumberSP, $idErrorNotCompleteNormal]);
                                }
                            } elseif ($sumTransferSP <> $sumTransferPS && ($transferNormalK->palletsNumber - $sumTransferPS) <> $loading->anz && ($transferNormalK->palletsNumber - $sumTransferSP) <> $loading->anz) {
                                foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Sale-Purchase')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ3) {
                                    $transferQ3->errors()->sync([$idErrorNotSameNumberSP, $idErrorNotCompleteNormal]);
                                }
                                foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Purchase-Sale')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ4) {
                                    $transferQ4->errors()->sync([$idErrorNotSameNumberSP, $idErrorNotCompleteNormal]);
                                }
                            } elseif ($sumTransferSP <> $sumTransferPS && ($transferNormalK->palletsNumber - $sumTransferPS) == $loading->anz && ($transferNormalK->palletsNumber - $sumTransferSP) <> $loading->anz) {
                                foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Sale-Purchase')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ5) {
                                    $transferQ5->errors()->sync([$idErrorNotSameNumberSP, $idErrorNotCompleteNormal]);
                                }
                                foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Purchase-Sale')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ6) {
                                    $transferQ6->errors()->sync($idErrorNotSameNumberSP);
                                }
                            } elseif ($sumTransferSP == $sumTransferPS && ($transferNormalK->palletsNumber - $sumTransferPS) == $loading->anz) {
                                foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Sale-Purchase')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ7) {
                                    $transferQ7->errors()->detach($idErrorNotSameNumberSP);
                                    $transferQ7->errors()->detach($idErrorNotCompleteNormal);
                                }
                                foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Purchase-Sale')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ8) {
                                    $transferQ8->errors()->detach($idErrorNotSameNumberSP);
                                    $transferQ8->errors()->detach($idErrorNotCompleteNormal);
                                }
                                $transferNormalK->errors()->detach($idErrorNotNumberLoadingOrder);
                            } elseif ($sumTransferSP == $sumTransferPS && ($transferNormalK->palletsNumber - $sumTransferPS) <> $loading->anz) {
                                foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Sale-Purchase')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ9) {
                                    $transferQ9->errors()->sync($idErrorNotCompleteNormal);
                                }
                                foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Purchase-Sale')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ10) {
                                    $transferQ10->errors()->sync($idErrorNotCompleteNormal);
                                }
                            }
                        } elseif ($transferNormalK->palletsNumber <= $loading->anz) {
                            if ($sumTransferSP <> $sumTransferPS && ($transferNormalK->palletsNumber + $sumTransferPS) <> $loading->anz && ($transferNormalK->palletsNumber + $sumTransferSP) == $loading->anz) {
                                foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Sale-Purchase')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ1) {
                                    $transferQ1->errors()->sync($idErrorNotSameNumberSP);
                                }
                                foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Purchase-Sale')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ2) {
                                    $transferQ2->errors()->sync([$idErrorNotSameNumberSP, $idErrorNotCompleteNormal]);
                                }
                            } elseif ($sumTransferSP <> $sumTransferPS && ($transferNormalK->palletsNumber + $sumTransferPS) <> $loading->anz && ($transferNormalK->palletsNumber + $sumTransferSP) <> $loading->anz) {
                                foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Sale-Purchase')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ3) {
                                    $transferQ3->errors()->sync([$idErrorNotSameNumberSP, $idErrorNotCompleteNormal]);
                                }
                                foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Purchase-Sale')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ4) {
                                    $transferQ4->errors()->sync([$idErrorNotSameNumberSP, $idErrorNotCompleteNormal]);
                                }
                            } elseif ($sumTransferSP <> $sumTransferPS && ($transferNormalK->palletsNumber + $sumTransferPS) == $loading->anz && ($transferNormalK->palletsNumber + $sumTransferSP) <> $loading->anz) {
                                foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Sale-Purchase')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ5) {
                                    $transferQ5->errors()->sync([$idErrorNotSameNumberSP, $idErrorNotCompleteNormal]);
                                }
                                foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Purchase-Sale')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ6) {
                                    $transferQ6->errors()->sync($idErrorNotSameNumberSP);
                                }
                            } elseif ($sumTransferSP == $sumTransferPS && ($transferNormalK->palletsNumber + $sumTransferPS) == $loading->anz) {
                                foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Sale-Purchase')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ7) {
                                    $transferQ7->errors()->detach($idErrorNotSameNumberSP);
                                    $transferQ7->errors()->detach($idErrorNotCompleteNormal);
                                }
                                foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Purchase-Sale')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ8) {
                                    $transferQ8->errors()->detach($idErrorNotSameNumberSP);
                                    $transferQ8->errors()->detach($idErrorNotCompleteNormal);
                                }
                                $transferNormalK->errors()->detach($idErrorNotNumberLoadingOrder);
                            } elseif ($sumTransferSP == $sumTransferPS && ($transferNormalK->palletsNumber + $sumTransferPS) <> $loading->anz) {
                                foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Sale-Purchase')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ9) {
                                    $transferQ9->errors()->sync($idErrorNotCompleteNormal);
                                }
                                foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Purchase-Sale')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ10) {
                                    $transferQ10->errors()->sync($idErrorNotCompleteNormal);
                                }
                            }
                        }
                    } elseif ($transferNormalK->type == 'Withdrawal_Only' || $transferNormalK->type == 'Deposit_Only') {
                        if ($sumTransferSP <> $sumTransferPS && ($transferNormalK->palletsNumber <> $sumTransferPS) && ($transferNormalK->palletsNumber == $sumTransferSP)) {
                            foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Sale-Purchase')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ1) {
                                $transferQ1->errors()->sync($idErrorNotSameNumberSP);
                            }
                            foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Purchase-Sale')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ2) {
                                $transferQ2->errors()->sync([$idErrorNotSameNumberSP, $idErrorNotCompleteNormal]);
                            }
                        } elseif ($sumTransferSP <> $sumTransferPS && ($transferNormalK->palletsNumber <> $sumTransferPS) && ($transferNormalK->palletsNumber <> $sumTransferSP)) {
                            foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Sale-Purchase')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ3) {
                                $transferQ3->errors()->sync([$idErrorNotSameNumberSP, $idErrorNotCompleteNormal]);
                            }
                            foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Purchase-Sale')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ4) {
                                $transferQ4->errors()->sync([$idErrorNotSameNumberSP, $idErrorNotCompleteNormal]);
                            }
                        } elseif ($sumTransferSP <> $sumTransferPS && ($transferNormalK->palletsNumber == $sumTransferPS) && ($transferNormalK->palletsNumber <> $sumTransferSP)) {
                            foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Sale-Purchase')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ5) {
                                $transferQ5->errors()->sync([$idErrorNotSameNumberSP, $idErrorNotCompleteNormal]);
                            }
                            foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Purchase-Sale')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ6) {
                                $transferQ6->errors()->sync($idErrorNotSameNumberSP);
                            }
                        } elseif ($sumTransferSP == $sumTransferPS && ($transferNormalK->palletsNumber == $sumTransferPS)) {
                            foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Sale-Purchase')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ7) {
                                $transferQ7->errors()->detach($idErrorNotSameNumberSP);
                                $transferQ7->errors()->detach($idErrorNotCompleteNormal);
                            }
                            foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Purchase-Sale')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ8) {
                                $transferQ8->errors()->detach($idErrorNotSameNumberSP);
                                $transferQ8->errors()->detach($idErrorNotCompleteNormal);
                            }
                            $transferNormalK->errors()->detach($idErrorNotSameNumber);
                        } elseif ($sumTransferSP == $sumTransferPS && ($transferNormalK->palletsNumber <> $sumTransferPS)) {
                            foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Sale-Purchase')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ9) {
                                $transferQ9->errors()->sync($idErrorNotCompleteNormal);
                            }
                            foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Purchase-Sale')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ10) {
                                $transferQ10->errors()->sync($idErrorNotCompleteNormal);
                            }
                        }
                    }
                }


//                    if ($sumTransferSP <> $sumTransferPS && ($sumTransferPS +$palletsNumberNormalTransferAssociated) <> $loading->anz && ($sumTransferSP+$palletsNumberNormalTransferAssociated) == $loading->anz) {
//                        foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Sale-Purchase')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ1) {
//                            $transferQ1->errors()->sync($idErrorNotSameNumberSP);
//                        }
//                        foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Purchase-Sale')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ2) {
//                            $transferQ2->errors()->sync([$idErrorNotSameNumberSP, $idErrorNotCompleteNormal]);
//                        }
//                    } elseif ($sumTransferSP <> $sumTransferPS && ($sumTransferPS +$palletsNumberNormalTransferAssociated) <> $loading->anz && ($sumTransferSP+$palletsNumberNormalTransferAssociated) <> $loading->anz) {
//                        foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Sale-Purchase')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ3) {
//                            $transferQ3->errors()->sync([$idErrorNotSameNumberSP, $idErrorNotCompleteNormal]);
//                        }
//                        foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Purchase-Sale')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ4) {
//                            $transferQ4->errors()->sync([$idErrorNotSameNumberSP, $idErrorNotCompleteNormal]);
//                        }
//                    } elseif ($sumTransferSP <> $sumTransferPS && ($sumTransferPS +$palletsNumberNormalTransferAssociated) == $loading->anz && ($sumTransferSP+$palletsNumberNormalTransferAssociated) <> $loading->anz) {
//                        foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Sale-Purchase')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ5) {
//                            $transferQ5->errors()->sync([$idErrorNotSameNumberSP, $idErrorNotCompleteNormal]);
//                        }
//                        foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Purchase-Sale')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ6) {
//                            $transferQ6->errors()->sync($idErrorNotSameNumberSP);
//                        }
//                    } elseif ($sumTransferSP == $sumTransferPS && ($sumTransferPS +$palletsNumberNormalTransferAssociated) == $loading->anz) {
//                        foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Sale-Purchase')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ7) {
//                            $transferQ7->errors()->detach($idErrorNotSameNumberSP);
//                            $transferQ7->errors()->detach($idErrorNotCompleteNormal);
//                        }
//                        foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Purchase-Sale')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ8) {
//                            $transferQ8->errors()->detach($idErrorNotSameNumberSP);
//                            $transferQ8->errors()->detach($idErrorNotCompleteNormal);
//                        }
//                    } elseif ($sumTransferSP == $sumTransferPS && ($sumTransferPS +$palletsNumberNormalTransferAssociated) <> $loading->anz) {
//                        foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Sale-Purchase')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ9) {
//                            $transferQ9->errors()->sync($idErrorNotCompleteNormal);
//                        }
//                        foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Purchase-Sale')->where('normalTransferAssociated', $listNormalTransfersAssociated[$k])->get() as $transferQ10) {
//                            $transferQ10->errors()->sync($idErrorNotCompleteNormal);
//                        }
//                    }
//                }
            }
        }

        //update errors on each transfer DW-WD associated to this loading
        $queryTransfer = Palletstransfer::where(function ($q) {
            $q->where('type', 'Deposit-Withdrawal')->orWhere('type', 'Withdrawal-Deposit');
        })->where('loading_atrnr', $loading->atrnr);

        if (!$queryTransfer->get()->isEmpty()) {
            foreach ($queryTransfer->get() as $transferQ) {
                $listTransfersDWK = Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Deposit-Withdrawal')->get();
                $listTransfersWDK = Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Withdrawal-Deposit')->get();
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
                    $sumTransferDW = Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Deposit-Withdrawal')->where('creditAccount', $listAccounts[$k])->sum('palletsNumber');
                    $sumTransferWD = Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Withdrawal-Deposit')->where('debitAccount', $listAccounts[$k])->sum('palletsNumber');
//                    $idErrorNotSameNumberDW = Error::where('name', 'DW-WD_notSameNumber')->first()->id;

                    if ($sumTransferWD <> $loading->anz) {
                        foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Withdrawal-Deposit')->where('debitAccount', $listAccounts[$k])->get() as $transferQ2) {
                            $transferQ2->errors()->sync([$idErrorNotNumberLoadingOrder]);
                        }
                    } else {
                        foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Withdrawal-Deposit')->where('debitAccount', $listAccounts[$k])->get() as $transferQ2) {
                            $transferQ2->errors()->detach($idErrorNotNumberLoadingOrder);
                        }
                    }
                    if ($sumTransferDW <> $loading->anz) {
                        foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Deposit-Withdrawal')->where('creditAccount', $listAccounts[$k])->get() as $transferQ3) {
                            $transferQ3->errors()->sync([$idErrorNotNumberLoadingOrder]);
                        }
                    } else {
                        foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Deposit-Withdrawal')->where('creditAccount', $listAccounts[$k])->get() as $transferQ3) {
                            $transferQ3->errors()->detach($idErrorNotNumberLoadingOrder);
                        }
                    }

//                    if ($sumTransferWD <> $sumTransferDW && $sumTransferWD <> $loading->anz && $sumTransferDW == $loading->anz) {
//                        foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Deposit-Withdrawal')->where('creditAccount', $listAccounts[$k])->get() as $transferQ1) {
//                            $transferQ1->errors()->sync($idErrorNotSameNumberDW);
//                        }
//                        foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Withdrawal-Deposit')->where('debitAccount', $listAccounts[$k])->get() as $transferQ2) {
//                            $transferQ2->errors()->sync([$idErrorNotSameNumberDW, $idErrorNotNumberLoadingOrder]);
//                        }
//                    } elseif ($sumTransferWD <> $sumTransferDW && $sumTransferWD <> $loading->anz && $sumTransferDW <> $loading->anz) {
//                        foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Deposit-Withdrawal')->where('creditAccount', $listAccounts[$k])->get() as $transferQ3) {
//                            $transferQ3->errors()->sync([$idErrorNotSameNumberDW, $idErrorNotNumberLoadingOrder]);
//                        }
//                        foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Withdrawal-Deposit')->where('debitAccount', $listAccounts[$k])->get() as $transferQ4) {
//                            $transferQ4->errors()->sync([$idErrorNotSameNumberDW, $idErrorNotNumberLoadingOrder]);
//                        }
//                    } elseif ($sumTransferWD <> $sumTransferDW && $sumTransferWD == $loading->anz && $sumTransferDW <> $loading->anz) {
//                        foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Deposit-Withdrawal')->where('creditAccount', $listAccounts[$k])->get() as $transferQ5) {
//                            $transferQ5->errors()->sync([$idErrorNotSameNumberDW, $idErrorNotNumberLoadingOrder]);
//                        }
//                        foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Withdrawal-Deposit')->where('debitAccount', $listAccounts[$k])->get() as $transferQ6) {
//                            $transferQ6->errors()->sync($idErrorNotSameNumberDW);
//                        }
//                    } elseif ($sumTransferWD == $sumTransferDW && $sumTransferWD == $loading->anz) {
//                        foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Deposit-Withdrawal')->where('creditAccount', $listAccounts[$k])->get() as $transferQ7) {
//                            $transferQ7->errors()->detach($idErrorNotSameNumberDW);
//                            $transferQ7->errors()->detach($idErrorNotNumberLoadingOrder);
//                        }
//                        foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Withdrawal-Deposit')->where('debitAccount', $listAccounts[$k])->get() as $transferQ8) {
//                            $transferQ8->errors()->detach($idErrorNotSameNumberDW);
//                            $transferQ8->errors()->detach($idErrorNotNumberLoadingOrder);
//                        }
//                    } elseif ($sumTransferWD == $sumTransferDW && $sumTransferWD <> $loading->anz) {
//                        foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Deposit-Withdrawal')->where('creditAccount', $listAccounts[$k])->get() as $transferQ9) {
//                            $transferQ9->errors()->sync($idErrorNotNumberLoadingOrder);
//                        }
//                        foreach (Palletstransfer::where('loading_atrnr', $transferQ->loading_atrnr)->where('type', 'Withdrawal-Deposit')->where('debitAccount', $listAccounts[$k])->get() as $transferQ10) {
//                            $transferQ10->errors()->sync($idErrorNotNumberLoadingOrder);
//                        }
//                    }
                }
            }
        }

        //update errors on each transfer D only or W only associated to this loading
        $sumTransferDepositOnly = Palletstransfer::where('type', 'Deposit_Only')->where('loading_atrnr', $loading->atrnr)->sum('palletsNumber');
        $sumTransferWithdrawalOnly = Palletstransfer::where('type', 'Withdrawal_Only')->where('loading_atrnr', $loading->atrnr)->sum('palletsNumber');

        $listTransferDWonly = Palletstransfer::where(function ($q) {
            $q->where('type', 'Deposit_Only')->orWhere('type', 'Withdrawal_Only');
        })->where('loading_atrnr', $loading->atrnr)->get();

        foreach ($listTransferDWonly as $transferDWonly) {
            if ($sumTransferDepositOnly <> $sumTransferWithdrawalOnly) {
                $transferDWonly->errors()->sync($idErrorNotSameNumber);
            } else {
                $transferDWonly->errors()->detach($idErrorNotSameNumber);
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

    /**
     * show the add form to add a subloading
     * @param $atrnr
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public
    function showAdd($atrnr)
    {
        $loading = Loading::where('atrnr', $atrnr)->first();
        return view('loadings.addSubloading', compact('loading'));
    }

    /**
     * add a subloading
     * @param $atrnr
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public
    function add($atrnr)
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
