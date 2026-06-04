<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Import HasMany

class Category extends Model
{
    // Jika Anda tidak memiliki kolom timestamps (created_at, updated_at) di tabel categories, uncomment baris ini:
    // public $timestamps = false;

    // Tambahkan properti fillable jika Anda mengizinkan mass assignment untuk kategori
    protected $fillable = [
        'name', // Asumsi ada kolom 'name' di tabel categories
        'slug', // Asumsi ada kolom 'slug'
    ];

    /**
     * Get the tourist attractions for the category.
     */
    public function touristAttractions(): HasMany
    {
        // Pastikan 'category_id' adalah nama foreign key di tabel tourist_attractions
        return $this->hasMany(TouristAttraction::class, 'category_id');
    }
}
