<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = [
            [
                'judul' => 'Perkembangan Teknologi AI di 2025',
                'deskripsi' => 'Artikel ini membahas tentang perkembangan teknologi artificial intelligence yang sangat pesat di tahun 2025, termasuk implementasinya dalam berbagai bidang.',
                'tag_id' => 1, // Teknologi
                'penulis' => 'John Doe',
                'image' => 'articles/ai-technology.jpg'
            ],
            [
                'judul' => 'Tips Hidup Sehat di Era Modern',
                'deskripsi' => 'Panduan lengkap untuk menjaga kesehatan di tengah kesibukan era modern, mulai dari pola makan hingga olahraga yang tepat.',
                'tag_id' => 2, // Kesehatan
                'penulis' => 'Dr. Jane Smith',
                'image' => 'articles/healthy-living.jpg'
            ],
            [
                'judul' => 'Metode Pembelajaran Online yang Efektif',
                'deskripsi' => 'Strategi dan tips untuk memaksimalkan pembelajaran online, baik untuk siswa maupun pengajar di era digital.',
                'tag_id' => 3, // Pendidikan
                'penulis' => 'Prof. Ahmad Rahman',
                'image' => 'articles/online-learning.jpg'
            ],
            [
                'judul' => 'Strategi Bisnis Digital untuk UMKM',
                'deskripsi' => 'Panduan praktis untuk UMKM dalam mengembangkan bisnis digital, mulai dari pemasaran online hingga manajemen keuangan.',
                'tag_id' => 4, // Bisnis
                'penulis' => 'Sarah Wilson',
                'image' => 'articles/digital-business.jpg'
            ],
            [
                'judul' => 'Tren Lifestyle Minimalis',
                'deskripsi' => 'Eksplorasi tentang gaya hidup minimalis yang semakin populer, manfaatnya, dan cara implementasinya dalam kehidupan sehari-hari.',
                'tag_id' => 5, // Lifestyle
                'penulis' => 'Maria Garcia',
                'image' => 'articles/minimalist-lifestyle.jpg'
            ]
        ];

        foreach ($articles as $article) {
            Article::create($article);
        }
    }
}
