<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Katalog extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($katalog) {
            $katalog->slug = static::generateUniqueSlug($katalog->nama);
        });

        static::updating(function ($katalog) {
            if ($katalog->isDirty('nama')) {
                $katalog->slug = static::generateUniqueSlug($katalog->nama, $katalog->id);
            }
        });
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

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function subKatalogs()
    {
        return $this->hasMany(SubKatalog::class, 'katalog_id');
    }
}
