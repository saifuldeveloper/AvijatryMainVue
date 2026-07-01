<?php

namespace App\Services;

use App\Repositories\RetailStoreExpenseRepository;
use Illuminate\Http\Request;

class RetailStoreExpenseService
{
    protected RetailStoreExpenseRepository $repository;

    public function __construct(RetailStoreExpenseRepository $repository)
    {
        $this->repository = $repository;
    }

    public function store(Request $request)
    {
        $request->validate([
            'account_book_id' => 'required|exists:account_books,id',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        $this->repository->store($request->all());
        return back()->with('success-alert', __('pages.Other expenses saved.') ?? 'Other expenses saved successfully');
    }

    public function delete($id)
    {
        $response = $this->repository->delete($id);
        return back()->with('success-alert', $response['success']);
    }

    public function restore($id)
    {
        $response = $this->repository->restore($id);
        return back()->with('success-alert', $response['success']);
    }

    public function forceDelete($id)
    {
        $response = $this->repository->forceDelete($id);
        return back()->with('success-alert', $response['success']);
    }
}
