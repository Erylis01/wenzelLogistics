<?php

namespace App\Http\Controllers;



use App\Loading;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ListLoadingsController extends Controller
{
    /**
     * Display the content.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
//        $this->importData();
        $currentDate = Carbon::now();
        $limitDate=$currentDate->subDays(60)->format('Y-m-d');
//        $listLoadings = DB::table('loadings')->where([
//            ['pt', '=', 'test'],
//            ['ladedatum', '>=', $limitDate],
//])->distinct()->get();
        $listLoadings = DB::table('loadings')->get();
//        dd($listLoadings);
//        foreach($listLoadings as $loading){
//            $listLoadingsArray[]=$loading;
//        }
//        dd(array_unique($listLoadingsArray));
        return view('loadings', compact('listLoadings'));
    }

    /**
     * Import data from an excel file
     */
    public function importData(){
        $path = 'resources/assets/excel/PalettenKonto2.xlsx';
        $data = Excel::load($path, function($reader) {
        })->get();
        if(!empty($data) && $data->count()){
            foreach ($data as $key => $row) {
                $loading=Loading::firstOrCreate(['ladedatum' => $row->ladedatum,
                    'entladedatum' => $row->entladedatum,
                    'disp' => $row->disp,
                    'atrnr' => $row->atrnr,
                    'referenz' => $row->referenz,
                    'auftraggeber' => $row->auftraggeber,
                    'beladestelle' => $row->beladestelle,
                    'landb' => $row->landb,
                    'plzb' => $row->plzb,
                    'ortb' => $row->ortb,
                    'entladestelle' => $row->entladestelle,
                    'lande' => $row->lande,
                    'plze' => $row->plze,
                    'orte' => $row->orte,
                    'anzahl' => $row->anzahl,
                    'try1' => $row->try1,
                    'try2' => $row->try2,
                    'try3' => $row->try3,
                    'ware' => $row->ware,
                    'gewicht' => $row->gewicht,
                    'umsatz' => $row->umsatz,
                    'aufwand' => $row->aufwand,
                    'db' => $row->db,
                    'trp' => $row->trp,
                    'pt' => $row->pt,
                    'subfrachter' => $row->subfrachter,
                    'pal' => $row->pal,
                    'imklarung' => $row->imklarung,
                    'paltauschvereinbart' =>$row->paltauschvereinbart,]);
                $loadings[] =$loading;
            }
            dd($loadings);
            if(!empty($loadings)){
                DB::table('loadings')->insert($loadings);
                dd('Insert Record successfully.');
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
