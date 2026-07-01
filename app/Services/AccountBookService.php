<?php

namespace App\Services;

use App\Models\AccountBook;
use App\Models\Factory;
use App\Models\BankAccount;
use App\Models\Cheque;
use App\Models\Commitment;
use App\Models\Employee;
use App\Models\EmployeeAccountEntry;
use App\Models\EmployeeSalary;
use App\Models\GiftPurchase;
use App\Models\GiftSupplier;
use App\Models\GiftSupplierAccountEntry;
use App\Models\Invoice;
use App\Models\ManualClosing;
use App\Models\Purchase;
use App\Models\RetailStore;
use App\Models\RetailStoreExpense;
use App\Models\ReturnFromRetailEntry;
use App\Models\View\BankAccountEntry;
use App\Models\View\FactoryAccountEntry as ViewFactoryAccountEntry;
use App\Models\View\RetailStoreAccountEntry;
use App\Repositories\AccountBookRepository;
use App\Enums\ClosingType;
use App\Enums\FactoryEntryType;
use App\Enums\GiftSupplierEntryType;
use App\Enums\RetailStoreEntryType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        switch ($accountBook->account_type) {
            case 'factory':
                $minDate = ViewFactoryAccountEntry::where('account_book_id', $accountBook->id)->min('created_at');
                $maxDate = ViewFactoryAccountEntry::where('account_book_id', $accountBook->id)->max('created_at');
                
                $dates = [
                    $minDate ? Carbon::parse($minDate)->format('m/d/Y') : Carbon::now()->format('m/d/Y'),
                    $maxDate ? Carbon::parse($maxDate)->format('m/d/Y') : Carbon::now()->format('m/d/Y')
                ];
                
                $defaultDateRange = '';
                if ($minDate && $maxDate) {
                    $defaultDateRange = Carbon::parse($minDate)->format('Y-m-d') . ' - ' . Carbon::parse($maxDate)->format('Y-m-d');
                }

                $trashedEntries = ViewFactoryAccountEntry::onlyTrashed()->where('account_book_id', $accountBook->id)
                    ->orderBy('created_at', 'desc')
                    ->where('status', 1)
                    ->paginate(10);
                $trashPurchases = Purchase::where('account_book_id', $accountBook->id)->with('deletedBy')->onlyTrashed()->paginate(10);

                // Fetch entries with filters and compute running balances for Inertia view
                $factory = Factory::findOrFail($accountBook->account_id);
                $entryQuery = ViewFactoryAccountEntry::where('account_book_id', $accountBook->id);
                
                if ($request->filled('start_date') && $request->filled('end_date')) {
                    $start = Carbon::parse($request->input('start_date'))->startOfDay();
                    $end = Carbon::parse($request->input('end_date'))->endOfDay();
                    $entryQuery->whereBetween('created_at', [$start, $end]);
                }
                if ($request->filled('description')) {
                    $desc = $request->input('description');
                    $entryQuery->where('description', 'like', '%' . $desc . '%');
                }
                if ($request->filled('amount')) {
                    $amt = $request->input('amount');
                    $entryQuery->where('total_amount', $amt);
                }

                $openingBalance = (float)$accountBook->getOpeningBalance();
                $allEntries = ViewFactoryAccountEntry::where('account_book_id', $accountBook->id)
                    ->where('status', 1)
                    ->orderBy('created_at', 'asc')
                    ->orderBy('id', 'asc')
                    ->get();

                $running = $openingBalance;
                $balancesMap = [];
                foreach ($allEntries as $ent) {
                    $amount = (float)$ent->total_amount;
                    $entType = is_object($ent->entry_type) ? $ent->entry_type->value : $ent->entry_type;
                    if ($entType == 0) {
                        $running += $amount;
                    } else {
                        $running -= $amount;
                    }
                    $balancesMap[$ent->id] = $running;
                }

                $entries = $entryQuery->orderBy('created_at', 'desc')->orderBy('id', 'desc')->paginate(15)->withQueryString();
                $transformedItems = $entries->getCollection()->map(function ($item) use ($balancesMap) {
                    $arr = $item->toArray();
                    $arr['running_balance'] = $balancesMap[$item->id] ?? 0;
                    $arr['formatted_created_at'] = Carbon::parse($item->created_at)->format('d/m/Y');
                    
                    // Format entry_type as an object for Factory/AccountBook.vue (expects entry.entry_type.value)
                    $val = is_object($item->entry_type) ? $item->entry_type->value : $item->entry_type;
                    $name = is_object($item->entry_type) ? $item->entry_type->name : '';
                    $arr['entry_type'] = [
                        'value' => (int)$val,
                        'name' => $name
                    ];
                    return $arr;
                });
                $entries->setCollection($transformedItems);

                $totalPurchases = ViewFactoryAccountEntry::where('account_book_id', $accountBook->id)
                    ->where('entry_type', 0)
                    ->where('status', 1)
                    ->sum('total_amount');
                $totalReturns = ViewFactoryAccountEntry::where('account_book_id', $accountBook->id)
                    ->where('entry_type', 1)
                    ->where('status', 1)
                    ->sum('total_amount');
                $totalPayments = ViewFactoryAccountEntry::where('account_book_id', $accountBook->id)
                    ->where('entry_type', 2)
                    ->where('status', 1)
                    ->sum('total_amount');

                $totalPairs = ViewFactoryAccountEntry::where('account_book_id', $accountBook->id)
                    ->where('entry_type', 0)
                    ->where('status', 1)
                    ->sum('count');

                $totalReturnPairs = ViewFactoryAccountEntry::where('account_book_id', $accountBook->id)
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
                    'trashPurchases' => $trashPurchases,
                    'filters' => $request->only(['start_date', 'end_date', 'description', 'amount']),
                    'defaultDateRange' => $defaultDateRange,
                    'dates' => $dates,
                    'purchase_amount' => 0,
                    'payment_amount' => 0,
                    'return_amount' => 0,
                    'total_balance' => [],
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

            case 'retail-store':
                $commitments = Commitment::with('CommitmentLog')->where('account_book_id', $accountBook->id)->get();
                $openingBalance = $accountBook->getOpeningBalance() ?? 0;

                $minDate = RetailStoreAccountEntry::where('account_book_id', $accountBook->id)->min('created_at');
                $maxDate = RetailStoreAccountEntry::where('account_book_id', $accountBook->id)->max('created_at');
                
                $dates = [
                    $minDate ? Carbon::parse($minDate)->format('m/d/Y') : Carbon::now()->format('m/d/Y'),
                    $maxDate ? Carbon::parse($maxDate)->format('m/d/Y') : Carbon::now()->format('m/d/Y')
                ];
                
                $defaultDateRange = '';
                if ($minDate && $maxDate) {
                    $defaultDateRange = Carbon::parse($minDate)->format('Y-m-d') . ' - ' . Carbon::parse($maxDate)->format('Y-m-d');
                }

                $trashInvoices = Invoice::with('deletedBy')->where('account_book_id', $accountBook->id)->onlyTrashed()->get();

                $trashedEntries = RetailStoreAccountEntry::onlyTrashed()->where('account_book_id', $accountBook->id)
                    ->when($request->amount, function ($query) use ($request) {
                        $query->where('amount', $request->amount);
                    })
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

                $retailStoreExpence = RetailStoreExpense::onlyTrashed()->where('account_book_id', $accountBook->id)
                    ->where('invoice_id', 0)
                    ->get();

                $totalReturnAmount = ReturnFromRetailEntry::getTotalReturnGrossAmount($accountBook->id);
                $totalReturnPairs = ReturnFromRetailEntry::totalReturnPair($accountBook->id);

                // Summary calculations from database query
                $summaryQuery = RetailStoreAccountEntry::where('account_book_id', $accountBook->id);
                $summaryQuery = $this->applyRetailStoreFilters($summaryQuery, $request);
                $totals = $summaryQuery->selectRaw('
                    SUM(amount) as total_bill,
                    SUM(count) as total_pairs,
                    SUM(CASE WHEN is_discount_product_sale = 1 THEN amount ELSE 0 END) as discount_sale_amount
                ')->first();

                $totalBill = $totals->total_bill ?? 0;
                $totalPairs = $totals->total_pairs ?? 0;
                $discountSaleAmount = $totals->discount_sale_amount ?? 0;
                $paidPercentage = $totalBill > 0 ? ($accountBook->getTotalPayment() / $totalBill) * 100 : 0;

                // Fetch entries with filters and compute running balances for Inertia view
                $retailStore = RetailStore::findOrFail($accountBook->account_id);
                $entryQuery = RetailStoreAccountEntry::where('account_book_id', $accountBook->id);
                
                if ($request->filled('start_date') && $request->filled('end_date')) {
                    $start = Carbon::parse($request->input('start_date'))->startOfDay();
                    $end = Carbon::parse($request->input('end_date'))->endOfDay();
                    $entryQuery->whereBetween('created_at', [$start, $end]);
                }
                if ($request->filled('description')) {
                    $desc = $request->input('description');
                    $entryQuery->where('expense_description', 'like', '%' . $desc . '%');
                }
                if ($request->filled('amount')) {
                    $amt = $request->input('amount');
                    $entryQuery->where('amount', $amt);
                }

                $allEntries = RetailStoreAccountEntry::where('account_book_id', $accountBook->id)
                    ->orderBy('created_at', 'asc')
                    ->orderBy('id', 'asc')
                    ->get();

                $running = $openingBalance;
                $balancesMap = [];
                foreach ($allEntries as $ent) {
                    $diff = (float)$ent->amount - (float)$ent->paid_amount;
                    $running += $diff;
                    $balancesMap[$ent->id] = $running;
                }

                $entries = $entryQuery->orderBy('created_at', 'desc')->orderBy('id', 'desc')->paginate(15)->withQueryString();
                $transformedItems = $entries->getCollection()->map(function ($item) use ($balancesMap) {
                    $arr = $item->toArray();
                    $arr['running_balance'] = $balancesMap[$item->id] ?? 0;
                    $arr['formatted_created_at'] = Carbon::parse($item->created_at)->format('d/m/Y');
                    
                    // Format entry_type as an object for RetailStore/AccountBook.vue (expects entry.entry_type.value)
                    $val = is_object($item->entry_type) ? $item->entry_type->value : $item->entry_type;
                    $name = is_object($item->entry_type) ? $item->entry_type->name : '';
                    $arr['entry_type'] = [
                        'value' => (int)$val,
                        'name' => $name
                    ];
                    return $arr;
                });
                $entries->setCollection($transformedItems);

                $totalSales = RetailStoreAccountEntry::where('account_book_id', $accountBook->id)
                    ->where('entry_type', RetailStoreEntryType::Invoice->value)
                    ->sum('amount');

                $totalReturns = RetailStoreAccountEntry::where('account_book_id', $accountBook->id)
                    ->where('entry_type', RetailStoreEntryType::Return->value)
                    ->sum('paid_amount');

                $totalPayments = RetailStoreAccountEntry::where('account_book_id', $accountBook->id)
                    ->where('entry_type', RetailStoreEntryType::Payment->value)
                    ->sum('paid_amount');

                $totalExpenses = RetailStoreAccountEntry::where('account_book_id', $accountBook->id)
                    ->where('entry_type', RetailStoreEntryType::Expence->value)
                    ->sum('amount');

                $totalClosingPayment = $accountBook->getTotalClosingPayment();
                $descriptionBalance = $accountBook->getDescriptionBalance();
                $description = $accountBook->getDescription();

                return Inertia::render('RetailStore/AccountBook', [
                    'retailStore' => $retailStore,
                    'accountBook' => $accountBook,
                    'entries' => $entries,
                    'trashedEntries' => $trashedEntries,
                    'filters' => $request->only(['start_date', 'end_date', 'description', 'amount']),
                    'defaultDateRange' => $defaultDateRange,
                    'retailStoreExpence' => $retailStoreExpence,
                    'trashInvoices' => $trashInvoices,
                    'totalReturnPairs' => $totalReturnPairs,
                    'totalReturnAmount' => $totalReturnAmount,
                    'commitments' => $commitments,
                    'dates' => $dates,
                    'ManualClosing_map' => null,
                    'ManualClosign_map' => null,
                    'totalBill' => $totalBill,
                    'totalPairs' => $totalPairs,
                    'discountSaleAmount' => $discountSaleAmount,
                    'paidPercentage' => $paidPercentage,
                    'summary' => [
                        'opening_balance' => $openingBalance,
                        'total_sales' => $totalSales,
                        'total_returns' => $totalReturns,
                        'total_payments' => $totalPayments,
                        'total_expenses' => $totalExpenses,
                        'total_pairs' => $totalPairs,
                        'total_return_pairs' => $totalReturnPairs,
                        'total_closing_payment' => $totalClosingPayment,
                        'description_balance' => $descriptionBalance,
                        'description' => $description,
                        'current_balance' => $accountBook->getCalculatedBalance()
                    ]
                ]);

            case 'gift-supplier':
                $minDate = GiftSupplierAccountEntry::where('account_book_id', $accountBook->id)->min('created_at');
                $maxDate = GiftSupplierAccountEntry::where('account_book_id', $accountBook->id)->max('created_at');
                
                $dates = [
                    $minDate ? Carbon::parse($minDate)->format('m/d/Y') : Carbon::now()->format('m/d/Y'),
                    $maxDate ? Carbon::parse($maxDate)->format('m/d/Y') : Carbon::now()->format('m/d/Y')
                ];
                
                $defaultDateRange = '';
                if ($minDate && $maxDate) {
                    $defaultDateRange = Carbon::parse($minDate)->format('Y-m-d') . ' - ' . Carbon::parse($maxDate)->format('Y-m-d');
                }

                $trashGiftInvoices = GiftPurchase::with('deletedBy')->where('account_book_id', $accountBook->id)->onlyTrashed()->get();
                $trashedEntries = GiftSupplierAccountEntry::onlyTrashed()->where('account_book_id', $accountBook->id)->get();

                // Fetch entries with filters and compute running balances for Inertia view
                $giftSupplier = GiftSupplier::findOrFail($accountBook->account_id);
                $entryQuery = GiftSupplierAccountEntry::where('account_book_id', $accountBook->id);
                $trashQuery = GiftSupplierAccountEntry::onlyTrashed()->where('account_book_id', $accountBook->id);

                if ($request->filled('daterange')) {
                    $dates_range = explode(' - ', $request->daterange);
                    if (count($dates_range) === 2) {
                        $start = Carbon::parse($dates_range[0])->startOfDay();
                        $end = Carbon::parse($dates_range[1])->endOfDay();
                        $entryQuery->whereBetween('created_at', [$start, $end]);
                        $trashQuery->whereBetween('created_at', [$start, $end]);
                    }
                }

                if ($request->filled('type')) {
                    $entryQuery->where('entry_type', $request->type);
                    $trashQuery->where('entry_type', $request->type);
                }

                if ($request->filled('gift_purchase_id')) {
                    $entryQuery->where('gift_purchase_id', $request->gift_purchase_id);
                    $trashQuery->where('gift_purchase_id', $request->gift_purchase_id);
                }

                if ($request->filled('gift_name')) {
                    $giftName = $request->gift_name;
                    $entryQuery->where(function ($q2) use ($giftName) {
                        $q2->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(gift_name, '$[0]')) LIKE ?", ["%{$giftName}%"])
                            ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(gift_name, '$[1]')) LIKE ?", ["%{$giftName}%"]);
                    });
                    $trashQuery->where(function ($q2) use ($giftName) {
                        $q2->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(gift_name, '$[0]')) LIKE ?", ["%{$giftName}%"])
                            ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(gift_name, '$[1]')) LIKE ?", ["%{$giftName}%"]);
                    });
                }

                if ($request->filled('count')) {
                    $entryQuery->where('count', $request->count);
                    $trashQuery->where('count', $request->count);
                }

                if ($request->filled('total_amount')) {
                    $amt = $request->total_amount;
                    $entryQuery->where(function($q) use ($amt) {
                        $q->where('total_amount', $amt)->orWhere('payment_amount', $amt);
                    });
                    $trashQuery->where(function($q) use ($amt) {
                        $q->where('total_amount', $amt)->orWhere('payment_amount', $amt);
                    });
                }

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

                $entries = $entryQuery->orderBy('created_at', 'desc')->orderBy('id', 'desc')->paginate(200)->withQueryString();
                $entries->getCollection()->transform(function ($item) use ($balancesMap) {
                    $item->running_balance = $balancesMap[$item->id] ?? 0;
                    $item->formatted_created_at = Carbon::parse($item->created_at)->format('d/m/Y');
                    
                    // Keep entry_type as integer for GiftSupplier/AccountBook.vue (expects entry.entry_type === integer)
                    $item->entry_type = is_object($item->entry_type) ? $item->entry_type->value : (int)$item->entry_type;
                    return $item;
                });

                $trashedEntriesPage = $trashQuery->orderBy('deleted_at', 'desc')->paginate(10)->withQueryString();
                $trashedEntriesPage->getCollection()->transform(function ($item) {
                    $item->formatted_created_at = Carbon::parse($item->created_at)->format('d/m/Y');
                    
                    // Format entry_type as integer for GiftSupplier trashed entries as well
                    $item->entry_type = is_object($item->entry_type) ? $item->entry_type->value : (int)$item->entry_type;
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
                    'trashedEntries' => $trashedEntriesPage,
                    'trashGiftInvoices' => $trashGiftInvoices,
                    'dates' => $dates,
                    'defaultDateRange' => $defaultDateRange,
                    'ManualClosing_map' => null,
                    'filters' => $request->only(['daterange', 'type', 'gift_purchase_id', 'gift_name', 'count', 'total_amount']),
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

            case 'employee':
                $employee = Employee::find($accountBook->account_id);
                $trashedEntries = EmployeeAccountEntry::where('account_book_id', $accountBook->id)->onlyTrashed()->get();
                $total = (float) EmployeeAccountEntry::where('account_book_id', $accountBook->id)->sum('total_amount');
                
                $minDate = EmployeeAccountEntry::where('account_book_id', $accountBook->id)->min('created_at');
                $maxDate = EmployeeAccountEntry::where('account_book_id', $accountBook->id)->max('created_at');
                
                $dates = [
                    $minDate ? Carbon::parse($minDate)->format('m/d/Y') : Carbon::now()->format('m/d/Y'),
                    $maxDate ? Carbon::parse($maxDate)->format('m/d/Y') : Carbon::now()->format('m/d/Y')
                ];

                $salaryInfo = EmployeeSalary::where('employee_id', $employee->id)->where('account_book_id', $accountBook->id)->first();
                $entries = collect();

                return Inertia::render('Employee/AccountBook', [
                    'salaryInfo' => $salaryInfo,
                    'employee' => $employee,
                    'accountBook' => $accountBook,
                    'entries' => $entries,
                    'trashedEntries' => $trashedEntries,
                    'total' => $total,
                    'dates' => $dates,
                ]);

            case 'bank-account':
                $openingBalanceValue = $accountBook->getOpeningBalance();
                $latestEntry = BankAccountEntry::withRunningBalance($accountBook->id, $openingBalanceValue)
                    ->orderBy('id', 'asc')
                    ->get()
                    ->last();
                $currentBalance = $latestEntry ? $latestEntry->current_balance : $accountBook->getOpeningBalance();

                $bankAccount = BankAccount::findOrFail($accountBook->account_id);
                $openingBalance = (float)$accountBook->getOpeningBalance();

                $query = BankAccountEntry::where('account_book_id', $accountBook->id);
                if ($request->filled('start_date') && $request->filled('end_date')) {
                    $start = Carbon::parse($request->input('start_date'))->startOfDay();
                    $end = Carbon::parse($request->input('end_date'))->endOfDay();
                    $query->whereBetween('created_at', [$start, $end]);
                }
                if ($request->filled('description')) {
                    $desc = $request->input('description');
                    $query->where('description', 'like', '%' . $desc . '%');
                }
                if ($request->filled('deposit')) {
                    $query->where('total_amount', $request->deposit)->whereIn('type', ['deposit', 'income']);
                }
                if ($request->filled('withdraw')) {
                    $query->where('total_amount', $request->withdraw)->whereIn('type', ['withdraw', 'expense']);
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

                return Inertia::render('BankAccount/AccountBook', [
                    'bankAccount' => $bankAccount,
                    'accountBook' => $accountBook,
                    'entries' => $entries,
                    'defaultDateRange' => $defaultDateRange,
                    'filters' => $request->only(['start_date', 'end_date', 'description', 'deposit', 'withdraw']),
                    'currentBalance' => $currentBalance,
                    'summary' => [
                        'opening_balance' => $openingBalance,
                        'total_deposit' => $totalDeposit,
                        'total_expense' => $totalExpense,
                        'current_balance' => $currentBalance,
                        'description' => $accountBook->getDescription(),
                    ]
                ]);
        }
    }

    public function closingPage(AccountBook $accountBook, Request $request)
    {
        $accountBook->load('closingTransactions');
        $bankAccounts = BankAccount::all();

        switch ($accountBook->account_type) {
            case 'factory':
                $accountBook->load('closingTransactions', 'ManualClosingdata');
                $manulClosing = json_decode($accountBook->ManualClosingdata->closing_data ?? '{}', true);
                $entries = ViewFactoryAccountEntry::where('account_book_id', $accountBook->id)->get();
                $closingpaidAmount = $accountBook->getTotalClosingTransactionAmount();
                $payment_amount = $accountBook->payment - $closingpaidAmount;
                $purchase_amount = $entries->where('entry_type', '0')->sum('total_amount');
                $return_amount = $entries->where('entry_type', '1')->sum('total_amount');
                $total_balance = $accountBook->getTotalProductsWorth() - ($payment_amount + $return_amount + $accountBook->staff_shoe_amount);

                $factory = Factory::findOrFail($accountBook->account_id);
                $purchaseAmount = (float)$accountBook->getTotalProductsWorth();
                $returnAmount = (float)$accountBook->getTotalReturnAmount();
                $paymentAmount = (float)$accountBook->getTotalPayment();
                $openingBalance = (float)$accountBook->getOpeningBalance();

                return Inertia::render('Factory/Closing', [
                    'factory' => $factory,
                    'accountBook' => $accountBook,
                    'purchaseAmount' => $purchaseAmount,
                    'returnAmount' => $returnAmount,
                    'paymentAmount' => $paymentAmount,
                    'openingBalance' => $openingBalance,
                    'bankAccounts' => $bankAccounts,
                    'isDummy' => $request->dummy == 1,
                    'total_balance' => $total_balance,
                    'manulClosing' => $manulClosing,
                ]);

            case 'retail-store':
                $accountBook->load('closingTransactions', 'closingRetialStoreCommitments', 'ManualClosingdata');
                $manulClosing = json_decode($accountBook->ManualClosingdata->closing_data ?? '{}', true);

                $retailStore = RetailStore::findOrFail($accountBook->account_id);
                $openingBalance = (float)$accountBook->getOpeningBalance();

                return Inertia::render('RetailStore/Closing', [
                    'retailStore' => $retailStore,
                    'accountBook' => $accountBook,
                    'bankAccounts' => $bankAccounts,
                    'isDummy' => $request->dummy == 1,
                    'openingBalance' => $openingBalance,
                    'manulClosing' => $manulClosing,
                    'calculations' => [
                        'total_sale' => (float)$accountBook->getTotalSale(),
                        'total_sale_commission' => (float)$accountBook->getTotalSaleMinusCommission(),
                        'total_discount_product_sale' => (float)$accountBook->getTotalDiscountProductSale(),
                        'total_discount_product_sale_commission' => (float)$accountBook->getTotalDiscountProductSaleMinusCommission(),
                        'total_return' => (float)$accountBook->getTotalReturnAmount(),
                        'total_return_commission' => (float)$accountBook->getTotalReturnMinusCommission(),
                        'total_payment' => (float)$accountBook->getTotalPayment(),
                        'total_transport' => (float)$accountBook->getTotalTransport(),
                        'total_expense' => (float)$accountBook->getTotalExpense(),
                        'total_discount' => (float)$accountBook->getTotalDiscount(),
                        'balance_before_closing' => (float)$accountBook->getBalanceBeforeClosing(),
                    ]
                ]);

            case 'gift-supplier':
                $entries = GiftSupplierAccountEntry::where('account_book_id', $accountBook->id)->get();
                $payment_amount = $entries->where('entry_type', '2')->sum('total_amount');
                $purchase_amount = $entries->where('entry_type', '0')->sum('total_amount');
                $total_balance = $purchase_amount - $payment_amount;

                $total_cheque_payment = Cheque::where('account_book_id', $accountBook->id)
                    ->where('closing_id', $accountBook->id)
                    ->sum('amount');

                $giftSupplier = GiftSupplier::findOrFail($accountBook->account_id);
                $purchaseAmount = (float)$accountBook->getTotalProductsWorth();
                $returnAmount = 0.0;
                $paymentAmount = (float)$accountBook->getTotalPayment();
                $openingBalance = (float)$accountBook->getOpeningBalance();

                return Inertia::render('GiftSupplier/Closing', [
                    'giftSupplier' => $giftSupplier,
                    'accountBook' => $accountBook,
                    'purchaseAmount' => $purchaseAmount,
                    'returnAmount' => $returnAmount,
                    'paymentAmount' => $paymentAmount,
                    'openingBalance' => $openingBalance,
                    'bankAccounts' => $bankAccounts,
                    'isDummy' => $request->dummy == 1,
                    'total_balance' => $total_balance,
                    'total_cheque_payment' => $total_cheque_payment,
                ]);

            case 'employee':
                $entries = EmployeeAccountEntry::where('account_book_id', $accountBook->id)->get();
                $paid_salary_amount = $entries->where('payment_type', 'employee-salary')->sum('total_amount');
                $eid_ul_fitor = $entries->where('payment_type', 'employee-bonus-eid-ul-fitor')->sum('total_amount');
                $eid_ul_adha = $entries->where('payment_type', 'employee-bonus-eid-ul-adha')->sum('total_amount');
                $employee = Employee::find($accountBook->account_id);
                $salaryInfo = EmployeeSalary::where('employee_id', $employee->id)->where('account_book_id', $accountBook->id)->first();

                $total_paid = $paid_salary_amount + $eid_ul_fitor + $eid_ul_adha;

                return Inertia::render('Employee/Closing', [
                    'total_paid' => $total_paid,
                    'salaryInfo' => $salaryInfo,
                    'eid_ul_fitor' => $eid_ul_fitor,
                    'eid_ul_adha' => $eid_ul_adha,
                    'accountBook' => $accountBook,
                    'bankAccounts' => $bankAccounts,
                    'paid_salary_amount' => $paid_salary_amount,
                    'employee' => $employee,
                    'isDummy' => $request->dummy == 1,
                ]);

            case 'bank-account':
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

                $bankAccount = BankAccount::findOrFail($accountBook->account_id);

                return Inertia::render('BankAccount/Closing', [
                    'bankAccount' => $bankAccount,
                    'accountBook' => $accountBook,
                    'bankAccounts' => $bankAccounts,
                    'total_deposit' => $total_deposit,
                    'total_expense' => $total_expense,
                    'currentBalance' => $currentBalance,
                ]);
        }
    }

    public function closing(Request $request, AccountBook $accountBook)
    {
        $newBook = $this->repository->closing($request->all(), $accountBook);
        return redirect()->route('account-book.show', $newBook->id)->with('success-alert', __('pages.Closing completed.') ?? 'ক্লোজিং সম্পন্ন হয়েছে।');
    }

    public function forwardBalance(AccountBook $accountBook)
    {
        $result = $this->repository->forwardBalance($accountBook);
        if ($result === null) {
            return back()->with('error-alert', __('pages.This action cannot be performed.') ?? 'এই কাজটি করা সম্ভব নয়।');
        }
        return back()->with('success-alert', __('pages.Change completed.') ?? 'পরিবর্তন সম্পন্ন হয়েছে।');
    }

    private function applyRetailStoreFilters($query, Request $request)
    {
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = Carbon::parse($request->input('start_date'))->startOfDay();
            $end = Carbon::parse($request->input('end_date'))->endOfDay();
            $query->whereBetween('retail_store_account_entries.created_at', [$start, $end]);
        }

        return $query->when($request->daterange, function ($q) use ($request) {
            $dates = explode(' - ', $request->daterange);
            if (count($dates) === 2) {
                $q->whereBetween('retail_store_account_entries.created_at', [
                    Carbon::parse($dates[0])->startOfDay(),
                    Carbon::parse($dates[1])->endOfDay(),
                ]);
            }
        })
            ->when($request->invoice_id, function ($q) use ($request) {
                $q->where('retail_store_account_entries.invoice_id', $request->invoice_id);
            })
            ->when($request->count, function ($q) use ($request) {
                $q->where('retail_store_account_entries.count', $request->count);
            })
            ->when($request->return_count, function ($q) use ($request) {
                $q->where('retail_store_account_entries.return_count', $request->return_count);
            })
            ->when($request->expense_description, function ($q) use ($request) {
                $q->where('retail_store_account_entries.expense_description', 'like', '%' . $request->expense_description . '%');
            })
            ->when($request->expense_amount, function ($q) use ($request) {
                $q->where('retail_store_account_entries.expense_amount', $request->expense_amount);
            })
            ->when($request->retail_price, function ($q) use ($request) {
                $q->where('retail_store_account_entries.total_retail_price', $request->retail_price);
            })
            ->when($request->paid_amount, function ($q) use ($request) {
                $q->where('retail_store_account_entries.paid_amount', $request->paid_amount);
            })
            ->when($request->amount, function ($q) use ($request) {
                $q->where('retail_store_account_entries.amount', $request->amount);
            });
    }
}
