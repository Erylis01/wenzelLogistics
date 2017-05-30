<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class PalletstransfersController extends Controller
{
    /**
     * Display the content.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAll(Request $request)
    {
        if (Auth::check()) {
            $totalpallets = DB::table('palletstransfers')->sum('palletsNumber');
            $currentDate = Carbon::now();
            $limitDate=$currentDate->subDays(60)->format('Y-m-d');

            if (request()->has('sortby') && request()->has('order')) {
                $sortby = $request->get('sortby'); // Order by what column?
                $order = $request->get('order'); // Order direction: asc or desc
                $listPalletstransfers = DB::table('palletstransfers')->where([
                    ['date', '>=', $limitDate],
                ])->orderBy($sortby, $order)->paginate(10);
                $links = $listPalletstransfers->appends(['sortby' => $sortby, 'order' => $order])->render();
            } else {
                $listPalletstransfers = DB::table('palletstransfers')->where([
                    ['date', '>=', $limitDate],
                ])->paginate(10);
                $links='';
            }
            $count = count(DB::table('palletstransfers')->where([
                ['date', '>=', $limitDate],
            ])->get());
            return view('palletstransfers.allPalletstransfers', compact('listPalletstransfers', 'totalpallets', 'sortby', 'order', 'links', 'count'));
        } else {
            return view('auth.login');
        }
    }

    /**
     * show the add form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAdd()
    {
        if (Auth::check()) {
            $listPalletsaccounts=DB::table('palletsaccounts')->get();

            return view('palletstransfers.addPalletstransfer', compact('listPalletsaccounts'));
        } else {
            return view('auth.login');
        }
    }

    /**
     * add a new pallets transfer to the list
     */
    public function add(Request $request)
    {

        $date = Input::get('date');
        $loadingRef = Input::get('loadingRef');
        $palletsAccount=Input::get('palletsAccount');
        $palletsNumber=Input::get('palletsNumber');

        $rules = array(
            'loadingRef' => 'required|string|max:255',
            'date'=>'required|date',
            'palletsAccount'=>'required|string',
            'palletsNumber'=>'required|integer',
        );
        $validator = Validator::make(Input::all(), $rules);
        // process the login
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            DB::table('palletstransfers')->insertGetId(
                ['date' => $date, 'loadingRef' => $loadingRef,'palletsAccount' =>$palletsAccount, 'palletsNumber'=>$palletsNumber]
            );

            //valable que si state=OK
//            $actualPalletsNumber=DB::table('palletsaccounts')->where('name',$palletsAccount)->value('numberPallets');
//            DB::table('palletsaccounts')->where('name',$palletsAccount)->update(['numberPallets' => $actualPalletsNumber+$palletsNumber]);

            session()->flash('messageAddPalletstransfer', 'Successfully added new pallets transfer');
            return redirect('/allPalletstransfers');
        }
    }
}
