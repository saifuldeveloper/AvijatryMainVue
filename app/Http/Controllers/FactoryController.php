<?php

namespace App\Http\Controllers;

use App\Models\Factory;
use App\Services\FactoryService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class FactoryController extends Controller implements HasMiddleware
{
    protected $factoryService;

    public function __construct(FactoryService $factoryService)
    {
        $this->factoryService = $factoryService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:manage factories', except: ['getFactories']),
        ];
    }

    public function index(Request $request)
    {
        return $this->factoryService->index($request);
    }

    public function getFactories(Request $request)
    {
        return $this->factoryService->getFactories($request);
    }

    public function store(Request $request)
    {
        return $this->factoryService->store($request);
    }

    public function show($id)
    {
        return $this->factoryService->show($id);
    }

    public function update(Request $request, Factory $factory)
    {
        return $this->factoryService->update($request, $factory);
    }

    public function destroy(Factory $factory)
    {
        return $this->factoryService->destroy($factory);
    }

    public function forceDelete($id)
    {
        return $this->factoryService->forceDelete($id);
    }

    public function restore($id)
    {
        return $this->factoryService->restore($id);
    }

    public function closing(Request $request, Factory $factory)
    {
        return $this->factoryService->closing($request, $factory);
    }
}
