<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use App\Models\Account;
use App\Models\AccountBook;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankAccountsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        // Disable foreign key checks to prevent deletion constraint failures
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('bank_accounts')->truncate();
        
        // Also clean up accounts and account_books that are type 'bank-account'
        DB::table('accounts')->where('type', 'bank-account')->delete();
        DB::table('account_books')->where('account_type', 'bank-account')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $accountsData = [
            [
                'id' => 1,
                'account_no' => 'cash',
                'bank' => 'ক্যাশ',
                'branch' => '-',
                'old_balance' => 0.00,
                'created_at' => '2024-05-01 03:57:12',
                'updated_at' => '2024-05-01 03:57:33',
            ],
            [
                'id' => 2,
                'account_no' => '0939902002916',
                'bank' => 'Pubali Bank',
                'branch' => 'Dhaka Stadium',
                'old_balance' => 0.00,
                'created_at' => '2024-05-01 03:58:08',
                'updated_at' => '2025-04-16 01:29:53',
            ],
            [
                'id' => 3,
                'account_no' => '0100006439181',
                'bank' => 'Janata Bank',
                'branch' => 'Ramna',
                'old_balance' => 0.00,
                'created_at' => '2024-05-01 03:58:31',
                'updated_at' => '2025-04-16 01:28:29',
            ],
            [
                'id' => 4,
                'account_no' => '20501570100160310',
                'bank' => 'Islami Bank',
                'branch' => 'Ramna',
                'old_balance' => 0.00,
                'created_at' => '2024-05-01 03:59:26',
                'updated_at' => '2025-04-16 01:32:26',
            ],
            [
                'id' => 5,
                'account_no' => '1041510046780',
                'bank' => 'Dutch Bangla Bank',
                'branch' => 'Nawabpur Road',
                'old_balance' => 0.00,
                'created_at' => '2024-05-01 03:59:45',
                'updated_at' => '2025-04-16 01:38:27',
            ],
            [
                'id' => 6,
                'account_no' => '1264324585001',
                'bank' => 'City Bank',
                'branch' => 'Principal Office',
                'old_balance' => 0.00,
                'created_at' => '2024-05-01 03:59:56',
                'updated_at' => '2025-04-16 01:31:21',
            ],
            [
                'id' => 7,
                'account_no' => '1623200017925',
                'bank' => 'Sonali Bank',
                'branch' => 'Nagar Bhaban',
                'old_balance' => 0.00,
                'created_at' => '2025-04-16 01:27:13',
                'updated_at' => '2025-04-16 01:27:13',
            ],
            [
                'id' => 8,
                'account_no' => '0059020008927',
                'bank' => 'Rupali Bank',
                'branch' => 'Ramna',
                'old_balance' => 0.00,
                'created_at' => '2025-04-16 01:33:22',
                'updated_at' => '2025-04-16 01:33:22',
            ],
            [
                'id' => 9,
                'account_no' => '2064853050001',
                'bank' => 'Brac Bank',
                'branch' => 'Nawabpur Road',
                'old_balance' => 0.00,
                'created_at' => '2025-04-16 01:35:01',
                'updated_at' => '2025-04-16 01:38:14',
            ],
            [
                'id' => 10,
                'account_no' => '0200000214954',
                'bank' => 'Agrani Bank',
                'branch' => 'B B Avenue',
                'old_balance' => 0.00,
                'created_at' => '2025-04-16 01:35:54',
                'updated_at' => '2025-04-16 01:35:54',
            ],
            [
                'id' => 11,
                'account_no' => '135412200213062',
                'bank' => 'Uttara Bank',
                'branch' => 'Fulbaria',
                'old_balance' => 0.00,
                'created_at' => '2025-05-25 15:21:14',
                'updated_at' => '2025-05-25 15:21:14',
            ],
            [
                'id' => 12,
                'account_no' => '12345678',
                'bank' => 'Meghna Bank',
                'branch' => 'Uttara',
                'old_balance' => 0.00,
                'created_at' => '2025-12-04 15:53:23',
                'updated_at' => '2025-12-04 15:53:23',
            ],
        ];

        foreach ($accountsData as $data) {
            // Save BankAccount
            $bankAccount = new BankAccount();
            $bankAccount->id = $data['id'];
            $bankAccount->account_no = $data['account_no'];
            $bankAccount->bank = $data['bank'];
            $bankAccount->branch = $data['branch'];
            $bankAccount->old_balance = $data['old_balance'];
            $bankAccount->created_at = $data['created_at'];
            $bankAccount->updated_at = $data['updated_at'];
            $bankAccount->save();

            // Save Account
            $account = new Account();
            $account->id = $data['id'];
            $account->type = 'bank-account';
            $account->name = $data['bank'];
            $account->created_at = $data['created_at'];
            $account->updated_at = $data['updated_at'];
            $account->save();

            // Save AccountBook
            $accountBook = new AccountBook();
            $accountBook->account_id = $data['id'];
            $accountBook->account_type = 'bank-account';
            $accountBook->created_at = $data['created_at'];
            $accountBook->updated_at = $data['updated_at'];
            $accountBook->save();
        }
    }
}
