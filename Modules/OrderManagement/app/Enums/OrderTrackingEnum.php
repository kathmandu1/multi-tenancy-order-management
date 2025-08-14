<?php

namespace Modules\OrderManagement\Enums;

enum OrderTrackingEnum: string
{

    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case CONFIRMED = 'confirmed';
    case SHIPPED = 'shipped';
    case PARTIALLYDELIVERED = 'partially delivered';
    case DELIVERED = 'delivered';
    case COMPLETED = 'completed';
    case RETURNED = 'returned';
    case REJECTED = 'rejected';
    case CANCELLED = 'cancelled';


    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getAllowedTransitions(): array
    {
        return match ($this) {
            self::PENDING => [self::PROCESSING, self::CONFIRMED, self::SHIPPED, self::DELIVERED],
            self::PROCESSING => [self::CONFIRMED, self::SHIPPED, self::DELIVERED],
            self::CONFIRMED => [self::SHIPPED, self::DELIVERED],
            self::SHIPPED => [self::PARTIALLYDELIVERED, self::DELIVERED],
            self::PARTIALLYDELIVERED => [self::DELIVERED, self::RETURNED],
            self::DELIVERED => [self::COMPLETED, self::RETURNED],
            self::COMPLETED => [],
            self::RETURNED => [],
            self::REJECTED => [],
            self::CANCELLED => [],
        };
    }
}
