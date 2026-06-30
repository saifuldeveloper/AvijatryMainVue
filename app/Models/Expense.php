<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'type'];



    public function getCurrentBookAttribute()
    {
        return $this->getCurrentAccountBook();
    }

    public function getCurrentAccountBook()
    {
        return $this->accountBooks()->latest()->first();
    }
     public function accountBook()
    {
        return $this->hasOne(AccountBook::class, 'account_id', 'id')->where('account_type', 'expense');
    }
    public function accountBooks()
    {
        return $this->hasMany(AccountBook::class, 'account_id', 'id')->where('account_type', 'expense');
    }
    public function entries()
    {
        return $this->hasMany(ExpenseAccountEntry::class, 'account_id', 'id')->orderBy('created_at', 'desc');
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
