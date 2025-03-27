<?php

namespace Database\Seeders;

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
                'foto' => 'profiles/taylorswiftred.jpeg'
            ],
            [
                'username' => 'admin2',
                'nama_user' => 'Admin Dua',
                'password' => Hash::make('admin456'),
                'foto' => 'profiles/admin2.jpeg'
            ],
            [
                'username' => 'admin3',
                'nama_user' => 'Admin Tiga',
                'password' => Hash::make('admin789'),
                'foto' => 'profiles/admin3.png'
            ],
        ]);
    }
}
