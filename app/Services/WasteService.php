<?php

namespace App\Services;

use App\Repositories\WasteRepository;
use Carbon\Carbon;
use App\Models\Gift;
use App\Models\WasteEntry;
use App\Models\GiftTransaction;
use App\Models\Factory;
use App\Models\GiftSupplier;
use App\Models\RetailStore;
use App\Models\Loan;
use App\Models\Employee;
use App\Models\Category;
use App\Models\Color;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;

class WasteService
{
    protected $repository;

    public function __construct(WasteRepository $repository)
    {
        $this->repository = $repository;
    }

    public function shoePage(Request $request)
    {
        $accountTypes = [
            (object) ['id' => 'factory', 'name' => 'মহাজন'],
            (object) ['id' => 'retail-store', 'name' => 'পার্টি'],
            (object) ['id' => 'gift-supplier', 'name' => 'গিফট মহাজন'],
            (object) ['id' => 'employee', 'name' => 'স্টাফ'],
            (object) ['id' => 'loan-payment', 'name' => 'হাওলাত খাতা'],
            (object) ['id' => 'other', 'name' => 'খাতা বাদে'],
        ];

        $fileraccountTypes = [
            (object) ['id' => 'factory', 'name' => 'মহাজন'],
            (object) ['id' => 'gift-supplier', 'name' => 'গিফট মহাজন'],
            (object) ['id' => 'employee', 'name' => 'স্টাফ'],
            (object) ['id' => 'loan-payment', 'name' => 'হাওলাত খাতা'],
            (object) ['id' => 'other', 'name' => 'খাতা বাদে'],
        ];
        
        $allEntries = WasteEntry::get();
        $factoryCount = $allEntries->where('entries_type', 'factory')->count();
        $retailstoreCount = $allEntries->where('entries_type', 'retail_store')->count();
        $otherCount = $allEntries->whereNotIn('entries_type', ['factory', 'retail_store'])->count();
        
        $trashedShoeWasteEntries = WasteEntry::with(['shoe', 'deletedBy'])
            ->whereNotIn('entries_type', ['retail-store', 'retail_store'])
            ->onlyTrashed()->get();

        $minDate = $allEntries->min('created_at');
        $maxDate = $allEntries->max('created_at');
        
        $dates = [
            $minDate ? Carbon::parse($minDate)->format('Y-m-d') : Carbon::now()->subMonth()->format('Y-m-d'),
            $maxDate ? Carbon::parse($maxDate)->format('Y-m-d') : Carbon::now()->format('Y-m-d'),
        ];

        // Apply filters directly
        $query = WasteEntry::with('shoe.category', 'shoe.color', 'shoe.factory', 'retailStore', 'giftSupplier', 'employee', 'loan')
            ->whereNotIn('entries_type', ['retail-store', 'retail_store'])
            ->newQuery();

        $query
            ->when($request->entries_type, fn($q) => $q->where('entries_type', $request->entries_type))
            ->when($request->account_id, fn($q) => $q->where('account_id', $request->account_id))
            ->when($request->daterange, function ($q) use ($request) {
                $dates = explode(' - ', $request->daterange);
                if (count($dates) === 2) {
                    $q->whereBetween('created_at', [
                        Carbon::parse(trim($dates[0]))->startOfDay(),
                        Carbon::parse(trim($dates[1]))->endOfDay(),
                    ]);
                }
            })
            ->when($request->shoe_id, fn($q) => $q->whereHas('shoe', fn($sq) => $sq->where('code', $request->shoe_id)))
            ->when($request->category, function ($q) use ($request) {
                $q->whereHas('shoe.category', fn($catq) => $catq->where('id', $request->category));
            })
            ->when($request->color, function ($q) use ($request) {
                $q->whereHas('shoe.color', fn($sq) => $sq->where('name', 'like', "%{$request->color}%"));
            })
            ->when($request->retail_price, function ($q) use ($request) {
                $q->whereHas('shoe', fn($sq) => $sq->where('retail_price', $request->retail_price));
            })
            ->when($request->description, fn($q) => $q->where('description', 'like', "%{$request->description}%"))
            ->when($request->factory, function ($q) use ($request) {
                $q->whereHas('shoe.factory', fn($sq) => $sq->where('id', $request->factory));
            })
            ->when($request->count, fn($q) => $q->where('count', $request->count));

        $orderColumn = $request->input('order_by', 'created_at');
        $orderDir = $request->input('order_dir', 'desc');
        
        if (in_array($orderColumn, ['created_at', 'count'])) {
            $query->orderBy($orderColumn, $orderDir);
        } else if ($orderColumn === 'retail_price') {
            $query->join('shoes', 'shoes.id', '=', 'waste_entries.shoe_id')
                  ->orderBy('shoes.retail_price', $orderDir)
                  ->select('waste_entries.*');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $perPage = $request->input('length', 10);
        $wastes = $query->paginate($perPage)->withQueryString();

        $wastes->getCollection()->transform(function ($row) {
            $row->book_type = match ($row->entries_type) {
                'retail_store' => 'পার্টি',
                'factory' => 'মহাজন',
                'gift-supplier' => 'গিফট মহাজন',
                'employee' => 'স্টাফ',
                'loan-payment' => 'হাওলাত',
                'other' => 'খাতা বাদে',
                default => '-',
            };
            $row->name = match ($row->entries_type) {
                'retail_store', 'retail-store' => ($row->retailStore->shop_name ?? '-') . ' - ' . ($row->retailStore->address ?? '-'),
                'factory' => $row->factory->name ?? '-',
                'gift-supplier' => $row->giftSupplier->name ?? '-',
                'employee' => $row->employee->name ?? '-',
                'loan-payment' => $row->loan->name ?? '-',
                'other' => 'অন্যান্য',
                default => '-',
            };
            $row->formatted_date = dateFormat($row->created_at);
            return $row;
        });

        return Inertia::render('Waste/Shoes', [
            'wastes' => $wastes,
            'filters' => $request->only(['entries_type', 'account_id', 'shoe_id', 'category', 'color', 'retail_price', 'description', 'count', 'daterange', 'order_by', 'order_dir', 'length']),
            'accountTypes' => $accountTypes,
            'fileraccountTypes' => $fileraccountTypes,
            'trashedShoeWasteEntries' => $trashedShoeWasteEntries,
            'factoryCount' => $factoryCount,
            'retailstoreCount' => $retailstoreCount,
            'otherCount' => $otherCount,
            'dates' => $dates,
            'factories' => Factory::all(),
            'retailStores' => RetailStore::all(),
            'giftSuppliers' => GiftSupplier::all(),
            'employees' => Employee::all(),
            'loans' => Loan::all(),
            'categories' => Category::where('parent_id', '!=', 0)->orderBy('id', 'asc')->get(),
            'colors' => Color::orderBy('name', 'asc')->get(),
        ]);
    }

    public function giftPage(Request $request)
    {
        $accountTypes = [
            (object) ['id' => 'factory', 'name' => 'মহাজন'],
            (object) ['id' => 'retail-store', 'name' => 'পার্টি'],
            (object) ['id' => 'gift-supplier', 'name' => 'গিফট মহাজন'],
            (object) ['id' => 'employee', 'name' => 'স্টাফ'],
            (object) ['id' => 'loan-payment', 'name' => 'হাওলাত খাতা'],
            (object) ['id' => 'other', 'name' => 'খাতা বাদে'],
        ];
        
        $gifts = Gift::all();
        $allEntries = GiftTransaction::where('type', 'waste')->get();
        
        $trashedWasteEntries = GiftTransaction::onlyTrashed()
            ->where('type', 'waste')
            ->whereNotIn('attachment_type', ['retail-store', 'retail_store'])
            ->with(['gift', 'deletedBy'])
            ->latest()
            ->get();

        $minDate = $allEntries->min('created_at');
        $maxDate = $allEntries->max('created_at');
        
        $dates = [
            $minDate ? Carbon::parse($minDate)->format('Y-m-d') : Carbon::now()->subMonth()->format('Y-m-d'),
            $maxDate ? Carbon::parse($maxDate)->format('Y-m-d') : Carbon::now()->format('Y-m-d'),
        ];

        // Apply filters directly
        $query = GiftTransaction::where('type', 'waste')
            ->whereNotIn('attachment_type', ['retail-store', 'retail_store'])
            ->with('gift')
            ->newQuery();

        $query
            ->when($request->daterange, function ($q) use ($request) {
                $dates = explode(' - ', $request->daterange);
                if (count($dates) === 2) {
                    $q->whereBetween('created_at', [
                        Carbon::parse(trim($dates[0]))->startOfDay(),
                        Carbon::parse(trim($dates[1]))->endOfDay(),
                    ]);
                }
            })
            ->when($request->account_type, fn($q) => $q->where('attachment_type', $request->account_type))
            ->when($request->name, fn($q) => $q->where('attachment_name', 'like', "%{$request->name}%"))
            ->when($request->gift_id, fn($q) => $q->where('gift_id', $request->gift_id))
            ->when($request->description, fn($q) => $q->where('description', 'like', "%{$request->description}%"))
            ->when($request->count, fn($q) => $q->where('count', $request->count));

        $query->orderBy('created_at', 'desc');
        
        $perPage = $request->input('length', 10);
        $giftWastes = $query->paginate($perPage)->withQueryString();

        $giftWastes->getCollection()->transform(function ($row) {
            $row->formatted_date = dateFormat($row->created_at);
            $row->book_type = match ($row->attachment_type) {
                'factory' => 'মহাজন',
                'retail-store' => 'পার্টি',
                'gift-supplier' => 'গিফট মহাজন',
                'employee' => 'স্টাফ',
                'loan-payment' => 'হাওলাত',
                'other' => 'খাতা বাদে',
                default => '-',
            };
            return $row;
        });

        return Inertia::render('Waste/Gifts', [
            'giftWastes' => $giftWastes,
            'filters' => $request->only(['daterange', 'account_type', 'name', 'gift_id', 'description', 'count', 'length']),
            'accountTypes' => $accountTypes,
            'gifts' => $gifts,
            'trashedWasteEntries' => $trashedWasteEntries,
            'dates' => $dates,
            'factories' => Factory::all(),
            'retailStores' => RetailStore::all(),
            'giftSuppliers' => GiftSupplier::all(),
            'employees' => Employee::all(),
            'loans' => Loan::all(),
        ]);
    }

    public function partyWasteGift(Request $request)
    {
        $fileraccountTypes = [
            (object) ['id' => 'retail-store', 'name' => 'পার্টি'],
        ];
        
        $allEntries = WasteEntry::get();
        $minDate = $allEntries->min('created_at');
        $maxDate = $allEntries->max('created_at');
        
        $dates = [
            $minDate ? Carbon::parse($minDate)->format('Y-m-d') : Carbon::now()->subMonth()->format('Y-m-d'),
            $maxDate ? Carbon::parse($maxDate)->format('Y-m-d') : Carbon::now()->format('Y-m-d'),
        ];

        $trashedShoeWasteEntries = WasteEntry::with(['shoe', 'deletedBy'])
            ->whereIn('entries_type', ['retail-store', 'retail_store'])
            ->onlyTrashed()
            ->get();

        $trashedGiftEntries = GiftTransaction::onlyTrashed()
            ->where('type', 'waste')
            ->where('attachment_type', 'retail-store')
            ->with(['gift', 'deletedBy'])
            ->latest()
            ->get();

        // 1. Party Shoe Waste query
        $shoeQuery = WasteEntry::with(['shoe.category', 'shoe.color', 'shoe.factory', 'retailStore'])
            ->whereIn('entries_type', ['retail-store', 'retail_store'])
            ->where('inventory_count', 'instant_waste')
            ->newQuery();

        $shoeQuery
            ->when($request->retailer_name, function ($q) use ($request) {
                $q->whereHas('retailStore', function ($r) use ($request) {
                    $r->whereRaw("CONCAT(shop_name, ' - ', address) LIKE ?", ["%{$request->retailer_name}%"]);
                });
            })
            ->when($request->account_id, fn($q) => $q->where('account_id', $request->account_id))
            ->when($request->daterange, function ($q) use ($request) {
                $dates = explode(' - ', $request->daterange);
                if (count($dates) === 2) {
                    $q->whereBetween('created_at', [
                        Carbon::parse(trim($dates[0]))->startOfDay(),
                        Carbon::parse(trim($dates[1]))->endOfDay(),
                    ]);
                }
            })
            ->when($request->shoe_id, fn($q) => $q->whereHas('shoe', fn($sq) => $sq->where('code', $request->shoe_id)))
            ->when($request->category, function ($q) use ($request) {
                $q->whereHas('shoe.category', function ($catq) use ($request) {
                    $catq->where('id', $request->category)->orWhere('name', 'like', "%{$request->category}%");
                });
            })
            ->when($request->color, function ($q) use ($request) {
                $q->whereHas('shoe.color', function ($colorq) use ($request) {
                    $colorq->where('name', 'like', "%{$request->color}%");
                });
            })
            ->when($request->shoe_retail_price, function ($q) use ($request) {
                $q->whereHas('shoe', function ($shoeq) use ($request) {
                    $shoeq->where('retail_price', $request->shoe_retail_price);
                });
            })
            ->when($request->description, fn($q) => $q->where('description', 'like', "%{$request->description}%"))
            ->when($request->factory, function ($q) use ($request) {
                $q->whereHas('shoe.factory', function ($factoryq) use ($request) {
                    $factoryq->where('id', $request->factory)->orWhere('name', 'like', "%{$request->factory}%");
                });
            })
            ->when($request->count, fn($q) => $q->where('count', $request->count));

        $orderColumn = $request->input('order_by', 'created_at');
        $orderDir = $request->input('order_dir', 'desc');
        
        if (in_array($orderColumn, ['created_at', 'count'])) {
            $shoeQuery->orderBy($orderColumn, $orderDir);
        } else if ($orderColumn === 'retail_price') {
            $shoeQuery->join('shoes', 'shoes.id', '=', 'waste_entries.shoe_id')
                      ->orderBy('shoes.retail_price', $orderDir)
                      ->select('waste_entries.*');
        } else {
            $shoeQuery->orderBy('created_at', 'desc');
        }

        $perPage = $request->input('length', 10);
        $partyShoeWastes = $shoeQuery->paginate($perPage, ['*'], 'shoe_page')->withQueryString();

        $partyShoeWastes->getCollection()->transform(function ($row) {
            $row->name = ($row->retailStore->shop_name ?? '-') . ' - ' . ($row->retailStore->address ?? '-');
            $row->formatted_date = dateFormat($row->created_at);
            return $row;
        });

        // 2. Party Gift query
        $giftQuery = GiftTransaction::where('type', 'waste')
            ->where('attachment_type', 'retail-store')
            ->with(['gift', 'retailStore'])
            ->newQuery();

        $giftQuery
            ->when($request->daterange, function ($q) use ($request) {
                $dates = explode(' - ', $request->daterange);
                if (count($dates) === 2) {
                    $q->whereBetween('created_at', [
                        Carbon::parse(trim($dates[0]))->startOfDay(),
                        Carbon::parse(trim($dates[1]))->endOfDay(),
                    ]);
                }
            })
            ->when($request->gift_retailer_name, function ($q) use ($request) {
                $q->whereHas('retailStore', function ($r) use ($request) {
                    $r->whereRaw("CONCAT(shop_name, ' - ', address) LIKE ?", ["%{$request->gift_retailer_name}%"]);
                });
            })
            ->when($request->gift_name, function ($q) use ($request) {
                $q->whereHas('gift', function ($g) use ($request) {
                    $g->where('name', 'like', "%{$request->gift_name}%");
                });
            })
            ->when($request->gift_details, fn($q) => $q->where('description', 'like', "%{$request->gift_details}%"))
            ->when($request->count, fn($q) => $q->where('count', $request->count));

        $giftQuery->orderBy('created_at', 'desc');

        $partyGifts = $giftQuery->paginate($perPage, ['*'], 'gift_page')->withQueryString();

        $partyGifts->getCollection()->transform(function ($row) {
            $row->party_name = $row->retailStore ? $row->retailStore->shop_name . ' - ' . $row->retailStore->address : '-';
            $row->formatted_date = dateFormat($row->created_at);
            return $row;
        });

        return Inertia::render('Waste/PartyWaste', [
            'partyShoeWastes' => $partyShoeWastes,
            'partyGifts' => $partyGifts,
            'filters' => $request->only(['retailer_name', 'shoe_id', 'category', 'color', 'description', 'shoe_retail_price', 'count', 'daterange', 'gift_retailer_name', 'gift_name', 'gift_details', 'length']),
            'fileraccountTypes' => $fileraccountTypes,
            'dates' => $dates,
            'trashedShoeWasteEntries' => $trashedShoeWasteEntries,
            'trashedGiftEntries' => $trashedGiftEntries,
            'retailStores' => RetailStore::all(),
            'categories' => Category::where('parent_id', '!=', 0)->orderBy('id', 'asc')->get(),
            'colors' => Color::orderBy('name', 'asc')->get(),
        ]);
    }

    public function shoe(Request $request)
    {
        try {
            $wasteEntry = $this->repository->storeShoe($request->all());
            logActivity($wasteEntry, $wasteEntry->id, 'waste.shoes-page', 'Create', __('Shoe waste information saved'));
            return Redirect::back()->with('success-alert', __('pages.Shoe waste information saved'));
        } catch (\Exception $e) {
            return Redirect::back()->with('error-alert', $e->getMessage());
        }
    }

    public function gift(Request $request)
    {
        try {
            $giftTransaction = $this->repository->storeGift($request->all());
            logActivity($giftTransaction, $giftTransaction->id, 'waste.gifts-page', 'Create', __('Gift waste information saved'));
            return Redirect::back()->with('success-alert', __('pages.Gift waste information saved'));
        } catch (\Exception $e) {
            return Redirect::back()->with('error-alert', $e->getMessage());
        }
    }

    public function wasteShoetSoftDelete($id)
    {
        $message = $this->repository->softDeleteShoe($id);
        return Redirect::back()->with('success-alert', $message['success']);
    }

    public function wasteShoeRestore($id)
    {
        $message = $this->repository->restoreShoe($id);
        return Redirect::back()->with('success-alert', $message['success']);
    }

    public function wasteShoeForceDelete($id)
    {
        $message = $this->repository->forceDeleteShoe($id);
        return Redirect::back()->with('success-alert', $message['success']);
    }

    public function wasteGiftSoftDelete($id)
    {
        $message = $this->repository->softDeleteGift($id);
        return Redirect::back()->with('success-alert', $message['success']);
    }

    public function wasteGiftRestore($id)
    {
        $message = $this->repository->restoreGift($id);
        return Redirect::back()->with('success-alert', $message['success']);
    }

    public function wasteGiftForceDelete($id)
    {
        $message = $this->repository->forceDeleteGift($id);
        return Redirect::back()->with('success-alert', $message['success']);
    }
}
