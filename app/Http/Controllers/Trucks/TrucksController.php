<?php

namespace App\Http\Controllers;

use App\Loading;
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
    public function showAll(Request $request, $refresh, $nb)
    {
        $searchQuery = $request->get('search');
        $searchQueryArray = explode(' ', $searchQuery);
        $searchColumns = $request->get('searchColumns');
        $listColumns = ['id', 'name', 'licensePlate', 'realNumberPallets', 'theoricalNumberPallets', 'theoricalPalletsDebt', 'realPalletsDebt'];

        if (Auth::check()) {
            //import new trucks and create pallets account associated
            if ($refresh == 'true') {
                $this->importData();
                $refresh = 'false';
            }

            if ($nb == 'all') {
                $query = DB::table('trucks');
            } elseif ($nb == 'debt only') {
                $query = DB::table('trucks')->where('palletsDebt', '<>', 0);
            }


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
                $listTrucks = $query->orderBy($sortby, $order)->paginate(20);
                $links = $listTrucks->appends(['sortby' => $sortby, 'order' => $order, 'search' => $searchQuery, 'searchColumns' => $searchColumns])->render();
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
                    $listTrucks = $query->orderBy('name', 'asc')->paginate(20);
                    $links = $listTrucks->appends(['search' => $searchQuery, 'searchColumns' => $searchColumns])->render();
                } else {
                    //not sorting nor searching
                    $count = count($query->get());
                    $listTrucks = $query->orderBy('name', 'asc')->paginate(20);
                    $links = '';
                }
            }
            return view('trucks.allTrucks', compact('nb', 'listTrucks', 'sortby', 'order', 'links', 'count', 'searchQuery', 'searchColumnsString', 'searchColumns', 'listColumns', 'refresh'));
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
                        foreach ($reader->get() as $sheet) {
                            for ($r = 4; $r < count($sheet); $r++) {
                                if (trim($sheet[$r][24]) == 'JA') {
                                    if (trim($sheet[$r][26]) <> '') {
                                        $licensePlate = trim($sheet[$r][26]);
                                    } else {
                                        $licensePlate = 'OTHER';
                                    }
                                    if ($sheet[$r][25] <> null) {
                                        if (count(explode(',', $sheet[$r][25])) > 2) {
                                            $adress = trim(explode(',', $sheet[$r][25])[count(explode(',', $sheet[$r][25])) - 1]);
                                            $name = trim(str_replace($adress, '', $sheet[$r][25]));
                                            $country = null;
                                            $zipcode = null;
                                            $town = null;
                                        } else {
                                            $name = trim(explode(',', $sheet[$r][25])[0]);
                                            $adress = trim(explode(',', $sheet[$r][25])[1]);
                                            $country = trim(explode('-', $adress)[0]);
                                            $zipTown = trim(explode('-', $adress)[1]);
                                            $zipcode = trim(explode(' ', $zipTown)[0]);
                                            $town = str_replace($zipcode, '', $zipTown);
                                        }

                                        $testAccount = Palletsaccount::where('type', 'Carrier')->where(function ($q) use ($name) {
                                            $q->where('name', $name)->orWhere('nickname', $name);
                                        })->first();
                                        if ($testAccount == null) {
                                            Palletsaccount::firstOrCreate([
                                                'name' => $name,
                                                'nickname' => $name,
                                                'adress' => $adress,
                                                'country' => $country,
                                                'zipcode' => $zipcode,
                                                'town' => $town,
                                                'type' => 'Carrier',
                                            ]);
                                        }

                                        $testTruckStock = Truck::where('licensePlate', '=', 'STOCK')->where('name', $name)->first();

                                        if ($testTruckStock == null) {
                                            Truck::firstOrCreate([
                                                'name' => $name,
                                                'licensePlate' => 'STOCK',
                                                'palletsaccount_name' => $name,
                                            ]);
                                        }
                                        $testTruck = Truck::where('licensePlate', '=', $licensePlate)->where('name', $name)->first();

                                        if ($testTruck == null) {
                                            //not double
                                            Truck::firstOrCreate([
                                                'name' => $name,
                                                'licensePlate' => $licensePlate,
                                                'palletsaccount_name' => $name,
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }
    }

    /**
     * show the add form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAdd($originalPage)
    {
        if (Auth::check()) {
            $listPalletsAccounts = DB::table('palletsaccounts')->where('type', 'Carrier')->orderBy('nickname', 'asc')->get();
            return view('trucks.addTruck', compact('listPalletsAccounts', 'originalPage'));
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
        $name = Input::get('name');
        $realNumberPallets = Input::get('realNumberPallets');
        $originalPage = Input::get('originalPage');
        $atrnr = Input::get('atrnr');
        $activate = Input::get('activate');

        $licensePlate = Input::get('licensePlate');
        if (!isset($licensePlate)) {
            $licensePlate = 'OTHER';
        }
        $palletsaccount_name = Input::get('palletsaccount_name');
        $truckTest = Truck::where([['name', $name], ['licensePlate', $licensePlate]])->first();

        //if the truck already exists -> error !
        if ($truckTest <> null) {
            session()->flash('messageErrorAddTruck', 'Error ! This truck already exists');
            $listPalletsAccounts = DB::table('palletsaccounts')->where('type', 'Carrier')->orderBy('nickname', 'asc')->get();
            return view('trucks.addTruck', compact('name', 'nickname', 'activate', 'realNumberPallets', 'licensePlate', 'palletsaccount_name', 'listPalletsAccounts'));
        } else {
            if (isset($activate)) {
                $activate = true;
            } else {
                $activate = false;
            }
            //if the truck doesn't already exists -> create
            Truck::create(
                ['name' => $name, 'realNumberPallets' => $realNumberPallets, 'theorcialNumberPallets' => $realNumberPallets, 'licensePlate' => $licensePlate, 'palletsaccount_name' => $palletsaccount_name, 'activated' => $activate]
            );
            //update the pallets account confirmed pallets number with the sum of all trucks of this account
            Palletsaccount::where('nickname', $palletsaccount_name)->update(['realNumberPallets' => Palletsaccount::where('nickname', $palletsaccount_name)->sum('realNumberPallets'), 'theoricalNumberPallets' => Palletsaccount::where('nickname', $palletsaccount_name)->sum('theoricalNumberPallets')]);
            session()->flash('messageAddTruck', 'Successfully added new truck');

            if (explode('-', $originalPage)[0] == 'detailsPalletsaccount') {
                return redirect('/detailsPalletsaccount/' . explode('-', $originalPage)[1]);
            } elseif (explode('-', $originalPage)[0] == 'detailsLoading') {
                if (isset($atrnr)) {
                    Loading::where('atrnr', $atrnr)->update(['kennzeichen' => $licensePlate]);
                }
                session()->flash('openPanelInformation', 'openPanelInformation');
                return redirect('/detailsLoading/' . explode('-', $originalPage)[1]);
            } elseif (explode('-', $originalPage)[0] == 'allTrucks') {
                return redirect('/allTrucks/false/'.explode('-', $originalPage)[1]);
            }
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
            $listPalletsAccounts = DB::table('palletsaccounts')->where('type', 'Carrier')->orderBy('nickname', 'asc')->get();
            $palletsaccount = Palletsaccount::where('nickname', $truck->palletsaccount_name)->first();
            $name = $truck->name;
            $licensePlate = $truck->licensePlate;

            $query = Palletstransfer::where(function ($q) use ($name, $licensePlate) {
                $q->where('creditAccount', 'LIKE', $name . '-' . $licensePlate . '%')->orWhere('debitAccount', 'LIKE', $name . '-' . $licensePlate . '%');
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
        $activate = Input::get('activate');
        if (isset($activate)) {
            $activate = true;
        } else {
            $activate = false;
        }
        $licensePlate = Input::get('licensePlate');
        if (!isset($licensePlate)) {
            $licensePlate = 'OTHER';
        }
        $palletsaccount_name = Input::get('palletsaccount_name');
        $name = $palletsaccount_name;
        $truckTest = Truck::where([['name', $name], ['licensePlate', $licensePlate]])->get();

        //check if there is already one truck ith same name and same license plate
        if (count($truckTest) > 1) {
            session()->flash('messageErrorUpdateTruck', 'Error ! This truck already exists');
            return redirect()->back();
        } else {
            //if not, update the truck
            Truck::where('id', $id)->update(['name' => $name, 'licensePlate' => $licensePlate, 'palletsaccount_name' => $palletsaccount_name, 'activated' => $activate]);
            session()->flash('messageUpdateTruck', 'Successfully updated truck');
            return redirect()->back();
        }
    }


    /**
     * delete the truck
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
//    public function delete($id)
//    {
//        $nameTruck=Truck::where('id', $id)->first()->name;
//        $licensePlateTruck=Truck::where('id', $id)->first()->licensePlate;
//        $transfers = Palletstransfer::where(function ($q) use ($nameTruck, $licensePlateTruck) {
//            $q->where('creditAccount', 'LIKE', $nameTruck . '-' . $licensePlateTruck . '-' . '%')->orWhere('debitAccount', 'LIKE',  $nameTruck . '-' . $licensePlateTruck . '-' . '%');
//        })->get();
//        if(!$transfers->isEmpty()){
//            session()->flash('messageDeleteTruck', 'Error ! You cant delete this truck because transfers are associated to.');
//            return redirect()->back();
//        }else {
//            $palletsaccount_name = Truck::where('id', $id)->first()->palletsaccount_name;
//            DB::table('trucks')->where('id', $id)->delete();
//            //update the pallets account confirmed pallets number with the sum of all trucks of this account
//            Palletsaccount::where('name', $palletsaccount_name)->update(['realNumberPallets' => Palletsaccount::where('name', $palletsaccount_name)->sum('realNumberPallets'), 'theoricalNumberPallets' => Palletsaccount::where('name', $palletsaccount_name)->sum('theoricalNumberPallets')]);
//
//            // redirect
//            session()->flash('messageDeleteTruck', 'Successfully deleted the truck!');
//            return redirect('/allTrucks/false');
//        }
//    }
}
