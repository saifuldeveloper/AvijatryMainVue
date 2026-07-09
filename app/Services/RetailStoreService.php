<?php

namespace App\Services;

use App\Models\RetailStore;
use App\Repositories\RetailStoreRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RetailStoreService
{
    protected $repository;

    public function __construct(RetailStoreRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $trashStores = $this->repository->getTrashedStores();

        // Regular Stores filtering and pagination
        $sortBy = $request->input('sort_by', 'id');
        $sortDirection = $request->input('sort_direction', 'desc');

        if (!in_array($sortBy, ['id', 'shop_name', 'address', 'mobile_no', 'commission'])) {
            $sortBy = 'id';
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        $perPage = $request->input('per_page', 50);
        $regularQuery = $this->repository->getBaseQuery($request, false);
        $retailStores = $regularQuery->orderBy($sortBy, $sortDirection)
            ->paginate($perPage, ['*'], 'page')
            ->withQueryString();

        // One-time Stores filtering and pagination
        $onetimePerPage = $request->input('onetime_per_page', 25);
        $onetimeQuery = $this->repository->getBaseQuery($request, true);
        $onetimeStores = $onetimeQuery->orderBy('id', 'desc')
            ->paginate($onetimePerPage, ['*'], 'onetime_page')
            ->withQueryString();

        return Inertia::render('RetailStore/Index', [
            'retailStores' => $retailStores,
            'onetimeStores' => $onetimeStores,
            'trashStores' => $trashStores,
            'filters' => $request->only(['name', 'address', 'mobile_no', 'commission', 'per_page', 'onetime_per_page', 'sort_by', 'sort_direction']),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'shop_name' => 'required|unique:retail_stores,shop_name',
            'address' => 'required|string',
            'mobile_no' => 'required|string',
            'commission' => 'nullable|numeric',
            'company_share' => 'nullable|boolean',
            'onetime_buyer' => 'nullable|boolean',
            'secret_key' => 'nullable|string',
        ], [
            'shop_name.required' => 'দোকানের নাম দেওয়া আবশ্যক।',
            'shop_name.unique' => 'এই নামটি ইতিমধ্যে পার্টিতে ব্যবহার করা হয়েছে।',
            'address.required' => 'ঠিকানা দেওয়া আবশ্যক।',
            'mobile_no.required' => 'মোবাইল নম্বর দেওয়া আবশ্যক।',
        ]);

        $data = $request->all();
        $data['onetime_buyer'] = $request->boolean('onetime_buyer', false);
        $data['company_share'] = $request->boolean('company_share', false);
        $data['commission'] = $request->input('commission') ?? 0;

        $retailStore = $this->repository->store($data);
        logActivity($retailStore, $retailStore->id, 'retail-store.index', 'Create', __('New RetailStore information saved'));

        if ($retailStore->onetime_buyer) {
            return back()->with([
                'success-alert' => __('New RetailStore information saved') ?? 'New retail store added successfully',
                'retail-store' => $retailStore
            ]);
        }

        return back()->with('success-alert', __('New RetailStore information saved') ?? 'New retail store added successfully');
    }

    public function show($id)
    {
        $retailStore = $this->repository->show($id);
        $accountBooks = $retailStore->accountBooks()->orderBy('id', 'desc')->get();

        foreach ($accountBooks as $book) {
            $book->calculated_balance = $book->getCalculatedBalance();
            $book->description = $book->getDescription();
        }

        return Inertia::render('RetailStore/Show', [
            'retailStore' => $retailStore,
            'accountBooks' => $accountBooks,
        ]);
    }

    public function update(Request $request, RetailStore $retailStore)
    {
        $request->validate([
            'shop_name' => 'required|unique:retail_stores,shop_name,' . $retailStore->id,
            'address' => 'required|string',
            'mobile_no' => 'required|string',
            'commission' => 'nullable|numeric',
            'company_share' => 'nullable|boolean',
            'onetime_buyer' => 'nullable|boolean',
            'secret_key' => 'nullable|string',
        ], [
            'shop_name.required' => 'দোকানের নাম দেওয়া আবশ্যক।',
            'shop_name.unique' => 'এই নামটি ইতিমধ্যে পার্টিতে ব্যবহার করা হয়েছে।',
            'address.required' => 'ঠিকানা দেওয়া আবশ্যক।',
            'mobile_no.required' => 'মোবাইল নম্বর দেওয়া আবশ্যক।',
        ]);

        $data = $request->all();
        $data['onetime_buyer'] = $request->boolean('onetime_buyer', false);
        $data['company_share'] = $request->boolean('company_share', false);
        $data['commission'] = $request->input('commission') ?? 0;

        $updatedStore = $this->repository->update($retailStore, $data);
        logActivity($updatedStore, $updatedStore->id, 'retail-store.index', 'Update', __('RetailStore Information Updated'));

        return back()->with('success-alert', __('RetailStore Information Updated') ?? 'Retail store updated successfully');
    }

    public function destroy(RetailStore $retailStore)
    {
        $result = $this->repository->destroy($retailStore);
        return back()->with('success-alert', $result->get('success'));
    }

    public function forceDelete($id)
    {
        $result = $this->repository->forceDelete($id);
        return back()->with('success-alert', $result->get('success'));
    }

    public function restore($id)
    {
        $result = $this->repository->restore($id);
        return back()->with('success-alert', $result->get('success'));
    }
}
