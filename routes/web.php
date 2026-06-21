<?php

use App\Http\Controllers\ProfileController;
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
});

require __DIR__.'/auth.php';
