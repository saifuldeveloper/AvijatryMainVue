<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModelHasRolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('model_has_roles')->delete();

        DB::table('model_has_roles')->insertOrIgnore(array (
            0 =>
            array (
                'role_id' => 1,
                'model_type' => 'App\\Models\\User',
                'model_id' => 1,
            ),
            1 =>
            array (
                'role_id' => 2,
                'model_type' => 'App\\Models\\User',
                'model_id' => 2,
            ),
            2 =>
            array (
                'role_id' => 2,
                'model_type' => 'App\\Models\\User',
                'model_id' => 3,
            ),
            3 =>
            array (
                'role_id' => 2,
                'model_type' => 'App\\Models\\User',
                'model_id' => 5,
            ),
            4 =>
            array (
                'role_id' => 2,
                'model_type' => 'App\\Models\\User',
                'model_id' => 6,
            ),
            5 =>
            array (
                'role_id' => 2,
                'model_type' => 'App\\Models\\User',
                'model_id' => 7,
            ),
            6 =>
            array (
                'role_id' => 2,
                'model_type' => 'App\\Models\\User',
                'model_id' => 8,
            ),
            7 =>
            array (
                'role_id' => 2,
                'model_type' => 'App\\Models\\User',
                'model_id' => 9,
            ),
            8 =>
            array (
                'role_id' => 2,
                'model_type' => 'App\\Models\\User',
                'model_id' => 12,
            ),
            9 =>
            array (
                'role_id' => 2,
                'model_type' => 'App\\Models\\User',
                'model_id' => 13,
            ),
            10 =>
            array (
                'role_id' => 2,
                'model_type' => 'App\\Models\\User',
                'model_id' => 14,
            ),
            11 =>
            array (
                'role_id' => 2,
                'model_type' => 'App\\Models\\User',
                'model_id' => 16,
            ),
            12 =>
            array (
                'role_id' => 3,
                'model_type' => 'App\\Models\\User',
                'model_id' => 17,
            ),
        ));


    }
}
