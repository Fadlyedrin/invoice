<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class IndonesiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Path ke file all.sql
        $sqlFile = base_path('vendor/laravolt/indonesia/database/all.sql');

        // Cek apakah file ada
        if (File::exists($sqlFile)) {
            // Baca isi file
            $sql = File::get($sqlFile);

            // Matikan foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Eksekusi semua query SQL
            DB::unprepared($sql);

            // Nyalakan foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $this->command->info('Indonesia database tables seeded successfully!');
        } else {
            $this->command->error('File all.sql tidak ditemukan. Pastikan laravolt/indonesia sudah terinstall!');
        }
    }
}
