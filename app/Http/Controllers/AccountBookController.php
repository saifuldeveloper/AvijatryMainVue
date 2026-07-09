<?php
 
namespace App\Http\Controllers;
 
use App\Models\AccountBook;
use App\Services\AccountBookService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
 
class AccountBookController extends Controller implements HasMiddleware
{
    protected AccountBookService $service;
 
    public function __construct(AccountBookService $service)
    {
        $this->service = $service;
    }
 
    public static function middleware(): array
    {
        return [];
    }

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }
 
    public function show(AccountBook $accountBook, Request $request)
    {
        $permissionMap = [
            'factory' => 'manage factories',
            'retail-store' => 'manage retail stores',
            'gift-supplier' => 'manage gift suppliers',
            'employee' => 'manage employees',
            'bank-account' => 'manage bank accounts',
        ];
        $permission = $permissionMap[$accountBook->account_type] ?? null;
        if ($permission && !auth()->user()->can($permission)) {
            abort(403, 'Unauthorized action.');
        }
        return $this->service->show($accountBook, $request);
    }
 
    public function edit(AccountBook $accountBook)
    {
        //
    }

    public function update(Request $request, AccountBook $accountBook)
    {
        //
    }

    public function destroy(AccountBook $accountBook)
    {
        //
    }

    public function closingPage(AccountBook $accountBook, Request $request)
    {
        $isDummy = $request->dummy == 1;
        $permissionMap = [
            'factory' => $isDummy ? 'factory dummy closing' : 'factory closing',
            'retail-store' => $isDummy ? 'retail store dummy closing' : 'retail store closing',
            'gift-supplier' => $isDummy ? 'gift suppliers dummy closing' : 'gift suppliers closing',
            'employee' => $isDummy ? 'employees dummy closing' : 'employees closing',
            'bank-account' => 'bank account closing',
        ];
        $permission = $permissionMap[$accountBook->account_type] ?? null;
        if ($permission && !auth()->user()->can($permission)) {
            abort(403, 'Unauthorized action.');
        }
        return $this->service->closingPage($accountBook, $request);
    }
 
    public function closing(Request $request, AccountBook $accountBook)
    {
        $permissionMap = [
            'factory' => 'factory closing',
            'retail-store' => 'retail store closing',
            'gift-supplier' => 'gift suppliers closing',
            'employee' => 'employees closing',
            'bank-account' => 'bank account closing',
        ];
        $permission = $permissionMap[$accountBook->account_type] ?? null;
        if ($permission && !auth()->user()->can($permission)) {
            abort(403, 'Unauthorized action.');
        }
        return $this->service->closing($request, $accountBook);
    }
 
    public function forwardBalance(AccountBook $accountBook)
    {
        $permissionMap = [
            'factory' => 'manage factories',
            'retail-store' => 'manage retail stores',
            'gift-supplier' => 'manage gift suppliers',
            'employee' => 'manage employees',
            'bank-account' => 'manage bank accounts',
        ];
        $permission = $permissionMap[$accountBook->account_type] ?? null;
        if ($permission && !auth()->user()->can($permission)) {
            abort(403, 'Unauthorized action.');
        }
        return $this->service->forwardBalance($accountBook);
    }
}
