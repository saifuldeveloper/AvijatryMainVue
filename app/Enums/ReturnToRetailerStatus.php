<?php

namespace App\Enums;

enum ReturnToRetailerStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case CANCELLED = 'cancelled';
    case REJECTED = 'rejected';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match($this) {
            self::PENDING   => __('pages.pending'),
            self::APPROVED  => __('pages.approved'),
            self::CANCELLED => __('pages.cancelled'),
            self::REJECTED  => __('pages.rejected'),
        };
    }
}
