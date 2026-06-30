<?php

namespace App\Enums;

enum RetailStoreEntryType: int
{
    case Invoice = 0;
    case Return = 1;
    case Expence = 2;
    case Payment = 3;
    case ClosingPayment = 5;

    // Sob value gulo array akare nite ei method-ti thakbe
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
