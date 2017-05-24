<?php

namespace App\Http\Controllers;

use App\PalletsAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PalletsAccountController extends Controller
{
    /**
     * Display the content.
     *
     * @return \Illuminate\Http\Response
     */
    public function showTotal(Request $request)
    {
//        if (Auth::check()) {
////table 1
//            $totalpalanzahl =DB::table('palletsAccounts')->sum('palanzahl');
//
//
////            if (request()->has('sortby') && request()->has('order')) {
////                $sortby = $request->get('sortby'); // Order by what column?
////                $order = $request->get('order'); // Order direction: asc or desc
//
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
