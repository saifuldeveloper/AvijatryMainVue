<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Services\InvoiceService;
use App\Services\InvoiceUpdateService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class InvoiceController extends Controller implements HasMiddleware
{
    protected InvoiceService $invoiceService;
    protected InvoiceUpdateService $invoiceUpdateService;

    public function __construct(InvoiceService $invoiceService, InvoiceUpdateService $invoiceUpdateService)
    {
        $this->invoiceService = $invoiceService;
        $this->invoiceUpdateService = $invoiceUpdateService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:manage invoices'),
        ];
    }

    public function create()
    {
        return $this->invoiceService->create();
    }

    public function store(Request $request)
    {
        $sales = collect($request->sales);
        $duplicates = $sales->map(function ($item) {
            return strtolower(trim($item['shoe_id'] ?? ''));
        })->filter()->duplicates()->unique()->values();

        if ($duplicates->isNotEmpty()) {
            return back()->with('error-alert', __('pages.The same shoe has been added multiple times:') . $duplicates->implode(', ') ?? "একই জুতা আইডি একাধিকবার দেয়া হয়েছে: " . $duplicates->implode(', '));
        }

        try {
            $invoice = $this->invoiceService->store($request);
            logActivity($invoice, $invoice->id, 'invoice.show', 'Create', __('Shoes sold'));
            
            return redirect()->route('invoice.show', $invoice->id)->with('success-alert', __('pages.Invoice created successfully!') ?? 'ইনভয়েস সফলভাবে তৈরি করা হয়েছে!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error-alert', $e->getMessage());
        }
    }

    public function show(Request $request, Invoice $invoice)
    {
        $invoice = $this->invoiceService->show($request, $invoice);
        if ($request->input('view') == 'id') {
            return \Inertia\Inertia::render('Invoice/IdView', compact('invoice'));
        }
        $transactions = $invoice->transactions;
        $giftTransactions = $invoice->giftTransactions;
        return \Inertia\Inertia::render('Invoice/Show', compact('invoice', 'transactions', 'giftTransactions'));
    }

    public function edit(Invoice $invoice)
    {
        return $this->invoiceService->edit($invoice);
    }

    public function update(Request $request, Invoice $invoice)
    {
        try {
            $updatedInvoice = $this->invoiceUpdateService->update($request, $invoice);
            logActivity($invoice, $invoice->id, 'invoice.show', 'Update', __('Shoes sold edited'));
            return redirect()->route('invoice.show', $invoice->id)->with('success-alert', __('pages.Invoice updated successfully!') ?? 'ইনভয়েস সফলভাবে আপডেট করা হয়েছে!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error-alert', 'Update Error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        return $this->invoiceService->destroy($id);
    }

    public function forceDelete($id)
    {
        return $this->invoiceService->forceDelete($id);
    }

    public function restore($id)
    {
        return $this->invoiceService->restore($id);
    }
}
