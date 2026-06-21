<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ActivityLogController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
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

        Route::get('/roles', [SettingController::class, 'roles'])->name('setting.roles');
        Route::post('/role/store', [SettingController::class, 'roleStore'])->name('setting.role.store');
        Route::post('/role/update/{role}', [SettingController::class, 'roleUpdate'])->name('setting.role.update');
        Route::delete('/role/delete/{role}', [SettingController::class, 'roleDelete'])->name('setting.role.delete');
    });

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
});

require __DIR__.'/auth.php';
