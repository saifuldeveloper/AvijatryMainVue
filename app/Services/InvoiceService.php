<?php

namespace App\Services;

use App\Enums\ReturnToRetailerStatus;
use App\Models\{RetailStore, AccountBook, Invoice, InvoiceEntry, Shoe, Inventory, ShoeToSize, Transaction, GiftTransaction, BankAccount, Gift, Message};
use App\Models\ReturnToRetailer;
use App\Models\View\RetailStoreAccountEntry;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Repositories\InvoiceRepository;
use Inertia\Inertia;

class InvoiceService
{
    protected InvoiceRepository $repository;

    public function __construct(InvoiceRepository $repository)
    {
        $this->repository = $repository;
    }

    /* =========================================================
     | PREVIEW (NO DB WRITE)
     ========================================================= */
    public function preview(Request $request): array
    {
        $retailStore = RetailStore::find($request->input('retail_store_id'));
        $accountBook = $this->getAccountBook($retailStore);
        $sales = $request->input('sales', []);
        
        // Filter out empty sales rows
        $sales = array_filter($sales, function ($sale) {
            return !empty($sale['shoe_id']);
        });

        $total_amount = 0;
        foreach ($sales as $i => $sale) {
            $shoe = Shoe::where('code', $sale['shoe_id'])->first();
            if ($shoe) {
                $sales[$i]['shoe'] = $shoe;
                $total_amount += $sales[$i]['count'] * $shoe['retail_price'];
            } else {
                $total_amount += $sales[$i]['count'] * 0;
            }
        }
        $preview = true;
        $is_discount_product = $request->is_discount_product;
        $invoice_id = Invoice::getNextId();
        $commission = $request->input('commission');
        $total_commission = $total_amount * $commission / 100;
        $commission_deducted = $total_amount - $total_commission;
        $return_count = $retailStore->return_count;
        $return_amount = $retailStore->return_amount;
        $return_deducted = $commission_deducted - $return_amount;
        $transport = $request->input('transport');
        $transport_added = $return_deducted + $transport;
        $other_costs = $retailStore->unlistedExpenses()->sum('amount');
        $other_costs_deducted = $transport_added - $other_costs;
        $discount = $request->input('discount');
        $total_receivable = $other_costs_deducted - $discount;
        $payments = $request->input('payments', []);
        $payment_amount = 0;
        foreach ($payments as $payment) {
            $payment_amount += $payment['amount'] ?? 0;
        }
        $previous_due = $accountBook->getCalculatedBalance(); // Use the calculated balance instead of the stored balance
        $total_due = $previous_due + $total_receivable - $payment_amount;

        $sales = collect($sales);
        $sales = $sales->sortBy('shoe.category.parent.id')
            ->groupBy('shoe.category.parent.name')
            ->transform(function ($item, $key) {
                return $item->groupBy(function ($item, $key) {
                    return isset($item['shoe']['retail_price']) ? '' . $item['shoe']['retail_price'] : '0';
                })->sortByDesc(function ($item, $key) {
                    return doubleval($key);
                })->transform(function ($item, $key) {
                    return $item->sortBy('shoe.category.id')
                        ->groupBy('shoe.category.name')
                        ->transform(function ($item, $key) {
                            return $item->sum('count');
                        });
                });
            });

        $payments = $request->input('payments', []);
        $validPayments = [];
        foreach ($payments as $item) {
            if (empty($item['payment_method']) || empty($item['amount'])) {
                continue;
            }
            $bankAccount = BankAccount::find($item['payment_method']);
            if ($item['payment_method'] == 'cheque') {
                $payment['payment_method'] = 'চেক';
            } else {
                $payment = [
                    'payment_method' => $bankAccount['name'] ?? '',
                    'amount' => $item['amount'],
                ];
            }
            if (isset($item['cheque_no'])) {
                $payment['cheque_no'] = $item['cheque_no'];
            }
            $validPayments[] = $payment;
        }
        $gifts_input = $request->input('gifts', []);

        $gifts = [];
        foreach ($gifts_input as $i => $gift_input) {
            if (empty($gift_input['gift_id']) || empty($gift_input['count']))
                continue;
            $gift = Gift::find($gift_input['gift_id']);
            if ($gift) {
                $gift['gift'] = $gift;
                $gift['count'] = $gift_input['count'];
                $gifts[] = $gift;
            }
        }
        return [
            'preview' => $preview,
            'invoice_id' => $invoice_id,
            'retailStore' => $retailStore,
            'sales' => $sales,
            'total_amount' => $total_amount,
            'commission' => $commission,
            'total_commission' => $total_commission,
            'commission_deducted' => $commission_deducted,
            'return_count' => $return_count,
            'return_amount' => $return_amount,
            'return_deducted' => $return_deducted,
            'transport' => $transport,
            'transport_added' => $transport_added,
            'other_costs' => $other_costs,
            'other_costs_deducted' => $other_costs_deducted,
            'discount' => $discount,
            'total_receivable' => $total_receivable,
            'payment_amount' => $payment_amount,
            'previous_due' => $previous_due,
            'total_due' => $total_due,
            'gifts' => $gifts,
            'validPayments' => $validPayments,
            'is_discount_product' => $is_discount_product,
        ];
    }

    /* =========================================================
     | STORE (DB WRITE)
     ========================================================= */
    public function store(Request $request): Invoice
    {
        DB::beginTransaction();
        try {
            $retailStore = RetailStore::find($request->input('retail_store_id'));
            $accountBook = $this->getAccountBook($retailStore);

            // --- calculation Start ---
            $tempSales = $this->preCalculateSales($request->input('sales'));
            $returnsData = $retailStore->unlistedReturns()->get();
            $expensesData = $retailStore->unlistedExpenses()->get();

            $totalRetailPrice = (float) $tempSales['total_retail_price'];
            $commissionAmt = round(($totalRetailPrice * (float) $request->commission) / 100, 2);

            $returnAmt = round($returnsData->sum(fn($r) => ($r->retail_price * $r->count) * (1 - ($r->commission / 100))), 2);
            $expenseAmt = (float) $expensesData->sum('amount');

            // entry final amount
            $finalAmount = round(($totalRetailPrice - $commissionAmt - $returnAmt - $expenseAmt + ($request->transport ?? 0) - ($request->discount ?? 0)), 2);

            // request update for invoice table right amount set
            $request->merge(['total_amount' => $finalAmount]);
            // --- end calculation ---

            $invoice = $this->createInvoice($request, $accountBook);
            $salesData = $this->processSales($request->input('sales'), $invoice);
            $this->processReturns($retailStore, $invoice);
            $this->processExpenses($retailStore, $invoice, $accountBook);
            $this->processGifts($request->input('gifts', []), $invoice);

            $paidAmount = $this->processPayments($request->input('payments', []), $retailStore, $invoice);

            $this->createAccountEntry($request, $invoice, $accountBook, $salesData, $returnsData, $expensesData, $paidAmount, $finalAmount);
            DB::commit();
            
            $invoice = Invoice::with('accountBook.retailAccount', 'invoiceEntries.shoe', 'transactions')->find($invoice->id);
            $this->sendInvoiceSms($invoice, $retailStore, $finalAmount, $paidAmount, $salesData['count']);
            
            return $invoice;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function preCalculateSales($sales)
    {
        $totalRetailPrice = 0;
        foreach ($sales as $row) {
            if (empty($row['shoe_id']))
                continue;
            $shoe = Shoe::where('code', $row['shoe_id'])->first();
            if ($shoe) {
                $totalRetailPrice += ($shoe->retail_price * (int) $row['count']);
            }
        }
        return ['total_retail_price' => $totalRetailPrice];
    }

    private function createInvoice($request, $accountBook)
    {
        $invoice = new Invoice;
        $request->merge(['retail_store_status' => 'Pending']);
        $invoice->account_book_id = $accountBook->id;
        $invoice->commission = $request->commission;
        $invoice->transport = $request->transport ?? 0;
        $invoice->discount = $request->discount ?? 0;
        $invoice->is_discount_product_sale = $request->is_discount_product ? 1 : 0;
        $accountBook->invoices()->save($invoice);
        return $invoice;
    }

    private function processSales($sales, $invoice)
    {
        $totalCount = 0;
        $totalRetailPrice = 0;
        $checkedShoes = [];

        $shoeCodes = array_filter(array_map(fn($row) => $row['shoe_id'] ?? null, $sales));
        $shoes = Shoe::whereIn('code', $shoeCodes)->get()->keyBy('code');
        $shoeIds = $shoes->pluck('id')->toArray();
        $inventories = Inventory::whereIn('shoe_id', $shoeIds)->get()->keyBy('shoe_id');

        foreach ($sales as $row) {
            $shoeCode = $row['shoe_id'] ?? null;
            $count = (int) ($row['count'] ?? 0);

            if (empty($shoeCode))
                continue;

            $currentShoeCode = strtolower(trim($shoeCode));
            if (in_array($currentShoeCode, $checkedShoes)) {
                throw new \Exception(__('pages.duplicate_shoe_error', ['id' => $shoeCode]) ?? "জুতা আইডি {$shoeCode} একাধিকবার প্রদান করা হয়েছে।");
            }
            $checkedShoes[] = $currentShoeCode;

            $shoe = $shoes[$shoeCode] ?? null;
            if (!$shoe) {
                throw new \Exception("Shoe not found for Code: " . $shoeCode);
            }
            $inventory = $inventories[$shoe->id] ?? null;
            if (!$inventory) {
                throw new \Exception("Inventory not found for ID: " . $shoe->id);
            }

            if ($inventory->count < $count) {
                throw new \Exception(__('pages.stock_out_error', [
                    'id' => $shoe->code,
                    'available' => $inventory->count
                ]) ?? "জুতা {$shoe->code} স্টক নাই। এভেইলেবল: {$inventory->count}");
            }

            $invoiceEntry = new InvoiceEntry();
            $invoiceEntry->fill($row);
            $invoiceEntry->shoe_id = $shoe->id;
            $invoiceEntry->retail_price = (float) $shoe->retail_price;
            $invoice->invoiceEntries()->save($invoiceEntry);

            $totalCount += $count;
            $totalRetailPrice += $shoe->retail_price * $count;

            $inventory->decrement('count', $count);

            $this->recordSizeChart($row, $shoe, $invoice);
        }

        return [
            'count' => $totalCount,
            'total_retail_price' => $totalRetailPrice
        ];
    }

    private function processReturns($retailStore, $invoice)
    {
        $returns = $retailStore->unlistedReturns()->get();
        foreach ($returns as $return) {
            $return->status = 'accepted';
            $invoice->returns()->save($return);
            RetailStoreAccountEntry::where('return_id', $return->id)->update(['paid_amount' => DB::raw('return_amount')]);
        }
        $masterReturnIds = $returns->pluck('return_id')->unique();
        RetailStoreAccountEntry::whereIn('return_id', $masterReturnIds)->forceDelete();
        if ($masterReturnIds->isNotEmpty()) {
            ReturnToRetailer::whereIn('id', $masterReturnIds)
                ->update([
                    'status' => ReturnToRetailerStatus::APPROVED->value,
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                    'invoice_id' => $invoice->id,
                ]);
        }
        return $returns;
    }

    private function processExpenses($retailStore, $invoice, $accountBook)
    {
        $expenses = $retailStore->unlistedExpenses()->get();
        foreach ($expenses as $expense) {
            $invoice->retailStoreExpenses()->save($expense);
            RetailStoreAccountEntry::where('expense_id', $expense->id)->forceDelete();
            foreach (['income', 'expense'] as $type) {
                $resp = Transaction::createTransaction('retail-store-expense', $accountBook->account_id, $type, 1, $expense->amount, $expense->description, now());
                $trans = Transaction::find($resp['transaction']->id);
                $trans->update(['attachment_type' => get_class($expense), 'attachment_id' => $expense->id]);
            }
        }
        return $expenses;
    }

    private function processPayments($payments, $retailStore, $invoice)
    {
        $paidAmount = 0;
        foreach ($payments as $payment) {
            if (empty($payment['amount']))
                continue;
            $desc = isset($payment['cheque_no']) ? 'চেক নং ' . $payment['cheque_no'] : null;
            Transaction::createTransaction('retail-store', $retailStore->id, 'income', $payment['payment_method'], $payment['amount'], $desc, now(), $invoice);
            $paidAmount += $payment['amount'];
        }
        return $paidAmount;
    }

    private function createAccountEntry($request, $invoice, $accountBook, $sales, $returns, $expenses, $paidAmount, $finalAmount)
    {
        $totalRetailPrice = (float) $sales['total_retail_price'];
        $commissionAmt = round(($totalRetailPrice * (float) $request->commission) / 100, 2);
        $entry = new RetailStoreAccountEntry;
        $entry->fill([
            'entry_id' => $invoice->id,
            'entry_type' => '0',
            'account_book_id' => $accountBook->id,
            'invoice_id' => $invoice->id,
            'count' => $sales['count'],
            'total_retail_price' => $totalRetailPrice,
            'return_count' => $returns->sum('count'),
            'return_amount' => round($returns->sum(fn($r) => ($r->retail_price * $r->count) * (1 - ($r->commission / 100))), 2),
            'expense_amount' => (float) $expenses->sum('amount'),
            'expense_description' => $expenses->pluck('description')->implode(', '),
            'total_commission' => $request->commission,
            'commission_amount' => $commissionAmt,
            'transport' => $request->transport ?? 0,
            'discount' => $request->discount ?? 0,
            'paid_amount' => $paidAmount,
            'amount' => $finalAmount,
            'is_discount_product_sale' => $request->is_discount_product ? 1 : 0,
        ]);
        $entry->save();
    }

    private function sendInvoiceSms($invoice, $retailStore, $totalAmount, $paidAmount, $itemCount)
    {
        try {
            $message = Message::where('id', '1')->select('retailer_message', 'retailer_status')->first();
            if ($message && $message->retailer_status == '1') {
                $formattedMessage = replaceMessagePlaceholders(
                    $message->retailer_message,
                    $retailStore->name,
                    $invoice->id,
                    $totalAmount,
                    $paidAmount,
                    ($totalAmount - $paidAmount),
                    $itemCount
                );

                $formattedNumber = formatBangladeshiNumber($retailStore->mobile_no);
                Http::post(env('SMS_PROVIDER_URL'), [
                    'api_key' => env('SMS_API_KEY'),
                    'sender_id' => env('SMS_SENDER_ID'),
                    'msg' => $formattedMessage,
                    'to' => $formattedNumber,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error("SMS Sending Failed for Invoice #{$invoice->id}: " . $e->getMessage());
        }
    }

    private function recordSizeChart($row, $shoe, $invoice)
    {
        if (isset($row['size_chart'])) {
            foreach ($row['size_chart'] as $size) {
                if (!empty($size['quantity']) && $size['quantity'] > 0) {
                    $shoeToSize = new ShoeToSize;
                    $shoeToSize->shoe_id = $shoe->id;
                    $shoeToSize->size_id = $size['size_id'];
                    $shoeToSize->sorts = isset($size['sorts']) ? json_encode($size['sorts']) : null;
                    $shoeToSize->quantity = $size['quantity'];
                    $shoeToSize->type = 'sale';
                    $shoeToSize->operation = '-';
                    $shoeToSize->reference_id = $invoice->id;
                    $shoeToSize->save();
                }
            }
        }
    }

    private function processGifts($gifts, $invoice)
    {
        foreach ($gifts as $row) {
            if (!empty($row['gift_id'])) {
                $giftSale = new GiftTransaction;
                $giftSale->fill($row);
                $giftSale->type = 'sale';
                $invoice->giftTransactions()->save($giftSale);
            }
        }
    }

    public function show(Request $request, Invoice $invoice)
    {
        $relations = [
            'accountBook.retailAccount',
            'invoiceEntries.shoe.category.parent',
            'invoiceEntries.shoe.color',
            'giftTransactions.gift',
            'transactions.toAccount.BankAccount'
        ];

        $invoice->load($relations);
        
        $invoice->total_amount_val = $invoice->getTotalAmount();
        $invoice->total_commission_val = $invoice->getTotalCommission();
        $invoice->commission_deducted_val = $invoice->getCommissionDeducted();
        $invoice->return_count_val = $invoice->getReturnCount();
        $invoice->return_amount_val = $invoice->getReturnAmount();
        $invoice->return_deducted_val = $invoice->getReturnDeducted();
        $invoice->transport_added_val = $invoice->getTransportAdded();
        $invoice->other_costs_val = $invoice->getOtherCosts();
        $invoice->other_costs_deducted_val = $invoice->getOtherCostsDeducted();
        $invoice->total_receivable_val = $invoice->getTotalReceivable();
        $invoice->total_payment_val = $invoice->getTotalPayment();
        $invoice->account_book_balance_val = $invoice->getAccountBookBalance();
        $invoice->account_book_previous_balance_val = $invoice->getAccountBookPreviousBalance();
        
        return $invoice;
    }

    protected function getAccountBook(RetailStore $retailStore)
    {
        return AccountBook::where('account_id', $retailStore->id)
            ->where('account_type', 'retail-store')
            ->where('open', true)
            ->first();
    }

    public function create()
    {
        $memoNo = Invoice::getNextId();
        $gifts = Gift::all();
        $bankAccount = BankAccount::all();
        $bankAccounts = new Collection();
        foreach ($bankAccount as $item) {
            if ($item->bank === 'ক্যাশ') {
                $bankAccounts->push((object) ['id' => $item->id, 'name' => 'ক্যাশ']);
            } else {
                $bankAccounts->push((object) ['id' => $item->id, 'name' => $item->bank . ' - ' . $item->branch . ' - (' . $item->account_no . ')']);
            }
        }
        $bankAccounts->push((object) ['id' => 'cheque', 'name' => 'চেক']);

        $retailStores = RetailStore::where('onetime_buyer', false)->get()->each(function ($store) {
            $store->append(['return_count', 'return_amount', 'other_costs']);
        });

        $sessionRetailStore = session()->get('retail-store', null);
        if ($sessionRetailStore) {
            $sessionRetailStore->append(['return_count', 'return_amount', 'other_costs']);
        }

        return Inertia::render('Invoice/Form', compact('memoNo', 'bankAccounts', 'gifts', 'retailStores', 'sessionRetailStore'));
    }

    public function edit(Invoice $invoice)
    {
        $invoice->load('accountBook.retailAccount', 'invoiceEntries.shoe.category.parent', 'invoiceEntries.shoe.color');
        
        $invoice->total_amount_val = $invoice->getTotalAmount();
        $invoice->total_commission_val = $invoice->getTotalCommission();
        $invoice->commission_deducted_val = $invoice->getCommissionDeducted();
        $invoice->return_count_val = $invoice->getReturnCount();
        $invoice->return_amount_val = $invoice->getReturnAmount();
        $invoice->return_deducted_val = $invoice->getReturnDeducted();
        $invoice->transport_added_val = $invoice->getTransportAdded();
        $invoice->other_costs_val = $invoice->getOtherCosts();
        $invoice->other_costs_deducted_val = $invoice->getOtherCostsDeducted();
        $invoice->total_receivable_val = $invoice->getTotalReceivable();
        $invoice->total_payment_val = $invoice->getTotalPayment();
        $invoice->account_book_balance_val = $invoice->getAccountBookBalance();
        $invoice->account_book_previous_balance_val = $invoice->getAccountBookPreviousBalance();

        // Fetch size data for each entry
        foreach ($invoice->invoiceEntries as $entry) {
            $entry->sizes_data = \App\Models\ShoeToSize::where([
                'shoe_id' => $entry->shoe_id,
                'type' => 'sale',
                'reference_id' => $invoice->id
            ])->with('size')->get();
            
            // Get available stock including this entry's count
            $inventory = \App\Models\Inventory::where('shoe_id', $entry->shoe_id)->first();
            $entry->available = $inventory ? ($inventory->count + $entry->count) : $entry->count;
        }

        $gifts = Gift::all();
        $giftTransactions = GiftTransaction::where('attachment_id', $invoice->id)
            ->where('type', 'sale')
            ->where('attachment_type', 'App\Models\Invoice')
            ->get();
        $transactions = Transaction::with('fromAccount.BankAccount', 'toAccount.BankAccount')
            ->where('attachment_id', $invoice->id)
            ->where('attachment_type', 'App\Models\Invoice')
            ->get();

        $bankAccount = BankAccount::all();
        $bankAccounts = new Collection();
        foreach ($bankAccount as $item) {
            if ($item->bank === 'ক্যাশ') {
                $bankAccounts->push((object) ['id' => $item->id, 'name' => 'ক্যাশ']);
            } else {
                $bankAccounts->push((object) ['id' => $item->id, 'name' => $item->bank . ' - ' . $item->branch . ' - (' . $item->account_no . ')']);
            }
        }
        $bankAccounts->push((object) ['id' => 'cheque', 'name' => 'চেক']);

        $retailStores = RetailStore::where('onetime_buyer', false)->get()->each(function ($store) {
            $store->append(['return_count', 'return_amount', 'other_costs']);
        });

        return Inertia::render('Invoice/Form', compact('invoice', 'bankAccounts', 'gifts', 'giftTransactions', 'transactions', 'retailStores'));
    }

    public function destroy($id)
    {
        $response = $this->repository->destroy($id);
        return redirect()->route('invoice.create')->with('success-alert', $response['success']);
    }

    public function forceDelete($id)
    {
        $response = $this->repository->forceDelete($id);
        if (isset($response['error'])) {
            return back()->with('error-alert', $response['error']);
        }
        return redirect()->route('account-book.show', $response['accountBook']->id)->with('success-alert', $response['success']);
    }

    public function restore($id)
    {
        $invoice = $this->repository->restore($id);
        return redirect()->route('account-book.show', $invoice->account_book_id)->with('success-alert', __('pages.Shoes sold restored') ?? 'Shoes sold restored');
    }
}
