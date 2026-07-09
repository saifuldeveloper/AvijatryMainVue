<?php

namespace App\Http\Controllers;

use App\Models\InventoryCheck;
use App\Services\InventoryCheckService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class InventoryCheckController extends Controller implements HasMiddleware
{
    protected InventoryCheckService $service;

    public function __construct(InventoryCheckService $service)
    {
        $this->service = $service;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:manage inventory check'),
        ];
    }

    public function index()
    {
        return $this->service->index();
    }

    public function create()
    {
        return $this->service->create();
    }

    public function store(Request $request)
    {
        return $this->service->store($request);
    }

    public function show($id)
    {
        return $this->service->show($id);
    }

    public function complete($id)
    {
        return $this->service->complete($id);
    }

    public function resume($id)
    {
        return $this->service->resume($id);
    }

    public function resolve(Request $request, $id)
    {
        try {
            $this->service->resolve($request, $id);
            return response()->json([
                'success' => true,
                'message' => __('pages.Inventory check completed.') ?? 'ইনভেন্টরি সমন্বয় সম্পন্ন হয়েছে।'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getRemainingData(Request $request, $id)
    {
        return $this->service->getRemainingData($request, $id);
    }

    public function getEntriesData(Request $request, $id)
    {
        $type = $request->input('type'); // partial, extra, full
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');

        $inventoryCheck = InventoryCheck::findOrFail($id);

        if ($type === 'partial') {
            $relationQuery = $inventoryCheck->partialMatchEntries();
        } elseif ($type === 'extra') {
            $relationQuery = $inventoryCheck->extraMatchEntries();
        } else {
            $relationQuery = $inventoryCheck->fullMatchEntries();
        }

        $relationQuery->leftJoin('shoes', 'shoes.code', '=', 'inventory_check_details.id')
            ->leftJoin('inventories', 'shoes.id', '=', 'inventories.shoe_id')
            ->select('inventory_check_details.*', 'shoes.id as shoe_id', DB::raw('COALESCE(inventories.count, 0) as current_stock'));

        if (!empty($search)) {
            $relationQuery->where(function ($q) use ($search) {
                $q->where('inventory_check_details.id', 'LIKE', "%{$search}%")
                    ->orWhere('inventory_check_details.factory', 'LIKE', "%{$search}%")
                    ->orWhere('inventory_check_details.color', 'LIKE', "%{$search}%");
            });
        }

        $entries = $relationQuery->paginate($perPage)->withQueryString();

        return response()->json($entries);
    }
}
