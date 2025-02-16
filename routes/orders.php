<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Order routes
|--------------------------------------------------------------------------
*/

Route::prefix('orders')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/generate', [OrderController::class, 'generate'])->name('orders.generate');
    Route::post('/delete/{id}', [OrderController::class, 'destroy'])->name('orders.delete');
    Route::post('/restore/{id}', [OrderController::class, 'restore'])->name('orders.restore');
    Route::post('/trash/{id}', [OrderController::class, 'trash'])->name('orders.trash');
    Route::post('/update-date/{id}', [OrderController::class, 'updateDate'])->name('orders.update-date');
    Route::post('/export', [OrderController::class, 'export'])->name('orders.export');
});
