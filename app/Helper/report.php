<?php


use App\Models\AdjustmentEntry;
use App\Models\GiftSupplierAccountEntry;
use App\Models\InvoiceEntry;
use App\Models\PurchaseEntry;
use App\Models\ReturnFromRetailEntry;
use App\Models\ReturnToFactoryEntry;
use App\Models\View\FactoryAccountEntry;
use App\Models\WasteEntry;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;









if (!function_exists('calculatePriceByDate')) {

    function calculatePriceByDate($date)
    {
        $date = Carbon::parse($date);
        $purchase = PurchaseEntry::join('shoes', 'shoes.id', '=', 'purchase_entries.shoe_id')
            ->whereDate('purchase_entries.created_at', '<=', $date)
            ->selectRaw('SUM(purchase_entries.count * shoes.purchase_price / 12) as total')
            ->value('total') ?? 0;
        $returnToFactory = ReturnToFactoryEntry::join('shoes', 'shoes.id', '=', 'return_to_factory_entries.shoe_id')
            ->where('shoes.code', 'not like', 'X%')
            ->where('status', 'accepted')
            ->where('retail_store_return_entry_id', 0)
            ->where('factory_return', 1)
            ->whereDate('return_to_factory_entries.created_at', '<=', $date)
            ->selectRaw('SUM(return_to_factory_entries.count * shoes.purchase_price / 12) as total')
            ->value('total') ?? 0;
        $invoice = InvoiceEntry::join('shoes', 'shoes.id', '=', 'invoice_entries.shoe_id')
            ->whereDate('invoice_entries.created_at', '<=', $date)
            ->selectRaw('SUM(invoice_entries.count * shoes.purchase_price / 12) as total')
            ->value('total') ?? 0;
        $waste = WasteEntry::join('shoes', 'shoes.id', '=', 'waste_entries.shoe_id')
            ->where('shoes.code', 'not like', 'X%')
            ->whereIn('entries_type', [
                'gift-supplier',
                'other',
                'employee',
                'loan-payment',
                'retail-store',
                'factory'
            ])
            ->where('inventory_count', 'instant_waste')
            ->whereDate('waste_entries.created_at', '<=', $date)
            ->selectRaw('SUM(waste_entries.count * shoes.purchase_price / 12) as total')
            ->value('total') ?? 0;

        $adjustmentIn = AdjustmentEntry::join('shoes', 'shoes.id', '=', 'adjustment_entries.shoe_id')
            ->where('type', 'in')
            ->whereDate('adjustment_entries.created_at', '<=', $date)
            ->selectRaw('SUM(abs(adjustment_entries.count) * shoes.purchase_price / 12) as total')
            ->value('total') ?? 0;

        $adjustmentOut = AdjustmentEntry::join('shoes', 'shoes.id', '=', 'adjustment_entries.shoe_id')
            ->where('type', 'out')
            ->whereDate('adjustment_entries.created_at', '<=', $date)
            ->selectRaw('SUM(abs(adjustment_entries.count) * shoes.purchase_price / 12) as total')
            ->value('total') ?? 0;

        $returnFromRetail = ReturnFromRetailEntry::join('shoes', 'shoes.id', '=', 'return_from_retail_entries.shoe_id')
            ->where('shoes.code', 'not like', 'X%')
            ->where('inventory', 1)
            ->whereDate('return_from_retail_entries.updated_at', '<=', $date)
            ->selectRaw('SUM(return_from_retail_entries.count * shoes.purchase_price / 12) as total')
            ->value('total') ?? 0;

        return
            $purchase
            - $returnToFactory
            - $invoice
            - $waste
            + $adjustmentIn
            - $adjustmentOut
            + $returnFromRetail;
    }
}






if (!function_exists('calculateRetailPriceByDate')) {

    function calculateRetailPriceByDate($date)
    {
        $date = Carbon::parse($date);

        $purchase = PurchaseEntry::query()
            ->join('shoes', 'shoes.id', '=', 'purchase_entries.shoe_id')
            ->whereDate('purchase_entries.created_at', '<=', $date)
            ->sum(DB::raw('purchase_entries.count * shoes.retail_price'));

        $returnToFactory = ReturnToFactoryEntry::query()
            ->join('shoes', 'shoes.id', '=', 'return_to_factory_entries.shoe_id')
            ->where('shoes.code', 'not like', 'X%')
            ->where('status', 'accepted')
            ->where('retail_store_return_entry_id', 0)
            ->where('factory_return', 1)
            ->whereDate('return_to_factory_entries.created_at', '<=', $date)
            ->sum(DB::raw('return_to_factory_entries.count * shoes.retail_price'));

        $invoice = InvoiceEntry::query()
            ->join('shoes', 'shoes.id', '=', 'invoice_entries.shoe_id')
            ->whereDate('invoice_entries.created_at', '<=', $date)
            ->sum(DB::raw('invoice_entries.count * shoes.retail_price'));

        $waste = WasteEntry::query()
            ->join('shoes', 'shoes.id', '=', 'waste_entries.shoe_id')
            ->where('shoes.code', 'not like', 'X%')
            ->whereIn('entries_type', [
                'gift-supplier',
                'other',
                'employee',
                'loan-payment',
                'retail-store',
                'factory'
            ])
            ->where('inventory_count', 'instant_waste')
            ->whereDate('waste_entries.created_at', '<=', $date)
            ->sum(DB::raw('waste_entries.count * shoes.retail_price'));

        $adjustmentIn = AdjustmentEntry::query()
            ->join('shoes', 'shoes.id', '=', 'adjustment_entries.shoe_id')
            ->where('type', 'in')
            ->whereDate('adjustment_entries.created_at', '<=', $date)
            ->sum(DB::raw('abs(adjustment_entries.count) * shoes.retail_price'));

        $adjustmentOut = AdjustmentEntry::query()
            ->join('shoes', 'shoes.id', '=', 'adjustment_entries.shoe_id')
            ->where('type', 'out')
            ->whereDate('adjustment_entries.created_at', '<=', $date)
            ->sum(DB::raw('abs(adjustment_entries.count) * shoes.retail_price'));

        $returnFromRetail = ReturnFromRetailEntry::query()
            ->join('shoes', 'shoes.id', '=', 'return_from_retail_entries.shoe_id')
            ->where('shoes.code', 'not like', 'X%')
            ->where('inventory', 1)
            ->whereDate('return_from_retail_entries.updated_at', '<=', $date)
            ->sum(DB::raw('return_from_retail_entries.count * shoes.retail_price'));

        return
            $purchase
            - $returnToFactory
            - $invoice
            - $waste
            + $adjustmentIn
            - $adjustmentOut
            + $returnFromRetail;
    }

}












if (!function_exists('calculateSupplierCurrentDue')) {
    function calculateSupplierCurrentDue($start, $end): float
    {
        $sumCurrentDue = FactoryAccountEntry::whereBetween('created_at', [$start, $end])
            ->whereIn('account_book_id', function ($q) use ($start, $end) {
                $q->select('account_book_id')
                    ->from('factory_account_entries')
                    ->whereBetween('created_at', [$start, $end])
                    ->where('entry_type', 0)
                    ->whereNotNull('purchase_id')
                    ->groupBy('account_book_id');
            })
            ->selectRaw('
        account_book_id,
        SUM(CASE WHEN entry_type = 0 THEN purchase_price ELSE 0 END) as total_pair_price,
        SUM(CASE WHEN entry_type = 1 THEN purchase_price ELSE 0 END) as return_shoe_price,
        SUM(CASE WHEN entry_type = 2 THEN total_amount ELSE 0 END) as paid_amount
    ')
            ->groupBy('account_book_id')
            ->get()
            ->sum(function ($row) {
                return $row->total_pair_price
                    - $row->return_shoe_price
                    - $row->paid_amount;
            });

        return round($sumCurrentDue, 2);
    }
}





if (!function_exists('calculateGiftSupplierCurrentDue')) {

    function calculateGiftSupplierCurrentDue($start, $end): float
    {
        $sumCurrentDue = 0;

        $supplierEntries = GiftSupplierAccountEntry::whereBetween('created_at', [$start, $end])
            ->where('entry_type', 0)
            ->whereNotNull('gift_purchase_id')
            ->get()
            ->unique('account_book_id')
            ->values();

        foreach ($supplierEntries as $entry) {

            $totalPairPrice = GiftSupplierAccountEntry::whereBetween('created_at', [$start, $end])
                ->where('account_book_id', $entry->account_book_id)
                ->where('entry_type', 0)
                ->sum('unit_price');

            $paidAmount = GiftSupplierAccountEntry::whereBetween('created_at', [$start, $end])
                ->where('account_book_id', $entry->account_book_id)
                ->where('entry_type', 2)
                ->sum('total_amount');

            $sumCurrentDue += ($totalPairPrice - $paidAmount);
        }

        return round($sumCurrentDue, 2);
    }
}



