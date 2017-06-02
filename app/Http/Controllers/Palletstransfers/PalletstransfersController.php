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
     * Display the content - only the last 2 months
     *
     * @return \Illuminate\Http\Response
     */
    public function showAll(Request $request)
    {

//        dd(Palletstransfer::all()->loading);

        if (Auth::check()) {
            $totalpallets = DB::table('palletstransfers')->sum('palletsNumber');
            $currentDate = Carbon::now();
            $limitDate = $currentDate->subDays(60)->format('Y-m-d');

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
                $links = '';
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
            $listPalletsaccounts = DB::table('palletsaccounts')->get();

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
        $loading_atrnr = Input::get('loading_atrnr');
        $palletsAccount = Input::get('palletsAccount');
        $palletsNumber = Input::get('palletsNumber');

        $rules = array(
            'loading_atrnr' => 'required|string|max:255',
            'date' => 'required|date',
        );
        $validator = Validator::make(Input::all(), $rules);
        // process the login
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            Palletstransfer::create(['date' => $date, 'loading_atrnr' => $loading_atrnr, 'palletsAccount' => $palletsAccount, 'palletsNumber' => $palletsNumber]);
            $actualPalletsNumber = DB::table('palletsaccounts')->where('name', $palletsAccount)->value('theoricalNumberPallets');
            Palletsaccount::where('name',$palletsAccount)->update(['theoricalNumberPallets'=> $actualPalletsNumber+$palletsNumber]);

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
            $listPalletsaccounts = DB::table('palletsaccounts')->get();

            $palletsTransfer = DB::table('palletstransfers')->where('id', $id)->first();
            $date = $palletsTransfer->date;
            $loading_atrnr = $palletsTransfer->loading_atrnr;
            $palletsNumber = $palletsTransfer->palletsNumber;
            $palletsAccount = $palletsTransfer->palletsAccount;
            $state = $palletsTransfer->state;
            $realPalletsNumber = $palletsTransfer->realPalletsNumber;
            $documents = $palletsTransfer->documents;
            $dateLastReminder = $palletsTransfer->dateLastReminder;
            $remindersNumber = $palletsTransfer->remindersNumber;
            $reminderWarehouse = $palletsTransfer->reminderWarehouse;
//            $listWarehouses=DB::table('palletsaccounts')->where('name', $palletsAccount)->value('');;
            $listWarehouses = ['warehouse1', 'warehouse2', 'warehouse44'];

            $currentDate = Carbon::now();
            if(isset($remindersNumber) && $remindersNumber>=3){
                $limitDate=$currentDate->addDays(45)->format('d-m-Y');
                session()->flash('messageBlockedAccount', 'BE CAREFUL ! The account '.$palletsAccount.' will be blocked until '.$limitDate.' (45 days)');
            }

            return view('palletstransfers.detailsPalletstransfer', compact('listPalletsaccounts', 'date', 'loading_atrnr', 'id', 'palletsNumber', 'palletsAccount', 'state', 'realPalletsNumber', 'documents', 'dateLastReminder', 'remindersNumber', 'reminderWarehouse', 'listWarehouses'));
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
            'loading_atrnr' => 'required|string|max:255',
            'date' => 'required|date',
        );
        $validator = Validator::make(Input::all(), $rules);
        // process the login
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $date = Input::get('date');
            $loading_atrnr = Input::get('loading_atrnr');
            $palletsNumber = Input::get('palletsNumber');
            $palletsAccount = Input::get('palletsAccount');

            $actualPalletsNumber = DB::table('palletsaccounts')->where('name', $palletsAccount)->value('theoricalNumberPallets');
            Palletsaccount::where('name',$palletsAccount)->update(['theoricalNumberPallets'=> $actualPalletsNumber+$palletsNumber]);

            Palletstransfer::where('id', $id)->update(['date' => $date]);
            Palletstransfer::where('id', $id)->update(['loading_atrnr' => $loading_atrnr]);
            Palletstransfer::where('id', $id)->update(['palletsNumber' => $palletsNumber]);
            Palletstransfer::where('id', $id)->update(['palletsAccount' => $palletsAccount]);

            session()->flash('messageUpdatePalletstransfer', 'Successfully updated pallets transfer');
            return redirect()->back();
        }
    }

    /**
     * delete the transfer from the database
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete($id)
    {
        DB::table('palletstransfers')->where('id', $id)->delete();

        // redirect
        session()->flash('messageDeletePalletstransfer', 'Successfully deleted the pallets transfer!');
        return redirect('/allPalletstransfers');
    }

    /**
     * Verificate the transfer and send reminders if necessary
     * @param $id
     * @param $palletsAccount
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveVerification($id, $palletsAccount)
    {
        $realPalletsNumber = Input::get('realPalletsNumber');
        $remindersNumber=Input::get('remindersNumber');
        if (Input::get('documents') == 'false') {
            $documents = false;
        } else {
            $documents = true;
        }
        if (Input::get('state') == 'false') {
            $state = false;
        } else {
            $state = true;
        }

        Palletstransfer::where('id', $id)->update(['realPalletsNumber' => $realPalletsNumber]);
        Palletstransfer::where('id', $id)->update(['documents' => $documents]);
        Palletstransfer::where('id', $id)->update(['state' => $state]);

        $reminderWarehouse = Input::get('reminderWarehouse');

        //Send reminder only if documents are missing or transfer not validated
        if($documents==false || $state==false){
            if (isset($reminderWarehouse)) {
                $reminder = DB::table('warehouses')->where('name', $reminderWarehouse)->first();
                $reminderEmail = $reminder->email;
                $reminderPhone = $reminder->phone;
                if (isset($reminderEmail)) {
                    $currentDate = Carbon::now();
                    $actualRemindersNumber = DB::table('palletstransfers')->where('id', $id)->value('remindersNumber');
                    Palletstransfer::where('id', $id)->update(['dateLastReminder' => $currentDate]);
                    Palletstransfer::where('id', $id)->update(['remindersNumber' => $actualRemindersNumber + 1]);
                    Palletstransfer::where('id', $id)->update(['reminderWarehouse' => $reminderWarehouse]);
                    session()->flash('messageSuccessEmail', 'An email has been sent to remind the warehouse about the pallets');
                } elseif (isset($reminderPhone)) {
                    $currentDate = Carbon::now();
                    Palletstransfer::where('id', $id)->update(['dateLastReminder' => $currentDate]);
                    $actualRemindersNumber = DB::table('palletstransfers')->where('id', $id)->value('remindersNumber');
                    Palletstransfer::where('id', $id)->update(['remindersNumber' => $actualRemindersNumber + 1]);
                    Palletstransfer::where('id', $id)->update(['reminderWarehouse' => $reminderWarehouse]);
                    $reminderNameContact = $reminder->namecontact;
                    if (isset($reminderNameContact)) {
                        session()->flash('messageSuccessPhone', 'No email for this warehouse. Please call ' . $reminderPhone . 'and ask for ' . $reminderNameContact);
                    } else {
                        session()->flash('messageSuccessPhone', 'No email for this warehouse. Please call ' . $reminderPhone);
                    }
                } else {
                    session()->flash('messageErrorEmailPhone', 'No email, no phone for this warehouse. Please try an other warehouse');
                }
            }else{
                Palletstransfer::where('id', $id)->update(['reminderWarehouse' => $reminderWarehouse]);
            }
        }

        //Transfer validated
        elseif ($state == true && $documents==true) {
            $actualPalletsNumber = DB::table('palletsaccounts')->where('name', $palletsAccount)->value('realNumberPallets');
            DB::table('palletsaccounts')->where('name', $palletsAccount)->update(['realNumberPallets' => $actualPalletsNumber + $realPalletsNumber]);

            $theoricalPalletNumber=DB::table('palletstransfers')->where('id',$id)->value('palletsNumber');
            if($realPalletsNumber==$theoricalPalletNumber){
                session()->flash('messageValidatedPalletstransfer', 'Transfer validated !');
            }
        }

// redirect
        session()->flash('messageSaveVerificationPalletstransfer', 'Successfully saved the pallets transfer verification !');
        return redirect()->back();
    }
}

