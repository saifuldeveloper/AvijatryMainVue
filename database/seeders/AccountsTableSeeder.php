<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AccountsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('accounts')->delete();
        
        \DB::table('accounts')->insert(array (
            0 => 
            array (
                'id' => 1,
                'type' => 'bank-account',
                'name' => 'cash',
                'deleted_at' => NULL,
                'created_at' => '2024-05-01 03:57:12',
                'updated_at' => '2024-05-01 03:57:12',
            ),
            1 => 
            array (
                'id' => 2,
                'type' => 'bank-account',
                'name' => 'Pubali Bank',
                'deleted_at' => NULL,
                'created_at' => '2024-05-01 03:58:08',
                'updated_at' => '2024-05-01 03:58:08',
            ),
            2 => 
            array (
                'id' => 3,
                'type' => 'bank-account',
                'name' => 'জনতা ব্যাংক',
                'deleted_at' => NULL,
                'created_at' => '2024-05-01 03:58:31',
                'updated_at' => '2024-05-01 03:58:31',
            ),
            3 => 
            array (
                'id' => 4,
                'type' => 'bank-account',
                'name' => 'জনতা ব্যাংক',
                'deleted_at' => NULL,
                'created_at' => '2024-05-01 03:59:26',
                'updated_at' => '2024-05-01 03:59:26',
            ),
            4 => 
            array (
                'id' => 5,
                'type' => 'bank-account',
                'name' => 'Dutch Bangla Bank',
                'deleted_at' => NULL,
                'created_at' => '2024-05-01 03:59:45',
                'updated_at' => '2024-05-01 03:59:45',
            ),
            5 => 
            array (
                'id' => 6,
                'type' => 'bank-account',
                'name' => 'City Bank',
                'deleted_at' => NULL,
                'created_at' => '2024-05-01 03:59:56',
                'updated_at' => '2024-05-01 03:59:56',
            ),
            6 => 
            array (
                'id' => 1,
                'type' => 'factory',
                'name' => 'Al Amin',
                'deleted_at' => NULL,
                'created_at' => '2024-05-01 04:00:23',
                'updated_at' => '2024-05-01 04:00:23',
            ),
            7 => 
            array (
                'id' => 2,
                'type' => 'factory',
                'name' => 'Bokkor',
                'deleted_at' => NULL,
                'created_at' => '2024-05-01 04:00:40',
                'updated_at' => '2024-05-01 04:00:40',
            ),
            8 => 
            array (
                'id' => 3,
                'type' => 'factory',
                'name' => 'Mamun',
                'deleted_at' => NULL,
                'created_at' => '2024-05-01 04:01:00',
                'updated_at' => '2024-05-01 04:01:00',
            ),
            9 => 
            array (
                'id' => 1,
                'type' => 'retail-store',
                'name' => 'Tarek Rahaman',
                'deleted_at' => NULL,
                'created_at' => '2024-05-01 04:01:29',
                'updated_at' => '2024-05-01 04:01:29',
            ),
            10 => 
            array (
                'id' => 2,
                'type' => 'retail-store',
                'name' => 'Ariful',
                'deleted_at' => NULL,
                'created_at' => '2024-05-01 04:01:48',
                'updated_at' => '2024-05-01 04:01:48',
            ),
            11 => 
            array (
                'id' => 3,
                'type' => 'retail-store',
                'name' => 'Rasel sarkar',
                'deleted_at' => NULL,
                'created_at' => '2024-05-01 04:02:06',
                'updated_at' => '2024-05-01 04:02:06',
            ),
            12 => 
            array (
                'id' => 1,
                'type' => 'gift-supplier',
                'name' => 'Nasir uddin',
                'deleted_at' => NULL,
                'created_at' => '2024-05-01 04:02:42',
                'updated_at' => '2024-05-01 04:02:42',
            ),
            13 => 
            array (
                'id' => 2,
                'type' => 'gift-supplier',
                'name' => 'Belal sarkar',
                'deleted_at' => NULL,
                'created_at' => '2024-05-01 04:03:05',
                'updated_at' => '2024-05-01 04:03:05',
            ),
            14 => 
            array (
                'id' => 1,
                'type' => 'employee',
                'name' => 'Md Abdullah',
                'deleted_at' => NULL,
                'created_at' => '2025-04-05 23:17:36',
                'updated_at' => '2025-04-05 23:17:36',
            ),
            15 => 
            array (
                'id' => 4,
                'type' => 'factory',
                'name' => 'আবু সাঈদ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-05 23:25:27',
                'updated_at' => '2025-04-05 23:25:27',
            ),
            16 => 
            array (
                'id' => 5,
                'type' => 'factory',
                'name' => 'এবি স্যান্ডেল ফ্যাক্টরী',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 01:12:32',
                'updated_at' => '2025-04-06 01:12:32',
            ),
            17 => 
            array (
                'id' => 6,
                'type' => 'factory',
                'name' => 'মোঃ ইসমাইল',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 01:13:42',
                'updated_at' => '2025-04-06 01:13:42',
            ),
            18 => 
            array (
                'id' => 7,
                'type' => 'factory',
                'name' => 'মোঃ ইব্রাহীম',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 01:13:59',
                'updated_at' => '2025-04-06 01:13:59',
            ),
            19 => 
            array (
                'id' => 8,
                'type' => 'factory',
                'name' => 'মোঃ আইনুল',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 01:14:52',
                'updated_at' => '2025-04-06 01:14:52',
            ),
            20 => 
            array (
                'id' => 9,
                'type' => 'factory',
                'name' => 'মোঃ আজিজুল',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 01:15:35',
                'updated_at' => '2025-04-06 01:15:35',
            ),
            21 => 
            array (
                'id' => 10,
                'type' => 'factory',
                'name' => 'মোঃ ইমরান',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 01:18:21',
                'updated_at' => '2025-04-06 01:18:21',
            ),
            22 => 
            array (
                'id' => 11,
                'type' => 'factory',
                'name' => 'মোঃ রবি',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 01:19:39',
                'updated_at' => '2025-04-06 01:19:39',
            ),
            23 => 
            array (
                'id' => 12,
                'type' => 'factory',
                'name' => 'মোঃ আউয়াল',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 01:20:00',
                'updated_at' => '2025-04-06 01:20:00',
            ),
            24 => 
            array (
                'id' => 13,
                'type' => 'factory',
                'name' => 'মোঃ অলিউল্লাহ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 01:20:57',
                'updated_at' => '2025-04-06 01:20:57',
            ),
            25 => 
            array (
                'id' => 14,
                'type' => 'factory',
                'name' => 'মোঃ সিয়াম',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 01:22:20',
                'updated_at' => '2025-04-06 01:22:20',
            ),
            26 => 
            array (
                'id' => 15,
                'type' => 'factory',
                'name' => 'মোঃ শহীদুল্লাহ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 01:23:39',
                'updated_at' => '2025-04-06 01:23:39',
            ),
            27 => 
            array (
                'id' => 16,
                'type' => 'factory',
                'name' => 'মোঃ মাসুম',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 01:24:35',
                'updated_at' => '2025-04-06 01:24:35',
            ),
            28 => 
            array (
                'id' => 17,
                'type' => 'factory',
                'name' => 'মোঃ আলাউদ্দিন',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 01:24:55',
                'updated_at' => '2025-04-06 01:24:55',
            ),
            29 => 
            array (
                'id' => 18,
                'type' => 'factory',
                'name' => 'মোঃ রিয়াজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 01:25:44',
                'updated_at' => '2025-04-06 01:25:44',
            ),
            30 => 
            array (
                'id' => 19,
                'type' => 'factory',
                'name' => 'মোঃ তামিম',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 01:26:06',
                'updated_at' => '2025-04-06 01:26:06',
            ),
            31 => 
            array (
                'id' => 20,
                'type' => 'factory',
                'name' => 'মোঃ মিজান',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 02:02:29',
                'updated_at' => '2025-04-06 02:02:29',
            ),
            32 => 
            array (
                'id' => 21,
                'type' => 'factory',
                'name' => 'মোঃ শাহ আলম',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 02:29:59',
                'updated_at' => '2025-04-06 02:29:59',
            ),
            33 => 
            array (
                'id' => 22,
                'type' => 'factory',
                'name' => 'মোঃ রাহুল',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 02:32:25',
                'updated_at' => '2025-04-06 02:32:25',
            ),
            34 => 
            array (
                'id' => 23,
                'type' => 'factory',
                'name' => 'মোঃ রাহুল',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 02:44:16',
                'updated_at' => '2025-04-06 02:44:16',
            ),
            35 => 
            array (
                'id' => 24,
                'type' => 'factory',
                'name' => 'মোঃ রমজান',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 03:08:49',
                'updated_at' => '2025-04-06 03:08:49',
            ),
            36 => 
            array (
                'id' => 25,
                'type' => 'factory',
                'name' => 'মোঃ গোলাপ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 03:12:28',
                'updated_at' => '2025-04-06 03:12:28',
            ),
            37 => 
            array (
                'id' => 26,
                'type' => 'factory',
                'name' => 'মোঃ শাহাবুদ্দিন',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 03:13:51',
                'updated_at' => '2025-04-06 03:13:51',
            ),
            38 => 
            array (
                'id' => 27,
                'type' => 'factory',
                'name' => 'মোঃ তাসফিক',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 03:15:17',
                'updated_at' => '2025-04-06 03:15:17',
            ),
            39 => 
            array (
                'id' => 28,
                'type' => 'factory',
                'name' => 'মোঃ নজরুল',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 03:43:42',
                'updated_at' => '2025-04-06 03:43:42',
            ),
            40 => 
            array (
                'id' => 29,
                'type' => 'factory',
                'name' => 'মোঃ হোসাইন',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 03:44:56',
                'updated_at' => '2025-04-06 03:44:56',
            ),
            41 => 
            array (
                'id' => 30,
                'type' => 'factory',
                'name' => 'মোঃ তোফাজ্জল',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 03:45:58',
                'updated_at' => '2025-04-06 03:45:58',
            ),
            42 => 
            array (
                'id' => 31,
                'type' => 'factory',
                'name' => 'মোঃ ইকবাল',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 03:47:49',
                'updated_at' => '2025-04-06 03:47:49',
            ),
            43 => 
            array (
                'id' => 32,
                'type' => 'factory',
                'name' => 'মোঃ শুক্কুর',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 03:48:55',
                'updated_at' => '2025-04-06 03:48:55',
            ),
            44 => 
            array (
                'id' => 33,
                'type' => 'factory',
                'name' => 'মোঃ কাদির',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 03:51:01',
                'updated_at' => '2025-04-06 03:51:01',
            ),
            45 => 
            array (
                'id' => 34,
                'type' => 'factory',
                'name' => 'মোঃ ফেরদৌস',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 03:51:35',
                'updated_at' => '2025-04-06 03:51:35',
            ),
            46 => 
            array (
                'id' => 35,
                'type' => 'factory',
                'name' => 'মোঃ শাহ নেওয়াজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 04:39:25',
                'updated_at' => '2025-04-06 04:39:25',
            ),
            47 => 
            array (
                'id' => 36,
                'type' => 'factory',
                'name' => 'মোঃ বাশার',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 04:41:41',
                'updated_at' => '2025-04-06 04:41:41',
            ),
            48 => 
            array (
                'id' => 37,
                'type' => 'factory',
                'name' => 'জনতা স্যান্ডেল',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 04:54:07',
                'updated_at' => '2025-04-06 04:54:07',
            ),
            49 => 
            array (
                'id' => 38,
                'type' => 'factory',
                'name' => 'মোসাঃ মিমি',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 04:54:22',
                'updated_at' => '2025-04-06 04:54:22',
            ),
            50 => 
            array (
                'id' => 39,
                'type' => 'factory',
                'name' => 'মোঃ সামসুল',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 04:59:25',
                'updated_at' => '2025-04-06 04:59:25',
            ),
            51 => 
            array (
                'id' => 40,
                'type' => 'factory',
                'name' => 'ফ্যালকন স্যান্ডেল',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 05:32:44',
                'updated_at' => '2025-04-06 05:32:44',
            ),
            52 => 
            array (
                'id' => 41,
                'type' => 'factory',
                'name' => 'মোঃ জাহাঙ্গীর',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 06:24:36',
                'updated_at' => '2025-04-06 06:24:36',
            ),
            53 => 
            array (
                'id' => 42,
                'type' => 'factory',
                'name' => 'মোঃ আবুল হোসেন',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 06:27:42',
                'updated_at' => '2025-04-06 06:27:42',
            ),
            54 => 
            array (
                'id' => 43,
                'type' => 'factory',
                'name' => 'মোঃ তাকবীর',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 06:30:21',
                'updated_at' => '2025-04-06 06:30:21',
            ),
            55 => 
            array (
                'id' => 44,
                'type' => 'factory',
                'name' => 'মোঃ বাদল হোসেন',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 06:33:10',
                'updated_at' => '2025-04-06 06:33:10',
            ),
            56 => 
            array (
                'id' => 45,
                'type' => 'factory',
                'name' => 'মোঃ দুলাল',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 06:40:41',
                'updated_at' => '2025-04-06 06:40:41',
            ),
            57 => 
            array (
                'id' => 46,
                'type' => 'factory',
                'name' => 'মোঃ লিটন',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 06:42:03',
                'updated_at' => '2025-04-06 06:42:03',
            ),
            58 => 
            array (
                'id' => 47,
                'type' => 'factory',
                'name' => 'রকেট',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 06:42:42',
                'updated_at' => '2025-04-06 06:42:42',
            ),
            59 => 
            array (
                'id' => 48,
                'type' => 'factory',
                'name' => 'মোঃ ওয়াদুদ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 06:55:12',
                'updated_at' => '2025-04-06 06:55:12',
            ),
            60 => 
            array (
                'id' => 49,
                'type' => 'factory',
                'name' => 'মোঃ বাছির',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 06:56:41',
                'updated_at' => '2025-04-06 06:56:41',
            ),
            61 => 
            array (
                'id' => 50,
                'type' => 'factory',
                'name' => 'মোঃ সোহেল',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 06:57:55',
                'updated_at' => '2025-04-06 06:57:55',
            ),
            62 => 
            array (
                'id' => 51,
                'type' => 'factory',
                'name' => 'মোঃ মাহবুব',
                'deleted_at' => NULL,
                'created_at' => '2025-04-06 06:58:54',
                'updated_at' => '2025-04-06 06:58:54',
            ),
            63 => 
            array (
                'id' => 52,
                'type' => 'factory',
                'name' => 'মোঃ ঝন্টু',
                'deleted_at' => NULL,
                'created_at' => '2025-04-07 04:42:23',
                'updated_at' => '2025-04-07 04:42:23',
            ),
            64 => 
            array (
                'id' => 53,
                'type' => 'factory',
                'name' => 'মোঃ হিবজু',
                'deleted_at' => NULL,
                'created_at' => '2025-04-07 04:44:12',
                'updated_at' => '2025-04-07 04:44:12',
            ),
            65 => 
            array (
                'id' => 54,
                'type' => 'factory',
                'name' => 'মোঃ আরমান',
                'deleted_at' => NULL,
                'created_at' => '2025-04-07 04:47:47',
                'updated_at' => '2025-04-07 04:47:47',
            ),
            66 => 
            array (
                'id' => 55,
                'type' => 'factory',
                'name' => 'মোঃ ফয়েজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-07 05:14:09',
                'updated_at' => '2025-04-07 05:14:09',
            ),
            67 => 
            array (
                'id' => 56,
                'type' => 'factory',
                'name' => 'মোঃ সায়মন',
                'deleted_at' => NULL,
                'created_at' => '2025-04-07 05:19:05',
                'updated_at' => '2025-04-07 05:19:05',
            ),
            68 => 
            array (
                'id' => 57,
                'type' => 'factory',
                'name' => 'মোঃ রাজিব',
                'deleted_at' => NULL,
                'created_at' => '2025-04-07 05:38:26',
                'updated_at' => '2025-04-07 05:38:26',
            ),
            69 => 
            array (
                'id' => 58,
                'type' => 'factory',
                'name' => 'মোঃ কবির',
                'deleted_at' => NULL,
                'created_at' => '2025-04-07 05:48:31',
                'updated_at' => '2025-04-07 05:48:31',
            ),
            70 => 
            array (
                'id' => 59,
                'type' => 'factory',
                'name' => 'মোঃ আওলাদ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-07 05:50:58',
                'updated_at' => '2025-04-07 05:50:58',
            ),
            71 => 
            array (
                'id' => 60,
                'type' => 'factory',
                'name' => 'মোঃ সিদরাতুল',
                'deleted_at' => NULL,
                'created_at' => '2025-04-07 06:01:24',
                'updated_at' => '2025-04-07 06:01:24',
            ),
            72 => 
            array (
                'id' => 61,
                'type' => 'factory',
                'name' => 'মোঃ রাজু',
                'deleted_at' => NULL,
                'created_at' => '2025-04-07 06:03:26',
                'updated_at' => '2025-04-07 06:03:26',
            ),
            73 => 
            array (
                'id' => 62,
                'type' => 'factory',
                'name' => 'বেপারী',
                'deleted_at' => NULL,
                'created_at' => '2025-04-07 06:03:39',
                'updated_at' => '2025-04-07 06:03:39',
            ),
            74 => 
            array (
                'id' => 63,
                'type' => 'factory',
                'name' => 'মোঃ শফিকুল',
                'deleted_at' => NULL,
                'created_at' => '2025-04-07 06:12:58',
                'updated_at' => '2025-04-07 06:12:58',
            ),
            75 => 
            array (
                'id' => 64,
                'type' => 'factory',
                'name' => 'মোঃ রুবেল',
                'deleted_at' => NULL,
                'created_at' => '2025-04-07 06:27:25',
                'updated_at' => '2025-04-07 06:27:25',
            ),
            76 => 
            array (
                'id' => 65,
                'type' => 'factory',
                'name' => 'মোঃ সাদমান',
                'deleted_at' => NULL,
                'created_at' => '2025-04-07 06:35:56',
                'updated_at' => '2025-04-07 06:35:56',
            ),
            77 => 
            array (
                'id' => 66,
                'type' => 'factory',
                'name' => 'মোসাঃ আফছা',
                'deleted_at' => NULL,
                'created_at' => '2025-04-07 07:03:59',
                'updated_at' => '2025-04-07 07:03:59',
            ),
            78 => 
            array (
                'id' => 67,
                'type' => 'factory',
                'name' => 'মোঃ লালচান',
                'deleted_at' => NULL,
                'created_at' => '2025-04-07 08:24:48',
                'updated_at' => '2025-04-07 08:24:48',
            ),
            79 => 
            array (
                'id' => 68,
                'type' => 'factory',
                'name' => 'মোঃ বায়জিদ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-07 08:29:42',
                'updated_at' => '2025-04-07 08:29:42',
            ),
            80 => 
            array (
                'id' => 69,
                'type' => 'factory',
                'name' => 'মোঃ ফয়সাল',
                'deleted_at' => NULL,
                'created_at' => '2025-04-07 09:37:25',
                'updated_at' => '2025-04-07 09:37:25',
            ),
            81 => 
            array (
                'id' => 70,
                'type' => 'factory',
                'name' => 'মোঃ মনির',
                'deleted_at' => NULL,
                'created_at' => '2025-04-07 09:39:36',
                'updated_at' => '2025-04-07 09:39:36',
            ),
            82 => 
            array (
                'id' => 71,
                'type' => 'factory',
                'name' => 'মোঃ শাহজাহান',
                'deleted_at' => NULL,
                'created_at' => '2025-04-07 09:57:36',
                'updated_at' => '2025-04-07 09:57:36',
            ),
            83 => 
            array (
                'id' => 72,
                'type' => 'factory',
                'name' => 'মোঃ রিদয়',
                'deleted_at' => NULL,
                'created_at' => '2025-04-07 10:03:11',
                'updated_at' => '2025-04-07 10:03:11',
            ),
            84 => 
            array (
                'id' => 73,
                'type' => 'factory',
                'name' => 'মোঃ শাহরিয়ার',
                'deleted_at' => NULL,
                'created_at' => '2025-04-07 10:05:41',
                'updated_at' => '2025-04-07 10:05:41',
            ),
            85 => 
            array (
                'id' => 74,
                'type' => 'factory',
                'name' => 'মোঃ আমিন',
                'deleted_at' => NULL,
                'created_at' => '2025-04-07 10:08:00',
                'updated_at' => '2025-04-07 10:08:00',
            ),
            86 => 
            array (
                'id' => 75,
                'type' => 'factory',
                'name' => 'মোসাঃ ফাইজা',
                'deleted_at' => NULL,
                'created_at' => '2025-04-07 10:10:19',
                'updated_at' => '2025-04-07 10:10:19',
            ),
            87 => 
            array (
                'id' => 76,
                'type' => 'factory',
                'name' => 'মোঃ নোবেল',
                'deleted_at' => NULL,
                'created_at' => '2025-04-07 10:15:20',
                'updated_at' => '2025-04-07 10:15:20',
            ),
            88 => 
            array (
                'id' => 77,
                'type' => 'factory',
                'name' => 'মোঃ সাকিব',
                'deleted_at' => NULL,
                'created_at' => '2025-04-07 10:15:51',
                'updated_at' => '2025-04-07 10:15:51',
            ),
            89 => 
            array (
                'id' => 78,
                'type' => 'factory',
                'name' => 'মোঃ শহীদুজ্জামান',
                'deleted_at' => NULL,
                'created_at' => '2025-04-07 10:20:24',
                'updated_at' => '2025-04-07 10:20:24',
            ),
            90 => 
            array (
                'id' => 79,
                'type' => 'factory',
                'name' => 'মোঃ আয়রা',
                'deleted_at' => NULL,
                'created_at' => '2025-04-07 10:22:11',
                'updated_at' => '2025-04-07 10:22:11',
            ),
            91 => 
            array (
                'id' => 80,
                'type' => 'factory',
                'name' => 'মোঃ কামরান',
                'deleted_at' => NULL,
                'created_at' => '2025-04-07 10:27:27',
                'updated_at' => '2025-04-07 10:27:27',
            ),
            92 => 
            array (
                'id' => 81,
                'type' => 'factory',
                'name' => 'মোঃ পলাশ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-07 10:28:47',
                'updated_at' => '2025-04-07 10:28:47',
            ),
            93 => 
            array (
                'id' => 82,
                'type' => 'factory',
                'name' => 'মোঃ জালাল ফকির',
                'deleted_at' => NULL,
                'created_at' => '2025-04-08 00:00:25',
                'updated_at' => '2025-04-08 00:00:25',
            ),
            94 => 
            array (
                'id' => 83,
                'type' => 'factory',
                'name' => 'মোঃ শাওন',
                'deleted_at' => NULL,
                'created_at' => '2025-04-08 00:08:38',
                'updated_at' => '2025-04-08 00:08:38',
            ),
            95 => 
            array (
                'id' => 84,
                'type' => 'factory',
                'name' => 'মোঃ ফখরুল',
                'deleted_at' => NULL,
                'created_at' => '2025-04-08 00:42:13',
                'updated_at' => '2025-04-08 00:42:13',
            ),
            96 => 
            array (
                'id' => 85,
                'type' => 'factory',
                'name' => 'মোঃ রানা',
                'deleted_at' => NULL,
                'created_at' => '2025-04-08 00:52:08',
                'updated_at' => '2025-04-08 00:52:08',
            ),
            97 => 
            array (
                'id' => 86,
                'type' => 'factory',
                'name' => 'মোঃ জাহান',
                'deleted_at' => NULL,
                'created_at' => '2025-04-08 00:54:04',
                'updated_at' => '2025-04-08 00:54:04',
            ),
            98 => 
            array (
                'id' => 87,
                'type' => 'factory',
                'name' => 'মোঃ সাজু',
                'deleted_at' => NULL,
                'created_at' => '2025-04-08 00:56:09',
                'updated_at' => '2025-04-08 00:56:09',
            ),
            99 => 
            array (
                'id' => 88,
                'type' => 'factory',
                'name' => 'মোঃ মহসিন',
                'deleted_at' => NULL,
                'created_at' => '2025-04-08 00:57:51',
                'updated_at' => '2025-04-08 00:57:51',
            ),
            100 => 
            array (
                'id' => 89,
                'type' => 'factory',
                'name' => 'মোসাঃ হাবিবা',
                'deleted_at' => NULL,
                'created_at' => '2025-04-08 00:59:07',
                'updated_at' => '2025-04-08 00:59:07',
            ),
            101 => 
            array (
                'id' => 90,
                'type' => 'factory',
                'name' => 'মোঃ বকুল',
                'deleted_at' => NULL,
                'created_at' => '2025-04-08 01:03:10',
                'updated_at' => '2025-04-08 01:03:10',
            ),
            102 => 
            array (
                'id' => 91,
                'type' => 'factory',
                'name' => 'মোঃ আজগর',
                'deleted_at' => NULL,
                'created_at' => '2025-04-08 01:05:08',
                'updated_at' => '2025-04-08 01:05:08',
            ),
            103 => 
            array (
                'id' => 92,
                'type' => 'factory',
                'name' => 'মোঃ নিরব',
                'deleted_at' => NULL,
                'created_at' => '2025-04-08 02:06:20',
                'updated_at' => '2025-04-08 02:06:20',
            ),
            104 => 
            array (
                'id' => 93,
                'type' => 'factory',
                'name' => 'মোঃ শাহীন',
                'deleted_at' => NULL,
                'created_at' => '2025-04-08 03:25:05',
                'updated_at' => '2025-04-08 03:25:05',
            ),
            105 => 
            array (
                'id' => 94,
                'type' => 'factory',
                'name' => 'মোঃ হারিস',
                'deleted_at' => NULL,
                'created_at' => '2025-04-08 03:32:02',
                'updated_at' => '2025-04-08 03:32:02',
            ),
            106 => 
            array (
                'id' => 95,
                'type' => 'factory',
                'name' => 'মোঃ সৌরভ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-08 03:38:42',
                'updated_at' => '2025-04-08 03:38:42',
            ),
            107 => 
            array (
                'id' => 96,
                'type' => 'factory',
                'name' => 'মোঃ আলম',
                'deleted_at' => NULL,
                'created_at' => '2025-04-08 03:39:45',
                'updated_at' => '2025-04-08 03:39:45',
            ),
            108 => 
            array (
                'id' => 97,
                'type' => 'factory',
                'name' => 'মোসাঃ জান্নাত',
                'deleted_at' => NULL,
                'created_at' => '2025-04-08 05:23:24',
                'updated_at' => '2025-04-08 05:23:24',
            ),
            109 => 
            array (
                'id' => 98,
                'type' => 'factory',
                'name' => 'ডিলাইট',
                'deleted_at' => NULL,
                'created_at' => '2025-04-08 05:30:16',
                'updated_at' => '2025-04-08 05:30:16',
            ),
            110 => 
            array (
                'id' => 99,
                'type' => 'factory',
                'name' => 'মোঃ সাইফুল',
                'deleted_at' => NULL,
                'created_at' => '2025-04-08 05:35:03',
                'updated_at' => '2025-04-08 05:35:03',
            ),
            111 => 
            array (
                'id' => 100,
                'type' => 'factory',
                'name' => 'মোঃ অন্তর',
                'deleted_at' => NULL,
                'created_at' => '2025-04-08 05:41:19',
                'updated_at' => '2025-04-08 05:41:19',
            ),
            112 => 
            array (
                'id' => 101,
                'type' => 'factory',
                'name' => 'মোঃ অপু',
                'deleted_at' => NULL,
                'created_at' => '2025-04-08 06:03:13',
                'updated_at' => '2025-04-08 06:03:13',
            ),
            113 => 
            array (
                'id' => 102,
                'type' => 'factory',
                'name' => 'মোঃ জিয়া',
                'deleted_at' => NULL,
                'created_at' => '2025-04-08 06:12:24',
                'updated_at' => '2025-04-08 06:12:24',
            ),
            114 => 
            array (
                'id' => 103,
                'type' => 'factory',
                'name' => 'মোঃ আইজান',
                'deleted_at' => NULL,
                'created_at' => '2025-04-08 07:18:52',
                'updated_at' => '2025-04-08 07:18:52',
            ),
            115 => 
            array (
                'id' => 104,
                'type' => 'factory',
                'name' => 'মোঃ সালাম',
                'deleted_at' => NULL,
                'created_at' => '2025-04-09 01:04:11',
                'updated_at' => '2025-04-09 01:04:11',
            ),
            116 => 
            array (
                'id' => 105,
                'type' => 'factory',
                'name' => 'মোসাঃ কারিমা',
                'deleted_at' => NULL,
                'created_at' => '2025-04-09 01:06:07',
                'updated_at' => '2025-04-09 01:06:07',
            ),
            117 => 
            array (
                'id' => 106,
                'type' => 'factory',
                'name' => 'মোঃ সায়েম',
                'deleted_at' => NULL,
                'created_at' => '2025-04-09 01:08:03',
                'updated_at' => '2025-04-09 01:08:03',
            ),
            118 => 
            array (
                'id' => 107,
                'type' => 'factory',
                'name' => 'মোঃ মিঠু',
                'deleted_at' => NULL,
                'created_at' => '2025-04-09 01:15:20',
                'updated_at' => '2025-04-09 01:15:20',
            ),
            119 => 
            array (
                'id' => 108,
                'type' => 'factory',
                'name' => 'মোঃ রোকন',
                'deleted_at' => NULL,
                'created_at' => '2025-04-09 04:57:03',
                'updated_at' => '2025-04-09 04:57:03',
            ),
            120 => 
            array (
                'id' => 109,
                'type' => 'factory',
                'name' => 'মোঃ সাঈদ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-09 05:42:56',
                'updated_at' => '2025-04-09 05:42:56',
            ),
            121 => 
            array (
                'id' => 110,
                'type' => 'factory',
                'name' => 'মোঃ সামিউল',
                'deleted_at' => NULL,
                'created_at' => '2025-04-09 06:17:00',
                'updated_at' => '2025-04-09 06:17:00',
            ),
            122 => 
            array (
                'id' => 111,
                'type' => 'factory',
                'name' => 'মোঃ শফিক',
                'deleted_at' => NULL,
                'created_at' => '2025-04-09 07:26:32',
                'updated_at' => '2025-04-09 07:26:32',
            ),
            123 => 
            array (
                'id' => 112,
                'type' => 'factory',
                'name' => 'মোঃ আদনান',
                'deleted_at' => NULL,
                'created_at' => '2025-04-09 07:56:15',
                'updated_at' => '2025-04-09 07:56:15',
            ),
            124 => 
            array (
                'id' => 113,
                'type' => 'factory',
                'name' => 'মোঃ জসীম',
                'deleted_at' => NULL,
                'created_at' => '2025-04-09 08:01:21',
                'updated_at' => '2025-04-09 08:01:21',
            ),
            125 => 
            array (
                'id' => 114,
                'type' => 'factory',
                'name' => 'মোঃ জাহীদ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-09 08:11:29',
                'updated_at' => '2025-04-09 08:11:29',
            ),
            126 => 
            array (
                'id' => 115,
                'type' => 'factory',
                'name' => 'মোঃ হামিদ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-09 08:12:15',
                'updated_at' => '2025-04-09 08:12:15',
            ),
            127 => 
            array (
                'id' => 116,
                'type' => 'factory',
                'name' => 'মোঃ মুরাদ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-09 08:52:28',
                'updated_at' => '2025-04-09 08:52:28',
            ),
            128 => 
            array (
                'id' => 117,
                'type' => 'factory',
                'name' => 'মোঃ শাহজালাল',
                'deleted_at' => NULL,
                'created_at' => '2025-04-09 08:54:00',
                'updated_at' => '2025-04-09 08:54:00',
            ),
            129 => 
            array (
                'id' => 118,
                'type' => 'factory',
                'name' => 'মোঃ জিল্লুর রহমান',
                'deleted_at' => NULL,
                'created_at' => '2025-04-09 09:40:15',
                'updated_at' => '2025-04-09 09:40:15',
            ),
            130 => 
            array (
                'id' => 119,
                'type' => 'factory',
                'name' => 'মোঃ রফিক',
                'deleted_at' => NULL,
                'created_at' => '2025-04-09 09:43:11',
                'updated_at' => '2025-04-09 09:43:11',
            ),
            131 => 
            array (
                'id' => 120,
                'type' => 'factory',
                'name' => 'মোঃ কাসেম',
                'deleted_at' => NULL,
                'created_at' => '2025-04-09 09:45:30',
                'updated_at' => '2025-04-09 09:45:30',
            ),
            132 => 
            array (
                'id' => 121,
                'type' => 'factory',
                'name' => 'মোঃ হাবিব',
                'deleted_at' => NULL,
                'created_at' => '2025-04-09 09:47:15',
                'updated_at' => '2025-04-09 09:47:15',
            ),
            133 => 
            array (
                'id' => 122,
                'type' => 'factory',
                'name' => 'মোঃ মামুন',
                'deleted_at' => NULL,
                'created_at' => '2025-04-09 09:51:46',
                'updated_at' => '2025-04-09 09:51:46',
            ),
            134 => 
            array (
                'id' => 123,
                'type' => 'factory',
                'name' => 'মোঃ বাহার',
                'deleted_at' => NULL,
                'created_at' => '2025-04-09 09:55:07',
                'updated_at' => '2025-04-09 09:55:07',
            ),
            135 => 
            array (
                'id' => 124,
                'type' => 'factory',
                'name' => 'মোঃ রাজিন',
                'deleted_at' => NULL,
                'created_at' => '2025-04-09 09:58:17',
                'updated_at' => '2025-04-09 09:58:17',
            ),
            136 => 
            array (
                'id' => 125,
                'type' => 'factory',
                'name' => 'মোঃ বিল্লাল',
                'deleted_at' => NULL,
                'created_at' => '2025-04-09 09:59:45',
                'updated_at' => '2025-04-09 09:59:45',
            ),
            137 => 
            array (
                'id' => 126,
                'type' => 'factory',
                'name' => 'মোঃ শরীফ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-09 23:35:46',
                'updated_at' => '2025-04-09 23:35:46',
            ),
            138 => 
            array (
                'id' => 127,
                'type' => 'factory',
                'name' => 'মোঃ স্বপন',
                'deleted_at' => NULL,
                'created_at' => '2025-04-09 23:44:02',
                'updated_at' => '2025-04-09 23:44:02',
            ),
            139 => 
            array (
                'id' => 128,
                'type' => 'factory',
                'name' => 'মোঃ ফয়েজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-09 23:45:56',
                'updated_at' => '2025-04-09 23:45:56',
            ),
            140 => 
            array (
                'id' => 129,
                'type' => 'factory',
                'name' => 'মোঃ নাজমুল',
                'deleted_at' => NULL,
                'created_at' => '2025-04-10 00:23:52',
                'updated_at' => '2025-04-10 00:23:52',
            ),
            141 => 
            array (
                'id' => 130,
                'type' => 'factory',
                'name' => 'মোঃ লাদেন',
                'deleted_at' => NULL,
                'created_at' => '2025-04-10 01:01:57',
                'updated_at' => '2025-04-10 01:01:57',
            ),
            142 => 
            array (
                'id' => 131,
                'type' => 'factory',
                'name' => 'মোঃ হিম্মত আলী',
                'deleted_at' => NULL,
                'created_at' => '2025-04-10 02:34:12',
                'updated_at' => '2025-04-10 02:34:12',
            ),
            143 => 
            array (
                'id' => 132,
                'type' => 'factory',
                'name' => 'মোঃ আলী',
                'deleted_at' => NULL,
                'created_at' => '2025-04-10 02:39:46',
                'updated_at' => '2025-04-10 02:39:46',
            ),
            144 => 
            array (
                'id' => 133,
                'type' => 'factory',
                'name' => 'মোঃ চৌধুরী',
                'deleted_at' => NULL,
                'created_at' => '2025-04-10 02:42:35',
                'updated_at' => '2025-04-10 02:42:35',
            ),
            145 => 
            array (
                'id' => 134,
                'type' => 'factory',
                'name' => 'মোঃ নান্টু',
                'deleted_at' => NULL,
                'created_at' => '2025-04-10 02:59:26',
                'updated_at' => '2025-04-10 02:59:26',
            ),
            146 => 
            array (
                'id' => 135,
                'type' => 'factory',
                'name' => 'সুন্দরী',
                'deleted_at' => NULL,
                'created_at' => '2025-04-10 03:01:51',
                'updated_at' => '2025-04-10 03:01:51',
            ),
            147 => 
            array (
                'id' => 136,
                'type' => 'factory',
                'name' => 'মোঃ রাফি',
                'deleted_at' => NULL,
                'created_at' => '2025-04-10 03:07:44',
                'updated_at' => '2025-04-10 03:07:44',
            ),
            148 => 
            array (
                'id' => 137,
                'type' => 'factory',
                'name' => 'মোঃ আনার',
                'deleted_at' => NULL,
                'created_at' => '2025-04-10 04:31:57',
                'updated_at' => '2025-04-10 04:31:57',
            ),
            149 => 
            array (
                'id' => 138,
                'type' => 'factory',
                'name' => 'মোঃ আশিক',
                'deleted_at' => NULL,
                'created_at' => '2025-04-10 04:44:18',
                'updated_at' => '2025-04-10 04:44:18',
            ),
            150 => 
            array (
                'id' => 139,
                'type' => 'factory',
                'name' => 'মোঃ বাচ্চু',
                'deleted_at' => NULL,
                'created_at' => '2025-04-10 04:58:26',
                'updated_at' => '2025-04-10 04:58:26',
            ),
            151 => 
            array (
                'id' => 140,
                'type' => 'factory',
                'name' => 'মোঃ আরাফাত',
                'deleted_at' => NULL,
                'created_at' => '2025-04-10 06:49:51',
                'updated_at' => '2025-04-10 06:49:51',
            ),
            152 => 
            array (
                'id' => 141,
                'type' => 'factory',
                'name' => 'মোঃ রুহুল আমিন',
                'deleted_at' => NULL,
                'created_at' => '2025-04-11 00:23:36',
                'updated_at' => '2025-04-11 00:23:36',
            ),
            153 => 
            array (
                'id' => 142,
                'type' => 'factory',
                'name' => 'মোঃ আমির',
                'deleted_at' => NULL,
                'created_at' => '2025-04-11 03:36:29',
                'updated_at' => '2025-04-11 03:36:29',
            ),
            154 => 
            array (
                'id' => 143,
                'type' => 'factory',
                'name' => 'মোঃ ফারুক',
                'deleted_at' => NULL,
                'created_at' => '2025-04-11 03:37:39',
                'updated_at' => '2025-04-11 03:37:39',
            ),
            155 => 
            array (
                'id' => 144,
                'type' => 'factory',
                'name' => 'মোঃ তাজভীন',
                'deleted_at' => NULL,
                'created_at' => '2025-04-11 03:38:36',
                'updated_at' => '2025-04-11 03:38:36',
            ),
            156 => 
            array (
                'id' => 145,
                'type' => 'factory',
                'name' => 'মোসাঃ আফসানা',
                'deleted_at' => NULL,
                'created_at' => '2025-04-11 05:21:39',
                'updated_at' => '2025-04-11 05:21:39',
            ),
            157 => 
            array (
                'id' => 146,
                'type' => 'factory',
                'name' => 'মোঃ দেওয়ান',
                'deleted_at' => NULL,
                'created_at' => '2025-04-11 05:56:16',
                'updated_at' => '2025-04-11 05:56:16',
            ),
            158 => 
            array (
                'id' => 147,
                'type' => 'factory',
                'name' => 'মোঃ নবী হোসেন',
                'deleted_at' => NULL,
                'created_at' => '2025-04-11 05:59:26',
                'updated_at' => '2025-04-11 05:59:26',
            ),
            159 => 
            array (
                'id' => 148,
                'type' => 'factory',
                'name' => 'মোসাঃ ছোয়ামণি',
                'deleted_at' => NULL,
                'created_at' => '2025-04-11 06:02:49',
                'updated_at' => '2025-04-11 06:02:49',
            ),
            160 => 
            array (
                'id' => 149,
                'type' => 'factory',
                'name' => 'মোঃ সামাদ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-11 06:05:02',
                'updated_at' => '2025-04-11 06:05:02',
            ),
            161 => 
            array (
                'id' => 150,
                'type' => 'factory',
                'name' => 'মোঃ কালাম',
                'deleted_at' => NULL,
                'created_at' => '2025-04-11 06:07:50',
                'updated_at' => '2025-04-11 06:07:50',
            ),
            162 => 
            array (
                'id' => 151,
                'type' => 'factory',
                'name' => 'মোসাঃ সোনালী',
                'deleted_at' => NULL,
                'created_at' => '2025-04-11 06:09:26',
                'updated_at' => '2025-04-11 06:09:26',
            ),
            163 => 
            array (
                'id' => 152,
                'type' => 'factory',
                'name' => 'মোঃ আব্দুর রহমান',
                'deleted_at' => NULL,
                'created_at' => '2025-04-13 00:28:50',
                'updated_at' => '2025-04-13 00:28:50',
            ),
            164 => 
            array (
                'id' => 153,
                'type' => 'factory',
                'name' => 'মোঃ মুজিবর',
                'deleted_at' => NULL,
                'created_at' => '2025-04-13 00:55:51',
                'updated_at' => '2025-04-13 00:55:51',
            ),
            165 => 
            array (
                'id' => 154,
                'type' => 'factory',
                'name' => 'ভূঁইয়া স্যান্ডেল',
                'deleted_at' => NULL,
                'created_at' => '2025-04-13 00:58:18',
                'updated_at' => '2025-04-13 00:58:18',
            ),
            166 => 
            array (
                'id' => 155,
                'type' => 'factory',
                'name' => 'মোঃ জহির',
                'deleted_at' => NULL,
                'created_at' => '2025-04-13 01:49:55',
                'updated_at' => '2025-04-13 01:49:55',
            ),
            167 => 
            array (
                'id' => 156,
                'type' => 'factory',
                'name' => 'মোঃ সোহরাব',
                'deleted_at' => NULL,
                'created_at' => '2025-04-13 01:50:50',
                'updated_at' => '2025-04-13 01:50:50',
            ),
            168 => 
            array (
                'id' => 157,
                'type' => 'factory',
                'name' => 'মোঃ মোশারফ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-13 02:28:25',
                'updated_at' => '2025-04-13 02:28:25',
            ),
            169 => 
            array (
                'id' => 158,
                'type' => 'factory',
                'name' => 'মোঃ আফছার',
                'deleted_at' => NULL,
                'created_at' => '2025-04-13 02:33:56',
                'updated_at' => '2025-04-13 02:33:56',
            ),
            170 => 
            array (
                'id' => 159,
                'type' => 'factory',
                'name' => 'মোঃ ফরিদ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-13 05:44:02',
                'updated_at' => '2025-04-13 05:44:02',
            ),
            171 => 
            array (
                'id' => 160,
                'type' => 'factory',
                'name' => 'মোসাঃ হুমায়রা',
                'deleted_at' => NULL,
                'created_at' => '2025-04-13 05:46:22',
                'updated_at' => '2025-04-13 05:46:22',
            ),
            172 => 
            array (
                'id' => 161,
                'type' => 'factory',
                'name' => 'মোঃ আল আমিন',
                'deleted_at' => NULL,
                'created_at' => '2025-04-14 00:27:15',
                'updated_at' => '2025-04-14 00:27:15',
            ),
            173 => 
            array (
                'id' => 162,
                'type' => 'factory',
                'name' => 'মোঃ শুভ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-14 00:49:02',
                'updated_at' => '2025-04-14 00:49:02',
            ),
            174 => 
            array (
                'id' => 163,
                'type' => 'factory',
                'name' => 'মোঃ সাজ্জাদ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-14 03:32:02',
                'updated_at' => '2025-04-14 03:32:02',
            ),
            175 => 
            array (
                'id' => 3,
                'type' => 'retail-store',
                'name' => 'সাথী সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-14 17:47:00',
                'updated_at' => '2025-04-14 17:47:00',
            ),
            176 => 
            array (
                'id' => 164,
                'type' => 'factory',
                'name' => 'মোঃ বাবলু',
                'deleted_at' => NULL,
                'created_at' => '2025-04-14 18:45:23',
                'updated_at' => '2025-04-14 18:45:23',
            ),
            177 => 
            array (
                'id' => 165,
                'type' => 'factory',
                'name' => 'প্রদিপ বাবু',
                'deleted_at' => NULL,
                'created_at' => '2025-04-14 18:56:09',
                'updated_at' => '2025-04-14 18:56:09',
            ),
            178 => 
            array (
                'id' => 166,
                'type' => 'factory',
                'name' => 'মোঃ শহীদ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-14 19:05:10',
                'updated_at' => '2025-04-14 19:05:10',
            ),
            179 => 
            array (
                'id' => 167,
                'type' => 'factory',
                'name' => 'মোঃ শামিম',
                'deleted_at' => NULL,
                'created_at' => '2025-04-14 20:26:58',
                'updated_at' => '2025-04-14 20:26:58',
            ),
            180 => 
            array (
                'id' => 168,
                'type' => 'factory',
                'name' => 'মোঃ আয়ান বেপারী',
                'deleted_at' => NULL,
                'created_at' => '2025-04-14 20:27:50',
                'updated_at' => '2025-04-14 20:27:50',
            ),
            181 => 
            array (
                'id' => 169,
                'type' => 'factory',
                'name' => 'মোসাঃ আফিয়া',
                'deleted_at' => NULL,
                'created_at' => '2025-04-14 20:32:48',
                'updated_at' => '2025-04-14 20:32:48',
            ),
            182 => 
            array (
                'id' => 170,
                'type' => 'factory',
                'name' => 'মোঃ নুরনবী',
                'deleted_at' => NULL,
                'created_at' => '2025-04-14 20:41:51',
                'updated_at' => '2025-04-14 20:41:51',
            ),
            183 => 
            array (
                'id' => 4,
                'type' => 'retail-store',
                'name' => 'সাথী সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-14 22:02:21',
                'updated_at' => '2025-04-14 22:02:21',
            ),
            184 => 
            array (
                'id' => 2,
                'type' => 'employee',
                'name' => 'Md Mir Farhad Hossain',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 17:24:21',
                'updated_at' => '2025-04-15 17:24:21',
            ),
            185 => 
            array (
                'id' => 3,
                'type' => 'employee',
                'name' => 'Kamal Hosan',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 17:25:12',
                'updated_at' => '2025-04-15 17:25:12',
            ),
            186 => 
            array (
                'id' => 4,
                'type' => 'employee',
                'name' => 'Md Musa Mia',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 17:26:27',
                'updated_at' => '2025-04-15 17:26:27',
            ),
            187 => 
            array (
                'id' => 5,
                'type' => 'employee',
                'name' => 'Md Khokon Ahmed',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 17:27:48',
                'updated_at' => '2025-04-15 17:27:48',
            ),
            188 => 
            array (
                'id' => 6,
                'type' => 'employee',
                'name' => 'Md Shiful Azam Rasal',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 17:29:29',
                'updated_at' => '2025-04-15 17:29:29',
            ),
            189 => 
            array (
                'id' => 7,
                'type' => 'employee',
                'name' => 'Md Shah Poran',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 17:31:33',
                'updated_at' => '2025-04-15 17:31:33',
            ),
            190 => 
            array (
                'id' => 8,
                'type' => 'employee',
                'name' => 'Md Rasel Mia',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 17:33:08',
                'updated_at' => '2025-04-15 17:33:08',
            ),
            191 => 
            array (
                'id' => 9,
                'type' => 'employee',
                'name' => 'Md Wahid',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 17:33:44',
                'updated_at' => '2025-04-15 17:33:44',
            ),
            192 => 
            array (
                'id' => 10,
                'type' => 'employee',
                'name' => 'Md Hasan',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 17:36:00',
                'updated_at' => '2025-04-15 17:36:00',
            ),
            193 => 
            array (
                'id' => 11,
                'type' => 'employee',
                'name' => 'Md Yasin',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 17:37:16',
                'updated_at' => '2025-04-15 17:37:16',
            ),
            194 => 
            array (
                'id' => 12,
                'type' => 'employee',
                'name' => 'Md junnun',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 17:37:53',
                'updated_at' => '2025-04-15 17:37:53',
            ),
            195 => 
            array (
                'id' => 13,
                'type' => 'employee',
                'name' => 'Md Shamim',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 17:39:00',
                'updated_at' => '2025-04-15 17:39:00',
            ),
            196 => 
            array (
                'id' => 14,
                'type' => 'employee',
                'name' => 'Md Masud',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 17:40:54',
                'updated_at' => '2025-04-15 17:40:54',
            ),
            197 => 
            array (
                'id' => 15,
                'type' => 'employee',
                'name' => 'Md Anis Mia',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 17:41:30',
                'updated_at' => '2025-04-15 17:41:30',
            ),
            198 => 
            array (
                'id' => 16,
                'type' => 'employee',
                'name' => 'Md Harun',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 17:42:02',
                'updated_at' => '2025-04-15 17:42:02',
            ),
            199 => 
            array (
                'id' => 17,
                'type' => 'employee',
            'name' => 'Md Jahangir (Driver)',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 17:44:44',
                'updated_at' => '2025-04-15 17:44:44',
            ),
            200 => 
            array (
                'id' => 1,
                'type' => 'expense',
                'name' => 'A H M Taslim Uddin',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 17:45:19',
                'updated_at' => '2025-04-15 17:45:19',
            ),
            201 => 
            array (
                'id' => 2,
                'type' => 'expense',
                'name' => 'Md Rafael',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 17:45:54',
                'updated_at' => '2025-04-15 17:45:54',
            ),
            202 => 
            array (
                'id' => 3,
                'type' => 'expense',
                'name' => 'Shaila Mitul',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 17:46:10',
                'updated_at' => '2025-04-15 17:46:10',
            ),
            203 => 
            array (
                'id' => 4,
                'type' => 'expense',
                'name' => 'Warehouse Rent',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 17:46:52',
                'updated_at' => '2025-04-15 17:46:52',
            ),
            204 => 
            array (
                'id' => 5,
                'type' => 'expense',
                'name' => 'Zakat',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 17:48:06',
                'updated_at' => '2025-04-15 17:48:06',
            ),
            205 => 
            array (
                'id' => 6,
                'type' => 'expense',
                'name' => 'Shashikob',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 17:48:43',
                'updated_at' => '2025-04-15 17:48:43',
            ),
            206 => 
            array (
                'id' => 7,
                'type' => 'expense',
                'name' => 'Warehouse Cost',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 17:49:01',
                'updated_at' => '2025-04-15 17:49:01',
            ),
            207 => 
            array (
                'id' => 8,
                'type' => 'expense',
            'name' => 'Bazar (Basa)',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 17:49:24',
                'updated_at' => '2025-04-15 17:49:24',
            ),
            208 => 
            array (
                'id' => 9,
                'type' => 'expense',
                'name' => 'car Maintenance',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 17:51:25',
                'updated_at' => '2025-04-15 17:51:25',
            ),
            209 => 
            array (
                'id' => 10,
                'type' => 'expense',
                'name' => 'Monthly Dues',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 17:53:59',
                'updated_at' => '2025-04-15 17:53:59',
            ),
            210 => 
            array (
                'id' => 11,
                'type' => 'expense',
                'name' => 'Newspaper Bill',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 17:54:15',
                'updated_at' => '2025-04-15 17:54:15',
            ),
            211 => 
            array (
                'id' => 12,
                'type' => 'expense',
                'name' => 'Tagada Cost',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 17:55:26',
                'updated_at' => '2025-04-15 17:55:26',
            ),
            212 => 
            array (
                'id' => 13,
                'type' => 'expense',
                'name' => 'Telephone Bill',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 17:55:51',
                'updated_at' => '2025-04-15 17:55:51',
            ),
            213 => 
            array (
                'id' => 14,
                'type' => 'expense',
                'name' => 'House Rent',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 17:56:08',
                'updated_at' => '2025-04-15 17:56:08',
            ),
            214 => 
            array (
                'id' => 15,
                'type' => 'expense',
                'name' => 'Bidyut Bill',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 17:56:21',
                'updated_at' => '2025-04-15 17:56:21',
            ),
            215 => 
            array (
                'id' => 16,
                'type' => 'expense',
            'name' => 'House Rent (Staff)',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 17:56:44',
                'updated_at' => '2025-04-15 17:56:44',
            ),
            216 => 
            array (
                'id' => 17,
                'type' => 'expense',
                'name' => 'Transport Cost',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 17:56:56',
                'updated_at' => '2025-04-15 17:56:56',
            ),
            217 => 
            array (
                'id' => 18,
                'type' => 'expense',
                'name' => 'Jolap',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 17:57:05',
                'updated_at' => '2025-04-15 17:57:05',
            ),
            218 => 
            array (
                'id' => 19,
                'type' => 'expense',
            'name' => 'Mobile card (Mama)',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 17:57:23',
                'updated_at' => '2025-04-15 17:57:23',
            ),
            219 => 
            array (
                'id' => 20,
                'type' => 'expense',
                'name' => 'Advertisement Cost',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 17:57:50',
                'updated_at' => '2025-04-15 17:57:50',
            ),
            220 => 
            array (
                'id' => 21,
                'type' => 'expense',
                'name' => 'miscellaneous expenses',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 17:58:12',
                'updated_at' => '2025-04-15 17:58:12',
            ),
            221 => 
            array (
                'id' => 22,
                'type' => 'expense',
                'name' => 'Daily Tiffin',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 17:58:29',
                'updated_at' => '2025-04-15 17:58:29',
            ),
            222 => 
            array (
                'id' => 23,
                'type' => 'expense',
                'name' => 'Party Expense',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 17:59:36',
                'updated_at' => '2025-04-15 17:59:36',
            ),
            223 => 
            array (
                'id' => 24,
                'type' => 'expense',
                'name' => 'Comilla Committee',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:02:49',
                'updated_at' => '2025-04-15 18:02:49',
            ),
            224 => 
            array (
                'id' => 25,
                'type' => 'expense',
            'name' => 'Electricity Bill (Basa)',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:03:18',
                'updated_at' => '2025-04-15 18:03:18',
            ),
            225 => 
            array (
                'id' => 26,
                'type' => 'expense',
            'name' => 'Taslim Mama (Loan)',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:06:12',
                'updated_at' => '2025-04-15 18:06:12',
            ),
            226 => 
            array (
                'id' => 27,
                'type' => 'expense',
            'name' => 'Shashikab (G)',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:07:51',
                'updated_at' => '2025-04-15 18:07:51',
            ),
            227 => 
            array (
                'id' => 28,
                'type' => 'expense',
                'name' => 'VAT Office',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:09:22',
                'updated_at' => '2025-04-15 18:09:22',
            ),
            228 => 
            array (
                'id' => 29,
                'type' => 'expense',
                'name' => 'Software Expense',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:10:26',
                'updated_at' => '2025-04-15 18:10:26',
            ),
            229 => 
            array (
                'id' => 30,
                'type' => 'expense',
                'name' => 'Flat Booking',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:10:47',
                'updated_at' => '2025-04-15 18:10:47',
            ),
            230 => 
            array (
                'id' => 31,
                'type' => 'expense',
                'name' => 'Iqbal Mama',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:11:37',
                'updated_at' => '2025-04-15 18:11:37',
            ),
            231 => 
            array (
                'id' => 32,
                'type' => 'expense',
            'name' => 'Key Ring (Gift)',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:12:23',
                'updated_at' => '2025-04-15 18:12:23',
            ),
            232 => 
            array (
                'id' => 33,
                'type' => 'expense',
            'name' => 'Sadrul (Marketing)',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:12:57',
                'updated_at' => '2025-04-15 18:12:57',
            ),
            233 => 
            array (
                'id' => 34,
                'type' => 'expense',
                'name' => 'Khulna Showroom',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:15:38',
                'updated_at' => '2025-04-15 18:15:38',
            ),
            234 => 
            array (
                'id' => 5,
                'type' => 'retail-store',
                'name' => 'অভিযাত্রী',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:26:41',
                'updated_at' => '2025-04-15 18:26:41',
            ),
            235 => 
            array (
                'id' => 6,
                'type' => 'retail-store',
                'name' => 'অভিযাত্রী',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:29:07',
                'updated_at' => '2025-04-15 18:29:07',
            ),
            236 => 
            array (
                'id' => 7,
                'type' => 'retail-store',
                'name' => 'অভিযাত্রী',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:29:40',
                'updated_at' => '2025-04-15 18:29:40',
            ),
            237 => 
            array (
                'id' => 8,
                'type' => 'retail-store',
                'name' => 'অভিযাত্রী',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:30:24',
                'updated_at' => '2025-04-15 18:30:24',
            ),
            238 => 
            array (
                'id' => 9,
                'type' => 'retail-store',
                'name' => 'অভিযাত্রী',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:31:11',
                'updated_at' => '2025-04-15 18:31:11',
            ),
            239 => 
            array (
                'id' => 10,
                'type' => 'retail-store',
                'name' => 'অভিযাত্রী',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:32:56',
                'updated_at' => '2025-04-15 18:32:56',
            ),
            240 => 
            array (
                'id' => 11,
                'type' => 'retail-store',
                'name' => 'অভিযাত্রী',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:33:08',
                'updated_at' => '2025-04-15 18:33:08',
            ),
            241 => 
            array (
                'id' => 12,
                'type' => 'retail-store',
                'name' => 'অভিযাত্রী',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:33:35',
                'updated_at' => '2025-04-15 18:33:35',
            ),
            242 => 
            array (
                'id' => 13,
                'type' => 'retail-store',
                'name' => 'অভিযাত্রী',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:34:18',
                'updated_at' => '2025-04-15 18:34:18',
            ),
            243 => 
            array (
                'id' => 14,
                'type' => 'retail-store',
                'name' => 'অভিযাত্রী',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:34:32',
                'updated_at' => '2025-04-15 18:34:32',
            ),
            244 => 
            array (
                'id' => 15,
                'type' => 'retail-store',
                'name' => 'অভিযাত্রী',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:34:46',
                'updated_at' => '2025-04-15 18:34:46',
            ),
            245 => 
            array (
                'id' => 16,
                'type' => 'retail-store',
                'name' => 'অভিযাত্রী',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:35:13',
                'updated_at' => '2025-04-15 18:35:13',
            ),
            246 => 
            array (
                'id' => 17,
                'type' => 'retail-store',
                'name' => 'অভিযাত্রী',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:35:33',
                'updated_at' => '2025-04-15 18:35:33',
            ),
            247 => 
            array (
                'id' => 18,
                'type' => 'retail-store',
                'name' => 'অভিযাত্রী',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:35:43',
                'updated_at' => '2025-04-15 18:35:43',
            ),
            248 => 
            array (
                'id' => 19,
                'type' => 'retail-store',
                'name' => 'অভিযাত্রী',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:35:56',
                'updated_at' => '2025-04-15 18:35:56',
            ),
            249 => 
            array (
                'id' => 20,
                'type' => 'retail-store',
                'name' => 'অভিযাত্রী',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:36:20',
                'updated_at' => '2025-04-15 18:36:20',
            ),
            250 => 
            array (
                'id' => 21,
                'type' => 'retail-store',
                'name' => 'অভিযাত্রী',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:36:45',
                'updated_at' => '2025-04-15 18:36:45',
            ),
            251 => 
            array (
                'id' => 22,
                'type' => 'retail-store',
                'name' => 'সুইটি সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:37:56',
                'updated_at' => '2025-04-15 18:37:56',
            ),
            252 => 
            array (
                'id' => 23,
                'type' => 'retail-store',
                'name' => 'ভাই ভাই সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:38:19',
                'updated_at' => '2025-04-15 18:38:19',
            ),
            253 => 
            array (
                'id' => 24,
                'type' => 'retail-store',
                'name' => 'সু হাউস',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:38:40',
                'updated_at' => '2025-04-15 18:38:40',
            ),
            254 => 
            array (
                'id' => 25,
                'type' => 'retail-store',
                'name' => 'রুনা সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:39:39',
                'updated_at' => '2025-04-15 18:39:39',
            ),
            255 => 
            array (
                'id' => 26,
                'type' => 'retail-store',
                'name' => 'সুবরনা সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:40:05',
                'updated_at' => '2025-04-15 18:40:05',
            ),
            256 => 
            array (
                'id' => 27,
                'type' => 'retail-store',
                'name' => 'ফেন্সি সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:40:38',
                'updated_at' => '2025-04-15 18:40:38',
            ),
            257 => 
            array (
                'id' => 28,
                'type' => 'retail-store',
                'name' => 'নরসিংদী',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:41:09',
                'updated_at' => '2025-04-15 18:41:09',
            ),
            258 => 
            array (
                'id' => 29,
                'type' => 'retail-store',
                'name' => 'কামড়ান সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:41:34',
                'updated_at' => '2025-04-15 18:41:34',
            ),
            259 => 
            array (
                'id' => 30,
                'type' => 'retail-store',
                'name' => 'গাফফার সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:41:54',
                'updated_at' => '2025-04-15 18:41:54',
            ),
            260 => 
            array (
                'id' => 31,
                'type' => 'retail-store',
                'name' => 'বিসমিল্লাহ সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:42:36',
                'updated_at' => '2025-04-15 18:42:36',
            ),
            261 => 
            array (
                'id' => 32,
                'type' => 'retail-store',
                'name' => 'মাহিন সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:42:55',
                'updated_at' => '2025-04-15 18:42:55',
            ),
            262 => 
            array (
                'id' => 33,
                'type' => 'retail-store',
                'name' => 'মাইমুন সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:43:13',
                'updated_at' => '2025-04-15 18:43:13',
            ),
            263 => 
            array (
                'id' => 34,
                'type' => 'retail-store',
                'name' => 'শরীফ সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:43:57',
                'updated_at' => '2025-04-15 18:43:57',
            ),
            264 => 
            array (
                'id' => 35,
                'type' => 'retail-store',
                'name' => 'রকমারি',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:55:00',
                'updated_at' => '2025-04-15 18:55:00',
            ),
            265 => 
            array (
                'id' => 36,
                'type' => 'retail-store',
                'name' => 'শামিম সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:55:22',
                'updated_at' => '2025-04-15 18:55:22',
            ),
            266 => 
            array (
                'id' => 37,
                'type' => 'retail-store',
                'name' => 'জামাল সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:55:47',
                'updated_at' => '2025-04-15 18:55:47',
            ),
            267 => 
            array (
                'id' => 38,
                'type' => 'retail-store',
                'name' => 'লিটন সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:56:14',
                'updated_at' => '2025-04-15 18:56:14',
            ),
            268 => 
            array (
                'id' => 39,
                'type' => 'retail-store',
                'name' => 'সুমাইয়া সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:56:30',
                'updated_at' => '2025-04-15 18:56:30',
            ),
            269 => 
            array (
                'id' => 40,
                'type' => 'retail-store',
                'name' => 'চয়েজ সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:57:18',
                'updated_at' => '2025-04-15 18:57:18',
            ),
            270 => 
            array (
                'id' => 41,
                'type' => 'retail-store',
                'name' => 'ফ্যাশন সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:57:43',
                'updated_at' => '2025-04-15 18:57:43',
            ),
            271 => 
            array (
                'id' => 42,
                'type' => 'retail-store',
                'name' => 'ইমরান সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:58:15',
                'updated_at' => '2025-04-15 18:58:15',
            ),
            272 => 
            array (
                'id' => 43,
                'type' => 'retail-store',
                'name' => 'ফ্যাশন সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:58:47',
                'updated_at' => '2025-04-15 18:58:47',
            ),
            273 => 
            array (
                'id' => 44,
                'type' => 'retail-store',
                'name' => 'মেট্রো সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:59:06',
                'updated_at' => '2025-04-15 18:59:06',
            ),
            274 => 
            array (
                'id' => 45,
                'type' => 'retail-store',
                'name' => 'রিপন সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 18:59:26',
                'updated_at' => '2025-04-15 18:59:26',
            ),
            275 => 
            array (
                'id' => 46,
                'type' => 'retail-store',
                'name' => 'ছাহেরা মটর',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:00:25',
                'updated_at' => '2025-04-15 19:00:25',
            ),
            276 => 
            array (
                'id' => 47,
                'type' => 'retail-store',
                'name' => 'জাহা সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:00:49',
                'updated_at' => '2025-04-15 19:00:49',
            ),
            277 => 
            array (
                'id' => 48,
                'type' => 'retail-store',
                'name' => 'ইতি সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:01:11',
                'updated_at' => '2025-04-15 19:01:11',
            ),
            278 => 
            array (
                'id' => 49,
                'type' => 'retail-store',
                'name' => 'মেহেদি সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:01:26',
                'updated_at' => '2025-04-15 19:01:26',
            ),
            279 => 
            array (
                'id' => 50,
                'type' => 'retail-store',
                'name' => 'ফাতেমা সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:01:43',
                'updated_at' => '2025-04-15 19:01:43',
            ),
            280 => 
            array (
                'id' => 51,
                'type' => 'retail-store',
                'name' => 'আলম সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:02:02',
                'updated_at' => '2025-04-15 19:02:02',
            ),
            281 => 
            array (
                'id' => 52,
                'type' => 'retail-store',
                'name' => 'শাহিন সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:02:20',
                'updated_at' => '2025-04-15 19:02:20',
            ),
            282 => 
            array (
                'id' => 53,
                'type' => 'retail-store',
                'name' => 'আলিফ সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:03:09',
                'updated_at' => '2025-04-15 19:03:09',
            ),
            283 => 
            array (
                'id' => 54,
                'type' => 'retail-store',
                'name' => 'ফিমা সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:03:57',
                'updated_at' => '2025-04-15 19:03:57',
            ),
            284 => 
            array (
                'id' => 55,
                'type' => 'retail-store',
                'name' => 'এন কে দে সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:04:30',
                'updated_at' => '2025-04-15 19:04:30',
            ),
            285 => 
            array (
                'id' => 56,
                'type' => 'retail-store',
                'name' => 'ভাই ভাই সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:05:06',
                'updated_at' => '2025-04-15 19:05:06',
            ),
            286 => 
            array (
                'id' => 57,
                'type' => 'retail-store',
                'name' => 'সুশীল সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:05:39',
                'updated_at' => '2025-04-15 19:05:39',
            ),
            287 => 
            array (
                'id' => 58,
                'type' => 'retail-store',
                'name' => 'অনন্যা সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:06:21',
                'updated_at' => '2025-04-15 19:06:21',
            ),
            288 => 
            array (
                'id' => 59,
                'type' => 'retail-store',
                'name' => 'শাওন সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:06:47',
                'updated_at' => '2025-04-15 19:06:47',
            ),
            289 => 
            array (
                'id' => 60,
                'type' => 'retail-store',
                'name' => 'দেলোয়ার সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:16:07',
                'updated_at' => '2025-04-15 19:16:07',
            ),
            290 => 
            array (
                'id' => 61,
                'type' => 'retail-store',
                'name' => 'মনির সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:16:19',
                'updated_at' => '2025-04-15 19:16:19',
            ),
            291 => 
            array (
                'id' => 62,
                'type' => 'retail-store',
                'name' => 'লাকি সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:16:47',
                'updated_at' => '2025-04-15 19:16:47',
            ),
            292 => 
            array (
                'id' => 63,
                'type' => 'retail-store',
                'name' => 'খাজা বুট হাউস',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:17:14',
                'updated_at' => '2025-04-15 19:17:14',
            ),
            293 => 
            array (
                'id' => 64,
                'type' => 'retail-store',
                'name' => 'নিউ গ্ল্যামার সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:18:00',
                'updated_at' => '2025-04-15 19:18:00',
            ),
            294 => 
            array (
                'id' => 65,
                'type' => 'retail-store',
                'name' => 'গ্রামীণ সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:18:37',
                'updated_at' => '2025-04-15 19:18:37',
            ),
            295 => 
            array (
                'id' => 66,
                'type' => 'retail-store',
                'name' => 'তাজ সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:19:10',
                'updated_at' => '2025-04-15 19:19:10',
            ),
            296 => 
            array (
                'id' => 67,
                'type' => 'retail-store',
                'name' => 'গ্রামীণ সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:19:33',
                'updated_at' => '2025-04-15 19:19:33',
            ),
            297 => 
            array (
                'id' => 68,
                'type' => 'retail-store',
                'name' => 'জলিল সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:19:49',
                'updated_at' => '2025-04-15 19:19:49',
            ),
            298 => 
            array (
                'id' => 69,
                'type' => 'retail-store',
                'name' => 'আলিম সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:20:06',
                'updated_at' => '2025-04-15 19:20:06',
            ),
            299 => 
            array (
                'id' => 70,
                'type' => 'retail-store',
                'name' => 'নাহার সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:20:27',
                'updated_at' => '2025-04-15 19:20:27',
            ),
            300 => 
            array (
                'id' => 71,
                'type' => 'retail-store',
                'name' => 'গ্রামীণ সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:20:49',
                'updated_at' => '2025-04-15 19:20:49',
            ),
            301 => 
            array (
                'id' => 72,
                'type' => 'retail-store',
                'name' => 'লিপি সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:21:08',
                'updated_at' => '2025-04-15 19:21:08',
            ),
            302 => 
            array (
                'id' => 73,
                'type' => 'retail-store',
                'name' => 'সানি সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:21:27',
                'updated_at' => '2025-04-15 19:21:27',
            ),
            303 => 
            array (
                'id' => 74,
                'type' => 'retail-store',
                'name' => 'কাদির সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:21:47',
                'updated_at' => '2025-04-15 19:21:47',
            ),
            304 => 
            array (
                'id' => 75,
                'type' => 'retail-store',
                'name' => 'নিতু সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:22:04',
                'updated_at' => '2025-04-15 19:22:04',
            ),
            305 => 
            array (
                'id' => 76,
                'type' => 'retail-store',
                'name' => 'নিপু সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:22:24',
                'updated_at' => '2025-04-15 19:22:24',
            ),
            306 => 
            array (
                'id' => 77,
                'type' => 'retail-store',
                'name' => 'তন্নি সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:22:44',
                'updated_at' => '2025-04-15 19:22:44',
            ),
            307 => 
            array (
                'id' => 78,
                'type' => 'retail-store',
                'name' => 'সজিব সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:23:13',
                'updated_at' => '2025-04-15 19:23:13',
            ),
            308 => 
            array (
                'id' => 79,
                'type' => 'retail-store',
                'name' => 'হিমন সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:23:27',
                'updated_at' => '2025-04-15 19:23:27',
            ),
            309 => 
            array (
                'id' => 80,
                'type' => 'retail-store',
                'name' => 'আদর্শ সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:23:46',
                'updated_at' => '2025-04-15 19:23:46',
            ),
            310 => 
            array (
                'id' => 81,
                'type' => 'retail-store',
                'name' => 'আলম সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:23:58',
                'updated_at' => '2025-04-15 19:23:58',
            ),
            311 => 
            array (
                'id' => 82,
                'type' => 'retail-store',
                'name' => 'ফ্যাশন সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:24:21',
                'updated_at' => '2025-04-15 19:24:21',
            ),
            312 => 
            array (
                'id' => 83,
                'type' => 'retail-store',
                'name' => 'আসফি সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:24:50',
                'updated_at' => '2025-04-15 19:24:50',
            ),
            313 => 
            array (
                'id' => 84,
                'type' => 'retail-store',
                'name' => 'হামিদা সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:25:07',
                'updated_at' => '2025-04-15 19:25:07',
            ),
            314 => 
            array (
                'id' => 85,
                'type' => 'retail-store',
                'name' => 'ব্রাদার্স সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:25:31',
                'updated_at' => '2025-04-15 19:25:31',
            ),
            315 => 
            array (
                'id' => 86,
                'type' => 'retail-store',
                'name' => 'সাইফুদ্দিন সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:25:59',
                'updated_at' => '2025-04-15 19:25:59',
            ),
            316 => 
            array (
                'id' => 87,
                'type' => 'retail-store',
                'name' => 'ভাই ভাই সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:26:16',
                'updated_at' => '2025-04-15 19:26:16',
            ),
            317 => 
            array (
                'id' => 88,
                'type' => 'retail-store',
                'name' => 'রাজু সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:26:34',
                'updated_at' => '2025-04-15 19:26:34',
            ),
            318 => 
            array (
                'id' => 89,
                'type' => 'retail-store',
                'name' => 'রংধনু সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:27:09',
                'updated_at' => '2025-04-15 19:27:09',
            ),
            319 => 
            array (
                'id' => 90,
                'type' => 'retail-store',
                'name' => 'চুমকি সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:27:39',
                'updated_at' => '2025-04-15 19:27:39',
            ),
            320 => 
            array (
                'id' => 91,
                'type' => 'retail-store',
                'name' => 'খোকন রোকন সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:28:08',
                'updated_at' => '2025-04-15 19:28:08',
            ),
            321 => 
            array (
                'id' => 92,
                'type' => 'retail-store',
                'name' => 'আপনজন সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:28:28',
                'updated_at' => '2025-04-15 19:28:28',
            ),
            322 => 
            array (
                'id' => 93,
                'type' => 'retail-store',
                'name' => 'বিসমিল্লাহ সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:28:46',
                'updated_at' => '2025-04-15 19:28:46',
            ),
            323 => 
            array (
                'id' => 94,
                'type' => 'retail-store',
                'name' => 'এপেক্স সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:29:28',
                'updated_at' => '2025-04-15 19:29:28',
            ),
            324 => 
            array (
                'id' => 95,
                'type' => 'retail-store',
                'name' => 'জুবায়ের সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:29:52',
                'updated_at' => '2025-04-15 19:29:52',
            ),
            325 => 
            array (
                'id' => 96,
                'type' => 'retail-store',
                'name' => 'কাজি সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:30:12',
                'updated_at' => '2025-04-15 19:30:12',
            ),
            326 => 
            array (
                'id' => 97,
                'type' => 'retail-store',
                'name' => 'শাকিল সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:30:36',
                'updated_at' => '2025-04-15 19:30:36',
            ),
            327 => 
            array (
                'id' => 98,
                'type' => 'retail-store',
                'name' => 'সু গ্যালারী',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:31:01',
                'updated_at' => '2025-04-15 19:31:01',
            ),
            328 => 
            array (
                'id' => 99,
                'type' => 'retail-store',
                'name' => 'আফজাল সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:31:18',
                'updated_at' => '2025-04-15 19:31:18',
            ),
            329 => 
            array (
                'id' => 100,
                'type' => 'retail-store',
                'name' => 'আফরিন সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:31:39',
                'updated_at' => '2025-04-15 19:31:39',
            ),
            330 => 
            array (
                'id' => 101,
                'type' => 'retail-store',
                'name' => 'রাজু সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:32:07',
                'updated_at' => '2025-04-15 19:32:07',
            ),
            331 => 
            array (
                'id' => 102,
                'type' => 'retail-store',
                'name' => 'খোকন সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:32:37',
                'updated_at' => '2025-04-15 19:32:37',
            ),
            332 => 
            array (
                'id' => 103,
                'type' => 'retail-store',
                'name' => 'খন্দকার সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:32:59',
                'updated_at' => '2025-04-15 19:32:59',
            ),
            333 => 
            array (
                'id' => 104,
                'type' => 'retail-store',
                'name' => 'ভাই ভাই সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:33:21',
                'updated_at' => '2025-04-15 19:33:21',
            ),
            334 => 
            array (
                'id' => 105,
                'type' => 'retail-store',
                'name' => 'ভাই ভাই সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:50:16',
                'updated_at' => '2025-04-15 19:50:16',
            ),
            335 => 
            array (
                'id' => 106,
                'type' => 'retail-store',
                'name' => 'সুজন সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:50:37',
                'updated_at' => '2025-04-15 19:50:37',
            ),
            336 => 
            array (
                'id' => 107,
                'type' => 'retail-store',
                'name' => 'অভিযাত্রী',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:51:01',
                'updated_at' => '2025-04-15 19:51:01',
            ),
            337 => 
            array (
                'id' => 108,
                'type' => 'retail-store',
                'name' => 'হিমন সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:51:20',
                'updated_at' => '2025-04-15 19:51:20',
            ),
            338 => 
            array (
                'id' => 109,
                'type' => 'retail-store',
                'name' => 'কবির সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:51:49',
                'updated_at' => '2025-04-15 19:51:49',
            ),
            339 => 
            array (
                'id' => 110,
                'type' => 'retail-store',
                'name' => 'বিসমিল্লাহ সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:52:42',
                'updated_at' => '2025-04-15 19:52:42',
            ),
            340 => 
            array (
                'id' => 111,
                'type' => 'retail-store',
                'name' => 'আল আরাফ সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:53:10',
                'updated_at' => '2025-04-15 19:53:10',
            ),
            341 => 
            array (
                'id' => 112,
                'type' => 'retail-store',
                'name' => 'বেলাল সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:56:40',
                'updated_at' => '2025-04-15 19:56:40',
            ),
            342 => 
            array (
                'id' => 113,
                'type' => 'retail-store',
                'name' => 'জয় গোপাল',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:57:25',
                'updated_at' => '2025-04-15 19:57:25',
            ),
            343 => 
            array (
                'id' => 114,
                'type' => 'retail-store',
                'name' => 'মিজান সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:59:10',
                'updated_at' => '2025-04-15 19:59:10',
            ),
            344 => 
            array (
                'id' => 115,
                'type' => 'retail-store',
                'name' => 'পায়ের সাজ সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 19:59:31',
                'updated_at' => '2025-04-15 19:59:31',
            ),
            345 => 
            array (
                'id' => 116,
                'type' => 'retail-store',
                'name' => 'সপ্না সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:00:08',
                'updated_at' => '2025-04-15 20:00:08',
            ),
            346 => 
            array (
                'id' => 117,
                'type' => 'retail-store',
                'name' => 'সু গ্যালারি',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:00:36',
                'updated_at' => '2025-04-15 20:00:36',
            ),
            347 => 
            array (
                'id' => 118,
                'type' => 'retail-store',
                'name' => 'আল-আমিন সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:01:08',
                'updated_at' => '2025-04-15 20:01:08',
            ),
            348 => 
            array (
                'id' => 119,
                'type' => 'retail-store',
                'name' => 'কে এম সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:01:40',
                'updated_at' => '2025-04-15 20:01:40',
            ),
            349 => 
            array (
                'id' => 120,
                'type' => 'retail-store',
                'name' => 'মোসতাক সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:02:31',
                'updated_at' => '2025-04-15 20:02:31',
            ),
            350 => 
            array (
                'id' => 121,
                'type' => 'retail-store',
                'name' => 'বাচ্চু সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:02:58',
                'updated_at' => '2025-04-15 20:02:58',
            ),
            351 => 
            array (
                'id' => 122,
                'type' => 'retail-store',
                'name' => 'এম আর সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:03:20',
                'updated_at' => '2025-04-15 20:03:20',
            ),
            352 => 
            array (
                'id' => 123,
                'type' => 'retail-store',
                'name' => 'তারেক সামিয়া সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:03:54',
                'updated_at' => '2025-04-15 20:03:54',
            ),
            353 => 
            array (
                'id' => 124,
                'type' => 'retail-store',
                'name' => 'বিথি সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:04:38',
                'updated_at' => '2025-04-15 20:04:38',
            ),
            354 => 
            array (
                'id' => 125,
                'type' => 'retail-store',
                'name' => 'সানি সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:04:52',
                'updated_at' => '2025-04-15 20:04:52',
            ),
            355 => 
            array (
                'id' => 126,
                'type' => 'retail-store',
                'name' => 'আশা সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:05:17',
                'updated_at' => '2025-04-15 20:05:17',
            ),
            356 => 
            array (
                'id' => 127,
                'type' => 'retail-store',
                'name' => 'এস বার্মিস সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:06:03',
                'updated_at' => '2025-04-15 20:06:03',
            ),
            357 => 
            array (
                'id' => 128,
                'type' => 'retail-store',
                'name' => 'করিম সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:06:25',
                'updated_at' => '2025-04-15 20:06:25',
            ),
            358 => 
            array (
                'id' => 129,
                'type' => 'retail-store',
                'name' => 'প্রমান সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:06:57',
                'updated_at' => '2025-04-15 20:06:57',
            ),
            359 => 
            array (
                'id' => 130,
                'type' => 'retail-store',
                'name' => 'জেরিন সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:07:29',
                'updated_at' => '2025-04-15 20:07:29',
            ),
            360 => 
            array (
                'id' => 131,
                'type' => 'retail-store',
                'name' => 'আলিফ সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:08:07',
                'updated_at' => '2025-04-15 20:08:07',
            ),
            361 => 
            array (
                'id' => 132,
                'type' => 'retail-store',
                'name' => 'তন্নি সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:08:25',
                'updated_at' => '2025-04-15 20:08:25',
            ),
            362 => 
            array (
                'id' => 133,
                'type' => 'retail-store',
                'name' => 'মডার্ন সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:08:50',
                'updated_at' => '2025-04-15 20:08:50',
            ),
            363 => 
            array (
                'id' => 134,
                'type' => 'retail-store',
                'name' => 'সুমন সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:09:16',
                'updated_at' => '2025-04-15 20:09:16',
            ),
            364 => 
            array (
                'id' => 135,
                'type' => 'retail-store',
                'name' => 'সুমন সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:09:48',
                'updated_at' => '2025-04-15 20:09:48',
            ),
            365 => 
            array (
                'id' => 136,
                'type' => 'retail-store',
                'name' => 'মিডলি সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:11:07',
                'updated_at' => '2025-04-15 20:11:07',
            ),
            366 => 
            array (
                'id' => 137,
                'type' => 'retail-store',
                'name' => 'তাসফিয়া সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:11:55',
                'updated_at' => '2025-04-15 20:11:55',
            ),
            367 => 
            array (
                'id' => 138,
                'type' => 'retail-store',
                'name' => 'ফাহিমা সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:12:30',
                'updated_at' => '2025-04-15 20:12:30',
            ),
            368 => 
            array (
                'id' => 139,
                'type' => 'retail-store',
                'name' => 'জননী সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:13:04',
                'updated_at' => '2025-04-15 20:13:04',
            ),
            369 => 
            array (
                'id' => 140,
                'type' => 'retail-store',
                'name' => 'মিডলি',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:13:22',
                'updated_at' => '2025-04-15 20:13:22',
            ),
            370 => 
            array (
                'id' => 141,
                'type' => 'retail-store',
                'name' => 'অভিযাত্রী',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:13:44',
                'updated_at' => '2025-04-15 20:13:44',
            ),
            371 => 
            array (
                'id' => 142,
                'type' => 'retail-store',
                'name' => 'রাফা সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:13:55',
                'updated_at' => '2025-04-15 20:13:55',
            ),
            372 => 
            array (
                'id' => 143,
                'type' => 'retail-store',
                'name' => 'আলপনা সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:14:17',
                'updated_at' => '2025-04-15 20:14:17',
            ),
            373 => 
            array (
                'id' => 144,
                'type' => 'retail-store',
                'name' => 'সারা সিফা সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:14:49',
                'updated_at' => '2025-04-15 20:14:49',
            ),
            374 => 
            array (
                'id' => 145,
                'type' => 'retail-store',
                'name' => 'জাহিদ সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:15:06',
                'updated_at' => '2025-04-15 20:15:06',
            ),
            375 => 
            array (
                'id' => 146,
                'type' => 'retail-store',
                'name' => 'আজাদ সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:15:53',
                'updated_at' => '2025-04-15 20:15:53',
            ),
            376 => 
            array (
                'id' => 147,
                'type' => 'retail-store',
                'name' => 'মিডলি সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:16:14',
                'updated_at' => '2025-04-15 20:16:14',
            ),
            377 => 
            array (
                'id' => 148,
                'type' => 'retail-store',
                'name' => 'সাওম সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:16:32',
                'updated_at' => '2025-04-15 20:16:32',
            ),
            378 => 
            array (
                'id' => 149,
                'type' => 'retail-store',
                'name' => 'ডিজিটাল সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:16:53',
                'updated_at' => '2025-04-15 20:16:53',
            ),
            379 => 
            array (
                'id' => 150,
                'type' => 'retail-store',
                'name' => 'রবিউল সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:17:11',
                'updated_at' => '2025-04-15 20:17:11',
            ),
            380 => 
            array (
                'id' => 151,
                'type' => 'retail-store',
                'name' => 'অভিযাত্রী',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:17:29',
                'updated_at' => '2025-04-15 20:17:29',
            ),
            381 => 
            array (
                'id' => 152,
                'type' => 'retail-store',
                'name' => 'আরিয়ান সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:20:06',
                'updated_at' => '2025-04-15 20:20:06',
            ),
            382 => 
            array (
                'id' => 153,
                'type' => 'retail-store',
                'name' => 'মায়ের দোয়া সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:20:32',
                'updated_at' => '2025-04-15 20:20:32',
            ),
            383 => 
            array (
                'id' => 154,
                'type' => 'retail-store',
                'name' => 'মৌসুমি সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:21:19',
                'updated_at' => '2025-04-15 20:21:19',
            ),
            384 => 
            array (
                'id' => 155,
                'type' => 'retail-store',
                'name' => 'আল-আমিন সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:21:45',
                'updated_at' => '2025-04-15 20:21:45',
            ),
            385 => 
            array (
                'id' => 156,
                'type' => 'retail-store',
                'name' => 'তমাল সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-15 20:22:05',
                'updated_at' => '2025-04-15 20:22:05',
            ),
            386 => 
            array (
                'id' => 18,
                'type' => 'employee',
                'name' => 'Md Mahfuz',
                'deleted_at' => NULL,
                'created_at' => '2025-04-16 01:11:03',
                'updated_at' => '2025-04-16 01:11:03',
            ),
            387 => 
            array (
                'id' => 7,
                'type' => 'bank-account',
                'name' => 'Sonali Bank',
                'deleted_at' => NULL,
                'created_at' => '2025-04-16 01:27:13',
                'updated_at' => '2025-04-16 01:27:13',
            ),
            388 => 
            array (
                'id' => 8,
                'type' => 'bank-account',
                'name' => 'Rupali Bank',
                'deleted_at' => NULL,
                'created_at' => '2025-04-16 01:33:22',
                'updated_at' => '2025-04-16 01:33:22',
            ),
            389 => 
            array (
                'id' => 9,
                'type' => 'bank-account',
                'name' => 'Brac Bank',
                'deleted_at' => NULL,
                'created_at' => '2025-04-16 01:35:01',
                'updated_at' => '2025-04-16 01:35:01',
            ),
            390 => 
            array (
                'id' => 10,
                'type' => 'bank-account',
                'name' => 'Agrani Bank',
                'deleted_at' => NULL,
                'created_at' => '2025-04-16 01:35:54',
                'updated_at' => '2025-04-16 01:35:54',
            ),
            391 => 
            array (
                'id' => 35,
                'type' => 'expense',
                'name' => 'Internet Bill',
                'deleted_at' => NULL,
                'created_at' => '2025-04-16 02:09:37',
                'updated_at' => '2025-04-16 02:09:37',
            ),
            392 => 
            array (
                'id' => 1,
                'type' => 'gift-supplier',
                'name' => 'আমিন চাবির রিং',
                'deleted_at' => NULL,
                'created_at' => '2025-04-29 22:59:25',
                'updated_at' => '2025-04-29 22:59:25',
            ),
            393 => 
            array (
                'id' => 2,
                'type' => 'gift-supplier',
                'name' => 'রহিম গেঞ্জি',
                'deleted_at' => NULL,
                'created_at' => '2025-04-29 22:59:53',
                'updated_at' => '2025-04-29 22:59:53',
            ),
            394 => 
            array (
                'id' => 3,
                'type' => 'gift-supplier',
                'name' => 'কাউসার সাইনবোর্ড',
                'deleted_at' => NULL,
                'created_at' => '2025-04-29 23:01:29',
                'updated_at' => '2025-04-29 23:01:29',
            ),
            395 => 
            array (
                'id' => 4,
                'type' => 'gift-supplier',
                'name' => 'কালাম প্রেস',
                'deleted_at' => NULL,
                'created_at' => '2025-04-29 23:01:59',
                'updated_at' => '2025-04-29 23:01:59',
            ),
            396 => 
            array (
                'id' => 5,
                'type' => 'gift-supplier',
                'name' => 'বাদশাহ ব্যাগ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-29 23:02:32',
                'updated_at' => '2025-04-29 23:02:32',
            ),
            397 => 
            array (
                'id' => 6,
                'type' => 'gift-supplier',
                'name' => 'সালাউদ্দিন বক্স',
                'deleted_at' => NULL,
                'created_at' => '2025-04-29 23:03:34',
                'updated_at' => '2025-04-29 23:03:34',
            ),
            398 => 
            array (
                'id' => 7,
                'type' => 'gift-supplier',
                'name' => 'ব্রাদার্স বক্স',
                'deleted_at' => NULL,
                'created_at' => '2025-04-29 23:04:05',
                'updated_at' => '2025-04-29 23:04:05',
            ),
            399 => 
            array (
                'id' => 8,
                'type' => 'gift-supplier',
                'name' => 'শাহেদ বক্স',
                'deleted_at' => NULL,
                'created_at' => '2025-04-29 23:04:39',
                'updated_at' => '2025-04-29 23:04:39',
            ),
            400 => 
            array (
                'id' => 157,
                'type' => 'retail-store',
                'name' => 'রাজন সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-30 01:19:30',
                'updated_at' => '2025-04-30 01:19:30',
            ),
            401 => 
            array (
                'id' => 158,
                'type' => 'retail-store',
                'name' => 'তামান্না সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-30 02:23:25',
                'updated_at' => '2025-04-30 02:23:25',
            ),
            402 => 
            array (
                'id' => 159,
                'type' => 'retail-store',
                'name' => 'নিউ ভাই ভাই সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-04-30 02:25:10',
                'updated_at' => '2025-04-30 02:25:10',
            ),
            403 => 
            array (
                'id' => 171,
                'type' => 'factory',
                'name' => 'Test',
                'deleted_at' => NULL,
                'created_at' => '2025-05-02 23:48:27',
                'updated_at' => '2025-05-02 23:48:27',
            ),
            404 => 
            array (
                'id' => 19,
                'type' => 'employee',
                'name' => 'Md. Sadrul',
                'deleted_at' => NULL,
                'created_at' => '2025-05-03 00:02:25',
                'updated_at' => '2025-05-03 00:02:25',
            ),
            405 => 
            array (
                'id' => 172,
                'type' => 'factory',
                'name' => 'মোঃ হালিম',
                'deleted_at' => NULL,
                'created_at' => '2025-05-03 11:29:32',
                'updated_at' => '2025-05-03 11:29:32',
            ),
            406 => 
            array (
                'id' => 173,
                'type' => 'factory',
                'name' => 'মোঃ রবীন',
                'deleted_at' => NULL,
                'created_at' => '2025-05-03 18:13:43',
                'updated_at' => '2025-05-03 18:13:43',
            ),
            407 => 
            array (
                'id' => 174,
                'type' => 'factory',
                'name' => 'মোঃ রিমন',
                'deleted_at' => NULL,
                'created_at' => '2025-05-03 18:18:04',
                'updated_at' => '2025-05-03 18:18:04',
            ),
            408 => 
            array (
                'id' => 175,
                'type' => 'factory',
                'name' => 'মোঃ ছাবিদ',
                'deleted_at' => NULL,
                'created_at' => '2025-05-03 19:02:47',
                'updated_at' => '2025-05-03 19:02:47',
            ),
            409 => 
            array (
                'id' => 176,
                'type' => 'factory',
                'name' => 'মোঃ আকাশ',
                'deleted_at' => NULL,
                'created_at' => '2025-05-03 19:12:12',
                'updated_at' => '2025-05-03 19:12:12',
            ),
            410 => 
            array (
                'id' => 177,
                'type' => 'factory',
                'name' => 'মোঃ ইয়াছিন',
                'deleted_at' => NULL,
                'created_at' => '2025-05-03 19:20:10',
                'updated_at' => '2025-05-03 19:20:10',
            ),
            411 => 
            array (
                'id' => 178,
                'type' => 'factory',
                'name' => 'মোঃ সারোয়ার',
                'deleted_at' => NULL,
                'created_at' => '2025-05-03 19:31:34',
                'updated_at' => '2025-05-03 19:31:34',
            ),
            412 => 
            array (
                'id' => 179,
                'type' => 'factory',
                'name' => 'মোঃ উসমান',
                'deleted_at' => NULL,
                'created_at' => '2025-05-03 20:00:20',
                'updated_at' => '2025-05-03 20:00:20',
            ),
            413 => 
            array (
                'id' => 180,
                'type' => 'factory',
                'name' => 'মোঃ রবিন',
                'deleted_at' => NULL,
                'created_at' => '2025-05-04 11:00:19',
                'updated_at' => '2025-05-04 11:00:19',
            ),
            414 => 
            array (
                'id' => 181,
                'type' => 'factory',
                'name' => 'মোঃ মাহমুদ',
                'deleted_at' => NULL,
                'created_at' => '2025-05-04 12:49:13',
                'updated_at' => '2025-05-04 12:49:13',
            ),
            415 => 
            array (
                'id' => 182,
                'type' => 'factory',
                'name' => 'মোঃ সাহাজউদ্দিন',
                'deleted_at' => NULL,
                'created_at' => '2025-05-04 13:02:34',
                'updated_at' => '2025-05-04 13:02:34',
            ),
            416 => 
            array (
                'id' => 183,
                'type' => 'factory',
                'name' => 'মোঃ আমির হোসেন',
                'deleted_at' => NULL,
                'created_at' => '2025-05-04 13:06:20',
                'updated_at' => '2025-05-04 13:06:20',
            ),
            417 => 
            array (
                'id' => 184,
                'type' => 'factory',
                'name' => 'মোঃ সুজন',
                'deleted_at' => NULL,
                'created_at' => '2025-05-04 13:20:15',
                'updated_at' => '2025-05-04 13:20:15',
            ),
            418 => 
            array (
                'id' => 185,
                'type' => 'factory',
                'name' => 'মোঃ নুরুজ্জামান',
                'deleted_at' => NULL,
                'created_at' => '2025-05-04 15:51:42',
                'updated_at' => '2025-05-04 15:51:42',
            ),
            419 => 
            array (
                'id' => 186,
                'type' => 'factory',
                'name' => 'মোঃ আনোয়ার',
                'deleted_at' => NULL,
                'created_at' => '2025-05-04 15:55:40',
                'updated_at' => '2025-05-04 15:55:40',
            ),
            420 => 
            array (
                'id' => 187,
                'type' => 'factory',
                'name' => 'মোঃ মোখলেস',
                'deleted_at' => NULL,
                'created_at' => '2025-05-04 16:14:26',
                'updated_at' => '2025-05-04 16:14:26',
            ),
            421 => 
            array (
                'id' => 188,
                'type' => 'factory',
                'name' => 'মোঃ ওমর মিয়া',
                'deleted_at' => NULL,
                'created_at' => '2025-05-04 16:16:39',
                'updated_at' => '2025-05-04 16:16:39',
            ),
            422 => 
            array (
                'id' => 189,
                'type' => 'factory',
                'name' => 'টাইগার স্যান্ডেল ফ্যাক্টরী',
                'deleted_at' => NULL,
                'created_at' => '2025-05-04 16:25:43',
                'updated_at' => '2025-05-04 16:25:43',
            ),
            423 => 
            array (
                'id' => 190,
                'type' => 'factory',
                'name' => 'মোঃ আলমগীর',
                'deleted_at' => NULL,
                'created_at' => '2025-05-04 16:29:53',
                'updated_at' => '2025-05-04 16:29:53',
            ),
            424 => 
            array (
                'id' => 191,
                'type' => 'factory',
                'name' => 'মোঃ জাবেদ মোল্লা',
                'deleted_at' => NULL,
                'created_at' => '2025-05-04 16:40:59',
                'updated_at' => '2025-05-04 16:40:59',
            ),
            425 => 
            array (
                'id' => 192,
                'type' => 'factory',
                'name' => 'মোঃ মাইনউদ্দিন',
                'deleted_at' => NULL,
                'created_at' => '2025-05-04 16:47:42',
                'updated_at' => '2025-05-04 16:47:42',
            ),
            426 => 
            array (
                'id' => 193,
                'type' => 'factory',
                'name' => 'মোঃ ইলিয়াছ',
                'deleted_at' => NULL,
                'created_at' => '2025-05-04 16:51:34',
                'updated_at' => '2025-05-04 16:51:34',
            ),
            427 => 
            array (
                'id' => 194,
                'type' => 'factory',
                'name' => 'মোঃ আক্তার',
                'deleted_at' => NULL,
                'created_at' => '2025-05-04 16:57:46',
                'updated_at' => '2025-05-04 16:57:46',
            ),
            428 => 
            array (
                'id' => 195,
                'type' => 'factory',
                'name' => 'নিশান ফ্যাক্টরী',
                'deleted_at' => NULL,
                'created_at' => '2025-05-04 17:01:43',
                'updated_at' => '2025-05-04 17:01:43',
            ),
            429 => 
            array (
                'id' => 196,
                'type' => 'factory',
                'name' => 'মোঃ জুয়েল',
                'deleted_at' => NULL,
                'created_at' => '2025-05-04 17:08:16',
                'updated_at' => '2025-05-04 17:08:16',
            ),
            430 => 
            array (
                'id' => 197,
                'type' => 'factory',
                'name' => 'মোঃ জুলহাস',
                'deleted_at' => NULL,
                'created_at' => '2025-05-04 17:19:10',
                'updated_at' => '2025-05-04 17:19:10',
            ),
            431 => 
            array (
                'id' => 198,
                'type' => 'factory',
                'name' => 'মোঃ কামরুল',
                'deleted_at' => NULL,
                'created_at' => '2025-05-04 19:32:21',
                'updated_at' => '2025-05-04 19:32:21',
            ),
            432 => 
            array (
                'id' => 199,
                'type' => 'factory',
                'name' => 'মোঃ শাহাদাত',
                'deleted_at' => NULL,
                'created_at' => '2025-05-04 20:36:49',
                'updated_at' => '2025-05-04 20:36:49',
            ),
            433 => 
            array (
                'id' => 200,
                'type' => 'factory',
                'name' => 'মোঃ মবিন',
                'deleted_at' => NULL,
                'created_at' => '2025-05-04 20:41:22',
                'updated_at' => '2025-05-04 20:41:22',
            ),
            434 => 
            array (
                'id' => 201,
                'type' => 'factory',
                'name' => 'মোঃ উজ্জল',
                'deleted_at' => NULL,
                'created_at' => '2025-05-04 20:45:27',
                'updated_at' => '2025-05-04 20:45:27',
            ),
            435 => 
            array (
                'id' => 160,
                'type' => 'retail-store',
                'name' => 'আনোয়ার সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-05-05 12:45:29',
                'updated_at' => '2025-05-05 12:45:29',
            ),
            436 => 
            array (
                'id' => 161,
                'type' => 'retail-store',
                'name' => 'আনোয়ার সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-05-05 14:52:20',
                'updated_at' => '2025-05-05 14:52:20',
            ),
            437 => 
            array (
                'id' => 202,
                'type' => 'factory',
                'name' => 'মোসাঃ প্রিসিলা',
                'deleted_at' => NULL,
                'created_at' => '2025-05-05 16:13:31',
                'updated_at' => '2025-05-05 16:13:31',
            ),
            438 => 
            array (
                'id' => 203,
                'type' => 'factory',
                'name' => 'মোঃ রাহাত',
                'deleted_at' => NULL,
                'created_at' => '2025-05-05 16:20:46',
                'updated_at' => '2025-05-05 16:20:46',
            ),
            439 => 
            array (
                'id' => 204,
                'type' => 'factory',
                'name' => 'মোঃ চান মিয়া',
                'deleted_at' => NULL,
                'created_at' => '2025-05-05 16:59:41',
                'updated_at' => '2025-05-05 16:59:41',
            ),
            440 => 
            array (
                'id' => 205,
                'type' => 'factory',
                'name' => 'মোঃ জামাল মিয়া',
                'deleted_at' => NULL,
                'created_at' => '2025-05-05 18:18:36',
                'updated_at' => '2025-05-05 18:18:36',
            ),
            441 => 
            array (
                'id' => 206,
                'type' => 'factory',
                'name' => 'মোঃ সুরুজ মিয়া',
                'deleted_at' => NULL,
                'created_at' => '2025-05-05 18:40:20',
                'updated_at' => '2025-05-05 18:40:20',
            ),
            442 => 
            array (
                'id' => 207,
                'type' => 'factory',
                'name' => 'মোঃ সবুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-05-05 18:41:48',
                'updated_at' => '2025-05-05 18:41:48',
            ),
            443 => 
            array (
                'id' => 208,
                'type' => 'factory',
                'name' => 'মোঃ রাজন',
                'deleted_at' => NULL,
                'created_at' => '2025-05-05 18:43:54',
                'updated_at' => '2025-05-05 18:43:54',
            ),
            444 => 
            array (
                'id' => 209,
                'type' => 'factory',
                'name' => 'ইউনিক',
                'deleted_at' => NULL,
                'created_at' => '2025-05-05 19:24:11',
                'updated_at' => '2025-05-05 19:24:11',
            ),
            445 => 
            array (
                'id' => 210,
                'type' => 'factory',
                'name' => 'মোঃ তাইজুল',
                'deleted_at' => NULL,
                'created_at' => '2025-05-05 19:25:24',
                'updated_at' => '2025-05-05 19:25:24',
            ),
            446 => 
            array (
                'id' => 211,
                'type' => 'factory',
                'name' => 'মোঃ মোস্তফা',
                'deleted_at' => NULL,
                'created_at' => '2025-05-05 19:28:17',
                'updated_at' => '2025-05-05 19:28:17',
            ),
            447 => 
            array (
                'id' => 212,
                'type' => 'factory',
                'name' => 'মোঃ সাদ্দাম',
                'deleted_at' => NULL,
                'created_at' => '2025-05-05 19:29:34',
                'updated_at' => '2025-05-05 19:29:34',
            ),
            448 => 
            array (
                'id' => 213,
                'type' => 'factory',
                'name' => 'মোঃ মোয়াজ্জেম',
                'deleted_at' => NULL,
                'created_at' => '2025-05-05 20:05:56',
                'updated_at' => '2025-05-05 20:05:56',
            ),
            449 => 
            array (
                'id' => 214,
                'type' => 'factory',
                'name' => 'মোঃ ইউসুফ',
                'deleted_at' => NULL,
                'created_at' => '2025-05-05 20:06:46',
                'updated_at' => '2025-05-05 20:06:46',
            ),
            450 => 
            array (
                'id' => 215,
                'type' => 'factory',
                'name' => 'বেওয়ারিশ',
                'deleted_at' => NULL,
                'created_at' => '2025-05-06 15:41:37',
                'updated_at' => '2025-05-06 15:41:37',
            ),
            451 => 
            array (
                'id' => 216,
                'type' => 'factory',
                'name' => 'মোঃ আমিরুল',
                'deleted_at' => NULL,
                'created_at' => '2025-05-06 19:46:29',
                'updated_at' => '2025-05-06 19:46:29',
            ),
            452 => 
            array (
                'id' => 217,
                'type' => 'factory',
                'name' => 'মোসাঃ ঝুমা',
                'deleted_at' => NULL,
                'created_at' => '2025-05-07 15:05:24',
                'updated_at' => '2025-05-07 15:05:24',
            ),
            453 => 
            array (
                'id' => 218,
                'type' => 'factory',
                'name' => 'মোঃ ফুয়াদ',
                'deleted_at' => NULL,
                'created_at' => '2025-05-07 17:26:42',
                'updated_at' => '2025-05-07 17:26:42',
            ),
            454 => 
            array (
                'id' => 219,
                'type' => 'factory',
                'name' => 'মোঃ নাছির',
                'deleted_at' => NULL,
                'created_at' => '2025-05-07 19:09:10',
                'updated_at' => '2025-05-07 19:09:10',
            ),
            455 => 
            array (
                'id' => 220,
                'type' => 'factory',
                'name' => 'মোঃ সুমন',
                'deleted_at' => NULL,
                'created_at' => '2025-05-11 00:12:35',
                'updated_at' => '2025-05-11 00:12:35',
            ),
            456 => 
            array (
                'id' => 221,
                'type' => 'factory',
                'name' => 'মোঃ আশরাফ',
                'deleted_at' => NULL,
                'created_at' => '2025-05-11 01:12:43',
                'updated_at' => '2025-05-11 01:12:43',
            ),
            457 => 
            array (
                'id' => 222,
                'type' => 'factory',
            'name' => 'আশরাফ (ক্লোজ)',
                'deleted_at' => NULL,
                'created_at' => '2025-05-11 01:31:35',
                'updated_at' => '2025-05-11 01:31:35',
            ),
            458 => 
            array (
                'id' => 223,
                'type' => 'factory',
                'name' => 'মোঃ রায়েন',
                'deleted_at' => NULL,
                'created_at' => '2025-05-11 01:41:26',
                'updated_at' => '2025-05-11 01:41:26',
            ),
            459 => 
            array (
                'id' => 162,
                'type' => 'retail-store',
                'name' => 'অভিযাত্রী সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-05-11 14:22:59',
                'updated_at' => '2025-05-11 14:22:59',
            ),
            460 => 
            array (
                'id' => 163,
                'type' => 'retail-store',
                'name' => 'ডলফিন সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-05-11 16:47:57',
                'updated_at' => '2025-05-11 16:47:57',
            ),
            461 => 
            array (
                'id' => 164,
                'type' => 'retail-store',
                'name' => 'ডলফিন সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-05-12 15:31:50',
                'updated_at' => '2025-05-12 15:31:50',
            ),
            462 => 
            array (
                'id' => 165,
                'type' => 'retail-store',
                'name' => 'লংলাইফ সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-05-12 19:05:20',
                'updated_at' => '2025-05-12 19:05:20',
            ),
            463 => 
            array (
                'id' => 166,
                'type' => 'retail-store',
                'name' => 'রেড ফেদার সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-05-13 13:42:33',
                'updated_at' => '2025-05-13 13:42:33',
            ),
            464 => 
            array (
                'id' => 167,
                'type' => 'retail-store',
                'name' => 'অভিযাত্রী সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-05-13 14:48:17',
                'updated_at' => '2025-05-13 14:48:17',
            ),
            465 => 
            array (
                'id' => 168,
                'type' => 'retail-store',
                'name' => 'হামিদা সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-05-13 16:22:33',
                'updated_at' => '2025-05-13 16:22:33',
            ),
            466 => 
            array (
                'id' => 169,
                'type' => 'retail-store',
                'name' => 'ফারদীন সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-05-13 18:03:36',
                'updated_at' => '2025-05-13 18:03:36',
            ),
            467 => 
            array (
                'id' => 224,
                'type' => 'factory',
                'name' => 'মোঃ রহিম',
                'deleted_at' => NULL,
                'created_at' => '2025-05-13 20:05:43',
                'updated_at' => '2025-05-13 20:05:43',
            ),
            468 => 
            array (
                'id' => 170,
                'type' => 'retail-store',
                'name' => 'আরাফাত সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-05-14 16:20:31',
                'updated_at' => '2025-05-14 16:20:31',
            ),
            469 => 
            array (
                'id' => 171,
                'type' => 'retail-store',
                'name' => 'জান্নাত সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-05-15 16:37:37',
                'updated_at' => '2025-05-15 16:37:37',
            ),
            470 => 
            array (
                'id' => 172,
                'type' => 'retail-store',
                'name' => 'রাজু সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-05-15 19:45:14',
                'updated_at' => '2025-05-15 19:45:14',
            ),
            471 => 
            array (
                'id' => 173,
                'type' => 'retail-store',
                'name' => 'স্পিডো সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-05-16 17:03:30',
                'updated_at' => '2025-05-16 17:03:30',
            ),
            472 => 
            array (
                'id' => 225,
                'type' => 'factory',
                'name' => 'নবাব',
                'deleted_at' => NULL,
                'created_at' => '2025-05-16 17:47:59',
                'updated_at' => '2025-05-16 17:47:59',
            ),
            473 => 
            array (
                'id' => 226,
                'type' => 'factory',
                'name' => 'শিপন',
                'deleted_at' => NULL,
                'created_at' => '2025-05-17 23:58:32',
                'updated_at' => '2025-05-17 23:58:32',
            ),
            474 => 
            array (
                'id' => 174,
                'type' => 'retail-store',
                'name' => 'ইত্যাদি সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-05-18 16:21:16',
                'updated_at' => '2025-05-18 16:21:16',
            ),
            475 => 
            array (
                'id' => 175,
                'type' => 'retail-store',
                'name' => 'ইত্যাদি সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-05-18 16:22:31',
                'updated_at' => '2025-05-18 16:22:31',
            ),
            476 => 
            array (
                'id' => 176,
                'type' => 'retail-store',
                'name' => 'ভাই ভাই সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-05-19 15:59:39',
                'updated_at' => '2025-05-19 15:59:39',
            ),
            477 => 
            array (
                'id' => 177,
                'type' => 'retail-store',
                'name' => 'ভাই বোন সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-05-19 17:06:39',
                'updated_at' => '2025-05-19 17:06:39',
            ),
            478 => 
            array (
                'id' => 9,
                'type' => 'gift-supplier',
                'name' => 'মুন লেভেল',
                'deleted_at' => NULL,
                'created_at' => '2025-05-20 20:14:37',
                'updated_at' => '2025-05-20 20:14:37',
            ),
            479 => 
            array (
                'id' => 178,
                'type' => 'retail-store',
                'name' => 'রাজধানী সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-05-22 17:28:51',
                'updated_at' => '2025-05-22 17:28:51',
            ),
            480 => 
            array (
                'id' => 227,
                'type' => 'factory',
                'name' => 'মোঃ নয়ন',
                'deleted_at' => NULL,
                'created_at' => '2025-05-22 22:21:04',
                'updated_at' => '2025-05-22 22:21:04',
            ),
            481 => 
            array (
                'id' => 179,
                'type' => 'retail-store',
                'name' => 'রবিউল সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-05-23 19:06:50',
                'updated_at' => '2025-05-23 19:06:50',
            ),
            482 => 
            array (
                'id' => 228,
                'type' => 'factory',
                'name' => 'মোঃ বশির',
                'deleted_at' => NULL,
                'created_at' => '2025-05-24 14:29:50',
                'updated_at' => '2025-05-24 14:29:50',
            ),
            483 => 
            array (
                'id' => 229,
                'type' => 'factory',
                'name' => 'মোসাঃ মেরিন',
                'deleted_at' => NULL,
                'created_at' => '2025-05-24 15:10:38',
                'updated_at' => '2025-05-24 15:10:38',
            ),
            484 => 
            array (
                'id' => 180,
                'type' => 'retail-store',
                'name' => 'আয়াশ সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-05-24 17:51:30',
                'updated_at' => '2025-05-24 17:51:30',
            ),
            485 => 
            array (
                'id' => 181,
                'type' => 'retail-store',
                'name' => 'সুফিয়া সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-05-24 20:24:46',
                'updated_at' => '2025-05-24 20:24:46',
            ),
            486 => 
            array (
                'id' => 230,
                'type' => 'factory',
                'name' => 'মোসাঃ মাইশা',
                'deleted_at' => NULL,
                'created_at' => '2025-05-24 21:07:22',
                'updated_at' => '2025-05-24 21:07:22',
            ),
            487 => 
            array (
                'id' => 231,
                'type' => 'factory',
                'name' => 'রাফিন',
                'deleted_at' => NULL,
                'created_at' => '2025-05-25 01:43:06',
                'updated_at' => '2025-05-25 01:43:06',
            ),
            488 => 
            array (
                'id' => 182,
                'type' => 'retail-store',
                'name' => 'রিফাত সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-05-25 15:07:39',
                'updated_at' => '2025-05-25 15:07:39',
            ),
            489 => 
            array (
                'id' => 11,
                'type' => 'bank-account',
                'name' => 'Uttara Bank',
                'deleted_at' => NULL,
                'created_at' => '2025-05-25 15:21:14',
                'updated_at' => '2025-05-25 15:21:14',
            ),
            490 => 
            array (
                'id' => 183,
                'type' => 'retail-store',
                'name' => 'আদর্শ সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-05-26 14:40:18',
                'updated_at' => '2025-05-26 14:40:18',
            ),
            491 => 
            array (
                'id' => 184,
                'type' => 'retail-store',
                'name' => 'বিশ্বাস সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 13:24:41',
                'updated_at' => '2025-05-27 13:24:41',
            ),
            492 => 
            array (
                'id' => 185,
                'type' => 'retail-store',
                'name' => 'ফরিদ সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 15:09:28',
                'updated_at' => '2025-05-27 15:09:28',
            ),
            493 => 
            array (
                'id' => 20,
                'type' => 'employee',
                'name' => 'Md Saleman',
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 16:17:27',
                'updated_at' => '2025-05-27 16:17:27',
            ),
            494 => 
            array (
                'id' => 21,
                'type' => 'employee',
                'name' => 'Md Prince',
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 16:18:48',
                'updated_at' => '2025-05-27 16:18:48',
            ),
            495 => 
            array (
                'id' => 232,
                'type' => 'factory',
                'name' => 'মোঃ রোমান',
                'deleted_at' => NULL,
                'created_at' => '2025-05-27 17:53:09',
                'updated_at' => '2025-05-27 17:53:09',
            ),
            496 => 
            array (
                'id' => 186,
                'type' => 'retail-store',
                'name' => 'পাপ্পু সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-05-28 14:40:21',
                'updated_at' => '2025-05-28 14:40:21',
            ),
            497 => 
            array (
                'id' => 233,
                'type' => 'factory',
                'name' => 'মাস্টার',
                'deleted_at' => NULL,
                'created_at' => '2025-05-28 21:08:54',
                'updated_at' => '2025-05-28 21:08:54',
            ),
            498 => 
            array (
                'id' => 187,
                'type' => 'retail-store',
                'name' => 'সাজ্জাদ সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-05-30 17:11:44',
                'updated_at' => '2025-05-30 17:11:44',
            ),
            499 => 
            array (
                'id' => 234,
                'type' => 'factory',
                'name' => 'মোঃ ইউনুস',
                'deleted_at' => NULL,
                'created_at' => '2025-05-30 22:11:47',
                'updated_at' => '2025-05-30 22:11:47',
            ),
        ));
        \DB::table('accounts')->insert(array (
            0 => 
            array (
                'id' => 36,
                'type' => 'expense',
                'name' => 'Donate',
                'deleted_at' => NULL,
                'created_at' => '2025-06-23 10:56:51',
                'updated_at' => '2025-06-23 10:56:51',
            ),
            1 => 
            array (
                'id' => 37,
                'type' => 'expense',
                'name' => 'Snacks',
                'deleted_at' => NULL,
                'created_at' => '2025-06-23 11:18:01',
                'updated_at' => '2025-06-23 11:18:01',
            ),
            2 => 
            array (
                'id' => 235,
                'type' => 'factory',
                'name' => 'তাহের',
                'deleted_at' => NULL,
                'created_at' => '2025-06-23 11:37:32',
                'updated_at' => '2025-06-23 11:37:32',
            ),
            3 => 
            array (
                'id' => 188,
                'type' => 'retail-store',
                'name' => 'নিউ মাস্টার সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-06-28 18:53:00',
                'updated_at' => '2025-06-28 18:53:00',
            ),
            4 => 
            array (
                'id' => 189,
                'type' => 'retail-store',
                'name' => 'শাহিন সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-07-03 15:33:17',
                'updated_at' => '2025-07-03 15:33:17',
            ),
            5 => 
            array (
                'id' => 190,
                'type' => 'retail-store',
                'name' => 'হারুন সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-07-07 17:26:40',
                'updated_at' => '2025-07-07 17:26:40',
            ),
            6 => 
            array (
                'id' => 236,
                'type' => 'factory',
                'name' => 'স্বদেশ',
                'deleted_at' => NULL,
                'created_at' => '2025-07-13 16:35:23',
                'updated_at' => '2025-07-13 16:35:23',
            ),
            7 => 
            array (
                'id' => 237,
                'type' => 'factory',
                'name' => 'মোঃ রাতুল',
                'deleted_at' => NULL,
                'created_at' => '2025-07-17 14:09:46',
                'updated_at' => '2025-07-17 14:09:46',
            ),
            8 => 
            array (
                'id' => 191,
                'type' => 'retail-store',
                'name' => 'আলহাজ্ব সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-07-19 12:53:10',
                'updated_at' => '2025-07-19 12:53:10',
            ),
            9 => 
            array (
                'id' => 192,
                'type' => 'retail-store',
                'name' => 'রিফাত সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-07-19 19:48:34',
                'updated_at' => '2025-07-19 19:48:34',
            ),
            10 => 
            array (
                'id' => 193,
                'type' => 'retail-store',
                'name' => 'সাইফুল সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-07-20 14:12:18',
                'updated_at' => '2025-07-20 14:12:18',
            ),
            11 => 
            array (
                'id' => 238,
                'type' => 'factory',
                'name' => 'মোঃ আবির',
                'deleted_at' => NULL,
                'created_at' => '2025-07-21 18:37:42',
                'updated_at' => '2025-07-21 18:37:42',
            ),
            12 => 
            array (
                'id' => 194,
                'type' => 'retail-store',
                'name' => 'সোহাগ সু ষ্টোর',
                'deleted_at' => NULL,
                'created_at' => '2025-07-22 12:33:52',
                'updated_at' => '2025-07-22 12:33:52',
            ),
            13 => 
            array (
                'id' => 38,
                'type' => 'expense',
                'name' => 'তাগাদা খরচ',
                'deleted_at' => NULL,
                'created_at' => '2025-07-22 14:01:07',
                'updated_at' => '2025-07-22 14:01:07',
            ),
            14 => 
            array (
                'id' => 10,
                'type' => 'gift-supplier',
                'name' => 'হাফিজ বক্স',
                'deleted_at' => NULL,
                'created_at' => '2025-07-22 18:05:39',
                'updated_at' => '2025-07-22 18:05:39',
            ),
            15 => 
            array (
                'id' => 39,
                'type' => 'expense',
                'name' => 'Mitul',
                'deleted_at' => NULL,
                'created_at' => '2025-07-22 20:51:06',
                'updated_at' => '2025-07-22 20:51:06',
            ),
            16 => 
            array (
                'id' => 40,
                'type' => 'expense',
                'name' => 'Shop Rent',
                'deleted_at' => NULL,
                'created_at' => '2025-07-22 20:55:59',
                'updated_at' => '2025-07-22 20:55:59',
            ),
            17 => 
            array (
                'id' => 11,
                'type' => 'gift-supplier',
                'name' => 'মিলন চাবির রিং',
                'deleted_at' => NULL,
                'created_at' => '2025-07-23 12:28:26',
                'updated_at' => '2025-07-23 12:28:26',
            ),
            18 => 
            array (
                'id' => 239,
                'type' => 'factory',
                'name' => 'মোঃ পাবেল',
                'deleted_at' => NULL,
                'created_at' => '2025-07-26 21:54:44',
                'updated_at' => '2025-07-26 21:54:44',
            ),
            19 => 
            array (
                'id' => 12,
                'type' => 'gift-supplier',
                'name' => 'জাকির বক্স',
                'deleted_at' => NULL,
                'created_at' => '2025-07-31 11:50:04',
                'updated_at' => '2025-07-31 11:50:04',
            ),
            20 => 
            array (
                'id' => 195,
                'type' => 'retail-store',
                'name' => 'প্লাবন সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-07-31 12:55:41',
                'updated_at' => '2025-07-31 12:55:41',
            ),
            21 => 
            array (
                'id' => 240,
                'type' => 'factory',
                'name' => 'মোঃ কাওসার',
                'deleted_at' => NULL,
                'created_at' => '2025-08-03 21:02:46',
                'updated_at' => '2025-08-03 21:02:46',
            ),
            22 => 
            array (
                'id' => 41,
                'type' => 'expense',
                'name' => 'Avijatry Khulna',
                'deleted_at' => NULL,
                'created_at' => '2025-08-04 11:58:46',
                'updated_at' => '2025-08-04 11:58:46',
            ),
            23 => 
            array (
                'id' => 42,
                'type' => 'expense',
                'name' => 'Avijatry Cumilla',
                'deleted_at' => NULL,
                'created_at' => '2025-08-04 11:58:57',
                'updated_at' => '2025-08-04 11:58:57',
            ),
            24 => 
            array (
                'id' => 241,
                'type' => 'factory',
                'name' => 'কাউসার',
                'deleted_at' => NULL,
                'created_at' => '2025-08-04 17:18:28',
                'updated_at' => '2025-08-04 17:18:28',
            ),
            25 => 
            array (
                'id' => 43,
                'type' => 'expense',
                'name' => 'Shop Expense',
                'deleted_at' => NULL,
                'created_at' => '2025-08-11 12:07:32',
                'updated_at' => '2025-08-11 12:07:32',
            ),
            26 => 
            array (
                'id' => 13,
                'type' => 'gift-supplier',
                'name' => 'কাচখোলা',
                'deleted_at' => NULL,
                'created_at' => '2025-08-11 12:44:43',
                'updated_at' => '2025-08-11 12:44:43',
            ),
            27 => 
            array (
                'id' => 242,
                'type' => 'factory',
                'name' => 'আফজাল',
                'deleted_at' => NULL,
                'created_at' => '2025-08-11 16:04:23',
                'updated_at' => '2025-08-11 16:04:23',
            ),
            28 => 
            array (
                'id' => 243,
                'type' => 'factory',
                'name' => 'মোঃ শামসুদ্দিন',
                'deleted_at' => NULL,
                'created_at' => '2025-08-11 16:09:35',
                'updated_at' => '2025-08-11 16:09:35',
            ),
            29 => 
            array (
                'id' => 244,
                'type' => 'factory',
                'name' => 'মোঃ আফজাল',
                'deleted_at' => NULL,
                'created_at' => '2025-08-11 16:36:57',
                'updated_at' => '2025-08-11 16:36:57',
            ),
            30 => 
            array (
                'id' => 245,
                'type' => 'factory',
                'name' => 'মোঃ জাকারিয়া',
                'deleted_at' => NULL,
                'created_at' => '2025-08-11 20:50:44',
                'updated_at' => '2025-08-11 20:50:44',
            ),
            31 => 
            array (
                'id' => 44,
                'type' => 'expense',
                'name' => 'EMI',
                'deleted_at' => NULL,
                'created_at' => '2025-08-12 12:39:54',
                'updated_at' => '2025-08-12 12:39:54',
            ),
            32 => 
            array (
                'id' => 14,
                'type' => 'gift-supplier',
                'name' => 'এস এ এন ব্যাগ',
                'deleted_at' => NULL,
                'created_at' => '2025-08-12 13:28:27',
                'updated_at' => '2025-08-12 13:28:27',
            ),
            33 => 
            array (
                'id' => 22,
                'type' => 'employee',
                'name' => 'Ripon',
                'deleted_at' => NULL,
                'created_at' => '2025-08-13 12:29:02',
                'updated_at' => '2025-08-13 12:29:02',
            ),
            34 => 
            array (
                'id' => 23,
                'type' => 'employee',
                'name' => 'Mahir',
                'deleted_at' => NULL,
                'created_at' => '2025-08-13 14:24:47',
                'updated_at' => '2025-08-13 14:24:47',
            ),
            35 => 
            array (
                'id' => 24,
                'type' => 'employee',
                'name' => 'Md Sunny',
                'deleted_at' => NULL,
                'created_at' => '2025-08-13 15:16:38',
                'updated_at' => '2025-08-13 15:16:38',
            ),
            36 => 
            array (
                'id' => 246,
                'type' => 'factory',
                'name' => 'সামসুদ্দিন',
                'deleted_at' => NULL,
                'created_at' => '2025-08-16 10:02:35',
                'updated_at' => '2025-08-16 10:02:35',
            ),
            37 => 
            array (
                'id' => 247,
                'type' => 'factory',
                'name' => 'মোসাঃ সুমাইয়া',
                'deleted_at' => NULL,
                'created_at' => '2025-08-17 15:13:53',
                'updated_at' => '2025-08-17 15:13:53',
            ),
            38 => 
            array (
                'id' => 248,
                'type' => 'factory',
                'name' => 'মোঃ ওবায়দুল',
                'deleted_at' => NULL,
                'created_at' => '2025-08-17 20:53:50',
                'updated_at' => '2025-08-17 20:53:50',
            ),
            39 => 
            array (
                'id' => 25,
                'type' => 'employee',
                'name' => 'Md Abu Bakkar',
                'deleted_at' => NULL,
                'created_at' => '2025-08-30 10:55:57',
                'updated_at' => '2025-08-30 10:55:57',
            ),
            40 => 
            array (
                'id' => 26,
                'type' => 'employee',
                'name' => 'Md Parvez',
                'deleted_at' => NULL,
                'created_at' => '2025-08-30 10:56:44',
                'updated_at' => '2025-08-30 10:56:44',
            ),
            41 => 
            array (
                'id' => 196,
                'type' => 'retail-store',
                'name' => 'আরিফ সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-08-30 11:31:55',
                'updated_at' => '2025-08-30 11:31:55',
            ),
            42 => 
            array (
                'id' => 249,
                'type' => 'factory',
                'name' => 'মোঃ ফাইজুদ্দিন',
                'deleted_at' => NULL,
                'created_at' => '2025-08-31 16:49:01',
                'updated_at' => '2025-08-31 16:49:01',
            ),
            43 => 
            array (
                'id' => 250,
                'type' => 'factory',
                'name' => 'মোঃ আবেদুর রহমান',
                'deleted_at' => NULL,
                'created_at' => '2025-08-31 16:52:35',
                'updated_at' => '2025-08-31 16:52:35',
            ),
            44 => 
            array (
                'id' => 251,
                'type' => 'factory',
                'name' => 'মোঃ গুড্ডু',
                'deleted_at' => NULL,
                'created_at' => '2025-08-31 17:37:54',
                'updated_at' => '2025-08-31 17:37:54',
            ),
            45 => 
            array (
                'id' => 252,
                'type' => 'factory',
                'name' => 'মোঃ আনাছ',
                'deleted_at' => NULL,
                'created_at' => '2025-09-01 20:57:33',
                'updated_at' => '2025-09-01 20:57:33',
            ),
            46 => 
            array (
                'id' => 253,
                'type' => 'factory',
                'name' => 'মোঃ পারভেজ',
                'deleted_at' => NULL,
                'created_at' => '2025-09-02 16:42:36',
                'updated_at' => '2025-09-02 16:42:36',
            ),
            47 => 
            array (
                'id' => 197,
                'type' => 'retail-store',
                'name' => 'ফিট এক্স গ্যালারী',
                'deleted_at' => NULL,
                'created_at' => '2025-09-10 18:42:10',
                'updated_at' => '2025-09-10 18:42:10',
            ),
            48 => 
            array (
                'id' => 198,
                'type' => 'retail-store',
                'name' => 'ভাই ভাই সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-09-11 19:56:47',
                'updated_at' => '2025-09-11 19:56:47',
            ),
            49 => 
            array (
                'id' => 199,
                'type' => 'retail-store',
                'name' => 'সনি সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-09-13 16:55:15',
                'updated_at' => '2025-09-13 16:55:15',
            ),
            50 => 
            array (
                'id' => 200,
                'type' => 'retail-store',
                'name' => 'ইজি সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-09-14 21:00:19',
                'updated_at' => '2025-09-14 21:00:19',
            ),
            51 => 
            array (
                'id' => 201,
                'type' => 'retail-store',
                'name' => 'অপূর্ব সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-09-14 21:14:48',
                'updated_at' => '2025-09-14 21:14:48',
            ),
            52 => 
            array (
                'id' => 202,
                'type' => 'retail-store',
                'name' => 'মরিয়ম সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-09-15 17:09:34',
                'updated_at' => '2025-09-15 17:09:34',
            ),
            53 => 
            array (
                'id' => 203,
                'type' => 'retail-store',
                'name' => 'ভাই ভাই সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-09-15 17:32:30',
                'updated_at' => '2025-09-15 17:32:30',
            ),
            54 => 
            array (
                'id' => 254,
                'type' => 'factory',
                'name' => 'মোঃ মানিক',
                'deleted_at' => NULL,
                'created_at' => '2025-09-16 21:50:17',
                'updated_at' => '2025-09-16 21:50:17',
            ),
            55 => 
            array (
                'id' => 255,
                'type' => 'factory',
                'name' => 'মোঃ মোয়াজ',
                'deleted_at' => NULL,
                'created_at' => '2025-09-17 17:22:17',
                'updated_at' => '2025-09-17 17:22:17',
            ),
            56 => 
            array (
                'id' => 204,
                'type' => 'retail-store',
                'name' => 'মেহেদী সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-09-20 14:51:34',
                'updated_at' => '2025-09-20 14:51:34',
            ),
            57 => 
            array (
                'id' => 256,
                'type' => 'factory',
                'name' => 'মোঃ বশিরুল',
                'deleted_at' => NULL,
                'created_at' => '2025-09-29 19:55:02',
                'updated_at' => '2025-09-29 19:55:02',
            ),
            58 => 
            array (
                'id' => 205,
                'type' => 'retail-store',
                'name' => 'সাফা সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-10-14 19:53:03',
                'updated_at' => '2025-10-14 19:53:03',
            ),
            59 => 
            array (
                'id' => 257,
                'type' => 'factory',
                'name' => 'মোঃ রাফসান',
                'deleted_at' => NULL,
                'created_at' => '2025-10-15 20:47:36',
                'updated_at' => '2025-10-15 20:47:36',
            ),
            60 => 
            array (
                'id' => 258,
                'type' => 'factory',
                'name' => 'মোঃ মাহমুদুল হাসান',
                'deleted_at' => NULL,
                'created_at' => '2025-10-18 13:00:16',
                'updated_at' => '2025-10-18 13:00:16',
            ),
            61 => 
            array (
                'id' => 259,
                'type' => 'factory',
                'name' => 'মোসাঃ মিদা',
                'deleted_at' => NULL,
                'created_at' => '2025-10-18 13:02:16',
                'updated_at' => '2025-10-18 13:02:16',
            ),
            62 => 
            array (
                'id' => 260,
                'type' => 'factory',
                'name' => 'মোঃ নুরআলম',
                'deleted_at' => NULL,
                'created_at' => '2025-10-18 20:24:32',
                'updated_at' => '2025-10-18 20:24:32',
            ),
            63 => 
            array (
                'id' => 261,
                'type' => 'factory',
                'name' => 'মোসাঃ ফাতেমা',
                'deleted_at' => NULL,
                'created_at' => '2025-10-18 20:51:34',
                'updated_at' => '2025-10-18 20:51:34',
            ),
            64 => 
            array (
                'id' => 262,
                'type' => 'factory',
                'name' => 'মোঃ হক',
                'deleted_at' => NULL,
                'created_at' => '2025-10-19 20:41:04',
                'updated_at' => '2025-10-19 20:41:04',
            ),
            65 => 
            array (
                'id' => 263,
                'type' => 'factory',
                'name' => 'মোঃ রকিব',
                'deleted_at' => NULL,
                'created_at' => '2025-10-21 17:10:22',
                'updated_at' => '2025-10-21 17:10:22',
            ),
            66 => 
            array (
                'id' => 264,
                'type' => 'factory',
                'name' => 'প্লাস পয়েন্ট',
                'deleted_at' => NULL,
                'created_at' => '2025-10-21 17:30:13',
                'updated_at' => '2025-10-21 17:30:13',
            ),
            67 => 
            array (
                'id' => 265,
                'type' => 'factory',
                'name' => 'মোঃ মাইজু মিয়া',
                'deleted_at' => NULL,
                'created_at' => '2025-10-23 16:38:38',
                'updated_at' => '2025-10-23 16:38:38',
            ),
            68 => 
            array (
                'id' => 45,
                'type' => 'expense',
                'name' => 'warehouse electricity bill',
                'deleted_at' => NULL,
                'created_at' => '2025-10-23 17:13:45',
                'updated_at' => '2025-10-23 17:13:45',
            ),
            69 => 
            array (
                'id' => 266,
                'type' => 'factory',
                'name' => 'মোসাঃ ইসরাত',
                'deleted_at' => NULL,
                'created_at' => '2025-10-25 21:08:55',
                'updated_at' => '2025-10-25 21:08:55',
            ),
            70 => 
            array (
                'id' => 267,
                'type' => 'factory',
                'name' => 'মোঃ হোসেন',
                'deleted_at' => NULL,
                'created_at' => '2025-10-25 21:11:45',
                'updated_at' => '2025-10-25 21:11:45',
            ),
            71 => 
            array (
                'id' => 268,
                'type' => 'factory',
                'name' => 'মোঃ রিয়াজুল',
                'deleted_at' => NULL,
                'created_at' => '2025-10-27 10:39:04',
                'updated_at' => '2025-10-27 10:39:04',
            ),
            72 => 
            array (
                'id' => 269,
                'type' => 'factory',
                'name' => 'মোঃ বশীর',
                'deleted_at' => NULL,
                'created_at' => '2025-10-27 10:58:41',
                'updated_at' => '2025-10-27 10:58:41',
            ),
            73 => 
            array (
                'id' => 46,
                'type' => 'expense',
                'name' => 'Tax',
                'deleted_at' => NULL,
                'created_at' => '2025-10-27 11:17:19',
                'updated_at' => '2025-10-27 11:17:19',
            ),
            74 => 
            array (
                'id' => 47,
                'type' => 'expense',
                'name' => 'Party Mamla',
                'deleted_at' => NULL,
                'created_at' => '2025-10-27 12:40:28',
                'updated_at' => '2025-10-27 12:40:28',
            ),
            75 => 
            array (
                'id' => 270,
                'type' => 'factory',
                'name' => 'মোঃ তকদির',
                'deleted_at' => NULL,
                'created_at' => '2025-10-27 12:57:32',
                'updated_at' => '2025-10-27 12:57:32',
            ),
            76 => 
            array (
                'id' => 206,
                'type' => 'retail-store',
                'name' => 'রেখা সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-10-30 16:36:16',
                'updated_at' => '2025-10-30 16:36:16',
            ),
            77 => 
            array (
                'id' => 271,
                'type' => 'factory',
                'name' => 'মোঃ সিদ্দিক',
                'deleted_at' => NULL,
                'created_at' => '2025-11-01 14:34:25',
                'updated_at' => '2025-11-01 14:34:25',
            ),
            78 => 
            array (
                'id' => 272,
                'type' => 'factory',
                'name' => 'মোসাঃ ওমি',
                'deleted_at' => NULL,
                'created_at' => '2025-11-01 17:23:29',
                'updated_at' => '2025-11-01 17:23:29',
            ),
            79 => 
            array (
                'id' => 273,
                'type' => 'factory',
                'name' => 'মোঃ সোহাগ',
                'deleted_at' => NULL,
                'created_at' => '2025-11-01 17:23:48',
                'updated_at' => '2025-11-01 17:23:48',
            ),
            80 => 
            array (
                'id' => 274,
                'type' => 'factory',
                'name' => 'মোঃ ওমি',
                'deleted_at' => NULL,
                'created_at' => '2025-11-01 17:34:27',
                'updated_at' => '2025-11-01 17:34:27',
            ),
            81 => 
            array (
                'id' => 207,
                'type' => 'retail-store',
                'name' => 'এস এম সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-11-01 18:00:49',
                'updated_at' => '2025-11-01 18:00:49',
            ),
            82 => 
            array (
                'id' => 208,
                'type' => 'retail-store',
                'name' => 'এস এম সুজ ষ্টোর',
                'deleted_at' => NULL,
                'created_at' => '2025-11-02 11:00:41',
                'updated_at' => '2025-11-02 11:00:41',
            ),
            83 => 
            array (
                'id' => 1,
                'type' => 'loan',
                'name' => 'চায়না',
                'deleted_at' => NULL,
                'created_at' => '2025-11-03 11:57:04',
                'updated_at' => '2025-11-03 11:57:04',
            ),
            84 => 
            array (
                'id' => 208,
                'type' => 'retail-store',
                'name' => 'তাহা সুজ স্টোর',
                'deleted_at' => NULL,
                'created_at' => '2025-11-03 18:23:49',
                'updated_at' => '2025-11-03 18:23:49',
            ),
            85 => 
            array (
                'id' => 275,
                'type' => 'factory',
                'name' => 'মোঃ পাটোয়ারী',
                'deleted_at' => NULL,
                'created_at' => '2025-11-04 11:19:49',
                'updated_at' => '2025-11-04 11:19:49',
            ),
            86 => 
            array (
                'id' => 276,
                'type' => 'factory',
                'name' => 'মোসাঃ নওশীন',
                'deleted_at' => NULL,
                'created_at' => '2025-11-05 21:34:20',
                'updated_at' => '2025-11-05 21:34:20',
            ),
            87 => 
            array (
                'id' => 277,
                'type' => 'factory',
                'name' => 'মোঃ নাহিয়ান',
                'deleted_at' => NULL,
                'created_at' => '2025-11-06 11:48:58',
                'updated_at' => '2025-11-06 11:48:58',
            ),
            88 => 
            array (
                'id' => 209,
                'type' => 'retail-store',
                'name' => 'মাহিন ফ্যাশন এন্ড সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-11-06 19:03:11',
                'updated_at' => '2025-11-06 19:03:11',
            ),
            89 => 
            array (
                'id' => 278,
                'type' => 'factory',
                'name' => 'মোসাঃ মাহেলা',
                'deleted_at' => NULL,
                'created_at' => '2025-11-09 21:25:18',
                'updated_at' => '2025-11-09 21:25:18',
            ),
            90 => 
            array (
                'id' => 279,
                'type' => 'factory',
                'name' => 'মোঃ বাপ্পি',
                'deleted_at' => NULL,
                'created_at' => '2025-11-09 21:34:42',
                'updated_at' => '2025-11-09 21:34:42',
            ),
            91 => 
            array (
                'id' => 280,
                'type' => 'factory',
                'name' => 'মোঃ মাহেরা',
                'deleted_at' => NULL,
                'created_at' => '2025-11-10 17:23:18',
                'updated_at' => '2025-11-10 17:23:18',
            ),
            92 => 
            array (
                'id' => 281,
                'type' => 'factory',
                'name' => 'মোসাঃ আঁচল',
                'deleted_at' => NULL,
                'created_at' => '2025-11-11 22:11:20',
                'updated_at' => '2025-11-11 22:11:20',
            ),
            93 => 
            array (
                'id' => 210,
                'type' => 'retail-store',
                'name' => 'ইউনিক সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-11-15 18:53:56',
                'updated_at' => '2025-11-15 18:53:56',
            ),
            94 => 
            array (
                'id' => 282,
                'type' => 'factory',
                'name' => 'মোঃ সাদ',
                'deleted_at' => NULL,
                'created_at' => '2025-11-15 19:40:47',
                'updated_at' => '2025-11-15 19:40:47',
            ),
            95 => 
            array (
                'id' => 211,
                'type' => 'retail-store',
                'name' => 'মায়ের দোয়া- সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-11-16 16:55:57',
                'updated_at' => '2025-11-16 16:55:57',
            ),
            96 => 
            array (
                'id' => 212,
                'type' => 'retail-store',
                'name' => 'মায়ের দোয়া সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-11-16 16:57:35',
                'updated_at' => '2025-11-16 16:57:35',
            ),
            97 => 
            array (
                'id' => 213,
                'type' => 'retail-store',
                'name' => 'আমেনা সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-11-18 17:43:05',
                'updated_at' => '2025-11-18 17:43:05',
            ),
            98 => 
            array (
                'id' => 214,
                'type' => 'retail-store',
                'name' => 'আরোহী সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-11-18 17:45:31',
                'updated_at' => '2025-11-18 17:45:31',
            ),
            99 => 
            array (
                'id' => 283,
                'type' => 'factory',
                'name' => 'মোঃ সাদেক',
                'deleted_at' => NULL,
                'created_at' => '2025-11-22 23:11:45',
                'updated_at' => '2025-11-22 23:11:45',
            ),
            100 => 
            array (
                'id' => 215,
                'type' => 'retail-store',
                'name' => 'ওয়েলকাম সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-11-22 23:48:03',
                'updated_at' => '2025-11-22 23:48:03',
            ),
            101 => 
            array (
                'id' => 284,
                'type' => 'factory',
                'name' => 'মোঃ মোবারক',
                'deleted_at' => NULL,
                'created_at' => '2025-11-23 20:35:07',
                'updated_at' => '2025-11-23 20:35:07',
            ),
            102 => 
            array (
                'id' => 285,
                'type' => 'factory',
                'name' => 'মোঃ আসলাম খান',
                'deleted_at' => NULL,
                'created_at' => '2025-11-23 21:21:36',
                'updated_at' => '2025-11-23 21:21:36',
            ),
            103 => 
            array (
                'id' => 286,
                'type' => 'factory',
                'name' => 'মোঃ রাকিব',
                'deleted_at' => NULL,
                'created_at' => '2025-11-25 20:14:16',
                'updated_at' => '2025-11-25 20:14:16',
            ),
            104 => 
            array (
                'id' => 15,
                'type' => 'gift-supplier',
                'name' => 'তাহের বক্স',
                'deleted_at' => NULL,
                'created_at' => '2025-11-26 14:11:28',
                'updated_at' => '2025-11-26 14:11:28',
            ),
            105 => 
            array (
                'id' => 216,
                'type' => 'retail-store',
                'name' => 'অভিযাত্রী সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-11-26 19:38:00',
                'updated_at' => '2025-11-26 19:38:00',
            ),
            106 => 
            array (
                'id' => 287,
                'type' => 'factory',
                'name' => 'মোঃ মাজেদ',
                'deleted_at' => NULL,
                'created_at' => '2025-11-29 17:04:08',
                'updated_at' => '2025-11-29 17:04:08',
            ),
            107 => 
            array (
                'id' => 48,
                'type' => 'expense',
            'name' => 'Driver(Rafial)',
                'deleted_at' => NULL,
                'created_at' => '2025-11-30 15:43:11',
                'updated_at' => '2025-11-30 15:43:11',
            ),
            108 => 
            array (
                'id' => 288,
                'type' => 'factory',
                'name' => 'মোঃ ফোরকান',
                'deleted_at' => NULL,
                'created_at' => '2025-12-01 17:14:10',
                'updated_at' => '2025-12-01 17:14:10',
            ),
            109 => 
            array (
                'id' => 27,
                'type' => 'employee',
                'name' => 'Antor',
                'deleted_at' => NULL,
                'created_at' => '2025-12-01 17:16:20',
                'updated_at' => '2025-12-01 17:16:20',
            ),
            110 => 
            array (
                'id' => 217,
                'type' => 'retail-store',
                'name' => 'নোমান সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-12-01 21:11:33',
                'updated_at' => '2025-12-01 21:11:33',
            ),
            111 => 
            array (
                'id' => 218,
                'type' => 'retail-store',
                'name' => 'এস বি এল সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-12-02 16:46:30',
                'updated_at' => '2025-12-02 16:46:30',
            ),
            112 => 
            array (
                'id' => 219,
                'type' => 'retail-store',
                'name' => 'রাইসা সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-12-03 16:13:02',
                'updated_at' => '2025-12-03 16:13:02',
            ),
            113 => 
            array (
                'id' => 12,
                'type' => 'bank-account',
                'name' => 'Meghna Bank',
                'deleted_at' => NULL,
                'created_at' => '2025-12-04 15:53:23',
                'updated_at' => '2025-12-04 15:53:23',
            ),
            114 => 
            array (
                'id' => 28,
                'type' => 'employee',
                'name' => 'Md Sagor',
                'deleted_at' => NULL,
                'created_at' => '2025-12-06 11:28:07',
                'updated_at' => '2025-12-06 11:28:07',
            ),
            115 => 
            array (
                'id' => 29,
                'type' => 'employee',
                'name' => 'Md Sajid',
                'deleted_at' => NULL,
                'created_at' => '2025-12-06 11:29:39',
                'updated_at' => '2025-12-06 11:29:39',
            ),
            116 => 
            array (
                'id' => 16,
                'type' => 'gift-supplier',
            'name' => 'মোঃ রশিদ (ব্যাগ)',
                'deleted_at' => NULL,
                'created_at' => '2025-12-09 15:54:10',
                'updated_at' => '2025-12-09 15:54:10',
            ),
            117 => 
            array (
                'id' => 220,
                'type' => 'retail-store',
                'name' => 'লাইবা সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-12-13 17:08:45',
                'updated_at' => '2025-12-13 17:08:45',
            ),
            118 => 
            array (
                'id' => 221,
                'type' => 'retail-store',
                'name' => 'আরিফ সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-12-15 12:11:33',
                'updated_at' => '2025-12-15 12:11:33',
            ),
            119 => 
            array (
                'id' => 289,
                'type' => 'factory',
                'name' => 'যাদু ফুটওয়্যার',
                'deleted_at' => NULL,
                'created_at' => '2025-12-18 18:00:58',
                'updated_at' => '2025-12-18 18:00:58',
            ),
            120 => 
            array (
                'id' => 290,
                'type' => 'factory',
                'name' => 'মোঃ মাহফুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-12-21 17:06:17',
                'updated_at' => '2025-12-21 17:06:17',
            ),
            121 => 
            array (
                'id' => 222,
                'type' => 'retail-store',
                'name' => 'আফিয়া সুজ',
                'deleted_at' => NULL,
                'created_at' => '2025-12-22 11:33:21',
                'updated_at' => '2025-12-22 11:33:21',
            ),
            122 => 
            array (
                'id' => 291,
                'type' => 'factory',
                'name' => 'মোঃ সাদিদ ইসলাম',
                'deleted_at' => NULL,
                'created_at' => '2025-12-29 15:46:41',
                'updated_at' => '2025-12-29 15:46:41',
            ),
            123 => 
            array (
                'id' => 292,
                'type' => 'factory',
                'name' => 'মোঃ তিজার',
                'deleted_at' => NULL,
                'created_at' => '2025-12-29 19:43:57',
                'updated_at' => '2025-12-29 19:43:57',
            ),
            124 => 
            array (
                'id' => 293,
                'type' => 'factory',
                'name' => 'মোঃ সাদিদ',
                'deleted_at' => NULL,
                'created_at' => '2025-12-29 19:53:29',
                'updated_at' => '2025-12-29 19:53:29',
            ),
            125 => 
            array (
                'id' => 294,
                'type' => 'factory',
                'name' => 'মোঃ রাজিবুল্লাহ',
                'deleted_at' => NULL,
                'created_at' => '2025-12-30 12:15:45',
                'updated_at' => '2025-12-30 12:15:45',
            ),
            126 => 
            array (
                'id' => 295,
                'type' => 'factory',
                'name' => 'মোঃ শিবলু',
                'deleted_at' => NULL,
                'created_at' => '2026-01-03 19:35:10',
                'updated_at' => '2026-01-03 19:35:10',
            ),
            127 => 
            array (
                'id' => 17,
                'type' => 'gift-supplier',
            'name' => 'মোঃ হানিফ (গ্যারান্টি বই)',
                'deleted_at' => NULL,
                'created_at' => '2026-01-04 17:22:47',
                'updated_at' => '2026-01-04 17:22:47',
            ),
            128 => 
            array (
                'id' => 296,
                'type' => 'factory',
                'name' => 'মোঃ সজল',
                'deleted_at' => NULL,
                'created_at' => '2026-01-12 11:14:10',
                'updated_at' => '2026-01-12 11:14:10',
            ),
            129 => 
            array (
                'id' => 297,
                'type' => 'factory',
                'name' => 'আবু সুফিয়ান',
                'deleted_at' => NULL,
                'created_at' => '2026-01-13 18:36:33',
                'updated_at' => '2026-01-13 18:36:33',
            ),
            130 => 
            array (
                'id' => 223,
                'type' => 'retail-store',
                'name' => 'অভিযাত্রী সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-01-13 18:48:19',
                'updated_at' => '2026-01-13 18:48:19',
            ),
            131 => 
            array (
                'id' => 298,
                'type' => 'factory',
                'name' => 'মোঃ দেলোয়ার',
                'deleted_at' => NULL,
                'created_at' => '2026-01-13 21:21:07',
                'updated_at' => '2026-01-13 21:21:07',
            ),
            132 => 
            array (
                'id' => 299,
                'type' => 'factory',
                'name' => 'মোঃ দ্বীন ইসলাম',
                'deleted_at' => NULL,
                'created_at' => '2026-01-15 16:57:10',
                'updated_at' => '2026-01-15 16:57:10',
            ),
            133 => 
            array (
                'id' => 2,
                'type' => 'loan',
                'name' => 'Avijatry- Cumilla',
                'deleted_at' => NULL,
                'created_at' => '2026-01-17 14:18:07',
                'updated_at' => '2026-01-17 14:18:07',
            ),
            134 => 
            array (
                'id' => 3,
                'type' => 'loan',
                'name' => 'Avijatry-Khulna',
                'deleted_at' => NULL,
                'created_at' => '2026-01-17 14:18:22',
                'updated_at' => '2026-01-17 14:18:22',
            ),
            135 => 
            array (
                'id' => 4,
                'type' => 'loan',
                'name' => 'Adda',
                'deleted_at' => NULL,
                'created_at' => '2026-01-17 17:14:25',
                'updated_at' => '2026-01-17 17:14:25',
            ),
            136 => 
            array (
                'id' => 300,
                'type' => 'factory',
                'name' => 'মোঃ সিজান',
                'deleted_at' => NULL,
                'created_at' => '2026-01-17 22:16:28',
                'updated_at' => '2026-01-17 22:16:28',
            ),
            137 => 
            array (
                'id' => 301,
                'type' => 'factory',
                'name' => 'মোসাঃ হালিমা',
                'deleted_at' => NULL,
                'created_at' => '2026-01-19 12:42:52',
                'updated_at' => '2026-01-19 12:42:52',
            ),
            138 => 
            array (
                'id' => 224,
                'type' => 'retail-store',
                'name' => 'সওদাগর সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-01-19 17:51:11',
                'updated_at' => '2026-01-19 17:51:11',
            ),
            139 => 
            array (
                'id' => 225,
                'type' => 'retail-store',
                'name' => 'মুজাহিদ সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-01-20 15:19:19',
                'updated_at' => '2026-01-20 15:19:19',
            ),
            140 => 
            array (
                'id' => 302,
                'type' => 'factory',
                'name' => 'মোঃ আশরাফুল',
                'deleted_at' => NULL,
                'created_at' => '2026-01-24 12:04:54',
                'updated_at' => '2026-01-24 12:04:54',
            ),
            141 => 
            array (
                'id' => 303,
                'type' => 'factory',
                'name' => 'মোঃ নাইম',
                'deleted_at' => NULL,
                'created_at' => '2026-01-24 12:09:22',
                'updated_at' => '2026-01-24 12:09:22',
            ),
            142 => 
            array (
                'id' => 226,
                'type' => 'retail-store',
                'name' => 'মুন্না সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-01-24 13:14:27',
                'updated_at' => '2026-01-24 13:14:27',
            ),
            143 => 
            array (
                'id' => 304,
                'type' => 'factory',
                'name' => 'হাসান জুয়েল',
                'deleted_at' => NULL,
                'created_at' => '2026-01-24 19:09:39',
                'updated_at' => '2026-01-24 19:09:39',
            ),
            144 => 
            array (
                'id' => 227,
                'type' => 'retail-store',
                'name' => 'মায়ের দোয়া সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-01-26 20:49:51',
                'updated_at' => '2026-01-26 20:49:51',
            ),
            145 => 
            array (
                'id' => 228,
                'type' => 'retail-store',
                'name' => 'রিপন সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-01-26 20:51:44',
                'updated_at' => '2026-01-26 20:51:44',
            ),
            146 => 
            array (
                'id' => 305,
                'type' => 'factory',
                'name' => 'মোসাঃ সুফিয়া',
                'deleted_at' => NULL,
                'created_at' => '2026-01-27 21:07:11',
                'updated_at' => '2026-01-27 21:07:11',
            ),
            147 => 
            array (
                'id' => 229,
                'type' => 'retail-store',
                'name' => 'রত্না সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-01-29 18:58:57',
                'updated_at' => '2026-01-29 18:58:57',
            ),
            148 => 
            array (
                'id' => 30,
                'type' => 'employee',
                'name' => 'রাব্বি',
                'deleted_at' => NULL,
                'created_at' => '2026-01-31 12:08:27',
                'updated_at' => '2026-01-31 12:08:27',
            ),
            149 => 
            array (
                'id' => 31,
                'type' => 'employee',
                'name' => 'Sakibul',
                'deleted_at' => NULL,
                'created_at' => '2026-01-31 12:10:25',
                'updated_at' => '2026-01-31 12:10:25',
            ),
            150 => 
            array (
                'id' => 230,
                'type' => 'retail-store',
                'name' => 'রাফিন সু হাউস',
                'deleted_at' => NULL,
                'created_at' => '2026-01-31 14:05:04',
                'updated_at' => '2026-01-31 14:05:04',
            ),
            151 => 
            array (
                'id' => 306,
                'type' => 'factory',
                'name' => 'মোসাঃ লামিছা',
                'deleted_at' => NULL,
                'created_at' => '2026-02-02 11:47:06',
                'updated_at' => '2026-02-02 11:47:06',
            ),
            152 => 
            array (
                'id' => 231,
                'type' => 'retail-store',
                'name' => 'শিহাব সু স্টোর',
                'deleted_at' => NULL,
                'created_at' => '2026-02-02 17:39:15',
                'updated_at' => '2026-02-02 17:39:15',
            ),
            153 => 
            array (
                'id' => 232,
                'type' => 'retail-store',
                'name' => 'ফাতেমা সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-02-03 13:34:49',
                'updated_at' => '2026-02-03 13:34:49',
            ),
            154 => 
            array (
                'id' => 233,
                'type' => 'retail-store',
                'name' => 'রুবেল সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-02-04 21:20:58',
                'updated_at' => '2026-02-04 21:20:58',
            ),
            155 => 
            array (
                'id' => 234,
                'type' => 'retail-store',
                'name' => 'ড্রীম সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-02-05 12:19:50',
                'updated_at' => '2026-02-05 12:19:50',
            ),
            156 => 
            array (
                'id' => 235,
                'type' => 'retail-store',
                'name' => 'সুজ গ্যালারী',
                'deleted_at' => NULL,
                'created_at' => '2026-02-05 16:59:56',
                'updated_at' => '2026-02-05 16:59:56',
            ),
            157 => 
            array (
                'id' => 236,
                'type' => 'retail-store',
                'name' => 'আবু হুরায়রা সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-02-07 11:51:27',
                'updated_at' => '2026-02-07 11:51:27',
            ),
            158 => 
            array (
                'id' => 18,
                'type' => 'gift-supplier',
                'name' => 'নগর ফ্যাশন গেঞ্জি',
                'deleted_at' => NULL,
                'created_at' => '2026-02-07 14:07:47',
                'updated_at' => '2026-02-07 14:07:47',
            ),
            159 => 
            array (
                'id' => 237,
                'type' => 'retail-store',
                'name' => 'ইউনিক সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-02-07 20:44:47',
                'updated_at' => '2026-02-07 20:44:47',
            ),
            160 => 
            array (
                'id' => 238,
                'type' => 'retail-store',
                'name' => 'বাটা সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-02-08 15:12:06',
                'updated_at' => '2026-02-08 15:12:06',
            ),
            161 => 
            array (
                'id' => 307,
                'type' => 'factory',
                'name' => 'টিটি লেদার',
                'deleted_at' => NULL,
                'created_at' => '2026-02-09 00:33:03',
                'updated_at' => '2026-02-09 00:33:03',
            ),
            162 => 
            array (
                'id' => 239,
                'type' => 'retail-store',
                'name' => 'পায়ে পায়ে সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-02-09 02:15:12',
                'updated_at' => '2026-02-09 02:15:12',
            ),
            163 => 
            array (
                'id' => 240,
                'type' => 'retail-store',
                'name' => 'গ্রামীণ সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-02-14 11:56:17',
                'updated_at' => '2026-02-14 11:56:17',
            ),
            164 => 
            array (
                'id' => 241,
                'type' => 'retail-store',
                'name' => 'মিডলী সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-02-14 12:01:45',
                'updated_at' => '2026-02-14 12:01:45',
            ),
            165 => 
            array (
                'id' => 242,
                'type' => 'retail-store',
                'name' => 'রাজ সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-02-16 16:44:03',
                'updated_at' => '2026-02-16 16:44:03',
            ),
            166 => 
            array (
                'id' => 243,
                'type' => 'retail-store',
                'name' => 'লেদার গ্যালারী',
                'deleted_at' => NULL,
                'created_at' => '2026-02-16 21:58:13',
                'updated_at' => '2026-02-16 21:58:13',
            ),
            167 => 
            array (
                'id' => 308,
                'type' => 'factory',
                'name' => 'মোঃ সাব্বির',
                'deleted_at' => NULL,
                'created_at' => '2026-02-17 16:54:40',
                'updated_at' => '2026-02-17 16:54:40',
            ),
            168 => 
            array (
                'id' => 244,
                'type' => 'retail-store',
                'name' => 'রনি সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-02-18 16:37:35',
                'updated_at' => '2026-02-18 16:37:35',
            ),
            169 => 
            array (
                'id' => 245,
                'type' => 'retail-store',
                'name' => 'আনোয়ার সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-02-19 01:39:35',
                'updated_at' => '2026-02-19 01:39:35',
            ),
            170 => 
            array (
                'id' => 49,
                'type' => 'expense',
            'name' => 'Transport (Prince)',
                'deleted_at' => NULL,
                'created_at' => '2026-02-19 12:20:56',
                'updated_at' => '2026-02-19 12:20:56',
            ),
            171 => 
            array (
                'id' => 246,
                'type' => 'retail-store',
                'name' => 'রাজু সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-02-19 13:03:30',
                'updated_at' => '2026-02-19 13:03:30',
            ),
            172 => 
            array (
                'id' => 247,
                'type' => 'retail-store',
                'name' => 'সোহেল সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-02-19 21:54:19',
                'updated_at' => '2026-02-19 21:54:19',
            ),
            173 => 
            array (
                'id' => 248,
                'type' => 'retail-store',
                'name' => 'বিসমিল্লাহ্‌ সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-02-20 17:06:53',
                'updated_at' => '2026-02-20 17:06:53',
            ),
            174 => 
            array (
                'id' => 249,
                'type' => 'retail-store',
                'name' => 'শহিদুল সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-02-21 12:12:56',
                'updated_at' => '2026-02-21 12:12:56',
            ),
            175 => 
            array (
                'id' => 250,
                'type' => 'retail-store',
                'name' => 'আমিন সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-02-21 19:08:46',
                'updated_at' => '2026-02-21 19:08:46',
            ),
            176 => 
            array (
                'id' => 309,
                'type' => 'factory',
                'name' => 'মোঃ মাহিন',
                'deleted_at' => NULL,
                'created_at' => '2026-02-22 21:39:19',
                'updated_at' => '2026-02-22 21:39:19',
            ),
            177 => 
            array (
                'id' => 310,
                'type' => 'factory',
                'name' => 'ইয়ামিন',
                'deleted_at' => NULL,
                'created_at' => '2026-02-25 01:23:45',
                'updated_at' => '2026-02-25 01:23:45',
            ),
            178 => 
            array (
                'id' => 5,
                'type' => 'loan',
                'name' => 'রবিউল',
                'deleted_at' => NULL,
                'created_at' => '2026-02-25 12:46:38',
                'updated_at' => '2026-02-25 12:46:38',
            ),
            179 => 
            array (
                'id' => 251,
                'type' => 'retail-store',
                'name' => 'বারেক সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-02-25 16:18:39',
                'updated_at' => '2026-02-25 16:18:39',
            ),
            180 => 
            array (
                'id' => 252,
                'type' => 'retail-store',
                'name' => 'আয়াশ সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-02-26 13:55:33',
                'updated_at' => '2026-02-26 13:55:33',
            ),
            181 => 
            array (
                'id' => 253,
                'type' => 'retail-store',
                'name' => 'মায়ের দোয়া',
                'deleted_at' => NULL,
                'created_at' => '2026-02-26 14:52:49',
                'updated_at' => '2026-02-26 14:52:49',
            ),
            182 => 
            array (
                'id' => 254,
                'type' => 'retail-store',
                'name' => 'ভাই বোন সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-02-28 17:27:37',
                'updated_at' => '2026-02-28 17:27:37',
            ),
            183 => 
            array (
                'id' => 255,
                'type' => 'retail-store',
                'name' => 'অভিযাত্রী সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-03-02 13:51:08',
                'updated_at' => '2026-03-02 13:51:08',
            ),
            184 => 
            array (
                'id' => 256,
                'type' => 'retail-store',
                'name' => 'সেলিম সু স্টোর',
                'deleted_at' => NULL,
                'created_at' => '2026-03-04 16:02:40',
                'updated_at' => '2026-03-04 16:02:40',
            ),
            185 => 
            array (
                'id' => 257,
                'type' => 'retail-store',
                'name' => 'ইউনিক সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-03-05 02:42:37',
                'updated_at' => '2026-03-05 02:42:37',
            ),
            186 => 
            array (
                'id' => 258,
                'type' => 'retail-store',
                'name' => 'শাকিল সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-03-05 11:44:22',
                'updated_at' => '2026-03-05 11:44:22',
            ),
            187 => 
            array (
                'id' => 259,
                'type' => 'retail-store',
                'name' => 'পারভেজ সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-03-07 12:24:17',
                'updated_at' => '2026-03-07 12:24:17',
            ),
            188 => 
            array (
                'id' => 260,
                'type' => 'retail-store',
                'name' => 'আল রাফি সু স্টোর',
                'deleted_at' => NULL,
                'created_at' => '2026-03-07 22:16:06',
                'updated_at' => '2026-03-07 22:16:06',
            ),
            189 => 
            array (
                'id' => 261,
                'type' => 'retail-store',
                'name' => 'আয়শা সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-03-09 19:48:01',
                'updated_at' => '2026-03-09 19:48:01',
            ),
            190 => 
            array (
                'id' => 262,
                'type' => 'retail-store',
                'name' => 'ফারিয়া সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-03-09 20:51:52',
                'updated_at' => '2026-03-09 20:51:52',
            ),
            191 => 
            array (
                'id' => 263,
                'type' => 'retail-store',
                'name' => 'রবিউল সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-03-11 15:59:13',
                'updated_at' => '2026-03-11 15:59:13',
            ),
            192 => 
            array (
                'id' => 264,
                'type' => 'retail-store',
                'name' => 'ইতি সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-03-11 17:42:13',
                'updated_at' => '2026-03-11 17:42:13',
            ),
            193 => 
            array (
                'id' => 265,
                'type' => 'retail-store',
                'name' => 'Grameen Shoes',
                'deleted_at' => NULL,
                'created_at' => '2026-03-11 21:23:22',
                'updated_at' => '2026-03-11 21:23:22',
            ),
            194 => 
            array (
                'id' => 266,
                'type' => 'retail-store',
                'name' => 'Nipu Shoes',
                'deleted_at' => NULL,
                'created_at' => '2026-03-11 21:37:23',
                'updated_at' => '2026-03-11 21:37:23',
            ),
            195 => 
            array (
                'id' => 267,
                'type' => 'retail-store',
                'name' => 'S Barmij Shoes',
                'deleted_at' => NULL,
                'created_at' => '2026-03-12 17:36:25',
                'updated_at' => '2026-03-12 17:36:25',
            ),
            196 => 
            array (
                'id' => 268,
                'type' => 'retail-store',
                'name' => 'S Barmij Shoes',
                'deleted_at' => NULL,
                'created_at' => '2026-03-12 17:39:49',
                'updated_at' => '2026-03-12 17:39:49',
            ),
            197 => 
            array (
                'id' => 269,
                'type' => 'retail-store',
                'name' => 'হোসেন সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-03-12 20:56:11',
                'updated_at' => '2026-03-12 20:56:11',
            ),
            198 => 
            array (
                'id' => 270,
                'type' => 'retail-store',
                'name' => 'নাজ সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-03-12 21:07:04',
                'updated_at' => '2026-03-12 21:07:04',
            ),
            199 => 
            array (
                'id' => 271,
                'type' => 'retail-store',
                'name' => 'Mayer dowa Shoes',
                'deleted_at' => NULL,
                'created_at' => '2026-03-12 22:20:23',
                'updated_at' => '2026-03-12 22:20:23',
            ),
            200 => 
            array (
                'id' => 272,
                'type' => 'retail-store',
                'name' => 'Shahin shoes',
                'deleted_at' => NULL,
                'created_at' => '2026-03-12 22:30:19',
                'updated_at' => '2026-03-12 22:30:19',
            ),
            201 => 
            array (
                'id' => 273,
                'type' => 'retail-store',
                'name' => 'রুপা সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-03-13 22:18:04',
                'updated_at' => '2026-03-13 22:18:04',
            ),
            202 => 
            array (
                'id' => 274,
                'type' => 'retail-store',
                'name' => 'চয়েজ সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-03-14 22:48:44',
                'updated_at' => '2026-03-14 22:48:44',
            ),
            203 => 
            array (
                'id' => 275,
                'type' => 'retail-store',
                'name' => 'চয়েজ সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-03-15 16:15:23',
                'updated_at' => '2026-03-15 16:15:23',
            ),
            204 => 
            array (
                'id' => 32,
                'type' => 'employee',
                'name' => 'Md Rabbi',
                'deleted_at' => NULL,
                'created_at' => '2026-03-16 03:07:03',
                'updated_at' => '2026-03-16 03:07:03',
            ),
            205 => 
            array (
                'id' => 33,
                'type' => 'employee',
                'name' => 'Md Akib',
                'deleted_at' => NULL,
                'created_at' => '2026-03-16 03:09:43',
                'updated_at' => '2026-03-16 03:09:43',
            ),
            206 => 
            array (
                'id' => 276,
                'type' => 'retail-store',
                'name' => 'Nipu Shoes',
                'deleted_at' => NULL,
                'created_at' => '2026-04-05 13:48:21',
                'updated_at' => '2026-04-05 13:48:21',
            ),
            207 => 
            array (
                'id' => 277,
                'type' => 'retail-store',
                'name' => 'Dolphin Shoes',
                'deleted_at' => NULL,
                'created_at' => '2026-04-05 13:51:41',
                'updated_at' => '2026-04-05 13:51:41',
            ),
            208 => 
            array (
                'id' => 278,
                'type' => 'retail-store',
                'name' => 'ইউনিক সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-04-22 11:53:04',
                'updated_at' => '2026-04-22 11:53:04',
            ),
            209 => 
            array (
                'id' => 279,
                'type' => 'retail-store',
                'name' => 'তাবাস্সুম জুতা‌ ঘর',
                'deleted_at' => NULL,
                'created_at' => '2026-04-28 18:34:47',
                'updated_at' => '2026-04-28 18:34:47',
            ),
            210 => 
            array (
                'id' => 311,
                'type' => 'factory',
                'name' => 'মোঃ সৈকত',
                'deleted_at' => NULL,
                'created_at' => '2026-04-29 17:13:37',
                'updated_at' => '2026-04-29 17:13:37',
            ),
            211 => 
            array (
                'id' => 280,
                'type' => 'retail-store',
                'name' => 'জুই সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-05-02 16:48:01',
                'updated_at' => '2026-05-02 16:48:01',
            ),
            212 => 
            array (
                'id' => 312,
                'type' => 'factory',
                'name' => 'মোসাঃ ছিফা',
                'deleted_at' => NULL,
                'created_at' => '2026-05-03 13:32:52',
                'updated_at' => '2026-05-03 13:32:52',
            ),
            213 => 
            array (
                'id' => 313,
                'type' => 'factory',
                'name' => 'মোঃ কামরুল',
                'deleted_at' => NULL,
                'created_at' => '2026-05-05 19:53:36',
                'updated_at' => '2026-05-05 19:53:36',
            ),
            214 => 
            array (
                'id' => 314,
                'type' => 'factory',
                'name' => 'মোঃ ওয়াফিকুল ইসলাম',
                'deleted_at' => NULL,
                'created_at' => '2026-05-06 17:12:31',
                'updated_at' => '2026-05-06 17:12:31',
            ),
            215 => 
            array (
                'id' => 281,
                'type' => 'retail-store',
                'name' => 'আবু শাহিন সুজ',
                'deleted_at' => NULL,
                'created_at' => '2026-05-07 18:17:39',
                'updated_at' => '2026-05-07 18:17:39',
            ),
        ));
        
        
    }
}