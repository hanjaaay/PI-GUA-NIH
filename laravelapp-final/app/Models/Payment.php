<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_id',
        'payment_code',
        'amount',
        'currency',
        'payment_method',
        'payment_channel',
        'payment_status',
        'payment_proof',
        'paid_at',
        'expired_at',
        'transaction_id',
        'payment_details',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
        'payment_details' => 'array',
    ];

    // Relationships
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopeFailed($query)
    {
        return $query->where('payment_status', 'failed');
    }

    public function scopeExpired($query)
    {
        return $query->where('payment_status', 'expired');
    }

    // Methods
    public function isPending()
    {
        return $this->payment_status === 'pending';
    }

    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    public function isFailed()
    {
        return $this->payment_status === 'failed';
    }

    public function isExpired()
    {
        return $this->payment_status === 'expired';
    }

    public function markAsPaid()
    {
        $this->update([
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);
    }

    public function markAsFailed()
    {
        $this->update([
            'payment_status' => 'failed',
        ]);
    }

    public function markAsExpired()
    {
        $this->update([
            'payment_status' => 'expired',
        ]);
    }

    // Generate unique payment code
    public static function generatePaymentCode()
    {
        do {
            $code = 'PAY-'.strtoupper(uniqid());
        } while (self::where('payment_code', $code)->exists());

        return $code;
    }
}
