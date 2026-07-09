<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RetailStoreExpenseService;

class RetailStoreExpenseController extends Controller
{
    protected RetailStoreExpenseService $service;

    public function __construct(RetailStoreExpenseService $service)
    {
        $this->service = $service;
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->can('manage retail stores'), 403);
        return $this->service->store($request);
    }

    public function delete(Request $request, $id)
    {
        abort_unless(auth()->user()->can('manage retail stores'), 403);
        return $this->service->delete($id);
    }

    public function restore($id)
    {
        abort_unless(auth()->user()->can('manage retail stores'), 403);
        return $this->service->restore($id);
    }

    public function forceDelete($id)
    {
        abort_unless(auth()->user()->can('manage retail stores'), 403);
        return $this->service->forceDelete($id);
    }
}
