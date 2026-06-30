<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LoansTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('loans')->delete();
        
        \DB::table('loans')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'China',
                'mobile_no' => '01',
                'mobile_no_two' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-11-03 11:57:04',
                'updated_at' => '2025-11-03 11:57:14',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Avijatry- Cumilla',
                'mobile_no' => '01',
                'mobile_no_two' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2026-01-17 14:18:07',
                'updated_at' => '2026-01-17 14:18:07',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Avijatry-Khulna',
                'mobile_no' => '01',
                'mobile_no_two' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2026-01-17 14:18:22',
                'updated_at' => '2026-01-17 14:18:22',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Adda',
                'mobile_no' => '01',
                'mobile_no_two' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2026-01-17 17:14:25',
                'updated_at' => '2026-01-17 17:14:25',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'রবিউল',
                'mobile_no' => '01',
                'mobile_no_two' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2026-02-25 12:46:38',
                'updated_at' => '2026-02-25 12:46:38',
            ),
        ));
        
        
    }
}