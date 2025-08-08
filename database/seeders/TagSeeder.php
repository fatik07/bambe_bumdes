<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            ['nama' => 'Teknologi'],
            ['nama' => 'Kesehatan'],
            ['nama' => 'Pendidikan'],
            ['nama' => 'Bisnis'],
            ['nama' => 'Lifestyle'],
            ['nama' => 'Travel'],
            ['nama' => 'Food'],
            ['nama' => 'Fashion'],
        ];

        foreach ($tags as $tag) {
            Tag::create($tag);
        }
    }
}
