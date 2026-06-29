<?php

namespace App\Http\Controllers;

use App\Models\GiftPurchase;
use Illuminate\Http\Request;
use App\Services\GiftPurchaseService;
use App\Http\Requests\GiftPurchaseRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class GiftPurchaseController extends Controller implements HasMiddleware
{
    protected $giftPurchaseService;

    public function __construct(GiftPurchaseService $giftPurchaseService)
    {
        $this->giftPurchaseService = $giftPurchaseService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:manage gifts'),
        ];
    }

    public function create()
    {
        return $this->giftPurchaseService->create();
    }

    public function store(GiftPurchaseRequest $request)
    {
        return $this->giftPurchaseService->store($request);
    }

    public function show(GiftPurchase $giftPurchase)
    {
        return $this->giftPurchaseService->show($giftPurchase);
    }

    public function edit(GiftPurchase $giftPurchase)
    {
        return $this->giftPurchaseService->edit($giftPurchase);
    }

    public function update(GiftPurchaseRequest $request, GiftPurchase $giftPurchase)
    {
        return $this->giftPurchaseService->update($request, $giftPurchase);
    }

    public function destroy(GiftPurchase $giftPurchase)
    {
        return $this->giftPurchaseService->destroy($giftPurchase);
    }

    public function tr(Request $request)
    {
        return $this->giftPurchaseService->tr($request);
    }

    public function restore($id)
    {
        return $this->giftPurchaseService->restore($id);
    }

    public function forceDelete($id)
    {
        return $this->giftPurchaseService->forceDelete($id);
    }
}
