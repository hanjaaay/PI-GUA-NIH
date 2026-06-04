<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Review extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'user_id',
        'tourist_attraction_id',
        'rating',
        'comment',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_verified' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function touristAttraction(): BelongsTo
    {
        return $this->belongsTo(TouristAttraction::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function reviewPhotos(): HasMany
    {
        return $this->hasMany(ReviewPhoto::class);
    }

    protected function getActivityDescription($type)
    {
        $description = parent::getActivityDescription($type);

        if ($type === 'created') {
            return "Added new review for {$this->touristAttraction->name}";
        }

        if ($type === 'updated') {
            return "Updated review for {$this->touristAttraction->name}";
        }

        return $description;
    }
}
