<?php

namespace App\Repositories;

use App\Enums\RetailStoreEntryType;
use App\Models\AccountBook;
use App\Models\RetailStoreExpense;
use App\Models\Transaction;
use App\Models\View\RetailStoreAccountEntry;

class RetailStoreExpenseRepository
{
    public function store(array $data)
    {
        $accountBook = AccountBook::findOrFail($data['account_book_id']);

        // Create RetailStoreExpense
        $retailStoreExpense = RetailStoreExpense::create($data);

        // Create RetailStoreAccountEntry
        $entry = new RetailStoreAccountEntry();
        $entry->account_book_id = $data['account_book_id'];
        $entry->entry_id = $accountBook->account_id;
        $entry->entry_type = RetailStoreEntryType::Expence->value;
        $entry->expense_id = $retailStoreExpense->id;
        $entry->amount = $data['total_amount'] ?? $data['amount'] ?? 0;
        $entry->expense_description = $data['description'];
        $entry->expense_amount = $data['amount'];
        $entry->save();

        logActivity($retailStoreExpense, $retailStoreExpense->id, 'retail-store-expense.show', 'Create', __('Other expenses saved.'));

        return $retailStoreExpense;
    }

    public function delete($id)
    {
        $retailStoreExpense = RetailStoreExpense::findOrFail($id);

        // Soft delete related transactions
        Transaction::where('payment_type', 'retail-store-expense')
            ->where('attachment_id', $id)
            ->where('attachment_type', RetailStoreExpense::class)
            ->where('transaction_type', 'income')
            ->delete();

        Transaction::where('payment_type', 'retail-store-expense')
            ->where('attachment_id', $id)
            ->where('attachment_type', RetailStoreExpense::class)
            ->where('transaction_type', 'expense')
            ->delete();

        // Soft delete related entries
        RetailStoreAccountEntry::where('expense_id', $id)->delete();

        logActivity($retailStoreExpense, $retailStoreExpense->id, 'retail-store-expense.show', 'SoftDelete', __('Other expenses deleted'));

        // Soft delete the expense
        $retailStoreExpense->delete();

        return collect(['success' => __('pages.Other expenses deleted') ?? 'Other expenses deleted successfully']);
    }

    public function restore($id)
    {
        $expense = RetailStoreExpense::onlyTrashed()->findOrFail($id);
        $expense->restore();

        Transaction::onlyTrashed()
            ->where('payment_type', 'retail-store-expense')
            ->where('attachment_id', $id)
            ->where('attachment_type', RetailStoreExpense::class)
            ->where('transaction_type', 'income')
            ->restore();

        Transaction::onlyTrashed()
            ->where('payment_type', 'retail-store-expense')
            ->where('attachment_id', $id)
            ->where('attachment_type', RetailStoreExpense::class)
            ->where('transaction_type', 'expense')
            ->restore();

        $entry = RetailStoreAccountEntry::onlyTrashed()->where('expense_id', $id)->first();
        if ($entry) {
            $entry->restore();
        }

        logActivity($expense, $expense->id, 'retail-store-expense.show', 'Restore', __('Other expenses restored'));

        return collect(['success' => __('pages.Other expenses restored') ?? 'Other expenses restored successfully']);
    }

    public function forceDelete($id)
    {
        $expense = RetailStoreExpense::onlyTrashed()->findOrFail($id);
        $expense->forceDelete();

        Transaction::onlyTrashed()
            ->where('payment_type', 'retail-store-expense')
            ->where('attachment_id', $id)
            ->where('attachment_type', RetailStoreExpense::class)
            ->where('transaction_type', 'income')
            ->forceDelete();

        Transaction::onlyTrashed()
            ->where('payment_type', 'retail-store-expense')
            ->where('attachment_id', $id)
            ->where('attachment_type', RetailStoreExpense::class)
            ->where('transaction_type', 'expense')
            ->forceDelete();

        $entry = RetailStoreAccountEntry::onlyTrashed()->where('expense_id', $id)->first();
        if ($entry) {
            $entry->forceDelete();
        }

        logActivity($expense, $expense->id, 'retail-store-expense.show', 'ForceDelete', __('Other expenses force deleted'));

        return collect(['success' => __('pages.Other expenses force deleted') ?? 'Other expenses force deleted successfully']);
    }
}
