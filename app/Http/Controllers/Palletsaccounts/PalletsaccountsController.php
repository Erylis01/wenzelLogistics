<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

}
