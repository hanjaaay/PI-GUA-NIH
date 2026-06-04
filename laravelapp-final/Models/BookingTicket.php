<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BookingTicket extends Model
{
    use HasFactory;

    public function checkins(): HasMany
{
    return $this->hasMany(TicketCheckin::class);
}

    protected $fillable = [
        'booking_id',
        'ticket_code',
        'qr_code',
        'is_used',
        'used_at',
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'used_at' => 'datetime',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
