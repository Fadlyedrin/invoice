<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    public function run()
    {
        $json = file_get_contents(database_path('seeders/json/cities.json'));
        $cities = json_decode($json, true);

        foreach ($cities as $city) {
            $city['code'] = $city['id'];
            $city['meta'] = json_encode($city['meta']);
            DB::table('indonesia_cities')->insert($city);
        }
    }
}