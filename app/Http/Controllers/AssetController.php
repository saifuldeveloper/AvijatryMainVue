<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Asset;
use App\Models\AssetBook;
use App\Models\AccountBook;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AssetController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:manage assets'),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $query = Asset::with(['user', 'accountBook.assetAccount'])->latest();

        if ($request->filled('name')) {
            $query->whereHas('accountBook.assetAccount', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->name . '%');
            });
        }

        if ($request->filled('daterange')) {
            $dates = explode(' - ', $request->daterange);
            if (count($dates) === 2) {
                $query->whereDate('created_at', '>=', Carbon::parse($dates[0]))
                      ->whereDate('created_at', '<=', Carbon::parse($dates[1]));
            }
        }

        $assets = $query->paginate(10)->withQueryString();

        // Transform/format assets for the Vue page
        $assets->getCollection()->transform(function ($asset) {
            $asset->formatted_created_at = dateFormat($asset->created_at);
            $asset->formatted_expire_date = $asset->expire_date ? dateFormat($asset->expire_date) : '-';
            $asset->asset_type_name = $asset->accountBook?->assetAccount?->name ?? 'N/A';
            return $asset;
        });

        $trashAssets = Asset::with(['user', 'accountBook.assetAccount', 'deletedBy'])
            ->onlyTrashed()
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($asset) {
                $asset->formatted_deleted_at = dateTimeFormat($asset->deleted_at);
                $asset->asset_type_name = $asset->accountBook?->assetAccount?->name ?? 'N/A';
                return $asset;
            });

        // Set default date range bounds
        $minDate = Asset::min('created_at') ?? now();
        $maxDate = Asset::max('created_at') ?? now();
        $defaultDateRange = Carbon::parse($minDate)->format('Y-m-d') . ' - ' . Carbon::parse($maxDate)->format('Y-m-d');

        return Inertia::render('Asset/Asset', [
            'assets' => $assets,
            'trashAssets' => $trashAssets,
            'filters' => $request->only(['name', 'daterange']),
            'defaultDateRange' => $defaultDateRange,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $accountBooks = AccountBook::with('assetAccount')
            ->where('account_type', 'asset')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->assetAccount?->name ?? 'N/A',
                ];
            });

        return Inertia::render('Asset/AssetForm', [
            'accountBooks' => $accountBooks,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'account_book_id' => 'required|numeric|exists:account_books,id',
            'asset_cost' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'expire_date' => 'nullable|date',
            'note' => 'nullable|string',
            'attachments.*' => 'file|max:10240',
        ]);

        $startDate = $validated['start_date'] ?? now()->format('Y-m-d');
        $validated['created_by'] = auth()->id();
        $validated['entry_type'] = 1;
        $validated['created_at'] = $startDate;
        $validated['type'] = 'normal';

        $asset = Asset::create($validated);

        // Handle file attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if ($file->isValid()) {
                    $asset->addMedia($file)->toMediaCollection('asset-attachments');
                }
            }
        }

        $accountBook = AccountBook::find($validated['account_book_id']);
        $assetBookId = $accountBook ? $accountBook->account_id : 1;

        return Redirect::route('asset-book.show', $assetBookId)->with('success', 'অ্যাসেট সফলভাবে তৈরি হয়েছে');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): Response
    {
        $asset = Asset::with('media')->findOrFail($id);

        $accountBooks = AccountBook::with('assetAccount')
            ->where('account_type', 'asset')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->assetAccount?->name ?? 'N/A',
                ];
            });

        // Format media attachments for Vue
        $attachments = $asset->getMedia('asset-attachments')->map(function ($media) {
            return [
                'id' => $media->id,
                'name' => $media->file_name,
                'mime_type' => $media->mime_type,
                'size' => $media->size,
                'url' => asset('storage/' . $media->id . '/' . $media->file_name),
            ];
        });

        return Inertia::render('Asset/AssetForm', [
            'asset' => $asset,
            'attachments' => $attachments,
            'accountBooks' => $accountBooks,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'account_book_id' => 'required|numeric|exists:account_books,id',
            'asset_cost' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'expire_date' => 'nullable|date',
            'note' => 'nullable|string',
            'status' => 'required|boolean',
            'attachments.*' => 'file|max:10240',
            'remove_media' => 'array',
            'remove_media.*' => 'integer',
        ]);

        $asset = Asset::findOrFail($id);

        $startDate = $validated['start_date'] ?? now()->format('Y-m-d');
        $validated['created_at'] = $startDate;

        $asset->update($validated);

        // Remove selected old attachments
        if ($request->filled('remove_media')) {
            foreach ($request->remove_media as $mediaId) {
                $mediaItem = $asset->media()->where('id', $mediaId)->first();
                if ($mediaItem) {
                    $mediaItem->delete();
                }
            }
        }

        // Add new attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if ($file->isValid()) {
                    $asset->addMedia($file)->toMediaCollection('asset-attachments');
                }
            }
        }

        $accountBook = AccountBook::find($validated['account_book_id']);
        $assetBookId = $accountBook ? $accountBook->account_id : 1;

        return Redirect::route('asset-book.show', $assetBookId)->with('success', 'অ্যাসেট সফলভাবে হালনাগাদ হয়েছে');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $asset = Asset::findOrFail($id);
        logActivity($asset, $asset->id, 'assets.index', 'SoftDelete', 'অ্যাসেট তথ্য মুছে ফেলা হয়েছে', false);
        $asset->delete();

        return Redirect::back()->with('success', 'অ্যাসেট মুছে ফেলা হয়েছে');
    }

    /**
     * Restore the specified resource.
     */
    public function restore(int $id): RedirectResponse
    {
        $asset = Asset::onlyTrashed()->findOrFail($id);
        $asset->restore();

        logActivity($asset, $asset->id, 'assets.index', 'Restore', 'অ্যাসেট পুনরুদ্ধার করা হয়েছে');

        return Redirect::back()->with('success', 'অ্যাসেট পুনরুদ্ধার করা হয়েছে');
    }

    /**
     * Force delete the specified resource.
     */
    public function forceDelete(int $id): RedirectResponse
    {
        $asset = Asset::onlyTrashed()->findOrFail($id);

        if ($asset->hasMedia('asset-attachments')) {
            $asset->clearMediaCollection('asset-attachments');
        }

        logActivity($asset, $asset->id, 'assets.index', 'Delete', 'অ্যাসেট সম্পূর্ণরূপে মুছে ফেলা হয়েছে');
        $asset->forceDelete();

        return Redirect::back()->with('success', 'অ্যাসেট সম্পূর্ণরূপে মুছে ফেলা হয়েছে');
    }
}
