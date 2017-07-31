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
        //errors on normal transfers
        Error::create([
            'id'=>1,
            'name'=>'DW-WD_atLeastOne',
            'description'=>"When there is a Deposit-Withdrawal transfer, there must be at least one Withdrawal-Deposit transfer and inversely"
        ]);
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
            'name'=>'DW-WD_notSameNumber',
            'description'=>"on Deposit-Withdrawal transfers and Withdrawal-Deposit transfers, the pallets numbers have to match"
        ]);
        //errors on correcting transfers
//        Error::create([
//            'id'=>6,
//            'name'=>'SP-PS_notEnoughTransfers',
//            'description'=>"There must be same number of SP and PS transfers"
//        ]);
        Error::create([
            'id'=>5,
            'name'=>'Correcting_notEnoughTransfers',
            'description'=>"There must be an even number of correcting transfers"
        ]);
        Error::create([
            'id'=>6,
            'name'=>'Correcting_notCompleteNormal',
            'description'=>"The correcting transfer doesn't complete the normal transfer"
        ]);

    }
}
