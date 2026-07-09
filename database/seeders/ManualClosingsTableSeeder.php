<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ManualClosingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('manual_closings')->delete();
        
        \DB::table('manual_closings')->insertOrIgnore(array (
            0 => 
            array (
                'id' => 1,
                'account_book_id' => 386,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "NimCDdFahEw8H0S1G5dm4i2vBr0nn58iMhHIqeRM", "payment": [{"amount": "52000.00", "method": "1"}], "commission": "12745.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-05-12", "closing_type": "manual", "input-remaining": "52000.00", "input-total-paid": "52000.00", "input-total-expense": null, "input-total-payable": "64745", "input-total-payment": "339000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "403745", "input-total-remaining": "0.00", "input-total-transport": null, "input-previous-balance": null, "input-total-commission": "403745", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": "576780", "commision-percentage-show": null, "input-commission-deducted": "52000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-12 16:09:07',
                'updated_at' => '2025-05-12 16:09:07',
            ),
            1 => 
            array (
                'id' => 2,
                'account_book_id' => 932,
                'account_type' => 'factory',
                'closing_data' => '{"staff": "6600.00", "_token": "x1uiSw3jZoeI5v71Ds6WyrLkmFMLhTnGEoN73TKH", "cheque": [{"id": "0", "amount": "40000.00", "due_date": "2025-07-30"}, {"id": "0", "amount": "30000.00", "due_date": "2025-11-30"}], "payment": [{"amount": "89000.00", "method": "1"}], "commission": "10000.00", "closing_date": "2025-05-12", "closing_type": "manual", "input-return": null, "input-payment": "140000", "input-purchase": "315600", "input-remaining": "159000.00", "input-total-paid": "89000.00", "input-total-payable": "175600", "comminsion-percentage": "3.17", "input-total-remaining": "0.00", "input-commission-deducted": "165600.00", "staff-comminsion-percentage": "2.09"}',
                'created_at' => '2025-05-12 19:48:07',
                'updated_at' => '2025-05-12 19:48:07',
            ),
            2 => 
            array (
                'id' => 3,
                'account_book_id' => 321,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "20000.00", "_token": "x1uiSw3jZoeI5v71Ds6WyrLkmFMLhTnGEoN73TKH", "payment": [{"amount": "1215000.00", "method": "1"}], "commission": "78100.00", "commitment": [{"date": "2025-05-20", "amount": "500000.00"}], "closing_date": "2025-05-12", "closing_type": "manual", "input-remaining": "1715000.00", "input-total-paid": "1215000.00", "input-total-expense": null, "input-total-payable": "1813100", "input-total-payment": "611000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "2444170", "input-total-remaining": "0.00", "input-total-transport": null, "input-previous-balance": "0", "input-total-commission": "2424100", "input-total-commitment": "500000.00", "input-return-commission": "20070", "input-sale-no-commission": "3394265", "commision-percentage-show": null, "input-commission-deducted": "1735000.00", "input-total-no-commission": null, "input-return-no-commission": "14355", "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": "0", "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-12 23:21:20',
                'updated_at' => '2025-05-12 23:21:20',
            ),
            3 => 
            array (
                'id' => 4,
                'account_book_id' => 254,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "CoLLApKGSwE4QTuq5oMJGTj3S3ya2rkjywVrll40", "payment": [{"amount": "2330000.00", "method": "1"}], "commission": "335621.00", "commitment": [{"date": "2025-05-30", "amount": "500000.00"}, {"date": "2025-06-30", "amount": "500000.00"}, {"date": "2025-07-30", "amount": "500000.00"}, {"date": "2025-08-30", "amount": "500000.00"}, {"date": "2025-09-30", "amount": "500000.00"}, {"date": "2025-10-30", "amount": "500000.00"}], "closing_date": "2025-05-13", "closing_type": "manual", "input-remaining": "5330000.00", "input-total-paid": "2330000.00", "input-total-expense": null, "input-total-payable": "5665621", "input-total-payment": "1305000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "7126797", "input-total-remaining": "0.00", "input-total-transport": null, "input-previous-balance": "0", "input-total-commission": "6970621", "input-total-commitment": "3000000.00", "input-return-commission": "156176", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "5330000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-13 14:57:31',
                'updated_at' => '2025-05-13 14:57:31',
            ),
            4 => 
            array (
                'id' => 5,
                'account_book_id' => 955,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "40000.00", "_token": "CoLLApKGSwE4QTuq5oMJGTj3S3ya2rkjywVrll40", "payment": [{"amount": "1320000.00", "method": "1"}, {"amount": "200000.00", "method": "10"}, {"amount": "100000.00", "method": "1"}], "commission": "236532.00", "commitment": [{"date": "2025-05-30", "amount": "100000.00"}, {"date": "2025-06-15", "amount": "100000.00"}, {"date": "2025-06-30", "amount": "100000.00"}, {"date": "2025-07-15", "amount": "100000.00"}, {"date": "2025-07-30", "amount": "100000.00"}, {"date": "2025-08-15", "amount": "100000.00"}, {"date": "2025-08-30", "amount": "100000.00"}, {"date": "2025-09-15", "amount": "100000.00"}, {"date": "2025-09-30", "amount": "100000.00"}, {"date": "2025-10-15", "amount": "100000.00"}], "closing_date": "2025-05-13", "closing_type": "manual", "input-remaining": "2620000.00", "input-total-paid": "1620000.00", "input-total-expense": null, "input-total-payable": "2896532", "input-total-payment": "500000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "3115458", "input-total-remaining": "0.00", "input-total-transport": null, "input-previous-balance": null, "input-total-commission": "3396532", "input-total-commitment": "1000000.00", "input-return-commission": "0", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "2660000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-13 15:03:29',
                'updated_at' => '2025-05-13 15:03:29',
            ),
            5 => 
            array (
                'id' => 6,
                'account_book_id' => 958,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "CoLLApKGSwE4QTuq5oMJGTj3S3ya2rkjywVrll40", "payment": [{"amount": "730000.00", "method": "1"}], "commission": "128314.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-05-13", "closing_type": "manual", "input-remaining": "730000.00", "input-total-paid": "730000.00", "input-total-expense": null, "input-total-payable": "858314", "input-total-payment": "1060000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "1926256", "input-total-remaining": "0.00", "input-total-transport": null, "input-previous-balance": null, "input-total-commission": "1918316", "input-total-commitment": null, "input-return-commission": "7940", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "730000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-13 16:26:08',
                'updated_at' => '2025-05-13 16:26:08',
            ),
            6 => 
            array (
                'id' => 7,
                'account_book_id' => 331,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "SGdqdiQvBisE8XziSJgNveBofhqx9ex9TTIKkVOs", "payment": [{"amount": "235000.00", "method": "1"}], "commission": "13766.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-05-14", "closing_type": "manual", "input-remaining": "235000.00", "input-total-paid": "235000.00", "input-total-expense": null, "input-total-payable": "248766", "input-total-payment": "60500", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "311126", "input-total-remaining": "0.00", "input-total-transport": null, "input-previous-balance": null, "input-total-commission": "311126", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "235000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-14 16:19:26',
                'updated_at' => '2025-05-14 16:19:26',
            ),
            7 => 
            array (
                'id' => 8,
                'account_book_id' => 963,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "SGdqdiQvBisE8XziSJgNveBofhqx9ex9TTIKkVOs", "payment": [{"amount": "39000.00", "method": "1"}], "commission": "6058.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-05-14", "closing_type": "manual", "input-remaining": "39000.00", "input-total-paid": "39000.00", "input-total-expense": null, "input-total-payable": "45058", "input-total-payment": "94500", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "146448", "input-total-remaining": "0.00", "input-total-transport": null, "input-previous-balance": "0", "input-total-commission": "139558", "input-total-commitment": null, "input-return-commission": "6890", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "39000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-14 16:22:09',
                'updated_at' => '2025-05-14 16:22:09',
            ),
            8 => 
            array (
                'id' => 9,
                'account_book_id' => 241,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "SGdqdiQvBisE8XziSJgNveBofhqx9ex9TTIKkVOs", "payment": [{"amount": "900000.00", "method": "1"}, {"amount": "100000.00", "method": "4"}], "commission": "64063.00", "commitment": [{"date": "2025-08-04", "amount": "180000.00"}], "closing_date": "2025-05-14", "closing_type": "manual", "input-remaining": "1180000.00", "input-total-paid": "1000000.00", "input-total-expense": null, "input-total-payable": "1244063", "input-total-payment": "300000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "1551530", "input-total-remaining": "0.00", "input-total-transport": null, "input-previous-balance": "0", "input-total-commission": "1544063", "input-total-commitment": "180000.00", "input-return-commission": "7467", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "1180000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-14 16:25:13',
                'updated_at' => '2025-05-14 16:25:13',
            ),
            9 => 
            array (
                'id' => 10,
                'account_book_id' => 305,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "SGdqdiQvBisE8XziSJgNveBofhqx9ex9TTIKkVOs", "payment": [{"amount": "300000.00", "method": "1"}], "commission": "45287.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-05-14", "closing_type": "manual", "input-remaining": "300000.00", "input-total-paid": "300000.00", "input-total-expense": null, "input-total-payable": "345287", "input-total-payment": "40000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "385902", "input-total-remaining": "0.00", "input-total-transport": null, "input-previous-balance": "0", "input-total-commission": "385287", "input-total-commitment": null, "input-return-commission": "615", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "300000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-14 16:27:09',
                'updated_at' => '2025-05-14 16:27:09',
            ),
            10 => 
            array (
                'id' => 11,
                'account_book_id' => 355,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "SGdqdiQvBisE8XziSJgNveBofhqx9ex9TTIKkVOs", "payment": [{"amount": "220000.00", "method": "1"}], "commission": "23530.00", "commitment": [{"date": "2025-05-30", "amount": "200000.00"}], "closing_date": "2025-05-14", "closing_type": "manual", "input-remaining": "420000.00", "input-total-paid": "220000.00", "input-total-expense": null, "input-total-payable": "443530", "input-total-payment": "190000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "634975", "input-total-remaining": "0.00", "input-total-transport": null, "input-previous-balance": null, "input-total-commission": "633530", "input-total-commitment": "200000.00", "input-return-commission": "1445", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "420000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-14 16:29:32',
                'updated_at' => '2025-05-14 16:29:32',
            ),
            11 => 
            array (
                'id' => 12,
                'account_book_id' => 343,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "SGdqdiQvBisE8XziSJgNveBofhqx9ex9TTIKkVOs", "payment": [{"amount": "400000.00", "method": "1"}], "commission": "0.00", "commitment": [{"date": "2025-07-10", "amount": "285979.00"}], "closing_date": "2025-05-14", "closing_type": "manual", "input-remaining": "685979.00", "input-total-paid": "400000.00", "input-total-expense": null, "input-total-payable": "685979", "input-total-payment": "190000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "882780", "input-total-remaining": "0.00", "input-total-transport": null, "input-previous-balance": "0", "input-total-commission": "875979", "input-total-commitment": "285979.00", "input-return-commission": "6801", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "685979.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-14 16:33:25',
                'updated_at' => '2025-05-14 16:33:25',
            ),
            12 => 
            array (
                'id' => 13,
                'account_book_id' => 263,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "52000.00", "_token": "SGdqdiQvBisE8XziSJgNveBofhqx9ex9TTIKkVOs", "payment": [{"amount": "2000000.00", "method": "4"}], "commission": "175623.00", "commitment": [{"date": "2025-07-10", "amount": "1270000.00"}], "closing_date": "2025-05-14", "closing_type": "manual", "input-remaining": "3270000.00", "input-total-paid": "2000000.00", "input-total-expense": null, "input-total-payable": "3497623", "input-total-payment": "900000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "4496367", "input-total-remaining": "0.00", "input-total-transport": null, "input-previous-balance": "0", "input-total-commission": "4397623", "input-total-commitment": "1270000.00", "input-return-commission": "98744", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "3322000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-14 19:59:22',
                'updated_at' => '2025-05-14 19:59:22',
            ),
            13 => 
            array (
                'id' => 14,
                'account_book_id' => 116,
                'account_type' => 'factory',
                'closing_data' => '{"staff": "12000.00", "_token": "SGdqdiQvBisE8XziSJgNveBofhqx9ex9TTIKkVOs", "cheque": [{"id": "983", "amount": "90000.00", "due_date": "2025-07-30"}, {"id": "984", "amount": "80000.00", "due_date": "2025-11-30"}], "payment": [{"amount": "168000.00", "method": "8"}], "commission": "18450.00", "closing_date": "2025-05-14", "closing_type": "manual", "input-return": null, "input-payment": "225850", "input-purchase": "594300", "input-remaining": "338000.00", "input-total-paid": "168000.00", "input-total-payable": "368450", "comminsion-percentage": "3.10", "input-total-remaining": "0.00", "input-commission-deducted": "350000.00", "staff-comminsion-percentage": "2.02"}',
                'created_at' => '2025-05-14 20:41:43',
                'updated_at' => '2025-05-14 20:41:43',
            ),
            14 => 
            array (
                'id' => 15,
                'account_book_id' => 238,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "5ORyHuUQZSQY2Zb493xbEoHBIpbtuPHEhLGg1pwH", "payment": [{"amount": "1700000.00", "method": "2"}, {"amount": "100000.00", "method": "2"}, {"amount": "200000.00", "method": "1"}], "commission": "0.00", "commitment": [{"date": "2025-07-10", "amount": "1405554.00"}], "closing_date": "2025-05-15", "closing_type": "manual", "input-remaining": "3405554.00", "input-total-paid": "2000000.00", "input-total-expense": null, "input-total-payable": "3405554", "input-total-payment": "2985000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "6407787", "input-total-remaining": "0.00", "input-total-transport": null, "input-previous-balance": "0", "input-total-commission": "6390554", "input-total-commitment": "1405554.00", "input-return-commission": "17233", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "3405554.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-15 14:24:50',
                'updated_at' => '2025-05-15 14:24:50',
            ),
            15 => 
            array (
                'id' => 16,
                'account_book_id' => 975,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "5ORyHuUQZSQY2Zb493xbEoHBIpbtuPHEhLGg1pwH", "payment": [{"amount": "2150000.00", "method": "2"}], "commission": "180440.00", "commitment": [{"date": "2025-06-30", "amount": "1000000.00"}], "closing_date": "2025-05-15", "closing_type": "manual", "input-remaining": "3150000.00", "input-total-paid": "2150000.00", "input-total-expense": null, "input-total-payable": "3330440", "input-total-payment": "300000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "3670340", "input-total-remaining": "0.00", "input-total-transport": null, "input-previous-balance": "0", "input-total-commission": "3630440", "input-total-commitment": "1000000.00", "input-return-commission": "39900", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "3150000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-15 19:49:24',
                'updated_at' => '2025-05-15 19:49:24',
            ),
            16 => 
            array (
                'id' => 17,
                'account_book_id' => 403,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "5ORyHuUQZSQY2Zb493xbEoHBIpbtuPHEhLGg1pwH", "payment": [{"amount": "56000.00", "method": "1"}, {"amount": "50000.00", "method": "1"}], "commission": "4082.00", "commitment": [{"date": "2025-05-10", "amount": "50000.00"}], "closing_date": "2025-05-15", "closing_type": "manual", "input-remaining": "106000.00", "input-total-paid": "106000.00", "input-total-expense": null, "input-total-payable": "110082", "input-total-payment": "100000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "210082", "input-total-remaining": "0.00", "input-total-transport": null, "input-previous-balance": null, "input-total-commission": "210082", "input-total-commitment": null, "input-return-commission": "0", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "106000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-15 20:49:22',
                'updated_at' => '2025-05-15 20:49:22',
            ),
            17 => 
            array (
                'id' => 18,
                'account_book_id' => 242,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "5ORyHuUQZSQY2Zb493xbEoHBIpbtuPHEhLGg1pwH", "payment": [{"amount": "800000.00", "method": "2"}], "commission": "0.00", "commitment": [{"date": "2025-07-25", "amount": "1064075.00"}], "closing_date": "2025-05-15", "closing_type": "manual", "input-remaining": "1864075.00", "input-total-paid": "800000.00", "input-total-expense": null, "input-total-payable": "1864075", "input-total-payment": "370000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "2250355", "input-total-remaining": "0.00", "input-total-transport": null, "input-previous-balance": null, "input-total-commission": "2234075", "input-total-commitment": "1064075.00", "input-return-commission": "16280", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "1864075.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-15 20:51:31',
                'updated_at' => '2025-05-15 20:51:31',
            ),
            18 => 
            array (
                'id' => 19,
                'account_book_id' => 377,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "OiqRa7r6knGJ16RAal8L7eF1CS76Kiw0bmONVAa5", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": "2025-07-10", "amount": "322102.00"}], "closing_date": "2025-05-16", "closing_type": "manual", "input-remaining": "322102.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "322102", "input-total-payment": "300000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "623678", "input-total-remaining": "0.00", "input-total-transport": null, "input-previous-balance": null, "input-total-commission": "622102", "input-total-commitment": "322102.00", "input-return-commission": "1576", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "322102.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-16 16:26:14',
                'updated_at' => '2025-05-16 16:26:14',
            ),
            19 => 
            array (
                'id' => 20,
                'account_book_id' => 350,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "3000.00", "_token": "OiqRa7r6knGJ16RAal8L7eF1CS76Kiw0bmONVAa5", "payment": [{"amount": "100000.00", "method": "1"}], "commission": "13538.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-05-16", "closing_type": "manual", "input-remaining": "100000.00", "input-total-paid": "100000.00", "input-total-expense": null, "input-total-payable": "116538", "input-total-payment": "230000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "360375", "input-total-remaining": "0.00", "input-total-transport": null, "input-previous-balance": "0", "input-total-commission": "346538", "input-total-commitment": null, "input-return-commission": "13837", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "103000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-16 16:29:11',
                'updated_at' => '2025-05-16 16:29:11',
            ),
            20 => 
            array (
                'id' => 21,
                'account_book_id' => 372,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "OiqRa7r6knGJ16RAal8L7eF1CS76Kiw0bmONVAa5", "payment": [{"amount": "750000.00", "method": "4"}], "commission": "0.00", "commitment": [{"date": "2025-07-20", "amount": "1470000.00"}], "closing_date": "2025-05-16", "closing_type": "manual", "input-remaining": "2220000.00", "input-total-paid": "750000.00", "input-total-expense": null, "input-total-payable": "2220000", "input-total-payment": "1660012", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "3880012", "input-total-remaining": "0.00", "input-total-transport": null, "input-previous-balance": "0", "input-total-commission": "3880012", "input-total-commitment": "1470000.00", "input-return-commission": "0", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "2220000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-16 16:33:15',
                'updated_at' => '2025-05-16 16:33:15',
            ),
            21 => 
            array (
                'id' => 22,
                'account_book_id' => 255,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "52600.00", "_token": "OiqRa7r6knGJ16RAal8L7eF1CS76Kiw0bmONVAa5", "payment": [{"amount": "2030000.00", "method": "10"}], "commission": "250000.00", "commitment": [{"date": "2025-07-25", "amount": "600000.00"}], "closing_date": "2025-05-16", "closing_type": "manual", "input-remaining": "2630000.00", "input-total-paid": "2030000.00", "input-total-expense": null, "input-total-payable": "2932600", "input-total-payment": "759100", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "3714379", "input-total-remaining": "0.00", "input-total-transport": null, "input-previous-balance": "0", "input-total-commission": "3691700", "input-total-commitment": "600000.00", "input-return-commission": "22679", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "2682600.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-16 16:37:57',
                'updated_at' => '2025-05-16 16:37:57',
            ),
            22 => 
            array (
                'id' => 23,
                'account_book_id' => 287,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "OiqRa7r6knGJ16RAal8L7eF1CS76Kiw0bmONVAa5", "payment": [{"amount": "250000.00", "method": "1"}, {"amount": "300000.00", "method": "4"}], "commission": "0.00", "commitment": [{"date": "2025-07-15", "amount": "1.00"}, {"date": "2025-07-15", "amount": "285000.00"}], "closing_date": "2025-05-16", "closing_type": "manual", "input-remaining": "835001.00", "input-total-paid": "550000.00", "input-total-expense": null, "input-total-payable": "835001", "input-total-payment": "350000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "1191052", "input-total-remaining": "0.00", "input-total-transport": null, "input-previous-balance": null, "input-total-commission": "1185001", "input-total-commitment": "285001.00", "input-return-commission": "6040", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "835001.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-16 16:44:48',
                'updated_at' => '2025-05-16 16:44:48',
            ),
            23 => 
            array (
                'id' => 24,
                'account_book_id' => 302,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "10000.00", "_token": "OiqRa7r6knGJ16RAal8L7eF1CS76Kiw0bmONVAa5", "payment": [{"amount": "200000.00", "method": "2"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-05-16", "closing_type": "manual", "input-remaining": "663368.00", "input-total-paid": "200000.00", "input-total-expense": null, "input-total-payable": "673368", "input-total-payment": "0", "input-total-discount": null, "balance_carry_forward": "1", "comminsion-percentage": "0", "input-sale-commission": "673368", "input-total-remaining": "463368.00", "input-total-transport": null, "input-previous-balance": null, "input-total-commission": "673368", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "673368.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": "0", "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-16 16:51:44',
                'updated_at' => '2025-05-16 16:51:44',
            ),
            24 => 
            array (
                'id' => 25,
                'account_book_id' => 184,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "25000.00", "_token": "OiqRa7r6knGJ16RAal8L7eF1CS76Kiw0bmONVAa5", "payment": [{"amount": "1805000.00", "method": "3"}], "commission": "213317.00", "commitment": [{"date": "2025-07-20", "amount": "900000.00"}], "closing_date": "2025-05-16", "closing_type": "manual", "input-remaining": "2705000.00", "input-total-paid": "1805000.00", "input-total-expense": null, "input-total-payable": "2943317", "input-total-payment": "890000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "3917397", "input-total-remaining": "0.00", "input-total-transport": null, "input-previous-balance": "0", "input-total-commission": "3833317", "input-total-commitment": "900000.00", "input-return-commission": "84230", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "2730000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-16 16:54:48',
                'updated_at' => '2025-05-16 16:54:48',
            ),
            25 => 
            array (
                'id' => 26,
                'account_book_id' => 277,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "OiqRa7r6knGJ16RAal8L7eF1CS76Kiw0bmONVAa5", "payment": [{"amount": "900000.00", "method": "4"}], "commission": "0.00", "commitment": [{"date": "2025-07-10", "amount": "329886.00"}], "closing_date": "2025-05-16", "closing_type": "manual", "input-remaining": "1229886.00", "input-total-paid": "900000.00", "input-total-expense": null, "input-total-payable": "1229886", "input-total-payment": "482000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "1752842", "input-total-remaining": "0.00", "input-total-transport": null, "input-previous-balance": null, "input-total-commission": "1711886", "input-total-commitment": "329886.00", "input-return-commission": "40956", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "1229886.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-16 16:58:20',
                'updated_at' => '2025-05-16 16:58:20',
            ),
            26 => 
            array (
                'id' => 27,
                'account_book_id' => 298,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "OiqRa7r6knGJ16RAal8L7eF1CS76Kiw0bmONVAa5", "payment": [{"amount": "710000.00", "method": "1"}], "commission": "134750.00", "commitment": [{"date": "2025-07-20", "amount": "600000.00"}], "closing_date": "2025-05-16", "closing_type": "manual", "input-remaining": "1310000.00", "input-total-paid": "710000.00", "input-total-expense": null, "input-total-payable": "1444750", "input-total-payment": "300000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "1772630", "input-total-remaining": "0.00", "input-total-transport": null, "input-previous-balance": null, "input-total-commission": "1744750", "input-total-commitment": "600000.00", "input-return-commission": "27878", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "1310000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-16 17:00:10',
                'updated_at' => '2025-05-16 17:00:10',
            ),
            27 => 
            array (
                'id' => 28,
                'account_book_id' => 310,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "OiqRa7r6knGJ16RAal8L7eF1CS76Kiw0bmONVAa5", "payment": [{"amount": "400000.00", "method": "4"}], "commission": "0.00", "commitment": [{"date": "2025-05-20", "amount": "0.00"}], "closing_date": "2025-05-16", "closing_type": "manual", "input-remaining": "1095935.00", "input-total-paid": "400000.00", "input-total-expense": null, "input-total-payable": "1095935", "input-total-payment": "0", "input-total-discount": null, "balance_carry_forward": "1", "comminsion-percentage": "0", "input-sale-commission": "1101730", "input-total-remaining": "695935.00", "input-total-transport": null, "input-previous-balance": "0", "input-total-commission": "1095935", "input-total-commitment": "0.00", "input-return-commission": "5796", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "1095935.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-16 17:03:03',
                'updated_at' => '2025-05-16 17:03:03',
            ),
            28 => 
            array (
                'id' => 29,
                'account_book_id' => 332,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "8000.00", "_token": "JtbQFZkT3khHfaZ0m3TbUuJP6AjEtyvOHidsoBPg", "payment": [{"amount": "700000.00", "method": "4"}, {"amount": "28000.00", "method": "1"}], "commission": "60432.00", "commitment": [{"date": "2025-07-20", "amount": "300000.00"}], "closing_date": "2025-05-17", "closing_type": "manual", "input-remaining": "1028000.00", "input-total-paid": "728000.00", "input-total-expense": null, "input-total-payable": "1096432", "input-total-payment": "110000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "1217030", "input-total-remaining": "0.00", "input-total-transport": null, "input-previous-balance": null, "input-total-commission": "1206432", "input-total-commitment": "300000.00", "input-return-commission": "10598", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "1036000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-17 14:14:19',
                'updated_at' => '2025-05-17 14:14:19',
            ),
            29 => 
            array (
                'id' => 30,
                'account_book_id' => 324,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "JtbQFZkT3khHfaZ0m3TbUuJP6AjEtyvOHidsoBPg", "payment": [{"amount": "400000.00", "method": "1"}], "commission": "89548.00", "commitment": [{"date": "2025-05-18", "amount": "70000.00"}, {"date": "2025-07-17", "amount": "100000.00"}], "closing_date": "2025-05-17", "closing_type": "manual", "input-remaining": "570000.00", "input-total-paid": "400000.00", "input-total-expense": null, "input-total-payable": "659548", "input-total-payment": "690000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "873443", "input-total-remaining": "0.00", "input-total-transport": null, "input-previous-balance": "508503", "input-total-commission": "841045", "input-total-commitment": "170000.00", "input-return-commission": "32398", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "570000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-17 17:15:34',
                'updated_at' => '2025-05-17 17:15:34',
            ),
            30 => 
            array (
                'id' => 31,
                'account_book_id' => 281,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "JtbQFZkT3khHfaZ0m3TbUuJP6AjEtyvOHidsoBPg", "payment": [{"amount": "700000.00", "method": "4"}], "commission": "0.00", "commitment": [{"date": "2025-07-15", "amount": "283737.00"}], "closing_date": "2025-05-17", "closing_type": "manual", "input-remaining": "983737.00", "input-total-paid": "700000.00", "input-total-expense": null, "input-total-payable": "983737", "input-total-payment": "573000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "1605696", "input-total-remaining": "0.00", "input-total-transport": null, "input-previous-balance": null, "input-total-commission": "1556737", "input-total-commitment": "283737.00", "input-return-commission": "48958", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "983737.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-17 21:54:03',
                'updated_at' => '2025-05-17 21:54:03',
            ),
            31 => 
            array (
                'id' => 32,
                'account_book_id' => 380,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "ce9wj0AlurvOR9iYfn71k4L8ANadPWeBR0KiQ8vw", "payment": [{"amount": "170000.00", "method": "1"}, {"amount": "24000.00", "method": "1"}], "commission": "8484.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-05-18", "closing_type": "manual", "input-remaining": "194000.00", "input-total-paid": "194000.00", "input-total-expense": null, "input-total-payable": "202484", "input-total-payment": "220000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "433640", "input-total-remaining": "0.00", "input-total-transport": null, "input-previous-balance": null, "input-total-commission": "422484", "input-total-commitment": null, "input-return-commission": "11156", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "194000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-18 15:48:00',
                'updated_at' => '2025-05-18 15:48:00',
            ),
            32 => 
            array (
                'id' => 33,
                'account_book_id' => 401,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "14660.00", "_token": "ce9wj0AlurvOR9iYfn71k4L8ANadPWeBR0KiQ8vw", "payment": [{"amount": "90000.00", "method": "1"}, {"amount": "150000.00", "method": "1"}, {"amount": "150000.00", "method": "1"}, {"amount": "150000.00", "method": "1"}], "commission": "28065.00", "commitment": [{"date": "2025-05-03", "amount": "0.00"}, {"date": "2025-05-13", "amount": "0.00"}], "closing_date": "2025-05-18", "closing_type": "manual", "input-remaining": "540000.00", "input-total-paid": "540000.00", "input-total-expense": null, "input-total-payable": "582725", "input-total-payment": "118900", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "701625", "input-total-remaining": "0.00", "input-total-transport": null, "input-previous-balance": "0", "input-total-commission": "701625", "input-total-commitment": "0.00", "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "554660.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-18 15:51:26',
                'updated_at' => '2025-05-18 15:51:26',
            ),
            33 => 
            array (
                'id' => 34,
                'account_book_id' => 368,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "24117.00", "_token": "ce9wj0AlurvOR9iYfn71k4L8ANadPWeBR0KiQ8vw", "payment": [{"amount": "575000.00", "method": "1"}], "commission": "71213.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-05-18", "closing_type": "manual", "input-remaining": "575000.00", "input-total-paid": "575000.00", "input-total-expense": null, "input-total-payable": "670330", "input-total-payment": "1110000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "1794030", "input-total-remaining": "0.00", "input-total-transport": null, "input-previous-balance": null, "input-total-commission": "1780330", "input-total-commitment": null, "input-return-commission": "13700", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "599117.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-18 20:14:05',
                'updated_at' => '2025-05-18 20:14:05',
            ),
            34 => 
            array (
                'id' => 35,
                'account_book_id' => 253,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "20000.00", "_token": "tk3cENNteOG7y5meyAQJntzc9arF1KSaLpfcjvH7", "payment": [{"amount": "1000000.00", "method": "7"}], "commission": "152573.00", "commitment": [{"date": "2025-07-15", "amount": "0.00"}], "closing_date": "2025-05-19", "closing_type": "manual", "input-remaining": "2450000.00", "input-total-paid": "1000000.00", "input-total-expense": null, "input-total-payable": "2622573", "input-total-payment": "100000", "input-total-discount": "20000", "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "2742573", "input-total-remaining": "1450000.00", "input-total-transport": null, "input-previous-balance": null, "input-total-commission": "2742573", "input-total-commitment": "0.00", "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "2470000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-19 13:08:16',
                'updated_at' => '2025-05-19 13:08:16',
            ),
            35 => 
            array (
                'id' => 36,
                'account_book_id' => 1001,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "W4vyCbqUTq42m4ToEBgkvEbvHWXbF2ioMpYrMvd0", "payment": [{"amount": "400000.00", "method": "4"}, {"amount": "200000.00", "method": "4"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-05-19", "closing_type": "manual", "input-remaining": "1219560.00", "input-total-paid": "600000.00", "input-total-expense": null, "input-total-payable": "1219560", "input-total-payment": "940000", "input-total-discount": null, "balance_carry_forward": "1", "comminsion-percentage": "0", "input-sale-commission": "1801825", "input-total-remaining": "619560.00", "input-total-transport": null, "input-previous-balance": "413560", "input-total-commission": "1746000", "input-total-commitment": null, "input-return-commission": "55825", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "1219560.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-19 16:05:39',
                'updated_at' => '2025-05-19 16:05:39',
            ),
            36 => 
            array (
                'id' => 37,
                'account_book_id' => 351,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "5000.00", "_token": "W4vyCbqUTq42m4ToEBgkvEbvHWXbF2ioMpYrMvd0", "payment": [{"amount": "130000.00", "method": "1"}], "commission": "21363.00", "commitment": [{"date": "2025-07-15", "amount": "44091.00"}], "closing_date": "2025-05-19", "closing_type": "manual", "input-remaining": "174091.00", "input-total-paid": "130000.00", "input-total-expense": "0", "input-total-payable": "200454", "input-total-payment": "224000", "input-total-discount": "0", "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "427262", "input-total-remaining": "0.00", "input-total-transport": "0", "input-previous-balance": "0", "input-total-commission": "424454", "input-total-commitment": "44091.00", "input-return-commission": "2808", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "179091.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": "0", "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-19 17:19:29',
                'updated_at' => '2025-05-19 17:19:29',
            ),
            37 => 
            array (
                'id' => 38,
                'account_book_id' => 294,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "W4vyCbqUTq42m4ToEBgkvEbvHWXbF2ioMpYrMvd0", "payment": [{"amount": "1000000.00", "method": "9"}, {"amount": "175000.00", "method": "1"}], "commission": "157200.00", "commitment": [{"date": "2025-06-15", "amount": "1400180.00"}], "closing_date": null, "closing_type": "manual", "input-remaining": "2575180.00", "input-total-paid": "1175000.00", "input-total-expense": "0", "input-total-payable": "2732380", "input-total-payment": "400000", "input-total-discount": "0", "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "3160720", "input-total-remaining": "0.00", "input-total-transport": "0", "input-previous-balance": "0", "input-total-commission": "3132380", "input-total-commitment": "1400180.00", "input-return-commission": "28340", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "2575180.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": "0", "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-19 17:40:16',
                'updated_at' => '2025-05-19 17:40:16',
            ),
            38 => 
            array (
                'id' => 39,
                'account_book_id' => 271,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "vjExUkBNstjrMLwuQ3izfsJ4sINBRjJgKeMuDmHR", "payment": [{"amount": "1200000.00", "method": "3"}, {"amount": "230000.00", "method": "1"}], "commission": "227804.00", "commitment": [{"date": "2025-07-15", "amount": "231000.00"}], "closing_date": "2025-05-19", "closing_type": "manual", "input-remaining": "1661000.00", "input-total-paid": "1430000.00", "input-total-expense": null, "input-total-payable": "1888804", "input-total-payment": "1365000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "3345102", "input-total-remaining": "0.00", "input-total-transport": "300", "input-previous-balance": "0", "input-total-commission": "3253504", "input-total-commitment": "231000.00", "input-return-commission": "91598", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "1661000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-19 22:15:27',
                'updated_at' => '2025-05-19 22:15:27',
            ),
            39 => 
            array (
                'id' => 40,
                'account_book_id' => 274,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "vjExUkBNstjrMLwuQ3izfsJ4sINBRjJgKeMuDmHR", "payment": [{"amount": "1370000.00", "method": "4"}, {"amount": "150000.00", "method": "4"}], "commission": "165140.00", "commitment": [{"date": "2025-07-20", "amount": "750000.00"}], "closing_date": "2025-05-19", "closing_type": "manual", "input-remaining": "2270000.00", "input-total-paid": "1520000.00", "input-total-expense": null, "input-total-payable": "2435140", "input-total-payment": "230000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "3081262", "input-total-remaining": "0.00", "input-total-transport": null, "input-previous-balance": null, "input-total-commission": "2665140", "input-total-commitment": "750000.00", "input-return-commission": "416122", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "2270000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-19 22:20:32',
                'updated_at' => '2025-05-19 22:20:32',
            ),
            40 => 
            array (
                'id' => 41,
                'account_book_id' => 311,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "WUYdcaLiDBhYW8Yx7TA1VlbswQtA73KItesZKMdb", "payment": [{"amount": "1600000.00", "method": "2"}], "commission": "460667.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-05-20", "closing_type": "manual", "input-remaining": "1600000.00", "input-total-paid": "1600000.00", "input-total-expense": null, "input-total-payable": "2060667", "input-total-payment": "4600000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "6681693", "input-total-remaining": "0.00", "input-total-transport": "48860", "input-previous-balance": "0", "input-total-commission": "6614807", "input-total-commitment": null, "input-return-commission": "66886", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "1600000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-20 13:01:48',
                'updated_at' => '2025-05-20 13:01:48',
            ),
            41 => 
            array (
                'id' => 42,
                'account_book_id' => 291,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "WUYdcaLiDBhYW8Yx7TA1VlbswQtA73KItesZKMdb", "payment": [{"amount": "345000.00", "method": "1"}, {"amount": "240000.00", "method": "1"}], "commission": "0.00", "commitment": [{"date": "2025-07-10", "amount": "1447664.00"}], "closing_date": "2025-05-20", "closing_type": "manual", "input-remaining": "2032664.00", "input-total-paid": "585000.00", "input-total-expense": null, "input-total-payable": "2032664", "input-total-payment": "160000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "2223978", "input-total-remaining": "0.00", "input-total-transport": null, "input-previous-balance": "0", "input-total-commission": "2192664", "input-total-commitment": "1447664.00", "input-return-commission": "31314", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "2032664.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-20 13:05:34',
                'updated_at' => '2025-05-20 13:05:34',
            ),
            42 => 
            array (
                'id' => 43,
                'account_book_id' => 122,
                'account_type' => 'factory',
                'closing_data' => '{"staff": "1500.00", "_token": "FT6pHEmaPAxWODmfCSEZxkJBMmxCFw4g8YLtfNtk", "cheque": [{"id": null, "amount": null, "due_date": null}], "payment": [{"amount": "30500.00", "method": "1"}], "commission": "2200.00", "closing_date": "2025-05-20", "closing_type": "manual", "input-return": null, "input-payment": "25000", "input-purchase": "59200", "input-remaining": "30500.00", "input-total-paid": "30500.00", "input-total-payable": "34200", "comminsion-percentage": "3.72", "input-total-remaining": "0.00", "input-commission-deducted": "32000.00", "staff-comminsion-percentage": "2.53"}',
                'created_at' => '2025-05-20 17:48:48',
                'updated_at' => '2025-05-20 17:48:48',
            ),
            43 => 
            array (
                'id' => 44,
                'account_book_id' => 133,
                'account_type' => 'factory',
                'closing_data' => '{"staff": "7000.00", "_token": "FT6pHEmaPAxWODmfCSEZxkJBMmxCFw4g8YLtfNtk", "cheque": [{"id": "987", "amount": "40000.00", "due_date": "2025-07-30"}, {"id": "988", "amount": "30000.00", "due_date": "2025-11-30"}], "payment": [{"amount": "83000.00", "method": "2"}], "commission": "10600.00", "closing_date": "2025-05-20", "closing_type": "manual", "input-return": null, "input-payment": "170000", "input-purchase": "340600", "input-remaining": "153000.00", "input-total-paid": "83000.00", "input-total-payable": "170600", "comminsion-percentage": "3.11", "input-total-remaining": "0.00", "input-commission-deducted": "160000.00", "staff-comminsion-percentage": "2.06"}',
                'created_at' => '2025-05-20 18:46:34',
                'updated_at' => '2025-05-20 18:46:34',
            ),
            44 => 
            array (
                'id' => 45,
                'account_book_id' => 144,
                'account_type' => 'factory',
                'closing_data' => '{"staff": "7000.00", "_token": "FT6pHEmaPAxWODmfCSEZxkJBMmxCFw4g8YLtfNtk", "cheque": [{"id": "0", "amount": "40000.00", "due_date": "2025-07-30"}, {"id": "00", "amount": "30000.00", "due_date": "2025-11-30"}], "payment": [{"amount": "74000.00", "method": "2"}], "commission": "10600.00", "closing_date": "2025-05-20", "closing_type": "manual", "input-return": null, "input-payment": "164800", "input-purchase": "326400", "input-remaining": "144000.00", "input-total-paid": "74000.00", "input-total-payable": "161600", "comminsion-percentage": "3.25", "input-total-remaining": "0.00", "input-commission-deducted": "151000.00", "staff-comminsion-percentage": "2.14"}',
                'created_at' => '2025-05-20 18:49:38',
                'updated_at' => '2025-05-20 18:49:38',
            ),
            45 => 
            array (
                'id' => 46,
                'account_book_id' => 402,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "FT6pHEmaPAxWODmfCSEZxkJBMmxCFw4g8YLtfNtk", "payment": [{"amount": "10000.00", "method": "1"}, {"amount": "120000.00", "method": "4"}], "commission": "23950.00", "commitment": [{"date": "2025-05-10", "amount": "120000.00"}], "closing_date": "2025-05-20", "closing_type": "manual", "input-remaining": "130000.00", "input-total-paid": "130000.00", "input-total-expense": null, "input-total-payable": "153950", "input-total-payment": "350000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "503950", "input-total-remaining": "0.00", "input-total-transport": null, "input-previous-balance": null, "input-total-commission": "503950", "input-total-commitment": null, "input-return-commission": "0", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "130000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-20 19:27:53',
                'updated_at' => '2025-05-20 19:27:53',
            ),
            46 => 
            array (
                'id' => 47,
                'account_book_id' => 349,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "10000.00", "_token": "FT6pHEmaPAxWODmfCSEZxkJBMmxCFw4g8YLtfNtk", "payment": [{"amount": "818000.00", "method": "1"}, {"amount": "300000.00", "method": "1"}], "commission": "108348.00", "commitment": [{"date": "2025-07-10", "amount": "200000.00"}], "closing_date": "2025-05-20", "closing_type": "manual", "input-remaining": "1318000.00", "input-total-paid": "1118000.00", "input-total-expense": null, "input-total-payable": "1436348", "input-total-payment": "730000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "2191892", "input-total-remaining": "0.00", "input-total-transport": null, "input-previous-balance": null, "input-total-commission": "2166348", "input-total-commitment": "200000.00", "input-return-commission": "25544", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "1328000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-20 19:32:15',
                'updated_at' => '2025-05-20 19:32:15',
            ),
            47 => 
            array (
                'id' => 48,
                'account_book_id' => 30,
                'account_type' => 'factory',
                'closing_data' => '{"staff": "7000.00", "_token": "FT6pHEmaPAxWODmfCSEZxkJBMmxCFw4g8YLtfNtk", "cheque": [{"id": "833", "amount": "40000.00", "due_date": "2025-07-30"}, {"id": "834", "amount": "40000.00", "due_date": "2025-11-30"}], "payment": [{"amount": "85000.00", "method": "2"}], "commission": "11425.00", "closing_date": "2025-05-20", "closing_type": "manual", "input-return": null, "input-payment": "174275", "input-purchase": "357700", "input-remaining": "165000.00", "input-total-paid": "85000.00", "input-total-payable": "183425", "comminsion-percentage": "3.19", "input-total-remaining": "0.00", "input-commission-deducted": "172000.00", "staff-comminsion-percentage": "1.96"}',
                'created_at' => '2025-05-20 20:08:31',
                'updated_at' => '2025-05-20 20:08:31',
            ),
            48 => 
            array (
                'id' => 49,
                'account_book_id' => 149,
                'account_type' => 'factory',
                'closing_data' => '{"staff": "5800.00", "_token": "FT6pHEmaPAxWODmfCSEZxkJBMmxCFw4g8YLtfNtk", "cheque": [{"id": "835", "amount": "40000.00", "due_date": "2025-07-30"}, {"id": "836", "amount": "30000.00", "due_date": "2025-11-30"}], "payment": [{"amount": "86000.00", "method": "2"}], "commission": "9000.00", "closing_date": "2025-05-20", "closing_type": "manual", "input-return": null, "input-payment": "107000", "input-purchase": "277800", "input-remaining": "156000.00", "input-total-paid": "86000.00", "input-total-payable": "170800", "comminsion-percentage": "3.24", "input-total-remaining": "0.00", "input-commission-deducted": "161800.00", "staff-comminsion-percentage": "2.09"}',
                'created_at' => '2025-05-20 20:10:55',
                'updated_at' => '2025-05-20 20:10:55',
            ),
            49 => 
            array (
                'id' => 50,
                'account_book_id' => 63,
                'account_type' => 'factory',
                'closing_data' => '{"staff": "11500.00", "_token": "FT6pHEmaPAxWODmfCSEZxkJBMmxCFw4g8YLtfNtk", "cheque": [{"id": "713", "amount": "80000.00", "due_date": "2025-07-30"}, {"id": "714", "amount": "80000.00", "due_date": "2025-11-30"}], "payment": [{"amount": "177000.00", "method": "3"}], "commission": "17600.00", "closing_date": "2025-05-20", "closing_type": "manual", "input-return": null, "input-payment": "206000", "input-purchase": "572100", "input-remaining": "337000.00", "input-total-paid": "177000.00", "input-total-payable": "366100", "comminsion-percentage": "3.08", "input-total-remaining": "0.00", "input-commission-deducted": "348500.00", "staff-comminsion-percentage": "2.01"}',
                'created_at' => '2025-05-20 20:13:05',
                'updated_at' => '2025-05-20 20:13:05',
            ),
            50 => 
            array (
                'id' => 51,
                'account_book_id' => 39,
                'account_type' => 'factory',
                'closing_data' => '{"staff": "9000.00", "_token": "FT6pHEmaPAxWODmfCSEZxkJBMmxCFw4g8YLtfNtk", "cheque": [{"id": "717", "amount": "60000.00", "due_date": "2025-07-30"}, {"id": "718", "amount": "50000.00", "due_date": "2025-11-30"}], "payment": [{"amount": "120000.00", "method": "3"}], "commission": "13600.00", "closing_date": "2025-05-20", "closing_type": "manual", "input-return": null, "input-payment": "187200", "input-purchase": "439800", "input-remaining": "230000.00", "input-total-paid": "120000.00", "input-total-payable": "252600", "comminsion-percentage": "3.09", "input-total-remaining": "0.00", "input-commission-deducted": "239000.00", "staff-comminsion-percentage": "2.05"}',
                'created_at' => '2025-05-20 20:34:55',
                'updated_at' => '2025-05-20 20:34:55',
            ),
            51 => 
            array (
                'id' => 52,
                'account_book_id' => 235,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "NxR1ppe5QILe7KSoKPGIMj9VRtGf72j2jRPpLswX", "payment": [{"amount": "2010000.00", "method": "10"}], "commission": "0.00", "commitment": [{"date": "2025-07-30", "amount": "5718036.00"}], "closing_date": "2025-05-21", "closing_type": "manual", "input-remaining": "7728036.00", "input-total-paid": "2010000.00", "input-total-expense": null, "input-total-payable": "7728036", "input-total-payment": "1617000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "9657278", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": null, "input-total-commission": "9345036", "input-total-commitment": "5718036.00", "input-return-commission": "312242", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "7728036.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-21 15:18:49',
                'updated_at' => '2025-05-21 15:18:49',
            ),
            52 => 
            array (
                'id' => 53,
                'account_book_id' => 375,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "NxR1ppe5QILe7KSoKPGIMj9VRtGf72j2jRPpLswX", "payment": [{"amount": "200000.00", "method": "1"}, {"amount": "100000.00", "method": "1"}, {"amount": "50000.00", "method": "1"}], "commission": "31660.00", "commitment": {"1": {"date": "2025-07-15", "amount": "142273.00"}}, "closing_date": "2025-05-21", "closing_type": "manual", "input-remaining": "492273.00", "input-total-paid": "350000.00", "input-total-expense": null, "input-total-payable": "523933", "input-total-payment": "250000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "783735", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": null, "input-total-commission": "773933", "input-total-commitment": "142273.00", "input-return-commission": "9802", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "492273.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-21 15:24:11',
                'updated_at' => '2025-05-21 15:24:11',
            ),
            53 => 
            array (
                'id' => 54,
                'account_book_id' => 283,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "NxR1ppe5QILe7KSoKPGIMj9VRtGf72j2jRPpLswX", "payment": [{"amount": "150000.00", "method": "1"}, {"amount": "150000.00", "method": "1"}, {"amount": "70000.00", "method": "1"}], "commission": "18651.00", "commitment": [{"date": "2025-07-15", "amount": "31000.00"}], "closing_date": "2025-05-21", "closing_type": "manual", "input-remaining": "401000.00", "input-total-paid": "370000.00", "input-total-expense": null, "input-total-payable": "419651", "input-total-payment": "200000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "625856", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": null, "input-total-commission": "619651", "input-total-commitment": "31000.00", "input-return-commission": "6205", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "401000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-21 15:27:47',
                'updated_at' => '2025-05-21 15:27:47',
            ),
            54 => 
            array (
                'id' => 55,
                'account_book_id' => 373,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "6000.00", "_token": "NxR1ppe5QILe7KSoKPGIMj9VRtGf72j2jRPpLswX", "payment": [{"amount": "150000.00", "method": "4"}, {"amount": "50000.00", "method": "1"}, {"amount": "120000.00", "method": "1"}], "commission": "29373.00", "commitment": [{"date": "2025-07-10", "amount": "30000.00"}], "closing_date": "2025-05-21", "closing_type": "manual", "input-remaining": "350000.00", "input-total-paid": "320000.00", "input-total-expense": null, "input-total-payable": "385373", "input-total-payment": "350000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "741205", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": null, "input-total-commission": "735373", "input-total-commitment": "30000.00", "input-return-commission": "5832", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "356000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-21 15:33:14',
                'updated_at' => '2025-05-21 15:33:14',
            ),
            55 => 
            array (
                'id' => 56,
                'account_book_id' => 365,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "NxR1ppe5QILe7KSoKPGIMj9VRtGf72j2jRPpLswX", "payment": [{"amount": "165000.00", "method": "1"}, {"amount": "50000.00", "method": "1"}], "commission": "48049.00", "commitment": [{"date": "2025-05-20", "amount": "100000.00"}, {"date": "2025-06-10", "amount": "100000.00"}, {"date": "2025-07-10", "amount": "100000.00"}, {"date": "2025-08-10", "amount": "100000.00"}, {"date": "2025-09-10", "amount": "100000.00"}], "closing_date": "2025-05-21", "closing_type": "manual", "input-remaining": "715000.00", "input-total-paid": "215000.00", "input-total-expense": null, "input-total-payable": "763049", "input-total-payment": "190000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "952279", "input-total-transport": "770", "input_total_remaining": "0.00", "input-previous-balance": null, "input-total-commission": "952279", "input-total-commitment": "500000.00", "input-return-commission": "0", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "715000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-21 17:38:50',
                'updated_at' => '2025-05-21 17:38:50',
            ),
            56 => 
            array (
                'id' => 57,
                'account_book_id' => 1036,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "10000.00", "_token": "gkaCSRjOcKKvWhhm6VpkHgo17uDOma8oecTejVa1", "payment": [{"amount": "400000.00", "method": "1"}, {"amount": "200000.00", "method": "1"}], "commission": "33065.00", "commitment": [{"date": "2025-07-10", "amount": "64000.00"}], "closing_date": "2025-05-22", "closing_type": "manual", "input-remaining": "664000.00", "input-total-paid": "600000.00", "input-total-expense": null, "input-total-payable": "707065", "input-total-payment": "400000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "1107065", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": null, "input-total-commission": "1107065", "input-total-commitment": "64000.00", "input-return-commission": "0", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "674000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-22 17:33:23',
                'updated_at' => '2025-05-22 17:33:23',
            ),
            57 => 
            array (
                'id' => 58,
                'account_book_id' => 285,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "gkaCSRjOcKKvWhhm6VpkHgo17uDOma8oecTejVa1", "payment": [{"amount": "30000.00", "method": "1"}, {"amount": "50000.00", "method": "1"}], "commission": "44924.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-05-22", "closing_type": "manual", "input-remaining": "80000.00", "input-total-paid": "80000.00", "input-total-expense": null, "input-total-payable": "124924", "input-total-payment": "240000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "317210", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": "58578", "input-total-commission": "306346", "input-total-commitment": null, "input-return-commission": "10864", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "80000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-22 18:47:00',
                'updated_at' => '2025-05-22 18:47:00',
            ),
            58 => 
            array (
                'id' => 59,
                'account_book_id' => 282,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "gkaCSRjOcKKvWhhm6VpkHgo17uDOma8oecTejVa1", "payment": [{"amount": "190000.00", "method": "10"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": null, "closing_type": "manual", "input-remaining": "375990.00", "input-total-paid": "190000.00", "input-total-expense": null, "input-total-payable": "375990", "input-total-payment": "0", "input-total-discount": null, "balance_carry_forward": "1", "comminsion-percentage": "0", "input-sale-commission": "375990", "input-total-transport": null, "input_total_remaining": "185990.00", "input-previous-balance": "0", "input-total-commission": "375990", "input-total-commitment": null, "input-return-commission": "0", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "375990.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-22 20:13:37',
                'updated_at' => '2025-05-22 20:13:37',
            ),
            59 => 
            array (
                'id' => 60,
                'account_book_id' => 385,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "gkaCSRjOcKKvWhhm6VpkHgo17uDOma8oecTejVa1", "payment": [{"amount": "30000.00", "method": "1"}, {"amount": "50000.00", "method": "1"}, {"amount": "26000.00", "method": "1"}], "commission": "6689.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-05-22", "closing_type": "manual", "input-remaining": "106000.00", "input-total-paid": "106000.00", "input-total-expense": null, "input-total-payable": "112689", "input-total-payment": "40000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "152689", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": "0", "input-total-commission": "152689", "input-total-commitment": null, "input-return-commission": "0", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "106000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-22 20:18:13',
                'updated_at' => '2025-05-22 20:18:13',
            ),
            60 => 
            array (
                'id' => 61,
                'account_book_id' => 341,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "KUmEWFygB1Q0AZ7K3VTmjbMlw5BtFACWk9cxpqUA", "payment": [{"amount": "400000.00", "method": "1"}, {"amount": "50000.00", "method": "1"}], "commission": "42238.00", "commitment": [{"date": "2025-07-10", "amount": "170000.00"}], "closing_date": "2025-05-23", "closing_type": "manual", "input-remaining": "620000.00", "input-total-paid": "450000.00", "input-total-expense": null, "input-total-payable": "662238", "input-total-payment": "250000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "920418", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": "0", "input-total-commission": "912238", "input-total-commitment": "170000.00", "input-return-commission": "8180", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "620000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-23 16:09:56',
                'updated_at' => '2025-05-23 16:09:56',
            ),
            61 => 
            array (
                'id' => 62,
                'account_book_id' => 296,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "KUmEWFygB1Q0AZ7K3VTmjbMlw5BtFACWk9cxpqUA", "payment": [{"amount": "300000.00", "method": "10"}, {"amount": "100000.00", "method": "7"}], "commission": "0.00", "commitment": [{"date": "2025-07-08", "amount": "124106.00"}], "closing_date": "2025-05-23", "closing_type": "manual", "input-remaining": "524106.00", "input-total-paid": "400000.00", "input-total-expense": null, "input-total-payable": "524106", "input-total-payment": "280000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "838210", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": null, "input-total-commission": "804106", "input-total-commitment": "124106.00", "input-return-commission": "34104", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "524106.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-23 16:11:58',
                'updated_at' => '2025-05-23 16:11:58',
            ),
            62 => 
            array (
                'id' => 63,
                'account_book_id' => 256,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "KUmEWFygB1Q0AZ7K3VTmjbMlw5BtFACWk9cxpqUA", "payment": [{"amount": "300000.00", "method": "1"}, {"amount": "400000.00", "method": "7"}], "commission": "0.00", "commitment": {"1": {"date": null, "amount": null}}, "closing_date": "2025-05-23", "closing_type": "manual", "input-remaining": "1565250.00", "input-total-paid": "700000.00", "input-total-expense": null, "input-total-payable": "1565250", "input-total-payment": "300000", "input-total-discount": null, "balance_carry_forward": "1", "comminsion-percentage": "0", "input-sale-commission": "1776060", "input-total-transport": null, "input_total_remaining": "865250.00", "input-previous-balance": "0", "input-total-commission": "1865250", "input-total-commitment": null, "input-return-commission": "500", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "1565250.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": "89690", "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-23 16:18:19',
                'updated_at' => '2025-05-23 16:18:19',
            ),
            63 => 
            array (
                'id' => 64,
                'account_book_id' => 280,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "KUmEWFygB1Q0AZ7K3VTmjbMlw5BtFACWk9cxpqUA", "payment": [{"amount": "50000.00", "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": null, "closing_type": "manual", "input-remaining": "345925.00", "input-total-paid": "50000.00", "input-total-expense": null, "input-total-payable": "345925", "input-total-payment": "290000", "input-total-discount": null, "balance_carry_forward": "1", "comminsion-percentage": "0", "input-sale-commission": "233258", "input-total-transport": null, "input_total_remaining": "295925.00", "input-previous-balance": "298137", "input-total-commission": "337788", "input-total-commitment": null, "input-return-commission": "0", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "345925.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": "104530", "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-23 18:44:05',
                'updated_at' => '2025-05-23 18:44:05',
            ),
            64 => 
            array (
                'id' => 65,
                'account_book_id' => 300,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "KUmEWFygB1Q0AZ7K3VTmjbMlw5BtFACWk9cxpqUA", "payment": [{"amount": "1300000.00", "method": "4"}], "commission": "526321.00", "commitment": [{"date": "2025-06-10", "amount": "1000000.00"}, {"date": "2025-07-10", "amount": "1000000.00"}], "closing_date": "2025-05-23", "closing_type": "manual", "input-remaining": "3300000.00", "input-total-paid": "1300000.00", "input-total-expense": null, "input-total-payable": "3826321", "input-total-payment": "1000000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "3900815", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": "946690", "input-total-commission": "3879631", "input-total-commitment": "2000000.00", "input-return-commission": "21184", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "3300000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-23 18:55:04',
                'updated_at' => '2025-05-23 18:55:04',
            ),
            65 => 
            array (
                'id' => 66,
                'account_book_id' => 1048,
                'account_type' => 'factory',
                'closing_data' => '{"staff": "2000.00", "_token": "AWlpk4V8PW3tIza483ql3BcWUulXXvvb0CYfarbV", "cheque": [{"id": "981", "amount": "30000.00", "due_date": "2025-07-30"}], "payment": [{"amount": "37000.00", "method": "8"}], "commission": "3000.00", "closing_date": "2025-05-24", "closing_type": "manual", "input-return": null, "input-payment": "20000", "input-purchase": "92000", "input-remaining": "67000.00", "input-total-paid": "37000.00", "input-total-payable": "72000", "comminsion-percentage": "3.26", "input-total-remaining": "0.00", "input-commission-deducted": "69000.00", "staff-comminsion-percentage": "2.17"}',
                'created_at' => '2025-05-24 14:31:08',
                'updated_at' => '2025-05-24 14:31:08',
            ),
            66 => 
            array (
                'id' => 67,
                'account_book_id' => 172,
                'account_type' => 'factory',
                'closing_data' => '{"staff": "11000.00", "_token": "AWlpk4V8PW3tIza483ql3BcWUulXXvvb0CYfarbV", "cheque": [{"id": "985", "amount": "70000.00", "due_date": "2025-07-30"}, {"id": "986", "amount": "70000.00", "due_date": "2025-11-30"}], "payment": [{"amount": "151000.00", "method": "8"}], "commission": "17300.00", "closing_date": "2025-05-24", "closing_type": "manual", "input-return": null, "input-payment": "227500", "input-purchase": "546800", "input-remaining": "291000.00", "input-total-paid": "151000.00", "input-total-payable": "319300", "comminsion-percentage": "3.16", "input-total-remaining": "0.00", "input-commission-deducted": "302000.00", "staff-comminsion-percentage": "2.01"}',
                'created_at' => '2025-05-24 14:41:10',
                'updated_at' => '2025-05-24 14:41:10',
            ),
            67 => 
            array (
                'id' => 68,
                'account_book_id' => 1054,
                'account_type' => 'factory',
                'closing_data' => '{"staff": "3000.00", "_token": "AWlpk4V8PW3tIza483ql3BcWUulXXvvb0CYfarbV", "cheque": [{"id": null, "amount": null, "due_date": null}], "payment": [{"amount": "50000.00", "method": "8"}], "commission": "4000.00", "closing_date": "2025-05-24", "closing_type": "manual", "input-return": null, "input-payment": "69000", "input-purchase": "126000", "input-remaining": "50000.00", "input-total-paid": "50000.00", "input-total-payable": "57000", "comminsion-percentage": "3.17", "input-total-remaining": "0.00", "input-commission-deducted": "53000.00", "staff-comminsion-percentage": "2.38"}',
                'created_at' => '2025-05-24 15:11:34',
                'updated_at' => '2025-05-24 15:11:34',
            ),
            68 => 
            array (
                'id' => 69,
                'account_book_id' => 266,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "5000.00", "_token": "AWlpk4V8PW3tIza483ql3BcWUulXXvvb0CYfarbV", "payment": [{"amount": "170000.00", "method": "1"}, {"amount": "30000.00", "method": "10"}, {"amount": "200000.00", "method": "10"}], "commission": "54079.00", "commitment": [{"date": "2025-07-10", "amount": "100000.00"}], "closing_date": "2025-05-24", "closing_type": "manual", "input-remaining": "500000.00", "input-total-paid": "400000.00", "input-total-expense": null, "input-total-payable": "559079", "input-total-payment": "568380", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "761033", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": "375156", "input-total-commission": "752303", "input-total-commitment": "100000.00", "input-return-commission": "8730", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "505000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-24 15:52:27',
                'updated_at' => '2025-05-24 15:52:27',
            ),
            69 => 
            array (
                'id' => 70,
                'account_book_id' => 356,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "AWlpk4V8PW3tIza483ql3BcWUulXXvvb0CYfarbV", "payment": [{"amount": "300000.00", "method": "1"}, {"amount": "100000.00", "method": "10"}, {"amount": "100000.00", "method": "10"}], "commission": "0.00", "commitment": [{"date": "2025-05-24", "amount": "0.00"}, {"date": "2025-07-10", "amount": "445632.00"}], "closing_date": "2025-05-24", "closing_type": "manual", "input-remaining": "945632.00", "input-total-paid": "500000.00", "input-total-expense": null, "input-total-payable": "945632", "input-total-payment": "205000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "1150632", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": null, "input-total-commission": "1150632", "input-total-commitment": "445632.00", "input-return-commission": "0", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "945632.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-24 16:02:07',
                'updated_at' => '2025-05-24 16:02:07',
            ),
            70 => 
            array (
                'id' => 71,
                'account_book_id' => 260,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "lg8DdOrv20ARDrYY5An7VXDrqiKKFqsTn6nomE9s", "payment": [{"amount": "500000.00", "method": "10"}, {"amount": "150000.00", "method": "1"}], "commission": "38425.00", "commitment": [{"date": "2025-07-10", "amount": "56000.00"}], "closing_date": "2025-05-25", "closing_type": "manual", "input-remaining": "706000.00", "input-total-paid": "650000.00", "input-total-expense": null, "input-total-payable": "744425", "input-total-payment": "184000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "935755", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": "0", "input-total-commission": "928425", "input-total-commitment": "56000.00", "input-return-commission": "7330", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "706000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-25 15:16:54',
                'updated_at' => '2025-05-25 15:16:54',
            ),
            71 => 
            array (
                'id' => 72,
                'account_book_id' => 1062,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "15000.00", "_token": "lg8DdOrv20ARDrYY5An7VXDrqiKKFqsTn6nomE9s", "payment": [{"amount": "300000.00", "method": "2"}, {"amount": "150000.00", "method": "11"}], "commission": "58386.00", "commitment": [{"date": "2025-07-10", "amount": "327000.00"}], "closing_date": "2025-05-25", "closing_type": "manual", "input-remaining": "777000.00", "input-total-paid": "450000.00", "input-total-expense": null, "input-total-payable": "850386", "input-total-payment": "604100", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "1491905", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": null, "input-total-commission": "1454486", "input-total-commitment": "327000.00", "input-return-commission": "37419", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "792000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-25 15:24:30',
                'updated_at' => '2025-05-25 15:24:30',
            ),
            72 => 
            array (
                'id' => 73,
                'account_book_id' => 347,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "lg8DdOrv20ARDrYY5An7VXDrqiKKFqsTn6nomE9s", "payment": [{"amount": "525000.00", "method": "1"}], "commission": "61506.00", "commitment": [{"date": "2025-07-10", "amount": "300000.00"}], "closing_date": "2025-05-25", "closing_type": "manual", "input-remaining": "825000.00", "input-total-paid": "525000.00", "input-total-expense": null, "input-total-payable": "886506", "input-total-payment": "458600", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "1374709", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": null, "input-total-commission": "1345106", "input-total-commitment": "300000.00", "input-return-commission": "29603", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "825000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-25 18:13:50',
                'updated_at' => '2025-05-25 18:13:50',
            ),
            73 => 
            array (
                'id' => 74,
                'account_book_id' => 237,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "lg8DdOrv20ARDrYY5An7VXDrqiKKFqsTn6nomE9s", "payment": [{"amount": "1200000.00", "method": "2"}], "commission": "0.00", "commitment": [{"date": "2025-04-30", "amount": "200000.00"}], "closing_date": "2025-05-25", "closing_type": "manual", "input-remaining": "2411298.00", "input-total-paid": "1200000.00", "input-total-expense": null, "input-total-payable": "2411298", "input-total-payment": "0", "input-total-discount": null, "balance_carry_forward": "1", "comminsion-percentage": "0", "input-sale-commission": "2411298", "input-total-transport": null, "input_total_remaining": "1211298.00", "input-previous-balance": null, "input-total-commission": "2411298", "input-total-commitment": null, "input-return-commission": "0", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "2411298.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-25 21:29:06',
                'updated_at' => '2025-05-25 21:29:06',
            ),
            74 => 
            array (
                'id' => 75,
                'account_book_id' => 295,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "12800.00", "_token": "lg8DdOrv20ARDrYY5An7VXDrqiKKFqsTn6nomE9s", "payment": [{"amount": "460000.00", "method": "1"}, {"amount": "30000.00", "method": "1"}], "commission": "32422.00", "commitment": [{"date": "2025-07-15", "amount": "70000.00"}], "closing_date": "2025-05-25", "closing_type": "manual", "input-remaining": "560000.00", "input-total-paid": "490000.00", "input-total-expense": null, "input-total-payable": "605222", "input-total-payment": "216500", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "828014", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": null, "input-total-commission": "821722", "input-total-commitment": "70000.00", "input-return-commission": "6292", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "572800.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-25 21:32:45',
                'updated_at' => '2025-05-25 21:32:45',
            ),
            75 => 
            array (
                'id' => 76,
                'account_book_id' => 370,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "sgIj1TuE19UaUYYA0dcSA2XYqSzS0a44Ir3kZNfN", "payment": [{"amount": "900000.00", "method": "2"}], "commission": "0", "commitment": [{"date": "2025-07-10", "amount": "437719.00"}], "closing_date": "2025-05-26", "closing_type": "manual", "input-remaining": "1337719.00", "input-total-paid": "900000.00", "input-total-expense": null, "input-total-payable": "1337719", "input-total-payment": "1200000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "2542147", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": "0", "input-total-commission": "2537719", "input-total-commitment": "437719.00", "input-return-commission": "4428", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "1337719.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-26 14:33:15',
                'updated_at' => '2025-05-26 14:33:15',
            ),
            76 => 
            array (
                'id' => 77,
                'account_book_id' => 268,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "sgIj1TuE19UaUYYA0dcSA2XYqSzS0a44Ir3kZNfN", "payment": [{"amount": "100000.00", "method": "10"}], "commission": "17407.00", "commitment": [{"date": "2025-07-10", "amount": "185000.00"}], "closing_date": "2025-05-26", "closing_type": "manual", "input-remaining": "285000.00", "input-total-paid": "100000.00", "input-total-expense": null, "input-total-payable": "302407", "input-total-payment": "270000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "579200", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": null, "input-total-commission": "572407", "input-total-commitment": "185000.00", "input-return-commission": "6793", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "285000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-26 14:35:32',
                'updated_at' => '2025-05-26 14:35:32',
            ),
            77 => 
            array (
                'id' => 78,
                'account_book_id' => 265,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "sgIj1TuE19UaUYYA0dcSA2XYqSzS0a44Ir3kZNfN", "payment": [{"amount": "400000.00", "method": "1"}], "commission": "49196.00", "commitment": [{"date": "2025-07-10", "amount": "365000.00"}], "closing_date": "2025-05-26", "closing_type": "manual", "input-remaining": "765000.00", "input-total-paid": "400000.00", "input-total-expense": null, "input-total-payable": "814196", "input-total-payment": "170000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "1004968", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": null, "input-total-commission": "984196", "input-total-commitment": "365000.00", "input-return-commission": "20772", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "765000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-26 14:38:44',
                'updated_at' => '2025-05-26 14:38:44',
            ),
            78 => 
            array (
                'id' => 79,
                'account_book_id' => 989,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "sgIj1TuE19UaUYYA0dcSA2XYqSzS0a44Ir3kZNfN", "payment": [{"amount": "115000.00", "method": "1"}], "commission": "0", "commitment": [{"date": "2025-07-14", "amount": "42315.00"}], "closing_date": "2025-05-26", "closing_type": "manual", "input-remaining": "157315.00", "input-total-paid": "115000.00", "input-total-expense": null, "input-total-payable": "157315", "input-total-payment": "126400", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "283715", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": null, "input-total-commission": "283715", "input-total-commitment": "42315.00", "input-return-commission": "0", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "157315.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-26 18:10:00',
                'updated_at' => '2025-05-26 18:10:00',
            ),
            79 => 
            array (
                'id' => 80,
                'account_book_id' => 333,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "sgIj1TuE19UaUYYA0dcSA2XYqSzS0a44Ir3kZNfN", "payment": [{"amount": "200000.00", "method": "1"}], "commission": "19483.00", "commitment": [{"date": "2025-05-26", "amount": "0.00"}, {"date": "2025-07-14", "amount": "312500.00"}], "closing_date": "2025-05-26", "closing_type": "manual", "input-remaining": "512500.00", "input-total-paid": "200000.00", "input-total-expense": null, "input-total-payable": "531983", "input-total-payment": "130000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "647447", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": "27646", "input-total-commission": "634337", "input-total-commitment": "312500.00", "input-return-commission": "13110", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "512500.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-26 18:27:04',
                'updated_at' => '2025-05-26 18:27:04',
            ),
            80 => 
            array (
                'id' => 81,
                'account_book_id' => 292,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "7000.00", "_token": "zLT8JAO8mVH1X63caDMeRGTzslxFHWKYGWYQdXNS", "payment": [{"amount": "253000.00", "method": "10"}, {"amount": "150000.00", "method": "10"}], "commission": "35555.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-05-27", "closing_type": "manual", "input-remaining": "403000.00", "input-total-paid": "403000.00", "input-total-expense": null, "input-total-payable": "445555", "input-total-payment": "269400", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "729827", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": "0", "input-total-commission": "714953", "input-total-commitment": null, "input-return-commission": "14874", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "410000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-27 16:58:07',
                'updated_at' => '2025-05-27 16:58:07',
            ),
            81 => 
            array (
                'id' => 82,
                'account_book_id' => 250,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "zLT8JAO8mVH1X63caDMeRGTzslxFHWKYGWYQdXNS", "payment": [{"amount": "180000.00", "method": "1"}], "commission": "69879.00", "commitment": [{"date": "2025-09-10", "amount": "700000.00"}], "closing_date": "2025-05-27", "closing_type": "manual", "input-remaining": "880000.00", "input-total-paid": "180000.00", "input-total-expense": null, "input-total-payable": "949879", "input-total-payment": "400000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "1381830", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": "0", "input-total-commission": "1349879", "input-total-commitment": "700000.00", "input-return-commission": "31951", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "880000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-27 17:18:33',
                'updated_at' => '2025-05-27 17:18:33',
            ),
            82 => 
            array (
                'id' => 83,
                'account_book_id' => 239,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "15000.00", "_token": "ETUTiA8Mp60fw2mLEur8OMYZM3GjJdZlWZ40H7NA", "payment": [{"amount": "150000.00", "method": "1"}, {"amount": "800000.00", "method": "1"}], "commission": "121632.00", "commitment": [{"date": "2025-09-14", "amount": "500000.00"}, {"date": "2025-10-12", "amount": "585000.00"}], "closing_date": "2025-05-27", "closing_type": "manual", "input-remaining": "2035000.00", "input-total-paid": "950000.00", "input-total-expense": null, "input-total-payable": "2171632", "input-total-payment": "250000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "2434880", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": null, "input-total-commission": "2421632", "input-total-commitment": "1085000.00", "input-return-commission": "13248", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "2050000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-27 22:32:31',
                'updated_at' => '2025-05-27 22:32:31',
            ),
            83 => 
            array (
                'id' => 84,
                'account_book_id' => 359,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "nfKOcRnWm4yrvWaYAYciQ15YQy3uzZqMq7PRWbTy", "payment": [{"amount": "250000.00", "method": "1"}, {"amount": "150000.00", "method": "3"}, {"amount": "100000.00", "method": "3"}], "commission": "63480.00", "commitment": [{"date": "2025-05-28", "amount": "0.00"}, {"date": "2025-07-20", "amount": "70000.00"}], "closing_date": "2025-05-28", "closing_type": "manual", "input-remaining": "570000.00", "input-total-paid": "500000.00", "input-total-expense": null, "input-total-payable": "633480", "input-total-payment": "621000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "1279480", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": null, "input-total-commission": "1254480", "input-total-commitment": "70000.00", "input-return-commission": "25000", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "570000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-28 14:28:54',
                'updated_at' => '2025-05-28 14:28:54',
            ),
            84 => 
            array (
                'id' => 85,
                'account_book_id' => 243,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "60000.00", "_token": "nfKOcRnWm4yrvWaYAYciQ15YQy3uzZqMq7PRWbTy", "payment": [{"amount": "60000.00", "method": "8"}, {"amount": "500000.00", "method": "8"}], "commission": "120512.00", "commitment": [{"date": "2025-08-10", "amount": "500000.00"}, {"date": "2025-09-10", "amount": "500000.00"}, {"date": "2025-10-09", "amount": "500000.00"}, {"date": "2025-11-10", "amount": "500000.00"}, {"date": "2025-12-10", "amount": "500000.00"}, {"date": "2026-01-11", "amount": "500000.00"}, {"date": "2026-02-10", "amount": "500000.00"}, {"date": "2026-03-15", "amount": "160000.00"}], "closing_date": "2025-05-28", "closing_type": "manual", "input-remaining": "4220000.00", "input-total-paid": "560000.00", "input-total-expense": null, "input-total-payable": "4400512", "input-total-payment": "0", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "4423335", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": null, "input-total-commission": "4400512", "input-total-commitment": "3660000.00", "input-return-commission": "22823", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "4280000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-28 14:37:54',
                'updated_at' => '2025-05-28 14:37:54',
            ),
            85 => 
            array (
                'id' => 86,
                'account_book_id' => 1085,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "nfKOcRnWm4yrvWaYAYciQ15YQy3uzZqMq7PRWbTy", "payment": [{"amount": "100000.00", "method": "4"}, {"amount": "20000.00", "method": "1"}, {"amount": "100000.00", "method": "4"}], "commission": "15095.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-05-28", "closing_type": "manual", "input-remaining": "220000.00", "input-total-paid": "220000.00", "input-total-expense": null, "input-total-payable": "235095", "input-total-payment": "300000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "535095", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": null, "input-total-commission": "535095", "input-total-commitment": null, "input-return-commission": "0", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "220000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-28 14:42:43',
                'updated_at' => '2025-05-28 14:42:43',
            ),
            86 => 
            array (
                'id' => 87,
                'account_book_id' => 11,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "nfKOcRnWm4yrvWaYAYciQ15YQy3uzZqMq7PRWbTy", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": "2025-06-15", "amount": "350000.00"}, {"date": "2025-06-30", "amount": "325000.00"}, {"date": "2025-07-10", "amount": "350000.00"}, {"date": "2025-07-30", "amount": "325000.00"}, {"date": "2025-08-10", "amount": "350000.00"}, {"date": "2025-08-25", "amount": "325000.00"}, {"date": "2025-09-10", "amount": "350000.00"}, {"date": "2025-09-25", "amount": "325000.00"}, {"date": "2025-10-15", "amount": "350000.00"}, {"date": "2025-10-26", "amount": "325000.00"}, {"date": "2025-11-13", "amount": "350000.00"}, {"date": "2025-11-25", "amount": "325000.00"}, {"date": "2025-12-15", "amount": "350000.00"}, {"date": "2025-12-25", "amount": "325000.00"}, {"date": "2026-01-11", "amount": "350000.00"}, {"date": "2026-01-25", "amount": "325000.00"}, {"date": "2026-02-12", "amount": "350000.00"}, {"date": "2026-02-25", "amount": "325000.00"}, {"date": "2026-03-12", "amount": "350000.00"}, {"date": "2026-03-30", "amount": "328700.00"}], "closing_date": "2025-05-28", "closing_type": "manual", "input-remaining": "6753700.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "6753700", "input-total-payment": "1823000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "8643438", "input-total-transport": "54529", "input_total_remaining": "0.00", "input-previous-balance": "0", "input-total-commission": "8522171", "input-total-commitment": "6753700.00", "input-return-commission": "121267", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "6753700.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-28 20:30:57',
                'updated_at' => '2025-05-28 20:30:57',
            ),
            87 => 
            array (
                'id' => 88,
                'account_book_id' => 240,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "nfKOcRnWm4yrvWaYAYciQ15YQy3uzZqMq7PRWbTy", "payment": [{"amount": "500000.00", "method": "1"}, {"amount": "100000.00", "method": "1"}], "commission": "35237.00", "commitment": [{"date": "2025-05-28", "amount": "0.00"}, {"date": "2025-06-03", "amount": "200000.00"}, {"date": "2025-07-10", "amount": "100000.00"}, {"date": "2025-08-12", "amount": "200000.00"}, {"date": "2025-09-10", "amount": "200000.00"}, {"date": "2025-10-09", "amount": "150000.00"}, {"date": "2025-11-13", "amount": "100000.00"}], "closing_date": "2025-05-28", "closing_type": "manual", "input-remaining": "1550000.00", "input-total-paid": "600000.00", "input-total-expense": null, "input-total-payable": "1585237", "input-total-payment": "50000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "1182237", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": "453000", "input-total-commission": "1182237", "input-total-commitment": "950000.00", "input-return-commission": "0", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "1550000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-28 22:57:17',
                'updated_at' => '2025-05-28 22:57:17',
            ),
            88 => 
            array (
                'id' => 89,
                'account_book_id' => 264,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "nfKOcRnWm4yrvWaYAYciQ15YQy3uzZqMq7PRWbTy", "payment": [{"amount": "550000.00", "method": "4"}], "commission": "30009.00", "commitment": [{"date": "2025-07-10", "amount": "100000.00"}, {"date": "2025-08-07", "amount": "100000.00"}, {"date": "2025-09-11", "amount": "100000.00"}, {"date": "2025-10-09", "amount": "100000.00"}, {"date": "2025-11-12", "amount": "100000.00"}, {"date": "2025-12-28", "amount": "98300.00"}], "closing_date": "2025-05-28", "closing_type": "manual", "input-remaining": "1148300.00", "input-total-paid": "550000.00", "input-total-expense": null, "input-total-payable": "1178309", "input-total-payment": "100000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "1327320", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": null, "input-total-commission": "1278309", "input-total-commitment": "598300.00", "input-return-commission": "49011", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "1148300.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-28 23:02:44',
                'updated_at' => '2025-05-28 23:02:44',
            ),
            89 => 
            array (
                'id' => 90,
                'account_book_id' => 328,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "100000.00", "_token": "nfKOcRnWm4yrvWaYAYciQ15YQy3uzZqMq7PRWbTy", "payment": [{"amount": "300000.00", "method": "4"}, {"amount": "200000.00", "method": "4"}, {"amount": "500000.00", "method": "4"}], "commission": "793153.00", "commitment": [{"date": "2025-06-12", "amount": "560000.00"}, {"date": "2025-07-10", "amount": "500000.00"}, {"date": "2025-08-07", "amount": "1000000.00"}, {"date": "2025-10-15", "amount": "1000000.00"}], "closing_date": "2025-05-29", "closing_type": "manual", "input-remaining": "4060000.00", "input-total-paid": "1000000.00", "input-total-expense": null, "input-total-payable": "4953153", "input-total-payment": "4930000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "10338925", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": null, "input-total-commission": "9883153", "input-total-commitment": "3060000.00", "input-return-commission": "455772", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "4160000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-29 01:36:41',
                'updated_at' => '2025-05-29 01:36:41',
            ),
            90 => 
            array (
                'id' => 91,
                'account_book_id' => 279,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "kFxGyZVAl4lGh4uSIkkmsovyoS4s7SQPIBxGJKXC", "payment": [{"amount": "250000.00", "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-05-29", "closing_type": "manual", "input-remaining": "700812.00", "input-total-paid": "250000.00", "input-total-expense": null, "input-total-payable": "700812", "input-total-payment": null, "input-total-discount": null, "balance_carry_forward": "1", "comminsion-percentage": "0", "input-sale-commission": "700812", "input-total-transport": null, "input_total_remaining": "450812.00", "input-previous-balance": null, "input-total-commission": "700812", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "700812.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-29 15:41:54',
                'updated_at' => '2025-05-29 15:41:54',
            ),
            91 => 
            array (
                'id' => 92,
                'account_book_id' => 293,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "kFxGyZVAl4lGh4uSIkkmsovyoS4s7SQPIBxGJKXC", "payment": [{"amount": "20000.00", "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": null, "closing_type": "manual", "input-remaining": "2107117.00", "input-total-paid": "20000.00", "input-total-expense": null, "input-total-payable": "2107117", "input-total-payment": "430000", "input-total-discount": null, "balance_carry_forward": "1", "comminsion-percentage": "0", "input-sale-commission": "370397", "input-total-transport": null, "input_total_remaining": "2087117.00", "input-previous-balance": "2166720", "input-total-commission": "370397", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "2107117.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-29 15:43:35',
                'updated_at' => '2025-05-29 15:43:35',
            ),
            92 => 
            array (
                'id' => 93,
                'account_book_id' => 273,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "kFxGyZVAl4lGh4uSIkkmsovyoS4s7SQPIBxGJKXC", "payment": [{"amount": "600000.00", "method": "1"}, {"amount": "200000.00", "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-05-29", "closing_type": "manual", "input-remaining": "1763976.00", "input-total-paid": "800000.00", "input-total-expense": null, "input-total-payable": "1763976", "input-total-payment": "860000", "input-total-discount": null, "balance_carry_forward": "1", "comminsion-percentage": "0", "input-sale-commission": "1727046", "input-total-transport": null, "input_total_remaining": "963976.00", "input-previous-balance": "910835", "input-total-commission": "1713141", "input-total-commitment": null, "input-return-commission": "13905", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "1763976.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-29 15:48:19',
                'updated_at' => '2025-05-29 15:48:19',
            ),
            93 => 
            array (
                'id' => 94,
                'account_book_id' => 236,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "10000.00", "_token": "kFxGyZVAl4lGh4uSIkkmsovyoS4s7SQPIBxGJKXC", "payment": [{"amount": "1900000.00", "method": "3"}, {"amount": "15000.00", "method": "1"}], "commission": "184900.00", "commitment": [{"date": "2025-07-10", "amount": "500000.00"}], "closing_date": "2025-05-29", "closing_type": "manual", "input-remaining": "2415000.00", "input-total-paid": "1915000.00", "input-total-expense": null, "input-total-payable": "2609900", "input-total-payment": "1085000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "3722832", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": null, "input-total-commission": "3694900", "input-total-commitment": "500000.00", "input-return-commission": "27932", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "2425000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-29 15:55:31',
                'updated_at' => '2025-05-29 15:55:31',
            ),
            94 => 
            array (
                'id' => 95,
                'account_book_id' => 327,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "6000.00", "_token": "kFxGyZVAl4lGh4uSIkkmsovyoS4s7SQPIBxGJKXC", "payment": [{"amount": "312000.00", "method": "1"}, {"amount": "100000.00", "method": "1"}], "commission": "29723.00", "commitment": [{"date": "2025-05-29", "amount": "0.00"}, {"date": "2025-07-24", "amount": "150000.00"}], "closing_date": "2025-05-29", "closing_type": "manual", "input-remaining": "562000.00", "input-total-paid": "412000.00", "input-total-expense": null, "input-total-payable": "597723", "input-total-payment": null, "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "604843", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": null, "input-total-commission": "597723", "input-total-commitment": "150000.00", "input-return-commission": "7120", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "568000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-29 18:25:48',
                'updated_at' => '2025-05-29 18:25:48',
            ),
            95 => 
            array (
                'id' => 96,
                'account_book_id' => 379,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "10800.00", "_token": "xPwGFlszznQDZhE2v1eb2rmVElPl84IBcvgmT3YO", "payment": [{"amount": "400000.00", "method": "4"}, {"amount": "50000.00", "method": "1"}, {"amount": "100000.00", "method": "1"}], "commission": "38990.00", "commitment": [{"date": "2025-05-30", "amount": "0.00"}, {"date": "2025-05-30", "amount": "0.00"}, {"date": "2025-06-12", "amount": "100000.00"}], "closing_date": "2025-05-30", "closing_type": "manual", "input-remaining": "650000.00", "input-total-paid": "550000.00", "input-total-expense": null, "input-total-payable": "699790", "input-total-payment": "150000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "849790", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": null, "input-total-commission": "849790", "input-total-commitment": "100000.00", "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "660800.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-30 00:06:42',
                'updated_at' => '2025-05-30 00:06:42',
            ),
            96 => 
            array (
                'id' => 97,
                'account_book_id' => 252,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "dCcZRYOBE7YKnpfS6LSlOFQtZ1d7RfloWF02lf90", "payment": [{"amount": "150000.00", "method": "10"}], "commission": "1.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-05-30", "closing_type": "manual", "input-remaining": "1218850.00", "input-total-paid": "150000.00", "input-total-expense": null, "input-total-payable": "1218851", "input-total-payment": "420000", "input-total-discount": null, "balance_carry_forward": "1", "comminsion-percentage": "0", "input-sale-commission": "601030", "input-total-transport": null, "input_total_remaining": "1068850.00", "input-previous-balance": "927064", "input-total-commission": "711787", "input-total-commitment": null, "input-return-commission": "828", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "1218850.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": "111585", "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-30 17:11:02',
                'updated_at' => '2025-05-30 17:11:02',
            ),
            97 => 
            array (
                'id' => 98,
                'account_book_id' => 1099,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "dCcZRYOBE7YKnpfS6LSlOFQtZ1d7RfloWF02lf90", "payment": [{"amount": "1000000.00", "method": "3"}], "commission": "209390.00", "commitment": [{"date": "2025-06-16", "amount": "300000.00"}, {"date": "2025-07-10", "amount": "300000.00"}, {"date": "2025-08-13", "amount": "300000.00"}, {"date": "2025-09-17", "amount": "300000.00"}, {"date": "2025-10-15", "amount": "258500.00"}], "closing_date": "2025-05-30", "closing_type": "manual", "input-remaining": "2458500.00", "input-total-paid": "1000000.00", "input-total-expense": null, "input-total-payable": "2667890", "input-total-payment": "473000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "3140890", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": null, "input-total-commission": "3140890", "input-total-commitment": "1458500.00", "input-return-commission": "0", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "2458500.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-30 17:16:46',
                'updated_at' => '2025-05-30 17:16:46',
            ),
            98 => 
            array (
                'id' => 99,
                'account_book_id' => 344,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "dCcZRYOBE7YKnpfS6LSlOFQtZ1d7RfloWF02lf90", "payment": [{"amount": "500000.00", "method": "3"}, {"amount": "100000.00", "method": "3"}], "commission": "53845.00", "commitment": [{"date": "2025-07-16", "amount": "170000.00"}], "closing_date": "2025-05-30", "closing_type": "manual", "input-remaining": "770000.00", "input-total-paid": "600000.00", "input-total-expense": null, "input-total-payable": "823845", "input-total-payment": "310000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "1155945", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": null, "input-total-commission": "1133845", "input-total-commitment": "170000.00", "input-return-commission": "22100", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "770000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-30 20:50:15',
                'updated_at' => '2025-05-30 20:50:15',
            ),
            99 => 
            array (
                'id' => 100,
                'account_book_id' => 384,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "dCcZRYOBE7YKnpfS6LSlOFQtZ1d7RfloWF02lf90", "payment": [{"amount": "1000.00", "method": "1"}, {"amount": "200000.00", "method": "4"}], "commission": "28330.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-05-30", "closing_type": "manual", "input-remaining": "201000.00", "input-total-paid": "201000.00", "input-total-expense": null, "input-total-payable": "229330", "input-total-payment": "175000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "404330", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": null, "input-total-commission": "404330", "input-total-commitment": null, "input-return-commission": "0", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "201000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-30 20:52:35',
                'updated_at' => '2025-05-30 20:52:35',
            ),
            100 => 
            array (
                'id' => 101,
                'account_book_id' => 364,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "MniJIuUHd9Q7z0GCJJHjqWSQMwsXRlM3VWTmptFj", "payment": [{"amount": "100000.00", "method": "7"}, {"amount": "100000.00", "method": "7"}, {"amount": "100000.00", "method": "7"}], "commission": "31900.00", "commitment": [{"date": "2025-07-16", "amount": "194000.00"}], "closing_date": "2025-05-31", "closing_type": "manual", "input-remaining": "494000.00", "input-total-paid": "300000.00", "input-total-expense": null, "input-total-payable": "525900", "input-total-payment": "535000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "1079520", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": null, "input-total-commission": "1060900", "input-total-commitment": "194000.00", "input-return-commission": "18620", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "494000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-31 21:00:48',
                'updated_at' => '2025-05-31 21:00:48',
            ),
            101 => 
            array (
                'id' => 102,
                'account_book_id' => 251,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "MniJIuUHd9Q7z0GCJJHjqWSQMwsXRlM3VWTmptFj", "payment": [{"amount": "500000.00", "method": "10"}], "commission": "0", "commitment": [{"date": "2025-05-10", "amount": "300000.00"}], "closing_date": "2025-05-31", "closing_type": "manual", "input-remaining": "1333784.00", "input-total-paid": "500000.00", "input-total-expense": null, "input-total-payable": "1333784", "input-total-payment": "697300", "input-total-discount": null, "balance_carry_forward": "1", "comminsion-percentage": "0", "input-sale-commission": "1590588", "input-total-transport": null, "input_total_remaining": "833784.00", "input-previous-balance": "440496", "input-total-commission": "1590588", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "1333784.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-31 21:02:43',
                'updated_at' => '2025-05-31 21:02:43',
            ),
            102 => 
            array (
                'id' => 103,
                'account_book_id' => 322,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "MniJIuUHd9Q7z0GCJJHjqWSQMwsXRlM3VWTmptFj", "payment": [{"amount": "100000.00", "method": "1"}, {"amount": "100000.00", "method": "4"}, {"amount": "50000.00", "method": "4"}, {"amount": "50000.00", "method": "4"}], "commission": "0.00", "commitment": [{"date": "2025-05-31", "amount": "0.00"}], "closing_date": "2025-05-31", "closing_type": "manual", "input-remaining": "753313.00", "input-total-paid": "300000.00", "input-total-expense": null, "input-total-payable": "753313", "input-total-payment": "100000", "input-total-discount": null, "balance_carry_forward": "1", "comminsion-percentage": "0", "input-sale-commission": "860536", "input-total-transport": null, "input_total_remaining": "453313.00", "input-previous-balance": null, "input-total-commission": "853313", "input-total-commitment": null, "input-return-commission": "7223", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "753313.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-31 21:42:08',
                'updated_at' => '2025-05-31 21:42:08',
            ),
            103 => 
            array (
                'id' => 104,
                'account_book_id' => 363,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "MniJIuUHd9Q7z0GCJJHjqWSQMwsXRlM3VWTmptFj", "payment": [{"amount": "290000.00", "method": "4"}], "commission": "41485.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-05-31", "closing_type": "manual", "input-remaining": "290000.00", "input-total-paid": "290000.00", "input-total-expense": null, "input-total-payable": "331485", "input-total-payment": "200000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "534265", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": null, "input-total-commission": "531485", "input-total-commitment": null, "input-return-commission": "2780", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "290000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-05-31 22:20:36',
                'updated_at' => '2025-05-31 22:20:36',
            ),
            104 => 
            array (
                'id' => 105,
                'account_book_id' => 360,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "GX2P6XMHFjg6PsTU9KcptprwIwcZVby4V4wJx6pI", "payment": [{"amount": "200000.00", "method": "1"}, {"amount": "200000.00", "method": "1"}], "commission": "0.00", "commitment": [{"date": "2025-08-14", "amount": "100000.00"}, {"date": "2025-09-11", "amount": "100000.00"}, {"date": "2025-10-23", "amount": "100000.00"}, {"date": "2025-10-16", "amount": "100000.00"}, {"date": "2025-10-16", "amount": "100000.00"}, {"date": "2025-11-13", "amount": "91500.00"}], "closing_date": "2025-07-21", "closing_type": "manual", "input-remaining": "991500.00", "input-total-paid": "400000.00", "input-total-expense": null, "input-total-payable": "991500", "input-total-payment": "230000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "1221500", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": "0", "input-total-commission": "1221500", "input-total-commitment": "591500.00", "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "991500.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-07-21 14:10:05',
                'updated_at' => '2025-07-21 14:10:05',
            ),
            105 => 
            array (
                'id' => 106,
                'account_book_id' => 275,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "3000.00", "_token": "vdiaIHbOI3rsD5tyLMYwQLfz8xyaBaW1giEgRO0p", "payment": [{"amount": "300000.00", "method": "10"}, {"amount": "100000.00", "method": "10"}, {"amount": "100000.00", "method": "1"}], "commission": "33620.00", "commitment": [{"date": "2025-08-11", "amount": "0.00"}, {"date": "2025-09-11", "amount": "100000.00"}, {"date": "2025-10-12", "amount": "100000.00"}, {"date": "2025-11-11", "amount": "100000.00"}], "closing_date": "2025-08-11", "closing_type": "manual", "input-remaining": "800000.00", "input-total-paid": "500000.00", "input-total-expense": null, "input-total-payable": "836620", "input-total-payment": "150000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "1031548", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": null, "input-total-commission": "986620", "input-total-commitment": "300000.00", "input-return-commission": "44928", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "803000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-08-11 17:39:36',
                'updated_at' => '2025-08-11 17:39:36',
            ),
            106 => 
            array (
                'id' => 107,
                'account_book_id' => 382,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "295.00", "_token": "hDAVIvhocZNHW9lul48Uj1JYd2Al6cYl03L04R4n", "payment": [{"amount": "30000.00", "method": "1"}, {"amount": "25000.00", "method": "1"}], "commission": "20919.00", "commitment": [{"date": null, "amount": null}], "closing_date": null, "closing_type": "manual", "input-remaining": "55000.00", "input-total-paid": "55000.00", "input-total-expense": null, "input-total-payable": "76214", "input-total-payment": "140000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "237295", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": "0", "input-total-commission": "216214", "input-total-commitment": null, "input-return-commission": "21081", "input-sale-no-commission": "329575", "commision-percentage-show": null, "input-commission-deducted": "55295.00", "input-total-no-commission": null, "input-return-no-commission": "36", "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-08-21 11:59:47',
                'updated_at' => '2025-08-21 11:59:47',
            ),
            107 => 
            array (
                'id' => 108,
                'account_book_id' => 259,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "lNmvMlIOVWyBi0hB9TZxjjnCJLRJGYJQFWXX4ODz", "payment": [{"amount": "280000.00", "method": "11"}, {"amount": "1090000.00", "method": "2"}], "commission": "0", "commitment": [{"date": "2025-09-07", "amount": "50000.00"}, {"date": "2025-09-14", "amount": "50000.00"}, {"date": "2025-09-21", "amount": "50000.00"}, {"date": "2025-09-28", "amount": "100000.00"}, {"date": "2025-10-05", "amount": "100000.00"}, {"date": "2025-10-12", "amount": "50000.00"}, {"date": "2025-10-19", "amount": "50000.00"}, {"date": "2025-10-26", "amount": "50000.00"}, {"date": "2025-11-02", "amount": "50000.00"}, {"date": "2025-11-09", "amount": "50000.00"}, {"date": "2025-11-16", "amount": "100000.00"}, {"date": "2025-11-23", "amount": "100000.00"}, {"date": "2025-11-30", "amount": "100000.00"}, {"date": "2025-12-07", "amount": "200000.00"}, {"date": "2025-12-14", "amount": "200000.00"}, {"date": "2025-12-21", "amount": "200000.00"}, {"date": "2025-12-28", "amount": "200000.00"}, {"date": "2026-01-04", "amount": "100000.00"}, {"date": "2026-01-11", "amount": "100000.00"}, {"date": "2026-01-18", "amount": "100000.00"}, {"date": "2026-01-25", "amount": "100000.00"}, {"date": "2026-02-01", "amount": "100000.00"}], "closing_date": "2025-08-31", "closing_type": "manual", "input-remaining": "4247490.00", "input-total-paid": "1370000.00", "input-total-expense": null, "input-total-payable": "4247490", "input-total-payment": "910000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "5692975", "input-total-transport": null, "input_total_remaining": "677490.00", "input-previous-balance": null, "input-total-commission": "5157490", "input-total-commitment": "2200000.00", "input-return-commission": "535485", "input-sale-no-commission": "7906810", "commision-percentage-show": null, "input-commission-deducted": "4247490.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-08-31 16:51:19',
                'updated_at' => '2025-08-31 16:51:19',
            ),
            108 => 
            array (
                'id' => 109,
                'account_book_id' => 1161,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "yYwStFJfyYhBh6FYHS3KJ1JUu58qlpqbeGAIiZzh", "payment": [{"amount": "500000.00", "method": "4"}, {"amount": "100000.00", "method": "4"}, {"amount": "100000.00", "method": "4"}, {"amount": "100000.00", "method": "1"}], "commission": "78198.00", "commitment": [{"date": "2025-10-15", "amount": "199000.00"}], "closing_date": "2025-09-13", "closing_type": "manual", "input-remaining": "999000.00", "input-total-paid": "800000.00", "input-total-expense": null, "input-total-payable": "1077198", "input-total-payment": "40000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "1117198", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": null, "input-total-commission": "1117198", "input-total-commitment": "199000.00", "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "999000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-09-13 17:02:24',
                'updated_at' => '2025-09-13 17:02:24',
            ),
            109 => 
            array (
                'id' => 110,
                'account_book_id' => 1166,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "2359.00", "_token": "zFJJnywqBtC88o9lLNKJt6vxnxHOaGLXVkWGrbV4", "payment": [{"amount": null, "method": "1"}], "commission": "5752.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-09-15", "closing_type": "manual", "input-remaining": "0.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "8111", "input-total-payment": "280000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "288111", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": "0", "input-total-commission": "288111", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "2359.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-09-15 17:36:35',
                'updated_at' => '2025-09-15 17:36:35',
            ),
            110 => 
            array (
                'id' => 111,
                'account_book_id' => 261,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "zFJJnywqBtC88o9lLNKJt6vxnxHOaGLXVkWGrbV4", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": "2025-09-30", "amount": "7789.00"}], "closing_date": "2025-09-15", "closing_type": "manual", "input-remaining": "7789.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "7789", "input-total-payment": "170000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "177789", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": null, "input-total-commission": "177789", "input-total-commitment": "7789.00", "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "7789.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-09-15 17:39:09',
                'updated_at' => '2025-09-15 17:39:09',
            ),
            111 => 
            array (
                'id' => 112,
                'account_book_id' => 286,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "2Rdb9RQhz84IoykiKJRnlYKZIJKMSI5oUsekEibL", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": "2025-04-29", "amount": "400000.00"}], "closing_date": "2025-09-16", "closing_type": "manual", "input-remaining": "820927.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "820927", "input-total-payment": "1210000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "2030927", "input-total-transport": null, "input_total_remaining": "820927.00", "input-previous-balance": null, "input-total-commission": "2030927", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "820927.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-09-16 20:07:44',
                'updated_at' => '2025-09-16 20:07:44',
            ),
            112 => 
            array (
                'id' => 113,
                'account_book_id' => 1214,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "9sYGfQmn1piacLSiqZH5eEs4445neYqqt2zBkK8X", "payment": [{"amount": "500000.00", "method": "1"}], "commission": "0", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-04-01", "closing_type": "manual", "input-remaining": "1801880.00", "input-total-paid": "500000.00", "input-total-expense": null, "input-total-payable": "1801880", "input-total-payment": "600000", "input-total-discount": null, "balance_carry_forward": "1", "comminsion-percentage": "0", "input-sale-commission": "2469765", "input-total-transport": "3680", "input_total_remaining": "1301880.00", "input-previous-balance": "0", "input-total-commission": "2398200", "input-total-commitment": null, "input-return-commission": "71566", "input-sale-no-commission": "3430230", "commision-percentage-show": null, "input-commission-deducted": "1801880.00", "input-total-no-commission": null, "input-return-no-commission": "99400", "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-04-01 17:51:09',
                'updated_at' => '2025-11-18 17:51:09',
            ),
            113 => 
            array (
                'id' => 114,
                'account_book_id' => 313,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "DJxkVmGljhDJHH90XIMi5iWWwMaT6b4AYlqeo2mg", "payment": [{"amount": "200000.00", "method": "1"}, {"amount": "50000.00", "method": "1"}, {"amount": "50000.00", "method": "1"}, {"amount": "100000.00", "method": "2"}], "commission": "38574.00", "commitment": {"1": {"date": "2026-01-29", "amount": "350000.00"}}, "closing_date": "2025-04-23", "closing_type": "manual", "input-remaining": "750000.00", "input-total-paid": "400000.00", "input-total-expense": "0", "input-total-payable": "788574", "input-total-payment": "0", "input-total-discount": "0", "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "788574", "input-total-transport": "0", "input_total_remaining": "350000.00", "input-previous-balance": "0", "input-total-commission": "788574", "input-total-commitment": "350000.00", "input-return-commission": "0", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "750000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": "0", "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-04-23 14:29:54',
                'updated_at' => '2025-11-27 14:29:54',
            ),
            114 => 
            array (
                'id' => 115,
                'account_book_id' => 371,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0.00", "_token": "6lBYBR705rIHFmkyZpUG4WHB6on25agPIO7pl3Bp", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-04-16", "closing_type": "manual", "input-remaining": "424825.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "424825", "input-total-payment": "500000", "input-total-discount": null, "balance_carry_forward": "1", "comminsion-percentage": "0", "input-sale-commission": "924825", "input-total-transport": null, "input_total_remaining": "424825.00", "input-previous-balance": null, "input-total-commission": "924825", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "424825.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-04-16 11:43:54',
                'updated_at' => '2025-12-07 11:43:54',
            ),
            115 => 
            array (
                'id' => 116,
                'account_book_id' => 353,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "voadAVcPsgLNf04cmoUJ3y2yhoIwkP0Vl1pQVoFD", "payment": [{"amount": "10000.00", "method": "1"}], "commission": "8244.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-12-27", "closing_type": "manual", "input-remaining": "10000.00", "input-total-paid": "10000.00", "input-total-expense": null, "input-total-payable": "18244", "input-total-payment": "380180", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "433955", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": null, "input-total-commission": "398424", "input-total-commitment": null, "input-return-commission": "35531", "input-sale-no-commission": "602715", "commision-percentage-show": null, "input-commission-deducted": "10000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-12-27 16:59:29',
                'updated_at' => '2026-01-04 16:59:29',
            ),
            116 => 
            array (
                'id' => 117,
                'account_book_id' => 244,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "sJhLcsJUwXbMjkIJ9303WSOsMz4DqOT8on4mB3W7", "payment": [{"amount": null, "method": "1"}], "commission": "18488.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-04-14", "closing_type": "manual", "input-remaining": "270000.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "288488", "input-total-payment": "165000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "230234", "input-total-transport": null, "input_total_remaining": "270000.00", "input-previous-balance": "223254", "input-total-commission": "230234", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "270000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-04-14 20:59:54',
                'updated_at' => '2026-01-25 20:59:54',
            ),
            117 => 
            array (
                'id' => 118,
                'account_book_id' => 1258,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "Zc8t7oZtyypj007rkOFdCwJ8HKaYQY7wRqeAcWbO", "payment": [{"amount": "100000.00", "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-04-23", "closing_type": "manual", "input-remaining": "1079675.00", "input-total-paid": "100000.00", "input-total-expense": null, "input-total-payable": "1079675", "input-total-payment": "100000", "input-total-discount": null, "balance_carry_forward": "1", "comminsion-percentage": "0", "input-sale-commission": "362794", "input-total-transport": null, "input_total_remaining": "979675.00", "input-previous-balance": "836213", "input-total-commission": "343462", "input-total-commitment": null, "input-return-commission": "19332", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "1079675.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-04-23 16:49:14',
                'updated_at' => '2026-03-09 16:49:14',
            ),
            118 => 
            array (
                'id' => 119,
                'account_book_id' => 348,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "Ft1CSSOy3D13ef5ewnbgunrIUfziaaQEHjSGVfTm", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "532660.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "532660", "input-total-payment": "300000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "430923", "input-total-transport": null, "input_total_remaining": "532660.00", "input-previous-balance": "401736", "input-total-commission": "430923", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "532660.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 17:11:31',
                'updated_at' => '2026-03-09 17:11:31',
            ),
            119 => 
            array (
                'id' => 120,
                'account_book_id' => 315,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "Ft1CSSOy3D13ef5ewnbgunrIUfziaaQEHjSGVfTm", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-04-23", "closing_type": "manual", "input-remaining": "117000.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "117000", "input-total-payment": "20000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "0", "input-total-transport": null, "input_total_remaining": "117000.00", "input-previous-balance": "137073", "input-total-commission": "0", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "117000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-04-23 17:18:22',
                'updated_at' => '2026-03-09 17:18:22',
            ),
            120 => 
            array (
                'id' => 121,
                'account_book_id' => 352,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "Ft1CSSOy3D13ef5ewnbgunrIUfziaaQEHjSGVfTm", "payment": [{"amount": "500000.00", "method": "4"}], "commission": "139128.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-04-23", "closing_type": "manual", "input-remaining": "3152000.00", "input-total-paid": "500000.00", "input-total-expense": null, "input-total-payable": "3291128", "input-total-payment": "190000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "3481128", "input-total-transport": null, "input_total_remaining": "2652000.00", "input-previous-balance": "0", "input-total-commission": "3481128", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "3152000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-04-23 17:24:20',
                'updated_at' => '2026-03-09 17:24:20',
            ),
            121 => 
            array (
                'id' => 122,
                'account_book_id' => 245,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "10000.00", "_token": "9YkrM4cJxJo2AcTwfho4idUeRjjP2CgGpMigOnAW", "payment": [{"amount": null, "method": "1"}], "commission": "0", "commitment": [{"date": "2025-04-29", "amount": "100000.00"}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "732355.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "742355", "input-total-payment": "350000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "1101272", "input-total-transport": null, "input_total_remaining": "732355.00", "input-previous-balance": null, "input-total-commission": "1092355", "input-total-commitment": null, "input-return-commission": "8917", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "742355.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 13:53:15',
                'updated_at' => '2026-03-11 13:53:15',
            ),
            122 => 
            array (
                'id' => 123,
                'account_book_id' => 246,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "9YkrM4cJxJo2AcTwfho4idUeRjjP2CgGpMigOnAW", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "1863911.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "1863911", "input-total-payment": "740000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "1768500", "input-total-transport": null, "input_total_remaining": "1863911.00", "input-previous-balance": "835411", "input-total-commission": "1768500", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "1863911.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 14:00:09',
                'updated_at' => '2026-03-11 14:00:09',
            ),
            123 => 
            array (
                'id' => 124,
                'account_book_id' => 248,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "9YkrM4cJxJo2AcTwfho4idUeRjjP2CgGpMigOnAW", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "405279.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "405279", "input-total-payment": null, "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "0", "input-total-transport": null, "input_total_remaining": "405279.00", "input-previous-balance": "405279", "input-total-commission": "0", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "405279.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 14:05:12',
                'updated_at' => '2026-03-11 14:05:12',
            ),
            124 => 
            array (
                'id' => 125,
                'account_book_id' => 337,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "9YkrM4cJxJo2AcTwfho4idUeRjjP2CgGpMigOnAW", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "144570.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "144570", "input-total-payment": "165000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "309570", "input-total-transport": null, "input_total_remaining": "144570.00", "input-previous-balance": null, "input-total-commission": "309570", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "144570.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 14:09:02',
                'updated_at' => '2026-03-11 14:09:02',
            ),
            125 => 
            array (
                'id' => 126,
                'account_book_id' => 257,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "6000.00", "_token": "9YkrM4cJxJo2AcTwfho4idUeRjjP2CgGpMigOnAW", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "1025300.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "1031300", "input-total-payment": "460000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "771206", "input-total-transport": null, "input_total_remaining": "1025300.00", "input-previous-balance": "731729", "input-total-commission": "759571", "input-total-commitment": null, "input-return-commission": "11635", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "1031300.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 14:52:49',
                'updated_at' => '2026-03-11 14:52:49',
            ),
            126 => 
            array (
                'id' => 127,
                'account_book_id' => 258,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "9YkrM4cJxJo2AcTwfho4idUeRjjP2CgGpMigOnAW", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "156586.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "156586", "input-total-payment": null, "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "0", "input-total-transport": null, "input_total_remaining": "156586.00", "input-previous-balance": "156586", "input-total-commission": "0", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "156586.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 15:14:26',
                'updated_at' => '2026-03-11 15:14:26',
            ),
            127 => 
            array (
                'id' => 128,
                'account_book_id' => 262,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "10000.00", "_token": "9YkrM4cJxJo2AcTwfho4idUeRjjP2CgGpMigOnAW", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": "2025-05-05", "amount": "100000.00"}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "1234471.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "1244471", "input-total-payment": "520000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "1205496", "input-total-transport": null, "input_total_remaining": "1234471.00", "input-previous-balance": "558975", "input-total-commission": "1205496", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "1244471.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 15:46:32',
                'updated_at' => '2026-03-11 15:46:32',
            ),
            128 => 
            array (
                'id' => 129,
                'account_book_id' => 1325,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "9YkrM4cJxJo2AcTwfho4idUeRjjP2CgGpMigOnAW", "payment": [{"amount": null, "method": "1"}], "commission": "6232.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "100000.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "106232", "input-total-payment": null, "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "106232", "input-total-transport": null, "input_total_remaining": "100000.00", "input-previous-balance": null, "input-total-commission": "106232", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "100000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 16:00:25',
                'updated_at' => '2026-03-11 16:00:25',
            ),
            129 => 
            array (
                'id' => 130,
                'account_book_id' => 269,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "9YkrM4cJxJo2AcTwfho4idUeRjjP2CgGpMigOnAW", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "1114313.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "1114313", "input-total-payment": null, "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "0", "input-total-transport": null, "input_total_remaining": "1114313.00", "input-previous-balance": "1114313", "input-total-commission": "0", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "1114313.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 16:53:28',
                'updated_at' => '2026-03-11 16:53:28',
            ),
            130 => 
            array (
                'id' => 131,
                'account_book_id' => 270,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "9YkrM4cJxJo2AcTwfho4idUeRjjP2CgGpMigOnAW", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "0.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": null, "input-total-payment": "134000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "177400", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": "90912", "input-total-commission": "135560", "input-total-commitment": null, "input-return-commission": "41840", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "0.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 17:00:26',
                'updated_at' => '2026-03-11 17:00:26',
            ),
            131 => 
            array (
                'id' => 132,
                'account_book_id' => 272,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "9YkrM4cJxJo2AcTwfho4idUeRjjP2CgGpMigOnAW", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "178740.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "178740", "input-total-payment": "100000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "89425", "input-total-transport": null, "input_total_remaining": "178740.00", "input-previous-balance": "202902", "input-total-commission": "75840", "input-total-commitment": null, "input-return-commission": "13587", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "178740.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 17:10:45',
                'updated_at' => '2026-03-11 17:10:45',
            ),
            132 => 
            array (
                'id' => 133,
                'account_book_id' => 276,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "9YkrM4cJxJo2AcTwfho4idUeRjjP2CgGpMigOnAW", "payment": [{"amount": null, "method": "1"}], "commission": "5050.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "161000.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "166050", "input-total-payment": "100000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "266050", "input-total-transport": null, "input_total_remaining": "161000.00", "input-previous-balance": null, "input-total-commission": "266050", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "161000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 17:33:59',
                'updated_at' => '2026-03-11 17:33:59',
            ),
            133 => 
            array (
                'id' => 134,
                'account_book_id' => 1331,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "9YkrM4cJxJo2AcTwfho4idUeRjjP2CgGpMigOnAW", "payment": [{"amount": null, "method": "1"}], "commission": "61113.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "1744322.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "1805435", "input-total-payment": "225000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "2044585", "input-total-transport": null, "input_total_remaining": "1744322.00", "input-previous-balance": null, "input-total-commission": "2030435", "input-total-commitment": null, "input-return-commission": "14150", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "1744322.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 17:46:13',
                'updated_at' => '2026-03-11 17:46:13',
            ),
            134 => 
            array (
                'id' => 135,
                'account_book_id' => 284,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "VAeHXhxw5RGvttg9llYZIRxE8cCLrBiFWbtvNrmw", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "602154.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "602154", "input-total-payment": "220000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "841381", "input-total-transport": null, "input_total_remaining": "602154.00", "input-previous-balance": null, "input-total-commission": "822154", "input-total-commitment": null, "input-return-commission": "19227", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "602154.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 20:17:32',
                'updated_at' => '2026-03-11 20:17:32',
            ),
            135 => 
            array (
                'id' => 136,
                'account_book_id' => 288,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "VAeHXhxw5RGvttg9llYZIRxE8cCLrBiFWbtvNrmw", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "450000.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "450000", "input-total-payment": "270000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "720825", "input-total-transport": null, "input_total_remaining": "450000.00", "input-previous-balance": null, "input-total-commission": "720090", "input-total-commitment": null, "input-return-commission": "735", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "450000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 20:48:16',
                'updated_at' => '2026-03-11 20:48:16',
            ),
            136 => 
            array (
                'id' => 137,
                'account_book_id' => 290,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "VAeHXhxw5RGvttg9llYZIRxE8cCLrBiFWbtvNrmw", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "465447.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "465447", "input-total-payment": null, "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": null, "input-total-transport": null, "input_total_remaining": "465447.00", "input-previous-balance": "465447", "input-total-commission": "0", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "465447.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 20:53:36',
                'updated_at' => '2026-03-11 20:53:36',
            ),
            137 => 
            array (
                'id' => 138,
                'account_book_id' => 297,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "VAeHXhxw5RGvttg9llYZIRxE8cCLrBiFWbtvNrmw", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "360000.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "360000", "input-total-payment": null, "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": null, "input-total-transport": null, "input_total_remaining": "360000.00", "input-previous-balance": "360000", "input-total-commission": "0", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "360000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 21:00:08',
                'updated_at' => '2026-03-11 21:00:08',
            ),
            138 => 
            array (
                'id' => 139,
                'account_book_id' => 299,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "VAeHXhxw5RGvttg9llYZIRxE8cCLrBiFWbtvNrmw", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": "2025-05-13", "amount": "1000000.00"}, {"date": "2025-05-20", "amount": "300000.00"}, {"date": "2025-06-15", "amount": "500000.00"}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "1404266.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "1404266", "input-total-payment": "420000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "1540815", "input-total-transport": null, "input_total_remaining": "1404266.00", "input-previous-balance": null, "input-total-commission": "1824266", "input-total-commitment": null, "input-return-commission": "9641", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "1404266.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": "293092", "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 21:17:41',
                'updated_at' => '2026-03-11 21:17:41',
            ),
            139 => 
            array (
                'id' => 140,
                'account_book_id' => 1338,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "8000.00", "_token": "VAeHXhxw5RGvttg9llYZIRxE8cCLrBiFWbtvNrmw", "payment": [{"amount": null, "method": "1"}], "commission": "110853.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "315000.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "433853", "input-total-payment": "1155000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "1650553", "input-total-transport": null, "input_total_remaining": "315000.00", "input-previous-balance": null, "input-total-commission": "1588853", "input-total-commitment": null, "input-return-commission": "61700", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "323000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 21:31:52',
                'updated_at' => '2026-03-11 21:31:52',
            ),
            140 => 
            array (
                'id' => 141,
                'account_book_id' => 304,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "VAeHXhxw5RGvttg9llYZIRxE8cCLrBiFWbtvNrmw", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "185532.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "185532", "input-total-payment": null, "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": null, "input-total-transport": null, "input_total_remaining": "185532.00", "input-previous-balance": "185532", "input-total-commission": "0", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "185532.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 21:35:03',
                'updated_at' => '2026-03-11 21:35:03',
            ),
            141 => 
            array (
                'id' => 142,
                'account_book_id' => 1341,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "VAeHXhxw5RGvttg9llYZIRxE8cCLrBiFWbtvNrmw", "payment": [{"amount": null, "method": "1"}], "commission": "131857.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "1792185.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "1924042", "input-total-payment": "300000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "2239286", "input-total-transport": null, "input_total_remaining": "1792185.00", "input-previous-balance": null, "input-total-commission": "2224042", "input-total-commitment": null, "input-return-commission": "15244", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "1792185.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 21:47:11',
                'updated_at' => '2026-03-11 21:47:11',
            ),
            142 => 
            array (
                'id' => 143,
                'account_book_id' => 307,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "VAeHXhxw5RGvttg9llYZIRxE8cCLrBiFWbtvNrmw", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "620548.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "620548", "input-total-payment": "200000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "832622", "input-total-transport": null, "input_total_remaining": "620548.00", "input-previous-balance": null, "input-total-commission": "820548", "input-total-commitment": null, "input-return-commission": "12075", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "620548.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 22:02:04',
                'updated_at' => '2026-03-11 22:02:04',
            ),
            143 => 
            array (
                'id' => 144,
                'account_book_id' => 308,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "VAeHXhxw5RGvttg9llYZIRxE8cCLrBiFWbtvNrmw", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "71474.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "71474", "input-total-payment": null, "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": null, "input-total-transport": null, "input_total_remaining": "71474.00", "input-previous-balance": "71474", "input-total-commission": "0", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "71474.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 22:04:21',
                'updated_at' => '2026-03-11 22:04:21',
            ),
            144 => 
            array (
                'id' => 145,
                'account_book_id' => 309,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "VAeHXhxw5RGvttg9llYZIRxE8cCLrBiFWbtvNrmw", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "70913.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "70913", "input-total-payment": null, "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": null, "input-total-transport": null, "input_total_remaining": "70913.00", "input-previous-balance": "70913", "input-total-commission": "0", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "70913.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 22:05:18',
                'updated_at' => '2026-03-11 22:05:18',
            ),
            145 => 
            array (
                'id' => 146,
                'account_book_id' => 319,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "VAeHXhxw5RGvttg9llYZIRxE8cCLrBiFWbtvNrmw", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "45503.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "45503", "input-total-payment": null, "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": null, "input-total-transport": null, "input_total_remaining": "45503.00", "input-previous-balance": "45503", "input-total-commission": "0", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "45503.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 22:24:11',
                'updated_at' => '2026-03-11 22:24:11',
            ),
            146 => 
            array (
                'id' => 147,
                'account_book_id' => 320,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "VAeHXhxw5RGvttg9llYZIRxE8cCLrBiFWbtvNrmw", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "629659.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "629659", "input-total-payment": "260000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "643118", "input-total-transport": null, "input_total_remaining": "629659.00", "input-previous-balance": "249056", "input-total-commission": "640600", "input-total-commitment": null, "input-return-commission": "2518", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "629659.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 22:26:25',
                'updated_at' => '2026-03-11 22:26:25',
            ),
            147 => 
            array (
                'id' => 148,
                'account_book_id' => 323,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "MGoFLUitOVj7ltV0P8q9nQZKdgUFEmVVMI3ShZ2j", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "79111.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "79111", "input-total-payment": null, "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": null, "input-total-transport": null, "input_total_remaining": "79111.00", "input-previous-balance": "79111", "input-total-commission": "0", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "79111.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 13:56:26',
                'updated_at' => '2026-03-12 13:56:26',
            ),
            148 => 
            array (
                'id' => 149,
                'account_book_id' => 325,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "MGoFLUitOVj7ltV0P8q9nQZKdgUFEmVVMI3ShZ2j", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": "2025-04-29", "amount": "50000.00"}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "302278.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "302278", "input-total-payment": null, "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": null, "input-total-transport": null, "input_total_remaining": "302278.00", "input-previous-balance": "302278", "input-total-commission": "0", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "302278.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 14:49:13',
                'updated_at' => '2026-03-12 14:49:13',
            ),
            149 => 
            array (
                'id' => 150,
                'account_book_id' => 326,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "MGoFLUitOVj7ltV0P8q9nQZKdgUFEmVVMI3ShZ2j", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "237057.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "237057", "input-total-payment": "60000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": null, "input-total-transport": null, "input_total_remaining": "237057.00", "input-previous-balance": "297057", "input-total-commission": "0", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "237057.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 15:09:52',
                'updated_at' => '2026-03-12 15:09:52',
            ),
            150 => 
            array (
                'id' => 151,
                'account_book_id' => 329,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "MGoFLUitOVj7ltV0P8q9nQZKdgUFEmVVMI3ShZ2j", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "726000.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "726000", "input-total-payment": null, "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": null, "input-total-transport": null, "input_total_remaining": "726000.00", "input-previous-balance": "726000", "input-total-commission": "0", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "726000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 15:38:08',
                'updated_at' => '2026-03-12 15:38:08',
            ),
            151 => 
            array (
                'id' => 152,
                'account_book_id' => 334,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "vWLNjqFWWmGbpJrD4qdtLWAE9OhjddZnvJSkQfKx", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "217388.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "217388", "input-total-payment": null, "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": null, "input-total-transport": null, "input_total_remaining": "217388.00", "input-previous-balance": "217388", "input-total-commission": "0", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "217388.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 16:34:25',
                'updated_at' => '2026-03-12 16:34:25',
            ),
            152 => 
            array (
                'id' => 153,
                'account_book_id' => 336,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "vWLNjqFWWmGbpJrD4qdtLWAE9OhjddZnvJSkQfKx", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "235355.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "235355", "input-total-payment": "50000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "235523", "input-total-transport": null, "input_total_remaining": "235355.00", "input-previous-balance": "51467", "input-total-commission": "233888", "input-total-commitment": null, "input-return-commission": "1635", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "235355.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 16:36:59',
                'updated_at' => '2026-03-12 16:36:59',
            ),
            153 => 
            array (
                'id' => 154,
                'account_book_id' => 339,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "vWLNjqFWWmGbpJrD4qdtLWAE9OhjddZnvJSkQfKx", "payment": [{"amount": null, "method": "1"}], "commission": "8862.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "12000.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "20862", "input-total-payment": null, "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": null, "input-total-transport": null, "input_total_remaining": "12000.00", "input-previous-balance": "20862", "input-total-commission": "0", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "12000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 16:47:28',
                'updated_at' => '2026-03-12 16:47:28',
            ),
            154 => 
            array (
                'id' => 155,
                'account_book_id' => 342,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "vWLNjqFWWmGbpJrD4qdtLWAE9OhjddZnvJSkQfKx", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "131032.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "131032", "input-total-payment": null, "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": null, "input-total-transport": null, "input_total_remaining": "131032.00", "input-previous-balance": "131032", "input-total-commission": "0", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "131032.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 16:56:11',
                'updated_at' => '2026-03-12 16:56:11',
            ),
            155 => 
            array (
                'id' => 156,
                'account_book_id' => 345,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "vWLNjqFWWmGbpJrD4qdtLWAE9OhjddZnvJSkQfKx", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "213207.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "213207", "input-total-payment": "100000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "243195", "input-total-transport": null, "input_total_remaining": "213207.00", "input-previous-balance": "72946", "input-total-commission": "240261", "input-total-commitment": null, "input-return-commission": "2934", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "213207.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 17:04:10',
                'updated_at' => '2026-03-12 17:04:10',
            ),
            156 => 
            array (
                'id' => 157,
                'account_book_id' => 346,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "vWLNjqFWWmGbpJrD4qdtLWAE9OhjddZnvJSkQfKx", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "499836.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "499836", "input-total-payment": null, "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": null, "input-total-transport": null, "input_total_remaining": "499836.00", "input-previous-balance": "499836", "input-total-commission": "0", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "499836.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 17:06:14',
                'updated_at' => '2026-03-12 17:06:14',
            ),
            157 => 
            array (
                'id' => 158,
                'account_book_id' => 1359,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "vWLNjqFWWmGbpJrD4qdtLWAE9OhjddZnvJSkQfKx", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "1146638.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "1146638", "input-total-payment": "200000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "1348038", "input-total-transport": null, "input_total_remaining": "1146638.00", "input-previous-balance": null, "input-total-commission": "1346638", "input-total-commitment": null, "input-return-commission": "1400", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "1146638.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 17:37:58',
                'updated_at' => '2026-03-12 17:37:58',
            ),
            158 => 
            array (
                'id' => 159,
                'account_book_id' => 1361,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "vWLNjqFWWmGbpJrD4qdtLWAE9OhjddZnvJSkQfKx", "payment": [{"amount": null, "method": "1"}], "commission": "94638.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "1052000.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "1146638", "input-total-payment": "200000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "1348038", "input-total-transport": null, "input_total_remaining": "1052000.00", "input-previous-balance": null, "input-total-commission": "1346638", "input-total-commitment": null, "input-return-commission": "1400", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "1052000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 17:41:25',
                'updated_at' => '2026-03-12 17:41:25',
            ),
            159 => 
            array (
                'id' => 160,
                'account_book_id' => 358,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "3570.00", "_token": "vWLNjqFWWmGbpJrD4qdtLWAE9OhjddZnvJSkQfKx", "payment": [{"amount": null, "method": "1"}], "commission": "18545.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "120000.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "142115", "input-total-payment": "100000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "263628", "input-total-transport": null, "input_total_remaining": "120000.00", "input-previous-balance": null, "input-total-commission": "242115", "input-total-commitment": null, "input-return-commission": "21513", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "123570.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 19:42:22',
                'updated_at' => '2026-03-12 19:42:22',
            ),
            160 => 
            array (
                'id' => 161,
                'account_book_id' => 366,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "vWLNjqFWWmGbpJrD4qdtLWAE9OhjddZnvJSkQfKx", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "603810.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "603810", "input-total-payment": "401000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "1004810", "input-total-transport": null, "input_total_remaining": "603810.00", "input-previous-balance": null, "input-total-commission": "1004810", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "603810.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 20:35:03',
                'updated_at' => '2026-03-12 20:35:03',
            ),
            161 => 
            array (
                'id' => 162,
                'account_book_id' => 367,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "vWLNjqFWWmGbpJrD4qdtLWAE9OhjddZnvJSkQfKx", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "132370.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "132370", "input-total-payment": "200000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "332370", "input-total-transport": null, "input_total_remaining": "132370.00", "input-previous-balance": null, "input-total-commission": "332370", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "132370.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 20:45:24',
                'updated_at' => '2026-03-12 20:45:24',
            ),
            162 => 
            array (
                'id' => 163,
                'account_book_id' => 1366,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "vWLNjqFWWmGbpJrD4qdtLWAE9OhjddZnvJSkQfKx", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "55526.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "55526", "input-total-payment": "30000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "78926", "input-total-transport": null, "input_total_remaining": "55526.00", "input-previous-balance": "6600", "input-total-commission": "78926", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "55526.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 21:00:57',
                'updated_at' => '2026-03-12 21:00:57',
            ),
            163 => 
            array (
                'id' => 164,
                'account_book_id' => 1368,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "vWLNjqFWWmGbpJrD4qdtLWAE9OhjddZnvJSkQfKx", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "41648.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "41648", "input-total-payment": "40000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "81648", "input-total-transport": null, "input_total_remaining": "41648.00", "input-previous-balance": null, "input-total-commission": "81648", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "41648.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 21:08:58',
                'updated_at' => '2026-03-12 21:08:58',
            ),
            164 => 
            array (
                'id' => 165,
                'account_book_id' => 369,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "vWLNjqFWWmGbpJrD4qdtLWAE9OhjddZnvJSkQfKx", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "65103.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "65103", "input-total-payment": null, "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "83657", "input-total-transport": null, "input_total_remaining": "65103.00", "input-previous-balance": null, "input-total-commission": "65103", "input-total-commitment": null, "input-return-commission": "18554", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "65103.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 21:11:17',
                'updated_at' => '2026-03-12 21:11:17',
            ),
            165 => 
            array (
                'id' => 166,
                'account_book_id' => 374,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "vWLNjqFWWmGbpJrD4qdtLWAE9OhjddZnvJSkQfKx", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "0.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "0", "input-total-payment": "124916", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "147060", "input-total-transport": null, "input_total_remaining": "0.00", "input-previous-balance": null, "input-total-commission": "124916", "input-total-commitment": null, "input-return-commission": "22144", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "0.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 21:35:18',
                'updated_at' => '2026-03-12 21:35:19',
            ),
            166 => 
            array (
                'id' => 167,
                'account_book_id' => 378,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "vWLNjqFWWmGbpJrD4qdtLWAE9OhjddZnvJSkQfKx", "payment": [{"amount": null, "method": "1"}], "commission": "10860.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "144000.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "154860", "input-total-payment": "110000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "264860", "input-total-transport": null, "input_total_remaining": "144000.00", "input-previous-balance": null, "input-total-commission": "264860", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "144000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 21:57:28',
                'updated_at' => '2026-03-12 21:57:28',
            ),
            167 => 
            array (
                'id' => 168,
                'account_book_id' => 381,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "vWLNjqFWWmGbpJrD4qdtLWAE9OhjddZnvJSkQfKx", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "18036.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "18036", "input-total-payment": null, "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "18036", "input-total-transport": null, "input_total_remaining": "18036.00", "input-previous-balance": null, "input-total-commission": "18036", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "18036.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 22:02:12',
                'updated_at' => '2026-03-12 22:02:12',
            ),
            168 => 
            array (
                'id' => 169,
                'account_book_id' => 383,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "vWLNjqFWWmGbpJrD4qdtLWAE9OhjddZnvJSkQfKx", "payment": [{"amount": null, "method": "1"}], "commission": "30065.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "489000.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "519065", "input-total-payment": "490000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "1011225", "input-total-transport": null, "input_total_remaining": "489000.00", "input-previous-balance": null, "input-total-commission": "1009065", "input-total-commitment": null, "input-return-commission": "2160", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "489000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 22:07:23',
                'updated_at' => '2026-03-12 22:07:23',
            ),
            169 => 
            array (
                'id' => 170,
                'account_book_id' => 1375,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "vWLNjqFWWmGbpJrD4qdtLWAE9OhjddZnvJSkQfKx", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "58648.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "58648", "input-total-payment": "50000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "108648", "input-total-transport": null, "input_total_remaining": "58648.00", "input-previous-balance": null, "input-total-commission": "108648", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "58648.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 22:23:25',
                'updated_at' => '2026-03-12 22:23:25',
            ),
            170 => 
            array (
                'id' => 171,
                'account_book_id' => 1377,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "vWLNjqFWWmGbpJrD4qdtLWAE9OhjddZnvJSkQfKx", "payment": [{"amount": null, "method": "1"}], "commission": "15260.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "210000.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "225260", "input-total-payment": "50000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "275260", "input-total-transport": null, "input_total_remaining": "210000.00", "input-previous-balance": null, "input-total-commission": "275260", "input-total-commitment": null, "input-return-commission": null, "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "210000.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 22:31:46',
                'updated_at' => '2026-03-12 22:31:46',
            ),
            171 => 
            array (
                'id' => 172,
                'account_book_id' => 1380,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "QmH7ioFzV6qDVQWlg6bvU1uAoI8SHF36sy3rgIcd", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "9470.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "9470", "input-total-payment": "134000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "177400", "input-total-transport": null, "input_total_remaining": "9470.00", "input-previous-balance": "90910", "input-total-commission": "133560", "input-total-commitment": null, "input-return-commission": "41839", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "9470.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 22:50:57',
                'updated_at' => '2026-03-14 22:50:57',
            ),
            172 => 
            array (
                'id' => 173,
                'account_book_id' => 1382,
                'account_type' => 'retail-store',
                'closing_data' => '{"staff": "0", "_token": "gEnf1rnH9HJE1kroewrRae0t9ESKwTROPuZkyi1x", "payment": [{"amount": null, "method": "1"}], "commission": "0.00", "commitment": [{"date": null, "amount": null}], "closing_date": "2025-03-31", "closing_type": "manual", "input-remaining": "92473.00", "input-total-paid": null, "input-total-expense": null, "input-total-payable": "92473", "input-total-payment": "134000", "input-total-discount": null, "balance_carry_forward": "0", "comminsion-percentage": "0", "input-sale-commission": "177400", "input-total-transport": null, "input_total_remaining": "92473.00", "input-previous-balance": "90912", "input-total-commission": "135661", "input-total-commitment": null, "input-return-commission": "41839", "input-sale-no-commission": null, "commision-percentage-show": null, "input-commission-deducted": "92473.00", "input-total-no-commission": null, "input-return-no-commission": null, "staff-comminsion-percentage": "0", "staff-commision-percentage-show": null, "input-discount-product-sale-commission": null, "input-discount-product-sale-no-commission": null}',
                'created_at' => '2025-03-31 16:21:38',
                'updated_at' => '2026-03-15 16:21:38',
            ),
        ));
        
        
    }
}