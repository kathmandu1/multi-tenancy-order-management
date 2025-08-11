<?php

namespace Modules\OrderManagement\Enums;

enum OrderTrackingEnums: string
{

    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case CONFIRMED = 'confirmed';
    case DELIVERED = 'delivered';
    case PARTIALLYDELIVERED = 'partially delivered';
    case COMPLETED = 'completed';
    case RETURNED = 'returned';
    case REJECTED = 'rejected';
    case CANCELLED = 'cancelled';


    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
