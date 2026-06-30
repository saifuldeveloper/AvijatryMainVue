<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetailStoreShoe extends Model
{
    use HasFactory;

    protected $fillable = ['retail_store_id', 'shoe_id', 'is_approved', 'quantity', 'note'];

    public function retailStore()
    {
        return $this->belongsTo(RetailStore::class);
    }

    public function shoe()
    {
        return $this->belongsTo(Shoe::class);
    }
}
