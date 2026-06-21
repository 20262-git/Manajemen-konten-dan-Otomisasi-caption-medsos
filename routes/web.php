<?php

use App\Http\Controllers\CaptionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');        // Halaman depan sebelum login
})->name('welcome');

Route::middleware('auth')->group(function () {
    
    // Dashboard Super Simpel untuk Test
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');
    Route::resource('schedule', ScheduleController::class);

    Route::get('/caption', function () {
        return view('caption.generator');
    })->name('caption.generator');

    Route::post('/caption/generate', [CaptionController::class, 'generate'])
         ->name('caption.generate');
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';