<?php

use App\Palletsaccount;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            'name' => 'STOCK',
            'type' => 'Other',
        ));
        Palletsaccount::firstOrCreate(array(
            'id' => 2,
            'name' => 'LOADING',
            'type' => 'Other',
        ));

        Palletsaccount::firstOrCreate(array(
            'id' => 3,
            'name' => 'UNLOADING',
            'type' => 'Other',
        ));

        Palletsaccount::firstOrCreate(array(
            'id' => 4,
            'name' => 'ALDI DAG',
            'type' => 'Network',
        ));

        Palletsaccount::firstOrCreate(array(
            'id' => 5,
            'name' => 'ALDI DOM',
            'type' => 'Network',
        ));

        Palletsaccount::firstOrCreate(array(
            'id' => 6,
            'name' => 'ALDI SWB',
            'type' => 'Network',
        ));

        Palletsaccount::firstOrCreate(array(
            'id' => 7,
            'name' => 'Arinthod',
            'type' => 'Network',
        ));

        Palletsaccount::firstOrCreate(array(
            'id' => 8,
            'name' => 'Benoit & Valerie',
            'type' => 'Network',
        ));

        Palletsaccount::firstOrCreate(array(
            'id' => 9,
            'name' => 'Bonduelle F80',
            'type' => 'Network',
        ));

        Palletsaccount::firstOrCreate(array(
            'id' => 10,
            'name' => 'Dachser F51',
            'type' => 'Network',
        ));

        Palletsaccount::firstOrCreate(array(
            'id' => 11,
            'name' => 'ECL Wolfurt',
            'type' => 'Network',
        ));

        Palletsaccount::firstOrCreate(array(
            'id' => 12,
            'name' => 'Impex-EUX',
            'type' => 'Network',
        ));

        Palletsaccount::firstOrCreate(array(
            'id' => 13,
            'name' => 'Impex-EUY',
            'type' => 'Network',
        ));

        Palletsaccount::firstOrCreate(array(
            'id' => 14,
            'name' => 'PFM - FR',
            'type' => 'Network',
        ));

        Palletsaccount::firstOrCreate(array(
            'id' => 15,
            'name' => 'Schefknecht',
            'type' => 'Network',
        ));

        Palletsaccount::firstOrCreate(array(
            'id' => 16,
            'name' => 'SPAR Wels',
            'type' => 'Network',
        ));

        Palletsaccount::firstOrCreate(array(
            'id' => 17,
            'name' => 'Systempo AT',
            'type' => 'Network',
        ));

        Palletsaccount::firstOrCreate(array(
            'id' => 18,
            'name' => 'Team Tex',
            'type' => 'Network',
        ));

        Palletsaccount::firstOrCreate(array(
            'id' => 19,
            'name' => 'Wildenhofer Salzburg',
            'type' => 'Network',
        ));

    }
}
