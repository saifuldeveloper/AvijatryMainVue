<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\View\InvoiceItem;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{

    use HasFactory, SoftDeletes;
    // Relationships
    public function accountBook()
    {
        return $this->belongsTo(AccountBook::class);

    }

    public function invoiceEntries()
    {
        return $this->hasMany(InvoiceEntry::class, 'invoice_id', 'id');
    }

    public function sales()
    {
        return $this->shoeTransactions()->where('type', 'sale')->orderBy('id', 'asc');
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class)
            ->orderBy('parent_id', 'asc')->orderBy('retail_price', 'desc');
    }

    public function returns()
    {
        return $this->hasMany(ReturnFromRetailEntry::class);
    }

    public function retailStoreExpenses()
    {
        return $this->hasMany(RetailStoreExpense::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'attachment_id', 'id')->where('attachment_type', 'App\Models\Invoice');
    }

    public function giftTransactions()
    {
        return $this->morphMany(GiftTransaction::class, 'attachment');
    }

    // Memoization Properties
    protected $memoizedTotalPairs = null;
    protected $memoizedTotalAmount = null;
    protected $memoizedTotalCommission = null;
    protected $memoizedCommissionDeducted = null;
    protected $memoizedReturnCount = null;
    protected $memoizedReturnAmount = null;
    protected $memoizedReturnDeducted = null;
    protected $memoizedTransportAdded = null;
    protected $memoizedOtherCosts = null;
    protected $memoizedOtherCostsDeducted = null;
    protected $memoizedTotalReceivable = null;
    protected $memoizedTotalPayment = null;
    protected $memoizedAccountBookBalance = null;
    protected $memoizedAccountBookPreviousBalance = null;

    // Functions
    public function getTotalPairs()
    {
        if ($this->memoizedTotalPairs !== null) {
            return $this->memoizedTotalPairs;
        }
        $this->memoizedTotalPairs = $this->invoiceEntries()->sum('count');
        return $this->memoizedTotalPairs;
    }

    public function getTotalAmount()
    {
        if ($this->memoizedTotalAmount !== null) {
            return $this->memoizedTotalAmount;
        }
        //---------------------inventory price change korle invoice entries price change hobe na
        $this->memoizedTotalAmount = $this->invoiceEntries()
            ->join('shoes', 'shoes.id', '=', 'invoice_entries.shoe_id')
            ->sum(DB::raw('
        (CASE 
            WHEN invoice_entries.retail_price > 0 
            THEN invoice_entries.retail_price 
            ELSE shoes.retail_price 
        END) * invoice_entries.count
    '));
        return $this->memoizedTotalAmount;
    }

    public function getTotalCommission()
    {
        if ($this->memoizedTotalCommission !== null) {
            return $this->memoizedTotalCommission;
        }
        $this->memoizedTotalCommission = $this->getTotalAmount() * $this->commission / 100;
        return $this->memoizedTotalCommission;
    }

    public function getCommissionDeducted()
    {
        if ($this->memoizedCommissionDeducted !== null) {
            return $this->memoizedCommissionDeducted;
        }
        $this->memoizedCommissionDeducted = $this->getTotalAmount() - $this->getTotalCommission();
        return $this->memoizedCommissionDeducted;
    }

    public function getReturnCount()
    {
        if ($this->memoizedReturnCount !== null) {
            return $this->memoizedReturnCount;
        }
        $this->memoizedReturnCount = $this->returns()->sum('count');
        return $this->memoizedReturnCount;
    }

    public function getReturnAmount()
    {
        if ($this->memoizedReturnAmount !== null) {
            return $this->memoizedReturnAmount;
        }
        $this->memoizedReturnAmount = $this->returns()
            ->join('shoes', 'shoes.id', '=', 'return_from_retail_entries.shoe_id')
            ->sum(DB::raw('
            return_from_retail_entries.count 
            * (CASE 
                  WHEN return_from_retail_entries.retail_price > 0 
                  THEN return_from_retail_entries.retail_price 
                  ELSE shoes.retail_price 
               END)
            * (100 - return_from_retail_entries.commission) / 100
        '));
        return $this->memoizedReturnAmount;
    }

    public function getReturnDeducted()
    {
        if ($this->memoizedReturnDeducted !== null) {
            return $this->memoizedReturnDeducted;
        }
        $this->memoizedReturnDeducted = $this->getCommissionDeducted() - $this->getReturnAmount();
        return $this->memoizedReturnDeducted;
    }

    public function getTransportAdded()
    {
        if ($this->memoizedTransportAdded !== null) {
            return $this->memoizedTransportAdded;
        }
        $this->memoizedTransportAdded = $this->getReturnDeducted() + $this->transport;
        return $this->memoizedTransportAdded;
    }

    public function getOtherCosts()
    {
        if ($this->memoizedOtherCosts !== null) {
            return $this->memoizedOtherCosts;
        }
        $this->memoizedOtherCosts = $this->retailStoreExpenses()->sum('amount');
        return $this->memoizedOtherCosts;
    }

    public function getOtherCostsDeducted()
    {
        if ($this->memoizedOtherCostsDeducted !== null) {
            return $this->memoizedOtherCostsDeducted;
        }
        $this->memoizedOtherCostsDeducted = $this->getTransportAdded() - $this->getOtherCosts();
        return $this->memoizedOtherCostsDeducted;
    }

    public function getTotalReceivable()
    {
        if ($this->memoizedTotalReceivable !== null) {
            return $this->memoizedTotalReceivable;
        }
        $this->memoizedTotalReceivable = $this->getOtherCostsDeducted() - $this->discount;
        return $this->memoizedTotalReceivable;
    }

    public function getTotalPayment()
    {
        if ($this->memoizedTotalPayment !== null) {
            return $this->memoizedTotalPayment;
        }
        $this->memoizedTotalPayment = $this->transactions()->sum('amount');
        return $this->memoizedTotalPayment;
    }

    public function getAccountBookBalance()
    {
        if ($this->memoizedAccountBookBalance !== null) {
            return $this->memoizedAccountBookBalance;
        }
        $accountBook = $this->accountBook; // Relationship caching
        if (!$accountBook) {
            $this->memoizedAccountBookBalance = 0.00;
            return $this->memoizedAccountBookBalance;
        }

        $accountEntry = $accountBook->entriesQuery()
            ->where('invoice_id', $this->id)
            ->first();

        $this->memoizedAccountBookBalance = $accountEntry ? (float) $accountEntry->balance : 0.00;
        return $this->memoizedAccountBookBalance;
    }

    public function getAccountBookPreviousBalance()
    {
        if ($this->memoizedAccountBookPreviousBalance !== null) {
            return $this->memoizedAccountBookPreviousBalance;
        }
        $accountBook = $this->accountBook;
        if (!$accountBook) {
            $this->memoizedAccountBookPreviousBalance = 0.00;
            return $this->memoizedAccountBookPreviousBalance;
        }
        // Current invoice entry
        $currentEntry = $accountBook->entriesQuery()->where('invoice_id', $this->id)->first();

        if ($currentEntry) {
            // created_at in preivous entry
            $previousEntry = $accountBook->entriesQuery()
                ->where('created_at', '<', $currentEntry->created_at)
                ->orderBy('created_at', 'desc')
                ->first();
            $this->memoizedAccountBookPreviousBalance = $previousEntry ? (float) $previousEntry->balance : 0.00;
            return $this->memoizedAccountBookPreviousBalance;
        }
        // if invoice entry  not now find
        $previousEntry = $accountBook->entriesQuery()
            ->where('created_at', '<', $this->created_at)
            ->orderBy('created_at', 'desc')
            ->first();
        $this->memoizedAccountBookPreviousBalance = $previousEntry ? (float) $previousEntry->balance : 0.00;
        return $this->memoizedAccountBookPreviousBalance;
    }
    protected $fillable = ['commission', 'transport', 'discount', 'retail_store_status'];

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


    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
    protected static function booted()
    {
        static::deleting(function ($invoice) {
            if (auth()->check()) {
                $invoice->deleted_by = auth()->id();
                $invoice->save();
            }
        });
    }
}
