<?php

namespace App\Services;

use App\Repositories\InventoryCheckEntryRepository;
use Illuminate\Http\Request;

class InventoryCheckEntryService
{
    protected InventoryCheckEntryRepository $repository;

    public function __construct(InventoryCheckEntryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function store(Request $request)
    {
        $this->repository->store($request->all());
        return redirect()->back();
    }
}
