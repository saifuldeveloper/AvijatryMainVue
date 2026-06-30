<?php

namespace App\Models\View;

use App\Models\Inventory;
use App\Models\InventoryCheck;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryCheckDetail extends Model
{
    use HasFactory;
    // Relationships
    public function inventoryCheck() {
    	return $this->belongsTo(InventoryCheck::class);
    }

    // Attributes
	public function getImageUrlAttribute() {
		return imageRoute($this->image, 'small-thumbnail');
	}

	public function getFullImageUrlAttribute() {
		return imageRoute($this->image, 'original');
	}

	public function getThumbnailUrlAttribute() {
		return imageRoute($this->image, 'thumbnail');
	}
   
    public function getCurrentStockAttribute() {
        return Inventory::join('shoes', 'inventories.shoe_id', '=', 'shoes.id')
            ->where('shoes.code', $this->id)
            ->value('inventories.count') ?? 0;
    }

    protected $appends = ['current_stock'];

	public $incrementing = false;
}
