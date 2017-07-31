<?php

namespace App\Http\Controllers;

use App\Palletsaccount;
use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class WarehousesController extends Controller
{
    /**
     * show all warehouses in a table. You can order the different columns. You can search things in this table
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAll(Request $request)
    {
        //search keys and columns
        $searchQuery = $request->get('search');
        $searchQueryArray = explode(' ', $searchQuery);
        $searchColumns = $request->get('searchColumns');
        $listColumns = ['nickname', 'adress', 'zipcode', 'town', 'country', 'phone', 'fax', 'email', 'details'];

        if (Auth::check()) {
            $query = DB::table('warehouses');
            //if the user sorts the table
            if (request()->has('sortby') && request()->has('order')) {
                $sortby = $request->get('sortby'); // Order by what column?
                $order = $request->get('order'); // Order direction: asc or desc
                $searchColumnsString = $request->get('searchColumnsString');;
                $searchColumns = explode('-', $searchColumnsString);
                //if the user sort the table while he is searching things
                if (isset($searchQuery) && $searchQuery <> '') {
                    //search in all columns
                    if (in_array('ALL', explode('-', $searchColumnsString))) {
                        $query->where(function ($q) use ($searchQueryArray, $listColumns) {
                            foreach ($listColumns as $column) {
                                foreach ($searchQueryArray as $searchQ) {
                                    $q->orWhere($column, 'LIKE', '%' . $searchQ . '%');
                                }
                            }
                        });
                    } else {
                        //search in specifics columns
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
                $listWarehouses = $query->orderBy($sortby, $order)->paginate(10);
                $links = $listWarehouses->appends(['sortby' => $sortby, 'order' => $order, 'search'=>$searchQuery, 'searchColumns'=>$searchColumns])->render();
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
                    $listWarehouses = $query->paginate(10);
                    $links = $listWarehouses->appends(['search'=>$searchQuery, 'searchColumns'=>$searchColumns])->render();
                }else{
                    //not sorting nor searching
                    $count = count($query->get());
                    $listWarehouses = $query->orderBy('nickname', 'asc')->paginate(10);
                    $links = '';
                }
            }
            return view('warehouses.allWarehouses', compact('listWarehouses', 'sortby', 'order', 'links', 'count', 'searchQuery', 'searchColumns', 'searchColumnsString', 'listColumns'));
        } else {
            return view('auth.login');
        }

    }


    public function uploadImport(Request $request){
        $documents = $request->file('documentsWarehouses');
        if (isset($documents)) {
            foreach ($documents as $doc) {
                $filename = $doc->getClientOriginalName();
                $extension = $doc->getClientOriginalExtension();
                $size = $doc->getSize();
                //if file is an image, a pdf or an email
                if ($extension == 'xlsx' && $size < 2000000) {
                    Storage::putFileAs('/../../resources/assets/excel/ListWarehouses', $doc, $filename);
                } else {
                    session()->flash('messageErrorUpload', 'Error ! The file type is not supported (xlsx only');
                }
            }
            $this->importDataAll();
        }
        return redirect()->back();
    }

    /**
     * import data from the excel file for others : standard format
     */
    public function importDataAll()
    {
        $path = '../resources/assets/excel/ListWarehouses';
        $files = File::allFiles($path);
        foreach ($files as $file) {
            if (strpos((string)$file, '.xls') !== false) {
                Excel::load($file, function ($reader) {
                    if (!empty($reader)) {
                        $reader->noHeading();
                        foreach ($reader->get() as $sheet) {
                            $nameAccount = $sheet->getTitle();
                            for ($r = 1; $r < count($sheet); $r++) {
                                $warehouseTest = Warehouse::where('name', '=', trim($sheet[$r][0]))->orWhere('nickname', '=', trim($sheet[$r][0]))->first();
                                if ($warehouseTest == null && trim($sheet[$r][0]) <> '') {
                                    //not double
                                    $k = count(Warehouse::get()) + 1;
                                    if(Palletsaccount::where('name', $nameAccount)->first() <> null){
                                        $id = Palletsaccount::where('name', $nameAccount)->first()->id;

                                        Warehouse::firstOrCreate([
                                            'id' => $k,
                                            'name' => trim($sheet[$r][0]),
                                            'nickname' => trim($sheet[$r][0]),
                                            'adress' => trim($sheet[$r][1]),
                                            'zipcode' => trim(substr($sheet[$r][2], 0, 20)),
                                            'town' => trim($sheet[$r][3]),
                                            'country' => trim($sheet[$r][4]),
                                            'phone' => trim(substr(str_replace(' ', '', $sheet[$r][5]), 0, 20)),
                                            'fax' => trim(substr(str_replace(' ', '', $sheet[$r][6]), 0, 20)),
                                            'email' => trim($sheet[$r][7]),
                                            'details' => trim($sheet[$r][8]),
                                        ])->palletsaccounts()->sync($id);
                                    }else{
                                        session()->flash('messageErrorImport', 'Error ! The account '.$nameAccount." doesn't exist");
                                    }
                                }
                            }
                        }
                    }
                }, 'ASCII');
            }
        }
    }



    /**
     * add a new wharehouse to the list
     */
    public function add(Request $request)
    {
        //get data from the form
        $zipcode = Input::get('zipcode');
        $zipcodeWarehouses = DB::table('warehouses')->where('zipcode', '=', $zipcode)->get();
        $actionAddForm=$request->actionAddForm;
        $name = Input::get('name');
        $nickname = Input::get('nickname');
        $adress = Input::get('adress');
        $town = Input::get('town');
        $country = Input::get('country');
        $phone = Input::get('phone');
        $fax = Input::get('fax');
        $email = Input::get('email');
        $details = Input::get('details');
        $namepalletsaccounts = Input::get('namepalletsaccounts');
$originalPage=Input::get('originalPage');

        foreach ($namepalletsaccounts as $namePA) {
            $idpalletsaccounts[] = Palletsaccount::where('nickname', $namePA)->value('id');
        }

        //validation
        $rules = array(
            'zipcode' => 'required',
            'name' => 'required|string|max:255|unique:warehouses',
            'town' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'nickname'=> 'string|max:255|unique:warehouses',
        );
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
        //if the rules haven't been respected
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }elseif (Warehouse::where('name', $nickname)->orWhere('nickname', $name)->first() <> null) {
            session()->flash('messageAddWarehouse', 'Error ! This name or nickname is already used as nickname or name for an other warehouse');
            return redirect()->back()
                ->withInput();
        } else {
            //if there is an other warehouse in this city : be careful
            if (!$zipcodeWarehouses->isEmpty()) {
                if (isset($actionAddForm)&& $actionAddForm=='validateAddWarehouse') {
                    //you validate creating a new warehouse even if there is an other one in the city
                    Warehouse::create(
                        ['name' => $name, 'nickname' => $nickname, 'adress' => $adress, 'zipcode' => $zipcode, 'town' => $town, 'country' => $country, 'phone' => $phone, 'fax' => $fax, 'email' => $email, 'details' => $details]
                    )->palletsaccounts()->sync($idpalletsaccounts);
                    session()->flash('messageAddWarehouse', 'Successfully added new warehouse');
                    return redirect('/allWarehouses');
                } elseif (isset($actionAddForm)&& $actionAddForm=='refuseAddWarehouse') {
                    //if after the warning message you refuse to add this warehouse : redirect with filled field to change them
                    $listPalletsAccounts = DB::table('palletsaccounts')->get();
                    session()->flash('messageRefuseAddWarehouse', 'Please change the warehouse');
                    return view('warehouses.addWarehouse', compact('listPalletsAccounts', 'name','nickname', 'adress', 'zipcode', 'town', 'country', 'phone', 'fax', 'email', 'details', 'namepalletsaccounts'));
                } else {
                    //redirect to the add form with a pop up warning about the zipcode
                    $listPalletsAccounts = DB::table('palletsaccounts')->get();
                    session()->flash('testZipcode', true);
                    return view('warehouses.addWarehouse', compact('listPalletsAccounts', 'zipcodeWarehouses', 'name','nickname', 'adress', 'zipcode', 'town', 'country', 'phone', 'fax', 'email', 'details', 'namepalletsaccounts'));
                }
            } else {
                //if no other warehouse in the city
                Warehouse::create(
                    ['name' => $name, 'nickname' => $nickname, 'adress' => $adress, 'zipcode' => $zipcode, 'town' => $town, 'country' => $country, 'phone' => $phone, 'fax' => $fax, 'email' => $email, 'details' => $details]
                )->palletsaccounts()->sync($idpalletsaccounts);

                session()->flash('messageAddWarehouse', 'Successfully added new warehouse');


                if($originalPage=='addPalletsaccount'){
                    return redirect('/addPalletsaccount');
                }elseif(explode('-', $originalPage)[0]=='detailsPalletsaccount'){
                    return redirect('/detailsPalletsaccount/'.explode('-', $originalPage)[1]);
                }else{
                    return redirect('/allWarehouses');
                }

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
                $listPalletsAccounts = DB::table('palletsaccounts')->get();
                return view('warehouses.addWarehouse', compact('listPalletsAccounts', 'originalPage'));
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
            $listPalletsAccounts = DB::table('palletsaccounts')->orderBy('nickname', 'asc')->get();

            //get warehouse data to display on the view
            $name = $warehouse->name;
            $nickname = $warehouse->nickname;
            $adress = $warehouse->adress;
            $zipcode = $warehouse->zipcode;
            $town = $warehouse->town;
            $country = $warehouse->country;
            $phone = $warehouse->phone;
            $fax = $warehouse->fax;
            $email = $warehouse->email;
            $details = $warehouse->details;
            $activate=$warehouse->activated;

            $palletsaccounts = DB::table('palletsaccount_warehouse')->where('warehouse_id', $id)->get();
            foreach ($palletsaccounts as $palletsaccount) {
                $namepalletsaccounts[] = Palletsaccount::where('id', $palletsaccount->palletsaccount_id)->value('nickname');
            }

            return view('warehouses.detailsWarehouse', compact('listPalletsAccounts', 'activate','id', 'name', 'nickname', 'adress', 'zipcode', 'town', 'country', 'phone', 'fax', 'email', 'details', 'namepalletsaccounts'));
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
        $actionUpdateForm=$request->actionUpdateForm;

        if(isset($actionUpdateForm) && $actionUpdateForm=='desactivate'){
            Warehouse::where('id', $id)->update(['activated'=> false]);
            return redirect()->back();
        }elseif(isset($actionUpdateForm) && $actionUpdateForm=='activate'){
            Warehouse::where('id', $id)->update(['activated'=> true]);
            return redirect()->back();
        }else{
            $namepalletsaccounts = Input::get('namepalletsaccounts');
            foreach ($namepalletsaccounts as $namePA) {
                $idpalletsaccounts[] = Palletsaccount::where('nickname', $namePA)->value('id');
            }
            $nickname = Input::get('nickname');
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
            $details = Input::get('details');

            //validation
            $rules = array(
                'zipcode' => 'required',
                'nickname' => 'required|string|max:255|unique:warehouses,nickname,' . $id,
                'town' => 'required|string|max:255',
                'country' => 'required|string|max:255',
            );
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
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }elseif (Warehouse::where('id', '<>', $id)->where('name', $nickname)->first() <> null) {
                session()->flash('messageUpdateWarehouse', 'Error ! This nickname is already used as name for an other warehouse');
                return redirect()->back()
                    ->withInput();
            } else {
                //same warning as in the add form when there is already an other warehouse in the same city
                if (isset($zipcodeWarehouses) && !$zipcodeWarehouses->isEmpty()) {
                    if (isset($actionUpdateForm)&& $actionUpdateForm=='validateUpdateWarehouse') {
                        Warehouse::where('id', $id)->update(['nickname' => $nickname, 'adress' => $adress, 'zipcode' => $zipcode, 'town' => $town, 'country' => $country, 'phone' => $phone, 'fax' => $fax, 'email' => $email, 'details' => $details]);
                        Warehouse::where('id', $id)->first()->palletsaccounts()->sync($idpalletsaccounts);
                        session()->flash('messageUpdateWarehouse', 'Successfully updated warehouse');
                        return redirect()->back();
                    } elseif (isset($actionUpdateForm)&& $actionUpdateForm=='refuseUpdateWarehouse') {
                        $listPalletsAccounts = DB::table('palletsaccounts')->get();
                        session()->flash('messageRefuseUpdateWarehouse', 'Please change the warehouse');
                        return view('warehouses.detailsWarehouse', compact('listPalletsAccounts', 'id', 'name', 'nickname', 'adress', 'zipcode', 'town', 'country', 'phone', 'fax', 'email', 'details', 'namepalletsaccounts'));
                    } else {
                        $listPalletsAccounts = DB::table('palletsaccounts')->get();
                        session()->flash('testZipcode', true);
                        return view('warehouses.detailsWarehouse', compact('listPalletsAccounts', 'id', 'zipcodeWarehouses', 'name', 'nickname', 'adress', 'zipcode', 'town', 'country', 'phone', 'fax', 'email', 'details', 'namepalletsaccounts'));
                    }
                } else {
                    Warehouse::where('id', $id)->update(['nickname' => $nickname, 'adress' => $adress, 'zipcode' => $zipcode, 'town' => $town, 'country' => $country, 'phone' => $phone, 'fax' => $fax, 'email' => $email, 'details' => $details]);
                    Warehouse::where('id', $id)->first()->palletsaccounts()->sync($idpalletsaccounts);

                    session()->flash('messageUpdateWarehouse', 'Successfully updated warehouse');
                    return redirect()->back();
                }
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

//    /**
//     * import warehouses data from excel files. Different import because of different kind of excel files
//     * @param Request $request
//     */
//    public function refreshListWarehouses(Request $request)
//    {
//        $this->importDataPFM();
//        $this->importDataSystempo();
//        $this->importDataDPL();
//        $this->importDataAll();
//    }


//    /**
//     * import data from the excel file for PFM
//     */
//    public function importDataPFM()
//    {
//        $path = '../resources/assets/excel/ListWarehouses/PFM';
//        $files = File::allFiles($path);
//        foreach ($files as $file) {
//            if (strpos((string)$file, '.xls') !== false) {
//                Excel::load($file, function ($reader) {
//                    if (!empty($reader)) {
//                        $reader->noHeading();
//                        $sheet = $reader->getSheet(1)->toArray();
//                        $nbrows = count($sheet);
//
//                        for ($r = 1; $r < $nbrows; $r++) {
//                            $warehouseTest = Warehouse::where('name', '=', trim($sheet[$r][3]))->orWhere('nickname', '=', trim($sheet[$r][3]))->first();
//                            if ($warehouseTest == null && trim($sheet[$r][3]) <> '') {
//                                //if the warehouse doesn't exist yet
//                                $k = count(Warehouse::get()) + 1;
//                                $id = Palletsaccount::where('name', 'PFM - FR')->first()->id;
//
//                                $name = trim($sheet[$r][3]);
//                                $nickname = $name;
//                                if (intval(trim($sheet[$r][0])) <> 0) {
//                                    $country = 'FR';
//                                } else {
//                                    $country = trim($sheet[$r][0]);
//                                }
//
//                                $cell7 = str_replace(' - ', ' ', trim($sheet[$r][7]));
//                                $cell7 = str_replace('-', ' ', $cell7);
//                                if (substr($cell7, 5, 1) == ' ') {
//                                    $zipcode = trim(substr($cell7, 0, 5));
//                                } else {
//                                    $zipcode = trim(substr($cell7, 0, 7));
//                                }
//                                $town = trim(str_replace($zipcode, '', $cell7));
//
//                                Warehouse::firstOrCreate([
//                                    'id' => $k,
//                                    'name' => $name,
//                                    'nickname' => $nickname,
//                                    'adress' => trim($sheet[$r][6]),
//                                    'zipcode' => $zipcode,
//                                    'town' => $town,
//                                    'country' => $country,
//                                    'phone' => trim(substr(str_replace(' ', '', $sheet[$r][8]), 0, 14)),
//                                    'fax' => trim(substr(str_replace(' ', '', $sheet[$r][9]), 0, 14)),
//                                    'email' => trim($sheet[$r][12]),
//                                    'details' => trim($sheet[$r][4]) . ' - ' . trim($sheet[$r][5]),
//                                ])->palletsaccounts()->sync($id);
//                            }
//                        }
//                    }
//                }, 'ASCII');
//            }
//        }
//    }
//
//    /**
//     * import data from the excel file for Systempo AT
//     */
//    public function importDataSystempo()
//    {
//        $path = '../resources/assets/excel/ListWarehouses/Systempo';
//        $files = File::allFiles($path);
//        foreach ($files as $file) {
//            if (strpos((string)$file, '.xls') !== false) {
//                Excel::load($file, function ($reader) {
//                    if (!empty($reader)) {
//                        $reader->noHeading();
//                        $sheet = $reader->getSheet(0)->toArray();
//                        $nbrows = count($sheet);
//
//                        for ($r = 1; $r < $nbrows; $r++) {
//                            $warehouseTest = Warehouse::where('name', '=', trim($sheet[$r][0]))->orWhere('nickname', '=', trim($sheet[$r][0]))->first();
//                            if ($warehouseTest == null && trim($sheet[$r][0]) <> '') {
//                                //if the warehouse doesn't exist yet
//                                $k = count(Warehouse::get()) + 1;
//                                $id = Palletsaccount::where('name', 'Systempo AT')->first()->id;
//
//                                Warehouse::firstOrCreate([
//                                    'id' => $k,
//                                    'name' => trim($sheet[$r][0]),
//                                    'nickname' => trim($sheet[$r][0]),
//                                    'adress' => trim($sheet[$r][1]),
//                                    'zipcode' => intval(trim($sheet[$r][3])),
//                                    'town' => trim($sheet[$r][4]),
//                                    'country' => trim($sheet[$r][2]),
//                                    'phone' => trim(str_replace(' ', '', $sheet[$r][5])),
//                                    'email' => trim($sheet[$r][6]),
//                                    'details' => trim($sheet[$r][7]),
//                                ])->palletsaccounts()->sync($id);
//                            }
//                        }
//                    }
//                }, 'ASCII');
//            }
//        }
//    }
//
//    /**
//     * import data from the excel file for DPL
//     */
//    public function importDataDPL()
//    {
//        $path = '../resources/assets/excel/ListWarehouses/DPL';
//        $files = File::allFiles($path);
//        foreach ($files as $file) {
//            if (strpos((string)$file, '.xls') !== false) {
//                Excel::load($file, function ($reader) {
//                    if (!empty($reader)) {
//                        $reader->noHeading();
//                        $sheet = $reader->getSheet(0)->toArray();
//                        $nbrows = count($sheet);
//
//                        for ($r = 1; $r < $nbrows; $r++) {
//                            $warehouseTest = Warehouse::where('name', '=', trim($sheet[$r][3]))->orWhere('nickname', '=', trim($sheet[$r][3]))->first();
//                            if ($warehouseTest == null && trim($sheet[$r][3]) <> '') {
//                                //if the warehouse doesn't exist yet
//                                $k = count(Warehouse::get()) + 1;
//                                $id = Palletsaccount::where('name', 'DPL')->first()->id;
//
//                                Warehouse::firstOrCreate([
//                                    'id' => $k,
//                                    'name' => trim($sheet[$r][3]),
//                                    'nickname' => trim($sheet[$r][3]),
//                                    'adress' => trim($sheet[$r][2]),
//                                    'zipcode' => intval(trim(explode('-', $sheet[$r][0])[1])),
//                                    'town' => trim($sheet[$r][1]),
//                                    'country' => 'D',
//                                    'phone' => trim(str_replace(' ', '', $sheet[$r][6])),
//                                    'email' => trim($sheet[$r][7]),
//                                ])->palletsaccounts()->sync($id);
//                            }
//                        }
//                    }
//                }, 'ASCII');
//            }
//        }
//    }


}
