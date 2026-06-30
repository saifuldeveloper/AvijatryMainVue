<?php

namespace App\Services;

use App\Models\AccountBook;
use App\Models\Factory;
use App\Models\FactoryAccountEntry;
use App\Models\GiftSupplier;
use App\Models\GiftSupplierAccountEntry;
use App\Models\BankAccount;
use App\Models\View\BankAccountEntry;
use App\Enums\GiftSupplierEntryType;
use App\Repositories\AccountBookRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountBookService
{
    protected AccountBookRepository $repository;

    public function __construct(AccountBookRepository $repository)
    {
        $this->repository = $repository;
    }

    public function show(AccountBook $accountBook, Request $request)
    {
        $accountBook = $this->repository->show($accountBook);

        if ($accountBook->account_type === 'bank-account') {
            $bankAccount = BankAccount::findOrFail($accountBook->account_id);
            $openingBalance = (float)$accountBook->getOpeningBalance();

            $query = BankAccountEntry::where('account_book_id', $accountBook->id);
            $trashQuery = BankAccountEntry::onlyTrashed()->where('account_book_id', $accountBook->id);

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $start = Carbon::parse($request->input('start_date'))->startOfDay();
                $end = Carbon::parse($request->input('end_date'))->endOfDay();
                $query->whereBetween('created_at', [$start, $end]);
                $trashQuery->whereBetween('created_at', [$start, $end]);
            }

            if ($request->filled('description')) {
                $desc = $request->input('description');
                $query->where('description', 'like', '%' . $desc . '%');
                $trashQuery->where('description', 'like', '%' . $desc . '%');
            }

            if ($request->filled('deposit')) {
                $query->where('total_amount', $request->deposit)->whereIn('type', ['deposit', 'income']);
                $trashQuery->where('total_amount', $request->deposit)->whereIn('type', ['deposit', 'income']);
            }

            if ($request->filled('withdraw')) {
                $query->where('total_amount', $request->withdraw)->whereIn('type', ['withdraw', 'expense']);
                $trashQuery->where('total_amount', $request->withdraw)->whereIn('type', ['withdraw', 'expense']);
            }

            $minDate = BankAccountEntry::where('account_book_id', $accountBook->id)->min('created_at');
            $maxDate = BankAccountEntry::where('account_book_id', $accountBook->id)->max('created_at');
            $defaultDateRange = '';
            if ($minDate && $maxDate) {
                $defaultDateRange = Carbon::parse($minDate)->format('Y-m-d') . ' - ' . Carbon::parse($maxDate)->format('Y-m-d');
            }

            $allEntries = BankAccountEntry::where('account_book_id', $accountBook->id)
                ->orderBy('id', 'asc')
                ->get();

            $running = $openingBalance;
            $balancesMap = [];
            foreach ($allEntries as $ent) {
                if (in_array($ent->type, ['deposit', 'income'])) {
                    $running += $ent->total_amount;
                } else {
                    $running -= $ent->total_amount;
                }
                $balancesMap[$ent->id] = $running;
            }

            $entries = $query->orderBy('id', 'desc')->paginate(50)->withQueryString();
            $entries->getCollection()->transform(function ($item) use ($balancesMap) {
                $item->running_balance = $balancesMap[$item->id] ?? 0;
                $item->formatted_created_at = Carbon::parse($item->created_at)->format('d/m/Y');
                $item->rendered_description = $item->entries();
                return $item;
            });

            $totalDeposit = BankAccountEntry::where('account_book_id', $accountBook->id)
                ->whereIn('type', ['deposit', 'income'])
                ->sum('total_amount');

            $totalExpense = BankAccountEntry::where('account_book_id', $accountBook->id)
                ->whereIn('type', ['withdraw', 'expense'])
                ->sum('total_amount');

            $latestEntry = BankAccountEntry::withRunningBalance($accountBook->id, $openingBalance)
                ->orderBy('id', 'asc')
                ->get()
                ->last();
            $currentBalance = $latestEntry ? $latestEntry->current_balance : $openingBalance;

            return Inertia::render('BankAccount/AccountBook', [
                'bankAccount' => $bankAccount,
                'accountBook' => $accountBook,
                'entries' => $entries,
                'defaultDateRange' => $defaultDateRange,
                'filters' => $request->only(['start_date', 'end_date', 'description', 'deposit', 'withdraw']),
                'summary' => [
                    'opening_balance' => $openingBalance,
                    'total_deposit' => $totalDeposit,
                    'total_expense' => $totalExpense,
                    'current_balance' => $currentBalance,
                    'description' => $accountBook->getDescription(),
                ]
            ]);
        }

        if ($accountBook->account_type === 'gift-supplier') {
            $giftSupplier = GiftSupplier::findOrFail($accountBook->account_id);
            $entryQuery = GiftSupplierAccountEntry::where('account_book_id', $accountBook->id);
            $trashQuery = GiftSupplierAccountEntry::onlyTrashed()->where('account_book_id', $accountBook->id);

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $start = Carbon::parse($request->input('start_date'))->startOfDay();
                $end = Carbon::parse($request->input('end_date'))->endOfDay();
                $entryQuery->whereBetween('created_at', [$start, $end]);
                $trashQuery->whereBetween('created_at', [$start, $end]);
            }

            if ($request->filled('description')) {
                $desc = $request->input('description');
                $entryQuery->where('description', 'like', '%' . $desc . '%');
                $trashQuery->where('description', 'like', '%' . $desc . '%');
            }

            if ($request->filled('amount')) {
                $amt = $request->input('amount');
                $entryQuery->where(function($q) use ($amt) {
                    $q->where('total_amount', $amt)->orWhere('payment_amount', $amt);
                });
                $trashQuery->where(function($q) use ($amt) {
                    $q->where('total_amount', $amt)->orWhere('payment_amount', $amt);
                });
            }

            $minDate = GiftSupplierAccountEntry::where('account_book_id', $accountBook->id)->min('created_at');
            $maxDate = GiftSupplierAccountEntry::where('account_book_id', $accountBook->id)->max('created_at');
            $defaultDateRange = '';
            if ($minDate && $maxDate) {
                $defaultDateRange = Carbon::parse($minDate)->format('Y-m-d') . ' - ' . Carbon::parse($maxDate)->format('Y-m-d');
            }

            // Calculate running balance
            $openingBalance = (float)$accountBook->getOpeningBalance();
            $allEntries = GiftSupplierAccountEntry::where('account_book_id', $accountBook->id)
                ->orderBy('created_at', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            $running = $openingBalance;
            $balancesMap = [];
            foreach ($allEntries as $ent) {
                $running += (float)$ent->total_amount - (float)$ent->payment_amount;
                $balancesMap[$ent->id] = $running;
            }

            $entries = $entryQuery->orderBy('created_at', 'desc')->orderBy('id', 'desc')->paginate(15)->withQueryString();
            $entries->getCollection()->transform(function ($item) use ($balancesMap) {
                $item->running_balance = $balancesMap[$item->id] ?? 0;
                $item->formatted_created_at = Carbon::parse($item->created_at)->format('d/m/Y');
                return $item;
            });

            $trashedEntries = $trashQuery->orderBy('deleted_at', 'desc')->paginate(10)->withQueryString();
            $trashedEntries->getCollection()->transform(function ($item) {
                $item->formatted_created_at = Carbon::parse($item->created_at)->format('d/m/Y');
                return $item;
            });

            $totalPurchases = GiftSupplierAccountEntry::where('account_book_id', $accountBook->id)
                ->where('entry_type', GiftSupplierEntryType::Purchase)
                ->sum('total_amount');
            $totalReturns = 0;
            $totalPayments = GiftSupplierAccountEntry::where('account_book_id', $accountBook->id)
                ->where('entry_type', GiftSupplierEntryType::Payment)
                ->sum('payment_amount');

            $totalPairs = GiftSupplierAccountEntry::where('account_book_id', $accountBook->id)
                ->where('entry_type', GiftSupplierEntryType::Purchase)
                ->sum('count');
            $totalReturnPairs = 0;

            $paidPercentage = $totalPurchases > 0 ? ($totalPayments / $totalPurchases) * 100 : 0;
            $totalClosingPayment = $accountBook->getTotalClosingPayment();
            $descriptionBalance = $accountBook->getDescriptionBalance();
            $description = $accountBook->getDescription();

            return Inertia::render('GiftSupplier/AccountBook', [
                'giftSupplier' => $giftSupplier,
                'accountBook' => $accountBook,
                'entries' => $entries,
                'trashedEntries' => $trashedEntries,
                'defaultDateRange' => $defaultDateRange,
                'filters' => $request->only(['start_date', 'end_date', 'description', 'amount']),
                'summary' => [
                    'opening_balance' => $openingBalance,
                    'total_purchases' => $totalPurchases,
                    'total_returns' => $totalReturns,
                    'total_payments' => $totalPayments,
                    'total_pairs' => $totalPairs,
                    'total_return_pairs' => $totalReturnPairs,
                    'paid_percentage' => $paidPercentage,
                    'total_closing_payment' => $totalClosingPayment,
                    'description_balance' => $descriptionBalance,
                    'description' => $description,
                    'current_balance' => $accountBook->getCalculatedBalance()
                ]
            ]);
        }

        if ($accountBook->account_type !== 'factory') {
            abort(404, 'Only factory account books are supported in this service.');
        }

        $factory = Factory::findOrFail($accountBook->account_id);
        $entryQuery = FactoryAccountEntry::where('account_book_id', $accountBook->id);
        $trashQuery = FactoryAccountEntry::onlyTrashed()->where('account_book_id', $accountBook->id);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = Carbon::parse($request->input('start_date'))->startOfDay();
            $end = Carbon::parse($request->input('end_date'))->endOfDay();
            $entryQuery->whereBetween('created_at', [$start, $end]);
            $trashQuery->whereBetween('created_at', [$start, $end]);
        }

        if ($request->filled('description')) {
            $desc = $request->input('description');
            $entryQuery->where('description', 'like', '%' . $desc . '%');
            $trashQuery->where('description', 'like', '%' . $desc . '%');
        }

        if ($request->filled('amount')) {
            $amt = $request->input('amount');
            $entryQuery->where('total_amount', $amt);
            $trashQuery->where('total_amount', $amt);
        }

        // Get min/max dates for filter
        $minDate = FactoryAccountEntry::where('account_book_id', $accountBook->id)->min('created_at');
        $maxDate = FactoryAccountEntry::where('account_book_id', $accountBook->id)->max('created_at');
        $defaultDateRange = '';
        if ($minDate && $maxDate) {
            $defaultDateRange = Carbon::parse($minDate)->format('Y-m-d') . ' - ' . Carbon::parse($maxDate)->format('Y-m-d');
        }

        // Calculate running balance
        $openingBalance = (float)$accountBook->getOpeningBalance();
        $allEntries = FactoryAccountEntry::where('account_book_id', $accountBook->id)
            ->where('status', 1)
            ->orderBy('created_at', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $running = $openingBalance;
        $balancesMap = [];
        foreach ($allEntries as $ent) {
            $amount = (float)$ent->total_amount;
            if ($ent->entry_type->value == 0) {
                $running += $amount;
            } else {
                $running -= $amount;
            }
            $balancesMap[$ent->id] = $running;
        }

        $entries = $entryQuery->orderBy('created_at', 'desc')->orderBy('id', 'desc')->paginate(15)->withQueryString();
        
        $entries->getCollection()->transform(function ($item) use ($balancesMap) {
            $item->running_balance = $balancesMap[$item->id] ?? 0;
            $item->formatted_created_at = Carbon::parse($item->created_at)->format('d/m/Y');
            return $item;
        });

        $trashedEntries = $trashQuery->orderBy('deleted_at', 'desc')->paginate(10)->withQueryString();
        $trashedEntries->getCollection()->transform(function ($item) {
            $item->formatted_created_at = Carbon::parse($item->created_at)->format('d/m/Y');
            return $item;
        });

        // Summary counts and amounts
        $totalPurchases = FactoryAccountEntry::where('account_book_id', $accountBook->id)
            ->where('entry_type', 0)
            ->where('status', 1)
            ->sum('total_amount');
        $totalReturns = FactoryAccountEntry::where('account_book_id', $accountBook->id)
            ->where('entry_type', 1)
            ->where('status', 1)
            ->sum('total_amount');
        $totalPayments = FactoryAccountEntry::where('account_book_id', $accountBook->id)
            ->where('entry_type', 2)
            ->where('status', 1)
            ->sum('total_amount');

        $totalPairs = FactoryAccountEntry::where('account_book_id', $accountBook->id)
            ->where('entry_type', 0)
            ->where('status', 1)
            ->sum('count');

        $totalReturnPairs = FactoryAccountEntry::where('account_book_id', $accountBook->id)
            ->where('entry_type', 1)
            ->where('status', 1)
            ->sum('count');

        $paidPercentage = $totalPurchases > 0
            ? (($totalPayments + $totalReturns) / $totalPurchases) * 100
            : 0;

        $totalClosingPayment = $accountBook->getTotalClosingPayment();
        $descriptionBalance = $accountBook->getDescriptionBalance();
        $description = $accountBook->getDescription();

        return Inertia::render('Factory/AccountBook', [
            'factory' => $factory,
            'accountBook' => $accountBook,
            'entries' => $entries,
            'trashedEntries' => $trashedEntries,
            'defaultDateRange' => $defaultDateRange,
            'filters' => $request->only(['start_date', 'end_date', 'description', 'amount']),
            'summary' => [
                'opening_balance' => $openingBalance,
                'total_purchases' => $totalPurchases,
                'total_returns' => $totalReturns,
                'total_payments' => $totalPayments,
                'total_pairs' => $totalPairs,
                'total_return_pairs' => $totalReturnPairs,
                'paid_percentage' => $paidPercentage,
                'total_closing_payment' => $totalClosingPayment,
                'description_balance' => $descriptionBalance,
                'description' => $description,
                'current_balance' => $accountBook->getCalculatedBalance()
            ]
        ]);
    }

    public function closingPage(AccountBook $accountBook, Request $request)
    {
        if ($accountBook->account_type === 'gift-supplier') {
            $giftSupplier = GiftSupplier::findOrFail($accountBook->account_id);
            $purchaseAmount = (float)$accountBook->getTotalProductsWorth();
            $returnAmount = 0.0;
            $paymentAmount = (float)$accountBook->getTotalPayment();
            $openingBalance = (float)$accountBook->getOpeningBalance();

            $bankAccounts = [];
            if (\Schema::hasTable('bank_accounts')) {
                $bankAccounts = \DB::table('bank_accounts')->whereNull('deleted_at')->get()->toArray();
            } else {
                $bankAccounts = [
                    (object)['id' => 1, 'account_no' => 'cash', 'bank' => 'ক্যাশ', 'branch' => '-'],
                ];
            }

            return Inertia::render('GiftSupplier/Closing', [
                'giftSupplier' => $giftSupplier,
                'accountBook' => $accountBook,
                'purchaseAmount' => $purchaseAmount,
                'returnAmount' => $returnAmount,
                'paymentAmount' => $paymentAmount,
                'openingBalance' => $openingBalance,
                'bankAccounts' => $bankAccounts,
            ]);
        }

        if ($accountBook->account_type === 'bank-account') {
            $bankAccount = BankAccount::findOrFail($accountBook->account_id);
            $total_deposit = BankAccountEntry::where('account_book_id', $accountBook->id)
                ->whereIn('type', ['deposit', 'income'])
                ->sum('total_amount');
            $total_expense = BankAccountEntry::where('account_book_id', $accountBook->id)
                ->whereIn('type', ['withdraw', 'expense'])
                ->sum('total_amount');

            $openingBalanceValue = $accountBook->getOpeningBalance();
            $latestEntry = BankAccountEntry::withRunningBalance($accountBook->id, $openingBalanceValue)
                ->orderBy('id', 'asc')
                ->get()
                ->last();
            $currentBalance = $latestEntry ? $latestEntry->current_balance : $accountBook->getOpeningBalance();

            return Inertia::render('BankAccount/Closing', [
                'bankAccount' => $bankAccount,
                'accountBook' => $accountBook,
                'total_deposit' => $total_deposit,
                'total_expense' => $total_expense,
                'currentBalance' => $currentBalance,
            ]);
        }

        if ($accountBook->account_type !== 'factory') {
            abort(404, 'Only factory, gift-supplier, and bank-account account books are supported.');
        }

        $factory = Factory::findOrFail($accountBook->account_id);
        
        $purchaseAmount = (float)$accountBook->getTotalProductsWorth();
        $returnAmount = (float)$accountBook->getTotalReturnAmount();
        $paymentAmount = (float)$accountBook->getTotalPayment();
        $openingBalance = (float)$accountBook->getOpeningBalance();
        
        $bankAccounts = [];
        if (\Schema::hasTable('bank_accounts')) {
            $bankAccounts = \DB::table('bank_accounts')->whereNull('deleted_at')->get()->toArray();
        } else {
            try {
                $bankAccounts = \DB::select("select * from avijatri_pos.bank_accounts where deleted_at is null");
            } catch (\Exception $e) {
                $bankAccounts = [
                    (object)['id' => 1, 'account_no' => 'cash', 'bank' => 'ক্যাশ', 'branch' => '-'],
                    (object)['id' => 2, 'account_no' => '0939902002916', 'bank' => 'Pubali Bank', 'branch' => 'Dhaka Stadium'],
                    (object)['id' => 3, 'account_no' => '0100006439181', 'bank' => 'Janata Bank', 'branch' => 'Ramna'],
                    (object)['id' => 4, 'account_no' => '20501570100160310', 'bank' => 'Islami Bank', 'branch' => 'Ramna'],
                    (object)['id' => 5, 'account_no' => '1041510046780', 'bank' => 'Dutch Bangla Bank', 'branch' => 'Nawabpur Road'],
                    (object)['id' => 6, 'account_no' => '1264324585001', 'bank' => 'City Bank', 'branch' => 'Principal Office'],
                    (object)['id' => 7, 'account_no' => '1623200017925', 'bank' => 'Sonali Bank', 'branch' => 'Nagar Bhaban'],
                    (object)['id' => 8, 'account_no' => '0059020008927', 'bank' => 'Rupali Bank', 'branch' => 'Ramna'],
                    (object)['id' => 9, 'account_no' => '2064853050001', 'bank' => 'Brac Bank', 'branch' => 'Nawabpur Road'],
                    (object)['id' => 10, 'account_no' => '0200000214954', 'bank' => 'Agrani Bank', 'branch' => 'B B Avenue'],
                    (object)['id' => 11, 'account_no' => '135412200213062', 'bank' => 'Uttara Bank', 'branch' => 'Poss'],
                    (object)['id' => 12, 'account_no' => '12345678', 'bank' => 'Meghna Bank', 'branch' => 'Uttara'],
                    (object)['id' => 13, 'account_no' => '00', 'bank' => 'Pos', 'branch' => 'Dhaka'],
                ];
            }
        }

        return Inertia::render('Factory/Closing', [
            'factory' => $factory,
            'accountBook' => $accountBook,
            'purchaseAmount' => $purchaseAmount,
            'returnAmount' => $returnAmount,
            'paymentAmount' => $paymentAmount,
            'openingBalance' => $openingBalance,
            'bankAccounts' => $bankAccounts,
        ]);
    }

    public function closing(Request $request, AccountBook $accountBook)
    {
        if ($accountBook->account_type === 'bank-account') {
            $request->validate([
                'closing_date' => 'required|date',
                'closing_balance' => 'required|numeric',
                'balance_carry_forward' => 'required|integer',
            ]);

            $newBook = $this->repository->closing($request->all(), $accountBook);
            return redirect()->route('bank-account.show', $accountBook->account_id)->with('success-alert', __('pages.Closing completed.') ?? 'Closing completed successfully');
        }

        if ($accountBook->account_type !== 'factory' && $accountBook->account_type !== 'gift-supplier') {
            abort(404, 'Only factory, gift-supplier, and bank-account account books can be closed.');
        }

        $request->validate([
            'closing_date' => 'required|date',
            'closing_type' => 'required|string',
            'commission_percentage' => 'nullable|numeric',
            'commission' => 'nullable|numeric',
            'staff_percentage' => 'nullable|numeric',
            'staff' => 'nullable|numeric',
            'staff_shoe_amount' => 'nullable|numeric',
            'payment.*.amount' => 'nullable|numeric',
            'payment.*.method' => 'nullable|string',
            'cheque.*.id' => 'nullable|string',
            'cheque.*.due_date' => 'nullable|date',
            'cheque.*.amount' => 'nullable|numeric',
        ]);

        $newBook = $this->repository->closing($request->all(), $accountBook);

        if ($accountBook->account_type === 'gift-supplier') {
            return redirect()->route('gift-supplier.show', $accountBook->account_id)->with('success-alert', __('pages.Closing completed.') ?? 'Closing completed successfully');
        }

        return redirect()->route('factory.show', $accountBook->account_id)->with('success-alert', __('pages.Closing completed.') ?? 'Closing completed successfully');
    }
}
