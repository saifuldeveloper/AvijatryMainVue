<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class GiftPurchase extends Model
{
    use HasFactory, SoftDeletes;


    // Relationships
    public function accountBook()
    {
        return $this->belongsTo(AccountBook::class);
    }

    public function giftTransactions()
    {
        return $this->morphMany(GiftTransaction::class, 'attachment');
    }

    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'attachment');
    }

    // Attributes
    public function getTotalAmountAttribute()
    {
        return $this->giftTransactions()->sum(DB::raw('count * unit_price'));
    }

     public function PaymentAmount()
    {
        return $this->transaction ? $this->transaction->amount : 0;
    }

    public function supplierAccountEntry()
    {
        return $this->hasOne(GiftSupplierAccountEntry::class, 'gift_purchase_id')
            ->whereNotNull('payment_method');
    }

    public function getPaymentMethod()
    {
        return $this->supplierAccountEntry?->PaymentMethod;
    }

    public function ChequePayment()
    {
        return Cheque::where('account_book_id', $this->account_book_id)
            ->where('attachment_type', GiftPurchase::class)
            ->where('attachment_id', $this->id)
            ->first();
    }

    protected $fillable = ['account_book_id'];
    protected $appends = ['total_amount'];

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
    public static function getNextId()
    {
        $dbname = config('database.connections.mysql.database');
        $table = (new self)->getTable();
        $query = DB::select("SELECT AUTO_INCREMENT
                             FROM information_schema.TABLES
                             WHERE TABLE_SCHEMA = '{$dbname}'
                             AND TABLE_NAME = '{$table}'");

        return $query[0]->AUTO_INCREMENT;
    }
}
