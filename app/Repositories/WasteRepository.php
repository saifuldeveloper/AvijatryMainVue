<?php

namespace App\Repositories;

use App\Models\WasteEntry;
use App\Models\GiftTransaction;
use App\Models\Inventory;
use App\Models\Factory;
use App\Models\GiftSupplier;
use App\Models\RetailStore;
use App\Models\Loan;
use App\Models\Employee;
use App\Models\Shoe;

class WasteRepository
{
    public function storeShoe(array $data)
    {
        $account_book = null;
        $account_type = $data['account_type'] ?? null;
        $account_id = $data['account_id'] ?? null;
        
        switch ($account_type) {
            case "factory":
                $factory = Factory::find($account_id);
                $account_book = $factory ? $factory->getCurrentAccountBook()->id : null;
                break;
            case "gift-supplier":
                $giftSupplier = GiftSupplier::find($account_id);
                $account_book = $giftSupplier ? $giftSupplier->getCurrentAccountBook()->id : null;
                break;
            case "loan-payment":
                $loanPayment = Loan::find($account_id);
                $account_book = $loanPayment ? $loanPayment->getCurrentAccountBook()->id : null;
                break;
            case "employee":
                $employee = Employee::find($account_id);
                $account_book = $employee ? $employee->getCurrentAccountBook()->id : null;
                break;
            default:
                break;
        }

        $shoe = Shoe::where('code', $data['shoe_id'])->first();
        if (!$shoe) {
            throw new \Exception("Shoe not found for Code: " . $data['shoe_id']);
        }

        $wasteEntry = new WasteEntry();
        $wasteEntry->fill($data);
        $wasteEntry->shoe_id = $shoe->id;
        $wasteEntry->entries_id = 0;
        $wasteEntry->entries_type = $account_type;
        $wasteEntry->account_book_id = $account_book;
        $wasteEntry->account_id = $account_id;
        $wasteEntry->inventory_count = 'instant_waste';
        $wasteEntry->save();

        if (isset($data['count'])) {
            $inventory = Inventory::find($shoe->id);
            if ($inventory) {
                $inventory->decrement('count', $data['count']);
            }
        }
        
        return $wasteEntry;
    }

    public function storeGift(array $data)
    {
        $name = '';
        $account_type = $data['account_type'] ?? null;
        $account_id = $data['account_id'] ?? null;
        
        switch ($account_type) {
            case "factory":
                $factory = Factory::find($account_id);
                $name = $factory ? $factory->name : '';
                break;
            case "gift-supplier":
                $giftSupplier = GiftSupplier::find($account_id);
                $name = $giftSupplier ? $giftSupplier->name : '';
                break;
            case "retail-store":
                $retailStore = RetailStore::find($account_id);
                $name = $retailStore ? $retailStore->shop_name : '';
                break;
            case "loan-payment":
                $loanPayment = Loan::find($account_id);
                $name = $loanPayment ? $loanPayment->name : '';
                break;
            case "employee":
                $employee = Employee::find($account_id);
                $name = $employee ? $employee->name : '';
                break;
            default:
                break;
        }

        $giftTransaction = new GiftTransaction();
        $giftTransaction->fill($data);
        $giftTransaction->type = 'waste';
        $giftTransaction->attachment_id = $account_id;
        $giftTransaction->attachment_type = $account_type;
        $giftTransaction->attachment_name = $name;
        $giftTransaction->save();
        
        return $giftTransaction;
    }

    public function softDeleteShoe($id)
    {
        $shoe_waste = WasteEntry::find($id);
        if ($shoe_waste) {
            $inventory = Inventory::find($shoe_waste->shoe_id);
            if ($inventory) {
                $inventory->increment('count', $shoe_waste->count);
            }
            logActivity($shoe_waste, $shoe_waste->id, 'waste.shoes-page', 'SoftDelete', __('Shoe waste information deleted'), false);
            $shoe_waste->delete();
        }
        return ['success' => __('pages.Shoe waste information deleted')];
    }

    public function restoreShoe($id)
    {
        $shoe_waste = WasteEntry::onlyTrashed()->find($id);
        if ($shoe_waste) {
            $inventory = Inventory::find($shoe_waste->shoe_id);
            if ($inventory) {
                $inventory->decrement('count', $shoe_waste->count);
            }
            logActivity($shoe_waste, $shoe_waste->id, 'waste.shoes-page', 'Restore', __('Shoe waste information restored'));
            $shoe_waste->restore();
        }
        return ['success' => __('pages.Shoe waste information restored')];
    }

    public function forceDeleteShoe($id)
    {
        $shoe_waste = WasteEntry::onlyTrashed()->find($id);
        if ($shoe_waste) {
            logActivity($shoe_waste, $shoe_waste->id, '', 'Delete', __('Shoe waste information permanently deleted'));
            $shoe_waste->forceDelete();
        }
        return ['success' => __('pages.Shoe waste information permanently deleted')];
    }

    public function softDeleteGift($id)
    {
        $gift = GiftTransaction::find($id);
        if ($gift) {
            logActivity($gift, $gift->id, 'waste.gifts-page', 'SoftDelete', __('Gift waste information deleted'), false);
            $gift->delete();
        }
        return ['success' => __('pages.Gift waste information deleted')];
    }

    public function restoreGift($id)
    {
        $gift = GiftTransaction::onlyTrashed()->find($id);
        if ($gift) {
            logActivity($gift, $gift->id, 'waste.gifts-page', 'Restore', __('Gift waste information restored'));
            $gift->restore();
        }
        return ['success' => __('pages.Gift waste information restored')];
    }

    public function forceDeleteGift($id)
    {
        $gift = GiftTransaction::onlyTrashed()->find($id);
        if ($gift) {
            logActivity($gift, $gift->id, '', 'Delete', __('Gift waste information permanently deleted'));
            $gift->forceDelete();
        }
        return ['success' => __('pages.Gift waste information permanently deleted')];
    }
}
