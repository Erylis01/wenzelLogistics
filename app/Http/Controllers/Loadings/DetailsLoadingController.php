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
            $numberPalletsBackLoadingPlace = $detailsLoading->numberPalletsBackLoadingPlace;
            $accountLoadingPlace=$detailsLoading->accountLoadingPlace;
            $listPalletsAccounts = DB::table('palletsaccounts')->get();
            $idWarehouseZipcodeLoadingPlace=Warehouse::where('zipcode', $plzb)->first()->id;
            $idAccountZipcodeLoadingPlace = DB::table('palletsaccount_warehouse')->where('warehouse_id',$idWarehouseZipcodeLoadingPlace)->first()->palletsaccount_id;
            $accountZipcodeLoadingPlace=Palletsaccount::where('id', $idAccountZipcodeLoadingPlace)->first()->name;

            $files = DB::table('document_loading')->where('loading_id', $atrnr)->get();
            foreach ($files as $f) {
                $filesNames[] = Document::where('id', $f->document_id)->first()->name;
            }

            return view('loadings.detailsLoading', compact('ladedatum', 'entladedatum', 'disp', 'atrnr', 'referenz', 'auftraggeber', 'beladestelle',
                'landb', 'plzb', 'ortb', 'entladestelle', 'lande', 'plze', 'orte', 'anz', 'art', 'ware',
                'pt', 'subfrachter', 'kennzeichen', 'zusladestellen', 'reasonUpdatePT',
                'filesNames', 'state', 'numberPalletsBackLoadingPlace', 'accountLoadingPlace', 'listPalletsAccounts', 'accountZipcodeLoadingPlace'
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
            return redirect()->back();
        }
    }

    public function uploadLoading($atrnr, $anz, Request $request)
    {
//        $detailsLoading = DB::table('loadings')->where('atrnr', '=', $atrnr)->first();
        $numberPalletsBackLoadingPlace = Input::get('numberPalletsBackLoadingPlace');
        Loading::where('atrnr', $atrnr)->update(['numberPalletsBackLoadingPlace'=>$numberPalletsBackLoadingPlace]);
       if(isset($numberPalletsBackLoadingPlace)){
        if ($numberPalletsBackLoadingPlace == $anz) {
            $state = 'OK';
        } elseif ($numberPalletsBackLoadingPlace > $anz) {
            $state = 'sup';
        } else {
            $state = 'inf';
        }
           Loading::where('atrnr', $atrnr)->update(['state'=>$state]);
       }

        $documentsLoading = $request->file('documentsLoading');
        if (isset($documentsLoading)) {
            foreach ($documentsLoading as $document) {
                $filename = $document->getClientOriginalName();
                $fileNames[] = $filename;
                $extension = $document->getClientOriginalExtension();
                $size = $document->getSize();
                //if file is an image, a pdf or an email
                if (($extension == 'png' || $extension == 'jpg' || $extension == 'msg' || $extension == 'htm' || $extension == 'rtf' || $extension == 'pdf') && $size < 2000000) {
                    $document->store('proofsPallets/' . $atrnr . '/documentsLoading');
                    Document::create([
                        'name' => $filename
                    ])->loadings()->sync($atrnr);
                    session()->flash('messageSuccessUploadLoading', 'Successfully uploaded the files');
                } else {
                    session()->flash('messageErrorUploadLoading', 'Error ! The file type is not supported (png, jgp, pdf, msg, htm, rtf only');
                    return redirect()->back();
                }
            }
        }

        $accountLoadingPlace=Input::get('accountLoadingPlace');
        Loading::where('atrnr', $atrnr)->update(['accountLoadingPlace'=>$accountLoadingPlace]);

        return redirect()->back();


    }

}
