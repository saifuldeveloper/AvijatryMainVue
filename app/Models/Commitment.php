<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Commitment extends Model
{
    use HasFactory, SoftDeletes;

    public function Accountbook()
    {
        return $this->belongsTo(AccountBook::class, 'account_book_id', 'id');
    }


    public function CommitmentLog()
    {
        return $this->hasMany(CommitmentLog::class, 'commitment_id', 'id');
    }

    protected $fillable = ['description', 'commitment_date', 'amount'];



}
