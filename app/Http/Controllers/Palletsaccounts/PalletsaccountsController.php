<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

//                $links = $listPalletsaccounts->appends(['sortby' => $sortby, 'order' => $order])->render();
            } else {
                $listPalletsaccounts = DB::table('palletsaccounts')->get();
//                $links = '';
            }
            return view('palletsaccounts.allPalletsaccounts', compact('listPalletsaccounts', 'totalpallets', 'sortby', 'order', 'links'));
        } else {
            return view('auth.login');
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

            for ($k = 0; $k < 11; $k++) {
                $listWarehouses[] = 'w'.$k;
            }

            $name = $palletsaccount->name;
            $numberPallets=$palletsaccount->numberPallets;
            $warehousesAssociated=['w1', 'w3','w5' ];

            return view('palletsaccounts.detailsPalletsaccount', compact('listWarehouses', 'id', 'name', 'numberPallets', 'warehousesAssociated'));
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
