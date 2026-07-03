<?php

namespace App\Services;

use App\Models\Gift;
use App\Models\Shoe;
use App\Models\Factory;
use App\Models\Purchase;
use App\Models\BankAccount;
use App\Models\Category;
use App\Models\Color;
use App\Models\PurchaseEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Repositories\PurchaseRepository;
use Inertia\Inertia;

class PurchaseService
{
    protected PurchaseRepository $repository;

    public function __construct(PurchaseRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create()
    {
        $memoNo = Purchase::getNextId();
        $nextShoe = Shoe::getNextId();
        $bankAccount = BankAccount::all();
        $bankAccounts = new Collection();
        foreach ($bankAccount as $item) {
            if ($item->bank === 'ক্যাশ') {
                $bankAccounts->push((object) ['id' => $item->id, 'name' => 'ক্যাশ']);
            } else {
                $bankAccounts->push((object) ['id' => $item->id, 'name' => $item->bank . ' - ' . $item->branch . ' - (' . $item->account_no . ')']);
            }
        }
        $bankAccounts->push((object) ['id' => 'cheque', 'name' => 'চেক']);

        $categories = Category::where('parent_id', '!=', 0)->orderBy('id', 'asc')->get();
        $colors = Color::orderBy('name', 'asc')->get();
        $boxes = Gift::where('gift_type_id', '1')->get();
        $bags = Gift::where('gift_type_id', '2')->get();
        $factories = Factory::all();

        return Inertia::render('Purchase/Create', compact('memoNo', 'nextShoe', 'bankAccounts', 'categories', 'colors', 'boxes', 'bags', 'factories'));
    }

    public function store(Request $request)
    {
        if ($request->factory_id == null) {
            return redirect()->route('purchase.create')->with('info-alert', __('pages.Invalid factory'));
        }

        $data = $request->all();

        foreach ($data['purchases'] ?? [] as $i => $row) {
            if (isset($row['category_id']) && $request->hasFile("purchases.{$i}.image")) {
                $uploadedFile = $request->file("purchases.{$i}.image");
                if ($uploadedFile && $uploadedFile->isValid()) {
                    $extension = $uploadedFile->getClientOriginalExtension();
                    $imageName = $row['shoe_id'] . '_' . uniqid() . '.' . $extension;
                    $uploadedFile->move(public_path('images/small-thumbnail'), $imageName);
                    $data['purchases'][$i]['image'] = $imageName;
                }
            }
        }

        $purchase = $this->repository->store($data);
        logActivity($purchase, $purchase->id, 'purchase.show', 'Create', __('Shoes purchased'));

        return redirect()->route('purchase.show', ['purchase' => $purchase->id])->with('success-alert', __('pages.Shoe purchase completed'));
    }

    public function show($id)
    {
        $purchase = Purchase::with(['accountBook.account', 'purchaseEntries.shoe.category', 'purchaseEntries.shoe.color'])->findOrFail($id);
        
        // Fetch size data for each entry
        foreach ($purchase->purchaseEntries as $entry) {
            $entry->sizes_data = \App\Models\ShoeToSize::where([
                'shoe_id' => $entry->shoe_id,
                'type' => 'purchase',
                'reference_id' => $purchase->id
            ])->with('size')->get();
        }

        return Inertia::render('Purchase/Show', compact('purchase'));
    }

    public function barcode($id)
    {
        $purchase = Purchase::find($id);
        if (!$purchase) {
            return redirect()->back()->with('error', 'Purchase not found.');
        }
        $purchaseEntries = PurchaseEntry::where('purchase_id', $purchase->id)->get();
        return view('barcode.barcode_printer', ['entries' => $purchaseEntries, 'memo' => $purchase->id]);
    }

    public function edit(Purchase $purchase)
    {
        $purchase->load(['accountBook.account', 'purchaseEntries.shoe.category', 'purchaseEntries.shoe.color']);
        
        // Fetch size data for each entry
        foreach ($purchase->purchaseEntries as $entry) {
            $entry->sizes_data = \App\Models\ShoeToSize::where([
                'shoe_id' => $entry->shoe_id,
                'type' => 'purchase',
                'reference_id' => $purchase->id
            ])->get();
        }

        $nextShoe = Shoe::getNextId();
        $boxes = Gift::where('gift_type_id', '1')->get();
        $bags = Gift::where('gift_type_id', '2')->get();

        $bankAccount = BankAccount::all();
        $bankAccounts = new Collection();
        foreach ($bankAccount as $item) {
            if ($item->bank === 'ক্যাশ') {
                $bankAccounts->push((object) ['id' => $item->id, 'name' => 'ক্যাশ']);
            } else {
                $bankAccounts->push((object) ['id' => $item->id, 'name' => $item->bank . ' - ' . $item->branch . ' - (' . $item->account_no . ')']);
            }
        }
        $bankAccounts->push((object) ['id' => 'cheque', 'name' => 'চেক']);

        $categories = Category::where('parent_id', '!=', 0)->orderBy('id', 'asc')->get();
        $colors = Color::orderBy('name', 'asc')->get();
        $factories = Factory::all();

        return Inertia::render('Purchase/Edit', compact('purchase', 'nextShoe', 'boxes', 'bags', 'bankAccounts', 'categories', 'colors', 'factories'));
    }

    public function update(Request $request, Purchase $purchase)
    {
        $data = $request->all();

        foreach ($data['purchases'] ?? [] as $i => $row) {
            if (isset($row['category_id']) && $request->hasFile("purchases.{$i}.image")) {
                $uploadedFile = $request->file("purchases.{$i}.image");
                if ($uploadedFile && $uploadedFile->isValid()) {
                    $extension = $uploadedFile->getClientOriginalExtension();
                    $imageName = $row['shoe_id'] . '_' . uniqid() . '.' . $extension;
                    $destinationPath = public_path('images/small-thumbnail');
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0777, true);
                    }
                    $uploadedFile->move($destinationPath, $imageName);
                    $data['purchases'][$i]['image'] = $imageName;
                }
            }
        }

        $purchase = $this->repository->update($purchase, $data);
        logActivity($purchase, $purchase->id, 'purchase.show', 'Update', __('Shoe purchase receipt edited'));

        return redirect()->route('purchase.show', ['purchase' => $purchase->id])->with('success-alert', __('pages.Shoe purchase receipt edited'));
    }

    public function destroy(Purchase $purchase)
    {
        $response = $this->repository->destroy($purchase);
        return redirect()->route('purchase.create')->with('success-alert', $response['success']);
    }

    public function forceDelete($id)
    {
        $response = $this->repository->forceDelete($id);
        if (isset($response['error'])) {
            abort(404, $response['error']);
        }
        return redirect()->route('account-book.show', $response['accountBook']->id)->with('success-alert', $response['success']);
    }

    public function restore($id)
    {
        $purchase = $this->repository->restore($id);
        if ($purchase) {
            return redirect()->route('account-book.show', $purchase->account_book_id)->with('success-alert', __('pages.Purchase restored'));
        }
        return redirect()->back();
    }
}
