<?php

namespace App\Http\Controllers;

use App\Truck;
use App\Palletsaccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;

class TrucksController extends Controller
{
    /**
     * show all trucks in a table. You can order the different columns
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAll(Request $request)
    {
        $searchQuery = $request->get('search');
        $searchColumn = $request->get('searchColumn');
        $listColumns = ['id', 'name', 'licensePlate', 'palletsaccount_name'];
        if (Auth::check()) {
            $this->importData();
            if (request()->has('sortby') && request()->has('order')) {
                $sortby = $request->get('sortby'); // Order by what column?
                $order = $request->get('order'); // Order direction: asc or desc
                if (isset($searchQuery) && $searchQuery <> '') {
                    if ($searchColumn == 'all') {
                        //search query
                        $listTrucks = DB::table('trucks')
                            ->where(function ($q) use ($searchQuery, $listColumns) {
                                $q->where($listColumns[0], 'LIKE', '%' . $searchQuery . '%')
                                    ->orWhere($listColumns[1], 'LIKE', '%' . $searchQuery . '%')
                                    ->orWhere($listColumns[2], 'LIKE', '%' . $searchQuery . '%')
                                    ->orWhere($listColumns[3], 'LIKE', '%' . $searchQuery . '%');
                            })->orderBy($sortby, $order)->paginate(10);
                        $count = count(DB::table('trucks')->where(function ($q) use ($searchQuery, $listColumns) {
                            $q->where($listColumns[0], 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere($listColumns[1], 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere($listColumns[2], 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere($listColumns[3], 'LIKE', '%' . $searchQuery . '%');
                        })->get());
                    } else {
                        $listTrucks = DB::table('trucks')
                            ->where($searchColumn, 'LIKE', '%' . $searchQuery . '%')->orderBy($sortby, $order)->paginate(10);
                        $count = count(DB::table('trucks')->where($searchColumn, 'LIKE', '%' . $searchQuery . '%')->get());
                    }
                } else {
                    $listTrucks = DB::table('trucks')->orderBy($sortby, $order)->paginate(10);
                    $count = count(DB::table('trucks')->get());
                }
                $links = $listTrucks->appends(['sortby' => $sortby, 'order' => $order])->render();
            } else {
                if (isset($searchQuery) && $searchQuery <> '') {
                    if ($searchColumn == 'all') {
                        //search query
                        $listTrucks = DB::table('trucks')
                            ->where(function ($q) use ($searchQuery, $listColumns) {
                                $q->where($listColumns[0], 'LIKE', '%' . $searchQuery . '%')
                                    ->orWhere($listColumns[1], 'LIKE', '%' . $searchQuery . '%')
                                    ->orWhere($listColumns[2], 'LIKE', '%' . $searchQuery . '%')
                                    ->orWhere($listColumns[3], 'LIKE', '%' . $searchQuery . '%');
                            })->paginate(10);
                        $count = count(DB::table('trucks')->where(function ($q) use ($searchQuery, $listColumns) {
                            $q->where($listColumns[0], 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere($listColumns[1], 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere($listColumns[2], 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere($listColumns[3], 'LIKE', '%' . $searchQuery . '%');
                        })->get());
                    } else {
                        $listTrucks = DB::table('trucks')
                            ->where($searchColumn, 'LIKE', '%' . $searchQuery . '%')->paginate(10);
                        $count = count(DB::table('trucks')->where($searchColumn, 'LIKE', '%' . $searchQuery . '%')->get());
                    }
                } else {
                    $listTrucks = DB::table('trucks')->paginate(10);
                    $count = count(DB::table('trucks')->get());
                }
                $links = '';
            }

            return view('trucks.allTrucks', compact('listTrucks', 'sortby', 'order', 'links', 'count', 'searchQuery', 'searchColumn', 'listColumns'));
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
                            $testLicense = DB::table('trucks')->where('licensePlate', '=', trim($sheet[$r][26]))->first();

                            if ($testLicense == null) {
                                //not double
                                $nameAdress=explode(',',$sheet[$r][25]);
                                $testTruck = DB::table('palletsaccounts')->where('type', 'Truck')->where('name', trim($nameAdress[0]))->first();

                                if($testTruck==null) {
                                    Palletsaccount::firstOrCreate([
                                        'name' => trim($nameAdress[0]),
                                        'adress'=>trim($nameAdress[1]),
                                        'type' => 'Carrier',
                                    ]);
                                }

                                if(trim($sheet[$r][26])==null){
                                    Truck::firstOrCreate([
                                        'name' => trim($nameAdress[0]),
                                        'adress'=>trim($nameAdress[1]),
                                        'licensePlate' => 'OTHER',
                                        'palletsaccount_name'=>trim($nameAdress[0]),
                                    ]);
                                }else{
                                    Truck::firstOrCreate([
                                        'name' => trim($nameAdress[0]),
                                        'adress'=>trim($nameAdress[1]),
                                        'licensePlate' => trim($sheet[$r][26]),
                                        'palletsaccount_name'=>trim($nameAdress[0]),
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
        $adress=Input::get('adress');

        $licensePlate = Input::get('licensePlate');
        if (!$licensePlate) {
            $licensePlate = 'OTHER';
        }
        $palletsaccount_name = Input::get('palletsaccount_name');
        $truckTest = Truck::where([['name', $name], ['adress',$adress],['licensePlate', $licensePlate]])->first();

        //validation
        $rules = array(
            'name' => 'required|string|max:255|unique:warehouses',
        );

        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } elseif ($truckTest <> null) {
            session()->flash('messageErrorAddTruck', 'Error ! This truck already exists');
            $listPalletsAccounts = DB::table('palletsaccounts')->where('type','Carrier')->get();
            return view('trucks.addTruck', compact('name', 'adress', 'licensePlate', 'palletsaccount_name', 'listPalletsAccounts'));
        } else {
            Truck::create(
                ['name' => $name, 'adress'=>$adress, 'licensePlate' => $licensePlate, 'palletsaccount_name' => $palletsaccount_name]
            );
            session()->flash('messageAddTruck', 'Successfully added new truck');
            return redirect('/allTrucks');
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
            $listPalletsAccounts = DB::table('palletsaccounts')->where('type','Carrier')->get();
            return view('trucks.addTruck', compact('listPalletsAccounts'));
        } else {
            return view('auth.login');
        }
    }


    /**
     * show one specific truck according to its ID
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public
    function showDetails($id)
    {
        if (Auth::check()) {
            $truck = DB::table('trucks')->where('id', '=', $id)->first();
            $listPalletsAccounts = DB::table('palletsaccounts')->where('type','Carrier')->get();

            $name = $truck->name;
            $adress=$truck->adress;
            $licensePlate = $truck->licensePlate;
            $palletsaccount_name = $truck->palletsaccount_name;

            return view('trucks.detailsTruck', compact('listPalletsAccounts', 'id', 'name','adress', 'licensePlate', 'palletsaccount_name'));
        } else {
            return view('auth.login');
        }
    }

    /**
     * update the truck n° ID
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
        if (!$licensePlate) {
            $licensePlate = 'OTHER';
        }
        $palletsaccount_name = Input::get('palletsaccount_name');
        $truckTest = Truck::where([['name', $name], ['licensePlate', $licensePlate]])->first();

        //validation
        $rules = array(
            'name' => 'required|string|max:255|unique:warehouses,name,' . $id,
        );

        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();

        } elseif (count($truckTest) > 1) {
            session()->flash('messageErrorUpdateTruck', 'Error ! This truck already exists');
            return redirect()->back();
        } else {
            Truck::where('id', $id)->update(['name' => $name, 'licensePlate' => $licensePlate, 'palletsaccount_name' => $palletsaccount_name]);

            session()->flash('messageUpdateTruck', 'Successfully updated truck');
            return redirect()->back();
        }
    }


    /**
     * delete the truck
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete($id)
    {
        DB::table('trucks')->where('id', $id)->delete();
        // redirect
        session()->flash('messageDeleteTruck', 'Successfully deleted the truck!');
        return redirect('/allTrucks');
    }
}
