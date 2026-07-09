<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \Schema::disableForeignKeyConstraints();

        $this->call([
            // Authentication, Roles & Permissions
            RolesTableSeeder::class,
            PermissionsTableSeeder::class,
            ModelHasRolesTableSeeder::class,
            ModelHasPermissionsTableSeeder::class,
            RoleHasPermissionsTableSeeder::class,
            RolesAndPermissionsSeeder::class,
            UsersTableSeeder::class,
            PasswordResetsTableSeeder::class,

            // Core Configurations
            CategoriesTableSeeder::class,
            ColorsTableSeeder::class,
            SizesTableSeeder::class,
            NotesTableSeeder::class,
            MediaTableSeeder::class,
            MessagesTableSeeder::class,
            ActivityLogTableSeeder::class,

            // Accounts & Books
            AccountBooksTableSeeder::class,
            AccountsTableSeeder::class,

            // Bank Accounts
            BankAccountsTableSeeder::class,
            BankAccountEntriesTableSeeder::class,

            // Cheques
            ChequesTableSeeder::class,
            ChequeAccountEntriesTableSeeder::class,

            // Shoes & Inventory
            // ShoesTableSeeder::class,
            // ShoeToSizesTableSeeder::class,
            // InventoriesTableSeeder::class,

            // Purchases & Factory
            FactoriesTableSeeder::class,
            FactoryAccountEntriesTableSeeder::class,
            // PurchasesTableSeeder::class,
            // PurchaseEntriesTableSeeder::class,
            // ReturnToFactoriesTableSeeder::class,
            // ReturnToFactoryEntriesTableSeeder::class,

            // Invoices & Retail Store
            // InvoicesTableSeeder::class,
            // InvoiceEntriesTableSeeder::class,
            RetailStoresTableSeeder::class,
            // RetailStoreShoesTableSeeder::class,
            RetailStoreExpensesTableSeeder::class,
            RetailStoreAccountEntriesTableSeeder::class,
            // ReturnFromRetailEntriesTableSeeder::class,
            // ReturnToRetailersTableSeeder::class,
            // WasteEntriesTableSeeder::class,

            // Commitments
            CommitmentsTableSeeder::class,
            CommitmentLogsTableSeeder::class,

            // Employees & Salary
            EmployeesTableSeeder::class,
            EmployeeAccountEntriesTableSeeder::class,
            EmployeeSalariesTableSeeder::class,
            SalaryHistoriesTableSeeder::class,

            // Expenses & Loans
            ExpensesTableSeeder::class,
            ExpenseAccountEntriesTableSeeder::class,
            LoansTableSeeder::class,
            LoanAccountEntriesTableSeeder::class,

            // Assets & Liabilities
            AssetBooksTableSeeder::class,
            AssetsTableSeeder::class,
            AssetTypesTableSeeder::class,
            LiabilitiesTableSeeder::class,
            LiabilitiesEntriesTableSeeder::class,

            // Gift System
            GiftPurchasesTableSeeder::class,
            GiftSupplierAccountEntriesTableSeeder::class,
            GiftSuppliersTableSeeder::class,
            GiftTransactionsTableSeeder::class,
            GiftTypesTableSeeder::class,
            GiftsTableSeeder::class,

            // Transactions & History
            TransactionsTableSeeder::class,
            TransactionHistoriesTableSeeder::class,

            // Adjustments & Closings
            // AdjustmentEntriesTableSeeder::class,
            // InventoryChecksTableSeeder::class,
            // InventoryCheckEntriesTableSeeder::class,
            // InventoryCheckDetailsTableSeeder::class,
            ClosingPeriodsTableSeeder::class,
            ManualClosingsTableSeeder::class,
            ZakatsTableSeeder::class,
        ]);

        \Schema::enableForeignKeyConstraints();
    }
}

