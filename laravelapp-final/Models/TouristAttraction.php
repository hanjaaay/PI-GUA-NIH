<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TouristAttraction extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [

        'category_id',

        'name',

        'slug',

        'description',

        'short_description',

        'address',

        'city',

        'province',

        'country',

        'postal_code',

        'phone',

        'email',

        'website',

        'featured_image',

        'gallery',

        'price',

        'opening_hours',

        'closing_hours',

        'operating_hours',

        'facilities',

        'terms_conditions',

        'cancellation_policy',

        'refund_policy',

        'max_capacity',

        'current_visitors',

        'is_featured',

        'is_active',

        'status',

        'type',

        'start_date',

        'end_date',
    ];

    protected $casts = [

        'facilities' => 'array',

        'gallery' => 'array',

        'is_active' => 'boolean',

        'is_featured' => 'boolean',

        'price' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function getFeaturedImageUrlAttribute(): ?string
    {
        if (! $this->featured_image) {
            return null;
        }

        return asset('storage/'.$this->featured_image);
    }

    protected function getActivityDescription($type): string
    {
        return match ($type) {

            'created' => "Created tourist attraction: {$this->name}",

            'updated' => "Updated tourist attraction: {$this->name}",

            'deleted' => "Deleted tourist attraction: {$this->name}",

            default => "Tourist attraction activity: {$type}",
        };
    }
}
