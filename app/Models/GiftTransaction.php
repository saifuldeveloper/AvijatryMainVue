<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GiftTransaction extends Model
{
    use HasFactory, SoftDeletes;
    // Relationships
    public function gift()
    {
        return $this->belongsTo(Gift::class);
    }

    public function attachment()
    {
        return $this->morphTo();
    }


    public function retailStore()
    {
        return $this->belongsTo(RetailStore::class, 'attachment_id' ,'id');
    }

    public function giftPurchase()
    {
        return $this->belongsTo(GiftPurchase::class, 'attachment_id', 'id');
    }


    public static function getGiftPurchasesOn($date)
    {
        $current = new \Carbon\CarbonImmutable($date);
        $next = $current->addDay();
        return GiftTransaction::with('gift.giftType', 'giftPurchase.accountBook.giftSupplierAccount')
        ->whereHas('giftPurchase')
        ->whereBetween('created_at', [$current, $next])->where('type', 'purchase')->get();

    }
    // Attributes
    public function getAmountAttribute()
    {
        return $this->count * $this->unit_price;
    }


    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
    protected static function booted()
    {
        static::deleting(function ($factory) {
            if (auth()->check()) {
                $factory->deleted_by = auth()->id();
                $factory->save();
            }
        });
    }

    protected $with = ['gift'];
    protected $appends = ['amount'];
    protected $fillable = ['gift_id', 'count', 'unit_price', 'description'];
}
