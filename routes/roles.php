<?php

use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Role routes
|--------------------------------------------------------------------------
*/

Route::resource('roles', RoleController::class)->except(['show']);
