<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UkmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ukms')->insert([[
            'name' => 'Kopi Kita',
            'email' => 'kopi_kita@gmail.com',
            'business_name' => 'Kopi kita',
            'phoneNumber' => "+6285215125",
            'bio' => "Kopi kita",
            'province' => "Jawa barat",
            "city" => "Bandung"
        ],[
            'name' => 'Budi',
            'email' => 'CraftVibes@gmail.com',
            'business_name' => 'CraftVibes',
            'phoneNumber' => "+6285215125",
            'bio' => "CraftVibes Pengrajin tangan",
            'province' => "Jawa barat",
            "city" => "Bandung"

        ]]);
    }
}
