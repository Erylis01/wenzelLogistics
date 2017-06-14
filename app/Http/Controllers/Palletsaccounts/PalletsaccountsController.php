<?php

namespace App\Http\Controllers;

use App\Loading;
use App\Palletsaccount;
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
        $searchColumn = $request->get('searchColumn');
        $listColumns = ['name', 'type', 'realNumberPallets'];
        if (Auth::check()) {
            $totalpallets = DB::table('palletsaccounts')->sum('realNumberPallets');
            if (request()->has('sortby') && request()->has('order')) {
                $sortby = $request->get('sortby'); // Order by what column?
                $order = $request->get('order'); // Order direction: asc or desc
                if (isset($searchQuery) && $searchQuery <> '') {
                    //search query
                    if ($searchColumn == 'all') {
                        $listPalletsaccounts = DB::table('palletsaccounts')
                            ->where(function ($q) use ($searchQuery, $listColumns) {
                                $q->where($listColumns[0], 'LIKE', '%' . $searchQuery . '%')
                                    ->orWhere($listColumns[1], 'LIKE', '%' . $searchQuery . '%')
                                    ->orWhere($listColumns[2], 'LIKE', '%' . $searchQuery . '%');
                            })->orderBy($sortby, $order)->paginate(10);
                    } else {
                        $listPalletsaccounts = DB::table('palletsaccounts')
                            ->where($searchColumn, 'LIKE', '%' . $searchQuery . '%')->paginate(10);
                    }
                } else {
                    $listPalletsaccounts = DB::table('palletsaccounts')->orderBy($sortby, $order)->get();
                }
            } else {
                if (isset($searchQuery) && $searchQuery <> '') {
                    //search query
                    if ($searchColumn == 'all') {
                        $listPalletsaccounts = DB::table('palletsaccounts')
                            ->where(function ($q) use ($searchQuery, $listColumns) {
                                $q->where($listColumns[0], 'LIKE', '%' . $searchQuery . '%')
                                    ->orWhere($listColumns[1], 'LIKE', '%' . $searchQuery . '%')
                                    ->orWhere($listColumns[2], 'LIKE', '%' . $searchQuery . '%');
                            })->paginate(10);
                    } else {
                        $listPalletsaccounts = DB::table('palletsaccounts')
                            ->where($searchColumn, 'LIKE', '%' . $searchQuery . '%')->paginate(10);
                    }
                } else {
                    $listPalletsaccounts = DB::table('palletsaccounts')->get();
                }
            }
            return view('palletsaccounts.allPalletsaccounts', compact('listPalletsaccounts', 'totalpallets', 'sortby', 'order', 'searchQuery', 'searchColumn', 'listColumns'));
        } else {
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
            $listTrucks = DB::table('trucks')->get();
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
        $type = Input::get('type');
        $realNumberPallets = Input::get('realNumberPallets');
        $theoricalNumberPallets = $realNumberPallets;
        $warehousesAssociatedName = Input::get('warehousesAssociated');
        if (isset($warehousesAssociatedName)) {
            foreach ($warehousesAssociatedName as $nameWarehouse) {
                $idwarehouses[] = Warehouse::where('name', $nameWarehouse)->value('id');
            }
        }

        //validation
        $rules = array(
            'name' => 'required|string|max:255|unique:palletsaccounts',
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            if (isset($warehousesAssociatedName)) {
                Palletsaccount::create(
                    ['name' => $name, 'realNumberPallets' => $realNumberPallets, 'theoricalNumberPallets' => $theoricalNumberPallets, 'type' => $type]
                )->warehouses()->sync($idwarehouses);
            } else {
                Palletsaccount::create(
                    ['name' => $name, 'realNumberPallets' => $realNumberPallets, 'theoricalNumberPallets' => $theoricalNumberPallets, 'type' => $type]
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

            $totalpallets = DB::table('palletsaccounts')->sum('realNumberPallets');
            $listWarehouses = DB::table('warehouses')->get();

            $name = $palletsaccount->name;
            $type = $palletsaccount->type;
            $realNumberPallets = $palletsaccount->realNumberPallets;
            $theoricalNumberPallets = $palletsaccount->theoricalNumberPallets;
            $warehousesAssociated = DB::table('palletsaccount_warehouse')->where('palletsaccount_id', $id)->get();
            foreach ($warehousesAssociated as $warehouse) {
                $namewarehouses[] = Warehouse::where('id', $warehouse->warehouse_id)->value('name');
            }

            //table data
            $currentDate = Carbon::now();
            $limitDate = $currentDate->subDays(60)->format('Y-m-d');
            $searchQuery = $request->get('search');
            $searchColumn = $request->get('searchColumn');
            $listColumns = ['atrnr', 'date', 'subfrachter', 'planned pallets nbr'];

//            if (request()->has('sortby') && request()->has('order')) {
//                $sortby = request()->get('sortby'); // Order by what column?
//                $order = request()->get('order'); // Order direction: asc or desc
//                if ($type == 'Warehouse') {
//                    for ($k = 1; $k <= 5; $k++) {
//                        $listLoadingsAssociated[] = Loading::where([['accountCreditLoadingPlace' . $k, $name], ['ladedatum', '>=', $limitDate]])->orderBy($sortby, $order)->get();
//                        $listLoadingsAssociated[] = Loading::where([['accountDebitLoadingPlace' . $k, $name], ['ladedatum', '>=', $limitDate]])->orderBy($sortby, $order)->get();
//                        $listLoadingsAssociated[] = Loading::where([['accountCreditOffloadingPlace' . $k, $name], ['entladedatum', '>=', $limitDate]])->orderBy($sortby, $order)->get();
//                        $listLoadingsAssociated[] = Loading::where([['accountDebitOffloadingPlace' . $k, $name], ['entladedatum', '>=', $limitDate]])->orderBy($sortby, $order)->get();
//                    }
////                    for($k=0;$k<20;$k++){
////                        $links[]=$listLoadingsAssociated[$k]->appends(['sortby' => $sortby, 'order' => $order])->render();
////                    }
//                } elseif ($type == 'Carrier') {
//
//                } elseif ($type == 'Other') {
//
//                }
//            } else {
                if ($type == 'Warehouse') {
                    if (isset($searchQuery) && $searchQuery <> '') {
                        //search query
                        if ($searchColumn == 'all') {
                            for ($k = 1; $k <= 5; $k++) {
                                $listLoadingsAssociated[] = Loading::where([['accountCreditLoadingPlace' . $k, $name], ['ladedatum', '>=', $limitDate]])->where(function ($q) use ($searchQuery, $listColumns) {
                                    $q->where('atrnr', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('ladedatum', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('entladedatum', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('subfrachter', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsOffloadingPlace1', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsLoadingPlace1', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsOffloadingPlace2', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsLoadingPlace2', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsOffloadingPlace3', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsLoadingPlace3', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsOffloadingPlace4', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsLoadingPlace4', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsOffloadingPlace5', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsLoadingPlace5', 'LIKE', '%' . $searchQuery . '%');
                                })->get();
                                $listLoadingsAssociated[] = Loading::where([['accountDebitLoadingPlace' . $k, $name], ['ladedatum', '>=', $limitDate]])->where(function ($q) use ($searchQuery, $listColumns) {
                                    $q->where('atrnr', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('ladedatum', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('entladedatum', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('subfrachter', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsOffloadingPlace1', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsLoadingPlace1', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsOffloadingPlace2', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsLoadingPlace2', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsOffloadingPlace3', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsLoadingPlace3', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsOffloadingPlace4', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsLoadingPlace4', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsOffloadingPlace5', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsLoadingPlace5', 'LIKE', '%' . $searchQuery . '%');
                                })->get();
                                $listLoadingsAssociated[] = Loading::where([['accountCreditOffloadingPlace' . $k, $name], ['entladedatum', '>=', $limitDate]])->where(function ($q) use ($searchQuery, $listColumns) {
                                    $q->where('atrnr', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('ladedatum', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('entladedatum', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('subfrachter', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsOffloadingPlace1', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsLoadingPlace1', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsOffloadingPlace2', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsLoadingPlace2', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsOffloadingPlace3', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsLoadingPlace3', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsOffloadingPlace4', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsLoadingPlace4', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsOffloadingPlace5', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsLoadingPlace5', 'LIKE', '%' . $searchQuery . '%');
                                })->get();
                                $listLoadingsAssociated[] = Loading::where([['accountDebitOffloadingPlace' . $k, $name], ['entladedatum', '>=', $limitDate]])->where(function ($q) use ($searchQuery, $listColumns) {
                                    $q->where('atrnr', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('ladedatum', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('entladedatum', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('subfrachter', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsOffloadingPlace1', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsLoadingPlace1', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsOffloadingPlace2', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsLoadingPlace2', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsOffloadingPlace3', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsLoadingPlace3', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsOffloadingPlace4', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsLoadingPlace4', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsOffloadingPlace5', 'LIKE', '%' . $searchQuery . '%')
                                        ->orWhere('numberPalletsLoadingPlace5', 'LIKE', '%' . $searchQuery . '%');
                                })->get();
                            }
                        } else {
                            for ($k = 1; $k <= 5; $k++) {
                                $listLoadingsAssociated[] = Loading::where([['accountCreditLoadingPlace' . $k, $name], ['ladedatum', '>=', $limitDate]])->where($searchColumn, 'LIKE', '%' . $searchQuery . '%')->get();
                                $listLoadingsAssociated[] = Loading::where([['accountDebitLoadingPlace' . $k, $name], ['ladedatum', '>=', $limitDate]])->where($searchColumn, 'LIKE', '%' . $searchQuery . '%')->get();
                                $listLoadingsAssociated[] = Loading::where([['accountCreditOffloadingPlace' . $k, $name], ['entladedatum', '>=', $limitDate]])->where($searchColumn, 'LIKE', '%' . $searchQuery . '%')->get();
                                $listLoadingsAssociated[] = Loading::where([['accountDebitOffloadingPlace' . $k, $name], ['entladedatum', '>=', $limitDate]])->where($searchColumn, 'LIKE', '%' . $searchQuery . '%')->get();
                                }
                        }
                    } else {
                        for ($k = 1; $k <= 5; $k++) {
                            $listLoadingsAssociated[] = Loading::where([['accountCreditLoadingPlace' . $k, $name], ['ladedatum', '>=', $limitDate]])->get();
                            $listLoadingsAssociated[] = Loading::where([['accountDebitLoadingPlace' . $k, $name], ['ladedatum', '>=', $limitDate]])->get();
                            $listLoadingsAssociated[] = Loading::where([['accountCreditOffloadingPlace' . $k, $name], ['entladedatum', '>=', $limitDate]])->get();
                            $listLoadingsAssociated[] = Loading::where([['accountDebitOffloadingPlace' . $k, $name], ['entladedatum', '>=', $limitDate]])->get();
                        }
//                    for($k=0;$k<20;$k++){
//                        $links[]='';
//                        $count=0;
//                        $count=$count+count($listLoadingsAssociated[$k]);
//                    }
                    }
                } elseif ($type == 'Carrier') {

                } elseif ($type == 'Other') {

                }
//                $listPalletstransfers = DB::table('palletstransfers')->where([['palletsaccount_name', $name], ['date', '>=', $limitDate]])->paginate(10);

//            }
            return view('palletsaccounts.detailsPalletsaccount', compact('typePlaceAccount', 'searchColumn', 'searchQuery','listColumns','listLoadingsAssociated', 'totalpallets', 'listWarehouses', 'id', 'name', 'realNumberPallets', 'theoricalNumberPallets', 'type', 'namewarehouses', 'count', 'links'));
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
        $rules = array(
            'name' => 'required|string|max:255|unique:palletsaccounts,name,' . $id,
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $name = Input::get('name');
            Palletsaccount::where('id', $id)->update(['name' => $name]);
            $type = Input::get('type');
            Palletsaccount::where('id', $id)->update(['type' => $type]);
            $warehousesAssociatedName = Input::get('namewarehouses');
            if (isset($warehousesAssociatedName)) {
                foreach ($warehousesAssociatedName as $warehouseAName) {
                    $idwarehouses[] = Warehouse::where('name', $warehouseAName)->value('id');
                }
                Palletsaccount::where('id', $id)->first()->warehouses()->sync($idwarehouses);
            }

            session()->flash('messageUpdatePalletsaccount', 'Successfully updated pallets account');
            return redirect()->back();
        }
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
