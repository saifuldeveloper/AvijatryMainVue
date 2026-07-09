<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Color;
use App\Models\Inventory;
use App\Models\Shoe;
use App\Models\Size;
use App\Models\View\FactoryAccountEntry;
use App\Models\View\InventoryEntry;
use Illuminate\Support\Facades\DB;

class ShoeRepository
{
    public function getShoesStats($request)
    {
        $orderby = $request->input('orderby', 'id');
        $order = $request->input('order', 'desc');

        $query = InventoryEntry::with('shoe');

        if ($orderby === 'id') {
            $query->orderBy('shoe_id', $order);
        } else {
            $query->orderBy($orderby, $order);
        }

        if ($request->filled('id')) {
            $query->join('shoes', 'shoes.id', '=', 'inventories.shoe_id')
                  ->where('shoes.code', 'like', '%' . $request->input('id') . '%');
        }
        if ($request->filled('factory')) {
            $query->where('factory', 'like', '%' . $request->input('factory') . '%');
        }
        if ($request->filled('category')) {
            $query->where('category', 'like', '%' . $request->input('category') . '%');
        }
        if ($request->filled('color')) {
            $query->where('color', 'like', '%' . $request->input('color') . '%');
        }
        if ($request->filled('retail_price')) {
            $query->where('retail_price', doubleval($request->input('retail_price')));
        }
        if ($request->filled('purchase_price')) {
            $query->where('purchase_price', doubleval($request->input('purchase_price')));
        }
        if ($request->filled('count')) {
            $query->where('count', intval($request->input('count')));
        }
        if ($request->boolean('has_stock') || $request->stock_filter == '1') {
            $query->where('count', '>', 0);
        }

        $statsQuery = clone $query;
        $perPage = intval($request->input('per_page', 10));
        if (!in_array($perPage, [10, 25, 30, 50, 100, 200])) {
            $perPage = 10;
        }
        $pagination = $query->paginate($perPage);

        $page_count = $pagination->sum('count');
        $page_total_purchase_price = $pagination->sum(function ($item) {
            return $item->purchase_price * $item->count / 12;
        });
        $page_total_retail_price = $pagination->sum(function ($item) {
            return $item->retail_price * $item->count;
        });

        // Optimize: Calculate all 3 sums in a single database query instead of 3 separate queries
        $totals = $statsQuery->selectRaw('
            SUM(count) as total_count,
            SUM(purchase_price * count / 12) as total_purchase_price,
            SUM(retail_price * count) as total_retail_price
        ')->first();

        return (object) [
            'shoes' => $pagination,
            'count' => $totals->total_count ?? 0,
            'total_purchase_price' => $totals->total_purchase_price ?? 0,
            'total_retail_price' => $totals->total_retail_price ?? 0,
            'page_count' => $page_count,
            'page_total_purchase_price' => $page_total_purchase_price,
            'page_total_retail_price' => $page_total_retail_price,
        ];
    }

    public function getShoeDetails(Shoe $shoe, $isAjax = false)
    {
        if (!$isAjax) {
            $shoe->load('purchaseEntries', 'invoiceEntries', 'acceptedFactoryReturnEntries', 'acceptedRetailReturnEntries', 'adjustmentEntries');
        }
        $shoe->cal = $shoe->shoeToSizeCalculation();
        $shoe->size = Size::where('category_id', $shoe->category_id)->get();
        return $shoe;
    }

    public function updateShoe(Shoe $shoe, array $data)
    {
        $shoe->fill($data);
        if ($shoe->save()) {
            $shoe->load(['factory', 'category', 'color']);
            $inventory = Inventory::where('shoe_id', $shoe->id)->first();
            if ($inventory) {
                $inventory->factory = $shoe->factory->name;
                $inventory->category = $shoe->category->parent->name . '-' . $shoe->category->name;
                $inventory->color = $shoe->color->name;
                $inventory->purchase_price = $shoe->purchase_price;
                $inventory->retail_price = $shoe->retail_price;
                $inventory->image = $shoe->image;
                $inventory->save();

                $entry = FactoryAccountEntry::where('purchase_id', $inventory->purchase_id)->first();
                if ($entry) {
                    $entry->purchase_price += $inventory->purchase_price;
                    $entry->total_amount += $inventory->purchase_price;
                    $entry->save();
                }
            }
        }

        return $shoe;
    }

    public function getShoesDataQuery($request)
    {
        $inventory = Inventory::select(
            'inventories.*',
            'shoes.code as shoe_code',
            'shoes.factory_id',
            'shoes.category_id',
            'shoes.color_id',
            'factories.name as factory_name',
            // 'categories.name as category_name',
            'colors.name as color_name',
            // এখানে চেক করা হচ্ছে parent category আছে কিনা। থাকলে CONCAT করবে, না থাকলে শুধু নিজের নাম দেখাবে।
            DB::raw("CASE
                WHEN parents.name IS NOT NULL THEN CONCAT(parents.name, '-', categories.name)
                ELSE categories.name
            END as category_name")

        )
            ->leftJoin('shoes', 'shoes.id', '=', 'inventories.shoe_id')
            ->leftJoin('factories', 'factories.id', '=', 'shoes.factory_id')
            ->leftJoin('categories', 'categories.id', '=', 'shoes.category_id')
            // parent category-র নাম নিয়ে আসার জন্য self-join করা হয়েছে
            ->leftJoin('categories as parents', 'parents.id', '=', 'categories.parent_id')
            ->leftJoin('colors', 'colors.id', '=', 'shoes.color_id');

        if ($request->has('order')) {
            $orderColumnIndex = $request->input('order.0.column');
            $orderDir = $request->input('order.0.dir');
            $columnName = $request->input("columns.$orderColumnIndex.name");

            $columnMapping = [
                'id' => 'shoes.code',
                'factory_id' => 'shoes.factory_id',
                'category_id' => 'shoes.category_id',
                'color_id' => 'shoes.color_id',
                'retail_price' => 'inventories.retail_price',
                'purchase_price' => 'inventories.purchase_price',
                'count' => 'inventories.count',
                'created_at' => 'inventories.created_at'
            ];

            if (array_key_exists($columnName, $columnMapping)) {
                $inventory->orderBy($columnMapping[$columnName], $orderDir);
            } else {
                $inventory->orderBy('inventories.created_at', 'desc');
            }
        } else {
            $inventory->orderBy('inventories.created_at', 'desc');
        }

        $inventory->when($request->filled('id'), fn($q) => $q->where('shoes.code', 'like', '%' . $request->id . '%'));
        $inventory->when($request->filled('factory'), fn($q) => $q->where('factories.name', 'like', '%' . $request->factory . '%'));
        // $inventory->when($request->filled('category'), fn($q) => $q->where('categories.name', 'like', '%' . $request->category . '%'));
        // ক্যাটাগরি সার্চের লজিক আপডেট করা হয়েছে
        // $inventory->when($request->filled('category'), function($q) use ($request) {
        //     $search = $request->category;
        //     $q->where(function($subQuery) use ($search) {
        //         $subQuery->where('categories.name', 'like', '%' . $search . '%')
        //                 ->orWhere('parents.name', 'like', '%' . $search . '%')
        //                 ->orWhere(DB::raw("CONCAT(parents.name, '-', categories.name)"), 'like', '%' . $search . '%');
        //     });
        // });

        // হাই-পারফরম্যান্স ক্যাটাগরি সার্চ
        $inventory->when($request->filled('category'), function($q) use ($request) {
            $search = trim($request->category);
            $q->where(function($subQuery) use ($search) {
                if (str_contains($search, '-')) {
                    $parts = explode('-', $search);
                    $parentSearch = trim($parts[0]);
                    $childSearch = trim($parts[1]);

                    $subQuery->where('parents.name', 'like', '%' . $parentSearch . '%')
                            ->where('categories.name', 'like', '%' . $childSearch . '%');
                } else {
                    $subQuery->where('categories.name', 'like', '%' . $search . '%')
                            ->orWhere('parents.name', 'like', '%' . $search . '%');
                }
            });
        });
        $inventory->when($request->filled('color'), fn($q) => $q->where('colors.name', 'like', '%' . $request->color . '%'));
        $inventory->when($request->filled('count'), fn($q) => $q->where('inventories.count', $request->count));
        $inventory->when($request->filled('retail_price'), fn($q) => $q->where('inventories.retail_price', $request->retail_price));
        $inventory->when($request->filled('purchase_price'), fn($q) => $q->where('inventories.purchase_price', $request->purchase_price));
        $inventory->when($request->stock_filter == '1', fn($q) => $q->where('inventories.count', '>', 0));
        return $inventory;
    }

    public function getImagesByIds(array $ids): array
    {
        return Shoe::whereIn('id', $ids)->whereNotNull('image')->where('image', '!=', '')->pluck('image')->toArray();
    }

    public function getInventoryTotals()
    {
        return Inventory::selectRaw('
            SUM(count) as total_count,
            SUM(purchase_price * count / 12) as total_weighted_purchase_price,
            SUM(retail_price * count) as total_weighted_retail_price
        ')->first();
    }

    public function getSubCategories()
    {
        return Category::where('parent_id', '!=', 0)->get();
    }

    public function getColors()
    {
        return Color::get();
    }
}
