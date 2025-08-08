<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'judul',
        'deskripsi',
        'tag_id',
        'penulis',
    ];

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
