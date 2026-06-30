<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdjustmentEntry extends Model
{
    use HasFactory;
    protected $fillable = ['shoe_id', 'count', 'type'];

    public function shoe()
    {

        return $this->belongsTo(Shoe::class, 'shoe_id');
    }
}
