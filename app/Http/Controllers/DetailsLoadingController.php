<?php

namespace App\Http\Controllers;

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
//        $currentDate = Carbon::now();
//        $limitDate=$currentDate->subDays(60)->format('Y-m-d');
        //        $listLoadings = DB::table('loadings')->where([
//            ['pt', '=', 'test'],
//            ['ladedatum', '>=', $limitDate],
//        ['id', '=', $id],
//])->distinct()->get();

        $detailsLoading = DB::table('loadings')->where('id', $id)->first();

        $ladedatum =$detailsLoading->ladedatum;
        $entladedatum =$detailsLoading->entladedatum;
        $disp=$detailsLoading->disp;
        $atrnr=$detailsLoading->atrnr;
        $referenz=$detailsLoading->referenz;
        $auftraggeber=$detailsLoading->auftraggeber;
        $beladestelle=$detailsLoading->beladestelle;
        $landb=$detailsLoading->landb;
        $plzb=$detailsLoading->plzb;
        $ortb=$detailsLoading->ortb;
        $entladestelle=$detailsLoading->entladestelle;
        $lande=$detailsLoading->lande;
        $plze=$detailsLoading->plze;
        $orte=$detailsLoading->orte;
        $anzahl=$detailsLoading->anzahl;
        $try1=$detailsLoading->try1;
        $try2=$detailsLoading->try2;
        $try3=$detailsLoading->try3;
        $ware=$detailsLoading->ware;
        $gewicht=$detailsLoading->gewicht;
        $umsatz=$detailsLoading->umsatz;
        $aufwand=$detailsLoading->aufwand;
        $db=$detailsLoading->db;
        $trp=$detailsLoading->trp;
        $pt=$detailsLoading->pt;
        $subfrachter=$detailsLoading->subfrachter;
        $pal=$detailsLoading->pal;
        $imklarung=$detailsLoading->imklarung;
        $paltauschvereinbart=$detailsLoading->paltauschvereinbart;

        return view('detailsLoading', compact('id','ladedatum', 'entladedatum', 'disp', 'atrnr', 'referenz', 'auftraggeber', 'beladestelle',
            'landb', 'plzb', 'ortb', 'entladestelle', 'lande', 'plze', 'orte', 'anzahl', 'try1', 'try2', 'try3', 'ware', 'gewicht', 'umsatz', 'aufwand',
            'db', 'trp', 'pt', 'subfrachter', 'pal', 'imklarung', 'paltauschvereinbart'
        ));
    }
}
