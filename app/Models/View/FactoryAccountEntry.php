<?php

namespace App\Models\View;

use App\Enums\FactoryEntryType;
use App\Models\Purchase;
use App\Models\ReturnToFactoryEntry;
use App\Models\AccountBook;
use App\Models\PurchaseEntry;
use App\Models\ReturnToFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FactoryAccountEntry extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'entry_type' => FactoryEntryType::class,
    ];

    public function accountBook()
    {
        return $this->belongsTo(AccountBook::class);
    }

    public function bankAccountBook()
    {
        return $this->belongsTo(AccountBook::class, 'bank_account_book_id');
    }

    // public function purchase()
    // {
    //     return $this->belongsTo(Purchase::class, 'entry_id', 'id');
    // }

    public function returnshoe()
    {
        return $this->belongsTo(ReturnToFactory::class, 'entry_id', 'id')
            ->where('status', 'accepted');
    }

    public function purchaseEntry()
    {
        return $this->belongsTo(PurchaseEntry::class, 'purchase_id', 'purchase_id');
    }

    public function realPurchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
    }

    public function returnToFactoryEntry()
    {
        return $this->belongsTo(ReturnToFactoryEntry::class, 'entry_id', 'id');
    }
}
