<?php

namespace App\Http\Controllers;

use App\Models\Shoe;
use App\Models\Size;
use App\Models\View\InventoryCheckDetail;
use App\Services\ShoeService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ShoeController extends Controller implements HasMiddleware
{
    protected $shoeService;

    public function __construct(ShoeService $shoeService)
    {
        $this->shoeService = $shoeService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:manage shoes', except: ['ajaxShow']),
        ];
    }

    public function index(Request $request)
    {
        $stats = $this->shoeService->getShoesStats($request);
        $totals = $this->shoeService->getIndexData();
        $editData = $this->shoeService->getEditData();

        return Inertia::render('Shoe/Index', [
            'shoes' => $stats->shoes,
            'stats' => [
                'count' => $stats->count,
                'total_purchase_price' => $stats->total_purchase_price,
                'total_retail_price' => $stats->total_retail_price,
                'page_count' => $stats->page_count,
                'page_total_purchase_price' => $stats->page_total_purchase_price,
                'page_total_retail_price' => $stats->page_total_retail_price,
            ],
            'totals' => $totals,
            'categories' => $editData['categories'],
            'colors' => $editData['colors'],
            'filters' => $request->only(['id', 'factory', 'category', 'color', 'retail_price', 'purchase_price', 'count', 'stock_filter', 'orderby', 'order', 'per_page']),
        ]);
    }

    public function show(Request $request, Shoe $shoe)
    {
        $shoe->load([
            'purchaseEntries.purchase.accountBook.account',
            'invoiceEntries.invoice.accountBook.retailAccount',
            'acceptedFactoryReturnEntries.accountBook.account',
            'acceptedRetailReturnEntries',
            'adjustmentEntries'
        ]);

        $shoe->waste_entries = \App\Models\WasteEntry::where('shoe_id', $shoe->id)->get();

        $editData = $this->shoeService->getEditData();

        return Inertia::render('Shoe/Show', [
            'shoe' => $shoe,
            'categories' => $editData['categories'],
            'colors' => $editData['colors'],
        ]);
    }

    public function edit(Shoe $shoe)
    {
        $shoe->load('factory', 'category', 'color');
        $editData = $this->shoeService->getEditData();

        return Inertia::render('Shoe/Edit', [
            'shoe' => $shoe,
            'categories' => $editData['categories'],
            'colors' => $editData['colors'],
        ]);
    }

    public function update(Request $request, Shoe $shoe)
    {
        $updatedShoe = $this->shoeService->updateShoe($request, $shoe);
        $shoeModel = Shoe::find($updatedShoe->id ?? $updatedShoe['id']);
        logActivity($updatedShoe, $shoeModel['id'], 'shoe.show', 'Update', __('Shoe information edited'));
        return back()->with('success-alert', __('pages.Shoe information edited'));
    }

    public function ajaxShow(Request $request, Shoe $shoe)
    {
        $details = $this->shoeService->getShoeDetails($shoe, true);

        $alreadyCounted = false;
        if ($request->filled('inventory_check_id')) {
            $alreadyCounted = InventoryCheckDetail::where('inventory_check_id', $request->inventory_check_id)
                ->where('id', $shoe->code)
                ->exists();
        }

        $responseData = $details->toArray();
        $responseData['already_counted'] = $alreadyCounted;

        return response()->json($responseData);
    }

    public function download(Request $request)
    {
        $ids = json_decode($request->input('selected_ids'), true);

        if (empty($ids)) {
            return response()->json(['success' => false, 'error' => __('pages.Shoes not selected') ?? 'কোন জুতা নির্বাচন করা হয়নি।'], 400);
        }

        $imagePaths = $this->shoeService->getImagesByIds($ids);

        if (empty($imagePaths)) {
            return response()->json(['success' => false, 'error' => __('pages.Shoes not selected') ?? 'নির্বাচিত জুতাগুলোর কোনো ছবি নেই।'], 400);
        }

        $result = $this->shoeService->createImagesZip($imagePaths);

        if ($result['success']) {
            session(['zip_file_path' => $result['zip_file_path']]);
            return response()->json([
                'success' => true,
                'file_url' => $result['zip_file_path'],
                'file_name' => $result['public_zip_file_path'],
            ]);
        } else {
            return response()->json(['success' => false, 'error' => 'Unable to create zip archive'], 500);
        }
    }

    public function downloadDeleted(Request $request)
    {
        $zipFilePath = session('zip_file_path');

        if ($this->shoeService->deleteImagesZip($zipFilePath)) {
            session()->forget('zip_file_path');
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'error' => 'File not found']);
        }
    }

    // Barcode methods from original ShoeController
    public function barcodePage()
    {
        return Inertia::render('Barcode/Page');
    }

    public function barcode(Request $request)
    {
        $entries = [];
        foreach ($request->input('entries', []) as $shoeEntry) {
            $shoe = Shoe::where('code', $shoeEntry['shoe_id'])->first();
            if ($shoe) {
                if (isset($shoeEntry['sizes'])) {
                    foreach ($shoeEntry['sizes'] as $size) {
                        $entries[] = (object) [
                            'shoe' => $shoe,
                            'size' => $size,
                            'count' => $shoeEntry['count'] ?? $size['quantity'] ?? 1,
                        ];
                    }
                } else {
                    $entries[] = (object) [
                        'shoe' => $shoe,
                        'sizes' => [],
                        'count' => $shoeEntry['count'] ?? 1,
                    ];
                }
            }
        }

        return Inertia::render('Barcode/Printer', [
            'entries' => $entries,
            'code' => $request->code
        ]);
    }

    public function barcodeTr(Request $request)
    {
        $index = $request->input('index', 0);
        return response()->json(['index' => $index]);
    }
}
