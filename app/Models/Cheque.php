<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cheque extends Model
{
    use HasFactory, SoftDeletes;
    // Static functions
    public static function issue($cheque_number, $account_book, $amount, $attachment_type, $due_date, $createdAt, $attachment = null, $closing = null)
    {
        $cheque = new Cheque();
        $due_date = !empty($due_date) ? (function ($date) {
            foreach (['d/m/Y', 'd-m-Y', 'Y-m-d'] as $format) {
                try {
                    return Carbon::createFromFormat($format, $date)->format('Y-m-d');
                } catch (\Exception $e) {}
            }
            return null;
        })($due_date) : null;
        $cheque->fill(compact('cheque_number', 'amount', 'due_date'));
        $cheque->attachment_type = $attachment_type;
        $cheque->created_at = $createdAt;
        $cheque->updated_at = $createdAt;

        if ($attachment !== null) {
            $cheque->attachment_id = $attachment->id;
        }
        if ($closing !== null) {
            $cheque->closing_id = $closing;
        }

        $account_book->cheques()->save($cheque);
        $account_book = new AccountBook;
        $account_book->account_id = $cheque->id;
        $account_book->account_type = 'cheque';
        $account_book->created_at = $createdAt;
        $account_book->updated_at = $createdAt;
        $account_book->save();

        return $cheque;
    }

    public function getCurrentAccountBook()
    {
        return $this->accountBooks()->latest()->first();
    }

    // Relationships
    public function accountBooks()
    {
        return $this->hasMany(AccountBook::class, 'account_id', 'id')->where('account_type', 'cheque');
    }
    public function accountBook()
    {
        return $this->belongsTo(AccountBook::class, 'account_id', 'id')->where('account_type', 'cheque');
    }

    public function ChequeOnarAccount()
    {
        return $this->belongsTo(AccountBook::class, 'account_book_id');
    }

    public function closingAccount()
    {
        return $this->belongsTo(AccountBook::class, 'closing_id');
    }

    public function entries()
    {
        // Fallback or relationship if ChequeAccountEntries class exists, but it's okay for now.
        return $this->hasMany(ChequeAccountEntries::class, 'entry_id', 'cheque_number')
            ->withTrashed()
            ->orderBy('created_at', 'desc');
    }

    public function getCurrentBalanceAttribute()
    {
        $final_balance = 0;
        $desired_balances = [];
        foreach ($this->entries->reverse() as $entry) {
            if ($entry->entry_type == 0) {
                $final_balance += $entry->total_amount;
            }
            $desired_balances[] = $final_balance;
        }

        return array_reverse($desired_balances);
    }

    public function getNameAttribute()
    {
        $owner = $this->ChequeOnarAccount;
        if (!$owner)
            return 'চেক - (No Owner)';

        $accountName = '';
        if ($owner->account_type == 'factory') {
            $accountName = $owner->account?->name ?? 'Unknown Factory';
            $prefix = $this->closing_id ? 'ক্লোজিং চেক' : 'চেক';
        } else {
            $accountName = $owner->giftSupplierAccount?->name ?? 'Unknown Supplier';
            $prefix = 'চেক';
        }

        return "{$prefix} - ({$this->cheque_number}) - {$accountName}";
    }

    public function getDueAmountAttribute()
    {
        $firstBook = $this->accountBooks()->first();
        if (!$firstBook)
            return $this->amount;
        $paid = $firstBook->transactionsTo()?->sum('amount') ?? 0;
        return $this->amount - $paid;
    }

    protected $fillable = ['cheque_number', 'account_book_id', 'amount', 'due_date'];
    protected $appends = ['name', 'due_amount'];

    protected $casts = [
        'due_date' => 'date',
    ];
}
