<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

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

   public function add(){

   }

   public function showDetails($id){
       $detailsWarehouse = DB::table('warehouses')->where('id', '=', $id)->first();

       $name=$detailsWarehouse->name;
       $adress=$detailsWarehouse->adress;
       $zipcode=$detailsWarehouse->zipcode;
       $town=$detailsWarehouse->town;
       $country=$detailsWarehouse->country;
       $phone=$detailsWarehouse->phone;
       $fax=$detailsWarehouse->fax;
       $email=$detailsWarehouse->email;
       $namecontact=$detailsWarehouse->namecontact;

       return view('warehouses.detailsWarehouse', compact('name', 'adress', 'zipcode', 'town', 'country', 'phone', 'fax', 'email', 'namecontact'));
   }

   public function update(Request $request, $id){
       $warehouse = DB::table('warehouses')->where('id', $id)->first();

       $name=Input::get('name');
       $adress=Input::get('adress');
       $zipcode=Input::get('zipcode');
       $town=Input::get('town');
       $country=Input::get('country');
       $phone=Input::get('phone');
       $fax=Input::get('fax');
       $email=Input::get('email');
       $namecontact=Input::get('namecontact');

       DB::table('warehouse')->where('id', $id)->update(['name'=>$name]);
       DB::table('warehouse')->where('id', $id)->update(['mahnung'=>$adress]);
       DB::table('warehouse')->where('id', $id)->update(['zipcode'=>$zipcode]);
       DB::table('warehouse')->where('id', $id)->update(['town'=>$town]);
       DB::table('warehouse')->where('id', $id)->update(['country'=>$country]);
       DB::table('warehouse')->where('id', $id)->update(['phone'=>$phone]);
       DB::table('warehouse')->where('id', $id)->update(['fax'=>$fax]);
       DB::table('warehouse')->where('id', $id)->update(['email'=>$email]);
       DB::table('warehouse')->where('id', $id)->update(['namecontact'=>$namecontact]);

       session()->flash('messageUpdateWarehouse', 'Successfully updated warehouse');

       return redirect()->back();
   }
}
