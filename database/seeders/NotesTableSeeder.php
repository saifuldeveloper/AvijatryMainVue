<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('notes')->delete();
        
        DB::table('notes')->insert(array (
            0 => 
            array (
                'id' => 1,
                'user_id' => 1,
                'name' => 'Staff Salary 2025-2026',
                'reference' => 'AB',
                'content' => 'Don\'t Touch It.',
                'deleted_by' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2025-12-01 18:08:00',
                'updated_at' => '2025-12-01 18:08:00',
            ),
            1 => 
            array (
                'id' => 3,
                'user_id' => 1,
                'name' => 'Stock',
                'reference' => 'ab',
                'content' => NULL,
                'deleted_by' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2025-12-30 17:02:53',
                'updated_at' => '2025-12-30 17:02:53',
            ),
            2 => 
            array (
                'id' => 4,
                'user_id' => 1,
                'name' => 'Avijatry Yearly Calculation 2024-2025',
                'reference' => 'ab',
                'content' => NULL,
                'deleted_by' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2025-12-30 17:04:01',
                'updated_at' => '2025-12-30 17:04:01',
            ),
            3 => 
            array (
                'id' => 5,
                'user_id' => 1,
                'name' => 'Authorization letter',
                'reference' => 'ab',
                'content' => 'bank authorization letter',
                'deleted_by' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2025-12-30 17:04:34',
                'updated_at' => '2025-12-30 17:04:34',
            ),
            4 => 
            array (
                'id' => 6,
                'user_id' => 1,
                'name' => 'Avijatry Bill Memo',
                'reference' => 'ab',
                'content' => 'Avijatry Bill Memo',
                'deleted_by' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2025-12-30 17:05:02',
                'updated_at' => '2025-12-30 17:05:02',
            ),
            5 => 
            array (
                'id' => 7,
                'user_id' => 1,
                'name' => 'Avijatry Staff Account List',
                'reference' => 'ab',
                'content' => 'Avijatry Staff Account List',
                'deleted_by' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2025-12-30 17:05:38',
                'updated_at' => '2025-12-30 17:05:38',
            ),
            6 => 
            array (
                'id' => 8,
                'user_id' => 1,
                'name' => 'Party Hisab 2025-26',
                'reference' => 'Party Hisab 2025-26',
                'content' => NULL,
                'deleted_by' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2026-01-12 17:23:00',
                'updated_at' => '2026-01-12 17:23:00',
            ),
            7 => 
            array (
                'id' => 9,
                'user_id' => 13,
                'name' => 'রকমারি - চাটখিল',
                'reference' => 'সাগর',
                'content' => '৬ জোড়া ফেরত বাবদ - ৩৪৭৪ টাকা পার্টি পাবে',
                'deleted_by' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2026-01-17 20:01:13',
                'updated_at' => '2026-01-17 20:01:13',
            ),
            8 => 
            array (
                'id' => 10,
                'user_id' => 1,
                'name' => 'adorsho chuknagar',
                'reference' => NULL,
                'content' => '1379136',
                'deleted_by' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2026-02-17 14:39:56',
                'updated_at' => '2026-02-17 14:39:56',
            ),
            9 => 
            array (
                'id' => 11,
                'user_id' => 1,
                'name' => 'Software Correction',
                'reference' => 'Software Correction',
                'content' => 'Software Correction',
                'deleted_by' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2026-02-19 12:54:56',
                'updated_at' => '2026-02-19 12:54:56',
            ),
        ));
    }
}
