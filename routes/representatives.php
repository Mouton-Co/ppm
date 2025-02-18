<?php

use App\Http\Controllers\RepresentativeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Representative routes
|--------------------------------------------------------------------------
*/

Route::resource('representatives', RepresentativeController::class)->except(['show']);
Route::post('representatives/restore/{id}', [RepresentativeController::class, 'restore'])->name('representatives.restore');
Route::post('representatives/trash/{id}', [RepresentativeController::class, 'trash'])->name('representatives.trash');
Route::post('representatives/export', [RepresentativeController::class, 'export'])->name('representatives.export');
