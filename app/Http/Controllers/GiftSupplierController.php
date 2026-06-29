<?php

namespace App\Http\Controllers;

use App\Models\GiftSupplier;
use Illuminate\Http\Request;
use App\Services\GiftSupplierService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class GiftSupplierController extends Controller implements HasMiddleware
{
    protected $giftSupplierService;

    public function __construct(GiftSupplierService $giftSupplierService)
    {
        $this->giftSupplierService = $giftSupplierService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:manage gift suppliers', except: ['datalist']),
        ];
    }

    public function index(Request $request)
    {
        return $this->giftSupplierService->index($request);
    }

    public function getGiftSupplierslist(Request $request)
    {
        return $this->giftSupplierService->getGiftSupplierslist($request);
    }

    public function getGiftSuppliers(Request $request)
    {
        return $this->giftSupplierService->getGiftSuppliers($request);
    }

    public function create()
    {
        return $this->giftSupplierService->create();
    }

    public function store(Request $request)
    {
        return $this->giftSupplierService->store($request);
    }

    public function show(GiftSupplier $giftSupplier)
    {
        return $this->giftSupplierService->show($giftSupplier);
    }

    public function edit(GiftSupplier $giftSupplier)
    {
        return $this->giftSupplierService->edit($giftSupplier);
    }

    public function update(Request $request, GiftSupplier $giftSupplier)
    {
        return $this->giftSupplierService->update($request, $giftSupplier);
    }

    public function destroy(GiftSupplier $giftSupplier)
    {
        return $this->giftSupplierService->destroy($giftSupplier);
    }

    public function forceDelete($id)
    {
        return $this->giftSupplierService->forceDelete($id);
    }

    public function restore($id)
    {
        return $this->giftSupplierService->restore($id);
    }

    public function datalist()
    {
        return $this->giftSupplierService->datalist();
    }

    public function entryDateCurrection($id)
    {
        return $this->giftSupplierService->entryDateCurrection($id);
    }

    public function giftSupplierEntryList(Request $request, int $id)
    {
        return $this->giftSupplierService->giftSupplierEntryList($request, $id);
    }
}
