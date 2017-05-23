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

    /**
     * check every excel file on a depository
     * check the 1st sheet
     * check every row
     * if atrnr already in database : not copy
     *
     * @return void
     */
    public function importData()
    {

//        Excel::batch('resources/assets/excel', function ($sheets, $file) {
//
//            // Explain the reader how it should interpret each row,
//            // for every file inside the batch
//            dd($sheets->get());
//            foreach ($sheets->get() as $sheet) {
//                if ($sheet->getTitle()=='Tabelle1'){
//
//                    foreach ($sheet as $row) {
//dd($row->get());
//                        foreach ($row as $cell) {
//                            dd($cell);
//                        }
//                    }
//                }
//
//            }
//
//        });


        $path = 'resources/assets/excel/20170523-BDD2.xlsx';
        Excel::load($path, function ($reader) {
            if (!empty($reader)) {
                $reader->noHeading();
                $sheet = $reader->getSheet(0)->toArray();
                $nbrows = count($sheet);

                for ($r = 4; $r < $nbrows; $r++) {
                    $loadingsTest = DB::table('loadings')->where('atrnr', '=', trim($sheet[$r][3]))->first();
                    if ($loadingsTest==null) {
                        //not double
                        $datel_parse=date_parse_from_format('m-d-y', trim($sheet[$r][0]));
                        $datel=new DateTime();
                        $datel->setDate($datel_parse['year'], $datel_parse['month'],$datel_parse['day']);

                        $datee_parse=date_parse_from_format('m-d-y', trim($sheet[$r][1]));
                        $datee=new DateTime();
                        $datee->setDate($datee_parse['year'], $datee_parse['month'],$datee_parse['day']);

                        Loading::firstOrCreate([
                            'ladedatum' =>$datel ,
                            'entladedatum' => $datee,
                            'disp' => trim($sheet[$r][2]),
                            'atrnr' => trim($sheet[$r][3]),
                            'referenz' => trim($sheet[$r][4]),
                            'auftraggeber' => trim($sheet[$r][5]),
                            'beladestelle' => trim($sheet[$r][6]),
                            'landb' => trim($sheet[$r][7]),
                            'plzb' => trim($sheet[$r][8]),
                            'ortb' => trim($sheet[$r][9]),
                            'entladestelle' => trim($sheet[$r][10]),
                            'lande' => trim($sheet[$r][11]),
                            'plze' => trim($sheet[$r][12]),
                            'orte' => trim($sheet[$r][13]),
                            'anz' => trim($sheet[$r][14]),
                            'art' => trim($sheet[$r][15]),
                            'ware' => trim($sheet[$r][16]),
                            'gewicht' => trim($sheet[$r][17]),
                            'vol' => trim($sheet[$r][18]),
                            'ldm' => trim($sheet[$r][19]),
                            'umsatz' => trim($sheet[$r][20]),
                            'aufwand' => trim($sheet[$r][21]),
                            'db' => trim($sheet[$r][22]),
                            'trp' => trim($sheet[$r][23]),
                            'pt' => trim($sheet[$r][24]),
                            'subfrachter' => trim($sheet[$r][25]),
                            'kennzeichen' => trim($sheet[$r][26]),
                            'zusladestellen' => trim($sheet[$r][27]),
                        ]);
                    }
                }
            }
        });


//                    $loadingsTest = DB::table('loadings')->where('atrnr', '=', trim($row->atrnr))->get();
//                    $count = count($loadingsTest);
//
////                    if (!$loadingsTest->isEmpty()) {
////
////                        $k = 0;
////                        while ($k < $count && $k <> -199) {
////                            if (date('Y-m-d H:i:s', strtotime($loadingsTest[$k]->ladedatum)) <> trim($row->ladedatum) || date('Y-m-d H:i:s', strtotime($loadingsTest[$k]->entladedatum)) <> trim($row->entladedatum) || $loadingsTest[$k]->disp <> trim($row->disp) || $loadingsTest[$k]->atrnr <> trim($row->atrnr) || $loadingsTest[$k]->auftraggeber <> trim($row->auftraggeber) || $loadingsTest[$k]->beladestelle <> trim($row->beladestelle) || $loadingsTest[$k]->landb <> trim($row->landb) || $loadingsTest[$k]->plzb <> trim($row->plzb) || $loadingsTest[$k]->ortb <> trim($row->ortb) || $loadingsTest[$k]->entladestelle <> trim($row->entladestelle) || $loadingsTest[$k]->lande <> trim($row->lande) || $loadingsTest[$k]->plze <> trim($row->plze) || $loadingsTest[$k]->orte <> trim($row->orte) || $loadingsTest[$k]->anzahl <> trim($row->anzahl) || $loadingsTest[$k]->try1 <> trim($row->try1) || $loadingsTest[$k]->try2 <> trim($row->try2) || $loadingsTest[$k]->try3 <> trim($row->try3) || $loadingsTest[$k]->ware <> trim($row->ware) || $loadingsTest[$k]->gewicht <> trim($row->gewicht) || $loadingsTest[$k]->umsatz <> trim($row->umsatz) || $loadingsTest[$k]->aufwand <> trim($row->aufwand) || $loadingsTest[$k]->db <> trim($row->db) || $loadingsTest[$k]->trp <> trim($row->trp) || $loadingsTest[$k]->pt <> trim($row->pt) || $loadingsTest[$k]->subfrachter <> trim($row->subfrachter) || $loadingsTest[$k]->pal <> trim($row->pal) || $loadingsTest[$k]->imklarung <> trim($row->imklarung)) {
////                                $k++;
////                            } else {
////                                $k = -199;
////                            }
////                        }
////                        if ($k == $count) {
//////                            no double
////                            Loading::firstOrCreate([
////                                'ladedatum' => trim($row->ladedatum),
////                                'entladedatum' => trim($row->entladedatum),
////                                'disp' => trim($row->disp),
////                                'atrnr' => trim($row->atrnr),
////                                'referenz' => trim($row->referenz),
////                                'auftraggeber' => trim($row->auftraggeber),
////                                'beladestelle' => trim($row->beladestelle),
////                                'landb' => trim($row->landb),
////                                'plzb' => trim($row->plzb),
////                                'ortb' => trim($row->ortb),
////                                'entladestelle' => trim($row->entladestelle),
////                                'lande' => trim($row->lande),
////                                'plze' => trim($row->plze),
////                                'orte' => trim($row->orte),
////                                'anzahl' => trim($row->anzahl),
////                                'try1' => trim($row->try1),
////                                'try2' => trim($row->try2),
////                                'try3' => trim($row->try3),
////                                'ware' => trim($row->ware),
////                                'gewicht' => trim($row->gewicht),
////                                'umsatz' => trim($row->umsatz),
////                                'aufwand' => trim($row->aufwand),
////                                'db' => trim($row->db),
////                                'trp' => trim($row->trp),
////                                'pt' => trim($row->pt),
////                                'subfrachter' => trim($row->subfrachter),
////                                'pal' => trim($row->pal),
////                                'imklarung' => trim($row->imklarung),
////                                'paltauschvereinbart' => trim($row->paltauschvereinbart),
////                                'warehouse_id' => trim($row->warehouse_id),
////                            ]);
////                        }
////                    } else {
////                        not existing yet


//        }
//    }

    }
}
