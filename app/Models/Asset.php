<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Asset extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'account_book_id',
        'entry_type',
        'asset_cost',
        'depreciation',
        'depreciation_type',
        'salvage_value',
        'useful_life',
        'daily_depreciation',
        'note',
        'start_date',
        'expire_date',
        'created_by',
        'type',
        'status',
        'created_at',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'expire_date' => 'datetime',
        'status' => 'boolean',
        'depreciation' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function accountBook()
    {
        return $this->belongsTo(AccountBook::class, 'account_book_id', 'id');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    protected static function booted()
    {
        static::deleting(function ($asset) {
            if (auth()->check()) {
                $asset->deleted_by = auth()->id();
                $asset->save();
            }
        });
    }
}
