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
//table 1
            $totalpallets =DB::table('palletsaccounts')->sum('numberPallets');


//
            if (request()->has('sortby') && request()->has('order')) {
                $sortby = $request->get('sortby'); // Order by what column?
                $order = $request->get('order'); // Order direction: asc or desc
$listPalletsaccounts=DB::table('palletsaccounts')->orderBy($sortby, $order)->get();
            } else {
                $listPalletsaccounts = DB::table('palletsaccounts')->get();
            }
            return view('palletsaccounts.allPalletsaccounts', compact('listPalletsaccounts', 'totalpallets', 'sortby', 'order'));
        } else {
            return view('auth.login');
        }
//                //table2
//                $warehouses=PalletsAccount::with(array('loadings' => function($query) {
//                    $query->orderBy('referenz', 'ASC');
//                }))->get();
//
////                $links=$listLoadings->appends(['sortby'=>$sortby, 'order'=>$order])->render();
////            }
////            else{
////                //table2
////                $warehouses=Warehouse::with('loadings')->get();
//
//
//            return view('palletsAccounts.allPalletsAccounts', compact('totalpalanzahl', 'warehouses','warehousesPalAnzahl', 'warehousesName'));
//        }else{
//            return view('auth.login');
//        }
}

}
