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
            'type' => 'Network',
        ));

        Palletsaccount::create(array(
            'id' => 2,
            'name' => 'account2',
            'type' => 'Network',
        ));

        Palletsaccount::create(array(
            'id' => 3,
            'name' => 'account3',
            'type' => 'Network',
        ));

        Palletsaccount::create(array(
            'id' => 4,
            'name' => 'Loading',
            'type' => 'Other',
        ));

        Palletsaccount::create(array(
            'id' => 5,
            'name' => 'Unloading',
            'type' => 'Other',
        ));
        Palletsaccount::create(array(
            'id' => 6,
            'name' => 'Wenzel',
            'type' => 'Other',
        ));
        Palletsaccount::create(array(
            'id' => 7,
            'name' => 'Stock',
            'type' => 'Other',
        ));
        Palletsaccount::create(array(
            'id' => 8,
            'name' => 'account8',
            'type' => 'Network',
        ));
        Palletsaccount::create(array(
            'id' => 9,
            'name' => 'account9',
            'type' => 'Network',
        ));
        Palletsaccount::create(array(
            'id' => 10,
            'name' => 'account10',
            'type' => 'Network',
        ));
        Palletsaccount::create(array(
            'id' => 11,
            'name' => 'account11',
            'type' => 'Network',
        ));
        Palletsaccount::create(array(
            'id' => 12,
            'name' => 'account12',
            'type' => 'Network',
        ));
    }
}
