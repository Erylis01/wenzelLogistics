<?php

namespace App\Http\Controllers;

use App\Loading;
use App\Palletsaccount;
use App\Palletstransfer;
use App\Truck;
use App\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

/**
 * Class PalletsaccountsController
 * @package App\Http\Controllers
 */
class PalletsaccountsController extends Controller
{
    /**
     * Display the content.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAll(Request $request)
    {
        $searchQuery = $request->get('search');
        $searchQueryArray = explode(' ', $searchQuery);
        $searchColumns = $request->get('searchColumns');
        $listColumns = ['name', 'type', 'realNumberPallets'];
        if (Auth::check()) {
            $totalpallets = DB::table('palletsaccounts')->sum('realNumberPallets');

            $query = DB::table('palletsaccounts');

            //if the user is sorting the table
            if (request()->has('sortby') && request()->has('order')) {
                $sortby = $request->get('sortby'); // Order by what column?
                $order = $request->get('order'); // Order direction: asc or desc
                $searchColumnsString = $request->get('searchColumnsString');;
                $searchColumns = explode('-', $searchColumnsString);
                //if the user is searching data in the table
                if (isset($searchQuery) && $searchQuery <> '') {
                    //searching in all columns
                    if (in_array('ALL', explode('-', $searchColumnsString))) {
                        $query->where(function ($q) use ($searchQueryArray, $listColumns) {
                            foreach ($listColumns as $column) {
                                foreach ($searchQueryArray as $searchQ) {
                                    $q->orWhere($column, 'LIKE', '%' . $searchQ . '%');
                                }
                            }
                        });
                    } else {
                        //Searching in specifics columns
                        $query->where(function ($q) use ($searchQueryArray, $searchColumns) {
                            foreach ($searchColumns as $column) {
                                foreach ($searchQueryArray as $searchQ) {
                                    $q->orWhere($column, 'LIKE', '%' . $searchQ . '%');
                                }
                            }
                        });
                    }
                }
                $listPalletsaccounts = $query->orderBy($sortby, $order)->get();
            } else {
                //only searching
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
                //not sorting nor searching
                $listPalletsaccounts = $query->orderBy('name', 'asc')->get();
            }
            return view('palletsaccounts.allPalletsaccounts', compact('listPalletsaccounts', 'totalpallets', 'sortby', 'order', 'searchQuery', 'searchColumns', 'searchColumnsString', 'listColumns'));
        } else {
            return view('auth.login');
        }
    }

    /** show the form to add a new pallets account
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAdd()
    {
        if (Auth::check()) {
            //list of the warehouses if the account created is a Network
            $listWarehouses = DB::table('warehouses')->get();
            return view('palletsaccounts.addPalletsaccount', compact('listWarehouses'));
        } else {
            return view('auth.login');
        }
    }

    /**
     * add a new pallets account to the list
     */
    public function add(Request $request)
    {
        //get data
        $name = Input::get('name');
        $nickname = Input::get('nickname');
        $type = Input::get('type');
        $realNumberPallets = Input::get('realNumberPallets');
        $theoricalNumberPallets = $realNumberPallets;

        //get the warehouses associated if the account is a Network
        $warehousesAssociatedName = Input::get('warehousesAssociated');
        if (isset($warehousesAssociatedName)) {
            foreach ($warehousesAssociatedName as $nameWarehouse) {
                $idwarehouses[] = Warehouse::where('name', $nameWarehouse)->value('id');
            }
        }

        //get carrier account special information
        $adress = Input::get('adress');
        $phone = Input::get('phone');
        $email = Input::get('email');
        $namecontact = Input::get('namecontact');

        //validation
        $rules = array(
            'name' => 'required|string|max:255|unique:palletsaccounts',
        );
        if (isset($email)) {
            $rules = array_add($rules, 'email', 'string|email');
        }
        if (isset($phone)) {
            $rules = array_add($rules, 'phone', 'string|max:15');
        }
        if (isset($nickname)) {
            $rules = array_add($rules, 'nickname', 'string|max:15|unique:palletsaccounts');
        }
        $validator = Validator::make(Input::all(), $rules);
//if the rules are not respected
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            //pallets account creation
            if ($type == 'Network') {
                Palletsaccount::create(
                    ['name' => $name, 'nickname' => $nickname, 'realNumberPallets' => $realNumberPallets, 'theoricalNumberPallets' => $theoricalNumberPallets, 'type' => $type]
                );
            } elseif ($type == 'Network' && isset($warehousesAssociatedName)) {
                Palletsaccount::create(
                    ['name' => $name, 'nickname' => $nickname, 'realNumberPallets' => $realNumberPallets, 'theoricalNumberPallets' => $theoricalNumberPallets, 'type' => $type]
                )->warehouses()->sync($idwarehouses);
            } elseif ($type == 'Carrier') {
                Palletsaccount::create(
                    ['name' => $name, 'nickname' => $nickname, 'realNumberPallets' => 0, 'theoricalNumberPallets' => 0, 'type' => $type, 'adress' => $adress, 'email' => $email, 'phone' => $phone, 'namecontact' => $namecontact]
                );
            } elseif ($type == 'Other') {
                Palletsaccount::create(
                    ['name' => $name, 'nickname' => $nickname, 'realNumberPallets' => $realNumberPallets, 'theoricalNumberPallets' => $theoricalNumberPallets, 'type' => $type]
                );
            }
//redirect
            session()->flash('messageAddPalletsaccount', 'Successfully added new pallets account');
            return redirect('/allPalletsaccounts');
        }
    }

    /**
     * show a specific pallets account
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public
    function showDetails($id, Request $request)
    {
        if (Auth::check()) {
            //general data
            $listWarehouses = DB::table('warehouses')->get();
            $account = Palletsaccount::where('id', $id)->first();
            $name = $account->name;

            //according to the type of account, get the right entities associated
            if ($account->type == 'Network') {
                $warehousesAssociated = DB::table('palletsaccount_warehouse')->where('palletsaccount_id', $id)->get();
                foreach ($warehousesAssociated as $warehouse) {
                    $namewarehouses[] = Warehouse::where('id', $warehouse->warehouse_id)->value('name');
                }
            } elseif ($account->type == 'Carrier') {
                $trucksAssociated = Truck::where('palletsaccount_name', $name)->get();
            }

            //table data transfers
            //search data
            $searchQuery = $request->get('search');
            $searchQueryArray = explode(' ', $searchQuery);
            $searchColumns = $request->get('searchColumns');
            if ($account->type == 'Carrier') {
                $listColumns = ['id', 'type', 'palletsNumber', 'loading_atrnr', 'date', 'licensePlate', 'state'];
            } else {
                $listColumns = ['id', 'type', 'palletsNumber', 'loading_atrnr', 'date', 'state'];
            }

            $query = Palletstransfer::where(function ($q) use ($name) {
                $q->where('creditAccount',  'LIKE',  $name. '-' .'%')->orWhere('debitAccount', 'LIKE',$name. '-' .'%');
            });

            //if the user is sorting the table
            if (request()->has('sortby') && request()->has('order')) {
                $sortby = $request->get('sortby'); // Order by what column?
                $order = $request->get('order'); // Order direction: asc or desc
                $searchColumnsString = $request->get('searchColumnsString');;
                $searchColumns = explode('-', $searchColumnsString);
                //if sorting while searching something in the table
                if (isset($searchQuery) && $searchQuery <> '') {
                    //if searching in the whole table
                    if (in_array('ALL', explode('-', $searchColumnsString))) {
                        $query->where(function ($q) use ($searchQueryArray, $listColumns) {
                            foreach ($listColumns as $column) {
                                foreach ($searchQueryArray as $searchQ) {
                                    $q->orWhere($column, 'LIKE', '%' . $searchQ . '%');
                                }
                            }
                        });
                    } else {
                        //if searching in special columns
                        $query->where(function ($q) use ($searchQueryArray, $searchColumns) {
                            foreach ($searchColumns as $column) {
                                foreach ($searchQueryArray as $searchQ) {
                                    $q->orWhere($column, 'LIKE', '%' . $searchQ . '%');
                                }
                            }
                        });
                    }
                }
//if searching in the license plate columns that is not a field of the data table Pallets account but Truck
                if ($sortby == 'licensePlate') {
                    $listTransfers = $query->with(['trucks' => function ($query, $order) {
                        $query->orderBy('licensePlate', $order);
                    }])->get();
                } else {
                    $listTransfers = $query->orderBy($sortby, $order)->get();
                }
            } else {
                //if not sorting but searching
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
                $listTransfers = $query->orderBy('id', 'asc')->get();
            }

            return view('palletsaccounts.detailsPalletsaccount', compact('searchQuery', 'listColumns', 'searchColumnsString', 'searchColumns', 'listTransfers', 'listWarehouses', 'account', 'namewarehouses', 'trucksAssociated'));
        } else {
            return view('auth.login');
        }
    }

    /**
     * update the pallets account n° ID - if carrier : clear trucks possible
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $clearTrucks = Input::get('clearTrucks');

        if (isset($clearTrucks)) {
            //CARRIER ONLY : this button will clear every pallets number for ervery truck and put the sum in the "truck" STOCK that is the stock of the carrier
            $palletsaccount_name = Palletsaccount::where('id', $id)->first()->name;
            $listTrucks = Truck::where('palletsaccount_name', $palletsaccount_name)->get();
            foreach ($listTrucks as $truck) {
                if ($truck->licensePlate <> 'STOCK') {
                    $realNumberPallets = $truck->realNumberPallets;
                    $theoricalNumberPallets = $truck->theoricalNumberPallets;
                    Truck::where('palletsaccount_name', $palletsaccount_name)->where('licensePlate', $truck->licensePlate)->update(['realNumberPallets' => 0, 'theoricalNumberPallets' => 0]);
                    $realNumberPalletsStock = Truck::where('palletsaccount_name', $palletsaccount_name)->where('licensePlate', 'STOCK')->first()->realNumberPallets;
                    $theoricalNumberPalletsStock = Truck::where('palletsaccount_name', $palletsaccount_name)->where('licensePlate', 'STOCK')->first()->theoricalNumberPallets;
                    Truck::where('palletsaccount_name', $palletsaccount_name)->where('licensePlate', 'STOCK')->update(['realNumberPallets' => $realNumberPalletsStock + $realNumberPallets, 'theoricalNumberPallets' => $theoricalNumberPalletsStock + $theoricalNumberPallets]);
                }
            }
            session()->flash('messageClearTrucks', 'Successfully cleared trucks');
        } else {
            //update data
            $nickname = Input::get('nickname');
            Palletsaccount::where('id', $id)->update(['nickname' => $nickname]);
            $type = Input::get('type');
            Palletsaccount::where('id', $id)->update(['type' => $type]);
            if (isset($type) && $type == 'Network') {
                $warehousesAssociatedName = Input::get('namewarehouses');
                if (isset($warehousesAssociatedName)) {
                    foreach ($warehousesAssociatedName as $warehouseAName) {
                        $idwarehouses[] = Warehouse::where('name', $warehouseAName)->value('id');
                    }
                    Palletsaccount::where('id', $id)->first()->warehouses()->sync($idwarehouses);
                }
            } elseif (isset($type) && $type == 'Carrier') {
                $adress = Input::get('adress');
                $phone = Input::get('phone');
                $email = Input::get('email');
                $namecontact = Input::get('namecontact');

                if (isset($adress)) {
                    Palletsaccount::where('id', $id)->update(['adress' => $adress]);
                }
                if (isset($phone)) {
                    Palletsaccount::where('id', $id)->update(['phone' => $phone]);
                }
                if (isset($email)) {
                    Palletsaccount::where('id', $id)->update(['email' => $email]);
                }
                if (isset($namecontact)) {
                    Palletsaccount::where('id', $id)->update(['namecontact' => $namecontact]);
                }
            }
            session()->flash('messageUpdatePalletsaccount', 'Successfully updated pallets account');
        }
        return redirect()->back();
    }

    /** delete a specific pallets account
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete($id)
    {
        DB::table('palletsaccounts')->where('id', $id)->delete();
        // redirect
        session()->flash('messageDeletePalletsaccount', 'Successfully deleted the pallets account!');
        return redirect('/allPalletsaccounts');
    }

}
