<?php

namespace App\Repositories;

use App\Models\Account;
use App\Models\AccountBook;
use App\Models\BankAccount;

class BankAccountRepository
{
    public function getBankAccounts($request)
    {
        return BankAccount::when($request->bank, function ($query) use ($request) {
            $query->where('bank', 'like', '%' . $request->bank . '%');
        })
        ->when($request->account_no, function ($query) use ($request) {
            $query->where('account_no', 'like', '%' . $request->account_no . '%');
        })
        ->get();
    }

    public function store(array $data)
    {
        $bankAccount = new BankAccount;
        $bankAccount->fill($data);
        $bankAccount->save();

        $account = new Account;
        $account->id = $bankAccount->id;
        $account->type = 'bank-account';
        $account->name = $bankAccount->bank;
        $account->save();

        $accountBook = new AccountBook;
        $accountBook->account_id = $bankAccount->id;
        $accountBook->account_type = 'bank-account';
        $accountBook->save();

        return $bankAccount;
    }

    public function show(BankAccount $bankAccount)
    {
        return $bankAccount;
    }

    public function update(BankAccount $bankAccount, array $data)
    {
        $bankAccount->fill($data);
        $bankAccount->save();

        return $bankAccount;
    }

    public function destroy(BankAccount $bankAccount)
    {
        logActivity($bankAccount, $bankAccount->id, 'bank-account.index', 'SoftDelete', __('Bank account information has been deleted'), false);
        $bankAccount->delete();
        return collect(['success' => __('pages.Bank account information has been deleted')]);
    }

    public function forceDelete($id)
    {
        $bankAccount = BankAccount::withTrashed()->find($id);
        logActivity($bankAccount, $bankAccount->id, 'bank-account.index', 'Delete', __('Bank account information has been permanently deleted.'));
        $bankAccount->forceDelete();
        return collect(['success' => __('pages.Bank account information has been permanently deleted.')]);
    }

    public function restore($id)
    {
        $bankAccount = BankAccount::withTrashed()->find($id);
        $bankAccount->restore();
        logActivity($bankAccount, $bankAccount->id, 'bank-account.index', 'SoftDelete', __('Bank account information has been restored'), false);
        return collect(['success' => __('pages.Bank account information has been restored')]);
    }
}
