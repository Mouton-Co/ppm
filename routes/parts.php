<?php

use App\Http\Controllers\PartsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Parts routes
|--------------------------------------------------------------------------
*/

Route::prefix('parts')->group(function () {
    Route::get('/procurement', [PartsController::class, 'index'])->name('parts.procurement.index');
    Route::get('/warehouse', [PartsController::class, 'warehouseIndex'])->name('parts.warehouse.index');
    Route::post('update/{id}', [PartsController::class, 'update'])->name('parts.update');
    Route::post('update-checkbox/{id}', [PartsController::class, 'updateCheckbox'])->name('parts.update-checkbox');
    Route::post('autofill-suppliers', [PartsController::class, 'autofillSuppliers'])->name('parts.autofill-suppliers');
    Route::post('generate-po-numbers', [PartsController::class, 'generatePoNumbers'])
        ->name('parts.generate-po-numbers');
    Route::get('mark-as', [PartsController::class, 'markAs'])->name('parts.mark-as');
    Route::post('search-replace-po', [PartsController::class, 'searchReplacePo'])->name('parts.search-replace-po');
    Route::get('unselect', [PartsController::class, 'unselect'])->name('parts.unselect');
});
