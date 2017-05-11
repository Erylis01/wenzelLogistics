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
        User::create(array(
            'lastname'     => 'test',
            'firstname'=>'Pierre',
            'username' => 'Erylis01',
            'initials'=>'tePi',
            'email'    => 'coquerelpierre@gmail.com',
            'password' => Hash::make('test'),
        ));
        User::create(array(
            'lastname'     => 'test',
            'firstname'=>'Camille',
            'username' => 'Camille',
            'initials'=>'teCa',
            'email'    => 'camillesamain.56@gmail.com',
            'password' => Hash::make('test'),
        ));
        User::create(array(
            'lastname'     => 'Alala',
            'firstname'=>'A',
            'username' => 'A',
            'initials'=>'AAl',
            'email'    => 'A@gmail.com',
            'password' => Hash::make('AAAAAA'),
        ));
    }
}
