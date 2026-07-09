<?php

namespace App\Http\Controllers;

use App\Models\Factory;
use App\Models\AccountBook;
use App\Models\FactoryAccountEntry;
use App\Enums\FactoryEntryType;
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

    /* =================== Factory Account Entry CRUD =================== */

    public function entryStore(Request $request)
    {
        abort_unless(auth()->user()->can('manage factories'), 403);

        $validated = $request->validate([
            'account_book_id' => 'required|numeric|exists:account_books,id',
            'entry_type' => 'required|integer|in:0,1,2',
            'purchase_id' => 'nullable|numeric',
            'count' => 'nullable|numeric|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'retail_price' => 'nullable|numeric|min:0',
            'total_amount' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'account_name' => 'nullable|string', // Payment method
            'created_at' => 'nullable|date',
        ]);

        $accountBook = AccountBook::findOrFail($validated['account_book_id']);
        
        $entry = new FactoryAccountEntry();
        $entry->account_book_id = $accountBook->id;
        $entry->entry_id = $accountBook->account_id; // Factory ID
        $entry->entry_type = (int)$validated['entry_type'];
        $entry->purchase_id = $validated['purchase_id'] ?? null;
        $entry->count = $validated['count'] ?? 0;
        $entry->purchase_price = $validated['purchase_price'] ?? 0;
        $entry->retail_price = $validated['retail_price'] ?? 0;
        $entry->description = $validated['description'] ?? null;
        $entry->account_name = $validated['account_name'] ?? null;
        $entry->created_at = $validated['created_at'] ?? now();

        // Calculate total amount if not explicitly passed
        if (isset($validated['total_amount'])) {
            $entry->total_amount = $validated['total_amount'];
        } else {
            if ($entry->entry_type->value == 0 || $entry->entry_type->value == 1) {
                // count is pair. purchase_price is price per dozen.
                $entry->total_amount = ($entry->count * $entry->purchase_price) / 12;
            } else {
                $entry->total_amount = 0;
            }
        }

        $entry->save();

        logActivity($entry, $entry->id, 'account-book.show', 'Create', __('Factory entry created'));

        return redirect()->route('account-book.show', $accountBook->id)->with('success-alert', __('pages.Factory entry created successfully') ?? 'Factory entry created successfully');
    }

    public function entryUpdate(Request $request, $id)
    {
        abort_unless(auth()->user()->can('manage factories'), 403);

        $validated = $request->validate([
            'account_book_id' => 'required|numeric|exists:account_books,id',
            'entry_type' => 'required|integer|in:0,1,2',
            'purchase_id' => 'nullable|numeric',
            'count' => 'nullable|numeric|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'retail_price' => 'nullable|numeric|min:0',
            'total_amount' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'account_name' => 'nullable|string',
            'created_at' => 'nullable|date',
        ]);

        $entry = FactoryAccountEntry::findOrFail($id);
        $entry->account_book_id = $validated['account_book_id'];
        $entry->entry_type = (int)$validated['entry_type'];
        $entry->purchase_id = $validated['purchase_id'] ?? null;
        $entry->count = $validated['count'] ?? 0;
        $entry->purchase_price = $validated['purchase_price'] ?? 0;
        $entry->retail_price = $validated['retail_price'] ?? 0;
        $entry->description = $validated['description'] ?? null;
        $entry->account_name = $validated['account_name'] ?? null;
        $entry->created_at = $validated['created_at'] ?? $entry->created_at;

        if (isset($validated['total_amount'])) {
            $entry->total_amount = $validated['total_amount'];
        } else {
            if ($entry->entry_type->value == 0 || $entry->entry_type->value == 1) {
                $entry->total_amount = ($entry->count * $entry->purchase_price) / 12;
            } else {
                $entry->total_amount = 0;
            }
        }

        $entry->save();

        logActivity($entry, $entry->id, 'account-book.show', 'Update', __('Factory entry updated'));

        return redirect()->route('account-book.show', $entry->account_book_id)->with('success-alert', __('pages.Factory entry updated successfully') ?? 'Factory entry updated successfully');
    }

    public function entryDestroy($id)
    {
        abort_unless(auth()->user()->can('manage factories'), 403);

        $entry = FactoryAccountEntry::findOrFail($id);
        $bookId = $entry->account_book_id;
        
        logActivity($entry, $entry->id, 'account-book.show', 'SoftDelete', __('Factory entry deleted'), false);
        $entry->delete();

        return redirect()->route('account-book.show', $bookId)->with('success-alert', __('pages.Factory entry deleted successfully') ?? 'Factory entry deleted successfully');
    }

    public function entryRestore($id)
    {
        abort_unless(auth()->user()->can('manage factories'), 403);

        $entry = FactoryAccountEntry::onlyTrashed()->findOrFail($id);
        $entry->restore();

        logActivity($entry, $entry->id, 'account-book.show', 'Restore', __('Factory entry restored'));

        return redirect()->route('account-book.show', $entry->account_book_id)->with('success-alert', __('pages.Factory entry restored successfully') ?? 'Factory entry restored successfully');
    }

    public function entryForceDelete($id)
    {
        abort_unless(auth()->user()->can('manage factories'), 403);

        $entry = FactoryAccountEntry::onlyTrashed()->findOrFail($id);
        $bookId = $entry->account_book_id;

        logActivity($entry, $entry->id, 'account-book.show', 'Delete', __('Factory entry permanently deleted'));
        $entry->forceDelete();

        return redirect()->route('account-book.show', $bookId)->with('success-alert', __('pages.Factory entry permanently deleted') ?? 'Factory entry permanently deleted');
    }
}
