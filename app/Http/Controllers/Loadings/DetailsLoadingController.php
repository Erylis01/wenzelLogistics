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
use Illuminate\Support\Facades\Input;
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
            //table 1
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


//            //table pallets
//            $palletstransfersPlus=Loading::where('atrnr',$atrnr)->with('palletstransfers')->first()->palletstransfers()->where('palletsNumber', '>=',0)->get();
//$palletstransfersMinus=Loading::where('atrnr',$atrnr)->with('palletstransfers')->first()->palletstransfers()->where('palletsNumber', '<',0)->get();
//$sumPlus=Loading::where('atrnr',$atrnr)->with('palletstransfers')->first()->palletstransfers()->where('palletsNumber', '>=',0)->sum('realPalletsNumber');
//$sumMinus=Loading::where('atrnr',$atrnr)->with('palletstransfers')->first()->palletstransfers()->where('palletsNumber', '<',0)->sum('realPalletsNumber');
//$sum=Loading::where('atrnr',$atrnr)->with('palletstransfers')->first()->palletstransfers()->sum('realPalletsNumber');

            //control pallets
            $state = $detailsLoading->state;

            //loading
            $stateLoadingPlace = $detailsLoading->stateLoadingPlace;
            $validateLoadingPlace = $detailsLoading->validateLoadingPlace;
            $numberPalletsBackLoadingPlace = $detailsLoading->numberPalletsBackLoadingPlace;
            $accountLoadingPlace = $detailsLoading->accountLoadingPlace;
            $listPalletsAccounts = DB::table('palletsaccounts')->get();
            if (Warehouse::where('zipcode', $plzb)->first() <> null) {
                $idWarehouseZipcodeLoadingPlace = Warehouse::where('zipcode', $plzb)->first()->id;
                $idAccountZipcodeLoadingPlace = DB::table('palletsaccount_warehouse')->where('warehouse_id', $idWarehouseZipcodeLoadingPlace)->first()->palletsaccount_id;
                $accountZipcodeLoadingPlace = Palletsaccount::where('id', $idAccountZipcodeLoadingPlace)->first()->name;
            };

            $files = DB::table('document_loading')->where('loading_id', $atrnr)->get();
            if (!$files->isEmpty()) {
//                dd(Document::where('type', 'CMR_Exchange')->get(), Document::where('type', 'Pallets order')->get());
                $filesLoadingPlace=Document::where('type', 'CMR_Exchange')->get();
                if(!$filesLoadingPlace->isEmpty()){
                    foreach ($filesLoadingPlace as $fLP) {
                        $filesNamesLoadingPlace[] = $fLP->name;
                    }
                }
                $palletsOrderLoadingPlace=Document::where('type', 'Pallets order')->get();
                if(!$palletsOrderLoadingPlace->isEmpty()){
                    foreach ($palletsOrderLoadingPlace as $poLP){
                        $palletsOrderNamesLoadingPlace[] = $poLP->name;
                    }
                }
            }

            return view('loadings.detailsLoading', compact('ladedatum', 'entladedatum', 'disp', 'atrnr', 'referenz', 'auftraggeber', 'beladestelle',
                'landb', 'plzb', 'ortb', 'entladestelle', 'lande', 'plze', 'orte', 'anz', 'art', 'ware',
                'pt', 'subfrachter', 'kennzeichen', 'zusladestellen', 'reasonUpdatePT', 'state', 'listPalletsAccounts',
                'filesNamesLoadingPlace', 'palletsOrderNamesLoadingPlace', 'stateLoadingPlace', 'numberPalletsBackLoadingPlace', 'accountLoadingPlace', 'accountZipcodeLoadingPlace', 'validateLoadingPlace'
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

    public function uploadLoading($atrnr, $anz, Request $request)
    {
        //number pallets
        $numberPalletsBackLoadingPlace = Input::get('numberPalletsBackLoadingPlace');
        if (isset($numberPalletsBackLoadingPlace)) {
//        if ($numberPalletsBackLoadingPlace == $anz) {
//            $stateLoadingPlace = 'OK';
//        } elseif ($numberPalletsBackLoadingPlace > $anz) {
//            $stateLoadingPlace = 'sup';
//        } else {
//            $stateLoadingPlace = 'inf';
//        }
            Loading::where('atrnr', $atrnr)->update(['numberPalletsBackLoadingPlace' => $numberPalletsBackLoadingPlace]);
        }

        //account associated
        $accountLoadingPlace = Input::get('accountLoadingPlace');
        if (isset($accountLoadingPlace)) {
            Loading::where('atrnr', $atrnr)->update(['accountLoadingPlace' => $accountLoadingPlace]);
            $actualTheoricalNumberPallets = Palletsaccount::where('name', $accountLoadingPlace)->first()->theoricalNumberPallets;
            Palletsaccount::where('name', $accountLoadingPlace)->update(['theoricalNumberPallets' => $actualTheoricalNumberPallets + $numberPalletsBackLoadingPlace]);
        }
        session()->flash('messageSuccessUploadLoading', 'Successfully updated pallets location');

        //documents
        $documentsLoading = $request->file('documentsLoading');
        if (isset($documentsLoading)) {
            foreach ($documentsLoading as $document) {
                $filename = $document->getClientOriginalName();
                $extension = $document->getClientOriginalExtension();
                $size = $document->getSize();
                //if file is an image, a pdf or an email
                if (($extension == 'png' || $extension == 'jpg' || $extension == 'msg' || $extension == 'htm' || $extension == 'rtf' || $extension == 'pdf') && $size < 2000000) {
                    $document->store('proofsPallets/' . $atrnr . '/documentsLoading/CMR_Exchange');
                    Document::firstOrCreate([
                        'name' => $filename,
                        'type'=>'CMR_Exchange'
                    ])->loadings()->attach($atrnr);
                    session()->flash('messageSuccessUploadLoading', 'Successfully uploaded the files');
                } else {
                    session()->flash('messageErrorUploadLoading', 'Error ! The file type is not supported (png, jgp, pdf, msg, htm, rtf only');
                    return redirect()->back();
                }
            }
        }

        //palletsOrder
        $palletsOrderLoading = $request->file('palletsOrderLoading');
        if (isset($palletsOrderLoading)) {
            foreach ($palletsOrderLoading as $palletsOrder) {
                $palletsOrderName = $palletsOrder->getClientOriginalName();
                $extensionPalletsOrder = $palletsOrder->getClientOriginalExtension();
                $sizePalletsOrder = $palletsOrder->getSize();
                //if file is an image, a pdf or an email
                if (($extensionPalletsOrder == 'png' || $extensionPalletsOrder == 'jpg' || $extensionPalletsOrder == 'msg' || $extensionPalletsOrder == 'htm' || $extensionPalletsOrder == 'rtf' || $extensionPalletsOrder == 'pdf') && $sizePalletsOrder < 2000000) {
                    $palletsOrder->store('proofsPallets/' . $atrnr . '/documentsLoading/palletsOrder');
                    Document::firstOrCreate([
                        'name' => $palletsOrderName,
                        'type'=>'Pallets order'
                    ])->loadings()->attach($atrnr);
                    session()->flash('messageSuccessUploadLoading', 'Successfully uploaded the files');
                } else {
                    session()->flash('messageErrorUploadLoading', 'Error ! The file type is not supported (png, jgp, pdf, msg, htm, rtf only');
                    return redirect()->back();
                }
            }
        }

        //validated
        $validateLoadingPlace = Input::get('validateLoadingPlace');
        if ($validateLoadingPlace == 'false' || $validateLoadingPlace==null) {
            $validateLoadingPlace = false;
        } else {
            $validateLoadingPlace = true;
        }
        Loading::where('atrnr', $atrnr)->update(['validateLoadingPlace' => $validateLoadingPlace]);

        //documents already associated to the loading ?
        //pallets order already associated to the laoding ?
        $actualDocumentsLoading1 = DB::table('document_loading')->where('loading_id', $atrnr)->get();
        if (!$actualDocumentsLoading1->isEmpty()) {
            foreach ($actualDocumentsLoading1 as $actualDoc) {
                $actualDocumentsLoading[] = Document::where('id', $actualDoc->document_id)->where( 'type', 'CMR_Exchange')->first();
                $actualPalletsOrderLoading[] = Document::where('id', $actualDoc->document_id)->where('type', 'Pallets order')->first();
            }
        }

        //state
        if (isset($numberPalletsBackLoadingPlace) && isset($accountLoadingPlace) && (isset($documentsLoading) || isset($actualDocumentsLoading)) && $validateLoadingPlace == true &&($numberPalletsBackLoadingPlace<$anz && (isset($palletsOrderLoading) || isset($actualPalletsOrderLoading)))) {
            $stateLoadingPlace = 'Complete Validated';
            $actualRealNumberPallets = Palletsaccount::where('name', $accountLoadingPlace)->first()->realNumberPallets;
            Palletsaccount::where('name', $accountLoadingPlace)->update(['realNumberPallets' => $actualRealNumberPallets + $numberPalletsBackLoadingPlace]);
        } elseif (isset($numberPalletsBackLoadingPlace) && isset($accountLoadingPlace) && (isset($documentsLoading) || isset($actualDocumentsLoading)) && $validateLoadingPlace == true &&($numberPalletsBackLoadingPlace<$anz && (isset($palletsOrderLoading) || isset($actualPalletsOrderLoading)))) {
            $stateLoadingPlace = 'Complete';
        } elseif (!isset($documentsLoading) || !isset($actualDocumentsLoading)||($numberPalletsBackLoadingPlace<$anz && !isset($palletsOrderLoading) ) ||($numberPalletsBackLoadingPlace<$anz && !isset($actualPalletsOrderLoading))) {
            $stateLoadingPlace = 'Waiting documents';
        } elseif (isset($numberPalletsBackLoadingPlace) || isset($accountLoadingPlace) || (isset($documentsLoading) || isset($actualDocumentsLoading))) {
            $stateLoadingPlace = 'In progress';
        } else {
            $stateLoadingPlace = 'Untreated';
        }

        Loading::where('atrnr', $atrnr)->update(['stateLoadingPlace' => $stateLoadingPlace]);

        session()->flash('openPanelLoading', 'openPanelLoading');
        return redirect()->back();
    }

}
