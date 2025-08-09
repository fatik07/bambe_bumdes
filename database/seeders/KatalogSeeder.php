<?php

namespace Database\Seeders;

use App\Models\Katalog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $katalogs = [
            [
                'nama' => 'Produk Unggulan',
                'deskripsi' => 'Berbagai produk unggulan dari BUMDes yang berkualitas tinggi dan terpercaya untuk memenuhi kebutuhan masyarakat'
            ],
            [
                'nama' => 'Layanan Jasa',
                'deskripsi' => 'Beragam layanan jasa profesional yang disediakan BUMDes untuk mendukung kebutuhan bisnis dan pribadi masyarakat'
            ],
            [
                'nama' => 'Produk Digital',
                'deskripsi' => 'Inovasi produk digital terdepan untuk mendukung transformasi digital di era modern'
            ],
            [
                'nama' => 'Kemitraan Bisnis',
                'deskripsi' => 'Program kemitraan strategis untuk mengembangkan bisnis bersama dan menciptakan nilai tambah'
            ],
            [
                'nama' => 'Edukasi & Pelatihan',
                'deskripsi' => 'Program edukasi dan pelatihan komprehensif untuk meningkatkan kapasitas dan keterampilan masyarakat'
            ],
            [
                'nama' => 'Konsultasi Bisnis',
                'deskripsi' => 'Layanan konsultasi bisnis profesional untuk membantu pengembangan usaha dan strategi bisnis yang tepat'
            ]
        ];

        foreach ($katalogs as $katalog) {
            Katalog::create([
                'nama' => $katalog['nama'],
                'slug' => Str::slug($katalog['nama']),
                'deskripsi' => $katalog['deskripsi']
            ]);
        }
    }
}
