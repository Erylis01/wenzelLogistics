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
                $links = $listPalletstransfers->appends(['sortby' => $sortby, 'order' => $order])->render();
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
                }
                $count = count($query->get());
                $listPalletstransfers = $query->orderBy('id', 'asc')->paginate(10);
                $links = '';
            }
//
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
        $errorsID = [];
        if (!$actualErrors_Palletstransfers->isEmpty()) {
            foreach ($actualErrors_Palletstransfers as $actualError) {
                $errorsID[] = Error::where('id', $actualError->error_id)->first()->id;
            }
        }
        return $errorsID;
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

            $date = Carbon::now()->format('Y-m-d');
            foreach (Loading::where('pt', 'JA')->orderBy('atrnr', 'asc')->get() as $loading) {
                $listAtrnr[] = $loading->atrnr;
            }
            return view('palletstransfers.addPalletstransfer', compact('listPalletsAccounts', 'listTrucksAccounts', 'date', 'listAtrnr'));
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

        $addPalletstransfer = Input::get('addPalletstransfer');
        $okSubmitAddModal = Input::get('okSubmitAddModal');
        $closeSubmitAddModal = Input::get('closeSubmitAddModal');

        if ($type == 'Purchase_Ext') {
            $rules = array(
                'creditAccount' => 'required',
            );
            if (strpos($creditAccount, '-') == 5 && explode('-', $creditAccount)[0] == 'truck') {
                //truck account
                $actualTheoricalCreditPalletsNumber = Truck::where('id', explode('-', $creditAccount)[1])->value('theoricalNumberPallets');
            } elseif (strpos($creditAccount, '-') == 7 && explode('-', $creditAccount)[0] == 'account') {
                //others accounts (network, other)
                $actualTheoricalCreditPalletsNumber = Palletsaccount::where('id', explode('-', $creditAccount)[1])->value('theoricalNumberPallets');
            }
//            $actualTheoricalCreditPalletsNumber = Palletsaccount::where('name', $creditAccount)->value('theoricalNumberPallets');
        } elseif ($type == 'Sale_Ext') {
            $rules = array(
                'debitAccount' => 'required',
            );
            if (strpos($debitAccount, '-') == 5 && explode('-', $debitAccount)[0] == 'truck') {
                //truck account
                $actualTheoricalDebitPalletsNumber = Truck::where('id', explode('-', $debitAccount)[1])->value('theoricalNumberPallets');
            } elseif (strpos($debitAccount, '-') == 7 && explode('-', $debitAccount)[0] == 'account') {
                //others accounts (network, other)
                $actualTheoricalDebitPalletsNumber = Palletsaccount::where('id', explode('-', $debitAccount)[1])->value('theoricalNumberPallets');
            }
//            $actualTheoricalDebitPalletsNumber = Palletsaccount::where('name', $debitAccount)->value('theoricalNumberPallets');
        } elseif ($type == 'Deposit-Withdrawal' || $type == 'Withdrawal-Deposit') {
            $creditAccount2 = $debitAccount;
            $debitAccount2 = $creditAccount;
            $palletsNumber2 = Input::get('palletsNumber2');

            $rules = array(
                'creditAccount' => 'required',
                'debitAccount' => 'required',
                'loading_atrnr' => 'required',
            );
            if (strpos($creditAccount, '-') == 5 && explode('-', $creditAccount)[0] == 'truck') {
                //truck account
                $actualTheoricalCreditPalletsNumber = Truck::where('id', explode('-', $creditAccount)[1])->value('theoricalNumberPallets');
            } elseif (strpos($creditAccount, '-') == 7 && explode('-', $creditAccount)[0] == 'account') {
                //others accounts (network, other)
                $actualTheoricalCreditPalletsNumber = Palletsaccount::where('id', explode('-', $creditAccount)[1])->value('theoricalNumberPallets');
            }
            if (strpos($debitAccount, '-') == 5 && explode('-', $debitAccount)[0] == 'truck') {
                //truck account
                $actualTheoricalDebitPalletsNumber = Truck::where('id', explode('-', $debitAccount)[1])->value('theoricalNumberPallets');
            } elseif (strpos($debitAccount, '-') == 7 && explode('-', $debitAccount)[0] == 'account') {
                //others accounts (network, other)
                $actualTheoricalDebitPalletsNumber = Palletsaccount::where('id', explode('-', $debitAccount)[1])->value('theoricalNumberPallets');
            }

//            $actualTheoricalCreditPalletsNumber = Palletsaccount::where('name', $creditAccount)->value('theoricalNumberPallets');
//            $actualTheoricalDebitPalletsNumber = Palletsaccount::where('name', $debitAccount)->value('theoricalNumberPallets');

        } else {
            $rules = array(
                'creditAccount' => 'required',
                'debitAccount' => 'required',
            );
            if (strpos($creditAccount, '-') == 5 && explode('-', $creditAccount)[0] == 'truck') {
                //truck account
                $actualTheoricalCreditPalletsNumber = Truck::where('id', explode('-', $creditAccount)[1])->value('theoricalNumberPallets');
            } elseif (strpos($creditAccount, '-') == 7 && explode('-', $creditAccount)[0] == 'account') {
                //others accounts (network, other)
                $actualTheoricalCreditPalletsNumber = Palletsaccount::where('id', explode('-', $creditAccount)[1])->value('theoricalNumberPallets');
            }
            if (strpos($debitAccount, '-') == 5 && explode('-', $debitAccount)[0] == 'truck') {
                //truck account
                $actualTheoricalDebitPalletsNumber = Truck::where('id', explode('-', $debitAccount)[1])->value('theoricalNumberPallets');
            } elseif (strpos($debitAccount, '-') == 7 && explode('-', $debitAccount)[0] == 'account') {
                //others accounts (network, other)
                $actualTheoricalDebitPalletsNumber = Palletsaccount::where('id', explode('-', $debitAccount)[1])->value('theoricalNumberPallets');
            }
//            $actualTheoricalCreditPalletsNumber = Palletsaccount::where('name', $creditAccount)->value('theoricalNumberPallets');
//            $actualTheoricalDebitPalletsNumber = Palletsaccount::where('name', $debitAccount)->value('theoricalNumberPallets');
        }

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            session()->flash('errorFields', "The field(s) has(ve) not been filled as expected");
            return view('palletstransfers.addPalletstransfer', compact('date', 'type', 'creditAccount', 'debitAccount', 'palletsNumber', 'listPalletsAccounts', 'listTrucksAccounts', 'details', 'loading_atrnr', 'listAtrnr'));
        } else {
            if (isset($addPalletstransfer)) {
                session()->flash('palletsNumber', $palletsNumber);
                if (isset($creditAccount)) {
                    if (strpos($creditAccount, '-') == 5 && explode('-', $creditAccount)[0] == 'truck') {
                        //truck account
                        $nameTruckAccount = Truck::where('id', explode('-', $creditAccount)[1])->value('name');
                        $licensePlate = Truck::where('id', explode('-', $creditAccount)[1])->value('licensePlate');
                        session()->flash('creditAccount', $nameTruckAccount . ' - ' . $licensePlate);
                    } elseif (strpos($creditAccount, '-') == 7 && explode('-', $creditAccount)[0] == 'account') {
                        //others accounts (network, other)
                        $namePalletsAccount = Palletsaccount::where('id', explode('-', $creditAccount)[1])->value('name');
                        session()->flash('creditAccount', $namePalletsAccount);
                    }
                    session()->flash('palletsNumberCreditAccount', $actualTheoricalCreditPalletsNumber);
                }
                if (isset($debitAccount)) {
                    if (strpos($debitAccount, '-') == 5 && explode('-', $debitAccount)[0] == 'truck') {
                        //truck account
                        $nameTruckAccount = Truck::where('id', explode('-', $debitAccount)[1])->value('name');
                        $licensePlate = Truck::where('id', explode('-', $debitAccount)[1])->value('licensePlate');
                        session()->flash('debitAccount', $nameTruckAccount . ' - ' . $licensePlate);
                    } elseif (strpos($debitAccount, '-') == 7 && explode('-', $debitAccount)[0] == 'account') {
                        //others accounts (network, other)
                        $namePalletsAccount = Palletsaccount::where('id', explode('-', $debitAccount)[1])->value('name');
                        session()->flash('debitAccount', $namePalletsAccount);
                    }
                    session()->flash('palletsNumberDebitAccount', $actualTheoricalDebitPalletsNumber);
                }
                if (isset($creditAccount2) && isset($debitAccount2) && isset($palletsNumber2)) {
                    if (strpos($creditAccount2, '-') == 5 && explode('-', $creditAccount2)[0] == 'truck') {
                        //truck account
                        $nameTruckAccount = Truck::where('id', explode('-', $creditAccount2)[1])->value('name');
                        $licensePlate = Truck::where('id', explode('-', $creditAccount2)[1])->value('licensePlate');
                        session()->flash('creditAccount2', $nameTruckAccount . ' - ' . $licensePlate);
                    } elseif (strpos($creditAccount2, '-') == 7 && explode('-', $creditAccount2)[0] == 'account') {
                        //others accounts (network, other)
                        $namePalletsAccount = Palletsaccount::where('id', explode('-', $creditAccount2)[1])->value('name');
                        session()->flash('creditAccount2', $namePalletsAccount);
                    }
                    if (strpos($debitAccount2, '-') == 5 && explode('-', $debitAccount2)[0] == 'truck') {
                        //truck account
                        $nameTruckAccount = Truck::where('id', explode('-', $debitAccount2)[1])->value('name');
                        $licensePlate = Truck::where('id', explode('-', $debitAccount2)[1])->value('licensePlate');
                        session()->flash('debitAccount2', $nameTruckAccount . ' - ' . $licensePlate);
                    } elseif (strpos($debitAccount2, '-') == 7 && explode('-', $debitAccount2)[0] == 'account') {
                        //others accounts (network, other)
                        $namePalletsAccount = Palletsaccount::where('id', explode('-', $debitAccount2)[1])->value('name');
                        session()->flash('debitAccount2', $namePalletsAccount);
                    }
                    session()->flash('palletsNumber2', $palletsNumber2);
                    session()->flash('palletsNumberCreditAccount2', $actualTheoricalDebitPalletsNumber - $palletsNumber);
                    session()->flash('palletsNumberDebitAccount2', $actualTheoricalCreditPalletsNumber + $palletsNumber);
                }

                return view('palletstransfers.addPalletstransfer', compact('anz', 'date', 'type', 'creditAccount', 'debitAccount', 'palletsNumber', 'creditAccount2', 'debitAccount2', 'palletsNumber2', 'addPalletstransfer', 'listPalletsAccounts', 'listTrucksAccounts', 'details', 'loading_atrnr', 'listAtrnr'));
            } elseif (isset($okSubmitAddModal)) {
                if (strpos($creditAccount, '-') == 5 && explode('-', $creditAccount)[0] == 'truck') {
                    //truck account
                    $nameTruckAccount = Truck::where('id', explode('-', $creditAccount)[1])->value('name');
                    $licensePlate = Truck::where('id', explode('-', $creditAccount)[1])->value('licensePlate');
                    $creditAccountTransfer = $nameTruckAccount . '-' . $licensePlate . '-' . $creditAccount;
                } elseif (strpos($creditAccount, '-') == 7 && explode('-', $creditAccount)[0] == 'account') {
                    //others accounts (network, other)
                    $namePalletsAccount = Palletsaccount::where('id', explode('-', $creditAccount)[1])->value('name');
                    $creditAccountTransfer = $namePalletsAccount . '-' . $creditAccount;
                }
                if (strpos($debitAccount, '-') == 5 && explode('-', $debitAccount)[0] == 'truck') {
                    //truck account
                    $nameTruckAccount = Truck::where('id', explode('-', $debitAccount)[1])->value('name');
                    $licensePlate = Truck::where('id', explode('-', $debitAccount)[1])->value('licensePlate');
                    $debitAccountTransfer = $nameTruckAccount . '-' . $licensePlate . '-' . $debitAccount;
                } elseif (strpos($debitAccount, '-') == 7 && explode('-', $debitAccount)[0] == 'account') {
                    //others accounts (network, other)
                    $namePalletsAccount = Palletsaccount::where('id', explode('-', $debitAccount)[1])->value('name');
                    $debitAccountTransfer = $namePalletsAccount . '-' . $debitAccount;
                }
                if (strpos($creditAccount2, '-') == 5 && explode('-', $creditAccount2)[0] == 'truck') {
                    //truck account
                    $nameTruckAccount = Truck::where('id', explode('-', $creditAccount2)[1])->value('name');
                    $licensePlate = Truck::where('id', explode('-', $creditAccount2)[1])->value('licensePlate');
                    $creditAccountTransfer2 = $nameTruckAccount . '-' . $licensePlate . '-' . $creditAccount2;
                } elseif (strpos($creditAccount2, '-') == 7 && explode('-', $creditAccount2)[0] == 'account') {
                    //others accounts (network, other)
                    $namePalletsAccount = Palletsaccount::where('id', explode('-', $creditAccount2)[1])->value('name');
                    $creditAccountTransfer2 = $namePalletsAccount . '-' . $creditAccount2;
                }
                if (strpos($debitAccount2, '-') == 5 && explode('-', $debitAccount2)[0] == 'truck') {
                    //truck account
                    $nameTruckAccount = Truck::where('id', explode('-', $debitAccount)[1])->value('name');
                    $licensePlate = Truck::where('id', explode('-', $debitAccount2)[1])->value('licensePlate');
                    $debitAccountTransfer2 = $nameTruckAccount . '-' . $licensePlate . '-' . $debitAccount2;
                } elseif (strpos($debitAccount2, '-') == 7 && explode('-', $debitAccount2)[0] == 'account') {
                    //others accounts (network, other)
                    $namePalletsAccount = Palletsaccount::where('id', explode('-', $debitAccount2)[1])->value('name');
                    $debitAccountTransfer2 = $namePalletsAccount . '-' . $debitAccount2;
                }

                $idErrorNotNumberLoading = Error::where('name', 'DW-WD_notNumberLoadingOrder')->first()->id;
                $idErrorNotSameNumber = Error::where('name', 'DW-WD_notSameNumber')->first()->id;
                if ($type == 'Deposit-Withdrawal') {
                    if (!isset($palletsNumber2)) {
                        if ($palletsNumber <> $anz) {
                            Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading_atrnr])->errors()->attach($idErrorNotNumberLoading);
                            Palletstransfer::create(['date' => $date, 'type' => 'Withdrawal-Deposit', 'details' => $details, 'creditAccount' => $creditAccountTransfer2, 'debitAccount' => $debitAccountTransfer2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading_atrnr, 'state' => 'Untreated'])->errors()->attach($idErrorNotNumberLoading);
                        } else {
                            Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccount, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading_atrnr]);
                            Palletstransfer::create(['date' => $date, 'type' => 'Withdrawal-Deposit', 'details' => $details, 'creditAccount' => $creditAccount2, 'debitAccount' => $debitAccountTransfer2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading_atrnr, 'state' => 'Untreated']);
                        }
                    } else {
                        if ($palletsNumber <> $palletsNumber2 && $palletsNumber <> $anz && $palletsNumber2 == $anz) {
                            Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading_atrnr])->errors()->attach([$idErrorNotSameNumber, $idErrorNotNumberLoading]);
                            Palletstransfer::create(['date' => $date, 'type' => 'Withdrawal-Deposit', 'details' => $details, 'creditAccount' => $creditAccountTransfer2, 'debitAccount' => $debitAccountTransfer2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading_atrnr])->errors()->attach($idErrorNotSameNumber);
                        } elseif ($palletsNumber <> $palletsNumber2 && $palletsNumber <> $anz && $palletsNumber2 <> $anz) {
                            Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading_atrnr])->errors()->attach([$idErrorNotSameNumber, $idErrorNotNumberLoading]);
                            Palletstransfer::create(['date' => $date, 'type' => 'Withdrawal-Deposit', 'details' => $details, 'creditAccount' => $creditAccountTransfer2, 'debitAccount' => $debitAccountTransfer2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading_atrnr])->errors()->attach([$idErrorNotSameNumber, $idErrorNotNumberLoading]);
                        } elseif ($palletsNumber <> $palletsNumber2 && $palletsNumber == $anz && $palletsNumber2 <> $anz) {
                            Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading_atrnr])->errors()->attach($idErrorNotNumberLoading);
                            Palletstransfer::create(['date' => $date, 'type' => 'Withdrawal-Deposit', 'details' => $details, 'creditAccount' => $creditAccountTransfer2, 'debitAccount' => $debitAccountTransfer2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading_atrnr])->errors()->attach([$idErrorNotSameNumber, $idErrorNotNumberLoading]);
                        } elseif ($palletsNumber == $palletsNumber2 && $palletsNumber <> $anz && $palletsNumber2 <> $anz) {
                            Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading_atrnr])->errors()->attach($idErrorNotNumberLoading);
                            Palletstransfer::create(['date' => $date, 'type' => 'Withdrawal-Deposit', 'details' => $details, 'creditAccount' => $creditAccountTransfer2, 'debitAccount' => $debitAccountTransfer2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading_atrnr])->errors()->attach($idErrorNotNumberLoading);
                        } elseif ($palletsNumber == $palletsNumber2 && $palletsNumber == $anz && $palletsNumber2 == $anz) {
                            Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading_atrnr]);
                            Palletstransfer::create(['date' => $date, 'type' => 'Withdrawal-Deposit', 'details' => $details, 'creditAccount' => $creditAccountTransfer2, 'debitAccount' => $debitAccountTransfer2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading_atrnr]);
                        }
                    }
                } elseif ($type == 'Withdrawal-Deposit') {
                    if (!isset($palletsNumber2)) {
                        if ($palletsNumber <> $anz) {
                            Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading_atrnr])->errors()->attach($idErrorNotNumberLoading);
                            Palletstransfer::create(['date' => $date, 'type' => 'Deposit-Withdrawal', 'details' => $details, 'creditAccount' => $creditAccountTransfer2, 'debitAccount' => $debitAccountTransfer2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading_atrnr, 'state' => 'Untreated'])->errors()->attach($idErrorNotNumberLoading);
                        } else {
                            Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading_atrnr]);
                            Palletstransfer::create(['date' => $date, 'type' => 'Deposit-Withdrawal', 'details' => $details, 'creditAccount' => $creditAccountTransfer2, 'debitAccount' => $debitAccountTransfer2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading_atrnr, 'state' => 'Untreated']);
                        }
                    } else {
                        if ($palletsNumber <> $palletsNumber2 && $palletsNumber <> $anz && $palletsNumber2 == $anz) {
                            Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading_atrnr])->errors()->attach([$idErrorNotSameNumber, $idErrorNotNumberLoading]);
                            Palletstransfer::create(['date' => $date, 'type' => 'Deposit-Withdrawal', 'details' => $details, 'creditAccount' => $creditAccountTransfer2, 'debitAccount' => $debitAccountTransfer2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading_atrnr])->errors()->attach($idErrorNotSameNumber);
                        } elseif ($palletsNumber <> $palletsNumber2 && $palletsNumber <> $anz && $palletsNumber2 <> $anz) {
                            Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading_atrnr])->errors()->attach([$idErrorNotSameNumber, $idErrorNotNumberLoading]);
                            Palletstransfer::create(['date' => $date, 'type' => 'Deposit-Withdrawal', 'details' => $details, 'creditAccount' => $creditAccountTransfer2, 'debitAccount' => $debitAccountTransfer2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading_atrnr])->errors()->attach([$idErrorNotSameNumber, $idErrorNotNumberLoading]);
                        } elseif ($palletsNumber <> $palletsNumber2 && $palletsNumber == $anz && $palletsNumber2 <> $anz) {
                            Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading_atrnr])->errors()->attach($idErrorNotNumberLoading);
                            Palletstransfer::create(['date' => $date, 'type' => 'Deposit-Withdrawal', 'details' => $details, 'creditAccount' => $creditAccountTransfer2, 'debitAccount' => $debitAccountTransfer2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading_atrnr])->errors()->attach([$idErrorNotSameNumber, $idErrorNotNumberLoading]);
                        } elseif ($palletsNumber == $palletsNumber2 && $palletsNumber <> $anz && $palletsNumber2 <> $anz) {
                            Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading_atrnr])->errors()->attach($idErrorNotNumberLoading);
                            Palletstransfer::create(['date' => $date, 'type' => 'Deposit-Withdrawal', 'details' => $details, 'creditAccount' => $creditAccountTransfer2, 'debitAccount' => $debitAccountTransfer2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading_atrnr])->errors()->attach($idErrorNotNumberLoading);
                        } elseif ($palletsNumber == $palletsNumber2 && $palletsNumber == $anz && $palletsNumber2 == $anz) {
                            Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading_atrnr]);
                            Palletstransfer::create(['date' => $date, 'type' => 'Deposit-Withdrawal', 'details' => $details, 'creditAccount' => $creditAccountTransfer2, 'debitAccount' => $debitAccountTransfer2, 'palletsNumber' => $palletsNumber2, 'loading_atrnr' => $loading_atrnr]);
                        }
                    }
                } else {
                    Palletstransfer::create(['date' => $date, 'type' => $type, 'details' => $details, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer, 'palletsNumber' => $palletsNumber, 'loading_atrnr' => $loading_atrnr]);
                }

                if (isset($creditAccount)) {
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
                if (isset($debitAccount)) {
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
                if (isset($creditAccount2) && isset($debitAccount2) && isset($palletsNumber2)) {
                    if (strpos($creditAccount2, '-') == 5 && explode('-', $creditAccount2)[0] == 'truck') {
                        //truck account
                        Truck::where('id', explode('-', $creditAccount2)[1])->update(['theoricalNumberPallets' => $actualTheoricalDebitPalletsNumber - $palletsNumber + $palletsNumber2]);
                        $palletsaccount_name = Truck::where('id', explode('-', $creditAccount2)[1])->value('palletsaccount_name');
                        Palletsaccount::where('name', $palletsaccount_name)->update(['theoricalNumberPallets' => Palletsaccount::where('name', $palletsaccount_name)->sum('theoricalNumberPallets')]);
                    } elseif (strpos($creditAccount2, '-') == 7 && explode('-', $creditAccount2)[0] == 'account') {
                        //others accounts (network, other)
                        Palletsaccount::where('id', explode('-', $creditAccount2)[1])->update(['theoricalNumberPallets' => $actualTheoricalDebitPalletsNumber - $palletsNumber + $palletsNumber2]);
                    }
                    if (strpos($debitAccount2, '-') == 5 && explode('-', $debitAccount2)[0] == 'truck') {
                        //truck account
                        Truck::where('id', explode('-', $debitAccount2)[1])->update(['theoricalNumberPallets' => $actualTheoricalCreditPalletsNumber + $palletsNumber - $palletsNumber2]);
                        $palletsaccount_name = Truck::where('id', explode('-', $debitAccount2)[1])->value('palletsaccount_name');
                        Palletsaccount::where('name', $palletsaccount_name)->update(['theoricalNumberPallets' => Palletsaccount::where('name', $palletsaccount_name)->sum('theoricalNumberPallets')]);
                    } elseif (strpos($debitAccount2, '-') == 7 && explode('-', $debitAccount2)[0] == 'account') {
                        //others accounts (network, other)
                        Palletsaccount::where('id', explode('-', $debitAccount2)[1])->update(['theoricalNumberPallets' => $actualTheoricalCreditPalletsNumber + $palletsNumber - $palletsNumber2]);
                    }
                }
                if(isset($loading_atrnr)){
                    $this->state($loading, Palletstransfer::where('loading_atrnr', $loading_atrnr)->get());
                }
                session()->flash('messageAddPalletstransfer', 'Successfully added new pallets transfer');
                return redirect('/allPalletstransfers');
            } elseif (isset($closeSubmitAddModal)) {
                return redirect()->back();
            }
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
            foreach (Loading::where('pt', 'JA')->orderBy('atrnr', 'asc')->get() as $loading) {
                $listAtrnr[] = $loading->atrnr;
            }
            $filesNames = $this->actualDocuments($id);
            $errors = $this->actualErrors($transfer);
            return view('palletstransfers.detailsPalletstransfer', compact('transfer', 'errors', 'listPalletsAccounts', 'listTrucksAccounts', 'listAtrnr', 'filesNames'));
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

        $listPalletsAccounts = Palletsaccount::where('type', 'Network')->orWhere('type', 'Other')->orderBy('name', 'asc')->get();
        $listTrucksAccounts = Truck::orderBy('name', 'asc')->get();
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
        } else {
            $rules = array(
                'creditAccount' => 'required',
                'debitAccount' => 'required',
            );
        }
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            session()->flash('errorFields', "The field(s) has(ve) not been filled as expected. REFILL !");
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
            } elseif (isset($update)) {
                if ($state == 'Complete Validated') {
                    $this->inverseRealPalletsNumber($transfer);
                }
                $filesNames = $this->actualDocuments($id);

                session()->put('actualCreditAccount', $transfer->creditAccount);
                session()->put('actualDebitAccount', $transfer->debitAccount);
                session()->put('actualPalletsNumber', $transfer->palletsNumber);
                session()->put('actualType', $transfer->type);
                session()->put('actualDetails', $transfer->details);
                session()->put('actualLoadingAtrnr', $transfer->loading_atrnr);
                session()->put('actualDate', $transfer->date);
                session()->put('actualValidate', $transfer->validate);
                session()->flash('palletsNumber', $palletsNumber);
                if (isset($creditAccount)) {
                    if (strpos($creditAccount, '-') == 5 && explode('-', $creditAccount)[0] == 'truck') {
                        //truck account
                        $nameTruckAccount = Truck::where('id', explode('-', $creditAccount)[1])->value('name');
                        $licensePlate = Truck::where('id', explode('-', $creditAccount)[1])->value('licensePlate');
                        session()->flash('creditAccount', $nameTruckAccount . ' - ' . $licensePlate);
                        session()->flash('thPalletsNumberCreditAccount', Truck::where('id', explode('-', $creditAccount)[1])->first()->theoricalNumberPallets);

                        $creditAccountTransfer = $nameTruckAccount . '-' . $licensePlate . '-' . $creditAccount;
                    } elseif (strpos($creditAccount, '-') == 7 && explode('-', $creditAccount)[0] == 'account') {
                        //others accounts (network, other)
                        $namePalletsAccount = Palletsaccount::where('id', explode('-', $creditAccount)[1])->value('name');
                        session()->flash('creditAccount', $namePalletsAccount);
                        session()->flash('thPalletsNumberCreditAccount', Palletsaccount::where('id', explode('-', $creditAccount)[1])->first()->theoricalNumberPallets);
                        $creditAccountTransfer = $namePalletsAccount . '-' . $creditAccount;
                    }
//                    session()->flash('thPalletsNumberCreditAccount', Palletsaccount::where('name', $creditAccount)->first()->theoricalNumberPallets);
//                    session()->flash('creditAccount', $creditAccount);
                }
                if (isset($debitAccount)) {
                    if (strpos($debitAccount, '-') == 5 && explode('-', $debitAccount)[0] == 'truck') {
                        //truck account
                        $nameTruckAccount = Truck::where('id', explode('-', $debitAccount)[1])->value('name');
                        $licensePlate = Truck::where('id', explode('-', $debitAccount)[1])->value('licensePlate');
                        session()->flash('debitAccount', $nameTruckAccount . ' - ' . $licensePlate);
                        session()->flash('thPalletsNumberDebitAccount', Truck::where('id', explode('-', $debitAccount)[1])->first()->theoricalNumberPallets);
                        $debitAccountTransfer = $nameTruckAccount . '-' . $licensePlate . '-' . $debitAccount;
                    } elseif (strpos($debitAccount, '-') == 7 && explode('-', $debitAccount)[0] == 'account') {
                        //others accounts (network, other)
                        $namePalletsAccount = Palletsaccount::where('id', explode('-', $debitAccount)[1])->value('name');
                        session()->flash('debitAccount', $namePalletsAccount);
                        session()->flash('thPalletsNumberDebitAccount', Palletsaccount::where('id', explode('-', $debitAccount)[1])->first()->theoricalNumberPallets);
                        $debitAccountTransfer = $namePalletsAccount . '-' . $debitAccount;
                    }
//                    session()->flash('thPalletsNumberDebitAccount', Palletsaccount::where('name', $debitAccount)->first()->theoricalNumberPallets);
//                    session()->flash('debitAccount', $debitAccount);
                }
                if (($transfer->type == 'Deposit-Withdrawal' || $transfer->type == 'Withdrawal-Deposit') && ($type <> 'Deposit-Withdrawal' || $type <> 'Withdrawal-Deposit')) {
                    $transfer->errors()->detach(Error::where('name', 'DW-WD_notSameNumber')->first()->id);
                    $transfer->errors()->detach(Error::where('name', 'DW-WD_notNumberLoadingOrder')->first()->id);
                }

                Palletstransfer::where('id', $id)->update(['type' => $type, 'details' => $details, 'loading_atrnr' => $loading_atrnr, 'palletsNumber' => $palletsNumber, 'date' => $date, 'creditAccount' => $creditAccountTransfer, 'debitAccount' => $debitAccountTransfer]);
                if ($validate <> null && $validate == 'true') {
                    Palletstransfer::where('id', $id)->update(['validate' => true]);
                } elseif ($validate <> null && $validate == 'false') {
                    Palletstransfer::where('id', $id)->update(['validate' => false]);
                }
                $transfer = Palletstransfer::where('id', $id)->first();
                if (isset($loading_atrnr)) {
                    $this->state(Loading::where('atrnr', $loading_atrnr)->where('pt', 'JA')->first(), Palletstransfer::where('loading_atrnr', $loading_atrnr)->get());
                }
                $anz=Loading::where('atrnr', $loading_atrnr)->first()->anz;
                return view('palletstransfers.detailsPalletstransfer', compact('anz','transfer', 'listPalletsAccounts', 'listTrucksAccounts','listAtrnr', 'update', 'filesNames'));
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
                    if (isset($transfer->creditAccount)) {
                        $partsCreditAccount = explode('-', $transfer->creditAccount);
                        $typeCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 2];
                        $idCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 1];

                        if ($typeCreditAccount == 'truck') {
                            session()->flash('realPalletsNumberCreditAccount', Truck::where('id', $idCreditAccount)->first()->realNumberPallets);
                        } elseif ($typeCreditAccount == 'account') {
                            session()->flash('realPalletsNumberCreditAccount', Palletsaccount::where('id', $idCreditAccount)->first()->realNumberPallets);}

//                        session()->flash('creditAccount', $transfer->creditAccount);
//                        session()->flash('realPalletsNumberCreditAccount', Palletsaccount::where('name', $transfer->creditAccount)->first()->realNumberPallets);
                    }
                    if (isset($transfer->debitAccount)) {
                        $partsDebitAccount = explode('-', $transfer->debitAccount);
                        $typeDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 2];
                        $idDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 1];

                        if ($typeDebitAccount == 'truck') {
                            session()->flash('realPalletsNumberDebitAccount', Truck::where('id', $idDebitAccount)->first()->realNumberPallets);
                        } elseif ($typeDebitAccount == 'account') {
                            session()->flash('realPalletsNumberDebitAccount', Palletsaccount::where('id', $idDebitAccount)->first()->realNumberPallets);
                        }
//                        session()->flash('debitAccount', $transfer->debitAccount);
//                        session()->flash('realPalletsNumberDebitAccount', Palletsaccount::where('name', $transfer->debitAccount)->first()->realNumberPallets);
                    }
                    $anz=Loading::where('atrnr', $loading_atrnr)->first()->anz;
                    return view('palletstransfers.detailsPalletstransfer', compact('anz','transfer', 'listNamesPalletsaccounts', 'listAtrnr', 'okSubmitUpdateModal', 'filesNames'));
                } else {
                    session()->pull('actualCreditAccount');
                    session()->pull('actualDebitAccount');
                    session()->pull('actualPalletsNumber');
                    session()->pull('actualType');
                    session()->pull('actualDetails');
                    session()->pull('actualLoadingAtrnr');
                    session()->pull('actualDate');
                    session()->pull('actualValidate');
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
                Palletstransfer::where('id', $id)->update(['validate' => $actualValidate, 'type' => $actualType, 'details' => $actualDetails, 'loading_atrnr' => $actualLoadingAtrnr, 'palletsNumber' => $actualPalletsNumber, 'date' => $actualDate, 'creditAccount' => $actualCreditAccount, 'debitAccount' => $actualDebitAccount]);
                if (isset($loading_atrnr)) {
                    $this->state(Loading::where('atrnr', $loading_atrnr)->where('pt', 'JA')->first(), Palletstransfer::where('loading_atrnr', $loading_atrnr)->get());
                }
                session()->pull('actualCreditAccount');
                session()->pull('actualDebitAccount');
                session()->pull('actualPalletsNumber');
                session()->pull('actualType');
                session()->pull('actualDetails');
                session()->pull('actualLoadingAtrnr');
                session()->pull('actualDate');
                session()->pull('actualValidate');
                return redirect()->back();
            } elseif (isset($okSubmitUpdateValidateModal)) {
                if (isset($transfer->creditAccount)) {
                    $partsCreditAccount = explode('-', $transfer->creditAccount);
                    $typeCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 2];
                    $idCreditAccount = $partsCreditAccount[count($partsCreditAccount) - 1];
                    if ($typeCreditAccount == 'truck') {
                        $realPalletsNumberCreditAccount = Truck::where('id', $idCreditAccount)->first()->realNumberPallets;
                        Truck::where('id', $idCreditAccount)->update(['realNumberPallets' => $realPalletsNumberCreditAccount + $transfer->palletsNumber]);
                        $palletsaccount_name = Truck::where('id', $idCreditAccount)->value('palletsaccount_name');
                        Palletsaccount::where('name', $palletsaccount_name)->update(['realNumberPallets' => Palletsaccount::where('name', $palletsaccount_name)->sum('realNumberPallets')]);
                    } elseif ($typeCreditAccount == 'account') {
                        $realPalletsNumberCreditAccount = Palletsaccount::where('id', $idCreditAccount)->first()->realNumberPallets;
                        Palletsaccount::where('id', $idCreditAccount)->update(['realNumberPallets' => $realPalletsNumberCreditAccount + $transfer->palletsNumber]);
                    }
//                    $realPalletsNumberCreditAccount = Palletsaccount::where('name', $transfer->creditAccount)->first()->realNumberPallets;
//                    Palletsaccount::where('name', $transfer->creditAccount)->update(['realNumberPallets' => $realPalletsNumberCreditAccount + $transfer->palletsNumber]);
                }
                if (isset($transfer->debitAccount)) {
                    $partsDebitAccount = explode('-', $transfer->debitAccount);
                    $typeDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 2];
                    $idDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 1];
                    if ($typeDebitAccount == 'truck') {
                        $realPalletsNumberDebitAccount = Truck::where('id', $idDebitAccount)->first()->realNumberPallets;
                        Truck::where('id', $idDebitAccount)->update(['realNumberPallets' => $realPalletsNumberDebitAccount - $transfer->palletsNumber]);
                        $palletsaccount_name = Truck::where('id', $idDebitAccount)->value('palletsaccount_name');
                        Palletsaccount::where('name', $palletsaccount_name)->update(['realNumberPallets' => Palletsaccount::where('name', $palletsaccount_name)->sum('realNumberPallets')]);
                    } elseif ($typeDebitAccount == 'account') {
                        $realPalletsNumberDebitAccount = Palletsaccount::where('id', $idDebitAccount)->first()->realNumberPallets;
                        Palletsaccount::where('id', $idDebitAccount)->update(['realNumberPallets' => $realPalletsNumberDebitAccount - $transfer->palletsNumber]);
                    }
//                    $realPalletsNumberDebitAccount = Palletsaccount::where('name', $transfer->debitAccount)->first()->realNumberPallets;
//                    Palletsaccount::where('name', $transfer->debitAccount)->update(['realNumberPallets' => $realPalletsNumberDebitAccount - $transfer->palletsNumber]);
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

            if($typeCreditAccount=='truck'){
                $actualPalletsNumberCreditAccount=Truck::where('id', $idCreditAccount)->first()->theoricalNumberPallets;
                Truck::where('id', $idCreditAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberCreditAccount - $transfer->palletsNumber]);
                $palletsaccount_name = Truck::where('id', $idCreditAccount)->value('palletsaccount_name');
                Palletsaccount::where('name', $palletsaccount_name)->update(['theoricalNumberPallets' => Palletsaccount::where('name', $palletsaccount_name)->sum('theoricalNumberPallets')]);
            }elseif($typeCreditAccount=='account'){
                $actualPalletsNumberCreditAccount = Palletsaccount::where('id', $idCreditAccount)->first()->theoricalNumberPallets;
                Palletsaccount::where('id', $idCreditAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberCreditAccount - $transfer->palletsNumber]);
            }
           }
        if (isset($transfer->debitAccount)) {
            if($typeDebitAccount=='truck'){
                $actualPalletsNumberDebitAccount=Truck::where('id', $idDebitAccount)->first()->theoricalNumberPallets;
                Truck::where('id', $idDebitAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberDebitAccount + $transfer->palletsNumber]);
                $palletsaccount_name = Truck::where('id', $idDebitAccount)->value('palletsaccount_name');
                Palletsaccount::where('name', $palletsaccount_name)->update(['theoricalNumberPallets' => Palletsaccount::where('name', $palletsaccount_name)->sum('theoricalNumberPallets')]);
            }elseif($typeDebitAccount=='account'){
                $actualPalletsNumberDebitAccount = Palletsaccount::where('id', $idDebitAccount)->first()->theoricalNumberPallets;
                Palletsaccount::where('id', $idDebitAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberDebitAccount + $transfer->palletsNumber]);
            }}

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
            $actualRealPalletsNumberCreditAccount = Palletsaccount::where('name', $transfer->creditAccount)->first()->realNumberPallets;
            Palletsaccount::where('name', $transfer->creditAccount)->update(['realNumberPallets' => $actualRealPalletsNumberCreditAccount - $transfer->palletsNumber]);
        }
        if (isset($transfer->debitAccount)) {
            $actualRealPalletsNumberDebitAccount = Palletsaccount::where('name', $transfer->debitAccount)->first()->realNumberPallets;
            Palletsaccount::where('name', $transfer->debitAccount)->update(['realNumberPallets' => $actualRealPalletsNumberDebitAccount + $transfer->palletsNumber]);
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
                Palletsaccount::where('name', $palletsaccount_name)->update(['theoricalNumberPallets' => Palletsaccount::where('name', $palletsaccount_name)->sum('theoricalNumberPallets')]);
            } elseif ($typeCreditAccount == 'account') {
                $actualPalletsNumberCreditAccount = Palletsaccount::where('id', $idCreditAccount)->first()->theoricalNumberPallets;
                Palletsaccount::where('id', $idCreditAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberCreditAccount - $actualPalletsNumber]);
            }
//            $actualPalletsNumberCreditAccount = Palletsaccount::where('name', $actualCreditAccount)->first()->theoricalNumberPallets;
//            Palletsaccount::where('name', $actualCreditAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberCreditAccount - $actualPalletsNumber]);
        }
        if (isset($actualDebitAccount)) {
            $partsDebitAccount = explode('-', $actualDebitAccount);
            $typeDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 2];
            $idDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 1];

            if ($typeDebitAccount == 'truck') {
                $actualPalletsNumberDebitAccount = Truck::where('id', $idDebitAccount)->first()->theoricalNumberPallets;
                Truck::where('id', $idDebitAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberDebitAccount + $actualPalletsNumber]);
                $palletsaccount_name = Truck::where('id', $idDebitAccount)->value('palletsaccount_name');
                Palletsaccount::where('name', $palletsaccount_name)->update(['theoricalNumberPallets' => Palletsaccount::where('name', $palletsaccount_name)->sum('theoricalNumberPallets')]);
            } elseif ($typeDebitAccount == 'account') {
                $actualPalletsNumberDebitAccount = Palletsaccount::where('id', $idDebitAccount)->first()->theoricalNumberPallets;
                Palletsaccount::where('id', $idDebitAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberDebitAccount + $actualPalletsNumber]);
            }
//            $actualPalletsNumberDebitAccount = Palletsaccount::where('name', $actualDebitAccount)->first()->theoricalNumberPallets;
//            Palletsaccount::where('name', $actualDebitAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberDebitAccount + $actualPalletsNumber]);
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
                Palletsaccount::where('name', $palletsaccount_name)->update(['theoricalNumberPallets' => Palletsaccount::where('name', $palletsaccount_name)->sum('theoricalNumberPallets')]);
            } elseif ($typeCreditAccount == 'account') {
                $palletsNumberCreditAccount = Palletsaccount::where('id', $idCreditAccount)->first()->theoricalNumberPallets;
                Palletsaccount::where('id', $idCreditAccount)->update(['theoricalNumberPallets' => $palletsNumberCreditAccount + $transfer->palletsNumber]);
            }
//            $palletsNumberCreditAccount = Palletsaccount::where('name', $transfer->creditAccount)->first()->theoricalNumberPallets;
//            Palletsaccount::where('name', $transfer->creditAccount)->update(['theoricalNumberPallets' => $palletsNumberCreditAccount + $transfer->palletsNumber]);
        }
        if (isset($transfer->debitAccount)) {
            $partsDebitAccount = explode('-', $transfer->debitAccount);
            $typeDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 2];
            $idDebitAccount = $partsDebitAccount[count($partsDebitAccount) - 1];

            if ($typeDebitAccount == 'truck') {
                $palletsNumberDebitAccount = Truck::where('id', $idDebitAccount)->first()->theoricalNumberPallets;
                Truck::where('id', $idDebitAccount)->update(['theoricalNumberPallets' => $palletsNumberDebitAccount - $transfer->palletsNumber]);
                $palletsaccount_name = Truck::where('id', $idDebitAccount)->value('palletsaccount_name');
                Palletsaccount::where('name', $palletsaccount_name)->update(['theoricalNumberPallets' => Palletsaccount::where('name', $palletsaccount_name)->sum('theoricalNumberPallets')]);
            } elseif ($typeDebitAccount == 'account') {
                $palletsNumberDebitAccount = Palletsaccount::where('id', $idDebitAccount)->first()->theoricalNumberPallets;
                Palletsaccount::where('id', $idDebitAccount)->update(['theoricalNumberPallets' => $palletsNumberDebitAccount - $transfer->palletsNumber]);
            }
//            $palletsNumberDebitAccount = Palletsaccount::where('name', $transfer->debitAccount)->first()->theoricalNumberPallets;
//            Palletsaccount::where('name', $transfer->debitAccount)->update(['theoricalNumberPallets' => $palletsNumberDebitAccount - $transfer->palletsNumber]);
        }

        if (!empty($actualDoc) && $transfer->validate == 1) {
            $state = 'Complete Validated';
        } elseif (!empty($actualDoc) && $transfer->validate == 0) {
            $state = 'Complete';
        } elseif (empty($actualDoc)) {
            $state = 'Waiting documents';
        }
        Palletstransfer::where('id', $transfer->id)->update(['state' => $state]);
        session()->flash('messageUpdatePalletstransfer', 'Successfully updated pallets transfer');
    }

    public function state($loading, $listPalletstransfers)
    {
        if ($listPalletstransfers->isEmpty()) {
            $state = 'Untreated';
        } else {
            //////STATE GENERAL////
             $stateCompleteValidated = 0;
            $stateComplete = 0;
            $stateWaitingDocuments = 0;
            $stateInProgress = 0;
            foreach ($listPalletstransfers as $transfer) {
                if ($transfer->state == 'Complete Validated') {
                    $stateCompleteValidated++;
                } elseif ($transfer->state == 'Complete') {
                    $stateComplete++;
                } elseif ($transfer->state == 'Waiting documents') {
                    $stateWaitingDocuments++;
                } elseif ($transfer->state == 'In progress') {
                    $stateInProgress++;
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
}

