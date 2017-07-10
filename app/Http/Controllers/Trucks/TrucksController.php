<?php

namespace App\Http\Controllers;

use App\Palletstransfer;
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
    public function showAll(Request $request, $refresh)
    {
        $searchQuery = $request->get('search');
        $searchQueryArray = explode(' ', $searchQuery);
        $searchColumns = $request->get('searchColumns');
        $listColumns = ['id', 'name', 'licensePlate', 'realNumberPallets', 'theoricalNumberPallets'];

        if (Auth::check()) {
            //import new trucks and create pallets account associated
            if($refresh=='true'){
                $this->importData();
                $refresh='false';
            }

            $query = DB::table('trucks');

            //if the user wants to sort the table
            if (request()->has('sortby') && request()->has('order')) {
                $sortby = $request->get('sortby'); // Order by what column?
                $order = $request->get('order'); // Order direction: asc or desc
                $searchColumnsString = $request->get('searchColumnsString');;
                $searchColumns = explode('-', $searchColumnsString);
                //if the user wants to search something in the table
                if (isset($searchQuery) && $searchQuery <> '') {
                    if (in_array('ALL', explode('-', $searchColumnsString))) {
                        $query->where(function ($q) use ($searchQueryArray, $listColumns) {
                            foreach ($listColumns as $column) {
                                foreach ($searchQueryArray as $searchQ) {
                                    $q->orWhere($column, 'LIKE', '%' . $searchQ . '%');
                                }
                            }
                        });
                    } else {
                        $query->where(function ($q) use ($searchQueryArray, $searchColumns) {
                            foreach ($searchColumns as $column) {
                                foreach ($searchQueryArray as $searchQ) {
                                    $q->orWhere($column, 'LIKE', '%' . $searchQ . '%');
                                }
                            }
                        });
                    }
                }
                $count = count($query->get());
                $listTrucks = $query->orderBy($sortby, $order)->paginate(10);
                $links = $listTrucks->appends(['sortby' => $sortby, 'order' => $order, 'search'=>$searchQuery, 'searchColumns'=>$searchColumns])->render();
            } else {
                //not sorting but searching
                if (isset($searchQuery) && $searchQuery <> '') {
                    $searchColumnsString = implode('-', $searchColumns);
                    if (in_array('ALL', $searchColumns)) {
                        //searching in all columns
                        $query->where(function ($q) use ($searchQueryArray, $listColumns) {
                            foreach ($listColumns as $column) {
                                foreach ($searchQueryArray as $searchQ) {
                                    $q->orWhere($column, 'LIKE', '%' . $searchQ . '%');
                                }
                            }
                        });
                    } else {
                        //Searching in specifics columns
                        $query->where(function ($q) use ($searchQueryArray, $searchColumns) {
                            foreach ($searchColumns as $column) {
                                foreach ($searchQueryArray as $searchQ) {
                                    $q->orWhere($column, 'LIKE', '%' . $searchQ . '%');
                                }
                            }
                        });
                    }
                    $count = count($query->get());
                    $listTrucks = $query->orderBy('name', 'asc')->paginate(10);
                    $links =$listTrucks->appends(['search'=>$searchQuery, 'searchColumns'=>$searchColumns])->render();
                }else{
                    //not sorting nor searching
                    $count = count($query->get());
                    $listTrucks = $query->orderBy('name', 'asc')->paginate(10);
                    $links = '';
                }
            }
            return view('trucks.allTrucks', compact('listTrucks', 'sortby', 'order', 'links', 'count', 'searchQuery', 'searchColumnsString', 'searchColumns', 'listColumns','refresh'));
        } else {
            return view('auth.login');
        }
    }

    /**
     * create pallets and trucks if they are not already in the database. The creation is based on the trucks used for each loading
     */
    public function importData()
    {
        $path = '../resources/assets/excel/Hypertrans';
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
                                $nameAdress = explode(',', $sheet[$r][25]);
                                $testTruck = DB::table('palletsaccounts')->where('type', 'Truck')->where('name', trim($nameAdress[0]))->first();

                                if ($testTruck == null) {
                                    Palletsaccount::firstOrCreate([
                                        'name' => trim($nameAdress[0]),
                                        'adress' => trim($nameAdress[1]),
                                        'type' => 'Carrier',
                                    ]);
                                    Truck::firstOrCreate([
                                        'name' => trim($nameAdress[0]),
                                        'licensePlate' => 'STOCK',
                                        'palletsaccount_name' => trim($nameAdress[0]),
                                    ]);
                                }

                                if (trim($sheet[$r][26]) == null) {
                                    Truck::firstOrCreate([
                                        'name' => trim($nameAdress[0]),
                                        'licensePlate' => 'OTHER',
                                        'palletsaccount_name' => trim($nameAdress[0]),
                                    ]);
                                } else {
                                    Truck::firstOrCreate([
                                        'name' => trim($nameAdress[0]),
                                        'licensePlate' => trim($sheet[$r][26]),
                                        'palletsaccount_name' => trim($nameAdress[0]),
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
        $realNumberPallets = Input::get('realNumberPallets');

        $licensePlate = Input::get('licensePlate');
        if (!isset($licensePlate)) {
            $licensePlate = 'OTHER';
        }
        $palletsaccount_name = Input::get('palletsaccount_name');
        $truckTest = Truck::where([['name', $name], ['licensePlate', $licensePlate]])->first();

        //if the truck already exists -> error !
        if ($truckTest <> null) {
            session()->flash('messageErrorAddTruck', 'Error ! This truck already exists');
            $listPalletsAccounts = DB::table('palletsaccounts')->where('type', 'Carrier')->get();
            return view('trucks.addTruck', compact('name', 'realNumberPallets', 'licensePlate', 'palletsaccount_name', 'listPalletsAccounts'));
        } else {
            //if the truck doesn't already exists -> create
            Truck::create(
                ['name' => $name, 'realNumberPallets' => $realNumberPallets,'theorcialNumberPallets' => $realNumberPallets, 'licensePlate' => $licensePlate, 'palletsaccount_name' => $palletsaccount_name]
            );
            //update the pallets account confirmed pallets number with the sum of all trucks of this account
            Palletsaccount::where('name', $palletsaccount_name)->update(['realNumberPallets'=>Palletsaccount::where('name', $palletsaccount_name)->sum('realNumberPallets'), 'theoricalNumberPallets'=>Palletsaccount::where('name', $palletsaccount_name)->sum('theoricalNumberPallets')]);
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
            $listPalletsAccounts = DB::table('palletsaccounts')->where('type', 'Carrier')->get();
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
    public function showDetails($id, Request $request)
    {
        $searchQuery = $request->get('search');
        $searchQueryArray = explode(' ', $searchQuery);
        $searchColumns = $request->get('searchColumns');
        $listColumns = ['id', 'type', 'palletsNumber', 'loading_atrnr', 'date'];

        if (Auth::check()) {
            $truck = DB::table('trucks')->where('id', '=', $id)->first();
            $listPalletsAccounts = DB::table('palletsaccounts')->where('type', 'Carrier')->get();
            $palletsaccount = Palletsaccount::where('name', $truck->palletsaccount_name)->first();
            $name = $truck->name;
            $licensePlate = $truck->licensePlate;

            $query = Palletstransfer::where(function ($q) use ($name, $licensePlate) {
                $q->where('creditAccount', 'LIKE',  $name . '-' . $licensePlate.'%')->orWhere('debitAccount', 'LIKE',  $name . '-' . $licensePlate.'%');
            });
            //transfers table : sorting and searching possible
            if (request()->has('sortby') && request()->has('order')) {
                $sortby = $request->get('sortby'); // Order by what column?
                $order = $request->get('order'); // Order direction: asc or desc
                $searchColumnsString = $request->get('searchColumnsString');;
                $searchColumns = explode('-', $searchColumnsString);
                if (isset($searchQuery) && $searchQuery <> '') {
                    if (in_array('ALL', explode('-', $searchColumnsString))) {
                        $query->where(function ($q) use ($searchQueryArray, $listColumns) {
                            foreach ($listColumns as $column) {
                                foreach ($searchQueryArray as $searchQ) {
                                    $q->orWhere($column, 'LIKE', '%' . $searchQ . '%');
                                }
                            }
                        });
                    } else {
                        $query->where(function ($q) use ($searchQueryArray, $searchColumns) {
                            foreach ($searchColumns as $column) {
                                foreach ($searchQueryArray as $searchQ) {
                                    $q->orWhere($column, 'LIKE', '%' . $searchQ . '%');
                                }
                            }
                        });
                    }
                }
                $listTransfers = $query->orderBy($sortby, $order)->get();
            } else {
                if (isset($searchQuery) && $searchQuery <> '') {
                    $searchColumnsString = implode('-', $searchColumns);
                    if (in_array('ALL', $searchColumns)) {
                        $query->where(function ($q) use ($searchQueryArray, $listColumns) {
                            foreach ($listColumns as $column) {
                                foreach ($searchQueryArray as $searchQ) {
                                    $q->orWhere($column, 'LIKE', '%' . $searchQ . '%');
                                }

                            }
                        });
                    } else {
                        $query->where(function ($q) use ($searchQueryArray, $searchColumns) {
                            foreach ($searchColumns as $column) {
                                foreach ($searchQueryArray as $searchQ) {
                                    $q->orWhere($column, 'LIKE', '%' . $searchQ . '%');
                                }
                            }
                        });
                    }
                }
                $listTransfers = $query->orderBy('id', 'asc')->get();
            }
            return view('trucks.detailsTruck', compact('listPalletsAccounts', 'truck', 'palletsaccount', 'searchQuery', 'listColumns', 'searchColumnsString', 'searchColumns', 'listTransfers'));
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
    public function update(Request $request, $id)
    {
        //get data
        $name = Input::get('name');
        $licensePlate = Input::get('licensePlate');
        if (!isset($licensePlate)) {
            $licensePlate = 'OTHER';
        }
        $palletsaccount_name = Input::get('palletsaccount_name');
        $truckTest = Truck::where([['name', $name], ['licensePlate', $licensePlate]])->get();

        //check if there is already one truck ith same name and same license plate
        if (count($truckTest) > 1) {
            session()->flash('messageErrorUpdateTruck', 'Error ! This truck already exists');
            return redirect()->back();
        } else {
            //if not, update the truck
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
        $palletsaccount_name=Truck::where('id', $id)->first()->palletsaccount_name;
        DB::table('trucks')->where('id', $id)->delete();
        //update the pallets account confirmed pallets number with the sum of all trucks of this account
        Palletsaccount::where('name', $palletsaccount_name)->update(['realNumberPallets'=>Palletsaccount::where('name', $palletsaccount_name)->sum('realNumberPallets'), 'theoricalNumberPallets'=>Palletsaccount::where('name', $palletsaccount_name)->sum('theoricalNumberPallets')]);

        // redirect
        session()->flash('messageDeleteTruck', 'Successfully deleted the truck!');
        return redirect('/allTrucks');
    }
}
