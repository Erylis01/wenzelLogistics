<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        Model::unguard();
//         $this->call(UserTableSeeder::class);
//        $this->call(LoadingTableSeeder::class);
//        $this->call(PalletsaccountsTableSeeder::class);
//        $this->call(WarehouseTableSeeder::class);
//        $this->call(TruckTableSeeder::class);
        $this->call(ErrorTableSeeder::class);

    }
}
