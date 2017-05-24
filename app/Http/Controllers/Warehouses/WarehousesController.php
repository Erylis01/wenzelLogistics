<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehousesController extends Controller
{
   public function showAll(Request $request)
   {
       if (request()->has('sortby') && request()->has('order')) {
           $sortby = $request->get('sortby'); // Order by what column?
           $order = $request->get('order'); // Order direction: asc or desc
           $listWarehouses=DB::table('warehouses')->orderBy($sortby, $order)->paginate(10);
           $links=$listWarehouses->appends(['sortby'=>$sortby, 'order'=>$order])->render();
       }else{
           $listWarehouses = DB::table('warehouses')->paginate(10);
           $links='';
       }
       $count=count(DB::table('warehouses')->get());

       return view('warehouses.allWarehouses', compact('listWarehouses','sortby', 'order', 'links', 'count'));
   }

   public function save(){

   }

   public function showDetails(){

   }
}
