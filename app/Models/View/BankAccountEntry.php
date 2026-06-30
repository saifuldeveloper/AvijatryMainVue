<?php

namespace App\Models\View;

use App\Models\User;
use App\Models\AccountBook;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BankAccountEntry extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bank_account_entries';

    protected $casts = [
        'total_amount' => 'float',
    ];

    public function accountBook()
    {
        return $this->belongsTo(AccountBook::class, 'account_book_id');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function toAccount()
    {
        return $this->belongsTo(AccountBook::class, 'to_account_book_id');
    }

    protected static function booted()
    {
        static::deleting(function ($entry) {
            if (auth()->check()) {
                $entry->deleted_by = auth()->id();
                DB::table((new self)->getTable())
                    ->where('id', $entry->id)
                    ->update(['deleted_by' => auth()->id()]);
            }
        });
    }

    public static function makeOpeningRow(float $openingBalance): self
    {
        $row = new self();
        $row->id = 0;
        $row->account_type = 'opening';
        $row->description = "Opening Balance";
        $row->type = 'opening';
        $row->total_amount = 0;
        $row->current_balance = $openingBalance;
        return $row;
    }

    public function scopeWithRunningBalance(Builder $query, int $accountBookId, float $openingBalance): Builder
    {
        $openingBalance = (float) $openingBalance;
        return $query->select(
            'bank_account_entries.*',
            DB::raw("({$openingBalance} + SUM(
                CASE
                    WHEN type IN ('deposit', 'income') THEN total_amount
                    ELSE -total_amount
                END
            ) OVER (ORDER BY id ASC)) as current_balance")
        )
            ->where('account_book_id', $accountBookId)
            ->orderBy('id', 'asc');
    }

    public function entries()
    {
        if ($this->type === 'opening') {
            return __('pages.old') . ' - (' . $this->current_balance . ')';
        }
        $desc = $this->description ? " ({$this->description})" : '';
        $format = fn($prefix, $text) => $desc ? ($prefix . $text . " - " . $desc) : ($prefix . $text);
        switch ($this->account_type) {
            case 'bank-account':
                $bankAccount = $this->accountBook?->BankAccount;
                if (!$bankAccount) {
                    return $this->account_type;
                }
                $name = $bankAccount->bank . ' - ' . $bankAccount->account_no;
                $isCash = $bankAccount->account_no === 'cash';
                $isDeposit = ($isCash && $this->type === 'income') || (!$isCash && $this->type === 'deposit');

                $text = $isDeposit ? __('pages.cash_deposit') : __('pages.cash_withdraw');
                return $text;

            case 'retail-store':
            case 'retail-closing':
                $store = $this->toAccount?->retailAccount;
                $name = $store ? $store->name : '';
                if (in_array($this->type, ['deposit', 'income'])) {
                    $text = $this->account_type === 'retail-store'
                        ? __('pages.party submission')
                        : __('pages.Party submission (closing)');
                } else {
                    $text = $this->account_type === 'retail-store'
                        ? __('pages.store_withdraw')
                        : $this->account_type;
                }
                return $format($name ? $name . ' - ' : '', $text);
            case 'factory':
                $name = $this->toAccount?->account?->name ?? '';
                return $format($name ? $name . ' - ' : '', __('pages.factory_payment'));
            case 'gift-supplier':
                $name = $this->toAccount?->giftSupplierAccount?->name ?? '';
                return $format($name ? $name . ' - ' : '', __('pages.gift_supplier_payment'));
            case 'expense':
                $name = $this->toAccount?->ExpenceAccount->name ?? '';
                return $format($name ? $name . ' - ' : '', __('pages.others_expenses'));
            case 'employee':
                $name = $this->toAccount?->EmployeeAccount?->name ?? '';
                return $format($name ? $name . ' - ' : '', __('pages.employee'));
            case 'loan-receipt':
                $name = $this->toAccount?->LoanAccount?->name ?? '';
                return $format($name ? $name . ' - ' : '', __('pages.loan_receipt'));
            case 'loan-payment':
                $name = $this->toAccount?->LoanAccount?->name ?? '';
                return $format($name ? $name . ' - ' : '', __('pages.loan_payment'));
        }
    }
}
