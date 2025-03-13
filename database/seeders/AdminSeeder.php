<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    public function run()
{
    DB::table('tb_user')->insert([
        [
            'username' => 'admin',
            'nama_user' => 'Admin',
            'password' => Hash::make('admin123'),
        ],
    ]);
}
}
