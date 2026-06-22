<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'name'];

    public function accountBooks() {
        return $this->morphMany(AccountBook::class, 'account');
    }

    public function getCurrentBookAttribute() {
        return $this->getCurrentAccountBook();
    }

    public function getCurrentAccountBook() {
        return $this->accountBooks()->where('open', true)->first();
    }
}
