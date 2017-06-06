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
            'type' => 'Warehouse',
            'theoricalNumberPallets' => '15',
            'realNumberPallets' => '10',
        ));

        Palletsaccount::create(array(
            'id' => 2,
            'name' => 'account2',
            'type' => 'Warehouse',
            'theoricalNumberPallets' => '22',
            'realNumberPallets' => '20',
        ));

        Palletsaccount::create(array(
            'id' => 3,
            'name' => 'account3',
            'type' => 'Warehouse',
            'theoricalNumberPallets' => '15',
            'realNumberPallets' => '20',
        ));

        Palletsaccount::create(array(
            'id' => 4,
            'name' => 'account44',
            'type' => 'loading',
        ));

        Palletsaccount::create(array(
            'id' => 5,
            'name' => 'account5',
            'type' => 'unloading',
        ));
        Palletsaccount::create(array(
            'id' => 6,
            'name' => 'account6',
            'type' => 'wenzel',
        ));
        Palletsaccount::create(array(
            'id' => 7,
            'name' => 'account7',
            'type' => 'other',
        ));
        Palletsaccount::create(array(
            'id' => 8,
            'name' => 'account8',
            'type' => 'Warehouse',
        ));
        Palletsaccount::create(array(
            'id' => 9,
            'name' => 'account9',
            'type' => 'Warehouse',
        ));
        Palletsaccount::create(array(
            'id' => 10,
            'name' => 'account10',
            'type' => 'Warehouse',
        ));
        Palletsaccount::create(array(
            'id' => 11,
            'name' => 'account11',
            'type' => 'Warehouse',
        ));
        Palletsaccount::create(array(
            'id' => 12,
            'name' => 'account12',
            'type' => 'Warehouse',
        ));
    }
}
