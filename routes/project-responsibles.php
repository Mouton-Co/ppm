<?php

use App\Http\Controllers\ProjectResponsibleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Project responsibles routes
|--------------------------------------------------------------------------
*/

Route::resource('project-responsibles', ProjectResponsibleController::class)->except(['show']);
Route::post('project-responsibles/restore/{id}', [ProjectResponsibleController::class, 'restore'])->name('project-responsibles.restore');
Route::post('project-responsibles/trash/{id}', [ProjectResponsibleController::class, 'trash'])->name('project-responsibles.trash');
