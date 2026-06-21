<?php

use App\Http\Controllers\CaptionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])
         ->name('dashboard');

    Route::resource('schedule', ScheduleController::class);

    Route::get('/caption', function () {
        return view('caption.generator');
    })->name('caption.generator');

    Route::post('/caption/generate', [CaptionController::class, 'generate'])
         ->name('caption.generate');
});

require __DIR__.'/auth.php';