<?php

namespace App\Http\Controllers;

use App\Models\Size;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SizeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:manage sizes'),
        ];
    }

    /**
     * Display sizes page.
     */
    public function index(Request $request): Response
    {
        $query = Size::with('category');

        if ($request->filled('category')) {
            $catId = $request->category;
            $query->where(function ($q) use ($catId) {
                $q->where('category_id', $catId)
                  ->orWhereHas('category', function ($subQ) use ($catId) {
                      $subQ->where('parent_id', $catId);
                  });
            });
        }

        if ($request->filled('size')) {
            $search = $request->size;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhereHas('category', function ($catQ) use ($search) {
                      $catQ->where('name', 'LIKE', '%' . $search . '%');
                  });
            });
        }

        $sizes = $query->orderBy('category_id')
            ->orderBy('name')
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $trashSize = Size::with('category')->onlyTrashed()->latest()->get();
        $categories = Category::where('parent_id', '!=', 0)->latest()->get();
        $parentscategories = Category::where('parent_id', 0)->latest()->get();

        return Inertia::render('Size/Size', [
            'sizes' => $sizes,
            'trashSize' => $trashSize,
            'categories' => $categories,
            'parentscategories' => $parentscategories,
            'filters' => $request->only(['category', 'size']),
        ]);
    }

    /**
     * Store a new size.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sizes')->where(function ($query) use ($request) {
                    return $query->where('category_id', $request->category_id)->whereNull('deleted_at');
                }),
            ],
            'category_id' => 'required|exists:categories,id',
        ], [
            'name.required' => __('pages.The size field is required'),
            'name.unique' => __('pages.This size is already set to this type'),
        ]);

        $size = Size::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
        ]);

        logActivity($size, $size->id, 'size.index', 'Create', __('New size created'));

        return Redirect::back()->with('success', __('New size created'));
    }

    /**
     * Update size.
     */
    public function update(Request $request, Size $size): RedirectResponse
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sizes')->where(function ($query) use ($request) {
                    return $query->where('category_id', $request->category_id)->whereNull('deleted_at');
                })->ignore($size->id),
            ],
            'category_id' => 'required|exists:categories,id',
        ], [
            'name.required' => __('pages.The size field is required'),
            'name.unique' => __('pages.This size is already set to this type'),
        ]);

        $size->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
        ]);

        logActivity($size, $size->id, 'size.index', 'Update', __('Size edited'));

        return Redirect::back()->with('success', __('Size edited'));
    }

    /**
     * Soft delete size.
     */
    public function destroy(Size $size): RedirectResponse
    {
        logActivity($size, $size->id, 'size.index', 'SoftDelete', __('Size deleted'), false);

        $size->delete();

        return Redirect::back()->with('success', __('pages.Size deleted'));
    }

    /**
     * Restore soft deleted size.
     */
    public function restore(int $id): RedirectResponse
    {
        $size = Size::onlyTrashed()->findOrFail($id);
        $size->restore();

        logActivity($size, $size->id, 'size.index', 'Restore', __('Size restored'));

        return Redirect::back()->with('success', __('pages.Size restore'));
    }

    /**
     * Permanently delete size.
     */
    public function forceDelete(int $id): RedirectResponse
    {
        $size = Size::onlyTrashed()->findOrFail($id);

        logActivity($size, $size->id, 'size.index', 'Delete', __('Size permanently deleted'));

        $size->forceDelete();

        return Redirect::back()->with('success', __('pages.Size permanently deleted'));
    }

    /**
     * Get sizes for a specific category.
     */
    public function getCategorySizes(Request $request)
    {
        $sizes = Size::where('category_id', $request->category_id)->get();
        return response()->json($sizes);
    }
}
