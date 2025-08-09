<?php

namespace Database\Seeders;

use App\Models\Legalitas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LegalitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $legalitasData = [
            [
                'nama' => 'Akta Pendirian BUMDes',
                'image' => 'legalitas/akta-pendirian.jpg',
            ],
            [
                'nama' => 'Surat Keterangan Domisili',
                'image' => 'legalitas/surat-domisili.jpg',
            ],
            [
                'nama' => 'NPWP BUMDes',
                'image' => 'legalitas/npwp.jpg',
            ],
            [
                'nama' => 'Sertifikat ISO 9001:2015',
                'image' => 'legalitas/iso-9001.jpg',
            ],
            [
                'nama' => 'Surat Izin Usaha Perdagangan (SIUP)',
                'image' => 'legalitas/siup.jpg',
            ],
            [
                'nama' => 'Tanda Daftar Perusahaan (TDP)',
                'image' => 'legalitas/tdp.jpg',
            ],
            [
                'nama' => 'Izin Lingkungan',
                'image' => 'legalitas/izin-lingkungan.jpg',
            ],
            [
                'nama' => 'Sertifikat Halal MUI',
                'image' => 'legalitas/sertifikat-halal.jpg',
            ],
        ];

        foreach ($legalitasData as $data) {
            Legalitas::create($data);
        }
    }
}
