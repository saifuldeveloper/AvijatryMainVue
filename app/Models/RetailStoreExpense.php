<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RetailStoreExpense extends Model
{
    use HasFactory, SoftDeletes;
    public function accountBook()
    {
        return $this->belongsTo(AccountBook::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }


    protected $dates = ['deleted_at'];
    protected $fillable = ['account_book_id', 'description', 'amount'];


    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
    protected static function booted()
    {
        static::deleting(function ($invoice) {
            if (auth()->check()) {
                $invoice->deleted_by = auth()->id();
                $invoice->save();
            }
        });
    }
}

