<?php

namespace App\Http\Controllers;

use App\Carrier;
use App\Palletsaccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;

class CarriersController extends Controller
{
    /**
     * show all carriers in a table. You can order the different columns
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAll(Request $request)
    {
        if (Auth::check()) {
            $this->importData();
            if (request()->has('sortby') && request()->has('order')) {
                $sortby = $request->get('sortby'); // Order by what column?
                $order = $request->get('order'); // Order direction: asc or desc
                $listCarriers = DB::table('carriers')->orderBy($sortby, $order)->paginate(10);
                $links = $listCarriers->appends(['sortby' => $sortby, 'order' => $order])->render();
            } else {
                $listCarriers = DB::table('carriers')->paginate(10);
                $links = '';
            }
            $count = count(DB::table('carriers')->get());
            return view('carriers.allCarriers', compact('listCarriers', 'sortby', 'order', 'links', 'count'));
        } else {
            return view('auth.login');
        }
    }

    public function importData()
    {
        $path = '../resources/assets/excel/';
        $files = File::allFiles($path);
        foreach ($files as $file) {
            if (strpos((string)$file, '.xls') !== false) {
                Excel::load($file, function ($reader) {
                    if (!empty($reader)) {
                        $reader->noHeading();
                        $sheet = $reader->getSheet(0)->toArray();
                        $nbrows = count($sheet);
                        for ($r = 4; $r < $nbrows; $r++) {
                            $carrierTest = DB::table('carriers')->where('licensePlate', '=', trim($sheet[$r][26]))->first();
                            if ($carrierTest == null) {
                                //not double
                                if (trim($sheet[$r][26]) == null) {
                                    Palletsaccount::firstOrCreate([
                                        'name' => trim($sheet[$r][25]),
                                        'type' => 'Carrier',
                                    ]);
                                    Carrier::firstOrCreate([
                                        'name' => trim($sheet[$r][25]),
                                        'licensePlate' => 'OTHER',
                                        'palletsaccount_name' => trim($sheet[$r][25]),
                                    ]);
                                } else {
                                    Palletsaccount::firstOrCreate([
                                        'name' => trim($sheet[$r][26]) . ' - ' . trim($sheet[$r][25]),
                                        'type' => 'Carrier',
                                    ]);
                                    Carrier::firstOrCreate([
                                        'name' => trim($sheet[$r][25]),
                                        'licensePlate' => trim($sheet[$r][26]),
                                        'palletsaccount_name' => trim($sheet[$r][26]) . ' - ' . trim($sheet[$r][25]),
                                    ]);
                                }
                            }
                        }
                    }
                });
            }
        }
    }

    /**
     * add a new wharehouse to the list
     */
    public function add(Request $request)
    {
        //get data
        $name = Input::get('name');
        $licensePlate = Input::get('licensePlate');
        if(!$licensePlate){
            $licensePlate='OTHER';
        }
        $palletsaccount_name = Input::get('palletsaccount_name');
        $carrierTest = Carrier::where([['name', $name], ['licensePlate', $licensePlate]])->first();

        //validation
        $rules = array(
            'name' => 'required|string|max:255|unique:warehouses',
        );

        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } elseif ($carrierTest <> null) {
            session()->flash('messageErrorAddCarrier', 'Error ! This carrier already exists');
            $listPalletsAccounts = DB::table('palletsaccounts')->get();
            return view('carriers.addCarrier', compact('name', 'licensePlate', 'palletsaccount_name', 'listPalletsAccounts'));
        } else {
            Carrier::create(
                ['name' => $name, 'licensePlate' => $licensePlate, 'palletsaccount_name' => $palletsaccount_name]
            );
            session()->flash('messageAddCarrier', 'Successfully added new carrier');
            return redirect('/allCarriers');
        }
    }

    /**
     * show the add form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public
    function showAdd()
    {
        if (Auth::check()) {
            $listPalletsAccounts = DB::table('palletsaccounts')->get();
            return view('carriers.addCarrier', compact('listPalletsAccounts'));
        } else {
            return view('auth.login');
        }
    }


    /**
     * show one specific carrier according to its ID
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public
    function showDetails($id)
    {
        if (Auth::check()) {
            $carrier = DB::table('carriers')->where('id', '=', $id)->first();
            $listPalletsAccounts = DB::table('palletsaccounts')->get();

            $name = $carrier->name;
            $licensePlate = $carrier->licensePlate;
            $palletsaccount_name = $carrier->palletsaccount_name;

            return view('carriers.detailsCarrier', compact('listPalletsAccounts', 'id', 'name', 'licensePlate', 'palletsaccount_name'));
        } else {
            return view('auth.login');
        }
    }

    /**
     * update the carrier n° ID
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public
    function update(Request $request, $id)
    {
        //get data
        $name = Input::get('name');
        $licensePlate = Input::get('licensePlate');
        if(!$licensePlate){
            $licensePlate='OTHER';
        }
        $palletsaccount_name = Input::get('palletsaccount_name');
        $carrierTest = Carrier::where([['name', $name], ['licensePlate', $licensePlate]])->first();

        //validation
        $rules = array(
            'name' => 'required|string|max:255|unique:warehouses,name,' . $id,
        );

        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();

        } elseif (count($carrierTest) > 1) {
            session()->flash('messageErrorUpdateCarrier', 'Error ! This carrier already exists');
            return redirect()->back();
        } else {
            Carrier::where('id', $id)->update(['name' => $name, 'licensePlate' => $licensePlate, 'palletsaccount_name' => $palletsaccount_name]);

            session()->flash('messageUpdateCarrier', 'Successfully updated carrier');
            return redirect()->back();
        }
    }


    /**
     * delete the carrier
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete($id)
    {
        DB::table('carriers')->where('id', $id)->delete();
        // redirect
        session()->flash('messageDeleteCarrier', 'Successfully deleted the carrier!');
        return redirect('/allCarriers');
    }
}
