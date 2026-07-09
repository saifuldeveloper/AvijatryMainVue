<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class GiftPurchasesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('gift_purchases')->delete();
        
        \DB::table('gift_purchases')->insertOrIgnore(array (
            0 => 
            array (
                'id' => 1,
                'account_book_id' => 393,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-29 23:13:11',
                'updated_at' => '2025-04-29 23:13:11',
            ),
            1 => 
            array (
                'id' => 2,
                'account_book_id' => 394,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-29 23:13:49',
                'updated_at' => '2025-04-29 23:13:49',
            ),
            2 => 
            array (
                'id' => 3,
                'account_book_id' => 395,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-29 23:14:15',
                'updated_at' => '2025-04-29 23:14:15',
            ),
            3 => 
            array (
                'id' => 4,
                'account_book_id' => 396,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-29 23:14:39',
                'updated_at' => '2025-04-29 23:14:39',
            ),
            4 => 
            array (
                'id' => 5,
                'account_book_id' => 396,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-29 23:14:57',
                'updated_at' => '2025-04-29 23:14:57',
            ),
            5 => 
            array (
                'id' => 6,
                'account_book_id' => 396,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-29 23:15:18',
                'updated_at' => '2025-04-29 23:15:18',
            ),
            6 => 
            array (
                'id' => 7,
                'account_book_id' => 397,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-29 23:15:49',
                'updated_at' => '2025-04-29 23:15:49',
            ),
            7 => 
            array (
                'id' => 8,
                'account_book_id' => 399,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-29 23:24:04',
                'updated_at' => '2025-04-29 23:24:04',
            ),
            8 => 
            array (
                'id' => 9,
                'account_book_id' => 399,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-29 23:25:33',
                'updated_at' => '2025-04-29 23:25:33',
            ),
            9 => 
            array (
                'id' => 10,
                'account_book_id' => 398,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-29 23:26:21',
                'updated_at' => '2025-04-29 23:26:21',
            ),
            10 => 
            array (
                'id' => 11,
                'account_book_id' => 398,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-29 23:26:41',
                'updated_at' => '2025-04-29 23:26:41',
            ),
            11 => 
            array (
                'id' => 12,
                'account_book_id' => 1123,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-11-26 12:46:26',
                'updated_at' => '2025-11-26 12:46:26',
            ),
            12 => 
            array (
                'id' => 13,
                'account_book_id' => 1128,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-11-26 13:16:27',
                'updated_at' => '2025-11-26 13:16:27',
            ),
            13 => 
            array (
                'id' => 14,
                'account_book_id' => 1221,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-11-26 14:12:34',
                'updated_at' => '2025-11-26 14:12:34',
            ),
            14 => 
            array (
                'id' => 15,
                'account_book_id' => 1123,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-11-27 12:40:04',
                'updated_at' => '2025-11-27 12:40:04',
            ),
            15 => 
            array (
                'id' => 16,
                'account_book_id' => 1221,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-11-27 14:17:03',
                'updated_at' => '2025-11-27 14:17:03',
            ),
            16 => 
            array (
                'id' => 17,
                'account_book_id' => 1221,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-11-27 16:50:44',
                'updated_at' => '2025-11-27 16:50:44',
            ),
            17 => 
            array (
                'id' => 18,
                'account_book_id' => 398,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-11-29 18:29:13',
                'updated_at' => '2025-11-29 18:29:13',
            ),
            18 => 
            array (
                'id' => 19,
                'account_book_id' => 1123,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-11-30 10:48:24',
                'updated_at' => '2025-11-30 10:48:24',
            ),
            19 => 
            array (
                'id' => 20,
                'account_book_id' => 399,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-11-30 11:16:36',
                'updated_at' => '2025-11-30 11:16:36',
            ),
            20 => 
            array (
                'id' => 21,
                'account_book_id' => 1221,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-11-30 14:48:24',
                'updated_at' => '2025-11-30 14:48:24',
            ),
            21 => 
            array (
                'id' => 22,
                'account_book_id' => 1128,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-11-30 15:23:01',
                'updated_at' => '2025-11-30 15:23:01',
            ),
            22 => 
            array (
                'id' => 23,
                'account_book_id' => 1221,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-12-02 12:45:41',
                'updated_at' => '2025-12-02 12:45:41',
            ),
            23 => 
            array (
                'id' => 24,
                'account_book_id' => 1123,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-12-06 18:29:44',
                'updated_at' => '2025-12-06 18:29:44',
            ),
            24 => 
            array (
                'id' => 25,
                'account_book_id' => 1123,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-12-07 18:17:47',
                'updated_at' => '2025-12-07 18:17:47',
            ),
            25 => 
            array (
                'id' => 26,
                'account_book_id' => 398,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-12-14 17:36:36',
                'updated_at' => '2025-12-14 17:36:36',
            ),
            26 => 
            array (
                'id' => 27,
                'account_book_id' => 1128,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-12-18 13:42:32',
                'updated_at' => '2025-12-18 13:42:32',
            ),
            27 => 
            array (
                'id' => 28,
                'account_book_id' => 1123,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-12-18 14:14:52',
                'updated_at' => '2025-12-18 14:14:52',
            ),
            28 => 
            array (
                'id' => 29,
                'account_book_id' => 1221,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-12-20 13:50:25',
                'updated_at' => '2025-12-20 13:50:25',
            ),
            29 => 
            array (
                'id' => 30,
                'account_book_id' => 1135,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-12-22 18:10:49',
                'updated_at' => '2025-12-22 18:10:49',
            ),
            30 => 
            array (
                'id' => 31,
                'account_book_id' => 1221,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-12-23 16:15:16',
                'updated_at' => '2025-12-23 16:15:16',
            ),
            31 => 
            array (
                'id' => 32,
                'account_book_id' => 1123,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2026-01-04 17:03:33',
                'updated_at' => '2026-01-04 17:03:33',
            ),
            32 => 
            array (
                'id' => 33,
                'account_book_id' => 1123,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2026-01-04 17:04:06',
                'updated_at' => '2026-01-04 17:04:06',
            ),
            33 => 
            array (
                'id' => 34,
                'account_book_id' => 1221,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2026-01-04 17:04:56',
                'updated_at' => '2026-01-04 17:04:56',
            ),
            34 => 
            array (
                'id' => 35,
                'account_book_id' => 1126,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2026-01-04 17:09:40',
                'updated_at' => '2026-01-04 17:09:40',
            ),
            35 => 
            array (
                'id' => 36,
                'account_book_id' => 395,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2026-01-04 17:11:29',
                'updated_at' => '2026-01-04 17:11:29',
            ),
            36 => 
            array (
                'id' => 37,
                'account_book_id' => 395,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2026-01-04 17:16:42',
                'updated_at' => '2026-01-04 17:16:42',
            ),
            37 => 
            array (
                'id' => 38,
                'account_book_id' => 1247,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2026-01-04 17:24:28',
                'updated_at' => '2026-01-04 17:24:28',
            ),
            38 => 
            array (
                'id' => 39,
                'account_book_id' => 1247,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2026-01-04 17:25:47',
                'updated_at' => '2026-01-04 17:25:47',
            ),
            39 => 
            array (
                'id' => 40,
                'account_book_id' => 1135,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2026-01-06 15:59:47',
                'updated_at' => '2026-01-06 15:59:47',
            ),
            40 => 
            array (
                'id' => 41,
                'account_book_id' => 393,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2026-01-11 17:27:51',
                'updated_at' => '2026-01-11 17:27:51',
            ),
            41 => 
            array (
                'id' => 42,
                'account_book_id' => 1128,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2026-01-15 12:20:22',
                'updated_at' => '2026-01-15 12:20:22',
            ),
            42 => 
            array (
                'id' => 43,
                'account_book_id' => 398,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2026-01-15 12:38:51',
                'updated_at' => '2026-01-15 12:38:51',
            ),
            43 => 
            array (
                'id' => 44,
                'account_book_id' => 398,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2026-01-17 15:37:12',
                'updated_at' => '2026-01-17 15:37:12',
            ),
            44 => 
            array (
                'id' => 45,
                'account_book_id' => 1128,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2026-01-17 17:39:18',
                'updated_at' => '2026-01-17 17:39:18',
            ),
            45 => 
            array (
                'id' => 46,
                'account_book_id' => 393,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2026-02-07 17:39:28',
                'updated_at' => '2026-02-07 17:39:28',
            ),
            46 => 
            array (
                'id' => 47,
                'account_book_id' => 1279,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2026-02-10 12:57:13',
                'updated_at' => '2026-02-10 12:57:13',
            ),
            47 => 
            array (
                'id' => 48,
                'account_book_id' => 1126,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2026-02-22 13:23:20',
                'updated_at' => '2026-02-22 13:23:20',
            ),
            48 => 
            array (
                'id' => 49,
                'account_book_id' => 1126,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2026-03-01 15:13:36',
                'updated_at' => '2026-03-01 15:13:36',
            ),
            49 => 
            array (
                'id' => 50,
                'account_book_id' => 1123,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2026-03-01 19:51:15',
                'updated_at' => '2026-03-01 19:51:15',
            ),
            50 => 
            array (
                'id' => 51,
                'account_book_id' => 1221,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2026-03-01 21:27:20',
                'updated_at' => '2026-03-01 21:27:20',
            ),
            51 => 
            array (
                'id' => 52,
                'account_book_id' => 1221,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2026-03-02 16:49:09',
                'updated_at' => '2026-03-02 16:49:09',
            ),
            52 => 
            array (
                'id' => 53,
                'account_book_id' => 1279,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2026-03-11 14:42:44',
                'updated_at' => '2026-03-11 14:42:44',
            ),
        ));
        
        
    }
}