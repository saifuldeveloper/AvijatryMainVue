<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Services\PurchaseService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PurchaseController extends Controller implements HasMiddleware
{
    protected PurchaseService $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:manage purchases'),
        ];
    }

    public function index(Request $request)
    {
        if (!$request->filled('id')) {
            return back()->with('error-alert', __('pages.ID required') ?? 'আইডি প্রদান করুন।');
        }
        return redirect()->route('purchase.show', ['purchase' => $request->input('id')]);
    }

    public function create()
    {
        return $this->purchaseService->create();
    }

    public function store(Request $request)
    {
        return $this->purchaseService->store($request);
    }

    public function show($id)
    {
        return $this->purchaseService->show($id);
    }

    public function barcode($id)
    {
        return $this->purchaseService->barcode($id);
    }

    public function edit(Purchase $purchase)
    {
        return $this->purchaseService->edit($purchase);
    }

    public function update(Request $request, Purchase $purchase)
    {
        return $this->purchaseService->update($request, $purchase);
    }

    public function destroy(Purchase $purchase)
    {
        return $this->purchaseService->destroy($purchase);
    }

    public function forceDelete($id)
    {
        return $this->purchaseService->forceDelete($id);
    }

    public function restore($id)
    {
        return $this->purchaseService->restore($id);
    }
}
