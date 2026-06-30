<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LiabilitiesEntry extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'liabilities_entries';

    protected $fillable = [
        'entry_id',
        'entry_type',
        'account_book_id',
        'description',
        'total_amount',
        'type',
        'attachments',
        'status',
        'created_at',
    ];

    protected $casts = [
        'attachments' => 'array',
        'status' => 'boolean',
    ];

    public function AccountBook()
    {
        return $this->belongsTo(AccountBook::class, 'account_book_id', 'id');
    }

    public function liability()
    {
        return $this->belongsTo(Liabilitie::class, 'entry_id', 'id');
    }
}
