<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use App\Models\SubKatalog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subKatalogs = SubKatalog::all();

        $testimonialData = [
            [
                'nama_project' => 'Website E-Commerce Fashion',
                'nama_client' => 'Toko Busana Indah',
                'deskripsi' => 'Pengembangan website e-commerce untuk toko fashion dengan fitur lengkap seperti katalog produk, keranjang belanja, dan sistem pembayaran online.',
                'complete_hari' => 30,
            ],
            [
                'nama_project' => 'Aplikasi Mobile Delivery Food',
                'nama_client' => 'Resto Nusantara',
                'deskripsi' => 'Pembuatan aplikasi mobile untuk layanan delivery makanan dengan GPS tracking, notifikasi real-time, dan sistem rating.',
                'complete_hari' => 45,
            ],
            [
                'nama_project' => 'Sistem Manajemen Inventory',
                'nama_client' => 'CV. Maju Bersama',
                'deskripsi' => 'Pengembangan sistem manajemen inventory berbasis web untuk mengoptimalkan pengelolaan stok barang dan laporan keuangan.',
                'complete_hari' => 60,
            ],
            [
                'nama_project' => 'Platform Edukasi Online',
                'nama_client' => 'Yayasan Pendidikan Cerdas',
                'deskripsi' => 'Pembuatan platform pembelajaran online dengan fitur video conference, assignment, dan tracking progress siswa.',
                'complete_hari' => 90,
            ],
            [
                'nama_project' => 'Website Company Profile',
                'nama_client' => 'PT. Teknologi Maju',
                'deskripsi' => 'Desain dan pengembangan website company profile yang modern dan responsif dengan CMS untuk update konten.',
                'complete_hari' => 20,
            ],
            [
                'nama_project' => 'Aplikasi Keuangan UMKM',
                'nama_client' => 'Koperasi Sejahtera',
                'deskripsi' => 'Pengembangan aplikasi untuk mengelola keuangan UMKM dengan fitur pencatatan transaksi, laporan, dan analisis bisnis.',
                'complete_hari' => 75,
            ],
        ];

        foreach ($subKatalogs->take(6) as $index => $subKatalog) {
            if (isset($testimonialData[$index])) {
                Testimonial::create([
                    'sub_katalog_id' => $subKatalog->id,
                    'nama_project' => $testimonialData[$index]['nama_project'],
                    'nama_client' => $testimonialData[$index]['nama_client'],
                    'deskripsi' => $testimonialData[$index]['deskripsi'],
                    'complete_hari' => $testimonialData[$index]['complete_hari'],
                ]);
            }
        }
    }
}
