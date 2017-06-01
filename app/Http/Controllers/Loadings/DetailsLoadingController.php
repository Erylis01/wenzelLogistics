<?php

namespace App\Http\Controllers;

use App\Loading;
use App\PalletsAccount;
use App\Palletstransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class DetailsLoadingController extends Controller
{
    /**
     * Display the content.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($atrnr)
    {

//        dd(Loading::where('atrnr',$atrnr)->with('palletstransfers')->first()->palletstransfers()->sum('palletsNumber'));
//dd(Palletstransfer::all()->loading());

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
            $gewicht = $detailsLoading->gewicht;
            $vol = $detailsLoading->vol;
            $ldm = $detailsLoading->ldm;
            $umsatz = $detailsLoading->umsatz;
            $aufwand = $detailsLoading->aufwand;
            $db = $detailsLoading->db;
            $trp = $detailsLoading->trp;
            $pt = $detailsLoading->pt;
            $subfrachter = $detailsLoading->subfrachter;
            $kennzeichen = $detailsLoading->kennzeichen;
            $zusladestellen = $detailsLoading->zusladestellen;
            $reasonUpdatePT = $detailsLoading->reasonUpdatePT;

            //table pallets
            $palletstransfersPlus=Loading::where('atrnr',$atrnr)->with('palletstransfers')->first()->palletstransfers()->where('palletsNumber', '>=',0)->get();
$palletstransfersMinus=Loading::where('atrnr',$atrnr)->with('palletstransfers')->first()->palletstransfers()->where('palletsNumber', '<',0)->get();
$sumPlus=Loading::where('atrnr',$atrnr)->with('palletstransfers')->first()->palletstransfers()->where('palletsNumber', '>=',0)->sum('realPalletsNumber');
$sumMinus=Loading::where('atrnr',$atrnr)->with('palletstransfers')->first()->palletstransfers()->where('palletsNumber', '<',0)->sum('realPalletsNumber');
$sum=Loading::where('atrnr',$atrnr)->with('palletstransfers')->first()->palletstransfers()->sum('realPalletsNumber');

            return view('loadings.detailsLoading', compact(  'sum','sumMinus','sumPlus','palletstransfersMinus','palletstransfersPlus','ladedatum', 'entladedatum', 'disp', 'atrnr', 'referenz', 'auftraggeber', 'beladestelle',
                'landb', 'plzb', 'ortb', 'entladestelle', 'lande', 'plze', 'orte', 'anz', 'art', 'vol', 'ldm', 'ware', 'gewicht', 'umsatz', 'aufwand',
                'db', 'trp', 'pt', 'subfrachter', 'kennzeichen', 'zusladestellen','reasonUpdatePT'
            ));
        } else {
            return view('auth.login');
        }
    }

    public function update(Request $request, $atrnr)
    {
        $ladedatum=Input::get('ladedatum');
        $entladedatum=Input::get('entladedatum');
        $disp=Input::get('disp');
        $referenz=Input::get('referenz');
        $auftraggeber=Input::get('auftraggeber');
        $beladestelle=Input::get('beladestelle');
        $ortb=Input::get('ortb');
        $plzb=Input::get('plzb');
        $landb=Input::get('landb');
        $entladestelle=Input::get('entladestelle');
        $orte=Input::get('orte');
        $plze=Input::get('plze');
        $lande=Input::get('lande');
        $anz=Input::get('anz');
        $art=Input::get('art');
        $ware=Input::get('ware');
        $gewicht=Input::get('gewicht');
        $vol=Input::get('vol');
        $ldm=Input::get('ldm');
        $umsatz=Input::get('umsatz');
        $aufwand=Input::get('aufwand');
        $db=$umsatz-$aufwand;
        $subfrachter=Input::get('subfrachter');
        $trp=Input::get('trp');
//        $pt=Input::get('pt');
        $kennzeichen=Input::get('kennzeichen');
        $zusladestellen=Input::get('zusladestellen');

        $reasonUpdatePT = Input::get('reasonUpdatePT');

        $rules = array(
            'disp' => 'required|string|max:4',
        );
        $validator = Validator::make(Input::all(), $rules);
        // process the login
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
                    'ortb' => $ortb, 'plzb' => $plzb, 'landb' => $landb, 'entladestelle' => $entladestelle, 'orte' => $orte, 'plze' => $plze, 'lande' => $lande, 'anz' => $anz, 'art' => $art, 'ware' => $ware, 'gewicht' => $gewicht,
                    'vol' => $vol, 'ldm' => $ldm, 'umsatz' => $umsatz, 'aufwand' => $aufwand, 'db' => $db, 'subfrachter' => $subfrachter, 'trp' => $trp, 'kennzeichen' => $kennzeichen, 'zusladestellen' => $zusladestellen]);
                session()->flash('messageUpdateLoading', 'Successfully updated loading');
            }
            return redirect()->back();
        }
    }
}
