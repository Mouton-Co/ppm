<?php

use App\Http\Controllers\ProjectStatusController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Project status routes
|--------------------------------------------------------------------------
*/

Route::resource('project-statuses', ProjectStatusController::class)->except(['show']);
Route::post('project-statuses/restore/{id}', [ProjectStatusController::class, 'restore'])->name('project-statuses.restore');
Route::post('project-statuses/trash/{id}', [ProjectStatusController::class, 'trash'])->name('project-statuses.trash');
