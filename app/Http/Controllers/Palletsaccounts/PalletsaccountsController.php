<?php

namespace App\Http\Controllers;

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
        if (Auth::check()) {
            $totalpallets = DB::table('palletsaccounts')->sum('numberPallets');
            if (request()->has('sortby') && request()->has('order')) {
                $sortby = $request->get('sortby'); // Order by what column?
                $order = $request->get('order'); // Order direction: asc or desc
                $listPalletsaccounts = DB::table('palletsaccounts')->orderBy($sortby, $order)->get();
            } else {
                $listPalletsaccounts = DB::table('palletsaccounts')->get();
            }
            return view('palletsaccounts.allPalletsaccounts', compact('listPalletsaccounts', 'totalpallets', 'sortby', 'order'));
        } else {
            return view('auth.login');
        }
    }

    public function showAdd()
    {
        if (Auth::check()) {
            $listWarehouses=DB::table('warehouses')->get();

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
        $name = Input::get('name');
        $numberPallets = Input::get('numberPallets');
        $warehousesAssociated=Input::get('warehousesAssociated');

//        $validateAddWarehouse = $request->validateAddWarehouse;
//        $refuseAddWarehouse = $request->refuseAddWarehouse;
//        $namepalletaccount = Input::get('nameswarehouses');

        $rules = array(
            'name' => 'required|string|max:255|unique:palletsaccounts',
            'warehousesAssociated'=> 'required',
        );
        $validator = Validator::make(Input::all(), $rules);
        // process the login
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            DB::table('palletsaccounts')->insertGetId(
                ['name' => $name, 'numberPallets' => $numberPallets]
            );
            session()->flash('messageAddPalletsaccount', 'Successfully added new pallets account');
            return redirect('/allPalletsaccounts');
        }
    }



    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showDetails($id)
    {
        if (Auth::check()) {
            $palletsaccount = DB::table('palletsaccounts')->where('id', '=', $id)->first();
            $totalpallets = DB::table('palletsaccounts')->sum('numberPallets');
            $listWarehouses=DB::table('warehouses')->get();
            $name = $palletsaccount->name;
            $numberPallets = $palletsaccount->numberPallets;
            $warehousesAssociated = ['warehouse1', 'warehouse3', 'warehouse5'];

            $currentDate = Carbon::now();
            $limitDate=$currentDate->subDays(60)->format('Y-m-d');


            if (request()->has('sortby') && request()->has('order')) {
                $sortby = request()->get('sortby'); // Order by what column?
                $order = request()->get('order'); // Order direction: asc or desc
                $listPalletstransfers=DB::table('palletstransfers')->where([['palletsAccount', $name],['date', '>=', $limitDate]])->orderBy($sortby, $order)->paginate(10);
                $links = $listPalletstransfers->appends(['sortby' => $sortby, 'order' => $order])->render();
            } else {
                $listPalletstransfers=DB::table('palletstransfers')->where([['palletsAccount', $name],['date', '>=', $limitDate]])->paginate(10);
                $links = '';
            }

            $count = count(DB::table('palletstransfers')->where([['palletsAccount', $name],['date', '>=', $limitDate]])->get());

            return view('palletsaccounts.detailsPalletsaccount', compact('listPalletstransfers','totalpallets','listWarehouses', 'id', 'name', 'numberPallets', 'warehousesAssociated', 'count', 'links'));
        } else {
            return view('auth.login');
        }
    }

    /**
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showTotal()
    {
        if (Auth::check()) {
            $totalpallets = DB::table('palletsaccounts')->sum('numberPallets');
//            $palletsaccount = DB::table('palletsaccounts')->where('id', '=', $id)->first();
//
//            for ($k = 0; $k < 11; $k++) {
//                $listWarehouses[] = 'w'.$k;
//            }
//
//            $name = $palletsaccount->name;
//            $numberPallets=$palletsaccount->numberPallets;
//            $warehousesAssociated=['w1', 'w3','w5' ];
//
//            return view('palletsaccounts.detailsPalletsaccount', compact('listWarehouses', 'id', 'name', 'numberPallets', 'warehousesAssociated'));
            return view('palletsaccounts.totalPalletsaccounts', compact('totalpallets'));
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
    public function update(Request $request, $id)
    {
        $rules = array(
            'name' => 'required|string|max:255|unique:warehouses',
            'numberPallets' => 'required|integer',
            'warehousesAssociated'=>'required',

        );
        $validator = Validator::make(Input::all(), $rules);
        // process the login
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $name = Input::get('name');
            $numberPallets = Input::get('numberPallets');
            $warehousesAssociated=Input::get('warehousesAssociated');

            //ATTENTION UPDATE WAREHOUSES ASSOCIATED

            DB::table('palletsaccounts')->where('id', $id)->update(['name' => $name]);
            DB::table('palletsaccounts')->where('id', $id)->update(['numberPallets' => $numberPallets]);

            session()->flash('messageUpdatePalletsaccount', 'Successfully updated pallets account');

            return redirect()->back();
        }
    }

    public function delete($id)
    {
        DB::table('palletsaccounts')->where('id', $id)->delete();

        // redirect
        session()->flash('messageDeletePalletsaccount', 'Successfully deleted the pallets account!');
        return redirect('/allPalletsaccounts');
    }

}
