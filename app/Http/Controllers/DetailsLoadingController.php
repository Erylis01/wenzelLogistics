<?php

namespace App\Http\Controllers;

use App\Loading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetailsLoadingController extends Controller
{
    /**
     * Display the content.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
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

        return view('detailsLoading', compact('id', 'ladedatum', 'entladedatum', 'disp', 'atrnr', 'referenz', 'auftraggeber', 'beladestelle',
            'landb', 'plzb', 'ortb', 'entladestelle', 'lande', 'plze', 'orte', 'anzahl', 'try1', 'try2', 'try3', 'ware', 'gewicht', 'umsatz', 'aufwand',
            'db', 'trp', 'pt', 'subfrachter', 'pal', 'imklarung', 'paltauschvereinbart', 'ruckgabewo', 'mahnung', 'blockierung', 'bearbeitungsdatum', 'palgebucht', 'state'
        ));
    }

    public function save(Request $request, $id)
    {
        $ruckgabewo = Input::get('ruckgabewo');
        $mahnung = Input::get('mahnung');
        $blockierung = Input::get('blockierung');
        $bearbeitungsdatum = Input::get('bearbeitungsdatum');
        $palgebucht = Input::get('palgebucht');

        if (isset($ruckgabewo) || isset($mahnung) || isset($blockierung) || isset($bearbeitungsdatum) || isset($palgebucht)) {
            // store
            $loading = Loading::find($id);
            $loading->ruckgabewo = $ruckgabewo;
            $loading->mahnung = $mahnung;
            $loading->blockierung = $blockierung;
            $loading->bearbeitungsdatum = $bearbeitungsdatum;
            $loading->palgebucht = $palgebucht;
            $loading->save();

            session()->flash('messageSaveLoading', 'Successfully saved loading');
            return redirect('/loadings');
        }else{
            return redirect()->back();
        }
    }
}
