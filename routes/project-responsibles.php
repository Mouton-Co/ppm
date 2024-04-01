<?php

use App\Http\Controllers\ProjectResponsibleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Project responsibles routes
|--------------------------------------------------------------------------
*/

Route::resource('project-responsibles', ProjectResponsibleController::class)->except(['show']);

