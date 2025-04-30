<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistrictSeeder extends Seeder
{
    public function run()
    {
        $json = file_get_contents(database_path('seeders/json/districts.json'));
        $districts = json_decode($json, true);

        foreach ($districts as $district) {
            $district['code'] = $district['id'];
            $district['meta'] = json_encode($district['meta']);
            DB::table('indonesia_districts')->insert($district);
        }
    }
}