<?php

use Illuminate\Database\Seeder;
use App\Loading;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LoadingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->importData();
    }

    public function importData()
    {
//        $path = 'resources/assets/excel/PalettenKonto2.xlsx';
//        $data = Excel::load($path, function ($reader) {
//        })->get();
//        if (!empty($data)) {
//            foreach ($data as $row) {
//
////                foreach ($sheet as $row) {
//                    DB::table('loadings')->insert([
//                        'ladedatum' => $row->ladedatum,
//                        'entladedatum' => $row->entladedatum,
//                        'disp' => $row->disp,
//                        'atrNr' => $row->atrNr,
//                        'referenz' => $row->referenz,
//                        'auftraggeber' => $row->auftraggeber,
//                        'beladestelle' => $row->beladestelle,
//                        'landB' => $row->landB,
//                        'plzB' => $row->plzB,
//                        'ortB' => $row->ortB,
//                        'entladestelle' => $row->entladestelle,
//                        'landE' => $row->landE,
//                        'plzE' => $row->plzE,
//                        'ortE' => $row->ortE,
//                        'anzahl' => $row->anzahl,
//                        'TRY1' => $row->TRY1,
//                        'TRY2' => $row->TRY2,
//                        'TRY3' => $row->TRY3,
//                        'ware' => $row->ware,
//                        'gewicht' => $row->gewicht,
//                        'umsatz' => $row->umsatz,
//                        'aufwand' => $row->aufwand,
//                        'db' => $row->db,
//                        'trp' => $row->trp,
//                        'pt' => $row->pt,
//                        'subfrächter' => $row->subfrächter,
//                        'pal' => $row->pal,
//                        'imKlärung' => $row->imKlärung,
//                        'palTauschVereinbart' =>$row->palTauschVereinbart,
//                    ]);
//                }
////            }
//        }
    }

}
