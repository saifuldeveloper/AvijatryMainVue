<?php

namespace App\Models;

use App\Enums\FactoryEntryType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FactoryAccountEntry extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'entry_id',
        'entry_type',
        'account_book_id',
        'purchase_id',
        'count',
        'purchase_price',
        'retail_price',
        'return_id',
        'return_count',
        'return_amount',
        'description',
        'account_id',
        'account_name',
        'total_amount',
        'status',
        'closing_id',
        'deleted_by',
    ];

    protected $casts = [
        'entry_type' => FactoryEntryType::class,
    ];

    public function accountBook()
    {
        return $this->belongsTo(AccountBook::class, 'account_book_id');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
