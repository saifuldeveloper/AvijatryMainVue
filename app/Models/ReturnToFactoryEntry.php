<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnToFactoryEntry extends Model
{
    use HasFactory;

    public function accountBook() {
    	return $this->belongsTo(AccountBook::class);
    }

    public function shoe() {
    	return $this->hasOne(Shoe::class, 'id' ,'shoe_id');
    }

    public function returnTofactory(){
        return $this->hasMany(ReturnToFactory::class, 'id', 'return_id');
    }

    public function retailStoreReturnEntry() {
        return $this->belongsTo(ReturnFromRetailEntry::class, 'retail_store_return_entry_id' , 'id');
    }


    protected $fillable = ['shoe_id', 'count','return_id','account_book_id'];
}
