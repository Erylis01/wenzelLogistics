<?php

namespace App\Http\Controllers;

use App\Loading;
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
    public function show($id)
    {
        if (Auth::check()) {
        $detailsLoading = DB::table('loadings')->where('id', $id)->first();

        $ladedatum = $detailsLoading->ladedatum;
        $entladedatum = $detailsLoading->entladedatum;
        $disp = $detailsLoading->disp;
        $atrnr = $detailsLoading->atrnr;
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
        $anzahl = $detailsLoading->anzahl;
        $try1 = $detailsLoading->try1;
        $try2 = $detailsLoading->try2;
        $try3 = $detailsLoading->try3;
        $ware = $detailsLoading->ware;
        $gewicht = $detailsLoading->gewicht;
        $umsatz = $detailsLoading->umsatz;
        $aufwand = $detailsLoading->aufwand;
        $db = $detailsLoading->db;
        $trp = $detailsLoading->trp;
        $pt = $detailsLoading->pt;
        $subfrachter = $detailsLoading->subfrachter;
        $pal = $detailsLoading->pal;
        $imklarung = $detailsLoading->imklarung;
        $paltauschvereinbart = $detailsLoading->paltauschvereinbart;
        $ruckgabewo = $detailsLoading->ruckgabewo;
        $mahnung = $detailsLoading->mahnung;
        $blockierung = $detailsLoading->blockierung;
        $bearbeitungsdatum = $detailsLoading->bearbeitungsdatum;
        $palgebucht = $detailsLoading->palgebucht;
        $state = $detailsLoading->state;
        $reasonUpdatePT = $detailsLoading->reasonUpdatePT;

        return view('detailsLoading', compact('id', 'ladedatum', 'entladedatum', 'disp', 'atrnr', 'referenz', 'auftraggeber', 'beladestelle',
            'landb', 'plzb', 'ortb', 'entladestelle', 'lande', 'plze', 'orte', 'anzahl', 'try1', 'try2', 'try3', 'ware', 'gewicht', 'umsatz', 'aufwand',
            'db', 'trp', 'pt', 'subfrachter', 'pal', 'imklarung', 'paltauschvereinbart', 'ruckgabewo', 'mahnung', 'blockierung', 'bearbeitungsdatum', 'palgebucht',
            'state', 'reasonUpdatePT'
        ));
    }else {
            return view('auth.login');
        }}

    public function save(Request $request, $id)
    {
        $loading = Loading::find($id);

        $ruckgabewo = Input::get('ruckgabewo');
        $mahnung = Input::get('mahnung');
        $blockierung = Input::get('blockierung');
        $bearbeitungsdatum = Input::get('bearbeitungsdatum');
        $palgebucht = Input::get('palgebucht');
        $reasonUpdatePT = Input::get('reasonUpdatePT');
        $updateValidatePT = $request->updateValidatePT;


        if (isset($reasonUpdatePT) && isset($updateValidatePT)) {
            $loading->reasonUpdatePT = $reasonUpdatePT;
            $loading->pt = 'NEIN';
            $loading->save();
            session()->flash('messageUpdatePTLoading', 'Be careful : your loading is now WITHOUT exchange pallets');

        } elseif ($loading->ruckgabewo <> $ruckgabewo || $loading->mahnung <> $mahnung || $loading->blockierung <> $blockierung || $loading->bearbeitungsdatum <> $bearbeitungsdatum || $loading->palgebucht <> $palgebucht) {
            // store
            $loading->ruckgabewo = $ruckgabewo;
            $loading->mahnung = $mahnung;
            $loading->blockierung = $blockierung;
            $loading->bearbeitungsdatum = $bearbeitungsdatum;
            $loading->palgebucht = $palgebucht;
            if ($palgebucht == 'OK' || $palgebucht == 'ok') {
                $loading->state = 'OK';
            } elseif ($palgebucht == 'almost OK' || $palgebucht == 'almost ok') {
                $loading->state = 'almost OK';
            } elseif ($palgebucht == 'not OK' || $palgebucht == 'not ok') {
                $loading->state = 'not OK';
            }
            $loading->save();

            session()->flash('messageSaveLoading', 'Successfully updated loading');
        }
        return redirect()->back();

    }
}
