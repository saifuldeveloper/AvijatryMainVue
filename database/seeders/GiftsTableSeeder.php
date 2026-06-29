<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class GiftsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('gifts')->delete();
        
        \DB::table('gifts')->insert(array (
            0 => 
            array (
                'id' => 5,
                'name' => 'জে-বক্স',
                'gift_type_id' => '1',
                'initial_count' => 0,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2024-02-20 22:02:42',
                'updated_at' => '2025-04-29 23:17:38',
            ),
            1 => 
            array (
                'id' => 6,
                'name' => 'জে-ব্যাগ',
                'gift_type_id' => '2',
                'initial_count' => 0,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2024-02-20 22:02:55',
                'updated_at' => '2024-02-20 22:02:55',
            ),
            2 => 
            array (
                'id' => 9,
                'name' => 'সু-বক্স',
                'gift_type_id' => '1',
                'initial_count' => 0,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2024-02-20 22:03:30',
                'updated_at' => '2025-04-29 23:18:20',
            ),
            3 => 
            array (
                'id' => 10,
                'name' => 'লে-বক্স',
                'gift_type_id' => '1',
                'initial_count' => 0,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2024-02-20 22:03:54',
                'updated_at' => '2025-04-29 23:18:00',
            ),
            4 => 
            array (
                'id' => 12,
                'name' => 'সু-ব্যাগ',
                'gift_type_id' => '2',
                'initial_count' => 0,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2024-03-03 16:53:21',
                'updated_at' => '2025-04-05 23:10:51',
            ),
            5 => 
            array (
                'id' => 13,
                'name' => 'সিট-বক্স',
                'gift_type_id' => '1',
                'initial_count' => 0,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2024-03-03 16:54:02',
                'updated_at' => '2025-05-07 15:13:53',
            ),
            6 => 
            array (
                'id' => 14,
                'name' => 'লে-ব্যাগ',
                'gift_type_id' => '2',
                'initial_count' => 0,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2024-03-03 16:55:14',
                'updated_at' => '2025-04-05 23:09:54',
            ),
            7 => 
            array (
                'id' => 15,
                'name' => 'চাবির রিং',
                'gift_type_id' => '3',
                'initial_count' => 0,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-29 23:08:26',
                'updated_at' => '2025-04-29 23:08:26',
            ),
            8 => 
            array (
                'id' => 16,
                'name' => 'গেঞ্জি',
                'gift_type_id' => '3',
                'initial_count' => 0,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-29 23:08:36',
                'updated_at' => '2025-04-29 23:08:36',
            ),
            9 => 
            array (
                'id' => 17,
                'name' => 'একদর সাইনবোর্ড',
                'gift_type_id' => '3',
                'initial_count' => 0,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-29 23:08:59',
                'updated_at' => '2025-04-29 23:08:59',
            ),
            10 => 
            array (
                'id' => 18,
                'name' => 'রাফ স্লিপ বই',
                'gift_type_id' => '3',
                'initial_count' => 0,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-29 23:09:26',
                'updated_at' => '2025-04-29 23:09:26',
            ),
            11 => 
            array (
                'id' => 19,
                'name' => 'গ্যারান্টি বই',
                'gift_type_id' => '3',
                'initial_count' => 0,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-29 23:09:56',
                'updated_at' => '2025-04-29 23:09:56',
            ),
            12 => 
            array (
                'id' => 20,
                'name' => 'ডেংলার',
                'gift_type_id' => '3',
                'initial_count' => 0,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-29 23:10:17',
                'updated_at' => '2025-04-29 23:10:17',
            ),
            13 => 
            array (
                'id' => 21,
                'name' => 'ফেস্টুন',
                'gift_type_id' => '3',
                'initial_count' => 0,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2026-01-04 17:15:36',
                'updated_at' => '2026-01-04 17:15:36',
            ),
        ));
        
        
    }
}