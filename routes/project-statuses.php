<?php

use App\Http\Controllers\ProjectStatusController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Project status routes
|--------------------------------------------------------------------------
*/

Route::resource('project-statuses', ProjectStatusController::class)->except(['show']);
