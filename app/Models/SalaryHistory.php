<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryHistory extends Model
{

    use HasFactory;

    protected $fillable = [
        'employee_id',
        'account_book_id',
        'old_salary',
        'new_salary',
        'change',
        'change_type',
        'remarks',
        'changed_by',
        'effective_date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function accountBook()
    {
        return $this->belongsTo(AccountBook::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
