<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('permissions')->delete();

        DB::table('permissions')->insert(array (
            0 =>
            array (
                'id' => 1,
                'name' => 'manage bank accounts',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            1 =>
            array (
                'id' => 2,
                'name' => 'manage categories',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            2 =>
            array (
                'id' => 3,
                'name' => 'manage cheques',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            3 =>
            array (
                'id' => 4,
                'name' => 'manage sizes',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            4 =>
            array (
                'id' => 5,
                'name' => 'manage colors',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            5 =>
            array (
                'id' => 6,
                'name' => 'manage users',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            6 =>
            array (
                'id' => 7,
                'name' => 'manage roles',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            7 =>
            array (
                'id' => 8,
                'name' => 'manage gift waste',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            8 =>
            array (
                'id' => 9,
                'name' => 'manage shoe waste',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            9 =>
            array (
                'id' => 10,
                'name' => 'manage sms settings',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            10 =>
            array (
                'id' => 11,
                'name' => 'manage employees',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            11 =>
            array (
                'id' => 12,
                'name' => 'manage expenses',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            12 =>
            array (
                'id' => 13,
                'name' => 'manage factories',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            13 =>
            array (
                'id' => 14,
                'name' => 'edit factories',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            14 =>
            array (
                'id' => 15,
                'name' => 'delete factories',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            15 =>
            array (
                'id' => 16,
                'name' => 'manage gifts',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            16 =>
            array (
                'id' => 17,
                'name' => 'manage gift suppliers',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            17 =>
            array (
                'id' => 18,
                'name' => 'manage invoices',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            18 =>
            array (
                'id' => 19,
                'name' => 'manage loans',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            19 =>
            array (
                'id' => 20,
                'name' => 'manage purchases',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            20 =>
            array (
                'id' => 21,
                'name' => 'manage reports',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            21 =>
            array (
                'id' => 22,
                'name' => 'manage retail stores',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            22 =>
            array (
                'id' => 23,
                'name' => 'manage returns to factory',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            23 =>
            array (
                'id' => 24,
                'name' => 'manage returns from retail stores',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            24 =>
            array (
                'id' => 25,
                'name' => 'manage pending returns',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            25 =>
            array (
                'id' => 26,
                'name' => 'manage shoes',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            26 =>
            array (
                'id' => 27,
                'name' => 'manage transactions',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            27 =>
            array (
                'id' => 28,
                'name' => 'edit retail stores',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            28 =>
            array (
                'id' => 29,
                'name' => 'edit bank accounts',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            29 =>
            array (
                'id' => 30,
                'name' => 'edit gift suppliers',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            30 =>
            array (
                'id' => 31,
                'name' => 'edit employees',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            31 =>
            array (
                'id' => 32,
                'name' => 'edit loans',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            32 =>
            array (
                'id' => 33,
                'name' => 'edit colors',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            33 =>
            array (
                'id' => 34,
                'name' => 'edit expenses',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            34 =>
            array (
                'id' => 35,
                'name' => 'edit categories',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            35 =>
            array (
                'id' => 36,
                'name' => 'edit gifts',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            36 =>
            array (
                'id' => 37,
                'name' => 'edit invoices',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            37 =>
            array (
                'id' => 38,
                'name' => 'edit purchases',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            38 =>
            array (
                'id' => 39,
                'name' => 'edit bikrikhata',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            39 =>
            array (
                'id' => 40,
                'name' => 'delete retail stores',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            40 =>
            array (
                'id' => 41,
                'name' => 'delete bank accounts',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            41 =>
            array (
                'id' => 42,
                'name' => 'delete gift suppliers',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            42 =>
            array (
                'id' => 43,
                'name' => 'delete employees',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            43 =>
            array (
                'id' => 44,
                'name' => 'delete loans',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            44 =>
            array (
                'id' => 45,
                'name' => 'delete colors',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            45 =>
            array (
                'id' => 46,
                'name' => 'delete expenses',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            46 =>
            array (
                'id' => 47,
                'name' => 'delete categories',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            47 =>
            array (
                'id' => 48,
                'name' => 'delete gifts',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            48 =>
            array (
                'id' => 49,
                'name' => 'delete invoices',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            49 =>
            array (
                'id' => 50,
                'name' => 'delete purchases',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            50 =>
            array (
                'id' => 51,
                'name' => 'delete bikrikhata',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            51 =>
            array (
                'id' => 52,
                'name' => 'edit shoes',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            52 =>
            array (
                'id' => 53,
                'name' => 'view shoes',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            53 =>
            array (
                'id' => 54,
                'name' => 'manage inventory check',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            54 =>
            array (
                'id' => 55,
                'name' => 'delete shoe waste',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            55 =>
            array (
                'id' => 56,
                'name' => 'force delete shoe waste',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            56 =>
            array (
                'id' => 57,
                'name' => 'restore deleted shoe waste',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            57 =>
            array (
                'id' => 58,
                'name' => 'delete gift waste',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            58 =>
            array (
                'id' => 59,
                'name' => 'restore deleted gift waste',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            59 =>
            array (
                'id' => 60,
                'name' => 'force delete gift waste',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            60 =>
            array (
                'id' => 61,
                'name' => 'show bikri khata list',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            61 =>
            array (
                'id' => 62,
                'name' => 'restore deleted factories',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            62 =>
            array (
                'id' => 63,
                'name' => 'force delete factories',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            63 =>
            array (
                'id' => 64,
                'name' => 'create factories',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            64 =>
            array (
                'id' => 65,
                'name' => 'create retail store',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            65 =>
            array (
                'id' => 66,
                'name' => 'restore deleted retail store',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            66 =>
            array (
                'id' => 67,
                'name' => 'force delete retail store',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            67 =>
            array (
                'id' => 68,
                'name' => 'restore deleted bank accounts',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            68 =>
            array (
                'id' => 69,
                'name' => 'force delete bank accounts',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            69 =>
            array (
                'id' => 70,
                'name' => 'factory closing',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            70 =>
            array (
                'id' => 71,
                'name' => 'factory dummy closing',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            71 =>
            array (
                'id' => 72,
                'name' => 'retail store closing',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            72 =>
            array (
                'id' => 73,
                'name' => 'retail store dummy closing',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            73 =>
            array (
                'id' => 74,
                'name' => 'bank account closing',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            74 =>
            array (
                'id' => 75,
                'name' => 'restore gift suppliers',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            75 =>
            array (
                'id' => 76,
                'name' => 'force delete gift suppliers',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            76 =>
            array (
                'id' => 77,
                'name' => 'gift suppliers closing',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            77 =>
            array (
                'id' => 78,
                'name' => 'gift suppliers dummy closing',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            78 =>
            array (
                'id' => 79,
                'name' => 'restore deleted employees',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            79 =>
            array (
                'id' => 80,
                'name' => 'force delete employees',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            80 =>
            array (
                'id' => 81,
                'name' => 'employees closing',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            81 =>
            array (
                'id' => 82,
                'name' => 'employees dummy closing',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            82 =>
            array (
                'id' => 83,
                'name' => 'restore deleted loans',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            83 =>
            array (
                'id' => 84,
                'name' => 'force delete loans',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            84 =>
            array (
                'id' => 85,
                'name' => 'restore deleted expense',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            85 =>
            array (
                'id' => 86,
                'name' => 'force delete expense',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            86 =>
            array (
                'id' => 87,
                'name' => 'restore deleted categories',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            87 =>
            array (
                'id' => 88,
                'name' => 'force delete categories',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            88 =>
            array (
                'id' => 89,
                'name' => 'restore deleted colors',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            89 =>
            array (
                'id' => 90,
                'name' => 'force delete colors',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            90 =>
            array (
                'id' => 91,
                'name' => 'edit sizes',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            91 =>
            array (
                'id' => 92,
                'name' => 'delete sizes',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            92 =>
            array (
                'id' => 93,
                'name' => 'restore deleted sizes',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            93 =>
            array (
                'id' => 94,
                'name' => 'force delete sizes',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            94 =>
            array (
                'id' => 95,
                'name' => 'restore deleted gifts',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            95 =>
            array (
                'id' => 96,
                'name' => 'force delete gifts',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            96 =>
            array (
                'id' => 97,
                'name' => 'manage commitments',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            97 =>
            array (
                'id' => 98,
                'name' => 'edit commitments',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            98 =>
            array (
                'id' => 99,
                'name' => 'delete commitments',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            99 =>
            array (
                'id' => 100,
                'name' => 'restore deleted commitments',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            100 =>
            array (
                'id' => 101,
                'name' => 'force delete commitments',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            101 =>
            array (
                'id' => 102,
                'name' => 'manage barcode',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            102 =>
            array (
                'id' => 103,
                'name' => 'manage notes',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            103 =>
            array (
                'id' => 104,
                'name' => 'edit notes',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            104 =>
            array (
                'id' => 105,
                'name' => 'delete notes',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            105 =>
            array (
                'id' => 106,
                'name' => 'restore deleted notes',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            106 =>
            array (
                'id' => 107,
                'name' => 'force delete notes',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            107 =>
            array (
                'id' => 108,
                'name' => 'manage asset types',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            108 =>
            array (
                'id' => 109,
                'name' => 'edit asset types',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            109 =>
            array (
                'id' => 110,
                'name' => 'delete asset types',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            110 =>
            array (
                'id' => 111,
                'name' => 'restore deleted asset types',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            111 =>
            array (
                'id' => 112,
                'name' => 'force delete asset types',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            112 =>
            array (
                'id' => 113,
                'name' => 'manage assets',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            113 =>
            array (
                'id' => 114,
                'name' => 'edit assets',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            114 =>
            array (
                'id' => 115,
                'name' => 'delete assets',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            115 =>
            array (
                'id' => 116,
                'name' => 'restore deleted assets',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            116 =>
            array (
                'id' => 117,
                'name' => 'force delete assets',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            117 =>
            array (
                'id' => 118,
                'name' => 'create bank account',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            118 =>
            array (
                'id' => 119,
                'name' => 'delete party gift',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            119 =>
            array (
                'id' => 120,
                'name' => 'Force Delete Party Gift',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            120 =>
            array (
                'id' => 121,
                'name' => 'manage liabilities',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            121 =>
            array (
                'id' => 122,
                'name' => 'edit liabilities',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            122 =>
            array (
                'id' => 123,
                'name' => 'delete liabilities',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            123 =>
            array (
                'id' => 124,
                'name' => 'restore deleted liabilities',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
            124 =>
            array (
                'id' => 125,
                'name' => 'force delete liabilities',
                'guard_name' => 'web',
                'created_at' => '2025-03-28 11:17:12',
                'updated_at' => '2025-03-28 11:17:12',
            ),
        ));


    }
}
