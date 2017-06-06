<?php

namespace App\Http\Controllers;

use App\Palletsaccount;
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
    public function showAll(Request $request)
    {

        if (Auth::check()) {
            $totalpallets = DB::table('palletsaccounts')->sum('realNumberPallets');
            if (request()->has('sortby') && request()->has('order')) {
                $sortby = $request->get('sortby'); // Order by what column?
                $order = $request->get('order'); // Order direction: asc or desc
                $listPalletsaccounts = DB::table('palletsaccounts')->orderBy($sortby, $order)->get();
            } else {
                $listPalletsaccounts = DB::table('palletsaccounts')->get();
            }
            return view('palletsaccounts.allPalletsaccounts', compact('listPalletsaccounts', 'totalpallets', 'sortby', 'order'));
        } else {
            return view('auth.login');
        }
    }

    /** show the add form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAdd()
    {
        if (Auth::check()) {
            $listWarehouses=DB::table('warehouses')->get();
            return view('palletsaccounts.addPalletsaccount', compact('listWarehouses'));
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
        $type = Input::get('type');
        $realNumberPallets = Input::get('realNumberPallets');
        $theoricalNumberPallets=$realNumberPallets;
        $warehousesAssociatedName=Input::get('warehousesAssociated');
        if(isset($warehousesAssociatedName)){
            foreach ($warehousesAssociatedName as $nameWarehouse) {
                $idwarehouses[] = Warehouse::where('name', $nameWarehouse)->value('id');
            }
        }

        //validation
        $rules = array(
            'name' => 'required|string|max:255|unique:palletsaccounts',
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            if(isset($warehousesAssociatedName)) {
                Palletsaccount::create(
                    ['name' => $name, 'realNumberPallets' => $realNumberPallets, 'theoricalNumberPallets' => $theoricalNumberPallets, 'type' => $type]
                )->warehouses()->sync($idwarehouses);
            }else{
                Palletsaccount::create(
                    ['name' => $name, 'realNumberPallets' => $realNumberPallets, 'theoricalNumberPallets' => $theoricalNumberPallets, 'type' => $type]
                );

            }

            session()->flash('messageAddPalletsaccount', 'Successfully added new pallets account');
            return redirect('/allPalletsaccounts');
        }
    }

    /**
     * show a specific pallets account
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showDetails($id)
    {
        if (Auth::check()) {
            //general data
            $palletsaccount = DB::table('palletsaccounts')->where('id', '=', $id)->first();

            $totalpallets = DB::table('palletsaccounts')->sum('realNumberPallets');
            $listWarehouses=DB::table('warehouses')->get();

            $name = $palletsaccount->name;
            $realNumberPallets = $palletsaccount->realNumberPallets;
            $theoricalNumberPallets=$palletsaccount->theoricalNumberPallets;
            $warehousesAssociated = DB::table('palletsaccount_warehouse')->where('palletsaccount_id', $id)->get();
            foreach ($warehousesAssociated as $warehouse) {
                $namewarehouses[] = Warehouse::where('id', $warehouse->warehouse_id)->value('name');
            }

            //table data
            $currentDate = Carbon::now();
            $limitDate=$currentDate->subDays(60)->format('Y-m-d');

            if (request()->has('sortby') && request()->has('order')) {
                $sortby = request()->get('sortby'); // Order by what column?
                $order = request()->get('order'); // Order direction: asc or desc
                $listPalletstransfers=DB::table('palletstransfers')->where([['palletsaccount_name', $name],['date', '>=', $limitDate]])->orderBy($sortby, $order)->paginate(10);
                $links = $listPalletstransfers->appends(['sortby' => $sortby, 'order' => $order])->render();
            } else {
                $listPalletstransfers=DB::table('palletstransfers')->where([['palletsaccount_name', $name],['date', '>=', $limitDate]])->paginate(10);
                $links = '';
            }
            $count = count(DB::table('palletstransfers')->where([['palletsaccount_name', $name],['date', '>=', $limitDate]])->get());

            return view('palletsaccounts.detailsPalletsaccount', compact('listPalletstransfers','totalpallets','listWarehouses', 'id', 'name', 'realNumberPallets', 'theoricalNumberPallets','namewarehouses', 'count', 'links'));
        } else {
            return view('auth.login');
        }
    }

    /**
     * update the pallets account n° ID
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {

        $rules = array(
            'name' => 'required|string|max:255|unique:palletsaccounts,name,'.$id,
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $name = Input::get('name');
            Palletsaccount::where('id', $id)->update(['name' => $name]);

            $warehousesAssociatedName=Input::get('namewarehouses');
            foreach ($warehousesAssociatedName as $warehouseAName) {
                $idwarehouses[] = Warehouse::where('name', $warehouseAName)->value('id');
            }
            Palletsaccount::where('id', $id)->first()->warehouses()->sync($idwarehouses);

//            foreach($warehousesAssociatedName as $warehouseAName){
//                Warehouse::where('name',$warehouseAName)->update(['palletsaccount_name'=>$name]);
//            }

            session()->flash('messageUpdatePalletsaccount', 'Successfully updated pallets account');
            return redirect()->back();
        }
    }

    /** delete a specific pallets account
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete($id)
    {
        DB::table('palletsaccounts')->where('id', $id)->delete();
        // redirect
        session()->flash('messageDeletePalletsaccount', 'Successfully deleted the pallets account!');
        return redirect('/allPalletsaccounts');
    }

}
