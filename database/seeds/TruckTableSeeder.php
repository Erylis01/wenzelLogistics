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
                Excel::load($file, function ($reader) {
                    if (!empty($reader)) {
                        $reader->noHeading();
                        $sheet = $reader->getSheet(0)->toArray();
                        $nbrows = count($sheet);

                        for ($r = 4; $r < $nbrows; $r++) {
                            $testLicense = DB::table('trucks')->where('licensePlate', '=', trim($sheet[$r][26]))->first();

                            if ($testLicense == null) {
                                //not double
                                $nameAdress=explode(',',$sheet[$r][25]);
                                $testTruck = DB::table('palletsaccounts')->where('type', 'Carrier')->where('name', trim($nameAdress[0]))->first();

                                if($testTruck==null) {
                                    Palletsaccount::firstOrCreate([
                                        'name' => trim($nameAdress[0]),
                                        'adress'=>trim($nameAdress[1]),
                                        'type' => 'Carrier',
                                    ]);
                                    Truck::firstOrCreate([
                                        'name' => trim($nameAdress[0]),
                                        'licensePlate' => 'STOCK',
                                        'palletsaccount_name'=>trim($nameAdress[0]),
                                    ]);
                                }

                                if(trim($sheet[$r][26])==null){
                                    Truck::firstOrCreate([
                                        'name' => trim($nameAdress[0]),
                                        'licensePlate' => 'OTHER',
                                        'palletsaccount_name'=>trim($nameAdress[0]),
                                    ]);
                                }else{
                                    Truck::firstOrCreate([
                                        'name' => trim($nameAdress[0]),
                                        'licensePlate' => trim($sheet[$r][26]),
                                        'palletsaccount_name'=>trim($nameAdress[0]),
                                    ]);
                                }
                            }
                        }
                    }
                });
            }}
    }
}
