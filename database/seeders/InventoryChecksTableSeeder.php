<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class InventoryChecksTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('inventory_checks')->delete();
        
        \DB::table('inventory_checks')->insertOrIgnore(array (
            0 => 
            array (
                'id' => 1,
                'start_date' => '2025-04-05',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-06 03:35:41',
                'updated_at' => '2025-04-06 03:35:59',
            ),
            1 => 
            array (
                'id' => 2,
                'start_date' => '2025-04-05',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-06 06:20:47',
                'updated_at' => '2025-04-06 06:20:58',
            ),
            2 => 
            array (
                'id' => 3,
                'start_date' => '2025-04-05',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-06 06:21:13',
                'updated_at' => '2025-04-06 06:21:22',
            ),
            3 => 
            array (
                'id' => 4,
                'start_date' => '2025-04-05',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-06 06:21:59',
                'updated_at' => '2025-04-06 06:22:11',
            ),
            4 => 
            array (
                'id' => 5,
                'start_date' => '2025-04-05',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-06 06:34:00',
                'updated_at' => '2025-04-06 06:34:08',
            ),
            5 => 
            array (
                'id' => 6,
                'start_date' => '2025-04-06',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-07 06:43:09',
                'updated_at' => '2025-04-07 06:43:20',
            ),
            6 => 
            array (
                'id' => 7,
                'start_date' => '2025-04-06',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-07 07:02:40',
                'updated_at' => '2025-04-07 07:02:53',
            ),
            7 => 
            array (
                'id' => 8,
                'start_date' => '2025-04-07',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-07 22:44:18',
                'updated_at' => '2025-04-07 22:44:26',
            ),
            8 => 
            array (
                'id' => 9,
                'start_date' => '2025-04-07',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-07 22:44:46',
                'updated_at' => '2025-04-07 22:44:56',
            ),
            9 => 
            array (
                'id' => 10,
                'start_date' => '2025-04-07',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-08 00:30:28',
                'updated_at' => '2025-04-08 00:30:37',
            ),
            10 => 
            array (
                'id' => 11,
                'start_date' => '2025-04-07',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-08 02:00:27',
                'updated_at' => '2025-04-08 02:00:36',
            ),
            11 => 
            array (
                'id' => 12,
                'start_date' => '2025-04-07',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-08 02:42:06',
                'updated_at' => '2025-04-08 02:42:28',
            ),
            12 => 
            array (
                'id' => 13,
                'start_date' => '2025-04-07',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-08 02:58:22',
                'updated_at' => '2025-04-08 03:21:34',
            ),
            13 => 
            array (
                'id' => 14,
                'start_date' => '2025-04-07',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-08 03:21:47',
                'updated_at' => '2025-04-08 03:22:08',
            ),
            14 => 
            array (
                'id' => 15,
                'start_date' => '2025-04-07',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-08 06:01:47',
                'updated_at' => '2025-04-08 06:02:16',
            ),
            15 => 
            array (
                'id' => 16,
                'start_date' => '2025-04-07',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-08 07:21:49',
                'updated_at' => '2025-04-08 07:22:19',
            ),
            16 => 
            array (
                'id' => 17,
                'start_date' => '2025-04-08',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-09 03:59:00',
                'updated_at' => '2025-04-09 03:59:13',
            ),
            17 => 
            array (
                'id' => 18,
                'start_date' => '2025-04-08',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-09 07:05:01',
                'updated_at' => '2025-04-09 07:05:38',
            ),
            18 => 
            array (
                'id' => 19,
                'start_date' => '2025-04-08',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-09 07:05:58',
                'updated_at' => '2025-04-09 07:07:01',
            ),
            19 => 
            array (
                'id' => 20,
                'start_date' => '2025-04-08',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-09 07:40:24',
                'updated_at' => '2025-04-09 07:40:54',
            ),
            20 => 
            array (
                'id' => 21,
                'start_date' => '2025-04-08',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-09 07:41:02',
                'updated_at' => '2025-04-09 07:41:54',
            ),
            21 => 
            array (
                'id' => 22,
                'start_date' => '2025-04-08',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-09 07:47:36',
                'updated_at' => '2025-04-09 07:48:29',
            ),
            22 => 
            array (
                'id' => 23,
                'start_date' => '2025-04-08',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-09 10:30:36',
                'updated_at' => '2025-04-09 10:30:54',
            ),
            23 => 
            array (
                'id' => 24,
                'start_date' => '2025-04-08',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-09 10:31:02',
                'updated_at' => '2025-04-09 10:31:14',
            ),
            24 => 
            array (
                'id' => 25,
                'start_date' => '2025-04-08',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-09 10:33:06',
                'updated_at' => '2025-04-09 10:33:22',
            ),
            25 => 
            array (
                'id' => 26,
                'start_date' => '2025-04-08',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-09 10:44:45',
                'updated_at' => '2025-04-09 10:45:02',
            ),
            26 => 
            array (
                'id' => 27,
                'start_date' => '2025-04-09',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-09 22:45:23',
                'updated_at' => '2025-04-09 22:48:32',
            ),
            27 => 
            array (
                'id' => 28,
                'start_date' => '2025-04-09',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-09 22:46:26',
                'updated_at' => '2025-04-09 22:48:01',
            ),
            28 => 
            array (
                'id' => 29,
                'start_date' => '2025-04-09',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-09 22:48:39',
                'updated_at' => '2025-04-10 03:10:26',
            ),
            29 => 
            array (
                'id' => 30,
                'start_date' => '2025-04-09',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-10 06:45:40',
                'updated_at' => '2025-04-10 06:45:53',
            ),
            30 => 
            array (
                'id' => 31,
                'start_date' => '2025-04-10',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-11 06:11:33',
                'updated_at' => '2025-04-11 06:11:46',
            ),
            31 => 
            array (
                'id' => 32,
                'start_date' => '2025-04-10',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-11 06:12:30',
                'updated_at' => '2025-04-11 06:12:41',
            ),
            32 => 
            array (
                'id' => 33,
                'start_date' => '2025-04-10',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-11 06:12:44',
                'updated_at' => '2025-04-11 06:12:53',
            ),
            33 => 
            array (
                'id' => 34,
                'start_date' => '2025-04-12',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-13 00:16:31',
                'updated_at' => '2025-04-13 00:16:43',
            ),
            34 => 
            array (
                'id' => 35,
                'start_date' => '2025-04-12',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-13 01:01:07',
                'updated_at' => '2025-04-13 02:26:25',
            ),
            35 => 
            array (
                'id' => 36,
                'start_date' => '2025-04-12',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-13 02:36:39',
                'updated_at' => '2025-04-13 02:36:54',
            ),
            36 => 
            array (
                'id' => 37,
                'start_date' => '2025-04-12',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-13 04:30:53',
                'updated_at' => '2025-04-13 04:31:00',
            ),
            37 => 
            array (
                'id' => 38,
                'start_date' => '2025-04-12',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-13 05:50:41',
                'updated_at' => '2025-04-13 05:50:49',
            ),
            38 => 
            array (
                'id' => 39,
                'start_date' => '2025-04-12',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-13 06:07:09',
                'updated_at' => '2025-04-13 06:07:25',
            ),
            39 => 
            array (
                'id' => 40,
                'start_date' => '2025-04-13',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-14 02:14:36',
                'updated_at' => '2025-04-14 02:14:48',
            ),
            40 => 
            array (
                'id' => 41,
                'start_date' => '2025-04-13',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-14 04:03:11',
                'updated_at' => '2025-04-14 04:35:37',
            ),
            41 => 
            array (
                'id' => 42,
                'start_date' => '2025-04-14',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-14 18:46:22',
                'updated_at' => '2025-04-14 18:46:31',
            ),
            42 => 
            array (
                'id' => 43,
                'start_date' => '2025-04-14',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-14 18:46:35',
                'updated_at' => '2025-04-14 18:46:46',
            ),
            43 => 
            array (
                'id' => 44,
                'start_date' => '2025-04-14',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-14 21:37:06',
                'updated_at' => '2025-04-14 21:37:38',
            ),
            44 => 
            array (
                'id' => 45,
                'start_date' => '2025-04-14',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-04-14 21:57:07',
                'updated_at' => '2025-04-14 21:59:58',
            ),
            45 => 
            array (
                'id' => 46,
                'start_date' => '2025-05-03',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-05-03 11:58:40',
                'updated_at' => '2025-05-10 23:27:22',
            ),
            46 => 
            array (
                'id' => 47,
                'start_date' => '2025-05-03',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-05-03 11:59:27',
                'updated_at' => '2025-05-10 23:26:57',
            ),
            47 => 
            array (
                'id' => 48,
                'start_date' => '2025-05-10',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-05-10 23:27:29',
                'updated_at' => '2025-05-10 23:27:41',
            ),
            48 => 
            array (
                'id' => 49,
                'start_date' => '2025-05-10',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-05-10 23:27:44',
                'updated_at' => '2025-05-10 23:27:54',
            ),
            49 => 
            array (
                'id' => 50,
                'start_date' => '2025-05-10',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-05-10 23:27:57',
                'updated_at' => '2025-05-10 23:28:05',
            ),
            50 => 
            array (
                'id' => 51,
                'start_date' => '2025-05-10',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-05-10 23:28:08',
                'updated_at' => '2025-05-10 23:28:16',
            ),
            51 => 
            array (
                'id' => 52,
                'start_date' => '2025-05-10',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-05-10 23:28:19',
                'updated_at' => '2025-05-10 23:28:30',
            ),
            52 => 
            array (
                'id' => 53,
                'start_date' => '2025-05-10',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-05-10 23:28:33',
                'updated_at' => '2025-05-10 23:28:46',
            ),
            53 => 
            array (
                'id' => 54,
                'start_date' => '2025-05-10',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-05-10 23:28:50',
                'updated_at' => '2025-05-10 23:28:59',
            ),
            54 => 
            array (
                'id' => 55,
                'start_date' => '2025-05-10',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-05-10 23:29:02',
                'updated_at' => '2025-05-11 19:57:38',
            ),
            55 => 
            array (
                'id' => 56,
                'start_date' => '2025-05-11',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-05-11 19:57:42',
                'updated_at' => '2025-05-11 19:57:55',
            ),
            56 => 
            array (
                'id' => 57,
                'start_date' => '2025-05-11',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-05-11 19:58:00',
                'updated_at' => '2025-05-11 19:58:25',
            ),
            57 => 
            array (
                'id' => 58,
                'start_date' => '2025-05-11',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-05-11 19:58:43',
                'updated_at' => '2025-05-11 19:59:08',
            ),
            58 => 
            array (
                'id' => 59,
                'start_date' => '2025-05-11',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-05-11 19:59:13',
                'updated_at' => '2025-05-11 19:59:36',
            ),
            59 => 
            array (
                'id' => 60,
                'start_date' => '2025-05-11',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-05-11 19:59:41',
                'updated_at' => '2025-05-11 20:00:04',
            ),
            60 => 
            array (
                'id' => 61,
                'start_date' => '2025-05-11',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-05-11 20:00:34',
                'updated_at' => '2025-05-11 20:01:02',
            ),
            61 => 
            array (
                'id' => 62,
                'start_date' => '2025-05-11',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-05-11 20:01:08',
                'updated_at' => '2025-05-11 20:01:32',
            ),
            62 => 
            array (
                'id' => 63,
                'start_date' => '2025-05-11',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-05-11 20:02:20',
                'updated_at' => '2025-05-11 20:02:42',
            ),
            63 => 
            array (
                'id' => 64,
                'start_date' => '2025-05-11',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-05-11 20:02:54',
                'updated_at' => '2025-05-11 20:03:05',
            ),
            64 => 
            array (
                'id' => 65,
                'start_date' => '2025-05-11',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-05-11 20:28:38',
                'updated_at' => '2025-05-12 13:26:41',
            ),
            65 => 
            array (
                'id' => 66,
                'start_date' => '2025-05-12',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-05-12 13:26:54',
                'updated_at' => '2025-05-12 13:27:04',
            ),
            66 => 
            array (
                'id' => 67,
                'start_date' => '2025-05-12',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-05-12 14:52:18',
                'updated_at' => '2025-05-12 14:52:31',
            ),
            67 => 
            array (
                'id' => 68,
                'start_date' => '2025-05-12',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-05-12 14:52:35',
                'updated_at' => '2025-05-12 14:52:51',
            ),
            68 => 
            array (
                'id' => 69,
                'start_date' => '2025-05-12',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-05-12 14:52:58',
                'updated_at' => '2025-05-12 14:53:08',
            ),
            69 => 
            array (
                'id' => 70,
                'start_date' => '2025-05-19',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-05-19 15:17:40',
                'updated_at' => '2025-05-22 01:31:25',
            ),
            70 => 
            array (
                'id' => 71,
                'start_date' => '2025-05-22',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-05-22 01:31:31',
                'updated_at' => '2025-05-22 01:31:51',
            ),
            71 => 
            array (
                'id' => 72,
                'start_date' => '2025-05-22',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-05-22 01:31:56',
                'updated_at' => '2025-05-22 01:32:16',
            ),
            72 => 
            array (
                'id' => 73,
                'start_date' => '2025-05-22',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-05-22 01:32:39',
                'updated_at' => '2025-05-22 01:32:47',
            ),
            73 => 
            array (
                'id' => 74,
                'start_date' => '2025-05-22',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-05-22 01:33:42',
                'updated_at' => '2025-05-22 01:33:53',
            ),
            74 => 
            array (
                'id' => 75,
                'start_date' => '2025-05-22',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-05-22 01:34:03',
                'updated_at' => '2025-05-22 01:34:11',
            ),
            75 => 
            array (
                'id' => 76,
                'start_date' => '2025-05-24',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-05-24 22:17:13',
                'updated_at' => '2025-05-30 01:00:28',
            ),
            76 => 
            array (
                'id' => 77,
                'start_date' => '2025-07-02',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-07-02 16:13:27',
                'updated_at' => '2025-07-29 19:17:14',
            ),
            77 => 
            array (
                'id' => 78,
                'start_date' => '2025-07-30',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-07-30 22:48:27',
                'updated_at' => '2025-09-14 21:48:45',
            ),
            78 => 
            array (
                'id' => 79,
                'start_date' => '2025-09-14',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-09-14 21:48:56',
                'updated_at' => '2025-09-14 21:49:53',
            ),
            79 => 
            array (
                'id' => 80,
                'start_date' => '2025-09-14',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-09-14 21:50:16',
                'updated_at' => '2025-09-14 21:57:43',
            ),
            80 => 
            array (
                'id' => 81,
                'start_date' => '2025-09-14',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-09-14 21:58:12',
                'updated_at' => '2025-09-14 21:58:32',
            ),
            81 => 
            array (
                'id' => 82,
                'start_date' => '2025-09-14',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-09-14 21:58:42',
                'updated_at' => '2025-09-14 21:58:57',
            ),
            82 => 
            array (
                'id' => 83,
                'start_date' => '2025-09-14',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-09-14 21:59:11',
                'updated_at' => '2025-09-14 22:00:12',
            ),
            83 => 
            array (
                'id' => 84,
                'start_date' => '2025-09-14',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-09-14 22:02:03',
                'updated_at' => '2025-09-14 22:02:34',
            ),
            84 => 
            array (
                'id' => 85,
                'start_date' => '2025-09-14',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-09-14 22:03:05',
                'updated_at' => '2025-09-14 22:03:19',
            ),
            85 => 
            array (
                'id' => 86,
                'start_date' => '2025-09-15',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-09-15 19:49:34',
                'updated_at' => '2025-09-15 19:50:26',
            ),
            86 => 
            array (
                'id' => 87,
                'start_date' => '2025-09-15',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-09-15 19:51:27',
                'updated_at' => '2025-09-15 19:53:33',
            ),
            87 => 
            array (
                'id' => 88,
                'start_date' => '2025-09-16',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-09-16 19:17:34',
                'updated_at' => '2025-09-16 19:17:44',
            ),
            88 => 
            array (
                'id' => 89,
                'start_date' => '2025-09-16',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-09-16 20:44:01',
                'updated_at' => '2025-09-16 20:46:43',
            ),
            89 => 
            array (
                'id' => 90,
                'start_date' => '2025-09-16',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-09-16 21:03:14',
                'updated_at' => '2025-09-16 21:03:24',
            ),
            90 => 
            array (
                'id' => 91,
                'start_date' => '2025-09-16',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-09-16 21:03:52',
                'updated_at' => '2025-09-16 21:04:02',
            ),
            91 => 
            array (
                'id' => 92,
                'start_date' => '2025-09-16',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-09-16 21:20:07',
                'updated_at' => '2025-09-16 21:20:15',
            ),
            92 => 
            array (
                'id' => 93,
                'start_date' => '2025-10-21',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-10-21 17:42:47',
                'updated_at' => '2025-10-21 17:42:59',
            ),
            93 => 
            array (
                'id' => 94,
                'start_date' => '2025-10-22',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-10-22 18:29:09',
                'updated_at' => '2025-11-09 20:03:28',
            ),
            94 => 
            array (
                'id' => 95,
                'start_date' => '2025-11-09',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-11-09 20:03:40',
                'updated_at' => '2025-11-09 20:04:38',
            ),
            95 => 
            array (
                'id' => 96,
                'start_date' => '2025-11-09',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-11-09 21:21:36',
                'updated_at' => '2025-11-23 12:42:15',
            ),
            96 => 
            array (
                'id' => 97,
                'start_date' => '2025-11-23',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2025-11-23 17:52:12',
                'updated_at' => '2025-11-23 17:52:16',
            ),
            97 => 
            array (
                'id' => 98,
                'start_date' => '2026-01-02',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2026-01-02 17:51:52',
                'updated_at' => '2026-02-15 19:11:10',
            ),
            98 => 
            array (
                'id' => 99,
                'start_date' => '2026-02-25',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2026-02-25 20:26:42',
                'updated_at' => '2026-02-25 20:27:16',
            ),
            99 => 
            array (
                'id' => 100,
                'start_date' => '2026-02-25',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2026-02-25 20:27:56',
                'updated_at' => '2026-02-25 20:28:22',
            ),
            100 => 
            array (
                'id' => 101,
                'start_date' => '2026-02-25',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2026-02-25 20:34:02',
                'updated_at' => '2026-02-25 20:34:16',
            ),
            101 => 
            array (
                'id' => 102,
                'start_date' => '2026-02-25',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2026-02-25 20:35:24',
                'updated_at' => '2026-02-25 20:35:39',
            ),
            102 => 
            array (
                'id' => 103,
                'start_date' => '2026-03-07',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2026-03-07 21:56:20',
                'updated_at' => '2026-03-07 21:57:29',
            ),
            103 => 
            array (
                'id' => 104,
                'start_date' => '2026-03-29',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2026-03-29 13:00:13',
                'updated_at' => '2026-03-29 13:05:10',
            ),
            104 => 
            array (
                'id' => 105,
                'start_date' => '2026-03-29',
                'complete' => 1,
                'resolved' => 1,
                'created_at' => '2026-03-29 13:05:16',
                'updated_at' => '2026-03-29 13:06:27',
            ),
            105 => 
            array (
                'id' => 106,
                'start_date' => '2026-03-29',
                'complete' => 1,
                'resolved' => 0,
                'created_at' => '2026-03-29 13:06:34',
                'updated_at' => '2026-04-04 14:33:05',
            ),
        ));
        
        
    }
}