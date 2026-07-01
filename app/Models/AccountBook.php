<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FactoryAccountEntry;
use App\Enums\FactoryEntryType;
use App\Models\GiftSupplier;
use App\Models\GiftPurchase;
use App\Models\GiftSupplierAccountEntry;
use App\Models\Transaction;
use App\Models\Cheque;

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

    public function account()
    {
        return $this->morphTo();
    }

    public function assetAccount()
    {
        return $this->belongsTo(AssetBook::class, 'account_id', 'id');
    }

    public function liabilityAccount()
    {
        return $this->belongsTo(Liabilitie::class, 'account_id', 'id');
    }

    public function factoryAccount()
    {
        return $this->belongsTo(Factory::class, 'account_id', 'id');
    }

    public function retailAccount()
    {
        return $this->belongsTo(RetailStore::class, 'account_id', 'id');
    }

    public function giftSupplierAccount()
    {
        return $this->belongsTo(GiftSupplier::class, 'account_id', 'id');
    }

    public function giftPurchases()
    {
        if ($this->account_type !== 'gift-supplier') {
            return null;
        }
        return $this->hasMany(GiftPurchase::class);
    }

    public function transactionsFrom()
    {
        return $this->hasMany(Transaction::class, 'from_account_id');
    }

    public function transactionsTo()
    {
        return $this->hasMany(Transaction::class, 'to_account_id');
    }

    public function closingTransactions()
    {
        return $this->hasMany(Transaction::class, 'closing_id');
    }

    public function cheques()
    {
        return $this->hasMany(Cheque::class);
    }

    public function closingCheques()
    {
        return $this->hasMany(Cheque::class, 'closing_id');
    }

    public function ManualClosingdata()
    {
        return $this->hasOne(ManualClosing::class, 'account_book_id', 'id');
    }

    public function giftSupplierEntries()
    {
        return $this->hasMany(GiftSupplierAccountEntry::class, 'account_book_id', 'id');
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

    public function closingPaidAmount()
    {
        return $this->closingTransactions()->sum('amount');
    }

    public function getDescriptionBalance()
    {
        if ($this->account_type === 'factory' || $this->account_type === 'gift-supplier') {
            if ($this->open) {
                return $this->getCalculatedBalance();
            }
            return (float)$this->closing_balance;
        }

        if ($this->account_type === 'retail-store') {
            if ($this->open) {
                return $this->getCalculatedBalance();
            }
            return (float)$this->closing_balance - $this->closingPaidAmount();
        }

        // Simple fallback since manual closing and complex calculations belong to retail stores
        if ($this->open) {
            return (float)$this->previous_balance; 
        }
        return (float)$this->closing_balance;
    }

    public function getCalculatedBalance()
    {
        if ($this->account_type === 'factory') {
            return $this->calculateFactoryBalance();
        } elseif ($this->account_type === 'gift-supplier') {
            return $this->calculateGiftSupplierBalance();
        } elseif ($this->account_type === 'retail-store') {
            return $this->calculateRetailStoreBalance();
        }
        return $this->getOpeningBalance();
    }

    public function calculateRetailStoreBalance(): float
    {
        $sum = \DB::table('retail_store_account_entries')
            ->where('account_book_id', $this->id)
            ->whereNull('deleted_at')
            ->selectRaw('SUM(COALESCE(amount, 0) - COALESCE(paid_amount, 0)) as net_sum')
            ->value('net_sum');
        return (float)$this->getOpeningBalance() + (float)$sum;
    }

    public function calculateGiftSupplierBalance(): float
    {
        return (float)$this->getTotalProductsWorth() - array_sum([
            $this->getTotalReturnAmount(),
            $this->getTotalPayment(),
            (float)$this->commission,
            (float)$this->staff,
            (float)$this->staff_shoe_amount,
            $this->getTotalClosingPayment(),
        ]);
    }

    private function calculateFactoryBalance(): float
    {
        return (float)$this->getTotalProductsWorth() - array_sum([
            $this->getTotalReturnAmount(),
            $this->getTotalPayment(),
            (float)$this->commission,
            (float)$this->staff,
            (float)$this->staff_shoe_amount,
            $this->getTotalClosingPayment(),
        ]);
    }

    public function getTotalProductsWorth()
    {
        if ($this->account_type === 'factory') {
            return FactoryAccountEntry::where('account_book_id', $this->id)
                ->where('entry_type', FactoryEntryType::Purchase)
                ->where('status', 1)
                ->sum('total_amount');
        }
        if ($this->account_type === 'gift-supplier') {
            return GiftSupplierAccountEntry::where('account_book_id', $this->id)
                ->where('entry_type', 0) // Purchase
                ->sum('total_amount');
        }
        return 0;
    }

    public function getTotalPurchaseProducts()
    {
        if ($this->account_type === 'factory') {
            return FactoryAccountEntry::where('account_book_id', $this->id)
                ->where('entry_type', FactoryEntryType::Purchase)
                ->where('status', 1)
                ->sum('count');
        }
        if ($this->account_type === 'gift-supplier') {
            return GiftSupplierAccountEntry::where('account_book_id', $this->id)
                ->sum('count');
        }
        return 0;
    }



    public function getTotalClosingTransactionAmount()
    {
        if ($this->open) {
            return 0;
        }
        if ($this->account_type === 'factory') {
            return FactoryAccountEntry::where('account_book_id', $this->id)
                ->where('entry_type', FactoryEntryType::Payment)
                ->where('closing_id', $this->id)
                ->where('status', 1)
                ->sum('total_amount');
        }
        if ($this->account_type === 'gift-supplier') {
            return GiftSupplierAccountEntry::where('account_book_id', $this->id)
                ->where('entry_type', 5) // ClosingPayment
                ->sum('payment_amount');
        }
        if ($this->account_type === 'retail-store') {
            return $this->closingTransactions()->sum('amount');
        }
        return 0;
    }

    public function getTotalClosingChequeAmount()
    {
        if ($this->open || ($this->account_type !== 'factory' && $this->account_type !== 'gift-supplier')) {
            return 0;
        }
        return $this->closingCheques()->sum('amount');
    }

    public function getTotalClosingPayment()
    {
        return $this->getTotalClosingTransactionAmount() + $this->getTotalClosingChequeAmount();
    }

    public function getPaymentPercentage()
    {
        if ($this->account_type === 'factory' || $this->account_type === 'gift-supplier') {
            $purchase = $this->getTotalProductsWorth();
            $payment = $this->getTotalPayment();
            if ($purchase == 0) {
                return 0;
            }
            return ($payment / $purchase) * 100;
        }
        return 0;
    }

    public function getTotalPurchasePrice()
    {
        return $this->getTotalProductsWorth();
    }

    protected array $memoized = [];

    protected function memoize(string $key, callable $callback)
    {
        if (!array_key_exists($key, $this->memoized)) {
            $this->memoized[$key] = $callback();
        }
        return $this->memoized[$key];
    }

    public function accountEntries()
    {
        if ($this->account_type == 'factory') {
            return $this->hasMany(\App\Models\View\FactoryAccountEntry::class, 'account_book_id');
        }
        if ($this->account_type == 'retail-store') {
            return $this->hasMany(\App\Models\View\RetailStoreAccountEntry::class, 'account_book_id');
        }
        if ($this->account_type == 'gift-supplier') {
            return $this->hasMany(\App\Models\GiftSupplierAccountEntry::class, 'account_book_id');
        }
        return null;
    }

    public function getTotalSale()
    {
        if ($this->account_type == 'retail-store') {
            return $this->memoize('total_sale', function () {
                return $this->accountEntries()
                    ->where('entry_type', 0)
                    ->where('is_discount_product_sale', 0)
                    ->sum('total_retail_price');
            });
        }
        return 0;
    }

    public function getTotalDiscountProductSale()
    {
        if ($this->account_type == 'retail-store') {
            return $this->memoize('total_discount_product_sale', function () {
                return $this->accountEntries()->where('entry_type', 0)->where('is_discount_product_sale', 1)->sum('total_retail_price');
            });
        }
        return 0;
    }

    public function getTotalSaleMinusCommission()
    {
        if ($this->account_type == 'retail-store') {
            return $this->memoize('total_sale_minus_commission', function () {
                return $this->accountEntries()->where('entry_type', 0)->where('is_discount_product_sale', 0)->sum(\DB::raw('total_retail_price - commission_amount'));
            });
        }
        return 0;
    }

    public function getTotalDiscountProductSaleMinusCommission()
    {
        if ($this->account_type == 'retail-store') {
            return $this->memoize('total_discount_product_sale_minus_commission', function () {
                return $this->accountEntries()->where('entry_type', 0)->where('is_discount_product_sale', 1)->sum(\DB::raw('total_retail_price - commission_amount'));
            });
        }
        return 0;
    }

    public function getTotalReturnAmount()
    {
        if ($this->account_type == 'factory') {
            return $this->memoize('total_return_amount', function () {
                return $this->accountEntries()->where('entry_type', 1)->sum('total_amount');
            });
        }
        if ($this->account_type == 'retail-store') {
            return $this->memoize('total_return_amount', function () {
                return \App\Models\ReturnFromRetailEntry::getTotalReturnNetAmount($this->id);
            });
        }
        return 0;
    }

    public function getTotalReturnMinusCommission()
    {
        if ($this->account_type == 'retail-store') {
            return $this->memoize('total_return_minus_commission', function () {
                return \App\Models\ReturnFromRetailEntry::getTotalReturnGrossAmount($this->id);
            });
        }
        return 0;
    }

    public function getTotalPayment()
    {
        return $this->memoize('total_payment', function () {
            if ($this->account_type === 'factory') {
                return $this->accountEntries()
                    ->where('entry_type', 2)->whereNull('closing_id')->where('status', 1)->sum('total_amount');
            } else if ($this->account_type === 'retail-store') {
                return $this->accountEntries()
                    ->where(function ($query) {
                        $query->where('entry_type', 0)
                            ->orWhere(function ($q) {
                                $q->where('entry_type', 3)->whereNull('closing_id');
                            });
                    })->sum('paid_amount');

            } else if ($this->account_type === 'gift-supplier') {
                return $this->accountEntries()
                    ->where('account_book_id', $this->id)
                    ->sum('payment_amount');
            }
            return 0;
        });
    }

    public function getTotalTransport()
    {
        if ($this->account_type == 'retail-store') {
            return $this->memoize('total_transport', function () {
                return $this->accountEntries()->where('entry_type', 0)->sum('transport');
            });
        }
        return 0;
    }

    public function getTotalExpense()
    {
        if ($this->account_type == 'retail-store') {
            return $this->memoize('total_expense', function () {
                return $this->accountEntries()->whereIn('entry_type', [0, 2])->sum('expense_amount');
            });
        }
        return 0;
    }

    public function getTotalDiscount()
    {
        if ($this->account_type == 'retail-store') {
            return $this->memoize('total_discount', function () {
                return $this->accountEntries()->where('entry_type', 0)->sum('discount');
            });
        }
        return 0;
    }

    public function getBalanceBeforeClosing()
    {
        return $this->getCalculatedBalance() + $this->getTotalClosingPayment();
    }

    public function closingRetialStoreCommitments()
    {
        return $this->hasMany(\App\Models\Commitment::class, 'account_book_id', 'id');
    }
}

