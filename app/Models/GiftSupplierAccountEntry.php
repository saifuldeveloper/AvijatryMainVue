<?php

namespace App\Models;

use App\Enums\GiftSupplierEntryType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GiftSupplierAccountEntry extends Model
{
    use HasFactory, SoftDeletes;


    protected $guarded = [];
    protected $casts = [
        'entry_type' => GiftSupplierEntryType::class,
    ];

       public function accountBook()
    {
        return $this->belongsTo(AccountBook::class);
    }

    public function PaymentMethod()
    {
        return $this->belongsTo(BankAccount::class, 'payment_method', 'id');
    }
}
