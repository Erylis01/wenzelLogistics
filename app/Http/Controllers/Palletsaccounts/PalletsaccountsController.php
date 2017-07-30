<?php

namespace App\Http\Controllers;

use App\Loading;
use App\Palletsaccount;
use App\Palletstransfer;
use App\Truck;
use App\User;
use App\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

/**
 * Class PalletsaccountsController
 * @package App\Http\Controllers
 */
class PalletsaccountsController extends Controller
{
    /**
     * Display the content.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAll(Request $request, $nb)
    {
        $searchQuery = $request->get('search');
        $searchQueryArray = explode(' ', $searchQuery);
        $searchColumns = $request->get('searchColumns');
        $listColumns = ['nickname', 'type', 'realNumberPallets', 'theoricalNumberPallets', 'palletsDebt'];
        if (Auth::check()) {
            $totalpallets = DB::table('palletsaccounts')->sum('realNumberPallets');
            $totalDebtpallets = DB::table('palletsaccounts')->sum('palletsDebt');

            if ($nb == 'all') {
                $query = DB::table('palletsaccounts');
            } elseif ($nb == 'debt only') {
                $query = DB::table('palletsaccounts')->where('palletsDebt', '<>', 0);
            }

            //if the user is sorting the table
            if (request()->has('sortby') && request()->has('order')) {
                $sortby = $request->get('sortby'); // Order by what column?
                $order = $request->get('order'); // Order direction: asc or desc
                $searchColumnsString = $request->get('searchColumnsString');;
                $searchColumns = explode('-', $searchColumnsString);
                //if the user is searching data in the table
                if (isset($searchQuery) && $searchQuery <> '') {
                    //searching in all columns
                    if (in_array('ALL', explode('-', $searchColumnsString))) {
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
                }
                $count = count($query->get());
                $listPalletsaccounts = $query->orderBy($sortby, $order)->paginate(20);
                $links = $listPalletsaccounts->appends(['sortby' => $sortby, 'order' => $order, 'search' => $searchQuery, 'searchColumns' => $searchColumns])->render();
            } else {
                //only searching
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
                    $count = count($query->get());
                    $listPalletsaccounts = $query->orderBy('nickname', 'asc')->paginate(20);
                    $links = $listPalletsaccounts->appends(['search' => $searchQuery, 'searchColumns' => $searchColumns])->render();
                } else {
                    //not sorting nor searching
                    $count = count($query->get());
                    $listPalletsaccounts = $query->orderBy('nickname', 'asc')->paginate(20);
                    $links = '';
                }
            }
            return view('palletsaccounts.allPalletsaccounts', compact('listPalletsaccounts', 'nb', 'totalpallets', 'totalDebtpallets', 'sortby', 'order', 'count', 'links', 'searchQuery', 'searchColumns', 'searchColumnsString', 'listColumns'));
        } else {
            return view('auth.login');
        }
    }

    /** show the form to add a new pallets account
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAdd($originalPage)
    {
        if (Auth::check()) {
            //list of the warehouses if the account created is a Network
            $listWarehouses = DB::table('warehouses')->get();
            return view('palletsaccounts.addPalletsaccount', compact('listWarehouses', 'originalPage'));
        } else {
            return view('auth.login');
        }
    }

    /**
     * add a new pallets account to the list
     */
    public function add(Request $request)
    {
        //get data
        $name = Input::get('name');
        $nickname = Input::get('nickname');
        $type = Input::get('type');
        $realNumberPallets = Input::get('realNumberPallets');
        $theoricalNumberPallets = $realNumberPallets;
        $originalPage = Input::get('originalPage');

        //get the warehouses associated if the account is a Network
        $warehousesAssociatedName = Input::get('warehousesAssociated');
        if (isset($warehousesAssociatedName)) {
            foreach ($warehousesAssociatedName as $nameWarehouse) {
                $idwarehouses[] = Warehouse::where('nickname', $nameWarehouse)->value('id');
            }
        }
        $oneWarehouse = Input::get('oneWarehouse');

        //oneWarehouse or carrier
        $adress = Input::get('adress');
        $country = Input::get('country');
        $town = Input::get('town');
        $zipcode = Input::get('zipcode');
        $phone = Input::get('phone');
        $fax = Input::get('fax');
        $email = Input::get('email');
        $details = Input::get('details');

        $atrnr = Input::get('atrnr');

        //validation
        $rules = array(
            'name' => 'required|string|max:255|unique:palletsaccounts',
            'nickname' => 'string|max:255|unique:palletsaccounts',
        );
        if (isset($oneWarehouse)) {
            $rules = array_add($rules, 'country', 'required|string|max:255');
            $rules = array_add($rules, 'town', 'required|string|max:255');
            $rules = array_add($rules, 'zipcode', 'required|string|max:10');
        }
        if (isset($email)) {
            $rules = array_add($rules, 'email', 'string|email');
        }
        if (isset($phone)) {
            $rules = array_add($rules, 'phone', 'string|max:20');
        }
        if (isset($fax)) {
            $rules = array_add($rules, 'fax', 'string|max:20');
        }

        $validator = Validator::make(Input::all(), $rules);
//if the rules are not respected
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }elseif (Palletsaccount::where('name', $nickname)->orWhere('nickname', $name)->first() <> null) {
            session()->flash('messageAddPalletsaccount', 'Error ! This nickname or name is already as a nickname or name for an other account');
        return redirect()->back()
            ->withInput();
    } else {
            //pallets account creation
            if ($type == 'Network' && isset($warehousesAssociatedName) && !(isset($oneWarehouse))) {
                Palletsaccount::create(
                    ['name' => $name, 'nickname' => $nickname, 'realNumberPallets' => $realNumberPallets, 'theoricalNumberPallets' => $theoricalNumberPallets, 'type' => $type]
                )->warehouses()->sync($idwarehouses);

            } elseif ($type == 'Network') {
                Palletsaccount::create(
                    ['name' => $name, 'nickname' => $nickname, 'realNumberPallets' => $realNumberPallets, 'theoricalNumberPallets' => $theoricalNumberPallets, 'type' => $type, 'adress' => $adress, 'zipcode' => $zipcode, 'town' => $town, 'country' => $country, 'phone' => $phone, 'fax' => $fax, 'email' => $email, 'details' => $details]
                );
                if (isset($oneWarehouse)) {
                    Warehouse::create(['name' => $name, 'nickname' => $nickname, 'adress' => $adress, 'zipcode' => $zipcode, 'town' => $town, 'country' => $country, 'phone' => $phone, 'fax' => $fax, 'email' => $email, 'details' => $details]);
                }
            } elseif ($type == 'Carrier') {
                Palletsaccount::create(
                    ['name' => $name, 'nickname' => $nickname, 'type' => $type, 'adress' => $adress, 'zipcode' => $zipcode, 'town' => $town, 'country' => $country, 'email' => $email, 'phone' => $phone, 'fax' => $fax, 'details' => $details]
                );
                Truck::create(['name' => $nickname, 'licensePlate' => 'STOCK', 'palletsaccount_name' => $nickname]);
                Truck::create(['name' => $nickname, 'licensePlate' => 'OTHER', 'palletsaccount_name' => $nickname]);
            } elseif ($type == 'Other') {
                Palletsaccount::create(
                    ['name' => $name, 'nickname' => $nickname, 'realNumberPallets' => $realNumberPallets, 'theoricalNumberPallets' => $theoricalNumberPallets, 'type' => $type]
                );
            }
//redirect
            session()->flash('messageAddPalletsaccount', 'Successfully added new pallets account');
            if (explode('-', $originalPage)[0] =='allPalletsaccounts') {
                return redirect('/allPalletsaccounts/'.explode('-', $originalPage)[1]);
            } elseif (explode('-', $originalPage)[0] == 'detailsLoading') {
                if (isset($atrnr)) {
                    Loading::where('atrnr', $atrnr)->update(['subfrachter' => $nickname . ', ' . $country.'-'.$zipcode.' '.$town]);
                }
                session()->flash('openPanelInformation', 'openPanelInformation');
                return redirect('/detailsLoading/' . explode('-', $originalPage)[1]);
            } elseif ($originalPage == 'addWarehouse') {
                return redirect('/addWarehouse');
            } elseif (explode('-', $originalPage)[0] == 'detailsWarehouse') {
                return redirect('/detailsWarehouse/' . explode('-', $originalPage)[1]);
            } elseif ($originalPage == 'addTruck') {
                return redirect('/addTruck');
            } elseif (explode('-', $originalPage)[0] == 'detailsTruck') {
                return redirect('/detailsTruck/' . explode('-', $originalPage)[1]);
            }elseif (explode('-', $originalPage)[0] == 'detailsPalletstransfer') {
                return redirect('/detailsPalletstransfer/' . explode('-', $originalPage)[1]);
            }elseif ($originalPage == 'addPalletstransfer') {
                return redirect('/addPalletstransfer');
            }
        }
    }

    /**
     * show a specific pallets account
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public
    function showDetails($id, Request $request)
    {
        if (Auth::check()) {
            //general data
            $listWarehouses = DB::table('warehouses')->orderBy('nickname', 'asc')->get();
            $account = Palletsaccount::where('id', $id)->first();
            $nickname = $account->nickname;

            //according to the type of account, get the right entities associated
            if ($account->type == 'Network') {
                $warehousesAssociated = DB::table('palletsaccount_warehouse')->where('palletsaccount_id', $id)->get();
                if (!$warehousesAssociated->isEmpty()) {
                    foreach ($warehousesAssociated as $warehouse) {
                        $namewarehouses[] = Warehouse::where('id', $warehouse->warehouse_id)->value('nickname');
                    }
                    asort($namewarehouses);
                }
            } elseif ($account->type == 'Carrier') {
                $trucksActivated = Truck::where('palletsaccount_name', $nickname)->where('activated',1)->orderBy('licensePlate', 'asc')->get();
                $trucksInactivated = Truck::where('palletsaccount_name', $nickname)->where('activated',0)->orderBy('licensePlate', 'asc')->get();
            }

            //table data transfers
            //search data
            $searchQuery = $request->get('search');
            $searchQueryArray = explode(' ', $searchQuery);
            $searchColumns = $request->get('searchColumns');
            if ($account->type == 'Carrier') {
                $listColumns = ['id', 'type', 'palletsNumber', 'loading_atrnr', 'date', 'licensePlate', 'state'];
            } else {
                $listColumns = ['id', 'type', 'palletsNumber', 'loading_atrnr', 'date', 'state'];
            }

            $query = Palletstransfer::where(function ($q) use ($nickname) {
                $q->where('creditAccount', 'LIKE', $nickname . '-' . '%')->orWhere('debitAccount', 'LIKE', $nickname . '-' . '%');
            });

            //if the user is sorting the table
            if (request()->has('sortby') && request()->has('order')) {
                $sortby = $request->get('sortby'); // Order by what column?
                $order = $request->get('order'); // Order direction: asc or desc
                $searchColumnsString = $request->get('searchColumnsString');;
                $searchColumns = explode('-', $searchColumnsString);
                //if sorting while searching something in the table
                if (isset($searchQuery) && $searchQuery <> '') {
                    //if searching in the whole table
                    if (in_array('ALL', explode('-', $searchColumnsString))) {
                        $query->where(function ($q) use ($searchQueryArray, $listColumns) {
                            foreach ($listColumns as $column) {
                                foreach ($searchQueryArray as $searchQ) {
                                    $q->orWhere($column, 'LIKE', '%' . $searchQ . '%');
                                }
                            }
                        });
                    } else {
                        //if searching in special columns
                        $query->where(function ($q) use ($searchQueryArray, $searchColumns) {
                            foreach ($searchColumns as $column) {
                                foreach ($searchQueryArray as $searchQ) {
                                    $q->orWhere($column, 'LIKE', '%' . $searchQ . '%');
                                }
                            }
                        });
                    }
                }
//if searching in the license plate columns that is not a field of the data table Pallets account but Truck
                if ($sortby == 'licensePlate') {
                    $listTransfers = $query->with(['trucks' => function ($query, $order) {
                        $query->orderBy('licensePlate', $order);
                    }])->get();
                } else {
                    $listTransfers = $query->orderBy($sortby, $order)->get();
                }
            } else {
                //if not sorting but searching
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

            return view('palletsaccounts.detailsPalletsaccount', compact('searchQuery', 'listColumns', 'searchColumnsString', 'searchColumns', 'listTransfers', 'listWarehouses', 'account', 'namewarehouses', 'trucksActivated', 'trucksInactivated'));
        } else {
            return view('auth.login');
        }
    }

    /**
     * update the pallets account n° ID - if carrier : clear trucks possible
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $actionClearForm = $request->actionClearForm;
        $actualNickname=Palletsaccount::where('id', $id)->first()->nickname;
        $listTrucks = Truck::where('palletsaccount_name', $actualNickname)->get();

        $desactivate=$request->desactivate;
        $activate=$request->activate;

        if (isset($actionClearForm) && $actionClearForm == 'Clear trucks') {
            //CARRIER ONLY : this button will clear every pallets number for ervery truck and put the sum in the "truck" STOCK that is the stock of the carrier
            foreach ($listTrucks as $truck) {
                if ($truck->licensePlate <> 'STOCK') {
                    $realNumberPallets = $truck->realNumberPallets;
                    $theoricalNumberPallets = $truck->theoricalNumberPallets;
                    $palletsDebt=$truck->palletsDebt;
                    Truck::where('palletsaccount_name', $actualNickname)->where('licensePlate', $truck->licensePlate)->update(['realNumberPallets' => 0, 'theoricalNumberPallets' => 0, 'palletsDebt'=>0]);
                    $realNumberPalletsStock = Truck::where('palletsaccount_name', $actualNickname)->where('licensePlate', 'STOCK')->first()->realNumberPallets;
                    $theoricalNumberPalletsStock = Truck::where('palletsaccount_name', $actualNickname)->where('licensePlate', 'STOCK')->first()->theoricalNumberPallets;
                    $palletsDebtStock = Truck::where('palletsaccount_name', $actualNickname)->where('licensePlate', 'STOCK')->first()->palletsDebt;
                    Truck::where('palletsaccount_name', $actualNickname)->where('licensePlate', 'STOCK')->update(['palletsDebt' => $palletsDebtStock + $palletsDebt,'realNumberPallets' => $realNumberPalletsStock + $realNumberPallets, 'theoricalNumberPallets' => $theoricalNumberPalletsStock + $theoricalNumberPallets]);
                }
            }
            session()->flash('messageClearTrucks', 'Successfully cleared trucks');
        } elseif(isset($desactivate)){
            Truck::where('id', $desactivate)->update(['activated'=> false]);
        }elseif(isset($activate)){
            Truck::where('id', $activate)->update(['activated'=> true]);
        }else {
            //update data
            $nickname = Input::get('nickname');
            if(Palletsaccount::where('id', '<>', $id)->where(function($q) use($nickname){
                $q->where('nickname', $nickname)->orWhere('name', $nickname);})->first() <> null){
                session()->flash('messageUpdatePalletsaccount', 'Error ! This nickname is already taken');
            }else{
                foreach($listTrucks as $truck){
                    Truck::where('id', $truck->id)->update(['name'=>$nickname, 'palletsaccount_name'=>$nickname]);
                }
                Palletsaccount::where('id', $id)->update(['nickname' => $nickname]);
                $type = Input::get('type');
                Palletsaccount::where('id', $id)->update(['type' => $type]);
                if (isset($type) && $type == 'Network') {
                    $warehousesAssociatedName = Input::get('namewarehouses');
                    if (isset($warehousesAssociatedName)) {
                        foreach ($warehousesAssociatedName as $warehouseAName) {
                            $idwarehouses[] = Warehouse::where('nickname', $warehouseAName)->value('id');
                        }
                        Palletsaccount::where('id', $id)->first()->warehouses()->sync($idwarehouses);
                    }
                } elseif (isset($type) && $type == 'Carrier') {
                    $adress = Input::get('adress');
                    $zipcode = Input::get('zipcode');
                    $country=Input::get('country');
                    $town=Input::get('town');
                    $phone = Input::get('phone');
                    $fax=Input::get('fax');
                    $email = Input::get('email');
                    $details = Input::get('details');

                    if (isset($adress)) {
                        Palletsaccount::where('id', $id)->update(['adress' => $adress]);
                    }
                    if (isset($phone)) {
                        Palletsaccount::where('id', $id)->update(['phone' => $phone]);
                    }
                    if (isset($fax)) {
                        Palletsaccount::where('id', $id)->update(['fax' => $fax]);
                    }
                    if (isset($email)) {
                        Palletsaccount::where('id', $id)->update(['email' => $email]);
                    }
                    if (isset($details)) {
                        Palletsaccount::where('id', $id)->update(['details' => $details]);
                    }
                    if (isset($country)) {
                        Palletsaccount::where('id', $id)->update(['country' => $country]);
                    }
                    if (isset($town)) {
                        Palletsaccount::where('id', $id)->update(['town' => $town]);
                    }
                    if (isset($zipcode)) {
                        Palletsaccount::where('id', $id)->update(['zipcode' => $zipcode]);
                    }
                }
                session()->flash('messageUpdatePalletsaccount', 'Successfully updated pallets account');
            }
        }
        return redirect()->back();
    }

    /** delete a specific pallets account
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
//    public function delete($id)
//    {
//        $nameAccount = Palletsaccount::where('id', $id)->first()->name;
//        $transfers = Palletstransfer::where(function ($q) use ($nameAccount) {
//            $q->where('creditAccount', 'LIKE', $nameAccount . '-' . '%')->orWhere('debitAccount', 'LIKE', $nameAccount . '-' . '%');
//        })->get();
////        dd($transfers);
//        if (!$transfers->isEmpty()) {
//            session()->flash('messageDeletePalletsaccount', 'Error ! You cant delete this account because transfers are associated to.');
//            return redirect()->back();
//        } else {
//            DB::table('palletsaccounts')->where('id', $id)->delete();
//            // redirect
//            session()->flash('messageDeletePalletsaccount', 'Successfully deleted the pallets account!');
//            return redirect('/allPalletsaccounts/all');
//        }
//    }

}
