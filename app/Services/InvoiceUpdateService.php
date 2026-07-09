<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\View\RetailStoreAccountEntry;
use App\Models\{Invoice, InvoiceEntry, Inventory, Transaction, GiftTransaction, RetailStore, Shoe, ShoeToSize, AccountBook};

class InvoiceUpdateService
{
    public function update(Request $request, Invoice $invoice): Invoice
    {
        return DB::transaction(function () use ($request, $invoice) {
            $retailStore = RetailStore::findOrFail($request->input('retail_store_id'));
            
            // Update account book when store changes
            if ($invoice->accountBook->account_id != $retailStore->id) {
                $accountBook = $this->getAccountBook($retailStore);
                $invoice->account_book_id = $accountBook->id;
            } else {
                $accountBook = $invoice->accountBook;
            }
            
            // 2. Pre-calculation (to generate the amount)
            $tempSales = $this->preCalculateSales($request->input('sales', []));
            $returnsData = $invoice->returns; // Already assigned returns
            $expensesData = $invoice->retailStoreExpenses; // Already assigned expenses

            $totalRetailPrice = (float) $tempSales['total_retail_price'];
            $commissionAmt = round(($totalRetailPrice * (float) $request->commission) / 100, 2);
            
            $returnAmt = round($returnsData->sum(fn($r) => ($r->retail_price * $r->count) * (1 - ($r->commission / 100))), 2);
            $expenseAmt = (float) $expensesData->sum('amount');

            // Final Amount Calculation
            $finalAmount = round(($totalRetailPrice - $commissionAmt - $returnAmt - $expenseAmt + ($request->transport ?? 0) - ($request->discount ?? 0)), 2);

            // 3. Main Invoice Table Update
            $invoice->update([
                'commission' => $request->commission,
                'transport'  => $request->transport ?? 0,
                'discount'   => $request->discount ?? 0,
                'is_discount_product_sale' => $request->is_discount_product ? 1 : 0,
            ]);

            // 4. Sales entry and stock sync
            $salesSummary = $this->syncSales($request->input('sales', []), $invoice);

            // 5. Gift and payment sync
            $this->syncGifts($request->input('gifts', []), $invoice);
            $paidAmount = $this->syncPayments($request->input('payments', []), $retailStore, $invoice);

            // 6. Account entry update
            $this->updateAccountEntry($request, $invoice, $accountBook, $salesSummary, $returnsData, $expensesData, $paidAmount, $finalAmount);

            $invoice->load('accountBook.retailAccount', 'invoiceEntries.shoe', 'transactions');
            return $invoice;
        });
    }

    /** --- Pre-calculation helper --- */
    private function preCalculateSales($sales)
    {
        $totalRetailPrice = 0;
        foreach ($sales as $row) {
            if (empty($row['shoe_id'])) continue;
            $shoe = Shoe::where('code', $row['shoe_id'])->first();
            if ($shoe) {
                $totalRetailPrice += ($row['retail_price'] * (int) $row['count']);
            }
        }
        return ['total_retail_price' => $totalRetailPrice];
    }

    /** --- 2. Sales sync and stock management --- */
    private function syncSales($sales, $invoice)
    {
        $survivedIds = [];
        $totalCount = 0;
        $totalRetailPrice = 0;
        foreach ($sales as $row) {
            if (empty($row['shoe_id']) && !isset($row['id'])) continue;

            if (isset($row['id'])) {
                $entry = InvoiceEntry::findOrFail($row['id']);
                $diff = (int)$row['count'] - $entry->count;
                $shoe = Shoe::where('code', $row['shoe_id'])->first();
                if ($shoe) {
                    $row['shoe_id'] = $shoe->id;
                }
                Inventory::where('shoe_id', $entry->shoe_id)->decrement('count', $diff);
                $entry->retail_price = $row['retail_price'];
                $entry->fill($row);
                $entry->save();
            } else {
                $shoe = Shoe::where('code', $row['shoe_id'])->firstOrFail();
                $entry = new InvoiceEntry($row);
                $entry->shoe_id = $shoe->id;
                $entry->retail_price = $shoe->retail_price;
                $invoice->invoiceEntries()->save($entry);
                Inventory::where('shoe_id', $entry->shoe_id)->decrement('count', (int)$row['count']);
            }

            $survivedIds[] = $entry->id;
            $totalCount += $entry->count;
            $totalRetailPrice += ($entry->retail_price * $entry->count);
            
            // Size chart update
            ShoeToSize::where('reference_id', $invoice->id)->where('shoe_id', $entry->shoe_id)->delete();
            $this->recordSizeChart($row, $entry->shoe_id, $invoice);
        }

        // Back stock of removed items
        $invoice->invoiceEntries()->whereNotIn('id', $survivedIds)->get()->each(function($old) {
            Inventory::where('shoe_id', $old->shoe_id)->increment('count', $old->count);
            ShoeToSize::where('reference_id', $old->invoice_id)->where('shoe_id', $old->shoe_id)->delete();
            $old->delete();
        });

        return ['count' => $totalCount, 'total_retail_price' => $totalRetailPrice];
    }

    /** --- 3. Payment Sync --- */
    private function syncPayments($payments, $retailStore, $invoice)
    {
        $survivedIds = [];
        foreach ($payments ?? [] as $pay) {
            if (isset($pay['id'])) {
                $tx = Transaction::findOrFail($pay['id']);
                $tx->update([
                    'amount' => $pay['amount'], 
                    'description' => isset($pay['cheque_no']) ? 'চেক নং ' . $pay['cheque_no'] : $tx->description
                ]);
                $survivedIds[] = $tx->id;
            } else {
                if (empty($pay['amount'])) continue;
                $desc = isset($pay['cheque_no']) ? 'চেক নং ' . $pay['cheque_no'] : null;
                $res = Transaction::createTransaction('retail-store', $retailStore->id, 'income', $pay['payment_method'], $pay['amount'], $desc, now(), $invoice);
                $survivedIds[] = $res['transaction']->id;
            }
        }

        Transaction::where('attachment_id', $invoice->id)
            ->where('attachment_type', get_class($invoice))
            ->whereNotIn('id', $survivedIds)->delete();

        return Transaction::where('attachment_id', $invoice->id)->where('attachment_type', get_class($invoice))->sum('amount');
    }

    /** --- 4. Account Entry Update --- */
    private function updateAccountEntry($request, $invoice, $accountBook, $sales, $returns, $expenses, $paidAmount, $finalAmount)
    {
        $totalRetailPrice = (float) $sales['total_retail_price'];
        $commissionAmt = round(($totalRetailPrice * (float) $request->commission) / 100, 2);

        RetailStoreAccountEntry::where('invoice_id', $invoice->id)->update([
            'account_book_id' => $accountBook->id,
            'count' => $sales['count'],
            'total_retail_price' => $totalRetailPrice,
            'total_commission' => $request->commission,
            'commission_amount' => $commissionAmt,
            'transport' => $request->transport ?? 0,
            'discount' => $request->discount ?? 0,
            'paid_amount' => $paidAmount,
            'amount' => $finalAmount,
        ]);
    }

    /** --- 5. Helper Method (Size Chart, Gift, etc.) --- */
    private function recordSizeChart($row, $shoeId, $invoice)
    {
        if (isset($row['size_chart'])) {
            foreach ($row['size_chart'] as $size) {
                if (!empty($size['quantity']) && $size['quantity'] > 0) {
                    $shoeToSize = new ShoeToSize;
                    $shoeToSize->shoe_id = $shoeId;
                    $shoeToSize->size_id = $size['size_id'];
                    $shoeToSize->quantity = $size['quantity'];
                    $shoeToSize->type = 'sale';
                    $shoeToSize->operation = '-';
                    $shoeToSize->reference_id = $invoice->id;
                    $shoeToSize->save();
                }
            }
        }
    }

    private function syncGifts($gifts, $invoice)
    {
        $survivedIds = [];
        foreach ($gifts ?? [] as $gift) {
            if (isset($gift['id'])) {
                $gTx = GiftTransaction::findOrFail($gift['id']);
                $gTx->update(['count' => $gift['count']]);
                $survivedIds[] = $gTx->id;
            } else {
                if (empty($gift['gift_id'])) continue;
                $gTx = new GiftTransaction($gift + ['type' => 'sale']);
                $invoice->giftTransactions()->save($gTx);
                $survivedIds[] = $gTx->id;
            }
        }
        $invoice->giftTransactions()->where('type', 'sale')->whereNotIn('id', $survivedIds)->delete();
    }

    protected function getAccountBook(RetailStore $retailStore)
    {
        return AccountBook::where('account_id', $retailStore->id)
            ->where('account_type', 'retail-store')
            ->where('open', true)
            ->first();
    }
}
