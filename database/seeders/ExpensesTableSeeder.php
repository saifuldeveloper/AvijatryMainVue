<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ExpensesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('expenses')->delete();
        
        \DB::table('expenses')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'A H M Taslim Uddin',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-15 17:45:19',
                'updated_at' => '2025-04-15 17:45:19',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Md Rafael',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-15 17:45:54',
                'updated_at' => '2025-04-15 17:45:54',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Shawlie',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-15 17:46:10',
                'updated_at' => '2025-04-16 00:43:20',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Warehouse Rent',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-15 17:46:52',
                'updated_at' => '2025-04-15 17:46:52',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Zakat',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-15 17:48:06',
                'updated_at' => '2025-04-15 17:48:06',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Staff Bonus',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-15 17:48:43',
                'updated_at' => '2025-04-16 00:41:33',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Warehouse Expense',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-15 17:49:01',
                'updated_at' => '2025-04-15 18:00:31',
            ),
            7 => 
            array (
                'id' => 8,
            'name' => 'Shopping (Basa)',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-15 17:49:24',
                'updated_at' => '2025-04-15 18:18:17',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Car Maintenance',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-15 17:51:25',
                'updated_at' => '2025-04-15 17:51:52',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'Monthly Dues',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-15 17:53:59',
                'updated_at' => '2025-04-15 17:53:59',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'Newspaper Bill',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-15 17:54:15',
                'updated_at' => '2025-04-15 17:54:15',
            ),
            11 => 
            array (
                'id' => 13,
                'name' => 'Telephone Bill',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-15 17:55:51',
                'updated_at' => '2025-04-15 17:55:51',
            ),
            12 => 
            array (
                'id' => 14,
                'name' => 'House Rent',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-15 17:56:08',
                'updated_at' => '2025-04-15 17:56:08',
            ),
            13 => 
            array (
                'id' => 15,
                'name' => 'Electricity Bill',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-15 17:56:21',
                'updated_at' => '2025-04-15 18:17:15',
            ),
            14 => 
            array (
                'id' => 16,
            'name' => 'House Rent (Staff)',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-15 17:56:44',
                'updated_at' => '2025-04-15 17:56:44',
            ),
            15 => 
            array (
                'id' => 17,
                'name' => 'Transport Expense',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-15 17:56:56',
                'updated_at' => '2025-04-15 18:00:17',
            ),
            16 => 
            array (
                'id' => 18,
                'name' => 'Jolap',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-15 17:57:05',
                'updated_at' => '2025-04-15 17:57:05',
            ),
            17 => 
            array (
                'id' => 19,
            'name' => 'Mobile card (Basa)',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-15 17:57:23',
                'updated_at' => '2025-04-15 17:57:35',
            ),
            18 => 
            array (
                'id' => 20,
                'name' => 'Advertisement Expense',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-15 17:57:50',
                'updated_at' => '2025-04-15 17:59:47',
            ),
            19 => 
            array (
                'id' => 21,
                'name' => 'Miscellaneous Expenses',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-15 17:58:12',
                'updated_at' => '2025-04-15 18:18:58',
            ),
            20 => 
            array (
                'id' => 22,
                'name' => 'Daily Tiffin',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-15 17:58:29',
                'updated_at' => '2025-04-15 17:58:29',
            ),
            21 => 
            array (
                'id' => 23,
                'name' => 'Party Expense',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-15 17:59:36',
                'updated_at' => '2025-04-15 17:59:36',
            ),
            22 => 
            array (
                'id' => 24,
                'name' => 'Comilla Association',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-15 18:02:49',
                'updated_at' => '2025-04-16 00:37:23',
            ),
            23 => 
            array (
                'id' => 25,
            'name' => 'Electricity Bill (Basa)',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-15 18:03:18',
                'updated_at' => '2025-04-15 18:03:18',
            ),
            24 => 
            array (
                'id' => 26,
            'name' => 'Taslim Mama (Loan)',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-15 18:06:12',
                'updated_at' => '2025-04-15 18:06:12',
            ),
            25 => 
            array (
                'id' => 27,
            'name' => 'Staff Bonus (Last)',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-15 18:07:51',
                'updated_at' => '2025-04-16 00:43:10',
            ),
            26 => 
            array (
                'id' => 28,
                'name' => 'VAT Office',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-15 18:09:22',
                'updated_at' => '2025-04-15 18:09:22',
            ),
            27 => 
            array (
                'id' => 29,
                'name' => 'Software Expense',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-15 18:10:26',
                'updated_at' => '2025-04-15 18:10:26',
            ),
            28 => 
            array (
                'id' => 30,
                'name' => 'Flat Booking',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-15 18:10:47',
                'updated_at' => '2025-04-15 18:10:47',
            ),
            29 => 
            array (
                'id' => 31,
                'name' => 'Iqbal Mama',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-15 18:11:37',
                'updated_at' => '2025-04-15 18:11:37',
            ),
            30 => 
            array (
                'id' => 33,
            'name' => 'Sadrul (Marketing)',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-15 18:12:57',
                'updated_at' => '2025-04-15 18:12:57',
            ),
            31 => 
            array (
                'id' => 35,
                'name' => 'Internet Bill',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-04-16 02:09:37',
                'updated_at' => '2025-04-16 02:09:37',
            ),
            32 => 
            array (
                'id' => 36,
                'name' => 'Donate',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-06-23 10:56:51',
                'updated_at' => '2025-06-23 10:56:51',
            ),
            33 => 
            array (
                'id' => 37,
                'name' => 'Snacks',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-06-23 11:18:01',
                'updated_at' => '2025-06-23 11:18:01',
            ),
            34 => 
            array (
                'id' => 38,
                'name' => 'Tagada Expense',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-07-22 14:01:06',
                'updated_at' => '2025-08-02 13:58:37',
            ),
            35 => 
            array (
                'id' => 39,
                'name' => 'Mitul',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-07-22 20:51:06',
                'updated_at' => '2025-07-22 20:51:06',
            ),
            36 => 
            array (
                'id' => 40,
                'name' => 'Shop Rent',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-07-22 20:55:59',
                'updated_at' => '2025-07-22 20:55:59',
            ),
            37 => 
            array (
                'id' => 41,
                'name' => 'Avijatry Khulna',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-08-04 11:58:46',
                'updated_at' => '2025-08-04 11:58:46',
            ),
            38 => 
            array (
                'id' => 42,
                'name' => 'Avijatry Cumilla',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-08-04 11:58:57',
                'updated_at' => '2025-08-04 11:58:57',
            ),
            39 => 
            array (
                'id' => 43,
                'name' => 'Shop Expense',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-08-11 12:07:32',
                'updated_at' => '2025-08-11 12:07:32',
            ),
            40 => 
            array (
                'id' => 44,
                'name' => 'EMI',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-08-12 12:39:54',
                'updated_at' => '2025-08-12 12:39:54',
            ),
            41 => 
            array (
                'id' => 45,
                'name' => 'warehouse electricity bill',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-10-23 17:13:45',
                'updated_at' => '2025-10-23 17:13:45',
            ),
            42 => 
            array (
                'id' => 46,
                'name' => 'Tax',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-10-27 11:17:19',
                'updated_at' => '2025-10-27 11:17:19',
            ),
            43 => 
            array (
                'id' => 47,
                'name' => 'Party Mamla',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-10-27 12:40:28',
                'updated_at' => '2025-10-27 12:40:28',
            ),
            44 => 
            array (
                'id' => 48,
            'name' => 'Driver(Rafael)',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2025-11-30 15:43:11',
                'updated_at' => '2025-12-02 13:00:12',
            ),
            45 => 
            array (
                'id' => 49,
            'name' => 'Transport (Prince)',
                'type' => NULL,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'created_at' => '2026-02-19 12:20:56',
                'updated_at' => '2026-02-19 12:20:56',
            ),
        ));
        
        
    }
}