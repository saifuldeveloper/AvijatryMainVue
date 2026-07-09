<?php

namespace App\Repositories;

use App\Models\AdjustmentEntry;
use App\Models\Inventory;
use App\Models\InventoryCheck;
use App\Models\View\InventoryCheckDetail;
use Illuminate\Support\Facades\DB;

class InventoryCheckRepository
{
    public function store(array $data)
    {
        $inventoryCheck = InventoryCheck::create($data);
        logActivity($inventoryCheck, $inventoryCheck->id, 'inventory-check.show', 'Create', __('Inventory check started'));
        return $inventoryCheck;
    }

    public function show($id)
    {
        $inventoryCheck = InventoryCheck::findOrFail($id);
        return $inventoryCheck;
    }

    public function getRemainingDataQuery($id)
    {
        $checkedInventoryCodes = InventoryCheckDetail::where('inventory_check_id', $id)
            ->whereNotNull('id')
            ->pluck('id');
            
        return Inventory::select('inventories.*', 'shoes.code as code', 'shoes.factory_id as factory_id')
            ->join('shoes', 'inventories.shoe_id', '=', 'shoes.id')
            ->whereNull('shoes.deleted_at')
            ->where('inventories.count', '>', 0)
            ->whereNotIn('shoes.code', $checkedInventoryCodes);
    }

    public function complete($id)
    {
        $inventoryCheck = InventoryCheck::findOrFail($id);
        $inventoryCheck->complete = true;
        $inventoryCheck->save();
        logActivity($inventoryCheck, $inventoryCheck->id, 'inventory-check.show', 'Update', __('Inventory check completed'));
        return $inventoryCheck;
    }

    public function resume($id)
    {
        $inventoryCheck = InventoryCheck::findOrFail($id);
        $inventoryCheck->complete = false;
        $inventoryCheck->save();
        logActivity($inventoryCheck, $inventoryCheck->id, 'inventory-check.show', 'Update', __('Inventory check resumed'));
        return $inventoryCheck;
    }

    public function resolve(array $data, $id)
    {
        $inventoryCheck = InventoryCheck::findOrFail($id);
        $resolvedBatch = $data['resolved_batch'] ?? [];
        $isFinalChunk = $data['is_final_chunk'] ?? 0;
        
        if (!empty($resolvedBatch) && is_array($resolvedBatch)) {
            DB::beginTransaction();
            try {
                foreach ($resolvedBatch as $r) {
                    if ($r['action'] == 'over_stock' || $r['action'] == 'under_stock') {
                        $shoeKey = $r['shoe_id'];
                        $shoe = \App\Models\Shoe::where('code', $shoeKey)->first();
                        if (!$shoe) {
                            $shoe = \App\Models\Shoe::find($shoeKey);
                        }

                        if (!$shoe) {
                            continue;
                        }

                        $shoeId = $shoe->id;
                        $shoeCode = $shoe->code;

                        $inventory = Inventory::where('shoe_id', $shoeId)->first();

                        if (!$inventory) {
                            $inventory = new Inventory;
                            $inventory->shoe_id = $shoeId;
                            $inventory->factory = $shoe->factory->name ?? '';
                            $inventory->category = $shoe->category ? ($shoe->category->parent?->name . '-' . $shoe->category->name) : '';
                            $inventory->color = $shoe->color->name ?? '';
                            $inventory->purchase_price = $shoe->purchase_price;
                            $inventory->retail_price = $shoe->retail_price;
                            $inventory->count = 0;
                            $inventory->image = $shoe->image;
                            $inventory->save();
                        }

                        if ($inventory) {
                            $detail = InventoryCheckDetail::where('inventory_check_id', $inventoryCheck->id)
                                ->where('id', $shoeCode)
                                ->first();

                            if ($detail) {
                                $remaining = (float) $detail->remaining;
                                if ($remaining != 0) {
                                    $diff = abs($remaining);
                                    $type = $remaining > 0 ? 'out' : 'in';

                                    if ($type == 'in') {
                                        $inventory->count += $diff;
                                    } else {
                                        $inventory->count = max(0, $inventory->count - $diff);
                                    }
                                    $inventory->save();

                                    AdjustmentEntry::create([
                                        'shoe_id' => $shoeId,
                                        'count' => $diff,
                                        'type' => $type,
                                    ]);
                                }
                            } else {
                                // Fallback delta calculation if detail record is missing
                                $diff = $r['count'] - $inventory->count;
                                if ($diff != 0) {
                                    $inventory->count = $r['count'];
                                    $inventory->save();
                                    AdjustmentEntry::create([
                                        'shoe_id' => $shoeId,
                                        'count' => abs($diff),
                                        'type' => $diff > 0 ? 'in' : 'out',
                                    ]);
                                }
                            }
                        }
                    }
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }
        
        if ($isFinalChunk == 1) {
            $inventoryCheck->resolved = true;
            $inventoryCheck->save();
            logActivity($inventoryCheck, $inventoryCheck->id, 'inventory-check.show', 'Update', __('Inventory check resolved'));
        }
        
        return $inventoryCheck;
    }
}
