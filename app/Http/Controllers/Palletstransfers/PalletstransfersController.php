<?php

namespace App\Http\Controllers;

use App\Palletsaccount;
use App\Palletstransfer;
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
        );
        $validator = Validator::make(Input::all(), $rules);
        // process the login
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            Palletstransfer::create(['date' => $date, 'loadingRef' => $loadingRef,'palletsAccount' =>$palletsAccount, 'palletsNumber'=>$palletsNumber]);

            session()->flash('messageAddPalletstransfer', 'Successfully added new pallets transfer');
            return redirect('/allPalletstransfers');
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showDetails($id)
    {
        if (Auth::check()) {
            $listPalletsaccounts=DB::table('palletsaccounts')->get();

            $palletsTransfer=DB::table('palletstransfers')->where('id', $id)->first();
            $date = $palletsTransfer->date;
            $loadingRef=$palletsTransfer->loadingRef;
            $palletsNumber = $palletsTransfer->palletsNumber;
            $palletsAccount = $palletsTransfer->palletsAccount;
            $state=$palletsTransfer->state;
            $realPalletsNumber=$palletsTransfer->realPalletsNumber;
            $documents=$palletsTransfer->documents;
            $dateLastReminder=$palletsTransfer->dateLastReminder;
            $remindersNumber=$palletsTransfer->remindersNumber;
            $reminderEmail=$palletsTransfer->reminderEmail;
            $listEmails=['achanger1', 'achanger2', 'achanger3'];

            return view('palletstransfers.detailsPalletstransfer', compact('listPalletsaccounts','date','loadingRef', 'id', 'palletsNumber', 'palletsAccount', 'state', 'realPalletsNumber', 'documents', 'dateLastReminder','remindersNumber', 'reminderEmail', 'listEmails' ));
        } else {
            return view('auth.login');
        }
    }

    /**
     * update the pallets transfer n° ID
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $rules = array(
            'loadingRef' => 'required|string|max:255',
            'date'=>'required|date',
        );
        $validator = Validator::make(Input::all(), $rules);
        // process the login
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $date = Input::get('date');
            $loadingRef=Input::get('loadingRef');
            $palletsNumber = Input::get('palletsNumber');
            $palletsAccount=Input::get('palletsAccount');

            Palletstransfer::where('id', $id)->update(['date' => $date]);
            Palletstransfer::where('id', $id)->update(['loadingRef' => $loadingRef]);
            Palletstransfer::where('id', $id)->update(['palletsNumber' => $palletsNumber]);
            Palletstransfer::where('id', $id)->update(['palletsAccount' => $palletsAccount]);

            session()->flash('messageUpdatePalletstransfer', 'Successfully updated pallets transfer');
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        DB::table('palletstransfers')->where('id', $id)->delete();

        // redirect
        session()->flash('messageDeletePalletstransfer', 'Successfully deleted the pallets transfer!');
        return redirect('/allPalletstransfers');
    }

    public function saveVerification($id, $palletsAccount)
    {

            $realPalletsNumber = Input::get('realPalletsNumber');
            $documents=Input::get('documents');
            $state = Input::get('state');
            $dateLastReminder=Input::get('dateLastReminder');
            $remindersNumber = Input::get('remindersNumber');
            $reminderEmail=Input::get('reminderEmail');

            Palletstransfer::where('id', $id)->update(['realPalletsNumber' => $realPalletsNumber]);
            Palletstransfer::where('id', $id)->update(['documents' => $documents]);
            Palletstransfer::where('id', $id)->update(['state' => $state]);
            Palletstransfer::where('id', $id)->update(['dateLastReminder' => $dateLastReminder]);
            Palletstransfer::where('id', $id)->update(['remindersNumber' => $remindersNumber]);
            Palletstransfer::where('id', $id)->update(['reminderEmail' => $reminderEmail]);

if($state==true){
    $actualPalletsNumber=DB::table('palletsaccounts')->where('name',$palletsAccount)->value('numberPallets');
    DB::table('palletsaccounts')->where('name',$palletsAccount)->update(['numberPallets' => $actualPalletsNumber+$realPalletsNumber]);
}

// redirect
            session()->flash('messageSaveVerificationPalletstransfer', 'Successfully saved the pallets transfer verification !');
            return redirect()->back();
        }
}
