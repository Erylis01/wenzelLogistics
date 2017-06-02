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
            'theoricalNumberPallets' => '15',
        ));

        Palletsaccount::create(array(
            'id' => 2,
            'name' => 'account2',
            'theoricalNumberPallets' => '22',
        ));

        Palletsaccount::create(array(
            'id' => 3,
            'name' => 'account3',
            'theoricalNumberPallets' => '15',
        ));

        Palletsaccount::create(array(
        'id' => 4,
        'name' => 'account4',
    ));
        Palletsaccount::create(array(
            'id' => 5,
            'name' => 'account5',
        ));
        Palletsaccount::create(array(
            'id' => 6,
            'name' => 'account6',
        ));
        Palletsaccount::create(array(
            'id' => 7,
            'name' => 'account7',
        ));
        Palletsaccount::create(array(
            'id' => 8,
            'name' => 'account8',
        ));
        Palletsaccount::create(array(
            'id' => 9,
            'name' => 'account9',
        ));
        Palletsaccount::create(array(
            'id' => 10,
            'name' => 'account10',
        ));
        Palletsaccount::create(array(
            'id' => 11,
            'name' => 'account11',
        ));
        Palletsaccount::create(array(
            'id' => 12,
            'name' => 'account12',
        ));
    }
}
