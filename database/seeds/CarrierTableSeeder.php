<?php

use App\Carrier;
use App\Palletsaccount;
use Illuminate\Database\Seeder;

class CarrierTableSeeder extends Seeder
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
        $path = 'resources/assets/excel/';
        $files = File::allFiles($path);
        foreach ($files as $file) {
            if (strpos((string)$file, '.xls') !== false) {
                Excel::load($file, function ($reader) {
                    if (!empty($reader)) {
                        $reader->noHeading();
                        $sheet = $reader->getSheet(0)->toArray();
                        $nbrows = count($sheet);

                        for ($r = 4; $r < $nbrows; $r++) {
                            $carrierTest = DB::table('carriers')->where('licensePlate', '=', trim($sheet[$r][26]))->first();

                            if ($carrierTest == null) {
                                //not double
                                if(trim($sheet[$r][26])==null){
                                    Palletsaccount::firstOrCreate([
                                        'name' => trim($sheet[$r][25]),
                                        'type'=>'Carrier',
                                    ]);
                                    Carrier::firstOrCreate([
                                        'name' => trim($sheet[$r][25]),
                                        'licensePlate' => 'OTHER',
                                        'palletsaccount_name'=>trim($sheet[$r][25]),
                                    ]);
//                                    dd(Palletsaccount::where('type', 'Carrier')->value('name'));
                                }else{
                                    Palletsaccount::firstOrCreate([
                                        'name' => trim($sheet[$r][26]).' - '.trim($sheet[$r][25]),
                                        'type'=>'Carrier',
                                    ]);
                                    Carrier::firstOrCreate([
                                        'name' => trim($sheet[$r][25]),
                                        'licensePlate' => trim($sheet[$r][26]),
                                        'palletsaccount_name'=>trim($sheet[$r][26]).' - '.trim($sheet[$r][25]),
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
