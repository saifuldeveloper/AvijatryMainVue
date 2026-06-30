<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RetailStoreExpensesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('retail_store_expenses')->delete();
        
        \DB::table('retail_store_expenses')->insert(array (
            0 => 
            array (
                'id' => 1,
                'account_book_id' => 760,
                'invoice_id' => 0,
                'description' => 'ak jura ferot babod',
                'amount' => 549.0,
                'deleted_by' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2025-05-05 14:39:46',
                'updated_at' => '2025-05-05 14:39:46',
            ),
            1 => 
            array (
                'id' => 2,
                'account_book_id' => 999,
                'invoice_id' => 91,
                'description' => '১ জোড়া ফেরত বাবদ',
                'amount' => 1.0,
                'deleted_by' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2025-05-18 22:52:06',
                'updated_at' => '2025-05-18 23:07:08',
            ),
            2 => 
            array (
                'id' => 3,
                'account_book_id' => 999,
                'invoice_id' => 91,
                'description' => '39 jora juta ferot babod',
                'amount' => 22280.0,
                'deleted_by' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2025-05-18 23:00:27',
                'updated_at' => '2025-05-18 23:07:08',
            ),
            3 => 
            array (
                'id' => 5,
                'account_book_id' => 957,
                'invoice_id' => 499,
                'description' => 'স্টাফ বোনাস ৬০০০ টাকা যাহা আগের খাতার ছিল',
                'amount' => 6000.0,
                'deleted_by' => 1,
                'deleted_at' => '2025-11-15 14:09:48',
                'created_at' => '2025-09-09 19:37:18',
                'updated_at' => '2025-11-15 14:09:48',
            ),
            4 => 
            array (
                'id' => 7,
                'account_book_id' => 973,
                'invoice_id' => 732,
                'description' => 'দিদি',
                'amount' => 10000.0,
                'deleted_by' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2025-10-23 11:50:50',
                'updated_at' => '2025-11-23 01:53:07',
            ),
        ));
        
        
    }
}