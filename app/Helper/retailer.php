<?php

use App\Models\View\RetailStoreAccountEntry;
use App\Models\ReturnFromRetailEntry;


if (!function_exists('retailReturnGrossAmount')) {
    function retailReturnGrossAmount($accountBookId, $start, $end)
    {
        $invoiceIds = RetailStoreAccountEntry::whereBetween('created_at', [$start, $end])
            ->where('account_book_id', $accountBookId)
            ->where('entry_type', 0)
            ->pluck('invoice_id')
            ->unique();

        $returnData = ReturnFromRetailEntry::whereIn('invoice_id', $invoiceIds)
            ->leftJoin('shoes', 'shoes.id', '=', 'return_from_retail_entries.shoe_id')
            ->selectRaw("
                return_from_retail_entries.invoice_id,
                COALESCE(
                    SUM(
                        (CASE
                            WHEN return_from_retail_entries.retail_price = 0
                            THEN shoes.retail_price
                            ELSE return_from_retail_entries.retail_price
                        END)
                        * return_from_retail_entries.count
                    ),
                0) AS total
            ")
            ->groupBy('return_from_retail_entries.invoice_id')
            ->pluck('total');

        return $returnData->sum();
    }
}

if (!function_exists('retailReturnNetAmount')) {
    function retailReturnNetAmount($accountBookId, $start, $end)
    {
        $invoiceIds = RetailStoreAccountEntry::whereBetween('created_at', [$start, $end])
            ->where('account_book_id', $accountBookId)
            ->where('entry_type', 0)
            ->pluck('invoice_id')
            ->unique();

        $returnData = ReturnFromRetailEntry::whereIn('invoice_id', $invoiceIds)
            ->leftJoin('shoes', 'shoes.id', '=', 'return_from_retail_entries.shoe_id')
            ->selectRaw("
                return_from_retail_entries.invoice_id,
                COALESCE(
                    SUM(
                        (
                            (CASE
                                WHEN return_from_retail_entries.retail_price = 0
                                THEN shoes.retail_price
                                ELSE return_from_retail_entries.retail_price
                            END)
                            * return_from_retail_entries.count
                        )
                        -
                        (
                            (CASE
                                WHEN return_from_retail_entries.retail_price = 0
                                THEN shoes.retail_price
                                ELSE return_from_retail_entries.retail_price
                            END)
                            * return_from_retail_entries.count
                            * return_from_retail_entries.commission / 100
                        )
                    ),
                0) AS total
            ")
            ->groupBy('return_from_retail_entries.invoice_id')
            ->pluck('total');

        return $returnData->sum();
    }
}