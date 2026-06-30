<?php

namespace App\Models;

use App\Enums\ReturnToRetailerStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ReturnToRetailer extends Model
{
    use HasFactory, SoftDeletes;


    protected $guarded = [];

    public function accountBook()
    {
        return $this->belongsTo(AccountBook::class, 'account_book_id');
    }


    public function details()
    {

        return $this->hasMany(ReturnFromRetailEntry::class, 'return_id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
    protected static function booted()
    {

        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = auth()->id();
            }
        });
        static::updating(function ($model) {
            if ($model->isDirty('status') && $model->status === ReturnToRetailerStatus::APPROVED->value) {
                $model->approved_by = auth()->id();
                $model->approved_at = now();
            }
        });
        static::deleting(function ($model) {
            if (auth()->check()) {
                $model->deleted_by = auth()->id();
                $model->saveQuietly();
            }
        });
    }


    public static function getNextId()
    {
        return (self::max('id') ?? 0) + 1;
    }

}
