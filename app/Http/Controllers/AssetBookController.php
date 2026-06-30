<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Asset;
use App\Models\Account;
use App\Models\AssetBook;
use App\Models\AccountBook;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AssetBookController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:manage asset types'),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $query = AssetBook::latest();

        if ($request->filled('name')) {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }

        $assetBooks = $query->paginate(10)->withQueryString();
        $trashAssetBooks = AssetBook::with('deletedBy')->onlyTrashed()->orderBy('id', 'desc')->get();

        return Inertia::render('AssetBook/AssetBook', [
            'assetBooks' => $assetBooks,
            'trashAssetBooks' => $trashAssetBooks,
            'filters' => $request->only(['name']),
        ]);
    }

    /**
     * Display the ledger for a specific asset book.
     */
    public function show(Request $request, AssetBook $assetBook): Response
    {
        $accountBook = $assetBook->getCurrentAccountBook();

        if (!$accountBook) {
            // Create a default account book if one doesn't exist
            $accountBook = AccountBook::create([
                'account_id' => $assetBook->id,
                'account_type' => 'asset',
                'open' => true,
            ]);
        }

        $query = Asset::with('media')->where('account_book_id', $accountBook->id)->latest();
        $trashQuery = Asset::with('media')->where('account_book_id', $accountBook->id)->onlyTrashed()->latest();

        if ($request->filled('daterange')) {
            $dates = explode(' - ', $request->daterange);
            if (count($dates) === 2) {
                $query->whereDate('created_at', '>=', Carbon::parse($dates[0]))
                      ->whereDate('created_at', '<=', Carbon::parse($dates[1]));
                $trashQuery->whereDate('created_at', '>=', Carbon::parse($dates[0]))
                           ->whereDate('created_at', '<=', Carbon::parse($dates[1]));
            }
        }

        if ($request->filled('note')) {
            $query->where('note', 'LIKE', '%' . $request->note . '%');
            $trashQuery->where('note', 'LIKE', '%' . $request->note . '%');
        }

        if ($request->filled('asset_cost')) {
            $query->where('asset_cost', $request->asset_cost);
            $trashQuery->where('asset_cost', $request->asset_cost);
        }

        $entries = $query->paginate(10)->withQueryString();
        $trashedEntries = $trashQuery->get();

        // Calculate min/max dates
        $minDate = Asset::where('account_book_id', $accountBook->id)->min('created_at') ?? now();
        $maxDate = Asset::where('account_book_id', $accountBook->id)->max('created_at') ?? now();
        $defaultDateRange = Carbon::parse($minDate)->format('Y-m-d') . ' - ' . Carbon::parse($maxDate)->format('Y-m-d');

        return Inertia::render('AssetBook/Show', [
            'assetBook' => $assetBook,
            'accountBook' => $accountBook,
            'entries' => $entries,
            'trashedEntries' => $trashedEntries,
            'filters' => $request->only(['daterange', 'note', 'asset_cost']),
            'defaultDateRange' => $defaultDateRange,
        ]);
    }

    /**
     * Store a newly created asset book.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:asset_books,name',
        ], [
            'name.required' => 'এসেট এর নাম প্রয়োজন।',
            'name.unique' => 'এই এসেট এর নাম ইতিমধ্যে বসানো আছে।',
        ]);

        $assetBook = AssetBook::create([
            'name' => $request->name,
        ]);

        $account = new Account;
        $account->id = $assetBook->id;
        $account->type = 'asset';
        $account->name = $assetBook->name;
        $account->save();

        $accountBook = new AccountBook;
        $accountBook->account_id = $assetBook->id;
        $accountBook->account_type = 'asset';
        $accountBook->save();

        logActivity($assetBook, $assetBook->id, 'asset-book.index', 'Create', __('Asset Book created'));

        return Redirect::back()->with('success', __('Asset Book created successfully'));
    }

    /**
     * Update the specified asset book.
     */
    public function update(Request $request, AssetBook $assetBook): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:asset_books,name,' . $assetBook->id,
        ], [
            'name.required' => 'এসেট এর নাম প্রয়োজন।',
            'name.unique' => 'এই এসেট এর নাম ইতিমধ্যে বসানো আছে।',
        ]);

        $assetBook->update([
            'name' => $request->name,
        ]);

        // Update corresponding account name
        Account::where('id', $assetBook->id)->where('type', 'asset')->update([
            'name' => $assetBook->name,
        ]);

        logActivity($assetBook, $assetBook->id, 'asset-book.index', 'Update', __('Asset Book updated'));

        return Redirect::back()->with('success', __('Asset Book updated successfully'));
    }

    /**
     * Remove the specified asset book.
     */
    public function destroy(AssetBook $assetBook): RedirectResponse
    {
        logActivity($assetBook, $assetBook->id, 'asset-book.index', 'SoftDelete', __('Asset Book deleted'), false);
        $assetBook->delete();

        return Redirect::back()->with('success', __('pages.asset_deleted'));
    }

    /**
     * Restore the specified asset book.
     */
    public function restore(int $id): RedirectResponse
    {
        $assetBook = AssetBook::onlyTrashed()->findOrFail($id);
        $assetBook->restore();

        logActivity($assetBook, $assetBook->id, 'asset-book.index', 'Restore', __('Asset Book restored'));

        return Redirect::back()->with('success', __('pages.asset_restored'));
    }

    /**
     * Force delete the specified asset book.
     */
    public function forceDelete(int $id): RedirectResponse
    {
        $assetBook = AssetBook::onlyTrashed()->findOrFail($id);

        logActivity($assetBook, $assetBook->id, 'asset-book.index', 'Delete', __('Asset Book permanently deleted'));
        $assetBook->forceDelete();

        return Redirect::back()->with('success', __('pages.asset_completely_deleted'));
    }
}
