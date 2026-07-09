<?php

namespace App\Http\Controllers;

use App\Models\RetailStore;
use App\Services\RetailStoreService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RetailStoreController extends Controller implements HasMiddleware
{
    protected $retailStoreService;

    public function __construct(RetailStoreService $retailStoreService)
    {
        $this->retailStoreService = $retailStoreService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:manage retail stores'),
        ];
    }

    public function index(Request $request)
    {
        return $this->retailStoreService->index($request);
    }

    public function store(Request $request)
    {
        return $this->retailStoreService->store($request);
    }

    public function show($id)
    {
        return $this->retailStoreService->show($id);
    }

    public function update(Request $request, RetailStore $retailStore)
    {
        return $this->retailStoreService->update($request, $retailStore);
    }

    public function destroy(RetailStore $retailStore)
    {
        return $this->retailStoreService->destroy($retailStore);
    }

    public function forceDelete($id)
    {
        return $this->retailStoreService->forceDelete($id);
    }

    public function restore($id)
    {
        return $this->retailStoreService->restore($id);
    }
}
