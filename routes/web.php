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
});

require __DIR__.'/auth.php';

