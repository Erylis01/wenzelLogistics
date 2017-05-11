<?php

use Illuminate\Database\Seeder;
use App\Pallet;

class PalletTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pallets')->delete();
        Pallet::create(array(
            'id' => 1,
            'ladedatum' => '2017-05-11',
            'entladedatum' => '2017-05-11',
            'disp' => 'test',
            'atrNr' => 1,
            'referenz' => 'test',
            'auftraggeber' => 'test',
            'beladestelle' => 'test',
            'landB' => 'test',
            'plzB' => 1,
            'ortB' => 'test',
            'entladestelle' => 'test',
            'landE' => 'test',
            'plzE' => 1,
            'ortE' => 'test',
            'anzahl' => 'test',
            'TRY1' => 'test',
            'TRY2' => 'test',
            'TRY3' => 'test',
            'ware' => 1,
            'gewicht' => 1,
            'umsatz' => 1,
            'aufwand' => 1,
            'db' => 1,
            'trp' => 1,
            'pt' => 'test',
            'subfrächter' => 'test',
            'pal' => 'test',
            'imKlärung' => 'test',
            'palTauschVereinbart' => true,

        ));


//        $faker = Faker::create();
//        foreach (range(1,10) as $index) {
//            DB::table('pallets')->insert([
//
//            'ladedatum'=> $faker->date,
//                'entladedatum'=> $faker->date,
//            'disp'=> $faker->firstName,
//            'atrNr'=> $faker->randomNumber,
//            'referenz'=> $faker->lexify,
//            'auftraggeber'=> $faker->lexify,
//            'beladestelle'=> $faker->lexify,
//                'landB'=> $faker->lexify,
//                'plzB'=> $faker->randomNumber,
//                'ortB'=> $faker->lexify,
//                'entladestelle'=> $faker->lexify,
//                'landE'=> $faker->lexify,
//                'plzE'=> $faker->randomNumber,
//                'ortE'=> $faker->lexify,
//                'anzahl'=> $faker->randomNumber,
//                'TRY1'=> $faker->lexify,
//                'TRY2'=> $faker->lexify,
//                'TRY3'=> $faker->randomNumber,
//                'ware'=> $faker->lexify,
//                'gewicht'=> $faker->randomNumber,
//                'umsatz'=> $faker->randomNumber,
//                'aufwand'=> $faker->randomNumber,
//                'db'=> $faker->randomNumber,
//                'trp'=> $faker->randomNumber,
//                'pt'=> $faker->lexify,
//                'subfrächter'=> $faker->lexify,
//                'pal'=> $faker->lexify,
//                'imKlärung'=> $faker->lexify,
//                'palTauschVereinbart'=> $faker->lexify,
//
//            ]);
//        }
    }
}
