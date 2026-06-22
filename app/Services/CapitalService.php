<?php

namespace App\Services;

use App\Models\ClosingPeriod;
use App\Models\AccountBook;
use App\Models\Asset;
use App\Models\LiabilitiesEntry;
use App\Models\Zakat;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CapitalService
{
    public function calculate($selectedYear)
    {
        $period = ClosingPeriod::find($selectedYear);
        if (!$period) {
            return null;
        }

        // current year data
        $data = $this->getDetailedData($period);

        // previous year data for net capital comparison
        $previousPeriod = ClosingPeriod::where('id', '<', $selectedYear)->orderByDesc('id')->first();
        $data['previousNetCapital'] = 0;

        if ($previousPeriod) {
            $prevYearData = $this->getDetailedData($previousPeriod);
            $data['previousNetCapital'] = $prevYearData['netCapital'];
        }

        return $data;
    }

    private function getDetailedData($period)
    {
        $start = Carbon::parse($period->start_date);
        $end = Carbon::parse($period->end_date);

        /* ----------------------- ASSETS SECTION ----------------------- */
        $data['totalRetailDues'] = 0;
        if (Schema::hasTable('retail_store_account_entries')) {
            try {
                $data['totalRetailDues'] = \App\Models\View\RetailStoreAccountEntry::with('AccountBook')
                    ->whereBetween('created_at', [$start, $end])
                    ->where('entry_type', 0)
                    ->whereNotNull('invoice_id')
                    ->get()
                    ->unique('account_book_id')
                    ->sum(fn($entry) => $entry->AccountBook ? $entry->AccountBook->getDescriptionBalance() : 0);
            } catch (\Exception $e) {
                $data['totalRetailDues'] = 0;
            }
        }

        $data['finalCashBalance'] = 0;
        if (Schema::hasTable('bank_accounts') && Schema::hasTable('account_books')) {
            try {
                $cashAccount = DB::table('bank_accounts')->where('name', 'ক্যাশ')->first();
                if ($cashAccount) {
                    $book = AccountBook::where('account_id', $cashAccount->id)->where('account_type', 'bank-account')->where('open', true)->first();
                    if ($book) {
                        $total_income = Schema::hasTable('transactions') ? DB::table('transactions')->where('to_account_id', $book->id)->where('created_at', '<', $end->copy()->addDay())->sum('amount') : 0;
                        $total_expense = Schema::hasTable('transactions') ? DB::table('transactions')->where('from_account_id', $book->id)->where('created_at', '<', $end->copy()->addDay())->sum('amount') : 0;
                        $data['finalCashBalance'] = (float)$book->previous_balance + $total_income - $total_expense;
                    }
                }
            } catch (\Exception $e) {
                $data['finalCashBalance'] = 0;
            }
        }

        $data['BankAmount'] = 0;
        $data['bankEntries'] = collect();
        if (Schema::hasTable('bank_account_entries') && Schema::hasTable('account_books')) {
            try {
                $bankEntriesGrouped = DB::table('bank_account_entries')
                    ->where('account_name', '!=', 'ক্যাশ')
                    ->whereBetween('created_at', [$start, $end])
                    ->get()
                    ->groupBy('account_book_id');

                $currentBankBalance = 0;
                foreach ($bankEntriesGrouped as $entries) {
                    $firstEntry = $entries->first();
                    $accountBook = AccountBook::find($firstEntry->account_book_id);
                    $opening = $accountBook ? ($accountBook->previous_balance ?? 0) : 0;
                    $currentBankBalance += $entries->last()->current_balance ?? $opening;
                }
                $data['BankAmount'] = round($currentBankBalance, 2);
            } catch (\Exception $e) {
                $data['BankAmount'] = 0;
            }
        }

        // Assets
        $data['assets'] = collect();
        $data['assetAmount'] = 0;
        if (Schema::hasTable('assets')) {
            try {
                $data['assets'] = Asset::with('accountBook.assetAccount')
                    ->where('status', 1)
                    ->get()
                    ->unique('account_book_id')
                    ->values();

                $totals = Asset::where('status', 1)
                    ->selectRaw("SUM(CASE WHEN entry_type = 1 THEN asset_cost ELSE 0 END) as total_cost")
                    ->selectRaw("SUM(CASE WHEN entry_type = 0 THEN asset_cost ELSE 0 END) as total_return")
                    ->first();

                $data['assetAmount'] = ($totals->total_cost ?? 0) - ($totals->total_return ?? 0);
            } catch (\Exception $e) {
                $data['assetAmount'] = 0;
            }
        }

        $data['totalPrice'] = 0;
        if (function_exists('calculatePriceByDate')) {
            try {
                $data['totalPrice'] = round(calculatePriceByDate($end), 2);
            } catch (\Exception $e) {
                $data['totalPrice'] = 0;
            }
        }

        $data['employeeExraPayemnt'] = 0;
        try {
            $data['employeeExraPayemnt'] = abs(
                AccountBook::where('account_type', 'employee')
                    ->where('open', 0)
                    ->whereBetween('closing_date', [$start, $end])
                    ->where('closing_balance', '<', 0)
                    ->sum('closing_balance')
            );
        } catch (\Exception $e) {
            $data['employeeExraPayemnt'] = 0;
        }

        $data['totalAssets'] = ($data['totalRetailDues'] ?? 0) + ($data['finalCashBalance'] ?? 0) + ($data['BankAmount'] ?? 0) + ($data['assetAmount'] ?? 0) + ($data['totalPrice'] ?? 0) + $data['employeeExraPayemnt'];


        /* ----------------------- LIABILITIES SECTION ----------------------- */
        $data['supplierDue'] = 0;
        if (function_exists('calculateSupplierCurrentDue')) {
            try {
                $data['supplierDue'] = calculateSupplierCurrentDue($start, $end);
            } catch (\Exception $e) {
                $data['supplierDue'] = 0;
            }
        }

        $data['giftSupplierDue'] = 0;
        if (function_exists('calculateGiftSupplierCurrentDue')) {
            try {
                $data['giftSupplierDue'] = calculateGiftSupplierCurrentDue($start, $end);
            } catch (\Exception $e) {
                $data['giftSupplierDue'] = 0;
            }
        }

        $data['chequeDue'] = 0;
        if (Schema::hasTable('cheques')) {
            try {
                $cheques = DB::table('cheques')->whereBetween('created_at', [$start, $end])->get();
                $total_amount = $cheques->sum('amount');
                $paid_amount = Schema::hasTable('cheque_entries') ? DB::table('cheque_entries')
                    ->whereIn('cheque_id', $cheques->pluck('id'))
                    ->sum('total_amount') : 0;
                $data['chequeDue'] = max(0, $total_amount - $paid_amount);
            } catch (\Exception $e) {
                $data['chequeDue'] = 0;
            }
        }

        // Liabilities Entry calculation
        $data['liabilities'] = collect();
        $data['liabilitieAmount'] = 0;
        if (Schema::hasTable('liabilities_entries')) {
            try {
                $liabilities = LiabilitiesEntry::with('accountBook.liabilityAccount')
                    ->whereBetween('created_at', [$start, $end])
                    ->get()
                    ->unique('account_book_id');

                $data['liabilities'] = $liabilities;
                $liabilities_balance = 0;
                foreach ($liabilities as $liability) {
                    $lEntries = LiabilitiesEntry::where('account_book_id', $liability->account_book_id)
                        ->whereBetween('created_at', [$start, $end])->get();
                    $liabilities_balance += ($lEntries->where('entry_type', 0)->sum('total_amount') - $lEntries->where('entry_type', 1)->sum('total_amount'));
                }
                $data['liabilitieAmount'] = $liabilities_balance;
            } catch (\Exception $e) {
                $data['liabilitieAmount'] = 0;
            }
        }

        // Commission and other items
        $data['commissionPaidAmountSum'] = 0;
        if (Schema::hasTable('expense_account_entries') && Schema::hasTable('expenses') && Schema::hasTable('account_books')) {
            try {
                $data['commissionPaidAmountSum'] = DB::table('expense_account_entries')
                    ->whereBetween('created_at', [$start, $end])
                    ->where('entry_type', 0)
                    ->whereIn('account_book_id', function ($q) {
                        $q->select('id')
                            ->from('account_books')
                            ->where('account_type', 'expense')
                            ->whereIn('account_id', function ($q2) {
                                $q2->select('id')
                                    ->from('expenses')
                                    ->where('type', 'commission_paid');
                            });
                    })
                    ->sum('total_amount');
            } catch (\Exception $e) {
                $data['commissionPaidAmountSum'] = 0;
            }
        }

        $data['commissionPaid'] = collect();
        if (Schema::hasTable('expense_account_entries') && Schema::hasTable('expenses') && Schema::hasTable('account_books')) {
            try {
                $data['commissionPaid'] = DB::table('expense_account_entries')
                    ->join('account_books', 'expense_account_entries.account_book_id', '=', 'account_books.id')
                    ->join('expenses', 'account_books.account_id', '=', 'expenses.id')
                    ->where('account_books.account_type', 'expense')
                    ->where('expenses.type', 'commission_paid')
                    ->whereBetween('expense_account_entries.created_at', [$start, $end])
                    ->where('expense_account_entries.entry_type', 0)
                    ->selectRaw('
                        account_books.id as account_book_id,
                        expenses.name as expense_name,
                        SUM(expense_account_entries.total_amount) as total
                        ')
                    ->groupBy('account_books.id', 'expenses.name')
                    ->get();
            } catch (\Exception $e) {
                $data['commissionPaid'] = collect();
            }
        }

        $data['loanDueAmount'] = 0;
        if (Schema::hasTable('loan_account_entries')) {
            try {
                $loanSummary = DB::table('loan_account_entries')
                    ->whereBetween('created_at', [$start, $end])
                    ->selectRaw("
                        SUM(CASE WHEN entry_type = 0 THEN total_amount ELSE 0 END) as loan_add,
                        SUM(CASE WHEN entry_type = 1 THEN total_amount ELSE 0 END) as loan_paid
                    ")
                    ->first();

                $loanAdd = $loanSummary->loan_add ?? 0;
                $loanPaid = $loanSummary->loan_paid ?? 0;
                $data['loanDueAmount'] = $loanAdd - $loanPaid;
            } catch (\Exception $e) {
                $data['loanDueAmount'] = 0;
            }
        }

        $data['retailerCommsions'] = collect();
        $data['retailerCommissionTotal'] = 0;
        try {
            $data['retailerCommsions'] = AccountBook::with(['retailAccount' => fn($q) => $q->where('company_share', 1)])
                ->where('account_type', 'retail-store')
                ->where('open', 0)
                ->whereBetween('closing_date', [$start, $end])
                ->whereHas('retailAccount', fn($q) => $q->where('company_share', 1))
                ->get();
            $data['retailerCommissionTotal'] = $data['retailerCommsions']->sum('commission') - $data['commissionPaidAmountSum'];
        } catch (\Exception $e) {
            $data['retailerCommissionTotal'] = 0;
        }

        $data['supplierStaffAmount'] = 0;
        if (Schema::hasTable('factory_account_entries')) {
            try {
                $data['supplierStaffAmount'] = DB::table('factory_account_entries')
                    ->whereBetween('created_at', [$start, $end])
                    ->where('entry_type', 0)
                    ->whereNotNull('purchase_id')
                    ->get()
                    ->unique('account_book_id')
                    ->sum(function($entry) {
                        $book = AccountBook::find($entry->account_book_id);
                        return $book ? ($book->staff ?? 0) : 0;
                    });
            } catch (\Exception $e) {
                $data['supplierStaffAmount'] = 0;
            }
        }

        // Zakat Due Calculation
        $zakatPayable = 0;
        try {
            $zakat = Zakat::where('fiscal_year_id', $period->id)->first();
            $zakatPayable = $zakat?->zakat_payable ?? 0;
        } catch (\Exception $e) {
            $zakatPayable = 0;
        }

        $zakatpad = 0;
        if (Schema::hasTable('expenses') && Schema::hasTable('expense_account_entries')) {
            try {
                $zakatExpense = DB::table('expenses')->where('name', 'Zakat')->where('type', 'zakat')->first();
                if ($zakatExpense && isset($zakatExpense->account_book_id)) {
                    $zakatpad = DB::table('expense_account_entries')
                        ->whereBetween('created_at', [$start, $end])
                        ->where('account_book_id', $zakatExpense->account_book_id)
                        ->where('entry_type', 0)
                        ->sum('total_amount');
                }
            } catch (\Exception $e) {
                $zakatpad = 0;
            }
        }
        $data['zakatDueAmount'] = $zakatPayable - $zakatpad;

        $data['totalLiabilities'] = ($data['supplierDue'] ?? 0) + ($data['giftSupplierDue'] ?? 0) + ($data['chequeDue'] ?? 0) + ($data['liabilitieAmount'] ?? 0) + ($data['retailerCommissionTotal'] ?? 0) + ($data['supplierStaffAmount'] ?? 0) + ($data['zakatDueAmount'] ?? 0) + ($data['loanDueAmount'] ?? 0);
        $data['netCapital'] = $data['totalAssets'] - $data['totalLiabilities'];

        return $data;
    }
}
