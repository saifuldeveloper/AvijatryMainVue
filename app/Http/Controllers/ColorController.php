<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ColorController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:manage colors'),
        ];
    }

    /**
     * Display colors page.
     */
    public function index(Request $request): Response
    {
        $query = Color::latest();

        if ($request->filled('name')) {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }

        $colors = $query->paginate(10)->withQueryString();
        $trashColor = Color::onlyTrashed()->latest()->get();

        return Inertia::render('Color/Color', [
            'colors' => $colors,
            'trashColor' => $trashColor,
            'filters' => $request->only(['name']),
        ]);
    }

    /**
     * Store a new color.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('colors')->whereNull('deleted_at'),
            ],
        ], [
            'name.required' => 'রং ফিল্ডটি প্রয়োজন।',
            'name.unique' => 'এই রং ইতিমধ্যে বসানো আছে।',
        ]);

        $color = Color::create([
            'name' => $request->name,
        ]);

        logActivity($color, $color->id, 'color.index', 'Create', __('New color created'));

        return Redirect::back()->with('success', __('New color created'));
    }

    /**
     * Update color.
     */
    public function update(Request $request, Color $color): RedirectResponse
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('colors')->whereNull('deleted_at')->ignore($color->id),
            ],
        ], [
            'name.required' => 'রং ফিল্ডটি প্রয়োজন।',
            'name.unique' => 'এই রং ইতিমধ্যে বসানো আছে।',
        ]);

        $color->update([
            'name' => $request->name,
        ]);

        logActivity($color, $color->id, 'color.index', 'Update', __('Color edited'));

        return Redirect::back()->with('success', __('Color edited'));
    }

    /**
     * Soft delete color.
     */
    public function destroy(Color $color): RedirectResponse
    {
        logActivity($color, $color->id, 'color.index', 'SoftDelete', __('Color deleted'), false);

        $color->delete();

        return Redirect::back()->with('success', __('pages.Color deleted'));
    }

    /**
     * Restore soft deleted color.
     */
    public function restore(int $id): RedirectResponse
    {
        $color = Color::onlyTrashed()->findOrFail($id);
        $color->restore();

        logActivity($color, $color->id, 'color.index', 'Restore', __('Color restored'));

        return Redirect::back()->with('success', __('pages.Color restored'));
    }

    /**
     * Permanently delete color.
     */
    public function forceDelete(int $id): RedirectResponse
    {
        $color = Color::onlyTrashed()->findOrFail($id);

        logActivity($color, $color->id, 'color.index', 'Delete', __('Color permanently deleted'));

        $color->forceDelete();

        return Redirect::back()->with('success', __('pages.Color permanently deleted'));
    }
}
