<?php

use App\Truck;
use App\Palletsaccount;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class TruckTableSeeder extends Seeder
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
//        DB::table('trucks')->delete();
        $path = 'resources/assets/excel/Hypertrans';
        $files = File::allFiles($path);

        foreach ($files as $file) {
            if (strpos((string)$file, '.xls') !== false) {
                ini_set('memory_limit', '-1');
                Excel::load($file, function ($reader) {
                    if (!empty($reader)) {
                        $reader->noHeading();
                        foreach ($reader->get() as $sheet) {
                            for ($r = 4; $r < count($sheet); $r++) {
                                if (trim($sheet[$r][24]) == 'JA') {
                                    if (trim($sheet[$r][26]) <> '') {
                                        $licensePlate = trim($sheet[$r][26]);
                                    } else {
                                        $licensePlate = 'OTHER';
                                    }
                                    if ($sheet[$r][25] <> null) {
                                        if (count(explode(',', $sheet[$r][25])) > 2) {
                                            $adress = trim(explode(',', $sheet[$r][25])[count(explode(',', $sheet[$r][25])) - 1]);
                                            $name = trim(str_replace($adress, '', $sheet[$r][25]));
                                            $country = null;
                                            $zipcode = null;
                                            $town = null;
                                        } else {
                                            $name = trim(explode(',', $sheet[$r][25])[0]);
                                            $adress = trim(explode(',', $sheet[$r][25])[1]);
                                            $country = trim(explode('-', $adress)[0]);
                                            $zipTown = trim(explode('-', $adress)[1]);
                                            $zipcode = trim(explode(' ', $zipTown)[0]);
                                            $town = str_replace($zipcode, '', $zipTown);
                                        }

                                        $testAccount = Palletsaccount::where('type', 'Carrier')->where(function ($q) use ($name) {
                                            $q->where('name', $name)->orWhere('nickname', $name);
                                        })->first();
                                        if ($testAccount == null) {
                                            Palletsaccount::firstOrCreate([
                                                'name' => $name,
                                                'nickname' => $name,
                                                'adress' => $adress,
                                                'country' => $country,
                                                'zipcode' => $zipcode,
                                                'town' => $town,
                                                'type' => 'Carrier',
                                            ]);
                                        }

                                        $testTruckStock = Truck::where('licensePlate', '=', 'STOCK')->where('name', $name)->first();

                                        if ($testTruckStock == null) {
                                            Truck::firstOrCreate([
                                                'name' => $name,
                                                'licensePlate' => 'STOCK',
                                                'palletsaccount_name' => $name,
                                            ]);
                                        }
                                        $testTruck = Truck::where('licensePlate', '=', $licensePlate)->where('name', $name)->first();

                                        if ($testTruck == null) {
                                            //not double
                                            Truck::firstOrCreate([
                                                'name' => $name,
                                                'licensePlate' => $licensePlate,
                                                'palletsaccount_name' => $name,
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }
    }

}
