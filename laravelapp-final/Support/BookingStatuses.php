<?php

namespace App\Support;

final class BookingStatuses
{
    public const PENDING = 'pending';

    public const PAID = 'paid';

    public const CANCELLED = 'cancelled';

    public const EXPIRED = 'expired';

    public const FAILED = 'failed';

    public const COMPLETED = 'completed';

    public const REFUNDED = 'refunded';

    public const CHALLENGE = 'challenge';

    public const ACTIVE_PENDING = [
        self::PENDING,
    ];

    public const FINAL = [
        self::PAID,
        self::CANCELLED,
        self::EXPIRED,
        self::FAILED,
        self::COMPLETED,
        self::REFUNDED,
    ];
}
