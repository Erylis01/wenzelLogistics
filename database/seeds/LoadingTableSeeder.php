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
                        'trp' =>trim($row->trp),
                        'pt' => trim($row->pt),
                        'subfrachter' => trim($row->subfrachter),
                        'pal' => trim($row->pal),
                        'imklarung' => trim($row->imklarung),
//                        'paltauschvereinbart' =>trim($row->paltauschvereinbart),
                ]);
            }
          }
       }
    }

}
