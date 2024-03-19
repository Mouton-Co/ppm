<?php

use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Project routes
|--------------------------------------------------------------------------
*/

Route::resource('projects', ProjectController::class)->except(['show']);
