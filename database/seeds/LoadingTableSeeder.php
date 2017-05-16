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
            foreach ($data as $row) {

//                foreach ($sheet as $row) {
                Loading::firstOrCreate([
                        'ladedatum' => $row->ladedatum,
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
                        'paltauschvereinbart' =>$row->paltauschvereinbart,
                ]);
            }
          }
//        }
    }

}
