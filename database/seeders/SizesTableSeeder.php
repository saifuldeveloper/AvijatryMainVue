<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SizesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \Schema::disableForeignKeyConstraints();

        \DB::table('sizes')->delete();
        
        \DB::table('sizes')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => '40',
                'category_id' => 12,
                'deleted_at' => NULL,
                'created_at' => '2025-03-28 11:30:06',
                'updated_at' => '2025-04-05 23:33:34',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => '41',
                'category_id' => 12,
                'deleted_at' => NULL,
                'created_at' => '2025-03-28 11:30:13',
                'updated_at' => '2025-04-05 23:33:45',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => '39',
                'category_id' => 12,
                'deleted_at' => NULL,
                'created_at' => '2025-03-28 11:30:22',
                'updated_at' => '2025-04-05 23:33:39',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => '42',
                'category_id' => 12,
                'deleted_at' => NULL,
                'created_at' => '2025-04-05 23:34:03',
                'updated_at' => '2025-04-05 23:34:03',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => '43',
                'category_id' => 12,
                'deleted_at' => NULL,
                'created_at' => '2025-04-05 23:34:34',
                'updated_at' => '2025-04-05 23:34:34',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => '44',
                'category_id' => 12,
                'deleted_at' => NULL,
                'created_at' => '2025-04-05 23:34:41',
                'updated_at' => '2025-04-05 23:34:41',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => '39',
                'category_id' => 16,
                'deleted_at' => NULL,
                'created_at' => '2025-04-05 23:35:02',
                'updated_at' => '2025-04-05 23:35:02',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => '40',
                'category_id' => 16,
                'deleted_at' => NULL,
                'created_at' => '2025-04-05 23:35:11',
                'updated_at' => '2025-04-05 23:35:11',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => '41',
                'category_id' => 16,
                'deleted_at' => NULL,
                'created_at' => '2025-04-05 23:35:28',
                'updated_at' => '2025-04-05 23:35:28',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => '42',
                'category_id' => 16,
                'deleted_at' => NULL,
                'created_at' => '2025-04-05 23:36:06',
                'updated_at' => '2025-04-05 23:36:06',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => '43',
                'category_id' => 16,
                'deleted_at' => NULL,
                'created_at' => '2025-04-05 23:36:15',
                'updated_at' => '2025-04-05 23:36:15',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => '44',
                'category_id' => 16,
                'deleted_at' => NULL,
                'created_at' => '2025-04-05 23:36:22',
                'updated_at' => '2025-04-05 23:36:22',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => '39',
                'category_id' => 5,
                'deleted_at' => NULL,
                'created_at' => '2025-04-05 23:36:42',
                'updated_at' => '2025-04-05 23:36:42',
            ),
            13 => 
            array (
                'id' => 14,
                'name' => '40',
                'category_id' => 5,
                'deleted_at' => NULL,
                'created_at' => '2025-04-05 23:36:48',
                'updated_at' => '2025-04-05 23:36:48',
            ),
            14 => 
            array (
                'id' => 15,
                'name' => '41',
                'category_id' => 5,
                'deleted_at' => NULL,
                'created_at' => '2025-04-05 23:36:55',
                'updated_at' => '2025-04-05 23:36:55',
            ),
            15 => 
            array (
                'id' => 16,
                'name' => '42',
                'category_id' => 5,
                'deleted_at' => NULL,
                'created_at' => '2025-04-05 23:37:15',
                'updated_at' => '2025-04-05 23:37:15',
            ),
            16 => 
            array (
                'id' => 17,
                'name' => '43',
                'category_id' => 5,
                'deleted_at' => NULL,
                'created_at' => '2025-04-05 23:37:23',
                'updated_at' => '2025-04-05 23:37:23',
            ),
            17 => 
            array (
                'id' => 18,
                'name' => '39',
                'category_id' => 6,
                'deleted_at' => NULL,
                'created_at' => '2025-04-05 23:59:53',
                'updated_at' => '2025-04-05 23:59:53',
            ),
            18 => 
            array (
                'id' => 19,
                'name' => '40',
                'category_id' => 6,
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 00:00:02',
                'updated_at' => '2025-04-06 00:00:02',
            ),
            19 => 
            array (
                'id' => 20,
                'name' => '41',
                'category_id' => 6,
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 00:00:12',
                'updated_at' => '2025-04-06 00:00:12',
            ),
            20 => 
            array (
                'id' => 21,
                'name' => '42',
                'category_id' => 6,
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 00:00:23',
                'updated_at' => '2025-04-06 00:00:23',
            ),
            21 => 
            array (
                'id' => 22,
                'name' => '43',
                'category_id' => 6,
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 00:00:30',
                'updated_at' => '2025-04-06 00:00:30',
            ),
            22 => 
            array (
                'id' => 23,
                'name' => '44',
                'category_id' => 6,
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 00:00:37',
                'updated_at' => '2025-04-06 00:00:37',
            ),
            23 => 
            array (
                'id' => 24,
                'name' => '42',
                'category_id' => 8,
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 00:00:45',
                'updated_at' => '2025-04-06 00:01:44',
            ),
            24 => 
            array (
                'id' => 25,
                'name' => '43',
                'category_id' => 8,
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 00:00:59',
                'updated_at' => '2025-04-06 00:02:05',
            ),
            25 => 
            array (
                'id' => 26,
                'name' => '41',
                'category_id' => 8,
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 00:01:06',
                'updated_at' => '2025-04-06 00:01:06',
            ),
            26 => 
            array (
                'id' => 27,
                'name' => '44',
                'category_id' => 8,
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 00:02:19',
                'updated_at' => '2025-04-06 00:02:19',
            ),
            27 => 
            array (
                'id' => 28,
                'name' => '39',
                'category_id' => 8,
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 00:02:28',
                'updated_at' => '2025-05-23 21:54:51',
            ),
            28 => 
            array (
                'id' => 29,
                'name' => '40',
                'category_id' => 8,
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 00:02:34',
                'updated_at' => '2025-05-23 21:54:57',
            ),
            29 => 
            array (
                'id' => 30,
                'name' => '39',
                'category_id' => 17,
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 00:04:48',
                'updated_at' => '2025-04-06 00:04:48',
            ),
            30 => 
            array (
                'id' => 31,
                'name' => '40',
                'category_id' => 17,
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 00:04:56',
                'updated_at' => '2025-04-06 00:04:56',
            ),
            31 => 
            array (
                'id' => 32,
                'name' => '41',
                'category_id' => 17,
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 00:05:05',
                'updated_at' => '2025-04-06 00:05:05',
            ),
            32 => 
            array (
                'id' => 33,
                'name' => '42',
                'category_id' => 17,
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 00:05:13',
                'updated_at' => '2025-04-06 00:05:13',
            ),
            33 => 
            array (
                'id' => 34,
                'name' => '43',
                'category_id' => 17,
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 00:05:30',
                'updated_at' => '2025-04-06 00:05:30',
            ),
            34 => 
            array (
                'id' => 35,
                'name' => '39',
                'category_id' => 19,
                'deleted_at' => NULL,
                'created_at' => '2025-04-07 01:40:27',
                'updated_at' => '2025-04-07 01:40:27',
            ),
            35 => 
            array (
                'id' => 36,
                'name' => '40',
                'category_id' => 19,
                'deleted_at' => NULL,
                'created_at' => '2025-04-07 01:40:36',
                'updated_at' => '2025-04-07 01:40:36',
            ),
            36 => 
            array (
                'id' => 37,
                'name' => '41',
                'category_id' => 19,
                'deleted_at' => NULL,
                'created_at' => '2025-04-07 01:40:44',
                'updated_at' => '2025-04-07 01:40:44',
            ),
            37 => 
            array (
                'id' => 38,
                'name' => '42',
                'category_id' => 19,
                'deleted_at' => NULL,
                'created_at' => '2025-04-07 01:40:52',
                'updated_at' => '2025-04-07 01:40:52',
            ),
            38 => 
            array (
                'id' => 39,
                'name' => '43',
                'category_id' => 19,
                'deleted_at' => NULL,
                'created_at' => '2025-04-07 01:41:02',
                'updated_at' => '2025-04-07 01:41:02',
            ),
            39 => 
            array (
                'id' => 40,
                'name' => '6',
                'category_id' => 23,
                'deleted_at' => NULL,
                'created_at' => '2025-05-13 13:05:36',
                'updated_at' => '2025-05-13 13:05:36',
            ),
            40 => 
            array (
                'id' => 41,
                'name' => '7',
                'category_id' => 23,
                'deleted_at' => NULL,
                'created_at' => '2025-05-13 13:05:44',
                'updated_at' => '2025-05-13 13:05:44',
            ),
            41 => 
            array (
                'id' => 42,
                'name' => '8',
                'category_id' => 23,
                'deleted_at' => NULL,
                'created_at' => '2025-05-13 13:05:51',
                'updated_at' => '2025-05-13 13:05:51',
            ),
            42 => 
            array (
                'id' => 43,
                'name' => '9',
                'category_id' => 23,
                'deleted_at' => NULL,
                'created_at' => '2025-05-13 13:05:59',
                'updated_at' => '2025-05-13 13:05:59',
            ),
            43 => 
            array (
                'id' => 44,
                'name' => '10',
                'category_id' => 23,
                'deleted_at' => NULL,
                'created_at' => '2025-05-13 13:06:14',
                'updated_at' => '2025-05-13 13:06:14',
            ),
            44 => 
            array (
                'id' => 45,
                'name' => '38',
                'category_id' => 9,
                'deleted_at' => NULL,
                'created_at' => '2025-05-13 13:06:26',
                'updated_at' => '2025-05-27 20:29:24',
            ),
            45 => 
            array (
                'id' => 46,
                'name' => '12',
                'category_id' => 33,
                'deleted_at' => NULL,
                'created_at' => '2025-05-13 13:07:55',
                'updated_at' => '2025-05-13 13:07:55',
            ),
            46 => 
            array (
                'id' => 47,
                'name' => '13',
                'category_id' => 33,
                'deleted_at' => NULL,
                'created_at' => '2025-05-13 13:08:02',
                'updated_at' => '2025-05-13 13:08:02',
            ),
            47 => 
            array (
                'id' => 48,
                'name' => '14',
                'category_id' => 33,
                'deleted_at' => NULL,
                'created_at' => '2025-05-13 13:08:09',
                'updated_at' => '2025-05-13 13:08:09',
            ),
            48 => 
            array (
                'id' => 49,
                'name' => '15',
                'category_id' => 33,
                'deleted_at' => NULL,
                'created_at' => '2025-05-13 13:08:15',
                'updated_at' => '2025-05-13 13:08:15',
            ),
            49 => 
            array (
                'id' => 50,
                'name' => '6',
                'category_id' => 18,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:27:13',
                'updated_at' => '2025-05-23 21:27:13',
            ),
            50 => 
            array (
                'id' => 51,
                'name' => '7',
                'category_id' => 18,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:27:21',
                'updated_at' => '2025-05-23 21:27:21',
            ),
            51 => 
            array (
                'id' => 52,
                'name' => '8',
                'category_id' => 18,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:27:34',
                'updated_at' => '2025-05-23 21:27:34',
            ),
            52 => 
            array (
                'id' => 53,
                'name' => '9',
                'category_id' => 18,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:27:44',
                'updated_at' => '2025-05-23 21:27:44',
            ),
            53 => 
            array (
                'id' => 54,
                'name' => '16',
                'category_id' => 26,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:28:12',
                'updated_at' => '2025-05-23 21:28:12',
            ),
            54 => 
            array (
                'id' => 55,
                'name' => '17',
                'category_id' => 26,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:28:20',
                'updated_at' => '2025-05-23 21:28:20',
            ),
            55 => 
            array (
                'id' => 56,
                'name' => '18',
                'category_id' => 26,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:28:27',
                'updated_at' => '2025-05-23 21:28:27',
            ),
            56 => 
            array (
                'id' => 57,
                'name' => '43',
                'category_id' => 26,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:28:34',
                'updated_at' => '2025-05-23 21:28:34',
            ),
            57 => 
            array (
                'id' => 58,
                'name' => '44',
                'category_id' => 26,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:28:41',
                'updated_at' => '2025-05-23 21:28:41',
            ),
            58 => 
            array (
                'id' => 59,
                'name' => '45',
                'category_id' => 26,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:28:51',
                'updated_at' => '2025-05-23 21:28:51',
            ),
            59 => 
            array (
                'id' => 60,
                'name' => '39',
                'category_id' => 27,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:29:32',
                'updated_at' => '2025-05-23 21:29:32',
            ),
            60 => 
            array (
                'id' => 61,
                'name' => '40',
                'category_id' => 27,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:29:42',
                'updated_at' => '2025-05-23 21:29:42',
            ),
            61 => 
            array (
                'id' => 62,
                'name' => '41',
                'category_id' => 27,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:29:49',
                'updated_at' => '2025-05-23 21:29:49',
            ),
            62 => 
            array (
                'id' => 63,
                'name' => '42',
                'category_id' => 26,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:29:56',
                'updated_at' => '2025-05-23 21:29:56',
            ),
            63 => 
            array (
                'id' => 64,
                'name' => '43',
                'category_id' => 27,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:30:07',
                'updated_at' => '2025-05-23 21:30:07',
            ),
            64 => 
            array (
                'id' => 65,
                'name' => '44',
                'category_id' => 27,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:30:16',
                'updated_at' => '2025-05-23 21:30:16',
            ),
            65 => 
            array (
                'id' => 66,
                'name' => '39',
                'category_id' => 29,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:30:48',
                'updated_at' => '2025-05-23 21:30:48',
            ),
            66 => 
            array (
                'id' => 67,
                'name' => '40',
                'category_id' => 29,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:30:56',
                'updated_at' => '2025-05-23 21:30:56',
            ),
            67 => 
            array (
                'id' => 68,
                'name' => '41',
                'category_id' => 29,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:31:04',
                'updated_at' => '2025-05-23 21:31:04',
            ),
            68 => 
            array (
                'id' => 69,
                'name' => '42',
                'category_id' => 29,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:31:27',
                'updated_at' => '2025-05-23 21:31:27',
            ),
            69 => 
            array (
                'id' => 70,
                'name' => '43',
                'category_id' => 29,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:32:41',
                'updated_at' => '2025-05-23 21:32:41',
            ),
            70 => 
            array (
                'id' => 71,
                'name' => '39',
                'category_id' => 30,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:33:04',
                'updated_at' => '2025-05-23 21:33:04',
            ),
            71 => 
            array (
                'id' => 72,
                'name' => '40',
                'category_id' => 30,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:33:17',
                'updated_at' => '2025-05-23 21:33:17',
            ),
            72 => 
            array (
                'id' => 73,
                'name' => '41',
                'category_id' => 30,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:33:26',
                'updated_at' => '2025-05-23 21:33:26',
            ),
            73 => 
            array (
                'id' => 74,
                'name' => '42',
                'category_id' => 30,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:33:33',
                'updated_at' => '2025-05-23 21:33:33',
            ),
            74 => 
            array (
                'id' => 75,
                'name' => '44',
                'category_id' => 30,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:33:52',
                'updated_at' => '2025-05-23 21:33:52',
            ),
            75 => 
            array (
                'id' => 76,
                'name' => '43',
                'category_id' => 30,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:34:17',
                'updated_at' => '2025-05-23 21:34:17',
            ),
            76 => 
            array (
                'id' => 77,
                'name' => '39',
                'category_id' => 31,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:34:36',
                'updated_at' => '2025-05-23 21:34:36',
            ),
            77 => 
            array (
                'id' => 78,
                'name' => '40',
                'category_id' => 31,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:34:43',
                'updated_at' => '2025-05-23 21:34:43',
            ),
            78 => 
            array (
                'id' => 79,
                'name' => '41',
                'category_id' => 31,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:34:52',
                'updated_at' => '2025-05-23 21:34:52',
            ),
            79 => 
            array (
                'id' => 80,
                'name' => '42',
                'category_id' => 31,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:34:57',
                'updated_at' => '2025-05-23 21:34:57',
            ),
            80 => 
            array (
                'id' => 81,
                'name' => '43',
                'category_id' => 31,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:35:04',
                'updated_at' => '2025-05-23 21:35:04',
            ),
            81 => 
            array (
                'id' => 82,
                'name' => '44',
                'category_id' => 31,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:35:11',
                'updated_at' => '2025-05-23 21:35:11',
            ),
            82 => 
            array (
                'id' => 83,
                'name' => '39',
                'category_id' => 34,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:35:31',
                'updated_at' => '2025-05-23 21:35:31',
            ),
            83 => 
            array (
                'id' => 84,
                'name' => '40',
                'category_id' => 34,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:35:39',
                'updated_at' => '2025-05-23 21:35:39',
            ),
            84 => 
            array (
                'id' => 85,
                'name' => '41',
                'category_id' => 34,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:35:51',
                'updated_at' => '2025-05-23 21:35:51',
            ),
            85 => 
            array (
                'id' => 86,
                'name' => '42',
                'category_id' => 34,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:36:00',
                'updated_at' => '2025-05-23 21:36:00',
            ),
            86 => 
            array (
                'id' => 87,
                'name' => '43',
                'category_id' => 34,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:36:16',
                'updated_at' => '2025-05-23 21:36:16',
            ),
            87 => 
            array (
                'id' => 88,
                'name' => '44',
                'category_id' => 34,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:36:23',
                'updated_at' => '2025-05-23 21:36:23',
            ),
            88 => 
            array (
                'id' => 89,
                'name' => '26',
                'category_id' => 15,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:38:22',
                'updated_at' => '2025-05-23 21:38:22',
            ),
            89 => 
            array (
                'id' => 90,
                'name' => '27',
                'category_id' => 15,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:38:29',
                'updated_at' => '2025-05-23 21:38:29',
            ),
            90 => 
            array (
                'id' => 91,
                'name' => '28',
                'category_id' => 15,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:38:37',
                'updated_at' => '2025-05-23 21:38:37',
            ),
            91 => 
            array (
                'id' => 92,
                'name' => '23',
                'category_id' => 15,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:38:56',
                'updated_at' => '2025-05-23 21:38:56',
            ),
            92 => 
            array (
                'id' => 93,
                'name' => '24',
                'category_id' => 15,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:39:01',
                'updated_at' => '2025-05-23 21:39:01',
            ),
            93 => 
            array (
                'id' => 94,
                'name' => '25',
                'category_id' => 15,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:39:06',
                'updated_at' => '2025-05-23 21:39:06',
            ),
            94 => 
            array (
                'id' => 95,
                'name' => '17',
                'category_id' => 15,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:39:11',
                'updated_at' => '2025-05-23 21:39:11',
            ),
            95 => 
            array (
                'id' => 96,
                'name' => '18',
                'category_id' => 15,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:39:17',
                'updated_at' => '2025-05-23 21:39:17',
            ),
            96 => 
            array (
                'id' => 97,
                'name' => '19',
                'category_id' => 15,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:39:23',
                'updated_at' => '2025-05-23 21:39:23',
            ),
            97 => 
            array (
                'id' => 98,
                'name' => '36',
                'category_id' => 10,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:40:49',
                'updated_at' => '2025-05-23 21:40:49',
            ),
            98 => 
            array (
                'id' => 99,
                'name' => '37',
                'category_id' => 10,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:40:55',
                'updated_at' => '2025-05-23 21:40:55',
            ),
            99 => 
            array (
                'id' => 100,
                'name' => '38',
                'category_id' => 10,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:41:01',
                'updated_at' => '2025-05-23 21:41:01',
            ),
            100 => 
            array (
                'id' => 101,
                'name' => '39',
                'category_id' => 10,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:41:08',
                'updated_at' => '2025-05-23 21:41:08',
            ),
            101 => 
            array (
                'id' => 102,
                'name' => '40',
                'category_id' => 10,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:41:14',
                'updated_at' => '2025-05-23 21:41:14',
            ),
            102 => 
            array (
                'id' => 103,
                'name' => '41',
                'category_id' => 9,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:41:22',
                'updated_at' => '2025-05-23 21:41:22',
            ),
            103 => 
            array (
                'id' => 104,
                'name' => '41',
                'category_id' => 10,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:42:04',
                'updated_at' => '2025-05-23 21:42:04',
            ),
            104 => 
            array (
                'id' => 105,
                'name' => '39',
                'category_id' => 9,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:42:32',
                'updated_at' => '2025-05-23 21:56:10',
            ),
            105 => 
            array (
                'id' => 106,
                'name' => '31',
                'category_id' => 11,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:42:48',
                'updated_at' => '2025-05-23 21:42:48',
            ),
            106 => 
            array (
                'id' => 107,
                'name' => '32',
                'category_id' => 11,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:42:57',
                'updated_at' => '2025-05-23 21:42:57',
            ),
            107 => 
            array (
                'id' => 108,
                'name' => '33',
                'category_id' => 11,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:43:12',
                'updated_at' => '2025-05-23 21:43:12',
            ),
            108 => 
            array (
                'id' => 109,
                'name' => '34',
                'category_id' => 11,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:43:19',
                'updated_at' => '2025-05-23 21:43:19',
            ),
            109 => 
            array (
                'id' => 110,
                'name' => '35',
                'category_id' => 11,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:43:25',
                'updated_at' => '2025-05-23 21:43:25',
            ),
            110 => 
            array (
                'id' => 111,
                'name' => '36',
                'category_id' => 20,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:43:44',
                'updated_at' => '2025-05-23 21:43:44',
            ),
            111 => 
            array (
                'id' => 112,
                'name' => '37',
                'category_id' => 20,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:43:50',
                'updated_at' => '2025-05-23 21:43:50',
            ),
            112 => 
            array (
                'id' => 113,
                'name' => '38',
                'category_id' => 20,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:44:00',
                'updated_at' => '2025-05-23 21:44:00',
            ),
            113 => 
            array (
                'id' => 114,
                'name' => '39',
                'category_id' => 20,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:44:06',
                'updated_at' => '2025-05-23 21:44:06',
            ),
            114 => 
            array (
                'id' => 115,
                'name' => '40',
                'category_id' => 20,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:44:16',
                'updated_at' => '2025-05-23 21:44:16',
            ),
            115 => 
            array (
                'id' => 116,
                'name' => '41',
                'category_id' => 20,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:44:22',
                'updated_at' => '2025-05-23 21:44:22',
            ),
            116 => 
            array (
                'id' => 117,
                'name' => '36',
                'category_id' => 21,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:44:34',
                'updated_at' => '2025-05-23 21:44:34',
            ),
            117 => 
            array (
                'id' => 118,
                'name' => '37',
                'category_id' => 21,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:44:43',
                'updated_at' => '2025-05-23 21:44:43',
            ),
            118 => 
            array (
                'id' => 119,
                'name' => '38',
                'category_id' => 21,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:44:55',
                'updated_at' => '2025-05-23 21:44:55',
            ),
            119 => 
            array (
                'id' => 120,
                'name' => '39',
                'category_id' => 21,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:45:04',
                'updated_at' => '2025-05-23 21:45:04',
            ),
            120 => 
            array (
                'id' => 121,
                'name' => '40',
                'category_id' => 21,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:45:11',
                'updated_at' => '2025-05-23 21:45:11',
            ),
            121 => 
            array (
                'id' => 122,
                'name' => '41',
                'category_id' => 21,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:45:18',
                'updated_at' => '2025-05-23 21:45:18',
            ),
            122 => 
            array (
                'id' => 123,
                'name' => '36',
                'category_id' => 24,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:45:47',
                'updated_at' => '2025-05-23 21:45:47',
            ),
            123 => 
            array (
                'id' => 124,
                'name' => '37',
                'category_id' => 24,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:46:02',
                'updated_at' => '2025-05-23 21:46:02',
            ),
            124 => 
            array (
                'id' => 125,
                'name' => '38',
                'category_id' => 24,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:46:13',
                'updated_at' => '2025-05-23 21:46:13',
            ),
            125 => 
            array (
                'id' => 126,
                'name' => '39',
                'category_id' => 24,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:46:22',
                'updated_at' => '2025-05-23 21:46:22',
            ),
            126 => 
            array (
                'id' => 127,
                'name' => '40',
                'category_id' => 24,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:46:28',
                'updated_at' => '2025-05-23 21:46:28',
            ),
            127 => 
            array (
                'id' => 128,
                'name' => '41',
                'category_id' => 24,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:46:35',
                'updated_at' => '2025-05-23 21:46:35',
            ),
            128 => 
            array (
                'id' => 129,
                'name' => '36',
                'category_id' => 25,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:46:58',
                'updated_at' => '2025-05-23 21:46:58',
            ),
            129 => 
            array (
                'id' => 130,
                'name' => '37',
                'category_id' => 25,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:47:08',
                'updated_at' => '2025-05-23 21:47:08',
            ),
            130 => 
            array (
                'id' => 131,
                'name' => '38',
                'category_id' => 25,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:47:16',
                'updated_at' => '2025-05-23 21:47:16',
            ),
            131 => 
            array (
                'id' => 132,
                'name' => '39',
                'category_id' => 25,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:47:24',
                'updated_at' => '2025-05-23 21:47:24',
            ),
            132 => 
            array (
                'id' => 133,
                'name' => '40',
                'category_id' => 25,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:47:32',
                'updated_at' => '2025-05-23 21:47:32',
            ),
            133 => 
            array (
                'id' => 134,
                'name' => '41',
                'category_id' => 25,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:47:39',
                'updated_at' => '2025-05-23 21:47:39',
            ),
            134 => 
            array (
                'id' => 135,
                'name' => '36',
                'category_id' => 28,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:47:59',
                'updated_at' => '2025-05-23 21:47:59',
            ),
            135 => 
            array (
                'id' => 136,
                'name' => '37',
                'category_id' => 28,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:48:09',
                'updated_at' => '2025-05-23 21:48:09',
            ),
            136 => 
            array (
                'id' => 137,
                'name' => '38',
                'category_id' => 28,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:48:33',
                'updated_at' => '2025-05-23 21:48:46',
            ),
            137 => 
            array (
                'id' => 138,
                'name' => '39',
                'category_id' => 28,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:48:54',
                'updated_at' => '2025-05-23 21:48:54',
            ),
            138 => 
            array (
                'id' => 139,
                'name' => '40',
                'category_id' => 28,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:49:02',
                'updated_at' => '2025-05-23 21:49:02',
            ),
            139 => 
            array (
                'id' => 140,
                'name' => '41',
                'category_id' => 28,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:49:09',
                'updated_at' => '2025-05-23 21:49:09',
            ),
            140 => 
            array (
                'id' => 141,
                'name' => '36',
                'category_id' => 32,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:49:35',
                'updated_at' => '2025-05-23 21:49:35',
            ),
            141 => 
            array (
                'id' => 142,
                'name' => '37',
                'category_id' => 32,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:49:43',
                'updated_at' => '2025-05-23 21:49:43',
            ),
            142 => 
            array (
                'id' => 143,
                'name' => '38',
                'category_id' => 32,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:49:50',
                'updated_at' => '2025-05-23 21:49:50',
            ),
            143 => 
            array (
                'id' => 144,
                'name' => '39',
                'category_id' => 32,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:50:00',
                'updated_at' => '2025-05-23 21:50:00',
            ),
            144 => 
            array (
                'id' => 145,
                'name' => '40',
                'category_id' => 32,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:50:09',
                'updated_at' => '2025-05-23 21:50:09',
            ),
            145 => 
            array (
                'id' => 146,
                'name' => '41',
                'category_id' => 32,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:50:18',
                'updated_at' => '2025-05-23 21:50:18',
            ),
            146 => 
            array (
                'id' => 147,
                'name' => '36',
                'category_id' => 35,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:52:08',
                'updated_at' => '2025-05-23 21:52:08',
            ),
            147 => 
            array (
                'id' => 148,
                'name' => '37',
                'category_id' => 35,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:52:15',
                'updated_at' => '2025-05-23 21:52:15',
            ),
            148 => 
            array (
                'id' => 149,
                'name' => '38',
                'category_id' => 35,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:52:22',
                'updated_at' => '2025-05-23 21:52:22',
            ),
            149 => 
            array (
                'id' => 150,
                'name' => '39',
                'category_id' => 35,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:52:28',
                'updated_at' => '2025-05-23 21:52:28',
            ),
            150 => 
            array (
                'id' => 151,
                'name' => '40',
                'category_id' => 35,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:52:35',
                'updated_at' => '2025-05-23 21:52:35',
            ),
            151 => 
            array (
                'id' => 152,
                'name' => '41',
                'category_id' => 35,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:52:41',
                'updated_at' => '2025-05-23 21:52:41',
            ),
            152 => 
            array (
                'id' => 153,
                'name' => '36',
                'category_id' => 36,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:53:04',
                'updated_at' => '2025-05-23 21:53:04',
            ),
            153 => 
            array (
                'id' => 154,
                'name' => '37',
                'category_id' => 36,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:53:10',
                'updated_at' => '2025-05-23 21:53:10',
            ),
            154 => 
            array (
                'id' => 155,
                'name' => '38',
                'category_id' => 36,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:53:17',
                'updated_at' => '2025-05-23 21:53:17',
            ),
            155 => 
            array (
                'id' => 156,
                'name' => '39',
                'category_id' => 36,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:53:23',
                'updated_at' => '2025-05-23 21:53:23',
            ),
            156 => 
            array (
                'id' => 157,
                'name' => '40',
                'category_id' => 36,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:53:31',
                'updated_at' => '2025-05-23 21:53:31',
            ),
            157 => 
            array (
                'id' => 158,
                'name' => '41',
                'category_id' => 36,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:53:37',
                'updated_at' => '2025-05-23 21:53:37',
            ),
            158 => 
            array (
                'id' => 159,
                'name' => '44',
                'category_id' => 5,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:54:19',
                'updated_at' => '2025-05-23 21:54:19',
            ),
            159 => 
            array (
                'id' => 160,
                'name' => '40',
                'category_id' => 9,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:55:26',
                'updated_at' => '2025-05-23 21:55:41',
            ),
            160 => 
            array (
                'id' => 161,
                'name' => '37',
                'category_id' => 9,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:56:25',
                'updated_at' => '2025-05-23 21:56:25',
            ),
            161 => 
            array (
                'id' => 162,
                'name' => '36',
                'category_id' => 9,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:56:30',
                'updated_at' => '2025-05-27 20:29:34',
            ),
            162 => 
            array (
                'id' => 163,
                'name' => '44',
                'category_id' => 17,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:57:50',
                'updated_at' => '2025-05-23 21:57:50',
            ),
            163 => 
            array (
                'id' => 164,
                'name' => '44',
                'category_id' => 19,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:58:37',
                'updated_at' => '2025-05-23 21:58:37',
            ),
            164 => 
            array (
                'id' => 165,
                'name' => '11',
                'category_id' => 23,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:59:40',
                'updated_at' => '2025-05-23 21:59:40',
            ),
            165 => 
            array (
                'id' => 166,
                'name' => '36',
                'category_id' => 23,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:59:49',
                'updated_at' => '2025-05-23 21:59:49',
            ),
            166 => 
            array (
                'id' => 167,
                'name' => '37',
                'category_id' => 23,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 21:59:57',
                'updated_at' => '2025-05-23 21:59:57',
            ),
            167 => 
            array (
                'id' => 168,
                'name' => '38',
                'category_id' => 23,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 22:00:06',
                'updated_at' => '2025-05-23 22:00:06',
            ),
            168 => 
            array (
                'id' => 169,
                'name' => '39',
                'category_id' => 23,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 22:00:12',
                'updated_at' => '2025-05-23 22:00:12',
            ),
            169 => 
            array (
                'id' => 170,
                'name' => '40',
                'category_id' => 23,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 22:00:19',
                'updated_at' => '2025-05-23 22:00:19',
            ),
            170 => 
            array (
                'id' => 171,
                'name' => '41',
                'category_id' => 23,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 22:00:25',
                'updated_at' => '2025-05-23 22:00:25',
            ),
            171 => 
            array (
                'id' => 172,
                'name' => '42',
                'category_id' => 27,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 22:01:23',
                'updated_at' => '2025-05-23 22:01:23',
            ),
            172 => 
            array (
                'id' => 173,
                'name' => '44',
                'category_id' => 29,
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 22:02:07',
                'updated_at' => '2025-05-23 22:02:07',
            ),
            173 => 
            array (
                'id' => 174,
                'name' => '36',
                'category_id' => 18,
                'deleted_at' => NULL,
                'created_at' => '2025-05-26 01:59:42',
                'updated_at' => '2025-05-26 01:59:42',
            ),
            174 => 
            array (
                'id' => 175,
                'name' => '37',
                'category_id' => 18,
                'deleted_at' => NULL,
                'created_at' => '2025-05-26 01:59:57',
                'updated_at' => '2025-05-26 01:59:57',
            ),
            175 => 
            array (
                'id' => 176,
                'name' => '38',
                'category_id' => 18,
                'deleted_at' => NULL,
                'created_at' => '2025-05-26 02:00:06',
                'updated_at' => '2025-05-26 02:00:06',
            ),
            176 => 
            array (
                'id' => 177,
                'name' => '39',
                'category_id' => 18,
                'deleted_at' => NULL,
                'created_at' => '2025-05-26 02:00:19',
                'updated_at' => '2025-05-26 02:00:19',
            ),
            177 => 
            array (
                'id' => 178,
                'name' => '45',
                'category_id' => 8,
                'deleted_at' => NULL,
                'created_at' => '2025-05-26 02:00:35',
                'updated_at' => '2025-05-26 02:00:35',
            ),
            178 => 
            array (
                'id' => 179,
                'name' => '35',
                'category_id' => 18,
                'deleted_at' => NULL,
                'created_at' => '2025-05-26 16:06:53',
                'updated_at' => '2025-05-26 16:06:53',
            ),
            179 => 
            array (
                'id' => 180,
                'name' => '45',
                'category_id' => 17,
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 15:48:39',
                'updated_at' => '2025-05-27 15:48:39',
            ),
            180 => 
            array (
                'id' => 181,
                'name' => '35',
                'category_id' => 25,
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 15:50:07',
                'updated_at' => '2025-05-27 15:50:07',
            ),
            181 => 
            array (
                'id' => 182,
                'name' => '35',
                'category_id' => 9,
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 15:50:16',
                'updated_at' => '2025-05-27 15:50:16',
            ),
            182 => 
            array (
                'id' => 183,
                'name' => '1',
                'category_id' => 7,
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 19:38:35',
                'updated_at' => '2025-05-27 19:38:35',
            ),
            183 => 
            array (
                'id' => 184,
                'name' => '2',
                'category_id' => 7,
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 19:38:42',
                'updated_at' => '2025-05-27 19:38:42',
            ),
            184 => 
            array (
                'id' => 185,
                'name' => '3',
                'category_id' => 7,
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 19:38:49',
                'updated_at' => '2025-05-27 19:38:49',
            ),
            185 => 
            array (
                'id' => 186,
                'name' => '4',
                'category_id' => 7,
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 19:39:02',
                'updated_at' => '2025-05-27 19:39:02',
            ),
            186 => 
            array (
                'id' => 187,
                'name' => '30',
                'category_id' => 7,
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 19:39:21',
                'updated_at' => '2025-05-27 19:39:21',
            ),
            187 => 
            array (
                'id' => 188,
                'name' => '31',
                'category_id' => 7,
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 19:39:27',
                'updated_at' => '2025-05-27 19:39:27',
            ),
            188 => 
            array (
                'id' => 189,
                'name' => '32',
                'category_id' => 7,
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 19:39:32',
                'updated_at' => '2025-05-27 19:39:32',
            ),
            189 => 
            array (
                'id' => 190,
                'name' => '33',
                'category_id' => 7,
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 19:39:37',
                'updated_at' => '2025-05-27 19:39:37',
            ),
            190 => 
            array (
                'id' => 191,
                'name' => '5',
                'category_id' => 15,
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 20:24:06',
                'updated_at' => '2025-05-27 20:24:06',
            ),
            191 => 
            array (
                'id' => 192,
                'name' => '6',
                'category_id' => 15,
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 20:24:13',
                'updated_at' => '2025-05-27 20:24:13',
            ),
            192 => 
            array (
                'id' => 193,
                'name' => '7',
                'category_id' => 15,
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 20:24:26',
                'updated_at' => '2025-05-27 20:24:26',
            ),
            193 => 
            array (
                'id' => 194,
                'name' => '8',
                'category_id' => 15,
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 20:24:38',
                'updated_at' => '2025-05-27 20:24:38',
            ),
            194 => 
            array (
                'id' => 195,
                'name' => '9',
                'category_id' => 15,
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 20:24:50',
                'updated_at' => '2025-05-27 20:24:50',
            ),
            195 => 
            array (
                'id' => 196,
                'name' => '10',
                'category_id' => 15,
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 20:24:56',
                'updated_at' => '2025-05-27 20:24:56',
            ),
            196 => 
            array (
                'id' => 197,
                'name' => '41',
                'category_id' => 33,
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 20:31:55',
                'updated_at' => '2025-05-27 20:31:55',
            ),
            197 => 
            array (
                'id' => 198,
                'name' => '42',
                'category_id' => 33,
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 20:32:01',
                'updated_at' => '2025-05-27 20:32:01',
            ),
            198 => 
            array (
                'id' => 199,
                'name' => '43',
                'category_id' => 33,
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 20:32:09',
                'updated_at' => '2025-05-27 20:32:09',
            ),
            199 => 
            array (
                'id' => 200,
                'name' => '44',
                'category_id' => 33,
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 20:32:17',
                'updated_at' => '2025-05-27 20:32:17',
            ),
            200 => 
            array (
                'id' => 201,
                'name' => '35',
                'category_id' => 20,
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 20:35:01',
                'updated_at' => '2025-05-27 20:35:01',
            ),
            201 => 
            array (
                'id' => 202,
                'name' => '35',
                'category_id' => 21,
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 20:35:08',
                'updated_at' => '2025-05-27 20:35:08',
            ),
            202 => 
            array (
                'id' => 203,
                'name' => '35',
                'category_id' => 23,
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 20:35:16',
                'updated_at' => '2025-05-27 20:35:16',
            ),
            203 => 
            array (
                'id' => 204,
                'name' => '35',
                'category_id' => 24,
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 20:35:22',
                'updated_at' => '2025-05-27 20:35:22',
            ),
            204 => 
            array (
                'id' => 205,
                'name' => '35',
                'category_id' => 28,
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 20:35:39',
                'updated_at' => '2025-05-27 20:35:39',
            ),
            205 => 
            array (
                'id' => 206,
                'name' => '35',
                'category_id' => 32,
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 20:35:46',
                'updated_at' => '2025-05-27 20:35:46',
            ),
            206 => 
            array (
                'id' => 207,
                'name' => '1',
                'category_id' => 22,
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 20:37:30',
                'updated_at' => '2025-05-27 20:37:30',
            ),
            207 => 
            array (
                'id' => 208,
                'name' => '2',
                'category_id' => 22,
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 20:37:37',
                'updated_at' => '2025-05-27 20:37:37',
            ),
            208 => 
            array (
                'id' => 209,
                'name' => '3',
                'category_id' => 22,
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 20:37:42',
                'updated_at' => '2025-05-27 20:37:42',
            ),
            209 => 
            array (
                'id' => 210,
                'name' => '4',
                'category_id' => 22,
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 20:37:48',
                'updated_at' => '2025-05-27 20:37:48',
            ),
            210 => 
            array (
                'id' => 211,
                'name' => '12',
                'category_id' => 22,
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 20:37:55',
                'updated_at' => '2025-05-27 20:37:55',
            ),
            211 => 
            array (
                'id' => 212,
                'name' => '13',
                'category_id' => 22,
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 20:38:03',
                'updated_at' => '2025-05-27 20:38:03',
            ),
            212 => 
            array (
                'id' => 213,
                'name' => '30',
                'category_id' => 22,
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 20:38:11',
                'updated_at' => '2025-05-27 20:38:11',
            ),
            213 => 
            array (
                'id' => 214,
                'name' => '31',
                'category_id' => 22,
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 20:38:17',
                'updated_at' => '2025-05-27 20:38:17',
            ),
            214 => 
            array (
                'id' => 215,
                'name' => '32',
                'category_id' => 22,
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 20:38:23',
                'updated_at' => '2025-05-27 20:38:23',
            ),
            215 => 
            array (
                'id' => 216,
                'name' => '33',
                'category_id' => 22,
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 20:38:29',
                'updated_at' => '2025-05-27 20:38:29',
            ),
            216 => 
            array (
                'id' => 217,
                'name' => '34',
                'category_id' => 22,
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 20:38:35',
                'updated_at' => '2025-05-27 20:38:35',
            ),
            217 => 
            array (
                'id' => 218,
                'name' => '35',
                'category_id' => 22,
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 20:38:41',
                'updated_at' => '2025-05-27 20:38:41',
            ),
        ));
        
        \Schema::enableForeignKeyConstraints();
    }
}