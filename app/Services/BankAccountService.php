<?php

namespace App\Services;

use App\Repositories\BankAccountRepository;
use Carbon\Carbon;
use App\Models\BankAccount;
use App\Models\AccountBook;
use App\Models\Transaction;
use App\Models\TransactionHistory;
use Illuminate\Http\Request;
use App\Models\View\BankAccountEntry;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;

class BankAccountService
{
    protected $repository;

    public function __construct(BankAccountRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $trashBankAccounts = BankAccount::onlyTrashed()->get();

        $sortBy = $request->input('sort_by', 'id');
        $sortDirection = $request->input('sort_direction', 'desc');
        $perPage = $request->input('per_page', 50);

        $query = BankAccount::query()
            ->when($request->bank, function ($q, $bank) {
                $q->where('bank', 'like', '%' . $bank . '%');
            })
            ->when($request->branch, function ($q, $branch) {
                $q->where('branch', 'like', '%' . $branch . '%');
            })
            ->when($request->account_no, function ($q, $account_no) {
                $q->where('account_no', 'like', '%' . $account_no . '%');
            });

        if (in_array($sortBy, ['id', 'bank', 'branch', 'account_no'])) {
            $query->orderBy($sortBy, $sortDirection);
        } else {
            $query->orderBy('id', 'desc');
        }

        $bankAccounts = $query->paginate($perPage)->withQueryString();

        return Inertia::render('BankAccount/Index', [
            'bankAccounts' => $bankAccounts,
            'trashBankAccounts' => $trashBankAccounts,
            'filters' => $request->only(['bank', 'branch', 'account_no', 'sort_by', 'sort_direction', 'per_page']),
        ]);
    }

    public function create()
    {
        // Handled via modal in the index view
    }

    public function store(Request $request)
    {
        $bankAccount = $this->repository->store($request->all());
        logActivity($bankAccount, $bankAccount->id, 'bank-account.show', 'Create', __('bank_created'));

        return Redirect::route('bank-account.index')->with('success', __('pages.bank_created') ?? 'Bank account created successfully');
    }

    public function show(BankAccount $bankAccount)
    {
        $bankAccount = $this->repository->show($bankAccount);
        
        // Eager load and order account books
        $bankAccount->load(['accountBooks' => function ($q) {
            $q->orderBy('created_at', 'desc')->orderBy('id', 'desc');
        }]);

        // Dynamically calculate the current balance of each book for display
        $accountBooks = $bankAccount->accountBooks->map(function ($book) {
            $openingBalanceValue = $book->getOpeningBalance();
            $latestEntry = BankAccountEntry::withRunningBalance($book->id, $openingBalanceValue)
                ->orderBy('id', 'asc')
                ->get()
                ->last();
            $book->calculated_balance = $latestEntry ? $latestEntry->current_balance : $book->getOpeningBalance();
            $book->description = $book->getDescription();
            return $book;
        });

        return Inertia::render('BankAccount/Show', [
            'bankAccount' => $bankAccount,
            'accountBooks' => $accountBooks,
        ]);
    }

    public function edit(BankAccount $bankAccount)
    {
        // Handled via modal or inline
    }

    public function update(Request $request, BankAccount $bankAccount)
    {
        $bankAccount = $this->repository->update($bankAccount, $request->all());
        logActivity($bankAccount, $bankAccount->id, 'bank-account.show', 'Update', __('Bank account information has been edited'));

        return Redirect::route('bank-account.show', $bankAccount->id)->with('success', __('pages.Bank account information has been edited') ?? 'Bank account updated successfully');
    }

    public function destroy(BankAccount $bankAccount)
    {
        $this->repository->destroy($bankAccount);
        return Redirect::route('bank-account.index')->with('success', __('pages.Bank account information has been deleted') ?? 'Bank account deleted successfully');
    }

    public function forceDelete($id)
    {
        $message = $this->repository->forceDelete($id);
        return Redirect::back()->with('success', $message['success']);
    }

    public function restore($id)
    {
        $message = $this->repository->restore($id);
        return Redirect::back()->with('success', $message['success']);
    }

    public function entryDateCurrection($id)
    {
        $entry = BankAccountEntry::find($id);

        $transaction = Transaction::where('from_account_id', $entry->account_book_id)
            ->where('created_at', $entry->created_at)
            ->where('amount', $entry->total_amount)
            ->first();
        if ($transaction) {
            $transactionHistory = TransactionHistory::where('transaction_id_one', $transaction->id)
                ->where('entry_bank_id', $entry->id)->first();
            if ($transactionHistory) {
                $transactionHistory->created_at = $entry->created_at;
                $transactionHistory->updated_at = $entry->updated_at;
                $transactionHistory->save();
            }
        }
        return Redirect::back()->with('success', 'Entry date correction processed successfully');
    }
}
