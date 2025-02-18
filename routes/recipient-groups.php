<?php

use App\Http\Controllers\RecipientGroupController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Recipient Group Routes
|--------------------------------------------------------------------------
*/

Route::resource('recipient-groups', RecipientGroupController::class)->except(['show']);
Route::post('recipient-groups/restore/{id}', [RecipientGroupController::class, 'restore'])->name('recipient-groups.restore');
Route::post('recipient-groups/trash/{id}', [RecipientGroupController::class, 'trash'])->name('recipient-groups.trash');
Route::post('recipient-groups/export', [RecipientGroupController::class, 'export'])->name('recipient-groups.export');
