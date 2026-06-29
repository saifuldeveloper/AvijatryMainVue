<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        \Schema::disableForeignKeyConstraints();

        $this->call([
            UsersTableSeeder::class,
            CategoriesTableSeeder::class,
            ColorsTableSeeder::class,
            SizesTableSeeder::class,
            NotesTableSeeder::class,
            AssetBooksTableSeeder::class,
            AssetsTableSeeder::class,
            FactoriesTableSeeder::class,
            BankAccountsTableSeeder::class,
            
            GiftPurchasesTableSeeder::class,
            GiftSupplierAccountEntriesTableSeeder::class,
            GiftSuppliersTableSeeder::class,
            GiftTransactionsTableSeeder::class,
            GiftTypesTableSeeder::class,
            GiftsTableSeeder::class,
        ]);

        \Schema::enableForeignKeyConstraints();
    }
}

