<?php

namespace App\Http\Controllers;

use App\Document;
use App\Loading;
use App\PalletsAccount;
use App\Palletstransfer;
use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DetailsLoadingController extends Controller
{
    /**
     * Display the content.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($atrnr)
    {
        if (Auth::check()) {
            ////////PANEL INFO///////
            $detailsLoading = DB::table('loadings')->where('atrnr', '=', $atrnr)->first();
            $ladedatum = $detailsLoading->ladedatum;
            $entladedatum = $detailsLoading->entladedatum;
            $disp = $detailsLoading->disp;
            $referenz = $detailsLoading->referenz;
            $auftraggeber = $detailsLoading->auftraggeber;
            $beladestelle = $detailsLoading->beladestelle;
            $landb = $detailsLoading->landb;
            $plzb = $detailsLoading->plzb;
            $ortb = $detailsLoading->ortb;
            $entladestelle = $detailsLoading->entladestelle;
            $lande = $detailsLoading->lande;
            $plze = $detailsLoading->plze;
            $orte = $detailsLoading->orte;
            $anz = $detailsLoading->anz;
            $art = $detailsLoading->art;
            $ware = $detailsLoading->ware;
            $pt = $detailsLoading->pt;
            $subfrachter = $detailsLoading->subfrachter;
            $kennzeichen = $detailsLoading->kennzeichen;
            $zusladestellen = $detailsLoading->zusladestellen;
            $reasonUpdatePT = $detailsLoading->reasonUpdatePT;


            //////PALLETS PANEL//////
            //control pallets
            $state = $detailsLoading->state;
            $numberLoadingPlace = $detailsLoading->numberLoadingPlace;
            $numberOffloadingPlace = $detailsLoading->numberOffloadingPlace;

            //all pallets account
            $listPalletsAccounts = DB::table('palletsaccounts')->get();

            //loading panel
            for ($k = 1; $k <= $numberLoadingPlace; $k++) {
                $stateLoadingPlaceK = 'stateLoadingPlace' . $k;
                $$stateLoadingPlaceK = $detailsLoading->$stateLoadingPlaceK;
                $validateLoadingPlaceK = 'validateLoadingPlace' . $k;
                $$validateLoadingPlaceK = $detailsLoading->$validateLoadingPlaceK;
                $numberPalletsLoadingPlaceK = 'numberPalletsLoadingPlace' . $k;
                $$numberPalletsLoadingPlaceK = $detailsLoading->$numberPalletsLoadingPlaceK;
                $accountDebitLoadingPlaceK = 'accountDebitLoadingPlace' . $k;
                $$accountDebitLoadingPlaceK = $detailsLoading->$accountDebitLoadingPlaceK;
                $accountCreditLoadingPlaceK = 'accountCreditLoadingPlace' . $k;
                $$accountCreditLoadingPlaceK = $detailsLoading->$accountCreditLoadingPlaceK;
            }
            //looking for the account of the warehouse which zipcode is plz beladestelle
            if (Warehouse::where('zipcode', $plzb)->first() <> null) {
                $idWarehouseZipcodeLoadingPlace = Warehouse::where('zipcode', $plzb)->first()->id;
                $accountZipcodeLoadingPlace1 = DB::table('palletsaccount_warehouse')->where('warehouse_id', $idWarehouseZipcodeLoadingPlace)->first();
                if ($accountZipcodeLoadingPlace1 <> null) {
                    $accountZipcodeLoadingPlace = Palletsaccount::where('id', $accountZipcodeLoadingPlace1->palletsaccount_id)->first()->name;

                }
            };

            $files = DB::table('document_loading')->where('loading_id', $atrnr)->get();


            //offloading panel
            for ($k = 1; $k <= $numberOffloadingPlace; $k++) {
                $stateOffloadingPlaceK = 'stateOffloadingPlace' . $k;
                $$stateOffloadingPlaceK = $detailsLoading->$stateOffloadingPlaceK;
                $validateOffloadingPlaceK = 'validateOffloadingPlace' . $k;
                $$validateOffloadingPlaceK = $detailsLoading->$validateOffloadingPlaceK;
                $numberPalletsOffloadingPlaceK = 'numberPalletsOffloadingPlace' . $k;
                $$numberPalletsOffloadingPlaceK = $detailsLoading->$numberPalletsOffloadingPlaceK;
                $accountDebitOffloadingPlaceK = 'accountDebitOffloadingPlace' . $k;
                $$accountDebitOffloadingPlaceK = $detailsLoading->$accountDebitOffloadingPlaceK;
                $accountCreditOffloadingPlaceK = 'accountCreditOffloadingPlace' . $k;
                $$accountCreditOffloadingPlaceK = $detailsLoading->$accountCreditOffloadingPlaceK;
            }

            if (Warehouse::where('zipcode', $plze)->first() <> null) {
                $idWarehouseZipcodeOffloadingPlace = Warehouse::where('zipcode', $plze)->first()->id;
                $accountZipcodeOffloadingPlace1 = DB::table('palletsaccount_warehouse')->where('warehouse_id', $idWarehouseZipcodeOffloadingPlace)->first();
                if ($accountZipcodeOffloadingPlace1 <> null) {
                    $accountZipcodeOffloadingPlace = Palletsaccount::where('id', $accountZipcodeOffloadingPlace1->palletsaccount_id)->first()->name;
                }
            };

            //offloading-loading
            if (!$files->isEmpty()) {
                foreach ($files as $f) {
                    $filesNames = Document::where('id', $f->document_id)->first();
                    if ($filesNames->type == 'Loading') {
                        $filesNamesLoadingPlace[] = $filesNames->name;
                    } elseif ($filesNames->type == 'Offloading') {
                        $filesNamesOffloadingPlace[] = $filesNames->name;
                    }
                }
            }

            return view('loadings.detailsLoading', compact('ladedatum', 'entladedatum', 'disp', 'atrnr', 'referenz', 'auftraggeber', 'beladestelle',
                'landb', 'plzb', 'ortb', 'entladestelle', 'lande', 'plze', 'orte', 'anz', 'art', 'ware',
                'pt', 'subfrachter', 'kennzeichen', 'zusladestellen', 'reasonUpdatePT', 'state', 'listPalletsAccounts', 'numberLoadingPlace', 'numberOffloadingPlace',
                'filesNamesLoadingPlace','accountZipcodeLoadingPlace', 'stateLoadingPlace1', 'numberPalletsLoadingPlace1', 'accountDebitLoadingPlace1', 'accountCreditLoadingPlace1', 'validateLoadingPlace1',
                'stateLoadingPlace2', 'numberPalletsLoadingPlace2', 'accountDebitLoadingPlace2', 'accountCreditLoadingPlace2', 'validateLoadingPlace2',
                'stateLoadingPlace3', 'numberPalletsLoadingPlace3', 'accountDebitLoadingPlace3', 'accountCreditLoadingPlace3', 'validateLoadingPlace3',
                'stateLoadingPlace4', 'numberPalletsLoadingPlace4', 'accountDebitLoadingPlace4', 'accountCreditLoadingPlace4', 'validateLoadingPlace4',
                'stateLoadingPlace5', 'numberPalletsLoadingPlace5', 'accountDebitLoadingPlace5', 'accountCreditLoadingPlace5', 'validateLoadingPlace5',
                'filesNamesOffloadingPlace', 'accountZipcodeOffloadingPlace', 'stateOffloadingPlace1', 'numberPalletsOffloadingPlace1', 'accountDebitOffloadingPlace1', 'accountCreditOffloadingPlace1', 'validateOffloadingPlace1',
                'stateOffloadingPlace2', 'numberPalletsOffloadingPlace2', 'accountDebitOffloadingPlace2', 'accountCreditOffloadingPlace2', 'validateOffloadingPlace2',
                'stateOffloadingPlace3', 'numberPalletsOffloadingPlace3', 'accountDebitOffloadingPlace3', 'accountCreditOffloadingPlace3', 'validateOffloadingPlace3',
                'stateOffloadingPlace4', 'numberPalletsOffloadingPlace4', 'accountDebitOffloadingPlace4', 'accountCreditOffloadingPlace4', 'validateOffloadingPlace4',
                'stateOffloadingPlace5', 'numberPalletsOffloadingPlace5', 'accountDebitOffloadingPlace5', 'accountCreditOffloadingPlace5', 'validateOffloadingPlace5'
                ));
        } else {
            return view('auth.login');
        }
    }

    public function update(Request $request, $atrnr)
    {
        $ladedatum = Input::get('ladedatum');
        $entladedatum = Input::get('entladedatum');
        $disp = Input::get('disp');
        $referenz = Input::get('referenz');
        $auftraggeber = Input::get('auftraggeber');
        $beladestelle = Input::get('beladestelle');
        $ortb = Input::get('ortb');
        $plzb = Input::get('plzb');
        $landb = Input::get('landb');
        $entladestelle = Input::get('entladestelle');
        $orte = Input::get('orte');
        $plze = Input::get('plze');
        $lande = Input::get('lande');
        $anz = Input::get('anz');
        $art = Input::get('art');
        $ware = Input::get('ware');
        $subfrachter = Input::get('subfrachter');
        $kennzeichen = Input::get('kennzeichen');
        $zusladestellen = Input::get('zusladestellen');
        $reasonUpdatePT = Input::get('reasonUpdatePT');

        $rules = array(
            'disp' => 'required|string|max:4',
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            if (isset($reasonUpdatePT) && isset($request->updateValidatePT)) {
                Loading::where('atrnr', $atrnr)->update(['reasonUpdatePT' => $reasonUpdatePT, 'pt' => 'NEIN']);
                session()->flash('messageUpdatePTLoading', 'Be careful : your loading is now WITHOUT exchange pallets');
            } elseif (isset($request->update)) {
                Loading::where('atrnr', $atrnr)->update(['ladedatum' => $ladedatum, 'entladedatum' => $entladedatum, 'disp' => $disp, 'referenz' => $referenz, 'auftraggeber' => $auftraggeber, 'beladestelle' => $beladestelle,
                    'ortb' => $ortb, 'plzb' => $plzb, 'landb' => $landb, 'entladestelle' => $entladestelle, 'orte' => $orte, 'plze' => $plze, 'lande' => $lande, 'anz' => $anz, 'art' => $art, 'ware' => $ware,
                    'subfrachter' => $subfrachter, 'kennzeichen' => $kennzeichen, 'zusladestellen' => $zusladestellen]);
                session()->flash('messageUpdateLoading', 'Successfully updated loading');
            }
            session()->flash('openPanelInformation', 'openPanelInformation');
            return redirect()->back();
        }
    }

    public function submitUpload($atrnr, $anz, Request $request)
    {
        //get button data to choose the right action to do
        $submitLoading = Input::get('submitLoading');
        $uploadLoading = Input::get('uploadLoading');
        $submitOffloading = Input::get('submitOffloading');
        $uploadOffloading = Input::get('uploadOffloading');
        $deleteDocument = Input::get('deleteDocument');

        if (isset($submitLoading) || isset($uploadLoading)) {
            //////////////////////LOADING PANEL///////////////////
            if (isset($submitLoading)) {
                ///////SUBMIT LOADING PANEL////////
//number pallets
                $numberPalletsLoadingPlace = Input::get('numberPalletsLoadingPlace');
                $this->numberPallets($atrnr, $numberPalletsLoadingPlace, 'LoadingPlace');

                //account credited
                $accountCreditLoadingPlace = Input::get('accountCreditLoadingPlace');
                $this->accountCredit($atrnr, $accountCreditLoadingPlace, 'LoadingPlace');

                //account debited
                $accountDebitLoadingPlace = Input::get('accountDebitLoadingPlace');
                $this->accountDebit($atrnr, $accountDebitLoadingPlace, 'LoadingPlace');

                //validated
                $validateLoadingPlace = Input::get('validateLoadingPlace');
                $this->validateTransfer($atrnr, $validateLoadingPlace, 'LoadingPlace');

                session()->flash('messageSuccessSubmit', 'Successfully updated pallets location');

            } elseif (isset($uploadLoading)) {
                ////////UPLOAD LOADING PANEL/////////
                //documents
                $documentsLoading = $request->file('documentsLoading');
                $this->uploadDocuments($atrnr, $documentsLoading, 'Loading');
            }

            //documents already associated to the loading ?
            $actualDocuments_LoadingLoading = DB::table('document_loading')->where('loading_id', $atrnr)->get();
            $actualDocumentsLoading = $this->documentsAssociated($actualDocuments_LoadingLoading, 'Loading');

            //state
            if (isset($numberPalletsLoadingPlace) && isset($accountDebitLoadingPlace) && isset($accountCreditLoadingPlace) && (isset($documentsLoading) || isset($actualDocumentsLoading)) && $validateLoadingPlace == true) {
                $stateLoadingPlace = 'Complete Validated';
//                $actualRealNumberPallets = Palletsaccount::where('name', $accountLoadingPlace)->first()->realNumberPallets;
//                Palletsaccount::where('name', $accountLoadingPlace)->update(['realNumberPallets' => $actualRealNumberPallets + $numberPalletsLoadingPlace]);
            } elseif (isset($numberPalletsLoadingPlace) && isset($accountDebitLoadingPlace) && isset($accountCreditLoadingPlace) && (isset($documentsLoading) || isset($actualDocumentsLoading))) {
                $stateLoadingPlace = 'Complete';
            } elseif (!isset($documentsLoading) || !isset($actualDocumentsLoading)) {
                $stateLoadingPlace = 'Waiting documents';
            } elseif (isset($numberPalletsLoadingPlace) || isset($accountDebitLoadingPlace) || isset($accountCreditLoadingPlace) || (isset($documentsLoading) || isset($actualDocumentsLoading))) {
                $stateLoadingPlace = 'In progress';
            } else {
                $stateLoadingPlace = 'Untreated';
            }
            Loading::where('atrnr', $atrnr)->update(['stateLoadingPlace' => $stateLoadingPlace]);

            session()->flash('openPanelLoading', 'openPanelLoading');

        } elseif (isset($submitOffloading) || isset($uploadOffloading)) {
            //////////////////////OFFLOADING PANEL///////////////////
            if (isset($submitOffloading)) {
                ///////SUBMIT OFFLOADING PANEL////////
//number pallets
                $numberPalletsOffloadingPlace = Input::get('numberPalletsOffloadingPlace');
                $this->numberPallets($atrnr, $numberPalletsOffloadingPlace, 'OffloadingPlace');

                //account credited
                $accountCreditOffloadingPlace = Input::get('accountCreditOffloadingPlace');
                $this->accountCredit($atrnr, $accountCreditOffloadingPlace, 'OffloadingPlace');

                //account debited
                $accountDebitOffloadingPlace = Input::get('accountDebitOffloadingPlace');
                $this->accountDebit($atrnr, $accountDebitOffloadingPlace, 'OffloadingPlace');

                //validated
                $validateOffloadingPlace = Input::get('validateOffloadingPlace');
                $this->validateTransfer($atrnr, $validateOffloadingPlace, 'OffloadingPlace');

                session()->flash('messageSuccessSubmit', 'Successfully updated pallets location');

            } elseif (isset($uploadOffloading)) {
                ////////UPLOAD OFFLOADING PANEL/////////
                //documents
                $documentsOffloading = $request->file('documentsOffloading');
                $this->uploadDocuments($atrnr, $documentsOffloading, 'Offloading');
            }
            //documents already associated to the loading ?
            $actualDocuments_LoadingOffloading = DB::table('document_loading')->where('loading_id', $atrnr)->get();
            $actualDocumentsOffloading = $this->documentsAssociated($actualDocuments_LoadingOffloading, 'Offloading');

            //state
            if (isset($numberPalletsOffloadingPlace) && isset($accountDebitOffloadingPlace) && isset($accountCreditOffloadingPlace) && (isset($documentsOffloading) || isset($actualDocumentsOffloading)) && $validateOffloadingPlace == true) {
                $stateOffloadingPlace = 'Complete Validated';
//                $actualRealNumberPallets = Palletsaccount::where('name', $accountLoadingPlace)->first()->realNumberPallets;
//                Palletsaccount::where('name', $accountLoadingPlace)->update(['realNumberPallets' => $actualRealNumberPallets + $numberPalletsLoadingPlace]);
            } elseif (isset($numberPalletsOffloadingPlace) && isset($accountDebitOffloadingPlace) && isset($accountCreditOffloadingPlace) && (isset($documentsOffloading) || isset($actualDocumentsOffloading))) {
                $stateOffloadingPlace = 'Complete';
            } elseif (!isset($documentsOffloading) || !isset($actualDocumentsOffloading)) {
                $stateOffloadingPlace = 'Waiting documents';
            } elseif (isset($numberPalletsOffloadingPlace) || isset($accountDebitOffloadingPlace) || isset($accountCreditOffloadingPlace) || (isset($documentsOffloading) || isset($actualDocumentsOffloading))) {
                $stateOffloadingPlace = 'In progress';
            } else {
                $stateOffloadingPlace = 'Untreated';
            }
            Loading::where('atrnr', $atrnr)->update(['stateOffloadingPlace' => $stateOffloadingPlace]);

            session()->flash('openPanelOffloading', 'openPanelOffloading');
        } elseif (isset($deleteDocument)) {
            $this->deleteDocument($atrnr, $deleteDocument);
        }
        return redirect()->back();
    }

    public function numberPallets($atrnr, $numberPallets, $type)
    {
        if (isset($numberPallets)) {
            Loading::where('atrnr', $atrnr)->update(['numberPallets' . $type => $numberPallets]);
        }
    }

    public function accountCredit($atrnr, $accountCredit, $type)
    {
        if (isset($accountCredit)) {
            Loading::where('atrnr', $atrnr)->update(['accountCredit' . $type => $accountCredit]);
//                $actualTheoricalNumberPallets = Palletsaccount::where('name', $accountLoadingPlace)->first()->theoricalNumberPallets;
//                Palletsaccount::where('name', $accountLoadingPlace)->update(['theoricalNumberPallets' => $actualTheoricalNumberPallets + $numberPalletsLoadingPlace]);
        }
    }

    public function accountDebit($atrnr, $accountDebit, $type)
    {
        if (isset($accountDebit)) {
            Loading::where('atrnr', $atrnr)->update(['accountDebit' . $type => $accountDebit]);
//                $actualTheoricalNumberPallets = Palletsaccount::where('name', $accountLoadingPlace)->first()->theoricalNumberPallets;
//                Palletsaccount::where('name', $accountLoadingPlace)->update(['theoricalNumberPallets' => $actualTheoricalNumberPallets + $numberPalletsLoadingPlace]);
        }
    }

    public function validateTransfer($atrnr, $validate, $type)
    {
        if ($validate == 'true') {
            $validate = true;
            Loading::where('atrnr', $atrnr)->update(['validate' . $type => $validate]);
        }
    }

    public function uploadDocuments($atrnr, $documents, $type)
    {
        if (isset($documents)) {
            foreach ($documents as $doc) {
                $filename = $doc->getClientOriginalName();
                $extension = $doc->getClientOriginalExtension();
                $size = $doc->getSize();
                //if file is an image, a pdf or an email
                if (($extension == 'png' || $extension == 'jpg' || $extension == 'msg' || $extension == 'htm' || $extension == 'rtf' || $extension == 'pdf') && $size < 2000000) {
                    Storage::putFileAs('/proofsPallets/' . $atrnr . '/documents' . $type, $doc, $filename);
                    Document::firstOrCreate([
                        'name' => $filename,
                        'type' => $type
                    ])->loadings()->attach($atrnr);
                    session()->flash('messageSuccessUpload', 'Successfully uploaded the files');
                } else {
                    session()->flash('messageErrorUpload', 'Error ! The file type is not supported (png, jgp, pdf, msg, htm, rtf only');
                    return redirect()->back();
                }
            }
        }
    }

    public function documentsAssociated($actualDocuments_Loading, $type)
    {
        $actualDocs = [];
        if (!$actualDocuments_Loading->isEmpty()) {
            foreach ($actualDocuments_Loading as $actualDoc) {
                $actualDocuments = Document::where('id', $actualDoc->document_id)->first();
                if ($actualDocuments->type == $type) {
                    $actualDocs[] = $actualDocuments;
                }
            }
        }
        return $actualDocs;
    }

    public function deleteDocument($atrnr, $name)
    {
        $doc = Document::where('name', $name)->first();
        if ($doc->type == 'Loading') {
            session()->flash('openPanelLoading', 'openPanelLoading');
        } elseif ($doc->type == 'Offloading') {
            session()->flash('openPanelOffloading', 'openPanelOffloading');
        }
        $doc->loadings()->detach($atrnr);
        $path = '/proofsPallets/' . $atrnr . '/documentsOffloading/';
        Storage::delete($path . $name);
        $doc->delete();
        // redirect
        session()->flash('messageSuccessDeleteDocument', 'Successfully deleted the document!');
        return redirect()->back();
    }
}
