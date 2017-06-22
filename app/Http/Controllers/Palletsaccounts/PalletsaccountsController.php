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

            if (request()->has('sortby') && request()->has('order')) {
                $sortby = $request->get('sortby'); // Order by what column?
                $order = $request->get('order'); // Order direction: asc or desc
                $searchColumnsString=$request->get('searchColumnsString');;
                $searchColumns=explode('-', $searchColumnsString);
                if (isset($searchQuery) && $searchQuery <> '') {
                    if (in_array('ALL', explode('-',$searchColumnsString ))) {
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
                $listPalletsaccounts = $query->orderBy($sortby, $order)->get();
            }else {
                if (isset($searchQuery) && $searchQuery <> '') {
                    $searchColumnsString=implode('-',$searchColumns);
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
                $listPalletsaccounts = $query->get();
            }
        return view('palletsaccounts.allPalletsaccounts', compact('listPalletsaccounts', 'totalpallets', 'sortby', 'order', 'searchQuery', 'searchColumns','searchColumnsString', 'listColumns'));
    } else
{
return view('auth.login');
}
}

/** show the add form
 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
 */
public
function showAdd()
{
    if (Auth::check()) {
        $listWarehouses = DB::table('warehouses')->get();
        $listTrucks = DB::table('trucks')->where('palletsaccount_name', null)->get();
        return view('palletsaccounts.addPalletsaccount', compact('listWarehouses', 'listTrucks'));
    } else {
        return view('auth.login');
    }
}

/**
 * add a new pallets account to the list
 */
public
function add(Request $request)
{
    //get data
    $name = Input::get('name');
    $nickname = Input::get('nickname');
    $type = Input::get('type');
    $realNumberPallets = Input::get('realNumberPallets');
    $theoricalNumberPallets = $realNumberPallets;

    $warehousesAssociatedName = Input::get('warehousesAssociated');
    if (isset($warehousesAssociatedName)) {
        foreach ($warehousesAssociatedName as $nameWarehouse) {
            $idwarehouses[] = Warehouse::where('name', $nameWarehouse)->value('id');
        }
    }

    $trucksAssociated = Input::get('trucksAssociated');
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

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    } else {
        if ($type == 'Network') {
            Palletsaccount::create(
                ['name' => $name, 'nickname' => $nickname, 'realNumberPallets' => $realNumberPallets, 'theoricalNumberPallets' => $theoricalNumberPallets, 'type' => $type]
            );
        } elseif($type == 'Network' && isset($warehousesAssociatedName)) {
            Palletsaccount::create(
                ['name' => $name, 'nickname' => $nickname, 'realNumberPallets' => $realNumberPallets, 'theoricalNumberPallets' => $theoricalNumberPallets, 'type' => $type]
            )->warehouses()->sync($idwarehouses);
        } elseif ($type == 'Carrier') {
            Palletsaccount::create(
                ['name' => $name, 'nickname' => $nickname, 'realNumberPallets' => $realNumberPallets, 'theoricalNumberPallets' => $theoricalNumberPallets, 'type' => $type, 'adress' => $adress, 'email' => $email, 'phone' => $phone, 'namecontact' => $namecontact]
            );
            if (isset($trucksAssociated)) {
                foreach ($trucksAssociated as $truckA) {
                    Truck::where('name', explode(' - ', $truckA)[0])->where('licensePlate', explode(' - ', $truckA)[1])->update(['palletsaccount_name' => $name]);
                }
            }
        } elseif ($type == 'Other') {
            Palletsaccount::create(
                ['name' => $name, 'nickname' => $nickname, 'realNumberPallets' => $realNumberPallets, 'theoricalNumberPallets' => $theoricalNumberPallets, 'type' => $type]
            );
        }

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
        $palletsaccount = DB::table('palletsaccounts')->where('id', '=', $id)->first();
//        $totalpallets = DB::table('palletsaccounts')->sum('realNumberPallets');
        $listWarehouses = DB::table('warehouses')->get();
//        $listTrucks = DB::table('trucks')->where('palletsaccount_name', null)->get();
        $name = $palletsaccount->name;
        $nickname = $palletsaccount->nickname;
        $type = $palletsaccount->type;
        $realNumberPallets = $palletsaccount->realNumberPallets;
        $theoricalNumberPallets = $palletsaccount->theoricalNumberPallets;

        if ($type == 'Network') {
            $warehousesAssociated = DB::table('palletsaccount_warehouse')->where('palletsaccount_id', $id)->get();
            foreach ($warehousesAssociated as $warehouse) {
                $namewarehouses[] = Warehouse::where('id', $warehouse->warehouse_id)->value('name');
            }
        } elseif ($type == 'Carrier') {
            $adress = $palletsaccount->adress;
            $phone = $palletsaccount->phone;
            $namecontact = $palletsaccount->namecontact;
            $email = $palletsaccount->email;
            $trucksAssociated = Truck::where('palletsaccount_name', $name)->get();
        }

        //table data
        $searchQuery = $request->get('search');
        $searchQueryArray = explode(' ', $searchQuery);
        $searchColumns = $request->get('searchColumns');
        $listColumns = ['id', 'type', 'palletsNumber','loading_atrnr', 'date', 'creditAccount', 'debitAccount'];

        $query = Palletstransfer::where(function ($q) use($name){
            $q->where('creditAccount', $name)->orWhere('debitAccount', $name);
        });

        if (request()->has('sortby') && request()->has('order')) {
            $sortby = $request->get('sortby'); // Order by what column?
            $order = $request->get('order'); // Order direction: asc or desc
            $searchColumnsString=$request->get('searchColumnsString');;
            $searchColumns=explode('-', $searchColumnsString);
            if (isset($searchQuery) && $searchQuery <> '') {
                if (in_array('ALL', explode('-',$searchColumnsString ))) {
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
            $listTransfers = $query->orderBy($sortby, $order)->get();
        }else {
            if (isset($searchQuery) && $searchQuery <> '') {
                $searchColumnsString=implode('-',$searchColumns);
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
            $listTransfers = $query->get();
        }

        return view('palletsaccounts.detailsPalletsaccount', compact( 'searchQuery', 'listColumns','searchColumnsString','searchColumns','listTransfers', 'listWarehouses',  'id', 'name', 'nickname', 'realNumberPallets', 'theoricalNumberPallets', 'type', 'namewarehouses', 'trucksAssociated', 'adress', 'email', 'phone', 'namecontact'));
    } else {
        return view('auth.login');
    }
}

/**
 * update the pallets account n° ID
 * @param Request $request
 * @param $id
 * @return \Illuminate\Http\RedirectResponse
 */
public
function update(Request $request, $id)
{
//    $account=Palletsaccount::where('id',$id)->first();
        $nickname = Input::get('nickname');
        Palletsaccount::where('id', $id)->update(['nickname' => $nickname]);
        $type = Input::get('type');
        Palletsaccount::where('id', $id)->update(['type' => $type]);
        if(isset($type) && $type=='Network'){
            $warehousesAssociatedName = Input::get('namewarehouses');

            if (isset($warehousesAssociatedName)) {
                foreach ($warehousesAssociatedName as $warehouseAName) {
                    $idwarehouses[] = Warehouse::where('name', $warehouseAName)->value('id');
                }
                Palletsaccount::where('id', $id)->first()->warehouses()->sync($idwarehouses);
            }
        }elseif(isset($type) && $type=='Carrier'){
//            $trucksAssociatedName=Input::get('trucksAssociated');
            $adress=Input::get('adress');
            $phone=Input::get('phone');
            $email=Input::get('email');
            $namecontact=Input::get('namecontact');
//            if(isset($trucksAssociatedName)){
//                foreach ($trucksAssociatedName as $truckAName) {
//                    $idtrucks[] = Warehouse::where('name', $truckAName)->value('id');
//                }
//                Truck::where('id', $idtrucks)->update(['palletsaccount_name'=>$account->name]);
//            }
            if(isset($adress)){
                Palletsaccount::where('id', $id)->update(['adress'=>$adress]);
            }
            if(isset($phone)){
                Palletsaccount::where('id', $id)->update(['phone'=>$phone]);
            }
            if(isset($email)){
                Palletsaccount::where('id', $id)->update(['email'=>$email]);
            }
            if(isset($namecontact)){
                Palletsaccount::where('id', $id)->update(['namecontact'=>$namecontact]);
            }
        }

        session()->flash('messageUpdatePalletsaccount', 'Successfully updated pallets account');
        return redirect()->back();
}

/** delete a specific pallets account
 * @param $id
 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
 */
public
function delete($id)
{
    DB::table('palletsaccounts')->where('id', $id)->delete();
    // redirect
    session()->flash('messageDeletePalletsaccount', 'Successfully deleted the pallets account!');
    return redirect('/allPalletsaccounts');
}

}
