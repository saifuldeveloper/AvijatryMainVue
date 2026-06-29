<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use App\Models\View\BankAccountEntry;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    public static function createBankToCashTransaction($type, $bankAccount, $amount, $description = '', $createdAt)
    {
        $cashAccount = BankAccount::getCashAccount();
        $bankAccount = BankAccount::find($bankAccount);

        switch ($type) {
            case 'deposit':
                $from_account_id = $cashAccount->getCurrentAccountBook()->id;
                $to_account_id = $bankAccount->getCurrentAccountBook()->id;
                break;

            case 'withdraw':
                $from_account_id = $bankAccount->getCurrentAccountBook()->id;
                $to_account_id = $cashAccount->getCurrentAccountBook()->id;
                break;

            default:
                return null;
        }

        $entry = new BankAccountEntry;
        $entry->entry_id = $bankAccount->id;
        $entry->entry_type = ($type == 'deposit') ? 0 : 1;
        $entry->account_book_id = $bankAccount->getCurrentAccountBook()->id;
        $entry->account_name = $bankAccount->bank;
        $entry->account_id = $bankAccount->id;
        $entry->account_type = 'bank-account';
        $entry->type = $type;
        $entry->description = $description;
        $entry->to_account_book_id = $to_account_id;
        $entry->total_amount = $amount;
        $entry->created_at = $createdAt;
        $entry->updated_at = $createdAt;
        $entry->save();

        $transaction = new Transaction;
        $transaction->fill(compact('from_account_id', 'to_account_id', 'amount', 'description'));
        $transaction->transaction_type = $type;
        $transaction->payment_type = 'bank-account';
        $transaction->created_at = $createdAt;
        $transaction->updated_at = $createdAt;
        $transaction->save();

        $transactionHistory = new TransactionHistory;
        $transactionHistory->transaction_id_one = $transaction->id;
        $transactionHistory->entry_bank_model = get_class($entry);
        $transactionHistory->entry_bank_id = $entry->id;
        $transactionHistory->description = $description;
        $transactionHistory->type = 'bikrikhata';
        $transactionHistory->created_at = $createdAt;
        $transactionHistory->updated_at = $createdAt;
        $transactionHistory->save();

        logActivity($bankAccount, $bankAccount->id, 'bank-account.show', 'Create', ($type == 'withdraw') ? 'ব্যাংক থেকে টাকা উত্তোলন  হয়েছে ' : 'ব্যাংকে টাকা জমা দেয়া হয়েছে ');

        return $transaction;
    }

    public static function createTransaction($accountType, $account, $type, $bankAccount, $amount, $description = '', $createdAt, $attachment = null, $closing = null)
    {
        $bankAccount = BankAccount::where('id', $bankAccount)->first();
        switch ($accountType) {
            case 'account-book':
                break;
            case 'factory':
                $account = Factory::find($account);
                break;
            case 'retail-store':
                $account = RetailStore::find($account);
                break;
            case 'retail-closing':
                $account = RetailStore::find($account);
                break;
            case 'retail-store-expense':
                $account = RetailStore::find($account);
                break;
            case 'gift-supplier':
                $account = GiftSupplier::find($account);
                break;
            case 'cheque':
                $account = Cheque::where('cheque_number', $account)->first();
                break;
            case 'employee-salary':
                $account = Employee::find($account);
                break;
            case 'employee-bonus-eid-ul-fitor':
                $account = Employee::find($account);
                break;
            case 'employee-bonus-eid-ul-adha':
                $account = Employee::find($account);
                break;
            case 'employee-closing':
                $account = Employee::find($account);
                break;
            case 'employee':
                $account = Employee::find($account);
                break;
            case 'loan-receipt':
            case 'loan-payment':
                $account = Loan::find($account);
                break;
            case 'expense':
                $account = Expense::find($account);
                break;
            case 'liability':
                $account = Liabilitie::find($account);
                break;
            case 'asset-receipt':
            case 'asset-payment':
                $account = AssetBook::find($account);
                break;
            default:
                return null;
        }

        switch ($type) {
            case 'income':
                if ($accountType == 'account-book') {
                    $from_account_id = $account;
                } else {
                    $from_account_id = $account->getCurrentAccountBook()->id;
                }
                $to_account_id = $bankAccount->getCurrentAccountBook()->id;
                break;
            case 'expense':
                if ($accountType == 'account-book') {
                    $to_account_id = $account;
                } else {
                    $to_account_id = $account->getCurrentAccountBook()->id;
                }
                $from_account_id = $bankAccount->getCurrentAccountBook()->id;
                break;
        }

        $transactionHistory = new TransactionHistory;
        $transactionHistory->model = get_class($account);
        $transactionHistory->model_id = $account->id;
        $transactionHistory->description = $description;

        if ($bankAccount->account_no !== 'cash') {
            $transaction = new Transaction;
            $transaction->fill(compact('from_account_id', 'to_account_id', 'amount', 'description'));
            $transaction->transaction_type = ($type == 'deposit') ? 'deposit' : (($type == 'expense') ? 'withdraw' : (($type == 'income') ? 'deposit' : 'withdraw'));
            $transaction->payment_type = $accountType;
            $transaction->created_at = $createdAt;
            $transaction->updated_at = $createdAt;
            $transaction->save();

            $transactionHistory->transaction_id_one = $transaction->id;
        }

        $transaction = new Transaction;
        $transaction->fill(compact('from_account_id', 'to_account_id', 'amount', 'description'));
        if ($closing !== null) {
            $transaction->closing_id = $closing;
        }
        if ($attachment !== null) {
            $transaction->attachment()->associate($attachment);
        }
        if ($accountType == 'loan-receipt' || $accountType == 'loan-payment') {
            $transaction->transaction_type = $accountType == 'loan-receipt' ? 'income' : 'expense';
        }
        $transaction->transaction_type = $type;
        $transaction->payment_type = $accountType;
        $transaction->created_at = $createdAt;
        $transaction->updated_at = $createdAt;
        $transaction->save();

        $transactionHistory->transaction_id_two = $transaction->id;

        $entry = new BankAccountEntry;
        $entry->entry_id = $bankAccount->id;
        $entry->entry_type = ($type == 'deposit') ? 0 : 1;
        $entry->account_book_id = $bankAccount->getCurrentAccountBook()->id;
        $entry->account_name = $bankAccount->bank;
        $entry->account_id = $bankAccount->id;
        $entry->account_type = $accountType;
        $entry->description = $description;
        $entry->to_account_book_id = $account->getCurrentAccountBook()->id;
        $entry->total_amount = $amount;
        $entry->created_at = $createdAt;
        $entry->updated_at = $createdAt;
        $entry->save();

        $transactionHistory->entry_bank_model = get_class($entry);
        $transactionHistory->entry_bank_id = $entry->id;
        $transactionHistory->created_at = $createdAt;
        $transactionHistory->updated_at = $createdAt;
        $transactionHistory->save();

        logActivity($bankAccount, $bankAccount->id, 'bank-account.show', 'Create', ($type == 'withdraw') ? 'ব্যাংক থেকে টাকা উত্তোলন  হয়েছে ' : 'ব্যাংকে টাকা জমা দেয়া হয়েছে ');

        return [
            'transaction' => $transaction,
            'transactionHistory' => $transactionHistory,
        ];
    }

    public static function getIncomesOn($date)
    {
        return self::queryIncomesOn($date)->get();
    }
    public static function sumIncomesOn($date)
    {
        $query = self::queryIncomesOn($date);
        $sql = $query->toSql();
        return DB::table(DB::raw("({$sql}) as query_table"))->mergeBindings($query->getQuery())->sum('amount');
    }

    public static function sumIncomesWithPreviousBalanceOn($date)
    {
        $previousCashBalance = BankAccount::getCashAccount()->getCurrentAccountBook()->getBalanceBefore($date);
        $incomeSum = self::sumIncomesOn($date);

        return $previousCashBalance + $incomeSum;
    }

    public static function getExpensesOn($date)
    {
        return self::queryExpensesOn($date)->get();
    }

    public static function sumExpensesOn($date)
    {
        $query = self::queryExpensesOn($date);
        $sql = $query->toSql();
        return DB::table(DB::raw("({$sql}) as query_table"))->mergeBindings($query->getQuery())->sum('amount');
    }

    private static function queryIncomesOn($date)
    {
        $current = \Carbon\CarbonImmutable::parse($date);
        $next = $current->addDay();
        return self::where('created_at', '>=', $current)
            ->where('created_at', '<', $next)
            ->whereIn('transaction_type', ['withdraw', 'income'])
            ->groupBy('from_account_id', 'payment_type')
            ->selectRaw('
            0 as id,
            0 as bank_withdrawal,
            from_account_id,
            payment_type,
            SUM(amount) as amount,
            GROUP_CONCAT(description SEPARATOR "; ") as descriptions
        ');
    }

    private static function queryExpensesOn($date)
    {
        $current = \Carbon\CarbonImmutable::parse($date);
        $next = $current->addDay();

        return self::where('created_at', '>=', $current)
            ->where('created_at', '<', $next)
            ->whereIn('transaction_type', ['deposit', 'expense'])
            ->groupBy('to_account_id', 'payment_type')
            ->selectRaw('
            0 as id,
            0 as bank_deposit,
            to_account_id,
            payment_type,
            SUM(amount) as amount,
            GROUP_CONCAT(description SEPARATOR "; ") as descriptions
        ');
    }

    public function fromAccount()
    {
        return $this->belongsTo(AccountBook::class, 'from_account_id');
    }

    public function toAccount()
    {
        return $this->belongsTo(AccountBook::class, 'to_account_id');
    }

    public function closingAccount()
    {
        return $this->belongsTo(AccountBook::class, 'closing_id');
    }

    public function attachment()
    {
        return $this->morphTo();
    }

    public function getTransactionTypeAttribute()
    {
        if (isset($this->bank_withdrawal)) {
            if ($this->bank_withdrawal == 1) {
                return 'ব্যাংক তোলা - ' . $this->fromAccount->BankAccount->name;
            } else {
                return ($this->fromAccount->account_type == 'loan' ? 'হাওলাত - ' : '') . $this->fromAccount->BankAccount->name;
            }
        }
        if (isset($this->bank_deposit)) {
            if ($this->bank_deposit == 1) {
                return 'ব্যাংক জমা - ' . $this->toAccount->BankAccount->name;
            } else {
                return ($this->toAccount->account_type == 'loan' ? 'হাওলাত - ' : '') . $this->toAccount->BankAccount->name;
            }
        }
        if ($this->from_account_id === null) {
            if ($this->toAccount->account_type == 'loan') {
                return 'হাওলাত - ' . $this->toAccount->BankAccount->name;
            } else {
                return $this->toAccount->BankAccount->name;
            }
        } else if ($this->to_account_id === null) {
            if ($this->fromAccount->account_type == 'loan') {
                return 'হাওলাত - ' . $this->fromAccount->BankAccount->name;
            } else {
                return $this->fromAccount->BankAccount->name;
            }
        } else if (@$this->fromAccount->BankAccount->account_no == 'cash' && $this->toAccount->account_type == 'bank-account') {
            return 'ব্যাংক জমা';
        } else if (@$this->toAccount->BankAccount->account_no == 'cash' && $this->fromAccount->account_type == 'bank-account') {
            return 'ব্যাংক তোলা';
        } else if (@$this->fromAccount->account_type == 'bank-account') {
            return @$this->toAccount->BankAccount->name;
        } else {
            return @$this->fromAccount->BankAccount->name;
        }
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
    protected $with = ['fromAccount.BankAccount', 'toAccount.BankAccount'];
    protected $appends = ['transaction_type'];
    protected $fillable = ['from_account_id', 'to_account_id', 'amount', 'description'];
}
