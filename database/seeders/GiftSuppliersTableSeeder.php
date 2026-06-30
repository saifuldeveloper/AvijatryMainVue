<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class GiftSuppliersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('gift_suppliers')->delete();
        
        \DB::table('gift_suppliers')->insertOrIgnore(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'আমিন চাবির রিং',
                'address' => 'নর্থ সাউথ রোড',
                'mobile_no' => '0',
                'mobile_no_two' => NULL,
                'deleted_by' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2025-04-29 22:59:25',
                'updated_at' => '2025-04-29 22:59:25',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'রহিম গেঞ্জি',
                'address' => 'সাইনবোর্ড',
                'mobile_no' => '01714434889',
                'mobile_no_two' => NULL,
                'deleted_by' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2025-04-29 22:59:53',
                'updated_at' => '2025-07-22 18:07:46',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'কাউসার সাইনবোর্ড',
                'address' => 'পল্টন',
                'mobile_no' => '01821235863',
                'mobile_no_two' => NULL,
                'deleted_by' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2025-04-29 23:01:29',
                'updated_at' => '2025-07-22 18:12:49',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'কালাম প্রেস',
                'address' => 'ফকিরাপুল',
                'mobile_no' => '01712539826',
                'mobile_no_two' => NULL,
                'deleted_by' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2025-04-29 23:01:59',
                'updated_at' => '2025-07-22 18:11:21',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'বাদশাহ ব্যাগ',
                'address' => 'মাদারগঞ্জ',
                'mobile_no' => '01716618761',
                'mobile_no_two' => NULL,
                'deleted_by' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2025-04-29 23:02:32',
                'updated_at' => '2025-07-22 18:10:51',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'সালাউদ্দিন বক্স',
                'address' => 'মারীটলা',
                'mobile_no' => '01711632422',
                'mobile_no_two' => NULL,
                'deleted_by' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2025-04-29 23:03:34',
                'updated_at' => '2025-07-22 18:08:27',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'ব্রাদার্স বক্স',
                'address' => 'কাম্রাঙ্গির চর',
                'mobile_no' => '01724817400',
                'mobile_no_two' => NULL,
                'deleted_by' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2025-04-29 23:04:05',
                'updated_at' => '2025-07-22 18:07:18',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'শাহেদ বক্স',
                'address' => 'পুরান ঢাকা',
                'mobile_no' => '01733523155',
                'mobile_no_two' => NULL,
                'deleted_by' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2025-04-29 23:04:39',
                'updated_at' => '2025-07-22 18:09:42',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'মুন লেভেল',
                'address' => 'ঢাকা',
                'mobile_no' => '01612112214',
                'mobile_no_two' => NULL,
                'deleted_by' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2025-05-20 20:14:37',
                'updated_at' => '2025-07-22 18:10:10',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'হাফিজ বক্স',
                'address' => 'নাজিরা বাজার',
                'mobile_no' => '01740406646',
                'mobile_no_two' => NULL,
                'deleted_by' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2025-07-22 18:05:39',
                'updated_at' => '2025-07-22 18:05:39',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'মিলন চাবির রিং',
                'address' => 'ঢাকা',
                'mobile_no' => 'XXXXXXXXX',
                'mobile_no_two' => NULL,
                'deleted_by' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2025-07-23 12:28:26',
                'updated_at' => '2025-07-23 12:28:26',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'জাকির বক্স',
                'address' => 'ঢাকা',
                'mobile_no' => 'XXXXXXXX',
                'mobile_no_two' => NULL,
                'deleted_by' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2025-07-31 11:50:04',
                'updated_at' => '2025-07-31 11:50:04',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'কাচখোলা ব্যাগ',
                'address' => 'ঢাকা',
                'mobile_no' => 'XXXXXXXXX',
                'mobile_no_two' => NULL,
                'deleted_by' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2025-08-11 12:44:43',
                'updated_at' => '2025-08-11 12:45:04',
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'এস এ এন ব্যাগ',
                'address' => 'ঢাকা',
                'mobile_no' => 'XXXXXXXXX',
                'mobile_no_two' => NULL,
                'deleted_by' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2025-08-12 13:28:27',
                'updated_at' => '2025-08-12 13:28:27',
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'তাহের বক্স',
                'address' => 'কদমতলী চৌরাস্তা, কেরাণীগঞ্জ',
                'mobile_no' => '01881150024',
                'mobile_no_two' => NULL,
                'deleted_by' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2025-11-26 14:11:28',
                'updated_at' => '2025-11-26 14:11:28',
            ),
            15 => 
            array (
                'id' => 16,
            'name' => 'মোঃ রশিদ (ব্যাগ)',
                'address' => 'ঢাকা',
                'mobile_no' => '01',
                'mobile_no_two' => NULL,
                'deleted_by' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2025-12-09 15:54:10',
                'updated_at' => '2025-12-09 15:54:10',
            ),
            16 => 
            array (
                'id' => 17,
            'name' => 'মোঃ হানিফ (গ্যারান্টি বই)',
                'address' => 'ঢাকা',
                'mobile_no' => 'XXXXXXXXXXX',
                'mobile_no_two' => NULL,
                'deleted_by' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2026-01-04 17:22:47',
                'updated_at' => '2026-01-04 17:22:47',
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'নগর ফ্যাশন গেঞ্জি',
                'address' => 'ঢাকা',
                'mobile_no' => 'xxxxxxx',
                'mobile_no_two' => NULL,
                'deleted_by' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2026-02-07 14:07:47',
                'updated_at' => '2026-02-07 14:07:47',
            ),
        ));
        
        
    }
}