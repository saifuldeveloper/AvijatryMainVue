<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\SizeController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\AssetBookController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\LiabilitiesController;
use App\Http\Controllers\ZakatController;
use App\Http\Controllers\FactoryController;
use App\Http\Controllers\AccountBookController;
use App\Http\Controllers\GiftController;
use App\Http\Controllers\GiftSupplierController;
use App\Http\Controllers\GiftPurchaseController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\RetailStoreController;
use App\Http\Controllers\RetailStoreExpenseController;
use App\Http\Controllers\ShoeController;
use App\Http\Controllers\InventoryCheckController;
use App\Http\Controllers\InventoryCheckEntryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\WasteController;
use Inertia\Inertia;

use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Custom Profile Routes
    Route::get('user/profile', [ProfileController::class, 'profile'])->name('user.profile');
    Route::post('user/profile/update', [ProfileController::class, 'profileUpdate'])->name('user.profile.update');

    // Custom Password Change Routes
    Route::get('change/password', [ProfileController::class, 'changePasswordGet'])->name('change.password');
    Route::post('change/password/update', [ProfileController::class, 'changePasswordUpdate'])->name('change.password.update');

    // GET Logout Route
    Route::get('logout', [ProfileController::class, 'logout'])->name('app.logout');

    // Settings Routes
    Route::prefix('setting')->group(function () {
        Route::get('/users', [SettingController::class, 'users'])->name('setting.users');
        Route::post('/user/store', [SettingController::class, 'userStore'])->name('setting.user.store');
        Route::post('/user/update/{user}', [SettingController::class, 'userUpdate'])->name('setting.user.update');
        Route::delete('/user/delete/{user}', [SettingController::class, 'userDelete'])->name('setting.user.delete');
        Route::post('/user/restore/{id}', [SettingController::class, 'userRestore'])->name('setting.user.restore');
        Route::delete('/user/force-delete/{id}', [SettingController::class, 'userForceDelete'])->name('setting.user.force-delete');

        // role permission
        Route::get('/roles', [SettingController::class, 'roles'])->name('setting.roles');
        Route::post('/role/store', [SettingController::class, 'roleStore'])->name('setting.role.store');
        Route::post('/role/update/{role}', [SettingController::class, 'roleUpdate'])->name('setting.role.update');
        Route::delete('/role/delete/{role}', [SettingController::class, 'roleDelete'])->name('setting.role.delete');
    });

    // Category, Color, Size Routes
    Route::resource('category', CategoryController::class)->except(['show']);
    Route::delete('category/{id}/force-delete', [CategoryController::class, 'forceDelete'])->name('category.forceDelete');
    Route::post('category/{id}/restore', [CategoryController::class, 'restore'])->name('category.restore');
    Route::get('get/subcategory', [CategoryController::class, 'getSubCategory'])->name('get.subcategory');

    Route::resource('color', ColorController::class)->except(['show']);
    Route::delete('color/{id}/force-delete', [ColorController::class, 'forceDelete'])->name('color.forceDelete');
    Route::post('color/{id}/restore', [ColorController::class, 'restore'])->name('color.restore');

    Route::resource('size', SizeController::class)->except(['show']);
    Route::delete('size/{id}/force-delete', [SizeController::class, 'forceDelete'])->name('size.forceDelete');
    Route::post('size/{id}/restore', [SizeController::class, 'restore'])->name('size.restore');

    // Language switcher route
    Route::get('/change-language', function () {
        $lang = request('lang');
        if (in_array($lang, ['en', 'bn'])) {
            Session::put('locale', $lang);
        }
        return redirect()->back();
    })->name('change.language');

    // Theme toggle route
    Route::post('/toggle-theme', function () {
        $newTheme = session('theme', 'light') === 'dark' ? 'light' : 'dark';
        session(['theme' => $newTheme]);
        return back();
    })->name('theme.toggle');

    // Activity Log & Trash list routes
    Route::get('activity-log', [ActivityLogController::class, 'index'])->name('activity.log');
    Route::get('trash-list', [ActivityLogController::class, 'trashList'])->name('trash.list');
    Route::get('/view-soft-delete-activity-log/{activity}', [ActivityLogController::class, 'viewSoftDeleteActivityLog'])->name('view.soft-delete.activity-log');

    // Notes Routes
    Route::resource('notes', NoteController::class);
    Route::post('notes/{id}/restore', [NoteController::class, 'restore'])->name('notes.restore');
    Route::delete('notes/{id}/force-delete', [NoteController::class, 'forceDelete'])->name('notes.forceDelete');

    // Asset Book / Asset Account Routes
    Route::resource('asset-book', AssetBookController::class);
    Route::post('asset-book/{id}/restore', [AssetBookController::class, 'restore'])->name('asset.types.restore');
    Route::delete('asset-book/{id}/force-delete', [AssetBookController::class, 'forceDelete'])->name('asset.types.forceDelete');

    // Assets Routes
    Route::resource('assets', AssetController::class);
    Route::post('assets/{id}/restore', [AssetController::class, 'restore'])->name('assets.restore');
    Route::delete('assets/{id}/force-delete', [AssetController::class, 'forceDelete'])->name('assets.forceDelete');

    // Zakat Routes
    Route::get('zakat', [ZakatController::class, 'zakatpage'])->name('zakat.page');
    Route::post('zakat/store', [ZakatController::class, 'zakatStore'])->name('zakat.store');
    Route::get('zakat/caculate/list', [ZakatController::class, 'zakatCalculateList'])->name('zakat.calculate.list');
    Route::delete('zakat/caculate/destroy/{id}', [ZakatController::class, 'zakatCalculateDestroy'])->name('zakat.calculate.destroy');
    Route::post('zakat/caculate/restore/{id}', [ZakatController::class, 'zakatCalculateRestore'])->name('zakat.calculate.restore');
    Route::delete('zakat/caculate/force-delete/{id}', [ZakatController::class, 'zakatCalculateForceDelete'])->name('zakat.calculate.forceDelete');

    // Liabilities Routes
    Route::get('get-liabilities', [LiabilitiesController::class, 'getLiabilities'])->name('get-liabilities');
    Route::resource('liabilities', LiabilitiesController::class);
    Route::post('liabilities/{id}/restore', [LiabilitiesController::class, 'restore'])->name('liabilities.restore');
    Route::delete('liabilities/{id}/force-delete', [LiabilitiesController::class, 'forceDelete'])->name('liabilities.forceDelete');

    Route::get('liabilities/entry/create', [LiabilitiesController::class, 'entry'])->name('liabilities.entry.create');
    Route::post('liabilities/entry/store', [LiabilitiesController::class, 'entryStore'])->name('liabilities.entry.store');
    Route::get('liabilities/entry/edit/{id}', [LiabilitiesController::class, 'entryEdit'])->name('liabilities.entry.edit');
    Route::put('liabilities/entry/update/{id}', [LiabilitiesController::class, 'entryUpdate'])->name('liabilities.entry.update');
    Route::delete('liabilities/entry/destory/{id}', [LiabilitiesController::class, 'entryDestroy'])->name('liabilities.entry.destroy');
    Route::post('liabilities/entry/restore/{id}', [LiabilitiesController::class, 'entryRestore'])->name('liabilities.entry.restore');
    Route::delete('liabilities/entry/force-delete/{id}', [LiabilitiesController::class, 'entryForceDelete'])->name('liabilities.entry.forcedelete');

    // Factory/Supplier Routes
    Route::get('get-factories', [FactoryController::class, 'getFactories'])->name('get-factories');
    Route::resource('factory', FactoryController::class);
    Route::post('factory/{id}/restore', [FactoryController::class, 'restore'])->name('factory.restore');
    Route::delete('factory/{id}/force-delete', [FactoryController::class, 'forceDelete'])->name('factory.forceDelete');
    Route::post('factory/{factory}/closing', [FactoryController::class, 'closing'])->name('factory.closing');

    // Retail Store Routes
    Route::resource('retail-store', RetailStoreController::class);
    Route::post('retail-store/{id}/restore', [RetailStoreController::class, 'restore'])->name('retail-store.restore');
    Route::delete('retail-store/{id}/force-delete', [RetailStoreController::class, 'forceDelete'])->name('retail-store.forceDelete');

    // Retail Store Expense Routes
    Route::post('retail-store-expense', [RetailStoreExpenseController::class, 'store'])->name('retail-store-expense.store');
    Route::delete('retail-store-expense/{id}', [RetailStoreExpenseController::class, 'delete'])->name('retail-store-expense.delete');
    Route::post('retail-store-expense/{id}/restore', [RetailStoreExpenseController::class, 'restore'])->name('retail-store-expense.restore');
    Route::delete('retail-store-expense/{id}/force-delete', [RetailStoreExpenseController::class, 'forceDelete'])->name('retail-store-expense.forceDelete');

    // Factory Account Book / Ledger Routes
    Route::get('account-book/{accountBook}', [AccountBookController::class, 'show'])->name('account-book.show');
    Route::get('account-book/{accountBook}/closing', [AccountBookController::class, 'closingPage'])->name('account-book.closing');
    Route::post('account-book/{accountBook}/closing-store', [AccountBookController::class, 'closing'])->name('account-book.closing-store');
    Route::get('account-book/{accountBook}/forward-balance', [AccountBookController::class, 'forwardBalance'])->name('account-book.forward-balance');

    // Factory Entry Routes
    Route::post('factory/entry/store', [FactoryController::class, 'entryStore'])->name('factory.entry.store');
    Route::put('factory/entry/update/{id}', [FactoryController::class, 'entryUpdate'])->name('factory.entry.update');
    Route::delete('factory/entry/destroy/{id}', [FactoryController::class, 'entryDestroy'])->name('factory.entry.destroy');
    Route::post('factory/entry/restore/{id}', [FactoryController::class, 'entryRestore'])->name('factory.entry.restore');
    Route::delete('factory/entry/force-delete/{id}', [FactoryController::class, 'entryForceDelete'])->name('factory.entry.forcedelete');

    // Gift Routes
    Route::resource('gift', GiftController::class)->except(['show']);
    Route::get('gift-transaction/{id}', [GiftController::class, 'transactionShow'])->name('gift.transaction');
    Route::delete('gift/{gift}/force-delete', [GiftController::class, 'forceDelete'])->name('gift.forceDelete');
    Route::post('gift/{gift}/restore', [GiftController::class, 'restore'])->name('gift.restore');

    // Gift Supplier Routes
    Route::get('/get-gift-suppliers-list', [GiftSupplierController::class, 'getGiftSupplierslist'])->name('get-gift-suppliers-list');
    Route::get('/get-gift-suppliers', [GiftSupplierController::class, 'getGiftSuppliers'])->name('get-gift-suppliers');
    Route::resource('gift-supplier', GiftSupplierController::class);
    Route::delete('gift-supplier/{gift_supplier}/force-delete', [GiftSupplierController::class, 'forceDelete'])->name('gift-supplier.forceDelete');
    Route::post('gift-supplier/{gift_supplier}/restore', [GiftSupplierController::class, 'restore'])->name('gift-supplier.restore');
    Route::get('gift-supplier/entry/date/currection/{id}', [GiftSupplierController::class, 'entryDateCurrection'])->name('gift-supplier.entry.date.currection');
    Route::get('/gift-suppliers', [GiftSupplierController::class, 'index'])->name('gift-supplier.index');
    Route::get('gift-supplier/account-book/entry-list/{accountBookId}', [GiftSupplierController::class, 'giftSupplierEntryList'])->name('gift-supplier.account-book.entry.list');

    // Gift Purchase Routes
    Route::resource('gift-purchase', GiftPurchaseController::class);
    Route::post('gift-purchases/restore/{id}', [GiftPurchaseController::class, 'restore'])->name('gift_purchases.restore');
    Route::delete('gift-purchases/force-delete/{id}', [GiftPurchaseController::class, 'forceDelete'])->name('gift_purchases.forceDelete');

    // Purchase Routes
    Route::resource('purchase', \App\Http\Controllers\PurchaseController::class);
    Route::get('purchase/{purchase}/barcode', [\App\Http\Controllers\PurchaseController::class, 'barcode'])->name('purchase.barcode');
    Route::post('purchase/restore/{id}', [\App\Http\Controllers\PurchaseController::class, 'restore'])->name('purchase.restore');
    Route::delete('purchase/force-delete/{id}', [\App\Http\Controllers\PurchaseController::class, 'forceDelete'])->name('purchase.forceDelete');
    Route::get('get/category-sizes', [\App\Http\Controllers\SizeController::class, 'getCategorySizes'])->name('get.category-sizes');

    // Invoice / Sale Routes
    Route::resource('invoice', InvoiceController::class)->except(['index']);
    Route::get('invoice/{invoice}/force-delete', [InvoiceController::class, 'forceDelete'])->name('invoice.forceDelete');
    Route::post('invoice/restore/{id}', [InvoiceController::class, 'restore'])->name('invoice.restore');

    // Bank Account Routes
    Route::resource('bank-account', BankAccountController::class);
    Route::delete('bank-account/{bank_account}/force-delete', [BankAccountController::class, 'forceDelete'])->name('bank-account.forceDelete');
    Route::post('bank-account/{bank_account}/restore', [BankAccountController::class, 'restore'])->name('bank-account.restore');
    Route::get('bank-account/entry/date/currection/{id}', [BankAccountController::class, 'entryDateCurrection'])->name('bank-account.entry.date.currection');

    // Shoe Routes
    Route::resource('shoe', ShoeController::class);
    Route::get('shoe/show/{shoe}', [ShoeController::class, 'ajaxShow'])->name('ajax.shoe.show');
    Route::post('shoe/download', [ShoeController::class, 'download'])->name('show.download');
    Route::post('shoe/download/delete', [ShoeController::class, 'downloadDeleted'])->name('show.download.deleted');
    Route::get('barcode', [ShoeController::class, 'barcodePage'])->name('shoe.barcode-page');
    Route::post('barcode', [ShoeController::class, 'barcode'])->name('shoe.barcode');
    Route::get('shoe/barcode/tr', [ShoeController::class, 'barcodeTr'])->name('tr.barcode');

    // Inventory Check Routes
    Route::resource('inventory-check', InventoryCheckController::class)->only(['index', 'create', 'store', 'show']);
    Route::get('inventory-check/{id}/entries-data', [InventoryCheckController::class, 'getEntriesData'])->name('inventory-check.entries-data');
    Route::get('inventory-check/{id}/remaining-data', [InventoryCheckController::class, 'getRemainingData'])->name('inventory-check.remaining-data');
    Route::resource('inventory-check-entry', InventoryCheckEntryController::class)->only(['store']);
    Route::get('inventory-check/{inventoryCheck}/complete', [InventoryCheckController::class, 'complete'])->name('inventory-check.complete');
    Route::get('inventory-check/{inventoryCheck}/resume', [InventoryCheckController::class, 'resume'])->name('inventory-check.resume');
    Route::post('inventory-check/{inventoryCheck}/resolve', [InventoryCheckController::class, 'resolve'])->name('inventory-check.resolve');

    // Waste Routes
    Route::controller(WasteController::class)->group(function () {
        Route::get('waste/shoes', 'shoePage')->name('waste.shoes-page');
        Route::get('waste/shoes-list', 'wasteShoesList')->name('waste.shoes.list');
        Route::post('waste/shoes', 'shoe')->name('waste.shoes');
        Route::post('waste/shoe/soft-delete/{id}', 'wasteShoetSoftDelete')->name('waste.shoe.softDelete');
        Route::get('waste/shoe/restore/{id}', 'wasteShoeRestore')->name('waste.shoe.restore');
        Route::get('waste/shoe/force-delete/{id}', 'wasteShoeForceDelete')->name('waste.shoe.forceDelete');

        Route::get('waste/gifts', 'giftPage')->name('waste.gifts-page');
        Route::get('waste/gifts/data', 'giftPageData')->name('waste.gifts.data');
        Route::post('waste/gifts', 'gift')->name('waste.gifts');
        Route::post('waste/gifts/soft-delete/{gift}', 'wasteGiftSoftDelete')->name('waste.gift.softDelete');
        Route::post('waste/gifts/restore/{gift}', 'wasteGiftRestore')->name('waste.gift.restore');
        Route::post('waste/gifts/force-delete/{gift}', 'wasteGiftForceDelete')->name('waste.gift.forceDelete');

        Route::get('party-gift', 'partyWasteGift')->name('waste.party.gift');
        Route::get('party-shoe-gift-data', 'partyShoeGiftData')->name('party.shoe.gift.data');
        Route::get('party-gift-data', 'partyGiftData')->name('party.gift.data');
    });
});

Route::get('images/{template}/{filename}', function ($template, $filename) {
    $path = public_path("images/{$template}/{$filename}");
    if (file_exists($path)) {
        return response()->file($path);
    }
    return response()->file(public_path('img/shoe.png'));
})->name('imagecache');

require __DIR__.'/auth.php';

