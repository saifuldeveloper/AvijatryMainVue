<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetBook extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'is_active',
        'deleted_by',
    ];

    public function getCurrentAccountBook()
    {
        return $this->accountBooks()->where('open', true)->latest()->first();
    }

    public function accountBooks()
    {
        return $this->hasMany(AccountBook::class, 'account_id', 'id')->where('account_type', 'asset');
    }

    public function entries()
    {
        return $this->hasMany(Asset::class, 'account_book_id', 'id')->orderBy('created_at', 'desc');
    }

    public function getEntries()
    {
        // This is used to return a relation or query for the entries
        // Let's make it robust by fetching through AccountBook if needed or direct
        return $this->hasMany(Asset::class, 'account_book_id', 'id')->orderBy('created_at', 'desc');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    protected static function booted()
    {
        static::deleting(function ($book) {
            if (auth()->check()) {
                $book->deleted_by = auth()->id();
                $book->save();
            }
        });
    }
}
