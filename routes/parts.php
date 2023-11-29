<?php

use App\Http\Controllers\PartsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Parts routes
|--------------------------------------------------------------------------
*/

Route::prefix('parts')->group(function () {
    Route::get('/', [PartsController::class, 'index'])->name('parts.index');
    Route::post('update/{id}', [PartsController::class, 'update'])->name('parts.update');
    Route::post('update-checkbox/{id}', [PartsController::class, 'updateCheckbox'])->name('parts.update-checkbox');
    Route::post('generate-po-numbers', [PartsController::class, 'generatePoNumbers'])
        ->name('parts.generate-po-numbers');
});
