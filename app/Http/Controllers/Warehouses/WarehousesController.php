<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class WarehousesController extends Controller
{
    /**
     * show all warehouses in a table. You can order the different columns
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAll(Request $request)
    {
        if (Auth::check()) {
            if (request()->has('sortby') && request()->has('order')) {
                $sortby = $request->get('sortby'); // Order by what column?
                $order = $request->get('order'); // Order direction: asc or desc
                $listWarehouses = DB::table('warehouses')->orderBy($sortby, $order)->paginate(10);
                $links = $listWarehouses->appends(['sortby' => $sortby, 'order' => $order])->render();
            } else {
                $listWarehouses = DB::table('warehouses')->paginate(10);
                $links = '';
            }
            $count = count(DB::table('warehouses')->get());

            return view('warehouses.allWarehouses', compact('listWarehouses', 'sortby', 'order', 'links', 'count'));
        } else {
            return view('auth.login');
        }

    }

    /**
     * add a new wharehouse to the list
     */
    public function add(Request $request)
    {
        $zipcode = Input::get('zipcode');
        $zipcodeWarehouses = DB::table('warehouses')->where('zipcode', '=', $zipcode)->get();
        $validateAddWarehouse = $request->validateAddWarehouse;
        $refuseAddWarehouse = $request->refuseAddWarehouse;
        $name = Input::get('name');
        $adress = Input::get('adress');
        $town = Input::get('town');
        $country = Input::get('country');
        $phone = Input::get('phone');
        $fax = Input::get('fax');
        $email = Input::get('email');
        $namecontact = Input::get('namecontact');
        $namepalletaccount=Input::get('namepalletaccount');

        $rules = array(
            'zipcode' => 'required',
            'name' => 'required|string|max:255|unique:warehouses',
            'adress' => 'required|string|max:255',
            'town' => 'required|string|max:255',
            'country' => 'required|string|max:255',

        );
        $validator = Validator::make(Input::all(), $rules);
        // process the login
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } else {

            if (!$zipcodeWarehouses->isEmpty()) {

                if (isset($validateAddWarehouse)) {
                    DB::table('warehouses')->insertGetId(
                        ['name' => $name, 'adress' => $adress, 'zipcode' => $zipcode, 'town' => $town, 'country' => $country, 'phone' => $phone, 'fax' => $fax, 'email' => $email, 'namecontact' => $namecontact]
                    );

                    return redirect('/allWarehouses');
                } elseif (isset($refuseAddWarehouse)) {
                    for($k=0; $k<11; $k++){
                        $listPalletsAccounts[]=$k;
                    }
                    session()->flash('messageRefuseAddWarehouse', 'Please change the warehouse');
                    return view('warehouses.addWarehouse', compact('listPalletsAccounts', 'name', 'adress', 'zipcode', 'town', 'country', 'phone', 'fax', 'email', 'namecontact', 'namepalletaccount'));
                } else {
                    for($k=0; $k<11; $k++){
                        $listPalletsAccounts[]=$k;
                    }
                    session()->flash('testZipcode', true);
                    return view('warehouses.addWarehouse', compact('listPalletsAccounts','zipcodeWarehouses', 'name', 'adress', 'zipcode', 'town', 'country', 'phone', 'fax', 'email', 'namecontact', 'namepalletaccount'));
                }

            } else {
                DB::table('warehouses')->insertGetId(
                    ['name' => $name, 'adress' => $adress, 'zipcode' => $zipcode, 'town' => $town, 'country' => $country, 'phone' => $phone, 'fax' => $fax, 'email' => $email, 'namecontact' => $namecontact]
                );
                session()->flash('messageAddWarehouse', 'Successfully added new warehouse');
                return redirect('/allWarehouses');
            }
        }
    }

    public function showAdd()
    {
        if (Auth::check()) {
            for($k=0; $k<11; $k++){
                $listPalletsAccounts[]=$k;
            }

            return view('warehouses.addWarehouse', compact('listPalletsAccounts'));
        } else {
            return view('auth.login');
        }
    }



    /**
     * show one specific warehouse according to its ID
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showDetails($id)
    {
        if (Auth::check()) {
            $warehouse = DB::table('warehouses')->where('id', '=', $id)->first();

            for($k=0; $k<11; $k++){
                $listPalletsAccounts[]=$k;
            }

            $name = $warehouse->name;
            $adress = $warehouse->adress;
            $zipcode = $warehouse->zipcode;
            $town = $warehouse->town;
            $country = $warehouse->country;
            $phone = $warehouse->phone;
            $fax = $warehouse->fax;
            $email = $warehouse->email;
            $namecontact = $warehouse->namecontact;
            $namepalletaccount='4';

            return view('warehouses.detailsWarehouse', compact('listPalletsAccounts','id', 'name', 'adress', 'zipcode', 'town', 'country', 'phone', 'fax', 'email', 'namecontact', 'namepalletaccount'));
        } else {
            return view('auth.login');
        }
    }

    /**
     * update the warehouse n° ID
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
//       $warehouse = DB::table('warehouses')->where('id', $id)->first();

        $rules = array(
            'zipcode' => 'required|',
            'name' => 'required|string|max:255|unique:warehouses',
            'adress' => 'required|string|max:255',
            'town' => 'required|string|max:255',
            'country' => 'required|string|max:255',

        );
        $validator = Validator::make(Input::all(), $rules);
        // process the login
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $name = Input::get('name');
            $adress = Input::get('adress');
            $zipcode = Input::get('zipcode');
            $town = Input::get('town');
            $country = Input::get('country');
            $phone = Input::get('phone');
            $fax = Input::get('fax');
            $email = Input::get('email');
            $namecontact = Input::get('namecontact');

            DB::table('warehouses')->where('id', $id)->update(['name' => $name]);
            DB::table('warehouses')->where('id', $id)->update(['adress' => $adress]);
            DB::table('warehouses')->where('id', $id)->update(['zipcode' => $zipcode]);
            DB::table('warehouses')->where('id', $id)->update(['town' => $town]);
            DB::table('warehouses')->where('id', $id)->update(['country' => $country]);
            DB::table('warehouses')->where('id', $id)->update(['phone' => $phone]);
            DB::table('warehouses')->where('id', $id)->update(['fax' => $fax]);
            DB::table('warehouses')->where('id', $id)->update(['email' => $email]);
            DB::table('warehouses')->where('id', $id)->update(['namecontact' => $namecontact]);

            session()->flash('messageUpdateWarehouse', 'Successfully updated warehouse');

            return redirect()->back();
        }
    }

    public function delete($id)
    {
        DB::table('warehouses')->where('id', $id)->delete();

        // redirect
        session()->flash('messageDeleteWarehouse', 'Successfully deleted the warehouse!');
        return redirect('/allWarehouses');
    }
}
