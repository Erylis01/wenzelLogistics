<?php

namespace App\Http\Controllers;

use App\Document;
use App\Palletsaccount;
use App\Palletstransfer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
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

        if (Auth::check()) {
            $currentDate = Carbon::now();
            $limitDate = $currentDate->subDays(60)->format('Y-m-d');
//            $totalpallets = DB::table('palletstransfers')->where([
//                ['date', '>=', $limitDate],
//            ])->sum('palletsNumber');
//            $realTotalpallets = DB::table('palletstransfers')->where([
//                ['date', '>=', $limitDate],
//            ])->sum('realPalletsNumber');

            if (request()->has('sortby') && request()->has('order')) {
                $sortby = $request->get('sortby'); // Order by what column?
                $order = $request->get('order'); // Order direction: asc or desc
                $listPalletstransfers = Palletstransfer::where([
                    ['date', '>=', $limitDate],
                ])->orderBy($sortby, $order)->paginate(10);
                $links = $listPalletstransfers->appends(['sortby' => $sortby, 'order' => $order])->render();
            } else {
                $listPalletstransfers = Palletstransfer::where([
                    ['date', '>=', $limitDate],
                ])->paginate(10);
                $links = '';
            }
            $count = count(Palletstransfer::where([
                ['date', '>=', $limitDate],
            ])->get());
            return view('palletstransfers.allPalletstransfers', compact('listPalletstransfers', 'sortby', 'order', 'links', 'count'));
        } else {
            return view('auth.login');
        }
    }

    /**
     * show the add form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAdd(Request $request)
    {
        if (Auth::check()) {
            $listPalletsaccounts = DB::table('palletsaccounts')->get();
            $listTypes = ['Other', 'Purchase'];
            $account = $request->get('addTransferAccount');
            $creditAccount = $account;
            $debitAccount = $account;
            $date = Carbon::now()->format('Y-m-d');

            return view('palletstransfers.addPalletstransfer', compact('listPalletsaccounts', 'date', 'creditAccount', 'debitAccount', 'listTypes'));
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
        $type = Input::get('type');
        $creditAccount = Input::get('creditAccount');
        $debitAccount = Input::get('debitAccount');
        $palletsNumber = Input::get('palletsNumber');


        $rules = array(
            'creditAccount' => 'required',
            'debitAccount' => 'required',
            'palletsNumber' => 'required',
            'date' => 'required|date',
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            if (isset($type)) {
                Palletstransfer::create(['date' => $date, 'type' => $type, 'creditAccount' => $creditAccount, 'debitAccount' => $debitAccount, 'palletsNumber' => $palletsNumber]);
            } else {
                Palletstransfer::create(['date' => $date, 'creditAccount' => $creditAccount, 'debitAccount' => $debitAccount, 'palletsNumber' => $palletsNumber]);
            }
            $actualTheoricalCreditPalletsNumber = Palletsaccount::where('name', $creditAccount)->value('theoricalNumberPallets');
            $actualTheoricalDebitPalletsNumber = Palletsaccount::where('name', $debitAccount)->value('theoricalNumberPallets');
            Palletsaccount::where('name', $creditAccount)->update(['theoricalNumberPallets' => $actualTheoricalCreditPalletsNumber + $palletsNumber]);
            Palletsaccount::where('name', $debitAccount)->update(['theoricalNumberPallets' => $actualTheoricalDebitPalletsNumber - $palletsNumber]);

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
            $type = $palletsTransfer->type;
            $palletsNumber = $palletsTransfer->palletsNumber;
            $creditAccount = $palletsTransfer->creditAccount;
            $debitAccount = $palletsTransfer->debitAccount;
            $state = $palletsTransfer->state;
            $validate = $palletsTransfer->validate;
            $listTypes = ['Other', 'Purchase'];
            $files = DB::table('document_palletstransfer')->where('palletstransfer_id', $id)->get();
            if (!$files->isEmpty()) {
                foreach ($files as $f) {
                    $filesNames[] = Document::where('id', $f->document_id)->first()->name;
                }
            }

            return view('palletstransfers.detailsPalletstransfer', compact('listPalletsaccounts', 'date', 'type', 'id', 'palletsNumber', 'creditAccount', 'debitAccount', 'state', 'filesNames', 'validate', 'listTypes'));
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
        $upload = Input::get('upload');
        $update = Input::get('update');

        $deleteDocument=Input::get('deleteDocument');
        $documents = $request->file('documentsTransfer');

        $date = Input::get('date');
        $type = Input::get('type');
        $creditAccount = Input::get('creditAccount');
        $debitAccount = Input::get('debitAccount');
        $palletsNumber = Input::get('palletsNumber');
        $validate=Input::get('validate');
        $state=Palletstransfer::where('id', $id)->first()->state;

        if (isset($upload)) {
            $actualDocuments_Palletstransfers = DB::table('document_palletstransfer')->where('palletstransfer_id', $id)->get();
            $actualDocuments = [];
            if (!$actualDocuments_Palletstransfers->isEmpty()) {
                foreach ($actualDocuments_Palletstransfers as $actualDoc) {
                    $actualDocuments[] = Document::where('id', $actualDoc->document_id)->first();
                }
            }
            $this->upload($documents, $id, $actualDocuments, $validate);

        } elseif (isset($update)) {

            $actualDocuments_Palletstransfers = DB::table('document_palletstransfer')->where('palletstransfer_id', $id)->get();
            $actualDocuments = [];
            if (!$actualDocuments_Palletstransfers->isEmpty()) {
                foreach ($actualDocuments_Palletstransfers as $actualDoc) {
                    $actualDocuments[] = Document::where('id', $actualDoc->document_id)->first();
                }
            }
            $this->updateInfo($id, $date, $type, $palletsNumber, $creditAccount, $debitAccount, $validate, $state, $documents, $actualDocuments);
        }elseif(isset($deleteDocument)){
            $this->deleteDocument($id, $deleteDocument, $documents, $validate);
        }
        return redirect()->back();
    }



//            if($palletsaccount_name<>$actualPalletsaccount_name){
//                //transfer data
//                $actualPalletsNumberTransfer=DB::table('palletstransfers')->where('id', $id)->value('palletsNumber');
//                $actualRealPalletsNumberTransfer=DB::table('palletstransfers')->where('id', $id)->value('realPalletsNumber');
//                //account data
//                $actualPalletsNumber = DB::table('palletsaccounts')->where('name', $actualPalletsaccount_name)->value('theoricalNumberPallets');
//                $actualRealPalletsNumber = DB::table('palletsaccounts')->where('name', $actualPalletsaccount_name)->value('realNumberPallets');
//                Palletsaccount::where('name',$actualPalletsaccount_name)->update(['theoricalNumberPallets'=> $actualPalletsNumber-$actualPalletsNumberTransfer]);
//                Palletsaccount::where('name',$actualPalletsaccount_name)->update(['realNumberPallets'=> $actualRealPalletsNumber-$actualRealPalletsNumberTransfer]);
//
//                Palletstransfer::where('id', $id)->update(['palletsaccount_name' => $palletsaccount_name]);
//                $actualPalletsNumberNewAccount = DB::table('palletsaccounts')->where('name', $palletsaccount_name)->value('theoricalNumberPallets');
//                $actualRealPalletsNumberNewAccount = DB::table('palletsaccounts')->where('name', $palletsaccount_name)->value('realNumberPallets');
//                Palletsaccount::where('name',$palletsaccount_name)->update(['theoricalNumberPallets'=> $actualPalletsNumberNewAccount+$palletsNumber]);
//                Palletsaccount::where('name',$palletsaccount_name)->update(['realNumberPallets'=> $actualRealPalletsNumberNewAccount+$actualRealPalletsNumberTransfer]);
//            }else{
//                $actualPalletsNumber = DB::table('palletsaccounts')->where('name', $palletsaccount_name)->value('theoricalNumberPallets');
//                Palletsaccount::where('name',$palletsaccount_name)->update(['theoricalNumberPallets'=> $actualPalletsNumber+$palletsNumber]);
//            }


    /**
     * delete the transfer from the database
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete($id)
    {
        $actualPalletsNumber = Palletstransfer::where('id', $id)->first()->palletsNumber;
        $actualCreditAccount = Palletstransfer::where('id', $id)->first()->creditAccount;
        $actualDebitAccount = Palletstransfer::where('id', $id)->first()->debitAccount;
        $actualPalletsNumberCreditAccount = Palletsaccount::where('name', $actualCreditAccount)->first()->theoricalNumberPallets;
        Palletsaccount::where('name', $actualCreditAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberCreditAccount - $actualPalletsNumber]);
        $actualPalletsNumberDebitAccount = Palletsaccount::where('name', $actualDebitAccount)->first()->theoricalNumberPallets;
        Palletsaccount::where('name', $actualDebitAccount)->update(['theoricalNumberPallets'=> $actualPalletsNumberDebitAccount + $actualPalletsNumber]);

        $actualDocuments_Palletstransfers = DB::table('document_palletstransfer')->where('palletstransfer_id', $id)->get();
        $actualDocuments = [];
        if (!$actualDocuments_Palletstransfers->isEmpty()) {
            foreach ($actualDocuments_Palletstransfers as $actualDoc) {
                $actualDocuments[] = Document::where('id', $actualDoc->document_id)->first();
            }
            foreach($actualDocuments as $actDoc){
                $doc = Document::where('name', $actDoc->name)->first();
                $doc->palletstransfers()->detach($id);
                $path = '/proofsPallets/documentsTransfer/';
                Storage::delete($path . $actDoc->name);
                $doc->delete();
            }
        }

        Palletstransfer::where('id', $id)->delete();
        // redirect
        session()->flash('messageDeletePalletstransfer', 'Successfully deleted the pallets transfer!');
        return redirect('/allPalletstransfers');
    }

    /**
     * upload a document on the website
     * @param $documents
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function upload($documents, $id, $actualDoc, $validate)
    {
        if (isset($documents)) {
            foreach ($documents as $doc) {
                $filename = $doc->getClientOriginalName();
                $extension = $doc->getClientOriginalExtension();
                $size = $doc->getSize();
                //if file is an image, a pdf or an email
                if (($extension == 'png' || $extension == 'jpg' || $extension == 'msg' || $extension == 'htm' || $extension == 'rtf' || $extension == 'pdf') && $size < 2000000) {
                    Storage::putFileAs('/proofsPallets/documentsTransfer', $doc, $filename);
                    Document::firstOrCreate([
                        'name' => $filename,
                        'type' => 'Transfer'
                    ])->palletstransfers()->attach($id);
                    session()->flash('messageSuccessUpload', 'Successfully uploaded the files');
                } else {
                    session()->flash('messageErrorUpload', 'Error ! The file type is not supported (png, jgp, pdf, msg, htm, rtf only');
                }
            }
        }
        if((isset($documents)||!empty($actualDoc))&& $validate==true){
            $state='Complete Validated';
        }elseif((isset($documents)||!empty($actualDoc))&& $validate<>true){
            $state='Complete';
        }elseif(!isset($documents)&&empty($actualDoc)){
            $state='Waiting documents';
        }
        Palletstransfer::where('id', $id)->update(['state' => $state]);
    }

    public function deleteDocument($id, $name, $documents, $validate)
    {
        $doc = Document::where('name', $name)->first();
        $doc->palletstransfers()->detach($id);
        $path = '/proofsPallets/documentsTransfer/';
        Storage::delete($path . $name);
        $doc->delete();

        $actualDocuments_Palletstransfers = DB::table('document_palletstransfer')->where('palletstransfer_id', $id)->get();
        $actualDocuments = [];
        if (!$actualDocuments_Palletstransfers->isEmpty()) {
            foreach ($actualDocuments_Palletstransfers as $actualDoc) {
                $actualDocuments[] = Document::where('id', $actualDoc->document_id)->first();
            }
        }
        if((isset($documents)||!empty($actualDoc))&& $validate==true){
            $state='Complete Validated';
        }elseif((isset($documents)||!empty($actualDoc))&& $validate<>true){
            $state='Complete';
        }elseif(!isset($documents)&&empty($actualDoc)){
            $state='Waiting documents';
        }
        Palletstransfer::where('id', $id)->update(['state' => $state]);
        // redirect
        session()->flash('messageSuccessDeleteDocument', 'Successfully deleted the document!');
    }

    public function updateInfo($id, $date, $type, $palletsNumber, $creditAccount, $debitAccount, $validate, $state, $documents, $actualDoc)
    {
        $rules = array(
            'creditAccount' => 'required',
            'debitAccount' => 'required',
            'palletsNumber' => 'required',
            'date' => 'required|date',
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            //inverse transfer : we delete the last transfer
            $actualPalletsNumber = Palletstransfer::where('id', $id)->first()->palletsNumber;
            $actualCreditAccount = Palletstransfer::where('id', $id)->first()->creditAccount;
            $actualDebitAccount = Palletstransfer::where('id', $id)->first()->debitAccount;
            $actualPalletsNumberCreditAccount = Palletsaccount::where('name', $actualCreditAccount)->first()->theoricalNumberPallets;
            Palletsaccount::where('name', $actualCreditAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberCreditAccount - $actualPalletsNumber]);
            $actualPalletsNumberDebitAccount = Palletsaccount::where('name', $actualDebitAccount)->first()->theoricalNumberPallets;
            Palletsaccount::where('name', $actualDebitAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberDebitAccount + $actualPalletsNumber]);

            //we do the new transfer
            $palletsNumberCreditAccount = Palletsaccount::where('name', $creditAccount)->first()->theoricalNumberPallets;
            Palletsaccount::where('name', $creditAccount)->update(['theoricalNumberPallets' => $palletsNumberCreditAccount - $palletsNumber]);
            $palletsNumberDebitAccount = Palletsaccount::where('name', $debitAccount)->first()->theoricalNumberPallets;
            Palletsaccount::where('name', $debitAccount)->update(['theoricalNumberPallets' => $palletsNumberDebitAccount + $palletsNumber]);

            Palletstransfer::where('id', $id)->update(['date' => $date]);
            Palletstransfer::where('id', $id)->update(['type' => $type]);
            Palletstransfer::where('id', $id)->update(['palletsNumber' => $palletsNumber]);
            Palletstransfer::where('id', $id)->update(['creditAccount' => $creditAccount]);
            Palletstransfer::where('id', $id)->update(['debitAccount' => $debitAccount]);

            if ($validate == 'true') {
                $validate = true;
                Palletstransfer::where('id', $id)->update(['validate' => $validate]);
            }

            if((isset($documents)||!empty($actualDoc))&& $validate==true){
                $state='Complete Validated';
            }elseif((isset($documents)||!empty($actualDoc))&& $validate<>true){
                $state='Complete';
            }elseif(!isset($documents)&&empty($actualDoc)){
                $state='Waiting documents';
            }
            Palletstransfer::where('id', $id)->update(['state' => $state]);
        }
        session()->flash('messageUpdatePalletstransfer', 'Successfully updated pallets transfer');
    }
}

