<?php

namespace App\Repositories;

use App\Models\AccountBook;
use App\Models\Cheque;
use App\Models\Commitment;
use App\Models\EmployeeAccountEntry;
use App\Models\EmployeeSalary;
use App\Models\Factory;
use App\Models\GiftSupplierAccountEntry;
use App\Models\ManualClosing;
use App\Models\SalaryHistory;
use App\Models\Transaction;
use App\Models\FactoryAccountEntry;
use App\Models\View\RetailStoreAccountEntry;
use App\Enums\FactoryEntryType;
use App\Enums\GiftSupplierEntryType;
use App\Enums\RetailStoreEntryType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AccountBookRepository
{
    public function show(AccountBook $accountBook)
    {
        $accountBook->load('retailAccount', 'factoryAccount', 'giftSupplierAccount');
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
                if (!empty($cheques) && is_array($cheques)) {
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
                            'factory',
                            $cheque_entry['due_date'],
                            $createdAt,
                            null,
                            $accountBook->id
                        );
                    }
                }

                $accountEntries = new FactoryAccountEntry;
                $accountEntries->account_book_id = $accountBook->id;
                $accountEntries->entry_type = FactoryEntryType::Payment; // 2
                $accountEntries->entry_id = $accountBook->id;
                $accountEntries->total_amount = $payment_sum + $cheque_sum;
                $accountEntries->closing_id = $accountBook->id;
                $accountEntries->save();

                $accountBook->closing_balance = $balance - $accountBook->commission - $accountBook->staff - $payment_sum - $cheque_sum;
                $accountBook->open = false;
                $accountBook->closing_date = dateToDb($data['closing_date']) ?? $requestCreatedDate;
                $accountBook->closing_type = $data['closing_type'] ?? null;
                $accountBook->commission_percentage = $data['comminsion_percentage'] ?? 0;
                $accountBook->staff_percentage = $data['staff_comminsion_percentage'] ?? 0;
                $accountBook->staff_shoe_amount = $data['staff_shoe_amount'] ?? 0;
                $accountBook->save();

                $newBook = new AccountBook();
                $newBook->account_type = $accountBook->account_type;
                $newBook->created_at = $requestCreatedDate;
                if ($accountBook->account) {
                    $accountBook->account->accountBooks()->save($newBook);
                }

                if (($data['closing_type'] ?? null) == "manual") {
                    $manualClosing = new ManualClosing();
                    $manualClosing->account_book_id = $accountBook->id;
                    $manualClosing->account_type = 'factory';
                    $manualClosing->closing_data = json_encode($data);
                    $manualClosing->save();
                }
                break;

            case 'retail-store':
                $balance = $accountBook->getCalculatedBalance();
                $accountBook->fill($data);
                $payments = $data['payment'] ?? [];
                $payment_sum = 0;
                foreach ($payments as $payment) {
                    if (empty($payment['amount'])) {
                        continue;
                    }
                    Transaction::createTransaction('retail-closing', $accountBook->account_id, 'income', $payment['method'], $payment['amount'], '', $requestCreatedDate, null, $accountBook->id);
                    $payment_sum += $payment['amount'];
                }

                if (isset($data['commitment']) && is_array($data['commitment'])) {
                    foreach ($data['commitment'] as $commitment) {
                        if (empty($commitment['date']) || empty($commitment['amount'])) {
                            continue;
                        }
                        $commitmentData = new Commitment();
                        $commitmentData->account_book_id = $accountBook->id;
                        $commitmentData->commitment_date = $commitment['date'];
                        $commitmentData->amount = $commitment['amount'];
                        $commitmentData->description = 'retail-closing';
                        $commitmentData->attechment_id = $accountBook->id;
                        $commitmentData->attechment_type = 'retail-closing';
                        $commitmentData->created_at = $requestCreatedDate;
                        $commitmentData->save();
                    }
                }

                if (($data['closing_type'] ?? null) == "manual") {
                    $accountBook->closing_balance = $data['input_total_remaining'] ?? 0;
                } else {
                    $accountBook->closing_balance = $balance - $accountBook->commission - $accountBook->staff - $payment_sum;
                }

                $entry = new RetailStoreAccountEntry;
                $entry->entry_id = $accountBook->id;
                $entry->entry_type = '3'; // Payment
                $entry->account_book_id = $accountBook->id;
                $entry->paid_amount = $payment_sum;
                $entry->closing_id = $accountBook->id;
                $entry->created_at = $requestCreatedDate;
                $entry->save();

                $accountBook->open = false;
                $accountBook->closing_date = dateToDb($data['closing_date']) ?? $requestCreatedDate;
                $accountBook->closing_type = $data['closing_type'] ?? null;
                $accountBook->commission_percentage = $data['commission_percentage'] ?? 0;
                $accountBook->commission = $data['commission'] ?? 0;
                $accountBook->staff_percentage = $data['staff_commission_percentage'] ?? 0;
                $accountBook->staff = $data['staff'] ?? 0;
                $accountBook->updated_at = $requestCreatedDate;
                $accountBook->save();

                $newBook = new AccountBook();
                $newBook->account_type = $accountBook->account_type;
                $newBook->balance_carry_forward = $data['balance_carry_forward'] ?? 0;
                if (($data['balance_carry_forward'] ?? 0) != 0) {
                    $newBook->previous_balance = $accountBook->closing_balance;
                }
                $newBook->created_at = $requestCreatedDate;

                if ($accountBook->retailAccount) {
                    $accountBook->retailAccount->accountBooks()->save($newBook);
                }

                if (($data['closing_type'] ?? null) == "manual") {
                    $manualClosing = new ManualClosing();
                    $manualClosing->account_book_id = $accountBook->id;
                    $manualClosing->account_type = 'retail-store';
                    $manualClosing->closing_data = json_encode($data);
                    $manualClosing->created_at = $requestCreatedDate;
                    $manualClosing->save();
                }
                break;

            case 'gift-supplier':
                $balance = $accountBook->getCalculatedBalance();
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
                if ($cheques !== null) {
                    $cheque_amounts = array_column($cheques, 'amount');
                    $cheque_sum = array_sum($cheque_amounts);
                    foreach ($cheques as $cheque_entry) {
                        if (empty($cheque_entry['id'])) {
                            continue;
                        }
                        Cheque::issue($cheque_entry['id'], $accountBook, $cheque_entry['amount'], 'factory', $cheque_entry['due_date'], $createdAt, null, $accountBook->id);
                    }
                }

                $accountEntries = new GiftSupplierAccountEntry;
                $accountEntries->account_book_id = $accountBook->id;
                $accountEntries->entry_type = GiftSupplierEntryType::ClosingPayment; // 5
                $accountEntries->entry_id = $accountBook->id;
                $accountEntries->total_amount = $payment_sum + $cheque_sum;
                $accountEntries->closing_id = $accountBook->id;
                $accountEntries->save();

                $accountBook->open = false;
                $accountBook->closing_date = dateToDb($data['closing_date']) ?? $requestCreatedDate;
                $accountBook->commission_percentage = $data['comminsion_percentage'] ?? null;
                $accountBook->staff_percentage = $data['staff_comminsion_percentage'] ?? null;
                $accountBook->save();

                $newBook = new AccountBook();
                $newBook->account_type = $accountBook->account_type;
                $newBook->previous_balance = $accountBook->closing_balance;
                $newBook->created_at = $requestCreatedDate;
                if ($accountBook->account) {
                    $accountBook->account->accountBooks()->save($newBook);
                }
                break;

            case 'employee':
                $balance = $accountBook->getCalculatedBalance();
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
                            'employee-closing',
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
                if ($cheques !== null) {
                    $cheque_amounts = array_column($cheques, 'amount');
                    $cheque_sum = array_sum($cheque_amounts);
                    foreach ($cheques as $cheque_entry) {
                        if (empty($cheque_entry['id'])) {
                            continue;
                        }
                        Cheque::issue($cheque_entry['id'], $accountBook, $cheque_entry['amount'], 'factory', $cheque_entry['due_date'], $createdAt, null, $accountBook->id);
                    }
                }

                if (($data['closing_type'] ?? null) == "manual") {
                    $accountBook->closing_balance = $data['input_total_remaining'] ?? 0;
                } else {
                    $accountBook->closing_balance = ($data['subtotal'] ?? 0) - $payment_sum;
                }

                $accountEntries = new EmployeeAccountEntry();
                $accountEntries->account_book_id = $accountBook->id;
                $accountEntries->entry_type = 0;
                $accountEntries->entry_id = $accountBook->id;
                $accountEntries->total_amount = $payment_sum + $cheque_sum;
                $accountEntries->account_id = $accountBook->account_id;
                $accountEntries->payment_type = 'employee-closing';
                $accountEntries->account_type = "expense";
                $accountEntries->closing_id = $accountBook->id;
                $accountEntries->created_at = $requestCreatedDate;
                $accountEntries->save();

                $accountBook->open = false;
                $accountBook->closing_date = $requestCreatedDate;
                $accountBook->closing_type = $data['closing_type'] ?? null;
                $accountBook->staff = $data['factory_staff_bonus'] ?? 0;
                $accountBook->save();

                $newBook = new AccountBook();
                $newBook->account_type = $accountBook->account_type;
                $newBook->balance_carry_forward = $data['balance_carry_forward'] ?? 0;
                $newBook->previous_balance = ($data['balance_carry_forward'] ?? 0) != 0 ? $accountBook->closing_balance : 0;
                $newBook->created_at = $requestCreatedDate;
                $newBook->account_id = $accountBook->account_id;
                $newBook->save();

                $salaryBook = EmployeeSalary::where('employee_id', $accountBook->account_id)
                    ->where('account_book_id', $accountBook->id)
                    ->first();

                if ($salaryBook) {
                    $salaryBook->previous_debt = $data['previous_debt_balance'] ?? 0;
                    $salaryBook->previous_creadit = $data['previous_creadit_balance'] ?? 0;
                    $salaryBook->save();

                    $newSalaryBook = $salaryBook->replicate(['previous_debt', 'previous_creadit']);
                    $newSalaryBook->account_book_id = $newBook->id;
                    $newSalaryBook->salary_start_date = $requestCreatedDate;
                    $newSalaryBook->created_at = $requestCreatedDate;
                    $newSalaryBook->updated_at = $requestCreatedDate;
                    $newSalaryBook->save();

                    $old = $salaryBook->salary ?? 0;
                    $new = $newSalaryBook->salary ?? 0;

                    $effectiveDate = Carbon::parse($requestCreatedDate)->addDay()->format('Y-m-d');
                    SalaryHistory::create([
                        'employee_id' => $newSalaryBook->employee_id,
                        'account_book_id' => $newSalaryBook->account_book_id,
                        'old_salary' => $old,
                        'new_salary' => $new,
                        'change' => $new - $old,
                        'change_type' => $new > $old ? 'increment' : ($new < $old ? 'decrement' : 'no_change'),
                        'remarks' => $data['remarks'] ?? null,
                        'changed_by' => auth()->id() ?? 1,
                        'effective_date' => $effectiveDate,
                    ]);
                }
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

    public function forwardBalance(AccountBook $accountBook)
    {
        if ($accountBook->account_type !== 'retail-store' || $accountBook->open) {
            return null;
        }

        $currentBook = AccountBook::where('account_type', $accountBook->account_type)
            ->where('account_id', $accountBook->account_id)
            ->where('id', '!=', $accountBook->id)
            ->where('open', true)
            ->latest('id')
            ->first();

        if (!$currentBook) {
            return null;
        }

        return DB::transaction(function () use ($accountBook, $currentBook) {
            $isForwarding = ($accountBook->balance_carry_forward == 0);
            $accountBook->balance_carry_forward = $isForwarding ? 1 : 0;

            $currentBook->previous_balance = $isForwarding ? $accountBook->getDescriptionBalance() : 0;
            
            $accountBook->save();
            $currentBook->save();

            return $accountBook;
        });
    }
}
