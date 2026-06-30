<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClosingPeriod extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'start_date',
        'end_date',
        'status',
        'created_by',
        'updated_by',
    ];

    protected static function booted()
    {
        static::deleting(function ($factory) {
            if (auth()->check()) {
                $factory->deleted_by = auth()->id();
                $factory->save();
            }
        });
    }

    public static function yearlyPeriodsCached($minutes = 60)
    {
        return Cache::remember("closing_periods_yearly", $minutes * 60, function () {
            return self::selectRaw('
                    MIN(id) as id, 
                    YEAR(start_date) as yr, 
                    MIN(start_date) as start_date, 
                    MAX(end_date) as end_date
                ')
                ->groupBy('yr')
                ->orderByDesc('yr')
                ->get();
        });
    }
}
