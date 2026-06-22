<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'account_type',
        'previous_balance',
        'open',
        'commission',
        'commission_percentage',
        'staff',
        'staff_percentage',
        'staff_shoe_amount',
        'discount',
        'due',
        'deadline',
        'balance_carry_forward',
        'closing_balance',
        'closing_type',
        'closing_date',
    ];

    public function assetAccount()
    {
        return $this->belongsTo(AssetBook::class, 'account_id', 'id');
    }

    public function liabilityAccount()
    {
        return $this->belongsTo(Liabilitie::class, 'account_id', 'id');
    }

    public function retailAccount()
    {
        return $this->belongsTo(RetailStore::class, 'account_id', 'id');
    }

    public function getDescription()
    {
        $description = dateFormat($this->created_at) . ' ' . __('pages.from') . ' ';
        if ($this->open) {
            $description .= __('pages.Ongoing');
        } else {
            $description .= dateFormat($this->closing_date, 'd/m/Y', 'Y-m-d');
        }
        return $description;
    }

    public function getOpeningBalance()
    {
        if ($this->account_type == 'retail-store' && $this->retailAccount !== null) {
            $previous_book = $this->retailAccount->accountBooks()
                ->where('created_at', '<', $this->created_at)
                ->latest()
                ->first();

            if ($previous_book && $previous_book->balance_carry_forward) {
                return (float) $this->previous_balance + (float) $previous_book->closing_balance;
            }
        }

        return (float) $this->previous_balance;
    }

    public function getDescriptionBalance()
    {
        // Simple fallback since manual closing and complex calculations belong to retail stores
        if ($this->open) {
            return (float)$this->previous_balance; 
        }
        return (float)$this->closing_balance;
    }
}
