<?php

namespace App\Http\Controllers;

use App\Document;
use App\Error;
use App\Loading;
use App\Palletsaccount;
use App\Palletstransfer;
use App\Truck;
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
    public function showAll(Request $request)
    {
        //data search query
        $searchQuery = $request->get('search');
        $searchQueryArray = explode(' ', $searchQuery);
        $searchColumns = $request->get('searchColumns');
        $listColumns = ['id', 'date', 'type', 'creditAccount', 'debitAccount', 'palletsNumber', 'state'];

        if (Auth::check()) {
            $query = DB::table('Palletstransfers');

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

            return view('palletstransfers.allPalletstransfers', compact('listPalletstransfers', 'sortby', 'order', 'links', 'count', 'searchColumns', 'searchQuery', 'searchQueryArray', 'listColumns', 'searchColumnsString'));
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

//    /**
//     * show the add form according to one parameter
//     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
//     */
//    public function showAddAccount($nameAccount)
//    {
//        if (Auth::check()) {
//            foreach (Palletsaccount::get() as $account) {
//                $listNamesPalletsaccounts[] = $account->name;
//            }
//            $date = Carbon::now()->format('Y-m-d');
//            foreach (Loading::get()->where('pt', 'JA') as $loading) {
//                $listAtrnr[] = $loading->atrnr;
//            }
//
//            return view('palletstransfers.addPalletstransfer', compact('listNamesPalletsaccounts', 'date', 'listAtrnr'));
//
//        } else {
//            return view('auth.login');
//        }
//    }


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

        } elseif($debitAccount==$creditAccount || (isset($debitAccount2) && isset($creditAccount2) && $debitAccount2 == $creditAccount2) || (isset($debitAccount3) && isset($creditAccount3) && $debitAccount3 == $creditAccount3)){
            session()->flash('errorFields', "The fields have not been filled as expected : debit account and credit account must be different");
            return redirect()->back();
        }else {
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
            $partsDebitAccount = explode('-', $transfer->debitAccount);
            $typeDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 2];
            $idDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 1];

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
        if (isset($loading_atrnr)) {
            $this->state(Loading::where('atrnr', $loading_atrnr)->where('pt', 'JA')->first(), Palletstransfer::where('loading_atrnr', $loading_atrnr)->get());
        }
        // redirect
        session()->flash('messageDeletePalletstransfer', 'Successfully deleted the pallets transfer!');
        return redirect('/allPalletstransfers');
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
            } else {
                foreach ($listTransfersWD_acc as $transferWD) {
                    $transferWD->errors()->detach($idErrorWDDW_atLeastOne);
                }
            }
            if (count($listTransfersDW_acc) == 0) {
                foreach ($listTransfersDW_acc as $transferDW) {
                    $transferDW->errors()->attach($idErrorWDDW_atLeastOne);
                }
            } else {
                foreach ($listTransfersDW_acc as $transferDW) {
                    $transferDW->errors()->detach($idErrorWDDW_atLeastOne);
                }
            }

            $sumTransferDW_acc = Palletstransfer::where('type', 'Deposit-Withdrawal')->where('loading_atrnr', $loading->atrnr)->where('creditAccount', $account)->sum('palletsNumber');
            $sumTransferWD_acc = Palletstransfer::where('type', 'Withdrawal-Deposit')->where('loading_atrnr', $loading->atrnr)->where('debitAccount', $account)->sum('palletsNumber');

            //check if sum(all transfers DW) = anz loading order
            if ($sumTransferDW_acc <> $loading->anz) {
                foreach ($listTransfersDW_acc as $transferDW) {
                    $transferDW->errors()->attach($idErrorWDDW_NotNumberLoadingOrder);
                }
            } else {
                foreach ($listTransfersDW_acc as $transferDW) {
                    $transferDW->errors()->detach($idErrorWDDW_NotNumberLoadingOrder);
                }
            }

            //check if sum(all transfers WD) = anz loading order
            if ($sumTransferWD_acc <> $loading->anz) {
                foreach ($listTransfersWD_acc as $transferWD) {
                    $transferWD->errors()->attach($idErrorWDDW_NotNumberLoadingOrder);
                }
            } else {
                foreach ($listTransfersWD_acc as $transferWD) {
                    $transferWD->errors()->detach($idErrorWDDW_NotNumberLoadingOrder);
                }
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
        } else {
            foreach ($listTransfersD as $transferD) {
                $transferD->errors()->detach($idErrorDW_NotSameNumber);
            }
            foreach ($listTransfersW as $transferW) {
                $transferW->errors()->detach($idErrorDW_NotSameNumber);
            }
        }

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
        } else {
            foreach ($listTransfersSP as $transferSP) {
                $transferSP->errors()->detach($idErrorSPPS_NotEnoughTransfers);
            }
            foreach ($listTransfersPS as $transferPS) {
                $transferPS->errors()->detach($idErrorSPPS_NotEnoughTransfers);
            }
        }

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
//dump('error name', $errorTransfer->name);
                        if ($errorTransfer->name == 'DW-WD_notNumberLoadingOrder' && ($transferNormal->type == 'Deposit-Withdrawal' || $transferNormal->type == 'Withdrawal-Deposit')) {
                            //check if NExchange or not
                            $partsCreditAccountTransferNormal = explode('-', $transferNormal->creditAccount);
                            $typeCreditAccountTransferNormal = $partsCreditAccountTransferNormal[count($partsCreditAccountTransferNormal) - 2];
                            $idCreditAccountTransferNormal = $partsCreditAccountTransferNormal[count($partsCreditAccountTransferNormal) - 1];
                            if ($typeCreditAccountTransferNormal == 'truck') {
                                $nameAccountTransferNormal = Truck::where('id', $idCreditAccountTransferNormal)->first()->palletsaccount_name;
                                $notExchange = Palletsaccount::where('name', $nameAccountTransferNormal)->first()->notExchange;
                            } else {
                                $partsDebitAccountTransferNormal = explode('-', $transferNormal->debitAccount);
                                $typeDebitAccountTransferNormal = $partsDebitAccountTransferNormal[count($partsDebitAccountTransferNormal) - 2];
                                $idDebitAccountTransferNormal = $partsDebitAccountTransferNormal[count($partsDebitAccountTransferNormal) - 1];
                                if ($typeDebitAccountTransferNormal == 'truck') {
                                    $nameAccountTransferNormal = Truck::where('id', $idDebitAccountTransferNormal)->first()->palletsaccount_name;
                                    $notExchange = Palletsaccount::where('name', $nameAccountTransferNormal)->first()->notExchange;
                                }
                            }


                            if ($notExchange == 1) {
                                //if not exchange : check if sum debt + p numb = anz
                                //if not : attach error on every debt transfer : not complete normal transfer
                                //if yes : detach error on normal transfer : notNumberLoadingOrder
                                if ($transferNormal->palletsNumber <= $loading->anz) {
                                    if ($sumTransfersDebtAssociated + $transferNormal->palletsNumber <> $loading->anz) {
                                        foreach ($listTransfersDebtAssociated as $transferDebtAssociated) {
                                            $transferDebtAssociated->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                                        }
                                    } else {
                                        foreach ($listTransfersDebtAssociated as $transferDebtAssociated) {
                                            $transferDebtAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                                        }
                                        $transferNormal->errors()->detach($idErrorWDDW_NotNumberLoadingOrder);
                                    }
                                } else {
                                    if ($transferNormal->palletsNumber - $sumTransfersDebtAssociated <> $loading->anz) {
                                        foreach ($listTransfersDebtAssociated as $transferDebtAssociated) {
                                            $transferDebtAssociated->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                                        }
                                    } else {
                                        foreach ($listTransfersDebtAssociated as $transferDebtAssociated) {
                                            $transferDebtAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                                        }
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
                                    } else {
                                        foreach ($listTransfersSPAssociated as $transferSPAssociated) {
                                            $transferSPAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                                        }
                                    }
                                    if ($sumTransfersPSAssociated + $transferNormal->palletsNumber <> $loading->anz) {
                                        foreach ($listTransfersPSAssociated as $transferPSAssociated) {
                                            $transferPSAssociated->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                                        }
                                    } else {
                                        foreach ($listTransfersPSAssociated as $transferPSAssociated) {
                                            $transferPSAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                                        }
                                    }
                                    if ($sumTransfersPSAssociated + $transferNormal->palletsNumber == $loading->anz && $sumTransfersSPAssociated + $transferNormal->palletsNumber == $loading->anz) {
                                        $transferNormal->errors()->detach($idErrorWDDW_NotNumberLoadingOrder);
                                    }
                                } else {
                                    if ($transferNormal->palletsNumber - $sumTransfersSPAssociated <> $loading->anz) {
                                        foreach ($listTransfersSPAssociated as $transferSPAssociated) {
                                            $transferSPAssociated->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                                        }
                                    } else {
                                        foreach ($listTransfersSPAssociated as $transferSPAssociated) {
                                            $transferSPAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                                        }
                                    }
                                    if ($transferNormal->palletsNumber - $sumTransfersPSAssociated <> $loading->anz) {
                                        foreach ($listTransfersPSAssociated as $transferPSAssociated) {
                                            $transferPSAssociated->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                                        }
                                    } else {
                                        foreach ($listTransfersPSAssociated as $transferPSAssociated) {
                                            $transferPSAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                                        }
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
                                } else {
                                    foreach ($listTransfersPSAssociated as $transferPSAssociated) {
                                        $transferPSAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                                    }
                                    foreach ($listTransfersDebtAssociated as $transferDebtAssociated) {
                                        $transferDebtAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                                    }
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
                                } else {
                                    foreach ($listTransfersSPAssociated as $transferSPAssociated) {
                                        $transferSPAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                                    }
                                    foreach ($listTransfersDebtAssociated as $transferDebtAssociated) {
                                        $transferDebtAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                                    }
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
                                } else {
                                    foreach ($listTransfersPSAssociated as $transferPSAssociated) {
                                        $transferPSAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                                    }
                                    foreach ($listTransfersDebtAssociated as $transferDebtAssociated) {
                                        $transferDebtAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                                    }
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
                                } else {
                                    foreach ($listTransfersSPAssociated as $transferSPAssociated) {
                                        $transferSPAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                                    }
                                    foreach ($listTransfersDebtAssociated as $transferDebtAssociated) {
                                        $transferDebtAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                                    }
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
                            if ($transferNormal->type == 'Deposit_Only') {
                                //check if debit account = truck is doing exchange or not
                                $partsDebitAccountTransferNormal = explode('-', $transferNormal->debitAccount);
                                $typeDebitAccountTransferNormal = $partsDebitAccountTransferNormal[count($partsDebitAccountTransferNormal) - 2];
                                $idDebitAccountTransferNormal = $partsDebitAccountTransferNormal[count($partsDebitAccountTransferNormal) - 1];
                                if ($typeDebitAccountTransferNormal == 'truck') {
                                    $nameAccountTransferNormal = Truck::where('id', $idDebitAccountTransferNormal)->first()->palletsaccount_name;
                                    $notExchange = Palletsaccount::where('name', $nameAccountTransferNormal)->first()->notExchange;
                                }
                            } elseif ($transferNormal->type == 'Withdrawal_Only') {
                                //check if credit account = truck is doing exchange or not
                                $partsCreditAccountTransferNormal = explode('-', $transferNormal->creditAccount);
                                $typeCreditAccountTransferNormal = $partsCreditAccountTransferNormal[count($partsCreditAccountTransferNormal) - 2];
                                $idCreditAccountTransferNormal = $partsCreditAccountTransferNormal[count($partsCreditAccountTransferNormal) - 1];
                                if ($typeCreditAccountTransferNormal == 'truck') {
                                    $nameAccountTransferNormal = Truck::where('id', $idCreditAccountTransferNormal)->first()->palletsaccount_name;
                                    $notExchange = Palletsaccount::where('name', $nameAccountTransferNormal)->first()->notExchange;
                                }
                            }

                            if ($notExchange == 1) {
                                //check debt transfers
                                //if sum debt = p numb : no errors
                                if ($sumTransfersDebtAssociated <> $transferNormal->palletsNumber) {
                                    foreach ($listTransfersDebtAssociated as $transferDebtAssociated) {
                                        $transferDebtAssociated->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                                    }
                                } else {
                                    foreach ($listTransfersDebtAssociated as $transferDebtAssociated) {
                                        $transferDebtAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                                    }
                                    $transferNormal->errors()->detach($idErrorDW_NotSameNumber);
                                }
                            } else {
                                //check PS and SP transfers
                                //if sum PS = sum SP = p numb : no errors
                                if ($sumTransfersSPAssociated <> $transferNormal->palletsNumber) {
                                    foreach ($listTransfersSPAssociated as $transferSPAssociated) {
                                        $transferSPAssociated->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                                    }
                                } else {
                                    foreach ($listTransfersSPAssociated as $transferSPAssociated) {
                                        $transferSPAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                                    }
                                }
                                if ($sumTransfersPSAssociated <> $transferNormal->palletsNumber) {
                                    foreach ($listTransfersPSAssociated as $transferPSAssociated) {
                                        $transferPSAssociated->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                                    }
                                } else {
                                    foreach ($listTransfersPSAssociated as $transferPSAssociated) {
                                        $transferPSAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                                    }
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
                            } else {
                                foreach ($listTransfersPSAssociated as $transferPSAssociated) {
                                    $transferPSAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                                }
                                foreach ($listTransfersDebtAssociated as $transferDebtAssociated) {
                                    $transferDebtAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                                }
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
                            } else {
                                foreach ($listTransfersSPAssociated as $transferSPAssociated) {
                                    $transferSPAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                                }
                                foreach ($listTransfersDebtAssociated as $transferDebtAssociated) {
                                    $transferDebtAssociated->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                                }
                            }
                            if ($sumTransfersSPAssociated + $sumTransfersDebtAssociated == $transferNormal->palletsNumber || $sumTransfersPSAssociated + $sumTransfersDebtAssociated == $transferNormal->palletsNumber) {
                                $transferNormal->errors()->detach($idErrorDW_NotSameNumber);
                            }
                        }
                    }

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

