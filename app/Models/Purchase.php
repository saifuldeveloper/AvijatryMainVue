<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasFactory, SoftDeletes;
    // Relationships
    public function accountBook()
    {
        return $this->belongsTo(AccountBook::class);
    }

    public function purchaseEntries()
    {
        return $this->hasMany(PurchaseEntry::class);
    }

    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'attachment');
    }

    public function cheque()
    {
        return $this->morphOne(Cheque::class, 'attachment');
    }

    // public function getTotalAmountAttribute()
    // {
    //     return $this->purchaseEntries()
    //         ->join('shoes', 'shoes.id', '=', 'purchase_entries.shoe_id')
    //         ->sum(DB::raw('
    //         (CASE
    //             WHEN purchase_entries.purchase_price > 0
    //             THEN purchase_entries.purchase_price
    //             ELSE shoes.purchase_price
    //          END) * purchase_entries.count / 12
    //     '));
    // }
    // Attributes

    //  public function getTotalAmountAttribute()
    // {
    //     return $this->purchaseEntries()
    //         ->sum(DB::raw('purchase_price * count / 12'));
    // }
    protected $totalAmountCache = null;

    public function getTotalAmountAttribute()
    {
        if ($this->totalAmountCache !== null) {
            return $this->totalAmountCache;
        }

        if ($this->relationLoaded('purchaseEntries')) {
            $total = 0.0;
            foreach ($this->purchaseEntries as $entry) {
                $shoePrice = ($entry->relationLoaded('shoe') && $entry->shoe) ? (float) $entry->shoe->purchase_price : 0.0;
                $total += $shoePrice * (int) $entry->count / 12.0;
            }
            $this->totalAmountCache = $total;
            return $this->totalAmountCache;
        }

        $this->totalAmountCache = (float) $this->purchaseEntries()
            ->join('shoes', 'shoes.id', '=', 'purchase_entries.shoe_id')
            ->sum(DB::raw('shoes.purchase_price * purchase_entries.count / 12'));

        return $this->totalAmountCache;
    }

     public function getTotalPurchaseAttribute()
    {
        return $this->purchaseEntries()->sum('count');

    }


    public function ruturnfactory()
    {
        return $this->hasMany(ReturnToFactoryEntry::class, 'account_book_id', 'account_book_id');
    }

    protected $appends = ['total_amount'];


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
        static::deleting(function ($purchase) {
            if (auth()->check()) {
                $purchase->deleted_by = auth()->id();
                $purchase->save();
            }
        });
    }



    public function canDelete(): bool
    {
        if ($this->purchaseEntries()->exists()) {
            foreach ($this->purchaseEntries as $entry) {
                $shoeId = $entry->shoe_id;
                $invoiceEntryExists = DB::table('invoice_entries')
                    ->where('shoe_id', $shoeId)
                    ->exists();

                if ($invoiceEntryExists) {
                    return false; //  delete allow
                }
            }
        }
        return true; //  delete allow
    }

}
