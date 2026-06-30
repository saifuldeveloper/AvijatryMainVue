<?php

namespace App\Models;
use App\Enums\ReturnToRetailerStatus;
use Illuminate\Database\Eloquent\Model;

class ReturnFromRetailEntry extends Model
{




    protected $casts = [
        'shoe_id' => 'integer',
    ];

    public function accountBook()
    {
        return $this->belongsTo(AccountBook::class);
    }

    public function shoe()
    {
        return $this->belongsTo(Shoe::class, 'shoe_id', 'id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function returnMaster()
    {
        return $this->belongsTo(ReturnToRetailer::class, 'return_id');
    }

    public function getTotalPriceAttribute()
    {
        return ($this->shoe?->retail_price ?? 0) * ($this->count ?? 0);
    }

    public function getTotalCommissionAttribute()
    {
        $retailPrice = $this->shoe?->retail_price ?? 0;
        $count = $this->count ?? 0;
        $commission = $this->commission ?? 0;

        return $retailPrice * $count * $commission / 100;
    }

    public function getCommissionDeductedAttribute()
    {
        $retailPrice = $this->shoe?->retail_price ?? 0;
        $count = $this->count ?? 0;
        $commission = $this->commission ?? 0;

        return $retailPrice * $count * (100 - $commission) / 100;
    }

    public static function getTotalReturnNetAmount($accountBookId)
    {
        return self::where('return_from_retail_entries.account_book_id', $accountBookId)
            ->join('return_to_retailers', 'return_to_retailers.id', '=', 'return_from_retail_entries.return_id')
            ->leftJoin('shoes', 'shoes.id', '=', 'return_from_retail_entries.shoe_id')
            ->where('return_to_retailers.status', ReturnToRetailerStatus::APPROVED->value)
            ->selectRaw("
            SUM(
                COALESCE(
                    (CASE
                        WHEN return_from_retail_entries.retail_price = 0 THEN shoes.retail_price
                        ELSE return_from_retail_entries.retail_price
                    END) * return_from_retail_entries.count, 0
                )
            ) as total
        ")
            ->first()
            ->total ?? 0;
    }


    public static function getTotalReturnGrossAmount($accountBookId)
    {
        return self::where('return_from_retail_entries.account_book_id', $accountBookId)
            ->join('return_to_retailers', 'return_to_retailers.id', '=', 'return_from_retail_entries.return_id')
            ->leftJoin('shoes', 'shoes.id', '=', 'return_from_retail_entries.shoe_id')
            ->where('return_to_retailers.status', ReturnToRetailerStatus::APPROVED->value)
            ->selectRaw("
            SUM(
                COALESCE(
                    (CASE
                        WHEN return_from_retail_entries.retail_price = 0 THEN shoes.retail_price
                        ELSE return_from_retail_entries.retail_price
                    END) * return_from_retail_entries.count
                    -
                    (
                        (CASE
                            WHEN return_from_retail_entries.retail_price = 0 THEN shoes.retail_price
                            ELSE return_from_retail_entries.retail_price
                        END) * return_from_retail_entries.count * return_from_retail_entries.commission / 100
                    ), 0
                )
            ) as total
        ")
            ->first()
            ->total ?? 0;
    }

    public static function totalReturnPair($accountBookId)
    {
        return self::where('return_from_retail_entries.account_book_id', $accountBookId)
            ->join('return_to_retailers', 'return_to_retailers.id', '=', 'return_from_retail_entries.return_id')
            ->where('return_to_retailers.status', ReturnToRetailerStatus::APPROVED->value)
            ->sum('return_from_retail_entries.count') ?? 0;
    }

    protected $fillable = ['shoe_id', 'count', 'commission'];
    protected $with = ['shoe'];
    protected $appends = ['total_price', 'total_commission', 'commission_deducted'];
}
