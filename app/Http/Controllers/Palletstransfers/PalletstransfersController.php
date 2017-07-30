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
            if ($type == 'all') {
                $query = DB::table('Palletstransfers');
            } elseif ($type == 'debt') {
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

            return view('palletstransfers.allPalletstransfers', compact('type', 'listPalletstransfers', 'sortby', 'order', 'links', 'count', 'searchColumns', 'searchQuery', 'searchQueryArray', 'listColumns', 'searchColumnsString'));
        } else {
            return view('auth.login');
        }
    }

    public function autocompleteAccount(Request $request)
    {
        $data = array();
        $accountName = DB::table('Palletsaccount')->select('nickname')->where('nickname', 'LIKE', "%{$request->input('query')}%")->get();
        foreach ($accountName as $account) {
            $data[] = $account->nickname;
        }
        return response()->json($data);
    }


    /**
     * show the add form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAdd($originalPage)
    {
        if (Auth::check()) {
            $listPalletsAccounts = Palletsaccount::orderBy('nickname', 'asc')->get();
            $listTrucksAccounts = Truck::orderBy('name', 'asc')->get();

            $date = Carbon::now()->format('Y-m-d');
             if(explode('-', $originalPage)[0]=='detailsTruck'){
                 $debitAccount='truck-'.explode('-', $originalPage)[1];
                 $creditAccount='truck-'.explode('-', $originalPage)[1];
             }elseif(explode('-', $originalPage)[0]=='detailsPalletsaccount'){
                 $debitAccount='account-'.explode('-', $originalPage)[1];
                 $creditAccount='account-'.explode('-', $originalPage)[1];
             }

            return view('palletstransfers.addPalletstransfer', compact('originalPage','creditAccount', 'debitAccount', 'listPalletsAccounts', 'listTrucksAccounts', 'date'));
        } else {
            return view('auth.login');
        }
    }

    /**
     * add a new pallets transfer to the list
     */
    public function add()
    {
        $originalPage = Input::get('originalPage');
        $listPalletsAccounts = Palletsaccount::orderBy('nickname', 'asc')->get();
        $listTrucksAccounts = Truck::orderBy('name', 'asc')->get();

        $type = Input::get('type');
        $details = Input::get('details');
        $date = Input::get('date');
        $creditAccount = Input::get('creditAccount');
        $debitAccount = Input::get('debitAccount');
        $palletsNumber = Input::get('palletsNumber');
        $actionForm = Input::get('actionForm');

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
        } else {
            $rules = array(
                'creditAccount' => 'required',
                'debitAccount' => 'required',
            );
        }
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            session()->flash('errorFields', "The field(s) has(ve) not been filled as expected");
            return view('palletstransfers.addPalletstransfer', compact('originalPage','date', 'type', 'creditAccount', 'debitAccount', 'palletsNumber', 'listPalletsAccounts', 'listTrucksAccounts', 'details'));
        } else {
            if (isset($actionForm) && $actionForm == 'addPalletstransfer') {
                if (isset($creditAccount)) {
                    $credAcc = $this->creditAccount($creditAccount, null);
                }
                if (isset($debitAccount)) {
                    $debAcc = $this->debitAccount($debitAccount, null);
                }

                return view('palletstransfers.addPalletstransfer', compact('originalPage','date', 'type', 'creditAccount', 'debitAccount', 'palletsNumber', 'actionForm', 'listPalletsAccounts', 'listTrucksAccounts', 'details'));
            } elseif (isset($actionForm) && $actionForm == 'okSubmitAddModal') {

                $this->createTransfer($type, $date, $details, $creditAccount, $debitAccount, $palletsNumber);
                $this->updatePalletsAccount($type, $creditAccount, $debitAccount, $palletsNumber);

                session()->flash('messageAddPalletstransfer', 'Successfully added new pallets transfer');
                if (explode('-', $originalPage)[0] == 'allPalletstransfers') {
                    return redirect('/allPalletstransfers/'.explode('-', $originalPage)[1]);
                } elseif (explode('-', $originalPage)[0] == 'detailsAccount') {
                    return redirect('/detailsAccount/' . explode('-', $originalPage)[1]);
                } elseif (explode('-', $originalPage)[0] == 'detailsTruck') {
                    return redirect('/detailsTruck/' . explode('-', $originalPage)[1]);
                }
            } elseif (isset($actionForm) && $actionForm == 'closeSubmitAddModal') {
                return view('palletstransfers.addPalletstransfer', compact('originalPage', 'listPalletsAccounts', 'listTrucksAccounts','type', 'creditAccount', 'debitAccount', 'palletsNumber', 'date', 'details'));
            }
        }
    }

    /**
     * write properly credit and debit account for the modals to confirm transfers
     * @param $creditAccount
     * @param $index
     * @return null|string
     */
    public function creditAccount($creditAccount, $index)
    {
        $creditAccountTransfer = null;
        if (strpos($creditAccount, '-') == 5 && explode('-', $creditAccount)[0] == 'truck') {
            //truck account
            $nameTruckAccount = Truck::where('id', explode('-', $creditAccount)[1])->value('name');
            $licensePlate = Truck::where('id', explode('-', $creditAccount)[1])->value('licensePlate');
            session()->flash('creditAccountModal', $nameTruckAccount . ' - ' . $licensePlate);
            if ($index <> null) {
                $creditAccountTransfer = $nameTruckAccount . '-' . $licensePlate . '-' . $creditAccount;
            }
        } elseif (strpos($creditAccount, '-') == 7 && explode('-', $creditAccount)[0] == 'account') {
            //others accounts (network, other)
            $namePalletsAccount = Palletsaccount::where('id', explode('-', $creditAccount)[1])->value('nickname');
            session()->flash('creditAccountModal', $namePalletsAccount);
            if ($index <> null) {
                $creditAccountTransfer = $namePalletsAccount . '-' . $creditAccount;
            }
        }
        return $creditAccountTransfer;
    }

    /**
     * write properly credit and debit account for the modals to confirm transfers
     * @param $debitAccount
     * @param $index
     * @return null|string
     */
    public function debitAccount($debitAccount, $index)
    {
        $debitAccountTransfer = null;
        if (strpos($debitAccount, '-') == 5 && explode('-', $debitAccount)[0] == 'truck') {
            //truck account
            $nameTruckAccount = Truck::where('id', explode('-', $debitAccount)[1])->value('name');
            $licensePlate = Truck::where('id', explode('-', $debitAccount)[1])->value('licensePlate');
            session()->flash('debitAccountModal', $nameTruckAccount . ' - ' . $licensePlate);
            if ($index <> null) {
                $debitAccountTransfer = $nameTruckAccount . '-' . $licensePlate . '-' . $debitAccount;
            }
        } elseif (strpos($debitAccount, '-') == 7 && explode('-', $debitAccount)[0] == 'account') {
            //others accounts (network, other)
            $namePalletsAccount = Palletsaccount::where('id', explode('-', $debitAccount)[1])->value('nickname');
            session()->flash('debitAccountModal', $namePalletsAccount);
            if ($index <> null) {
                $debitAccountTransfer = $namePalletsAccount . '-' . $debitAccount;
            }
        }
        return $debitAccountTransfer;
    }

    /**
     * create ne transfer according to the type
     * @param $type
     * @param $date
     * @param $details
     * @param $creditAccount
     * @param $debitAccount
     * @param $palletsNumber
     */
    public function createTransfer($type, $date, $details, $creditAccount, $debitAccount, $palletsNumber)
    {
        $creditAccountTransfer = $this->creditAccount($creditAccount, 1);
        $debitAccountTransfer = $this->debitAccount($debitAccount, 1);
        Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber]);
    }

    public function updatePalletsAccount($type, $creditAccount, $debitAccount, $palletsNumber)
    {
        if ($type == 'Debt') {
            $this->updateDebtPalletsAccount($creditAccount, $debitAccount, $palletsNumber);
        } elseif ($type == 'Purchase_Ext') {
            $this->updateCreditPalletsAccount($creditAccount, $palletsNumber);
        } elseif ($type == 'Sale_Ext') {
            $this->updateDebitPalletsAccount($debitAccount, $palletsNumber);
        } else {
            $this->updateCreditPalletsAccount($creditAccount, $palletsNumber);
            $this->updateDebitPalletsAccount($debitAccount, $palletsNumber);
        }
    }

    public function updateDebtPalletsAccount($creditAccount, $debitAccount, $palletsNumber){
        if (strpos($creditAccount, '-') == 5 && explode('-', $creditAccount)[0] == 'truck') {
            //truck account
            $actualDebtCreditPalletsNumber = Truck::where('id', explode('-', $creditAccount)[1])->first()->palletsDebt;
            Truck::where('id', explode('-', $creditAccount)[1])->update(['palletsDebt' => $actualDebtCreditPalletsNumber + $palletsNumber]);
            $palletsaccount_name = Truck::where('id', explode('-', $creditAccount)[1])->value('palletsaccount_name');
            Palletsaccount::where('nickname', $palletsaccount_name)->update(['palletsDebt' => Palletsaccount::where('nickname', $palletsaccount_name)->sum('palletsDebt')]);
        } elseif (strpos($creditAccount, '-') == 7 && explode('-', $creditAccount)[0] == 'account') {
            //others accounts (network, other)
            $actualDebtCreditPalletsNumber = Palletsaccount::where('id', explode('-', $creditAccount)[1])->first()->palletsDebt;
            Palletsaccount::where('id', explode('-', $creditAccount)[1])->update(['palletsDebt' => $actualDebtCreditPalletsNumber + $palletsNumber]);
        }
        if (strpos($debitAccount, '-') == 5 && explode('-', $debitAccount)[0] == 'truck') {
            //truck account
            $actualDebtDebitPalletsNumber = Truck::where('id', explode('-', $debitAccount)[1])->first()->palletsDebt;
            Truck::where('id', explode('-', $debitAccount)[1])->update(['palletsDebt' => $actualDebtDebitPalletsNumber - $palletsNumber]);
            $palletsaccount_name = Truck::where('id', explode('-', $debitAccount)[1])->value('palletsaccount_name');
            Palletsaccount::where('nickname', $palletsaccount_name)->update(['palletsDebt' => Palletsaccount::where('nickname', $palletsaccount_name)->sum('palletsDebt')]);
        } elseif (strpos($debitAccount, '-') == 7 && explode('-', $debitAccount)[0] == 'account') {
            //others accounts (network, other)
            $actualDebtDebitPalletsNumber = Palletsaccount::where('id', explode('-', $debitAccount)[1])->first()->palletsDebt;
            Palletsaccount::where('id', explode('-', $debitAccount)[1])->update(['palletsDebt' => $actualDebtDebitPalletsNumber - $palletsNumber]);
        }
    }

    /**
     * update pallets numbers on pallets account according to the type of account
     * @param $creditAccount
     * @param $actualTheoricalCreditPalletsNumber
     * @param $palletsNumber
     */
    public function updateCreditPalletsAccount($creditAccount, $palletsNumber)
    {
        if (strpos($creditAccount, '-') == 5 && explode('-', $creditAccount)[0] == 'truck') {
            //truck account
            $actualTheoricalCreditPalletsNumber = Truck::where('id', explode('-', $creditAccount)[1])->first()->theoricalNumberPallets;
            Truck::where('id', explode('-', $creditAccount)[1])->update(['theoricalNumberPallets' => $actualTheoricalCreditPalletsNumber + $palletsNumber]);
            $palletsaccount_name = Truck::where('id', explode('-', $creditAccount)[1])->value('palletsaccount_name');
            Palletsaccount::where('nickname', $palletsaccount_name)->update(['theoricalNumberPallets' => Palletsaccount::where('nickname', $palletsaccount_name)->sum('theoricalNumberPallets')]);
        } elseif (strpos($creditAccount, '-') == 7 && explode('-', $creditAccount)[0] == 'account') {
            //others accounts (network, other)
            $actualTheoricalCreditPalletsNumber = Palletsaccount::where('id', explode('-', $creditAccount)[1])->first()->palletsDebt;
            Palletsaccount::where('id', explode('-', $creditAccount)[1])->update(['theoricalNumberPallets' => $actualTheoricalCreditPalletsNumber + $palletsNumber]);
        }
    }

    /**
     * update pallets numbers on pallets account according to the type of account
     * @param $debitAccount
     * @param $actualTheoricalDebitPalletsNumber
     * @param $palletsNumber
     */
    public function updateDebitPalletsAccount($debitAccount, $palletsNumber)
    {
        if (strpos($debitAccount, '-') == 5 && explode('-', $debitAccount)[0] == 'truck') {
            //truck account
            $actualTheoricalDebitPalletsNumber = Truck::where('id', explode('-', $debitAccount)[1])->first()->theoricalNumberPallets;
            Truck::where('id', explode('-', $debitAccount)[1])->update(['theoricalNumberPallets' => $actualTheoricalDebitPalletsNumber - $palletsNumber]);
            $palletsaccount_name = Truck::where('id', explode('-', $debitAccount)[1])->value('palletsaccount_name');
            Palletsaccount::where('nickname', $palletsaccount_name)->update(['theoricalNumberPallets' => Palletsaccount::where('nickname', $palletsaccount_name)->sum('theoricalNumberPallets')]);
        } elseif (strpos($debitAccount, '-') == 7 && explode('-', $debitAccount)[0] == 'account') {
            //others accounts (network, other)
            $actualTheoricalDebitPalletsNumber = Palletsaccount::where('id', explode('-', $debitAccount)[1])->first()->palletsDebt;
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
            $listPalletsAccounts = Palletsaccount::where('type', 'Network')->orWhere('type', 'Other')->orderBy('nickname', 'asc')->get();
            $listTrucksAccounts = Truck::orderBy('name', 'asc')->get();
//            $listPalletstransfersNormal = Palletstransfer::where('type', 'Deposit-Withdrawal')->orWhere('type', 'Withdrawal-Deposit')->orWhere('type', 'Deposit_Only')->orWhere('type', 'Withdrawal_Only')->orderBy('id', 'asc')->get();

//            foreach (Loading::where('pt', 'JA')->orderBy('atrnr', 'asc')->get() as $loading) {
//                $listAtrnr[] = $loading->atrnr;
//            }
            $filesNames = $this->actualDocuments($id);
            $errorsTransfer = $this->actualErrors($transfer);
            return view('palletstransfers.detailsPalletstransfer', compact('transfer', 'errorsTransfer', 'listPalletsAccounts', 'listTrucksAccounts', 'filesNames'));
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
        $actionForm = Input::get('actionForm');

        //data
        $documents = $request->file('documentsTransfer');
        $date = Input::get('date');
        $details = Input::get('details');
        $creditAccount = Input::get('creditAccount');
        $debitAccount = Input::get('debitAccount');
        $palletsNumber = Input::get('palletsNumber');
        $validate = Input::get('validate');

        $listPalletsAccounts = Palletsaccount::where('type', 'Network')->orWhere('type', 'Other')->orderBy('nickname', 'asc')->get();
        $listTrucksAccounts = Truck::orderBy('name', 'asc')->get();
        $errorsTransfer = $this->actualErrors($transfer);

        if (isset($actionForm) && $actionForm == 'upload') {
            $this->upload($documents, $transfer, $validate);
            return redirect()->back();
//            } elseif (isset($actionForm) && explode('-', $actionForm)[0]== 'showAddCorrectingTransfer') {
//                $transferToCorrect = explode('-', $actionForm)[1];
//                $transferNormal = Palletstransfer::where('id', explode('-', $actionForm)[1])->first();
//                $listPalletstransferNormal=Palletstransfer::where('type', 'Deposit-Withdrawal')->orWhere('type', 'Deposit_Only')->orWhere('Withdrawal_Only')->get();
////                $loading=Loading::where('')
////                if ($transferNormal->palletsNumber <= $loading->anz) {
////                    $palletsNumber = $loading->anz - $transferNormal->palletsNumber;
////                } else {
////                    $palletsNumber = $transferNormal->palletsNumber - $loading->anz;
////                }
//                $creditAccountCorr = $transferNormal->creditAccount;
//                $debitAccountCorr = $transferNormal->debitAccount;
//                $date = Carbon::now()->format('Y-m-d');
//                return view('palletstransfers.addPalletstransfer', compact('listPalletsAccounts', 'listTrucksAccounts', 'listPalletstransfersNormal', 'date', 'listAtrnr', 'transferToCorrect', 'palletsNumber', 'creditAccountCorr', 'debitAccountCorr'));

        } elseif (isset($actionForm) && $actionForm == 'update' && $debitAccount == $creditAccount) {
            session()->flash('errorFields', "The fields have not been filled as expected : debit account and credit account must be different");
            return redirect()->back();
        } elseif (isset($actionForm) && $actionForm == 'update') {
            $filesNames = $this->actualDocuments($id);
            $this->updateTransfer($palletsNumber, $validate, $details, $date, $debitAccount, $creditAccount, $transfer);
            return view('palletstransfers.detailsPalletstransfer', compact('transfer', 'listPalletsAccounts', 'listTrucksAccounts', 'actionForm', 'filesNames', 'errorsTransfer'));
        } elseif (isset($actionForm) && explode('-', $actionForm)[0] == 'deleteDocument') {
            $this->deleteDocument($transfer, explode('-', $actionForm)[1]);
            if (isset($transfer->loading_atrnr)) {
                $this->state(Loading::where('atrnr', $transfer->loading_atrnr)->where('pt', 'JA')->first(), Palletstransfer::where('loading_atrnr', $transfer->loading_atrnr)->get());
            }
            return redirect()->back();
        } elseif (isset($actionForm) && $actionForm == 'okSubmitPalletsModal') {
            $filesNames = $this->actualDocuments($id);
            $this->validateUpdateTransfer($transfer, $filesNames);
            return redirect()->back();
        } elseif (isset($actionForm) && $actionForm == 'closeSubmitUpdateModal') {
            return redirect()->back();
        }
    }


    public function updateTransfer($palletsNumber, $validate, $details, $date, $debitAccount, $creditAccount, $transfer)
    {
        session()->put('palletsNumber', $palletsNumber);
        session()->put('validate', $validate);

        Palletstransfer::where('id', $transfer->id)->update(['date' => $date, 'details' => $details]);

        if (isset($creditAccount)) {
            session()->put('creditAccountComplete', $creditAccount);
            if (strpos($creditAccount, '-') == 5 && explode('-', $creditAccount)[0] == 'truck') {
                //truck account
                $nameTruckAccount = Truck::where('id', explode('-', $creditAccount)[1])->value('name');
                $licensePlate = Truck::where('id', explode('-', $creditAccount)[1])->value('licensePlate');
                session()->put('creditAccount', $nameTruckAccount . '-' . $licensePlate);
            } elseif (strpos($creditAccount, '-') == 7 && explode('-', $creditAccount)[0] == 'account') {
                //others accounts (network, other)
                $namePalletsAccount = Palletsaccount::where('id', explode('-', $creditAccount)[1])->value('nickname');
                session()->put('creditAccount', $namePalletsAccount);

            }
        }
        if (isset($debitAccount)) {
            session()->put('debitAccountComplete', $debitAccount);
            if (strpos($debitAccount, '-') == 5 && explode('-', $debitAccount)[0] == 'truck') {
                //truck account
                $nameTruckAccount = Truck::where('id', explode('-', $debitAccount)[1])->value('name');
                $licensePlate = Truck::where('id', explode('-', $debitAccount)[1])->value('licensePlate');
                session()->put('debitAccount', $nameTruckAccount . '-' . $licensePlate);

            } elseif (strpos($debitAccount, '-') == 7 && explode('-', $debitAccount)[0] == 'account') {
                //others accounts (network, other)
                $namePalletsAccount = Palletsaccount::where('id', explode('-', $debitAccount)[1])->value('nickname');
                session()->put('debitAccount', $namePalletsAccount);
            }
        }
    }

    public function validateUpdateTransfer($transfer, $actualDoc)
    {
        $creditAccount = session('creditAccountComplete');
        $debitAccount = session('debitAccountComplete');
        $creditAccountUpdate = session('creditAccount') . '-' . $creditAccount;
        $debitAccountUpdate = session('debitAccount') . '-' . $debitAccount;
        $palletsNumber = session('palletsNumber');
        $validate = session('validate');

        //inverse transfer : we delete the last transfer
        if (isset($transfer->creditAccount)) {
            $partsCreditAccount = explode('-', $transfer->creditAccount);
            $typeCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 2];
            $idCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 1];
            if ($typeCreditAccount == 'truck') {
                $actualPalletsNumberCreditAccount = Truck::where('id', $idCreditAccount)->first()->theoricalNumberPallets;
                Truck::where('id', $idCreditAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberCreditAccount - $transfer->palletsNumber]);
                $palletsaccount_name = Truck::where('id', $idCreditAccount)->value('palletsaccount_name');
                Palletsaccount::where('nickname', $palletsaccount_name)->update(['theoricalNumberPallets' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('theoricalNumberPallets')]);
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
                Palletsaccount::where('nickname', $palletsaccount_name)->update(['theoricalNumberPallets' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('theoricalNumberPallets')]);
            } elseif ($typeDebitAccount == 'account') {
                $actualPalletsNumberDebitAccount = Palletsaccount::where('id', $idDebitAccount)->first()->theoricalNumberPallets;
                Palletsaccount::where('id', $idDebitAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberDebitAccount + $transfer->palletsNumber]);
            }
        }

        //we do the new transfer
        if (isset($creditAccount)) {
            if (strpos($creditAccount, '-') == 5 && explode('-', $creditAccount)[0] == 'truck') {
                $palletsNumberCreditAccount = Truck::where('id', explode('-', $creditAccount)[1])->first()->theoricalNumberPallets;
                Truck::where('id', explode('-', $creditAccount)[1])->update(['theoricalNumberPallets' => $palletsNumberCreditAccount + $palletsNumber]);
                $palletsaccount_name = Truck::where('id', explode('-', $creditAccount)[1])->value('palletsaccount_name');
                Palletsaccount::where('nickname', $palletsaccount_name)->update(['theoricalNumberPallets' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('theoricalNumberPallets')]);
            } elseif (strpos($creditAccount, '-') == 7 && explode('-', $creditAccount)[0] == 'account') {
                $palletsNumberCreditAccount = Palletsaccount::where('id', explode('-', $creditAccount)[1])->first()->theoricalNumberPallets;
                Palletsaccount::where('id', explode('-', $creditAccount)[1])->update(['theoricalNumberPallets' => $palletsNumberCreditAccount + $palletsNumber]);
            }
        }
        if (isset($debitAccount)) {
            if (strpos($debitAccount, '-') == 5 && explode('-', $debitAccount)[0] == 'truck') {
                $palletsNumberDebitAccount = Truck::where('id', explode('-', $debitAccount)[1])->first()->theoricalNumberPallets;
                Truck::where('id', explode('-', $debitAccount)[1])->update(['theoricalNumberPallets' => $palletsNumberDebitAccount - $palletsNumber]);
                $palletsaccount_name = Truck::where('id', explode('-', $debitAccount)[1])->value('palletsaccount_name');
                Palletsaccount::where('nickname', $palletsaccount_name)->update(['theoricalNumberPallets' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('theoricalNumberPallets')]);
            } elseif (strpos($debitAccount, '-') == 7 && explode('-', $debitAccount)[0] == 'account') {
                $palletsNumberDebitAccount = Palletsaccount::where('id', explode('-', $debitAccount)[1])->first()->theoricalNumberPallets;
                Palletsaccount::where('id', explode('-', $debitAccount)[1])->update(['theoricalNumberPallets' => $palletsNumberDebitAccount - $palletsNumber]);
            }
        }

        if ($transfer->validate == 1 && $validate == 'false') {
            if (isset($transfer->creditAccount)) {
                $partsCreditAccount = explode('-', $transfer->creditAccount);
                $typeCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 2];
                $idCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 1];
                if ($typeCreditAccount == 'truck') {
                    $actualPalletsNumberCreditAccount = Truck::where('id', $idCreditAccount)->first()->realNumberPallets;
                    Truck::where('id', $idCreditAccount)->update(['realNumberPallets' => $actualPalletsNumberCreditAccount - $transfer->palletsNumber]);
                    $palletsaccount_name = Truck::where('id', $idCreditAccount)->value('palletsaccount_name');
                    Palletsaccount::where('nickname', $palletsaccount_name)->update(['realNumberPallets' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('realNumberPallets')]);
                } elseif ($typeCreditAccount == 'account') {
                    $actualPalletsNumberCreditAccount = Palletsaccount::where('id', $idCreditAccount)->first()->realNumberPallets;
                    Palletsaccount::where('id', $idCreditAccount)->update(['realNumberPallets' => $actualPalletsNumberCreditAccount - $transfer->palletsNumber]);
                }
            }
            if (isset($transfer->debitAccount)) {
                $partsDebitAccount = explode('-', $transfer->debitAccount);
                $typeDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 2];
                $idDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 1];
                if ($typeDebitAccount == 'truck') {
                    $actualPalletsNumberDebitAccount = Truck::where('id', $idDebitAccount)->first()->realNumberPallets;
                    Truck::where('id', $idDebitAccount)->update(['realNumberPallets' => $actualPalletsNumberDebitAccount + $transfer->palletsNumber]);
                    $palletsaccount_name = Truck::where('id', $idDebitAccount)->value('palletsaccount_name');
                    Palletsaccount::where('nickname', $palletsaccount_name)->update(['realNumberPallets' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('realNumberPallets')]);
                } elseif ($typeDebitAccount == 'account') {
                    $actualPalletsNumberDebitAccount = Palletsaccount::where('id', $idDebitAccount)->first()->realNumberPallets;
                    Palletsaccount::where('id', $idDebitAccount)->update(['realNumberPallets' => $actualPalletsNumberDebitAccount + $transfer->palletsNumber]);
                }
            }
        } elseif ($transfer->validate == 0 && $validate == 'true') {
            if (isset($creditAccount)) {
                $partsCreditAccount = explode('-', $creditAccount);
                $typeCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 2];
                $idCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 1];
                if ($typeCreditAccount == 'truck') {
                    $palletsNumberCreditAccount = Truck::where('id', $idCreditAccount)->first()->realNumberPallets;
                    Truck::where('id', $idCreditAccount)->update(['realNumberPallets' => $palletsNumberCreditAccount + $palletsNumber]);
                    $palletsaccount_name = Truck::where('id', $idCreditAccount)->value('palletsaccount_name');
                    Palletsaccount::where('nickname', $palletsaccount_name)->update(['realNumberPallets' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('realNumberPallets')]);
                } elseif ($typeCreditAccount == 'account') {
                    $palletsNumberCreditAccount = Palletsaccount::where('id', $idCreditAccount)->first()->realNumberPallets;
                    Palletsaccount::where('id', $idCreditAccount)->update(['realNumberPallets' => $palletsNumberCreditAccount + $palletsNumber]);
                }
            }
            if (isset($debitAccount)) {
                $partsDebitAccount = explode('-', $debitAccount);
                $typeDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 2];
                $idDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 1];
                if ($typeDebitAccount == 'truck') {
                    $palletsNumberDebitAccount = Truck::where('id', $idDebitAccount)->first()->realNumberPallets;
                    Truck::where('id', $idDebitAccount)->update(['realNumberPallets' => $palletsNumberDebitAccount - $palletsNumber]);
                    $palletsaccount_name = Truck::where('id', $idDebitAccount)->value('palletsaccount_name');
                    Palletsaccount::where('nickname', $palletsaccount_name)->update(['realNumberPallets' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('reallNumberPallets')]);
                } elseif ($typeDebitAccount == 'account') {
                    $palletsNumberDebitAccount = Palletsaccount::where('id', $idDebitAccount)->first()->realNumberPallets;
                    Palletsaccount::where('id', $idDebitAccount)->update(['realNumberPallets' => $palletsNumberDebitAccount - $palletsNumber]);
                }
            }
        }

        if (!empty($actualDoc) && $validate == 'true') {
            Palletstransfer::where('id', $transfer->id)->update(['state' => 'Complete Validated']);
        } elseif (!empty($actualDoc) && $validate == 'false') {
            Palletstransfer::where('id', $transfer->id)->update(['state' => 'Complete']);
        } elseif (empty($actualDoc)) {
            Palletstransfer::where('id', $transfer->id)->update(['state' => 'Waiting documents']);
        }
        if ($validate == 'true') {
            Palletstransfer::where('id', $transfer->id)->update(['validate' => true]);
        } elseif ($validate == 'false') {
            Palletstransfer::where('id', $transfer->id)->update(['validate' => false]);
        }
        Palletstransfer::where('id', $transfer->id)->update(['palletsNumber' => $palletsNumber, 'debitAccount' => $debitAccountUpdate, 'creditAccount' => $creditAccountUpdate]);
        $transfer = Palletstransfer::where('id', $transfer->id)->first();

        //update debt associated if there is one
        if ($transfer->type == 'Deposit-Withdrawal' || $transfer->type == 'Withdrawal-Deposit' || $transfer->type == 'Deposit_Only' || $transfer->type == 'Withdrawal_Only') {
            $debtAssociated = Palletstransfer::where('type', 'Debt')->where('loading_atrnr', $transfer->loading_atrnr)->where(function ($q) use ($transfer) {
                $q->where('transferToCorrect', 'like', '%' . $transfer->id)->orWhere('transferToCorrect', 'like', $transfer->id . '%');
            })->first();

            if ($debtAssociated <> null) {
                //remove last debt
                if (isset($debtAssociated->creditAccount)) {
                    $partsCreditAccount = explode('-', $debtAssociated->creditAccount);
                    $typeCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 2];
                    $idCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 1];
                    if ($typeCreditAccount == 'truck') {
                        $actualPalletsNumberCreditAccount = Truck::where('id', $idCreditAccount)->first()->palletsDebt;
                        Truck::where('id', $idCreditAccount)->update(['palletsDebt' => $actualPalletsNumberCreditAccount - $debtAssociated->palletsNumber]);
                        $palletsaccount_name = Truck::where('id', $idCreditAccount)->value('palletsaccount_name');
                        Palletsaccount::where('nickname', $palletsaccount_name)->update(['palletsDebt' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('palletsDebt')]);
                    } elseif ($typeCreditAccount == 'account') {
                        $actualPalletsNumberCreditAccount = Palletsaccount::where('id', $idCreditAccount)->first()->palletsDebt;
                        Palletsaccount::where('id', $idCreditAccount)->update(['palletsDebt' => $actualPalletsNumberCreditAccount - $debtAssociated->palletsNumber]);
                    }
                }
                if (isset($debtAssociated->debitAccount)) {
                    $partsDebitAccount = explode('-', $debtAssociated->debitAccount);
                    $typeDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 2];
                    $idDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 1];
                    if ($typeDebitAccount == 'truck') {
                        $actualPalletsNumberDebitAccount = Truck::where('id', $idDebitAccount)->first()->palletsDebt;
                        Truck::where('id', $idDebitAccount)->update(['palletsDebt' => $actualPalletsNumberDebitAccount + $debtAssociated->palletsNumber]);
                        $palletsaccount_name = Truck::where('id', $idDebitAccount)->value('palletsaccount_name');
                        Palletsaccount::where('nickname', $palletsaccount_name)->update(['palletsDebt' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('palletsDebt')]);
                    } elseif ($typeDebitAccount == 'account') {
                        $actualPalletsNumberDebitAccount = Palletsaccount::where('id', $idDebitAccount)->first()->palletsDebt;
                        Palletsaccount::where('id', $idDebitAccount)->update(['palletsDebt' => $actualPalletsNumberDebitAccount + $debtAssociated->palletsNumber]);
                    }
                }

                if (strpos($debtAssociated->transferToCorrect, '-') == true && count(explode('-', $debtAssociated->transferToCorrect)) == 2) {
                    //case DW-WD
                    if (explode('-', $debtAssociated->transferToCorrect)[0] == $transfer->id) {
                        $transferAssociated = Palletstransfer::where('id', explode('-', $debtAssociated->transferToCorrect)[1])->first();
                    } elseif (explode('-', $debtAssociated->transferToCorrect)[1] == $transfer->id) {
                        $transferAssociated = Palletstransfer::where('id', explode('-', $debtAssociated->transferToCorrect)[0])->first();
                    }

                    $palletsNumber2 = $transferAssociated->palletsNumber;
                    $palletsNumber3 = 0;
                    $loading = Loading::where('atrnr', $transfer->loading_atrnr)->first();
                    $creditAccount3 = null;
                    $debitAccount3 = null;
//
                    if ($palletsNumber < $loading->anz) {
                        if ($palletsNumber2 < $loading->anz) {
                            //we don't know who has a debt
                            $palletsNumber3 = $palletsNumber2 - $palletsNumber;
                            if ($palletsNumber3 < 0) {
                                $creditAccount3 = $debitAccountUpdate;
                                $debitAccount3 = $creditAccountUpdate;
                            } elseif ($palletsNumber3 > 0) {
                                $creditAccount3 = $creditAccountUpdate;
                                $debitAccount3 = $debitAccountUpdate;
                            }
                        } elseif ($palletsNumber2 > $loading->anz) {
                            //wenzel has a debt
                            $palletsNumber3 = $palletsNumber2 - $palletsNumber;
                            $creditAccount3 = $creditAccountUpdate;
                            $debitAccount3 = $debitAccountUpdate;
                        } elseif ($palletsNumber2 == $loading->anz) {
                            //wenzel has debt
                            $palletsNumber3 = $loading->anz - $palletsNumber;
                            $creditAccount3 = $creditAccountUpdate;
                            $debitAccount3 = $debitAccountUpdate;
                        }
                    } elseif ($palletsNumber > $loading->anz) {
                        if ($palletsNumber2 < $loading->anz) {
                            //other account has a debt
                            $palletsNumber3 = $palletsNumber - $palletsNumber2;
                            $creditAccount3 = $debitAccountUpdate;
                            $debitAccount3 = $creditAccountUpdate;
                        } elseif ($palletsNumber2 > $loading->anz) {
                            //we don't know who has a debt
                            $palletsNumber3 = $palletsNumber - $palletsNumber2;
                            if ($palletsNumber3 < 0) {
                                $creditAccount3 = $debitAccountUpdate;
                                $debitAccount3 = $creditAccountUpdate;
                            } elseif ($palletsNumber3 > 0) {
                                $creditAccount3 = $creditAccountUpdate;
                                $debitAccount3 = $debitAccountUpdate;
                            }
                        } elseif ($palletsNumber2 == $loading->anz) {
                            //other has a debt
                            $palletsNumber3 = $palletsNumber - $loading->anz;
                            $creditAccount3 = $debitAccountUpdate;
                            $debitAccount3 = $creditAccountUpdate;
                        }
                    } elseif ($palletsNumber == $loading->anz) {
                        if ($palletsNumber2 < $loading->anz) {
                            //other has a debt
                            $palletsNumber3 = $loading->anz - $palletsNumber2;
                            $creditAccount3 = $debitAccountUpdate;
                            $debitAccount3 = $creditAccountUpdate;
                        } elseif ($palletsNumber2 > $loading->anz) {
                            //wenzel has debt
                            $palletsNumber3 = $palletsNumber2 - $loading->anz;
                            $creditAccount3 = $creditAccountUpdate;
                            $debitAccount3 = $debitAccountUpdate;
                        }
                    }

                    if (isset($creditAccount3)) {
                        $partsCreditAccount = explode('-', $creditAccount3);
                        $typeCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 2];
                        $idCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 1];
                        if ($typeCreditAccount == 'truck') {
                            $actualDebtCreditAccount = Truck::where('id', $idCreditAccount)->first()->palletsDebt;
                            Truck::where('id', $idCreditAccount)->update(['palletsDebt' => $actualDebtCreditAccount + $palletsNumber3]);
                            $palletsaccount_name = Truck::where('id', $idCreditAccount)->value('palletsaccount_name');
                            Palletsaccount::where('nickname', $palletsaccount_name)->update(['palletsDebt' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('palletsDebt')]);
                        } elseif ($typeCreditAccount == 'account') {
                            $actualDebtCreditAccount = Palletsaccount::where('id', $idCreditAccount)->first()->palletsDebt;
                            Palletsaccount::where('id', $idCreditAccount)->update(['palletsDebt' => $actualDebtCreditAccount + $palletsNumber3]);
                        }
                    }
                    if (isset($debitAccount3)) {
                        $partsDebitAccount = explode('-', $debitAccount3);
                        $typeDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 2];
                        $idDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 1];
                        if ($typeDebitAccount == 'truck') {
                            $actualDebtDebitAccount = Truck::where('id', $idDebitAccount)->first()->palletsDebt;
                            Truck::where('id', $idDebitAccount)->update(['palletsDebt' => $actualDebtDebitAccount - $palletsNumber3]);
                            $palletsaccount_name = Truck::where('id', $idDebitAccount)->value('palletsaccount_name');
                            Palletsaccount::where('nickname', $palletsaccount_name)->update(['palletsDebt' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('palletsDebt')]);
                        } elseif ($typeDebitAccount == 'account') {
                            $actualDebtDebitAccount = Palletsaccount::where('id', $idDebitAccount)->first()->palletsDebt;
                            Palletsaccount::where('id', $idDebitAccount)->update(['palletsDebt' => $actualDebtDebitAccount - $palletsNumber3]);
                        }
                    }
                    Palletstransfer::where('id', $debtAssociated->id)->update(['palletsNumber' => $palletsNumber3, 'creditAccount' => $creditAccount3, 'debitAccount' => $debitAccount3]);

                } elseif (strpos($debtAssociated->transferToCorrect, '-') == false) {
                    //case D only or W only
                    //we do the new transfer
                    if (isset($creditAccount)) {
                        //here debt debit account
                        if (strpos($creditAccount, '-') == 5 && explode('-', $creditAccount)[0] == 'truck') {
                            $palletsNumberCreditAccount = Truck::where('id', explode('-', $creditAccount)[1])->first()->palletsDebt;
                            Truck::where('id', explode('-', $creditAccount)[1])->update(['palletsDebt' => $palletsNumberCreditAccount - $palletsNumber]);
                            $palletsaccount_name = Truck::where('id', explode('-', $creditAccount)[1])->value('palletsaccount_name');
                            Palletsaccount::where('nickname', $palletsaccount_name)->update(['palletsDebt' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('palletsDebt')]);
                        } elseif (strpos($creditAccount, '-') == 7 && explode('-', $creditAccount)[0] == 'account') {
                            $palletsNumberCreditAccount = Palletsaccount::where('id', explode('-', $creditAccount)[1])->first()->palletsDebt;
                            Palletsaccount::where('id', explode('-', $creditAccount)[1])->update(['palletsDebt' => $palletsNumberCreditAccount - $palletsNumber]);
                        }
                    }
                    if (isset($debitAccount)) {
                        //here debt credit account
                        if (strpos($debitAccount, '-') == 5 && explode('-', $debitAccount)[0] == 'truck') {
                            $palletsNumberDebitAccount = Truck::where('id', explode('-', $debitAccount)[1])->first()->palletsDebt;
                            Truck::where('id', explode('-', $debitAccount)[1])->update(['palletsDebt' => $palletsNumberDebitAccount + $palletsNumber]);
                            $palletsaccount_name = Truck::where('id', explode('-', $debitAccount)[1])->value('palletsaccount_name');
                            Palletsaccount::where('nickname', $palletsaccount_name)->update(['palletsDebt' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('palletsDebt')]);
                        } elseif (strpos($debitAccount, '-') == 7 && explode('-', $debitAccount)[0] == 'account') {
                            $palletsNumberDebitAccount = Palletsaccount::where('id', explode('-', $debitAccount)[1])->first()->palletsDebt;
                            Palletsaccount::where('id', explode('-', $debitAccount)[1])->update(['palletsDebt' => $palletsNumberDebitAccount + $palletsNumber]);
                        }
                    }

                    Palletstransfer::where('id', $debtAssociated->id)->update(['palletsNumber' => $palletsNumber, 'creditAccount' => $debitAccountUpdate, 'debitAccount' => $creditAccountUpdate]);
                }
            }

        }
        if (isset($transfer->loading_atrnr)) {
            $this->state(Loading::where('atrnr', $transfer->loading_atrnr)->where('pt', 'JA')->first(), Palletstransfer::where('loading_atrnr', $transfer->loading_atrnr)->get());
        }

        session()->pull('creditAccountComplete');
        session()->pull('debitAccountComplete');
        session()->pull('creditAccount');
        session()->pull('debitAccount');
        session()->pull('palletsNumber');
        session()->pull('validate');
        session()->flash('messageUpdatePalletstransfer', 'Successfully updated pallets transfer');
    }

    /**
     * delete the transfer from the database
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete($id)
    {
        if (Palletstransfer::where('transferToCorrect', $id)->first() <> null) {
            $idAssociated = Palletstransfer::where('transferToCorrect', $id)->first()->id;
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
                Palletsaccount::where('nickname', $palletsaccount_name)->update(['theoricalNumberPallets' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('theoricalNumberPallets')]);
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
                Palletsaccount::where('nickname', $palletsaccount_name)->update(['theoricalNumberPallets' => Truck::where('palletsaccount_name', $palletsaccount_name)->sum('theoricalNumberPallets')]);
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
    public function upload($documents, $transfer, $validate)
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
        if (!empty($filesNames) && $validate == 'true') {
            $state = 'Complete Validated';
        } elseif (!empty($filesNames) && ($validate == 'false' || $validate == null)) {
            $state = 'Complete';
        } elseif (empty($filesNames)) {
            $state = 'Waiting documents';
        }
        Palletstransfer::where('id', $transfer->id)->update(['state' => $state]);
        if (isset($transfer->loading_atrnr)) {
            $this->state(Loading::where('atrnr', $transfer->loading_atrnr)->where('pt', 'JA')->first(), Palletstransfer::where('loading_atrnr', $transfer->loading_atrnr)->get());
        }
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
        if (count($listTransfersDebt) % 2 <> 0) {
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
        $sumD = 0;
        $sum1CorrectingTransferD = 0;
        $sum2CorrectingTransferD = 0;
        foreach ($listTransfersD as $transferD) {
            $sum1CorrectingTransferD = $sum1CorrectingTransferD + Palletstransfer::where('transferToCorrect', $transferD->id)->where('loading_atrnr', $loading->atrnr)->where(function ($q) {
                    $q->where('type', 'Debt')->orWhere('type', 'Purchase-Sale');
                })->sum('palletsNumber');
            $sum2CorrectingTransferD = $sum2CorrectingTransferD + Palletstransfer::where('transferToCorrect', $transferD->id)->where('loading_atrnr', $loading->atrnr)->where(function ($q) {
                    $q->where('type', 'Debt')->orWhere('type', 'Sale-Purchase');
                })->sum('palletsNumber');
            $sumD = $sumD + $transferD->palletsNumber;
        }

        $sumW = 0;
        $sum1CorrectingTransferW = 0;
        $sum2CorrectingTransferW = 0;
        foreach ($listTransfersW as $transferW) {
            $sum1CorrectingTransferW = $sum1CorrectingTransferW + Palletstransfer::where('transferToCorrect', $transferW->id)->where('loading_atrnr', $loading->atrnr)->where(function ($q) {
                    $q->where('type', 'Debt')->orWhere('type', 'Purchase-Sale');
                })->sum('palletsNumber');
            $sum2CorrectingTransferW = $sum2CorrectingTransferW + Palletstransfer::where('transferToCorrect', $transferW->id)->where('loading_atrnr', $loading->atrnr)->where(function ($q) {
                    $q->where('type', 'Debt')->orWhere('type', 'Sale-Purchase');
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
                    $q->where('type', 'Debt')->orWhere('type', 'Purchase-Sale');
                })->get() as $transferCorrecting1D) {
                    $transferCorrecting1D->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                }
            }
            foreach ($listTransfersW as $transferW) {
                foreach (Palletstransfer::where('transferToCorrect', $transferW->id)->where('loading_atrnr', $loading->atrnr)->where(function ($q) {
                    $q->where('type', 'Debt')->orWhere('type', 'Purchase-Sale');
                })->get() as $transferCorrecting1W) {
                    $transferCorrecting1W->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                }
            }
        }
        if ($sum1D <> $sum2W) {
            foreach ($listTransfersD as $transferD) {
                foreach (Palletstransfer::where('transferToCorrect', $transferD->id)->where('loading_atrnr', $loading->atrnr)->where(function ($q) {
                    $q->where('type', 'Debt')->orWhere('type', 'Purchase-Sale');
                })->get() as $transferCorrecting1D) {
                    $transferCorrecting1D->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                    $transferCorrecting1D->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                }
            }
            foreach ($listTransfersW as $transferW) {
                foreach (Palletstransfer::where('transferToCorrect', $transferW->id)->where('loading_atrnr', $loading->atrnr)->where(function ($q) {
                    $q->where('type', 'Debt')->orWhere('type', 'Sale-Purchase');
                })->get() as $transferCorrecting2W) {
                    $transferCorrecting2W->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                    $transferCorrecting2W->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                }
            }
        }
        if ($sum2D <> $sum1W) {
            foreach ($listTransfersD as $transferD) {
                foreach (Palletstransfer::where('transferToCorrect', $transferD->id)->where('loading_atrnr', $loading->atrnr)->where(function ($q) {
                    $q->where('type', 'Debt')->orWhere('type', 'Sale-Purchase');
                })->get() as $transferCorrecting2D) {
                    $transferCorrecting2D->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                    $transferCorrecting2D->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                }
            }
            foreach ($listTransfersW as $transferW) {
                foreach (Palletstransfer::where('transferToCorrect', $transferW->id)->where('loading_atrnr', $loading->atrnr)->where(function ($q) {
                    $q->where('type', 'Debt')->orWhere('type', 'Purchase-Sale');
                })->get() as $transferCorrecting1W) {
                    $transferCorrecting1W->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                    $transferCorrecting1W->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                }
            }
        }
        if ($sum2D <> $sum2W) {
            foreach ($listTransfersD as $transferD) {
                foreach (Palletstransfer::where('transferToCorrect', $transferD->id)->where('loading_atrnr', $loading->atrnr)->where(function ($q) {
                    $q->where('type', 'Debt')->orWhere('type', 'Sale-Purchase');
                })->get() as $transferCorrecting2D) {
                    $transferCorrecting2D->errors()->detach($idErrorCorrecting_NotCompleteNormal);
                    $transferCorrecting2D->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                }
            }
            foreach ($listTransfersW as $transferW) {
                foreach (Palletstransfer::where('transferToCorrect', $transferW->id)->where('loading_atrnr', $loading->atrnr)->where(function ($q) {
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
                $sumTransfersPSAssociated = Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Purchase-Sale')->where('transferToCorrect', $transferDW_acc->id)->sum('palletsNumber');
                if ($transferDW_acc->palletsNumber <= $loading->anz) {
                    $sum1DW = $sum1DW + $sumTransfersPSAssociated + $transferDW_acc->palletsNumber;
                } else {
                    $sum1DW = $sum1DW - $sumTransfersPSAssociated + $transferDW_acc->palletsNumber;
                }
                $sumTransfersSPAssociated = Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Sale-Purchase')->where('transferToCorrect', $transferDW_acc->id)->sum('palletsNumber');
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
                $sumTransfersPSAssociated = Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Purchase-Sale')->where('transferToCorrect', $transferWD_acc->id)->sum('palletsNumber');
                if ($transferWD_acc->palletsNumber <= $loading->anz) {
                    $sum1WD = $sum1WD + $sumTransfersPSAssociated + $transferWD_acc->palletsNumber;
                } else {
                    $sum1WD = $sum1WD - $sumTransfersPSAssociated + $transferWD_acc->palletsNumber;
                }
                $sumTransfersSPAssociated = Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Sale-Purchase')->where('transferToCorrect', $transferWD_acc->id)->sum('palletsNumber');
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
                    foreach (Palletstransfer::where('transferToCorrect', $transferDW_acc->id)->where('loading_atrnr', $loading->atrnr)->where('type', 'Purchase-Sale')->get() as $transferCorrecting1DW) {
                        $transferCorrecting1DW->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                    foreach (Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Debt')->where(function ($q) use ($transferDW_acc) {
                        $q->where('transferToCorrect', 'like', '%' . $transferDW_acc->id)->orWhere('transferToCorrect', 'like', $transferDW_acc->id . '%');
                    })->get() as $transferCorrectingDebt) {
                        $transferCorrectingDebt->errors()->attach($idErrorCorrecting_NotCompleteNormal);
                    }
                }
                foreach ($listTransfersWD_acc as $transferWD_acc) {
                    foreach (Palletstransfer::where('transferToCorrect', $transferWD_acc->id)->where('loading_atrnr', $loading->atrnr)->where('type', 'Purchase-Sale')->get() as $transferCorrecting1WD) {
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
                    foreach (Palletstransfer::where('transferToCorrect', $transferDW_acc->id)->where('loading_atrnr', $loading->atrnr)->where('type', 'Purchase-Sale')->get() as $transferCorrecting1DW) {
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
                    foreach (Palletstransfer::where('transferToCorrect', $transferWD_acc->id)->where('loading_atrnr', $loading->atrnr)->where('type', 'Sale-Purchase')->get() as $transferCorrecting2WD) {
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
                    foreach (Palletstransfer::where('transferToCorrect', $transferDW_acc->id)->where('loading_atrnr', $loading->atrnr)->where('type', 'Sale-Purchase')->get() as $transferCorrecting2DW) {
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
                    foreach (Palletstransfer::where('transferToCorrect', $transferWD_acc->id)->where('loading_atrnr', $loading->atrnr)->where('type', 'Purchase-Sale')->get() as $transferCorrecting1WD) {
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
                    foreach (Palletstransfer::where('transferToCorrect', $transferDW_acc->id)->where('loading_atrnr', $loading->atrnr)->where('type', 'Sale-Purchase')->get() as $transferCorrecting2DW) {
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
                    foreach (Palletstransfer::where('transferToCorrect', $transferWD_acc->id)->where('loading_atrnr', $loading->atrnr)->where('type', 'Sale-Purchase')->get() as $transferCorrecting2WD) {
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
                $sumTransfersPSAssociated = Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Purchase-Sale')->where('transferToCorrect', $transferDW_acc->id)->sum('palletsNumber');
                if ($transferDW_acc->palletsNumber <= $loading->anz) {
                    $sum1DW = $sum1DW + $sumTransfersPSAssociated + $transferDW_acc->palletsNumber;
                } else {
                    $sum1DW = $sum1DW - $sumTransfersPSAssociated + $transferDW_acc->palletsNumber;
                }
                $sumTransfersSPAssociated = Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Sale-Purchase')->where('transferToCorrect', $transferDW_acc->id)->sum('palletsNumber');
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
                $sumTransfersPSAssociated = Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Purchase-Sale')->where('transferToCorrect', $transferWD_acc->id)->sum('palletsNumber');
                if ($transferWD_acc->palletsNumber <= $loading->anz) {
                    $sum1WD = $sum1WD + $sumTransfersPSAssociated + $transferWD_acc->palletsNumber;
                } else {
                    $sum1WD = $sum1WD - $sumTransfersPSAssociated + $transferWD_acc->palletsNumber;
                }
                $sumTransfersSPAssociated = Palletstransfer::where('loading_atrnr', $loading->atrnr)->where('type', 'Sale-Purchase')->where('transferToCorrect', $transferWD_acc->id)->sum('palletsNumber');
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
                    foreach (Palletstransfer::where('transferToCorrect', $transferDW_acc->id)->where('loading_atrnr', $loading->atrnr)->where('type', 'Purchase-Sale')->get() as $transferCorrecting1DW) {
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
                    foreach (Palletstransfer::where('transferToCorrect', $transferWD_acc->id)->where('loading_atrnr', $loading->atrnr)->where('type', 'Purchase-Sale')->get() as $transferCorrecting1WD) {
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
                    foreach (Palletstransfer::where('transferToCorrect', $transferDW_acc->id)->where('loading_atrnr', $loading->atrnr)->where('type', 'Sale-Purchase')->get() as $transferCorrecting2DW) {
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
                    foreach (Palletstransfer::where('transferToCorrect', $transferWD_acc->id)->where('loading_atrnr', $loading->atrnr)->where('type', 'Sale-Purchase')->get() as $transferCorrecting2WD) {
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
}

