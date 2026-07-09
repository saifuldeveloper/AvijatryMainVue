<?php

namespace App\Repositories;

use App\Models\Invoice;
use App\Models\InvoiceEntry;
use App\Models\Inventory;
use App\Models\RetailStoreExpense;
use App\Models\ReturnFromRetailEntry;
use App\Models\GiftTransaction;
use App\Models\Transaction;
use App\Models\View\RetailStoreAccountEntry;
use Illuminate\Support\Facades\DB;

class InvoiceRepository
{
    public function destroy($id)
    {
        $invoice = Invoice::with('accountBook.retailAccount')->findOrFail($id);

        InvoiceEntry::where('invoice_id', $invoice->id)
            ->each(function ($item) {
                if ($inventory = Inventory::find($item->shoe_id)) {
                    $inventory->increment('count', $item->count);
                }
                $item->delete();
            });

        RetailStoreExpense::where('invoice_id', $invoice->id)
            ->each(function ($expense) {
                Transaction::where([
                    ['attachment_type', 'App\Models\RetailStoreExpense'],
                    ['attachment_id', $expense->id],
                    ['payment_type', 'retail-store-expense'],
                ])->delete();

                $expense->delete();
            });

        GiftTransaction::where([['type', 'sale'], ['attachment_id', $invoice->id]])->delete();
        Transaction::where([['attachment_id', $invoice->id], ['attachment_type', 'App\Models\Invoice']])->delete();
        RetailStoreAccountEntry::where('invoice_id', $invoice->id)->delete();

        $invoice->delete();
        logActivity($invoice, $invoice->id, '', 'Delete', __('Shoes sold deleted'));
        
        return collect(['success' => __('pages.Shoes sold deleted')]);
    }

    public function forceDelete($id)
    {
        $invoice = Invoice::withTrashed()->with('accountBook.retailAccount')->find($id);

        if (!$invoice) {
            return collect(['error' => __('pages.Invoice not found')]);
        }

        DB::transaction(function () use ($invoice, $id) {
            $shouldIncrement = !$invoice->trashed();

            InvoiceEntry::withTrashed()->where('invoice_id', $id)
                ->each(function ($item) use ($shouldIncrement) {
                    if ($shouldIncrement && $inventory = Inventory::find($item->shoe_id)) {
                        $inventory->increment('count', $item->count);
                    }
                    $item->forceDelete();
                });

            RetailStoreExpense::withTrashed()->where('invoice_id', $invoice->id)
                ->each(function ($expense) {
                    Transaction::withTrashed()
                        ->where([
                            ['attachment_type', 'App\Models\RetailStoreExpense'],
                            ['attachment_id', $expense->id],
                            ['payment_type', 'retail-store-expense'],
                        ])->forceDelete();
                    $expense->forceDelete();
                });

            ReturnFromRetailEntry::where('invoice_id', $invoice->id)
                ->update([
                    'invoice_id' => 0,
                    'status' => 'pending',
                ]);

            GiftTransaction::withTrashed()->where(['type' => 'sale', 'attachment_id' => $id])->forceDelete();

            Transaction::withTrashed()->where([
                ['attachment_id', $id],
                ['attachment_type', 'App\Models\Invoice']
            ])->forceDelete();

            RetailStoreAccountEntry::withTrashed()->where('invoice_id', $id)->forceDelete();

            $invoice->forceDelete();
            logActivity($invoice, $invoice->id, '', 'Force Delete', __('Shoes sold permanently deleted'));
        });

        return collect(['success' => __('pages.Shoes sold permanently deleted'), 'accountBook' => $invoice->accountBook]);
    }

    public function restore($id)
    {
        $invoice = Invoice::withTrashed()
            ->with('accountBook.retailAccount')
            ->findOrFail($id);

        $invoice->restore();

        InvoiceEntry::withTrashed()->where('invoice_id', $invoice->id)
            ->each(function ($item) {
                $item->restore();
                Inventory::find($item->shoe_id)?->decrement('count', $item->count);
            });

        RetailStoreExpense::withTrashed()->where('invoice_id', $invoice->id)
            ->each(function ($expense) {
                Transaction::withTrashed()
                    ->where([
                        ['attachment_type', 'App\Models\RetailStoreExpense'],
                        ['attachment_id', $expense->id],
                        ['payment_type', 'retail-store-expense'],
                    ])->restore();
                $expense->restore();
            });

        GiftTransaction::withTrashed()->where(['type' => 'sale', 'attachment_id' => $invoice->id])->restore();
        Transaction::withTrashed()->where([['attachment_id', $invoice->id], ['attachment_type', 'App\Models\Invoice']])->restore();
        RetailStoreAccountEntry::withTrashed()->where('invoice_id', $invoice->id)->restore();

        logActivity($invoice, $invoice->id, '', 'Restore', __('Shoes sold restored'));

        return $invoice;
    }
}
