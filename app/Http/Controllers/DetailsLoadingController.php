<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DetailsLoadingController extends Controller
{
    /**
     * Display the content.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
//        $ladedatum =;
//        $entladedatum =;
//        $disp=;
//        $atrnr=;
//        $referenz=;
//        $auftraggeber=;
//        $beladestelle=;
//        $landb=;
//        $plzb=;
//        $ortb=;
//        $entladestelle=;
//        $lande=;
//        $plze=;
//        $orte=;
//        $anzahl=;
//        $try1=;
//        $try2=;
//        $try3=;
//        $ware=;
//        $gewicht=;
//        $umsatz=;
//        $aufwand=;
//        $db=;
//        $trp=;
//        $pt=;
//        $subfrachter=;
//        $pal=;
//        $imklarung=;
//        $paltauschvereinbart=;

        return view('detailsLoading', compact('ladedatum', 'entladedatum', 'disp', 'atrnr', 'referenz', 'auftraggeber', 'beladestelle',
            'landb', 'plzb', 'ortb', 'entladestelle', 'lande', 'plze', 'orte', 'anzahl', 'try1', 'try2', 'try3', 'ware', 'gewicht', 'umsatz', 'aufwand',
            'db', 'trp', 'pt', 'subfrachter', 'pal', 'imklarung', 'paltauschvereinbart'
        ));
    }
}
