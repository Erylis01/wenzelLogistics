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
        Error::create([
            'id'=>1,
            'name'=>'DW-WD_notSameNumber',
            'description'=>"on Deposit-Withdrawal transfers and Withdrawal-Deposit transfers, the pallets numbers have to match"
        ]);
        Error::create([
            'id'=>2,
            'name'=>'DW-WD_notNumberLoadingOrder',
            'description'=>"on Deposit-Withdrawal transfers and Withdrawal-Deposit transfers, the pallets numbers have to match the pallets number written on the loading order"
        ]);
    }
}
