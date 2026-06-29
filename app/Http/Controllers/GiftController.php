<?php

namespace App\Http\Controllers;

use App\Models\Gift;
use Illuminate\Http\Request;
use App\Services\GiftService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class GiftController extends Controller implements HasMiddleware
{
    protected $giftService;

    public function __construct(GiftService $giftService)
    {
        $this->giftService = $giftService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:manage gifts'),
        ];
    }

    public function index(Request $request)
    {
        return $this->giftService->index($request);
    }

    public function create()
    {
        return $this->giftService->create();
    }

    public function store(Request $request)
    {
        return $this->giftService->store($request);
    }

    public function show(Gift $gift)
    {
        return $this->giftService->show($gift);
    }

    public function transactionShow(Request $request, $id)
    {
        return $this->giftService->transactionShow($request, $id);
    }

    public function edit(Gift $gift)
    {
        return $this->giftService->edit($gift);
    }

    public function update(Request $request, Gift $gift)
    {
        return $this->giftService->update($request, $gift);
    }

    public function destroy(Gift $gift)
    {
        return $this->giftService->destroy($gift);
    }

    public function forceDelete($id)
    {
        return $this->giftService->forceDelete($id);
    }

    public function restore($id)
    {
        return $this->giftService->restore($id);
    }
}
