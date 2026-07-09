<?php

namespace App\Http\Controllers;

use App\Services\InventoryCheckEntryService;
use Illuminate\Http\Request;

class InventoryCheckEntryController extends Controller
{
    protected InventoryCheckEntryService $service;

    public function __construct(InventoryCheckEntryService $service)
    {
        $this->service = $service;
    }

    public function store(Request $request)
    {
        return $this->service->store($request);
    }
}
