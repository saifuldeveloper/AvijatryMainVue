<?php

namespace App\Repositories;

use App\Models\BankAccount;
use App\Models\Cheque;
use App\Models\Gift;
use App\Models\GiftPurchase;
use App\Models\GiftSupplier;
use App\Models\GiftSupplierAccountEntry;
use App\Models\GiftTransaction;
use App\Models\Message;
use App\Models\Transaction;
use App\Models\TransactionHistory;
use App\Models\View\BankAccountEntry;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class GiftPurchaseRepository
{
    public function store(array $data)
    {


        $giftSupplier = GiftSupplier::find($data['gift_supplier_id']);
        $accountBook = $giftSupplier->getCurrentAccountBook();
        $createdAt = !empty($data['created_at']) ? Carbon::parse($data['created_at'])->setTimeFrom(now()) : now();


        $bank_account = BankAccount::find($data['payment_method']);


        $giftPurchase = new GiftPurchase;
        $giftPurchase->created_at = $createdAt;
        $giftPurchase->updated_at = $createdAt;
        $accountBook->giftPurchases()->save($giftPurchase);

        $count = 0;
        $total_amount = 0;
        $gift_name = [];

        foreach ($data['gift_purchases'] as $row) {
            if (empty($row['unit_price'])) {
                $row['unit_price'] = 0;
            }
            $giftTransaction = new GiftTransaction();
            $giftTransaction->fill($row);
            $giftTransaction->type = 'purchase';
            $total_price = $row['count'] * $row['unit_price'];
            $count += $row['count'];
            $total_amount += $total_price;
            $gift = Gift::find($row['gift_id']);
            $gift_name[] = $gift->name;
            $giftTransaction->created_at = $createdAt;
            $giftPurchase->giftTransactions()->save($giftTransaction);
        }

        if (isset($data['payment_amount']) && $data['payment_amount'] > 0) {

            if ($data['payment_method'] == 'cheque') {
                $chequeData = Cheque::issue(
                    $data['cheque_no'],
                    $accountBook,
                    $data['payment_amount'],
                    'gift-purchase',
                    $data['cheque_date'],
                    $createdAt,
                    $giftPurchase
                );
            } else {
                $description = isset($data['cheque_no']) ? 'চেক নং ' . $data['cheque_no'] : null;
                Transaction::createTransaction(
                    'gift-supplier',
                    $giftSupplier->id,
                    'expense',
                    $data['payment_method'],
                    $data['payment_amount'],
                    $description,
                    $createdAt,
                    $giftPurchase
                );
            }
        }
        // Create account entry
        $entry = new GiftSupplierAccountEntry;
        $entry->entry_id = $giftSupplier->id;
        $entry->entry_type = 0;
        $entry->account_book_id = $accountBook->id;
        $entry->gift_purchase_id = $giftPurchase->id;
        $entry->gift_name = json_encode($gift_name);
        $entry->count = $count;
        $entry->total_amount = $total_amount;
        $entry->created_at = $createdAt;

        if ($data['payment_method'] == 'cheque') {
            $entry->payment_amount = 0;
            $entry->payment_method = null;
            $entry->account_id = $chequeData->id;
            $entry->account_name = 'Cheque';
        } else if (isset($data['payment_amount']) && $data['payment_amount'] > 0) {
            $entry->payment_amount = $data['payment_amount'];
            $entry->payment_method = $data['payment_method'];
            $entry->account_id = $data['payment_method'];
            $entry->account_name = $bank_account ? $bank_account->bank : null;
        } else if ($data['cheque_no'] ?? false) {
            dd('Cheque entry without payment amount is not allowed');
        } else {
            $entry->payment_amount = 0;
            $entry->payment_method = null;
            $entry->account_id = null;
            $entry->account_name = null;
        }


        $entry->save();
        $giftPurchase->load('accountBook.account', 'giftTransactions.gift');
        // SMS send
        $message = Message::where('id', '1')->select('gift_supplier_message', 'gift_supplier_status')->first();
        if ($message && $message->gift_supplier_status == '1') {
            $formattedMessage = replaceMessagePlaceholders($message->gift_supplier_message, $giftSupplier->name, $giftPurchase->id, $total_amount, $data['payment_amount'] ?? 0, ($total_amount - ($data['payment_amount'] ?? 0)), $count);
            $formattedNumber = formatBangladeshiNumber($giftSupplier->mobile_no);
            Http::post(env('SMS_PROVIDER_URL'), [
                'api_key' => env('SMS_API_KEY'),
                'msg' => $formattedMessage,
                'to' => $formattedNumber,
            ]);
        }

        return $giftPurchase;
    }

    public function show(GiftPurchase $giftPurchase)
    {
        $giftPurchase->load('accountBook.giftSupplierAccount', 'giftTransactions.gift');
        return $giftPurchase;
    }

    // public function update(GiftPurchase $giftPurchase, array $data)
    // {
    //     $giftPurchase->load('accountBook');
    //     $survived = [];
    //     $formattedDate = null;
    //     if (isset($data['created_at'])) {
    //         $formattedDate = Carbon::createFromFormat('d-m-Y', $data['created_at'])->format('Y-m-d H:i:s');
    //         $giftPurchase->created_at = $formattedDate;
    //         $giftPurchase->save();
    //     }

    //     if ($giftPurchase->accountBook->open && $giftPurchase->accountBook->account_id != $data['gift_supplier_id']) {
    //         $giftSupplier = GiftSupplier::find($data['gift_supplier_id']);
    //         $accountBook = $giftSupplier->getCurrentAccountBook();
    //         $giftPurchase->accountBook()->associate($accountBook);
    //         $giftPurchase->save();
    //     }

    //     foreach ($data['gift_purchases'] as $row) {
    //         if (isset($row['id'])) {
    //             $giftTransaction = GiftTransaction::find($row['id']);
    //             $giftTransaction->fill($row);
    //             if ($formattedDate) {
    //                 $giftTransaction->created_at = $formattedDate;
    //             }
    //             $giftTransaction->save();
    //         } else {
    //             $giftTransaction = new GiftTransaction();
    //             $giftTransaction->fill($row);
    //             $giftTransaction->type = 'purchase';
    //             if ($formattedDate) {
    //                 $giftTransaction->created_at = $formattedDate;
    //             }
    //             $giftPurchase->giftTransactions()->save($giftTransaction);
    //         }
    //         $survived[] = $giftTransaction->id;
    //     }

    //     $giftTransactions = $giftPurchase->giftTransactions()->get();
    //     foreach ($giftTransactions as $giftTransaction) {
    //         if (!in_array($giftTransaction->id, $survived)) {
    //             $giftTransaction->delete();
    //         }
    //     }

    //     $giftTransactions = $giftPurchase->giftTransactions()->get();
    //     $count = 0;
    //     $unit_price = 0;
    //     foreach ($giftTransactions as $item) {
    //         $unit_price += $item->unit_price * $item->count;
    //         $count += $item->count;
    //     }

    //     $entry = GiftSupplierAccountEntry::where('gift_purchase_id', $giftPurchase->id)->first();
    //     if ($entry) {
    //         $entry->count = $count;
    //         $entry->total_amount = $unit_price;
    //         if ($formattedDate) {
    //             $entry->created_at = $formattedDate;
    //         }
    //         $entry->save();
    //     }

    //     $giftPurchase->load('accountBook.account', 'giftTransactions.gift');
    //     return $giftPurchase;
    // }


    public function update(GiftPurchase $giftPurchase, array $data)
    {
        $giftPurchase->load('accountBook');
        $survived = [];
        $formattedDate = null;

        // 1. Process and update date if it has been changed
        if (isset($data['created_at'])) {
            $formattedDate = Carbon::parse($data['created_at'])->format('Y-m-d H:i:s');
            $giftPurchase->created_at = $formattedDate;
            $giftPurchase->save();
        }

        if ($giftPurchase->accountBook && $giftPurchase->accountBook->open && $giftPurchase->accountBook->account_id != $data['gift_supplier_id']) {
            $giftSupplier = GiftSupplier::find($data['gift_supplier_id']);
            $accountBook = $giftSupplier->getCurrentAccountBook();
            $giftPurchase->accountBook()->associate($accountBook);
            $giftPurchase->save();
        }

        // 2. Loop to update item or gift transaction table
        foreach ($data['gift_purchases'] as $row) {
            if (isset($row['id'])) {
                $giftTransaction = GiftTransaction::find($row['id']);
                $giftTransaction->fill($row);
                if ($formattedDate) {
                    $giftTransaction->created_at = $formattedDate;
                }
                $giftTransaction->save();
            } else {
                $giftTransaction = new GiftTransaction();
                $giftTransaction->fill($row);
                $giftTransaction->type = 'purchase';
                if ($formattedDate) {
                    $giftTransaction->created_at = $formattedDate;
                }
                $giftPurchase->giftTransactions()->save($giftTransaction);
            }
            $survived[] = $giftTransaction->id;
        }

        $giftTransactions = $giftPurchase->giftTransactions()->get();
        foreach ($giftTransactions as $giftTransaction) {
            if (!in_array($giftTransaction->id, $survived)) {
                $giftTransaction->delete();
            }
        }

        // 3. Calculate the total bill amount
        $giftTransactions = $giftPurchase->giftTransactions()->get();
        $count = 0;
        $unit_price = 0;
        foreach ($giftTransactions as $item) {
            $unit_price += $item->unit_price * $item->count;
            $count += $item->count;
        }

        // 4. Clean and update account entry, bank account entry, transaction, and history
        $entry = GiftSupplierAccountEntry::where('gift_purchase_id', $giftPurchase->id)->first();
        if ($entry) {
            $entry->count = $count;
            $entry->total_amount = $unit_price;

            if (isset($data['payment_method'])) {

                // --- a) Track old transaction IDs (including soft-deleted records) ---
                $oldTransactionIds = Transaction::withTrashed()
                    ->where('attachment_type', GiftPurchase::class)
                    ->where('attachment_id', $giftPurchase->id)
                    ->pluck('id')
                    ->toArray();

                if (!empty($oldTransactionIds)) {
                    // 1. Permanently delete (forceDelete) old bank data from Bank Account Entries table
                    BankAccountEntry::withTrashed()
                        ->whereIn('account_id', $oldTransactionIds)
                        ->orWhere('account_id', $giftPurchase->id) // Also check by purchase ID for safety
                        ->forceDelete();

                    // 2. Delete data from Transaction History table
                    TransactionHistory::withTrashed()
                        ->whereIn('transaction_id_one', $oldTransactionIds)
                        ->orWhereIn('transaction_id_two', $oldTransactionIds)
                        ->forceDelete();

                    // 3. Permanently delete from the main Transaction table
                    Transaction::withTrashed()->whereIn('id', $oldTransactionIds)->forceDelete();
                }

                // 4. Clean and update account entry, bank account entry, transaction, and history
                $entry = GiftSupplierAccountEntry::where('gift_purchase_id', $giftPurchase->id)->first();
                if ($entry) {
                    $entry->count = $count;
                    $entry->total_amount = $unit_price;

                    if (isset($data['payment_method'])) {
                        BankAccountEntry::withTrashed()
                            ->where('account_type', 'gift-supplier')
                            ->where('to_account_book_id', $giftPurchase->account_book_id)
                            ->forceDelete();

                        // 2. Find old transaction IDs (including soft-deleted)
                        $oldTransactionIds = Transaction::withTrashed()
                            ->where('attachment_type', GiftPurchase::class)
                            ->where('attachment_id', $giftPurchase->id)
                            ->pluck('id')
                            ->toArray();

                        if (!empty($oldTransactionIds)) {
                            // 3. Delete data from Transaction History table
                            TransactionHistory::withTrashed()
                                ->whereIn('transaction_id_one', $oldTransactionIds)
                                ->orWhereIn('transaction_id_two', $oldTransactionIds)
                                ->forceDelete();

                            // 4. Permanently delete from the main Transaction table
                            Transaction::withTrashed()->whereIn('id', $oldTransactionIds)->forceDelete();
                        }

                        // 5. Delete any existing cheques as well
                        Cheque::where('attachment_type', GiftPurchase::class)
                            ->where('attachment_id', $giftPurchase->id)
                            ->forceDelete();


                        // --- b) Create new bank or transaction entries ---
                        if (isset($data['payment_amount']) && $data['payment_amount'] > 0) {
                            if ($data['payment_method'] == 'cheque') {
                                $chequeData = Cheque::issue(
                                    $data['cheque_no'],
                                    $giftPurchase->accountBook,
                                    $data['payment_amount'],
                                    'gift-purchase',
                                    $data['cheque_date'],
                                    $formattedDate ?? $giftPurchase->created_at,
                                    $giftPurchase
                                );

                                $entry->payment_amount = 0;
                                $entry->payment_method = null;
                                $entry->account_id = $chequeData->id;
                                $entry->account_name = 'Cheque';
                            } else {
                                $description = isset($data['cheque_no']) ? 'Cheque No ' . $data['cheque_no'] : null;

                                // New bank transaction entry (creates a single fresh bank_account_entries record)
                                Transaction::createTransaction(
                                    'gift-supplier',
                                    $data['gift_supplier_id'],
                                    'expense',
                                    $data['payment_method'],
                                    $data['payment_amount'],
                                    $description,
                                    $formattedDate ?? $giftPurchase->created_at,
                                    $giftPurchase
                                );
                                $bank_account = BankAccount::find($data['payment_method']);
                                $entry->payment_amount = $data['payment_amount'];
                                $entry->payment_method = $data['payment_method'];
                                $entry->account_id = $data['payment_method'];
                                $entry->account_name = $bank_account ? $bank_account->bank : null;
                            }
                        } else {
                            // If payment amount is set to 0 or empty, clear all bank info
                            $entry->payment_amount = 0;
                            $entry->payment_method = null;
                            $entry->account_id = null;
                            $entry->account_name = null;
                        }
                    }

                    // If the date was changed, sync the date in the bank ledger
                    if ($formattedDate) {
                        $entry->created_at = $formattedDate;
                        BankAccountEntry::where('account_type', 'gift-supplier')
                            ->where('to_account_book_id', $giftPurchase->account_book_id)
                            ->update(['created_at' => $formattedDate, 'updated_at' => $formattedDate]);
                    }
                    $entry->save();
                }
            }

            // If the date was changed, update the date to maintain bank ledger balance continuity
            if ($formattedDate) {
                $entry->created_at = $formattedDate;
                BankAccountEntry::where('account_type', 'gift-supplier')
                    ->where('to_account_book_id', $giftPurchase->account_book_id)
                    ->update(['created_at' => $formattedDate, 'updated_at' => $formattedDate]);
            }

            $entry->save();
        }
        $giftPurchase->load('accountBook.account', 'giftTransactions.gift');
        return $giftPurchase;
    }

    public function destroy(GiftPurchase $giftPurchase)
    {
        logActivity($giftPurchase, $giftPurchase->id, 'gift-purchase.show', 'SoftDelete', __('Gift purchase deleted'));
        $giftPurchase->delete();
        return collect(['success' => __('pages.Gift purchase deleted')]);
    }

    public function forceDelete($id)
    {
        $giftPurchase = GiftPurchase::withTrashed()
            ->with([
                'giftTransactions',
                'accountBook',
            ])
            ->findOrFail($id);

        DB::transaction(function () use ($giftPurchase) {
            $giftPurchase->giftTransactions()->forceDelete();
            GiftSupplierAccountEntry::where('gift_purchase_id', $giftPurchase->id)->forceDelete();
            $giftPurchase->forceDelete();
        });

        return collect(['success' => 'Gift purchase permanently deleted successfully!']);
    }

    public function restore($id)
    {
        $purchase = GiftPurchase::withTrashed()->find($id);
        if ($purchase) {
            logActivity($purchase, $purchase->id, 'gift-purchase.show', 'Restore', __('Gift Purchase restored'));
            $purchase->restore();
            return $purchase;
        }
        return null;
    }
}
