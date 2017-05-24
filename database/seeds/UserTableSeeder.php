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

        User::firstOrCreate(array(
            'id'=>1,
            'lastname'     => 'test',
            'firstname'=>'Pierre',
            'username' => 'Erylis01',
            'initials'=>'tePi',
            'email'    => 'coquerelpierre@gmail.com',
            'password' => Hash::make('test'),
            'activated'=> true,
            'email_token'=>'1234567890'

        ));
        User::firstOrCreate(array(
            'id'=>2,
            'lastname'     => 'test',
            'firstname'=>'Camille',
            'username' => 'Camille',
            'initials'=>'teCa',
            'email'    => 'camillesamain.56@gmail.com',
            'password' => Hash::make('test'),
            'activated'=> true,
            'email_token'=>'1234657089'

        ));
        User::firstOrCreate(array(
            'id'=>3,
            'lastname'     => 'Alala',
            'firstname'=>'A',
            'username' => 'A',
            'initials'=>'AAl',
            'email'    => 'A@gmail.com',
            'password' => Hash::make('AAAAAA'),
            'activated'=> true,
            'email_token'=>'0258369147'
        ));
    }
}
