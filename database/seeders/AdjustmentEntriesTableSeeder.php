<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AdjustmentEntriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('adjustment_entries')->delete();
        
        \DB::table('adjustment_entries')->insert(array (
            0 => 
            array (
                'id' => 1,
                'shoe_id' => '10c',
                'count' => 6,
                'description' => NULL,
                'type' => 'in',
                'created_at' => '2025-04-06 03:35:59',
                'updated_at' => '2025-04-06 03:35:59',
            ),
            1 => 
            array (
                'id' => 2,
                'shoe_id' => '17b',
                'count' => -6,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-06 06:20:58',
                'updated_at' => '2025-04-06 06:20:58',
            ),
            2 => 
            array (
                'id' => 3,
                'shoe_id' => '17a',
                'count' => -6,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-06 06:21:22',
                'updated_at' => '2025-04-06 06:21:22',
            ),
            3 => 
            array (
                'id' => 4,
                'shoe_id' => '179',
                'count' => -6,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-06 06:22:11',
                'updated_at' => '2025-04-06 06:22:11',
            ),
            4 => 
            array (
                'id' => 5,
                'shoe_id' => '184',
                'count' => -7,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-06 06:34:08',
                'updated_at' => '2025-04-06 06:34:08',
            ),
            5 => 
            array (
                'id' => 6,
                'shoe_id' => '121',
                'count' => -1,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-07 06:43:20',
                'updated_at' => '2025-04-07 06:43:20',
            ),
            6 => 
            array (
                'id' => 7,
                'shoe_id' => '22e',
                'count' => -1,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-07 07:02:53',
                'updated_at' => '2025-04-07 07:02:53',
            ),
            7 => 
            array (
                'id' => 8,
                'shoe_id' => '230',
                'count' => -6,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-07 22:44:26',
                'updated_at' => '2025-04-07 22:44:26',
            ),
            8 => 
            array (
                'id' => 9,
                'shoe_id' => '237',
                'count' => -6,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-07 22:44:56',
                'updated_at' => '2025-04-07 22:44:56',
            ),
            9 => 
            array (
                'id' => 10,
                'shoe_id' => '2b0',
                'count' => -120,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-08 00:30:37',
                'updated_at' => '2025-04-08 00:30:37',
            ),
            10 => 
            array (
                'id' => 11,
                'shoe_id' => '2af',
                'count' => -1,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-08 02:00:36',
                'updated_at' => '2025-04-08 02:00:36',
            ),
            11 => 
            array (
                'id' => 12,
                'shoe_id' => '1f9',
                'count' => -1,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-08 02:42:28',
                'updated_at' => '2025-04-08 02:42:28',
            ),
            12 => 
            array (
                'id' => 13,
                'shoe_id' => '232',
                'count' => -6,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-08 03:21:34',
                'updated_at' => '2025-04-08 03:21:34',
            ),
            13 => 
            array (
                'id' => 14,
                'shoe_id' => '233',
                'count' => -6,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-08 03:22:08',
                'updated_at' => '2025-04-08 03:22:08',
            ),
            14 => 
            array (
                'id' => 15,
                'shoe_id' => '326',
                'count' => -1,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-08 06:02:16',
                'updated_at' => '2025-04-08 06:02:16',
            ),
            15 => 
            array (
                'id' => 16,
                'shoe_id' => '351',
                'count' => -1,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-08 07:22:19',
                'updated_at' => '2025-04-08 07:22:19',
            ),
            16 => 
            array (
                'id' => 17,
                'shoe_id' => '3c4',
                'count' => -1,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-09 03:59:13',
                'updated_at' => '2025-04-09 03:59:13',
            ),
            17 => 
            array (
                'id' => 18,
                'shoe_id' => '404',
                'count' => -1,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-09 07:07:01',
                'updated_at' => '2025-04-09 07:07:01',
            ),
            18 => 
            array (
                'id' => 19,
                'shoe_id' => '3eb',
                'count' => -6,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-09 07:41:54',
                'updated_at' => '2025-04-09 07:41:54',
            ),
            19 => 
            array (
                'id' => 20,
                'shoe_id' => '40b',
                'count' => -1,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-09 07:48:29',
                'updated_at' => '2025-04-09 07:48:29',
            ),
            20 => 
            array (
                'id' => 21,
                'shoe_id' => '438',
                'count' => -6,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-09 10:30:54',
                'updated_at' => '2025-04-09 10:30:54',
            ),
            21 => 
            array (
                'id' => 22,
                'shoe_id' => '420',
                'count' => -6,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-09 10:31:14',
                'updated_at' => '2025-04-09 10:31:14',
            ),
            22 => 
            array (
                'id' => 23,
                'shoe_id' => '481',
                'count' => -1,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-09 10:33:22',
                'updated_at' => '2025-04-09 10:33:22',
            ),
            23 => 
            array (
                'id' => 24,
                'shoe_id' => '476',
                'count' => -1,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-09 10:45:02',
                'updated_at' => '2025-04-09 10:45:02',
            ),
            24 => 
            array (
                'id' => 25,
                'shoe_id' => '46c',
                'count' => -6,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-09 22:48:32',
                'updated_at' => '2025-04-09 22:48:32',
            ),
            25 => 
            array (
                'id' => 26,
                'shoe_id' => '4ce',
                'count' => -120,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-10 03:10:26',
                'updated_at' => '2025-04-10 03:10:26',
            ),
            26 => 
            array (
                'id' => 27,
                'shoe_id' => '51b',
                'count' => -1,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-10 06:45:53',
                'updated_at' => '2025-04-10 06:45:53',
            ),
            27 => 
            array (
                'id' => 28,
                'shoe_id' => '5bd',
                'count' => -6,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-11 06:11:46',
                'updated_at' => '2025-04-11 06:11:46',
            ),
            28 => 
            array (
                'id' => 29,
                'shoe_id' => '5bd',
                'count' => -114,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-11 06:12:53',
                'updated_at' => '2025-04-11 06:12:53',
            ),
            29 => 
            array (
                'id' => 30,
                'shoe_id' => '5da',
                'count' => -1,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-13 00:16:43',
                'updated_at' => '2025-04-13 00:16:43',
            ),
            30 => 
            array (
                'id' => 31,
                'shoe_id' => '5d9',
                'count' => -12,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-13 02:26:25',
                'updated_at' => '2025-04-13 02:26:25',
            ),
            31 => 
            array (
                'id' => 32,
                'shoe_id' => '613',
                'count' => -120,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-13 02:36:54',
                'updated_at' => '2025-04-13 02:36:54',
            ),
            32 => 
            array (
                'id' => 33,
                'shoe_id' => '627',
                'count' => -1,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-13 04:31:00',
                'updated_at' => '2025-04-13 04:31:00',
            ),
            33 => 
            array (
                'id' => 34,
                'shoe_id' => '648',
                'count' => -1,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-13 05:50:49',
                'updated_at' => '2025-04-13 05:50:49',
            ),
            34 => 
            array (
                'id' => 35,
                'shoe_id' => '611',
                'count' => -6,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-13 06:07:25',
                'updated_at' => '2025-04-13 06:07:25',
            ),
            35 => 
            array (
                'id' => 36,
                'shoe_id' => '684',
                'count' => -6,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-14 02:14:48',
                'updated_at' => '2025-04-14 02:14:48',
            ),
            36 => 
            array (
                'id' => 37,
                'shoe_id' => '6c0',
                'count' => -12,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-14 04:35:37',
                'updated_at' => '2025-04-14 04:35:37',
            ),
            37 => 
            array (
                'id' => 38,
                'shoe_id' => '722',
                'count' => -1,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-14 18:46:31',
                'updated_at' => '2025-04-14 18:46:31',
            ),
            38 => 
            array (
                'id' => 39,
                'shoe_id' => '725',
                'count' => -1,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-14 18:46:46',
                'updated_at' => '2025-04-14 18:46:46',
            ),
            39 => 
            array (
                'id' => 40,
                'shoe_id' => '750',
                'count' => -6,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-14 21:37:38',
                'updated_at' => '2025-04-14 21:37:38',
            ),
            40 => 
            array (
                'id' => 41,
                'shoe_id' => '51b',
                'count' => -5,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-04-14 21:59:58',
                'updated_at' => '2025-04-14 21:59:58',
            ),
            41 => 
            array (
                'id' => 42,
                'shoe_id' => 'e19',
                'count' => -12,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-05-10 23:26:57',
                'updated_at' => '2025-05-10 23:26:57',
            ),
            42 => 
            array (
                'id' => 43,
                'shoe_id' => 'e18',
                'count' => -12,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-05-10 23:27:21',
                'updated_at' => '2025-05-10 23:27:21',
            ),
            43 => 
            array (
                'id' => 44,
                'shoe_id' => 'e17',
                'count' => -12,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-05-10 23:27:41',
                'updated_at' => '2025-05-10 23:27:41',
            ),
            44 => 
            array (
                'id' => 45,
                'shoe_id' => 'e16',
                'count' => -12,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-05-10 23:27:54',
                'updated_at' => '2025-05-10 23:27:54',
            ),
            45 => 
            array (
                'id' => 46,
                'shoe_id' => 'e15',
                'count' => -12,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-05-10 23:28:05',
                'updated_at' => '2025-05-10 23:28:05',
            ),
            46 => 
            array (
                'id' => 47,
                'shoe_id' => 'e14',
                'count' => -12,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-05-10 23:28:16',
                'updated_at' => '2025-05-10 23:28:16',
            ),
            47 => 
            array (
                'id' => 48,
                'shoe_id' => 'e13',
                'count' => -12,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-05-10 23:28:30',
                'updated_at' => '2025-05-10 23:28:30',
            ),
            48 => 
            array (
                'id' => 49,
                'shoe_id' => 'e12',
                'count' => -12,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-05-10 23:28:46',
                'updated_at' => '2025-05-10 23:28:46',
            ),
            49 => 
            array (
                'id' => 50,
                'shoe_id' => 'e11',
                'count' => -12,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-05-10 23:28:59',
                'updated_at' => '2025-05-10 23:28:59',
            ),
            50 => 
            array (
                'id' => 51,
                'shoe_id' => 'ed8',
                'count' => 6,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-05-11 20:00:04',
                'updated_at' => '2025-05-11 20:00:04',
            ),
            51 => 
            array (
                'id' => 52,
                'shoe_id' => 'ed8',
                'count' => 42,
                'description' => NULL,
                'type' => 'in',
                'created_at' => '2025-05-11 20:01:32',
                'updated_at' => '2025-05-11 20:01:32',
            ),
            52 => 
            array (
                'id' => 53,
                'shoe_id' => 'ed8',
                'count' => 18,
                'description' => NULL,
                'type' => 'in',
                'created_at' => '2025-05-11 20:02:42',
                'updated_at' => '2025-05-11 20:02:42',
            ),
            53 => 
            array (
                'id' => 54,
                'shoe_id' => 'ed8',
                'count' => -6,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-05-11 20:03:05',
                'updated_at' => '2025-05-11 20:03:05',
            ),
            54 => 
            array (
                'id' => 55,
                'shoe_id' => 'ed7',
                'count' => 84,
                'description' => NULL,
                'type' => 'in',
                'created_at' => '2025-05-12 13:26:41',
                'updated_at' => '2025-05-12 13:26:41',
            ),
            55 => 
            array (
                'id' => 56,
                'shoe_id' => 'ed7',
                'count' => -36,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-05-12 13:27:04',
                'updated_at' => '2025-05-12 13:27:04',
            ),
            56 => 
            array (
                'id' => 57,
                'shoe_id' => 'ed8',
                'count' => -48,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-05-12 14:52:51',
                'updated_at' => '2025-05-12 14:52:51',
            ),
            57 => 
            array (
                'id' => 58,
                'shoe_id' => 'ed7',
                'count' => -48,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-05-12 14:53:08',
                'updated_at' => '2025-05-12 14:53:08',
            ),
            58 => 
            array (
                'id' => 59,
                'shoe_id' => 'a30',
                'count' => 6,
                'description' => NULL,
                'type' => 'in',
                'created_at' => '2025-05-22 01:31:25',
                'updated_at' => '2025-05-22 01:31:25',
            ),
            59 => 
            array (
                'id' => 60,
                'shoe_id' => '94b',
                'count' => 6,
                'description' => NULL,
                'type' => 'in',
                'created_at' => '2025-05-22 01:31:51',
                'updated_at' => '2025-05-22 01:31:51',
            ),
            60 => 
            array (
                'id' => 61,
                'shoe_id' => '8f1',
                'count' => 6,
                'description' => NULL,
                'type' => 'in',
                'created_at' => '2025-05-22 01:32:16',
                'updated_at' => '2025-05-22 01:32:16',
            ),
            61 => 
            array (
                'id' => 62,
                'shoe_id' => 'a30',
                'count' => 6,
                'description' => NULL,
                'type' => 'in',
                'created_at' => '2025-05-22 01:32:47',
                'updated_at' => '2025-05-22 01:32:47',
            ),
            62 => 
            array (
                'id' => 63,
                'shoe_id' => '94b',
                'count' => 6,
                'description' => NULL,
                'type' => 'in',
                'created_at' => '2025-05-22 01:33:53',
                'updated_at' => '2025-05-22 01:33:53',
            ),
            63 => 
            array (
                'id' => 64,
                'shoe_id' => '8f1',
                'count' => 6,
                'description' => NULL,
                'type' => 'in',
                'created_at' => '2025-05-22 01:34:11',
                'updated_at' => '2025-05-22 01:34:11',
            ),
            64 => 
            array (
                'id' => 65,
                'shoe_id' => '61a',
                'count' => 1,
                'description' => NULL,
                'type' => 'in',
                'created_at' => '2025-05-30 01:00:28',
                'updated_at' => '2025-05-30 01:00:28',
            ),
            65 => 
            array (
                'id' => 66,
                'shoe_id' => '244e',
                'count' => -12,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-07-29 19:17:14',
                'updated_at' => '2025-07-29 19:17:14',
            ),
            66 => 
            array (
                'id' => 67,
                'shoe_id' => '2d1c',
                'count' => -12,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-09-14 21:49:53',
                'updated_at' => '2025-09-14 21:49:53',
            ),
            67 => 
            array (
                'id' => 68,
                'shoe_id' => '2d1b',
                'count' => -12,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-09-14 21:57:43',
                'updated_at' => '2025-09-14 21:57:43',
            ),
            68 => 
            array (
                'id' => 69,
                'shoe_id' => '2d1f',
                'count' => -12,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-09-14 21:58:32',
                'updated_at' => '2025-09-14 21:58:32',
            ),
            69 => 
            array (
                'id' => 70,
                'shoe_id' => '2d20',
                'count' => -12,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-09-14 21:58:57',
                'updated_at' => '2025-09-14 21:58:57',
            ),
            70 => 
            array (
                'id' => 71,
                'shoe_id' => '2d1b',
                'count' => 12,
                'description' => NULL,
                'type' => 'in',
                'created_at' => '2025-09-14 22:00:12',
                'updated_at' => '2025-09-14 22:00:12',
            ),
            71 => 
            array (
                'id' => 72,
                'shoe_id' => '2507',
                'count' => 12,
                'description' => NULL,
                'type' => 'in',
                'created_at' => '2025-09-14 22:00:12',
                'updated_at' => '2025-09-14 22:00:12',
            ),
            72 => 
            array (
                'id' => 73,
                'shoe_id' => '2506',
                'count' => 12,
                'description' => NULL,
                'type' => 'in',
                'created_at' => '2025-09-14 22:00:12',
                'updated_at' => '2025-09-14 22:00:12',
            ),
            73 => 
            array (
                'id' => 74,
                'shoe_id' => '2505',
                'count' => 12,
                'description' => NULL,
                'type' => 'in',
                'created_at' => '2025-09-14 22:00:12',
                'updated_at' => '2025-09-14 22:00:12',
            ),
            74 => 
            array (
                'id' => 75,
                'shoe_id' => '2d1b',
                'count' => -12,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-09-14 22:02:34',
                'updated_at' => '2025-09-14 22:02:34',
            ),
            75 => 
            array (
                'id' => 76,
                'shoe_id' => '2503',
                'count' => 12,
                'description' => NULL,
                'type' => 'in',
                'created_at' => '2025-09-14 22:03:19',
                'updated_at' => '2025-09-14 22:03:19',
            ),
            76 => 
            array (
                'id' => 77,
                'shoe_id' => '2d19',
                'count' => -6,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-09-15 19:50:26',
                'updated_at' => '2025-09-15 19:50:26',
            ),
            77 => 
            array (
                'id' => 78,
                'shoe_id' => '2d1a',
                'count' => -6,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-09-15 19:50:26',
                'updated_at' => '2025-09-15 19:50:26',
            ),
            78 => 
            array (
                'id' => 79,
                'shoe_id' => '2d13',
                'count' => -6,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-09-15 19:50:26',
                'updated_at' => '2025-09-15 19:50:26',
            ),
            79 => 
            array (
                'id' => 80,
                'shoe_id' => '2d14',
                'count' => -6,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-09-15 19:50:26',
                'updated_at' => '2025-09-15 19:50:26',
            ),
            80 => 
            array (
                'id' => 81,
                'shoe_id' => '2d1d',
                'count' => 6,
                'description' => NULL,
                'type' => 'in',
                'created_at' => '2025-09-15 19:53:33',
                'updated_at' => '2025-09-15 19:53:33',
            ),
            81 => 
            array (
                'id' => 82,
                'shoe_id' => '2507',
                'count' => 6,
                'description' => NULL,
                'type' => 'in',
                'created_at' => '2025-09-15 19:53:33',
                'updated_at' => '2025-09-15 19:53:33',
            ),
            82 => 
            array (
                'id' => 83,
                'shoe_id' => '2506',
                'count' => 6,
                'description' => NULL,
                'type' => 'in',
                'created_at' => '2025-09-15 19:53:33',
                'updated_at' => '2025-09-15 19:53:33',
            ),
            83 => 
            array (
                'id' => 84,
                'shoe_id' => '2505',
                'count' => 6,
                'description' => NULL,
                'type' => 'in',
                'created_at' => '2025-09-15 19:53:33',
                'updated_at' => '2025-09-15 19:53:33',
            ),
            84 => 
            array (
                'id' => 85,
                'shoe_id' => '142',
                'count' => 6,
                'description' => NULL,
                'type' => 'in',
                'created_at' => '2025-09-16 19:17:44',
                'updated_at' => '2025-09-16 19:17:44',
            ),
            85 => 
            array (
                'id' => 86,
                'shoe_id' => '2a8a',
                'count' => 6,
                'description' => NULL,
                'type' => 'in',
                'created_at' => '2025-09-16 20:46:43',
                'updated_at' => '2025-09-16 20:46:43',
            ),
            86 => 
            array (
                'id' => 87,
                'shoe_id' => '2a89',
                'count' => 6,
                'description' => NULL,
                'type' => 'in',
                'created_at' => '2025-09-16 20:46:43',
                'updated_at' => '2025-09-16 20:46:43',
            ),
            87 => 
            array (
                'id' => 88,
                'shoe_id' => '2a87',
                'count' => 6,
                'description' => NULL,
                'type' => 'in',
                'created_at' => '2025-09-16 20:46:43',
                'updated_at' => '2025-09-16 20:46:43',
            ),
            88 => 
            array (
                'id' => 89,
                'shoe_id' => '2a86',
                'count' => 6,
                'description' => NULL,
                'type' => 'in',
                'created_at' => '2025-09-16 20:46:43',
                'updated_at' => '2025-09-16 20:46:43',
            ),
            89 => 
            array (
                'id' => 90,
                'shoe_id' => '2845',
                'count' => 6,
                'description' => NULL,
                'type' => 'in',
                'created_at' => '2025-09-16 20:46:43',
                'updated_at' => '2025-09-16 20:46:43',
            ),
            90 => 
            array (
                'id' => 91,
                'shoe_id' => '2d13',
                'count' => -6,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-09-16 21:03:24',
                'updated_at' => '2025-09-16 21:03:24',
            ),
            91 => 
            array (
                'id' => 92,
                'shoe_id' => '2d1d',
                'count' => 6,
                'description' => NULL,
                'type' => 'in',
                'created_at' => '2025-09-16 21:04:02',
                'updated_at' => '2025-09-16 21:04:02',
            ),
            92 => 
            array (
                'id' => 93,
                'shoe_id' => '2d1d',
                'count' => 12,
                'description' => NULL,
                'type' => 'in',
                'created_at' => '2025-09-16 21:20:15',
                'updated_at' => '2025-09-16 21:20:15',
            ),
            93 => 
            array (
                'id' => 94,
                'shoe_id' => '392',
                'count' => -1,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2025-10-21 17:42:59',
                'updated_at' => '2025-10-21 17:42:59',
            ),
            94 => 
            array (
                'id' => 95,
                'shoe_id' => '5405',
                'count' => -12,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2026-02-15 19:11:10',
                'updated_at' => '2026-02-15 19:11:10',
            ),
            95 => 
            array (
                'id' => 96,
                'shoe_id' => '5403',
                'count' => -12,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2026-02-15 19:11:10',
                'updated_at' => '2026-02-15 19:11:10',
            ),
            96 => 
            array (
                'id' => 97,
                'shoe_id' => '5404',
                'count' => -12,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2026-02-15 19:11:10',
                'updated_at' => '2026-02-15 19:11:10',
            ),
            97 => 
            array (
                'id' => 98,
                'shoe_id' => '3f5c',
                'count' => -6,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2026-02-25 20:27:16',
                'updated_at' => '2026-02-25 20:27:16',
            ),
            98 => 
            array (
                'id' => 99,
                'shoe_id' => '416a',
                'count' => -6,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2026-02-25 20:27:16',
                'updated_at' => '2026-02-25 20:27:16',
            ),
            99 => 
            array (
                'id' => 100,
                'shoe_id' => '40ef',
                'count' => 6,
                'description' => NULL,
                'type' => 'in',
                'created_at' => '2026-02-25 20:28:22',
                'updated_at' => '2026-02-25 20:28:22',
            ),
            100 => 
            array (
                'id' => 101,
                'shoe_id' => '40f0',
                'count' => 6,
                'description' => NULL,
                'type' => 'in',
                'created_at' => '2026-02-25 20:28:22',
                'updated_at' => '2026-02-25 20:28:22',
            ),
            101 => 
            array (
                'id' => 102,
                'shoe_id' => '4dfa',
                'count' => -6,
                'description' => NULL,
                'type' => 'out',
                'created_at' => '2026-02-25 20:34:16',
                'updated_at' => '2026-02-25 20:34:16',
            ),
            102 => 
            array (
                'id' => 103,
                'shoe_id' => '39ba',
                'count' => 6,
                'description' => NULL,
                'type' => 'in',
                'created_at' => '2026-02-25 20:35:39',
                'updated_at' => '2026-02-25 20:35:39',
            ),
            103 => 
            array (
                'id' => 104,
                'shoe_id' => '313e',
                'count' => 156,
                'description' => NULL,
                'type' => 'in',
                'created_at' => '2026-03-07 21:57:29',
                'updated_at' => '2026-03-07 21:57:29',
            ),
        ));
        
        
    }
}