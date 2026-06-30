<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \Schema::disableForeignKeyConstraints();

        \DB::table('categories')->delete();
        
        \DB::table('categories')->insertOrIgnore(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'সু',
                'parent_id' => 0,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2024-02-22 18:05:27',
                'updated_at' => '2024-02-22 18:05:27',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'জে',
                'parent_id' => 0,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2024-02-22 18:05:27',
                'updated_at' => '2024-02-22 18:05:27',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'লে',
                'parent_id' => 0,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2024-02-22 18:05:27',
                'updated_at' => '2024-02-22 18:05:27',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'বেবি',
                'parent_id' => 0,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2024-02-22 18:05:27',
                'updated_at' => '2024-02-22 18:05:27',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'আংটা',
                'parent_id' => 2,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2024-02-22 18:08:27',
                'updated_at' => '2024-02-22 18:08:27',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'লোফার',
                'parent_id' => 2,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2024-02-22 18:08:43',
                'updated_at' => '2024-02-22 18:08:43',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'সীট',
                'parent_id' => 2,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2024-02-22 18:09:02',
                'updated_at' => '2024-02-22 18:09:02',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'কলাপুরি',
                'parent_id' => 2,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2024-02-22 18:09:34',
                'updated_at' => '2024-02-22 18:09:34',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'গোলাই চটি',
                'parent_id' => 3,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2024-02-22 18:09:48',
                'updated_at' => '2025-04-05 23:30:50',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'কারচুপী',
                'parent_id' => 3,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2024-02-22 18:10:02',
                'updated_at' => '2024-02-22 18:10:02',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'সীট পাম্পী',
                'parent_id' => 3,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2024-02-22 18:10:18',
                'updated_at' => '2024-02-22 18:10:18',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'পাম',
                'parent_id' => 1,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2024-02-22 18:10:40',
                'updated_at' => '2024-02-22 18:10:40',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'টেপ',
                'parent_id' => 1,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2024-02-22 18:10:52',
                'updated_at' => '2024-02-22 18:10:52',
            ),
            13 => 
            array (
                'id' => 15,
                'name' => '০-২',
                'parent_id' => 4,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2024-02-22 18:11:19',
                'updated_at' => '2024-02-22 18:11:19',
            ),
            14 => 
            array (
                'id' => 16,
                'name' => 'দুই ফিতা',
                'parent_id' => 2,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-05 23:27:16',
                'updated_at' => '2025-04-05 23:27:16',
            ),
            15 => 
            array (
                'id' => 17,
                'name' => 'বেল্ট',
                'parent_id' => 2,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-05 23:27:35',
                'updated_at' => '2025-04-05 23:27:35',
            ),
            16 => 
            array (
                'id' => 18,
                'name' => '৬-৯',
                'parent_id' => 2,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-05 23:27:44',
                'updated_at' => '2025-04-05 23:27:44',
            ),
            17 => 
            array (
                'id' => 19,
                'name' => 'চটি',
                'parent_id' => 2,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-05 23:28:04',
                'updated_at' => '2025-04-05 23:28:04',
            ),
            18 => 
            array (
                'id' => 20,
                'name' => 'হীল',
                'parent_id' => 3,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-05 23:28:49',
                'updated_at' => '2025-04-05 23:28:49',
            ),
            19 => 
            array (
                'id' => 21,
                'name' => 'বেল্ট',
                'parent_id' => 3,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-05 23:29:07',
                'updated_at' => '2025-04-05 23:29:07',
            ),
            20 => 
            array (
                'id' => 22,
                'name' => 'সীট',
                'parent_id' => 3,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-05 23:29:37',
                'updated_at' => '2025-04-05 23:29:37',
            ),
            21 => 
            array (
                'id' => 23,
                'name' => 'ফ্লাট',
                'parent_id' => 3,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-05 23:29:46',
                'updated_at' => '2025-04-05 23:29:46',
            ),
            22 => 
            array (
                'id' => 24,
                'name' => 'বুস্টন',
                'parent_id' => 3,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-05 23:29:53',
                'updated_at' => '2025-04-05 23:29:53',
            ),
            23 => 
            array (
                'id' => 25,
                'name' => 'পিউ চটি',
                'parent_id' => 3,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-05 23:30:23',
                'updated_at' => '2025-04-05 23:30:23',
            ),
            24 => 
            array (
                'id' => 26,
                'name' => '১৬-১৮',
                'parent_id' => 2,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-06 04:46:31',
                'updated_at' => '2025-04-06 04:46:31',
            ),
            25 => 
            array (
                'id' => 27,
                'name' => 'নাগড়া',
                'parent_id' => 2,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-06 06:31:57',
                'updated_at' => '2025-04-06 06:31:57',
            ),
            26 => 
            array (
                'id' => 28,
                'name' => 'নাগড়া',
                'parent_id' => 3,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-06 06:32:03',
                'updated_at' => '2025-04-06 06:32:03',
            ),
            27 => 
            array (
                'id' => 29,
                'name' => 'কিপ্স আংটা',
                'parent_id' => 2,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-06 06:43:17',
                'updated_at' => '2025-04-06 06:43:17',
            ),
            28 => 
            array (
                'id' => 30,
                'name' => 'সাইকেল সু',
                'parent_id' => 2,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-06 06:59:19',
                'updated_at' => '2025-04-06 06:59:19',
            ),
            29 => 
            array (
                'id' => 31,
                'name' => 'সাচ্চি',
                'parent_id' => 2,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-06 06:59:25',
                'updated_at' => '2025-04-06 06:59:25',
            ),
            30 => 
            array (
                'id' => 32,
                'name' => 'পাম্পি',
                'parent_id' => 3,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-07 09:38:01',
                'updated_at' => '2025-04-07 09:38:01',
            ),
            31 => 
            array (
                'id' => 33,
                'name' => '১২-১৫',
                'parent_id' => 3,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-07 09:45:58',
                'updated_at' => '2025-04-07 09:45:58',
            ),
            32 => 
            array (
                'id' => 34,
                'name' => 'ক্যাজুয়াল',
                'parent_id' => 2,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-08 00:02:00',
                'updated_at' => '2025-04-08 00:02:00',
            ),
            33 => 
            array (
                'id' => 35,
                'name' => 'পিউ বেল্ট',
                'parent_id' => 3,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-08 00:50:01',
                'updated_at' => '2025-04-08 00:50:01',
            ),
            34 => 
            array (
                'id' => 36,
                'name' => 'নাগড়া হীল',
                'parent_id' => 3,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-09 05:19:05',
                'updated_at' => '2025-04-09 05:19:05',
            ),
            35 => 
            array (
                'id' => 37,
                'name' => 'স্কুল পাম্পি',
                'parent_id' => 3,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2026-01-18 14:39:04',
                'updated_at' => '2026-01-18 14:39:04',
            ),
        ));
        
        \Schema::enableForeignKeyConstraints();
        
    }
}