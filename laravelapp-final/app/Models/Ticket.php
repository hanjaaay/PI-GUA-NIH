<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [

        'tourist_attraction_id',

        'name',

        'type',

        'ticket_type',

        'valid_date',

        'valid_from',

        'valid_until',

        'price',

        'quota',

        'available_quantity',

        'description',

        'is_active',

        'qr_code',
    ];

    protected $casts = [
        'gallery' => 'array',
        'is_active' => 'boolean',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'valid_date' => 'date',
    ];

    public function touristAttraction(): BelongsTo
    {
        return $this->belongsTo(TouristAttraction::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function getRemainingQuantity(): int
    {
        return max(0, (int) $this->available_quantity);
    }

    public function isAvailable()
    {
        return $this->is_active
            && $this->getRemainingQuantity() > 0
            && (
                ! $this->valid_from
                || ! $this->valid_until
                || now()->between($this->valid_from, $this->valid_until)
            );
    }

    public function generateQrCode()
    {
        $this->qr_code = uniqid('TICKET-');
        $this->save();

        return $this->qr_code;
    }
}
