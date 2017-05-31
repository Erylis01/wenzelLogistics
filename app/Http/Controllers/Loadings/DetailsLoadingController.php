<?php

namespace App\Http\Controllers;

use App\Loading;
use App\PalletsAccount;
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
        if (Auth::check()) {
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

            $ruckgabewo = $detailsLoading->ruckgabewo;
            $mahnung = $detailsLoading->mahnung;
            $blockierung = $detailsLoading->blockierung;
            $bearbeitungsdatum = $detailsLoading->bearbeitungsdatum;
            $palgebucht = $detailsLoading->palgebucht;
            $state = $detailsLoading->state;
            $reasonUpdatePT = $detailsLoading->reasonUpdatePT;

            return view('loadings.detailsLoading', compact(  'ladedatum', 'entladedatum', 'disp', 'atrnr', 'referenz', 'auftraggeber', 'beladestelle',
                'landb', 'plzb', 'ortb', 'entladestelle', 'lande', 'plze', 'orte', 'anz', 'art', 'vol', 'ldm', 'ware', 'gewicht', 'umsatz', 'aufwand',
                'db', 'trp', 'pt', 'subfrachter', 'kennzeichen', 'zusladestellen', 'ruckgabewo', 'mahnung', 'blockierung', 'bearbeitungsdatum', 'palgebucht',
                'state', 'reasonUpdatePT'
            ));
        } else {
            return view('auth.login');
        }
    }

    public function save(Request $request, $atrnr)
    {
        $loading = DB::table('loadings')->where('atrnr', $atrnr)->first();
        $ruckgabewo = Input::get('ruckgabewo');
        $mahnung = Input::get('mahnung');
        $blockierung = Input::get('blockierung');
        $bearbeitungsdatum = Input::get('bearbeitungsdatum');
        $palgebucht = Input::get('palgebucht');
        $reasonUpdatePT = Input::get('reasonUpdatePT');
        $updateValidatePT = $request->updateValidatePT;

        if (isset($reasonUpdatePT) && isset($updateValidatePT)) {
            Loading::where('atrnr', $atrnr)->update(['reasonUpdatePT'=>$reasonUpdatePT,'pt'=>'NEIN']);
            session()->flash('messageUpdatePTLoading', 'Be careful : your loading is now WITHOUT exchange pallets');
        } elseif ($loading->ruckgabewo <> $ruckgabewo || $loading->mahnung <> $mahnung || $loading->blockierung <> $blockierung || $loading->bearbeitungsdatum <> $bearbeitungsdatum || $loading->palgebucht <> $palgebucht) {
            // store
            if ($palgebucht == 'OK' || $palgebucht == 'ok') {
                $state = 'OK';
            } elseif ($palgebucht == 'almost OK' || $palgebucht == 'almost ok') {
                $state = 'almost OK';
            } elseif ($palgebucht == 'not OK' || $palgebucht == 'not ok') {
                $state = 'not OK';
            }
            Loading::where('atrnr', $atrnr)->update(['ruckgabewo'=>$ruckgabewo,'mahnung'=>$mahnung, 'blockierung'=>$blockierung,'bearbeitungsdatum'=>$bearbeitungsdatum, 'palgebucht'=>$palgebucht,'state'=>$state] );
            session()->flash('messageSaveLoading', 'Successfully updated loading');
        }
        return redirect()->back();
    }
}
