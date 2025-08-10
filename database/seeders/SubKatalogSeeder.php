<?php

namespace Database\Seeders;

use App\Models\SubKatalog;
use App\Models\Katalog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SubKatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $katalogs = Katalog::all();

        $subKatalogData = [
            'Produk Unggulan' => [
                ['nama' => 'Kopi Arabika Premium', 'deskripsi' => 'Kopi arabika pilihan dengan cita rasa premium yang dipetik langsung dari perkebunan lokal'],
                ['nama' => 'Madu Murni Hutan', 'deskripsi' => 'Madu murni dari lebah hutan yang dipanen secara alami tanpa campuran bahan kimia'],
                ['nama' => 'Kerajinan Bambu', 'deskripsi' => 'Aneka kerajinan tangan dari bambu berkualitas tinggi dengan desain modern'],
                ['nama' => 'Batik Tulis Tradisional', 'deskripsi' => 'Batik tulis asli dengan motif tradisional yang dikerjakan oleh pengrajin lokal'],
            ],
            'Layanan Jasa' => [
                ['nama' => 'Konsultasi Bisnis', 'deskripsi' => 'Layanan konsultasi untuk pengembangan bisnis UMKM dan strategi pemasaran'],
                ['nama' => 'Pelatihan Digital Marketing', 'deskripsi' => 'Pelatihan komprehensif untuk menguasai teknik pemasaran digital modern'],
                ['nama' => 'Jasa Keuangan Mikro', 'deskripsi' => 'Layanan peminjaman modal usaha dengan bunga rendah untuk UMKM'],
                ['nama' => 'Pendampingan UMKM', 'deskripsi' => 'Program pendampingan berkelanjutan untuk pengembangan usaha mikro'],
            ],
            'Produk Digital' => [
                ['nama' => 'Aplikasi E-Commerce', 'deskripsi' => 'Platform jual beli online khusus untuk produk UMKM lokal'],
                ['nama' => 'Sistem Manajemen Keuangan', 'deskripsi' => 'Software untuk mengelola keuangan bisnis UMKM secara digital'],
                ['nama' => 'Portal Edukasi Online', 'deskripsi' => 'Platform pembelajaran online untuk skill development dan bisnis'],
                ['nama' => 'Digital Payment Gateway', 'deskripsi' => 'Solusi pembayaran digital terintegrasi untuk UMKM'],
            ],
            'Kemitraan Bisnis' => [
                ['nama' => 'Kemitraan Distribusi', 'deskripsi' => 'Program kemitraan untuk distribusi produk UMKM ke pasar yang lebih luas'],
                ['nama' => 'Joint Venture Teknologi', 'deskripsi' => 'Kolaborasi pengembangan teknologi untuk inovasi produk dan layanan'],
                ['nama' => 'Aliansi Strategis', 'deskripsi' => 'Pembentukan aliansi dengan perusahaan besar untuk akses pasar'],
                ['nama' => 'Program Magang', 'deskripsi' => 'Kesempatan magang dan pengembangan SDM melalui kemitraan industri'],
            ],
        ];

        foreach ($katalogs as $katalog) {
            if (isset($subKatalogData[$katalog->nama])) {
                foreach ($subKatalogData[$katalog->nama] as $subData) {
                    SubKatalog::create([
                        'katalog_id' => $katalog->id,
                        'nama' => $subData['nama'],
                        'slug' => Str::slug($subData['nama']),
                        'deskripsi' => $subData['deskripsi'],
                    ]);
                }
            }
        }
    }
}
