<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionHistory extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    protected $appends = ['amount'];

    public function getAmountAttribute()
    {
        $transactionId = $this->transaction_id_two ?? $this->transaction_id_one;

        if (!$transactionId) {
            return null;
        }

        $transaction = Transaction::withTrashed()->find($transactionId);
        return $transaction ? $transaction->amount : null;
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id_one')
            ->withDefault(function () {
                return $this->belongsTo(Transaction::class, 'transaction_id_two')->first();
            });
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    protected static function booted()
    {
        static::deleting(function ($factory) {
            if (auth()->check()) {
                $factory->deleted_by = auth()->id();
                $factory->save();
            }
        });
    }
}
