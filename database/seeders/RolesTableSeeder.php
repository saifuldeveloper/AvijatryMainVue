<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('roles')->delete();

        DB::table('roles')->insert(array (
            0 =>
            array (
                'id' => 1,
                'name' => 'super-admin',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            1 =>
            array (
                'id' => 2,
                'name' => 'operator',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            2 =>
            array (
                'id' => 3,
                'name' => 'Management',
                'guard_name' => 'web',
                'created_at' => '2026-04-22 21:59:05',
                'updated_at' => '2026-04-22 21:59:05',
            ),
        ));


    }
}
