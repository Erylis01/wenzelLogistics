<?php

namespace App\Http\Controllers;

use App\Document;
use App\Loading;
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
        $searchQuery = $request->get('search');
        $searchQueryArray = explode(' ', $searchQuery);
        $searchColumns=$request->get('searchColumns');
        $listColumns=['id','date', 'type', 'creditAccount', 'debitAccount', 'palletsNumber', 'state'];

        if (Auth::check()) {
            $currentDate = Carbon::now();
            $limitDate = $currentDate->subDays(60)->format('Y-m-d');

            $query=Palletstransfer::where([
                ['date', '>=', $limitDate],
            ]);

            if (request()->has('sortby') && request()->has('order')) {
                $sortby = $request->get('sortby'); // Order by what column?
                $order = $request->get('order'); // Order direction: asc or desc
                $searchColumnsString=$request->get('searchColumnsString');;
                $searchColumns=explode('-', $searchColumnsString);
                if (isset($searchQuery) && $searchQuery <> '') {
                    if (in_array('ALL', explode('-',$searchColumnsString ))) {
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
                $listPalletstransfers = $query->orderBy($sortby, $order)->paginate(10);
                $links = $listPalletstransfers->appends(['sortby' => $sortby, 'order' => $order])->render();
            } else {
                if (isset($searchQuery) && $searchQuery <> '') {
                    $searchColumnsString=implode('-',$searchColumns);
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
                $count = count($query->get());
                $listPalletstransfers = $query->paginate(10);
                $links = '';
            }
            return view('palletstransfers.allPalletstransfers', compact('listPalletstransfers', 'sortby', 'order', 'links', 'count', 'searchColumns', 'searchQuery', 'searchQueryArray', 'listColumns', 'searchColumnsString'));
        } else {
            return view('auth.login');
        }
    }

    /**
     * show the add form according to one parameter
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAddOther($other){
        if (Auth::check()) {
            foreach(Palletsaccount::get() as $account){
                $listNamesPalletsaccounts[]=$account->name;
            }
            $listTypes = ['Deposit', 'Withdrawal', 'Purchase', 'Sale','Other'];
            $date = Carbon::now()->format('Y-m-d');
            foreach(Loading::get()->where('pt', 'JA') as $loading){
                $listAtrnr[]=$loading->atrnr;
            }
            if(in_array($other, $listAtrnr)){
                $loading_atrnr=$other;
                return view('palletstransfers.addPalletstransfer', compact('listNamesPalletsaccounts', 'date', 'listTypes', 'listAtrnr', 'loading_atrnr'));
            }elseif(in_array($other, $listNamesPalletsaccounts)) {
                $creditAccount = $other;
                $debitAccount = $other;

                return view('palletstransfers.addPalletstransfer', compact('listNamesPalletsaccounts', 'date', 'creditAccount', 'debitAccount', 'listTypes', 'listAtrnr'));
            }else{
                return view('palletstransfers.addPalletstransfer', compact('listNamesPalletsaccounts', 'date', 'listTypes', 'listAtrnr'));
            }
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
            foreach(Palletsaccount::get() as $account){
                $listNamesPalletsaccounts[]=$account->name;
            }
            $listTypes = ['Deposit', 'Withdrawal', 'Purchase', 'Sale','Other'];
        $date = Carbon::now()->format('Y-m-d');
            foreach(Loading::get()->where('pt', 'JA') as $loading){
                $listAtrnr[]=$loading->atrnr;
            }
            return view('palletstransfers.addPalletstransfer', compact('listNamesPalletsaccounts', 'date', 'listTypes', 'listAtrnr'));
        } else {
            return view('auth.login');
        }
    }

    /**
     * add a new pallets transfer to the list
     */
    public function add()
    {
        foreach(Palletsaccount::get() as $account){
            $listNamesPalletsaccounts[]=$account->name;
        }
        $listTypes = ['Deposit', 'Withdrawal', 'Purchase', 'Sale','Other'];
        foreach(Loading::get()->where('pt', 'JA') as $loading){
            $listAtrnr[]=$loading->atrnr;
        }
        $date = Input::get('date');
        $type = Input::get('type');
        $multiTransfer=Input::get('multiTransfer');
        $details=Input::get('details');
        $loading_atrnr=Input::get('loading_atrnr');
        $creditAccount = Input::get('creditAccount');
        $debitAccount = Input::get('debitAccount');
        $palletsNumber = Input::get('palletsNumber');
        $addPalletstransfer = Input::get('addPalletstransfer');
        $okSubmitAddModal = Input::get('okSubmitAddModal');
        $closeSubmitAddModal = Input::get('closeSubmitAddModal');

            $actualTheoricalCreditPalletsNumber = Palletsaccount::where('name', $creditAccount)->value('theoricalNumberPallets');
            $actualTheoricalDebitPalletsNumber = Palletsaccount::where('name', $debitAccount)->value('theoricalNumberPallets');

            if (isset($addPalletstransfer)) {
                session()->flash('palletsNumber', $palletsNumber);
                session()->flash('creditAccount', $creditAccount);
                session()->flash('debitAccount', $debitAccount);
                session()->flash('palletsNumberCreditAccount', $actualTheoricalCreditPalletsNumber);
                session()->flash('palletsNumberDebitAccount', $actualTheoricalDebitPalletsNumber);
                return view('palletstransfers.addPalletstransfer', compact('date', 'type', 'creditAccount', 'debitAccount', 'palletsNumber', 'addPalletstransfer', 'listNamesPalletsaccounts', 'listTypes', 'multiTransfer', 'details', 'loading_atrnr', 'listAtrnr'));
            } elseif (isset($okSubmitAddModal)) {
                if($multiTransfer=='true'){
                    $multiTransfer=true;
                }elseif($multiTransfer=='false'){
                    $multiTransfer=false;
                }
                if (isset($date)&&isset($loading_atrnr)) {
                    Palletstransfer::create(['date' => $date, 'type' => $type,'details'=>$details, 'creditAccount' => $creditAccount, 'debitAccount' => $debitAccount, 'palletsNumber' => $palletsNumber, 'multiTransfer'=>$multiTransfer, 'loading_atrnr'=>$loading_atrnr]);
                } elseif(isset($date)) {
                    Palletstransfer::create(['date' => $date,'type' => $type, 'details'=>$details,'creditAccount' => $creditAccount, 'debitAccount' => $debitAccount, 'palletsNumber' => $palletsNumber, 'multiTransfer'=>$multiTransfer]);
                }elseif(isset($loading_atrnr)) {
                    Palletstransfer::create(['loading_atrnr' => $loading_atrnr,'type' => $type, 'details'=>$details,'creditAccount' => $creditAccount, 'debitAccount' => $debitAccount, 'palletsNumber' => $palletsNumber, 'multiTransfer'=>$multiTransfer]);
                }else {
                    Palletstransfer::create(['type' => $type, 'details'=>$details,'creditAccount' => $creditAccount, 'debitAccount' => $debitAccount, 'palletsNumber' => $palletsNumber, 'multiTransfer'=>$multiTransfer]);
                }
                Palletsaccount::where('name', $creditAccount)->update(['theoricalNumberPallets' => $actualTheoricalCreditPalletsNumber + $palletsNumber]);
                Palletsaccount::where('name', $debitAccount)->update(['theoricalNumberPallets' => $actualTheoricalDebitPalletsNumber - $palletsNumber]);
                session()->flash('messageAddPalletstransfer', 'Successfully added new pallets transfer');
                return redirect('/allPalletstransfers');
            } elseif (isset($closeSubmitAddModal)) {
                return redirect()->back();
      }

    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showDetails($id)
    {
        if (Auth::check()) {
            $transfer = Palletstransfer::where('id', $id)->first();
            foreach(Palletsaccount::get() as $account){
                $listNamesPalletsaccounts[]=$account->name;
            }
            $listTypes = ['Deposit', 'Withdrawal', 'Purchase', 'Sale','Other'];
            foreach(Loading::get()->where('pt', 'JA') as $loading){
                $listAtrnr[]=$loading->atrnr;
            }
           $filesNames=$this->actualDocuments($id);

            return view('palletstransfers.detailsPalletstransfer', compact('transfer','listNamesPalletsaccounts', 'listAtrnr','filesNames', 'listTypes'));
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

       //buttons
        $upload = Input::get('upload');
        $update = Input::get('update');
        $deleteDocument = Input::get('deleteDocument');
        $okSubmitUpdateModal = Input::get('okSubmitUpdateModal');
        $okSubmitUpdateValidateModal = Input::get('okSubmitUpdateValidateModal');
        $closeSubmitUpdateModal = Input::get('closeSubmitUpdateModal');

        //data
        $documents = $request->file('documentsTransfer');
        $date = Input::get('date');
        $type = Input::get('type');
        $details=Input::get('details');
        $multiTransfer=Input::get('multiTransfer');
        $loading_atrnr=Input::get('loading_atrnr');
        $creditAccount = Input::get('creditAccount');
        $debitAccount = Input::get('debitAccount');
        $palletsNumber = Input::get('palletsNumber');
        $validate = Input::get('validate');
        $state = $transfer->state;

        foreach(Palletsaccount::get() as $account){
            $listNamesPalletsaccounts[]=$account->name;
        }
        $listTypes = ['Deposit', 'Withdrawal', 'Purchase', 'Sale','Other'];
        foreach(Loading::get()->where('pt', 'JA') as $loading){
            $listAtrnr[]=$loading->atrnr;
        }

        if (isset($upload)) {
            $filesNames=$this->upload($documents, $id);

            if ((isset($documents) || !empty($filesNames)) && ($validate<>null && $validate == 'true')) {
                $state = 'Complete Validated';
            } elseif ((isset($documents) || !empty($filesNames)) && ($validate<>null && $validate == 'false')) {
                $state = 'Complete';
            } elseif (!isset($documents) && empty($filesNames)) {
                $state = 'Waiting documents';
            }
            Palletstransfer::where('id', $id)->update(['state' => $state]);
            return redirect()->back();
        } elseif (isset($update)) {
            if ($state == 'Complete Validated') {
                $this->inverseRealPalletsNumber($transfer->creditAccount,$transfer->debitAccount, $transfer->palletsNumber);
                }
            $filesNames= $this->actualDocuments($id);
            session()->put('actualCreditAccount', $transfer->creditAccount);
            session()->put('actualDebitAccount', $transfer->debitAccount);
            session()->put('actualPalletsNumber', $transfer->palletsNumber);
            session()->put('actualType', $transfer->type);
            session()->put('actualDetails', $transfer->details);
            session()->put('actualLoadingAtrnr', $transfer->loading_atrnr);
            session()->put('actualDate', $transfer->date);
            session()->put('actualValidate', $transfer->validate);
            session()->put('actualMultiTransfer', $transfer->multiTransfer);

            session()->flash('palletsNumber', $palletsNumber);
            session()->flash('creditAccount', $creditAccount);
            session()->flash('debitAccount', $debitAccount);
            session()->flash('thPalletsNumberCreditAccount', Palletsaccount::where('name', $creditAccount)->first()->theoricalNumberPallets);
            session()->flash('thPalletsNumberDebitAccount', Palletsaccount::where('name', $debitAccount)->first()->theoricalNumberPallets);

            Palletstransfer::where('id', $id)->update(['type'=>$type, 'details'=>$details,'loading_atrnr'=>$loading_atrnr,'palletsNumber'=>$palletsNumber,'date'=>$date,  'creditAccount'=>$creditAccount, 'debitAccount'=>$debitAccount]);
            if($validate<>null && $validate=='true'){
                Palletstransfer::where('id', $id)->update(['validate'=>true]);
            }elseif($validate<>null&&$validate=='false'){
                Palletstransfer::where('id', $id)->update(['validate'=>false]);
            }
            if($multiTransfer<>null && $multiTransfer=='true'){
                Palletstransfer::where('id', $id)->update(['multiTransfer'=>true]);
            }elseif($multiTransfer<>null&&$multiTransfer=='false'){
                Palletstransfer::where('id', $id)->update(['multiTransfer'=>false]);
            }
            $transfer=Palletstransfer::where('id', $id)->first();
            return view('palletstransfers.detailsPalletstransfer', compact('transfer','listNamesPalletsaccounts','listAtrnr','update', 'listTypes', 'documents', 'filesNames'));

        } elseif (isset($deleteDocument)) {
            $this->deleteDocument($id, $deleteDocument, $state, $transfer->creditAccount, $transfer->debitAccount, $transfer->palletsNumber);
            return redirect()->back();
        } elseif (isset($okSubmitUpdateModal)) {
            $filesNames=$this->actualDocuments($id);
            $actualCreditAccount = session('actualCreditAccount');
            $actualDebitAccount = session('actualDebitAccount');
            $actualPalletsNumber = session('actualPalletsNumber');
            $this->updateInfo($transfer, $actualPalletsNumber, $actualCreditAccount, $actualDebitAccount, $documents, $filesNames);
            $transfer=Palletstransfer::where('id', $id)->first();
            if ($transfer->state == 'Complete Validated') {
                session()->flash('palletsNumber', $transfer->palletsNumber);
                session()->flash('creditAccount', $transfer->creditAccount);
                session()->flash('debitAccount', $transfer->debitAccount);
                session()->flash('realPalletsNumberCreditAccount', Palletsaccount::where('name', $transfer->creditAccount)->first()->realNumberPallets);
                session()->flash('realPalletsNumberDebitAccount', Palletsaccount::where('name', $transfer->debitAccount)->first()->realNumberPallets);
                return view('palletstransfers.detailsPalletstransfer', compact( 'transfer','listNamesPalletsaccounts','listAtrnr','okSubmitUpdateModal',  'listTypes', 'documents', 'filesNames'));
            } else {
                session()->pull('actualCreditAccount');
                session()->pull('actualDebitAccount');
                session()->pull('actualPalletsNumber');
                session()->pull('actualType');
                session()->pull('actualDetails');
                session()->pull('actualLoadingAtrnr');
                session()->pull('actualDate');
                session()->pull('actualMultiTransfer');
                session()->pull('actualValidate');
                return redirect()->back();
            }
        } elseif (isset($closeSubmitUpdateModal)) {
            $actualCreditAccount = session('actualCreditAccount');
            $actualDebitAccount = session('actualDebitAccount');
            $actualPalletsNumber = session('actualPalletsNumber');
            $actualType = session('actualType');
            $actualDetails = session('actualDetails');
            $actualLoadingAtrnr = session('actualLoadingAtrnr');
            $actualDate = session('actualDate');
            $actualMultiTransfer = session('actualMultiTransfer');
            $actualValidate = session('actualValidate');
            Palletstransfer::where('id', $id)->update(['multiTransfer'=>$actualMultiTransfer,'validate'=>$actualValidate, 'type'=>$actualType, 'details'=>$actualDetails,'loading_atrnr'=>$actualLoadingAtrnr,'palletsNumber'=>$actualPalletsNumber,'date'=>$actualDate,  'creditAccount'=>$actualCreditAccount, 'debitAccount'=>$actualDebitAccount]);
            session()->pull('actualCreditAccount');
            session()->pull('actualDebitAccount');
            session()->pull('actualPalletsNumber');
            session()->pull('actualType');
            session()->pull('actualDetails');
            session()->pull('actualLoadingAtrnr');
            session()->pull('actualDate');
            session()->pull('actualMultiTransfer');
            session()->pull('actualValidate');
            return redirect()->back();
        } elseif (isset($okSubmitUpdateValidateModal)) {
            $realPalletsNumberCreditAccount = Palletsaccount::where('name', $transfer->creditAccount)->first()->realNumberPallets;
            Palletsaccount::where('name', $transfer->creditAccount)->update(['realNumberPallets' => $realPalletsNumberCreditAccount + $transfer->palletsNumber]);
            $realPalletsNumberDebitAccount = Palletsaccount::where('name', $transfer->debitAccount)->first()->realNumberPallets;
            Palletsaccount::where('name', $transfer->debitAccount)->update(['realNumberPallets' => $realPalletsNumberDebitAccount - $transfer->palletsNumber]);
            session()->flash('messageUpdateValidatePalletstransfer', 'VALIDATE ! Successfully updated and validated pallets transfer');
            session()->pull('actualCreditAccount');
            session()->pull('actualDebitAccount');
            session()->pull('actualPalletsNumber');
            session()->pull('actualType');
            session()->pull('actualDetails');
            session()->pull('actualLoadingAtrnr');
            session()->pull('actualDate');
            session()->pull('actualMultiTransfer');
            session()->pull('actualValidate');
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
        //inverse operation
        $actualPalletsNumber = Palletstransfer::where('id', $id)->first()->palletsNumber;
        $actualCreditAccount = Palletstransfer::where('id', $id)->first()->creditAccount;
        $actualDebitAccount = Palletstransfer::where('id', $id)->first()->debitAccount;
        $actualPalletsNumberCreditAccount = Palletsaccount::where('name', $actualCreditAccount)->first()->theoricalNumberPallets;
        Palletsaccount::where('name', $actualCreditAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberCreditAccount - $actualPalletsNumber]);
        $actualPalletsNumberDebitAccount = Palletsaccount::where('name', $actualDebitAccount)->first()->theoricalNumberPallets;
        Palletsaccount::where('name', $actualDebitAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberDebitAccount + $actualPalletsNumber]);

        $state = Palletstransfer::where('id', $id)->first()->state;
        if ($state == 'Complete Validated') {
        $this->inverseRealPalletsNumber($actualCreditAccount, $actualDebitAccount, $actualPalletsNumber);
        }

        $actualDocuments_Palletstransfers = DB::table('document_palletstransfer')->where('palletstransfer_id', $id)->get();
        $actualDocuments = [];
        if (!$actualDocuments_Palletstransfers->isEmpty()) {
            foreach ($actualDocuments_Palletstransfers as $actualDoc) {
                $actualDocuments[] = Document::where('id', $actualDoc->document_id)->first();
            }
            foreach ($actualDocuments as $actDoc) {
                $doc = Document::where('name', $actDoc)->where('type', 'Transfer')->first();
                $doc->palletstransfers()->detach($id);
                $path = '/proofsPallets/documentsTransfer/'.$id.'/';
                Storage::delete($path . $actDoc);
                $actualTransferAssociated=DB::table('document_palletstransfer')->where('document_id', $doc->id)->get();
                if($actualTransferAssociated->isEmpty()){
                    $doc->delete();
                }
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
        $filesNames=$this->actualDocuments($id);
        if (isset($documents)) {
            foreach ($documents as $doc) {
                $filename = $doc->getClientOriginalName();
                $extension = $doc->getClientOriginalExtension();
                $size = $doc->getSize();
                //if file is an image, a pdf or an email
                if (($extension == 'png' || $extension == 'jpg'|| $extension == 'JPG' || $extension == 'msg' || $extension == 'htm' || $extension == 'rtf' || $extension == 'pdf') && $size < 2000000) {
                    Storage::putFileAs('/proofsPallets/documentsTransfer/'.$id, $doc, $filename);
                    Document::firstOrCreate([
                        'name' => $filename,
                    ])->palletstransfers()->attach($id);
                } else {
                    session()->flash('messageErrorUpload', 'Error ! The file type is not supported (png, jgp, pdf, msg, htm, rtf only');
                }
            }
        }
        return $filesNames;
    }

    /**
     * delete a document attach to this transfer
     * @param $id
     * @param $name
     * @param $state
     * @param $actualCreditAccount
     * @param $actualDebitAccount
     * @param $actualPalletsNumber
     */
    public function deleteDocument($id, $name, $state, $actualCreditAccount, $actualDebitAccount, $actualPalletsNumber)
    {
        $doc = Document::where('name', $name)->first();
        $doc->palletstransfers()->detach($id);
        $path = '/proofsPallets/documentsTransfer/'.$id.'/';
        Storage::delete($path . $name);
        $actualTransferAssociated=DB::table('document_palletstransfer')->where('document_id', $doc->id)->get();
        if($actualTransferAssociated->isEmpty()){
            $doc->delete();
        }
        $actualDocuments_Palletstransfers = DB::table('document_palletstransfer')->where('palletstransfer_id', $id)->get();
        if ($actualDocuments_Palletstransfers->isEmpty()) {
            Palletstransfer::where('id', $id)->update(['validate' => false]);
            if ($state == 'Complete Validated') {
                $this->inverseRealPalletsNumber($actualCreditAccount,$actualDebitAccount, $actualPalletsNumber );
                }
            $state = 'Waiting documents';
            Palletstransfer::where('id', $id)->update(['state' => $state]);
        }
    }

    /**
     * find the documents currently attach to this transfer
     * @param $id
     * @return $filesNames
     */
    public function actualDocuments($id){
        $actualDocuments_Palletstransfers = DB::table('document_palletstransfer')->where('palletstransfer_id', $id)->get();
        $filesNames=[];
        if (!$actualDocuments_Palletstransfers->isEmpty()) {
            foreach ($actualDocuments_Palletstransfers as $actualDoc) {
                $filesNames[] = Document::where('id', $actualDoc->document_id)->first()->name;
            }
        }
        return $filesNames;
    }

    /**
     * remove the last confirmed pallets transfer made for this transfer
     * @param $creditAccount
     * @param $debitAccount
     * @param $palletsNumber
     */
    public function inverseRealPalletsNumber($creditAccount, $debitAccount, $palletsNumber){
        $actualRealPalletsNumberCreditAccount = Palletsaccount::where('name', $creditAccount)->first()->realNumberPallets;
        Palletsaccount::where('name', $creditAccount)->update(['realNumberPallets' => $actualRealPalletsNumberCreditAccount - $palletsNumber]);
        $actualRealPalletsNumberDebitAccount = Palletsaccount::where('name',  $debitAccount)->first()->realNumberPallets;
        Palletsaccount::where('name',  $debitAccount)->update(['realNumberPallets' => $actualRealPalletsNumberDebitAccount + $palletsNumber]);

    }

    public function updateInfo($transfer, $actualPalletsNumber, $actualCreditAccount, $actualDebitAccount,$documents, $actualDoc)
    {
            //inverse transfer : we delete the last transfer
            $actualPalletsNumberCreditAccount = Palletsaccount::where('name', $actualCreditAccount)->first()->theoricalNumberPallets;
            Palletsaccount::where('name', $actualCreditAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberCreditAccount - $actualPalletsNumber]);
            $actualPalletsNumberDebitAccount = Palletsaccount::where('name', $actualDebitAccount)->first()->theoricalNumberPallets;
            Palletsaccount::where('name', $actualDebitAccount)->update(['theoricalNumberPallets' => $actualPalletsNumberDebitAccount + $actualPalletsNumber]);

            //we do the new transfer
            $palletsNumberCreditAccount = Palletsaccount::where('name', $transfer->creditAccount)->first()->theoricalNumberPallets;
            Palletsaccount::where('name', $transfer->creditAccount)->update(['theoricalNumberPallets' => $palletsNumberCreditAccount + $transfer->palletsNumber]);
            $palletsNumberDebitAccount = Palletsaccount::where('name', $transfer->debitAccount)->first()->theoricalNumberPallets;
            Palletsaccount::where('name', $transfer->debitAccount)->update(['theoricalNumberPallets' => $palletsNumberDebitAccount - $transfer->palletsNumber]);

            if ((isset($documents) || !empty($actualDoc)) && ($transfer->validate<>null && $transfer->validate == 1)) {
                $state = 'Complete Validated';
            } elseif ((isset($documents) || !empty($actualDoc)) && ($transfer->validate<>null && $transfer->validate == 0)) {
                $state = 'Complete';
            } elseif (!isset($documents) && empty($actualDoc)) {
                $state = 'Waiting documents';
            }
            Palletstransfer::where('id', $transfer->id)->update(['state' => $state]);

        session()->flash('messageUpdatePalletstransfer', 'Successfully updated pallets transfer');
    }
}

