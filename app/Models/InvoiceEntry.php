<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class InvoiceEntry extends Model
{
    use HasFactory,SoftDeletes;
      // Relationships
      public function invoice() {
    	return $this->belongsTo(Invoice::class);
    }

    public function shoe() {
    	return $this->belongsTo(Shoe::class);
    }

    public function giftTransactions() {
        return $this->morphMany(GiftTransaction::class, 'attachment');
    }

    // Attributes
    // public function getTotalPriceAttribute() {
    // 	if(isset($this->shoe)) {
    // 		$shoe = $this->shoe;
    // 	} else {
    // 		$shoe = $this->shoe()->first();
    // 	}
	// 	return @$shoe->retail_price * $this->count;
    // }
    public function getTotalPriceAttribute() {
    // 1. Prothome shoe object-ti load kore nilam
    $shoe = $this->shoe ?? $this->shoe()->first();

    // 2. Logic: retail_price 0 er beshi hole sheta, nahole shoe-er price
    $price = ($this->retail_price > 0) 
                ? $this->retail_price 
                : ($shoe?->retail_price ?? 0);

    // 3. Calculation
    return $price * $this->count;
}

    protected $fillable = ['shoe_id', 'count', 'size_name', 'retail_store_received_count'];
    protected $appends = ['total_price'];


    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
    protected static function booted()
    {
        static::deleting(function ($invoiceEntries) {
            if (auth()->check()) {
                $invoiceEntries->deleted_by = auth()->id();
                $invoiceEntries->save();
            }
        });
    }
}
