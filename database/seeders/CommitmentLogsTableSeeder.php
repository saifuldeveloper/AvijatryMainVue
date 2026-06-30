<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CommitmentLogsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('commitment_logs')->delete();
        
        \DB::table('commitment_logs')->insert(array (
            0 => 
            array (
                'id' => 1,
                'commitment_id' => 1,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2025-04-30',
                'created_at' => '2025-04-30 01:06:05',
                'updated_at' => '2025-04-30 01:06:05',
            ),
            1 => 
            array (
                'id' => 2,
                'commitment_id' => 2,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2025-04-30',
                'created_at' => '2025-04-30 01:06:36',
                'updated_at' => '2025-04-30 01:06:36',
            ),
            2 => 
            array (
                'id' => 3,
                'commitment_id' => 3,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2025-05-10',
                'created_at' => '2025-04-30 01:07:06',
                'updated_at' => '2025-04-30 01:07:06',
            ),
            3 => 
            array (
                'id' => 4,
                'commitment_id' => 4,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2025-05-05',
                'created_at' => '2025-04-30 01:08:30',
                'updated_at' => '2025-04-30 01:08:30',
            ),
            4 => 
            array (
                'id' => 5,
                'commitment_id' => 3,
                'paid_amount' => NULL,
                'status' => 'full-paid',
                'commitment_date' => '2025-04-29',
                'created_at' => '2025-04-30 01:08:58',
                'updated_at' => '2025-04-30 01:08:58',
            ),
            5 => 
            array (
                'id' => 6,
                'commitment_id' => 1,
                'paid_amount' => NULL,
                'status' => 'full-paid',
                'commitment_date' => '2025-04-29',
                'created_at' => '2025-04-30 01:09:07',
                'updated_at' => '2025-04-30 01:09:07',
            ),
            6 => 
            array (
                'id' => 7,
                'commitment_id' => 5,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2025-04-05',
                'created_at' => '2025-04-30 01:10:44',
                'updated_at' => '2025-04-30 01:10:44',
            ),
            7 => 
            array (
                'id' => 8,
                'commitment_id' => 1,
                'paid_amount' => NULL,
                'status' => 'full-paid',
                'commitment_date' => '2025-04-29',
                'created_at' => '2025-04-30 01:10:53',
                'updated_at' => '2025-04-30 01:10:53',
            ),
            8 => 
            array (
                'id' => 9,
                'commitment_id' => 2,
                'paid_amount' => NULL,
                'status' => 'full-paid',
                'commitment_date' => '2025-04-29',
                'created_at' => '2025-04-30 01:11:00',
                'updated_at' => '2025-04-30 01:11:00',
            ),
            9 => 
            array (
                'id' => 10,
                'commitment_id' => 3,
                'paid_amount' => NULL,
                'status' => 'full-paid',
                'commitment_date' => '2025-04-29',
                'created_at' => '2025-04-30 01:11:06',
                'updated_at' => '2025-04-30 01:11:06',
            ),
            10 => 
            array (
                'id' => 11,
                'commitment_id' => 4,
                'paid_amount' => NULL,
                'status' => 'full-paid',
                'commitment_date' => '2025-04-29',
                'created_at' => '2025-04-30 01:11:13',
                'updated_at' => '2025-04-30 01:11:13',
            ),
            11 => 
            array (
                'id' => 12,
                'commitment_id' => 5,
                'paid_amount' => NULL,
                'status' => 'full-paid',
                'commitment_date' => '2025-04-29',
                'created_at' => '2025-04-30 01:11:20',
                'updated_at' => '2025-04-30 01:11:20',
            ),
            12 => 
            array (
                'id' => 13,
                'commitment_id' => 6,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2025-05-20',
                'created_at' => '2025-04-30 01:12:26',
                'updated_at' => '2025-04-30 01:12:26',
            ),
            13 => 
            array (
                'id' => 14,
                'commitment_id' => 7,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2025-06-10',
                'created_at' => '2025-04-30 01:13:06',
                'updated_at' => '2025-04-30 01:13:06',
            ),
            14 => 
            array (
                'id' => 15,
                'commitment_id' => 8,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2025-07-10',
                'created_at' => '2025-04-30 01:13:57',
                'updated_at' => '2025-04-30 01:13:57',
            ),
            15 => 
            array (
                'id' => 16,
                'commitment_id' => 9,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2025-08-10',
                'created_at' => '2025-04-30 01:14:25',
                'updated_at' => '2025-04-30 01:14:25',
            ),
            16 => 
            array (
                'id' => 17,
                'commitment_id' => 10,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2025-09-10',
                'created_at' => '2025-04-30 01:14:38',
                'updated_at' => '2025-04-30 01:14:38',
            ),
            17 => 
            array (
                'id' => 18,
                'commitment_id' => 11,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2025-05-05',
                'created_at' => '2025-04-30 01:15:30',
                'updated_at' => '2025-04-30 01:15:30',
            ),
            18 => 
            array (
                'id' => 19,
                'commitment_id' => 12,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2025-06-05',
                'created_at' => '2025-04-30 01:16:06',
                'updated_at' => '2025-04-30 01:16:06',
            ),
            19 => 
            array (
                'id' => 20,
                'commitment_id' => 13,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2025-05-03',
                'created_at' => '2025-04-30 01:20:11',
                'updated_at' => '2025-04-30 01:20:11',
            ),
            20 => 
            array (
                'id' => 21,
                'commitment_id' => 14,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2025-05-13',
                'created_at' => '2025-04-30 01:20:55',
                'updated_at' => '2025-04-30 01:20:55',
            ),
            21 => 
            array (
                'id' => 22,
                'commitment_id' => 15,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2025-04-30',
                'created_at' => '2025-04-30 01:50:04',
                'updated_at' => '2025-04-30 01:50:04',
            ),
            22 => 
            array (
                'id' => 23,
                'commitment_id' => 16,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2025-05-05',
                'created_at' => '2025-04-30 01:58:33',
                'updated_at' => '2025-04-30 01:58:33',
            ),
            23 => 
            array (
                'id' => 24,
                'commitment_id' => 17,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2025-04-30',
                'created_at' => '2025-04-30 01:59:07',
                'updated_at' => '2025-04-30 01:59:07',
            ),
            24 => 
            array (
                'id' => 25,
                'commitment_id' => 17,
                'paid_amount' => NULL,
                'status' => 'full-paid',
                'commitment_date' => '2025-04-29',
                'created_at' => '2025-04-30 01:59:35',
                'updated_at' => '2025-04-30 01:59:35',
            ),
            25 => 
            array (
                'id' => 26,
                'commitment_id' => 18,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2025-05-05',
                'created_at' => '2025-04-30 02:00:31',
                'updated_at' => '2025-04-30 02:00:31',
            ),
            26 => 
            array (
                'id' => 27,
                'commitment_id' => 19,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2025-05-05',
                'created_at' => '2025-04-30 02:01:15',
                'updated_at' => '2025-04-30 02:01:15',
            ),
            27 => 
            array (
                'id' => 28,
                'commitment_id' => 20,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2025-05-05',
                'created_at' => '2025-04-30 02:02:15',
                'updated_at' => '2025-04-30 02:02:15',
            ),
            28 => 
            array (
                'id' => 29,
                'commitment_id' => 21,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2025-05-05',
                'created_at' => '2025-04-30 02:03:27',
                'updated_at' => '2025-04-30 02:03:27',
            ),
            29 => 
            array (
                'id' => 30,
                'commitment_id' => 22,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2025-05-05',
                'created_at' => '2025-04-30 02:04:45',
                'updated_at' => '2025-04-30 02:04:45',
            ),
            30 => 
            array (
                'id' => 31,
                'commitment_id' => 23,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2025-04-17',
                'created_at' => '2025-04-30 02:07:30',
                'updated_at' => '2025-04-30 02:07:30',
            ),
            31 => 
            array (
                'id' => 32,
                'commitment_id' => 24,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2025-05-20',
                'created_at' => '2025-04-30 02:07:59',
                'updated_at' => '2025-04-30 02:07:59',
            ),
            32 => 
            array (
                'id' => 33,
                'commitment_id' => 25,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2025-06-15',
                'created_at' => '2025-04-30 02:08:33',
                'updated_at' => '2025-04-30 02:08:33',
            ),
            33 => 
            array (
                'id' => 34,
                'commitment_id' => 26,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2025-06-10',
                'created_at' => '2025-04-30 02:08:58',
                'updated_at' => '2025-04-30 02:08:58',
            ),
            34 => 
            array (
                'id' => 35,
                'commitment_id' => 27,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2025-04-23',
                'created_at' => '2025-04-30 02:10:02',
                'updated_at' => '2025-04-30 02:10:02',
            ),
            35 => 
            array (
                'id' => 36,
                'commitment_id' => 28,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2025-04-30',
                'created_at' => '2025-04-30 02:10:16',
                'updated_at' => '2025-04-30 02:10:16',
            ),
            36 => 
            array (
                'id' => 37,
                'commitment_id' => 29,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2025-05-05',
                'created_at' => '2025-04-30 02:11:36',
                'updated_at' => '2025-04-30 02:11:36',
            ),
            37 => 
            array (
                'id' => 38,
                'commitment_id' => 30,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2025-05-05',
                'created_at' => '2025-04-30 02:12:50',
                'updated_at' => '2025-04-30 02:12:50',
            ),
            38 => 
            array (
                'id' => 39,
                'commitment_id' => 31,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2025-05-05',
                'created_at' => '2025-04-30 02:14:30',
                'updated_at' => '2025-04-30 02:14:30',
            ),
            39 => 
            array (
                'id' => 40,
                'commitment_id' => 32,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2025-04-05',
                'created_at' => '2025-04-30 02:14:56',
                'updated_at' => '2025-04-30 02:14:56',
            ),
            40 => 
            array (
                'id' => 41,
                'commitment_id' => 33,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2025-05-05',
                'created_at' => '2025-04-30 02:16:00',
                'updated_at' => '2025-04-30 02:16:00',
            ),
            41 => 
            array (
                'id' => 42,
                'commitment_id' => 34,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2025-05-03',
                'created_at' => '2025-04-30 02:20:50',
                'updated_at' => '2025-04-30 02:20:50',
            ),
            42 => 
            array (
                'id' => 43,
                'commitment_id' => 35,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2025-05-10',
                'created_at' => '2025-04-30 02:23:51',
                'updated_at' => '2025-04-30 02:23:51',
            ),
            43 => 
            array (
                'id' => 44,
                'commitment_id' => 36,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2025-05-10',
                'created_at' => '2025-04-30 02:25:45',
                'updated_at' => '2025-04-30 02:25:45',
            ),
            44 => 
            array (
                'id' => 45,
                'commitment_id' => 37,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2025-05-10',
                'created_at' => '2025-04-30 03:21:53',
                'updated_at' => '2025-04-30 03:21:53',
            ),
            45 => 
            array (
                'id' => 46,
                'commitment_id' => 38,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2025-05-20',
                'created_at' => '2025-04-30 03:22:19',
                'updated_at' => '2025-04-30 03:22:19',
            ),
            46 => 
            array (
                'id' => 47,
                'commitment_id' => 23,
                'paid_amount' => 100000.0,
                'status' => 'full-paid',
                'commitment_date' => '2025-05-12',
                'created_at' => '2025-05-13 12:25:05',
                'updated_at' => '2025-05-13 12:25:05',
            ),
            47 => 
            array (
                'id' => 48,
                'commitment_id' => 22,
                'paid_amount' => NULL,
                'status' => 'full-paid',
                'commitment_date' => '2025-05-16',
                'created_at' => '2025-05-16 16:40:06',
                'updated_at' => '2025-05-16 16:40:06',
            ),
            48 => 
            array (
                'id' => 49,
                'commitment_id' => 7,
                'paid_amount' => NULL,
                'status' => 'full-paid',
                'commitment_date' => '2025-05-21',
                'created_at' => '2025-05-21 17:40:43',
                'updated_at' => '2025-05-21 17:40:43',
            ),
            49 => 
            array (
                'id' => 50,
                'commitment_id' => 6,
                'paid_amount' => NULL,
                'status' => 'full-paid',
                'commitment_date' => '2025-05-21',
                'created_at' => '2025-05-21 17:40:55',
                'updated_at' => '2025-05-21 17:40:55',
            ),
            50 => 
            array (
                'id' => 51,
                'commitment_id' => 8,
                'paid_amount' => NULL,
                'status' => 'full-paid',
                'commitment_date' => '2025-05-21',
                'created_at' => '2025-05-21 17:41:11',
                'updated_at' => '2025-05-21 17:41:11',
            ),
            51 => 
            array (
                'id' => 52,
                'commitment_id' => 9,
                'paid_amount' => NULL,
                'status' => 'full-paid',
                'commitment_date' => '2025-05-21',
                'created_at' => '2025-05-21 17:41:16',
                'updated_at' => '2025-05-21 17:41:16',
            ),
            52 => 
            array (
                'id' => 53,
                'commitment_id' => 10,
                'paid_amount' => NULL,
                'status' => 'full-paid',
                'commitment_date' => '2025-05-21',
                'created_at' => '2025-05-21 17:41:22',
                'updated_at' => '2025-05-21 17:41:22',
            ),
            53 => 
            array (
                'id' => 54,
                'commitment_id' => 19,
                'paid_amount' => NULL,
                'status' => 'full-paid',
                'commitment_date' => '2025-05-23',
                'created_at' => '2025-05-23 16:22:22',
                'updated_at' => '2025-05-23 16:22:22',
            ),
            54 => 
            array (
                'id' => 55,
                'commitment_id' => 26,
                'paid_amount' => NULL,
                'status' => 'full-paid',
                'commitment_date' => '2025-05-23',
                'created_at' => '2025-05-23 18:47:11',
                'updated_at' => '2025-05-23 18:47:11',
            ),
            55 => 
            array (
                'id' => 56,
                'commitment_id' => 31,
                'paid_amount' => NULL,
                'status' => 'full-paid',
                'commitment_date' => '2025-05-24',
                'created_at' => '2025-05-24 15:57:03',
                'updated_at' => '2025-05-24 15:57:03',
            ),
            56 => 
            array (
                'id' => 57,
                'commitment_id' => 30,
                'paid_amount' => NULL,
                'status' => 'full-paid',
                'commitment_date' => '2025-05-26',
                'created_at' => '2025-05-26 18:25:21',
                'updated_at' => '2025-05-26 18:25:21',
            ),
            57 => 
            array (
                'id' => 58,
                'commitment_id' => 33,
                'paid_amount' => NULL,
                'status' => 'full-paid',
                'commitment_date' => '2025-05-28',
                'created_at' => '2025-05-28 14:25:44',
                'updated_at' => '2025-05-28 14:25:44',
            ),
            58 => 
            array (
                'id' => 59,
                'commitment_id' => 16,
                'paid_amount' => NULL,
                'status' => 'full-paid',
                'commitment_date' => '2025-05-28',
                'created_at' => '2025-05-28 22:53:39',
                'updated_at' => '2025-05-28 22:53:39',
            ),
            59 => 
            array (
                'id' => 60,
                'commitment_id' => 5,
                'paid_amount' => NULL,
                'status' => 'full-paid',
                'commitment_date' => '2025-05-29',
                'created_at' => '2025-05-29 18:23:51',
                'updated_at' => '2025-05-29 18:23:51',
            ),
            60 => 
            array (
                'id' => 61,
                'commitment_id' => 11,
                'paid_amount' => NULL,
                'status' => 'full-paid',
                'commitment_date' => '2025-05-30',
                'created_at' => '2025-05-30 00:04:55',
                'updated_at' => '2025-05-30 00:04:55',
            ),
            61 => 
            array (
                'id' => 62,
                'commitment_id' => 12,
                'paid_amount' => NULL,
                'status' => 'full-paid',
                'commitment_date' => '2025-05-30',
                'created_at' => '2025-05-30 00:05:03',
                'updated_at' => '2025-05-30 00:05:03',
            ),
            62 => 
            array (
                'id' => 63,
                'commitment_id' => 29,
                'paid_amount' => NULL,
                'status' => 'full-paid',
                'commitment_date' => '2025-05-31',
                'created_at' => '2025-05-31 21:39:35',
                'updated_at' => '2025-05-31 21:39:35',
            ),
            63 => 
            array (
                'id' => 64,
                'commitment_id' => 21,
                'paid_amount' => NULL,
                'status' => 'full-paid',
                'commitment_date' => '2025-08-11',
                'created_at' => '2025-08-11 17:36:20',
                'updated_at' => '2025-08-11 17:36:20',
            ),
            64 => 
            array (
                'id' => 65,
                'commitment_id' => 28,
                'paid_amount' => NULL,
                'status' => 'full-paid',
                'commitment_date' => '2025-11-27',
                'created_at' => '2025-11-27 14:32:32',
                'updated_at' => '2025-11-27 14:32:32',
            ),
            65 => 
            array (
                'id' => 66,
                'commitment_id' => 27,
                'paid_amount' => NULL,
                'status' => 'full-paid',
                'commitment_date' => '2025-11-27',
                'created_at' => '2025-11-27 14:32:52',
                'updated_at' => '2025-11-27 14:32:52',
            ),
            66 => 
            array (
                'id' => 67,
                'commitment_id' => 233,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2026-04-19',
                'created_at' => '2026-04-08 16:44:46',
                'updated_at' => '2026-04-08 16:44:46',
            ),
            67 => 
            array (
                'id' => 68,
                'commitment_id' => 234,
                'paid_amount' => NULL,
                'status' => 'initial',
                'commitment_date' => '2026-04-30',
                'created_at' => '2026-04-08 16:45:33',
                'updated_at' => '2026-04-08 16:45:33',
            ),
        ));
        
        
    }
}