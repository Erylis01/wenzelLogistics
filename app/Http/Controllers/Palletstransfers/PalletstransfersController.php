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
        $listPalletsaccounts = DB::table('palletsaccounts')->get();
        $listTypes = ['Other', 'Purchase'];
        $creditAccount = Input::get('creditAccount');
        $debitAccount = Input::get('debitAccount');
        $palletsNumber = Input::get('palletsNumber');
        $addPalletstransfer = Input::get('addPalletstransfer');
        $okSubmitAddModal = Input::get('okSubmitAddModal');
        $closeSubmitAddModal = Input::get('closeSubmitAddModal');

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

            $actualTheoricalCreditPalletsNumber = Palletsaccount::where('name', $creditAccount)->value('theoricalNumberPallets');
            $actualTheoricalDebitPalletsNumber = Palletsaccount::where('name', $debitAccount)->value('theoricalNumberPallets');

            if (isset($addPalletstransfer)) {
                session()->flash('palletsNumber', $palletsNumber);
                session()->flash('creditAccount', $creditAccount);
                session()->flash('debitAccount', $debitAccount);
                session()->flash('palletsNumberCreditAccount', $actualTheoricalCreditPalletsNumber);
                session()->flash('palletsNumberDebitAccount', $actualTheoricalDebitPalletsNumber);
                return view('palletstransfers.addPalletstransfer', compact('date', 'type', 'creditAccount', 'debitAccount', 'palletsNumber', 'addPalletstransfer', 'listPalletsaccounts', 'listTypes'));
            } elseif (isset($okSubmitAddModal)) {
                if (isset($type)) {
                    Palletstransfer::create(['date' => $date, 'type' => $type, 'creditAccount' => $creditAccount, 'debitAccount' => $debitAccount, 'palletsNumber' => $palletsNumber]);
                } else {
                    Palletstransfer::create(['date' => $date, 'creditAccount' => $creditAccount, 'debitAccount' => $debitAccount, 'palletsNumber' => $palletsNumber]);
                }
                Palletsaccount::where('name', $creditAccount)->update(['theoricalNumberPallets' => $actualTheoricalCreditPalletsNumber + $palletsNumber]);
                Palletsaccount::where('name', $debitAccount)->update(['theoricalNumberPallets' => $actualTheoricalDebitPalletsNumber - $palletsNumber]);
                session()->flash('messageAddPalletstransfer', 'Successfully added new pallets transfer');
                return redirect('/allPalletstransfers');
            } elseif (isset($closeSubmitAddModal)) {
                return view('palletstransfers.addPalletstransfer', compact('date', 'type', 'creditAccount', 'debitAccount', 'palletsNumber', 'listPalletsaccounts', 'listTypes'));
            }

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
            $validateM = $palletsTransfer->validate;

            $listTypes = ['Other', 'Purchase'];
            $files = DB::table('document_palletstransfer')->where('palletstransfer_id', $id)->get();
            if (!$files->isEmpty()) {
                foreach ($files as $f) {
                    $filesNames[] = Document::where('id', $f->document_id)->first()->name;
                }
            }

            return view('palletstransfers.detailsPalletstransfer', compact('listPalletsaccounts', 'date', 'type', 'id', 'palletsNumber', 'creditAccount', 'debitAccount', 'state', 'filesNames', 'validateM', 'listTypes'));
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
       $transfer= Palletstransfer::where('id', $id)->first();
        $upload = Input::get('upload');
        $update = Input::get('update');

        $deleteDocument = Input::get('deleteDocument');
        $documents = $request->file('documentsTransfer');

        $date = Input::get('date');
        $type = Input::get('type');
        $creditAccount = Input::get('creditAccount');
        $debitAccount = Input::get('debitAccount');
        $palletsNumber = Input::get('palletsNumber');
        $validate = Input::get('validate');
        if($validate==null){
            $validateM=$transfer->validate;
        }
        $state = $transfer->state;

        $okSubmitUpdateModal = Input::get('okSubmitUpdateModal');
        $okSubmitUpdateValidateModal = Input::get('okSubmitUpdateValidateModal');
        $closeSubmitUpdateModal = Input::get('closeSubmitUpdateModal');
        $listPalletsaccounts = DB::table('palletsaccounts')->get();
        $listTypes = ['Other', 'Purchase'];

        if (isset($upload)) {
            $actualDocuments_Palletstransfers = DB::table('document_palletstransfer')->where('palletstransfer_id', $id)->get();
            if (!$actualDocuments_Palletstransfers->isEmpty()) {
                foreach ($actualDocuments_Palletstransfers as $actualDoc) {
                    $actualDocuments[] = Document::where('id', $actualDoc->document_id)->first();
                }
            }
            $this->upload($documents, $id);

            if ((isset($documents) || !empty($actualDoc)) && (($validate<>null && $validate == 'true')||($validate==null && $validateM==1))) {
                $state = 'Complete Validated';
            } elseif ((isset($documents) || !empty($actualDoc)) && (($validate<>null && $validate == 'false')||($validate==null && $validateM==0))) {
                $state = 'Complete';
            } elseif (!isset($documents) && empty($actualDoc)) {
                $state = 'Waiting documents';
            }
            Palletstransfer::where('id', $id)->update(['state' => $state]);
            return redirect()->back();
        } elseif (isset($update)) {
            $actualCreditAccount =$transfer->creditAccount;
            $actualDebitAccount = $transfer->debitAccount;
            $actualPalletsNumber = $transfer->palletsNumber;
            if ($state == 'Complete Validated') {
                $actualRealPalletsNumberCreditAccount = Palletsaccount::where('name', $actualCreditAccount)->first()->realNumberPallets;
                Palletsaccount::where('name', $actualCreditAccount)->update(['realNumberPallets' => $actualRealPalletsNumberCreditAccount - $actualPalletsNumber]);
                $actualRealPalletsNumberDebitAccount = Palletsaccount::where('name', $actualDebitAccount)->first()->realNumberPallets;
                Palletsaccount::where('name', $actualDebitAccount)->update(['realNumberPallets' => $actualRealPalletsNumberDebitAccount + $actualPalletsNumber]);
            }

            $actualDocuments_Palletstransfers = DB::table('document_palletstransfer')->where('palletstransfer_id', $id)->get();
            if (!$actualDocuments_Palletstransfers->isEmpty()) {
                foreach ($actualDocuments_Palletstransfers as $actualDoc) {
                    $filesNames[] = Document::where('id', $actualDoc->document_id)->first()->name;
                }
            }
            session()->flash('palletsNumber', $palletsNumber);
            session()->flash('creditAccount', $creditAccount);
            session()->flash('debitAccount', $debitAccount);
            session()->flash('actualCreditAccount', $actualCreditAccount);
            session()->flash('actualDebitAccount', $actualDebitAccount);
            session()->flash('actualPalletsNumber', $actualPalletsNumber);
            session()->flash('thPalletsNumberCreditAccount', Palletsaccount::where('name', $creditAccount)->first()->theoricalNumberPallets);
            session()->flash('thPalletsNumberDebitAccount', Palletsaccount::where('name', $debitAccount)->first()->theoricalNumberPallets);

            return view('palletstransfers.detailsPalletstransfer', compact('id', 'date', 'type', 'creditAccount', 'debitAccount', 'palletsNumber', 'update', 'listPalletsaccounts', 'listTypes', 'documents', 'filesNames', 'validate','validateM', 'state'));

        } elseif (isset($deleteDocument)) {
            $this->deleteDocument($id, $transfer, $deleteDocument, $documents, $validate, $state, $transfer->creditAccount, $transfer->debitAccount, $transfer->palletsNumber);
            return redirect()->back();
        } elseif (isset($okSubmitUpdateModal)) {
            $actualDocuments_Palletstransfers = DB::table('document_palletstransfer')->where('palletstransfer_id', $id)->get();
            $actualDocuments = [];
            if (!$actualDocuments_Palletstransfers->isEmpty()) {
                foreach ($actualDocuments_Palletstransfers as $actualDoc) {
                    $actualDocuments[] = Document::where('id', $actualDoc->document_id)->first();
                }
            }
            $this->updateInfo($id, $transfer, $date, $type, $palletsNumber, $creditAccount, $debitAccount, $validate, $state, $documents, $actualDocuments);

            $state = Palletstransfer::where('id',$id)->first()->state;

            if ($state == 'Complete Validated') {
                session()->flash('palletsNumber', $palletsNumber);
                session()->flash('creditAccount', $creditAccount);
                session()->flash('debitAccount', $debitAccount);
                session()->flash('realPalletsNumberCreditAccount', Palletsaccount::where('name', $creditAccount)->first()->realNumberPallets);
                session()->flash('realPalletsNumberDebitAccount', Palletsaccount::where('name', $debitAccount)->first()->realNumberPallets);
                return view('palletstransfers.detailsPalletstransfer', compact('id', 'date', 'type', 'creditAccount', 'debitAccount', 'palletsNumber', 'okSubmitUpdateModal', 'listPalletsaccounts', 'listTypes', 'documents', 'filesNames', 'validate','validateM', 'state'));
            } else {
                return redirect()->back();
            }
        } elseif (isset($closeSubmitUpdateModal)) {
            return redirect()->back();
        } elseif (isset($okSubmitUpdateValidateModal)) {

            $realPalletsNumberCreditAccount = Palletsaccount::where('name', $creditAccount)->first()->realNumberPallets;
            Palletsaccount::where('name', $creditAccount)->update(['realNumberPallets' => $realPalletsNumberCreditAccount + $palletsNumber]);
            $realPalletsNumberDebitAccount = Palletsaccount::where('name', $debitAccount)->first()->realNumberPallets;
            Palletsaccount::where('name', $debitAccount)->update(['realNumberPallets' => $realPalletsNumberDebitAccount - $palletsNumber]);
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
        $actualPalletsNumber = Palletstransfer::where('id', $id)->first()->palletsNumber;
        $actualCreditAccount = Palletstransfer::where('id', $id)->first()->creditAccount;
        $actualDebitAccount = Palletstransfer::where('id', $id)->first()->debitAccount;
        $actualPalletsNumberCreditAccount = Palletsaccount::where('name', $actualCreditAccount)->first()->theoricalNumberPallets;
        Palletsaccount::where('name', $actualCreditAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberCreditAccount - $actualPalletsNumber]);
        $actualPalletsNumberDebitAccount = Palletsaccount::where('name', $actualDebitAccount)->first()->theoricalNumberPallets;
        Palletsaccount::where('name', $actualDebitAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberDebitAccount + $actualPalletsNumber]);

        $state = Palletstransfer::where('id', $id)->first()->state;
        if ($state == 'Complete Validated') {
            $actualRealPalletsNumberCreditAccount = Palletsaccount::where('name', $actualCreditAccount)->first()->realNumberPallets;
            Palletsaccount::where('name', $actualCreditAccount)->update(['realNumberPallets' => $actualRealPalletsNumberCreditAccount - $actualPalletsNumber]);
            $actualRealPalletsNumberDebitAccount = Palletsaccount::where('name', $actualDebitAccount)->first()->realNumberPallets;
            Palletsaccount::where('name', $actualDebitAccount)->update(['realNumberPallets' => $actualRealPalletsNumberDebitAccount + $actualPalletsNumber]);
        }

        $actualDocuments_Palletstransfers = DB::table('document_palletstransfer')->where('palletstransfer_id', $id)->get();
        $actualDocuments = [];
        if (!$actualDocuments_Palletstransfers->isEmpty()) {
            foreach ($actualDocuments_Palletstransfers as $actualDoc) {
                $actualDocuments[] = Document::where('id', $actualDoc->document_id)->first();
            }
            foreach ($actualDocuments as $actDoc) {
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
    public function upload($documents, $id)
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
    }

    public function deleteDocument($id, $transfer, $name, $documents, $validate, $state, $actualCreditAccount, $actualDebitAccount, $actualPalletsNumber)
    {
        $doc = Document::where('name', $name)->first();
        $doc->palletstransfers()->detach($id);
        $path = '/proofsPallets/documentsTransfer/';
        Storage::delete($path . $name);
        $doc->delete();

        $actualDocuments_Palletstransfers = DB::table('document_palletstransfer')->where('palletstransfer_id', $id)->get();
        if ($actualDocuments_Palletstransfers->isEmpty()) {
            Palletstransfer::where('id', $id)->update(['validate' => false]);
            if ($state == 'Complete Validated') {
                $actualRealPalletsNumberCreditAccount = Palletsaccount::where('name', $actualCreditAccount)->first()->realNumberPallets;
                Palletsaccount::where('name', $actualCreditAccount)->update(['realNumberPallets' => $actualRealPalletsNumberCreditAccount - $actualPalletsNumber]);
                $actualRealPalletsNumberDebitAccount = Palletsaccount::where('name', $actualDebitAccount)->first()->realNumberPallets;
                Palletsaccount::where('name', $actualDebitAccount)->update(['realNumberPallets' => $actualRealPalletsNumberDebitAccount + $actualPalletsNumber]);
            }
            $state = 'Waiting documents';
            Palletstransfer::where('id', $id)->update(['state' => $state]);
//        }else{
//            foreach ($actualDocuments_Palletstransfers as $actualDoc) {
//                $actualDocuments[] = Document::where('id', $actualDoc->document_id)->first();
//            }
//
        }
        // redirect
        session()->flash('messageSuccessDeleteDocument', 'Successfully deleted the document!');
    }

    public function updateInfo($id, $transfer, $date, $type, $palletsNumber, $creditAccount, $debitAccount, $validate, $state, $documents, $actualDoc)
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
            $actualPalletsNumber = $transfer->palletsNumber;
            $actualCreditAccount = $transfer->creditAccount;
            $actualDebitAccount = $transfer->debitAccount;
            $actualPalletsNumberCreditAccount = Palletsaccount::where('name', $actualCreditAccount)->first()->theoricalNumberPallets;
            Palletsaccount::where('name', $actualCreditAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberCreditAccount - $actualPalletsNumber]);
            $actualPalletsNumberDebitAccount = Palletsaccount::where('name', $actualDebitAccount)->first()->theoricalNumberPallets;
            Palletsaccount::where('name', $actualDebitAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberDebitAccount + $actualPalletsNumber]);

            //we do the new transfer
            $palletsNumberCreditAccount = Palletsaccount::where('name', $creditAccount)->first()->theoricalNumberPallets;
            Palletsaccount::where('name', $creditAccount)->update(['theoricalNumberPallets' => $palletsNumberCreditAccount + $palletsNumber]);
            $palletsNumberDebitAccount = Palletsaccount::where('name', $debitAccount)->first()->theoricalNumberPallets;
            Palletsaccount::where('name', $debitAccount)->update(['theoricalNumberPallets' => $palletsNumberDebitAccount - $palletsNumber]);

            Palletstransfer::where('id', $id)->update(['date' => $date]);
            Palletstransfer::where('id', $id)->update(['type' => $type]);
            Palletstransfer::where('id', $id)->update(['palletsNumber' => $palletsNumber]);
            Palletstransfer::where('id', $id)->update(['creditAccount' => $creditAccount]);
            Palletstransfer::where('id', $id)->update(['debitAccount' => $debitAccount]);

            if ($validate == 'true') {
                Palletstransfer::where('id', $id)->update(['validate' => true]);
            }else{
                Palletstransfer::where('id', $id)->update(['validate' => false]);
            }

            if ((isset($documents) || !empty($actualDoc)) && (($validate<>null && $validate == 'true')||($validate==null && $transfer->validate==1))) {
                $state = 'Complete Validated';
            } elseif ((isset($documents) || !empty($actualDoc)) && (($validate<>null && $validate == 'false')||($validate==null && $transfer->validate==0))) {
                $state = 'Complete';
            } elseif (!isset($documents) && empty($actualDoc)) {
                $state = 'Waiting documents';
            }
            Palletstransfer::where('id', $id)->update(['state' => $state]);

        }
        session()->flash('messageUpdatePalletstransfer', 'Successfully updated pallets transfer');

    }
}

