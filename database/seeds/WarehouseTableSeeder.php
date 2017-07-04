<?php

use App\Palletsaccount;
use App\Warehouse;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

class WarehouseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->importDataPFM();
        $this->importDataSystempo();

    }

    public function importDataPFM()
    {
        $path = 'resources/assets/excel/ListWarehouses/PFM';
        $files = File::allFiles($path);
        foreach ($files as $file) {
            if (strpos((string)$file, '.xls') !== false) {
                Excel::load($file, function ($reader) {
                    if (!empty($reader)) {
                        $reader->noHeading();
                        $sheet = $reader->getSheet(1)->toArray();
                        $nbrows = count($sheet);

                        for ($r = 1; $r < $nbrows; $r++) {
                            $warehouseTest = Warehouse::where('name', '=', trim($sheet[$r][3]))->first();
                            if ($warehouseTest == null && trim($sheet[$r][3]) <> '') {
                                //not double
                                $k = count(Warehouse::get()) + 1;
                                $id = Palletsaccount::where('name', 'PFM - FR')->first()->id;

                                if (intval(trim($sheet[$r][0])) <> 0) {
                                    $country = 'France';
                                } else {
                                    $country = trim($sheet[$r][0]);
                                }

                                $cell7 = str_replace(' - ', ' ', trim($sheet[$r][7]));
                                $cell7 = str_replace('-', ' ', $cell7);

                                $zipcode = intval(substr($cell7, 0, 5));

                                $town = trim(str_replace($zipcode, '', $cell7));

                                Warehouse::firstOrCreate([
                                    'id' => $k,
                                    'name' => trim($sheet[$r][3]),
                                    'nickname' => trim($sheet[$r][3]),
                                    'adress' => trim($sheet[$r][6]),
                                    'zipcode' => $zipcode,
                                    'town' => $town,
                                    'country' => $country,
                                    'phone' => trim(substr(str_replace(' ', '',$sheet[$r][8] ), 0, 14)),
                                    'fax' => trim(substr(str_replace(' ', '',$sheet[$r][9]), 0, 14)),
                                    'email' => trim($sheet[$r][12]),
                                    'namecontact' => trim($sheet[$r][4]) . ' - ' . trim($sheet[$r][5]),
                                ])->palletsaccounts()->sync($id);
                            }
                        }
                    }
                }, 'ASCII');
            }
        }
    }

        public function importDataSystempo()
    {
        $path = 'resources/assets/excel/ListWarehouses/Systempo';
        $files = File::allFiles($path);
        foreach ($files as $file) {
            if (strpos((string)$file, '.xls') !== false) {
                Excel::load($file, function ($reader) {
                    if (!empty($reader)) {
                        $reader->noHeading();
                        $sheet = $reader->getSheet(0)->toArray();
                        $nbrows = count($sheet);

                        for ($r = 1; $r < $nbrows; $r++) {
                            $warehouseTest = Warehouse::where('name', '=', trim($sheet[$r][0]))->first();
                            if ($warehouseTest == null && trim($sheet[$r][0])<>'') {
                                //not double
                                $k = count(Warehouse::get()) + 1;
                                $id = Palletsaccount::where('name', 'Systempo AT')->first()->id;

                                Warehouse::firstOrCreate([
                                    'id' => $k,
                                    'name' => trim($sheet[$r][0]),
                                    'nickname' => trim($sheet[$r][0]),
                                    'adress' => trim($sheet[$r][1]),
                                    'zipcode' => intval(trim($sheet[$r][3])),
                                    'town' => trim($sheet[$r][4]),
                                    'country' =>trim($sheet[$r][2]),
                                    'phone' => trim(str_replace(' ','', $sheet[$r][5])),
                                    'email' => trim($sheet[$r][6]),
                                    'namecontact' => trim($sheet[$r][7]),
                                ])->palletsaccounts()->sync($id);
                            }
                        }
                    }
                }, 'ASCII');
            }
        }
    }


}
