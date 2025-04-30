<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinceSeeder extends Seeder
{
    public function run()
    {
        $json = file_get_contents(database_path('seeders/json/provinces.json'));
        $provinces = json_decode($json, true);

        foreach ($provinces as $province) {
            $province['code'] = $province['id'];
            $province['meta'] = json_encode($province['meta']);
            DB::table('indonesia_provinces')->insert($province);
        }
    }
}