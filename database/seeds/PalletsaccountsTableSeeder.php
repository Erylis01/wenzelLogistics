<?php

use App\Palletsaccount;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;


class PalletsaccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        DB::table('palletsaccounts')->delete();
        Palletsaccount::firstOrCreate(array(
            'id' => 1,
            'name' => 'WENZEL',
            'nickname'=>'WENZEL',
            'type' => 'Other',
        ));
        Palletsaccount::firstOrCreate(array(
            'id' => 2,
            'name' => 'LOADING',
            'nickname' => 'LOADING',
            'type' => 'Other',
        ));

        Palletsaccount::firstOrCreate(array(
            'id' => 3,
            'name' => 'UNLOADING',
            'nickname' => 'UNLOADING',
            'type' => 'Other',
        ));


        $this->importDataAll();


//        Palletsaccount::firstOrCreate(array(
//            'id' => 4,
//            'name' => 'ALDI DAG',
//            'nickname' => 'ALDI DAG',
//            'type' => 'Network',
//        ));
//
//        Palletsaccount::firstOrCreate(array(
//            'id' => 5,
//            'name' => 'ALDI DOM',
//            'nickname' => 'ALDI DOM',
//            'type' => 'Network',
//        ));
//
//        Palletsaccount::firstOrCreate(array(
//            'id' => 6,
//            'name' => 'ALDI SWB',
//            'nickname' => 'ALDI SWB',
//            'type' => 'Network',
//        ));
//
//        Palletsaccount::firstOrCreate(array(
//            'id' => 7,
//            'name' => 'Arinthod',
//            'nickname' => 'Arinthod',
//            'type' => 'Network',
//        ));
//
//        Palletsaccount::firstOrCreate(array(
//            'id' => 8,
//            'name' => 'Benoit & Valerie',
//            'nickname' => 'Benoit & Valerie',
//            'type' => 'Network',
//        ));
//
//        Palletsaccount::firstOrCreate(array(
//            'id' => 9,
//            'name' => 'Bonduelle F80',
//            'nickname' => 'Bonduelle F80',
//            'type' => 'Network',
//        ));
//
//        Palletsaccount::firstOrCreate(array(
//            'id' => 10,
//            'name' => 'Dachser F51',
//            'nickname' => 'Dachser F51',
//            'type' => 'Network',
//        ));
//
//        Palletsaccount::firstOrCreate(array(
//            'id' => 11,
//            'name' => 'DPL',
//            'nickname' => 'DPL',
//            'type' => 'Network',
//        ));
//
//        Palletsaccount::firstOrCreate(array(
//            'id' => 12,
//            'name' => 'ECL Wolfurt',
//            'nickname' => 'ECL Wolfurt',
//            'type' => 'Network',
//        ));
//
//        Palletsaccount::firstOrCreate(array(
//            'id' => 13,
//            'name' => 'HOFER Trumau',
//            'nickname' => 'HOFER Trumau',
//            'type' => 'Network',
//        ));
//
//        Palletsaccount::firstOrCreate(array(
//            'id' => 14,
//            'name' => 'Impex-EUX',
//            'nickname' => 'Impex-EUX',
//            'type' => 'Network',
//        ));
//
//        Palletsaccount::firstOrCreate(array(
//            'id' => 15,
//            'name' => 'Impex-EUY',
//            'nickname' => 'Impex-EUY',
//            'type' => 'Network',
//        ));
//
//        Palletsaccount::firstOrCreate(array(
//            'id' => 16,
//            'name' => 'PFM - FR',
//            'nickname' => 'PFM - FR',
//            'type' => 'Network',
//        ));
//
//        Palletsaccount::firstOrCreate(array(
//            'id' => 17,
//            'name' => 'Schefknecht',
//            'nickname' => 'Schefknecht',
//            'type' => 'Network',
//        ));
//
//        Palletsaccount::firstOrCreate(array(
//            'id' => 18,
//            'name' => 'SPAR Wels',
//            'nickname' => 'SPAR Wels',
//            'type' => 'Network',
//        ));
//
//        Palletsaccount::firstOrCreate(array(
//            'id' => 19,
//            'name' => 'Systempo AT',
//            'nickname' => 'Systempo AT',
//            'type' => 'Network',
//        ));
//
//        Palletsaccount::firstOrCreate(array(
//            'id' => 20,
//            'name' => 'Team Tex',
//            'nickname' => 'Team Tex',
//            'type' => 'Network',
//        ));
//
//        Palletsaccount::firstOrCreate(array(
//            'id' => 21,
//            'name' => 'TO-MA',
//            'nickname' => 'TO-MA',
//            'type' => 'Network',
//        ));
//
//        Palletsaccount::firstOrCreate(array(
//            'id' => 22,
//            'name' => 'Wildenhofer Salzburg',
//            'nickname' => 'Wildenhofer Salzburg',
//            'type' => 'Network',
//        ));

    }

    public function importDataAll()
{
    $path = 'resources/assets/excel/ListAccounts';
    $files = File::allFiles($path);
    foreach ($files as $file) {
        if (strpos((string)$file, '.xls') !== false) {
            Excel::load($file, function ($reader) {
                if (!empty($reader)) {
                    $reader->noHeading();
                    foreach ($reader->get() as $sheet) {
                        for ($r = 1; $r < count($sheet); $r++) {
                            $accountTest = Palletsaccount::where('name', '=', trim($sheet[$r][0]))->orWhere('nickname', '=', trim($sheet[$r][0]))->first();
                            if ($accountTest == null && trim($sheet[$r][0]) <> '') {
                                //not double
                                $k = count(Palletsaccount::get()) + 1;

                                Palletsaccount::firstOrCreate([
                                    'id' => $k,
                                    'name' => trim($sheet[$r][0]),
                                    'nickname' => trim($sheet[$r][0]),
                                    'type'=>'Network',
                                    'adress' => trim($sheet[$r][1]),
                                    'zipcode' => trim(substr($sheet[$r][2], 0, 20)),
                                    'town' => trim($sheet[$r][3]),
                                    'country' => trim($sheet[$r][4]),
                                    'phone' => trim(substr(str_replace(' ', '', $sheet[$r][5]), 0, 20)),
                                    'fax' => trim(substr(str_replace(' ', '', $sheet[$r][6]), 0, 20)),
                                    'email' => trim($sheet[$r][7]),
                                    'details' => trim($sheet[$r][8]),
                                ]);
                            }
                        }
                    }
                }
            }, 'ASCII');
        }
    }
}
}


