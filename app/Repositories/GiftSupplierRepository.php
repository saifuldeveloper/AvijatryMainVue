<?php

namespace App\Repositories;

use App\Models\Account;
use App\Models\AccountBook;
use App\Models\GiftSupplier;

class GiftSupplierRepository
{
    public function store(array $data)
    {
        $giftSupplier = new GiftSupplier;
        $giftSupplier->fill($data);
        $giftSupplier->save();

        $account = new Account;
        $account->id = $giftSupplier->id;
        $account->type = 'gift-supplier';
        $account->name = $giftSupplier->name;
        $account->save();

        if ($giftSupplier) {
            $account_book = new AccountBook;
            $account_book->account_id = $giftSupplier->id;
            $account_book->account_type = 'gift-supplier';
            $account_book->save();
        }

        return $giftSupplier;
    }

    public function show(GiftSupplier $giftSupplier)
    {
        $giftSupplier->getCurrentAccountBook();
        $giftSupplier->load('entries');
        return $giftSupplier;
    }

    public function update(GiftSupplier $giftSupplier, array $data)
    {
        $giftSupplier->fill($data);
        $giftSupplier->save();

        return $giftSupplier;
    }

    public function destroy(GiftSupplier $giftSupplier)
    {
        logActivity($giftSupplier, $giftSupplier->id, 'gift-supplier.index', 'SoftDelete', __('Gift factory Information Deleted'), false);
        $giftSupplier->delete();
        return collect(['success' => __('pages.Gift factory Information Deleted')]);
    }

    public function forceDelete($id)
    {
        $giftSupplier = GiftSupplier::onlyTrashed()->find($id);
        logActivity($giftSupplier, $giftSupplier->id, 'gift-supplier.index', 'Delete', __('Gift factory Information Permanently Deleted'));
        $giftSupplier->forceDelete();
        return collect(['success' => __('pages.Gift factory Information Permanently Deleted')]);
    }

    public function restore($id)
    {
        $giftSupplier = GiftSupplier::onlyTrashed()->find($id);
        logActivity($giftSupplier, $giftSupplier->id, 'gift-supplier.show', 'Restore', __('Gift factory Information Restored'));
        $giftSupplier->restore();
        return collect(['success' => __('pages.Gift factory Information Restored')]);
    }
}
