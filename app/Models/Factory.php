<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Factory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'address', 'mobile_no', 'mobile_no_two', 'deleted_by'];

    public function getCurrentAccountBook()
    {
        return $this->accountBooks()->where('open', true)->latest()->first();
    }

    public function accountBooks()
    {
        return $this->hasMany(AccountBook::class, 'account_id', 'id')->where('account_type', 'factory');
    }

    public function entries()
    {
        return $this->hasMany(FactoryAccountEntry::class, 'entry_id', 'id')->where('status', 1)->orderBy('created_at', 'desc');
    }

    public function getCurrentBalanceAttribute()
    {
        $final_balance = 0;
        $desired_balances = [];
        foreach ($this->entries->reverse() as $entry) {
            if ($entry->entry_type->value == 0) {
                $final_balance += $entry->total_amount;
            } else {
                $final_balance -= $entry->total_amount;
            }
            $desired_balances[] = $final_balance;
        }

        return array_reverse($desired_balances);
    }

    public function purchaseBalance()
    {
        return $this->entries->where('entry_type', 0)->sum('total_amount');
    }

    public function payAmount()
    {
        return $this->entries->where('entry_type', 2)->sum('total_amount');
    }

    public function returnAmount()
    {
        return $this->entries->where('entry_type', 1)->where('status', 1)->sum('total_amount');
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
