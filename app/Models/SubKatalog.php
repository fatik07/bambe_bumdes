<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SubKatalog extends Model
{
    use HasFactory;

    protected $fillable = [
        'katalog_id',
        'nama',
        'slug',
        'deskripsi',
        'image',
    ];

    protected $appends = ['image_url'];

    public function katalog()
    {
        return $this->belongsTo(Katalog::class);
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return Storage::disk('public')->url($this->image);
        }
        return null;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public static function generateUniqueSlug($nama, $excludeId = null)
    {
        $slug = Str::slug($nama);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->when($excludeId, function ($query, $excludeId) {
            return $query->where('id', '!=', $excludeId);
        })->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
