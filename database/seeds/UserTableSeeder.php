<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        User::firstOrCreate([
            'id'=>1,
            'lastname' => 'SAMAIN',
            'firstname'=>'Camille',
            'username' => 'CamilleS',
            'initials'=>'teCa',
            'email'    => 'camillesamain.56@gmail.com',
            'password' => Hash::make('wenzel56'),
            'activated'=> true,
            'email_token'=>'1234657089'
        ]);

    }
}
