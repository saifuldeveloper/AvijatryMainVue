<?php

namespace App\Models;

use App\Models\View\InventoryEntry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shoe extends Model
{
    use HasFactory, SoftDeletes;
    // Queries
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    public function getRouteKeyName()
    {
        return 'code';
    }

    public static function getNextId()
    {
        $last = self::where('code', 'not like', 'X-%')->orderByRaw('convert(conv(code, 16, 10), signed) desc')->first();
        if (!$last) {
            return '100';
        }
        $lastId = hexdec($last->code);
        $nextId = dechex($lastId + 1);
        return $nextId;
    }

    public static function getNextTrashId()
    {
        $last = self::where('code', 'like', 'X-%')->orderByRaw('convert(conv(substring(code, 3), 16, 10), signed) desc')->first();
        if (!$last) {
            return '100';
        }
        $lastId = hexdec(substr($last->code, 2));
        $nextId = dechex($lastId + 1);
        return $nextId;
    }

    // Relationships
    private function inventoryEntry()
    {
        return $this->hasOne(InventoryEntry::class, 'shoe_id');
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    // ShoeToSize
    public function shoeToSize()
    {
        return $this->hasMany(ShoeToSize::class);
    }

    public function shoeToSizeCalculation()
    {
        // Eager load the shoe to sizes relationship
        $shoeToSizeRecords = $this->shoeToSize()->get();
        $totalsBySize = [];

        foreach ($shoeToSizeRecords as $record) {
            $sizeId = $record->size_id;

            // Initialize size entry if it doesn't exist
            if (!isset($totalsBySize[$sizeId])) {
                $totalsBySize[$sizeId] = [
                    'total_quantity' => 0,
                    'sorts' => [] // Initialize sorts as an empty array
                ];
            }

            // Update total quantity and sorts based on the operation
            $this->updateTotals($totalsBySize[$sizeId], $record);
        }

        return $totalsBySize;
    }

    private function updateTotals(&$sizeTotals, $record)
    {
        $currentSorts = json_decode($record->sorts, true) ?? [];
        $operation = $record->operation;

        if ($operation == '+') {
            $sizeTotals['total_quantity'] += $record->quantity;

            foreach ($currentSorts as $index => $value) {
                $sizeTotals['sorts'][$index] = ($sizeTotals['sorts'][$index] ?? 0) + $value;
            }
        } elseif ($operation == '-') {
            $sizeTotals['total_quantity'] -= $record->quantity;

            foreach ($currentSorts as $index => $value) {
                $sizeTotals['sorts'][$index] = ($sizeTotals['sorts'][$index] ?? 0) - $value;
            }
        }

        // Ensure quantities and sorts are not negative
        $sizeTotals['total_quantity'] = max(0, $sizeTotals['total_quantity']);
        foreach ($sizeTotals['sorts'] as $index => $value) {
            $sizeTotals['sorts'][$index] = max(0, $value);
        }
    }





    public function box()
    {
        return $this->belongsTo(Gift::class, 'box_id');
    }

    public function bag()
    {
        return $this->belongsTo(Gift::class, 'bag_id');
    }

    public function purchaseEntries()
    {
        return $this->hasMany(PurchaseEntry::class)->orderByDesc('created_at');
    }

    public function invoiceEntries()
    {
        return $this->hasMany(InvoiceEntry::class)->orderByDesc('created_at');
    }

    public function returnToFactoryEntries()
    {
        return $this->hasMany(ReturnToFactoryEntry::class)->orderByDesc('created_at');
    }

    public function acceptedFactoryReturnEntries()
    {
        return $this->returnToFactoryEntries()->where('status', '<>', 'rejected');
    }

    public function returnFromRetailEntries()
    {
        return $this->hasMany(ReturnFromRetailEntry::class);
    }

    public function acceptedRetailReturnEntries()
    {
        return $this->returnFromRetailEntries()->where('status', 'accepted')->where('inventory',1);
    }

    public function inventoryCheckEntries()
    {
        return $this->hasMany(inventoryCheckEntry::class, 'shoe_id', 'id');
    }
    public function adjustmentEntries()
    {
        return $this->hasMany(AdjustmentEntry::class, 'shoe_id', 'id')->orderByDesc('created_at');
    }

    // Attributes
    public function getAvailableAttribute()
    {
        return $this->inventoryEntry()->first()->count ?? 0;
    }

    public function getImageUrlAttribute()
    {
        return imageRoute($this->image, 'small-thumbnail');
    }

    public function getFullImageUrlAttribute()
    {
        return imageRoute($this->image, 'original');
    }

    public function getThumbnailUrlAttribute()
    {
        return imageRoute($this->image, 'thumbnail');
    }

    public function getPreviewUrlAttribute()
    {
        return imageRoute($this->image, 'preview');
    }

    public function retailStoreShoes()
    {
        return $this->hasMany(RetailStoreShoe::class);
    }
    protected $with = ['factory', 'category', 'color', 'shoeToSize.size'];
    protected $appends = ['image_url', 'full_image_url', 'thumbnail_url', 'preview_url', 'available'];
    protected $fillable = [
        'code',
        'factory_id',
        'category_id',
        'color_id',
        'image',
        'purchase_price',
        'retail_price',
        'box_id',
        'bag_id',
        'initial_count'
    ];
    protected $casts = [
        'factory_id' => 'integer',
        'category_id' => 'integer',
        'color_id' => 'integer',
        'box_id' => 'integer',
        'bag_id' => 'integer',
        'initial_count' => 'integer',
        'purchase_price' => 'double',
        'retail_price' => 'double',
    ];

    protected $attributes = [
        'box_id' => 0, // Set your default value here
        'bag_id' => 0,
    ];
}
