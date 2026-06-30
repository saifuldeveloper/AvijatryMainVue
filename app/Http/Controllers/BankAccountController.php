<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use Illuminate\Http\Request;
use App\Services\BankAccountService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class BankAccountController extends Controller implements HasMiddleware
{
    protected $bankAccountService;

    public function __construct(BankAccountService $bankAccountService)
    {
        $this->bankAccountService = $bankAccountService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:manage bank accounts'),
        ];
    }

    public function index(Request $request)
    {
        return $this->bankAccountService->index($request);
    }

    public function create()
    {
        return $this->bankAccountService->create();
    }

    public function store(Request $request)
    {
        return $this->bankAccountService->store($request);
    }

    public function show(BankAccount $bankAccount)
    {
        return $this->bankAccountService->show($bankAccount);
    }

    public function edit(BankAccount $bankAccount)
    {
        return $this->bankAccountService->edit($bankAccount);
    }

    public function update(Request $request, BankAccount $bankAccount)
    {
        return $this->bankAccountService->update($request, $bankAccount);
    }

    public function destroy(BankAccount $bankAccount)
    {
        return $this->bankAccountService->destroy($bankAccount);
    }

    public function forceDelete($id)
    {
        return $this->bankAccountService->forceDelete($id);
    }

    public function restore($id)
    {
        return $this->bankAccountService->restore($id);
    }

    public function entryDateCurrection($id)
    {
        return $this->bankAccountService->entryDateCurrection($id);
    }
}
