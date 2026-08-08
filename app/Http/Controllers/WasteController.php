<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WasteService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class WasteController extends Controller implements HasMiddleware
{
    protected $wasteService;

    public function __construct(WasteService $wasteService)
    {
        $this->wasteService = $wasteService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:manage shoe waste', only: ['shoePage']),
            new Middleware('permission:manage gift waste', only: ['giftPage']),
        ];
    }

    public function shoePage(Request $request)
    {
        return $this->wasteService->shoePage($request);
    }

    public function wasteShoesList(Request $request)
    {
        return $this->wasteService->shoePage($request);
    }

    public function giftPage(Request $request)
    {
        return $this->wasteService->giftPage($request);
    }

    public function giftPageData(Request $request)
    {
        return $this->wasteService->giftPage($request);
    }

    public function partyWasteGift(Request $request)
    {
        return $this->wasteService->partyWasteGift($request);
    }

    public function partyShoeGiftData(Request $request)
    {
        return $this->wasteService->partyWasteGift($request);
    }

    public function partyGiftData(Request $request)
    {
        return $this->wasteService->partyWasteGift($request);
    }

    public function shoe(Request $request)
    {
        return $this->wasteService->shoe($request);
    }

    public function gift(Request $request)
    {
        return $this->wasteService->gift($request);
    }

    public function wasteShoetSoftDelete($id)
    {
        return $this->wasteService->wasteShoetSoftDelete($id);
    }

    public function wasteShoeRestore($id)
    {
        return $this->wasteService->wasteShoeRestore($id);
    }

    public function wasteShoeForceDelete($id)
    {
        return $this->wasteService->wasteShoeForceDelete($id);
    }

    public function wasteGiftSoftDelete($id)
    {
        return $this->wasteService->wasteGiftSoftDelete($id);
    }

    public function wasteGiftRestore($id)
    {
        return $this->wasteService->wasteGiftRestore($id);
    }

    public function wasteGiftForceDelete($id)
    {
        return $this->wasteService->wasteGiftForceDelete($id);
    }
}
