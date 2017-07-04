<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Error;

class ErrorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('errors')->delete();
//        Error::create([
//            'id'=>1,
//            'name'=>'DW-WD_notSameNumber',
//            'description'=>"on Deposit-Withdrawal transfers and Withdrawal-Deposit transfers, the pallets numbers have to match"
//        ]);
        Error::create([
            'id'=>2,
            'name'=>'DW-WD_notNumberLoadingOrder',
            'description'=>"on Deposit-Withdrawal transfers and Withdrawal-Deposit transfers, the pallets numbers have to match the pallets number written on the loading order"
        ]);
        Error::create([
            'id'=>3,
            'name'=>'Donly-Wonly_notSameNumber',
            'description'=>"When you depose pallets to an extra pallets account you have to have taken pallets from an other pallets account and inversely"
        ]);
        Error::create([
            'id'=>4,
            'name'=>'Correcting_notCompleteNormal',
            'description'=>"The correcting transfer doesn't complete the normal transfer"
        ]);
        Error::create([
            'id'=>5,
            'name'=>'SP-PS_notSameNumber',
            'description'=>"on Sale-Purchase transfers and Purchase-Sale transfers, the pallets numbers have to match"
        ]);
    }
}
