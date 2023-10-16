<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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
            'id' => 1,
            'full_name' => 'admin',
            'email' => 'admin@admin.com',
            'phone' => '0988888888',
            'password' => Hash::make('admin'),
            'active'  => 1
        ]);
    }
}
