<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_message',
        'retailer_message',
        'gift_supplier_message',
        'collection_message',
        'supplier_status',
        'retailer_status',
        'gift_supplier_status',
        'collection_status',
    ];
}
