<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class WarehousesController extends Controller
{
    /**
     * show all warehouses in a table. You can order the different columns
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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

    /**
     * add a new wharehouse to the list
     */
    public function add(){

   }

    /**
     * show one specific warehouse according to its ID
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showDetails($id){
       $warehouse = DB::table('warehouses')->where('id', '=', $id)->first();

       $name=$warehouse->name;
       $adress=$warehouse->adress;
       $zipcode=$warehouse->zipcode;
       $town=$warehouse->town;
       $country=$warehouse->country;
       $phone=$warehouse->phone;
       $fax=$warehouse->fax;
       $email=$warehouse->email;
       $namecontact=$warehouse->namecontact;

       return view('warehouses.detailsWarehouse', compact('id','name', 'adress', 'zipcode', 'town', 'country', 'phone', 'fax', 'email', 'namecontact'));
   }

    /**
     * update the warehouse n° ID
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id){
//       $warehouse = DB::table('warehouses')->where('id', $id)->first();

       $name=Input::get('name');
       $adress=Input::get('adress');
       $zipcode=Input::get('zipcode');
       $town=Input::get('town');
       $country=Input::get('country');
       $phone=Input::get('phone');
       $fax=Input::get('fax');
       $email=Input::get('email');
       $namecontact=Input::get('namecontact');

       DB::table('warehouses')->where('id', $id)->update(['name'=>$name]);
       DB::table('warehouses')->where('id', $id)->update(['adress'=>$adress]);
       DB::table('warehouses')->where('id', $id)->update(['zipcode'=>$zipcode]);
       DB::table('warehouses')->where('id', $id)->update(['town'=>$town]);
       DB::table('warehouses')->where('id', $id)->update(['country'=>$country]);
       DB::table('warehouses')->where('id', $id)->update(['phone'=>$phone]);
       DB::table('warehouses')->where('id', $id)->update(['fax'=>$fax]);
       DB::table('warehouses')->where('id', $id)->update(['email'=>$email]);
       DB::table('warehouses')->where('id', $id)->update(['namecontact'=>$namecontact]);

       session()->flash('messageUpdateWarehouse', 'Successfully updated warehouse');

       return redirect()->back();
   }

   public function delete($id){
       DB::table('warehouses')->where('id', $id)->delete();

       // redirect
       session()->flash('messageDeleteWarehouse', 'Successfully deleted the warehouse!');
       return redirect('/allWarehouses');
   }
}
