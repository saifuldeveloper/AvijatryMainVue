<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Account;
use App\Models\Liabilitie;
use App\Models\AccountBook;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\LiabilitiesEntry;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class LiabilitiesController extends Controller
{
    public function __construct()
    {
        // Permission check can be disabled or configured as per application standard
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $trashLiabilities = Liabilitie::onlyTrashed()->with('deletedBy')->get();

        $query = Liabilitie::query()
            ->when($request->input('name'), function ($q, $name) {
                $q->where('name', 'like', '%' . $name . '%');
            });

        $liabilities = $query->latest()->paginate(10)->withQueryString();
        $accountBooks = AccountBook::with('liabilityAccount')->where('account_type', 'liability')->get();

        return Inertia::render('Liability/Index', [
            'liabilities' => $liabilities,
            'trashLiabilities' => $trashLiabilities,
            'accountBooks' => $accountBooks,
            'filters' => $request->only(['name']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:liabilities,name',
            'mobile_no' => 'nullable|string',
        ], [
            'name.required' => ' দেনা খাতা ফিল্ডটি নাম প্রয়োজন।',
            'name.unique' => 'ইতিমধ্যে দেনা খাতা এই নাম বসানো আছে।',
        ]);

        $liabilitie = new Liabilitie;
        $liabilitie->fill($request->all());
        $liabilitie->save();

        $account = new Account;
        $account->id = $liabilitie->id;
        $account->type = 'liability';
        $account->name = $liabilitie->name;
        $account->save();

        $accountBook = new AccountBook;
        $accountBook->account_id = $liabilitie->id;
        $accountBook->account_type = 'liability';
        $accountBook->save();

        logActivity($liabilitie, $liabilitie->id, 'liabilities.index', 'Create', __('Liabilitie Account Book Created'));
        return back()->with('success-alert', __('pages.Liabilitie Account Book Created') ?? 'Liability Account Book Created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Liabilitie $liability)
    {
        $accountBook = $liability->getCurrentAccountBook();

        if (!$accountBook) {
            $accountBook = new AccountBook;
            $accountBook->account_id = $liability->id;
            $accountBook->account_type = 'liability';
            $accountBook->save();
        }

        $entryQuery = LiabilitiesEntry::where('account_book_id', $accountBook->id);
        $trashQuery = LiabilitiesEntry::onlyTrashed()->where('account_book_id', $accountBook->id);

        if ($request->input('daterange')) {
            $dates = explode(' - ', $request->input('daterange'));
            if (count($dates) == 2) {
                $start = Carbon::parse($dates[0])->startOfDay();
                $end = Carbon::parse($dates[1])->endOfDay();
                $entryQuery->whereBetween('created_at', [$start, $end]);
                $trashQuery->whereBetween('created_at', [$start, $end]);
            }
        }

        if ($request->input('description')) {
            $desc = $request->input('description');
            $entryQuery->where('description', 'like', '%' . $desc . '%');
            $trashQuery->where('description', 'like', '%' . $desc . '%');
        }

        if ($request->input('total_amount')) {
            $amt = $request->input('total_amount');
            $entryQuery->where('total_amount', $amt);
            $trashQuery->where('total_amount', $amt);
        }

        // Get min/max dates for filter
        $minDate = LiabilitiesEntry::where('account_book_id', $accountBook->id)->min('created_at');
        $maxDate = LiabilitiesEntry::where('account_book_id', $accountBook->id)->max('created_at');
        $defaultDateRange = '';
        if ($minDate && $maxDate) {
            $defaultDateRange = Carbon::parse($minDate)->format('Y-m-d') . ' - ' . Carbon::parse($maxDate)->format('Y-m-d');
        }

        $entries = $entryQuery->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $trashedEntries = $trashQuery->orderBy('deleted_at', 'desc')->paginate(10)->withQueryString();

        return Inertia::render('Liability/Show', [
            'liability' => $liability,
            'accountBook' => $accountBook,
            'entries' => $entries,
            'trashedEntries' => $trashedEntries,
            'defaultDateRange' => $defaultDateRange,
            'filters' => $request->only(['daterange', 'description', 'total_amount']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Liabilitie $liability)
    {
        $request->validate([
            'name' => [
                'required',
                Rule::unique('liabilities', 'name')->ignore($liability->id),
            ],
            'mobile_no' => 'nullable|string',
        ], [
            'name.required' => 'নাম দেওয়া আবশ্যক।',
            'name.unique' => 'ইতিমধ্যে খাতা এই নাম বসানো আছে।',
        ]);

        $liability->fill($request->all());
        $liability->save();

        // Update corresponding account name
        Account::where('id', $liability->id)->where('type', 'liability')->update([
            'name' => $liability->name,
        ]);

        logActivity($liability, $liability->id, 'liabilities.index', 'Update', __('Liabilitie Account Book Edited'));
        return back()->with('success-alert', __('pages.Liabilitie Account Book Edited') ?? 'Liability Account Book Edited successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Liabilitie $liability)
    {
        logActivity($liability, $liability->id, 'liabilities.index', 'SoftDelete', __('liabilitie Book Deleted'), false);
        $liability->delete();
        return back()->with('success-alert', __('pages.liabilitie Book Deleted') ?? 'Liability Book Deleted');
    }

    public function restore($id)
    {
        $liabilitie = Liabilitie::onlyTrashed()->findOrFail($id);
        logActivity($liabilitie, $liabilitie->id, 'liabilities.index', 'Restore', __('liabilites Account Book Restored'));
        $liabilitie->restore();
        return back()->with('success-alert', __('pages.liabilites Account Book Restored') ?? 'Liability Account Book Restored');
    }

    public function forceDelete($id)
    {
        $liabilitie = Liabilitie::onlyTrashed()->findOrFail($id);
        logActivity($liabilitie, $liabilitie->id, 'liabilities.index', 'Delete', __('liabilities Book Permanently Deleted'));
        $liabilitie->forceDelete();
        return back()->with('success-alert', __('pages.liabilities Book Permanently Deleted') ?? 'Liability Book Permanently Deleted');
    }

    public function getLiabilities(Request $request)
    {
        $searchTerm = $request->input('search');
        $liabilities = Liabilitie::when($searchTerm, function ($query, $searchTerm) {
            return $query->where('name', 'like', '%' . $searchTerm . '%')
                ->orWhere('mobile_no', 'like', '%' . $searchTerm . '%');
        })->latest()->get();

        return response()->json([
            "status" => true,
            "data" => $liabilities
        ]);
    }

    /* =================== Liabilities Entry CRUD =================== */

    public function entry()
    {
        $accountBooks = AccountBook::with('liabilityAccount')->where('account_type', 'liability')->get();
        return Inertia::render('Liability/EntryForm', [
            'accountBooks' => $accountBooks,
            'liability' => null,
        ]);
    }

    public function entryEdit($id)
    {
        $liability = LiabilitiesEntry::findOrFail($id);
        $accountBooks = AccountBook::with('liabilityAccount')->where('account_type', 'liability')->get();
        return Inertia::render('Liability/EntryForm', [
            'accountBooks' => $accountBooks,
            'liability' => $liability,
        ]);
    }

    public function entryStore(Request $request)
    {
        $validated = $request->validate([
            'account_book_id' => 'required|numeric|exists:account_books,id',
            'total_amount' => 'required|numeric|min:0',
            'created_at' => 'nullable|date',
            'note' => 'nullable|string',
            'attachments.*' => 'file|max:10240',
        ]);

        $startDate = $validated['created_at'] ?? now()->format('Y-m-d');

        $entry = new LiabilitiesEntry();
        $entry->entry_type = 0; // 0 for addition/entry
        $entry->account_book_id = $validated['account_book_id'];
        $entry->description = $validated['note'] ?? null;
        $entry->total_amount = $validated['total_amount'];
        $entry->created_at = $startDate;

        $paths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if ($file->isValid()) {
                    $paths[] = $file->store('liabilities/attachments', 'public');
                }
            }
        }
        if (!empty($paths)) {
            $entry->attachments = json_encode($paths);
        }

        // Get the liability account to associate with entry_id
        $accountBook = AccountBook::find($validated['account_book_id']);
        if ($accountBook) {
            $entry->entry_id = $accountBook->account_id;
        }

        $entry->save();

        return redirect()->route('liabilities.show', $entry->entry_id)->with('success-alert', __('pages.Liability entry created successfully') ?? 'Liability entry created successfully');
    }

    public function entryUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'account_book_id' => 'required|numeric|exists:account_books,id',
            'total_amount' => 'required|numeric|min:0',
            'created_at' => 'nullable|date',
            'note' => 'nullable|string',
            'attachments.*' => 'file|max:10240',
        ]);

        $entry = LiabilitiesEntry::findOrFail($id);
        $startDate = $validated['created_at'] ?? $entry->created_at;

        $entry->account_book_id = $validated['account_book_id'];
        $entry->description = $validated['note'] ?? null;
        $entry->total_amount = $validated['total_amount'];
        $entry->created_at = $startDate;

        $oldFiles = json_decode($entry->attachments ?? '[]', true);
        $newFiles = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if ($file->isValid()) {
                    $newFiles[] = $file->store('liabilities/attachments', 'public');
                }
            }
        }
        $mergedFiles = array_merge($oldFiles, $newFiles);
        if (!empty($mergedFiles)) {
            $entry->attachments = json_encode($mergedFiles);
        }

        $accountBook = AccountBook::find($validated['account_book_id']);
        if ($accountBook) {
            $entry->entry_id = $accountBook->account_id;
        }

        $entry->save();

        return redirect()->route('liabilities.show', $entry->entry_id)->with('success-alert', __('pages.Liability entry updated successfully') ?? 'Liability entry updated successfully');
    }

    public function entryDestroy($id)
    {
        $entry = LiabilitiesEntry::findOrFail($id);
        $liabilityId = $entry->entry_id;
        $entry->delete();

        return back()->with('success-alert', __('pages.Liability entry deleted successfully') ?? 'Liability entry deleted successfully');
    }

    public function entryRestore($id)
    {
        $entry = LiabilitiesEntry::withTrashed()->findOrFail($id);
        $entry->restore();

        return back()->with('success-alert', __('pages.Liability entry restored successfully') ?? 'Liability entry restored successfully');
    }

    public function entryForceDelete($id)
    {
        $entry = LiabilitiesEntry::withTrashed()->findOrFail($id);

        if ($entry->attachments) {
            $files = json_decode($entry->attachments, true);
            if (is_array($files)) {
                foreach ($files as $file) {
                    if (Storage::disk('public')->exists($file)) {
                        Storage::disk('public')->delete($file);
                    }
                }
            }
        }

        $entry->forceDelete();

        return back()->with('success-alert', __('pages.Liability entry permanently deleted') ?? 'Liability entry permanently deleted');
    }
}
