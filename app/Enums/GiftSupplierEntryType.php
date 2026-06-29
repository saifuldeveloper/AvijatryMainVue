<?php

namespace App\Enums;

enum GiftSupplierEntryType: int
{
    case Purchase = 0;
    case Payment = 2;
    case ClosingPayment = 5;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
