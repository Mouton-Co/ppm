<?php

use Illuminate\Support\Facades\Route;

Route::resource('process-types', \App\Http\Controllers\ProcessTypeController::class)
    ->except(['show']);
Route::post('process-types/restore/{id}', [\App\Http\Controllers\ProcessTypeController::class, 'restore'])
    ->name('process-types.restore');
Route::post('process-types/trash/{id}', [\App\Http\Controllers\ProcessTypeController::class, 'trash'])
    ->name('process-types.trash');
Route::post('process-types/export', [\App\Http\Controllers\ProcessTypeController::class, 'export'])
    ->name('process-types.export');
