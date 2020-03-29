<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'firstname' => 'Jacek',
            'lastname' => 'Sławiński',
            'email' => 'jacek_slawinski@poczta.onet.pl',
            'password' => Hash::make('qwerty123')
        ]);
    }
}
