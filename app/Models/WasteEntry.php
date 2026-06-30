<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WasteEntry extends Model
{
    use HasFactory, SoftDeletes;
    public function shoe()
    {

        return $this->belongsTo(Shoe::class, 'shoe_id');
    }
    public function factory()
    {
        return $this->belongsTo(Factory::class, 'account_id');
    }
    public function returnEntry()
    {
        return $this->belongsTo(ReturnFromRetailEntry::class, 'retail_store_return_entry_id');
    }

    public function giftSupplier()
    {
        return $this->belongsTo(GiftSupplier::class, 'account_id');
    }
    public function retailStore()
    {
        return $this->belongsTo(RetailStore::class, 'account_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'account_id');
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class, 'account_id');
    }

    public function Accountbook()
    {
        return $this->belongsTo(AccountBook::class, 'account_book_id');

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

    // protected $with = ['shoe'];
    protected $fillable = ['shoe_id', 'count', 'description'];
}
