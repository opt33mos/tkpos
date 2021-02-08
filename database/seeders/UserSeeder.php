<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Foundation\Auth\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Juan Dela Cruz',
            'username' => 'admin super',
            'role' => 'admin',
            'password' => bcrypt('123456')
        ]);
    }
}
