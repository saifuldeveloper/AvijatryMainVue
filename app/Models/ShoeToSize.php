<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoeToSize extends Model
{
    use HasFactory;

    protected $fillable = [
        'shoe_id',
        'size_id',
        'quantity_1',
        'quantity_2',
    ];

    public function shoe()
    {
        return $this->belongsTo(Shoe::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }
}
