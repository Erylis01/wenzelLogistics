<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WarehousesController extends Controller
{
    /**
     * Display the content.
     *
     * @return \Illuminate\Http\Response
     */
    public function showTotal(Request $request)
    {
        if (Auth::check()) {
            //table 1
            $totalpalanzahl =DB::table('warehouses')->sum('palanzahl');
            $totalpalanzahlFakturiert=DB::table('warehouses')->where('name', '=', 'Fakturiert')->sum('palanzahl');
            $totalpalanzahlVerschenkt=DB::table('warehouses')->where('name', '=', 'Verschenkt')->sum('palanzahl');
            $totalpalanzahlECLWolfurt=DB::table('warehouses')->where('name', '=', 'ECL Wolfurt')->sum('palanzahl');
            $totalpalanzahlSystempoAT=DB::table('warehouses')->where('name', '=', 'Systempo AT')->sum('palanzahl');
            $totalpalanzahlSBenoitValerie=DB::table('warehouses')->where('name', '=', 'Benoit & Valerie')->sum('palanzahl');
            $totalpalanzahlSPFMFR=DB::table('warehouses')->where('name', '=', 'PFM - FR')->sum('palanzahl');
            $totalpalanzahlTeamTex=DB::table('warehouses')->where('name', '=', 'Team Tex')->sum('palanzahl');
            $totalpalanzahlALDISWB=DB::table('warehouses')->where('name', '=', 'ALDI SWB')->sum('palanzahl');
            $totalpalanzahlALDIDAG=DB::table('warehouses')->where('name', '=', 'ALDI DAG')->sum('palanzahl');
            $totalpalanzahlALDIDOM=DB::table('warehouses')->where('name', '=', 'ALDI DOM')->sum('palanzahl');
            $totalpalanzahlDachserF51=DB::table('warehouses')->where('name', '=', 'Dachser F51 Reims')->sum('palanzahl');
            $totalpalanzahlImpexEUY=DB::table('warehouses')->where('name', '=', 'Impex-EUY')->sum('palanzahl');
            $totalpalanzahlBonduelleF80=DB::table('warehouses')->where('name', '=', 'Bonduelle F80')->sum('palanzahl');
            $totalpalanzahlSchefknecht=DB::table('warehouses')->where('name', '=', 'Schefknecht')->sum('palanzahl');
            $totalpalanzahlWildenhoferSalzburg=DB::table('warehouses')->where('name', '=', 'Wildenhofer Salzburg')->sum('palanzahl');
            $totalpalanzahlIMPEXEUX=DB::table('warehouses')->where('name', '=', 'IMPEX-EUX')->sum('palanzahl');
            $totalpalanzahlArinthod=DB::table('warehouses')->where('name', '=', 'Arinthod')->sum('palanzahl');
            $totalpalanzahlSparWels=DB::table('warehouses')->where('name', '=', 'Spar Wels')->sum('palanzahl');

            //table2

            return view('warehouses.allWarehouses', compact('totalpalanzahl', 'totalpalanzahlSparWels','totalpalanzahlArinthod','totalpalanzahlIMPEXEUX','totalpalanzahlWildenhoferSalzburg','totalpalanzahlSchefknecht','totalpalanzahlBonduelleF80','totalpalanzahlImpexEUY','totalpalanzahlDachserF51','totalpalanzahlALDIDOM','totalpalanzahlALDIDAG','totalpalanzahlALDISWB','totalpalanzahlTeamTex','totalpalanzahlSPFMFR','totalpalanzahlSBenoitValerie','totalpalanzahlSystempoAT','totalpalanzahlECLWolfurt','totalpalanzahlFakturiert','totalpalanzahlVerschenkt' ));
        }else{
            return view('auth.login');
        }}

}
