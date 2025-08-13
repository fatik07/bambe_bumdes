<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'sub_katalog_id',
        'nama_project',
        'nama_client',
        'deskripsi',
        'complete_hari',
        'gambar',
    ];

    protected $appends = ['gambar_url'];

    public function subKatalog()
    {
        return $this->belongsTo(SubKatalog::class);
    }

    public function getGambarUrlAttribute()
    {
        if ($this->gambar) {
            return Storage::disk('public')->url($this->gambar);
        }
        return null;
    }
}
