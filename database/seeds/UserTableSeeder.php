<?php

use Illuminate\Database\Seeder;
use App\User;

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
            'firstname'=>'test',
            'username' => 'test',
            'email'    => 'test@test.com',
            'password' => Hash::make('test'),
        ));
    }
}
