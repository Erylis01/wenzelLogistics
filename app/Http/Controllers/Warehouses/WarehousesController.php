<?php

namespace App\Http\Controllers;

use App\Palletsaccount;
use App\Warehouse;
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
        $searchQuery = $request->get('search');
        $searchQueryArray = explode(' ', $searchQuery);
        $searchColumns=$request->get('searchColumns');
        $listColumns=['id','name', 'adress', 'zipcode', 'town', 'country', 'phone','fax', 'email', 'namecontact'];

        if (Auth::check()) {
            $query=DB::table('warehouses');

            if (isset($searchQuery) && $searchQuery <> '') {
                if (in_array('ALL', $searchColumns)) {
                    $query->where(function ($q) use ($searchQueryArray, $listColumns) {
                        foreach ($listColumns as $column) {
                            foreach ($searchQueryArray as $searchQ){
                                $q->orWhere($column, 'LIKE', '%' . $searchQ . '%');
                            }
                        }
                    });
                } else {
                    $query->where(function ($q) use ($searchQueryArray, $searchColumns) {
                        foreach ($searchColumns as $column) {
                            foreach ($searchQueryArray as $searchQ){
                                $q->orWhere($column, 'LIKE', '%' . $searchQ . '%');
                            }
                        }
                    });
                }
                $count = count($query->get());
                $listWarehouses = $query->paginate(10);
                $links = '';
            }else{
                if (request()->has('sortby') && request()->has('order')) {
                    $sortby = $request->get('sortby'); // Order by what column?
                    $order = $request->get('order'); // Order direction: asc or desc
                    $count = count($query->get());
                    $listWarehouses = $query->orderBy($sortby, $order)->paginate(10);
                    $links = $listWarehouses->appends(['sortby' => $sortby, 'order' => $order])->render();
                } else {
                    $count = count($query->get());
                    $listWarehouses = $query->paginate(10);
                    $links = '';
                }
            }
            return view('warehouses.allWarehouses', compact('listWarehouses', 'sortby', 'order', 'links', 'count', 'searchQuery', 'searchQueryArray','searchColumns', 'listColumns'));
        } else {
            return view('auth.login');
        }

    }

    /**
     * add a new wharehouse to the list
     */
    public function add(Request $request)
    {
        //get data
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
        $namepalletsaccounts = Input::get('namepalletsaccounts');
        foreach ($namepalletsaccounts as $namePA) {
            $idpalletsaccounts[] = Palletsaccount::where('name', $namePA)->value('id');
        }

        //validation
        $rules = array(
            'zipcode' => 'required',
            'name' => 'required|string|max:255|unique:warehouses',
            'adress' => 'required|string|max:255',
            'town' => 'required|string|max:255',
            'country' => 'required|string|max:255',
        );
        if (isset($email)) {
            $rules = array_add($rules, 'email', 'string|email');
        }
        if (isset($phone)) {
            $rules = array_add($rules, 'phone', 'string|max:15');
        }
        if (isset($fax)) {
            $rules = array_add($rules, 'fax', 'string|max:15');
        }
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } else {
//if there is an other warehouse in this city : be careful
            if (!$zipcodeWarehouses->isEmpty()) {
                if (isset($validateAddWarehouse)) {
                    //you validate creating a new warehouse even if there is an other one in the city
                    Warehouse::create(
                        ['name' => $name, 'adress' => $adress, 'zipcode' => $zipcode, 'town' => $town, 'country' => $country, 'phone' => $phone, 'fax' => $fax, 'email' => $email, 'namecontact' => $namecontact]
                    )->palletsaccounts()->sync($idpalletsaccounts);
                    session()->flash('messageAddWarehouse', 'Successfully added new warehouse');
                    return redirect('/allWarehouses');
                } elseif (isset($refuseAddWarehouse)) {
                    //if after the warning message you refuse to add this warehouse : redirect with filled field to change them
                    $listPalletsAccounts = DB::table('palletsaccounts')->get();
                    session()->flash('messageRefuseAddWarehouse', 'Please change the warehouse');
                    return view('warehouses.addWarehouse', compact('listPalletsAccounts', 'name', 'adress', 'zipcode', 'town', 'country', 'phone', 'fax', 'email', 'namecontact', 'namepalletsaccounts'));
                } else {
                    //redirect to the add form with a pop up warning about the zipcode
                    $listPalletsAccounts = DB::table('palletsaccounts')->get();
                    session()->flash('testZipcode', true);
                    return view('warehouses.addWarehouse', compact('listPalletsAccounts', 'zipcodeWarehouses', 'name', 'adress', 'zipcode', 'town', 'country', 'phone', 'fax', 'email', 'namecontact', 'namepalletsaccounts'));
                }
            } else {
                //if no other warehouse in the city
                Warehouse::create(
                    ['name' => $name, 'adress' => $adress, 'zipcode' => $zipcode, 'town' => $town, 'country' => $country, 'phone' => $phone, 'fax' => $fax, 'email' => $email, 'namecontact' => $namecontact]
                )->palletsaccounts()->sync($idpalletsaccounts);

                session()->flash('messageAddWarehouse', 'Successfully added new warehouse');
                return redirect('/allWarehouses');
            }
        }
    }

    /**
     * show the add form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAdd()
    {
        if (Auth::check()) {
            $listPalletsAccounts = DB::table('palletsaccounts')->get();
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
            $listPalletsAccounts = DB::table('palletsaccounts')->get();

            $name = $warehouse->name;
            $adress = $warehouse->adress;
            $zipcode = $warehouse->zipcode;
            $town = $warehouse->town;
            $country = $warehouse->country;
            $phone = $warehouse->phone;
            $fax = $warehouse->fax;
            $email = $warehouse->email;
            $namecontact = $warehouse->namecontact;
            $palletsaccounts = DB::table('palletsaccount_warehouse')->where('warehouse_id', $id)->get();
            foreach ($palletsaccounts as $palletsaccount) {
                $namepalletsaccounts[] = Palletsaccount::where('id', $palletsaccount->palletsaccount_id)->value('name');
            }

            return view('warehouses.detailsWarehouse', compact('listPalletsAccounts', 'id', 'name', 'adress', 'zipcode', 'town', 'country', 'phone', 'fax', 'email', 'namecontact', 'namepalletsaccounts'));
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
        //get data
        $validateUpdateWarehouse = $request->validateUpdateWarehouse;
        $refuseUpdateWarehouse = $request->refuseUpdateWarehouse;
        $namepalletsaccounts = Input::get('namepalletsaccounts');
        foreach ($namepalletsaccounts as $namePA) {
            $idpalletsaccounts[] = Palletsaccount::where('name', $namePA)->value('id');
        }
        $name = Input::get('name');
        $adress = Input::get('adress');
        $currentZipcode = DB::table('warehouses')->where('id', $id)->value('zipcode');
        $zipcode = Input::get('zipcode');
        if ($currentZipcode <> $zipcode) {
            $zipcodeWarehouses = DB::table('warehouses')->where('zipcode', '=', $zipcode)->get();
        }
        $town = Input::get('town');
        $country = Input::get('country');
        $phone = Input::get('phone');
        $fax = Input::get('fax');
        $email = Input::get('email');
        $namecontact = Input::get('namecontact');

        //validation
        $rules = array(
            'zipcode' => 'required',
            'name' => 'required|string|max:255|unique:warehouses,name,' . $id,
            'adress' => 'required|string|max:255',
            'town' => 'required|string|max:255',
            'country' => 'required|string|max:255',
        );
        if (isset($email)) {
            $rules = array_add($rules, 'email', 'string|email');
        }
        if (isset($phone)) {
            $rules = array_add($rules, 'phone', 'string|max:15');
        }
        if (isset($fax)) {
            $rules = array_add($rules, 'fax', 'string|max:15');
        }
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            //same warning as in the add form when there is already an other warehouse in the same city
            if (isset($zipcodeWarehouses) && !$zipcodeWarehouses->isEmpty()) {
                if (isset($validateUpdateWarehouse)) {
                    Warehouse::where('id', $id)->update(['name' => $name, 'adress' => $adress, 'zipcode' => $zipcode, 'town' => $town, 'country' => $country, 'phone' => $phone, 'fax' => $fax, 'email' => $email, 'namecontact' => $namecontact]);
                    Warehouse::where('id', $id)->first()->palletsaccounts()->sync($idpalletsaccounts);
                    session()->flash('messageUpdateWarehouse', 'Successfully updated warehouse');
                    return redirect()->back();
                } elseif (isset($refuseUpdateWarehouse)) {
                    $listPalletsAccounts = DB::table('palletsaccounts')->get();
                    session()->flash('messageRefuseUpdateWarehouse', 'Please change the warehouse');
                    return view('warehouses.detailsWarehouse', compact('listPalletsAccounts', 'id', 'name', 'adress', 'zipcode', 'town', 'country', 'phone', 'fax', 'email', 'namecontact', 'namepalletsaccounts'));
                } else {
                    $listPalletsAccounts = DB::table('palletsaccounts')->get();
                    session()->flash('testZipcode', true);
                    return view('warehouses.detailsWarehouse', compact('listPalletsAccounts', 'id', 'zipcodeWarehouses', 'name', 'adress', 'zipcode', 'town', 'country', 'phone', 'fax', 'email', 'namecontact', 'namepalletsaccounts'));
                }
            } else {
                Warehouse::where('id', $id)->update(['name' => $name, 'adress' => $adress, 'zipcode' => $zipcode, 'town' => $town, 'country' => $country, 'phone' => $phone, 'fax' => $fax, 'email' => $email, 'namecontact' => $namecontact]);
                Warehouse::where('id', $id)->first()->palletsaccounts()->sync($idpalletsaccounts);

                session()->flash('messageUpdateWarehouse', 'Successfully updated warehouse');
                return redirect()->back();
            }
        }
    }

    /**
     * delete the warehouse
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete($id)
    {
        DB::table('warehouses')->where('id', $id)->delete();
        // redirect
        session()->flash('messageDeleteWarehouse', 'Successfully deleted the warehouse!');
        return redirect('/allWarehouses');
    }
}
