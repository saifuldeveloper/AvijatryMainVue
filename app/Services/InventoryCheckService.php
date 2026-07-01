<?php

namespace App\Services;

use App\Models\InventoryCheck;
use App\Repositories\InventoryCheckRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InventoryCheckService
{
    protected InventoryCheckRepository $repository;

    public function __construct(InventoryCheckRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $inventoryCheck = InventoryCheck::getRunningCheck();

        if ($inventoryCheck === null) {
            return redirect()->route('inventory-check.create');
        } else {
            return redirect()->route('inventory-check.show', ['inventory_check' => $inventoryCheck->id]);
        }
    }

    public function create()
    {
        return Inertia::render('InventoryCheck/Create');
    }

    public function store(Request $request)
    {
        $inventoryCheck = $this->repository->store($request->all());
        return redirect()->route('inventory-check.show', ['inventory_check' => $inventoryCheck->id]);
    }

    public function show($id)
    {
        $inventoryCheck = $this->repository->show($id);

        $stats = [
            'complete_match_pairs' => (int) $inventoryCheck->inventoryCheckDetails()->where('remaining', 0)->sum('count'),
            'under_stock_pairs' => (int) $inventoryCheck->inventoryCheckDetails()->where('remaining', '>', 0)->sum('remaining'),
            'extra_match_pairs' => (int) abs($inventoryCheck->inventoryCheckDetails()->where('remaining', '<', 0)->sum('remaining')),
            'total_count_ids' => (int) $inventoryCheck->inventoryCheckDetails()->count(),
            'total_count_pairs' => (int) $inventoryCheck->inventoryCheckDetails()->sum('count'),
            'remaining_count_ids' => (int) $inventoryCheck->RemainingCount(),
            'remaining_count_pairs' => (int) $inventoryCheck->RemainingPairs(),
        ];

        if ($inventoryCheck->complete) {
            return Inertia::render('InventoryCheck/Resolve', [
                'inventoryCheck' => $inventoryCheck,
                'stats' => $stats
            ]);
        } else {
            return Inertia::render('InventoryCheck/CheckForm', [
                'inventoryCheck' => $inventoryCheck,
                'stats' => $stats
            ]);
        }
    }

    public function getRemainingData(Request $request, $id)
    {
        $query = $this->repository->getRemainingDataQuery($id);

        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('shoes.code', 'LIKE', "%{$search}%")
                    ->orWhere('inventories.factory', 'LIKE', "%{$search}%")
                    ->orWhere('inventories.category', 'LIKE', "%{$search}%")
                    ->orWhere('inventories.color', 'LIKE', "%{$search}%");
            });
        }

        $perPage = $request->input('per_page', 50);
        $entries = $query->paginate($perPage)->withQueryString();

        return Inertia::render('InventoryCheck/RemainingData', [
            'id' => $id,
            'entries' => $entries,
            'filters' => $request->only(['search', 'per_page']),
        ]);
    }

    public function complete($id)
    {
        $inventoryCheck = $this->repository->complete($id);
        return redirect()->route('inventory-check.show', ['inventory_check' => $inventoryCheck->id]);
    }

    public function resume($id)
    {
        $inventoryCheck = $this->repository->resume($id);
        return redirect()->route('inventory-check.show', ['inventory_check' => $inventoryCheck->id]);
    }

    public function resolve(Request $request, $id)
    {
        return $this->repository->resolve($request->all(), $id);
    }
}
