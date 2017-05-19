<?php

use App\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('warehouses')->delete();
        Warehouse::create(array(
            'id'=>1,
            'name'     => 'Fakturiert',
            'adresse'=>'testAdresse',
            'palanzahl' => 1,
            'idLoading'=>5,
        ));
        Warehouse::create(array(
        'id'=>2,
        'name'     => 'Verschenkt',
        'adresse'=>'testAdresse2',
        'palanzahl' =>2,
        'idLoading'=>3,
    ));

        Warehouse::create(array(
            'id'=>3,
            'name'     => 'ECL Wolfurt',
            'adresse'=>'testAdresse2',
            'palanzahl' => 3,
            'idLoading'=>3,
        ));
        Warehouse::create(array(
            'id'=>44,
            'name'     => 'Systempo AT',
            'adresse'=>'testAdresse2',
            'palanzahl' => 44,
            'idLoading'=>3,
        ));

        Warehouse::create(array(
            'id'=>4,
            'name'     => 'Benoit & Valerie',
            'adresse'=>'testAdresse2',
            'palanzahl' => 4,
            'idLoading'=>3,
        ));
        Warehouse::create(array(
            'id'=>5,
            'name'     => 'PFM-FR',
            'adresse'=>'testAdresse2',
            'palanzahl' => 5,
            'idLoading'=>3,
        ));
        Warehouse::create(array(
            'id'=>6,
            'name'     => 'Team Tex',
            'adresse'=>'testAdresse2',
            'palanzahl' => 6,
            'idLoading'=>3,
        ));
        Warehouse::create(array(
            'id'=>7,
            'name'     => 'ALDI SWB',
            'adresse'=>'testAdresse2',
            'palanzahl' => 7,
            'idLoading'=>3,
        ));
        Warehouse::create(array(
        'id'=>8,
        'name'     => 'ALDI DAG',
        'adresse'=>'testAdresse2',
        'palanzahl' => 8,
        'idLoading'=>3,
    ));
        Warehouse::create(array(
            'id'=>9,
            'name'     => 'ALDI DOM',
            'adresse'=>'testAdresse2',
            'palanzahl' => 9,
            'idLoading'=>3,
        ));
        Warehouse::create(array(
            'id'=>10,
            'name'     => 'Dachser F51',
            'adresse'=>'testAdresse2',
            'palanzahl' => 10,
            'idLoading'=>3,
        ));
        Warehouse::create(array(
            'id'=>11,
            'name'     => 'Impex-EUY',
            'adresse'=>'testAdresse2',
            'palanzahl' => 11,
            'idLoading'=>3,
        ));
        Warehouse::create(array(
            'id'=>12,
            'name'     => 'Bonduelle F80',
            'adresse'=>'testAdresse2',
            'palanzahl' => 12,
            'idLoading'=>3,
        ));
        Warehouse::create(array(
            'id'=>13,
            'name'     => 'Schefknecht',
            'adresse'=>'testAdresse2',
            'palanzahl' => 13,
            'idLoading'=>3,
        ));
        Warehouse::create(array(
            'id'=>14,
            'name'     => 'Wildenhofer Salzburg',
            'adresse'=>'testAdresse2',
            'palanzahl' => 14,
            'idLoading'=>3,
        ));
        Warehouse::create(array(
            'id'=>15,
            'name'     => 'Impex-EUX',
            'adresse'=>'testAdresse2',
            'palanzahl' => 15,
            'idLoading'=>3,
        ));
        Warehouse::create(array(
            'id'=>16,
            'name'     => 'Arinthod',
            'adresse'=>'testAdresse2',
            'palanzahl' => 16,
            'idLoading'=>3,
        ));
        Warehouse::create(array(
            'id'=>17,
            'name'     => 'Spar Wels',
            'adresse'=>'testAdresse2',
            'palanzahl' => 17,
            'idLoading'=>3,
        ));

    }
}
