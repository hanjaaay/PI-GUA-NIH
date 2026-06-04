<?php

namespace App\Models;

use App\Services\BookingService;
use App\Support\BookingStatuses;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'user_id',
        'tourist_attraction_id',
        'ticket_id',
        'visit_date',
        'quantity',
        'total_price',
        'status',
        'payment_status',
        'payment_method',
        'payment_proof',
        'notes',
        'order_id',
        'midtrans_order_id',
        'expired_at',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'total_price' => 'decimal:2',
        'expired_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function touristAttraction(): BelongsTo
    {
        return $this->belongsTo(TouristAttraction::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function bookingTickets(): HasMany
    {
        return $this->hasMany(BookingTicket::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            BookingStatuses::PENDING => 'warning',
            BookingStatuses::PAID => 'success',
            BookingStatuses::CANCELLED,
            BookingStatuses::EXPIRED,
            BookingStatuses::FAILED => 'danger',
            default => 'secondary',
        };
    }

    /*
|--------------------------------------------------------------------------
| REALTIME STATUS
|--------------------------------------------------------------------------
*/

    public function isExpired(): bool
    {
        return
            $this->status === BookingStatuses::EXPIRED
            ||
            $this->shouldExpire();
    }

    public function shouldExpire(): bool
    {
        return

                $this->status === BookingStatuses::PENDING
                &&
                $this->expired_at
                &&
                now()->greaterThan(
                    $this->expired_at
                );
    }

    public function isPending(): bool
    {
        return
            ! $this->isExpired()
            &&
            $this->status === BookingStatuses::PENDING;
    }

    public function remainingSeconds(): int
    {
        if (! $this->expired_at) {
            return 0;
        }

        return max(

            now()->diffInSeconds(
                $this->expired_at,
                false
            ),

            0
        );
    }

    public function remainingMinutes(): int
    {
        return max(
            ceil(
                $this->remainingSeconds() / 60
            ),
            1
        );
    }

    public function expireBooking(): void
    {
        app(BookingService::class)->expire($this);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {

            if (empty($booking->booking_code)) {

                $booking->booking_code =
                    'BK'.
                    date('Ymd').
                    strtoupper(uniqid());
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Activity Logs
    |--------------------------------------------------------------------------
    */

    /**
     * @param  string  $type
     */
    protected function getActivityDescription($type): string
    {
        if ($type === 'created') {

            return "Created new booking for {$this->touristAttraction->name}";
        }

        if ($type === 'updated') {

            return "Updated booking status to {$this->status} for {$this->touristAttraction->name}";
        }

        return "Booking activity: {$type}";
    }
}
