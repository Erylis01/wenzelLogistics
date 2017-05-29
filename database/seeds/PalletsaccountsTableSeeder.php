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
        DB::table('palletsaccounts')->delete();
        Palletsaccount::create(array(
            'id' => 1,
            'name' => 'account1',
            'numberPallets' => '11',
        ));
        Palletsaccount::create(array(
            'id' => 2,
            'name' => 'account2',
            'numberPallets' => '22',
        ));
        Palletsaccount::create(array(
            'id' => 3,
            'name' => 'account3',
            'numberPallets' => '33',
        ));
        Palletsaccount::create(array(
        'id' => 4,
        'name' => 'account4',
        'numberPallets' => '44',
    ));
        Palletsaccount::create(array(
            'id' => 5,
            'name' => 'account5',
            'numberPallets' => '55',
        ));
        Palletsaccount::create(array(
            'id' => 6,
            'name' => 'account6',
            'numberPallets' => '66',
        ));
        Palletsaccount::create(array(
            'id' => 7,
            'name' => 'account7',
            'numberPallets' => '77',
        ));
        Palletsaccount::create(array(
            'id' => 8,
            'name' => 'account8',
            'numberPallets' => '88',
        ));
        Palletsaccount::create(array(
            'id' => 9,
            'name' => 'account9',
            'numberPallets' => '99',
        ));
        Palletsaccount::create(array(
            'id' => 10,
            'name' => 'account10',
            'numberPallets' => '1010',
        ));
        Palletsaccount::create(array(
            'id' => 11,
            'name' => 'account11',
            'numberPallets' => '1111',
        ));
        Palletsaccount::create(array(
            'id' => 12,
            'name' => 'account12',
            'numberPallets' => '1212',
        ));
    }
}
