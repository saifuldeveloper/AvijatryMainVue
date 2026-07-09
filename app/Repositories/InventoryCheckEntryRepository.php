<?php

namespace App\Repositories;

use App\Models\InventoryCheckEntry;
use App\Models\View\InventoryCheckDetail;
use App\Models\Inventory;
use App\Models\Shoe;

class InventoryCheckEntryRepository
{
    public function store(array $data)
    {
        $shoeCode = $data['shoe_id'];
        $shoe = Shoe::where('code', $shoeCode)->first();
        if (!$shoe) {
            $shoe = Shoe::find($shoeCode);
        }

        if ($shoe) {
            $data['shoe_id'] = $shoe->id;
        }

        $inventoryCheckEntry = InventoryCheckEntry::create($data);
        
        $inventoryCheckDetails = InventoryCheckDetail::where('inventory_check_id', $data['inventory_check_id'])
            ->where('id', $shoeCode)
            ->first();
        
        $inventory = Inventory::find($inventoryCheckEntry->shoe_id);
        if (!$shoe) {
            $shoe = Shoe::find($inventoryCheckEntry->shoe_id);
        }

        if (!$shoe) {
            return $inventoryCheckEntry;
        }

        $inventoryCount = $inventory ? $inventory->count : 0;

        if (!$inventoryCheckDetails) {
            $inventory_details = new InventoryCheckDetail;
            $inventory_details->serial_no = $inventoryCheckEntry->id;
            $inventory_details->id = $shoe->code;
            $inventory_details->inventory_check_id = $inventoryCheckEntry->inventory_check_id;
            $inventory_details->factory = $shoe->factory->name ?? '';
            $inventory_details->category = $shoe->category->name ?? '';
            $inventory_details->color = $shoe->color->name ?? '';
            $inventory_details->retail_price = $shoe->retail_price;
            $inventory_details->purchase_price = $shoe->purchase_price;
            
            $count = InventoryCheckEntry::where('inventory_check_id', $inventoryCheckEntry->inventory_check_id)
                ->where('shoe_id', $shoe->id)
                ->get();
                
            $totalCountBreakdown = [];
            $totalCount = 0;
            foreach ($count as $item) {
                $totalCountBreakdown[] = $item->count;
                $totalCount += $item->count;
            }
            
            $totalCountString = implode('+', $totalCountBreakdown);
            $inventory_details->total_count_breakdown = $totalCountString;
            $inventory_details->remaining = $inventoryCount - $totalCount;
            $inventory_details->count = $totalCount;
            $inventory_details->image = $shoe->image;
            $inventory_details->save();
        } else {
            $inventory_details = $inventoryCheckDetails;
            $count = InventoryCheckEntry::where('inventory_check_id', $inventoryCheckEntry->inventory_check_id)
                ->where('shoe_id', $shoe->id)
                ->get();
                
            $totalCountBreakdown = [];
            $totalCount = 0;
            foreach ($count as $item) {
                $totalCountBreakdown[] = $item->count;
                $totalCount += $item->count;
            }
            
            $totalCountString = implode('+', $totalCountBreakdown);
            $inventory_details->total_count_breakdown = $totalCountString;
            $inventory_details->remaining = $inventoryCount - $totalCount;
            $inventory_details->count = $totalCount;
            $inventory_details->image = $shoe->image;
            $inventory_details->save();
        }

        return $inventoryCheckEntry;
    }
}
