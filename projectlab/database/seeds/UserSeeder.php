<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Adminidea',
                'email' => 'admin@admin',
                'dob' => '2020-11-12',
                'address' => 'sfasfasdfasfasdfasdfasfas',
                'password' => Hash::make('admin1234'),
                'role' => 'admin',
                'gender' => 'male',
            ],
            [
                'name' => 'Memberidea',
                'email' => 'member@member',
                'dob' => '2020-11-12',
                'address' => 'sfasfasdfasfasdfasdfasfas',
                'password' => Hash::make('member1234'),
                'role' => 'member',
                'gender' => 'male',
            ]
        ]);
    }
}
