<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'mobile_no'];

    public function getCurrentAccountBook()
    {
        return $this->accountBooks()->where('open',true)->latest()->first();
    }
    public function accountBooks()
    {
        return $this->hasMany(AccountBook::class, 'account_id', 'id')->where('account_type', 'loan');
    }

    public function entries()
    {
        return $this->hasMany(LoanAccountEntry::class, 'entry_id', 'id')->orderBy('created_at', 'desc');
    }

    public function getCurrentBalanceAttribute()
    {
        $final_balance = 0;
        $desired_balances = [];
        foreach ($this->entries->reverse() as $entry) {
            if ($entry->entry_type == 0) {
                $final_balance += $entry->total_amount;
            } else {
                $final_balance -= $entry->total_amount;
            }
            $desired_balances[] = $final_balance;
        }

        return array_reverse($desired_balances);
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
