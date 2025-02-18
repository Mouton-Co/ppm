<?php

use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Role routes
|--------------------------------------------------------------------------
*/

Route::post('roles/export', [RoleController::class, 'export'])->name('roles.export');
Route::resource('roles', RoleController::class)->except(['show']);
