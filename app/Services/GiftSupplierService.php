<?php

namespace App\Services;

use App\Enums\GiftSupplierEntryType;
use App\Models\Transaction;
use App\Models\GiftSupplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\TransactionHistory;
use Illuminate\Support\Facades\Auth;
use App\Models\View\BankAccountEntry;
use App\Models\GiftSupplierAccountEntry;
use App\Repositories\GiftSupplierRepository;
use App\Models\AccountBook;
use App\Enums\ClosingType;
use App\Models\ManualClosing;
use App\Models\GiftPurchase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;

class GiftSupplierService
{
    protected $repository;

    public function __construct(GiftSupplierRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $trashGiftSuppliers = GiftSupplier::onlyTrashed()->get();

        $query = GiftSupplier::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('address')) {
            $query->where('address', 'like', '%' . $request->address . '%');
        }
        if ($request->filled('mobile_no')) {
            $query->where('mobile_no', 'like', '%' . $request->mobile_no . '%');
        }

        $giftSuppliers = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('GiftSupplier/Index', [
            'giftSuppliers' => $giftSuppliers,
            'trashGiftSuppliers' => $trashGiftSuppliers,
            'filters' => $request->only(['name', 'address', 'mobile_no']),
        ]);
    }

    public function getGiftSupplierslist(Request $request)
    {
        // Left for backwards compatibility if needed, but index() is preferred for Inertia
    }

    public function getGiftSuppliers(Request $request)
    {
        $searchTerm = $request->input('search');
        $giftSuppliers = GiftSupplier::when($searchTerm, function ($query, $searchTerm) {
            return $query->where('name', 'like', '%' . $searchTerm . '%')
                ->orWhere('mobile_no', 'like', '%' . $searchTerm . '%');
        })->latest()->get();

        return response()->json([
            "status" => true,
            "data" => $giftSuppliers
        ]);
    }

    public function create()
    {
        // Handled in Vue Modal
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:gift_suppliers,name',
            'address' => 'required|string',
            'mobile_no' => 'required|string',
            'mobile_no_two' => 'nullable|string',
        ], [
            'name.required' => 'নাম দেওয়া আবশ্যক।',
            'name.unique' => 'এই নামটি ইতিমধ্যে গিফট মহাজনে ব্যবহৃত হয়েছে।',
        ]);

        $giftSupplier = $this->repository->store($request->all());
        logActivity($giftSupplier, $giftSupplier->id, 'gift-supplier.show', 'Create', __('New Gift factory Information Saved'));

        return Redirect::back()->with('success', __('pages.New Gift factory Information Saved') ?? 'Supplier added successfully');
    }

    public function show(GiftSupplier $giftSupplier)
    {
        $giftSupplier = $this->repository->show($giftSupplier);
        
        $accountBooks = $giftSupplier->accountBooks()->orderBy('id', 'desc')->get();
        foreach ($accountBooks as $book) {
            $book->calculated_balance = $book->getCalculatedBalance();
            $book->description = $book->getDescription();
        }

        $entries = $giftSupplier->entries()->paginate(10);

        return Inertia::render('GiftSupplier/Show', [
            'giftSupplier' => $giftSupplier,
            'entries' => $entries,
            'accountBooks' => $accountBooks,
        ]);
    }

    public function edit(GiftSupplier $giftSupplier)
    {
        // Handled in Vue Modal
    }

    public function update(Request $request, GiftSupplier $giftSupplier)
    {
        $request->validate([
            'name' => [
                'required',
                Rule::unique('gift_suppliers', 'name')->ignore($giftSupplier->id),
            ],
            'address' => 'required|string',
            'mobile_no' => 'required|string',
            'mobile_no_two' => 'nullable|string',
        ], [
            'name.required' => 'নাম দেওয়া আবশ্যক।',
            'name.unique' => 'এই নামটি ইতিমধ্যে গিফট মহাজনে ব্যবহৃত হয়েছে।',
        ]);

        $updatedSupplier = $this->repository->update($giftSupplier, $request->all());
        logActivity($updatedSupplier, $updatedSupplier->id, 'gift-supplier.show', 'Update', __('Gift factory Information Edited'));

        return Redirect::back()->with('success', __('pages.Gift factory Information Edited') ?? 'Supplier updated successfully');
    }

    public function destroy(GiftSupplier $giftSupplier)
    {
        $message = $this->repository->destroy($giftSupplier);
        return Redirect::route('gift-supplier.index')->with('success', $message['success']);
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

    public function datalist()
    {
        // Handled in frontend
    }

    public function entryDateCurrection($id)
    {
        $entry = GiftSupplierAccountEntry::find($id);
        $transaction = Transaction::where('to_account_id', $entry->account_book_id)
            ->where('created_at', $entry->created_at)
            ->where('amount', $entry->total_amount)
            ->where('payment_type', 'gift-supplier')
            ->first();

        if ($transaction) {
            $bankEntry = BankAccountEntry::where('account_book_id', $transaction->from_account_id)
                ->where('created_at', $entry->created_at)
                ->where('total_amount', $transaction->amount)
                ->first();

            if ($bankEntry) {
                $transactionHistory = TransactionHistory::where('model', 'App\Models\GiftSupplier')
                    ->where('transaction_id_two', $transaction->id)
                    ->where('entry_bank_id', $bankEntry->id)
                    ->first();

                if ($transactionHistory) {
                    $transactionHistory->created_at = $entry->created_at;
                    $transactionHistory->updated_at = $entry->updated_at;
                    $transactionHistory->save();
                }
            }
        }
        return Redirect::back();
    }

    private function applyGiftSupplierFilters($query, Request $request)
    {
        return $query->when($request->daterange, function ($q) use ($request) {
            $dates = explode(' - ', $request->daterange);
            if (count($dates) === 2) {
                $q->whereBetween('gift_supplier_account_entries.created_at', [
                    Carbon::parse($dates[0])->startOfDay(),
                    Carbon::parse($dates[1])->endOfDay(),
                ]);
            }
        })
            ->when($request->type !== null && $request->type !== '', function ($q) use ($request) {
                $q->where('gift_supplier_account_entries.entry_type', $request->type);
            })
            ->when($request->gift_purchase_id, function ($q) use ($request) {
                $q->where('gift_supplier_account_entries.gift_purchase_id', $request->gift_purchase_id);
            })
            ->when($request->gift_name, function ($q) use ($request) {
                $giftName = $request->gift_name;
                $q->where(function ($q2) use ($giftName) {
                    $q2->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(gift_supplier_account_entries.gift_name, '$[0]')) LIKE ?", ["%{$giftName}%"])
                        ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(gift_supplier_account_entries.gift_name, '$[1]')) LIKE ?", ["%{$giftName}%"]);
                });
            })
            ->when($request->count, function ($q) use ($request) {
                $q->where('gift_supplier_account_entries.count', $request->count);
            })
            ->when($request->total_amount, function ($q) use ($request) {
                $q->where('gift_supplier_account_entries.total_amount', $request->total_amount);
            });
    }

    public function giftSupplierEntryList(Request $request, int $id)
    {
        $accountBook = AccountBook::findOrFail($id);
        $openingBalance = (float) $accountBook->getOpeningBalance();

        // 1. Base Query & Filters
        $query = GiftSupplierAccountEntry::where('account_book_id', $accountBook->id);
        $query = $this->applyGiftSupplierFilters($query, $request);

        $totalRecords = $query->count();
        $start = (int) ($request->input('start') ?? 0);
        $length = (int) ($request->input('length') ?? 200);
        $draw = (int) ($request->input('draw') ?? 1);
        $orderDirection = $request->has('order') && is_array($request->order) ? ($request->order[0]['dir'] ?? 'desc') : 'desc';

        // 2. Fetch Entries
        $entries = (clone $query)->orderBy('created_at', $orderDirection)
            ->orderBy('id', $orderDirection)
            ->skip($start)
            ->take($length)
            ->get();

        // 3. Calculate Balance Before Page
        $balanceBefore = $openingBalance;
        if ($entries->count() > 0) {
            $oldestEntry = $orderDirection === 'asc' ? $entries->first() : $entries->last();
            $minId = $oldestEntry->id;
            $minCreatedAt = $oldestEntry->created_at;

            $sumQuery = GiftSupplierAccountEntry::where('account_book_id', $accountBook->id)
                ->where(function ($q) use ($minCreatedAt, $minId) {
                    $q->where('created_at', '<', $minCreatedAt)
                        ->orWhere(function ($q2) use ($minCreatedAt, $minId) {
                            $q2->where('created_at', '=', $minCreatedAt)
                                ->where('id', '<', $minId);
                        });
                });

            $sumQuery = $this->applyGiftSupplierFilters($sumQuery, $request);

            $netSum = (float) $sumQuery->selectRaw("SUM(COALESCE(total_amount, 0) - COALESCE(payment_amount, 0)) as net_sum")->value('net_sum');
            $balanceBefore += $netSum;
        }

        // 4. Compute Running Balance (DRY Approach)
        $running = $balanceBefore;
        $balances = [];
        $iterableEntries = $orderDirection === 'asc' ? $entries : $entries->reverse();

        foreach ($iterableEntries as $row) {
            $amountDiff = (float) $row->total_amount - (float) $row->payment_amount;
            $balances[$row->id] = $running + $amountDiff;
            $running += $amountDiff;
        }

        // 5. Handle Manual Closing Specific Logic
        $manualClosingMap = null;
        if ($accountBook->closing_type === ClosingType::MANUAL) {
            $manualClosingEntry = ManualClosing::where('account_book_id', $accountBook->id)->first();
            $manualClosingData = json_decode($manualClosingEntry->closing_data ?? '{}', true);
            $finalBalance = (float) ($manualClosingData['input-remaining'] ?? 0);

            $futurePaymentsSum = 0;
            if ($entries->count() > 0) {
                $latestEntry = $orderDirection === 'desc' ? $entries->first() : $entries->last();
                $latestId = $latestEntry->id;
                $latestCreatedAt = $latestEntry->created_at;

                $futurePaymentsSum = (float) GiftSupplierAccountEntry::where('account_book_id', $accountBook->id)
                    ->where('entry_type', GiftSupplierEntryType::ClosingPayment)
                    ->where(function ($q) use ($latestCreatedAt, $latestId) {
                        $q->where('created_at', '>', $latestCreatedAt)
                            ->orWhere(function ($q2) use ($latestCreatedAt, $latestId) {
                                $q2->where('created_at', '=', $latestCreatedAt)
                                    ->where('id', '>', $latestId);
                            });
                    })->sum('payment_amount');
            }

            $currentFinalBalance = $finalBalance - $futurePaymentsSum;
            $manualClosingMap = [];
            $pageEntriesLatestFirst = $orderDirection === 'desc' ? $entries : $entries->reverse();

            foreach ($pageEntriesLatestFirst as $entry) {
                $manualClosingMap[$entry->id] = $currentFinalBalance;
                if ($entry->entry_type === GiftSupplierEntryType::ClosingPayment) {
                    $currentFinalBalance -= (float) $entry->payment_amount;
                }
            }
        }

        // 6. Build DataTable Rows
        $data = [];
        foreach ($entries as $row) {
            $type = $memo = $gift = $count = $total_amount = $payment_amount = '';

            if ($row->entry_type === GiftSupplierEntryType::Purchase) {
                $type = __('pages.Purchase');
                $memo = '<a href="' . route('gift-purchase.show', $row->gift_purchase_id) . '" target="_blank">' . $row->gift_purchase_id . '</a>';

                $giftNames = [];
                if ($row->gift_name) {
                    $decoded = json_decode($row->gift_name, true);
                    if (is_array($decoded))
                        $giftNames = $decoded;
                }
                $gift = empty($giftNames) ? '' : implode(', ', $giftNames);
                $count = $row->count;
                $total_amount = $row->total_amount > 0 ? toFixed($row->total_amount) : '';
                $payment_amount = $row->payment_amount > 0 ? toFixed($row->payment_amount) : '';

            } elseif ($row->entry_type === GiftSupplierEntryType::Payment) {
                $type = isset($row->closing_id) ? __('pages.Closing payment') : __('pages.payment');
                $gift = $row->account_name . (!empty($row->description) ? ' (' . $row->description . ')' : '');
                $payment_amount = $row->payment_amount > 0 ? toFixed($row->payment_amount) : '';
            }

            $balanceVal = $manualClosingMap === null ? ($balances[$row->id] ?? 0) : ($manualClosingMap[$row->id] ?? 0);

            $data[] = [
                'id' => $row->id,
                'created_at' => dateTimeFormat($row->created_at),
                'type' => $type,
                'memo' => $memo,
                'gift' => $gift,
                'count' => $count,
                'total_amount' => $total_amount,
                'payment_amount' => $payment_amount,
                'balance' => toFixed($balanceVal),
            ];
        }

        // 7. Handle Extra Rows (Commission, Staff, Opening Balance)
        $numPrepended = (!$accountBook->open ? (($accountBook->commission > 0 ? 1 : 0) + ($accountBook->staff > 0 ? 1 : 0)) : 0);
        $numAppended = ($openingBalance != 0 ? 1 : 0);
        $recordsFiltered = $totalRecords + $numPrepended + $numAppended;

        if ($start === 0 && !$accountBook->open) {
            $prepended = [];
            $baseExtraRow = [
                'id' => 0,
                'created_at' => '',
                'type' => __('pages.Closing'),
                'memo' => '',
                'count' => '',
                'total_amount' => '',
                'action' => ''
            ];

            if ($accountBook->commission > 0) {
                $prepended[] = array_merge($baseExtraRow, [
                    'gift' => __('pages.commission'),
                    'payment_amount' => toFixed($accountBook->commission),
                    'balance' => toFixed($accountBook->getDescriptionBalance())
                ]);
            }
            if ($accountBook->staff > 0) {
                $prepended[] = array_merge($baseExtraRow, [
                    'gift' => __('pages.staff_expences'),
                    'payment_amount' => toFixed($accountBook->staff),
                    'balance' => toFixed($accountBook->getDescriptionBalance() + $accountBook->commission)
                ]);
            }
            $data = array_merge($prepended, $data);
        }

        if ($start + $length >= $recordsFiltered && $openingBalance != 0) {
            $data[] = [
                'id' => 0,
                'created_at' => '',
                'type' => '',
                'memo' => '',
                'gift' => __('pages.old') ?? 'সাবেক',
                'count' => '',
                'total_amount' => '',
                'payment_amount' => '',
                'balance' => toFixed($openingBalance),
                'action' => ''
            ];
        }

        // 8. Assign DT_RowIndex
        foreach ($data as $i => &$row) {
            $row['DT_RowIndex'] = $orderDirection === 'desc' ? ($recordsFiltered - $start - $i) : ($start + $i + 1);
        }

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalRecords + $numPrepended + $numAppended,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }
}
