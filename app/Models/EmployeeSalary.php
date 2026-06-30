<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSalary extends Model
{
    use HasFactory;

      protected $fillable = [
        'employee_id',
        'account_book_id',
        'salary_start_date',
        'limit',
        'salary',
    ];
}
