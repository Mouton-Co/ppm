<?php

use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Project routes
|--------------------------------------------------------------------------
*/

Route::resource('projects', ProjectController::class)->except(['show']);
Route::get('project/coc/{machine_nr}', [ProjectController::class, 'generateCoc'])->name('projects.coc');
Route::post('project/update/ajax/{id}', [ProjectController::class, 'updateAjax'])->name('projects.update.ajax');
Route::post('project/unlink/{id}', [ProjectController::class, 'unlink'])->name('projects.unlink');
Route::post('project/link/{id}', [ProjectController::class, 'link'])->name('projects.link');
Route::post('project/send-update/{id}', [ProjectController::class, 'sendUpdate'])->name('projects.send-update');
Route::post('projects/restore/{id}', [ProjectController::class, 'restore'])->name('projects.restore');
Route::post('projecst/trash/{id}', [ProjectController::class, 'trash'])->name('projects.trash');
