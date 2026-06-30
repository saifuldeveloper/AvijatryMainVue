<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'shoe_id',
        'count',
        // Other columns that can be mass assigned
    ];

    protected $primaryKey = 'shoe_id';
    public $incrementing = false;
    protected $keyType = 'int';
    protected $hidden = ['created_at', 'updated_at'];

    public function shoe()
    {
        return $this->belongsTo(Shoe::class, 'shoe_id');
    }
}
