<?php

namespace App\Services;

use App\Models\Gift;
use App\Models\BankAccount;
use App\Models\GiftPurchase;
use App\Models\GiftSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Requests\GiftPurchaseRequest;
use App\Repositories\GiftPurchaseRepository;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;

class GiftPurchaseService
{
    protected $repository;

    public function __construct(GiftPurchaseRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create()
    {
        $gifts = Gift::all();
        $memoNo = GiftPurchase::getNextId();
        $giftSuppliers = GiftSupplier::all();
        $bankAccount = BankAccount::all();
        $bankAccounts = new Collection();
        foreach ($bankAccount as $item) {
            if ($item->bank === 'ক্যাশ') {
                $bankAccounts->push((object) [
                    'id' => $item->id,
                    'name' => 'ক্যাশ'
                ]);
            } else {
                $bankAccounts->push((object) [
                    'id' => $item->id,
                    'name' => $item->bank . ' - ' . $item->branch . ' - (' . $item->account_no . ')'
                ]);
            }
        }
        $bankAccounts->push((object) ['id' => 'cheque', 'name' => 'চেক']);
        
        return Inertia::render('GiftPurchase/Create', [
            'gifts' => $gifts,
            'memoNo' => $memoNo,
            'bankAccounts' => $bankAccounts,
            'giftSuppliers' => $giftSuppliers,
        ]);
    }

    public function store(GiftPurchaseRequest $request)
    {
        $request->validated();
        $giftPurchase = $this->repository->store($request->all());

        logActivity($giftPurchase, $giftPurchase->id, 'gift-purchase.show', 'Create', __('Gift purchase completed'));

        return Redirect::route('gift-purchase.show', $giftPurchase->id)->with('success', __('pages.Gift purchase completed') ?? 'Gift purchase completed successfully');
    }

    public function show(GiftPurchase $giftPurchase)
    {
        $giftPurchase = $this->repository->show($giftPurchase);
        return Inertia::render('GiftPurchase/Show', [
            'giftPurchase' => $giftPurchase,
        ]);
    }

    public function edit(GiftPurchase $giftPurchase)
    {
        $gifts = Gift::all();
        $giftPurchase->load('accountBook.account', 'giftTransactions');
        $giftSuppliers = GiftSupplier::all();
        $bankAccount = BankAccount::all();
        $bankAccounts = new Collection();
        foreach ($bankAccount as $item) {
            if ($item->bank === 'ক্যাশ') {
                $bankAccounts->push((object) [
                    'id' => $item->id,
                    'name' => 'ক্যাশ'
                ]);
            } else {
                $bankAccounts->push((object) [
                    'id' => $item->id,
                    'name' => $item->bank . ' - ' . $item->branch . ' - (' . $item->account_no . ')'
                ]);
            }
        }
        $bankAccounts->push((object) ['id' => 'cheque', 'name' => 'চেক']);
        
        return Inertia::render('GiftPurchase/Create', [
            'gifts' => $gifts,
            'giftPurchase' => $giftPurchase,
            'bankAccounts' => $bankAccounts,
            'giftSuppliers' => $giftSuppliers,
            'isEditing' => true,
        ]);
    }

    public function update(GiftPurchaseRequest $request, GiftPurchase $giftPurchase)
    {
        $request->validated();
        $giftPurchase = $this->repository->update($giftPurchase, $request->all());

        logActivity($giftPurchase, $giftPurchase->id, 'gift-purchase.show', 'Update', __('Gift memo edit completed.'));

        return Redirect::route('gift-purchase.show', $giftPurchase->id)->with('success', __('pages.Gift memo edit completed.') ?? 'Gift memo edit completed successfully');
    }

    public function destroy(GiftPurchase $giftPurchase)
    {
        if ($giftPurchase) {
            $this->repository->destroy($giftPurchase);
            return Redirect::route('gift-purchase.create')->with('success', __('pages.Gift purchase deleted'));
        }
        return Redirect::back();
    }

    public function tr(Request $request)
    {
        // Handled in Vue state
    }

    public function restore($id)
    {
        $purchase = $this->repository->restore($id);
        if ($purchase) {
            $accountBook = $purchase->accountBook;
            return Redirect::route('account-book.show', $accountBook->id)->with('success', __('pages.Gift Purchase restored') ?? 'Gift Purchase restored successfully');
        }
        return Redirect::back();
    }

    public function forceDelete($id)
    {
        $message = $this->repository->forceDelete($id);
        return Redirect::back()->with('success', $message['success']);
    }
}
