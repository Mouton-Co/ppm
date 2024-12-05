<?php

use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Supplier routes
|--------------------------------------------------------------------------
*/

Route::resource('suppliers', SupplierController::class)->except(['show']);
Route::post('suppliers/restore/{id}', [SupplierController::class, 'restore'])->name('suppliers.restore');
Route::post('suppliers/trash/{id}', [SupplierController::class, 'trash'])->name('suppliers.trash');
Route::post('suppliers/update-checkbox/{id}', [SupplierController::class, 'updateCheckbox'])->name('suppliers.update-checkbox');
