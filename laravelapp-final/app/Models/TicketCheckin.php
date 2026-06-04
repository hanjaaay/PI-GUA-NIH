<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketCheckin extends Model
{
    protected $fillable = [
        'booking_ticket_id',
        'checkin_date',
        'checked_in_at',
    ];

    protected $casts = [
        'checkin_date' => 'date',
        'checked_in_at' => 'datetime',
    ];

    public function bookingTicket(): BelongsTo
    {
        return $this->belongsTo(BookingTicket::class);
    }
}