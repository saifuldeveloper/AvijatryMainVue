<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ColorsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        
        \Schema::disableForeignKeyConstraints();

        \DB::table('colors')->delete();
        
        \DB::table('colors')->insertOrIgnore(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Black',
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2024-02-21 22:03:36',
                'updated_at' => '2025-04-06 00:11:06',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Blue',
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2024-02-21 22:04:07',
                'updated_at' => '2025-04-06 00:10:59',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Maroon',
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2024-02-21 22:04:15',
                'updated_at' => '2025-04-06 00:09:46',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Master',
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2024-02-21 22:04:22',
                'updated_at' => '2025-04-06 00:11:13',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Sky Blue',
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2024-02-21 22:04:28',
                'updated_at' => '2025-04-06 00:13:51',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Cream',
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-06 00:09:01',
                'updated_at' => '2025-04-06 00:09:21',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Pink',
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-06 00:13:58',
                'updated_at' => '2025-04-06 00:13:58',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Brown',
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-06 00:14:29',
                'updated_at' => '2025-04-06 00:14:29',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Golden',
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-06 00:14:36',
                'updated_at' => '2025-04-06 00:14:36',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'Silver',
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-06 00:14:48',
                'updated_at' => '2025-04-06 00:14:48',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'White',
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-06 00:15:14',
                'updated_at' => '2025-04-06 00:15:14',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'Gray',
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-06 00:15:31',
                'updated_at' => '2025-04-06 00:15:31',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'Olive',
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-06 00:16:28',
                'updated_at' => '2025-04-06 00:16:28',
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'Chocolate',
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-06 00:16:47',
                'updated_at' => '2025-04-06 00:16:47',
            ),
            14 => 
            array (
                'id' => 16,
                'name' => 'Coffee',
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-06 00:19:18',
                'updated_at' => '2025-04-06 00:19:18',
            ),
            15 => 
            array (
                'id' => 17,
                'name' => 'Green',
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-06 00:19:41',
                'updated_at' => '2025-04-06 00:19:41',
            ),
            16 => 
            array (
                'id' => 18,
                'name' => 'Orange',
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-05-08 18:17:48',
                'updated_at' => '2025-05-08 18:19:14',
            ),
            17 => 
            array (
                'id' => 19,
                'name' => 'Purple',
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-05-27 23:43:02',
                'updated_at' => '2025-05-27 23:43:02',
            ),
        ));
        
        \Schema::enableForeignKeyConstraints();
    }
}