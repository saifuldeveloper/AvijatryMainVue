<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    public function getCurrentAccountBook()
    {

        return $this->accountBooks()->where('open', true)->latest()->first();
    }
    public function accountBooks()
    {
        return $this->hasMany(AccountBook::class, 'account_id', 'id')->where('account_type', 'employee');
    }

    public function entries()
    {
        return $this->hasMany(EmployeeAccountEntry::class, 'account_id', 'id');
    }

    public function previousClosingBook()
    {
        return $this->accountBooks()->where('open', false)->latest()->first();
    }

    // accountBook Salary
    public function getAccountBookSalary($accountBookId = null)
    {
        $accountBookId = $accountBookId ?? $this->getCurrentAccountBook()?->id;
        return $accountBookId
            ? EmployeeSalary::where('employee_id', $this->id)
                ->where('account_book_id', $accountBookId)
                ->value('salary')
            : null;
    }
    //accountBook Limit

    public function getAccountLimit($accountBookId = null)
    {
        $accountBookId = $accountBookId ?? $this->getCurrentAccountBook()?->id;
        return $accountBookId
            ? EmployeeSalary::where('employee_id', $this->id)
                ->where('account_book_id', $accountBookId)
                ->value('limit')
            : null;
    }


    public function getSalaryStartDate($accountBookId = null)
    {
        $startDate = EmployeeSalary::where('employee_id', $this->id)
            ->where('account_book_id', $accountBookId)
            ->value('salary_start_date');
        if (!$startDate) {
            $accountBook = $accountBookId ? AccountBook::find($accountBookId) : $this->getCurrentAccountBook();
            $startDate = $this->join_date ?? $accountBook->created_at;
        }
        return Carbon::parse($startDate)->startOfDay();

    }
    public function getSalaryEndDate($accountBookId = null)
    {
        $accountBook = $accountBookId ? AccountBook::find($accountBookId) : $this->getCurrentAccountBook();
        if ($accountBook) {
            return $accountBook->open
                ? now()
                : Carbon::parse($accountBook->closing_date ?? $accountBook->created_at ?? now());
        }
        return now();
    }
    public function calculateDateDifference($accountBookId = null, $startDate = null)
    {
        $startDate = $this->getSalaryStartDate($accountBookId);
        $endDate = $this->getSalaryEndDate($accountBookId)->endOfDay();
        $months = 0;
        $tmp = $startDate->copy();

        while ($tmp->copy()->addMonthNoOverflow()->lte($endDate)) {
            $tmp->addMonthNoOverflow();
            $months++;
        }
        // প্রতি পূর্ণ মাস = 30 দিন
        $daysFromMonths = $months * 30;

        // বাকি দিন (partial month)
        $remainingDays = $tmp->diffInDays($endDate);
        // যদি partial month শুরু হয় মাসের 1 তারিখ থেকে, তখন end date include
        if ($tmp->day === 1) {
            $remainingDays += 1;
        }

        // মোট দিন
        $totalDays = $daysFromMonths + $remainingDays;
        return $totalDays;
    }

    // Daily salary
    public function getDailySalary($accountBookId = null)
    {
        $salary = $this->getAccountBookSalary($accountBookId);
        return $salary ? $salary / 30 : 0;
    }

    // Total salary
    public function getSumSalary($accountBookId = null)
    {
        $startDate = $this->getSalaryStartDate($accountBookId);
        $dateDiff = $this->calculateDateDifference($accountBookId, $startDate);
        $daily = $this->getDailySalary($accountBookId);

        return round($daily * $dateDiff, 2);
    }






    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
    protected static function booted()
    {
        static::deleting(function ($factory) {
            if (auth()->check()) {
                $factory->deleted_by = auth()->id();
                $factory->save();
            }
        });
    }




    public function getPreviousDueAmount($accountBookID = null)
    {
        $currentBook = AccountBook::find($accountBookID);


        $previousBook = AccountBook::where('account_type', 'employee')
            ->where('account_id', $this->id)
            ->where('open', false)
            ->where('id', '<', $currentBook->id)
            ->orderBy('id', 'desc')
            ->first();
        $previousJoma = 0;
        if ($previousBook) {
            $previousJoma = EmployeeAccountEntry::where('account_book_id', $previousBook->id)
                ->where('payment_type', 'employee-closing')
                ->whereNull('closing_id')
                ->sum('total_amount');
        }
        $currentJoma = EmployeeAccountEntry::where('account_book_id', $currentBook->id)
            ->where('payment_type', 'employee-closing')
            ->whereNull('closing_id')
            ->sum('total_amount');

        $totalJoma = $previousJoma + $currentJoma;

        $previousBalance = $currentBook->previous_balance ?? 0;

        $remainingDue = $previousBalance - $totalJoma;
        // $remainingDue = $previousBalance - $previousJoma;

        return $remainingDue;
    }


    protected $fillable = ['name', 'address', 'mobile_no', 'limit'];





    // public function getSalaryUntil($closingDate, $accountBookId = null)
    // {
    //     $accountBookId = $accountBookId ?? $this->getCurrentAccountBook()?->id;

    //     $employeeSalary = EmployeeSalary::where('employee_id', $this->id)
    //         ->where('account_book_id', $accountBookId)
    //         ->first();

    //     if (!$employeeSalary) {
    //         return 0;
    //     }

    //     $endDate = Carbon::parse($closingDate)->endOfDay();

    //     // 🔹 প্রথম effective_date খুঁজে বের করা (যদি history থাকে)
    //     $firstHistory = SalaryHistory::where('employee_id', $this->id)
    //         ->where('account_book_id', $accountBookId)
    //         ->orderBy('effective_date', 'asc')
    //         ->first();

    //     // 🔹 যদি history থাকে তাহলে তারিখ, না থাকলে employeeSalary->salary_start_date ব্যবহার করো
    //     $startDate = $firstHistory
    //         ? Carbon::parse($firstHistory->effective_date)->startOfDay()
    //         : Carbon::parse($employeeSalary->salary_start_date ?? now())->startOfDay();

    //     if ($endDate->lt($startDate)) {
    //         return 0;
    //     }

    //     // 🔹 সব salary history আনো (start থেকে end পর্যন্ত)
    //     $salaryHistories = SalaryHistory::where('employee_id', $this->id)
    //         ->where('account_book_id', $accountBookId)
    //         ->whereBetween('effective_date', [$startDate, $endDate])
    //         ->orderBy('effective_date', 'asc')
    //         ->get();

    //     $totalSalary = 0;

    //     // 🔹 current salary শুরু হবে history থাকলে প্রথম old_salary থেকে, না থাকলে employeeSalary থেকে
    //     $currentSalary = $firstHistory
    //         ? $firstHistory->old_salary
    //         : $employeeSalary->salary;

    //     $lastChangeDate = $startDate->copy();

    //     // 🔹 ধাপে ধাপে হিসাব
    //     foreach ($salaryHistories as $history) {
    //         $changeDate = Carbon::parse($history->effective_date)->startOfDay();

    //         if ($changeDate->gt($endDate)) {
    //             break;
    //         }

    //         // 🔹 পূর্ণ মাসের পার্থক্য বের করা
    //         $monthsDiff = $lastChangeDate->diffInMonths($changeDate);

    //         // 🔹 বাকি partial days
    //         $partialDays = $lastChangeDate->copy()->addMonthsNoOverflow($monthsDiff)->diffInDays($changeDate);

    //         // 🔹 fixed 30 দিন প্রতি মাস ধরে হিসাব
    //         $days = ($monthsDiff * 30) + $partialDays;

    //         if ($days > 0) {
    //             $totalSalary += ($currentSalary / 30) * $days;
    //         }

    //         // salary আপডেট করো
    //         $currentSalary = $history->new_salary;
    //         $lastChangeDate = $changeDate;
    //     }

    //     // 🔹 শেষ পরিবর্তনের পর থেকে closing date পর্যন্ত হিসাব
    //     // শেষ থেকে closing date পর্যন্ত
    //     if ($lastChangeDate->lte($endDate)) {
    //         $monthsDiff = $lastChangeDate->diffInMonths($endDate);
    //         $partialDays = $lastChangeDate->copy()->addMonthsNoOverflow($monthsDiff)->diffInDays($endDate);
    //         $days = ($monthsDiff * 30) + $partialDays + 1; // closing date included

    //         $totalSalary += ($currentSalary / 30) * $days;
    //     }
    //     return round($totalSalary, 2);
    // }





    public function getSalaryUntil($closingDate, $accountBookId = null)
    {
        $accountBookId = $accountBookId ?? $this->getCurrentAccountBook()?->id;


        $employeeSalary = EmployeeSalary::where('employee_id', $this->id)
            ->where('account_book_id', $accountBookId)
            ->first();
        if (!$employeeSalary)
            return 0;

        $endDate = Carbon::parse($closingDate)->startOfDay();




        $firstHistory = SalaryHistory::where('employee_id', $this->id)
            ->where('account_book_id', $accountBookId)
            ->orderBy('effective_date', 'asc')
            ->first();

        $startDate = $firstHistory
            ? Carbon::parse($firstHistory->effective_date)->startOfDay()
            : Carbon::parse($employeeSalary->salary_start_date ?? now())->startOfDay();

        if ($endDate->lt($startDate))
            return 0;

        $salaryHistories = SalaryHistory::where('employee_id', $this->id)
            ->where('account_book_id', $accountBookId)
            ->whereBetween('effective_date', [$startDate, $endDate])
            ->orderBy('effective_date', 'asc')
            ->get();

        $totalSalary = 0;
        $currentSalary = $firstHistory ? $firstHistory->old_salary : $employeeSalary->salary;
        $lastDate = $startDate->copy();

        foreach ($salaryHistories as $history) {
            $changeDate = Carbon::parse($history->effective_date)->startOfDay();

            // আগের স্যালারি পিরিয়ডের দিন বের করা (Effective Date এর ঠিক আগের দিন পর্যন্ত)
            $days = $this->getDaysDiff360($lastDate, $changeDate->copy()->subDay());

            if ($days > 0) {
                $totalSalary += ($currentSalary / 30) * $days;
            }

            $currentSalary = $history->new_salary;
            $lastDate = $changeDate;
        }

        // শেষ পিরিয়ড থেকে Closing Date পর্যন্ত
        $finalDays = $this->getDaysDiff360($lastDate, $endDate);
        if ($finalDays > 0) {
            $totalSalary += ($currentSalary / 30) * $finalDays;
        }

        return round($totalSalary, 2);
    }

    /**
     * মাসের দিন ৩১ বা ২৮ হলেও সেটাকে ৩০ ধরে দিন গণনা করার লজিক (30/360 Method)
     */
    private function getDaysDiff360($start, $end)
    {
        $d1 = $start->day;
        $m1 = $start->month;
        $y1 = $start->year;

        $d2 = $end->day;
        $m2 = $end->month;
        $y2 = $end->year;

        // ৩১ তারিখ হলে সেটাকে ৩০ ধরা
        if ($d1 > 30)
            $d1 = 30;
        if ($d2 > 30)
            $d2 = 30;

        // ফেব্রুয়ারির শেষ দিন হলে সেটাকেও ৩০ ধরা (জানুয়ারি ১ থেকে ফেব্রুয়ারি ২৮ যেন পুরো ২ মাস আসে)
        if ($start->isLastOfMonth() && $m1 == 2)
            $d1 = 30;
        if ($end->isLastOfMonth() && $m2 == 2)
            $d2 = 30;

        // ৩০ দিনের মাসের ফর্মুলা: (Y2 - Y1) * 360 + (M2 - M1) * 30 + (D2 - D1)
        $diff = ($y2 - $y1) * 360 + ($m2 - $m1) * 30 + ($d2 - $d1) + 1; // +1 inclusive date এর জন্য

        return max(0, $diff);
    }


}
