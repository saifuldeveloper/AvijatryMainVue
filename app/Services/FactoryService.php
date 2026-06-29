<?php

namespace App\Services;

use App\Models\Factory;
use App\Models\AccountBook;
use App\Repositories\FactoryRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FactoryService
{
    protected $repository;

    public function __construct(FactoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $trashFactories = $this->repository->getTrashedFactories();
        
        $sortBy = $request->input('sort_by', 'id');
        $sortDirection = $request->input('sort_direction', 'desc');

        if (!in_array($sortBy, ['id', 'name', 'address', 'mobile_no'])) {
            $sortBy = 'id';
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        $query = $this->repository->getBaseQuery($request);
        $perPage = $request->input('per_page', 50);
        $factories = $query->orderBy($sortBy, $sortDirection)->paginate($perPage)->withQueryString();

        return Inertia::render('Factory/Index', [
            'factories' => $factories,
            'trashFactories' => $trashFactories,
            'filters' => $request->only(['name', 'address', 'mobile_no', 'per_page', 'sort_by', 'sort_direction']),
        ]);
    }

    public function getFactories(Request $request)
    {
        $searchTerm = $request->input('search');
        $factories = $this->repository->getActiveFactoriesSearch($searchTerm);

        return response()->json([
            "status" => true,
            "data" => $factories
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:factories,name',
            'address' => 'required|string',
            'mobile_no' => 'required|string',
            'mobile_no_two' => 'nullable|string',
        ], [
            'name.required' => 'নাম দেওয়া আবশ্যক।',
            'name.unique' => 'এই নামটি ইতিমধ্যে মহাজনে ব্যবহৃত হয়েছে।',
            'address.required' => 'ঠিকানা দেওয়া আবশ্যক।',
            'mobile_no.required' => 'মোবাইল নম্বর দেওয়া আবশ্যক।',
        ]);

        $factory = $this->repository->store($request->all());
        logActivity($factory, $factory->id, 'factory.show', 'Create', __('New factory owner information saved'));

        return back()->with('success-alert', __('pages.New factory owner information saved') ?? 'New supplier added successfully');
    }

    public function show($id)
    {
        $factory = $this->repository->show($id);
        $accountBooks = $factory->accountBooks()->orderBy('id', 'desc')->get();

        // Calculate calculated balances for each account book
        foreach ($accountBooks as $book) {
            $book->calculated_balance = $book->getCalculatedBalance();
            $book->description = $book->getDescription();
        }

        return Inertia::render('Factory/Show', [
            'factory' => $factory,
            'accountBooks' => $accountBooks,
        ]);
    }

    public function update(Request $request, Factory $factory)
    {
        $request->validate([
            'name' => 'required|unique:factories,name,' . $factory->id,
            'address' => 'required|string',
            'mobile_no' => 'required|string',
            'mobile_no_two' => 'nullable|string',
        ], [
            'name.required' => 'নাম দেওয়া আবশ্যক।',
            'name.unique' => 'এই নামটি ইতিমধ্যে ব্যবহৃত হয়েছে।',
            'address.required' => 'ঠিকানা দেওয়া আবশ্যক।',
            'mobile_no.required' => 'মোবাইল নম্বর দেওয়া আবশ্যক।',
        ]);

        $updatedFactory = $this->repository->update($factory, $request->all());
        logActivity($updatedFactory, $updatedFactory->id, 'factory.show', 'Update', __('Factory owner information edited'));

        return back()->with('success-alert', __('pages.Factory owner information edited') ?? 'Supplier updated successfully');
    }

    public function destroy(Factory $factory)
    {
        $message = $this->repository->destroy($factory);
        return redirect()->route('factory.index')->with('success-alert', $message['success']);
    }

    public function forceDelete($id)
    {
        $message = $this->repository->forceDelete($id);
        return back()->with('success-alert', $message['success']);
    }

    public function restore($id)
    {
        $message = $this->repository->restore($id);
        return back()->with('success-alert', $message['success']);
    }

    public function closing(Request $request, Factory $factory)
    {
        $request->validate([
            'closing_date' => 'required|date',
            'closing_type' => 'required|string',
            'commission_percentage' => 'nullable|numeric',
            'commission' => 'nullable|numeric',
            'staff_percentage' => 'nullable|numeric',
            'staff' => 'nullable|numeric',
            'staff_shoe_amount' => 'nullable|numeric',
            'payment.*.amount' => 'nullable|numeric',
            'payment.*.method' => 'nullable|string',
        ]);

        $newBook = $this->repository->closing($factory, $request->all(), $request->input('payment', []), $request->input('cheque', []));
        return redirect()->route('factory.show', $factory->id)->with('success-alert', __('pages.Closing completed.') ?? 'Closing completed successfully');
    }
}
