<?php

use Illuminate\Support\Facades\Route;

Route::resource('autofill-suppliers', \App\Http\Controllers\AutofillSupplierController::class)
    ->except(['show']);
Route::post('autofill-suppliers/restore/{id}', [\App\Http\Controllers\AutofillSupplierController::class, 'restore'])
    ->name('autofill-suppliers.restore');
Route::post('autofill-suppliers/trash/{id}', [\App\Http\Controllers\AutofillSupplierController::class, 'trash'])
    ->name('autofill-suppliers.trash');
