<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\JenisTableSeeder;
use Database\Seeders\LokasiAsalTableSeeder;
use Database\Seeders\TujuanSampahTableSeeder;
use Database\Seeders\SampahTerkelolaSeeder;
use Database\Seeders\SampahDiserahkanSeeder;
use Database\Seeders\DokumenTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // core master data
        $this->call(UsersTableSeeder::class);
        $this->call(JenisTableSeeder::class);
        $this->call(LokasiAsalTableSeeder::class);
        $this->call(TujuanSampahTableSeeder::class);

        // dokumen
        $this->call(DokumenTableSeeder::class);

        // transaction data
        $this->call(SampahTerkelolaSeeder::class);
        $this->call(SampahDiserahkanSeeder::class);
    }
}
