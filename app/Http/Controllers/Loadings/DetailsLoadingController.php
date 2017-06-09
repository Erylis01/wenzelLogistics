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
            //all pallets account
            $listPalletsAccounts = DB::table('palletsaccounts')->get();

            //loading panel
            $stateLoadingPlace = $detailsLoading->stateLoadingPlace;
            $validateLoadingPlace = $detailsLoading->validateLoadingPlace;
            $numberPalletsLoadingPlace = $detailsLoading->numberPalletsLoadingPlace;
            $accountDebitLoadingPlace = $detailsLoading->accountDebitLoadingPlace;
            $accountCreditLoadingPlace = $detailsLoading->accountCreditLoadingPlace;

            if (Warehouse::where('zipcode', $plzb)->first() <> null) {
                $idWarehouseZipcodeLoadingPlace = Warehouse::where('zipcode', $plzb)->first()->id;
                $accountZipcodeLoadingPlace1 = DB::table('palletsaccount_warehouse')->where('warehouse_id', $idWarehouseZipcodeLoadingPlace)->first();
                if ($accountZipcodeLoadingPlace1 <> null) {
                    $accountZipcodeLoadingPlace = Palletsaccount::where('id', $accountZipcodeLoadingPlace1->palletsaccount_id)->first()->name;

                } };

            $files = DB::table('document_loading')->where('loading_id', $atrnr)->get();



            //offloading panel
            $stateOffloadingPlace = $detailsLoading->stateOffloadingPlace;
            $validateOffloadingPlace = $detailsLoading->validateOffloadingPlace;
            $numberPalletsOffloadingPlace = $detailsLoading->numberPalletsOffloadingPlace;
            $accountDebitOffloadingPlace = $detailsLoading->accountDebitOffloadingPlace;
            $accountCreditOffloadingPlace = $detailsLoading->accountCreditOffloadingPlace;

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
                    if ($filesNames->type == 'loading') {
                        $filesNamesLoadingPlace[] = $filesNames->name;
                    }elseif($filesNames->type == 'offloading'){
                        $filesNamesOffloadingPlace[] = $filesNames->name;
                    }
                }
            }

            return view('loadings.detailsLoading', compact('ladedatum', 'entladedatum', 'disp', 'atrnr', 'referenz', 'auftraggeber', 'beladestelle',
                'landb', 'plzb', 'ortb', 'entladestelle', 'lande', 'plze', 'orte', 'anz', 'art', 'ware',
                'pt', 'subfrachter', 'kennzeichen', 'zusladestellen', 'reasonUpdatePT', 'state', 'listPalletsAccounts',
                'filesNamesLoadingPlace', 'stateLoadingPlace', 'numberPalletsLoadingPlace', 'accountDebitLoadingPlace', 'accountCreditLoadingPlace', 'accountZipcodeLoadingPlace', 'validateLoadingPlace',
                'filesNamesOffloadingPlace', 'stateOffloadingPlace', 'numberPalletsOffloadingPlace', 'accountDebitOffloadingPlace', 'accountCreditOffloadingPlace', 'accountZipcodeOffloadingPlace', 'validateOffloadingPlace'
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
        $deleteDocument=Input::get('deleteDocument');

        if (isset($submitLoading) || isset($uploadLoading)) {
            //////////////////////LOADING PANEL///////////////////
            if (isset($submitLoading)) {
                ///////SUBMIT LOADING PANEL////////
//number pallets
                $numberPalletsLoadingPlace = Input::get('numberPalletsLoadingPlace');
                if (isset($numberPalletsLoadingPlace)) {
                    Loading::where('atrnr', $atrnr)->update(['numberPalletsLoadingPlace' => $numberPalletsLoadingPlace]);
                }
                //account credited
                $accountCreditLoadingPlace = Input::get('accountCreditLoadingPlace');
                if (isset($accountCreditLoadingPlace)) {
                    Loading::where('atrnr', $atrnr)->update(['accountCreditLoadingPlace' => $accountCreditLoadingPlace]);
//                $actualTheoricalNumberPallets = Palletsaccount::where('name', $accountLoadingPlace)->first()->theoricalNumberPallets;
//                Palletsaccount::where('name', $accountLoadingPlace)->update(['theoricalNumberPallets' => $actualTheoricalNumberPallets + $numberPalletsLoadingPlace]);
                }

                //account debited
                $accountDebitLoadingPlace = Input::get('accountDebitLoadingPlace');
                if (isset($accountDebitLoadingPlace)) {
                    Loading::where('atrnr', $atrnr)->update(['accountDebitLoadingPlace' => $accountDebitLoadingPlace]);
//                $actualTheoricalNumberPallets = Palletsaccount::where('name', $accountLoadingPlace)->first()->theoricalNumberPallets;
//                Palletsaccount::where('name', $accountLoadingPlace)->update(['theoricalNumberPallets' => $actualTheoricalNumberPallets + $numberPalletsLoadingPlace]);
                }

                //validated
                $validateLoadingPlace = Input::get('validateLoadingPlace');
                if ($validateLoadingPlace == 'true') {
                    $validateLoadingPlace = true;
                    Loading::where('atrnr', $atrnr)->update(['validateLoadingPlace' => $validateLoadingPlace]);
                }

                session()->flash('messageSuccessSubmit', 'Successfully updated pallets location');

            } elseif (isset($uploadLoading)) {
                ////////UPLOAD LOADING PANEL/////////
                //documents
                $documentsLoading = $request->file('documentsLoading');
                if (isset($documentsLoading)) {
                    foreach ($documentsLoading as $document) {
                        $filename = $document->getClientOriginalName();
                        $extension = $document->getClientOriginalExtension();
                        $size = $document->getSize();
                        //if file is an image, a pdf or an email
                        if (($extension == 'png' || $extension == 'jpg' || $extension == 'msg' || $extension == 'htm' || $extension == 'rtf' || $extension == 'pdf') && $size < 2000000) {
                            $document->move('../storage/app/proofsPallets/' . $atrnr . '/documentsLoading', $filename);
                            Document::firstOrCreate([
                                'name' => $filename,
                                'type' => 'loading'
                            ])->loadings()->attach($atrnr);
                            session()->flash('messageSuccessUpload', 'Successfully uploaded the files');
                        } else {
                            session()->flash('messageErrorUpload', 'Error ! The file type is not supported (png, jgp, pdf, msg, htm, rtf only');
                            return redirect()->back();
                        }
                    }
                }
            }

            //documents already associated to the loading ?
            $actualDocuments_LoadingLoading = DB::table('document_loading')->where('loading_id', $atrnr)->get();
            if (!$actualDocuments_LoadingLoading->isEmpty()) {
                foreach ($actualDocuments_LoadingLoading as $actualDoc) {
                    $actualDocuments = Document::where('id', $actualDoc->document_id)->first();
                    if ($actualDocuments->type == 'loading') {
                        $actualDocumentsLoading[] = $actualDocuments;
                    }
                }
            }
            //state
            if (isset($numberPalletsLoadingPlace) && isset($accountDebitLoadingPlace) && isset($accountCreditLoadingPlace) && (isset($documentsLoading) || isset($actualDocumentsLoading)) && $validateLoadingPlace == true) {
                $stateLoadingPlace = 'Complete Validated';
//                $actualRealNumberPallets = Palletsaccount::where('name', $accountLoadingPlace)->first()->realNumberPallets;
//                Palletsaccount::where('name', $accountLoadingPlace)->update(['realNumberPallets' => $actualRealNumberPallets + $numberPalletsLoadingPlace]);
            } elseif (isset($numberPalletsLoadingPlace) && isset($accountDebitLoadingPlace) && isset($accountCreditLoadingPlace) && (isset($documentsLoading) || isset($actualDocumentsLoading)) && $validateLoadingPlace == true) {
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
                if (isset($numberPalletsOffloadingPlace)) {
                    Loading::where('atrnr', $atrnr)->update(['numberPalletsOffloadingPlace' => $numberPalletsOffloadingPlace]);
                }
                //account credited
                $accountCreditOffloadingPlace = Input::get('accountCreditOffloadingPlace');
                if (isset($accountCreditOffloadingPlace)) {
                    Loading::where('atrnr', $atrnr)->update(['accountCreditOffloadingPlace' => $accountCreditOffloadingPlace]);
//                $actualTheoricalNumberPallets = Palletsaccount::where('name', $accountLoadingPlace)->first()->theoricalNumberPallets;
//                Palletsaccount::where('name', $accountLoadingPlace)->update(['theoricalNumberPallets' => $actualTheoricalNumberPallets + $numberPalletsLoadingPlace]);
                }

                //account debited
                $accountDebitOffloadingPlace = Input::get('accountDebitOffloadingPlace');
                if (isset($accountDebitOffloadingPlace)) {
                    Loading::where('atrnr', $atrnr)->update(['accountDebitOffloadingPlace' => $accountDebitOffloadingPlace]);
//                $actualTheoricalNumberPallets = Palletsaccount::where('name', $accountLoadingPlace)->first()->theoricalNumberPallets;
//                Palletsaccount::where('name', $accountLoadingPlace)->update(['theoricalNumberPallets' => $actualTheoricalNumberPallets + $numberPalletsLoadingPlace]);
                }

                //validated
                $validateOffloadingPlace = Input::get('validateOffloadingPlace');
                if ($validateOffloadingPlace == 'true') {
                    $validateOffloadingPlace = true;
                    Loading::where('atrnr', $atrnr)->update(['validateOffloadingPlace' => $validateOffloadingPlace]);
                }

                session()->flash('messageSuccessSubmit', 'Successfully updated pallets location');

            } elseif (isset($uploadOffloading)) {
                ////////UPLOAD OFFLOADING PANEL/////////
                //documents
                $documentsOffloading = $request->file('documentsOffloading');
                if (isset($documentsOffloading)) {
                    foreach ($documentsOffloading as $document) {
                        $filename = $document->getClientOriginalName();
                        $extension = $document->getClientOriginalExtension();
                        $size = $document->getSize();
//                        dd($document);
                        //if file is an image, a pdf or an email
                        if (($extension == 'png' || $extension == 'jpg' || $extension == 'msg' || $extension == 'htm' || $extension == 'rtf' || $extension == 'pdf') && $size < 2000000) {
//                            $document->copy('/proofsPallets/' . $atrnr . '/documentsOffloading',$filename);
//                            $document->move('../storage/app/proofsPallets/' . $atrnr . '/documentsOffloading', $filename);
                            Storage::putFileAs('/proofsPallets/' . $atrnr . '/documentsOffloading', $document, $filename);
                            Document::firstOrCreate([
                                'name' => $filename,
                                'type' => 'offloading'
                            ])->loadings()->attach($atrnr);
                            session()->flash('messageSuccessUpload', 'Successfully uploaded the files');
                        } else {
                            session()->flash('messageErrorUpload', 'Error ! The file type is not supported (png, jgp, pdf, msg, htm, rtf only');
                            return redirect()->back();
                        }
                    }
                }
            }
            //documents already associated to the loading ?
            $actualDocuments_Loadingoffloading = DB::table('document_loading')->where('loading_id', $atrnr)->get();
            if (!$actualDocuments_Loadingoffloading->isEmpty()) {
                foreach ($actualDocuments_Loadingoffloading as $actualDoc) {
                    $actualDocuments = Document::where('id', $actualDoc->document_id)->first();
                    if ($actualDocuments->type == 'offloading') {
                        $actualDocumentsOffloading[] = $actualDocuments;
                    }
                }
            }

            //state
            if (isset($numberPalletsOffloadingPlace) && isset($accountDebitOffloadingPlace) && isset($accountCreditLoadingPlace) && (isset($documentsOffloading) || isset($actualDocumentsOffloading)) && $validateOffloadingPlace == true) {
                $stateOffloadingPlace = 'Complete Validated';
//                $actualRealNumberPallets = Palletsaccount::where('name', $accountLoadingPlace)->first()->realNumberPallets;
//                Palletsaccount::where('name', $accountLoadingPlace)->update(['realNumberPallets' => $actualRealNumberPallets + $numberPalletsLoadingPlace]);
            } elseif (isset($numberPalletsOffloadingPlace) && isset($accountDebitOffloadingPlace) && isset($accountCreditOffloadingPlace) && (isset($documentsOffloading) || isset($actualDocumentsOffloading)) && $validateOffloadingPlace == true) {
                $stateOffloadingPlace = 'Complete';
            } elseif (!isset($documentsOffloading) || !isset($actualDocumentsOffloading)) {
                $stateOffloadingPlace = 'Waiting documents';
            } elseif (isset($numberPalletsOffloadingPlace) || isset($accountDebitOffloadingPlace) || (isset($documentsOffloading) || isset($actualDocumentsOffloading))) {
                $stateOffloadingPlace = 'In progress';
            } else {
                $stateOffloadingPlace = 'Untreated';
            }
            Loading::where('atrnr', $atrnr)->update(['stateOffloadingPlace' => $stateOffloadingPlace]);
            session()->flash('openPanelOffloading', 'openPanelOffloading');
        }elseif(isset($deleteDocument)){
            $this->deleteDocument($atrnr, $deleteDocument);
        }
        return redirect()->back();
    }

    public function deleteDocument($atrnr ,$name){
        $doc=Document::where('name', $name)->first();
        if($doc->type=='loading'){
            session()->flash('openPanelLoading', 'openPanelLoading');
        }elseif($doc->type=='offloading'){
            session()->flash('openPanelOffloading', 'openPanelOffloading');
        }
        $doc->loadings()->detach($atrnr);
        $path='/proofsPallets/' . $atrnr . '/documentsOffloading/';
        Storage::delete($path.$name);
        $doc->delete();
        // redirect
        session()->flash('messageSuccessDeleteDocument', 'Successfully deleted the document!');
        return redirect()->back();
    }
}
