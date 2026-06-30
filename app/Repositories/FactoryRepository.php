<?php

namespace App\Repositories;

use App\Models\Factory;
use App\Models\Account;
use App\Models\AccountBook;
use App\Models\FactoryAccountEntry;
use App\Enums\FactoryEntryType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FactoryRepository
{
    public function getBaseQuery(Request $request)
    {
        return Factory::query()
            ->when($request->name, function ($q, $name) {
                $q->where('name', 'like', '%' . $name . '%');
            })
            ->when($request->address, function ($q, $address) {
                $q->where('address', 'like', '%' . $address . '%');
            })
            ->when($request->mobile_no, function ($q, $mobile_no) {
                $q->where('mobile_no', 'like', '%' . $mobile_no . '%');
            });
    }

    public function getTrashedFactories()
    {
        return Factory::with('deletedBy')->onlyTrashed()->get();
    }

    public function getAllFactories()
    {
        return Factory::all();
    }

    public function getActiveFactoriesSearch($searchTerm)
    {
        return Factory::when($searchTerm, function ($query, $searchTerm) {
            return $query->where('name', 'like', '%' . $searchTerm . '%')
                ->orWhere('mobile_no', 'like', '%' . $searchTerm . '%');
        })->latest()->get();
    }

    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {
            $factory = new Factory;
            $factory->fill($data);
            $factory->save();

            $account = new Account;
            $account->id = $factory->id;
            $account->type = 'factory';
            $account->name = $factory->name;
            $account->save();

            $account_book = new AccountBook;
            $account_book->account_id = $factory->id;
            $account_book->account_type = 'factory';
            $account_book->save();

            return $factory;
        });
    }

    public function show($id)
    {
        $factory = Factory::with('accountBooks')->findOrFail($id);
        $factory->load('entries');
        return $factory;
    }

    public function update(Factory $factory, array $data)
    {
        $factory->fill($data);
        $factory->save();

        Account::where('id', $factory->id)->where('type', 'factory')->update([
            'name' => $factory->name,
        ]);

        return $factory;
    }

    public function destroy(Factory $factory)
    {
        logActivity($factory, $factory->id, 'factory.index', 'SoftDelete', __('Factory owner information deleted'), false);
        $factory->delete();
        return collect(['success' => __('pages.Factory owner information deleted')]);
    }

    public function forceDelete($id)
    {
        $factory = Factory::withTrashed()->findOrFail($id);
        logActivity($factory, $factory->id, 'factory.index', 'Delete', __('Factory owner information permanently deleted'));
        $factory->forceDelete();
        return collect(['success' => __('pages.Factory owner information permanently deleted')]);
    }

    public function restore($id)
    {
        $factory = Factory::withTrashed()->findOrFail($id);
        logActivity($factory, $factory->id, 'factory.show', 'Restore', __('Factory owner information restored'));
        $factory->restore();
        return collect(['success' => __('pages.Factory owner information restored')]);
    }

    public function closing(Factory $factory, array $data, $payments = [], $cheques = [])
    {
        return DB::transaction(function () use ($factory, $data, $payments, $cheques) {
            $accountBook = $factory->getCurrentAccountBook();
            if (!$accountBook) {
                $accountBook = new AccountBook;
                $accountBook->account_id = $factory->id;
                $accountBook->account_type = 'factory';
                $accountBook->save();
            }

            $balance = $accountBook->getCalculatedBalance();
            $createdAt = Carbon::now();

            $accountBook->fill($data);

            $payment_sum = 0;
            if ($payments && is_array($payments)) {
                foreach ($payments as $payment) {
                    if (empty($payment['amount'])) {
                        continue;
                    }
                    $payment_sum += $payment['amount'];
                }
            }

            $cheque_sum = 0;
            if ($cheques && is_array($cheques)) {
                foreach ($cheques as $cheque_entry) {
                    if (empty($cheque_entry['amount'])) {
                        continue;
                    }
                    $cheque_sum += $cheque_entry['amount'];
                }
            }

            if (($payment_sum + $cheque_sum) > 0) {
                $accountEntries = new FactoryAccountEntry;
                $accountEntries->account_book_id = $accountBook->id;
                $accountEntries->entry_type = FactoryEntryType::Payment;
                $accountEntries->entry_id = $factory->id;
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
            $newBook->account_id = $factory->id;
            $newBook->account_type = 'factory';
            $newBook->save();

            logActivity($accountBook, $accountBook->id, 'account-book.show', 'Update', __('Factory account book closed'));

            return $newBook;
        });
    }
}
