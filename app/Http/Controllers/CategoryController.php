<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CategoryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:manage categories'),
        ];
    }

    /**
     * Display categories page.
     */
    public function index(): Response
    {
        $parents = Category::where('parent_id', 0)->with('children')->latest()->get();
        $parentCategory = Category::where('parent_id', 0)->latest()->get();
        $trashCategory = Category::onlyTrashed()->latest()->get();

        return Inertia::render('Category/Category', [
            'parents' => $parents,
            'parentCategory' => $parentCategory,
            'trashCategory' => $trashCategory,
        ]);
    }

    /**
     * Store a new category.
     */
    public function store(Request $request): RedirectResponse
    {
        $parentId = $request->input('parent_id', 0) ?: 0;

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->where(function ($query) use ($parentId) {
                    return $query->where('parent_id', $parentId)->whereNull('deleted_at');
                }),
            ],
            'parent_id' => 'nullable|integer',
        ], [
            'name.required' => 'জুতার ধরণ ফিল্ডটি প্রয়োজন।',
            'name.unique' => 'এই ধরণ ইতিমধ্যে বসানো আছে।',
        ]);

        $category = Category::create([
            'name' => $request->name,
            'parent_id' => $parentId,
        ]);

        logActivity($category, $category->id, 'category.index', 'Create', __('A new category has been created'));

        return Redirect::back()->with('success', __('A new category has been created'));
    }

    /**
     * Update category.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $parentId = $request->input('parent_id', 0) ?: 0;

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->where(function ($query) use ($parentId) {
                    return $query->where('parent_id', $parentId)->whereNull('deleted_at');
                })->ignore($category->id),
            ],
            'parent_id' => 'nullable|integer',
        ], [
            'name.required' => 'জুতার ধরণ ফিল্ডটি প্রয়োজন।',
            'name.unique' => 'এই ধরণ ইতিমধ্যে বসানো আছে।',
        ]);

        $category->update([
            'name' => $request->name,
            'parent_id' => $parentId,
        ]);

        logActivity($category, $category->id, 'category.index', 'Update', __('Category has been Edited'));

        return Redirect::back()->with('success', __('Category has been Edited'));
    }

    /**
     * Soft delete category.
     */
    public function destroy(Category $category): RedirectResponse
    {
        // Prevent deleting if it has active child categories
        if ($category->children()->count() > 0) {
            return Redirect::back()->with('error', __('Cannot delete category because it has subcategories.'));
        }

        logActivity($category, $category->id, 'category.index', 'SoftDelete', __('Shoe type deleted'), false);

        $category->delete();

        return Redirect::back()->with('success', __('pages.Shoe type deleted'));
    }

    /**
     * Restore soft deleted category.
     */
    public function restore(int $id): RedirectResponse
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();

        logActivity($category, $category->id, 'category.index', 'Restore', __('Shoe type restored'));

        return Redirect::back()->with('success', __('pages.Shoe type restored'));
    }

    /**
     * Permanently delete category.
     */
    public function forceDelete(int $id): RedirectResponse
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        
        logActivity($category, $category->id, 'category.index', 'Delete', __('Shoe type permanently deleted'));

        $category->forceDelete();

        return Redirect::back()->with('success', __('pages.Shoe type permanently deleted'));
    }

    /**
     * Get subcategories by parent ID (JSON helper for product dropdowns).
     */
    public function getSubCategory(Request $request)
    {
        $categories = Category::where('parent_id', $request->category_id)->get();
        return response()->json($categories);
    }
}
