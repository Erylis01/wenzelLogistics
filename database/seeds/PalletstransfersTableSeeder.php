<?php

use App\Palletstransfer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PalletstransfersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('palletstransfers')->delete();
        Palletstransfer::create(array(
            'id' => 1,
            'palletsNumber' => '-11',
            'palletsAccount' => 'account1',
            'loading_atrnr' => 2278849,
            'date' => '2017-04-20',
        ));
        Palletstransfer::create(array(
            'id' => 2,
            'palletsNumber' => '22',
            'palletsAccount' => 'account2',
            'loading_atrnr' => 2278863,
            'date' => '2017-04-12',
        ));
        Palletstransfer::create(array(
            'id' => 3,
            'palletsNumber' => '-33',
            'palletsAccount' => 'account3',
            'loading_atrnr' => 2277925,
            'date' => '2017-02-25',
        ));
        Palletstransfer::create(array(
            'id' => 4,
            'palletsNumber' => '44',
            'palletsAccount' => 'account4',
            'loading_atrnr' => 2277925,
            'date' => '2017-05-03',
        ));
        Palletstransfer::create(array(
            'id' => 5,
            'palletsNumber' => '-55',
            'palletsAccount' => 'account5',
            'loading_atrnr' => 2278850,
            'date' => '2017-04-14',
        ));
        Palletstransfer::create(array(
            'id' => 6,
            'palletsNumber' => '66',
            'palletsAccount' => 'account6',
            'loading_atrnr' => 2278850,
            'date' => '2017-05-19',
        ));
    }
}
