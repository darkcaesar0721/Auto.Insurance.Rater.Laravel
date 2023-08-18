<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create([
            'name' => "James Ara",
            'password' => bcrypt(123123),
            'email' => "insurausa@gmail.com"
        ]);

        \App\User::create([
            'name' => "Bertug",
            'password' => bcrypt(123123),
            'email' => "test@insura.app"
        ]);
    }
}
