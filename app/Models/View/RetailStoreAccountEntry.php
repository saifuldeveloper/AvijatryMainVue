<?php

namespace App\Models\View;

use App\Enums\RetailStoreEntryType;
use App\Models\AccountBook;
use App\Models\Invoice;
use App\Models\RetailStoreExpense;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RetailStoreAccountEntry extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'entry_type' => RetailStoreEntryType::class,
    ];
    protected $fillable = [
        'entry_id',
        'entry_type',
        'account_book_id',
        'invoice_id',
        'count',
        'total_retail_price',
        'return_count',
        'return_amount',
        'expense_amount',
        'expense_description',
        'total_commission',
        'commission_amount',
        'transport',
        'discount',
        'paid_amount',
        'amount',
        'is_discount_product_sale',
    ];

    public function invoices()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function AccountBook()
    {
        return $this->belongsTo(AccountBook::class, 'account_book_id', 'id');
    }

    public function retailStoreExpenses()
    {
        return $this->belongsTo(RetailStoreExpense::class, 'expense_id', 'id');
    }


    protected static function booted()
    {
        static::deleting(function ($retailStoreAccountEntry) {
            if (auth()->check()) {
                $retailStoreAccountEntry->deleted_by = auth()->id();
                $retailStoreAccountEntry->save();
            }
        });
    }
}
