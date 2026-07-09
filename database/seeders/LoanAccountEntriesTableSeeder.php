<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LoanAccountEntriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('loan_account_entries')->delete();
        
        \DB::table('loan_account_entries')->insertOrIgnore(array (
            0 => 
            array (
                'id' => 1,
                'entry_id' => 1,
                'entry_type' => 0,
                'account_book_id' => 1199,
                'account_name' => 'Janata Bank',
                'account_id' => 3,
                'account_type' => 'bank-account',
                'description' => 'প্রমাণ সুজ - হাতীবান্ধার চায়না মালের টাকা অভিযাত্রী হাওলাত নিয়েছে',
                'total_amount' => 63500.0,
                'type' => 'in',
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-11-02 11:58:55',
                'updated_at' => '2025-11-02 11:58:55',
            ),
            1 => 
            array (
                'id' => 2,
                'entry_id' => 1,
                'entry_type' => 0,
                'account_book_id' => 1199,
                'account_name' => 'ক্যাশ',
                'account_id' => 1,
                'account_type' => 'bank-account',
            'description' => 'Haw. China (Mymun-Nalitabari)',
                'total_amount' => 25000.0,
                'type' => 'in',
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-09-07 17:11:33',
                'updated_at' => '2025-09-07 17:11:33',
            ),
            2 => 
            array (
                'id' => 3,
                'entry_id' => 4,
                'entry_type' => 0,
                'account_book_id' => 1255,
                'account_name' => 'ক্যাশ',
                'account_id' => 1,
                'account_type' => 'bank-account',
            'description' => 'Haw. Add (Mymun-Nalitabari)',
                'total_amount' => 40000.0,
                'type' => 'in',
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-09-07 17:15:54',
                'updated_at' => '2025-09-07 17:15:54',
            ),
            3 => 
            array (
                'id' => 4,
                'entry_id' => 5,
                'entry_type' => 0,
                'account_book_id' => 1301,
                'account_name' => 'ক্যাশ',
                'account_id' => 1,
                'account_type' => 'bank-account',
                'description' => NULL,
                'total_amount' => 50000.0,
                'type' => 'in',
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2026-02-22 12:48:35',
                'updated_at' => '2026-02-22 12:48:35',
            ),
            4 => 
            array (
                'id' => 5,
                'entry_id' => 5,
                'entry_type' => 1,
                'account_book_id' => 1301,
                'account_name' => 'ক্যাশ',
                'account_id' => 1,
                'account_type' => 'bank-account',
                'description' => '২২ তারিখ আনা হয়েছে',
                'total_amount' => 50000.0,
                'type' => 'out',
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2026-02-24 12:49:16',
                'updated_at' => '2026-02-24 12:49:16',
            ),
        ));
        
        
    }
}