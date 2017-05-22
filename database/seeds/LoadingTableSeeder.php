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
        $path = 'resources/assets/excel/PalettenKonto2.xlsx';
        $data = Excel::load($path, function ($reader) {
        })->get();
        if (!empty($data)) {
            foreach ($data as $sheet) {

                foreach ($sheet as $row) {

                    $loadingsTest = DB::table('loadings')->where('referenz', '=', trim($row->referenz))->get();

                    $count = count($loadingsTest);

                    if (!$loadingsTest->isEmpty()) {

                        $k = 0;
                        while ($k < $count && $k <> -199) {
                            if (date('Y-m-d H:i:s', strtotime($loadingsTest[$k]->ladedatum)) <> trim($row->ladedatum) || date('Y-m-d H:i:s', strtotime($loadingsTest[$k]->entladedatum)) <> trim($row->entladedatum) || $loadingsTest[$k]->disp <> trim($row->disp) || $loadingsTest[$k]->atrnr <> trim($row->atrnr) || $loadingsTest[$k]->auftraggeber <> trim($row->auftraggeber) || $loadingsTest[$k]->beladestelle <> trim($row->beladestelle) || $loadingsTest[$k]->landb <> trim($row->landb) || $loadingsTest[$k]->plzb <> trim($row->plzb) || $loadingsTest[$k]->ortb <> trim($row->ortb) || $loadingsTest[$k]->entladestelle <> trim($row->entladestelle) || $loadingsTest[$k]->lande <> trim($row->lande) || $loadingsTest[$k]->plze <> trim($row->plze) || $loadingsTest[$k]->orte <> trim($row->orte) || $loadingsTest[$k]->anzahl <> trim($row->anzahl) || $loadingsTest[$k]->try1 <> trim($row->try1) || $loadingsTest[$k]->try2 <> trim($row->try2) || $loadingsTest[$k]->try3 <> trim($row->try3) || $loadingsTest[$k]->ware <> trim($row->ware) || $loadingsTest[$k]->gewicht <> trim($row->gewicht) || $loadingsTest[$k]->umsatz <> trim($row->umsatz) || $loadingsTest[$k]->aufwand <> trim($row->aufwand) || $loadingsTest[$k]->db <> trim($row->db) || $loadingsTest[$k]->trp <> trim($row->trp) || $loadingsTest[$k]->pt <> trim($row->pt) || $loadingsTest[$k]->subfrachter <> trim($row->subfrachter) || $loadingsTest[$k]->pal <> trim($row->pal) || $loadingsTest[$k]->imklarung <> trim($row->imklarung)) {
                                $k++;
                            } else {
                                $k = -199;
                            }
                        }
                        if ($k == $count) {
//                            no double
                            Loading::firstOrCreate([
                                'ladedatum' => trim($row->ladedatum),
                                'entladedatum' => trim($row->entladedatum),
                                'disp' => trim($row->disp),
                                'atrnr' => trim($row->atrnr),
                                'referenz' => trim($row->referenz),
                                'auftraggeber' => trim($row->auftraggeber),
                                'beladestelle' => trim($row->beladestelle),
                                'landb' => trim($row->landb),
                                'plzb' => trim($row->plzb),
                                'ortb' => trim($row->ortb),
                                'entladestelle' => trim($row->entladestelle),
                                'lande' => trim($row->lande),
                                'plze' => trim($row->plze),
                                'orte' => trim($row->orte),
                                'anzahl' => trim($row->anzahl),
                                'try1' => trim($row->try1),
                                'try2' => trim($row->try2),
                                'try3' => trim($row->try3),
                                'ware' => trim($row->ware),
                                'gewicht' => trim($row->gewicht),
                                'umsatz' => trim($row->umsatz),
                                'aufwand' => trim($row->aufwand),
                                'db' => trim($row->db),
                                'trp' => trim($row->trp),
                                'pt' => trim($row->pt),
                                'subfrachter' => trim($row->subfrachter),
                                'pal' => trim($row->pal),
                                'imklarung' => trim($row->imklarung),
                                'paltauschvereinbart' => trim($row->paltauschvereinbart),
                                'warehouse_id' => trim($row->warehouse_id),
                            ]);
                        }
                    } else {
//                        not existing yet
                        Loading::firstOrCreate([
                            'ladedatum' => trim($row->ladedatum),
                            'entladedatum' => trim($row->entladedatum),
                            'disp' => trim($row->disp),
                            'atrnr' => trim($row->atrnr),
                            'referenz' => trim($row->referenz),
                            'auftraggeber' => trim($row->auftraggeber),
                            'beladestelle' => trim($row->beladestelle),
                            'landb' => trim($row->landb),
                            'plzb' => trim($row->plzb),
                            'ortb' => trim($row->ortb),
                            'entladestelle' => trim($row->entladestelle),
                            'lande' => trim($row->lande),
                            'plze' => trim($row->plze),
                            'orte' => trim($row->orte),
                            'anzahl' => trim($row->anzahl),
                            'try1' => trim($row->try1),
                            'try2' => trim($row->try2),
                            'try3' => trim($row->try3),
                            'ware' => trim($row->ware),
                            'gewicht' => trim($row->gewicht),
                            'umsatz' => trim($row->umsatz),
                            'aufwand' => trim($row->aufwand),
                            'db' => trim($row->db),
                            'trp' => trim($row->trp),
                            'pt' => trim($row->pt),
                            'subfrachter' => trim($row->subfrachter),
                            'pal' => trim($row->pal),
                            'imklarung' => trim($row->imklarung),
                            'paltauschvereinbart' => trim($row->paltauschvereinbart),
                            'warehouse_id' => trim($row->warehouse_id),
                        ]);
                    }

                }
            }
        }
    }


}
