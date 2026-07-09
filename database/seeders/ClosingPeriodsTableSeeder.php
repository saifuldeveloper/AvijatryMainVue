<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ClosingPeriodsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('closing_periods')->delete();
        
        \DB::table('closing_periods')->insertOrIgnore(array (
            0 => 
            array (
                'id' => 1,
                'start_date' => '2024-06-01',
                'end_date' => '2025-04-01',
                'status' => 'closed',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-12-02 18:53:30',
                'updated_at' => '2025-12-02 18:54:16',
            ),
            1 => 
            array (
                'id' => 2,
                'start_date' => '2025-04-01',
                'end_date' => '2026-04-01',
                'status' => 'closed',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-12-02 18:54:16',
                'updated_at' => '2026-04-23 15:23:45',
            ),
            2 => 
            array (
                'id' => 3,
                'start_date' => '2026-04-01',
                'end_date' => NULL,
                'status' => 'open',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2026-04-23 15:23:45',
                'updated_at' => '2026-04-23 15:23:45',
            ),
        ));
        
        
    }
}