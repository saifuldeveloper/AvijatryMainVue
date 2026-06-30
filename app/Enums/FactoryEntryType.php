<?php

namespace App\Enums;

enum FactoryEntryType: int
{
    case Purchase = 0;
    case Return = 1;
    case Payment = 2;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
