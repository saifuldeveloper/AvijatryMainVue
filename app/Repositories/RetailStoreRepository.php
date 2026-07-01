<?php

namespace App\Repositories;

use App\Models\RetailStore;
use App\Models\Account;
use App\Models\AccountBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RetailStoreRepository
{
    public function getBaseQuery(Request $request, bool $onetimeBuyer = false)
    {
        return RetailStore::query()
            ->where('onetime_buyer', $onetimeBuyer)
            ->when($request->name, function ($query, $name) {
                $query->where('shop_name', 'like', '%' . $name . '%');
            })
            ->when($request->address, function ($query, $address) {
                $query->where('address', 'like', '%' . $address . '%');
            })
            ->when($request->mobile_no, function ($query, $mobile_no) {
                $query->where('mobile_no', 'like', '%' . $mobile_no . '%');
            })
            ->when(!$onetimeBuyer && $request->commission !== null && $request->commission !== '', function ($query, $commission) {
                $query->where('commission', $commission);
            });
    }

    public function getTrashedStores()
    {
        return RetailStore::with('deletedBy')->onlyTrashed()->orderBy('id', 'desc')->get();
    }

    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {
            $retailStore = new RetailStore;
            $retailStore->fill($data);
            $retailStore->save();

            $account = new Account;
            $account->id = $retailStore->id;
            $account->type = 'retail-store';
            $account->name = $retailStore->shop_name;
            $account->save();

            $accountBook = new AccountBook;
            $accountBook->account_id = $retailStore->id;
            $accountBook->account_type = 'retail-store';
            $accountBook->save();

            return $retailStore;
        });
    }

    public function show($id)
    {
        return RetailStore::with('accountBooks')->findOrFail($id);
    }

    public function update(RetailStore $retailStore, array $data)
    {
        return DB::transaction(function () use ($retailStore, $data) {
            $retailStore->fill($data);
            $retailStore->save();

            Account::where('id', $retailStore->id)->where('type', 'retail-store')->update([
                'name' => $retailStore->shop_name,
            ]);

            return $retailStore;
        });
    }

    public function destroy(RetailStore $retailStore)
    {
        logActivity($retailStore, $retailStore->id, 'retail-store.index', 'SoftDelete', __('RetailStore Information Deleted'), false);
        $retailStore->delete();
        return collect(['success' => __('pages.RetailStore Information Deleted')]);
    }

    public function forceDelete($id)
    {
        $retailStore = RetailStore::withTrashed()->findOrFail($id);
        logActivity($retailStore, $retailStore->id, 'retail-store.index', 'Delete', __('RetailStore Information Permanently Deleted'), false);
        $retailStore->forceDelete();
        return collect(['success' => __('pages.RetailStore Information Permanently Deleted')]);
    }

    public function restore($id)
    {
        $retailStore = RetailStore::withTrashed()->findOrFail($id);
        $retailStore->restore();
        logActivity($retailStore, $retailStore->id, 'retail-store.index', 'Restore', __('RetailStore Information Restored'));
        return collect(['success' => __('pages.RetailStore Information Restored')]);
    }
}
