<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FundingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('fundings')->insert([[
            'title' => 'Warung Kopi Kita: Satu Cangkir Inspirasi',
            'campaign' => 'Bantu perjuangan UKM untuk maju dan berkembang dengan berkontribusi pada kampanye penggalangan dana EmpowerUKM. Kami bertekad untuk meningkatkan daya saing dan peluang pertumbuhan bisnis kecil. Dukungan Anda akan membantu mewujudkan mimpi-mimpi kreatif para pengusaha lokal. Aplikasi kami, EmpowerUKM, memberikan platform aman dan transparan untuk menjalin ikatan yang berarti antara UKM yang berpotensi dengan para dermawan yang peduli. Mari bersama-sama memberdayakan UKM Indonesia dan menciptakan dampak positif bagi masyarakat. Jadilah bagian dari perubahan dan ikut berinvestasi dalam masa depan UKM yang cerah!',
            'fund_raise_use' => 'Digunakan untuk membangun Perelatan Kopi baru ',
            'image' => 'https://freshmart.oss-ap-southeast-5.aliyuncs.com/Growup/dummy1.jpg',
            'benefit' => "+6285215125",
            'target_amount' => 10000000,
            'current_amount' =>   5000000,
            "day_left" => 15,
            "ukm_id" => 1,
            "status" => false
        ], [
            'title' => 'CraftVibes: Memberdayakan Pengrajin',
            'campaign' => 'Bantu perjuangan UKM untuk maju dan berkembang dengan berkontribusi pada kampanye penggalangan dana EmpowerUKM. Kami bertekad untuk meningkatkan daya saing dan peluang pertumbuhan bisnis kecil. Dukungan Anda akan membantu mewujudkan mimpi-mimpi kreatif para pengusaha lokal. Aplikasi kami, EmpowerUKM, memberikan platform aman dan transparan untuk menjalin ikatan yang berarti antara UKM yang berpotensi dengan para dermawan yang peduli. Mari bersama-sama memberdayakan UKM Indonesia dan menciptakan dampak positif bagi masyarakat. Jadilah bagian dari perubahan dan ikut berinvestasi dalam masa depan UKM yang cerah!',
            'fund_raise_use' => 'Digunakan untuk membeli bahan untuk kerajinan',
            'image' => 'https://freshmart.oss-ap-southeast-5.aliyuncs.com/Growup/dummy2.jpg',
            'benefit' => "+6285215125",
            'target_amount' => 40000000,
            'current_amount' => 5000000,
            "day_left" => 12,
            "ukm_id" => 2,
            "status" => false

        ]]);
    }
}
