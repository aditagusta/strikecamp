<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                "username" => "pusat",
                "password" => Hash::make('123'),
                "nama_user" => "Lorem Pusat",
                "level" => "1",
                "telepon" => "001",
                "id_cabang" => 1
            ],
            [
                "username" => "cabang",
                "password" => Hash::make('123'),
                "nama_user" => "Lorem Cabang",
                "level" => "2",
                "telepon" => "002",
                "id_cabang" => 2
            ],
        ];
        DB::table('tbl_user')->insert($data);
    }
}
