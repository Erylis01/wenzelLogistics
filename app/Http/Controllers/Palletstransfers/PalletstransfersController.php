<?php

namespace App\Http\Controllers;

use App\Document;
use App\Error;
use App\Loading;
use App\Palletsaccount;
use App\Palletstransfer;
use App\Truck;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PalletstransfersController extends Controller
{
    /**
     * Display the content - only the last 2 months
     *
     * @return \Illuminate\Http\Response
     */
    public function showAll(Request $request, $type)
    {
        //data search query
        $searchQuery = $request->get('search');
        $searchQueryArray = explode(' ', $searchQuery);
        $searchColumns = $request->get('searchColumns');
        $listColumns = ['id', 'date', 'type', 'creditAccount', 'debitAccount', 'palletsNumber', 'state'];

        if (Auth::check()) {
            if($type=='all'){
                $query = DB::table('Palletstransfers');
            }elseif($type=='debt'){
                $query = DB::table('Palletstransfers')->where('type', 'Debt');
            }

            if (request()->has('sortby') && request()->has('order')) {
                $sortby = $request->get('sortby'); // Order by what column?
                $order = $request->get('order'); // Order direction: asc or desc
                $searchColumnsString = $request->get('searchColumnsString');;
                $searchColumns = explode('-', $searchColumnsString);
                if (isset($searchQuery) && $searchQuery <> '') {
                    if (in_array('ALL', explode('-', $searchColumnsString))) {
                        $query->where(function ($q) use ($searchQueryArray, $listColumns) {
                            foreach ($listColumns as $column) {
                                foreach ($searchQueryArray as $searchQ) {
                                    $q->orWhere($column, 'LIKE', '%' . $searchQ . '%');
                                }
                            }
                        });
                    } else {
                        $query->where(function ($q) use ($searchQueryArray, $searchColumns) {
                            foreach ($searchColumns as $column) {
                                foreach ($searchQueryArray as $searchQ) {
                                    $q->orWhere($column, 'LIKE', '%' . $searchQ . '%');
                                }
                            }
                        });
                    }
                }
                $count = count($query->get());
                $listPalletstransfers = $query->orderBy($sortby, $order)->paginate(10);
                $links = $listPalletstransfers->appends(['sortby' => $sortby, 'order' => $order, 'search' => $searchQuery, 'searchColumnsString' => $searchColumnsString])->render();
            } else {
                if (isset($searchQuery) && $searchQuery <> '') {
                    $searchColumnsString = implode('-', $searchColumns);
                    if (in_array('ALL', $searchColumns)) {
                        $query->where(function ($q) use ($searchQueryArray, $listColumns) {
                            foreach ($listColumns as $column) {
                                foreach ($searchQueryArray as $searchQ) {
                                    $q->orWhere($column, 'LIKE', '%' . $searchQ . '%');
                                }
                            }
                        });
                    } else {
                        $query->where(function ($q) use ($searchQueryArray, $searchColumns) {
                            foreach ($searchColumns as $column) {
                                foreach ($searchQueryArray as $searchQ) {
                                    $q->orWhere($column, 'LIKE', '%' . $searchQ . '%');
                                }
                            }
                        });
                    }
                    $count = count($query->get());
                    $listPalletstransfers = $query->orderBy('id', 'asc')->paginate(10);
                    $links = $listPalletstransfers->appends(['search' => $searchQuery, 'searchColumns' => $searchColumns])->render();
                } else {
                    $count = count($query->get());
                    $listPalletstransfers = $query->orderBy('id', 'asc')->paginate(10);
                    $links = '';
                }
            }

            return view('palletstransfers.allPalletstransfers', compact('type','listPalletstransfers', 'sortby', 'order', 'links', 'count', 'searchColumns', 'searchQuery', 'searchQueryArray', 'listColumns', 'searchColumnsString'));
        } else {
            return view('auth.login');
        }
    }

    /**
     * find the errors currently attach to this transfer
     * @param $transfer
     * @return $filesNames
     */
    public static function actualErrors($transfer)
    {
        $actualErrors_Palletstransfers = DB::table('error_palletstransfer')->where('palletstransfer_id', $transfer->id)->get();
        $errors = [];
        if (!$actualErrors_Palletstransfers->isEmpty()) {
            foreach ($actualErrors_Palletstransfers as $actualError) {
                $errors[] = Error::where('id', $actualError->error_id)->first();
            }
        }
        return $errors;
    }

    /**
     * show the add form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAdd()
    {
        if (Auth::check()) {
            $listPalletsAccounts = Palletsaccount::where('type', 'Network')->orWhere('type', 'Other')->orderBy('name', 'asc')->get();
            $listTrucksAccounts = Truck::orderBy('name', 'asc')->get();
            $listPalletstransfersNormal = Palletstransfer::where('type', 'Deposit-Withdrawal')->orWhere('type', 'Withdrawal-Deposit')->orWhere('type', 'Deposit_Only')->orWhere('type', 'Withdrawal_Only')->orderBy('id', 'asc')->get();

            $date = Carbon::now()->format('Y-m-d');
            foreach (Loading::where('pt', 'JA')->orderBy('atrnr', 'asc')->get() as $loading) {
                $listAtrnr[] = $loading->atrnr;
            }
            return view('palletstransfers.addPalletstransfer', compact('listPalletsAccounts', 'listTrucksAccounts', 'listPalletstransfersNormal', 'date', 'listAtrnr'));
        } else {
            return view('auth.login');
        }
    }

    /**
     * add a new pallets transfer to the list
     */
    public function add()
    {
        $listPalletsAccounts = Palletsaccount::where('type', 'Network')->orWhere('type', 'Other')->orderBy('name', 'asc')->get();
        $listTrucksAccounts = Truck::orderBy('name', 'asc')->get();
        $listPalletstransfersNormal = Palletstransfer::where('type', 'Deposit-Withdrawal')->orWhere('type', 'Withdrawal-Deposit')->orWhere('type', 'Deposit_Only')->orWhere('type', 'Withdrawal_Only')->orderBy('id', 'asc')->get();

        foreach (Loading::where('pt', 'JA')->orderBy('atrnr', 'asc')->get() as $loading) {
            $listAtrnr[] = $loading->atrnr;
        }
        $type = Input::get('type');
        $details = Input::get('details');
        $loading_atrnr = Input::get('loading_atrnr');
        if (isset($loading_atrnr)) {
            $anz = Loading::where('atrnr', $loading_atrnr)->first()->anz;
        }
        $date = Input::get('date');
        $creditAccount = Input::get('creditAccount');
        $debitAccount = Input::get('debitAccount');
        $palletsNumber = Input::get('palletsNumber');
        //only for some correcting transfer
        $normalTransferAssociated = Input::get('normalTransferAssociated');
        //add a transfer then redirect with a pop up modal to validate the adding
        $addPalletstransfer = Input::get('addPalletstransfer');
        //validate the adding
        $okSubmitAddModal = Input::get('okSubmitAddModal');
        //cancel the adding
        $closeSubmitAddModal = Input::get('closeSubmitAddModal');

        if ($type == 'Purchase_Ext') {
            $rules = array(
                'creditAccount' => 'required',
            );
            $debitAccount = null;
            $actualTheoricalCreditPalletsNumber = $this->actualTheoricalPalletsNumber($creditAccount, $debitAccount)[1];
        } elseif ($type == 'Sale_Ext') {
            $rules = array(
                'debitAccount' => 'required',
            );
            $creditAccount = null;
            $actualTheoricalDebitPalletsNumber = $this->actualTheoricalPalletsNumber($creditAccount, $debitAccount)[0];
        } elseif ($type == 'Deposit-Withdrawal' || $type == 'Withdrawal-Deposit') {
            $creditAccount2 = $debitAccount;
            $debitAccount2 = $creditAccount;
            $palletsNumber2 = Input::get('palletsNumber2');

            $rules = array(
                'creditAccount' => 'required',
                'debitAccount' => 'required',
                'loading_atrnr' => 'required',
            );
            $actualTheoricalCreditPalletsNumber = $this->actualTheoricalPalletsNumber($creditAccount, $debitAccount)[1];
            $actualTheoricalDebitPalletsNumber = $this->actualTheoricalPalletsNumber($creditAccount, $debitAccount)[0];
            $actualTheoricalDebitPalletsNumber2 = $this->actualTheoricalPalletsNumber($creditAccount2, $debitAccount2)[0];
            $actualTheoricalCreditPalletsNumber2 = $this->actualTheoricalPalletsNumber($creditAccount2, $debitAccount2)[1];
        } elseif ($type == 'Deposit_Only' || $type == 'Withdrawal_Only') {
            $rules = array(
                'creditAccount' => 'required',
                'debitAccount' => 'required',
                'loading_atrnr' => 'required',
            );
            $actualTheoricalCreditPalletsNumber = $this->actualTheoricalPalletsNumber($creditAccount, $debitAccount)[1];
            $actualTheoricalDebitPalletsNumber = $this->actualTheoricalPalletsNumber($creditAccount, $debitAccount)[0];
        } elseif ($type == 'Purchase-Sale' || $type == 'Sale-Purchase') {
            $rules = array(
                'creditAccount' => 'required',
                'debitAccount' => 'required',
                'normalTransferAssociated' => 'required',
            );
            $actualTheoricalCreditPalletsNumber = $this->actualTheoricalPalletsNumber($creditAccount, $debitAccount)[1];
            $actualTheoricalDebitPalletsNumber = $this->actualTheoricalPalletsNumber($creditAccount, $debitAccount)[0];
        } else {
            $rules = array(
                'creditAccount' => 'required',
                'debitAccount' => 'required',
            );
            $actualTheoricalCreditPalletsNumber = $this->actualTheoricalPalletsNumber($creditAccount, $debitAccount)[1];
            $actualTheoricalDebitPalletsNumber = $this->actualTheoricalPalletsNumber($creditAccount, $debitAccount)[0];
        }

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            session()->flash('errorFields', "The field(s) has(ve) not been filled as expected");
            return view('palletstransfers.addPalletstransfer', compact('date', 'type', 'creditAccount', 'debitAccount', 'palletsNumber', 'listPalletsAccounts', 'listTrucksAccounts', 'listPalletstransfersNormal', 'details', 'loading_atrnr', 'listAtrnr', 'normalTransferAssociated'));
        } else {
            if (isset($addPalletstransfer)) {
                session()->flash('palletsNumber', $palletsNumber);
                if (isset($creditAccount)) {
                    $this->displayCreditAccount($creditAccount, null);
                    session()->flash('palletsNumberCreditAccount', $actualTheoricalCreditPalletsNumber);
                }
                if (isset($debitAccount)) {
                    $this->displayDebitAccount($debitAccount, null);
                    session()->flash('palletsNumberDebitAccount', $actualTheoricalDebitPalletsNumber);
                }
                if (isset($creditAccount2) && isset($debitAccount2) && isset($palletsNumber2)) {
                    $this->displayCreditAccount($creditAccount2, 2);
                    $this->displayDebitAccount($debitAccount2, 2);
                    session()->flash('palletsNumber2', $palletsNumber2);
                    session()->flash('palletsNumberCreditAccount2', $actualTheoricalCreditPalletsNumber2);
                    session()->flash('palletsNumberDebitAccount2', $actualTheoricalDebitPalletsNumber2);
                }
                return view('palletstransfers.addPalletstransfer', compact('anz', 'date', 'type', 'creditAccount', 'debitAccount', 'palletsNumber', 'creditAccount2', 'debitAccount2', 'palletsNumber2', 'addPalletstransfer', 'listPalletsAccounts', 'listTrucksAccounts', 'listPalletstransfersNormal', 'details', 'loading_atrnr', 'listAtrnr', 'normalTransferAssociated'));
            } elseif (isset($okSubmitAddModal)) {
                $debitAccountTransfer = $this->namesAccounts($creditAccount, $debitAccount, null)[0];
                $creditAccountTransfer = $this->namesAccounts($creditAccount2, $debitAccount2, null)[1];
                if (isset($creditAccount2) && isset($debitAccount2)) {
                    $debitAccountTransfer2 = $this->namesAccounts($creditAccount2, $debitAccount2, null)[0];
                    $creditAccountTransfer2 = $this->namesAccounts($creditAccount2, $debitAccount2, null)[1];
                } else {
                    $debitAccountTransfer2 = null;
                    $creditAccountTransfer2 = null;
                }
                $this->createTransfer($loading, $type, $date, $details, $creditAccountTransfer, $debitAccountTransfer, $palletsNumber, $creditAccountTransfer2, $debitAccountTransfer2, $palletsNumber2, $normalTransferAssociated);

                if (isset($creditAccount)) {
                    $this->updatePalletsAccountCredit($creditAccount, $actualTheoricalCreditPalletsNumber, $palletsNumber);
                }
                if (isset($debitAccount)) {
                    $this->updatePalletsAccountDebit($debitAccount, $actualTheoricalDebitPalletsNumber, $palletsNumber);
                }
                if (isset($creditAccount2) && isset($debitAccount2) && isset($palletsNumber2)) {
                    $this->updatePalletsAccountCredit($creditAccount2, $actualTheoricalCreditPalletsNumber2, $palletsNumber2);
                    $this->updatePalletsAccountDebit($debitAccount2, $actualTheoricalDebitPalletsNumber2, $palletsNumber2);
                }
                if (isset($loading_atrnr)) {
                    $this->state($loading, Palletstransfer::where('loading_atrnr', $loading_atrnr)->get());
                }
                if ($type == 'Deposit_Only') {
                    session()->flash('sumTransfersDepositOnly', Palletstransfer::where('type', 'Deposit_Only')->sum('palletsNumber') + $palletsNumber);
                    session()->flash('sumTransfersWithdrawalOnly', Palletstransfer::where('type', 'Withdrawal_Only')->sum('palletsNumber'));
                } elseif ($type == 'Withdrawal_Only') {
                    session()->flash('sumTransfersDepositOnly', Palletstransfer::where('type', 'Deposit_Only')->sum('palletsNumber'));
                    session()->flash('sumTransfersWithdrawalOnly', Palletstransfer::where('type', 'Withdrawal_Only')->sum('palletsNumber') + $palletsNumber);
                }
                session()->flash('messageAddPalletstransfer', 'Successfully added new pallets transfer');
                return redirect('/allPalletstransfers');
            } elseif (isset($closeSubmitAddModal)) {
                return redirect()->back();
            }
        }
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
     * @param $index
     */
    public function displayCreditAccount($creditAccount, $index)
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
    }

    /**
     * write properly credit and debit account for the modals to confirm transfers
     * @param $debitAccount
     * @param $index
     */
    public function displayDebitAccount($debitAccount, $index)
    {
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
    public function createTransfer($type, $date, $details, $creditAccountTransfer, $debitAccountTransfer, $palletsNumber, $creditAccountTransfer2, $debitAccountTransfer2, $palletsNumber2, $normalTransferAssociated, $loading_atrnr)
    {
        if ($type == 'Deposit-Withdrawal') {
            if (!isset($palletsNumber2)) {
                Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading_atrnr]);
                Palletstransfer::create(['date' => $date, 'type' => 'Withdrawal-Deposit', 'details' => $details, 'creditAccount' => $creditAccountTransfer2, 'debitAccount' => $debitAccountTransfer2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading_atrnr, 'state' => 'Untreated']);
            } else {
                Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading_atrnr]);
                Palletstransfer::create(['date' => $date, 'type' => 'Withdrawal-Deposit', 'details' => $details, 'creditAccount' => $creditAccountTransfer2, 'debitAccount' => $debitAccountTransfer2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading_atrnr]);
            }
        } elseif ($type == 'Withdrawal-Deposit') {
            if (!isset($palletsNumber2)) {
                Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading_atrnr]);
                Palletstransfer::create(['date' => $date, 'type' => 'Deposit-Withdrawal', 'details' => $details, 'creditAccount' => $creditAccountTransfer2, 'debitAccount' => $debitAccountTransfer2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading_atrnr, 'state' => 'Untreated']);
            } else {
                Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading_atrnr]);
                Palletstransfer::create(['date' => $date, 'type' => 'Deposit-Withdrawal', 'details' => $details, 'creditAccount' => $creditAccountTransfer2, 'debitAccount' => $debitAccountTransfer2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading_atrnr]);
            }
        } elseif ($type == 'Deposit_Only' || $type == 'Withdrawal_Only') {
            Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading_atrnr]);
        } elseif ($type == 'Sale-Purchase' || $type == 'Purchase-Sale' || $type == 'Other') {
            Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading_atrnr, 'normalTransferAssociated' => $normalTransferAssociated]);
        } elseif ($type == 'Sale_Ext' || $type == 'Purchase_Ext') {
            Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading_atrnr]);
        }
    }

    /**
     * update pallets numbers on pallets account according to the type of account
     * @param $creditAccount
     * @param $actualTheoricalCreditPalletsNumber
     * @param $palletsNumber
     */
    public function updatePalletsAccountCredit($creditAccount, $actualTheoricalCreditPalletsNumber, $palletsNumber)
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
    }

    /**
     * update pallets numbers on pallets account according to the type of account
     * @param $debitAccount
     * @param $actualTheoricalDebitPalletsNumber
     * @param $palletsNumber
     */
    public function updatePalletsAccountDebit($debitAccount, $actualTheoricalDebitPalletsNumber, $palletsNumber)
    {
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
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showDetails($id)
    {
        if (Auth::check()) {
            $transfer = Palletstransfer::where('id', $id)->first();
            $listPalletsAccounts = Palletsaccount::where('type', 'Network')->orWhere('type', 'Other')->orderBy('name', 'asc')->get();
            $listTrucksAccounts = Truck::orderBy('name', 'asc')->get();
            $listPalletstransfersNormal = Palletstransfer::where('type', 'Deposit-Withdrawal')->orWhere('type', 'Withdrawal-Deposit')->orWhere('type', 'Deposit_Only')->orWhere('type', 'Withdrawal_Only')->orderBy('id', 'asc')->get();

            foreach (Loading::where('pt', 'JA')->orderBy('atrnr', 'asc')->get() as $loading) {
                $listAtrnr[] = $loading->atrnr;
            }
            $filesNames = $this->actualDocuments($id);
            $errorsTransfer = $this->actualErrors($transfer);
            return view('palletstransfers.detailsPalletstransfer', compact('transfer', 'errorsTransfer', 'listPalletsAccounts', 'listTrucksAccounts', 'listPalletstransfersNormal', 'listAtrnr', 'filesNames'));
        } else {
            return view('auth.login');
        }
    }

    /**
     * update the pallets transfer n° ID
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $transfer = Palletstransfer::where('id', $id)->first();

        //buttons
        $upload = Input::get('upload');
        $update = Input::get('update');
        $deleteDocument = Input::get('deleteDocument');
        $okSubmitUpdateModal = Input::get('okSubmitUpdateModal');
        $okSubmitUpdateValidateModal = Input::get('okSubmitUpdateValidateModal');
        $closeSubmitUpdateModal = Input::get('closeSubmitUpdateModal');
        $closeSubmitValidateUpdateModal = Input::get('closeSubmitValidateUpdateModal');
        $showAddCorrectingTransfer = Input::get('showAddCorrectingTransfer');

        //data
        $documents = $request->file('documentsTransfer');
        $date = Input::get('date');
        $type = Input::get('type');
        $details = Input::get('details');
        $loading_atrnr = Input::get('loading_atrnr');
        $creditAccount = Input::get('creditAccount');
        $debitAccount = Input::get('debitAccount');
        $palletsNumber = Input::get('palletsNumber');
        $validate = Input::get('validate');
        $state = $transfer->state;
        //only for some correcting transfer
        $normalTransferAssociated = Input::get('normalTransferAssociated');

        $listPalletsAccounts = Palletsaccount::where('type', 'Network')->orWhere('type', 'Other')->orderBy('name', 'asc')->get();
        $listTrucksAccounts = Truck::orderBy('name', 'asc')->get();
        $listPalletstransfersNormal = Palletstransfer::where('type', 'Deposit-Withdrawal')->orWhere('type', 'Withdrawal-Deposit')->orWhere('type', 'Deposit_Only')->orWhere('type', 'Withdrawal_Only')->orderBy('id', 'asc')->get();

        foreach (Loading::where('pt', 'JA')->orderBy('atrnr', 'asc')->get() as $loading) {
            $listAtrnr[] = $loading->atrnr;
        }

        if ($type == 'Purchase_Ext') {
            $rules = array(
                'creditAccount' => 'required',
            );
            $debitAccount = null;
        } elseif ($type == 'Sale_Ext') {
            $rules = array(
                'debitAccount' => 'required',
            );
            $creditAccount = null;
        } elseif ($type == 'Deposit-Withdrawal' || $type == 'Withdrawal-Deposit') {
            $rules = array(
                'creditAccount' => 'required',
                'debitAccount' => 'required',
                'loading_atrnr' => 'required',
            );
        } elseif ($type == 'Deposit_Only' || $type == 'Withdrawal_Only') {
            $rules = array(
                'creditAccount' => 'required',
                'debitAccount' => 'required',
                'loading_atrnr' => 'required',
            );
        } elseif ($type == 'Purchase-Sale' || $type == 'Sale-Purchase') {
            $rules = array(
                'creditAccount' => 'required',
                'debitAccount' => 'required',
                'normalTransferAssociated' => 'required',
            );
        } else {
            $rules = array(
                'creditAccount' => 'required',
                'debitAccount' => 'required',
            );
        }
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            session()->flash('errorFields', "The field(s) has(ve) not been filled as expected. REFILL !");

        } elseif ($debitAccount == $creditAccount || (isset($debitAccount2) && isset($creditAccount2) && $debitAccount2 == $creditAccount2) || (isset($debitAccount3) && isset($creditAccount3) && $debitAccount3 == $creditAccount3)) {
            session()->flash('errorFields', "The fields have not been filled as expected : debit account and credit account must be different");
            return redirect()->back();
        } else {
            if (isset($upload)) {
                $filesNames = $this->upload($documents, $transfer);
                if (!empty($filesNames) && $validate == 'true') {
                    $state = 'Complete Validated';
                } elseif (!empty($filesNames) && ($validate == 'false' || $validate == null)) {
                    $state = 'Complete';
                } elseif (empty($filesNames)) {
                    $state = 'Waiting documents';
                }
                Palletstransfer::where('id', $id)->update(['state' => $state]);
                if (isset($loading_atrnr)) {
                    $this->state(Loading::where('atrnr', $loading_atrnr)->where('pt', 'JA')->first(), Palletstransfer::where('loading_atrnr', $loading_atrnr)->get());
                }
                return redirect()->back();
            } elseif (isset($showAddCorrectingTransfer)) {
                $normalTransferAssociated = $showAddCorrectingTransfer;
                $transferNormal = Palletstransfer::where('id', $showAddCorrectingTransfer)->first();
                if ($transferNormal->palletsNumber <= $loading->anz) {
                    $palletsNumber = $loading->anz - $transferNormal->palletsNumber;
                } else {
                    $palletsNumber = $transferNormal->palletsNumber - $loading->anz;
                }
                $creditAccountCorr = $transferNormal->creditAccount;
                $debitAccountCorr = $transferNormal->debitAccount;
                $date = Carbon::now()->format('Y-m-d');
                return view('palletstransfers.addPalletstransfer', compact('listPalletsAccounts', 'listTrucksAccounts', 'listPalletstransfersNormal', 'date', 'listAtrnr', 'normalTransferAssociated', 'palletsNumber', 'creditAccountCorr', 'debitAccountCorr'));

            } elseif (isset($update)) {
                if ($state == 'Complete Validated') {
                    $this->inverseRealPalletsNumber($transfer);
                }
                $filesNames = $this->actualDocuments($id);

                session()->put('actualCreditAccount', $transfer->creditAccount);
                session()->put('actualDebitAccount', $transfer->debitAccount);
                session()->put('actualPalletsNumber', $transfer->palletsNumber);
                session()->put('actualType', $transfer->type);
                session()->put('actualLoadingAtrnr', $transfer->loading_atrnr);
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
                    return redirect()->back();
                } elseif ($transfer->validate == 1 && $validate <> null && $validate == 'true') {
                    return redirect()->back();
                } else {
                    if (isset($creditAccount)) {
                        $creditAccountTransfer = $this->namesAccounts($creditAccount, $debitAccount, 1)[1];
                        Palletstransfer::where('id', $transfer->id)->update(['creditAccount' => $creditAccountTransfer]);
                    }
                    if (isset($debitAccount)) {
                        $debitAccountTransfer = $this->namesAccounts($creditAccount, $debitAccount, 1)[0];
                        Palletstransfer::where('id', $transfer->id)->update(['debitAccount' => $debitAccountTransfer]);
                    }
//                    if (($transfer->type == 'Deposit-Withdrawal' || $transfer->type == 'Withdrawal-Deposit') && ($type <> 'Deposit-Withdrawal' || $type <> 'Withdrawal-Deposit')) {
//                        $transfer->errors()->detach(Error::where('name', 'DW-WD_notSame')->first()->id);
//                        $transfer->errors()->detach(Error::where('name', 'DW-WD_notNumberLoadingOrder')->first()->id);
//                    } elseif (($transfer->type == 'Deposit_Only' || $transfer->type == 'Withdrawal_Only') && ($type <> 'Deposit_Only' || $type <> 'Withdrawal_Only')) {
//                        $transfer->errors()->detach(Error::where('name', 'Donly-Wonly_notSameNumber')->first()->id);
//                    } elseif (($transfer->type == 'Sale-Purchase' || $transfer->type == 'Purchase-Sale') && ($type <> 'Sale-Purchase' || $type <> 'Purchase-Sale')) {
//                        $transfer->errors()->detach(Error::where('name', 'SP-PS_notSameNumber')->first()->id);
//                        $transfer->errors()->detach(Error::where('name', 'Correcting_notCompleteNormal')->first()->id);
//                    }

                    Palletstransfer::where('id', $transfer->id)->update(['type' => $type, 'details' => $details, 'loading_atrnr' => $loading_atrnr, 'palletsNumber' => $palletsNumber, 'date' => $date, 'normalTransferAssociated' => $normalTransferAssociated]);

                    if ($validate <> null && $validate == 'true') {
                        Palletstransfer::where('id', $transfer->id)->update(['validate' => true]);
                    } elseif ($validate <> null && $validate == 'false') {
                        Palletstransfer::where('id', $transfer->id)->update(['validate' => false]);
                    }
                    $transfer = Palletstransfer::where('id', $id)->first();
                    if (isset($loading_atrnr)) {
                        $this->state(Loading::where('atrnr', $loading_atrnr)->where('pt', 'JA')->first(), Palletstransfer::where('loading_atrnr', $loading_atrnr)->get());
                    }
                    $anz = Loading::where('atrnr', $loading_atrnr)->first()->anz;
                    return view('palletstransfers.detailsPalletstransfer', compact('anz', 'transfer', 'listPalletsAccounts', 'listTrucksAccounts', 'listPalletstransfersNormal', 'listAtrnr', 'update', 'filesNames'));
                }
            } elseif (isset($deleteDocument)) {
                $this->deleteDocument($transfer, $deleteDocument);
                if (isset($loading_atrnr)) {
                    $this->state(Loading::where('atrnr', $loading_atrnr)->where('pt', 'JA')->first(), Palletstransfer::where('loading_atrnr', $loading_atrnr)->get());
                }
                return redirect()->back();
            } elseif (isset($okSubmitUpdateModal)) {
                $filesNames = $this->actualDocuments($id);
                $actualCreditAccount = session('actualCreditAccount');
                $actualDebitAccount = session('actualDebitAccount');
                $actualPalletsNumber = session('actualPalletsNumber');
                $this->updateInfo($transfer, $actualPalletsNumber, $actualCreditAccount, $actualDebitAccount, $filesNames);
                $transfer = Palletstransfer::where('id', $id)->first();
                if (isset($loading_atrnr)) {
                    $this->state(Loading::where('atrnr', $loading_atrnr)->where('pt', 'JA')->first(), Palletstransfer::where('loading_atrnr', $loading_atrnr)->get());
                }

                if ($transfer->type == 'Deposit_Only') {
                    session()->flash('sumTransfersDepositOnly', Palletstransfer::where('type', 'Deposit_Only')->where('loading_atrnr', $loading_atrnr)->sum('palletsNumber') + $transfer->palletsNumber);
                    session()->flash('sumTransfersWithdrawalOnly', Palletstransfer::where('type', 'Withdrawal_Only')->where('loading_atrnr', $loading_atrnr)->sum('palletsNumber'));
                } elseif ($transfer->type == 'Withdrawal_Only') {
                    session()->flash('sumTransfersDepositOnly', Palletstransfer::where('type', 'Deposit_Only')->where('loading_atrnr', $loading_atrnr)->sum('palletsNumber'));
                    session()->flash('sumTransfersWithdrawalOnly', Palletstransfer::where('type', 'Withdrawal_Only')->where('loading_atrnr', $loading_atrnr)->sum('palletsNumber') + $transfer->palletsNumber);
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
                    return view('palletstransfers.detailsPalletstransfer', compact('anz', 'transfer', 'listPalletsAccounts', 'listTrucksAccounts', 'listPalletstransfersNormal', 'listAtrnr', 'update', 'filesNames', 'okSubmitPalletsModal'));
                } else {
                    session()->pull('actualCreditAccount');
                    session()->pull('actualDebitAccount');
                    session()->pull('actualPalletsNumber');
                    session()->pull('actualType');
                    session()->pull('actualDetails');
                    session()->pull('actualDate');
                    session()->pull('actualNormalTransferAssociated');
                    session()->pull('actualValidate');
                    session()->pull('actualLoadingAtrnr', $transfer->loading_atrnr);
                    return redirect()->back();
                }
            } elseif (isset($closeSubmitUpdateModal)) {
                $actualCreditAccount = session('actualCreditAccount');
                $actualDebitAccount = session('actualDebitAccount');
                $actualPalletsNumber = session('actualPalletsNumber');
                $actualType = session('actualType');
                $actualDetails = session('actualDetails');
                $actualLoadingAtrnr = session('actualLoadingAtrnr');
                $actualDate = session('actualDate');
                $actualValidate = session('actualValidate');
                $actualNormalTransferAssociated = session('actualNormalTransferAssociated');

                if (isset($actualDebitAccount)) {
                    Palletstransfer::where('id', $closeSubmitUpdateModal)->update(['debitAccount' => $actualDebitAccount]);
                }
                if (isset($actualCreditAccount)) {
                    Palletstransfer::where('id', $closeSubmitUpdateModal)->update(['creditAccount' => $actualCreditAccount]);
                }
                Palletstransfer::where('id', $closeSubmitUpdateModal)->update(['validate' => $actualValidate, 'type' => $actualType, 'details' => $actualDetails, 'palletsNumber' => $actualPalletsNumber, 'date' => $actualDate, 'normalTransferAssociated' => $actualNormalTransferAssociated, 'loading_atrnr' => $actualLoadingAtrnr]);

                $filesNames = $this->actualDocuments($closeSubmitUpdateModal);
                if (!empty($filesNames) && $actualValidate == 1) {
                    Palletstransfer::where('id', $closeSubmitUpdateModal)->update(['state' => 'Complete Validated']);
                } elseif (!empty($filesNames) && $actualValidate == 0) {
                    Palletstransfer::where('id', $closeSubmitUpdateModal)->update(['state' => 'Complete']);
                } elseif (empty($filesNames)) {
                    Palletstransfer::where('id', $closeSubmitUpdateModal)->update(['state' => 'Waiting documents']);
                }

                if (isset($loading_atrnr)) {
                    $this->state(Loading::where('atrnr', $loading_atrnr)->where('pt', 'JA')->first(), Palletstransfer::where('loading_atrnr', $loading_atrnr)->get());
                }
                session()->pull('actualCreditAccount');
                session()->pull('actualDebitAccount');
                session()->pull('actualPalletsNumber');
                session()->pull('actualType');
                session()->pull('actualDetails');
                session()->pull('actualDate');
                session()->pull('actualValidate');
                session()->pull('actualNormalTransferAssociated');
                session()->pull('actualLoadingAtrnr');
                return redirect()->back();
            } elseif (isset($closeSubmitValidateUpdateModal)) {
                $actualCreditAccount = session('actualCreditAccount');
                $actualDebitAccount = session('actualDebitAccount');
                $actualPalletsNumber = session('actualPalletsNumber');
                $actualType = session('actualType');
                $actualDetails = session('actualDetails');
                $actualLoadingAtrnr = session('actualLoadingAtrnr');
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
                    Palletstransfer::where('id', $closeSubmitValidateUpdateModal)->update(['debitAccount' => $actualDebitAccount]);
                }
                if (isset($actualCreditAccount)) {
                    Palletstransfer::where('id', $closeSubmitValidateUpdateModal)->update(['creditAccount' => $actualCreditAccount]);
                }
                Palletstransfer::where('id', $closeSubmitValidateUpdateModal)->update(['validate' => $actualValidate, 'type' => $actualType, 'details' => $actualDetails, 'palletsNumber' => $actualPalletsNumber, 'date' => $actualDate, 'normalTransferAssociated' => $actualNormalTransferAssociated, 'loading_atrnr' => $actualLoadingAtrnr]);

                $filesNames = $this->actualDocuments($closeSubmitValidateUpdateModal);
                if (!empty($filesNames) && $actualValidate == 1) {
                    Palletstransfer::where('id', $closeSubmitValidateUpdateModal)->update(['state' => 'Complete Validated']);
                } elseif (!empty($filesNames) && $actualValidate == 0) {
                    Palletstransfer::where('id', $closeSubmitValidateUpdateModal)->update(['state' => 'Complete']);
                } elseif (empty($filesNames)) {
                    Palletstransfer::where('id', $closeSubmitValidateUpdateModal)->update(['state' => 'Waiting documents']);
                }

                if (isset($loading_atrnr)) {
                    $this->state(Loading::where('atrnr', $loading_atrnr)->where('pt', 'JA')->first(), Palletstransfer::where('loading_atrnr', $loading_atrnr)->get());
                }
                session()->pull('actualCreditAccount');
                session()->pull('actualDebitAccount');
                session()->pull('actualPalletsNumber');
                session()->pull('actualType');
                session()->pull('actualDetails');
                session()->pull('actualDate');
                session()->pull('actualValidate');
                session()->pull('actualNormalTransferAssociated');
                session()->pull('actualLoadingAtrnr');
                return redirect()->back();
            } elseif (isset($okSubmitUpdateValidateModal)) {
//                $actualCreditAccount = session('actualCreditAccount');
//                $actualDebitAccount = session('actualDebitAccount');
//                $actualPalletsNumber = session('actualPalletsNumber');
//                $actualType = session('actualType');
//                $actualDetails = session('actualDetails');
//                $actualDate = session('actualDate');
//                $actualValidate = session('actualValidate');
//                $actualNormalTransferAssociated = session('actualNormalTransferAssociated');
//
//                if (isset($actualDebitAccount)) {
//                    Palletstransfer::where('id', $id)->update(['debitAccount' => $actualDebitAccount]);
//                }
//                if (isset($actualCreditAccount)) {
//                    Palletstransfer::where('id', $id)->update(['creditAccount' => $actualCreditAccount]);
//                }
//                Palletstransfer::where('id', $id)->update(['validate' => $actualValidate, 'type' => $actualType, 'details' => $actualDetails, 'palletsNumber' => $actualPalletsNumber, 'date' => $actualDate, 'normalTransferAssociated' => $actualNormalTransferAssociated]);
//
//                $filesNames = $this->actualDocuments($id);
//                if (!empty($filesNames) && $actualValidate == 1) {
//                    Palletstransfer::where('id', $id)->update(['state' => 'Complete Validated']);
//                } elseif (!empty($filesNames) && $actualValidate == 0) {
//                    Palletstransfer::where('id', $id)->update(['state' => 'Complete']);
//                } elseif (empty($filesNames)) {
//                    Palletstransfer::where('id', $id)->update(['state' => 'Waiting documents']);
//                }
                $transfer = Palletstransfer::where('id', $okSubmitUpdateValidateModal)->first();

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

                if (isset($loading_atrnr)) {
                    $this->state(Loading::where('atrnr', $loading_atrnr)->where('pt', 'JA')->first(), Palletstransfer::where('loading_atrnr', $loading_atrnr)->get());
                }
                session()->flash('messageUpdateValidatePalletstransfer', 'VALIDATE ! Successfully updated and validated pallets transfer');
                session()->pull('actualCreditAccount');
                session()->pull('actualDebitAccount');
                session()->pull('actualPalletsNumber');
                session()->pull('actualType');
                session()->pull('actualDetails');
                session()->pull('actualLoadingAtrnr');
                session()->pull('actualDate');
                session()->pull('actualValidate');
                session()->pull('actualNormalTransferAssociated');
                return redirect()->back();
            }
        }
    }


    /**
     * delete the transfer from the database
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete($id)
    {
        if (Palletstransfer::where('normalTransferAssociated', $id)->first() <> null) {
            $idAssociated = Palletstransfer::where('normalTransferAssociated', $id)->first()->id;
            $this->delete($idAssociated);
        }

        $transfer = Palletstransfer::where('id', $id)->first();
        $loading_atrnr = $transfer->loading_atrnr;

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

        $state = $transfer->state;
        if ($state == 'Complete Validated') {
            $this->inverseRealPalletsNumber($transfer);
        }

        $actualDocuments_Palletstransfers = DB::table('document_palletstransfer')->where('palletstransfer_id', $id)->get();
        $actualDocuments = [];
        if (!$actualDocuments_Palletstransfers->isEmpty()) {
            foreach ($actualDocuments_Palletstransfers as $actualDoc) {
                $actualDocuments[] = Document::where('id', $actualDoc->document_id)->first();
            }
            foreach ($actualDocuments as $actDoc) {
                $actDoc->palletstransfers()->detach($id);
                $path = '/proofsPallets/documentsTransfer/' . $id . '/' . $transfer->type . '/';
                Storage::delete($path . $actDoc->name);
                $actualTransferAssociated = DB::table('document_palletstransfer')->where('document_id', $actDoc->id)->get();
                if ($actualTransferAssociated->isEmpty()) {
                    $actDoc->delete();
                }
            }
        }
        $actualErrors_Palletstransfers = DB::table('error_palletstransfer')->where('palletstransfer_id', $id)->get();
        $actualErrors = [];
        if (!$actualErrors_Palletstransfers->isEmpty()) {
            foreach ($actualErrors_Palletstransfers as $actualError) {
                $actualErrors[] = Error::where('id', $actualError->error_id)->first();
            }
            foreach ($actualErrors as $actErr) {
                $actErr->palletstransfers()->detach($id);
            }
        }


        Palletstransfer::where('id', $id)->delete();
        session()->flash('messageDeletePalletstransfer', 'Successfully deleted the pallets transfer!');

        if (isset($loading_atrnr)) {
            $this->state(Loading::where('atrnr', $loading_atrnr)->where('pt', 'JA')->first(), Palletstransfer::where('loading_atrnr', $loading_atrnr)->get());
            return redirect('/detailsLoading/' . $loading_atrnr);

        } else {
            return redirect('/allPalletstransfers');
        }
    }

    /**
     * upload a document on the website
     * @param $documents
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
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
     * find the documents currently attach to this transfer
     * @param $id
     * @return $filesNames
     */
    public function actualDocuments($id)
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
     * remove the last confirmed pallets transfer made for this transfer
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

    public function updateInfo($transfer, $actualPalletsNumber, $actualCreditAccount, $actualDebitAccount, $actualDoc)
    {
        //inverse transfer : we delete the last transfer
        if (isset($actualCreditAccount)) {
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
        }
        if (isset($actualDebitAccount)) {
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
        }

        //we do the new transfer
        if (isset($transfer->creditAccount)) {
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
        }
        if (isset($transfer->debitAccount)) {
            $partsDebitAccount = explode('-', $transfer->debitAccount);
            $typeDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 2];
            $idDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 1];
            if ($typeDebitAccount == 'truck') {
                $palletsNumberDebitAccount = Truck::where('id', $idDebitAccount)->first()->theoricalNumberPallets;
                Truck::where('id', $idDebitAccount)->update(['theoricalNumberPallets' => $palletsNumberDebitAccount - $transfer->palletsNumber]);
                $palletsaccount_name = Truck::where('id', $idDebitAccount)->value('palletsaccount_name');
                Palletsaccount::where('name', $palletsaccount_name)->update(['theoricalNumberPallets' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('theoricalNumberPallets')]);
            } elseif ($typeDebitAccount == 'account') {
                $palletsNumberDebitAccount = Palletsaccount::where('id', $idDebitAccount)->first()->theoricalNumberPallets;
                Palletsaccount::where('id', $idDebitAccount)->update(['theoricalNumberPallets' => $palletsNumberDebitAccount - $transfer->palletsNumber]);
            }
        }

        if (!empty($actualDoc) && $transfer->validate == 1) {
            Palletstransfer::where('id', $transfer->id)->update(['state' => 'Complete Validated']);
        } elseif (!empty($actualDoc) && $transfer->validate == 0) {
            Palletstransfer::where('id', $transfer->id)->update(['state' => 'Complete']);
        } elseif (empty($actualDoc)) {
            Palletstransfer::where('id', $transfer->id)->update(['state' => 'Waiting documents']);
        }
        session()->flash('messageUpdatePalletstransfer', 'Successfully updated pallets transfer');
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
        $idErrorDebt_NotEnoughTransfers = Error::where('name', 'Debt_notEnoughTransfers')->first()->id;
        $idErrorSPPS_NotEnoughTransfers = Error::where('name', 'SP-PS_notEnoughTransfers')->first()->id;

        //0) remove all errors on transfers
        foreach ($listPalletstransfers as $transfer) {
            $transfer->errors()->detach([$idErrorWDDW_atLeastOne, $idErrorDWWD_NotSameNumber, $idErrorDW_NotSameNumber, $idErrorWDDW_NotNumberLoadingOrder, $idErrorCorrecting_NotCompleteNormal, $idErrorDebt_NotEnoughTransfers, $idErrorSPPS_NotEnoughTransfers]);
        }

        //////CORRECTING TRANSFERS//////
        //check if there is enough correcting transfers (even number)
        $listTransfersDebt = Palletstransfer::where('type', 'Debt')->where('loading_atrnr', $loading->atrnr)->get();
        $listTransfersSP = Palletstransfer::where('type', 'Purchase-Sale')->where('loading_atrnr', $loading->atrnr)->get();
        $listTransfersPS = Palletstransfer::where('type', 'Sale-Purchase')->where('loading_atrnr', $loading->atrnr)->get();

        if (count($listTransfersSP) <> count($listTransfersPS)) {
            foreach ($listTransfersSP as $transferSP) {
                $transferSP->errors()->attach($idErrorSPPS_NotEnoughTransfers);
            }
            foreach ($listTransfersPS as $transferPS) {
                $transferPS->errors()->attach($idErrorSPPS_NotEnoughTransfers);
            }
        }
        if (count($listTransfersDebt)%2 <>0) {
            foreach ($listTransfersDebt as $transferDebt) {
                $transferDebt->errors()->attach($idErrorDebt_NotEnoughTransfers);
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
        $sumD=0;
        $sum1CorrectingTransferD=0;
        $sum2CorrectingTransferD=0;
        foreach ($listTransfersD as $transferD) {
            $sum1CorrectingTransferD = $sum1CorrectingTransferD+Palletstransfer::where('normalTransferAssociated', $transferD->id)->where('loading_atrnr', $loading->atrnr)->where(function ($q) {
                    $q->where('type', 'Debt')->orWhere('type', 'Purchase-Sale');
                })->sum('palletsNumber');
            $sum2CorrectingTransferD =$sum2CorrectingTransferD+ Palletstransfer::where('normalTransferAssociated', $transferD->id)->where('loading_atrnr', $loading->atrnr)->where(function ($q) {
                    $q->where('type', 'Debt')->orWhere('type', 'Sale-Purchase');
                })->sum('palletsNumber');
            $sumD= $sumD + $transferD->palletsNumber;
        }

        $sumW=0;
        $sum1CorrectingTransferW=0;
        $sum2CorrectingTransferW=0;
        foreach ($listTransfersW as $transferW) {
            $sum1CorrectingTransferW = $sum1CorrectingTransferW+Palletstransfer::where('normalTransferAssociated', $transferW->id)->where('loading_atrnr', $loading->atrnr)->where(function ($q) {
                    $q->where('type', 'Debt')->orWhere('type', 'Purchase-Sale');
                })->sum('palletsNumber');
            $sum2CorrectingTransferW =$sum2CorrectingTransferW+ Palletstransfer::where('normalTransferAssociated', $transferW->id)->where('loading_atrnr', $loading->atrnr)->where(function ($q) {
                    $q->where('type', 'Debt')->orWhere('type', 'Sale-Purchase');
                })->sum('palletsNumber');
            $sumW = $sumW + $transferW->palletsNumber ;
        }
        //sum d only + correcting w only
        $sum1D= $sumD + $sum1CorrectingTransferW;
        $sum2D= $sumD + $sum2CorrectingTransferW;
        //sum w only + correcting d only
        $sum1W= $sumW + $sum1CorrectingTransferD;
        $sum2W= $sumW + $sum2CorrectingTransferD;

        //errors
        if ($sum1D <> $sum1W) {
            foreach ($listTransfersD as $transferD) {
                foreach (Palletstransfer::where('normalTransferAssociated', $transferD->id)->where('loading_atrnr', $loading->atrnr)->where(function ($q) {
                    $q->where('type', 'Debt')->orWhere('type', 'Purchase-Sale');
                })->get() as $transferCorrecting1D) {
                    $transferCorrecting1D->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                }
            }
            foreach ($listTransfersW as $transferW) {
                foreach (Palletstransfer::where('normalTransferAssociated', $transferW->id)->where('loading_atrnr', $loading->atrnr)->where(function ($q) {
                    $q->where('type', 'Debt')->orWhere('type', 'Purchase-Sale');
                })->get() as $transferCorrecting1W) {
                    $transferCorrecting1W->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                }
            }
        }
        if ($sum1D <> $sum2W) {
            foreach ($listTransfersD as $transferD) {
                foreach (Palletstransfer::where('normalTransferAssociated', $transferD->id)->where('loading_atrnr', $loading->atrnr)->where(function ($q) {
                    $q->where('type', 'Debt')->orWhere('type', 'Purchase-Sale');
                })->get() as $transferCorrecting1D) {
                    $transferCorrecting1D->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                    $transferCorrecting1D->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                }
            }
            foreach ($listTransfersW as $transferW) {
                foreach (Palletstransfer::where('normalTransferAssociated', $transferW->id)->where('loading_atrnr', $loading->atrnr)->where(function ($q) {
                    $q->where('type', 'Debt')->orWhere('type', 'Sale-Purchase');
                })->get() as $transferCorrecting2W) {
                    $transferCorrecting2W->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                    $transferCorrecting2W->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                }
            }
        }
        if ($sum2D <> $sum1W) {
            foreach ($listTransfersD as $transferD) {
                foreach (Palletstransfer::where('normalTransferAssociated', $transferD->id)->where('loading_atrnr', $loading->atrnr)->where(function ($q) {
                    $q->where('type', 'Debt')->orWhere('type', 'Sale-Purchase');
                })->get() as $transferCorrecting2D) {
                    $transferCorrecting2D->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                    $transferCorrecting2D->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                }
            }
            foreach ($listTransfersW as $transferW) {
                foreach (Palletstransfer::where('normalTransferAssociated', $transferW->id)->where('loading_atrnr', $loading->atrnr)->where(function ($q) {
                    $q->where('type', 'Debt')->orWhere('type', 'Purchase-Sale');
                })->get() as $transferCorrecting1W) {
                    $transferCorrecting1W->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                    $transferCorrecting1W->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                }
            }
        }
        if ($sum2D <> $sum2W) {
            foreach ($listTransfersD as $transferD) {
                foreach (Palletstransfer::where('normalTransferAssociated', $transferD->id)->where('loading_atrnr', $loading->atrnr)->where(function ($q) {
                    $q->where('type', 'Debt')->orWhere('type', 'Sale-Purchase');
                })->get() as $transferCorrecting2D) {
                    $transferCorrecting2D->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                    $transferCorrecting2D->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                }
            }
            foreach ($listTransfersW as $transferW) {
                foreach (Palletstransfer::where('normalTransferAssociated', $transferW->id)->where('loading_atrnr', $loading->atrnr)->where(function ($q) {
                    $q->where('type', 'Debt')->orWhere('type', 'Sale-Purchase');
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
                $sumTransfersPSAssociated = Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Purchase-Sale')->where('normalTransferAssociated', $transferDW_acc->id)->sum('palletsNumber');
                if ($transferDW_acc->palletsNumber <= $loading->anz) {
                    $sum1DW = $sum1DW + $sumTransfersPSAssociated + $transferDW_acc->palletsNumber;
                } else {
                    $sum1DW = $sum1DW - $sumTransfersPSAssociated + $transferDW_acc->palletsNumber;
                }
                $sumTransfersSPAssociated = Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Sale-Purchase')->where('normalTransferAssociated', $transferDW_acc->id)->sum('palletsNumber');
                if ($transferDW_acc->palletsNumber <= $loading->anz) {
                    $sum2DW = $sum2DW + $sumTransfersSPAssociated + $transferDW_acc->palletsNumber;
                } else {
                    $sum2DW = $sum2DW - $sumTransfersSPAssociated + $transferDW_acc->palletsNumber;
                }

                $transferDebtAssociated = Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Debt')->where(function ($q) use ($transferDW_acc) {
                    $q->where('normalTransferAssociated', 'like', '%' . '-' . $transferDW_acc->id)->orWhere('normalTransferAssociated', 'like', $transferDW_acc->id . '-' . '%');
                })->first();
                if ($transferDebtAssociated <> null && strpos($transferDebtAssociated->normalTransferAssociated, '-') == true && count(explode('-', $transferDebtAssociated->normalTransferAssociated)) == 2) {
                    if (explode('-', $transferDebtAssociated->normalTransferAssociated)[0] == $transferDW_acc->id) {
                        $transferWDAssociated = Palletstransfer::where('id', explode('-', $transferDebtAssociated->normalTransferAssociated)[1])->first();
                    } elseif (explode('-', $transferDebtAssociated->normalTransferAssociated)[1] == $transferDW_acc->id) {
                        $transferWDAssociated = Palletstransfer::where('id', explode('-', $transferDebtAssociated->normalTransferAssociated)[0])->first();
                    }
                    if ($transferDW_acc->palletsNumber <= $transferWDAssociated->palletsNumber) {
                        $sum1DW = $sum1DW + $transferDebtAssociated->palletsNumber;
                        $sum2DW = $sum2DW + $transferDebtAssociated->palletsNumber;
                    }
                } elseif ($transferDebtAssociated <> null && strpos($transferDebtAssociated->normalTransferAssociated, '-') == false) {
                    $sum1DW = $sum1DW + $transferDebtAssociated->palletsNumber;
                    $sum2DW = $sum2DW + $transferDebtAssociated->palletsNumber;
                }
            }
            $sum1WD = 0;
            $sum2WD = 0;
            foreach ($listTransfersWD_acc as $transferWD_acc) {
                $sumTransfersPSAssociated = Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Purchase-Sale')->where('normalTransferAssociated', $transferWD_acc->id)->sum('palletsNumber');
                if ($transferWD_acc->palletsNumber <= $loading->anz) {
                    $sum1WD = $sum1WD + $sumTransfersPSAssociated + $transferWD_acc->palletsNumber;
                } else {
                    $sum1WD = $sum1WD - $sumTransfersPSAssociated + $transferWD_acc->palletsNumber;
                }
                $sumTransfersSPAssociated = Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Sale-Purchase')->where('normalTransferAssociated', $transferWD_acc->id)->sum('palletsNumber');
                if ($transferWD_acc->palletsNumber <= $loading->anz) {
                    $sum2WD = $sum2WD + $sumTransfersSPAssociated + $transferWD_acc->palletsNumber;
                } else {
                    $sum2WD = $sum2WD - $sumTransfersSPAssociated + $transferWD_acc->palletsNumber;
                }

                $transferDebtAssociated = Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Debt')->where(function ($q) use ($transferWD_acc) {
                    $q->where('normalTransferAssociated', 'like', '%' . '-' . $transferWD_acc->id)->orWhere('normalTransferAssociated', 'like', $transferWD_acc->id . '-' . '%');
                })->first();
                if ($transferDebtAssociated <> null && strpos($transferDebtAssociated->normalTransferAssociated, '-') == true && count(explode('-', $transferDebtAssociated->normalTransferAssociated)) == 2) {
                    if (explode('-', $transferDebtAssociated->normalTransferAssociated)[0] == $transferWD_acc->id) {
                        $transferDWAssociated = Palletstransfer::where('id', explode('-', $transferDebtAssociated->normalTransferAssociated)[1])->first();
                    } elseif (explode('-', $transferDebtAssociated->normalTransferAssociated)[1] == $transferWD_acc->id) {
                        $transferDWAssociated = Palletstransfer::where('id', explode('-', $transferDebtAssociated->normalTransferAssociated)[0])->first();
                    }
                    if ($transferWD_acc->palletsNumber <= $transferDWAssociated->palletsNumber) {
                        $sum1WD = $sum1WD + $transferDebtAssociated->palletsNumber;
                        $sum2WD = $sum2WD + $transferDebtAssociated->palletsNumber;
                    }
                } elseif ($transferDebtAssociated <> null && strpos($transferDebtAssociated->normalTransferAssociated, '-') == false) {
                    $sum1WD = $sum1WD + $transferDebtAssociated->palletsNumber;
                    $sum2WD = $sum2WD + $transferDebtAssociated->palletsNumber;
                }
            }

            //errors
            if ($sum1DW <> $sum1WD) {
                foreach ($listTransfersDW_acc as $transferDW_acc) {
                    foreach (Palletstransfer::where('normalTransferAssociated', $transferDW_acc->id)->where('loading_atrnr', $loading->atrnr)->where('type', 'Purchase-Sale')->get() as $transferCorrecting1DW) {
                        $transferCorrecting1DW->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                    foreach (Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Debt')->where(function ($q) use ($transferDW_acc) {
                        $q->where('normalTransferAssociated', 'like', '%' . $transferDW_acc->id)->orWhere('normalTransferAssociated', 'like', $transferDW_acc->id . '%');
                    })->get() as $transferCorrectingDebt) {
                        $transferCorrectingDebt->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                }
                foreach ($listTransfersWD_acc as $transferWD_acc) {
                    foreach (Palletstransfer::where('normalTransferAssociated', $transferWD_acc->id)->where('loading_atrnr', $loading->atrnr)->where('type', 'Purchase-Sale')->get() as $transferCorrecting1WD) {
                        $transferCorrecting1WD->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                    foreach (Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Debt')->where(function ($q) use ($transferWD_acc) {
                        $q->where('normalTransferAssociated', 'like', '%' . $transferWD_acc->id)->orWhere('normalTransferAssociated', 'like', $transferWD_acc->id . '%');
                    })->get() as $transferCorrectingDebt) {
                        $transferCorrectingDebt->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrectingDebt->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                }
            }
            if ($sum1DW <> $sum2WD) {
                foreach ($listTransfersDW_acc as $transferDW_acc) {
                    foreach (Palletstransfer::where('normalTransferAssociated', $transferDW_acc->id)->where('loading_atrnr', $loading->atrnr)->where('type', 'Purchase-Sale')->get() as $transferCorrecting1DW) {
                        $transferCorrecting1DW->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrecting1DW->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                    foreach (Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Debt')->where(function ($q) use ($transferDW_acc) {
                        $q->where('normalTransferAssociated', 'like', '%' . $transferDW_acc->id)->orWhere('normalTransferAssociated', 'like', $transferDW_acc->id . '%');
                    })->get() as $transferCorrectingDebt) {
                        $transferCorrectingDebt->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrectingDebt->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                }
                foreach ($listTransfersWD_acc as $transferWD_acc) {
                    foreach (Palletstransfer::where('normalTransferAssociated', $transferWD_acc->id)->where('loading_atrnr', $loading->atrnr)->where('type', 'Sale-Purchase')->get() as $transferCorrecting2WD) {
                        $transferCorrecting2WD->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrecting2WD->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                    foreach (Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Debt')->where(function ($q) use ($transferWD_acc) {
                        $q->where('normalTransferAssociated', 'like', '%' . $transferWD_acc->id)->orWhere('normalTransferAssociated', 'like', $transferWD_acc->id . '%');
                    })->get() as $transferCorrectingDebt) {
                        $transferCorrectingDebt->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrectingDebt->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                }
            }
            if ($sum2DW <> $sum1WD) {
                foreach ($listTransfersDW_acc as $transferDW_acc) {
                    foreach (Palletstransfer::where('normalTransferAssociated', $transferDW_acc->id)->where('loading_atrnr', $loading->atrnr)->where('type', 'Sale-Purchase')->get() as $transferCorrecting2DW) {
                        $transferCorrecting2DW->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrecting2DW->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                    foreach (Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Debt')->where(function ($q) use ($transferDW_acc) {
                        $q->where('normalTransferAssociated', 'like', '%' . $transferDW_acc->id)->orWhere('normalTransferAssociated', 'like', $transferDW_acc->id . '%');
                    })->get() as $transferCorrectingDebt) {
                        $transferCorrectingDebt->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrectingDebt->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                }
                foreach ($listTransfersWD_acc as $transferWD_acc) {
                    foreach (Palletstransfer::where('normalTransferAssociated', $transferWD_acc->id)->where('loading_atrnr', $loading->atrnr)->where('type', 'Purchase-Sale')->get() as $transferCorrecting1WD) {
                        $transferCorrecting1WD->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrecting1WD->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                    foreach (Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Debt')->where(function ($q) use ($transferWD_acc) {
                        $q->where('normalTransferAssociated', 'like', '%' . $transferWD_acc->id)->orWhere('normalTransferAssociated', 'like', $transferWD_acc->id . '%');
                    })->get() as $transferCorrectingDebt) {
                        $transferCorrectingDebt->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrectingDebt->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                }
            }
            if ($sum2DW <> $sum2WD) {
                foreach ($listTransfersDW_acc as $transferDW_acc) {
                    foreach (Palletstransfer::where('normalTransferAssociated', $transferDW_acc->id)->where('loading_atrnr', $loading->atrnr)->where('type', 'Sale-Purchase')->get() as $transferCorrecting2DW) {
                        $transferCorrecting2DW->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrecting2DW->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                    foreach (Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Debt')->where(function ($q) use ($transferDW_acc) {
                        $q->where('normalTransferAssociated', 'like', '%' . $transferDW_acc->id)->orWhere('normalTransferAssociated', 'like', $transferDW_acc->id . '%');
                    })->get() as $transferCorrectingDebt) {
                        $transferCorrectingDebt->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrectingDebt->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                }
                foreach ($listTransfersWD_acc as $transferWD_acc) {
                    foreach (Palletstransfer::where('normalTransferAssociated', $transferWD_acc->id)->where('loading_atrnr', $loading->atrnr)->where('type', 'Sale-Purchase')->get() as $transferCorrecting2WD) {
                        $transferCorrecting2WD->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrecting2WD->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                    foreach (Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Debt')->where(function ($q) use ($transferWD_acc) {
                        $q->where('normalTransferAssociated', 'like', '%' . $transferWD_acc->id)->orWhere('normalTransferAssociated', 'like', $transferWD_acc->id . '%');
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
                $sumTransfersPSAssociated = Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Purchase-Sale')->where('normalTransferAssociated', $transferDW_acc->id)->sum('palletsNumber');
                if ($transferDW_acc->palletsNumber <= $loading->anz) {
                    $sum1DW = $sum1DW + $sumTransfersPSAssociated + $transferDW_acc->palletsNumber;
                } else {
                    $sum1DW = $sum1DW - $sumTransfersPSAssociated + $transferDW_acc->palletsNumber;
                }
                $sumTransfersSPAssociated = Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Sale-Purchase')->where('normalTransferAssociated', $transferDW_acc->id)->sum('palletsNumber');
                if ($transferDW_acc->palletsNumber <= $loading->anz) {
                    $sum2DW = $sum2DW + $sumTransfersSPAssociated + $transferDW_acc->palletsNumber;
                } else {
                    $sum2DW = $sum2DW - $sumTransfersSPAssociated + $transferDW_acc->palletsNumber;
                }


                $transferDebtAssociated = Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Debt')->where(function ($q) use ($transferDW_acc) {
                    $q->where('normalTransferAssociated', 'like', '%' . '-' . $transferDW_acc->id)->orWhere('normalTransferAssociated', 'like', $transferDW_acc->id . '-' . '%');
                })->first();
                if ($transferDebtAssociated <> null && strpos($transferDebtAssociated->normalTransferAssociated, '-') == true && count(explode('-', $transferDebtAssociated->normalTransferAssociated)) == 2) {
                    if (explode('-', $transferDebtAssociated->normalTransferAssociated)[0] == $transferDW_acc->id) {
                        $transferWDAssociated = Palletstransfer::where('id', explode('-', $transferDebtAssociated->normalTransferAssociated)[1])->first();
                    } elseif (explode('-', $transferDebtAssociated->normalTransferAssociated)[1] == $transferDW_acc->id) {
                        $transferWDAssociated = Palletstransfer::where('id', explode('-', $transferDebtAssociated->normalTransferAssociated)[0])->first();
                    }
                    if ($transferDW_acc->palletsNumber <= $transferWDAssociated->palletsNumber) {
                        $sum1DW = $sum1DW + $transferDebtAssociated->palletsNumber;
                        $sum2DW = $sum2DW + $transferDebtAssociated->palletsNumber;
                    }
                } elseif ($transferDebtAssociated <> null && strpos($transferDebtAssociated->normalTransferAssociated, '-') == false) {
                    $sum1DW = $sum1DW + $transferDebtAssociated->palletsNumber;
                    $sum2DW = $sum2DW + $transferDebtAssociated->palletsNumber;
                }
            }
            $sum1WD = 0;
            $sum2WD = 0;
            foreach ($listTransfersWD_acc as $transferWD_acc) {
                $sumTransfersPSAssociated = Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Purchase-Sale')->where('normalTransferAssociated', $transferWD_acc->id)->sum('palletsNumber');
                if ($transferWD_acc->palletsNumber <= $loading->anz) {
                    $sum1WD = $sum1WD + $sumTransfersPSAssociated + $transferWD_acc->palletsNumber;
                } else {
                    $sum1WD = $sum1WD - $sumTransfersPSAssociated + $transferWD_acc->palletsNumber;
                }
                $sumTransfersSPAssociated = Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Sale-Purchase')->where('normalTransferAssociated', $transferWD_acc->id)->sum('palletsNumber');
                if ($transferWD_acc->palletsNumber <= $loading->anz) {
                    $sum2WD = $sum2WD + $sumTransfersSPAssociated + $transferWD_acc->palletsNumber;
                } else {
                    $sum2WD = $sum2WD - $sumTransfersSPAssociated + $transferWD_acc->palletsNumber;
                }

                $transferDebtAssociated = Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Debt')->where(function ($q) use ($transferWD_acc) {
                    $q->where('normalTransferAssociated', 'like', '%' . '-' . $transferWD_acc->id)->orWhere('normalTransferAssociated', 'like', $transferWD_acc->id . '-' . '%');
                })->first();
                if ($transferDebtAssociated <> null && strpos($transferDebtAssociated->normalTransferAssociated, '-') == true && count(explode('-', $transferDebtAssociated->normalTransferAssociated)) == 2) {
                    if (explode('-', $transferDebtAssociated->normalTransferAssociated)[0] == $transferWD_acc->id) {
                        $transferDWAssociated = Palletstransfer::where('id', explode('-', $transferDebtAssociated->normalTransferAssociated)[1])->first();
                    } elseif (explode('-', $transferDebtAssociated->normalTransferAssociated)[1] == $transferWD_acc->id) {
                        $transferDWAssociated = Palletstransfer::where('id', explode('-', $transferDebtAssociated->normalTransferAssociated)[0])->first();
                    }
                    if ($transferWD_acc->palletsNumber <= $transferDWAssociated->palletsNumber) {
                        $sum1WD = $sum1WD + $transferDebtAssociated->palletsNumber;
                        $sum2WD = $sum2WD + $transferDebtAssociated->palletsNumber;
                    }
                } elseif ($transferDebtAssociated <> null && strpos($transferDebtAssociated->normalTransferAssociated, '-') == false) {
                    $sum1WD = $sum1WD + $transferDebtAssociated->palletsNumber;
                    $sum2WD = $sum2WD + $transferDebtAssociated->palletsNumber;
                }
            }

            //errors
            if ($sum1DW <> $loading->anz) {
                foreach ($listTransfersDW_acc as $transferDW_acc) {
                    foreach (Palletstransfer::where('normalTransferAssociated', $transferDW_acc->id)->where('loading_atrnr', $loading->atrnr)->where('type', 'Purchase-Sale')->get() as $transferCorrecting1DW) {
                        $transferCorrecting1DW->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrecting1DW->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                    foreach (Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Debt')->where(function ($q) use ($transferDW_acc) {
                        $q->where('normalTransferAssociated', 'like', '%' . $transferDW_acc->id)->orWhere('normalTransferAssociated', 'like', $transferDW_acc->id . '%');
                    })->get() as $transferCorrectingDebt) {
                        $transferCorrectingDebt->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrectingDebt->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                }
            }
            if ($sum1WD <> $loading->anz) {
                foreach ($listTransfersWD_acc as $transferWD_acc) {
                    foreach (Palletstransfer::where('normalTransferAssociated', $transferWD_acc->id)->where('loading_atrnr', $loading->atrnr)->where('type', 'Purchase-Sale')->get() as $transferCorrecting1WD) {
                        $transferCorrecting1WD->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrecting1WD->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                    foreach (Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Debt')->where(function ($q) use ($transferWD_acc) {
                        $q->where('normalTransferAssociated', 'like', '%' . $transferWD_acc->id)->orWhere('normalTransferAssociated', 'like', $transferWD_acc->id . '%');
                    })->get() as $transferCorrectingDebt) {
                        $transferCorrectingDebt->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrectingDebt->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                }
            }
            if ($sum2DW <> $loading->anz) {
                foreach ($listTransfersDW_acc as $transferDW_acc) {
                    foreach (Palletstransfer::where('normalTransferAssociated', $transferDW_acc->id)->where('loading_atrnr', $loading->atrnr)->where('type', 'Sale-Purchase')->get() as $transferCorrecting2DW) {
                        $transferCorrecting2DW->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrecting2DW->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                    foreach (Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Debt')->where(function ($q) use ($transferDW_acc) {
                        $q->where('normalTransferAssociated', 'like', '%' . $transferDW_acc->id)->orWhere('normalTransferAssociated', 'like', $transferDW_acc->id . '%');
                    })->get() as $transferCorrectingDebt) {
                        $transferCorrectingDebt->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrectingDebt->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                }
            }
            if ($sum2WD <> $loading->anz) {
                foreach ($listTransfersWD_acc as $transferWD_acc) {
                    foreach (Palletstransfer::where('normalTransferAssociated', $transferWD_acc->id)->where('loading_atrnr', $loading->atrnr)->where('type', 'Sale-Purchase')->get() as $transferCorrecting2WD) {
                        $transferCorrecting2WD->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                        $transferCorrecting2WD->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                    foreach (Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Debt')->where(function ($q) use ($transferWD_acc) {
                        $q->where('normalTransferAssociated', 'like', '%' . $transferWD_acc->id)->orWhere('normalTransferAssociated', 'like', $transferWD_acc->id . '%');
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
}

