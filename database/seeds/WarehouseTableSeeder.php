<?php

use App\Warehouse;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            'id' => 1,
            'name' => 'warehouse1',
            'adress' => 'adress1',
            'zipcode' => 1,
            'town' => 'town1',
            'country' => 'country1',
            'phone'=>'phone1',
            'fax'=>'fax1',
            'email'=>'email1',
            'namecontact'=>'contact1',
        ));
        Warehouse::create(array(
            'id' => 2,
            'name' => 'warehouse2',
            'adress' => 'adress2',
            'zipcode' => 2,
            'town' => 'town2',
            'country' => 'country2',
            'phone'=>'phone2',
            'fax'=>'fax2',
            'email'=>'email2',
            'namecontact'=>'contact2',
        ));

        Warehouse::create(array(
            'id' => 3,
            'name' => 'warehouse3',
            'adress' => 'adress3',
            'zipcode' => 3,
            'town' => 'town3',
            'country' => 'country3',
            'phone'=>'phone3',
            'fax'=>'fax3',
            'email'=>'email3',
            'namecontact'=>'contact3',
        ));

        Warehouse::create(array(
            'id' => 44,
            'name' => 'warehouse44',
            'adress' => 'adress44',
            'zipcode' => 44,
            'town' => 'town44',
            'country' => 'country44',
            'phone'=>'phone44',
            'fax'=>'fax44',
            'email'=>'email44',
            'namecontact'=>'contact44',
        ));


    }
}
