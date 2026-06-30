<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class NoteController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:manage notes'),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $query = Note::with('user')
            ->where('user_id', auth()->id())
            ->latest();

        if ($request->filled('name')) {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }

        if ($request->filled('reference')) {
            $query->where('reference', 'LIKE', '%' . $request->reference . '%');
        }

        if ($request->filled('daterange')) {
            $dates = explode(' - ', $request->daterange);
            if (count($dates) === 2) {
                $query->whereDate('created_at', '>=', Carbon::parse($dates[0]))
                      ->whereDate('created_at', '<=', Carbon::parse($dates[1]));
            }
        }

        $notes = $query->paginate(10)->withQueryString();

        // Format dates or other structures for Vue
        $notes->getCollection()->transform(function ($note) {
            $note->formatted_created_at = dateTimeFormat($note->created_at);
            return $note;
        });

        $trashNotes = Note::with('deletedBy')
            ->onlyTrashed()
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($note) {
                $note->formatted_deleted_at = dateTimeFormat($note->deleted_at);
                return $note;
            });

        // Set default date range
        $minDate = Note::where('user_id', auth()->id())->min('created_at') ?? now();
        $maxDate = Note::where('user_id', auth()->id())->max('created_at') ?? now();
        $defaultDateRange = Carbon::parse($minDate)->format('Y-m-d') . ' - ' . Carbon::parse($maxDate)->format('Y-m-d');

        return Inertia::render('Note/Note', [
            'notes' => $notes,
            'trashNotes' => $trashNotes,
            'filters' => $request->only(['name', 'reference', 'daterange']),
            'defaultDateRange' => $defaultDateRange,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'reference' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'attachments.*' => 'file|max:10240',
        ]);

        $note = Note::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'reference' => $request->reference,
            'content' => $request->content,
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if ($file->isValid()) {
                    $note->addMedia($file)->toMediaCollection('note-attachments');
                }
            }
        }

        logActivity($note, $note->id, 'notes.index', 'Create', __('Note created successfully'));

        return Redirect::back()->with('success', __('Note created successfully'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Note $note): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'reference' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'attachments.*' => 'file|max:10240',
            'remove_media' => 'array',
            'remove_media.*' => 'integer',
        ]);

        $note->update([
            'name' => $request->name,
            'reference' => $request->reference,
            'content' => $request->content,
        ]);

        // Remove selected old attachments
        if ($request->filled('remove_media')) {
            foreach ($request->remove_media as $mediaId) {
                $mediaItem = $note->media()->where('id', $mediaId)->first();
                if ($mediaItem) {
                    $mediaItem->delete();
                }
            }
        }

        // Add new attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if ($file->isValid()) {
                    $note->addMedia($file)->toMediaCollection('note-attachments');
                }
            }
        }

        logActivity($note, $note->id, 'notes.index', 'Update', __('Note updated successfully'));

        return Redirect::back()->with('success', __('Note updated successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note): JsonResponse
    {
        $attachments = $note->getMedia('note-attachments')->map(function ($media) {
            return [
                'id' => $media->id,
                'name' => $media->file_name,
                'mime_type' => $media->mime_type,
                'size' => $media->size,
                'url' => asset('storage/' . $media->id . '/' . $media->file_name),
            ];
        });

        return response()->json([
            'id' => $note->id,
            'name' => $note->name,
            'reference' => $note->reference,
            'content' => $note->content,
            'attachments' => $attachments,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note): RedirectResponse
    {
        logActivity($note, $note->id, 'notes.index', 'SoftDelete', __('Note deleted'), false);
        $note->delete();

        return Redirect::back()->with('success', __('pages.note_deleted'));
    }

    /**
     * Restore the specified resource.
     */
    public function restore(int $id): RedirectResponse
    {
        $note = Note::onlyTrashed()->findOrFail($id);
        $note->restore();

        logActivity($note, $note->id, 'notes.index', 'Restore', __('Note restored'));

        return Redirect::back()->with('success', __('pages.note_restored'));
    }

    /**
     * Force delete the specified resource.
     */
    public function forceDelete(int $id): RedirectResponse
    {
        $note = Note::onlyTrashed()->findOrFail($id);

        if ($note->hasMedia('note-attachments')) {
            $note->clearMediaCollection('note-attachments');
        }

        logActivity($note, $note->id, 'notes.index', 'Delete', __('Note permanently deleted'));
        $note->forceDelete();

        return Redirect::back()->with('success', __('pages.note_completely_deleted'));
    }
}
