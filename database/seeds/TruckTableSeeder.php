<?php

use App\Truck;
use App\Palletsaccount;
use Illuminate\Database\Seeder;

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
        $path = 'resources/assets/excel/Hypertrans';
        $files = File::allFiles($path);

        foreach ($files as $file) {
            if (strpos((string)$file, '.xls') !== false) {
                ini_set('memory_limit', '-1');
                Excel::load($file, function ($reader) {
                    if (!empty($reader)) {
                        $reader->noHeading();
                        $sheet = $reader->getSheet(0)->toArray();
                        $nbrows = count($sheet);

                        for ($r = 4; $r < $nbrows; $r++) {
                            if (trim($sheet[$r][26]) <> '') {
                                $licensePlate = trim($sheet[$r][26]);
                            } else {
                                $licensePlate = 'OTHER';
                            }
                            if ($sheet[$r][25] <> null) {
                                $name = trim(explode(',', $sheet[$r][25])[0]);
                                $adress = trim(explode(',', $sheet[$r][25])[1]);

                                $testTruck = Truck::where('licensePlate', '=', $licensePlate)->where('name', $name)->first();

                                if ($testTruck == null) {
                                    //not double

                                    $testAccount = Palletsaccount::where('type', 'Carrier')->where('name', $name)->first();

                                    if ($testAccount == null) {
                                        Palletsaccount::firstOrCreate([
                                            'name' => $name,
                                            'adress' => $adress,
                                            'type' => 'Carrier',
                                        ]);
                                        Truck::firstOrCreate([
                                            'name' => $name,
                                            'licensePlate' => 'STOCK',
                                            'palletsaccount_name' => $name,
                                        ]);
                                    }
                                    Truck::firstOrCreate([
                                        'name' => $name,
                                        'licensePlate' => $licensePlate,
                                        'palletsaccount_name' => $name,
                                    ]);
                                }
                            }
                        }
                    }
                });
            }
        }
    }
}
