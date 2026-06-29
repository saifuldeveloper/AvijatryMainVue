<?php

namespace App\Repositories;

use App\Models\AccountBook;
use App\Models\Factory;
use App\Models\FactoryAccountEntry;
use App\Enums\FactoryEntryType;
use App\Models\Transaction;
use App\Models\Cheque;
use App\Models\GiftSupplierAccountEntry;
use App\Enums\GiftSupplierEntryType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AccountBookRepository
{
    public function show(AccountBook $accountBook)
    {
        $accountBook->load('factoryAccount');
        return $accountBook;
    }

    public function closing(array $data, AccountBook $accountBook)
    {
        $createdAt = Carbon::now()->format('Y-m-d H:i:s');
        $requestCreatedDate = Carbon::parse($data['closing_date'] ?? now())->setTimeFrom(Carbon::now());
        $newBook = null;

        switch ($accountBook->account_type) {
            case 'factory':
                $balance = $accountBook->getCalculatedBalance(); // Use the calculated balance instead of the stored balance
                $accountBook->fill($data);
                $payments = $data['payment'] ?? null;
                $payment_sum = 0;

                if ($payments !== null) {
                    $payment_amounts = array_column($payments, 'amount');
                    $payment_sum = array_sum($payment_amounts);
                }

                $cheques = $data['cheque'] ?? null;
                $cheque_sum = 0;
                if (!empty($cheques) && is_array($cheques)) {
                    $cheque_amounts = array_column($cheques, 'amount');
                    $cheque_sum = array_sum($cheque_amounts);
                }

                if (($payment_sum + $cheque_sum) > 0) {
                    $accountEntries = new FactoryAccountEntry;
                    $accountEntries->account_book_id = $accountBook->id;
                    $accountEntries->entry_type = FactoryEntryType::Payment;
                    $accountEntries->entry_id = $accountBook->account_id;
                    $accountEntries->total_amount = $payment_sum + $cheque_sum;
                    $accountEntries->closing_id = $accountBook->id;
                    $accountEntries->description = $data['closing_type'] ?? 'Closing';
                    $accountEntries->save();
                }

                $accountBook->closing_balance = $balance - (float)($data['commission'] ?? 0) - (float)($data['staff'] ?? 0) - (float)($data['staff_shoe_amount'] ?? 0) - $payment_sum - $cheque_sum;
                $accountBook->open = false;
                $accountBook->closing_date = Carbon::parse($data['closing_date'] ?? now())->format('Y-m-d H:i:s');
                $accountBook->closing_type = $data['closing_type'] ?? null;
                $accountBook->commission_percentage = $data['commission_percentage'] ?? 0;
                $accountBook->commission = $data['commission'] ?? 0;
                $accountBook->staff_percentage = $data['staff_percentage'] ?? 0;
                $accountBook->staff = $data['staff'] ?? 0;
                $accountBook->staff_shoe_amount = $data['staff_shoe_amount'] ?? 0;
                $accountBook->save();

                $newBook = new AccountBook();
                $newBook->account_type = $accountBook->account_type;
                $newBook->account_id = $accountBook->account_id;
                $newBook->created_at = $requestCreatedDate;
                $newBook->save();

                logActivity($accountBook, $accountBook->id, 'account-book.show', 'Update', __('Factory account book closed'));
                break;

            case 'gift-supplier':
                $balance = $accountBook->getCalculatedBalance(); // Use calculated balance
                $accountBook->fill($data);
                $payments = $data['payment'] ?? null;
                $payment_sum = 0;

                if ($payments !== null) {
                    $payment_amounts = array_column($payments, 'amount');
                    $payment_sum = array_sum($payment_amounts);
                    foreach ($payments as $payment) {
                        if (empty($payment['amount']) || empty($payment['method'])) {
                            continue;
                        }
                        Transaction::createTransaction(
                            $accountBook->account_type,
                            $accountBook->account_id,
                            'expense',
                            $payment['method'],
                            $payment['amount'],
                            '',
                            $createdAt,
                            null,
                            $accountBook->id
                        );
                    }
                }

                $cheques = $data['cheque'] ?? null;
                $cheque_sum = 0;
                if ($cheques !== null && is_array($cheques)) {
                    $cheque_amounts = array_column($cheques, 'amount');
                    $cheque_sum = array_sum($cheque_amounts);
                    foreach ($cheques as $cheque_entry) {
                        if (empty($cheque_entry['id']) || empty($cheque_entry['amount'])) {
                            continue;
                        }
                        Cheque::issue(
                            $cheque_entry['id'],
                            $accountBook,
                            $cheque_entry['amount'],
                            'gift-supplier',
                            $cheque_entry['due_date'],
                            $createdAt,
                            null,
                            $accountBook->id
                        );
                    }
                }

                if (($payment_sum + $cheque_sum) > 0) {
                    $accountEntries = new GiftSupplierAccountEntry;
                    $accountEntries->account_book_id = $accountBook->id;
                    $accountEntries->entry_type = GiftSupplierEntryType::ClosingPayment;
                    $accountEntries->entry_id = $accountBook->account_id;
                    $accountEntries->payment_amount = $payment_sum + $cheque_sum;
                    $accountEntries->closing_id = $accountBook->id;
                    $accountEntries->description = $data['closing_type'] ?? 'Closing';
                    $accountEntries->save();
                }

                $accountBook->closing_balance = $balance - (float)($data['commission'] ?? 0) - (float)($data['staff'] ?? 0) - (float)($data['staff_shoe_amount'] ?? 0) - $payment_sum - $cheque_sum;
                $accountBook->open = false;
                $accountBook->closing_date = Carbon::parse($data['closing_date'] ?? now())->format('Y-m-d H:i:s');
                $accountBook->closing_type = $data['closing_type'] ?? null;
                $accountBook->commission_percentage = $data['commission_percentage'] ?? 0;
                $accountBook->commission = $data['commission'] ?? 0;
                $accountBook->staff_percentage = $data['staff_percentage'] ?? 0;
                $accountBook->staff = $data['staff'] ?? 0;
                $accountBook->staff_shoe_amount = $data['staff_shoe_amount'] ?? 0;
                $accountBook->save();

                $newBook = new AccountBook();
                $newBook->account_type = $accountBook->account_type;
                $newBook->account_id = $accountBook->account_id;
                $newBook->created_at = $requestCreatedDate;
                $newBook->save();

                logActivity($accountBook, $accountBook->id, 'account-book.show', 'Update', __('Gift Supplier account book closed'));
                break;

            case 'bank-account':
                $accountBook->open = false;
                $accountBook->closing_date = $requestCreatedDate;
                $accountBook->closing_type = $data['closing_type'] ?? null;
                $accountBook->staff = 0;
                $accountBook->save();

                $newBook = new AccountBook();
                $newBook->account_type = $accountBook->account_type;
                $newBook->balance_carry_forward = $data['balance_carry_forward'] ?? 0;
                if (($data['balance_carry_forward'] ?? 0) != 0) {
                    $newBook->previous_balance = $data['closing_balance'] ?? 0;
                }
                $newBook->created_at = $requestCreatedDate;
                if ($accountBook->account) {
                    $accountBook->account->accountBooks()->save($newBook);
                }
                break;
        }
        return $newBook;
    }
}
