<?php

namespace App\Models\View;

use App\Models\PurchaseEntry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InventoryEntry extends Model
{
    use HasFactory;
    // public static methods
	public static function totalStock() {
		return self::sum('count');
	}

	public function totalQty(){
		return $this->belongsTo(PurchaseEntry::class, 'shoe_id', 'shoe_id')
                ->selectRaw('sum(count) as total_qty')
                ->groupBy('shoe_id');

	}

	public static function totalStockPurchasePrice() {
		return self::sum(DB::raw('purchase_price * count / 12'));
	}

	public static function totalStockRetailPrice() {
		return self::sum(DB::raw('retail_price * count'));
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

	public function getPreviewUrlAttribute() {
		return imageRoute($this->image, 'preview');
	}

    public function shoe()
    {
        return $this->belongsTo(\App\Models\Shoe::class, 'shoe_id');
    }

    public function getShoeCodeAttribute()
    {
        return $this->shoe->code ?? '';
    }

    public function getFactoryNameAttribute()
    {
        return $this->factory;
    }

    public function getCategoryNameAttribute()
    {
        return $this->category;
    }

    public function getColorNameAttribute()
    {
        return $this->color;
    }

    public function getFactoryIdAttribute()
    {
        return $this->shoe->factory_id ?? null;
    }

    public function getCategoryIdAttribute()
    {
        return $this->shoe->category_id ?? null;
    }

    public $incrementing = false;
    protected $primaryKey = 'shoe_id';
    protected $table = 'inventories';
    protected $appends = ['image_url', 'full_image_url', 'thumbnail_url', 'preview_url', 'shoe_code', 'factory_name', 'category_name', 'color_name', 'factory_id', 'category_id'];
}
